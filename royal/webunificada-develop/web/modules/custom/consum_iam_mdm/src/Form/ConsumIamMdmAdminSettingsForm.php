<?php


namespace Drupal\consum_iam_mdm\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure consum_iam_mdm settings for this site.
 * Class ConsumIamMdmAdminSettingsForm
 * @package Drupal\consum_iam_mdm\Form
 */
class ConsumIamMdmAdminSettingsForm extends ConfigFormBase
{

    /**
     * Config settings.
     *
     * @var string
     */
    const SETTINGS = 'consum_iam_mdm.settings';

    /**
     * @inheritDoc
     */
    public function getFormId()
    {
      return 'consum_iam_mdm_admin_settings';
    }

    /**
     * @inheritDoc
     */
    protected function getEditableConfigNames()
    {
      return [static::SETTINGS];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config(static::SETTINGS);

      $form['api_mdm_endpoint'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Endpoint del MDM para la gestión de socios.'),
        '#default_value' => $config->get('api_mdm_endpoint'),
      ];
      $form['api_mdm_modification'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Valor del campo "aplicación de modificación" que se mandará al MDM'),
        '#default_value' => $config->get('api_mdm_modification'),
      ];
      $form['api_mdm_scope'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Valor del campo "scope" que se mandará al MDM'),
        '#default_value' => $config->get('api_mdm_scope'),
      ];
      $form['api_emas_endpoint'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Endpoint de EMAS para la creación de tickets en caso de error.'),
        '#default_value' => $config->get('api_emas_endpoint'),
      ];

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $config = $this->configFactory->getEditable(static::SETTINGS);
      $config->set('api_mdm_endpoint',$form_state->getValue('api_mdm_endpoint'))
        ->save();
      $config->set('api_mdm_modification', $form_state->getValue('api_mdm_modification'))
        ->save();
      $config->set('api_mdm_scope', $form_state->getValue('api_mdm_scope'))
        ->save();
      $config->set('api_emas_endpoint', $form_state->getValue('api_emas_endpoint'))
        ->save();

      parent::submitForm($form, $form_state);
    }

}
