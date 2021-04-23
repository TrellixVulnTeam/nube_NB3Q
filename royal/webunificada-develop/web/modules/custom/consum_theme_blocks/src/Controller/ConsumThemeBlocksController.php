<?php

/**
  * @file
  * Contains \Drupal\consum_theme_blocks\Controller\ConsumThemeBlocksController.
  */

namespace Drupal\consum_theme_blocks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Class ConsumThemeBlocksController
 */
class ConsumThemeBlocksController extends ControllerBase {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
    * Subscribe product tastea function
    */
  public function subscribe_product_tastea($nid,$uid) {

    $node = Node::load($nid);

    if ($node instanceof NodeInterface) {
      if ($node->hasField('field_usuarios_apuntados')) {
        $users_signed = $node->get('field_usuarios_apuntados')->getValue();
      }
    }
    $current_signed = false;
    foreach($users_signed as $user_signed){
      if($user_signed['target_id'] == $uid){
        $current_signed = true;
      }
    }

    if (!$current_signed){
      $node->field_usuarios_apuntados[] = $uid;
      $node->save();
    }

    // Ajax response
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#subscribe-tastea','<span class="btn-suerte">¡Suerte!</span>'));
    $response->addCommand(new ReplaceCommand('#container-state','<div id="container-state" class="container-state d-flex align-items-center"><span>Estás apuntado</span></div>'));
    $response->addCommand(new ReplaceCommand('#message-status-tastea','<p id="message-status-tastea">Ya te has apuntado, en breve sabrás si has sido seleccionado</p>'));
    $response->getContent();
    return $response;
  }
}