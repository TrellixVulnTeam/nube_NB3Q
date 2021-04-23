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
 *   id = "recetas_manage_recetas_tipo_de_comida"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_recetas_tipo_de_comida
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageRecetasTipoDeComida extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */

  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $field_tipo_de_comida = $this->getTipoComida($value);

    $vocabulary = 'tipo_de_comida';
    $arr_terms = taxonomy_term_load_multiple_by_name($field_tipo_de_comida, $vocabulary);
    if (!empty($arr_terms)) {
      $arr_terms = array_values($arr_terms);
      $tid = $arr_terms[0]->tid;
    }
    return $value = $arr_terms[0];
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
//0	"S/C"
//1	"CARNE"
//2	"PESCADO"
//3	"VERDURA"
//4	"FRUTA"
//5	"PASTA"
//6	"LEGUMBRES"
//7	"ARROZ"
//8	"HUEVO"
//9	"LACTEO"
//10	"PATATA"
//11	"MASAS Y FRITOS"
  }
}



