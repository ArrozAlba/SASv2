<?php 
class sigesp_apr_c_inventario
{
	//-----------------------------------------------------------------------------------------------------------------------------------
    function sigesp_apr_c_inventario()
    {
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_movimiento_inicial_siv_result_".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
	
		$this->ls_database_source=$_SESSION["ls_database"];
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];		
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("class_folder/class_validacion.php");
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf	 = new sigesp_c_reconvertir_monedabsf(); 
		$this->io_mensajes=new class_mensajes();
		$this->io_validacion      = new class_validacion();
		$io_conect	= new sigesp_include();
		$io_conexion_origen = $io_conect->uf_conectar();
		$io_conexion_destino = $io_conect->uf_conectar($this->ls_dabatase_target);
		$this->io_sql_origen = new class_sql($io_conexion_origen);
		$this->io_sql_destino = new class_sql($io_conexion_destino);
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_insert_movimientoinicial()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_movimientoinicial
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que selecciona la data de cxp_clasificador_rd y los inserta en cxp_clasificador_rd
		//	   Creado Por: 
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido       = true;
		$li_total_select = 0;
		$li_total_insert = 0;
		$ls_sql          = "SELECT codemp,codart,codalm,SUM(existencia) AS existencia, ".
						   "       (SELECT ultcosart FROM siv_articulo ".
                           "         WHERE siv_articuloalmacen.codemp=siv_articulo.codemp ".
						   "           AND siv_articuloalmacen.codart=siv_articulo.codart) AS ultcosart ".
                           "  FROM siv_articuloalmacen ".
						   " WHERE existencia > 0 ".
                           " GROUP BY codemp,codart,codalm ";
		$io_recordset    = $this->io_sql_origen->select($ls_sql);
		if($io_recordset===false)
		{ 
			$lb_valido=false;
			$ls_cadena="Error al Seleccionar los Artículos por Almacen.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{   
			$li_total_select = $this->io_sql_origen->num_rows($io_recordset);
			if($li_total_select>0)
			{
				$ls_comprobante = "000000000000001";
				$ld_fecha		= "";
				$this->uf_select_empresa(&$ld_fecha);
				$ls_usuario		= $_SESSION["la_logusr"];
				$ls_solicitante = "Apertura";
				$ls_sql="INSERT INTO siv_movimiento (nummov,fecmov,nomsol,codusu)  VALUES ('".$ls_comprobante."','".$ld_fecha."','".$ls_usuario."','".$ls_solicitante."') ";
				$li_row    = $this->io_sql_destino->execute($ls_sql);
				if($li_row===false)
				{ 
					$lb_valido=false;
					$ls_cadena="Error al Insertar el Movimiento Inicial.\r\n".$this->io_sql_destino->message."\r\n";
					$ls_cadena=$ls_cadena.$ls_sql."\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
				}
			}
			while($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ls_codemp=$this->io_validacion->uf_valida_texto($row["codemp"],0,4,"");
				$ls_nummov=$ls_comprobante;
				$ld_fecmov=$ld_fecha;
				$ls_codart=$this->io_validacion->uf_valida_texto($row["codart"],0,20,"");
				$ls_codalm=$this->io_validacion->uf_valida_texto($row["codalm"],0,10,"");
				$ls_opeinv="ENT";
				$ls_codprodoc="APR";
				$ls_numdoc=$ls_comprobante;
				$li_canart=$this->io_validacion->uf_valida_monto($row["existencia"],0);
				$li_cosart=$this->io_validacion->uf_valida_monto($row["ultcosart"],0,254,"");
				$ls_promov="APE";
				$li_numdocori=$ls_comprobante;
				$li_candesart=$this->io_validacion->uf_valida_monto($row["existencia"],0);
				$ld_fecdesart=$ld_fecha;
				$li_cosartaux=$this->io_validacion->uf_valida_monto($row["ultcosart"],0);
				$li_cosart=$this->io_rcbsf->uf_convertir_monedabsf($li_cosart,2,1,1000,1);
				if($li_canart>0)
				{
					$ls_sql="INSERT INTO siv_dt_movimiento (codemp, nummov, fecmov, codart, codalm, opeinv, codprodoc, numdoc, canart, cosart, ".
							"promov, numdocori, candesart, fecdesart, cosartaux) VALUES ('".$ls_codemp."','".$ls_nummov."','".$ld_fecmov."',".
							"'".$ls_codart."','".$ls_codalm."','".$ls_opeinv."','".$ls_codprodoc."','".$ls_numdoc."',".$li_canart.",".$li_cosart.",".
							"'".$ls_promov."',".$li_numdocori.",".$li_candesart.",'".$ld_fecdesart."',".$li_cosartaux.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Detalles del movimiento inicial.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					$ls_sql="INSERT INTO siv_articuloalmacen (codemp, codart, codalm, existencia) VALUES ".
							"('".$ls_codemp."','".$ls_codart."','".$ls_codalm."',".$li_canart.")";
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar los Artículos por almacén.\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}

				}
			}
			$ls_cadena="//*****************************************************************//\r\n";
			$ls_cadena=$ls_cadena."   El Movimiento Inicial de Inventario se Creo con Exito \r\n";
			$ls_cadena=$ls_cadena."//*****************************************************************//\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}		
		return $lb_valido;
	}// end function uf_insert_movimientoinicial
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_empresa(&$ls_periodo)
	{	//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function:  uf_select_comprobante()
		//	   Access:  public
		//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
		//	  Returns:	booleano lb_existe
		//Description:  Método que verifica si existe o no el comprobante
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql=" SELECT periodo ".
			    " FROM  sigesp_empresa ".
				" WHERE codemp='0001' ";
		$lr_result=$this->io_sql_destino->select($ls_sql);
		if($lr_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_apr_c_inventario 
			                     MÉTODO->uf_select_comprobante 
								 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql_destino->message);
			return false;
		}
		else  
		{ 
			if($row=$this->io_sql_destino->fetch_row($lr_result)) 
			{ 
				$ls_periodo=$row["periodo"];
				$lb_existe=true;
			}  
		}
		return $lb_existe;
	} // end function uf_select_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>