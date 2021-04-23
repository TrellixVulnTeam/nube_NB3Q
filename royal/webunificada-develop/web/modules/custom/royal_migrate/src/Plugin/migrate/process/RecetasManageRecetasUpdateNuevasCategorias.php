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
 *   id = "recetas_manage_recetas_update_nuevas_categorias"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: recetas_manage_recetas_update_nuevas_categorias
 *   source: not needed*
 * @endcode
 *
 */
class RecetasManageRecetasUpdateNuevasCategorias extends ProcessPluginBase
{
  /**
   * {@inheritdoc}
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property)
  {

    // We want to get several fields from the migration so we retrieve all.
    $row_data = $row->getSource();

    $field_old_id = $value;

    // Searching for the related node (post).
    $nodes = \Drupal::entityManager()
      ->getStorage('node')
      ->loadByProperties([
        'type' => 'receta',
        'field_old_id' => $field_old_id,
      ]);

    if (empty($nodes)) {
      $message = 'No se encuentra la receta relacionada (field_old_id=' . $field_old_id . ').' . "\n";
      echo $message;
      throw new MigrateSkipRowException($message);
      return;
    }

    $node = reset($nodes);

    $necesidades = $this->getNecesidades();
    $field_necesidades_especificas = $this->populateFieldWithTaxonomy('necesidades_especificas', $necesidades, $row_data['field_necesidades_especificas']);

    $tipos_de_plato = $this->getTiposPlato();
    $field_tipo_de_plato = $this->populateFieldWithTaxonomy('tipo_de_plato', $tipos_de_plato, $row_data['field_tipo_de_plato']);

    $tipos_de_dieta = $this->getTiposDieta();
    $field_tipo_de_dieta = $this->populateFieldWithTaxonomy('tipo_de_dieta', $tipos_de_dieta, $row_data['field_tipo_de_dieta']);

    $estaciones = $this->getEstaciones();
    $field_estacion = $this->populateFieldWithTaxonomy('estacion', $estaciones, $row_data['field_estacion']);

    $node->field_necesidades_especificas = $field_necesidades_especificas;
    $node->field_tipo_de_plato = $field_tipo_de_plato;
    $node->field_tipo_de_dieta = $field_tipo_de_dieta;
    $node->field_estacion = $field_estacion;

    $node->save();

    return null;
  }

  protected function populateFieldWithTaxonomy($vocabulary, $data, $value) {
    $result = [];
    foreach ($data as $indice => $valor){
      $potencia=pow(2,$indice);
      if(($value & $potencia)==$potencia) {
        $arr_terms = taxonomy_term_load_multiple_by_name($valor, $vocabulary);
        if (!empty($arr_terms)) {
          $arr_terms = array_values($arr_terms);
          $result[] = $arr_terms[0];
        }
      }
    }
    return  $result;
  }

  protected function getNecesidades()
  {
    return array(
      1 => 'Diabetes',
      2 => 'Baja en colesterol',
      3 => 'Hipertensión',
      4 => 'Sin gluten',
      5 => 'Sin lactosa'
    );
  }

  protected function getEstaciones()
  {
    return array(
      1 => 'Primavera',
      2 => 'Verano',
      3 => 'Otoño',
      4 => 'Invierno'
    );
  }

  protected function getTiposDieta()
  {
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

  protected function getTiposPlato()
  {
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



