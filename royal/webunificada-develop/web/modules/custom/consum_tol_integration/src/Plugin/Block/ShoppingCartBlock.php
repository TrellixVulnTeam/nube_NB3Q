<?php

namespace Drupal\consum_tol_integration\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use stdClass;

/**
 * Implements TOL block.
 *
 * @Block(
 *   id = "shopping_cart_block",
 *   admin_label = @Translation("Shopping Cart")
 * )
 */
class ShoppingCartBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Builds element classes.
    $elements = [
      '#theme' => 'content_tol',
      '#attributes' => [
        'class' => [
          'col-1',
          'col-sm-auto',
          'd-flex',
          'align-items-center',
          'order-6',
          'order-sm-7',
        ],
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    // Generates the popup link.
    $elements['#link'] = [
      '#type' => 'markup',
      '#markup' => '<a href="#" class="btn-ci ci-carrito shopping-cart-header" data-toggle="modal" data-target="#shopping-cart"></a>',
    ];

    // Gets cart values from services.
    $cart = $this->getConnections();

    // Popup definition.
    $elements['#popup'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'shopping-cart-popup modal fade',
        'id' => 'shopping-cart',
      ],
      '#tree' => TRUE,
    ];

    $elements['#popup']['modal-dialog'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'modal-dialog',
      ],
      '#tree' => TRUE,
    ];
    $elements['#popup']['modal-dialog']['modal-content'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'modal-content',
      ],
      '#tree' => TRUE,
    ];
    $elements['#popup']['modal-dialog']['modal-content']['modal-body']  = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'modal-body',
      ],
      '#tree' => TRUE,
    ];

    if (!empty($cart->productsData->products['0'])) {
      // Summary containter definition.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-summary',
        ],
        '#tree' => TRUE,
      ];

      // Total products element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-products-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-products-wrapper']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-title col',
        ],
        '#value' => $this->t('Total products'),
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-products-wrapper']['price'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-price col',
        ],
        '#value' => $this->getNumberMoneyFormat($cart->productsData->summary->totals->totalProducts) . '€',
      ];

      // Total discount element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-discount-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-discount-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-discount-wrapper']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-discount-title col',
        ],
        '#value' => $this->t('Applied coupons'),
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-discount-wrapper']['price'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-discount-price col',
        ],
        '#value' => $this->getNumberMoneyFormat($cart->productsData->summary->discounts->totalDiscounts->couponDiscounts->totals->instantDiscount) . '€',
      ];

      // Total coupons element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-coupons-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-discount-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-coupons-wrapper']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-discount-title col',
        ],
        '#value' => $this->t('Applied discount'),
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['total-coupons-wrapper']['price'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-discount-price col',
        ],
        '#value' => $this->getNumberMoneyFormat($cart->productsData->summary->discounts->totalDiscounts->offerDiscounts->totals->instantDiscount) . '€',
      ];


      // Subtotal element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['subtotal-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['subtotal-wrapper']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-title col',
        ],
        '#value' => $this->t('Subtotal'),
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['subtotal-wrapper']['price'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-price col',
        ],
        '#value' => $this->getNumberMoneyFormat($cart->productsData->summary->totals->totalsWithTotalPromotions->totalWithPromotions) . '€',
      ];

      // Selling prices element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['selling-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-discount-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['selling-wrapper']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-discount-title col',
        ],
        '#value' => $this->t('Selling and process prices'),
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['selling-wrapper']['price'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-discount-price col',
        ],
        '#value' => $this->getNumberMoneyFormat($cart->otherItems->summary->total->total) . '€',
      ];

      // Summary price element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['summary-price-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['summary-price-wrapper']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-title col',
        ],
        '#value' => $this->t('Total price'),
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['summary-price-wrapper']['price'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'class' => 'cart-price col',
        ],
        '#value' => $this->getNumberMoneyFormat($cart->summary->totals->totalOrderAmountToPay) . '€',
      ];

      // Validate shopping cart link element.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['cart-link-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-row row',
        ],
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['summary']['cart-link-wrapper']['link'] = [
        '#type' => 'link',
        '#url' => Url::fromUri('https://tienda.consum.es/consum/#ValidarCarrito'),
        '#attributes' => [
          'target' => '_blank',
          'class' => 'boton1',
        ],
        '#title' => $this->t('Validate Cart'),
      ];

      // Products container definition.
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-products',
        ],
        '#tree' => TRUE,
      ];

      // Foreach product, get the elements.
      if (!empty($products = $cart->productsData->products)) {
        foreach ($products as $delta => $product) {
          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'cart-products-wrapper',
            ],
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'cart-products-row row',
            ],
          ];

          // Product image wrapper.
          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['image-wrapper'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'cart-product-image col',
            ],
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['image-wrapper']['image'] = [
            '#type' => 'markup',
            '#markup' => '<img src="' . $product->product->productData->imageURL . '"/>',
          ];

          // Description product wrapper.
          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['description-wrapper'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'cart-product-description col',
            ],
            '#tree' => TRUE,
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['description-wrapper']['title'] = [
            '#type' => 'markup',
            '#markup' => '<p>' . $product->product->productData->description . '</p>',
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['description-wrapper']['brand'] = [
            '#type' => 'markup',
            '#markup' => '<p>' . $product->product->productData->brand->name . '</p>',
          ];

          if (!empty($product->product->priceData->prices['1'])) {
            $i = 1;
          }
          else {
            $i = 0;
          }

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['description-wrapper']['price'] = [
            '#type' => 'markup',
            '#markup' => '<p>' .  $this->getNumberMoneyFormat($product->product->priceData->prices[$i]->value->centUnitAmount) . '€ / ' . $product->product->priceData->unitPriceUnitType . '</p>',
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['description-wrapper']['price-base'] = [
            '#type' => 'markup',
            '#markup' => '<p>' .  $this->getNumberMoneyFormat($product->prices->finalPriceWithPromotions) . '€</p>',
          ];

          // Description product wrapper.
          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['actions-wrapper'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'cart-product-actions col',
            ],
            '#tree' => TRUE,
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['actions-wrapper']['rubbish'] = [
            '#type' => 'markup',
            '#markup' => '<p>basura<p/>',
          ];

          $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['single-product']['actions-wrapper']['quantity-button'] = [
            '#type' => 'markup',
            '#markup' => '<p>botón + -<p/>',
          ];

          // If this product has a coupon applied.
          if (!empty($offer_id = $product->product->offers['0']->id)) {
            $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['offers'] = [
              '#type' => 'container',
              '#attributes' => [
                'class' => 'cart-product-offers row',
              ],
              '#tree' => TRUE,
            ];

            $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['offers']['img'] = [
              '#type' => 'markup',
              '#markup' => '<div class="col"><img src="' . $product->product->offers['0']->image . '"/></div>',
            ];

            // If discount is coupon type.
            if ($product->product->offers['0']->promotionType == '2') {
              $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['offers']['description'] = [
                '#type' => 'markup',
                '#markup' => '<div class="col">
                  <p>' . $product->product->offers['0']->longDescription . '</p>
                  <p>+' .  $this->getNumberMoneyFormat($product->product->offers['0']->amount) . '€</p>
                  </div>',
              ];
            }
            // If discount is offer type.
            elseif ($product->product->offers['0']->promotionType == '1') {
              $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['offers']['description'] = [
                '#type' => 'markup',
                '#markup' => '<div class="col"><p>' . $product->product->offers['0']->longDescription . '</p></div>',
              ];
              $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['offers']['final-price'] = [
                '#type' => 'markup',
                '#markup' => '<div class="col"><p>' .  $this->getNumberMoneyFormat($product->prices->finalPrice) . '€</p></div>',
              ];
              $elements['#popup']['modal-dialog']['modal-content']['modal-body']['products'][$delta]['offers']['final-discount'] = [
                '#type' => 'markup',
                '#markup' => '<div class="col"><p>-' .  $this->getNumberMoneyFormat($product->discounts->immediateDiscount) . '€</p></div>',
              ];
            }
          }
        }
      }
    }
    else {
      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['empty'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'cart-empty',
        ],
        '#tree' => TRUE,
      ];

      $elements['#popup']['modal-dialog']['modal-content']['modal-body']['empty']['text'] = [
        '#type' => 'markup',
        '#markup' => $this->t('Your cart is empty.'),
      ];
    }

    return $elements;
  }

  /**
   * Gets the user connection and the shopping cart.
   */
  protected function getConnections() {
    // Creates a stdClass object and gets shoppingcart service.
    $shoppingCart = new stdClass;
    $services = \Drupal::service('shoppingcart.connection');

    // Get user token from TOL.
    $userLogged = $services->getUserToken();
    if (!empty($userLogged)) {
      $token = $userLogged->access_token;
      if (!empty($token)) {
        // Gets shopping cart from user (by token).
        $shoppingCart = $services->getShoppingCart($token);
      }
    }
    return $shoppingCart;
  }


/**
 * Returns number as money format.
 */
  protected function getNumberMoneyFormat($number) {
    $moneyNumber = str_replace('.', ',', $number);
    $len = strlen($moneyNumber);
    $commapos = strpos($moneyNumber, ',');
    if (!$commapos) {
      $moneyNumber = $moneyNumber . ',00';
    }
    elseif (($len - $commapos) == 2) {
      $moneyNumber = $moneyNumber . '0';
    }

    return $moneyNumber;
  }

}
