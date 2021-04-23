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
 *   id = "recetas_manage_tipos_plato"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_tipos_plato
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageTiposPlato extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   * field_tipo_de_plato
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $tipos_plato = $this->getTiposPlato();
    $result = [];

    foreach ($tipos_plato as $indice => $valor){
      $potencia=pow(2,$indice);
      if(($value & $potencia)==$potencia) {
        $vocabulary = 'tipo_de_plato';
        $arr_terms = taxonomy_term_load_multiple_by_name($valor, $vocabulary);
        if (!empty($arr_terms)) {
          $arr_terms = array_values($arr_terms);
          $result[] = $arr_terms[0];
        }
      }
    }

    return  $value = $result;

  }

  protected function getTiposPlato() {
    return array(
      1 => 'Primero',
      2 => 'Segundo',
      3 => 'Guarnición',
      4 => 'Postre: Lácteo',
      5 => 'Postre: Repostería',
      6 => 'Postre: fruta',
      7 => 'Desayuno/Merienda',
      8 => 'Desayuno completo'
    );
  }

}



