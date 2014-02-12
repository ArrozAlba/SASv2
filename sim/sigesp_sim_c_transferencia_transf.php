<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_sim_c_transferencia_transf
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_transferencia_transf()
	{
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("sigesp_sim_c_movimientoinventario.php");
		require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");

		$pathbase= str_replace("sfc","sim",getcwd());
		//$archivo= new sigesp_sfc_c_intarchivo("var/www/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO");
		$this->archivoO= new sigesp_sfc_c_intarchivo("var/www/sigesp_fac/sfc/transferencias/ALMACENORIGEN");
		$this->archivoD= new sigesp_sfc_c_intarchivo("var/www/sigesp_fac/sfc/transferencias/ALMACENDESTINO");
		$this->dat_emp=   $_SESSION["la_empresa"];
		$this->ls_gestor= $_SESSION["ls_gestor"];
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_mov= new sigesp_sim_c_movimientoinventario();
	}


	function uf_sim_select_transferencia($as_codemp,$as_numtra,$ad_fecemi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp //codigo de empresa
		//                 $as_numtra //numero de transferencia
		//                 $ad_fecemi //fecha de emision
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion busca si existe una transferencia entre almacenes en la tabla de  sim_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_transferencia  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numtra='".$as_numtra."'".
				  "   AND fecemi='".$ad_fecemi."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_select_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end  function uf_sim_select_transferencia

	function uf_sim_insert_transferencia_transf($as_codemp,&$as_numtra,$ad_fecemi,$as_codusu,$as_codalmori,$as_codalmdes,$as_obstra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa				$as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision				$as_codusu    // codigo del usuario
		//                 $as_codalmori // codigo de almacen de origen		$as_codalmdes // codigo de almacen de destino
		//                 $as_obstra    // observacion de la transferencia	$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion inserta una operacion de transferencia entre almacenes  en la tabla de  sim_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_emp="";
		$ls_empresa="";
		$ls_tabla="sim_transferencia";
		$ls_columna="numtra";
		$as_numtra=$this->fun->uf_generar_codigo($ls_emp,$ls_empresa,$ls_tabla,$ls_columna);
		$ls_sql="INSERT INTO sim_transferencia (codemp, numtra, fecemi, codusu, obstra, codalmori, codalmdes)".
				" VALUES ('".$as_codemp."','".$as_numtra."','".$ad_fecemi."','".$as_codusu."','".$as_obstra."',".
				"         '".$as_codalmori."','".$as_codalmdes."');";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_insert_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Realizo la Transferencia ".$as_numtra." del Almac�n ".$as_codalmori." al Almac�n ".$as_codalmdes.". Asociados a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	} // end  function uf_sim_insert_transferencia

	function uf_sim_update_transferencia($as_codemp,&$as_numtra,$ad_fecemi,$as_codusu,$as_codalmori,$as_codalmdes,$as_obstra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa				$as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision				$as_codusu    // codigo del usuario
		//                 $as_codalmori // codigo de almacen de origen		$as_codalmdes // codigo de almacen de destino
		//                 $as_obstra    // observacion de la transferencia	$aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion modifica una operacion de transferencia entre almacenes en la tabla de  sim_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= "UPDATE sim_transferencia".
		 		  "   SET codusu='". $as_codusu ."',".
				  "       codalmori='". $as_codalmori ."',".
				  "       codalmdes='". $as_codalmdes ."',".
				  "       obstra='". $as_obstra ."' ".
				  " WHERE codemp='" . $as_codemp ."'".
				  "   AND numtra='" . $as_numtra ."'".
				  "   AND fecemi='" . $ad_fecemi ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_update_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end  function uf_sim_update_transferencia

	function uf_sim_delete_transferencia($as_codemp,$as_numtra,$ad_fecemi,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa				$as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision				$aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion elimina una transferencia entre almacenes en la tabla de  sim_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " DELETE FROM sim_transferencia".
				  " WHERE codemp= '".$as_codemp. "'".
				  "   AND numtra= '".$as_numtra. "'".
				  "   AND fecemi= '".$ad_fecemi. "'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_delete_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sim_delete_transferencia

	function uf_sim_select_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa
		//                 $as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision
		//                 $as_codart    // codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los detalles asociados a una transferencia entre almacenes en la tabla sim_dt_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_dt_transferencia".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numtra='". $as_numtra ."'".
				"   AND fecemi='". $ad_fecemi ."'".
				"   AND codart='". $as_codart ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_select_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end function uf_sim_select_dt_transferencia

	function uf_sim_insert_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart,$as_unidad,$ai_cantidad,$ai_cosuni,$ai_costot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_dt_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa				$as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision				$as_codart    // codigo de articulo
		//                 $ai_cosuni    // costo unitario 					$as_unidad    // unidad de medida M->Mayor D->Detal
		//                 $ai_costot    // costo total 					$ai_cantidad  // cantidad de articulos a ser transferidos
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de una transferencia entre almacenes en la tabla de  sim_dt_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sim_dt_transferencia (codemp, numtra, fecemi, codart, unidad, cantidad, cosuni, costot)".
				" VALUES ('".$as_codemp."','".$as_numtra."','".$ad_fecemi."','".$as_codart."','".$as_unidad."','".$ai_cantidad."',".
				"         '".$ai_cosuni."','".$ai_costot."')";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_insert_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
		}
		return $lb_valido;
	} // end function uf_sim_insert_dt_transferencia

	function uf_sim_update_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart,$as_codunimed,$ai_cantidad,$ai_cosuni,$ai_costot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_dt_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa				$as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision				$as_codart    // codigo de articulo
		//                 $as_codunimed // codigo de unidad de medida		$ai_cantidad  // cantidad de articulos a ser transferidos
		//                 $ai_cosuni    // costo unitario					$ai_costot    // costo total
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de una transferencia entre almacenes en la tabla de  sim_dt_transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= "UPDATE sim_dt_transferencia".
		 		  "   SET codunimed='". $as_codunimed ."',".
				  "       cantidad='". $ai_cantidad ."',".
				  "       cosuni='". $ai_cantidad ."',".
				  "       costot='". $ai_costot ."' ".
				  " WHERE codemp='" . $as_codemp ."'".
				  "   AND numtra='" . $as_numtra ."'".
				  "   AND fecemi='" . $ad_fecemi ."'".
				  "   AND codart='" . $as_codart ."'";
		$li_exec = $this->io_sql->execute($ls_sql);
		if($li_exec==false&&($this->io_sql->message!=""))
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_update_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end  function uf_sim_update_dt_transferencia

	function uf_sim_guardar_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart,$as_codunimed,$ai_cantidad,$ai_cosuni,$ai_costot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_guardar_dt_transferencia
		//         Access: public (sigesp_sim_p_transferencia)
		//      Argumento: $as_codemp    //codigo de empresa				$as_numtra    // numero de transferencia
		//                 $ad_fecemi    // fecha de emision				$as_codart    // codigo de articulo
		//                 $as_codunimed // codigo de unidad de medida		$ai_cantidad  // cantidad de articulos a ser transferidos
		//                 $ai_cosuni    // costo unitario					$ai_costot    // costo total
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Esta funcion deacuerdo a una busqueda (select) inserta � modifica un  detalle de la transferencia
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(!($this->uf_sim_select_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart)))
		{
			$lb_valido=$this->uf_sim_insert_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart,$as_codunimed,$ai_cantidad,$ai_cosuni,$ai_costot);
		}
		else
		{
			$lb_valido=$this->uf_sim_update_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart,$as_codunimed,$ai_cantidad,$ai_cosuni,$ai_costot);
		}
		return $lb_valido;
	} // end function uf_sim_guardar_dt_transferencia

	function uf_sim_delete_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,$as_codart)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_delete_dt_transferencia
	//	Access:    public
	//	Arguments:
	//  as_codemp    // codigo de empresa
	//  as_numtra    // numero de transferencia
	//  ad_fecemi    // fecha de emision
	//  as_codart    // codigo de articulo
	//  aa_seguridad // arreglo de registro de seguridad
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion elimina los detalles asociados a una transferencia
	//                entre almacenes en la tabla de  sim_dt_transferencia
	//
	//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " DELETE FROM sim_dt_transferencia".
				  " WHERE codemp= '".$as_codemp. "'".
				  "   AND numtra= '".$as_numtra. "'".
				  "   AND fecemi= '".$ad_fecemi. "'".
				  "   AND codart= '".$as_codart. "'";

		$li_exec=$this->io_sql->select($ls_sql);

		if($li_exec==false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_delete_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_exec))
			{
				$lb_valido=true;

			}
			else
			{
				$lb_valido=false;
			}
		}

		$this->io_sql->free_result($li_exec);
		return $lb_valido;
	}

	function uf_sim_obtener_dt_transferencia($as_codemp,$as_numtra,$ad_fecemi,&$ai_totrows,&$ao_object)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_sim_obtener_dt_transferencia
	//	Access:    public
	//	Arguments:
	//  as_codemp    // codigo de empresa
	//  as_numtra    // numero de transferencia
	//  ad_fecemi    // fecha de emision
	//  ai_totrows   // total de filas encontradas
	//  ao_object    // arreglo de objetos para pintar el grid
	//	Returns:		$lb_valido-----> true: operacion exitosa false: operacion no exitosa
	//	Description:  Esta funcion busca los detalles asociados a un  movimientos  en la tabla de  sim_dt_movimiento y los imprime en el grid
	//
	//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sim_dt_transferencia.*,sim_articulo.codunimed,sim_unidadmedida.unidad AS unidades,".
				"       (SELECT denart FROM sim_articulo ".
				"         WHERE sim_dt_transferencia.codart=sim_articulo.codart) AS denart".
				"  FROM sim_dt_transferencia,sim_articulo,sim_unidadmedida".
				" WHERE sim_dt_transferencia.codemp='". $as_codemp ."'".
				"   AND sim_dt_transferencia.numtra='". $as_numtra ."'".
				"   AND sim_dt_transferencia.codart=sim_articulo.codart".
				"   AND sim_articulo.codunimed=sim_unidadmedida.codunimed".
				"   AND sim_dt_transferencia.fecemi='". $ad_fecemi ."'";

		$li_exec=$this->io_sql->select($ls_sql);
		if($li_exec===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_sim_obtener_dt_transferencia ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($li_exec))
			{
					$ls_codart=     $row["codart"];
					$ls_denart=     $row["denart"];
					$ls_unidad=     $row["unidad"];
					$li_unidad=     $row["unidades"];
					$li_cantidad=   $row["cantidad"];
					$li_cosuni=     $row["cosuni"];
					$li_costot=     $row["costot"];
					switch ($ls_unidad)
					{
						case "M":
							$ls_unidadaux="Mayor";
							$li_cantidad= ($li_cantidad/$li_unidad);
							$li_cosuni=($li_cosuni*$li_unidad);
							break;
						case "D":
							$ls_unidadaux="Detal";
							break;
					}
					$ai_totrows=$ai_totrows+1;
					$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."   type=text id=txtdenart".$ai_totrows."   class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' onKeyUp='javascript: ue_validarcomillas(this);' readonly>";
					$ao_object[$ai_totrows][2]="<input name=txtcoduni".$ai_totrows."   type=text id=txtcoduni".$ai_totrows."   class=sin-borde size=14 maxlength=12 value='".$ls_unidadaux."' onKeyUp='javascript: ue_validarcomillas(this);' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='". $li_unidad ."'>";
					$ao_object[$ai_totrows][3]="<input name=txtcantidad".$ai_totrows." type=text id=txtcantidad".$ai_totrows." class=sin-borde size=14 maxlength=12 value='".number_format ($li_cantidad,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$ao_object[$ai_totrows][4]="<input name=txtcosuni".$ai_totrows."   type=text id=txtcosuni".$ai_totrows."   class=sin-borde size=14 maxlength=15 value='".number_format ($li_cosuni,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);'>";
					$ao_object[$ai_totrows][5]="<input name=txtcostot".$ai_totrows."   type=text id=txtcostot".$ai_totrows."   class=sin-borde size=14 maxlength=15 value='".number_format ($li_costot,2,",",".")."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
					$ao_object[$ai_totrows][6]="";
					$ao_object[$ai_totrows][7]="";

			}//while
		}//else
		return $lb_valido;
	}

	function uf_select_metodo(&$ls_metodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_metodo
		//         Access: private
		//      Argumento: $ls_metodo    // metodo de inventario
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que metodo de inventario esta siendo utilizado actualmente.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 09/02/2006 								Fecha �ltima Modificaci�n :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_config";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->transferencia M�TODO->uf_select_metodo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_metodo=$row["metodo"];
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message("No se ha definido la configuraci�n de inventario");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function uf_select_metodo

	function uf_select_movimiento($ls_metodo,&$rs_metodo,$as_codart,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_movimiento
		//         Access: private
		//      Argumento: $ls_metodo    // metodo de inventario
		//                 $rs_metodo    // result set de la operacion del select
		//                 $as_codart    // codigo de articulo
		//                 $as_codalm    // codigo de almac�n
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los movimientos que no han sido reversados y los ordena segun sea el el metodo
	    //				   de inventario (en caso de ser FIFO � LIFO), o saca el promedio si es Costo Promedio Ponderado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 09/02/2006 								Fecha �ltima Modificaci�n :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($ls_metodo=="FIFO")
		{
			if($this->ls_gestor=="MYSQL")
			{
				$ls_sql="SELECT * FROM sim_dt_movimiento".
						" WHERE codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND CONCAT(promov,numdocori) NOT IN".
						" (SELECT CONCAT(promov,numdocori) FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov";
			}
			else
			{
				$ls_sql="SELECT * FROM sim_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND promov || numdocori NOT IN".
						" (SELECT promov || numdocori FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov";
			}

			$rs_metodo=$this->io_sql->select($ls_sql);
		}
		if($ls_metodo=="LIFO")
		{
			if($this->ls_gestor=="MYSQL")
			{
				$ls_sql="SELECT * FROM sim_dt_movimiento".
						" WHERE codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND CONCAT(promov,numdocori) NOT IN".
						" (SELECT CONCAT(promov,numdocori) FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov DESC";
			}
			else
			{
				$ls_sql="SELECT * FROM sim_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND promov || numdocori NOT IN".
						" (SELECT promov || numdocori FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov DESC";
			}
			$rs_metodo=$this->io_sql->select($ls_sql);
		}
		if($ls_metodo=="CPP")
		{
			if($this->ls_gestor=="MYSQL")
			{
				$ls_sql="SELECT Avg(cosart) as cosart".
						" FROM sim_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND CONCAT(promov,numdocori) NOT IN".
						" (SELECT CONCAT(promov,numdocori) FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" ORDER BY nummov DESC";
			}
			else
			{
				$ls_sql="SELECT Avg(cosart) as cosart".
						" FROM sim_dt_movimiento".
						" WHERE  codart='".$as_codart."'".
						"   AND codalm='".$as_codalm."'".
						"   AND promov || numdocori NOT IN".
						" (SELECT promov || numdocori FROM sim_dt_movimiento".
						"   WHERE opeinv ='REV')".
						" GROUP BY cosart,nummov".
						" ORDER BY nummov DESC";
			}
			$rs_metodo=$this->io_sql->select($ls_sql);
		}
		if($rs_metodo===false)
		{
			$this->io_msg->message("CLASE->transferencias M�TODO->uf_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_select_movimiento

	function uf_sim_procesar_dt_movimientotransferencia($as_codemp,$as_nummov,$as_codart,$as_codalm,$as_unidad,$ai_canart,
	                                                    $ai_preuniart,$ad_fecemi,$as_numtra,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_procesar_dt_movimientotransferencia
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa							$as_numorddes // numero de orden de despacho
		//                 $as_codart    // codigo de articulo							$as_codalm    // codigo de almac�n
		//                 $as_unidad    // codigo de unidad M-->Mayor D->Detal		 	$ai_canorisolsep // cantidad de articulos de la SEP
		//                 $ai_canart    // cantidad despachada de articulos			$ai_preuniart    // precio unitario del articulo
		//                 $ai_canoriart // codigo de procedencia del documento			$as_nummov       // numero de movimiento
		//                 $ad_fecdesaux // fecha del despacho							$as_numsol      // numero de la SEP
		//                 $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que verifica que metodo de inventario se esta utilizando y adem�s va buscando los precios unitarios
	    //				   en caso de que no existan suficientes artiulos al mismo precio y procede a llamar al metodo de insert_dt_movimientos
	    //				   y al insert_dt_despacho para ingresarlo en la tabla sim_dt_despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 09/02/2006 								Fecha �ltima Modificaci�n :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_metodo="";
		$rs_metodo="";
		$lb_valido=$this->uf_select_metodo($ls_metodo);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_select_movimiento($ls_metodo,$rs_metodo,$as_codart,$as_codalm);
			if($lb_valido)
			{
				if($ls_metodo!="CPP")
				{
					$lb_break=false;
					$li_diferencia=0;
					$li_i=0;
					while(($row=$this->io_sql->fetch_row($rs_metodo))&&(!$lb_break))
					{
						$li_preuniart=$row["cosart"];
						$ls_numdocori=$row["numdocori"];
						$ls_nummov=$row["nummov"];
						$ls_codalm=$row["codalm"];

						if($this->ls_gestor=="MYSQL")
						{
							$ls_sql="SELECT SUM(CASE opeinv WHEN 'ENT' THEN candesart ELSE -candesart END) total FROM sim_dt_movimiento".
									" WHERE codemp='". $as_codemp ."'".
									"   AND codart='". $as_codart ."'".
									"   AND codalm='". $as_codalm ."'".
									"   AND numdocori='". $ls_numdocori ."'".
									"   AND CONCAT(promov,numdocori) NOT IN".
									" (SELECT CONCAT(promov,numdocori) FROM sim_dt_movimiento".
									"   WHERE opeinv ='REV')".
									" ORDER BY nummov";
						}
						else
						{
							$ls_sql="SELECT SUM(CASE opeinv WHEN 'ENT' THEN candesart ELSE -candesart END) AS total FROM sim_dt_movimiento".
									" WHERE codemp='". $as_codemp ."'".
									"   AND codart='". $as_codart ."'".
									"   AND codalm='". $as_codalm ."'".
									"   AND numdocori='". $ls_numdocori ."'".
									"   AND promov  || numdocori NOT IN".
									" (SELECT promov || numdocori FROM sim_dt_movimiento".
									"   WHERE opeinv ='REV')".
									" GROUP BY nummov".
									" ORDER BY nummov";
						}
						$li_exec1=$this->io_sql->select($ls_sql);
						if($row1=$this->io_sql->fetch_row($li_exec1))
						{
							$li_existencia=$row1["total"];
							if ($li_existencia > 0)
							{
								$lb_encontrado=true;
								$li_i=$li_i + 1;

								if ($li_existencia < $ai_canart)
								{
									$ai_canart= $ai_canart-$li_existencia;


									$lb_valido=$this->uf_sim_disminuir_articuloxmovimiento($as_codemp,$as_codart,$ls_codalm,$ls_nummov,
																							$ls_numdocori,$li_existencia);
									if ($lb_valido)
									{
										$ls_opeinv="SAL";
										$ls_promov="TRA";
										$ls_codprodoc="ALM";
										$li_candesart="0.00";
										$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecemi,
																						  	  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																							  $as_numtra,$li_existencia,$li_preuniart,$ls_promov,
																						  	  $as_numtra,$li_candesart,$ad_fecemi,
																							  $aa_seguridad);
									}

								}  // fin  if ($li_existencia < $ai_canart)
								elseif($li_existencia >= $ai_canart)
								{
									$lb_valido=$this->uf_sim_disminuir_articuloxmovimiento($as_codemp,$as_codart,$ls_codalm,$ls_nummov,
																							$ls_numdocori,$ai_canart);
									if ($lb_valido)
									{
										$ls_opeinv="SAL";
										$ls_promov="TRA";
										$ls_codprodoc="ALM";
										$li_candesart="0.00";
										$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecemi,
																						  	  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																							  $as_numtra,$ai_canart,$li_preuniart,$ls_promov,
																						  	  $as_numtra,$li_candesart,$ad_fecemi,
																							  $aa_seguridad);
										if($lb_valido)
										{
											$lb_break=true;
										}
									}
								}
								if(!$lb_valido)
								{
									$lb_break=true;
								}
							}  // fin  ($li_existencia > 0)
						}  //fin  if($row1=$io_sql->fetch_row($li_exec1))
					}// fin  while(($row=$io_sql->fetch_row($rs_metodo))&&(!$lb_break))
				}// fin  if($ls_metodo!="CPP")
				else
				{
					if($row=$this->io_sql->fetch_row($rs_metodo))
					{
						$li_preuniart=$row["cosart"];
						$ls_numdocori="";
						$ls_opeinv="SAL";
						$ls_promov="TRA";
						$ls_codprodoc="ALM";
						$li_candesart="0.00";
						$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecemi,
																			  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																			  $as_numtra,$ai_canart,$li_preuniart,$ls_promov,
																			  $as_numtra,$li_candesart,$ad_fecemi,
																			  $aa_seguridad);
					}// fin  if($row=$this->io_sql->fetch_row($rs_metodo))
				}// fin  else($ls_metodo!="CPP")
			}
		}
		return $lb_valido;
	}// end  function uf_sim_procesar_dt_movimientotransferencia

	function uf_sim_disminuir_articuloxmovimiento($as_codemp,$as_codart,$as_codalm,$as_nummov,$ls_numdocori,$ai_cantidad)
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
				  " AND   numdocori='" . $ls_numdocori ."'";
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

}
?>
