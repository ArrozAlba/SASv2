<?
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
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cotizacion</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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



if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_nomusu="%".$_POST["nomusu"]."%";
	$ls_codcli=$_POST["txtcodcli"];
}
else
{
	$ls_operacion="";
	$ls_nomusu="";
	$ls_codcli=$_GET["codcli1"];
}


?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

    <input name="txtcodcli" type="hidden" id="txtcodcli" value="<?php print $ls_codcli ?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cotizaciones Emitidas </td>
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
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?

/****************************************************************************************************************************/
/****************************************************************************************************************************/
/****************************************************************************************************************************/
if($ls_operacion=="BUSCAR")
{
 if ($ls_codcli=="") $ls_codcli="%%";

$dia=time()-(3*24*60*60);
$hace_tres_dias = date('Y-m-d', $dia);
 $ls_cadena=" SELECT   sfc_cotizacion.numcot,sfc_cotizacion.feccot,sfc_cotizacion.monto,sfc_cotizacion.obscot," .
 		"sfc_cliente.codcli,sfc_cliente.cedcli,sfc_cliente.razcli,sfc_cotizacion.estcot ".
			" FROM sfc_cliente,sfc_cotizacion ".
			" WHERE sfc_cotizacion.codcli=sfc_cliente.codcli and sfc_cotizacion.estcot='E' and sfc_cotizacion.codtiend ilike '".$ls_codtie."' AND " .
			" sfc_cotizacion.feccot>= '".$hace_tres_dias."' and sfc_cliente.razcli ilike '".$ls_nomusu."' " .
					"order by sfc_cotizacion.numcot DESC";
//print($ls_cadena);
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de cotizaciones");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Cotizaci&oacute;n</font></td>";
					print "<td><font color=#FFFFFF>Nombre cliente</font></td>";
					print "<td><font color=#FFFFFF>Fecha</font></td>";
					print "<td><font color=#FFFFFF>Monto</font></td>";
					$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("codcli");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codigo=$io_data->getValue("codcli",$z);
						$cedcli=$io_data->getValue("cedcli",$z);
						$cotizacion=$io_data->getValue("numcot",$z);
		                $nombre=$io_data->getValue("razcli",$z);
						$fecha=$io_data->getValue("feccot",$z);
						$monto=$io_data->getValue("monto",$z);
						$obs=$io_data->getValue("obscot",$z);
						$estcot=$io_data->getValue("estcot",$z);
						print "<td><a href=\"javascript: aceptar('$codigo','$cotizacion','$nombre','$fecha','$monto','$obs','$estcot','$cedcli');\">".$cotizacion."</a></td>";
						print "<td align=left>".$nombre."</td>";
						print "<td align=left>".$fecha."</td>";
						print "<td align=left>".number_format($monto,2,',','.')."</td>";
						print "</tr>";
						ob_flush();
						flush();
					}

				}
				else
				{
					$io_msg->message("No se han registrado Cotizaciones");
				}
		}
}
print "</table>";
/*ob_end_flush();*/
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(codigo,cotizacion,nombre,fecha,monto,obs,estcot,cedcli)
  {
    opener.ue_cargarcotizacion(codigo,cotizacion,nombre,fecha,monto,obs,estcot,cedcli);

	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_cotizacionfactura.php";
  f.submit();
  }

</script>
</html>
