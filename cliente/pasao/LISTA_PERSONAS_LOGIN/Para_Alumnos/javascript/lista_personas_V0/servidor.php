<?php



header("Access-Control-Allow-Origin: *"); // allow request from all origin
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin, X-Requested-With, Content-Type, Accept, Authorization");

header('Content-Type: application/json');  //  Todo se devolverá en formato JSON.



//  Para poder hacer uso de la clase jwt:
require_once 'jwt.php';
$jwt = new jwt();
$claveSecreta = 'Ciao bella ciao';

//  Vamos a comprobar el JWT:
//  Obtenemos el JWT que nos ha debido de pasar el cliente (en la cabecera):
$token = $jwt->getBearerToken();
if (($token == null) || ($token == "")) {
	//  Devuelve información indicando que la sesión NO está creada:
	print '{"sesion":"NO"}';
	//  Finaliza la ejecución:
  return;
}

$tokenValido = $jwt->validarJWT($token, $claveSecreta);
// var_dump($tokenValido);

if (!$tokenValido->valido) {
	//  Devuelve información indicando que la sesión NO está creada:
	print '{"sesion":"NO"}';
	//  Finaliza la ejecución:
  return;
}


/*
	****************************************************************************

  ¡¡¡¡¡ SI LLEGAMOS AQUI ES PORQUE EL TOKEN ES VALIDO Y NO HA EXPIRADO !!!!

	****************************************************************************

	(El resto ya es similar a lo visto anteriormente)
*/



//  Definimos la variable global $idUsuairo:
$idUsuairo = $tokenValido->datos->id;


//  Instanciamos un objeto del tipo BD_Servidor:
$bd = new BD_Servidor();


//  Con esta línea recogemos los datos (en formato JSON), enviados por el cliente:
$datos = file_get_contents('php://input');  //  $datos es un string, y no un objeto php
//  Lo convertimos a un objeto php:
$objeto=json_decode($datos);

// print "<br>Datos: " . $datos;
// return;


switch ($objeto->servicio) {
	
	case "listar":
			print json_encode($bd->listadoPersonas());
			break;		
			
	case "insertar":
			$bd->insertarPersona($objeto);
			print json_encode($bd->listadoPersonas());
			break;
			
	case "borrar":
			$bd->borrarPersona($objeto->id);
			print json_encode($bd->listadoPersonas());
			break;
			
	default:
			print '{"servicio": "NO"}';
}



class BD_Servidor {

	private $pdo;
	private $claveSecreta = 'Ciao bella ciao';

	public function __CONSTRUCT() {
		try {
			$opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
			$this->pdo = new PDO('mysql:host=localhost;dbname=ajax', 'root', '', $opciones);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                
		} catch(Exception $e) {
				die($e->getMessage());
		}
	}

	
	public function listadoPersonas() {
		try {
			$sc = "Select * From personas Order By ID";
			$stm = $this->pdo->prepare($sc);
			$stm->execute();
			return ($stm->fetchAll(PDO::FETCH_ASSOC));
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	
	public function insertarPersona($datos) {
		try {
			$sc = "Insert into personas(DNI, NOMBRE, APELLIDOS) Values(?, ?, ?)";
			$stm = $this->pdo->prepare($sc);
			$stm->execute(array(
					$datos->dni,
					$datos->nombre,
					$datos->apellidos
			));
			return true;
		} catch (Exception $e) {
			die($e->getMessage());
			return false;
		}
	}

	
	public function borrarPersona($id) {
		try {
			$sc = "Delete From personas Where ID = ?";
			$stm = $this->pdo->prepare($sc);
			$stm->execute(array($datos->id));
			return true;
		} catch (Exception $e) {
			die($e->getMessage());
			return false;
		}
	}
	
}  //  class BD_login

?>




