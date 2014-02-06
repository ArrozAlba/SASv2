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
<title>Cat&aacute;logo de Cobranza</title>
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
/**************   GRID   DETALLES   FACTURA   *******************/
$ls_tituloconcepto="Detalle Conceptos";
$li_anchoconcepto=600;
$ls_nametable="grid2";
$la_columconcepto[1]="Código";
$la_columconcepto[2]="Descripción";
$la_columconcepto[3]="Precio Unitario";
$la_columconcepto[4]="IVA";
$la_columconcepto[5]="Cantidad";
$la_columconcepto[6]="Total";
$la_columconcepto[7]="Edición";
/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_razcli="%".$_POST["razcli"]."%";
}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{
	$ls_operacion="";
	$ls_razcli="";
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cobranzas</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
      <tr>
        <td width="67"><div align="right">Nombre</div></td>
        <td width="431"><div align="left">
          <input name="razcli" type="text" id="razcli"  size="60" maxlength="225">
        </div></td>
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

// $ls_cadena="SELECT c.*,f.numfac,f.fecemi,f.monto,co.moncob,co.feccob,cf.moncancel,cf.tipcancel FROM sfc_cliente c,sfc_factura f,sfc_cobro co,sfc_cobrofactura cf WHERE cf.numfac=f.numfac AND f.codcli=cf.codcli AND cf.codcli=co.codcli AND cf.numcob=co.numcob AND  c.codcli=f.codcli and c.razcli LIKE '".$ls_razcli."' AND f.conpag='2';";

//** Visualiza las facturas que estan pendientes por cancelar  ****
 $ls_cadena="SELECT f.*,c.razcli,c.codcli FROM sfc_cliente c,sfc_factura f WHERE c.codcli=f.codcli AND estfaccon='P' AND f.conpag='2';";
			
		
			/*sfc_detcotizacion/sfc_cliente.codcli=sfc_detcotizacion.codcli and */
          
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
					print "<td><font color=#FFFFFF>No. factura</font></td>";
					print "<td><font color=#FFFFFF>Fecha emisión</font></td>";
					print "<td><font color=#FFFFFF>Nombre cliente</font></td>";
					print "<td><font color=#FFFFFF>Monto factura</font></td>";
                 //	print "<td><font color=#FFFFFF>Monto cancelado</font></td>";
					//print "<td><font color=#FFFFFF>Monto por pagar</font></td>";
									
										
					$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("codcli");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$numfac=$io_data->getValue("numfac",$z);
						$fecemi=$io_data->getValue("fecemi",$z);
						$razcli=$io_data->getValue("razcli",$z);
						$monto=$io_data->getValue("monto",$z);
						//$tipcancel=$io_data->getValue("tipcancel",$z);
						//$moncancel=$io_data->getValue("moncancel",$z);
						$monto=number_format($monto,2, ',', '.');
						print "<td><a href=\"javascript: aceptar('$numfac','$fecemi','$razcli','$monto');\">".$numfac."</a></td>";
						
						print "<td align=left>".$fecemi."</td>";
						
						print "<td align=left>".$razcli."</td>";
						print "<td align=center>".$monto."</td>";
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("¡No se han registrado cobros!");
				}
		}
}

if ($ls_operacion=="ue_cargar_facturadetalles")
{
$li_filasconcepto=1;
    $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";

$ls_cadena="SELECT sigesp_cargos.porcar,sfc_producto.moncar,sfc_producto.denpro,sfc_detfactura.* FROM  sigesp_cargos,sfc_producto,sfc_detfactura WHERE sigesp_cargos.codcar=sfc_producto.codcar AND  sfc_detfactura.codpro=sfc_producto.codpro AND sfc_detfactura.numfac='".$numfac."';";
	//print $ls_cadena;		
			$arr_detfactura=$io_sql->select($ls_cadena);
			 
			if($arr_detfactura==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de productos");
			}
			else
			{						
				if($row=$io_sql->fetch_row($arr_detfactura))

 				  {
					$la_producto=$io_sql->obtener_datos($arr_detfactura);
					$io_datastore->data=$la_producto;
					$totrow=$io_datastore->getRowCount("numfac");
						
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
						$ls_codpro=$io_datastore->getValue("codpro",$li_i);
		                $ls_denpro=$io_datastore->getValue("denpro",$li_i);
						$ls_prepro=$io_datastore->getValue("prepro",$li_i);
						$ls_canpro=$io_datastore->getValue("canpro",$li_i);
						$ls_totpro=$io_datastore->getValue("totpro",$li_i);
						$ls_porcar=$io_datastore->getValue("porcar",$li_i);
						$ls_moncar=$io_datastore->getValue("moncar",$li_i);
						
						$ls_prepro=number_format($ls_prepro,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
						$ls_totpro=number_format($ls_totpro,2, ',', '.');
						$ls_moncar=number_format($ls_moncar,2, ',', '.');
						$ls_porcar=number_format($ls_porcar,2, ',', '.');
						
						//print  $ls_codpro."/".$ls_denpro."/".$ls_prepro."/".$ls_canpro."/".$li_i."/";
						
		$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][4]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtcanpro".$li_i." onChange=javascript:ue_subtotal(); type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=15 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))>";
		$la_objectconcepto[$li_i][6]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=15 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][7]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
		   	 	   }
					
		 $li_filasconcepto=$li_i;		
				              
	$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
	$la_objectconcepto[$li_filasconcepto][3]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][4]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][5]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center >";
	$la_objectconcepto[$li_filasconcepto][6]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=15 style= text-align:center readonly>";
	$la_objectconcepto[$li_filasconcepto][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
             }	
  //print_r($la_objectconcepto);
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

                   
  function aceptar(numfac,fecemi,razcli,monto)
  {
  	 f=document.form1;
     f.operacion.value="ue_cargar_facturadetalles";
     f.action="sigesp_cat_cobranza_total.php";
     f.submit();
  }
 
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_cobranza_total.php";
  f.submit();
  }
 
</script>
</html>
