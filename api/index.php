<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/Usuarios', 'getUsuarios');
$app->get('/Usuarios/:id', 'getUsuario');
$app->post('/NovoUsuario', 'addUsuario');
$app->put('/Usuarios/:id', 'updateUsuario');
$app->delete('/Usuarios/:id', 'deleteUsuario');

/*EVENTOS*/

$app->get('/Eventos', 'getEventos');
$app->get('/Eventos/:id', 'getEvento');
$app->post('/NovoEvento', 'addEvento');
$app->put('/Eventos/:id', 'updateEvento');
$app->delete('/Eventos/:id', 'deleteEvento');

/*FOTOS*/
$app->get('/Fotos', 'getFotos');
$app->get('/Fotos/:id', 'getFoto');
$app->post('/NovaFoto', 'addFoto');
$app->put('/Fotos/:id', 'updateFoto');
$app->delete('/Fotos/:id', 'deleteFoto');

$app->run();

function DB_Connection() {	
	$dbhost = "127.0.0.1";
	$dbuser = "root";
	$dbpass = "123";
	$dbname = "BancoDeImagens";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
function getUsuarios() {
	$sql = "SELECT * FROM vw_fotografosEventosFotos";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



function addUsuario() {
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());

	$sql = "INSERT INTO usuarios (nome, login, senha, tipo, status) VALUES (:nome, :login, :senha, :tipo, :status)";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $usuario->nome);
		$stmt->bindParam("login", $usuario->login);
		$stmt->bindParam("senha", $usuario->senha);
		$stmt->bindParam("tipo", $usuario->tipo);
		$stmt->bindParam("status", $usuario->status);
		$stmt->execute();
		$usuario->id = $db->lastInsertId();
		$db = null;
		echo json_encode($usuario); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getUsuario($id) {
	$sql = "SELECT * FROM usuarios WHERE id=".$id." ORDER BY id";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function updateUsuario($id) {
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());

	$sql = "UPDATE usuarios SET nome=:nome,login=:login,senha=:senha, tipo=:tipo, status=:status WHERE id=:id";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $usuario->nome);
		$stmt->bindParam("login", $usuario->login);
		$stmt->bindParam("senha", $usuario->senha);
		$stmt->bindParam("tipo", $usuario->tipo);
		$stmt->bindParam("status", $usuario->status);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($usuario); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function deleteUsuario($id) {
	$sql = "DELETE FROM usuarios WHERE id=".$id;
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


/* CRUD Eventos */

function getEventos() {
	$sql = "SELECT * FROM eventos";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



function addEvento() {
	$request = Slim::getInstance()->request();
	$evento = json_decode($request->getBody());

	$sql = "INSERT INTO eventos (nome, login, senha, tipo, descricao) VALUES (:nome, :login, :senha, :tipo, :descricao)";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $evento->nome);
		$stmt->bindParam("login", $evento->login);
		$stmt->bindParam("senha", $evento->senha);
		$stmt->bindParam("tipo", $evento->tipo);
		$stmt->bindParam("descricao", $evento->descricao);
		$stmt->execute();
		$evento->id = $db->lastInsertId();
		$db = null;
		echo json_encode($evento); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getEvento($id) {
	$sql = "SELECT * FROM eventos WHERE id=".$id." ORDER BY id";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function updateEvento($id) {
	$request = Slim::getInstance()->request();
	$evento = json_decode($request->getBody());

	$sql = "UPDATE eventos SET nome=:nome,login=:login,senha=:senha, tipo=:tipo, descricao=:descricao WHERE id=:id";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $evento->nome);
		$stmt->bindParam("login", $evento->login);
		$stmt->bindParam("senha", $evento->senha);
		$stmt->bindParam("tipo", $evento->tipo);
		$stmt->bindParam("descricao", $evento->descricao);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($evento); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function deleteEvento($id) {
	$sql = "DELETE FROM eventos WHERE id=".$id;
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}







?>