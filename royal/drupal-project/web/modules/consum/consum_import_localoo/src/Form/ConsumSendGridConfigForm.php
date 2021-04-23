<?php

namespace Drupal\consum_sendgrid\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class ConsumSendgridConfigForm extends ConfigFormBase {

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
    $config = $this->config(static::SETTINGS);

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sendgrid url'),
      '#default_value' => $config->get('url'),
    ];

    $form['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('token'),
      '#default_value' => $config->get('token'),
    ];

  
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('url', $form_state->getValue('url'))
      ->set('token', $form_state->getValue('token'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}