<?php


include '../banco.php';
header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding( 'UTF-8' );

if(isset($_POST ["cod_registro_academico"]))
{
	$cod_registro_academico = $_POST ["cod_registro_academico"];
	$atividade = $_POST ["atividade"];
	$id = $_POST ["id"];
	
	
	
	$nQuery = "update registro_academico set  atividade = '$atividade' where cod_registro_academico = $cod_registro_academico";
	//$nQuery = iconv("ISO-8859-1", "UTF-8//IGNORE", $nQuery);
	
	noQuery($nQuery);	

}




?>

<script>window.location='grid.php?cod_disciplina=<?php echo $id;?>'</script>