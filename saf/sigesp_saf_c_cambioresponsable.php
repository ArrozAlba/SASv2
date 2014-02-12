<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_cambioresponsable
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_cambioresponsable()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=      new class_sql($this->con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	function uf_saf_select_cambioresponsable($as_codemp,$as_cmpmov,$ad_feccam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_cambioresponsable
		//         Access: private 
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $ad_feccam //fecha del cambio de responsable
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica existe el movimiento 
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT cmpmov".
				"  FROM saf_cambioresponsable  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND cmpmov='".$as_cmpmov."'" .
				"   AND feccam='".$ad_feccam."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_select_cambioresponsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_saf_select_cambioresponsable
	
	function  uf_saf_insert_cambioresponsable($as_codemp,$as_cmpmov,$as_codact,$as_idact,$ad_feccam,$as_obstra,$as_codusureg,$as_codres,
											  $as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_cambioresponsable
		//         Access: public
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $as_codact //Codigo de Activo
		//                 $as_idact  //Identificador de Activo
		//                 $ad_feccam //fecha del cambio de responsable
		//                 $as_obstra //observaciones del cambio de responsable
		//                 $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el movimiento de cambio de responsable
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO saf_cambioresponsable (codemp,cmpmov,feccam,obstra,codusureg,codres,codresnew,codact,idact) ".
				" VALUES('".$as_codemp."','".$as_cmpmov."','".$ad_feccam."','".$as_obstra."','".$as_codusureg."', ".
				" 		 '".$as_codres."','".$as_codresnew."','".$as_codact."','".$as_idact."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_insert_cambioresponsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cambio de Responsable ".$as_cmpmov."del personal ".$as_codres." al ".$as_codresnew." al activo ".$as_codact."-".$as_idact;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_cambioresponsable

	function uf_saf_update_dta_uso($as_codemp,$as_codact,$as_ideact,$as_codres,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dta_uso
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo del activo
		//                 $as_ideact //identificación del elemento u objeto
		//                 $as_codres //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE saf_dta".
				"   SET codres='".$as_codres."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codact='".$as_codact."'".
				"   AND ideact='".$as_ideact."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_dta_uso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Responsable por uso del Activo ".$as_codact." - ".$as_ideact." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dta_uso
	
	function uf_saf_update_dta_primario($as_codemp,$as_codact,$as_ideact,$as_codres,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dta_primario
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo del activo
		//                 $as_ideact //identificación del elemento u objeto
		//                 $as_codres //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 03/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE saf_dta".
				"   SET codrespri='".$as_codres."'".
				" WHERE codemp='".$as_codemp."'".
				"   AND codact='".$as_codact."'".
				"   AND ideact='".$as_ideact."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_dta_primario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Responsable primario del Activo ".$as_codact." - ".$as_ideact." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dta_primario

	function uf_saf_procesar_cambioresponsable($as_codemp,$as_cmpmov,$as_codact,$as_idact,$ad_feccam,$as_obstra,$as_codusureg,$as_codres,
											   $as_codresnew,$as_tiporesponsable,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_procesar_cambioresponsable
		//         Access: public
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $as_codact //Codigo de Activo
		//                 $as_idact  //Identificador de Activo
		//                 $ad_feccam //fecha del cambio de responsable
		//                 $as_obstra //observaciones del cambio de responsable
		//                 $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//                 $as_tiporesponsable //Tipo de Responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza las operaciones asociadas al cambio del responsable de un activo 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 21/11/2007 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_feccam=$this->io_funcion->uf_convertirdatetobd($ad_feccam);
		$lb_existe=$this->uf_saf_select_cambioresponsable($as_codemp,$as_cmpmov,$ad_feccam);
		if(!$lb_existe)
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_saf_insert_cambioresponsable($as_codemp,$as_cmpmov,$as_codact,$as_idact,$ad_feccam,$as_obstra,
															  $as_codusureg,$as_codres,$as_codresnew,$aa_seguridad);
			if($lb_valido)
			{
				if($as_tiporesponsable==0)
				{
					$lb_valido=$this->uf_saf_update_dta_primario($as_codemp,$as_codact,$as_idact,$as_codresnew,$aa_seguridad);
				}
				else
				{
					$lb_valido=$this->uf_saf_update_dta_uso($as_codemp,$as_codact,$as_idact,$as_codresnew,$aa_seguridad);
				}
				if(!$lb_valido)
				{break;}
			}
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_msg->message("El Cambio de responsable fue exitoso");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_msg->message("No se proceso el cambio de responsable");
			}
		}
		else
		{
			$this->io_msg->message("Error. El movimiento ya esta registrado");
		}
		return $lb_valido;
	} // end  function uf_saf_procesar_cambioresponsable
} 
?>
