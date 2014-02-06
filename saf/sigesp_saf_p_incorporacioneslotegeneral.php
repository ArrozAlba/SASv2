<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_incorporacioneslotegeneral.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_obtenervalor($as_valor, $as_valordefecto)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_obtenervalor
	//	Access:    public
	//	Arguments:
    // 				as_valor         //  nombre de la variable que desamos obtener
    // 				as_valordefecto  //  contenido de la variable
    // Description: Función que obtiene el valor de una variable si viene de un submit
	//////////////////////////////////////////////////////////////////////////////
		if(array_key_exists($as_valor,$_POST))
		{
			$valor=$_POST[$as_valor];
		}
		else
		{
			$valor=$as_valordefecto;
		}
   		return $valor; 
   }
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_cmpmov,$ls_codres,$ls_codresnew,$ls_nomres,$ls_nomresnew,$ls_descmp,$ld_feccmp,$ls_codcau,$ls_dencau,$ls_estpromov,$ls_status;
 		global $ls_codrespri,$ls_denrespri,$ls_codresuso,$ls_denresuso,$ls_coduniadm,$ls_denuniadm,$ls_ubigeo,$ls_fecent;
		global $ls_tiprespri,$ls_tipresuso,$ls_tiprespri,$ls_tipresuso,$ldt_fecent;
  		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		
		$ls_cmpmov="";
		$ls_codres="";
		$ls_codresnew="";
		$ls_nomres="";
		$ls_nomresnew="";
		$ls_descmp="";
		$ls_codcau="";
		$ls_dencau="";
		$ls_estpromov="";
		$ld_feccmp= date("d/m/Y");
		$ls_status="";		
		$ls_codrespri= "";
		$ls_denrespri= "";
		$ls_codresuso= "";
		$ls_denresuso= "";
		$ls_coduniadm= "";
		$ls_denuniadm= "";
		$ls_ubigeo= "";
		$ls_fecent= "";
		$ls_tiprespri= "";	
		$ls_tipresuso= "";
		$ls_tiprespri= "";	
		$ls_tipresuso= "";
		$ldt_fecent="";
		
		$ls_titletable="Activos";
		$li_widthtable=700;
		$ls_nametable="grid";
		$lo_title[1]="Activo";
		$lo_title[2]="Id. Activo";
		$lo_title[3]="Descripción del Movimiento";
		$lo_title[4]="Monto Activo";
		$lo_title[5]="";
		$li_totrows=1;
   }
   
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=25 maxlength=150 readonly>".
								   "<input name=txtcodact".$ai_totrows." type=hidden id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdesmov".$ai_totrows." type=text   id=txtdesmov".$ai_totrows." class=sin-borde size=45 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." class=sin-borde size=15 readonly>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

   }
   
   function uf_pintardetalle(&$lo_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $aa_object // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar el detalle existente en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 11/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			$ls_codact= $_POST["txtcodact".$li_i];
			$ls_denact= $_POST["txtdenact".$li_i];
			$ls_idact=  $_POST["txtidact".$li_i];
			$ls_desmov= $_POST["txtdesmov".$li_i];
			$li_monact= $_POST["txtmonact".$li_i];

			$lo_object[$li_i][1]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." class=sin-borde size=25 maxlength=150 value='".$ls_denact."' readonly>".
							     "<input name=txtcodact".$li_i." type=hidden id=txtcodact".$li_i." class=sin-borde size=17 maxlength=15  value='".$ls_codact."' readonly>";
			$lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text   id=txtidact".$li_i."  class=sin-borde size=17 maxlength=15  value='".$ls_idact."'  readonly>";
			$lo_object[$li_i][3]="<input name=txtdesmov".$li_i." type=text   id=txtdesmov".$li_i." class=sin-borde size=52 maxlength=150 value='".$ls_desmov."' readonly>";
			$lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text   id=txtmonact".$li_i." class=sin-borde size=15 maxlength=150 value='".$li_monact."' readonly>";
			$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
		}
		uf_agregarlineablanca($lo_object,$ai_totrows);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Incorporaciones por Lote </title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
   <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
   <!-- <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Grabar" title="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fundb= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("sigesp_saf_c_movimiento.php");
	$io_saf= new sigesp_saf_c_movimiento();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_totrows = uf_obtenervalor("totalfilas",1);	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="NUEVO";
		uf_limpiarvariables();
		//uf_agregarlineablanca($lo_object,$li_totrows);
		$ls_readonly="readonly";
	}

	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_readonly="";
			$ls_emp="";
			$ls_codemp="";
			$ls_tabla="saf_movimiento";
			$ls_columna="cmpmov";
			$ls_cmpmov=$io_fundb->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			//uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
		case "GUARDAR":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_codusureg=$_SESSION["la_logusr"];
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$ls_status=$_POST["hidstatus"];
			$ld_date=date("Y-m-d");
			$ls_codrespri= $_POST["txtcodrespri"];
			$ls_codresuso= $_POST["txtcodresuso"];
			$ls_coduniadm= $_POST["txtcoduniadm"];
			$ls_denuniadm= $_POST["txtdenuniadm"];
			$ls_ubigeo="";
			$ls_tiprespri= $_POST["cmbtiprespri"];	
			$ls_tipresuso= $_POST["cmbtipresuso"];
			$ldt_fecent=$_POST["txtfecent"];
			$ls_fecent=$_POST["txtfecent"];
			$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_feccmpbd);
			if($lb_valido)
			{
				if(($ls_cmpmov!="")&&($ls_codcau!=""))
				{
					$ls_estpromov="0";
					$ls_codpro="----------";
					$ls_cedbene="----------";
					$ls_codtipdoc="";
	
					$lb_valido=$io_saf->uf_saf_guardar_en_lote($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd,$ls_descmp,
															   $ls_codpro,$ls_cedbene,$ls_codtipdoc,$ls_codusureg,$ls_estpromov,$ls_codrespri,$ls_codresuso,$ls_coduniadm,
															   $ls_ubigeo,$ls_tiprespri,$ls_tipresuso,$ldt_fecent,$la_seguridad);
				   // $lb_valido=$io_saf->uf_saf_guardar_en_lote($ls_codemp,$ls_cmpmov,$ls_codcau,$ld_feccmpbd,$ls_descmp,$ls_codpro,$ls_cedbene,$ls_codtipdoc,$ls_codusureg,$ls_estpromov,$la_seguridad);
					if($lb_valido)
					{
						$io_msg->message("La incorporacion en lote fue procesada con exito");
						$ls_estpromov=0;
						uf_limpiarvariables();
					}
					else
					{
						$io_msg->message("La incorporacion no fue procesada");
					}
				}
				else
				{
					$io_msg->message("Debe llenar todos los datos");
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				uf_limpiarvariables();
			}
		break;

		case "PROCESAR":
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_status=$_POST["hidstatus"];
			$ls_codrespri= $_POST["txtcodrespri"];
			$ls_codresuso= $_POST["txtcodresuso"];
			$ls_coduniadm= $_POST["txtcoduniadm"];
			$ls_denuniadm= $_POST["txtdenuniadm"];
			$ls_ubigeo= $_POST["txtubigeo"];
			$ls_tiprespri= $_POST["cmbtiprespri"];	
			$ls_tipresuso= $_POST["cmbtipresuso"];
			$ldt_fecent=$_POST["txtfecent"];
			$ls_fecent=$_POST["txtfecent"];
			$ls_estpromov=1;
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_procesarincorporacion($ls_codemp,$ls_cmpmov,$ls_codcau,$ls_estpromov,
																		$ls_codrespri,$ls_codresuso,$ls_coduniadm,$ls_denuniadm,
																		$ls_ubigeo,$ls_tiprespri,$ls_tipresuso,$ldt_fecent,
																		$la_seguridad);
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El registro fue procesado con exito");
					uf_agregarlineablanca($lo_object,1);
					uf_limpiarvariables();
					$li_totrows=1;
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar el registro");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,1);
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_limpiarvariables();
			}
		break;

		case "REVERSAR":
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_status=$_POST["hidstatus"];
			$ls_estpromov=0;
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_procesarincorporacion($ls_codemp,$ls_cmpmov,$ls_codcau,$ls_estpromov,$la_seguridad);
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El registro fue reversado con exito");
					uf_agregarlineablanca($lo_object,1);
					uf_limpiarvariables();
					$li_totrows=1;
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo reversar el registro");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,1);
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_limpiarvariables();
			}
		break;

		case "ELIMINARDETALLE":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codact= $_POST["txtcodact".$li_i];
					$ls_denact= $_POST["txtdenact".$li_i];
					$ls_idact=  $_POST["txtidact".$li_i];
					$ls_desmov= $_POST["txtdesmov".$li_i];
					$li_monact= $_POST["txtmonact".$li_i];
					
					$lo_object[$li_temp][1]="<input name=txtdenact".$li_temp." type=text   id=txtdenact".$li_temp." class=sin-borde size=25 maxlength=150 value='".$ls_denact."' readonly>".
											"<input name=txtcodact".$li_temp." type=hidden id=txtcodact".$li_temp." class=sin-borde size=17 maxlength=15  value='".$ls_codact."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtidact".$li_temp."  type=text   id=txtidact".$li_temp."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtdesmov".$li_temp." type=text   id=txtdesmov".$li_temp." class=sin-borde size=52 value='". $ls_desmov ."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtmonact".$li_temp." type=text   id=txtmonact".$li_temp." class=sin-borde size=15 value='". $li_monact ."' readonly>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				}
				else
				{
					$li_rowdelete= 0;
				}
			}
			if ($li_temp==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			else
			{				
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
		break;
	
		case "BUSCARDETALLE":
			uf_limpiarvariables();
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ls_codcau=$_POST["txtcodcau"];
			$ls_dencau=$_POST["txtdencau"];
			$ld_feccmp=$_POST["txtfeccmp"];
			$ls_descmp=$_POST["txtdescmp"];
			$ls_estpromov=$_POST["hidestpromov"];
			$ls_status=$_POST["hidstatus"];
			$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
			$li_montot="";

			$lb_valido=$io_saf->uf_siv_load_dt_movimiento($ls_codemp,$ls_cmpmov,$ld_feccmpbd,$li_totrows,$lo_object,$li_montot);
			
		break;
	}
?>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="740" height="159" border="0" class="formato-blanco">
      <tr>
        <td width="724" ><div align="left">
            <table width="706" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="3" class="titulo-ventana">Incorporaciones por Lote General </td>
              </tr>
              <tr class="formato-blanco">
                <td width="110" height="19">&nbsp;</td>
                <td width="480"><div align="right">Fecha</div></td>
                <td width="114"><input name="txtfeccmp" type="text" id="txtfeccmp" style="text-align:center " value="<?php print $ld_feccmp ?>" size="13" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="29"><div align="right">Comprobante</div></td>
                <td height="29" colspan="2">
                  <input name="txtcmpmov" type="text" id="txtcmpmov" value="<?php print $ls_cmpmov ?>" size="20" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center " readonly>
                  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="29"><div align="right">Causa de Movimiento</div></td>
                <td height="29" colspan="2"><input name="txtcodcau" type="text" id="txtcodcau" value="<?php print $ls_codcau ?>" size="10" style="text-align:center " readonly>
                  <a href="javascript: ue_catacausas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdencau" type="text" class="sin-borde" id="txtdencau" value="<?php print $ls_dencau ?>" size="50" readonly></td>
              </tr>
              <tr>
                <td height="20"><div align="right">Responsable Primario</div></td>
                <td height="20" colspan="2"><select name="cmbtiprespri" id="cmbtiprespri" onChange="javascript: ue_catalogo_responsable_primario();">
                    <option value="-" selected>-- Seleccione Uno --</option>
                    <option value="P" <?php if($ls_tiprespri=="P"){ print "selected";} ?>>PERSONAL</option>
                    <option value="B" <?php if($ls_tiprespri=="B"){ print "selected";} ?>>BENEFICIARIO</option>
                  </select>
                    <input name="txtcodrespri" type="text"  style="text-align:center" class="sin-borde" id="txtcodrespri" value="<?php print $ls_codrespri; ?>" size="15" maxlength="10" readonly>
                    <input name="txtdenrespri" type="text"  style="text-align:left" class="sin-borde" id="txtdenrespri" value="<?php print $ls_denrespri ?>" size="45" readonly></td>
              </tr>
              <tr>
                <td height="20"><div align="right">Responsable de Uso </div></td>
                <td height="20" colspan="2"><select name="cmbtipresuso" id="cmbtipresuso" onChange="javascript: ue_catalogo_responsable_uso();">
                    <option value="-" selected>-- Seleccione Uno --</option>
                    <option value="P" <?php if($ls_tipresuso=="P"){ print "selected";} ?>>PERSONAL</option>
                    <option value="B" <?php if($ls_tipresuso=="B"){ print "selected";} ?>>BENEFICIARIO</option>
                  </select>
                    <input name="txtcodresuso" type="text" style="text-align:center" class="sin-borde" id="txtcodresuso" value="<?php print $ls_codresuso; ?>" size="15" maxlength="10" readonly>
                    <input name="txtdenresuso" type="text" style="text-align:left" class="sin-borde" id="txtdenresuso" value="<?php print $ls_denresuso; ?>" size="45" readonly></td>
              </tr>
              <tr>
                <td height="26"><div align="right">Ubicacion Organizacional </div></td>
                <td height="26" colspan="2"><label>
                  <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm; ?>" size="10" maxlength="15" readonly>
                  <a href="javascript: ue_catalogo_unidad_administrativa();"><img src="../shared/imagebank/tools15/buscar.gif" alt=" " width="15" height="15" border="0"></a>
                  <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="45" readonly>
                </label></td>
              </tr>
              <tr>
                <td height="26"><div align="right">Fecha  de la  Entrega </div></td>
                <td height="26" colspan="2"><div align="left">
                    <label></label>
                    <label>
                    <input name="txtfecent" type="text" id="txtfecent" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ls_fecent; ?>" size="13" maxlength="10" datepicker="true">
                    </label>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right">Observaciones</div></td>
                <td rowspan="2"><textarea name="txtdescmp" cols="60" rows="3" id="txtdescmp"  onKeyUp="javascript: ue_validarcomillas(this)" onBlur="javascript: ue_validarcomillas(this)"><?php print $ls_descmp ?></textarea></td>
                <td style="visibility:hidden"><input name="btnprocesar" type="button" class="boton" value="  Procesar" <?php if($ls_estpromov==0){ print "onClick='javascript: ue_procesar();'";}?>>
                <input name="hidestpromov" type="hidden" id="hidestpromov" value="<?php print $ls_estpromov ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right"></div></td>
                <td style="visibility:hidden"><input name="btnreversar" type="button" class="boton" value="  Reversar"  <?php if($ls_estpromov==1){ print "onClick='javascript: ue_reversar();'";}?> ></td>
              </tr>
              
              <tr class="formato-blanco">
                <td height="28" colspan="3">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right"></div></td>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
            <input name="operacion" type="hidden" id="operacion">
            <input name="filadelete" type="hidden" id="filadelete">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
        </div></td>
      </tr>
    </table>
  </form>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_saf_cat_incorporaciones.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_catalogo_responsable_primario()
{
	f=document.form1;
	tipresuso=f.cmbtiprespri.value;
	if(tipresuso=='P')
	{
		window.open("sigesp_saf_cat_personal.php?destino=repasignadospri","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else if(tipresuso=='B')
	{
		window.open("sigesp_saf_cat_beneficiario.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catalogo_responsable_uso()
{
	f=document.form1;
	tipresuso=f.cmbtipresuso.value;
	if(tipresuso=='P')
	{
		window.open("sigesp_saf_cat_personal.php?destino=repasignadosuso","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else if(tipresuso=='B')
	{
		window.open("sigesp_saf_cat_beneficiario.php?destino=responsableuso","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catalogo_unidad_administrativa()
{
	f=document.form1;
	window.open("sigesp_saf_cat_unidadejecutora.php?destino=activo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    //f.txtubigeo.disabled=true;	
}

function ue_agregardetalle()
{
	f=document.form1;
	ls_cmpmov=f.txtcmpmov.value;
	if(ls_cmpmov=="")
	{
		alert("Debe existir un numero de comprobante");
	}
	else
	{
		li_totrow=f.totalfilas.value;
		window.open("sigesp_saf_pdt_loteactivo.php?totrow="+ li_totrow +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=850,height=350,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catacausas()
{
	tipo="I";
	window.open("sigesp_saf_cat_causasmovimiento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_guardar()
{
	f=document.form1;
	ls_status=f.hidstatus.value;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if(((ls_status=="C")&&(li_cambiar==1))||(ls_status=="")&&(li_incluir==1))
	{
		if(ls_status!="C")
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_p_incorporacioneslotegeneral.php";
			f.submit();
		}
		else
		{alert("Este documento no debe ser modificado");}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		f.operacion.value="PROCESAR";
		f.action="sigesp_saf_p_incorporacioneslotegeneral.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_reversar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		f.operacion.value="REVERSAR";
		f.action="sigesp_saf_p_incorporacioneslotegeneral.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_fila=f.totalfilas.value;
	if(li_fila!=li_row)
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	
			f.filadelete.value=li_row;
			f.operacion.value="ELIMINARDETALLE"
			f.action="sigesp_saf_p_incorporacioneslotegeneral.php";
			f.submit();
		}
	}
}


function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(confirm("¿Seguro desea eliminar el Registro?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_saf_p_incorporacioneslotegeneral.php";
			f.submit();
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_imprimir()
{
	f=document.form1;
	ls_status=f.hidstatus.value;
	ls_cmpmov=f.txtcmpmov.value;
	li_imprimir=f.imprimir.value;
	if(ls_status=="C")
	{
		if (li_imprimir==1)
		{
			window.open("reportes/sigesp_saf_rfs_incorporacion.php?cmpmov="+ls_cmpmov+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("Seleccione un documento a imprimir");
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>