<?
if($_POST)
{
	include ($_SERVER["DOCUMENT_ROOT"]."/iesgn/includes/config.inc");
	
	$cont_error=0;
	
	
	//Validacion de fecha
	$datos=explode("/",$_POST["fecha"]);
	$v=checkdate((int)$datos[1],(int)$datos[0],(int)$datos[2]);
		if(!$v)
	{
		$cont_error++;
		$error_fecha=true;
	}
	
	if(!is_numeric($_POST["cantidad"]))
	{
		$cont_error++;
		$error_cantidad=true;
	}
	
	
	
	//Si todo ok
	if($cont_error==0)
	{
		
		$sql="INSERT INTO Contabilidad(Idcg,Fecha,Concepto,Cantidad) VALUES (".$_POST["cg"].",'".cambia_a_mysql($_POST["fecha"])."','".$_POST["concepto"]."',".$_POST["cantidad"].")";
		
		mysql_query($sql) or die("Error en SQL:".$sql."<br>".mysql_error($bd));	
		mysql_close();
		header("Location:contabilidad.php?cg=".$_POST["cg"]);
		
	
	}	
}

if(!$_POST || $cont_error>0)
{
include ($_SERVER["DOCUMENT_ROOT"]."/iesgn/includes/header.inc");
permisos("contabilidad","A",1);


?>
     
	<h1>Nuevo Registro de Contabilidad</h1>
	<form name="for" action="nuevo.php" method="post">
	<?
	$vcg=$_GET["cg"];
	if($_POST["cg"]) $vcg=$_POST["cg"];
	if($_POST["fecha"]) $vf=$_POST["fecha"]; else $vf=date("d/m/Y");
	
	echo "<input type=\"hidden\" name=\"cg\"  value=\"".$vcg."\">";
	$sql="select * from CentroGastos where Id=".$vcg;
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result)) $dep=$row[2];
	echo "<br><h3>".$dep."</h3>";
	
	
		echo "Fecha:<br>";
	//Fecha
		if($error_fecha) 
		{
			echo "<input type=\"text\" size=\"10\" name=\"fecha\">"; 
			echo "Fecha incorrecta.";
			echo "<a href=\"javascript:cal1.popup();\">".imagen("cal.gif")."</a><br><br>";
		}		
		else
		{	
			echo "<input type=\"text\" size=\"10\" name=\"fecha\" value=\"".$vf."\">";
			echo "<a href=\"javascript:cal1.popup();\">".imagen("cal.gif")."</a><br><br>";
		}
		
		
	echo "Concepto:<br><input type=\"text\" size=\"70\" name=\"concepto\" value=\"".$_POST["concepto"]."\"><br>";
	if(!$error_cantidad)
		echo "Cantidad:<br><input type=\"text\" size=\"10\" name=\"cantidad\" value=\"".$_POST["contidad"]."\"><br>";
	else
		echo "Contidad Incorrecta:<br><input type=\"text\" size=\"10\" name=\"cantidad\"><br>";
	?>
	<br>
	<br>
	<input type="submit" value="Listo!!!">
	</form>
<?
}include ($_SERVER["DOCUMENT_ROOT"]."/iesgn/includes/footer.inc");
?>
<script language="JavaScript">
<!-- // create calendar object(s) just after form tag closed
	 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
	 // note: you can have as many calendar objects as you need for your application
	
	var cal1 = new calendar1(document.forms['for'].elements['fecha']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
	
//-->
</script>	
<?function cambia_a_mysql($fecha){
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
}
function curso2($f=0)
{
	if($f==0)
	{
	if(date("m")<9) $curso=(date("Y")-1)."-".date("Y");
	else $curso=date("Y")."-".(date("Y")+1);
	}
	else
	{
	$datos=explode("/",$f);	
	if($datos[1]<9) $curso=($datos[2]-1)."-".$datos[2];
	else $curso=$datos[2]."-".($datos[2]+1);
	}
  return $curso;
}  


function calcula_id2($t,$c)
{
	$sql="select * from Registro where Tipo='".$t."' and Curso='".$c."' order by Id desc";
	
$result=mysql_query($sql);
if($row=mysql_fetch_array($result)) return ($row["Id"]+1); else return (1);
	
} ?>
