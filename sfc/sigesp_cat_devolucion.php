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
<title>Cat&aacute;logo de Devoluci�n</title>
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
$ls_codtie=$_SESSION["ls_codtienda"];


/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_razcli="%".$_POST["razcli"]."%";
	$ls_cedcli="%".$_POST["cedcli"]."%";
	$ls_codtiend=$_POST["txtcodtie"];
	$ls_destienda=$_POST["txtdestienda"];

	$ls_feccob_desde=$_POST["txtfeccob_desde"];
	$ls_feccob_hasta=$_POST["txtfeccob_hasta"];
	$ls_coddev="%".$_POST["txtcoddev"]."%";

}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{

	$ls_operacion="";
	$ls_codtiend="";
	$ls_destienda="";
	$ls_razcli="";
	$ls_cedcli="";
	$ls_feccob_desde="";
	$ls_feccob_hasta="";
	$ls_coddev="";
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo devoluciones </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

       <tr>
        <input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtiend?>" size="5" maxlength="4">

        <td width="67" height="30"><div align="right">Unidad Operativa de Suministro</div></td>

        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>


      <tr>
        <td width="106" height="25"><div align="right">Nombre cliente </div></td>
        <td width="392"><div align="left">
          <input name="razcli" type="text" id="razcli"  size="60">
        </div></td>
      </tr>

	 <tr>
        <td width="106" height="25"><div align="right">Cedula/Rif </div></td>
        <td width="392"><div align="left">
          <input name="cedcli" type="text" id="cedcli"  size="16" maxlength="15">
        </div></td>
      </tr>

	  <tr>
        <td height="27"><div align="right">Fecha Devoluci&oacute;n: </div></td>
        <td><p>
          Desde
          <input name="txtfeccob_desde" type="text" id="txtfeccob_desde"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true">
          Hasta
          <input name="txtfeccob_hasta" type="text" id="txtfeccob_hasta"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></p>
        </td>
      </tr>
      <tr>
        <td><div align="right">No.devoluci&oacute;n</div></td>
        <td><input name="txtcoddev" type="text" id="txtcoddev" value="<?php $ls_coddev ?>"  size="60"></td>
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

	if ($ls_codtiend=="")
	 $io_msg->message("Seleccione la Tienda!");
	else
	{
$suiche=false;
$ls_feccob_desde=$io_funcion->uf_convertirdatetobd($ls_feccob_desde);
$ls_feccob_hasta=$io_funcion->uf_convertirdatetobd($ls_feccob_hasta);

 if ($ls_codtie=='0001')
 {
		if ($ls_feccob_desde=="" && $ls_feccob_hasta=="")
		{
		 $ls_cadena="SELECT c.codcli,c.razcli,c.cedcli,d.*, t.dentie FROM sfc_factura f, sfc_cliente c, sfc_devolucion d,sfc_tienda t " .
		 		"WHERE d.numfac=f.numfac AND f.codcli=c.codcli AND d.coddev ilike '%".$ls_coddev."%' AND c.razcli ilike '%".$ls_razcli."%' AND " .
		 		"c.cedcli ilike '%".$ls_cedcli."%' AND t.codtiend=d.codtiend AND d.codtiend='".$ls_codtiend."' AND t.codtiend='".$ls_codtiend."' " .
		 		"order by d.coddev ASC;";
		}
		elseif ($ls_feccob_desde<>"" && $ls_feccob_hasta<>"")
		{
		 $ls_cadena="SELECT c.codcli,c.razcli,c.cedcli,d.* FROM sfc_factura f, sfc_cliente c, sfc_devolucion d,sfc_tienda t " .
		 		"WHERE d.numfac=f.numfac AND f.codcli=c.codcli AND (substr(cast (d.fecdev as char(30)),0,11)>='".$ls_feccob_desde."' AND substr(cast (d.fecdev as char(30)),0,11)<='".$ls_feccob_hasta."') " .
		 		"AND d.coddev ilike '%".$ls_coddev."%' AND c.razcli ilike '%".$ls_razcli."%' AND c.cedcli ilike '%".$ls_cedcli."%' " .
		 		"AND t.codtiend=d.codtiend AND d.codtiend='".$ls_codtiend."' AND t.codtiend='".$ls_codtiend."' order by d.coddev ASC;";
		}
		else
		{
		 $io_msg->message("Debe introducir el rango de fecha completo!");
		 $suiche=true;
		}

 }
 else
 {
 	if ($ls_feccob_desde=="" && $ls_feccob_hasta=="")
		{
		 $ls_cadena="SELECT c.codcli,c.razcli,c.cedcli,d.*,t.dentie FROM sfc_factura f, sfc_cliente c, sfc_devolucion d, sfc_tienda t WHERE " .
		 		"d.numfac=f.numfac AND f.codcli=c.codcli AND d.coddev ilike '%".$ls_coddev."%' AND c.razcli ilike '%".$ls_razcli."%' AND " .
		 		"c.cedcli ilike '%".$ls_cedcli."%' AND f.codtiend='".$ls_codtiend."' AND d.codtiend='".$ls_codtiend."' AND " .
		 		"t.codtiend='".$ls_codtiend."' order by d.coddev ASC;";

		}
		elseif ($ls_feccob_desde<>"" && $ls_feccob_hasta<>"")
		{
		 $ls_cadena="SELECT c.codcli,c.razcli,c.cedcli,d.*,t.dentie FROM sfc_factura f, sfc_cliente c, sfc_devolucion d, sfc_tienda t " .
		 		"WHERE d.numfac=f.numfac AND f.codcli=c.codcli AND (substr(cast (d.fecdev as char(30)),0,11)>='".$ls_feccob_desde."' AND substr(cast (d.fecdev as char(30)),0,11)<='".$ls_feccob_hasta."') " .
		 		"AND d.coddev ilike '%".$ls_coddev."%' AND c.razcli ilike '%".$ls_razcli."%' AND c.cedcli ilike '%".$ls_cedcli."%' AND " .
		 		"f.codtiend='".$ls_codtiend."' AND d.codtiend='".$ls_codtiend."' AND t.codtiend='".$ls_codtiend."' order by d.coddev ASC;";
		}
		else
		{
		 $io_msg->message("Debe introducir el rango de fecha completo!");
		 $suiche=true;
		}


 }
		if ($suiche==false)
		{

			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de devoluciones");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Devoluci�n</font></td>";
					print "<td><font color=#FFFFFF>No. Factura</font></td>";
					print "<td><font color=#FFFFFF>Nombre cliente</font></td>";
					print "<td><font color=#FFFFFF>C�dula/Rif</font></td>";
					print "<td><font color=#FFFFFF>Fecha devoluci�n</font></td>";
					print "<td><font color=#FFFFFF>Monto devuelto</font></td>";
					//print "<td><font color=#FFFFFF>Monto por pagar</font></td>";


					$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("codcli");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$coddev=$io_data->getValue("coddev",$z);
						$numcon=$io_data->getValue("numcon",$z);
						$razcli=$io_data->getValue("razcli",$z);
						$cedcli=$io_data->getValue("cedcli",$z);
						$fecdev=$io_data->getValue("fecdev",$z);
						$fecdev=$io_funcion->uf_convertirfecmostrar($fecdev);
						$mondev=$io_data->getValue("mondev",$z);
						$estdev=$io_data->getValue("estdev",$z);
						$codcli=$io_data->getValue("codcli",$z);
						$numfac=$io_data->getValue("numfac",$z);

						$obsdev=$io_data->getValue("obsdev",$z);
						$dentie=$io_data->getValue("dentie",$z);
						$codtiend=$io_data->getValue("codtiend",$z);

						$mondev=number_format($mondev,2,',','.');

						print "<td><a href=\"javascript: aceptar('$coddev','$razcli','$fecdev','$mondev','$codcli','$numfac','$obsdev','$estdev','$numcon','$dentie','$codtiend');\">".$coddev."</a></td>";
						print "<td align=center>".$numfac."</td>";
						print "<td align=center widht=145>".$razcli."</td>";
						print "<td align=center>".$cedcli."</td>";
						print "<td align=center>".$fecdev."</td>";
						print "<td align=right>".$mondev."</td>";
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("�No se han registrado devoluciones!");
				}
		}
}//$suiche
}}
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


  function aceptar(coddev,razcli,fecdev,mondev,codcli,numfac,obsdev,estdev,numcon,dentie,codtiend)
  {

    opener.ue_cargardevolucion(coddev,razcli,fecdev,mondev,codcli,numfac,obsdev,estdev,numcon,dentie,codtiend);
	close();
  }

  function ue_search()
  {
  f=document.form1;

  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_devolucion.php";
  f.submit();
  }

  function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);




		}

/***********************************************************************************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentaiva,deniva)
		{
			f=document.form1;

			f.txtcodtie.value=codtie;
            f.txtdestienda.value=nomtie;


		}


</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
