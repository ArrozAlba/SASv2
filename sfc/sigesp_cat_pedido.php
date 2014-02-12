<?
/////////////////////////////////////////////////////////////////////////////////////////////
 // Catalogo:    - sigesp_cat_pedido.php
 // Autor:       - Ing. Zulheymar Rodríguez
 // Fecha:       - 31/08/2007
 //////////////////////////////////////////////////////////////////////////////////////////
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
<title>Cat&aacute;logo de Pedido</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->

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
	$ls_nomtie="%".$_POST["nomtie"]."%";
	$ls_numped="%".$_POST["numped"]."%";
	$ls_estatus=$_POST["cmbestatus"];
	$ls_fecemi=$_POST["txtfecemi"];	
	$ls_codtie=$_POST["txtcodtie"];
	$ls_destienda=$_POST["txtdestienda"];
}
else
{
	$ls_operacion="";
	$ls_nomtie="";
	$ls_numped="";
	$ls_estatus="";
	$ls_fecemi="";
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Pedidos </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
 <td width="107" ><input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtie?>" size="5" maxlength="4"></td>

 <td>&nbsp;</td>


	    <tr>
        <td width="107" height="33"><div align="right">Unidad Operativa de Suministro</div></td>
        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>
<td>&nbsp;</td>

	  <tr>
        <td width="107"><div align="right">Nro. Pedido</div></td>
        <td width="391"><div align="left">
          <input name="numped" type="text" id="numped"  size="26" maxlength="25">
        </div></td>
      </tr>

	    <td>&nbsp;</td>

	  <tr>
        <td width="107"><div align="right">Fecha</div></td>
        <td width="391"><input name="txtfecemi" type="text"  id="txtfecemi"  datepicker="true" size="11" maxlength="10"></td>
      </tr>

	  <td>&nbsp;</td>

	   <tr>
        <td height="31"><div align="right">Estatus</div></td>
        <td><label>
          <select name="cmbestatus" size="1" id="cmbestatus">
            <option value="N" selected>Seleccione...</option>
		    <option value="E" >Emitido</option>
            <option value="P">En Proceso</option>
			<option value="F">Facturado</option>
        </select>
        </label></td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?
if($ls_operacion=="BUSCAR")
{

if ($ls_codtie=="")
{
	if($ls_fecemi=="")
	{
	 if($ls_estatus<>'N')
	 {

	 $ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend  AND sfc_pedido.estpedido ilike '".$ls_estatus."' AND sfc_pedido.numpedido ilike '".$ls_numped."' AND ue.coduniadm=sfc_tienda.coduniadm ";
	}
	else
		{
		$ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue ".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend  AND sfc_pedido.numpedido ilike '".$ls_numped."' AND ue.coduniadm=sfc_tienda.coduniadm ";

		}

	}
	elseif($ls_fecemi<>"")
	{
	  	$ls_fecemi=$io_funcion->uf_convertirdatetobd($ls_fecemi);
	 	if($ls_estatus<>'N')
	  	{
	  		$ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
					" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue ".
					" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend  AND sfc_pedido.numpedido ilike '".$ls_numped."' AND sfc_pedido.estpedido ilike '".$ls_estatus."' AND sfc_pedido.fecpedido='".$ls_fecemi."' AND ue.coduniadm=sfc_tienda.coduniadm ";
	 	}
		else{
			 $ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue ".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend  AND sfc_pedido.numpedido ilike '".$ls_numped."' AND sfc_pedido.fecpedido='".$ls_fecemi."' AND ue.coduniadm=sfc_tienda.coduniadm ";
		}
	}
}
else
{

	if($ls_fecemi=="%/%")
	{
	 if($ls_estatus<>'N')
	 {

	 $ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend and sfc_tienda.codtiend ilike '".$ls_codtie."' AND sfc_pedido.estpedido ilike '".$ls_estatus."' AND sfc_pedido.numpedido ilike '".$ls_numped."' AND ue.coduniadm=sfc_tienda.coduniadm ";
	}
	else
		{
		$ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue ".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend and sfc_tienda.codtiend ilike '".$ls_codtie."' AND sfc_pedido.numpedido ilike '".$ls_numped."' AND ue.coduniadm=sfc_tienda.coduniadm ";

		}

	}
	elseif($ls_fecemi<>"%/%")
	{
	 if($ls_estatus<>'N')
	 {
	 $ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue ".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend and sfc_tienda.codtiend ilike '".$ls_codtie."' AND sfc_pedido.numpedido ilike '".$ls_numped."' AND sfc_pedido.estpedido ilike '".$ls_estatus."' AND sfc_pedido.fecpedido ilike '".$ls_fecemi."' AND ue.coduniadm=sfc_tienda.coduniadm ";
	}
	else{
	 $ls_cadena=" SELECT DISTINCT sfc_pedido.numpedido,sfc_pedido.fecpedido,sfc_pedido.obspedido,sfc_tienda.codtiend,sfc_tienda.dentie,sfc_pedido.estpedido,ue.denuniadm,ue.coduniadm ".
				" FROM sfc_tienda,sfc_pedido,spg_unidadadministrativa ue ".
				" WHERE sfc_pedido.codtiend=sfc_tienda.codtiend and sfc_tienda.codtiend ilike '".$ls_codtie."' AND sfc_pedido.numpedido ilike '".$ls_numped."' AND sfc_pedido.fecpedido ilike '".$ls_fecemi."' AND ue.coduniadm=sfc_tienda.coduniadm ";
		}
	}

}
			//print $ls_cadena;
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de pedidos");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Pedido</font></td>";
					print "<td><font color=#FFFFFF>Tienda</font></td>";
					print "<td><font color=#FFFFFF>Fecha</font></td>";
					print "<td><font color=#FFFFFF>Estado</font></td>";
					$ls_pedido=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_pedido;
					$totrow=$io_data->getRowCount("numpedido");
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codigo=$io_data->getValue("codtiend",$z);
						$pedido=$io_data->getValue("numpedido",$z);
		                $nombre=$io_data->getValue("dentie",$z);
						$fecha=$io_data->getValue("fecpedido",$z);
						$fecha=$io_funcion->uf_convertirfecmostrar($fecha);
						$obs=$io_data->getValue("obspedido",$z);
						$estped=$io_data->getValue("estpedido",$z);
						$coduniadm=$io_data->getValue("coduniadm",$z);
						$denuniadm=$io_data->getValue("denuniadm",$z);


						if($estped=='E')
						{
						  $estpedD='Emitido';
						}
						elseif($estped=='F')
						{
						  $estpedD='Facturado';
						}
						elseif($estped=='P')
						{
						  $estpedD='Procesado';
						}
						print "<td><a href=\"javascript: aceptar('$codigo','$pedido','$nombre','$fecha','$obs','$estped','$coduniadm','$denuniadm');\">".$pedido."</a></td>";
						print "<td align=left>".$nombre."</td>";
						print "<td align=left>".$fecha."</td>";
						print "<td align=left>".$estpedD."</td>";
						print "</tr>";

					}

				}
				else
				{
					$io_msg->message("No se han registrado Pedidos");
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

  function aceptar(codigo,pedido,nombre,fecha,obs,estped,coduniadm,denuniadm)
  {
    opener.ue_cargarpedido(codigo,pedido,nombre,fecha,obs,estped,coduniadm,denuniadm);

	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_pedido.php";
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
