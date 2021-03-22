<?php

namespace Drupal\consum_iam_mdm;

use Drupal\user\Entity\User;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * Class IamTokenConnection.
 *
 * @package Drupal\consum_iam_mdm\Services
 */
class IamTokenConnection {

  /**
   * @var \Drupal\Core\Config\Config IamTokenConnection settings.
   */
  protected $config  = NULL;

  /**
   * ProductsConnection constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('consum_iam_mdm.settings');
  }

  /**
   * Gets the current user token.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getIamToken(User $user) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('POST',  $this->config->get('api_iam_endpoint') . '?scope=openid', [
      "Content-Type" => 'application/x-www-form-urlencoded',
      "Authorization" => "Basic dlR5dmVzY3FjNDlYM05QNFNJUTdBYzRkTXRRYTprT3NPbUgxSVRTZm5uMFg4aEZmTFBobEZ0Yjhh",
    ]);
    try {
      $body = [
        'grant_type' => 'password',
        'username' => $user->getUsername(),
        'password' => 'prueba',
        'client_id' => $this->config->get('api_iam_client_id'),
      ];
      $response = $client->send($request, ['timeout' => 30, 'form_params' => $body]);
      $data = json_decode($response->getBody());
      $_SESSION['access_token'] = $data->access_token;
    }
    catch (Exception $e) {
      \Drupal::logger('consum_iam_mdm')->error('IAM service unavailable. ' . $e);
    }
    return $data;
  }

  /**
   * Gets the client token.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getIamClientToken() {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('POST',  $this->config->get('api_iam_endpoint') . '?scope=openid', [
      "Content-Type" => 'application/x-www-form-urlencoded',
    ]);
    try {
      $body = [
        'grant_type' => 'client_credentials',
        'client_id' => $this->config->get('api_iam_client_id'),
        'client_secret' => $this->config->get('api_iam_client_secret'),
      ];
      $response = $client->send($request, ['timeout' => 30, 'form_params' => $body]);
      $data = json_decode($response->getBody());
      $_SESSION['access_token'] = $data->access_token;
    }
    catch (Exception $e) {
      \Drupal::logger('consum_iam_mdm')->error('IAM service unavailable. ' . $e);
    }
    return $data;
  }

}
