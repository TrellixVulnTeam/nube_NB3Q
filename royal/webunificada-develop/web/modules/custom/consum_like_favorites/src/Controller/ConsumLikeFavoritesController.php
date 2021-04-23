<?php

/**
  * @file
  * Contains \Drupal\consum_like_favorites\Controller\ConsumLikeFavoritesController.
  */

namespace Drupal\consum_like_favorites\Controller;

use Drupal\consum_comunidad_events\EventsTrait;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Class ConsumLikeFavoritesController
 */
class ConsumLikeFavoritesController extends ControllerBase {

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
   * Likes function
   */
  public function likes($nid,$uid) {

    // Load current node object from storage.
    $node_storage = $this->entityManager->getStorage('node');
    $node = $node_storage->load($nid);

    // Load array field_like values and count it
    $likes = $node->get('field_like')->getValue();
    $index = count($likes);

    // Loop for match $uid
    $match = 0;
    foreach($likes as $like){
      if($like['target_id'] == $uid){
        $match = 1;
      }
    }

    // Set or remove user like ($uid) item
    if($match == 1) {
      for ($i = 0; $i < $index; $i++) {
        if ($node->get('field_like')->getValue()[$i]['target_id'] == $uid) {
          $node->get('field_like')->removeItem($i);
          $node->save();
        }
      }

      // Load final array field_like values and count it
      $likesFinal = $node->get('field_like')->getValue();
      $totalLikes = count($likesFinal);

      // Set $totalLikes value on ajax response
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#'.$nid,'<span id='.$nid.'>'.$totalLikes.'</span>'));
      $response->addCommand(new ReplaceCommand('#heart-'.$nid.'','<span id="heart-'.$nid.'" class="btn-ci ci-corazon"></span>'));
      $response->getContent();
      return $response;


    } elseif($match == 0) {
      $node->get('field_like')->appendItem($uid, $notify = TRUE);
      $node->save();

      // Load final array field_like values and count it
      $likesFinal = $node->get('field_like')->getValue();
      $totalLikes = count($likesFinal);

      // Set $totalLikes value on ajax response
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#'.$nid,'<span id='.$nid.'>'.$totalLikes.'</span>'));
      $response->addCommand(new ReplaceCommand('#heart-'.$nid.'','<span id="heart-'.$nid.'" class="btn-ci ci-corazon-activo"></span>'));
      $response->getContent();

      // Generates a new like event.
      EventsTrait::setLikeEvent($node, 'Content like');

      return $response;
    }
  }

  /**
   * Favorites function
   */
  public function favorites($nid,$uid) {

    // Load current user object from storage.
    $user_storage = $this->entityManager->getStorage('user');
    $user = $user_storage->load($uid);

    // Load array field_favorites values
    $favorites = $user->get('field_favorites')->getValue();
    $index = count($favorites);

    // Loop for match $nid
    $match = 0;
    foreach($favorites as $favorite){
      if($favorite['target_id'] == $nid){
        $match = 1;
      }
    }

    // Set or remove user like ($uid) item
    if($match == 1) {
      for ($i = 0; $i < $index; $i++) {
        if ($user->get('field_favorites')->getValue()[$i]['target_id'] == $nid) {
          $user->get('field_favorites')->removeItem($i);
          $user->save();
        }
      }

      // Set color class icon ajax response
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#flag-'.$nid.'','<span id="flag-'.$nid.'" class="btn-ci ci-marcador"></span>'));
      $response->getContent();
      return $response;

    } elseif($match == 0) {
      $user->get('field_favorites')->appendItem($nid, $notify = TRUE);
      $user->save();

      // Set color class icon ajax response
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#flag-'.$nid.'','<span id="flag-'.$nid.'" class="btn-ci ci-marcador-activo"></span>'));
      $response->getContent();
      return $response;
    }

  }

  /**
    * Comment Likes function
    */
  public function likes_comment($cid,$uid) {

    // Load current comment object from storage.
    $comment_storage = $this->entityManager->getStorage('comment');
    $comment = $comment_storage->load($cid);

    // Load array field_like values and count it
    $likes = $comment->get('field_like')->getValue();
    $index = count($likes);

    // Loop for match $uid
    $match = 0;
    foreach($likes as $like){
      if($like['target_id'] == $uid){
        $match = 1;
      }
    }

    // Set or remove user like ($uid) item
    if($match == 1) {
      for ($i = 0; $i < $index; $i++) {
        if ($comment->get('field_like')->getValue()[$i]['target_id'] == $uid) {
          $comment->get('field_like')->removeItem($i);
          $comment->save();
        }
      }

      // Load final array field_like values and count it
      $likesFinal = $comment->get('field_like')->getValue();
      $totalLikes = count($likesFinal);

      // Set $totalLikes value on ajax response
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#'.$cid,'<span id='.$cid.'>'.$totalLikes.'</span>'));
      $response->addCommand(new ReplaceCommand('#heart-'.$cid.'','<span id="heart-'.$cid.'" class="btn-ci ci-corazon"></span>'));
      $response->getContent();
      return $response;

    } elseif($match == 0) {
      $comment->get('field_like')->appendItem($uid, $notify = TRUE);
      $comment->save();

      // Load final array field_like values and count it
      $likesFinal = $comment->get('field_like')->getValue();
      $totalLikes = count($likesFinal);

      // Set $totalLikes value on ajax response
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#'.$cid,'<span id='.$cid.'>'.$totalLikes.'</span>'));
      $response->addCommand(new ReplaceCommand('#heart-'.$cid.'','<span id="heart-'.$cid.'" class="btn-ci ci-corazon-activo"></span>'));
      $response->getContent();

      // Generates a new like event.
      EventsTrait::setLikeEvent($comment, 'Comment like');

      return $response;
    }

  }

}