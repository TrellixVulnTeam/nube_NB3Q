<?php


namespace Drupal\consum_openid\Plugin\Block;


use Drupal;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;

/**
 *
 * @Block(
 *   id = "consum_iam_login_block",
 *   admin_label = @Translation("Consum IAM Login Block")
 * )
 */
class ConsumIAMLoginBlock extends BlockBase
{

  protected function blockAccess(AccountInterface $account)
  {
    if ($account->isAnonymous()) {
      return AccessResult::allowed()
        ->addCacheContexts([
          'user.roles:anonymous',
        ]);
    }
    return AccessResult::forbidden();
  }

  /**
     * @inheritDoc
     */
    public function build()
    {
      return Drupal::formBuilder()->getForm('Drupal\consum_openid\Form\ConsumOpenIDLoginForm');
    }
}
