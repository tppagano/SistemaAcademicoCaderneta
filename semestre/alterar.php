<?php
function convertDate($data) {
	$d=explode("/",$data);
	$data=$d[2]."-".$d[1]."-".$d[0];
	return $data;
}

function recebeDate($data) {
	$d=explode("-",$data);
	$data=$d[2]."/".$d[1]."/".$d[0];
	return $data;
}

include '../banco.php';

if(isset($_GET ["cod_semestre"]))
{
	$cod_semestre = $_GET ["cod_semestre"];
	
	if(isset($_POST ["nome"]))
	{
		$nome	= $_POST ["nome"];
		$data_inicio	= convertDate($_POST ["data_inicio"]);
		
		$nQuery = "UPDATE semestre SET nome = '$nome', data_inicio = '$data_inicio' where cod_semestre = $cod_semestre";
				
		noQuery($nQuery);
	}	


	$rsSemestre = Select("select * from semestre where cod_semestre = $cod_semestre LIMIT 1");
	$rowSemestre = mysql_fetch_array($rsSemestre);
	
	$nome = $rowSemestre ["nome"];
	$data_inicio = recebeDate($rowSemestre ["data_inicio"]);
		
}




?>
<form method='post' action='alterar.php?cod_semestre=<?php echo $cod_semestre ?>'>
<table>
<tr>
<td>Nome:</td>
<td><input type="text" name="nome" id="nome" value="<?php echo $nome ?>"></td>
</tr>
<tr>
<td>Data de inicio:</td>
<td><input type="text" name="data_inicio" id="data_inicio" value="<?php echo $data_inicio ?>"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="btn"  id="btn" value="OK"></td>
</tr>
</table>
