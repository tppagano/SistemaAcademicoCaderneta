<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>

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
		horaIniSeg = "";
		horaFimSeg = "";
		if(document.getElementById('valHoraSegIni') != null)
		{
			horaIniSeg = document.getElementById('valHoraSegIni').value;
			horaFimSeg = document.getElementById('valHoraSegFim').value;
		}
		else
		{
			horaIniSeg = "";
			horaFimSeg = "";
		}
		
		campos = campos + "Seg: <input type='text' id='segIni' name='segIni' value='" + horaIniSeg + "'> as <input type='text' id='segFim' name='segFim' value='" + horaFimSeg + "'><br>";
	}
	
	if(option2)
	{
		horaIniTer = "";
		horaFimTer = "";
		if(document.getElementById('valHoraTerIni') != null)
		{
			horaIniTer = document.getElementById('valHoraTerIni').value;
			horaFimTer = document.getElementById('valHoraTerFim').value;
		}
		else
		{
			horaIniTer = "";
			horaFimTer = "";
		}
		
		campos = campos + "Ter: <input type='text' id='terIni' name='terIni' value='" + horaIniTer + "'> as <input type='text' id='terFim' name='terFim' value='" + horaFimTer + "'><br>";
	}
	
	if(option3)
	{
		horaIniQua = "";
		horaFimQua = "";
		if(document.getElementById('valHoraQuaIni') != null)
		{
			horaIniQua = document.getElementById('valHoraQuaIni').value;
			horaFimQua = document.getElementById('valHoraQuaFim').value;
		}
		else
		{
			horaIniQua = "";
			horaFimQua = "";
		}
		
		campos = campos + "Qua: <input type='text' id='quaIni' name='quaIni' value='" + horaIniQua + "'> as <input type='text' id='quaFim' name='quaFim' value='" + horaFimQua + "'><br>";
	}
	
	if(option4)
	{
		horaIniQui = "";
		horaFimQui = "";
		if(document.getElementById('valHoraQuiIni') != null)
		{
			horaIniQui = document.getElementById('valHoraQuiIni').value;
			horaFimQui = document.getElementById('valHoraQuiFim').value;
		}
		else
		{
			horaIniQui = "";
			horaFimQui = "";
		}
		
		campos = campos + "Qui: <input type='text' id='quiIni' name='quiIni' value='" + horaIniQui + "'> as <input type='text' id='quiFim' name='quiFim' value='" + horaFimQui + "'><br>";
	}
	
	if(option5)
	{
		horaIniSex = "";
		horaFimSex = "";
		if(document.getElementById('valHoraSexIni') != null)
		{
			horaIniSex = document.getElementById('valHoraSexIni').value;
			horaFimSex = document.getElementById('valHoraSexFim').value;
		}
		else
		{
			horaIniSex = "";
			horaFimSex = "";
		}
		
		campos = campos + "Sex: <input type='text' id='sexIni' name='sexIni' value='" + horaIniSex + "'> as <input type='text' id='sexFim' name='sexFim' value='" + horaFimSex + "'><br>";
	}
	
	document.getElementById('hora').innerHTML = campos;
}

</script>

<?php

include '../banco.php';

if(isset($_GET ["cod_disciplina"]))
{
	$cod_disciplina = $_GET ["cod_disciplina"];
	
	if(isset($_POST ["nome"]))
	{
		$nome	= $_POST ["nome"];
		$turma	= $_POST ["turma"];
		$id	= $_POST ["id"];
		$semestre	= $_POST ["semestre"];
		$professor	= $_POST ["professor"];
		$limite_faltas = $_POST ["limite_faltas"];
		
		$pratica	= 0;
		if(isset($_POST ["pratica"]))
		{
			$pratica	= 1;
		}
		
		$rsDisciplina = Select("select * from disciplina where cod_disciplina = $cod_disciplina LIMIT 1");
		$rowDisciplina = mysql_fetch_array($rsDisciplina);
		
		$cod = $rowDisciplina['cod_disciplina'];		
		
		$nQuery = "UPDATE disciplina SET nome = '$nome', id = '$id', cod_semestre = $semestre, turma = '$turma', professor = '$professor', pratica = $pratica, limite_faltas = $limite_faltas where cod_disciplina = $cod";
		noQuery($nQuery);
		/*
		$num_rows = mysql_num_rows($rsDisciplina);
		
		if($num_rows > 0)
		{	
			$nQuery = "DELETE FROM sistema_academico.disciplina_dia_hora WHERE cod_disciplina = $cod";
			noQuery($nQuery);			
				
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
		*/
		
	}	
	
	$rsDisciplina = Select("select * from disciplina where cod_disciplina = $cod_disciplina LIMIT 1");
	$rowDisciplina = mysql_fetch_array($rsDisciplina);
	
	$nome	= $rowDisciplina ["nome"];
	$turma	= $rowDisciplina ["turma"];
	$professor	= $rowDisciplina ["professor"];
	$id	= $rowDisciplina ["id"];
	$cod_semestre	= $rowDisciplina ["cod_semestre"];
	$pratica	= $rowDisciplina ["pratica"];
	$limite_faltas	= $rowDisciplina ["limite_faltas"];
}

?>
<form method='post' action='alterar.php?cod_disciplina=<?php echo $cod_disciplina ?>'>
<table>
<tr>
<td>Nome:</td>
<td><input type="text" name="nome" id="nome" value="<?php echo $nome ?>"></td>
</tr>
<tr>
<td>Identificador:</td>
<td><input type="text" name="id" id="id" value="<?php echo $id ?>"></td>
</tr>
<tr>
<td>Turma:</td>
<td><input type="text" name="turma" id="turma" value="<?php echo $turma ?>"></td>
</tr>
<tr>
<td>Limite Faltas:</td>
<td><input type="text" name="limite_faltas" id="limite_faltas" value="<?php echo $limite_faltas ?>"></td>
</tr>
<tr>
<td>Professor:</td>
<td><input type="text" name="professor" id="professor" value="<?php echo $professor ?>"></td>
</tr>
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


$sel = "";
if($pratica == 1)
{
	$sel = "checked";
}


?>


</td>
</tr>
<tr>
<td>Prática:</td>
<td><input type="checkbox" name="pratica" id="pratica" value="1" <?php echo $sel;?>></td>
</tr>

<tr>
<td>Dia:</td>
<td>

<?php

$rsDisciplinaDiaHoraSeg = Select("select * from disciplina_dia_hora where cod_disciplina = $cod_disciplina and cod_dia = 1 LIMIT 1");
$rsDisciplinaDiaHoraTer = Select("select * from disciplina_dia_hora where cod_disciplina = $cod_disciplina and cod_dia = 2 LIMIT 1");
$rsDisciplinaDiaHoraQua = Select("select * from disciplina_dia_hora where cod_disciplina = $cod_disciplina and cod_dia = 3 LIMIT 1");
$rsDisciplinaDiaHoraQui = Select("select * from disciplina_dia_hora where cod_disciplina = $cod_disciplina and cod_dia = 4 LIMIT 1");
$rsDisciplinaDiaHoraSex = Select("select * from disciplina_dia_hora where cod_disciplina = $cod_disciplina and cod_dia = 5 LIMIT 1");

$numRowsSeg = mysql_num_rows($rsDisciplinaDiaHoraSeg);
$numRowsTer = mysql_num_rows($rsDisciplinaDiaHoraTer);
$numRowsQua = mysql_num_rows($rsDisciplinaDiaHoraQua);
$numRowsQui = mysql_num_rows($rsDisciplinaDiaHoraQui);
$numRowsSex = mysql_num_rows($rsDisciplinaDiaHoraSex);

$selSeg = "";
if($numRowsSeg > 0)
{
	$row = mysql_fetch_array($rsDisciplinaDiaHoraSeg);
	$selSeg = "checked";
	echo "<input type='hidden' id='valHoraSegIni' name='valHoraSegIni' value='" . $row["hora_ini"] . "'>";
	echo "<input type='hidden' id='valHoraSegFim' name='valHoraSegFim' value='" . $row["hora_fim"] . "'>";
}

$selTer = "";
if($numRowsTer > 0)
{
	$row = mysql_fetch_array($rsDisciplinaDiaHoraTer);
	$selTer = "checked";	
	echo "<input type='hidden' id='valHoraTerIni' name='valHoraTerIni' value='" . $row["hora_ini"] . "'>";
	echo "<input type='hidden' id='valHoraTerFim' name='valHoraTerFim' value='" . $row["hora_fim"] . "'>";
}

$selQua = "";
if($numRowsQua > 0)
{
	$row = mysql_fetch_array($rsDisciplinaDiaHoraQua);
	$selQua = "checked";	
	echo "<input type='hidden' id='valHoraQuaIni' name='valHoraQuaIni' value='" . $row["hora_ini"] . "'>";
	echo "<input type='hidden' id='valHoraQuaFim' name='valHoraQuaFim' value='" . $row["hora_fim"] . "'>";
}

$selQui = "";
if($numRowsQui > 0)
{
	$row = mysql_fetch_array($rsDisciplinaDiaHoraQui);
	$selQui = "checked";	
	echo "<input type='hidden' id='valHoraQuiIni' name='valHoraQuiIni' value='" . $row["hora_ini"] . "'>";
	echo "<input type='hidden' id='valHoraQuiFim' name='valHoraQuiFim' value='" . $row["hora_fim"] . "'>";
}

$selSex = "";
if($numRowsSex > 0)
{
	$row = mysql_fetch_array($rsDisciplinaDiaHoraSex);
	$selSex = "checked";	
	echo "<input type='hidden' id='valHoraSexIni' name='valHoraSexIni' value='" . $row["hora_ini"] . "'>";
	echo "<input type='hidden' id='valHoraSexFim' name='valHoraSexFim' value='" . $row["hora_fim"] . "'>";
}
?>
<input type="checkbox" name="option1" id="option1" value="1" onclick="adicionaHora();" <?php echo $selSeg ?>> Seg
<input type="checkbox" name="option2" id="option2" value="2" onclick="adicionaHora();" <?php echo $selTer ?>> Ter
<input type="checkbox" name="option3" id="option3" value="3" onclick="adicionaHora();" <?php echo $selQua ?>> Qua
<input type="checkbox" name="option4" id="option4" value="4" onclick="adicionaHora();" <?php echo $selQui ?>> Qui
<input type="checkbox" name="option5" id="option5" value="5" onclick="adicionaHora();" <?php echo $selSex ?> > Sex

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

<script>
adicionaHora();
</script>