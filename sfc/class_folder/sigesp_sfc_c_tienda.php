<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cliente
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_tienda
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_tienda()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}


	function uf_select_tienda($ls_codtie)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_tienda
		            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_tienda ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
	}


	function uf_guardar_tienda($ls_codtie,$ls_nomtie,$ls_dirtie,$ls_teltie,$ls_riftie,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_item,$ls_spi_cuenta,$unidad,$cuentapre,$codestpro1,$codestpro2,$codestpro3,$codestpro4,$codestpro5,$aa_seguridad,$as_codunisum,$as_facporvol)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida).
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		//				- $as_codunisum(Codigo de la unidad de suministro asociada a la tienda)
		//				- $as_facporvol(Realiza facturacion por volumen)
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_tienda($ls_codtie);

		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sfc_tienda(codtiend,dentie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,item,spi_cuenta,coduniadm,spg_cuenta,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,facporvol,codtipundopesum)
			              VALUES ('".$ls_codtie."','".$ls_nomtie."','".$ls_dirtie."','".$ls_teltie."','".$ls_riftie."','".$ls_codpai."','".$ls_codest."','".$ls_codmun."','".$ls_codpar."','".$ls_item."','".$ls_spi_cuenta."','".$unidad."','".$cuentapre."','".$ls_codemp."'," .
			              		"'".$codestpro1."','".$codestpro2."','".$codestpro3."','".$codestpro4."','".$codestpro5."','$as_facporvol','$as_codunisum') ";
			$this->io_msgc="Registro Incluido!!!";			
			$ls_evento="INSERT";
			
		}
		else
		{
			$ls_cadena= "UPDATE sfc_tienda
			             SET dentie='".$ls_nomtie."', dirtie='".$ls_dirtie."', teltie='".$ls_teltie."', riftie='".$ls_riftie."', codpai='".$ls_codpai."', codest='".$ls_codest."', codmun='".$ls_codmun."', codpar='".$ls_codpar."', item='".$ls_item."', spi_cuenta='".$ls_spi_cuenta."',coduniadm='".$unidad."'," .
			             		"spg_cuenta='".$cuentapre."',codemp='".$ls_codemp."',codestpro1='".$codestpro1."',codestpro2='".$codestpro2."'," .
			             		" codestpro3='".$codestpro3."',codestpro4='".$codestpro4."',codestpro5='".$codestpro5."',facporvol='$as_facporvol',codtipundopesum='$as_codunisum' WHERE codtiend='".$ls_codtie."' ";
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_tienda".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				if($ls_evento=="INSERT")
				{
					$lb_valido=$this->uf_guardar_almacen($this->io_funcion->uf_cerosizquierda($ls_codtie,10),"ALMACEN ".$ls_dentie,"ALMACEN ".$ls_dentie,$ls_teltie,$ls_dirtie,'','');
					if($lb_valido)
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó La Tienda ".$ls_nomtie." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
					}
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó La Tienda ".$ls_nomtie." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";

				}
			}

		}
		return $lb_valido;
	}

	function uf_select_almacen($ls_codalm)
	{
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT codemp,codalm,nomfisalm,desalm,telalm,ubialm,nomresalm,telresalm ".
				"  FROM sim_almacen ".
				" WHERE codemp='".$ls_codemp."' AND codalm='".$ls_codalm."' ";
		$rs_data=$this->io_sql->select($ls_cadena);
		$lb_valido=false;
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_almacen ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}			
		}
		return $lb_valido;				
	}	
	
	function uf_guardar_almacen($ls_codalm,$ls_nomfisalm,$ls_desalm,$ls_telalm,$ls_ubialm,$ls_nomresalm,$ls_telresalm)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_insert_almacen
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida).
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		//				- $as_codunisum(Codigo de la unidad de suministro asociada a la tienda)
		//				- $as_facporvol(Realiza facturacion por volumen)
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_almacen($ls_codalm);
		$lb_valido=true;
		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sim_almacen(codemp,codalm,nomfisalm,desalm,telalm,ubialm,nomresalm,telresalm)
			              VALUES ('".$ls_codemp."','".$ls_codalm."','".$ls_nomfisalm."','".$ls_desalm."','".$ls_telalm."','".$ls_ubialm."','".$ls_nomresalm."','".$ls_telresalm."') ";
			$this->io_msgc="Registro Incluido!!!";			
			$ls_evento="INSERT";
			$li_numrows=$this->io_sql->execute($ls_cadena);
	
			if($li_numrows==false)
			{
				$lb_valido=false;
				$this->is_msgc="Error en metodo uf_guardar_tienda".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				$this->io_sql->rollback();
				print $this->io_sql->message;
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
	
					if($ls_evento=="INSERT")
					{
						
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó La Tienda ".$ls_nomtie." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
					}	
				}
			}
		}	
		return $lb_valido;
	}
	
	function uf_delete_tienda($ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_tienda($ls_codtie);

		if($lb_existe)
		{
		    	$ls_cadena= " UPDATE sfc_tienda SET estatus='f'
							  WHERE codtiend='".$ls_codtie."'";

				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_tienda ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminï¿½ La Tienda ".$ls_codtie." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->is_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}

				}
		}
		else
		{
			$lb_valido=1;
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}

/******************************************************************************************************/
/*********************** SELECT CLIENTE DEDUCCIONES  **************************************************/
/******************************************************************************************************/
function uf_select_tienda_ctascontables($ls_codtie,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallescot                                               */
     /* Access:			public                                                              */
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */
	 /*	Description:	Funcion que se encarga de 									        */
	 /*  Fecha:          25/03/2006                                                         */
	 /*	Autor:          GERARDO CORDERO		                                                */
	 /***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT  *
				 FROM sfc_tienda_ctascontables
				 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select tienda_ctascontables".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}
		}
		return $lb_valido;
	}
  /*****************************************************************************************/
  /*********************** GUARDAR CUENTAS CONTABLES POR TIENDA ****************************/
  /*****************************************************************************************/
	function uf_guardar_tienda_ctacontable($ls_codtie,$sc_cuenta,$aa_seguridad)
    {
	    /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          GERARDO CORDERO		                                               */
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql= "INSERT INTO sfc_tienda_ctascontables (codemp,codtiend,sc_cuenta) VALUES ('".$ls_codemp."','".$ls_codtie."','".$sc_cuenta."')";

		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
		    $lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_tienda_ctacontable".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
		}
		else
		{
			if($li_row>0)
			{
			    //************    SEGURIDAD    **************/
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó la Cta Contable ".$sc_cuenta.", Asociada a la Tienda ".$ls_codtie." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{

				$this->io_sql->rollback();
			}

		}
		return $lb_valido;
	}

/*****************************************************************************************/
  /*********************** BORRAR TODAS LAS CTAS. CONTABLES POR TIENDA *******************/
  /***************************************************************************************/
	function uf_delete_ctascontables($ls_codtie,$aa_seguridad)

	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          GERARDO CORDERO		                                               */
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$ls_sql= "DELETE FROM sfc_tienda_ctascontables WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."';";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			$this->is_msgc="Error en metodo eliminar_ctascontables ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			///*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las Ctas Contables de la Tienda ".$ls_codtie." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;

	}
  /*****************************************************************************************/
  /*********************** BORRAR UNA DEDUCCION DE UN CLIENTE ******************************/
  /*****************************************************************************************/
	function uf_delete_tienda_ctacontable($ls_codtie,$sc_cuenta,$aa_seguridad)

	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          GERARDO CORDERO		                                               */
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$ls_sql= "DELETE FROM sfc_tienda_ctascontables WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND sc_cuenta='".$sc_cuenta."';";

		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			$this->is_msgc="Error en metodo eliminar_tienda_ctascontables ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			///*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Elimina la Cta Contable ".$sc_cuenta.", de la Tienda ".$ls_codtie." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
			$this->io_sql->commit();
		}
		return $lb_valido;

	}

  /****************************************************************************************************/
  /*********************** UPDATE ARREGLO DE CTAS CONTABLES POR TIENDAS *******************************/
  /****************************************************************************************************/

  /*Revisar, este llama a los metodos anteriores*/
	function uf_update_tienda_ctascontables($ls_codtie,$aa_detallesnuevos,$ai_totalfilasnuevas,$aa_seguridad)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_tienda_ctascontables($ls_codtie,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codtiend"][$li_j]==$ls_codtie && $la_detallesviejos["sc_cuenta"][$li_j] ==$aa_detallesnuevos["sc_cuenta"][$li_i])
				//if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codcli"][$li_j]==$ls_codcli)
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			 {
			  $ls_codcuenta=$aa_detallesnuevos["sc_cuenta"][$li_i];
			  $this->uf_guardar_tienda_ctacontable($ls_codtie,$ls_codcuenta,$aa_seguridad);
			 }
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codtiend"][$li_j]==$ls_codtie && $la_detallesviejos["sc_cuenta"][$li_j] ==$aa_detallesnuevos["sc_cuenta"][$li_i])
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			{
				$this->uf_delete_tienda_ctacontable($ls_codtie,$la_detallesviejos["sc_cuenta"][$li_j],$aa_seguridad);
			}
		}
	}


}
?>
