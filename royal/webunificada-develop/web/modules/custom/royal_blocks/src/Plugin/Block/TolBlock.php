<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Implements TOL block.
 *
 * @Block(
 *   id = "tol_block",
 *   admin_label = @Translation("Tol")
 * )
 */
class TolBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_tol',
      '#attributes' => [
        'class' => [
          'col-1',
          'col-sm-auto',
          'd-flex',
          'align-items-center',
          'order-6',
          'order-sm-7',
        ],
      ],
    ];
  }

}
