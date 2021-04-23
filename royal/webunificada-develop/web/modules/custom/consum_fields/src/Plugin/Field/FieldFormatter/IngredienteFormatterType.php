<?php

namespace Drupal\consum_fields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ingrediente_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "ingrediente_formatter_type",
 *   label = @Translation("Ingrediente formatter type"),
 *   field_types = {
 *     "ingrediente_field_type"
 *   }
 * )
 */
class IngredienteFormatterType extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implements settings summary.
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      if ($item->get('unidad')->getValue()[0]) {
        $elements[$delta] = [
          '#markup' => $this->t('@cantidad @unidad de @nombre', [
            '@nombre' => $item->nombre,
            '@cantidad' => $item->cantidad,
            '@unidad' => $item->unidad,
          ]),
        ];
      }
      else {
        $elements[$delta] = [
          '#markup' => $this->t('@cantidad @nombre', [
            '@nombre' => $item->nombre,
            '@cantidad' => $item->cantidad,
          ]),
        ];
      }
    }

    return $elements;
  }

}
