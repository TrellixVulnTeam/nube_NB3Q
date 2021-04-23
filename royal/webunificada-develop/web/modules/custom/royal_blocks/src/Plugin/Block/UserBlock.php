<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;

/**
 * Defines User block.
 *
 * @Block(
 *   id = "user_block",
 *   admin_label = @Translation("User")
 * )
 */
class UserBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $account = User::load(\Drupal::currentUser()->id());

    if (NULL != $account->user_picture->entity) {
      $file_uri = $account->user_picture->entity->getFileUri();
      $user_picture = file_create_url($file_uri);
    }else{
      $user_picture = "false";
    }
    
    return [
      '#theme' => 'content_user',
      '#attributes' => [
        'class' => [
          'col-1',
          'col-sm-auto',
          'd-flex',
          'align-items-center',
          'order-5',
          'order-sm-6',
        ],
      ],
      "#user_picture" => $user_picture,
    ];
  }

}
