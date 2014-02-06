<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

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
   		global $ls_cmpmov,$ls_codres,$ls_codresnew,$ls_nomres,$ls_nomresnew,$ls_obstra,$ld_fectraact;
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$li_totrows;
		
		$ls_cmpmov="";
		$ls_codres="";
		$ls_codresnew="";
		$ls_nomres="";
		$ls_nomresnew="";
		$ls_obstra="";
		$ld_fectraact= date("d/m/Y");
		
		$ls_titletable="Detalle del Traslado de Activo";
		$li_widthtable=820;
		$ls_nametable="grid";
		$lo_title[1]="Fecha";
		$lo_title[2]="Activo";
		$lo_title[3]="Serial";
		$lo_title[4]="Observación";
		$lo_title[5]="Unidad Ant.";
		$lo_title[6]="Responsable Actual";
		$lo_title[7]="Unidad Nueva";
		$lo_title[8]="Responsable Nuevo";
		$lo_title[9]="";
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
		$aa_object[$ai_totrows][1]="<input name=txtfectraact".$ai_totrows." type=text id=txtfectraact".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtcodact".$ai_totrows." type=text id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtidact".$ai_totrows." type=text id=txtidact".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
		$aa_object[$ai_totrows][4]="<input name=txtobstraact".$ai_totrows." type=text id=txtobstraact".$ai_totrows." class=sin-borde size=40 readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtcoduniadm".$ai_totrows." type=text id=txtcoduniadm".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtcodres".$ai_totrows." type=text id=txtcodres".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][7]="<input name=txtcoduniadmnew".$ai_totrows." type=text id=txtcoduniadmnew".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][8]="<input name=txtcodresnew".$ai_totrows." type=text id=txtcodresnew".$ai_totrows." class=sin-borde size=12 maxlength=10 readonly>";
		$aa_object[$ai_totrows][9]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Traslados de Activos </title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("sigesp_saf_c_traslado.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	$io_fundb= new class_funciones_db($con);
	$io_sql=  new class_sql($con);
	$io_fun= new class_funciones();
	$io_saf= new sigesp_saf_c_traslado();
	$io_msg= new class_mensajes();

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SAF";
	$ls_ventanas="sigesp_saf_p_traslado.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_count=count($arr);


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
			
			$ls_emp="";
			$ls_codemp="";
			$ls_tabla="saf_traslado";
			$ls_columna="cmpmov";
		
			$ls_cmpmov=$io_fundb->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			uf_agregarlineablanca($lo_object,$li_totrows);
		break;
		
		case "AGREGARDETALLE":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$li_totrows=$li_totrows+1;
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ld_fectraact=$_POST["txtfectraact"];
			$ls_obstra=$_POST["txtobstra"];
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codact=    $_POST["txtcodact".$li_i];
				$ld_fectraact= $_POST["txtfectraact".$li_i];
				$ls_idact=     $_POST["txtidact".$li_i];
				$ls_obstraact= $_POST["txtobstraact".$li_i];
				$ls_coduniadm= $_POST["txtcoduniadm".$li_i];
				$ls_codres=    $_POST["txtcodres".$li_i];
				$ls_coduniadmnew= $_POST["txtcoduniadmnew".$li_i];
				$ls_codresnew= $_POST["txtcodresnew".$li_i];
				
				$lo_object[$li_i][1]="<input name=txtfectraact".$li_i." type=text id=txtfectraact".$li_i." class=sin-borde size=12 maxlength=10 value='".$ld_fectraact."' readonly>";
				$lo_object[$li_i][2]="<input name=txtcodact".$li_i."    type=text id=txtcodact".$li_i."    class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
				$lo_object[$li_i][3]="<input name=txtidact".$li_i."     type=text id=txtidact".$li_i."     class=sin-borde size=17 maxlength=15 value='".$ls_idact."' readonly>";
				$lo_object[$li_i][4]="<input name=txtobstraact".$li_i." type=text id=txtobstraact".$li_i." class=sin-borde size=40 value='".$ls_obstraact."' readonly>";
				$lo_object[$li_i][5]="<input name=txtcoduniadm".$li_i." type=text id=txtcoduniadm".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadm."' readonly>";
				$lo_object[$li_i][6]="<input name=txtcodres".$li_i."    type=text id=txtcodres".$li_i."    class=sin-borde size=12 maxlength=10 value='".$ls_codres."' readonly>";
				$lo_object[$li_i][7]="<input name=txtcoduniadmnew".$li_i." type=text id=txtcoduniadmnew".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadmnew."' readonly>";
				$lo_object[$li_i][8]="<input name=txtcodresnew".$li_i." type=text id=txtcodresnew".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_codresnew."' readonly>";
				$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

			}	
			uf_agregarlineablanca($lo_object,$li_totrows);

		break;
		case "GUARDAR":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_codusureg=$_SESSION["la_logusr"];
			$ls_cmpmov=$_POST["txtcmpmov"];
			$ld_fectraact=$_POST["txtfectraact"];
			$ls_obstra=$_POST["txtobstra"];
			$ld_fectraactbd=$io_fun->uf_convertirdatetobd($ld_fectraact);
			$lb_existe=$io_saf->uf_saf_select_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd);
			if(!$lb_existe)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_saf->uf_saf_insert_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd,$ls_obstra,$ls_codusureg,$la_seguridad);
				if($lb_valido)
				{
					for($li_i=1;$li_i<$li_totrows;$li_i++)
					{
						$ls_codact=    $_POST["txtcodact".$li_i];
						$ld_fectraact= $_POST["txtfectraact".$li_i];
						$ls_idact=     $_POST["txtidact".$li_i];
						$ls_obstraact= $_POST["txtobstraact".$li_i];
						$ls_coduniadm= $_POST["txtcoduniadm".$li_i];
						$ls_codres=    $_POST["txtcodres".$li_i];
						$ls_coduniadmnew= $_POST["txtcoduniadmnew".$li_i];
						$ls_codresnew= $_POST["txtcodresnew".$li_i];
						
						$lb_valido=$io_saf->uf_saf_insert_dt_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd,$ls_codact,$ls_idact,$ls_obstraact,$ls_coduniadm,$ls_codres,$ls_coduniadmnew,$ls_codresnew,$la_seguridad);
						if($lb_valido)
						{
							$lb_valido=$io_saf->uf_saf_update_dta($ls_codemp,$ls_codact,$ls_idact,$ls_codresnew,$ls_coduniadmnew,$la_seguridad);
						}
					}				
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El registro fue incluido con exito");
					uf_agregarlineablanca($lo_object,1);
					uf_limpiarvariables();
					$li_totrows=1;
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo incluir el registro");
					uf_agregarlineablanca($lo_object,1);
				}
			}
			else
			{
				$io_msg->message("El numero comprobante ya existe");
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$ls_codact=    $_POST["txtcodact".$li_i];
					$ld_fectraact= $_POST["txtfectraact".$li_i];
					$ls_idact=     $_POST["txtidact".$li_i];
					$ls_obstraact= $_POST["txtobstraact".$li_i];
					$ls_coduniadm= $_POST["txtcoduniadm".$li_i];
					$ls_codres=    $_POST["txtcodres".$li_i];
					$ls_coduniadmnew= $_POST["txtcoduniadmnew".$li_i];
					$ls_codresnew= $_POST["txtcodresnew".$li_i];

					$lo_object[$li_i][1]="<input name=txtfectraact".$li_i." type=text id=txtfectraact".$li_i." class=sin-borde size=12 maxlength=10 value='".$ld_fectraact."' readonly>";
					$lo_object[$li_i][2]="<input name=txtcodact".$li_i."    type=text id=txtcodact".$li_i."    class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
					$lo_object[$li_i][3]="<input name=txtidact".$li_i."     type=text id=txtidact".$li_i."     class=sin-borde size=17 maxlength=15 value='".$ls_idact."' readonly>";
					$lo_object[$li_i][4]="<input name=txtobstraact".$li_i." type=text id=txtobstraact".$li_i." class=sin-borde size=40 value='".$ls_obstraact."' readonly>";
					$lo_object[$li_i][5]="<input name=txtcoduniadm".$li_i." type=text id=txtcoduniadm".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadm."' readonly>";
					$lo_object[$li_i][6]="<input name=txtcodres".$li_i."    type=text id=txtcodres".$li_i."    class=sin-borde size=12 maxlength=10 value='".$ls_codres."' readonly>";
					$lo_object[$li_i][7]="<input name=txtcoduniadmnew".$li_i." type=text id=txtcoduniadmnew".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadmnew."' readonly>";
					$lo_object[$li_i][8]="<input name=txtcodresnew".$li_i." type=text id=txtcodresnew".$li_i." class=sin-borde size=12 maxlength=10 value='".$ls_codresnew."' readonly>";
					$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
					
				}

			}
			
		
		break;

		case "ELIMINARDETALLE":
			uf_limpiarvariables();
			$li_totrows = uf_obtenervalor("totalfilas",1);
			$ls_cmpmov=   $_POST["txtcmpmov"];
			$ld_fectraact=$_POST["txtfectraact"];
			$ls_obstra=   $_POST["txtobstra"];
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codact=    $_POST["txtcodact".$li_i];
					$ld_fectraact= $_POST["txtfectraact".$li_i];
					$ls_idact=     $_POST["txtidact".$li_i];
					$ls_obstraact= $_POST["txtobstraact".$li_i];
					$ls_coduniadm= $_POST["txtcoduniadm".$li_i];
					$ls_codres=    $_POST["txtcodres".$li_i];
					$ls_coduniadmnew= $_POST["txtcoduniadmnew".$li_i];
					$ls_codresnew= $_POST["txtcodresnew".$li_i];
					$lo_object[$li_temp][1]="<input name=txtfectraact".$li_temp." type=text id=txtfectraact".$li_temp." class=sin-borde size=12 maxlength=10 value='".$ld_fectraact."' readonly>";
					$lo_object[$li_temp][2]="<input name=txtcodact".$li_temp."    type=text id=txtcodact".$li_temp."    class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtidact".$li_temp."     type=text id=txtidact".$li_temp."     class=sin-borde size=17 maxlength=15 value='".$ls_idact."' readonly>";
					$lo_object[$li_temp][4]="<input name=txtobstraact".$li_temp." type=text id=txtobstraact".$li_temp." class=sin-borde size=40 value='".$ls_obstraact."' readonly>";
					$lo_object[$li_temp][5]="<input name=txtcoduniadm".$li_temp." type=text id=txtcoduniadm".$li_temp." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadm."' readonly>";
					$lo_object[$li_temp][6]="<input name=txtcodres".$li_temp."    type=text id=txtcodres".$li_temp."    class=sin-borde size=12 maxlength=10 value='".$ls_codres."' readonly>";
					$lo_object[$li_temp][7]="<input name=txtcoduniadmnew".$li_temp." type=text id=txtcoduniadmnew".$li_temp." class=sin-borde size=12 maxlength=10 value='".$ls_coduniadmnew."' readonly>";
					$lo_object[$li_temp][8]="<input name=txtcodresnew".$li_temp." type=text id=txtcodresnew".$li_temp." class=sin-borde size=12 maxlength=10 value='".$ls_codresnew."' readonly>";
					$lo_object[$li_temp][9]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
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
			$ls_cmpmov=   $_POST["txtcmpmov"];
			$ld_fectraact=$_POST["txtfectraact"];
			$ls_obstra=   $_POST["txtobstra"];
			$ld_fectraactbd=$io_fun->uf_convertirdatetobd($ld_fectraact);
			$lb_valido=$io_saf->uf_siv_load_dt_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd,$li_totrows,$lo_object);
		break;
		case "ELIMINAR":
			$ls_cmpmov=   $_POST["txtcmpmov"];
			$ld_fectraact=$_POST["txtfectraact"];
			$ls_obstra=   $_POST["txtobstra"];
			$lb_valido=false;
			$ld_fectraactbd=$io_fun->uf_convertirdatetobd($ld_fectraact);
			$io_sql->begin_transaction();
			$lb_existe=$io_saf->uf_saf_select_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd);
			if($lb_existe)
			{
				for($li_i=1;$li_i<=$li_totrows;$li_i++)
				{
					$ls_codact=    $_POST["txtcodact".$li_i];
					//$ld_fectraact= $_POST["txtfectraact".$li_i];
					$ls_ideact=     $_POST["txtidact".$li_i];
					//$ls_obstraact= $_POST["txtobstraact".$li_i];
					$ls_coduniadm= $_POST["txtcoduniadm".$li_i];
					$ls_codres=    $_POST["txtcodres".$li_i];
					//$ls_coduniadmnew= $_POST["txtcoduniadmnew".$li_i];
					//$ls_codresnew= $_POST["txtcodresnew".$li_i];
					
					$lb_valido=$io_saf->uf_saf_select_dt_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd,$ls_codact,$ls_ideact,$ls_codres,$ls_coduniadm);
					if($lb_valido)
					{
						$lb_valido=$io_saf->uf_saf_update_dta($ls_codemp,$ls_codact,$ls_ideact,$ls_codres,$ls_coduniadm,$la_seguridad);
						if($lb_valido)
						{
							$lb_valido=$io_saf->uf_saf_delete_dt_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd,$ls_codact,$ls_ideact,$la_seguridad);
						}
					}
				} // end for
				if($lb_valido)
				{
					$lb_valido=$io_saf->uf_saf_delete_traslado($ls_codemp,$ls_cmpmov,$ld_fectraactbd,$la_seguridad);
				}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El registro fue eliminado con exito");
					uf_agregarlineablanca($lo_object,1);
					uf_limpiarvariables();
					$li_totrows=1;
				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo eliminar el registro");
					uf_limpiarvariables();
					uf_agregarlineablanca($lo_object,1);
				}
			}
			else
			{
				$io_msg->message("El numero de comprobante no existe");
				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,1);
			}
			
		break;

	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="">
    <table width="827" height="159" border="0" class="formato-blanco">
      <tr>
        <td ><div align="left">
            <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="739" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="3" class="titulo-ventana">Traslados de Activos </td>
              </tr>
              <tr class="formato-blanco">
                <td width="91" height="19">&nbsp;</td>
                <td width="552" align="right">Fecha</td>
                <td width="94" align="left">
                  <input name="txtfectraact" type="text" id="txtfectraact" style="text-align:center " value="<?php print $ld_fectraact ?>" size="13" maxlength="10">
                </td>
              </tr>
              <tr class="formato-blanco">
                <td height="29"><div align="right">Comprobante</div></td>
                <td height="29" colspan="2">
                  <input name="txtcmpmov" type="text" id="txtcmpmov" value="<?php print $ls_cmpmov ?>" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center ">
                  <input name="hidstatus" type="hidden" id="hidstatus"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right">Observaciones</div></td>
                <td colspan="2" rowspan="2"><textarea name="txtobstra" cols="60" rows="3" id="txtobstra"><?php print $ls_obstra ?></textarea></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right"></div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28" colspan="3"><a href="javascript: ue_agregardetalle();"><img src="../shared/imagebank/tools/nuevo.gif" width="15" height="15" class="sin-borde">Agregar Detalle del Traslado </a></td>
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
function ue_buscar()
{
	window.open("sigesp_saf_cat_traslado.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		window.open("sigesp_saf_pdt_traslado.php?totrow="+ li_totrow +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_catapersonalnew()
{
	f=document.form1;
	codres=f.txtcodres.value;
	if(codres=="")
	{
		alert("Debe seleccionar el responsable actual");
	}
	else
	{
		window.open("sigesp_cat_personalnuevo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_saf_p_traslado.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	li_fila=f.totalfilas.value;
	if(li_fila<=1)
	{
		alert("El traslado debe manejar al menos un activo");
	}
	else
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_p_traslado.php";
		f.submit();
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
			f.action="sigesp_saf_p_traslado.php";
			f.submit();
		}
	}
}


function ue_eliminar()
{
	if(confirm("¿Seguro desea eliminar el Registro?"))
	{
		f=document.form1;
		f.operacion.value="ELIMINAR";
		f.action="sigesp_saf_p_traslado.php";
		f.submit();
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if(texto != "'")
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9")||(texto=="."))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que rellena un campo con ceros a la izquierda
//--------------------------------------------------------
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	
	total=0;
    auxiliar=valor.value;
	longitud=valor.value.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor.value = auxiliar;
	}
}

</script> 
</html>