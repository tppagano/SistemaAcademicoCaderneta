<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
<script>

function submit()
{
	document.form1.submit();
}

function cadastraAtividade(cod)
{
	
	campo1 = 'atividade' + cod;
	campo2 = 'cod' + cod;
	
	
	document.alterar.atividade.value = document.getElementById(campo1).value;
	document.alterar.cod_registro_academico.value = document.getElementById(campo2).value;
	document.alterar.id.value = document.getElementById('id_disciplina').value;
	
	
	document.alterar.submit();
}

</script>

<?php
header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding( 'UTF-8' );

include '../banco.php';

$id = "";
if(isset($_POST ["id_disciplina"]))
{
	$id = $_POST ["id_disciplina"];
}

if(isset($_GET ["cod_disciplina"]))
{
	$id = $_GET ["cod_disciplina"];
}


?>
<form method='post' action='grid.php' id="form1">
<table>
<tr>
<td>Disciplina:</td>
<td>

<?php

$rsDisciplina = Select("select distinct id_disciplina from registro_academico order by id_disciplina");
$num_rows = mysql_num_rows($rsDisciplina);
if($num_rows > 0)
{
	echo "<select id='id_disciplina' name='id_disciplina' onchange='submit();'><option value='0'>Selecione</option>";
	while($row = mysql_fetch_array($rsDisciplina))
	{
		$sel = "";
		if($id == $row['id_disciplina'])
		{
			$sel = "selected";
		}
		
		echo "<option value='" . $row['id_disciplina'] . "' " . $sel . ">" . $row['id_disciplina'] . "</option>";
	}
	echo "</select>";
}

?>


</td>
</tr>
</table>
</form>

<form method='post' action='alterar.php' id='alterar' name='alterar'>
	<input type="hidden" id="cod_registro_academico" name="cod_registro_academico">
	<input type="hidden" id="atividade" name="atividade">
	<input type="hidden" id="id" name="id">
</form>

<?php

$rs = Select("select * from registro_academico where id_disciplina = '$id' order by id_disciplina");

echo "<input type='hidden' id='id_disciplina' name='id_disciplina' value='" . $id . "'>";

echo "<table>";

echo "<tr><td>Aula</td><td>Atividade</td></tr>";

$num_rows = mysql_num_rows($rs);
$cont = 0;
if($num_rows > 0)
{
	while($row = mysql_fetch_array($rs))
	{
		$cont++;
		echo "<tr><td>" . $cont . "</td><td><input type='hidden' id='cod" .$cont. "' value='" . $row['cod_registro_academico'] . "'><textarea id='atividade" . $cont . "' onblur='cadastraAtividade(". $cont . ")'  rows='4' cols='50'>". $row['atividade'] ."</textarea></ td></tr>";
		
	}
}
else
{
	echo "<tr><td colspan='5'>Sem registros</td></tr>";
}
echo "</table>";

?>

