<?php
class sigesp_snorh_c_permiso
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_permiso()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	     Function: sigesp_snorh_c_permiso
		//		   Access: public (sigesp_snorh_d_permiso)
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
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_permiso)
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
	function uf_select_permiso($as_codper, $ai_numper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_permiso
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // número del permiso
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el permiso está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numper ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND numper='".$ai_numper."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_select_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_permiso
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_correlativo($as_codper, &$ai_numper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_correlativo
		//		   Access: private (uf_guardar) 
		//	    Arguments: as_codper  // código del personal
		//				   ai_numper  // código del permiso
		//	      Returns: lb_valido True si lo obtuvo correctamente ó False si hubo error
		//	  Description: Funcion que busca el correlativo del último permiso  y genera el nuevo correlativo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_numper=1;
		$ls_sql="SELECT numper as codigo ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				" ORDER BY numper DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_load_correlativo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_numper=intval($row["codigo"]+1);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_correlativo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_permiso($as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_numhoras,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_permiso
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   ad_feciniper  // fecha inicio
		//				   ad_fecfinper  // fecha fin
		//				   ai_numdiaper  // número de días
		//				   ai_afevacper  // afecta vacaciones
		//				   ai_tipper  // tipo
		//				   as_obsper  // observación
		//				   as_remper  // Si el permiso es remunerado ó no
		//				   as_numhoras // numero de horas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de permiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_permiso".
				"(codemp,codper,numper,feciniper,fecfinper,numdiaper,afevacper,tipper,obsper,remper,tothorper)VALUES".
				"('".$this->ls_codemp."','".$as_codper."',".$ai_numper.",'".$ad_feciniper."','".$ad_fecfinper."',".
				"".$ai_numdiaper.",'".$ai_afevacper."',".$ai_tipper.",'".$as_obsper."','".$as_remper."','".$as_numhoras."')";
		
		$this->io_sql->begin_transaction()	;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_insert_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Permiso ".$ai_numper." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Permiso fue Registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_insert_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_permiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_permiso($as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_numhoras, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_permiso
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   ad_feciniper  // fecha inicio
		//				   ad_fecfinper  // fecha fin
		//				   ai_numdiaper  // número de días
		//				   ai_afevacper  // afecta vacaciones
		//				   ai_tipper  // tipo
		//				   as_obsper  // observación
		//				   as_remper  // Si el permiso es remunerado ó no
		// 				   as_numhoras // numero de horas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de permiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_permiso ".
				"	SET feciniper='".$ad_feciniper."', ".
				"		fecfinper='".$ad_fecfinper."', ".
				"		numdiaper=".$ai_numdiaper.", ".
				"		afevacper=".$ai_afevacper.", ".
				"		tipper=".$ai_tipper.", ".
				"		obsper='".$as_obsper."', ".
				"		remper='".$as_remper."', ".
				"		tothorper='".$as_numhoras."' ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND numper=".$ai_numper."";
		
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_update_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el Permiso ".$ai_numper." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Permiso fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_update_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_insert_permiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_numhoras,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_permiso)
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   ad_feciniper  // fecha inicio
		//				   ad_fecfinper  // fecha fin
		//				   ai_numdiaper  // número de días
		//				   ai_afevacper  // afecta vacaciones
		//				   ai_tipper  // tipo
		//				   as_obsper  // observación
		//				   as_remper  // Si el permiso es remunerado ó no
		//				   as_numhoras // numero de horas
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla de permiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ad_feciniper=$this->io_funciones->uf_convertirdatetobd($ad_feciniper);
		$ad_fecfinper=$this->io_funciones->uf_convertirdatetobd($ad_fecfinper);
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_permiso($as_codper,$ai_numper)===false)
				{
					$lb_valido=$this->uf_load_correlativo($as_codper,$ai_numper);
					if($lb_valido)
					{
						$lb_valido=$this->uf_insert_permiso($as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,
															$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_numhoras,
															$aa_seguridad);
					}
				}
				else
				{
					$this->io_mensajes->message("El Permiso ya existe, no lo puede incluir.");
				}
				break;
							
			case "TRUE":
				if(($this->uf_select_permiso($as_codper,$ai_numper)))
				{
					$lb_valido=$this->uf_update_permiso($as_codper,$ai_numper,$ad_feciniper,$ad_fecfinper,$ai_numdiaper,
														$ai_afevacper,$ai_tipper,$as_obsper,$as_remper,$as_numhoras,
														$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Permiso no existe, no lo puede actualizar.");
				}
				break;
		}		
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_permiso($as_codper,$ai_numper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_permiso
		//		   Access: public (sigesp_snorh_d_permiso)
		//	    Arguments: as_codper  // Código de Personal
		//				   ai_numper  // Número del Permiso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina en la tabla de permiso
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND numper=".$ai_numper."";
				
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_delete_permiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="eliminó el Permiso ".$ai_numper." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Permiso fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
        		$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_delete ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
    }// end function uf_delete_permiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_totaldiaspermiso($as_codper,$ad_fecdes,$ad_fechas,&$ai_dias)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_totaldiaspermiso
		//		   Access: public sigesp_sno_c_vacacion
		//	    Arguments: as_codper  // Código de Personal
		//				   ad_fecdes  // Fecha Desde
		//				   ad_fechas  // Fecha Hasta
		//				   ai_dias  // número de días
		//	      Returns: lb_valido True si el select se realizó con éxito ó False si hubo error
		//	  Description: Funcion que obtiene el total de permisos 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_dias=0;
		$lb_valido=true;
		$ls_sql="SELECT sum(numdiaper) as  total ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND feciniper>='".$ad_fecdes."'".
				"   AND feciniper<='".$ad_fechas."'".
				"   AND afevacper=0";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_load_totaldiaspermiso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				if ($row["total"]=="")
				{
					$ai_dias=0;
				}
				else
				{				
					$ai_dias=$row["total"];
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_totaldiaspermiso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_diaspermisos($as_codper,$ad_fecdes,$ad_fechas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_diaspermisos
		//		   Access: private
		//	    Arguments: as_codper  // Código de Personal
		//				   ad_fecdes  // Fecha Desde
		//				   ad_fechas  // Fecha Hasta
		//	      Returns: li_total Total de permisos dado un rango de fecha
		//	  Description: Funcion que verifica todos los permisos que tuvo el personal para un rango de fecha
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/09/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=0;
		$ls_sql="SELECT feciniper, numdiaper ".
				"  FROM sno_permiso ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				"   AND feciniper>='".$ad_fecdes."' ".
				"   AND feciniper<='".$ad_fechas."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$this->io_mensajes->message("CLASE->Permiso MÉTODO->uf_select_diaspermisos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_numdiaper=$row["numdiaper"];
				$ld_feciniper=$row["feciniper"];
				$ld_feciniper=$this->io_funciones->uf_convertirfecmostrar($ld_feciniper);
				for($li_i=1;$li_i<=$li_numdiaper;$li_i++)
				{
					if(substr($ld_feciniper,3,2)==substr($ad_fecdes,5,2))
					{
						if($this->io_sno->uf_nro_sabydom($ld_feciniper,$ld_feciniper)==0)
						{
							$li_total=$li_total+1;
						}
					}
					$ld_feciniper=$this->io_sno->uf_suma_fechas($ld_feciniper,1);
				}
			}
			$this->io_sql->free_result($rs_data);
		}
		return $li_total;
	}// end function uf_select_diaspermisos
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>