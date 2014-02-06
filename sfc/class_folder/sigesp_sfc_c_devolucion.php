<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_factura
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla sfc_factura y sfc_detfactura.
 // Fecha:       - 16/02/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_devolucion
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_devolucion()
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


function uf_select_devolucion($ls_coddev,$ls_codtie,&$ls_estdev)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];

		//print $ls_coddev;
		$ls_cadena="SELECT * FROM sfc_devolucion
		            WHERE codemp='".$ls_codemp."' AND coddev='".$ls_coddev."' AND codtiend='".$ls_codtie."';";

		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_devolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{

				$lb_valido=true;
				$ls_estdev=$row["estdev"];

			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		//print $lb_valido;
		return $lb_valido;
}

function uf_guardar_devolucion($ls_coddev,$ls_numfac,$ls_obsdev,$ls_fecdev,$ls_mondev,$ls_codusu,$ls_codtie,$aa_seguridad,$numcon)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_factura.
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//				- $ls_numfac(N�mero de la factura)
		//			    - $ls_numcot.
		//			    - $ls_codusu(c�digo del usuario).
		//				- $ls_fecemi(fecha de la emision de la factura).
		//				- $ls_conpag(condicion de pago: Cr�dito � Contado).
		//              - $ld_monto(monto a pagar).
		//              - $ld_estfac(Estado de la factura: Cancelada � No cancelada).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////

		$ls_codemp=$this->datoemp["codemp"];

		$lb_existe=$this->uf_select_devolucion($ls_coddev,$ls_codtie,&$ls_estdev);
		$ld_mondev=$this->funsob->uf_convertir_cadenanumero($ls_mondev); /* convierte cadena en numero */
		setlocale(LC_TIME, "es_VE");
		date_default_timezone_set('America/Caracas');
		$m=time() - 1800;
		$horadev=date("h:i:s",$m);
		$ls_fecdev=$this->io_funcion->uf_convertirdatetobd_hora($ls_fecdev,$horadev);



		//$ls_fecdev=$this->io_funcion->uf_convertirdatetobd($ls_fecdev);

		if(!$lb_existe)
		{

            $ls_cadena= "INSERT INTO sfc_devolucion (codemp,coddev,numfac,obsdev,fecdev,mondev,estdev,codusu,codtiend,numcon,cod_caja) " .
            		"VALUES ('".$ls_codemp."','".$ls_coddev."','".$ls_numfac."','".$ls_obsdev."','".$ls_fecdev."',".$ld_mondev.",'E','".$ls_codusu."','".$ls_codtie."','".$numcon."','".$_SESSION["ls_codcaj"]."')";

			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_devolucion
			             SET numfac='".$ls_numfac."', obsdev='".$ls_obsdev."', fecdev='".$ls_fecdev."',mondev=".$ld_mondev.",codusu='".$ls_codusu."' WHERE coddev='".$ls_coddev."' and codemp='".$ls_codemp."' and codtiend='".$ls_codtie."';";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		//print $ls_cadena;
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_devolucion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Inserto la Devolucion ".$ls_coddev.", de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo la Devolucion ".$ls_coddev.", de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				//$this->io_sql->commit();

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


function uf_delete_devolucion($ls_coddev,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_factura.
		// Parameters:  - $ls_numfac (n�mero de la factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$lb_existe=$this->uf_select_devolucion($ls_coddev,$ls_codtie,&$ls_estdev);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_devolucion WHERE coddev='".$ls_coddev."' and codemp='".$ls_codemp."' and codtiend='".$ls_codtie."'";
				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_devolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);

				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
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
/******************************************************************************************************************************/
/******************  DEVOLUCION DETALLES **************************************************************************************/
/******************************************************************************************************************************/
function uf_select_detdevolucion($ls_coddev,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detdevolucion.
		// Parameters:  - $ls_numfac( N�mero de la devolucion).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////



		$ls_cadena="SELECT * FROM sfc_detdevolucion
		            WHERE coddev='".$ls_coddev."' and codtiend='".$ls_codtie."' ;";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detdevolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

function uf_select_detproducto($ls_coddev,$ls_codpro)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detproducto
		// Parameters:  - $ls_codpro( Codigo del producto).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////


		$ls_cadena="SELECT * FROM sfc_detdevolucion
		            WHERE codpro='".$ls_codpro."' AND coddev='".$ls_coddev."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
function uf_guardar_detdevolucion($ls_coddev,$ls_codpro,$ls_candev,$ls_precio,$ls_porimp,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_detdevolucion
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numfac ()N�mero de la factura.
		//			    - $ls_codpro( c�digo del producto).
		//				- $ls_canpro (cantidad de producto).
		//				- $ls_prepro(precio del producto).
		//              - $ld_porimp( impuesto al producto).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];

		/*$lb_existe_cot=$this->uf_select_detcotizacion($ls_numcot);*/
		$lb_existe=$this->uf_select_detproducto($ls_coddev,$ls_codpro);
		$ls_candev=$this->funsob->uf_convertir_cadenanumero($ls_candev); /* convierte cadena en numero */
		$ls_precio=$this->funsob->uf_convertir_cadenanumero($ls_precio); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */



		  if ($lb_existe)
		    {

				$ls_cadena= "UPDATE sfc_detdevolucion SET candev=".$ls_candev.", precio=".$ls_precio.", porimp=".$ls_porimp." WHERE coddev='".$ls_coddev."' AND codpro='".$ls_codpro."';";

			/*$this->io_msgc="Registro Actualizado!!!";*/
			$ls_evento="UPDATE";
			}
		  else
			{
            $ls_cadena= "INSERT INTO sfc_detdevolucion (coddev,codpro,candev,precio,porimp,codtiend) VALUES ('".$ls_coddev."','".$ls_codpro."',".$ls_candev.",".$ls_precio.",".$ls_porimp.",'".$ls_codtie."')";
			/*$this->io_msgc="Registro Incluido!!!";	*/
			$ls_evento="INSERT";
		    }


		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detdevolucion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Inserto el Detalle ".$ls_codpro." de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo el Detalle ".$ls_codpro." de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
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
				if ($lb_existe)
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


function uf_delete_detdevolucion($ls_coddev,$ls_codtie,$aa_seguridad)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_detfactura
		// Parameters:  - $ls_numfac (n�mero de factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$lb_existe=$this->uf_select_detdevolucion($ls_coddev,$ls_codtie);


		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_detdevolucion
							  WHERE coddev='".$ls_coddev."' AND codtiend='".$ls_codtie."' ";


				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_detdevolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print $ls_cadena;*/
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminolo detalles de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
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
/********************************************************************************************************************************/
/****************************** SELECT DETALLES FACTURACION *********************************************************************/
/********************************************************************************************************************************/
function uf_select_detallesdev ($ls_coddev,&$aa_data,&$ai_rows,$ls_codtie)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallesdev                                               */
     /* Access:			public                                                              */
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */
	 /*	Description:	Funcion que se encarga de 									        */
	 /*  Fecha:          25/03/2006                                                         */
	 /*	Autor:          GERARDO CORDERO		                                                */
	 /***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$ls_sql="SELECT  * FROM sfc_detdevolucion WHERE coddev='".$ls_coddev."' and codemp='".$ls_codemp."' and codtiend='".$ls_codtie."';";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{


			$this->is_msg_error="Error en select detallesdev".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$this->io_sql->rollback();


		}
		else
		{

			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
				//$this->io_sql->commit();

			}else
			{
				$ai_rows=0;
				$aa_data="";

			}
		}
		return $lb_valido;
	}
 /*****************************************************************************************/
 /*********************** GUARDAR DETALLES FACTURACION ************************************/
 /*****************************************************************************************/
function uf_guardar_detallesdev($ls_coddev,$ls_codpro,$ls_candev,$ls_precio,$ls_porimp,$ls_codalm,$ls_codproveedor,$ls_proveeedor,$ls_codtie,$ls_condmerc,$aa_seguridad)
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


		$ls_candev=$this->funsob->uf_convertir_cadenanumero($ls_candev); /* convierte cadena en numero */
		$ls_precio=$this->funsob->uf_convertir_cadenanumero($ls_precio); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */


		 $ls_sql= "INSERT INTO sfc_detdevolucion (codemp,coddev,codart,candev,precio,porimp,codalm,cod_pro,codtiend,condmerc) VALUES " .
		 		" ('".$ls_codemp."','".$ls_coddev."','".$ls_codpro."',".$ls_candev.",".$ls_precio.",".$ls_porimp.",'".$ls_codalm."','".$ls_codproveedor."','".$ls_codtie."','".$ls_condmerc."')";
		 $ls_evento="INSERT";



		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
		   $lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detdevolucion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}

		else
		{
			if($li_row>0)
			{
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion ="Inserto el Detalle ".$ls_codpro." de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo el Detalle ".$ls_codpro." de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
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
  /*********************** BORRAR DETALLES FACTURACION *************************************/
  /*****************************************************************************************/
	function uf_delete_detallesdev($ls_coddev,$ls_codpro,$ls_codtie,$aa_seguridad)

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

		$ls_sql= "DELETE FROM sfc_detdevolucion WHERE coddev='".$ls_coddev."' AND codpro='".$ls_codpro."' AND codemp='".$ls_codemp."' and codtiend='".$ls_codtie."';";

		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();

			//$this->io_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);

			print"Error en metodo eliminar_detallesdev".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino lo detalles de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$lb_valido=true;
			//$this->io_sql->commit();
		}
		return $lb_valido;

	}
 /*****************************************************************************************/
 /*********************** UPDATE DETALLES FACTURACION *************************************/
 /*****************************************************************************************/
function uf_update_detallesdev($ls_coddev,$ls_codpro,$ls_candev,$ls_precio,$ls_porimp,$ls_codalm,$ls_codproveedor,$ls_codtie,$ls_condmerc,$aa_seguridad)
{

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$ls_candev=$this->funsob->uf_convertir_cadenanumero($ls_candev); /* convierte cadena en numero */
		$ls_precio=$this->funsob->uf_convertir_cadenanumero($ls_precio); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */


		$ls_sql= "UPDATE sfc_detdevolucion SET candev=".$ls_candev.", precio=".$ls_precio.", porimp=".$ls_porimp.",condmerc='".$ls_condmerc."' WHERE coddev='".$ls_coddev."' AND codart='".$ls_codpro."' AND codemp='".$ls_codemp."' and cod_pro='".$ls_codproveedor."' and codalm='".$ls_codalm."' and codtiend='".$ls_codtie."';";


		//$this->io_sql->begin_transaction();

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print "Error en metodo uf_update_detallesdev ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
		}
		else
		{
			if($li_row>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el Detalle ".$ls_codpro." de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
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
 /*********************** UPDATE ARREGLO DE DETALLES FACTURAS **************************************/
 /*****************************************************************************************/
function uf_update_detallesdevoluciones($ls_coddev,$ls_codpro,$aa_detallesnuevos,$ai_totalfilasnuevas,$ls_codtie,$aa_seguridad)
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
		$lb_valido=$this->uf_select_detallesdev($ls_coddev,&$la_detallesviejos,&$li_totalfilasviejas,$ls_codtie);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1


		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codart"][$li_i])
				{
				  if($la_detallesviejos["candev"][$li_j] != $aa_detallesnuevos["candev"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}

			}
				if (!$lb_existe)
				{
				  $ls_codpro=$aa_detallesnuevos["codart"][$li_i];
				  $ls_denpro=$aa_detallesnuevos["denart"][$li_i];
				  $ls_prepro=$aa_detallesnuevos["prepro"][$li_i];
				  $ls_canpro=$aa_detallesnuevos["canpro"][$li_i];
				  $ls_candev=$aa_detallesnuevos["candev"][$li_i];
				  $ls_porimp=$aa_detallesnuevos["porimp"][$li_i];
				  $ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
	  			  $ls_proveeedor=$aa_detallesnuevos["proveedor"][$li_i];
	  			  $ls_codproveedor=$aa_detallesnuevos["cod_pro"][$li_i];
	  			  $ls_condmerc=$aa_detallesnuevos["condmerc"][$li_i];


				  if ($ls_candev<>"0,00")
				    $lb_valido=$this->uf_guardar_detallesdev($ls_coddev,$ls_codpro,$ls_candev,$ls_prepro,$ls_porimp,$ls_codalm,$ls_codproveedor,$ls_proveeedor,$ls_codtie,$ls_condmerc,$aa_seguridad);
				}
			if ($lb_update)
			{

			   	 $ls_codpro=$aa_detallesnuevos["codart"][$li_i];
				 $ls_denpro=$aa_detallesnuevos["denart"][$li_i];
				 $ls_prepro=$aa_detallesnuevos["prepro"][$li_i];
				 $ls_canpro=$aa_detallesnuevos["canpro"][$li_i];
				 $ls_candev=$aa_detallesnuevos["candev"][$li_i];
				 $ls_porimp=$aa_detallesnuevos["porimp"][$li_i];
				 $ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
	  			 $ls_proveeedor=$aa_detallesnuevos["proveedor"][$li_i];
	  			 $ls_codproveedor=$aa_detallesnuevos["cod_pro"][$li_i];
	  			 $ls_condmerc=$aa_detallesnuevos["condmerc"][$li_i];
			    if ($ls_candev<>"0,00")
				  $lb_valido=$this->uf_update_detallesdev($ls_coddev,$ls_codpro,$ls_candev,$ls_prepro,$ls_porimp,$ls_codalm,$ls_codproveedor,$ls_codtie,$ls_condmerc,$aa_seguridad);

			}


		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codpro"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i])
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_detallesdev($ls_coddev,$la_detallesviejos["codpro"][$li_j],$ls_codtie,$aa_seguridad);

			}
		}
	return $lb_valido;
	}

function uf_actualizar_factura($ls_numfac,$ls_codpro,$ls_candev,$ls_tipact,$ls_codproveedor,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_detdevolucion
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numfac ()N�mero de la factura.
		//			    - $ls_codpro( c�digo del producto).
		//				- $ls_canpro (cantidad de producto).
		//				- $ls_prepro(precio del producto).
		//              - $ld_porimp( impuesto al producto).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];

		if($ls_tipact=="A"){
		   $ls_cadena= "UPDATE sfc_detfactura SET candev=candev+".$ls_candev." WHERE codemp='".$ls_codemp."' and codtiend='".$ls_codtie."' AND numfac='".$ls_numfac."' AND codart='".$ls_codpro."' AND cod_pro='".$ls_codproveedor."';";
		}
		else{
		   $ls_cadena= "UPDATE sfc_detfactura SET candev=candev-".$ls_candev." WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codart='".$ls_codpro."' and codtiend='".$ls_codtie."' AND cod_pro='".$ls_codproveedor."';";
		}

	//	$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detdevolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{

				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el Detalle ".$ls_codpro." de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();

			}

		}
		return $lb_valido;
	}

	function uf_delete_nota($ls_numnot,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->datoemp["codemp"];

		$lb_valido=$this->uf_select_nota_devolucion($ls_numnot,$ls_codtie);
		if ($lb_valido)
		{
			$ls_cadena=" DELETE FROM sfc_nota
						 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND nro_documento='".$ls_numnot."'";
			//print $ls_cadena."<br>";

		    $li_numrows=$this->io_sql->execute($ls_cadena);
			if(($li_numrows==false)&&($this->io_sql->message!="")){
				 $lb_valido=false;
				 $this->io_sql->rollback();
				// print $ls_cadena;
		 	}
			else
			{
				 if($li_numrows>0)
				 {

				 	/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino la Nota ".$ls_numnot." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					//$this->io_sql->commit();
				 }
				 else
				 {
					$lb_valido=false;
					$this->io_sql->rollback();
				 }
	        }
		}

		return $lb_valido;
	}


	function uf_actualizar_estdevolucion($ls_coddev,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_detdevolucion
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numfac ()N�mero de la factura.
		//			    - $ls_codpro( c�digo del producto).
		//				- $ls_canpro (cantidad de producto).
		//				- $ls_prepro(precio del producto).
		//              - $ld_porimp( impuesto al producto).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];


		$ls_cadena= "UPDATE sfc_devolucion SET estdev='A' WHERE codemp='".$ls_codemp."' and codtiend='".$ls_codtie."' and coddev='".$ls_coddev."' ;";

		//print $ls_cadena;
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_estdevolucion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{

				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo el Estatus de la Devolucion ".$ls_coddev." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();

			}

		}
		return $lb_valido;
	}

function uf_select_nota_devolucion($ls_numnot,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_nota
		// Parameters:  - $ls_numnot( Codigo de la nota de credito).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];


		$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND nro_documento='".$ls_numnot."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_nota ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				//$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}




}/*FIN DE LA CLASE */
?>
