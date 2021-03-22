<?php

namespace Drupal\consum_tol_integration\Controller;

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
class AutocompleteArticles extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
    $results = [];
    $input = $request->query->get('q');

    // Get the typed string from the URL, if it exists.
    if (!$input) {
      return [];
    }

    // Get the results of the query from products connection service.
    $results = \Drupal::service('products.connection')->getProductsByQuery($input, '15');
    if (!empty($results->products)) {
      // Foreach product received from the service response.
      foreach ($results->products as $product) {
        // Set an array with id-product name.
        $products_result[] = [
          'value' => $product->id,
          'label' => $product->productData->brand->name ? $product->productData->name . ' ' . $product->productData->brand->name : $product->productData->name,
        ];
      }
    }

    return new JsonResponse($products_result);
  }

}
