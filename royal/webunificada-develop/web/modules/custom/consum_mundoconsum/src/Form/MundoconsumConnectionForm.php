<?php

namespace Drupal\consum_mundoconsum\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Mundoconsum settings for this site.
 */
class MundoconsumConnectionForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'mundoconsum.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mundoconsum_settings';
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
      '#title' => $this->t('MundoConsum API url'),
      '#default_value' => $config->get('url'),
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
      ->save();

    parent::submitForm($form, $form_state);
  }

}