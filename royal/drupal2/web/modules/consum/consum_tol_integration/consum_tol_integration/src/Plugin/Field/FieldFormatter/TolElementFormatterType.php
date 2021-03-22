<?php

namespace Drupal\consum_tol_integration\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use stdClass;

/**
 * Plugin implementation of the 'tol_element_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "tol_element_formatter_type",
 *   label = @Translation("Tol Element formatter type"),
 *   field_types = {
 *     "tol_element_field_type"
 *   }
 * )
 */
class TolElementFormatterType extends FormatterBase {

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
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof Node) {
      if ($node->hasField('field_product_code')) {
        $tol_values = $node->get('field_product_code')[0];
        if (!empty($tol_values->id_tol)) {
          $values = new stdClass;
          $values = \Drupal::service('products.connection')->getProductById($tol_values->id_tol);

          // Print elements.
          if (!empty($values)) {
            // Set elements on $elements.
            $new_elements = $this->printTolElement($values);
            for ($i = 0; $i < count($new_elements); $i++) {
              $elements[count($elements)] = $new_elements[$i];
            }
          }
        }
      }
    }
    return $elements;
  }

  /**
   * Print caroussel elements function.
   *
   * @param stdClass $values
   * @return void
   */
  protected function printTolElement(stdClass $values) {

    $elements = [];

    if ($values->productData->url != '') {
      $elements[0] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product',
        ],
        '#tree' => TRUE,
      ];

      $elements[0]['image-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-image',
        ],
        '#tree' => TRUE,
      ];

      if (!empty($values->productData)) {
        $elements[0]['image-wrapper']['image'] = [
          '#type' => 'markup',
          '#markup' => '<img src="'
            . $values->productData->imageURL
            . '"/>',
        ];
      }

      $elements[0]['content-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-content',
        ],
        '#tree' => TRUE,
      ];
      $elements[0]['content-wrapper']['title'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-title',
        ],
        '#tree' => TRUE,
      ];

      if (!empty($values->productData)) {
        $elements[0]['content-wrapper']['title']['link'] = [
          '#type' => 'link',
          '#url' => Url::fromUri($values->productData->url),
          '#title' => $values->productData->name,
        ];
        $elements[0]['content-wrapper']['description'] = [
          '#type' => 'markup',
          '#markup' => '<p>' . $values->productData->description . '</p>',
        ];
        $elements[0]['content-wrapper']['price'] = [
          '#type' => 'markup',
          '#markup' => '<p>' . $values->priceData->prices[0]->value->centAmount . '€</p>',
        ];
      }
      $elements[0]['content-wrapper']['tol-link'] = [
        '#type' => 'markup',
        '#markup' => '<a href="#" class="boton1 shopping-cart-button" data-toggle="modal" data-target="#article-list-0"><img src="/themes/custom/consum_es/assets/img/carrito.svg" class="img-prop"/></a>',
      ];

      // Popup definition.
      $elements[0]['popup'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mycoupons-popup modal fade',
          'id' => 'article-list-0',
        ],
        '#tree' => TRUE,
      ];

      $elements[0]['popup']['modal-dialog'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-dialog',
        ],
        '#tree' => TRUE,
      ];
      $elements[0]['popup']['modal-dialog']['modal-content'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-content',
        ],
        '#tree' => TRUE,
      ];
      $elements[0]['popup']['modal-dialog']['modal-content']['modal-body']  = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-body row',
        ],
        '#tree' => TRUE,
      ];

      $elements[0]['popup']['modal-dialog']['modal-content']['modal-body']['text'] = [
        '#type' => 'markup',
        '#markup' => '<p class="discount-text">El Artículo se ha añadido correctamente a su carrito de la compra.</p>',
      ];
    }
    else {
      \Drupal::messenger()->addError('Ha habido un error con uno de los valores añadidos');
    }

    return $elements;
  }

}
