<?php

namespace Drupal\simple_ldap_role;

use Drupal\simple_ldap\SimpleLdapException;
use Drupal\simple_ldap\SimpleLdapServer;
use Drupal\simple_ldap_user\SimpleLdapUser;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ImmutableConfig;

/**
 * Manages the loading and syncing of data between LDAP server and Drupal.
 */
class SimpleLdapRoleManager {

  /**
   * @var SimpleLdapServer
   */
  protected $server;

  /**
   * @var ImmutableConfig
   */
  protected $config;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $entity_manager;

  /**
   * @var array
   */
  protected $cache = [];

  public function __construct(SimpleLdapServer $server, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_manager) {
    $this->server = $server;
    $this->config = $config_factory->get('simple_ldap.role');
    $this->entity_manager = $entity_manager;
  }

  /**
   * Get the groups of which a user is a member on the LDAP server with a certain name.
   *
   * @param string $name
   *  The name to search for on the server.
   *
   * @return mixed
   *  A SimpleLdapUser object if the user is a member of groups on the server, FALSE if
   *   otherwise.
   *
   * @throws \Drupal\simple_ldap\SimpleLdapException
   */
  public function getLdapGroup($name) {
    $cid = sprintf('LdapRole::%s', $name);
    if (array_key_exists($cid, $this->cache)) {
      return $this->cache[$cid];
    }

    $basedn_group = $this->config->get('basedn_group');
    $basedn_user = $this->config->get('basedn_user');
    $scope = $this->config->get('scope');

    $object_classes = $this->config->get('object_class');
    $object_class_filter = '';
    if (isset($object_classes)) {
      $object_class_filter = '(objectclass=' . implode(')(objectclass=', $object_classes) . ')';
    }
    $search_filter = '(member=cn=' . $name . ',' . $basedn_user . ')';
    $filter = '(&' . $object_class_filter . $search_filter . ')';

    if (!$this->server->bind(Null, Null, true)) {
      $this->cache[$cid] = FALSE;
      return FALSE;
    }

    try {
      $results = $this->server->search($basedn_group, $filter, $scope, [], 0, 0);
    }
    catch (SimpleLdapException $e) {
      if ($e->getCode() == -1) {
        $results = array();
      }
      else {
        $this->cache[$cid] = FALSE;
        throw $e;
      }
    }

    if (count($results) >= 1) {
      foreach ($results as $key => $result) {
        $simple_ldap_role[$key] = new SimpleLdapUser($key, $result);
      }
      $this->cache[$cid] = $simple_ldap_role;
      return $simple_ldap_role;
    }

    return $results;
  }

}
