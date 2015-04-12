<?php

$host= '127.0.0.1';
$bd= 'root';
$senhabd= '';
$database = "sistema_academico";
	
function conectaBancoSelect ()
{



    global $host, $bd, $senhabd, $database;
	
	$conexao = mysql_connect($host,$bd, $senhabd);
	
	mysql_select_db($database, $conexao);
	
	mysql_query("SET NAMES 'utf8';");	
	mysql_query("SET character_set_connection=utf8");
	mysql_query("SET character_set_client=utf8");
	mysql_query("SET character_set_results=utf8");
	mysql_query("SET character_set_database=utf8");
	mysql_query("SET character_set_server=utf8");
	mysql_query( "SET CHARACTER SET utf8");
	
	//mysql_set_charset('utf8', $conexao);
	//$re = mysql_query("show variables like '%character_set%';");
	//while($r = mysql_fetch_assoc($re))
	//{
	//	var_dump($r); echo "<br />";	
	//}
	//exit();
	
	return $conexao;
}


function Select ($query)
{
    global $host, $bd, $senhabd, $database;
	
	$conexao = conectaBancoSelect($host,$bd, $senhabd, $database);
	$result = mysql_query($query, $conexao) or die (mysql_error());
	mysql_close($conexao);
	return $result;
}

function noQuery($nQuery)
{
	global $host, $bd, $senhabd, $database;
	$conexao = conectaBancoSelect($host,$bd, $senhabd, $database);
	mysql_query($nQuery,$conexao);	
	mysql_close($conexao);
}
?>
