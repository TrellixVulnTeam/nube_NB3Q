<?php

namespace Drupal\consum_tol_integration\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'tol_element_widget_type' widget.
 *
 * @FieldWidget(
 *   id = "tol_element_widget_type",
 *   module = "consum_tol_integration",
 *   label = @Translation("Tol Element widget type"),
 *   field_types = {
 *     "tol_element_field_type"
 *   }
 * )
 */
class TolElementWidgetType extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // FieldClass definition to get default_value automatically.
    $fieldclass = isset($items[$delta]) ? $items[$delta] : '';

    $element['id_tol'] = [
      '#title' => $this->t('Product Code'),
      '#type' => 'number',
      '#default_value' => $fieldclass->get('id_tol')->getValue(),
    ];

    return $element;
  }

}
