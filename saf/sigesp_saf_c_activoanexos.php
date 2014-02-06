<?php
class sigesp_saf_c_activoanexos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_activoanexos()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");      
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	
	}//fin de la function sigesp_saf_c_metodos()
	
	function uf_saf_select_activobanco($as_codemp,$as_codact,&$as_codban,&$as_denban,&$as_ctaban,&$as_dencta,&$as_codtipcta,&$as_dentipcta,&$as_tippag,&$as_numregpag)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activobanco
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//				   $as_codban //codigo de banco
		//				   $as_denban //denominacion del banco
		//				   $as_ctaban //codigo de cuenta bancaria
		//				   $as_dencta //denominacion de cuenta bancaria
		//				   $as_codtipcta //tipo de cuenta
		//				   $as_dentipcta //denominacion del tipo de cuenta
		//				   $as_tippag //tipo de pago
		//				   $as_numregpag //numero de registro del pago
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos del activo que se refieren al banco y la cuenta con que se pago en la 
		//				   tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT saf_activo.*, ".
				  "       (SELECT nomban ".
				  "          FROM scb_banco ".
				  "         WHERE codemp='".$as_codemp."' ".
				  "           AND scb_banco.codban=saf_activo.codban) AS nomban, ".
				  "       (SELECT dencta ".
				  "          FROM scb_ctabanco ".
				  "         WHERE codemp='".$as_codemp."' ".
				  "           AND scb_ctabanco.codban=saf_activo.codban ".
				  "           AND scb_ctabanco.ctaban=saf_activo.ctaban) AS dencta, ".
				  "       (SELECT nomtipcta ".
				  "          FROM scb_tipocuenta ".
				  "         WHERE scb_tipocuenta.codtipcta=saf_activo.codtipcta) AS dentipcta ".
				  "  FROM saf_activo  ".
				  " WHERE codemp='".$as_codemp."' ".
				  "   AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print ($this->io_sql->message);
			$this->io_msg->message("CLASE->activoanexo MÉTODO->uf_saf_select_activobanco ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codban=$row["codban"];
				$as_denban=$row["nomban"];
				$as_ctaban=$row["ctaban"];
				$as_dencta=$row["dencta"];
				$as_codtipcta=$row["codtipcta"];
				$as_dentipcta=$row["dentipcta"];
				$as_tippag=$row["tippag"];
				$as_numregpag=$row["numregpag"];
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_activobanco()

	function  uf_saf_update_activobanco($as_codemp,$as_codact,$as_codban,$as_ctaban,$as_codtipcta,$as_tippag,$as_numregpag,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_activo
		//         Access: public (sigesp_siv_d_activo)
		//     Argumentos: $as_codemp    // codigo de empresa                  
		//				   $as_codact    // codigo de activo          	     
		//			       $as_codban    // codigo del banco
		//				   $as_ctaban    // codigo de cuenta de la empresa
		//				   $as_codtipcta // tipo de cuenta
		//				   $as_tippag    // tipo de pago
		//				   $as_numregpag // numero de registro del pago
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos del activo que se refieren al banco y la cuenta en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql = "UPDATE saf_activo".
				  "   SET codban='". $as_codban ."',".
				  "       ctaban='". $as_ctaban ."',".
				  "       codtipcta='". $as_codtipcta ."',".
				  "       tippag='". $as_tippag ."',".
				  "       numregpag='". $as_numregpag ."'". 
				  " WHERE codemp =  '". $as_codemp ."'". 
				  " AND codact =  '". $as_codact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activoanexos MÉTODO->uf_saf_update_activobanco ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó los datos de banco del Activo ".$as_codact." acociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin de la function uf_saf_update_activobanco
	
	function uf_saf_select_activomantenimiento($as_codemp,$as_codact,&$as_numconman,&$as_codproman,&$as_denproman,&$ad_feciniman,&$ad_fecfinman)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activomantenimiento
		//         Access: public  
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//				   $as_numconman //numero de contrato de mantenimiento
		//				   $as_codproman //codigo del proveedor de mantenimiento
		//				   $as_denproman //denominacion del proveedor de mantenimiento
		//				   $ad_feciniman //fecha de inicio del contrato
		//				   $ad_fecfinman //fecha de cierre del contrato
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos del activo que se refieren a los datos de mantenimiento del activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT saf_activo.*,".
				  "       (SELECT nompro".
				  "          FROM rpc_proveedor".
				  "         WHERE codemp='".$as_codemp."'".
				  "           AND rpc_proveedor.cod_pro=saf_activo.codproman) AS denproman".
				  "  FROM saf_activo  ".
				  " WHERE codemp='".$as_codemp."' ".
				  "   AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activoanexo MÉTODO->uf_saf_select_activomantenimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_numconman=$row["numconman"];
				$as_codproman=$row["codproman"];
				$as_denproman=$row["denproman"];
				$ad_feciniman=$this->io_funcion->uf_formatovalidofecha($row["feciniman"]);
				$ad_fecfinman=$this->io_funcion->uf_formatovalidofecha($row["fecfinman"]);
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_activomantenimiento

	function  uf_saf_update_activomantenimiento($as_codemp,$as_codact,$as_numconman,$as_codproman,$ad_feciniman,$ad_fecfinman,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_activomantenimiento
		//         Access: public  
		//     Argumentos: $as_codemp    // codigo de empresa                  
		//				   $as_codact    // codigo de activo          	     
		//				   $as_numconman //numero de contrato de mantenimiento
		//				   $as_codproman //codigo del proveedor de mantenimiento
		//				   $ad_feciniman //fecha de inicio del contrato
		//				   $ad_fecfinman //fecha de cierre del contrato
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos del activo que se refieren al contrato de manteniento en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql = "UPDATE saf_activo".
				  "   SET numconman='". $as_numconman ."',".
				  "       codproman='". $as_codproman ."',".
				  "       feciniman='". $ad_feciniman ."',".
				  "       fecfinman='". $ad_fecfinman ."'". 
				  " WHERE codemp =  '". $as_codemp ."'". 
				  "   AND codact =  '". $as_codact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activoanexos MÉTODO->uf_saf_update_activomantenimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó los datos del contrato de mantenimiento del Activo ".$as_codact." acociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin de la function uf_saf_update_activomantenimiento
	
	function uf_saf_select_activopoliza($as_codemp,$as_codact,&$as_rifase,&$as_numpolase,&$as_percobase,&$ai_moncobase,&$ad_fecvigase)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activopoliza
		//         Access: public  
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//				   $as_rifase //R.I.F. de la aseguradora
		//				   $as_numpolase //numero de la poliza de seguro
		//				   $as_percobase //periodo de cobertura de la poliza
		//				   $ai_moncobase //monto de cobertura de la poliza
		//				   $ad_fecvigase //fecha de vigencia de la poliza
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos del activo que se refieren a la poliza de seguros
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_activo".
				  " WHERE codemp='".$as_codemp."' ".
				  "   AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activoanexo MÉTODO->uf_saf_select_activopoliza ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_rifase=$row["rifase"];
				$as_numpolase=$row["numpolase"];
				$as_percobase=$row["percobase"];
				$ai_moncobase=$row["moncobase"];
				$ad_fecvigase=$this->io_funcion->uf_formatovalidofecha($row["fecvigase"]);
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_activopoliza
	
	function  uf_saf_update_activopoliza($as_codemp,$as_codact,$as_rifase,$as_numpolase,$as_percobase,$ai_moncobase,$ad_fecvigase,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_activopoliza
		//         Access: public  
		//     Argumentos: $as_codemp    // codigo de empresa                  
		//				   $as_codact    // codigo de activo          	     
		//				   $as_rifase    //R.I.F. de la aseguradora
		//				   $as_numpolase //numero de la poliza de seguro
		//				   $as_percobase //periodo de cobertura de la poliza
		//				   $ai_moncobase //monto de cobertura de la poliza
		//				   $ad_fecvigase //fecha de vigencia de la poliza
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos del activo que se refieren a la poliza del activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql = "UPDATE saf_activo".
				  "   SET rifase='". $as_rifase ."',".
				  "       numpolase='". $as_numpolase ."',".
				  "       percobase='". $as_percobase ."',".
				  "       moncobase='". $ai_moncobase ."',".
				  "       fecvigase='". $ad_fecvigase ."'". 
				  " WHERE codemp =  '". $as_codemp ."'". 
				  "   AND codact =  '". $as_codact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activoanexos MÉTODO->uf_saf_update_activopoliza ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó los datos de la póliza del Activo ".$as_codact." acociado a la Empresa ".$as_codemp;
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
	}// fin de la function uf_saf_update_activopoliza

	function uf_saf_select_activorotulacion($as_codemp,$as_codact,&$as_codrot,&$as_denrot,&$as_codprorot,&$as_denprorot,&$ad_fecrot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activorotulacion
		//         Access: public  
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//				   $as_codrot //codigo de rotulacion
		//				   $as_denrot //denominacion de la rotulacion
		//				   $as_codprorot //codigo del proveedor del servicio de rotulacion
		//				   $as_denprorot //denominacion del proveedor del servicio de rotulacion
		//				   $ad_fecrot //fecha de la rotulacion
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos del activo que se refieren a la rotulacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT saf_activo.codrot,saf_activo.codprorot,saf_activo.fecrot,".
				  "       (SELECT denrot".
				  "          FROM saf_rotulacion".
				  "         WHERE saf_rotulacion.codrot=saf_activo.codrot) AS denrot,".
				  "       (SELECT nompro".
				  "          FROM rpc_proveedor".
				  "         WHERE codemp='".$as_codemp."'".
				  "           AND rpc_proveedor.cod_pro=saf_activo.codprorot) AS denproman".
				  "  FROM saf_activo  ".
				  " WHERE codemp='".$as_codemp."' ".
				  "   AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activoanexo MÉTODO->uf_saf_select_activorotulacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codrot=$row["codrot"];
				$as_denrot=$row["denrot"];
				$as_codprorot=$row["codprorot"];
				$as_denprorot=$row["denproman"];
				$ad_fecrot=$this->io_funcion->uf_formatovalidofecha($row["fecrot"]);
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_activorotulacion

	function  uf_saf_update_activorotulacion($as_codemp,$as_codact,$as_codrot,$as_codprorot,$ad_fecrot,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_activopoliza
		//         Access: public  
		//     Argumentos: $as_codemp    // codigo de empresa                  
		//				   $as_codact    // codigo de activo          	     
		//				   $as_codrot    //codigo de rotulacion
		//				   $as_codprorot //codigo del proveedor del servicio de rotulacion
		//				   $ad_fecrot    //fecha de la rotulacion
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos del activo que se refieren a la poliza del activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql = "UPDATE saf_activo".
				  "   SET codrot='". $as_codrot ."',".
				  "       codprorot='". $as_codprorot ."',".
				  "       fecrot='". $ad_fecrot ."'".
				  " WHERE codemp =  '". $as_codemp ."'". 
				  "   AND codact =  '". $as_codact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activoanexos MÉTODO->uf_saf_update_activopoliza ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó los datos de la rotulación del Activo ".$as_codact." acociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	    return $lb_valido;
	}// fin de la function uf_saf_update_activopoliza

}//fin de la class sigesp_saf_c_activosanexos
?>
