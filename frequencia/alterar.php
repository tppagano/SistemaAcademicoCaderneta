<?php


include '../banco.php';

if(isset($_GET ["cod_frequencia"]))
{

	$cod = $_GET ["cod_frequencia"];

	$nquery = "UPDATE frequencia SET presenca = CASE WHEN presenca = 1 THEN 0 ELSE 1 END WHERE cod_frequencia = $cod";
		

	noQuery($nquery);		

		
}




?>

<script>window.location = 'grid.php?cod_frequencia= <?php echo $cod;?>'; </script>