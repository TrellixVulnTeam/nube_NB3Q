<?php

namespace Drupal\consum_tol_integration;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class ProductsConnection.
 *
 * @package Drupal\consum_tol_integration\Services
 */
class ShoppingCartConnection {

  /**
   * @var \Drupal\Core\Config\Config TOL settings
   */
  protected $config  = NULL;

  /**
   * ProductsConnection constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('consum_tol_integration.settings');
  }

  /**
   * Gets a list of the products filtered.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getUserToken() {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('POST',  $this->config->get('url') . 'rest/V2.0/account/token');
    try {
      $body = [
        'grant_type' => 'password',
        'username' => '77865085B',
        'password' => md5('Royal_1144'),
        'client_id' => 'webConsum'
      ];
      $response = $client->send($request, ['timeout' => 30, 'form_params' => $body]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
    }

    return $data;
  }

/**
   * Gets a list of the products filtered.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getShoppingCart($token) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V2.0/shopping/cart?shippingMethod=D',[
      'X-TOL-TOKEN' => $token,
      'X-TOL-ZONE' => '0',
      'X-TOL-USERID' => '207220',
      'X-TOL-CHANNEL' => '1',
      'X-TOL-LOCALE' => 'es',
      'X-TOL-CURRENCY' => 'EUR'
    ]);
    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
    }

    return $data;
  }

}
