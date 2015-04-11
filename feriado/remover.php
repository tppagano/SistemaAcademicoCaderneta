<?php

include '../banco.php';

if(isset($_GET ["cod_feriado"]))
{
	$cod_feriado = $_GET ["cod_feriado"];
	
	$nQuery = "delete from feriado where cod_feriado = $cod_feriado";
	noQuery($nQuery);
	
	
	echo "<script>alert('Feriado removido com sucesso!!!');window.location = 'grid.php'</script>";
	
}



?>