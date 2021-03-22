<?php

namespace Drupal\consum_sendgrid\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
/**
 * Defines Sendgrid block.
 *
 * @Block(
 *   id = "sendgrid-block",
 *   admin_label = @Translation("SendGrid")
 * )
 */
class ConsumSendGridBlock extends BlockBase {


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
    $this->configuration['heigth'] = $form_state->getValue('heigth');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => 'Sendgrid',
    ];
  }

}
