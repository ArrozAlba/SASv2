<?php

class sigesp_rpc_c_importarbeneficiario
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_rpc_c_importarbeneficiario()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
	}
	

	function uf_scv_select_estatus_recepcion($as_codemp,$as_codsolvia,&$ab_registro,&$as_numrecdoc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_select_estatus_recepcion
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de la solicitud de viaticos
		//  			   $ab_registro  // indica si alguna de las recepciones de documentos ha sido pasada a otro estatus
		//  			   $as_numrecdoc // numeto de la recepcion de documento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica el estatus que se encuentra la recepcion de documentos generada desde viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$as_numrecdoc=$this->io_funcion->uf_cerosizquierda($as_codsolvia,11);
		$as_numrecdoc="SCV-".$as_numrecdoc;
		//$as_numrecdoc="SCV-00000".$as_codsolvia;
		$ls_sql = "SELECT estprodoc".
		          "  FROM cxp_rd  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numrecdoc='".$as_numrecdoc."'".
				  "   AND procede='SCVSOV'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_select_estatus_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ab_registro=true;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_estprodoc=$row["estprodoc"];
				if($ls_estprodoc!="R")
				{
					$ab_registro=false;
					break;
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_scv_select_estatus_recepcion

	function uf_scv_delete_dt_rd_scg($as_codemp,$as_numrecdoc,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_rd_scg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle contable de una recepcion de documentos generada por una solicitud de 
		//                 viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_scg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SCVSOV'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_rd_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó el detalle contable de la recepcion de documento ".$as_numrecdoc." mediante el reverso de".
			                 " la solicitud de viaticos".$as_codsolvia." asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_rd_scg

	function uf_scv_delete_dt_rd_spg($as_codemp,$as_numrecdoc,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_rd_spg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle contable de una recepcion de documentos generada por una solicitud de 
		//                 viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd_spg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc ."'".
				"   AND procede_doc='SCVSOV'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_rd_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó el detalle presupuestario de la recepcion de documento ".$as_numrecdoc." mediante el reverso".
			                 " de la solicitud de viaticos".$as_codsolvia." asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_rd_spg

	function uf_scv_delete_rd($as_codemp,$as_numrecdoc,$as_codsolvia,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_rd
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numrecdoc // numero de recepcion de documentos
		//  			   $as_codsolvia // codigo de solicitud de viaticos
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina las recepciones de documentos originadas de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM cxp_rd".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numrecdoc='". $as_numrecdoc."'".
				"   AND cod_pro='----------'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó  la recepcion de documento ".$as_numrecdoc." mediante el reverso".
			                 " de la solicitud de viaticos".$as_codsolvia." asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_scv_delete_recepcion

	function uf_scv_delete_dt_scg($as_codemp,$as_codsolvia,$ls_codcom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_scg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos 
		//  			   $ls_codcom    // codigo de comprobante
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un  Booleano
		//    Description: Funcion que elimina un detalle contable de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scv_dt_scg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'".
				"   AND codcom='". $ls_codcom ."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Reversó el detalle contable de la solicitud de viaticos".$as_codsolvia.
							 " asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_scg

	function uf_scv_delete_dt_spg($as_codemp,$as_codsolvia,$ls_codcom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_delete_dt_spg
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codsolvia // codigo de solicitud de viaticos 
		//  			   $ls_codcom    // codigo de comprobante
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle presupuestario de una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM scv_dt_spg".
				" WHERE codemp='". $as_codemp ."'".
				"   AND codsolvia='". $as_codsolvia ."'".
				"   AND codcom='". $ls_codcom ."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_delete_dt_spg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Reversó el detalle presupuestario de la solicitud de viaticos".$as_codsolvia.
							" asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); 
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function uf_scv_delete_dt_spg

	function uf_scv_obtener_solicitud(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_obtener_solicitud
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las entradas de las solicitudes de viaticos en estatus de "Procesada" para luego
		//				   imprimirlos en el grid de  la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_func=  new class_funciones();
		$lb_valido=true;
		$ls_sql= "SELECT codsolvia,codmis,codrut,coduniadm,fecsolvia,".
				 "       (SELECT denmis".
				 "          FROM scv_misiones".
				 "         WHERE scv_misiones.codemp=scv_solicitudviatico.codemp".
				 "           AND scv_misiones.codmis=scv_solicitudviatico.codmis) AS denmis,".
				 "       (SELECT desrut".
				 "          FROM scv_rutas".
				 "         WHERE scv_rutas.codemp=scv_solicitudviatico.codemp".
				 "           AND scv_rutas.codrut=scv_solicitudviatico.codrut) AS desrut,".
				 "       (SELECT denuniadm".
				 "          FROM spg_unidadadministrativa".
				 "         WHERE spg_unidadadministrativa.codemp=scv_solicitudviatico.codemp".
				 "           AND spg_unidadadministrativa.coduniadm=scv_solicitudviatico.coduniadm) AS denuniadm".
				 "  FROM scv_solicitudviatico".
				 " WHERE estsolvia='P'".
				 " ORDER BY codsolvia ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revcalcularviaticos MÉTODO->uf_scv_obtener_solicitud ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_codsolvia= $row["codsolvia"];
				$ls_denmis=    $row["denmis"];
				$ls_desrut=    $row["desrut"];
				$ls_denuniadm= $row["denuniadm"];
				$ld_fecsolvia= $row["fecsolvia"];
				$ld_fecsolvia=$io_func->uf_convertirfecmostrar($ld_fecsolvia);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodsolvia".$ai_totrows." type=text id=txtcodsolvia".$ai_totrows." class=sin-borde size=12 value='".$ls_codsolvia."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenmis".$ai_totrows."    type=text id=txtdenmis".$ai_totrows."    class=sin-borde size=44 value='".$ls_denmis."'    readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesrut".$ai_totrows."    type=text id=txtdesrut".$ai_totrows."    class=sin-borde size=37 value='".$ls_desrut."'    readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtdenuniadm".$ai_totrows." type=text id=txtdenuniadm".$ai_totrows." class=sin-borde size=34 value='".$ls_denuniadm."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtfecsolvia".$ai_totrows." type=text id=txtfecsolvia".$ai_totrows." class=sin-borde size=10 value='".$ld_fecsolvia."' readonly>";
				$ao_object[$ai_totrows][6]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtcodsolvia".$ai_totrows." type=text id=txtcodsolvia".$ai_totrows." class=sin-borde size=12 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenmis".$ai_totrows."    type=text id=txtdenmis".$ai_totrows."    class=sin-borde size=44 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesrut".$ai_totrows."    type=text id=txtdesrut".$ai_totrows."    class=sin-borde size=37 readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtdenuniadm".$ai_totrows." type=text id=txtdenuniadm".$ai_totrows." class=sin-borde size=34 readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtfecsolvia".$ai_totrows." type=text id=txtfecsolvia".$ai_totrows." class=sin-borde size=10 readonly>";
				$ao_object[$ai_totrows][6]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	}// fin de la function uf_scv_obtener_solicitud

	function uf_scv_update_solivitud_viaticos($as_codemp,$as_codsolvia,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_scv_update_solivitud_viaticos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ls_codsolvia // codigo de solicitud de viaticos 
		//        		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//	  Description: Función que se encarga de poner en estado de registrada a una solicitud de viaticos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/11/2006							Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" UPDATE scv_solicitudviatico".
				"    SET monsolvia=0, ".
				"        estsolvia='R'".
				"  WHERE codemp='".$as_codemp."'".
				"    AND codsolvia='".$as_codsolvia."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->revcalcularviaticos METODO->uf_scv_update_solivitud_viaticos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion= "Reversó la solicitud de viaticos ".$as_codsolvia." Asociada a la empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_scv_update_rutas

}//end  class sigesp_scv_c_revcalcularviaticos
?>
