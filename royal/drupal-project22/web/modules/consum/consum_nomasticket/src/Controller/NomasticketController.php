<?php

namespace Drupal\consum_nomasticket\Controller;

use Drupal\Core\Controller\ControllerBase;
use stdClass;

class NomasticketController extends ControllerBase {

  //Función que llama al iframe, así como los métodos para cifrar el DNI y password:
  public function ticketsAction() {
    /*
    if (!$this->_usuario->hasIdentity()) {
      $this->_redirect("/" . $this->_language . "/auth/index");
    }
    $usuario = $this->_usuario->getIdentity();
    $this->view->usuario = $usuario;
    $oSocios = new Model_DbTable_Socios();
    $fSocio = $oSocios->find($usuario->idSocio)->current();
    if ($fSocio) {
    */

      $dniSocio="20439060H"; //base de datos //pasar a mayus la letra //fsocio
      $NOMASTICKETS_token = 'MS0TJXfyIxellV7BZ8OU'; //config $this->NOMASTICKETS_token
      $salt = array(0x49, 0x76, 0x61, 0x6e, 0x20, 0x4d, 0x65, 0x64, 0x76, 0x65, 0x64, 0x65, 0x76);

      $saltBytesstring = "";
      for($i=0;$i<count($salt);$i++){ 
          $saltBytesstring=$saltBytesstring.chr($salt[$i]);
      }
      $encrypted_dni = $this->encrypt($dniSocio,$NOMASTICKETS_token,$saltBytesstring); 
    
      /*
      $desencrypted_dni = $this->decrypt($encrypted_dni,$this->NOMASTICKETS_token,$saltBytesstring);
      echo 'clave encriptada: '. $encrypted_dni.'<br>';
      echo 'clave desencriptada: '. $desencrypted_dni;
      die();
      $this->view->data = $fSocio;
      $this->view->encrypted_dni = $encrypted_dni;
    }
    */

    $build = array(
      '#type' => 'markup',
      '#markup' => $this->t('<iframe width="890" height="515" src='. "https://nomastickets.consum.es/app/index.html?pass=". $encrypted_dni. "&language=es&isticketonline=false".'></iframe><p>'.$encrypted_dni.'</p>'),
    ); //añadir variable para el idioma de la url ($idiomaIframe)
    return $build; 
  }

  //Funciones para el cifrado/descifrado de los parámetros:
  private function derived($password, $salt){
    $AESKeyLength = 256/8;
    $AESIVLength = 128/8;
    /*La funcion hash_pbkdf2 no existe en la verision 5.4 de PHP. 
    PRE está mas actualizado, pero en PRO se ha emulado*/
    $pbkdf2 = hash_pbkdf2("SHA1", $password, mb_convert_encoding($salt, 'UTF-8'), 100000, 128, TRUE); //$this->iterations = 100000
    //$pbkdf2 = pbkdf2("SHA1", $password, mb_convert_encoding($salt, 'UTF-8'), 100000, 128, TRUE); //$this->iterations = 100000
    $key = substr($pbkdf2, 0, $AESKeyLength);
    $iv = substr($pbkdf2, $AESKeyLength, $AESIVLength);
    $derived = new stdClass();
    $derived->key = $key;
    $derived->iv = $iv; 
    return $derived;
  }

  function encrypt($message, $password, $salt){
    $cipher = 'AES256'; //$this->cipher
    $derived = $this->derived($password, $salt);
    $enc = openssl_encrypt(mb_convert_encoding($message, 'UTF-8', 'auto'), $cipher, $derived->key, NULL, $derived->iv); //$this->cipher
    
    return $enc;
  }

  /*
  function decrypt($message, $password, $salt) {
      $derived = $this->derived($password, $salt);
      $dec = openssl_decrypt($message, $this->cipher, $derived->key, NULL, $derived->iv);
      return mb_convert_encoding($dec, 'UTF-16', 'auto');
  }

  private function getKeySize(){
      if (preg_match("/([0-9]+)/i", $this->cipher, $matches)) {
          return $matches[1] >> 3;
      }
      return 0;
  }
  */

}
