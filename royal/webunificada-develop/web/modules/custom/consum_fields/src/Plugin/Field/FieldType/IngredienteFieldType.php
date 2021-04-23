<?php

namespace Drupal\consum_fields\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'ingrediente_field_type' field type.
 *
 * @FieldType(
 *   id = "ingrediente_field_type",
 *   label = @Translation("Ingrediente"),
 *   description = @Translation("Campo personalizado para los ingredientes, con tres atributos: nombre, cantidad y unidad de medida"),
 *   default_widget = "ingrediente_widget_type",
 *   default_formatter = "ingrediente_formatter_type"
 * )
 */
class IngredienteFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['nombre'] = DataDefinition::create('string')
      ->setLabel(t('Nombre'));

    $properties['cantidad'] = DataDefinition::create('string')
      ->setLabel(t('Cantidad'));

    $properties['unidad'] = DataDefinition::create('string')
      ->setLabel(t('Unidad'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'nombre' => [
          'type' => 'varchar',
          'length' => '255',
          'not null' => TRUE,
        ],
        'cantidad' => [
          'type' => 'varchar',
          'length' => '255',
        ],
        'unidad' => [
          'type' => 'varchar',
          'length' => '255',
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('nombre')->getValue();
    return $value === NULL || $value === '';
  }

}
