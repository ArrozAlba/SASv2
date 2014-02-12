<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cajero
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cajero.
 // Fecha:       - 04/12/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_caja
{

	 var $io_funcion;
	 var $io_msgc;
	 var $io_sql;
	 var $datoemp;
	 var $io_msg;
	 var $codtie;


	function sigesp_sfc_c_caja()
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
		$this->codtie=$_SESSION["ls_codtienda"];
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_msg=new class_mensajes();
	}


	function uf_select_caja($ls_codcaja,$ls_codtienda)
	{
			//////////////////////////////////////////////////////////////////////////////////////////
			// Function:    - uf_select_unidad
			// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
			// Descripcion: - Funcion que busca un registro en la bd.
			//////////////////////////////////////////////////////////////////////////////////////////
	
			$ls_codemp=$this->datoemp["codemp"];
			$ls_codtie=$_SESSION["ls_codtienda"];
	
				$ls_cadena="SELECT * FROM sfc_caja
							WHERE codemp='".$ls_codemp."' AND cod_caja='".$ls_codcaja."' AND codtiend='".$ls_codtienda."'";
	
			//print $ls_cadena;
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
					$this->io_msgc="Registro no encontrado";
				}
			}
			return $lb_valido;
	}

	function uf_verificar_movimientos_caja($ls_codtienda,$ls_codcaja)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_verificar_movimientos_caja
		// Parameters:  - $ls_codtienda( Codigo de la tiende).
		//				- $ls_codcaja (Codigo de la caja)
		// Descripcion: - Funcion que busca si existen movimientos para una determinada caja
		//////////////////////////////////////////////////////////////////////////////////////////
	
		$ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="(SELECT cod_caja ".
				   "  FROM sfc_factura ".
				   " WHERE codemp='".$ls_codemp."' AND cod_caja='".$ls_codcaja."' AND codtiend='".$ls_codtienda."' ".
				   " LIMIT 1 )".
				   "UNION ".
				   "(SELECT cod_caja ".
				   "  FROM sim_orden_entrega ".
				   " WHERE codemp='".$ls_codemp."' AND cod_caja='".$ls_codcaja."' AND codtiend='".$ls_codtienda."' ".
				   " LIMIT 1)";
	
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);
	
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_verificar_movimientos_caja ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

	function uf_guardar_caja($ls_codcaja,$ls_desccaja,$ls_precot,$ls_prefac,$ls_predev,$ls_preped,$ls_sercot,$ls_serfac,$ls_serdev,$ls_serped,$ls_sernot,$ls_sercon,$ls_formalibre,$ls_precob,$ls_sercob,$ls_codtienda,$ls_preordent,$ls_serordent,$ls_formalibreordent,$aa_seguridad,&$lb_existe)
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
		//$ls_codtie=$_SESSION["ls_codtienda"];
		//print $ls_codtie."//";
		//print $ls_codcaja;
		$lb_existe=$this->uf_select_caja($ls_codcaja,$ls_codtienda);

		if(!$lb_existe)
		{
            $ls_cadena= "INSERT INTO sfc_caja(cod_caja,descripcion_caja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,codemp,sercon,formalibre,precob,sercob,codtiend,preordent,serordent,formalibreordent)
			              VALUES ('".$ls_codcaja."','".$ls_desccaja."','".$ls_precot."','".$ls_prefac."','".$ls_predev."','".$ls_preped."','".$ls_sercot."','".$ls_serfac."','".$ls_serdev."','".$ls_serped."','".$ls_sernot."','".$ls_codemp."','".$ls_sercon."','".$ls_formalibre."','".$ls_precob."','".$ls_sercob."','".$ls_codtienda."','".$ls_preordent."','".$ls_serordent."','".$ls_formalibreordent."')";
            //print $ls_cadena;  
			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_caja SET descripcion_caja='".$ls_desccaja."',precot='".$ls_precot."',prefac='".$ls_prefac."',predev='".$ls_predev."',preped='".$ls_preped."',sercot='".$ls_sercot."',serfac='".$ls_serfac."',serdev='".$ls_serdev."',serped='".$ls_serped."',sernot='".$ls_sernot."',sercon='".$ls_sercon."', formalibre='".$ls_formalibre."',precob='".$ls_precob."',sercob='".$ls_sercob."',preordent='".$ls_preordent."',serordent='".$ls_serordent."',formalibreordent='".$ls_formalibreordent."' WHERE codemp='".$ls_codemp."' AND cod_caja='".$ls_codcaja."' AND codtiend='".$ls_codtienda."'";

			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		//print $ls_cadena;

		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_caja".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
			//print $ls_cadena;
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
					$ls_descripcion ="Insert� la caja ".$ls_desccaja." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualiz� la caja ".$ls_desccaja." Asociado a la Empresa ".$ls_codemp;
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


	function uf_delete_caja($ls_codcaja,$ls_codtienda,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];

		$lb_existe=$this->uf_select_caja($ls_codcaja,$ls_codtienda);
		$lb_relacion=$this->uf_verificar_movimientos_caja($ls_codtienda,$ls_codcaja);
		if($lb_existe )
		{
			if(!$lb_relacion)
			{
			   	$ls_cadena= " DELETE FROM sfc_caja
							  WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtienda."' AND cod_caja='".$ls_codcaja."'";
				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_caja ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					//print $ls_cadena;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� la caja ".$ls_codcaja." Asociado a la Empresa ".$ls_codemp;
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
				$this->io_msg->message("La caja posee movimientos no puede ser eliminada");
				$lb_valido=false;
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
