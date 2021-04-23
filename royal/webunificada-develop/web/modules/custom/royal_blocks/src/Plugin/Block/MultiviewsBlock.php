<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines multiviews block.
 *
 * @Block(
 *   id = "multiviews",
 *   admin_label = @Translation("Artículos destacados")
 * )
 */
class MultiviewsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'destacado' => 'A',
      'maquetacion' => 'card',
      'block_category' => 'destacados_magazine_card',
      'ocultar' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $list_terms = [
      'destacados_magazine_card' => 'Magazine (General)',
      'destacados_alimentacion' => 'Alimentación',
      'destacados_estilo_de_vida' => 'Estilo de vida',
      'destacados_salud' => 'Salud',
      'destacados_belleza' => 'Belleza',
      'destacados_en_familia' => 'En familia',
      'destacados_hogar' => 'Hogar',
    ];

    $form['category'] = [
      '#type' => 'select',
      '#title' => t('Selecciona la categoría del contenido a mostrar'),
      '#default_value' => $this->configuration['category'],
      '#options' => $list_terms,
    ];

    $list_destacado = [
      '1' => 'Destacado A',
      '2' => 'Destacado B',
      '3' => 'Destacado C',
      '4' => 'Destacado D',
      '5' => 'Destacado E',
      '6' => 'Destacado F',
      '7' => 'Destacado G',
      '8' => 'Destacado H',
      '9' => 'Destacado I',
      '10' => 'Destacado J',
    ];

    $form['destacado'] = [
      '#type' => 'select',
      '#title' => t('Selecciona la posición del artículo destacado'),
      '#default_value' => $this->configuration['destacado'],
      '#options' => $list_destacado,
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
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['maquetacion'] = $form_state->getValue('maquetacion');
    $this->configuration['category'] = $form_state->getValue('category');
    $this->configuration['destacado'] = $form_state->getValue('destacado');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $maquetacion = $this->configuration['maquetacion'];
    $destacado = $this->configuration['destacado'];
    $category = $this->configuration['category'];
    $ocultar = $this->configuration['ocultar'];

    $pos_destacado = 'block_' . $destacado;

    if (!$category) {
      $category = 'destacados_magazine_card';
    }

    $view = views_embed_view($category, $pos_destacado);

    if (!$view) {
      $view = views_embed_view('destacados_magazine_card', $pos_destacado);
    }

    return [
      '#theme' => 'content_multiviews',
      '#vista' => $view,
      '#ocultar' => $ocultar,
    ];

  }

}
