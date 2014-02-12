<?
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
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
<title>Cat&aacute;logo de Facturas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
	$ls_codtiend=$_POST["txtcodtie"];
	$ls_destienda=$_POST["txtdestienda"];
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
	$ls_numfac="";
	$ls_codtiend="";
	$ls_destienda="";
	$ls_fecemi="%%";
}
/************************************************************************************************************************/
/***************************   TABLA DREAMWEAVER ************************************************************************/
/************************************************************************************************************************/

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Facturas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

     <tr>
        <input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtiend?>" size="5" maxlength="4">

       <td width="92" height="30"><div align="right">Unidad Operativa de Suministro</div></td>

        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>

      <tr>
        <td width="92"><div align="right">Nombre</div></td>
        <td width="406"><div align="left">
          <input name="nomusu" type="text" id="nomusu"  size="60">
        </div></td>
      </tr>
      <tr>

      <tr>
        <td width="92"><div align="right">Nro. Factura </div></td>
        <td width="406"><input name="numfac" type="text"  id="numfac" size="26" maxlength="25"></td>
      </tr>
	  <td>&nbsp;</td>
	 <tr>
       <td width="92"><div align="right">Fecha</div></td>
        <td width="406"><input name="txtfecemi" type="text"  id="txtfecemi"  size="11" maxlength="12" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></td>
      </tr>


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

 	if($ls_fecemi=="%/%")
	{
 		 $ls_cadena=" SELECT DISTINCT sfc_tienda.codtiend, sfc_tienda.dentie,sfc_factura.codtiend,sfc_factura.numfac,sfc_factura.numcot,substring(cast (sfc_factura.fecemi as char(30)),0,11) as fecemi,sfc_factura.conpag,sfc_factura.monto," .
 		"sfc_factura.estfaccon,sfc_factura.estfac,sfc_factura.esppag,sfc_cliente.codcli,sfc_cliente.razcli,sfc_cliente.cedcli " .
 		"FROM sfc_cliente,sfc_factura,sfc_tienda WHERE sfc_tienda.codtiend=sfc_factura.codtiend and sfc_factura.codcli=sfc_cliente.codcli and sfc_factura.estfaccon!='A' and " .
 		"sfc_cliente.razcli ilike '%".$ls_nomusu."%' and sfc_factura.numfac ilike '%".$ls_numfac."%' and sfc_factura.codtiend='".$ls_codtiend."'";
	
	}
	elseif($ls_fecemi<>"%/%")
	{
		 $ls_cadena=" SELECT DISTINCT sfc_tienda.codtiend, sfc_tienda.dentie,sfc_factura.codtiend,sfc_factura.numfac,sfc_factura.numcot,substring(cast (sfc_factura.fecemi as char(30)),0,11) as fecemi,sfc_factura.conpag,sfc_factura.monto," .
 		"sfc_factura.estfaccon,sfc_factura.estfac,sfc_factura.esppag,sfc_cliente.codcli,sfc_cliente.razcli,sfc_cliente.cedcli " .
 		"FROM sfc_cliente,sfc_factura,sfc_tienda WHERE sfc_tienda.codtiend=sfc_factura.codtiend and sfc_factura.codcli=sfc_cliente.codcli and sfc_factura.estfaccon!='A' and " .
 		"sfc_cliente.razcli ilike '%".$ls_nomusu."%' and sfc_factura.numfac ilike '%".$ls_numfac."%' and substring(cast (sfc_factura.fecemi as char(30)),0,11) ilike '%".$ls_fecemi."%'  and sfc_factura.codtiend='".$ls_codtiend."' ";

	}
      //print $ls_cadena;
			$rs_datauni=$io_sql->select($ls_cadena);				
			if(($rs_datauni==false) && ($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de Facturas");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Factura</font></td>";
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
						$numfac=$io_data->getValue("numfac",$z);
						$cotizacion=$io_data->getValue("numcot",$z);
						$fecemi=$io_data->getValue("fecemi",$z);
					    $fecemi=$io_funcion->uf_convertirfecmostrar($fecemi);

						$conpag=$io_data->getValue("conpag",$z);
						$monto=number_format($io_data->getValue("monto",$z),'2',',','.');
		                $estfac=$io_data->getValue("estfac",$z);
						$estfaccon=$io_data->getValue("estfaccon",$z);
						$esppag=$io_data->getValue("esppag",$z);
		                $nombre=$io_data->getValue("razcli",$z);
		                $ls_codtie=$io_data->getValue("codtiend",$z);
		                $ls_tienda=$io_data->getValue("dentie",$z);
						print "<td><a href=\"javascript: aceptar('$codigo','$numfac','$cotizacion','$fecemi','$conpag','$monto','$estfac','$nombre','$estfaccon','$esppag','$cedcli','$ls_codtie','$ls_tienda');\">".$numfac."</a></td>";
						print "<td align=left>".$nombre."</td>";
						print "<td align=left>".$fecemi."</td>";
						print "<td align=left>".$monto."</td>";
						print "</tr>";
						//print "faccon:".$estfaccon;
					}
				}
				else
				{
					$io_msg->message("No se han registrado Facturas");
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
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT **************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/


  function aceptar(codigo,numfac,cotizacion,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,ls_codtie,ls_tienda)
  {

    opener.ue_cargarfactura(codigo,numfac,cotizacion,fecemi,conpag,monto,estfac,nombre,estfaccon,esppag,cedcli,ls_codtie,ls_tienda);

	close();
  }

  function ue_search()
  {
	  f=document.form1;

	  if(f.txtcodtie.value=="")
	  alert("Seleccione la Tienda!!");
	  else{

	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_facturadev.php";
	  f.submit();
	  }
  }

function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);




		}

/***********************************************************************************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,facporvol,codtipundopesum,dentipundopesum,estatus)
		{
			f=document.form1;

			f.txtcodtie.value=codtie;
            f.txtdestienda.value=nomtie;


		}


</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
