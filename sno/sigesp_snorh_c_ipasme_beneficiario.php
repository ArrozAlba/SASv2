<?php
class sigesp_snorh_c_ipasme_beneficiario
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $ls_codemp;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_ipasme_beneficiario()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_ipasme_beneficiario
		//		   Access: public (sigesp_snorh_d_ipasme_beneficiario)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
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
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_ipasme_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_ipasme_beneficiario)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
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
	function uf_select_ipasme_beneficiario($as_codper,$as_codben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_ipasme_beneficiario
		//		   Access: private
 		//	    Arguments: as_codper  // código de personal
 		//	    		   as_codben  // código de beneficiario
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el personal esta beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_ipasme_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codben='".$as_codben."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_select_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_load_correlativo($as_codper, &$ai_codben)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_correlativo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // código del personal
		//				   ai_codben  // código de Beneficiario
		//	      Returns: lb_valido True si lo obtuvo correctamente ó False si hubo error
		//	  Description: Funcion que busca el correlativo del último permiso  y genera el nuevo correlativo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_codben=1;
		$ls_sql="SELECT codben as codigo ".
				"  FROM sno_ipasme_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				" ORDER BY codben DESC ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_load_correlativo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_codben=intval($row["codigo"]+1);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_correlativo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ipasme_beneficiario($as_codper,$ai_codben,$as_cedben,$as_tiptraben,$as_codpare,$as_nacben,$as_prinomben,
										   $as_segnomben,$as_priapeben,$as_segapeben,$as_sexben,$as_fecnacben,$as_estcivben,
										   $as_fecfalben,$as_codban,$as_numcueben,$as_tipcueben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ipasme_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//				   ai_codben  // Código de beneficiario
		//				   as_cedben  // Cédula de Beneficiario 
		//				   as_tiptraben  // Tipo de Transacción
		//				   as_codpare  // Código de Parentesco
		//				   as_nacben  // Nacionalidad 
		//				   as_prinomben  // Primer Nombre
		//				   as_segnomben  // Segundo Nombre
		//				   as_priapeben  // Primer Apellido
		//				   as_segapeben  // Segundo Apellido
		//				   as_sexben  // sexo
		//				   as_fecnacben  // Fecha de Nacimiento
		//				   as_estcivben  // Estado civil
		//				   as_fecfalben  // Fecha de Fallecimiento
		//				   as_codban  // Código de Banco
		//				   as_numcueben  // Número de cuenta
		//				   as_tipcueben  // Tipo de cuenta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_ipasme_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_ipasme_beneficiario(codemp,codper,codben,cedben,tiptraben,codpare,nacben,prinomben,segnomben,".
				"priapeben,segapeben,sexben,fecnacben,estcivben,fecfalben,codban,numcueben,tipcueben)VALUES('".$this->ls_codemp."',".
				"'".$as_codper."',".$ai_codben.",'".$as_cedben."','".$as_tiptraben."','".$as_codpare."','".$as_nacben."',".
				"'".$as_prinomben."','".$as_segnomben."','".$as_priapeben."','".$as_segapeben."','".$as_sexben."','".$as_fecnacben."',".
				"'".$as_estcivben."','".$as_fecfalben."','".$as_codban."','".$as_numcueben."','".$as_tipcueben."')";
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_insert_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Beneficiario Ipasme Afiliado ".$as_codper." Beneficiario ".$ai_codben;
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
        		$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_insert_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_ipasme_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ipasme_beneficiario($as_codper,$ai_codben,$as_cedben,$as_tiptraben,$as_codpare,$as_nacben,$as_prinomben,
										   $as_segnomben,$as_priapeben,$as_segapeben,$as_sexben,$as_fecnacben,$as_estcivben,
										   $as_fecfalben,$as_codban,$as_numcueben,$as_tipcueben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//    	 Function: uf_update_ipasme_beneficiario
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//				   ai_codben  // Código de beneficiario
		//				   as_cedben  // Cédula de Beneficiario 
		//				   as_tiptraben  // Tipo de Transacción
		//				   as_codpare  // Código de Parentesco
		//				   as_nacben  // Nacionalidad 
		//				   as_prinomben  // Primer Nombre
		//				   as_segnomben  // Segundo Nombre
		//				   as_priapeben  // Primer Apellido
		//				   as_segapeben  // Segundo Apellido
		//				   as_sexben  // sexo
		//				   as_fecnacben  // Fecha de Nacimiento
		//				   as_estcivben  // Estado civil
		//				   as_fecfalben  // Fecha de Fallecimiento
		//				   as_codban  // Código de Banco
		//				   as_numcueben  // Número de cuenta
		//				   as_tipcueben  // Tipo de cuenta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_ipasme_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_ipasme_beneficiario ".
				"   SET tiptraben='".$as_tiptraben."', ".
				"   	codpare='".$as_codpare."', ".
				"   	nacben='".$as_nacben."', ".
				"   	prinomben='".$as_prinomben."', ".
				"   	segnomben='".$as_segnomben."', ".
				"   	priapeben='".$as_priapeben."', ".
				"   	segapeben='".$as_segapeben."', ".
				"   	sexben='".$as_sexben."', ".
				"   	fecnacben='".$as_fecnacben."', ".
				"   	estcivben='".$as_estcivben."', ".
				"   	fecfalben='".$as_fecfalben."', ".
				"   	codban='".$as_codban."', ".
				"   	numcueben='".$as_numcueben."', ".
				"   	tipcueben='".$as_tipcueben."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codben=".$ai_codben."".
				"   AND cedben='".$as_cedben."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_update_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Beneficiario ".$as_codper;
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
        		$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_update_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_ipasme_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$ai_codben,$as_cedben,$as_tiptraben,$as_codpare,$as_nacben,$as_prinomben,$as_segnomben,
						$as_priapeben,$as_segapeben,$as_sexben,$as_fecnacben,$as_estcivben,$as_fecfalben,$as_codban,$as_numcueben,
						$as_tipcueben,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_ipasme_beneficiario)
		//	    Arguments: as_codper  // código de personal
		//				   ai_codben  // Código de beneficiario
		//				   as_cedben  // Cédula de Beneficiario 
		//				   as_tiptraben  // Tipo de Transacción
		//				   as_codpare  // Código de Parentesco
		//				   as_nacben  // Nacionalidad 
		//				   as_prinomben  // Primer Nombre
		//				   as_segnomben  // Segundo Nombre
		//				   as_priapeben  // Primer Apellido
		//				   as_segapeben  // Segundo Apellido
		//				   as_sexben  // sexo
		//				   as_fecnacben  // Fecha de Nacimiento
		//				   as_estcivben  // Estado civil
		//				   as_fecfalben  // Fecha de Fallecimiento
		//				   as_codban  // Código de Banco
		//				   as_numcueben  // Número de cuenta
		//				   as_tipcueben  // Tipo de cuenta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_ipasme_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$as_fecnacben=$this->io_funciones->uf_convertirdatetobd($as_fecnacben);
		$as_fecfalben=$this->io_funciones->uf_convertirdatetobd($as_fecfalben);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_ipasme_beneficiario($as_codper,$ai_codben)===false)
				{
					$lb_valido=$this->uf_load_correlativo($as_codper,$ai_codben);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_ipasme_beneficiario($as_codper,$ai_codben,$as_cedben,$as_tiptraben,$as_codpare,
																		$as_nacben,$as_prinomben,$as_segnomben,$as_priapeben,
																		$as_segapeben,$as_sexben,$as_fecnacben,$as_estcivben,
																		$as_fecfalben,$as_codban,$as_numcueben,$as_tipcueben,
																		$aa_seguridad);
					}
				}
				else
				{
					$this->io_mensajes->message("El Beneficiario ya existe, no la puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_ipasme_beneficiario($as_codper,$ai_codben)))
				{
					$lb_valido=$this->uf_update_ipasme_beneficiario($as_codper,$ai_codben,$as_cedben,$as_tiptraben,$as_codpare,
																	$as_nacben,$as_prinomben,$as_segnomben,$as_priapeben,
										   							$as_segapeben,$as_sexben,$as_fecnacben,$as_estcivben,
										   							$as_fecfalben,$as_codban,$as_numcueben,$as_tipcueben,
																	$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("el Beneficiario no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_ipasme_beneficiario($as_codper,$as_codben,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_ipasme_beneficiario
		//		   Access: public (sigesp_snorh_d_ipasme_beneficiario)
		//	    Arguments: as_codper  // código de personal
		//				   as_codben  // Código de Beneficiario
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina de la tabla sno_ipasme_beneficiario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 19/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sno_ipasme_beneficiario ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codben='".$as_codben."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_delete_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Beneficiario ".$as_codper;
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
				$this->io_mensajes->message("CLASE->Ipasme Beneficiario MÉTODO->uf_delete_ipasme_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_ipasme_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_gendisk_beneficiario($as_codperdes,$as_codperhas,$as_tiptra,$ad_fecmov,$as_ruta,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_gendisk_beneficiario
		//         Access: public (desde la clase sigesp_snorh_rpp_ipasme_afiliado)  
		//	    Arguments: as_codperdes // Código de personal donde se empieza a filtrar
		//	  			   as_codperhas // Código de personal donde se termina de filtrar		  
		//	  			   as_tiptra // Tipo de Transacción  
		//	  			   ad_fecmov // Fecha del Movimiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los beneficarios al ipasme y lo exporta a un disco
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codorg=trim($this->io_sno->uf_select_config("SNO","NOMINA","COD ORGANISMO IPAS","XXX","C"));
		$ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND sno_ipasme_beneficiario.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_beneficiario.codper<='".$as_codperhas."'";
		}
		if(!empty($as_tiptra))
		{
			$ls_criterio= $ls_criterio." AND sno_ipasme_beneficiario.tiptraben='".$as_tiptra."'";
		}
		$ls_sql="SELECT sno_ipasme_beneficiario.codper, sno_ipasme_beneficiario.codben, sno_ipasme_beneficiario.cedben, ".
				"		sno_ipasme_beneficiario.tiptraben, sno_ipasme_beneficiario.codpare, sno_ipasme_beneficiario.nacben, ".
				"		sno_ipasme_beneficiario.prinomben, sno_ipasme_beneficiario.segnomben, sno_ipasme_beneficiario.priapeben, ".
				"		sno_ipasme_beneficiario.segapeben, sno_ipasme_beneficiario.sexben, sno_ipasme_beneficiario.fecnacben, ".
				"		sno_ipasme_beneficiario.estcivben, sno_ipasme_beneficiario.fecfalben, sno_ipasme_beneficiario.codban, ".
				"		sno_ipasme_beneficiario.numcueben, sno_ipasme_beneficiario.tipcueben, sno_personal.cedper ".
				"  FROM sno_ipasme_beneficiario, sno_personal ".
				" WHERE sno_ipasme_beneficiario.codemp = '".$this->ls_codemp."'".
				"   ".$ls_criterio." ".
				"   AND sno_ipasme_beneficiario.codemp = sno_personal.codemp ".
				" 	AND sno_ipasme_beneficiario.codper = sno_personal.codper ".
				" ORDER BY sno_personal.cedper, sno_ipasme_beneficiario.codben ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_gendisk_beneficiario ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_ano=substr($ad_fecmov,8,2);
			$ls_mes=substr($ad_fecmov,3,2);
			$ls_dia=substr($ad_fecmov,0,2);
			$ls_nombrearchivo=$as_ruta."/beneficiarios".$ls_codorg."_".$ls_ano.$ls_mes.$ls_dia.".txt";
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
				$ld_fecfalben=$row["fecfalben"];
				if($ld_fecfalben=="1900-01-01")
				{
					$ld_fecfalben="";
				}
				else
				{
					$ld_fecfalben=$this->io_funciones->uf_convertirfecmostrar($ld_fecfalben);
				}
				$ls_cadena="";
				$ls_cadena=$ls_cadena.$row["tiptraben"].":";
				$ls_cadena=$ls_cadena.$row["cedper"].":";
				$ls_cadena=$ls_cadena.$row["codben"].":";
				$ls_cadena=$ls_cadena.$row["codpare"].":";
				$ls_cadena=$ls_cadena.$row["cedben"].":";
				$ls_cadena=$ls_cadena.$row["nacben"].":";
				$ls_cadena=$ls_cadena.$row["prinomben"].":";
				$ls_cadena=$ls_cadena.$row["segnomben"].":";
				$ls_cadena=$ls_cadena.$row["priapeben"].":";
				$ls_cadena=$ls_cadena.$row["segapeben"].":";
				$ls_cadena=$ls_cadena.$row["sexben"].":";
				$ls_cadena=$ls_cadena.$this->io_funciones->uf_convertirfecmostrar($row["fecnacben"]).":";
				$ls_cadena=$ls_cadena.$row["estcivben"].":";
				$ls_cadena=$ls_cadena.$ld_fecfalben.":";
				$ls_cadena=$ls_cadena.$row["codban"].":";
				$ls_cadena=$ls_cadena.substr($row["numcueben"],0,20).":";
				$ls_cadena=$ls_cadena.$row["tipcueben"]."";
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
				$ls_descripcion ="Generó el Archivo al IPASME de Beneficiarios ";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}		
		return $lb_valido;
	}// end function uf_gendisk_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>