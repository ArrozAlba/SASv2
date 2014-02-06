<?php
class sigesp_scv_c_tarifas
{
var $ls_sql;
var $is_msg_error;
	
	function sigesp_scv_c_tarifas()
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones_db.php"); 		
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		$this->io_sql=      new class_sql($con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->io_funciondb= new class_funciones_db($con);
	}

	function uf_insert_tarifa($as_codemp,&$as_codtar,$as_dentar,$as_codpai,$as_codreg,$ai_monbol,$ai_mondol,$ai_monpas,$ai_monhos,
							  $ai_monali,$ai_monmov,$ai_exterior,$as_codcat,$as_codnom,$aa_seguridad) 
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	           Metodo:  uf_insert_tarifa
		//	           Access:  public
		//	        Arguments:  $as_codemp    // código de empresa.             $ai_mondol    // monto en dolares
		//                      $as_codtar    // codigo de tarifa				$ai_monpas    // monto de pasajes
		//                      $as_dentar    // denominacion de tarifa			$ai_monhos    // monto de hospedaje
		//                      $as_codpai    // codigo de pais					$ai_monmov    // monto de movilizacion
		//                      $as_codreg    // codigo de region				$ai_exterior  // viaticos al exterior 1->si 0->no
		//                      $as_codcat    // codigo de categoria 			$as_codnom    //  codigo de nomina
		//                      $ai_monbol    // monto en bolivares				$aa_seguridad //  Arreglo de seguridad
		//	          Returns:  $lb_valido.
		//	      Description:  Función que se encarga de insertar una tarifa en la tabla scv_tarifas
		//      Elaborado Por:  Ing. Luis Anibal Lang
		//  Fecha de Creación:  19/09/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_codtaraux = $as_codtar;
		$as_codtar=$this->io_funciondb->uf_generar_codigo(true,$as_codemp,'scv_tarifas','codtar');
		$this->io_sql->begin_transaction();
		$ls_sql=" INSERT INTO scv_tarifas (codemp,codtar,dentar,codpai,codreg,monbol,mondol,monpas,monhos,monali,monmov,nacext,".
				"                          codcat,codnom) ".
		  		"      VALUES ('".$as_codemp."','".$as_codtar."','".$as_dentar."','".$as_codpai."','".$as_codreg."',".
				"              '".$ai_monbol."','".$ai_mondol."','".$ai_monpas."','".$ai_monhos."','".$ai_monali."',".
				"              '".$ai_monmov."','".$ai_exterior."','".$as_codcat."','".$as_codnom."')";
			
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)		     
		{
			$this->io_sql->rollback();			
			if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			{
				$lb_valido=$this->uf_insert_tarifa($as_codemp,$as_codtar,$as_dentar,$as_codpai,$as_codreg,$ai_monbol,$ai_mondol,
				                                   $ai_monpas,$ai_monhos,$ai_monali,$ai_monmov,$ai_exterior,$as_codcat,$as_codnom,
												   $aa_seguridad);
			}
			else
			{
				$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_insert_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la tarifa ".$as_codtar." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
			
			if($lb_valido)
			{
				if($ls_codtaraux!=$as_codtar)
				{
					$this->io_msg->message("Se Asigno el Código de Tarifa: ".$as_codtar);
				}
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}
	
	function uf_update_tarifa($as_codemp,$as_codtar,$as_dentar,$as_codpai,$as_codreg,$ai_monbol,$ai_mondol,$ai_monpas,
							  $ai_monhos,$ai_monali,$ai_monmov,$ai_exterior,$as_codcat,$as_codnom,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_update_tarifa
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa.             $ai_mondol    // monto en dolares
		//                     $as_codtar    // codigo de tarifa				$ai_monpas    // monto de pasajes
		//                     $as_dentar    // denominacion de tarifa			$ai_monhos    // monto de hospedaje
		//                     $as_codpai    // codigo de pais					$ai_monmov    // monto de movilizacion
		//                     $as_codreg    // codigo de region				$ai_exterior  // viaticos al exterior 1->si 0->no
		//                     $as_codcat    // codigo de categoria 			$as_codnom    //  codigo de nomina
		//                     $ai_monbol    // monto en bolivares				$aa_seguridad //  Arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de actualizar los datos de una tarifa de viaticos en la tabla scv_tarifas
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  20/09/2006       Fecha Última Actualización:27/06/2006.	 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql="UPDATE scv_tarifas ".
				"   SET dentar='".$as_dentar."',codpai='".$as_codpai."',codreg='".$as_codreg."',monbol='".$ai_monbol."',".
				"       monpas='".$ai_monpas."',monhos='".$ai_monhos."',monali='".$ai_monali."',monmov='".$ai_monmov."',".
				" 		nacext='".$ai_exterior."',codcat='".$as_codcat."',codnom='".$as_codnom."',mondol='".$ai_mondol."'".
				" WHERE codemp='" .$as_codemp. "'".
				"   AND codtar='".$as_codtar."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_update_tarifa; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la tarifa ".$as_codtar." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
			
			if($lb_valido)
			{
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
			}
		}  		      
		return $lb_valido;
	} 
			
	function uf_delete_tarifa($as_codemp,$as_codtar,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_delete_tarifa
		//	          Access:  public
		// 	        Arguments   
		//        $as_codemp:  Código de la Empresa.
		//        $as_coddep:  Código de la Dependencia.
		//     $aa_seguridad:  Arreglo cargado con la información acerca de la ventana,usuario,etc.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de eliminar una Dependencia en la tabla scv_tarifas.
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  27/06/2006       Fecha Última Actualización:27/06/2006.	 
		//////////////////////////////////////////////////////////////////////////////  
		$lb_valido= false;
		$lb_relacion= $this->uf_check_relaciones($as_codemp,$as_codtar);
		if (!$lb_relacion)
		{
			$ls_sql= " DELETE FROM scv_tarifas".
					 "  WHERE codemp='".$as_codemp."'".
					 "    AND codtar='".$as_codtar."'";	    
			$this->io_sql->begin_transaction();
			$rs_data = $this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_delete_tarifas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó la tarifa ".$as_codtar." Asociada a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////// 		     
				$this->io_sql->commit();
			}
		}	  		 
		return $lb_valido;
	}
	
	function uf_select_tarifa($as_codemp,$as_codtar) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_select_tarifa
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//                     $as_codtar    // codigo de tarifa
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe una tarifa de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  20/09/2006       Fecha Última Actualización:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido= false;
		$ls_sql= " SELECT * FROM scv_tarifas".
				 "  WHERE codemp='".$as_codemp."'".
				 "    AND codtar='".$as_codtar."'";
		$rs_data= $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_select_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_numrows=$this->io_sql->num_rows($rs_data);
			if($li_numrows>0)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}
	
	function uf_load_tarifa($as_codemp,$as_codtar) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_tarifa
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de empresa
		//                     $as_codtar    // codigo de tarifa
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de cargar los datos de una tarifa de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  20/09/2006       Fecha Última Actualización:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$ls_sql= " SELECT scv_tarifas.*,".
				 "        (SELECT despai FROM sigesp_pais".
				 "          WHERE scv_tarifas.codpai=sigesp_pais.codpai) as despai,".
				 "        (SELECT denreg FROM scv_regiones".
				 "          WHERE scv_tarifas.codreg=scv_regiones.codreg) as denreg".
				 "  FROM scv_tarifas".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codtar='".$as_codtar."' ";
		$rs_data= $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
		  	return false;
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_load_tarifa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_numrows = $this->io_sql->num_rows($rs_data);	    
			if ($li_numrows>0)
			{
				return $rs_data;
			}
			else
			{
				return false;
			}
		}
	}

	function uf_check_relaciones($as_codemp,$as_codtar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Metodo:  uf_check_relaciones
		//	      Access:  public
		// 	   Arguments:  $as_codemp // codigo de empresa.
		//     			   $as_codtar // codigo de tarifa
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de verificar si existen tablas relacionadas al Código de la Dependencia. 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codasi".
				"  FROM scv_dt_asignaciones".
				" WHERE codemp='".$as_codemp."'".
				"   AND proasi='TVS'".
				"   AND codasi='".$as_codtar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_scv_c_tarifas METODO->uf_check_relaciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->is_msg_error="La Tarifa no puede ser eliminada, posee registros asociados a otras tablas";
			}
		}
		return $lb_valido;	
	}
	
}//Fin class sigesp_scv_c_tarifas
?> 