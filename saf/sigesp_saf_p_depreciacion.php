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
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_depreciacion.php",$ls_permisos,$la_seguridad,$la_permisos);
$lb_cierrescg = $io_fun_activo->uf_chkciescg();
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
   		global $ls_codact,$ls_ideact,$ls_denact,$li_cosact,$ls_seract,$li_viduti,$li_cossal,$li_mondep,$li_depanu;
		global $li_depmen,$ld_feccmpact,$ld_fecincact,$ld_fecdepact;
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		
		$ls_codact="";
		$ls_ideact="";
		$ls_denact="";
		$li_cosact="";
		$ls_seract="";
		$li_viduti="";
		$li_cossal="";
		$li_mondep="";
		$li_depanu="";
		$li_depmen="";
		$ld_fecdepact= date("d/m/Y");
		$ld_feccmpact="";
		$ld_fecincact="";
		
		$ls_titletable="Detalle de la Depreciación";
		$li_widthtable=640;
		$ls_nametable="grid";
		$lo_title[1]="Fecha Depreciación";
		$lo_title[2]="Meses";
		$lo_title[3]="Dias";
		$lo_title[4]="Depreciacion Anual";
		$lo_title[5]="Mensual";
		$lo_title[6]="Acumulada";
		$lo_title[7]="Valor Contable";
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
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 22/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtfecdep".$ai_totrows." type=text id=txtfecdep".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtcanmes".$ai_totrows." type=text id=txtcanmes".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtcandia".$ai_totrows." type=text id=txtcandia".$ai_totrows." class=sin-borde size=15 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtdepanu".$ai_totrows." type=text id=txtdepanu".$ai_totrows." class=sin-borde size=20 readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtdepmen".$ai_totrows." type=text id=txtdepmen".$ai_totrows." class=sin-borde size=20 readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtdepacu".$ai_totrows." type=text id=txtdepacu".$ai_totrows." class=sin-borde size=20 readonly>";
		$aa_object[$ai_totrows][7]="<input name=txtvalcon".$ai_totrows." type=text id=txtvalcon".$ai_totrows." class=sin-borde size=20 readonly>";

   }

   function uf_pintargrid($ad_fecdep,$ai_mesd,$ai_diasd,$ai_depacum,$ai_depmes,$ai_depanu,$ai_valcont,&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $ad_fecdep  // fecha de la depreciacion
		//				   $ai_mesd    // meses de depreciacion
		//				   $ai_diasd   // dias de depreciacion
		//				   $ai_depacum // depreciacion acumulada
		//				   $ai_depmes // depreciacion mensual
		//				   $ai_depanu // depreciacion anual
		//				   $ai_valcont // valor contable de la depreciacion
		//				   $aa_object  // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de pintar todos los datos de la depreciacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/05/2006 								Fecha Última Modificación : 24/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_depanuaux=number_format($ai_depanu,2,',','.');
		$li_depmenaux=number_format($ai_depmes,2,',','.');
		$li_depacumaux=number_format($ai_depacum,2,',','.');
		$li_valcontaux=number_format($ai_valcont,2,',','.');
		$aa_object[$ai_totrows][1]="<input name=txtfecdep".$ai_totrows." type=text id=txtfecdep".$ai_totrows." value='".$ad_fecdep."'     class=sin-borde size=15 readonly style=text-align:center>";
		$aa_object[$ai_totrows][2]="<input name=txtcanmes".$ai_totrows." type=text id=txtcanmes".$ai_totrows." value='".$ai_mesd."'       class=sin-borde size=5  readonly style=text-align:left>";
		$aa_object[$ai_totrows][3]="<input name=txtcandia".$ai_totrows." type=text id=txtcandia".$ai_totrows." value='".$ai_diasd."'      class=sin-borde size=5  readonly style=text-align:left>";
		$aa_object[$ai_totrows][4]="<input name=txtdepanu".$ai_totrows." type=text id=txtdepanu".$ai_totrows." value='".$li_depanuaux."'  class=sin-borde size=20 readonly style=text-align:right>";
		$aa_object[$ai_totrows][5]="<input name=txtdepmen".$ai_totrows." type=text id=txtdepmen".$ai_totrows." value='".$li_depmenaux."'  class=sin-borde size=20 readonly style=text-align:right>";
		$aa_object[$ai_totrows][6]="<input name=txtdepacu".$ai_totrows." type=text id=txtdepacu".$ai_totrows." value='".$li_depacumaux."' class=sin-borde size=20 readonly style=text-align:right>";
		$aa_object[$ai_totrows][7]="<input name=txtvalcon".$ai_totrows." type=text id=txtvalcon".$ai_totrows." value='".$li_valcontaux."' class=sin-borde size=20 readonly style=text-align:right>";

   }

   function uf_pintardetalle(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $ad_fecdep  // fecha de la depreciacion
		//				   $ai_mesd    // meses de depreciacion
		//				   $ai_diasd   // dias de depreciacion
		//				   $ai_depacum // depreciacion acumulada
		//				   $ai_depmes // depreciacion mensual
		//				   $ai_depanu // depreciacion anual
		//				   $ai_valcont // valor contable de la depreciacion
		//				   $aa_object  // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de pintar todos los datos de la depreciacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/05/2006 								Fecha Última Modificación : 24/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for($li_i=1;$li_i<=$ai_totrows;$li_i++)
		{
			$ld_fecdep= $_POST["txtfecdep".$li_i];
			$li_canmes= $_POST["txtcanmes".$li_i];
			$li_candia= $_POST["txtcandia".$li_i];
			$li_depmen= $_POST["txtdepmen".$li_i];
			$li_depanu= $_POST["txtdepanu".$li_i];
			$li_depacu= $_POST["txtdepacu".$li_i];
			$li_valcon= $_POST["txtvalcon".$li_i];
			
			$aa_object[$li_i][1]="<input name=txtfecdep".$li_i." type=text id=txtfecdep".$li_i." value='".$ld_fecdep."' class=sin-borde size=15 readonly style=text-align:center>";
			$aa_object[$li_i][2]="<input name=txtcanmes".$li_i." type=text id=txtcanmes".$li_i." value='".$li_canmes."' class=sin-borde size=5  readonly style=text-align:left>";
			$aa_object[$li_i][3]="<input name=txtcandia".$li_i." type=text id=txtcandia".$li_i." value='".$li_candia."' class=sin-borde size=5  readonly style=text-align:left>";
			$aa_object[$li_i][4]="<input name=txtdepanu".$li_i." type=text id=txtdepanu".$li_i." value='".$li_depanu."' class=sin-borde size=20 readonly style=text-align:right>";
			$aa_object[$li_i][5]="<input name=txtdepmen".$li_i." type=text id=txtdepmen".$li_i." value='".$li_depmen."' class=sin-borde size=20 readonly style=text-align:right>";
			$aa_object[$li_i][6]="<input name=txtdepacu".$li_i." type=text id=txtdepacu".$li_i." value='".$li_depacu."' class=sin-borde size=20 readonly style=text-align:right>";
			$aa_object[$li_i][7]="<input name=txtvalcon".$li_i." type=text id=txtvalcon".$li_i." value='".$li_valcon."' class=sin-borde size=20 readonly style=text-align:right>";
		}

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
<title >C&aacute;lculo de Depreciaci&oacute;n del Activo </title>
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
    <td height="30" colspan="12" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
<!--    <td height="20" colspan="12" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="12" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" title="Nuevo" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" title="Guardar" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" width="20" height="20" title="Salir" border="0"></a></div></td>
    <td class="toolbar" width="22"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
    <td class="toolbar" width="696">&nbsp;</td>
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
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("sigesp_saf_c_depreciacion.php");
	$io_saf= new sigesp_saf_c_depreciacion();
	require_once("class_funciones_activos.php");
	$io_fac= new class_funciones_activos();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
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
	if(array_key_exists("txtdepreciar",$_POST))
	{
		$ls_operacion=$_POST["txtdepreciar"];
	}
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();		
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		case "GUARDAR":
			uf_limpiarvariables();
			$ls_codact = uf_obtenervalor("txtcodact","");
			$ls_ideact = uf_obtenervalor("txtideact","");
			$ls_denact = uf_obtenervalor("txtdenact","");
			$ls_seract = uf_obtenervalor("txtseract","");
			$ls_codmet = uf_obtenervalor("cmbmetodos","");
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_cosact=uf_obtenervalor("txtcosact","");
			$li_cossal=uf_obtenervalor("txtcossal","");
			$li_viduti=uf_obtenervalor("txtviduti","");
			$li_mondep=uf_obtenervalor("txtmondep","");
			$li_depmen=uf_obtenervalor("txtdepmen","");
			$li_depanu=uf_obtenervalor("txtdepanu","");
			$ld_fecincact=uf_obtenervalor("txtfecincact","");
			$ld_feccmpact=uf_obtenervalor("txtfeccmpact","");
			$ld_fecmod = uf_obtenervalor("txtfecmod","");
			$li_cosact=$io_fac->uf_convertirformatonumerico($li_cosact);
			$li_cossal=$io_fac->uf_convertirformatonumerico($li_cossal);
			$li_viduti=$io_fac->uf_convertirformatonumerico($li_viduti);
			$li_mondep=$io_fac->uf_convertirformatonumerico($li_mondep);
			$li_depmen=$io_fac->uf_convertirformatonumerico($li_depmen);
			$li_depanu=$io_fac->uf_convertirformatonumerico($li_depanu);
			$io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ld_fecdep=    $_POST["txtfecdep".$li_i];
				$li_mondepmen= $_POST["txtdepmen".$li_i];
				$li_mondepanu= $_POST["txtdepanu".$li_i];
				$li_mondepacu= $_POST["txtdepacu".$li_i];
				$ld_fecdepaux=$io_fun->uf_convertirdatetobd($ld_fecdep);
				$li_mondepmen=$io_fac->uf_convertirformatonumerico($li_mondepmen);
				$li_mondepanu=$io_fac->uf_convertirformatonumerico($li_mondepanu);
				$li_mondepacu=$io_fac->uf_convertirformatonumerico($li_mondepacu);
				if($ld_fecmod=="")
				{
					$lb_existe=$io_saf->uf_saf_select_depreciacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecdepaux);
					if(!$lb_existe)
					{
						$lb_valido=$io_saf->uf_saf_insert_depreciacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecdepaux,$li_mondepmen,$li_mondepanu,$li_mondepacu,$la_seguridad);
					}
					else
					{
						$io_msg->message("Ya existe una depreciación para este activo");
						$lb_valido=false;
					}
					if(!$lb_valido)
					{break;}
				}
				else
				{
					$lb_existe=$io_saf->uf_saf_select_depreciacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecdepaux);
					if($lb_existe)
					{
						$lb_valido=$io_saf->uf_saf_update_depreciacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecdepaux,$li_mondepmen,$li_mondepanu,$li_mondepacu,$la_seguridad);
					}
					else
					{
						$lb_valido=$io_saf->uf_saf_insert_depreciacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecdepaux,$li_mondepmen,$li_mondepanu,$li_mondepacu,$la_seguridad);
					}
				}
			}
			if($lb_valido)
			{
				$io_sql->commit();
				$io_msg->message("La depreciación del activo fue registrada");
			}
			else
			{
				$io_sql->rollback();
				$io_msg->message("No se pudo registrar la depreciación del activo");
			}
			uf_pintardetalle($lo_object,$li_totrows);
			if($ld_fecmod!="")
			{
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,$li_totrows);
				
			}
		break;

		case "BUSCARACTIVO":
			uf_limpiarvariables();
			$ls_codact = uf_obtenervalor("txtcodact","");
			$ls_ideact = uf_obtenervalor("txtideact","");
			$ls_denact = uf_obtenervalor("txtdenact","");
			$ls_seract = uf_obtenervalor("txtseract","");
			$ls_codmet = uf_obtenervalor("cmbmetodos","");
			$li_cosact=0;
			$li_cossal=0;
			$li_vidautil=0;
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ld_date=date("Y-m-d");
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_date);
			if($lb_valido)
			{
				$lb_depreciable=$io_saf->uf_saf_select_statusdepreciacion($ls_codemp,$ls_codact);
				if($lb_depreciable)
				{
					$lb_valido=$io_saf->uf_saf_load_activo($ls_codemp,$ls_codact,$li_cosact,$li_cossal,$li_vidautil,$ld_feccmpact);
					$ld_feccmpact=$io_fun->uf_convertirfecmostrar($ld_feccmpact);
					if(($lb_valido)&&($li_cossal!="")&&($li_vidautil!=""))
					{
						$lb_valido=$io_saf->uf_saf_load_incorporacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecinc);
						if($lb_valido)
						{
							$ld_fecincact=$io_fun->uf_convertirfecmostrar($ld_fecinc);
							$li_viduti=($li_vidautil*12);
							$li_mondep=($li_cosact-$li_cossal);
							$li_depmen=($li_mondep/$li_viduti);
							$li_depanu=round($li_depmen*12);
							$ls_annioinc=substr($ld_fecinc,0,4);
							$ls_mesinc=substr($ld_fecinc,5,2);
							$ls_diainc=substr($ld_fecinc,8,2);
							$ls_mesf=$ls_mesinc;
							$ls_mest=0;
							$ls_dia=0;
							$li_depacum=0;
							$li_depmes=0;
							
							$lb_mes1=true;
							$li_j=0;
							$li_totrows=0;
							if($ld_fecinc=="")
							{
								$io_msg->message("El activo no ha sido incorporado");
								uf_limpiarvariables();
								uf_agregarlineablanca($lo_object,$li_totrows);
							}
							else
							{
								for($ls_anio=$ls_annioinc;$ls_anio<=($ls_annioinc+$li_vidautil);$ls_anio++)
								{
									if($ls_anio==($ls_annioinc+$li_vidautil))
									{
										$ls_mest=$ls_mesf-1;
										if($ls_mest==0)
										{$ls_mest=$ls_mesinc;}
									}
									else
									{$ls_mest=12;}
									for($li_i=$ls_mesf;$li_i<=$ls_mest;$li_i++)
									{
										$li_i=str_pad($li_i,2,"0",0);
										$ld_fecdep=$io_fec->uf_last_day($li_i,$ls_anio);
										if($ls_anio==$ls_annioinc)
										{$ls_dia=(30-$ls_diainc+1);}
										else
										{$ls_dia=0;}
										if($ls_anio==($ls_annioinc+$li_vidautil))
										{
											$ls_dia=$ls_diainc-1;
										}
										$li_j=$li_j+1;
										$ls_mesd=$li_j;
										$ls_diasd=0;
										if($lb_mes1)
										{
											$li_j=$li_j-1;
											$li_depmes=($li_depmen/30)*$ls_dia;
											$ls_diasd=(30-$ls_diainc+1);//GRABAR
											$ls_mesd=0;//GRABAR
/*											if($ls_dia==0)
											{$li_depmes=$li_depmen;}
											else
											{$li_depmes=($li_depmen/30)*$ls_dia;}
											$ls_diasd=(30-$ls_diainc+1);//GRABAR
											$ls_mesd=0;//GRABAR
*/										}
										else
										{$li_depmes=$li_depmen;}
										if(($ls_anio==($ls_annioinc+$li_vidautil))&&($li_i==$ls_mest))//BUSCAR DIAS Y MESES
										{
											$ls_diasd=$ls_diainc;
											$ls_mesd=(12-$ls_mesinc);
											if($ls_mesd==1)
											{$ls_mesd=0;}
											else
											{$ls_mesd=0;}
											$ld_fecdep=$ls_diainc."/".$ls_mesinc."/".($ls_annioinc+$li_vidautil);
											$li_depmes=($li_depmen/30)*$ls_dia;
										}
										$li_depacum=$li_depacum + $li_depmes;
										$li_valcont=($li_cosact-$li_depacum);
										$li_totrows=$li_totrows + 1;
										uf_pintargrid($ld_fecdep,$ls_mesd,$ls_diasd,$li_depacum,$li_depmes,$li_depanu,$li_valcont,$lo_object,$li_totrows);
										$lb_mes1=false;
									}// end for
									$ls_mesf=1;
								}// end for
							}
						}
					}
					else
					{
						$io_msg->message("No se pudo calcular la depreciacion, Faltan datos para calcularla");
						uf_limpiarvariables();
						uf_agregarlineablanca($lo_object,$li_totrows);
					}
				}
				else
				{
					$io_msg->message("El activo que selecciono no es depreciable");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,$li_totrows);
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
		case "DEPRECIAR":
			uf_limpiarvariables();	
			$ls_codact = uf_obtenervalor("txtcodact","");
			$ls_ideact = uf_obtenervalor("txtideact","");
			$ls_denact = uf_obtenervalor("txtdenact","");
			$ls_seract = uf_obtenervalor("txtseract","");
			$ld_fecmod = uf_obtenervalor("txtfeccmp","");
			$li_totmonpar = uf_obtenervalor("totmonpar","");
			$li_totcossal = uf_obtenervalor("totcossal","");
			$li_totviduti = uf_obtenervalor("totviduti","");
			$ls_codmet = "001";
			$li_cosact=0;
			$li_cossal=0;
			$li_vidautil=0;
			$li_totrows= 0;
			$li_j=0;
			$lb_mes1=true;
			$lb_depreciable=$io_saf->uf_saf_select_depreciacion($ls_codemp,$ls_codact,$ls_ideact,"");
			if($lb_depreciable)
			{
				$lb_valido=$io_saf->uf_saf_load_activo($ls_codemp,$ls_codact,$li_cosact,$li_cossal,$li_vidautil,$ld_feccmpact);
				$ld_feccmpact=$io_fun->uf_convertirfecmostrar($ld_feccmpact);
				if(($lb_valido)&&($li_cossal!="")&&($li_vidautil!=""))
				{
					$lb_valido=$io_saf->uf_saf_load_incorporacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecinc);
					if($lb_valido)
					{
						$ld_fecmodaux=$io_fun->uf_convertirdatetobd($ld_fecmod);
						$ld_fecincact=$io_fun->uf_convertirfecmostrar($ld_fecinc);
						$ls_annioinc=substr($ld_fecinc,0,4);
						$ls_mesinc=substr($ld_fecinc,5,2);
						$ls_diainc=substr($ld_fecinc,8,2);
						$ls_anniomod=substr($ld_fecmodaux,0,4);
						$ls_mesmod=substr($ld_fecmodaux,5,2);
						$ls_diamod=substr($ld_fecmodaux,8,2);
						$li_mesdep=0;
						if($ls_annioinc<$ls_anniomod)
						{
								$li_mesdep=($ls_anniomod - $ls_annioinc)*12;
								$li_mesdep=$li_mesdep+($ls_mesmod-$ls_mesinc);
						}
						else
						{
							$li_mesdep=($ls_mesmod-$ls_mesinc);
						}
						$lb_valido=$io_saf->uf_saf_load_depreciacion($ls_codemp,$ls_codact,$ls_ideact,$ld_fecmodaux,$li_mondepacu,$ld_fecdep);
						if($lb_valido)
						{
							$li_viduti=(($li_vidautil*12)-($li_mesdep))+($li_totviduti*12);
							$li_cossal=$li_cossal+$li_totcossal;
							$li_cosact=($li_cosact-$li_mondepacu)+$li_totmonpar;
							$li_mondep=($li_cosact-$li_cossal);
							$li_depmen=($li_mondep/$li_viduti);
							$ls_mesf=$ls_mesmod;
							$ls_mest=0;
							$ls_dia=0;
							$li_depacum=0;
							$li_depmes=0;
							$li_vidautilmod=$li_vidautil+$li_totviduti;
							$li_depanu=round($li_depmen*12);
							
							$lb_mes1=true;
							$li_j=0;
							$li_totrows=0;
							for($ls_anio=$ls_anniomod;$ls_anio<=($ls_anniomod+$li_vidautilmod);$ls_anio++)
							{
								if($ls_anio==($ls_anniomod+$li_vidautilmod))
								{
									$ls_mest=$ls_mesf-1;
									if($ls_mest==0)
									{$ls_mest=$ls_mesmod;}
								}
								else
								{$ls_mest=12;}
								for($li_i=$ls_mesf;$li_i<=$ls_mest;$li_i++)
								{
									$li_i=str_pad($li_i,2,"0",0);
									$ld_fecdep=$io_fec->uf_last_day($li_i,$ls_anio);
									if($ls_anio==$ls_anniomod)
									{$ls_dia=(30-$ls_diamod+1);}
									else
									{$ls_dia=0;}
									if($ls_anio==($ls_anniomod+$li_vidautilmod))
									{
										$ls_dia=$ls_diamod-1;
									}
									$li_j=$li_j+1;
									$ls_mesd=$li_j;
									$ls_diasd=0;
									if($lb_mes1)
									{
										$li_j=$li_j-1;
										if($ls_dia==0)
										{$li_depmes=$li_depmen;}
										else
										{$li_depmes=($li_depmen/30)*$ls_dia;}
										$ls_diasd=(30-$ls_diamod+1);//GRABAR
										$ls_mesd=0;//GRABAR
									}
									else
									{$li_depmes=$li_depmen;}
									if(($ls_anio==($ls_anniomod+$li_vidautilmod))&&($li_i==$ls_mest))//BUSCAR DIAS Y MESES
									{
										$ls_diasd=$ls_diamod;
										$ls_mesd=(12-$ls_mesmod);
										if($ls_mesd==1)
										{$ls_mesd=0;}
										else
										{$ls_mesd=0;}
										$ld_fecdep=$ls_diamod."/".$ls_mesmod."/".($ls_anniomod+$li_vidautilmod);
										$li_depmes=($li_depmen/30)*$ls_dia;
									}
									$li_depacum=$li_depacum + $li_depmes;
									$li_valcont=($li_cosact-$li_depacum);
									$li_totrows=$li_totrows + 1;
									uf_pintargrid($ld_fecdep,$ls_mesd,$ls_diasd,$li_depacum,$li_depmes,$li_depanu,$li_valcont,$lo_object,$li_totrows);
									$lb_mes1=false;
								}// end for
								$ls_mesf=1;
							}// end for
						}
					}
				}
				else
				{
					$io_msg->message("No se pudo calcular la depreciacion, Faltan datos para calcularla");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,$li_totrows);
				}
			}
			else
			{
				$io_msg->message("El activo que selecciono no ha sido depreciado");
				$io_msg->message("No se puede recalcular la depreciación");
				uf_limpiarvariables();
				$ls_operacion="";
				uf_agregarlineablanca($lo_object,$li_totrows);
			}			
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
    <table width="746" height="159" border="0" class="formato-blanco">
      <tr>
        <td width="749" ><div align="left">
            <table width="661" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="3" class="titulo-ventana">C&aacute;lculo de Depreciaci&oacute;n del Activo </td>
              </tr>
              <tr class="formato-blanco">
                <td width="195" height="19">&nbsp;</td>
                <td width="395"><div align="right">Fecha</div></td>
                <td width="114"><input name="txtfecdepact" type="text" id="txtfecdepact" style="text-align:center " value="<?php print $ld_fecdepact ?>" size="13" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Activo</div></td>
                <td height="22" colspan="2"><input name="txtcodact" type="text" id="txtcodact" value="<?php print $ls_codact ?>" style="text-align:center " readonly>                  
                <a href="javascript: ue_catactivo();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
                <input name="txtdenact" type="text" class="sin-borde" id="txtdenact" value="<?php print $ls_denact ?>" size="55"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Serial</div></td>
                <td height="22" colspan="2"><input name="txtseract" type="text" id="txtseract" value="<?php print $ls_seract ?>" style="text-align:center " readonly>                </td>
              </tr>
              <tr class="formato-blanco">
                <td height="21"><div align="right">Identificador</div></td>
                <td height="22" colspan="2"><input name="txtideact" type="text" id="txtideact" value="<?php print $ls_ideact ?>"  style="text-align:center " readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="21"><div align="right">Fecha de Compra</div></td>
                <td height="22" colspan="2"><input name="txtfeccmpact" type="text" id="txtfeccmpact" value="<?php print $ld_feccmpact ?>" style="text-align:center " readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="21"><div align="right">Fecha de Incorporaci&oacute;n </div></td>
                <td height="22" colspan="2"><input name="txtfecincact" type="text" id="txtfecincact" value="<?php print $ld_fecincact ?>" style="text-align:center " readonly></td>
              </tr>
<?php
	if($ls_operacion=="DEPRECIAR")
	{
?>
              <tr class="formato-blanco">
                <td height="21"><div align="right">Fecha de Modificaci&oacute;n </div></td>
                <td height="22" colspan="2"><input name="txtfecmod" type="text" id="txtfecmod" value="<?php print $ld_fecmod ?>" style="text-align:center " readonly></td>
              </tr>
<?php
	}
?>
              <tr class="formato-blanco">
                <td height="21"><div align="right">Metodo</div></td>
                <td height="22" colspan="2"><select name="cmbmetodos" id="cmbmetodos">
                  <option value="001">Linea Recta</option>
                </select></td>
              </tr>
              <tr class="formato-blanco">
                <td height="21" colspan="3" class="titulo-celdanew">Depreciaci&oacute;n</td>
              </tr>
              <tr class="formato-blanco">
                <td height="21" colspan="3"><table width="585" border="0" align="center">
                  <tr>
                    <td width="123"><div align="right">Costo</div></td>
                    <td width="166"><input name="txtcosact" type="text" id="txtcosact" value="<?php print number_format($li_cosact,2,',','.'); ?>" style="text-align:right " readonly></td>
                    <td width="158"><div align="right">Vida Util (Meses) </div></td>
                    <td width="197"><input name="txtviduti" type="text" id="txtviduti" value="<?php print number_format($li_viduti,2,',','.'); ?>" style="text-align:right " readonly></td>
                  </tr>
                  <tr>
                    <td><div align="right">Valor de Rescate </div></td>
                    <td><input name="txtcossal" type="text" id="txtcossal" value="<?php print number_format($li_cossal,2,',','.'); ?>" style="text-align:right " readonly></td>
                    <td><div align="right">Dep.Mensual</div></td>
                    <td><input name="txtdepmen" type="text" id="txtdepmen" value="<?php print number_format($li_depmen,2,',','.'); ?>" style="text-align:right " readonly></td>
                  </tr>
                  <tr>
                    <td><div align="right">Monto a Depreciar </div></td>
                    <td><input name="txtmondep" type="text" id="txtmondep" value="<?php print number_format($li_mondep,2,',','.'); ?>" style="text-align:right " readonly></td>
                    <td><div align="right">Dep. Anual</div></td>
                    <td><input name="txtdepanu" type="text" id="txtdepanu" value="<?php print number_format($li_depanu,2,',','.'); ?>" style="text-align:right " readonly></td>
                  </tr>
                </table></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28" colspan="3"><div align="center">
                    <?php
		$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	?>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right"></div></td>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
            <input name="operacion" type="hidden" id="operacion">
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
		window.open("sigesp_saf_pdt_activo.php?totrow="+ li_totrow +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=200,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catactivo()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		window.open("sigesp_saf_cat_codactivo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_saf_p_depreciacion.php";
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		li_row=f.totalfilas.value;
		li_valcon=eval("f.txtvalcon"+li_row+".value");
		li_valres=f.txtcossal.value
		if(li_valcon==li_valres)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_p_depreciacion.php";
			f.submit();
		}
		else
		{
			alert("No se pueden registar los datos");
		}
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
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>