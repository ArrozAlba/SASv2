<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_entregaunidad
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_entregaunidad()
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
	
   //------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_entregaunidad
		//         Access: public (sigesp_siv_p_entregaunidad)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_cmpent    //Nº del Comprobante de la entrega de unidad
		//                 $ad_fecentuni //fecha de la entrega de unidad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe el comprobante de entrega de unidad
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT cmpent".
				"  FROM saf_entregauniadm  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND cmpent='".$as_cmpent."'".
				"   AND fecentuni='".$ad_fecentuni."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entregaunidad MÉTODO->uf_saf_select_entregaunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end function uf_saf_select_entregaunidad
   //------------------------------------------------------------------------------------------------------------------------------
											
   //------------------------------------------------------------------------------------------------------------------------------
	function  uf_saf_insert_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni,$as_coduniadm,$as_obsentuni,$as_codusureg,$as_codres,
										  $as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_entregaunidad
		//         Access: public (sigesp_siv_p_centregaunidad)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpent //Nº del Comprobante de la entrega
		//                 $ad_fecentuni //fecha de la entrega
		//                 $as_coduniadm //codigo de la unidad administrativa
		//                 $as_obsentuni //observaciones de la entrega
		//                 $as_codusureg //codigo del usuario que esta haciendo la entrega
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una nueva entrega de unidad administrativa en la tabla saf_entregauniadm
		//	   Creado Por: Ing. Luis Lang /Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007 								Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO saf_entregauniadm (codemp,cmpent,fecentuni,codusureg,coduniadm,codres,codresnew,obsentuni) ".
				" VALUES('".$as_codemp."','".$as_cmpent."','".$ad_fecentuni."','".$as_codusureg."','".$as_coduniadm."', ".
				" 		 '".$as_codres."','".$as_codresnew."','".$as_obsentuni."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->entregauniadad MÉTODO->uf_saf_insert_entregaunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Entrega de la Unidad ".$as_coduniadm."del personal ".$as_codres." al ".$as_codresnew.
								 " Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_entregaunidad
   //------------------------------------------------------------------------------------------------------------------------------

   //------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_activosresponsableuso($as_codemp,$as_codres,$as_coduniadm,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activosresponsableuso
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codres //codigo del responsable
		//                 $as_coduniadm //codigo de unidad administrativa
		//                 $rs_data //resulset de la busqueda
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los activos que estan asociados a un responsable en particular en la tabla saf_dta
		//	   Creado Por: Ing. Luis Lang /Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007								Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codact,ideact".
				"  FROM saf_dta  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND coduniadm='".$as_coduniadm."'".
				"   AND codres='".$as_codres."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entregaunidad MÉTODO->uf_saf_select_activosresponsableuso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end function uf_saf_select_activosresponsableuso
   //------------------------------------------------------------------------------------------------------------------------------

   //------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_activosresponsableprimario($as_codemp,$as_codres,$as_coduniadm,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activosresponsableprimario
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codres //codigo del responsable
		//                 $as_coduniadm //codigo de unidad administrativa
		//                 $rs_data //resulset de la busqueda
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los activos que estan asociados a un responsable en particular en la tabla saf_dta
		//	   Creado Por: Ing. Luis Lang /Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007								Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codact,ideact".
				"  FROM saf_dta  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND coduniadm='".$as_coduniadm."'".
				"   AND codrespri='".$as_codres."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->entregaunidad MÉTODO->uf_saf_select_activosresponsableprimario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end function uf_saf_select_activosresponsableprimario
   //------------------------------------------------------------------------------------------------------------------------------

   //------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_dtauso($as_codemp,$as_codact,$as_ideact,$as_codresnew,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtauso
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo del activo
		//                 $as_ideact //identificación del elemento u objeto
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007										Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE saf_dta".
				"   SET codres='". $as_codresnew ."'".
				" WHERE codemp='" . $as_codemp ."'".
				"   AND codact='" . $as_codact ."'".
				"   AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_dtauso ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Responsable del Activo ".$as_codact." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtauso
   //------------------------------------------------------------------------------------------------------------------------------
	
   //------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_dtaprimario($as_codemp,$as_codact,$as_ideact,$as_codresnew,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtaprimario
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo del activo
		//                 $as_ideact //identificación del elemento u objeto
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un los responsables de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007										Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE saf_dta".
				"   SET codrespri='". $as_codresnew ."'".
				" WHERE codemp='" . $as_codemp ."'".
				"   AND codact='" . $as_codact ."'".
				"   AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->cambioresponsable MÉTODO->uf_saf_update_dtaprimario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Responsable del Activo ".$as_codact." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtaprimario
   //------------------------------------------------------------------------------------------------------------------------------

   //------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_procesar_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni,$as_coduniadm,$as_obsentuni,$as_codusureg,$as_codres,$as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_procesar_cambioresponsable
		//         Access: public (sigesp_siv_p_cambioresponsable)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpent //Nº del Comprobante de la entrega
		//                 $ad_fecentuni //fecha de la entrega
		//                 $as_coduniadm //codigo de la unidad administrativa
		//                 $as_obsentuni //observaciones de la entrega
		//                 $as_codusureg //codigo del usuario que esta haciendo la entrega
		//                 $as_codres // codigo del responsable actual
		//                 $as_codresnew //codigo del nuevo responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que realiza las operaciones asociadas al cambio de un responsable 
		//	   Creado Por: Ing. Luis Lang / Ing. Yesenia Moreno
		// Fecha Creación: 20/11/2007								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ad_fecentuni=$this->io_funcion->uf_convertirdatetobd($ad_fecentuni);
		$this->io_sql->begin_transaction();
		$lb_existe=$this->uf_saf_select_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni);
		if(!$lb_existe)
		{
			$lb_valido=$this->uf_saf_insert_entregaunidad($as_codemp,$as_cmpent,$ad_fecentuni,$as_coduniadm,$as_obsentuni,
														  $as_codusureg,$as_codres,$as_codresnew,$aa_seguridad);
			if($lb_valido)
			{
				$rs_datauso="";
				$lb_valido=$this->uf_saf_select_activosresponsableuso($as_codemp,$as_codres,$as_coduniadm,$rs_datauso);
				if($lb_valido)
				{
					$li_actuso=0;
					while($row=$this->io_sql->fetch_row($rs_datauso))
					{
						$li_actuso++;
						$as_codact=$row["codact"];
						$as_ideact=$row["ideact"];
						$lb_valido=$this->uf_saf_update_dtauso($as_codemp,$as_codact,$as_ideact,$as_codresnew,$aa_seguridad);
						if(!$lb_valido)
						{break;}
					}
				}
				if($lb_valido)
				{
					$rs_dataprimario="";
					$lb_valido=$this->uf_saf_select_activosresponsableprimario($as_codemp,$as_codres,$as_coduniadm,$rs_dataprimario);
					if($lb_valido)
					{
						$li_actpri=0;
						while($row=$this->io_sql->fetch_row($rs_dataprimario))
						{
							$li_actpri++;
							$as_codact=$row["codact"];
							$as_ideact=$row["ideact"];
							$lb_valido=$this->uf_saf_update_dtaprimario($as_codemp,$as_codact,$as_ideact,$as_codresnew,$aa_seguridad);
							if(!$lb_valido)
							{break;}
						}
					}
				}
			}
			if($lb_valido)
			if(($li_actuso==0)&&($li_actpri==0))
			{
				$this->io_msg->message("El usuario no tiene Activos asociados en la unidad indicada");
				$lb_valido=false;
			}
			if($lb_valido)
			{
				$this->io_sql->commit();
				$this->io_msg->message("El cambio de unidad fue exitoso");
			}
			else
			{
				$this->io_sql->rollback();
				$this->io_msg->message("No se proceso el cambio de unidad");
			}
		}
		else
		{
			$this->io_msg->message("Error. El movimiento ya esta registrado");
		}
		return $lb_valido;
	} // end  function uf_saf_procesar_cambioresponsable
   //------------------------------------------------------------------------------------------------------------------------------
} 
?>
