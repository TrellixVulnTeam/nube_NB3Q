<?php


namespace Drupal\consum_openid;

use Drupal;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\openid_connect\Plugin\OpenIDConnectClientInterface;
use Drupal\openid_connect\Plugin\OpenIDConnectClientManager;

/**
 * The Consum OpenID Connect authmap service.
 *
 * @package Drupal\consum_openid
 */
class ConsumOpenIDConnectAuthmap
{

  /**
   * The User entity storage.
   *
   * @var EntityStorageInterface
   */
  protected $userStorage;

  /**
   * The field plugin manager.
   *
   * @var FieldTypePluginManagerInterface
   */
  protected $pluginManager;

  /**
   * Constructs a OpenIDConnectAuthmap service object.
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   * @param OpenIDConnectClientManager $plugin_manager
   *   The OpenID Connect client manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, OpenIDConnectClientManager $plugin_manager) {
    $this->userStorage = $entity_type_manager ? $entity_type_manager->getStorage('user') : Drupal::entityTypeManager()->getStorage('user');
    $this->pluginManager = $plugin_manager;
  }

  /**
   * Create a account for new user in IAM Server.
   *
   * @param array $user_data
   *   A array with user data.
   * @param string $client_name
   *   The client name.
   */
  public function createExternalAccount($user_data, $client_name = 'consum') {

    $configuration = Drupal::config('openid_connect.settings.' . $client_name)->get('settings');

    /** @var OpenIDConnectClientInterface $client */
    $client = $this->pluginManager->createInstance(
      $client_name,
      $configuration
    );
    return $client->createNewUser($user_data);
  }

  /**
   * Update user in IAM Server.
   *
   * @param array $user_data
   *   A array with user data.
   * @param string $client_name
   *   The client name.
   */
  public function updateExternalAccount($user_data, AccountInterface $account, $client_name = 'consum') {

    $configuration = Drupal::config('openid_connect.settings.' . $client_name)->get('settings');

    /** @var OpenIDConnectClientInterface $client */
    $client = $this->pluginManager->createInstance(
      $client_name,
      $configuration
    );
    return $client->updateUser($user_data, $account);
  }

  /**
   * Cancel remote account in IAM Server.
   *
   * @param object $account
   *   A user account object.
   * @param string $client_name
   *   The client name.
   */
  public function deleteExternalAccount(AccountInterface $account, $client_name = 'consum') {

    $configuration = Drupal::config('openid_connect.settings.' . $client_name)->get('settings');

    /** @var OpenIDConnectClientInterface $client */
    $client = $this->pluginManager->createInstance(
      $client_name,
      $configuration
    );
    return $client->cancelUser($account);
  }

  /**
   * Logout remote account in IAM Server.
   *
   * @param object $account
   *   A user account object.
   * @param string $client_name
   *   The client name.
   */
  public function logoutExternalAccount(AccountInterface $account, $client_name = 'consum') {

    $configuration = Drupal::config('openid_connect.settings.' . $client_name)->get('settings');

    /** @var OpenIDConnectClientInterface $client */
    $client = $this->pluginManager->createInstance(
      $client_name,
      $configuration
    );
    return $client->logoutUser($account);
  }

}
