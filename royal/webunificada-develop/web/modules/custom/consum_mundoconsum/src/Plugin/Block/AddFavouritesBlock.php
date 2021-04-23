<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\AddFavouritesBlock.
 */

namespace Drupal\consum_mundoconsum\Plugin\Block;

use Drupal\consum_mundoconsum\MundoconsumConnection;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\consum_mundoconsum\Plugin\Block\MundoConsumBlockBase;
use stdClass;

/**
 *
 * @Block(
 *   id = "add_favourites_block",
 *   admin_label = @Translation("Add Favourites Block")
 * )
 */
class AddFavouritesBlock extends MundoConsumBlockBase {

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
      'class' => 'myfavourites-title',
    ];

    $favourites_month = 'You can already choose your favourites for January';

    $elements['title']['block-title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . $this->t('Choose My Favourites ') . '</h2>
        <p><img src="/themes/custom/consum_es/assets/img/mi_ahorro/mis_favoritos.png" alt="Mi ahorro"></p>
        <p>' . $this->t($favourites_month) . '</p>',
    ];

    // Data container.
    $elements['articles']['#attributes'] = [
      'class' => 'myfavourites',
    ];

    $aux = parent::getAux();

    $round = 0;
    while ($round < 2) {

      $elements['articles'][$round] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'myfavourites-articles-wrapper row',
        ],
        '#tree' => TRUE,
      ];
      for ($i = 0 + (5 * $round); $i < 5 * ($round + 1); $i++) {

        if($i == 0) {

          $elements['articles'][$round][0] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'myfavourites-article add-favourites-article col text-center',
            ],
            '#tree' => TRUE,
          ];

          $elements['articles'][$round][$i]['logo-access'] = [
            '#type' => 'markup',
            '#markup' => '<a href="#" class="text-center" data-toggle="modal" data-target="#favourite-delete-1">
              <img src="/themes/custom/consum_es/assets/img/add.svg" class="add-favourite-image"/>
              </a>',
          ];

          $elements['articles'][$round][$i]['text-access'] = [
            '#type' => 'markup',
            '#markup' => '<p class="add-favourite-text">' . $this->t('Choose a new favourite item') . '</p>',
          ];

          if (count($aux['value']) >= 9) {
            $elements['articles'][$round][$i]['text-limit-access'] = [
              '#type' => 'markup',
              '#markup' => '<p class="add-favourite-limit">' . $this->t('You have already added 9 favourite items') . '</p>',
            ];
          }

          // Popup definition.
          $elements['popup'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'mycoupons-popup modal fade',
              'id' => 'favourite-delete-1',
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
            '#markup' => '<p class="add-favourite-text">Sección pendiente de desarrollar.</p>',
          ];
        }
        elseif (($i < 10) && ($i < count($aux['value']))){

          $elements['articles'][$round][$i] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'myfavourites-article add-favourites-article col',
            ],
            '#tree' => TRUE,
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
            '#markup' => '<a href="#" class="boton1 shopping-cart-button text-center" data-toggle="modal" data-target="#favourite-delete-1">' . $this->t('Delete') . '</a>',
          ];

          // Popup definition.
          $elements['popup'] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'mycoupons-popup modal fade',
              'id' => 'favourite-delete-1',
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
            '#markup' => '<p class="add-favourite-text">Sección pendiente de desarrollar.</p>',
          ];
        }
      }
      $round ++;
    }

    return $elements;
  }

}
