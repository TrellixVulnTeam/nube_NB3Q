<?php

namespace Drupal\consum_custom_blocks\Plugin\Block;

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
 *   id = "consum_cabecera_breadcrumb",
 *   admin_label = @Translation("Breadcrumb TEST")
 * )
 */
class ConsumCabeceraBreadcrumb extends BlockBase implements ContainerFactoryPluginInterface {

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
  public function blockForm($form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function build() {



    // Get current node id.
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface) {
      $nid = $node->id();
    }
    else {
      return [];
    }

    // Get translation language.
    $translation = \Drupal::languageManager()->getCurrentLanguage()->getId();

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
                $url_father = Url::fromRoute('entity.node.canonical', ['node' => $node_father]);
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

              /* Delete link is already exist */
              if ($grandson == $children) {
                $list_links[4]['#title'] = '';
                $list_links[5]['#markup'] = '';

              }

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

    return [
      '#theme' => 'content_cabecera_breadcrumb',
      '#listLinks' => $list_links,
      '#cache' => ['max-age' => 0,],
    ];
  }

}
