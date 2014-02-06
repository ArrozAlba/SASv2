<?Php
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
    print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
//$_SESSION["ls_codtienda"]='0002';
$ls_codcaj=$_SESSION["ls_codcaj"];
$ls_codtie=$_SESSION["ls_codtienda"];
if($ls_codcaj=="" || $ls_codcaj=="T")
{
	print "<script language=JavaScript>";
	print "alert('Debe seleccionar una caja para poder procesar datos');";
	print "location.href='../index_modules.php';";
	print "</script>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Nota</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="458" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="320" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
	<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php
/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_ctasporcobrar.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_permisos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_permisos["leer"]=     $_POST["leer"];
			$la_permisos["incluir"]=  $_POST["incluir"];
			$la_permisos["cambiar"]=  $_POST["cambiar"];
			$la_permisos["eliminar"]= $_POST["eliminar"];
			$la_permisos["imprimir"]= $_POST["imprimir"];
			$la_permisos["anular"]=   $_POST["anular"];
			$la_permisos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}

/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once("class_folder/sigesp_sfc_c_ctasporcobrar.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	$io_function=new class_funciones();
	$io_secuencia=new sigesp_sfc_c_secuencia();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$io_sql=new class_sql($io_connect);
 	$io_datastore=new class_datastore();
	$is_msg=new class_mensajes();
	$io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_catscobrar=new sigesp_sfc_c_ctasporcobrar();

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
	if(array_key_exists("operacion",$_POST))
	{

		$ls_operacion=$_POST["operacion"]; /* campo oculto */
		$ls_codcli=$_POST["txtcodcli"];
		$ls_razcli=$_POST["txtnomcli"];
		$ls_cedcli=$_POST["txtrazcli"];

		$ls_numnot=$_POST["txtnumnot"];
		$ls_dennot=$_POST["txtdennot"];
		$ls_tipnot=$_POST["txttipnot"];
		$ls_fecnot=$_POST["txtfecnot"];
		$ld_monto=$_POST["txtmonto"];
		$ld_montoaux=$_POST["txtmontoaux"];

		$ls_codtiend=$_POST["txtcodtie"];
		$ls_destienda=$_POST["txtdestienda"];
		$la_caja=$_POST["txtcodcaja"];
		$ls_descaja=$_POST["txtdescaja"];
		$ls_estnot=$_POST["txtestnot"];
		$ls_nro_factura=$_POST["txtnro_factura"];
		$ls_hidstatus=$_POST["hidstatus"];
		//$ls_resta=$_POST["txtresta"];

		if($ls_operacion=="ue_guardar")
		{
			$ls_resta2=$ld_monto;
		}
		else
		{
			$ls_resta=$_POST["txtresta"];
		}

		$ls_ciecaj="";
    }
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion=""; /* campo oculto */
		$ls_codcli="";
		$ls_razcli="";
		$ls_cedcli="";

		$ls_numnot="";
		$ls_dennot="";
		$ls_tipnot="";
		$ls_fecnot="";
		$ld_monto="0,00";
		$ld_montoaux="0,00";
		$ls_codtiend="";
		$ls_destienda="";
		$la_caja="";
		$ls_descaja="";
		$ls_estnot="";
		$ls_nro_factura="";
		$ls_resta="0,00";
		$ls_ciecaj="";
		$ls_hidstatus="";
	}
/**************************************************************************************************************************/
/*********************** NUEVA -->PREPARANDO INSERCION DE "NUEVA NOTA DE CREDITO" *****************************************/
/**************************************************************************************************************************/
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		//$ls_operacion=""; /* campo oculto */
		//$ls_codcaj=$_SESSION["ls_codcaj"];

		$ls_prefijo="";
	    //$ls_serie=$_SESSION["ls_sernot"];
	    $ls_serie="09";
	    $ls_numnot="";
		$ls_codcli="";

		$ls_cedcli="";
		$ls_razcli="";
		$ls_dennot="";
		$ls_tipnot="";
		$ls_fecnot="";
		$ld_monto="0,00";
		$ld_montoaux="0,00";
		$ls_estnot="";
		$ls_nro_factura="0000000000000000000000000";
		$ls_ciecaj="";
		$ls_estcie="N";
		$ls_resta="0,00";
		$ls_resta2="0,00";

		$ls_codtiend="";
		$ls_destienda="";
		$la_caja="";
		$ls_descaja="";
	}
/**************************************************************************************************************************/
/*********************** GUARDA -->"NUEVA NOTA DE CREDITO"    *************************************************************/
/**************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
	{

		$ls_estnot="P";
		//$ls_codcaj=$_SESSION["ls_codcaj"];
		$ls_tipnot="CXC";
		$ls_ciecaj="";
		$ls_estcie="N";

		if($ls_nro_factura == "0000000000000000000000000"){
			$totrow=0;
		}else{

			$ls_cadena="SELECT sfc_factura.* FROM sfc_factura " .
				"WHERE codtiend='".$ls_codtiend."' AND cod_caja='".$la_caja."' AND numfac='".$ls_nro_factura."' " .
				"AND codcli=".$ls_codcli." ;";
			//print $ls_cadena;

			$arr_pagoctascobrar=$io_sql->select($ls_cadena);

			if($arr_pagoctascobrar==false&&($io_sql->message!=""))
			{
				$is_msg->message("No hay registros de formas de pago");
			}
			else
			{
				if($row=$io_sql->fetch_row($arr_pagoctascobrar))

				  {
					$la_pago=$io_sql->obtener_datos($arr_pagoctascobrar);
					$io_datastore->data=$la_pago;
					$totrow=$io_datastore->getRowCount("numfac");

					for($li_j=1;$li_j<=$totrow;$li_j++)
					{
					  	$ls_nrofac=($io_datastore->getValue("nrofactura",$li_j));

				    }//for
				 }//if registro
			}

		}


		if ($totrow==0)
		{

			$lb_valido=$io_catscobrar->uf_guardar_nota($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_nro_factura,$ls_ciecaj,$ls_estcie,$ls_codtiend,$la_seguridad);
			$ld_montoaux=$ld_monto;

			$ls_mensaje=$io_catscobrar->io_msgc;

			if ($lb_valido==true) /* SE GUARDA y SE LIMPIAN LAS VARIABLES*/
			{
				$is_msg->message ($ls_mensaje);

				/*$ls_estnot="P";
				$lb_valido=$io_nota->uf_update_actualizaestnot($ls_numnot,$ls_estnot);*/

			}
			elseif ($lb_valido==0)
			{
				$ls_codcli="";
				$ls_razcli="";
				$ls_cedcli="";

				$ls_numnot="";
				$ls_dennot="";
				$ls_tipnot="";
				$ls_fecnot="";
				$ld_monto="0,00";
				$ld_montoaux="0,00";
				$ls_nro_factura="0000000000000000000000000";
				$ls_resta="0,00";
				$ls_ciecaj="";
				$ls_estcie="";
				$ls_resta2="0,00";

				$ls_codtiend="";
				$ls_destienda="";
				$la_caja="";
				$ls_descaja="";

			}

		}//if null
		else
		{	/////////////////////
			$is_msg->message ("No se puede generar una NOTA a favor del cliente, Factura ya Existe");
			$ls_estnot="";
			//$ld_monto=$_POST["txtresta"];
		}



	}

/**************************************************************************************************************************/
/*********************** ELIMINAR -->"ELIMINA NOTA DE CREDITO"    *************************************************************/
/**************************************************************************************************************************/

	elseif($ls_operacion=="ue_eliminar")
	{
		$ls_cadena2="SELECT f.* FROM sfc_factura f " .
					"WHERE f.codtiend='".$ls_codtiend."' AND f.numfac='".$ls_nro_factura."' AND f.codcli=".$ls_codcli;

		//print $ls_cadena2;

		$arr_pagoctascobrar2=$io_sql->select($ls_cadena2);

		if($arr_pagoctascobrar2==false&&($io_sql->message!=""))
		{
			//$io_msg->message("No hay registros de formas de pago");
		}
		else
		{
			if($row=$io_sql->fetch_row($arr_pagoctascobrar2))
			{
				$la_pago=$io_sql->obtener_datos($arr_pagoctascobrar2);
				$io_datastore->data=$la_pago;
				$totrow2=$io_datastore->getRowCount("numfac");
			}

			if($ls_nro_factura=="0000000000000000000000000"){
				$totrow2=0;
			}

			if ($totrow2==0)
			{
				/*$ls_cadena="SELECT sfc_cobrofactura.numfac as nrofac,sfc_nota.nro_factura,sfc_nota.codciecaj,sfc_factura.numfac " .
							"FROM sfc_nota,sfc_cobrofactura,sfc_factura " .
							"WHERE sfc_nota.numnot='".$ls_numnot."' AND sfc_cobrofactura.numfac=sfc_nota.nro_factura;";*/

				$ls_cadena="SELECT co.numfac as nrofac, n.nro_documento, n.codciecaj, f.numfac " .
						"FROM sfc_nota n,sfc_dt_cobrocliente co,sfc_factura f " .
						"WHERE n.numnot='".$ls_numnot."' AND co.numfac=n.nro_documento AND n.codcli=".$ls_codcli.";";


				$arr_pagoctascobrar=$io_sql->select($ls_cadena);

				if($arr_pagoctascobrar==false&&($io_sql->message!=""))
				{
					//$io_msg->message("No hay registros de formas de pago");
				}
				else
				{
					if($row=$io_sql->fetch_row($arr_pagoctascobrar))

					  {
						$la_pago=$io_sql->obtener_datos($arr_pagoctascobrar);
						$io_datastore->data=$la_pago;
						$totrow=$io_datastore->getRowCount("nrofac");

						for($li_j=1;$li_j<=$totrow;$li_j++)
						{
							$ls_nrofac=($io_datastore->getValue("nrofac",$li_j));
							$ls_stacodcie=($io_datastore->getValue("codciecaj",$li_j));


						}//for
					 }//if registro

				}

				if (($totrow==0) || ($ls_stacodcie=""))
				{

					$lb_valido=$io_catscobrar->uf_delete_nota($ls_numnot,$ls_codtiend,$la_seguridad);
					if ($lb_valido===true)
					{

						$is_msg->message($io_catscobrar->io_msgc);
						$ls_codcli="";
						$ls_razcli="";
						$ls_cedcli="";
						$ls_numnot="";
						$ls_dennot="";
						$ls_tipnot="";
						$ls_fecnot="";
						$ls_estnot="";
						$ld_monto="0,00";
						$ls_nro_factura="";
						$ls_resta="0,00";
						$ls_resta2="0,00";
						$ls_ciecaj="";
						$ls_estcie="";

						$ls_codtiend="";
						$ls_destienda="";
						$la_caja="";
						$ls_descaja="";
					}
					elseif ($lb_valido==0)
					{
						/*$ls_codcli="";
						$ls_razcli="";

						$ls_numnot="";
						$ls_dennot="";
						$ls_tipnot="";
						$ls_fecnot="";
						$ld_monto="0,00";
						$ls_nro_factura="";
						$ls_resta="0,00";
						$ls_ciecaj="";
						$ls_estcie="";
						$ls_estnot="";*/
					}//elseif lv_valido
				}//if totrwo

				else
				{	/////////////////////
					$is_msg->message ("No se puede Eliminar, la Cuenta por Cobrar tiene Cobro Asociado ó no hay Cierre de
					Caja");
					//print $ls_resta2;
				}
			}
			else
			{
				$is_msg->message ("No se puede Eliminar la Cuenta por Cobrar, Tiene factura Asociada");
				/*$ls_codcli="";
				$ls_razcli="";

				$ls_numnot="";
				$ls_dennot="";
				$ls_tipnot="";
				$ls_fecnot="";
				$ld_monto="0,00";
				$ls_nro_factura="";
				$ls_resta="0,00";
				$ls_resta2="0,00";
				$ls_ciecaj="";
				$ls_estcie="";*/


			}//else Factura

		}//else

    }//if operacion==ue_eliminar

?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{

	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

    <table width="550" height="293" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="545" height="291"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="3" class="titulo-ventana">Cuentas Por Cobrar</td>
            </tr>
            <tr>
              <td ><input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
              <input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>" >
              <input name="txtestnot" type="hidden" id="txtestnot" value="<?php print $ls_estnot ?>"></td>
              <td colspan="2" >&nbsp;</td>
            </tr>

            <tr>
		        <td height="22"><div align="right">Unidad Operativa de Suministro</div></td>
		        <td>
		        	<input name="txtcodtie" type="text" id="txtcodtie" value="<? print $ls_codtiend?>">
		        	<a href="javascript:ue_buscartienda();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		        	<input name="txtdestienda" type="text" id="txtdestienda"   class="sin-borde" value="<? print $ls_destienda?>" size="20" readonly="true">
		        </td>
	      	</tr>

	      	<tr>
		        <td height="22"><div align="right">Caja</div></td>
		        <td>
		        	<input name="txtcodcaja" type="text" id="txtcodcaja" value="<? print $la_caja?>">
		        	<a href="javascript:ue_buscarcaja();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		        	<input name="txtdescaja" type="text" id="txtdescaja"   class="sin-borde" value="<? print $ls_descaja?>" size="20" readonly="true">
		        </td>
	      	</tr>

            <tr>

			  <td width="92" height="22" align="right">Cliente: </td>
              <td colspan="2" >
              	<input name="txtrazcli"  type="text" id="txtrazcli" value="<?php print $ls_cedcli ?>"  size="20" readonly="true">
              	<a href="javascript:ue_catclientenota();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
				<input name="txtnomcli" type="text" style="text-align:center "  class="sin-borde" id="txtnomcli" value="<? print  $ls_razcli?>" size="40" maxlength="40"  readonly="true">
				<input name="txtcodcli" type="hidden" style="text-align:center " id="txtcodcli" value="<? print  $ls_codcli?>" size="15" maxlength="15"  readonly="true">
			  </td>
            </tr>
            <tr>
           <?php
		   if($ls_operacion!=="ue_nuevo")
		   {
		   	//print $ls_operacion." --";
		   ?>

		      <td width="92" height="22" align="right">Nro. Nota: </td>
              <td width="160" ><input name="txtnumnot" type="text" style="text-align:center " id="txtnumnot" value="<? print  $ls_numnot?>" size="28" maxlength="25" >
			     <a href="javascript:ue_cattienda();"></a></td>
              <td width="216" ><span class="titulo-cat&aacute;logo">
        <?php
			 }
		 else
		 {
			?>
			  <td width="92" height="22" align="right">Nro. Nota: </td>
              <td width="160" ><input name="txtnumnot" type="text" style="text-align:center " id="txtnumnot" value="<? print  $ls_numnot?>" size="28" maxlength="25" >
			  <a href="javascript:ue_cattienda();"></a></td>
              <td width="216" ><span class="titulo-cat&aacute;logo">
		<?php
		}


		if ($ls_estnot=="P" || $ls_estnot=="A")
		{
		?>
                <font color="#006600">PENDIENTE</font>
        <?php
		}
		elseif ($ls_estnot=="C")
		{
		?>
                <font color="#006600">CANCELADA</font>
        <?php
		}
		elseif ($ls_estnot=="")
		{

		?>

		     <font color="#006600"></font>
        <?php
		}
		?>
              </span></td>
            </tr>

			<tr>
              <td height="24"><div align="right">Denominaci&oacute;n:</div></td>
              <td colspan="2"><input name="txtdennot" type="text" style="text-align:left " id="txtdennot" value="<? print  $ls_dennot?>" size="38" maxlength="35"></td>
            </tr>


		    <tr>

			 <td colspan="2"><input name="txttipnot" type="hidden" style="text-align:center " id="txttipnot" value="<? print  $ls_tipnot?>" size="28" maxlength="25"  readonly="true">
	          </td>
            </tr>
            <tr>
              <td height="8"><div align="right">Fecha: </div></td>
              <td colspan="2"><input name="txtfecnot" type="text"  id="txtfecnot"  datepicker="true" value="<? print $ls_fecnot;?>" size="11" maxlength="10" ></td>
            </tr>
            <tr>
              <td height="34"><div align="right">Monto:</div></td>
              <td colspan="2">
              	<input name="txtmonto" type="text" id="txtmonto"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<? print $ld_monto;?>"  size="20" >
              	<input name="txtmontoaux" type="hidden" id="txtmontoaux"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<? print $ld_montoaux;?>">
              </td>
            </tr>

		 	<tr>


			 <?php

			 if ($ls_estnot=="P")
				{

				$ls_cadena="SELECT ip.monto as sumar,ip.numinst,ip.numcob,co.estcob,sfc_nota.numnot " .
						"FROM sfc_instpagocob ip,sfc_nota,sfc_cobro_cliente co, sfc_dt_cobrocliente cc " .
						//"WHERE ip.numinst='".$ls_numnot."' AND ip.numcob=co.numcob AND co.numcob=cc.numcob AND cc.numfac='".$ls_numnot."' " .
						"WHERE ip.numcob=co.numcob AND co.numcob=cc.numcob AND cc.numfac='".$ls_numnot."' " .
						"AND co.estcob!='A' AND sfc_nota.numnot='".$ls_numnot."';";

				$arr_pagoctascobrar=$io_sql->select($ls_cadena);
				//print $arr_pagoctascobrar;
				if($arr_pagoctascobrar==false&&($io_sql->message!=""))
				{
					$io_msg->message("No hay registros de formas de pago");
				}
				else
				{
					if($row=$io_sql->fetch_row($arr_pagoctascobrar))

 				 	 {
						$la_pago=$io_sql->obtener_datos($arr_pagoctascobrar);
						$io_datastore->data=$la_pago;
						$totrow=$io_datastore->getRowCount("numinst");
						$ls_acumresta=0;

						for($li_j=1;$li_j<=$totrow;$li_j++)
						{

							$ls_resta=($io_datastore->getValue("sumar",$li_j));
							$ls_acumresta+=$ls_resta;

							/*$ls_resta=number_format($ls_resta,2, ',', '.');
							$ls_resta=str_replace('.','',$ls_resta);
							$ls_resta=str_replace(',','.',$ls_resta);

							$ld_monto=str_replace('.','',$ld_monto);
							$ld_monto=str_replace(',','.',$ld_monto);
							$ls_resta2=($ld_monto-$ls_resta);
							$ls_resta2=number_format($ls_resta2,2, ',', '.');*/

				    	}//for

				    	$ld_monto=str_replace('.','',$ld_monto);
						$ld_monto=str_replace(',','.',$ld_monto);
						$ls_resta2=($ld_monto-$ls_acumresta);
						$ls_resta2=number_format($ls_resta2,2, ',', '.');
					  }//if registro

			 	// else
			 	//{
					if($ls_resta2=="")
					{
					?>

						 <td height="34"><div align="right">Monto Pendiente:</div></td>
            		 	 <td colspan="2"><input name="txtresta" type="text" id="txtresta"
			 			value="<? print $ld_monto;?>"  size="20"  readonly="true"></td>

					<?php
					}//if ls_resta
					else
					{
					?>
						 <td height="34"><div align="right">Monto Pendiente:</div></td>
              			<td colspan="2"><input name="txtresta" type="text" id="txtresta"
						 value="<? print $ls_resta2;?>"  size="20"  readonly="true" ></td>
					<?php
					}
				// }//else ls_resta

			 }//else
			 ?>



       		<?php
				}//if Pendiente
				elseif ($ls_estnot=="C")
				{?>
				  <font color="#006600"></font>

				<?php
				}
			?>
            </tr>

		 	<tr>
				 <?php
			     if(($ls_numnot!==$ls_nro_factura)||($ls_numnot==""))
				  {
					?>
					<td width="92" height="22" align="right" visible="true">Nro. Factura: </td>
		  			<td colspan="2">
						<input name="txtnro_factura" type="text" style="text-align:center " id="txtnro_factura" value="<? print  $ls_nro_factura?>" size="28" maxlength="25" readonly="true" >
					</td>
				    <?php
				  }
				 else
				  {
					?>
				 	<td colspan="2">
					<input name="txtnro_factura" type="hidden" style="text-align:center " id="txtnro_factura" value="<? print  $ls_nro_factura?>" size="28" maxlength="25" readonly="true">
					</td>
				    <?php
				  }
				 ?>
			</tr>

		  </table>
          <p>&nbsp;</p>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT **************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtcodcli.value="";
		f.txtrazcli.value="";

		f.txtnomcli.value=""
		f.txtnumnot.value="";
		f.txtdennot.value="";
		f.txtfecnot.value="";
		f.txtmonto.value="";
		f.txtnro_factura.value="";
		f.txtcodtie.value="";
		f.txtdestienda.value="";
		f.txtcodcaja.value="";
		f.txtdescaja.value="";
		f.action="sigesp_sfc_d_ctasporcobrar.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;

	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

			if (f.txtestnot.value=="A")
			{
			  alert("Las Notas de créito generadas de forma AUTOMÁTICA no se pueden modificar.");
			}
			else if (f.txtestnot.value=="C")
			{
			  alert("La Nota de crédito esta CANCELADA no se puede modificar.");
			}
			else if (f.txtestnot.value=="P" && f.txtresta.value!=f.txtmontoaux.value)
			{
				alert("La Nota de crédito tiene pagos realizados; No se puede modificar.");
			}
			else{

			with(f)
				{
					if (ue_valida_null(txtcodcli,"Cliente")==false)
					 {
					 	txtcodcli.focus();
					 }
					else if (ue_valida_null(txtcodtie,"Código de Tienda")==false)
					 {
						txtnumnot.focus();
					 }
					else if (ue_valida_null(txtcodcaja,"Código de la Caja")==false)
					 {
						txtnumnot.focus();
					 }
					else if (ue_valida_null(txtnumnot,"No de Nota de crédito")==false)
					 {
						txtnumnot.focus();
					 }
					 else if (ue_valida_null(txtrazcli,"No de Nota de crédito")==false)
					 {
						txtrazcli.focus();
					 }
					else if (ue_valida_null(txtdennot,"Denominación de Nota de crédito")==false)
					 {
						  txtdennot.focus();
					  }
					else if (ue_valida_null(txtfecnot,"Fecha")==false)
					 {
						  txtfecnot.focus();
					 }
					else if (ue_valida_null(txtmonto,"Monto")==false)
					 {
						  txtmonto.focus();
					 }
					 else if (ue_valida_null(txtnro_factura,"Nro. Factura")==false)
					 {
						  txtnro_factura.focus();
					 }
					 else
					 {
							f.operacion.value="ue_guardar";
							f.action="sigesp_sfc_d_ctasporcobrar.php";
							f.submit();
					 }

				}
		    }

	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}

function ue_eliminar()
{
	f=document.form1;

	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
		with(f)
		{
			if (ue_valida_null(txtnumnot,"No. Nota de crï¿½dito")==false)
			{
				txtnumnot.focus();
			}
			else if (f.txtestnot.value=="P" && f.txtresta.value!=f.txtmontoaux.value)
			{
				alert("La Nota de crédito tiene pagos realizados; No se puede Eliminar.");
			}
			else
			{
				if (confirm("¿ Está seguro de eliminar este registro ?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_ctasporcobrar.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sfc_d_ctasporcobrar.php";
					alert("Eliminación Cancelada !!!");
					/*f.txtcodcli.value="";
					f.txtrazcli.value="";
					f.txtnomcli.value="";
					f.txtnumnot.value="";
					f.txtdennot.value="";
					f.txtfecnot.value="";
					f.txtestnot.value="";
					f.txtmonto.value="0,00";
					f.txtresta.value="0,00";*/
					f.operacion.value="";
					f.submit();
				}
			}
		}
    }
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;

	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_ctasporcobrar.php";
		popupWin(pagina,"ctasporcobrar",700,500);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/**********************************************************************************************************************************/
function ue_catclientenota()
{
    f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_cliente.php";
	popupWin(pagina,"catalogo",520,450);
}

function ue_cattienda()
{
    f=document.form1;
	f.operacion.value="";
    pagina="sigesp_cat_tienda.php";
	popupWin(pagina,"catalogo",520,450);
}

/////////////////////////////////////////////////////////////////////////////
function ue_buscartienda()
{
    f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_tienda.php" ;
	popupWin(pagina,"catalogo_tiendas",600,250);
}

/////////////////////////////////////////////////////////////////////////////
function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{
	f=document.form1;
	f.txtcodtie.value=codtie;
	f.txtdestienda.value=nomtie;
}

/////////////////////////////////////////////////////////////////////////////
function ue_buscarcaja()
{
    f=document.form1;
	f.operacion.value="";
	tienda=f.txtcodtie.value;
	if(tienda==''){
		alert('Selecciona la tienda en la que Desea registrar la Cta por Cobrar!');
	}else{
		pagina="sigesp_cat_cajatienda.php?codtienda="+tienda ;
		popupWin(pagina,"catalogo_caja",600,250);
	}

}

/////////////////////////////////////////////////////////////////////////////
function ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	f.operacion.value="";
	f.txtcodcaja.value=codcaja;
	f.txtdescaja.value=desccaja;
}

function ue_cargarnota(nomcli,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_factura,cedcli,codtiend,tienda,codcaja)
{
 	  f=document.form1;
	  f.txtcodcli.value=codcli;

	  f.txtnumnot.value=numnot;
	  f.txtdennot.value=dennot;
	  f.txtrazcli.value=cedcli;
	  f.txtnomcli.value=nomcli;
	  f.txttipnot.value=tipnot;
	  f.txtfecnot.value=fecnot;
	  f.txtmonto.value=monto;
	  f.txtmontoaux.value=monto;
	  f.txtestnot.value=estnota;
	  f.txtnro_factura.value=nro_factura;
	  f.txtcodtie.value=codtiend;
	  f.txtdestienda.value=tienda;
	  f.txtcodcaja.value=codcaja;
	 // f.txtresta.value=monto;
	 // alert( f.txtresta.value);
	  f.operacion.value="";
	  f.hidstatus.value = "C";
	  f.action="sigesp_sfc_d_ctasporcobrar.php";

	  f.submit();


}


/*******************  modificada ***************************************/
function ue_cargarcliente(codcli,nomcli,nombre,dircli,telcli,celcli,codpai,codest,codmun,codpar)
{
	f=document.form1;
	f.txtcodcli.value=codcli;
	f.txtrazcli.value=nomcli;
	f.txtnomcli.value=nombre;
}
/***********************************************************************************************************************************/

/*function ue_actumontopen()
{
	f.txtresta.value=$ld_monto;
}*/

function currencyFormat(fld, milSep, decSep, e)
 {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true; // Enter
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del
    key = String.fromCharCode(whichCode); // Get key value from key code
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    len = fld.value.length;
    for(i = 0; i < len; i++)
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
    aux = '';
    for(; i < len; i++)
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '';
    if (len == 1) fld.value = '0'+ decSep + '0' + aux;
    if (len == 2) fld.value = '0'+ decSep + aux;
    if (len > 2)
	{
     aux2 = '';
     for (j = 0, i = len - 3; i >= 0; i--)
	 {
      if (j == 3)
	  {
       aux2 += milSep;
       j = 0;
      }
      aux2 += aux.charAt(i);
      j++;
     }

     fld.value = '';
     len2 = aux2.length;
     for (i = len2 - 1; i >= 0; i--)
      fld.value += aux2.charAt(i);

     fld.value += decSep + aux.substr(len - 2, len);
    }

	//ue_calretencion()
	return false;
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
