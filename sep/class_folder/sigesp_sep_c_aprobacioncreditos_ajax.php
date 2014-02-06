<?php
	session_start(); 
	require_once("../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../shared/class_folder/class_funciones_xml.php");
	$io_xml=new class_funciones_xml();		
	require_once("class_funciones_sep.php");
	$io_funciones_sep=new class_funciones_sep();
	$ruta = '../../';
	require_once("../../shared/class_folder/sigesp_conexiones.php");
    $io_conexiones=new conexiones();
	$io_conexiones->decodificar_post();

	// tipo de SEP si es de BIENES ó de SERVICIOS
	$ls_rutaarchivo=$io_funciones_sep->uf_obtenervalor("rutaarchivo","");
	// proceso a ejecutar
	$ls_proceso=$io_funciones_sep->uf_obtenervalor("proceso","");
	switch($ls_proceso)
	{
		case "BUSCAR":
			uf_print_creditos($ls_rutaarchivo);
			break;
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_creditos($as_rutaarchivo)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print_creditos
		//		   Access: private
		//	    Arguments: as_rutaarchivo  // Ruta donde se van a leer los archivos xml
		//	  Description: Método que busca los archivos xml y los lee 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 23/07/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid, $io_funciones_sep, $io_xml;
		
		// Titulos del Grid de Bienes
        $lo_title[1]="";
		$lo_title[2]="Beneficiario";
		$lo_title[3]="Fecha Cr&eacute;dito";
		$lo_title[4]="Concepto";
		$lo_title[5]="Monto";
		$ld_fecregsol=date("d/m/Y");
		$la_archivos=$io_xml->uf_load_archivos($as_rutaarchivo);
		$li_totalarchivos=count($la_archivos["filnam"]);
		if($la_archivos=="")
		{
			$li_totalarchivos=0;
		}
		$li_fila=0;
		for($li_i=1;$li_i<=$li_totalarchivos;$li_i++)
		{
			$ls_archivo=$la_archivos["filnam"][$li_i];
			$la_data=$io_xml->uf_cargar_sep_solicitud($as_rutaarchivo."/".$ls_archivo);
			$li_total=count($la_data);
			if(!is_array($la_data))
			{
				$li_total=0;
			}
			for($i=1;$i<=$li_total;$i++)
			{
				$li_fila=$li_fila+1;
				$ls_ced_bene=rtrim($la_data[$i]["ced_bene"]);		
				$ls_consol=rtrim($la_data[$i]["consol"]);
				$li_monto=rtrim($la_data[$i]["monto"]);				
				$ls_codtipsol=rtrim($la_data[$i]["codtipsol"]);
				$ls_coduniadm=rtrim($la_data[$i]["coduniadm"]);
				$ls_estcla=rtrim($la_data[$i]["estcla"]);
				$ls_codestpro1=rtrim($la_data[$i]["codestpro1"]);
				$ls_codestpro2=rtrim($la_data[$i]["codestpro2"]);
				$ls_codestpro3=rtrim($la_data[$i]["codestpro3"]);
				$ls_codestpro4=rtrim($la_data[$i]["codestpro4"]);
				$ls_codestpro5=rtrim($la_data[$i]["codestpro5"]);
				$ls_tipo_destino=rtrim($la_data[$i]["tipo_destino"]);				
				$li_monto = number_format($li_monto,2,',','.');
						
				$lo_object[$li_fila][1] = "<input type=checkbox name=chkaprobacion".$li_fila." id=chkaprobacion".$li_fila."   value=1 >";		
				$lo_object[$li_fila][2] = "<input type=text name=txtbeneficiario".$li_fila."   id=txtbeneficiario".$li_fila." value='".$ls_ced_bene."'  class=sin-borde readonly style=text-align:center size=15 maxlength=15 >";
				$lo_object[$li_fila][3] = "<input type=text name=txtfecha".$li_fila." 		   id=txtfecha".$li_fila."        value='".$ld_fecregsol."' class=sin-borde readonly style=text-align:center size=15 maxlength=12 >";
				$lo_object[$li_fila][4] = "<input type=text name=txtconcepto".$li_fila." 	   id=txtconcepto".$li_fila."     value='".$ls_consol."'    class=sin-borde readonly style=text-align:left   size=50 maxlength=250 title='".$ls_consol."'>";
				$lo_object[$li_fila][5] = "<input type=text name=txtmonto".$li_fila." 		   id=txtmonto".$li_fila."        value='".$li_monto."'     class=sin-borde readonly style=text-align:right  size=15 maxlength=250>".
										  "<input type=hidden name=txtarchivo".$li_fila."      id=txtarchivo".$li_fila."      value='".$ls_archivo."'>".			
										  "<input type=hidden name=txtcodtipsol".$li_fila."    id=txtcodtipsol".$li_fila."    value='".$ls_codtipsol."'>".			
										  "<input type=hidden name=txtcoduniadm".$li_fila."    id=txtcoduniadm".$li_fila."    value='".$ls_coduniadm."'>".			
										  "<input type=hidden name=txtestcla".$li_fila."       id=txtestcla".$li_fila."       value='".$ls_estcla."'>".
										  "<input type=hidden name=txtcodestpro1".$li_fila."   id=txtcodestpro1".$li_fila."   value='".$ls_codestpro1."'>".
										  "<input type=hidden name=txtcodestpro2".$li_fila."   id=txtcodestpro2".$li_fila."   value='".$ls_codestpro2."'>".
										  "<input type=hidden name=txtcodestpro3".$li_fila."   id=txtcodestpro3".$li_fila."   value='".$ls_codestpro3."'>".
										  "<input type=hidden name=txtcodestpro4".$li_fila."   id=txtcodestpro4".$li_fila."   value='".$ls_codestpro4."'>".
										  "<input type=hidden name=txtcodestpro5".$li_fila."   id=txtcodestpro5".$li_fila."   value='".$ls_codestpro5."'>".
										  "<input type=hidden name=txttipodestino".$li_fila."  id=txttipodestino".$li_fila."  value='".$ls_tipo_destino."'>";
			}
		}
		if($li_fila>0)
		{
			$io_grid->makegrid($li_fila,$lo_title,$lo_object,700,"Cr&eacute;ditos por aprobar","gridbienes");
		}
	}// end function uf_print_creditos
	//-----------------------------------------------------------------------------------------------------------------------------------
?>