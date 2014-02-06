<?php
class sigesp_snorh_c_ipasme_afiliado
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $io_personal;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_ipasme_afiliado()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_ipasme_afiliado
		//		   Access: public (sigesp_snorh_d_ipasme_afiliado)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
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
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("sigesp_snorh_c_personal.php");
		$this->io_personal= new sigesp_snorh_c_personal();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ipasme_afiliado)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_personal);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ipasme_afiliado($as_campo,$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ipasme_afiliado
		//		   Access: private
 		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal esta afiliado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." ".
				"  FROM sno_ipasme_afiliado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ".$as_campo."='".$as_valor."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_select_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ipasme_afiliado($as_codper,$as_tiptraafi,$as_coddep,$as_actlabafi,$as_tipafiafi,$as_codban,$as_cuebanafi,
									   $as_tipcueafi,$as_codent,$as_codmun,$as_codloc,$as_urbafi,$as_aveafi,$as_nomresafi,
									   $as_numresafi,$as_pisafi,$as_zonafi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ipasme_afiliado
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//				   as_tiptraafi  // Tipo de Transacción
		//				   as_coddep  // Código de la Dependencia 
		//				   as_actlabafi  // Actividad Laboral 
		//				   as_tipafiafi  // Tipo de Afiliación
		//				   as_codban  // Código de Banco 
		//				   as_cuebanafi  // Cuenta banco
		//				   as_codent  // Código de Entidad
		//				   as_codmun  // Código de Municipio
		//				   as_codloc  // Código de Localización 
		//				   as_urbafi  // Urbanización
		//				   as_aveafi  // Avenida
		//				   as_nomresafi  // Nombre de Residencia
		//				   as_numresafi  // Número de Residencia
		//				   as_pisafi  // Piso
		//				   as_zonafi  // Zona Postal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_ipasme_afiliado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_ipasme_afiliado(codemp,codper,tiptraafi,coddep,actlabafi,tipafiafi,codban,cuebanafi,tipcueafi,".
				"codent,codmun,codloc,urbafi,aveafi,nomresafi,numresafi,pisafi,zonafi)VALUES('".$this->ls_codemp."','".$as_codper."',".
				"'".$as_tiptraafi."','".$as_coddep."','".$as_actlabafi."','".$as_tipafiafi."','".$as_codban."','".$as_cuebanafi."',".
				"'".$as_tipcueafi."','".$as_codent."','".$as_codmun."','".$as_codloc."','".$as_urbafi."','".$as_aveafi."',".
				"'".$as_nomresafi."','".$as_numresafi."','".$as_pisafi."','".$as_zonafi."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_insert_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Afiliado Ipasme ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Afiliado fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_insert_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ipasme_afiliado($as_codper,$as_tiptraafi,$as_coddep,$as_actlabafi,$as_tipafiafi,$as_codban,$as_cuebanafi,
									   $as_tipcueafi,$as_codent,$as_codmun,$as_codloc,$as_urbafi,$as_aveafi,$as_nomresafi,
									   $as_numresafi,$as_pisafi,$as_zonafi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_ipasme_afiliado
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//				   as_tiptraafi  // Tipo de Transacción
		//				   as_coddep  // Código de la Dependencia 
		//				   as_actlabafi  // Actividad Laboral 
		//				   as_tipafiafi  // Tipo de Afiliación
		//				   as_codban  // Código de Banco 
		//				   as_cuebanafi  // Cuenta banco
		//				   as_codent  // Código de Entidad
		//				   as_codmun  // Código de Municipio
		//				   as_codloc  // Código de Localización 
		//				   as_urbafi  // Urbanización
		//				   as_aveafi  // Avenida
		//				   as_nomresafi  // Nombre de Residencia
		//				   as_numresafi  // Número de Residencia
		//				   as_pisafi  // Piso
		//				   as_zonafi  // Zona Postal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_ipasme_afiliado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_ipasme_afiliado ".
				"   SET tiptraafi='".$as_tiptraafi."', ".
				"   	coddep='".$as_coddep."', ".
				"   	actlabafi='".$as_actlabafi."', ".
				"   	tipafiafi='".$as_tipafiafi."', ".
				"   	codban='".$as_codban."', ".
				"   	cuebanafi='".$as_cuebanafi."', ".
				"   	codent='".$as_codent."', ".
				"   	codmun='".$as_codmun."', ".
				"   	codloc='".$as_codloc."', ".
				"   	urbafi='".$as_urbafi."', ".
				"   	aveafi='".$as_aveafi."', ".
				"   	nomresafi='".$as_nomresafi."', ".
				"   	numresafi='".$as_numresafi."', ".
				"   	pisafi='".$as_pisafi."', ".
				"   	zonafi='".$as_zonafi."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_update_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Afiliado ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Afiliado fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_update_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$as_tiptraafi,$as_coddep,$as_actlabafi,$as_tipafiafi,$as_codban,$as_cuebanafi,$as_tipcueafi,
						$as_codent,$as_codmun,$as_codloc,$as_urbafi,$as_aveafi,$as_nomresafi,$as_numresafi,$as_pisafi,$as_zonafi,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_ipasme_afiliado)
		//	    Arguments: as_codper  // código de personal
		//				   as_tiptraafi  // Tipo de Transacción
		//				   as_coddep  // Código de la Dependencia 
		//				   as_actlabafi  // Actividad Laboral 
		//				   as_tipafiafi  // Tipo de Afiliación
		//				   as_codban  // Código de Banco 
		//				   as_cuebanafi  // Cuenta banco
		//				   as_codent  // Código de Entidad
		//				   as_codmun  // Código de Municipio
		//				   as_codloc  // Código de Localización 
		//				   as_urbafi  // Urbanización
		//				   as_aveafi  // Avenida
		//				   as_nomresafi  // Nombre de Residencia
		//				   as_numresafi  // Número de Residencia
		//				   as_pisafi  // Piso
		//				   as_zonafi  // Zona Postal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_ipasme_afiliado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_ipasme_afiliado("codper",$as_codper)===false)
				{
					$lb_valido=$this->uf_insert_ipasme_afiliado($as_codper,$as_tiptraafi,$as_coddep,$as_actlabafi,$as_tipafiafi,
																$as_codban,$as_cuebanafi,$as_tipcueafi,$as_codent,$as_codmun,
																$as_codloc,$as_urbafi,$as_aveafi,$as_nomresafi,$as_numresafi,
																$as_pisafi,$as_zonafi,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Afiliado ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_ipasme_afiliado("codper",$as_codper)))
				{
					$lb_valido=$this->uf_update_ipasme_afiliado($as_codper,$as_tiptraafi,$as_coddep,$as_actlabafi,$as_tipafiafi,
																$as_codban,$as_cuebanafi,$as_tipcueafi,$as_codent,$as_codmun,
																$as_codloc,$as_urbafi,$as_aveafi,$as_nomresafi,$as_numresafi,
																$as_pisafi,$as_zonafi,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("el Afiliado no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_ipasme_beneficiario($as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ipasme_beneficiario
		//		   Access: private
 		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal esta en beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_ipasme_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_select_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_ipasme_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_ipasme_afiliado($as_codper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_ipasme_afiliado
		//		   Access: public (sigesp_snorh_d_ipasme_afiliado)
		//	    Arguments: as_codper  // código de personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_ipasme_afiliado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->uf_select_ipasme_beneficiario($as_codper)===false)   
		{
			$ls_sql="DELETE FROM sno_ipasme_afiliado ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codper='".$as_codper."'";
        	$this->io_sql->begin_transaction();
		   	$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_delete_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Afiliado ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("El Afiliado fue Eliminado.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
        			$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_delete_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$this->io_sql->rollback();
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el Afiliado, hay Beneficiarios relacionados a esta.");
		}
		return $lb_valido;
    }// end function uf_delete_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------*/

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ipasme_afiliado(&$as_existe,&$as_codper,&$as_nomper,&$as_tiptraafi,&$as_coddep,&$as_desdep,&$as_actlabafi,
									 &$as_tipafiafi,&$as_codban,&$as_cuebanafi,&$as_tipcueafi,&$as_codent,&$as_codmun,&$as_codloc,
									 &$as_urbafi,&$as_aveafi,&$as_nomresafi,&$as_numresafi,&$as_pisafi,&$as_zonafi,&$ad_fecnacper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ipasme_afiliado
		//		   Access: public (sigesp_snorh_d_ipasme_afiliado)
		//	    Arguments: as_codper  // Código de Personal     
		//				   as_nomper // Nombre de PErsonal         
		//				   as_tiptraafi // Tipo de Transacción      
		//				   as_coddep // Código de Dependencia  
		//                 as_desdep // Descripción de Dependencia   
		//                 as_actlabafi // Actividad Laboral
		//                 as_tipafiafi // Tipo de Afiliación
		//				   as_codban // Código de Banco
		//                 as_cuebanafi // Cuenta de Banco  
		//				   as_tipcueafi // tipo de Cuenta    
		//				   as_codent // Código de entidad       
		//                 as_codmun // Código de Municipio  
		//				   as_codloc // Código de Localización   
		//				   as_urbafi // Urbanización 
		//				   as_aveafi // Avenida		   
		//				   as_nomresafi // Nombre de Residencia
		//				   as_numresafi // Número de Residencia   
		//				   as_pisafi // Piso
		//				   as_zonafi // Zona Postal			   
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Funcion que obtiene un  Afiliado en específico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_ipasme_afiliado.codper, sno_ipasme_afiliado.tiptraafi, sno_ipasme_afiliado.coddep, ".
				"		sno_ipasme_afiliado.actlabafi, sno_ipasme_afiliado.tipafiafi, sno_ipasme_afiliado.codban, ".
				"		sno_ipasme_afiliado.cuebanafi, sno_ipasme_afiliado.tipcueafi, sno_ipasme_afiliado.codent, ".
				"		sno_ipasme_afiliado.codmun, sno_ipasme_afiliado.codloc, sno_ipasme_afiliado.urbafi, ".
				"		sno_ipasme_afiliado.aveafi, sno_ipasme_afiliado.nomresafi, sno_ipasme_afiliado.pisafi, ".
				"		sno_ipasme_afiliado.zonafi, sno_ipasme_afiliado.numresafi, sno_personal.nomper, sno_personal.apeper, ".
				"		sno_personal.fecnacper, sno_ipasme_dependencias.desdep ".
				"  FROM sno_ipasme_afiliado, sno_personal, sno_ipasme_dependencias ".
				" WHERE sno_ipasme_afiliado.codemp = '".$this->ls_codemp."'".
				"   AND sno_ipasme_afiliado.codper = '".$as_codper."' ".
				"   AND sno_ipasme_afiliado.codemp = sno_personal.codemp ".
				"	AND sno_ipasme_afiliado.codper = sno_personal.codper ".
				"   AND sno_ipasme_afiliado.codemp = sno_ipasme_dependencias.codemp ".
				"	AND sno_ipasme_afiliado.coddep = sno_ipasme_dependencias.coddep ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Ipasme Afiliado MÉTODO->uf_load_ipasme_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existe="TRUE";
				$as_codper=$row["codper"];
				$as_nomper=$row["apeper"].", ".$row["nomper"];
				$as_coddep=$row["coddep"];
				$as_desdep=$row["desdep"];
				$as_tiptraafi=$row["tiptraafi"];
				$as_actlabafi=$row["actlabafi"];
				$as_tipafiafi=$row["tipafiafi"];
				$as_codban=$row["codban"];
				$as_cuebanafi=$row["cuebanafi"];
				$as_tipcueafi=$row["tipcueafi"];
				$as_codent=$row["codent"];
				$as_codmun=$row["codmun"];
				$as_codloc=$row["codloc"];
				$as_urbafi=$row["urbafi"];
				$as_aveafi=$row["aveafi"];
				$as_nomresafi=$row["nomresafi"];
				$as_pisafi=$row["pisafi"];
				$as_zonafi=$row["zonafi"];
				$as_numresafi=$row["numresafi"];
				$ad_fecnacper=$this->io_funciones->uf_convertirfecmostrar($row["fecnacper"]);
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_ipasme_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_afiliado($as_codperdes,$as_codperhas,$as_tiptra,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_afiliado
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_afiliado)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_tiptra // Tipo de Transacción  
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal Afiliado al ipasme y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IPAS","XXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_ipasme_afiliado.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_afiliado.codper<='".$as_codperhas."'";
		}
		if(!empty($as_tiptra))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_afiliado.tiptraafi='".$as_tiptra."'";
		}
		$ls_sql="SELECT sno_ipasme_afiliado.tiptraafi,sno_ipasme_afiliado.coddep,sno_personal.nacper,sno_personal.cedper, ".
				"		sno_personal.nomper,sno_personal.apeper,sno_personal.sexper,sno_personal.edocivper,sno_personal.fecnacper, ".
				"		sno_personal.fecingper,sno_ipasme_afiliado.actlabafi,sno_personal.fecegrper,sno_ipasme_afiliado.tipafiafi, ".
				"		sno_ipasme_afiliado.codban,sno_ipasme_afiliado.cuebanafi,sno_ipasme_afiliado.tipcueafi, ".
				"		sno_ipasme_afiliado.codent,sno_ipasme_afiliado.codmun,sno_ipasme_afiliado.codloc,sno_ipasme_afiliado.urbafi, ".
				"		sno_ipasme_afiliado.aveafi, sno_ipasme_afiliado.nomresafi, sno_ipasme_afiliado.numresafi, sno_ipasme_afiliado.pisafi, ".
				"		sno_ipasme_afiliado.zonafi,sno_personal.telhabper,sno_personal.telmovper,sno_personal.cauegrper,sno_personal.estper, ".
				"		SUM(sno_personalnomina.sueper) as sueper, SUM(sno_personalnomina.sueintper) as sueintper, count(sno_personalnomina.codper) as total ".
				"  FROM sno_personal, sno_ipasme_afiliado, sno_personalnomina, sno_nomina ".
				" WHERE sno_nomina.espnom = 0 ".
				"   AND sno_personal.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"	AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				"	AND sno_personal.codemp = sno_personalnomina.codemp ".
				"	AND sno_personal.codper = sno_personalnomina.codper ".
				"	AND sno_personalnomina.codemp = sno_nomina.codemp ".
				"	AND sno_personalnomina.codnom = sno_nomina.codnom  ".
				" GROUP BY sno_personal.codper, sno_ipasme_afiliado.tiptraafi,sno_ipasme_afiliado.coddep,sno_personal.nacper,sno_personal.cedper, ".
				"          sno_personal.nomper,sno_personal.apeper,sno_personal.sexper,sno_personal.edocivper,sno_personal.fecnacper, ".
				"          sno_personal.fecingper,sno_ipasme_afiliado.actlabafi,sno_personal.fecegrper,sno_ipasme_afiliado.tipafiafi, ".
				"          sno_ipasme_afiliado.codban,sno_ipasme_afiliado.cuebanafi,sno_ipasme_afiliado.tipcueafi,".
				"          sno_ipasme_afiliado.codent,sno_ipasme_afiliado.codmun,sno_ipasme_afiliado.codloc,sno_ipasme_afiliado.urbafi,".
				"          sno_ipasme_afiliado.aveafi, sno_ipasme_afiliado.nomresafi, sno_ipasme_afiliado.numresafi, sno_ipasme_afiliado.pisafi,".
				"          sno_ipasme_afiliado.zonafi,sno_personal.telhabper,sno_personal.telmovper,sno_personal.cauegrper,sno_personal.estper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_afiliado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_dia=substr($ad_fecmov,0,2);
			$ls_nombrearchivo=$as_ruta."/afiliados".$ls_codorg."_".$ls_ano.$ls_mes.$ls_dia.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_nomper=$row["nomper"];
				$li_pos=strpos($ls_nomper," ");
				if($li_pos>0)
				{
					$ls_prinom=substr($ls_nomper,0,$li_pos);
					$ls_prinom=substr($ls_prinom,0,15);
					$ls_segnom=substr($ls_nomper,$li_pos+1,15);
				}
				else
				{
					$ls_prinom=substr($ls_nomper,0,15);
					$ls_segnom="";
				}
				$ls_apeper=$row["apeper"];
				$li_pos=strpos($ls_apeper," ");
				if($li_pos>0)
				{
					$ls_priape=substr($ls_apeper,0,$li_pos);
					$ls_priape=substr($ls_priape,0,15);
					$ls_segape=substr($ls_apeper,$li_pos+1,15);
				}
				else
				{
					$ls_priape=substr($ls_apeper,0,15);
					$ls_segape="";
				}
				$ld_fecegrper=$row["fecegrper"];
				if($ld_fecegrper=="1900-01-01 00:00:00")
				{
					$ld_fecegrper="";
				}
				else
				{
					$ld_fecegrper=$this->io_funciones->uf_convertirfecmostrar($ld_fecegrper);
				}
				$ls_estper=$row["estper"];
				$ls_cauegrper=$row["cauegrper"];
				$ls_estatus="";
				switch($ls_estper)
				{
					case "1": // Activo
						$ls_estatus="A";
						break;
					case "3": // Egresado
						switch($ls_cauegrper)
						{
							case "N": // Ninguno
								$ls_estatus="R";
								break;
							case "D": // Despedido
								$ls_estatus="R";
								break;
							case "R": // Renuncia
								$ls_estatus="R";
								break;
							case "T": // Traaslado
								$ls_estatus="R";
								break;
							case "P": // Pensionado
								$ls_estatus="I";
								break;
							case "J": // Jubilado
								$ls_estatus="J";
								break;
							case "F": // Fallecido
								$ls_estatus="F";
								break;
						}
						break;
				}
				$li_sueper=($row["sueper"]/$row["total"]);
				$li_sueintper=($row["sueintper"]/$row["total"]);
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$row["tiptraafi"].":";
				$ls_cadena=$ls_cadena.$ls_codorg.":";
				$ls_cadena=$ls_cadena.$row["coddep"].":";
				$ls_cadena=$ls_cadena.$row["nacper"].":";
				$ls_cadena=$ls_cadena.$row["cedper"].":";
				$ls_cadena=$ls_cadena.$ls_prinom.":";
				$ls_cadena=$ls_cadena.$ls_segnom.":";
				$ls_cadena=$ls_cadena.$ls_priape.":";
				$ls_cadena=$ls_cadena.$ls_segape.":";
				$ls_cadena=$ls_cadena.$row["sexper"].":";
				$ls_cadena=$ls_cadena.$row["edocivper"].":";
				$ls_cadena=$ls_cadena.$this->io_funciones->uf_convertirfecmostrar($row["fecnacper"]).":";
				$ls_cadena=$ls_cadena.$this->io_funciones->uf_convertirfecmostrar($row["fecingper"]).":";
				$ls_cadena=$ls_cadena.$li_sueper.":";
				$ls_cadena=$ls_cadena.$li_sueintper.":";
				$ls_cadena=$ls_cadena.$row["actlabafi"].":";
				$ls_cadena=$ls_cadena.$ld_fecegrper.":";
				$ls_cadena=$ls_cadena.$row["tipafiafi"].":";
				$ls_cadena=$ls_cadena.$row["codban"].":";
				$ls_cadena=$ls_cadena.substr($row["cuebanafi"],0,20).":";
				$ls_cadena=$ls_cadena.$row["tipcueafi"].":";
				$ls_cadena=$ls_cadena.$row["codent"].":";
				$ls_cadena=$ls_cadena.$row["codmun"].":";
				$ls_cadena=$ls_cadena.$row["codloc"].":";
				$ls_cadena=$ls_cadena.$row["urbafi"].":";
				$ls_cadena=$ls_cadena.$row["aveafi"].":";
				$ls_cadena=$ls_cadena.$row["nomresafi"].":";
				$ls_cadena=$ls_cadena.$row["numresafi"].":";
				$ls_cadena=$ls_cadena.$row["pisafi"].":";
				$ls_cadena=$ls_cadena.$row["zonafi"].":";
				$ls_cadena=$ls_cadena.$row["telhabper"].":";
				$ls_cadena=$ls_cadena.$row["telmovper"].":";
				$ls_cadena=$ls_cadena.$ls_estatus."";
				$ls_cadena=$ls_cadena."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al IPASME de Afiliados ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}		
		return $lb_valido;
	}// end function uf_gendisk_afiliado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_aporte($as_codnomdes,$as_codnomhas,$as_anocurper,$as_mescurper,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_aporte
		//         Access: public (desde la clase sigesp_snorh_r_ipasme_aporte)  
		//	    Arguments: as_codnomdes // Código de Nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de Nómina donde se termina de filtrar		  
		//	  			   as_anocurper // Año en curso
		//	  			   as_mescurper // Mes en curso
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los aportes del personal afiliado y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IPAS","XXX","C"));
		$ls_codconc_ahorro=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO AHORRO IPAS","XXXXXXXXXX","C"));
		$ls_codconc_servicio=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO SERVICIO IPAS","XXXXXXXXXX","C"));
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_anocurper."' ";
		}
		if(!empty($as_mes))
		{
			$ls_criterio = $ls_criterio." AND substr(sno_hperiodo.fecdesper,6,2)='".$as_mescurper."' ";
		}
		$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		$ls_sql="SELECT sno_personal.nacper, sno_personal.cedper, sno_ipasme_afiliado.coddep, ".
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE sno_hsalida.codconc ='".$ls_codconc_ahorro."' ".
				"   		AND (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='Q1') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as personalahorro, ".
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE sno_hsalida.codconc ='".$ls_codconc_ahorro."' ".
				"   		AND (sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='Q2') ".
				$ls_criterio.
				"			AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as patronahorro, ".
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE sno_hsalida.codconc ='".$ls_codconc_servicio."' ".
				"   		AND (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='Q1') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as personalservicio, ".
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE sno_hsalida.codconc ='".$ls_codconc_servicio."' ".
				"   		AND (sno_hsalida.tipsal='P2' OR sno_hsalida.tipsal='Q2') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as patronservicio ".
				"  FROM sno_personal, sno_ipasme_afiliado, sno_hpersonalnomina, sno_hsalida, sno_hperiodo ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_anocurper."' ".
				"   AND sno_hpersonalnomina.staper='1' ".
				"   AND substr(sno_hperiodo.fecdesper,6,2)='".$as_mescurper."' ".
				$ls_criterio.
				"   AND sno_personal.codemp = sno_ipasme_afiliado.codemp ".
				"   AND sno_personal.codper = sno_ipasme_afiliado.codper ".
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"	AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"	AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"	AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"	AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"	AND sno_hsalida.codemp = sno_hperiodo.codemp ".
				"	AND sno_hsalida.codnom = sno_hperiodo.codnom ".
				"	AND sno_hsalida.anocur = sno_hperiodo.anocur ".
				"	AND sno_hsalida.codperi  = sno_hperiodo.codperi ".
				" GROUP BY sno_personal.cedper, sno_personal.nacper,sno_ipasme_afiliado.coddep, sno_hpersonalnomina.codemp, ".
				"		   sno_hpersonalnomina.codnom, sno_hpersonalnomina.anocur, sno_hpersonalnomina.codperi,sno_hpersonalnomina.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_aporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_dia=substr($ad_fecmov,0,2);
			$ls_nombrearchivo=$as_ruta."/aportes".$ls_codorg."_".$ls_ano.$ls_mes.$ls_dia.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$row["nacper"].":";
				$ls_cadena=$ls_cadena.$row["cedper"].":";
				$ls_cadena=$ls_cadena.$ls_codorg.":";
				$ls_cadena=$ls_cadena.$row["coddep"].":";
				$ls_cadena=$ls_cadena.$ad_fecmov.":";
				$ls_cadena=$ls_cadena.round(abs($row["personalahorro"]),2).":";
				$ls_cadena=$ls_cadena.round(abs($row["patronahorro"]),2).":";
				$ls_cadena=$ls_cadena.round(abs($row["personalservicio"]),2).":";
				$ls_cadena=$ls_cadena.round(abs($row["patronservicio"]),2)."";
				$ls_cadena=$ls_cadena."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			$this->io_sql->free_result($rs_data);
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="PROCESS";
				$ls_descripcion ="Generó el Archivo al IPASME de Afiliados ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			
		}
		return $lb_valido;
	}// end function uf_gendisk_aporte
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_cobranza($as_arctxt,$as_codnomdes,$as_codnomhas,$as_anocurper,$as_mescurper,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_cobranza
		//         Access: public (desde la clase sigesp_snorh_r_ipasme_aporte)  
		//	    Arguments: as_arctxt // Archivo txt que se va a leer
		//	    		   as_codnomdes // Código de Nómina donde se empieza a filtrar
		//	  			   as_codnomhas // Código de Nómina donde se termina de filtrar		  
		//	  			   as_anocurper // Año en curso
		//	  			   as_mescurper // Mes en curso
		//	  			   ad_fecmov // Fecha de Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los aportes del personal afiliado y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IPAS","XXX","C"));
		$ls_ruta=$as_ruta."/";
		$ls_nombrearchivo=$ls_ruta.$as_arctxt;
		$lb_valido=$this->uf_abrir_archivo($ls_nombrearchivo,$lo_archivo);
		if($lb_valido)
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_dia=substr($ad_fecmov,0,2);
			$ls_nombrearchivo="a:/cobranzas".$ls_codorg."_".$ls_ano.$ls_mes.$ls_dia.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			$li_total=count($lo_archivo);
			for($li_i=0;($li_i<$li_total)&&($lb_valido);$li_i++)
			{
				$la_personal=split(":",$lo_archivo[$li_i]);			
				$ls_nacper=$la_personal[0];
				$ls_cedper=$la_personal[1];
				$ls_codorg=$la_personal[2];
				$ls_coddep=$la_personal[3];
				$ls_numcre=$la_personal[4];
				$ls_numgir=$la_personal[5];
				$ld_fechavenc=$la_personal[6];
				$li_montogiro=$la_personal[7];
				$ls_congir=$la_personal[8];
				$ls_clase=$la_personal[9];
				$ls_concepto=$la_personal[10];
				$ls_observacion=$la_personal[11];
				$ls_codper="";
				$ls_codconc="";
				$lb_valido=$this->io_personal->uf_load_codigopersonal($ls_cedper,$ls_codper);
				if($lb_valido)
				{
					switch($ls_clase)
					{
						case "01": // Hipotecario
							switch($ls_concepto)
							{
								case "01": // Hipotecario Vivienda
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO VIVIENDA IPAS","XXXXXXXXXX","C"));
									break;
	
								case "02": // Hipotecario LPH
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO LPH IPAS","XXXXXXXXXX","C"));
									break;
	
								case "03": // Hipotecario Hipoteca
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO HIPOTECA IPAS","XXXXXXXXXX","C"));
									break;
	
								case "05": // Hipotecario Construcción
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO CONSTRUCCION IPAS","XXXXXXXXXX","C"));
									break;
	
								case "06": // Hipotecario Ampliación
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO AMLIACION IPAS","XXXXXXXXXX","C"));
									break;
	
								case "07": // Hipotecario Especial
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO HIPOTECARIO ESPECIAL IPAS","XXXXXXXXXX","C"));
									break;
							}
							break;
	
						case "02": // Personal
							switch($ls_concepto)
							{
								case "08": // Personal
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO PERSONAL IPAS","XXXXXXXXXX","C"));
									break;
							}
							break;
	
						case "03": // Turistico
							switch($ls_concepto)
							{
								case "09": // Turisticos
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO TURISTICOS IPAS","XXXXXXXXXX","C"));
									break;
							}
							break;
							
						case "04": // Proveeduria
							switch($ls_concepto)
							{
								case "10": // Proveeduria
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO PROVEEDURIA IPAS","XXXXXXXXXX","C"));
									break;
							}
							break;
							
						case "05": // Asistencial
							switch($ls_concepto)
							{
								case "11": // Asistencial
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO ASISTENCIALES IPAS","XXXXXXXXXX","C"));
									break;
							}
							break;
							
						case "06": // Vehiculo
							switch($ls_concepto)
							{
								case "12": // Vehiculos
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO VEHICULOS IPAS","XXXXXXXXXX","C"));
									break;
							}
							break;
							
						case "07": // Comercial
							switch($ls_concepto)
							{
								case "13": // Comerciales
									$ls_codconc=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD CONCEPTO COMERCIALES IPAS","XXXXXXXXXX","C"));	
									break;
							}
							break;
					}
					$ls_criterio="";
					if(!empty($as_codnomdes))
					{
						$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
					}
					if(!empty($as_codnomhas))
					{
						$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
					}
					if(!empty($as_ano))
					{
						$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_anocurper."' ";
					}
					if(!empty($as_mes))
					{
						$ls_criterio = $ls_criterio." AND substr(sno_hperiodo.fecdesper,6,2)='".$as_mescurper."' ";
					}
					if(!empty($ld_fechavenc))
					{
						$ls_criterio = $ls_criterio." AND sno_hperiodo.fechasper>='".$this->io_funciones->uf_convertirdatetobd($ld_fechavenc)."' ";
					}
					$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
					$ls_sql="SELECT sno_hsalida.valsal ".
							"  FROM sno_hpersonalnomina, sno_hsalida, sno_hperiodo ".
							" WHERE sno_hsalida.codper = '".$ls_codper."' ".
							"  	AND sno_hsalida.codconc = '".$ls_codconc."' ".
							$ls_criterio.
							"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
							" 	AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
							" 	AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
							" 	AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
							" 	AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
							"   AND sno_hperiodo.codemp = sno_hsalida.codemp ".
							" 	AND sno_hperiodo.codnom = sno_hsalida.codnom ".
							" 	AND sno_hperiodo.anocur = sno_hsalida.anocur ".
							" 	AND sno_hperiodo.codperi = sno_hsalida.codperi ".
							" GROUP BY sno_hpersonalnomina.codper ";
					$rs_data=$this->io_sql->select($ls_sql);
					if($rs_data===false)
					{
						$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_cobranza ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
					}
					else
					{
						$li_ok=false;
						while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
						{
							$li_ok=true;
							$li_valsal=abs($row["valsal"]);
							$ls_cadena="";
							$ls_cadena=$ls_cadena.$ls_nacper.":";
							$ls_cadena=$ls_cadena.$ls_cedper.":";
							$ls_cadena=$ls_cadena.$ls_codorg.":";
							$ls_cadena=$ls_cadena.$ls_coddep.":";
							$ls_cadena=$ls_cadena.$ls_numcre.":";
							$ls_cadena=$ls_cadena.$ls_numgir.":";
							$ls_cadena=$ls_cadena.$ld_fechavenc.":";
							$ls_cadena=$ls_cadena.$li_valsal.":";
							$ls_cadena=$ls_cadena."01".":"; // Aplicado ó Descontado
							$ls_cadena=$ls_cadena.$ls_clase.":";
							$ls_cadena=$ls_cadena.$ls_concepto.":";
							$ls_cadena=$ls_cadena.$ls_observacion;
							if ($ls_creararchivo)  //Chequea que el archivo este abierto				
							{
								if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
								{
									$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
									$lb_valido = false;
								}
							}
							else
							{
								$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
								$lb_valido = false;
							}
						}
						$this->io_sql->free_result($rs_data);
						if($li_ok==false)
						{
							$ls_cadena="";
							$ls_cadena=$ls_cadena.$ls_nacper.":";
							$ls_cadena=$ls_cadena.$ls_cedper.":";
							$ls_cadena=$ls_cadena.$ls_codorg.":";
							$ls_cadena=$ls_cadena.$ls_coddep.":";
							$ls_cadena=$ls_cadena.$ls_numcre.":";
							$ls_cadena=$ls_cadena.$ls_numgir.":";
							$ls_cadena=$ls_cadena.$ld_fechavenc.":";
							$ls_cadena=$ls_cadena.$li_valsal.":";
							$ls_cadena=$ls_cadena."02".":"; // Sin capacidad de pago
							$ls_cadena=$ls_cadena.$ls_clase.":";
							$ls_cadena=$ls_cadena.$ls_concepto.":";
							$ls_cadena=$ls_cadena.$ls_observacion;
							if ($ls_creararchivo)  //Chequea que el archivo este abierto				
							{
								if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
								{
									$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
									$lb_valido = false;
								}
							}
							else
							{
								$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
								$lb_valido = false;
							}
						}
					}
				}
			}	
		}
		return $lb_valido;
	}// end function uf_gendisk_cobranza
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_abrir_archivo($as_nombrearchivo,&$ao_archivo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_abrir_archivo
		//		   Access: private
		//	    Arguments: as_nombrearchivo // Ruta donde se debe abrir el archivo
		//	    		   ao_archivo // conexión del archivo que se desea abrir
		// 	      Returns: lb_valido True si se abrio el archivo ó False si no se abrio
		//	  Description: Funcion que abre un archivo de texto dada una ruta 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if (file_exists("$as_nombrearchivo"))
		{
			$ao_archivo=@file("$as_nombrearchivo");
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Importar/Exportar Datos MÉTODO->uf_abrir_archivo ERROR->el archivo no existe."); 
		}
		return $lb_valido;
	}// end function uf_abrir_archivo
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>