<?php
class sigesp_scv_c_config
{
	var $ls_sql;
	var $is_msg_error;
		
	function sigesp_scv_c_config($conn)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->seguridad = new sigesp_c_seguridad();		  
		$this->io_funcion = new class_funciones();
		$this->io_sql= new class_sql($conn);
		$this->io_msg= new class_mensajes();		
	}
	
	function uf_insert_scv_config($as_codemp,$as_codsis,$as_seccion,$as_entry,$as_type,$as_value,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_insert_scv_config
		//	          Access:  public
		//	        Arguments  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//    			       $as_denmis    // Denominación de la Misión.
		//     				   $aa_seguridad // Arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de insertar una nueva misión en la tabla scv_misiones. 
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  13/11/2006    
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" INSERT INTO sigesp_config (codemp,codsis,seccion,entry,type,value)".
				"      VALUES ('".$as_codemp."','".$as_codsis."','".$as_seccion."','".$as_entry."','".$as_type."',".
				"              '".$as_value."')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)		     
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_config METODO->uf_insert_scv_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($as_entry=="BENEFICIARIO")
			{$as_entry= "de BENEFICIARIO";}
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Configuración de Viaticos, definiendo la cuenta <b> ".$as_value.
							 "</b> como cuenta de viaticos <b>".$as_entry."</b> Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////// 		     
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin de la function uf_insert_scv_config
	
	function uf_update_scv_config($as_codemp,$as_codsis,$as_seccion,$as_entry,$as_type,$as_value,$aa_seguridad) 
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_update_scv_config
		//	          Access:  public
		//	       Arguments:  $as_codemp    // Código de la Empresa.
		//        			   $as_codmis    //  Código de la Misión.
		//    			       $as_denmis    // Denominación de la Misión.
		//     				   $aa_seguridad // Arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de actualizar los datos de una Misión en la tabla scv_misiones. 
		//     Elaborado Por:  Ing. Néstor Falcón.
		// Fecha de Creación:  22/06/2006        
		//    Modificado Por:  Ing. Luis Anibal Lang
		//   Fecha de Modif.:  18/09/2006      
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" UPDATE sigesp_config SET value='".$as_value."'".
				" WHERE codemp='" .$as_codemp. "'".
				"   AND codsis='".$as_codsis."'".
				"   AND seccion='".$as_seccion."'".
				"   AND entry='".$as_entry."'".
				"   AND type='".$as_type."'";
		$rs_data = $this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_config METODO->uf_update_scv_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			if($as_entry=="BENEFICIARIO")
			{$as_entry= "de BENEFICIARIO";}
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Configuración de Viaticos, definiendo la cuenta <b> ".$as_value.
							 "</b> como cuenta de viaticos <b>".$as_entry."</b> Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		     
		}  		      
		return $lb_valido;
	} // fin de la function uf_update_scv_config
			
	function uf_scv_load_config($as_codemp,$as_codsis,$as_seccion,$as_entry,&$as_spgcuenta,&$as_denspgcuenta) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_config
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de la Empresa.
		//        			   $as_codmis    //  código de la Misión.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe o no la configuracion de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  13/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		if(($as_entry=="NACIONALES")||($as_entry=="INTERNACIONALES"))
		{
			$ls_sql=" SELECT value,".
					"        (SELECT MAX(denominacion) FROM spg_cuentas".
					"          WHERE spg_cuentas.spg_cuenta=sigesp_config.value".
					"          GROUP BY spg_cuenta) AS denominacion".
					"   FROM sigesp_config".
					"  WHERE codemp='".$as_codemp."'".
					"    AND codsis='".$as_codsis."'".
					"    AND seccion='".$as_seccion."'".
					"    AND entry='".$as_entry."'";
		}
		else
		{
			$ls_sql=" SELECT value,".
					"        (SELECT MAX(denominacion) FROM scg_cuentas".
					"          WHERE scg_cuentas.sc_cuenta=sigesp_config.value".
					"          GROUP BY sc_cuenta) AS denominacion".
					"   FROM sigesp_config".
					"  WHERE codemp='".$as_codemp."'".
					"    AND codsis='".$as_codsis."'".
					"    AND seccion='".$as_seccion."'".
					"    AND entry='".$as_entry."'";
		}

		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_config METODO->uf_scv_load_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_spgcuenta=$row["value"];
				$as_denspgcuenta=$row["denominacion"];
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_load_config
	
	function uf_scv_select_config($as_codemp,$as_codsis,$as_seccion,$as_entry) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_config
		//	          Access:  public
		//	       Arguments:  $as_codemp    // código de la Empresa.
		//        			   $as_codmis    //  código de la Misión.
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar si existe o no la configuracion de viaticos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  13/11/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT value".
				"   FROM sigesp_config".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codsis='".$as_codsis."'".
				"    AND seccion='".$as_seccion."'".
				"    AND entry='".$as_entry."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_config METODO->uf_scv_select_config ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // fin de la function uf_scv_select_config
	
} //Fin de la class sigesp_scv_c_misiones
?> 