<?php

$host = $_REQUEST['host'];
$user = $_REQUEST['user'];
$password = $_REQUEST['password'];
$database = $_REQUEST['database'];
$link = @mysql_connect($host, $user, $password);
if (!$link) {
    $msg = 'Não foi possível conectar: ' . @mysql_error();
}else{
	$db_selected = @mysql_select_db($database, $link);
	if (!$db_selected) {
		$msg = 'Não foi possível selecionar o banco de dados '.$database.' : ' . @mysql_error();
	}
}
if(!isset($msg)){
	$content = '<?php
		
	$conn = @mysql_connect(\''.$host.'\',\''.$user.'\',\''.$password.'\');
	if (!$conn) {
		die(\'Não foi possível Conectar: \' . mysql_error());
	}
	mysql_select_db(\''.$database.'\', $conn);
	
	?>';
	
	$fp = fopen(__DIR__ . "/conn.php","wb");
	fwrite($fp,$content);
	fclose($fp);
	
	if(file_exists('conn.php')){
		unlink('js/jquery.install.js');
		unlink('index.html');
		rename('index_pos.html', 'index.html');
		unlink('install.php');
		$result = true;
	}else{
		$result = false;
		$msg = 'Não foi possível criar e salvar o arquivo de configurações, verifique as permissões de escrita no servidor';
	}
}else{
	$result = false;
}
if ($result){
	echo json_encode(array('success'=>true, 'msg'=>'Os arquivos de instalação foram removidos, o script está pronto para uso. Favor Atualizar a Página'));
} else {
	echo json_encode(array('msg'=>$msg));
}
?>