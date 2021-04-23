<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Column Break block.
 *
 * @Block(
 *   id = "column_break_block",
 *   admin_label = @Translation("Column Break")
 * )
 */
class ColumnBreakBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#attributes' => [
        'class' => ['w-100'],
      ],
    ];
  }

}