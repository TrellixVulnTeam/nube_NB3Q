<?php


namespace Drupal\consum_user_register\Plugin\WebformHandler;

use Drupal;
use Drupal\consum_iam_mdm\EntityHelperMDM;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Webform submission consum_user_register handler.
 *
 * @WebformHandler(
 *   id = "consum_user_register",
 *   label = @Translation("Consum user register"),
 *   category = @Translation("Consum user register"),
 *   description = @Translation("Set Consum user register webform submission handler."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 * )
 */
class ConsumUserRegisterWebformHandler extends WebformHandlerBase
{

  /**
   * {@inheritdoc}
   */
  public function alterForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    $form["#attached"]["library"][] = 'consum_user_register/consum_user_register';
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    try {
      // Get an array of the values from the submission.
      $values = $webform_submission->getData();
      $date = new DrupalDateTime();
      $timestamp = $date->getTimestamp();
      $values['user_name'] = 'CONSUM_' . md5($values['email'] . $timestamp);

      // Create user object.
      $user = User::create();
      //Mandatory settings
      $user->setPassword($values['password']);
      $user->setEmail($values['email']);
      $user->setUsername($values['user_name']); //This username must be unique and accept only a-Z,0-9, - _ @ .
      $user->enforceIsNew();
      $user->save();

      $documento_principal = isset($values['documento_principal_numero']) ? $values['documento_principal_numero'] : '';
      $numero_socio = isset($values['numero_socio']) ? $values['numero_socio'] : '';
      $user->set("field_documento_principal_numero", $documento_principal);
      $user->set("field_codigo_socio", $numero_socio);
      if (!empty($values["socio_cliente_options"]) && in_array('current', $values["socio_cliente_options"])) {
        $user->addRole('mundo_consum');
      }

      $violations = $user->validate();
      if ($violations->count() > 0) {
        // Validation failed.
        $error = '';
        foreach ($violations as $v) {
          $error = $v->getMessage() . '. ';
        }
        Drupal::logger('consum_user_register')->error($error);
        return false;
      }
      $user->activate();

      //Save user and create internal association with IAM
      if ($user->save()) {
        if (!empty($values["socio_cliente_options"]) && in_array('new', $values["socio_cliente_options"])) {
          $_SESSION['registerSocioCliente'] = $user->id();
        }
        // Create external account in IAM server
        Drupal::service('consum_openid.authmap')->createExternalAccount($values);
        Drupal::service('openid_connect.authmap')->createAssociation($user, 'consum', $values['user_name']);
        Drupal::logger('consum_user_register')->notice('Created new user: @user_name.', ['@username' => $values['user_name']]);
        $helper = new EntityHelperMDM();
        $helper->sendUsuarioNuevo($user);
        return true;
      }
    }
    catch (\Exception $e) {
      Drupal::logger('consum_user_register')->error($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    $values = $form_state->getValues();

    // email validation
    if (!empty($values["email"])) {
      $ids = Drupal::entityQuery('user')
        ->condition('mail', $values["email"])
        ->execute();
      if (!empty($ids)) {
        $form_state->setErrorByName('email', $this->t('The indicated email already exists in the system.'));
      }
    }

    // email validation
    if (!empty($values["email"])) {
      $ids = Drupal::entityQuery('user')
        ->condition('mail', $values["email"])
        ->execute();
      if (!empty($ids)) {
        $form_state->setErrorByName('email', $this->t('The indicated email already exists in the system.'));
      }
    }

    // password validation
    if (!empty($values["password"])) {
      if (strlen($values["password"]) < 10 | strlen($values["password"]) > 25) {
        $form_state->setErrorByName('password', t("The password must be between 10 and 25 characters."));
      } elseif (!preg_match('`[A-Z]`', $values["password"])) {
        $form_state->setErrorByName('password', t("The password must include at least one uppercase letter."));
      } elseif (!preg_match('`[a-z]`', $values["password"])) {
        $form_state->setErrorByName('password', t("The password must include at least one lowercase letter."));
      } elseif (!preg_match('`[0-9]`', $values["password"])) {
        $form_state->setErrorByName('password', t("The password must include at least one number."));
      }
    }

    // Socio/cliente validation
    if (!empty($values["socio_cliente_options"]) && in_array('current', $values["socio_cliente_options"])) {
      // TODO: Añadir aquí la validación de núnero de socio y dni con Mundoconsum
    }
  }

  /**
   * {@inheritdoc}
   */
  public function preprocessConfirmation(array &$variables) {
    // If user check New Socio-Cliente, we change link in confirmation
    if (isset($_SESSION['registerSocioCliente'])) {
      if (isset($variables["back_label"])) {
        $variables["back_label"] = $this->t('Continue Socio-Client registration');
      }
      if (isset($variables["back_url"])) {
        $variables["back_url"] = '/form/registro-de-socio-cliente';
      }
    } else {
      $variables["back_url"] = Url::fromRoute('<front>');
    }
  }

}
