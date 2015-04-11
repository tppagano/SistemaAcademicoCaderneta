<?php

include '../banco.php';

if(isset($_GET ["cod_aluno"]))
{
	$cod_disciplina = $_GET ["cod_disciplina"];
	$cod_aluno = $_GET ["cod_aluno"];
	
	if(isset($_POST ["matricula"]))
	{
		$matricula	= $_POST ["matricula"];
		$nome	= $_POST ["nome"];
		
		$nota1 = $_POST ["nota1"] == null ? 0 : $_POST ["nota1"];
		$nota2 = $_POST ["nota2"] == null ? 0 : $_POST ["nota2"];
		$nota3 = $_POST ["nota3"] == null ? 0 : $_POST ["nota3"];
		
		$media_parcial = $_POST ["media_parcial"] == null ? 0 : $_POST ["media_parcial"];
		$faltas = $_POST ["faltas"] == null ? 0 : $_POST ["faltas"];
		$prova_final = $_POST ["prova_final"] == null ? 0 : $_POST ["prova_final"];
		$media_final = $_POST ["media_final"] == null ? 0 : $_POST ["media_final"];
		$situacao	= $_POST ["situacao"];
		
		$nQuery = "UPDATE sistema_academico.aluno SET cod_disciplina = $cod_disciplina , matricula = '$matricula', nome = '$nome', nota1 = $nota1, nota2 = $nota2, nota3 = $nota3, media_parcial = $media_parcial, faltas = $faltas, prova_final = $prova_final, media_final = $media_final, situacao = '$situacao' WHERE cod_aluno = $cod_aluno";
		
		noQuery($nQuery);
	}	

	$rsAluno = Select("select * from Aluno where cod_aluno = $cod_aluno LIMIT 1");
	$rowAluno = mysql_fetch_array($rsAluno);
	
	$matricula = $rowAluno ["matricula"];
	$nome = $rowAluno ["nome"];
	$nota1 = $rowAluno ["nota1"];
	$nota2 = $rowAluno ["nota2"];
	$nota3 = $rowAluno ["nota3"];
	$media_parcial = $rowAluno ["media_parcial"];
	$faltas = $rowAluno ["faltas"];
	$prova_final = $rowAluno ["prova_final"];
	$media_final = $rowAluno ["media_final"];
	$situacao = $rowAluno ["situacao"];
		
}




?>
<form method='post' action='alterar.php?cod_aluno=<?php echo $cod_aluno ?>&cod_disciplina=<?php echo $cod_disciplina?>'>
<table>
<tr>
<td>Matricula:</td>
<td><input type="text" name="matricula" id="matricula" value="<?php echo $matricula ?>"></td>
</tr>
<tr>
<td>Nome:</td>
<td><input type="text" name="nome" id="nome" value="<?php echo $nome ?>"></td>
</tr>
<tr>
<td>Nota 1:</td>
<td><input type="text" name="nota1" id="nota1" value="<?php echo $nota1 ?>"></td>
</tr>
<tr>
<td>Nota 2:</td>
<td><input type="text" name="nota2" id="nota2" value="<?php echo $nota2 ?>"></td>
</tr>
<tr>
<td>Nota 3:</td>
<td><input type="text" name="nota3" id="nota3" value="<?php echo $nota3 ?>"></td>
</tr>
<tr>
<td>Média parcial:</td>
<td><input type="text" name="media_parcial" id="media_parcial" value="<?php echo $media_parcial ?>"></td>
</tr>
<tr>
<td>Faltas:</td>
<td><input type="text" name="faltas" id="faltas" value="<?php echo $faltas ?>"></td>
</tr>
<tr>
<td>Final:</td>
<td><input type="text" name="prova_final" id="prova_final" value="<?php echo $prova_final ?>"></td>
</tr>
<tr>
<td>Media Final:</td>
<td><input type="text" name="media_final" id="media_final" value="<?php echo $media_final ?>"></td>
</tr>
<tr>
<td>Situacao:</td>
<td><input type="text" name="situacao" id="situacao" value="<?php echo $situacao ?>"></td>
</tr>
<tr>

<tr>
<tr>
<td colspan="2"><input type="submit" name="btn"  id="btn" value="OK"></td>
</tr>
</table>
