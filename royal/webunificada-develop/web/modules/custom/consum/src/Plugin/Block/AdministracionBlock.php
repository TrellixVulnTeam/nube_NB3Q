<?php

namespace Drupal\consum\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Administration block.
 *
 * @Block(
 *   id = "administracion_block",
 *   admin_label = @Translation("Administración Consum"),
 * )
 */
class AdministracionBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Aquí va toda la programática del bloque de administración (o no)'),
    ];
  }

}
