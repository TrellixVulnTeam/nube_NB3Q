<?php

namespace Drupal\consum_cta_blocks\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Defines CTA 2 block.
 *
 * @Block(
 *   id = "consum_cta2_block",
 *   admin_label = @Translation("Click To Action 2 TEST")
 * )
 */
class ConsumCta2Block extends ConsumCtaBlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {

    $config =  parent::defaultConfiguration();

    /* set default for new attributes */
    $config['icon'] = NULL;
    $config['texto_enlace'] = NULL;
    $config['maquetacion'] = NULL;
    $config['posicion_btn'] = NULL;
    $config['alineacion_icon'] = NULL;

    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    unset($form['border']);

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
    $form['texto_enlace'] = [
      '#type' => 'textfield',
      '#title' => $this->t('link text'),
      '#default_value' => $this->configuration['texto_enlace'],
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


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);


    // Save icon as permanent.
    $icon = $form_state->getValue('icon');
    if ($icon != $this->configuration['icon']) {
      if (!empty($icon[0])) {
        $file3 = FILE::load($icon[0]);
        $file3->setPermanent();
        $file3->save();
      }
    }

    // Save configurations.
    $this->configuration['icon'] = $form_state->getValue('icon');
    $this->configuration['texto_enlace'] = $form_state->getValue('texto_enlace');
    $this->configuration['maquetacion'] = $form_state->getValue('maquetacion');
    $this->configuration['posicion_btn'] = $form_state->getValue('posicion_btn');
    $this->configuration['alineacion_icon'] = $form_state->getValue('alineacion_icon');


  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $render = parent::build();
    $render['#theme'] = 'consum_cta2';

    $icon = $this->configuration['icon'];
    $texto_enlace = $this->configuration['texto_enlace'];
    $maquetacion = $this->configuration['maquetacion'];
    $posicion_btn = $this->configuration['posicion_btn'];
    $alineacion_icon = $this->configuration['alineacion_icon'];



    if (!empty($icon[0])) {
      if ($file3 = File::load($icon[0])) {
        $icon_url = $file3->url();
      }
    }
    $render['#icon_url'] = $icon_url;
    $render['#icon'] = $icon;
    $render['#texto_enlace'] = $texto_enlace;
    $render['#maquetacion'] = $maquetacion;
    $render['#posicion_btn'] = $posicion_btn;
    $render['#alineacion_icon'] = $alineacion_icon;

    unset($render['#border']);

    return $render;
  }

}
