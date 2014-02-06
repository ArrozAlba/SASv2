<?php
session_start();
$arremp=$_SESSION["la_empresa"];
$ls_codemp=$arremp["codemp"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Servicios</title>
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

<body><br>
<form name="form1" method="post" action=""  >
  <div align="center">
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Servicios </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td width="413" height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="81" height="22"><div align="right">C&oacute;digo</div></td>
        <td height="22"><div align="left">
            <input name="txtcodser" type="text" id="txtcodser" onKeyPress="javascript: ue_mostrar(this,event);">
          </div>
            <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
            <input name="txtdenser" type="text" id="txtdenser" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript:ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a><a href="javascript: ue_search();">Buscar</a> </div></td>
    </table>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_in   = new sigesp_include();
$con     = $io_in->uf_conectar();
$io_ds   = new class_datastore();
$io_sql  = new class_sql($con);
$arr     = $_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_codser="%".$_POST["txtcodser"]."%";
	$ls_denser="%".$_POST["txtdenser"]."%";
	$ls_sql  = " SELECT soc_servicios.*,soc_tiposervicio.dentipser,".
			   "        (SELECT denunimed FROM siv_unidadmedida".
			   "		  WHERE soc_servicios.codunimed=siv_unidadmedida.codunimed) AS denunimed".
			   "   FROM soc_servicios , soc_tiposervicio ".
			   "  WHERE soc_servicios.codemp='".$ls_codemp."'".
			   "    AND soc_servicios.codser like '".$ls_codser."'".
			   "    AND soc_servicios.denser like '".$ls_denser."'".
			   "    AND soc_servicios.codtipser=soc_tiposervicio.codtipser   ".
			   "  ORDER BY soc_servicios.codser ASC";
	$rs_data = $io_sql->select($ls_sql);
	$data    = $rs_data;
	if($row=$io_sql->fetch_row($rs_data))
	  {
		$data        = $io_sql->obtener_datos($rs_data);
		$arrcols     = array_keys($data);
		$totcol      = count($arrcols);
		$io_ds->data = $data;
		$totrow      = $io_ds->getRowCount("codser");
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>"; 
		print "<tr class=titulo-celda>"; 
		print "<td>C&oacute;digo </td>"; 
		print "<td>Denominaci&oacute;n</td>";
		print "</tr>";
		for($z=1;$z<=$totrow;$z++)
		   {
			 print "<tr class=celdas-blancas>";
			 $ls_codigo       = $data["codser"][$z];
			 $ls_codtipser    = $data["codtipser"][$z];
			 $ls_dentipser    = $data["dentipser"][$z];
			 $ls_denominacion = $data["denser"][$z];
			 $ld_precio       = $data["preser"][$z];    
			 $ld_precio       = number_format($ld_precio,2,',','.');
			 $ls_spgcuenta    = $data["spg_cuenta"][$z]; 
			 $ls_codunimed    = $data["codunimed"][$z]; 
			 $ls_denunimed    = $data["denunimed"][$z]; 
			 print "<td  align=center><a href=\"javascript: aceptar('$ls_codigo','$ls_codtipser','$ls_dentipser','$ls_denominacion','$ld_precio','$ls_spgcuenta','$ls_codunimed','$ls_denunimed');\">".$ls_codigo."</a></td>";
			 print "<td  align=left>".$ls_denominacion."</td>";
			 print "</tr>";			
		   }
	$io_sql->free_result($rs_data);
	print "</table>";
	}
	else
	{
	?>
	 <script language="javascript">
	 alert("No se han creado Servicios !!!");
	 //close();
	 </script> 
	<?php
	}
}
	?>
    <input name="operacion" type="hidden" id="operacion">
  </div>
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,ls_codtipser,ls_dentipser,denominacion,precio,spg_cuenta,codunimed,denunimed)
  {
    fop= opener.document.form1;
	fop.txtcodigo.value= codigo;
    fop.txtcodtipser.value= ls_codtipser;
	fop.txtdentipser.value= ls_dentipser;
	fop.readOnly= true;
	fop.txtdenominacion.value= denominacion;
    fop.txtprecio.value= precio;
    fop.txtcuenta.value= spg_cuenta;
	fop.operacion.value= "CARGAR";
	fop.txtcodunimed.value= codunimed;
	fop.txtdenunimed.value= denunimed;
	fop.hidestatus.value= "GRABADO";
	fop.submit(); 
	close();
  }
  
  function ue_search()
  {
  	f=document.form1;
	f.operacion.value="BUSCAR";
	f.submit();
  }
</script>
</html>