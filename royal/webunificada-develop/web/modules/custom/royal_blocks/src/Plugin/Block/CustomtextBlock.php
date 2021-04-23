<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines CustomText block.
 *
 * @Block(
 *   id = "customtext_block",
 *   admin_label = @Translation("Texto personalizado")
 * )
 */
class CustomtextBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'text1' => '',
      'alto' => '',
      'customHeiht' => '',
      'color_fondo' => '',
      'custom_color' => 'sin-fondo',
      'visibility' => '',
      'ocultar' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['texto1'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Título del CTA'),
      '#default_value' => $this->configuration['texto1']['value'],
      '#format' => 'rich_text',

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
    $list_color_fondo = [
      'sin-fondo' => $this->t('Sin fondo'),
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

    // Save configurations.
    $this->configuration['texto1'] = $form_state->getValue('texto1');
    $this->configuration['alto'] = $form_state->getValue('alto');
    $this->configuration['customHeight'] = $form_state->getValue('customHeight');
    $this->configuration['color_fondo'] = $form_state->getValue('color_fondo');
    $this->configuration['custom_color'] = $form_state->getValue('custom_color');
    $this->configuration['visibility'] = $form_state->getValue('visibility');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

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
      $customHeight =  $this->configuration['customHeight'];
      $alto = '';
    } else {
      $alto = $this->configuration['alto'];
      $customHeight = '';
    }

    $texto1 = $this->configuration['texto1'];
    $visibility = $this->configuration['visibility'];
    $ocultar = $this->configuration['ocultar'];

    return [
      '#theme' => 'content_customtext',
      '#texto1' => $texto1,
      '#alto' => $alto,
      '#customHeight' => $customHeight . 'px',
      '#color_fondo' => $color_fondo,
      '#visibility' => $visibility,
      '#ocultar' => $ocultar,
    ];
  }

}
