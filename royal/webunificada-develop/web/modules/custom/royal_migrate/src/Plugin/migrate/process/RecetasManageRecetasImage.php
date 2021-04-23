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
 *   id = "recetas_manage_recetas_image"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_recetas_image
 *   source: old_id
 * @endcode
 *
 */
class RecetasManageRecetasImage extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $source = "https://planificador.consum.es/bd/imagenes/recetas/$value.JPG";
    // Creating the file with the same filename.
    try {
      $new_file = system_retrieve_file($source,  "public://images/recetas/$value.JPG" , true);
    } catch (\Exception $e) {
      $message = "\nError retrieving the file: $source = Detail: $e->getMessage() \n";
      echo $message;
      // throw new MigrateSkipRowException($message);
    }

    if(!$new_file) {
      $message = "\nError retrieving the file: $source \n";
      echo $message;
      // throw new MigrateSkipRowException($message);
      return;
    }

    $value = $new_file;
    return $value;

  }
}



