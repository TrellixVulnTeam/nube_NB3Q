<?php

namespace Drupal\consum_tol_integration\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'tol_item_widget_type' widget.
 *
 * @FieldWidget(
 *   id = "tol_item_widget_type",
 *   module = "consum_tol_integration",
 *   label = @Translation("Tol Item widget type"),
 *   field_types = {
 *     "tol_item_field_type"
 *   }
 * )
 */
class TolItemWidgetType extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // FieldClass definition to get default_value automatically.
    $fieldclass = isset($items[$delta]) ? $items[$delta] : '';

    // Id list form fieldset.
    $element['id_list'] = [
      '#title' => $this->t('Tol Item Id'),
      '#type' => 'textfield',
      '#default_value' => $fieldclass->get('id_list')->getValue(),
      '#autocomplete_route_name' => 'consum_tol_integration.autocomplete.articles',
    ];

    $element['id_order'] = [
      '#title' => $this->t('Id Order'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 3,
      '#default_value' => $fieldclass->get('id_order')->getValue(),
      /*'#element_validate' => [
        [$this, 'orderValidate'],
      ],*/
    ];

    // Query elements form fieldset.
    $element['query_text'] = [
      '#title' => $this->t('Tol elements query'),
      '#type' => 'textfield',
      '#default_value' => $fieldclass->get('query_text')->getValue(),
    ];

    $element['query_order'] = [
      '#title' => $this->t('Query Order'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 3,
      '#default_value' => $fieldclass->get('query_order')->getValue(),
      /*'#element_validate' => [
        [$this, 'orderValidate'],
      ],*/
    ];

    // Category elements form fieldset.
    $category_options = [];
    $numberOfElements = \Drupal::service('products.connection')->getCategories('1')->totalCount;
    $response = \Drupal::service('products.connection')->getCategories($numberOfElements);
    foreach ($response->categories as $category) {
      $category_options[$category->id] = $category->nombre;
    }

    $element['category'] = [
      '#title' => $this->t('Category'),
      '#type' => 'select',
      '#default_value' => $fieldclass->get('category')->getValue(),
      '#options' => $category_options,
    ];

    $element['category_order'] = [
      '#title' => $this->t('Category Order'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 3,
      '#default_value' => $fieldclass->get('category_order')->getValue(),
      /*'#element_validate' => [
        [$this, 'orderValidate'],
      ],*/
    ];

    // Limit for query.
    $element['limit'] = [
      '#title' => $this->t('Caroussel limit'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 100,
      //'#required' => true,
      '#default_value' => $fieldclass->get('limit')->getValue(),
    ];

    return $element;
  }

  /*public function orderValidate ($element, FormStateInterface $form_state) {
    if (strpos( $element['#name'], 'id_order')) {
      if ($form_state->getUserInput()['field_tol_item'][0]['id_order'] == $form_state->getUserInput()['field_tol_item'][0]['query_order']) {
        $form_state->setError($element, t('Elements order should be unique.'));
      }
      elseif ($form_state->getUserInput()['field_tol_item'][0]['id_order'] == $form_state->getUserInput()['field_tol_item'][0]['category_order']) {
        $form_state->setError($element, t('Elements order should be unique.'));
      }
    }
    else {
      if ($form_state->getUserInput()['field_tol_item'][0]['query_order'] == $form_state->getUserInput()['field_tol_item'][0]['category_order']) {
        $form_state->setError($element, t('Elements order should be unique.'));
      }
    }
  }*/

}
