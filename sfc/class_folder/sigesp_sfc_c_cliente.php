<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cliente
 // Autor:       - Ing. Oscar Sequera
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_cliente
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 var $io_con;

function sigesp_sfc_c_cliente()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_con=$io_include->uf_conectar();
	$this->io_funcdb   = new class_funciones_db($this->io_con);
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();	
}


	function uf_select_cliente($ls_codcli)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_cliente
		            WHERE codemp='".$ls_codemp."' AND codcli=".$ls_codcli;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cliente ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->io_msgc="Registro no encontrado ";
			}
		}
		return $lb_valido;
	}

	function uf_guardar_cliente(&$ls_codcli,$ls_cedcli,$ls_nomcli,$ls_dircli,$ls_telcli,$ls_celcli,$ls_codpai,
	           $ls_codest,$ls_codmun,$ls_codpar,$ls_precioestandar,$ls_productor,$aa_seguridad,$ls_codtipcli,$as_tipocanal,$as_hidstatus)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida).
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_cliente($ls_codcli);
		if($as_hidstatus=="" && $lb_existe)
		{
			$ls_codcli=$this->io_funcdb->uf_generar_codigo(false,"","sfc_cliente","codcli",100); 
			$lb_existe=false;
			$as_hidstatus='C';
		}
		if(!$lb_existe)
		{
            $ls_cadena= "INSERT INTO sfc_cliente (codemp,codcli,cedcli,razcli,dircli,telcli,celcli,codpai,codest,
			                                      codmun,codpar,productor,precio_estandar,feccre,codtipcli,tipocliente)
			              VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_cedcli."','".$ls_nomcli."','".$ls_dircli."',
						          '".$ls_telcli."','".$ls_celcli."','".$ls_codpai."','".$ls_codest."',
						          '".$ls_codmun."','".$ls_codpar."','".$ls_productor."','".$ls_precioestandar."',
								  '".date('Y-m-d')."','".$ls_codtipcli."','".$as_tipocanal."')";
			$ls_evento="INSERT";

		}
		else
		{
		$ls_cadena= "UPDATE  sfc_cliente
			             SET razcli='".$ls_nomcli."', dircli='".$ls_dircli."',
						     telcli='".$ls_telcli."', celcli='".$ls_celcli."', codpai='".$ls_codpai."',
							 codest='".$ls_codest."', codmun='".$ls_codmun."', codpar='".$ls_codpar."',
							 productor='".$ls_productor."',precio_estandar='".$ls_precioestandar."',codtipcli='".$ls_codtipcli."',tipocliente='".$as_tipocanal."'
					  WHERE  codemp='".$ls_codemp."'
					  AND    codcli=".$ls_codcli;

			$ls_evento="UPDATE";
		}
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_cliente".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print $this->io_sql->message;
			$this->io_sql->rollback();
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				if($ls_evento=="INSERT")
				{
					$this->io_msgc="Registro de Cliente Incluido!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_descripcion ="Insert� el Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					
				}
				else
				{
					$this->io_msgc="Registro Actualizado!!!";
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_descripcion ="Actualiz� el Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				///$this->io_sql->commit();
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

	function uf_delete_cliente($ls_codcli,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_cliente($ls_codcli);

		if($lb_existe)
		{
		    	/*$ls_cadena= " DELETE FROM sfc_cliente
							  WHERE codemp='".$ls_codemp."' AND codcli=".$ls_codcli;*/
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// CAMBIO SEGUN REQUERIMIENTO ECISA_FAC_001
				////////////////////////////////////////////////////////////////////////////////////////////////////////
				$ls_cadena="UPDATE sfc_cliente SET estatus='f' WHERE codcli='".$ls_codcli."'" ;				
				
				$this->io_msgc="Registro Eliminado!!!";
				$this->io_sql->begin_transaction();
				$li_numrows=$this->io_sql->execute($ls_cadena);
				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_cliente ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);

				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� el Cliente ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ////////////////////////////
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

/**********************************************************************************************************************************/
/**********************************************************************************************************************************/
/***************************  DEDUCCIONES - DEDUCCIONES - DEDUCCIONES - DEDUCCIONES - DEDUCCIONES  ********************************/
/**********************************************************************************************************************************/
/**********************************************************************************************************************************/


/******************************************************************************************************/
/*********************** SELECT CLIENTE DEDUCCIONES  **************************************************/
/******************************************************************************************************/
function uf_select_clientededuccion($as_codcli,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallescot                                               */
     /* Access:			public                                                              */
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */
	 /*	Description:	Funcion que se encarga de 									        */
	 /*  Fecha:         25/03/2006                                                         */
	 /*	Autor:          GERARDO CORDERO		                                                */
	 /***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtienda=$this->datoemp["ls_codtienda"];
		$ls_sql="SELECT  *
				 FROM    sfc_clientededuccion
				 WHERE   codemp='".$ls_codemp."'
				 AND     codcli='".$as_codcli."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			/*
			$this->is_msg_error="Error en select clientededuccion".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			*/
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
  /*********************** GUARDAR CLIENTE DEDUCCIONES  ************************************/
  /*****************************************************************************************/
	function uf_guardar_clientededuccion($as_codcli,$as_codded,$aa_seguridad)
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
		$ls_sql= "INSERT INTO  sfc_clientededuccion(codemp,codcli,codded)
		          VALUES ('".$ls_codemp."','".$as_codcli."','".$as_codded."')";
		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
		    /*
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_clientededuccion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
			*/
		}
		else
		{
			if($li_row>0)
			{
			   //************    SEGURIDAD    **************/
				  $ls_evento="INSERT";
				  $ls_descripcion ="Inserto la Deduccion ".$ls_codded.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
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
  /*********************** BORRAR TODAS LAS DEDUCCIONES DE UN CLIENTE ******************************/
  /*****************************************************************************************/
	function uf_delete_deducciones($as_codcli,$aa_seguridad)

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

		$ls_sql= "DELETE
		            FROM  sfc_clientededuccion
		           WHERE  codemp='".$ls_codemp."'  AND  codcli='".$as_codcli."'";
        //print $ls_sql."<br>";
		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();
			print"Error en metodo eliminar_clientededuccion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
		    $this->io_sql->commit();
			///*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino las Retenciones del Cliente ".$as_codcli." Asociado a la Empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;

		}
		return $lb_valido;

	}
  /*****************************************************************************************/
  /*********************** BORRAR UNA DEDUCCION DE UN CLIENTE ******************************/
  /*****************************************************************************************/
	function uf_delete_clientededuccion($as_codcli,$as_codded,$aa_seguridad)

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

		$ls_sql= "DELETE
		           FROM  sfc_clientededuccion
				  WHERE  codemp='".$ls_codemp."'
		            AND  codcli='".$as_codcli."'
					AND  codded='".$as_codded."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			/*$this->io_sql->rollback();
			print "Error en metodo eliminar_clientededuccion ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			*/
		}
		else
		{
			///*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino la Retencion ".$ls_codded.", del Cliente ".$ls_codcli." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
		//	$this->io_sql->commit();
		}
		return $lb_valido;

	}

  /****************************************************************************************************/
  /*********************** UPDATE ARREGLO DE CLIENTE DEDUCCIONES **************************************/
  /****************************************************************************************************/

  /*Revisar, este llama a los metodos anteriores*/
	function uf_update_clientededucciones($ls_codcli,$aa_detallesnuevos,$ai_totalfilasnuevas,$aa_seguridad)
	{
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_clientededuccion($ls_codcli,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
		
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codcli"][$li_j]==$ls_codcli && $la_detallesviejos["codded"][$li_j] ==$aa_detallesnuevos["codded"][$li_i])
				//if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codcli"][$li_j]==$ls_codcli)
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			 {
				  $ls_codded=$aa_detallesnuevos["codded"][$li_i];
				  $this->uf_guardar_clientededuccion($ls_codcli,$ls_codded,$aa_seguridad);
			 }
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["codcli"][$li_j]==$ls_codcli && $la_detallesviejos["codded"][$li_j] ==$aa_detallesnuevos["codded"][$li_i])
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			{
				$this->uf_delete_clientededuccion($ls_codcli,$la_detallesviejos["codded"][$li_j],$aa_seguridad);
			}
		}
	}

	function uf_validar_cliente_onidex($ls_tipcli,$ls_cedcli,&$ls_nombre)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		$ls_nombre = "";
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_cliente
		            WHERE nacionalidad='".$ls_tipcli."' AND cedula=".$ls_cedcli;

		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cliente ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{

			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$ls_nombre=$row["nombre1"]." ".$row["nombre2"]." ".$row["apellido1"]." ".$row["apellido2"];
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
	
	function uf_existe_pto_colocacion($as_codemp,$as_codptocol)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:	 uf_existe_pto_colocacion
		//  Access:		 public
		//	Returns:	 Boolean 
		//	Description: Funcion que se encarga de verificar si extiste el punto de colocacion
		//	      Autor: Ing. Nelson Barraez
		//        Fecha: 18 de Agosto del 2010
		//  Modificado Por: Ing. Nelson Barraez          Fecha Última Modificación: 30/08/2010
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codptocol 
				   FROM sfc_puntocolocacion 
				  WHERE codemp='".$as_codemp."' AND codptocol='".$as_codptocol."'";
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data==false)
		{
			$this->io_msgc="Error en uf_existe_pto_colocacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{		  
				return true;
			}
			else
			{
				return false;
			}
		}			
	}	
	
	function uf_guardar_ptos_colocacion($as_codemp,$as_codptocol,$as_codcliperptocol,$as_razptocol,$as_dirptocol,$as_ptorefptocol,$as_codpai,$as_codest,$as_codmun,$as_codpar,$as_telfijptocol,
					   $as_telfaxptocol,$as_telmovptocol,$as_obsptocol,$as_nomconptocol,$as_cedconptocol,$as_emailconptocol,$as_telfijconptocol,$as_telmovconptocol,$as_estatus,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:	 uf_guardar_ptos_colocacion
		//  Access:		 public
		//	Returns:	 Boolean 
		//	Description: Funcion que se encarga de insertar o actualizar los puntos de colocación asociados al cliente o canal de distribución
		//	      Autor: Ing. Nelson Barraez
		//        Fecha: 18 de Agosto del 2010
		//  Modificado Por: Ing. Nelson Barraez.     Fecha Última Modificación: 30/08/2010.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=false;
		if(!$this->uf_existe_pto_colocacion($as_codemp,$as_codptocol))
		{
			$ls_sql="INSERT INTO sfc_puntocolocacion(codemp,codptocol,codcliperptocol,razptocol,dirptocol,ptorefptocol,codpai,codest,codmun,codpar,telfijptocol,
								telfaxptocol,telmovptocol,obsptocol,nomconptocol,cedconptocol,emailconptocol,telfijconptocol,telmovconptocol,estatus)
					 VALUES('".$as_codemp."','".$as_codptocol."','".$as_codcliperptocol."','".$as_razptocol."','".$as_dirptocol."','".$as_ptorefptocol."','".$as_codpai."','".$as_codest."','".$as_codmun."','".$as_codpar."','".$as_telfijptocol."',
						   '".$as_telfaxptocol."','".$as_telmovptocol."','".$as_obsptocol."','".$as_nomconptocol."','".$as_cedconptocol."','".$as_emailconptocol."','".$as_telfijconptocol."','".$as_telmovconptocol."','".$as_estatus."')";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_sql="UPDATE sfc_puntocolocacion SET razptocol='".$as_razptocol."',dirptocol='".$as_dirptocol."',ptorefptocol='".$as_ptorefptocol."',
							codpai='".$as_codpai."',codest='".$as_codest."',codmun='".$as_codmun."',codpar='".$as_codpar."',telfijptocol='".$as_telfijptocol."',
							telfaxptocol='".$as_telfaxptocol."',telmovptocol='".$as_telmovptocol."',obsptocol='".$as_obsptocol."',
							nomconptocol='".$as_nomconptocol."',cedconptocol='".$as_cedconptocol."',emailconptocol='".$as_emailconptocol."',
							telfijconptocol='".$as_telfijconptocol."',telmovconptocol='".$as_telmovconptocol."',estatus='".$as_estatus."'
					 WHERE codemp='".$as_codemp."' AND codptocol='".$as_codptocol."' AND codcliperptocol='".$as_codcliperptocol."'";
			$ls_evento="UPDATE";				
		}
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msgc="Error en guardar_ptos_colocacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);			
		}
		else
		{
			//************* **********************************   SEGURIDAD    *****************************************************
			$ls_descripcion ="Guardo el punto de colocación".$as_codptocol.", del Cliente ".$as_codcliperptocol." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/*********************************************************************************************************************/
			$lb_valido=true;
		}
		return $lb_valido;		
	}
	
	
	function uf_eliminar_ptos_colocacion($as_codemp,$as_codptocol,$as_codcliperptocol,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:	 uf_eliminar_ptos_colocacion
		//  Access:		 public
		//	Returns:	 Boolean
		//	Description: Funcion que se encarga de cambiar el estatus del registro para los puntos de colocación asociados al cliente o canal de distribución
		//	      Autor: Ing. Nelson Barraez
		//        Fecha: 18 de Agosto del 2010
		//  Modificado Por: Ing. Nelson Barraez.     Fecha Última Modificación: 30/08/2010.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lb_valido=false;
		if($this->uf_existe_pto_colocacion($as_codemp,$as_codptocol))
		{
			$ls_sql="UPDATE sfc_puntocolocacion SET estatus='f'
					 WHERE codemp='".$as_codemp."' AND codptocol='".$as_codptocol."' AND codcliperptocol='".$as_codcliperptocol."'";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$this->io_msgc="Error en eliminar_ptos_colocacion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);							
			}
			else
			{
				//*************************************************    SEGURIDAD    ***********************************************
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino el punto de colocación".$as_codptocol.", del Cliente ".$as_codcliperptocol." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				//*****************************************************************************************************************
				$lb_valido=true;
			}
		}		
		return $lb_valido;		
	}

}// FIN DE LA CLASE
?>
