<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Horario AC block.
 *
 * @Block(
 *   id = "horario_ac_block",
 *   admin_label = @Translation("Horario Atención al Cliente")
 * )
 */
class HorarioACBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_horario_ac',
      '#attributes' => [
        'class' => ['col-12','col-sm-12','col-md','d-flex','align-items-center','justify-content-center','justify-content-sm-center','justify-content-md-end'],
      ],
    ];
  }

}