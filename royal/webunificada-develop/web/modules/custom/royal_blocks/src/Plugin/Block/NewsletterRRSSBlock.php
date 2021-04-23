<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Newsletter RRSS block.
 *  
 * @Block(
 *   id = "newsletter_rrss_block",
 *   admin_label = @Translation("Newsletter y RRSS")
 * )
 */
class NewsletterRRSSBlock extends BlockBase {

    /**
       * {@inheritdoc}
       */
      public function build() {
        return [
            '#theme' => 'content_newsletter_rrss',
            '#attributes' => [
                'class' => ['col','d-flex','align-items-center'],
            ],
        ];
    }

}