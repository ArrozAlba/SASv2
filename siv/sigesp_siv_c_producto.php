
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
class sigesp_siv_c_producto
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_siv_c_producto()
	{
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}//fin de la function sigesp_saf_c_metodos()
	
   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_producto($as_codemp,$as_codseg,$as_codfam,$as_desprod,$as_codclase,$as_codprod,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_producto
		//		   Access: public
		//	    Arguments: as_codseg  // código del segmento
		//                 as_codfam  // codigo de la familia
		//                 as_codclase  // codigo de la clase
		//				   as_desprod  // descripcion del producto
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta los datos que entran como parámetro en la tabla siv_producto
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 14/11/2008				Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe="true";
		$ls_sql = "INSERT INTO siv_producto(codemp,codseg,codfami,codclase,codprod,desproducto)". 
				  "VALUES( '".$as_codemp."','".$as_codseg."','".$as_codfam."','".$as_codclase."','".$as_codprod."','".$as_desprod."')"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			//print ($this->io_sql->message);
			$this->io_msg->message("CLASE-> MÉTODO->uf_guardar_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el producto".$as_codprod." perteneciente a la clase ".$as_codclase. " a la familia ".$as_codfam." y al segmento".$as_codseg." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
                $this->io_sql->commit();
		}
	    return  $lb_valido;
	}// end function uf_guardar_clase	
	//-----------------------------------------------------------------------------------------------------------------------------------

    function  uf_actualizar_producto($as_codemp,$as_codseg,$as_codfam,$as_desprod,$as_codclase,$as_codprod,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_actualizar_producto
		//         Access: public 
		//     Argumentos: $as_empresa    // codigo de empresa                
		//				   $as_codseg    // codigo del segmento         	      
		//			       $as_desprod    // denominacion del producto          
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza los datos del producto en la tabla siv_producto
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 14/11/2008 				Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		
		$ls_sql="UPDATE siv_producto".
				"   SET desproducto='".$as_desprod."'".
				" WHERE codemp =  '".$as_codemp."'". 
				"   AND codseg =  '".$as_codseg ."'".
				"   AND codfami =  '".$as_codfam ."'".
				"   AND codclase= '".$as_codclase."'".
				"   AND codprod= '".$as_codprod."'"; 
				
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE-> MÉTODO->uf_actualizar_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la demominación del producto ".$as_codprod." perteneciente a la clase ".$as_codclase." a la familia ".$as_codfam." y al segmento".$as_codseg." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();

		}
	    return $lb_valido;
	}// fin de la function uf_actualizar_producto

	function uf_elimina_producto($as_codemp,$as_codseg,$as_codfam,$as_codclase,$as_codprod,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_elimina_producto
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codseg //codigo del segmento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que elimina un determinado activo en la tabla siv_segmento
		//	   Creado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 10/10/2008								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$this->io_sql->begin_transaction();	
		$ls_sql = " DELETE FROM siv_producto".
				  " WHERE siv_producto.codemp= '".$as_codemp. "'".
				  " AND siv_producto.codseg= '".$as_codseg. "'".
				  " AND siv_producto.codfami='".$as_codfam. "'".
				  " AND siv_producto.codclase='".$as_codclase. "'".
				  " AND siv_producto.codprod='".$as_codprod. "'"; 
				  
		$li_exec=$this->io_sql->execute($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE-> MÉTODO->uf_elimina_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el producto ".$as_codprod." perteneciente a la clase ".$as_codclase." a la familia ".$as_codfam." y al segmento ".$as_codseg." de la Empresa ".$as_codemp;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$this->io_sql->commit();
		}
		return $lb_valido;
	} //fin de uf_elimina_producto
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_siv_select_producto($as_codemp,$as_codseg,$as_codfam,$as_codclase,$as_codprod)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_producto
		//         Access: public 
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_codseg //codigo del segmento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica si existe datos en la tabla siv_producto
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM siv_producto ".
				  "WHERE codemp='".$as_codemp."' ".
				  "AND codseg='".$as_codseg."'". 
				  "AND codfami='".$as_codfam."'".
				  "AND codclase='".$as_codclase."'".
				  "AND codprod='".$as_codprod."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE-> MÉTODO->uf_siv_select_producto ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{$lb_valido=true;}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_siv_select_producto
  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
}//fin de la class sigesp_siv_c_segmento
?>
