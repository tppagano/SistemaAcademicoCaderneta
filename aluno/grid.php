<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<script>
function redirecionaSemestre()
{
		window.location = 'grid.php?cod_semestre=' + document.getElementById('semestre').value;		
}

function redirecionaDisciplina()
{
		window.location = 'grid.php?cod_semestre=' + document.getElementById('semestre').value + '&cod_disciplina=' + document.getElementById('disciplina').value;		
}

function redirecionaTurma()
{
		window.location = 'grid.php?cod_semestre=' + document.getElementById('semestre').value + '&cod_disciplina=' + document.getElementById('disciplina').value + '&cod_turma=' + document.getElementById('turma').value;		
}

</script>

<?php

include '../banco.php';

$cod_semestre = "0";
$cod_disciplina = "0";
$cod_turma = "0";

if(isset($_GET ["cod_semestre"]))
{
	$cod_semestre	= $_GET ["cod_semestre"];
	$rsDisciplina = Select("select distinct id, cod_disciplina from disciplina where cod_semestre = $cod_semestre order by nome");

	if(isset($_GET ["cod_disciplina"]))
	{	
		$cod_disciplina = $_GET ["cod_disciplina"];;
		
		$rsTurma = Select("select * from disciplina where cod_disciplina = $cod_disciplina and cod_semestre = $cod_semestre order by nome");
		
		if(isset($_GET ["cod_turma"]))
		{	
			$cod_turma = $_GET ["cod_turma"];
		}
	}	
}
	

echo "<table>";

echo "<tr><td>Semestre:</td><td>";

$rsSemestre = Select("select * from semestre order by nome");
$num_rows = mysql_num_rows($rsSemestre);

if($num_rows > 0)
{	
	echo "<select id='semestre' name='semestre' onchange='redirecionaSemestre()'><option value='0'>Selecione</option>";
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
		echo "<option value='" . $row['cod_semestre'] . "' " . $sel .">" . $row['nome'] . "</option>";	
	}
	echo "</select>";
}
echo "</td>";
echo "<td>Disciplina:</td><td>";

if(isset($_GET ["cod_semestre"]))
{
	$num_rows = mysql_num_rows($rsDisciplina);
	if($num_rows > 0)
	{
		
		echo "<select id='disciplina' name='disciplina' onchange='redirecionaDisciplina()'><option value='0'>Selecione</option>";
		while($row = mysql_fetch_array($rsDisciplina))
		{
			if($cod_disciplina == $row['cod_disciplina'])
			{
				$sel = "selected";
			}
			else
			{
				$sel = "";
			}
			echo "<option value='" . $row['cod_disciplina'] . "' " . $sel .">" . $row['id'] . "</option>";
			
			
		}
		echo "</select>";
	}
}
echo "</td>";


echo "<td>Turma:</td><td>";

if(isset($_GET ["cod_disciplina"]))
{
	$num_rows = mysql_num_rows($rsTurma);
	if($num_rows > 0)
	{
		
		echo "<select id='turma' name='turma' onchange='redirecionaTurma()'><option value='0'>Selecione</option>";
		while($row = mysql_fetch_array($rsTurma))
		{
			if($cod_turma == $row['cod_disciplina'])
			{
				$sel = "selected";
			}
			else
			{
				$sel = "";
			}
			echo "<option value='" . $row['cod_disciplina'] . "' " . $sel .">" . $row['turma'] . "</option>";
			
			
		}
		echo "</select>";
	}
}

echo "</td>";

echo "</table>";


//$rs = Select("select * from aluno where cod_disciplina = $cod_turma order by nome");
$rs = Select("select a.*, (select count(*) from frequencia where cod_disciplina = a.cod_disciplina and matricula = a.matricula and presenca = 0 having count(*)) as faltas_sis from aluno a where cod_disciplina = $cod_turma order by nome");


echo "<table border=1>";
echo "<tr>
<td>Matricula</td>
<td>Nome</td>
<td>Nota 1</td>
<td>Nota 2</td>
<td>Nota 3</td>
<td>Media Parcial</td>
<td>Faltas</td>
<td>Final</td>
<td>Media final</td>
<td>Situacao</td>
<td>Falta Sistema</td>
<td>Alterar</td>
</tr>";

$num_rows = mysql_num_rows($rs);
if($num_rows > 0)
{
	while($row = mysql_fetch_array($rs))
	{
		echo "<tr>";
		echo "<td>" . $row['matricula'] . "</td>";
		echo "<td>" . $row['nome'] . "</td>"; 
		echo "<td>" . $row['nota1'] . "</td>"; 
		echo "<td>" . $row['nota2'] . "</td>";
		echo "<td>" . $row['nota3'] . "</td>";
		echo "<td>" . $row['media_parcial'] . "</td>";
		echo "<td>" . $row['faltas'] . "</td>";
		echo "<td>" . $row['prova_final'] . "</td>";
		echo "<td>" . $row['media_final'] . "</td>";
		echo "<td>" . $row['situacao'] . "</td>";
		echo "<td>" . $row['faltas_sis'] * 2 . "</td>";
		echo "<td><a href='alterar.php?cod_aluno=" . $row['cod_aluno'] . "&cod_disciplina=" . $cod_turma . "'>Alterar</a></td>";
		echo "</tr>";
	}
}
else
{
	echo "<tr><td colspan='5'>Sem registros</td></tr>";
}
echo "</table>";

if($cod_semestre != "0" && $cod_disciplina != "0" && $cod_turma != "0")
{
	$end = "cadastrar.php?cod_semestre=" . $cod_semestre . "&cod_disciplina=" . $cod_disciplina . "&cod_turma=" . $cod_turma;
	echo "<a href='" . $end. "'>Cadastrar</a>";
}

?>