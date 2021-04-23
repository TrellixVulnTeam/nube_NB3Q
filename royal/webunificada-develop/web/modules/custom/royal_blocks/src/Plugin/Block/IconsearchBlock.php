<?php

/**
 * @file
 * Contains \Drupal\royal_blocks\Plugin\Block\IconsearchBlock.
 */

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 * @Block(
 *   id = "iconsearch_block",
 *   admin_label = @Translation("Icono Search")
 * )
 */
class IconsearchBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'content_iconsearch',
      '#attributes' => array(
        'class' => array('col-2','d-flex','d-sm-flex','d-md-none','align-items-center','order-2', 'order-sm-4'),
      ),
    );
  }
}