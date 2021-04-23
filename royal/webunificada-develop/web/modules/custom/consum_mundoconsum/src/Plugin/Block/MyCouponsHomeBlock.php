<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\MyCouponsBlock.
 */

namespace Drupal\consum_mundoconsum\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\user\Entity\User as EntityUser;
use stdClass;
use User;

/**
 *
 * @Block(
 *   id = "mycoupon_mundoconsum_block",
 *   admin_label = @Translation("My Coupons Home Block")
 * )
 */
class MyCouponsHomeBlock extends MundoConsumBlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    unset ($form['block_description']);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save configurations.
    parent::blockSubmit($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $elements = parent::build();

    // Data container.
    $elements['title']['#attributes'] = [
      'class' => 'mycoupons-title',
    ];

    $coupons_value = 12;
    $coupons_count = 'You have ' . $coupons_value . ' disponible coupons';

    // Title container.
    $elements['title']['block-title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . $this->t('My Discount Coupons') . ' <img src="/themes/custom/consum_es/assets/img/mi_ahorro/bola_porcentaje.png" alt="Mi ahorro"></h2>
        <p>' . $coupons_count . '</p>',
    ];

    // Articles container.
    $elements['articles']['#attributes'] = [
      'class' => 'mycoupons-articles-wrapper row',
    ];

    $aux = parent::getAux();

    for ($i=0; $i < 5; $i++) {

      $elements['articles'][$i] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mycoupons-article col',
        ],
        '#tree' => TRUE,
      ];

      $elements['articles'][$i]['discount'] = [
        '#type' => 'markup',
        '#markup' => '<div class="discount"><span class="discount-euros">' . $aux['value'][$i] . '</span><br>de descuento</div>',
      ];

      $elements['articles'][$i]['image-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-image',
        ],
        '#tree' => TRUE,
      ];

      $product = \Drupal::service('products.connection')->getProductById($aux['article'][$i]);

      if (!empty($product->productData)) {
        $elements['articles'][$i]['image-wrapper']['image'] = [
          '#type' => 'markup',
          '#markup' => '<img src="'
            . $product->productData->imageURL
            . '"/>',
        ];
      }
      $elements['articles'][$i]['content-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-content',
        ],
        '#tree' => TRUE,
      ];
      $elements['articles'][$i]['content-wrapper']['title'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-title',
        ],
        '#tree' => TRUE,
      ];

      if (!empty($product->productData)) {
        $elements['articles'][$i]['content-wrapper']['title']['link'] = [
          '#type' => 'link',
          '#url' => Url::fromUri($product->productData->url),
          '#attributes' => [
            'target' => '_blank',
          ],
          '#title' => $product->productData->brand->name ? $product->productData->name . ' ' . $product->productData->brand->name : $product->productData->name,
        ];
        $elements['articles'][$i]['content-wrapper']['description'] = [
          '#type' => 'markup',
          '#markup' => '<p class="tol-article-description">' . $product->productData->description . '</p>',
        ];
      }
      $elements['articles'][$i]['content-wrapper']['tol-link'] = [
        '#type' => 'link',
        '#url' => Url::fromUri('http://consum.docker.localhost/'),
        '#attributes' => [
          'data-toggle' => 'modal',
          'data-target' => '#coupon-list-' . $i,
          'class' => 'boton1',
        ],
        '#title' => $this->t('Use coupon'),
      ];

      // Popup definition.
      $elements['popup'][$i] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mycoupons-popup modal fade',
          'id' => 'coupon-list-' . $i,
        ],
        '#tree' => TRUE,
      ];

      $elements['popup'][$i]['modal-dialog'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-dialog',
        ],
        '#tree' => TRUE,
      ];
      $elements['popup'][$i]['modal-dialog']['modal-content'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-content',
        ],
        '#tree' => TRUE,
      ];
      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']  = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-body row',
        ],
        '#tree' => TRUE,
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mycoupons-article offset-md-1 col-md-3',
        ],
        '#tree' => TRUE,
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['image-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-image',
        ],
        '#tree' => TRUE,
      ];

      $product = \Drupal::service('products.connection')->getProductById($aux['article'][$i]);

      if (!empty($product->productData)) {
        $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['image-wrapper']['image'] = [
          '#type' => 'markup',
          '#markup' => '<img src="'
            . $product->productData->imageURL
            . '"/>',
        ];
      }

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['content-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-content',
        ],
        '#tree' => TRUE,
      ];
      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['content-wrapper']['title'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'tol-product-title',
        ],
        '#tree' => TRUE,
      ];

      if (!empty($product->productData)) {
        $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['content-wrapper']['title']['link'] = [
          '#type' => 'link',
          '#url' => Url::fromUri($product->productData->url),
          '#title' => $product->productData->brand->name ? $product->productData->name . ' ' . $product->productData->brand->name : $product->productData->name,
        ];
        $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['content-wrapper']['description'] = [
          '#type' => 'markup',
          '#markup' => '<p class="tol-article-description">' . $product->productData->description . '</p>',
        ];
      }
      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['article']['content-wrapper']['tol-link'] = [
        '#type' => 'markup',
        '#markup' => '<a href="#" id="article-list-' . $i . '" class="boton1 shopping-cart-button">Canjeado</a>',
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mycoupons-discount offset-md-1 col-md-7',
        ],
        '#tree' => TRUE,
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['discount-value'] = [
        '#type' => 'markup',
        '#markup' => '<p class="discount-value"><span class="value-big">' . $aux['value'][$i] . '</span><br>de descuento</p>',
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['caducity'] = [
        '#type' => 'markup',
        '#markup' => '<p class="discount-caducity">' . $this->t('Expiration date') . ' 25/11/2020</p>',
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['code-description'] = [
        '#type' => 'markup',
        '#markup' => '<p class="discount-code-description">' . $this->t('Show this code at the box') . '</p>',
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['code-wrapper'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mydiscount-discount-code-wrapper',
        ],
        '#tree' => TRUE,
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['code-wrapper']['code'] = [
        '#type' => 'markup',
        '#markup' => '<p>00789</p>',
      ];
      /*
      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['link'] = [
        '#type' => 'link',
        '#url' => Url::fromUri('http://consum.docker.localhost/'),
        '#title' => $this->t('Access to your coupon from APP'),
      ];
      */
      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['discount']['link'] = [
        '#type' => 'markup',
        '#markup' => '<a href="#">' . $this->t('Access to your coupon from APP') . '</a>',
      ];

      $elements['popup'][$i]['modal-dialog']['modal-content']['modal-body']['footer'] = [
        '#type' => 'markup',
        '#markup' => '<p class="discount-footer">' .
            $this->t('The discount of this voucher will be applied in the purchase of the referred product,
            Presenting this discount coupon and identifying yourself at the cash register as a member-client (ID, app or Mundoconsum Card).
            The copies are not valid and will be detected.') . '</p>',
      ];
    }


    // User links container.
    $elements['links'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'mycoupons-links',
      ],
      '#tree' => TRUE,
    ];

    $elements['links']['view-all'] = [
      '#type' => 'link',
      '#attributes' => [
        'class' => 'boton1',
      ],
      '#url' => Url::fromUri('internal:/cupones-ahorro'),
      '#title' => $this->t('View All'),
    ];

    return $elements;
  }

}
