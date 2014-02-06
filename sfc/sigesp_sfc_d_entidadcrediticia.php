<?Php

/******************************************************************************************************************************/
/******************  Creado por:Rosmary Linarez              *****************************************************************/
/******************  Revisado por: Zhuleymar Rodriguez       ****************************************************************/
/***************************************************************************************************************************/

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
<title>Definici&oacute;n de Entidad Crediticia</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>

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
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699" >
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="509" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="269" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
$ls_ventanas="sigesp_sfc_d_entidadcrediticia.php";

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


	require_once("class_folder/sigesp_sfc_c_entidadcrediticia.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("class_folder/sigesp_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/evaluate_formula.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/grid_param.php");

	require_once("../shared/class_folder/sigesp_c_seguridad.php");

	$io_grid=new grid_param();
	$io_include=new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
	$io_funcdb=new class_funciones_db($io_connect);
	$io_secuencia=new sigesp_sfc_c_secuencia();
	$io_funcsob=new sigesp_sob_c_funciones_sob();
	$io_evalform=new evaluate_formula();
	$io_grid=new grid_param();
	$is_msg=new class_mensajes();
	$io_datastore= new class_datastore();
    $io_function=new class_funciones();
	$io_utilidad = new sigesp_sfc_class_utilidades();
	$io_entidadcrediticia = new sigesp_sfc_c_entidadcrediticia();

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];

		$ls_identidad=$_POST["hididentidad"];
		$ls_codentidad=$_POST["txtcodentidad"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_direccion=$_POST["txtdireccion"];
		$ls_telefono=$_POST["txttelefono"];
		$ls_email=$_POST["txtemail"];
		$ls_pagweb=$_POST["txtpagweb"];
		$ls_codest=$_POST["cmbestado"];
		$ls_codmun=$_POST["cmbmunicipio"];
		$ls_codpar=$_POST["cmbparroquia"];
		$ls_codpai=$_POST["hidcodpai"];
		$ls_hidstatus=$_POST["hidstatus"];
	}
	else
	{
		$ls_operacion="";
		$ls_identidad="";
		$ls_codentidad="";
		$ls_denominacion="";
		$ls_direccion="";
		$ls_telefono="";
		$ls_email="";
		$ls_pagweb="";
		$ls_codest="";
		$ls_codmun="";
		$ls_codpar="";
		$ls_codpai="";
		$ls_hidstatus="";
	}
/****************************************************************************************************************************/
/****************************************  Crear Nuevo  ************************************************************************/
/****************************************************************************************************************************/
if($ls_operacion=="ue_nuevo")
{
	$ls_identidad=$io_funcdb->uf_generar_codigo(false,"","sfc_entidadcrediticia","id_entidad","");
	$ls_codentidad=$io_funcdb->uf_generar_codigo(false,0,"sfc_entidadcrediticia","cod_entidad",15);
	$ls_denominacion="";
	$ls_direccion="";
	$ls_telefono="";
	$ls_email="";
	$ls_pagweb="";
	$ls_codest="";
	$ls_codmun="";
	$ls_codpar="";
	$ls_hidstatus="";
}
/****************************************************************************************************************************/
/****************************************  Guardar  ************************************************************************/
/****************************************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
	{

		$lb_valido=$io_entidadcrediticia->uf_guardar_entidadcrediticia($ls_codentidad,$ls_denominacion,$ls_direccion,
		$ls_telefono,$ls_email,$ls_pagweb,$ls_codest,$ls_codpai,$ls_codmun,$ls_codpar,$la_seguridad);
		$ls_mensaje=$io_entidadcrediticia->io_msgc;
		if($lb_valido===true)
		{
			$is_msg->message ($ls_mensaje);

		}
		else
		{
			if($lb_valido===0)
			{
				$ls_identidad="";
				$ls_codentidad="";
				$ls_denominacion="";
				$ls_direccion="";
				$ls_telefono="";
				$ls_email="";
				$ls_pagweb="";
				$ls_codest="";
				$ls_codmun="";
				$ls_codpar="";
				$ls_codpai="";
			}
			else
			{
				$is_msg->message ($ls_mensaje);
			}
		}//else ==0
	}
/****************************************************************************************************************************/
/****************************************  Eliminar  ************************************************************************/
/****************************************************************************************************************************/
elseif($ls_operacion=="ue_eliminar")
{

		$lb_valido=$io_entidadcrediticia->uf_delete_entidadcrediticia($ls_codentidad,$la_seguridad);
		$ls_mensaje=$io_entidadcrediticia->io_msgc;
		if ($lb_valido===true)
		{
		    $is_msg->message ($ls_mensaje);
			$ls_identidad="";
			$ls_codentidad="";
			$ls_denominacion="";
			$ls_direccion="";
			$ls_telefono="";
			$ls_email="";
			$ls_pagweb="";
			$ls_codest="";
			$ls_codmun="";
			$ls_codpar="";
			$ls_codpai="";
			$ls_hidstatus="";
		}
}

/****************************************************************************************************************************/
/****************************************  Cargar desde el Catalogo *************************************************************/
/****************************************************************************************************************************/
elseif($ls_operacion=="ue_cargarentidad")
{
	$ls_codpai=$_POST["hidcodpai"];
	$ls_codest=$_POST["hidcodest"];
	$ls_codmun=$_POST["hidcodmun"];
	$ls_codpar=$_POST["hidcodpar"];
}

/****************************************************************************************************************************/
/****************************************  Valida que el Codigo no se repita ************************************************/
/****************************************************************************************************************************/
	elseif($ls_operacion=="ue_validar")
	{
	   	$ls_sql="SELECT *
                   FROM sfc_entidadcrediticia
                  WHERE denominacion ilike '".$ls_denominacion."' OR cod_entidad ilike '".$ls_codentidad."'";
	    $lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_entidad);
		if ($ls_codentidad!='0')
		{
		if ($lb_valido==true)
		{
		  $is_msg->message ("La Entidad Crediticia está registrada!!");
		  $io_datastore->data=$la_entidad;
		  $ls_identidad=$io_datastore->getValue("id_entidad",1);
		  $ls_codentidad=$io_datastore->getValue("cod_entidad",1);
		  $ls_denominacion=$io_datastore->getValue("denominacion",1);
		  $ls_direccion=$io_datastore->getValue("direccion",1);
		  $ls_telefono=$io_datastore->getValue("telefono",1);
		  $ls_email=$io_datastore->getValue("email",1);
		  $ls_paginaweb=$io_datastore->getValue("paginaweb",1);
		  $ls_codest=$io_datastore->getValue("codest",1);
		  $ls_codpai=$io_datastore->getValue("codpai",1);
		  $ls_codmun=$io_datastore->getValue("codmun",1);
		  $ls_codpar=$io_datastore->getValue("codpar",1);

		}
		else{
		$lb_valido=$io_entidadcrediticia->uf_guardar_entidadcrediticia($ls_codentidad,strtoupper($ls_denominacion),$ls_direccion,
				$ls_telefono,$ls_email,$ls_paginaweb,$ls_codest,$ls_codpai,$ls_codmun,$ls_codpar,$la_seguridad);
		$ls_mensaje=$io_entidadcrediticia->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
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
		}else{
		$lb_valido=$io_entidadcrediticia->uf_guardar_entidadcrediticia($ls_codentidad,strtoupper($ls_denominacion),$ls_direccion,
				$ls_telefono,$ls_email,$ls_pagweb,$ls_codest,$ls_codpai,$ls_codmun,$ls_codpar,$la_seguridad);
		$ls_mensaje=$io_entidadcrediticia->io_msgc;
		if($lb_valido==true)
		{
			$is_msg->message ($ls_mensaje);
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

        <table width="536" height="275" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
        <td width="534" height="273">

		<div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Entidad Crediticia</td>
            </tr>
            <tr>
              <td><input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
                 <input name="hidcodpai" type="hidden" id="hidcodpai" value="<?php print $ls_codpai?>">
                 <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_hidstatus?>">
				<input name="hidcodest" type="hidden" id="hidcodest" value="">
				<input name="hidcodmun" type="hidden" id="hidcodmun" value="">
				<input name="hidcodpar" type="hidden" id="hidcodpar" value="">
			 </td>
              <td >&nbsp;</td>
            </tr>
            <tr>

              <td width="334" ><input name="hididentidad" type="hidden" id="hididentidad" value="<?php print  $ls_identidad?>" size="5" maxlength="4" readonly="true"></td>
            </tr>
			<tr>
              <td width="134" height="22" align="right"><span class="style2">Codigo </span></td>
              <td width="334" ><input name="txtcodentidad" type="text" id="txtcodentidad" onKeyPress="return validaCajas(this,'a',event)"  value="<?php print  $ls_codentidad?>" size="15" maxlength="15" readonly></td>
            </tr>
            <tr>
              <td width="134" height="22" align="right">Denominaci&oacute;n </td>
              <td width="334" ><input name="txtdenominacion" type="text" id="txtdenominacion"  value="<?php print  $ls_denominacion?>" size="50" maxlength="225" ></td>
            </tr>

            <tr>
              <td height="4" align="right">Direcci&oacute;n</td>
              <td><textarea name="txtdireccion" onKeyDown="textCounter(this,254)" onKeyUp="textCounter(this,254)"  onKeyPress="return(validaCajas(this,'x',event,254))" cols="47" rows="2" id="txtdireccion" ><?php print $ls_direccion?></textarea></td>
            </tr>
            <tr align="left">
              <td height="22" align="right"><span class="style2">Telefono</span></td>
              <td><input name="txttelefono" id="txttelefono"  onKeyPress="return validaCajas(this,'i',event)"  value="<? print $ls_telefono?>" type="text" size="20" maxlength="20"></td>
            </tr>

			<tr>
              <td height="22" align="right">Email </td>
              <td ><input name="txtemail" type="text" id="txtemail" value="<?php print $ls_email ?>" size="25" maxlength="25" onBlur="valida_Email(txtemail)"></td>
            </tr>

			<tr>
              <td height="22" align="right">Direcci&oacute;n Web </td>
              <td ><input name="txtpagweb" type="text" id="txtpagweb" value="<?php print $ls_pagweb ?>" size="50" maxlength="50">
			  </td>
            </tr>


		  		<tr>
                <td height="24" align="right">Estado</td>
                <td><span class="style6">
                  <?Php
				   $ls_codpai="058";
				   if($ls_codpai=="")
				    {
						$lb_valest=false;
					}
					else
					 {
				       $ls_sql="SELECT codest ,desest
                                FROM sigesp_estados
                                WHERE codpai='$ls_codpai' ORDER BY codest ASC";
				       $lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_estado);
					 }

					if($lb_valest)
				     {
					   $io_datastore->data=$la_estado;
					   $li_totalfilas=$io_datastore->getRowCount("codest");
				     }
					 else
					 	$li_totalfilas=0;

				    ?>
                <select name="cmbestado" size="1" id="cmbestado" onChange="javascript:ue_llenarcmb();">
                  <option value="">Seleccione...</option>
                  <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					}
	                ?>
                </select>
                </span></td>
              </tr>
              <tr>
                <td height="24" align="right">Municipio</td>
                <td> <span class="style6"><?Php
					$lb_valmun=false;
					if($ls_codest=="")
					{
						$lb_valmun=false;
					}
					else
					 {
						 $ls_sql="SELECT codmun ,denmun,codpai,codest
                                  FROM sigesp_municipio
                                  WHERE codpai='".$ls_codpai."' AND codest='".$ls_codest."' ORDER BY codmun ASC";
				         $lb_valmun=$io_utilidad->uf_datacombo($ls_sql,&$la_municipio);

					 }

					if($lb_valmun)
					{
						$io_datastore->data=$la_municipio;
						$li_totalfilas=$io_datastore->getRowCount("codmun");
					}
					else
					$li_totalfilas=0;
			    ?>
                  <select name="cmbmunicipio" size="1" id="cmbmunicipio" onChange="javascript:ue_llenarcmb();">
                    <option value="">Seleccione...</option>
                    <?php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codmun",$li_i);
						 $ls_denmun=$io_datastore->getValue("denmun",$li_i);
						 if ($ls_codigo==$ls_codmun)
						 {
							  print "<option value='$ls_codigo' selected>$ls_denmun</option>";
						 }
						 else
						 {
							  print "<option value='$ls_codigo'>$ls_denmun</option>";
						 }
					}
	            ?>
                  </select>
				  </span>				  </td>
              </tr>
              <tr>
                <td height="24" align="right">Parroquia</td>
                <td>
				<span class="style6">
				<?Php
				$lb_valpar=false;
			    if($ls_codmun=="")
					{
						$lb_valpar=false;
					}
					else
					 {
						 $ls_sql="SELECT codpar ,denpar
                                  FROM sigesp_parroquia
                                  WHERE codpai='$ls_codpai' AND codest='".$ls_codest."' AND codmun='".$ls_codmun."' ORDER BY codpar ASC";
				         $lb_valpar=$io_utilidad->uf_datacombo($ls_sql,&$la_parroquia);
					 }

					if($lb_valpar)
					{
						$io_datastore->data=$la_parroquia;
						$li_totalfilas=$io_datastore->getRowCount("codpar");
					}
					else{$li_totalfilas=0;}
			    ?>
                  <select name="cmbparroquia" size="1" id="cmbparroquia" onChange="javascript:ue_llenarcmb();">
                    <option value="">Seleccione...</option>
                    <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codpar",$li_i);
					 $ls_denpar=$io_datastore->getValue("denpar",$li_i);
					 if ($ls_codigo==$ls_codpar)
					 {
						  print "<option value='$ls_codigo' selected>$ls_denpar</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_denpar</option>";
					 }
					}
	            ?>
                  </select>
				  </span></td>
              </tr>
            <tr>
              <td height="8">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
	</table>
 </div>
 </td>
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

/**********************************************************************************************************************************/

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.hidcodpai.value="058";
		f.hididentidad.value="";
		f.txtcodentidad.value="";
		f.txtdenominacion.value="";
		f.txttelefono.value="";
		f.txtdireccion.value="";
		f.txtemail.value="";
		f.txtpagweb.value="";
		f.action="sigesp_sfc_d_entidadcrediticia.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
/*********************************************************************************************************************************/
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status =f.hidstatus.value;

	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}
		with(f)
		 {
		  if (ue_valida_null(txtcodentidad,"Codigo")==false)
		   {
			 txtcodentidad.focus();
		   }
		   else
		   {
			if (ue_valida_null(txtdenominacion,"Denominacion")==false)
			 {
			  txtdenominacion.focus();
			 }
			 else
			 {
			  if (ue_valida_null(txtdireccion,"Direccion")==false)
			   {
			    txtdireccion.focus();
			   }
			   else
			   {
			    if (ue_valida_null(txttelefono,"Telefono")==false)
			     {
			      txttelefono.focus();
			     }
				  else
				   {
					if (ue_valida_null(txtemail,"Email")==false)
					 {
					  txtemail.focus();
					 }
					 else
					   {
						if (ue_valida_null(txtpagweb,"Direccion Web")==false)
						 {
						  txtpagweb.focus();
						 }
						else
					   {
						if (ue_valida_null(cmbestado,"Estado")==false)
						 {
						  cmbestado.focus();
						 }
						else
						{
							 if (ue_valida_null(cmbmunicipio,"Municipio")==false)
							 {
							  cmbmunicipio.focus();
							 }
							 else
							 {
								if (ue_valida_null(cmbparroquia,"Parroquia")==false)
								{
								  cmbparroquia.focus();
								}
								else
								{
								   f.operacion.value="ue_guardar";
								   f.action="sigesp_sfc_d_entidadcrediticia.php";
								   f.submit();
								}
							}
					  }
					}
				 }
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
/*********************************************************************************************************************************/
function ue_eliminar()
{

	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
		if (f.txtcodentidad.value=="")
	   {
		 alert("No ha seleccionado ningún registro para eliminar !!!");
	   }
		else
		{
		 if (confirm("¿Esta seguro de eliminar este registro ?"))
			   {
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="sigesp_sfc_d_entidadcrediticia.php";
				 f.submit();
			   }
			else
			   {
				 f=document.form1;
				 f.action="sigesp_sfc_d_entidadcrediticia.php";
				 alert("Eliminación Cancelada !!!");
				 f.submit();
			   }
		}

	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/*********************************************************************************************************************************/

function ue_buscar()
{
    f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_entidadcrediticia.php";
		popupWin(pagina,"catalogo",600,250);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

/***********************************************************************************************************************************/

function ue_cargarentidad(id_entidad,cod_entidad,denominacion,direccion,telefono,email,paginaweb,codest,codpai,codmun,codpar)
{

	f=document.form1;
	f.hididentidad.value=id_entidad;
	f.txtcodentidad.value=cod_entidad;
	f.txtdenominacion.value=denominacion;
	f.txtdireccion.value=direccion;
	f.txttelefono.value=telefono;
	f.txtemail.value=email;
	f.txtpagweb.value=paginaweb;
	f.hidcodpai.value=codpai;
    f.hidcodest.value=codest;
	f.hidcodmun.value=codmun;
	f.hidcodpar.value=codpar;
	//f.hidcodtie.value=codtiend;
	f.hidstatus.value="C";
	f.operacion.value="ue_cargarentidad";
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

/***********************************************************************************************************************************/
function ue_llenarcmb()
        {
	        f=document.form1;
	        f.action="sigesp_sfc_d_entidadcrediticia.php";
			f.hidcodpai.value="058";
	        f.operacion.value="";
	        f.submit();
        }

/***********************************************************************************************************************************/
/*function ue_validar()
        {
	        f=document.form1;
	        f.action="sigesp_sfc_d_entidadrediticia.php";
	        f.operacion.value="ue_validar";
	        f.submit();
        }	*/
</script>
</html>