<?php

/**
 * @file
 * Contains \Drupal\consum_theme_blocks\Plugin\Block\LocalizacionBlock.
 */

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 * @Block(
 *   id = "localizacion-block",
 *   admin_label = @Translation("Localización")
 * )
 */
class ConsumThemeLocalizacionBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return array(
      '#theme' => 'consum_theme_localizacion',
      '#attributes' => array(
        /*'class' => array('col-auto','col-sm-auto','col-md-auto','col-lg-3','d-flex','align-items-center','justify-content-end','order-4','order-sm-5'),*/ //Para mostrar texto de dirección
        'class' => array('col-auto','col-sm-auto','col-md-auto','col-lg-auto','d-flex','align-items-center','justify-content-end','order-4','order-sm-5'),
      ),
    );
  }
}