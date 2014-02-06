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
<title>Definici&oacute;n de Clasificaci&oacute;n del Rubro</title>
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
    <td width="559" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="219" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
	$ls_ventanas="sigesp_sfc_d_clasificacionrubro.php";

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
	require_once("class_folder/sigesp_sfc_c_clasificacionrubro.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/grid_param.php");
    $io_grid=new grid_param();
	$io_tipo = new sigesp_sfc_c_clasificacionrubro();
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
		$ls_codclarubro=$_POST["txtcodclarubro"];
		$ls_codclarubromac=$_POST["txtcodclarubromac"];
		$ls_nomclarubro=$_POST["txtnomclarubro"];
		$ls_codrubro=$_POST["txtcodrubro"];
		$ls_codrubromac=$_POST["txtcodrubromac"];
		$ls_nomrubro=$_POST["txtnomrubro"];
		$ls_codrenglon=$_POST["txtcodrenglon"];
		$ls_nomrenglon=$_POST["txtnomrenglon"];
		$ls_codtipoexplotacion=$_POST["txtcodtipoexplotacion"];
		$ls_nomtipoexplotacion=$_POST["txtnomtipoexplotacion"];
		$ls_prodestimada=number_format($_POST["txtprodestimada"],2, ',', '.');
		$ls_animalhas=number_format($_POST["txtnrohas"],2, ',', '.');
		$ls_hidstatus=$_POST["hidstatus"];

	}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
	else
	{
		$ls_operacion="";
		$ls_codclarubro="";
		$ls_codclarubromac="";
		$ls_nomclarubro="";
		$ls_codrubro="";
		$ls_codrubromac="";
		$ls_nomrubro="";
		$ls_codrenglon="";
		$ls_nomrenglon="";
		$ls_codtipoexplotacion="";
		$ls_nomtipoexplotacion="";
		$ls_prodestimada="";
		$ls_animalhas="";
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
		//$ls_codclarubro=$io_funcdb->uf_generar_codigo(false,"","sfc_clasificacionrubro",id_clasificacion,"");
		$ls_codclarubromac='0000';
		$ls_nomclarubro="";
		$ls_codrubro="";
		$ls_codrubromac="";
		$ls_nomrubro="";
		$ls_codrenglon="";
		$ls_nomrenglon="";
		$ls_codtipoexplotacion="";
		$ls_nomtipoexplotacion="";
		$ls_prodestimada="";
		$ls_animalhas="";
		$ls_hidstatus="";
	}
/************************************************************************************************************************/
/***************************   GUARDAR   ********************************************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_guardar")
	{
		$lb_valido=$io_tipo->uf_guardar_clasificacion($ls_codclarubro,$ls_codclarubromac,$ls_nomclarubro,$ls_animalhas,$ls_codrubro,$ls_codrubromac,$ls_prodestimada,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
			print("<script language=JavaScript>");
			print("pagina='sigesp_sfc_d_clasificacionrubro.php';");
		    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    print("</script>");
		}
		else
		{
			if($lb_valido==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_clasificacionrubro.php';");
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
                   FROM sfc_rubroagri_cliente
                  WHERE id_clasificacion='".$ls_codclarubro."'";
		//print $ls_sql;
		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_rubroagri=false;
			$is_msg="Error en uf_select_clasificacionrubro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_rubroagri=true; //Registro encontrado
		        $is_msg->message ("El rubro tiene asociada una clasificacion, no puede ser eliminado!!!");
			}
			else
			{
				$lb_valido_rubroagri=false; //"Registro no encontrado"
			}
		}
	/***********************  verificar si posee "Rubros" ***************************************************************/
	     $ls_sql="SELECT *
                   FROM sfc_rubropec_cliente
                  WHERE id_clasificacion='".$ls_codclarubro."'";

		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_rubropec=false;
			$is_msg="Error en uf_select_clasificacionrubro ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_rubropec=true; //Registro encontrado
		        $is_msg->message ("El rubro tiene asociada una clasificacion, no puede ser eliminado!!!");
			}
			else
			{
				$lb_valido_rubropec=false; //"Registro no encontrado"
			}
		}
	/***********************************************************************************************************************/



	if ($lb_valido_rubroagri==false or $lb_validorubropec==false) // si cliente no posee nota de credito ni cotizaci�n ni factura pendiente ni cobro �eliminar!
	 {
		if($ls_codclarubromac=='0000')
		{
			$lb_valido=$io_tipo->uf_delete_clasificacion($ls_codclarubro,$la_seguridad);
			$ls_mensaje=$io_tipo->io_msgc;
			if ($lb_valido==true)
			{
			    $is_msg->message ($ls_mensaje);
				$ls_codclarubromac="";
				$ls_nomclarubro="";
				$ls_codrubro="";
				$ls_codrubromac="";
				$ls_nomrubro="";
				$ls_codrenglon="";
				$ls_nomrenglon="";
				$ls_codtipoexplotacion="";
				$ls_nomtipoexplotacion="";
				$ls_prodestimada="";
				$ls_animalhas="";
			}
		}
		else
		{
			$is_msg->message ("La Clasificaci�n de Rubro no se puede eliminar, fue registrada por el MPPAT!!!");

		}
	 }
}
/************************************************************************************************************************/
/***************************   VERIFICA SI EL Rubro EXISTE   ****************************************************/
/************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar")
	{

	    $ls_sql="select c.*,r.denominacion as denrubro,r.id_renglon,re.denominacion as denrenglon,re.id_tipoexplotacion,te.denominacion as " .
	    		" denexplotacion from sfc_clasificacionrubro c,sfc_rubro r,sfc_renglon re, sfc_tipoexplotacion as te where c.id_rubro=r.id_rubro " .
	    		" AND c.cod_rubro=r.cod_rubro AND r.id_renglon=re.id_renglon AND r.cod_renglon=re.cod_renglon AND " .
	    		" re.id_tipoexplotacion=te.id_tipoexplotacion AND re.cod_tipoexplotacion=te.cod_tipoexplotacion " .
	    		" AND c.cod_clasificacion ilike '".$ls_codclarubromac."'";


	    $lb_validov=$io_utilidad->uf_datacombo($ls_sql,&$la_cla_rubro);


		if ($ls_codclarubromac!='0000')
		{


			if ($lb_validov==true)
			{

			  $is_msg->message ("La Clasificacion de Rubro esta registrado!!");
			  $io_datastore->data=$la_cla_rubro;
			  $ls_codclarubro=$io_datastore->getValue("id_clasificacion",1);
			  $ls_codclarubromac=$io_datastore->getValue("cod_clasificacion",1);
			  $ls_nomclarubro=$io_datastore->getValue("denominacion",1);
			  $ls_codrubro=$io_datastore->getValue("id_rubro",1);
			  $ls_codrubromac=$io_datastore->getValue("cod_rubro",1);
			  $ls_nomrubro=$io_datastore->getValue("denrubro",1);
			  $ls_codrenglon=$io_datastore->getValue("id_renglon",1);
			  $ls_nomrenglon=$io_datastore->getValue("denrenglon",1);
			  $ls_codtipoexplotacion=$io_datastore->getValue("id_tipoexplotacion",1);
			  $ls_nomtipoexplotacion=$io_datastore->getValue("denexplotacion",1);
			  $ls_prodestimada=$io_datastore->getValue("prod_estimada",1);
			  $ls_animalhas=$io_datastore->getValue("animal_has",1);
			  $ls_prodestimada=number_format($ls_prodestimada,2, ',', '.');
			  $ls_animalhas=number_format($ls_animalhas,2, ',', '.');
			}
			else
			{

				$lb_validov=$io_tipo->uf_guardar_clasificacion($ls_codclarubro,$ls_codclarubromac,strtoupper($ls_nomclarubro),$ls_animalhas,$ls_codrubro,$ls_codrubromac,$ls_prodestimada,$la_seguridad);
				$ls_mensaje=$io_tipo->io_msgc;
				if($lb_validov==true)
				{
					$is_msg->message ($ls_mensaje);
					$ls_codclarubro="";
					$ls_codclarubromac="";
					$ls_nomclarubro="";
					$ls_codrubro="";
					$ls_codrubromac="";
					$ls_nomrubro="";
					$ls_codrenglon="";
					$ls_nomrenglon="";
					$ls_codtipoexplotacion="";
					$ls_nomtipoexplotacion="";
					$ls_prodestimada="";
					$ls_animalhas="";
					$ls_hidstatus="";

					print("<script language=JavaScript>");
					print("pagina='sigesp_sfc_d_clasificacionrubro.php';");
				    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
				    print("</script>");
				}
				else
				{
					if($lb_validov==0)
					{
						print("<script language=JavaScript>");
						print("pagina='sigesp_sfc_d_clasificacionrubro.php';");
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
		$lb_validov=$io_tipo->uf_guardar_clasificacion($ls_codclarubro,$ls_codclarubromac,strtoupper($ls_nomclarubro),$ls_animalhas,$ls_codrubro,$ls_codrubromac,$ls_prodestimada,$la_seguridad);
		$ls_mensaje=$io_tipo->io_msgc;
		if($lb_validov==true)
		{
			$is_msg->message ($ls_mensaje);
			$ls_codclarubro="";
			$ls_codclarubromac="";
			$ls_nomclarubro="";
			$ls_codrubro="";
			$ls_codrubromac="";
			$ls_nomrubro="";
			$ls_codrenglon="";
			$ls_nomrenglon="";
			$ls_codtipoexplotacion="";
			$ls_nomtipoexplotacion="";
			$ls_prodestimada="";
			$ls_animalhas="";
			$ls_hidstatus="";

			print("<script language=JavaScript>");
			print("pagina='sigesp_sfc_d_clasificacionrubro.php';");
		    print(" location.href='sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    print("</script>");
		}
		else
		{
			if($lb_validov==0)
			{
				print("<script language=JavaScript>");
				print("pagina='sigesp_sfc_d_clasificacionrubro.php';");
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
    <table width="807" height="160" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="775" height="200"><div align="center">
            <table width="788"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="2" class="titulo-ventana"> Clasificaci&oacute;n de Rubro </td>
              </tr>
              <tr>
                <td>
				<input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
				<input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
				<input name="txtcodrubromac" type="hidden" style="text-align:center " id="txtcodrubromac" value="<? print  $ls_codrubromac?>" size="15" maxlength="15"  readonly="true">
				<input name="txtcodciclomac" type="hidden" style="text-align:center " id="txtcodciclomac" value="<? print  $ls_codciclomac?>" size="15" maxlength="15"  readonly="true">
				<input name="txtcodciclo" type="hidden" style="text-align:center " id="txtcodciclo" value="<? print  $ls_codciclo?>" size="15" maxlength="15"  readonly="true">
				<input name="txtcodrenglonmac" type="hidden" style="text-align:center " id="txtcodrenglonmac" value="<? print  $ls_codrenglonmac?>" size="15" maxlength="15"  readonly="true">
				<input name="txtnomciclo" type="hidden" style="text-align:center " id="txtnomciclo" value="<? print  $ls_nomciclo?>" size="15" maxlength="15"  readonly="true">

				</td>
              </tr>
              <tr>
               <td width="91" ><input name="txtcodclarubro" type="hidden" id="txtcodclarubro" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_codclarubro?>" size="15" maxlength="15" readonly>
				</td>
			  </tr>
				<tr>
				 <td width="191" height="28" align="right"><span class="style2">C&oacute;digo MPPAT </span></td>
				<td>
				<input name="txtcodclarubromac" type="text" id="txtcodclarubromac" onKeyPress="return validaCajas(this,'a',event)" value="<? print  $ls_codclarubromac?>" size="15" maxlength="15">
				</td>
              </tr>
              <tr>
                <td width="91" height="28" align="right">Denominaci&oacute;n </td>
                <td width="695" ><input name="txtnomclarubro" type="text" id="txtnomclarubro"  onKeyPress="return(validaCajas(this,'x',event,50))"  value="<? print  strtoupper($ls_nomclarubro)?>" size="50" maxlength="100" ></td>
              </tr>
			<tr>
              <td width="91" height="28" align="right">Rubro </td>
              <td>
	              <input name="txtcodrubro" type="text" style="text-align:center " id="txtcodrubro" value="<? print  $ls_codrubro?>" size="15" maxlength="15"  readonly="true">
				  <a href="javascript:ue_catrubro();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Renglones"></a>
	              <input name="txtnomrubro" type="text" id="txtnomrubro" value="<? print $ls_nomrubro;?>" class="sin-borde" size="50" readonly="true">
	          </td>
           </tr>
            <tr>
              <td width="91" height="28" align="right">Rengl&oacute;n </td>
              <td width="695" ><input name="txtcodrenglon" type="text" style="text-align:center " id="txtcodrenglon" value="<? print  $ls_codrenglon?>" size="15" maxlength="15"  readonly="true">
			  <a href="javascript:ue_catrenglon();"></a>
              <input name="txtnomrenglon" type="text" id="txtnomrenglon" value="<? print $ls_nomrenglon;?>" class="sin-borde" size="70" readonly="true"></td>
           </tr>
		   <tr>
              <td width="91" height="28" align="right">Tipo de Explotaci&oacute;n </td>
              <td width="695" ><input name="txtcodtipoexplotacion" type="text" style="text-align:center " id="txtcodtipoexplotacion" value="<? print  $ls_codtipoexplotacion?>" size="15" maxlength="15"  readonly="true">
			 <input name="txtnomtipoexplotacion" type="text" id="txtnomtipoexplotacion" value="<? print $ls_nomtipoexplotacion;?>" class="sin-borde" size="60" readonly="true"></td>
           </tr>
		  <?php
		  if ($ls_codtipoexplotacion!='2')
		  {
		  ?>
		   <tr>
           <td width="91" ><input name="txtnrohas" type='hidden' style="text-align:center " id="txtnrohas" value="<? print  $ls_animalhas?>" size="15" maxlength="15"  onKeyPress="return currencyFormat(this,'.',',',event)" > </td>
		   </tr>
		  <?php
		  }else{
		  ?>
		   <tr>
              <td width="91" height="22" align="right">Animales * Has </td>
              <td width="695" ><input name="txtnrohas" type='text' style="text-align:center " id="txtnrohas" value="<? print  $ls_animalhas?>" size="15" maxlength="15"  onKeyPress="return currencyFormat(this,'.',',',event)" > </td>
		   </tr>
		   <?php
		   }
		   ?>

		   <tr>
              <td width="91" height="22" align="right">Producci&oacute;n Estimada </td>
			  <td width="695" ><input name="txtprodestimada" type="text" style="text-align:center " id="txtprodestimada" value="<? print  $ls_prodestimada?>" size="15" maxlength="15"  onKeyPress="return currencyFormat(this,'.',',',event)" > </td>
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

			//alert ("paso");
			f=document.form1;
			li_incluir=f.incluir.value;
			if(li_incluir==1)
			{
			f.operacion.value="ue_nuevo";
			f.txtcodclarubro.value="";
			f.txtcodclarubromac.value="";
			f.txtnomclarubro.value="";
			f.txtcodrubro.value="";
			f.txtcodrubromac.value="";
			f.txtnomrubro.value="";
			f.txtcodrenglon.value="";
			f.txtnomrenglon.value="";
			f.txtcodtipoexplotacion.value="";
			f.txtnomtipoexplotacion.value="";
			f.txtnrohas.value="";
			f.txtprodestimada.value="";
			f.action="sigesp_sfc_d_clasificacionrubro.php";
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
				  if (ue_valida_null(txtcodclarubromac,"C�digo del Rubro")==false)
				   {
					 txtcodclarubromac.focus();
				   }
				   else
				   if (ue_valida_null(txtnomclarubro,"Denominaci�n")==false)
					 {
					  txtnomrubro.focus();
					 }
					 else
					 {
					 if (ue_valida_null(txtcodrubro,"Ciclo")==false)
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
								if (ue_valida_null(txtprodestimada,"Producci�n Estimada")==false)
								{
								txtcodprodestimada.focus();
								}
								else
								{
								 f.operacion.value="ue_validar";
								 f.action="sigesp_sfc_d_clasificacionrubro.php";
								 f.submit();
								}
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
		if (f.txtcodclarubro.value=="")
			   {
				 alert("No ha seleccionado ning�n registro para eliminar !!!");
			   }
				else
				{
				 if (confirm("� Esta seguro de eliminar este registro ?"))
					   {
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_sfc_d_clasificacionrubro.php";
						 f.submit();
					   }
					else
					   {
						 f=document.form1;
						 f.action="sigesp_sfc_d_clasificacionrubro.php";
						 alert("Eliminaci�n Cancelada !!!");
						f.txtcodclarubro.value="";
						f.txtcodclarubromac.value="";
						f.txtnomclarubro.value="";
						f.txtcodrubro.value="";
						f.txtcodrubromac.value="";
						f.txtnomrubro.value="";
						f.txtcodrenglon.value="";
						f.txtnomrenglon.value="";
						f.txtcodtipoexplotacion.value="";
						f.txtnomtipoexplotacion.value="";
						f.txtnrohas.value="";
						f.txtprodestimada.value="";
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
			pagina="sigesp_cat_clasificacionrubro.php";
			popupWin(pagina,"catalogo",600,250);
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
}

/*******************************************************************************************************************************/

function ue_cargar_clasificacionrubro(id_clasificacion,codclarubromac,nomclarubro,codrubro,codrubromac,nomrubro,codrenglon,nomrenglon,codtipoexplotacion,nomtipoexplotacion,has_animales,prod_estimada)
{
			f=document.form1;
			f.txtcodclarubro.value=id_clasificacion;
			f.hidstatus.value="C";
			f.txtcodclarubromac.value=codclarubromac;
			f.txtnomclarubro.value=nomclarubro;
			f.txtcodrubro.value=codrubro;
			f.txtcodrubromac.value=codrubromac;
			f.txtnomrubro.value=nomrubro;
			f.txtcodrenglon.value=codrenglon;
			f.txtnomrenglon.value=nomrenglon;
			f.txtcodtipoexplotacion.value=codtipoexplotacion;
			f.txtnomtipoexplotacion.value=nomtipoexplotacion;
			f.txtnrohas.value=has_animales;
			f.txtprodestimada.value=prod_estimada;
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
	        f.action="sigesp_sfc_d_clasificacionrubro.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }
function ue_catrubro()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_rubro.php";
	    	popupWin(pagina,"catalogo",520,200);
		}


function ue_cargar_rubro(idrubro,codrubromac,nomrubro,codciclo,codciclomac,nomciclo,codrenglon,codrenglonmac,nomrenglon,codtipoexplotacion,nomtipoexplotacion)
{


			f=document.form1;
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
	return false;
}
</script>
</html>