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
require_once("class_funciones_inventario.php");
$io_fun_inventario= new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SIV","sigesp_siv_p_recepcion.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_reporte=$io_fun_inventario->uf_select_config("SIV","REPORTE","ENTRADA_ALMACEN","sigesp_siv_rfs_recepcion.php","C");
$lb_cierrescg = $io_fun_inventario->uf_chkciescg();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_seleccionarcombo
		//         Access: private
		//      Argumento: $as_valores      // valores que puede tomar el combo				
		//                 $as_seleccionado // item seleccionado				
		//                 $aa_parametro    // arreglo de seleccionados		
		//                 $li_total        // total de elementos en el combo
		//	      Returns:
		//    Description: Funcion que mantiene la seleccion de un combo despues de hacer un submit
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/01/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }
   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 		
		//                 $ai_totrows // ultima fila pintada en el grid		
		//	      Returns:
		//    Description: Funcion que agrega una linea en blanco al final del grid cuando es una factura
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/01/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=20 maxlength=50 readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:60px '><option value=D>Detal</option><option value=M selected>Mayor</option></select></div><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."'>";
		$aa_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_articulosfactura(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 readonly>";
		$aa_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_montosfactura(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][9]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
		$aa_object[$ai_totrows][10]="<a href=javascript:uf_dt_activo(".$ai_totrows.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a><input name=hclasi".$ai_totrows." type=hidden id=hclasi".$ai_totrows." class=sin-borde size=20 maxlength=20 readonly>";
				
   }
   //--------------------------------------------------------------
   function uf_agregarlineablancaorden(&$aa_object,$ai_totrows)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancaorden
		//         Access: private
		//      Argumento: $aa_object  // arreglo de titulos 		
		//                 $ai_totrows // ultima fila pintada en el grid		
		//	      Returns:
		//    Description: Funcion que agrega una linea en blanco al final del grid cuando es una orden de compra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/01/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows."    type=text id=txtcodart".$ai_totrows."    class=sin-borde size=20 maxlength=20 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][2]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:60px '><option value=D>Detal</option><option value=M selected>Mayor</option></select></div><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."'>";
		$aa_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);' readonly>";
		$aa_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);'>";
		$aa_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
   		$aa_object[$ai_totrows][8]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";

   }
   //--------------------------------------------------------------
   function uf_pintartituloorden(&$lo_object,&$lo_title)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintartituloorden
		//         Access: private
		//      Argumento: $lo_object  // arreglo de objetos
		//				   $lo_title   // arreglo de titulos 	
		//	      Returns:
		//    Description: Función que carga las caracteristicas del grid de detalle de despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/01/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_title="";
		$lo_object="";
		$lo_title[1]="Artículo";
		$lo_title[2]="Unidad de Medida";
		$lo_title[3]="Cantidad Original";
		$lo_title[4]="Cantidad";
		$lo_title[5]="Pendiente";
		$lo_title[6]="Costo Unitario";
		$lo_title[7]="Costo Total";
		$lo_title[8]="";
   }
   //--------------------------------------------------------------
   function uf_pintardetalle($ai_totrows,$ls_estpro)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $ai_totrows    // cantidad de filas que tiene el grid
		//				   $ls_estpro     // indica que valor tiene el radiobutton 0--> Orden de compra 1--> Factura
		//				   $ls_checkedord // variable imprime o no "checked" para el radiobutton en la orden de compra
		//				   $ls_checkedfac // variable imprime o no "checked" para el radiobutton en la factura
		//	      Returns:
		//    Description: Funcion que vuelve a pintar el detalle del grid tal cual como estaba.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/02/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $lo_object;
		if($ls_estpro==0)
		{
			$ls_checkedord="checked";
			$ls_checkedfac="";
		}
		elseif($ls_estpro==1)
		{
			$ls_checkedord="";
			$ls_checkedfac="checked";
		}
		else
		{
			$ls_checkedord="";
			$ls_checkedfac="";
		}
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{	
			$la_unidad[0]="";
			$la_unidad[1]="";
			$ls_codart=    $_POST["txtcodart".$li_i];
			$ls_denart=    $_POST["txtdenart".$li_i];
			//$ls_unidad=    $_POST["txtunidad".$li_i];
			if (array_key_exists("la_logusr",$_POST))
			{
				$ls_unidad=    $_POST["txtunidad".$li_i];  
			}
			else
			{
				$ls_unidad=    "";
			}
			$li_canart=    $_POST["txtcanart".$li_i];
			$li_penart=    $_POST["txtpenart".$li_i];
			$li_preuniart= $_POST["txtpreuniart".$li_i];
			$li_canoriart= $_POST["txtcanoriart".$li_i];
			$li_montotart= $_POST["txtmontotart".$li_i];
			if($ls_estpro==1)
			{
				$ls_clasi=     $_POST["hclasi".$li_i]; 
			}
			//uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
					
			$lo_object[$li_i][1]="<input name=txtdenart".$li_i."    type=text id=txtdenart".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
								 "<input name=txtcodart".$li_i."    type=hidden id=txtcodart".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
			$lo_object[$li_i][2]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:60px '><option value=D>Detal</option><option value=M selected>Mayor</option></select></div><input name=txtunidad".$li_i."    type=text id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidad."' readonly><input name='hidunidad".$li_i."' type='hidden' id='hidunidad".$li_i."'>";
			$lo_object[$li_i][3]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."'  readonly>";
			$lo_object[$li_i][4]="<input name=txtcanart".$li_i."    type=text id=txtcanart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
			$lo_object[$li_i][5]="<input name=txtpenart".$li_i."    type=text id=txtpenart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_penart."' readonly>";
			$lo_object[$li_i][6]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
			$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
			$lo_object[$li_i][8]="";
			if($ls_estpro==1)
			{
				$lo_object[$li_i][8]="";
				$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
				$lo_object[$li_i][10]="<a href=javascript:uf_dt_activo(".$li_i.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a><input name=hclasi".$li_i." type=hidden id=hclasi".$li_i." class=sin-borde size=20 maxlength=20 value='".$ls_clasi."' readonly>";	
						
			}
  	    } 
		if($ls_estpro==1)
		{
			//uf_agregarlineablanca($lo_object,$ai_totrows+1);
			uf_agregarlineablanca($lo_object,$ai_totrows);		
		}
		else
		{
			//uf_agregarlineablancaorden($lo_object,$ai_totrows+1);
			uf_agregarlineablancaorden($lo_object,$ai_totrows);
		}
   }
  	//--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//         Access: private
		//      Argumento:  	
		//	      Returns:
		//    Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/01/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_numordcom,$ls_numconrec,$ls_numconrecmov,$ls_codpro,$ls_denpro,$ls_codalm,$ls_nomfisalm,$ld_fecrec,$ls_obsrec,$li_totentsum,$ls_status;
		global $ls_checkedord,$ls_checkedfac,$ls_checkedparc,$ls_checkedcomp,$ls_codusu,$ls_readonly,$ls_readonlyrad,$li_totrows,$ls_hidsaverev;
		
		$ls_numordcom="";
		$ls_numconrec="";
		
		//$ls_numconrecmov="";
		//$ls_codpro="";
		
		$ls_denpro="";
		$ls_codalm="";
		$ls_nomfisalm="";
		$ld_fecrec="";
		$ls_obsrec="";
		$li_totentsum="0,00";
		$ls_checkedord="";
		$ls_checkedfac="";
		$ls_checkedparc="";
		$ls_checkedcomp="";
		$ls_readonlyrad="";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_status="";
		$li_totrows=1;
		$ls_hidsaverev="false";
   }
  	//--------------------------------------------------------------
   function uf_obtenervalorunidad($li_i)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalorunidad
		//         Access: private
		//      Argumento: $li_i  //  indica que opcion esta seleccionado en el combo	
		//	      Returns: Retorna el valor obtenido
		//    Description: Función que obtiene el contenido del combo cmbunidad o del campo txtunidad deacuerdo sea el caso 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/01/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (array_key_exists("cmbunidad".$li_i,$_POST))
		{
			$ls_valor= $_POST["cmbunidad".$li_i];
		}
		else
		{
			$ls_valoraux= $_POST["txtunidad".$li_i];
			if($ls_valoraux=="Mayor")
			{
				$ls_valor="M";
			}
			else
			{
				$ls_valor="D";
			}
		}
   		return $ls_valor; 
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Entrada de Suministros a Almac&eacute;n </title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="css/siv.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
.Estilo1 {font-size: 12px}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Inventario </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir('<?php print $ls_reporte ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=  new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql= new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("sigesp_siv_c_recepcion.php");
	$io_siv= new sigesp_siv_c_recepcion();
	require_once("sigesp_siv_c_articuloxalmacen.php");
	$io_art= new sigesp_siv_c_articuloxalmacen();
	require_once("sigesp_siv_c_movimientoinventario.php");
	$io_mov= new sigesp_siv_c_movimientoinventario();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$li_totrows = $io_fun_inventario->uf_obtenervalor("totalfilas",1);
	$ls_hidsaverev = $io_fun_inventario->uf_obtenervalor("hidsaverev","");

	//$ls_emp="";
	$ls_tabla="siv_recepcion";
	$ls_columna="numconrec";
	$ls_numconrecmov=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
	if($ls_numconrecmov==false)
	{
		print "<script language=JavaScript>";
		print "location.href='sigespwindow_blank.php'";
		print "</script>";
	}

	$ls_titletable="Detalle de la Entrada";
	$li_widthtable=800;
	$ls_nametable="grid";
	$lo_title[1]="Artículo";
	$lo_title[2]="Unidad de Medida";
	$lo_title[3]="Cantidad Original";
	$lo_title[4]="Cantidad";
	$lo_title[5]="Pendiente";
	$lo_title[6]="Costo Unitario";
	$lo_title[7]="Costo Total";
	$lo_title[8]="";
	$lo_title[9]="";
	$lo_title[10]="";
	
	$ls_operacion= $io_fun_inventario->uf_obteneroperacion();
	$ls_status=    $io_fun_inventario->uf_obtenervalor("hidestatus","");
	if($ls_status=="C")
	{
		$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
		$li_catafilas= $io_fun_inventario->uf_obtenervalor("catafilas","");
	}
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$ls_status="";
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$li_totrows=1;
			$ld_fecrec=date("d/m/Y");
		break;

		case "NUEVAFACTURA":
			uf_agregarlineablanca($lo_object,1);
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_checkedord="";
			$ls_checkedfac="checked";
			$ls_checkedparc="";
			$ls_checkedcomp="checked";
			$ls_readonlyrad="onClick='return false'";
			$ld_fecrec=date("d/m/Y");
		break;
		
		case "NUEVAORDEN":
			uf_limpiarvariables();
			$li_totrows=1;
			$ls_checkedord="checked";
			$ls_checkedfac="";
			$ls_checkedparc="";
			$ls_checkedcomp="checked";
			$ls_readonlyrad="";
			$ls_readonly="readonly";
			uf_pintartituloorden($lo_object,$lo_title);
			uf_agregarlineablancaorden($lo_object,1);
			$ld_fecrec=date("d/m/Y");
		break;
		
		case "GUARDAR":
			$ls_estpro=    $io_fun_inventario->uf_obtenervalor("radiotipo","");
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			$ls_estrec=    $io_fun_inventario->uf_obtenervalor("radiotipentrega","");
			$li_totentsum= $io_fun_inventario->uf_obtenervalor("txttotentsum","");
            $ls_numconrecmov=$io_fun_inventario->uf_obtenervalor("txtnumconrecmov","");
			$ls_clasi=     $io_fun_inventario->uf_obtenervalor("hclasi","");
			$ls_limnumordcom=strlen($ls_numordcom);
			if($ls_estpro==0)
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
				$ls_codprodoc="ORD";
			}
			else
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
				$ls_codprodoc="FAC";
				if($ls_limnumordcom<15)
                {
				   $ls_numordcom="F".$ls_numordcom;
			    }
			}

			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
				$ls_readonlyrad="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
				$ls_readonlyrad="onClick='return false'";
			}
			$ls_readonly="readonly";
			$ls_numordcom=$io_func->uf_cerosizquierda($ls_numordcom,15);
			$ld_fecrecbd=$io_func->uf_formatovalidofecha($ld_fecrec);
			$ld_fecrecbd=$io_func->uf_convertirdatetobd($ld_fecrec);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrecbd);
			if($lb_valido)
			{
				if ($ls_status!="C")
				{
					$lb_encontrado=false;
					if ($lb_encontrado)
					{
						$io_msg->message("Registro ya existe"); 
						uf_pintardetalle($li_totrows+1,$ls_estpro);
					}
					else
					{	
						$ls_numconrec="";  
						$io_sql->begin_transaction();
						$lb_valido=$io_siv->uf_siv_insert_recepcion($ls_codemp,$ls_numordcom,$ls_codpro,$ls_codalm,$ld_fecrecbd,
																	$ls_obsrec,$ls_codusu,$ls_estpro,$ls_estrec,&$ls_numconrecmov,$la_seguridad);
						
						if ($lb_valido)
						{
							$ls_nummov=0;
							$ls_nomsol="Recepcion";
							$lb_valido=$io_mov->uf_siv_insert_movimiento(&$ls_nummov,$ld_fecrecbd,$ls_nomsol,$ls_codusu,
																		 $la_seguridad);
						}
						if ($lb_valido)
						{  
							if($ls_estpro==0)
							{
								$li_totrowsaux=$li_totrows+1;
							}
							else
							{
								$li_totrowsaux=$li_totrows;
							}
							for($li_i=1;$li_i<$li_totrowsaux;$li_i++)
							{ 
								$ls_unidad=    uf_obtenervalorunidad($li_i);
								$li_unidad=    $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
								$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
								$li_canart=    $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
								$li_penart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
								$li_preuniart= $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
								$li_canoriart= $io_fun_inventario->uf_obtenervalor("txtcanoriart".$li_i,"");
								$li_montotart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
								$li_monsubart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
								$ls_clasi=     $io_fun_inventario->uf_obtenervalor("hclasi".$li_i,"");
								
								$li_canart=    str_replace(".","",$li_canart);
								$li_canart=    str_replace(",",".",$li_canart);
								$li_penart=    str_replace(".","",$li_penart);
								$li_penart=    str_replace(",",".",$li_penart);
								$li_preuniart= str_replace(".","",$li_preuniart);
								$li_preuniart= str_replace(",",".",$li_preuniart);
								$li_canoriart= str_replace(".","",$li_canoriart);
								$li_canoriart= str_replace(",",".",$li_canoriart);
								$li_montotart= str_replace(".","",$li_montotart);
								$li_montotart= str_replace(",",".",$li_montotart);
								$li_monsubart= str_replace(".","",$li_monsubart);
								$li_monsubart= str_replace(",",".",$li_monsubart);
								if($li_canart=="")
								{
									$li_canart=0.00;
									$li_montotart=0.00;
									$li_monsubart=0.00;
									$li_penart=$li_canoriart;
								}
								
								if ($ls_unidad=="M")
								{
									$li_canart= ($li_canart * $li_unidad);
								}
								$lb_valido=$io_siv->uf_siv_insert_dt_recepcion($ls_codemp,$ls_numordcom,$ls_codart,$ls_unidad,$li_canart,
																			   $li_penart,$li_preuniart,$li_monsubart,$li_montotart,
																			   $li_i,$li_canoriart,$ls_numconrecmov,$la_seguridad);
								if ($lb_valido)
								{
									$lb_valido=$io_art->uf_siv_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																						 $li_canart,$la_seguridad);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$lb_valido=$io_mov->uf_siv_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecrecbd,
																						$ls_codart,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_numordcom,$li_canart,
																						$li_preuniart,$ls_promov,$ls_numconrecmov,
																						$li_canart,$ld_fecrecbd,$la_seguridad);
									}
								}
								if($lb_valido)
								{ 
									$lb_valido=$io_art->uf_siv_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$la_seguridad);
								}
							}
						}
						
						if($lb_valido)
						{
							$io_sql->commit();
							$io_msg->message("La entrada de suministros a almacén ha sido procesada");
							if($ls_estpro==0)
							{
								uf_pintartituloorden($lo_object,$lo_title);
							}
							uf_pintardetalle($li_totrowsaux,$ls_estpro);
							$ls_status="C";
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No se pudo procesar la entrada de suministros a almacén");
							uf_pintardetalle($li_totrowsaux,$ls_estpro);
						}
					}
				}
				else
				{
					$io_msg->message("La entrada de suministros a almacén no debe ser modificada");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,$li_totrows);
					//uf_limpiarvariables();
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				//uf_limpiarvariables();
			}
		break;
		
		case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			$ls_readonly="";
			$ls_radiotipo= $io_fun_inventario->uf_obtenervalor("radiotipo","");
			if($ls_radiotipo=="0")
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
			}
			if ($ls_radiotipo=="1")
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
			}
			$ls_estrec= $io_fun_inventario->uf_obtenervalor("radiotipentrega","");
			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
				$ls_readonlyrad="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
				$ls_readonlyrad="onClick='return false'";
			}

			$ls_numconrec= $io_fun_inventario->uf_obtenervalor("txtnumconrec","");
			$ls_numconrecmov= $io_fun_inventario->uf_obtenervalor("txtnumconrecmov","");
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			$li_totentsum= $io_fun_inventario->uf_obtenervalor("txttotentsum","");
			//$ls_clasi=     $io_fun_inventario->uf_obtenervalor("hclasi",""); print "valor".$ls_clasi;

			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{	
				$ls_unidad= uf_obtenervalorunidad($li_i);
				$li_unidad=    $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
				$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
				$ls_denart=    $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
				$li_canart=    $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
				$li_penart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
				$li_preuniart= $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
				$li_canoriart= $io_fun_inventario->uf_obtenervalor("txtcanoriart".$li_i,"");
				$li_montotart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
				$ls_clasi=     $io_fun_inventario->uf_obtenervalor("hclasi".$li_i,"");
				switch ($ls_unidad) 
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				if (($ls_status=="C")&&($li_i<=$li_catafilas))
				{
					
				}
				else
				{
					$lo_object[$li_i][1]="<input name=txtdenart".$li_i."    type=text id=txtdenart".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
										 "<input name=txtcodart".$li_i."    type=hidden id=txtcodart".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][2]="<input name=txtunidad".$li_i."    type=text id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$li_i."' type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
					$lo_object[$li_i][3]="<input name=txtcanoriart".$li_i." type=text id=txtcanoriart".$li_i." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' readonly>";
					$lo_object[$li_i][4]="<input name=txtcanart".$li_i."    type=text id=txtcanart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' readonly>";
					$lo_object[$li_i][5]="<input name=txtpenart".$li_i."    type=text id=txtpenart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_penart."'readonly>";
					$lo_object[$li_i][6]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
					$lo_object[$li_i][7]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
					$lo_object[$li_i][8]="";
					$lo_object[$li_i][9]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
					$lo_object[$li_i][10]="<a href=javascript:uf_dt_activo(".$li_i.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a><input name=hclasi".$li_i." type=hidden id=hclasi".$li_i." class=sin-borde size=20 maxlength=20  value='".$ls_clasi."' readonly>";	
							
				}

			}
			uf_agregarlineablanca($lo_object,$li_totrows,$ls_codart);
		break;
		
		case "ELIMINARDETALLE":
			$ls_readonly="";
			if(array_key_exists("radiotipo",$_POST))
			{
				$ls_radiotipo= $io_fun_inventario->uf_obtenervalor("radiotipo","");
				if($ls_radiotipo=="0")
				{
					$ls_checkedord="checked";
					$ls_checkedfac="";
				}
				if ($ls_radiotipo=="1")
				{
					$ls_checkedord="";
					$ls_checkedfac="checked";
				}
			}
			else
			{
					$ls_checkedord="";
					$ls_checkedfac="";
			}
			if(array_key_exists("radiotipentrega",$_POST))
			{
				$ls_estrec= $io_fun_inventario->uf_obtenervalor("radiotipentrega","");
				if($ls_estrec==0)
				{
					$ls_checkedparc="checked";
					$ls_checkedcomp="";
					$ls_readonlyrad="";
				}
				else
				{
					$ls_checkedparc="";
					$ls_checkedcomp="checked";
					$ls_readonlyrad="onClick='return false'";
				}
			}
			else
			{
					$ls_checkedparc="";
					$ls_checkedcomp="";
					$ls_readonlyrad="";
			}

			$ls_numconrec="";
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			
			$li_totrows=$li_totrows-1;
			$li_rowdelete= $io_fun_inventario->uf_obtenervalor("filadelete","");
			$li_temp=0;
			
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_unidad= uf_obtenervalorunidad($li_i);
					$li_unidad=    $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
					$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
					$ls_denart=    $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
					$li_canart=    $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
					$li_penart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
					$li_preuniart= $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
					$li_canoriart= $io_fun_inventario->uf_obtenervalor("txtcanoriart".$li_i,"");
					$li_montotart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
					uf_seleccionarcombo("D-M",$ls_unidad,$la_unidad,2);
					switch ($ls_unidad) 
					{
						case "M":
							$ls_unidadaux="Mayor";
							break;
						case "D":
							$ls_unidadaux="Detal";
							break;
					}
					$lo_object[$li_temp][1]="<input name=txtdenart".$li_temp."    type=text id=txtdenart".$li_temp."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$li_temp."    type=hidden id=txtcodart".$li_temp."    class=sin-borde size=20 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulo(".$li_temp.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_temp][2]="<input name=txtunidad".$li_temp."    type=text id=txtunidad".$li_temp."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$li_temp."' type='hidden' id='hidunidad".$li_temp."' value='". $li_unidad ."'>";
					$lo_object[$li_temp][3]="<input name=txtcanoriart".$li_temp." type=text id=txtcanoriart".$li_temp." class=sin-borde size=12 maxlength=12 value='".$li_canoriart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][4]="<input name=txtcanart".$li_temp."    type=text id=txtcanart".$li_temp."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][5]="<input name=txtpenart".$li_temp."    type=text id=txtpenart".$li_temp."    class=sin-borde size=10 maxlength=12 value='".$li_penart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][6]="<input name=txtpreuniart".$li_temp." type=text id=txtpreuniart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$lo_object[$li_temp][7]="<input name=txtmontotart".$li_temp." type=text id=txtmontotart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' onKeyUp='javascript: ue_validarnumero(this);' style='text-align:right' readonly>";
					$lo_object[$li_temp][8]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
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
		
		case "BUSCARDETALLEORDEN":
		    $ls_operacion="BUSCARDETALLEORDEN";
			$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
			$ls_radiotipo= $io_fun_inventario->uf_obtenervalor("radiotipo","");
			if($ls_radiotipo=="0")
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
			}
			if ($ls_radiotipo=="1")
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
			}
			$ls_numconrec= $io_fun_inventario->uf_obtenervalor("txtnumconrec","");
			$ls_numconrecmov= $io_fun_inventario->uf_obtenervalor("txtnumconrecmov","");
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","1");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","1");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			
			$li_totentsum="0,00";
			$data="";
			$li_totrows=0;
			$ls_pendiente="";
			$ls_checkedcomp="";
			$ls_checkedparc="";
			$ls_readonlyrad="";
			$ld_fecrec1=$io_func->uf_convertirdatetobd($ld_fecrec);
			uf_pintartituloorden($lo_object,$lo_title);
			$lb_valido=$io_siv->uf_siv_obtener_dt_orden($ls_codemp,$ls_numordcom,$li_totrows,$lo_object);
			if (!$lb_valido)
			{
				$li_totrows=1;
				$ls_checkedcomp="";
				$ls_checkedparc="";
				$ls_readonlyrad="";
				uf_pintartituloorden($lo_object,$lo_title);
				uf_agregarlineablancaorden($lo_object,$li_totrows);
			}
		break;
		
		case "BUSCARDETALLE":
			$ls_radiotipo= $io_fun_inventario->uf_obtenervalor("radiotipo","");
			if($ls_radiotipo=="0")
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
			}
			if ($ls_radiotipo=="1")
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
			}
			$ls_estrec=  $io_fun_inventario->uf_obtenervalor("radiotipentrega","");
			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
				$ls_readonlyrad="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
				$ls_readonlyrad="onClick='return false'";
			}
			$ls_numconrec= $io_fun_inventario->uf_obtenervalor("txtnumconrec","");
			$ls_numconrecmov= $io_fun_inventario->uf_obtenervalor("txtnumconrecmov","");
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			uf_pintartituloorden($lo_object,$lo_title);
			$lb_valido=$io_siv->uf_siv_obtener_dt_recepcion($ls_codemp,$ls_numordcom,$ls_numconrec,$li_totrows,$lo_object,$li_totentsum);
			$li_totentsum=number_format($li_totentsum,2,',','.');
		break;
		
		case  "COPIARREVERSO":
			$ls_radiotipo= $io_fun_inventario->uf_obtenervalor("radiotipo","");
			$ls_hidsaverev=$io_fun_inventario->uf_obtenervalor("hidsaverev","");
			$ls_readonly="readonly";
			if($ls_radiotipo=="0")
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
			}
			if ($ls_radiotipo=="1")
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
			}
			$ls_estrec=  $io_fun_inventario->uf_obtenervalor("radiotipentrega","");
			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
				$ls_readonlyrad="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
				$ls_readonlyrad="onClick='return false'";
			}

			$ls_numconrec= $io_fun_inventario->uf_obtenervalor("txtnumconrec","");
			$ls_numconrecmov= $io_fun_inventario->uf_obtenervalor("txtnumconrecmov","");
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			//uf_pintartituloorden($lo_object,$lo_title);
			
			//$lb_valido=$io_siv->uf_siv_obtener_dt_copia_reverso($ls_codemp,$ls_numordcom,$ls_numconrec,$li_totrows,$li_totentsum,$lo_object);
			$lb_valido=$io_siv->uf_siv_obtener_dt_recepcion($ls_codemp,$ls_numordcom,$ls_numconrec,$li_totrows,$li_totentsum,$lo_object);////modificado el dia 03/12/2007
			
		break;
		
		case "GUARDARREVERSO";
			$ls_estpro=    $io_fun_inventario->uf_obtenervalor("radiotipo","");
			$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_codpro=    $io_fun_inventario->uf_obtenervalor("txtcodpro","");
			$ls_denpro=    $io_fun_inventario->uf_obtenervalor("txtdenpro","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecrec=    $io_fun_inventario->uf_obtenervalor("txtfecrec","");
			$ls_obsrec=    $io_fun_inventario->uf_obtenervalor("txtobsrec","");
			$ls_estrec=    $io_fun_inventario->uf_obtenervalor("radiotipentrega","");
			$li_totentsum= $io_fun_inventario->uf_obtenervalor("txttotentsum","");
            $ls_numconrecmov=$io_fun_inventario->uf_obtenervalor("txtnumconrecmov","");
			if($ls_estpro==0)
			{
				$ls_checkedord="checked";
				$ls_checkedfac="";
				$ls_codprodoc="ORD";
			}
			else
			{
				$ls_checkedord="";
				$ls_checkedfac="checked";
				$ls_codprodoc="FAC";
				$ls_numordcom=$ls_numordcom;
			}

			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
				$ls_readonlyrad="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
				$ls_readonlyrad="onClick='return false'";
			}
			$ls_readonly="readonly";
			$ls_numordcom=$io_func->uf_cerosizquierda($ls_numordcom,15);
			$ld_fecrecbd=$io_func->uf_convertirdatetobd($ld_fecrec);
			$lb_valido=$io_fec->uf_valida_fecha_mes($ls_codemp,$ld_fecrecbd);
			if($lb_valido)
			{
				/*if ($ls_status!="C")
				{
					$lb_encontrado=false;
					if ($lb_encontrado)
					{
						$io_msg->message("Registro ya existe"); 
						uf_pintardetalle($li_totrows+1,$ls_estpro);
					}
					else
					{*/	
						$ls_numconrec="";  
						$io_sql->begin_transaction();
						$lb_valido=$io_siv->uf_siv_insert_recepcion($ls_codemp,$ls_numordcom,$ls_codpro,$ls_codalm,$ld_fecrecbd,
																	$ls_obsrec,$ls_codusu,$ls_estpro,$ls_estrec,&$ls_numconrecmov,$la_seguridad);
						
						if ($lb_valido)
						{
							$ls_nummov=0;
							$ls_nomsol="Recepcion";
							$lb_valido=$io_mov->uf_siv_insert_movimiento(&$ls_nummov,$ld_fecrecbd,$ls_nomsol,$ls_codusu,
																		 $la_seguridad);
						}
						if ($lb_valido)
						{
							if($ls_estpro==0)
							{
								$li_totrowsaux=$li_totrows+1;
							}
							else
							{
								$li_totrowsaux=$li_totrows;
							}
							for($li_i=1;$li_i<$li_totrowsaux;$li_i++)
							{
								$ls_unidad=    uf_obtenervalorunidad($li_i);
								$li_unidad=    $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
								$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
								$li_canart=    $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
								$li_penart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
								$li_preuniart= $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
								$li_canoriart= $io_fun_inventario->uf_obtenervalor("txtcanoriart".$li_i,"");
								$li_montotart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
								$li_monsubart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
								
								$li_canart=    str_replace(".","",$li_canart);
								$li_canart=    str_replace(",",".",$li_canart);
								$li_penart=    str_replace(".","",$li_penart);
								$li_penart=    str_replace(",",".",$li_penart);
								$li_preuniart= str_replace(".","",$li_preuniart);
								$li_preuniart= str_replace(",",".",$li_preuniart);
								$li_canoriart= str_replace(".","",$li_canoriart);
								$li_canoriart= str_replace(",",".",$li_canoriart);
								$li_montotart= str_replace(".","",$li_montotart);
								$li_montotart= str_replace(",",".",$li_montotart);
								$li_monsubart= str_replace(".","",$li_monsubart);
								$li_monsubart= str_replace(",",".",$li_monsubart);
								if($li_canart=="")
								{
									$li_canart=0.00;
									$li_montotart=0.00;
									$li_monsubart=0.00;
									$li_penart=$li_canoriart;
								}
								
								if ($ls_unidad=="M")
								{
									$li_canart= ($li_canart * $li_unidad);
								}
								$lb_valido=$io_siv->uf_siv_insert_dt_recepcion($ls_codemp,$ls_numordcom,$ls_codart,$ls_unidad,$li_canart,
																			   $li_penart,$li_preuniart,$li_monsubart,$li_montotart,
																			   $li_i,$li_canoriart,$ls_numconrecmov,$la_seguridad);
								if ($lb_valido)
								{
									$lb_valido=$io_art->uf_siv_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,
																						 $li_canart,$la_seguridad);
									if($lb_valido)
									{
										$ls_opeinv="ENT";
										$ls_promov="RPC";
										$lb_valido=$io_mov->uf_siv_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecrecbd,
																						$ls_codart,$ls_codalm,$ls_opeinv,
																						$ls_codprodoc,$ls_numordcom,$li_canart,
																						$li_preuniart,$ls_promov,$ls_numconrecmov,
																						$li_canart,$ld_fecrecbd,$la_seguridad);
									}
								}
								if($lb_valido)
								{
									$lb_valido=$io_art->uf_siv_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$la_seguridad);
								}
	
							}
						}
						
						if($lb_valido)
						{
							$io_sql->commit();
							$io_msg->message("La entrada de suministros a almacén ha sido procesada");
							if($ls_estpro==0)
							{
								uf_pintartituloorden($lo_object,$lo_title);
							}
							uf_pintardetalle($li_totrowsaux,$ls_estpro);
							$ls_status="C";
						}
						else
						{
							$io_sql->rollback();
							$io_msg->message("No se pudo procesar la entrada de suministros a almacén");
							uf_pintardetalle($li_totrowsaux,$ls_estpro);
						}
					/*}
				}
				else
				{
					$io_msg->message("La entrada de suministros a almacén no debe ser modificada");
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,$li_totrows);
					uf_limpiarvariables();
				}*/
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				//uf_limpiarvariables();
			}
		break;	
	}
?>

<p>&nbsp;</p>
<div align="center">
  <table width="767" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="669" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_inventario->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_inventario);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="755" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="620">&nbsp;</td>
              </tr>
              <tr>
                <td><table width="744" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana">Entrada de Suministros a Almac&eacute;n </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="156" height="19">&nbsp;</td>
                    <td width="373"><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_status?>">
                      <input name="hidreadonly" type="hidden" id="hidreadonly">
                      <input name="txtnumconrec" type="hidden" id="txtnumconrec" value="<?php print $ls_numconrec ?>"></td>
                    <td width="65"><div align="right"></div></td>
                    <td width="148">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right"> </div></td>
                    <td height="22"><div align="left">
                          <input name="radiotipo" type="radio" class="sin-borde"  onClick="javascript: ue_cataorden();" value="0" <?php print $ls_checkedord; ?>>
                      Orden de Compra
                        <input name="radiotipo" type="radio" class="sin-borde" onClick="javascript: ue_cataorden();" value="1" <?php print $ls_checkedfac ?>>
                      Factura</div></td>
                    <td><div align="right">Fecha</div></td>
                    <td><input name="txtfecrec" type="text" id="txtfecrec" style="text-align:center " onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecrec ?>" size="17" maxlength="10" datepicker="true"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Numero Consecutivo  </div></td>
                    <td height="22" colspan="3"><div align="left">
                      <label>
                      <input name="txtnumconrecmov" type="text" id="txtnumconrecmov" style="text-align:center" value="<?php print $ls_numconrecmov ; ?>" size="20" maxlength="15" readonly>
                      </label></div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Orden de Compra/Factura</div></td>
                    <td height="22" colspan="3">
                      <div align="left">
                        <input name="txtnumordcom" type="text" id="txtnumordcom" value="<?php print $ls_numordcom;?>" size="20" maxlength="15"<?php print $ls_readonly?> onKeyPress="return keyRestrict(event, '1234567890'+'abcdefghijklmnopqrstuvwxyz');"onBlur="javascript: ue_rellenarcampo(this,'14')" style="text-align:center ">
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Proveedor</div></td>
                    <td height="22" colspan="3">
                      <div align="left">
                        <input name="txtcodpro" type="text" id="txtcodpro" value="<?php print $ls_codpro?>" size="15" maxlength="10" style="text-align:center " readonly>
                        <a href="javascript: ue_cataproveedor();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                          <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denpro ?>" size="50" readonly>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Almac&eacute;n</div></td>
                    <td height="22" colspan="3">
                      <div align="left">
                        <input name="txtcodalm" type="text" id="txtcodalm" value="<?php print $ls_codalm ?>" size="15" maxlength="10" style="text-align:center " readonly>
                          <a href="javascript: ue_catalmacen();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                          <input name="txtnomfisalm" type="text" class="sin-borde" id="txtnomfisalm" value="<?php print $ls_nomfisalm ?>" size="50" readonly>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">Observaci&oacute;n</div></td>
                    <td colspan="3" rowspan="2">
                      <div align="left">
                        <textarea name="txtobsrec" cols="97" rows="3" id="txtobsrec"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_obsrec ?></textarea>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="20">&nbsp;</td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="20"><div align="right">Tipo de Entrega </div></td>
                    <td colspan="3"><div align="left">
                          <input name="radiotipentrega" type="radio" class="sin-borde" value="1" <?php print $ls_checkedcomp ?> <?php print $ls_readonlyrad ?> onClick="ue_completa();">
                      Completa
                        <input name="radiotipentrega" type="radio" class="sin-borde" value="0" <?php print $ls_checkedparc ?> <?php print $ls_readonlyrad ?>  onClick="ue_parcial();">
                        Parcial</div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="13">&nbsp;</td>
                    <td colspan="3">                      <input name="txtdesalm" type="hidden" id="txtdesalm">
                      <input name="txttelalm" type="hidden" id="txttelalm">
                      <input name="txtubialm" type="hidden" id="txtubialm">
                      <input name="txtnomresalm" type="hidden" id="txtnomresalm">
                      <input name="txttelresalm" type="hidden" id="txttelresalm">
                      <input name="hidstatus" type="hidden" id="hidstatus">
                      <input name="hidsaverev" type="hidden" id="hidsaverev" value="<?php print $ls_hidsaverev; ?>">
                      <input name="txtcodordcomp" type="hidden" id="txtcodordcomp"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28" colspan="4"><p>
                      <?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					?>
                    </p>                      </td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="28">&nbsp;</td>
                    <td height="28">&nbsp;</td>
                    <td height="28"><div align="right">Total</div></td>
                    <td height="28"><input name="txttotentsum" type="text" id="txttotentsum" value="<? print $li_totentsum; ?>" size="17" style="text-align:right" readonly></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
            <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            <input name="filadelete" type="hidden" id="filadelete">
            <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_catarticulo(li_linea)
{
	window.open("sigesp_catdinamic_articulom.php?linea="+li_linea+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_catalmacen()
{
	window.open("sigesp_catdinamic_almacen.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cataproveedor()
{
	f=document.form1;
	if(f.radiotipo[1].checked)
	{
		window.open("sigesp_catdinamic_prov.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else if(f.radiotipo[0].checked)
	{
	  alert(" Selecciono la opcion de Orden de Compra.");
	}
	else
	{
	  alert(" Seleccione una opcion.");
	}
}
function ue_cataorden()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	orden="";
	if(li_incluir==1)
	{	
		if(f.radiotipo[0].checked)
		{
			window.open("sigesp_catdinamic_ordenes.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
			/*f.operacion.value="NUEVAORDEN";
			f.action="sigesp_siv_p_recepcion.php";
			f.submit();*/
		}
		if(f.radiotipo[1].checked)
		{
			f.txtnumordcom.value="";
			f.txtcodpro.value="";
			f.txtdenpro.value="";
			f.operacion.value="NUEVAFACTURA";
			//f.action="sigesp_siv_p_recepcion.php";
			//f.submit();
		}
	}
	else
	{
		f.radiotipo[0].checked=false;
		f.radiotipo[1].checked=false;
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_catdinamic_recepcion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_siv_p_recepcion.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_imprimir(ls_reporte)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		numconrec=  f.txtnumconrec.value;
		if(numconrec!="")
		{
			numordcom= f.txtnumordcom.value;
			codpro=    f.txtcodpro.value;
			denpro=    f.txtdenpro.value;
			codalm=    f.txtcodalm.value;
			denalm=    f.txtnomfisalm.value;
			fecrec=    f.txtfecrec.value;
			obsrec=    f.txtobsrec.value;
			window.open("reportes/"+ls_reporte+"?numconrec="+numconrec+"&fecrec="+fecrec+"&obsrec="+obsrec+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
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


function uf_agregar_dt(li_row)
{
	f=document.form1;
	ls_codnewart= eval("f.txtcodart"+li_row+".value");
	ls_codnewuni= eval("f.cmbunidad"+li_row+".value");
	if (ls_codnewuni=="M")
	{
		ls_codnewuni="Mayor";
	}
	else
	{
		ls_codnewuni="Detal";
	}
	ls_codnewcan= eval("f.txtcanart"+li_row+".value");
	ls_codnewpen= eval("f.txtpenart"+li_row+".value");
	ls_codnewpre= eval("f.txtpreuniart"+li_row+".value");
	ls_codnewori= eval("f.txtcanoriart"+li_row+".value");
	ls_codnewmon= eval("f.txtmontotart"+li_row+".value");	
	li_total=f.totalfilas.value;
	lb_valido=false;
	
	for(li_i=1;li_i<li_total&&lb_valido!=true;li_i++)
	{
		ls_codart=    eval("f.txtcodart"+li_i+".value");
		ls_unidad=    eval("f.txtunidad"+li_i+".value");
		ls_canart=    eval("f.txtcanart"+li_i+".value");
		ls_penart=    eval("f.txtpenart"+li_i+".value");
		ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
		ls_canoriart= eval("f.txtcanoriart"+li_i+".value");
		ls_montotord= eval("f.txtmontotart"+li_i+".value");
		if((ls_codart==ls_codnewart)&&(ls_unidad==ls_codnewuni)&&(li_i!=li_row))
		{
			alert("El detalle ya esta registrado");
			lb_valido=true;
		}
	}
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_penart=eval("f.txtpenart"+li_row+".value");
	ls_penart=ue_validarvacio(ls_penart);
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	ls_montotord=eval("f.txtmontotart"+li_row+".value"); 
	ls_montotord=ue_validarvacio(ls_montotord);

	if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_penart=="")||(ls_preuniart=="")||(ls_canoriart=="")||(ls_montotord==""))
	{
		alert("Debe llenar todos los campos");
		lb_valido=true;
	}

	
	ls_canart=ue_formato_operaciones(ls_canart);
	ls_penart=ue_formato_operaciones(ls_penart);
	li_aux=(parseFloat(ls_canart) + parseFloat(ls_penart));
	li_aux=uf_convertir(li_aux);

	if (li_aux!=ls_canoriart)
	{
		alert("No concuerdan las cantidades de articulos");
		lb_valido=true;	
	}

	ls_numordcom=eval("f.txtnumordcom.value");
	ls_numordcom=ue_validarvacio(ls_numordcom);
	ls_codpro=eval("f.txtcodpro.value");
	ls_codpro=ue_validarvacio(ls_codpro);
	ls_codalm=eval("f.txtcodalm.value");
	ls_codalm=ue_validarvacio(ls_codalm);
	ls_fecrec=eval("f.txtfecrec.value");
	ls_fecrec=ue_validarvacio(ls_fecrec);
	
	if((ls_numordcom=="")||(ls_codpro=="")||(ls_codalm=="")||(ls_fecrec==""))
	{
		alert("Debe llenar los campos principales");
		lb_valido=true;
	}

	if(!lb_valido)
	{
		ue_calculartotal();
		f.operacion.value="AGREGARDETALLE";
		f.action="sigesp_siv_p_recepcion.php";
		f.submit();
	}
}
function uf_dt_activo(li_row)
{
	f=document.form1;
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_denart=eval("f.txtdenart"+li_row+".value");
	li_canart=eval("f.txtcanart"+li_row+".value");
	li_clasif=eval("f.hclasi"+li_row+".value");			
	ls_estatus=f.hidestatus.value;
	ls_numconrec=f.txtnumconrec.value;
	if(ls_numconrec="")
	{
		ls_numconrec=f.txtnumconrecmov.value;
	}
	ls_numordcom=f.txtnumordcom.value;
	li_canart=ue_formato_operaciones(li_canart);
	
	if((ls_codart!="")&&(li_canart>0))
	{
		if (li_clasif==1)
		{	
			if(ls_estatus=="C")
			{
				window.open("sigesp_siv_pdt_activos.php?codart="+ls_codart+"&canart="+li_canart+"&denart="+ls_denart+"&numordcom="+ls_numordcom+"&numconrec="+ls_numconrec,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=850,height=600,left=30,top=30,location=no,resizable=yes");
			}
			else
			{
				alert("El movimiento debe estar registrado");
			}
		 }
		 else
		 {
		 	alert("El Artículo Seleccionado NO es un bien");
		 }
	}
	else
	{
		alert("Debe exisistir mas de 1 articulo en el movimiento");
	}
}
function uf_delete_dt(li_row)
{
	f=document.form1;
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_penart=eval("f.txtpenart"+li_row+".value");
	ls_penart=ue_validarvacio(ls_penart);
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	ls_montotord=eval("f.txtmontotart"+li_row+".value");
	ls_montotord=ue_validarvacio(ls_montotord);

	if((ls_codart=="")||(ls_canart=="")||(ls_penart=="")||(ls_preuniart=="")||(ls_canoriart=="")||(ls_montotord==""))
	{
		alert("No deben tener campos vacios");
		lb_valido=true;
	}
	else
	{
		li_fila=f.totalfilas.value;
		if(li_fila!=li_row)
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{	
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_siv_p_recepcion.php";
				f.submit();
			}
		}
	}
}

function ue_guardar()
{
	f=document.form1;
	lb_valido=true;
	li_totfilas=f.totalfilas.value;
	ls_numordcom=eval("f.txtnumordcom.value");
	ls_numordcom=ue_validarvacio(ls_numordcom);
	ls_codpro=eval("f.txtcodpro.value");
	ls_codpro=ue_validarvacio(ls_codpro);
	ls_codalm=eval("f.txtcodalm.value");
	ls_codalm=ue_validarvacio(ls_codalm);
	ls_fecrec=eval("f.txtfecrec.value");
	ls_fecrec=ue_validarvacio(ls_fecrec);
	if ((ls_numordcom=="")||(ls_codpro=="")||(ls_codalm=="")||(ls_fecrec==""))
	{
		alert("Debe llenar los campos principales");
		lb_valido=false;
	}
	else
	{
		if ((f.radiotipentrega[0].checked==false)&&(f.radiotipentrega[1].checked==false))
		{
			alert("Debe indicar si es entrega completa ó parcial");
			lb_valido=false;
		}
	}
	if ((f.radiotipo[0].checked==true)||(f.radiotipo[1].checked==true))
	{
		li_totfilas=li_totfilas+1;
	}
   /*for(li_i=1;li_i<li_totfilas;li_i++)
	{
		ls_codart=    eval("f.txtcodart"+li_i+".value");
		ls_codart=ue_validarvacio(ls_codart);
		ls_unidad=    eval("f.txtunidad"+li_i+".value");
		ls_unidad=ue_validarvacio(ls_unidad);
		ls_canart=    eval("f.txtcanart"+li_i+".value");
		ls_canart=ue_validarvacio(ls_canart);
		ls_penart=    eval("f.txtpenart"+li_i+".value");
		ls_penart=ue_validarvacio(ls_penart);
		ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
		ls_preuniart=ue_validarvacio(ls_preuniart);
		ls_canoriart= eval("f.txtcanoriart"+li_i+".value");
		ls_canoriart=ue_validarvacio(ls_canoriart);
		ls_montotord= eval("f.txtmontotart"+li_i+".value");
		ls_montotord=ue_validarvacio(ls_montotord);
		if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_penart=="")||(ls_preuniart=="")||(ls_canoriart=="")||(ls_montotord==""))
		{
			alert("Debe indicar la cantidad recibida para el articulo "+ls_codart+"");
			lb_valido=false;
		}
	}*/	
    if(li_totfilas<=1)
	{
	   // if ((f.radiotipo[0].checked==true)||(f.radiotipo[1].checked==true))
		//{
			alert("La entrada de suministros debe tener al menos 1 artículo");
			lb_valido=false;
		//}
	}

	if(lb_valido)
	{
		ls_hidsaverev=f.hidsaverev.value;
		if(ls_hidsaverev!="true")
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_siv_p_recepcion.php";
			f.submit();
		}
		else if(ls_hidsaverev=="true")
		{
			f.operacion.value="GUARDARREVERSO";
			f.action="sigesp_siv_p_recepcion.php";
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
		f.action="sigesp_siv_p_tipoarticulo.php";
		f.submit();
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que calcula cuantos articulos quedaran pendientes
//	de la orden de compra
//--------------------------------------------------------
function ue_calcularpendiente(li_row)
{
	f=document.form1;
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	
	ls_unidad=eval("f.txtunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);

	li_unidad=eval("f.hidunidad"+li_row+".value");
	li_unidad=ue_validarvacio(li_unidad);
	
	ls_penart=eval("f.txtpenart"+li_row+".value");
	ls_penart=ue_validarvacio(ls_penart);
	
	ls_hidpenart=eval("f.hidpendiente"+li_row+".value");
	ls_hidpenart=ue_validarvacio(ls_hidpenart);
	
	ls_canoriart=eval("f.txtcanoriart"+li_row+".value");
	ls_canoriart=ue_validarvacio(ls_canoriart);
	
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	li_cero="0,00";
	
	ls_canoriart=ue_formato_operaciones(ls_canoriart);
	ls_canart=ue_formato_operaciones(ls_canart);
	ls_preuniart=ue_formato_operaciones(ls_preuniart);

	if((parseFloat(ls_canoriart) < parseFloat(ls_canart)))
	{
		ls_canoriart=uf_convertir(ls_canoriart);
		ls_canart=uf_convertir(ls_canart);
		alert("La cantidad recibida no puede ser mayor que la ordenada");
		obj=eval("f.txtcanart"+li_row+"");
		obj.value=li_cero;
	}
	else
	{
		if((parseFloat(ls_hidpenart) < parseFloat(ls_canart)))
		{
			alert("La cantidad recibida no puede ser mayor que pendiente");
			obj=eval("f.txtcanart"+li_row+"");
			obj.value=li_cero;
		}
		else
		{
			if(ls_canart!="")
			{
				li_pendiente=(parseFloat(ls_hidpenart) - parseFloat(ls_canart));
				li_pendiente=uf_convertir(li_pendiente);
				obj=eval("f.txtpenart"+li_row+"");
				obj.value=li_pendiente;
				li_totart=(parseFloat(ls_preuniart) * parseFloat(ls_canart));
				li_totart=uf_convertir(li_totart);
			}
		}

		if(ls_hidpenart=="")
		{
			if(ls_canart!="")
			{
				li_pendiente=(parseFloat(ls_canoriart) - parseFloat(ls_canart));
				li_pendiente=uf_convertir(li_pendiente);
				obj=eval("f.txtpenart"+li_row+"");
				obj.value=li_pendiente;
				li_unidad=eval("f.hidunidad"+li_row+".value");
			}
		}
	}
	if((ls_canart!="")&&(ls_preuniart!=""))
	{
		li_unidad=eval("f.hidunidad"+li_row+".value");
		if(ls_unidad=="Mayor")
		{
			ls_canart=parseFloat(ls_canart) * parseFloat(li_unidad);
		}
		li_montot=parseFloat(ls_canart) * parseFloat(ls_preuniart);
		li_montot=uf_convertir(li_montot);
		obj=eval("f.txtmontotart"+li_row+"");
		obj.value=li_montot;
	}
	else
	{
		ls_blanco="0,00";
		obj=eval("f.txtmontotart"+li_row+"");
		obj.value=ls_blanco;
		ls_canoriginal=uf_convertir(ls_canoriart)
		if(ls_canart!="")
		{
			obj=eval("f.txtpenart"+li_row+"");
			obj.value=ls_canoriginal;
		}
	}
	ue_calculartotal();
}
//--------------------------------------------------------
//	Función que llena por defecto campos del grid 
//	cuando la entrada de suministros es por una factura
//--------------------------------------------------------
function ue_articulosfactura(li_row)
{
	f=document.form1;
	if(f.radiotipo[0].checked==true)
	{
		li_preuniart=eval("f.txtpreuniart"+li_row+".value");
		li_preuniart=ue_validarvacio(li_preuniart);
		li_canoriart=eval("f.txtcanoriart"+li_row+".value");
		li_canoriart=ue_validarvacio(li_canoriart);
		li_canart=eval("f.txtcanart"+li_row+".value");
		li_canart=ue_validarvacio(li_canart);
		obj=eval("f.txtcanart"+li_row+"");
		obj.value=li_canoriart;
		obj=eval("f.txtpenart"+li_row+"");
		obj.value="0,00";
		if((li_canart!="")&&(ls_preuniart!=""))
		{
			li_unidad=eval("f.hidunidad"+li_row+".value");
			li_unidad= ue_formato_operaciones(li_unidad);
			li_canart= ue_formato_operaciones(li_canart);
			if(ls_unidad=="M")
			{
				li_canart=parseFloat(li_canart) * parseFloat(li_unidad);
			}
			li_preuniart= ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_canart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			eval("f.txtmontotart"+li_row+".value='"+li_montot+"'");
		}
	}
	else
	{
		li_preuniart=eval("f.txtpreuniart"+li_row+".value");
		li_preuniart=ue_validarvacio(li_preuniart);
		li_canoriart=eval("f.txtcanoriart"+li_row+".value");
		li_canoriart=ue_validarvacio(li_canoriart);
		li_canart=eval("f.txtcanart"+li_row+".value");
		li_canart=ue_validarvacio(li_canart);
		obj=eval("f.txtcanart"+li_row+"");
		obj.value=li_canoriart;
		obj=eval("f.txtpenart"+li_row+"");
		obj.value="0,00";
		if((li_canoriart!="")&&(ls_preuniart!=""))
		{
			li_unidad=eval("f.hidunidad"+li_row+".value");
			li_unidad= ue_formato_operaciones(li_unidad);
			li_canoriart= ue_formato_operaciones(li_canoriart);
			if(ls_unidad=="M")
			{
				li_canoriart=parseFloat(li_canoriart) * parseFloat(li_unidad);
			}
			li_preuniart= ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_canoriart) * parseFloat(li_preuniart); 
			li_montot=uf_convertir(li_montot);   
			eval("f.txtmontotart"+li_row+".value='"+li_montot+"'");
		}
	}
}
//--------------------------------------------------------
//	Función que calcula el monto total por articulo 
//	cuando la entrada de suministros es por una factura
//--------------------------------------------------------
function ue_montosfactura(li_row)
{
	f=document.form1;
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	li_unidad=eval("f.hidunidad"+li_row+".value");
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	if((ls_canart!="")&&(ls_preuniart!=""))
	{
		ls_preuniart=ue_formato_operaciones(ls_preuniart);
		ls_canart=   ue_formato_operaciones(ls_canart);
		li_unidad=   ue_formato_operaciones(li_unidad);
		if(ls_unidad=="M")
		{
			ls_canart=parseFloat(ls_canart) * parseFloat(li_unidad);
		}
		li_montot=parseFloat(ls_canart) * parseFloat(ls_preuniart); 
		li_montot=uf_convertir(li_montot);  
		obj=eval("f.txtmontotart"+li_row+"");
		obj.value=li_montot;
	}
}

function ue_completa()
{
	f=document.form1;
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		li_canoriart= eval("f.txtcanoriart"+li_i+".value");
		li_penart= eval("f.txtpenart"+li_i+".value");
		li_preuniart= eval("f.txtpreuniart"+li_i+".value");
		if(li_penart=="0,00")
		{
			obj=eval("f.txtcanart"+li_i+"");
			obj.value=li_canoriart;
			obj=eval("f.txtpenart"+li_i+"");
			obj.value="0,00";
			li_canoriart=   ue_formato_operaciones(li_canoriart);
			li_preuniart=   ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_canoriart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			obj=eval("f.txtmontotart"+li_i+"");
			obj.value=li_montot;
		}
		else
		{
			obj=eval("f.txtcanart"+li_i+"");
			obj.value=li_penart;
			obj=eval("f.txtpenart"+li_i+"");
			obj.value="0,00";
			li_penart=    ue_formato_operaciones(li_penart);
			li_preuniart= ue_formato_operaciones(li_preuniart);
			li_montot=parseFloat(li_penart) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			obj=eval("f.txtmontotart"+li_i+"");
			obj.value=li_montot;
		}
	}	
	ue_calculartotal();
}

function ue_parcial()
{   
    f=document.form1;/////agregado el 07/02/2008
	li_totfilas=f.totalfilas.value;	
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		ls_hidpenart=eval("f.hidpendiente"+li_i+".value");
		ls_hidpenart=ue_validarvacio(ls_hidpenart);
		if(ls_hidpenart!="")
		{
			li_pendiente=uf_convertir(ls_hidpenart);
			obj=eval("f.txtpenart"+li_i+"");
			obj.value=li_pendiente;
		}
		else
		{
			obj=eval("f.txtpenart"+li_i+"");
			obj.value="0,00";
		}
		obj=eval("f.txtcanart"+li_i+"");
		obj.value="";
		obj=eval("f.txtmontotart"+li_i+"");
		obj.value="";
	}
	f.txttotentsum.value="0,00";
}

//--------------------------------------------------------------
//	Función que calcula el total de la recepcion de suministros
//--------------------------------------------------------------
function ue_calculartotal()
{
	f=document.form1;
	li_totalrow=f.totalfilas.value;
	li_total=0;
	for(li_i=1;li_i<=li_totalrow;li_i++)
	{
		li_subtotal=eval("f.txtmontotart"+li_i+".value");
		if(li_subtotal!="")
		{
			li_subtotal= ue_formato_operaciones(li_subtotal);
			li_total=li_total + parseFloat(li_subtotal);
		}
	}
	li_total=uf_convertir(li_total);
	f.txttotentsum.value=li_total;
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
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumerosinpunto(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>