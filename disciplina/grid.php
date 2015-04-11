<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php

include '../banco.php';


$rs = Select("select d.*,s.nome as semestre from disciplina d, semestre s where d.cod_semestre = s.cod_semestre order by semestre, id, turma");

echo "<table>";

echo "<tr><td>Semestre</td><td>Identificador</td><td>Nome</td><td>Turma</td><td>Consultar</td><td>Alterar</td><td>Excluir</td></tr>";

$num_rows = mysql_num_rows($rs);
if($num_rows > 0)
{
	while($row = mysql_fetch_array($rs))
	{
		echo "<tr><td>" . $row['semestre'] . "</td><td>" . $row['id'] . "</td><td>" . $row['nome'] . "</td><td>" . $row['turma'] . "</td><td>Consultar</td><td><a href='alterar.php?cod_disciplina=" . $row['cod_disciplina'] . "'>Alterar</a></td><td><a href='remover.php?cod_disciplina=" . $row['cod_disciplina'] . "'>Excluir</a></td></tr>";
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