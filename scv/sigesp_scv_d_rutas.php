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
$io_fun_viaticos->uf_load_seguridad("SCV","sigesp_scv_d_rutas.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codrut,$ls_codpai,$ls_despai,$ls_codest,$ls_desest,$ls_codciu,$ls_desciu,$ls_desrut,$ls_estatus,$ls_existe;
		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;

		$ls_codrut="";
		$ls_codpai="058";
		$ls_despai="VENEZUELA";
		$ls_codest="";
		$ls_desest="";
		$ls_codciu="";
		$ls_desciu="";
		$ls_desrut="";
		$ls_estatus="";
		$ls_existe="FALSE";
		$ls_titletable="Ciudades Destino";
		$li_widthtable=650;
		$ls_nametable="grid";
		$lo_title[1]="País";
		$lo_title[2]="Estado";
		$lo_title[3]="Ciudad";
		$lo_title[4]="";
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
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdespaides".$ai_totrows." type=text   id=txtdespaides".$ai_totrows." class=sin-borde size=35 readonly>".
								   "<input name=txtcodpaides".$ai_totrows." type=hidden id=txtcodpaides".$ai_totrows." class=sin-borde size=17 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtdesestdes".$ai_totrows." type=text   id=txtdesestdes".$ai_totrows." class=sin-borde size=20 readonly>".
								   "<input name=txtcodestdes".$ai_totrows." type=hidden id=txtcodestdes".$ai_totrows." class=sin-borde size=17 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdesciudes".$ai_totrows." type=text   id=txtdesciudes".$ai_totrows." class=sin-borde size=35 readonly>".
								   "<input name=txtcodciudes".$ai_totrows." type=hidden id=txtcodciudes".$ai_totrows." class=sin-borde size=17 readonly>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";

   }

   function uf_repintargrid(&$aa_object,&$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_repintargrid
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar lo que esta impreso en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/10/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_fun_viaticos=new class_funciones_viaticos();
		for($li_i=1;$li_i<=$ai_totrows;$li_i++)
		{
			$ls_codpaides= $io_fun_viaticos->uf_obtenervalor("txtcodpaides".$li_i,"");
			$ls_despaides= $io_fun_viaticos->uf_obtenervalor("txtdespaides".$li_i,"");
			$ls_codestdes= $io_fun_viaticos->uf_obtenervalor("txtcodestdes".$li_i,"");
			$ls_desestdes= $io_fun_viaticos->uf_obtenervalor("txtdesestdes".$li_i,"");
			$ls_codciudes= $io_fun_viaticos->uf_obtenervalor("txtcodciudes".$li_i,"");
			$ls_desciudes= $io_fun_viaticos->uf_obtenervalor("txtdesciudes".$li_i,"");
			
			$aa_object[$li_i][1]="<input name=txtdespaides".$li_i." type=text   id=txtdespaides".$li_i." class=sin-borde size=35 value='". $ls_despaides ."' readonly>".
								 "<input name=txtcodpaides".$li_i." type=hidden id=txtcodpaides".$li_i." class=sin-borde size=17 value='". $ls_codpaides ."' readonly>";
			$aa_object[$li_i][2]="<input name=txtdesestdes".$li_i." type=text   id=txtdesestdes".$li_i." class=sin-borde size=20 value='". $ls_desestdes ."' readonly>".
								 "<input name=txtcodestdes".$li_i." type=hidden id=txtcodestdes".$li_i." class=sin-borde size=17 value='". $ls_codestdes ."' readonly>";
			$aa_object[$li_i][3]="<input name=txtdesciudes".$li_i." type=text   id=txtdesciudes".$li_i." class=sin-borde size=35 value='". $ls_desciudes ."' readonly>".
								 "<input name=txtcodciudes".$li_i." type=hidden id=txtcodciudes".$li_i." class=sin-borde size=17 value='". $ls_codciudes ."' readonly>";
			$aa_object[$li_i][4]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		return true;

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
<title >Definici&oacute;n de Rutas</title>
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
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="22"><div align="center"></div></td>
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
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("class_folder/sigesp_scv_c_rutas.php");
	$io_scv= new sigesp_scv_c_rutas($con);
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_operacion=$io_fun_viaticos->uf_obtenervalor("operacion","NUEVO");
	uf_limpiarvariables();
	if(empty($ls_operacion))
	{
		uf_agregarlineablanca($lo_object,$li_totrows);
	}
	$lb_empresa= true;
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_codrut= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_rutas','codrut');
			if(empty($ls_codrut))
			{
				$io_msg->message($io_fundb->is_msg_error);
			}
			uf_agregarlineablanca($lo_object,1);
		break;
		
		case "GUARDAR":
			$ls_codrut=  $io_fun_viaticos->uf_obtenervalor("txtcodrut","");
			$ls_codpai=  $io_fun_viaticos->uf_obtenervalor("txtcodpai","");
			$ls_despai=  $io_fun_viaticos->uf_obtenervalor("txtdespai","");
			$ls_codest=  $io_fun_viaticos->uf_obtenervalor("txtcodest","");
			$ls_desest=  $io_fun_viaticos->uf_obtenervalor("txtdesest","");
			$ls_codciu=  $io_fun_viaticos->uf_obtenervalor("txtcodciu","");
			$ls_desciu=  $io_fun_viaticos->uf_obtenervalor("txtdesciu","");
			$ls_desrut=  $io_fun_viaticos->uf_obtenervalor("txtdesrut","");
			$ls_estatus= $io_fun_viaticos->uf_obtenervalor("hidestatus","");
			$li_totrows= $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codpaides= $io_fun_viaticos->uf_obtenervalor("txtcodpaides".$li_i,"");
				$ls_despaides= $io_fun_viaticos->uf_obtenervalor("txtdespaides".$li_i,"");
				$ls_codestdes= $io_fun_viaticos->uf_obtenervalor("txtcodestdes".$li_i,"");
				$ls_desestdes= $io_fun_viaticos->uf_obtenervalor("txtdesestdes".$li_i,"");
				$ls_codciudes= $io_fun_viaticos->uf_obtenervalor("txtcodciudes".$li_i,"");
				$ls_desciudes= $io_fun_viaticos->uf_obtenervalor("txtdesciudes".$li_i,"");
				if($ls_existe=="FALSE")
				{
					$lb_valido=$io_scv->uf_scv_insert_rutas($ls_codemp,$ls_codrut,$ls_codpai,$ls_codest,$ls_codciu,
															$ls_codpaides,$ls_codestdes,$ls_codciudes,$ls_desrut,$la_seguridad,'1');
					if(!$lb_valido)
					{
						break;
					}
				}
				else
				{
					$lb_valido=$io_scv->uf_scv_update_rutas($ls_codemp,$ls_codrut,$ls_desrut,$la_seguridad);
					if(!$lb_valido)
					{
						break;
					}
				}
			}			
			if($lb_valido)
			{
				$io_sql->commit();
				$io_msg->message("La Ruta ha sido procesada");
				$ls_estatus="C";
			}
			else
			{
				$io_sql->rollback();
				$io_msg->message("No se pudo procesar la Ruta");
			}
			$li_totrows=1;
			uf_limpiarvariables();
			$ls_codrut= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_rutas','codrut');
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
		case "ELIMINAR":
			$ls_codrut=  $io_fun_viaticos->uf_obtenervalor("txtcodrut","");
			$ls_codpai=  $io_fun_viaticos->uf_obtenervalor("txtcodpai","");
			$ls_despai=  $io_fun_viaticos->uf_obtenervalor("txtdespai","");
			$ls_codest=  $io_fun_viaticos->uf_obtenervalor("txtcodest","");
			$ls_desest=  $io_fun_viaticos->uf_obtenervalor("txtdesest","");
			$ls_codciu=  $io_fun_viaticos->uf_obtenervalor("txtcodciu","");
			$ls_desciu=  $io_fun_viaticos->uf_obtenervalor("txtdesciu","");
			$ls_desrut=  $io_fun_viaticos->uf_obtenervalor("txtdesrut","");
			$ls_estatus= $io_fun_viaticos->uf_obtenervalor("hidestatus","");
			$li_totrows= $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			$lb_existe=$io_scv->uf_check_existe($ls_codemp,$ls_codrut);
			if($lb_existe)
			{
				$lb_valido= $io_scv->uf_scv_delete_ruta($ls_codemp,$ls_codrut,$la_seguridad);
				if($lb_valido)
				{
					$io_msg->message("La Ruta ha sido eliminada");
					$li_totrows=1;
					uf_limpiarvariables();
					$ls_codrut= $io_fundb->uf_generar_codigo($lb_empresa,$ls_codemp,'scv_rutas','codrut');
					uf_agregarlineablanca($lo_object,$li_totrows);
				}
				else
				{
					$io_msg->message($io_scv->is_msg_error);
					$lb_valido=uf_repintargrid($lo_object,$li_totrows);
				}
			}
			else
			{
				$io_msg->message("La Ruta no existe");
			}			
		break;
		case "ELIMINARDETALLE":
			$ls_codrut=    $io_fun_viaticos->uf_obtenervalor("txtcodrut","");
			$ls_codpai=    $io_fun_viaticos->uf_obtenervalor("txtcodpai","");
			$ls_despai=    $io_fun_viaticos->uf_obtenervalor("txtdespai","");
			$ls_codest=    $io_fun_viaticos->uf_obtenervalor("txtcodest","");
			$ls_desest=    $io_fun_viaticos->uf_obtenervalor("txtdesest","");
			$ls_codciu=    $io_fun_viaticos->uf_obtenervalor("txtcodciu","");
			$ls_desciu=    $io_fun_viaticos->uf_obtenervalor("txtdesciu","");
			$ls_desrut=    $io_fun_viaticos->uf_obtenervalor("txtdesrut","");
			$li_totrows=   $io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$li_rowdelete= $io_fun_viaticos->uf_obtenervalor("filadelete","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			$ls_estatus=   "";
			$li_temp=0;
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codpaides= $io_fun_viaticos->uf_obtenervalor("txtcodpaides".$li_i,"");
				$ls_despaides= $io_fun_viaticos->uf_obtenervalor("txtdespaides".$li_i,"");
				$ls_codestdes= $io_fun_viaticos->uf_obtenervalor("txtcodestdes".$li_i,"");
				$ls_desestdes= $io_fun_viaticos->uf_obtenervalor("txtdesestdes".$li_i,"");
				$ls_codciudes= $io_fun_viaticos->uf_obtenervalor("txtcodciudes".$li_i,"");
				$ls_desciudes= $io_fun_viaticos->uf_obtenervalor("txtdesciudes".$li_i,"");
				if($li_i!=$li_rowdelete)
				{		
					$li_temp++;
					$lo_object[$li_temp][1]="<input name=txtdespaides".$li_temp." type=text   id=txtdespaides".$li_temp." class=sin-borde size=35 value='". $ls_despaides ."' readonly>".
										 	"<input name=txtcodpaides".$li_temp." type=hidden id=txtcodpaides".$li_temp." class=sin-borde size=17 value='". $ls_codpaides ."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtdesestdes".$li_temp." type=text   id=txtdesestdes".$li_temp." class=sin-borde size=20 value='". $ls_desestdes ."' readonly>".
										 	"<input name=txtcodestdes".$li_temp." type=hidden id=txtcodestdes".$li_temp." class=sin-borde size=17 value='". $ls_codestdes ."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtdesciudes".$li_temp." type=text   id=txtdesciudes".$li_temp." class=sin-borde size=35 value='". $ls_desciudes ."' readonly>".
										 	"<input name=txtcodciudes".$li_temp." type=hidden id=txtcodciudes".$li_temp." class=sin-borde size=17 value='". $ls_codciudes ."' readonly>";
					$lo_object[$li_temp][4]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
				}
				else
				{
					$lb_valido=$io_scv-> uf_scv_delete_destinos($ls_codemp,$ls_codrut,$ls_codpai,$ls_codest,$ls_codciu,
																$ls_codpaides,$ls_codestdes,$ls_codciudes,$la_seguridad);
					if($lb_valido)
					{
						$ls_desrut=str_replace(" - ".$ls_desciudes,"",$ls_desrut);
						$ls_desrut=str_replace(" - ".$ls_desciu,"",$ls_desrut);
						$lb_valido=$io_scv->uf_scv_update_rutas($ls_codemp,$ls_codrut,$ls_desrut,$la_seguridad);
					}
					if($lb_valido)
					{
						$io_msg->message("La ciudad destino ha sido eliminada");
						$li_totrows++;
						uf_agregarlineablanca($lo_object,$li_totrows);
					}
				}
			}
			if($lb_valido)
			{
				$li_totrows=$li_temp;
			}
		break;
		
		case "BUSCARDETALLE":
			$ls_codrut=  $io_fun_viaticos->uf_obtenervalor("txtcodrut","");
			$ls_codpai=  $io_fun_viaticos->uf_obtenervalor("txtcodpai","");
			$ls_despai=  $io_fun_viaticos->uf_obtenervalor("txtdespai","");
			$ls_codest=  $io_fun_viaticos->uf_obtenervalor("txtcodest","");
			$ls_desest=  $io_fun_viaticos->uf_obtenervalor("txtdesest","");
			$ls_codciu=  $io_fun_viaticos->uf_obtenervalor("txtcodciu","");
			$ls_desciu=  $io_fun_viaticos->uf_obtenervalor("txtdesciu","");
			$ls_desrut=  $io_fun_viaticos->uf_obtenervalor("txtdesrut","");
			$ls_estatus= $io_fun_viaticos->uf_obtenervalor("hidestatus","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			$li_totrows=0;
			$lb_valido=$io_scv->uf_scv_load_rutas($ls_codemp,$ls_codrut,$ls_codpai,$ls_codest,$ls_codciu,$li_totrows,$lo_object);
			$li_totrows++;
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		case "AGREGARDETALLE":
			$ls_codrut=  $io_fun_viaticos->uf_obtenervalor("txtcodrut","");
			$ls_codpai=  $io_fun_viaticos->uf_obtenervalor("txtcodpai","");
			$ls_despai=  $io_fun_viaticos->uf_obtenervalor("txtdespai","");
			$ls_codest=  $io_fun_viaticos->uf_obtenervalor("txtcodest","");
			$ls_desest=  $io_fun_viaticos->uf_obtenervalor("txtdesest","");
			$ls_codciu=  $io_fun_viaticos->uf_obtenervalor("txtcodciu","");
			$ls_desciu=  $io_fun_viaticos->uf_obtenervalor("txtdesciu","");
			$ls_desrut=  $io_fun_viaticos->uf_obtenervalor("txtdesrut","");
			$ls_existe=$io_fun_viaticos->uf_obtenervalor("existe","FALSE");
			$ls_estatus= "";
			$ls_desrut=str_replace(" - ".$ls_desciu,"",$ls_desrut);
			$li_totrows=$io_fun_viaticos->uf_obtenervalor("totalfilas","");
			$lb_valido=uf_repintargrid($lo_object,$li_totrows);
			if($lb_valido)
			{
				$li_totrows++;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
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
  <table width="667" height="43" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="691" height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="13"><div align="center">
        <table width="611" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="22" colspan="2" class="titulo-celda">Definici&oacute;n de Rutas </td>
            </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td width="413"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus;  ?>">
              <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;  ?>">
			  <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">C&oacute;digo</div></td>
            <td><div align="left">
              <input name="txtcodrut" type="text" id="txtcodrut" style="text-align:center" value="<?php print $ls_codrut;?>" size="10" readonly>
            </div></td>
          </tr>
          <tr>
            <td width="198" height="22"><div align="right">Pa&iacute;s Origen</div></td>
            <td><div align="left">
              <input name="txtcodpai" type="text" id="txtcodpai" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codpai; ?>" size="8" maxlength="3" readonly style="text-align:center ">
              <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdespai" type="text" class="sin-borde" id="txtdespai"  value="<?php print $ls_despai; ?>" size="45" readonly>
            </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Estado Origen</div></td>
            <td><div align="left">
              <input name="txtcodest" type="text" id="txtcodest" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codest; ?>" size="8" maxlength="3" readonly style="text-align:center ">
              <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesest" type="text" class="sin-borde" id="txtdesest"  value="<?php print $ls_desest; ?>" size="40" readonly>
            </div></td>
          </tr>
          <tr>
            <td height="22"><div align="right"> Ciudad Origen</div></td>
            <td><div align="left">
              <input name="txtcodciu" type="text" id="txtcodciu" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codciu; ?>" size="8" maxlength="3" readonly style="text-align:center ">
              <a href="javascript: ue_buscarciudad();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesciu" type="text" class="sin-borde" id="txtdesciu"  value="<?php print $ls_desciu; ?>" size="40" readonly>
            </div></td>
          </tr>
          <tr>
            <td><div align="right">Descripci&oacute;n </div></td>
            <td height="22"><div align="left">
              <input name="txtdesrut" type="text" id="txtdesrut" value="<?php print $ls_desrut; ?>" size="70" readonly>
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><a href="javascript: ue_agregardetalle();"><img src="../shared/imagebank/tools20/nuevo.gif" width="20" height="20" class="sin-borde">Agregar Destinos</a> </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><div align="center">
              <?php
		$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	?>
            </div></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows ?>">
              <input name="filadelete" type="hidden" id="filadelete"></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="15">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<div align="center"></div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
function ue_buscarpais()
{
	f=document.form1;
	window.open("sigesp_scv_cat_pais.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	f.txtcodest.value="";
	f.txtdesest.value="";
	f.txtcodciu.value="";
	f.txtdesciu.value="";
}

function ue_buscarestado()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("sigesp_scv_cat_estados.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
	f.txtcodciu.value="";
	f.txtdesciu.value="";

}

function ue_buscarciudad()
{
	f=document.form1;
	ls_codpai=ue_validarvacio(f.txtcodpai.value);
	ls_codest=ue_validarvacio(f.txtcodest.value);
	ls_destino="RUTAS";
	if((ls_codpai!="")||(ls_codest!=""))
	{
		window.open("sigesp_scv_cat_ciudades.php?hidpais="+ls_codpai+"&hidestado="+ls_codest+"&destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_agregardetalle()
{
	f=document.form1;
	li_totrow=f.totalfilas.value;
	ls_ciudad=f.txtcodciu.value;
	if(ls_ciudad!="")
	{
		window.open("sigesp_scv_pdt_rutas.php?totrow="+li_totrow+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe indicar la ciudad de origen");
	}
}

//--------------------------------------------------------
//  Funciones de las operaciones de la páginas
//--------------------------------------------------------
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_scv_d_rutas.php";
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
	lb_status=f.hidestatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status=="")&&(li_incluir==1))
	{
		ls_codpai=f.txtcodpai.value;
		ls_codest=f.txtcodest.value;
		ls_codciu=f.txtcodciu.value;
		ls_desciu=f.txtdesciu.value;
		li_totrow=f.totalfilas.value;
		if((ls_codpai!="")&&(ls_codest!="")&&(ls_codciu!="")&&(li_totrow>1))
		{
			ls_desrut=f.txtdesrut.value;
			if(lb_status!="C")
			{
				f.txtdesrut.value=ls_desrut+" - "+ls_desciu;
			}
			f.operacion.value="GUARDAR";
			f.action="sigesp_scv_d_rutas.php";
			f.submit();
		}
		else
		{alert("Deben existir una ciudad origen y al menos una destino");}
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
	if (li_leer==1)
   	{
		ls_destino="DEFINICION"
		window.open("sigesp_scv_cat_rutas.php?destino="+ls_destino+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=400,left=50,top=50,location=no,resizable=yes");
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
	if (li_eliminar==1)
	{
		if(confirm("¿Desea eliminar el Registro actual?"))
		{	
			f.operacion.value="ELIMINAR"
			f.action="sigesp_scv_d_rutas.php";
			f.submit();
		}
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
	li_eliminar=f.incluir.value;
	if (li_eliminar==1)
	{
		if(li_fila!=li_row)
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{	
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_scv_d_rutas.php";
				f.submit();
			}
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
//	Función que obtiene el valor de el radio button
//--------------------------------------------------------
   function actualizaValor(oRad)
   { 
    var i 
	f=document.form1;
    for (i=0;i<f.radiotipo.length;i++)
	{ 
       if (f.radiotipo[i].checked) 
          break; 
    } 
    valor= i;
	f.hidradio.value=i;
   } 
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>