<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\node\NodeInterface;
use Drupal\Core\Render\MetadataBubblingUrlGenerator;
use Drupal\Core\Utility\LinkGenerator;
use Drupal\Core\Url;

/**
 * Defines Header block.
 *
 * @Block(
 *   id = "cabecera_block",
 *   admin_label = @Translation("Cabecera con título")
 * )
 */
class CabeceraBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * The current route match service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatch;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuTree;

  /**
   * The active menu trail service.
   *
   * @var \Drupal\Core\Menu\MenuActiveTrailInterface
   */
  protected $menuActiveTrail;

  /**
   * The url service.
   *
   * @var \Drupal\Core\Render\MetadataBubblingUrlGenerator
   */
  protected $urlGenerator;

  /**
   * The tested link generator.
   *
   * @var \Drupal\Core\Utility\LinkGenerator
   */
  protected $linkGenerator;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager,
                              RouteMatchInterface $route_match,
                              MenuLinkTreeInterface $menu_tree,
                              MenuActiveTrailInterface $menu_active_trail,
                              MetadataBubblingUrlGenerator $url_generator,
                              LinkGenerator $link_generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->routeMatch = $route_match;
    $this->menuTree = $menu_tree;
    $this->menuActiveTrail = $menu_active_trail;
    $this->urlGenerator = $url_generator;
    $this->linkGenerator = $link_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('menu.link_tree'),
      $container->get('menu.active_trail'),
      $container->get('url_generator'),
      $container->get('link_generator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'image1' => '',
      'image2' => '',
      'ocultar' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['image1'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen de fondo de la cabecera'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image1']) ? $this->configuration['image1'] : '',
      '#description' => t('Imagen de fondo de la cabecera'),
      '#required' => FALSE,
    ];
    $form['image2'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => t('Imagen de fondo mobile'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => t('Imagen que se mostrará en dispositivos móviles'),
      '#required' => FALSE,
    ];
    $list_ocultar = [
      'movil' => $this->t('Ocultar en móvil'),
      'tablet' => $this->t('Ocultar en Tablet'),
      'escritorio' => $this->t('Ocultar en Escritorio'),
    ];
    $form['ocultar'] = [
      '#type' => 'checkboxes',
      '#title' => t('Ocultar en dispositivos'),
      '#default_value' => $this->configuration['ocultar'],
      '#options' => $list_ocultar,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Load file object from storage.
    $file_storage = $this->entityManager->getStorage('file');

    // Save image 1 as permanent.
    $image1 = $form_state->getValue('image1');
    if ($image1 != $this->configuration['image1']) {
      if (!empty($image1[0])) {
        $file = $file_storage->load($image1[0]);
        $file->setPermanent();
        $file->save();
      }
    }
    // Save image 2 as permanent.
    $image2 = $form_state->getValue('image2');
    if ($image2 != $this->configuration['image2']) {
      if (!empty($image2[0])) {
        $file2 = $file_storage->load($image2[0]);
        $file2->setPermanent();
        $file2->save();
      }
    }
    // Save configurations.
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Load file object from storage.
    $file_storage = $this->entityManager->getStorage('file');

    // Get current node id.
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface) {
      $nid = $node->id();
    }

    // Get translation language.
    $request = \Drupal::request();
    $current_path = $request->getPathInfo();
    $path_args = explode('/', $current_path);
    $first_argument = $path_args[1];
    if ($first_argument == 'va') {
      $translation = 'ca';
    }
    elseif ($first_argument == 'en') {
      $translation = 'en';
    }
    else {
      $translation = 'es';
    }

    // Build the typical default set of menu tree parameters.
    $parameters = new MenuTreeParameters();
    $parameters->setActiveTrail($this->menuActiveTrail->getActiveTrailIds('main'));

    // Build the menu tree.
    $tree = $this->menuTree->load('main', $parameters);

    // Load the storage entity type menu_link_content for main menu.
    $menu = $this->entityManager->getStorage('menu_link_content')
      ->loadByProperties(['menu_name' => 'main']);

    foreach ($menu as $item) {
      $link_format = trim($item->get('link')->getValue()[0]['uri'], 'entity:node/');

      if ($nid === $link_format) {
        $parent_id = $item->getParentId();
        $children_id = $item->getPluginId();
        $act_category = $item->getTitle();
        $first_category = $this->t('Home');
        $url_first = Url::fromRoute('<front>');
        // Menu Breadcrumbs.
        foreach ($tree as $subtree) {

          // For the 3th level.
          if ($subtree->link->getPluginDefinition()['parent'] === '') {
            $fathers = $subtree->link->getPluginDefinition()['id'];

            if ($tree[$fathers]->subtree[$parent_id] !== NULL) {
              $father_definition = $tree[$fathers]->link->getPluginDefinition();
              if (!empty($father_definition)) {
                $father = $father_definition['title'];
                $node_father = $father_definition['route_parameters']['node'];
                if ($node_father instanceof NodeInterface) {
                  $url_father = Url::fromRoute('entity.node.canonical', ['node' => $node_father]);
                }
              }

              $node_children = \Drupal::entityTypeManager()->getStorage('node')
                ->load($tree[$fathers]->subtree[$parent_id]->link->getPluginDefinition()['route_parameters']['node']);
              if ($node_children instanceof NodeInterface) {
                if ($node_children->hasTranslation($translation)) {
                  $children = $node_children->getTranslation($translation)->getTitle();
                }
                else {
                  $children = $node_children->getTitle();
                }
                //$children = $tree[$fathers]->subtree[$parent_id]->link->getPluginDefinition()['title'];
                $url_children = Url::fromRoute('entity.node.canonical', ['node' => $node_children->id()]);
              }
            }

            if ($tree[$fathers]->subtree[$parent_id]->subtree[$children_id] !== NULL) {
              $grandson_node =  \Drupal::entityTypeManager()->getStorage('node')
                ->load($tree[$fathers]->subtree[$parent_id]->subtree[$children_id]->link->getPluginDefinition()['route_parameters']['node']);
              if ($grandson_node instanceof NodeInterface) {
                if ($grandson_node->hasTranslation($translation)) {
                  $grandson = $grandson_node->getTranslation($translation)->getTitle();
                }
                else {
                  $grandson = $grandson_node->getTitle();
                }
              }

              $list_links = [
                0 => [
                  '#type' => 'link',
                  '#title' => '' . $first_category . '',
                  '#attributes' => [
                    'class' => 'link-class',
                    'target' => '_self',
                  ],
                  '#url' => $url_first,
                ],
                1 => ['#markup' => ' > '],
                2 => [
                  '#type' => 'link',
                  '#title' => '' . $father . '',
                  '#attributes' => [
                    'class' => 'link-class',
                    'target' => '_self',
                  ],
                  '#url' => $url_father,
                ],
                3 => ['#markup' => ' > '],
                4 => [
                  '#type' => 'link',
                  '#title' => '' . $children . '',
                  '#attributes' => [
                    'class' => 'link-class',
                    'target' => '_self',
                  ],
                  '#url' => $url_children,
                ],
                5 => ['#markup' => ' > '],
                6 => [
                  '#markup' => '<span class="title-breadcrumb">' . $grandson . '</span>',
                ],
              ];
            }
          }

          // For the 2nd level.
          if ($subtree->link->pluginId === $parent_id) {
            $node_prev = \Drupal::entityTypeManager()->getStorage('node')
              ->load($tree[$parent_id]->link->getPluginDefinition()['route_parameters']['node']);
            if ($node_prev instanceof NodeInterface) {
              if ($node_prev->hasTranslation($translation)) {
                $prev_category = $node_prev->getTranslation($translation)->getTitle();
              }
              else {
                $prev_category = $node_prev->getTitle();
              }
              $url_prev = Url::fromRoute('entity.node.canonical', ['node' => $node_prev->id()]);
            }

            $list_links = [
              0 => [
                '#type' => 'link',
                '#title' => '' . $first_category . '',
                '#attributes' => [
                  'class' => 'link-class',
                  'target' => '_self',
                ],
                '#url' => $url_first,
              ],
              1 => ['#markup' => ' > '],
              2 => [
                '#type' => 'link',
                '#title' => '' . $prev_category . '',
                '#attributes' => [
                  'class' => 'link-class',
                  'target' => '_self',
                ],
                '#url' => $url_prev,
              ],
              3 => ['#markup' => ' > '],
              4 => ['#markup' => $act_category],
            ];
          }
        }
      }
    }

    $file_url = $file_url2 = "";
    $image1 = $this->configuration['image1'];
    if (!empty($image1[0])) {
      if ($file = $file_storage->load($image1[0])) {
        $file_url = $file->url();
      }
    }
    $image2 = $this->configuration['image2'];
    if (!empty($image2[0])) {
      if ($file2 = $file_storage->load($image2[0])) {
        $file_url2 = $file2->url();
      }
    }

    // Load current node object from storage.
    $node_storage = $this->entityManager->getStorage('node');
    $node = $node_storage->load($nid);

    if ($node instanceof NodeInterface) {
      $image1 = $this->configuration['image1'];
      $image2 = $this->configuration['image2'];
      $ocultar = $this->configuration['ocultar'];

      return [
        '#theme' => 'content_cabecera',
        '#img1' => $file_url,
        '#img2' => $file_url2,
        '#ocultar' => $ocultar,
        '#tituloDinamico' => $node->getTitle(),
        '#listLinks' => $list_links,
        '#cache' => ['max-age' => 0,],
      ];
    }
  }

}
