<STYLE>
p.quebra    { page-break-before: always }
</STYLE>

<script>

function printit() 
{ 
	document.getElementById("printButton").style.display='none'; 
	if ((navigator.appName == "Netscape")) 
	{ 
		window.print() ; 
	} 
	else 
	{ 
		var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
		var nCopies = 4; //because i want to print it 4 times 
		for(x=0; x<=nCopies;x++) 
		{ 
			document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6,-1); 
			WebBrowser1.outerHTML = ""; 
		} 
	} 
	document.getElementById("printButton").style.display='inline'; 
} 
</script>

<?php

header("Content-Type: text/html; charset=utf-8");

include '../banco.php';

$cod_disciplina = "0";


if(isset($_GET ["cod_disciplina"]))
{

	$cod_disciplina = $_GET ["cod_disciplina"];
	
	$rsDisciplina = Select("select d.*, s.nome as nomeSemestre from disciplina d, semestre s where cod_disciplina = $cod_disciplina and d.cod_semestre = s.cod_semestre");
	$rowDisciplina = mysql_fetch_array($rsDisciplina);	
	
	$rsDisciplinaDiaHora = Select("select cod_disciplina, cod_dia, date_format(hora_ini,'%H:%i') as hora_ini, date_format(hora_fim,'%h:%i') as hora_fim  from disciplina_dia_hora where cod_disciplina = $cod_disciplina");
	$rsAlunos = Select("select * from aluno where cod_disciplina = $cod_disciplina");
	
	$conexao = conectaBancoSelect($host,$bd, $senhabd, $database);
	
	mysql_query(" SET @rowId1 :=0; ");
	
	$query = " select (@rowId1 := @rowId1 + 1) as num , tab.* from ";
	$query = $query . "(";
	$query = $query . " select * from dias_letivos where cod_disciplina = $cod_disciplina ";
	$query = $query . " union all ";
	$query = $query . " select * from dias_letivos where cod_disciplina = $cod_disciplina ";
	$query = $query . " )  tab order by data ";
	
	$rsDiasLetivos = mysql_query($query, $conexao) or die (mysql_error());
	
	$num_rows_dias_letivos = mysql_num_rows($rsDiasLetivos);
	
	
	$c = 0;
	$d_dias_letivos = array();
	$m_dias_letivos = array();
	$a_dias_letivos = array();
	while($row = mysql_fetch_array($rsDiasLetivos))
	{
		$data = $row["data"];
		list ($ano, $mes, $dia) = explode('-', $data);
		
		$d_dias_letivos[$c] = $dia;
		$m_dias_letivos[$c] = $mes;
		$a_dias_letivos[$c] = $ano;
		
		$c++;
	}
	
	//echo $num_rows_dias_letivos;
	
	$qtd_alunos = mysql_num_rows($rsAlunos);
	
	$qtdFolhasCapa = 1;
	$qtdFolhasAvaliacao = ((integer) (mysql_num_rows($rsAlunos) / 18) + 1);
	$qtdFolhasRegistro = (integer) ($rowDisciplina["carga_horaria"] / 30) + 1;
	
	$qtdFolhasFrequencia = (integer)((integer) (mysql_num_rows($rsAlunos) / 18) + 1) * ((integer)($num_rows_dias_letivos / 30) + 1);
	
	$qtdFolhas = $qtdFolhasCapa + $qtdFolhasRegistro + $qtdFolhasFrequencia;	
	
	IF($rowDisciplina["pratica"] == "0")
	{
		$qtdFolhas = $qtdFolhas + $qtdFolhasAvaliacao;
	}
	
	$countFolhas = 0;
	capaCaderneta();
	echo "<p class='quebra' id='t1'></p>";
	IF($rowDisciplina["pratica"] == "0")
	{
		folhaAvaliacaoCaderneta();
		echo "<p class='quebra' id='t2'></p>";
	}
	folhaRegistroAcademico(1);
	echo "<p class='quebra' id='t2'></p>";
	
	//move para o inicio do rs
	folhaFrequencia();	
	
	
	
	echo "<input type='button' value='Imprimir' onclick='printit();' id='printButton' name='printButton'/>";
}





function capaCaderneta()
{
	global $cod_disciplina, $rsDisciplina, $rowDisciplina, $rsDisciplinaDiaHora, $qtdFolhas;
	
	imprimeTopo("DIÁRIO DE CLASSE");
	
	echo "<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left width=877 style='width:657.45pt;border-collapse:collapse;border:none; mso-border-alt:solid black .5pt;mso-yfti-tbllook:1184;mso-table-lspace:7.05pt; margin-left:4.8pt;mso-table-rspace:7.05pt;margin-right:4.8pt;mso-table-anchor-vertical: paragraph;mso-table-anchor-horizontal:margin;mso-table-left:left;mso-table-top: -1.1pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;mso-border-insideh:.5pt solid black; mso-border-insidev:.5pt solid black'>";
	echo "<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>";
	echo "<td width=680 colspan=2 valign=top style='width:510.35pt;border-top:solid black;  border-left:solid black;border-bottom:double windowtext;border-right:double windowtext;  border-width:1.0pt;mso-border-top-alt:solid black .5pt;mso-border-left-alt:  solid black .5pt;mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:  double windowtext .75pt;padding:0cm 5.4pt 0cm 5.4pt'>  ";
	echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical: paragraph;mso-element-anchor-horizontal:margin;mso-element-top:-1.1pt;  mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'>";
	echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Centro</span>";
	echo "</b>";
	echo "</p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:  normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:  margin;mso-element-top:-1.1pt;mso-height-rule:exactly'>";
	echo "<span lang=PT-BR  style='font-size:9.0pt;font-family:'Cambria','serif''>CENTRO DE CIÊNCIAS  EXATAS E TECNOLÓGICAS</span>";
	echo "<span lang=PT-BR style='font-size:8.0pt;  mso-bidi-font-size:9.0pt;font-family:'Cambria','serif''></span>";
	echo "</p>";
	echo "</td>";
	echo "<td width=196 colspan=2 valign=top style='width:147.1pt;border:solid black 1.0pt;";
	echo "border-left:none;mso-border-left-alt:double windowtext .75pt;mso-border-alt:";
	echo "solid black .5pt;mso-border-left-alt:double windowtext .75pt;padding:0cm 5.4pt 0cm 5.4pt'>";
	echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
	echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
	echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:-1.1pt;";
	echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'><span";
	echo "lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Classe</span></b></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
	echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
	echo "margin;mso-element-top:-1.1pt;mso-height-rule:exactly'><span lang=PT-BR";
	echo "style='font-size:9.0pt;font-family:'Cambria','serif''>" . $rowDisciplina["id"] . " - " . $rowDisciplina["turma"] . "</span></p>";
	echo "</td>";
	echo "</tr>";
	echo "<tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes'>";
	echo "<td width=679 valign=top style='width:509.55pt;border-top:none;border-left:";
	echo "solid black 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:double windowtext 1.0pt;";
	echo "mso-border-top-alt:double windowtext .75pt;mso-border-top-alt:double windowtext .75pt;";
	echo "mso-border-left-alt:solid black .5pt;mso-border-bottom-alt:solid windowtext .5pt;";
	echo "mso-border-right-alt:double windowtext .75pt;padding:0cm 5.4pt 0cm 5.4pt'>";
	echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
	echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
	echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:-1.1pt;";
	echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'><span";
	echo "lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Disciplina</span></b></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
	echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
	echo "margin;mso-element-top:-1.1pt;mso-height-rule:exactly'><span lang=PT-BR";
	echo "style='font-size:9.0pt;font-family:'Cambria','serif''>" . $rowDisciplina["nome"] . "</span></p>";
	echo "</td>";
	echo "<td width=103 colspan=2 valign=top style='width:77.05pt;border-top:none;";
	echo "border-left:none;border-bottom:solid windowtext 1.0pt;border-right:double windowtext 1.0pt;";
	echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
	echo "mso-border-alt:double windowtext .75pt;mso-border-bottom-alt:solid windowtext .5pt;";
	echo "padding:0cm 5.4pt 0cm 5.4pt'>";
	echo "<p class=MsoNormal align=center style='margin-bottom:3.0pt;text-align:center;";
	echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
	echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
	echo "margin;mso-element-top:-1.1pt;mso-height-rule:exactly'><b style='mso-bidi-font-weight:";
	echo "normal'><span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Carga";
	echo "Horária</span></b></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:0cm;";
	echo "margin-bottom:0cm;margin-left:6.45pt;margin-bottom:.0001pt;text-align:center;";
	echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
	echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
	echo "margin;mso-element-top:-1.1pt;mso-height-rule:exactly'><span lang=PT-BR";
	echo "style='font-size:9.0pt;font-family:'Cambria','serif''>" . $rowDisciplina["carga_horaria"] . "</span></p>";
	echo "</td>";
	echo "<td width=94 valign=top style='width:70.85pt;border-top:none;border-left:";
	echo "none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;";
	echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
	echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
	echo "mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid black .5pt;";
	echo "padding:0cm 5.4pt 0cm 5.4pt'>";
	echo "<p class=MsoNormal align=center style='margin-bottom:3.0pt;text-align:center;";
	echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
	echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
	echo "margin;mso-element-top:-1.1pt;mso-height-rule:exactly'><span class=GramE><b";
	echo "style='mso-bidi-font-weight:normal'><span lang=PT-BR style='font-size:9.0pt;";
	echo "font-family:'Cambria','serif''>Limite Faltas</span></b></span><b";
	echo "style='mso-bidi-font-weight:normal'><span lang=PT-BR style='font-size:9.0pt;";
	echo "font-family:'Cambria','serif''></span></b></p>";
	echo "<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;";
	echo "text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:";
	echo "7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;";
	echo "mso-element-anchor-horizontal:margin;mso-element-top:-1.1pt;mso-height-rule:";
	echo "exactly'><span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>" . $rowDisciplina["limite_faltas"] . "</span></p>";
	echo "</td>";
	echo "</tr>";
	echo "<![if !supportMisalignedColumns]>";
	echo "<tr height=0>";
	echo "<td width=679 style='border:none'></td>";
	echo "<td width=1 style='border:none'></td>";
	echo "<td width=102 style='border:none'></td>";
	echo "<td width=94 style='border:none'></td>";
	echo "</tr>";
	echo "<![endif]>";
	echo "</table>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'><span lang=PT-BR>&nbsp;</span></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'><span lang=PT-BR>&nbsp;</span></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'><span lang=PT-BR>&nbsp;</span></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'><span lang=PT-BR>&nbsp;</span></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'><span lang=PT-BR>&nbsp;</span></p>";
	echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
	echo "normal'><span lang=PT-BR>&nbsp;</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><b";
	echo "style='mso-bidi-font-weight:normal'><span lang=PT-BR style='font-size:22.0pt;";
	echo "line-height:normal%;font-family:'Arial Black','sans-serif''>DIÁRIO DE CLASSE</span></b></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><b";
	echo "style='mso-bidi-font-weight:normal'><span lang=PT-BR style='font-size:22.0pt;";
	echo "line-height:115%;font-family:'Arial Black','sans-serif''>&nbsp;</span></b></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
	echo "lang=PT-BR style='font-size:22.0pt;line-height:115%;font-family:'Arial','sans-serif''>" . $rowDisciplina["nomeSemestre"] . "</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
	echo "lang=PT-BR style='font-size:22.0pt;line-height:115%;font-family:'Arial','sans-serif''>&nbsp;</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
	echo "lang=PT-BR style='mso-bidi-font-size:22.0pt;line-height:115%;font-family:'Arial','sans-serif''>&nbsp;</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
	echo "lang=PT-BR style='mso-bidi-font-size:22.0pt;line-height:115%;font-family:'Arial','sans-serif''>&nbsp;</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><![if !vml]><span style='mso-ignore:vglayout'>";
	echo "<table cellpadding=0 cellspacing=0 align=left border=0>";
	echo "<tr>";
	echo "<td width=161 height=3></td>";
	echo "<td width=2></td>";
	echo "<td width=100></td>";
	echo "<td width=40></td>";
	echo "<td></td>";
	echo "<td width=62></td>";
	echo "<td width=261></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td height=1></td>";
	echo "<td></td>";
	echo "<td colspan=2 width=100 height=32 bgcolor=white style='border:.75pt solid black;  vertical-align:top;background:white'>";
	echo "Horário";
	echo "</td>  ";
	echo "<td height=31></td>";
	echo "<td height=31></td>";
	echo "<td width=200 height=30 bgcolor=white style='border:.75pt solid black;  vertical-align:top;background:white'>  ";
	echo "Local  ";
	echo "</td>";	
	echo "</tr>";
	echo "<tr>";
	echo "<td height=1></td>";
	echo "<td></td>";
	echo "<td colspan=2 height=32 bgcolor=white style='font-size:10.0pt;line-height:115%;font-family:'Cambria','serif'; mso-bidi-font-family:Arial'>";
	while($row = mysql_fetch_array($rsDisciplinaDiaHora))
	{	
		$dia = "";
		if($row['cod_dia'] == "1")
		{
			$dia = "SEG";
		}
		else if($row['cod_dia'] == "2")
		{
			$dia = "TER";
		}
		else if($row['cod_dia'] == "3")
		{
			$dia = "QUA";
		}
		else if($row['cod_dia'] == "4")
		{
			$dia = "QUI";
		}
		else if($row['cod_dia'] == "5")
		{
			$dia = "SEX";
		}
		
	
		echo $dia . " " . $row['hora_ini'] . " às " . $row['hora_fim'] . "</br>";
	}
	echo "</td>  ";
	echo "<td height=31></td>";
	echo "<td height=31></td>";
	echo "<td height=32 bgcolor=white style='font-size:10.0pt;line-height:115%;font-family:'Cambria','serif'; mso-bidi-font-family:Arial'>  ";
	echo "003 / CENTRO DE CIÊNCIAS EXATAS E TECNOLÓGICAS  ";
	echo "</td>";	
	echo "</tr> ";
	echo "<tr>";
	echo "<td height=1></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td height=1></td>";
	echo "<td colspan=4></td>";
	echo "<td colspan=2 rowspan=2 align=left valign=top><img width=323 height=2";
	echo "src='Capa_arquivos/image003.gif' v:shapes='_x0000_s1034'></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td height=1></td>";
	echo "<td></td>";
	echo "<td colspan=2 rowspan=2 align=left valign=top><img width=121 height=2";
	echo "src='Capa_arquivos/image004.gif' v:shapes='_x0000_s1033'></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td height=1></td>";
	echo "</tr>";
	echo "</table>";
	echo "</span><![endif]><span lang=PT-BR style='mso-bidi-font-size:22.0pt;line-height:";
	echo "115%;font-family:'Arial','sans-serif''>&nbsp;</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
	echo "lang=PT-BR style='mso-bidi-font-size:22.0pt;line-height:115%;font-family:'Arial','sans-serif''>&nbsp;</span></p>";
	echo "<br style='mso-ignore:vglayout' clear=ALL>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt; margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt; margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'>";
	echo "<span lang=PT-BR style='font-size:10.0pt;line-height:115%;font-family:'Cambria','serif';mso-bidi-font-family:Arial'>&nbsp;</span>";
	echo "</p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;";
	echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'>";
	echo "<span style='mso-ignore:vglayout'>";
	echo "<table cellpadding=0 cellspacing=0 align=left>";
	echo "<tr>";
	echo "<td width=159 height=2></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td></td>";
	echo "<td><img width=323 height=34 src='Capa_arquivos/image005.gif' v:shapes='_x0000_s1032 _x0000_s1035'></td>";
	echo "</tr>";
	echo "</table>";
	echo "</span>";
	echo "<span lang=PT-BR style='font-size:10.0pt;line-height:115%; font-family:'Cambria','serif';mso-bidi-font-family:Arial'>&nbsp;</span></p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt;margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'>";
	echo "<span lang=PT-BR style='font-size:10.0pt;line-height:115%;font-family:'Cambria','serif'; mso-bidi-font-family:Arial'>&nbsp;</span>";
	echo "</p>";
	echo "<br style='mso-ignore:vglayout' clear=ALL>";
	echo "<p class=MsoNormal style='margin-top:0cm;margin-right:112.7pt;margin-bottom: 0cm;margin-left:4.3cm;margin-bottom:.0001pt'>";
	echo "<span lang=PT-BR style='font-size: 10.0pt;line-height:115%;font-family:'Cambria','serif';mso-bidi-font-family: Arial'>";
	echo "<span style='mso-spacerun:yes'></span>" . strtoupper($rowDisciplina["professor"]) . "</span>";
	echo "</p>";
	echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:112.7pt; margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'>";
	echo "<span lang=PT-BR style='font-size:10.0pt;line-height:115%;font-family:'Cambria','serif'; mso-bidi-font-family:Arial'>&nbsp;</span>";
	echo "</p>";

}

function folhaAvaliacaoCaderneta()
{

		global $cod_disciplina, $rsDisciplina, $rowDisciplina, $rsDisciplinaDiaHora, $rsAlunos, $qtdFolhasAvaliacao;
		$count = 0;
		$countAluno = 0;
		$countPag = 1;
		
		while($row = mysql_fetch_array($rsAlunos))
		{	
			$count++;
			if($countAluno == 0)
			{
				imprimeCabecalho($countPag);	
			}	
			
			if($countAluno < 17)
			{
				$countAluno++;	
				imprimirAluno($count, $row);
			}
			else
			{
				imprimirAluno($count, $row);
				$countAluno = 0;
				$countPag++;	
				echo "</table>";
				echo "<p class='quebra'></p>";
			}
		}			
		
		imprimeRodapeFolhaAvaliacao();
		
		
	}

function folhaRegistroAcademico()
{
	global $cod_disciplina,$host,$bd, $senhabd, $database;
	
	
	$conexao = conectaBancoSelect($host,$bd, $senhabd, $database);
	
	
	mysql_query(" SET @rowId1 :=0; ");
	mysql_query(" SET @rowId2 :=0; ");
	mysql_query(" SET @rowId3 :=0; ");
	
	$query = "";
	$query = $query . " select (@rowId3 := @rowId3 + 1) as num3,data,atividade from ";
	$query = $query . " ( ";
	$query = $query . " (select data,atividade from ";
	$query = $query . " ( ";
	$query = $query . " select (@rowId1 := @rowId1 + 1) as num1, data from dias_letivos dl, disciplina d ";
	$query = $query . " where dl.cod_disciplina = d.cod_disciplina and  ";
	$query = $query . " d.cod_disciplina = $cod_disciplina ";
	$query = $query . " order by data ";
	$query = $query . " ) as tab1, ";
	$query = $query . " ( ";
	$query = $query . " select (@rowId2 := @rowId2 + 1) as num2, ra.atividade from registro_academico ra, disciplina d ";
	$query = $query . " where ra.id_disciplina = d.id and ";
	$query = $query . " d.cod_disciplina = $cod_disciplina ";
	$query = $query . " order by num2,cod_registro_academico  ";
	$query = $query . " ) as tab2 ";
	$query = $query . " where tab1.num1 = tab2.num2)  ";
	$query = $query . " union all ";
	$query = $query . " (select data,atividade from ";
	$query = $query . " ( ";
	$query = $query . " select (@rowId1 := @rowId1 + 1) as num1, data from dias_letivos dl, disciplina d ";
	$query = $query . " where dl.cod_disciplina = d.cod_disciplina and  ";
	$query = $query . " d.cod_disciplina = $cod_disciplina ";
	$query = $query . " order by data ";
	$query = $query . " ) as tab1, ";
	$query = $query . " ( ";
	$query = $query . " select (@rowId2 := @rowId2 + 1) as num2, ra.atividade from registro_academico ra, disciplina d ";
	$query = $query . " where ra.id_disciplina = d.id and ";
	$query = $query . " d.cod_disciplina = $cod_disciplina ";
	$query = $query . " order by num2,cod_registro_academico  ";
	$query = $query . " ) as tab2 ";
	$query = $query . " where tab1.num1 = tab2.num2) ";
	$query = $query . " ) tab order by data ";
	
	
	$rsRegistro_academico = mysql_query($query, $conexao) or die (mysql_error());
	
	$num_rows = mysql_num_rows($rsRegistro_academico);
	
	$lista = array();	
	for($count = 0;$count < $num_rows;$count++)
	{
		$lista[] = array('','','','');
	}
			
	$count = 0;
	
	while($row = mysql_fetch_array($rsRegistro_academico))
	{	
		$data = $row["data"];
		list ($ano, $mes, $dia) = explode('-', $data);
		$data = $dia . "/" . $mes . "/" . $ano;
		
		
		$lista[$count][0] = $row["num3"];
		$lista[$count][1] = $data;
		$lista[$count][2] = $row["atividade"];
		$lista[$count][3] = "";
		
		$count++;
	}
	
	
	$lista1 = array();	
	
	for($count = 0;$count < $num_rows / 2;$count++)
	{
		$lista1[] = array('','','','','','','','');
	}
	
	
	$c = 0;
	for($i = 0; $i < $num_rows;$i++)
	{
		if($i + 15 < $num_rows - ($num_rows % 30))
		{
			$lista1[$c][0] = $lista[$i][0];
			$lista1[$c][1] = $lista[$i][1];
			$lista1[$c][2] = $lista[$i][2];
			$lista1[$c][3] = $lista[$i][3];
			$lista1[$c][4] = $lista[$i + 15][0];
			$lista1[$c][5] = $lista[$i + 15][1];
			$lista1[$c][6] = $lista[$i + 15][2];
			$lista1[$c][7] = $lista[$i + 15][3];
		}
		else
		{
			if( $i + 15 < $num_rows )
			{ 
				$lista1[$c][0] = $lista[$i + 15][0];
				$lista1[$c][1] = $lista[$i + 15][1];
				$lista1[$c][2] = $lista[$i + 15][2];
				$lista1[$c][3] = $lista[$i + 15][3];
				$lista1[$c][4] = "&nbsp";
				$lista1[$c][5] = "&nbsp";
				$lista1[$c][6] = "&nbsp";
				$lista1[$c][7] = "&nbsp";
			}
			else if($i < $num_rows)
			{
				$lista1[$c][0] = $lista[$i][0];
				$lista1[$c][1] = $lista[$i][1];
				$lista1[$c][2] = $lista[$i][2];
				$lista1[$c][3] = $lista[$i][3];
				$lista1[$c][4] = "&nbsp";
				$lista1[$c][5] = "&nbsp";
				$lista1[$c][6] = "&nbsp";
				$lista1[$c][7] = "&nbsp";
			
			}
		}
		
		if($i == 15)
		{
			$i = $i + 14;
		}
		else
		{
			$c++;		
		}
		
	}
	
	if($num_rows % 30 == 8)
	{
		$val = ($num_rows / 2);	
	}
	else
	{
		$val = $c;	
	}	
	
	$c = 0;
	
	for($i = 0; $i < $val; $i++)
	{
		if($i % 15 == 0) // $i % 15 == 0
		{
			$altura = "";
			if($i < 30 && $num_rows % 30 == 8)
			{
				$altura = "height:550;";
			}
			
			echo "<p class='quebra' id='" . ++$c . "'></p>";
			imprimeTopo("FOLHA DE REGISTRO ACADÊMICO");
			echo("<table width='100%'>");			
			echo("<tr>");
			echo("<td>");
			imprimeCabecalhoFormato2();
			echo("</td>");
			echo("</tr>");
			echo("<tr>");
			echo("<td>");
			echo("<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left width=1018 style='width:763.8pt;" . $altura . "border-collapse:collapse;border:none; mso-border-alt:solid black .5pt;mso-yfti-tbllook:1184;mso-table-lspace:7.05pt;");
			echo("margin-left:4.8pt;mso-table-rspace:7.05pt;margin-right:4.8pt;mso-table-anchor-vertical: paragraph;mso-table-anchor-horizontal:margin;mso-table-left:left;mso-table-top:");
			echo("26.15pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;mso-border-insideh:.5pt solid black; mso-border-insidev:.5pt solid black'>");
			echo("<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>");
			echo("<td width=36 valign=top style='width:26.7pt;border-top:solid black;  border-left:solid black;border-bottom:double windowtext;border-right:double windowtext;");
			echo("border-width:1.0pt;mso-border-top-alt:solid black .5pt;mso-border-left-alt:  solid black .5pt;mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:");
			echo("double windowtext .75pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-12.45pt;  margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;");
			echo("line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;  mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:");
			echo("margin;mso-element-top:26.15pt;mso-height-rule:exactly'>");
			echo("<span class=GramE>");
			echo("<span  lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''>Aula  .</span>");
			echo("</span>");
			echo("<span lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''></span>");
			echo("</p>");
			echo("</td>");
			echo("<td width=47 valign=top style='width:35.4pt;border-top:solid black 1.0pt;  border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-alt:double windowtext .75pt;  mso-border-top-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;  margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;");
			echo("line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;  mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:");
			echo("margin;mso-element-top:26.15pt;mso-height-rule:exactly'>");
			echo("<span lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''>Dia/Mês</span>");
			echo("</p>");
			echo("</td>");
			echo("<td width=380 valign=top style='width:285.2pt;border-top:solid black 1.0pt;  border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-alt:double windowtext .75pt;  mso-border-top-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;  text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:");
			echo("7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;  mso-element-anchor-horizontal:margin;mso-element-top:26.15pt;mso-height-rule:");
			echo("exactly'>");
			echo("<span lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''>Assunto</span></p>");
			echo("</td>");
			echo("<td width=67 valign=top style='width:50.45pt;border-top:solid black 1.0pt;  border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-alt:double windowtext .75pt;  mso-border-top-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;  text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:");
			echo("7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;  mso-element-anchor-horizontal:margin;mso-element-top:26.15pt;mso-height-rule:  exactly'>");
			echo("<span lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''>Rubrica</span>");
			echo("</p>");
			echo("</td>");
			echo("<td width=34 valign=top style='width:25.85pt;border-top:solid black 1.0pt; border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-alt:double windowtext .75pt;  mso-border-top-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;  margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;");
			echo("line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;  mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:");
			echo("margin;mso-element-top:26.15pt;mso-height-rule:exactly'>");
			echo("<span lang=PT-BR  style='font-size:9.0pt;font-family:'Times New Roman','serif''>");
			echo("<span style='mso-spacerun:yes'> </span>Aula</span>");
			echo("</p>");
			echo("</td>");
			echo("<td width=47 valign=top style='width:35.4pt;border-top:solid black 1.0pt;  border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-alt:double windowtext .75pt;  mso-border-top-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;  margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;");
			echo("line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;  mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:");
			echo("margin;mso-element-top:26.15pt;mso-height-rule:exactly'>");
			echo("<span lang=PT-BR  style='font-size:9.0pt;font-family:'Times New Roman','serif''>Dia/Mês</span>");
			echo("</p>");
			echo("</td>");
			echo("<td width=340 valign=top style='width:9.0cm;border-top:solid black 1.0pt;  border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-alt:double windowtext .75pt;  mso-border-top-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;  text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:");
			echo("7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;  mso-element-anchor-horizontal:margin;mso-element-top:26.15pt;mso-height-rule:");
			echo("exactly'>");
			echo("<span lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''>Assunto</span>");
			echo("</p>");
			echo("</td>");
			echo("<td width=66 valign=top style='width:49.65pt;border-top:solid black 1.0pt;  border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;");
			echo("mso-border-left-alt:double windowtext .75pt;mso-border-top-alt:solid black .5pt;  mso-border-left-alt:double windowtext .75pt;mso-border-bottom-alt:double windowtext .75pt;");
			echo("mso-border-right-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt'>");
			echo("<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;  text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:");
			echo("7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;  mso-element-anchor-horizontal:margin;mso-element-top:26.15pt;mso-height-rule:");
			echo("exactly'>");
			echo("<span lang=PT-BR style='font-size:9.0pt;font-family:'Times New Roman','serif''>Rubrica</span>");
			echo("</p>");
			echo("</td>");
			echo("</tr>");
		}
		
		echo("<tr>");
		
		if($i == 30)
		{
			$val = $val + 4;
			
		}
		
		
		for($j = 0; $j < 8;$j++)
		{			
			if($lista1[$i][$j] != "")
			{
				echo("<td cellspacing=1 cellpadding=1>" . $lista1[$i][$j] . "</td>");
			}
			else
			{
				echo("<td cellspacing=1 cellpadding=1>&nbsp;</td>");
			}
		}		
		echo("</tr>");
		
		
		
		if(($i + 1) % 15 == 0) 
		{ 
			echo("</table>");
			echo("</td>");
			echo("</tr>");
			echo("</table>");
		}
	}
	
	
	echo("</table>");
	echo("</td>");
	echo("</tr>");
	echo("</table>");	
	


}	
	
function imprimirAluno($count, $row)
{
		$mat = $row["matricula"];
		$disc = $row["cod_disciplina"];
		
		$query = " select (count(*) * 2) as faltas  from aluno a, frequencia f ";
		$query = $query . " where " ;
		$query = $query . " f.cod_disciplina = $disc and " ;
		$query = $query . " f.cod_disciplina = a.cod_disciplina and " ;
		$query = $query . " a.matricula = f.matricula and " ;
		$query = $query . " a.matricula = '$mat' and " ;
		$query = $query . " presenca = 0 ";
		
		$rsFalta = Select($query);
		
		$rowFalta = mysql_fetch_array($rsFalta);
		
		echo "<tr style='mso-yfti-irow:4;height:3.5pt'>";
				echo "<td width=28 valign=top style='width:21.0pt;border-top:none;border-left:solid black 1.0pt;";
				echo "border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-top-alt:double windowtext .75pt;";
				echo "mso-border-left-alt:solid black .5pt;mso-border-bottom-alt:double windowtext .75pt;";
				echo "mso-border-right-alt:solid black .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:";
				echo "3.5pt'>";
				echo "<p class=MsoNormal style='margin-top:1.5pt;margin-right:0cm;margin-bottom:";
				echo "0cm;margin-left:0cm;margin-bottom:.0001pt;line-height:normal;mso-element:";
				echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
				echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
				echo "mso-height-rule:exactly'><span lang=PT-BR style='font-size:9.0pt;font-family:";
				echo "'Cambria','serif''>" . $count . "</span></p>";
				echo "</td>";
				echo "<td width=500 valign=bottom style='width:500pt;border-top:none;border-left:  none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;  mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;  mso-border-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;  padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height: normal;mso-element:frame;mso-element-wrap:auto;mso-element-anchor-vertical:  paragraph;mso-element-anchor-horizontal:margin;mso-height-rule:exactly'>";
				echo "<span  lang=PT-BR style='mso-fareast-font-family:'Times New Roman';color:black;'><b><font size='1'>" . $row["matricula"] . " - " . $row["nome"] . "</font></b></span>";
				echo "</p>  ";
				echo "</td>";
				echo "<td width=76 valign=bottom style='width:2.0cm;border-top:none;border-left:";
				echo "none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($row["nota1"]) . "</span></p>";
				echo "</td>";
				echo "<td width=76 colspan=3 valign=bottom style='width:92.15pt;border-top:none;";
				echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($row["nota2"]) . "</span></p>";
				echo "</td>";
				echo "<td width=76 colspan=2 valign=bottom style='width:99.25pt;border-top:none;";
				echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($row["nota3"]) . "</span></p>";
				echo "</td>";
				echo "<td width=47 valign=bottom style='width:35.4pt;border-top:none;border-left:";
				echo "none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($row["media_parcial"]) . "</span></p>";
				echo "</td>";
				echo "<td width=57 colspan=2 valign=bottom style='width:42.55pt;border-top:none;";
				echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span class=GramE><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($row["prova_final"]) . "</span></span><span lang=PT-BR";
				echo "style='mso-fareast-font-family:'Times New Roman';color:black'></span></p>";
				echo "</td>";
				echo "<td width=57 valign=bottom style='width:42.5pt;border-top:none;border-left:";
				echo "none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span class=GramE><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($row["media_final"]) . "</span></span><span lang=PT-BR";
				echo "style='mso-fareast-font-family:'Times New Roman';color:black'></span></p>";
				echo "</td>";
				echo "<td width=66 valign=bottom style='width:49.65pt;border-top:none;border-left:";
				echo "none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;";
				echo "text-align:right;line-height:normal;mso-element:frame;mso-element-wrap:auto;";
				echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
				echo "mso-height-rule:exactly'><span lang=PT-BR style='mso-fareast-font-family:";
				echo "'Times New Roman';color:black'>" . strtoupper($rowFalta["faltas"]) . "</span></p>";
				echo "</td>";
				echo "<td valign=bottom style='width:49.6pt;border-top:none;border-left:";
				echo "none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
				echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
				echo "normal;mso-element:frame;mso-element-wrap:auto;mso-element-anchor-vertical:";
				echo "paragraph;mso-element-anchor-horizontal:margin;mso-height-rule:exactly'><span";
				echo "lang=PT-BR style='mso-fareast-font-family:'Times New Roman';color:black'>" . strtoupper($row["situacao"]) . "</span></p>";
				echo "</td>";
				echo "</tr>";
				echo "<tr height=0>";
				echo "<td width=28 style='border:none'></td>";
				echo "<td width=338 style='border:none'></td>";
				echo "<td width=95 style='border:none'></td>";
				echo "<td width=8 style='border:none'></td>";
				echo "<td width=28 style='border:none'></td>";
				echo "<td width=86 style='border:none'></td>";
				echo "<td width=37 style='border:none'></td>";
				echo "<td width=96 style='border:none'></td>";
				echo "<td width=47 style='border:none'></td>";
				echo "<td width=56 style='border:none'></td>";
				echo "<td width=1 style='border:none'></td>";
				echo "<td width=57 style='border:none'></td>";
				echo "<td width=66 style='border:none'></td>";
				echo "<td width=66 style='border:none'></td>";
				echo "</tr>";
	
	}
	
function imprimeCabecalho()
{	
			global $rowDisciplina;
			
			imprimeTopo("FOLHA DE AVALIAÇÃO");
			
			echo "<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left width=100% style='width:756.7pt; border-collapse:collapse;border:none; mso-border-alt:solid black .5pt;mso-yfti-tbllook:1184;mso-table-lspace:7.05pt;";
			echo "margin-left:4.8pt;mso-table-rspace:7.05pt;margin-right:4.8pt;mso-table-anchor-vertical: paragraph;mso-table-anchor-horizontal:margin;mso-table-left:left;mso-table-top:";
			echo "4.75pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;mso-border-insideh:.5pt solid black; mso-border-insidev:.5pt solid black'>";
			
			echo "<tr><td colspan=38>";
			imprimeCabecalhoFormato1();	
			echo "</td></tr>";
			
			echo "<tr style='mso-yfti-irow:2;height:35.05pt'>";
			echo "<td width=700 colspan=2 rowspan=2 valign=top style='width:300pt;border-top:";
			echo "none;border-left:solid black 1.0pt;border-bottom:solid black 1.0pt;";
			echo "border-right:double windowtext 1.0pt;mso-border-top-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:double windowtext .75pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;'>Aluno";
			echo "</td>";
			echo "<td width=76 valign=top style='width:2.0cm;border-top:none;border-left:none;";
			echo "border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;mso-border-top-alt:";
			echo "double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-4.2pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
			echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;font-family:'Cambria','serif''>1ª Avaliação</span></p>";
			echo "</td>";
			echo "<td width=76 colspan=3 valign=top style='width:60pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-4.2pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
			echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;font-family:'Cambria','serif''>2ª Avaliação</span></p>";
			echo "</td>";
			echo "<td width=76 colspan=2 valign=top style='width:99.25pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-4.2pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
			echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;font-family:'Cambria','serif''>3ª Avaliação</span></p>";
			echo "</td>";
			echo "<td width=47 rowspan=2 valign=top style='width:35.4pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;";
			echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
			echo "mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif''>Média</span></p>";
			echo "</td>";
			echo "<td width=57 colspan=2 rowspan=2 valign=top style='width:42.55pt;border-top:";
			echo "none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;";
			echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
			echo "mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif''>Prova";
			echo "Final</span></p>";
			echo "</td>";
			echo "<td width=57 rowspan=2 valign=top style='width:42.5pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;";
			echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
			echo "mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif''>Média";
			echo "Final</span></p>";
			echo "</td>";
			echo "<td width=66 rowspan=2 valign=top style='width:49.65pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;";
			echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
			echo "mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif''>Total Geral de Faltas</span></p>";
			echo "</td>";
			echo "<td rowspan=2 valign=top style='width:49.6pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:35.05pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:6.0pt;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;";
			echo "mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;";
			echo "mso-element-top:4.75pt;mso-height-rule:exactly'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif''>Resultado Final</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "<tr style='mso-yfti-irow:3;height:13.5pt'>";
			echo "<td width=76 valign=top style='width:2.0cm;border-top:none;border-left:none;";
			echo "border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;mso-border-top-alt:";
			echo "double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;height:13.5pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:3.0pt;margin-right:0cm;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
			echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;font-family:'Cambria','serif''>Nota</span></p>";
			echo "</td>";
			echo "<td width=123 colspan=3 valign=top style='width:92.15pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;height:13.5pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:3.0pt;margin-right:0cm;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
			echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;font-family:'Cambria','serif''>Nota</span></p>";
			echo "</td>";
			echo "<td width=132 colspan=2 valign=top style='width:99.25pt;border-top:none;";
			echo "border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
			echo "mso-border-bottom-alt:solid black .5pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt;height:13.5pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:3.0pt;margin-right:0cm;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center;";
			echo "line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;";
			echo "mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
			echo "style='font-size:9.0pt;font-family:'Cambria','serif''>Nota</span></p>";
			echo "</td>";
			echo "</tr>";
	
	}
	
function imprimeRodapeFolhaAvaliacao()
{			
			echo "<table>";
			echo "<tr><td>&nbsp;</td></tr>";
			echo "<tr><td>Resultado Final:</td></tr>";
			echo "<tr><td>";
			echo "<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0";
			echo "style='margin-left:-14.2pt;border-collapse:collapse;border:none;mso-border-alt:";
			echo "solid black .5pt;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;";
			echo "mso-border-insideh:.5pt solid black;mso-border-insidev:.5pt solid black'>";
			echo "<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>";
			echo "<td width=36 valign=top style='width:26.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>AF</span></p>";
			echo "</td>";
			echo "<td width=198 valign=top style='width:148.85pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Aprovado Frequência</span></p>";
			echo "</td>";
			echo "<td width=38 valign=top style='width:1.0cm;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>AM</span></p>";
			echo "</td>";
			echo "<td width=189 colspan=2 valign=top style='width:5.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Aprovado por Média</span></p>";
			echo "</td>";
			echo "<td width=47 valign=top style='width:35.45pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>AP</span></p>";
			echo "</td>";
			echo "<td width=151 colspan=3 valign=top style='width:4.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Aprovado</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "<tr style='mso-yfti-irow:1'>";
			echo "<td width=36 valign=top style='width:26.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>AT</span></p>";
			echo "</td>";
			echo "<td width=198 valign=top style='width:148.85pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Aprovado Atividade</span></p>";
			echo "</td>";
			echo "<td width=38 valign=top style='width:1.0cm;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>DI</span></p>";
			echo "</td>";
			echo "<td width=189 colspan=2 valign=top style='width:5.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Dispensado</span></p>";
			echo "</td>";
			echo "<td width=47 valign=top style='width:35.45pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>DU</span></p>";
			echo "</td>";
			echo "<td width=151 colspan=3 valign=top style='width:4.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Dispensa UFBA</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "<tr style='mso-yfti-irow:2'>";
			echo "<td width=36 valign=top style='width:26.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>EC</span></p>";
			echo "</td>";
			echo "<td width=198 valign=top style='width:148.85pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Em curso</span></p>";
			echo "</td>";
			echo "<td width=38 valign=top style='width:1.0cm;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>IC</span></p>";
			echo "</td>";
			echo "<td width=189 colspan=2 valign=top style='width:5.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Incompleto</span></p>";
			echo "</td>";
			echo "<td width=47 valign=top style='width:35.45pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>ME</span></p>";
			echo "</td>";
			echo "<td width=151 colspan=3 valign=top style='width:4.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "class=GramE><span lang=PT-BR>Aprovado Média</span></span><span lang=PT-BR>";
			echo "(Pós)</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "<tr style='mso-yfti-irow:3'>";
			echo "<td width=36 valign=top style='width:26.7pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>MF</span></p>";
			echo "</td>";
			echo "<td width=198 valign=top style='width:148.85pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Aprovado Prova Final</span></p>";
			echo "</td>";
			echo "<td width=38 valign=top style='width:1.0cm;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>RM</span></p>";
			echo "</td>";
			echo "<td width=189 colspan=2 valign=top style='width:5.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Reprovado por Média</span></p>";
			echo "</td>";
			echo "<td width=47 valign=top style='width:35.45pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;";
			echo "margin-bottom:0cm;margin-left:0cm;margin-bottom:.0001pt;text-align:center'><span";
			echo "lang=PT-BR>RP</span></p>";
			echo "</td>";
			echo "<td width=151 colspan=3 valign=top style='width:4.0cm;border:none;padding:";
			echo "0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR>Reprovado</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "<tr style='mso-yfti-irow:4;mso-yfti-lastrow:yes;height:5.0pt;mso-row-margin-right:";
			echo "34.4pt'>";
			echo "<td width=272 colspan=3 valign=top style='width:203.9pt;border:none;";
			echo "padding:0cm 3.5pt 0cm 3.5pt;height:5.0pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR><o:p>&nbsp;</o:p></span></p>";
			echo "</td>";
			echo "<td width=167 valign=top style='width:124.95pt;border:none;padding:0cm 3.5pt 0cm 3.5pt;";
			echo "height:5.0pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR><o:p>&nbsp;</o:p></span></p>";
			echo "</td>";
			echo "<td width=155 colspan=3 valign=top style='width:116.25pt;border:none;";
			echo "padding:0cm 3.5pt 0cm 3.5pt;height:5.0pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt'><span";
			echo "lang=PT-BR><o:p>&nbsp;</o:p></span></p>";
			echo "</td>";
			echo "<td width=20 valign=top style='width:15.0pt;border:none;padding:0cm 3.5pt 0cm 3.5pt;";
			echo "height:5.0pt'>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
			echo "normal'><span lang=PT-BR><o:p>&nbsp;</o:p></span></p>";
			echo "</td>";
			echo "<td style='mso-cell-special:placeholder;border:none;padding:0cm 0cm 0cm 0cm'";
			echo "width=46><p class='MsoNormal'>&nbsp;</td>";
			echo "</tr>";
			echo "<tr height=0>";
			echo "<td width=36 style='border:none'></td>";
			echo "<td width=198 style='border:none'></td>";
			echo "<td width=38 style='border:none'></td>";
			echo "<td width=167 style='border:none'></td>";
			echo "<td width=22 style='border:none'></td>";
			echo "<td width=47 style='border:none'></td>";
			echo "<td width=85 style='border:none'></td>";
			echo "<td width=20 style='border:none'></td>";
			echo "<td width=46 style='border:none'></td>";
			echo "</tr>";
			echo "</table>";
			echo "</td>";
			echo "<td valign='top'>";	
			echo "<table>";
			echo "<tr><td align='center'>________________________________</td></tr>";
			echo "<tr><td align='center'>Assinatura do Professor</td></tr>";
			echo "</table>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
	}

function folhaFrequencia()
{
	global $cod_disciplina, $d_dias_letivos, $m_dias_letivos, $num_rows_dias_letivos, $qtd_alunos;

	$ini = 0;
	$c1 = 1;
	
	
	$countDiasLetivos = 30;
	
	$c5 = 0; //contador de dias 
	
	loopAluno :
	
	
	while($countDiasLetivos <= $num_rows_dias_letivos)
	{
		
		
		
		$query = "select * from aluno where cod_disciplina = $cod_disciplina LIMIT $ini, 16";
		
		$rsAlunos1 = Select($query);
		$num_rows_cons = mysql_num_rows($rsAlunos1);
		
		if($num_rows_cons > 0)
		{
		echo "<p class='quebra' id='t1'></p>";
		
		imprimeTopo("FOLHA DE FREQUENCIA");
		
		{
		echo "<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left  width=1068 style='width:100%;border-collapse:collapse;border:none; mso-border-alt:solid black .5pt;mso-yfti-tbllook:1184;mso-table-lspace:7.05pt; margin-left:4.8pt;mso-table-rspace:7.05pt;margin-right:4.8pt;mso-table-anchor-vertical: paragraph;mso-table-anchor-horizontal:margin;mso-table-left:left;mso-table-top: 4.75pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;mso-border-insideh:.5pt solid black; mso-border-insidev:.5pt solid black'>";
		echo "<tr><td colspan=38>";
		imprimeCabecalhoFormato1();	
		echo "</td></tr>";
		echo "<tr style='mso-yfti-irow:2;height:6.75pt'>";
		echo "<td colspan=2 rowspan=4 valign=top style='width:1000pt;";
		echo "border-top:none;border-left:solid black 1.0pt;border-bottom:double windowtext 1.0pt;";
		echo "border-right:solid black 1.0pt;mso-border-top-alt:double windowtext .75pt;";
		echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
		echo "mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
		echo "padding:0cm 5.4pt 0cm 5.4pt;height:6.75pt'>";
		echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
		echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
		echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
		echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''><o:p>&nbsp;</o:p></span></p>";
		echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
		echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
		echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
		echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''><o:p>&nbsp;</o:p></span></p>";
		echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
		echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
		echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
		echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''>Aluno</span></p>";
		echo "</td>";
		echo "<td width=85 rowspan=4 valign=top style='width:63.75pt;border-top:none;";
		echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;";
		echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
		echo "mso-border-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
		echo "padding:0cm 5.4pt 0cm 5.4pt;height:6.75pt'>";
		echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
		echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
		echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
		echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''><o:p>&nbsp;</o:p></span></p>";
		echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
		echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
		echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
		echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''><o:p>&nbsp;</o:p></span></p>";
		echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
		echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
		echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
		echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''>Curso</span></p>";
		echo "</td>";
		echo "<td width=681 colspan=35 valign=top style='width:510.55pt;border-top:none;";
		echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
		echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:double windowtext .75pt;";
		echo "mso-border-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
		echo "background:#EEECE1;padding:0cm 5.4pt 0cm 5.4pt;height:6.75pt'>";
		echo "<p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;";
		echo "text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:";
		echo "7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;";
		echo "mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;mso-height-rule:";
		echo "exactly'><b style='mso-bidi-font-weight:normal'><span lang=PT-BR";
		echo "style='font-size:9.0pt;font-family:'Cambria','serif''>Registro de falta –";
		echo "Preencher com ‘A’ as ausências</span></b></p>";
		echo "</td>";
		echo "</tr>";
		echo "<tr style='mso-yfti-irow:3;height:13.0pt'>";
		echo "<td>Aula</td>";
		}
		
		if($countDiasLetivos == $num_rows_dias_letivos)
		{
			$tam = $num_rows_dias_letivos % 30;
			$c = $countDiasLetivos - ($tam - 1);			
		}
		else
		{
			$c = $countDiasLetivos - 29;
			$tam = 30;
		}
		
		$col = "";
		$count = 1;
		while($count <= $tam)
		{
			if($count == 4 || $count == 14 || $count == 23)
			{
				$col = " colspan=2 ";
			}
			else
			{
				$col = "";
			}
			echo "<td $col align='center' valign='middle'>" . $c . "</td>";
			$c++;
			$count++;
		}
		
		$aux = $count;
		$count = 1;
		while($count <= 31 - $aux)
		{
			if($count == 5 || $count == 16)
			{
				$col = " colspan=2 ";
			}
			else
			{
				$col = "";
			}
			echo "<td $col align='center' valign='middle'>&nbsp;&nbsp;&nbsp;</td>";
			$count++;
		}
		
		echo "<td rowspan=3>Sub-Total de Faltas</td>";
		echo "</tr>";
		//fim primeira linha cabecalho
		
		//aqui
				
		echo "<tr style='mso-yfti-irow:4;height:13.9pt'>";
		echo "<td>Dia</td>";
		
		if($countDiasLetivos == $num_rows_dias_letivos)
		{
			$tam = $num_rows_dias_letivos % 30;
			$c = $countDiasLetivos - ($tam - 1);
		}
		else
		{
			$c = $countDiasLetivos - 29;
			$tam = 30;
		}
		
		$col = "";
		$count = 1;
		while($count <= $tam)
		{
			if($count == 4 || $count == 14 || $count == 23)
			{
				$col = " colspan=2 ";
			}
			else
			{
				$col = "";
			}
			echo "<td $col align='center' valign='middle'>" . $d_dias_letivos[$c - 1] . "</td>";
			$c++;
			$count++;
		}
		
		$aux = $count;
		$count = 1;
		while($count <= 31 - $aux)
		{
			if($count == 5 || $count == 16)
			{
				$col = " colspan=2 ";
			}
			else
			{
				$col = "";
			}
			echo "<td $col align='center' valign='middle'>&nbsp;&nbsp;&nbsp;</td>";
			$count++;
		}
		
		echo "</tr>";
		
		echo "<tr style='mso-yfti-irow:5;height:3.5pt'>";
		echo "<td>Mês</td>";
		
		if($countDiasLetivos == $num_rows_dias_letivos)
		{
			$c = $countDiasLetivos - 7;
			$tam = $num_rows_dias_letivos % 30;			
		}
		else
		{
			$c = $countDiasLetivos - 29;
			$tam = 30;
		}
		$col = "";
		$count = 1;
		
		while($count <= $tam)
		{
			if($count == 4 || $count == 14 || $count == 23)
			{
				$col = " colspan=2 ";
			}
			else
			{
				$col = "";
			}
			echo "<td $col align='center' valign='middle'>" . $m_dias_letivos[$c - 1] . "</td>";
			$c++;
			$count++;
		}
		
		
		$aux = $count;
		$count = 1;
		while($count <= 31 - $aux)
		{
			if($count == 5 || $count == 16)
			{
				$col = " colspan=2 ";
			}
			else
			{
				$col = "";
			}
			echo "<td $col align='center' valign='middle'>&nbsp;&nbsp;&nbsp;</td>";
			$count++;
		}
		
		echo "</tr>";
		
		
		
		//linhas de alunos
		{
			
			while($row = mysql_fetch_array($rsAlunos1))
			{
			
				if ($c1 < 10) 
				{ 
					$val = "0".$c1;
				} 
				else
				{
					$val = $c1;
				}
				echo "<tr style='mso-yfti-irow:6;height:3.5pt'>";
				echo "<td>$val</td>";
				echo "<td><span lang=PT-BR style='font-size:9.0pt;font-family: 'Cambria','serif''>" . $row["matricula"] . "-" . $row["nome"] . "</span></td>";
				echo "<td width=123 colspan=2 valign=top style='width:150.1pt;border-top:none;";
				echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:double windowtext 1.0pt;";
				echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid windowtext 1.0pt;";
				echo "mso-border-alt:double windowtext .75pt;mso-border-left-alt:solid windowtext 1.0pt;";
				echo "padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>";
				echo "<p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:";
				echo "0cm;margin-left:0cm;margin-bottom:.0001pt;line-height:normal;mso-element:";
				echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
				echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
				echo "mso-height-rule:exactly'>";
				echo "<span lang=PT-BR style='font-size:6.0pt;font-family: 'Cambria','serif''>BACHARELADO EM CIÊNCIAS EXATAS</span>";
				echo "</p>";
				echo "</td>";
				
				
				//AQUI
					
				if($countDiasLetivos == $num_rows_dias_letivos)
				{
					$tam = ($countDiasLetivos % 15) /2 ;
					$c = ($tam) - ($tam % 15);
				}
				else
				{
					$c = $countDiasLetivos - 30;
					$tam = 15;
				}
				
				
				$matricula = $row["matricula"];
				
				//echo "[" . $c5 . " - " . $tam . "]";
				
				$query = "select * from frequencia f, dias_letivos dl where matricula = '$matricula' and f.cod_disciplina = $cod_disciplina and f.cod_dias_letivos = dl.cod_dias_letivos order by data LIMIT $c5, $tam";
				
				$rs = Select($query);
				
				$vet = array();
				
				$c2 = 0;
				while($rowRs = mysql_fetch_array($rs))
				{
					$vet[$c2] = $rowRs["presenca"];
					
					$c2++;
				}
				
				
				$faltas=0;
				
				$c4 = 0;
				
				while($c4 < $tam)
				{
					$presenca = ".";
					
					if($vet[$c4] == "0")
					{
						$presenca = "A";
						$faltas++;
					}
				
					$cols1 = "";
					$cols2 = "";
					
					if($c4 == 1 || $c4 == 6 || $c4 == 16)
					{
						$cols1 = " colspan=2 ";
					}
					else if($c4 == 11)
					{
						$cols2 = " colspan=2 ";
					}					
					
					echo "<td valign='middle' align='center' $cols2>$presenca</td>";
					echo "<td valign='middle' align='center' $cols1>$presenca</td>";
					$c++;
					$c4++;
				}
				
				
				
				$c3 = 0;
				while($c3 < 30 - $c4 * 2)
				{
					$cols1 = "";
					
					if($c3 == 4 || $c3 == 15)
					{
						$cols1 = " colspan=2 ";
					}
					echo "<td valign='middle' align='center' $cols1>&nbsp;&nbsp;&nbsp;</td>";
					
					
					$c3++;
				}
				
				echo "<td valign='middle' align='center' $cols1>" . $faltas*2 . "</td>";
				
				echo "</tr>";
				
				
				$c1++;
			}
		}
		
		echo "</table>";
		
		
		}
		
		
		if($c1 <= $qtd_alunos)
		{			
			$ini += 16;
			
			goto loopAluno;
		}
		$c5 += 15; 
		
		$c1 = 1;
		$ini = 0;
		
		if($countDiasLetivos + 30 <= $num_rows_dias_letivos)
		{
			$countDiasLetivos += 30; 
		}
		else
		{
			echo "<table>";
				echo "<tr>";
				echo "<td colspan='3'></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Observações:</td>";
				echo "<td width='800'><table border=1  width='100%' height='80'><tr><td>&nbsp;</td></tr></table></td>";
				echo "<td><table><tr><td>_____________________________</td></tr><tr><td align=center>Assinatura do Professor</td></tr></table></td>";
				echo "</tr>";
				echo "</table>";
			if($countDiasLetivos == $num_rows_dias_letivos)
			{
				$c5 = 0; //contador de dias 
				$countDiasLetivos++;
			}
			else
			{
				$countDiasLetivos += ($num_rows_dias_letivos - $countDiasLetivos); 			
			}
		}
		
	}
}

function imprimeCabecalhoFormato1()
{
			global $rowDisciplina;
			echo "<table border=0>";
			echo "<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>";
			echo "<td width=498 valign=top style='width:373.15pt;border-top:solid black;   border-left:solid black;border-bottom:double windowtext;border-right:double windowtext;   border-width:1.0pt;mso-border-top-alt:solid black .5pt;mso-border-left-alt:  solid black .5pt;mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:   double windowtext .75pt;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:   frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:  paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;  mso-height-rule:exactly'>";
			echo "<b style='mso-bidi-font-weight:normal'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Centro</span></b></p>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:  normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:  margin;mso-element-top:4.75pt;mso-height-rule:exactly'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>CENTRO DE CIÊNCIAS EXATAS E TECNOLÓGICAS</span>";
			echo "<span lang=PT-BR style='font-size:8.0pt;  mso-bidi-font-size:9.0pt;font-family:'Cambria','serif''></span></p>";
			echo "</td>";
			echo "<td width=511 colspan=5 valign=top style='width:383.55pt;border:solid black 1.0pt;";
			echo "border-left:none;mso-border-left-alt:double windowtext .75pt;mso-border-alt:";
			echo "solid black .5pt;mso-border-left-alt:double windowtext .75pt;padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
			echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Disciplina</span></b></p>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;mso-height-rule:exactly'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>" . $rowDisciplina["id"] . "-" . $rowDisciplina["nome"] . "</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "<tr style='mso-yfti-irow:1'>";
			echo "<td width=469 valign=top style='width:351.9pt;border-top:none;";
			echo "border-left:solid black 1.0pt;border-bottom:double windowtext 1.0pt;";
			echo "border-right:solid black 1.0pt;mso-border-top-alt:double windowtext .75pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
			echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'><span";
			echo "lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Professor</span></b></p>";
			echo "<p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:";
			echo "normal;mso-element:frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:";
			echo "around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:";
			echo "margin;mso-element-top:4.75pt;mso-height-rule:exactly'><b style='mso-bidi-font-weight:";
			echo "normal'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>" . strtoupper($rowDisciplina["professor"]) . "</span></b></p>";
			echo "</td>";
			echo "<td width=151 valign=top style='width:4.0cm;border-top:none;";
			echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
			echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'><span";
			echo "lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Classe</span></b></p>";
			echo "<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;";
			echo "margin-left:8.75pt;margin-bottom:.0001pt;line-height:normal;mso-element:frame;";
			echo "mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><span lang=PT-BR style='font-size:9.0pt;font-family:";
			echo "'Cambria','serif''>" . strtoupper($rowDisciplina["turma"]) . "</span></p>";
			echo "</td>";
			echo "<td width=198 colspan=3 valign=top style='width:148.85pt;border-top:none;";
			echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
			echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Período Letivo</span></b></p>";
			echo "<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;";
			echo "margin-left:11.15pt;margin-bottom:.0001pt;line-height:normal;mso-element:";
			echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><span lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>" . strtoupper($rowDisciplina["nomeSemestre"]) . "</span></p>";
			echo "</td>";
			echo "<td width=190 valign=top style='width:142.55pt;border-top:none;";
			echo "border-left:none;border-bottom:double windowtext 1.0pt;border-right:solid black 1.0pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-top-alt:double windowtext .75pt;mso-border-left-alt:solid black .5pt;";
			echo "mso-border-bottom-alt:double windowtext .75pt;mso-border-right-alt:solid black .5pt;";
			echo "padding:0cm 5.4pt 0cm 5.4pt'>";
			echo "<p class=MsoNormal style='margin-bottom:3.0pt;line-height:normal;mso-element:";
			echo "frame;mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><b style='mso-bidi-font-weight:normal'><span";
			echo "lang=PT-BR style='font-size:9.0pt;font-family:'Cambria','serif''>Carga Horária</span></b></p>";
			echo "<p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;";
			echo "margin-left:6.45pt;margin-bottom:.0001pt;line-height:normal;mso-element:frame;";
			echo "mso-element-frame-hspace:7.05pt;mso-element-wrap:around;mso-element-anchor-vertical:";
			echo "paragraph;mso-element-anchor-horizontal:margin;mso-element-top:4.75pt;";
			echo "mso-height-rule:exactly'><span lang=PT-BR style='font-size:9.0pt;font-family:";
			echo "'Cambria','serif''>" . strtoupper($rowDisciplina["carga_horaria"]) . "</span></p>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";

}
	
function imprimeCabecalhoFormato2()
{
			global $rowDisciplina;
			
			echo("<table border=1 cellspacing=10 cellpadding=10 align=left width=1018 style='width:763.8pt;border-collapse:collapse;border:none; mso-border-alt:solid black .5pt;mso-yfti-tbllook:1184;mso-table-lspace:7.05pt;");
			echo("margin-left:4.8pt;mso-table-rspace:7.05pt;margin-right:4.8pt;mso-table-anchor-vertical: paragraph;mso-table-anchor-horizontal:margin;mso-table-left:left;mso-table-top:");
			echo("26.15pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt;mso-border-insideh:.5pt solid black; mso-border-insidev:.5pt solid black' >");
			echo("<tr>");
			echo("<td><b>Centro</b> <br> <font size='2'>CENTRO DE CIÊNCIAS EXATAS E TECNOLÓGICAS</font></td>");
			echo("<td><b>Disciplina</b> <br> <font size='2'>" . $rowDisciplina["id"] . "-" . $rowDisciplina["nome"] . "</font></td>");
			echo("<td><b>Classe</b> <br> <font size='2'>" . strtoupper($rowDisciplina["turma"]) . "</font></td>");
			echo("<td><b>Período Letivo</b> <br> <font size='2'>" . strtoupper($rowDisciplina["nomeSemestre"]) . "</font></td>");
			echo("<td><b>Carga Horária</b> <br> <font size='2'>" . $rowDisciplina["carga_horaria"] . "</font></td>");
			echo("</tr>");
			echo("</table>");
}
	
function imprimeTopo($nome)
{
			global $rowDisciplina, $countFolhas, $qtdFolhas;
			
			echo "<table>";
			echo "<tr>";
			echo "<td>";
			echo "<img width=88 height=88 src='imagens/image002.jpg' v:shapes='_x0000_s1036'></span>";
			echo "</td>";
			echo "<td width='700'>";
			echo "<b style='mso-bidi-font-weight:normal'>";
			echo "<span lang='PT-BR' style='font-size:10.0pt; mso-bidi-font-size:9.0pt;line-height:115%;font-family:'Times New Roman','serif'>";
			echo "Universidade Federal do Recôncavo da Bahia";
			echo "</span>";
			echo "</b>";
			echo "<br>";
			echo "<b style='mso-bidi-font-weight:normal'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif'; mso-bidi-font-family:Arial'>";
			echo "SAGRES ACADÊMICO";
			echo "</span>";
			echo "</b>";
			echo "<br>";
			echo "<b style='mso-bidi-font-weight:normal'>";
			echo "<span lang=PT-BR style='font-size:9.0pt;line-height:115%;font-family:'Cambria','serif';mso-bidi-font-family:Arial'>" . $nome . "</span>";
			echo "</b>";
			echo "</td>";
			echo "<td>";
			echo "Página: " . ++$countFolhas . " de " . $qtdFolhas;
			echo "</td>";
			echo "<tr>";
			echo "</table>";


}	
	
?>