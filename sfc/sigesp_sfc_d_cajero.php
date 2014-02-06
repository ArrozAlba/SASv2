<?Php
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
<title>Definici&oacute;n de Cajero</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
    <td width="511" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="267" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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

<?php
/////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_cajero.php";

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

//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////
	require_once("class_folder/sigesp_sfc_c_cajero.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	$is_msg=new class_mensajes();
	$io_cajero=new sigesp_sfc_c_cajero();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
/********************************************************************************************************************************/

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codusu=$_POST["txtcodusu"];
		$ls_nomusu=$_POST["txtnomusu"];
		$ls_codtie=$_POST["txtcodtie"];
		$ls_nomtie=$_POST["txtnomtie"];
		$ls_codcaja=$_POST["txtcodcaja"];
		$ls_dencaja=$_POST["txtdencaja"];
		$ls_hidstatus=$_POST["hidstatus"];
    }
	else
	{
		$ls_operacion="";
		$ls_codusu="";
		$ls_nomusu="";
		$ls_codtie="";
		$ls_nomtie="";
		$ls_codcaja="";
		$ls_dencaja="";
		$ls_hidstatus="";

	}

	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		$ls_codusu="";
		$ls_nomusu="";
		$ls_codtie="";
		$ls_nomtie="";
		$ls_codcaja="";
		$ls_dencaja="";
		$ls_hidstatus="";

	}
	elseif($ls_operacion=="ue_guardar")
	{
		$lb_valido=$io_cajero->uf_guardar_cajero($ls_codusu,$ls_nomusu,$ls_codtie,$ls_nomtie,$ls_codcaja,$ls_dencaja,$la_seguridad);
		$ls_mensaje=$io_cajero->io_msgc;

		if ($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			$ls_codusu="";
		    $ls_nomusu="";
		    $ls_codtie="";
		    $ls_nomtie="";
			$ls_codcaja="";
			$ls_dencaja="";

		}else
		{
			if($lb_valido==0)
			{
				$ls_codusu="";
		        $ls_nomusu="";
		        $ls_codtie="";
		        $ls_nomtie="";
				$ls_codcaja="";
				$ls_dencaja="";

			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
		}
	}
/*******************************************************************************************************************************/
/**************************************************      ELIMINAR      *********************************************************/
/*******************************************************************************************************************************/
	elseif($ls_operacion=="ue_eliminar")
	{

	/***********************  verificar si cajero generó "FACTURA" ***************************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_factura
                  WHERE codemp='".$la_datemp["codemp"]."' AND codusu='".$ls_codusu."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_fac=false;
			$is_msg="Error en uf_select_cobro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_fac=true; //Registro encontrado
		        $is_msg->message ("El cajero generó facturas no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_fac=false; //"Registro no encontrado"
			}
		}
	/***********************  verificar si cajero generó "COTIZACION"  **********************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_cotizacion
                  WHERE codemp='".$la_datemp["codemp"]."' AND codusu='".$ls_codusu."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_cot=false;
			$is_msg="Error en uf_select_cobro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_cot=true; //Registro encontrado
		        $is_msg->message ("El cajero generó cotizaciones no se puede eliminar!!!");
			}
			else
			{
				$lb_valido_cot=false; //"Registro no encontrado"
			}
		}


       /********************************************************************************************************************/

	if ($lb_valido_cot==false && $lb_valido_fac==false) // si cliente no posee nota de credito ni cotizaci�n ni factura pendiente ni cobro �eliminar!
	 {
		$lb_valido=$io_cajero->uf_delete_cajero($ls_codusu,$ls_codtie,$la_seguridad);
		if ($lb_valido===true)
		{
			$is_msg->message($io_cajero->io_msgc);
			$ls_codusu="";
			$ls_nomusu="";
			$ls_codtie="";
			$ls_nomtie="";
			$ls_codcaja="";
			$ls_dencaja="";

		}
	  }
}


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
//////////////////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////
?>

    <table width="518" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="195"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Cajero</td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
			  </td>
              <td >&nbsp;</td>
            </tr>

			<tr>
              <td width="113" height="22" align="right">Usuario </td>
              <td width="355" ><input name="txtcodusu" type="text" style="text-align:center " id="txtcodusu" value="<? print  $ls_codusu?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catusuario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomusu" type="text" id="txtnomusu" value="<? print $ls_nomusu;?>" class="sin-borde" size="10" readonly="true"></td>
            </tr>
            <tr>
              <td width="113" height="22" align="right">Unidad Operativa de Suministro </td>
              <td width="355" ><input name="txtcodtie" type="text" style="text-align:left" id="txtcodtie" value="<? print  $ls_codtie?>" size="6" maxlength="4"  readonly="true">
              <a href="javascript:ue_cattienda();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomtie" type="text" id="txtnomtie" value="<? print $ls_nomtie;?>" class="sin-borde" size="40" readonly="true"></td>
            </tr>
			 <tr>
              <td width="113" height="22" align="right">Nro. de Caja </td>
              <td width="355" >
			  <input name="txtcodcaja" type="text" style="text-align:left" id="txtcodcaja" value="<? print  $ls_codcaja?>" size="6" maxlength="4"  readonly="true">
              <a href="javascript:ue_catcaja();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtdencaja" type="text" id="txtdencaja" value="<? print $ls_dencaja;?>" class="sin-borde" size="40" readonly="true"></td>
            </tr>
			 <tr>
                <td height="24" align="right"></td>
                <td><span class="style6">







                </span></td>
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


/*******************************************************************************************************************************/

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtcodusu.value="";
		f.txtnomusu.value="";
		f.txtcodtie.value="";
		f.txtnomtie.value="";
		f.txtcodcaja.value="";
		f.txtdencaja.value="";
		f.action="sigesp_sfc_d_cajero.php";
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
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (ue_valida_null(txtcodtie,"Tienda")==false)
				{
				  txtcodtie.focus();
				}
				else
				{
					if (ue_valida_null(txtcodcaja,"Nro de Caja")==false)
					{
				 	 txtcodcaja.focus();
					}
					else{

					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cajero.php";
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

		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (confirm("¿Esta seguro de eliminar este registro?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_cajero.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sob_d_cajero.php";
					alert("Eliminación Cancelada !!!");
					f.txtcodusu.value="";
					f.txtnomusu.value="";
					f.txtcodtie.value="";
					f.txtnomuni.value="";
					f.txtcodcaja.value="";
					f.txtdencaja.value="";
					f.operacion.value="";
					f.submit();
				}
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
		pagina="sigesp_cat_cajero.php";
		popupWin(pagina,"catalogo",650,300);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/***********************************************************************************************************************************/

	function ue_catusuario()
	{
        f=document.form1;
		f.operacion.value="";
	    pagina="../sss/sigesp_sss_cat_usuarios.php?destino=Reporte";
    	popupWin(pagina,"catalogo",520,200);
	}

	function ue_cattienda()
	{
        f=document.form1;
		f.operacion.value="";
	    pagina="sigesp_cat_tienda.php";
    	popupWin(pagina,"catalogo",520,200);
	}

	function ue_catcaja()
	{
        f=document.form1;
		f.operacion.value="";
	    pagina="sigesp_cat_caja.php";
    	popupWin(pagina,"catalogo",520,200);
	}

	function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,
		items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
	{
   	    f=document.form1;
		f.txtcodtie.value=codtie;
    	f.txtnomtie.value=nomtie;

	}

	function ue_cargarcajero(codusu,nomusu,codtie,nomtie,codcaja,dencaja)
	{
	    f=document.form1;
		f.txtcodusu.value=codusu;
		f.txtnomusu.value=nomusu;
		f.txtcodtie.value=codtie;
		f.txtnomtie.value=nomtie;
		f.txtcodcaja.value=codcaja;
		f.txtdencaja.value=dencaja;
		f.hidstatus.value="C";

   	}

	function ue_cargarcaja(codtien,dentiend,codcaja,dencaja)
	{
   	    f=document.form1;
		f.txtcodcaja.value=codcaja;
    	f.txtdencaja.value=dencaja;

		}

</script>
</html>
