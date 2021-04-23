<?php

namespace Drupal\consum_fields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ingrediente_widget_type' widget.
 *
 * @FieldWidget(
 *   id = "ingrediente_widget_type",
 *   module = "consum_fields",
 *   label = @Translation("Ingrediente widget type"),
 *   field_types = {
 *     "ingrediente_field_type"
 *   }
 * )
 */
class IngredienteWidgetType extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $fieldclass = isset($items[$delta]) ? $items[$delta] : NULL;
    $element['nombre'] = [
      '#title' => t('Nombre del Ingrediente'),
      '#type' => 'textfield',
      '#default_value' => $fieldclass->get('nombre')->getValue(),
    ];

    $element['cantidad'] = [
      '#title' => t('Cantidad'),
      '#type' => 'textfield',
      '#default_value' => $fieldclass->get('cantidad')->getValue(),
    ];
    $element['unidad'] = [
      '#title' => t('Unidad de medida'),
      '#type' => 'textfield',
      '#default_value' => $fieldclass->get('unidad')->getValue(),
    ];
    return $element;
  }

}
