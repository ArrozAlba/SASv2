<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_siv_c_articulo
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	//-----------------------------------------------------------------------------------------------------------------------------
	function sigesp_siv_c_articulo()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad=new sigesp_c_seguridad();
		$this->io_funcion=new class_funciones();
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_catalogo(&$ai_estnum,&$ai_estcmp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_catalogo
		//         Access: public (sigesp_siv_d_articulo)
		//      Argumento: $ai_estnum //estatus que indica si la codificion es numerica o alfanumerica
		//				   $ai_estcmp // Estatus que indica si se van a agregar ceros a la izq. del codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene la configuracion del inventario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación: 08/10/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT metodo, estcatsig, estnum, estcmp".
				"  FROM siv_config".
				" WHERE id=1 ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_catalogo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_estcatsig= $row["estcatsig"];
				$ai_estnum= $row["estnum"];
				$ai_estcmp= $row["estcmp"];
				if($li_estcatsig==1)
				{$lb_valido=true;}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_siv_select_catalogo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_articulo($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_articulo
		//         Access: public (sigesp_siv_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codart //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado articulo en la tabla siv_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codart".
				"  FROM siv_articulo  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_siv_select_articulo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function  uf_siv_insert_articulo($as_codemp,$as_codart,$as_denart,$as_codtipart,$as_codunimed,$ad_feccreart,$as_obsart,
									 $ai_exiart,$ai_exiiniart,$ai_minart,$ai_maxart,$ai_prearta,$ai_preartb, 
									 $ai_preartc,$ai_preartd,$ad_fecvenart,$as_spg_cuenta,$ai_pesart,$ai_altart,$ai_ancart,
									 $ai_proart,$as_fotart,$as_codcatsig,$as_sccuenta,$aa_seguridad,$as_codmil,$as_serart,
									 $as_fabart,$as_ubiart,$as_docart,$ai_reoart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_articulo
		//         Access: public (sigesp_siv_d_articulo)
		//     Argumentos: $as_codemp     //codigo de empresa                 $as_codart    // codigo de articulo
		//				   $as_denart     // denominacion del articulo        $as_codtipart // codigo de tipo de articulo
		//			       $as_codunimed  // codigo de unidad de medida       $ad_feccreart // fecha de creacion del articulo
		//				   $as_obsart     // observacion del articulo		  $ai_exiart    // existencia del articulo
		//				   $ai_exiiniart  // existencia inicial del articulo  $ai_minart    // existencia minima del articulo
		//				   $ai_maxart     // existencia maxima del articulo   $ai_prearta   // precio A del articulo
		//				   $ai_preartb    // precio B del articulo		      $ai_preartc   // precio C del articulo
		//				   $ai_preartd    // precio D del articulo			  $ad_fecvenart // fecha de vencimiento del articulo
		//				   $as_spg_cuenta // numero de cuenta presupuestaria  $ai_pesart    // peso del articulo
		//				   $ai_altart     // altura del articulo			  $ai_ancart    // ancho del articulo
		//				   $ai_proart     // profundidad del articulo		  $as_codcatsig // codigo del catalogo sigecof
		//				   $as_sccuenta   // cuenta contable de gasto         $aa_seguridad // arreglo de registro de seguridad
		//                 $as_codmil     // codigo del catalogo milco
		//				   $as_serart     // serial del articulo			  $as_fabart    // fabricante del articulo
		//				   $as_ubiart     // ubicacion del  articulo		  $as_docart    // documento del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un articulo en la tabla de  siv_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 30/08/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($ai_exiart=="")
		{$ai_exiart=0;}
		if($ai_prearta=="")
		{$ai_prearta=0;}
		if($ai_preartb=="")
		{$ai_preartb=0;}
		if($ai_preartc=="")
		{$ai_preartc=0;}
		if($ai_preartd=="")
		{$ai_preartd=0;}
		if($ai_pesart=="")
		{$ai_pesart=0;}
		if($ai_altart=="")
		{$ai_altart=0;}
		if($ai_ancart=="")
		{$ai_ancart=0;}
		if($ai_proart=="")
		{$ai_proart=0;}
		if($ad_fecvenart=="")
		{$ad_fecvenart="1900-01-01";}
		$this->io_sql->begin_transaction();
		$ls_sql="INSERT INTO siv_articulo (codemp,codart,denart,codtipart,codunimed,feccreart,obsart,exiart,exiiniart, ".
				"                          minart,maxart,prearta,preartb,preartc,preartd,fecvenart,spg_cuenta,pesart,altart,".
				"                          ancart, proart,fotart,codcatsig,sc_cuenta,codmil,serart,ubiart,docart,fabart,reoart)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_denart."','".$as_codtipart."','".$as_codunimed."',".
				"         '".$ad_feccreart."','".$as_obsart."',".$ai_exiart.",".$ai_exiiniart.",".$ai_minart.",".$ai_maxart.",".
				"          ".$ai_prearta.",".$ai_preartb.",".$ai_preartc.",".$ai_preartd.",'".$ad_fecvenart."','".$as_spg_cuenta."',".
				"          ".$ai_pesart.",".$ai_altart.",".$ai_ancart.",".$ai_proart.",'".$as_fotart."','".$as_codcatsig."',".
				"         '".$as_sccuenta."','".$as_codmil."','".$as_serart."','".$as_ubiart."','".$as_docart."','".$as_fabart."','".$ai_reoart."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_insert_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
				$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{
					$lb_valido=true;
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_sql->rollback();
				}	
		}
		return $lb_valido;
	} // end  function  uf_siv_insert_articulo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function  uf_siv_update_articulo($as_codemp,$as_codart,$as_denart,$as_codtipart,$as_codunimed,$ad_feccreart,$as_obsart,
									 $ai_exiart,$ai_exiiniart,$ai_minart,$ai_maxart,$ai_prearta,$ai_preartb, 
									 $ai_preartc,$ai_preartd,$ad_fecvenart,$as_spg_cuenta,$ai_pesart,$ai_altart,$ai_ancart,
									 $ai_proart,$as_fotart,$as_codcatsig,$as_sccuenta,$aa_seguridad,$as_codmil,$as_serart,
									 $as_fabart,$as_ubiart,$as_docart,$ai_reoart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_articulo
		//         Access: public (sigesp_siv_d_articulo)
		//     Argumentos: $as_codemp     //codigo de empresa                 $as_codart    // codigo de articulo
		//				   $as_denart     // denominacion del articulo        $as_codtipart // codigo de tipo de articulo
		//			       $as_codunimed // codigo de unidad de medida        $ad_feccreart // fecha de creacion del articulo
		//				   $as_obsart    // observacion del articulo		  $ai_exiart    // existencia del articulo
		//				   $ai_exiiniart // existencia inicial del articulo   $ai_minart    // existencia minima del articulo
		//				   $ai_maxart    // existencia maxima del articulo    $ai_prearta   // precio A del articulo
		//				   $ai_preartb   // precio B del articulo		      $ai_preartc   // precio C del articulo
		//				   $ai_preartd   // precio D del articulo			  $ad_fecvenart // fecha de vencimiento del articulo
		//				   $as_spg_cuenta// numero de cuenta presupuestaria   $ai_pesart    // peso del articulo
		//				   $ai_altart    // altura del articulo				  $ai_ancart    // ancho del articulo
		//				   $ai_proart    // profundidad del articulo		  $as_fotart     // foto del articulo
		//                 $as_codcatsig // codgido del catalogo SIGECOF      $aa_seguridad // arreglo de registro de seguridad
		//				   $as_sccuenta  // cuenta contable de gasto          $as_codmil   // codigo del catalogo milco
		//				   $as_serart     // serial del articulo			  $as_fabart    // fabricante del articulo
		//				   $as_ubiart     // ubicacion del  articulo		  $as_docart    // documento del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que modifica un articulo en la tabla de  siv_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql="UPDATE siv_articulo".
		 		 "   SET denart='". $as_denart ."',codtipart='". $as_codtipart ."',codunimed='". $as_codunimed ."',".
				 " 		 feccreart='". $ad_feccreart ."',obsart='". $as_obsart ."',exiart='". $ai_exiart ."',".
				 " 		 exiiniart='". $ai_exiiniart ."',minart='". $ai_minart ."',maxart='". $ai_maxart ."',". 
				 " 		 prearta='". $ai_prearta ."',preartb='". $ai_preartb ."',preartc='". $ai_preartc ."', ". 
				 " 		 preartd='". $ai_preartd ."',fecvenart='". $ad_fecvenart ."',spg_cuenta='". $as_spg_cuenta ."',".
				 "		 pesart='". $ai_pesart ."',altart='". $ai_altart ."',ancart='". $ai_ancart ."',".
				 "		 proart='". $ai_proart ."',fotart='". $as_fotart ."',codcatsig='". $as_codcatsig ."',".
				 "		 sc_cuenta='". $as_sccuenta ."', codmil='".$as_codmil."',reoart='".$ai_reoart."', ".
				 "	     serart='".$as_serart."',fabart='".$as_fabart."',ubiart='".$as_ubiart."',docart='".$as_docart."'".
				 " WHERE codart='" . $as_codart ."'".
				 "   AND codemp='" . $as_codemp ."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_update_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_variable)
			{
				$lb_valido=true;
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
			}
		}
	  return $lb_valido;
	} // end function  uf_siv_update_articulo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_delete_articulo($as_codemp,$as_codart, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_articulo
		//         Access: public (sigesp_siv_d_articulo)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de articulo
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que llama a la verificacion de algun articulo en las tablas de siv_componetearticulo y
		//				   en la de siv_dt_recepcion y en caso de no encontrarse procede a su eliminacion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe= $this->uf_siv_select_componentearticulo($as_codemp,$as_codart);
		$lb_cargos= $this->uf_siv_select_dt_cargos($as_codemp,$as_codart);
		if(($lb_existe)||($lb_cargos))
		{
			$this->io_msg->message("El articulo tiene componentes y/o créditos asociados");		
			$lb_valido=false;
		}
		else
		{
			$lb_existe=$this->uf_siv_select_dt_recepcion($as_codemp,$as_codart);
			if($lb_existe)
			{
				$this->io_msg->message("El articulo tiene entradas registradas en la empresa");		
				$lb_valido=false;
			}
			else
			{
				$lb_existe=$this->uf_siv_select_dt_articulos_sep($as_codemp,$as_codart);
				if($lb_existe)
				{
					$this->io_msg->message("El articulo tiene por lo menos 1 SEP registrada en la empresa");		
					$lb_valido=false;
				}
				else
				{
					$ls_sql=" DELETE FROM siv_articulo".
							"  WHERE codemp= '".$as_codemp. "'".
							"    AND codart= '".$as_codart. "'"; 
					$this->io_sql->begin_transaction();	
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_delete_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
						$lb_valido=false;
						$this->io_sql->rollback();
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						//////////////////////////////////         SEGURIDAD               /////////////////////////////			
						if($lb_variable)
						{
							$lb_valido=true;
							$this->io_sql->commit();
						}
						else
						{
							$lb_valido=false;
							$this->io_sql->rollback();
						}
					}
				}
			}
		}
		return $lb_valido;
	} // end  function uf_siv_delete_articulo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_componentearticulo($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_componentearticulo
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo tiene o no componentes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codart".
				"  FROM siv_componente  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_componentearticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_siv_select_componentearticulo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_dt_recepcion($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dt_recepcion
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo ha tenido alguna entrada en la empresa
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codart".
				"  FROM siv_dt_recepcion ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_siv_select_dt_recepcion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_dt_articulos_sep($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dt_articulos_sep
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo se le ha realizado una sep
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codart".
				"  FROM sep_dt_articulos ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_dt_articulos_sep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_siv_select_dt_articulos_sep
	//-----------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_dt_cargos($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dt_cargos
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_codart    //codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si un articulo tiene algun cargo asociado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codart".
				"  FROM siv_cargosarticulo ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_dt_cargos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end  function uf_siv_select_dt_cargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_siv_select_cuentaspg($as_codemp,&$as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_cuentaspg
		//         Access: public (sigesp_siv_d_articulo)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_cuenta //numero de cuenta presupuestaria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe una determinada cuenta presupuestaria
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 28/03/2006 								Fecha Última Modificación : 28/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT spg_cuenta".
				"  FROM spg_cuentas  ".
				" WHERE codemp='".$as_codemp."'".
				"   AND spg_cuenta LIKE '".$as_cuenta."%'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_siv_select_cuentaspg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_cuenta=$row["spg_cuenta"];
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	}// end function uf_siv_select_cuentaspg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
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
				if (!((move_uploaded_file($as_nomtemfot, "fotosarticulos/".$as_nomfot))))
				{
					$lb_valido=false;
					$as_nomfot="";
		        	$this->io_msg->message("CLASE->articulo MÉTODO->uf_upload ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				}
			}
		}
		return $lb_valido;	
    }// end function uf_upload
	//-----------------------------------------------------------------------------------------------------------------------------
   
   //-----------------------------------------------------------------------------------------------------------------------------
	function uf_saf_insert_activo($as_codart,$as_codact,$as_denart,$ai_cosact,$as_codcatsig,$as_codgru,$as_codsubgru,$as_codsec,
								  $as_spgcuenta,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_insert_activo
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//				   as_codact  // Codigo del Activo
		//			       as_denart  // Denominacion del Articulo
		//			       ai_cosact  // Costo
		//			       as_codcatsig  // Codigo de la clasificacion SIGECOF
		//			       as_codgru  // Codigo de Grupo
		//			       as_codsubgru  // Codigo de Sub-grupo
		//			       as_codsec  // Codigo de Seccion
		//			       aa_seguridad  // Arreglo de parametros de seguridad
		//	  Description: Funcion que Inserta un Articulo como Activo Fijo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		if($as_codgru=="")
		{
			$as_codgru="---";
			$as_codsubgru="---";
			$as_codsec="---";
		}
		if($as_codcatsig=="")
		{
			$as_codcatsig="---------------";
		}
		$ls_sql="INSERT INTO saf_activo (codemp, codact, denact, fecregact, codgru, codsubgru, codsec, spg_cuenta_act, catalogo,".
				" 						 costo, numordcom,modact,maract) ".
				" VALUES ( '".$this->ls_codemp."','".$as_codact."','".$as_denart."','".date("Y-m-d")."','".$as_codgru."','".$as_codsubgru."',".
				"		   '".$as_codsec."','".$as_spgcuenta."','".$as_codcatsig."',".$ai_cosact.",'000000000000000','N/A','N/A' )";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print ($this->io_sql->message);
			$this->io_msg->message("CLASE->Articulo MÉTODO->uf_saf_insert_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Activo ".$as_codact." Asociada al Articulo ".$as_codart;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=$this->uf_saf_update_estactivo($as_codart,$as_codact);
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
	}//fin de la uf_saf_insert_activo
   //-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_saf_update_estactivo($as_codart,$as_codact) 
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_update_estactivo
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//				   as_codact  // Codigo del Activo
		//	  Description: Funcion que Actualiza el estatus que indica si se creo un Activo a partir de un articulo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="UPDATE siv_articulo".
				"   SET estact='1', codact='".$as_codact."'".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codart='".$as_codart."'" ;
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Articulo MÉTODO->uf_saf_update_estactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}// fin uf_saf_update_estactivo
	//-----------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------
	function uf_saf_select_estactivo($as_codart)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_estactivo
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//	  Description: Funcion que Actualiza el estatus que indica si se creo un Activo a partir de un articulo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT estact".
				"  FROM siv_articulo".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Articulo MÉTODO->uf_saf_select_estactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estact=$row["estact"];
				if($ls_estact=="0")
				{
					$lb_valido=false;
				}
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_unidad 
   //-----------------------------------------------------------------------------------------------------------------------------

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
			$this->io_mensajes->message("CLASE->articulo ->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	function uf_verificarmovimientos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificarmovimientos
		//         Access: public (sigesp_siv_d_articulo)
		//      Argumento: 
		//	      Returns: Retorna un numero 1 si encontró movimiento o 0 si no encontró.
		//    Description: Funcion que verifica si existe un determinado articulo en la tabla siv_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * ".
				"  FROM siv_movimiento ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articulo MÉTODO->uf_verificarmovimientos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=1;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=0;
			}
		} 
		return $lb_valido;
	}// end function uf_verificarmovimientos
//--------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscarcodigoactivo($as_codact)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscarcodigoactivo
		//		   Access: public
		//	    Arguments: 
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que busca el codigo del activo si es que existe
		// Modificado por: Ing. Jennifer Rivero            
		// Fecha Creación: 11/09/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_codact="";
		$ls_sql=" SELECT codact ".
	   		    "  FROM saf_activo".
			    " WHERE codemp='".$this->ls_codemp."' ".
			    "   AND codact='".$as_codact."' ";			    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->articulo->uf_buscarcodigoactivo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codact=trim($row["codact"]);				
			}
			else
			{
				$ls_codact="";
			}
		}
		return $ls_codact;
	}// enduf_buscarcodigoactivo 
//-------------------------------------------------------------------------------------------------------------------------------------
     function uf_clasificacionarticulo($as_codtipart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_clasificacionarticulo
		//		   Access: public
		//	    Arguments: 
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que busca la clasificaciòn del articulo
		// Modificado por: Ing. Jennifer Rivero            
		// Fecha Creación: 11/09/2008 	 Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$tipart="";
		$ls_sql=" SELECT tipart FROM siv_tipoarticulo WHERE  codtipart='".$as_codtipart."'";			    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->articulo->uf_clasificacionarticulo ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_tipart=trim($row["tipart"]);				
			}
			else
			{
				$ls_tipart="";
			}
		}
		return $ls_tipart;
	}// end uf_clasificacionarticulo 
//-------------------------------------------------------------------------------------------------------------------------------------
} 
?>
