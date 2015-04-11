<?php

function convertDate($data) {
	$d=explode("/",$data);
	$data=$d[2]."-".$d[1]."-".$d[0];
	return $data;
}

include '../banco.php';

if(isset($_POST ["nome"]))
{
	$nome	= $_POST ["nome"];
	$data_inicio = convertDate($_POST ["data_inicio"]);
	
	
	$nQuery = "insert into semestre (nome, data_inicio)  values ('$nome','$data_inicio')";
	noQuery($nQuery);	
	
}


?>
<form method='post' action='cadastrar.php'>
<table>
<tr>
<td>Nome:</td>
<td><input type="text" name="nome" id="nome"></td>
</tr>
<tr>
<td>Data de inicio:</td>
<td><input type="text" name="data_inicio" id="data_inicio"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="btn"  id="btn" value="OK"></td>
</tr>
</table>