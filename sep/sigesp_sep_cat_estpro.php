<?php
session_start();
$arr=$_SESSION["la_empresa"];
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$ls_loncodestpro3)+1;
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_longestpro4= (25-$ls_loncodestpro4)+1;
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
$li_longestpro5= (25-$ls_loncodestpro5)+1;

$ls_ancho='50';
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
$in         = new sigesp_include();
$ds         = new class_datastore();
$con        = $in->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($con);
$io_funcion = new class_funciones();

$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];


if(array_key_exists("operacion",$_POST))
{
	$ls_operacion = $_POST["operacion"];
	$ls_codestpro = $_POST["txtcodestpro"];
	$ls_denestpro = $_POST["txtdenestpro"];
	
	if (array_key_exists("tipo",$_GET))
	   {
		 $ls_tipo = $_GET["tipo"];
	   }
	else
	   {
		 $ls_tipo = "";
	   }
}
else
{
	$ls_operacion="BUSCAR";
	if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];
	}
	else
	{
		$ls_tipo="";
	}
	$ls_codestpro = "";
	$ls_denestpro = "";
}

   
if (array_key_exists("coduniadmin",$_GET))
   {
	 $ls_coduniadmin=$_GET["coduniadmin"];
   }
else
   {
	 $ls_coduniadmin="";
   }
   
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
if ($li_estmodest==2)
   {?>
     <title>Catálogo de Programatica Nivel 5 <?php print $arr["nomestpro5"] ?></title>
   <?php
   }
else
   { ?>
     <title>Catálogo de Programatica Nivel 3 <?php print $arr["nomestpro3"] ?></title>
     <?php
   }
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../js/funciones_configuracion.js"></script>

</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        <?php
if ($li_estmodest==2)
   {?>
     Catálogo de Programatica Nivel 5 <?php print $arr["nomestpro5"] ?>
   <?php
   }
else
   { ?>
     Catálogo de Programatica Nivel 3 <?php print $arr["nomestpro3"] ?>
     <?php
   }
?>
</td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right">Codigo</div></td>
        <td width="380" height="22"><input name="txtcodestpro" type="text" id="txtcodestpro"  size="22" maxlength="<?php print $li_maxlength ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro" type="text" id="txtdenestpro"  size="72" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=780 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
if ($li_estmodest=='1')
   {
     print "<td>Código </td>";
     print "<td>Denominación</td>";
	 print "<td>Tipo</td>";
   }
else
   {
     print "<td>".$arr["nomestpro3"]."</td>"; 
     print "<td>".$arr["nomestpro4"]."</td>";
     print "<td>Código </td>";
     print "<td>Denominación</td>";
   }
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codestpro1) && !empty($ls_codestpro2) && !empty($ls_codestpro3))
	    {
		  $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
  	      $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
		  $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
		}
	if ($li_estmodest==1)
	   {
	     $ls_sql="SELECT SUBSTR(spg_dt_unidadadministrativa.codestpro1,".$li_longestpro1.",25) AS codestpro1,		 
				         SUBSTR(spg_dt_unidadadministrativa.codestpro2,".$li_longestpro2.",25) AS codestpro2,						 
						 SUBSTR(spg_dt_unidadadministrativa.codestpro3,".$li_longestpro3.",25) AS codestpro3,						 
						 SUBSTR(spg_dt_unidadadministrativa.codestpro4,".$li_longestpro4.",25) AS codestpro4,						 
						 SUBSTR(spg_dt_unidadadministrativa.codestpro5,".$li_longestpro5.",25) AS codestpro5,						 
						 (SELECT denestpro1 FROM spg_ep1 WHERE spg_ep1.codestpro1=spg_dt_unidadadministrativa.codestpro1 AND spg_dt_unidadadministrativa.estcla=spg_ep1.estcla) AS denestpro1,						
       					 (SELECT denestpro2 FROM spg_ep2 WHERE spg_ep2.codestpro1=spg_dt_unidadadministrativa.codestpro1 AND spg_ep2.codestpro2=spg_dt_unidadadministrativa.codestpro2 AND spg_dt_unidadadministrativa.estcla=spg_ep2.estcla) AS denestpro2,						
       					 spg_ep3.denestpro3 as denestpro3,
						 spg_dt_unidadadministrativa.estcla AS estcla
				    FROM spg_unidadadministrativa, spg_dt_unidadadministrativa, spg_ep3
				   WHERE spg_unidadadministrativa.coduniadm='".$ls_coduniadmin."' 
				     AND spg_unidadadministrativa.coduniadm=spg_dt_unidadadministrativa.coduniadm 
				     AND spg_ep3.codestpro1=spg_dt_unidadadministrativa.codestpro1
				     AND spg_ep3.codestpro2=spg_dt_unidadadministrativa.codestpro2
				     AND spg_ep3.codestpro3=spg_dt_unidadadministrativa.codestpro3
				     AND spg_ep3.estcla=spg_dt_unidadadministrativa.estcla";
	   }
	else
	{
		 $ls_sql="  SELECT SUBSTR(spg_dt.codestpro1,".$li_longestpro1.",25) AS codestpro1, 
						   SUBSTR(spg_dt.codestpro2,".$li_longestpro2.",25) AS codestpro2, 
						   SUBSTR(spg_dt.codestpro3,".$li_longestpro3.",25) AS codestpro3, 
						   SUBSTR(spg_dt.codestpro4,".$li_longestpro4.",25) AS codestpro4, 
						   SUBSTR(spg_dt.codestpro5,".$li_longestpro5.",25) AS codestpro5, 
						   (SELECT denestpro1 FROM spg_ep1 WHERE spg_ep1.codestpro1=spg_dt.codestpro1 AND spg_dt.estcla=spg_ep1.estcla) AS denestpro1, 
      					   (SELECT denestpro2 FROM spg_ep2 WHERE spg_ep2.codestpro1=spg_dt.codestpro1 AND spg_ep2.codestpro2=spg_dt.codestpro2 AND spg_dt.estcla=spg_ep2.estcla) AS denestpro2, 
       					   (SELECT denestpro3 FROM spg_ep3 WHERE spg_ep3.codestpro1=spg_dt.codestpro1 AND spg_ep3.codestpro2=spg_dt.codestpro2 AND spg_ep3.codestpro3=spg_dt.codestpro3 AND spg_dt.estcla=spg_ep3.estcla) AS denestpro3, 
                           (SELECT denestpro4 FROM spg_ep4 WHERE spg_ep4.codestpro1=spg_dt.codestpro1 AND spg_ep4.codestpro2=spg_dt.codestpro2 AND spg_ep4.codestpro3=spg_dt.codestpro3 AND spg_ep4.codestpro4=spg_dt.codestpro4 AND spg_dt.estcla=spg_ep4.estcla) AS denestpro4,
						   spg_ep5.denestpro5 as denestpro5,spg_dt.estcla AS estcla 
					  FROM spg_unidadadministrativa AS spg, spg_dt_unidadadministrativa AS spg_dt,spg_ep5 
				  	 WHERE spg.coduniadm=spg_dt.coduniadm AND spg_ep5.codestpro5=spg_dt.codestpro5 
					   AND spg_ep5.codestpro1=spg_dt.codestpro1 
					   AND spg_ep5.codestpro2=spg_dt.codestpro2 
					   AND spg_ep5.codestpro3=spg_dt.codestpro3
					   AND spg_ep5.codestpro4=spg_dt.codestpro4
					   AND spg_dt.estcla=spg_ep5.estcla
				       AND spg.coduniadm='".$ls_coduniadmin."'";	
	}
	$rs_data = $io_sql->select($ls_sql);
		while ($row=$io_sql->fetch_row($rs_data))
		     {
			   print "<tr class=celdas-blancas>";
			   $ls_codestpro1 = $row["codestpro1"];
			   $ls_denestpro1 = $row["denestpro1"];
			   $ls_codestpro2 = $row["codestpro2"];
			   $ls_denestpro2 = $row["denestpro2"];
			   $ls_codestpro3 = $row["codestpro3"];
			   $ls_denestpro3 = $row["denestpro3"];
			   $ls_estcla     = $row["estcla"]; 
			   if ($li_estmodest=='2')
			      {
					$ls_codestpro4   = $row["codestpro4"];
					$ls_denestpro4   = $row["denestpro4"];
			        $ls_codestpro5   = $row["codestpro5"];
			        $ls_denestpro5   = $row["denestpro5"]; 
			        $ls_denominacion = $ls_denestpro5;
				  }
			   else 
			      {			        
					$ls_codestpro4   = str_pad('',25,0);
			        $ls_codestpro5   = str_pad('',25,0);
					$ls_denominacion = $ls_denestpro3;
				  }
			   if ($ls_tipo=="")
			      {
				    print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3',$ls_codestpro4,'',$ls_codestpro5,'','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
				    print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3',$ls_codestpro4,'',$ls_codestpro5,'','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
				    print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3',$ls_codestpro4,'',$ls_codestpro5,'','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
				    if ($li_estmodest=='2')
					   {
				         print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro4)."</td>";
				         print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro5)."</a></td>";
						 print "<td width=180 align=\"left\">".trim($ls_denominacion)."</td>";
					   }
				   else
					   {
							print "<td width=80 align=\"left\">".trim($ls_denominacion)."</td>";
							if ($ls_estcla=='P')
							   {
							     print "<td width=60 align=\"left\">PROYECTO</td>";
							   }
							elseif($ls_estcla=='A')
							   {
							     print "<td width=60 align=\"left\"> ACCION</td>";
							   }
						}
				    print "</tr>";	
			      }
			} 
} 
print "</table>";
?>
</div>
</form>


<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="javascript">
f   = document.form1;
fop = opener.document.formulario;

function aceptar(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_codestpro4,ls_denestpro4,ls_codestpro5,ls_denestpro5,ls_estcla)
{
	fop.txtestcla.value     = ls_estcla;
	fop.txtcodestpro1.value = ls_codestpro1;
	fop.txtdenestpro1.value = ls_denestpro1;
	fop.txtcodestpro2.value = ls_codestpro2;	
	fop.txtdenestpro2.value = ls_denestpro2;
	fop.txtcodestpro3.value = ls_codestpro3;
	fop.txtdenestpro3.value = ls_denestpro3;
		
	li_estmodest = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')//Presupuesto por Programas.
	   {
	     fop.txtcodestpro4.value = ls_codestpro4;	
	     fop.txtdenestpro4.value = ls_denestpro4;
	     fop.txtcodestpro5.value = ls_codestpro5;
	     fop.txtdenestpro5.value = ls_denestpro5;	   
	   }
	close();
}
  
  
</script>
</html>