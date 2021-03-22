<?php

namespace Drupal\consum_tol_integration\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'tol_item_field_type' field type.
 *
 * @FieldType(
 *   id = "tol_item_field_type",
 *   label = @Translation("Tol Caroussel"),
 *   description = @Translation("Tol Item field to integrate Tol elements"),
 *   default_widget = "tol_item_widget_type",
 *   default_formatter = "tol_item_formatter_type"
 * )
 */
class TolItemFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['id_list'] = DataDefinition::create('string')
      ->setLabel(t('Name'));
    $properties['id_order'] = DataDefinition::create('integer')
      ->setLabel(t('id order'));

    $properties['query_text'] = DataDefinition::create('string')
      ->setLabel(t('EAN'));
    $properties['query_order'] = DataDefinition::create('integer')
      ->setLabel(t('Query order'));

    $properties['category'] = DataDefinition::create('string')
      ->setLabel(t('Id'));
    $properties['category_order'] = DataDefinition::create('integer')
    ->setLabel(t('Category order'));

    $properties['limit'] = DataDefinition::create('integer')
    ->setLabel(t('Caroussel Limit'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'id_list' => [
          'type' => 'varchar',
          'length' => '255',
          'not null' => TRUE,
        ],
        'id_order' => [
          'type' => 'int',
        ],
        'query_text' => [
          'type' => 'varchar',
          'length' => '255',
        ],
        'query_order' => [
          'type' => 'int',
        ],
        'category' => [
          'type' => 'varchar',
          'length' => '255',
        ],
        'category_order' => [
          'type' => 'int',
        ],
        'limit' => [
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
    $value = $this->get('id_list')->getValue();
    return $value === NULL || $value === '';
  }

}
