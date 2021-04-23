<?php

/**
 * @file
 * Contains \Drupal\consum_theme_blocks\Plugin\Block\ConsumThemeIconSearchBlock.
 */

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 * @Block(
 *   id = "icon-search-block",
 *   admin_label = @Translation("Icon Search")
 * )
 */
class ConsumThemeIconSearchBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'consum_theme_iconSearch',
      '#attributes' => array(
        'class' => array('col-2','d-flex','d-sm-flex','d-md-none','align-items-center','order-2', 'order-sm-4'),
      ),
    );
  }
}