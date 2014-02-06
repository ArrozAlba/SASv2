<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones.php");

class sigesp_saf_c_activo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_activo()
	{
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		
	}//fin de la function sigesp_saf_c_metodos()
	
	function uf_saf_select_activo($as_codemp,$as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activo
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_activo  ".
				  "WHERE codemp='".$as_codemp."' ".
				  "AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_movimientos()

	function  uf_saf_insert_activo($as_codemp,$ad_fecregact,$as_codact,$as_denact,$as_maract,$as_modact,$ad_feccmpact,$ai_cosact,
								   $as_codconbie,$as_codpai,$as_codest,$as_codmun,$as_radiotipo,$as_obsact,$as_catalogo,$as_numordcom,
								   $as_codpro,$as_denpro,$ai_monord,$as_foto,$as_spgcuenta,$as_codfuefin,$as_codsitcon,$as_codconcom,
								   $ad_fecordcom,$as_numsolpag,$ad_fecemisol,$ls_estdepact,$aa_seguridad,$as_codgru,
								   $as_codsubgru,$as_codsec,$as_codite, $as_clasif)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_activo
		//         Access: public (sigesp_siv_d_activo)
		//     Argumentos: $as_codemp    // codigo de empresa                 $as_codmun    // codigo de municipio
		//				   $as_codact    // codigo de activo          	      $as_radiotipo // tipo de bien
		//			       $as_denact    // denominacion del activo           $as_obsact    // observaciones
		//				   $ad_fecregact // fecha de registro del activo	  $as_catalogo  // codigo del catalogo SIGECOF
		//				   $as_maract    // marca del activo  				  $as_numordcom // numero de la orden de compra
		//				   $as_modact    // modelo del activo			      $as_codpro    // codigo de proveedor
		//				   $ad_feccmpact // fecha de compra del activo	      $as_denpro    // denominacion del proveedor
		//				   $ai_cosact    // costo del activo   				  $ai_monord    // monto de la orden de compra
		//				   $as_codconbie // codigo de condicion del bien      $as_foto      // foto del activo
		//				   $as_codpai    // codigo de pais				  	  $as_spgcuenta // codigo de cuenta presupuestaria
		//				   $as_codest    // codigo de estado			      $as_numsolpag // numero de la solicitud de pago
		//                 $ad_fecemisol // fecha de emision de la solicitud  $aa_seguridad // arreglo de registro de seguridad
		//                 $as_codgru    // codigo del grupo                  $as_codsubgru // codigo del subgrupo
		//                 $as_codsec    //  codsec							  $as_codite    // codigo del item
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta los datos basicos de un activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();	
		if($ad_fecemisol=="")
		{
			$ad_fecemisol="1900-01-01";
		}
		if($as_codgru=="")
		{
		   $as_codgru="---";
		}
		if($as_codsubgru=="")
		{
		   $as_codsubgru="---";
		}
		if($as_codsec=="")
		{
		   $as_codsec="---";
		}
		if($as_codite=="")
		{
		   $as_codite="---";
		}
		if (empty($as_codconbie) || strlen($as_codconbie)<>2)
		   {
		     $as_codconbie = '02';
		   }
		$ls_sql = "INSERT INTO saf_activo (codemp,codact,denact,maract,modact,fecregact,feccmpact,codconbie,spg_cuenta_act,esttipinm,". 
				  "                        catalogo,costo,estdepact,obsact,fotact,codpai,codest,codmun,cod_pro,nompro,numordcom,monordcom,codfuefin,".
				  "                        numsolpag,fecemisol,codsitcon,codconcom,codgru,codsubgru,codsec,codite, tipinm)". 
				  "VALUES( '".$as_codemp."','".$as_codact."','".$as_denact."','".$as_maract."','".$as_modact."','".$ad_fecregact."',".
				  "        '".$ad_feccmpact."','".$as_codconbie."','".$as_spgcuenta."','".$as_radiotipo."','".$as_catalogo."',".$ai_cosact.",".
				  "        '".$ls_estdepact."','".$as_obsact."','".$as_foto."','".$as_codpai."','".$as_codest."','".$as_codmun."','".$as_codpro."',".
				  "        '".$as_denpro."','".$as_numordcom."',".$ai_monord.",'".$as_codfuefin."','".$as_numsolpag."','".$ad_fecemisol."',".
				  "        '".$as_codsitcon."','".$as_codconcom."','".$as_codgru."','".$as_codsubgru."','".$as_codsec."','".$as_codite."','".$as_clasif."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print ($this->io_sql->message);
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_insert_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Activo ".$as_codact." de la Empresa ".$as_codemp;
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
	}//fin de la uf_saf_insert_activos

	function  uf_saf_update_activo($as_codemp,$ad_fecregact,$as_codact,$as_denact,$as_maract,$as_modact,$ad_feccmpact,$ai_cosact,
								   $as_codconbie,$as_codpai,$as_codest,$as_codmun,$as_radiotipo,$as_obsact,$as_catalogo,$as_numordcom,
								   $as_codpro,$as_denpro,$ai_monord,$as_foto,$as_spgcuenta,$as_codfuefin,$as_codsitcon,$as_codconcom,
								   $ad_fecordcom,$as_numsolpag,$ad_fecemisol,$ls_estdepact,$aa_seguridad,$as_codgru,
								   $as_codsubgru,$as_codsec,$as_codite,  $as_clasif)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_activo
		//         Access: public (sigesp_siv_d_activo)
		//     Argumentos: $as_codemp    // codigo de empresa                 $as_codmun    // codigo de municipio
		//				   $as_codact    // codigo de activo          	      $as_radiotipo // tipo de bien
		//			       $as_denact    // denominacion del activo           $as_obsact    // observaciones
		//				   $ad_fecregact // fecha de registro del activo	  $as_catalogo  // codigo del catalogo SIGECOF
		//				   $as_maract    // marca del activo  				  $as_numordcom // numero de la orden de compra
		//				   $as_modact    // modelo del activo			      $as_codpro    // codigo de proveedor
		//				   $ad_feccmpact // fecha de compra del activo	      $as_denpro    // denominacion del proveedor
		//				   $ai_cosact    // costo del activo   				  $ai_monord    // monto de la orden de compra
		//				   $as_codconbie // codigo de condicion del bien      $as_foto      // foto del activo
		//				   $as_codpai    // codigo de pais				  	  $as_spgcuenta // codigo de cuenta presupuestaria
		//				   $as_codest    // codigo de estado			      $as_numsolpag // numero de la solicitud de pago
		//                 $ad_fecemisol // fecha de emision de la solicitud  $aa_seguridad // arreglo de registro de seguridad
		//                 $as_codgru    // codigo del grupo                  $as_codsubgru // codigo del subgrupo
		//                 $as_codsec    //  codsec							  $as_codite    // codigo del item
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos basicos de un activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 				Fecha Última Modificación : 05/06/2006 -- 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if($ad_fecemisol=="")
		{
			$ad_fecemisol="1900-01-01";
		}
		
		if($as_codgru=="")
		{
		   $as_codgru="---";
		}
		if($as_codsubgru=="")
		{
		   $as_codsubgru="---";
		}
		if($as_codsec=="")
		{
		   $as_codsec="---";
		}
		if($as_codite=="")
		{
		   $as_codite="---";
		}
				
		$ls_sql="UPDATE saf_activo".
				"   SET denact='".$as_denact."',maract='".$as_maract."',modact='".$as_modact."',fecregact='".$ad_fecregact."',".
				" 		esttipinm='".$as_radiotipo."',feccmpact='".$ad_feccmpact."',codconbie='".$as_codconbie."',". 
   				" 		spg_cuenta_act='".$as_spgcuenta."',catalogo='".$as_catalogo."',costo='".$ai_cosact ."',".
				" 		estdepact='".$ls_estdepact."',obsact='".$as_obsact."',fotact='".$as_foto."',codpai='".$as_codpai."',".
				" 		codest='".$as_codest."',codmun='".$as_codmun ."',cod_pro='".$as_codpro."',nompro='".$as_denact."',".
				" 		numordcom='".$as_numordcom."',monordcom='". $ai_monord."',codfuefin='".$as_codfuefin."',".
				"       numsolpag='".$as_numsolpag."',fecemisol='".$ad_fecemisol."',codsitcon='".$as_codsitcon."',codconcom='".$as_codconcom."',". 
				" 		codgru='".$as_codgru."',codsubgru='".$as_codsubgru."',codsec='".$as_codsec."',codite='".$as_codite."', ".
				"       tipinm='".$as_clasif."' ".
				" WHERE codemp =  '".$as_codemp ."'". 
				"   AND codact =  '".$as_codact ."'"; 
				
				
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_update_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Activo ".$as_codact." de la Empresa ".$as_codemp;
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
	}// fin de la function uf_sss_update_movimientos

	function uf_saf_delete_activo($as_codemp,$as_codact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_activo
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un determinado activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();	
		$lb_encontrado=$this->uf_saf_select_dta($as_codemp,$as_codact);
		if ($lb_encontrado)
		   {
			 $this->io_msg->message("El Activo tiene seriales asociados");
		   }
		else
		   {
			 $lb_tiene = $this->uf_saf_select_dtedificios($as_codemp,$as_codact);
			 if ($lb_tiene)
			    {
				  $this->io_msg->message("El Activo tiene Edificios asociados !!!");
				}
			 else
			    {
				  $lb_encontrado=$this->uf_saf_select_movimiento($as_codemp,$as_codact);
				  if ($lb_encontrado)
				     {
					   $this->io_msg->message("El Activo tiene movimientos asociados");
				     }
			 	  else
				     {
					   $ls_sql = "DELETE FROM saf_activo WHERE codemp= '".$as_codemp. "' AND codact = '".$as_codact."'";
					   $li_exec=$this->io_sql->execute($ls_sql);
					   if ($li_exec===false)
						  {
						    $this->io_msg->message("CLASE->activo MÉTODO->uf_saf_delete_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
							$this->io_sql->rollback();
						  }
					   else
						  {
						    $lb_valido=true;
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="DELETE";
							$ls_descripcion ="Eliminó el Activo ".$as_codact." de la Empresa ".$as_codemp;
							$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////			
							$this->io_sql->commit();
						  }
				     }
				}
		   }
		return $lb_valido;
	} //fin de uf_saf_delete_movimientos

	function uf_saf_select_dta($as_codemp,$as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_dta
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un activo tiene seriales asociados
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codemp FROM saf_dta  ".
				  "WHERE codemp='".$as_codemp."' ".
				  "AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_dta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_dta

	function uf_saf_select_dtedificios($as_codemp,$as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_dtedificios
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un activo tiene seriales asociados
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 06/01/2009 								Fecha Última Modificación : 06/01/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql  = "SELECT codemp FROM saf_edificios WHERE codemp='".$as_codemp."' AND codact='".$as_codact."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
		     $this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_dtedificios;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		     echo $this->io_sql->message;
		   }
		else
		   {
		     if ($row=$this->io_sql->fetch_row($rs_data))
			    {
				  $lb_valido = true;
				}
		   }
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_dtedificios.

	function uf_saf_select_movimiento($as_codemp,$as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_movimiento
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un activo tiene seriales asociados
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_dt_movimiento  ".
				  "WHERE codemp='".$as_codemp."' ".
				  "AND codact='".$as_codact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_movimiento

	function  uf_saf_update_depreciacion($as_codemp,$as_codact,$as_metodo,$ai_vidautil,$as_valres,$as_ctadep,$as_ctacon,
										 $as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_depreciacion
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codact    //codigo de activo
		//				   $as_metodo    // codigo del metodo de depreciacion
		//				   $ai_vidautil  // vida util del activo
		//				   $as_valres    // valor de rescate del activo
		//				   $as_ctadep    // codigo cuenta de la depreciacion
		//				   $as_ctacon    // codigo cuenta asociada al activo
		//				   $as_codestpro1 // codigo de estructura programatica nivel 1
		//				   $as_codestpro2 // codigo de estructura programatica nivel 2
		//				   $as_codestpro3 // codigo de estructura programatica nivel 3
		//				   $as_codestpro4 // codigo de estructura programatica nivel 4
		//				   $as_codestpro5 // codigo de estructura programatica nivel 5
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos de la depreciacion de un activo en la tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $as_codestpro1=str_pad($as_codestpro1,25,"0",0);
		 $as_codestpro2=str_pad($as_codestpro2,25,"0",0);
		 $as_codestpro3=str_pad($as_codestpro3,25,"0",0);
		 $as_codestpro4=str_pad($as_codestpro4,25,"0",0);
		 $as_codestpro5=str_pad($as_codestpro5,25,"0",0);
		 $ls_sql =  "UPDATE saf_activo".
		 			"   SET codmetdep='". $as_metodo ."', vidautil='". $ai_vidautil ."', cossal='". $as_valres ."', ".
					"       spg_cuenta_dep='". $as_ctadep ."', sc_cuenta='". $as_ctacon ."', codestpro1='". $as_codestpro1 ."',".
					"       codestpro2='". $as_codestpro2 ."', codestpro3='". $as_codestpro3 ."', codestpro4='". $as_codestpro4 ."',".
					"       codestpro5='". $as_codestpro5 ."', estcla='".$as_estcla."' ".
					" WHERE codemp =  '". $as_codemp ."'". 
					"   AND codact =  '". $as_codact ."'";
		$this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_update_depreciacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la depreciación del Activo ".$as_codact." de la Empresa ".$as_codemp;
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
	}// fin de la function uf_saf_update_depreciacion

	function uf_saf_load_seriales($as_codemp,$as_codact,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_seriales
		//         Access: public  
		//      Argumento: $as_codemp  //codigo de empresa 
		//				   $as_codact  //codigo de activo
		//				   $ao_object  // arreglo de objetos de la grid
		//				   $ai_totrows // total de filas
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que carga los seriales asociados a un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 07/06/2006 								Fecha Última Modificación : 07/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_int = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_int = " CONCAT(c.nomper,' ',c.apeper) as nomres_per, CONCAT(d.nombene,' ',d.apebene) as nomres_ben, 
                         CONCAT(e.nomper,' ',e.apeper) as nomrespri_per, CONCAT(f.nombene,' ',f.apebene) as nomrespri_ben";
		}
		else
		{
		 $ls_sql_int = " c.nomper||' '||c.apeper as nomres_per, d.nombene||' '||d.apebene as nomres_ben, 
                         e.nomper||' '||e.apeper as nomrespri_per, f.nombene||' '||f.apebene as nomrespri_ben ";
		}
		
		$ls_sql = "SELECT a.*, b.denuniadm, ".$ls_sql_int." ".
		          "   FROM saf_dta a ".
                  " LEFT OUTER JOIN spg_unidadadministrativa b ON b.coduniadm = a.coduniadm ".
                  " LEFT OUTER JOIN sno_personal c on c.codper = a.codres ".
                  " LEFT OUTER JOIN rpc_beneficiario d on d.ced_bene = a.codres  ".
                  " LEFT OUTER JOIN sno_personal e on e.codper = a.codrespri ".
                  " LEFT OUTER JOIN rpc_beneficiario f on f.ced_bene = a.codrespri".
				  " WHERE a.codemp='".$as_codemp."'". 
				  " AND a.codact='".$as_codact."'";	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_load_seriales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nomresuso = "";
				$ls_nomrespri = "";
				$ls_codact=$row["codact"]; 
				$ls_seract=$row["seract"];
				$ls_chaact=$row["idchapa"];
				$ls_unidad=$row["coduniadm"];
				$ls_denunidad=$row["denuniadm"];
				$ls_nomrespri_per=$row["nomrespri_per"];
				$ls_nomrespri_ben=$row["nomrespri_ben"];
				$ls_nomresuso_per=$row["nomres_per"];
				$ls_nomresuso_ben=$row["nomres_ben"];
				$ls_responsable=$row["codrespri"];
				$ls_responsableuso=$row["codres"];
				$ls_observacion=$row["obsideact"];
				$ls_idactivo=$row["ideact"];
				
				if ($ls_nomrespri_per == "" && $ls_nomrespri_ben == "" )
				{
				 $ls_nomrespri = "POR DEFINIR";
				}
				elseif($ls_nomrespri_per != "") 
				{
				 $ls_nomrespri = $ls_nomrespri_per;
				}
				else
				{
				 $ls_nomrespri = $ls_nomrespri_ben;
				}
				
				if ($ls_nomresuso_per == "" && $ls_nomresuso_ben == "" )
				{
				 $ls_nomresuso = "POR DEFINIR";
				}
				elseif($ls_nomresuso_per != "")
				{
				 $ls_nomresuso = $ls_nomresuso_per;
				}
				else
				{
				 $ls_nomresuso = $ls_nomresuso_ben;
				} 
	
				$ao_object[$ai_totrows][1]="<input name=txtcodactd".$ai_totrows." type=text id=txtcodactd".$ai_totrows." class=sin-borde size=18 maxlength=15 value='".$ls_codact."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$ao_object[$ai_totrows][2]="<input name=txtseractd".$ai_totrows." type=text id=txtseractd".$ai_totrows." class=sin-borde size=22 maxlength=20 value='".$ls_seract."' onKeyPress='return keyrestrictgrid(event)' onBlur='ue_rellenarcampo(this,15)'>";
				$ao_object[$ai_totrows][3]="<input name=txtchaactd".$ai_totrows." type=text id=txtchaactd".$ai_totrows." class=sin-borde size=18 maxlength=15 value='".$ls_chaact."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,15)'>";
				$ao_object[$ai_totrows][4]="<input name=txtdenunidadd".$ai_totrows." type=text id=txtdenunidadd".$ai_totrows." class=sin-borde size=50 maxlength=50 value='".$ls_denunidad."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>".
				                           "<input name=txtunidadd".$ai_totrows." type=hidden id=txtunidadd".$ai_totrows." class=sin-borde size=18 maxlength=100 value='".$ls_unidad."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
				$ao_object[$ai_totrows][5]="<input name=txtnomrespri".$ai_totrows." type=text id=txtnomrespri".$ai_totrows." class=sin-borde size=50 maxlength=50 value='".$ls_nomrespri."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtresponsabled".$ai_totrows." type=hidden id=txtresponsabled".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_responsable."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='ue_rellenarcampo(this,10)'>";
				$ao_object[$ai_totrows][6]="<input name=txtnomres".$ai_totrows." type=text id=txtnomres".$ai_totrows." class=sin-borde size=50 maxlength=50 value='".$ls_nomresuso."' onKeyUp='javascript: ue_validarcomillas(this);'  readonly>".
				                           "<input name=txtcodres".$ai_totrows." type=hidden id=txtcodres".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_responsableuso."' readonly>";			  	   
				$ao_object[$ai_totrows][7]="<input name=txtobserd".$ai_totrows." type=text id=txtobserd".$ai_totrows." class=sin-borde size=18 maxlength=100 value='".$ls_observacion."' onKeyUp='javascript: ue_validarcomillas(this);' >";
				$ao_object[$ai_totrows][8]="<input name=txtidactivod".$ai_totrows." type=text id=txtidactivod".$ai_totrows." class=sin-borde size=18 maxlength=15 value='".$ls_idactivo."' onKeyUp='javascript: ue_validarnumero(this);'>";			
				$ao_object[$ai_totrows][9]="<a href=javascript:uf_agregarpartes(".$ai_totrows.");><img src=../shared/imagebank/tools/nuevo.gif alt='Agregar partes' width=15 height=15 border=0></a>";			
				$ao_object[$ai_totrows][10]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";			
	
				$ai_totrows=$ai_totrows + 1;			
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}// fin uf_saf_load_seriales
	function uf_saf_select_seriales($as_codemp,$as_codact,$as_idact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_seriales
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact //codigo de activo
		//				   $as_idact    // id de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_dta  ".
				  "WHERE codemp='".$as_codemp."'". 
				  " AND codact='".$as_codact."'".
				  " AND ideact='".$as_idact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_seriales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_seriales

	function uf_saf_select_unidad($as_codemp,$as_coduniadm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_unidad
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_coduniadm // codigo de unidad administrativa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una unidad administrativa en la tabla spg_unidadadministrativa
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT coduniadm".
				"  FROM saf_unidadadministrativa".
				" WHERE codemp='".$as_codemp."'". 
				"   AND coduniadm='".$as_coduniadm."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_unidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_unidad()

	function uf_saf_select_responsable($as_codemp,$as_codres)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_responsable
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codres // codigo de personal (responsable)
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un personal en la tabla sno_personal
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sno_personal  ".
				  " WHERE codemp='".$as_codemp."'". 
				  " AND codper='".$as_codres."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_responsable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_responsable

	function  uf_saf_insert_seriales($as_codemp,$as_codact,$as_idact,$as_seract,$as_idchapa,$as_coduniadm,$as_codrespri,$as_obsideact,
									$as_estact,$as_logusr,$as_codres,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_seriales
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codact    // codigo de activo
		//				   $as_idact     // id de activo
		//				   $as_idchapa   // numero de chapa en el activo
		//				   $as_coduniadm // codigo de unidad adminisrativa
		//				   $as_codrespri // codigo de personal (responsable primario)
		//				   $as_obsideact // observaciones en el registro de seriales
		//				   $as_estact    // estado de activo
		//				   $as_logusr    // usuario que esta registrando el serial
		//				   $as_codres    // codigo de personal (responsable por uso)
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta los seriales y otros datos importantes de los activos relacionados a un activo en particular
		//					en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 12/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql = "INSERT INTO saf_dta (codemp,codact,ideact,seract,idchapa,coduniadm,codrespri,obsideact,estact,".
			          "                     codusureg,codres)".
					  " VALUES( '".$as_codemp."','".$as_codact."','".$as_idact."','".$as_seract."','".$as_idchapa."',".
					  "         '".$as_coduniadm."','".$as_codrespri."','".$as_obsideact."','".$as_estact."','".$as_logusr."',".
					  "         '".$as_codres."') ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);	
	    if($li_row===false)
	    {
		 $this->io_msg->message("CLASE->activo MÉTODO->uf_saf_insert_seriales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
		 $this->io_sql->rollback();
		}
		else
		{
		 $lb_valido=true;
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $ls_evento="INSERT";
		 $ls_descripcion ="Insertó Serial".$as_seract."con Id".$as_idact." asociado al Activo ".$as_codact." de la Empresa ".$as_codemp;
		 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										 $aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		 $this->io_sql->commit();
	   }
		return $lb_valido;
	}//fin de la uf_saf_insert_seriales

	function  uf_saf_update_seriales($as_codemp,$as_codact,$as_idact,$as_seract,$as_idchapa,$as_coduniadm,$as_codrespri,$as_obsideact,
									$as_estact,$as_codres,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_seriales
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codact    // codigo de activo
		//				   $as_idact     // id de activo
		//				   $as_idchapa   // numero de chapa en el activo
		//				   $as_coduniadm // codigo de unidad adminisrativa
		//				   $as_codrespri // codigo de personal (responsable primario)
		//				   $as_obsideact // observaciones en el registro de seriales
		//				   $as_estact    // estado de activo
		//				   $as_codres    // codigo de personal (responsable por uso)
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los seriales y otros datos importantes de los activos relacionados a un activo en 
		//				   particular en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
			 $ls_sql =  "UPDATE saf_dta ".
			 			"   SET seract='". $as_seract ."',".
			 			"       idchapa='". $as_idchapa ."',".
						"       coduniadm='". $as_coduniadm ."', ".
						"       codrespri='". $as_codrespri ."',".
						"       obsideact='". $as_obsideact ."',".
						"       codres='". $as_codres ."'".
						" WHERE codemp =  '". $as_codemp ."'". 
						"   AND codact =  '". $as_codact ."'".
						"   AND ideact =  '". $as_idact ."'";
			$this->io_sql->begin_transaction();
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_update_seriales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó Serial".$as_seract."con Id".$as_idact." asociado al Activo ".$as_codact." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
			}
		
	  return $lb_valido;
	}// fin de la function uf_saf_update_seriales

	function uf_saf_delete_seriales($as_codemp,$as_seract,$as_codact,$as_idact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_delete_seriales
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact // codigo de activo
		//				   $as_idact  // id de activo
		//				   $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un serial y otros datos de los activos relacionados a un activo en 
		//				   particular en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_encontrado=$this->uf_saf_select_dt_movimiento($as_codemp,$as_codact,$as_idact);
		if(!$lb_encontrado)
		{
			$lb_encontrado=$this->uf_saf_select_partes($as_codemp,$as_codact,$as_idact,'%%');
		}
		if(!$lb_encontrado)
		{
			$ls_sql = " DELETE FROM saf_dta".
					  " WHERE codemp= '".$as_codemp. "'".
					  " AND codact= '".$as_codact. "'".
					  " AND ideact= '".$as_idact. "'"; 
			$this->io_sql->begin_transaction();	
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_delete_seriales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó Serial".$as_seract."con Id".$as_idact." asociado al Activo ".$as_codact." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		}
		else
		{
			$this->io_msg->message("El Activo tiene movimientos y/o partes asociados");
		}
		return $lb_valido;
	} //fin de uf_saf_delete_seriales

	function uf_saf_select_dt_movimiento($as_codemp,$as_codact,$as_idact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_movimiento
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact // codigo de activo
		//				   $as_idact  // identificador del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un activo ha tenido movimientos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codact,ideact FROM saf_dt_movimiento  ".
				  " WHERE codemp='".$as_codemp."'". 
				  "   AND codact='".$as_codact."'" .
				  "   AND ideact='".$as_idact."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_movimiento

	function uf_saf_select_partes($as_codemp,$as_codact,$as_idact,$as_codpar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_partes
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact // codigo de activo
		//				   $as_idact  // id de activo
		//				   $as_codpar // codigo de parte asociada al activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una parte asociada a un activo en la tabla saf_partes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_partes  ".
				  "WHERE codemp='".$as_codemp."'".
				  " AND codact='".$as_codact."'".
				  " AND ideact='".$as_idact."'".
				  " AND codpar like '".$as_codpar."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_partes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;

	}//fin de la function uf_saf_select_partes()

	function  uf_saf_insert_partes($as_codemp,$as_codact,$as_idact,$as_codpar,$as_denpar,$as_estpar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_partes
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact // codigo de activo
		//				   $as_idact  // id de activo
		//				   $as_codpar // codigo de parte
		//				   $as_denpar // denominacion de la parte
		//				   $as_estpar // estado en que se encuentra la parte
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una parte asociada a un activo en la tabla saf_partes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        $this->io_sql->begin_transaction();
		$ls_sql = "INSERT INTO saf_partes (codemp, codact, ideact, codpar, denpar, estpar, cmpmov)". 
				  " VALUES( '".$as_codemp."','".$as_codact."','".$as_idact."','".$as_codpar."', '".$as_denpar."','".$as_estpar."','000000000000000')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_insert_partes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el codigo de parte ".$as_codpar." con Id ".$as_idact." asociado al Activo ".$as_codact." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de uf_saf_insert_partes

	function  uf_saf_update_partes($as_codemp,$as_codact,$as_idact,$as_codpar,$as_denpar,$as_estpar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_partes
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact // codigo de activo
		//				   $as_idact  // id de activo
		//				   $as_codpar // codigo de parte
		//				   $as_denpar // denominacion de la parte
		//				   $as_estpar // estado en que se encuentra la parte
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza una parte asociada a un activo en la tabla saf_partes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_sql = "UPDATE saf_partes SET   denpar='". $as_denpar ."', estpar='". $as_estpar ."'".
			   " WHERE codemp =  '". $as_codemp ."'".
			   " AND codact =  '". $as_codact ."'".
			   " AND ideact =  '". $as_idact ."'".
			   " AND codpar =  '". $as_codpar ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_update_partes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el codigo de parte ".$as_codpar." con Id ".$as_idact." asociado al Activo ".$as_codact." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
	  return $lb_valido;
	}// fin de la function uf_sss_update_partes

	function uf_saf_delete_partes($as_codemp,$as_codact,$as_idact,$as_codpar,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_partes
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codact // codigo de activo
		//				   $as_idact  // id de activo
		//				   $as_codpar // codigo de parte
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina una parte asociada a un activo en la tabla saf_partes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql =  " DELETE FROM saf_partes".
				   " WHERE codemp =  '". $as_codemp ."'".
				   " AND codact =  '". $as_codact ."'".
				   " AND ideact =  '". $as_idact ."'".
				   " AND codpar =  '". $as_codpar ."'";
		$this->io_sql->begin_transaction();	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_delete_partes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el codigo de parte ".$as_codpar." con Id ".$as_idact." asociado al Activo ".$as_codact." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
		}
		return $lb_valido;
	} //fin de uf_saf_delete_partes

	function uf_saf_select_cuentaspg($as_codemp,&$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_articulo
		//         Access: public (sigesp_siv_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_cuenta //numero de cuenta presupuestaria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe una determinada cuenta presupuestaria
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 28/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_spgcuenta=substr($as_cuenta,0,3);
		if($ls_spgcuenta=='404')
		{$lb_valido=true;}
		else
		{return false;}
		$ls_sql="SELECT spg_cuenta".
				"  FROM spg_cuentas  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND spg_cuenta LIKE '".trim($as_cuenta)."%'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_cuentaspg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
				$as_cuenta=$row["spg_cuenta"];
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_siv_select_articulo

	function uf_upload($as_nomfot,$as_tipfot,$as_tamfot,$as_nomtemfot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_upload
		//		   Access: public (sigesp_snorh_d_personal)
		//	    Arguments: as_nomfot  // Nombre Foto
		//				   as_tipfot  // Tipo Foto
		//				   as_tamfot  // Tamaño Foto
		//				   as_nomtemfot  // Nombre Temporal
		//	      Returns: Retorna un booleano
		//	  Description: Funcion que sube una foto al servidor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($as_nomfot!="")
		{
			if (!((strpos($as_tipfot, "gif") || strpos($as_tipfot, "jpeg") || strpos($as_tipfot, "png")) && ($as_tamfot < 100000))) 
			{ 
				$lb_valido=false;
				$as_nomfot="";
				$this->io_msg->message("El archivo de la foto no es válido.");
			}
			else
			{ 
				if (!((move_uploaded_file($as_nomtemfot, "fotosactivos/".$as_nomfot))))
				{
					$lb_valido=false;
					$as_nomfot="";
		        	$this->io_msg->message("CLASE->articulo MÉTODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $lb_valido;	
    }
  //----------------------------------------------------------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: 
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		// Modificado por: Ing. Yozelin Barragan            
		// Fecha Creación: 01/01/2006 	 Fecha Última Modificación : 21/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
	   		    "  FROM sigesp_config ".
			    " WHERE codemp='".$as_codemp."' ".
			    "   AND codsis='SAF' ".
			    "   AND seccion='CATEGORIA' ".
			    "   AND entry='TIPO-CATEGORIA-CSG-CGR' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->articulo ->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true; 
			}
		}
		return rtrim($lb_valido);
	}// end function uf_select_config
   //----------------------------------------------------------------------------------------------------------------------------------
	
   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_codemp,$as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 				Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
				"('".$as_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$as_valor."','".$as_tipo."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->articulo ->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$this->io_sql->commit();
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------
  
   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_guardar_configuracion($as_codemp,$as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_guardar_configuracion
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/05/2007				Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe=$this->uf_select_config($as_codemp);
		if(!$lb_existe)
		{
		   $lb_valido=$this->uf_insert_config($as_codemp,$as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
		}
		else
		{
		   $this->io_msg->message("La configuracion ya existe.");  
		   $lb_valido=false;
		}
	    return  $lb_valido;
	}// end function uf_saf_guardar_configuracion	
	//-----------------------------------------------------------------------------------------------------------------------------------
  
   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_valor_config($as_codemp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_valor_config
		//		   Access: public
		//	    Arguments: 
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		// Modificado por: Ing. Yozelin Barragan            
		// Fecha Creación: 21/05/2007 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
	   		    "  FROM sigesp_config ".
			    " WHERE codemp='".$as_codemp."' ".
			    "   AND codsis='SAF' ".
			    "   AND seccion='CATEGORIA' ".
			    "   AND entry='TIPO-CATEGORIA-CSG-CGR' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo ->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_valor=trim($row["value"]);
				$lb_valido=true; 
			}
			else
			{
				$li_valor="0";
			}
		}
		return $li_valor;
	}// end function uf_select_config
   //----------------------------------------------------------------------------------------------------------------------------------
function  uf_saf_update_res_uniadm_seriales($as_codemp,$as_codact,$as_idact,$as_coduniadm,$as_codrespri,
					                             $as_codres,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_res_uniadm_seriales
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codact    // codigo de activo
		//				   $as_idact     // id de activo
		//				   $as_coduniadm // codigo de unidad adminisrativa
		//				   $as_codrespri // codigo de personal (responsable primario)
		//				   $as_codres    // codigo de personal (responsable por uso)
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los seriales y otros datos importantes de los activos relacionados a un activo en 
		//				   particular en la tabla saf_dta
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 20/02/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql =  "UPDATE saf_dta SET ".
				   "       coduniadm='". $as_coduniadm ."', ".
				   "       codrespri='". $as_codrespri ."',".
				   "       codres='". $as_codres ."'".
				   " WHERE codemp =  '". $as_codemp ."'". 
				   "   AND codact =  '". $as_codact ."'".
				   "   AND ideact =  '". $as_idact ."'";			
			$this->io_sql->begin_transaction();
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_update_res_uniadm_seriales ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó detalle de Activo ".$as_codact."con Id".$as_idact." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
			}	
		
	  return $lb_valido;
	}// fin de la function uf_saf_update_seriales
	
}//fin de la class sigesp_saf_c_activo
?>
