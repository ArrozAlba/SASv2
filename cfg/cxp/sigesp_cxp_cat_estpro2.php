<?php
session_start();
$arr=$_SESSION["la_empresa"];
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
$in           = new sigesp_include();
$con          = $in->uf_conectar();
$io_msg       = new class_mensajes();
$ds           = new class_datastore();
$io_sql       = new class_sql($con);
$io_funcion   = new class_funciones();
$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];

if ($li_estmodest=='1')
   {
	/* $li_maxlength_1 = '20';
	 $li_maxlength_2 = '6';
	 $li_size        = '25';
	 $li_ancho       = '50';*/
   }
else
   {
/*	 $li_maxlength_1 = '2';
	 $li_maxlength_2 = '2';
	 $li_size        = '5';
	 $li_ancho       = '70';*/
   }

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_estcla=$_POST["txtestcla1"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["txtcodestpro1"];
	 $ls_denestpro1 = $_GET["txtdenestpro1"];
	 $ls_estcla     = $_GET["txtestcla1"];
	 $ls_codestpro2 = "";
	 $ls_denestpro2 = "";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 2 <?php print $arr["nomestpro2"] ?> </title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $arr["nomestpro2"] ?></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="99" height="22"><div align="right"><?php print $arr["nomestpro1"]?></div></td>
        <td width="449" height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="<?php print $li_ancho ?>" value="<?php print $ls_denestpro1 ?>" readonly style="text-align:left">
          <input name="txtestcla1" type="text" id="txtestcla1" value="<?php print $ls_estcla ?>" size="2">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength_2 ?>" style="text-align:center">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" size="72" maxlength="100" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codestpro1))
	    {
	      $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
		}
	 $ls_sql = "SELECT substr(codestpro1,".$li_longestpro1.",25) as codestpro1 ,substr(codestpro2,".$li_longestpro2.",25) as codestpro2 ,denestpro2,spg_ep2.estcla as estcla ".
	           "  FROM spg_ep2 ".
			   " WHERE codemp='".$ls_codemp."'                AND codestpro1 ='".$ls_codestpro1."'  AND  estcla='".$ls_estcla."'   AND ".
			   "       codestpro2 like '%".$ls_codestpro2."%' AND denestpro2 like '%".$ls_denestpro2."%'    ";
	 $rs_cta = $io_sql->select($ls_sql);
	 $data   = $rs_cta;
	 if ($row=$io_sql->fetch_row($rs_cta))
	    {
		  $data     = $io_sql->obtener_datos($rs_cta);
		  $arrcols  = array_keys($data);
		  $totcol   = count($arrcols);
		  $ds->data = $data;
		  $totrow   = $ds->getRowCount("codestpro1");
	 	  for ($z=1;$z<=$totrow;$z++)
		      {
			    print "<tr class=celdas-blancas>";
			    $ls_codestpro1 = $data["codestpro1"][$z];
			    $ls_codestpro2 = $data["codestpro2"][$z];
			    $ls_estcla     = $data["estcla"][$z];
				$ls_denestpro2 = $data["denestpro2"][$z];
			    print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
			    print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2','$ls_estcla');\">".trim($ls_codestpro2)."</a></td>";
			    print "<td width=130 align=\"left\">".trim($ls_denestpro2)."</td>";
		 	    print "</tr>";			
		      }
	    }
	 else
	    {
		  $io_msg->message("No se han definido ".$arr["nomestpro2"]);
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
  function aceptar(ls_codestpro2,ls_denestpro2,ls_estcla2)
  {
	fop.txtcodestpro2.value = ls_codestpro2;
    fop.txtdenestpro2.value = ls_denestpro2;
    fop.txtestcla2.value    = ls_estcla2
	ls_maestro              = fop.hidmaestro.value; 
	if (ls_maestro=='Y')
	   {
	     fop.operacionestprog2.value = "BUSCAR";
	     fop.statusprog2.value       = 'C';
	   }
	fop.txtcodestpro2.readOnly  = true;
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cxp_cat_estpro2.php";
  f.submit();
  }
</script>
</html>
