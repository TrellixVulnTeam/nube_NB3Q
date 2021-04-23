<?php

namespace Drupal\consum_iam_mdm\Plugin\rest\resource;

use Drupal\consum_iam_mdm\EntityHelperMDM;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\user\Entity\User;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides a MDM user resource.
 *
 * @RestResource(
 *   id = "user_id_mdm_rest_resource",
 *   label = @Translation("User Id MDM Rest Resource"),
 *   uri_paths = {
 *     "canonical" = "/services/mdm/user/{id_mdm}",
 *     "https://www.drupal.org/link-relations/create" = "/services/mdm/user"
 *   }
 * )
 */
class UserIdMdmRestResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return Symfony\Component\HttpFoundation\JsonResponse
   */
  public function get($id_mdm = NULL) {
    $helper = new EntityHelperMDM();

    $account = $helper->getUserByField('field_id_mdm', $id_mdm);
    if (empty($account))
      return new JsonResponse( ['status' => 'Not Found','data' => 'User not Found'], '404' );

    $name = $account->getUsername();

    return new JsonResponse($name);
  }

  /**
   * Responds to entity POST requests.
   *
   * @param Request $request
   * @return void
   */
  public function post(Request $request) {
    try {

      /* This condition checks the `Content-type` and makes sure to
       * decode JSON string from the request body into array.
       */
      if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), TRUE);
        $request->request->replace(is_array($data) ? $data : []);
      } else {
        return new JsonResponse( ['status' => 'Bad request', 'data' => 'Malformed data'], '400'  );
      }

      $userData = $data['userInfo'];

      $documentoPrincipal = $userData['infoPersonal']['documentoPrincipal']['numDocumento'];
      $idMdm = $userData['idMdm'];
      $codigoSocio = $userData['codigoSocio'];
      $usernameIAM = $userData['idIam'];

      if ((!empty($usernameIAM)) || (!empty($documentoPrincipal)) || (!empty($idMdm)) || (!empty($codigoSocio))) {
        // Verify that user exist from these variables: documento | codigoSocio | idMdm
        $helper = new EntityHelperMDM();
        $account = $helper->getAccountFromMDMData($userData);
        if (!empty($account)) {
          // Update found user.
          try {
            $helper->updateUserProcess($account, $request);
            return new JsonResponse( ['status' => 'OK', 'data' => 'User correctly updated.'], '200');
          }
          catch (Exception $exception) {
            return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $exception->getMessage()], '500'  );
          }
        }

        if (!empty($usernameIAM)) {
          $aplicacionModificacion = $userData['aplicacionModificacion'];
          if (empty($email = $userData['infoPersonal']['email'])) {
            $email = 'consum-' . rand(1,100000000000) . '@temp-mail.test';
          }
          $nombre = $userData['infoPersonal']['nombre'];
          $apellido1 = $userData['infoPersonal']['apellido1'];
          $apellido2 = $userData['infoPersonal']['apellido2'];
          $username = $usernameIAM ? $usernameIAM : $email;
          $pass = user_password();

          $user = User::create();
          $user->setUsername($username);
          $user->setPassword($pass);
          $user->setEmail($email);
          $user->enforceIsNew();

          $user->set('init', $email);
          $user->set('field_nombre', $nombre);
          $user->set('field_id_mdm', $idMdm);
          $user->set('field_apellido1', $apellido1);
          $user->set('field_apellido2', $apellido2);
          $user->set('field_documento_principal_numero', $documentoPrincipal);
          $user->set('field_codigo_socio', $codigoSocio);
          $user->set('field_aplicacion_modificacion', $aplicacionModificacion);

          $violations = $user->validate();
          if ($violations->count() > 0) {
            // Validation failed.
            $error = [];
            foreach ($violations as $v) {
              $error[] = $v->getMessage();
            }
            return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $error], '500'  );
          }
          $user->activate();
          $user->save();

          if (!empty($userData['infoPersonal']['direccion'])) {
            $direccion = $helper->createNewDireccion($userData['infoPersonal']['direccion']);
            $user->set('field_direccion', $direccion);
            $user->save();
          }

          return new JsonResponse( ['status' => 'created', 'data' => 'User has been created without any error.'], '201' );
        }
        else {
          return new JsonResponse( ['status' => 'No content', 'data' => 'No content found.'], '204' );
        }
      }
      else {
        return new JsonResponse( ['status' => 'No content', 'data' => 'No content found.'], '204' );
      }
    }
    catch (Exception $exception) {
      return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $exception->getMessage()], '500'  );
    }

  }

  /**
   * Responds to entity PUT requests.
   *
   * @param Request $request
   * @return void
   */
  public function put(Request $request) {
    /* This condition checks the `Content-type` and makes sure to
     * decode JSON string from the request body into array.
     */
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
      $data = json_decode($request->getContent(), TRUE);
      $request->request->replace(is_array($data) ? $data : []);
      $id_mdm = substr($request->getRequestUri(), 23);
    }
    else {
      return new JsonResponse( ['status' => 'Bad request', 'data' => 'Malformed data'], '400'  );
    }

    $userData = $data['userInfo'];
    $documentoPrincipal = $userData['infoPersonal']['documentoPrincipal']['numDocumento'];
    $idMdm = $userData['idMdm'];
    $codigoSocio = $userData['codigoSocio'];
    $usernameIAM = $userData['idIam'];
    $helper = new EntityHelperMDM();

    // Verify that user exist from these variables: documento | codigoSocio | idMdm | username
    $account = $helper->getAccountFromMDMData($userData);

    if ((empty($account)) && (!empty($usernameIAM))) {
      try {
        $documentoPrincipal = $userData['infoPersonal']['documentoPrincipal']['numDocumento'];
        $idMdm = $userData['idMdm'];
        $codigoSocio = $userData['codigoSocio'];

        $aplicacionModificacion = $userData['aplicacionModificacion'];
        $email = $userData['infoPersonal']['email'];
        $nombre = $userData['infoPersonal']['nombre'];
        $apellido1 = $userData['infoPersonal']['apellido1'];
        $apellido2 = $userData['infoPersonal']['apellido2'];
        $username = $usernameIAM ? $usernameIAM : ('Consum-' . rand(1,100000000000)) ;
        $pass = user_password();

        $user = User::create();
        $user->setUsername($username);
        $user->setPassword($pass);
        $user->setEmail($email);
        $user->enforceIsNew();

        $user->set('init', $email);
        $user->set('field_nombre', $nombre);
        $user->set('field_id_mdm', $idMdm);
        $user->set('field_apellido1', $apellido1);
        $user->set('field_apellido2', $apellido2);
        $user->set('field_documento_principal_numero', $documentoPrincipal);
        $user->set('field_codigo_socio', $codigoSocio);
        $user->set('field_aplicacion_modificacion', $aplicacionModificacion);

        $violations = $user->validate();
        if ($violations->count() > 0) {
          // Validation failed.
          $error = [];
          foreach ($violations as $v) {
            $error[] = $v->getMessage();
          }
          return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $error], '500'  );
        }
        $user->activate();

        $user->save();

        $direccion = $helper->createNewDireccion($userData['infoPersonal']['direccion']);
        $user->set('field_direccion', $direccion);

        $user->save();

        return new JsonResponse( ['status' => 'created', 'data' => 'User has been created without any error.'], '201' );
      }
      catch (Exception $exception) {
        return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $exception->getMessage()], '500'  );
      }
    }
    elseif (((!empty($account)) && (!empty($documentoPrincipal))) || ((!empty($account)) && (!empty($codigoSocio)))) {
      try {
        $updated_user = $helper->updateUserProcess($account, $request);
        return new JsonResponse( ['status' => 'OK', 'data' => 'The user has been updated without any error'], '200');
      }
      catch (Exception $exception) {
        return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $exception->getMessage()], '500');
      }
    }
    else {
      return new JsonResponse( ['status' => 'No content', 'data' => 'No content found.'], '204' );
    }

    return new JsonResponse( ['status' => 'Bad request', 'data' => 'Method not allowed'], '400'  );
  }

  /**
   * Responds to entity DELETE requests.
   *
   * @param String $id_mdm
   * @return void
   */
  public function delete($id_mdm) {
    try {
      $helper = new EntityHelperMDM();
      $account = $helper->getUserByField('field_id_mdm', $id_mdm);
      if (!empty($account)) {
        $deleted = $helper->sendUsuarioBorrado($account);
        if (!empty($deleted)) {
          $account->delete();
          return new JsonResponse( ['status' => 'OK', 'data' => 'Se ha eliminado correctamente, pero no devuelve ningún contenido'], '204');
        }
        else {
          return new JsonResponse( ['status' => 'Internal Server Error','data' => 'Internal Server Error'], '500' );
        }
      }
      else {
        return new JsonResponse( ['status' => 'Not found','data' => 'User not found'], '404' );
      }
    } catch (Exception $exception) {
      return new JsonResponse( ['status' => 'Internal Server Error', 'data' => $exception->getMessage()], '500'  );
    }
  }

  public function permissions() {
    return [];
  }

}