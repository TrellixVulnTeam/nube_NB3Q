<?php


namespace Drupal\consum_iam_mdm;

use Drupal\consum_iam_mdm\Entity\ApiCallEntity;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\user\UserInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class ApiManagerMDM {

  /**
   * @var \Drupal\Core\Config\Config IAM/MDM settings
   */
  protected $config  = NULL;

  /**
   * APIManagerMDM constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('consum_iam_mdm.settings');
  }


  /**
   * Post a new user on MDM.
   *
   * @return bool|\Symfony\Component\HttpFoundation\Response
   *   API call response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function postUser(array $data) {
    $client  = new GuzzleClient();
    $request = new GuzzleRequest('POST',  $this->config->get('api_mdm_endpoint'), ['Content-Type' => 'application/json'], json_encode($data));
    try {
      $response = $client->send($request, ['timeout' => 30]);
      $status = $response->getStatusCode();
      $response_data = json_decode($response->getBody(), true);
    }
    catch (GuzzleException $e) {
      \Drupal::logger('consum_iam_mdm')->error($e);
      $response_data = $e;
      $status = '';
    }

    return $this->storeApiCall($data, $response_data, $status);
  }

  /**
   * Stores the api call on dedicated content.
   *
   * @param array $data
   * @param array $response_data
   * @param String $status
   * @return void
   */
  protected function storeApiCall($data, $response_data, $status) {
    $error = NULL;
    $user = null;
    $documento_principal_numero = $data['events']['0']['properties']['userInfo']['infoPersonal']['documentoPrincipal']['numDocumento'];
    if (!empty($documento_principal_numero)) {
      $user = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties([
        'field_documento_principal_numero' => $documento_principal_numero]);
      $user = reset($user);
    } else {
      $error = $response_data;
      $response_data = '';
      $success = FALSE;
    }

    if ($user instanceof UserInterface) {
      switch($status) {
        case '403':
        case '404':
        case '500':
          $success = FALSE;
          $error = $response_data;
          break;
        default:
          $success = TRUE;
      }
    }
    else {
      $error = 'User not valid';
      $success = FALSE;
    }

    $now = new DrupalDateTime('now');

    $call_data['name'] = bin2hex(random_bytes(20));
    $call_data['field_url'] = $this->config->get('api_mdm_endpoint');
    $call_data['field_body'] = json_encode($data);
    $call_data['field_method'] = 'POST';
    $call_data['field_response'] = $response_data;
    $call_data['field_retryed'][] = $now->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
    $call_data['field_status_code'] = $status;
    $call_data['field_success'] = $success;
    if ($user instanceof UserInterface) {
      $call_data['field_user_modified'] = $user ? $user->id() : NULL;
    }
    $call_data['field_error'] = $error;

    if ($call = ApiCallEntity::create($call_data)) {
      $call->save();
      return $call;
    }

    return FALSE;
  }
}
