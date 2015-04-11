<script>

function adicionaHora()
{
	option1 = document.getElementById('option1').checked;
	option2 = document.getElementById('option2').checked;
	option3 = document.getElementById('option3').checked;
	option4 = document.getElementById('option4').checked;
	option5 = document.getElementById('option5').checked;
	
	campos = "";
	
	if(option1)
	{
		campos = campos + "Seg: <input type='text' id='segIni' name='segIni'> as <input type='text' id='segFim' name='segFim'><br>";
	}
	
	if(option2)
	{
		campos = campos + "Ter: <input type='text' id='terIni' name='terIni'> as <input type='text' id='terFim' name='terFim'><br>";
	}
	
	if(option3)
	{
		campos = campos + "Qua: <input type='text' id='quaIni' name='quaIni'> as <input type='text' id='quaFim' name='quaFim'><br>";
	}
	
	if(option4)
	{
		campos = campos + "Qui: <input type='text' id='quiIni' name='quiIni'> as <input type='text' id='quiFim' name='quiFim'><br>";
	}
	
	if(option5)
	{
		campos = campos + "Sex: <input type='text' id='sexIni' name='sexIni'> as <input type='text' id='sexFim' name='sexFim'><br>";
	}
	
	document.getElementById('hora').innerHTML = campos;
}

</script>

<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>

<?php

set_time_limit(0);
ignore_user_abort(1);

mb_internal_encoding( 'UTF-8' );

header("Content-Type: text/html; charset=utf-8");

include '../banco.php';

if(isset($_POST ["nome"]))
{
	$nome	= $_POST ["nome"];
	$id	= $_POST ["id"];
	$semestre	= $_POST ["semestre"];
	$turma	= $_POST ["turma"];
	$professor	= $_POST ["professor"];
	
	$pratica = 0;
	if(isset($_POST ["pratica"]))
	{
		$pratica	= $_POST ["pratica"];
	}
	
	$nQuery = "insert into disciplina (nome, id, cod_semestre, turma, professor, pratica)  values ('$nome','$id',$semestre,'$turma','$professor', $pratica)";		
	
	noQuery($nQuery);
	
	$nQuery = "select * from disciplina where nome = '$nome' and id = '$id' and cod_semestre = $semestre  and turma = '$turma' LIMIT 1";
	
	//$nQuery = iconv("ISO-8859-1", "UTF-8//IGNORE", $nQuery);
	
	$rsDisciplina = Select($nQuery);
	$rowDisciplina = mysql_fetch_array($rsDisciplina);
	
	$cod = $rowDisciplina['cod_disciplina'];
	
	$num_rows = mysql_num_rows($rsDisciplina);
	
	if($num_rows > 0)
	{	
		
		if(isset($_POST ["segIni"]))
		{					
			$hini = $_POST ["segIni"];	
			$hfim = $_POST ["segFim"];	
			$nQuery = "INSERT INTO sistema_academico.disciplina_dia_hora (cod_disciplina, cod_dia, hora_ini, hora_fim) VALUES ($cod, 1, '$hini', '$hfim')";
			noQuery($nQuery);			
		}
		
		if(isset($_POST ["terIni"]))
		{			
		
			$hini = $_POST ["terIni"];	
			$hfim = $_POST ["terFim"];	
			$nQuery = "INSERT INTO sistema_academico.disciplina_dia_hora (cod_disciplina, cod_dia, hora_ini, hora_fim) VALUES ($cod, 2, '$hini', '$hfim')";
			noQuery($nQuery);			
		}
		
		if(isset($_POST ["quaIni"]))
		{			
		
			$hini = $_POST ["quaIni"];	
			$hfim = $_POST ["quaFim"];	
			$nQuery = "INSERT INTO sistema_academico.disciplina_dia_hora (cod_disciplina, cod_dia, hora_ini, hora_fim) VALUES ($cod, 3, '$hini', '$hfim')";
			noQuery($nQuery);			
		}
		
		if(isset($_POST ["quiIni"]))
		{			
		
			$hini = $_POST ["quiIni"];	
			$hfim = $_POST ["quiFim"];	
			$nQuery = "INSERT INTO sistema_academico.disciplina_dia_hora (cod_disciplina, cod_dia, hora_ini, hora_fim) VALUES ($cod, 4, '$hini', '$hfim')";
			noQuery($nQuery);			
		}
		
		if(isset($_POST ["sexIni"]))
		{			
		
			$hini = $_POST ["sexIni"];	
			$hfim = $_POST ["sexFim"];	
			$nQuery = "INSERT INTO sistema_academico.disciplina_dia_hora (cod_disciplina, cod_dia, hora_ini, hora_fim) VALUES ($cod, 5, '$hini', '$hfim')";
			noQuery($nQuery);			
		}	
		
		$proc = Select("call obtemDiasLetivosdisciplinas(" . $cod . ");");			
		
		
	}	
	
	
}


?>
<form method='post' action='cadastrar.php'>
<table>
<tr>
<td>Nome:</td>
<td><input type="text" name="nome" id="nome"></td>
</tr>
<tr>
<td>Identificador:</td>
<td><input type="text" name="id" id="id"></td>
</tr>
<tr>
<td>Turma:</td>
<td><input type="text" name="turma" id="turma"></td>
</tr>
<tr>
<td>Professor:</td>
<td><input type="text" name="professor" id="professor"></td>
</tr>
<tr>
<td>Semestre:</td>
<td>

<?php

$rsSemestre = Select("select * from semestre order by nome");

$num_rows = mysql_num_rows($rsSemestre);
if($num_rows > 0)
{
	echo "<select id='semestre' name='semestre'><option value='0'>Selecione</option>";
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
<td>Prática:</td>
<td><input type="checkbox" name="pratica" id="pratica" value="1"></td>
</tr>
<tr>
<td>Dia:</td>
<td>
<input type="checkbox" name="option1" id="option1" value="1" onclick="adicionaHora();"> Seg
<input type="checkbox" name="option2" id="option2" value="2" onclick="adicionaHora();"> Ter
<input type="checkbox" name="option3" id="option3" value="3" onclick="adicionaHora();"> Qua
<input type="checkbox" name="option4" id="option4" value="4" onclick="adicionaHora();"> Qui
<input type="checkbox" name="option5" id="option5" value="5" onclick="adicionaHora();"> Sex

</td>
</tr>
<tr>
<td>Hora:</td>
<td id="hora">



</td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="btn"  id="btn" value="OK"></td>
</tr>
</table>