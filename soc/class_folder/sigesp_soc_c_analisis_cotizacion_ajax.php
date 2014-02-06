<?php
	session_start(); 
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/grid_param.php");	
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();
	$io_include=new sigesp_include();
	$io_conexion=$io_include->uf_conectar();		
	$io_sql=new class_sql($io_conexion);			
	$io_mensajes=new class_mensajes();
	$io_funciones=new class_funciones();	
	$ls_empresa=$_SESSION["la_empresa"]["codemp"];
	$io_grid=new grid_param();
	require_once("class_funciones_compra.php");
	$io_funciones_soc=new class_funciones_compra();
	// proceso a ejecutar
	$ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	// total de filas de Bienes	
	switch($ls_proceso)
	{
		case "AGREGARCOTIZACIONES":
			$li_totalcotizaciones=$io_funciones_soc->uf_obtenervalor("totalcotizaciones","1");			
			uf_print_cotizaciones($li_totalcotizaciones);			
			break;
		case "AGREGARITEMS":			
			uf_print_items();			
			break;
		case "LIMPIARCOTIZACIONES":
			uf_limpiar_grid_cotizaciones();
			break;
		case "ACTUALIZARITEMS":
			$li_totalitems=$io_funciones_soc->uf_obtenervalor("total","0");
			uf_actualizar_items($li_totalitems);
			break;
		case "CARGARCOTIZACIONES":
			uf_cargar_analisis();
			break;	
		case "CARGARITEMS":
			uf_print_items(true);
			break;		
	}
	function uf_limpiar_grid_cotizaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiar_grid
		//		   Access: public
		//	    Arguments://
		//	  Description: Método que limpia el grid de las Cotizaciones
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 01/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		//Titulos de la Tabla
		$lo_title[1]="No. Solicitud";
		$lo_title[2]="No. Cotizacion";
		$lo_title[3]="Proveedor";
		$lo_title[4]="Fecha";
		$lo_title[5]="Monto Total";
		$lo_title[6]="I.V.A.";
		$lo_title[7]="Ver";
		$lo_title[8]="X";	
		
		//Tabla
		$lo_object[1][1]="<input name=txtnumsol1   id=txtnumsol1    type=text  class=sin-borde   size=12  value=''     style=text-align:center    readonly>";
		$lo_object[1][2]="<input name=txtnumcot1   id=txtnumcot1    type=text  class=sin-borde   size=12  value=''     style=text-align:center    readonly>";
		$lo_object[1][3]="<input name=txtnompro1   id=txtnompro1    type=text  class=sin-borde   size=30  value=''     style=text-align:left      readonly><input name=txtcodpro1  id=txtcodpro1   type=hidden value=''>";
		$lo_object[1][4]="<input name=txtfecha1    id=txtfecha1     type=text  class=sin-borde   size=8   value=''     style=text-align:center    readonly>";
		$lo_object[1][5]="<input name=txtmonto1    id=txtmonto1     type=text  class=sin-borde   size=18  value=''     style=text-align:right     readonly>";
		$lo_object[1][6]="<input name=txtiva1      id=txtiva1       type=text  class=sin-borde   size=5   value=''     style=text-align:center    readonly><input name=txttipsolcot1   id=txttipsolcot1   type=hidden value=''>";
		$lo_object[1][7]=" ";
		$lo_object[1][8]="  ";			
		//$io_grid->make_gridScroll(1,$lo_title,$lo_object,780,"Cotizaciones","gridcotizaciones",80);
		$io_grid->makegrid(1,$lo_title,$lo_object,775,"Cotizaciones","gridcotizaciones");			
	}
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_cotizaciones($ai_total,$aa_cotizaciones=array())
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_cotizaciones
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de las Cotizaciones
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 14/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc, $io_funciones;
		$lo_object=array();
		$li_total=count($aa_cotizaciones);
		// Titulos del Grid de Cotizaciones
		$lo_title[1]="No. Solicitud";
		$lo_title[2]="No. Cotizacion";
		$lo_title[3]="Proveedor";
		$lo_title[4]="Fecha";
		$lo_title[5]="Monto Total";
		$lo_title[6]="I.V.A.";
		$lo_title[7]="Ver";
		$lo_title[8]="X";	
		// Recorrido de todos las cotizaciones del Grid
		for($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		{
			if($li_total==0)//Esto es en caso de q las cotizaciones se esten cargando una a una
			{
				$ls_numcot=$io_funciones_soc->uf_obtenervalor("txtnumcot".$li_fila,"");
				$ls_numsol=$io_funciones_soc->uf_obtenervalor("txtnumsol".$li_fila,"");
				$ls_nompro=$io_funciones_soc->uf_obtenervalor("txtnompro".$li_fila,"");
				$ls_codpro=$io_funciones_soc->uf_obtenervalor("txtcodpro".$li_fila,"");
				$ld_fecha=$io_funciones_soc->uf_obtenervalor("txtfecha".$li_fila,"");
				$li_monto=$io_funciones_soc->uf_obtenervalor("txtmonto".$li_fila,"");
				$li_iva=$io_funciones_soc->uf_obtenervalor("txtiva".$li_fila,"");
				$ls_tipsolcot=$io_funciones_soc->uf_obtenervalor("txttipsolcot".$li_fila,"");						
			}
			else// Esto es en caso de q las cotizaciones se esten llenando todas a la vez a partir del catalogo de analisis de cotizacion
			{
				if($li_fila<$ai_total)
				{
					$ls_numcot=$aa_cotizaciones[$li_fila]["numcot"];
					$ls_numsol=$aa_cotizaciones[$li_fila]["numsolcot"];
					$ls_nompro=$aa_cotizaciones[$li_fila]["nompro"];
					$ls_codpro=$aa_cotizaciones[$li_fila]["cod_pro"];
					$ld_fecha=$io_funciones->uf_convertirfecmostrar($aa_cotizaciones[$li_fila]["feccot"]);
					$li_monto=number_format($aa_cotizaciones[$li_fila]["montotcot"],2,",",".");
					$li_iva=number_format($aa_cotizaciones[$li_fila]["poriva"],2,",",".");
					$ls_tipsolcot=$aa_cotizaciones[$li_fila]["tipsolcot"];
				}
				else// Esto se hace para q se imprima una linea adicional en blanco
				{
					$ls_numcot="";
					$ls_numsol="";
					$ls_nompro="";
					$ls_codpro="";
					$ld_fecha="";
					$li_monto="";
					$li_iva="";
					$ls_tipsolcot="";

				}
			}		
			$lo_object[$li_fila][1]="<input name=txtnumsol".$li_fila."   id=txtnumsol".$li_fila."  type=text  class=sin-borde   size=13  value='$ls_numsol'     style=text-align:center    readonly>";
			$lo_object[$li_fila][2]="<input name=txtnumcot".$li_fila."   id=txtnumcot".$li_fila."  type=text  class=sin-borde   size=13  value='$ls_numcot'     style=text-align:center    readonly>";
			$lo_object[$li_fila][3]="<input name=txtnompro".$li_fila."   id=txtnompro".$li_fila."  type=text  class=sin-borde   size=30  value='$ls_nompro'     style=text-align:left      readonly title='$ls_nompro'><input name=txtcodpro".$li_fila."   id=txtcodpro".$li_fila."   type=hidden value='$ls_codpro'>";
			$lo_object[$li_fila][4]="<input name=txtfecha".$li_fila."    id=txtfecha".$li_fila."   type=text  class=sin-borde   size=8  value='$ld_fecha'       style=text-align:center    readonly>";
			$lo_object[$li_fila][5]="<input name=txtmonto".$li_fila."    id=txtmonto".$li_fila."   type=text  class=sin-borde   size=18  value='$li_monto'      style=text-align:right     readonly>";
			$lo_object[$li_fila][6]="<input name=txtiva".$li_fila."      id=txtiva".$li_fila."     type=text  class=sin-borde   size=5  value='$li_iva'         style=text-align:center    readonly><input name=txttipsolcot".$li_fila."   id=txttipsolcot".$li_fila."   type=hidden value='$ls_tipsolcot'>";
			if($li_fila==$ai_total)// si es la última fila no pinto las dos ultimas 
			{
				$lo_object[$li_fila][7]=" ";
				$lo_object[$li_fila][8]="  ";
			}
			else
			{
				$lo_object[$li_fila][7]="<a href=javascript:uf_ver_cotizacion(".$li_fila.")><div align=center><img src=../shared/imagebank/mas.gif title=Clic para ver detalle de Cotización width=9 height=17 border=0></div></a>";
				$lo_object[$li_fila][8]="<a href=javascript:ue_delete_cotizacion(".$li_fila.")><div align=center><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar Cotización width=15 height=15 border=0></div></a>";
			}			
		}			
		$io_grid->makegrid($ai_total,$lo_title,$lo_object,775,"Cotizaciones","gridcotizaciones");	
		if(!array_key_exists("txttotalcotizaciones",$_POST))
			print "<input type='hidden' name='totalcotizaciones' id='totalcotizaciones' value='$ai_total'>";
		
	}// end function uf_print_cotizaciones
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_items($ab_catalogo=false)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_items
		//		   Access: private
		//	  Description: Método que imprime el grid de los bienes/servicios
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_analisis_cotizacion,$io_grid,$io_funciones_soc;
		$lo_object=array();
		//Se determita si el analisis es de bienes o servicios
		$ls_tipcot = $io_funciones_soc->uf_obtenervalor("txttipsolcot1","");
		if($ls_tipcot=="B")
			$ls_titulo="Bienes";
		else
			$ls_titulo="Servicios";
		// Titulos del Grid de Items
		$lo_title[1]="Codigo";
		$lo_title[2]="Descripcion";
		$lo_title[3]="Proveedor";
		$lo_title[4]="Cantidad";
		$lo_title[5]="Precio/Unid";
		$lo_title[6]="I.V.A.";
		$lo_title[7]="Monto Total";
		$lo_title[8]="Observacion";
		$li_totrows = $io_funciones_soc->uf_obtenervalor("hidtotrows","0");
		for ($li_fila=1;$li_fila<=$li_totrows;$li_fila++)
		    {
			  $ls_coditem     = $io_funciones_soc->uf_obtenervalor("txtcoditem".$li_fila,"");
			  $ls_nomitem     = $io_funciones_soc->uf_obtenervalor("txtnomitem".$li_fila,"");
			  $ls_nompro      = $io_funciones_soc->uf_obtenervalor("txtnomproitem".$li_fila,"");
			  $ls_codpro      = $io_funciones_soc->uf_obtenervalor("txtcodproselec".$li_fila,""); 
			  $ld_cantidad    = $io_funciones_soc->uf_obtenervalor("txtcanselec".$li_fila,"");
			  $ld_precio      = $io_funciones_soc->uf_obtenervalor("txtpreuniselec".$li_fila,"");
			  $ld_moniva      = $io_funciones_soc->uf_obtenervalor("txtivaselec".$li_fila,"");
			  $ld_monto       = $io_funciones_soc->uf_obtenervalor("txtmonselec".$li_fila,"");
			  $ls_observacion =	$io_funciones_soc->uf_obtenervalor("txtobservacion".$li_fila,"");		  
			  $ls_numcot      = $io_funciones_soc->uf_obtenervalor("txtnumcotsele".$li_fila,"");
			  
			  $lo_object[$li_fila][1]="<input name=txtcoditem".$li_fila."          id=txtcoditem".$li_fila."        type=text  class=sin-borde   size=20  value='$ls_coditem'    style=text-align:center   readonly>";
			  $lo_object[$li_fila][2]="<input name=txtnomitem".$li_fila."          id=txtnomitem".$li_fila."        type=text  class=sin-borde   size=30  value='$ls_nomitem'    style=text-align:left     readonly title='$ls_nomitem'>";
			  $lo_object[$li_fila][3]="<div align=right><input name=txtnomproitem".$li_fila."       id=txtnomproitem".$li_fila."     type=text  class=sin-borde   size=38  value='$ls_nompro'               style=text-align:left     readonly ><a href='javascript:ue_catalogo_proveedores(".$li_fila.")'><img src='../shared/imagebank/tools20/buscar.gif' title='Seleccionar Proveedor' width=20 height=20 border=0></a></div><input name=txtcodproselec".$li_fila."   id=txtcodproselec".$li_fila."   type=hidden value='$ls_codpro'>";
			  $lo_object[$li_fila][4]="<input name=txtcanselec".$li_fila."         id=txtcanselec".$li_fila."       type=text  class=sin-borde   size=4   value='$ld_cantidad'               style=text-align:right   readonly>";
			  $lo_object[$li_fila][5]="<input name=txtpreuniselec".$li_fila."      id=txtpreuniselec".$li_fila."    type=text  class=sin-borde   size=12  value='$ld_precio'               style=text-align:right   readonly>";
			  $lo_object[$li_fila][6]="<input name=txtivaselec".$li_fila."         id=txtivaselec".$li_fila."       type=text  class=sin-borde   size=12  value='$ld_moniva'               style=text-align:right     readonly>";
			  $lo_object[$li_fila][7]="<input name=txtmonselec".$li_fila."         id=txtmonselec".$li_fila."       type=text  class=sin-borde   size=12  value='$ld_monto'               style=text-align:right     readonly>";
			  $lo_object[$li_fila][8]="<input name=txtobservacion".$li_fila."      id=txtobservacion".$li_fila."    type=text  class=sin-borde   size=30  value='$ls_observacion'               style=text-align:left   ><input name=txtnumcotsele".$li_fila."   id=txtnumcotsele".$li_fila."   type=hidden value='$ls_numcot'>";										
			}
		$ls_numcot = $io_funciones_soc->uf_obtenervalor("hidnumcot","");
		$ls_codpro = $io_funciones_soc->uf_obtenervalor("hidcodpro","");
		if($ab_catalogo)//si el cargar biene de seleccionar cotizaciones una a una
			uf_cargar_items($la_items);			
		else
			uf_select_items_cotizacion($ls_numcot,$ls_codpro,$ls_tipcot,$la_items);
			
		$li_total=count($la_items);
		$li_row = $li_fila-1;
		for ($li_i=1;$li_i<=$li_total;$li_i++)
		    {
			  $ls_coditem = trim($la_items[$li_i]["codigo"]);
			  $ls_nomitem = trim($la_items[$li_i]["denominacion"]);
			  if ($ab_catalogo)
			     {
				   $ls_nompro      = $la_items[$li_i]["nompro"];
				   $ls_cantidad    = number_format($la_items[$li_i]["cantidad"],2,",",".");
				   $ls_precio      = number_format($la_items[$li_i]["precio"],2,",",".");
				   $ls_moniva      = number_format($la_items[$li_i]["moniva"],2,",",".");
				   $ls_monto       = number_format($la_items[$li_i]["monto"],2,",",".");
				   $ls_observacion = $la_items[$li_i]["obsanacot"];
				   $ls_numcot      = $la_items[$li_i]["numcot"];
				   $ls_codpro      = $la_items[$li_i]["cod_pro"];
			     }
			   else
			     {
				   $ls_nompro="";
				   $ls_cantidad="";
				   $ls_precio="";
				   $ls_moniva="";
				   $ls_monto="";
				   $ls_observacion="";
				   $ls_numcot="";
				   $ls_codpro="";
			     }			
			$lo_object[$li_fila][1]="<input name=txtcoditem".$li_fila."          id=txtcoditem".$li_fila."        type=text  class=sin-borde   size=20  value='$ls_coditem'    style=text-align:center   readonly>";
			$lo_object[$li_fila][2]="<input name=txtnomitem".$li_fila."          id=txtnomitem".$li_fila."        type=text  class=sin-borde   size=30  value='$ls_nomitem'    style=text-align:left     readonly title='$ls_nomitem'>";
			$lo_object[$li_fila][3]="<div align=right><input name=txtnomproitem".$li_fila."       id=txtnomproitem".$li_fila."     type=text  class=sin-borde   size=38  value='$ls_nompro'               style=text-align:left     readonly ><a href='javascript:ue_catalogo_proveedores(".$li_fila.")'><img src='../shared/imagebank/tools20/buscar.gif' title='Seleccionar Proveedor' width=20 height=20 border=0></a></div><input name=txtcodproselec".$li_fila."   id=txtcodproselec".$li_fila."   type=hidden value='$ls_codpro'>";
			$lo_object[$li_fila][4]="<input name=txtcanselec".$li_fila."         id=txtcanselec".$li_fila."       type=text  class=sin-borde   size=4   value='$ls_cantidad'               style=text-align:right   readonly>";
			$lo_object[$li_fila][5]="<input name=txtpreuniselec".$li_fila."      id=txtpreuniselec".$li_fila."    type=text  class=sin-borde   size=12  value='$ls_precio'               style=text-align:right   readonly>";
			$lo_object[$li_fila][6]="<input name=txtivaselec".$li_fila."         id=txtivaselec".$li_fila."       type=text  class=sin-borde   size=12  value='$ls_moniva'               style=text-align:right     readonly>";
			$lo_object[$li_fila][7]="<input name=txtmonselec".$li_fila."         id=txtmonselec".$li_fila."       type=text  class=sin-borde   size=12  value='$ls_monto'               style=text-align:right     readonly>";
			$lo_object[$li_fila][8]="<input name=txtobservacion".$li_fila."      id=txtobservacion".$li_fila."    type=text  class=sin-borde   size=30  value='$ls_observacion'               style=text-align:left   ><input name=txtnumcotsele".$li_fila."   id=txtnumcotsele".$li_fila."   type=hidden value='$ls_numcot'>";										
		    $li_fila++;
		}	
		$io_grid->make_gridScroll($li_fila-1,$lo_title,$lo_object,775,$ls_titulo,"griditems",200);
	}// end function uf_print_items()
	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------------------------------------

	function uf_actualizar_items($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_items
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que actualiza el grid de los bienes/servicios
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 28/05/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		$lo_object=array();
		// Titulos del Grid de Cotizaciones
		//Se determita si el analisis es de bienes o servicios
		$ls_tipsolcot=$io_funciones_soc->uf_obtenervalor("tipsolcot1","");
		$li_total=$io_funciones_soc->uf_obtenervalor("total","");
		if($ls_tipsolcot=="B")
			$ls_titulo="Bienes";
		else
			$ls_titulo="Servicios";
		// Titulos del Grid de Items
		$lo_title[1]="Codigo";
		$lo_title[2]="Descripcion";
		$lo_title[3]="Proveedor";
		$lo_title[4]="Cantidad";
		$lo_title[5]="Precio/Unid";
		$lo_title[6]="I.V.A.";
		$lo_title[7]="Monto Total";
		$lo_title[8]="Observacion";
		// Recorrido de todos las items del Grid
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_coditem     = trim($io_funciones_soc->uf_obtenervalor("txtcoditem".$li_fila,""));
			$ls_nomitem     = $io_funciones_soc->uf_obtenervalor("txtnomitem".$li_fila,"");
			$ls_nomproitem  = $io_funciones_soc->uf_obtenervalor("txtnomproitem".$li_fila,"");
			$ls_canselec    = $io_funciones_soc->uf_obtenervalor("txtcanselec".$li_fila,"");
			$ls_preuniselec = $io_funciones_soc->uf_obtenervalor("txtpreuniselec".$li_fila,"");
			$ls_ivaselec    = $io_funciones_soc->uf_obtenervalor("txtivaselec".$li_fila,"");
			$ls_monselec    = $io_funciones_soc->uf_obtenervalor("txtmonselec".$li_fila,"");
			$ls_observacion = $io_funciones_soc->uf_obtenervalor("txtobservacion".$li_fila,"");
			$ls_numcot      = $io_funciones_soc->uf_obtenervalor("txtnumcotsele".$li_fila,"");
			$ls_codpro      = $io_funciones_soc->uf_obtenervalor("txtcodproselec".$li_fila,"");
			
			
			$lo_object[$li_fila][1]="<input name=txtcoditem".$li_fila."          id=txtcoditem".$li_fila."        type=text  class=sin-borde   size=20  value='$ls_coditem'    style=text-align:center   readonly>";
			$lo_object[$li_fila][2]="<input name=txtnomitem".$li_fila."          id=txtnomitem".$li_fila."        type=text  class=sin-borde   size=30  value='$ls_nomitem'    style=text-align:left     readonly title='$ls_nomitem'>";
			$lo_object[$li_fila][3]="<div align=right><input name=txtnomproitem".$li_fila."       id=txtnomproitem".$li_fila."     type=text  class=sin-borde   size=38  value='$ls_nomproitem'               style=text-align:left     readonly ><a href='javascript:ue_catalogo_proveedores(".$li_fila.")'><img src='../shared/imagebank/tools20/buscar.gif' title='Seleccionar Proveedor' width=20 height=20 border=0></a></div><input name=txtcodproselec".$li_fila."   id=txtcodproselec".$li_fila."   type=hidden value='$ls_codpro'>";
			$lo_object[$li_fila][4]="<input name=txtcanselec".$li_fila."         id=txtcanselec".$li_fila."       type=text  class=sin-borde   size=4   value='$ls_canselec'               style=text-align:right   readonly>";
			$lo_object[$li_fila][5]="<input name=txtpreuniselec".$li_fila."      id=txtpreuniselec".$li_fila."    type=text  class=sin-borde   size=12  value='$ls_preuniselec'               style=text-align:right   readonly>";
			$lo_object[$li_fila][6]="<input name=txtivaselec".$li_fila."         id=txtivaselec".$li_fila."       type=text  class=sin-borde   size=12  value='$ls_ivaselec'               style=text-align:right     readonly>";
			$lo_object[$li_fila][7]="<input name=txtmonselec".$li_fila."         id=txtmonselec".$li_fila."       type=text  class=sin-borde   size=12  value='$ls_monselec'               style=text-align:right     readonly>";
			$lo_object[$li_fila][8]="<textarea name=txtobservacion".$li_fila."   id=txtobservacion".$li_fila."    class=sin-borde   size=80                 style=text-align:left   >$ls_observacion</textarea><input name=txtnumcotsele".$li_fila."   id=txtnumcotsele".$li_fila."   type=hidden value='$ls_numcot'>";										
		}	
		$io_grid->make_gridScroll($li_total,$lo_title,$lo_object,775,$ls_titulo,"griditems",200);		
		print "	<input type='hidden' name='totalitems' id='totalitems' value=$li_total>";
	}// end function uf_actualizar_items
	//-------------------------------------------------------------------------------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_items_cotizacion($as_numcot,$as_codpro,$as_tipcot,&$aa_items)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items_cotizacion
		//		   Access: public
		//	    Arguments: $as_numcot-->Numero de Cotizacion
		//						$as_codpro--->Codigo del Proveedor
		//						$as_tipsolcot--->Si la cotizacion es de bienes o servicios
		//		return	:		arreglo con los bienes/servicios 
		//	  Description: Metodo que  devuelve los bienes/servicios de una cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 29/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_empresa,$io_sql,$io_mensajes,$io_funciones,$io_funciones_soc;
		$aa_items  = array();
		$lb_valido = false;
		$ls_sqlaux = $ls_strsql = "";
		$ls_strsql = $io_funciones_soc->uf_obtenervalor("hidsqlaux","");
		$ls_strsql = str_replace('numcot = ',"numcot = '",$ls_strsql);
		$ls_strsql = str_replace(';',"'",$ls_strsql);
		if (!empty($ls_strsql) && $as_tipcot=='B')
		   {
		     $ls_sqlaux = "AND soc_dtcot_bienes.codart NOT IN (SELECT soc_dtcot_bienes.codart 
																 FROM soc_dtcot_bienes 
																WHERE $ls_strsql)";
		   }
		elseif(!empty($ls_strsql) && $as_tipcot=='S')
		   {
		     $ls_sqlaux = "AND soc_dtcot_servicio.codser NOT IN (SELECT soc_dtcot_servicio.codart 
																   FROM soc_dtcot_servicio 
																  WHERE $ls_strsql)";
		   }
		if ($as_tipcot=='B')
		   {
			 $ls_sql = "SELECT DISTINCT soc_dtcot_bienes.codart as codigo,siv_articulo.denart as denominacion
						  FROM soc_dtcot_bienes, siv_articulo
						 WHERE soc_dtcot_bienes.codemp='".$ls_empresa."'
						   AND soc_dtcot_bienes.numcot='".$as_numcot."'
						   AND soc_dtcot_bienes.cod_pro='".$as_codpro."'
						   AND soc_dtcot_bienes.codemp=siv_articulo.codemp 
						   AND siv_articulo.codart=soc_dtcot_bienes.codart $ls_sqlaux";
		   }
		elseif($as_tipcot=='S')
		   {
		     $ls_sql= "SELECT DISTINCT soc_dtcot_servicio.codser as codigo,soc_servicios.denser as denominacion
						 FROM soc_dtcot_servicio, soc_servicios
					    WHERE soc_dtcot_servicio.codemp='".$ls_empresa."'
					      AND soc_dtcot_servicio.numcot='".$as_numcot."'
						  AND soc_dtcot_servicio.cod_pro='".$as_codpro."' 
						  AND soc_dtcot_servicio.codemp=soc_servicios.codemp 
						  AND soc_servicios.codser=soc_dtcot_servicio.codser $ls_sqlaux";		
		   }
		$rs_data=$io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
		     $io_mensajes->message("CLASS->sigesp_soc_c_analisis_cotizacion_ajax.php;Metodo->;ERROR->uf_select_items_cotizacion".$io_funciones->uf_convertirmsg($io_sql->message)); 
			 $lb_valido=false;	
		   }
		else
		   {
		     $li_i = 0;
			 while($row=$io_sql->fetch_row($rs_data))
				  {
				    $li_i++;
					$aa_items[$li_i]["codigo"]       = trim($row["codigo"]);
					$aa_items[$li_i]["denominacion"] = $row["denominacion"];					
				  }																
			}		
	}  //Fin funcion uf_select_items_cotizacion.	
	
	function uf_cargar_cotizaciones()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_cotizaciones
		//		   Access: public
		//		  return :	arreglo que contiene las cotizaciones que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		global $ls_empresa;
		global $io_sql;
		global $io_mensajes;
		global $io_funciones;
		global $io_funciones_soc;
		$aa_items=array();
		$lb_valido=false;
		$ls_numanacot=$io_funciones_soc->uf_obtenervalor("numanacot","");				
		$ls_sql= "SELECT a.numanacot, a.fecanacot, a.obsana, a.tipsolcot, a.numsolcot, cxa.numcot, cxa.cod_pro,c.feccot, c.montotcot,c.poriva,p.nompro
				  FROM soc_analisicotizacion a, soc_cotizacion c, rpc_proveedor p, soc_cotxanalisis cxa
				  WHERE a.codemp='$ls_empresa' AND a.numanacot='$ls_numanacot'
				  AND a.codemp=c.codemp AND cxa.cod_pro=c.cod_pro AND cxa.numcot=c.numcot
				  AND a.codemp=p.codemp AND cxa.cod_pro=p.cod_pro
				  AND a.codemp=cxa.codemp and a.numanacot=cxa.numanacot";		
		
			$rs_data=$io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->uf_cargar_cotizaciones".$io_funciones->uf_convertirmsg($io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				$li_i=0;
				while($row=$io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
				{
					$li_i++;
					$aa_items[$li_i]=$row;					
				}																
			}
		return $aa_items;
	}
	
	function uf_cargar_items(&$aa_items)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		global $ls_empresa;
		global $io_sql;
		global $io_mensajes;
		global $io_funciones;
		global $io_funciones_soc;
		$aa_items=array();
		$lb_valido=false;
		$ls_numanacot=$io_funciones_soc->uf_obtenervalor("numanacot","");
		$ls_tipsolcot=$io_funciones_soc->uf_obtenervalor("txttipsolcot1","");
		if($ls_tipsolcot=="Bienes" || $ls_tipsolcot=="B")
		{				
			$ls_sql="SELECT d.codart as codigo, a.denart as denominacion, p.nompro, dt.canart as cantidad, dt.preuniart as precio, dt.moniva,dt.montotart as monto,
					d.obsanacot, d.numcot, d.cod_pro
					FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt
					WHERE
					d.codemp='$ls_empresa' AND d.numanacot='$ls_numanacot' AND d.codemp=a.codemp AND a.codemp=p.codemp AND p.codemp=dt.codemp AND
					d.codart=a.codart AND d.cod_pro=p.cod_pro AND d.numcot=dt.numcot AND d.cod_pro=dt.cod_pro AND d.codart=dt.codart";				
		}
		elseif($ls_tipsolcot=="Servicios" || $ls_tipsolcot=="S")
		{
				$ls_sql="SELECT d.codser as codigo, a.denser as denominacion, p.nompro, dt.canser as cantidad, dt.monuniser as precio, dt.moniva,dt.montotser as monto,
					d.obsanacot, d.numcot, d.cod_pro
					FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt
					WHERE
					d.codemp='$ls_empresa' AND d.numanacot='$ls_numanacot' AND d.codemp=a.codemp AND a.codemp=p.codemp AND p.codemp=dt.codemp AND
					d.codser=a.codser AND d.cod_pro=p.cod_pro AND d.numcot=dt.numcot AND d.cod_pro=dt.cod_pro AND d.codser=dt.codser";				
		}
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$li_i++;
				$aa_items[$li_i]=$row;					
			}																
		}
		return $aa_items;
	}
	
	function uf_cargar_analisis()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_analisis
		//		   Access: public
		//		  return :	
		//	  Description: Metodo que carga un analisis de cotizacion seleccionado de un catalogo
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_cotizaciones=uf_cargar_cotizaciones();
		$li_totalcotizaciones=count($la_cotizaciones);		
		uf_print_cotizaciones(($li_totalcotizaciones+1),$la_cotizaciones);		
	}
?>