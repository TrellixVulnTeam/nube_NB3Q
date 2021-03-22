<?php

namespace Drupal\consum_tol_integration;

use Drupal\file\Entity\File;
use Drupal\user\Entity\User;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class ProductsConnection.
 *
 * @package Drupal\consum_tol_integration\Services
 */
class ProductsConnection {

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
  public function getProductsFiltered() {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V1.0/catalog/product?q=agua&limit=5', ["X-TOL-ZONE" => "0","X-TOL-LOCALE" => "es","X-TOL-CHANNEL" => "1",]);
    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
      return [];
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
  public function getProductsByQuery($query, $limit) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V1.0/catalog/product?q=' . $query . '&limit=' . $limit ,
      ["X-TOL-ZONE" => "0","X-TOL-LOCALE" => "es","X-TOL-CHANNEL" => "1",]);

    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
      return [];
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
  public function getProductsByCategories($categories, $limit) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V1.0/catalog/product?categories=' . $categories . '&limit=' . $limit ,
      ["X-TOL-ZONE" => "0","X-TOL-LOCALE" => "es","X-TOL-CHANNEL" => "1",]);

    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
      return [];
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
  public function getProductById($id) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V1.0/catalog/product/?productCodes=' . $id, ["X-TOL-ZONE" => "0","X-TOL-LOCALE" => "es","X-TOL-CHANNEL" => "1",]);

    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data_log = json_decode($response->getBody());
      $data = $data_log->products[0];
      if (empty($data->productData)) {
        $data->productData->imageURL = "https://cdn.aktiosdigitalservices.com/tol/consum/catalog/product/media/img/135x135/7139975_001.jpg?t=20200926083004";
        $data->productData->url = "http://consum.docker.localhost/";
        $data->productData->name = "Producto no encontrado";
        $data->productData->description = "El producto no ha sido encontrado en la tienda online";
        $data->priceData->prices[0]->value->centAmount = 0.00;
      }
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
      return [];
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
  public function getProductByTolId($id) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V1.0/catalog/product/' . $id, ["X-TOL-ZONE" => "0","X-TOL-LOCALE" => "es","X-TOL-CHANNEL" => "1",]);

    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
      return [];
    }

    return $data;
  }

  /**
   * Gets a list of the categories ordered.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getCategories($number) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('GET',  $this->config->get('url') . 'rest/V1.0/shopping/category/?limit=' . $number, ["X-TOL-ZONE" => "0","X-TOL-LOCALE" => "es","X-TOL-CHANNEL" => "1",]);
    try {
      $response = $client->send($request, ['timeout' => 30]);
      $data = json_decode($response->getBody());
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_tol_integration')->error($e);
      return [];
    }

    return $data;
  }

}
