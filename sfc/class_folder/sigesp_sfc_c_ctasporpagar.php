<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_ctasporpagar
 // Autor:       - Ing. Oscar Sequera
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla nota.
 // Fecha:       - 12/11/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_ctasporpagar
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_ctasporpagar()
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
	    $ls_codemp=$this->datoemp["codemp"];
		/*$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND numnot='".$ls_numnot."' AND codciecaj='".$ls_codciecaj."';";*/

		$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND numnot='".$ls_numnot."' AND codtiend='".$ls_codtie."';";

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

function uf_guardar_nota($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_nro_factura,$ls_codciecaj,$ls_estcie,$ls_codtie,$aa_seguridad)
	{

		$ls_codemp=$this->datoemp["codemp"];
		//$ls_codtie=$_SESSION["ls_codtienda"];
		$lb_existe=$this->uf_select_nota($ls_numnot,$ls_codtie);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */


		 $ls_fecnot=$this->io_funcion->uf_convertirdatetobd($ls_fecnot);

		if(!$lb_existe)
		{

            /*$ls_cadena= " INSERT INTO sfc_nota (codemp,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_factura,codtiend,codciecaj,estcie)
			              VALUES ('".$ls_codemp."','".$ls_codcli."','".$ls_numnot."','".$ls_dennot."','".$ls_tipnot."','".$ls_fecnot."',".$ld_monto.",'".$ls_estnot."','".$ls_nro_factura."','".$ls_codtie."','".$ls_codciecaj."','".$ls_estcie."')";*/

			$ls_cadena= " INSERT INTO sfc_nota (codemp,codcli,numnot,dennot,tipnot,fecnot,monto,estnota,nro_documento,codtiend)
			              VALUES ('".$ls_codemp."',".$ls_codcli.",'".$ls_numnot."','".$ls_dennot."','".$ls_tipnot."','".$ls_fecnot."',".$ld_monto.",'".$ls_estnot."','".$ls_nro_factura."','".$ls_codtie."')";

			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_nota " .
					" SET dennot='".$ls_dennot."', tipnot='".$ls_tipnot."', estnota='".$ls_estnot."', fecnot='".$ls_fecnot."', monto=".$ld_monto.", nro_documento='".$ls_nro_factura."' " .
					" WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."';";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		//print $ls_cadena.'<br>';
		$this->io_sql->begin_transaction();
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
					$ls_descripcion ="Inserto la Nota de Debito ".$ls_numnot.", de la Factura ".$ls_nro_factura." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo la Nota de Debito ".$ls_numnot.", de la Factura ".$ls_nro_factura." Asociado a la Empresa ".$ls_codemp;
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


	function uf_delete_nota($ls_numnot,$ls_codtie,$aa_seguridad)
	{

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		//$ls_codtie=$_SESSION["ls_codtienda"];
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
					print $ls_cadena;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						///////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimino la Nota de Debito ".$ls_numnot." Asociado a la Empresa ".$ls_codemp;
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
			$this->io_msg->message("No Se Puede Eliminar, Esta Siendo Procesada");
		}
		return $lb_valido;
	}

    function uf_actualizar_nota($ls_codcli,$ls_numnot,$ls_estnot,$ls_nro_factura,$ls_fecnot,$ls_codtie,$aa_seguridad)
	{
		$lb_valido=true;
		$ls_codemp=$this->datoemp["codemp"];
		//$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_fecnot=$this->io_funcion->uf_convertirdatetobd($ls_fecnot);

		$ls_cadena= "UPDATE sfc_nota
		             SET estnota='".$ls_estnot."', fecnot='".$ls_fecnot."'
					 WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND numnot='".$ls_numnot."'
					 AND codcli=".$ls_codcli." AND nro_documento='".$ls_nro_factura."'";
		$ls_evento="UPDATE";
		$this->io_sql->begin_transaction();
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
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion ="Inserto la Nota de Debito ".$ls_numnot." Asociada a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo la Nota ".$ls_numnot." Asociada a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				$this->io_msgc="Registro Actualizado!!!";
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_msgc="No actualizo el registro";
			}
		}
		return $lb_valido;
	}
}
?>
