<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\ChequeCreceListBlock.
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
 *   id = "chequecrece_list_block",
 *   admin_label = @Translation("ChequeCrece List Block")
 * )
 */
class ChequeCreceListBlock extends MundoConsumBlockBase {

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

    $aux = parent::getAux();

    $round = 0;
    while ($round < count($aux['value'])) {
      $elements['articles'][$round] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'chequecrece-articles-wrapper row',
        ],
        '#tree' => TRUE,
      ];
      for ($i = 0 + (5 * $round); $i < 5 * ($round + 1); $i++) {
        if ($i < count($aux['value'])){
          $elements['articles'][$round][$i] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'chequecrece-article col mt-5',
            ],
            '#tree' => TRUE,
          ];

          $elements['articles'][$round][$i]['discount'] = [
            '#type' => 'markup',
            '#markup' => '<div class="discount">Cheque-crece<br><span class="discount-euros">' . $aux['value'][$i] . '</span></div>',
          ];

          $elements['articles'][$round][$i]['image-wrapper'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'tol-product-image',
            ],
            '#tree' => TRUE,
          ];

          $product = \Drupal::service('products.connection')->getProductById($aux['article'][$i]);

          if (!empty($product->productData)) {
            $elements['articles'][$round][$i]['image-wrapper']['image'] = [
              '#type' => 'markup',
              '#markup' => '<img src="'
                . $product->productData->imageURL
                . '"/>',
            ];
          }

          $elements['articles'][$round][$i]['content-wrapper'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'tol-product-content',
            ],
            '#tree' => TRUE,
          ];
          $elements['articles'][$round][$i]['content-wrapper']['title'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'tol-product-title',
            ],
            '#tree' => TRUE,
          ];

          if (!empty($product->productData)) {
            $elements['articles'][$round][$i]['content-wrapper']['title']['link'] = [
              '#type' => 'link',
              '#url' => Url::fromUri($product->productData->url),
              '#attributes' => [
                'target' => '_blank',
              ],
              '#title' => $product->productData->brand->name ? $product->productData->name . ' ' . $product->productData->brand->name : $product->productData->name,
            ];
            $elements['articles'][$round][$i]['content-wrapper']['description'] = [
              '#type' => 'markup',
              '#markup' => '<p class="tol-article-description">' . $product->productData->description . '</p>',
            ];
            $elements['articles'][$round][$i]['content-wrapper']['price'] = [
              '#type' => 'markup',
              '#markup' => '<p class="tol-price">' . $product->priceData->prices[0]->value->centAmount . '€</p>',
            ];
          }
          $elements['articles'][$round][$i]['content-wrapper']['tol-link'] = [
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
      }
      $round ++;
    }


    /* User links container.
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
      '#url' => Url::fromUri('http://consum.docker.localhost/'),
      '#title' => $this->t('View More (next ajax)'),
    ];
*/
    return $elements;
  }

}
