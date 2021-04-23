<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Defines CTA block.
 *
 * @Block(
 *   id = "cta_block",
 *   admin_label = @Translation("Click To Action")
 * )
 */
class CtaBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritDoc}
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
      'image1' => '',
      'gradient' => '',
      'image2' => '',
      'text1' => '',
      'text2' => t('Leer más'),
      'text3' => '',
      'alto' => '',
      'customHeiht' => '',
      'target' => '',
      'autocomplete' => '',
      'link' => '',
      'color_bot' => 'boton1',
      'color_fondo' => 'sin-fondo',
      'custom_color' => '',
      'visibility' => '',
      'ocultar' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

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
      '#description' => t('Imagen de fondo'),
      '#required' => FALSE,
    ];

    $form['gradient'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Apply Gradient'),
      '#default_value' => isset($this->configuration['gradient']) ? $this->configuration['gradient'] : '',
    ];

    $form['image2'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen mobile'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => t('Imagen que se mostrará en dispositivos móviles'),
      '#required' => FALSE,
    ];
    $form['texto1'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Título del CTA'),
      '#default_value' => $this->configuration['texto1']['value'],
      '#format' => 'rich_text',
    ];
    $form['texto2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del botón'),
      '#default_value' => $this->configuration['texto2'],
    ];
    $list_color_bot = [
      'boton1' => $this->t('Principal'),
      'boton2' => $this->t('Monocromo naranja'),
      'boton3' => $this->t('Monocromo negro'),
    ];
    $form['color_bot'] = [
      '#type' => 'select',
      '#title' => t('Color de fondo del botón'),
      '#default_value' => $this->configuration['color_bot'],
      '#options' => $list_color_bot,
    ];
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
      '#default_value' => isset($this->configuration['autocomplete']) ? Node::load($this->configuration['autocomplete']) : '',
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
    $list_color_fondo = [
      'sin-fondo' => $this->t('Sin color de fondo'),
      'custom-color' => $this->t('Color Personalizado'),
      'fondo-blanco' => $this->t('Blanco'),
      'fondo-naranja-claro' => $this->t('Naranja claro'),
      'fondo-naranja' => $this->t('Naranja'),
      'fondo-marron' => $this->t('Marrón'),
      'fondo-salmon' => $this->t('Salmón'),
      'fondo-arcilla' => $this->t('Arcilla'),
      'fondo-burdeos' => $this->t('Burdeos'),
      'fondo-morado' => $this->t('Morado'),
      'fondo-verde-claro' => $this->t('Verde claro'),
      'fondo-verde' => $this->t('Verde'),
    ];
    $form['color_fondo'] = [
      '#type' => 'select',
      '#title' => $this->t('Color de fondo'),
      '#default_value' => $this->configuration['color_fondo'],
      '#description' => t('Si el color elegido es blanco o "Sin color de fondo", el color del título pasará a ser gris corporativo.'),
      '#options' => $list_color_fondo,
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
    $list_alto = [
      'custom-height' => $this->t('Alto Personalizado'),
      'customblock-h' => $this->t('Alto estándar (270px)'),
      'customblock-2h' => $this->t('Alto doble (570px)'),
    ];
    $form['alto'] = [
      '#type' => 'select',
      '#title' => t('Altura del bloque'),
      '#default_value' => isset($this->configuration['alto']) ? $this->configuration['alto'] : '',
      '#empty_value' => '_none',
      '#options' => $list_alto,
      '#attributes' => [
        'id' => 'height',
      ],
    ];
    $form['customHeight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Custom Height'),
      '#description' => t('Determine your custom height. Only numbers.'),
      '#default_value' => isset($this->configuration['customHeight']) ? $this->configuration['customHeight'] : '',
      '#size' => 6,
      '#maxlength' => 3,
      '#states' => [
        'visible' => [
          ':input[id="height"]' => ['value' => 'custom-height'],
        ],
      ],
    ];
    $restriction = [
      'users' => $this->t('Users'),
      'anonymous' => $this->t('Visitors'),
    ];
    $form['visibility'] = [
      '#type' => 'select',
      '#title' => t('Hide Block'),
      '#description' => t('Select users type to hide block'),
      '#default_value' => isset($this->configuration['visibility']) ? $this->configuration['visibility'] : '',
      '#empty_value' => '_none',
      '#options' => $restriction,
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
    if (preg_match("/[^0-9]/", $form_state->getValue('customHeight')) == 1) {
      $form_state->setErrorByName('customHeight',
      $this->t('You can only introduce numbers'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Save image 1 as permanent.
    $image = $form_state->getValue('image1');
    if ($image != $this->configuration['image1']) {
      if (!empty($image[0])) {
        if ($file = File::load($image[0])) {
          $file->setPermanent();
          $file->save();
        }
      }
    }
    // Save image 2 as permanent.
    $image2 = $form_state->getValue('image2');
    if ($image2 != $this->configuration['image2']) {
      if (!empty($image2[0])) {
        if ($file2 = File::load($image2[0])) {
          $file2->setPermanent();
          $file2->save();
        }
      }
    }
    // Save configurations.
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['gradient'] = $form_state->getValue('gradient');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['texto1'] = $form_state->getValue('texto1');
    $this->configuration['texto2'] = $form_state->getValue('texto2');
    $this->configuration['target'] = $form_state->getValue('target');
    $this->configuration['autocomplete'] = $form_state->getValue('autocomplete');
    $this->configuration['link'] = $form_state->getValue('link');
    $this->configuration['color_bot'] = $form_state->getValue('color_bot');
    $this->configuration['color_fondo'] = $form_state->getValue('color_fondo');
    $this->configuration['custom_color'] = $form_state->getValue('custom_color');
    $this->configuration['alto'] = $form_state->getValue('alto');
    $this->configuration['customHeight'] = $form_state->getValue('customHeight');
    $this->configuration['visibility'] = $form_state->getValue('visibility');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

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

    // Switch to correct case to set $enlace value.
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

    // Set $color_fondo value
    if($this->configuration['color_fondo'] == 'sin-fondo') {
      $color_fondo = '';
    } elseif ($this->configuration['color_fondo'] == 'custom-color') {
      $color_fondo = $this->configuration['custom_color'];
    } else {
      $color_fondo = $this->configuration['color_fondo'];
    }

    // Set $alto and $customHeight values
    if($this->configuration['alto'] == 'custom-height') {
      $customHeight = $this->configuration['customHeight'];
      $alto = '';
    } else {
      $alto = $this->configuration['alto'];
      $customHeight = '';
    }

    $target = $this->configuration['target'];
    $image1 = $this->configuration['image1'];
    $gradient = $this->configuration['gradient'];
    $image2 = $this->configuration['image2'];
    $texto1 = $this->configuration['texto1'];
    $texto2 = $this->configuration['texto2'];
    $color_bot = $this->configuration['color_bot'];
    $visibility = $this->configuration['visibility'];
    $ocultar = $this->configuration['ocultar'];

    return [
      '#theme' => 'content_cta',
      '#img1' => $file_url,
      '#gradient' => $gradient,
      '#img2' => $file_url2,
      '#texto2' => $texto2,
      '#target' => $target,
      '#enlace' => $enlace,
      '#texto1' => $texto1,
      '#color_bot' => $color_bot,
      '#color_fondo' => $color_fondo,
      '#alto' => $alto,
      '#customHeight' => $customHeight . 'px',
      '#visibility' => $visibility,
      '#ocultar' => $ocultar,
    ];
  }

}
