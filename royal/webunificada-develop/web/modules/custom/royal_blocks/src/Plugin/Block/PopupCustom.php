<?php

/**
 * @file
 * Contains \Drupal\royal_blocks\Plugin\Block\PopupCustom.
 */

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 *
 * @Block(
 *   id = "popupcustom",
 *   admin_label = @Translation("Popup Custom")
 * )
 */
class PopupCustom extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  // Injection services

 public function __construct(array $configuration, 
                              $plugin_id, 
                              $plugin_definition, 
                              EntityTypeManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
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
      'image1' => '',
      'gradient' => '',
      'image2' => '',
      'texto1' => '',
      'texto2' => '',
      'text_modal' => '',
      'id_modal' => '',
      'alto' => '',
      'customHeiht' => '',
      'color_bot' => 'boton1',
      'color_fondo' => 'sin-fondo',
      'ocultar' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Load nodes storage
    $node_storage = $this->entityManager->getStorage('node');

    $form['image1'] = array(
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen principal'),
      '#upload_validators'    => [
        'file_validate_is_image' => array(),
        'file_validate_extensions' => array('gif png jpg jpeg'),
        'file_validate_size' => array(25600000)
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image1']) ? $this->configuration['image1'] : '',
      '#description' => t('Imagen de fondo'),
      '#required' => false,
    );

    $form['gradient'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Apply Gradient'),
      '#default_value' => isset($this->configuration['gradient']) ? $this->configuration['gradient'] : '',
    );

    $form['image2'] = array(
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen mobile'),
      '#upload_validators'    => [
        'file_validate_is_image' => array(),
        'file_validate_extensions' => array('gif png jpg jpeg'),
        'file_validate_size' => array(25600000)
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => t('Imagen que se mostrará en dispositivos móviles'),
      '#required' => false
    );
    $form['texto1'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Título del CTA'),
      '#default_value' => $this->configuration['texto1']['value'],
      '#format' => 'rich_text',
    );
    $form['texto2'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Texto del botón'),
      '#default_value' => $this->configuration['texto2'],
    );
  
    $form['modal_text'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Modal Text'),
      '#default_value' => $this->configuration['modal_text']['value'],
      '#format' => 'rich_text',
    );

    $list_color_bot = array(
      'boton1' => $this->t('Principal'),
      'boton2' => $this->t('Monocromo naranja'),
      'boton3' => $this->t('Monocromo negro'),
    );
    $form['color_bot'] = array(
      '#type' => 'select',
      '#title' => t('Color de fondo del botón'),
      '#default_value' => $this->configuration['color_bot'],
      '#options' => $list_color_bot,
    );
    $list_color_fondo = array(
      'sin-fondo' => $this->t('Sin color de fondo'),
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
    );
    $form['color_fondo'] = array(
      '#type' => 'select',
      '#title' => $this->t('Color de fondo'),
      '#default_value' => $this->configuration['color_fondo'],
      '#description' => t('Si el color elegido es blanco o "Sin color de fondo", el color del título pasará a ser gris corporativo.'),
      '#options' => $list_color_fondo,
    );   
   
    $list_alto = array(
      'customblock-h' => $this->t('Alto estándar (270px)'),
      'customblock-2h' => $this->t('Alto doble (570px)'),
    );
    $form['alto'] = array(
      '#type' => 'select',
      '#title' => t('Altura del bloque'),
      '#default_value' => isset($this->configuration['alto']) ? $this->configuration['alto'] : '',
      '#empty_value' => '_none',
      '#options' => $list_alto,
    );
    $form['customHeight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Custom Height'),
      '#description' => t('Determine your custom height. Only numbers.'),
      '#default_value' => isset($this->configuration['customHeight']) ? $this->configuration['customHeight'] : '',
      '#size' => 6,
      '#maxlength' => 3,
    ];
    $list_ocultar = array(
      'movil' => $this->t('Ocultar en móvil'),
      'tablet' => $this->t('Ocultar en Tablet'),
      'escritorio' => $this->t('Ocultar en Escritorio'),
    );
    $form['ocultar'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Ocultar en dispositivos'),
      '#default_value' => $this->configuration['ocultar'],
      '#options' => $list_ocultar,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    if ($form_state->getValue('target') == "_blank"){
      $link = $form_state->getValue('link');    
      if (substr_count($link, 'http') < 1) {
        $form_state->setErrorByName('link',
        $this->t('You must use preffix "http://" or "https://" in domain'));
      }
    }
    if (($form_state->getValue('alto') != '_none') && $form_state->getValue('customHeight')){
      $form_state->setErrorByName('customHeight',
      $this->t('You must choose between custom height or select height'));
    }
    if (preg_match("/[^0-9]/", $form_state->getValue('customHeight')) == 1) {
      $form_state->setErrorByName('customHeight',
      $this->t('You can only introduce numbers'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Load file object from storage
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
    // Save configurations.
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['gradient'] = $form_state->getValue('gradient');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['texto1'] = $form_state->getValue('texto1');
    $this->configuration['texto2'] = $form_state->getValue('texto2');
    $this->configuration['modal_text'] = $form_state->getValue('modal_text');
    $this->configuration['color_bot'] = $form_state->getValue('color_bot');
    $this->configuration['color_fondo'] = $form_state->getValue('color_fondo');
    $this->configuration['alto'] = $form_state->getValue('alto');
    $this->configuration['customHeight'] = $form_state->getValue('customHeight');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');

    if (!$this->configuration['modal_id']) {
      $fecha = \Drupal::time()->getCurrentTime();
      $this->configuration['modal_id'] = $fecha;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Getting entity file storage
    $file_storage = $this->entityManager->getStorage('file');

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

    $image1 = $this->configuration['image1'];
    $gradient = $this->configuration['gradient'];
    $image2 = $this->configuration['image2'];
    $texto1 = $this->configuration['texto1'];
    $texto2 = $this->configuration['texto2'];
    $modal_text = $this->configuration['modal_text'];
    $modal_id = $this->configuration['modal_id'];
    $color_bot = $this->configuration['color_bot'];    
    $color_fondo = $this->configuration['color_fondo'];
    $alto = $this->configuration['alto'];
    $customHeight = $this->configuration['customHeight'];
    $ocultar = $this->configuration['ocultar'];

    return array(
      '#theme' => 'content_popup_custom',
      '#img1' => $file_url,
      '#gradient' => $gradient,
      '#img2' => $file_url2,
      '#texto2' => $texto2,
      '#texto1' => $texto1,
      '#modal_text' => $modal_text,
      '#modal_id' => $modal_id,
      '#color_bot' => $color_bot,
      '#color_fondo' => $color_fondo,
      '#alto' => $alto,
      '#customHeight' => $customHeight.'px',
      '#ocultar' => $ocultar,
    );
  }
}
