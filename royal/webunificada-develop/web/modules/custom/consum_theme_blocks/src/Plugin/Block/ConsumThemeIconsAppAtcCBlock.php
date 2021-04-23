<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines IconsAPP AtcC block.
 *
 * @Block(
 *   id = "iconsapp-atcc-block",
 *   admin_label = @Translation("Iconos App y Botón de atención al cliente")
 * )
 */
class ConsumThemeIconsAppAtcCBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'consum_theme_iconsAppAtcC',
      '#attributes' => [
        'class' => ['col-md','d-flex','align-items-center','justify-content-center'],
      ],
    ];
  }

}
