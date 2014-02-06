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
$ls_codtie=$_SESSION["ls_codtienda"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Rubro</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="499" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="279" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?Php
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_rubro.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST))
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

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
    /*require_once ("../shared/class_folder/sigesp_include.php");*/
	require_once("class_folder/sigesp_sfc_c_rubro.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	$io_secuencia=new sigesp_sfc_c_secuencia();
    $io_grid=new grid_param();
	$io_tipo = new sigesp_sfc_c_rubro();
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$is_msg=new class_mensajes();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_hidstatus=$_POST["hidstatus"];
		$ls_codrubro=$_POST["txtcodrubro"];
		$ls_codrubromac=$_POST["txtcodrubromac"];
		$ls_nomrubro=$_POST["txtnomrubro"];
		$ls_codciclo=$_POST["txtcodciclo"];
		$ls_codciclomac=$_POST["txtcodciclomac"];
		$ls_nomciclo=$_POST["txtnomciclo"];
		$ls_codrenglon=$_POST["txtcodrenglon"];
		$ls_codrenglonmac=$_POST["txtcodrenglonmac"];
		$ls_nomrenglon=$_POST["txtnomrenglon"];
		$ls_codtipoexplotacion=$_POST["txtcodtipoexplotacion"];
		$ls_nomtipoexplotacion=$_POST["txtnomtipoexplotacion"];
	}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion="";
		$ls_hidstatus="";
		$ls_codrubro="";
		$ls_codrubromac="";
		$ls_nomrubro="";
		$ls_codciclo="";
		$ls_codciclomac="";
		$ls_nomciclo="";
		$ls_codrenglon="";
		$ls_codrenglonmac="";
		$ls_nomrenglon="";
		$ls_codtipoexplotacion="";
		$ls_nomtipoexplotacion="";

	}

/************************************************************************************************************************/
/***************************   NUEVO-> Limpia cajas de textos para nuevo rubro ****************************************/
/************************************************************************************************************************/

	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{

	    require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);


		//$ls_codrubro=$io_funcdb->uf_generar_codigo(false,"","sfc_rubro",id_rubro,"");
		$ls_codrubromac="0000";
		$ls_nomrubro="";
		$ls_codciclo="";
		$ls_codciclomac="";
		$ls_nomciclo="";
		$ls_codrenglon="";
		$ls_codrenglonmac="";
		$ls_nomrenglon="";
		$ls_hidstatus="";
		$ls_codtipoexplotacion="";
		$ls_nomtipoexplotacion="";
	}
/************************************************************************************************************************/
/***************************   GUARDAR   ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_guardar")
	{


		$lb_valido=$io_tipo->uf_guardar_uso($ls_codrubro,$ls_codrubromac,$ls_nomrubro,$ls_codciclo,$ls_codciclomac,$ls_codrenglon,$ls_codrenglonmac,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			print("<script language=JavaScript>");
			print("pagina='sigesp_sfc_d_rubro.php';");
		    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    print("</script>");
		}
		else
		{
			if($lb_valido==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_rubro.php';");
			    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
			    print("</script>");
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
		}
	}
/************************************************************************************************************************/
/***************************   ELIMINAR  ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_eliminar")
	{

	/***********************  verificar si posee "Rubros" ***************************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_clasificacionrubro
                  WHERE cod_rubro='".$ls_codrubromac."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_rubro=false;
			$is_msg="Error en uf_select_clasificacionrubro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_rubro=true; //Registro encontrado
		        $is_msg->message ("El rubro tiene asociada una clasificacion, no puede ser eliminado!!!");
			}
			else
			{
				$lb_valido_rubro=false; //"Registro no encontrado"
			}
		}
	/***********************************************************************************************************************/


	if ($lb_valido_rubro==false) // si cliente no posee nota de credito ni cotizaci�n ni factura pendiente ni cobro �eliminar!
	 {

		if($ls_codrubromac=='0000')
		{
			$lb_valido=$io_tipo->uf_delete_rubro($ls_codrubro,$la_seguridad);
			$ls_mensaje=$io_tipo->io_msgc;
			if ($lb_valido==true)
			{
			    $is_msg->message ($ls_mensaje);
				$ls_codrubro="";
				$ls_codrubromac="";
			    $ls_nomrubro="";
				$ls_codciclo="";
				$ls_codciclomac="";
				$ls_nomciclo="";
				$ls_codrenglon="";
				$ls_codrenglonmac="";
				$ls_nomrenglon="";
				$ls_codtipoexplotacion="";
				$ls_nomtipoexplotacion="";
				$ls_hidstatus="";
			}
		}
		else
		{
			$is_msg->message ("El Rubro no se puede eliminar, fue registrado por el MPPAT!!!");

		}
	  }
}
/************************************************************************************************************************/
/***************************   VERIFICA SI EL Rubro EXISTE   ****************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar")
	{
	    $ls_sql="SELECT u.*,c.denominacion as denciclo,c.cod_ciclo,r.denominacion as denrenglon,r.cod_renglon as renglon,te.id_tipoexplotacion," .
	    		"te.denominacion as dentipoexplotacion FROM sfc_rubro u,sfc_ciclo c, sfc_renglon r,sfc_tipoexplotacion te" .
	    		" WHERE u.id_ciclo=c.id_ciclo AND u.id_renglon=r.id_renglon AND r.id_tipoexplotacion=te.id_tipoexplotacion " .
	    		"AND u.denominacion ilike '".$ls_nomrubro."' OR cod_rubro ilike '".$ls_codrubromac."'";
		//print $ls_sql;
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_tipo_rubro);

		if ($ls_codrubromac!='0000')
		{
			if ($lb_valido==true)
			{

			  $is_msg->message ("El Rubro está registrado!!");
			  $io_datastore->data=$la_tipo_rubro;
			  $ls_codrubro=$io_datastore->getValue("id_rubro",1);
			  $ls_codrubromac=$io_datastore->getValue("cod_rubro",1);
			  $ls_nomrubro=$io_datastore->getValue("denominacion",1);
			  $ls_codciclo=$io_datastore->getValue("id_ciclo",1);
			  $ls_nomciclo=$io_datastore->getValue("denciclo",1);
			  $ls_codciclomac=$io_datastore->getValue("cod_ciclo",1);
			  $ls_codrenglon=$io_datastore->getValue("id_renglon",1);
			  $ls_nomrenglon=$io_datastore->getValue("denrenglon",1);
			  $ls_codrenglonmac=$io_datastore->getValue("renglon",1);
			  $ls_codtipoexplotacion=$io_datastore->getValue("id_tipoexplotacion",1);
			  $ls_nomtipoexplotacion=$io_datastore->getValue("dentipoexplotacion",1);

			}
			else
			{


				$lb_valido=$io_tipo->uf_guardar_rubro($ls_codrubro,$ls_codrubromac,strtoupper($ls_nomrubro),$ls_codciclo,$ls_codciclomac,$ls_codrenglon,$ls_codrenglonmac,$la_seguridad);

				$ls_mensaje=$io_tipo->io_msgc;
				if($lb_valido==true)
				{
					$is_msg->message ($ls_mensaje);
					$ls_hidstatus="";
					$ls_codrubro="";
					$ls_codrubromac="";
				    $ls_nomrubro="";
					$ls_codciclo="";
					$ls_codciclomac="";
					$ls_nomciclo="";
					$ls_codrenglon="";
					$ls_codrenglonmac="";
					$ls_nomrenglon="";
					$ls_codtipoexplotacion="";
					$ls_nomtipoexplotacion="";
					$ls_hidstatus="";

					print("<script language=JavaScript>");
					print("pagina='sigesp_sfc_d_rubro.php';");
				    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
				    print("</script>");
				}
				else
				{
					if($lb_valido==0)
					{
						print("<script language=JavaScript>");
						print("pagina='sigesp_sfc_d_rubro.php';");
					    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
					    print("</script>");
					}
					else
					{
						$is_msg->message ($ls_mensaje);
					}

				}

			}
		}
	else
	{

		$lb_valido=$io_tipo->uf_guardar_rubro($ls_codrubro,$ls_codrubromac,strtoupper($ls_nomrubro),$ls_codciclo,$ls_codciclomac,$ls_codrenglon,$ls_codrenglonmac,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			$ls_hidstatus="";
			$ls_codrubro="";
				$ls_codrubromac="";
			    $ls_nomrubro="";
				$ls_codciclo="";
				$ls_codciclomac="";
				$ls_nomciclo="";
				$ls_codrenglon="";
				$ls_codrenglonmac="";
				$ls_nomrenglon="";
				$ls_codtipoexplotacion="";
				$ls_nomtipoexplotacion="";
				$ls_hidstatus="";

				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_rubro.php';");
			    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
			    print("</script>");
		}
		else
		{
			if($lb_valido==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_rubro.php';");
			    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
			    print("</script>");
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}

		}

		}



	}



?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos))
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
    <table width="737" height="170" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="735" height="170"><div align="center">
            <table width="728"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="2" class="titulo-ventana"> Clasificaci&oacute;n de Rubro </td>
              </tr>
              <tr>
                <td>
				<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
				<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				<input name="txtcodciclomac" type="hidden" style="text-align:center " id="txtcodciclomac" value="<? print  $ls_codciclomac?>" size="15" maxlength="15"  readonly="true">
				<input name="txtcodrenglonmac" type="hidden" style="text-align:center " id="txtcodrenglonmac" value="<? print  $ls_codrenglonmac?>" size="15" maxlength="15"  readonly="true">
				</td>
              </tr>
              <tr>
               <td width="94" ><input name="txtcodrubro" type="hidden" id="txtcodrubro" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_codrubro?>" size="15" maxlength="15" readonly>
				</td>
			  </tr>
				<tr>
				 <td width="94" height="22" align="right">C&oacute;digo MPPAT </td>
				<td>
				<input name="txtcodrubromac" type="text" id="txtcodrubromac" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_codrubromac?>" size="15" maxlength="15" readonly="true">
				</td>
              </tr>
              <tr>
                <td width="94" height="22" align="right">Denominaci&oacute;n </td>
                <td width="197" ><input name="txtnomrubro" type="text" id="txtnomrubro"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  strtoupper($ls_nomrubro)?>" size="50" maxlength="100" ></td>
              </tr>
			 <tr>
              <td width="94" height="22" align="right">Ciclo </td>
              <td width="207" ><input name="txtcodciclo" type="text" style="text-align:center " id="txtcodciclo" value="<? print  $ls_codciclo?>" size="15" maxlength="15"  readonly="true">
              <a href="javascript:ue_catciclo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Ciclos"></a>
              <input name="txtnomciclo" type="text" id="txtnomciclo" value="<? print $ls_nomciclo;?>" class="sin-borde" size="40" readonly="true"></td>
            </tr>
            <tr>
              <td width="94" height="22" align="right">Rengl&oacute;n </td>
              <td width="207">
	              <input name="txtcodrenglon" type="text" style="text-align:center " id="txtcodrenglon" value="<? print  $ls_codrenglon?>" size="15" maxlength="15"  readonly="true">
				  <a href="javascript:ue_catrenglon();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Renglones"></a>
	              <input name="txtnomrenglon" type="text" id="txtnomrenglon" value="<? print $ls_nomrenglon;?>" class="sin-borde" size="60" readonly="true">
	          </td>
           </tr>
		   <tr>
              <td width="114" height="22" align="right">Tipo de Explotaci&oacute;n </td>
              <td width="207" ><input name="txtcodtipoexplotacion" type="text" style="text-align:center " id="txtcodtipoexplotacion" value="<? print  $ls_codtipoexplotacion?>" size="15" maxlength="15"  readonly="true">
			 <input name="txtnomtipoexplotacion" type="text" id="txtnomtipoexplotacion" value="<? print $ls_nomtipoexplotacion;?>" class="sin-borde" size="70" readonly="true"></td>
           </tr>
          </table>
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

/***********************************************************************************************************************************/

function ue_nuevo()
{
			f=document.form1;
			li_incluir=f.incluir.value;
			if(li_incluir==1)
			{
				//alert ("paso");

		    f.operacion.value="ue_nuevo";
			f.txtcodrubro.value="";
			f.txtcodrubromac.value="";
			f.txtnomrubro.value="";
			f.txtcodciclo.value="";
			f.txtcodciclomac.value="";
			f.txtnomciclo.value="";
			f.txtcodrenglon.value="";
			f.txtcodrenglonmac.value="";
			f.txtnomrenglon.value="";
			f.txtcodtipoexplotacion.value="";
			f.txtnomtipoexplotacion.value="";
			f.action="sigesp_sfc_d_rubro.php";
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
			 with(f)
				 {
				  if (ue_valida_null(txtcodrubromac,"C�digo del Rubro")==false)
				   {
					 txtcodrubromac.focus();
				   }
				   else
				   if (ue_valida_null(txtnomrubro,"Denominaci�n")==false)
					 {
					  txtnomrubro.focus();
					 }
					 else
					 {
					 if (ue_valida_null(txtcodciclo,"Ciclo")==false)
						 {
						 txtcodciclouso.focus();
						 }
						 else
						{
						if (ue_valida_null(txtcodrenglon,"Rengl�n")==false)
							{
							txtcodrenglon.focus();
							}
						else
							{
							 f.operacion.value="ue_validar";
							 f.action="sigesp_sfc_d_rubro.php";
							 f.submit();
						 	}
						 }
						}
				   }
	 }
				   	else
	{
	alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_eliminar()
{
		f=document.form1;
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{
			if (f.txtcodrubro.value=="")
			   {
				 alert("No ha seleccionado ning�n registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("�Esta seguro de eliminar este registro ?"))
					   {
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_sfc_d_rubro.php";
						 f.submit();
					   }
					else
					   {
						 f=document.form1;
						 f.action="sigesp_sfc_d_rubro.php";
						 alert("Eliminaci�n Cancelada !!!");
						 f.txtcodrubro.value="";
						 f.txtcodrubromac.value="";
						 f.txtnomrubro.value="";
						 f.txtcodciclo.value="";
						 f.txtcodciclomac.value="";
						 f.txtnomciclo.value="";
						 f.txtcodrenglon.value="";
						 f.txtcodrenglonmac.value="";
						 f.txtnomrenglon.value="";
						 f.txtcodtipoexplotacion.value="";
						 f.txtnomtipoexplotacion.value="";
						 f.submit();
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
			pagina="sigesp_cat_rubro.php";
			popupWin(pagina,"catalogo",600,250);
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
}



function ue_cargar_rubro(idrubro,codrubromac,nomrubro,codciclo,codciclomac,nomciclo,codrenglon,codrenglonmac,nomrenglon,codtipoexplotacion,nomtipoexplotacion)
{


			f=document.form1;

			f.hidstatus.value="C";
			f.txtcodrubro.value=idrubro;
			f.txtcodrubromac.value=codrubromac;
            f.txtnomrubro.value=nomrubro;
			f.txtcodciclo.value=codciclo;
			f.txtcodciclomac.value=codciclomac;
			f.txtnomciclo.value=nomciclo;
			f.txtcodrenglon.value=codrenglon;
			f.txtcodrenglonmac.value=codrenglonmac;
			f.txtnomrenglon.value=nomrenglon;
			f.txtcodtipoexplotacion.value=codtipoexplotacion;
			f.txtnomtipoexplotacion.value=nomtipoexplotacion;
			f.submit();

}

	function EvaluateText(cadena, obj)
		{
		opc = false;

			if (cadena == "%d")
			  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))
			  opc = true;
			if (cadena == "%f"){
			 if (event.keyCode > 47 && event.keyCode < 58)
			  opc = true;
			 if (obj.value.search("[.*]") == -1 && obj.value.length != 0)
			  if (event.keyCode == 46)
			   opc = true;
			}
			 if (cadena == "%s") // toma numero y letras
			 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46))
			  opc = true;
			 if (cadena == "%c") // toma numero y punto
			 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
			  opc = true;
			if(opc == false)
			 event.returnValue = false;
		   }

function ue_validar()
        {
	        f=document.form1;
	        f.action="sigesp_sfc_d_rubro.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }
function ue_catciclo()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_tipo_ciclo.php";
	    	popupWin(pagina,"catalogo",520,200);
		}

function ue_catrenglon()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_renglon.php";
	popupWin(pagina,"catalogo",520,200);
}
function ue_cargar_ciclo(id_ciclo,codciclo,nomciclo)
		{
 	   	    f=document.form1;
			f.txtcodciclo.value=id_ciclo;
			f.txtcodciclomac.value=codciclo;

        	f.txtnomciclo.value=nomciclo;

		}

function ue_cargar_renglon(id_renglon,codrenglon,nomrenglon,codtipoexplotacion,nomtipoexplotacion)
{
	f=document.form1;
	f.txtcodrenglon.value=id_renglon;
	f.txtcodrenglonmac.value=codrenglon;

	f.txtnomrenglon.value=nomrenglon;
	f.txtcodtipoexplotacion.value=codtipoexplotacion;
	f.txtnomtipoexplotacion.value=nomtipoexplotacion;
	}
</script>
</html>