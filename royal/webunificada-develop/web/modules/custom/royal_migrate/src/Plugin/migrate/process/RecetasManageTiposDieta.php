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
 *   id = "recetas_manage_tipos_dieta"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_tipos_dieta
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageTiposDieta extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   * field_tipo_de_plato
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $tipos_dieta = $this->getTiposDieta();
    $result = [];

    foreach ($tipos_dieta as $indice => $valor){
      $potencia=pow(2,$indice);
      if(($value & $potencia)==$potencia) {
        $vocabulary = 'tipo_de_dieta';
        $arr_terms = taxonomy_term_load_multiple_by_name($valor, $vocabulary);
        if (!empty($arr_terms)) {
          $arr_terms = array_values($arr_terms);
          $result[] = $arr_terms[0];
        }
      }
    }

    return  $value = $result;

  }

  protected function getTiposDieta() {
    return array(
      0 => 'Vegetariana',
      1 => 'Rico en fibra',
      2 => 'Me dejo aconsejar',
      3 => 'Ligera',
      4 => 'Tengo pareja',
      5 => 'Tengo hijos',
      6 => 'Gourmet',
      7 => 'Vivo solo'
    );
  }

}



