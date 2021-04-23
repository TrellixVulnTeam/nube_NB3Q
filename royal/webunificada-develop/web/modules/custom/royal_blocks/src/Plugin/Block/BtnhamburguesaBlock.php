<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Btn burguer block.
 *
 * @Block(
 *   id = "btnhamburguesa_block",
 *   admin_label = @Translation("Botón hamburguesa")
 * )
 */
class BtnhamburguesaBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_btnhamburguesa',
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
        'library' => ['royal_blocks/btnhamburguesa'],
      ],
    ];
  }

}
