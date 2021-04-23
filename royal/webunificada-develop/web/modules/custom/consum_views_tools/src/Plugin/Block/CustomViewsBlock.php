<?php

namespace Drupal\consum_views_tools\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;

/**
 * Custom view block class.
 *
 * @Block(
 *   id = "custom_views_block",
 *   admin_label = @Translation("Vistas personalizadas")
 * )
 */
class CustomViewsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'view_block' => '',
      'ocultar' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Preparamos la lsta de vistas a mostrar.
    $view_options1['blank'] = '----';
    $view_options2 = Drupal::config('consum_views_tools.settings')->get('view_block');
    $view_options = array_merge($view_options1, $view_options2);

    $form['view_block'] = [
      '#type' => 'select',
      '#title' => t('Selecciona la vista'),
      '#default_value' => $this->configuration['view_block'],
      '#options' => $view_options,
    ];

    foreach ($view_options as $view_option => $view_label) {

      $selected_view = explode('/', $view_option);
      $view_machine_name = $selected_view[0];
      $view_display_name = $selected_view[1];

      if (!empty($view = Views::getView($view_machine_name))) {

        // A falta de parametrizar lo ocultamos por defecto.
        $show_items_per_page = FALSE;
        if ($show_items_per_page === TRUE) {
          $items_per_page = $this->configuration[$view_option]['items_per_page'];
          if ($items_per_page == NULL) {
            $items_per_page = $view->getItemsPerPage();
          }
          if ($items_per_page !== NULL) {
            $form[$view_option]['items_per_page'] = [
              '#type' => 'number',
              '#title' => t('Items to display'),
              '#min' => 1,
              '#max' => 20,
              '#default_value' => $items_per_page,
              '#states' => [
                'visible' => [
                  ':input[name="settings[view_block]"]' => [
                    'value' => $view_option,
                  ],
                ],
              ],
            ];
          }
        }

        // A falta de parametrizar lo activamos por defecto.
        $show_offset = TRUE;
        if ($show_offset === TRUE) {
          $offset = $this->configuration[$view_option]['offset'];
          if ($offset == NULL) {
            $offset = $view->getOffset() ? $view->getOffset() : 0;
          }
          $form[$view_option]['offset'] = [
            '#type' => 'number',
            '#title' => t('Offset (number of items to skip)'),
            '#min' => 0,
            '#max' => 20,
            '#default_value' => $offset,
            '#states' => [
              'visible' => [
                ':input[name="settings[view_block]"]' => [
                  'value' => $view_option,
                ],
              ],
            ],
          ];
        }

        // Filtros.
        $view->setDisplay($view_display_name);
        $filter_handlers = $view->getHandlers('filter');
        foreach ($filter_handlers as $filter_handler) {
          if ($filter_handler["plugin_id"] == 'taxonomy_index_tid') {

            $vid = $filter_handler["vid"];
            $list_terms['blank'] = '----';
            $terms = Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
            foreach ($terms as $term) {
              $id_term = $term->tid;
              $name_term = $term->name;
              $list_terms[$id_term] = $name_term;
            }
            $default_value = $this->configuration[$view_option][$filter_handler["id"]];
            if ($default_value === NULL) {
              $default_value = $filter_handler["value"];
              $this->configuration[$view_option][$filter_handler["id"]] = $default_value;
            }
            $form[$view_option][$filter_handler["id"]] = [
              '#type' => 'select',
              '#title' => $vid,
              '#default_value' => $default_value,
              '#options' => $list_terms,
              '#multiple' => TRUE,
              '#attributes' => [
                'style' => 'height:200px',
              ],
              '#states' => [
                'visible' => [
                  ':input[name="settings[view_block]"]' => [
                    'value' => $view_option,
                  ],
                ],
              ],
            ];
          }
        }
      }
    }

    $list_ocultar = [
      'movil' => $this->t('Ocultar en móvil'),
      'tablet' => $this->t('Ocultar en Tablet'),
      'escritorio' => $this->t('Ocultar en Escritorio'),
    ];
    $form['ocultar'] = [
      '#type' => 'checkboxes',
      '#title' => t('Ocultar en dispositivos'),
      '#default_value' => $this->configuration['ocultar'],
      '#options' => $list_ocultar,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      $this->configuration[$key] = $value;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $view_block = $this->configuration['view_block'];
    $view_block_array = explode('/', $view_block);

    $view = Views::getView($view_block_array[0]);
    $view->setDisplay($view_block_array[1]);

    $filter_handlers = $view->getHandlers('filter');
    foreach ($this->configuration[$view_block] as $key => $value) {
      if (!in_array($key, ['offset', 'items_per_page'])) {
        $new_filter = $filter_handlers[$key];
        $new_filter['value'] = !empty($value) ? $value : [3 => 3];
        $view->setHandler($view_block_array[1], 'filter', $key, $new_filter);
      }
    }
    if (!empty($this->configuration[$view_block]['offset'])) {
      $view->setOffset($this->configuration[$view_block]['offset']);
    }
    if (!empty($this->configuration[$view_block]['items_per_page'])) {
      $view->setItemsPerPage($this->configuration[$view_block]['items_per_page']);
    }
    $view->execute();
    $rendered_view = $view->render();

    $ocultar = $this->configuration['ocultar'];

    return [
      '#theme' => 'content_multiviews',
      '#vista' => $rendered_view,
      '#ocultar' => $ocultar,
    ];

  }

}
