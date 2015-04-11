<?php

session_start(); 
// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = 'uploads/';

// Tamanho máximo do arquivo (em Bytes)

$_UP['tamanho'] = 1024 * 1024 * 20; // tamanho do arquivo

// Array com as extensões permitidas

//$_UP['extensoes'] = array('jpg', 'png', 'gif'); 

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)

$_UP['renomeia'] = false;
 

// Array com os tipos de erros de upload do PHP

$_UP['erros'][0] = 'Não houve erro';

$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';

$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';

$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';

$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

 
// Primeiro verifica se deve trocar o nome do arquivo
if ($_UP['renomeia'] == true) 
{
	// Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
	$nome_final = time().'.jpg';
} 
else 
{
	// Mantém o nome original do arquivo
	$nome_final = $_FILES['arquivo']['name'];
}
 

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro

if ($_FILES['arquivo']['error'] != 0) 
{
	die("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['arquivo']['error']]);
	exit; // Para a execução do script
}


 

// Depois verifica se é possível mover o arquivo para a pasta escolhida

if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) 
{
    $_SESSION['arquivo']=$nome_final;
	echo $_SESSION['arquivo'];
	
	// Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
	echo "<script>alert('Upload efetuado com sucesso!');window.location='upload.php';</script>";
	//echo '<br /><a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';
} 
else 
{
	// Não foi possível fazer o upload, provavelmente a pasta está incorreta
	echo "Não foi possível enviar o arquivo, tente novamente";
}

 



 

?>