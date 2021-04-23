<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Logo block.
 *
 * @Block(
 *   id = "logo-block",
 *   admin_label = @Translation("Logo")
 * )
 */
class ConsumThemeLogoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'consum_theme_logo',
      '#attributes' => [
        'class' => ['col-auto'],
      ],
    ];
  }

}
