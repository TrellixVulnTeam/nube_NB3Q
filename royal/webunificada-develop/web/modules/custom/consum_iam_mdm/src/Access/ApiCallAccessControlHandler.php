<?php

namespace Drupal\consum_iam_mdm\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the contact entity.
 *
 * @see \Drupal\consum_iam_mdm\Entity\ApiCallEntity.
 */
class ApiCallAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        if (in_array('administrator', $account->getRoles())) {
          return AccessResult::allowed();
        }
        else {
          return AccessResult::forbidden();
        }
      case 'edit':
        return AccessResult::forbidden();
      case 'delete':
        return AccessResult::forbidden();
    }
    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist, it
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::forbidden();
  }

}
?>