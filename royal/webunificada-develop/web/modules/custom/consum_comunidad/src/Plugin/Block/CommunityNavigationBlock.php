<?php

namespace Drupal\consum_comunidad\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Community Navigation block.
 *
 * @Block(
 *   id = "community_navigation_block",
 *   admin_label = @Translation("Community Navigation")
 * )
 */
class CommunityNavigationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'content_community_navigation',
    ];
  }

}
