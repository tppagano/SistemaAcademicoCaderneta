<?php

function convertDate($data) {
	$d=explode("/",$data);
	$data=$d[2]."-".$d[1]."-".$d[0];
	return $data;
}

include '../banco.php';

if(isset($_POST ["cod_semestre"]))
{
	$cod_semestre	= $_POST ["cod_semestre"];
	$data_ini = convertDate($_POST ["data_ini"]);
	$data_fim = convertDate($_POST ["data_fim"]);
	
	
	$nQuery = "insert into feriado (cod_semestre, data_ini, data_fim)  values ($cod_semestre,'$data_ini','$data_fim')";
	noQuery($nQuery);	
	
}


?>
<form method='post' action='cadastrar.php'>
<table>
<tr>
<td>Semestre:</td>
<td>

<?php

$rsSemestre = Select("select * from semestre order by nome");

$num_rows = mysql_num_rows($rsSemestre);
if($num_rows > 0)
{
	echo "<select id='cod_semestre' name='cod_semestre'><option value='0'>Selecione</option>";
	while($row = mysql_fetch_array($rsSemestre))
	{
		echo "<option value='" . $row['cod_semestre'] . "'>" . $row['nome'] . "</option>";
	}
	echo "</select>";
}
?>

</td>
</tr>
<tr>
<td>Data de inicio:</td>
<td><input type="text" name="data_ini" id="data_ini"></td>
</tr>
<tr>
<td>Data de fim:</td>
<td><input type="text" name="data_fim" id="data_fim"></td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="btn"  id="btn" value="OK"></td>
</tr>
</table>