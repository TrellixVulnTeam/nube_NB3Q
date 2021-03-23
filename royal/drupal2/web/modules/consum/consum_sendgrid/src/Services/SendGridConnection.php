<?php

namespace Drupal\consum_sendgrid\Services;

use Drupal\user\Entity\User;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class SendGridConnection.
 *
 * @package Drupal\consum_sendgrid\Services
 */
class SendGridConnection {

  /**
   * @var \Drupal\Core\Config\Config IamTokenConnection settings. //???
   */
  protected $config  = NULL;

  /**
   * Request to SendGrid
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function RequestSendGrid($email) {
    $token="Bearer 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";
    $client  = new GuzzleClient();         //$this->config->get('url')
    $request = new GuzzleRequest('PUT',  'https://api.sendgrid.com/v3/marketing/contacts', [
      'Content-Type' => 'application/json',
      'Authorization'=> $token,
    ]);
    try { 
      $body = json_encode([
        "contacts" => [
          "email" => $email
        ]
      ]);

      $estado=$request->getStatusCode();

      if($estado=='200'){
        print("Petición realizada correctamente");
      } 

      $response = $client->send($request, ['timeout' => 30, 'form_params' => $body]);
      $data = json_decode($response->getBody());
    }
    catch (Exception $e) {
      \Drupal::logger('consum_sendgrid')->error('SendGrid service unavailable. ' . $e);
    }
    return $data;
  }

}
