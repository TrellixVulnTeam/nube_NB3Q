<?php

namespace Drupal\consum_tol_integration\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use stdClass;

/**
 * Plugin implementation of the 'tol_item_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "tol_item_formatter_type",
 *   label = @Translation("Tol Item formatter type"),
 *   field_types = {
 *     "tol_item_field_type"
 *   }
 * )
 */
class TolItemFormatterType extends FormatterBase {

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
    $count = 0;
    $round = 1;
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof Node) {
      if ($node->hasField('field_tol_item')) {
        $tol_values = $node->get('field_tol_item')[0];
        if (!empty($tol_values->limit)) {
          while (($count <= $tol_values->limit) && ($round < 4)) {
            // Get elements by ID if limit is not full.
            if (!empty($tol_values->id_list) && ($round == $tol_values->id_order)) {
              $id_elements = explode(', ', $tol_values->id_list);
              $values = new stdClass;
              foreach ($id_elements as $id_element) {
                $values->products[] = \Drupal::service('products.connection')->getProductByTolId($id_element);
                $count ++;
              }

              while (($count < $tol_values->limit) && ($round < 4)) {
                // Get elements by ID if limit is not full.
                if (!empty($tol_values->id_list) && ($round == $tol_values->id_order)) {
                  $id_elements = explode(', ', $tol_values->id_list);
                  $values = new stdClass;
                  foreach ($id_elements as $id_element) {
                    if ($count < $tol_values->limit) {
                      $values->products[] = \Drupal::service('products.connection')->getProductByTolId($id_element);
                      $count ++;
                    }
                  }
                }

                // Get elements by query if limit is not full.
                if (!empty($tol_values->query_text) && ($round == $tol_values->query_order)) {
                  $values = \Drupal::service('products.connection')->getProductsByQuery($tol_values->query_text, $tol_values->limit - $count);
                  $count += $values->totalCount;
                }

                // Get elements by category if limit is not full.
                if (!empty($tol_values->category) && ($round == $tol_values->category_order)) {
                  $values = \Drupal::service('products.connection')->getProductsByCategories($tol_values->category, $tol_values->limit - $count);
                  $count += $values->totalCount;
                }

                if (!empty($values)) {
                  // Set elements on $elements.
                  $new_elements = $this->printCarousselElements($values, $count);
                  for ($i = 0; $i < count($new_elements); $i++) {
                    $elements[count($elements)] = $new_elements[$i];
                  }
                }

                $round++;
              }
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
  protected function printCarousselElements(stdClass $values, $count) {

    $elements = [];

    if ((!empty($values)) || ($values->totalCount == 0)) {
      // Products list array.
      if (!empty($products = $values->products)) {
        foreach ($products as $delta => $product) {
          if ($product->productData->url != '') {
            $elements[$delta] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'tol-product',
              ],
              '#tree' => TRUE,
            ];

            $elements[$delta]['image-wrapper'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'tol-product-image',
              ],
              '#tree' => TRUE,
            ];

            if (!empty($product->productData)) {
              $elements[$delta]['image-wrapper']['image'] = [
                '#type' => 'markup',
                '#markup' => '<img src="'
                  . $product->productData->imageURL
                  . '"/>',
              ];
            }

            $elements[$delta]['content-wrapper'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'tol-product-content',
              ],
              '#tree' => TRUE,
            ];
            $elements[$delta]['content-wrapper']['title'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'tol-product-title',
              ],
              '#tree' => TRUE,
            ];

            if (!empty($product->productData)) {
              $elements[$delta]['content-wrapper']['title']['link'] = [
                '#type' => 'link',
                '#url' => Url::fromUri($product->productData->url),
                '#attributes' => [
                  'target' => '_blank',
                ],
                '#title' => $product->productData->brand->name ? $product->productData->name . ' ' . $product->productData->brand->name : $product->productData->name,
              ];
              $elements[$delta]['content-wrapper']['description'] = [
                '#type' => 'markup',
                '#markup' => '<p class="tol-article-description">' . $product->productData->description . '</p>',
              ];
              $elements[$delta]['content-wrapper']['price'] = [
                '#type' => 'markup',
                '#markup' => '<p class="tol-price">' . $product->priceData->prices[0]->value->centAmount . '€</p>',
              ];
            }
            $elements[$delta]['content-wrapper']['tol-link'] = [
              '#type' => 'markup',
              '#markup' => '<a href="#" class="boton1 shopping-cart-button" data-toggle="modal" data-target="#article-list-' . $delta . '"><img src="/themes/custom/consum_es/assets/img/carrito.svg" class="img-prop"/></a>',
            ];

            // Popup definition.
            $elements[$delta]['popup'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'mycoupons-popup modal fade',
                'id' => 'article-list-' . $delta,
              ],
              '#tree' => TRUE,
            ];

            $elements[$delta]['popup']['modal-dialog'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'modal-dialog',
              ],
              '#tree' => TRUE,
            ];
            $elements[$delta]['popup']['modal-dialog']['modal-content'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'modal-content',
              ],
              '#tree' => TRUE,
            ];
            $elements[$delta]['popup']['modal-dialog']['modal-content']['modal-body']  = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'modal-body row',
              ],
              '#tree' => TRUE,
            ];

            $elements[$delta]['popup']['modal-dialog']['modal-content']['modal-body']['text'] = [
              '#type' => 'markup',
              '#markup' => '<p class="discount-text">El Artículo se ha añadido correctamente a su carrito de la compra.</p>',
            ];
          }
          else {
            \Drupal::messenger()->addError('Ha habido un error con uno de los valores añadidos');
          }
        }
      }

      // Single product array.
      else {
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
      }
    }
    return $elements;
  }

}
