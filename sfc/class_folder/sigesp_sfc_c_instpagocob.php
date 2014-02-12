<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_instrpago
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla Instrumento de pago.
 // Fecha:       - 16/02/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_instpagocob
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_instpagocob()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /* se toma la funcion de convertir cadena a caracteres*/
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}


/*********************************************************************************************************************************/
/*********************** SELECT DETALLES INSTRUMENTO DE PAGO (INSTPAGOCOB) *******************************************************/
/*********************************************************************************************************************************/
function uf_select_detalles_instpagocob($ls_numcob,&$aa_data,&$ai_rows)
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
		$ls_sql="SELECT * FROM sfc_instpagocob WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_msg_error="Error en select detalles_instpagocob".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->io_msg_error;
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

	function uf_select_instpagocarta($ls_numcob,$ls_numinst,$ls_codcli)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_facturacobro.
		// Parameters:  - $ls_numcob( N�mero del Cobro).
		//		      -$ls_numfactura (N�mero de la Factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_instpagocob
		            WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND numinst='".$ls_numinst."'  AND codcli='".$ls_codcli."' ORDER BY numinst ASC;";
		//	print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);
        //print $ls_cadena;
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detINST ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				}
		}
		return $lb_valido;
}
/********************************************************************************************************************************/
/************************************ GUARDAR DETALLES INSTRUMENTO DE PAGO ******************************************************/
/********************************************************************************************************************************/
	function uf_guardar_detalles_instpagocob($ls_codtie,$ls_codcli,$ls_numcob,$ls_numinst,$ls_fecins,$ls_obsins,$ls_codban,$ls_ctaban,$ls_monto,$ls_codforpag)
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
		$lb_existe=$this->uf_select_instpagocarta($ls_numcob,$ls_numinst,$ls_codcli);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ls_monto); /* convierte cadena en numero */

		$ls_fecins=$this->io_funcion->uf_convertirdatetobd($ls_fecins);
		if ($lb_existe)
		{
			$ls_sql= " UPDATE sfc_instpagocob set  numinst='".$ls_numinst."',fecins='".$ls_fecins."',codban='".$ls_codban."',ctaban='".$ls_ctaban."',monto='".$ld_monto."',codtiend='".$ls_codtie."' " .
					"WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."' AND codcli='".$ls_codcli."'";
			$ls_evento="UPDATE";
		}else{
			$ls_sql= " INSERT INTO sfc_instpagocob (codemp,codcli,numcob,numinst,fecins,obsins,codban,ctaban,monto,codforpag,codtiend) ".
			     " VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numcob."','".$ls_numinst."','".$ls_fecins."','".$ls_obsins."','".$ls_codban."','".$ls_ctaban."',".$ld_monto.",'".$ls_codforpag."','".$ls_codtie."')";
				 $ls_evento="INSERT";
		}

		//print $ls_sql.' guarda-det <br>';
		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);

		if($li_row===false)
		{
		$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detcobro".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			//$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_sql->message;

		}
		else
		{
			if($li_row>0)
			{
			    /************    SEGURIDAD    **************
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insert� la Retencion ".$as_codded.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{

				//$this->io_sql->rollback();
			}

		}
		return $lb_valido;
	}
	/********************************************************************************************************************************/
/************************************* BORRAR TODOS DETALLES INSTRUMENTO DE PAGO ******************************************************/
/********************************************************************************************************************************/
	function uf_delete_detinstpagocob($ls_numcob)

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

		$ls_sql1 = "DELETE FROM sfc_facturaretencion WHERE (codemp || numfac || numcob || codtiend) in " .
				"(SELECT ft.codemp || ft.numfac || ft.numcob || ft.codtiend FROM sfc_facturaretencion ft, sfc_instpagocob ip, sfc_dt_cobrocliente dtco " .
				"WHERE ip.numcob='".$ls_numcob."' AND ft.numcob='".$ls_numcob."' AND dtco.numcob=ip.numcob AND dtco.numfac=ft.numfac " .
				"GROUP BY ft.codemp, ft.numfac, ft.numcob, ft.codtiend)";

		//print $ls_sql1.'<br>';

		$ls_sql= "DELETE FROM sfc_instpagocob WHERE codemp='".$ls_codemp."' AND numcob='".$ls_numcob."'";
		//print "delete_detinstpagocob ".$ls_sql;

		//$this->io_sql->begin_transaction();

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			//$this->io_sql->rollback();

			//$this->io_msg="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);

			print "Error en metodo eliminar_detinstpagocob".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� la Retencion ".$as_codded.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			/********************************************/
			$lb_valido=true;
			//$this->io_sql->commit();
		}
		return $lb_valido;

	}

/********************************************************************************************************************************/
/************************************* BORRAR DETALLES INSTRUMENTO DE PAGO ******************************************************/
/********************************************************************************************************************************/
function uf_delete_detalles_instpagocob($ls_numcob,$ls_numinst,$ls_codforpag)

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

	$ls_sql= "DELETE FROM sfc_instpagocob WHERE codemp='".$ls_codemp."' AND codforpag='".$ls_codforpag."' AND numcob='".$ls_numcob."' " .
			"AND numinst='".$ls_numinst."'";
	//print "delete_detalles_instpagocob".$ls_sql;
	//$this->io_sql->begin_transaction();
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		//$this->io_sql->rollback();

		//$this->io_msg="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);

		print "Error en metodo eliminar_detalles_instpagocob".$this->io_function->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		/*************    SEGURIDAD    **************
		$ls_evento="DELETE";
		$ls_descripcion ="Elimin� la Retencion ".$as_codded.",Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
		/********************************************/
		$lb_valido=true;
		//$this->io_sql->commit();
	}
	return $lb_valido;

}
/********************************************************************************************************************************/
/************************************ UPDATE DETALLES INSTRUMENTO DE PAGO *******************************************************/
/********************************************************************************************************************************/
function uf_update_detalles_instpagocob($ls_codtie,$ls_codcli,$ls_numcob,$ls_numinst,$ls_fecins,$ls_obsins,$ls_codban,$ls_ctaban,$ls_monto,$ls_codforpag)
	 {

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ls_monto); /* convierte cadena en numero */

		$ls_fecins=$this->io_funcion->uf_convertirdatetobd($ls_fecins);

		$ls_sql= "UPDATE sfc_instpagocob ".
			     " SET monto=".$ld_monto.", codban='".$ls_codban."', ctaban='".$ls_ctaban."', fecins='".$ls_fecins."', obsins='".$ls_obsins."',codtiend='".$ls_codtie."' " .
			     "WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."' AND  numinst='".$ls_numinst."' AND codforpag='".$ls_codforpag."' " .
			     "AND numcob='".$ls_numcob."';";

		//print("updatepagosantes:".$ls_sql);
		//$this->io_sql->begin_transaction();
		//print "update_detalles_instpagocob".$ls_sql;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row==false)
		{
			//$this->is_msg= "Error en metodo uf_update_detalles_instrpag ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->is_msgc="Error en metodo uf_update_detalles_instpagocob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			//$this->io_sql->rollback();
		}
		else
		{
			if($li_row>0)
			{
				/*************    SEGURIDAD    **************
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� la retencion ".$as_codpar.", Detalle de la Valuacion ".$as_codval." Asociado a la Empresa ".$ls_codemp;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{

				//$this->io_sql->rollback();
			}

		}
		return $lb_valido;
	  }
/********************************************************************************************************************************/
/************************************* UPDATE ARREGLO DE DETALLES COTIZACION ****************************************************/
/********************************************************************************************************************************/
	function uf_update_detalles_instrumentopagocob($ls_codtie,$ls_codcli,$ls_numcob,$aa_detallesnuevos,$ai_totalfilasnuevas/*,$aa_seguridad*/)
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
		$lb_update=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_detalles_instpagocob($ls_numcob,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1


		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numcob"][$li_j]==$ls_numcob && $la_detallesviejos["codforpag"][$li_j]==$aa_detallesnuevos["codforpag"][$li_i] && $la_detallesviejos["numinst"][$li_j]==$aa_detallesnuevos["numinst"][$li_i])
				{
				  if($la_detallesviejos["monto"][$li_j] != $aa_detallesnuevos["monto"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}

			}

			if (!$lb_existe)
			{
				//print $li_i.'No existe <br>';
				$ls_codforpag=$aa_detallesnuevos["codforpag"][$li_i];
				$ls_denforpag=$aa_detallesnuevos["denforpag"][$li_i];
				$ls_numinst=$aa_detallesnuevos["numinst"][$li_i];
				$ls_codban=$aa_detallesnuevos["codban"][$li_i];
				$ls_ctaban=$aa_detallesnuevos["ctaban"][$li_i];
				$ls_obsins=$aa_detallesnuevos["obsins"][$li_i];
				$ls_fecins=$aa_detallesnuevos["fecins"][$li_i];
				$ls_nomban=$aa_detallesnuevos["nomban"][$li_i];
				$ls_monto=$aa_detallesnuevos["monto"][$li_i];
				//$ls_numcarta="0";
				$this->uf_guardar_detalles_instpagocob($ls_codtie,$ls_codcli,$ls_numcob,$ls_numinst,$ls_fecins,$ls_obsins,$ls_codban,$ls_ctaban,$ls_monto,$ls_codforpag);
				//$this->uf_guardar_detalles_instpagocob($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ls_monto,$ls_codban);
			}

			if ($lb_update)
			{
				$ls_codforpag=$aa_detallesnuevos["codforpag"][$li_i];
				$ls_denforpag=$aa_detallesnuevos["denforpag"][$li_i];
				$ls_numinst=$aa_detallesnuevos["numinst"][$li_i];
				$ls_codban=$aa_detallesnuevos["codban"][$li_i];
				$ls_ctaban=$aa_detallesnuevos["ctaban"][$li_i];
				$ls_obsins=$aa_detallesnuevos["obsins"][$li_i];
				$ls_fecins=$aa_detallesnuevos["fecins"][$li_i];
				$ls_nomban=$aa_detallesnuevos["nomban"][$li_i];
				$ls_monto=$aa_detallesnuevos["monto"][$li_i];

				$this->uf_update_detalles_instpagocob($ls_codtie,$ls_codcli,$ls_numcob,$ls_numinst,$ls_fecins,$ls_obsins,$ls_codban,$ls_ctaban,$ls_monto,$ls_codforpag);
				//$this->uf_update_detalles_instpagocob($ls_codcli,$ls_numfac,$ls_codforpag,$ls_numinst,$ls_monto,$ls_codban);
				//print "despues".$ls_codcli."/".$ls_numfac."/".$ls_codforpag."/".$ls_numinst."/".$ls_monto."/";
				//print "update_detallespago";
			}
		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numcob"][$li_j]==$ls_numcob && $la_detallesviejos["codforpag"][$li_j]==$aa_detallesnuevos["codforpag"][$li_i] && $la_detallesviejos["numinst"][$li_j]==$aa_detallesnuevos["numinst"][$li_i])
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			{

				$this->uf_delete_detalles_instpagocob($ls_numcob,$la_detallesviejos["numinst"][$li_j],$la_detallesviejos["codforpag"][$li_j]);
			}
		}

	}
}// FIN DE CLASE
?>
