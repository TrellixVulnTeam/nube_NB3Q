<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Polytics and Copyright block.
 *
 * @Block(
 *   id = "politicas_copyright_block",
 *   admin_label = @Translation("Políticas y copyright")
 * )
 */
class PoliticasCopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_politicas_copyright',
      '#attributes' => [
        'class' => ['col-12', 'order-12'],
      ],
    ];
  }

}
