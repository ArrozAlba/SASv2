<?php
session_start();
$arr=$_SESSION["la_empresa"];
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$ls_loncodestpro3)+1;
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");

$in           = new sigesp_include();
$con          = $in->uf_conectar();
$io_msg       = new class_mensajes();
$io_funcion   = new class_funciones();
$ds           = new class_datastore();
$io_sql       = new class_sql($con);
$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
	 $ls_codestpro3 = $_POST["txtcodestpro3"];
	 $ls_denestpro3 = $_POST["txtdenestpro3"];
	 $ls_estcla     = $_POST["txtestcla2"];
   }
else
   { 
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["txtcodestpro1"];
	 $ls_denestpro1 = $_GET["txtdenestpro1"];
	 $ls_codestpro2 = $_GET["txtcodestpro2"];
	 $ls_denestpro2 = $_GET["txtdenestpro2"];
	 $ls_estcla     = $_GET["txtestcla2"];
	 $ls_codestpro3 = "";
	 $ls_denestpro3 = ""; 
   }

if ($li_estmodest=='1')
   {
	/* $li_maxlength_1 = '20';
	 $li_maxlength_2 = '6';
	 $li_maxlength_3 = '3';
	 $li_size        = '25';
	 $ls_ancho       = '55';*/
   }
else
   {
	/* $li_maxlength_1 = '2';
	 $li_maxlength_2 = '2';
	 $li_maxlength_3 = '2';
	 $li_size        = '5';
	 $ls_ancho       = '75';*/
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 3 <?php print $arr["nomestpro3"] ?> </title>
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
	 <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $arr["nomestpro3"]."this" ?></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right"><?php print $arr["nomestpro1"]?></div></td>
        <td width="432" height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="<?php print $ls_ancho ?>" value="<?php print $ls_denestpro1 ?>" readonly>
          <input name="txtestcla1" type="text" id="txtestcla1" value="<?php print $ls_estcla ?>" size="2">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $arr["nomestpro2"]?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print  $ls_codestpro2?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_2 ?>" readonly style="text-align:center">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" value="<?php print $ls_denestpro2?>" size="<?php print $ls_ancho ?>" class="sin-borde" readonly>
          <input name="txtestcla2" type="text" id="txtestcla2" value="<?php print $ls_estcla ?>" size="2">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td height="22"><input name="txtcodestpro3" type="text" id="txtcodestpro3"  size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_3 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro3" type="text" id="txtdenestpro3"  size="72" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
         <?php

print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td  width=150>".$arr["nomestpro1"]."</td>";
print "<td  width=100>".$arr["nomestpro2"]."</td>";
print "<td  width=50>Código </td>";
print "<td  width=300>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codestpro1) && !empty($ls_codestpro2))
	    {
	      $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
  	      $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
		}
	  $ls_sql="SELECT substr(codestpro1,".$li_longestpro1.",25) as codestpro1 ,substr(codestpro2,".$li_longestpro2.",25) as codestpro2 ,
	          substr(codestpro3,".$li_longestpro3.",25) as codestpro3,denestpro3,estcla,sigesp_fuentefinanciamiento.codfuefin,
			  sigesp_fuentefinanciamiento.denfuefin
				FROM spg_ep3,sigesp_fuentefinanciamiento
				WHERE spg_ep3.codfuefin=sigesp_fuentefinanciamiento.codfuefin ".
			 "AND spg_ep3.codemp='".$ls_codemp."'   AND   estcla='".$ls_estcla."'       AND codestpro1 ='".$ls_codestpro1."'       AND ".
			 "       codestpro2 ='".$ls_codestpro2."' AND codestpro3 like '%".$ls_codestpro3."%' AND ".
			 "       denestpro3 like '%".$ls_denestpro3."%'                                          ".
			 " ORDER BY codestpro3  ";	
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
			   $ls_denestpro3 = $data["denestpro3"][$z];
			   $ls_estcla     =$data["estcla"][$z];
			   print "<td width=150 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
			   print "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
			   print "<td width=50 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
			   print "<td width=300 align=\"left\">".trim($ls_denestpro3)."</td>";
			   print "</tr>";			
		     }
	   }
	else
	   {
		 $io_msg->message("No se han definido ".$arr["nomestpro3"]);
	   }
}
print "</table>";
?>
       </div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
fop = opener.document.form1; 

function aceptar(ls_codestpro3,ls_denestpro3,ls_estcla3)
{
	fop.txtcodestpro3.value    = ls_codestpro3;
	fop.txtcodestpro3.readOnly = true;
	fop.txtdenestpro3.value    = ls_denestpro3;
	fop.txtestcla3.value       = ls_estcla3;
	ls_maestro                 = fop.hidmaestro.value; 
	if (ls_maestro=='Y')
	   {
		 fop.operacionestprog3.value = "BUSCAR";
		 fop.statusprog3.value       = 'C';   
	   }
	close();
}
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_cxp_cat_estpro3.php";
	f.submit();
}
</script>
</html>
