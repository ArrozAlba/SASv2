<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_nota
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla nota.
 // Fecha:       - 16/02/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_nota
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_nota()
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
	$ls_codtie=$_SESSION["ls_codtienda"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}


function uf_select_nota($ls_numnot,$ls_codtie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_nota
		// Parameters:  - $ls_numnot( Codigo de la nota de credito).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];

		$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."';";
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
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}


function uf_guardar_nota($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_numdoc,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_nota
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numnot( numero de nota).
		//			    - $ls_tipnot( tipo de nota cr�dito o d�bito).
		//				- $ls_dennot( denominaci�n o descripcion de nota cr�dito).
		//              - $ls_fecnot( Fecha emision nota).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];

		$lb_existe=$this->uf_select_nota($ls_numnot,$ls_codtie);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */


		 $ls_fecnot=$this->io_funcion->uf_convertirdatetobd($ls_fecnot);

		if(!$lb_existe)
		{

            $ls_cadena= " INSERT INTO sfc_nota (codemp,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_documento,codtiend,estcie)
			              VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numnot."','".$ls_dennot."','".$ls_tipnot."','".$ls_fecnot."',".$ld_monto.",'".$ls_estnot."','".$ls_numdoc."','".$ls_codtie."','N')";

			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
	//	print $ls_cadena;
		}
		else
		{
			$ls_cadena= "UPDATE sfc_nota
			             SET dennot='".$ls_dennot."', tipnot='".$ls_tipnot."', estnota='".$ls_estnot."', fecnot='".$ls_fecnot."', monto=".$ld_monto.", nro_documento='".$ls_numdoc."'
						 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."';";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

        //print $ls_cadena;
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_nota".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			///$this->io_sql->rollback();
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
					$ls_descripcion ="Insertó la nota ".$ls_numnot." del cliente ".$ls_codcli." asociado al documento ".$ls_numdoc."
					 Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la nota ".$ls_numnot." Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				//$this->io_sql->commit();
			}
			else
			{
				//$this->io_sql->rollback();
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
function uf_guardar_nota_factura($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_numdoc,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_nota
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numnot( numero de nota).
		//			    - $ls_tipnot( tipo de nota cr�dito o d�bito).
		//				- $ls_dennot( denominaci�n o descripcion de nota cr�dito).
		//              - $ls_fecnot( Fecha emision nota).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];

		$lb_existe=$this->uf_select_nota($ls_numnot,$ls_codtie);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */


		 $ls_fecnot=$this->io_funcion->uf_convertirdatetobd($ls_fecnot);

		if(!$lb_existe)
		{

            $ls_cadena= " INSERT INTO sfc_nota (codemp,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_documento,codtiend,estcie)
			              VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numnot."','".$ls_dennot."','".$ls_tipnot."','".$ls_fecnot."',".$ld_monto.",'".$ls_estnot."','".$ls_numdoc."','".$ls_codtie."','N')";

			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
	//	print $ls_cadena;
		}
		else
		{
			$ls_cadena= "UPDATE sfc_nota
			             SET dennot='".$ls_dennot."', tipnot='".$ls_tipnot."', estnota='".$ls_estnot."', fecnot='".$ls_fecnot."', monto=".$ld_monto.", nro_documento='".$ls_numdoc."'
						 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."';";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

        //print $ls_cadena;
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_nota".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Insertó la nota ".$ls_numnot." del cliente ".$ls_codcli." asociado al documento ".$ls_numdoc."
					 Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la nota ".$ls_numnot." Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie;
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

function uf_update_actualizaestnot($ls_numnot,$ls_estnot,$aa_seguridad)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_nota
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//			    - $ls_numnot( numero de nota).
		//			    - $ls_tipnot( tipo de nota cr�dito o d�bito).
		//				- $ls_dennot( denominaci�n o descripcion de nota cr�dito).
		//              - $ls_fecnot( Fecha emision nota).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];

		$lb_existe=$this->uf_select_nota($ls_numnot,$ls_codtie);
		 //$ls_fecnot=$this->io_funcion->uf_convertirdatetobd($ls_fecnot);

		if(!$lb_existe)
		{

		}
		else
		{
			$ls_cadena= "UPDATE sfc_nota ".
			            "SET estnota='".$ls_estnot."' ".
						"WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."';";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}
		//$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_actualizarestnot".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			//$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				/************    SEGURIDAD    **************/
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó estatus de la nota ".$ls_numnot." Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie." ".$ls_nomtie." por proceso de facturación";
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
			}
			else
			{
				//$this->io_sql->rollback();
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

	function uf_delete_nota($ls_numnot,$ls_codtie,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$lb_existe=$this->uf_select_nota($ls_numnot,$ls_codtie);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_nota
							  WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."'";
				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_nota ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					//print $ls_cadena;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/*////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ////////////////////////////*/
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

function uf_select_nota_debito($ls_codcli,$ls_numfac,$ls_codtie,&$ls_numnot,&$ls_monto)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_nota
		// Parameters:  - $ls_numnot( Codigo de la nota de credito).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];

		$ls_cadena="Select monto,numnot FROM sfc_nota" .
					" WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codcli='".$ls_codcli."' AND numnot='".$ls_numfac."' " .
					" and nro_documento='".$ls_numfac."' and tipnot='CXC' ;";
		$rs_datauni=$this->io_sql->select($ls_cadena);
print $ls_cadena."<br>";
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_nota_debito ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$ls_monto=$row["monto"];
				$ls_numnot=$row["numnot"];
				//print $ls_monto."--".$ls_numnot."<br>";
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


function uf_actualizar_nota_debito($ls_codcli,$ls_numfac,$li_montonota,$ls_numnotdeb,$ls_codtie,$la_seguridad)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_nota
		// Parameters:  - $ls_numnot( Codigo de la nota de credito).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];



		$ls_cadena="UPDATE sfc_nota" .
					" SET monto=".$li_montonota." WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."' AND codtiend='".$ls_codtie."'" .
					" AND numnot='".$ls_numfac."' and nro_documento='".$ls_numfac."' AND numnot='".$ls_numnotdeb."'" .
		            " and tipnot='CXC' ;";

		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_actualizarestnot".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				/************    SEGURIDAD    **************/
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó monto de la nota de debito ".$ls_numnotdeb." Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie."  por proceso de facturación";
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],$la_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();

			}

		}
	return $lb_valido;
}

function uf_actualizar_estatusnota_debito($ls_codcli,$ls_numfac,$ls_numnotdeb,$ls_estatus,$ls_codtie,$la_seguridad)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_nota
		// Parameters:  - $ls_numnot( Codigo de la nota de credito).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];



		$ls_cadena="UPDATE sfc_nota" .
					" SET estnota='".$ls_estatus."' WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."' AND codtiend='".$ls_codtie."'" .
					" AND nro_documento='".$ls_numfac."' AND numnot='".$ls_numnotdeb."'" .
		            " and tipnot='CXC' ;";

		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_update_actualizarestnot".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				/************    SEGURIDAD    **************/
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó estatus de la nota de debito ".$ls_numnotdeb." Asociado a la Empresa ".$ls_codemp." de la Tienda ".$ls_codtie."  por proceso de facturación";
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],$la_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();

			}

		}
	return $lb_valido;
}



}
?>
