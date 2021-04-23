<?php

namespace Drupal\consum_views_tools\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views_layout\Plugin\views\style\ViewsLayoutStyle;

/**
 * Style plugin for the timeline view.
 *
 * @ViewsStyle(
 *   id = "views_layout_style_custom_node_display",
 *   title = @Translation("Views Layout Grid Custom Node Display"),
 *   help = @Translation("Displays content in a grid defined by a layout with custom display per node."),
 *   theme = "views_layout_style",
 *   display_types = {"normal"}
 * )
 */
class ViewsLayoutStyleCustomNodeDisplay extends ViewsLayoutStyle {

  /**
   * Maximum number of view modes to use.
   */
  const MAX_VIEW_MODES = 10;

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['num_view_modes'] = 1;

    for ($i = 1; $i < self::MAX_VIEW_MODES; $i++) {
      $options['view_mode_' . $i] = ['default' => 'default'];
    }

    return $options;
  }

  /**
   * Builds the configuration form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    parent::buildOptionsForm($form, $form_state);

    $view_modes = Drupal::service('entity_display.repository')->getViewModeOptions('node');
    $num_view_modes = $this->options['num_view_modes'];

    $form['num_view_modes'] = [
      '#type' => 'number',
      '#title' => t('View modes'),
      '#min' => 1,
      '#max' => self::MAX_VIEW_MODES,
      '#default_value' => $num_view_modes,
      '#description' => t('The number of view modes to use.'),
    ];
    for ($i = 1; $i < self::MAX_VIEW_MODES; $i++) {
      $condition = [];
      for ($j = $i; $j < self::MAX_VIEW_MODES; $j++) {
        $condition[] = ['value' => $j];
      }
      $form['view_mode_' . $i] = [
        '#type' => 'select',
        '#options' => $view_modes,
        '#title' => $this->t('View mode') . ' ' . $i,
        '#default_value' => $this->options['view_mode_' . $i],
        '#states' => [
          'visible' => [
            ':input[name="style_options[num_view_modes]"]' => $condition,
          ],
        ],
      ];
    }

  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    if (!empty($max = $this->options['num_view_modes'])) {
      $n = 1;
      foreach ($build["#rows"] as $key_row => $row) {
        foreach ($row as $key => $value) {
          if (is_array($value) && !empty($value[0]["#view_mode"])) {
            $view_mode = $this->options['view_mode_' . $n];
            $build["#rows"][$key_row][$key][0]["#view_mode"] = $view_mode;
            $n = $n < $max ? $n + 1 : 1;
          }
        }
      }
    }
    return $build;
  }

}
