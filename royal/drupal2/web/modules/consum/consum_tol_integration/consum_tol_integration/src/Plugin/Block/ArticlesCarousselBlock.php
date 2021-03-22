<?php

/**
 * @file
 * Contains \Drupal\consum_tol_integration\Plugin\Block\ArticlesCarousselBlock.
 */

namespace Drupal\consum_tol_integration\Plugin\Block;

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
 *   id = "articles_caroussel",
 *   admin_label = @Translation("Articles Caroussel")
 * )
 */
class ArticlesCarousselBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
  public function defaultConfiguration() {
    return [
      'id_list' => '',
      'id_order' => '',
      'query_text' => '',
      'query_order' => '',
      'category' => '',
      'category_order' => '',
      'limit' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Id list form fieldset.
    $form['id_list'] = [
      '#title' => $this->t('Tol Item Id'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['id_list'] ? $this->configuration['id_list'] : '',
      '#autocomplete_route_name' => 'consum_tol_integration.autocomplete.articles',
    ];

    $form['id_order'] = [
      '#title' => $this->t('Id Order'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 3,
      '#default_value' => $this->configuration['id_order'] ? $this->configuration['id_order'] : '',
    ];

    // Query elements form fieldset.
    $form['query_text'] = [
      '#title' => $this->t('Tol elements query'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['query_text'] ? $this->configuration['query_text'] : '',
    ];

    $form['query_order'] = [
      '#title' => $this->t('Query Order'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 3,
      '#default_value' => $this->configuration['query_order'] ? $this->configuration['query_order'] : '',
    ];

    // Category elements form fieldset.
    $category_options = [];
    $numberOfElements = \Drupal::service('products.connection')->getCategories('1')->totalCount;
    $response = \Drupal::service('products.connection')->getCategories($numberOfElements);
    foreach ($response->categories as $category) {
      $category_options[$category->id] = $category->nombre;
    }

    $form['category'] = [
      '#title' => $this->t('Category'),
      '#type' => 'select',
      '#default_value' => $this->configuration['category'] ? $this->configuration['category'] : '',
      '#options' => $category_options,
    ];

    $form['category_order'] = [
      '#title' => $this->t('Category Order'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 3,
      '#default_value' => $this->configuration['category_order'] ? $this->configuration['category_order'] : '',
    ];

    // Limit for query.
    $form['limit'] = [
      '#title' => $this->t('Caroussel limit'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 100,
      '#required' => true,
      '#default_value' => $this->configuration['limit'] ? $this->configuration['limit'] : '',
    ];

    return $form;
  }

  /*
   * {@inheritdoc}
   *
  public function blockValidate($form, FormStateInterface $form_state) {
    if ($form_state->getValue('target') == "_blank"){
      $link = $form_state->getValue('link');
      if (substr_count($link, 'http') < 1) {
        $form_state->setErrorByName('link',
        $this->t('You must use preffix "http://" or "https://" in domain'));
      }
    }
  }*/

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save configurations.
    $this->configuration['id_list'] = $form_state->getValue('id_list');
    $this->configuration['id_order'] = $form_state->getValue('id_order');
    $this->configuration['query_text'] = $form_state->getValue('query_text');
    $this->configuration['query_order'] = $form_state->getValue('query_order');
    $this->configuration['category'] = $form_state->getValue('category');
    $this->configuration['category_order'] = $form_state->getValue('category_order');
    $this->configuration['limit'] = $form_state->getValue('limit');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $values = new stdClass;
    $elements = [];
    $count = 0;
    $round = 1;
    //$node = \Drupal::routeMatch()->getParameter('node');
    //$tol_values = $node->get('field_tol_item')[0];
    $tol_values = $this->configuration;

    while (($count < $tol_values['limit']) && ($round < 4)) {
      // Get elements by ID if limit is not full.
      if (!empty($tol_values['id_list']) && ($round == $tol_values['id_order'])) {
        $id_elements = explode(', ', $tol_values['id_list']);
        $values = new stdClass;
        foreach ($id_elements as $id_element) {
          $values->products[] = \Drupal::service('products.connection')->getProductByTolId($id_element);
          $count ++;
        }
      }

      // Get elements by query if limit is not full.
      if (!empty($tol_values['query_text']) && ($round == $tol_values['query_order'])) {
        $values = \Drupal::service('products.connection')->getProductsByQuery($tol_values['query_text'], $tol_values['limit'] - $count);
        $count += $values->totalCount;
      }

      // Get elements by category if limit is not full.
      if (!empty($tol_values['category']) && ($round == $tol_values['category_order'])) {
        $values = \Drupal::service('products.connection')->getProductsByCategories($tol_values['category'], $tol_values['limit'] - $count);
        $count += $values->totalCount;
      }

      // Set elements on $elements.
      $new_elements = $this->printCarousselElements($values, $count);
      for ($i = 0; $i < count($new_elements); $i++) {
        $elements[count($elements)] = $new_elements[$i];
      }

      $round++;
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
      }

      // Single product array.
      else {
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
    }
    return $elements;
  }

}
