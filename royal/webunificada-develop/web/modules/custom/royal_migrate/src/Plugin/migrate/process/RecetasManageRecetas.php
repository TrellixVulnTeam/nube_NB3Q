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
 *   id = "recetas_manage_recetas"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_recetas
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageRecetas extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */

/*
 * Fields managed here:
 *
 * field_tipo_de_comida
 * field_estacion
 *
 */

  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // We want to get several fields from the migration so we retrieve all.
    $row_data = $row->getSource();

    $field_tipo_de_comida = $this->getTipoComida($row_data['field_tipo_de_comida']);
//    $field_estacion = $this->getEstacion($row_data['field_estacion']);

    $cantidad = $row_data['cantidad'];
    $id_unimedida = $row_data['id_unimedida'];
    $orden = $row_data['orden'];
    $id_receta = $row_data['id_receta'];
    $nombre = $row_data['nombre'];


    // Searching for the related node (post).
    $nodes = \Drupal::entityManager()
    ->getStorage('node')
    ->loadByProperties([
      'type' => 'receta',
      'field_old_id' => $id_receta,
      ]);

    if (empty($nodes)) {
      $message = $nombre . ' => No se encuentra la receta relacionada ('.$id_receta.')a este ingrediente.' . "\n";
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

  protected function getTipoComida($id_tipocomida) {

    switch ($id_tipocomida) {
      case '0':
        return false;
        break;
      case '1':
        return 'Carnes y aves';
        break;
      case '2':
        return 'Pescados y mariscos';
        break;
      case '3':
        return 'Verduras y hortalizas';
        break;
      case '4':
        return 'Frutas';
        break;
      case '5':
        return 'Pastas';
        break;
      case '6':
        return 'Cereales y Legumbres';
        break;
      case '7':
        return 'Arroces';
        break;
      case '8':
        return 'Huevos y lácteos';
        break;
      case '9':
        return 'Huevos y lácteos';
        break;
      case '10':
        return 'Verduras y hortalizas';
        break;
      case '11':
        return 'Otros';
        break;
    }
  }
}



