<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Defines Newsletter RRSS block.
 *  
 * @Block(
 *   id = "newsletter-rrss-block",
 *   admin_label = @Translation("Newsletter y RRSS")
 * )
 */
class ConsumThemeNewsletterRRSSBlock extends BlockBase {

    /**
       * {@inheritdoc}
       */
      public function build() {
        return [
            '#theme' => 'consum_theme_newsletterRrss',
            '#attributes' => [
                'class' => ['col','d-flex','align-items-center'],
            ],
        ];
    }

}