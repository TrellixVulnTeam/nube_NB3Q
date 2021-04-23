<?php

namespace Drupal\consum_comunidad_events;

use Drupal\comment\Entity\Comment;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Class EventsTrait.
 *
 * @package Drupal\consum_comunidad_events
 */
class EventsTrait {

  /**
   * Generates an event node from content trigger.
   */
  public static function setContentEvent(Node $origin_node, $message) {
    $isset_node = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
      'field_contenido_referenciado' => $origin_node->id()
    ]);

    // If isset an event for this node, skip the new event creation.
    if (empty($isset_node)) {
      $event_node = Node::create([
        'type' => 'evento',
        'title' => 'Crear contenido - ' . $origin_node->label(),
        'uid' => $origin_node->getOwner(),
        'field_mensaje' => $message,
        'moderation_state' => [
          'target_id' => 'published',
        ],
        'field_contenido_referenciado' => [
          'target_id' => $origin_node->id(),
        ],
        'field_tipo_de_evento' => 'crear_contenido',
        'field_usuario_destino' => $origin_node->getOwnerId(),
      ]);

      // If node has been created without errors.
      if ($event_node instanceof NodeInterface) {
        $event_node->save();
      }
      else {
        \Drupal::logger('consum_comunidad_events')->error('An error has occurred while event node was created.');
      }
    }
  }

  /**
   * Generates an event node from comment trigger.
   */
  public static function setCommentEvent(Comment $comment, $message) {
    $node = $comment->getCommentedEntity();

    // If comment has parent, returns this parent's owner ID.
    if ($comment->hasParentComment()) {
      $parent_comment = $comment->getParentComment();
      $event_node = Node::create([
        'type' => 'evento',
        'title' => 'Comentario - ' . $node->label(),
        'uid' => $comment->getOwner(),
        'field_mensaje' => $message,
        'moderation_state' => [
          'target_id' => 'published',
        ],
        'field_contenido_referenciado' => [
          'target_id' => $node->id()
        ],
        'field_comentario_de_referencia' => [
          'target_id' => $comment->id(),
        ],
        'field_tipo_de_evento' => 'dar_comentario',
        'field_usuario_destino' => [
          $parent_comment->getOwnerId(),
          $comment->getOwner(),
          $node->getOwnerId(),
        ],
      ]);

      // If node has been created without errors.
      if ($event_node instanceof NodeInterface) {
        $event_node->save();
      }
      else {
        \Drupal::logger('consum_comunidad_events')->error('An error has occurred while event node was created.');
      }
    }
    // If comment has no parent, field_usuario_destino is empty.
    else {
      $event_node = Node::create([
        'type' => 'evento',
        'title' => 'Comentario - ' . $node->label(),
        'uid' => $comment->getOwner(),
        'field_mensaje' => $message,
        'moderation_state' => [
          'target_id' => 'published',
        ],
        'field_contenido_referenciado' => [
          'target_id' => $node->id()
        ],
        'field_comentario_de_referencia' => [
          'target_id' => $comment->id(),
        ],
        'field_tipo_de_evento' => 'dar_comentario',
        'field_usuario_destino' => [
          $node->getOwnerId(),
          $comment->getOwner(),
        ],
      ]);

      // If node has been created without errors.
      if ($event_node instanceof NodeInterface) {
        $event_node->save();
      }
      else {
        \Drupal::logger('consum_comunidad_events')->error('An error has occurred while event node was created.');
      }
    }
  }

  /**
   * Generates an event node from content trigger.
   */
  public static function setLikeEvent(EntityInterface $origin_entity, $message) {

    // Node entity like.
    if ($origin_entity instanceof Node) {
      $isset_node = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
        'field_contenido_referenciado' => $origin_entity->id()
      ]);

      // If isset an event for this node, skip the new event creation.
      if (empty($isset_node)) {
        $event_node = Node::create([
          'type' => 'evento',
          'title' => 'Like contenido - ' . $origin_entity->label(),
          'uid' => $origin_entity->getOwner(),
          'field_mensaje' => $message,
          'moderation_state' => [
            'target_id' => 'published',
          ],
          'field_contenido_referenciado' => [
            'target_id' => $origin_entity->id(),
          ],
          'field_tipo_de_evento' => 'dar_like',
          'field_usuario_destino' => $origin_entity->getOwnerId(),
        ]);

        // If node has been created without errors.
        if ($event_node instanceof NodeInterface) {
          $event_node->save();
        }
        else {
          \Drupal::logger('consum_comunidad_events')->error('An error has occurred while event node was created.');
        }
      }
    }

    // Comment entity like.
    elseif ($origin_entity instanceof Comment) {
      $node = $origin_entity->getCommentedEntity();

      // If comment has parent, returns this parent's owner ID.
      if ($origin_entity->hasParentComment()) {
        $parent_comment = $origin_entity->getParentComment();
        $event_node = Node::create([
          'type' => 'evento',
          'title' => 'Like comentario - ' . $node->label(),
          'uid' => $origin_entity->getOwner(),
          'field_mensaje' => $message,
          'moderation_state' => [
            'target_id' => 'published',
          ],
          'field_contenido_referenciado' => [
            'target_id' => $node->id()
          ],
          'field_comentario_de_referencia' => [
            'target_id' => $origin_entity->id(),
          ],
          'field_tipo_de_evento' => 'dar_like',
          'field_usuario_destino' => [
            $parent_comment->getOwnerId(),
            $origin_entity->getOwner(),
            $node->getOwnerId(),
          ],
        ]);

        // If node has been created without errors.
        if ($event_node instanceof NodeInterface) {
          $event_node->save();
        }
        else {
          \Drupal::logger('consum_comunidad_events')->error('An error has occurred while event node was created.');
        }
      }
      // If comment has no parent, field_usuario_destino is empty.
      else {
        $event_node = Node::create([
          'type' => 'evento',
          'title' => 'Like comentario - ' . $node->label(),
          'uid' => $origin_entity->getOwner(),
          'field_mensaje' => $message,
          'moderation_state' => [
            'target_id' => 'published',
          ],
          'field_contenido_referenciado' => [
            'target_id' => $node->id()
          ],
          'field_comentario_de_referencia' => [
            'target_id' => $origin_entity->id(),
          ],
          'field_tipo_de_evento' => 'dar_like',
          'field_usuario_destino' => [
            $node->getOwnerId(),
            $origin_entity->getOwner(),
          ],
        ]);

        // If node has been created without errors.
        if ($event_node instanceof NodeInterface) {
          $event_node->save();
        }
        else {
          \Drupal::logger('consum_comunidad_events')->error('An error has occurred while event node was created.');
        }
      }
    }
  }

}
