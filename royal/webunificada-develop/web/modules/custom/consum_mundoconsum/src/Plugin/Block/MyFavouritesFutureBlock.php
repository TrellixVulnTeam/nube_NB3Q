<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\MyFavouritesFutureBlock.
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
 *   id = "myfavourites_future_block",
 *   admin_label = @Translation("My Favourites Future Block")
 * )
 */
class MyFavouritesFutureBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  // Injection services

 public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = [];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save configurations.
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $elements = [];

    $colapser_text = 'My favourites of December';

    $elements['colapser'] = [
      '#type' => 'markup',
      '#markup' =>'<p><a class="btn btn-primary button-myfavourites" data-toggle="collapse" href="#myfavourites" role="button" aria-expanded="false" aria-controls="myfavourites">'
        . $this->t($colapser_text) .
        '</a></p>',
    ];

    // Data container.
    $elements['articles'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'myfavourites-collapse collapse',
        'id' => 'myfavourites',
      ],
      '#tree' => TRUE,
    ];

    // Articles container.
    $aux = [
      'value' => [
        '0' => '3€',
        '1' => '0,45€',
        '2' => '20%',
        '3' => '1,25€',
        '4' => '18%',
        '5' => '3€',
        '6' => '0,45€',
        '7' => '20%',
        '8' => '1,25€',
        '9' => '18%',
        '10' => '3€',
        '11' => '20%',
      ],
      'article' => [
        '0' => '5182',
        '1' => '16075',
        '2' => '5074',
        '3' => '13590',
        '4' => '4968',
        '5' => '5182',
        '6' => '16075',
        '7' => '5074',
        '8' => '13590',
        '9' => '4968',
        '10' => '5182',
        '11' => '16075',
      ],
      'selection' => [
        '0' => 'Te gusta',
        '1' => 'Te puede gustar',
        '2' => 'Tu selección',
        '3' => 'Te puede Gustar',
        '4' => 'Tu selección',
        '5' => 'Te gusta',
        '6' => 'Te puede gustar',
        '7' => 'Tu selección',
        '8' => 'Te puede Gustar',
        '9' => 'Tu selección',
        '10' => 'Te puede Gustar',
        '11' => 'Tu selección',
      ],
    ];

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
        if ($i < 9){

          $class_selection = '';
          if ($aux['selection'][$i] == 'Te gusta') { $class_selection = 'selection-1';}
          else if ($aux['selection'][$i] == 'Tu selección') { $class_selection = 'selection-2';}
          else { $class_selection = 'selection-3';}

          $elements['articles'][$round][$i] = [
            '#type' => 'container',
            '#attributes' => [
              'class' => 'myfavourites-article col',
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

          $elements['articles'][$round][$i]['image-wrapper']['selection'] = [
            '#type' => 'markup',
            '#markup' => '<p class="'.$class_selection.'">' . $aux['selection'][$i] . '</p>',
          ];

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
            '#markup' => '<p class="discount-text">' . $this->t('The Item has been successfully added to your shopping cart.') . '</p>',
          ];
        }
      }
      $round ++;
    }

    return $elements;
  }

}
