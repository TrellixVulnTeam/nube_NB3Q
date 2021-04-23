<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines Carousel Unlimited.
 *
 * @Block(
 *   id = "carousel_unlimited_block",
 *   admin_label = @Translation("Carousel Unlimited")
 * )
 */
class CarouselUnlimitedBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    return parent::defaultConfiguration() + [
      'items' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $items = [];

    if (!$form_state->has('items_temp')) {
      $form_state->set('items_temp', $this->configuration['items']);
    }
    $items = $form_state->get('items_temp');
    
    $form['#tree'] = TRUE;

    $form['items_carousel'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Items carousel'),
      '#prefix' => '<div id="items-carousel-wrapper">',
      '#suffix' => '</div>',
    ];

    if (!$form_state->has('num_items')) {
      if ($this->configuration['num_items'] < 1){
        $form_state->set('num_items', 1);
      }else{
        $form_state->set('num_items', $this->configuration['num_items']);
      }
    }

    $name_field = $form_state->get('num_items');

    for ($i = 1; $i <= $name_field; $i++) {

      $form_state->set('key_change','key_change_'.$form_state->get('num_items'));

      $items = array_values($items);

      $form['items_carousel']['item'.$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Item '.$i),
      ];

      $form['items_carousel']['item'.$i]['image_'.$form_state->get('key_change')] = [
        '#type' => 'managed_file',
        '#upload_location' => 'public://',
        '#title' => $this->t('Imagen '.$i),
        '#upload_validators'    => [
          'file_validate_is_image' => [],
          'file_validate_extensions' => ['gif png jpg jpeg'],
          'file_validate_size' => [25600000],
        ],
        '#theme' => 'image_widget',
        '#preview_image_style' => 'medium',
        '#default_value' => isset($items[$i-1]["image"]) ? $items[$i-1]["image"] : '',
        '#required' => FALSE,
      ];

      $form['items_carousel']['item'.$i]['texto_'.$form_state->get('key_change')] = [
        '#type' => 'text_format',
        '#title' => $this->t('Bloque de texto personalizado '.$i),
        '#default_value' => $items[$i-1]["text"]['value'],
        '#format' => 'rich_text',
      ];

      $list_positions = [
        'no-align' => $this->t('Sin definir'),
        'justify-content-center' => $this->t('Centrado'),
        'justify-content-start' => $this->t('Izquierda'),
        'justify-content-end' => $this->t('Derecha'),
      ];

      $form['items_carousel']['item'.$i]['position_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Posición texto '.$i),
        '#default_value' => $items[$i-1]["position"],
        '#options' => $list_positions,
      ];

      $list_align = [
        'no-align' => $this->t('Sin definir'),
        'text-center' => $this->t('Centrado'),
        'text-left' => $this->t('Izquierda'),
        'text-right' => $this->t('Derecha'),
      ];

      $form['items_carousel']['item'.$i]['align_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Alineación texto '.$i),
        '#default_value' => $items[$i-1]["align"],
        '#options' => $list_align,
      ];

      $list_color_fondo = [
        'sin-fondo' => $this->t('Sin definir'),
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

      $form['items_carousel']['item'.$i]['color_fondo_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Color de fondo '.$i),
        '#default_value' => $items[$i-1]["background_color"],
        '#options' => $list_color_fondo,
      ];

      /*$list_ocultar = [
        'movil' => $this->t('Ocultar en móvil'),
        'tablet' => $this->t('Ocultar en Tablet'),
        'escritorio' => $this->t('Ocultar en Escritorio'),
      ];

      $form['items_carousel']['item'.$i]['ocultar_'.$form_state->get('key_change')] = [
        '#type' => 'checkboxes',
        '#title' => t('Ocultar item en dispositivos '.$i),
        '#default_value' => $items[$i-1]["visibility"],
        '#options' => $list_ocultar,
      ];*/

      if ($name_field >= 2){
        $form['items_carousel']['item'.$i]['eliminar'.$i] = [
          '#type' => 'submit',
          '#value' => t('Eliminar'),
          '#name' => $i,
          '#submit' => [[$this, 'removeOne']],
          '#ajax' => [
            'callback' => [$this, 'removeOneCallback'],
            'wrapper' => 'items-carousel-wrapper',
          ]
        ];
      }
    }

    $list_ocultar = [
      'movil' => $this->t('Ocultar en móvil'),
      'tablet' => $this->t('Ocultar en Tablet'),
      'escritorio' => $this->t('Ocultar en Escritorio'),
    ];

    $form['ocultar'] = [
      '#type' => 'checkboxes',
      '#title' => t('Ocultar en dispositivos'),
      '#default_value' => $this->configuration['visibility'],
      '#options' => $list_ocultar,
    ];

    $form['items_carousel']['actions'] = [
      '#type' => 'actions',
    ];

    $form['items_carousel']['actions']['add_item'] = [
      '#type' => 'submit',
      '#value' => t('Añadir item'),
      '#submit' => [[$this, 'addMore']],
      '#ajax' => [
        'callback' => [$this, 'addMoreCallback'],
        'wrapper' => 'items-carousel-wrapper',
      ],
    ];

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function addMore(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_items');
    $add_button = $name_field + 1;
    $items_temp = [];
    for ($i = 1; $i <= $form_state->get('num_items'); $i++){
      $items_temp[$i-1] = [
        'image' => $form_state->getValue(['settings','items_carousel','item'.$i,'image_'.$form_state->get('key_change')]),
        'background_color' => $form_state->getValue(['settings','items_carousel','item'.$i,'color_fondo_'.$form_state->get('key_change')]),
        'position' => $form_state->getValue(['settings','items_carousel','item'.$i,'position_'.$form_state->get('key_change')]),
        'align' => $form_state->getValue(['settings','items_carousel','item'.$i,'align_'.$form_state->get('key_change')]),
        /*'visibility' => $form_state->getValue(['settings','items_carousel','item'.$i,'ocultar_'.$form_state->get('key_change')]),*/
        'text' => $form_state->getValue(['settings','items_carousel','item'.$i,'texto_'.$form_state->get('key_change')]),
      ];
    
    }
    $form_state->set('items_temp', $items_temp);
    $form_state->set('num_items', $add_button);
    $form_state->setRebuild();
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return mixed
   */
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    // The form passed here is the entire form, not the subform that is
    // passed to non-AJAX callback.
    return $form['settings']['items_carousel'];
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function removeOne(array &$form, FormStateInterface $form_state) {
    $key_delete = $form_state->getTriggeringElement()['#name'];
    $name_field = $form_state->get('num_items');
    $items_temp = [];
    $item = 1;
    for ($i = 1; $i <= $form_state->get('num_items'); $i++){
      if ($i == $key_delete){
        $item++; 
      }
      $items_temp[$i-1] = [
        'image' => $form_state->getValue(['settings','items_carousel','item'.$item,'image_'.$form_state->get('key_change')]),
        'background_color' => $form_state->getValue(['settings','items_carousel','item'.$item,'color_fondo_'.$form_state->get('key_change')]),
        'position' => $form_state->getValue(['settings','items_carousel','item'.$item,'position_'.$form_state->get('key_change')]),
        'align' => $form_state->getValue(['settings','items_carousel','item'.$item,'align_'.$form_state->get('key_change')]),
        /*'visibility' => $form_state->getValue(['settings','items_carousel','item'.$item,'ocultar_'.$form_state->get('key_change')]),*/
        'text' => $form_state->getValue(['settings','items_carousel','item'.$item,'texto_'.$form_state->get('key_change')]),
      ];
  
      $item++;
    }
    $form_state->set('items_temp', $items_temp);
    $remove_button = $name_field - 1;
    $form_state->set('num_items', $remove_button);
    $form_state->setRebuild();
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return mixed
   */
  public function removeOneCallback(array &$form, FormStateInterface $form_state) {
    // The form passed here is the entire form, not the subform that is
    // passed to non-AJAX callback.
    return $form['settings']['items_carousel'];
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Load file object from storage.
    $file_storage = $this->entityManager->getStorage('file');

    $this->configuration['items'] = [];
    for ($i = 1; $i <= $form_state->get('num_items'); $i++){
      // Save image $i as permanent.
      $image = $form_state->getValue(['items_carousel','item'.$i,'image_'.$form_state->get('key_change')]);
      if ($image != $this->configuration['items'][$i-1]["image"]) {
        if (!empty($image[0])) {
          $file = $file_storage->load($image[0]);
          $file->setPermanent();
          $file->save();
        }
      }

      $this->configuration['items'][$i-1] = [
        'image' => $form_state->getValue(['items_carousel','item'.$i,'image_'.$form_state->get('key_change')]),
        'url_image' => "",
        'text' => $form_state->getValue(['items_carousel','item'.$i,'texto_'.$form_state->get('key_change')]),
        /*'visibility' => $form_state->getValue(['items_carousel','item'.$i,'ocultar_'.$form_state->get('key_change')]),*/
        'background_color' => $form_state->getValue(['items_carousel','item'.$i,'color_fondo_'.$form_state->get('key_change')]),
        'position' => $form_state->getValue(['items_carousel','item'.$i,'position_'.$form_state->get('key_change')]),
        'align' => $form_state->getValue(['items_carousel','item'.$i,'align_'.$form_state->get('key_change')]),
      ];
    }
    
    // Save configurations.
    $this->configuration['num_items'] = $form_state->get('num_items');
    $this->configuration['visibility'] = $form_state->getValue('ocultar');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Getting entity file storage.
    $file_storage = $this->entityManager->getStorage('file');

    for ($i = 1; $i <= $this->configuration['num_items']; $i++){
      $url_img = "";
      $image = $this->configuration['items'][$i-1]["image"];
      if (!empty($image[0])) {
        if ($file = $file_storage->load($image[0])) {
          $url_img = $file->url();
        }
      }
      $this->configuration['items'][$i-1]["url_image"] = $url_img;
    }

    return [
      '#theme' => 'content_carousel_unlimited',
      '#items' => $this->configuration['items'],
      '#visibility' => $this->configuration['visibility'],
    ];
  }
}