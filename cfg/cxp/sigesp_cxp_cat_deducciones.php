<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Deducciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body>
<table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Deducciones</td>
    </tr>
</table>
  <div align="center"><br>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_conect = new sigesp_include();
$con       = $io_conect->uf_conectar();
$io_data   = new class_datastore();
$io_sql    = new class_sql($con);
$arr       = $_SESSION["la_empresa"];
$ls_codemp = $arr["codemp"];
$ls_sql    ="SELECT a.*,b.denominacion,".
			"      (SELECT desact FROM sigesp_conceptoretencion".
			"        WHERE a.codemp=sigesp_conceptoretencion.codemp".
			"          AND a.codconret=sigesp_conceptoretencion.codconret) AS desact".
			"  FROM sigesp_deducciones a,scg_cuentas b".
            " WHERE a.codemp='".$ls_codemp."'".
			"   AND a.codemp=b.codemp".
			"   AND a.sc_cuenta=b.sc_cuenta ".
            " ORDER BY a.codded ASC";
$rs_data   = $io_sql->select($ls_sql);
$data      = $rs_data;
if ($row=$io_sql->fetch_row($rs_data))
   {
     $data    = $io_sql->obtener_datos($rs_data);
	 $arrcols = array_keys($data);
     $totcol  = count($arrcols);
     $io_data->data=$data;
     $totrow  = $io_data->getRowCount("codded");
	 print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td height=22>Código</td>";
	 print "<td height=22>Denominación</td>";
 	 print "<td height=22>SCG</td>";
	 print "<td height=22>Deducible</td>";
	 print "<td height=22>Fórmula</td>";
	 print "</tr>";	
	 for ($z=1;$z<=$totrow;$z++)
		 {
			print "<tr class=celdas-blancas>";
			$ls_codded = $data["codded"][$z];
			$ls_dended = $data["dended"][$z];
			$ld_porded = $data["porded"][$z];
			$ls_cuenta = $data["sc_cuenta"][$z];
			$ls_dencue = $data["denominacion"][$z];
			$ld_monded = number_format($data["monded"][$z],2,',','.');
			$ls_forded = $data["formula"][$z];
			$li_islr   = $data["islr"][$z];
			$li_iva    = $data["iva"][$z];
			$li_impmun = $data["estretmun"][$z];
 		    $li_otras  = $data["otras"][$z];
			$tipopersdeduccion  = $data["tipopers"][$z];
			$li_retaposol = $data["retaposol"][$z];
			$ls_codconret = $data["codconret"][$z];
			$ls_denconret = $data["desact"][$z];
			if ($li_islr==1)
			   {
				 $ls_tipded = "S";
		       }
			else
			if ($li_iva==1)
			   {
			     $ls_tipded = "I";
			   }
			else
			if ($li_impmun==1)
			   {
			     $ls_tipded = "M";
			   }
			if ($li_otras==1)
			   {
			     $ls_tipded = "O";
			   } 
			if ($li_retaposol==1)
			   {
				 $ls_tipded = "A";
			   }	   		
		    print "<td style=text-align:center><a href=\"javascript: aceptar('$ls_codded','$ls_dended','$ld_porded','$ls_cuenta','$ls_dencue','$ld_monded','$ls_forded','$ls_tipded','$tipopersdeduccion','$ls_codconret','$ls_denconret');\">".$ls_codded."</a></td>";
			print "<td style=text-align:left>".$ls_dended."</td>";
			print "<td style=text-align:right>".$ls_cuenta."</td>";
			print "<td style=text-align:right width=100>".$ld_monded."</td>";
			print "<td style=text-align:center>".$ls_forded."</td>";
			print "</tr>";			
		}//End del For...
print "</table>";
$io_sql->free_result($rs_data);
}//End del if($row=$io_sql->fetch_row($rs_data))... 
else
   {
   ?>
    <script language="javascript">
	alert("No se han creado Deducciones !!!");
	close();
	</script>
   <?php
   }
?>
  </div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,porcentaje,cuenta,denocuenta,deducible,formula,tipodeduccion,tipopersdeduccion,ls_codconret,ls_denconret)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.txtporcentaje.value=porcentaje;
	opener.document.form1.txtcuentaplan.value=cuenta;
    opener.document.form1.txtdencuentaplan.value=denocuenta;
    opener.document.form1.txtcodconret.value=ls_codconret;
    opener.document.form1.txtdenconret.value=ls_denconret;
	if (tipodeduccion=="S")
	   {
	     opener.document.form1.radiotipodeduccion[0].checked=true;
	   }
	else   
	if (tipodeduccion=="I")
	   {
	    opener.document.form1.radiotipodeduccion[1].checked=true;
		opener.document.form1.radiotipoperdeduccion[0].disabled=true;
	    opener.document.form1.radiotipoperdeduccion[1].disabled=true;
	   }
	else   
    if (tipodeduccion=="M")
	   {
	     opener.document.form1.radiotipodeduccion[2].checked=true;
		 opener.document.form1.radiotipoperdeduccion[0].disabled=true;
	     opener.document.form1.radiotipoperdeduccion[1].disabled=true;
	   }
    else	
	if (tipodeduccion=="A")
	   {
	     opener.document.form1.radiotipodeduccion[3].checked=true;
		 opener.document.form1.radiotipoperdeduccion[0].disabled=true;
	     opener.document.form1.radiotipoperdeduccion[1].disabled=true;
	   }
	else
    if (tipodeduccion=="O")
	   {
	     opener.document.form1.radiotipodeduccion[4].checked=true;
		 opener.document.form1.radiotipoperdeduccion[0].disabled=true;
	     opener.document.form1.radiotipoperdeduccion[1].disabled=true;
	   }
    if (tipopersdeduccion=="J")
	   {
	     opener.document.form1.radiotipoperdeduccion[0].checked=true;
	   }
	else   
	if (tipopersdeduccion=="N")
	   {
	    opener.document.form1.radiotipoperdeduccion[1].checked=true;
	   }
	opener.document.form1.txtdeducible.value=deducible;
	opener.document.form1.txtformula.value=formula;
	opener.document.form1.hidestatus.value="GRABADO";
	close();
  }
</script>
</html>