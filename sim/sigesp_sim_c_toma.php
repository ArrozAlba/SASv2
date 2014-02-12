<?php
class sigesp_sim_c_toma
{
	var $io_sql;
	var $con;

	function sigesp_sim_c_toma()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		$this->io_sql=      new class_sql($con);
		$this->seguridad=   new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
		$this->io_fundb = new class_funciones_db($con);

	}
	
	function uf_sim_load_articuloalmacen($as_codemp,$as_codalm,&$ao_object,&$ai_totrows,$as_codtie,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_load_articuloalmacen
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_codalm //codigo de almacen
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los articulos que estan en determinado almacen de la empresa
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT sim_articuloalmacen.codart,sim_articulo.denart".
				  "  FROM sim_articuloalmacen,sim_articulo  ".
				  " WHERE sim_articuloalmacen.codart=sim_articulo.codart".
				  "  AND sim_articuloalmacen.codemp='".$as_codemp."'".
				  "  AND sim_articuloalmacen.cod_pro='".$as_codpro."'".
				  "  AND sim_articuloalmacen.codtiend='".$as_codtie."'".
				  "  AND sim_articuloalmacen.codalm='".$as_codalm."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_load_articuloalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart= $row["codart"];
				$ls_denart= $row["denart"];
				$ai_totrows= $ai_totrows + 1;
								
				$ao_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows." type=text id=txtcodart".$ai_totrows." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenart".$ai_totrows." type=text id=txtdenart".$ai_totrows." class=sin-borde size=40 maxlength=50 value='".$ls_denart."' readonly>";
				$ao_object[$ai_totrows][3]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:110px '><option value=->-Seleccione uno-</option><option value=D>Detal</option><option value=M>Mayor</option></select></div>";
				$ao_object[$ai_totrows][4]="<input name=txtcanfis".$ai_totrows." type=text id=txtcanfis".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event)); value=0,00>";
				$ao_object[$ai_totrows][5]="<img src='../shared/imagebank/tools/espacio.gif' width=20 height=20>";
			    $lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_sim_load_articuloalmacen
	
	function uf_sim_load_toma($as_codemp,$as_numtom,&$ao_object,&$ai_totrows,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_load_toma
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp  //codigo de empresa 
		//                 $as_numtom  //codigo de almacen
		//                 $ao_object  //arreglo de objeto
		//                 $ai_totrows //total de filas
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los articulos que estan relacionados a una toma de inventario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 31/03/2006 								Fecha Última Modificación : 31/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT sim_dt_toma.codart,sim_articulo.denart,sim_dt_toma.unidad,sim_dt_toma.canexifis,sim_dt_toma.canexisis".
				  "  FROM sim_dt_toma,sim_articulo  ".
				  " WHERE sim_dt_toma.codart=sim_articulo.codart".
				  "   AND sim_dt_toma.codemp='".$as_codemp."'".
				  "   AND sim_dt_toma.codtiend='".$as_codtie."'".
				  "   AND sim_dt_toma.numtom='".$as_numtom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_load_toma ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=   $row["codart"];
				$ls_denart=   $row["denart"];
				$ls_unidad=   $row["unidad"];
				$li_canexifis=$row["canexifis"];
				$li_canexifisaux=$row["canexifis"];
				$li_canexisis=$row["canexisis"];
				$li_canexifis=number_format($li_canexifis,2,",",".");
				if($ls_unidad=="M")
				{
					$la_unidad[0]="";
					$la_unidad[1]="selected";
				}
				else
				{
					$la_unidad[0]="selected";
					$la_unidad[1]="";
				}
				$ai_totrows= $ai_totrows + 1;
								
				$ao_object[$ai_totrows][1]="<input name=txtcodart".$ai_totrows." type=text id=txtcodart".$ai_totrows." class=sin-borde size=21 maxlength=20 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenart".$ai_totrows." type=text id=txtdenart".$ai_totrows." class=sin-borde size=40 maxlength=50 value='".$ls_denart."' readonly>";
				$ao_object[$ai_totrows][3]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:110px '><option value=->-Seleccione uno-</option><option value=D ".$la_unidad[0].">Detal</option><option value=M ".$la_unidad[1].">Mayor</option></select></div>";
				$ao_object[$ai_totrows][4]="<input name=txtcanfis".$ai_totrows." type=text id=txtcanfis".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".$li_canexifis."'onKeyPress=return(ue_formatonumero(this,'.',',',event)); value=0,00>";
				if($li_canexifisaux==$li_canexisis)
				{
					$ao_object[$ai_totrows][5]="<img src='../shared/imagebank/ok.png' width=10 height=10>";
				}
				else
				{
					$ao_object[$ai_totrows][5]="<img src='../shared/imagebank/failed.png' width=10 height=10>";
				}
			}
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_sim_load_toma

	function uf_sim_select_comparararticulos($as_codemp,$as_codalm,$as_codart,$ai_canexifis,$as_unidad,
	                                         &$ai_canexisis,&$ab_ok,&$ai_unidad,$as_codpro,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comparararticulos
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codalm    //codigo de almacen
		//                 $as_codart    //codigo de artioculo
		//                 $as_canexifis //cantidad fisica de articulos en el almacen
		//                 $as_unidad    //unidad de medida por el que fue contado el articulo
		//                 $ai_canexisis //cantidad de articulos en el almacen segun la base de datos
		//                 $ab_ok        //indica si el resultado de la comparacion es correcta o no
		//                 $ai_unidad    //cantidad de articulos definidos en unidad de medida asociada al articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca un articulo en determinado almacen y compara la existencia ingresada por el usuario con
		//				   la existencia que se tiene en el sistema.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/03/2006 								Fecha Última Modificación : 24/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT sim_articuloalmacen.existencia,sim_unidadmedida.unidad FROM sim_articuloalmacen,".
				"       sim_unidadmedida,sim_articulo".
				" WHERE sim_articuloalmacen.codart=sim_articulo.codart".
				"   AND sim_articulo.codunimed=sim_unidadmedida.codunimed".
				"   AND sim_articuloalmacen.codemp  ='".$as_codemp."'".
				"   AND sim_articuloalmacen.codalm  ='".$as_codalm."'".
				"   AND sim_articuloalmacen.codtiend='".$as_codtie."'".
				"   AND sim_articuloalmacen.cod_pro ='".$as_codpro."'".
				"   AND sim_articuloalmacen.codart  ='".$as_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_select_comparararticulos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_canexisis=$row["existencia"];
				$ai_unidad=$row["unidad"];
				if($as_unidad=="M")
				{
					$ai_canexifis=($ai_canexifis*$ai_unidad);
				}
				if($ai_canexisis==$ai_canexifis)
				{
					$ab_ok=true;
				}
				else
				{
					$ab_ok=false;
				}				
			}
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function uf_sim_select_comparararticulos 
	
	function uf_sim_insert_tomainventario($as_codemp,$as_codalm,&$as_numtom,$ad_fectom,$as_obstom,
	                                      $as_codusu,$aa_seguridad,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_tomainventario
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codalm    //codigo de almacen
		//                 $as_numtom    //numero de toma de inventario
		//                 $ad_fectom    //fecha en que fue realizada la toma
		//                 $as_obstom    //observaciones de la toma
		//                 $ai_canexisis //cantidad de articulos en el almacen segun la base de datos
		//                 $as_codusu    //usuario que realizo el proceso de la toma
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el registo maestro de una toma de inventario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/03/2006 								Fecha Última Modificación : 24/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_emp="";
		$ls_tabla="sim_toma";
		$ls_columna="numtom";
		$as_numtom=$this->io_fundb->uf_generar_codigo($ls_emp,$as_codemp,$ls_tabla,$ls_columna);

		$ls_sql="INSERT INTO sim_toma (codemp, codalm, numtom, fectom, obstom, codusu, codtiend)".
				"VALUES ('".$as_codemp."','".$as_codalm."','".$as_numtom."','".$ad_fectom."',
				         '".$as_obstom."','".$as_codusu."','".$as_codtie."')";
		//print $ls_sql."<br>";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_insert_tomainventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la toma ".$as_numtom."  de Almacén ".$as_codalm." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_variable)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_insert_tomainventario
												 
	function uf_sim_insert_dt_tomainventario($as_codemp,$as_codalm,$as_numtom,$as_codart,$ai_canexisis,$ai_canexifis,
											 $ai_canexifisant,$as_unidad,$aa_seguridad,$as_codpro,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_tomainventario
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codalm       //codigo de almacen
		//                 $as_numtom       //numero de toma de inventario
		//                 $as_codart       //codigo de articulo
		//                 $ai_canexisis    //cantidad de articulos en el almacen segun la base de datos
		//                 $ai_canexifis    //cantidad de articulos en el almacen segun la toma de inventario
		//                 $ai_canexifisant //cantidad anterior de articulos en el almacen segun la toma de inventario
		//                 $as_unidad       //unidad en la cual se realizo el conteo M->Mayor D-> Detal
		//                 $aa_seguridad    //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el registo maestro de una toma de inventario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/03/2006 								Fecha Última Modificación : 24/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql=" INSERT INTO sim_dt_toma (codemp,codalm,numtom,codart,canexisis,canexifis,canexifisant,unidad,codtiend,cod_pro)".
				" VALUES ('".$as_codemp."','".$as_codalm."','".$as_numtom."','".$as_codart."','".$ai_canexisis."','".$ai_canexifis."',".
				"         '".$ai_canexifisant."','".$as_unidad."','".$as_codtie."','".$as_codpro."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_insert_dt_tomainventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la toma ".$as_numtom."  de Almacén ".$as_codalm." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_variable)
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	} // end  function uf_sim_insert_tomainventario

	function uf_sim_load_ultimocosto($as_codemp,$as_codart,&$ai_preuniart,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_load_ultimocosto
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codart    //codigo de articulo
		//				   $ai_preuniart //ultimo precio unitario del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene el ultimo precio por el cual se recibio un articulo en el inventario de la tabla
		//				   sim_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/03/2006 								Fecha Última Modificación : 27/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sim_dt_recepcion".
				  " WHERE codart  ='".$as_codart."'".
				  "   AND codemp  ='".$as_codemp."'".
				  "   AND codtiend='".$as_codtie."'".
				  " ORDER BY numconrec DESC" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_load_ultimocosto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_preuniart= $row["preuniart"];
			}
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end function uf_sim_load_ultimocosto

	function uf_sim_update_tomainventario($as_codemp,$as_codalm,&$as_numtom,$ad_fectom,$as_obstom,$as_codusu,$aa_seguridad,$as_codtie) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_tomainventario
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codalm    //codigo de almacen
		//                 $as_numtom    //numero de toma de inventario
		//                 $ad_fectom    //fecha en que fue realizada la toma
		//                 $as_obstom    //observaciones de la toma
		//                 $ai_canexisis //cantidad de articulos en el almacen segun la base de datos
		//                 $as_codusu    //usuario que realizo el proceso de la toma
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un maestro de toma de inventario en la tabla sim_toma
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/04/2006 								Fecha Última Modificación : 01/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql = "UPDATE sim_toma".
				  "   SET fectom  ='".$ad_fectom."',".
				  "       obstom  ='".$as_obstom."',".
				  "       codusu  ='".$as_codusu."' ". 
				  " WHERE codemp  ='".$as_codemp."'".
				  "   AND codalm  ='".$as_codalm."'".
				  "   AND codtiend='".$as_codtie."'".
				  "   AND numtom  ='".$as_numtom."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->almacen MÉTODO->uf_sim_update_tomainventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Toma ".$as_numtom." del Almacén ". $as_codalm ." Asociado a la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_sim_update_tomainventario

	function uf_sim_update_dt_tomainventario($as_codemp,$as_codalm,$as_numtom,$as_codart,$ai_canexisis,$ai_canexifis,
											 $ai_canexifisant,$as_unidad,$aa_seguridad,$as_codpro,$as_codtie) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_dt_tomainventario
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codalm       //codigo de almacen
		//                 $as_numtom       //numero de toma de inventario
		//                 $as_codart       //codigo de articulo
		//                 $ai_canexisis    //cantidad de articulos en el almacen segun la base de datos
		//                 $ai_canexifis    //cantidad de articulos en el almacen segun la toma de inventario
		//                 $ai_canexifisant //cantidad anterior de articulos en el almacen segun la toma de inventario
		//                 $as_unidad       //unidad en la cual se realizo el conteo M->Mayor D-> Detal
		//                 $aa_seguridad    //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza un maestro de toma de inventario en la tabla sim_toma
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/04/2006 								Fecha Última Modificación : 01/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$li_canexifisant= 0;
		$lb_valido=$this->uf_sim_select_dt_tomainventario($as_codemp,$as_codalm,$as_numtom,$as_codart,$li_canexifisant,$as_codtie);
		if ($lb_valido)
		{
			$ls_sql = "UPDATE sim_dt_toma".
					  "   SET canexifis   ='".$ai_canexifis."',".
					  "       canexifisant='".$li_canexifisant."'".
					  " WHERE codemp      ='".$as_codemp."'".
					  "   AND codalm      ='".$as_codalm."'".
					  "   AND numtom      ='".$as_numtom."'".
					  "   AND codtiend    ='".$as_codtie."'".
  					  "   AND cod_pro     ='".$as_codpro."'".
					  "   AND codart      ='".$as_codart."'";
			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->almacen MÉTODO->uf_sim_update_dt_tomainventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
//				$ls_evento="UPDATE";
//				$ls_descripcion ="Actualizó el Almacén ".$as_codalm." Asociado a la Empresa ".$as_codemp;
//				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
//												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
//												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		else
		{
			$lb_valido=false;
		}
	    return $lb_valido;
	} // end  function uf_sim_update_dt_tomainventario

	function uf_sim_select_dt_tomainventario($as_codemp,$as_codalm,$as_numtom,$as_codart,&$ai_canexifisant,$as_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_dt_tomainventario
		//         Access: public (sigesp_sim_d_almacen)
		//      Argumento: $as_codemp       //codigo de empresa 
		//                 $as_codalm       //codigo de almacen
		//                 $as_numtom       //nuemro de toma
		//                 $as_codart       //codigo de articulo
		//				   $ai_canexifisant // cantidad existente anterior (fisica)
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe un determinado almacen en la tabla de  sim_almacen.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/04/2006 								Fecha Última Modificación : 01/04/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sim_dt_toma  ".
				  " WHERE codemp   ='".$as_codemp."'".
				  "   AND codalm   ='".$as_codalm."'".
				  "   AND numtom   ='".$as_numtom."'".
				  "   AND codtiend ='".$as_codtie."'".
				  "   AND codart   ='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->toma MÉTODO->uf_sim_select_dt_tomainventario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_canexifisant=$row["canexifis"];
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}  // end function uf_sim_select_dt_tomainventario

	function uf_sim_update_estatustoma($as_codemp,$as_codalm,$as_numtom,$aa_seguridad,$as_codtie) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_estatustoma
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codalm    //codigo de almacen
		//                 $as_numtom    //numero de toma de inventario
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que coloca el estatus del proceso de una toma de inventario (Ajuste)
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 15/09/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 	$lb_valido=true;
		$ls_sql = "UPDATE sim_toma".
				  "   SET estpro=1".
				  " WHERE codemp  ='".$as_codemp."'".
				  "   AND codalm  ='".$as_codalm."'".
				  "   AND codtiend='".$as_codtie."'".
				  "   AND numtom  ='".$as_numtom."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->almacen MÉTODO->uf_sim_update_estatustoma ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Ajustó el Almacén ".$as_codalm." Asociado a la Empresa ".$as_codemp.
							 " por la toma de Inventario número ".$as_numtom;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	    return $lb_valido;
	} // end  function uf_sim_update_estatustoma
	function uf_sim_load_denpro($as_codemp,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_load_ultimocosto
		//         Access: public (sigesp_sim_p_toma)
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_codart    //codigo de articulo
		//				   $ai_preuniart //ultimo precio unitario del articulo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene el ultimo precio por el cual se recibio un articulo en el inventario de la tabla
		//				   sim_dt_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 27/03/2006 								Fecha Última Modificación : 27/03/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= false;
		$as_nompro= "";
		
		$ls_sql = "SELECT cod_pro,nompro FROM rpc_proveedor".
				  " WHERE cod_pro= '".$as_codpro."'".
				  "   AND codemp = '".$as_codemp."'".
				  " ORDER BY cod_pro DESC" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->tomainventario MÉTODO->uf_sim_load_denpro ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_nompro= $row["nompro"];
			}
			$lb_valido=true;
			$this->io_sql->free_result($rs_data);
		}
		return $as_nompro;
	}  // end function uf_sim_load_ultimocosto

} //end class sigesp_sim_c_almacen
?>
