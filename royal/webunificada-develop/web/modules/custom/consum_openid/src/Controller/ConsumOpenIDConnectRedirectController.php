<?php


namespace Drupal\consum_openid\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Url;
use Drupal\openid_connect\Controller\OpenIDConnectRedirectController;
use Drupal\openid_connect\Plugin\OpenIDConnectClientInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ConsumOpenIDConnectRedirectController extends OpenIDConnectRedirectController
{

  /**
   * Redirect.
   *
   * @param string $client_name
   *   The client name.
   *
   * @return RedirectResponse
   *   The redirect response starting the authentication request.
   */
  public function authenticate($client_name) {
    $query = $this->requestStack->getCurrentRequest()->query;

    // Delete the state token, since it's already been confirmed.
    unset($_SESSION['openid_connect_state']);

    // Get parameters from the session, and then clean up.
    $parameters = [
      'destination' => 'user',
      'op' => 'login',
      'connect_uid' => NULL,
    ];
    foreach ($parameters as $key => $default) {
      if (isset($_SESSION['openid_connect_' . $key])) {
        $parameters[$key] = $_SESSION['openid_connect_' . $key];
        unset($_SESSION['openid_connect_' . $key]);
      }
    }
    $destination = $parameters['destination'];

    $configuration = $this->config('openid_connect.settings.' . $client_name)
      ->get('settings');
    $client = $this->pluginManager->createInstance(
      $client_name,
      $configuration
    );
    if (!$query->get('error') && (!($client instanceof OpenIDConnectClientInterface) || !$query->get('code'))) {
      // In case we don't have an error, but the client could not be loaded or
      // there is no state token specified, the URI is probably being visited
      // outside of the login flow.
      throw new NotFoundHttpException();
    }

    $provider_param = ['@provider' => $client->getPluginDefinition()['label']];

    if ($query->get('error')) {
      if (in_array($query->get('error'), [
        'interaction_required',
        'login_required',
        'account_selection_required',
        'consent_required',
      ])) {
        // If we have an one of the above errors, that means the user hasn't
        // granted the authorization for the claims.
        $this->messenger()->addWarning($this->t('Logging in with @provider has been canceled.', $provider_param));
      }
      else {
        // Any other error should be logged. E.g. invalid scope.
        $variables = [
          '@error' => $query->get('error'),
          '@details' => $query->get('error_description') ? $query->get('error_description') : $this->t('Unknown error.'),
        ];
        $message = 'Authorization failed: @error. Details: @details';
        $this->loggerFactory->get('openid_connect_' . $client_name)->error($message, $variables);
        $this->messenger()->addError($this->t('Could not authenticate with @provider.', $provider_param));
      }
    }
    else {
      // Process the login or connect operations.
      $tokens = $client->retrieveTokens($query->get('code'));
      if ($tokens) {
        $_SESSION["id_token"] = $tokens["id_token"];
        $_SESSION["access_token"] = $tokens["access_token"];
        $_SESSION["session_state"] = $query->get('session_state');

        if ($configuration['debug_mode'] === true) {
          $variables = [
            '@id_token' => $tokens["id_token"],
          ];
          $this->loggerFactory->get('openid_connect_consum')
            ->notice('authenticate: id_token = @id_token', $variables);
        }

        if ($parameters['op'] === 'login') {
          $success = $this->openIDConnect->completeAuthorization($client, $tokens, $destination);

          if (!$success) {
            // Check Drupal user register settings before saving.
            $register = $this->config('user.settings')->get('register');
            // Respect possible override from OpenID-Connect settings.
            $register_override = $this->config('openid_connect.settings')
              ->get('override_registration_settings');
            if ($register === UserInterface::REGISTER_ADMINISTRATORS_ONLY && $register_override) {
              $register = UserInterface::REGISTER_VISITORS;
            }

            switch ($register) {
              case UserInterface::REGISTER_ADMINISTRATORS_ONLY:
              case UserInterface::REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL:
                // Skip creating an error message, as completeAuthorization
                // already added according messages.
                break;

              default:
                $this->messenger()->addError($this->t('Logging in with @provider could not be completed due to an error.', $provider_param));
                break;
            }
          }
        }
        elseif ($parameters['op'] === 'connect' && $parameters['connect_uid'] === $this->currentUser->id()) {
          $success = $this->openIDConnect->connectCurrentUser($client, $tokens);
          if ($success) {
            $this->messenger()->addMessage($this->t('Account successfully connected with @provider.', $provider_param));
          }
          else {
            $this->messenger()->addError($this->t('Connecting with @provider could not be completed due to an error.', $provider_param));
          }
        }
      }
    }

    // It's possible to set 'options' in the redirect destination.
    if (is_array($destination)) {
      $query = !empty($destination[1]['query']) ? '?' . $destination[1]['query'] : '';
      $redirect = Url::fromUri('internal:/' . ltrim($destination[0], '/') . $query)->toString();
    }
    else {
      $redirect = Url::fromUri('internal:/' . ltrim($destination, '/'))->toString();
    }
    return new RedirectResponse($redirect);
  }

  /**
   * Logs the current user out.
   *
   * @return RedirectResponse
   *   A redirection to home page.
   */
  public function logout() {
    if ($this->currentUser()->isAuthenticated()) {
      user_logout();
    }
    return $this->redirect('<front>');
  }

  /**
   * Access callback: Logout Success.
   *
   * @return AccessResultInterface
   */
  public function accessLogout() {
    return AccessResult::allowed();
  }

}
