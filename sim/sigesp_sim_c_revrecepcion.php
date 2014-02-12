<?php
class sigesp_sim_c_revrecepcion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
    var $io_art;
	var $ls_sql;
    var $io_mov;

	function sigesp_sim_c_revrecepcion()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("sigesp_sim_c_articuloxalmacen.php");
		require_once("sigesp_sim_c_movimientoinventario.php");
		require_once("sigesp_sim_c_recepcion.php");

		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_fun=    new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_msg=    new class_mensajes();
		$this->io_funcion=new class_funciones();
		$this->io_mov    =new sigesp_sim_c_movimientoinventario();
		$this->io_art    =new sigesp_sim_c_articuloxalmacen();
		$this->io_rec=new sigesp_sim_c_recepcion();
	}


	function uf_sim_select_recepcion($as_codemp,$as_numordcom,$as_numconrec,&$as_codalm,$as_codtie,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_recepcion
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepci�n
		//  			   $as_codalm    // codigo de almac�n
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de entrada de suministo a almacen y obtiene el almacen al que
		//				   fueron enviado los articulos en la tabla de  sim_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_recepcion  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND numordcom='".$as_numordcom."'".
				  " AND codtiend='".$as_codtie."'".
				  " AND cod_pro='".$as_codpro."'".
				  " AND numconrec='".$as_numconrec."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion M�TODO->uf_sim_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function uf_sim_select_recepcion

function uf_sim_select_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec,$as_codtie,$as_codpro,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_recepcion
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepci�n
		//  			   $rs_data     // resulset del select
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de recepcion de  suministros a almacen
		//				   en la tabla de  sim_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_dt_recepcion".
				" WHERE codemp  = '".$as_codemp ."'  ".
				" AND numordcom = '".$as_numordcom ."'".
				" AND codtiend  = '".$as_codtie ."'  ".
				" AND cod_pro   = '".$as_codpro ."'  ".
				" AND numconrec = '".$as_numconrec."'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion M�TODO->uf_sim_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	}  // end  function uf_sim_select_dt_recepcion

	
	function uf_sim_delete_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_dt_recepcion
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepci�n
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un detalle de recepcion para un reverso en la tabla sim_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sim_dt_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND numconrec='". $as_numconrec ."'";
		$li_row=$this->io_sql->execute($ls_sql);

		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_delete_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	} // end  function uf_sim_delete_dt_recepcion

	function uf_sim_delete_recepcion($as_codemp,$as_numordcom,$as_numconrec)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_recepcion
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepci�n
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un maestro de recepcion para un reverso en la tabla sim_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sim_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND numconrec='". $as_numconrec ."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion M�TODO->uf_sim_delete_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sim_delete_recepcion

	function uf_sim_obtener_recepcion(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_obtener_recepcion
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca las entradas de suministros a los almacenes en la tabla de sim_recepcion para luego
		//				   mprimirlos en el grid de  la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_func=  new class_funciones();
		$lb_valido=true;
		$ls_sql= "SELECT * FROM sim_recepcion".
				  " WHERE estrevrec=1".
				  " ORDER BY numordcom, numconrec ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_obtener_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function uf_sim_update_articulos($as_codemp,$as_numordcom,$as_numconrec,$as_codalm,$as_codtie,$as_codpro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_articulos
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero concecutivo de recepcion
		//  			   $as_codalm    // codigo de almac�n
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos de una recepcion para disminuirles en las existencias  la cantidad que
		//				   se esta reversando.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=true;
		$ls_sql= "SELECT * FROM sim_dt_recepcion".
				" WHERE codemp ='". $as_codemp ."'".
				" AND numordcom='". $as_numordcom ."'".
				" AND codtiend ='". $as_codtie."'".
				" AND cod_pro   ='". $as_codpro."'".
				" AND numconrec='". $as_numconrec ."'";

		//print 'update articulos->'.$ls_sql.'<br>';
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_update_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_break=false;

			while(($row=$this->io_sql->fetch_row($rs_data))&&(!$lb_break))
			{
				$ls_codart= $row["codart"];
				$li_canart= $row["canart"];
				$ai_preuniart=$row["preuniart"];
				$lb_valido=$this->io_art->uf_sim_disminuir_articuloxalmacen($as_codemp,$ls_codart,$as_codalm,$li_canart,$as_codpro,$as_codtie,$aa_seguridad);
				if(!$lb_valido)
				{
					$lb_break=true;
				}else
				{
					//$lb_valido=$this->io_art->uf_sim_actualizar_cantidad_articulos($as_codemp,$ls_codart,$as_codtie,$as_codpro,$as_codalm,$aa_seguridad);
				}

				if(substr($ls_codart,0,5)=='0000V'){

				$lb_valido=$this->io_rec->uf_sim_verificar_existencia($as_codemp,$ls_codart,$as_codpro,&$as_exiant,$as_codtie);
				if ($lb_valido)
				{
					if ($as_exiant<=0)
					{
						$lb_valido=$this->uf_sim_ultimo_cosproart($as_codemp,$ls_codart,$as_codpro,&$li_cosproart,&$li_ultcosto,$as_codtie);
						if (!$lb_valido)
						{
							$li_ultcosto=0.0000;
						}
						$li_cosproart=0.0000;
						$lb_valido=$this->uf_sim_update_costo_prom_producto($as_codemp,$ls_codart,$li_cosproart,$li_ultcosto,$as_codtie);
					}
					else
					{
						$lb_valido=$this->uf_sim_ultimo_cosproart($as_codemp,$ls_codart,$as_codpro,&$li_cosproart,&$li_ultcosto,$as_codtie);
						if (!$lb_valido)
						{
							$li_ultcosto=0;
							if($li_cosproart=="" || is_null($li_cosproart))
							{
								$li_cosproart=0;
							}						
						}
						$lb_valido=$this->uf_sim_update_costo_prom_producto($as_codemp,$ls_codart,$li_cosproart,$li_ultcosto,$as_codtie);
					}
				}
				else
				{
				$li_cosproart=0.0000;
				$li_ultcosto=0.0000;
				//print 'paso'.$ls_codart;
				$lb_valido=$this->uf_sim_update_costo_prom_producto($as_codemp,$ls_codart,$li_cosproart,$li_ultcosto,$as_codtie);
				}

				}


			}//while

		}
                
		return $lb_valido;
	}  // end  function uf_sim_update_articulos
	function uf_sim_ultimo_cosproart($as_codemp,$as_codart,$as_codprov,&$li_cosproart,&$li_ultcosto,$ls_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_verificar_existencia
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart // Articulo
		//  			   $as_codprov //Proveedor
		//  			   $as_codalm // Almacen donde se encuentra el Articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica existencia del articulo
		//	   Creado Por: Ing. Zulheymar Rodríguez
		// Fecha Creaci�n: 06/04/2009							Fecha �ltima Modificaci�n : 06/04/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		/*$ls_sql = "SELECT MAX(COALESCE(dt.cosproart,0)) as ultcosproart,MAX(COALESCE(dt.preuniart,0)) as ultcosto FROM sim_dt_recepcion dt, sim_recepcion r ".
		" WHERE r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND ".
		" r.codtiend=dt.codtiend AND r.estrevrec<>'0' AND ".
		" dt.codemp='".$as_codemp."' AND dt.codart='".$as_codart."' AND dt.cod_pro='".$as_codprov."' ".
		" AND dt.codtiend='".$ls_codtie."' AND r.numconrec=(SELECT MAX(dt.numconrec)FROM sim_dt_recepcion dt, sim_recepcion r WHERE r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND
r.numconrec=dt.numconrec AND r.codtiend=dt.codtiend AND r.estrevrec<>'0');";*/
		$ls_sql = "SELECT MAX(COALESCE(dt.cosproart,0)) as ultcosproart,MAX(COALESCE(dt.preuniart,0)) as ultcosto FROM sim_dt_recepcion dt, sim_recepcion r ".
		" WHERE r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND ".
		" r.codtiend=dt.codtiend AND r.estrevrec<>'0' AND ".
		" dt.codemp='".$as_codemp."' AND dt.codart='".$as_codart."' ".
		" AND dt.codtiend='".$ls_codtie."' AND r.numconrec=(SELECT MAX(dt.numconrec)FROM sim_dt_recepcion dt, sim_recepcion r WHERE r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND " .
		" r.numconrec=dt.numconrec AND r.codtiend=dt.codtiend AND r.estrevrec<>'0' AND dt.codart='".$as_codart."' );";
		//$this->io_sql->free_result($rs_data);
		$rs_data=$this->io_sql->select($ls_sql);
		//var_dump($rs_data);
		if($rs_data==false)
		{
			$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_ultimo_cosproart ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$row=$this->io_sql->fetch_row($rs_data);
			if($row)
			{
				$li_cosproart=$row["ultcosproart"];
				$li_ultcosto=$row["ultcosto"];
				if ($li_cosproart<>NULL && $li_ultcosto<>NULL && $li_cosproart!="" && $li_ultcosto!="")
				{
					$lb_valido=true;
				}
				else
				{
					$lb_valido=false;
				}
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_sim_select_recepcion
	
	function uf_sim_select_articulo($as_codemp,$as_codart)
	{
		$lb_valido=false;
		$ls_sql= "SELECT * FROM sim_articulo ".
				" WHERE codemp ='". $as_codemp ."' ".
				" AND codart='". $as_codart ."' ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_select_articulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}//if
		}
		return $lb_valido;
	}
	
	function uf_sim_update_costo_prom_producto($as_codemp,$as_codart,$ai_cosproart,$ai_ultcosto,$ls_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_costo_promedio
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//  			   $as_codalm     // codigo de almacen
		//  			   $ai_cosproart  // costo promedio por articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el costo promedio en un determinado articulo en la tabla sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $li_exce=-1;
		 $lb_existe=true;
		 $lb_existe=$this->uf_sim_select_articulo($as_codemp,$as_codart);
		
		if($lb_existe)
		{
			 $ls_sql = "UPDATE sfc_producto".
			 		   "   SET cosproart=". $ai_cosproart .", ".
					   " ultcosart=".$ai_ultcosto." ".
					   " WHERE codemp='" . $as_codemp ."'".
					   "   AND codart='" . $as_codart ."'".
					   " AND codtiend='".$ls_codtie."'";

				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_update_costo_prom_prod ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;

				}
				else
				{
					$lb_valido=true;
				}
		}
		return $lb_valido;
	} // end  function uf_sim_update_costo_promedio
	function uf_sim_select_ordencompra($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_ordencompra
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica la existencia de una orden de compra en la tabla de  soc_ordencompra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM soc_ordencompra  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND numordcom='".$as_numordcom."'".
				  " AND estpenalm=1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->revrecepcion M�TODO->uf_sim_select_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function uf_sim_update_ordencompra($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ordencompra
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la orden de compra "estpenalm" que indica si una orden de compra ha sido
		//			       completa o no. En la tabla soc_ordencompra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE soc_ordencompra SET  estpenalm=0".
					" WHERE codemp='" . $as_codemp ."' ".
					" AND numordcom='" . $as_numordcom ."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->revrecepcion M�TODO->uf_sim_update_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*			$ls_evento="UPDATE";
			$ls_descripcion ="Modific� el estatus de la orden de compra numero ".$as_numordcom." Asociada a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion); */
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
	  return $lb_valido;
	} // end  function uf_sim_update_ordencompra

	function uf_sim_update_status_recepcion($as_codemp,$as_numordcom,$as_numconrec,$as_codtie,$as_codpro,$aa_seguridad,$ls_obsAnul)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_status_recepcion
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero consecutivo de recepci�n
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de una recepcion de suministros a alamc�n "estrevrec" en la tabla
		//			       sim_recepcion  la cual indica que esta fue reversada.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 11/02/2006							Fecha �ltima Modificaci�n : 13/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
                 $fechaanu = date('Y/m/d');
                 $cadenaAnular = ($ls_obsAnul=="")?"":",obsanu     ='".$ls_obsAnul."', fecanu='$fechaanu', codusu ='".$_SESSION["la_logusr"]."'";
		 //$this->io_sql=    new class_sql($this->con);
		$ls_sql= " UPDATE sim_recepcion SET estrevrec = 0 $cadenaAnular ".
			      " WHERE  codemp ='".$as_codemp."'        ".
				  " AND    numordcom='".$as_numordcom."'   ".
                                  " AND    codtiend ='".$as_codtie."'      ".
				  " AND    cod_pro ='".$as_codpro."'       ".
				  " AND    numconrec='".$as_numconrec."'   ";
		//print 'esta_recep->'.$ls_sql.'<br>';
		$li_row = $this->io_sql->execute($ls_sql);
                
		if($li_row===false)
		{
                       
                    	//$this->io_msg->message("CLASE->revrecepcion METODO->uf_sim_update_status_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
                        $this->io_msg->message($ls_sql);
			$lb_valido=false;
		}
		else
		{
			
                        $lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Realizo el reverso de la recepcion numero ".$as_numconrec." Asociada a la Orden de compra ".$as_numordcom.
							 " de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
	  return $lb_valido;
	} // end  function uf_sim_update_status_recepcion

	function uf_sim_actualizarestatus($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_actualizarestatus
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que activa el proceso de verificar la existencia de una orden de compra y en caso afirmativo
		//			       procede a actualizar el estatus de pendientes de almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 11/02/2006							Fecha �ltima Modificaci�n : 13/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_sim_select_ordencompra($as_codemp,$as_numordcom);
		if($lb_existe)
		{
			$lb_valido=$this->uf_sim_update_ordencompra($as_codemp,$as_numordcom);
		}
		return $lb_valido;
	}  // end  function uf_sim_actualizarestatus

	function uf_sim_crear_movimientos($as_codemp,$ad_fecrev,$as_codalm,$as_opeinv,$as_codprodoc,
	         $as_numordcom,$as_promov,$as_numconrec,$ai_candesart,$as_codusu,$as_codtie,$as_cod_pro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_actualizarestatus
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ad_fecrev    // fecha en la cual se hizo el reverso
		//  			   $as_codalm    // codigo de almacen
		//  			   $as_opeinv    // operacion de inventario
		//  			   $as_codprodoc // codigo de procedencia del documento
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_promov    // codigo de la procedencia del movimiento
		//  			   $as_numconrec // numero consecutivo de recepciï¿½n
		//  			   $ai_candesart // cantidad restante de articulos en el movimiento
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion en la que se busca los datos restantes para crear un movimiento de inventario y luego de obtenerlos
		//			       hace el llamado a la funcion de la clase sigesp_sim_c_movimientoinventario que lo inserta
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaciï¿½n: 14/02/2006							Fecha ï¿½ltima Modificaciï¿½n : 14/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomsol="Reverso";
		$ls_nummov="";
		$lb_valido=$this->io_mov->uf_sim_insert_movimiento($ls_nummov,$ad_fecrev,$ls_nomsol,$as_codusu,$aa_seguridad,$as_codtie);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_sim_select_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec,$as_codtie,$as_cod_pro,$rs_data);
			if($lb_valido)
			{
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_codart=$row["codart"];
					$li_canart=$row["canart"];
					$li_cosart=$row["preuniart"];
					$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$as_codalm,$as_opeinv,
																		  $as_codprodoc,$as_numordcom,$li_canart,$li_cosart,$as_promov,
																		  $as_numconrec,$ai_candesart,$ad_fecrev,$aa_seguridad,$as_codtie,$as_cod_pro);

					if($lb_valido==false)
					{
						break;
					}
				}  //fin while($row=$this->io_sql->fetch_row($rs_dtrec))
				$this->io_sql->free_result($rs_data);
			}
		} // fin if($lb_valido) uf_sim_insert_movimiento
		return $lb_valido;
	}// end function uf_sim_crear_movimientos

	function uf_sim_crear_dt_mov($as_codemp,$ls_nummov,$ad_fecrev,$as_codalm,$as_opeinv,$as_codprodoc,$as_numordcom,
								 $as_promov,$as_numconrec,$ai_candesart,$as_codusu,$as_codtie,$as_cod_pro,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_crear_dt_mov
		//         Access: public (sigesp_sim_p_revrecepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $ls_nummov    // Numero del movimiento en sim_movimiento
		//  			   $ad_fecrev    // fecha en la cual se hizo el reverso
		//  			   $as_codalm    // codigo de almacen
		//  			   $as_opeinv    // operacion de inventario
		//  			   $as_codprodoc // codigo de procedencia del documento
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_promov    // codigo de la procedencia del movimiento
		//  			   $as_numconrec // numero consecutivo de recepci�n
		//  			   $ai_candesart // cantidad restante de articulos en el movimiento
		//  			   $as_codusu    // codigo de usuario
		//  			   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion en la que se busca los datos restantes para crear un movimiento de inventario, luego inserta
		//			        los detalles correspondientes y elimina los registros asociados a la recepci�n
		//	   Creado Por: Ing. Luis Alberto Alvarez
		// Fecha Creaci�n: 25/02/2008							Fecha �ltima Modificaci�n:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomsol="Reverso";

		$lb_valido=$this->uf_sim_select_recepcion($as_codemp,$as_numordcom,$as_numconrec,$as_codalm1,$as_codtie,$as_cod_pro,$rs_data);
		if($lb_valido)
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=$row["codart"];
				$li_canart=$row["canart"];
				$li_cosart=$row["preuniart"];
				$lb_valido=$this->io_mov->uf_sim_insert_dt_movimiento($as_codemp,$ls_nummov,$ad_fecrev,$ls_codart,$as_codalm,$as_opeinv, $as_codprodoc,
			 							$as_numordcom,$li_canart,$li_cosart,$as_promov, $as_numconrec,$ai_candesart,$ad_fecrev,$aa_seguridad,$as_codtie,$as_cod_pro);
				if($lb_valido==false)
				{
					break;
				}
			}  //fin while($row=$this->io_sql->fetch_row($rs_dtrec))
			$this->io_sql->free_result($rs_data);
		}

		
		$ls_sql= "DELETE FROM sim_dt_recepcion".
				  " WHERE sim_dt_recepcion.codemp    ='".$as_codemp."'   ".
				  "   AND sim_dt_recepcion.numordcom ='".$as_numordcom."'
				      AND sim_dt_recepcion.codtiend  ='".$as_codtie."'   ".
				  "   AND sim_dt_recepcion.numconrec ='".$as_numconrec."'";

		//print 'delete_dt_recep->'.$ls_sql.'<br>';
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion METODO->uf_sim_crear_dt_mov ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//$this->io_sql->rollback();
		}
		else
		{
			//$this->io_sql->rollback();
			//$this->io_sql->commit();
			$ls_sql= "DELETE FROM sim_recepcion".
					  " WHERE sim_recepcion.codemp    ='".$as_codemp."'   ".
					  "   AND sim_recepcion.numordcom ='".$as_numordcom."'
				          AND sim_recepcion.codtiend  ='".$as_codtie."'   ".
					  "   AND sim_recepcion.numconrec ='".$as_numconrec."'";

			//print 'delete_dt_recep->'.$ls_sql.'<br>';
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->recepcion METODO->uf_sim_crear_dt_mov ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				//$this->io_sql->rollback();
			}
			else
			{
				//$this->io_sql->rollback();
				//$this->io_sql->commit();
			}
		}
		

		return $lb_valido;
	}// end function uf_sim_crear_dt_mov


}//end  class sigesp_sim_c_revrecepcion
?>
