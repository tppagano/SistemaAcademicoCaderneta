<?php

include '../banco.php';


$rs = Select("select * from semestre order by nome");

echo "<table>";

echo "<tr><td>Nome</td><td>Data inicio</td><td>Consultar</td><td>Alterar</td><td>Excluir</td></tr>";

$num_rows = mysql_num_rows($rs);
if($num_rows > 0)
{
	while($row = mysql_fetch_array($rs))
	{
		echo "<tr><td>" . $row['nome'] . "</td><td>" . $row['data_inicio'] . "</td><td>Consultar</td><td><a href='alterar.php?cod_semestre=" . $row['cod_semestre'] . "'>Alterar</a></td><td><a href='remover.php?cod_semestre=" . $row['cod_semestre'] . "'>Excluir</a></td></tr>";
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