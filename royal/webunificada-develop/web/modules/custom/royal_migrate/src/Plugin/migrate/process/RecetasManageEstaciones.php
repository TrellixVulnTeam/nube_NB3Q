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
 *   id = "recetas_manage_estaciones"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_estaciones
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageEstaciones extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $estaciones = $this->getEstaciones();
    $result = [];

    foreach ($estaciones as $indice => $valor){
      $potencia=pow(2,$indice);
      if(($value & $potencia)==$potencia) {
        $vocabulary = 'estacion';
        $arr_terms = taxonomy_term_load_multiple_by_name($valor, $vocabulary);
        if (!empty($arr_terms)) {
          $arr_terms = array_values($arr_terms);
          $result[] = $arr_terms[0];
        }
      }
    }

    return  $value = $result;

  }

  protected function getEstaciones() {
    return array(
      1 => 'Primavera',
      2 => 'Verano',
      3 => 'Otoño',
      4 => 'Invierno'
    );
  }

}



