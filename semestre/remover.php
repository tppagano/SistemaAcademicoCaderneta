<?php

include '../banco.php';

if(isset($_GET ["cod_semestre"]))
{
	$cod_semestre = $_GET ["cod_semestre"];
	
	$nQuery = "delete from semestre where cod_semestre = $cod_semestre";
	noQuery($nQuery);
	
	
	echo "<script>alert('Semestre removida com sucesso!!!');window.location = 'grid.php'</script>";
	
}



?>