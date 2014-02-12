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

require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
$in         = new sigesp_include();
$ds         = new class_datastore();
$con        = $in->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($con);
$io_funcion = new class_funciones();

$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];
if ($li_estmodest=='2')
   {
     $li_maxlength = '2';
   }
else
   {
     $li_maxlength = '3';
   }
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

if (array_key_exists("submit",$_GET))
   {
	 $ls_submit=$_GET["submit"];
   }
else
   {
	 $ls_submit="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?
if ($li_estmodest=='2')
   {?>
     <title>Catálogo de Programatica Nivel 5 <?php print $arr["nomestpro5"] ?></title>
   <?
   }
else
   { ?>
     <title>Catálogo de Programatica Nivel 3 <?php print $arr["nomestpro3"] ?></title>
     <?
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $arr["nomestpro3"] ?></td>
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
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
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
	      $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,20);
  	      $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,6);
		  $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,3);
		}
	if ($li_estmodest=='1')
	   {
		 $ls_sql = "SELECT SUBSTR(spg_ep3.codestpro1,".$li_longestpro1.",25) as codestpro1,
		                   SUBSTR(spg_ep3.codestpro2,".$li_longestpro2.",25) as codestpro2,
		                   SUBSTR(spg_ep3.codestpro3,".$li_longestpro3.",25) as codestpro3,
		                   spg_ep3.denestpro3, spg_ep3.estcla as estcla1,spg_ep3.estcla as estcla2, spg_ep3.estcla as estcla3,
					      (SELECT denestpro1 FROM spg_ep1 WHERE spg_ep1.codestpro1=spg_ep3.codestpro1 AND 
					       spg_ep1.estcla=spg_ep3.estcla) as denestpro1,  
					      (SELECT denestpro2 FROM spg_ep2 WHERE spg_ep2.codestpro1=spg_ep3.codestpro1 AND  
					       spg_ep2.codestpro2=spg_ep3.codestpro2 AND  spg_ep2.estcla=spg_ep3.estcla) as denestpro2 
				    FROM spg_ep3 	
				    WHERE spg_ep3.codestpro3 like '%$ls_codestpro%' AND 
				           spg_ep3.denestpro3 like '%$ls_denestpro%' 
				    ORDER BY codestpro1,codestpro2,codestpro3,spg_ep3.estcla ";
	   }
	else
	   {
		 $ls_sql="SELECT SUBSTR(a.codestpro1,".$li_longestpro1.",25) as codestpro1,a.denestpro1 as denestpro1, SUBSTR(b.codestpro2,".$li_longestpro2.",25) as codestpro2,          ".
		         "		 b.denestpro2 as denestpro2, SUBSTR(c.codestpro3,".$li_longestpro3.",25) as codestpro3,c.denestpro3 as denestpro3,          ".
				 "		  SUBSTR(d.codestpro4,".$li_longestpro4.",25) as codestpro4,d.denestpro4 as denestpro4, SUBSTR(e.codestpro5,".$li_longestpro5.",25) as codestpro5,          ".
	             "		 e.denestpro5 as denestpro5                                                                 ".
				 "  FROM spg_ep1 a,spg_ep2 b,spg_ep3 c, spg_ep4 d, spg_ep5 e                                        ".
				 " WHERE e.codestpro5 like '%".$ls_codestpro."%' AND e.denestpro5 like '%".$ls_denestpro."%'    AND ".
				 "       a.codemp=b.codemp          AND a.codemp=c.codemp AND a.codemp=d.codemp                 AND ".
				 "       a.codemp=e.codemp          AND a.codemp='".$ls_codemp."'                               AND ".
				 "       a.codestpro1=b.codestpro1  AND a.codestpro1=c.codestpro1 AND b.codestpro2=c.codestpro2 AND ".
				 "       a.codestpro1=d.codestpro1  AND a.codestpro1=e.codestpro1 AND b.codestpro2=d.codestpro2 AND ".
				 "       b.codestpro2=e.codestpro2  AND c.codestpro3=d.codestpro3 AND c.codestpro3=e.codestpro3 AND ".
				 "       d.codestpro4=d.codestpro4                                                                  ";
	   }
	$rs_data = $io_sql->select($ls_sql);
	$data    = $rs_data;
	if ($row=$io_sql->fetch_row($rs_data))
	   {
		 $data     = $io_sql->obtener_datos($rs_data);
		 $arrcols  = array_keys($data);
		 $totcol   = count($arrcols);
		 $ds->data = $data;
		 $totrow   = $ds->getRowCount("codestpro3");
		 for ($z=1;$z<=$totrow;$z++)
		     {
			   print "<tr class=celdas-blancas>";
			   $ls_codestpro1 = $data["codestpro1"][$z];
			   $ls_codestpro2 = $data["codestpro2"][$z];
			   $ls_codestpro3 = $data["codestpro3"][$z];
			   $ls_denestpro1 = $data["denestpro1"][$z];
			   $ls_denestpro2 = $data["denestpro2"][$z];
			   $ls_denestpro3 = $data["denestpro3"][$z];
			   if ($li_estmodest=='2')
			      {
					$ls_codestpro4   = $data["codestpro4"][$z];
			        $ls_codestpro5   = $data["codestpro5"][$z];
				    $ls_denestpro4   = $data["denestpro4"][$z];
			        $ls_denestpro5   = $data["denestpro5"][$z];   
			        $ls_denominacion = $ls_denestpro5;
				  }
			   else 
			      {
				    $ls_codestpro4   = str_pad('',25,0,0);
					$ls_codestpro5   = str_pad('',25,0,0);
					$ls_denestpro4   = '';
			        $ls_denestpro5   = '';   
					$ls_denominacion = $ls_denestpro3;
				  }
			   if ($ls_tipo=="")
			      {
				    print "<td width=200 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_submit');\">".trim($ls_codestpro1)."</td>";
				    print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_submit');\">".trim($ls_codestpro2)."</td>";
				    print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_submit');\">".trim($ls_codestpro3)."</a></td>";
				    if ($li_estmodest=='2')
					   {
				         print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_submit');\">".trim($ls_codestpro4)."</td>";
				         print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5','$ls_submit');\">".trim($ls_codestpro5)."</a></td>";
					   }
					print "<td width=180 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "</tr>";	
			      }
			if ($ls_tipo=="apertura")
			   {
				 print "<td width=200 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro1)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro2)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro3)."</a></td>";
				 if ($li_estmodest=='1')
				    {
				      print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro4)."</td>";
				      print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro5)."</a></td>";
					}
				 print "<td width=180 align=\"left\">".trim($ls_denominacion)."</td>";
				 print "</tr>";	
			   }
			if ($ls_tipo=="progrep")
			   {
				 print "<td width=200 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro1)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro2)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro3)."</a></td>";
				 if ($li_estmodest=='2')
				    {
					  print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro4)."</td>";
					  print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro5)."</a></td>";
					}
				 print "<td width=180 align=\"left\">".trim($ls_denominacion)."</td>";
				 print "</tr>";	
			   }
			if ($ls_tipo=="reporte")		
			   {
				 print "<td width=200 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro1)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro2)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro3)."</a></td>";
				 if ($li_estmodest=='2')
				    {
					  print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro4)."</td>";
					  print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro5)."</a></td>";
					}
				 print "<td width=180 align=\"left\">".trim($ls_denominacion)."</td>";
				 print "</tr>";	
			   }
			if ($ls_tipo=="rephas")		
			   {
				 print "<td width=200 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro1)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro2)."</td>";
				 print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro3)."</a></td>";
			     if ($li_estmodest=='2')
				    {
					  print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro4)."</td>";
					  print "<td width=100 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".trim($ls_codestpro5)."</a></td>";
					}
				 print "<td width=180 align=\"left\">".trim($ls_denominacion)."</td>";
				 print "</tr>";	
			   }
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
<script language="JavaScript">
fop = opener.document.form1;
f   = document.form1;

function aceptar(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_codestpro4,ls_denestpro4,ls_codestpro5,ls_denestpro5,ls_submit)
{
	fop.txtcodestpro1.value = ls_codestpro1;
	fop.txtdenestpro1.value = ls_denestpro1;
	fop.txtcodestpro2.value = ls_codestpro2;	
	fop.txtdenestpro2.value = ls_denestpro2;
	fop.txtcodestpro3.value = ls_codestpro3;
	fop.txtdenestpro3.value = ls_denestpro3;
	li_estmodest = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
	     fop.txtcodestpro4.value = ls_codestpro4;	
	     fop.txtdenestpro4.value = ls_denestpro4;
	     fop.txtcodestpro5.value = ls_codestpro5;
	     fop.txtdenestpro5.value = ls_denestpro5;
	   }
	if (ls_submit=='si')
	   {
		 fop.operacion.value="BLANQUEAR";
		 fop.submit();
	   }
	close();
}
  
  function aceptar_progrep(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_codestpro4,ls_denestpro4,ls_codestpro5,ls_denestpro5)
  {
    fop.denestpro1.value = ls_denestpro1;
	fop.codestpro1.value = ls_codestpro1;
    fop.denestpro2.value = ls_denestpro2;
	fop.codestpro2.value = ls_codestpro2;
    fop.denestpro3.value = ls_denestpro3;
	fop.codestpro3.value = ls_codestpro3;
	li_estmodest = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
	     fop.codestpro4.value = ls_codestpro4;	
	     fop.denestpro4.value = ls_denestpro4;
	     fop.codestpro5.value = ls_codestpro5;
	     fop.denestpro5.value = ls_denestpro5;
	   }
	fop.operacion.value  = "CARGAR";
    fop.submit();
	close();
  }
  
  function aceptar_apertura(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_codestpro4,ls_denestpro4,ls_codestpro5,ls_denestpro5)
  {
    fop.denestpro1.value = ls_denestpro1;
	fop.codestpro1.value = ls_codestpro1;
    fop.denestpro2.value = ls_denestpro2;
	fop.codestpro2.value = ls_codestpro2;
    fop.denestpro3.value = ls_denestpro3;
	fop.codestpro3.value = ls_codestpro3;
 	li_estmodest = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
	     fop.codestpro4.value = ls_codestpro4;	
	     fop.denestpro4.value = ls_denestpro4;
	     fop.codestpro5.value = ls_codestpro5;
	     fop.denestpro5.value = ls_denestpro5;
	   }
	fop.operacion.value  = "CARGAR";
    fop.submit();
	close();
  }
  
  function aceptar_rep(ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5)
  {
	fop.codestpro1.value    = ls_codestpro1;
	fop.codestpro2.value    = ls_codestpro2;
	fop.codestpro3.value    = ls_codestpro3;
	fop.codestpro3.readOnly = true;
	li_estmodest            = "<?php print $li_estmodest ?>";
	if (li_estmodest=='2')
	   {
	     fop.codestpro4.value = ls_codestpro4;	
	     fop.codestpro5.value = ls_codestpro5;
	   }
	close();
  }
  
  function aceptar_rephas(ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5)
  {
	fop.codestpro1.value     = ls_codestpro1;
	fop.codestpro2.value     = ls_codestpro2;
	fop.codestpro3h.value    = ls_codestpro3;
	fop.codestpro3h.readOnly = true;
	if (li_estmodest=='2')
	   {
	     fop.codestpro4.value = ls_codestpro4;	
	     fop.codestpro5.value = ls_codestpro5;
	   }
	close();
  }

function ue_search()
{
	f.operacion.value="BUSCAR";
	f.action="sigesp_cat_public_estpro.php?tipo=<?php print $ls_tipo; ?>";
	f.submit();
}
</script>
</html>