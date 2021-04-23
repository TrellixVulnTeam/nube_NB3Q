<?php

/**
 * @file
 * Contains \Drupal\consum_custom_blocks\Plugin\Block\ConsumCustomBlockBase.
 */

namespace Drupal\consum_custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Defines a base block implementation that most Consum custom blocks plugins will extend.
 *
 * This abstract class provides the generic block configuration form, default
 * block settings, and handling for general user-defined block visibility
 * settings.
 *
 * @ingroup consum
 */
abstract class ConsumCustomBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  // Injection services

 public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'text1' => '',
      'height' => '',
      'customHeight' => '',
      'customStyle' => '',
      'background_color' => '',
      'custom_color' => 'without-background',
      'visibility' => '',
      'hide' => '',
      'image1' => '',
      'image2' => '',
      'border' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['text1'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Block title'),
      '#default_value' => $this->configuration['text1']['value'] ? $this->configuration['text1']['value'] : '',
      '#format' => 'rich_text',

    ];
    $list_height = [
      'custom-height' => $this->t('Custom Height'),
      'customblock-h' => $this->t('Standard Height (270px)'),
      'customblock-2h' => $this->t('Double Height (570px)'),
    ];
    $form['height'] = [
      '#type' => 'select',
      '#title' => $this->t('Altura del bloque'),
      '#default_value' => isset($this->configuration['height']) ? $this->configuration['height'] : '',
      '#empty_value' => '_none',
      '#options' => $list_height,
      '#attributes' => [
        'id' => 'height',
      ],
    ];
    $form['customHeight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Custom Height'),
      '#description' => $this->t('Determine your custom height. Only numbers.'),
      '#default_value' => isset($this->configuration['customHeight']) ? $this->configuration['customHeight'] : '',
      '#size' => 6,
      '#maxlength' => 3,
      '#states' => [
        'visible' => [
          ':input[id="height"]' => ['value' => 'custom-height'],
        ],
      ],
    ];
    $form['customStyle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Custom Style'),
      '#description' => $this->t('Determine the custom styles from the block.'),
      '#default_value' => isset($this->configuration['customStyle']) ? $this->configuration['customStyle'] : '',
    ];
    $list_background_color = [
      'sin-fondo' => $this->t('Without background'),
      'custom-color' => $this->t('Custom Color'),
      'fondo-blanco' => $this->t('White'),
      'fondo-naranja-claro' => $this->t('Light Orange'),
      'fondo-naranja' => $this->t('Orange'),
      'fondo-marron' => $this->t('Brown'),
      'fondo-salmon' => $this->t('Salmon'),
      'fondo-arcilla' => $this->t('Clay'),
      'fondo-burdeos' => $this->t('Bordeaux'),
      'fondo-morado' => $this->t('Purple'),
      'fondo-verde-claro' => $this->t('Light Green'),
      'fondo-verde' => $this->t('Green'),
    ];
    $form['background_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Background color'),
      '#default_value' => $this->configuration['background_color'],
      '#options' => $list_background_color,
      '#attributes' => [
        'id' => 'background',
      ],
    ];
    $form['custom_color'] = [
      '#type' => 'color',
      '#title' => $this->t('Custom Color'),
      '#default_value' => isset($this->configuration['custom_color']) ? $this->configuration['custom_color'] : '',
      '#states' => [
        'visible' => [
          ':input[id="background"]' => ['value' => 'custom-color'],
        ],
      ],
    ];
    $restriction = [
      'users' => $this->t('Users'),
      'anonymous' => $this->t('Visitors'),
    ];
    $form['visibility'] = [
      '#type' => 'select',
      '#title' => $this->t('Hide Block'),
      '#description' => $this->t('Select users type to hide block'),
      '#default_value' => isset($this->configuration['visibility']) ? $this->configuration['visibility'] : '',
      '#empty_value' => '_none',
      '#options' => $restriction,
    ];
    $list_hide = [
      'movil' => $this->t('Hide on mobile'),
      'tablet' => $this->t('Hide on tablet'),
      'escritorio' => $this->t('Hide on desktop'),
    ];
    $form['hide'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Hide on devices'),
      '#default_value' => $this->configuration['hide'],
      '#options' => $list_hide,
    ];
    $form['image1'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Main picture'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image1']) ? $this->configuration['image1'] : '',
      '#description' => $this->t('Block idle image'),
    ];
    $form['image2'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Responsive image'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => $this->t('Image to be displayed when placing the mouse pointer over the block'),
      '#required' => FALSE,
    ];
    $form['border'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Image Border'),
      '#description' => $this->t('Set the image border (px).'),
      '#default_value' => isset($this->configuration['border']) ? $this->configuration['border'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Load file object from storage.

    $image1 = $image2 = "";

    // Save image 1 as permanent.
    $image = $form_state->getValue('image1');
    if ($image != $this->configuration['image1']) {
      if (!empty($image[0])) {
        $file = File::load($image[0]);
        $file->setPermanent();
        $file->save();
      }
    }
    // Save image 2 as permanent.
    $image2 = $form_state->getValue('image2');
    if ($image2 != $this->configuration['image2']) {
      if (!empty($image2[0])) {
        $file2 = File::load($image2[0]);
        $file2->setPermanent();
        $file2->save();
      }
    }
    // Save configurations.
    $this->configuration['text1'] = $form_state->getValue('text1');
    $this->configuration['height'] = $form_state->getValue('height');
    $this->configuration['customHeight'] = $form_state->getValue('customHeight');
    $this->configuration['customStyle'] = $form_state->getValue('customStyle');
    $this->configuration['background_color'] = $form_state->getValue('background_color');
    $this->configuration['custom_color'] = $form_state->getValue('custom_color');
    $this->configuration['visibility'] = $form_state->getValue('visibility');
    $this->configuration['hide'] = $form_state->getValue('hide');
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['border'] = $form_state->getValue('border');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Getting entity file storage.

    $file_url = $file_url2 = "";
    $image1 = $this->configuration['image1'];
    if (!empty($image1[0])) {
      if ($file = File::load($image1[0])) {
        $file_url = $file->url();
      }
    }
    $image2 = $this->configuration['image2'];
    if (!empty($image2[0])) {
      if ($file2 = File::load($image2[0])) {
        $file_url2 = $file2->url();
      }
    }

    // Set $background_color value
    if($this->configuration['background_color'] == 'without-background') {
      $background_color = '';
    } elseif ($this->configuration['background_color'] == 'custom-color') {
      $background_color = $this->configuration['custom_color'];
    } else {
      $background_color = $this->configuration['background_color'];
    }

    // Set $height and $customHeight values
    if($this->configuration['height'] == 'custom-height') {
      $customHeight =  $this->configuration['customHeight'];
      $height = '';
    } else {
      $height = $this->configuration['height'];
      $customHeight = '';
    }

    $customStyle =  $this->configuration['customStyle'];
    $border = $this->configuration['border'];
    $text1 = $this->configuration['text1'];
    $visibility = $this->configuration['visibility'];
    $hide = $this->configuration['hide'];

    return [
      //'#theme' => 'content_customtext',
      '#text1' => $text1,
      '#height' => $height,
      '#customHeight' => $customHeight . 'px',
      '#customStyle' => $customStyle,
      '#background_color' => $background_color,
      '#visibility' => $visibility,
      '#hide' => $hide,
      '#img1' => $file_url,
      '#img2' => $file_url2,
      '#border' => $border,
    ];
  }

}
