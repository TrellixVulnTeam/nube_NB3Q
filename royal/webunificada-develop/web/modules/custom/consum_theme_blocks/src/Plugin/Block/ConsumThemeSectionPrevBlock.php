<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Defines Section Prev block.
 *
 * @Block(
 *   id = "section-prev-block",
 *   admin_label = @Translation("Section Prev")
 * )
 */
class ConsumThemeSectionPrevBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'target' => '',
      'autocomplete' => '',
      'link' => '',
      'position' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $list_target = [
      '_parent' => $this->t('Misma página'),
      '_blank' => $this->t('Nueva pestaña'),
    ];
    $form['target'] = [
      '#type' => 'select',
      '#title' => $this->t('Destino'),
      '#description' => $this->t('Indica el tipo de enlace hacia donde se redirigirá.'),
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
      '#description' => $this->t('Indica la sección hacia donde se redirigirá.'),
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
    $position_arrow = [
        'default' => $this->t('Por defecto'),
        'flex' => $this->t('En línea con el título'),
    ];
    $form['position'] = [
        '#type' => 'select',
        '#title' => $this->t('Posición'),
        '#description' => $this->t('Indica la posición del arrow.'),
        '#default_value' => $this->configuration['position'],
        '#options' => $position_arrow
      ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save configurations.
    $this->configuration['target'] = $form_state->getValue('target');
    $this->configuration['autocomplete'] = $form_state->getValue('autocomplete');
    $this->configuration['link'] = $form_state->getValue('link');
    $this->configuration['position'] = $form_state->getValue('position');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $enlace = '';
    // Switch to correct case to set $enlace value.
    switch ($this->configuration['target']) {
      case '_parent':
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
    $position = $this->configuration['position'];

    return [
      '#theme' => 'consum_theme_sectionPrev',
      '#target' => $target,
      '#enlace' => $enlace,
      '#position' => $position
    ];
  }

}
