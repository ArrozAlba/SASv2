<?Php
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
<title>Definici&oacute;n de Forma de Pago </title>
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
    <td width="523" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n </span></td>
    <td width="255" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
	$ls_ventanas="sigesp_sfc_d_formapago.php";

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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("class_folder/sigesp_sfc_c_formapago.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	$io_formapago = new sigesp_sfc_c_formapago();
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$is_msg=new class_mensajes();

	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
/*****************************************************************************************************************************/
/**************************************  SUBMIT    ***************************************************************************/
/*****************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
		$ls_operacion=$_POST["operacion"];
		$ls_codforpag=$_POST["txtcodforpag"];
		$ls_denforpag=$_POST["txtdenforpag"];
		$ls_metforpag=$_POST["cmbmetforpag"];
		$ls_comision=$_POST["txtcomision"];
		$ls_hidstatus=$_POST["hidstatus"];

}
else
{
/*****************************************************************************************************************************/
/************************************** NO SUBMIT  ***************************************************************************/
/*****************************************************************************************************************************/
		$ls_operacion="";
		$ls_codforpag="";
		$ls_denforpag="";
		$ls_metforpag="";
		$ls_comision="0,00";
		$ls_hidstatus="";
}
/*****************************************************************************************************************************/
/**************************************    NUEVO   ***************************************************************************/
/*****************************************************************************************************************************/
if($ls_operacion=="ue_nuevo")
{
	    require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_codforpag=$io_funcdb->uf_generar_codigo(false,0,"sfc_formapago","codforpag",2);
		$ls_denforpag="";
		$ls_comision="0,00";
		$ls_metforpag="S";
		$ls_hidstatus="";

}
/*****************************************************************************************************************************/
/**************************************   GUARDAR  ***************************************************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
{
	$lb_valido=$io_formapago->uf_guardar_formapago($ls_codforpag,$ls_denforpag,$ls_metforpag,$ls_comision,$la_seguridad);
	$ls_mensaje=$io_formapago->io_msgc;
	if($lb_valido===true)
	{
		$is_msg->message ($ls_mensaje);
		$ls_codforpag="";
	    $ls_denforpag="";
		$ls_comision="0,00";
		$ls_metforpag="S";
	}
	else
	{
		if($lb_valido===0)
		{
			$ls_codforpag="";
	        $ls_denforpag="";
			$ls_comision="0,00";
		    $ls_metforpag="S";
	  	}
		else
		{
			$is_msg->message ($ls_mensaje);
		}
	}

}
/*****************************************************************************************************************************/
/**************************************  ELIMINAR  ***************************************************************************/
/*****************************************************************************************************************************/
elseif($ls_operacion=="ue_eliminar")
{

	/**********************  verificar si forma de pago esta enlazada con alguna factura **************************************/
	    $ls_sql="SELECT *
                   FROM sfc_instpago
                  WHERE codemp='".$la_datemp["codemp"]."' AND codforpag='".$ls_codforpag."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_inst=false;
			$is_msg="Error en uf_select_instpago ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_inst=true; //Registro encontrado
		        $is_msg->message ("Una factura esta enlazada a esta forma de pago no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_inst=false; //"Registro no encontrado"
			}
		}


	/****************************************************************************************************************************/
	if ($lb_valido_inst==false)
	 {
		$lb_valido=$io_formapago->uf_delete_formapago($ls_codforpag,$la_seguridad);
		$ls_mensaje=$io_formapago->io_msgc;
		if ($lb_valido===true)
		{
		    $ls_codforpag="";
		    $ls_denforpag="";
			$ls_metforpag="S";
		}
	 }
}
/*****************************************************************************************************************************/
/**************************************  CARGAR    ***************************************************************************/
/*****************************************************************************************************************************/

elseif($ls_operacion=="ue_cargar")
{
	$ls_metforpag=$_POST["hidmetforpag"];
}



?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="2" class="titulo-ventana">Forma de Pago </td>
              </tr>
              <tr>
                <td >
				<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
				<input name="hidmetforpag" type="hidden" id="hidmetforpag" value="<?php print $ls_metforpag?>">				</td>
                <td >&nbsp;</td>
              </tr>
              <tr>
                <td width="134" height="22" align="right"><span class="style2">Codigo </span></td>
                <td width="343" ><input name="txtcodforpag" type="text" id="txtcodforpag" value="<? print  $ls_codforpag ?>" size="2" maxlength="2" readonly="true"></td>
              </tr>
              <tr>
                <td width="134" height="22" align="right">Descripcion</td>
                <td width="343" ><input name="txtdenforpag" type="text" id="txtdenforpag"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  $ls_denforpag ?>" size="65" maxlength="65" >                </td>
              </tr>
              <tr>
                <td height="22" align="right">Metodo</td>
                <td ><select name="cmbmetforpag" size="1" id="cmbmetforpag"  onChange="actualizar_pagina();">
				<?php
				  if(($ls_metforpag=="S")||($ls_metforpag==""))
				   {
				   $ls_comision="0,00";
				?>
                  <option value="S" selected>Seleccione Uno</option>
                  <option value="B">Banco</option>
                  <option value="C">Caja(Efectivo)</option>
				  <option value="H">Caja(Cheque)</option>
                  <option value="D">Documento</option>
				  <?php
				    }
					elseif($ls_metforpag=="B")
					{
				 ?>
				   <option value="S">Seleccione Uno</option>
                   <option value="B" selected>Banco</option>
                   <option value="C">Caja(Efectivo)</option>
				   <option value="H">Caja(Cheque)</option>
                   <option value="D">Documento</option>
				  <?php
				    }
					elseif($ls_metforpag=="C")
					{
					$ls_comision="0,00";
				 ?>
				    <option value="S">Seleccione Uno</option>
                    <option value="B">Banco</option>
                    <option value="C" selected>Caja(Efectivo)</option>
					<option value="H">Caja(Cheque)</option>
                    <option value="D">Documento</option>
				 <?php
				    }
					elseif($ls_metforpag=="H")
					{
					$ls_comision="0,00";
				 ?>
				    <option value="S">Seleccione Uno</option>
                    <option value="B">Banco</option>
                    <option value="C">Caja(Efectivo)</option>
					<option value="H" selected>Caja(Cheque)</option>
                    <option value="D">Documento</option>
				 <?php
				    }
					elseif($ls_metforpag=="D")
					{
					$ls_comision="0,00";
				 ?>
				     <option value="S">Seleccione Uno</option>
                     <option value="B">Banco</option>
                     <option value="C">Caja</option>
					 <option value="H">Caja(Cheque)</option>
                     <option value="D" selected>Documento</option>
				 <?php
				    }
				 ?>
                </select></td>
              </tr>
              <tr>
                <td height="22" align="right">Comisi&oacute;n</td>
                <td >
				<?php
				//print "METFORPAG:".$ls_metforpag;
				if($ls_metforpag=="B")
				{

				?>
				<input name="txtcomision" type="text" id="txtcomision"  onKeyPress=return(currencyFormat(this,'.',',',event))   value="<?php print  $ls_comision ?>"  size="20" maxlength="20"  >
				<?php
				}
				else
				{
				?>
				<input name="txtcomision" type="text" id="txtcomision"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<?php print  $ls_comision ?>" size="20" maxlength="20" readonly="true">
				<?php
				}
				?>


				</td>
              </tr>
              <tr>
                <td height="8">&nbsp;</td>
                <td>&nbsp;</td>
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

/*********************************************************************************************************************************/
function actualizar_pagina()
{
	f=document.form1;
	f.operacion.value="";
	f.action="sigesp_sfc_d_formapago.php";
	f.submit();

}

function ue_nuevo()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtdenforpag.value="";
		f.hidmetforpag.value="";
		f.action="sigesp_sfc_d_formapago.php";
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
		  if (ue_valida_null(txtcodforpag,"Codigo")==false)
		   {
			 txtcodforpag.focus();
		   }
		   else
		   {
			if (ue_valida_null(txtdenforpag,"Descripcion")==false)
			 {
			  txtdenforpag.focus();
			 }
			 else
			 {
			  if (cmbmetforpag.value=="S")
			   {
			     alert("Debe inidicar el metodo asociado a la forma de pago");
				 cmbmetforpag.focus();
			   }
			   else
			   {
			     f.operacion.value="ue_guardar";
			     f.action="sigesp_sfc_d_formapago.php";
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
		if (f.txtcodforpag.value=="")
	    {
	    	alert("No ha seleccionado ning�n registro para eliminar !!!");
	    }
		else
		{
		 if (confirm("� Esta seguro de eliminar este registro ?"))
			   {
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="sigesp_sfc_d_formapago.php";
				 f.submit();
			   }
			else
			   {
				 f=document.form1;
				 f.action="sigesp_sfc_d_formapago.php";
				 alert("Eliminaci�n Cancelada !!!");
				 f.txtcodforpag.value="";
				 f.txtdenforpag.value="";
	             f.hidmetforpag.value="";
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

	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_formapago.php";
		popupWin(pagina,"catalogo",600,250);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/**********************************************************************************************************************************/
function ue_cargarformapago(codigo,nombre,metodo)
{
			f=document.form1;
			f.hidstatus.value="C";
			f.txtcodforpag.value=codigo;
            f.txtdenforpag.value=nombre;
			f.hidmetforpag.value=metodo;
			f.operacion.value="ue_cargar";
			f.submit();
}

/**********************************************************************************************************************************/
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
</script>
</html>