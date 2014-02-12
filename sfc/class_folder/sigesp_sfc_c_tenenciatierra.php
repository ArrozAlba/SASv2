<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_tenenciatierra
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_tenenciatierra
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_tenenciatierra()
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


	function uf_select_tenencia($ls_codtenencia)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_tenenciatierra
		            WHERE codtenencia='".$ls_codtenencia."'";
		//$this->io_sql->begin_transaction();
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_tenencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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


	function uf_guardar_tenencia($ls_codtenencia,$ls_denominacion,$la_seguridad)
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
		$lb_existe=$this->uf_select_tenencia($ls_codtenencia);

		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sfc_tenenciatierra(codtenencia,denominacion)
			              VALUES ('".$ls_codtenencia."','".$ls_denominacion."') ";
			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_tenenciatierra
			             SET codtenencia='".$ls_codtenencia."',denominacion='".$ls_denominacion."' WHERE codtenencia='".$ls_codtenencia."'";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);


		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_tenencia".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
					$ls_descripcion ="Inserto la tenencia de tierra ".$ls_codtenencia." ".$ls_denominacion." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
													$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
													$la_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo la tenencia de tierra ".$ls_codtenencia." ".$ls_denominacion." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
													$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
													$la_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				//print "NO JOMBREEEE";
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


	function uf_delete_tenencia($ls_codtenencia,$la_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_tenencia($ls_codtenencia);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_tenenciatierra
							  WHERE codtenencia='".$ls_codtenencia."'";
				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_tenencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $ls_cadena;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� la tenencia de tierra ".$ls_codtenencia." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
														$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
														$la_seguridad["ventanas"],$ls_descripcion);
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


}
?>
