<?php

namespace Drupal\consum_iam_mdm;

use Drupal;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;
use Drupal\user\Plugin\migrate\destination\EntityUser;
use Exception;
use User;

class EntityHelperMDM {

  /**
   * Sends a new user to ApiManagerMDM service post.
   */
  public function sendUsuarioNuevo($usuario, $action = 'altaUsuario') {
    $array = $this->getArrayFromUsuario($usuario);
    $payload = ['userInfo' => ['infoPersonal' => $array]];
    $data = $this->formatDataToSend($payload, $action);

    $response = \Drupal::service('apimanagermdm.connection')->postUser($data);

    return $response;
  }


  /**
   * Sends an updated user to ApiManagerMDM service post.
   */
  public function sendUsuarioModificado($entity, $action = 'modificarUsuario') {
    $array = [];
    $infoPersonal = [];

    $changesUser = $this->entityHasChanged($entity);

    // If user has no address, generate a new address for user.
    if (empty($entity->original->field_direccion->target_id) && !empty($entity->field_direccion->target_id)) {
      $array['infoPersonal']['direccion'] = $this->parseDireccion($entity->field_direccion->entity);
    }
    // If user had a direction and now it's been deleted.
    if (!empty($entity->original->field_direccion->target_id) && empty($entity->field_direccion->target_id)) {
      $array['infoPersonal']['direccion'] = [];
    }

    // If has no changes on MDM.
    if (empty($changesUser) && empty($array)) {
        return false;
    }

    $infoPersonal['nombre'] = ($changesUser['field_nombre']) ?: '';
    $infoPersonal['apellido1'] = ($changesUser['field_apellido1']) ?: '';
    $infoPersonal['apellido2'] = ($changesUser['field_apellido2']) ?: '';
    $infoPersonal['email'] = ($changesUser['email']) ?: '';

    if (empty($array)) {
      $array['infoPersonal'] = array_filter($infoPersonal);
    }
    else {
      $array['infoPersonal'] = array_merge($array['infoPersonal'], array_filter($infoPersonal));
    }

    $array['idMdm'] = ($entity->field_id_mdm->value) ? $entity->field_id_mdm->value : '';
    $array['codigoSocio'] = ($entity->field_codigo_socio->value) ? $entity->field_codigo_socio->value : '';
    $field_documento_principal_numero = '';
    if ($entity->hasField('field_documento_principal_numero') && !$entity->field_documento_principal_numero->isEmpty()) {
      $field_documento_principal_numero = $entity->field_documento_principal_numero->value;
    }
    $array['infoPersonal']['documentoPrincipal']['numDocumento'] = $field_documento_principal_numero;

    $payload = ['userInfo' => array_filter($array)];

    $data = $this->formatDataToSend($payload, $action);

    if (empty($entity->get('field_id_mdm'))) {
      $action = 'altaUsuario';
    }

    $response = \Drupal::service('apimanagermdm.connection')->postUser($data);

    return $response;
  }

  // Recibimos una direccion y tenemos que conseguir el identificador del usuario:
  public function sendDireccionModificada($entity, $changesDireccion, $action = 'modificarUsuario') {
    $nodes = Drupal::entityTypeManager()->getStorage('user')->loadByProperties([
      'field_direccion' => $entity->nid->value,
    ]);
    if (empty($nodes))
      return false;

    $userIdentificador = reset($nodes)->field_id_mdm->value;

    $payload = ['userInfo' => ['idMdm' => $userIdentificador, 'infoPersonal' => [ 'direccion' => $changesDireccion]]];

    $data = $this->formatDataToSend($payload, $action);

    $response = \Drupal::service('apimanagermdm.connection')->postUser($data);

    return $response;
  }

  public function sendUsuarioBorrado($usuario) {
    $action = 'bajaUsuario';
    $array = [];

    $array['idMdm'] = $usuario->field_id_mdm->value ? $usuario->field_id_mdm->value : '';
    $array['codigoSocio'] = $usuario->field_codigo_socio->value ? $usuario->field_codigo_socio->value : '';
    $field_documento_principal_numero = '';
    if ($usuario->hasField('field_documento_principal_numero') && !$usuario->field_documento_principal_numero->isEmpty()) {
      $field_documento_principal_numero = $usuario->field_documento_principal_numero->value;
    }
    $array['infoPersonal']['documentoPrincipal']['numDocumento'] = $field_documento_principal_numero;

    $data = ['userInfo' => array_filter($array)];

    $data = $this->formatDataToSend($data, $action);

    $response = \Drupal::service('apimanagermdm.connection')->postUser($data);

    return $response;
  }


  protected function getArrayFromUsuario($usuario) {
    $array = [
      "nombre" => '',
      "apellido1" => '',
      "apellido2" => '',
      "email" => '',
      "direccion" => '',
    ];
    if ($usuario->hasField('field_nombre') && !$usuario->field_nombre->isEmpty()) {
      $array['nombre'] = $usuario->field_nombre->value;
    }
    if ($usuario->hasField('field_apellido1') && !$usuario->field_apellido1->isEmpty()) {
      $array['apellido1'] = $usuario->field_apellido1->value;
    }
    if ($usuario->hasField('field_apellido2') && !$usuario->field_apellido2->isEmpty()) {
      $array['apellido2'] = $usuario->field_apellido2->value;
    }
    $array['email'] = isset($usuario->mail->value) ? $usuario->mail->value : '';
    if ($usuario->hasField('field_direccion') && !$usuario->field_direccion->isEmpty()) {
      $array['direccion'] = $this->parseDireccion($usuario->field_direccion->entity);
    }
    $field_documento_principal_numero = '';
    if ($usuario->hasField('field_documento_principal_numero') && !$usuario->field_documento_principal_numero->isEmpty()) {
      $field_documento_principal_numero = $usuario->field_documento_principal_numero->value;
    }
    $array['documentoPrincipal']['numDocumento'] = $field_documento_principal_numero;
    return array_filter($array);
  }

  protected function formatDataToSend($data, $eventType, $id = null) {
    if ($eventType == 'altaUsuario') {
      $payload = $data;
    }
    else if ($eventType == 'modificarUsuario') {
      $payload = $data;
    }
    else if ($eventType == 'bajaUsuario') {
      $payload = $data;
    }
    else if ($eventType == 'poblarUsuario') {
      $payload = $data;
    }
    else {
      throw new Exception('Tipo de operación no encontrada.');
    }
    if (!empty($id)) {
      $payload['idMdm'] = $id;
    }

    $payload['userInfo']['fechaModificacion'] = Drupal::time()
      ->getCurrentTime();
    $payload['userInfo']['aplicacionModificacion'] = Drupal::config("consum_iam_mdm.settings")->get('api_mdm_modification');
    $payload['userInfo']['idTransaccion'] = md5($payload['userInfo']['fechaModificacion']);

    $dataFormatted = [
      'sessionId' => '',
      'events' => [
        [
          'eventType' => $eventType,
          'scope' => Drupal::config("consum_iam_mdm.settings")->get('api_mdm_scope'),
          'properties' => $payload
        ]
      ]
    ];
    return $dataFormatted;
  }


  public function parseDireccion($direccion)
  {
    $array = [];
    if (empty($direccion))
      return $array;
    // Ordenamos los campos para poder montar el título como si fuese una dirección postal
    $array['tipoVia'] = $direccion->field_tipovia->value ? $direccion->field_tipovia->value : '';
    $array['domicilio'] = $direccion->field_domicilio->value ? $direccion->field_domicilio->value : '';
    $array['codigoCalle'] = $direccion->field_codigo_calle->value ? $direccion->field_codigo_calle->value : '';
    $array['anexo'] = $direccion->field_anexo->value ? $direccion->field_anexo->value : '';
    $array['patio'] = $direccion->field_patio->value ? $direccion->field_patio->value : '';
    $array['anexoPatio'] = $direccion->field_anexo_patio->value ? $direccion->field_anexo_patio->value : '';
    $array['escalera'] = $direccion->field_escalera->value ? $direccion->field_escalera->value : '';
    $array['piso'] = $direccion->field_piso->value ? $direccion->field_piso->value : '';
    $array['puerta'] = $direccion->field_puerta->value ? $direccion->field_puerta->value : '';
    $array['poblacion'] = $direccion->field_poblacion->value ? $direccion->field_poblacion->value : '';
    $array['codigoPostal'] = $direccion->field_codigo_postal->value ? $direccion->field_codigo_postal->value : '';
    $array['provincia'] = $direccion->field_provincia->value ? $direccion->field_provincia->value : '';
    $array['pais'] = $direccion->field_pais->value ? $direccion->field_pais->value : '';

    $data = array_filter($array);

    return $data;
  }


  /**
   * Check if entity field content has changed
   * @param EntityInterface $entity The entity being saved
   * @return array                    A list of changed field names
   */
  public function entityHasChanged(EntityInterface $entity)
  {
    try {
      $changed_fields = [];
      if (empty($entity_original = $entity->original)) {
        return $changed_fields;
      }
      if ($entity->getEntityTypeId() == 'user') {
        $field_names = $this->getUserFieldList();
      } else {
        $field_names = $this->getFieldList($entity->bundle(), $entity->getEntityTypeId());
      }

      foreach ($field_names as $key => $field_name) {
        if ($entity->hasField($field_name) && $field_name !== 'field_comments' && $field_name !== 'created' && (!$entity->{$field_name}->isEmpty || !$entity->original->{$field_name}->isEmpty)) {
          if ($entity->{$field_name}->value !== $entity->original->{$field_name}->value) {
            $changed_fields[$field_name] = $entity->{$field_name}->value;
          }
        }
      }

      return $changed_fields;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }


  /**
   * Get list of field names from bundle
   * @param string $bundle Bundle name
   * @return array         Array of field names
   */
  public function getFieldList($bundle, $entity_type_id) {
    $fields_by_weight = [];
    $bundle_fields = \Drupal::entityTypeManager()
      ->getStorage('entity_view_display')
      ->load($entity_type_id . '.' . $bundle . '.' . 'default')
      ->getComponents();

    foreach ($bundle_fields as $name => $options) {
      $fields_by_weight[] = $name;
    }
    return $fields_by_weight;
  }

  public function getUserFieldList() {
    return [
      'field_nombre',
      'field_apellido1',
      'field_apellido2',
      'field_documento_principal_numero',
      'field_codigo_socio',
      'field_aplicacion_modificacion',
      'field_id_mdm',
    ];
  }

  // Funcion que se encarga de obtener un usuario buscado por cualquier campo indicado.
  public function getUserByField ($fieldName, $fieldValue) {
    $users = Drupal::entityTypeManager()
      ->getStorage('user')
      ->loadByProperties([
        $fieldName => $fieldValue,
      ]);
    return $users ? reset($users) : FALSE;
  }

    /**
   * Batería de funciones propias.
   * @ToDo las siguientes funciones son susceptibles de refactorización para crear clases, modelos o servicios.
   */

  // Funcion que se encarga de updatear un usuario desde los datos recibidos desde el MDM.
  public function updateUserProcess($user, $request) {
    $result = []; // Declaramos al principio el array donde iran volcandose los resultados de las operaciones.
    $data = json_decode( $request->getContent(), TRUE );
    $userData = $data['userInfo'];

    // Si el usuario no tenía anteriormente 'idMdm' llamaremos a poblarUsuario
    $userHasIdMdm = $user->hasfield('field_id_mdm') && !$user->field_id_mdm->isEmpty;

    $array['field_id_mdm'] = $userData['idMdm'] ? $userData['idMdm'] : '';
    $array['field_codigo_socio'] = $userData['codigoSocio'] ? $userData['codigoSocio'] : '';
    $array['field_documento_principal_numero'] = $userData['infoPersonal']['documentoPrincipal']['numDocumento'] ? $userData['infoPersonal']['documentoPrincipal']['numDocumento'] : '';
    $array['field_aplicacion_modificacion'] = $userData['aplicacionModificacion'] ? $userData['aplicacionModificacion'] : '';
    $array['field_nombre'] = $userData['infoPersonal']['nombre'] ? $userData['infoPersonal']['nombre'] : '';
    $array['field_apellido1'] = $userData['infoPersonal']['apellido1'] ? $userData['infoPersonal']['apellido1'] : '';
    $array['field_apellido2'] = $userData['infoPersonal']['apellido2'] ? $userData['infoPersonal']['apellido2'] : '';
    //$array['name'] = $userData['infoPersonal']['email'] ? $userData['infoPersonal']['email'] : '';
    $array['mail'] = $userData['infoPersonal']['email'] ? $userData['infoPersonal']['email'] : '';

    $data = array_filter($array);

    foreach ($data as $key => $value) {
      $user->set($key, $value);
    }

    // Buscamos y guardamos cambios en direccion, o creamos una nueva si el usuario no tenía anteriormente:
    if (!empty($userData['infoPersonal']['direccion'])) {
      if (empty($user->field_direccion->entity)) {
        $result['direccion'] = $this->createNewDireccion($userData['infoPersonal']['direccion']);
        $user->set('field_direccion', $result['direccion']);
      }
      elseif (!empty($user->get('field_direccion'))) {
        $result['direccion'] = $this->updateDireccion(
          $user->field_direccion->entity, // parametro 1 $direccion
          $this->parseDireccionFromMDM($userData['infoPersonal']['direccion']) // parametro 2 $data
        );
      }

    }

    // Y Guardamos cambio en usuario.
    $result['user'] = $user->save();

    // Funcionalidad de llamada a PoblarUsuario en caso de que el usuario ya existiese en Drupal sin idMdm asociado
    if (!$userHasIdMdm) {
      $helper = new EntityHelperMDM();
      $helper->sendUsuarioNuevo($user, 'poblarUsuario');
    }

    return $result;
  }

  // Funcion que se encarga de updatear una direccion a partir de un array clave=>valor de los campos.
  public function updateDireccion($direccion, $data) {
    foreach ($data as $key => $value) {
      $direccion->set($key, $value);
    }
    return ($direccion->save());
  }

  // Funcion que se encarga de crear una  nueva direccion a partir de los datos recibidos desde el MDM.
  public function createNewDireccion($direccion) {
    $data = $this->parseDireccionFromMDM($direccion) ;

    $title = implode(' ', $data);

    $data['type'] = 'direccion';
    $data['status'] = 1;
    $data['title'] = $title;

    $node = Node::create($data);
    $node->save();

    return $node;
  }

  // Funcion que se encarga de devolver un array clave=>valor de la dirección recibida desde el MDM.
  public function parseDireccionFromMDM($direccion) {
    $array = [];
    // Ordenamos los campos para poder montar el título como si fuese una dirección postal
    $array['field_tipovia'] = $direccion['tipoVia'] ? $direccion['tipoVia'] : '';
    $array['field_domicilio'] = $direccion['domicilio'] ? $direccion['domicilio'] : '';
    $array['field_codigo_calle'] = $direccion['codigoCalle'] ? $direccion['codigoCalle'] : '';
    $array['field_anexo'] = $direccion['anexo'] ? $direccion['anexo'] : '';
    $array['field_patio'] = $direccion['patio'] ? $direccion['patio'] : '';
    $array['field_anexo_patio'] = $direccion['anexoPatio'] ? $direccion['anexoPatio'] : '';
    $array['field_escalera'] = $direccion['escalera'] ? $direccion['escalera'] : '';
    $array['field_piso'] = $direccion['piso'] ? $direccion['piso'] : '';
    $array['field_puerta'] = $direccion['puerta'] ? $direccion['puerta'] : '';
    $array['field_poblacion'] = $direccion['poblacion'] ? $direccion['poblacion'] : '';
    $array['field_codigo_postal'] = $direccion['codigoPostal'] ? $direccion['codigoPostal'] : '';
    $array['field_provincia'] = $direccion['provincia'] ? $direccion['provincia'] : '';
    $array['field_pais'] = $direccion['pais'] ? $direccion['pais'] : '';

    $data = array_filter($array);

    return $data;
  }

  public function getAccountFromMDMData($userData) {
    $helper = new EntityHelperMDM();

    $documentoPrincipal = $userData['infoPersonal']['documentoPrincipal']['numDocumento'];
    $idMdm = $userData['idMdm'];
    $codigoSocio = $userData['codigoSocio'];
    $usernameIAM = $userData['idIam'];

    if (!empty($idMdm)) {
      $account = $helper->getUserByField('field_id_mdm', $idMdm);
      if (!empty($account)) {
        return $account;
      }
    }

    if (!empty($documentoPrincipal)) {
      $account = $helper->getUserByField('field_documento_principal_numero', $documentoPrincipal);
      if (!empty($account)) {
        return $account;
      }
    }

    if (!empty($codigoSocio)) {
      $account = $helper->getUserByField('field_codigo_socio', $codigoSocio);
      if (!empty($account)) {
        return $account;
      }
    }

    if (!empty($usernameIAM)) {
      $account = $helper->getUserByField('name', $usernameIAM);
      if (!empty($account)) {
        return $account;
      }
    }

    return [];
    }

}
