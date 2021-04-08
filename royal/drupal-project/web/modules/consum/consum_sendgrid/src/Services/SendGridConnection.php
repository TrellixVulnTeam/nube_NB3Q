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
   * @var \Drupal\Core\Config\Config SendGridConnection settings.
   */
  protected $config  = NULL;


  /**
   * SendGridConnection constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('consum_sendgrid.settings');
  }

  /**
   * Request to SendGrid
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function RequestSendGrid($email) {
    //$token="Bearer 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";   'https://api.sendgrid.com/v3/marketing/contacts'
    $client  = new GuzzleClient();        
    $request = new GuzzleRequest('PUT', $this->config->get('url'), [
      'Content-Type' => 'application/json',
      'Authorization'=> $this->config->get('token'),
    ]);
    try { 
      $body =  json_encode([
        "contacts" => [[
          "email" => $email
        ]]
      ]);

     // $response = $client->send($request, ['timeout' => 30, 'form_params' => $body]);
      $response = $client->send($request, ['timeout' => 30, 'body' => $body]);
      $data = json_decode($response->getBody());
      
      \Drupal::logger('consum_sendgrid')->warning('Status code: ' . $response->getStatusCode());
     
    }
    catch (Exception $e) {
      \Drupal::logger('consum_sendgrid')->error('SendGrid service unavailable. ' . $e);
    }
    return $data;
  }

}
