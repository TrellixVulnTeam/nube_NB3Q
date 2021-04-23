<?php

namespace Drupal\consum_mundoconsum\Controller;

/**
* @file
* Contains \Drupal\consum_organizator\Controller\AutocompleteArticles.
*/

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller to return the articles to autocomplete field.
 */
class TestController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function landing() {
    // Get the results of the query from products connection service.
    $results = \Drupal::service('shoppingcart.connection')->getShoppingCart();

    return new JsonResponse($results);
  }

}
