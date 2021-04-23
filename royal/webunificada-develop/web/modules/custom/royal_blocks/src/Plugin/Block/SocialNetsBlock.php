<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'SocialNets' block.
 *
 * @Block(
 *  id = "social_nets_block",
 *  admin_label = @Translation("Social Networks"),
 * )
 */
class SocialNetsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'choose_social_net' => 'Twitter',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['choose_social_net'] = [
      '#type' => 'select',
      '#title' => $this->t('Choose social net'),
      '#options' => [
        'Twitter' => $this->t('Twitter'),
        'Instagram' => $this->t('Instagram'),
        'Facebook' => $this->t('Facebook'),
        'Youtube' => $this->t('Youtube'),
      ],
      '#default_value' => $this->configuration['choose_social_net'],
      '#size' => 4,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['choose_social_net'] = $form_state->getValue('choose_social_net');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#theme' => 'content_social_nets',
      '#social_net' => $this->configuration['choose_social_net'],
      '#image' => '/sites/default/files/twitter.jpg',
    ];

  }

}
