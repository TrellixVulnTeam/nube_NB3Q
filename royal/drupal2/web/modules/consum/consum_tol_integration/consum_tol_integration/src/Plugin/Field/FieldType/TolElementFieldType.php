<?php

namespace Drupal\consum_tol_integration\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'tol_element_field_type' field type.
 *
 * @FieldType(
 *   id = "tol_element_field_type",
 *   label = @Translation("Tol Element"),
 *   description = @Translation("Tol Elemment field to integrate Tol elements"),
 *   default_widget = "tol_element_widget_type",
 *   default_formatter = "tol_element_formatter_type"
 * )
 */
class TolElementFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['id_tol'] = DataDefinition::create('integer')
      ->setLabel(t('Product Code'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'id_tol' => [
          'type' => 'int',
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('id_tol')->getValue();
    return $value === NULL || $value === '';
  }

}
