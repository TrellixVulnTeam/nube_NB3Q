<?php

namespace Drupal\royal_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\node\Entity\Node;


/**
 * Perform image saving from the wordpress site into the drupal filesystem and database.
 * This plugin is a standalone process triggered by a migration but it will not return any value.
 *
 * @MigrateProcessPlugin(
 *   id = "recetas_manage_pasos"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_pasos
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageRecetasPasos extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // We want to get several fields from the migration so we retrieve all.
    $row_data = $row->getSource();
    $id_receta = $row_data['id_receta'];
    // Searching for the related node (post).
    $nodes = \Drupal::entityManager()
    ->getStorage('node')
    ->loadByProperties([
      'type' => 'receta',
      'field_old_id' => $id_receta
    ]);

    if (empty($nodes)) {
      echo "\n No hay receta con 'field_old_id' = $id_receta \n";
      throw new MigrateSkipRowException("\n No hay receta con 'field_old_id' = $id_receta \n");
      return;
    }

    $node = reset($nodes);

    // Creamos el nuevo nodo "Pasos"
    $paso = Node::create([
      'title' => 'Paso '.$row_data['orden'],
      'type' => 'paso_receta',
      'field_orden' => $row_data['orden'],
      'field_descripcion' => $row_data['descripcion'],
      'moderation_state' => 'published',
    ]);
    $paso->save();

    // Lo asignamos a la receta
    $node->field_paso[] = $paso;

    $node->save();

    return null ;
  }
}



