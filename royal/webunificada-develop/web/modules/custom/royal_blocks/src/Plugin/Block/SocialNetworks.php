<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'SocialNetworks' block.
 *
 * @Block(
 *  id = "social_networks",
 *  admin_label = @Translation("Social Networks"),
 * )
 */
class SocialNetworks extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;
  
  // Injection services
  
  public function __construct(array $configuration, 
                              $plugin_id, 
                              $plugin_definition,
                              ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }
  
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration, 
      $plugin_id, 
      $plugin_definition,
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'social_network' => '',
      'social_network_id' => '',
      'customHeiht' => '',
      'border' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    
    $form['social_network'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Social Network'),
      '#options' => [
        'twitter' => $this->t('twitter'), 
        'facebook' => $this->t('facebook'), 
        'instagram' => $this->t('instagram'), 
        'youtube' => $this->t('youtube')
      ],
      '#default_value' => $this->configuration['social_network'],
      '#empty_value' => '_none',
      '#required' => TRUE,
    ];
    $form['customHeight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Custom Height'),
      '#description' => t('Determine your custom height. Only numbers.'),
      '#default_value' => isset($this->configuration['customHeight']) ? $this->configuration['customHeight'] : '',
      '#size' => 6,
      '#maxlength' => 3,
    ];
    $form['border'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image Border'),
      '#description' => $this->t('Set the image border (px).'),
      '#default_value' => isset($this->configuration['border']) ? $this->configuration['border'] : '',
      '#size' => 3,
      '#maxlenght' => 3,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    if (preg_match("/[^0-9]/", $form_state->getValue('customHeight')) == 1) {
      $form_state->setErrorByName('customHeight',
      $this->t('You can only introduce numbers'));
    }
    if (preg_match("/[^0-9]/", $form_state->getValue('border')) == 1) {
      $form_state->setErrorByName('Image Border',
      $this->t('You can only introduce numbers'));
    }
    if ($form_state->getValue('border') > 100) {
      $form_state->setErrorByName('Image Border',
      $this->t('The value not be mayor than 100'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['social_network'] = $form_state->getValue('social_network');
    $this->configuration['customHeight'] = $form_state->getValue('customHeight');
    $this->configuration['border'] = $form_state->getValue('border');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Get config social networks object
    $config = $this->configFactory->getEditable('royal_blocks.settings');

    switch ($this->configuration['social_network']) {
      case 'twitter':
          $social_network_id = $config->get('socialnetworks.twitter_id');
          break;
      case 'facebook':
          $social_network_id = $config->get('socialnetworks.facebook_id');
          break;
      case 'instagram':
          $social_network_id = $config->get('socialnetworks.instagram_id');
          break;
      case 'youtube':
          $social_network_id = $config->get('socialnetworks.youtube_id');
          break;
    }

    $border = $this->configuration['border'];


    return array(
      '#theme' => 'content_social_networks',
      '#social_network' => $this->configuration['social_network'],
      '#social_network_id' => $social_network_id,
      '#customHeight' => $this->configuration['customHeight'],
      '#border' => $border . 'px',
    );
  }

}
