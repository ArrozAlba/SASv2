<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cliente
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_entidadcrediticia
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_entidadcrediticia()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->seguridad  = new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include       = new sigesp_include();
	$io_connect       = $io_include->uf_conectar();
	$this->io_sql     = new class_sql($io_connect);
	$this->datoemp    = $_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg     = new class_mensajes();
}


	function uf_select_entidadcrediticia($ls_codentidad)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT *
		            FROM   sfc_entidadcrediticia
		            WHERE  codemp='".$ls_codemp."'  AND  cod_entidad='".$ls_codentidad."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_entidadcrediticia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

    function uf_select_den_ent_cred($ls_denominacion)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_denominacion=trim($ls_denominacion);

		$ls_cadena="SELECT *
		            FROM   sfc_entidadcrediticia
		            WHERE  codemp='".$ls_codemp."'  AND  denominacion='".$ls_denominacion."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_entidadcrediticia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=false;
			}
			else
			{
				$lb_valido=true;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
	}

	function uf_guardar_entidadcrediticia($ls_codentidad,$ls_denominacion,$ls_direccion,$ls_telefono,$ls_email,$ls_pagweb,$ls_codest,$ls_codpai,$ls_codmun,$ls_codpar,$aa_seguridad)
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
		$ls_codentidad=trim($ls_codentidad);
		$ls_denominacion=trim($ls_denominacion);

		$lb_valden=$this->uf_select_den_ent_cred($ls_denominacion);
		if($lb_valden)
		{
				$lb_existe=$this->uf_select_entidadcrediticia($ls_codentidad);

				if(!$lb_existe)
				{
					$ls_cadena= " INSERT INTO sfc_entidadcrediticia(codemp,cod_entidad,denominacion,direccion,telefono,email,paginaweb,codest,codpai,codmun,codpar)
								  VALUES ('".$ls_codemp."','".$ls_codentidad."','".$ls_denominacion."','".$ls_direccion."','".$ls_telefono."','".$ls_email."',
										  '".$ls_pagweb."','".$ls_codest."','".$ls_codpai."','".$ls_codmun."','".$ls_codpar."')";
					$ls_evento="INSERT";
				}
				else
				{
					$ls_cadena= "UPDATE sfc_entidadcrediticia
								 SET denominacion='".$ls_denominacion."', direccion='".$ls_direccion."',
									 telefono    ='".$ls_telefono."', email ='".$ls_email."',  paginaweb='".$ls_pagweb."',
									 codpai      ='".$ls_codpai."',   codest='".$ls_codest."', codmun   ='".$ls_codmun."',
									 codpar      ='".$ls_codpar."'
								 WHERE codemp='".$ls_codemp."'   AND  cod_entidad='".$ls_codentidad."' ";
					$ls_evento="UPDATE";
				}
				$this->io_sql->begin_transaction();
				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->is_msgc="Error en metodo uf_guardar_entidadcrediticia".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					$this->io_sql->rollback();
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						$this->io_sql->commit();
						if($ls_evento=="INSERT")
						{
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="INSERT";
							$ls_descripcion ="Inserto la Unidad Crediticia ".$ls_denominacion." Asociado a la Empresa ".$ls_codemp;
							$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
						}
						else
						{
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="UPDATE";
							$ls_descripcion ="Actualizo la Unidad Crediticia ".$ls_denominacion." Asociado a la Empresa ".$ls_codemp;
							$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
						}
						$this->io_msgc="Registro Incluido!!!";
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
		}
		else
		{
		    $this->io_msgc="La Entidad Crediticia ya ha sido registrada";
		}
		return $lb_valido;
	}


	function uf_delete_entidadcrediticia($ls_codentidad,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_entidadcrediticia($ls_codentidad);
		if($lb_existe)
		{
		    	$ls_cadena= "DELETE FROM sfc_entidadcrediticia
							 WHERE codemp='".$ls_codemp."'  AND  cod_entidad='".$ls_codentidad."'";
				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_entidadcrediticia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						$this->io_sql->commit();
						////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� la Unidad Crediticia ".$ls_codentidad." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               ////////////////////////////*/

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


}
?>
