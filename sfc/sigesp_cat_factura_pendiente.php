<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
$ls_codtiend=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Abono/Cancelaci&oacute;n de Factura</title>
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
<?php
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



 $factura = $_GET['nrofac'];
 $fecha_factura = $_GET['fecha'];
 $monto_factura = $_GET['monto'];
 $cliente_factura=$_GET['cliente'];
 $rif_cliente_factura=$_GET['rif'];
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
    <td width="334" ><input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtiend;?>" size="5" maxlength="4"></td>


</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
            <td width="496" colspan="2" class="titulo-celda">Abono/Cancelaci&oacute;n de Factura</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
             <tr>
                 <td align="right"  colspan="2"><b>Factura Nro.:<font size="3" color="red"> <?php echo $factura;  ?></b></font></td>
                 
             </tr>   
              <tr>
                  <td align="right"  colspan="2"><b>Fecha de Emisi&oacute;n: <?php echo $fecha_factura;  ?></b></td>
             </tr> 
             <tr>
                  <td align="left"><b>Cliente: <?php echo $cliente_factura;  ?></b></td>
                  <td align="right"><b>Monto: <font size="3" color="red"> <?php echo number_format($monto_factura,2,',','.');  ?></b></font></td>
             </tr> 
             <tr>
                 <td colspan="2">&nbsp;</td>
                 
             </tr>
             <tr>
                  <td align="right">
                     Tipo de Cancelaci&oacute;n: <select size="1" id="combo_tipocancel" name="combo_tipocancel">  	
                       <option selected="" value="n">Seleccione...</option>
			<option value="T">Total</option>
			<option value="P">Parcial</option>
                      </select>
                  </td>
                  <td>
                     
                     Monto a Cancelar: <input type="text"  onkeypress="return(currencyFormat(this,'.',',',event))" style="text-align:center" size="15" class="sin-borde" value="0,00" id="txtmontocancel" name="txtmontocancel" disabled="true">
                     
                 </td>
             </tr> 
              <tr>
                 <td colspan="2">&nbsp;</td>
                 
             </tr>
              <tr>
                 <td colspan="2">&nbsp;</td>
                 
             </tr>
             <tr>
                 <td colspan="2" align="center">
                     <div id="formaDePago">
                         
                         
                         
                     </div>
                     
                 </td>
             </tr>
             <tr>
                 <td colspan="2" align="center">
                     <table width="470" cellspacing="1" cellpadding="1" border="0" class="fondo-tabla" id="grid3">
                        <tbody>
                            <tr class="titulo-celda">
                                <td colspan="8">Instrumento de pago</td>
                            </tr>
                            <tr class="titulo-celdanew">
                                <td align="center">Banco</td>
                                <td align="center">Forma de pago</td>
                                <td align="center">Fecha</td>
                                <td align="center">Monto</td>
                                <td align="center">Edici&oacute;n</td>
                            </tr>
                            <tr class="celdas-blancas">
                                <td class="celdas-blancas"><input type="text" readonly="" style="text-align:center" size="25" class="sin-borde" id="txtdenforpag1" name="txtdenforpag1" isdatepicker="true"></td>
                                <td class="celdas-blancas"><input type="text" readonly="" style="text-align:center" size="15" class="sin-borde" id="txtdenforpag1" name="txtdenforpag1" isdatepicker="true"></td>
                                <td class="celdas-blancas">
                                    <input type="text" readonly="" style="text-align:center" size="10" class="sin-borde" id="txtfecins1" name="txtfecins1" isdatepicker="true">
                                </td>
                                <td class="celdas-blancas">
                                    <input type="text" readonly="" style="text-align:center" size="15" class="sin-borde" id="txtmontoforpag1" name="txtmontoforpag1" isdatepicker="true"></td>
                                <td class="celdas-blancas"><input type="text" readonly="" size="5" style="text-align:center" class="sin-borde" id="txtvacio2" name="txtvacio2" isdatepicker="true"></td>
                            </tr>
                        </tbody>
                    </table>  
                 </td> 
                 
             </tr> 
     
    </table>
       
	<br>
    

</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob,preordent,serordent)
  {
  	opener.ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob,preordent,serordent);
	close();
  }

  function ue_buscarcaja()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_caja.php";
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
</html>
