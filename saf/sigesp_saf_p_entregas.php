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
$io_fun_activo=new class_funciones_activos("../");
$ls_reporte=$io_fun_activo->uf_select_config("SAF","REPORTE","COMPROBANTE_ENT","sigesp_saf_rpp_comentrega.php","C");
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_entregas.php",$ls_permisos,$la_seguridad,$la_permisos);
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
		//////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		/////////////////////////////////////////////////////////////////////////////////
   		global $ls_cmpent,$ls_descmp,$ld_feccmp, $ld_fecent, $ls_codcau,$ls_dencau;
   		global $ls_estproent,$ls_status,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows,$ls_codrespri;
		global $ls_codres, $ls_denres, $ls_codrec, $ls_denrec, $ls_coddes, $ls_dendes,$ls_tipdes,$ls_tipres,$ls_tiprec, $ls_coduniadm, $ls_denuniadm, $ls_codunisol, $ls_denunisol;
		
		$ls_cmpent="";
		$ls_descmp="";
		$ls_estproent="";
		$ld_feccmp= date("d/m/Y");
		$ld_fecent= date("d/m/Y");
		$ls_status="";		
		$ls_titletable="Activos";
		$li_widthtable=750;
		$ls_nametable="grid";
		$lo_title[1]  ="Codigo";
		$lo_title[2]  ="Serial";
		$lo_title[3]  ="Denominacion";
		$lo_title[4]  ="Monto";
		$lo_title[5]  ="";
		$li_totrows   =1;
		$ls_codres    ="";	
		$ls_denres    ="";
		$ls_codrec    ="";
		$ls_denrec    ="";
		$ls_coddes    ="";
		$ls_dendes    ="";
		$ls_denuniadm ="";
		$ls_denunisol ="";
		$ls_coduniadm =uf_obtenervalor("cmbuniadm","");
		$ls_codunisol =uf_obtenervalor("cmbunisol","");
		$ls_tipres    =uf_obtenervalor("cmbtipres","-");	
		$ls_tipdes    =uf_obtenervalor("cmbtipdes","-");
		$ls_tiprec    =uf_obtenervalor("cmbtiprec","-");
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
		$aa_object[$ai_totrows][1]="<input name=txtcodact".$ai_totrows." type=text    id=txtcodact".$ai_totrows." style=text-align:center class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  style=text-align:center class=sin-borde size=15 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." style=text-align:left class=sin-borde size=25 maxlength=150 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." style=text-align:right class=sin-borde size=15 readonly>";
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
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 06/06/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			$ls_codact= $_POST["txtcodact".$li_i];
			$ls_denact= $_POST["txtdenact".$li_i];
			$ls_idact=  $_POST["txtidact".$li_i];
			$li_monact= $_POST["txtmonact".$li_i];
            $lo_object[$li_i][1]="<input name=txtcodact".$li_i." type=text id=txtcodact".$li_i."   style=text-align:center class=sin-borde size=15 maxlength=15 value='".$ls_codact."' readonly>";
			$lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text   id=txtidact".$li_i."  style=text-align:center class=sin-borde  size=15 maxlength=15 value='".$ls_idact."'  readonly>";
			$lo_object[$li_i][3]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." style=text-align:left   class=sin-borde  size=25 maxlength=150 value='".$ls_denact."' readonly>";
			$lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text   id=txtmonact".$li_i." style=text-align:right  class=sin-borde style=text-align:right size=15 value='".$li_monact."' readonly>";
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
<title >Entrega de Activos</title>
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
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24">&nbsp;</td>
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
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("sigesp_saf_c_entrega.php");
	$io_saf= new sigesp_saf_c_entrega();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("sigesp_saf_c_activo.php");
	$io_saf_dta= new sigesp_saf_c_activo();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_totrows = uf_obtenervalor("totalfilas",1);	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,$li_totrows);
		$ls_readonly="readonly";
	}

	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_readonly="";
			$ls_cmpent = $io_keygen->uf_generar_numero_nuevo("SAF","saf_entrega","cmpent","SAF",15,"","codemp",$ls_codemp);
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
		case "AGREGARDETALLE":
			uf_limpiarvariables();
			$li_totrows   = uf_obtenervalor("totalfilas",1);
			$li_totrows   = $li_totrows+1;
			$ls_cmpent    = $_POST["txtcmpent"];
			$ld_feccmp    = $_POST["txtfeccmp"];
			$ld_fecent    = $_POST["txtfecent"];
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codunisol = $_POST["txtcodunisol"];
			$ls_denunisol = $_POST["txtdenunisol"];
			$ls_descmp    = $_POST["txtdescmp"];
			$ls_status    = $_POST["hidstatus"];
			$ls_codres    = $_POST["txtcodres"];;	
		    $ls_denres    = $_POST["txtnomres"];
		    $ls_codrec    = $_POST["txtcodrec"];
		    $ls_denrec    = $_POST["txtnomrec"];
		    $ls_coddes    = $_POST["txtcoddes"];
		    $ls_dendes    =  $_POST["txtnomdes"];
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codact= $_POST["txtcodact".$li_i];
				$ls_denact= $_POST["txtdenact".$li_i];
				$ls_idact=  $_POST["txtidact".$li_i];
				$li_monact= $_POST["txtmonact".$li_i];
				$lo_object[$li_i][1]="<input name=txtcodact".$li_i." type=text id=txtcodact".$li_i."   style=text-align:center class=sin-borde  size=15 maxlength=15 value='".$ls_codact."' readonly>";
				$lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text id=txtidact".$li_i."    style=text-align:center class=sin-borde  size=15 maxlength=15 value='". $ls_idact ."' readonly>";
				$lo_object[$li_i][3]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." style=text-align:left   class=sin-borde  size=25 maxlength=150 value='".$ls_denact."' readonly>";				 
				$lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text id=txtmonact".$li_i."   style=text-align:right  class=sin-borde  size=15 value='". $li_monact ."' readonly>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

			}	
			uf_agregarlineablanca($lo_object,$li_totrows);

		break;
		case "GUARDAR":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_codusureg = $_SESSION["la_logusr"];
			$ls_cmpent    = $_POST["txtcmpent"];
			$ld_feccmp    = $_POST["txtfeccmp"];
			$ld_fecent    = $_POST["txtfecent"];
			$ls_descmp    = $_POST["txtdescmp"];
			$ls_status    = $_POST["hidstatus"];
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codunisol = $_POST["txtcodunisol"];
			$ls_denunisol = $_POST["txtdenunisol"];
			$ls_codres    = $_POST["txtcodres"];	
		    $ls_codrec    = $_POST["txtcodrec"];
		    $ls_dendes    = $_POST["txtnomdes"];
			$ls_denres    = $_POST["txtnomres"];	
		    $ls_denrec    = $_POST["txtnomrec"];
		    $ls_coddes    = $_POST["txtcoddes"];
			$ls_tipres    = $_POST["cmbtipres"];	
			$ls_tiprec    = $_POST["cmbtiprec"];
			$ls_tipdes    = $_POST["cmbtipdes"];
			$ld_date      = date("Y-m-d");
			$lb_valido = $io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				if(($ls_cmpent!="")&&($li_totrows>1))
				{
					$ls_estproent=0;
					$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
					$ld_fecentbd=$io_fun->uf_convertirdatetobd($ld_fecent);

					$lb_existe=$io_saf->uf_saf_select_entrega($ls_codemp,$ls_cmpent,$ls_coduniadm,$ld_feccmpbd);
					if($lb_existe)
					{
						$li_totrows=1;
						uf_limpiarvariables();
						uf_agregarlineablanca($lo_object,1);
						$io_msg->message("El numero de comprobante ".$ls_cmpent."ya existe");
						$lb_valido=false;
					}
					else
					{
						$io_sql->begin_transaction();
									
						$lb_valido=$io_saf->uf_saf_insert_entrega($ls_codemp,$ls_cmpent,$ld_feccmpbd,$ld_fecentbd,$ls_coduniadm,$ls_codunisol,
						                                          $ls_codres,$ls_codrec,$ls_coddes,$ls_tipres,$ls_tiprec,$ls_tipdes,$ls_descmp,$ls_estproent,
																  $la_seguridad);
						if($lb_valido)
						{
							for($li_i=1;$li_i<$li_totrows;$li_i++)
							{
								$ls_codact= $_POST["txtcodact".$li_i];
								$ls_denact= $_POST["txtdenact".$li_i];
								$ls_idact=  $_POST["txtidact".$li_i];
								$li_monact= $_POST["txtmonact".$li_i];
								$li_monact= str_replace(".","",$li_monact);
								$li_monact= str_replace(",",".",$li_monact);
								
								$lb_valido=$io_saf->uf_saf_insert_dt_entrega($ls_codemp,$ls_cmpent,$ls_coduniadm,$ld_feccmpbd,$ls_codact,$ls_idact,$la_seguridad);
								$lo_object[$li_i][1]="<input name=txtcodact".$li_i." type=text   id=txtcodact".$li_i." style=text-align:center class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
								$lo_object[$li_i][2]="<input name=txtidact".$li_i."  type=text   id=txtidact".$li_i."  style=text-align:center class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
								$lo_object[$li_i][3]="<input name=txtdenact".$li_i." type=text   id=txtdenact".$li_i." style=text-align:left class=sin-borde size=25 maxlength=150 value='".$ls_denact."' readonly>".
								$lo_object[$li_i][4]="<input name=txtmonact".$li_i." type=text   id=txtmonact".$li_i." style=text-align:right class=sin-borde size=15 value='". $li_monact ."' readonly>";
								$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
								
							}				
						}
						if($lb_valido)
						{
							$io_sql->commit();
							$io_msg->message("El registro fue incluido con exito");
							$ls_estproent=0;
							uf_pintardetalle($lo_object,$li_totrows);
							uf_agregarlineablanca($lo_object,$li_totrows);
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No se pudo incluir el registro");
							uf_pintardetalle($lo_object,$li_totrows);
						}
					}
				}
				else
				{
					if($li_totrows<=1)
					{
						$io_msg->message("El registro debe tener al menos 1 detalle");
						uf_agregarlineablanca($lo_object,1);
					}
					else
					{
						$io_msg->message("Debe completar los datos");
						uf_pintardetalle($lo_object,$li_totrows);
					}
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

		case "PROCESAR":
			$ls_cmpent    = $_POST["txtcmpent"];
			$ld_feccmp    = $_POST["txtfeccmp"];
			$ld_fecent    = $_POST["txtfecent"];
			$ls_descmp    = $_POST["txtdescmp"];
			$ls_status    = $_POST["hidstatus"];
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codunisol = $_POST["txtcodunisol"];
			$ls_denunisol = $_POST["txtdenunisol"];
			$ls_codres    = $_POST["txtcodres"];	
		    $ls_codrec    = $_POST["txtcodrec"];
		    $ls_dendes    = $_POST["txtnomdes"];
			$ls_denres    = $_POST["txtnomres"];	
		    $ls_denrec    = $_POST["txtnomrec"];
		    $ls_coddes    = $_POST["txtcoddes"];
			$ls_tipres    = $_POST["cmbtipres"];	
			$ls_tiprec    = $_POST["cmbtiprec"];
			$ls_tipdes    = $_POST["cmbtipdes"];
			$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
			$ls_status=$_POST["hidstatus"];
			$ls_estproent=1;
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_procesarentrega($ls_codemp,$ls_cmpent,$ld_feccmpbd,$ls_coduniadm,$ls_estproent,$la_seguridad);
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
			$ls_cmpent    = $_POST["txtcmpent"];
			$ld_feccmp    = $_POST["txtfeccmp"];
			$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
			$ld_fecent    = $_POST["txtfecent"];
			$ls_descmp    = $_POST["txtdescmp"];
			$ls_status    = $_POST["hidstatus"];
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codunisol = $_POST["txtcodunisol"];
			$ls_denunisol = $_POST["txtdenunisol"];
			$ls_codres    = $_POST["txtcodres"];	
		    $ls_codrec    = $_POST["txtcodrec"];
		    $ls_dendes    = $_POST["txtnomdes"];
			$ls_denres    = $_POST["txtnomres"];	
		    $ls_denrec    = $_POST["txtnomrec"];
		    $ls_coddes    = $_POST["txtcoddes"];
			$ls_tipres    = $_POST["cmbtipres"];	
			$ls_tiprec    = $_POST["cmbtiprec"];
			$ls_tipdes    = $_POST["cmbtipdes"];
			$ls_status=$_POST["hidstatus"];
			$ls_estproent=0;
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_procesarentrega($ls_codemp,$ls_cmpent,$ld_feccmpbd,$ls_estproent,$ls_coduniadm,$la_seguridad);
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
			$ls_cmpent    = $_POST["txtcmpent"];
			$ld_feccmp    = $_POST["txtfeccmp"];
			$ld_fecent    = $_POST["txtfecent"];
			$ls_descmp    = $_POST["txtdescmp"];
			$ls_status    = $_POST["hidstatus"];
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codunisol = $_POST["txtcodunisol"];
			$ls_denunisol = $_POST["txtdenunisol"];
			$ls_codres    = $_POST["txtcodres"];	
		    $ls_codrec    = $_POST["txtcodrec"];
		    $ls_dendes    = $_POST["txtnomdes"];
			$ls_denres    = $_POST["txtnomres"];	
		    $ls_denrec    = $_POST["txtnomrec"];
		    $ls_coddes    = $_POST["txtcoddes"];
			$ls_tipres    = $_POST["cmbtipres"];	
			$ls_tiprec    = $_POST["cmbtiprec"];
			$ls_tipdes    = $_POST["cmbtipdes"];
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
					$li_monact= $_POST["txtmonact".$li_i];
					
					$lo_object[$li_temp][1]="<input name=txtcodact".$li_temp." type=text   id=txtcodact".$li_temp." style=text-align:center class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtidact".$li_temp."  type=text   id=txtidact".$li_temp."  style=text-align:center class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtdenact".$li_temp." type=text   id=txtdenact".$li_temp." style=text-align:left class=sin-borde size=25 maxlength=150 value='".$ls_denact."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtmonact".$li_temp." type=text   id=txtmonact".$li_temp." style=text-align:right class=sin-borde size=15 value='". $li_monact ."' readonly>";
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
			$ls_cmpent = $_POST["txtcmpent"];
			$ld_feccmp = $_POST["txtfeccmp"];
			$ld_fecent = $_POST["txtfecent"];
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codunisol = $_POST["txtcodunisol"];
			$ls_denunisol = $_POST["txtdenunisol"];
			$ls_descmp = $_POST["txtdescmp"];
			$ls_estproent = $_POST["hidestproent"];
			$ls_status = $_POST["hidstatus"];
			$ls_codres = $_POST["txtcodres"];;	
		    $ls_denres = $_POST["txtnomres"];
		    $ls_codrec = $_POST["txtcodrec"];
		    $ls_denrec = $_POST["txtnomrec"];
		    $ls_coddes = $_POST["txtcoddes"];
		    $ls_dendes=  $_POST["txtnomdes"];
			$ld_feccmpbd=$io_fun->uf_convertirdatetobd($ld_feccmp);
			$lb_valido=$io_saf->uf_saf_load_dt_entrega($ls_codemp,$ls_cmpent,$ld_feccmpbd,$ls_coduniadm,$li_totrows,$lo_object);
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
    <table width="783" height="159" border="0" class="formato-blanco">
      <tr>
        <td width="775" ><div align="left">
            <table width="735" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="3" class="titulo-ventana">Entrega de Activos </td>
              </tr>
              <tr class="formato-blanco">
                <td width="110" height="19">&nbsp;</td>
                <td width="480"><div align="right">Fecha de Comprobante: </div></td>
                <td width="143"><input name="txtfeccmp" type="text" id="txtfeccmp" style="text-align:center " value="<?php print $ld_feccmp ?>" size="13" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Comprobante</div></td>
                <td height="20" colspan="2">
                  <input name="txtcmpent" type="text" id="txtcmpent" value="<?php print $ls_cmpent ?>" size="20" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center " readonly>
                  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Fecha de Entrega </div></td>
                <td height="26" colspan="2"><input name="txtfecent" type="text" id="txtfecent" style="text-align:center " value="<?php print $ld_fecent ?>" size="13" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Unidad Administrativa </div></td>
				<td height="26" colspan="2"><label>
                  <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm; ?>" size="20" maxlength="15" readonly style="text-align:center">
                  <a href="javascript: ue_catalogo_uniadm();"><img src="../shared/imagebank/tools15/buscar.gif" alt=" " width="15" height="15" border="0"></a>
                  <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="90" readonly>
                </label></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Unidad Solicitante </div></td>
                <td height="20" colspan="2"><input name="txtcodunisol" type="text" id="txtcodunisol" value="<?php print $ls_codunisol; ?>" size="20" maxlength="15" readonly style="text-align:center">
                <a href="javascript: ue_catalogo_unisol();"><img src="../shared/imagebank/tools15/buscar.gif" alt=" " width="15" height="15" border="0"></a>
                <input name="txtdenunisol" type="text" class="sin-borde" id="txtdenunisol" value="<?php print $ls_denunisol; ?>" size="90" readonly>                </td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Responsable </div></td>
                <td height="20" colspan="2"><select name="cmbtipres" id="cmbtipres" onChange="javascript: ue_catalogo_responsable();">
                  <option value="-">---seleccione---</option>
                  <option value="P" <?php if($ls_tipres=="P"){ print "selected";} ?>>PERSONAL</option>
                  <option value="B" <?php if($ls_tipres=="B"){ print "selected";} ?>>BENEFICIARIO</option>
                                </select>
                <input name="txtcodres" type="text"  style="text-align:center" class="sin-borde" id="txtcodres" value="<?php print $ls_codres; ?>" size="15" maxlength="10" readonly>
                <input name="txtnomres" type="text"  style="text-align:left" class="sin-borde" id="txtnomres" value="<?php print $ls_denres ?>" size="45" readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Despachador </div></td>
                <td height="20" colspan="2"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_catalogo_despachador();">
                    <option value="-">---seleccione---</option>
                    <option value="P" <?php if($ls_tipdes=="P"){ print "selected";} ?>>PERSONAL</option>
                    <option value="B" <?php if($ls_tipdes=="B"){ print "selected";} ?>>BENEFICIARIO</option>
                  </select>
                  <input name="txtcoddes" type="text" style="text-align:center" class="sin-borde" id="txtcoddes" value="<?php print $ls_coddes; ?>" size="15" maxlength="10" readonly>
                  <input name="txtnomdes" type="text" style="text-align:left" class="sin-borde" id="txtnomdes" value="<?php print $ls_dendes; ?>" size="45" readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20"><div align="right">Receptor </div></td>
                <td height="20" colspan="2"><select name="cmbtiprec" id="cmbtiprec" onChange="javascript: ue_catalogo_receptor();">
                    <option value="-">---seleccione---</option>
                    <option value="P" <?php if($ls_tiprec=="P"){ print "selected";} ?>>PERSONAL</option>
                    <option value="B" <?php if($ls_tiprec=="B"){ print "selected";} ?>>BENEFICIARIO</option>
                  </select>
                <input name="txtcodrec" type="text" style="text-align:center" class="sin-borde" id="txtcodrec" value="<?php print $ls_codrec; ?>" size="15" maxlength="10" readonly>
                <input name="txtnomrec" type="text" style="text-align:left" class="sin-borde" id="txtnomrec" value="<?php print $ls_denrec; ?>" size="45" readonly></td>
              </tr>
              
              <tr class="formato-blanco">
                <td height="28"><div align="right">Observaciones</div></td>
                <td rowspan="2"><textarea name="txtdescmp" cols="60" rows="3" id="txtdescmp"  onKeyUp="javascript: ue_validarcomillas(this)" onBlur="javascript: ue_validarcomillas(this)"><?php print $ls_descmp ?></textarea></td>
                <td><input name="btnprocesar" type="button" class="boton" value="  Procesar" <?php if($ls_estproent==0){ print "onClick='javascript: ue_procesar();'";}?>>
                <input name="hidestproent" type="hidden" id="hidestproent" value="<?php print $ls_estproent ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="23"><div align="right"></div></td>
                <td><input name="btnreversar" type="button" class="boton" value="  Reversar"  <?php if($ls_estproent==1){ print "onClick='javascript: ue_reversar();'";}?> ></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28" colspan="3"><a href="javascript: ue_agregardetalle();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" class="sin-borde">Agregar Activos</a></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28" colspan="3"><div align="center">
                    <?php
		             $in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	                 ?>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
            <input name="operacion"  type="hidden" id="operacion">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            <input name="filadelete" type="hidden" id="filadelete">
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
		window.open("sigesp_saf_cat_entregas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}


function ue_catalogo_uniadm()
{
	 window.open("sigesp_saf_cat_unidadejecutora.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");	
}

function ue_catalogo_unisol()
{
	 window.open("sigesp_saf_cat_unidadejecutora.php?destino=solicitante","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");	
}

function ue_catalogo_responsable()
{
	f=document.form1;
	tipres=f.cmbtipres.value;
	if(tipres=='P')
	{
		window.open("sigesp_saf_cat_personal.php?destino=responsable","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else if(tipres=='B')
	{
		window.open("sigesp_saf_cat_beneficiario.php?destino=responsable","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catalogo_despachador()
{
	f=document.form1;
	tipdes=f.cmbtipdes.value;
	if(tipdes=='P')
	{
		window.open("sigesp_saf_cat_personal.php?destino=despachador","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else if(tipdes=='B')
	{
		window.open("sigesp_saf_cat_beneficiario.php?destino=despachador","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catalogo_receptor()
{
	f=document.form1;
	tiprec=f.cmbtiprec.value;
	if(tiprec=='P')
	{
		window.open("sigesp_saf_cat_personal.php?destino=receptor","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else if(tiprec=='B')
	{
		window.open("sigesp_saf_cat_beneficiario.php?destino=receptor","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}


function ue_agregardetalle()
{
	f=document.form1;
	ls_cmpent=f.txtcmpent.value;
	if(ls_cmpent=="")
	{
		alert("Debe existir un numero de comprobante");
	}
	else
	{
		li_totrow=f.totalfilas.value;
		window.open("sigesp_saf_cat_actent.php?totrow="+ li_totrow +"&origen=entrega","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=620,height=260,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_p_entregas.php";
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
	ls_status=f.hidstatus.value;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if(((ls_status=="C")&&(li_cambiar==1))||(ls_status=="")&&(li_incluir==1))
	{
		if(ls_status!="C")
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_p_entregas.php";
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
		f.action="sigesp_saf_p_entregas.php";
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
		f.action="sigesp_saf_p_entregas.php";
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
			f.action="sigesp_saf_p_entregas.php";
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
			f=document.form1;
			f.operacion.value="ELIMINAR";
			f.action="sigesp_saf_p_entregas.php";
			f.submit();
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_imprimir(ls_reporte)
{
	f            = document.form1;
	ls_status    = f.hidstatus.value;
	ls_cmpent    = f.txtcmpent.value;
	ld_feccmp    = f.txtfeccmp.value;
	ls_coduniadm = f.txtcoduniadm.value;
	li_imprimir  = f.imprimir.value;
    if (li_imprimir==1)
	{
	 window.open("reportes/"+ls_reporte+"?cmpent="+ls_cmpent+"&feccmp="+ld_feccmp+"&coduniadm="+ls_coduniadm,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	}
	else
	{
	 alert("No tiene permiso para realizar esta operacion");
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
function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto != "'")&&(texto != '"'))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>