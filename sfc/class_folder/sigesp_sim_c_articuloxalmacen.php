<?php

class sigesp_sim_c_articuloxalmacen
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_articuloxalmacen()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("sigesp_sim_c_recepcion.php");
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->io_rec=new sigesp_sim_c_recepcion();
	}


	function uf_sim_select_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$as_codproveedor,$ls_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp //codigo de empresa
		//                 $as_codart // codigo de articulo
		//                 $as_codalm //codigo de almacen
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que verifica si existe un articulo en un determinado almacen en la tabla de  sim_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;

		$ls_sql = "SELECT * FROM sim_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'".
				  " AND codalm='".$as_codalm."' AND codtiend='".$ls_codtie."' AND cod_pro='".$as_codproveedor."' ";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_select_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
	} // end  function uf_sim_select_articuloxalmacen


	function uf_sim_insert_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$as_existencia,$as_codproveedor,$ls_codtie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_insert_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $as_existencia  // codigo del usuario
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que registra cierta cantidad de un articulo en determinado almacen en la tabla sim_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		$ls_sql="INSERT INTO sim_articuloalmacen (codemp, codart, codalm, existencia,codtiend,cod_pro)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_codalm."',".$as_existencia.",'".$ls_codtie."','".$as_codproveedor."')";
		//print $ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_insert_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Articulo ".$as_codart." en el Almacén ".$as_codalm." de la Empresa ".$as_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	} // end function uf_sim_insert_articuloxalmacen

	function uf_sim_update_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$as_existencia,$ls_codproveedor,$ls_codtiend)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $as_existencia  // codigo del usuario
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza cierta cantidad de articulos en un almacen determinado en la tabla sim_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql = "UPDATE sim_articuloalmacen SET   existencia='". $as_existencia ."' ".
					" WHERE codemp='" . $as_codemp ."'".
					" AND codart='" . $as_codart ."'".
					" AND codalm='" . $as_codalm ."'  AND codtiend='".$ls_codtiend."' AND cod_pro='".$ls_codproveedor."'";
        $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_update_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*			$ls_evento="UPDATE";
			$ls_descripcion ="Modificó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);*/
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$this->io_sql->commit();
		}


	  return $lb_valido;

	}

	function uf_sim_chequear_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_chequear_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad   // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que verifica que exista la cantidad suficiente de articulos en un almacen deteriminado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;

		$ls_sql = "SELECT * FROM sim_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'".
				  " AND codalm='".$as_codalm."' AND codtiend='".$ls_codtie."' AND cod_pro='".$as_codproveedor."'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_chequear_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{

				$li_existencia=$row["existencia"];
				if ($li_existencia >= $ai_cantidad)
				{
					$lb_valido=true;
				}
				else
				{
					$this->io_msg->message("No existen suficientes articulos de: ".$as_codart."-Sol: ".$ai_cantidad."-Exis".$li_existencia."  en el almacen seleccionado ");
					$lb_valido=false;
				}
			}
			else
			{
				$li_existencia=$row["existencia"];

				$this->io_msg->message("2 No existen suficientes articulos de: ".$as_codart."-Sol: ".$ai_cantidad."-Exis: ".$li_existencia."  en el almacen seleccionado ");

				//$this->io_msg->message("No existen suficientes articulos en el almacen seleccionado: ".$as_codart);
			}
		}
		return $lb_valido;
	}

	function uf_sim_disminuir_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_disminuir_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que disminuye la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 require_once("class_folder/sigesp_sfc_c_secuencia.php");
     $io_function=new class_funciones();
	 $ls_codtie=$_SESSION["ls_codtienda"];
	 $ls_nomtie=$_SESSION["ls_nomtienda"];
	 $lb_valido=true;
	 $lb_valido=$this->uf_sim_chequear_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie);
	 if($lb_valido)
	 {
		 $ls_sql =  "UPDATE sim_articuloalmacen".
		 			"   SET existencia= (existencia - ". $ai_cantidad .") ".
					" WHERE codemp='" . $as_codemp ."'".
					"   AND codart='" . $as_codart ."'".
					"   AND codalm='" . $as_codalm ."' AND codtiend='".$ls_codtie."' AND cod_pro='".$as_codproveedor."'";


			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_disminuir_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;

			}
			else
			{
				$lb_valido=true;
				$lb_valido=$this->io_rec->uf_sim_verificar_existencia($as_codemp,$as_codart,$as_codproveedor,&$as_existencia);
				if ($as_existencia==0)
				{
				$li_cosproart=0.0000;
				$as_numordcom='---';
				$as_numconrec='---';
				$lb_valido=$this->io_rec->uf_sim_update_costo_promedio($as_codemp,$as_codart,$li_cosproart,$ls_codtie,$as_numordcom,$as_codproveedor,$as_numconrec,$as_existencia);
				}
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
$ls_descripcion ="Disminuyó ".$ai_cantidad." Articulos ".$as_codart." del Almacén ".$as_codalm." del proveedor ".$as_codproveedor.
" de la Empresa ".$as_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
	  return $lb_valido;
	}

	function uf_sim_disminuir_articuloxalmacenmovi($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$ls_codproveedor,$ls_codtiend/*,$aa_seguridad*/)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_disminuir_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que disminuye la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 //$lb_valido=$this->uf_sim_chequear_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad);
	 if($lb_valido)
	 {

	 	//print $ai_cantidad;
		 $ls_sql =  "UPDATE sim_articuloalmacen".
		 			"   SET existencia= (existencia - '". $ai_cantidad ."') ".
					" WHERE codemp='" . $as_codemp ."'".
					"   AND codart='" . $as_codart ."'".
					"   AND codalm='" . $as_codalm ."' AND cod_pro='".$ls_codproveedor."' AND codtiend='".$ls_codtiend."' ";
		//print $ls_sql;

			$li_row = $this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_disminuir_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;

			}
			else
			{
				$lb_valido=true;
				/*////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Disminuyó ".$ai_cantidad." Articulos ".$as_codart." del Almacén ".$as_codalm." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ////////////////////////////*/
			}
		}
	  return $lb_valido;
	}// Fin uf_sim_disminuir_articuloxalmacenmovi

	function uf_sim_sumar_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_sumar_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que aumenta la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		 $ls_sql= "UPDATE sim_articuloalmacen".
		 		  "   SET existencia= (existencia + '". $ai_cantidad ."') ".
				  " WHERE codemp='" . $as_codemp ."'".
				  "   AND codart='" . $as_codart ."'".
				  "   AND codalm='" . $as_codalm ."' AND codtiend='".$ls_codtie."' AND cod_pro='".$as_codproveedor."'";
				  
		//print $ls_sql;
		$li_row = $this->io_sql->execute($ls_sql);

		if($li_row===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_sumar_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion ="Aumentó ".$ai_cantidad." Articulos ".$as_codart." del Almacén ".$as_codalm." de la Empresa ".$as_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
	    return $lb_valido;
	}

	function uf_sim_aumentar_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_aumentar_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codart      // codigo de articulo
		//                 $as_codalm      //codigo de almacen
		//                 $ai_cantidad    // cantidad de articulos
		//                 $aa_seguridad   // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que deacuerdo a los resultados de una busqueda (select), inserta o actualiza cierta cantidad de
		//				    articulos en un almacen determinado en la tabla de  sim_articuloalmacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("class_folder/sigesp_sfc_c_secuencia.php");
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		$io_function=new class_funciones();
		if ($as_codalm=='' or strlen($as_codalm)<10)
		{
		   if ($as_codalm=='')
		   {
			$as_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($ls_codtie,10);
		   }
		   else
		   {
			$as_codalm=$ls_secuencia=$io_function->uf_cerosizquierda($as_codalm,10);
		   }
		}
		if(!($this->uf_sim_select_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$as_codproveedor,$ls_codtie)))
		{
			$lb_valido=$this->uf_sim_insert_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_sim_sumar_articuloxalmacen($as_codemp,$as_codart,$as_codalm,$ai_cantidad,$as_codproveedor,$ls_codtie,$aa_seguridad);
		}
		return $lb_valido;
	}

	function uf_sim_select_articulo($as_codemp,$as_codart)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_select_articulo
		//         Access: public
		//      Argumento: $as_codemp //codigo de empresa
		//                 $as_codart // codigo de articulo
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que busca un articulo en la tabla de  sim_articulo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_articulo  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codart='".$as_codart."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_select_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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

	function uf_sim_disminuir_articulo($as_codemp,$as_codart,$ai_cantidad,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_disminuir_articulo
		//         Access: public
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $as_cantidad   // cantidad de articulos
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que disminuye la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=false;
		$lb_existe=uf_sim_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
			 $ls_sql= "UPDATE sim_articulo".
			 		  "   SET exiart= (exiart - '". $ai_cantidad ."') ".
					  " WHERE codemp='" . $as_codemp ."'".
					  "   AND codart='" . $as_codart ."'";
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_disminuir_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
		/*			$ls_evento="UPDATE";
					$ls_descripcion ="Modificó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);*/
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
		}
	    return $lb_valido;
	}

	function uf_sim_update_total_articulo($as_codemp,$as_codart,$ai_cantidad,$as_codproveedor,$ls_codtie/*,$aa_seguridad*/)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_total_articulo
		//         Access: public
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $as_cantidad   // cantidad de articulos
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_sim_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
			 $ls_sql= "UPDATE sim_articuloalmacen".
			 		  "   SET existencia='". $ai_cantidad ."' ".
					  " WHERE codemp='" . $as_codemp ."'".
					  "   AND codart='" . $as_codart ."' AND codtiend='".$ls_codtie."' AND cod_pro='".$as_codproveedor."'";
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_update_total_articulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la cantidad total del articulo ".$as_codart." de la Empresa ".$as_codemp." en ".$ai_cantidad;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);*/
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
		}
		return $lb_valido;
	}

	function uf_sim_update_total_articulomovi($as_codemp,$as_codart,$ai_cantidad,$as_codalm,$ls_codproveedor,$ls_codtiend/*,$aa_seguridad*/)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_update_total_articulo
		//         Access: public
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $as_cantidad   // cantidad de articulos
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que actualiza la cantidad de un articulo en un almacen determinado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_existe=$this->uf_sim_select_articulo($as_codemp,$as_codart);
		if($lb_existe)
		{
			 $ls_sql= "UPDATE sim_articulo".
			 		  "   SET exiart='". $ai_cantidad ."' ".
					  " WHERE codemp='" . $as_codemp ."'".
					  "   AND codart='" . $as_codart ."' AND cod_pro='".$ls_codproveedor."' AND codtiend='".$ls_codtiend."' ".
				      "AND codalm='".$as_codalm."'";

				//print $ls_sql;
				$li_row = $this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_update_total_articulomovi ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la cantidad total del articulo ".$as_codart." de la Empresa ".$as_codemp." en ".$ai_cantidad;
					$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);*/
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
		}
		return $lb_valido;
	}


	function uf_sim_actualizar_cantidad_articulos($as_codemp,$as_codart,$ls_codproveedor,$ls_codalm,$ls_codtie/*,$aa_seguridad*/)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_actualizar_cantidad_articulos
		//         Access: public
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codart     // codigo de articulo
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que calcula la cantidad total de un articulo entre todos los almacenes para luego actualizar dicha cantidad
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_exec=-1;
		$li_totart=0;

		$ls_sql = "SELECT * FROM sim_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codart='".$as_codart."' AND cod_pro='".$ls_codproveedor."' AND codtiend='".$ls_codtie."' AND ".
				  " codalm='".$ls_codalm."'";
		$rs_data=$this->io_sql->select($ls_sql);
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$li_cantalm=$row["existencia"];
			$li_totart=$li_totart + $li_cantalm;
		}
		$lb_valido=$this->uf_sim_update_total_articulo($as_codemp,$as_codart,$li_totart,$ls_codproveedor,$ls_codtie/*,$aa_seguridad*/);
		return $lb_valido;
	}

function uf_sim_actualizar_cantidad_articulosmovi($as_codemp,$as_codart,$as_codalm,$ls_codproveedor,$ls_codtiend/*,$aa_seguridad*/)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_actualizar_cantidad_articulos
		//         Access: public
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codart     // codigo de articulo
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que calcula la cantidad total de un articulo entre todos los almacenes para luego actualizar dicha cantidad
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_exec=-1;
		$li_totart=0;
		$ls_sql = "SELECT * FROM sim_articuloalmacen  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'" .
				  "AND codalm='".$as_codalm."' AND cod_pro='".$ls_codproveedor."' AND codtiend='".$ls_codtiend."'  " ;

		$rs_data=$this->io_sql->select($ls_sql);
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$li_cantalm=$row["existencia"];
			$li_totart=$li_totart + $li_cantalm;
		}
		$lb_valido=$this->uf_sim_update_total_articulomovi($as_codemp,$as_codart,$li_totart,$as_codalm,$ls_codproveedor,$ls_codtiend/*,$aa_seguridad*/);
		return $lb_valido;
	}


	function uf_sim_delete_articuloxalmacen($as_codemp,$as_codart,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_delete_articuloxalmacen
		//         Access: public
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codart     // codigo de articulo
		//                 $as_codalm     // codigo de almacen
		//                 $aa_seguridad  // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que elimina un registro de cantidad de articulos en un almacen
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql= "DELETE FROM sim_articuloxalmacen".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codart= '".$as_codart. "'".
				 "   AND codalm= '".$as_codalm. "'";

			$this->io_sql->begin_transaction();
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->articuloxalmacen MÉTODO->uf_sim_delete_articuloxalmacen ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
/*				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el Movimiento ".$as_nummov." de Fecha ".$ad_fecmov;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);*/
				//////////////////////////////////         SEGURIDAD               /////////////////////////////
				$this->io_sql->commit();
			}
		return $lb_valido;
	}
}
?>
