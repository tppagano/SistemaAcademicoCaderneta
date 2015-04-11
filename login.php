<?php

include 'banco.php';

$usuario	= $_POST ["usuario"];
$senha	= $_POST ["senha"];

$rs = Select("select * from usuario where usuario = '$usuario' and senha = '$senha' LIMIT 1");

$row = mysql_fetch_array($rs);

$num_rows = mysql_num_rows($rs);

if($num_rows != 0)
{
		echo "Login efetuado com sucesso!!!";
		echo "<script>window.location = 'inicio.php';</script>";

		
}
else
{
		echo "Não foi possível fazer o login!!!";
		
		
}


?>

