<?php

namespace Drupal\consum_sendgrid\Plugin\Block;

use Drupal\Core\Block\BlockBase;
//use Drupal\consum_sendgrid\Services\SendGridConnection;

/**
 * Defines Sendgrid block.
 *
 * @Block(
 *   id = "sendgrid-block",
 *   admin_label = @Translation("SendGrid")
 * )
 */
class ConsumSendGridBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() { 
    $form = \Drupal::formBuilder()->getForm('Drupal\consum_sendgrid\Form\ConsumSendGridForm');
  }

}
