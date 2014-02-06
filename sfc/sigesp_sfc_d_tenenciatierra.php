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
<title>Tenencia De Tierra</title>
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
    <td width="470" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="308" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
	$ls_ventanas="sigesp_sfc_d_tenenciatierra.php";

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

	require_once("class_folder/sigesp_sfc_c_tenenciatierra.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/grid_param.php");
    $io_grid=new grid_param();
	$io_tipo = new sigesp_sfc_c_tenenciatierra();
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
		$ls_codtenencia=$_POST["txtcodtenencia"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_hidstatus=$_POST["hidstatus"];


	}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion="";
		$ls_denominacion="";
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
		$ls_codtenencia="";
		$ls_denominacion="";
		$ls_hidstatus="";
	}
/************************************************************************************************************************/
/***************************   GUARDAR   ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_guardar")
	{

		$lb_valido=$io_tipo->uf_guardar_tenencia($ls_codtenencia,$ls_denominacion,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			print "PASEEEEE";
		}
		else
		{
			if($lb_valido==0)
			{

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

	/***********************  verificar si posee Clientes Asociados ***************************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_productor
                  WHERE codtenencia='".$ls_codtenencia."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_cliente=false;
			$is_msg="Error en uf_select_cliente ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cliente=true; //Registro encontrado
		        $is_msg->message ("la Tenencia De Tierra tiene un Cliente Asociado, no se puede eliminar!!!");

			}
			else
			{
				$lb_valido_cliente=false; //"Registro no encontrado"
			}
		}


	if ($lb_valido_cliente==false) // si cliente no posee nota de credito ni cotizaci�n ni factura pendiente ni cobro �eliminar!
	 {

		$lb_valido=$io_tipo->uf_delete_tenencia($ls_codtenencia,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if ($lb_valido==true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_codtenencia="";
		    $ls_denominacion="";
		}
	  }
}
/************************************************************************************************************************/
/***************************   VERIFICA SI EXISTE   ****************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar")
	{

		$ls_sql="SELECT *
                   FROM sfc_tenenciatierra
                  WHERE codtenencia ilike '".$ls_codtenencia."' OR denominacion ilike '".$ls_denominacion."' ";

	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_tenencia);
		if ($ls_codtenencia!='0')
		{
			if ($lb_valido==true)
			{
			  $is_msg->message ("La Tenencia de Tierra esta registrada!!");
			  $io_datastore->data=$la_tenencia;
			  $ls_codtenencia=$io_datastore->getValue("codtenencia",1);
			  $ls_denominacion=$io_datastore->getValue("denominacion",1);
			}
		else
		{
			if (($ls_codtenencia<>"") and ($ls_denominacion<>""))
			{

				$lb_valido=$io_tipo->uf_guardar_tenencia($ls_codtenencia,strtoupper($ls_denominacion),$la_seguridad);
				$ls_mensaje=$io_tipo->io_msgc;
				if($lb_valido==true)
				{
					$is_msg->message ($ls_mensaje);

					print("<script language=JavaScript>");
					print("pagina='sigesp_sfc_d_tenenciatierra.php';");
				    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
				    print("</script>");

				}
				else
				{
					if($lb_valido==0)
					{

						print("<script language=JavaScript>");
						print("pagina='sigesp_sfc_d_tenenciatierra.php';");
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
    <table width="518" height="100" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="100"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="2" class="titulo-ventana">Tenencia De Tierra </td>
              </tr>
              <tr>
                <td width="334">
				<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
				<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>"></td>
              </tr>

				<tr>
				 <td width="334" height="22" align="right"><span class="style2">C&oacute;digo </span></td>
				 <td>
				<input name="txtcodtenencia" type="text" id="txtcodtenencia" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_codtenencia?>" size="15" maxlength="15" onBlur="ue_validar()">				</td>
              </tr>
              <tr>
                <td width="334" height="22" align="right">Denominaci&oacute;n </td>
                <td width="334" ><input name="txtdenominacion" type="text" id="txtdenominacion"  onKeyPress="return(validaCajas(this,'a',event))"  value="<? print  strtoupper($ls_denominacion)?>" size="50" maxlength="100" ></td>
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
			f.txtcodtenencia.value="";
			f.txtdenominacion.value="";
			f.action="sigesp_sfc_d_tenenciatierra.php";
			f.submit();
}else
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
				  if (ue_valida_null(txtcodtenencia,"Codigo")==false)
				   {
					 txtcodtenencia.focus();
				   }
				   else
				   {
					if (ue_valida_null(txtdenominacion,"Denominaci�n")==false)
					 {
					  txtdenominacion.focus();
					 }
					 else
					 {
					     f.operacion.value="ue_validar";
					     f.action="sigesp_sfc_d_tenenciatierra.php";
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

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
		if (f.txtcodtenencia.value=="")
			   {
				 alert("No ha seleccionado ning�n registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("� Esta seguro de eliminar este registro ?"))
					   {
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_sfc_d_tenenciatierra.php";
						 f.submit();
					   }
					else
					   {
						 f=document.form1;
						 f.action="sigesp_sfc_d_tenenciatierra.php";
						 alert("Eliminaci�n Cancelada !!!");
						 f.submit();
					   }
				}
	}else
	{
		alert("No tiene permiso para realizar esta operacion");
	}

}


function ue_buscar()
{
            f=document.form1;
			f.operacion.value="";
			li_leer=f.leer.value;
   		    if(li_leer==1)
			{
			pagina="sigesp_cat_tenencia.php";
			popupWin(pagina,"catalogo",600,250);
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}


}

/*******************************************************************************************************************************/

function ue_cargar_tenencia(codtenencia,denominacion)
{
			f=document.form1;
			f.hidstatus.value="C";
			f.txtcodtenencia.value=codtenencia;
            f.txtdenominacion.value=denominacion;
			f.submit();

}

/***********************************************************************************************************************************/

function ue_validar()
        {
	        f=document.form1;
	        f.action="sigesp_sfc_d_tenenciatierra.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }
</script>
</html>