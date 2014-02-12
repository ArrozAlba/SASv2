
<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cobro
 // Autor:       - Ing. Zulheymar Rodroguez
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla sfc_cobrocartaorden, sfc_cobrocartaordenfactura y sfc_instpagocob
 // Fecha:       - 03/08/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_cobranzacarta
{
	 var $io_funcion;
	 var $io_msgc;
	 var $io_sql;
	 var $datoemp;
	 var $io_msg;
	 
	function sigesp_sfc_c_cobranzacarta()
	{
		require_once ("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("sigesp_sob_c_funciones_sob.php"); /* se toma la funcion de convertir cadena a caracteres*/
		$this->funsob      = new sigesp_sob_c_funciones_sob();
		$this->seguridad   = new sigesp_c_seguridad();
		$this->io_funcion  = new class_funciones();
		$io_include        = new sigesp_include();
		$io_connect        = $io_include->uf_conectar();
		$this->io_sql      = new class_sql($io_connect);
		$this->datoemp     = $_SESSION["la_empresa"];
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_msg      = new class_mensajes();
		$this->io_datastore= new class_datastore();
	}

	function uf_select_cobrocliente($ls_numcob,$ls_codban,$ls_codtiend)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_cobro
		// Parameters:  - $ls_numcob( Codigo del Cobro).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT numcob
		            FROM   sfc_cobrocartaorden
		            WHERE  codemp='".$ls_codemp."' AND numcob='".$ls_numcob."'  
					AND    codban='".$ls_codban."' AND codtiend='".$ls_codtiend."'";		
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			//$this->io_msgc="Error en uf_select_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
		}
	
		function uf_select_cobro($ls_numcob)
		{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_cobro
		// Parameters:  - $ls_numcob( Codigo del Cobro).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_cadena="SELECT  * 
		            FROM    sfc_cobrocartaorden
		            WHERE   codemp='".$ls_codemp."' AND numcob='".$ls_numcob."'  
					AND     codban='".$ls_codban."' AND codtiend='".$ls_codtie."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			//$this->io_msgc="Error en uf_select_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}
	
	function uf_guardar_cobro($ls_codtie,$ls_codcli,$ls_numcob,$ls_descob,$ls_feccob,$ld_moncob,$ls_codusu,
	                         $ls_estcob,$ls_especial,$ls_numcarta,$la_codcaj,$ls_codbanco,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_cobro.
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//		        - $ls_numcob(Nomero del Cobro).
		//		        - $ls_descob. (Descripcion del Cobro).
		//		        - $ls_feccob.(Fecha del Cobro).
		//		        - $ls_moncob.(Monto del Cobro).
		//		        - $ls_codusu.(Codigo de Usuario de la Caja):
		//		        - $ls_estcob. (Estatus del Cobro A->Anulado, E->Emitido,C->Cancelado, P->Procesado).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_cobrocliente($ls_numcob,$ls_codcli,$ls_numcarta);
		$ld_moncob=$this->funsob->uf_convertir_cadenanumero($ld_moncob); 
		$ls_feccob=$this->io_funcion->uf_convertirdatetobd($ls_feccob);
		$ls_descob="Insercion de Carta Orden";
		if(!$lb_existe)
		{
            $ls_cadena= "INSERT INTO sfc_cobrocartaorden (codemp,numcob,codban,descob,feccob,
			                        moncob,estcob,codusu,cod_caja,codtiend) VALUES
						 ('".$ls_codemp."','".$ls_numcob."','".$ls_codbanco."','".$ls_descob."',
						 '".$ls_feccob."',".$ld_moncob.",'".$ls_estcob."','".$ls_codusu."',
						'".$la_codcaj."','".$ls_codtie."')";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_cobrocartaorden
			             SET    descob='".$ls_descob."',         feccob='".$ls_feccob."', 
						        moncob=".$ld_moncob.",           moncob='".$ld_moncob."',
						        codusu='".$ls_codusu."',         estcob='".$ls_estcob."',
							    codciecaj='".$ls_codciecaj."',   cod_caja='".$la_codcaj."' 
					      WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."'  
					      AND   codban='".$ls_codban."' AND codtiend='".$ls_codtiend."'";
			$ls_evento="UPDATE";
		}
     	//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_cobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$this->io_sql->commit();
				$lb_valido=true;                
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_descripcion ="Inserto el Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_msgc="Registro Incluido!!!";

				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_descripcion ="Actualizo el Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_msgc="Registro Actualizado!!!";
				}
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
				}
				else
				{
					$lb_valido=false;
				}
			}

		}
		if($ls_evento=="INSERT")
		{
					$ls_cadena="SELECT * 
								FROM   sfc_cobrocartaorden 
								WHERE  sfc_cobrocartaorden.numcob<'".$ls_numcob."' 
								AND    (sfc_cobrocartaorden.estcob<>'A' 
								AND    sfc_cobrocartaorden.estcob<>'P')";
					$rs_datauni2=$this->io_sql->select($ls_cadena);
					if($row2=$this->io_sql->fetch_row($rs_datauni2))
					{
							$la_prod=$this->io_sql->obtener_datos($rs_datauni2);
							$this->io_datastore->data=$la_prod;
							$totrow2=$this->io_datastore->getRowCount("numcob");
							for($z2=1;$z2<=$totrow2;$z2++)
							{
									$ls_cod=$this->io_datastore->getValue("numcob",$z2);
									$lb_existe2=$this->uf_actualizar_cobrostatus($ls_cod,'C',$ls_codbanco,$ls_codtie,$aa_seguridad);
							}
					}
		}
		return $lb_valido;
	}
	
	function uf_delete_cobro($ls_numcob,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_cobro.
		// Parameters:  - $ls_numcob (nomero del Cobro).
		// Descripcion: - Funcion que elimina un Cobro.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_cobro($ls_numcob);
		if($lb_existe)
		{
		    	$ls_cadena= "DELETE 
				             FROM  sfc_cobrocartaorden 
				             WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."'  
					         AND   codban='".$ls_codban."' AND codtiend='".$ls_codtiend."'";
				$this->io_msgc="Registro Eliminado!!!";
				//$this->io_sql->begin_transaction();
				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_cobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino el Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->is_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}
				}
		}
		else
		{
			$lb_valido=1;
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}
	
	function uf_select_facturacobro($ls_numcob,$ls_codban,$ls_numcartaorden,$ls_codtiend)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_facturacobro.
		// Parameters:  - $ls_numcob( Nomero del Cobro).
		//		      -$ls_numfactura (Nomero de la Factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * 
		            FROM   sfc_dt_cobrocartaorden
		            WHERE  codemp='".$ls_codemp."'  
					AND    numcob='".$ls_numcob."' 
					AND    codban='".$ls_codban."'  
					AND    numcartaorden='".$ls_numcartaorden."' 
					AND    codtiend='".$ls_codtiend."'  
					ORDER BY numcob,codban,numcartaorden,codtiend ASC;";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}
	
	function uf_select_detcobro($ls_numcob)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detcobro.
		// Parameters:  - $ls_numcob( Nomero del Cobro).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * 
		            FROM   sfc_dt_cobrocartaorden
                    WHERE  codemp='".$ls_codemp."'  
					AND    numcob='".$ls_numcob."' 
					AND    codban='".$ls_codban."'  
					AND    numcartaorden='".$ls_numcartaorden."' 
					AND    codtiend='".$ls_codtiend."'  
					ORDER BY numcob,codban,numcartaorden,codtiend ASC;";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       	if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}
	
	function uf_select_detfactura($ls_numfac)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_select_detfactura.
			// Parameters:  - $ls_numfac( Nomero de la factura).
			// Descripcion: - Funcion que busca un registro en la bd.
			//////////////////////////////////////////////////////////////////////////////////////////
			$ls_codemp=$this->datoemp["codemp"];
			$ls_cadena="SELECT * 
						FROM   sfc_detfactura
						WHERE  codemp='".$ls_codemp."' 
						AND    numfac='".$ls_numfac."';";
			$rs_datauni=$this->io_sql->select($ls_cadena);
			if($rs_datauni==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_select_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
	
	
				if($row=$this->io_sql->fetch_row($rs_datauni))
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro no encontrado";
				}
			}
			return $lb_valido;
	}
	
	function uf_select_factura($ls_numfac)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_select_factura
			// Parameters:  - $ls_numfac( Codigo de la factura).
			// Descripcion: - Funcion que busca un registro en la bd.
			//////////////////////////////////////////////////////////////////////////////////////////
			$ls_codemp=$this->datoemp["codemp"];
			$ls_cadena="SELECT * 
						FROM   sfc_factura
						WHERE  codemp='".$ls_codemp."' 
						AND    numfac='".$ls_numfac."';";	
			$rs_datauni=$this->io_sql->select($ls_cadena);
			if($rs_datauni==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_datauni))
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro no encontrado";
				}
			}
			return $lb_valido;
	}
	
	function uf_actualizar_factura($ls_numfac,$ls_estfaccon,$aa_seguridad)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_actualizar_factura
			// Parameters:  - $ls_numfac( Codigo de la factura).
			//		      -$ls_estfaccon
			// Descripcion: - Funcion que busca un registro en la tabla sfc_factura y actualiza su estatus.
			//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);
		$ls_cadena= "UPDATE sfc_factura 
					 SET    estfaccon = '".$ls_estfaccon."' 
					 WHERE  codemp    = '".$ls_codemp."' 
					 AND    numfac    = '".$ls_numfac."';";
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_factura".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el estatus de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if ($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";
	
				}//($lb_existe)
			}//($li_numrows>0)
	
		}// ($li_numrows==false)&&($this->io_sql->message!="")
	return $lb_valido;
	}
	
	function uf_select_nota($ls_numnot)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_select_factura
			// Parameters:  - $ls_numfac( Codigo de la factura).
			// Descripcion: - Funcion que busca un registro en la bd.
			//////////////////////////////////////////////////////////////////////////////////////////
			$ls_codemp=$this->datoemp["codemp"];
			$ls_codtie=$_SESSION["ls_codtienda"];
			$ls_cadena="SELECT * 
						FROM   sfc_nota
						WHERE  codemp  ='".$ls_codemp."' 
						AND    numnot  ='".$ls_numnot."'
						AND    codtiend='".$ls_codtie."'";
			$rs_datauni=$this->io_sql->select($ls_cadena);
			if($rs_datauni==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_datauni))
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="La Nota de Debito no encontrada";
				}
			}
			return $lb_valido;
	}
	
	function uf_actualizar_nota($ls_codtiend,$ls_numnot,$ls_estnot,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_actualizar_nota
	// Parameters:  - $ls_numfac( Codigo de la factura).
	//		      -$ls_estfaccon
	// Descripcion: - Funcion que busca un registro en la tabla sfc_factura y actualiza su estatus.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_nota($ls_numnot);
	$ls_cadena= "UPDATE sfc_nota 
	             SET    estnota ='".$ls_estnot."' 
	             WHERE  codemp  ='".$ls_codemp."' 
				 AND    numnot  ='".$ls_numnot."' 
 				 AND    codtiend='".$ls_codtiend."'";
	//$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_actualizar_nota".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_msgc;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizo la Nota ".$ls_numnot." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_sql->commit();
		}
		else
		{
			$this->io_sql->rollback();
			if ($lb_existe)
			{
				$lb_valido=0;
				$this->io_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro No Incluido!!!";

			}//($lb_existe)
		}//($li_numrows>0)

	}// ($li_numrows==false)&&($this->io_sql->message!="")
	return $lb_valido;
	}
	
    	function uf_guardar_detcobro($ls_codtie,$ls_codcli,$ls_numcob,$as_numcartaorden,$ls_tipcancel,
		                             $ls_moncancel,$ls_monto,$ls_numfacasoc,$estcarta,$as_codbanco,$aa_seguridad)
		{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_detcobro
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//		      - $ls_numfac (Nomero de la factura).
		//		      - $ls_numcob( nomero de cobro).
		//		      - $ls_tipcancel (tipo de cancelacion).
		//		      - $ls_moncancel(monto a cancelar).
		//		      -$ls_monto (monto por cancelar).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_facturacobro($ls_numcob,$as_codbanco,$as_numcartaorden,$ls_codtie);
		$ls_moncancel=$this->funsob->uf_convertir_cadenanumero($ls_moncancel); /* convierte cadena en numero */
		$ls_montoxcancel=$this->funsob->uf_convertir_cadenanumero($ls_monto);
		if ($ls_tipcancel=='T')
		{
				$lb_existefactura=$this->uf_select_factura($ls_numfacasoc);
				if ($lb_existefactura)
					{
						$lb_existe_fac=$this->uf_actualizar_factura($ls_numfacasoc,'C',$aa_seguridad);
					}
				$lb_existe_not=$this->uf_actualizar_nota($ls_codtie,$as_numcartaorden,'C',$aa_seguridad);
		}
		else
		{
				$lb_existefactura=$this->uf_select_factura($ls_numfacasoc);
				if ($lb_existefactura)
				{
						$lb_existefac=$this->uf_actualizar_factura($ls_numfacasoc,'P',$aa_seguridad);
						$lb_existe_not=$this->uf_actualizar_nota($as_numcartaorden,'P',$aa_seguridad);
				}
		  }
		  if ($lb_existe)
		  {
				$ls_cadena= "UPDATE sfc_dt_cobrocartaorden 
							 SET  tipcancel='".$ls_tipcancel."', moncancel=".$ls_moncancel.", 
							 montoxcancel=".$ls_montoxcancel.",estcarta='".$estcarta."',
							 codtiend='".$ls_codtie."' 
							 WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND numcartaorden='".$as_numcartaorden."' AND codcli='".$ls_codcli."';";		
				$ls_evento="UPDATE";
		  }
		  else
		  {
            $ls_cadena= "INSERT INTO sfc_dt_cobrocartaorden (codemp,numcob,codban,numcartaorden,
			                                     codcli,codtiend,moncancel) VALUES 
						('".$ls_codemp."','".$ls_numcob."','".$as_codbanco."','".$as_numcartaorden."',
 						 '".$ls_codcli."','".$ls_codtie."',".$ls_moncancel.")";
			$ls_evento="INSERT";
		    }
        //$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detcobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
			    $this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if ($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";
				}
				else
				{
					$lb_valido=false;
				//	$this->io_msgc="Registro No Incluido!!!";

				} //$lb_existe
			} //$li_numrows>0
		} //($li_numrows==false)&&($this->io_sql->message!="")
		return $lb_valido;
	}
	function uf_delete_detcobro($ls_numcob,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_detcobro
	// Parameters:  - $ls_numcob (nomero del Cobro).
	// Descripcion: - Funcion que elimina un cobro.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_detcobro($ls_numcob);

	if($lb_existe)
	{
	    	$ls_cadena= " DELETE 
			              FROM   sfc_dt_cobrocartaorden
						  WHERE  codemp       = '".$ls_codemp."'  
						  AND    numcob       = '".$ls_numcob."' 
						  AND    codban       = '".$ls_codban."'  
						  AND    numcartaorden= '".$ls_numcartaorden."' 
						  AND    codtiend     = '".$ls_codtiend."'";
			//$this->io_sql->begin_transaction();
			$li_numrows=$this->io_sql->execute($ls_cadena);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_msgc="Error en metodo uf_delete_detcobro ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->io_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino los detalles de Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->is_msgc="Registro No Eliminado!!!";
					$this->io_sql->rollback();
				}
			}
	}
	else
	{
		$lb_valido=1;
		$this->io_msg->message("El Registro no Existe");
	}
	return $lb_valido;
	}
	function uf_actualizar_cobrostatus($ls_numcob,$ls_estcob,$ls_codban,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_actualizar_cobrostatus.
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//		      - $ls_estcob(Estado del Cobro).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_cobro($ls_numcob);
		
		$ls_cadena= "UPDATE sfc_cobrocartaorden 
					 SET    estcob='".$ls_estcob."' 
					 WHERE  codemp='".$ls_codemp."'  AND  numcob='".$ls_numcob."'  
					 AND    codban='".$ls_codban."'  AND  codtiend='".$ls_codtie."'";
		$ls_evento="UPDATE";
      	//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_cobrostatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
			        $this->io_sql->commit();
				    $lb_valido=true;
				    $ls_descripcion ="Actualizo de Cobro ".$ls_numcob." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$this->io_msgc="Registro Actualizado!!!";

			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";

				}
				else
				{
					$lb_valido=false;
					//$this->io_msgc="Registro No Incluido!!!";

				}
			}
		}
	return $lb_valido;
	}
	function uf_actualizar_dfacturatatus($ls_numfac,$ls_estcob,$aa_seguridad)
	{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_actualizar_dfacturatatus.
	// Parameters:- $ls_numfac(Nomero de la factura)
	//		    -$ls_estcob (Estado del detalle del cobro factura
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];

		$ls_cadena= "UPDATE sfc_dt_cobrocartaorden                                ".
		            "SET    tipcancel='".$ls_estcob."'                            ".
					"WHERE  codemp='".$ls_codemp."'  AND  numfac='".$ls_numfac."' ";
		$this->io_msgc="Registro Actualizado!!!";
		$ls_evento="UPDATE";
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_cobrostatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_descripcion ="Actualizo el Cobro de la factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";

				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";

				}
			}

		}
	return $lb_valido;
}
	function uf_guardar_detalles_instpagocob($ls_codtie,$ls_codcli,$ls_numcob,$ls_numinst,$ls_fecins,
	                                         $ls_obsins,$ls_codban,$ls_ctaban,$ls_monto,$ls_codforpag,$ls_numcarta)             
    { 
	
	
	    /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */    
		/* Access:			public                                                             */ 
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */ 
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */    
		/*  Fecha:          25/03/2006                                                         */        
		/*	Autor:          GERARDO CORDERO		                                               */     
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_instpagocarta($ls_numcob,$ls_numinst,$ls_codcli,$ls_numcarta);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ls_monto); /* convierte cadena en numero */
		
		$ls_fecins=$this->io_funcion->uf_convertirdatetobd($ls_fecins);	
		if ($lb_existe)
		{
		$ls_sql= " UPDATE sfc_instpagocobcartaorden 
		           set    numinst='".$ls_numinst."',
				          fecins='".$ls_fecins."',
				          codban='".$ls_codban."',
				          monto='".$ld_monto."',
						  codtiend='".$ls_codtie."' 
				   WHERE  codemp='".$ls_codemp."' 
				   AND    numcob='".$ls_numcob."'";
		$ls_evento="UPDATE";
		}
		else
		{
		$ls_sql= " INSERT INTO sfc_instpagocobcartaorden (codemp,numcob,numinst,fecins,obsins,codban,monto,codforpag,codtiend,ctaban) ".
			     " VALUES ('".$ls_codemp."','".$ls_numcob."','".$ls_numinst."','".$ls_fecins."','".$ls_obsins."',
				           '".$ls_codban."',".$ld_monto.",'".$ls_codforpag."','".$ls_codtie."','".$ls_ctaban."')";
				 $ls_evento="INSERT";
				 }		
		//$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		
		
		if($li_row===false)
		{			
		$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detcobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
	
		}
		else
		{
			if($li_row>0)
			{
			      /************    SEGURIDAD    **************		 
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Retencion ".$as_codded.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				  $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion); 
				  /**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{
				
				$this->io_sql->rollback();
			}
		
		}		
		return $lb_valido;
	}
	function uf_select_instpagocarta($ls_numcob,$ls_numinst,$ls_codcli,$ls_numcarta)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_select_facturacobro.
			// Parameters:  - $ls_numcob( Número del Cobro).		
			//		      -$ls_numfactura (Número de la Factura).
			// Descripcion: - Funcion que busca un registro en la bd.
			//////////////////////////////////////////////////////////////////////////////////////////		
			$ls_codemp=$this->datoemp["codemp"];
			$ls_cadena="SELECT * FROM sfc_instpagocob
						WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND numinst='".$ls_numinst."'  AND codcli='".$ls_codcli."' ORDER BY numinst ASC;";
			$rs_datauni=$this->io_sql->select($ls_cadena);
			if($rs_datauni==false)
			{
				$lb_valido=false;
				$this->io_msgc="Error en uf_select_detINST ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_datauni))
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
					}
			}
			return $lb_valido;
	}
	function uf_update_actualizaestnot($ls_numnot,$ls_estnot)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_guardar_nota
			// Parameters:  - $ls_codcli( Codigo del cliente).
			//			    - $ls_numnot( numero de nota).
			//			    - $ls_tipnot( tipo de nota crédito o débito).
			//				- $ls_dennot( denominación o descripcion de nota crédito).
			//              - $ls_fecnot( Fecha emision nota).
			// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
			//////////////////////////////////////////////////////////////////////////////////////////
			$ls_codemp=$this->datoemp["codemp"];
			$ls_codtie=$_SESSION["ls_codtienda"];

			$ls_cadena= "UPDATE sfc_nota ".
						"SET estnota='".$ls_estnot."' ".
						"WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."';";
			//$this->io_sql->begin_transaction();
			$li_numrows=$this->io_sql->execute($ls_cadena);
			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->is_msgc="Error en metodo uf_update_actualizarestnot".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				$this->io_sql->rollback();
				print $this->io_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					$this->io_sql->rollback();
					if($lb_existe)
					{
						$lb_valido=0;
						$this->io_msgc="No actualizo el registro";
					}
					else
					{
						$lb_valido=false;
						$this->io_msgc="Registro No Incluido!!!";
	
					}
				}
			}
	return $lb_valido;
	}
}/*FIN DE LA CLASE */
?>
