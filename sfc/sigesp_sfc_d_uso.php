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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n del Uso</title>
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
    <td width="500" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="278" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
	$ls_ventanas="sigesp_sfc_d_uso.php";

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
    require_once("class_folder/sigesp_sfc_c_uso.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_funciones.php");
    $io_grid=new grid_param();
	$io_tipo = new sigesp_sfc_c_uso();
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$is_msg=new class_mensajes();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
	$io_funcion=new class_funciones();

/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_coduso=$_POST["txtcoduso"];
		$ls_codusomac=$_POST["txtcodusomac"];
		$ls_nomuso=$_POST["txtnomuso"];
		$ls_descripcion=$_POST["txtdescripcion"];
		$ls_codtipouso=$_POST["txtcodtipouso"];
		$ls_nomtipouso=$_POST["txtnomtipouso"];
		$ls_codactividad=$_POST["txtcodactividad"];
		$ls_nomactividad=$_POST["txtnomactividad"];
		$ls_hidstatus=$_POST["hidstatus"];
	}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion="";
		$ls_coduso="";
		$ls_codusomac="";
		$ls_nomuso="";
		$ls_descripcion="";
		$ls_codtipouso="";
		$ls_nomtipouso="";
		$ls_codactividad="";
		$ls_nomactividad="";
		$ls_hidstatus="";
	}

/************************************************************************************************************************/
/***************************   NUEVO-> Limpia cajas de textos para nuevo cliente ****************************************/
/************************************************************************************************************************/

	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
	    require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_coduso=$io_funcdb->uf_generar_codigo(false,"","sfc_uso",id_uso,"");
		$ls_codusomac='0000';
		$ls_nomuso="";
		$ls_descripcion="";
		$ls_codtipouso="";
		$ls_nomtipouso="";
		$ls_codactividad="";
		$ls_nomactividad="";
		$ls_hidstatus="";
	}
/************************************************************************************************************************/
/***************************   GUARDAR   ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_guardar")
	{
		$lb_valido=$io_tipo->uf_guardar_uso($ls_coduso,$ls_nomuso,$ls_descripcion,$ls_codtipouso,$ls_codactividad,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			print("<script language=JavaScript>");
			print("pagina='sigesp_sfc_d_uso.php';");
		    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    print("</script>");
		}
		else
		{
			if($lb_valido==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_uso.php';");
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

	/***********************  verificar si posee "articulos asociados" ***************************************************************/
	     $ls_sql="SELECT *
                   FROM sim_articulo
                  WHERE id_uso='".$ls_coduso."'";
       // print $ls_sql;

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_rubro=false;
			$is_msg="Error en uf_select_producto ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_rubro=true; //Registro encontrado
		        $is_msg->message ("El Uso esta asociado a un Producto, no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_rubro=false; //"Registro no encontrado"
			}
		}
	/***********************************************************************************************************************/

	if ($lb_valido_rubro==false and $ls_codusomac=='0000') // si cliente no posee nota de credito ni cotizaci�n ni factura pendiente ni cobro �eliminar!
	 {

		$lb_valido=$io_tipo->uf_delete_uso($ls_coduso,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if ($lb_valido==true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_coduso="";
			$ls_codusomac="";
		    $ls_nomuso="";
			$ls_descripcion="";
			$ls_codtipouso="";
			$ls_nomtipouso="";
			$ls_codactividad="";
			$ls_nomactividad="";
			$ls_hidstatus="";
		}
	  }else
	  {
  		$is_msg->message ("El Uso no puede ser eliminado!!!");
	  }
}
/************************************************************************************************************************/
/***************************   VERIFICA SI EL Tipo de Rubro EXISTE   ****************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar")
	{


		$ls_sql="SELECT u.*, t.dentipouso, a.denactividad FROM sfc_uso u,sfc_tipouso t, sfc_actividad a WHERE u.id_tipouso=t.id_tipouso " .
				"AND a.id_actividad=u.id_actividad AND u.codemp=t.codemp AND u.codemp=a.codemp AND t.codemp=a.codemp" .
				" AND u.denuso ilike '".$ls_nomuso."' and u.codusomac='".$ls_codusomac."'";

	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_tipo_rubro);

		if ($ls_codusomac!='0000')
		{
		if ($lb_valido==true)
		{
		  $is_msg->message ("El Uso no puede ser modificado!!");
		  $io_datastore->data=$la_tipo_rubro;
		  $ls_coduso=$io_datastore->getValue("id_uso",1);
		  $ls_codusomac=$io_datastore->getValue("codusomac",1);
		  $ls_nomuso=$io_datastore->getValue("denuso",1);
		  $ls_descripcion=$io_datastore->getValue("dentipouso",1);
		  $ls_codtipouso=$io_datastore->getValue("denactividad",1);
		}
		else{
		$lb_valido=$io_tipo->uf_guardar_uso($ls_coduso,$ls_codusomac,strtoupper($ls_nomuso),$ls_descripcion,$ls_codtipouso,$ls_codactividad,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			$ls_coduso="";
			$ls_codusomac="";
		    $ls_nomuso="";
			$ls_descripcion="";
			$ls_codtipouso="";
			$ls_nomtipouso="";
			$ls_codactividad="";
			$ls_nomactividad="";
			$ls_hidstatus="";
			print("<script language=JavaScript>");
			print("pagina='sigesp_sfc_d_uso.php';");
		    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    print("</script>");
		}
		else
		{
			if($lb_valido==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_uso.php';");
			    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
			    print("</script>");
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}

		}

		}
	}else{
	$lb_valido=$io_tipo->uf_guardar_uso($ls_coduso,$ls_codusomac,strtoupper($ls_nomuso),$ls_descripcion,$ls_codtipouso,$ls_codactividad,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
				$ls_coduso="";
			$ls_codusomac="";
		    $ls_nomuso="";
			$ls_descripcion="";
			$ls_codtipouso="";
			$ls_nomtipouso="";
			$ls_codactividad="";
			$ls_nomactividad="";
			$ls_hidstatus="";

			print("<script language=JavaScript>");
			print("pagina='sigesp_sfc_d_uso.php';");
		    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    print("</script>");
		}
		else
		{
			if($lb_valido==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_uso.php';");
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
    <table width="777" height="100" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="775" height="100"><div align="center">
            <table width="788"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="2" class="titulo-ventana"> Denominaci&oacute;n de Uso </td>
              </tr>
              <tr>
                <td>
				<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
				<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				</td>
              </tr>
              <tr>
               <td width="104" ><input name="txtcoduso" type="hidden" id="txtcoduso" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_coduso?>" size="15" maxlength="15" readonly>
				</td>
			  </tr>
				<tr>
				 <td width="104" height="22" align="right"><span class="style2">C&oacute;digo MPPAT </span></td>
				<td>
				<input name="txtcodusomac" type="text" id="txtcodusomac" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_codusomac?>" size="15" maxlength="15" readonly>
				</td>
              </tr>
              <tr>
                <td width="104" height="22" align="right">Denominaci&oacute;n </td>
                <td width="697" ><input name="txtnomuso" type="text" id="txtnomuso"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  strtoupper($ls_nomuso)?>" size="50" maxlength="100" ></td>
              </tr>
			  <tr>
                <td width="104" height="22" align="right">Descripci&oacute;n</td>
                <td width="697" ><input name="txtdescripcion" type="text" id="txtdescripcion"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  strtoupper($ls_descripcion)?>" size="50" maxlength="100" ></td>
              </tr>
              <tr>
              <td width="104" height="22" align="right">Tipo de Uso </td>
              <td width="697" ><input name="txtcodtipouso" type="text" style="text-align:center " id="txtcodtipouso" value="<? print  $ls_codtipouso?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_cattipouso();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de usos"></a>
              <input name="txtnomtipouso" type="text" id="txtnomtipouso" value="<? print $ls_nomtipouso;?>" class="sin-borde" size="40" readonly="true"></td>
            </tr>
            <tr>
              <td width="20" height="22" align="right">Actividad </td>
              <td><input name="txtcodactividad" type="text" style="text-align:center " id="txtcodactividad" value="<? print  $ls_codactividad?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catactividad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de actividades"></a>
              <input name="txtnomactividad" type="text" id="txtnomactividad" value="<? print $ls_nomactividad;?>" class="sin-borde" size="80" readonly="true"></td>
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
			f.operacion.value="ue_nuevo";
			f.txtcoduso.value="";
			f.txtcodusomac.value="";
			f.txtnomuso.value="";
			f.txtdescripcion.value="";
			f.txtcodtipouso.value="";
			f.txtnomtipouso.value="";
			f.txtcodactividad.value="";
			f.txtnomactividad.value="";
			f.action="sigesp_sfc_d_uso.php";
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
				  if (ue_valida_null(txtcodusomac,"Código del Uso")==false)
				   {
					 txtcodusomac.focus();
				   }
				   else
				   if (ue_valida_null(txtnomuso,"Denominación")==false)
					 {
					  txtnomuso.focus();
					 }
					 else
					 {
					 if (ue_valida_null(txtcodtipouso,"Tipo de Uso")==false)
						 {
						 txtcodtipouso.focus();
						 }
						 else
						{
						if (ue_valida_null(txtcodactividad,"Actividad")==false)
							{
							txtcodactividad.focus();
							}
						else
							{
							 f.operacion.value="ue_validar";
							 f.action="sigesp_sfc_d_uso.php";
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
		if (f.txtcoduso.value=="")
			   {
				 alert("No ha seleccionado ningún registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("¿Esta seguro de eliminar este registro?"))
					   {
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_sfc_d_uso.php";
						 f.submit();
					   }
					else
					   {
						 f=document.form1;
						 f.action="sigesp_sfc_d_uso.php";
						 alert("Eliminación Cancelada !!!");
						 f.txtcoduso.value="";
						 f.txtcodusomac.value="";
						 f.txtnomuso.value="";
						 f.txtdescripcion.value="";
						 f.txtcodtipouso.value="";
						 f.txtnomtipouso.value="";
						 f.txtcodactividad.value="";
						 f.txtnomactividad.value="";
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
			pagina="sigesp_cat_uso.php";
			popupWin(pagina,"catalogo",600,250);
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
}

/*******************************************************************************************************************************/

function ue_cargar_uso(id_uso,codusomac,nomuso,descripcion,codtipouso,nomtipouso,codactividad,nomactividad)
{
			f=document.form1;
			f.txtcoduso.value=id_uso;
			f.hidstatus.value="C";
			f.txtcodusomac.value=codusomac;
            f.txtnomuso.value=nomuso;
			f.txtdescripcion.value=descripcion;
			f.txtcodtipouso.value=codtipouso;
			f.txtnomtipouso.value=nomtipouso;
			f.txtcodactividad.value=codactividad;
			f.txtnomactividad.value=nomactividad;
			f.submit();

}

/***********************************************************************************************************************************/

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
	        f.action="sigesp_sfc_d_uso.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }
function ue_cattipouso()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_tipo_uso.php";
	    	popupWin(pagina,"catalogo",520,200);
		}

function ue_catactividad()
{
	f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_actividad2.php";
	popupWin(pagina,"catalogo",520,200);
}
function ue_cargar_tipo_rubro(codtipouso,nomtipouso)
		{
 	   	    f=document.form1;
			f.txtcodtipouso.value=codtipouso;
        	f.txtnomtipouso.value=nomtipouso;

		}

function ue_cargaractividad(codactividad,nomactividad)
{
	f=document.form1;
	f.txtcodactividad.value=codactividad;
	f.txtnomactividad.value=nomactividad;
	}
</script>
</html>