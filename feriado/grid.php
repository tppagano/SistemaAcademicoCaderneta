<script>

function postarFiltro()
{
	valor = document.getElementById('semestre').value;
	
	
	window.location = 'grid.php?semestre=' + valor;


}

</script>

<?php

include '../banco.php';



$rsSemestre = Select("select * from semestre s order by nome");

$num_rows_semestre = mysql_num_rows($rsSemestre);

echo "<table>";


$semestre = "";
if(isset($_GET["semestre"]))
{
	$semestre = $_GET["semestre"];
}

$query = "select f.*, s.nome  from feriado f, semestre s where f.cod_semestre = s.cod_semestre  ";
if($semestre != "")
{
	$query =  $query . " and f.cod_semestre = $semestre ";
}
$query =  $query . "order by nome";

$rs = Select($query);

$cmbsemestre = "<select id='semestre' nome='semestre' onchange='postarFiltro();'><option value=''>Selecione</option>";

if($num_rows_semestre > 0)
{
	while($row = mysql_fetch_array($rsSemestre))
	{
		$value = $row["cod_semestre"];
		
		$selected = "";
		
		if($value == $semestre)
		{
			$selected = "selected";
		}
	
		
		$cmbsemestre = $cmbsemestre . "<option value='$value' $selected>" . $row["nome"];
		$cmbsemestre = $cmbsemestre . "</option>";
	}
}

$cmbsemestre = $cmbsemestre . "</select>";


echo "<tr><td colspan = 6>$cmbsemestre</td></tr>";
echo "<tr><td>Semestre</td><td>Data inicio</td><td>Data fim</td><td>Consultar</td><td>Alterar</td><td>Excluir</td></tr>";

$num_rows = mysql_num_rows($rs);
if($num_rows > 0)
{
	while($row = mysql_fetch_array($rs))
	{
		echo "<tr><td>" . $row['nome'] . "</td><td>" . $row['data_ini'] . "</td><td>" . $row['data_fim'] . "</td><td>Consultar</td><td><a href='alterar.php?cod_feriado=" . $row['cod_feriado'] . "'>Alterar</a></td><td><a href='remover.php?cod_feriado=" . $row['cod_feriado'] . "'>Excluir</a></td></tr>";
	}
}
else
{
	echo "<tr><td colspan='5'>Sem registros</td></tr>";
}
echo "</table>";

$end = "cadastrar.php";

echo "<a href='" . $end. "'>Cadastrar</a>";

?>