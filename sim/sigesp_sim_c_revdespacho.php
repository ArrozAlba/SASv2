<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("sigesp_sim_c_articuloxalmacen.php");
require_once("sigesp_sim_c_movimientoinventario.php");

class sigesp_sim_c_revdespacho
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_revdespacho()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=       new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_art=    new sigesp_sim_c_articuloxalmacen();
		$this->io_mov=    new sigesp_sim_c_movimientoinventario();

	}

	function uf_sim_select_despacho($as_codemp,$as_numorddes)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_despacho
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numorddes // numero de la orden de despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de un despacho de almacen y obtiene sus datos de la tabla sim_despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sim_despacho  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numorddes='".$as_numorddes."'".
				  "   AND estrevdes=1";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_select_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end function uf_sim_select_despacho

	function uf_sim_select_dt_despacho($as_codemp,$as_numorddes,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_despacho
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numorddes // numero de la orden de despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de despacho y obtiene el codigo del almacen
		//                 del cual fueron despachados los art�culos, en la tabla de  sim_dt_despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sim_dt_despacho.*,".
		        "       (SELECT numsol FROM sim_despacho".
				"         WHERE codemp='". $as_codemp ."'".
				"           AND numorddes='". $as_numorddes ."')AS numsol".
				"  FROM sim_dt_despacho".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numorddes='". $as_numorddes ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_select_dt_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  // end  function uf_sim_select_dt_despacho

	function uf_sim_obtener_despacho(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_obtener_despacho
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los despachos en la tabla de sim_despacho para luego imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_func=  new class_funciones();
		$lb_valido=true;
		$ls_sql= "SELECT * FROM sim_despacho".
				 " WHERE estrevdes=1 AND coduniadm<>'' ";
				 " ORDER BY numorddes ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_obtener_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_numorddes= $row["numorddes"];
				$ld_fecdes=    $row["fecdes"];
				$ls_obsdes=    $row["obsdes"];
				$ls_numsol=    $row["numsol"];
				$ld_fecdesaux=$io_func->uf_convertirfecmostrar($ld_fecdes);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtnumorddes".$ai_totrows." type=text id=txtnumorddes".$ai_totrows." class=sin-borde size=20 maxlength=15 value='".$ls_numorddes."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtfecdes".$ai_totrows."    type=text id=txtfecdes".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ld_fecdesaux."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtnumsol".$ai_totrows."    type=text id=txtnumsol".$ai_totrows."    class=sin-borde size=20 maxlength=15 value='".$ls_numsol."' readonly>";
				$ao_object[$ai_totrows][4]="<textarea name=txtobsdes".$ai_totrows." class=sin-borde cols=40 rows=2 readonly>".$ls_obsdes."</textarea>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class=sin-borde value=1>";

			}//while
			if($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtnumorddes".$ai_totrows." type=text id=txtnumorddes".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtfecdes".$ai_totrows."    type=text id=txtfecdes".$ai_totrows."    class=sin-borde size=12 maxlength=12 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtnumsol".$ai_totrows."    type=text id=txtnumsol".$ai_totrows."    class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][4]="<textarea name=txtobsdes".$ai_totrows." class=sin-borde cols=40 rows=2 readonly></textarea>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class=sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	}  // end  function uf_sim_obtener_despacho

	function uf_sim_update_articulos($as_codemp,$as_numorddes,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_articulos
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de despacho
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles del despacho en la tabla de sim_dt_despacho obteniendo el codigo de los
		//				   art�culos indicando de que almacen fueron despachados para volverles a dar entrada.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sim_dt_despacho".
				"  WHERE codemp='". $as_codemp ."'".
				"    AND numorddes='". $as_numorddes ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_update_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codalm=$row["codalm"];
				$ls_codart=$row["codart"];
				$li_canart=$row["canart"];
				$lb_valido=$this->io_art->uf_sim_aumentar_articuloxalmacen($as_codemp,$ls_codart,$ls_codalm,$li_canart,$aa_seguridad);
				/*if ($lb_valido)
				{
					$lb_valido=$this->io_art->uf_sim_actualizar_cantidad_articulos($as_codemp,$ls_codart,$aa_seguridad);
				}*/
			}//  fin while
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  //  end  function uf_sim_update_articulos

	function uf_sim_update_articulos_alm($as_codemp,$as_codalm,$as_numorddes,$as_codtienda,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_articulos
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de despacho
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles del despacho en la tabla de sim_dt_despacho obteniendo el codigo de los
		//				   art�culos indicando de que almacen fueron despachados para volverles a dar entrada.
		//	   Creado Por: Ing. Luis A. Alvarez
		// Fecha Creaci�n: 25/06/2008							Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sim_dt_despacho".
				"  WHERE codemp='". $as_codemp ."'".
				"  AND numorddes='". $as_numorddes ."' AND codtiend='". $as_codtienda ."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revdespacho METODO->uf_sim_update_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codalm=$row["codalm"];
				$ls_codart=$row["codart"];
				$li_canart=$row["canart"];
				$li_codprov=$row["cod_pro"];
				$lb_valido=$this->io_art->uf_sim_aumentar_articuloxalmacen($as_codemp,$ls_codart,$ls_codalm,$li_canart,$aa_seguridad,$li_codprov,$as_codtienda);
				/*if ($lb_valido)
				{
					$this->io_sql->commit();
					$this->io_sql->begin_transaction();
					$lb_valido=$this->io_art->uf_sim_actualizar_cantidad_articulos_alm($as_codemp,$ls_codart,$as_codalm,$aa_seguridad);
				}*/
			}//  fin while
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  //  end  function uf_sim_update_articulos_alm

	function uf_sim_update_status_despacho($as_codemp,$as_numorddes,$as_numsol,$as_codtienda,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_status_despacho
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numorddes // numero de despacho
		//  			   $as_numsol    // numero de sep
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de una orden de despacho "estrevdes" la cual indica que esta fue
		//				   reversada.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sim_despacho SET  estrevdes=0".
				   " WHERE codemp='". $as_codemp ."' ".
				   " AND numorddes='". $as_numorddes ."' AND codtiend='". $as_codtienda ."'";

		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revdespacho METODO->uf_sim_update_status_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Realizo un reverso de la Orden de Despacho".$as_numorddes." Asociada a la Empresa ".$as_codemp;
			$lb_valido= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_sim_update_sep($as_codemp,$as_numsol);
		}
	  return $lb_valido;
	} // end  function uf_sim_update_status_despacho

	function uf_sim_update_ordencompra($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ordencompra
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de orden de compra
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la orden de compra "estpenalm" que indica si una orden de compra ha sido
		//				   completa o no. En la tabla soc_ordencompra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE soc_ordencompra SET  estpenalm=0".
				   " WHERE codemp='" . $as_codemp ."' ".
				   "   AND numordcom='" . $as_numordcom ."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_update_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end  function uf_sim_update_ordencompra

	function uf_sim_actualizarestatus($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ordencompra
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de orden de compra
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que activa el proceso de verificar la existencia de una orden de compra  y en caso afirmativo procede
		//				   procede a actualizar el estatus de pendientes de almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_sim_select_ordencompra($as_codemp,$as_numordcom);
		if($lb_existe)
		{
			$lb_valido=$this->uf_sim_update_ordencompra($as_codemp,$as_numordcom);
		}
		return $lb_valido;

	}  // end  function uf_sim_actualizarestatus

	function uf_sim_crear_movimientos($as_codemp,$ad_fecrev,$as_opeinv,$as_codprodoc,$as_numorddes,$as_promov,$as_numoridoc,$ai_candesart,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ordencompra
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ad_fecrev    // fecha en la cual se hizo el reverso
		//  			   $s_opeinv    // operacion de inventario
		//  			   $as_codprodoc // codigo de procedencia del documento
		//  			   $as_numorddes // numero de la orden de despacho
		//  			   $as_numoridoc // numero original del documento
		//  			   $ai_candesart // cantidad restante de articulos en el movimiento
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion en la que se busca los datos restantes para crear un movimiento de inventario y luego de obtenerlos
		//				   hace el llamado a la funcion de la clase sigesp_sim_c_movimientoinventario que lo inserta.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 15/02/2006							Fecha �ltima Modificaci�n : 15/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomsol="Reverso";
		$ls_nummov="";
		$lb_valido=$this->io_mov->uf_sim_insert_movimiento($ls_nummov,$as_codemp,$ad_fecrev,$ls_nomsol,$as_codusu,$aa_seguridad);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_sim_select_dt_despacho($as_codemp,$as_numorddes,$rs_revdes);
			if($lb_valido)
			{
				while($row=$this->io_sql->fetch_row($rs_revdes))
				{
					$ls_numdocori=$ls_nomsol;
					$ls_codart=$row["codart"];
					$ls_codalm=$row["codalm"];
					$li_canart=$row["canart"];
					$li_cosart=$row["preuniart"];
					$ls_numsol=$row["numsol"];
					$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$ls_codalm,$as_opeinv,
																		  $as_codprodoc,$as_numorddes,$li_canart,$li_cosart,$as_promov,
																		  $ls_numsol,$ai_candesart,$ad_fecrev,$aa_seguridad);
/*					if($lb_valido)
					{
						$ls_opeinv=    "ENT";
						$ls_codprodoc= "REV";
						$ls_promov=    "DES";
						$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$ls_codalm,$ls_opeinv,
																			  $ls_codprodoc,$as_numorddes,$li_canart,$li_cosart,$ls_promov,
																			  $as_numoridoc,$li_canart,$ad_fecrev,$aa_seguridad);
					}
*/					if($lb_valido==false)
					{
						break;
					}
				}  //fin while($row=$this->io_sql->fetch_row($rs_dtrec))
				//$this->io_sql->free_result($rs_revdes);
			}
		} // fin if($lb_valido) uf_sim_insert_movimiento
		return $lb_valido;
	}// end function uf_sim_crear_movimientos

	function uf_sim_update_sep($as_codemp,$as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_sep
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numsol    // numero de la solicitud de ejecuci�n presupuestaria
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la solicitud de ejecuci�n presupuestaria  estsol que indica
		//				   si la SEP fue despachada o no.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 16/02/2006							Fecha �ltima Modificaci�n : 16/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_valido=$this->uf_sim_select_estatus_sep($as_codemp,$as_numsol,$ls_estsep);
		if($lb_valido)
		{
			$ls_sql= "UPDATE sep_solicitud".
					 "   SET estsol='" . $ls_estsep ."'".
					 " WHERE codemp='" . $as_codemp ."' ".
					 "   AND numsol='" . $as_numsol ."' ";
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_update_sep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end function uf_sim_update_sep

	function uf_sim_select_estatus_sep($as_codemp,$as_numsol,&$as_estsep)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_estatus_sep
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numorddes // numero de la orden de despacho
		//				   $as_estsep    // estatus de la sep
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si una sep esta en una orden de compra para colocar el estatus correspondiente
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 20/11/2006							Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol FROM soc_enlace_sep".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numsol='". $as_numsol ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_select_estatus_sep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_estsep="P";
			}
			else
			{
				$as_estsep="E";
			}
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sim_select_dt_despacho

	function uf_sim_select_dt_contable($as_codemp,$as_codcmp)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_contable
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp // codigo de empresa
		//  			   $as_codcmp // codigo de comprobante (numero de la orden de despacho)
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que la orden de despacho no ha sido contabilizada
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 21/11/2006							Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= "SELECT codcmp,estint".
				 "  FROM sim_dt_scg  ".
				 " WHERE codemp='".$as_codemp."'".
				 "   AND codcmp='".$as_codcmp."'".
				 " GROUP BY codcmp,estint";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revdespacho M�TODO->uf_sim_select_dt_contable ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estint=$row["estint"];
				if($ls_estint==0)
				{$lb_valido=true;}
			}
			else
			{$lb_valido=true;}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_sim_select_dt_contable

	function uf_sim_delete_dt_contable($as_codemp,$as_codcmp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_dt_contable
		//         Access: public (sigesp_sim_p_revdespacho)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codcmp    // codigo de comprobante (numero de la orden de despacho)
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que la orden de despacho no ha sido contabilizada
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 21/11/2006							Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$lb_existe=$this->uf_sim_select_dt_contable($as_codemp,$as_codcmp);
		if($lb_existe)
		{
			$ls_sql= "DELETE FROM sim_dt_scg".
					 " WHERE codemp= '".$as_codemp. "'".
					 "   AND codcmp= '".$as_codcmp. "'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->almacen M�TODO->uf_sim_delete_almacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Elimin� el detalle contable del comprobante ".$as_codcmp." Asociado a la Empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
		return $lb_valido;
	}  // end function uf_sim_delete_dt_contable

}//end  class sigesp_sim_c_revrecepcion
?>
