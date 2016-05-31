<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->post('/Login', 'logar');


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
// $app->get('/Fotos', 'getFotos');
$app->get('/Fotos/:id', 'getFotos');


$app->post('/fileUpload/:id', 'uploadFotos');
$app->put('/Fotos', 'selecionarFoto');

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


function logar() {
	$request = Slim::getInstance()->request();
	$usuario = json_decode($request->getBody());
	$sql = "SELECT u.id,u.nome,u.tipo,e.id as evento FROM usuarios u LEFT JOIN eventos e ON u.id = e.usuario WHERE login = :nome AND senha = :senha AND status = 1";

	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("nome", $usuario->nome);
		$stmt->bindParam("senha", $usuario->senha);  
		$stmt->execute();
		$usuario = $stmt->fetch(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($usuario);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
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
	$usuario = '{"login" : "'. $evento->login . '","nome" : "'. $evento->nome. '","senha" : "'. $evento->senha. '","status" : "1","tipo": "evento"}';
	$usuario = json_decode($usuario);
	$usuarioLogado = json_decode($_COOKIE['usuario']);
	print_r($usuarioLogado->id);
	$sql = "INSERT INTO eventos (nome,  descricao,fotografo,usuario) VALUES (:nome, :descricao,:fotografo, :usuario)";
	$sql2 = "INSERT INTO usuarios (nome, login, senha,status,tipo) VALUES (:nome, :login, :senha,:status,:tipo)";
	try {
		$db = DB_Connection();
		$stmt2 = $db->prepare($sql2);  
		$stmt2->bindParam("nome", $usuario->nome);
		$stmt2->bindParam("login", $usuario->login);
		$stmt2->bindParam("senha", $usuario->senha);
		$stmt2->bindParam("tipo", $usuario->tipo);
		$stmt2->bindParam("status", $usuario->status);
		$stmt2->execute();
		$usuario->id = $db->lastInsertId();

		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $evento->nome);
		$stmt->bindParam("descricao", $evento->descricao);
		$stmt->bindParam("fotografo", $usuarioLogado->id);
		$stmt->bindParam("usuario", $usuario->id);
		$stmt->execute();
		$evento->id = $db->lastInsertId();
		$db = null;
		echo json_encode($evento); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getEvento($id) {
	$sql = "SELECT e.*,u.login,u.senha FROM eventos e INNER JOIN usuarios u ON e.usuario = u.id WHERE e.id='".$id."'";
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
	$usuario = '{"login" : "'. $evento->login . '","nome" : "'. $evento->nome. '","senha" : "'. $evento->senha. '","status" : "1","tipo": "evento"}';
	$usuario = json_decode($usuario);
	$sql = "UPDATE eventos SET nome=:nome, descricao=:descricao WHERE id=:id";
	$sql2 = "UPDATE usuarios SET nome=:nome, login=:login, senha=:senha WHERE id=:id";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $evento->nome);
		$stmt->bindParam("descricao", $evento->descricao);
		$stmt->bindParam("id", $id);
		$stmt->execute();

		$stmt2 = $db->prepare($sql2);  
		$stmt2->bindParam("nome", $usuario->nome);
		$stmt2->bindParam("login", $usuario->login);
		$stmt2->bindParam("senha", $usuario->senha);
		$stmt2->bindParam("id", $evento->usuario);
		$stmt2->execute();

		$db = null;
		echo json_encode($evento); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function deleteEvento($id) {
	$sql = "SELECT usuario FROM eventos where id='".$id."'";
	$sql2 = "DELETE FROM eventos WHERE id=".$id;
	$sql3 = "DELETE FROM usuarios WHERE id= :id";

	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$usuarioEvento = $stmt->fetch(PDO::FETCH_OBJ);

		$stmt2 = $db->query($sql2);  
		$list = $stmt->fetch(PDO::FETCH_OBJ);

		$stmt3 = $db->prepare($sql3);  
		$stmt3->bindParam("id", $usuarioEvento->usuario);
		$stmt3->execute();
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getFotos($id) {
	$sql = "SELECT * FROM fotos WHERE evento_id ='".$id."'";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$fotos = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($fotos);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function uploadFotos($id){
	$pastaEvento = "/var/www/BancoDeImagem/eventosFotos/". $id;
	if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
		if (!is_dir($pastaEvento)) {
	        mkdir($pastaEvento);
	    }
	    $file = $pastaEvento . "/tmp_" . clean_name($_FILES["file"]['name']);

	    move_uploaded_file($_FILES["file"]["tmp_name"], $file);


	    // gera_thumb_max($file, $pastaEvento "/wall_" . clean_name($_FILES["file"]['name']), 800, 600);
	    // gera_thumb_max($file, $pastaEvento. "/wall_thumb_" . clean_name($_FILES["file"]['name']), 150, 150);

	    // unlink($file);

	    $arquivo =  "http://banco.imagens/eventosFotos/". $id ."/tmp_" . clean_name($_FILES["file"]['name']);

	    $sql = "INSERT INTO fotos (nome,url,evento_id) values ( :nome, :url, :evento_id)";
	    try {
	    	$db = DB_Connection();
	    	$stmt = $db->prepare($sql);  
	    	$stmt->bindParam("nome", $_FILES["file"]['name']);
	    	$stmt->bindParam("url", $arquivo);
	    	$stmt->bindParam("evento_id", $id);
	    	$stmt->execute();
	    	$db = null;
	    } catch(PDOException $e) {
	    	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	    }
	}
}

function selecionarFoto() {
	$request = Slim::getInstance()->request();
	$foto = json_decode($request->getBody());
	if(isset($foto->selecionada)){
		$foto->selecionada =  ($foto->selecionada) ? 0 : 1;
	}else{
		$foto->selecionada = 1;
	}

	$sql = "UPDATE fotos SET selecionada=:selecionada WHERE id=:id";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("selecionada", $foto->selecionada);
		$stmt->bindParam("id", $foto->id);
		$stmt->execute();
		$db = null;
		echo json_encode($foto); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

//funções para ajudar


function clean_name($realname) {
    $bad_chars = array("'", "\\", ' ', '/', ':', '*', '?', '"', '<', '>', '|');
    $realname = rawurlencode(str_replace($bad_chars, '_', strtolower($realname)));
    $realname = preg_replace("/%(\w{2})/", '_', $realname);

    while (strpos($realname, '__') !== false) {
        $realname = str_replace("__", "_", $realname);
    }

    return $realname;
}


function gera_thumb_max($dir_imagem, $dest, $max_width, $max_height) {

    // se a imagem for menor que as dimensões a nova imagem não é criada
    // criamos aqui
    copy($dir_imagem, $dest);

    $filename = urldecode($dir_imagem);

    $ext = strtolower(substr(strrchr($filename, '.'), 1));

    list($width, $height) = getimagesize($filename);

    if ($width > $max_width) {
        $new_height = ($height * $max_width) / $width;
        gera_thumb($dir_imagem, $dest, $max_width, round($new_height));
        gera_thumb_max($dest, $dest, $max_width, $max_height);
    } else if ($height > $max_height) {
        $new_width = ($width * $max_height) / $height;
        gera_thumb($dir_imagem, $dest, round($new_width), $max_height);
    }
}



?>