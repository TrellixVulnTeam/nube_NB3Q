<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Column Break block.
 *
 * @Block(
 *   id = "column-break-block",
 *   admin_label = @Translation("Column Break")
 * )
 */
class ConsumThemeColumnBreakBlock extends BlockBase {

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