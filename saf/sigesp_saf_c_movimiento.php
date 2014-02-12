<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_saf_c_movimiento
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_movimiento()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("sigesp_saf_c_activo.php");
		$this->io_msg = new class_mensajes();
		$in = new sigesp_include();
		$this->con = $in->uf_conectar();
		$this->io_sql = new class_sql($this->con);
		$this->seguridad = new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->io_activo = new sigesp_saf_c_activo();
		
	}
	
	function uf_saf_select_movimiento($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $as_codcau    // codigo de causa de movimiento
		//  			   $ad_fectraact // fecha del traslado del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un movimiento en la tabla saf_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/04/2006 								Fecha Última Modificación : 08/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_movimiento".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND codcau='". $as_codcau ."'".
				" AND feccmp='". $ad_feccmp ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimento MÉTODO->uf_saf_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_movimiento
	

	function uf_saf_select_activo($as_codemp,$as_codact,&$ai_monact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activo
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo de activo
		//                 $ai_monact //monto del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene serial y monto relacionados con un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 05/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT saf_dta.codact,saf_activo.costo".
				  "  FROM saf_activo,saf_dta  ".
				  " WHERE saf_activo.codact=saf_dta.codact".
				  "   AND saf_activo.codemp='".$as_codemp."'".
				  "   AND saf_activo.codact='".$as_codact."'".
				  " GROUP BY saf_dta.codact,saf_activo.costo" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimento MÉTODO->uf_saf_select_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_monact= $row["costo"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_activo

	function  uf_saf_insert_movimento($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$as_descmp,$as_codpro,$as_cedbene,
	                                  $as_codtipdoc,$as_codusureg,$as_estpromov,$aa_seguridad,$as_codrespri,$as_codresuso,
									  $as_coduniadm,$as_ubigeo,$as_tiprespri,$as_tipresuso,$as_fecent)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_movimento
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $as_codcau //codigo de la causa de movimiemto
		//                 $ad_feccmp //fecha en que se genero el comprobante
		//                 $as_descmp //observaciones del comprobante
		//                 $as_codpro //codigo del proveedor
		//                 $as_cedbene //cedula del beneficiario
		//                 $as_codtipdoc //codigo del tipo de documento
		//                 $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//                 $as_estpromov // Estatus de procesamiento del movimiento
		//				   $aa_seguridad //arreglo de registro de seguridad
		//                 as_codrespri ---> codigo del reponsable primario del activo
		//                 as_codresuso ---> codigo del reponsable de uso del activo
		//                 as_coduniadm ---> codigo de la unidad administrativa donde se va incorporar el activo
		//                 as_ubigeo ---> codigo de la ubicacion geografica donde se incorpora el activo si es sale de la institucion
		//                 as_tiprespri ---> tipo de responsable primario (personal o beneficiario) 
		//                 as_tipresuso ---> tipo de responsable de uso (personal o beneficiario) 
		//                 as_fecent ---> fecha de entrega del activo 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de movimiento de activos en la tabla saf_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 06/04/2006 								Fecha Última Modificación : 10/07/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(empty($as_fecent))
		{
		 $as_fecent = $ad_feccmp;
		}
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$li_estcat = $this->io_activo->uf_select_valor_config($ls_codemp);
		$ls_sql = " INSERT INTO saf_movimiento (codemp,cmpmov,codcau,feccmp,descmp,cod_pro,ced_bene,codtipdoc,codusureg,estpromov, ".
	              "                             codrespri,codresuso,coduniadm,ubigeoact,tiprespri,tipresuso,fecentact,estcat) ".
				  " VALUES('".$as_codemp."','".$as_cmpmov."','".$as_codcau."','".$ad_feccmp."','".$as_descmp."','".$as_codpro."',".
				  "       '".$as_cedbene."','".$as_codtipdoc."','".$as_codusureg."',".$as_estpromov.", '".$as_codrespri."', ".
				  "       '".$as_codresuso."','".$as_coduniadm."','".$as_ubigeo."','".$as_tiprespri."','".$as_tipresuso."', ".
				  "       '".$as_fecent."',".$li_estcat.")";	  		  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_movimento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Movimiento ".$as_cmpmov." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_movimento

	function uf_saf_update_autorizacion($as_codemp,$as_cmpsal,$as_coduniadmcede,$as_codprov,$as_cedrepre,$ad_fechauto,$ad_fecent,
										$ad_fecdevo,$as_estauto,$as_concepto,$as_obser,$aa_seguridad)
	{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_saf_update_autorizacion
			//         Access: public  
			//      Argumento: $as_codemp       //código de empresa 
			//                 $ls_cmpsal      //numero de comprobante
			//                 $as_coduniadmcede  //código de la unidad cedente
			//                 $as_codprov     // código de la empresa quien recibe el bien
			//                 $as_cedrepre    // cédula del reponsable de la empresa quien recibe el bien
			//                 $ad_fechauto     // fecha de la autorización
			//                 $ad_fecent        // fecha de entrega
			//                 $ad_fecdevo       // fecha de devolución
			//                 $as_estauto       // estatus de procesamiento de la autorización 
			//                 $as_concepto      // concepto de la autorización
			//                 $as_obser         // observación
			//				   $aa_seguridad    //arreglo de registro de seguridad
			//	      Returns: Retorna un Booleano
			//    Description: Funcion que actualiza la cabecera de la autorización
			//	   Creado Por: Ing.Gloriely Fréitez
			// Fecha Creación: 30/04/2008 								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$lb_valido=true;
			$ls_sql = "UPDATE saf_autsalida SET codpro='".$as_codprov."',codrep='".$as_cedrepre."',fecent='".$ad_fecent."',fecdev='".$ad_fecdevo."',".
						" consal='".$as_concepto."',obssal='".$as_obser."'".
						" WHERE saf_autsalida.codemp='".$as_codemp ."'".
						" AND saf_autsalida.cmpsal='".$as_cmpsal."'".
						" AND saf_autsalida.estprosal='".$as_estauto."'".
						" AND saf_autsalida.fecaut='".$ad_fechauto."'";
			//print "actualizacion cabecera detalle".$ls_sql."<br>";
			$li_row = $this->io_sql->select($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_autorizacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la autorización ".$as_cmpsal." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			return $lb_valido;
		} // end  function uf_saf_update_procesarprestamo

	function  uf_saf_insert_dt_movimiento($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$as_codact,$as_ideact,
	                                      $as_desmov,$ai_monact,$as_estsoc,$as_estmov,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_movimiento
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_cmpmov     //Nº del Comprobante del Movimiento
		//                 $as_codcau     //codigo de causa de movimiento
		//                 $ad_feccmp     //fecha en que se genero el comprobante
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $as_desmov     //descripcion del comprobante
		//                 $ai_monact     //costo del activo
		//                 $as_estsoc     //estatus de compra
		//                 $as_estmov     //estatus de movimiento
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de traslado de activos en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/04/2006 								Fecha Última Modificación : 06/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$li_estcat=$this->io_activo->uf_select_valor_config($ls_codemp);
		$ls_sql = " INSERT INTO saf_dt_movimiento (codemp,cmpmov,codcau,feccmp,codact,ideact,desmov,monact,estsoc,estmov,estcat) ".
				  " VALUES('".$as_codemp."','".$as_cmpmov."','".$as_codcau."','".$ad_feccmp."','".$as_codact."','".$as_ideact."',".
				  "        '".$as_desmov."','".$ai_monact."','".$as_estsoc."','".$as_estmov."',".$li_estcat.")";		  		  
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Activo ". $as_codact ." al Traslado ".$as_cmpmov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
		}
		return $lb_valido;
	} //end function  uf_saf_insert_dt_movimiento

	function uf_saf_update_dtaincorporacion($as_codemp,$as_codact,$as_ideact,$as_estact,$ad_fecincact,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtaincorporacion
		//         Access: public  
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codact       //codigo del activo
		//                 $as_ideact       //identificación del elemento u objeto
		//                 $as_estact       //codigo del nuevo responsable
		//                 $ad_fecincact    //codigo de la nueva unidad administrativa
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus y la fecha de incorporacion de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 10/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ldt_fecdesact="1900-01-01"; // esto fue  modificado  se estaba guardando en blanco
		$ls_sql= "UPDATE saf_dta".
				 "   SET estact='".$as_estact."',".
				 "       fecincact='".$ad_fecincact."',".
				 "       fecdesact='".$ldt_fecdesact."' ".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codact='".$as_codact."'".
				 "   AND ideact='".$as_ideact."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_dtaincorporacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Incorporó el Activo ".$as_codact." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtaincorporacion
	
	function uf_saf_update_procesarincorporacion($as_codemp,$as_cmpmov,$as_codcau,$as_estpromov,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_procesarincorporacion
		//         Access: public  
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_cmpmov       //numero de comprobante
		//                 $as_codcau       //codigo de la causa de movimiento
		//                 $as_estpromov    //Estatus de procesamiento del movimiento
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus y la fecha de incorporacion de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 10/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_movimiento SET   estpromov='". $as_estpromov ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND cmpmov='" . $as_cmpmov ."'".
					" AND codcau='" . $as_codcau ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_procesarincorporacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Procesó la Incorporacion  ".$as_cmpmov." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_procesarincorporacion

	function uf_siv_load_dt_movimiento($as_codemp,$as_cmpmov,$ad_feccmp,&$ai_totrows,&$ao_object,&$ai_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_movimiento
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_feccmp    // fecha de emision del comprobante
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//  			   $ai_montot    // monto total del grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a un movimiento de activos en la tabla saf_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006							Fecha Última Modificación : 10/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT saf_dt_movimiento.*,".
				"       (SELECT denact".
				"          FROM saf_activo".
				"         WHERE saf_dt_movimiento.codact=saf_activo.codact ".
				"         GROUP BY codact,denact) AS denact ".
				"  FROM saf_dt_movimiento".
				" WHERE codemp='". $as_codemp ."' ".
				"   AND cmpmov='". $as_cmpmov ."' ".
				"   AND feccmp='". $ad_feccmp ."' ".
				" ORDER BY codact";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_siv_load_dt_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			$ai_montot=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codact= $row["codact"];
				$ls_denact= $row["denact"];
				$ls_idact=  $row["ideact"];
				$ls_desmov= $row["desmov"];
				$li_monact= $row["monact"];
				$ai_montot= $ai_montot + $li_monact;
				$li_monact=number_format($li_monact,2,",",".");

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=25 maxlength=150 value='". $ls_denact ."' readonly>".
										   "<input name=txtcodact".$ai_totrows." type=hidden id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 value='". $ls_codact ."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesmov".$ai_totrows." type=text   id=txtdesmov".$ai_totrows." class=sin-borde size=52 value='". $ls_desmov ."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." class=sin-borde size=15 value='". $li_monact ."' readonly style=text-align:right>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				
			}//while
			$ai_totrows=$ai_totrows+1;
			$ao_object[$ai_totrows][1]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=25 maxlength=150 readonly>".
									   "<input name=txtcodact".$ai_totrows." type=hidden id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 readonly>";
			$ao_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 readonly>";
			$ao_object[$ai_totrows][3]="<input name=txtdesmov".$ai_totrows." type=text   id=txtdesmov".$ai_totrows." class=sin-borde size=52 readonly>";
			$ao_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." class=sin-borde size=15 readonly style=text-align:right>";
			$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_load_dt_movimiento

	function uf_siv_load_dt_movreasignacion($as_codemp,$as_cmpmov,$ad_feccmp,&$ai_totrows,&$ao_object,&$ai_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_movreasignacion
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_feccmp    // fecha de emision del comprobante
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//  			   $ai_montot    // monto total del grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a un movimiento de activos en la tabla saf_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006							Fecha Última Modificación : 10/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT saf_dt_traslado.*, saf_dt_movimiento.desmov,saf_dt_movimiento.monact".
				"  FROM saf_dt_traslado,saf_dt_movimiento".
				" WHERE saf_dt_traslado.cmpmov=saf_dt_movimiento.cmpmov".
				"   AND saf_dt_movimiento.codemp='". $as_codemp ."'".
				"   AND saf_dt_movimiento.cmpmov='". $as_cmpmov ."'".
				"   AND saf_dt_movimiento.feccmp='". $ad_feccmp ."'".
				" ORDER BY codact";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_siv_load_dt_movreasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fectraact = $row["fectraact"];
				$ls_obstraact = $row["obstraact"];
				$ls_coduniadm = $row["coduniadm"];
				$ls_codres =    $row["codres"];
				$ls_coduniadmnew = $row["coduniadmnew"];
				$ls_codresnew = $row["codresnew"];
				$ls_codact = $row["codact"];
				$ls_idact =  $row["ideact"];
				$ls_desmov = $row["desmov"];
				$li_monto = $row["monact"];
				$ld_fectraact = $this->io_funcion->uf_convertirfecmostrar($ld_fectraact);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtfectraact".$ai_totrows." type=text id=txtfectraact".$ai_totrows." class=sin-borde size=10 maxlength=10 value='".$ld_fectraact."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodact".$ai_totrows."    type=text id=txtcodact".$ai_totrows."    class=sin-borde size=17 maxlength=15 value='".$ls_codact."' readonly><input type=hidden name=txtmonto".$ai_totrows." id=txtmonto".$ai_totrows." value='".$li_monto."'>";
				$ao_object[$ai_totrows][3]="<input name=txtidact".$ai_totrows."     type=text id=txtidact".$ai_totrows."     class=sin-borde size=17 maxlength=15 value='".$ls_idact."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtobstraact".$ai_totrows." type=text id=txtobstraact".$ai_totrows." class=sin-borde size=20 value='".$ls_obstraact."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtcoduniadm".$ai_totrows." type=text id=txtcoduniadm".$ai_totrows." class=sin-borde size=11 maxlength=10 value='".$ls_coduniadm."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtcodres".$ai_totrows."    type=text id=txtcodres".$ai_totrows."    class=sin-borde size=11 maxlength=10 value='".$ls_codres."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtcoduniadmnew".$ai_totrows." type=text id=txtcoduniadmnew".$ai_totrows." class=sin-borde size=11 maxlength=10 value='".$ls_coduniadmnew."' readonly>";
				$ao_object[$ai_totrows][8]="<input name=txtcodresnew".$ai_totrows." type=text id=txtcodresnew".$ai_totrows." class=sin-borde size=12 maxlength=10 value='".$ls_codresnew."' readonly>";
				$ao_object[$ai_totrows][9]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";

				
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_load_dt_movreasignacion

	function uf_saf_load_activos($as_codemp,&$ai_totrows,&$ao_object)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_saf_load_activos
		//           Access:  public 
		//	     Argumentos:  $as_codemp   // codigo de empresa
		//  		          $ai_totrows  // total de lineas del grid
		//  		          $ao_object   // arreglo de objetos
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que busca los activos existentes en la empresa en la tabla saf_activos y se trae el 
		//                    resultado de la busqueda
		//        Creado por: Ing. Luis Anibal Lang           
		// Fecha de Creacion: 11/04/2006							Fecha de Ultima Modificación: 11/04/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql=" SELECT saf_activo.codact,saf_activo.denact,saf_activo.costo,saf_dta.seract,saf_dta.ideact".
				"   FROM saf_activo,saf_dta ".
				"  WHERE saf_activo.codemp='". $as_codemp ."'".
				"    AND saf_activo.codact=saf_dta.codact".
				"    AND (saf_dta.estact='R' OR saf_dta.estact='D')".
				"  ORDER BY saf_dta.codact ASC"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_load_activos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codact=$row["codact"];
				$ls_denact=$row["denact"];
				$li_monact=$row["costo"];
				$ls_seract=$row["seract"];
				$ls_ideact=$row["ideact"];

				$ao_object[$ai_totrows][1]="<div align=center><img src=../shared/imagebank/tools15/aprobado.gif width=15 height=15  onClick='javascript: ue_agregar(".$ai_totrows.");'></div>";
				$ao_object[$ai_totrows][2]="<input type=text name=txtcodact".$ai_totrows." id=txtcodact".$ai_totrows." value='".$ls_codact."' class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";		
				$ao_object[$ai_totrows][3]="<input type=text name=txtseract".$ai_totrows." id=txtseract".$ai_totrows." value='".$ls_seract."' class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtdenact".$ai_totrows." id=txtdenact".$ai_totrows." value='".$ls_denact."' class=sin-borde readonly style=text-align:left  size=60 maxlength=254>";
				$ao_object[$ai_totrows][5]="<input type=text name=txtideact".$ai_totrows." id=txtideact".$ai_totrows." value='".$ls_ideact."' class=sin-borde readonly style=text-align:center  size=17 maxlength=15>";
				$ao_object[$ai_totrows][6]="<input type=text name=txtmonact".$ai_totrows." id=txtmonact".$ai_totrows." value='".number_format($li_monact,2,',','.')."' class=sin-borde readonly style=text-align:right  size=15 maxlength=20>";
			}
			if ($ai_totrows==0)
			{
				$lb_valido=false;
				$ao_object[$ai_totrows][1]="<input name=chkagregar".$ai_totrows." type=checkbox id=chkagregar".$ai_totrows." value=1 class=sin-borde >";
				$ao_object[$ai_totrows][2]="<input type=text name=txtcodact".$ai_totrows." id=txtcodact".$ai_totrows." class=sin-borde readonly style=text-align:left size=20 maxlength=10 >";		
				$ao_object[$ai_totrows][3]="<input type=text name=txtseract".$ai_totrows." id=txtseract".$ai_totrows." class=sin-borde readonly style=text-align:left   size=60 maxlength=254>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtdenact".$ai_totrows." id=txtdenact".$ai_totrows." class=sin-borde readonly style=text-align:left  size=10 maxlength=20>";
				$ao_object[$ai_totrows][5]="<input type=text name=txtideact".$ai_totrows." id=txtideact".$ai_totrows." class=sin-borde readonly style=text-align:left  size=10 maxlength=20>";
				$ao_object[$ai_totrows][6]="<input type=text name=txtmonact".$ai_totrows." id=txtmonact".$ai_totrows." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
			}
		}
		   
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_saf_load_activos

    function uf_saf_update_prestamo($as_codemp,$as_cmpres,$ad_fecenacta,$as_coduniadmcede,$as_coduniadmrece,$as_codresced,
                                $as_codreserec,$as_codper,$as_estpres,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_saf_update_prestamo
		//         Access: public  
		//      Argumento: $as_codemp       //código de empresa 
		//                 $as_cmpres      //numero de comprobante
		//                 $as_coduniadmcede  //código de la unidad cedente
		//                 $as_codresced     // código del responsable de la unidad cedente
		//                 $as_codreserec    // código del responsable de la unidad receptora
		//                 $as_coduniadmrece  // código de la unidad receptora
		//                 $ad_fecenacta      // fecha del acta de préstamo
		//                 $as_codper        // código del testigo
		//                 $as_estpres  //Estatus de procesamiento del movimiento
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el préstamo
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 25/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_prestamo SET   coduniced='".$as_coduniadmcede."',codunirec='".$as_coduniadmrece."',".
					" codresced='".$as_codresced."',codresrec='".$as_codreserec."',codtespre='".$as_codper."'".
					" WHERE codemp='".$as_codemp ."'".
					" AND cmppre='".$as_cmpres ."'".
					" AND estpropre='".$as_estpres."'".
					" AND fecpreact='".$ad_fecenacta."'";
		//print $ls_sql;
		$li_row = $this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_procesarprestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Procesó al acta de prestamo ".$as_cmpres." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_procesarprestamo

	function uf_saf_load_activos_prestamo($as_codemp,&$ai_totrows,&$ao_object)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_saf_load_activos_prestamo
		//           Access:  public 
		//	     Argumentos:  $as_codemp   // codigo de empresa
		//  		          $ai_totrows  // total de lineas del grid
		//  		          $ao_object   // arreglo de objetos
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que busca los activos existentes en la empresa en la tabla saf_activos y se trae el 
		//                    resultado de la busqueda
		//        Creado por: Ing. Luis Anibal Lang           
		// Fecha de Creacion: 11/04/2006							Fecha de Ultima Modificación: 11/04/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql=" SELECT saf_activo.codact,saf_activo.denact,saf_activo.costo,saf_dta.seract,saf_dta.ideact".
				"   FROM saf_activo,saf_dta ".
				"  WHERE saf_activo.codemp='". $as_codemp ."'".
				"    AND saf_activo.codact=saf_dta.codact".
				"    AND saf_dta.estact='I'".
				"  ORDER BY saf_dta.codact ASC"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_load_activos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codact=$row["codact"];
				$ls_denact=$row["denact"];
				$li_monact=$row["costo"];
				$ls_seract=$row["seract"];
				$ls_ideact=$row["ideact"];

				$ao_object[$ai_totrows][1]="<div align=center><img src=../shared/imagebank/tools15/aprobado.gif width=15 height=15  onClick='javascript: ue_agregar(".$ai_totrows.");'></div>";
				$ao_object[$ai_totrows][2]="<input type=text name=txtcodact".$ai_totrows." id=txtcodact".$ai_totrows." value='".$ls_codact."' class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";		
				$ao_object[$ai_totrows][3]="<input type=text name=txtseract".$ai_totrows." id=txtseract".$ai_totrows." value='".$ls_seract."' class=sin-borde readonly style=text-align:center size=17 maxlength=15>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtdenact".$ai_totrows." id=txtdenact".$ai_totrows." value='".$ls_denact."' class=sin-borde readonly style=text-align:left  size=60 maxlength=254>";
				$ao_object[$ai_totrows][5]="<input type=text name=txtideact".$ai_totrows." id=txtideact".$ai_totrows." value='".$ls_ideact."' class=sin-borde readonly style=text-align:center  size=17 maxlength=15>";
				$ao_object[$ai_totrows][6]="<input type=text name=txtmonact".$ai_totrows." id=txtmonact".$ai_totrows." value='".number_format($li_monact,2,',','.')."' class=sin-borde readonly style=text-align:right  size=15 maxlength=20>";
			}
			if ($ai_totrows==0)
			{
				$lb_valido=false;
				$ao_object[$ai_totrows][1]="<input name=chkagregar".$ai_totrows." type=checkbox id=chkagregar".$ai_totrows." value=1 class=sin-borde >";
				$ao_object[$ai_totrows][2]="<input type=text name=txtcodact".$ai_totrows." id=txtcodact".$ai_totrows." class=sin-borde readonly style=text-align:left size=20 maxlength=10 >";		
				$ao_object[$ai_totrows][3]="<input type=text name=txtseract".$ai_totrows." id=txtseract".$ai_totrows." class=sin-borde readonly style=text-align:left   size=60 maxlength=254>";
				$ao_object[$ai_totrows][4]="<input type=text name=txtdenact".$ai_totrows." id=txtdenact".$ai_totrows." class=sin-borde readonly style=text-align:left  size=10 maxlength=20>";
				$ao_object[$ai_totrows][5]="<input type=text name=txtideact".$ai_totrows." id=txtideact".$ai_totrows." class=sin-borde readonly style=text-align:left  size=10 maxlength=20>";
				$ao_object[$ai_totrows][6]="<input type=text name=txtmonact".$ai_totrows." id=txtmonact".$ai_totrows." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
			}
		}
		   
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_saf_load_activos_prestamo

	function uf_saf_select_activocuenta($as_codemp,$as_codact,$as_ideact,&$ai_monact,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activocuenta
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp   //codigo de empresa 
		//                 $as_codact   //codigo de activo
		//                 $as_ideact   //identificador del activo
		//                 $ai_monact   //monto del activo
		//                 $as_sccuenta //cuenta contable del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene el monto y la cuenta contable relacionados con un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 17/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;

		$ls_sql="SELECT saf_dta.codemp,saf_dta.codact,saf_dta.ideact,saf_dta.coduniadm,saf_activo.spg_cuenta_act,saf_activo.costo".
				"  FROM saf_dta,saf_activo".
				" WHERE saf_dta.codemp=saf_activo.codemp".
				"   AND saf_dta.codact=saf_activo.codact".
				"   AND saf_dta.codemp='".$as_codemp."'".
				"   AND saf_dta.codact='".$as_codact."'" .
				"   AND saf_dta.ideact='".$as_ideact."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_select_activocuenta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_monact= $row["costo"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_activocuenta

	function uf_saf_select_dt_cuentas($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$as_codact,$as_ideact,$as_sccuenta,$as_documento)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_dt_cuentas
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_cmpmov     //Nº del Comprobante del Movimiento
		//                 $as_codcau     //codigo de causa de movimiento
		//                 $ad_feccmp     //fecha en que se genero el comprobante
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $as_sccuenta   //descripcion del comprobante
		//                 $as_documento  //costo del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un registro contable en la tabla saf_contable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 21/04/2006 								Fecha Última Modificación : 21/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_contable".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND codcau='". $as_codcau ."'".
				" AND feccmp='". $ad_feccmp ."'".
				" AND codact='". $as_codact ."'".
				" AND ideact='". $as_ideact ."'".
				" AND sc_cuenta='". $as_sccuenta ."'".
				" AND documento='". $as_documento ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimento MÉTODO->uf_saf_select_dt_cuentas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_dt_cuentas


	function  uf_saf_insert_dt_cuentas($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$as_codact,$as_ideact,$as_sccuenta,
	                                   $as_documento,$as_debhab,$ai_monto,$as_cuentaact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_cuentas
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_cmpmov     //Nº del Comprobante del Movimiento
		//                 $as_codcau     //codigo de causa de movimiento
		//                 $ad_feccmp     //fecha en que se genero el comprobante
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $as_sccuenta   //numero de cuenta contable
		//                 $as_documento  //numero de documento relacionado a la cuenta contable
		//                 $as_debhab     //indica si la cuenta va por el debe o por el haber
		//                 $ai_monto      //monto que se le carga a la cuenta 
		//                 $as_cuentaact  //activo al que se le esta asignando el movimiento contable
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle contable de los movimientos de activos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 21/04/2006 								Fecha Última Modificación : 21/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO saf_contable (codemp,cmpmov,codcau,feccmp,codact,ideact,sc_cuenta,documento,debhab,monto,estint) ".
				  " VALUES('".$as_codemp."','".$as_cmpmov."','".$as_codcau."','".$ad_feccmp."','".$as_cuentaact."','".$as_ideact."',".
				  " '".$as_sccuenta."','".$as_documento."','".$as_debhab."','".$ai_monto."',0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_dt_cuentas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la cuenta ". $as_sccuenta ."el Activo ". $as_codact ." al Traslado ".$as_cmpmov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				
		}
		return $lb_valido;
	} //end function  uf_saf_insert_dt_cuentas

	function uf_saf_update_dtadesincorporacion($as_codemp,$as_codact,$as_ideact,$as_estact,$ad_fecdesact,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtadesincorporacion
		//         Access: public  
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codact       //codigo del activo
		//                 $as_ideact       //identificación del elemento u objeto
		//                 $as_estact       //estatus del activo
		//                 $ad_fecdesact    //fecha de desincorporacion del activo
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus y la fecha de desincorporacion de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 10/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta SET   estact='". $as_estact ."', fecdesact='". $ad_fecdesact ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_dtadesincorporacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Desincorporó el Activo ".$as_codact." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtadesincorporacion

	function uf_saf_update_dtareasignacion($as_codemp,$as_codact,$as_ideact,$as_codres,$as_coduniadm) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtareasignacion
		//         Access: public  
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codact    //codigo del activo
		//                 $as_ideact    //identificación del elemento u objeto
		//                 $as_codres    //codigo de responsable por uso
		//                 $as_coduniadm //codigo de unidad administrativa
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el responsable por uso de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 12/06/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta SET   codres='". $as_codres ."',coduniadm='".$as_coduniadm."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_dtareasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtareasignacion

	function uf_siv_load_dt_movimientocontable($as_codemp,$as_cmpmov,$ad_feccmp,$as_codcau,&$ai_totrowsscg,&$ao_objectscg,&$ai_totdeb,&$ai_tothab)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_dt_movimientocontable
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $ad_feccmp    // fecha de emision del comprobante
		//  			   $as_codcau    // codigo de causa de movimiento
		//  			   $ai_totrowsscg   // total de filas encontradas
		//  			   $ao_objectscg    // arreglo de objetos para pintar el grid
		//  			   $ai_totdeb       // monto total por el debe
		//  			   $ai_tothab       // monto toal por el haber
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles contables asociados a un movimiento de activos de la tabla saf_contable
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/04/2006							Fecha Última Modificación : 22/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM saf_contable".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND codcau='". $as_codcau ."'".
				" AND feccmp='". $ad_feccmp ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_siv_load_dt_movimientocontable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrowsscg=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_sccuenta= $row["sc_cuenta"];
				$ls_cuentaact= $row["codact"];
				$ls_cuentaide= $row["ideact"];
				$ls_docscg=   $row["documento"];
				$ls_debhab=   $row["debhab"];
				$li_montocont= $row["monto"];
				$ai_totrowsscg= $ai_totrowsscg + 1;
				$ls_descripcion= "";//$row["txtdescripcion"];
				if($ls_debhab=="D")
				{
					$ai_totdeb=$ai_totdeb+$li_montocont;
				}
				else
				{
					$ai_tothab=$ai_tothab+$li_montocont;
				}
	
	
				$ao_objectscg[$ai_totrowsscg][1] = "<input type=text name=txtcontable".$ai_totrowsscg."   id=txtcontable".$ai_totrowsscg."  class=sin-borde  value='".$ls_sccuenta."' style=text-align:center size=25 maxlength=25 readonly><input type=hidden name=txtcuentaact".$ai_totrowsscg." id=txtcuentaact".$ai_totrowsscg." value='".$ls_cuentaact."'>";		
				$ao_objectscg[$ai_totrowsscg][2] = "<input type=text name=txtdocscg".$ai_totrowsscg."     id=txtdocscg".$ai_totrowsscg."    class=sin-borde  value='".$ls_docscg."' style=text-align:center size=18 maxlength=15 readonly>";
				$ao_objectscg[$ai_totrowsscg][3] = "<input type=text name=txtdebhab".$ai_totrowsscg."     id=txtdebhab".$ai_totrowsscg."    class=sin-borde  value='".$ls_debhab."' style=text-align:center size=8 maxlength=1 readonly>"; 
				$ao_objectscg[$ai_totrowsscg][4] = "<input type=text name=txtmontocont".$ai_totrowsscg."  id=txtmontocont".$ai_totrowsscg." class=sin-borde  value='".number_format($li_montocont,2,',','.')."' style=text-align:right size=22 maxlength=22 readonly> ";
				$ao_objectscg[$ai_totrowsscg][5] = "<a href=javascript:uf_delete_scg('".$ai_totrowsscg."');><img src=../shared/imagebank/tools15/eliminar.gif alt='Eliminar detalle contable' width=15 height=15 border=0></a>";
					
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_siv_load_dt_movimientocontable

	function uf_saf_update_dtaestatus($as_codemp,$as_codact,$as_ideact,$as_estact,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtaestatus
		//         Access: public  
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codact       //codigo del activo
		//                 $as_ideact       //identificación del elemento u objeto
		//                 $as_estact       //codigo del nuevo responsable
		//                 $ad_fecdesact    //codigo de la nueva unidad administrativa
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/04/2006 								Fecha Última Modificación : 25/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta SET   estact='". $as_estact ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_dtaestatus ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			if($as_estact=="R")
			{$ls_operacion="Reasignó";}
			else
			{$ls_operacion="Modificó";}
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Desincorporó el Activo ".$as_codact." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtaestatus

	function uf_saf_select_activoreasignacion($as_codemp,$as_codact,$as_seract,$as_ideact,&$as_codres,&$as_nomres,&$as_coduniadm,&$as_denuniadm,&$ai_monact,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activo
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codact //codigo de activo
		//                 $as_seract //serial del activo
		//                 $as_ideact //identificador del activo
		//                 $as_codres //codigo de responsable del activo
		//                 $as_nomres //nombre del responsable del activo
		//                 $as_coduniadm //codigo de unidad administrativa
		//                 $as_denuniadm //denominacion de unidad administrativa
		//                 $ai_monact    //monto del activo
		//                 $as_sccuenta  //cuenta contable del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene serial, responsable y unidad administrativa relacionados con un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 05/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT saf_dta.*,saf_activo.spg_cuenta_act,saf_activo.costo,saf_unidadadministrativa.denuniadm,".
				"		sno_personal.nomper,sno_personal.apeper".
				"  FROM saf_dta,saf_activo,saf_unidadadministrativa,sno_personal".
				" WHERE saf_dta.codact=saf_activo.codact".
				"   AND saf_dta.codemp=saf_unidadadministrativa.codemp".
				"   AND saf_dta.coduniadm=saf_unidadadministrativa.coduniadm".
				"   AND saf_dta.codemp=sno_personal.codemp".
				"   AND saf_dta.codres=sno_personal.codper".
				"   AND saf_dta.codemp='".$as_codemp."'".
				"   AND saf_dta.codact='".$as_codact."'". 
				"   AND saf_dta.ideact='".$as_ideact."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_select_activoreasignacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_seract= $row["ideact"];
				$as_codres= $row["codres"];
				$as_nomres= $row["nomper"]." ".$row["apeper"];
				$as_denuniadm= $row["denuniadm"];
				$as_coduniadm= $row["coduniadm"];
				//$as_sccuenta= $row["sc_cuenta"];
				$ai_monact= $row["costo"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_activoreasignacion

	function  uf_saf_insert_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$as_obstra,$as_codusureg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_cmpmov //Nº del Comprobante del Movimiento
		//                 $ad_fectraact //fecha del traslado
		//                 $as_obstra //observaciones del cambio de responsable
		//                 $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//				   $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un maestro de traslado de activos en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/04/2006 								Fecha Última Modificación : 20/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO saf_traslado (codemp,cmpmov,fectraact,obstra,codusureg) ".
					" VALUES('".$as_codemp."','".$as_cmpmov."','".$ad_fectraact."','".$as_obstra."','".$as_codusureg."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Reasignación ".$as_cmpmov." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_traslado

	function  uf_saf_insert_dt_traslado($as_codemp,$as_cmpmov,$ad_fectraact,$as_codact,$as_ideact,$as_obstraact,$as_coduniadm,$as_codres,$as_coduniadmnew,$as_codresnew,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp     //codigo de empresa 
		//                 $as_cmpmov     //Nº del Comprobante del Movimiento
		//                 $ad_fectraact  //fecha del traslado
		//                 $as_codact     //codigo de activo
		//                 $as_ideact     //identificador del activo
		//                 $as_obstraact  //observacion del traslado
		//                 $as_coduniadm  //codigo de unidad administrativa actual
		//                 $as_codres     //codigo de responsable actual
		//                 $as_coduniadmnew //codigo de unidad administrativa nueva
		//                 $as_codresnew  //codigo de responsable nuevo
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de traslado de activos en la tabla saf_traslado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/04/2006 								Fecha Última Modificación : 20/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO saf_dt_traslado (codemp,cmpmov,fectraact,codact,ideact,obstraact,coduniadm,codres,coduniadmnew,codresnew) ".
				  " VALUES('".$as_codemp."','".$as_cmpmov."','".$ad_fectraact."','".$as_codact."','".$as_ideact."',".
				  " '".$as_obstraact."','".$as_coduniadm."','".$as_codres."','".$as_coduniadmnew."','".$as_codresnew."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_dt_traslado ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Activo ". $as_codact ." a la Reasignación ".$as_cmpmov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_dt_traslado

	function uf_saf_select_parte($as_codemp,$as_codact,$as_ideact,$as_codpar)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_parte
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp  //codigo de empresa 
		//                 $as_codact  //codigo de activo
		//                 $as_ideact  //identificador del activo
		//                 $as_cmpmov  //Nº del Comprobante del Movimiento en el que se grabo la parte
		//                 $as_codpar  //codigo de parte
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una parte de un activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación : 11/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM saf_partes".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codact='".$as_codact."'".
				  " AND ideact='".$as_ideact."'" .
				  " AND codpar='".$as_codpar."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_select_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_parte


	function  uf_saf_insert_partes($as_codemp,$as_codact,$as_ideact,$as_cmpmov,$as_codpar,$as_denpar,$ai_monto,$ai_cossal,$ai_viduti,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_traslado
		//         Access: public (sigesp_siv_p_traslado)
		//      Argumento: $as_codemp  //codigo de empresa 
		//                 $as_codact  //codigo de activo
		//                 $as_ideact  //identificador del activo
		//                 $as_cmpmov  //Nº del Comprobante del Movimiento en el que se grabo la parte
		//                 $as_codpar  //codigo de parte
		//                 $as_denpar  //denominacion de la parte
		//                 $ai_monto  //monto de la parte
		//                 $ai_cossal  //valor de rescate 
		//                 $ai_viduti  //vida util
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta una parte asociada a un activo la cual se incorporo en el proceso de Modificaciones
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación : 11/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_estpar=1;
		$ls_sql = "INSERT INTO saf_partes (codemp,codact,ideact,codpar,denpar,estpar,cmpmov,monto,cossal,vidautil) ".
				  " VALUES('".$as_codemp."','".$as_codact."','".$as_ideact."','".$as_codpar."',".
				  " '".$as_denpar."','".$ls_estpar."','".$as_cmpmov."','".$ai_monto."','".$ai_cossal."','".$ai_viduti."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_partes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la parte ". $as_codpar ." al Activo ".$as_codact." - ".$as_ideact.
								 " en el movimiento".$as_cmpmov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} //end function  uf_saf_insert_partes

	function uf_saf_update_parte($as_codemp,$as_codact,$as_ideact,$as_cmpmov,$as_codpar,$ai_monto,$ai_cossal,$ai_viduti,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_parte
		//         Access: public  
		//      Argumento: $as_codemp  //codigo de empresa 
		//                 $as_codact  //codigo de activo
		//                 $as_ideact  //identificador del activo
		//                 $as_cmpmov  //Nº del Comprobante del Movimiento en el que se grabo la parte
		//                 $as_codpar  //codigo de parte
		//                 $ai_monto   //monto de la parte
		//                 $ai_cossal  //valor de rescate 
		//                 $ai_viduti  //vida util
		//				   $aa_seguridad  //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus y la fecha de incorporacion de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación : 11/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_partes SET   cmpmov='". $as_cmpmov ."',monto='". $ai_monto ."',cossal='". $ai_cossal ."',vidautil='". $ai_viduti ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'".
					" AND codpar='" . $as_codpar ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_parte ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Pate".$as_codpar." del Activo ".$as_codact." - ".$as_ideact.
							 " asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_parte
										
	function uf_saf_update_dtamodificacion($as_codemp,$as_codact,$as_ideact,$ad_fecmodact,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_dtamodificacion
		//         Access: public  
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codact       //codigo del activo
		//                 $as_ideact       //identificación del elemento u objeto
		//                 $ad_fecmodact    //fecha de la modificacion del activo
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus y la fecha de desincorporacion de un activo en la tabla saf_dta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 10/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta SET   estact='". $as_estact ."', fecajuact='". $ad_fecdesact ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codact='" . $as_codact ."'".
					" AND ideact='" . $as_ideact ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_dtamodificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Activo ".$as_codact." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_dtamodificacion

	function uf_saf_load_dt_modificacion($as_codemp,$as_cmpmov,$as_codact,$as_ideact,&$ai_totrows,&$ao_object,&$ai_montot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_dt_modificacion
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpmov    // No de comprobante de movimiento
		//  			   $as_codact    // codigo de activo
		//  			   $as_ideact    // identificador del activo
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//  			   $ai_montot    // monto total del grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a un movimiento de activos en la tabla saf_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/04/2006							Fecha Última Modificación : 13/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM saf_partes".
				" WHERE codemp='". $as_codemp ."'".
				" AND cmpmov='". $as_cmpmov ."'".
				" AND codact='". $as_codact ."'".
				" AND ideact='". $as_ideact ."'".
				" ORDER BY codact";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_load_dt_modificacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			$ai_montot=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codpar=  $row["codpar"];
				$ls_denpar=  $row["denpar"];
				$li_monpar=  $row["monto"];
				$li_viduti=  $row["vidautil"];
				$li_cossal=  $row["cossal"];
				$ai_montot=$ai_montot + $li_monpar;
				$li_monparaux= number_format($li_monpar,2,',','.');
				$li_vidutiaux= number_format($li_viduti,2,',','.');
				$li_cossalaux= number_format($li_cossal,2,',','.');
	
				$ao_object[$ai_totrows][1]="<input name=txtcodpar".$ai_totrows." type=text id=txtcodpar".$ai_totrows." class=sin-borde size=25 maxlength=15 value='". $ls_codpar ."' readonly  style=text-align:center>";
				$ao_object[$ai_totrows][2]="<input name=txtdenpar".$ai_totrows."  type=text id=txtdenpar".$ai_totrows."  class=sin-borde size=30 maxlength=250 value='". $ls_denpar ."' readonly style=text-align:left>";
				$ao_object[$ai_totrows][3]="<input name=txtmonpar".$ai_totrows." type=text id=txtmonpar".$ai_totrows." class=sin-borde size=18 value='". $li_monparaux ."' readonly style=text-align:right>";
				$ao_object[$ai_totrows][4]="<input name=txtviduti".$ai_totrows." type=text id=txtviduti".$ai_totrows." class=sin-borde size=15 value='". $li_vidutiaux ."' readonly style=text-align:right>";
				$ao_object[$ai_totrows][5]="<input name=txtcossal".$ai_totrows." type=text id=txtcossal".$ai_totrows." class=sin-borde size=15 value='". $li_cossalaux ."' readonly style=text-align:right>";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
				
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_saf_load_dt_modificacion

	function uf_saf_select_cuentaunidad($as_codemp,$as_coduniadm,$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_cuentaunidad
		//         Access: public  
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_coduniadm //codigo de unidad administrativa
		//                 $as_sccuenta  //cuenta contable del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que exista la cuenta contable bajo la estructura presupuestaria de la unidad 
		//				   administrativa
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 17/08/2006 								Fecha Última Modificación : 17/08/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT spg_cuentas.sc_cuenta".
				"  FROM spg_unidadadministrativa,spg_cuentas".
				"  WHERE spg_cuentas.codestpro1=spg_unidadadministrativa.codestpro1".
				"  AND   spg_cuentas.codestpro2=spg_unidadadministrativa.codestpro2".
				"  AND   spg_cuentas.codestpro3=spg_unidadadministrativa.codestpro3".
				"  AND   spg_cuentas.codestpro4=spg_unidadadministrativa.codestpro4".
				"  AND   spg_cuentas.codestpro5=spg_unidadadministrativa.codestpro5".
				"  AND   spg_unidadadministrativa.codemp='".$as_codemp."'".
				"  AND   spg_unidadadministrativa.coduniadm='".$as_coduniadm."'".
				"  AND   spg_cuentas.sc_cuenta='".$as_sccuenta."'".
				" GROUP BY sc_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->traslado MÉTODO->uf_saf_select_cuentaunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_cuentaunidad
//---------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_activos_contabilizado($as_codemp,$adt_fecdep,$as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activos_contabilizado
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa 
		//                 $adt_fecdep  //  fecha de depreciacion del articulo
		//                 $as_codact  //   codigo del activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si el activo esta contabilizado
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 27/02/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codact ".
                "  FROM saf_depreciacion ".
                " WHERE codemp='".$as_codemp."'".
				"   AND fecdep>='".$adt_fecdep."'".
				"   AND codact='".$as_codact."'".
				"   AND estcon='1'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_saf_c_movimiento 
			                        MÉTODO->uf_saf_select_activos_contabilizado 
									ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_activos_contabilizado
//---------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_load_activos_lote($as_codemp)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_saf_load_activos_lote
		//           Access:  public 
		//	     Argumentos:  $as_codemp   // codigo de empresa
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que busca los activos existentes en la empresa en la tabla saf_activos y se trae el 
		//                    resultado de la busqueda
		//       Creado por:  Ing. Yozelin Barragan / TSU. Victor Mendoza           
		// Fecha de Cracion:  22/06/2007		Fecha de Ultima Modificación: 	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql=" SELECT saf_activo.*,saf_dta.seract,saf_dta.ideact".
				"   FROM saf_activo,saf_dta ".
				" WHERE saf_activo.codemp='". $as_codemp ."'".
				"   AND saf_activo.codact=saf_dta.codact".
				"   AND ((saf_dta.estact='R') OR (saf_dta.estact='D'))".
				" ORDER BY saf_dta.codact ASC";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			return false;
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_load_activos_lote ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
      return $rs_data;
	}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
	function uf_saf_guardar_en_lote($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$as_descmp,$as_codpro,$as_cedbene,$as_codtipdoc,
									$as_codusureg,$as_estpromov,$as_codrespri,$as_codresuso,$as_coduniadm,$as_ubigeo,$as_tiprespri,
									$as_tipresuso,$ad_fecent,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_saf_guardar_en_lote
		//           Access:  public 
		//        Argumento: $as_codemp //codigo de empresa 
		//                   $as_cmpmov //Nº del Comprobante del Movimiento
		//                   $as_codcau //codigo de la causa de movimiemto
		//                   $ad_feccmp //fecha en que se genero el comprobante
		//                   $as_descmp //observaciones del comprobante
		//                   $as_codpro //codigo del proveedor
		//                   $as_cedbene //cedula del beneficiario
		//                   $as_codtipdoc //codigo del tipo de documento
		//                   $as_codusureg //codigo del usuario que esta haciendo el cambio de responsable
		//                   $as_estpromov // Estatus de procesamiento del movimiento
		//				     $aa_seguridad //arreglo de registro de seguridad
		//	        Returns: Retorna un Booleano
		//	    Description: Funcion que busca los activos existentes en la empresa en la tabla saf_activos y se trae el 
		//                   resultado de la busqueda
		//       Creado por: Ing. Yozelin Barragan / TSU. Victor Mendoza           
		// Fecha de Cracion: 22/06/2007		Fecha de Ultima Modificación: 17/07/2007	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$this->io_sql->begin_transaction();
		$ad_fecent   = $this->io_funcion->uf_convertirdatetobd($ad_fecent);
		$ad_feccmpbd = $this->io_funcion->uf_convertirdatetobd($ad_feccmp);
		$lb_valido=$this->uf_saf_insert_movimento($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$as_descmp,$as_codpro,$as_cedbene,
	                                  			  $as_codtipdoc,$as_codusureg,$as_estpromov,$aa_seguridad,$as_codrespri,
												  $as_codresuso,$as_coduniadm,$as_ubigeo,$as_tiprespri,$as_tipresuso,$ad_fecent);
		if($lb_valido)
		{
			$rs_data=$this->uf_saf_load_activos_lote($as_codemp);
			$conta=0;
			while($row=$this->io_sql->fetch_row($rs_data))	  
			{
				$ls_codact=$row["codact"];
				$ls_seract=$row["seract"];
				$ls_ideact=$row["ideact"];
				$ls_denact=$row["denact"];
				$ld_monact=$row["costo"];
				$ls_estsoc = 0;
				$ls_estmov = "";
				$conta = $conta+1;
				$lb_valido=$this->uf_saf_insert_dt_movimiento($as_codemp,$as_cmpmov,$as_codcau,$ad_feccmp,$ls_codact,$ls_ideact,
	                                      					  $as_descmp,$ld_monact,$ls_estsoc,$ls_estmov,$aa_seguridad);
				if($lb_valido)
				{
					$ls_estact="I";
					$lb_valido=$this->uf_saf_update_dtaincorporacion($as_codemp,$ls_codact,$ls_ideact,$ls_estact,$ad_feccmpbd,$aa_seguridad);
				}
			}//while
			if($lb_valido)
			{
				$this->io_sql->commit();
				$ls_estpromov=0;
			}
			else
			{
				$this->io_sql->rollback();
			}
	   }//if
	return $lb_valido;
	} // end uf_saf_guardar_en_lote
//---------------------------------------------------------------------------------------------------------------------------------

	function uf_saf_update_procesarprestamo($as_codemp,$as_cmpres,$as_coduniadmcede,
	                                        $as_coduniadmrece,$ad_fecenacta,$as_estpres,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_procesarincorporacion
		//         Access: public  
		//      Argumento: $as_codemp       //código de empresa 
		//                 $as_cmpres      //numero de comprobante
		//                 $as_coduniadmcede  //código de la unidad cedente
		//                 $as_coduniadmrece  // código de la unidad receptora
		//                 $ad_fecenacta      // fecha del acta de préstamo
		//                 $as_estpres  //estatus de procesamiento del préstamo
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus del préstamo en la tabla saf_prestamo
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 25/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_prestamo
		              SET estpropre='". $as_estpres ."'
					WHERE codemp = '" . $as_codemp ."'
					  AND cmppre = '" .$as_cmpres."'
					  AND coduniced = '" .$as_coduniadmcede."'
					  AND codunirec = '" .$as_coduniadmrece."'
					  AND fecpreact = '" .$ad_fecenacta."'"; 
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_procesarprestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Procesó al acta de prestamo ".$as_cmpres." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_procesarprestamo

    function uf_saf_select_prestamo($as_codemp,$as_cmpres,$ad_fecenacta,$as_coduniadmcede,$as_coduniadmrece)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_prestamo
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpres    // No de comprobante de movimiento
		//  			   $ad_fecenacta // fecha delprestamo
		//                 $as_coduniadmcede  // código de la unidad cedente
		//                 $as_coduniadmrece  // código de la unidad receptora
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un prestamo en la tabla saf_prestamo
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codemp 
		             FROM saf_prestamo
				    WHERE saf_prestamo.codemp='".$as_codemp."'
				      AND saf_prestamo.cmppre='".$as_cmpres."'
				      AND saf_prestamo.fecpreact='".$ad_fecenacta."'
				      AND saf_prestamo.coduniced='".$as_coduniadmcede."'
				      AND saf_prestamo.codunirec='".$as_coduniadmrece."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimento MÉTODO->uf_saf_select_prestamo;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_prestamo
	
	function uf_saf_insertar_prestamo($as_codemp,$as_cmpres,$ad_fecenacta,$as_codunicede,$as_codunirece,$as_codreserec,
	                                  $as_codreserec,$as_codper,$as_estpres,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insertar_prestamo
		//		   Access: public
		//	    Arguments: $as_codemp      // código de la empresa.
		//                 $as_cmpres      // código del comprobante de préstamo.
		//                 $ad_fecenacta   // fecha del comprobante.
		//                 $as_codunicede  // código de la unidad cedente.
		//                 $as_codunirece  // código de la unidad receptora.
		//                 $as_codreserec  // código del responsable de la unidad cedente
		//                 $as_codreserec  // código del reponsable de la unidad receptora 
		//                 $as_codper      // código del testigo.(personal de nómina)
		//                 $as_estpres     // estado del préstamo
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que guarda la cabecera del préstamo.
		// Modificado por: Ing.Gloriely Fréitez           
		// Fecha Creación: 22/04/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="INSERT INTO  saf_prestamo (codemp,cmppre,coduniced,codunirec,codresced,codresrec,fecpreact,codtespre,estpropre) ".
				" VALUES('".$as_codemp."','".$as_cmpres."','".$as_codunicede."','".$as_codunirece."','".$as_codreserec."','".$as_codreserec."','".$ad_fecenacta."','".$as_codper."','".$as_estpres."')" ;		
         //print $ls_sql;
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_prestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el préstamo".$as_cmpres."de la unidad cedente".$as_codunicede." a la unidad receptora".$as_codunirece." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;

	}// end function uf_saf_insertar_prestamo
	
	function uf_saf_insert_dt_prestamo($as_codemp,$as_cmpres,$ad_fecenacta,$as_coduniadmcede,$as_coduniadmrece,$as_codact,$as_idact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_dt_prestamo
		//		   Access: public
		//	    Arguments: $as_codemp      // código de la empresa.
		//                 $as_cmpres      // código del comprobante de préstamo.
		//                 $ad_fecenacta   // fecha del comprobante.
		//                 $as_coduniadmcede  // código de la unidad cedente.
		//                 $as_coduniadmrece  // código de la unidad receptora.
		//                 $as_codact         // código del articulo
		//                 $as_idact        //  identificación del activo  
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que busca los reponsables de la unidad cedente y la unidad receptora.
		// Modificado por: Ing.Gloriely Fréitez           
		// Fecha Creación: 22/04/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="INSERT INTO  saf_dt_prestamo (codemp,cmppre,coduniced,codunirec,fecpreact,codact,ideact) ".
				" VALUES('".$as_codemp."','".$as_cmpres."','".$as_coduniadmcede."','". $as_coduniadmrece."','".$ad_fecenacta."','".$as_codact."','".$as_idact."' )" ;		
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insert_dt_prestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó detalle del prestamo".$as_cmpres." ".
								 " de la unidad cedente".$as_coduniadmcede." a la unidad receptora ".$as_coduniadmrece." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_saf_insert_dt_prestamo
	
	function uf_saf_update_saf_dta($as_codemp,$as_codact,$as_coduniadmcede,$as_idact,$as_estactpre,
                               $ad_fecenacta,$as_coduniadmrece,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_saf_dta
		//         Access: public  
		//      Argumento: $as_codemp       //código de empresa 
		//                 $as_cmpres      //numero de comprobante
		//                 $as_coduniadmcede  // código de la unidad cedente
		//                 $as_coduniadmrece  // código de la unidad receptora
		//                 $ad_fecenacta      // fecha del acta de préstamo
		//                 $as_estpres  //estatus de procesamiento del préstamo
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de un activo en la tabla saf_dta
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 25/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "UPDATE saf_dta 
		              SET estactpre='". $as_estactpre ."' , codunipre='".$as_coduniadmrece."'
					WHERE codemp='" .$as_codemp."'
					  AND codact='" .$as_codact."'
					  AND ideact='" .$as_idact."'
					  AND coduniadm='" .$as_coduniadmcede."'";

		$li_row = $this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_saf_dta ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el activo $as_codact al estatus ".$as_estactpre." de la unidad $as_coduniadmrece, asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_saf_dta
	
	function uf_saf_load_detalle_prestamo($as_codemp,$as_cmpres,$ad_fecenacta,$as_coduniadmcede,$as_coduniadmrece,
	                                      &$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_detalle_prestamo
		//		   Access: public
		//	    Arguments: $as_codemp      // código de la empresa.
		//                 $as_cmpres      // código del comprobante de préstamo.
		//                 $ad_fecenacta   // fecha del comprobante.
		//                 $as_coduniadmcede  // código de la unidad cedente.
		//                 $as_coduniadmrece  // código de la unidad receptora.
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que busca el los activos cedidos.
		// Modificado por: Ing.Gloriely Fréitez           
		// Fecha Creación: 22/04/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ad_fecenacta=$this->io_funcion->uf_convertirdatetobd($ad_fecenacta); 
		$ls_sql="SELECT saf_dt_prestamo.cmppre,saf_dt_prestamo.coduniced,saf_dt_prestamo.codunirec,
		        saf_dt_prestamo.fecpreact,saf_dt_prestamo.codact,saf_dt_prestamo.ideact,saf_activo.denact,saf_activo.costo".
				"  FROM saf_dt_prestamo,saf_prestamo,saf_activo".
				" WHERE saf_dt_prestamo.codemp='".$as_codemp."'  ".
				"   AND saf_dt_prestamo.cmppre=saf_dt_prestamo.cmppre ".
				"   AND saf_prestamo.coduniced=saf_dt_prestamo.coduniced ".
				"   AND saf_prestamo.codunirec=saf_dt_prestamo.codunirec ".
				"   AND saf_dt_prestamo.cmppre='".$as_cmpres."'  ".
				"   AND saf_dt_prestamo.coduniced='".$as_coduniadmcede."'  ".
				"   AND saf_dt_prestamo.codunirec='".$as_coduniadmrece."' ".
				"   AND saf_dt_prestamo.fecpreact='".$ad_fecenacta."' ".
				"   AND saf_activo.codemp=saf_dt_prestamo.codemp".
				"   AND saf_activo.codact=saf_dt_prestamo.codact"; 
	    //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_load_detalle_prestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codact= $row["codact"];// print $ls_codact;
				$ls_denact= $row["denact"];// print $ls_denact;
				$ls_idact=  $row["ideact"]; //print $ls_idact;
				$li_monact= $row["costo"];  //print $ls_denact;
				$li_monact=number_format($li_monact,2,",",".");

				$ao_object[$ai_totrows][1]="<input name=txtcodact".$ai_totrows." type=text   id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 value='". $ls_codact ."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
		     	$ao_object[$ai_totrows][3]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=52 value='".$ls_denact."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." class=sin-borde size=15 value='".$li_monact."' readonly style=text-align:right>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido; 
	}// end function uf_saf_load_detalle_prestamo
	
	function uf_saf_select_autorizacion($as_codemp,$as_cmpsal,$ad_fechauto)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_movimiento
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_cmpsal    // No de comprobante de movimiento
		//  			   $ad_fechauto // fecha del la autorización
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una autorizacion de salida en la tabal saf_autosalida
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 29/04/2008 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM saf_autsalida".
				" WHERE saf_autsalida.codemp='". $as_codemp ."'".
				" AND saf_autsalida.cmpsal='". $as_cmpsal ."'".
				" AND saf_autsalida.fecaut='". $ad_fechauto ."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimento MÉTODO->uf_saf_select_autorizacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_saf_select_autorizacion
	
	function uf_saf_insertar_autorizacion($as_codemp,$as_cmpsal,$as_coduniadmcede,$as_codprov,$as_cedrepre,$ad_fechauto,
	                                      $ad_fecent,$ad_fecdevo,$as_estauto,$as_concepto,$as_obser,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insertar_autorizacion
		//		   Access: public
		//	    Arguments: $as_codemp      // código de la empresa.
		//                 $as_cmpsal      // código del comprobante de préstamo.
		//                 $as_codunicede  // código de la unidad cedente.
		//                 $as_codprov     // código del proveedor quien receibe el bien.
		//                 $as_cedrepre    // cédula del reponsable de la empresa.
		//                 $ad_fechauto    // fecha de la autorización de salida.
		//                 $ad_fecent      // fecha de entrega del bien 
		//                 $ad_fecdevo     // fecha de devolución
		//                 $as_estauto     // estado de la autorización
		//                 $as_concepto    // concepto de la autorización
		//                 $as_obser       // observación de la autorización
		//                 $aa_seguridad   // arreglo de registro de seguridad 
		//
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que guarda la cabecera de la autorización.
		// Modificado por: Ing.Gloriely Fréitez           
		// Fecha Creación: 29/04/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO  saf_autsalida (codemp,cmpsal,coduniadm,codpro,codrep,fecaut,fecent,fecdev,estprosal,consal,obssal) ".
				" VALUES('".$as_codemp."','".$as_cmpsal."','".$as_coduniadmcede."','".$as_codprov."','".$as_cedrepre."','".$ad_fechauto."','".$ad_fecent."','".$ad_fecdevo."','".$as_estauto."','".$as_concepto."','".$as_obser."')" ;		
       // print "cabecera".$ls_sql."<br><br>";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insertar_autorizacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la autorización de salida".$as_cmpsal."de la unidad cedente".$as_coduniadmcede." a la empresa".$as_codprov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;

	}// end function uf_saf_insertar_autorizacion
	
	function uf_saf_insertar_dt_autorizacion($as_codemp,$as_cmpsal,$as_coduniadmcede,$as_codprov,$as_cedrepre,$ad_fechauto,
	                                      $as_codact,$as_idact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insertar_dt_autorizacion
		//		   Access: public
		//	    Arguments: $as_codemp      // código de la empresa.
		//                 $as_cmpsal      // código del comprobante de préstamo.
		//                 $as_codunicede  // código de la unidad cedente.
		//                 $as_codprov     // código del proveedor quien receibe el bien.
		//                 $as_cedrepre    // cédula del reponsable de la empresa.
		//                 $ad_fechauto    // fecha de la autorización de salida.
		//                 $as_codact      // código del bien
		//                 $as_idact     // identificación del bien
		//                 $aa_seguridad   // arreglo de registro de seguridad 
		//
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que guarda el detalle de la autorización.
		// Modificado por: Ing.Gloriely Fréitez           
		// Fecha Creación: 29/04/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_sql="INSERT INTO saf_dt_autsalida (codemp,cmpsal,coduniadm,fecaut,codact,ideact) ".
				" VALUES('".$as_codemp."','".$as_cmpsal."','".$as_coduniadmcede."','".$ad_fechauto."','".$as_codact."','".$as_idact."')" ;		
        //print "detalle--->".$ls_sql;
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_insertar_dt_autorizacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el detalle de la autorización de salida".$as_cmpsal."de la unidad cedente".$as_coduniadmcede." a la empresa".$as_codprov." asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_saf_insertar_dt_autorizacion
	
	function uf_saf_load_detalle_autorizacion($as_codemp,$as_cmpsal,$as_coduniadmcede,$ad_fechauto,$as_codprov,
			                                  $as_cedrepre,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_detalle_autorizacion
		//		   Access: public
		//	    Arguments: $as_codemp     // código de la empresa.
		//                 $as_cmpsal     // código del comprobante de préstamo.
		//                 $ad_fechauto     // fecha de la autorización.
		//                 $as_coduniadmcede  // código de la unidad cedente.
		//                 $as_codprov     // código del la empresa receptora.
		//                 $as_cedrepre    // cédula del representante de la empresa quien recibe.
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que busca el los activos autorizados para su salida.
		// Modificado por: Ing.Gloriely Fréitez           
		// Fecha Creación: 29/04/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ad_fechauto=$this->io_funcion->uf_convertirdatetobd($ad_fechauto); 
		$ls_sql="SELECT saf_dt_autsalida.cmpsal,saf_dt_autsalida.coduniadm,
		        saf_dt_autsalida.fecaut,saf_dt_autsalida.codact,saf_dt_autsalida.ideact,
				(select denact from saf_activo where saf_activo.codemp=saf_dt_autsalida.codemp 
				  and saf_activo.codact=saf_dt_autsalida.codact) as denact,
				(select costo from saf_activo where saf_activo.codemp=saf_dt_autsalida.codemp 
				  and saf_activo.codact=saf_dt_autsalida.codact) as costo".
				"  FROM saf_dt_autsalida,saf_autsalida,saf_activo".
				" WHERE saf_dt_autsalida.codemp='".$as_codemp."'  ".
				"   AND saf_dt_autsalida.cmpsal=saf_autsalida.cmpsal".
				"   AND saf_dt_autsalida.coduniadm=saf_autsalida.coduniadm ".
				"   AND saf_dt_autsalida.fecaut=saf_autsalida.fecaut".
				"   AND saf_dt_autsalida.cmpsal='".$as_cmpsal."'  ".
				"   AND saf_dt_autsalida.coduniadm='".$as_coduniadmcede."'  ".
				"   AND saf_dt_autsalida.fecaut='".$ad_fechauto."' ".
		    	"   AND saf_activo.codemp='".$as_codemp."'".
				"   AND saf_activo.codact=saf_dt_autsalida.codact"; 
	    //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_load_detalle_autorizacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codact= $row["codact"];
				$ls_denact= $row["denact"];
				$ls_idact=  $row["ideact"]; 
				$li_monact= $row["costo"];  
				$li_monact=number_format($li_monact,2,",",".");

				$ao_object[$ai_totrows][1]="<input name=txtcodact".$ai_totrows." type=text   id=txtcodact".$ai_totrows." class=sin-borde size=17 maxlength=15 value='". $ls_codact ."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtidact".$ai_totrows."  type=text   id=txtidact".$ai_totrows."  class=sin-borde size=17 maxlength=15 value='". $ls_idact ."' readonly>";
		     	$ao_object[$ai_totrows][3]="<input name=txtdenact".$ai_totrows." type=text   id=txtdenact".$ai_totrows." class=sin-borde size=52 value='".$ls_denact."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtmonact".$ai_totrows." type=text   id=txtmonact".$ai_totrows." class=sin-borde size=15 value='".$li_monact."' readonly>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido; 
	}// end function uf_saf_load_detalle_autorizacion
	
	function uf_saf_update_procesarautorizacion($as_codemp,$as_cmpsal,$as_coduniadmcede,
				                                $ad_fechauto,$as_codprov,$as_estprosal,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_procesarautorizacion
		//         Access: public  
		//      Argumento: $as_codemp       //código de empresa 
		//                 $as_cmpsal      //numero de la autorización
		//                 $as_coduniadmcede  //código de la unidad cedente
		//                 $ad_fechauto     // fecha de la autorización
		//                 $as_estpres  //estatus de procesamiento del préstamo
		//                 $as_codprov   // código de la empresa quien recibe
		//                 $as_estprosal  // estatus de la autorización
		//				   $aa_seguridad    //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la autorización en la tabla saf_autsalida
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 25/04/2008 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fechauto=$this->io_funcion->uf_convertirdatetobd($ad_fechauto);
		$ls_sql = "UPDATE saf_autsalida SET  estprosal='".$as_estprosal."'".
					" WHERE saf_autsalida.codemp='" . $as_codemp ."'".
					" AND saf_autsalida.cmpsal='" .$as_cmpsal."'".
					" AND saf_autsalida.coduniadm='" .$as_coduniadmcede ."'".
					" AND saf_autsalida.codpro='" .$as_codprov."'".
					" AND saf_autsalida.fecaut='" .$ad_fechauto."'"; 
		//print $ls_sql;
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->movimiento MÉTODO->uf_saf_update_procesarprestamo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Procesó al acta de prestamo-salida ".$as_cmpsal." asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_saf_update_procesarautorizacion
}//fin de la clase 
?>