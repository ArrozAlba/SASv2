<?php
	session_start();
	require_once("../../../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	require_once("../../class_folder/class_funciones_cfg.php");
	$io_funciones_cfg=new class_funciones_cfg();
	$li_totrows = $io_funciones_cfg->uf_obtenervalor("totrows","1");
	// Proceso a Ejecutar.
	$ls_proceso = $io_funciones_cfg->uf_obtenervalor("proceso","");
	
	switch($ls_proceso){
	  case "LIMPIAR":
	    uf_print_detalles($li_totrows);
	  break;
	  case "LOADDT":
	    uf_load_dt_fuentefinanciamiento();
	  break;
	  case "DELETE_DT":
	    uf_delete_dt_fuentefinanciamiento();
	  break;
	}
    
	function uf_print_detalles($ai_totrows)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_print_detalles
	//		   Access: private
	//	    Arguments: ai_totrows  // Total de filas a imprimir
	//	  Description: Método que imprime el grid de las Fuentes de Financiamiento.
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 22/12/2008.								Fecha Última Modificación : 22/12/2008.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  global $io_grid, $io_funciones_cfg;
	  
	  // Titulos del Grid de Fuentes de Financiamiento.
	  $lo_title[1] = "C&oacute;digo";
	  $lo_title[2] = "Denominaci&oacute;n";
	  $lo_title[3] = "";

	  // Recorrido de las Fuentes de Financiamiento del Grid.
	  $li_totrows = 0;
	  for ($li_fila=1;$li_fila<=$ai_totrows;$li_fila++)
		  {
			$ls_codfuefin = $io_funciones_cfg->uf_obtenervalor("txtcodfuefin".$li_fila,"");
			$ls_denfuefin = utf8_decode($io_funciones_cfg->uf_obtenervalor("txtdenfuefin".$li_fila,""));
			$lb_exifuefin = $io_funciones_cfg->uf_obtenervalor("hidexiste".$li_fila,"false");
			
			$li_totrows++;
			$lo_object[$li_totrows][1]="<input type=text name=txtcodfuefin".$li_totrows."  id=txtcodfuefin".$li_totrows."  class=sin-borde style=text-align:center size=24  value='".$ls_codfuefin."'  readonly><input type=hidden name=hidexiste".$li_totrows."  id=hidexiste".$li_totrows." value='".$lb_exifuefin."'>";
			$lo_object[$li_totrows][2]="<input type=text name=txtdenfuefin".$li_totrows."  id=txtdenfuefin".$li_totrows."  class=sin-borde style=text-align:left   size=85  value='".$ls_denfuefin."'  readonly>";
			if (empty($ls_codfuefin))// si el la última fila no pinto el eliminar
			   {
				 $lo_object[$li_totrows][3]="";
			   }
			else
			   {
				 if ($lb_exifuefin=='true')
				    {
				      $lo_object[$li_totrows][3]="<a href=javascript:uf_delete_dt('".$li_totrows."');><img src=../../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
					}
			     else
				    {
					  $lo_object[$li_totrows][3]="<a href=javascript:uf_delete_detalle('".$li_totrows."');><img src=../../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
					}
			   }
		}
	  echo "    <tr>";
	  echo " 	  <td height='13' align='left'><a href='javascript:uf_catalogo_fuente_financiamiento();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Fuente Financiamiento' width='20' height='20' border='0'>Agregar Detalle Fuente Financiamiento</a></td>";
	  echo "    </tr>";
	  $io_grid->make_gridScroll($li_totrows,$lo_title,$lo_object,650,"Detalle Fuentes Financiamiento","gridfuentes",100);
	}
	
	function uf_load_dt_fuentefinanciamiento()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_dt_fuentefinanciamiento
		//		   Access: private
		//	    Arguments: as_numsol  // Numero de solicitud 
		//	  Description: Método que busca los bienes de la solicitud y los imprime
		//	   Creado Por: Néstor Falcón.
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_grid;
		
		// Titulos del Grid de Fuentes de Financiamiento
		$lo_title[1]="C&oacute;digo";
		$lo_title[2]="Denominaci&oacute;n";
		$lo_title[3]="";
		require_once("sigesp_spg_c_codestpro_codfuefin.php");
		$io_codfue = new sigesp_spg_c_codestpro_codfuefin("../../../");
		$ls_estcla     = $_POST["estcla"];
		$ls_codestpro1 = $_POST["codestpro1"];
		$ls_codestpro2 = $_POST["codestpro2"];
		$ls_codestpro3 = $_POST["codestpro3"];
		$ls_codestpro4 = $_POST["codestpro4"];
		$ls_codestpro5 = $_POST["codestpro5"];
		
		$la_data = $io_codfue->uf_load_dt_fuentefinanciamiento($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla);
		if (!empty($la_data))
		   {
		     $li_totrow = count($la_data);
			 if ($li_totrow>=1)
			    {
				  unset($lo_object);
				  $li_fila = 0;
				  for ($li_i=0;$li_i<$li_totrow;$li_i++)
					  {
						$li_fila++;
						$ls_codfuefin = $la_data[$li_i]["codfuefin"];
						$ls_denfuefin = $la_data[$li_i]["denfuefin"];
						$lo_object[$li_fila][1] = "<input type=text name=txtcodfuefin".$li_fila."  id=txtcodfuefin".$li_fila."  class=sin-borde style=text-align:center size=24  value='".$ls_codfuefin."'  readonly><input type=hidden name=hidexiste".$li_fila."  id=hidexiste".$li_fila." value='true'>";
						$lo_object[$li_fila][2] = "<input type=text name=txtdenfuefin".$li_fila."  id=txtdenfuefin".$li_fila."  class=sin-borde style=text-align:left   size=85  value='".$ls_denfuefin."'  title='".$ls_denfuefin."' readonly>";
						$lo_object[$li_fila][3] = "<a href=javascript:uf_delete_dt('".$li_fila."');><img src=../../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
					  }
				  echo "    <tr>";
				  echo " 	  <td height='13' align='left'><a href='javascript:uf_catalogo_fuente_financiamiento();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Fuente Financiamiento' width='20' height='20' border='0'>Agregar Detalle Fuente Financiamiento</a></td>";
				  echo "    </tr>";
				  $io_grid->make_gridScroll($li_fila,$lo_title,$lo_object,650,"Detalle Fuentes Financiamiento","gridfuentes",100);
				}
		     else
			    {
				  uf_print_detalles('1');
				}
		   }
        else
		   { 
		     uf_print_detalles('1');
		   }
		unset($io_codfue,$la_data);
	}// end function uf_load_dt_fuentefinanciamiento.

    function uf_delete_dt_fuentefinanciamiento()
	{
	  $la_seguridad["empresa"]  = $_SESSION["la_empresa"]["codemp"];
	  $la_seguridad["logusr"]   = $_SESSION["la_logusr"];
	  $la_seguridad["sistema"]  = "CFG";
	  $la_seguridad["ventanas"] = "sigesp_spg_d_codestpro_codfuefin.php";
	  
	  $ls_estcla     = $_POST["estcla"];
	  $ls_codestpro1 = $_POST["codestpro1"];
	  $ls_codestpro2 = $_POST["codestpro2"];
	  $ls_codestpro3 = $_POST["codestpro3"];
	  if ($_SESSION["la_empresa"]["estmodest"]==2)
	     {
		   $ls_codestpro4 = $_POST["codestpro4"];
		   $ls_codestpro5 = $_POST["codestpro5"];
		 }
	  else
	     {
		   $ls_codestpro4 = $ls_codestpro5 = "";
		 }
	  $li_totrows   = $_POST["totrows"];
	  $ls_codfuefin = $_POST["codfuefin"];
	  $la_datfuefin = array();
	  if (empty($ls_codfuefin))
	     {
		   for ($li_i=1;$li_i<=$li_totrows;$li_i++)
			   {
				 $ls_codfuefin = $_POST["txtcodfuefin".$li_i];
				 $ls_denfuefin = $_POST["txtdenfuefin".$li_i];
				 $la_datfuefin["codfuefin"][$li_i] = $ls_codfuefin;
				 $la_datfuefin["denfuefin"][$li_i] = $ls_denfuefin;
			   }
		 }
	  else
	     {
		   $ls_denfuefin = $_POST["denfuefin"];
		   $la_datfuefin["codfuefin"][1] = $ls_codfuefin;
		   $la_datfuefin["denfuefin"][1] = $ls_denfuefin;
		 }
	  require_once("sigesp_spg_c_codestpro_codfuefin.php");
	  $io_codfue = new sigesp_spg_c_codestpro_codfuefin("../../../");
	  $lb_valido = $io_codfue->uf_delete_dt_fuentefinanciamiento($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$la_datfuefin,$la_seguridad);
	  if (!$lb_valido)
	     {
		   uf_print_detalles($li_totrows);
		 }
	}
?>