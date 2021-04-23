<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Btn Hamburguesa block.
 *
 * @Block(
 *   id = "btn-hamburguesa-block",
 *   admin_label = @Translation("Botón hamburguesa")
 * )
 */
class ConsumThemeBtnhamburguesaBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'consum_theme_btnHamburguesa',
      '#attributes' => [
        'class' => [
          'col-2',
          'col-sm-auto',
          'd-flex',
          'align-items-center',
          'order-1',
          'order-sm-1',
        ],
      ],
      '#attached' => [
        'library' => ['consum_theme_blocks/btnhamburguesa'],
      ],
    ];
  }

}
