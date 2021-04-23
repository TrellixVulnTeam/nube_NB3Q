<?php

/**
 * @file
 * Contains \Drupal\consum_cta_blocks\Plugin\Block\ConsumCtaBlockBase.
 */

namespace Drupal\consum_cta_blocks\Plugin\Block;

use Drupal\consum_custom_blocks\Plugin\Block\ConsumCustomBlockBase;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Defines a base block implementation that most Consum CTA custom blocks plugins will extend.
 *
 * This abstract class provides the generic block configuration form, default
 * block settings, and handling for general user-defined block visibility
 * settings.
 *
 * @ingroup consum
 */
abstract class ConsumCtaBlockBase extends ConsumCustomBlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $element = parent::defaultConfiguration();
    $element['gradient'] = '';
    $element['text2'] = $this->t('Leer más');
    $element['text3'] = '';
    $element['target'] = '';
    $element['autocomplete'] = '';
    $element['link'] = '';
    $element['button_color'] = 'boton1';
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['gradient'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Apply Gradient'),
      '#default_value' => isset($this->configuration['gradient']) ? $this->configuration['gradient'] : '',
    ];

    $form['text2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texto del botón'),
      '#default_value' => $this->configuration['text2'],
    ];

    $list_color_bot = [
      'boton1' => $this->t('Principal'),
      'boton2' => $this->t('Monocromo naranja'),
      'boton3' => $this->t('Monocromo negro'),
    ];
    $form['button_color'] = [
      '#type' => 'select',
      '#title' => t('Color de fondo del botón'),
      '#default_value' => $this->configuration['button_color'],
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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    // Save configurations.
    $this->configuration['gradient'] = $form_state->getValue('gradient');
    $this->configuration['text2'] = $form_state->getValue('text2');
    $this->configuration['target'] = $form_state->getValue('target');
    $this->configuration['autocomplete'] = $form_state->getValue('autocomplete');
    $this->configuration['link'] = $form_state->getValue('link');
    $this->configuration['button_color'] = $form_state->getValue('button_color');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $render = parent::build();
    $render['#gradient'] = $this->configuration['gradient'];
    $render['#text2'] = $this->configuration['text2'];
    if ($this->configuration['target'] == '_parent') {
      $render['#autocomplete'] = $this->configuration['autocomplete'];
    }
    else {
      $render['#link'] = $this->configuration['link'];
    }
    $render['#button_color'] = $this->configuration['button_color'];
    return $render;
  }

}
