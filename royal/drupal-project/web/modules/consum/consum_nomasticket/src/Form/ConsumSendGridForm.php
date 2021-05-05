<?php

namespace Drupal\consum_sendgrid\Form;


use Drupal\consum_sendgrid\Services\SendGridConnection;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Configure example settings for this site.
 */
class ConsumSendgridForm extends FormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'consum_sendgrid.settings';
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'consum_sendgrid';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('email'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::submitForm',
        'event' => 'click',
        'progress' => [
          'type'    => 'throbber',
          'message' => t('Suscripción realizada'),
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /**
     * foreach ($form_state->getValues() as $key => $value) {
     *  \Drupal::messenger()->addStatus($key . ': ' . $value);
     * }
    */

    $connection = new SendGridConnection();
    $result = $connection->RequestSendGrid($form_state->getValue('email'));
  }

 
}