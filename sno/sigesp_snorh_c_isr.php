<?php
class sigesp_snorh_c_isr
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_isr()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_isr
		//		   Access: public (sigesp_snorh_d_isr)
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
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_isr
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_isr)
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
		unset($this->io_fun_nomina);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_isr($as_codper,&$as_existe,&$ai_porisrper,&$ai_porisr,&$li_codconret,&$ai_bloqueado)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_isr
		//		   Access: public (sigesp_snorh_d_isr)
		//	    Arguments: as_codper  // Código de Personal
		//	    		   as_existe  // Si existe el impuesto
		//				   ai_porisrper  // porcentaje global
		//				   ai_porisr  // porcentaje por meses
		//				   ai_bloqueado  // si el mes esta bloqueado ó no
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que busca todos los meses definidos en impuesto sobre la renta del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//Obtener el % global que está definido en personal
		$ls_sql="SELECT porisrper ".
				"  FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)			
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_load_isr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_porisrper=$row["porisrper"];
				$ai_porisrper=$this->io_fun_nomina->uf_formatonumerico($ai_porisrper);	
			}
			$this->io_sql->free_result($rs_data);
		}
		$ld_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
		//Obtener el % específico de cada mes
		$ls_sql="SELECT codisr, porisr, codconret, ".
				"       (SELECT substr(sno_periodo.fechasper,6,2) AS MES ".
				"		   FROM sno_periodo, sno_nomina, sno_personalnomina ".
				"		  WHERE sno_nomina.espnom=0 ".
				"			AND sno_personalnomina.codper='".$as_codper."'". 
				"		    AND substr(sno_periodo.fechasper,1,4) = '".$ld_ano."' ".
				"   		AND sno_periodo.cerper = 1 ".
				"   		AND sno_periodo.codemp = sno_personalisr.codemp ".
				"		    AND substr(sno_periodo.fechasper,6,2) = sno_personalisr.codisr ".
				"   		AND sno_periodo.codemp = sno_nomina.codemp ".
				"			AND sno_periodo.codnom = sno_nomina.codnom ".
				"			AND sno_nomina.codemp = sno_personalnomina.codemp ".
				"			AND sno_nomina.codnom = sno_personalnomina.codnom ".
				"		  GROUP BY substr(sno_periodo.fechasper,6,2)) AS bloqueado ".
				"  FROM sno_personalisr ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				" ORDER BY codisr ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_load_isr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codisr=(integer)$row["codisr"];
				$ai_porisr[$ls_codisr]=$this->io_fun_nomina->uf_formatonumerico($row["porisr"]);
				$li_codconret=$row["codconret"];
				$ai_bloqueado[$ls_codisr]="";
				if($row["bloqueado"]!="")
				{					
					$ai_bloqueado[$ls_codisr]="disabled";					
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
    }// end function uf_load_isr
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_isr($as_codper, $as_codisr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_isr
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//			       as_codisr  // Código de ISR
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el impuesto sobre la resta está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codisr ".
				"  FROM sno_personalisr ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codisr='".$as_codisr."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_select_isr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_select_isr
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_isr($as_codper,$as_codisr,$ai_porisr,$ai_codconret,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_isr
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//			       as_codisr  // Código de ISR
		//			       ai_porisr  // Porcentaje de ISR
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla isr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_personalisr".
				"(codemp,codper,codisr,porisr,codconret)VALUES".
				"('".$this->ls_codemp."','".$as_codper."','".$as_codisr."',".$ai_porisr.",'".$ai_codconret."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_insert_isr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
 			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Porcentaje de ISR ".$as_codisr." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_isr
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_defecto($as_codper,$as_codisr,$ai_porisr)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_isr
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//			       as_codisr  // Código de ISR
		//			       ai_porisr  // Porcentaje de ISR
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla isr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_personalisr (codemp,codper,codisr,porisr,codconret) ".
				"SELECT '".$this->ls_codemp."','".$as_codper."','".$as_codisr."',".$ai_porisr.",'' ".
				"  FROM sno_personal ".
				" WHERE codper = '".$as_codper."' ".
				"   AND codper NOT IN (SELECT codper FROM sno_personalisr WHERE codisr='".$as_codisr."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_insert_isr_defecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
 			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_insert_defecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_isr($as_codper,$as_codisr,$ai_porisr,$ai_codconret,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_isr
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//			       as_codisr  // Código de ISR
		//			       ai_porisr  // Porcentaje de ISR
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla isr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_personalisr ".
				"   SET porisr=".$ai_porisr.", ".
				" 		codconret='".$ai_codconret."'".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codisr='".$as_codisr."'";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_update_isr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Porcentaje de ISR ".$as_codisr." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_isr
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_global($as_codper,$ai_porisrper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_global
		//		   Access: public (sigesp_snorh_d_isr)
		//	    Arguments: as_codper  // Código de Personal
		//			       ai_porisrper  // Porcentaje Global del personal
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de personal el impuesto sobre la renta global
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_porisrper=str_replace(".","",$ai_porisrper);
		$ai_porisrper=str_replace(",",".",$ai_porisrper);
		$lb_valido=true;
		$ls_sql="UPDATE sno_personal ".
				"   SET porisrper=".$ai_porisrper." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_update_global ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Porcentaje de ISR asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_global
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_codper,$as_codisr,$ai_porisr,$ai_codconret,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_isr)
		//	    Arguments: as_codper  // Código de Personal
		//			       as_codisr  // Código de ISR
		//			       ai_porisr  // Porcentaje de ISR
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//   	  Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que actualiza en la tabla isr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_porisr=str_replace(".","",$ai_porisr);
		$ai_porisr=str_replace(",",".",$ai_porisr);
		$lb_valido=false;		
		if($this->uf_select_isr($as_codper,$as_codisr)===false)
		{
			$lb_valido=$this->uf_insert_isr($as_codper,$as_codisr,$ai_porisr,$ai_codconret,$aa_seguridad);
		}
		else
		{
			$lb_valido=$this->uf_update_isr($as_codper,$as_codisr,$ai_porisr,$ai_codconret,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_isrpersonal($as_codper, $as_codisr, &$li_porisr, &$li_codconret='')
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_isrpersonal
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper  // Código de Personal
		//			       as_codisr  // Código de ISR
		//			       li_porisr // valor del porcentaje de isr del mes
		//	      Returns: lb_valido es true si se ejecuto correctamente el select y False si no se ejecuto
		//	  Description: Funcion que obtiene el impuesto sobre la renta dado un personal y un mes
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_porisr=0;
		$ls_sql="SELECT porisr, codconret ".
				"  FROM sno_personalisr ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."'".
				"   AND codisr='".$as_codisr."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->ISR MÉTODO->uf_load_isrpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_porisr=$row["porisr"];
				$li_codconret=$row["codconret"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_isrpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>