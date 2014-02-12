<?php
class sigesp_cfg_c_monedas
 {
    var $ls_sql="";
	var $io_msg_error;
	
	function sigesp_cfg_c_monedas()//Constructor de la Clase.
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
        require_once("../shared/class_folder/class_funciones.php");
		$this->seguridad  = new sigesp_c_seguridad();		  
        $this->io_funcion = new class_funciones();
		$io_conect        = new sigesp_include();
		$conn             = $io_conect->uf_conectar();
		$this->la_emp     = $_SESSION["la_empresa"];
		$this->io_sql     = new class_sql($conn); //Instanciando  la clase sql
		$this->io_msg     = new class_mensajes();
		$this->ds_moneda  = new class_datastore();
	}


	function uf_guardar_moneda($as_codigo,$as_denmoneda,$as_codpais,$as_abremon,$as_estatus,$aa_seguridad)
	{  	   
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_guardar_moneda
	//	Access:  public
	//	Arguments: $ar_datos,$aa_seguridad	//   
	//	Returns:	$lb_valido= Variable que devuelve true si la operación fue exitosa de lo contrario devuelve false 
	//	Description: Este metodo guarda la información de las monedas
	//  Creado por: Ing. Jennifer Rivero                  
	//  Fecha de Creación: 2008/11/19
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	     if ($as_estatus=="C")
		 {
			$ls_sql=" UPDATE sigesp_moneda ".
					" SET denmon='".$as_denmoneda."',".
					"     codpai='".$as_codpais."',  ".
					"     abrmon='".$as_abremon."'   ".
					" WHERE codmon='".$as_codigo."'";
					
			$this->io_sql->begin_transaction();             
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data==false)
			   {
				 $this->io_sql->rollback();
				 $this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_guardar_moneda;ERROR->".
				                        $this->io_funcion->uf_convertirmsg($this->io_sql->message));   
				 $lb_valido=false;
			   }
			else
			   {   
				 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				 $ls_evento="UPDATE";
				 $ls_descripcion ="Actualizó en CFG Nueva Moneda ".$as_codigo;
				 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				 $aa_seguridad["ventanas"],$ls_descripcion);
				 /////////////////////////////////         SEGURIDAD               ///////////////////////////
				 $this->io_sql->commit();
				 $lb_valido=true;
				 $this->io_msg->message("Registro Actualizado !!!");
			   }	  	
			return $lb_valido;
		 }
	     else
		 {
				$ls_sql=" INSERT INTO sigesp_moneda(codmon, denmon, codpai, estmonpri, abrmon) ".
					    "        VALUES ('".$as_codigo."', '".$as_denmoneda."', '".$as_codpais."', '0','".$as_abremon."');";
				$this->io_sql->begin_transaction();
				$rs_data=$this->io_sql->execute($ls_sql);
				if ($rs_data==false)
				{
					$this->io_sql->rollback();
					$this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_guardar_moneda; ERROR->".
										   $this->io_funcion->uf_convertirmsg($this->io_sql->message));   
					 $lb_valido=false;
				}
				else
				{   
					 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					 $ls_evento="INSERT";
					 $ls_descripcion ="Insertó en CFG Nueva Monedas ".$as_codigo;
					 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
					 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
					 $aa_seguridad["ventanas"],$ls_descripcion);
					 /////////////////////////////////         SEGURIDAD               ///////////////////////////
					 $this->io_sql->commit();
					 $lb_valido=true;
					 $this->io_msg->message("Registro Incluido !!!");
				 }	  	
				 return $lb_valido;	
		  }
	      $this->io_sql->close();
	 }// fin del function 
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_moneda_ordencompra($as_codigo, &$ls_valor1,&$ls_valor2)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_buscar_moneda_ordencompra
	//	Access:  public
	//	Arguments:	
	//	  Returns:	
	//	Description: 
	//  Creado por : Ing. Jennifer Rivero
	//  Fecha de Creación  : 2008-11-19
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;	   
		$ls_sql=" select count(codmon)   ".
                "   from soc_ordencompra ".
                "   where codmon='".$as_codigo."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data==false)
		{
			$this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_buscar_moneda_ordencompra; ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
		}
		else
		{
			$li_numrows=$this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			{  
				$ls_valor1=$li_numrows;  
			}
			else
			{
			  	$ls_valor1=0;
			}	 
		}// fin del else
		
		
		$ls_sql=" select count(codmon) ".
                "   from sigesp_moneda ".
                "   where codmon='".$as_codigo."'".
				"   and  (estmonpri='1' or  estmonpri='2')";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data==false)
		{
			$this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_buscar_moneda_ordencompra; ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
		}
		else
		{
			$li_numrows2=$this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			{  
				$ls_valor2=$li_numrows2;  
			}
			else
			{
			  	$ls_valor2=0;
			}	 
		}// fin del else
	    return $lb_valido;
	}// fin de uf_buscar_moneda_ordencompra
//-----------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_moneda($as_codigo,$aa_seguridad)
	{   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_delete_moneda
		//	Access:  public
		//	Arguments:	
		//	  Returns:	
		//	Description: Este método Elimina una moenda
		//  Creado por : Ing. Jennifer Rivero
		//  Fecha de Creación  : 2008-11-19
		/////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = false;
		$ls_valor1=0;
		$ls_valor2=0;
		$lb_valido =  $this->uf_buscar_moneda_ordencompra($as_codigo, $ls_valor1,$ls_valor2);
		if (($ls_valor1==0)&&($ls_valor2==0))
		{		
			$ls_sql    = " DELETE FROM sigesp_monedas WHERE codmon='".$as_codigo."'";
			$this->io_sql->begin_transaction();
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data===false)
			{ 
				  $this->io_msg_error="CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_delete_moneda;ERROR->".
									   $this->io_funcion->uf_convertirmsg($this->io_sql->message);   
			}
			else
			{
				 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
				 $ls_evento="DELETE";
				 $ls_descripcion ="Eliminó en CFG la Moneda ".$as_codigo;
				 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				 $aa_seguridad["ventanas"],$ls_descripcion);
				 /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
				  $this->io_sql->commit();
				  $lb_valido=true;
				  $this->io_msg->message("Registro Eliminado !!!");
			 }
		}
		else
		{
			  $this->io_msg->message("No se puede eliminar la moneda esta asociada a una orden de compra o es moneda Principal o secundaria !!!");
		}
		return $lb_valido;
	}// fin de uf_delete_moneda
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_agregar($as_codigo,$ad_fecha,$as_tasacam1,$as_tasacam2,$aa_seguridad)	                         
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_agregar
		//	Access:  public
		//	Arguments:	
		//	  Returns:	
		//	Description: Este método Elimina una moenda
		//  Creado por : Ing. Jennifer Rivero
		//  Fecha de Creación  : 2008-11-19
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecha=$this->io_funcion->uf_convertirdatetobd($ad_fecha);
		
		$as_tasacam1=str_replace(".","",$as_tasacam1);
	    $as_tasacam1=str_replace(",",".",$as_tasacam1);
		
		$as_tasacam2=str_replace(".","",$as_tasacam2);
	    $as_tasacam2=str_replace(",",".",$as_tasacam2);
		
		$ls_contar=$this->uf_buscar_dt_monedas2($as_codigo,$ad_fecha);
		if ($ls_contar==0)
		{
			$ls_sql=" INSERT INTO sigesp_dt_moneda(codmon, fecha, tascam1, tascam2)".
					"    VALUES ('".$as_codigo."', '".$ad_fecha."', ".$as_tasacam1.", ".$as_tasacam2.");"; 
					
			$this->io_sql->begin_transaction();
			$rs_data=$this->io_sql->execute($ls_sql);
			if ($rs_data==false)
			{
				$this->io_sql->rollback();
				$this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_agregar; ERROR->".
									   $this->io_funcion->uf_convertirmsg($this->io_sql->message));   
				$lb_valido=false;
			}
			else
			{   
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				 $ls_evento="INSERT";
				 $ls_descripcion ="Insertó en CFG el detalle ".$as_codigo. " a la fecha".$ad_fecha;
				 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
				 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
				 $aa_seguridad["ventanas"],$ls_descripcion);
				 /////////////////////////////////         SEGURIDAD               ///////////////////////////
				 $this->io_sql->commit();
				 $lb_valido=true;			 
			}	
		}
		else
		{
			$this->io_msg->message("Debe Seleccionar otra fecha !!!");
		}
		return $lb_valido;  		
	}// fin de uf_agregar
///--------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
   function uf_buscar_dt_monedas($as_codigo)
   {
   		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_buscar_dt_monedas
		//	Access:  public
		//	Arguments:	
		//	  Returns:	
		//	Description: Este método Elimina una moenda
		//  Creado por : Ing. Jennifer Rivero
		//  Fecha de Creación  : 2008-11-19
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
   		$lb_valido=true;	   
		$ls_sql=" select *   ".
                "   from sigesp_dt_moneda ".
                "   where codmon='".$as_codigo."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data==false)
		{
			$this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_buscar_dt_monedas; ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_moneda->data=$this->io_sql->obtener_datos($rs_data);			      
			}
			else
			{
				$lb_valido=false;
			}		
			$this->io_sql->free_result($rs_data);
		}// fin del else
		return $lb_valido;
   }// fin de uf_buscar_dt_monedas
//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_dt_moneda($as_codigo,$as_fecha,$aa_seguridad)
	{   
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_delete_dt_moneda
		//	Access:  public
		//	Arguments:	
		//	  Returns:	
		//	Description: Este método Elimina una moenda
		//  Creado por : Ing. Jennifer Rivero
		//  Fecha de Creación  : 2008-11-19
		/////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = false;
		$as_fecha=$this->io_funcion->uf_convertirdatetobd($as_fecha);
	    $ls_sql    = " DELETE FROM sigesp_dt_moneda WHERE codmon='".$as_codigo."' AND fecha='".$as_fecha."'"; 
		
		$this->io_sql->begin_transaction();
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{ 
			  $this->io_msg_error="CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_delete_dt_moneda;ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message);   
		}
		else
		{
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="DELETE";
			 $ls_descripcion ="Eliminó en CFG el detalle de la Moneda ".$as_codigo." a la fecha ".$as_fecha;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			 $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
			 $aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               ///////////////////////////// 
			 $this->io_sql->commit();
			 $lb_valido=true;
			 $this->io_msg->message("Registro Eliminado !!!");
		}		
		return $lb_valido;
	}// fin de uf_delete_moneda
//--------------------------------------------------------------------------------------------------------------------------------------
   function uf_buscar_dt_monedas2($as_codigo,$as_fecha)
   {
   		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_buscar_dt_monedas
		//	Access:  public
		//	Arguments:	
		//	  Returns:	
		//	Description: Este método Elimina una moenda
		//  Creado por : Ing. Jennifer Rivero
		//  Fecha de Creación  : 2008-11-19
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
   		$lb_valido=true;	   
		$ls_sql=" select *   ".
                "   from sigesp_dt_moneda ".
                "   where codmon='".$as_codigo."'".
				"   and fecha='".$as_fecha."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data==false)
		{
			$this->io_msg->message("CLASE->SIGESP_CFG_C_MONEDAS; METODO->uf_buscar_dt_monedas2; ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_contar=1;		      
			}
			else
			{
				$lb_valido=false;
				$ls_contar=0;
			}		
			$this->io_sql->free_result($rs_data);
		}// fin del else
		return $ls_contar;
   }// fin de uf_buscar_dt_monedas
 //-------------------------------------------------------------------------------------------------------------------------------------
}//Fin de la Clase.
?>