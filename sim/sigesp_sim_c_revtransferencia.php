<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("sigesp_sim_c_articuloxalmacen.php");
require_once("sigesp_sim_c_movimientoinventario.php");
require_once("sigesp_sim_c_recepcion.php");


class sigesp_sim_c_revtransferencia
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_revtransferencia()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=       new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_mov=  new sigesp_sim_c_movimientoinventario();
		$this->io_rec= new sigesp_sim_c_recepcion();
		$this->io_art= new sigesp_sim_c_articuloxalmacen();
	}


	function uf_sim_select_transferencia($as_codemp,$as_numtra,&$as_codalmori,&$as_codalmdes,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_transferencia
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numtra    // numero de la orden de compra/factura
		//  			   $as_codalmori // codigo de almacen  de origen
		//  			   $as_codalmdes // codigo de almacen de destino
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de transferencia entre almacenes y obtiene los codigos de los
		//				   almacenes que intervinieron el la operacion de la tabla sim_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 28/02/2006							Fecha �ltima Modificaci�n : 28/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_transferencia  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND numtra='".$as_numtra."' AND codtiend='".$ls_codtiend."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_select_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_codalmori=$row["codalmori"];
				$as_codalmdes=$row["codalmdes"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_sim_select_recepcion

	function uf_sim_select_dt_transferencia($as_codemp,$as_numtra,&$rs_data,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_transferencia
		//         Access: public (sigesp_sim_p_revtransferencia)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numtra    // numero de la transferencia
		//  			   $rs_data     // resultset obtenido
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de transferencia entre almacenes
		//				   en la tabla de  sim_dt_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sim_dt_transferencia".
				" WHERE codemp='". $as_codemp ."'".
				" AND numtra='". $as_numtra ."' AND codtiend='".$ls_codtiend."' ";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_select_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;

		}
		return $lb_valido;
	}  // end  function uf_sim_select_dt_transferencia

	function uf_sim_load_transferencia(&$ai_totrows,&$ao_object,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_load_transferencia
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las transferencia entre almacenes en la tabla de sim_recepcion para luego
		//				   mprimirlos en el grid de  la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 28/02/2006							Fecha �ltima Modificaci�n : 28/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_func=  new class_funciones();
		$lb_valido=true;
		$ls_sql= "SELECT sim_transferencia.numtra,sim_transferencia.fecemi,".
				 "       (SELECT sim_almacen.nomfisalm FROM sim_almacen".
				 "         WHERE sim_transferencia.codalmori=sim_almacen.codalm ) AS nomfisori,".
				 "       (SELECT sim_almacen.nomfisalm FROM sim_almacen".
				 "         WHERE sim_transferencia.codalmdes=sim_almacen.codalm ) AS nomfisdes".
				 "  FROM sim_transferencia where codtiend='".$ls_codtiend."' ".
				 " ORDER BY numtra ASC ";
				 //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_load_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_i=$li_i + 1;
				$ls_numtra=    $row["numtra"];
				$ld_fecemi=    $row["fecemi"];
				$ls_nomfisori= $row["nomfisori"];
				$ls_nomfisdes= $row["nomfisdes"];
				$ld_fecemiaux=$io_func->uf_convertirfecmostrar($ld_fecemi);

				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtnumtra".$ai_totrows."    type=text id=txtnumtra".$ai_totrows."    class=sin-borde size=20 maxlength=15 value='".$ls_numtra."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnomfisori".$ai_totrows." type=text id=txtnomfisori".$ai_totrows." class=sin-borde size=30 maxlength=50 value='".$ls_nomfisori."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtnomfisdes".$ai_totrows." type=text id=txtnomfisdes".$ai_totrows." class=sin-borde size=30 maxlength=50 value='".$ls_nomfisdes."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtfecemi".$ai_totrows."    type=text id=txtfecemi".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ld_fecemiaux."' readonly>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}//while
			if ($li_i==0)
			{
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtnumtra".$ai_totrows." type=text id=txtnumtra".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnomfisori".$ai_totrows." type=text id=txtnomfisori".$ai_totrows." class=sin-borde size=15 maxlength=15 readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtnomfisdes".$ai_totrows." type=text id=txtnomfisdes".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
				$ao_object[$ai_totrows][4]="<imput name=txtfecemi".$ai_totrows." type=text id=txtfecemi".$ai_totrows." class=sin-borde size=12 maxlength=12 readonly>";
				$ao_object[$ai_totrows][5]="<input type='checkbox' name=chkreversar".$ai_totrows." class= sin-borde value=1>";
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{
			$lb_valido=false;
		}
		return $lb_valido;
	} // end  function uf_sim_load_transferencia

	function uf_sim_update_articulos($as_codemp,$as_numtra,$as_codalmori,$as_codalmdes,$aa_seguridad,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_articulos
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numtra    // numero de transferencia
		//  			   $as_codalmori // codigo de almac�n origen
		//  			   $as_codalmdes // codigo de almac�n destino
		//				   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos de una transferencia entre almacenes para disminuirles y aumentarles en las
		//				   existencias  la cantidad que se esta reversando.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 29/02/2006							Fecha �ltima Modificaci�n : 29/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=false;
		$ls_sql= "SELECT * FROM sim_dt_transferencia".
				" WHERE codemp='". $as_codemp ."'".
				" AND numtra='". $as_numtra ."' AND codtiend='".$ls_codtiend."'";

		//print "<br>".$ls_sql."<br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_update_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart= $row["codart"];
				$li_canart= $row["cantidad"];
				$ls_codproveedor=$row["cod_pro"];
				$ls_codtienddes=substr($as_codalmdes,6,4);
				$lb_valido=$this->io_art->uf_sim_disminuir_articuloxalmacen($as_codemp,$ls_codart,$as_codalmdes,$li_canart,$ls_codproveedor,$ls_codtienddes,$aa_seguridad);
				if($lb_valido)
				{

					$lb_valido=$this->io_art->uf_sim_aumentar_articuloxalmacen($as_codemp,$ls_codart,$as_codalmori,$li_canart,$aa_seguridad,$ls_codproveedor,$ls_codtiend);

				}
			}//while
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_sim_update_articulos

	function uf_sim_crear_movimientos($as_codemp,$ad_fecrev,$as_codalmori,$as_codalmdes,$as_opeinv,$as_codprodoc,$as_numtra,$as_promov,$ai_candesart,$as_codusu,$aa_seguridad,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_actualizarestatus
		//         Access: public (sigesp_sim_p_revtransferencia)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ad_fecrev    // fecha en la cual se hizo el reverso
		//  			   $as_codalmori // codigo de almacen origen
		//  			   $as_codalmdes // codigo de almacen destino
		//  			   $as_opeinv    // operacion de inventario
		//  			   $as_codprodoc // codigo de procedencia del documento
		//  			   $as_numtra    // numero de la transferencia
		//  			   $as_promov    // codigo de la procedencia del movimiento
		//  			   $as_numconrec // numero consecutivo de recepci�n
		//  			   $ai_candesart // cantidad restante de articulos en el movimiento
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion en la que se busca los datos restantes para crear un movimiento de inventario y luego de obtenerlos
		//			       hace el llamado a la funcion de la clase sigesp_sim_c_movimientoinventario que lo inserta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 28/02/2006							Fecha �ltima Modificaci�n : 28/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomsol="Reverso";
		$ls_nummov="";

			$rs_data="";
			$lb_valido=$this->uf_sim_select_dt_transferencia($as_codemp,$as_numtra,$rs_data,$ls_codtiend);
			if($lb_valido)
			{

				$li_i=0;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_opeinv= "SAL";
					$ls_codprodoc= "REV";
					$ls_promov= "TRA";
					$li_candesart= 0.00;
					$ls_codart=$row["codart"];
					$li_canart=$row["cantidad"];
					$li_cosart=$row["cosuni"];
					$ls_unidad=$row["unidad"];
					$ls_codproveedor=$row["cod_pro"];
					$ls_codtienddes=substr($as_codalmdes,6,4);
					$ls_obstra="Entrada por Reverso de Transferencia";
					$ls_estpro=1;
					$ls_estrec=1;
					$li_costot=$li_cosart*$li_canart;
					$li_i++;

					$lb_valido=$this->io_rec->uf_sim_insert_recepcion($as_codemp,$as_numtra,$ls_codproveedor,$as_codalmori,$ad_fecrev,$ls_obstra,
																	$as_codusu,$ls_estpro,$ls_estrec,&$ls_numconrec,$ls_codtiend,$aa_seguridad);

					if($lb_valido)
					{


						$lb_valido=$this->io_rec->uf_sim_insert_dt_recepcion($as_codemp,$as_numtra,$ls_codart,$ls_unidad,$li_canart,$li_canart,$li_cosart,
																	$li_costot,$li_costot,$li_i,$li_canart,$ls_numconrec,$ls_codtiend,
																	$ls_codproveedor,$aa_seguridad);


						$ls_codprodoc="ALM";
						$ls_promov="TRA";


						$lb_valido=$this->io_mov->uf_sim_insert_movimiento($ls_nummov,$ad_fecrev,$ls_nomsol,$as_codusu,$aa_seguridad,$ls_codtienddes);
						if ($lb_valido)
						{
							$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$as_codalmdes,$ls_opeinv,
																			  $ls_codprodoc,$as_numtra,$li_canart,$li_cosart,$as_promov,
																			  $as_numtra,$ai_candesart,$ad_fecrev,$aa_seguridad,$ls_codtienddes,$ls_codproveedor);


						}
						$lb_valido=$this->io_mov->uf_sim_insert_movimiento($ls_nummov,$ad_fecrev,$ls_nomsol,$as_codusu,$aa_seguridad,$ls_codtiend);
						if($lb_valido)
						{
							$ls_opeinv= "ENT";
							$ls_codprodoc= "REV";
							$ls_promov= "TRA";
							$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$as_codalmori,$ls_opeinv,
																				  $ls_codprodoc,$as_numtra,$li_canart,$li_cosart,$ls_promov,
																				  $as_numtra,$li_canart,$ad_fecrev,$aa_seguridad,$ls_codtiend,$ls_codproveedor);
						}
					}
					if($lb_valido==false)
					{
						break;

					}
				}  //fin while($row=$this->io_sql->fetch_row($rs_dtrec))

				$this->io_sql->free_result($rs_data);
			}
		//} // fin if($lb_valido) uf_sim_insert_movimiento
		return $lb_valido;
	}// end function uf_sim_crear_movimientos

	function uf_sim_delete_dt_transferencia($as_codemp,$as_numtra,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_dt_transferencia
		//         Access: public (sigesp_sim_p_revtransferencia)
		//      Argumento: $as_codemp // codigo de empresa
		//  			   $as_numtra // numero de transferencia
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina los detalles asociados a una transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 28/02/2006							Fecha �ltima Modificaci�n : 28/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sim_dt_transferencia".
				" WHERE codemp='". $as_codemp ."'".
				" AND numtra='". $as_numtra ."' AND codtiend='".$ls_codtiend."'";
		$li_row=$this->io_sql->execute($ls_sql);

		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_delete_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sim_delete_dt_transferencia

	function uf_sim_delete_transferencia($as_codemp,$as_numtra,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_transferencia
		//         Access: public (sigesp_sim_p_revtranferencia)
		//      Argumento: $as_codemp // codigo de empresa
		//  			   $as_numtra // numero de transferencia
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un maestro de transferencia entre almacenes
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 28/02/2006							Fecha �ltima Modificaci�n : 28/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sim_transferencia".
				" WHERE codemp='". $as_codemp ."'".
				" AND numtra='". $as_numtra ."' AND codtiend='".$ls_codtiend."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_delete_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sim_delete_transferencia

	function uf_sim_disminuir_articuloxmovimiento($as_codemp,$as_codart,$li_canart,$as_codalm,$as_numtra,$ls_codproveedor,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_disminuir_articuloxmovimiento
		//         Access: private
		//      Argumento: $as_codemp       // codigo de empresa
		//                 $as_codart       // codigo de articulo
		//                 $as_codalm       // codigo de almacen
		//                 $as_numtra       // numero de transferencia
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que disminuye la cantidad de articulos proveniente de un movimiento en la tabla sim_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 30/02/2006 								Fecha �ltima Modificaci�n :30/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $rs_disart=-1;
		 $ld_date= date("Y-m-d");
		 $ls_sql = "UPDATE sim_dt_movimiento SET candesart= (candesart - '". $li_canart ."'), ".
		 			" fecdesart= '" . $ld_date ."'".
					" WHERE codemp='" . $as_codemp ."'".
					" AND opeinv='SAL'".
					" AND codprodoc='ALM'".
					" AND promov='TRA'".
					" AND numdoc='" . $as_numtra ."'".
					" AND codart='" . $as_codart ."'".
					" AND codalm='" . $as_codalm ."'".
					" AND numdocori='" . $as_numtra ."' AND codtiend='".$ls_codtiend."' AND cod_pro='".$ls_codproveedor."'";


		$li_row= $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revtransferencia M�TODO->uf_sim_disminuir_articuloxmovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sim_disminuir_articuloxmovimiento

/**********************************************************************************************
  * function uf_sim_disminuir_articuloxmovimiento($as_codemp,$as_codart,$as_codalm,$as_nummov,$ls_numdocori,$ai_cantidad,$ls_codproveedor,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_disminuir_articuloxmovimiento
		//         Access: private
		//      Argumento: $as_codemp       // codigo de empresa
		//                 $as_codart       // codigo de articulo
		//                 $as_codalm       // codigo de almacen
		//                 $ls_numdocori    // numero original de la entrada de suministros a almac�n
		//                 $as_nummov       // numero de movimiento
		//                 $as_cantidad     // cantidad de articulos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que disminuye la cantidad de articulos proveniente de un movimiento en la tabla sim_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 09/02/2006 								Fecha �ltima Modificaci�n :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $rs_disart=-1;
		 $ld_date= date("Y-m-d");
		 $ls_sql= "UPDATE sim_dt_movimiento".
		 		  "   SET candesart= (candesart - '". $ai_cantidad ."'), ".
		 		  "       fecdesart= '" . $ld_date ."'".
				  " WHERE codemp='" . $as_codemp ."'".
				  " AND   opeinv='ENT'".
				  " AND   nummov='" . $as_nummov ."'".
				  " AND   codart='" . $as_codart ."'".
				  " AND   codalm='" . $as_codalm ."'".
				  " AND   numdocori='" . $ls_numdocori ."' AND codtiend='".$ls_codtiend."' AND cod_pro='".$ls_codproveedor."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_disminuir_articuloxmovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sim_disminuir_articuloxmovimiento

 *
 *
 *
 *
 *
 *
 *
 */










}//end  class sigesp_sim_c_revtransferencia
?>
