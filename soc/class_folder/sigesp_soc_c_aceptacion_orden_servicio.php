<?php
class sigesp_soc_c_aceptacion_orden_servicio
{
  function sigesp_soc_c_aceptacion_orden_servicio($as_path)
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: sigesp_soc_c_aceptacion_orden_servicio
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 03/05/2007 								Fecha Última Modificación : 03/05/2007 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        require_once($as_path."shared/class_folder/sigesp_include.php");
		require_once($as_path."shared/class_folder/class_sql.php");
		require_once($as_path."shared/class_folder/class_funciones.php");
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$io_include			= new sigesp_include();
		$io_conexion		= $io_include->uf_conectar();
		$this->io_sql       = new class_sql($io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();	
		$this->io_seguridad = new sigesp_c_seguridad();
		$this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
  }

	function uf_load_ordenes_servicios($as_tipope)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ordenes_servicios
		//         Access: public
		//      Argumento: 
		//      $as_tipope //Tipo de Operación A=Aceptación, R=Reverso.
		//	      Returns: Retorna un resulset
		//    Description: Funcion que busca las ordenes de servicio que estan contabilizadas. 
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/03/2007							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido = true;
		if ($as_tipope=='A')
		   {
			 $ls_sql="SELECT soc_ordencompra.numordcom,                   ".
					 "       soc_ordencompra.obscom,                      ".
					 "       soc_ordencompra.fecordcom,                   ".		
					 "       soc_ordencompra.cod_pro,                     ".		        
					 "       rpc_proveedor.nompro                         ".
					 "  FROM soc_ordencompra,rpc_proveedor                ".
					 " WHERE soc_ordencompra.codemp='".$this->ls_codemp."'".
					 "   AND soc_ordencompra.estcondat='S'                ".
					 "   AND soc_ordencompra.estcom=2					  ".
					 "   AND soc_ordencompra.estapro=1					  ".			
					 "   AND soc_ordencompra.codemp=rpc_proveedor.codemp  ".
					 "   AND soc_ordencompra.cod_pro=rpc_proveedor.cod_pro";
		   }
		elseif($as_tipope=='R')
		   {
		     $ls_sql="SELECT soc_ordencompra.numordcom,                   										   ".
					 "       soc_ordencompra.obscom,                      										   ".
					 "       soc_ordencompra.fecordcom,                   										   ".		
					 "       soc_ordencompra.cod_pro,                     										   ".		        
					 "       rpc_proveedor.nompro                         										   ".
				     "  FROM soc_ordencompra,rpc_proveedor                										   ".
					 " WHERE soc_ordencompra.codemp='".$this->ls_codemp."'										   ".
					 "   AND soc_ordencompra.estcondat='S'                										   ".
					 "   AND soc_ordencompra.estcom=7                     										   ".
					 "   AND soc_ordencompra.estapro=1					  										   ".			
					 "   AND soc_ordencompra.codemp=rpc_proveedor.codemp  										   ".
					 "   AND soc_ordencompra.cod_pro=rpc_proveedor.cod_pro										   ".
					 "   AND soc_ordencompra.numordcom NOT IN(SELECT CASE WHEN numdoccom IS NULL THEN '-----' ELSE numdoccom END ".
					 "                                          FROM cxp_rd_spg									   ".
					 "										   WHERE cxp_rd_spg.procede_doc='SOCCOS'               ".
					 "											AND soc_ordencompra.codemp=cxp_rd_spg.codemp       ".
					 "                                          AND soc_ordencompra.cod_pro=cxp_rd_spg.cod_pro     ".
					 "											AND soc_ordencompra.numordcom=cxp_rd_spg.numdoccom)";
		   }
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_soc_c_aceptacion_orden_servicio.MÉTODO->uf_soc_load_ordenes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		return $rs_data;
	} // end  function uf_load_ordenes_servicios

	function uf_aceptar_orden_servicio($as_tipope,$ai_totrows,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_aceptar_orden_servicio
		//         Access: public 
		//      Argumento: $as_totrows   // Total de Filas del Grid.
		//                 $as_tipope    // Tipo de Operacion A=Aceptacion, R=Reverso.
		//  			   $aa_seguridad // arreglo de seguridad
		//    Description: Funcion que actualiza el estatus de la orden de servicio.
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/06/2007							Fecha Última Modificación : 06/06/2007.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 if ($as_tipope=='A')
		    {
			  $ls_estordser = 7;
			}
		 elseif($as_tipope=='R')
		    {
			  $ls_estordser = 2;
			}
		 $this->io_sql->begin_transaction();
		 for ($i=1;$i<=$ai_totrows;$i++)
		     {
			   if (array_key_exists("chk".$i,$_POST))
			      {
		            $ls_codpro    = $_POST["hidcodpro".$i];
					$ls_numordcom = $_POST["txtnumord".$i];
					$ls_sql       = "UPDATE soc_ordencompra SET estcom='".$ls_estordser."' ".
					                " WHERE codemp='".$this->ls_codemp."' 				   ".
									"   AND numordcom='".$ls_numordcom ."'                 ".
									"   AND estcondat='S'                                  ".
									"   AND cod_pro='".$ls_codpro."'                       ";
					$rs_data = $this->io_sql->execute($ls_sql);
		            if ($rs_data===false)
		               {
			             $this->io_msg->message("CLASE->sigesp_soc_c_aceptacion_orden_servicio.MÉTODO->uf_aceptar_orden_servicio->ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			             $lb_valido=false;
		                 break; 
					   }
					else
					   {
						 ///////////////////////////////         SEGURIDAD               /////////////////////////////		
						 $ls_evento="UPDATE";
						 $ls_descripcion ="Actualizó estatus de aprobacion en ".$ls_estordser." la orden de servicio numero ".$ls_numordcom." Asociada a la Empresa ".$this->ls_codemp;
						 $ls_variable= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														    $aa_seguridad["ventanas"],$ls_descripcion); 
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					   }
				  }
			 }
		  if ($lb_valido)
		     {	
			   $this->io_sql->commit();
			   $this->io_mensajes->message("Operación fue realizada con éxito !!!");
	  	       $this->io_sql->close();
			 }
		  else
		     {
		  	   $this->io_sql->rollback();
			   $this->io_mensajes->message("Ocurrio un Error en la operación !!!"); 
   	  	       $this->io_sql->close();
			 }
	}//end  function uf_aceptar_orden_servicio
}
?>
