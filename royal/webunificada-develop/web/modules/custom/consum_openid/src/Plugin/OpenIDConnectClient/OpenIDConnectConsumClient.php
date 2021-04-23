<?php

namespace Drupal\consum_openid\Plugin\OpenIDConnectClient;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\openid_connect\Annotation\OpenIDConnectClient;
use Drupal\openid_connect\Plugin\OpenIDConnectClientBase;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * OpenID Connect client for Consum.
 *
 * Used primarily to login to Drupal sites powered by oauth2_server or PHP
 * sites powered by oauth2-server-php.
 *
 * @OpenIDConnectClient(
 *   id = "consum",
 *   label = @Translation("Consum IAM")
 * )
 */
class OpenIDConnectConsumClient extends OpenIDConnectClientBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'authorization_endpoint' => '',
      'token_endpoint' => '',
      'userinfo_endpoint' => '',
      'create_user_endpoint' => '',
      'create_user_callback' => '',
      'cancel_user_endpoint' => '',
      'logout_user_endpoint' => '',
      'logout_redirect_uri' => '',
      'check_session_endpoint' => '',
      'check_session_enable' => '',
      'claims_emailaddress_uri' => '',
      'claims_identificationNumber_uri' => '',
      'iam_user' => '',
      'iam_password' => '',
      'guzzle_verify' => '',
      'guzzle_cert_path' => '',
      'debug_mode' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['authorization_endpoint'] = [
      '#title' => $this->t('Authorization endpoint'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['authorization_endpoint'],
    ];
    $form['token_endpoint'] = [
      '#title' => $this->t('Token endpoint'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['token_endpoint'],
    ];
    $form['userinfo_endpoint'] = [
      '#title' => $this->t('UserInfo endpoint'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['userinfo_endpoint'],
    ];

    $form['create_user_endpoint'] = [
      '#title' => $this->t('Create user endpoint'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['create_user_endpoint'],
    ];

    $form['create_user_callback'] = [
      '#title' => $this->t('Create user callback'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['create_user_callback'],
    ];

    $form['cancel_user_endpoint'] = [
      '#title' => $this->t('Cancel/Update user endpoint'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['cancel_user_endpoint'],
    ];

    $form['logout_user_endpoint'] = [
      '#title' => $this->t('Logout user endpoint'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['logout_user_endpoint'],
    ];

    $form['logout_redirect_uri'] = [
      '#title' => $this->t('Logout redirect URL'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['logout_redirect_uri'],
    ];

    $form['claims_emailaddress_uri'] = [
      '#title' => $this->t('claims email url'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['claims_emailaddress_uri'],
    ];

    $form['claims_identificationNumber_uri'] = [
      '#title' => t('claims identificationNumber url'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['claims_identificationNumber_uri'],
    ];

    $form['check_session_endpoint'] = [
      '#title' => $this->t('Check Session URL'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['check_session_endpoint'],
    ];

    $form['check_session_enable'] = [
      '#type' => 'checkbox',
      '#title' => t('Check Session enable'),
      '#default_value' => $this->configuration['check_session_enable'],
    ];

    $form['iam_user'] = [
      '#title' => $this->t('IAM User Name'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['iam_user'],
      '#description' => t('This user is needed to cancel user account in IAM Server.'),
    ];

    $form['iam_password'] = [
      '#type' => 'password',
      '#title' => t('Password'),
      '#default_value' => $this->configuration['iam_password'],
    ];

    $description = $this->t("Describes the SSL certificate verification behavior of a request.");
    $description .= ' ' . $this->t("Set to <em>true</em> to enable SSL certificate verification and use the default CA bundle provided by operating system.");
    $description .= ' ' . $this->t("Set to <em>false</em> to disable certificate verification (this is insecure!).");
    $description .= ' ' . $this->t("Set to <em>Custom Certificate</em> to use a custom certificate in disk.");

    $form['guzzle_verify'] = [
      '#type' => 'radios',
      '#title' => $this->t('GUZZLE Verify SSL'),
      '#options' => [
        'true' => $this->t('True'),
        'false' => $this->t('False'),
        'cert' => $this->t('Custom Certificate'),
      ],
      '#description' => $description,
      '#default_value' => $this->configuration['guzzle_verify'],
    ];

    $form['guzzle_cert_path'] = [
      '#title' => $this->t('Custom certificate path'),
      '#description' => $this->t('Set to a string to provide the path to a CA bundle to enable verification using a custom certificate.  (e.g. <em>/path/to/cert.pem</em>)'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['guzzle_cert_path'],
      '#states' => [
        'required' => [
          ':input[name="guzzle_verify"]' => ['value' => 'cert'],
        ],
        'visible' => [
          ':input[name="guzzle_verify"]' => ['value' => 'cert'],
        ],
      ],
    ];

    $form['debug_mode'] = [
      '#type' => 'checkbox',
      '#title' => t('debug mode'),
      '#default_value' => $this->configuration['debug_mode'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndpoints() {
    return [
      'authorization' => $this->configuration['authorization_endpoint'],
      'token' => $this->configuration['token_endpoint'],
      'userinfo' => $this->configuration['userinfo_endpoint'],
      'create_user_endpoint' => $this->configuration['create_user_endpoint'],
      'create_user_callback' => $this->configuration['create_user_callback'],
      'cancel_user_endpoint' => $this->configuration['cancel_user_endpoint'],
      'logout_user_endpoint' => $this->configuration['logout_user_endpoint'],
      'logout_redirect_uri' => $this->configuration['logout_redirect_uri'],
      'check_session_endpoint' => $this->configuration['check_session_endpoint'],
      'check_session_enable' => $this->configuration['check_session_enable'],
      'claims_emailaddress_uri' => $this->configuration['claims_emailaddress_uri'],
      'claims_identificationNumber_uri' => $this->configuration['claims_identificationNumber_uri'],
      'debug_mode' => $this->configuration['debug_mode'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getVerify() {
    $verify =  $this->configuration['guzzle_verify'];
    switch ($verify) {
      case 'false':
        $result = false;
        break;
      case 'cert':
        $result =  $this->configuration['guzzle_cert_path'];
        break;
      default:
        $result = true;
    }
    return $result;
  }

  public function getRedirectUrl(array $route_parameters = [], array $options = [])
  {
    $language_none = $this->languageManager
      ->getLanguage(LanguageInterface::LANGCODE_NOT_APPLICABLE);

    $route_parameters += [
      'client_name' => $this->pluginId,
    ];
    $options += [
      'absolute' => TRUE,
      'language' => $language_none,
    ];
    return Url::fromRoute('consum_openid.redirect_controller_redirect', $route_parameters, $options);
  }

  /**
   * {@inheritdoc}
   */
  public function retrieveTokens($authorization_code) {
    // Exchange `code` for access token and ID token.
    $redirect_uri = $this->getRedirectUrl()->toString();
    $endpoints = $this->getEndpoints();

    $request_options = [
      'form_params' => [
        'code' => $authorization_code,
        'client_id' => $this->configuration['client_id'],
        'client_secret' => $this->configuration['client_secret'],
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
      ],
      'headers' => [
        'Accept' => 'application/json',
      ],
      'verify' => $this->getVerify(),
    ];

    $client = $this->httpClient;

    try {
      $response = $client->post($endpoints['token'], $request_options);
      $response_data = json_decode((string) $response->getBody(), TRUE);

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
        '@response_data' => json_encode($response_data, TRUE),
      ];

      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('retrieveTokens: request_options = @request_options - response_data = @response_data', $variables);
      }

      // Expected result.
      $tokens = [
        'id_token' => isset($response_data['id_token']) ? $response_data['id_token'] : NULL,
        'access_token' => isset($response_data['access_token']) ? $response_data['access_token'] : NULL,
      ];
      if (array_key_exists('expires_in', $response_data)) {
        $tokens['expire'] = $this->dateTime->getRequestTime() + $response_data['expires_in'];
      }
      if (array_key_exists('refresh_token', $response_data)) {
        $tokens['refresh_token'] = $response_data['refresh_token'];
      }
      return $tokens;
    }
    catch (Exception $e) {
      $variables = [
        '@message' => 'Could not retrieve tokens',
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function retrieveUserInfo($access_token) {
    $request_options = [
      'headers' => [
        'Authorization' => 'Bearer ' . $access_token,
        'Accept' => 'application/json',
      ],
      'verify' => $this->getVerify(),
    ];
    $endpoints = $this->getEndpoints();

    $client = $this->httpClient;
    try {
      $response = $client->get($endpoints['userinfo'], $request_options);
      $response_data = (string) $response->getBody();
      $result = json_decode($response_data, TRUE);
      $_REQUEST['response_data'] = $result;

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
        '@response_data' => json_encode($response_data, TRUE),
      ];
      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('retrieveUserInfo: request_options = @request_options - response_data = @response_data', $variables);
      }

      return $result;
    }
    catch (Exception $e) {
      $variables = [
        '@message' => 'Could not retrieve user profile information',
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   * Get client_credentials token to allow create new user
   */
  public function getClientCredentialsTokens() {
    // Exchange `code` for access token and ID token.
    $endpoints = $this->getEndpoints();

    $request_options = [
      'form_params' => [
        'client_id' => $this->configuration['client_id'],
        'client_secret' => $this->configuration['client_secret'],
        'grant_type' => 'client_credentials',
      ],
      'headers' => [
        'Accept' => 'application/json',
      ],
      'verify' => $this->getVerify(),
    ];

    $client = $this->httpClient;

    try {
      $response = $client->post($endpoints['token'], $request_options);
      $response_data = json_decode((string) $response->getBody(), TRUE);

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
        '@response_data' => json_encode($response_data, TRUE),
      ];
      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('getClientCredentialsTokens: request_options = @request_options - response_data = @response_data', $variables);
      }

      // Expected result.
      $tokens = [
        'access_token' => isset($response_data['access_token']) ? $response_data['access_token'] : NULL,
      ];
      if (array_key_exists('expires_in', $response_data)) {
        $tokens['expire'] = $this->dateTime->getRequestTime() + $response_data['expires_in'];
      }
      if (array_key_exists('refresh_token', $response_data)) {
        $tokens['refresh_token'] = $response_data['refresh_token'];
      }
      return $tokens;
    }
    catch (Exception $e) {
      $variables = [
        '@message' => 'Could not retrieve Client Credentials tokens',
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   * Create new user in IAM
   */
  public function createNewUser($user_data) {
    $token = $this->getClientCredentialsTokens();

    $endpoints = $this->getEndpoints();

    $request_options = [
      'headers' => [
        'Authorization' => 'Bearer ' . $token["access_token"],
        'Content-Type' => 'application/json',
      ],
      "json" => [
        "user" => [
          "username" => $user_data['user_name'],
          "realm" => "PRIMARY",
          "password" => $user_data['password'],
          "claims" => [
            [
              "uri" => $endpoints['claims_emailaddress_uri'],
              "value" => $user_data['email']
            ],
            [
              "uri" => $endpoints['claims_identificationNumber_uri'],
              "value" => $user_data['documento_principal_numero']
            ]
          ],
        ],
        "properties" => [
          [
            "key" => "callback",
            "value" => $endpoints['create_user_callback']
          ]
        ],
      ],
      'verify' => $this->getVerify(),
    ];


    /** @var Client $client */
    $client = $this->httpClient;
    try {
      $response = $client->post($endpoints['create_user_endpoint'], $request_options);
      $response_data = (string) $response->getBody();

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
        '@response_data' => json_encode($response_data, TRUE),
      ];
      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('createNewUser: request_options = @request_options - response_data = @response_data', $variables);
      }

      return json_decode($response_data, TRUE);
    }
    catch (Exception $e) {
      $error_code = $e->getCode();
      switch ($error_code) {
        case 409:
          $message = 'New user already exists in the IAM Server';
          break;
        case 500:
          if (!empty($account = User::load($user_data['uid']))) {
            $account->delete();
          }
          $message = 'Could not creaate new user in IAM Server';
          break;
        default:
          $message = 'Could not creaate new user in IAM Server';
          break;
      }
      $variables = [
        '@message' => $message,
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   * Update user in IAM
   */
  public function updateUser($user_data, AccountInterface $account) {

    $user_id = $this->getIAMUserID($account->getAccountName());
    if (empty($user_id)) {
      return false;
    }

    $endpoints = $this->getEndpoints();

    $request_options = [
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode($this->configuration['iam_user'] . ':' . $this->configuration['iam_password']),
      ],
      "json" => [
        "schemas" => [],
        "userName" => $user_data['user_name'],
      ],
      'verify' => $this->getVerify(),
    ];
    if (!empty($user_data['documento_principal_numero'])) {
      $request_options['json']['nickName'] = $user_data['documento_principal_numero'];
    }
    if (!empty($user_data['email'])) {
      $request_options['json']['emails'] = [
        [
          "value" => $user_data['email'],
          "primary" => true
        ]
      ];
    }


    /** @var Client $client */
    $client = $this->httpClient;
    $uri = $endpoints['cancel_user_endpoint'] . '/' . $user_id;

    try {
      $response = $client->put($uri, $request_options);
      $response_data = (string) $response->getBody();

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
        '@response_data' => json_encode($response_data, TRUE),
      ];
      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('updateUser: request_options = @request_options - response_data = @response_data', $variables);
      }

      return json_decode($response_data, TRUE);
    }
    catch (Exception $e) {
      $variables = [
        '@message' => 'Could not update user in IAM',
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   *Delete user's in IAM Server.
   *
   *@param AccountInterface $account
   *A client name.
   */
  public function cancelUser(AccountInterface $account){

    $user_id = $this->getIAMUserID($account->getAccountName());
    if (empty($user_id)) {
      return false;
    }

    $endpoints = $this->getEndpoints();

    $request_options = [
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode($this->configuration['iam_user'] . ':' . $this->configuration['iam_password']),
      ],
      'verify' => $this->getVerify(),
    ];

    /** @var Client $client */
    $client = $this->httpClient;
    $uri = $endpoints['cancel_user_endpoint'] . '/' . $user_id;

    try {
      $client->delete($uri, $request_options);

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
      ];
      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('cancelUser: request_options = @request_options', $variables);
      }

      return true;
    }
    catch (Exception $e) {
      $variables = [
        '@message' => 'Could not cancel user in IAM',
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   * Get User ID to allow cancel the user in IAM
   */
  public function getIAMUserID($username) {

    $endpoints = $this->getEndpoints();

    $request_options = [
      'headers' => [
        'Authorization' => 'Basic ' . base64_encode($this->configuration['iam_user'] . ':' . $this->configuration['iam_password']),
      ],
      'verify' => $this->getVerify(),
    ];

    /** @var Client $client */
    $client = $this->httpClient;

    try {
      $response = $client->get($endpoints['cancel_user_endpoint'] . '?filter=userName+eq+' . $username , $request_options);
      $response_data = json_decode((string) $response->getBody(), TRUE);

      $variables = [
        '@request_options' => json_encode($request_options, TRUE),
        '@response_data' => json_encode($response_data, TRUE),
      ];
      if ($this->configuration['debug_mode'] === true) {
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->notice('getIAMUserID: request_options = @request_options - response_data = @response_data', $variables);
      }

      return isset($response_data["Resources"][0]["id"]) ? $response_data["Resources"][0]["id"] : NULL;
    }
    catch (Exception $e) {
      $variables = [
        '@message' => 'Could not retrieve IAM User ID',
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
        ->error('@message. Details: @error_message', $variables);
      return FALSE;
    }
  }

  /**
   *Delete user's in IAM Server.
   *
   *@param AccountInterface $account
   *A client name.
   */
  public function logoutUser(AccountInterface $account){

    $endpoints = $this->getEndpoints();
    if (!empty($endpoints) && !empty($_SESSION["id_token"])) {
      $uri = $endpoints['logout_user_endpoint'] . '?id_token_hint=' . $_SESSION["id_token"] . '&post_logout_redirect_uri=' . $endpoints['logout_redirect_uri']  . '&=state_1';

      try {
        $response = new RedirectResponse($uri);
        $response->send();
      }
      catch (Exception $e) {
        drupal_set_message(t('There was an authentication error. Message: @message.', ['@message' => $e->getMessage()]), 'error', FALSE);
        $variables = [
          '@message' => 'Could not logout IAM Server',
          '@error_message' => $e->getMessage(),
        ];
        $this->loggerFactory->get('openid_connect_' . $this->pluginId)
          ->error('@message. Details: @error_message', $variables);
      }
    }
//    else {
//      $variables = [
//        '@id_token' => $_SESSION["id_token"]
//      ];
//      $this->loggerFactory->get('openid_connect_' . $this->pluginId)
//        ->error('Could not logout IAM Server. Bad settings. id_token:  @id_token', $variables);
//    }
    return FALSE;
  }

}
