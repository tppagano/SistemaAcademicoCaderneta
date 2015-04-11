<script>
function redirecionaSemestre()
{
		window.location = 'grid.php?cod_semestre=' + document.getElementById('semestre').value;		
}

function redirecionaDisciplina()
{
		window.location = 'grid.php?cod_semestre=' + document.getElementById('semestre').value + '&cod_disciplina=' + document.getElementById('disciplina').value;		
}

function redirecionaData()
{

		window.location = 'grid.php?cod_semestre=' + document.getElementById('semestre').value + '&cod_disciplina=' + document.getElementById('disciplina').value + '&data=' + document.getElementById('data').value;		
}

function alterar(cod)
{
		window.location = 'alterar.php?cod_frequencia=' + cod;		

}
</script>

<?php

header("Content-Type: text/html; charset=utf-8");

include '../banco.php';

$cod_semestre = "0";
$cod_disciplina = "0";
$data = "0";



if(isset($_GET ["cod_frequencia"]))
{
	$cod_frequencia = $_GET ["cod_frequencia"];
	
	$rsFrequencia = Select("select d.cod_semestre, d.cod_disciplina, f.cod_dias_letivos from frequencia f, aluno a, disciplina d where cod_frequencia = $cod_frequencia and a.cod_disciplina = f.cod_disciplina and a.matricula = f.matricula and  a.cod_disciplina = d.cod_disciplina");
	
	$row = mysql_fetch_array($rsFrequencia);
	
	$cod_semestre =  $row['cod_semestre'] ;
	$cod_disciplina =  $row['cod_disciplina'] ;
	$data =  $row['cod_dias_letivos'] ;
	
	$rsDisciplina = Select("select id, cod_disciplina, turma from disciplina where cod_semestre = $cod_semestre order by nome");
	$rsTurma = Select("select * from disciplina where cod_disciplina = $cod_disciplina and cod_semestre = $cod_semestre order by nome");
	$rsDiasLetivos = Select("select * from dias_letivos where cod_disciplina = $cod_disciplina order by data");
}

if(isset($_GET ["cod_semestre"]))
{
	$cod_semestre	= $_GET ["cod_semestre"];
	$rsDisciplina = Select("select id, cod_disciplina, turma from disciplina where cod_semestre = $cod_semestre order by nome");

	if(isset($_GET ["cod_disciplina"]))
	{	
		$cod_disciplina = $_GET ["cod_disciplina"];;
		
		$rsTurma = Select("select * from disciplina where cod_disciplina = $cod_disciplina and cod_semestre = $cod_semestre order by nome");
		$rsDiasLetivos = Select("select * from dias_letivos where cod_disciplina = $cod_disciplina order by data");
		
		if(isset($_GET ["data"]))
		{	
			$data = $_GET ["data"];
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
echo "<td>Data:</td><td>";

if(isset($_GET ["cod_disciplina"]) || $cod_disciplina != "0")
{
	$num_rows = mysql_num_rows($rsDiasLetivos);
	if($num_rows > 0)
	{
		
		echo "<select id='data' name='data' onchange='redirecionaData()'><option value='0'>Selecione</option>";
		while($row = mysql_fetch_array($rsDiasLetivos))
		{
			if($data == $row['cod_dias_letivos'])
			{
				$sel = "selected";
			}
			else
			{
				$sel = "";
			}
			echo "<option value='" . $row['cod_dias_letivos'] . "' " . $sel .">" . $row['data'] . "</option>";
			
			
		}
		echo "</select>";
	}
}
echo "</td>";
echo "</table>";

$q = "select f.cod_frequencia, a.matricula, a.nome, f.presenca from dias_letivos dl, aluno a, disciplina d, frequencia f 
where 
a.cod_disciplina = d.cod_disciplina and 
dl.cod_disciplina = d.cod_disciplina and 
d.cod_disciplina = $cod_disciplina and 
dl.cod_dias_letivos = f.cod_dias_letivos and 
f.cod_disciplina = a.cod_disciplina and 
f.matricula = a.matricula and 
dl.cod_dias_letivos = $data 
order by nome";

$rs = Select($q);

echo "<table border=1>";
echo "<tr>
<td>Matricula</td>
<td>Nome</td>
<td>Presenca</td>
</tr>";

$num_rows = mysql_num_rows($rs);
if($num_rows > 0)
{
	while($row = mysql_fetch_array($rs))
	{
		$presenca = $row['presenca'];
		
		$sel = "";
		if($presenca == "1")
		{
			$sel = "checked";
		}
		echo "<tr>";
		echo "<td>" . $row['matricula'] . "</td>";
		echo "<td>" . $row['nome'] . "</td>"; 
		echo "<td><input type='checkbox' name='" . $row['matricula'] . "' onclick='alterar(" . $row['cod_frequencia'] . ")' " . $sel . "></td>"; 
		echo "</tr>";
	}
}
else
{
	echo "<tr><td colspan='5'>Sem registros</td></tr>";
}
echo "</table>";


?>



