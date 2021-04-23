<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\ChequeCreceBlock.
 */

namespace Drupal\consum_mundoconsum\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use stdClass;

/**
 *
 * @Block(
 *   id = "chequecrece_block",
 *   admin_label = @Translation("ChequeCrece Home Block")
 * )
 */
class ChequeCreceHomeBlock  extends MundoConsumBlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
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
      'class' => 'chequecrece-title',
    ];

    $elements['title']['block-title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>Cheque Crece <img src="/themes/custom/consum_es/assets/img/mi_ahorro/ico_cheque_crece.png" alt="Cheque crece"></h2>
        <p>' . $this->t('Accumulate discounts by buying products with an additional discount') . '</p>',
    ];

    // Articles container.
    $elements['articles']['#attributes'] = [
      'class' => 'chequecrece-articles-wrapper row',
    ];

    $aux = parent::getAux();

    for ($i=0; $i < 5; $i++) {

      $elements['articles'][$i] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'chequecrece-article col',
        ],
        '#tree' => TRUE,
      ];

      $elements['articles'][$i]['discount'] = [
        '#type' => 'markup',
        '#markup' => '<div class="discount">Cheque-crece<br><span class="discount-euros">' . $aux['value'][$i] . '</span></div>',
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
        $elements['articles'][$i]['content-wrapper']['price'] = [
          '#type' => 'markup',
          '#markup' => '<p class="tol-price">' . $product->priceData->prices[0]->value->centAmount . '€</p>',
        ];
      }
      $elements['articles'][$i]['content-wrapper']['tol-link'] = [
        '#type' => 'markup',
        '#markup' => '<a href="#" class="boton1 shopping-cart-button" data-toggle="modal" data-target="#article-list-0"><img src="/themes/custom/consum_es/assets/img/carrito.svg" class="img-prop"/></a>',
      ];

      // Popup definition.
      $elements['popup'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'mycoupons-popup modal fade',
          'id' => 'article-list-0',
        ],
        '#tree' => TRUE,
      ];

      $elements['popup']['modal-dialog'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-dialog',
        ],
        '#tree' => TRUE,
      ];
      $elements['popup']['modal-dialog']['modal-content'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-content',
        ],
        '#tree' => TRUE,
      ];
      $elements['popup']['modal-dialog']['modal-content']['modal-body']  = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'modal-body row',
        ],
        '#tree' => TRUE,
      ];

      $elements['popup']['modal-dialog']['modal-content']['modal-body']['text'] = [
        '#type' => 'markup',
        '#markup' => '<p class="discount-text">' . $this->t('This item has been added to your shipping cart.') . '</p>',
      ];
    }


    // User links container.
    $elements['links'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'chequecrece-links',
      ],
      '#tree' => TRUE,
    ];

    $elements['links']['view-all'] = [
      '#type' => 'link',
      '#attributes' => [
        'class' => 'boton1',
      ],
      '#url' => Url::fromUri('internal:/cheque-crece'),
      '#title' => $this->t('View All'),
    ];

    return $elements;
  }

}
