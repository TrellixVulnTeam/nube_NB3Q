<?php

namespace Drupal\consum_sendgrid\Plugin\Block;

use Drupal\Core\Block\BlockBase;

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
  public function build() { //añadir a una pag y con entrar en ella se mandaría.
    $connection = new SendGridConnection();
    $result = $connection->RequestSendGrid('prueba27@gmail.com');
    //enlazar con el formulario que me pase
  }

}
