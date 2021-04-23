<?php

namespace Drupal\consum_theme_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;

/**
 * Defines User block.
 *
 * @Block(
 *   id = "user-block",
 *   admin_label = @Translation("User")
 * )
 */
class ConsumThemeUserBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $account = User::load(\Drupal::currentUser()->id());

    $user_login = false;
    $user_mundo_consum = $account->hasRole('mundo_consum');
    if (0 < \Drupal::currentUser()->id()) {
      $user_login = true;
    }

    if (NULL != $account->user_picture->entity) {
      $file_uri = $account->user_picture->entity->getFileUri();
      $user_picture = file_create_url($file_uri);
    }else{
      $user_picture = "false";
    }

    $block_manager = \Drupal::service('plugin.manager.block');
    // You can hard code configuration or you load from settings.
    $config = [];
    $plugin_block = $block_manager->createInstance('consum_iam_login_block', $config);
    $btn_iam_login = $plugin_block->build();

    
    return [
      '#theme' => 'consum_theme_user',
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
      "#user_login" => $user_login,
      "#user_mundo_consum" => $user_mundo_consum,
      "#btn_iam_login" => $btn_iam_login,
    ];
  }

}
