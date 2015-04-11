<?php

$cod_semestre = "0";
$cod_disciplina = "0";
$cod_turma = "0";

if(isset($_GET ["cod_semestre"]))
{
	$cod_semestre	= $_GET ["cod_semestre"];
	
	if(isset($_GET ["cod_disciplina"]))
	{	
		$cod_disciplina = $_GET ["cod_disciplina"];;
		
		if(isset($_GET ["cod_turma"]))
		{	
			$cod_turma = $_GET ["cod_turma"];
		}
	}
	
}

?>

<table>

<tr>
<td>
	Informe o arquivo com os dados dos alunos
</td>
</tr>
<tr>
<td>
	<iframe id="iframe_upload" src="../upload.php" width="500px" height="40px" scrolling="no" style="border: 1px solid red"></iframe>
</td>
</tr>

<tr>
<td>
	<input type="button" value="Inserir" onclick="window.location='inserirAlunos.php?cod_disciplina=<?php echo $cod_disciplina?>';">
</td>
</tr>

</table>