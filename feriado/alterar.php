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

if(isset($_GET ["cod_feriado"]))
{
	$cod_feriado = $_GET ["cod_feriado"];
	
	if(isset($_POST ["data_ini"]))
	{
		$data_ini	= convertDate($_POST ["data_ini"]);
		$data_fim	= convertDate($_POST ["data_fim"]);
		$cod_semestre	= $_POST ["semestre"];
		
		$nQuery = "UPDATE feriado SET cod_semestre = $cod_semestre, data_ini = '$data_ini', data_fim = '$data_fim' where cod_feriado = $cod_feriado";
				
		noQuery($nQuery);
	}	


	$rsFeriado = Select("select * from feriado where cod_feriado = $cod_feriado LIMIT 1");
	$rowFeriado = mysql_fetch_array($rsFeriado);
	
	$cod_semestre = $rowFeriado ["cod_semestre"];
	$data_ini = recebeDate($rowFeriado ["data_ini"]);
	$data_fim = recebeDate($rowFeriado ["data_fim"]);
		
}




?>
<form method='post' action='alterar.php?cod_feriado=<?php echo $cod_feriado ?>'>
<table>
<tr>
<td>Semestre:</td>
<td>

<?php

$rsSemestre = Select("select * from semestre order by nome");

$num_rows = mysql_num_rows($rsSemestre);
if($num_rows > 0)
{
    $sel = "";
	echo "<select id='semestre' name='semestre'><option value='0'>Selecione</option>";
	while($row = mysql_fetch_array($rsSemestre))
	{
		if($cod_semestre == $row['cod_semestre'])
		{
			$sel = "selected";
		}
		else
		{
			$sel = "";
		}
		echo "<option value='" . $row['cod_semestre'] . "' " . $sel . ">" . $row['nome'] . "</option>";
	}
	echo "</select>";
}



?>


</td>
</tr>

<tr>
<td>Data de inicio:</td>
<td><input type="text" name="data_ini" id="data_ini" value="<?php echo $data_ini ?>"></td>
</tr>
<tr>
<td>Data de fim:</td>
<td><input type="text" name="data_fim" id="data_fim" value="<?php echo $data_fim ?>"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="btn"  id="btn" value="OK"></td>
</tr>
</table>
