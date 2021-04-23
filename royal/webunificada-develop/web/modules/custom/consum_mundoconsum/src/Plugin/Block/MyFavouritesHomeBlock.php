<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\MyFavouritesBlock.
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
 *   id = "myfavourites_block",
 *   admin_label = @Translation("My Favourites Home Block")
 * )
 */
class MyFavouritesHomeBlock extends MundoConsumBlockBase {

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

    $favourites_text = 'My favourite items 01/11/2020 -> 30/11/2020';

    $elements['title']['block-title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . $this->t('My Favourites') . '  <img src="/themes/custom/consum_es/assets/img/mi_ahorro/mis_favoritos.png" alt="Mi ahorro"></h2>
        <p>' . $this->t('Accumulate discounts by buying what you like the most') . '</p>
        <p>' . $this->t($favourites_text) . '</p>',
    ];

    // Articles container.
    $elements['articles']['#attributes'] = [
      'class' => 'myfavourites-articles-wrapper row',
    ];

    $aux = parent::getAux();

    for ($i=0; $i < 5; $i++) {

      $elements['articles'][$i] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'myfavourites-article col',
        ],
        '#tree' => TRUE,
      ];


      $class_selection = '';
      if ($aux['selection'][$i] == 'Te gusta') { $class_selection = 'selection-1';}
      else if ($aux['selection'][$i] == 'Tu selección') { $class_selection = 'selection-2';}
      else { $class_selection = 'selection-3';}

      $elements['articles'][$i]['discount'] = [
        '#type' => 'markup',
        '#markup' => '<div class="discount">Acumula<br><span class="discount-euros">' . $aux['value'][$i] . '</span></div>',
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

      $elements['articles'][$i]['image-wrapper']['selection'] = [
        '#type' => 'markup',
        '#markup' => '<p class="'.$class_selection.'">' . $aux['selection'][$i] . '</p>',
      ];

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
        '#markup' => '<p class="discount-text">' . $this->t('The Item has been successfully added to your shopping cart.') . '</p>',
      ];
    }


    // User links container.
    $elements['links'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'myfavourites-links',
      ],
      '#tree' => TRUE,
    ];

    $elements['links']['view-all'] = [
      '#type' => 'link',
      '#attributes' => [
        'class' => 'boton1',
      ],
      '#url' => Url::fromUri('internal:/mi-ahorro-favoritos'),
      '#title' => $this->t('View All'),
    ];

    return $elements;
  }

}
