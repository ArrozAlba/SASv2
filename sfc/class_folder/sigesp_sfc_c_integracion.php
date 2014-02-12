<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_factura
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla sfc_factura y sfc_detfactura.
 // Fecha:       - 16/02/2007     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_integracion
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sfc_c_integracion()
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

/*****************************************************************************************************************************/	
/*************************        INTEGRACION CON INVENTARIO             *****************************************************/	
/*****************************************************************************************************************************/	

	function actualizar_existencia($ls_codart,$ls_codalm,$ls_cant,$ls_operador,$ls_tipo)
	{
	    $ls_codemp=$this->datoemp["codemp"];
		$ld_cant=$this->funsob->uf_convertir_cadenanumero($ls_cant);
		
		if($ls_tipo=='G')
		{
		   $ls_cadena="UPDATE sim_articulo SET exiart=exiart".$ls_operador." ".$ld_cant." where codemp='".$ls_codemp."' and codart='".$ls_codart."'";
		}
		elseif($ls_tipo=='A')
		{
		   $ls_cadena="UPDATE sim_articuloalmacen SET existencia=existencia".$ls_operador." ".$ld_cant." where codemp='".$ls_codemp."' and codart='".$ls_codart."' and codalm='".$ls_codalm."'";
		}
		
		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
         
		 
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en actualizando existencia de almacen".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;
				/*
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="INSERT";
					$ls_descripcion ="Insertó el Propietario ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               ////////////////////////////*/
			
				$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				$lb_valido=false;
				$this->io_msgc="Error actualizando existencia!!";
			}
		}
      return $lb_valido;
	}
	
	
}
?>
