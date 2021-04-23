<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;

/**
 * Provides a 'RecipesHeaderBlock' block.
 *
 * @Block(
 *  id = "recipes_header_block",
 *  admin_label = @Translation("Cabecera Recetas"),
 * )
 */
class RecipesHeaderBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
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
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager,
                              RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->routeMatch = $route_match;
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Get id current node.
    $node = $this->routeMatch->getParameter('node');

    if (!$node instanceof NodeInterface) {
      return [];
    }

    $nid = $node->id();
    $imageId = $node->get('field_image')->getValue()[0]['target_id'];
    $title = $node->label();
    $selection = $node->get('field_receta_destacada')->getValue()[0]['value'];

    // Load current file object from storage.
    $file_storage = $this->entityManager->getStorage('file');
    $image = $file_storage->load($imageId);
    $image_url = file_create_url($image->getFileUri());

    return [
      '#image' => $image_url,
      '#title' => $title,
      '#selection' => $selection,
      '#theme' => 'content_recipes_header',
    ];
  }

}
