<?php


namespace Drupal\consum_user_register\Plugin\WebformHandler;

use Drupal;
use Drupal\user\Entity\User;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\UserInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Webform submission consum_user_register handler.
 *
 * @WebformHandler(
 *   id = "consum_socio_register",
 *   label = @Translation("Consum socio register"),
 *   category = @Translation("Consum socio register"),
 *   description = @Translation("Set Consum socio register webform submission handler."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 * )
 */
class ConsumSocioRegisterWebformHandler extends WebformHandlerBase
{
  // Function to be fired after submitting the Webform.
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    // Get an array of the values from the submission.
    $values = $webform_submission->getData();
    $user = null;
    // Si llegamos aquí despues de crear el usuario vamos a actualizar la info de la cuenta a partir del formulario
    if (Drupal::currentUser()->isAnonymous()) {
      if (isset($_SESSION['registerSocioCliente'])) {
        $user = User::load($_SESSION['registerSocioCliente']);
      }
    } else {
      $user = User::load(Drupal::currentUser()->id());
    }
    if ($user instanceof UserInterface) {
      $form_field_map = [
        'nombre' => 'field_nombre',
        'apellido' => 'field_apellido1',
        'apellido_2' => 'field_apellido2',
        'documento_identidad' => 'field_documento_principal_numero',
      ];
      foreach ($form_field_map as $form_element => $field_name) {
        if (!empty($values[$form_element]) && $user->hasfield($field_name)) {
          $user->set($field_name, $values[$form_element]);
        }
      }
      // TODO: Falta crear la dirección ó determinar si se hace cuando llegue desde el MDM
      // Asignamos provisionalmente 'SOLICITADO' para control
      if ($user->hasfield('field_estado_socio_cliente')) {
        $user->set("field_estado_socio_cliente", '1');
      }
      $user->save();
      unset($_SESSION['registerSocioCliente']);
    }
      // TODO: falta enviar los datos a Mundoconsum (Laravel) para la gestión de aprobación
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    // Get an array of the values from the submission.
    $values = $webform_submission->getData();
    // TODO: falta añadir validaciones si procede

  }

}
