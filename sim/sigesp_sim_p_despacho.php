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
$ls_codtiend = $_SESSION["ls_codtienda"];
$ls_dentiend = $_SESSION["ls_nomtienda"];

/*$_SESSION["ls_coduniad"]= '0000021453';
$ls_codtiend = "0002";
$ls_dentiend = "Agrot. Numero 2";*/

require_once("class_funciones_inventario.php");
$io_fun_inventario=new class_funciones_inventario();
$io_fun_inventario->uf_load_seguridad("SIM","sigesp_sim_p_despacho.php",$ls_permisos,$la_seguridad,$la_permisos);

$ls_tipo= $io_fun_inventario->uf_obtenervalor("tipo","");

if($ls_tipo!="Salida"){
	if($ls_tipo=="")
	{
		$ls_tipo="Salida";
		$ls_lectura="";
		$ls_coduniadm=$_SESSION["ls_coduniad"];
		$ls_checkedparc="";
		$ls_checkedcomp="checked";
	}
	else{
		$ls_lectura="readonly";
	}
}else{
	$ls_lectura="";
	$ls_coduniadm=$_SESSION["ls_coduniad"];
	$ls_checkedparc="";
	$ls_checkedcomp="checked";
}
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
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
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
		//    Description: Funcion que agrega una linea en blanco al final del grid del detalle de despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input  name=txtdenart".$ai_totrows."     type=text   id=txtdenart".$ai_totrows." class=sin-borde size=25 maxlength=50 readonly>".
								   "<input  name=txtcodart".$ai_totrows."     type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=50 readonly>".
								   "<input  name=txtctagas".$ai_totrows."     type=hidden id=txtctagas".$ai_totrows." class=sin-borde size=20 maxlength=50 readonly>".
								   "<input  name=txtctasep".$ai_totrows."     type=hidden id=txtctasep".$ai_totrows." class=sin-borde size=20 maxlength=20 readonly>";
		$aa_object[$ai_totrows][2]="<input  name=txtcodalm".$ai_totrows."     type=text   id=txtcodalm".$ai_totrows." class=sin-borde size=13 maxlength=10 readonly>".
								   "<a href='javascript: ue_catalmacen(".$ai_totrows.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
		$aa_object[$ai_totrows][3]="<select name=cmbunidad".$ai_totrows."     style='width:60px '><option value=D>Detal</option><option value=M>Mayor</option></select>";
		$aa_object[$ai_totrows][4]="<input  name=txtcansol".$ai_totrows."     type=text   id=txtcansol".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>".
								   "<input  name=hidexistencia".$ai_totrows." type=hidden id=hidexistencia".$ai_totrows.">";
		$aa_object[$ai_totrows][5]="<input  name=txtpenart".$ai_totrows."     type=text   id=txtpenart".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
		$aa_object[$ai_totrows][6]="<input  name=txtcanart".$ai_totrows."     type=text   id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyUp='javascript: ue_validarnumero(this);'  onBlur='javascript: ue_montosfactura(".$ai_totrows.");'>";
		$aa_object[$ai_totrows][7]="<input  name=txtpreuniart".$ai_totrows."  type=text   id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 readonly>".
								   "<input  name=hidnumdocori".$ai_totrows."  type=hidden id=hidnumdocori".$ai_totrows.">";
		$aa_object[$ai_totrows][8]="<input  name=txtmontotart".$ai_totrows."  type=text   id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 readonly>";
   }
   //--------------------------------------------------------------

   function uf_agregarlineablancacontable(&$aa_objectc,$ai_totrowsc)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancacontable
		//         Access: private
		//      Argumento: $aa_objectc  // arreglo de titulos
		//                 $ai_totrowsc // ultima fila pintada en el grid
		//	      Returns:
		//    Description: Funcion que agrega una linea en blanco al final del grid del detalle contable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_objectc[$ai_totrowsc][1]="<input  name=txtdenartc".$ai_totrowsc."  type=text   id=txtdenartc".$ai_totrowsc."  class=sin-borde size=15 maxlength=50 readonly>".
								     "<input  name=txtcodartc".$ai_totrowsc."  type=hidden id=txtcodartc".$ai_totrowsc."  class=sin-borde size=20 maxlength=50 readonly>".
		$aa_objectc[$ai_totrowsc][2]="<input  name=txtsccuenta".$ai_totrowsc." type=text   id=txtsccuenta".$ai_totrowsc." class=sin-borde size=15              readonly>";
		$aa_objectc[$ai_totrowsc][3]="<input  name=txtdebhab".$ai_totrowsc."   type=text   id=txtdebhab".$ai_totrowsc."   class=sin-borde size=5               readonly>";
		$aa_objectc[$ai_totrowsc][4]="<input  name=txtmonto".$ai_totrowsc."    type=text   id=txtcansolc".$ai_totrowsc."  class=sin-borde size=12              readonly>";
   }
   //--------------------------------------------------------------

   function uf_agregarlineasalida(&$aa_object,$ai_totrows) {

		$aa_object[$ai_totrows][1]="<input  name=txtdenart".$ai_totrows."     type=text   id=txtdenart".$ai_totrows." class=sin-borde size=25 maxlength=50 readonly>".
								   "<input  name=txtcodart".$ai_totrows."     type=text   id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=50 onKeyUp='javascript: ue_validarnumerosinpunto(this);' readonly><a href='javascript: ue_catarticulosal(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";

		$aa_object[$ai_totrows][2]="<input  name=txtnomprov".$ai_totrows."     type=text   id=txtnomprov".$ai_totrows." class=sin-borde size=25 maxlength=50 readonly>".
								   "<input  name=txtcodprov".$ai_totrows."     type=text   id=txtcodprov".$ai_totrows." class=sin-borde size=20 maxlength=50 readonly>";

		$aa_object[$ai_totrows][3]="<select name=cmbunidad".$ai_totrows."     style='width:60px '><option value=D>Detal</option><option value=M>Mayor</option></select> <input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."'>";

		$aa_object[$ai_totrows][4]="<input  name=txtcanart".$ai_totrows."     type=text   id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event)) onBlur='javascript: ue_montosfactura2(".$ai_totrows.");'>" .
				"<input  name=hidexistencia".$ai_totrows." type=hidden id=hidexistencia".$ai_totrows.">";
		$aa_object[$ai_totrows][5]="<input  name=txtpreuniart".$ai_totrows."  type=text   id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 readonly>".
								   "<input  name=hidnumdocori".$ai_totrows."  type=hidden id=hidnumdocori".$ai_totrows.">";
		$aa_object[$ai_totrows][6]="<input  name=txtmontotart".$ai_totrows."  type=text   id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 readonly>";
		$aa_object[$ai_totrows][7]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][8]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
   }

   //--------------------------------------------------------------

   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//         Access: private
		//      Argumento:
		//	      Returns:
		//    Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_numorddes,$ls_numsol,$ls_coduniadm,$ls_denuniadm,$ls_obsdes,$ld_fecdes,$li_totentsum;
		global $ls_codusu,$ls_readonly,$ls_codunides,$ls_denunides,$ls_checkedparc,$ls_checkedcomp,$ls_codprov,$ls_nomprov;

		$ls_numorddes="";
		$ls_numsol="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_obsdes="";
		$ld_fecdes=date("d/m/Y");
		$li_totentsum="0,00";
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_readonly="true";
		$ls_codunides="";
		$ls_denunides="";
		$ls_checkedparc="";
		$ls_checkedcomp="checked";
		//$ls_codprov="";
		//$ls_nomprov="";
   }

   function uf_titulosdespacho()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_titulosdespacho
		//         Access: private
		//      Argumento:
		//	      Returns:
		//    Description: Funci�n que carga las caracteristicas del grid de detalle de despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title;

		$ls_titletable="Detalle del Despacho";
		$li_widthtable=800;
		$ls_nametable="grid";
		$lo_title[1]="Art&iacute;culo";
		$lo_title[2]="Almac&eacute;n";
		$lo_title[3]="Unidad";
		$lo_title[4]="Cant. Solicitada";
		$lo_title[5]="Cant. Pendiente";
		$lo_title[6]="Cant. a Despachar";
		$lo_title[7]="Precio Unitario";
		$lo_title[8]="Total";
   }

   function uf_tituloscontable()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_tituloscontable
		//         Access: private
		//      Argumento:
		//	      Returns:
		//    Description: Funci�n que carga las caracteristicas del grid de detalle contable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_titlecontable,$li_widthcontable,$ls_namecontable,$lo_titlecontable;

		$ls_titlecontable="Detalle Contable";
		$li_widthcontable=800;
		$ls_namecontable="grid";
		$lo_titlecontable[1]="Producto";
		$lo_titlecontable[2]="Cuenta";
		$lo_titlecontable[3]="Debe/Haber";
		$lo_titlecontable[4]="Monto";
   }

   function uf_titulossalida()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_titulosdespacho
		//         Access: private
		//      Argumento:
		//	      Returns:
		//    Description: Funci�n que carga las caracteristicas del grid de detalle de despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title;

		$ls_titletable="Detalle de la Salida";
		$li_widthtable=800;
		$ls_nametable="grid";
		$lo_title[1]="Producto";
		$lo_title[2]="Proveedor";
		$lo_title[3]="Unidad";
		$lo_title[4]="Cant. a Despachar";
		$lo_title[5]="Precio Unitario";
		$lo_title[6]="Total";
		$lo_title[7]=" ";
		$lo_title[8]=" ";
   }

   function uf_obtenervalorunidad($li_i)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenervalorunidad
		//         Access: private
		//      Argumento: $li_i  //  indica que opcion esta seleccionado en el combo
		//	      Returns: Retorna el valor obtenido
		//    Description: Funci�n que obtiene el contenido del combo cmbunidad o del campo txtunidad deacuerdo sea el caso
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
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

	function uf_incluircontable($as_codemp,$as_numorddes,$ad_fecdes,&$aa_objectc,$ai_totrowsc,$aa_seguridad,$io_fun_inventario,$io_siv)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablancacontable
		//         Access: private
		//      Argumento: $as_codemp         // codigo de empresa
		//                 $as_numorddes      // numero de orden de despacho
		//                 $ad_fecdes         // fecha del despacho
		//                 $aa_objectc        // arreglo de titulos
		//                 $ai_totrowsc       // ultima fila pintada en el grid
		//                 $aa_seguridad      // arreglo de seguridad
		//                 $io_fun_inventario // instancia de la clase de funciones de inventario
		//                 $io_siv            // instancia de la clase sigesp_sim_c_despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que pinta nuevamente el grid de detalle contable con los datos que estaban en el ademas de
		//                 activar el proceso de insert del mismo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 08/02/2006								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_error=false;
		$lb_valido=true;
		for($li_j=1;$li_j<=$ai_totrowsc;$li_j++)
		{
			$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodartc".$li_j,"");
			$ls_denart=    $io_fun_inventario->uf_obtenervalor("txtdenartc".$li_j,"");
			$ls_sccuenta=  $io_fun_inventario->uf_obtenervalor("txtsccuenta".$li_j,"");
			$ls_debhab=    $io_fun_inventario->uf_obtenervalor("txtdebhab".$li_j,"");
			$li_montoc=    $io_fun_inventario->uf_obtenervalor("txtmonto".$li_j,"");
			$li_montoc=    str_replace(".","",$li_montoc);
			$li_montoc=    str_replace(",",".",$li_montoc);
			$li_montotot= $li_montoc;
			$lb_incluir=true;

			/*for($li_z=1;$li_z<=$ai_totrowsc;$li_z++)
			{
				$ls_sccuentaaux=  $io_fun_inventario->uf_obtenervalor("txtsccuenta".$li_z,"");
				$li_montoaux=     $io_fun_inventario->uf_obtenervalor("txtmonto".$li_z,"");
				$li_montoaux=     str_replace(".","",$li_montoaux);
				$li_montoaux=     str_replace(",",".",$li_montoaux);
				if(($ls_sccuentaaux==$ls_sccuenta)&&($li_z > $li_j))
				{$li_montotot=$li_montotot + $li_montoaux;}
				if(($ls_sccuentaaux==$ls_sccuenta)&&($li_z < $li_j))
				{$lb_incluir=false;}

			}*/

			if($lb_incluir)
			{
				$lb_valido=$io_siv->uf_sim_insert_dt_scg($as_codemp,$ls_codart,$as_numorddes,$ad_fecdes,$ls_sccuenta,$ls_debhab,
														 $li_montotot,$aa_seguridad);
			}
			if(!$lb_valido)
			{$lb_error=true;}

			$aa_objectc[$li_j][1]="<input  name=txtdenartc".$li_j."  type=text   id=txtdenartc".$li_j."  class=sin-borde size=50  value='".$ls_denart."'   readonly>".
								  "<input  name=txtcodartc".$li_j."  type=hidden id=txtcodartc".$li_j."  class=sin-borde size=30  value='".$ls_codart."'   readonly>";
			$aa_objectc[$li_j][2]="<input  name=txtsccuenta".$li_j." type=text   id=txtsccuenta".$li_j." class=sin-borde size=30  value='".$ls_sccuenta."' readonly>";
			$aa_objectc[$li_j][3]="<input  name=txtdebhab".$li_j."   type=text   id=txtdebhab".$li_j."   class=sin-borde size=15  value='".$ls_debhab."'   readonly style='text-align:center'>";
			$aa_objectc[$li_j][4]="<input  name=txtmonto".$li_j."    type=text   id=txtcansolc".$li_j."  class=sin-borde size=30  value='".number_format ($li_montoc,2,",",".")."' style='text-align:right' readonly>";
		}
		if($lb_error)
		{$lb_valido=false;}
		return $lb_valido;
	}

	function uf_total_salida($ai_totrows){

		$li_total =0;
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			$li_montotartaux = $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
			//print $li_montotartaux.'<br>';
			$li_montotartaux=    str_replace(".","",$li_montotartaux);
			$li_montotartaux=    str_replace(",",".",$li_montotartaux);
			$li_total = $li_total + $li_montotartaux;
		}

		return 	$li_total;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Despacho de Suministros </title>
<!--meta http-equiv="imagetoolbar" content="no"-->
<meta http-equiv="" content="text/html; charset=iso-8859-1">
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function()
		{
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


<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=      new sigesp_include();
	$con=     $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=  new class_sql($con);
	require_once("../shared/class_folder/class_fecha.php");
	$io_fec= new class_fecha();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=  new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun=  new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("../shared/class_folder/grid_param.php");
	$in_grid= new grid_param();
	require_once("sigesp_sim_c_despacho.php");
	$io_siv=  new sigesp_sim_c_despacho();
	require_once("sigesp_sim_c_articuloxalmacen.php");
	$io_art=  new sigesp_sim_c_articuloxalmacen();
	require_once("sigesp_sim_c_almacen.php");
	$io_alm=  new sigesp_sim_c_almacen();
	require_once("sigesp_sim_c_recepcion.php");
	$io_recepcion=  new sigesp_sim_c_recepcion();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_numsol = $io_fun_inventario->uf_obtenervalor("txtnumsol","");
	$li_totrows = $io_fun_inventario->uf_obtenervalor("totalfilas",1);
	$li_totrowsc= $io_fun_inventario->uf_obtenervalor("totalfilasc",1);
	$ls_obsdes= $io_fun_inventario->uf_obtenervalor("txtobsdes","");



	$ls_operacion= $io_fun_inventario->uf_obteneroperacion();
	$ls_status=    $io_fun_inventario->uf_obtenervalor("hidstatus","");
	if ($ls_status=="C")
	{
		$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
		$li_catafilas= $io_fun_inventario->uf_obtenervalor("catafilas","");
	}

	$lb_cont=$io_siv->uf_sim_load_contabilizacion($ls_codemp,$li_value);
	if($li_value==0)
	{
		$ls_ok=true;
	}

	switch ($ls_operacion)
	{

		case "NUEVO":

			if($ls_tipo!="Salida")
			{
				uf_titulosdespacho();
				uf_tituloscontable();

				uf_limpiarvariables();
				uf_agregarlineablanca($lo_object,1);
				uf_agregarlineablancacontable($lo_objectc,1);
				$ls_tiporeq = "checked";
				$ls_tiposal = "";
			}else
			{
				uf_limpiarvariables();
				uf_titulossalida();
				$li_totrows=1;
				uf_agregarlineasalida($lo_object,1);

				$ls_tiporeq = "";
				$ls_tiposal = "checked";
			}

		break;
		case "GUARDAR":
			uf_limpiarvariables();
			$lb_descomp=true;
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ls_estsol=    $io_fun_inventario->uf_obtenervalor("txtestsol","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ld_fecdesaux= $io_func->uf_convertirdatetobd($ld_fecdes);
			$ls_estrevdes= "1";
			$ls_estdes=    "1";
			$ls_estrec=  $io_fun_inventario->uf_obtenervalor("rdtipodespacho","");

			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
			}
			$lb_valido=$io_fec->uf_valida_fecha_periodo($ld_fecdes,$ls_codemp);
			if($lb_valido)
			{
				$io_sql->begin_transaction();
				$lb_valido=$io_siv->uf_sim_insert_despacho($ls_codemp,$ls_numorddes,$ls_numsol,$ls_coduniadm,$ld_fecdesaux,$ls_obsdes,
														   $ls_logusr,$ls_estdes,$ls_estrevdes,$ls_codunides,$la_seguridad);
				if($lb_valido)
				{

					$ls_nummov=0;
					$ls_nomsol="Despacho";
					$lb_valido=$io_siv->io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecdesaux,$ls_nomsol,$ls_logusr,
																		  $la_seguridad,$ls_codtiend);

					if($lb_valido)
					{
						$lb_exito=true;
						for($li_i=1;$li_i<=$li_totrows;$li_i++)
						{
							$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
							$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
							$ls_codalm=       $io_fun_inventario->uf_obtenervalor("txtcodalm".$li_i,"");
							$li_canorisolsep= $io_fun_inventario->uf_obtenervalor("txtcansol".$li_i,"");
							$li_existencia=   $io_fun_inventario->uf_obtenervalor("hidexistencia".$li_i,"");
							$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
							$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
							$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
							$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
							$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
							$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
							$ls_ctagas=       $io_fun_inventario->uf_obtenervalor("txtctagas".$li_i,"");
							$ls_ctasep=       $io_fun_inventario->uf_obtenervalor("txtctasep".$li_i,"");
							$li_canpenart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
							$li_canorisolsep= str_replace(".","",$li_canorisolsep);
							$li_canorisolsep= str_replace(",",".",$li_canorisolsep);
							$li_canart=       str_replace(".","",$li_canart);
							$li_canart=       str_replace(",",".",$li_canart);
							$li_preuniart=    str_replace(".","",$li_preuniart);
							$li_preuniart=    str_replace(",",".",$li_preuniart);
							$li_montotart=    str_replace(".","",$li_montotart);
							$li_montotart=    str_replace(",",".",$li_montotart);
							$li_canpenart=    str_replace(".","",$li_canpenart);
							$li_canpenart=    str_replace(",",".",$li_canpenart);
							$li_auxcanpenart=$li_canpenart;
							$li_canartaux=$li_canart;
							if($ls_unidad=="")
							{
								$ls_unidad= $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
								$ls_hidunidad= $io_fun_inventario->uf_obtenervalor("hidtxtuni".$li_i,"");
							}
							if($ls_unidad=="Mayor")
							{
								$ls_unidad="M";
								$li_canartaux=($li_canart*$li_unidad);
							}
							else
							{$ls_unidad="D";}
							if($ls_hidunidad=="Mayor")
							{
								$li_auxcanpenart=($li_canpenart*$li_unidad);
							}
							switch ($ls_unidad)
							{
								case "M":
									$ls_unidadaux="Mayor";
								break;
								case "D":
									$ls_unidadaux="Detal";
								break;
							}
							$lo_object[$li_i][1]="<input name=txtdenart".$li_i."     type=text   id=txtdenart".$li_i."    class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input name=txtcodart".$li_i."     type=hidden id=txtcodart".$li_i."    class=sin-borde size=15 maxlength=25   value='".$ls_codart."' readonly>".
												 "<input name=txtctagas".$li_i."     type=hidden id=txtctagas".$li_i."    class=sin-borde size=20 maxlength=50   value='".$ls_ctagas."' readonly>".
												 "<input name=txtctasep".$li_i."     type=hidden id=txtctasep".$li_i."    class=sin-borde size=20 maxlength=20 value='".$ls_ctasep."' readonly>";
							$lo_object[$li_i][2]="<input name=txtcodalm".$li_i."     type=text   id=txtcodalm".$li_i."    class=sin-borde size=13 maxlength=10 value='". $ls_codalm."' readonly>".
												 "<a href='javascript: ue_catalmacen(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
							$lo_object[$li_i][3]="<input name=txtunidad".$li_i."     type=text   id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='". $ls_unidadaux."' readonly></div>".
												 "<input name='hidunidad".$li_i."'    type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
							$lo_object[$li_i][4]="<input name=txtcansol".$li_i."     type=text   id=txtcansol".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canorisolsep,2,",",".")."' readonly>".
												 "<input name=hidexistencia".$li_i." type=hidden id=hidexistencia".$li_i."                                     value='". $li_existencia."'>";
							$lo_object[$li_i][5]="<input name=txtpenart".$li_i."     type=text   id=txtpenart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canpenart,2,",",".")."' readonly>";
							$lo_object[$li_i][6]="<input name=txtcanart".$li_i."     type=text   id=txtcanart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canart,2,",",".")."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_montosfactura(".$li_i.");'>";
							$lo_object[$li_i][7]="<input name=txtpreuniart".$li_i."  type=text   id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>".
												 "<input name=hidnumdocori".$li_i."  type=hidden id=hidnumdocori".$li_i.">";
							$lo_object[$li_i][8]="<input name=txtmontotart".$li_i."  type=text   id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' readonly>";

							if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
							{
								$lb_valido=$io_siv->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numorddes,$ls_codart,$ls_codalm,$ls_unidad,
																				$li_canorisolsep,$li_canartaux,$li_preuniart,$li_montotart, //monsubart
																				$li_montotart,$li_i,$ls_nummov,$ld_fecdesaux,
																				$ls_numsol,$li_auxcanpenart,$la_seguridad);
								if($lb_valido)
								{
									$lb_valido=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,$li_canartaux,
																						  $la_seguridad);

									if($lb_valido)
									{
										$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos($ls_codemp,$ls_codart,$la_seguridad);
									} // fin  if($lb_valido)->uf_sim_disminuir_articuloxalmacen
								} //fin  if($lb_valido)->uf_sim_insert_dt_despacho
								if($li_canartaux<$li_auxcanpenart)
								{
									$lb_descomp=false;
								}
							}//  fin if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
							else
							{
								$lb_descomp=false;
							}
							if(!$lb_valido)
							{$lb_exito=false;}
						}  // fin  for($li_i=1;$li_i<$li_totrows;$li_i++)
						if($li_value==1)
						{
							$lb_valido=uf_incluircontable($ls_codemp,$ls_numorddes,$ld_fecdesaux,$lo_objectc,
														  $li_totrowsc,$la_seguridad,$io_fun_inventario,$io_siv);
						}
					}  //fin  if($lb_valido) uf_sim_insert_movimiento
				}  //fin  if($lb_valido)
				if($lb_descomp)
				{
					$ls_estsep="D";
					$lb_valido=$io_siv->uf_sim_update_sep($ls_codemp,$ls_numsol,$ls_estsep);
				}
				else
				{
					$ls_estsep="L";
					$lb_valido=$io_siv->uf_sim_update_sep($ls_codemp,$ls_numsol,$ls_estsep);

				}
				if(!$lb_exito)
				{$lb_valido=false;}
				if($lb_valido)
				{
					$io_sql->commit();
					$io_msg->message("El despacho ha sido procesado");

				}
				else
				{
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar el despacho");
				}
			}
			else
			{
				$io_msg->message("El mes no esta abierto");
				$li_totrows=1;
				$li_totrowsc=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
				uf_agregarlineablancacontable($lo_objectc,1);
				uf_limpiarvariables();
			}

		break;

		case "BUSCARDETALLESOLICITUD":
			$ls_numorddes=  "";
			$ls_readonly=  $io_fun_inventario->uf_obtenervalor("hidreadonly","");
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ls_estsol=    $io_fun_inventario->uf_obtenervalor("txtestsol","");
			$li_totentsum="0,00";
			$data="";
			$li_totrows=0;
			$li_totrowsc=1;
			$ls_pendiente="";
 		    $ls_checkedcomp="";
			$ls_checkedparc="";
			$ls_readonlyrad="";
			$ld_fecdes1=$io_func->uf_convertirdatetobd($ld_fecdes);
			uf_agregarlineablancacontable($lo_objectc,1);
			if($ls_estsol=="L")
			{
				$lb_valido=$io_siv->uf_sim_obtener_dt_pendiente($ls_codemp,$ls_numsol,$li_totrows,$lo_object);
			}
			else
			{
				$lb_valido=$io_siv->uf_sim_obtener_dt_solicitud($ls_codemp,$ls_numsol,$li_totrows,$lo_object);
			}
			if (!$lb_valido)
			{
				uf_agregarlineablanca($lo_object,1);
				uf_agregarlineablancacontable($lo_objectc,1);
				uf_limpiarvariables();
				$io_msg->message("Debe definir una cuenta contable de gasto para los articulos de la solicitud");
				$li_totrows=1;
				/*$ls_checkedcomp="";
				$ls_checkedparc="";
				$ls_readonlyrad="";*/
			}
		break;

		case "BUSCARDETALLE":
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$li_totentsum=    $io_fun_inventario->uf_obtenervalor("txttotentsum","");
			$ls_checkedcomp="";
			$ls_checkedparc="";
			$li_totentsum="0,00";
			$lb_valido=$io_siv->uf_sim_obtener_dt_despacho($ls_codemp,$ls_numorddes,$li_totrows,$li_totentsum,$lo_object);
			if($lb_valido)
			{
				$lb_valido=$io_siv->uf_sim_obtener_dt_scg($ls_codemp,$ls_numorddes,$li_totrowsc,$lo_objectc);
			}
		break;

		case "CALCULARCONTABLE":
			uf_limpiarvariables();
			$ls_numsol=    $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_coduniadm= $io_fun_inventario->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_inventario->uf_obtenervalor("txtdenuniadm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ls_estsol=    $io_fun_inventario->uf_obtenervalor("txtestsol","");
			$ls_codunides= $io_fun_inventario->uf_obtenervalor("txtcodunides","");
			$ls_denunides= $io_fun_inventario->uf_obtenervalor("txtdenunides","");
			$li_totrowsc=0;
			$ls_estrec=  $io_fun_inventario->uf_obtenervalor("rdtipodespacho","");
			if($ls_estrec==0)
			{
				$ls_checkedparc="checked";
				$ls_checkedcomp="";
			}
			else
			{
				$ls_checkedparc="";
				$ls_checkedcomp="checked";
			}
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
				$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
				$ls_codalm=       $io_fun_inventario->uf_obtenervalor("txtcodalm".$li_i,"");
				$li_canorisolsep= $io_fun_inventario->uf_obtenervalor("txtcansol".$li_i,"");
				$li_existencia=   $io_fun_inventario->uf_obtenervalor("hidexistencia".$li_i,"");
				$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
				$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
				$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
				$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
				$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
				$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
				$ls_ctagas=       $io_fun_inventario->uf_obtenervalor("txtctagas".$li_i,"");
				$ls_ctasep=       $io_fun_inventario->uf_obtenervalor("txtctasep".$li_i,"");
				$li_canpenart=    $io_fun_inventario->uf_obtenervalor("txtpenart".$li_i,"");
				$li_hidpenart=    $io_fun_inventario->uf_obtenervalor("txthidpenart".$li_i,"");
				$li_canorisolsep= str_replace(".","",$li_canorisolsep);
				$li_canorisolsep= str_replace(",",".",$li_canorisolsep);
				$li_canart=       str_replace(".","",$li_canart);
				$li_canart=       str_replace(",",".",$li_canart);
				$li_preuniart=    str_replace(".","",$li_preuniart);
				$li_preuniart=    str_replace(",",".",$li_preuniart);
				$li_montotart=    str_replace(".","",$li_montotart);
				$li_montotart=    str_replace(",",".",$li_montotart);
				$li_canpenart=    str_replace(".","",$li_canpenart);
				$li_canpenart=    str_replace(",",".",$li_canpenart);
				if($ls_ctagas=="")
				{
					$li_totrowsc=1;
					$li_totrows=1;
					uf_agregarlineablanca($lo_object,1);
					uf_agregarlineablancacontable($lo_objectc,1);
					uf_limpiarvariables();
					uf_agregarlineablancacontable($lo_objectc,1);
					$lb_ok=false;
					$io_msg->message("Verifique que todos los articulos de la solicitud tengan cuenta contable de gasto asociada");
					break;
				}
				if($ls_unidad=="")
				{
					$ls_unidad= $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
					$ls_hidunidad= $io_fun_inventario->uf_obtenervalor("hidtxtuni".$li_i,"");
				}
				if($ls_unidad=="Mayor")
				{$ls_unidad="M";}
				else
				{$ls_unidad="D";}
				switch ($ls_unidad)
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				$lo_object[$li_i][1]="<input name=txtdenart".$li_i."     type=text   id=txtdenart".$li_i."    class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
									 "<input name=txtcodart".$li_i."     type=hidden id=txtcodart".$li_i."    class=sin-borde size=15 maxlength=25 value='".$ls_codart."' readonly>".
								     "<input name=txtctagas".$li_i."     type=hidden id=txtctagas".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_ctagas."' readonly>".
								     "<input name=txtctasep".$li_i."     type=hidden id=txtctasep".$li_i."    class=sin-borde size=20 maxlength=20 value='".$ls_ctasep."' readonly>";
				$lo_object[$li_i][2]="<input name=txtcodalm".$li_i."     type=text   id=txtcodalm".$li_i."    class=sin-borde size=13 maxlength=10 value='". $ls_codalm."' readonly>".
								     "<a href='javascript: ue_catalmacen(".$li_i.");'><img src='../shared/imagebank/tools20/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
				$lo_object[$li_i][3]="<input name=txtunidad".$li_i."     type=text   id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='". $ls_unidadaux."' style='text-align:center' readonly></div>".
									 "<input name=hidtxtuni".$li_i."     type=hidden id=hidtxtuni".$li_i."                                         value='". $ls_hidunidad ."'>".
									 "<input name=hidunidad".$li_i."     type=hidden id=hidunidad".$li_i."                                         value='". $li_unidad ."'>";
				$lo_object[$li_i][4]="<input name=txtcansol".$li_i."     type=text   id=txtcansol".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canorisolsep,2,",",".")."' style='text-align:right' readonly>".
									 "<input name=hidexistencia".$li_i." type=hidden id=hidexistencia".$li_i."                                     value='". $li_existencia."'>";
				$lo_object[$li_i][5]="<input name=txtpenart".$li_i."     type=text   id=txtpenart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canpenart,2,",",".")."'  style='text-align:right' readonly>".
 								     "<input name=txthidpenart".$li_i."  type=hidden id=txthidpenart".$li_i." class=sin-borde size=12 value='".$li_hidpenart."'>";
				$lo_object[$li_i][6]="<input name=txtcanart".$li_i."     type=text   id=txtcanart".$li_i."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_canart,2,",",".")."'    style='text-align:right' onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_montosfactura(".$li_i.");'>";
				$lo_object[$li_i][7]="<input name=txtpreuniart".$li_i."  type=text   id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' style='text-align:right' readonly>".
									 "<input name=hidnumdocori".$li_i."  type=hidden id=hidnumdocori".$li_i.">";
				$lo_object[$li_i][8]="<input name=txtmontotart".$li_i."  type=text   id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";

				if($li_canart>0)
				{
					$li_totrowsc=$li_totrowsc + 1;
					$ls_debhab="D";
					$lo_objectc[$li_totrowsc][1]="<input  name=txtdenartc".$li_totrowsc."  type=text   id=txtdenartc".$li_totrowsc."  class=sin-borde size=50 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input  name=txtcodartc".$li_totrowsc."  type=hidden id=txtcodartc".$li_totrowsc."  class=sin-borde size=30 maxlength=50 value='".$ls_codart."' readonly>";
					$lo_objectc[$li_totrowsc][2]="<input  name=txtsccuenta".$li_totrowsc." type=text   id=txtsccuenta".$li_totrowsc." class=sin-borde size=30              value='".$ls_ctasep."' readonly>";
					$lo_objectc[$li_totrowsc][3]="<input  name=txtdebhab".$li_totrowsc."   type=text   id=txtdebhab".$li_totrowsc."   class=sin-borde size=15              value='".$ls_debhab."' readonly style='text-align:center'>";
					$lo_objectc[$li_totrowsc][4]="<input  name=txtmonto".$li_totrowsc."    type=text   id=txtcansolc".$li_totrowsc."  class=sin-borde size=30              value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";


					$li_totrowsc=$li_totrowsc + 1;
					$ls_debhab="H";
					$lo_objectc[$li_totrowsc][1]="<input  name=txtdenartc".$li_totrowsc."  type=text   id=txtdenartc".$li_totrowsc."  class=sin-borde size=50 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input  name=txtcodartc".$li_totrowsc."  type=hidden id=txtcodartc".$li_totrowsc."  class=sin-borde size=30 maxlength=50 value='".$ls_codart."' readonly>";
					$lo_objectc[$li_totrowsc][2]="<input  name=txtsccuenta".$li_totrowsc." type=text   id=txtsccuenta".$li_totrowsc." class=sin-borde size=30              value='".$ls_ctagas."' readonly>";
					$lo_objectc[$li_totrowsc][3]="<input  name=txtdebhab".$li_totrowsc."   type=text   id=txtdebhab".$li_totrowsc."   class=sin-borde size=15              value='".$ls_debhab."' readonly style='text-align:center'>";
					$lo_objectc[$li_totrowsc][4]="<input  name=txtmonto".$li_totrowsc."    type=text   id=txtcansolc".$li_totrowsc."  class=sin-borde size=30              value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";
				}
				$ls_ok=true;
			}
		break;

		case "AGREGARDETALLE":

			$ls_numsol= $io_func->uf_cerosizquierda($io_fun_inventario->uf_obtenervalor("txtnumsol",""),15);
		    uf_titulossalida();
			$ls_tiporeq = "";
			$ls_tiposal = "checked";
			$li_totrows=$li_totrows+1;

			//$ls_numordcom= $io_fun_inventario->uf_obtenervalor("txtnumordcom","");
			$ls_numorddes= $ls_numsol;
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			//$li_totentsum=    $io_fun_inventario->uf_obtenervalor("txttotentsum","");
			//print $li_totentsum;

			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_unidad= uf_obtenervalorunidad($li_i);
				$li_unidad=    $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
				$ls_codart=    $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
				$ls_denart=    $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
				$ls_nomprov=   $io_fun_inventario->uf_obtenervalor("txtnomprov".$li_i,"");
				$ls_codprov=   $io_fun_inventario->uf_obtenervalor("txtcodprov".$li_i,"");
				$li_canart=    $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
				$li_preuniart= $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
				$li_montotart= $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");

				$li_montotartaux=    str_replace(".","",$li_montotart);
				$li_montotartaux=    str_replace(",",".",$li_montotartaux);
				$li_totentsum=$li_totentsum+$li_montotartaux;
				switch ($ls_unidad)
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}


					$lo_object[$li_i][1]="<input name=txtdenart".$li_i."    type=text id=txtdenart".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
										 "<input name=txtcodart".$li_i."    type=text  id=txtcodart".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_i][2]="<input name=txtcodprov".$li_i."    type=text id=txtcodprov".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_codprov."' readonly>".
										 "<input name=txtnomprov".$li_i."    type=text  id=txtnomprov".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_nomprov."' readonly>";
					$lo_object[$li_i][3]="<input name=txtunidad".$li_i."    type=text id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$li_i."' type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
					$lo_object[$li_i][4]="<input name=txtcanart".$li_i."    type=text id=txtcanart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' readonly>";
					$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
					$lo_object[$li_i][6]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
					$lo_object[$li_i][7]="";
					$lo_object[$li_i][8]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

					//print $li_totentsum;
			}
			$li_totentsum=number_format($li_totentsum,2,',','.');
			//$li_totentsum=    str_replace(".","",$li_totentsum);
			//	$li_totentsum=    str_replace(",",".",$li_totentsum);
			//$li_totentsum=str_replace(",",".",$li_totentsum);
			//$li_totentsum=number_format($li_totentsum,2,',','.');

			uf_agregarlineasalida($lo_object,$li_totrows);
		break;

		case "GUARDARSALIDA":
			uf_titulossalida();
			$ls_tiporeq = "";
			$ls_tiposal = "checked";

			$ls_numsol= $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_codtiend=    $io_fun_inventario->uf_obtenervalor("txtcodtiend","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm= $io_fun_inventario->uf_obtenervalor("txtnomfisalm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ld_fecdesaux= $io_func->uf_convertirdatetobd($ld_fecdes);
			$ls_estrevdes= "1";
			$ls_estdes=    "1";
			$lb_valido=true;
			$li_totentsum=$io_fun_inventario->uf_obtenervalor("txttotentsum","");

			$ls_coduniadm=$_SESSION["ls_coduniad"];
			$io_sql->begin_transaction();
			$_SESSION["ls_tiposal"]="SAL";
			$lb_valido=$io_siv->uf_sim_insert_despacho($ls_codemp,$ls_numorddes,$ls_numsol,$ls_codtiend,$ls_coduniadm,$ld_fecdesaux,$ls_obsdes,
													   $ls_logusr,$ls_estdes,$ls_estrevdes,$ls_codalm/*,$la_seguridad*/);

			if($lb_valido){

				$ls_nummov=0;
				$ls_nomsol="Despacho";
				$lb_valido=$io_siv->io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecdesaux,$ls_nomsol,$ls_logusr,
																	  $la_seguridad,$ls_codtiend);

				if($lb_valido){

					$lb_exito=true;
					for($li_i=1;$li_i<$li_totrows;$li_i++)
					{
						if($lb_exito){
							$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
							$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
							$ls_nomprov=       $io_fun_inventario->uf_obtenervalor("txtnomprov".$li_i,"");
							$ls_codprov=       $io_fun_inventario->uf_obtenervalor("txtcodprov".$li_i,"");

							$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
							$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
							$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
							$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
							$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
							$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");

							if($ls_unidad=="Mayor")
							{
								$ls_unidad="M";
								//$li_canartaux=($li_canart*$li_unidad);
							}
							else
							{$ls_unidad="D";}

							switch ($ls_unidad)
							{
								case "M":
									$ls_unidadaux="Mayor";
								break;
								case "D":
									$ls_unidadaux="Detal";
								break;
							}

							$lo_object[$li_i][1]="<input name=txtdenart".$li_i."    type=text id=txtdenart".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
												 "<input name=txtcodart".$li_i."    type=text  id=txtcodart".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catarticulo(".$li_i.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
							$lo_object[$li_i][2]="<input name=txtcodprov".$li_i."    type=text id=txtcodprov".$li_i."    class=sin-borde size=20 maxlength=50 value='".$ls_codprov."' readonly>".
										 		"<input name=txtnomprov".$li_i."    type=text  id=txtnomprov".$li_i."  class=sin-borde size=20 maxlength=20 value='".$ls_nomprov."' readonly>";
							$lo_object[$li_i][3]="<input name=txtunidad".$li_i."    type=text id=txtunidad".$li_i."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$li_i."' type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
							$lo_object[$li_i][4]="<input name=txtcanart".$li_i."    type=text id=txtcanart".$li_i."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' readonly>";
							$lo_object[$li_i][5]="<input name=txtpreuniart".$li_i." type=text id=txtpreuniart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
							$lo_object[$li_i][6]="<input name=txtmontotart".$li_i." type=text id=txtmontotart".$li_i." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
							$lo_object[$li_i][7]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
							$lo_object[$li_i][8]="";

							$li_canart=       str_replace(".","",$li_canart);
							$li_canart=       str_replace(",",".",$li_canart);
							$li_preuniart=    str_replace(".","",$li_preuniart);
							$li_preuniart=    str_replace(",",".",$li_preuniart);
							$li_montotart=    str_replace(".","",$li_montotart);
							$li_montotart=    str_replace(",",".",$li_montotart);
							$li_canartaux=$li_canart;

							if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
							{
								$ls_numdocori="";
								$ls_opeinv="SAL";
								$ls_promov="DES";
								$ls_codprodoc="SAL";
								$li_candesart="0.00";
								$lb_valido=$io_siv->io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecdesaux,
																					  $ls_codart,$ls_codalm,$ls_opeinv,$ls_codprodoc,
																					  $ls_numsol,$li_canart,$li_preuniart,$ls_promov,
																					  $ls_numorddes,$li_canart,$ld_fecdesaux,$la_seguridad,$ls_codtiend,$ls_codprov);

								if($lb_valido)
								{
									//$li_i=1;
									$lb_valido=$io_siv->uf_sim_insert_dt_despacho($ls_codemp,$ls_numorddes,$ls_codart,$li_i,$ls_codtiend,$ls_codprov,$ls_codalm,
																				$ls_unidad,$li_canart,$li_canart,$li_preuniart,
																				$li_montotart,$li_montotart,$li_i,0/*,$la_seguridad*/);
							    }

								if($lb_valido)
								{
									$lb_valido=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,$li_canartaux,
																						  $ls_codprov,$ls_codtiend,$la_seguridad);

									if($lb_valido)
									{
										//$io_sql->commit();
										//$io_sql->begin_transaction();
										//$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos_alm($ls_codemp,$ls_codart,$ls_codtiend,$ls_codprov,$ls_codalm,$la_seguridad);
									} // fin  if($lb_valido)->uf_sim_disminuir_articuloxalmacen
								} //fin  if($lb_valido)->uf_sim_insert_dt_despacho

							}//  fin if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))

							if(!$lb_valido)
							{
								$lb_exito=false;
								//$li_i=$li_totrows;
							}

						}

					}  // fin  for($li_i=1;$li_i<$li_totrows;$li_i++)

					if($lb_exito){
						$li_totrows=$li_totrows-1;
						$io_sql->commit();

						$io_msg->message("Se proceso la Salida de los articulos correctamente!");

						print("<script language=JavaScript>");
						print("pagina='../sim/sigesp_sim_p_despacho.php';");
	   					print(" location.href='../sfc/sigesp_sfc_d_liberar.php?pagina='+pagina;");
	    				print("</script>");


					}
					else
					{
						$_SESSION["ls_tiposal"]="";
						$io_sql->rollback();
						//$li_totrows=    $io_fun_inventario->uf_obtenervalor("totalfilas","");
						//print $li_totrows;
						uf_agregarlineasalida($lo_object,$li_totrows);
						$io_msg->message("No se pudo procesar el despacho!!");
					}

				}else
				{
					$_SESSION["ls_tiposal"]="";
					$io_sql->rollback();
					//$li_totrows=    $io_fun_inventario->uf_obtenervalor("totalfilas","");
					uf_agregarlineasalida($lo_object,$li_totrows);
					$io_msg->message("No se pudo procesar el despacho");
				}

			}else{
				$_SESSION["ls_tiposal"]="";
				$io_sql->rollback();
				//$li_totrows=    $io_fun_inventario->uf_obtenervalor("totalfilas","");
				uf_agregarlineasalida($lo_object,$li_totrows);
				$io_msg->message("No se pudo procesar el despacho");
			}

		break;

		case "BUSCARDETSALIDA":

			uf_titulossalida();
			$ls_lectura = "readonly";
			$ls_tiporeq = "";
			$ls_tiposal = "checked";
			$ls_checkedcomp = "checked";
			$ls_checkedparc = "";

			$ls_numsol = $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes = $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm = $_SESSION["ls_coduniad"];
			$ls_codalm = $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm = $io_alm->uf_sim_select_nombrealmacen($ls_codemp,$ls_codalm);
			$ld_fecdes =    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_obsdes =    $io_fun_inventario->uf_obtenervalor("txtobsdes","");

			$ls_totsalida = 0;
			$io_siv->uf_sim_obtener_dt_salida($ls_codemp,$ls_numorddes,$li_totrows,$lo_object,$ls_totsalida);
			$li_totentsum = number_format($ls_totsalida,2,",",".");

		break;

		case "ELIMINARSALIDA":

			require_once("sigesp_sim_c_revdespacho.php");
			$io_revdes=  new sigesp_sim_c_revdespacho();

			uf_titulossalida();
			$ls_lectura="readonly";
			$ls_tiporeq = "";
			$ls_tiposal = "checked";
			$ls_checkedcomp="checked";
			$ls_checkedparc="";

			$ls_numsol= $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm=$_SESSION["ls_coduniad"];
			$ls_codtiend=    $io_fun_inventario->uf_obtenervalor("txtcodtiend","");
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			//$ls_nomfisalm = $io_alm->uf_sim_select_nombrealmacen($ls_codemp,$ls_codalm);
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");

			$ld_fecdesaux= $io_func->uf_convertirdatetobd($ld_fecdes);
			$ls_estrevdes= "0";
			$lb_valido=true;

			$io_sql->begin_transaction();

			$lb_valido=$io_revdes->uf_sim_update_status_despacho($ls_codemp,$ls_numorddes,$ls_numsol,$ls_codtiend,$la_seguridad);

			if($lb_valido){

				$ls_nummov=0;
				$ls_nomsol="Reverso";
				$lb_valido=$io_siv->io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecdesaux,$ls_nomsol,$ls_logusr,
																	  $la_seguridad,$ls_codtiend);

				if($lb_valido){

					$lb_exito=true;
					for($li_i=1;$li_i<=$li_totrows;$li_i++)
					{
						if($lb_exito){
							$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
							$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
							$ls_codprov=       $io_fun_inventario->uf_obtenervalor("txtcodprov".$li_i,"");
							$ls_nomprov=       $io_fun_inventario->uf_obtenervalor("txtnomprov".$li_i,"");

							$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
							$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
							$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
							$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
							$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
							$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");

							if($ls_unidad=="Mayor")
							{
								$ls_unidad="M";
								//$li_canartaux=($li_canart*$li_unidad);
							}
							else
							{$ls_unidad="D";}

							switch ($ls_unidad)
							{
								case "M":
									$ls_unidadaux="Mayor";
								break;
								case "D":
									$ls_unidadaux="Detal";
								break;
							}

							$li_canart=       str_replace(".","",$li_canart);
							$li_canart=       str_replace(",",".",$li_canart);
							$li_preuniart=    str_replace(".","",$li_preuniart);
							$li_preuniart=    str_replace(",",".",$li_preuniart);
							$li_montotart=    str_replace(".","",$li_montotart);
							$li_montotart=    str_replace(",",".",$li_montotart);
							$li_canartaux=$li_canart;

							if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
							{
								$ls_numdocori="";
								$ls_opeinv="ENT";
								$ls_promov="REV";
								$ls_codprodoc="SAL";
								$li_candesart="0.00";
								$lb_valido=$io_siv->io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecdesaux,
																					  $ls_codart,$ls_codalm,$ls_opeinv,$ls_codprodoc,
																					  $ls_numsol,$li_canart,$li_preuniart,$ls_promov,
																					  $ls_numorddes,$li_canart,$ld_fecdesaux,$la_seguridad,$ls_codtiend,$ls_codprov);

								if($lb_valido)
								{
									$lb_valido=$io_recepcion->uf_sim_insert_recepcion($ls_codemp,$ls_numsol,$ls_codprov,$ls_codalm,$ld_fecdesaux,"Entrada por Reverso de Despacho",$ls_logusr,
	                                 1,1,$ls_numconrec,$ls_codtiend,$la_seguridad);

	                                 if($lb_valido){
	                                 	$lb_valido=$io_recepcion->uf_sim_insert_dt_recepcion($ls_codemp,$ls_numsol,$ls_codart,$ls_unidad,$li_canart,0,$li_preuniart,
	                                    $li_montotart,$li_montotart,1,$li_canart,$ls_numconrec,$ls_codtiend,$ls_codprov,$la_seguridad);
	                                    if(!$lb_valido){
		                                 	$lb_exito=false;
											$io_sql->rollback();
											$io_msg->message("No se pudo procesar la Eliminacion de Salida");
		                                 }
	                                 }else{
	                                 	$lb_exito=false;
										$io_sql->rollback();
										$io_msg->message("No se pudo procesar la Eliminacion de Salida");
	                                 }

								} // fin  if($lb_valido)->uf_sim_aumentar_articuloxalmacen
								else{
									$lb_exito=false;
									$io_sql->rollback();
									$io_msg->message("No se pudo procesar la Eliminacion de Salida");
								}
							}

						}
					}// for para insertar dt_movimiento

				}else{
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar la Eliminacion de Salida");
				}

				if($lb_valido){

					$lb_valido=$io_revdes->uf_sim_update_articulos_alm($ls_codemp,$ls_codalm,$ls_numorddes,$ls_codtiend,$la_seguridad);

					if($lb_valido){

						$io_sql->commit();
						$io_msg->message("Eliminacion de Salida se proceso correctamente");

						print("<script language=JavaScript>");
						print("pagina='../sim/sigesp_sim_p_despacho.php';");
		   				print(" location.href='../sfc/sigesp_sfc_d_liberar.php?pagina='+pagina;");
		    			print("</script>");

						uf_limpiarvariables();
						$ls_codalm= "";
						$ls_nomfisalm ="";
						$ls_lectura="";
						uf_agregarlineasalida($lo_object,1);
					}else{
						$io_sql->rollback();
						$io_msg->message("No se pudo procesar la Eliminacion de Salida");
					}
				}
				else
				{
					//$_SESSION["ls_tiposal"]="";
					$io_sql->rollback();
					$io_msg->message("No se pudo procesar la Eliminacion de Salida");
				}
			}
			else
			{
				//$_SESSION["ls_tiposal"]="";
				$io_sql->rollback();
				$io_msg->message("No se pudo procesar la Eliminacion de Salida");
			}

		break;

		case "ELIMINARDETALLE":

			require_once("sigesp_sim_c_revdespacho.php");
			$io_revdes=  new sigesp_sim_c_revdespacho();


			$ls_readonly="";
			uf_titulossalida();
			$ls_lectura="readonly";
			$ls_tiporeq = "";
			$ls_tiposal = "checked";
			$ls_checkedcomp="checked";
			$ls_checkedparc="";


			$ls_numsol = $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_numorddes = $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm = $_SESSION["ls_coduniad"];
			$ls_codalm = $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_nomfisalm = $io_alm->uf_sim_select_nombrealmacen($ls_codemp,$ls_codalm);
			$ld_fecdes = $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_obsdes = $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$li_totrows = $io_fun_inventario->uf_obtenervalor("totalfilas","");

			$li_totrows = $li_totrows-1;
			$li_rowdelete= $io_fun_inventario->uf_obtenervalor("filadelete","");

			$li_temp=0;

			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{
					$li_temp=$li_temp+1;
					$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$li_i,"");
					$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$li_i,"");
					$ls_nomprov=       $io_fun_inventario->uf_obtenervalor("txtnomprov".$li_i,"");
					$ls_codprov=       $io_fun_inventario->uf_obtenervalor("txtcodprov".$li_i,"");

					$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$li_i,"");
					$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$li_i,"");
					$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$li_i,"");
					$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$li_i,"");
					$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$li_i,"");
					$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$li_i,"");
					
					$li_montotartaux=    str_replace(".","",$li_montotart);					$li_montotartaux=    str_replace(",",".",$li_montotartaux);
					$li_totentsum=$li_totentsum+$li_montotartaux;

					if($ls_unidad=="Mayor")
					{
						$ls_unidad="M";
						//$li_canartaux=($li_canart*$li_unidad);
					}
					else
					{$ls_unidad="D";}

					switch ($ls_unidad)
					{
						case "M":
							$ls_unidadaux="Mayor";
						break;
						case "D":
							$ls_unidadaux="Detal";
						break;
					}

					$lo_object[$li_temp][1]="<input name=txtdenart".$li_temp."    type=text id=txtdenart".$li_temp."    class=sin-borde size=20 maxlength=50 value='".$ls_denart."' readonly>".
										 "<input name=txtcodart".$li_temp."    type=text  id=txtcodart".$li_temp."  class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly><a href='javascript: ue_catarticulo(".$li_temp.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
					$lo_object[$li_temp][2]="<input name=txtcodprov".$li_temp."    type=text id=txtcodprov".$li_temp."    class=sin-borde size=20 maxlength=50 value='".$ls_codprov."' readonly>".
										 "<input name=txtnomprov".$li_temp."    type=text  id=txtnomprov".$li_temp."  class=sin-borde size=20 maxlength=20 value='".$ls_nomprov."' readonly>";
					$lo_object[$li_temp][3]="<input name=txtunidad".$li_temp."    type=text id=txtunidad".$li_temp."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$li_temp."' type='hidden' id='hidunidad".$li_i."' value='". $li_unidad ."'>";
					$lo_object[$li_temp][4]="<input name=txtcanart".$li_temp."    type=text id=txtcanart".$li_temp."    class=sin-borde size=10 maxlength=12 value='".$li_canart."' readonly>";
					$lo_object[$li_temp][5]="<input name=txtpreuniart".$li_temp." type=text id=txtpreuniart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_preuniart."' readonly>";
					$lo_object[$li_temp][6]="<input name=txtmontotart".$li_temp." type=text id=txtmontotart".$li_temp." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
					$lo_object[$li_temp][7]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][8]="";

				}
			}
			$li_totentsum=number_format($li_totentsum,2,',','.');
			if ($li_temp==0)
			{
				$li_totrows=1;
				uf_agregarlineasalida($lo_object,$li_totrows);
			}
			else
			{
				uf_agregarlineasalida($lo_object,$li_temp+1);
			}

		break;

		case "ELIMINARITEM":
			require_once("sigesp_sim_c_revdespacho.php");
			$io_revdes=  new sigesp_sim_c_revdespacho();

			//print "ELIMINAR-ITEM";
			uf_titulossalida();
			$ls_lectura="readonly";
			$ls_tiporeq = "";
			$ls_tiposal = "checked";
			$ls_checkedcomp="checked";
			$ls_checkedparc="";

			$ls_numsol= $io_fun_inventario->uf_obtenervalor("txtnumsol","");
			$ls_status= $io_fun_inventario->uf_obtenervalor("hidestatus2","");
			$ls_numorddes= $io_fun_inventario->uf_obtenervalor("txtnumorddes","");
			$ls_coduniadm=$_SESSION["ls_coduniad"];
			$ls_codalm=    $io_fun_inventario->uf_obtenervalor("txtcodalm","");
			$ls_codtiend=    $io_fun_inventario->uf_obtenervalor("txtcodtiend","");
			$ld_fecdes=    $io_fun_inventario->uf_obtenervalor("txtfecdes","");
			$ls_nomfisalm = $io_alm->uf_sim_select_nombrealmacen($ls_codemp,$ls_codalm);
			$ls_obsdes=    $io_fun_inventario->uf_obtenervalor("txtobsdes","");
			$ld_fecdesaux= $io_func->uf_convertirdatetobd($ld_fecdes);

			$ls_eliminar= $io_fun_inventario->uf_obtenervalor("filadelete","");

			$io_sql->begin_transaction();
			$lb_valido=true;

			$ls_nummov=0;
			$ls_nomsol="Reverso";
			$lb_valido=$io_siv->io_mov->uf_sim_insert_movimiento($ls_nummov,$ld_fecdesaux,$ls_nomsol,$ls_logusr,$la_seguridad,$ls_codtiend);

			if($lb_valido){

				$ls_codart=       $io_fun_inventario->uf_obtenervalor("txtcodart".$ls_eliminar,"");
				$ls_denart=       $io_fun_inventario->uf_obtenervalor("txtdenart".$ls_eliminar,"");
				$ls_codprov=       $io_fun_inventario->uf_obtenervalor("txtcodprov".$ls_eliminar,"");
				$ls_nomprov=       $io_fun_inventario->uf_obtenervalor("txtnomprov".$ls_eliminar,"");
				$li_canart=       $io_fun_inventario->uf_obtenervalor("txtcanart".$ls_eliminar,"");
				$li_preuniart=    $io_fun_inventario->uf_obtenervalor("txtpreuniart".$ls_eliminar,"");
				$li_montotart=    $io_fun_inventario->uf_obtenervalor("txtmontotart".$ls_eliminar,"");
				$ls_unidad=       $io_fun_inventario->uf_obtenervalor("cmbunidad".$ls_eliminar,"");
				$ls_hidunidad=    $io_fun_inventario->uf_obtenervalor("txtunidad".$ls_eliminar,"");
				$li_unidad=       $io_fun_inventario->uf_obtenervalor("hidunidad".$ls_eliminar,"");

				if($ls_unidad=="Mayor")
				{
					$ls_unidad="M";
					//$li_canartaux=($li_canart*$li_unidad);
				}
				else
				{$ls_unidad="D";}

				switch ($ls_unidad)
				{
					case "M":
						$ls_unidadaux="Mayor";
					break;
					case "D":
						$ls_unidadaux="Detal";
					break;
				}

				$li_canart=       str_replace(".","",$li_canart);
				$li_canart=       str_replace(",",".",$li_canart);
				$li_preuniart=    str_replace(".","",$li_preuniart);
				$li_preuniart=    str_replace(",",".",$li_preuniart);
				$li_montotart=    str_replace(".","",$li_montotart);
				$li_montotart=    str_replace(",",".",$li_montotart);
				$li_canartaux=$li_canart;

				if(($ls_codalm!="")&&($li_canart!="")&&($li_canart>0))
				{
					$ls_numdocori="";
					$ls_opeinv="ENT";
					$ls_promov="REV";
					$ls_codprodoc="SAL";
					$li_candesart="0.00";
					$lb_valido=$io_siv->io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ld_fecdesaux,
																		  $ls_codart,$ls_codalm,$ls_opeinv,$ls_codprodoc,
																		  $ls_numsol,$li_canart,$li_preuniart,$ls_promov,
																		  $ls_numorddes,$li_canart,$ld_fecdesaux,$la_seguridad,$ls_codtiend,$ls_codprov);

					if($lb_valido){

						$lb_valido=$io_siv->uf_sim_delete_dt_despacho($ls_codemp,$ls_numorddes,$ls_codalm,$ls_codart,$ls_codprov,$ls_codtiend);

						if($lb_valido){

							//esto es lo de recepcion
							$lb_valido=$io_recepcion->uf_sim_insert_recepcion($ls_codemp,$ls_numsol,$ls_codprov,$ls_codalm,$ld_fecdes,"Entrada por Reverso de Despacho",$ls_logusr,
	                                 1,1,$ls_numconrec,$ls_codtiend,$la_seguridad);

	                        if($lb_valido){

	                        	$lb_valido=$io_recepcion->uf_sim_insert_dt_recepcion($ls_codemp,$ls_numsol,$ls_codart,$ls_unidad,$li_canart,0,$li_preuniart,
	                                    $li_montotart,$li_montotart,1,$li_canart,$ls_numconrec,$ls_codtiend,$ls_codprov,$la_seguridad);

	                             if($lb_valido){

	                             	/********** ESTO YA ESTABA DESDE AQUI ***************/
	                             	$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codart,$ls_codalm,$li_canartaux,$la_seguridad,$ls_codprov,$ls_codtiend);

									if($lb_valido)
									{
										/*$io_sql->commit();
										$io_sql->begin_transaction();
										$lb_valido=$io_art->uf_sim_actualizar_cantidad_articulos_alm($ls_codemp,$ls_codart,$ls_codalm,$la_seguridad);*/
										if($lb_valido){
											$io_sql->commit();
											$io_msg->message("Articulo Eliminado de la Salida correctamente");
										}else{
											$io_sql->rollback();
											$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
										}
									} // fin  if($lb_valido)->uf_sim_aumentar_articuloxalmacen
									else{
										$io_sql->rollback();
										$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
									}
									/********** ESTO YA ESTABA HASTA AQUI ***************/

	                             }else{
	                             	$io_sql->rollback();
									$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
	                             }

	                        }else{
	                        	$io_sql->rollback();
								$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
	                        }


						}else{
							$io_sql->rollback();
							$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
						}

					}else{
						$io_sql->rollback();
						$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
					}
				}

			}else{
				$io_sql->rollback();
				$io_msg->message("No se pudo procesar la Eliminacion de Salida del articulo");
			}

			$ls_totsalida = 0;
			$io_siv->uf_sim_obtener_dt_salida($ls_codemp,$ls_numorddes,$li_totrows,$lo_object,$ls_totsalida);
			$li_totentsum = number_format($ls_totsalida,2,",",".");

		break;

	}
?>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Inventario</span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <? if($ls_tipo=="Salida")
	{ ?>
    	<td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar_sal();"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" border="0" alt="Eliminar"></a></div></td>
    <? } ?>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="676" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="744"><?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_inventario->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_inventario);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?></td>
    </tr>
    <tr>
      <td><table width="654" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="4" class="titulo-ventana">Despacho de Suministros </td>
          </tr>
          <tr class="formato-blanco">
            <td width="132" height="19">&nbsp;</td>
            <td width="139">
            	<input name="hidestatus2" type="hidden" id="hidestatus2" value="<?php print $ls_status?>">
                <input name="hidreadonly" type="hidden" id="hidreadonly">
                <input name="txtnumorddes" type="hidden" id="txtnumorddes" value="<?php print $ls_numorddes ?>" size="15" maxlength="15" readonly>
                <input name="contable" type="hidden" id="contable" value="<?php print $li_value; ?>">
                <input name="hidok" type="hidden" id="hidok" value="<?php print $ls_ok ?>"></td>
            <td width="270"><div align="right">Fecha</div></td>
            <td width="111"><input name="txtfecdes" type="text" id="txtfecdes" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecdes ?>" size="17" maxlength="10" datepicker="true" style="text-align:center "></td>
          </tr>
          <tr>
          	<td height="20">&nbsp;</td>
          	<td height="22"><input name="rdtiposal" type="radio" id="rdtiposal" value="1" onClick="ue_salida('Requisicion');" <?php print $ls_tiporeq ?> >Requisici&oacute;n <input name="rdtiposal" type="radio" id="rdtiposal" value="0" onClick="ue_salida('Salida');" <?php print $ls_tiposal ?> >Salida</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr class="formato-blanco">
            <td height="20"><div align="right">N&uacute;mero de la Solicitud</div></td>
            <td height="22"><input name="txtnumsol" type="text" id="txtnumsol" value="<?php print $ls_numsol; ?>" size="22" maxlength="15" style="text-align:center " <? print $ls_lectura;?> onKeyPress="return keyRestrict(event, '1234567890'+'abcdefghijklmnopqrstuvwxyz');" onBlur="javascript: ue_rellenarcampo(this,'15')" ></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr class="formato-blanco">
            <td height="20"><div align="right">Unidad Operativa de Suministro</div></td>
            <td height="22" colspan="2">
            	<input name="txtcodtiend" type="text" id="txtcodtiend" value="<?php print $ls_codtiend; ?>" size="5" maxlength="5" style="text-align:center " readonly >
            	&nbsp;<input name="txtdentiend" type="text" class="sin-borde" id="txtdentiend" value="<?php print $ls_dentiend; ?>" size="50" readonly>
            </td>
            <td>&nbsp;</td>
          </tr>

          <?php if($ls_tipo!="Salida"){ ?>
          	<tr class="formato-blanco">
            <td height="20"><div align="right">Unidad Solicitante </div></td>
            <td height="22" colspan="2"><input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm; ?>" size="15" maxlength="10" style="text-align:center " readonly>
              <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="50" readonly>            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Unidad a Despachar </div></td>
            <td height="22" colspan="3"><div align="left">
                <input name="txtcodunides" type="text" id="txtcodunides" value="<?php print $ls_codunides; ?>" size="15" maxlength="10" style="text-align:center" readonly>
                <a href="javascript: ue_cataunidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenunides" type="text" class="sin-borde" id="txtdenunides" value="<?php print $ls_denunides; ?>" size="50" readonly>
                <input name="txtcodalm" type="hidden" id="txtcodalm" value="<?php print $ls_codalm; ?>" size="15" maxlength="10" style="text-align:center " readonly>
                <input name="txtnomfisalm" type="hidden" class="sin-borde" id="txtnomfisalm" value="<?php print $ls_nomfisalm; ?>" size="50" readonly>
            </div></td>
          </tr>
          <? } else{ ?>
          	<tr class="formato-blanco">
            <td height="20"><div align="right">Almac&eacute;n </div></td>
            <td height="22" colspan="2"><input name="txtcodalm" type="text" id="txtcodalm" value="<?php print $ls_codalm; ?>" size="15" maxlength="10" style="text-align:center " readonly>
              <a href="javascript: ue_catalmacen2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtnomfisalm" type="text" class="sin-borde" id="txtnomfisalm" value="<?php print $ls_nomfisalm; ?>" size="50" readonly>            </td>
            <td>&nbsp;</td>
          </tr>
          <? }?>

          <tr class="formato-blanco">
            <td height="20"><div align="right">Observaci&oacute;n</div></td>
            <td colspan="3" rowspan="2"><textarea name="txtobsdes" cols="70" rows="3" id="textarea" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz����� ()@#!%/[]*-+_');"><?php print $ls_obsdes; ?></textarea></td>
          </tr>
          <tr class="formato-blanco">
            <td height="20"><div align="right"></div></td>
          </tr>
          <tr class="formato-blanco">
            <td height="17">&nbsp;</td>
            <td colspan="3" align="left">
                <input name="rdtipodespacho" type="radio" class="sin-borde" value="1"  onClick="ue_completa();" <?php print $ls_checkedcomp; ?>>
              Completa
              <input name="rdtipodespacho" type="radio" class="sin-borde" value="0"  onClick="ue_parcial();" <?php print $ls_checkedparc; ?>>
            Parcial
            <input name="txtestsol" type="hidden" id="txtestsol" value="<?php print $ls_estsol; ?>"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="28" colspan="4"><?php
					$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				?>
            <p>&nbsp;</p></td>
          </tr>
		  <?php
		  	if($li_value==1)
			{
		   ?>
          <tr class="formato-blanco">
            <td height="28" colspan="4"><input name="btngenerar" type="button" class="boton" id="btngenerar" value="Generar Detalle Contables" onClick="javascript: ue_contable();"></td>
          </tr>
          <tr class="formato-blanco">
            <td height="28" colspan="4"><p>
              <?php
					$in_grid->makegrid($li_totrowsc,$lo_titlecontable,$lo_objectc,$li_widthcontable,$ls_titlecontable,$ls_namecontable);
			}
				?>
            </p>
                <p>&nbsp; </p></td>
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
      <td><input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
          <input name="filadelete" type="hidden" id="filadelete">
          <input name="catafilas" type="hidden" id="catafilas" value="<?php print $li_catafilas;?>">
          <input name="totalfilasc" type="hidden" id="totalfilasc" value="<?php print $li_totrowsc;?>">
          <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
          <input name="txtdesalm" type="hidden" id="txtdesalm">
          <input name="txttelalm" type="hidden" id="txttelalm">
          <input name="txtubialm" type="hidden" id="txtubialm">
          <input name="txtnomresalm" type="hidden" id="txtnomresalm">
          <input name="txttelresalm" type="hidden" id="txttelresalm">
          <input name="hidstatus" type="hidden" id="hidstatus ">
          </td>
    </tr>
  </table>

  <div align="center"></div>
</form>
<p>&nbsp;</p>
<div align="center"></div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function ue_cataunidad()
{
	window.open("sigesp_sim_cat_unidad.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catarticulo(li_linea)
{
	//f=document.form1;
	window.open("sigesp_catdinamic_articulom.php?linea="+li_linea,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catarticulosal(li_linea)
{
	f=document.form1;
	if( (f.txtnumsol.value=="") ||  (f.txtcodalm.value=="") || (f.txtobsdes.value=="") )
	{
		alert("Debe indicar todos los Datos principales!!");
	}
	else
	{
		if(f.tipo.value=='Salida'){
			almacen=f.txtcodalm.value;
			tienda=f.txtcodtiend.value;
			pagina=	"sigesp_catdinamic_articulo.php?linea="+li_linea+"&almacen="+almacen+"&tienda="+tienda;
		}else{
			pagina=	"sigesp_catdinamic_articulo.php?linea="+li_linea
		}

		//alert(pagina);
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}

}

function ue_salida(valor)
{
	f=document.form1;
	ls_salida = valor;
	f.operacion.value="NUEVO";
	f.tipo.value=valor;
	f.submit();
}


function ue_catalmacen(li_linea)
{
	f=document.form1;
	ls_articulo= eval("f.txtcodart"+li_linea+".value");
	if (ls_articulo=="")
	{
		alert("Debe existir un articulo");
	}
	else
	{
		window.open("sigesp_catdinamic_almacendespacho.php?linea="+li_linea+"&codart="+ls_articulo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=425,height=200,left=180,top=160,location=no,resizable=yes");
	}
}

function ue_catalmacen2()
{
	f=document.form1;
	pagina = 'sigesp_catdinamic_almacen.php?tienda='+f.txtcodtiend.value;
	//alert(pagina);
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_catdinamic_despacho.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_imprimir()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;



	if(li_imprimir==1)
	{
		ls_ordendes=  f.txtnumorddes.value;
		if(ls_ordendes!="")
		{
			//alert( f.txtdenuniadm.value);
			numsol=    f.txtnumsol.value;
			fecdes=    f.txtfecdes.value;
			obsdes=    f.txtobsdes.value;
			tiendadsd=	f.txtcodtiend.value;
			tiendahst=	f.txtcodtiend.value;

			window.open("reportes/sigesp_sim_rfs_despachos.php?numorddes="+ls_ordendes+"&numsol="+numsol+"&fecdes="+fecdes+"&obsdes="+obsdes+"&tiendadsd="+tiendadsd+"&tiendahst="+tiendahst+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{alert("Debe existir un documento a imprimir");}
	}
	else
	{alert("No tiene permiso para realizar esta operacion");}
}

function ue_nuevo()
{
	f=document.form1;

	if(f.tipo.value != "Salida"){
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{
			window.open("sigesp_catdinamic_sol_eje_pre.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{alert("No tiene permiso para realizar esta operacion");}
	}else{
		f.operacion.value="NUEVO";
		f.action="sigesp_sim_p_despacho.php";
		f.submit();
	}
}


function ue_guardar()
{
	f=document.form1;

	if(f.tipo.value!="Salida")
	{

		ls_numorddes=eval("f.txtnumorddes.value");
		ls_numorddes=ue_validarvacio(ls_numorddes);
		li_totfilas= f.totalfilas.value;
		lb_ok=       f.hidok.value;
		li_contable= f.contable.value;
		li_procesar= f.ejecutar.value;

		if(li_procesar==1)
		{
			if((li_contable==0)||(lb_ok==true))
			{
				if(ls_numorddes=="")
				{
					lb_valido=true;
					ls_numsol=eval("f.txtnumsol.value");
					ls_numsol=ue_validarvacio(ls_numsol);
					ls_coduniadm=eval("f.txtcoduniadm.value");
					ls_coduniadm=ue_validarvacio(ls_coduniadm);
					ls_fecdes=eval("f.txtfecdes.value");
					ls_fecdes=ue_validarvacio(ls_fecdes);
					if ((ls_numsol=="")||(ls_coduniadm=="")||(ls_fecdes==""))
					{
						alert("Debe llenar los campos principales");
						lb_valido=false;
					}
					lb_blancos=true;
					li_blancos=0;
					for(li_i=1;(li_i<=li_totfilas && lb_blancos);li_i++)
					{
						ls_denart=    eval("f.txtdenart"+li_i+".value");
						ls_denart=ue_validarvacio(ls_denart);
						ls_codart=    eval("f.txtcodart"+li_i+".value");
						ls_codart=ue_validarvacio(ls_codart);
						ls_codalm=    eval("f.txtcodalm"+li_i+".value");
						ls_codalm=ue_validarvacio(ls_codalm);
						ls_unidad=    eval("f.txtunidad"+li_i+".value");
						ls_unidad=ue_validarvacio(ls_unidad);
						ls_canart=    eval("f.txtcanart"+li_i+".value");
						ls_canart=ue_validarvacio(ls_canart);
						ls_cansol=    eval("f.txtcansol"+li_i+".value");
						ls_cansol=ue_validarvacio(ls_cansol);
						ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
						ls_preuniart=ue_validarvacio(ls_preuniart);
						ls_montotart= eval("f.txtmontotart"+li_i+".value");
						ls_montotart=ue_validarvacio(ls_montotart);
						if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_codalm==""))
						{
							lb_blancos=false;
							li_blancos=li_blancos + 1;
						}
					}
					if((!lb_blancos)&&(lb_valido))
					{
						if(li_blancos==li_totfilas)
						{alert("Debe despachar al menos 1 articulo");}
						else
						{
							if(confirm("Desea continuar sin despachar todos los articulos?"))
							{
								lb_blancos=true;
							}
						}
					}
					if((lb_valido)&&(lb_blancos))
					{
						f.operacion.value="GUARDAR";
						f.action="sigesp_sim_p_despacho.php";
						f.submit();
					}
				}
				else{
					alert("No se puede modificar este registro");
				}
			}
			else{
				alert ("Debe actualizar el detalle contable");
			}
		}
		else{
			alert("No tiene permiso para realizar esta operacion");
		}

	}else{

		ls_status=eval("f.hidestatus2.value");
		if(ls_status=="C"){
			alert("El Despacho procesado, No puede ser modificado!!");
		}else{

			ls_numsol=eval("f.txtnumsol.value");
			ls_numsol=ue_validarvacio(ls_numsol);
			li_totfilas= f.totalfilas.value;

			if(li_totfilas>1){

				ls_fecdes=eval("f.txtfecdes.value");
				ls_fecdes=ue_validarvacio(ls_fecdes);

				f.txtnumorddes.value=ls_numsol;

				/*if(li_procesar==1)
				{*/
					lb_blancos=true;
					li_blancos=0;

					for(li_i=1;(li_i<li_totfilas && lb_blancos);li_i++)
					{
						ls_denart=    eval("f.txtdenart"+li_i+".value");
						ls_denart=ue_validarvacio(ls_denart);
						ls_codart=    eval("f.txtcodart"+li_i+".value");
						ls_codart=ue_validarvacio(ls_codart);
						ls_unidad=    eval("f.txtunidad"+li_i+".value");
						ls_unidad=ue_validarvacio(ls_unidad);
						ls_canart=    eval("f.txtcanart"+li_i+".value");
						ls_canart=ue_validarvacio(ls_canart);
						ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
						ls_preuniart=ue_validarvacio(ls_preuniart);
						ls_montotart= eval("f.txtmontotart"+li_i+".value");
						ls_montotart=ue_validarvacio(ls_montotart);

						if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_montotart==""))
						{
							lb_blancos=false;
							li_blancos=li_blancos + 1;
						}
					}

					if(lb_blancos){
						f.operacion.value="GUARDARSALIDA";
						//alert(f.operacion.value);
						f.action="sigesp_sim_p_despacho.php";
						f.submit();
					}else{
						alert("Debe completar los datos para Guardar!!");
					}

				/*}else{
					alert("No tiene permiso para realizar esta operacion");
				}*/
			}else{
				alert("Debe agregar al menos 1 articulo a la salida!");
			}
		}
	}

}

function ue_eliminar_sal()
{
	f=document.form1;
	if(f.hidestatus2.value=="C"){
		if(confirm("Esta operacion no se puede deshacer, Desea Elimnar esta Salida?"))
		{
			f.operacion.value="ELIMINARSALIDA";
			//alert(f.operacion.value);
			f.action="sigesp_sim_p_despacho.php";
			f.submit();
		}
	}

}

function uf_delete_dt(li_row)
{
	f=document.form1;
	//alert("PASE"+li_row);

		li_fila=f.totalfilas.value;
		if(li_fila!=li_row)
		{
			if(confirm("Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_sim_p_despacho.php";
				f.submit();
			}
		}

}



function uf_delete_dt2(li_del)
{
	f=document.form1;
	if(f.hidestatus2.value=="C"){
		li_total = parseInt(f.totalfilas.value);
		if(li_total>1){
			//alert("OK");
			if(confirm("No puedo deshacer esta operacio, Desea Elimnar el articulo de la Salida?")){
				f.filadelete.value = li_del;
				f.operacion.value="ELIMINARITEM";
				f.action="sigesp_sim_p_despacho.php";
				f.submit();
			}
		}else{
			alert("La Salida debe tener al menos 1 articulo. \n Por favor elimine la Salida completa!");
		}
	}


}



function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_montosfactura(li_row)
{
//--------------------------------------------------------
//	Funcion que calcula el monto total por articulo multiplicando la cantidad de articulos a despachar por el costo
//  unitario de cada uno de ellos, ademas verifica que la cantidad a despachar no sea mayor a la existencia en el almacen
//   que se ha indicado e igualmente no sea mayor a la cantidad solicitada.
//--------------------------------------------------------
	f=document.form1;
	lb_valido=true;
	ls_unisol=eval("f.txtunidad"+li_row+".value");
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	li_unidad=eval("f.hidunidad"+li_row+".value");
	li_unidad=ue_validarvacio(li_unidad);
	li_existencia=eval("f.hidexistencia"+li_row+".value");
	li_existencia=ue_validarvacio(li_existencia);
	li_canart=eval("f.txtcanart"+li_row+".value");
	li_canart=ue_validarvacio(li_canart);
	li_cansol=eval("f.txtcansol"+li_row+".value");
	li_cansol=ue_validarvacio(li_cansol);
	li_preuniart=eval("f.txtpreuniart"+li_row+".value");
	li_preuniart=ue_validarvacio(li_preuniart);
	li_canpendes=eval("f.txthidpenart"+li_row+".value");
	li_canpendes=ue_validarvacio(li_canpendes);
	li_preuniart=ue_formato_operaciones(li_preuniart);
	li_canart=ue_formato_operaciones(li_canart);
	li_cansol=ue_formato_operaciones(li_cansol);
	ls_estsol=f.txtestsol.value;
	f.hidok.value=false;
	li_canartaux=li_canart;
	if(ls_unidad=="Mayor")
	{
		li_canartaux=parseFloat(li_canart) * parseFloat(li_unidad);
	}

	alert(parseFloat(li_existencia)+"--"+parseFloat(li_canartaux));

	if(parseFloat(li_existencia)<parseFloat(li_canartaux))
	{
		eval("f.txtcanart"+li_row+".value=''");
		eval("f.txtmontotart"+li_row+".value=''");
		alert("No hay suficientes, el maximo es de "+li_existencia+" articulos al detal");
		lb_valido=false;
	}
	if ((lb_valido==true)&&(li_canart!="")&&(li_preuniart!=""))
	{
		switch(ls_unisol)
		{
			case "Mayor":
				if(ls_unidad=="Detal")
				{
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					li_penart=(parseFloat(li_penart)/parseFloat(li_unidad));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				}
				else
				{
					li_canart=parseFloat(li_canart) * parseFloat(li_unidad);
					li_canart=String(li_canart);
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					li_penart=(parseFloat(li_penart)/parseFloat(li_unidad));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;

				}
				li_montot=parseFloat(li_canart) * parseFloat(li_preuniart);
				li_montot=uf_convertir(li_montot);
				obj=eval("f.txtmontotart"+li_row+"");
				obj.value=li_montot;
			break;
			case "Detal":
				if(ls_unidad=="Mayor")
				{
					li_canart=parseFloat(li_canart) * parseFloat(li_unidad);
					li_canart=String(li_canart);
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				}
				else

				{
					li_penart=(parseFloat(li_canpendes)-parseFloat(li_canart));
					if(li_penart<0)
					{
						eval("f.txtcanart"+li_row+".value=''");
						alert("No se puede exeder la cantidad solicitada/pendiente");
						break;
					}
					li_penart=uf_convertir(li_penart);
					obj=eval("f.txtpenart"+li_row+"");
					obj.value=li_penart;
				}
				li_montot=parseFloat(li_canart) * parseFloat(li_preuniart);
				li_montot=uf_convertir(li_montot);
				obj=eval("f.txtmontotart"+li_row+"");
				obj.value=li_montot;
			break;
		}
	}
}

//--------------------------------------------------------
//cuando la entrada de suministros es por una factura
//--------------------------------------------------------
function ue_montosfactura2(li_row)
{
	f=document.form1;
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	li_unidad=eval("f.hidunidad"+li_row+".value");
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	li_existencia=eval("f.hidexistencia"+li_row+".value");
	li_existencia=ue_validarvacio(li_existencia);

	if((ls_canart!="")&&(ls_preuniart!=""))
	{
		ls_preuniart=ue_formato_operaciones(ls_preuniart);
		ls_canart=   ue_formato_operaciones(ls_canart);
		li_unidad=   ue_formato_operaciones(li_unidad);

		ls_canart=parseFloat(ls_canart);

		li_existencia=uf_convertir(li_existencia)

		//alert(li_existencia+"--"+ls_canart+"--"+ls_preuniart);
		if(li_existencia<ls_canart)
		{
		eval("f.txtcanart"+li_row+".value=''");
		eval("f.txtmontotart"+li_row+".value=''");
		alert("No hay suficientes, el maximo es de "+li_existencia+" articulos al detal");
		lb_valido=false;
		}
	else
	{
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
}


function uf_agregar_dt(li_row)
{
	f=document.form1;
	if(f.tipo.value == "Salida"){
		f.hidstatus.value = "";
	}
	ls_codnewart= eval("f.txtcodart"+li_row+".value");
	ls_dennewart= eval("f.txtdenart"+li_row+".value");
	ls_codpronewart= eval("f.txtcodprov"+li_row+".value");
	ls_nompronewart= eval("f.txtnomprov"+li_row+".value");
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
	ls_codnewpre= eval("f.txtpreuniart"+li_row+".value");
	ls_codnewmon= eval("f.txtmontotart"+li_row+".value");
	li_total=f.totalfilas.value;
	lb_valido=false;

	for(li_i=1; (li_i<li_total&&lb_valido!=true) ;li_i++)
	{
		ls_codart=    eval("f.txtcodart"+li_i+".value");
		ls_codprov=    eval("f.txtcodprov"+li_i+".value");
		ls_unidad=    eval("f.txtunidad"+li_i+".value");
		ls_canart=    eval("f.txtcanart"+li_i+".value");
		ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
		ls_montotord= eval("f.txtmontotart"+li_i+".value");
		if((ls_codart==ls_codnewart)&&(ls_codprov==ls_codpronewart)&&(ls_unidad==ls_codnewuni)&&(li_i!=li_row))
		{
			alert("El Art�culo "+ls_dennewart+" ya esta registrado");
			lb_valido=true;
			//lb_valido=false;
			f.totalfilas.value=li_total;

		}
	}
	ls_codart=eval("f.txtcodart"+li_row+".value");
	ls_codart=ue_validarvacio(ls_codart);
	ls_unidad=eval("f.cmbunidad"+li_row+".value");
	ls_unidad=ue_validarvacio(ls_unidad);
	ls_canart=eval("f.txtcanart"+li_row+".value");
	ls_canart=ue_validarvacio(ls_canart);
	ls_preuniart=eval("f.txtpreuniart"+li_row+".value");
	ls_preuniart=ue_validarvacio(ls_preuniart);
	ls_montotord=eval("f.txtmontotart"+li_row+".value");
	ls_montotord=ue_validarvacio(ls_montotord);
	ls_obsdes=f.txtobsdes.value;

	if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_preuniart=="")||(ls_montotord==""))
	{
		alert("Debe llenar todos los campos");
		lb_valido=true;
	}

	ls_numsol=eval("f.txtnumsol.value");
	ls_numsol=ue_validarvacio(ls_numsol);
	ls_codalm=eval("f.txtcodalm.value");
	ls_codalm=ue_validarvacio(ls_codalm);
	ls_fecdes=eval("f.txtfecdes.value");
	ls_fecdes=ue_validarvacio(ls_fecdes);

	if((ls_numsol=="")||(ls_codalm=="")||(ls_fecdes==""))
	{
		alert("Debe llenar los campos principales");
		lb_valido=true;
	}

	if(!lb_valido)
	{
		//ue_calculartotal();
		f.operacion.value="AGREGARDETALLE";
		f.action="sigesp_sim_p_despacho.php";
		f.submit();
	}else{
		obj1=eval("f.txtcodart"+li_row);
		obj1.value="";
		obj2=eval("f.txtdenart"+li_row);
		obj2.value="";
		obj3=eval("f.txtpreuniart"+li_row);
		obj3.value="";
		obj4=eval("f.txtmontotart"+li_row);
		obj4.value="";
		obj5=eval("f.txtcanart"+li_row);
		obj5.value="";
		obj6=eval("f.txtcodprov"+li_row);
		obj6.value="";
		obj7=eval("f.txtnomprov"+li_row);
		obj7.value="";
	}
}


function ue_contable()
{
//--------------------------------------------------------
// Funcion que genera los asientos contables del despacho
//--------------------------------------------------------

	f=document.form1;
	li_totfilas=  f.totalfilas.value;
	li_totfilasc= f.totalfilasc.value;
	ls_numorddes=   f.txtnumorddes.value;
	lb_blancos=   true;
	li_blancos=0;
	if(ls_numorddes=="")
	{
		for(li_i=1;li_i<=li_totfilas;li_i++)
		{
			ls_denart=    eval("f.txtdenart"+li_i+".value");
			ls_denart=ue_validarvacio(ls_denart);
			ls_codart=    eval("f.txtcodart"+li_i+".value");
			ls_codart=ue_validarvacio(ls_codart);
			ls_codalm=    eval("f.txtcodalm"+li_i+".value");
			ls_codalm=ue_validarvacio(ls_codalm);
			ls_unidad=    eval("f.txtunidad"+li_i+".value");
			ls_unidad=ue_validarvacio(ls_unidad);
			ls_canart=    eval("f.txtcanart"+li_i+".value");
			ls_canart=ue_validarvacio(ls_canart);
			ls_cansol=    eval("f.txtcansol"+li_i+".value");
			ls_cansol=ue_validarvacio(ls_cansol);
			ls_preuniart= eval("f.txtpreuniart"+li_i+".value");
			ls_preuniart=ue_validarvacio(ls_preuniart);
			ls_montotart= eval("f.txtmontotart"+li_i+".value");
			ls_montotart=ue_validarvacio(ls_montotart);
			if((ls_codart=="")||(ls_unidad=="")||(ls_canart=="")||(ls_codalm==""))
			{
				lb_blancos=false;
				li_blancos=li_blancos + 1;
			}
		}
		if((!lb_blancos)&&(lb_valido))
		{
			if(li_blancos!=li_totfilas)
			{lb_blancos=true;}
		}
		if(lb_blancos)
		{
			f.operacion.value="CALCULARCONTABLE";
			f.action="sigesp_sim_p_despacho.php";
			f.submit();
		}
	}
	else
	{alert("No se puede modificar este despacho");}
}

function ue_completa()
{
	f=document.form1;
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		ls_unisol=eval("f.txtunidad"+li_i+".value");
		li_cansol=eval("f.txtcansol"+li_i+".value");
		li_canpendes=eval("f.txthidpenart"+li_i+".value");
		li_preuniart=eval("f.txtpreuniart"+li_i+".value");
		ls_unidad=eval("f.txtunidad"+li_i+".value");
		li_unidad=eval("f.hidunidad"+li_i+".value");
		if(li_preuniart!="")
		{
			if(ls_unisol=="Mayor")
			{
				obj=eval("f.cmbunidad"+li_i+".options[1]");
				obj.selected=true;
				li_canpendes=(parseFloat(li_canpendes)/parseFloat(li_unidad));
				li_canpendes=uf_convertir(li_canpendes);
				obj=eval("f.txtcanart"+li_i+"");
				obj.value=li_canpendes;
			}
			else
			{
				li_canpendes=uf_convertir(li_canpendes);
				obj=eval("f.cmbunidad"+li_i+".options[0]");
				obj.selected=true;
				obj=eval("f.txtcanart"+li_i+"");
				obj.value=li_canpendes;
			}
			obj=eval("f.txtpenart"+li_i+"");
			obj.value="0,00";
			li_canpendes=   ue_formato_operaciones(li_canpendes);
			li_preuniart=   ue_formato_operaciones(li_preuniart);
			if(ls_unidad=="Mayor")
			{
				li_canpendes=parseFloat(li_canpendes) * parseFloat(li_unidad);
				li_canpendes=String(li_canpendes);
			}
			li_montot=parseFloat(li_canpendes) * parseFloat(li_preuniart);
			li_montot=uf_convertir(li_montot);
			obj=eval("f.txtmontotart"+li_i+"");
			obj.value=li_montot;
		}
		else
		{
			alert("Debe indicar el almacen del que desea despachar cada articulo.");
			f.rdtipodespacho[0].checked=false;
			break;
		}
	}
}

function ue_parcial()
{
	li_totfilas=f.totalfilas.value;
	for(li_i=1;li_i<=li_totfilas;li_i++)
	{
		obj=eval("f.txtpenart"+li_i+"");
		obj.value="";
		obj=eval("f.txtcanart"+li_i+"");
		obj.value="";
		obj=eval("f.txtmontotart"+li_i+"");
		obj.value="";
	}
}


//--------------------------------------------------------
//	Funci�n que coloca los separadores (/) de las fechas
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
