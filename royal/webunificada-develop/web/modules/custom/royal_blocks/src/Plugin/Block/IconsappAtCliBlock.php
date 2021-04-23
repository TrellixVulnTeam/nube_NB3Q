<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines IconsAPP ATC block.
 *
 * @Block(
 *   id = "iconsapp_atcli_block",
 *   admin_label = @Translation("Iconos App y Botón de atención al cliente")
 * )
 */
class IconsappAtCliBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_iconsapp_atcli',
      '#attributes' => [
        'class' => ['col-md','d-flex','align-items-center','justify-content-center'],
      ],
    ];
  }

}
