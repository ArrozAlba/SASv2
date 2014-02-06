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
/*$_SESSION["ls_codtienda"]='0002';
$_SESSION["ls_codcaj"]='C02';*/
$ls_codtie=$_SESSION["ls_codtienda"];
$ls_codcaj=$_SESSION["ls_codcaj"];
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
<title>Definici&oacute;n De cuentas Por Pagar</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->
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
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="501" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="277" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
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
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a>
    <a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
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
	$ls_ventanas="sigesp_sfc_d_ctasporpagar.php";

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
	require_once("class_folder/sigesp_sfc_c_ctasporpagar.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sfc_c_secuencia.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("class_folder/sigesp_sfc_class_utilidades.php");
	$io_function=new class_funciones();
	$io_secuencia=new sigesp_sfc_c_secuencia();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_funcdb=new class_funciones_db($io_connect);
	$is_msg=new class_mensajes();
	$io_nota=new sigesp_sfc_c_ctasporpagar();
	$io_connect = $io_include->uf_conectar();
	$io_sql= new class_sql($io_connect);
	$io_datastore= new class_datastore();
	$io_utilidad = new sigesp_sfc_class_utilidades();

/**********************************************************************************************/
/***************    SUBMIT   ******************************************************************/
/**********************************************************************************************/
	if(array_key_exists("operacion",$_POST))
	{

		$ls_operacion=$_POST["operacion"]; /* campo oculto */
		$ls_operacion1=$_POST["operacion1"]; /* campo oculto */
		$ls_codcli=$_POST["txtcodcli"];
		$ls_cedcli=$_POST["txtcedcli"];
		$ls_razcli=$_POST["txtrazcli"];
		$ls_numnot=$_POST["txtnumnot"];
		$ls_dennot=$_POST["txtdennot"];
		$ls_tipnot=$_POST["txttipnot"];
		$ls_fecnot=$_POST["txtfecnot"];
		$ld_monto=$_POST["txtmonto"];
		$ls_estnot=$_POST["txtestnot"];
		$ls_estnotdev=$_POST["cmbestatus"];
		$ls_nro_factura=$_POST["txtnro_factura"];
		$ls_codforpag=$_POST["txtcodforpag"];
		$ls_codciecaj="";
		$ls_estcie="N";
		$ls_hidstatus = $_POST["hidstatus"];

		$ls_codtiend = $_POST["txtcodtie"];
		$ls_destienda = $_POST["txtdestienda"];
		$la_caja = $_POST["txtcodcaja"];
		$ls_descaja = $_POST["txtdescaja"];

    }
/*************************************************************************************/
/****************   NO SUBMIT ********************************************************/
/*************************************************************************************/
	else
	{
		$ls_operacion=""; /* campo oculto */
		$ls_operacion1=""; /* campo oculto */
		$ls_codcli="";
		$ls_cedcli="";
		$ls_razcli="";
		$ls_codciecaj="";
		$ls_numnot="";
		$ls_nro_factura="";
		$ls_dennot="";
		$ls_tipnot="";
		$ls_fecnot="";
		$ld_monto="";
		$ls_estnot="";
		$ls_codforpag="";
		$ls_codciecaj="";
		$ls_hidstatus = "";

	}
/***********************************************************************************************************/
/******** NUEVA -->PREPARANDO INSERCION DE "NUEVA NOTA DE CREDITO" *****************************************/
/***********************************************************************************************************/

	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		$ls_operacion=""; /* campo oculto */
		$ls_operacion1=""; /* campo oculto */
		//$ls_codcaj=$_SESSION["ls_codcaj"];
	    /*$ls_serie=$_SESSION["ls_sernot"];
	    $ls_serie="09";
	    $io_secuencia->uf_ver_secuencia($la_codcaj."not",&$ls_secuencia);
	    $ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
	    $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);*/

		$ls_codcli="";
		$ls_cedcli="";
		$ls_nro_factura="";
		$ls_razcli="";
		$ls_dennot="";
		$ls_estnot="";
		$ls_tipnot="";
		$ls_fecnot="";
		$ld_monto="";
		$ls_codforpag="";
		$ls_estcie="N";
		$ls_codciecaj="";
		$ls_codtiend="";
		$ls_destienda="";
		$la_caja="";
		$ls_descaja="";

	}
elseif($ls_operacion=="ue_nuevanota")
{
	if($ls_tipnot="CXP")
		  $ls_prefijo="NC";
		 else
		  $ls_prefijo="ND";

	$ls_serie=$_SESSION["ls_sernot"];
    //$ls_serie="09";
    $io_secuencia->uf_ver_secuencia($la_caja.$ls_codtie."not",&$ls_secuencia);
    $ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
    $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
}
/*************************************************************************************************/
/***************** GUARDA -->"NUEVA NOTA DE CREDITO"    ******************************************/
/*************************************************************************************************/
elseif($ls_operacion=="ue_guardar")
	{
		/*$ls_sql3="SELECT * FROM sfc_factura " .
				"WHERE codtiend='".$ls_codtie."' and numfac='".$ls_nro_factura."'";*/

		if($ls_operacion1 != "UPDATE"){

			$ls_sql3="SELECT * FROM sfc_factura " .
				"WHERE codtiend='".$ls_codtiend."' AND numfac='".$ls_nro_factura."' AND cod_caja='".$la_caja."' ";

	        //print $ls_sql3;
			$rs_datauni3=$io_sql->select($ls_sql3);

			if($rs_datauni3==false)
			{
				$lb_valido_factura=false;
				$is_msg="Error en uf_select_factura ".$io_funcion->uf_convertirmsg($io_sql->message);
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni3))
				{
					$lb_valido_factura=true; //Registro encontrado
			        $is_msg->message ("No Se Puede Actualizar, Esta Factura Ya Existe!!!");

					/*$ls_sql4="SELECT * FROM sfc_nota " .
							" WHERE codcli ilike ".$ls_codcli." OR numnot ilike '".$ls_numnot."' and codtiend='".$ls_codtie."'";*/

					$ls_sql4="SELECT * FROM sfc_nota " .
							" WHERE codcli ilike ".$ls_codcli." OR numnot ilike '".$ls_numnot."' and codtiend='".$ls_codtiend."'";

		    			$lb_valido4=$io_utilidad->uf_datacombo($ls_sql4,&$la_nota);
						if ($ls_codcli!='0')
						{
							if ($lb_valido4==true)
							{
			   					 $io_datastore->data=$la_nota;

									$ls_codcli=$io_datastore->getValue("codcli",1);
									$ls_nro_factura=$io_datastore->getValue("nro_documento",1);
									$ls_dennot=$io_datastore->getValue("dennot",1);
									$ls_tipnot=$io_datastore->getValue("tipnot",1);
									$ls_fecnot=$io_datastore->getValue("fecnot",1);
									$ld_monto=$io_datastore->getValue("monto",1);
									$ls_codforpag=$io_datastore->getValue("codforpaj",1);
									//$ls_codciecaj=$io_datastore->getValue("codciecaj",1);
									//$ls_estcie=$io_datastore->getValue("estcie",1);
							}
						}
				}
				else
				{
					$lb_valido_factura=false;
				}
			}

		}else{
			$lb_valido_factura=false;
		}


		if ($lb_valido_factura==false)
		{
			if(substr($ls_nro_factura,0,3)!="DEV"){

		       if($ls_operacion1 != "UPDATE"){
		       		$ls_estnot="P";
				   //$ls_codcaj=$_SESSION["ls_codcaj"];

				   if($ls_tipnot="CXP")
				       $ls_prefijo="NC";
				    else
				       $ls_prefijo="ND";

			       $ls_serie=$_SESSION["ls_sernot"];
			       //$ls_serie="09";
		       	   $io_secuencia->uf_obtener_secuencia($la_caja.$ls_codtie."not",&$ls_secuencia);
		       	   $ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
    			   $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
		       }else{
		       	$ls_estnot = $ls_estnotdev;
		       }

			   $lb_valido=$io_nota->uf_guardar_nota($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_nro_factura,$ls_codciecaj,$ls_estcie,$ls_codtiend,$la_seguridad);
			   $ls_mensaje=$io_nota->io_msgc;
			 }
			 else{

			 	if($ls_operacion1 != "UPDATE"){
			 		$ls_estnot="P";
				   //$ls_codcaj=$_SESSION["ls_codcaj"];

				   if($ls_tipnot="CXP")
				       $ls_prefijo="NC";
				    else
				       $ls_prefijo="ND";

			       $ls_serie=$_SESSION["ls_sernot"];
			       //$ls_serie="09";
		       	   $io_secuencia->uf_obtener_secuencia($la_caja.$ls_codtie."not",&$ls_secuencia);
		       	   $ls_secuencia=$io_function->uf_cerosizquierda($ls_secuencia,16);
    			   $ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);

    			   $lb_valido=$io_nota->uf_guardar_nota($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_nro_factura,$ls_codciecaj,$ls_estcie,$ls_codtiend,$la_seguridad);
			   	   $ls_mensaje=$io_nota->io_msgc;
			 	}else{
			 		$ls_fecnot=date("d/m/Y");
			   		$lb_valido=$io_nota->uf_actualizar_nota($ls_codcli,$ls_numnot,$ls_estnotdev,$ls_nro_factura,$ls_fecnot,$ls_codtiend,$la_seguridad);
			   		$ls_mensaje=$io_nota->io_msgc;
			 	}

			 }

			 if ($lb_valido==true) /* SE GUARDA y SE LIMPIAN LAS VARIABLES*/
			  {
				 $is_msg->message ($ls_mensaje);
				 $ls_operacion1=""; /* campo oculto */
				 $ls_codcli="";
				 $ls_cedcli="";
				 $ls_razcli="";
				 $ls_numnot="";
				 $ls_nro_factura="";
				 $ls_dennot="";
				 $ls_tipnot="";
				 $ls_fecnot="";
				 $ld_monto="";
				 $ls_codforpag="";
				 $ls_codciecaj="";
				 $ls_estnot="";

				 $ls_codtiend="";
				 $ls_destienda="";
				 $la_caja="";
				 $ls_descaja="";
			  }
			  else
			  {
				 $is_msg->message ($ls_mensaje);
			  }
	  	}
	}
/**************************************************************************************************************************/
/*********************** ELIMINAR -->"ELIMINA NOTA DE CREDITO"    *************************************************************/
/**************************************************************************************************************************/

	elseif($ls_operacion=="ue_eliminar")
	{
		$ls_sql="SELECT *
                   FROM sfc_instpago
                  WHERE codtiend='".$ls_codtiend."' AND numfac='".$ls_nro_factura."'";
		$rs_datauni=$io_sql->select($ls_sql);

		if($rs_datauni==false)
		{
			$lb_valido_instpago=false;
			$is_msg="Error en uf_select_instpago ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido_instpago=true; //Registro encontrado
		        $is_msg->message ("No Se Puede Eliminar, No Ha Sido Procesada!!!");
			}
			else
			{
				$lb_valido_instpago=false;
			}
		}

		$ls_sql2="SELECT *
                   FROM sfc_instpagocob
                  WHERE codtiend='".$ls_codtiend."' AND numinst='".$ls_nro_factura."'";
		$rs_datauni2=$io_sql->select($ls_sql2);

		if($rs_datauni2==false)
		{
			$lb_valido_instpagocob=false;
			$is_msg="Error en uf_select_instpagocob ".$io_funcion->uf_convertirmsg($io_sql->message);
		}
		else
		{
			if($row=$io_sql->fetch_row($rs_datauni2))
			{
				$lb_valido_instpagocob=true; //Registro encontrado
		        $is_msg->message ("No Se Puede Eliminar, No Ha Sido Procesada!!!");
			}
			else
			{
				$lb_valido_instpagocob=false;
			}
		}
	if (($lb_valido_instpago==false) and ($lb_valido_instpagocob==false))
	 {

		$lb_valido=$io_nota->uf_delete_nota($ls_numnot,$ls_codtiend,$la_seguridad);
		if ($lb_valido===true)
		{
			$is_msg->message($io_nota->io_msgc);
			$ls_operacion1=""; /* campo oculto */
			$ls_codcli="";
			$ls_cedcli="";
			$ls_razcli="";
			$ls_estnot="";
			$ls_numnot="";
			$ls_nro_factura="";
			$ls_dennot="";
			$ls_tipnot="";
			$ls_fecnot="";
			$ld_monto="";
			$ls_codforpag="";
			$ls_codciecaj="";
			$ls_estcie="";

			$ls_codtiend="";
			$ls_destienda="";
			$la_caja="";
			$ls_descaja="";

		}
	 }
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

    <table width="518" height="218" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="216"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="4" class="titulo-ventana">Cuentas Por Pagar </td>
            </tr>
            <tr>
              <td ><input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
              <input name="operacion1" type="hidden" id="operacion1"  value="<? print $ls_operacion1?>">
              <input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>" >
              <input name="txtestnot" type="hidden" id="txtestnot" value="<?php print $ls_estnot ?>"></td>
              <td colspan="3" >&nbsp;</td>
            </tr>

            <tr>
		        <td height="22"><div align="right">Unidad Operativa de Suministro</div></td>
		        <td>
		        	<input name="txtcodtie" type="text" id="txtcodtie" value="<? print $ls_codtiend?>">
		        	<a href="javascript:ue_buscartienda();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		        	<input name="txtdestienda" type="text" id="txtdestienda"   class="sin-borde" value="<? print $ls_destienda?>" size="20" readonly="true">
		        </td>
	      	</tr>


	      	<?php
	      	if($ls_operacion1 != "UPDATE"){ ?>
      		<tr>
	        <td height="22"><div align="right">Caja</div></td>
	        <td>
	        	<input name="txtcodcaja" type="text" id="txtcodcaja" value="<? print $la_caja?>">
	        	<a href="javascript:ue_buscarcaja();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
	        	<input name="txtdescaja" type="text" id="txtdescaja"   class="sin-borde" value="<? print $ls_descaja?>" size="20" readonly="true">
	        </td>
	      	</tr>
	      	<?php } ?>
            <tr>
              <td width="87" height="22" align="right">Cliente </td>
              <td colspan="3" ><input name="txtcedcli" type="text" style="text-align:center " id="txtcedcli" value="<? print  $ls_cedcli?>" size="15" maxlength="15"  readonly="true">
                <a href="javascript:ue_catclientenota();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
                <input name="txtrazcli"  type="text" class="sin-borde" id="txtrazcli" value="<?php print $ls_razcli ?>"  size="40" readonly="true">
                <input name="txtcodcli" type="hidden" style="text-align:center " id="txtcodcli" value="<? print  $ls_codcli?>" size="15" maxlength="15"  readonly="true"></td>
            </tr>

            <tr>
              <td width="87" height="22" align="right">No. nota </td>
              <td width="156" ><input name="txtnumnot" type="text" style="text-align:center " id="txtnumnot" value="<? print  $ls_numnot?>" size="28" maxlength="25"  readonly="true">              </td>
              <td colspan="2" ><span class="titulo-cat&aacute;logo">
        <?php
		if ($ls_estnot=="P" || $ls_estnot=="A")
		{
		?>
                <font color="#006600">PENDIENTE</font>
        <?php
		}
		elseif ($ls_estnot=="C")
		{
		?>
                <font color="#006600">CANCELADA POR CAJA</font>
		 <?php
		}
		elseif ($ls_estnot=="H")
		{
		?>
                <font color="#006600">CANCELADA POR CHEQUE</font>

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
			<?php
			if(substr($ls_nro_factura,0,3)=="DEV"){
			?>
            <tr>
              <td height="24" align="right">Status
              </td>
              <td colspan="3">
                <select name="cmbestatus" size="1" id="cmbestatus">
				   <?php
		              if ($ls_estnot=="P" || $ls_estnot=="A")
		               {
		           ?>
                  <option value="-">Seleccione</option>
				  <option value="P" selected>Pendiente</option>
                  <option value="C">Cancelada por Caja</option>
				  <option value="H">Cancelada por Cheque</option>
				    <?php
		               }
		               elseif ($ls_estnot=="C")
		               {
		            ?>
					<option value="-">Seleccione</option>
				    <option value="P">Pendiente</option>
                    <option value="C" selected>Cancelada por Caja</option>
				    <option value="H">Cancelada por Cheque</option>
					 <?php
		               }
		               elseif ($ls_estnot=="H")
		               {
		            ?>
					<option value="-">Seleccione</option>
				    <option value="P">Pendiente</option>
                    <option value="C">Cancelada por Caja</option>
				    <option value="H" selected>Cancelada por Cheque</option>
					<?php
		                }
		                elseif ($ls_estnot=="")
		                {
		            ?>
					<option value="-" selected>Seleccione</option>
				    <option value="P">Pendiente</option>
                    <option value="C">Cancelada por Caja</option>
				    <option value="H">Cancelada por Cheque</option>
					 <?php
		                 }
		             ?>
                </select>
              </td>
            </tr>
			<?php
			}elseif($ls_nro_factura != ""){ ?>
			<tr>
              <td height="24" align="right">Status
              </td>
              <td colspan="3">
                <select name="cmbestatus" size="1" id="cmbestatus">
                	<?php
		              if ($ls_estnot=="P" || $ls_estnot=="A")
		               {
		           ?>
	                  <option value="-">Seleccione</option>
					  <option value="P" selected>Pendiente</option>
	                  <option value="H">Cancelada por Cheque</option>

	                 <?php
		               }
		               elseif ($ls_estnot=="H")
		               {
		            ?>
					<option value="-">Seleccione</option>
				    <option value="P">Pendiente</option>
				    <option value="H" selected>Cancelada por Cheque</option>
					<?php
		                }
		                elseif ($ls_estnot=="")
		                {
		            ?>
					<option value="-" selected>Seleccione</option>
				    <option value="P">Pendiente</option>
				    <option value="H">Cancelada por Cheque</option>
					 <?php
		                 }
		             ?>
                </select>
              </td>
            </tr>
			<?php
			}
			?>
            <tr>

			  <?php
		if ($ls_operacion="ue_nuevo" or $ls_operacion="ue_nuevanota" or $ls_operacion="ue_cargarnota"){
		?>
			  <td height="24" align="right">No. Factura </td>
              <td><input name="txtnro_factura" type="text" style="text-align:center " id="txtnro_factura" value="<? print  strtoupper($ls_nro_factura) ?>" size="28" maxlength="25"></td>
			  <?php
		}else{
		?>
              <td width="32"><input name="txtnro_factura" type="hidden" style="text-align:center " id="txtnro_factura" value="<? print  $ls_nro_factura?>" size="28" maxlength="25" readonly="true"></td>
			  <?php
		}
		?>
              <td width="229"><input name="txtcodforpag" type="hidden" style="text-align:center " id="txtcodforpag" value="" size="10" maxlength="25"  readonly="true"></td>
            </tr>

            <tr>
              <td height="24"><div align="right">Denominaci&oacute;n:</div></td>
              <td colspan="3"><input name="txtdennot" type="text" style="text-align:left " id="txtdennot" value="<? print  utf8_decode($ls_dennot)?>" size="38" maxlength="35"  >
              <input name="txttipnot" type="hidden" style="text-align:center " id="txttipnot" value="CXP" ></td>
            </tr>
            <tr>
              <td height="8"><div align="right">Fecha</div></td>
              <td colspan="3"><input name="txtfecnot" type="text"  id="txtfecnot"  datepicker="true" value="<? print $ls_fecnot;?>" size="11" maxlength="10" ></td>
            </tr>
            <tr>
              <td height="20"><div align="right">Monto</div></td>
              <td colspan="3"><input name="txtmonto" type="text" id="txtmonto"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<? print $ld_monto;?>"  size="20"></td>
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
		f.operacion1.value="";
		f.txtcodcli.value="";
		f.txtcedcli.value="";
		f.txtrazcli.value="";
		f.txtnumnot.value="";
		f.txtestnot.value="";
		f.txtdennot.value="";
		f.txtfecnot.value="";
		f.txtmonto.value="";
		f.txtnro_factura.value="";
		f.action="sigesp_sfc_d_ctasporpagar.php";
		f.submit();
	}else{
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
			  alert("Las Notas de cr�dito generadas de forma AUTOM�TICA no se pueden modificar.");
			}
			else if (f.txtestnot.value=="C")
			{
			  alert("La Nota de cr�dito esta CANCELADA no se puede modificar.");
			}
			else
			{


			with(f)
				{
					if (ue_valida_null(txtcedcli,"Cliente")==false)
					 {
					 	txtcedcli.focus();
					 }
					else if (ue_valida_null(txtnumnot,"No de Nota de cr�dito")==false)
					 {
						txtnumnot.focus();
					 }
					else if (ue_valida_null(txtdennot,"Denominaci�n de Nota de cr�dito")==false)
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
					 else if (ue_valida_null(txtnro_factura,"Nro de Factura")==false)
					 {
						  txtnro_factura.focus();
					 }
					else
					 {
							f.operacion.value="ue_guardar";
							f.action="sigesp_sfc_d_ctasporpagar.php";
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
			if (ue_valida_null(txtnumnot,"No. Nota de cr�dito")==false)
			{
				txtnumnot.focus();
			}
			else
			{
				if (confirm("� Esta seguro de eliminar este registro ?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_ctasporpagar.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sfc_d_ctasporpagar.php";
					alert("Eliminaci�n Cancelada !!!");
					f.submit();
				}
			}
		}
	}else{
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
		pagina="sigesp_cat_nota.php";
		popupWin(pagina,"catalogo",750,550);
	}else{
		alert("No tiene permiso para realizar esta operacion");
	}

}
function ue_cargarnota(nomcli,codcli,cedcli,numnot,dennot,tipnot,fecnot,monto,estnot,nro_factura1,codforpag,codtienda,dentienda)
{

 	  f=document.form1;
	  f.txtcodcli.value=codcli;
	  f.txtcedcli.value=cedcli;
	  f.txtnumnot.value=numnot;
	  f.txtdennot.value=dennot;
	  f.txtrazcli.value=nomcli;

	  f.txttipnot.value=tipnot;
	  f.txtfecnot.value=fecnot;
	  f.txtmonto.value=monto;
	  f.txtestnot.value=estnot;
	  f.txtnro_factura.value=nro_factura1;
	  f.txtcodforpag.value=codforpag;

	  f.txtcodtie.value=codtienda;
	  f.txtdestienda.value=dentienda;

	  f.operacion.value="";
	  f.operacion1.value="UPDATE";
	  f.hidstatus.value="C";
	  f.action="sigesp_sfc_d_ctasporpagar.php";
	  f.submit();


}
/**********************************************************************************************************************************/
function ue_catclientenota()
{
    f=document.form1;
	f.operacion.value="";

	pagina="sigesp_cat_cliente.php";
	popupWin(pagina,"catalogo",520,450);
}
/*******************  modificada ***************************************/
function ue_cargarcliente(codcli,cedcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,producto,precioestandar,tentierra)
{
	f=document.form1;
	f.txtcedcli.value=cedcli;
	f.txtcodcli.value=codcli;
	f.txtrazcli.value=nomcli;
}
/***********************************************************************************************************************************/

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
		alert('Selecciona la tienda en la que Desea registrar la Cta por Pagar!');
	}else{
		pagina="sigesp_cat_cajatienda.php?codtienda="+tienda ;
		popupWin(pagina,"catalogo_caja",600,250);
	}

}

/////////////////////////////////////////////////////////////////////////////
function ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	f.operacion.value="ue_nuevanota";
	f.txtcodcaja.value=codcaja;
	f.txtdescaja.value=desccaja;
	f.action="sigesp_sfc_d_ctasporpagar.php";
	f.submit();
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
