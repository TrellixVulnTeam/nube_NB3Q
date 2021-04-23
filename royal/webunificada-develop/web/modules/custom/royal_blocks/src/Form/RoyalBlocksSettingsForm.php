<?php

namespace Drupal\royal_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RoyalBlocksSettingsForm extends ConfigFormBase {
  
  public function getFormId() {
    return 'royal_blocks_admin_settings';
  }
  
  protected function getEditableConfigNames() {
    return [
      'royal_blocks.settings'
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $config = $this->config('royal_blocks.settings');

    $form['consum_twitter_id'] = [
      '#type' => 'textfield',
      '#title' => t('Twitter ID'),
      '#size' => '13',
      '#default_value' => $config->get('socialnetworks.twitter_id'),
      '#description' => 'Set de screen_name (@Consum where Consum is the screen_name)',
    ];

    $form['consum_facebook_id'] = [
      '#type' => 'textfield',
      '#title' => t('Facebook ID'),
      '#size' => '13',
      '#default_value' => $config->get('socialnetworks.facebook_id'),
      '#description' => 'Set de profile name (supermercadosconsum is actual consum facebook profile name)',
    ];

    $form['consum_instagram_id'] = [
      '#type' => 'textfield',
      '#title' => t('Instagram ID'),
      '#size' => '13',
      '#default_value' => $config->get('socialnetworks.instagram_id'),
    ];

    $form['consum_youtube_id'] = [
      '#type' => 'textfield',
      '#title' => t('Youtube ID'),
      '#size' => '13',
      '#default_value' => $config->get('socialnetworks.youtube_id'),
    ];
    
    return parent::buildForm($form, $form_state);
  }
  
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('royal_blocks.settings')
      ->set('socialnetworks.twitter_id', $form_state->getValue('consum_twitter_id'))
      ->set('socialnetworks.facebook_id', $form_state->getValue('consum_facebook_id'))
      ->set('socialnetworks.instagram_id', $form_state->getValue('consum_instagram_id'))
      ->set('socialnetworks.youtube_id', $form_state->getValue('consum_youtube_id'))
      ->save(); 

    parent::submitForm($form, $form_state);
  }

}