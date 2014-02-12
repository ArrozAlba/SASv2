<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("class_funciones_soc.php");
	$io_funciones_soc=new class_funciones_soc();
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();
	// tipo de SEP si es de BIENES ó de SERVICIOS
	$ls_tipo=$io_funciones_soc->uf_obtenervalor("tipo","-");
	// proceso a ejecutar
	$ls_proceso=$io_funciones_soc->uf_obtenervalor("proceso","");
	// Total de Filas del Grid de Bienes.
	$li_totalrowbienes = $io_funciones_soc->uf_obtenervalor("totalbienes","1");
	// Total de Filas del Grid de Servicios.
	$li_totalrowservicios = $io_funciones_soc->uf_obtenervalor("totalservicios","1");
	// Valor del Subtotal de la Cotizacion.
	$ld_subtotal=$io_funciones_soc->uf_obtenervalor("txtsubtotal","0,00");
	// Valor del Cargo de la Cotizacion
	$ld_creditos=$io_funciones_soc->uf_obtenervalor("txtcreditos","0,00");
	// Valor del Total de la Cotizacion
	$ld_total=$io_funciones_soc->uf_obtenervalor("txttotal","0,00");

	$ls_codpro    = $io_funciones_soc->uf_obtenervalor("cod_pro","----------");	
	$ls_numcot    = $io_funciones_soc->uf_obtenervalor("numcot","");
	$ls_numsolcot = $io_funciones_soc->uf_obtenervalor("numsolcot","");
    
	switch($ls_proceso)
	{
		case "LIMPIAR":
			switch($ls_tipo)
			{
				case "B": // Bienes
					$ls_titulo="Bien o Material";
					uf_print_detalles_bienes($li_totalrowbienes);
					break;
				case "S": // Servicios
					$ls_titulo="Servicios";
					uf_print_detalles_servicios($li_totalrowservicios);
					break;
			}
		break;

        case "AGREGARBIENES":
			$ls_titulo="Bien o Material";
			uf_print_detalles_bienes($li_totalrowbienes);
			break; 
		
		case "AGREGARSERVICIOS":
			$ls_titulo="Servicios";
			uf_print_detalles_servicios($li_totalrowservicios);
			break;		
		
		case "LOADBIENES":
			$ls_titulo="Bien o Material";
			uf_load_bienes_solicitud($ls_numsolcot,$ls_codpro);
			break;

		case "LOADSERVICIOS":
			$ls_titulo="Servicios";
			uf_load_servicios_solicitud($ls_numsolcot,$ls_codpro);
			break;
   
		case "CARGAR_DT_BIENES":
			$ls_titulo="Bien o Material";
			uf_load_bienes($ls_numcot,$ls_codpro);
			break;

		case "CARGAR_DT_SERVICIOS":
			$ls_titulo="Servicios";
			uf_load_servicios($ls_numcot,$ls_codpro);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalles_bienes($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_detalles_bienes
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los Bienes
		//	   Creado Por: Ing. Néstor Falcon
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Bienes
	    $lo_title[1] = "C&oacute;digo"; 
		$lo_title[2] = "Denominaci&oacute;n"; 
		$lo_title[3] = "Cantidad"; 
		$lo_title[4] = "Precio"; 
		$lo_title[5] = "Subtotal";
		$lo_title[6] = "Cr&eacute;ditos"; 
		$lo_title[7] = "Total"; 
		$lo_title[8] = "Calidad"; 
		$lo_title[9] = "";

		// Recorrido de todos los Bienes del Grid
		for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		    {
			  $ls_codart = $io_funciones_soc->uf_obtenervalor("txtcodart".$li_fila,"");
			  $ls_denart = $io_funciones_soc->uf_obtenervalor("txtdenart".$li_fila,"");
			  $ld_canart = $io_funciones_soc->uf_obtenervalor("txtcanart".$li_fila,"0,00");
			  $ld_preart = $io_funciones_soc->uf_obtenervalor("txtpreart".$li_fila,"0,00");
			  $ld_subart = $io_funciones_soc->uf_obtenervalor("txtsubart".$li_fila,"0,00");
			  $ld_creart = $io_funciones_soc->uf_obtenervalor("txtcreart".$li_fila,"0,00"); 
			  $ld_totart = $io_funciones_soc->uf_obtenervalor("txttotart".$li_fila,"0,00");
			  $ls_calart = $io_funciones_soc->uf_obtenervalor("cmbcalart".$li_fila,"0,00"); 
			  $ld_porcre = $io_funciones_soc->uf_obtenervalor("hidporcre".$li_fila,"0");
			  $ls_excsel = '';
			  $ls_buesel = '';
			  $ls_regsel = '';
			  $ls_malsel = '';
			  $ls_mumsel = '';
			  switch ($ls_calart){
			    case 'E':
				  $ls_excsel = 'selected';
				break;
			    case 'B':
				  $ls_buesel = 'selected';
				break;
			    case 'R':
				  $ls_regsel = 'selected';
				break;
			    case 'M':
				  $ls_malsel = 'selected';
				break;
			    case 'P':
				  $ls_mumsel = 'selected';
				break;
			  }
			  
			  $lo_object[$li_fila][1] = "<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_codart."'  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value='".$ld_porcre."'>";
			  $lo_object[$li_fila][2] = "<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=60  value='".$ls_denart."'  readonly>";
			  $lo_object[$li_fila][3] = "<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=10  value='".$ld_canart."'  readonly>"; 
			  $lo_object[$li_fila][4] = "<input type=text name=txtpreart".$li_fila."  id=txtpreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_preart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			  $lo_object[$li_fila][5] = "<input type=text name=txtsubart".$li_fila."  id=txtsubart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_subart."'  readonly>"; 
			  $lo_object[$li_fila][6] = "<input type=text name=txtcreart".$li_fila."  id=txtcreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_creart."'  readonly>"; 
			  $lo_object[$li_fila][7] = "<input type=text name=txttotart".$li_fila."  id=txttotart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_totart."'  readonly>"; 
			  $lo_object[$li_fila][8] = "<select name=cmbcalart".$li_fila." style='width:75px '><option value=E ".$ls_excsel.">Excelente</option><option value=B ".$ls_buesel.">Bueno</option><option value=R ".$ls_regsel.">Regular</option><option value=M ".$ls_malsel.">Malo</option><option value=P ".$ls_mumsel.">Muy Malo</option></select>";

			  if ($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			     {
				   $lo_object[$li_fila][9]="";
		  	     }
			  else
			     {
				   $lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			     }
		}
		print "<p>&nbsp;</p>";
		$io_grid->make_gridScroll($ai_total,$lo_title,$lo_object,795,"Detalle de Bienes","gridbienes",150);
	}// end function uf_print_detalles_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalles_servicios($ai_total)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_servicios
		//		   Access: private
		//	    Arguments: ai_total  // Total de filas a imprimir
		//	  Description: Método que imprime el grid de los servicios
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 13/04/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;

		//Titulos de la tabla de Detalle Servicios
		  $lo_title[1] = "C&oacute;digo"; 
		  $lo_title[2] = "Denominaci&oacute;n"; 
		  $lo_title[3] = "Cantidad"; 
		  $lo_title[4] = "Precio";
		  $lo_title[5] = "Subtotal"; 
		  $lo_title[6] = "Cargos";
		  $lo_title[7] = "Total";
		  $lo_title[8] = "Calidad"; 
		  $lo_title[9] = "";
		  for ($li_fila=1;$li_fila<=$ai_total;$li_fila++)
		      {
			    $ls_codser = $io_funciones_soc->uf_obtenervalor("txtcodser".$li_fila,"");
			    $ls_denser = $io_funciones_soc->uf_obtenervalor("txtdenser".$li_fila,"");
			    $ld_canser = $io_funciones_soc->uf_obtenervalor("txtcanser".$li_fila,"0,00");
			    $ld_preser = $io_funciones_soc->uf_obtenervalor("txtpreser".$li_fila,"0,00");
				$ld_subser = $io_funciones_soc->uf_obtenervalor("txtsubser".$li_fila,"0,00");
				$ld_creser = $io_funciones_soc->uf_obtenervalor("txtcreser".$li_fila,"0,00");
				$ld_totser = $io_funciones_soc->uf_obtenervalor("txttotser".$li_fila,"0,00");
		        $ls_calser = $io_funciones_soc->uf_obtenervalor("cmbcalser".$li_fila,"-");
				$ld_porcre = $io_funciones_soc->uf_obtenervalor("hidporcre".$li_fila,"0");
			    $ls_excsel = '';
			    $ls_buesel = '';
			    $ls_regsel = '';
			    $ls_malsel = '';
			    $ls_mumsel = '';
			
				  switch ($ls_calser){
					case 'E':
					  $ls_excsel = 'selected';
					break;
					case 'B':
					  $ls_buesel = 'selected';
					break;
					case 'R':
					  $ls_regsel = 'selected';
					break;
					case 'M':
					  $ls_malsel = 'selected';
					break;
					case 'P':
					  $ls_mumsel = 'selected';
					break;
				  }
				
				$lo_object[$li_fila][1] = "<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_codser."'  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value='".$ld_porcre."'>";
				$lo_object[$li_fila][2] = "<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=60  value='".$ls_denser."'  readonly>";
				$lo_object[$li_fila][3] = "<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_canser."'  readonly>"; 
				$lo_object[$li_fila][4] = "<input type=text name=txtpreser".$li_fila."  id=txtpreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_preser."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
				$lo_object[$li_fila][5] = "<input type=text name=txtsubser".$li_fila."  id=txtsubser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_subser."'  readonly>"; 
				$lo_object[$li_fila][6] = "<input type=text name=txtcreser".$li_fila."  id=txtcreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_creser."'  readonly>"; 
				$lo_object[$li_fila][7] = "<input type=text name=txttotser".$li_fila."  id=txttotser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_totser."'  readonly>"; 
				$lo_object[$li_fila][8] = "<select name=cmbcalser".$li_fila." style='width:75px '><option value=E ".$ls_excsel.">Excelente</option><option value=B ".$ls_buesel.">Bueno</option><option value=R ".$ls_regsel.">Regular</option><option value=M ".$ls_malsel.">Malo</option><option value=P ".$ls_mumsel.">Muy Malo</option></select>";
			
				if ($li_fila==$ai_total)// si el la última fila no pinto el eliminar
			       {
				     $lo_object[$li_fila][9]="";
			       }
			    else
			       {
				     $lo_object[$li_fila][9] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			       }
		      }
		print "<p>&nbsp;</p>";
		$io_grid->make_gridScroll($ai_total,$lo_title,$lo_object,800,"Detalle de Servicios","gridservicios",150);
	}// end function uf_print_detalles_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes_solicitud($as_numsolcot,$as_codpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud de cotizacion.
		//	  Description: Método que busca los bienes de la solicitud y los imprime
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 28/05/2007								Fecha Última Modificación : 28/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Bienes
	    $lo_title[1] = "C&oacute;digo"; 
		$lo_title[2] = "Denominaci&oacute;n"; 
		$lo_title[3] = "Cantidad"; 
		$lo_title[4] = "Precio"; 
		$lo_title[5] = "Subtotal";
		$lo_title[6] = "Cr&eacute;ditos"; 
		$lo_title[7] = "Total"; 
		$lo_title[8] = "Calidad"; 
		$lo_title[9] = "";
		
		require_once("sigesp_soc_c_registro_cotizacion.php");
		$io_registro = new sigesp_soc_c_registro_cotizacion("../../");
		$rs_data     = $io_registro->uf_load_bienes_solicitud($as_numsolcot,$as_codpro);
		$li_fila=0;
		$lb_valido = true;
		
		$ls_excsel = 'selected';
		$ls_buesel = '';
		$ls_regsel = '';
		$ls_malsel = '';
		$ls_mumsel = '';
				
		while ($row=$io_registro->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila++;
				$ls_codart = $row["codart"];
				$ld_porcre = $io_registro->uf_load_porcentaje_credito($ls_codart,'B',$lb_valido);//Porcentaje del Crédito en caso de tenerlo.
				$ls_denart = $row["denart"];
				$ld_canart = number_format($row["canart"],2,',','.');
			   
			    $lo_object[$li_fila][1] = "<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_codart."'  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value='".$ld_porcre."'>";
			    $lo_object[$li_fila][2] = "<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=60  value='".$ls_denart."'  readonly>";
			    $lo_object[$li_fila][3] = "<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=10  value='".$ld_canart."'  readonly>"; 
			    $lo_object[$li_fila][4] = "<input type=text name=txtpreart".$li_fila."  id=txtpreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');>"; 
			    $lo_object[$li_fila][5] = "<input type=text name=txtsubart".$li_fila."  id=txtsubart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  readonly>"; 
			    $lo_object[$li_fila][6] = "<input type=text name=txtcreart".$li_fila."  id=txtcreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  readonly>"; 
			    $lo_object[$li_fila][7] = "<input type=text name=txttotart".$li_fila."  id=txttotart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  readonly>"; 
			    $lo_object[$li_fila][8] = "<select name=cmbcalart".$li_fila." style='width:75px '><option value=E ".$ls_excsel.">Excelente</option><option value=B ".$ls_buesel.">Bueno</option><option value=R ".$ls_regsel.">Regular</option><option value=M ".$ls_malsel.">Malo</option><option value=P ".$ls_mumsel.">Muy Malo</option></select>";
			    $lo_object[$li_fila][9]="<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		      }
		$li_fila++;
		$lo_object[$li_fila][1] = "<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=20  value=''  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value=''>";
		$lo_object[$li_fila][2] = "<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=60  value=''  readonly>";
		$lo_object[$li_fila][3] = "<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=10  value=''  readonly>"; 
		$lo_object[$li_fila][4] = "<input type=text name=txtpreart".$li_fila."  id=txtpreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][5] = "<input type=text name=txtsubart".$li_fila."  id=txtsubart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][6] = "<input type=text name=txtcreart".$li_fila."  id=txtcreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][7] = "<input type=text name=txttotart".$li_fila."  id=txttotart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][8] = "";
		$lo_object[$li_fila][9] = "";
		print "<p>&nbsp;</p>";
		unset($io_registro);
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,795,"Detalle de Bienes","gridbienes",150);
	}// end function uf_load_bienes_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios_solicitud($as_numsolcot,$as_codpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los servicios de la solicitud y los imprime
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		//Titulos de la tabla de Detalle Servicios
	    $lo_title[1] = "C&oacute;digo";
		$lo_title[2] = "Denominaci&oacute;n"; 
	    $lo_title[3] = "Cantidad"; 
	    $lo_title[4] = "Precio";
	    $lo_title[5] = "Subtotal"; 
	    $lo_title[6] = "Cargos";
	    $lo_title[7] = "Total";
	    $lo_title[8] = "Calidad"; 
	    $lo_title[9] = "";
		  
		require_once("sigesp_soc_c_registro_cotizacion.php");
		$io_registro = new sigesp_soc_c_registro_cotizacion("../../");
		$rs_data     = $io_registro->uf_load_servicios_solicitud($as_numsolcot,$as_codpro);
		$li_fila=0;
		$lb_valido = true;

	    $ls_excsel = 'selected';
	    $ls_buesel = '';
	    $ls_regsel = '';
	    $ls_malsel = '';
	    $ls_mumsel = '';

		while($row=$io_registro->io_sql->fetch_row($rs_data))	  
		     {
			   $li_fila++;
			   $ls_codser = $row["codser"];
			   $ld_porcre = $io_registro->uf_load_porcentaje_credito($ls_codser,'S',$lb_valido);//Porcentaje del Crédito en caso de tenerlo.
			   $ls_denser = $row["denser"];
			   $ld_canser = number_format($row["canser"],2,',','.');
			   
			   $lo_object[$li_fila][1] = "<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_codser."'  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value='".$ld_porcre."'>";
			   $lo_object[$li_fila][2] = "<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=60  value='".$ls_denser."'  readonly>";
			   $lo_object[$li_fila][3] = "<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_canser."'  readonly>"; 
			   $lo_object[$li_fila][4] = "<input type=text name=txtpreser".$li_fila."  id=txtpreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');>"; 
			   $lo_object[$li_fila][5] = "<input type=text name=txtsubser".$li_fila."  id=txtsubser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  readonly>"; 
			   $lo_object[$li_fila][6] = "<input type=text name=txtcreser".$li_fila."  id=txtcreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  readonly>"; 
			   $lo_object[$li_fila][7] = "<input type=text name=txttotser".$li_fila."  id=txttotser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='0,00'  readonly>"; 
			   $lo_object[$li_fila][8] = "<select name=cmbcalser".$li_fila." style='width:75px '><option value=E ".$ls_excsel.">Excelente</option><option value=B ".$ls_buesel.">Bueno</option><option value=R ".$ls_regsel.">Regular</option><option value=M ".$ls_malsel.">Malo</option><option value=P ".$ls_mumsel.">Muy Malo</option></select>";
			   $lo_object[$li_fila][9] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			 }
	   $li_fila++;
	   $lo_object[$li_fila][1] = "<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20  value=''  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value=''>";
	   $lo_object[$li_fila][2] = "<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=60  value=''  readonly>";
	   $lo_object[$li_fila][3] = "<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][4] = "<input type=text name=txtpreser".$li_fila."  id=txtpreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][5] = "<input type=text name=txtsubser".$li_fila."  id=txtsubser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][6] = "<input type=text name=txtcreser".$li_fila."  id=txtcreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][7] = "<input type=text name=txttotser".$li_fila."  id=txttotser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][8] = "";
	   $lo_object[$li_fila][9] = "";
		print "<p>&nbsp;</p>";
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,795,"Detalle de Servicios","gridservicios",150);
		unset($io_registro);
	}// end function uf_load_servicios_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numcot,$as_codpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de cotizacion.
		//	  Description: Método que busca los bienes de la Cotización y los imprime
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 28/05/2007								Fecha Última Modificación : 28/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_soc;
		
		// Titulos del Grid de Bienes
	    $lo_title[1] = "C&oacute;digo"; 
		$lo_title[2] = "Denominaci&oacute;n"; 
		$lo_title[3] = "Cantidad"; 
		$lo_title[4] = "Precio"; 
		$lo_title[5] = "Subtotal";
		$lo_title[6] = "Cr&eacute;ditos"; 
		$lo_title[7] = "Total"; 
		$lo_title[8] = "Calidad"; 
		$lo_title[9] = "";
		
		require_once("sigesp_soc_c_registro_cotizacion.php");
		$io_registro = new sigesp_soc_c_registro_cotizacion("../../");
		$rs_data     = $io_registro->uf_load_bienes($as_numcot,$as_codpro);
		$li_fila     = 0;
		
		$ls_excsel   = 'selected';
		$ls_buesel   = '';
		$ls_regsel   = '';
		$ls_malsel   = '';
		$ls_mumsel   =  '';
		$lb_valido   = true;
		$ls_opcion   = '';
		$ls_disabled = '';
		
		while ($row=$io_registro->io_sql->fetch_row($rs_data))	  
		      {
			    $li_fila++;
				$ls_codart = $row["codart"];
				$ls_denart = $row["denart"];
				$ld_canart = number_format($row["canart"],2,',','.');
				$ld_preart = number_format($row["preuniart"],2,',','.');
				$ld_subart = number_format($row["monsubart"],2,',','.');
				$ld_creart = number_format($row["moniva"],2,',','.');
				$ld_totart = number_format($row["montotart"],2,',','.');
			    $ld_porcre = $io_registro->uf_load_porcentaje_credito($ls_codart,'B',$lb_valido);//Porcentaje del Crédito en caso de tenerlo.
				if ($li_fila==1)//Estatus del Registro de la Cotizacion.
				   {
				     $ls_estcot = $row["estcot"];
				     if ($ls_estcot==0)
					    {
						  $ls_opcion = "onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('B','".$li_fila."');";
						}
				     else
					    {
						  $ls_opcion   = "readonly";
						  $ls_disabled = "disabled";
						}
				   }
				$ls_calart = $row["nivcalart"];
				switch ($ls_calart){
				  case 'E':
				    $ls_excsel = 'selected';    
				  break;
				  case 'B':
				    $ls_buesel = 'selected';    
				  break;
				  case 'R':
				    $ls_regsel = 'selected';    
				  break;
				  case 'M':
				    $ls_malsel = 'selected';    
				  break;
				  case 'P':
				    $ls_mumsel = 'selected';    
				  break;
				}

				$lo_object[$li_fila][1] = "<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_codart."'  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value='".$ld_porcre."'>";
			    $lo_object[$li_fila][2] = "<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=60  value='".$ls_denart."'  readonly>";
			    $lo_object[$li_fila][3] = "<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=10  value='".$ld_canart."'  readonly>"; 
			    $lo_object[$li_fila][4] = "<input type=text name=txtpreart".$li_fila."  id=txtpreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_preart."'  $ls_opcion>"; 
			    $lo_object[$li_fila][5] = "<input type=text name=txtsubart".$li_fila."  id=txtsubart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_subart."'  readonly>"; 
			    $lo_object[$li_fila][6] = "<input type=text name=txtcreart".$li_fila."  id=txtcreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_creart."'  readonly>"; 
			    $lo_object[$li_fila][7] = "<input type=text name=txttotart".$li_fila."  id=txttotart".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_totart."'  readonly>"; 
			    $lo_object[$li_fila][8] = "<select name=cmbcalart".$li_fila." style='width:75px ' $ls_disabled><option value=E ".$ls_excsel.">Excelente</option><option value=B ".$ls_buesel.">Bueno</option><option value=R ".$ls_regsel.">Regular</option><option value=M ".$ls_malsel.">Malo</option><option value=P ".$ls_mumsel.">Muy Malo</option></select>";
			    $lo_object[$li_fila][9] = "<a href=javascript:ue_delete_bienes('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		      }
		$li_fila++;
		$lo_object[$li_fila][1] = "<input type=text name=txtcodart".$li_fila."  id=txtcodart".$li_fila."  class=sin-borde style=text-align:center size=20  value=''  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value=''>";
		$lo_object[$li_fila][2] = "<input type=text name=txtdenart".$li_fila."  id=txtdenart".$li_fila."  class=sin-borde style=text-align:left   size=60  value=''  readonly>";
		$lo_object[$li_fila][3] = "<input type=text name=txtcanart".$li_fila."  id=txtcanart".$li_fila."  class=sin-borde style=text-align:right  size=10  value=''  readonly>"; 
		$lo_object[$li_fila][4] = "<input type=text name=txtpreart".$li_fila."  id=txtpreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][5] = "<input type=text name=txtsubart".$li_fila."  id=txtsubart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][6] = "<input type=text name=txtcreart".$li_fila."  id=txtcreart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][7] = "<input type=text name=txttotart".$li_fila."  id=txttotart".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
		$lo_object[$li_fila][8] = "";
		$lo_object[$li_fila][9] = "";
		print "<p>&nbsp;</p>";
		unset($io_registro);
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,795,"Detalle de Bienes","gridbienes",150);
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numcot,$as_codpro)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los servicios de la solicitud y los imprime
		//	   Creado Por: Ing. Néstor Falcon.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		//Titulos de la tabla de Detalle Servicios
	    $lo_title[1] = "C&oacute;digo"; 
	    $lo_title[2] = "Denominaci&oacute;n"; 
	    $lo_title[3] = "Cantidad"; 
	    $lo_title[4] = "Precio";
	    $lo_title[5] = "Subtotal"; 
	    $lo_title[6] = "Cargos";
	    $lo_title[7] = "Total";
	    $lo_title[8] = "Calidad"; 
	    $lo_title[9] = "";
		  
		require_once("sigesp_soc_c_registro_cotizacion.php");
		$io_registro = new sigesp_soc_c_registro_cotizacion("../../");
		$rs_data     = $io_registro->uf_load_servicios($as_numcot,$as_codpro);
		$li_fila=0;
		
		$ls_excsel   = 'selected';
		$ls_buesel   = '';
		$ls_regsel   = '';
		$ls_malsel   = '';
		$ls_mumsel   = '';
		$ls_opcion   = '';
		$ls_disabled = '';
		$lb_valido = true;
		
		while($row=$io_registro->io_sql->fetch_row($rs_data))	  
		     {
			   $li_fila++;
			   $ls_codser = $row["codser"];
			   $ld_porcre = $io_registro->uf_load_porcentaje_credito($ls_codser,'S',$lb_valido);//Porcentaje del Crédito en caso de tenerlo.
			   $ls_denser = $row["denser"];
			   $ld_canser = number_format($row["canser"],2,',','.');
			   if ($li_fila==1)//Estatus del Registro de la Cotizacion.
				  {
				    $ls_estcot = $row["estcot"];
				    if ($ls_estcot==0)
					   {
					     $ls_opcion = "onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_procesar_monto('S','".$li_fila."');";
					   }
				    else
					   {
					     $ls_opcion = "readonly";
					     $ls_disabled = "disabled";
					   }
				  }
			   
			   $ls_calser = $row["nivcalser"];
			   switch ($ls_calser){
				  case 'E':
				    $ls_excsel = 'selected';    
				  break;
				  case 'B':
				    $ls_buesel = 'selected';    
				  break;
				  case 'R':
				    $ls_regsel = 'selected';    
				  break;
				  case 'M':
				    $ls_malsel = 'selected';    
				  break;
				  case 'P':
				    $ls_mumsel = 'selected';    
				  break;
			   }
			   $ld_preser = number_format($row["monuniser"],2,',','.');
			   $ld_subser = number_format($row["monsubser"],2,',','.');
			   $ld_creser = number_format($row["moniva"],2,',','.');
			   $ld_totser = number_format($row["montotser"],2,',','.');

			   $lo_object[$li_fila][1] = "<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20  value='".$ls_codser."'  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value='".$ld_porcre."'>";
			   $lo_object[$li_fila][2] = "<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=60  value='".$ls_denser."'  readonly>";
			   $lo_object[$li_fila][3] = "<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_canser."'  readonly>"; 
			   $lo_object[$li_fila][4] = "<input type=text name=txtpreser".$li_fila."  id=txtpreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_preser."'  $ls_opcion>"; 
			   $lo_object[$li_fila][5] = "<input type=text name=txtsubser".$li_fila."  id=txtsubser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_subser."'  readonly>"; 
			   $lo_object[$li_fila][6] = "<input type=text name=txtcreser".$li_fila."  id=txtcreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_creser."'  readonly>"; 
			   $lo_object[$li_fila][7] = "<input type=text name=txttotser".$li_fila."  id=txttotser".$li_fila."  class=sin-borde style=text-align:right  size=15  value='".$ld_totser."'  readonly>"; 
			   $lo_object[$li_fila][8] = "<select name=cmbcalser".$li_fila." style='width:75px ' $ls_disabled><option value=E ".$ls_excsel.">Excelente</option><option value=B ".$ls_buesel.">Bueno</option><option value=R ".$ls_regsel.">Regular</option><option value=M ".$ls_malsel.">Malo</option><option value=P ".$ls_mumsel.">Muy Malo</option></select>";
			   $lo_object[$li_fila][9] ="<a href=javascript:ue_delete_servicios('".$li_fila."');><img src=../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
			 }
	   $li_fila++;
	   $lo_object[$li_fila][1] = "<input type=text name=txtcodser".$li_fila."  id=txtcodser".$li_fila."  class=sin-borde style=text-align:center size=20  value=''  readonly><input type=hidden name=hidporcre".$li_fila." id=hidporcre".$li_fila." value=''>";
	   $lo_object[$li_fila][2] = "<input type=text name=txtdenser".$li_fila."  id=txtdenser".$li_fila."  class=sin-borde style=text-align:left   size=60  value=''  readonly>";
	   $lo_object[$li_fila][3] = "<input type=text name=txtcanser".$li_fila."  id=txtcanser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][4] = "<input type=text name=txtpreser".$li_fila."  id=txtpreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][5] = "<input type=text name=txtsubser".$li_fila."  id=txtsubser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][6] = "<input type=text name=txtcreser".$li_fila."  id=txtcreser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][7] = "<input type=text name=txttotser".$li_fila."  id=txttotser".$li_fila."  class=sin-borde style=text-align:right  size=15  value=''  readonly>"; 
	   $lo_object[$li_fila][8] = "";
	   $lo_object[$li_fila][9] = "";
		print "<p>&nbsp;</p>";
		$io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,795,"Detalle de Servicios","gridservicios",150);
		unset($io_registro);
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------
?>