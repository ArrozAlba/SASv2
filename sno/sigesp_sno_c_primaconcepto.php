<?php
class sigesp_sno_c_primaconcepto
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_primaconcepto()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_primaconcepto
		//		   Access: public (sigesp_sno_d_primaconcepto)
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
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
		
	}// end function sigesp_sno_c_primaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_primaconcepto)
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
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_primaconcepto($as_campo,$as_valor,$ai_anopri)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_primaconcepto
		//		   Access: public (sigesp_sno_c_concepto, uf_guardar)
		//	    Arguments: as_campo  // Campo por el cual se va  a filtrar
		//				   as_valor  // Valor del campo del que se quire filtrar
		//				   ai_anopri  // Año de la prima
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la prima concepto está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT  ".$as_campo." ".
				"  FROM sno_primaconcepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND ".$as_campo."='".$as_valor."'";
		if(!empty($ai_anopri))
		{
			$ls_sql=$ls_sql."   AND anopri=".$ai_anopri."";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_select_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_primaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_primaconcepto($as_codconc,$ai_anopri,$ai_valpri,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_primaconcepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//				   ai_anopri  // Año de la prima
		//				   ai_valpri  // valor de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el primaconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_primaconcepto(codemp,codnom,codconc,anopri,valpri)VALUES('".$this->ls_codemp."','".$this->ls_codnom."',".
				"'".$as_codconc."',".$ai_anopri.",".$ai_valpri.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_insert_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Prima Concepto ".$as_codconc." año ".$ai_anopri." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("Las primas conceptos fue registrada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_insert_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_primaconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_primaconcepto($as_codconc,$ai_anopri,$ai_valpri,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_primaconcepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//				   ai_anopri  // Año de la prima
		//				   ai_valpri  // valor de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//   	  Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el primaconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_primaconcepto ".
				"   SET valpri=".$ai_valpri." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codconc='".$as_codconc."'".
				"   AND anopri=".$ai_anopri."";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_update_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la Prima Concepto ".$as_codconc." año ".$ai_anopri." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Prima Concepto fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_update_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_primaconcepto		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codconc,$ai_anopri,$ai_valpri,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_primaconcepto)
		//	    Arguments: as_codconc  // código de concepto
		//				   ai_anopri  // Año de la prima
		//				   ai_valpri  // valor de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda el primaconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_valpri=str_replace(".","",$ai_valpri);
		$ai_valpri=str_replace(",",".",$ai_valpri);
		switch ($as_existe)
		{
			case "FALSE":
				if(!($this->uf_select_primaconcepto("codconc",$as_codconc,$ai_anopri)))
				{
					$lb_valido=$this->uf_insert_primaconcepto($as_codconc,$ai_anopri,$ai_valpri,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La prima concepto ya existe, no la puede incluir");
				}
				break;

			case "TRUE":
				if(($this->uf_select_primaconcepto("codconc",$as_codconc,$ai_anopri)))
				{
					$lb_valido=$this->uf_update_primaconcepto($as_codconc,$ai_anopri,$ai_valpri,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La prima concepto no existe, no la puede actualizar");
				}
				break;
		}
		
		return $lb_valido;
	}// end function uf_guardar	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_primaconcepto($as_codconc,$ai_anopri,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_primaconcepto
		//		   Access: public (sigesp_sno_d_primaconcepto)
		//	    Arguments: as_codconc  // código de concepto
		//				   ai_anopri  // Año de la prima
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el concepto vacación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_primaconcepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codconc='".$as_codconc."'".
				"   AND anopri=".$ai_anopri."";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_delete_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la Prima Concepto ".$as_codconc." año ".$ai_anopri." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("La Prima concepto fue Eliminada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_delete_primaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_primaconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_primahijos($as_codconc,&$ai_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_primahijos
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codconc // Código del concepto
		//				   ai_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un código de concepto y número de hijos calcula la prima por hijos de personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_valor=0;
		$lb_valido=true;
		$ls_sql="SELECT COALESCE(valpri,0) as valpri".
				"  FROM sno_primaconcepto ".
				" WHERE sno_primaconcepto.codemp='".$this->ls_codemp."'".
				"   AND sno_primaconcepto.codnom='".$this->ls_codnom."'".
				"   AND sno_primaconcepto.codconc='".$as_codconc."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_select_primahijos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_valor=$row["valpri"];
			}			
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_primahijos	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_primaantiguedad($as_codconc,$as_anoser,&$ai_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_primaantiguedad
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codconc // Código del concepto
		//				   ai_anoser // año de servicios del personal
		//				   ai_valor // valor del token
		//	      Returns: lb_valido True si se sustituye correctamente el valor ó False si hubo error 
		//	  Description: función que dado un código de concepto y número de hijos calcula la prima por antiguedad de personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_valor=0;
		$lb_valido=true;
		$ls_sql="SELECT anopri, COALESCE(valpri,0) as valpri".
				"  FROM sno_primaconcepto ".
				" WHERE sno_primaconcepto.codemp='".$this->ls_codemp."'".
				"   AND sno_primaconcepto.codnom='".$this->ls_codnom."'".
				"   AND sno_primaconcepto.codconc='".$as_codconc."'".
				" ORDER BY anopri ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Prima Concepto MÉTODO->uf_select_primaantiguedad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_anopri=0;
			$ai_valor=0;
			$ai_valpri=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($li_anopri==0)
				{
					$li_anopri=$row["anopri"];
				}
				
				if($as_anoser>=$li_anopri)
				{
					$ai_valor=$ai_valor+$row["valpri"];
					$li_anopri=$li_anopri+1;
					$ai_valpri=$row["valpri"];
				}
			}			
			if($as_anoser>=$li_anopri)
			{
				$as_anoser=$as_anoser+1;
				$ai_valor=$ai_valor+(($as_anoser-$li_anopri)*$ai_valpri);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_primaantiguedad
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>