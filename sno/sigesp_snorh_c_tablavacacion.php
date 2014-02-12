<?php
class sigesp_snorh_c_tablavacacion
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_personal;
	var $ls_codemp;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_tablavacacion()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_tablavacacion
		//		   Access: public (sigesp_snorh_d_tablavacacion)
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
		require_once("sigesp_sno_c_personalnomina.php");
		$this->io_personal=new sigesp_sno_c_personalnomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_snorh_c_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_tablavacacion)
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
	function uf_select_tablavacacion($as_codtabvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tablavacacion
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la tabla de vacacion está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codtabvac ".
				"  FROM sno_tablavacacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_select_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tablavacacion($as_codtabvac,$as_dentabvac,$as_pertabvac,$ai_adequitabvac,$ai_aderettabvac,$ai_bonauttabvac,
									 $ai_anoserpre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tablavacacion
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   as_dentabvac  // Denominación
		//				   as_pertabvac  // período
		//				   ai_adequitabvac  // adelanta quincena
		//				   ai_aderettabvac  // adelanta retención
		//				   ai_bonauttabvac  // bono vacacional automático
		//				   ai_anoserpre  // Año de servicios previos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla sno_tablavacacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tablavacacion".
				"(codemp,codtabvac,dentabvac,pertabvac,adequitabvac,aderettabvac,bonauttabvac,anoserpre)VALUES('".$this->ls_codemp."','".$as_codtabvac."',".
				"'".$as_dentabvac."','".$as_pertabvac."',".$ai_adequitabvac.",".$ai_aderettabvac.",".$ai_bonauttabvac.",".$ai_anoserpre.")";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_insert_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la tabla de Vacación ".$as_codtabvac;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}		
		return $lb_valido;
	}// end function uf_insert_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tablavacacion($as_codtabvac,$as_dentabvac,$as_pertabvac,$ai_adequitabvac,$ai_aderettabvac,$ai_bonauttabvac,
									 $ai_anoserpre,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tablavacacion
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   as_dentabvac  // Denominación
		//				   as_pertabvac  // período
		//				   ai_adequitabvac  // adelanta quincena
		//				   ai_aderettabvac  // adelanta retención
		//				   ai_bonauttabvac  // bono vacacional automático
		//				   ai_anoserpre  // Año de servicios previos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla sno_tablavacacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_tablavacacion ".
				"   SET dentabvac = '".$as_dentabvac."', ".
				"		pertabvac = '".$as_pertabvac."', ".
				"		adequitabvac = ".$ai_adequitabvac.", ".
				"		aderettabvac = ".$ai_aderettabvac.", ".
				"		bonauttabvac = ".$ai_bonauttabvac.", ".
				"		anoserpre = ".$ai_anoserpre." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'";
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_update_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 		
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la tabla de Vacación ".$as_codtabvac;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codtabvac,$as_dentabvac,$as_pertabvac,$ai_adequitabvac,$ai_aderettabvac,$ai_bonauttabvac,
						$ai_anoserpre,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_snorh_d_tablavacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   as_dentabvac  // Denominación
		//				   as_pertabvac  // período
		//				   ai_adequitabvac  // adelanta quincena
		//				   ai_aderettabvac  // adelanta retención
		//				   ai_bonauttabvac  // bono vacacional automático
		//				   ai_anoserpre  // Año de servicios previos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que guarda en la tabla sno_tablavacacion
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;		
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_tablavacacion($as_codtabvac)===false)
				{
					$lb_valido=$this->uf_insert_tablavacacion($as_codtabvac,$as_dentabvac,$as_pertabvac,$ai_adequitabvac,$ai_aderettabvac,
										$ai_bonauttabvac,$ai_anoserpre,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La tabla de Vacación ya existe, no la puede incluir.");
				}
				break;
				
			case "TRUE":
				if(($this->uf_select_tablavacacion($as_codtabvac)))
				{
					$lb_valido=$this->uf_update_tablavacacion($as_codtabvac,$as_dentabvac,$as_pertabvac,$ai_adequitabvac,$ai_aderettabvac,
										$ai_bonauttabvac,$ai_anoserpre,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La tabla de Vacación no existe, no la puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_tablavacacion($as_codtabvac, $aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_tablavacacion
		//		   Access: public (sigesp_snorh_d_tablavacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacación
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//    Sescription: Funcion que elimina la tabla de vacación y todos los períodos asociados a ella
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if ($this->io_personal->uf_select_personalnomina("codtabvac",$as_codtabvac,"0")===false)   
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_delete_perido_lote($as_codtabvac, $aa_seguridad);
			if($lb_valido)
			{
				$ls_sql="DELETE ".
						"  FROM sno_tablavacacion ".
						" WHERE codemp='".$this->ls_codemp."'".
						"   AND codtabvac='".$as_codtabvac."'";
						
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_delete_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			} 
			
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó tabla de Vacación ".$as_codtabvac." y todos los períodos asociados a dicha tabla";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_sql->commit();
					$this->io_mensajes->message("La tabla de Vacación fue Eliminada.");
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_delete_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
			}
			else
			{
				$this->io_sql->rollback();
			}
		} 
		else
		{
			$this->io_mensajes->message("No se puede eliminar la tabla de Vacación. Hay personal asociado a esta.");
		}       
		return $lb_valido;
    }// end function uf_delete_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tablavacacion_periodo($as_codtabvac,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tablavacacion_periodo
		//		   Access: public (sigesp_snorh_d_tablavacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todos los períodos de una tabla de vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codtabvac, lappervac, diadisvac, diabonvac, diaadidisvac, diaadibonvac ".
				"  FROM sno_tablavacperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'".
				" ORDER BY lappervac";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_load_tablavacacion_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$li_lappervac=$row["lappervac"];
				$li_diadisvac=$row["diadisvac"];
				$li_diaadidisvac=$row["diaadidisvac"];
				$li_diabonvac=$row["diabonvac"];
				$li_diaadibonvac=$row["diaadibonvac"];

				$ao_object[$ai_totrows][1]="<input name=txtlappervac".$ai_totrows." type=text id=txtlappervac".$ai_totrows." class=sin-borde size=6 value='".$li_lappervac."' onKeyUp='javascript: ue_validarnumero(this);' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdiadisvac".$ai_totrows." type=text id=txtdiadisvac".$ai_totrows." class=sin-borde size=6 value='".$li_diadisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$ao_object[$ai_totrows][3]="<input name=txtdiaadidisvac".$ai_totrows." type=text id=txtdiaadidisvac".$ai_totrows." class=sin-borde size=6 value='".$li_diaadidisvac."' onKeyUp='javascript: ue_validarnumero(this);'>";
				$ao_object[$ai_totrows][4]="<input name=txtdiabonvac".$ai_totrows." type=text id=txtdiabonvac".$ai_totrows." class=sin-borde size=6 value='".$li_diabonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$ao_object[$ai_totrows][5]="<input name=txtdiaadibonvac".$ai_totrows." type=text id=txtdiaadibonvac".$ai_totrows." class=sin-borde size=6 value='".$li_diaadibonvac."' onKeyUp='javascript: ue_validarnumero(this);'> ";
				$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
				$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";
			}
			$ai_totrows=$ai_totrows+1;
			$ao_object[$ai_totrows][1]="<input name=txtlappervac".$ai_totrows." type=text id=txlappervac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][2]="<input name=txtdiadisvac".$ai_totrows." type=text id=txtdiadisvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][3]="<input name=txtdiaadidisvac".$ai_totrows." type=text id=txtdiaadidisvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][4]="<input name=txtdiabonvac".$ai_totrows." type=text id=txtdiabonvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][5]="<input name=txtdiaadibonvac".$ai_totrows." type=text id=txtdiaadibonvac".$ai_totrows." class=sin-borde size=6 maxlength=3 onKeyUp='javascript: ue_validarnumero(this);'>";
			$ao_object[$ai_totrows][6]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif alt=Aceptar width=15 height=15 border=0></a>";
			$ao_object[$ai_totrows][7]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif alt=Aceptar width=15 height=15 border=0></a>";
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_load_tablavacacion_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_tablavacacion_periodo($as_codtabvac,$ai_lappervac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_tablavacacion_periodo
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_lappervac  // período
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si el periodo de la tabla de vacacion está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT lappervac FROM sno_tablavacperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'".
				"   AND lappervac=".$ai_lappervac."";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_select_tablavacacion_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_tablavacacion_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_tablavacacion_periodo($as_codtabvac,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_tablavacacion_periodo
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de vacación período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_tablavacperiodo ".
				"(codemp,codtabvac,lappervac,diadisvac,diabonvac,diaadidisvac,diaadibonvac)VALUES".
				"('".$this->ls_codemp."','".$as_codtabvac."',".$ai_lappervac.",".$ai_diadisvac.",".
				"".$ai_diabonvac.",".$ai_diaadidisvac.",".$ai_diaadibonvac.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_insert_tablavacacion_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="INSERT";
			$ls_descripcion="Insertó el período ".$ai_lappervac." asociado a la tabla de Vacación ".$as_codtabvac;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_insert_tablavacacion_periodo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_tablavacacion_periodo($as_codtabvac,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_tablavacacion_periodo
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza en la tabla de vacación período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_tablavacperiodo ".
				"   SET diadisvac = ".$ai_diadisvac.", ".
				"  		diabonvac = ".$ai_diabonvac.", ".
				"		diaadidisvac = ".$ai_diaadidisvac.", ".
				"		diaadibonvac = ".$ai_diaadibonvac." ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac = '".$as_codtabvac."'".
				"   AND lappervac = ".$ai_lappervac."";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_update_tablavacacion_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizó el período ".$ai_lappervac." asociado a la tabla de Vacación ".$as_codtabvac;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}		
		return $lb_valido;
	}// end function uf_update_tablavacacion_periodo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_periodo($as_codtabvac,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,&$as_existe,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_periodo
		//		   Access: public (sigesp_snorh_d_tablavacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_lappervac  // lapso
		//				   ai_diadisvac  // dias de disfrute
		//				   ai_diabonvac  // dias de bono
		//				   ai_diaadidisvac  // días adicionales de disfrute
		//				   ai_diaadibonvac  // días adicionales de bono
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que almacena el perído relacionado con la tabla de vacación
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($this->uf_select_tablavacacion_periodo($as_codtabvac,$ai_lappervac)===false)
		{
			$as_existe="FALSE";
			$lb_valido=$this->uf_insert_tablavacacion_periodo($as_codtabvac,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad);
		}
		else
		{
			$as_existe="TRUE";
			$lb_valido=$this->uf_update_tablavacacion_periodo($as_codtabvac,$ai_lappervac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_update_tablavacacion_periodo	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_perido($as_codtabvac,$ai_lappervac,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_perido
		//		   Access: public (sigesp_snorh_d_tablavacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   ai_lappervac  // lapso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la tabla de vacación período un período en partícular
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM sno_tablavacperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'".
				"   AND lappervac='".$ai_lappervac."'";		
		
       	$this->io_sql->begin_transaction();
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_delete_perido ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el período ".$ai_lappervac." asociado a la tabla de Vacación ".$as_codtabvac;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El período fue Eliminado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_delete_perido ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_delete_perido
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_perido_lote($as_codtabvac,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_perido_lote
		//		   Access: private
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina la tabla de vacación período en lote
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_tablavacperiodo ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'";
		
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_delete_perido_lote ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los períodos asociados a la tabla de Vacación ".$as_codtabvac;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_delete_perido_lote
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tablavacacion($as_codtabvac,&$ai_anoant,&$ai_diadisvac,&$ai_diabonvac,&$ai_diaadidisvac,&$ai_diaadibonvac,$ai_anopre)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tablavacacion
		//		   Access: public (sigesp_sno_c_vacacion)
		//	    Arguments: as_codtabvac  // código de la tabla de vacacion
		//	    		   ai_anoant  // Año de antiguedad
		//	    		   ai_diadisvac  // Días de disfrute
		//	    		   ai_diabonvac  // Días de Bono vacacional
		//	    		   ai_diaadidisvac  // Días adicionales de disfrute
		//	    		   ai_diaadibonvac  // Días adicinales de bono
		//	    		   ai_anopre  // Años de servicio previos
		//	      Returns: lb_valido True si existe ó False si no existe
		//	  Description: Funcion que verifica si la tabla de vacacion está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_quinquenio=0;
		$ai_diadisvac=0;
		$ai_diabonvac=0;
		$ai_diaadidisvac=0;
		$ai_diaadibonvac=0;
		$ls_sql="SELECT pertabvac, anoserpre ".
				"  FROM sno_tablavacacion ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codtabvac='".$as_codtabvac."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_load_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				if($row["anoserpre"]==1)
				{
					$ai_anoant=$ai_anoant+$ai_anopre;
				}
				if($row["pertabvac"]==0)
				{
					$li_anoxper=5;// Quinquenal
				}
				else
				{
					$li_anoxper=1;// Anual
				}
				$li_quinquenio=(($ai_anoant-1)/$li_anoxper)+1;
			}
			$this->io_sql->free_result($rs_data);
			$ls_sql="SELECT diadisvac, diabonvac, diaadidisvac, diaadibonvac ".
					"  FROM sno_tablavacperiodo ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codtabvac='".$as_codtabvac."'".
					"   AND lappervac<=".$li_quinquenio."".
					" ORDER BY lappervac DESC ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Tabla Vacación MÉTODO->uf_load_tablavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_diadisvac=$row["diadisvac"];
					$ai_diabonvac=$row["diabonvac"];
					$ai_diaadidisvac=$row["diaadidisvac"];
					$ai_diaadibonvac=$row["diaadibonvac"];
				}
				$this->io_sql->free_result($rs_data);
			}
		}
		return $lb_valido;
	}// end function uf_select_tablavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>