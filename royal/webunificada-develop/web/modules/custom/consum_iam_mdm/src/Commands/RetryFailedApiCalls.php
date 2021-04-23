<?php

namespace Drupal\consum_iam_mdm\Commands;

use Drupal;
use Drupal\node\NodeInterface;
use Drupal\royal_services\Helper\APIManager;
use Drush\Commands\DrushCommands;

/**
 * A drush command file.
 *
 * @package Drupal\royal_services\Commands
 */
class RetryFailedApiCalls extends DrushCommands {

  /**
   * Drush command that displays the given text.
   *
   * @param string $text
   *   Argument with message to be displayed.
   * @command rs-retry_failed_api_calls:message
   * @aliases rs-rfac
   * @usage rs-retry_failed_api_calls:option
   */


  public function message($text = '', $options = []) {

    // Buscamos la lista de llamadas pendientes.
    $storage = Drupal::entityTypeManager()
      ->getStorage('node');

    $query = $storage->getQuery();

    $nids = $query
      ->condition('type','api_call')
      ->condition('field_success','false')
      ->sort('field_created', 'ASC')->execute()
    ;

    if (empty($nids)) {
      $message = 'No hay llamadas pendientes.';
      $this->output()->writeln($message);
      return;
    }
    else
    {
      $message = 'Hay ' . count($nids) . ' llamadas pendientes.';
      $this->output()->writeln($message);
      $nodes = $storage->loadMultiple($nids);
      foreach ($nodes as $node) {

        // Recopilamos la información del ultimo intento
        $method = $node->field_method->value;
        $url = $node->field_url->value;
        $data = json_decode($node->field_body->value, true) ;
        $statusCode = $node->field_status_code->value;
        $error = $node->field_error->value;
        $response = $node->field_response->value;

        // Lanzamos la llamada de nuevo
        $manager = new APIManager($method, $url, $data, $statusCode, $error, $response, $entity_affected = NULL);
        $resultNode = $manager->send($node);
        if ($resultNode instanceof NodeInterface) {
          // Sacamos por consola el resultado
          $title = $resultNode->getTitle();
          $success = ($resultNode->hasField('field_success') && $resultNode->field_success->value) ? 'con' : 'sin';
          $this->output()->writeln('Se ha reintentado el envío de la llamada: ' . $title . ' ' . $success . ' éxito');
        }
      }
      return;
    }

  }

}
