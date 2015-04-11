<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>

<?php
mb_internal_encoding( 'UTF-8' );
set_time_limit(0);
header("Content-Type: text/html; charset=utf-8");
include '../banco.php';

session_start(); 

if (isset($_SESSION['arquivo']))
{ 
	$cod_disciplina = 0;
	if(isset($_GET ["cod_disciplina"]))
	{
		$cod_disciplina = $_GET ["cod_disciplina"];
	}
	
	$row = 1;
	$handle = fopen ("../uploads/".$_SESSION['arquivo'],"r");
	
	$nQuery = "delete from aluno where cod_disciplina = $cod_disciplina";
	noQuery($nQuery);
	
	//$nQuery = "delete from frequencia f, aluno a where a.cod_disciplina = $cod_disciplina and a.cod_aluno = f.cod_aluno";
	//noQuery($nQuery);
	
	
	
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$num = count ($data);
		echo "<p> $num campos na linha $row: <br /></p>\n";
		$row++;
		
		$nQuery = "insert into aluno (cod_disciplina, matricula, nome, nota1, nota2, nota3, media_parcial, faltas, prova_final, media_final, situacao)  values (";
		
		$campos = $cod_disciplina . ",";
		
		for ($c=0; $c < $num; $c++) {
			echo $data[$c] . "<br />\n";
			
			//if($row == 2 && $c == 0)
			//{
			//	$dado = substr($data[$c], 3, strlen($data[$c]) - 3);
			//	$campos = $campos . "'" . str_replace(',','.',$dado) . "'";
			//}
			//else
			//{			
				if($c == 0 || $c == 1 || $c == 9)
				{				
					$campos = $campos . "'" . str_replace(',','.',$data[$c]) . "'";				
				}
				else
				{
					$dado = "null";
					if($data[$c] != "")
					{
						$dado = $data[$c];
					}
					
					$campos = $campos . str_replace(',','.',$dado);
					
					$faltas = 0;
					if($c == 7)
					{
						$faltas = $dado;
					}
				}
			//}
			if($c < $num - 1)
			{
				$campos = $campos . ",";
			}
		}
		
		$nQuery = $nQuery . $campos . ")";
		
		
		
		$nQuery = iconv("ISO-8859-1", "UTF-8//IGNORE", $nQuery);
		
		noQuery($nQuery);
		
		
	}
	
	fclose ($handle);
	
	
	$nQuery = "insert into frequencia (cod_dias_letivos, cod_disciplina, matricula) ";
	$nQuery = $nQuery . " select dl.cod_dias_letivos, a.cod_disciplina, a.matricula from dias_letivos dl, aluno a, disciplina d";
	$nQuery = $nQuery . " where" ;
	$nQuery = $nQuery . " a.cod_disciplina = d.cod_disciplina and ";
	$nQuery = $nQuery . " dl.cod_disciplina = d.cod_disciplina and	";
	$nQuery = $nQuery . " d.cod_disciplina = $cod_disciplina and";
	$nQuery = $nQuery . " a.matricula not in (select matricula from frequencia where cod_disciplina = $cod_disciplina)";
	
	echo $nQuery;
		
	noQuery($nQuery);	
	
	
	
	$Query = "select a.*, ( select count(*) from frequencia f where  f.cod_disciplina = $cod_disciplina and  presenca = 0 and f.matricula = a.matricula) qtd from aluno a ";
	$Query = $Query . " where ";
	$Query = $Query . " a.cod_disciplina = $cod_disciplina and ";
	$Query = $Query . " faltas > ( select count(*) from frequencia f where  f.cod_disciplina = $cod_disciplina and  presenca = 0 and f.matricula = a.matricula) ";

	$rs = select($Query);
	
	
	
	while($row = mysql_fetch_array($rs))
	{	
	
		if($row["qtd"] * 2 < $row["faltas"])
		{
			$qtd = ($row["faltas"] / 2)  - $row["qtd"];
			$matricula = $row["matricula"];
			
			$Query = "select * from frequencia where matricula  = '$matricula' and cod_disciplina = $cod_disciplina limit $qtd";
			echo "<br>" . $row["qtd"] . "-" . $Query;
			
			
			$rsInd = select($Query);
			
			while($rowInd = mysql_fetch_array($rsInd))
			{
				$cod_frequencia = $rowInd["cod_frequencia"];
				$nQuery = "update frequencia set presenca = 0 where cod_frequencia = $cod_frequencia";
				noQuery($nQuery);		
			}
		}
	}
	
}
else
{
	echo "Faca upload do arquivo";
}

?>