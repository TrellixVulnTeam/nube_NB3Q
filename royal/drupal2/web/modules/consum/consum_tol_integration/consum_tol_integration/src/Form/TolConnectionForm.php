<?php

namespace Drupal\consum_tol_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class TolConnectionForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'consum_tol_integration.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'consum_tol_integration';
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
      '#title' => $this->t('TOL url'),
      '#default_value' => $config->get('url'),
    ];

    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client_id'),
      '#default_value' => $config->get('client_id'),
    ];

    $form['admin_user'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Admin User'),
      '#default_value' => $config->get('admin_user'),
    ];

    $form['admin_pass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Admin Password'),
      '#default_value' => $config->get('admin_pass'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('url', $form_state->getValue('url'))
      ->set('client_id', $form_state->getValue('client_id'))
      ->set('admin_user', $form_state->getValue('admin_user'))
      ->set('admin_pass', $form_state->getValue('admin_pass'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}