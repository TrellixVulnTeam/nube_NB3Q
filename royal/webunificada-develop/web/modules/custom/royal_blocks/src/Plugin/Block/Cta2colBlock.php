<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines Click to action 2 columns block.
 *
 * @Block(
 *   id = "cta_2colblock",
 *   admin_label = @Translation("Click To Action (2 columnas)")
 * )
 */
class Cta2colBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
      'image1' => '',
      'gradient' => '',
      'image2' => '',
      'texto1' => '',
      'texto2' => '',
      'target' => '',
      'autocomplete' => '',
      'link' => '',
      'texto_enlace' => 'Saber más',
      'color_bot' => 'boton1',
      'maquetacion' => 'img_izq_33',
      'color_fondo' => 'fondo-blanco',
      'custom_color' => '',
      'icon' => '',
      'alineacion_icon' => '',
      'visibility' => '',
      'ocultar' => '',
      'custom_class' => '',
      'posicion_btn' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Load nodes storage.
    $node_storage = $this->entityManager->getStorage('node');

    $list_maquetacion = [
      'img_izq_33' => $this->t('Imagen Izquierda 1/3'),
      'img_der_33' => $this->t('Imagen Derecha 1/3'),
      'img_izq_66' => $this->t('Imagen Izquierda 2/3'),
      'img_der_66' => $this->t('Imagen Derecha 2/3'),
      'img_izq_50' => $this->t('Imagen Izquierda 1/2'),
      'img_der_50' => $this->t('Imagen Derecha 1/2'),
      'img_cen' => $this->t('Imagen Central'),
      'img_sup' => $this->t('Imagen Superior'),
    ];
    $form['maquetacion'] = [
      '#type' => 'select',
      '#title' => $this->t('Maquetación del bloque'),
      '#default_value' => $this->configuration['maquetacion'],
      '#options' => $list_maquetacion,
      '#attributes' => [
        'id' => 'maquetacion',
      ],
    ];

    $list_pos_boton = [
      'sin-alineacion' => $this->t('Sin definir'),
      'columna_2' => $this->t('Columna derecha'),
    ];
    $form['posicion_btn'] = [
      '#type' => 'select',
      '#title' => $this->t('Posición botón'),
      '#default_value' => $this->configuration['posicion_btn'],
      '#options' => $list_pos_boton,
      '#states' => [
        'visible' => [
          ':input[id="maquetacion"]' => ['value' => 'img_der_33'],
        ],
      ],
    ];

    $form['image1'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Imagen principal'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image1']) ? $this->configuration['image1'] : '',
      '#description' => $this->t('Imagen de fondo'),
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
      '#title' => $this->t('Imagen mobile'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => $this->t('Imagen que se mostrará en dispositivos móviles'),
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
      '#title' => $this->t('Texto de Sección'),
      '#default_value' => $this->configuration['texto2'],
    ];
    $form['texto_enlace'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del botón'),
      '#default_value' => $this->configuration['texto_enlace'],
    ];
    $list_color_bot = [
      'boton1' => $this->t('Principal'),
      'boton2' => $this->t('Monocromo naranja'),
      'boton3' => $this->t('Monocromo negro'),
    ];
    $form['color_bot'] = [
      '#type' => 'select',
      '#title' => $this->t('Estilo del botón'),
      '#default_value' => $this->configuration['color_bot'],
      '#options' => $list_color_bot,
    ];
    $list_color_fondo = [
      'sin-fondo' => $this->t('Sin definir'),
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
    $form['icon'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Icono'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['icon']) ? $this->configuration['icon'] : '',
      '#required' => FALSE,
    ];
    $list_align_icon = [
      'sin-alineacion' => $this->t('Sin definir'),
      'middle' => $this->t('Alineado con el texto'),
    ];
    $form['alineacion_icon'] = [
      '#type' => 'select',
      '#title' => $this->t('Alineación icono'),
      '#default_value' => $this->configuration['alineacion_icon'],
      '#options' => $list_align_icon,
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

    $form['custom_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom class'),
      '#default_value' => $this->configuration['custom_class'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Load file object from storage.
    $file_storage = $this->entityManager->getStorage('file');

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
    // Save icon as permanent.
    $icon = $form_state->getValue('icon');
    if ($icon != $this->configuration['icon']) {
      if (!empty($icon[0])) {
        $file3 = $file_storage->load($icon[0]);
        $file3->setPermanent();
        $file3->save();
      }
    }
    // Save configurations.
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['gradient'] = $form_state->getValue('gradient');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['icon'] = $form_state->getValue('icon');
    $this->configuration['texto1'] = $form_state->getValue('texto1');
    $this->configuration['texto2'] = $form_state->getValue('texto2');
    $this->configuration['target'] = $form_state->getValue('target');
    $this->configuration['autocomplete'] = $form_state->getValue('autocomplete');
    $this->configuration['link'] = $form_state->getValue('link');
    $this->configuration['texto_enlace'] = $form_state->getValue('texto_enlace');
    $this->configuration['color_bot'] = $form_state->getValue('color_bot');
    $this->configuration['maquetacion'] = $form_state->getValue('maquetacion');
    $this->configuration['color_fondo'] = $form_state->getValue('color_fondo');
    $this->configuration['custom_color'] = $form_state->getValue('custom_color');
    $this->configuration['visibility'] = $form_state->getValue('visibility');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
    $this->configuration['custom_class'] = $form_state->getValue('custom_class');
    $this->configuration['posicion_btn'] = $form_state->getValue('posicion_btn');
    $this->configuration['alineacion_icon'] = $form_state->getValue('alineacion_icon');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Getting entity file storage.
    $file_storage = $this->entityManager->getStorage('file');

    // Load nodes storage.
    $node_storage = $this->entityManager->getStorage('node');

    $file_url = $file_url2 = $icon_url = "";
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
    $icon = $this->configuration['icon'];
    if (!empty($icon[0])) {
      if ($file3 = $file_storage->load($icon[0])) {
        $icon_url = $file3->url();
      }
    }

    // Switch to correct case to set $enlace value.
    switch ($this->configuration['target']) {
      case '_parent':
        $node = $node_storage->load($this->configuration['autocomplete']);
        if ($node) {
          $url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()]);
          $enlace = $url->toString();
        }
        else {
          $enlace = '';
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

    $target = $this->configuration['target'];
    $image1 = $this->configuration['image1'];
    $gradient = $this->configuration['gradient'];
    $image2 = $this->configuration['image2'];
    $icon = $this->configuration['icon'];
    $texto1 = $this->configuration['texto1'];
    $texto2 = $this->configuration['texto2'];
    $texto_enlace = $this->configuration['texto_enlace'];
    $color_bot = $this->configuration['color_bot'];
    $maquetacion = $this->configuration['maquetacion'];
    $visibility = $this->configuration['visibility'];
    $ocultar = $this->configuration['ocultar'];
    $custom_class = $this->configuration['custom_class'];
    $posicion_btn = $this->configuration['posicion_btn'];
    $alineacion_icon = $this->configuration['alineacion_icon'];

    return [
      '#theme' => 'content_cta2col',
      '#img1' => $file_url,
      '#gradient' => $gradient,
      '#img2' => $file_url2,
      '#icon' => $icon_url,
      '#texto1' => $texto1,
      '#texto2' => $texto2,
      '#target' => $target,
      '#enlace' => $enlace,
      '#texto_enlace' => $texto_enlace,
      '#color_bot' => $color_bot,
      '#maquetacion' => $maquetacion,
      '#color_fondo' => $color_fondo,
      '#visibility' => $visibility,
      '#ocultar' => $ocultar,
      '#custom_class' => $custom_class,
      '#posicion_btn' => $posicion_btn,
      '#alineacion_icon' => $alineacion_icon,
    ];
  }

}
