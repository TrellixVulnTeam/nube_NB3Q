<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Newsletter' block.
 *
 * @Block(
 *  id = "newsletter_block",
 *  admin_label = @Translation("Newsletter Block"),
 * )
 */
class NewsletterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'orientation' => 'vertical',
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
        'vertical' => $this->t('Vertical'),
        'horizontal' => $this->t('Horizontal'),
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

    if ($this->configuration['orientation'] == 'vertical') {
      $newsletter_img = '/sites/default/files/v_newsletter.jpg';
    }
    elseif ($this->configuration['orientation'] == 'horizontal') {
      $newsletter_img = '/sites/default/files/h_newsletter.jpg';
    }
    else {
      $newsletter_img = '';
    }

    return [
      '#theme' => 'content_newsletter',
      '#newsletter_img' => $newsletter_img,
      '#heigth' => $this->configuration['heigth'],
    ];
  }

}
