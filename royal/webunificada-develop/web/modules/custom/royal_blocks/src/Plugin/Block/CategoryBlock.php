<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\comment\CommentStatistics;
use Drupal\node\NodeInterface;

/**
 * Provides a 'CategoryBlock' block.
 *
 * @Block(
 *  id = "category_block",
 *  admin_label = @Translation("Category block"),
 * )
 */
class CategoryBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
   * The format date service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The comments statistics service.
   *
   * @var Drupal\comment\CommentStatistics
   */
  protected $commentStatistics;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager,
                              RouteMatchInterface $route_match,
                              DateFormatterInterface $date_formatter,
                              CommentStatistics $comment_statistics) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->routeMatch = $route_match;
    $this->dateFormatter = $date_formatter;
    $this->commentStatistics = $comment_statistics;
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
      $container->get('date.formatter'),
      $container->get('comment.statistics')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    
    // Get id current node.
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface) {
      $nid = $node->id();
    }

    // Load current node object from storage.
    $node_storage = $this->entityManager->getStorage('node');
    $node = $node_storage->load($nid);

    // Load current user object from storage.
    $user_storage = $this->entityManager->getStorage('user');
    $user = $user_storage->load(\Drupal::currentUser()->id());

    // Getting and format create time of node.
    $create_time = $node->getCreatedTime();
    if ($this->dateFormatter->format($create_time, 'category')) {
      $dateFormated = $this->dateFormatter->format($create_time, 'category');
    }
    else {
      $dateFormated = $this->dateFormatter->format($create_time, 'html_date');
    }

    // Get entity type.
    $entity_type = $node->bundle();
    $termId = '';
    $nameCategory = '';

    if ($entity_type == 'magazine') {
      // Get de id category field value.
      $category = $node->get('field_category')->getValue();
      $termId = $category[0]['target_id'];

      // Get term category object.
      $taxonomy_storage = $this->entityManager->getStorage('taxonomy_term');
      $term = $taxonomy_storage->load($termId);
      $nameCategory = $term->getName();
      if (!$nameCategory) {
        $nameCategory = "";
      }
    }

    // Get comments statistics object for current node.
    $nodeStats = $this->commentStatistics->read([$nid => $node], 'node');
    $totalComments = $nodeStats[0]->comment_count;


    // Block manager.
    $block_manager = \Drupal::service('plugin.manager.block');
    // Load Block settings / Settings.
    $addtoany_config = \Drupal::config('addtoany.settings')->getRawData();
    /** 
     * @var \Drupal\addtoany\Plugin\Block\AddToAnyBlock $plugin_block 
     */
    $addtoany_block = $block_manager->createInstance('addtoany_block', $addtoany_config);
    // Get Block build.
    $addtoany = $addtoany_block->build();

    $listLikes = $node->get('field_like')->getValue();

    $favorites = $user->get('field_favorites')->getValue();

    $flagMatch = false;
    foreach($favorites as $favorite){
      if($favorite['target_id'] == $nid){
        $flagMatch = true;
      } 
    }

    return [
      '#theme' => 'content_category',
      '#entityType' => $entity_type,
      '#nameCategory' => $nameCategory,
      '#dateFormated' => $dateFormated,
      '#termId' => $termId,
      '#totalComments' => $totalComments,
      '#cache' => ['max-age' => 0],
      '#addtoany' => $addtoany,
      '#label' => $node->label(),
      '#path' => $_SERVER['REQUEST_URI'],
      '#nid' => $nid,
      '#listLikes' => $listLikes,
      '#flagMatch' => $flagMatch,
      '#node' => $node,
    ];

  }

}
