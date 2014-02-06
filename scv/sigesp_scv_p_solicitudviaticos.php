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
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_p_solicitudviaticos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_formato=$io_fun_viaticos->uf_select_config("SCV","REPORTE","SOLICITUD_VIATICOS","sigesp_scv_rfs_solicitudviaticos.php","C");
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codsolvia,$ld_fecsolvia,$ls_codmis,$ls_denmis,$ls_codrut,$ls_desrut,$ls_coduniadm,$ls_denuniadm;
		global $ls_ctaspg,$ls_denctaspg,$ld_fecsal,$ld_fecreg,$li_numdia,$ls_obssolvia,$ls_checked,$lb_fechaok;
		global $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_codfuefin;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		global $ls_titlepersonal,$lo_titlepersonal,$li_totrowspersonal;

		$ls_codsolvia="";
		$ld_fecsolvia=date("d/m/Y");
		$ls_codmis="";
		$ls_denmis="";
		$ls_codrut="";
		$ls_desrut="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_codestpro1="";		
		$ls_codestpro2="";		
		$ls_codestpro3="";		
		$ls_codestpro4="";		
		$ls_codestpro5="";
		$ls_estcla="";
		$ls_codfuefin="";
		$ls_ctaspg="";
		$ls_denctaspg="";
		$ld_fecsal="";
		$ld_fecreg="";
		$li_numdia="";
		$ls_obssolvia="";
		$ls_checked="";
		$lb_fechaok=0;
		$ls_titletable="Asignaciones";
		$li_widthtable=730;
		$ls_nametable="grid";
		$lo_title[1]="Procedencia";
		$lo_title[2]="Código";
		$lo_title[3]="Concepto";
		$lo_title[4]="Cantidad";
		$lo_title[5]="";
		$li_totrows=1;
		$ls_titlepersonal="Personal";
		$lo_titlepersonal[1]="Código";
		$lo_titlepersonal[2]="Nombre";
		$lo_titlepersonal[3]="Cédula";
		$lo_titlepersonal[4]="Cargo";
		$lo_titlepersonal[5]="Categoría";
		$lo_titlepersonal[6]="";
		$li_totrowspersonal=1;
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
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtproasig".$ai_totrows."  type=text   id=txtproasig".$ai_totrows."  class=sin-borde size=16 readonly style='text-align:center'>";
		$aa_object[$ai_totrows][2]="<input name=txtcodasig".$ai_totrows."  type=text   id=txtcodasig".$ai_totrows."  class=sin-borde size=11 readonly >";
		$aa_object[$ai_totrows][3]="<input name=txtdenasig".$ai_totrows."  type=text   id=txtdenasig".$ai_totrows."  class=sin-borde size=55 readonly >";
		$aa_object[$ai_totrows][4]="<input name=txtcantidad".$ai_totrows." type=text   id=txtcantidad".$ai_totrows." class=sin-borde size=12 readonly  style='text-align:right'>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt_asignaciones(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";

   }

   function uf_agregarlineablancapersonal(&$aa_objectpersonal,$ai_totrowspersonal)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_objectpersonal  // arreglo de titulos 
		//				   $ai_totrowspersonal // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_objectpersonal[$ai_totrowspersonal][1]="<input name=txtcodper".$ai_totrowspersonal."    type=text   id=txtcodper".$ai_totrowspersonal."    class=sin-borde size=15 readonly >";
		$aa_objectpersonal[$ai_totrowspersonal][2]="<input name=txtnomper".$ai_totrowspersonal."    type=text   id=txtnomper".$ai_totrowspersonal."    class=sin-borde size=40 readonly >";
		$aa_objectpersonal[$ai_totrowspersonal][3]="<input name=txtcedper".$ai_totrowspersonal."    type=text   id=txtcedper".$ai_totrowspersonal."    class=sin-borde size=11 readonly >";
		$aa_objectpersonal[$ai_totrowspersonal][4]="<input name=txtcodcar".$ai_totrowspersonal."    type=text   id=txtcodcar".$ai_totrowspersonal."    class=sin-borde size=30 readonly >";
		$aa_objectpersonal[$ai_totrowspersonal][5]="<input name=txtcodclavia".$ai_totrowspersonal." type=text   id=txtcodclavia".$ai_totrowspersonal." class=sin-borde size=10 readonly style='text-align:center'>".
												   "<input name=txtcodnom".$ai_totrowspersonal."    type=hidden id=txtcodnom".$ai_totrowspersonal."    class=sin-borde >";
		$aa_objectpersonal[$ai_totrowspersonal][6]="<a href=javascript:uf_delete_dt_personal(".$ai_totrowspersonal.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";

   }

   function uf_repintarasignaciones(&$aa_object,&$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_repintarasignaciones
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar lo que esta impreso en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_fun_viaticos=new class_funciones_viaticos();
		for($li_i=1;$li_i<=$ai_totrows;$li_i++)
		{
			$ls_proasi= $io_fun_viaticos->uf_obtenervalor("txtproasig".$li_i,"");
			$ls_codasi= $io_fun_viaticos->uf_obtenervalor("txtcodasig".$li_i,"");
			$ls_denasi= $io_fun_viaticos->uf_obtenervalor("txtdenasig".$li_i,"");
			$li_canasi= $io_fun_viaticos->uf_obtenervalor("txtcantidad".$li_i,"");
			
			$aa_object[$li_i][1]="<input name=txtproasig".$li_i."  type=text   id=txtproasig".$li_i."  class=sin-borde size=16 value='". $ls_proasi ."' readonly style='text-align:center'>";
			$aa_object[$li_i][2]="<input name=txtcodasig".$li_i."  type=text   id=txtcodasig".$li_i."  class=sin-borde size=11 value='". $ls_codasi ."' readonly>";
			$aa_object[$li_i][3]="<input name=txtdenasig".$li_i."  type=text   id=txtdenasig".$li_i."  class=sin-borde size=55 value='". $ls_denasi ."' readonly>";
			$aa_object[$li_i][4]="<input name=txtcantidad".$li_i." type=text   id=txtcantidad".$li_i." class=sin-borde size=12 value='". $li_canasi ."' readonly style='text-align:right'>";
			$aa_object[$li_i][5]="<a href=javascript:uf_delete_dt_asignaciones(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		return true;

   }
   function uf_repintarpersonal(&$aa_objectpersonal,&$ai_totrowspersonal)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_repintarpersonal
		//         Access: private
		//      Argumento: $aa_objectpersonal  // arreglo de titulos 
		//				   $ai_totrowspersonal // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar lo que esta impreso en el grid de personal
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 19/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_fun_viaticos=new class_funciones_viaticos();
		for($li_i=1;$li_i<=$ai_totrowspersonal;$li_i++)
		{
			$ls_codper=    $io_fun_viaticos->uf_obtenervalor("txtcodper".$li_i,"");
			$ls_nomper=    $io_fun_viaticos->uf_obtenervalor("txtnomper".$li_i,"");
			$ls_codcar=    $io_fun_viaticos->uf_obtenervalor("txtcodcar".$li_i,"");
			$ls_cedper=    $io_fun_viaticos->uf_obtenervalor("txtcedper".$li_i,"");
			$ls_codclavia= $io_fun_viaticos->uf_obtenervalor("txtcodclavia".$li_i,"");
			$ls_codnom=    $io_fun_viaticos->uf_obtenervalor("txtcodnom".$li_i,"");

			$aa_objectpersonal[$li_i][1]="<input name=txtcodper".$li_i."    type=text   id=txtcodper".$li_i."    class=sin-borde size=15 value='". $ls_codper ."'    readonly>";
			$aa_objectpersonal[$li_i][2]="<input name=txtnomper".$li_i."    type=text   id=txtnomper".$li_i."    class=sin-borde size=40 value='". $ls_nomper ."'    readonly>";
			$aa_objectpersonal[$li_i][3]="<input name=txtcedper".$li_i."    type=text   id=txtcedper".$li_i."    class=sin-borde size=11 value='". $ls_cedper ."'    readonly>";
			$aa_objectpersonal[$li_i][4]="<input name=txtcodcar".$li_i."    type=text   id=txtcodcar".$li_i."    class=sin-borde size=30 value='". $ls_codcar ."'    readonly>";
			$aa_objectpersonal[$li_i][5]="<input name=txtcodclavia".$li_i." type=text   id=txtcodclavia".$li_i." class=sin-borde size=10 value='". $ls_codclavia ."' readonly style='text-align:center'>".
 									     "<input name=txtcodnom".$li_i."    type=hidden id=txtcodnom".$li_i."    class=sin-borde  value='".$ls_codnom."'>";
			$aa_objectpersonal[$li_i][6]="<a href=javascript:uf_delete_dt_personal(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		return true;

   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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
<title >Solicitud de Viaticos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
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
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
			<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Control de Viaticos </td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="640">&nbsp;</td>
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
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fundb= new class_funciones_db($con);
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("sigesp_scv_c_solicitudviaticos.php");
	$io_scv= new sigesp_scv_c_solicitudviaticos();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_operacion=$io_fun_viaticos->uf_obtenervalor("operacion","NUEVO");
	$lb_cierre=$io_fun_viaticos->uf_select_cierre_presupuestario();
	uf_limpiarvariables();
	if(empty($ls_operacion))
	{
		uf_agregarlineablanca($lo_object,$li_totrows);
		uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
	}
	$ls_codsolvia= $io_fun_viaticos->uf_obtenervalor("txtcodsolvia","");
	$ld_fecsolvia= $io_fun_viaticos->uf_obtenervalor("txtfecsolvia","");
	$ls_codmis=    $io_fun_viaticos->uf_obtenervalor("txtcodmis","");
	$ls_denmis=    $io_fun_viaticos->uf_obtenervalor("txtdenmis","");
	$ls_codrut=    $io_fun_viaticos->uf_obtenervalor("txtcodrut","");
	$ls_desrut=    $io_fun_viaticos->uf_obtenervalor("txtdesrut","");
	$ls_coduniadm= $io_fun_viaticos->uf_obtenervalor("txtcoduniadm","");
	$ls_denuniadm= $io_fun_viaticos->uf_obtenervalor("txtdenuniadm","");
	$ls_codestpro1=$io_fun_viaticos->uf_obtenervalor("txtcodestpro1","");
	$ls_codestpro2=$io_fun_viaticos->uf_obtenervalor("txtcodestpro2","");	
	$ls_codestpro3=$io_fun_viaticos->uf_obtenervalor("txtcodestpro3","");	
	$ls_codestpro4=$io_fun_viaticos->uf_obtenervalor("txtcodestpro4","");	
	$ls_codestpro5=$io_fun_viaticos->uf_obtenervalor("txtcodestpro5","");
	$ls_codfuefin=$io_fun_viaticos->uf_obtenervalor("txtcodfuefin","--");
	if($ls_codfuefin=="")
	{
		$ls_codfuefin="--";
	} 
	$ls_estcla=$io_fun_viaticos->uf_obtenervalor("hidestcla","");		
	$ls_ctaspg=    $io_fun_viaticos->uf_obtenervalor("txtctaspg","");
	$ls_denctaspg= $io_fun_viaticos->uf_obtenervalor("txtdenctaspg","");
	$ls_obssolvia= $io_fun_viaticos->uf_obtenervalor("txtobssolvia","");
	$ld_fecsal=    $io_fun_viaticos->uf_obtenervalor("txtfecsal","");
	$ld_fecreg=    $io_fun_viaticos->uf_obtenervalor("txtfecreg","");
	$li_numdia=    $io_fun_viaticos->uf_obtenervalor("txtnumdia","");
	$ls_estatus=   $io_fun_viaticos->uf_obtenervalor("hidestatus","");
	$ls_estsolvia= $io_fun_viaticos->uf_obtenervalor("hidestsolvia","");
	$li_solviaext= $io_fun_viaticos->uf_obtenervalor("chksolviaext",0);
	$lb_fechaok= $io_fun_viaticos->uf_obtenervalor("fechaok",0);
	$ld_fecsalvia= $io_fun->uf_convertirdatetobd($ld_fecsal);
	$ld_fecregvia= $io_fun->uf_convertirdatetobd($ld_fecreg);
	$ld_fecsolaux= $io_fun->uf_convertirdatetobd($ld_fecsolvia);
	$li_numdiaaux= str_replace(".","",$li_numdia);
	$li_numdiaaux= str_replace(",",".",$li_numdiaaux);
	if($li_solviaext==1)
	{
		$ls_checked="checked";
	}
	switch ($ls_operacion) 
	{
		case "NUEVO":
				$ls_estsolvia="R";
			if($lb_cierre!=false)
			{
				uf_limpiarvariables();
				$lb_empresa= true;
				$ls_estatus="";
				$ls_codsolvia= $io_keygen->uf_generar_numero_nuevo("SCV","scv_solicitudviatico","codsolvia","SCV",8,"","","");
	//			$ls_codsolvia= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_solicitudviatico','codsolvia');
				if(empty($ls_codsolvia))
				{
					$io_msg->message("Error asignando Código de Solicitud de Viaticos");
				}
			}
			uf_agregarlineablanca($lo_object,1);
			uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
		break;
		
		case "GUARDAR":
			$li_totrows= $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$li_totrowspersonal= $io_fun_viaticos->uf_obtenervalor("totalfilaspersonal","");
			uf_repintarpersonal($lo_objectpersonal,$li_totrowspersonal);
			uf_repintarasignaciones($lo_object,$li_totrows);
			$lb_valido=$io_fun_viaticos->uf_select_cierre_presupuestario();
			if($lb_valido)
			{
				$lb_valido=$io_fec->uf_valida_fecha_periodo($ld_fecsolvia,$ls_codemp);
				if($lb_valido)
				{
					if($li_solviaext==1)
					{
						$lb_existe=$io_scv->uf_scv_load_config($ls_codemp,"SCV","CONFIG","INTERNACIONALES",$ls_spgcta);
					}
					else
					{
						$lb_existe=$io_scv->uf_scv_load_config($ls_codemp,"SCV","CONFIG","NACIONALES",$ls_spgcta);
					}
					if($lb_existe)
					{
						
						$lb_valido=$io_scv->uf_scv_select_cuentaspg($ls_codemp,$ls_spgcta,$ls_codestpro1,$ls_codestpro2,
						                                            $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
						if(!$lb_valido)
						{
							$io_msg->message("La cuenta presupuestaria no existe en la estructura de la Unidad Solicitante");
							break;
						}
						/*else 
						{
							$lb_valido=$io_scv->uf_scv_select_cuentaspg_fuente_financiamiento($ls_codemp,$ls_spgcta,
							                                                                  $ls_codestpro1,$ls_codestpro2,
						                                            						  $ls_codestpro3,$ls_codestpro4,
																	                          $ls_codestpro5,$ls_estcla,
																							  $ls_codfuefin);
							if(!$lb_valido)
							{
								$io_msg->message("La cuenta presupuestaria no existe en la estructura de la Fuente de Financiamiento");
								break;
							}
						}*/
						
					}
					else
					{
						$io_msg->message("No ha definido cuentas presupuestarias de Viáticos");
						break;
					}
					$io_sql->begin_transaction();
					if($ls_estatus=="C")
					{
						$lb_existe=$io_scv->uf_scv_select_solicitudviaticos($ls_codemp,$ls_codsolvia);
						if($lb_existe)
						{
							if($ls_estsolvia=="R")
							{
								$lb_valido=$io_scv->uf_scv_update_solicitudviatico($ls_codemp,$ls_codsolvia,$ls_codmis,$ls_codrut,
																				   $ls_coduniadm,$ls_codestpro1,$ls_codestpro2, 
																				   $ls_codestpro3,$ls_codestpro4,
																				   $ls_codestpro5,$ls_estcla,$ld_fecsalvia,
																				   $ld_fecregvia,
																				   $ld_fecsolaux,$li_numdiaaux,$ls_obssolvia,
																				   $li_solviaext,$ls_codfuefin,
																				   $la_seguridad);
								for($li_i=1;$li_i<$li_totrows;$li_i++)
								{
									$ls_proasi= $io_fun_viaticos->uf_obtenervalor("txtproasig".$li_i,"");
									$ls_codasi= $io_fun_viaticos->uf_obtenervalor("txtcodasig".$li_i,"");
									$ls_denasi= $io_fun_viaticos->uf_obtenervalor("txtdenasig".$li_i,"");
									$li_canasi= $io_fun_viaticos->uf_obtenervalor("txtcantidad".$li_i,"");
									$li_canasi= str_replace(".","",$li_canasi);
									$li_canasi= str_replace(",",".",$li_canasi);
									$lb_existe=$io_scv->uf_scv_select_dt_asignaciones($ls_codemp,$ls_codsolvia,$ls_codasi,$ls_proasi);
									if(!$lb_existe)
									{
										$lb_valido=$io_scv->uf_scv_insert_dt_asignaciones($ls_codemp,$ls_codsolvia,$ls_codasi,$ls_proasi,
																						  $li_canasi,$la_seguridad);
										if(!$lb_valido)
										{break;}
									}
								}	
								if($lb_valido)
								{
									for($li_i=1;$li_i<$li_totrowspersonal;$li_i++)
									{
										$ls_codper= $io_fun_viaticos->uf_obtenervalor("txtcodper".$li_i,"");
										$ls_nomper= $io_fun_viaticos->uf_obtenervalor("txtnomper".$li_i,"");
										$ls_codcar= $io_fun_viaticos->uf_obtenervalor("txtcodcar".$li_i,"");
										$ls_cedper= $io_fun_viaticos->uf_obtenervalor("txtcedper".$li_i,"");
										$ls_codclavia= $io_fun_viaticos->uf_obtenervalor("txtcodclavia".$li_i,"");
										$ls_codnom= $io_fun_viaticos->uf_obtenervalor("txtcodnom".$li_i,"");
										$lb_existe=$io_scv->uf_scv_validar_fecha_viaticos($ls_codemp,$ls_codper,$ld_fecsalvia,
																						  $ld_fecregvia);
										if(!$lb_existe)
										{
											$lb_existe=$io_scv->uf_scv_select_dt_personal($ls_codemp,$ls_codsolvia,$ls_codper);
											if(!$lb_existe)
											{
												$lb_valido=$io_scv->uf_scv_insert_dt_personal($ls_codemp,$ls_codsolvia,$ls_codper,
																							  $ls_codclavia,$ls_codnom,	
																							  $la_seguridad);
												if(!$lb_valido)
												{break;}
											}
										}
										else
										{
											
											$io_msg->message("El personal/beneficiario ".$ls_codper." tiene Solicitudes de Viaticos para este Periodo");
											$lb_valido=false;
											break;
										}
									}
								}
								if($lb_valido)
								{
									$io_sql->commit();
									$io_msg->message("La solicitud de viaticos fue Modificada");
								}
								else
								{
									$io_sql->rollback();
									$io_msg->message("Verifique la solicitud de viaticos");
								}
							}
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No existe la Solicitud de Viaticos");
						}
					}
					else
					{
							$lb_valido=$io_scv->uf_scv_insert_solicitudviatico($ls_codemp,$ls_codsolvia,$ls_codmis,$ls_codrut,
							                                                   $ls_coduniadm,$ls_codestpro1,$ls_codestpro2,
																			   $ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
																			   $ls_estcla,
																			   $ld_fecsalvia,$ld_fecregvia,$ld_fecsolaux,
																			   $li_numdiaaux,
																			   $ls_obssolvia,$ls_estsolvia,$li_solviaext,
																			   $ls_codfuefin,$la_seguridad);
							if($lb_valido)
							{
								for($li_i=1;$li_i<$li_totrows;$li_i++)
								{
									$ls_proasi= $io_fun_viaticos->uf_obtenervalor("txtproasig".$li_i,"");
									$ls_codasi= $io_fun_viaticos->uf_obtenervalor("txtcodasig".$li_i,"");
									$ls_denasi= $io_fun_viaticos->uf_obtenervalor("txtdenasig".$li_i,"");
									$li_canasi= $io_fun_viaticos->uf_obtenervalor("txtcantidad".$li_i,"");
									$li_canasi= str_replace(".","",$li_canasi);
									$li_canasi= str_replace(",",".",$li_canasi);
									$lb_valido=$io_scv->uf_scv_insert_dt_asignaciones($ls_codemp,$ls_codsolvia,$ls_codasi,$ls_proasi,
																					  $li_canasi,$la_seguridad);
									if(!$lb_valido)
									{break;}
								}	
								if($lb_valido)
								{
									for($li_i=1;$li_i<$li_totrowspersonal;$li_i++)
									{
										$ls_codper= $io_fun_viaticos->uf_obtenervalor("txtcodper".$li_i,"");
										$ls_nomper= $io_fun_viaticos->uf_obtenervalor("txtnomper".$li_i,"");
										$ls_codcar= $io_fun_viaticos->uf_obtenervalor("txtcodcar".$li_i,"");
										$ls_cedper= $io_fun_viaticos->uf_obtenervalor("txtcedper".$li_i,"");
										$ls_codclavia= $io_fun_viaticos->uf_obtenervalor("txtcodclavia".$li_i,"");
										$ls_codnom= $io_fun_viaticos->uf_obtenervalor("txtcodnom".$li_i,"");
										$lb_existe=$io_scv->uf_scv_validar_fecha_viaticos($ls_codemp,$ls_codper,$ld_fecsalvia,
																						  $ld_fecregvia);
										if(!$lb_existe)
										{
											$lb_valido=$io_scv->uf_scv_insert_dt_personal($ls_codemp,$ls_codsolvia,$ls_codper,
																						  $ls_codclavia,$ls_codnom,$la_seguridad);
											if(!$lb_valido)
											{break;}
										}
										else
										{
											$io_msg->message("El personal/beneficiario ".$ls_codper." ya tiene Solicitudes de Viaticos para este Periodo");
											$lb_valido=false;
											break;
										}
									}
								}	
							}

						if($lb_valido)
						{
							$io_sql->commit();
							$io_msg->message("La solicitud de viaticos fue Registrada");
							$ls_estatus="C";
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No se pudo realizar la solicitud de viaticos");
						}
					}
				}
				else
				{
					$io_msg->message("El mes no esta abierto");
				}
			}
			//////
		break;
		case "ELIMINAR":
			$lb_valido= $io_scv->uf_scv_delete_solicitudviatico($ls_codemp,$ls_codsolvia,$la_seguridad);
			if($lb_valido)
			{
				$io_msg->message("La Solicitud de Viaticos ha sido eliminada");
				$li_totrows=1;
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
			}
			else
			{
				$io_msg->message("No se pudo eliminar la Solicitud de Viaticos");
				$lb_valido= uf_repintarasignaciones($lo_object,$li_totrows);
				$lb_valido= uf_repintarpersonal($lo_objectpersonal,$li_totrowspersonal);
			}
		break;
		case "ELIMINARDETALLEASIG":
			$lb_valido=true;
			$li_totrows=   $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$li_totrowspersonal=   $io_fun_viaticos->uf_obtenervalor("totalfilaspersonal","");
			$lb_valido= uf_repintarasignaciones($lo_object,$li_totrows);
			$lb_valido= uf_repintarpersonal($lo_objectpersonal,$li_totrowspersonal);
			$li_rowdelete= $io_fun_viaticos->uf_obtenervalor("filadelete","");
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_proasi= $io_fun_viaticos->uf_obtenervalor("txtproasig".$li_i,"");
				$ls_codasi= $io_fun_viaticos->uf_obtenervalor("txtcodasig".$li_i,"");
				$ls_denasi= $io_fun_viaticos->uf_obtenervalor("txtdenasig".$li_i,"");
				$li_canasi= $io_fun_viaticos->uf_obtenervalor("txtcantidad".$li_i,"");
				if($li_i!=$li_rowdelete)
				{		
					$li_temp++;
					$lo_object[$li_temp][1]="<input name=txtproasig".$li_temp."  type=text   id=txtproasig".$li_temp."  class=sin-borde size=16 value='". $ls_proasi ."' readonly style='text-align:center'>";
					$lo_object[$li_temp][2]="<input name=txtcodasig".$li_temp."  type=text   id=txtcodasig".$li_temp."  class=sin-borde size=11 value='". $ls_codasi ."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtdenasig".$li_temp."  type=text   id=txtdenasig".$li_temp."  class=sin-borde size=55 value='". $ls_denasi ."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtcantidad".$li_temp." type=text   id=txtcantidad".$li_temp." class=sin-borde size=12 value='". $li_canasi ."' readonly style='text-align:right'>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt_asignaciones(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
				}
				else
				{
					$lb_valido=$io_scv->uf_scv_delete_dt_asignacion($ls_codemp,$ls_codsolvia,$ls_codasi,$ls_proasi,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$li_totrows=$li_temp;
			//	uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
			}
		break;
		case "ELIMINARDETALLEPER":
			$lb_valido=true;
			$li_totrows=   $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$lb_valido= uf_repintarasignaciones($lo_object,$li_totrows);
			$lb_valido= uf_repintarasignaciones($lo_object,$li_totrows);
			$li_totrowspersonal=   $io_fun_viaticos->uf_obtenervalor("totalfilaspersonal","");
			$li_rowdeletepersonal= $io_fun_viaticos->uf_obtenervalor("filadeleteper","");
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrowspersonal;$li_i++)
			{
				$ls_codper=    $io_fun_viaticos->uf_obtenervalor("txtcodper".$li_i,"");
				$ls_nomper=    $io_fun_viaticos->uf_obtenervalor("txtnomper".$li_i,"");
				$ls_codcar=    $io_fun_viaticos->uf_obtenervalor("txtcodcar".$li_i,"");
				$ls_cedper=    $io_fun_viaticos->uf_obtenervalor("txtcedper".$li_i,"");
				$ls_codclavia= $io_fun_viaticos->uf_obtenervalor("txtcodclavia".$li_i,"");
				$ls_codnom= $io_fun_viaticos->uf_obtenervalor("txtcodnom".$li_i,"");
				if($li_i!=$li_rowdeletepersonal)
				{		
					$li_temp++;
					$lo_objectpersonal[$li_temp][1]="<input name=txtcodper".$li_temp."    type=text   id=txtcodper".$li_temp."    class=sin-borde size=15 value='". $ls_codper ."'>";
					$lo_objectpersonal[$li_temp][2]="<input name=txtnomper".$li_temp."    type=text   id=txtnomper".$li_temp."    class=sin-borde size=40 value='". $ls_nomper ."'>";
					$lo_objectpersonal[$li_temp][3]="<input name=txtcedper".$li_temp."    type=text   id=txtcedper".$li_temp."    class=sin-borde size=11 value='". $ls_cedper ."'>";
					$lo_objectpersonal[$li_temp][4]="<input name=txtcodcar".$li_temp."    type=text   id=txtcodcar".$li_temp."    class=sin-borde size=30 value='". $ls_codcar ."'>";
					$lo_objectpersonal[$li_temp][5]="<input name=txtcodclavia".$li_temp." type=text   id=txtcodclavia".$li_temp." class=sin-borde size=10 value='". $ls_codclavia ."' style='text-align:center'>".
 									     "<input name=txtcodnom".$li_temp."    type=hidden id=txtcodnom".$li_temp."    class=sin-borde  value='".$ls_codnom."'>";
					$lo_objectpersonal[$li_temp][6]="<a href=javascript:uf_delete_dt_personal(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
				}
				else
				{
					$lb_valido=$io_scv->uf_scv_delete_dt_personal($ls_codemp,$ls_codsolvia,$ls_codper,$la_seguridad);
				}
			}
			if($lb_valido)
			{
				$li_totrowspersonal=$li_temp;
			//	uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
			}
		break;
		case "BUSCARDETALLE":
			$li_totrows=0;
			$li_totrowspersonal=0;
			$ld_fecsolvia= $io_fun_viaticos->uf_obtenervalor("txtfecsolvia","");
			$lb_valido=$io_scv->uf_scv_load_dt_asignacion($ls_codemp,$ls_codsolvia,$li_totrows,$lo_object);
			if($lb_valido)
			{
				$lb_valido=$io_scv->uf_scv_load_dt_personal($ls_codemp,$ls_codsolvia,$li_totrowspersonal,$lo_objectpersonal);
			}
			$li_totrows++;
			$li_totrowspersonal++;
			uf_agregarlineablanca($lo_object,$li_totrows);
			uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
		break;
		case "AGREGARASIGNACIONES":
			$li_totrows=   $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$lb_valido= uf_repintarasignaciones($lo_object,$li_totrows);
			$li_totrowspersonal=   $io_fun_viaticos->uf_obtenervalor("totalfilaspersonal","");
			if($lb_valido)
			{
				$li_totrows++;
				uf_agregarlineablanca($lo_object,$li_totrows);
				$lb_valido= uf_repintarpersonal($lo_objectpersonal,$li_totrowspersonal);
			}
		break;
		case "AGREGARPERSONAL":
			$li_totrows= $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$li_totrowspersonal= $io_fun_viaticos->uf_obtenervalor("totalfilaspersonal","");
			$lb_valido= uf_repintarpersonal($lo_objectpersonal,$li_totrowspersonal);
			if($lb_valido)
			{
				$li_totrowspersonal++;
				$lb_valido= uf_repintarasignaciones($lo_object,$li_totrows);
				uf_agregarlineablancapersonal($lo_objectpersonal,$li_totrowspersonal);
			}
		break;
	}
	switch($ls_estsolvia)
	{
		case "R":
			$ls_estatussol="Registro";
		break;
		case "P":
			$ls_estatussol="Procesada";
		break;
		case "A":
			$ls_estatussol="Anulada";
		break;	
	}
?>
<p>&nbsp;</p>
<form action="" method="post" name="form1" id="form1" enctype="multipart/form-data">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_viaticos->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_viaticos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="754" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="22" colspan="6"><table width="400" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" class="sin-borde2"><div align="right">Estatus:</div></td>
        <td width="330"> <input name="txtestsolvia" type="text" class="sin-borde2" id="txtestsolvia" value="<?php print $ls_estatussol;?>">
          <input name="hidestsolvia" type="hidden" id="hidestsolvia" value="<?php print $ls_estsolvia;?>"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="22" colspan="6" class="titulo-ventana">Solicitud de Viaticos </td>  
  </tr>
  <tr>
    <td height="22" colspan="2" style="visibility:hidden">Reporte en
      <select name="cmbbsf" id="cmbbsf">
        <option value="0" selected>Bs.</option>
        <option value="1">Bs.F.</option>
      </select></td>
	<?php
//	  	 }
	?>
    <td><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus;?>">
        <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;  ?>">
        <input name="formato" type="hidden" id="formato" value="<?php print $ls_formato;?>">
        <input name="cierre" type="hidden" id="cierre" value="<?php print $lb_cierre; ?>"></td>
    <td colspan="2"><div align="right">Fecha</div></td>
    <td width="167"><input name="txtfecsolvia" type="text" id="txtfecsolvia" style="text-align:center"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);"  value="<?php print $ld_fecsolvia;  ?>" size="17" datepicker="true"></td>  </tr>
  <tr>
    <td height="22" colspan="2"><div align="right">C&oacute;digo</div></td>
    <td colspan="4"><input name="txtcodsolvia" type="text" id="txtcodsolvia" style="text-align:center" value="<?php print $ls_codsolvia;?>" size="10" readonly></td>  </tr>
  <tr>
    <td height="22" colspan="2"><div align="right">Misi&oacute;n</div></td>
    <td colspan="4"><input name="txtcodmis" type="text" id="txtcodmis" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codmis; ?>" size="10" maxlength="5" readonly style="text-align:center ">
        <a href="javascript: ue_buscarmision();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdenmis" type="text" class="sin-borde" id="txtdenmis"  value="<?php print $ls_denmis; ?>" size="70" readonly></td>  </tr>
  <tr>
    <td height="22" colspan="2"><div align="right">Ruta</div></td>
    <td colspan="4"><input name="txtcodrut" type="text" id="txtcodrut" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codrut; ?>" size="10" maxlength="5" readonly style="text-align:center ">
      <a href="javascript: ue_buscarruta();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdesrut" type="text" class="sin-borde" id="txtdesrut"  value="<?php print $ls_desrut; ?>" size="70" readonly></td>  </tr>
  <tr>
    <td height="22" colspan="2"><div align="right"> Unidad Solicitante </div></td>
    <td colspan="4"><input name="txtcoduniadm" type="text" id="txtcoduniadm" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_coduniadm; ?>" size="15" maxlength="10" readonly style="text-align:center ">
        <a href="javascript: ue_buscarunidad();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm"  value="<?php print $ls_denuniadm; ?>" size="70" readonly></td>  </tr>
  <tr>
    <td colspan="2"><div align="right">Fecha de Salida </div></td>
    <td width="259" height="22"><input name="txtfecsal" type="text" id="txtfecsal" style="text-align:center"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" value="<?php print $ld_fecsal; ?>" size="17" datepicker="true" onBlur="DiferenciaFechas();"></td>
    <td width="124"><div align="right">Nro. Dias
        <input name="txtnumdia" type="text" id="txtnumdia" value="<?php print $li_numdia; ?>" size="8" style="text-align:center" onChange="ue_verificar();" readonly>
    </div></td>
    <td colspan="2">&nbsp;</td>  </tr>
  <tr>
    <td colspan="2"><div align="right">Fecha de Retorno </div></td>
    <td height="22" colspan="4"><input name="txtfecreg" type="text" id="txtfecreg"  style="text-align:center"  onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" value="<?php print $ld_fecreg; ?>" size="17" datepicker="true"  onBlur="DiferenciaFechas();"></td>  </tr>
  <tr>
    <td height="22" colspan="2"><div align="right">Observaciones </div></td>
    <td height="22" colspan="4" rowspan="2"><textarea name="txtobssolvia" cols="73" id="txtobssolvia" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú ()@#¡!%/[]*-+_¿?');" onFocus="ue_recogerfecha();"><?php print $ls_obssolvia; ?></textarea></td>  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="right">Exterior
      </div></td>
    <td colspan="4"><input name="chksolviaext" type="checkbox" class="sin-borde" id="chksolviaext" value="1" <?php print $ls_checked; ?>></td>
  </tr>
  
  <tr>
    <td height="22" colspan="2"><div align="right"> Fuente de Financiamiento</div></td>
    <td colspan="4"><input name="txtcodfuefin" type="text" id="txtcodfuefin" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codfuefin; ?>" size="10" maxlength="2" readonly style="text-align:center ">
        <a href="javascript: ue_buscarfuentefinaciamiento();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
  </tr>
  
  <tr>
    <td width="23"><div align="left"><a href="javascript: ue_agregardetalleasignaciones();"></a> </div></td>
    <td width="164"><a href="javascript: ue_agregardetalleasignaciones();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" class="sin-borde">Agregar Asignaciones</a></td>
    <td colspan="4">&nbsp;</td>  </tr>
  <tr>
    <td colspan="6"><div align="center">
      <?php
		$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	?>
    </div></td>  </tr>
  <tr><td><a href="javascript: ue_agregardetallepersonal();"></a></td>
    <td><a href="javascript: ue_agregardetallepersonal();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" class="sin-borde">Agregar Personal </a></td>
    <td colspan="4">&nbsp;</td></tr><tr><td colspan="6"><div align="CENTER">
  <?php
		$in_grid->makegrid($li_totrowspersonal,$lo_titlepersonal,$lo_objectpersonal,$li_widthtable,$ls_titlepersonal,$ls_nametable);
	?>
  </div><div align="CENTER"></div></td></tr>
  <tr>
    <td height="22" colspan="6"><div align="left">
      <strong>TVS</strong>: Tarifas de Viaticos <strong>TRP</strong>: Tarifa de Transporte <strong>TDS</strong>: Tarifa de Distancias <strong>TOA</strong>: Otras Asignaciones</div> </td>
    </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td colspan="4"><input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows; ?>">
      <input name="filadelete" type="hidden" id="filadelete">
      <input name="filadeleteper" type="hidden" id="filadeleteper">
      <input name="totalfilaspersonal" type="hidden" id="totalfilaspersonal" value="<?php print $li_totrowspersonal; ?>">
      <input name="fechaok" type="hidden" id="fechaok" value="<? print $lb_fechaok; ?>">
	  <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>">
	  <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2;?>">
	  <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>">
	  <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
	  <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
	  <input name="hidestcla"     type="hidden" id="hidestcla"     value="<?php print $ls_estcla ?>"></td>
  </tr></table>
</form>
<p>&nbsp;</p>

</body>
<script language="javascript">
function ue_buscarmision()
{
	ls_destino="SOLICITUD";
	window.open("sigesp_scv_cat_misiones.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarruta()
{
	ls_destino="SOLICITUD";
	window.open("sigesp_scv_cat_rutas.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarfuentefinaciamiento()
{
	f=document.form1;
	coduniadm=f.txtcoduniadm.value;
	if (coduniadm!="")
	{
		ls_codestpro1=f.txtcodestpro1.value;
		ls_codestpro2=f.txtcodestpro2.value;
		ls_codestpro3=f.txtcodestpro3.value;
		ls_codestpro4=f.txtcodestpro4.value;
		ls_codestpro5=f.txtcodestpro5.value;
		ls_estcla=f.hidestcla.value;
		
		window.open("sigesp_scv_cat_fuentefinanciamiento.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=750,height=500,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert ('Debe seleccionar una Unidad Solicitante');
	}
}

function ue_buscarunidad()
{
	window.open("sigesp_scv_cat_unidadadm.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_agregardetalleasignaciones()
{
	f=document.form1;
	li_totrow=f.totalfilas.value;
	li_numdia=f.txtnumdia.value;
	ls_estsolvia=f.hidestsolvia.value;
	if(ls_estsolvia=="R")
	{
		window.open("sigesp_scv_pdt_asignaciones.php?totrow="+li_totrow+"&numdia="+li_numdia+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=250,left=50,top=50,location=no,resizable=no");
	}
	else
	{alert("La Solicitud de Viaticos debe estar en Estatus Registro para ser Modificada");}
}

function ue_agregardetallepersonal()
{
	f=document.form1;
	li_totrow=f.totalfilaspersonal.value;
	ls_estsolvia=f.hidestsolvia.value;
	if(ls_estsolvia=="R")
	{
		window.open("sigesp_scv_pdt_personal.php?totrow="+li_totrow+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=450,left=50,top=50,location=no,resizable=no");
	}
	else
	{alert("La Solicitud de Viaticos debe estar en Estatus Registro para ser Modificada");}
}

//--------------------------------------------------------
//  Funciones de las operaciones de la páginas
//--------------------------------------------------------
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	lb_cierre=f.cierre.value;
	if(li_incluir==1)
	{	
		if(lb_cierre!=false)
		{
			f.operacion.value="NUEVO";
			f.action="sigesp_scv_p_solicitudviaticos.php";
			f.submit();
		}
		else
		{
			alert("Se ha procesado el cierre presupuestario del sistema")
		}
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
	lb_status=f.hidestatus.value;
	ls_estsolvia=f.hidestsolvia.value;
	lb_cierre=f.cierre.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status=="")&&(li_incluir==1))
	{
		if(lb_cierre!=false)
		{
			if(ls_estsolvia=="R")
			{
				ls_codsolvia= f.txtcodsolvia.value;
				ls_codmis=    f.txtcodmis.value;
				ls_codrut=    f.txtcodrut.value;
				ls_coduniadm= f.txtcoduniadm.value;
				ld_fecsalvia= f.txtfecsal.value;
				ld_fecregvia= f.txtfecreg.value;
				li_totrow=    f.totalfilas.value;
				li_totrowper= f.totalfilaspersonal.value;
				if((ls_codsolvia!="")&&(ls_codmis!="")&&(ls_codrut!="")&&(ls_coduniadm!="")&&(ld_fecsalvia!="")&&(ld_fecregvia!="")&&(li_totrow>1)&&(li_totrowper>1))
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_scv_p_solicitudviaticos.php";
					f.submit();
				}
				else
				{alert("Deben completar todos los datos");}
			}
			else
			{alert("La Solicitud de Viaticos debe estar en Estatus Registro para ser Modificada");}
		}
		else
		{
			alert("Se ha procesado el cierre presupuestario del sistema")
		}
	}
	else
   	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	ls_destino="SOLICITUD";
	if (li_leer==1)
   	{
		window.open("sigesp_scv_cat_sol_via.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.incluir.value;
	ls_estsolvia=f.hidestsolvia.value;
	if (li_eliminar==1)
	{
		if(ls_estsolvia=="R")
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{	
				f.operacion.value="ELIMINAR"
				f.action="sigesp_scv_p_solicitudviaticos.php";
				f.submit();
			}
		}
		else
		{alert("La Solicitud de Viaticos debe estar en Estatus Registro para ser Modificada");}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
	
}

function uf_delete_dt_personal(li_row)
{
	f=document.form1;
	li_fila=f.totalfilaspersonal.value;
	li_eliminar=f.incluir.value;
	ls_estsolvia=f.hidestsolvia.value;
	if (li_eliminar==1)
	{
		if(li_fila!=li_row)
		{
			if(ls_estsolvia=="R")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{	
						f.filadeleteper.value=li_row;
						f.operacion.value="ELIMINARDETALLEPER"
						f.action="sigesp_scv_p_solicitudviaticos.php";
						f.submit();
				}
			}
			else
			{alert("La Solicitud de Viaticos debe estar en Estatus Registro para ser Modificada");}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_delete_dt_asignaciones(li_row)
{
	f=document.form1;
	li_fila=f.totalfilas.value;
	li_eliminar=f.incluir.value;
	ls_estsolvia=f.hidestsolvia.value;
	if (li_eliminar==1)
	{
		if(li_fila!=li_row)
		{
			if(ls_estsolvia=="R")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{	
					f.filadelete.value=li_row;
					f.operacion.value="ELIMINARDETALLEASIG"
					f.action="sigesp_scv_p_solicitudviaticos.php";
					f.submit();
				}
			}
			else
			{alert("La Solicitud de Viaticos debe estar en Estatus Registro para ser Modificada");}
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
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		formato=f.formato.value;
		ls_estatus=  f.hidestatus.value;
		if(ls_estatus=="C")
		{
			ls_codsolvia= f.txtcodsolvia.value;
			ld_fecsolvia= f.txtfecsolvia.value;
			ls_tiporeporte=f.cmbbsf.value;
			window.open("reportes/"+formato+"?codsolvia="+ls_codsolvia+"&fecsolvia="+ld_fecsolvia+"&tiporeporte="+ls_tiporeporte+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("Debe existir un documento a imprimir");
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

////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
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

//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
//--------------------------------------------------------
/*var patron = new Array(2,2,4)
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
*/
function ue_verificar()
{
	f=document.form1;
	numdias= f.txtnumdia.value;
	if(numdias<0)
	{
		alert ("La fecha de Retorno no puede ser menor que la de Salida");
		f.txtnumdia.value="";
		f.txtfecreg.value="";
	}

}


function DiferenciaFechas ()
{
    //Obtiene los datos del formulario
	f=document.form1;
    CadenaFecha1 = f.txtfecreg.value;
    CadenaFecha2 = f.txtfecsal.value;
	numdias= f.txtnumdia.value;
	if((CadenaFecha1!="")&&(CadenaFecha2!=""))
	{
		//Obtiene dia, mes y año
		var fecha1 = new fecha( CadenaFecha1 )   
		var fecha2 = new fecha( CadenaFecha2 )
		
		//Obtiene objetos Date
		var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia )
		var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia )
	
		//Resta fechas y redondea
		var diferencia = miFecha1.getTime() - miFecha2.getTime()
		var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24))
		dias=(parseFloat(dias) + 1);
	    f.txtnumdia.value=dias+",00";
		if(dias<0)
		{
			f.txtnumdia.value="";
			f.txtfecreg.value="";
		} 
		else
		{
			ls_obssolvia=f.txtobssolvia.value;
		}
    	return false
	}
}
function ue_recogerfecha()
{
	f=document.form1;
	ls_obssolvia=f.txtobssolvia.value;
	if(f.fechaok.value==0)
	{
		CadenaFecha1 = f.txtfecreg.value;
		CadenaFecha2 = f.txtfecsal.value;
		if((CadenaFecha1!="")&&(CadenaFecha2!=""))
		{
			if(ls_obssolvia=="")
			{
				f.txtobssolvia.value= "Fecha Salida: "+CadenaFecha2+" Fecha Retorno: "+CadenaFecha1;
			}
			else
			{
				f.txtobssolvia.value= ls_obssolvia+", Fecha Salida: "+CadenaFecha2+" Fecha Retorno: "+CadenaFecha1;
			}
			f.fechaok.value=1;
		}
	}
}
function fecha( cadena ) {

    //Separador para la introduccion de las fechas
    var separador = "/"

    //Separa por dia, mes y año
    if ( cadena.indexOf( separador ) != -1 ) {
         var posi1 = 0
         var posi2 = cadena.indexOf( separador, posi1 + 1 )
         var posi3 = cadena.indexOf( separador, posi2 + 1 )
         this.dia = cadena.substring( posi1, posi2 )
         this.mes = cadena.substring( posi2 + 1, posi3 )
		 this.mes= this.mes-1;
         this.anio = cadena.substring( posi3 + 1, cadena.length )
    } else {
         this.dia = 0
         this.mes = 0
         this.anio = 0   
    }
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>