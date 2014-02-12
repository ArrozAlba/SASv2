<?php
class sigesp_spg_c_codestpro_codfuefin{
  
  function sigesp_spg_c_codestpro_codfuefin($as_path)
  {
	////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_spg_c_codestpro_codfuefin
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 24/12/2008. 								Fecha Última Modificación : 
	////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_fecha.php");
		require_once($as_path."shared/class_folder/sigesp_include.php");		
		require_once($as_path."shared/class_folder/class_mensajes.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/grid_param.php");

		$io_include			 = new sigesp_include();
		$io_conexion		 = $io_include->uf_conectar();
		$this->io_sql        = new class_sql($io_conexion);	
		$this->io_mensajes   = new class_mensajes();		
		$this->io_funciones  = new class_funciones();	
		$this->io_seguridad  = new sigesp_c_seguridad();
		$this->io_fecha      = new class_fecha();
		$this->io_grid		 = new grid_param();
		$this->ls_codemp     = $_SESSION["la_empresa"]["codemp"]; 
  }

  function uf_insert_codestpro_codfuefin($as_existe,$ai_totrows,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
                                         $as_codestpro5,$as_estcla,$aa_seguridad)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_insert_bienes
	//		   Access: private
	//	    Arguments: $ai_totrows     // Número de Filas del Grid de las Fuentes de Financiamiento.
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que inserta los bienes de una  Solicitud de Cotizacion.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 24/12/2008 								Fecha Última Modificación : 24/12/2008.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = true;
    $ls_codestpro1 = str_pad($as_codestpro1,25,0,0);
    $ls_codestpro2 = str_pad($as_codestpro2,25,0,0);
    $ls_codestpro3 = str_pad($as_codestpro3,25,0,0);
    $ls_codestpro4 = str_pad($as_codestpro4,25,0,0);
    $ls_codestpro5 = str_pad($as_codestpro5,25,0,0);
    
	$this->io_sql->begin_transaction();
	for ($li_i=1;$li_i<=$ai_totrows;$li_i++)
	    {
	      $ls_codfuefin = $_POST["txtcodfuefin".$li_i];
		  $ls_denfuefin = $_POST["txtdenfuefin".$li_i];
		  $lb_exifuefin = $_POST["hidexiste".$li_i];
		  if (!empty($ls_codfuefin))
		     { 
			   if ($lb_exifuefin=='false')
			      {
			        $ls_sql = "INSERT INTO spg_dt_fuentefinanciamiento(codemp,codfuefin,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) 
			                   VALUES ('".$this->ls_codemp."','".$ls_codfuefin."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$as_estcla."')";
			   
			        $rs_data = $this->io_sql->execute($ls_sql);
			        if ($rs_data===false)
					   {
						 $this->io_sql->rollback();
						 $this->io_mensajes->message("CLASE->sigesp_spg_c_codestpro_codfuefin.php->MÉTODO->uf_insert_codestpro_codfuefin. ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						 return false;
					   }
				    else
					   {
						 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						 $ls_evento="INSERT";
						 if ($_SESSION["la_empresa"]["estmodest"]==2)
						    {
							  $ls_descripcion ="Insertó la Fuente de Financiamiento $ls_codfuefin - $ls_denfuefin, asociada a la Estructura Presupuestaria $as_codestpro1-$as_codestpro2-$as_codestpro3-$as_codestpro4-$as_codestpro5 y de tipo $as_estcla a la empresa ".$this->ls_codemp;
						    }
						 else
						    {
							  $ls_descripcion ="Insertó la Fuente de Financiamiento $ls_codfuefin - $ls_denfuefin, asociada a la Estructura Presupuestaria $as_codestpro1-$as_codestpro2-$as_codestpro3 y de tipo $as_estcla a la empresa ".$this->ls_codemp;
						    }
						 $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														 $aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				       }
			      }	
		     }	
        }
	if ($lb_valido)
	   {
	     $this->io_sql->commit();
		 $this->io_mensajes->message("Registro Incluido Exitosamente !!!");
	   }
	return $lb_valido;
  }

  function uf_load_dt_fuentefinanciamiento($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_dt_fuentefinanciamiento
	//		   Access: private
	//	    Arguments: ai_totrows  // Total de filas a imprimir
	//	  Description: Método que imprime en el grid las Fuentes de Financiamiento asociadas a una Estructura Presupuestaria.
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 24/12/2008.								Fecha Última Modificación : 24/12/2008.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $la_datfuefin = array();
	  $ls_codestpro1 = str_pad($as_codestpro1,25,0,0);
	  $ls_codestpro2 = str_pad($as_codestpro2,25,0,0);
	  $ls_codestpro3 = str_pad($as_codestpro3,25,0,0);
	  if ($_SESSION["la_empresa"]["estmodest"]==2)
	     {
		   $ls_codestpro4 = str_pad($as_codestpro4,25,0,0);
		   $ls_codestpro5 = str_pad($as_codestpro5,25,0,0);		 
		 }
	  else
	     {
		   $ls_codestpro4 = $ls_codestpro5 = str_pad('',25,'0',0);
		 }
	  $ls_sql = "SELECT spg_dt_fuentefinanciamiento.codfuefin, sigesp_fuentefinanciamiento.denfuefin
				   FROM spg_dt_fuentefinanciamiento, sigesp_fuentefinanciamiento
				  WHERE spg_dt_fuentefinanciamiento.codemp = '".$this->ls_codemp."'
				    AND spg_dt_fuentefinanciamiento.estcla = '".$as_estcla."'   
				    AND spg_dt_fuentefinanciamiento.codestpro1 = '".$ls_codestpro1."'
				    AND spg_dt_fuentefinanciamiento.codestpro2 = '".$ls_codestpro2."'
				    AND spg_dt_fuentefinanciamiento.codestpro3 = '".$ls_codestpro3."'
				    AND spg_dt_fuentefinanciamiento.codestpro4 = '".$ls_codestpro4."'
				    AND spg_dt_fuentefinanciamiento.codestpro5 = '".$ls_codestpro5."'
				    AND spg_dt_fuentefinanciamiento.codemp=sigesp_fuentefinanciamiento.codemp
				    AND spg_dt_fuentefinanciamiento.codfuefin=sigesp_fuentefinanciamiento.codfuefin
				  ORDER BY spg_dt_fuentefinanciamiento.codfuefin ASC;";
	  
	  $rs_data = $this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
	  if ($rs_data===false)
	     {
		   return false;
		 }
	  else
	     {
		   $la_datfuefin = $rs_data->GetRows();
		 }
	  return $la_datfuefin;
	}

  function uf_delete_dt_fuentefinanciamiento($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$aa_codfuefin,$aa_seguridad)
  {
    $lb_valido = true;
	$this->io_sql->begin_transaction();
	$ls_codestpro1 = str_pad($as_codestpro1,25,0,0);
	$ls_codestpro2 = str_pad($as_codestpro2,25,0,0);
	$ls_codestpro3 = str_pad($as_codestpro3,25,0,0);
	if ($_SESSION["la_empresa"]["estmodest"]==2)
	   {
	     $ls_codestpro4 = str_pad($as_codestpro4,25,0,0);
		 $ls_codestpro5 = str_pad($as_codestpro5,25,0,0);
	   }
	else
	   {
	     $ls_codestpro4 = $ls_codestpro5 = str_pad('',25,'0',0);
	   }
	$li_totrowfue = count($aa_codfuefin["codfuefin"]);
	for ($li_i=1;$li_i<=$li_totrowfue;$li_i++)
	    {
		  $ls_codfuefin = $aa_codfuefin["codfuefin"][$li_i];
		  $lb_valido    = $this->uf_load_relacion($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$as_estcla,$ls_codfuefin);
		  if ($lb_valido)
		     {
			   $ls_denfuefin = $aa_codfuefin["denfuefin"][$li_i];
			   $ls_sql = "DELETE FROM spg_dt_fuentefinanciamiento
						   WHERE codemp = '".$this->ls_codemp."'
						     AND codestpro1 = '".$ls_codestpro1."'
						     AND codestpro2 = '".$ls_codestpro2."'
						     AND codestpro3 = '".$ls_codestpro3."'
						     AND codestpro4 = '".$ls_codestpro4."'
						     AND codestpro5 = '".$ls_codestpro5."'
						     AND estcla = '".$as_estcla."'
						     AND codfuefin = '".$ls_codfuefin."'";
			   $rs_data = $this->io_sql->execute($ls_sql);//echo $ls_sql.'<br>';	
			   if ($rs_data===false)
				  {
				    $this->io_sql->rollback();
				    $this->io_mensajes->message("CLASE->sigesp_spg_c_codestpro_codfuefin.php->MÉTODO->uf_insert_codestpro_codfuefin. ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				    return false;
				  }		   
			   else
				  {
				    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				    $ls_evento="DELETE";
				    if ($_SESSION["la_empresa"]["estmodest"]==2)
					   {
					     $ls_descripcion ="Eliminó la Fuente de Financiamiento $ls_codfuefin - $ls_denfuefin, asociada a la Estructura Presupuestaria $as_codestpro1-$as_codestpro2-$as_codestpro3-$as_codestpro4-$as_codestpro5 y de tipo $as_estcla a la empresa ".$this->ls_codemp;
					   }
				    else
					   {
					     $ls_descripcion ="Eliminó la Fuente de Financiamiento $ls_codfuefin - $ls_denfuefin, asociada a la Estructura Presupuestaria $as_codestpro1-$as_codestpro2-$as_codestpro3 y de tipo $as_estcla a la empresa ".$this->ls_codemp;
					   }
				    $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												 $aa_seguridad["ventanas"],$ls_descripcion);
				    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				  }
		     }
		  else
		     {
			   $this->io_mensajes->message("Error en Eliminación, Existen Relaciones asociadas a este Registro !!!");
			 }
		}
    if ($lb_valido)
	   {
	    $this->io_sql->commit();
		// Titulos del Grid de Fuentes de Financiamiento.
	    $lo_title[1] = "C&oacute;digo";
	    $lo_title[2] = "Denominaci&oacute;n";
	    $lo_title[3] = "";

		$la_data = $this->uf_load_dt_fuentefinanciamiento($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$as_estcla);
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
				  $this->io_grid->make_gridScroll($li_totrow,$lo_title,$lo_object,650,"Detalle Fuentes Financiamiento","gridfuentes",100);
				}
		   }
 		else
		   {
			 $lo_object[1][1] = "<input type=text name=txtcodfuefin1  id=txtcodfuefin1  class=sin-borde style=text-align:center size=24  value=''  readonly>";
			 $lo_object[1][2] = "<input type=text name=txtdenfuefin1  id=txtdenfuefin1  class=sin-borde style=text-align:left   size=85  value=''  title='' readonly>";
			 $lo_object[1][3] = "<a href=javascript:uf_delete_dt('1');><img src=../../shared/imagebank/tools15/eliminar.gif title=Eliminar width=15 height=10 border=0></a>";
		     echo "    <tr>";
			 echo " 	  <td height='13' align='left'><a href='javascript:uf_catalogo_fuente_financiamiento();'><img src='../shared/imagebank/tools/nuevo.gif' title='Agregar Detalle Fuente Financiamiento' width='20' height='20' border='0'>Agregar Detalle Fuente Financiamiento</a></td>";
			 echo "    </tr>";
			 $this->io_grid->make_gridScroll(1,$lo_title,$lo_object,650,"Detalle Fuentes Financiamiento","gridfuentes",100);
		   }
		unset($io_codfue,$la_data);
	   }
	return $lb_valido;
  }
    
	function uf_load_relacion($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$as_codfuefin)
	{
	  $lb_valido = true;
	  $ls_sql = "SELECT codfuefin 
	               FROM spg_cuenta_fuentefinanciamiento
				  WHERE codemp = '".$this->ls_codemp."'
				    AND codestpro1 = '".$as_codestpro1."'
					AND codestpro2 = '".$as_codestpro2."'
					AND codestpro3 = '".$as_codestpro3."'
				    AND codestpro4 = '".$as_codestpro4."'
					AND codestpro5 = '".$as_codestpro5."'
					AND estcla = '".$as_estcla."'
					AND codfuefin = '".$as_codfuefin."'";
	  $rs_data = $this->io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   return false;
		 }
	  else
	     {
		   if ($row=$this->io_sql->fetch_row($rs_data))
		      {
			    $lb_valido = false;
			  }
		 }
	  return $lb_valido;
	}
}
?>