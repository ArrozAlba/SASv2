<?php
class sigesp_snorh_c_diaferiado
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_diaferiado()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_diaferiado
		//		   Access: public (sigesp_snorh_d_diaferiado)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_diaferiado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_diaferiado)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_diaferiado($as_campo,$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_diaferiado
		//		   Access: private
		//	    Arguments: as_campo  // Campo por el que se quiere filtrar
		//	    		   as_valor  // Valor del campo
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el día feriado está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." ".
				"  FROM sno_diaferiado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND ".$as_campo."='".$as_valor."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_select_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_diaferiado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_diaferiado($ad_fecfer,$as_nomfer,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_diaferiado
		//		   Access: private
		//	    Arguments: ad_fecfer  // Fecha del Feriado
		//				   as_nomfer  // Descripción del Feriado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_diaferiado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_diaferiado".
		        "(codemp,fecfer,nomfer)VALUES('".$this->ls_codemp."','".$ad_fecfer."','".$as_nomfer."')";
				
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_insert_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Día Feriado ".$ad_fecfer;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Dia Feriado fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_insert_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_diaferiado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_diaferiado($ad_fecfer,$as_nomfer,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_diaferiado
		//		   Access: private
		//	    Arguments: ad_fecfer  // Fecha del Feriado
		//				   as_nomfer  // Descripción del Feriado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_diaferiado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_diaferiado ".
				"   SET nomfer='".$as_nomfer."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND fecfer='".$ad_fecfer."'";
				
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_update_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Día Feriado ".$ad_fecfer;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Día feriado fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_update_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_diaferiado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$ad_fecfer,$as_nomfer,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_diaferiado)
		//	    Arguments: ad_fecfer  // Fecha del Feriado
		//				   as_nomfer  // Descripción del Feriado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si guardo ó False si hubo error al guardar
		//	  Description: Funcion que almacena el día feriado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ad_fecfer=$this->io_funciones->uf_convertirdatetobd($ad_fecfer);
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_diaferiado("fecfer",$ad_fecfer)===false)
				{
					$lb_valido=$this->uf_insert_diaferiado($ad_fecfer,$as_nomfer,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Dia Feriado ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_diaferiado("fecfer",$ad_fecfer)))
				{
					$lb_valido=$this->uf_update_diaferiado($ad_fecfer,$as_nomfer,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Dia Feriado no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_diaferiado($ad_fecfer,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_diaferiado
		//		   Access: public (sigesp_snorh_d_diaferiado)
		//	    Arguments: ad_fecfer  // Fecha del Feriado
		//				   as_nomfer  // Descripción del Feriado
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el día feriado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecfer=$this->io_funciones->uf_convertirdatetobd($ad_fecfer);
		$ls_sql="DELETE ".
		        "  FROM sno_diaferiado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND fecfer='".$ad_fecfer."'";
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_delete_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Día Feriado ".$ad_fecfer;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Día feriado fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_delete_diaferiado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}// end function uf_delete_diaferiado
		return $lb_valido;
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_feriados($ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_feriados
		//		   Access: private
		//	    Arguments: ad_fecdes  // Fecha desde
		//	    		   ad_fechas  // Fecha Hasta
		//	      Returns: li_total Toal de días feriados
		//	  Description: Funcion que cuenta la cantidad de días feriados dada una fecha
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=0;
		$ls_sql="SELECT fecfer ".
				"  FROM sno_diaferiado ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND fecfer>='".$ad_fecdes."' ".
				"   AND fecfer<='".$ad_fechas."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Dia Feriado MÉTODO->uf_select_feriados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecfer=$row["fecfer"];
				$ld_fecfer=$this->io_funciones->uf_convertirfecmostrar($ld_fecfer);
				if($this->io_sno->uf_nro_sabydom($ld_fecfer,$ld_fecfer)==0)
				{
					$li_total=$li_total+1;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $li_total;
	}// end function uf_select_feriados
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>