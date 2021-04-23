<?php

namespace Drupal\consum_views_tools\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;

/**
 * Configure consum_views_tools settings for this site.
 *
 * Class ConsumViewsToolsAdminSettingsForm.
 *
 * @package Drupal\consum_views_tools\Form\
 */
class ConsumViewsToolsAdminSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'consum_views_tools.settings';

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'consum_views_tools_admin_settings';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [static::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    $views = Views::getApplicableViews('uses_hook_block');
    $view_options = [];
    foreach ($views as $view) {
      list($view_id, $display_id) = $view;
      $view_options[$view_id . '/' . $display_id] = $view_id . ': ' . $display_id;
    }

    $form['view_block'] = [
      '#type' => 'select',
      '#title' => $this->t('Selecciona las vistas a utilizar'),
      '#default_value' => $config->get('view_block'),
      '#options' => $view_options,
      '#multiple' => TRUE,
      '#attributes' => [
        'style' => 'height:600px',
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('view_block', $form_state->getValue('view_block'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
