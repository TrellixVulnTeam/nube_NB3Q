<?php

namespace Drupal\consum_iam_mdm\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a MDM user resource.
 *
 * @RestResource(
 *   id = "users_mdm_rest_resource",
 *   label = @Translation("Users MDM Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/services/mdm/user-list"
 *   }
 * )
 */
class UsersMdmRestResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $response = ['message' => 'Hello, this is a rest service'];
    return new ResourceResponse($response);
  }

  public function permissions() {
    return [];
  }

}