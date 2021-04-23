<?php

namespace Drupal\royal_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipRowException;


/**
 * Perform image saving from the wordpress site into the drupal filesystem and database.
 * This plugin is a standalone process triggered by a migration but it will not return any value.
 *
 * @MigrateProcessPlugin(
 *   id = "recetas_manage_ingredientes"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_ingredientes
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageIngredientes extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // We want to get several fields from the migration so we retrieve all.
    $row_data = $row->getSource();

    $nombre = $row_data['nombre'];
    $cantidad = $row_data['cantidad'];
    $id_unimedida = $row_data['id_unimedida'];
    $orden = $row_data['orden'];
    $id_receta = $row_data['id_receta'];


    // Searching for the related node (post).
    $nodes = \Drupal::entityManager()
    ->getStorage('node')
    ->loadByProperties([
      'type' => 'receta',
      'field_old_id' => $id_receta,
      ]);

    if (empty($nodes)) {
      $message = $nombre . ' => No se encuentra la receta relacionada ('.$id_receta.') a este ingrediente.' . "\n";
      echo $message;
      throw new MigrateSkipRowException($message);
      return;
    }

    // Obtenemos la unidad de medida del ingrediente.
    $unidad_medida = $this->getUnidadFromId($id_unimedida);


    // Relating the new created file to his corresponding node.
    $node = reset($nodes);

    $nombre = (!empty($nombre)) ? $nombre  : '';

    $node->field_ingrediente[] = [
      'nombre' => $nombre,
      'cantidad' => $cantidad,
      'unidad' => $unidad_medida,
    ];

    $node->save();

    return null ;
  }

  protected function getUnidadFromId($id_umedida) {
    switch ($id_umedida) {
      case '10':
        return 'g';
        break;
      case '20':
        return 'ml';
        break;
      case '30':
        return 'rama';
        break;
      case '40':
        return 'unidad';
        break;
      case '50':
        return 'diente';
        break;
      case '60':
        return 'hoja';
        break;
      case '90':
        return 'loncha';
        break;

    }

  }
}



