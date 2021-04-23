<?php

namespace Drupal\housework_organizer\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'HouseWorkOrganizer' block.
 *
 * @Block(
 *  id = "housework_organizer",
 *  admin_label = @Translation("Organizador de tareas"),
 * )
 */
class HouseWorkOrganizer extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return ['#theme' => 'housework_organizer'];
  }

}
