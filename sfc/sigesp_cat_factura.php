<?
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
ini_set('max_execution_time',9000); //tiempo limite de ejecucion de un escript en segundos.
#
ini_set("memory_limit","1500M"); // aumentamos la memoria a 1,5GB
#
ini_set("buffering ","0"); // aumentamos la memoria a 1,5GB
/*ob_start();
ob_implicit_flush(true);*/
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Factura</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
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
	color: #006699#006699;
}
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_nomusu="%".$_POST["nomusu"]."%";
	$ls_numfac="%".$_POST["numfac"]."%";
	$ls_cedcli="%".$_POST["cedcli"]."%";
	$ls_codtienda=$_POST["tienda"];
	$ls_codcaja=$_POST["caja"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	if ($ls_fecemi=="")
	{
		$ls_fecemi="%/".$ls_fecemi."%";
		}
}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{
	$ls_operacion="";
	$ls_nomusu="";
	$ls_cedcli="";
	$ls_numfac="";
	$ls_fecemi="%%";
	$ls_codtienda="";
	$ls_codcaja="";
}

if(array_key_exists("tienda",$_REQUEST) and array_key_exists("caja",$_REQUEST))
{
	$ls_codtienda=$_REQUEST["tienda"];
	$ls_codcaja=$_REQUEST["caja"];
}
else{
	$ls_codtienda="";
	$ls_codcaja="";
}
print $ls_codtienda.'<br>'.$ls_codcaja;
/************************************************************************************************************************/
/***************************   TABLA DREAMWEAVER ************************************************************************/
/************************************************************************************************************************/

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
    <input name="tienda" type="hidden" id="tienda"  value="<? print $ls_codtienda?>">
    <input name="caja" type="hidden" id="caja"  value="<? print $ls_codcaja?>">

</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Facturas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td width="67"><div align="right">Nombre</div></td>
        <td width="431"><div align="left">
          <input name="nomusu" type="text" id="nomusu"  size="60">
        </div></td>
      </tr>
	   <td>&nbsp;</td>
	   <tr>
        <td width="67"><div align="right">Cédula/Rif</div></td>
        <td width="431"><div align="left">
          <input name="cedcli" type="text" id="cedcli"   maxlength="15" size="16">
        </div></td>
      </tr>
      <td>&nbsp;</td>
	 <tr>
        <td width="67"><div align="right">Nro. Factura </div></td>
        <td width="431"><input name="numfac" type="text"  id="numfac" size="26" maxlength="25"></td>
      </tr>
	  <td>&nbsp;</td>
	 <tr>
        <td width="67"><div align="right">Fecha</div></td>
        <td width="431"><input name="txtfecemi" type="text"  id="txtfecemi"  datepicker="true" size="11" maxlength="10"></td>
      </tr>

	 <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?
/************************************************************************************************************************/
/******************   BUSCAR --> BUSCA LA FACTURA Y ENVIA LOS DATOS A LA PAGINA "FACTURAR" ******************************/
/************************************************************************************************************************/
if($ls_operacion=="BUSCAR")
{
	if($ls_codtienda != ""){
		$filtro=" AND f.codtiend='$ls_codtienda' AND f.cod_caja='$ls_codcaja' ";
	}else{
		$filtro="";
	}

if($ls_fecemi=="%/%")
{
 $ls_cadena=" SELECT DISTINCT f.numfac,f.numcon,f.numcot,f.fecemi,f.conpag,f.numordent,f.obsfac,f.numdiacre,COALESCE(f.monto,0) as monto,f.estfaccon,f.estfac,f.esppag,c.codcli,c.razcli,c.cedcli ".
			" FROM sfc_cliente c,sfc_factura f ".
			" WHERE f.codcli=c.codcli AND c.razcli ilike '%".$ls_nomusu."%' AND c.cedcli ilike '%".$ls_cedcli."%' AND f.numfac ilike '%".$ls_numfac."%' " .
			" AND f.numfac <> '0000000000000000000000000' ".$filtro;
}
elseif($ls_fecemi<>"%/%")
{
 $ls_cadena=" SELECT DISTINCT f.numfac,f.numcon,f.numcot,f.fecemi,f.conpag,f.numordent,f.obsfac, f.numdiacre,COALESCE(f.monto,0) as monto,f.estfaccon,f.estfac,f.esppag,c.codcli,c.razcli,c.cedcli ".
			" FROM sfc_cliente c,sfc_factura f ".
			" WHERE f.codcli=c.codcli and c.razcli ilike '%".$ls_nomusu."%' AND c.cedcli ilike '%".$ls_cedcli."%' AND f.numfac ilike '%".$ls_numfac."%' " .
			" AND substr(cast (f.fecemi as char(30)),0,11) ilike '%".$ls_fecemi."%' AND f.numfac <> '0000000000000000000000000' ".$filtro;
}
			/*sfc_detcotizacion/sfc_cliente.codcli=sfc_detcotizacion.codcli and */
		//	print $ls_cadena;
			$rs_datauni=$io_sql->select($ls_cadena." ORDER BY f.numfac");
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de cotizaciones");
			}
			else
			{
				$li_i=0;
				print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Factura</font></td>";
					print "<td><font color=#FFFFFF>Nombre cliente</font></td>";
					print "<td><font color=#FFFFFF>Cédula/Rif</font></td>";
					print "<td><font color=#FFFFFF>Fecha</font></td>";
					print "<td><font color=#FFFFFF>Monto</font></td>";
					print "</tr>";
				while($row=$io_sql->fetch_row($rs_datauni))
				{
					$li_i++;
					

					/*$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("codcli");

					for($z=1;$z<=$totrow;$z++)
					{*/
						print "<tr class=celdas-blancas>";
						$codigo=$row["codcli"];
						$cedcli=$row["cedcli"];
						$numfac=$row["numfac"];
						$numcon=$row["numcon"];
						$cotizacion=$row["numcot"];
						$orden=$row["numordent"];
						$fecemi=$row["fecemi"];
						$observ=$row["obsfac"];
						$numdia=$row["numdiacre"];
					    $fecemi=$io_funcion->uf_convertirfecmostrar($fecemi);

						$conpag=$row["conpag"];
						$monto=number_format($row["monto"],'2',',','.');
						//print $monto;
		                $estfac=$row["estfac"];
						$estfaccon=$row["estfaccon"];
						$esppag=$row["esppag"];
		                $nombre=$row["razcli"];
						print "<td><a href=\"javascript: aceptar('$codigo','$numfac','$numcon','$cotizacion','$orden','$fecemi','$conpag','$monto','$estfac','$nombre','$estfaccon','$esppag','$cedcli','$observ','$numdia');\">".$numfac."</a></td>";
						print "<td align=left>".$nombre."</td>";
						print "<td align=left>".$cedcli."</td>";
						print "<td align=left>".$fecemi."</td>";
						print "<td align=right>".$monto."</td>";
						print "</tr>";
						//print "faccon:".$estfaccon;
					//}
				}
				if($li_i==0)
				{
					$io_msg->message("No se han registrado Facturas");
				}
		}
}
print "</table>";
/*ob_end_flush();
ob_clean();*/
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT **************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/


  function aceptar(codigo,numfac,numcon,cotizacion,orden,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,observ,numdia)
  {
    opener.f.txtconsulta.value="M";
    opener.ue_cargarfactura(codigo,numfac,numcon,cotizacion,orden,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,observ,numdia);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_factura.php";
  f.submit();
  }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
