<?php 
class sigesp_sss_c_actualizar_ventana
{
	var $obj="";
	var $SQL;
	var $siginc;
	var $con;

	function sigesp_sss_c_actualizar_ventana()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad;
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
	}

	function  uf_sss_load_sistemas(&$aa_sistemas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_sistemas
		//         Access: public  
		//      Argumento: $aa_sistemas //arreglo de sistemas
		//	      Returns: Retorna un Booleano
		//    Description: Función que carga los datos de los sistemas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codsis,nomsis".
		        "  FROM sss_sistemas".
				" ORDER BY nomsis ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->actualizarventana MÉTODO->uf_sss_load_sistemas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_sistemas["codsis"][$li_pos]=$row["codsis"];  
				$aa_sistemas["nomsis"][$li_pos]=$row["nomsis"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuariosdisponibles

	function  uf_sss_insert_ventana($as_codsis,$as_nomven,$as_titven,$as_desven,$aa_seguridad )
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_ventana
		//         Access: public  
		//      Argumento: $as_codsis    //codigo de sistema
		//                 $as_nomven    //nombre fisico de la ventana
		//                 $as_titven    //titulo de la ventana
		//                 $as_desven    //descripcion de la ventana
		//                 $aa_seguridad //arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que inserta una ventana de un sistema en la tabla de sss_sistemas_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "INSERT INTO sss_sistemas_ventanas (codsis, nomven, titven, desven) ".
				  " VALUES('".$as_codsis."','".$as_nomven."','".$as_titven."','".$as_desven."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row==false)
		{
			$this->io_msg->message("CLASE->actualizarventana MÉTODO->uf_sss_insert_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la ventana ".$as_nomven." Asociada al Sistema ".$as_codsis;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end function uf_sss_insert_ventana

	function  uf_sss_select_ventana($as_codsis,$as_nomven)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_ventana
		//         Access: public  
		//      Argumento: $as_codsis    //codigo de sistema
		//                 $as_nomven    //nombre fisico de la ventana
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica la existencia de una ventana en la tabla de sss_sistemas_ventanas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/11/2005								Fecha Última Modificación : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT * FROM sss_sistemas_ventanas".
		 		  " WHERE codsis = '".$as_codsis."'".
				  " AND nomven ='".$as_nomven."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->actualizarventana MÉTODO->uf_sss_select_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function  uf_sss_select_ventana
	
	function uf_sss_update_ventana($as_codsis,$as_nomven,$as_titven,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_update_ventana
		//         Access: public  
		//      Argumento: $as_codsis    // codigo de sistema
		//  			   $as_nomven    // nombre de ventana
		//  			   $as_titven    // titulo de la ventana
		//  			   $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de modificar el nombre logico de una ventana del sistema
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 02/11/2006									Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=false;
		 $ls_sql = "UPDATE sss_sistemas_ventanas".
		           "   SET titven='". $as_titven ."'".
				   " WHERE codsis='" . $as_codsis ."'".
				   "   AND nomven='" . $as_nomven ."'";
       // $this->io_sql->begin_transaction();
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->actualizarventana MÉTODO->uf_sss_update_ventana ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el titulo de la ventana ".$as_titven." del modulo ".$as_codsis;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
	  return $lb_valido;
	}  // end  function uf_sss_update_ventana



}//fin de la class sigesp_sss_c_actualizar_ventana

?>
