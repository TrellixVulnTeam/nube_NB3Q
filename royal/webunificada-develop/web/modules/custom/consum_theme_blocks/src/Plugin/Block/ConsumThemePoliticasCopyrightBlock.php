<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Polytics and Copyright block.
 *
 * @Block(
 *   id = "politicas-copyright-block",
 *   admin_label = @Translation("Políticas y copyright")
 * )
 */
class ConsumThemePoliticasCopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'consum_theme_politicasCopyright',
      '#attributes' => [
        'class' => ['col-12', 'order-12'],
      ],
    ];
  }

}
