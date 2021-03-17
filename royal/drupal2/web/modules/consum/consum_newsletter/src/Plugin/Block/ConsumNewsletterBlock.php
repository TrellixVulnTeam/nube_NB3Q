<?php

namespace Drupal\consum_newsletter\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines Newsletter block.
 *
 * @Block(
 *   id = "newsletter-block",
 *   admin_label = @Translation("Newsletter")
 * )
 */
class ConsumNewsletterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'orientation' => 'horizontal',
      'heigth' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['orientation'] = [
      '#type' => 'select',
      '#title' => $this->t('Orientation'),
      '#description' => $this->t('Newsletter layer orientation of block'),
      '#options' => [
        'horizontal' => $this->t('Horizontal'),
        'vertical' => $this->t('Vertical'),
      ],
      '#default_value' => $this->configuration['orientation'],
      '#size' => 2,
    ];

    $form['heigth'] = [
      '#type' => 'select',
      '#title' => $this->t('Heigth'),
      '#description' => $this->t('Heigth layer newsletter of block'),
      '#options' => [
        '' => $this->t('Sin definir'),
        'customblock-h' => $this->t('Alto estándar (270px)'),
        'customblock-2h' => $this->t('Alto doble (570px)'),
      ],
      '#default_value' => $this->configuration['heigth'],
      '#size' => 3,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['orientation'] = $form_state->getValue('orientation');
    $this->configuration['heigth'] = $form_state->getValue('heigth');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'consum_newsletter',
      '#orientation' => $this->configuration['orientation'],
      '#heigth' => $this->configuration['heigth'],
    ];
  }

}
