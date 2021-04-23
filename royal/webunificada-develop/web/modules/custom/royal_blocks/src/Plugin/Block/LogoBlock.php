<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Logo block.
 *
 * @Block(
 *   id = "logo_block",
 *   admin_label = @Translation("Logo")
 * )
 */
class LogoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_logo',
      '#attributes' => [
        'class' => ['col-auto'],
      ],
    ];
  }

}
