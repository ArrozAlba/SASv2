<?php
require_once("../shared/class_folder/class_sql.php");
class sigesp_sim_c_recepcion
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_recepcion()
	{
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		$in=               new sigesp_include();
		$this->con=        $in->uf_conectar();
		$this->io_sql=     new class_sql($this->con);
		$this->seguridad=  new sigesp_c_seguridad();
		$this->fun=        new class_funciones_db($this->con);
		$this->io_msg =    new class_mensajes();
		$this->DS=         new class_datastore();
		$this->io_funcion= new class_funciones();
	}

	function uf_sim_select_recepcion($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_recepcion
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que exista una entrada de suministo a almacen en la tabla de  sim_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_recepcion  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numordcom='".$as_numordcom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sim_select_recepcion

	function uf_sim_verificar_existencia($as_codemp,$as_codart,$as_codprov,&$as_existencia,$ls_codtie)
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
		/*$ls_sql = "SELECT COALESCE(existencia,0) as existencia FROM sim_articuloalmacen WHERE".
		" codemp='".$as_codemp."' AND codart='".$as_codart."' AND cod_pro='".$as_codprov."' AND codtiend='".$ls_codtie."';";*/
		$ls_sql = "SELECT COALESCE(existencia,0) as existencia FROM sim_articuloalmacen WHERE".
		" codemp='".$as_codemp."' AND codart='".$as_codart."' AND codtiend='".$ls_codtie."';";
		//print 'existencia-->'.$ls_sql.'<br>';
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_existencia=$row["existencia"];
				//print 'existencia-->'.$row["existencia"].'<br>';
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_sim_select_recepcion

	function uf_sim_ultimo_cosproart_cero($as_codemp,$as_codart,$as_codprov,&$ls_numconrec,$ls_codtie)
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
		/*$ls_sql = "SELECT MAX(dt.numconrec) as ultimo FROM sim_dt_recepcion dt, sim_recepcion r ".
		" WHERE r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND ".
		" r.codtiend=dt.codtiend AND r.estrevrec<>'0' AND ".
		" dt.exiant=0 AND  dt.codemp='".$as_codemp."' AND dt.codart='".$as_codart."' AND dt.cod_pro='".$as_codprov."' ".
		" AND dt.codtiend='".$ls_codtie."';";*/
		$ls_sql = "SELECT MAX(dt.numconrec) as ultimo FROM sim_dt_recepcion dt, sim_recepcion r ".
		" WHERE r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND ".
		" r.codtiend=dt.codtiend AND r.estrevrec<>'0' AND ".
		" dt.exiant=0 AND  dt.codemp='".$as_codemp."' AND dt.codart='".$as_codart."' AND dt.codtiend='".$ls_codtie."';";
		//print 'ultimocostos-> '.$ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_select_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numconrec=$row["ultimo"];
				//print $ls_numconrec;
				if ($ls_numconrec<>NULL)
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

	function uf_sim_insert_recepcion($as_codemp,$as_numordcom,$as_codpro,$as_codalm,$ad_fecrec,$as_obsrec,$as_codusu,
	                                 $as_estpro,$as_estrec,&$as_numconrec,$as_codtie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_recepcion
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa               $as_numordcom // numero de la orden de compra/factura
		// 				   $as_codpro    // codigo de proveedor			    $as_codalm    // codigo de almacen
		//				   $ad_fecrec    // fecha de recepcion              $as_obsrec    // observacion de la recepcion
		//				   $as_codusu    // codigo del usuario	 			$aa_seguridad // arreglo de registro de seguridad
		//				   $as_estpro    // estatus de la procedencia: 1--> Factura, 0--> Orden de compra
		//				   $as_estrec    // estatus de la recepcion:   0--> Parcial, 1--> Completa
		//				   $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que inserta  los  datos  maestros  de  una  entrada  de  suministros a almacen  y genera
		//				   el numero  de  comprobante  de  la  recepcion  de manera que puedan existir varias recepciones para una
		//				   misma orden de compra, en la tabla sim_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$ls_emp=true;
		$ls_tabla="sim_recepcion";
		$ls_columna="numconrec";
		$ls_estrevrec="1";
		$as_numconrec=$io_fun->uf_generar_codigo($ls_emp,$as_codemp,$ls_tabla,$ls_columna);
		$ls_sql="INSERT INTO sim_recepcion (codemp,numordcom,cod_pro,codalm,fecrec,obsrec,codusu,estpro,estrec,numconrec,estrevrec,codtiend)".
				" VALUES ('".$as_codemp."','".$as_numordcom."','".$as_codpro."','".$as_codalm."','".$ad_fecrec."','".$as_obsrec."',".
				"         '".$as_codusu."','".$as_estpro."','".$as_estrec."','".$as_numconrec."','".$ls_estrevrec."','".$as_codtie."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_insert_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� una Entrada de Suminisros a almacen proveniente del Documento ".$as_numordcom.", y fue enviado al Almacen ".$as_codalm.
								 " Asociado a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
	// Comentado por manejar las ordenes de compra de manera manual	
	/*	if(($lb_valido)&&($as_estrec==1)&&($as_estpro==0))
		{
			$lb_valido=$this->uf_sim_update_ordencompra($as_codemp,$as_numordcom,$aa_seguridad);
		}*/
		return $lb_valido;
	} // end function uf_sim_insert_recepcion

	function uf_sim_select_dt_recepcion($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_recepcion
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de recepcion de suministros a almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM sim_dt_recepcion".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numordcom NOT IN (SELECT numordcom FROM sim_recepcion WHERE estrevrec='0')".
				"   AND numordcom='". $as_numordcom ."'";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sim_select_dt_recepcion

	function uf_sim_insert_dt_recepcion($as_codemp,$as_numordcom,$as_codart,$as_unidad,$ai_canart,$ai_penart,$ai_preuniart,
	                                    $ai_monsubart,$ai_montotart,$ai_orden,$ai_canoriart,$as_numconrec,$as_codtie,$as_codpro,$aa_seguridad)

	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_dt_recepcion
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa               $as_numordcom // numero de la orden de compra/factura
		// 				   $as_codart    // codigo de articulo			    $as_unidad    // codigo de unidad M-->Mayor D->Detal
		//				   $ai_canart    // cantidad recibida de articulos  $ai_penart    // cantidad pendiente de articulos por recibir
		//				   $ai_preuniart // precio unitario del articulo	$ai_monsubart // monto sub-total por articulo
		//				   $ai_montotart // monto total de articulo			$ai_orden     // orden consecutivo de registro
		//				   $as_estrec    // estatus de la recepcion:   0--> Parcial, 1--> Completa
		//				   $ai_canoriart // codigo de procedencia del documento
		//				   $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//				   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de recepcion de articulos a almacen sociado a su respectivo
		//				   maestro en la tabla de  sim_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido_costo=$this->uf_sim_ultimo_cosproart_cero($as_codemp,$as_codart,$as_codpro,&$ls_numconrec,$as_codtie);
		$lb_validoexiste=$this->uf_sim_verificar_existencia($as_codemp,$as_codart,$as_codpro,&$as_existencia,$as_codtie);
		if(!$lb_validoexiste){
			$as_existencia=0;
		}
			$ls_sql="INSERT INTO sim_dt_recepcion (codemp,numordcom,codart,unidad,canart,penart,preuniart,monsubart,montotart,".
					"                              orden,canoriart,numconrec,codtiend,cod_pro,exiant)".
					" VALUES ('".$as_codemp."','".$as_numordcom."','".$as_codart."','".$as_unidad."','".$ai_canart."',".
					"         '".$ai_penart."','".$ai_preuniart."','".$ai_monsubart."','".$ai_montotart."','".$ai_orden."',".
					"         '".$ai_canoriart."','".$as_numconrec."','".$as_codtie."','".$as_codpro."',$as_existencia)";

			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->recepcion METODO->uf_sim_insert_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				print $this->io_sql->message;
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Permitio una entrada a ".$ai_canart." Articulos ".$as_codart." Asociado a la Orden de Compra ".$as_numordcom." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}

			//print $as_codart." -- ".substr($as_codart,0,5);
			if(substr($as_codart,0,5)=='0000V'){
				$lb_valido=$this->uf_sim_update_ultimocosto($as_codemp,$as_codart,$ai_preuniart,$as_codtie);
				if($lb_valido)
				{
					if ($lb_valido_costo)
					{
						$lb_valido=$this->uf_sim_actualizar_costo_promedio($as_codemp,$as_codart,$as_codtie,$as_numordcom,$as_codpro,$as_numconrec,$ls_numconrec,$ai_preuniart);
					}
					else
					{
					//print 'paso';
					$ls_numconrec='---';
					$lb_valido=$this->uf_sim_actualizar_costo_promedio($as_codemp,$as_codart,$as_codtie,$as_numordcom,$as_codpro,$as_numconrec,$ls_numconrec,$ai_preuniart);
					}
				}
			}

		return $lb_valido;
	}  // end   function uf_sim_insert_dt_recepcion

	function uf_sim_obtener_dt_pendiente($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_obtener_dt_pendiente
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una orden de compra ordenados por el campo "orden" en la
		//				   tabla de soc_dt_bienes, y por articulo busca en la tabla sim_dt_recepcion los pendientes asociados a esos
		//				   articulos para luego imprimirlos en el grid de la pagina exepto aquellos que ya se recibieron por completo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT * FROM soc_dt_bienes".
				 " WHERE codemp='". $as_codemp ."'".
				 "   AND numordcom='". $as_numordcom ."'".
				 " ORDER BY orden";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_obtener_dt_pendiente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ls_gestor=$_SESSION["ls_gestor"];
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=$row["codart"];
				if($ls_gestor=="ORACLE")
				{
					$ls_sql= "SELECT sim_dt_recepcion.*,sim_articulo.codunimed,".
							  "     (SELECT unidad FROM sim_unidadmedida ".
							  "	      WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidades,".
							  "     (SELECT denart FROM sim_articulo".
							  "       WHERE sim_dt_recepcion.codart=sim_articulo.codart) AS denart".
							  "  FROM sim_dt_recepcion, sim_recepcion,sim_articulo".
							  " WHERE  sim_dt_recepcion.codemp=sim_recepcion.codemp".
							  "   AND sim_dt_recepcion.codart=sim_articulo.codart".
							  "   AND sim_dt_recepcion.numordcom=sim_recepcion.numordcom".
							  "   AND sim_dt_recepcion.numconrec=sim_recepcion.numconrec ".
							  "   AND sim_dt_recepcion.codemp='". $as_codemp ."'".
							  "   AND sim_dt_recepcion.numordcom='". $as_numordcom ."'".
							  "   AND sim_recepcion.estrec=0".
							  "   AND sim_dt_recepcion.codart='". $ls_codart ."'".
							  " ORDER BY sim_dt_recepcion.numconrec DESC ROWNUM <= 1";
				}
				else
				{
					$ls_sql= "SELECT sim_dt_recepcion.*,sim_articulo.codunimed,".
							  "     (SELECT unidad FROM sim_unidadmedida ".
							  "	      WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidades,".
							  "     (SELECT denart FROM sim_articulo".
							  "       WHERE sim_dt_recepcion.codart=sim_articulo.codart) AS denart".
							  "  FROM sim_dt_recepcion, sim_recepcion,sim_articulo".
							  " WHERE  sim_dt_recepcion.codemp=sim_recepcion.codemp".
							  "   AND sim_dt_recepcion.codart=sim_articulo.codart".
							  "   AND sim_dt_recepcion.numordcom=sim_recepcion.numordcom".
							  "   AND sim_dt_recepcion.numconrec=sim_recepcion.numconrec ".
							  "   AND sim_dt_recepcion.codemp='". $as_codemp ."'".
							  "   AND sim_dt_recepcion.numordcom='". $as_numordcom ."'".
							  "   AND sim_recepcion.estrec=0".
							  "   AND sim_dt_recepcion.codart='". $ls_codart ."'".
							  " ORDER BY sim_dt_recepcion.numconrec DESC LIMIT  1";
				}
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_obtener_dt_pendiente ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					while($row=$this->io_sql->fetch_row($rs_data1))
					{
						$ls_codart=    $row["codart"];
						$ls_denart=    $row["denart"];
						$ls_unidad=    $row["unidad"];
						$li_unidad=    $row["unidades"];
						$li_preuniart= $row["preuniart"];
						$li_penart=    $row["penart"];
						$li_canoriart= $row["canoriart"];
						$li_canart=    "";
						$li_montotart= "";
						switch ($ls_unidad)
						{
							case "M":
								$ls_unidadaux="Mayor";
								break;
							case "D":
								$ls_unidadaux="Detal";
								break;
						}
						if($li_penart!=0.00)
						{
							$ai_totrows=$ai_totrows+1;
							$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
							$ao_object[$ai_totrows][2]="<input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='".$li_unidad."'>";
							$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."'  readonly>";
							$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur='javascript: ue_calcularpendiente(".$ai_totrows.");'>";
							$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_penart,2,",",".")."'  onKeyPress=return(ue_formatonumero(this,'.',',',event)); readonly><input name='hidpendiente".$ai_totrows."' type='hidden' id='hidpendiente".$ai_totrows."' value='".$li_penart."'>";
							$ao_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
							$ao_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
						}
					}//while
				}//else
			}//while($row=$this->io_sql->fetch_row($li_exec))
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_sim_obtener_dt_pendiente

	function uf_sim_obtener_dt_bienes($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_obtener_dt_bienes
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una nueva orden de compra ordenados por el campo "orden" en la
		//				   tabla de  soc_dt_bienes e imprime los resultados obtenidos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT soc_dt_bienes.*,sim_articulo.codunimed,".
				"       (SELECT denart FROM sim_articulo".
				"         WHERE soc_dt_bienes.codart=sim_articulo.codart) AS denart,".
			    "       (SELECT unidad FROM sim_unidadmedida ".
			    "	      WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidades".
				"  FROM soc_dt_bienes,sim_articulo".
				" WHERE soc_dt_bienes.codemp='". $as_codemp ."'".
 			    "   AND soc_dt_bienes.codart=sim_articulo.codart".
				"   AND numordcom='".$as_numordcom."'".
				" ORDER BY orden";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion METODO->uf_sim_obtener_dt_bienes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=    $row["codart"];
				$ls_denart=    $row["denart"];
				$ls_unidad=    $row["unidad"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_canoriart= $row["canart"];
				$li_canart=    "";
				$li_montotart= "";
				$li_penart=    "";
				switch ($ls_unidad)
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='". $li_unidad ."'>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".$li_canart."'  onKeyPress=return(ue_formatonumero(this,'.',',',event));  onBlur='javascript: ue_calcularpendiente(".$ai_totrows.");'>";
				$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_penart,2,",",".")."' readonly><input name='hidpendiente".$ai_totrows."' type='hidden' id='hidpendiente".$ai_totrows."' value='".$li_penart."'>";
				$ao_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".$li_montotart."' style='text-align:right' readonly>";
			}//while
		}//else
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end function uf_sim_obtener_dt_bienes

	function uf_sim_obtener_dt_orden($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_obtener_dt_orden
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que deacuerdo a que si una orden de compra es nueva  � no, procesa la busqueda
		//				   e los articulos de forma diferente.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe= $this->uf_sim_select_dt_recepcion($as_codemp,$as_numordcom);
		if($lb_existe)
		{
			$lb_valido=$this->uf_sim_obtener_dt_pendiente($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object);
		}
		else
		{
			$lb_valido=$this->uf_sim_obtener_dt_bienes($as_codemp,$as_numordcom,&$ai_totrows,&$ao_object);
		}
		return $lb_valido;
	} // end  function uf_sim_obtener_dt_orden

	function uf_sim_update_ordencompra($as_codemp,$as_numordcom,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ordencompra
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la orden de compra estpenalm que indica si una orden de compra
		//				   ha sido completa o no. En la tabla soc_ordencompra.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE soc_ordencompra".
		 		   "   SET estpenalm=1".
				   " WHERE codemp='" . $as_codemp ."' ".
				   "   AND numordcom='" . $as_numordcom ."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion METODO->uf_sim_update_ordencompra ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end function uf_sim_update_ordencompra

	function uf_sim_obtener_dt_recepcion($as_codemp,$as_numordcom,$as_numconrec,$as_radiotipo,&$ai_totrows,&$ai_totentsum,$as_codtie,$as_codpro,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_obtener_dt_recepcion
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//  			   $as_numconrec // numero consecutivo de recepcion
		//  			   $ai_totrows   // total de filas encontradas
		//  			   $ai_totentsum // total de la recepcion
		//  			   $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion  que busca los articulos asociados a recepcion en la tabla sim_dt_recepcion para luego
		//                 imprimirlos en el grid de  la pagina exepto que ya se recibieron por completo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 12/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "SELECT sim_dt_recepcion.*,sim_articulo.codunimed,".
				  "      (SELECT unidad FROM sim_unidadmedida ".
				  "	       WHERE sim_unidadmedida.codunimed = sim_articulo.codunimed) AS unidades,".
				  "      (SELECT denart FROM sim_articulo".
				  "        WHERE sim_dt_recepcion.codart=sim_articulo.codart) AS denart".
				  "  FROM sim_dt_recepcion, sim_recepcion,sim_articulo".
				  " WHERE  sim_dt_recepcion.codemp=sim_recepcion.codemp".
				  "   AND sim_dt_recepcion.codart=sim_articulo.codart".
				  "   AND sim_dt_recepcion.numordcom=sim_recepcion.numordcom".
				  "   AND sim_dt_recepcion.numconrec=sim_recepcion.numconrec ".
				  "   AND sim_dt_recepcion.codemp='".$as_codemp."'".
				  "   AND sim_dt_recepcion.numordcom='".$as_numordcom."'".
				  "   AND sim_dt_recepcion.numconrec='".$as_numconrec."'".
  				  "   AND sim_dt_recepcion.codtiend='".$as_codtie."'".
				  "   AND sim_dt_recepcion.cod_pro='".$as_codpro."'".
				  " ORDER BY sim_dt_recepcion.numconrec";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->recepcion METODO->uf_sim_obtener_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$ai_totrows=0;
			$ai_totentsum=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=    $row["codart"];
				$ls_denart=    $row["denart"];
				$ls_unidad=    $row["unidad"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_penart=    $row["penart"];
				$li_canoriart= $row["canoriart"];
				$li_canart=    $row["canart"];
				$li_montotart= $row["montotart"];
				switch ($ls_unidad)
				{
					case "M":
						$ls_unidadaux="Mayor";
						$li_canart= ($li_canart/$li_unidad);
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				$ai_totentsum=$ai_totentsum+$li_montotart;
				$ai_totrows=$ai_totrows+1;
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."    type=text id=txtdenart".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_denart."' readonly><input name=txtcodart".$ai_totrows." type=hidden id=txtcodart".$ai_totrows." class=sin-borde size=20 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtunidad".$ai_totrows."    type=text id=txtunidad".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".$ls_unidadaux."' readonly><input name='hidunidad".$ai_totrows."' type='hidden' id='hidunidad".$ai_totrows."' value='".$li_unidad."'>";
				$ao_object[$ai_totrows][3]="<input name=txtcanoriart".$ai_totrows." type=text id=txtcanoriart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canoriart,2,",",".")."'  readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcanart".$ai_totrows."    type=text id=txtcanart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_canart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."    type=text id=txtpenart".$ai_totrows."    class=sin-borde size=10 maxlength=12 value='".number_format ($li_penart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtpreuniart".$ai_totrows." type=text id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][7]="<input name=txtmontotart".$ai_totrows." type=text id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."'style='text-align:right'  readonly>";

			//////////////////////// Modificado	LUIS A. ALVAREZ 20-2-2008 ////////////////////////
			if($as_radiotipo == "1"){
				$ao_object[$ai_totrows][8]="<a href=javascript:uf_reversar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0></a>";
			}
			//////////////////////// 		FIN Modificado	20-2-2008 	////////////////////////

			}//while
		}//else
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		$ai_totentsum=number_format($ai_totentsum,2,',','.');
		return $lb_valido;
	} // end function uf_sim_obtener_dt_recepcion

	function uf_sim_update_ultimocosto($as_codemp,$as_codart,$ai_preuniart,$ls_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ultimocosto
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//  			   $ai_preuniart  // precio unitario del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el monto del ultimo costo con el cual el articulo ha ingresado a la empresa
		//				   en la tabla sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sfc_producto".
		 		   "   SET ultcosart='" . $ai_preuniart ."'".
				   " WHERE codemp='" . $as_codemp ."' ".
				   "   AND codart='" . $as_codart ."' ".
				   " AND codtiend='".$ls_codtie."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion METODO->uf_sim_update_ultimocosto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end function uf_sim_update_ultimocosto
	function uf_sim_actualizar_costo_promedio($as_codemp,$as_codart,$as_codtie,$as_numordcom,$as_codpro,$as_numconrec,$ls_numconrec,$ai_preuniart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_actualizar_costo_promedio
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que se encarga de calcular el costo promedio por articulo para luego actualizar
		//				   dicho monto en la tabla de sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_cosproart=0;
		$li_montot=0;
		$li_conart=0;
		$lb_valido=$this->uf_sim_verificar_existencia($as_codemp,$as_codart,$as_codpro,&$as_existencia,$as_codtie);
		//print $as_existencia;
		if ($lb_valido)
		{
		//print 'paso valido';
			if ($as_existencia>0)
			{
				if ($ls_numconrec<>'---')
				{
				/*$ls_sql = "SELECT dt.preuniart,dt.canart FROM sim_dt_recepcion dt,sim_recepcion r  ".
						  " WHERE dt.codemp='".$as_codemp."'".
						  " AND dt.codart='".$as_codart."'".
						  " AND dt.codtiend='".$as_codtie."'".
						  " AND dt.cod_pro='".$as_codpro."'".
					 " AND dt.numconrec BETWEEN  '".$ls_numconrec."' AND (SELECT MAX(dt.numconrec) FROM sim_dt_recepcion dt) ".
				"AND r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND r.codtiend=dt.codtiend".
						  " AND r.estrevrec<>'0' ORDER BY dt.numconrec;";*/
				$ls_sql = "SELECT dt.preuniart,dt.canart FROM sim_dt_recepcion dt,sim_recepcion r  ".
						  " WHERE dt.codemp='".$as_codemp."'".
						  " AND dt.codart='".$as_codart."'".
						  " AND dt.codtiend='".$as_codtie."'".
						  " AND dt.numconrec BETWEEN  '".$ls_numconrec."' AND (SELECT MAX(dt.numconrec) FROM sim_dt_recepcion dt) ".
						  " AND r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND r.codtiend=dt.codtiend".
						  " AND r.estrevrec<>'0' ORDER BY dt.numconrec;";
				}
				else
				{
				/*$ls_sql = "SELECT dt.preuniart,dt.canart FROM sim_dt_recepcion  dt,sim_recepcion r ".
				  " WHERE dt.codemp='".$as_codemp."'".
				  " AND dt.codart='".$as_codart."' ".
				  " AND dt.codtiend='".$as_codtie."' ".
				  " AND dt.cod_pro='".$as_codpro."'".
				  " AND r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND r.codtiend=dt.codtiend".
				  " AND r.estrevrec<>'0' ORDER BY dt.numconrec;";*/
				  $ls_sql = "SELECT dt.preuniart,dt.canart FROM sim_dt_recepcion  dt,sim_recepcion r ".
				  " WHERE dt.codemp='".$as_codemp."'".
				  " AND dt.codart='".$as_codart."' ".
				  " AND dt.codtiend='".$as_codtie."' ".
				  " AND r.codemp=dt.codemp AND r.numordcom=dt.numordcom AND r.numconrec=dt.numconrec AND r.codtiend=dt.codtiend".
				  " AND r.estrevrec<>'0' ORDER BY dt.numconrec;";
				}
				//print 'recepcion-->'.$ls_sql.'<br>';
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_actualizar_costo_promedio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_preuniart=$row["preuniart"];
					$li_canart=$row["canart"];
					$li_montot=$li_montot + ($li_preuniart * $li_canart);
					$li_conart=$li_conart + $li_canart;

					//print "PERUNI-->".$li_preuniart."<br>";

				}
				if($li_conart!=0)
				{
					$li_cosproart=($li_montot / $li_conart);
					//print $li_cosproart;
					//	print "COSTPRO-->".$li_cosproart."<br>";
				}
				else
				{$li_cosproart=0.0000;}
					$lb_valido=$this->uf_sim_update_costo_promedio($as_codemp,$as_codart,$li_cosproart,$as_numordcom,$as_codpro,$as_numconrec,$as_existencia,$as_codtie);
					return $lb_valido;

			}
			else
			{
			//print 'paso2';
			$as_existencia=0;
			$li_cosproart=$ai_preuniart;
			$lb_valido=$this->uf_sim_update_costo_promedio($as_codemp,$as_codart,$li_cosproart,$as_numordcom,$as_codpro,$as_numconrec,$as_existencia,$as_codtie);
			return $lb_valido;
			}
		}
		else
		{
		$as_existencia=0;
		$li_cosproart=$ai_preuniart;
		$lb_valido=$this->uf_sim_update_costo_promedio($as_codemp,$as_codart,$li_cosproart,$as_numordcom,$as_codpro,$as_numconrec,$as_existencia,$as_codtie);
		return $lb_valido;
		}

	}  // end function uf_sim_actualizar_costo_promedio

	function uf_sim_update_costo_promedio($as_codemp,$as_codart,$ai_cosproart,$as_numordcom,$as_codpro,$as_numconrec,$as_existencia,$ls_codtie)
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
		 //$lb_existe=$this->uf_sim_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
			 $ls_sql = "UPDATE sfc_producto".
			 		   "   SET cosproart='". $ai_cosproart ."' ".
					   " WHERE codemp='" . $as_codemp ."'".
					   "   AND codart='" . $as_codart ."'".
					   " AND codtiend='".$ls_codtie."'";

				//print 'sfc_producto-->'.$ls_sql.'<br>';
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_update_costo_promedio ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;

				}
				else
				{
					$lb_valido=true;
					if ($as_numordcom<>'---' && $as_numconrec<>'---')
					{
					if ($as_existencia=='')
					$as_existencia=0;
					/*$ls_sql = "UPDATE sim_dt_recepcion ".
					   "   SET cosproart='".$ai_cosproart."', ".
					   "	exiant='".$as_existencia."' ".
					   " WHERE codemp='".$as_codemp."'".
					   "   AND codart='".$as_codart."'".
					   " AND codtiend='".$ls_codtie."'".
					   " AND numordcom='".$as_numordcom."' AND numconrec='".$as_numconrec."' AND cod_pro='".$as_codpro."'";*/

					   $ls_sql = "UPDATE sim_dt_recepcion ".
					   "   SET cosproart='".$ai_cosproart."', ".
					   "	exiant='".$as_existencia."' ".
					   " WHERE codemp='".$as_codemp."'".
					   "   AND codart='".$as_codart."'".
					   " AND codtiend='".$ls_codtie."'".
					   " AND numordcom='".$as_numordcom."' AND numconrec='".$as_numconrec."'";


						//print 'costo-->'.$ls_sql.'<br>';
						$li_row = $this->io_sql->execute($ls_sql);
						if($li_row===false)
						{
							$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_update_costo_promedio 2 ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
							$lb_valido=false;

						}
						else
						{
						$lb_valido=true;
						}
					}
				}
		}
		return $lb_valido;
	} // end  function uf_sim_update_costo_promedio

	function uf_sim_reversar_dt_factura($as_codemp,$as_numordcom,$as_codart,$as_unidad,$as_codtie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_reversar_dt_factura
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // codigo de articulo
		//  			   $as_numordcom     // numero de orden de compra
		//  			   $as_numordcom     // unidad de medida del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que reversar la entrada de un art�culo de suministro almacen siempre que la existencia sea suficiente
		//	   Creado Por: Ing. Luis Alberto Alvarez
		// Fecha Creaci�n: 21/02/2008							Fecha �ltima Modificaci�n : 21/02/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;

		if($as_codart == ""){
			$ls_sql= "DELETE FROM sim_dt_recepcion".
				  " WHERE sim_dt_recepcion.codemp    = '".$as_codemp."'".
				  "   AND sim_dt_recepcion.codtiend  = '".$as_codtie."'".
				  "   AND sim_dt_recepcion.numordcom = '".$as_numordcom."'";
		}else{
			$ls_sql= "DELETE FROM sim_dt_recepcion".
				  " WHERE sim_dt_recepcion.codemp    = '".$as_codemp."'".
				  "   AND sim_dt_recepcion.numordcom = '".$as_numordcom."'".
				  "   AND sim_dt_recepcion.codart    = '".$as_codart."'".
				  "   AND sim_dt_recepcion.codtiend  = '".$as_codtie."'".
				  "   AND sim_dt_recepcion.unidad    = '".$as_unidad."'";
		}

		//$rs_data=$this->io_sql->select($ls_sql);
		//$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_reversar_dt_factura ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			//$this->io_sql->rollback();
		}else{
			//$this->io_sql->commit();
		}

		return $lb_valido;
	} // end uf_sim_reversar_dt_factura

    function uf_soc_update($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_ultimocosto
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codart     // numero de orden de compra
		//  			   $ai_preuniart  // precio unitario del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el monto del ultimo costo con el cual el articulo ha ingresado a la empresa
		//				   en la tabla sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE soc_ordencompra          ".
		 		   "   SET estpenalm = 0            ".
				   " WHERE codemp='".$as_codemp ."' ".
				   "   AND numordcom='". $as_numordcom."'".
			       "   AND estcondat='B' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_update_ultimocosto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end functio

	function uf_soc_select($as_codemp,$as_numordcom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_recepcion
		//         Access: public (sigesp_sim_p_recepcion)
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_numordcom // numero de la orden de compra/factura
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe detalles asociados a un maestro de recepcion de suministros a almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 10/02/2006							Fecha �ltima Modificaci�n : 10/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM soc_ordencompra       ".
				" WHERE codemp    = '".$as_codemp."' ".
				"   AND numordcom = '".$as_numordcom."'
				    AND estcondat = 'B'              ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->recepcion M�TODO->uf_sim_select_dt_recepcion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end function uf_sim_select_dt_recepcion

}//fin  class sigesp_sim_c_recepcion
?>
