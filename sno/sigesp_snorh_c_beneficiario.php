<?php
class sigesp_snorh_c_beneficiario
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_beneficiario()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_beneficiario
		//		   Access: public (sigesp_snorh_d_beneficiario)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_beneficiario)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_beneficiario($as_codper,$as_codben,$as_tipben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   as_codben  // Código del Beneficiario
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el beneficiario está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codben ".
		        "  FROM sno_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codben='".$as_codben."'".
				"   AND tipben='".$as_tipben."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_select_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}// end function uf_select_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_beneficiario($as_codper,$as_codben,$as_cedben,$as_nomben,$as_apeben,$as_dirben,$as_telben,$as_tipben,
									$as_nomcheben,$ai_porpagben,$ai_monpagben,$as_codban,$as_ctaban,$as_forpagben,$as_nacben,$as_tipcueben,$as_nexben,$as_cedaut,$as_numexpben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codben  // Código del Beneficiario
		//				   as_cedben  // Cedula del Beneficiario
		//				   as_nomben  // Nombre del Beneficiario
		//				   as_apeben  // Apellido del Beneficiario
		//				   as_dirben  // Direccion del Beneficiario
		//				   as_telben  // Telefono del Beneficiario
		//				   as_tipben  // Tipo de beneficiario
		//				   as_nomcheben  // Nombre del cheque del Beneficiario
		//				   ai_porpagben //  Porcentaje de pago del Beneficiario
		//				   ai_monpagben //  Monto del pago  del Beneficiario
		//				   as_codban //  Código de Banco
		//				   as_ctaban //  Cuenta de Banco
		//				   as_forpagben  // Forma de Pago del Beneficiario
		//				   as_nacben  // Nacionalidad del Beneficiario
		//				   as_tipcueben  // Tipo de Cuenta del Beneficiario
		//                 as_nexben  // parentesco del beneficiario con el trabajador
		//				   as_cedaut  // cedula del autorizado
		//                 as_numexpben // numero de expediente del beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_beneficiario (codemp, codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, ".
				"porpagben, monpagben, codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben, nexben, cedaut, numexpben) VALUES ('".$this->ls_codemp."','".$as_codper."','".$as_codben."',".
				"'".$as_cedben."','".$as_nomben."','".$as_apeben."','".$as_dirben."','".$as_telben."','".$as_tipben."','".$as_nomcheben."',".$ai_porpagben.",".
				"".$ai_monpagben.",'".$as_codban."','".$as_ctaban."','','".$as_forpagben."','".$as_nacben."','".$as_tipcueben."','".$as_nexben."','".$as_cedaut."', '".$as_numexpben."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el beneficiario ".$as_codben."-".$as_cedben." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Beneficiario fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_insert_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_beneficiario($as_codper,$as_codben,$as_cedben,$as_nomben,$as_apeben,$as_dirben,$as_telben,$as_tipben,
									$as_nomcheben,$ai_porpagben,$ai_monpagben,$as_codban,$as_ctaban,$as_forpagben,$as_nacben,$as_tipcueben,$as_nexben,$as_cedaut, $as_numexpben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codben  // Código del Beneficiario
		//				   as_cedben  // Cedula del Beneficiario
		//				   as_nomben  // Nombre del Beneficiario
		//				   as_apeben  // Apellido del Beneficiario
		//				   as_dirben  // Direccion del Beneficiario
		//				   as_telben  // Telefono del Beneficiario
		//				   as_tipben  // Tipo de beneficiario
		//				   as_nomcheben  // Nombre del cheque del Beneficiario
		//				   ai_porpagben //  Porcentaje de pago del Beneficiario
		//				   ai_monpagben //  Monto del pago  del Beneficiario
		//				   as_codban //  Código de Banco
		//				   as_ctaban //  Cuenta de Banco
		//				   as_forpagben  // Forma de Pago del Beneficiario
		//				   as_nacben  // Nacionalidad del Beneficiario
		//				   as_tipcueben  // Tipo de Cuenta del Beneficiario
		//                 as_nexben  // parentesco del beneficiario con el trabajador
		//				   as_cedaut  // cedula del autorizado
		//                 as_numexpben // numero de expdiente del beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_beneficiario ".
				"   SET cedben='".$as_cedben."', ".
				"		nomben='".$as_nomben."', ".
				"		apeben='".$as_apeben."', ".
				"		dirben='".$as_dirben."', ".
				"		telben='".$as_telben."', ".				
				"		nomcheben='".$as_nomcheben."', ".
				"		porpagben=".$ai_porpagben.", ".
				"		monpagben=".$ai_monpagben.", ".
				"		codban='".$as_codban."', ".
				"		ctaban='".$as_ctaban."', ".
				"		forpagben='".$as_forpagben."', ".
				"		nacben='".$as_nacben."', ".
				"		tipcueben='".$as_tipcueben."', ".
				"		nexben='".$as_nexben."', ".
				"		cedaut='".$as_cedaut."', ".
				"		numexpben='".$as_numexpben."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codben='".$as_codben."'".
				"	AND	tipben='".$as_tipben."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_update_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el beneficiario ".$as_codben."-".$as_cedben." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Beneficiario fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
	        	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_update_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_codben,$as_cedben,$as_nomben,$as_apeben,$as_dirben,$as_telben,$as_tipben,
						$as_nomcheben,$ai_porpagben,$ai_monpagben,$as_codban,$as_ctaban,$as_forpagben,$as_nacben,$as_tipcueben,
						$as_nexben,$as_cedaut,$as_numexpben,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_beneficiario)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codben  // Código del Beneficiario
		//				   as_cedben  // Cedula del Beneficiario
		//				   as_nomben  // Nombre del Beneficiario
		//				   as_apeben  // Apellido del Beneficiario
		//				   as_dirben  // Direccion del Beneficiario
		//				   as_telben  // Telefono del Beneficiario
		//				   as_tipben  // Tipo de beneficiario
		//				   as_nomcheben  // Nombre del cheque del Beneficiario
		//				   ai_porpagben //  Porcentaje de pago del Beneficiario
		//				   ai_monpagben //  Monto del pago  del Beneficiario
		//				   as_codban //  Código de Banco
		//				   as_ctaban //  Cuenta de Banco
		//				   as_forpagben  // Forma de Pago del Beneficiario
		//				   as_nacben  // Nacionalidad del Beneficiario
		//				   as_tipcueben  // Tipo de Cuenta del Beneficiario
		//                 as_nexben  // parentesco del beneficiario con el trabajador
		//				   as_cedaut  // cedula del autorizado
		//                 as_numexpben // número del expediente del beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ai_porpagben=str_replace(".","",$ai_porpagben);
		$ai_porpagben=str_replace(",",".",$ai_porpagben);
		$ai_monpagben=str_replace(".","",$ai_monpagben);
		$ai_monpagben=str_replace(",",".",$ai_monpagben);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_beneficiario($as_codper,$as_codben,$as_tipben)===false)
				{
					$lb_valido=$this->uf_insert_beneficiario($as_codper,$as_codben,$as_cedben,$as_nomben,$as_apeben,$as_dirben,
															 $as_telben,$as_tipben,$as_nomcheben,$ai_porpagben,$ai_monpagben,
															 $as_codban,$as_ctaban,$as_forpagben,$as_nacben,$as_tipcueben,
															 $as_nexben,$as_cedaut,$as_numexpben,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Beneficiario ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_beneficiario($as_codper,$as_codben,$as_tipben)))
				{
					$lb_valido=$this->uf_update_beneficiario($as_codper,$as_codben,$as_cedben,$as_nomben,$as_apeben,$as_dirben,
															 $as_telben,$as_tipben,$as_nomcheben,$ai_porpagben,$ai_monpagben,
															 $as_codban,$as_ctaban,$as_forpagben,$as_nacben,$as_tipcueben,
															 $as_nexben, $as_cedaut,$as_numexpben,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Beneficiario no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_beneficiario($as_codper,$as_codben,$as_tipben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_beneficiario
		//		   Access: public (sigesp_snorh_d_beneficiario)
		//	    Arguments: as_codper  // Código del Personal
		//				   as_codben  // Código del Beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codben='".$as_codben."'".
				"	AND	tipben='".$as_tipben."' ";
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->beneficiario MÉTODO->uf_delete_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino el beneficiario ".$as_codben." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Beneficiario fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
 		       	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_delete_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_familiar
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_sueldo_beneficiario($as_cedben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sueldo_beneficiario
		//		   Access: private
		//	    Arguments: as_cedben  // Cédula del Beneficiario		
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que devuelve el sueldo del beneficiario
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 16/10/2008				Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_sueldo=0;
		$ls_sql="SELECT sno_beneficiario.codper, sno_beneficiario.porpagben, sno_beneficiario.monpagben, sno_personalnomina.sueper ".
		        "  FROM sno_beneficiario ".
				" INNER JOIN sno_personalnomina ".
				"    ON sno_beneficiario.codemp='".$this->ls_codemp."' ".
				"   AND (sno_beneficiario.cedben='".$as_cedben."' OR sno_beneficiario.cedaut='".$as_cedben."') ".
				"   AND sno_personalnomina.codemp = sno_beneficiario.codemp ".
				"   AND sno_personalnomina.codper = sno_beneficiario.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_select_sueldo_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(!$rs_data->EOF)
			{
				$ls_codper=$rs_data->fields["codper"];
				$ls_porpagben=$rs_data->fields["porpagben"];
				$ls_monpagbe=$rs_data->fields["monpagben"];
				$li_sueper=$rs_data->fields["sueper"];
				if (trim($ls_porpagben)==0)
				{
					$li_sueldo=$li_sueldo+$ls_monpagbe;
				}
				else
				{
					$li_sueldo=$li_sueldo+round($li_sueper * $ls_porpagben)/100;
				}
				$rs_data->MoveNext();
			}
		}
		return $li_sueldo;
	}// end function uf_select_sueldo_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_porcentaje_beneficiario($as_cedben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_porcentaje_beneficiario
		//		   Access: private
		//	    Arguments: as_cedben  // Cédula del Beneficiario		
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que devuelve el sueldo del beneficiario
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 16/10/2008				Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_porc=0;
		$ls_sql="SELECT porpagben ".
		        "  FROM sno_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".				
				"   AND cedben='".$as_cedben."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Beneficiario MÉTODO->uf_select_porcentaje_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$li_porc=$row["porpagben"];
			}
			$this->io_sql->free_result($rs_data);
		}
		
		return $li_porc;
	}// end function uf_select_porcentaje_beneficiarioo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>