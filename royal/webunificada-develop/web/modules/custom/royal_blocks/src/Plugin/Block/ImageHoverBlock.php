<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines Imagehover block.
 *
 * @Block(
 *   id = "imagenhover",
 *   admin_label = @Translation("Image Hover")
 * )
 */
class ImageHoverBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
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
      'target' => '',
      'autocomplete' => '',
      'link' => '',
      'image1' => '',
      'image2' => '',
      'border' => '',
      'alt' => '',
      'ocultar' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Load nodes storage.
    $node_storage = $this->entityManager->getStorage('node');

    $list_target = [
      '_parent' => $this->t('Misma página'),
      '_blank' => $this->t('Nueva pestaña'),
    ];
    $form['target'] = [
      '#type' => 'select',
      '#title' => $this->t('Destino'),
      '#description' => $this->t('Indica dónde se abrirá el enlace.'),
      '#default_value' => $this->configuration['target'],
      '#options' => $list_target,
      '#attributes' => [
        'id' => 'target',
      ],
    ];
    $form['autocomplete'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Enlace Interno'),
      '#target_type' => 'node',
      '#description' => $this->t('Indica el enlace que debe tener el botón.'),
      '#default_value' => isset($this->configuration['autocomplete']) ? $node_storage->load($this->configuration['autocomplete']) : '',
      '#states' => [
        'visible' => [
          ':input[id="target"]' => ['value' => '_parent'],
        ],
      ],
    ];
    $form['link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enlace Externo'),
      '#description' => $this->t('Debe indicar en la ruta https:// o http://'),
      '#default_value' => $this->configuration['link'],
      '#states' => [
        'visible' => [
          ':input[id="target"]' => ['value' => '_blank'],
        ],
      ],
    ];
    $form['image1'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen principal'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image1']) ? $this->configuration['image1'] : '',
      '#description' => t('Imagen en reposo del bloque'),
      '#required' => TRUE,
    ];
    $form['image2'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen secundaria'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => t('Imagen que se mostrará al colocar el puntero del ratón encima del bloque'),
      '#required' => FALSE,
    ];
    $form['border'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image Border'),
      '#description' => $this->t('Set the image border (px).'),
      '#default_value' => isset($this->configuration['border']) ? $this->configuration['border'] : '',
      '#size' => 3,
      '#maxlenght' => 3,
    ];
    $form['alt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto alternativo'),
      '#description' => $this->t('Indica el texto alternativo que utilizará la imagen principal.'),
      '#default_value' => $this->configuration['alt'],
    ];
    $list_ocultar = [
      'movil' => $this->t('Ocultar en móvil'),
      'tablet' => $this->t('Ocultar en Tablet'),
      'escritorio' => $this->t('Ocultar en Escritorio'),
    ];
    $form['ocultar'] = [
      '#type' => 'checkboxes',
      '#title' => t('Ocultar en dispositivos'),
      '#default_value' => $this->configuration['ocultar'],
      '#options' => $list_ocultar,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
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

    // Load file object from storage.
    $file_storage = $this->entityManager->getStorage('file');

    $image1 = $image2 = "";

    // Save image 1 as permanent.
    $image = $form_state->getValue('image1');
    if ($image != $this->configuration['image1']) {
      if (!empty($image[0])) {
        $file = $file_storage->load($image[0]);
        $file->setPermanent();
        $file->save();
      }
    }
    // Save image 2 as permanent.
    $image2 = $form_state->getValue('image2');
    if ($image2 != $this->configuration['image2']) {
      if (!empty($image2[0])) {
        $file2 = $file_storage->load($image2[0]);
        $file2->setPermanent();
        $file2->save();
      }
    }
    // Save configurations.
    $this->configuration['target'] = $form_state->getValue('target');
    $this->configuration['autocomplete'] = $form_state->getValue('autocomplete');
    $this->configuration['link'] = $form_state->getValue('link');
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['border'] = $form_state->getValue('border');
    $this->configuration['alt'] = $form_state->getValue('alt');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Getting entity file storage.
    $file_storage = $this->entityManager->getStorage('file');

    // Load nodes storage.
    $node_storage = $this->entityManager->getStorage('node');

    $file_url = $file_url2 = "";
    $image1 = $this->configuration['image1'];
    if (!empty($image1[0])) {
      if ($file = $file_storage->load($image1[0])) {
        $file_url = $file->url();
      }
    }
    $image2 = $this->configuration['image2'];
    if (!empty($image2[0])) {
      if ($file2 = $file_storage->load($image2[0])) {
        $file_url2 = $file2->url();
      }
    }

    // Switch to correct case to set $enlace value.
    $enlace = '';
    switch ($this->configuration['target']) {
      case '_parent':
        $enlace = '';
        if ($nid = $this->configuration['autocomplete']) {
          if($url = Url::fromRoute('entity.node.canonical',['node' => $nid])) {
            $enlace = $url->toString();
          }
        }
      break;


      case '_blank':
        $enlace = $this->configuration['link'];
        break;

    }

    $target = $this->configuration['target'];
    $border = $this->configuration['border'];
    $alt = $this->configuration['alt'];
    $ocultar = $this->configuration['ocultar'];

    return [
      '#theme' => 'content_imagenhover',
      '#target' => $target,
      '#enlace' => $enlace,
      '#img1' => $file_url,
      '#img2' => $file_url2,
      '#border' => $border . 'px',
      '#alt' => $alt,
      '#ocultar' => $ocultar,
    ];
  }

}
