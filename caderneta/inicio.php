<script>
function redirecionaSemestre()
{
		window.location = 'inicio.php?cod_semestre=' + document.getElementById('semestre').value;		
}

function redirecionaDisciplina()
{
		window.location = 'inicio.php?cod_semestre=' + document.getElementById('semestre').value + '&cod_disciplina=' + document.getElementById('disciplina').value;		
}

</script>

<?php

header("Content-Type: text/html; charset=utf-8");

include '../banco.php';

$cod_semestre = "0";
$cod_disciplina = "0";


if(isset($_GET ["cod_semestre"]))
{
	$cod_semestre	= $_GET ["cod_semestre"];
	$rsDisciplina = Select("select id, cod_disciplina, turma from disciplina where cod_semestre = $cod_semestre order by nome");

	if(isset($_GET ["cod_disciplina"]))
	{	
		$cod_disciplina = $_GET ["cod_disciplina"];;
		
		$rsTurma = Select("select * from disciplina where cod_disciplina = $cod_disciplina and cod_semestre = $cod_semestre order by nome");
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

if(isset($_GET ["cod_semestre"]) || $cod_semestre != "0")
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
			echo "<option value='" . $row['cod_disciplina'] . "' " . $sel .">" . $row['id'] . " - " . $row['turma'] . "</option>";
			
			
		}
		echo "</select>";
	}
}
echo "</td>";

echo "</table>";

if($cod_disciplina != "0")
{
	echo "<a href='gerarCaderneta.php?cod_disciplina=" . $cod_disciplina . "'>Gerar Caderneta</a>";
}

?>



