<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("sigesp_siv_c_articuloxalmacen.php");
require_once("sigesp_siv_c_movimientoinventario.php");

class sigesp_siv_c_revrecepcion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_revrecepcion()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=       new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_mov=  new sigesp_siv_c_movimientoinventario();
	}
	

	function uf_siv_select_recepcion($as_codemp,$as_numordcom,$as_numconrec,&$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_recepcion
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//  			   $as_codalm    // codigo de almacén
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de entrada de suministo a almacen y obtiene el almacen al que 
		//				   fueron enviado los articulos en la tabla de  siv_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_recepcion  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND numordcom='".$as_numordcom."'".
				  " AND numconrec='".$as_numconrec."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codalm=$row["codalm"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_siv_select_recepcion

	function uf_siv_select_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_dt_recepcion
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//  			   $rs_data     // resulset del select
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de recepcion de  suministros a almacen
		//				   en la tabla de  siv_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM siv_dt_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND numconrec='".$as_numconrec."'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			/*if($row=$this->io_sql->fetch_row($rs_dtrec))
			{
				$lb_valido=true;
				
			}
			else
			{
				$lb_valido=false;
			}*/
		}
		return $lb_valido;
	}  // end  function uf_siv_select_dt_recepcion
	
	function uf_siv_delete_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_dt_recepcion
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle de recepcion para un reverso en la tabla siv_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM siv_dt_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND numconrec='". $as_numconrec ."'";
		$li_row=$this->io_sql->execute($ls_sql);

		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_delete_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_siv_delete_dt_recepcion

	function uf_siv_delete_recepcion($as_codemp,$as_numordcom,$as_numconrec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_delete_recepcion
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepción
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un maestro de recepcion para un reverso en la tabla siv_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM siv_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND numconrec='". $as_numconrec ."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_delete_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_siv_delete_recepcion

	function uf_siv_obtener_recepcion(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_recepcion
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las entradas de suministros a los almacenes en la tabla de siv_recepcion para luego
		//				   mprimirlos en el grid de  la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_func=  new class_funciones();
		$lb_valido=true;
		$ls_sql= "SELECT * FROM siv_recepcion".
				  " WHERE estrevrec=1".
				  " ORDER BY numordcom, numconrec ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_obtener_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_numordcom= $row["numordcom"];
				$ld_fecrec=    $row["fecrec"];
				$ls_obsrec=    $row["obsrec"];
				$ls_numconrec= $row["numconrec"];
				$ld_fecrecaux=$io_func->uf_convertirfecmostrar($ld_fecrec);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtnumordcom".$ai_totrows." type=text id=txtnumordcom".$ai_totrows." class=sin-borde size=20 maxlength=15 value='".$ls_numordcom."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnumconrec".$ai_totrows." type=text id=txtnumconrec".$ai_totrows." class=sin-borde size=20 maxlength=15 value='".$ls_numconrec."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtfecrec".$ai_totrows." type=text id=txtfecrec".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".$ld_fecrecaux."' readonly>";
				$ao_object[$ai_totrows][4]="<textarea name=txtobsrec".$ai_totrows." class=sin-borde cols=40 rows=2 readonly>".$ls_obsrec."</textarea>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";

			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtnumordcom".$ai_totrows." type=text id=txtnumordcom".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnumconrec".$ai_totrows." type=text id=txtnumconrec".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtfecrec".$ai_totrows." type=text id=txtfecrec".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
				$ao_object[$ai_totrows][4]="<textarea name=txtobsrec".$ai_totrows." class=sin-borde cols=40 rows=2 readonly></textarea>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	}

	function uf_siv_update_articulos($as_codemp,$as_numordcom,$as_numconrec,$as_codalm,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_articulos
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepcion
		//  			   $as_codalm    // codigo de almacén
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos de una recepcion para disminuirles en las existencias  la cantidad que 
		//				   se esta reversando.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_art=  new sigesp_siv_c_articuloxalmacen();
		$lb_valido=false;
		$ls_sql= "SELECT * FROM siv_dt_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND numconrec='". $as_numconrec ."'";
				  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_update_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_break=false;
			while(($row=$this->io_sql->fetch_row($rs_data))&&(!$lb_break))
			{
				$ls_codart= $row["codart"];
				$li_canart= $row["canart"];
				$lb_valido=$io_art->uf_siv_disminuir_articuloxalmacen($as_codemp,$ls_codart,$as_codalm,$li_canart,$aa_seguridad);
				if(!$lb_valido)
				{$lb_break=true;}
			}//while
			if ($lb_valido)
			{
				$lb_valido=$io_art->uf_siv_actualizar_cantidad_articulos($as_codemp,$ls_codart,$aa_seguridad);
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_siv_update_articulos

	function uf_siv_select_ordencompra($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_ordencompra
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una orden de compra en la tabla de  soc_ordencompra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM soc_ordencompra  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND numordcom='".$as_numordcom."'".
				  " AND estpenalm=1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_select_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}

	function uf_siv_update_ordencompra($as_codemp,$as_numordcom) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_ordencompra
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la orden de compra "estpenalm" que indica si una orden de compra ha sido
		//			       completa o no. En la tabla soc_ordencompra 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 10/02/2006							Fecha Última Modificación : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE soc_ordencompra SET  estpenalm=0".
					" WHERE codemp='" . $as_codemp ."' ".
					" AND numordcom='" . $as_numordcom ."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_update_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
/*			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el estatus de la orden de compra numero ".$as_numordcom." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); */
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	  return $lb_valido;
	} // end  function uf_siv_update_ordencompra
	
	function uf_siv_update_status_recepcion($as_codemp,$as_numordcom,$as_numconrec,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_status_recepcion
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero consecutivo de recepción
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de una recepcion de suministros a alamcén "estrevrec" en la tabla 
		//			       siv_recepcion  la cual indica que esta fue reversada.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/02/2006							Fecha Última Modificación : 13/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE siv_recepcion SET  estrevrec=0".
					" WHERE codemp='" . $as_codemp ."' ".
					" AND numordcom='" . $as_numordcom ."' ".
					" AND numconrec='" . $as_numconrec ."' ";
					
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion MÉTODO->uf_siv_update_status_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Realizó el reverso de la recepcion numero ".$as_numconrec." Asociada a la Orden de compra ".$as_numordcom.
							 " de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	  return $lb_valido;
	} // end  function uf_siv_update_status_recepcion

	function uf_siv_actualizarestatus($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_actualizarestatus
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que activa el proceso de verificar la existencia de una orden de compra y en caso afirmativo
		//			       procede a actualizar el estatus de pendientes de almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/02/2006							Fecha Última Modificación : 13/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;  
		$lb_existe=$this->uf_siv_select_ordencompra($as_codemp,$as_numordcom);
		if($lb_existe)
		{
			$lb_valido=$this->uf_siv_update_ordencompra($as_codemp,$as_numordcom);
		}	
		return $lb_valido;
	}  // end  function uf_siv_actualizarestatus

	function uf_siv_crear_movimientos($as_codemp,$ad_fecrev,$as_codalm,$as_opeinv,$as_codprodoc,$as_numordcom,$as_promov,$as_numconrec,$ai_candesart,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_actualizarestatus
		//         Access: public (sigesp_siv_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ad_fecrev    // fecha en la cual se hizo el reverso
		//  			   $as_codalm    // codigo de almacen
		//  			   $as_opeinv    // operacion de inventario
		//  			   $as_codprodoc // codigo de procedencia del documento
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_promov    // codigo de la procedencia del movimiento
		//  			   $as_numconrec // numero consecutivo de recepción
		//  			   $ai_candesart // cantidad restante de articulos en el movimiento
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion en la que se busca los datos restantes para crear un movimiento de inventario y luego de obtenerlos
		//			       hace el llamado a la funcion de la clase sigesp_siv_c_movimientoinventario que lo inserta 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 14/02/2006							Fecha Última Modificación : 14/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomsol="Reverso";
		$ls_nummov="";
		$lb_valido=$this->io_mov->uf_siv_insert_movimiento($ls_nummov,$ad_fecrev,$ls_nomsol,$as_codusu,$aa_seguridad);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_siv_select_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec,$rs_data);
			if($lb_valido)
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_codart=$row["codart"];
					$li_canart=$row["canart"];
					$li_cosart=$row["preuniart"];
					$lb_valido=$this->io_mov->uf_siv_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$as_codalm,$as_opeinv,
																		  $as_codprodoc,$as_numordcom,$li_canart,$li_cosart,$as_promov,
																		  $as_numconrec,$ai_candesart,$ad_fecrev,$aa_seguridad);
					if($lb_valido==false)
					{
						break;
					}
				}  //fin while($row=$this->io_sql->fetch_row($rs_dtrec))
				$this->io_sql->free_result($rs_data);
			}
		} // fin if($lb_valido) uf_siv_insert_movimiento
		return $lb_valido;
	}// end function uf_siv_crear_movimientos

}//end  class sigesp_siv_c_revrecepcion
?>
