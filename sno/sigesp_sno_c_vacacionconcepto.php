<?php
class sigesp_sno_c_vacacionconcepto
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_vacacionconcepto()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_vacacionconcepto
		//		   Access: public (sigesp_sno_d_vacacionconcepto)
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
		
	}// end function sigesp_sno_c_vacacionconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_vacacionconcepto)
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
	function uf_select_vacacionconcepto($as_campo,$as_valor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_vacacionconcepto
		//		   Access: public(sigesp_sno_c_concepto, uf_guardar)
		//	    Arguments: as_campo  // campo por el cual se quiere filtrar
		//	    		   as_valor  // valor del campor por el cual se quiere filtrar
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el conceptovacacion está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT ".$as_campo." ".
				"  FROM sno_conceptovacacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND ".$as_campo."='".$as_valor."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_select_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_vacacionconcepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_vacacionconcepto($as_codconc,$as_forsalvac,$ai_acumaxsalvac,$ai_minsalvac,$ai_maxsalvac,$as_consalvac,
										$as_forpatsalvac,$ai_minpatsalvac,$ai_maxpatsalvac,$as_forreivac,$ai_acumaxreivac,$ai_minreivac,
										$ai_maxreivac,$as_conreivac,$as_forpatreivac,$ai_minpatreivac,$ai_maxpatreivac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_vacacionconcepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto					as_forsalvac  // fórmula de salida
		//				   ai_acumaxsalvac  // acumulado máximo de salida		ai_minsalvac  // valor mínimo de salida
		//				   ai_maxsalvac  // valor máximo de salida				as_consalvac  // condición de salida
		//				   as_forpatsalvac  // fórmula patrón salida			ai_minpatsalvac  // valor mínimo patrón salida
		//				   ai_maxpatsalvac  // valor máximo patrón salida		as_forreivac  // fórmula de reintegro
		//				   ai_acumaxreivac  // acumulado máximo de reintegro 	ai_minreivac  // valor mínimo de reintegro
		//				   ai_maxreivac  // valor maximo de reintegro			as_conreivac  // condición de reintegro
		//				   as_forpatreivac  // formula patrón de reintegro		ai_minpatreivac  // valor mínimo de reintegro
		//				   ai_maxpatreivac  // valor máximo de reintegro		aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el vacacionconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptovacacion(codemp,codnom,codconc,forsalvac,acumaxsalvac,minsalvac,maxsalvac,consalvac,forpatsalvac,".
				"minpatsalvac,maxpatsalvac,forreivac,acumaxreivac,minreivac,maxreivac,conreivac,forpatreivac,minpatreivac,maxpatreivac)".
				"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codconc."','".$as_forsalvac."',".$ai_acumaxsalvac.",".
				"".$ai_minsalvac.",".$ai_maxsalvac.",'".$as_consalvac."','".$as_forpatsalvac."',".$ai_minpatsalvac.",".$ai_maxpatsalvac.",".
				"'".$as_forreivac."',".$ai_acumaxreivac.",".$ai_minreivac.",".$ai_maxreivac.",'".$as_conreivac."','".$as_forpatreivac."',".
				"".$ai_minpatreivac.",".$ai_maxpatreivac.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_insert_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Conceptovacacion ".$as_codconc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("El concepto de vacación fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_insert_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_vacacionconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_vacacionconcepto($as_codconc,$as_forsalvac,$ai_acumaxsalvac,$ai_minsalvac,$ai_maxsalvac,$as_consalvac,
										$as_forpatsalvac,$ai_minpatsalvac,$ai_maxpatsalvac,$as_forreivac,$ai_acumaxreivac,$ai_minreivac,
										$ai_maxreivac,$as_conreivac,$as_forpatreivac,$ai_minpatreivac,$ai_maxpatreivac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_vacacionconcepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto					as_forsalvac  // fórmula de salida
		//				   ai_acumaxsalvac  // acumulado máximo de salida		ai_minsalvac  // valor mínimo de salida
		//				   ai_maxsalvac  // valor máximo de salida				as_consalvac  // condición de salida
		//				   as_forpatsalvac  // fórmula patrón salida			ai_minpatsalvac  // valor mínimo patrón salida
		//				   ai_maxpatsalvac  // valor máximo patrón salida		as_forreivac  // fórmula de reintegro
		//				   ai_acumaxreivac  // acumulado máximo de reintegro 	ai_minreivac  // valor mínimo de reintegro
		//				   ai_maxreivac  // valor maximo de reintegro			as_conreivac  // condición de reintegro
		//				   as_forpatreivac  // formula patrón de reintegro		ai_minpatreivac  // valor mínimo de reintegro
		//				   ai_maxpatreivac  // valor máximo de reintegro		aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el vacacionconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_conceptovacacion ".
				"   SET forsalvac='".$as_forsalvac."', ".
				"		acumaxsalvac=".$ai_acumaxsalvac.", ".
				"		minsalvac=".$ai_minsalvac.", ".
				"		maxsalvac=".$ai_maxsalvac.", ".
				"		consalvac='".$as_consalvac."', ".
				"		forpatsalvac='".$as_forpatsalvac."', ".
				"		minpatsalvac=".$ai_minpatsalvac.", ".
				"		maxpatsalvac=".$ai_maxpatsalvac.", ".
				"		forreivac='".$as_forreivac."', ".
				"		acumaxreivac=".$ai_acumaxreivac.", ".
				"		minreivac=".$ai_minreivac.", ".
				"		maxreivac=".$ai_maxreivac.", ".
				"		conreivac='".$as_conreivac."', ".
				"		forpatreivac='".$as_forpatreivac."', ".
				"		minpatreivac=".$ai_minpatreivac.", ".
				"		maxpatreivac=".$ai_maxpatreivac." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codconc='".$as_codconc."'";

		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_update_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el conceptovacacion ".$as_codconc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("EL concepto de vacación fué Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_update_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_vacacionconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codconc,$as_forsalvac,$ai_acumaxsalvac,$ai_minsalvac,$ai_maxsalvac,$as_consalvac,
						$as_forpatsalvac,$ai_minpatsalvac,$ai_maxpatsalvac,$as_forreivac,$ai_acumaxreivac,$ai_minreivac,
						$ai_maxreivac,$as_conreivac,$as_forpatreivac,$ai_minpatreivac,$ai_maxpatreivac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_vacacionconcepto)
		//	    Arguments: as_codconc  // código de concepto					as_forsalvac  // fórmula de salida
		//				   ai_acumaxsalvac  // acumulado máximo de salida		ai_minsalvac  // valor mínimo de salida
		//				   ai_maxsalvac  // valor máximo de salida				as_consalvac  // condición de salida
		//				   as_forpatsalvac  // fórmula patrón salida			ai_minpatsalvac  // valor mínimo patrón salida
		//				   ai_maxpatsalvac  // valor máximo patrón salida		as_forreivac  // fórmula de reintegro
		//				   ai_acumaxreivac  // acumulado máximo de reintegro 	ai_minreivac  // valor mínimo de reintegro
		//				   ai_maxreivac  // valor maximo de reintegro			as_conreivac  // condición de reintegro
		//				   as_forpatreivac  // formula patrón de reintegro		ai_minpatreivac  // valor mínimo de reintegro
		//				   ai_maxpatreivac  // valor máximo de reintegro		aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda el vacacionconcepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		$ai_acumaxsalvac=str_replace(".","",$ai_acumaxsalvac);
		$ai_acumaxsalvac=str_replace(",",".",$ai_acumaxsalvac);
		$ai_minsalvac=str_replace(".","",$ai_minsalvac);
		$ai_minsalvac=str_replace(",",".",$ai_minsalvac);
		$ai_maxsalvac=str_replace(".","",$ai_maxsalvac);
		$ai_maxsalvac=str_replace(",",".",$ai_maxsalvac);
		$ai_minpatsalvac=str_replace(".","",$ai_minpatsalvac);
		$ai_minpatsalvac=str_replace(",",".",$ai_minpatsalvac);
		$ai_maxpatsalvac=str_replace(".","",$ai_maxpatsalvac);
		$ai_maxpatsalvac=str_replace(",",".",$ai_maxpatsalvac);
		$ai_acumaxreivac=str_replace(".","",$ai_acumaxreivac);
		$ai_acumaxreivac=str_replace(",",".",$ai_acumaxreivac);
		$ai_minreivac=str_replace(".","",$ai_minreivac);
		$ai_minreivac=str_replace(",",".",$ai_minreivac);
		$ai_maxreivac=str_replace(".","",$ai_maxreivac);
		$ai_maxreivac=str_replace(",",".",$ai_maxreivac);
		$ai_minpatreivac=str_replace(".","",$ai_minpatreivac);
		$ai_minpatreivac=str_replace(",",".",$ai_minpatreivac);
		$ai_maxpatreivac=str_replace(".","",$ai_maxpatreivac);
		$ai_maxpatreivac=str_replace(",",".",$ai_maxpatreivac);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_vacacionconcepto("codconc",$as_codconc)===false)
				{
					$lb_valido=$this->uf_insert_vacacionconcepto($as_codconc,$as_forsalvac,$ai_acumaxsalvac,$ai_minsalvac,$ai_maxsalvac,
																 $as_consalvac,$as_forpatsalvac,$ai_minpatsalvac,$ai_maxpatsalvac,
																 $as_forreivac,$ai_acumaxreivac,$ai_minreivac,$ai_maxreivac,$as_conreivac,
																 $as_forpatreivac,$ai_minpatreivac,$ai_maxpatreivac,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El concepto de vacación ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_vacacionconcepto("codconc",$as_codconc)))
				{
					$lb_valido=$this->uf_update_vacacionconcepto($as_codconc,$as_forsalvac,$ai_acumaxsalvac,$ai_minsalvac,$ai_maxsalvac,
																 $as_consalvac,$as_forpatsalvac,$ai_minpatsalvac,$ai_maxpatsalvac,
																 $as_forreivac,$ai_acumaxreivac,$ai_minreivac,$ai_maxreivac,$as_conreivac,
																 $as_forpatreivac,$ai_minpatreivac,$ai_maxpatreivac,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Concepto de Vacación no existe, no lo puede actualizar.");
				}
				break;
		}
		
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_vacacionconcepto($as_codconc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_vacacionconcepto
		//		   Access: public (sigesp_sno_d_vacacionconcepto)
		//	    Arguments: as_codconc  // código de concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el concepto vacación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_conceptovacacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codconc='".$as_codconc."'";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_delete_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el concepto vacación ".$as_codconc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Concepto de Vacación fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Vacación Concepto MÉTODO->uf_delete_vacacionconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_vacacionconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>