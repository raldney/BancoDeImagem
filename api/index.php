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
$app->get('/EnviarFotos/:id', 'enviarFotos');


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

	$sql = "INSERT INTO usuarios (nome, login, senha, tipo, status, email) VALUES (:nome, :login, :senha, 'fotografo', 1, :email)";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $usuario->nome);
		$stmt->bindParam("login", $usuario->login);
		$stmt->bindParam("senha", $usuario->senha);
		$stmt->bindParam("email", $usuario->email);
		// $stmt->bindParam("tipo", "fotografo");
		// $stmt->bindParam("status", 1);
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

	$sql = "UPDATE usuarios SET nome=:nome,login=:login,senha=:senha, email=:email WHERE id=:id";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("nome", $usuario->nome);
		$stmt->bindParam("login", $usuario->login);
		$stmt->bindParam("senha", $usuario->senha);
		$stmt->bindParam("email", $usuario->email);
		// $stmt->bindParam("tipo", "fotografo");
		// $stmt->bindParam("status", "1");
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

function enviarFotos($id) {
	$sql = "SELECT * FROM fotos WHERE evento_id ='".$id."' and selecionada = 1";
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


function enviarEmail($conteudo,$titulo,$email){
	$mail = new PHPMailer();
	// Define os dados do servidor e tipo de conexão
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	// $mail->IsSMTP(); // Define que a mensagem será SMTP
	// $mail->Host = "smtp.dominio.net"; // Endereço do servidor SMTP
	//$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
	//$mail->Username = 'seumail@dominio.net'; // Usuário do servidor SMTP
	//$mail->Password = 'senha'; // Senha do servidor SMTP
	// Define o remetente
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->From = "seumail@dominio.net"; // Seu e-mail
	$mail->FromName = "BancoDeImagem"; // Seu nome
	// Define os destinatário(s)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->AddAddress('fulano@dominio.com.br', 'Fulano da Silva');
	// $mail->AddAddress('ciclano@site.net');
	//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
	//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
	// Define os dados técnicos da Mensagem
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
	//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
	// Define a mensagem (Texto e Assunto)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->Subject  = "Mensagem Teste"; // Assunto da mensagem
	$mail->Body = "Este é o corpo da mensagem de teste, em <b>HTML</b>!  :)";
	$mail->AltBody = "Este é o corpo da mensagem de teste, em Texto Plano! \r\n :)";
	// Define os anexos (opcional)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
	// Envia o e-mail
	$enviado = $mail->Send();
	// Limpa os destinatários e os anexos
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	// Exibe uma mensagem de resultado
	if ($enviado) {
	  echo "E-mail enviado com sucesso!";
	} else {
	  echo "Não foi possível enviar o e-mail.";
	  echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
	}

}




?>