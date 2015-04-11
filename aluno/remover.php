<?php

include '../banco.php';

if(isset($_GET ["cod_disciplina"]))
{
	$cod_disciplina = $_GET ["cod_disciplina"];
	
	$nQuery = "delete from disciplina where cod_disciplina = $cod_disciplina";
	noQuery($nQuery);
	
	$nQuery = "delete from dias_letivos where cod_disciplina = $cod_disciplina";
	noQuery($nQuery);
	
	$nQuery = "delete from disciplina_dia_hora where cod_disciplina = $cod_disciplina";
	noQuery($nQuery);	
	
	echo "<script>alert('Disciplina removida com sucesso!!!');window.location = 'grid.php'</script>";
	
}



?>