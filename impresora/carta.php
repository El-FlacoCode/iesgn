<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"]."iesgn/fpdf/mpdf1_3/mpdf.php");
include ($_SERVER["DOCUMENT_ROOT"]."iesgn/includes/config.inc");
include ($_SERVER["DOCUMENT_ROOT"]."iesgn/includes/funciones.inc");
include ($_SERVER["DOCUMENT_ROOT"]."iesgn/includes/verify.inc");
permisos("General");

$sql="select * from Cartas where Id=".$_GET["l"];
$result=mysql_query($sql);
if($row=mysql_fetch_array($result))
{
if($_GET["l"]==1 ) //Amonestaciones
{
	$sql="select * from Alumnos,Partes where Alumnos.Id=Partes.Ida and Partes.Tipo='a' and Partes.Fecha='".cambiaf_a_mysql($_GET["f"])."' group by Partes.Ida order by Alumnos.Nombre";
	
		$result2=mysql_query($sql) or die("error:".$sql);

	$mpdf=new mPDF();
$cont=1;
	while($row2=mysql_fetch_array($result2))
	{
		if($cont==$row["Pag"])
		{
			$mpdf->AddPage();
			$cont=0;
		}
	   
		$html=cambia($row2[0],$row["Contenido"],"Alumnos");
		$html=cambia($row2[15],$html,"Partes");
		$mpdf->WriteHTML($html,2);
		
		$cont++;
		
	}
// Output a PDF file:
	$mpdf->Output('filename.pdf','I');
}

if($_GET["l"]==3 ) //Sanciones
{
	$sql="select * from Alumnos,Partes where Alumnos.Id=Partes.Ida and Partes.Id=".$_GET["id"];

	
		$result2=mysql_query($sql) or die("error:".$sql);

	$mpdf=new mPDF();

$cont=1;
	while($row2=mysql_fetch_array($result2))
	{
		if($cont==$row["Pag"])
		{
			$mpdf->AddPage();
			$cont=0;
		}
	   
		$html=cambia($row2[0],$row["Contenido"],"Alumnos");
		$html=cambia($row2[15],$html,"Partes");
		$mpdf->WriteHTML($html,2);
		
		$cont++;
		
	}
// Output a PDF file:
	$mpdf->Output('filename.pdf','I');
}



if($_GET["l"]	==2) //Mayor de edad
{
	
		if($_SESSION["sql"]) $sql=$_SESSION["sql"]; else $sql="select * from Alumnos";
		$result2=mysql_query($sql) or die("error:".$sql);
		$mpdf=new mPDF();
$mpdf->AddPage();
$cont=0;
	while($row2=mysql_fetch_array($result2))
	{
		$edad=edad(cambiaf_a_normal($row2["Fecha_nacimiento"]));
	if($edad>18)
		{
		$mpdf->WriteHTML(cambia($row2["Id"],$row["Contenido"]),2);
		$cont++;
		if($cont==$row["Pag"])
		{
			$mpdf->AddPage();
			$cont=0;
		}
		}
	}
// Output a PDF file:
	$mpdf->Output('filename.pdf','I');
}
if($_GET["l"]	==4) //Revision libros alumnos
{
		$sql="select * from Alumnos where Unidad='".$_GET["uni"]."'";
		$result2=mysql_query($sql) or die("error:".$sql);
		$mpdf=new mPDF();
		
		
		while($row2=mysql_fetch_array($result2))
		{
			$sql="select * from LibrosAlumnos where Id=".$row2["Id"];
			
			$result3=mysql_query($sql) or die("error:".$sql);
			if($row3=mysql_fetch_array($result3))
			{
				$mpdf->AddPage();
				$html=cambia($row3["Id"],$row["Contenido"],"Alumnos","Nombre");
				$mpdf->WriteHTML($html,2);
			}
		}
		$mpdf->Output('filename.pdf','I');
}




if($_GET["l"]	==5) //Partes de falta
{
		if($_GET["uni"][0]=='C' or $_GET["uni"][0]=='D')
		{
			
			$sql="select * from Cursos where Abr='".$_GET["uni"]."'";
			$result3=mysql_query($sql);
			if($row3=mysql_fetch_array($result3)) $c=$row3["Id"];
			$sql="select * from Alumnos,CompDiv where Id=Ida and Idc=".$c;
			$result2=mysql_query($sql) or die("error:".$sql);
		if($row2=mysql_fetch_array($result2))
		{
			
			$sql="select * from Alumnos,CompDiv where Id=Ida and Idc=".$c." and Id=".$row2["Id"];
			$mpdf=new mPDF();
			//$mpdf=new mPDF('win-1252','a4-l');
			$mpdf->AddPage();
			
			$html=cambia($row2["Id"],$row["Contenido"],"CompDiv","",$sql);
			$mpdf->WriteHTML($html,2);
		}
		$mpdf->Output('filename.pdf','I');
		}
		else
		{
		$sql="select * from Alumnos where Unidad='".$_GET["uni"]."'";
		
		$result2=mysql_query($sql) or die("error:".$sql);
		if($row2=mysql_fetch_array($result2))
		{
			
			
			$mpdf=new mPDF();
			//$mpdf=new mPDF('win-1252','a4-l');
			$mpdf->AddPage();
			$html=cambia($row2["Id"],$row["Contenido"],"Alumnos");
			$mpdf->WriteHTML($html,2);
		}
		$mpdf->Output('filename.pdf','I');
		}
	}
		if($_GET["l"]	==6) //Sitación
{
		$sql="select * from Alumnos where Id=".$_GET["id"];
		$result2=mysql_query($sql) or die("error:".$sql);
		$mpdf=new mPDF();
		
		
		
			
			
			if($row3=mysql_fetch_array($result2))
			{
				$mpdf->AddPage();
				$html=cambia($row3["Id"],$row["Contenido"],"Alumnos","Nombre");
				$mpdf->WriteHTML($html,2);
			}
		
		$mpdf->Output('filename.pdf','I');
}

if($_GET["l"]==7) //Revision libros libros
{
		
		$sql="select * from Libros where Curso=".substr($_GET["uni"],0,1)." order by Id";

		$result2=mysql_query($sql) or die("error:".$sql);
		$mpdf=new mPDF();
		
		
		while($row2=mysql_fetch_array($result2))
		{
			$sql="select * from Alumnos,LibrosAlumnos where Alumnos.Id=LibrosAlumnos.Id and Idl=".$row2["Id"]." and Unidad='".$_GET["uni"]."'";
			
			$result3=mysql_query($sql) or die("error:".$sql);
			if($row3=mysql_fetch_array($result3))
			{
				$mpdf->AddPage();
				$html=cambia($row2["Id"],$row["Contenido"],"Libros","Nombre");
				$html=cambia($row2["Id"],$html,"","","select * from Alumnos where Unidad='".$_GET["uni"]."'");
				
				$mpdf->WriteHTML($html,2);
			}
		}
		$mpdf->Output('filename.pdf','I');
}


}
mysql_close();
?>
