<?php

namespace Drupal\simple_ldap_role\Event;

use Drupal\simple_ldap_user\Events\SimpleLdapUserEvent;
use Drupal\simple_ldap_user\Events\Events;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LdapAuthRoleEventSubscriber.
 *
 * @package Drupal\simple_ldap_role
 */
class LdapAuthRoleEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents()
  {
    $events[Events::USER_SYNCHRONIZATION][] = ['onRequestUpdateLdapAuthRole'];

    return $events;
  }

  /**
   * On Request Check Restricted Pages.
   */
  public function onRequestUpdateLdapAuthRole(SimpleLdapUserEvent $event)
  {
    $config = \Drupal::config('simple_ldap.role');
    $manager = \Drupal::service('simple_ldap_role.manager');
    $data = $config->getRawData();
    $event_account = $event->getDrupalUser();

    $ldap_groups = $manager->getLdapGroup($event_account->getAccountName());
    $ldap_roles = [];
    $drupal_roles = $event_account->getRoles();
    if (!empty($ldap_groups)) {
      foreach ($ldap_groups as $ldap_group) {
        $group_attributes = $ldap_group->getAttributes();
        $ldap_roles[0] = 'authenticated';
        foreach ($group_attributes["cn"] as $attribute) {
          foreach ($data["roles"] as $key => $value) {
            if (in_array($attribute, $value)) {
              $ldap_roles[] = $key;
            }
          }
        }
      }
      $ldap_roles = array_unique($ldap_roles);
    }

    $account = null;
    $revoke_roles = array_diff($drupal_roles, $ldap_roles);
    $new_roles = array_diff($ldap_roles, $drupal_roles);

    if (!empty($revoke_roles)) {
      $account = User::load($event_account->id());
      foreach ($revoke_roles as $revoke_role) {
        $account->removeRole($revoke_role);
      }
    }

    if (!empty($new_roles)) {
      if (!$account instanceof UserInterface) {
        $account = User::load($event_account->id());
      }
      foreach ($new_roles as $new_role) {
        $account->addRole($new_role);
      }
    }

    if ($account instanceof UserInterface) {
      $account->save();
    }
  }
}
