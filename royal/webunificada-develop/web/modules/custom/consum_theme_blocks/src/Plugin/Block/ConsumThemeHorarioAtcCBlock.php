<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Horario AtcC block.
 *
 * @Block(
 *   id = "horario-atcc-block",
 *   admin_label = @Translation("Horario y Atención al Cliente")
 * )
 */
class ConsumThemeHorarioAtcCBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'consum_theme_horarioAtcC',
      '#attributes' => [
        'class' => ['col-12','col-sm-12','col-md','d-flex','align-items-center','justify-content-center','justify-content-sm-center','justify-content-md-end'],
      ],
    ];
  }

}