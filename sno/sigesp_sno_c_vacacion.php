<?php
class sigesp_sno_c_vacacion
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $io_evaluador;
	var $io_fun_nomina;
	var $io_prestamo;
	var $io_permiso;
	var $io_tablavacacion;
	var $io_diaferiado;
	var $io_fecha;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_vacacion()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_vacacion
		//		   Access: public (sigesp_snorh_d_vacacion, sigesp_sno_p_vacacionvencida)
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
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
		require_once("sigesp_sno_c_evaluador.php");
		$this->io_evaluador=new sigesp_sno_c_evaluador();
		require_once("sigesp_sno_c_prestamo.php");
		$this->io_prestamo=new sigesp_sno_c_prestamo();
		require_once("sigesp_snorh_c_permiso.php");
		$this->io_permiso=new sigesp_snorh_c_permiso();
		require_once("sigesp_snorh_c_tablavacacion.php");
		$this->io_tablavacacion=new sigesp_snorh_c_tablavacacion();
		require_once("sigesp_snorh_c_diaferiado.php");
		$this->io_diaferiado=new sigesp_snorh_c_diaferiado();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
			$this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		}
		else
		{
			$this->ls_codnom="0000";
		}
		
	}// end function sigesp_sno_c_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_snorh_d_vacacion, sigesp_sno_p_vacacionvencida)
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
		unset($this->io_sno);
		unset($this->io_evaluador);
		unset($this->io_prestamo);
		unset($this->io_permiso);
		unset($this->io_tablavacacion);
		unset($this->io_diaferiado);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_totalpersonal($as_codperdes,$as_codperhas,&$ai_totper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_totalpersonal
		//		   Access: public (sigesp_sno_p_vacacionvencida)
		//	    Arguments: as_codperdes  // código de personal desde
		//				   as_codperhas  // código de personal hasta
		//				   ai_totper  // Total de personal seleccionado
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene la cantidad de personal seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$ls_sql="SELECT COUNT(sno_personalnomina.codper) as total".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
				"   AND sno_personalnomina.staper='1'".
				"   AND sno_personal.estper='1'".
				"   AND sno_personal.fecingper<='".$ld_fechasper."' ";
		if(!empty($as_codperdes))
		{
			$ls_sql=$ls_sql."   AND sno_personalnomina.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_sql=$ls_sql."   AND sno_personalnomina.codper<='".$as_codperhas."'";
		}		
		$ls_sql=$ls_sql."	AND sno_personalnomina.codemp=sno_personal.codemp".
						"   AND sno_personalnomina.codper=sno_personal.codper";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_totalpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totper=$row["total"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_totalpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_vacacion($as_codper,$ai_codvac,$ad_fecvenvac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,
								$ai_sueintvac,$ai_sueintbonvac,$ai_stavac,$ai_diaspermiso,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_vacacion
		//		   Access: private
		//	    Arguments: as_codper  // código del personal                    ai_codvac  // código de vacación
		//				   ad_fecvenvac  // Fecha de Vencimiento				ai_diadisvac  // días de vacaciones						
		//				   ai_diabonvac  // días de bono vacacional				ai_diaadidisvac  // Días adicionales de vacaciones
		//				   ai_diaadibonvac  // Días adicionales de bono			ai_sueintvac  // Sueldo integral de vacaciones
		//				   ai_sueintbonvac  // sueldo integral de bono vaca		ai_stavac  // Estatus de Vacaciones
		//                 ai_diapermiso // días de permiso descontables 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecdisvac="";
		$ld_fecreivac="";
		$li_sabdom=0;
		$li_diafer=0;
		$lb_valido=$this->uf_load_fechadisfrute($ad_fecvenvac,$ld_fecdisvac,$ai_sueintbonvac);
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_fechareingreso($ld_fecdisvac,$ai_diadisvac,$ai_diaadidisvac,$ld_fecreivac,$li_sabdom,$li_diafer,$ai_diaspermiso);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_periodo($ld_fecdisvac,$ls_persalvac);
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_load_periodo($ld_fecreivac,$ls_peringvac);
		}
		if($lb_valido)
		{
			$li_dianorvac=intval(strtotime($ld_fecreivac)-strtotime($ld_fecdisvac))/86400;
			$ls_obsvac="Vacación Generada Automáticamente por el proceso->Generar Vacaciones Vencidas.";
			if(intval(substr($ld_fecdisvac,8,2))<=15)
			{
				$li_quisalvac=1;
			}
			else
			{
				$li_quisalvac=2;
			}
			if(intval(substr($ld_fecreivac,8,2))<=15)
			{
				$li_quireivac=1;
			}
			else
			{
				$li_quireivac=2;
			}
			$ls_sql="INSERT INTO sno_vacacpersonal(codemp,codper,codvac,fecvenvac,fecdisvac,fecreivac,diavac,stavac,sueintbonvac,".
					"sueintvac,diabonvac,obsvac,diapenvac,persalvac,peringvac,dianorvac,quisalvac,quireivac,diaadivac,diaadibon,".
					"diafer,sabdom,diapag,pagcan,diapervac)VALUES('".$this->ls_codemp."','".$as_codper."',".$ai_codvac.",'".$ad_fecvenvac."',".
					"'".$ld_fecdisvac."','".$ld_fecreivac."',".$ai_diadisvac.",".$ai_stavac.",".$ai_sueintbonvac.",".$ai_sueintvac.",".
					"".$ai_diabonvac.",'".$ls_obsvac."',0,'".$ls_persalvac."','".$ls_peringvac."',".$li_dianorvac.",".$li_quisalvac.",".
					"".$li_quireivac.",".$ai_diaadidisvac.",".$ai_diaadibonvac.",".$li_diafer.",".$li_sabdom.",0,0,".$ai_diaspermiso.")";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_insert_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la vacación ".$ai_codvac." asociado al personal ".$as_codper;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
		}
		return $lb_valido;
	}// end function uf_insert_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fechadisfrute($ad_fecvenvac,&$ad_fecdisvac,&$ai_sueintbonvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fechadisfrute
		//		   Access: private
		//	    Arguments: ad_fecvenvac  // Fecha de Vencimiento						
		//				   ad_fecdisvac  // Fecha de disfrute de las vacaciones
		//				   ai_sueintbonvac  // sueldo integral de bono vaca	
		//	      Returns: lb_valido True si se ejecuto el proceso correctamnte ó False si hubo error en el proceso
		//	  Description: Funcion que dada la fecha de vencimiento de las vacaciones se obtiene la fecha de disfrute
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if(intval(substr($ad_fecvenvac,8))<=15)
		{
			$ad_fecdisvac=substr($ad_fecvenvac,0,8)."16";
		}
		else
		{
			if(intval(substr($ad_fecvenvac,5,2))<12)
			{
				$ad_fecdisvac=substr($ad_fecvenvac,0,4)."-".str_pad(intval(substr($ad_fecvenvac,5,2)+1),2,"0",0)."-01";
			}
			else
			{
				$ad_fecdisvac=intval(substr($ad_fecvenvac,0,4)+1)."-01-01";
			}
		}
		return $lb_valido;
	}// end function uf_load_fechadisfrute
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_fechareingreso($ad_fecdisvac,$ai_diadisvac,$ai_diaadidisvac,&$ad_fecreivac,&$ai_sabdom,&$ai_diafer,$ai_diaspermiso)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_fechareingreso
		//		   Access: private
		//	    Arguments: ad_fecdisvac  // Fecha de disfrute de las vacaciones						
		//				   ai_diadisvac  // Días de Disfrute de las vacaciones
		//				   ai_diaadidisvac  // Días adicionales de disfrute
		//				   ad_fecreivac  // Fecha de reintegro de las vacaciones
		//				   ai_sabdom  // sábados y domingos dentro del período de las vacaciones
		//				   ai_diafer  // días feriados dentro del período de las vacaciones
		//		           ai_diaspermiso // días de permiso descontables de vacaciones
		//	      Returns: lb_valido True si se ejecuto el proceso correctamnte ó False si hubo error en el proceso
		//	  Description: Funcion que dada la fecha de disfrute, obtiene la fecha de reintegro, sabados, domingos y feriados
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecreivac=$this->io_funciones->uf_convertirfecmostrar($ad_fecdisvac);
		$ai_sabdom=0;
		$ai_diafer=0;
		$li_diahab=1;
		$li_totdia=($ai_diadisvac+$ai_diaadidisvac-$ai_diaspermiso);
		while($li_diahab<=$li_totdia)
		{
			$ad_fecreivac=$this->io_sno->uf_suma_fechas($ad_fecreivac,1);
			if($this->io_sno->uf_nro_sabydom($ad_fecreivac,$ad_fecreivac)==1)
			{
				$ai_sabdom=$ai_sabdom+1;
				$li_diahab=$li_diahab-1;
			}
			else
			{
				$ad_fecreivac=$this->io_funciones->uf_convertirdatetobd($ad_fecreivac);
				if($this->io_diaferiado->uf_select_diaferiado("fecfer",$ad_fecreivac))
				{
					$ai_diafer=$ai_diafer+1;
					$li_diahab=$li_diahab-1;
				}
				$ad_fecreivac=$this->io_funciones->uf_convertirfecmostrar($ad_fecreivac);
			}
			$li_diahab=$li_diahab+1;
		}
		$ad_fecreivac=$this->io_funciones->uf_convertirdatetobd($ad_fecreivac);
		return $lb_valido;
	}// end function uf_load_fechareingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_periodo($ad_fecper,&$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_periodo
		//		   Access: private
		//	    Arguments: ad_fecper  // Fecha desde del perído
		//				   as_codperi  // Código del período
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Función que obtiene el perído dada una fecha
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_codperi="000";
		if(substr($ad_fecper,0,4)==date("Y"))
		{
			$ls_sql="SELECT codperi ".
					"  FROM sno_periodo  ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND '".$ad_fecper."'>=fecdesper".
					"   AND '".$ad_fecper."'<=fechasper";
		}
		else
		{
			$ls_sql="SELECT codperi ".
					"  FROM sno_hperiodo  ".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND codnom='".$this->ls_codnom."'".
					"   AND '".$ad_fecper."'>=fecdesper".
					"   AND '".$ad_fecper."'<=fechasper";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codperi=$row["codperi"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_vacacion($as_codper,$ai_codvac,$ad_fecvenvac,$ad_fecdisvac,$ad_fecreivac,$ai_stavac,$ai_diavac,$ai_diaadivac,
								$ai_diabonvac,$ai_diaadibon,$ai_diapenvac,$ai_diafer,$ai_sabdom,$ai_sueintvac,$ai_sueintbonvac,
								$as_obsvac,$ai_diapag,$ai_pagcan,$ai_dianorvac,$as_peringvac,$as_persalvac,$ai_quisalvac,$ai_quireivac,$as_pagpersal,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_vacacion
		//		   Access: public (sigesp_snorh_d_vacacion, sigesp_sno_p_programarvacaciones)
		//	    Arguments: as_codper  // código del personal                    ai_codvac  // código de vacación
		//				   ad_fecvenvac  // Fecha de Vencimiento				ad_fecdisvac  // Fecha de Disfrute
		//				   ad_fecreivac  // Fecha de Reintegro					ai_stavac  // Estatus de Vacaciones
		//				   ai_diavac  // días de vacaciones						ai_diaadivac  // Días adicionales de vacaciones
		//				   ai_diabonvac  // días de bono vacacional				ai_diaadibon  // Días adicionales de bono
		//				   ai_diapenvac  // Días pendientes de vacaciones		ai_diafer  // Días feriados
		//				   ai_sabdom  // Sábados y Domingos						ai_sueintvac  // Sueldo integral
		//				   ai_sueintbonvac  // sueldo integral de bono vaca		as_obsvac  // Observación de vacaciones
		//				   ai_diapag  // Disfrutó los días						ai_pagcan  // Bono vacacional cancelado
		//				   ai_dianorvac // Dias normales de vaca				as_peringvac // Período de Reingreso
		//				   as_persalvac // Período de salida					ai_quisalvac // Quincena de Salida
		//				   ai_quireivac // Quincena de Reintegro				as_pagpersal // Pagar Vac. en el periodo actual
		//                 aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza las vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecvenvac=$this->io_funciones->uf_convertirdatetobd($ad_fecvenvac);
		$ad_fecdisvac=$this->io_funciones->uf_convertirdatetobd($ad_fecdisvac);
		$ad_fecreivac=$this->io_funciones->uf_convertirdatetobd($ad_fecreivac);
		$ai_sueintvac=str_replace(".","",$ai_sueintvac);
		$ai_sueintvac=str_replace(",",".",$ai_sueintvac);				
		$ai_sueintbonvac=str_replace(".","",$ai_sueintbonvac);
		$ai_sueintbonvac=str_replace(",",".",$ai_sueintbonvac);				
		$ls_sql="UPDATE sno_vacacpersonal ".
				"   SET fecvenvac='".$ad_fecvenvac."', ".
				"       fecdisvac='".$ad_fecdisvac."', ".
				"       fecreivac='".$ad_fecreivac."', ".
				"       stavac=".$ai_stavac.", ".
				"       diavac=".$ai_diavac.", ".
				"       diaadivac=".$ai_diaadivac.", ".
				"       diabonvac=".$ai_diabonvac.", ".
				"       diaadibon=".$ai_diaadibon.", ".
				"       diapenvac=".$ai_diapenvac.", ".
				"       diafer=".$ai_diafer.", ".
				"       sabdom=".$ai_sabdom.", ".
				"       sueintvac=".$ai_sueintvac.", ".
				"       sueintbonvac=".$ai_sueintbonvac.", ".
				"       obsvac='".$as_obsvac."', ".
				"       diapag=".$ai_diapag.", ".
				"       pagcan=".$ai_pagcan.", ".
				"       dianorvac=".$ai_dianorvac.", ".
				"       peringvac='".$as_peringvac."', ".
				"       persalvac='".$as_persalvac."', ".
				"       quisalvac=".$ai_quisalvac.", ".
				"       quireivac=".$ai_quireivac.", ".				
				"       pagpersal='".$as_pagpersal."' ".				
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'".
				"   AND codvac=".$ai_codvac."";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_update_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la vacación ".$ai_codvac." asociado al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		

			if($lb_valido)
			{	
				$this->io_mensajes->message("La Vacación fue Actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_update_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_vencidas($as_codperdes,$as_codperhas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_vencidas
		//		   Access: public (sigesp_sno_p_vacacionvencida)
		//	    Arguments: as_codperdes  // código de personal desde
		//				   as_codperhas  // código de personal hasta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecutó correctamente el proceso ó False si hubo algún error
		//	  Description: Funcion que dado un personal le genera las vacaciones vencidas hasta la fecha actual
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$rs_data="";
		$ls_metodovacaciones=$this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C");
		if($ls_metodovacaciones<>"0")
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_load_codigopersonal($as_codperdes,$as_codperhas,$rs_data);
			if($lb_valido)
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$as_codper=$row["codper"];
					$lb_valido=$this->uf_load_personal($as_codper);
					if($lb_valido)
					{
						$lb_valido=$this->uf_delete_vacacionpersonal($as_codper,$aa_seguridad);
						if($lb_valido)
						{
							$ld_fecingper=$_SESSION["la_personalvacacion"]["fecingper"];
							$li_sueper=$_SESSION["la_personalvacacion"]["sueper"];
							$ld_fecvac="";
							$ld_fecvacproper="";
							$li_codvac=0;
							$li_diadisvac=0;
							$li_diabonvac=0;
							$li_diaadidisvac=0;
							$li_diaadibonvac=0;
							$li_sueintvac=0;
							$li_stavac=1;// Vacaciones Vencidas
							$ld_fecha=$_SESSION["la_nomina"]["fechasper"];;
							$lb_valido=$this->uf_load_vacacion($as_codper,$ld_fecingper,$ld_fecvac,$ld_fecvacproper,$li_codvac,
															   $li_diadisvac,$li_diabonvac,$li_diaadidisvac,$li_diaadibonvac,
															   $li_diaspermiso);
							if($lb_valido)
							{
								$lb_valido=$this->uf_load_sueldointegral_vac($as_codper,$li_sueintvac);
							}
							while((strtotime($ld_fecvac)<=strtotime($ld_fecha))&&($lb_valido))
							{
								$lb_valido=$this->uf_insert_vacacion($as_codper,$li_codvac,$ld_fecvac,$li_diadisvac,
																	 $li_diabonvac,$li_diaadidisvac,$li_diaadibonvac,
																	 $li_sueintvac,$li_sueper,$li_stavac,$li_diaspermiso,
																	 $aa_seguridad);
								if($lb_valido)
								{
									$ld_ultvac=$ld_fecvac;
									$lb_valido=$this->uf_load_vacacion($as_codper,$ld_ultvac,$ld_fecvac,$ld_fecvacproper,
																	   $li_codvac,
																	   $li_diadisvac,$li_diabonvac,$li_diaadidisvac,
																	   $li_diaadibonvac,$li_diaspermiso);
								}
							}
						}					
						unset($_SESSION["la_personalvacacion"]);
					}
				}
				$this->io_sql->free_result($rs_data);
			}
			if($lb_valido)
			{	
				$this->io_mensajes->message("Las vacaciones fueron generadas.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un error al generar las vacaciones."); 
				$this->io_sql->rollback();
			}			
		}
		else
		{
			$this->io_mensajes->message("ERROR->No hay método de vacación seleccionado."); 
		}
		return $lb_valido;
	}// end function uf_procesar_vencidas
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_porvencer($ad_codperdes,$ad_codperhas,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_porvencer
		//		   Access: public (sigesp_sno_c_cierreperiodo)
		//	    Arguments: ad_codperdes  // código del perído desde
		//				   ad_codperhas  // código del período hasta
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecutó correctamente el proceso ó False si hubo algún error
		//	  Description: Funcion que dado un rango de período le genera las vacaciones que se vencen en este rango.
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$rs_data="";
		$ls_metodovacaciones=$this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C");
		if($ls_metodovacaciones<>"0")
		{
			$lb_valido=$this->uf_load_personalporvencer($ad_codperdes,$ad_codperhas,$rs_data);
			if($lb_valido)
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$as_codper=$row["codper"];
					$lb_valido=$this->uf_load_personal($as_codper);
					if($lb_valido)
					{
						$ld_fecultvac=$_SESSION["la_personalvacacion"]["fecingper"];
						$li_codvac=0; 
						$lb_valido=$this->uf_load_ultimavacacion($as_codper,$ld_fecultvac,$li_codvac);
						if($lb_valido)
						{
							$li_sueper=$_SESSION["la_personalvacacion"]["sueper"];
							$ld_fecvac="";
							$ld_fecvacproper="";
							$li_diadisvac=0;
							$li_diabonvac=0;
							$li_diaadidisvac=0;
							$li_diaadibonvac=0;
							$li_sueintvac=0;
							$li_stavac=1;// Vacaciones Vencidas
							$ld_fecha=$ad_codperhas;
							$lb_valido=$this->uf_load_vacacion($as_codper,$ld_fecultvac,$ld_fecvac,$ld_fecvacproper,$li_codvac,
															   $li_diadisvac,$li_diabonvac,$li_diaadidisvac,$li_diaadibonvac,
															   $li_diaspermiso);
							if($lb_valido)
							{
								$lb_valido=$this->uf_load_sueldointegral_vac($as_codper,$li_sueintvac);							
							}
							while((strtotime($ld_fecvac)<=strtotime($ld_fecha))&&($lb_valido))
							{
								$lb_valido=$this->uf_insert_vacacion($as_codper,$li_codvac,$ld_fecvac,$li_diadisvac,
																	 $li_diabonvac,$li_diaadidisvac,$li_diaadibonvac,
																	 $li_sueintvac,$li_sueper,$li_stavac,$li_diaspermiso,
																	 $aa_seguridad);
								if($lb_valido)
								{
									$ld_ultvac=$ld_fecvac;
									$lb_valido=$this->uf_load_vacacion($as_codper,$ld_ultvac,$ld_fecvac,$ld_fecvacproper,
																       $li_codvac,
																	   $li_diadisvac,$li_diabonvac,$li_diaadidisvac,
																	   $li_diaadibonvac,$li_diaspermiso);
								}
							}
						}			
						unset($_SESSION["la_personalvacacion"]);
					}
				}
				$this->io_sql->free_result($rs_data);
			}
			if($lb_valido===false)
			{
				$this->io_mensajes->message("Ocurrio un error al generar las vacaciones."); 
			}			
		}
		return $lb_valido;
	}// end function uf_procesar_porvencer
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personalporvencer($ad_codperdes,$ad_codperhas,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personalporvencer
		//		   Access: private
		//	    Arguments: ad_codperdes  // código del periodo desde
		//				   ad_codperhas  // código del período hasta
		//				   $rs_data  // resultado de la consulta
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene la cantidad de personal seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_mes=substr($ad_codperdes,5,2);
		$ls_ano=substr($ad_codperdes,0,4);
		$ls_diades=substr($ad_codperdes,8,2);
		$ls_diahas=substr($ad_codperhas,8,2);
		$ls_sql="SELECT sno_personalnomina.codper ".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
				"   AND sno_personalnomina.staper='1'".
				"   AND sno_personal.estper='1'".
				"   AND SUBSTR(sno_personal.fecingper,1,4)<'".$ls_ano."'".
				"   AND SUBSTR(sno_personal.fecingper,6,2)='".$ls_mes."'".
				"   AND SUBSTR(sno_personal.fecingper,9,2)>='".$ls_diades."'".
				"   AND SUBSTR(sno_personal.fecingper,9,2)<='".$ls_diahas."' ".
				"	AND sno_personalnomina.codemp=sno_personal.codemp".
				"   AND sno_personalnomina.codper=sno_personal.codper";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_personalporvencer ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_load_personalporvencer
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ultimavacacion($as_codper,&$ad_fecultvac,&$ai_codvac)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ultimavacacion
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	    		   ad_fecultvac  // Fecha de la última vacacación 
		//	    		   ai_codvac  // Código de la última vacación
		//	      Returns: lb_valido True si se ejecutó el select correctamente ó False si hubo algún error
		//	  Description: función que dado el código de personal obtiene la última vacacion disfrutada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codvac, fecvenvac ".
				"  FROM sno_vacacpersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codper='".$as_codper."' ".
				" ORDER BY codvac DESC ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_ultimavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_fecultvac=$row["fecvenvac"]; 
				$ai_codvac=$row["codvac"]; 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_ultimavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_codigopersonal($as_codperdes,$as_codperhas,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_codigopersonal
		//		   Access: private
		//	    Arguments: as_codperdes  // código de personal desde
		//				   as_codperhas  // código de personal hasta
		//				   $rs_data  // resultado de la consulta
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene la cantidad de personal seleccionado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$ls_ano=substr($ld_fechasper,0,4);
		$ls_sql="SELECT sno_personalnomina.codper ".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE (sno_personalnomina.codemp='".$this->ls_codemp."')".
				"   AND (sno_personalnomina.codnom='".$this->ls_codnom."')".
				"   AND (sno_personalnomina.staper='1')".
				"   AND (sno_personal.estper='1')".
				"   AND (SUBSTR(sno_personal.fecingper,1,4)<'".$ls_ano."')".
				"   AND (sno_personal.fecingper<='".$ld_fechasper."')";				
		if(!empty($as_codperdes))
		{
			$ls_sql=$ls_sql."   AND (sno_personalnomina.codper>='".$as_codperdes."')";
		}
		if(!empty($as_codperhas))
		{
			$ls_sql=$ls_sql."   AND (sno_personalnomina.codper<='".$as_codperhas."')";
		}	
		$ls_sql=$ls_sql."   AND (sno_personalnomina.codemp=sno_personal.codemp)".
						"   AND (sno_personalnomina.codper=sno_personal.codper)";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_codigopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_load_codigopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_personal($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_personal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal crea una variable session con todos los datos del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.sueper, sno_personal.fecingper, sno_personalnomina.codtabvac, ".
				"		sno_personal.anoservpreper, sno_personalnomina.sueper	".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
				"   AND sno_personalnomina.staper='1'".
				"   AND sno_personal.estper='1'".
				"   AND sno_personal.fecingper<='".$ld_fechasper."'".
				"   AND sno_personalnomina.codper='".$as_codper."' ".
				"   AND sno_personalnomina.codemp=sno_personal.codemp".
				"   AND sno_personalnomina.codper=sno_personal.codper";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$la_personalvacacion=$row;   
				$_SESSION["la_personalvacacion"]=$la_personalvacacion;
				$_SESSION["la_personalvacacion"]["fecingper"]=$this->io_funciones->uf_formatovalidofecha($_SESSION["la_personalvacacion"]["fecingper"]);
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_vacacionpersonal($as_codper,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_vacacionpersonal
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	    		   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se eliminaron las vacaciones del personal ó False si hubo algún error
		//	  Description: función que dado el código de personal le elimina las vacaciones que se han generado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE  ".
				"  FROM sno_vacacpersonal ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codper='".$as_codper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_delete_vacacionpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las vacaciones asociadas al personal ".$as_codper;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_delete_vacacionpersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_vacacion($as_codper,$ad_fecvacant,&$ad_fecvacpro,&$ad_fecvacproper,&$ai_codvac,&$ai_diadisvac,&$ai_diabonvac,
							  &$ai_diaadidisvac,&$ai_diaadibonvac,&$ai_diaspermiso)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_vacacion
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	    		   ad_fecvacant  // fecha de la vacación anterior
		//	    		   ad_fecvacpro  // fecha de la próxima vacación
		//	    		   ad_fecvacproper  // fecha de la próxima vacación mas los días de permiso
		//	    		   ai_codvac  // Código de Vacación
		//	    		   ai_diadisvac  // Días de disfrute
		//	    		   ai_diabonvac  // Días de Bono vacacional
		//	    		   ai_diaadidisvac  // Días adicionales de disfrute
		//	    		   ai_diaadibonvac  // Días adicinales de bono
		//	      Returns: lb_valido True si se cargo perfectamente los valores de las próximas vacaciones ó False si hubo algún error
		//	  Description: función que dado el código de personal y la fecha de vacación anterior genera las próximas vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecvacpro=(intval(substr($ad_fecvacant,0,4))+1)."-".substr($ad_fecvacant,5);
		$ai_diaspermiso=0;
		$lb_valido=$this->io_permiso->uf_load_totaldiaspermiso($as_codper,$ad_fecvacant,$ad_fecvacpro,$ai_diaspermiso);
		if($lb_valido)
		{
			$ad_fecvacproper=$this->io_sno->uf_suma_fechas($this->io_funciones->uf_convertirfecmostrar($ad_fecvacpro),$ai_diaspermiso);
			$ad_fecvacproper=$this->io_funciones->uf_convertirdatetobd($ad_fecvacproper);
			$ai_codvac=$ai_codvac+1;
			$li_anoing=intval(substr($_SESSION["la_personalvacacion"]["fecingper"],0,4));
			$li_anovac=intval(substr($ad_fecvacpro,0,4));
			$li_anopre=intval($_SESSION["la_personalvacacion"]["anoservpreper"]);
			$li_anoant=abs($li_anovac-$li_anoing);
			$ls_codtabvac=$_SESSION["la_personalvacacion"]["codtabvac"];
			$lb_valido=$this->io_tablavacacion->uf_load_tablavacacion($ls_codtabvac,&$li_anoant,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$li_anopre);
			
		}
		return $lb_valido;
	}// end function uf_load_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sueldointegral_vac($as_codper,&$ai_sueintvac)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_sueldointegral_vac
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	    		   ai_sueintvac  // Sueldo Integral de Vacaciones
		//	      Returns: lb_valido True si se obtuvo el sueldo integral de vacaciones correctamente ó False si hubo algún error
		//	  Description: función que dado el código de personal obtiene el sueldo integral 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_sueintvac=0;
		$ls_mesant=str_pad((intval(substr($_SESSION["la_nomina"]["fecdesper"],5,2))-1),2,"0",0);
		if($ls_mesant=="00")
		{
			$ls_codvac=0;
			if(array_key_exists("la_vacacion",$_SESSION))
			{
				$ls_codvac=$_SESSION["la_vacacion"]["codvac"];
			}
			$ls_sql="SELECT sueintvac as total ".
					"  FROM sno_vacacpersonal ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codper='".$as_codper."' ".
					"   AND codvac=".$ls_codvac." ";
		}
		else
		{
			$ls_anoant=str_pad((intval(substr($_SESSION["la_nomina"]["fecdesper"],0,4))),4,"0",0);
			$ls_sql="SELECT COALESCE(sum(valsal),0.00) as total ".
					"  FROM sno_hsalida, sno_hperiodo ".
					" WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
					"   AND sno_hsalida.codnom='".$this->ls_codnom."' ".
					"   AND sno_hsalida.codper='".$as_codper."' ".
					"   AND (sno_hsalida.tipsal='A' OR sno_hsalida.tipsal='D' OR sno_hsalida.tipsal='P1') ".
					"   AND SUBSTR(sno_hperiodo.fechasper,6,2)='".$ls_mesant."' ".
					"   AND SUBSTR(sno_hperiodo.fechasper,1,4)='".$ls_anoant."' ".
					"   AND sno_hsalida.codconc IN (SELECT codconc ".
					"					 			   FROM sno_concepto ".
					"				      			  WHERE codemp='".$this->ls_codemp."' ".
					"					    			AND codnom='".$this->ls_codnom."' ".
					"					    			AND sueintvaccon=1)".
					"   AND sno_hsalida.codemp = sno_hperiodo.codemp ".
					"   AND sno_hsalida.codnom = sno_hperiodo.codnom ".
					"   AND sno_hsalida.codperi = sno_hperiodo.codperi ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_sueldointegral_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_sueintvac=$row["total"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_sueldointegral_vac
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_sueldobono_vac($as_codper,&$ai_suebonvac)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_sueldobono_vac
		//		   Access: private
		//	    Arguments: as_codper  // código de personal
		//	    		   ai_sueintbonvac  // Sueldo para los bonos
		//	      Returns: lb_valido True si se obtuvo el sueldo integral de vacaciones correctamente ó False si hubo algún error
		//	  Description: función que dado el código de personal obtiene el sueldo integral 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_suebonvac=0;
		$ls_sql="SELECT sueper ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_personalnomina.codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_sueldobono_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_suebonvac=$row["sueper"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_sueldobono_vac
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_sueldointegral_vac($as_codper,$as_codvac,$ai_sueintvac,$ai_suebonvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_sueldointegral_vac
		//		   Access: private
		//	    Arguments: as_codper  // código del personal                 
		//				   ai_sueintvac  // Sueldo integral de vacaciones						
		//				   ai_suebonvac  // Sueldo de vacaciones						
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_vacacpersonal ".
				"   SET sueintvac = ".$ai_sueintvac.", ".
				"   	sueintbonvac = ".$ai_suebonvac." ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codper = '".$as_codper."' ".
				"   AND codvac = ".$as_codvac."";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_update_sueldointegral_vac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_update_sueldointegral_vac
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_existe_resumen()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_existe_resumen
		//		   Access: private
		//	      Returns: lb_valido True si existe alguna salida y false si no existe Salida
		//	  Description: Funcion que verifica si hay registros en resumen
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/08/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT count(codper) as total".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$this->ls_peractnom."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=true;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_existe_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($row["total"]>0)
				{
					$lb_valido=true;
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_existe_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprogramar_vacacion($as_codper,&$ad_fecdisvac,$ai_diadisvac,$ai_diabonvac,$ai_diaadidisvac,$ai_diaadibonvac,$ad_fecvenvac,
									 &$ad_fecreivac,&$ai_sabdom,&$ai_diafer,&$as_persalvac,&$as_peringvac,&$ai_dianorvac,&$as_obsvac,
									 &$ai_quisalvac,&$ai_quireivac,&$ai_sueintvac,$ai_diaspermiso)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reprogramar_vacacion
		//		   Access: private
		//	    Arguments: as_codper  // código del personal                    ai_codvac  // código de vacación
		//				   ad_fecvenvac  // Fecha de Vencimiento				ai_diadisvac  // días de vacaciones						
		//				   ai_diabonvac  // días de bono vacacional				ai_diaadidisvac  // Días adicionales de vacaciones
		//				   ai_diaadibonvac  // Días adicionales de bono			ai_sueintvac  // Sueldo integral de vacaciones
		//				   ai_sueintbonvac  // sueldo integral de bono vaca		ai_stavac  // Estatus de Vacaciones
		//                 ai_diaspermiso // dias de permiso descontables de vacaciones
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_correcto=true;
		$li_dias=0;
		switch($_SESSION["la_nomina"]["tippernom"])
		{
			case "0": // Nóminas Semanales
				$li_dias=7;
				break;
			case "1": // Nóminas Quincenales
				$li_dias=15;
				break;
			case "2": // Nóminas Mensuales
				$li_dias=30;
				break;
			case "3": // Nóminas Anuales
				$li_dias=365;
				break;
		}
		$ld_hasta=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		$ld_hasta=$this->io_sno->uf_suma_fechas($ld_hasta,$li_dias);
		if($this->io_fecha->uf_comparar_fecha($ad_fecdisvac,$ld_hasta))
		{
			$lb_valido=$this->uf_existe_resumen();
		}
		if($lb_valido===false)
		{
			$ad_fecdisvac=$this->io_funciones->uf_convertirdatetobd($ad_fecdisvac);
			$ad_fecvenvac=$this->io_funciones->uf_convertirdatetobd($ad_fecvenvac);
			$ad_fecreivac="";
			$ai_sabdom=0;
			$ai_diafer=0;
			$lb_valido=true;
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_fechareingreso($ad_fecdisvac,$ai_diadisvac,$ai_diaadidisvac,$ad_fecreivac,$ai_sabdom,$ai_diafer, $ai_diaspermiso);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_periodo($ad_fecdisvac,$as_persalvac);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_periodo($ad_fecreivac,$as_peringvac);
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_load_sueldointegral_vac($as_codper,$ai_sueintvac);
				$ai_sueintvac=$this->io_fun_nomina->uf_formatonumerico($ai_sueintvac);
			}
			if($lb_valido)
			{
				$ai_dianorvac=intval(strtotime($ad_fecreivac)-strtotime($ad_fecdisvac))/86400;
				$as_obsvac="Vacación Reprogramada.";
				if(intval(substr($ad_fecdisvac,8,2))<=15)
				{
					$ai_quisalvac=1;
				}
				else
				{
					$ai_quisalvac=2;
				}
				if(intval(substr($ad_fecreivac,8,2))<=15)
				{
					$ai_quireivac=1;
				}
				else
				{
					$ai_quireivac=2;
				}
			}
			$ad_fecreivac=$this->io_funciones->uf_convertirfecmostrar($ad_fecreivac);
			$ad_fecdisvac=$this->io_funciones->uf_convertirfecmostrar($ad_fecdisvac);
		}
		else
		{
			$this->io_mensajes->message("Para este período No se pueden Programar Vacaciones. Ya realizó el cálculo de la Nómina. Reverse la Nómina y vuelva a programar"); 
			$ad_fecdisvac="";
			$lb_correcto=false;
		}
		return array($lb_valido,$lb_correcto);
	}// end function uf_reprogramar_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_salidavacacion($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_salidavacacion
		//		   Access: public (sigesp_sno_c_calcularprenomina, uf_calcular_vacacion)
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal verfica si este sale de vacaciones en este período y de ser así crea una
		//				   variable de session con todos sus atos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_metodovacaciones=trim($this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C"));
		switch ($ls_metodovacaciones)
		{
			case "1": //METODO #0
				$ld_desde=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
				$ld_desde=$this->io_sno->uf_suma_fechas($ld_desde,1);
				$ld_desde=$this->io_funciones->uf_convertirdatetobd($ld_desde);	
				switch($_SESSION["la_nomina"]["tippernom"])
				{
					case "0": // Nóminas Semanales
						$li_dias=7;
						break;
					case "1": // Nóminas Quincenales
						$li_dias=15;
						break;
					case "2": // Nóminas Mensuales
						$li_dias=30;
						break;
					case "3": // Nóminas Anuales
						$li_dias=365;
						break;
				}
				$ld_hasta=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
				$ld_hasta=$this->io_sno->uf_suma_fechas($ld_hasta,$li_dias);
				$ls_dia=substr($ld_hasta,0,2);
				$ls_mes=substr($ld_hasta,3,2);
				$ls_ano=substr($ld_hasta,6,4);
				while(checkdate($ls_mes,$ls_dia,$ls_ano)==false)
				{ 
				   $ls_dia=$ls_dia-1; 
				} 
				$ld_hasta=$ls_dia."/".$ls_mes."/".$ls_ano;
				$ld_hasta=$this->io_funciones->uf_convertirdatetobd($ld_hasta);
				$ld_desde_r=$_SESSION["la_nomina"]["fecdesper"];
				$ld_hasta_r=$_SESSION["la_nomina"]["fechasper"];
				$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.sueper, sno_personalnomina.horper, ".
						"  		sno_personalnomina.quivacper, sno_personalnomina.staper, sno_vacacpersonal.codvac, ".
						"  		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.numhijper ".
						"  FROM sno_personalnomina, sno_personal, sno_vacacpersonal ".
						" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
						"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
						"   AND sno_personalnomina.codper='".$as_codper."'".
						"   AND sno_personalnomina.staper='1'".
						"	AND sno_vacacpersonal.stavac='2'".
						"   AND sno_vacacpersonal.pagpersal='0' ".
						"   AND sno_vacacpersonal.pagcan=0 ".
						"	AND sno_vacacpersonal.fecdisvac BETWEEN '".$ld_desde."' AND '".$ld_hasta."'".
						"   AND sno_personalnomina.codemp=sno_personal.codemp".
						"   AND sno_personalnomina.codper=sno_personal.codper".
						"   AND sno_personal.codemp=sno_vacacpersonal.codemp".
						"   AND sno_personal.codper=sno_vacacpersonal.codper".
						" UNION ".
						"SELECT sno_personalnomina.codper, sno_personalnomina.sueper, sno_personalnomina.horper, ".
						"  		sno_personalnomina.quivacper, sno_personalnomina.staper, sno_vacacpersonal.codvac, ".
						"  		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.numhijper ".
						"  FROM sno_personalnomina, sno_personal, sno_vacacpersonal ".
						" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
						"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
						"   AND sno_personalnomina.codper='".$as_codper."'".
						"   AND sno_personalnomina.staper='1'".
						"	AND sno_vacacpersonal.stavac='2'".
						"   AND sno_vacacpersonal.pagpersal='1' ".
						"   AND sno_vacacpersonal.pagcan=0 ".
						"	AND sno_vacacpersonal.fecdisvac BETWEEN '".$ld_desde_r."' AND '".$ld_hasta_r."'".
						"   AND sno_personalnomina.codemp=sno_personal.codemp".
						"   AND sno_personalnomina.codper=sno_personal.codper".
						"   AND sno_personal.codemp=sno_vacacpersonal.codemp".
						"   AND sno_personal.codper=sno_vacacpersonal.codper";
				break;
		}
		if($ls_metodovacaciones!="0")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_salidavacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$la_personalvacacion=$row;   
					$_SESSION["la_vacacion"]=$la_personalvacacion;
					$_SESSION["la_vacacion"]["envacacion"]=1;
				}
				else
				{
					$_SESSION["la_vacacion"]["envacacion"]=0;
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		else
		{
			$_SESSION["la_vacacion"]["envacacion"]=0;
		}
		return $lb_valido;
	}// end function uf_load_salidavacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_vacaciondisfrutada($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_vacaciondisfrutada
		//		   Access: public (sigesp_sno_c_calcularprenomina, uf_calcular_vacacion)
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal verfica si este sale de vacaciones en este período y de ser así crea una
		//				   variable de session con todos sus atos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/06/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_metodovacaciones=trim($this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C"));
		switch ($ls_metodovacaciones)
		{
			case "1": //METODO #0
				$ld_desde=$_SESSION["la_nomina"]["fecdesper"];
				$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.sueper, sno_personalnomina.horper, ".
						"  		sno_personalnomina.quivacper, sno_personalnomina.staper, sno_vacacpersonal.codvac, ".
						"  		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.numhijper ".
						"  FROM sno_personalnomina, sno_personal, sno_vacacpersonal ".
						" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
						"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
						"   AND sno_personalnomina.codper='".$as_codper."'".
						"   AND (sno_personalnomina.staper='1' OR sno_personalnomina.staper='2')".
						"	AND sno_vacacpersonal.stavac<>'1' ".
						"   AND sno_vacacpersonal.pagcan=0 ".
						"	AND sno_vacacpersonal.fecdisvac < '".$ld_desde."' ".
						"   AND sno_personalnomina.codemp=sno_personal.codemp".
						"   AND sno_personalnomina.codper=sno_personal.codper".
						"   AND sno_personal.codemp=sno_vacacpersonal.codemp".
						"   AND sno_personal.codper=sno_vacacpersonal.codper";
				break;
		}
		if($ls_metodovacaciones!="0")
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_vacaciondisfrutada ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$la_personalvacacion=$row;   
					$_SESSION["la_vacacion"]=$la_personalvacacion;
					$_SESSION["la_vacacion"]["envacacion"]=1;
				}
				else
				{
					$_SESSION["la_vacacion"]["envacacion"]=0;
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		else
		{
			$_SESSION["la_vacacion"]["envacacion"]=0;
		}
		return $lb_valido;
	}// end function uf_load_vacaciondisfrutada
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_reingresovacacion($as_codper)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_reingresovacacion
		//		   Access: public (sigesp_sno_c_calcularprenomina, uf_calcular_vacacion)
		//	    Arguments: as_codper // código de personal
		//	      Returns: lb_valido True si se creo la variable sesion ó False si no se creo
		//	  Description: función que dado el código de personal verifica si este personal esta de reintegro de vacaciones y crea una
		//				   variable de sessión con sus datos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_metodovacaciones=trim($this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C"));
		switch ($ls_metodovacaciones)
		{
			case "1": //METODO #0
				$ld_desde=$_SESSION["la_nomina"]["fecdesper"];
				$ld_hasta=$this->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
				$ld_hasta=$this->io_sno->uf_suma_fechas($ld_hasta,1);
				$ld_hasta=$this->io_funciones->uf_convertirdatetobd($ld_hasta);
				break;
		}
		
		if($ls_metodovacaciones!="0")
		{
			$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.sueper,  sno_personalnomina.horper, ".
					"  		sno_personalnomina.quivacper, sno_personalnomina.staper, ".
					"  		sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, sno_personal.numhijper ".
					"  FROM sno_personalnomina, sno_personal ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
					"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
					"   AND sno_personalnomina.codper='".$as_codper."'".
					"   AND sno_personalnomina.staper='2'".
					"   AND sno_personalnomina.codper IN (SELECT codper ".
					"										 FROM sno_vacacpersonal ".
					"										WHERE stavac='3'".
					"										  AND fecreivac BETWEEN '".$ld_desde."' AND '".$ld_hasta."')".
					"   AND sno_personalnomina.codemp=sno_personal.codemp".
					"   AND sno_personalnomina.codper=sno_personal.codper";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_load_reingresovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$la_personalvacacion=$row;   
					$_SESSION["la_vacacion"]=$la_personalvacacion;
					$_SESSION["la_vacacion"]["envacacion"]=1;
				}
				else
				{
					$_SESSION["la_vacacion"]["envacacion"]=0;
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		else
		{
			$_SESSION["la_vacacion"]["envacacion"]=0;
		}
		return $lb_valido;
	}// end function uf_load_reingresovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptovacacion($as_tipo,$as_codper,&$as_sql)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptovacacion
		//		   Access: private
		//	    Arguments: as_tipo // Tipo si es de salida ó si es de reingreso
		//				   as_codper // Còdigo de personal
		//				   as_sql // Cadena SQL que va a servir para buscar al personal
		//	      Returns: lb_valido True si se creo la Cadena SQL ó False si no se creo
		//	  Description: función que dado el tipo de operacion (Salida ó Reingreso) crea una cadena SQL para ser ejecutada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_tipo)
		{
			case "S": // Si es de salida
				$as_sql="SELECT sno_conceptovacacion.forsalvac as formula, sno_conceptovacacion.minsalvac as minimo, ".
						"		sno_conceptovacacion.maxsalvac as maximo, sno_conceptovacacion.acumaxsalvac as acumuladomax, ".
						"  		sno_conceptovacacion.consalvac as condicion, sno_conceptovacacion.forpatsalvac as formulapat, ".
						"  		sno_conceptovacacion.minpatsalvac as minimopat, sno_conceptovacacion.maxpatsalvac as maximopat, ".
						"  		sno_concepto.nomcon, sno_concepto.titcon, sno_concepto.sigcon, sno_concepto.glocon, ".
						"  		sno_concepto.codconc, sno_conceptopersonal.aplcon, sno_concepto.conprenom, sno_concepto.quirepcon ".
						"  FROM sno_conceptovacacion, sno_conceptopersonal, sno_concepto ".
						" WHERE sno_conceptovacacion.codemp='".$this->ls_codemp."'".
						"   AND sno_conceptovacacion.codnom='".$this->ls_codnom."'".
						"   AND sno_conceptopersonal.codper='".$as_codper."'".
						"   AND sno_conceptovacacion.codemp=sno_concepto.codemp".
						"   AND sno_conceptovacacion.codnom=sno_concepto.codnom".
						"   AND sno_conceptovacacion.codconc=sno_concepto.codconc".
						"   AND sno_conceptovacacion.codemp=sno_conceptopersonal.codemp".
						"   AND sno_conceptovacacion.codnom=sno_conceptopersonal.codnom".
						"   AND sno_conceptovacacion.codconc=sno_conceptopersonal.codconc";
				break;
				
			case "R": // Si es de Reingreso
				$as_sql="SELECT sno_conceptovacacion.forreivac as formula, sno_conceptovacacion.minreivac as minimo, ".
						"		sno_conceptovacacion.maxreivac as maximo, sno_conceptovacacion.acumaxreivac as acumuladomax, ".
						"  		sno_conceptovacacion.conreivac as condicion, sno_conceptovacacion.forpatreivac as formulapat, ".
						"  		sno_conceptovacacion.minpatreivac as minimopat, sno_conceptovacacion.maxpatreivac as maximopat, ".
						"  		sno_concepto.nomcon, sno_concepto.titcon, sno_concepto.sigcon, sno_concepto.glocon, ".
						"  		sno_concepto.codconc, sno_conceptopersonal.aplcon, sno_concepto.conprenom, sno_concepto.quirepcon ".
						"  FROM sno_conceptovacacion, sno_conceptopersonal, sno_concepto ".
						" WHERE sno_conceptovacacion.codemp='".$this->ls_codemp."'".
						"   AND sno_conceptovacacion.codnom='".$this->ls_codnom."'".
						"   AND sno_conceptopersonal.codper='".$as_codper."'".
						"   AND sno_conceptovacacion.codemp=sno_concepto.codemp".
						"   AND sno_conceptovacacion.codnom=sno_concepto.codnom".
						"   AND sno_conceptovacacion.codconc=sno_concepto.codconc".
						"   AND sno_conceptovacacion.codemp=sno_conceptopersonal.codemp".
						"   AND sno_conceptovacacion.codnom=sno_conceptopersonal.codnom".
						"   AND sno_conceptovacacion.codconc=sno_conceptopersonal.codconc";
				break;
			
			default:
				$as_sql="";
				$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_load_conceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_calcular_vacacion($as_codper,&$ai_total_nomi)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_vacacion
		//		   Access: public (sigesp_sno_c_calularnomina)
		//	    Arguments: as_codper // código de personal
		//			       ai_total_nomi // Total acumulado de la nómina
		//	      Returns: lb_valido True si se calculo correctamente las vacaciones al personal False si no se calcularon bien
		//	  Description: función que dado el código de personal se calcula la salida ó reingreso de vacaciones de ser así
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		// Obtener si el personal está de Salida de Vacaciones
		$lb_valido=$this->uf_load_salidavacacion($as_codper);
		if($lb_valido)
		{
			if($_SESSION["la_vacacion"]["envacacion"]==1)
			{
				$li_sueintvac=0;
				$li_suebonvac=0;
				$lb_valido=$this->uf_load_sueldointegral_vac($as_codper,$li_sueintvac);
				if($lb_valido)
				{
					$lb_valido=$this->uf_load_sueldobono_vac($as_codper,$li_suebonvac);
				}
				if($lb_valido)
				{
					$ls_codvac=$_SESSION["la_vacacion"]["codvac"];
					$lb_valido=$this->uf_update_sueldointegral_vac($as_codper,$ls_codvac,$li_sueintvac,$li_suebonvac);
				}
				if($lb_valido)
				{
					// Calculamos la Salida
					$lb_valido=$this->uf_procesar_vacacion($as_codper,"S",$ai_total_nomi);
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_update_vacacioncancelada($as_codper,$ls_codvac);
				}
			}				
		}
		if($lb_valido)
		{
			// Obtener si el personal está de Reingreso de Vacaciones
			$lb_valido=$this->uf_load_reingresovacacion($as_codper);
			if($lb_valido)
			{
				if($_SESSION["la_vacacion"]["envacacion"]==1)
				{
					// Calculamos el Reingreso
					$lb_valido=$this->uf_procesar_vacacion($as_codper,"R",$ai_total_nomi);
				}
			}
		}
		if($lb_valido)
		{
			$li_desincorporar=$this->io_sno->uf_select_config("SNO","NOMINA","DESINCORPORAR DE NOMINA","0","C");
			if ($li_desincorporar == 0) // se aplica solo cuando no se desincorpora de la nómina
			{
				// Obtener si al personal no se le han cancelado la vacaciones 
				$lb_valido=$this->uf_load_vacaciondisfrutada($as_codper);
				if($_SESSION["la_vacacion"]["envacacion"]==1)
				{
					$li_sueintvac=0;
					$li_suebonvac=0;
					$lb_valido=$this->uf_load_sueldointegral_vac($as_codper,$li_sueintvac);
					if($lb_valido)
					{
						$lb_valido=$this->uf_load_sueldobono_vac($as_codper,$li_suebonvac);
					}
					if($lb_valido)
					{
						$ls_codvac=$_SESSION["la_vacacion"]["codvac"];
						$lb_valido=$this->uf_update_sueldointegral_vac($as_codper,$ls_codvac,$li_sueintvac,$li_suebonvac);
					}
					if($lb_valido)
					{
						// Calculamos la Salida
						$lb_valido=$this->uf_procesar_vacacion($as_codper,"S",$ai_total_nomi);
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_update_vacacioncancelada($as_codper,$ls_codvac);
					}
				}		
			}		
		}
		unset($_SESSION["la_vacacion"]);
		return $lb_valido;
	}// end function uf_calcular_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_vacacioncancelada($as_codper,$as_codvac)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_vacacioncancelada
		//		   Access: private
		//	    Arguments: as_codper  // código del personal                 
		//				   ai_sueintvac  // Sueldo integral de vacaciones						
		//				   ai_suebonvac  // Sueldo de vacaciones						
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las vacaciones
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/06/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_vacacpersonal ".
				"   SET calpagvac = 1 ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND codper = '".$as_codper."' ".
				"   AND codvac = ".$as_codvac."";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_update_vacacioncancelada ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		return $lb_valido;
	}// end function uf_update_vacacioncancelada
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_vacacion($as_codper,$as_tipo,&$ai_total_nomi)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_vacacion
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_tipo // tipo de calculo si es de salida ó de reingreso
		//				   ai_total_nomi // total 
		//	      Returns: lb_valido True si se calculo correctamente la salida de vacaciones al personal False si no se calcularon bien
		//	  Description: función que dado el código de personal se calculan la salida de las vacaciones 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_total_asig=0;
		$li_total_dedu=0;
		$li_total_apor_emp=0;
		$li_total_apor_pat=0;
		$li_quincena_1=0;
		$li_quincena_2=0;
		$ls_sql="";
		$lb_valido=$this->uf_load_conceptovacacion($as_tipo,$as_codper,$ls_sql);
		if($lb_valido)
		{
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_procesar_vacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$ls_codconc=$row["codconc"];
					$ai_acumulado=0;
					$ai_acumuladopat=0;
					$ls_formula=$row["formula"];
					$ls_formulapat=$row["formulapat"];
					$ls_condicion=$row["condicion"];
					$li_glocon=$row["glocon"];
					$li_aplcon=$row["aplcon"];
					$ls_sigcon=$row["sigcon"];
					$li_minimo=$row["minimo"];
					$li_maximo=$row["maximo"];
					$li_minimopat=$row["minimopat"];
					$li_maximopat=$row["maximopat"];
					$ls_quirepcon=$row["quirepcon"];
					$li_valor=0;
					$lb_filtro=true;
					$lb_aplica=true;
					if (!(trim($ls_condicion)=="")) // Si tiene una condición
					{
						$lb_filtro=false;
						$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$ls_condicion,$lb_filtro);
					}
					if($li_glocon==0) // Si el concepto NO es global
					{
						if($li_aplcon==0) // Si el concepto NO se aplica al personal
						{
							$lb_aplica=false;
						}
					}
					if(($lb_valido)&&($lb_filtro)&&($lb_aplica))
					{
						$lb_valido=$this->uf_evaluar_concepto($as_codper,$ls_formula,"C",$li_minimo,$li_maximo,$li_minimopat,$li_maximopat,$li_valor);					
						if(($ls_sigcon=="A")||($ls_sigcon=="B")) // Si son Asignaciones 
						{
							if($as_tipo=="S")
							{
								$as_tipovac="V1";
							}
							else
							{
								$as_tipovac="W1";
							}								
							$lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"A",$li_valor,$ai_acumulado,$as_tipovac,$ls_quirepcon);
							if($lb_valido)
							{
								$ai_total_nomi=$ai_total_nomi+$li_valor;
								$li_total_asig=$li_total_asig+$li_valor;
							}
						}
						if(($ls_sigcon=="D")||($ls_sigcon=="E")) // Si son Deducciones 
						{
							if($as_tipo=="S")
							{
								$as_tipovac="V2";
							}
							else
							{
								$as_tipovac="W2";
							}								
							$li_valor=($li_valor*-1);
							$lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"D",$li_valor,$ai_acumulado,$as_tipovac,$ls_quirepcon);
							if($lb_valido)
							{
								$ai_total_nomi=$ai_total_nomi+$li_valor;
								$li_total_dedu=$li_total_dedu-$li_valor;
							}
						}
						if($ls_sigcon=="P") // Si es un Aporte Patronal
						{
							if($as_tipo=="S")
							{
								$as_tipovac="V3";
							}
							else
							{
								$as_tipovac="W3";
							}								
							$li_valor=($li_valor*-1);
							$lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"P1",$li_valor,$ai_acumulado,$as_tipovac,$ls_quirepcon);
							if($lb_valido)
							{
								if($as_tipo=="S")
								{
									$as_tipovac="V4";
								}
								else
								{
									$as_tipovac="W4";
								}								
								$ai_total_nomi=$ai_total_nomi+$li_valor;
								$li_total_apor_emp=$li_total_apor_emp-$li_valor;
								$li_valoraporte=0;
								$lb_valido=$this->uf_evaluar_concepto($as_codper,$ls_formulapat,"P",$li_minimo,$li_maximo,$li_minimopat,$li_maximopat,$li_valoraporte);
								if($lb_valido)
								{
									$li_valoraporte=($li_valoraporte*-1);
									$lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"P2",$li_valoraporte,$ai_acumulado,$as_tipovac,$ls_quirepcon);
								}
								if($lb_valido)
								{
									$li_total_apor_pat=$li_total_apor_pat-$li_valoraporte;
								}
							}
						}
						if($ls_sigcon=="R") // Si es un Reporte
						{
							if($as_tipo=="S")
							{
								$as_tipovac="V5";
							}
							else
							{
								$as_tipovac="W5";
							}								
							$lb_valido=$this->uf_guardar_salida($as_codper,$ls_codconc,"R",$li_valor,$ai_acumulado,$as_tipovac,$ls_quirepcon);
							$li_valor=0;
						}
					}	
					if(($lb_valido)&&($_SESSION["la_nomina"]["divcon"]==1))
					{
						switch($ls_quirepcon)
						{
							case "1": // Primera Quincena
								$li_quincena_1=$li_quincena_1+$li_valor;
								break;
							case "2": // Segunda Quincena
								$li_quincena_2=$li_quincena_2+$li_valor;
								break;
							case "3": // Ambas Quincena
								$li_quincena_1=$li_quincena_1+round($li_valor/2,2);
								$li_quincena_2=$li_quincena_2+round($li_valor/2,2);
								break;
							case "-": // Ambas Quincena
								$li_quincena_1=$li_quincena_1+round($li_valor/2,2);
								$li_quincena_2=$li_quincena_2+round($li_valor/2,2);
								break;
						}
					}				
				}
				$this->io_sql->free_result($rs_data);	
				if($lb_valido)
				{
					$li_totalneto=$li_total_asig-($li_total_dedu+$li_total_apor_emp);
					$li_divcon=$_SESSION["la_nomina"]["divcon"];
					if($_SESSION["la_nomina"]["adenom"]==1)
					{
						$li_quincena_1=round($li_totalneto/2);
						$li_quincena_2=$li_totalneto-$li_quincena_1;
					}
					else
					{
						if($li_divcon==0)
						{
							$li_quincena_1=$li_totalneto;
							$li_quincena_2=0;
						}
						else
						{
							if(($li_quincena_1+$li_quincena_2)!=$ld_totalneto)
							{
								$ld_ajuste= $li_totalneto - ($li_quincena_1+$li_quincena_2);
								$li_quincena_2 = $li_quincena_2 + $ld_ajuste;
							}
						}
					}
					if($lb_valido)
					{
						$lb_valido=$this->uf_guardar_resumen($as_codper,$li_total_asig,$li_total_dedu,$li_total_apor_emp,$li_total_apor_pat,
															 $li_quincena_1,$li_quincena_2,$li_totalneto);
					}
				}
			}
		}
		return $lb_valido;
	}// end function uf_procesar_vacacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_evaluar_concepto($as_codper,$as_formula,$as_tipo,$ai_minimo,$ai_maximo,$ai_minimopat,$ai_maximopat,&$as_valor)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_evaluar_concepto
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_formula // Fórmula del concepto
		//				   as_tipo // si es un concepto normal ó es un aporte patronal
		//				   ai_minimo // valor mínimo que puede tener el concepto
		//				   ai_maximo // valor maximo que puede tener el concepto
		//				   ai_minimopat // valor mínimo que puede tener el concepto si es un aporte patronal
		//				   ai_maximopat // valor maximo que puede tener el concepto si es un aporte patronal
		//				   as_valor // Valor de la fórmula
		//	      Returns: lb_valido True si se calculo correctamente la formula False si no se calculo bien
		//	  Description: función que dado el código de personal y la fórmula se evalua y retorna un valor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_tipo)
		{
			case "C": // si es un concepto normal
				$li_minimo=$ai_minimo;
				$li_maximo=$ai_maximo;
				break;

			case "P": // si es un aporte patronal
				$li_minimo=$ai_minimopat;
				$li_maximo=$ai_maximopat;
				break;
		}
		$lb_valido=$this->io_evaluador->uf_evaluar($as_codper,$as_formula,$as_valor);
		if($lb_valido)
		{
			if($li_minimo>0)
			{
				if($as_valor<$li_minimo)
				{
					$as_valor=$li_minimo;
				}
			}
			if($li_maximo>0)
			{
				if($as_valor>$li_maximo)
				{
					$as_valor=$li_maximo;
				}
			}
		}
		return $lb_valido;
	}// end function uf_evaluar_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_prestamo($as_periodo,$as_codper,$as_tipo,&$ai_acumulado,&$ai_total_nomi,&$ai_total_dedu)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_prestamo
		//		   Access: private
		//	    Arguments: as_periodo // perìodo para el cual se desea calcular las vacaciones
		//				   as_codper // código de personal
		//				   as_tipo // tipo de calculo si es de salida ó de reingreso
		//				   ai_acumulado // acumulado total
		//				   ai_total_nomi // monto acumulado de la nómina
		//				   ai_total_dedu // monto acumulado de deducciones
		//	      Returns: lb_valido True si se proceso correctamente los prestamos ó False si hubo alguna falla
		//	  Description: función que dado el código de personal verifica si tiene prestamos asociados y los acumula
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_sql=" SELECT sno_prestamos.codpre, sno_prestamos.monpre, sno_prestamos.amoprepre, ".
                "        sno_prestamos.numcuopre, sno_prestamos.perinipre, sno_prestamos.forpagpre, ".
                "        sno_prestamos.monamopre,sno_prestamos.stapre, sno_prestamos.codconc, ".
                "        sno_prestamosperiodo.numcuo, sno_prestamosperiodo.estcuo, sno_concepto.quirepcon ".
                "   FROM sno_prestamos, sno_prestamosperiodo, sno_concepto ".
                "  WHERE sno_prestamos.codemp='".$this->ls_codemp."' ".
                "    AND sno_prestamos.codnom='".$this->ls_codnom."' ".
				"	 AND sno_prestamos.codper='".$as_codper."' ".
				"    AND sno_prestamosperiodo.girpre='".$as_periodo."' ".
				"    AND sno_prestamos.stapre='1' ".
				"	 AND sno_prestamos.codemp=sno_prestamosperiodo.codemp".
                "    AND sno_prestamos.codnom=sno_prestamosperiodo.codnom".
                "    AND sno_prestamos.codper=sno_prestamosperiodo.codper".
				"	 AND sno_prestamos.numpre=sno_prestamosperiodo.numpre".
				"	 AND sno_prestamos.codemp=sno_concepto.codemp".
                "    AND sno_prestamos.codnom=sno_concepto.codnom".
                "    AND sno_prestamos.codconc=sno_concepto.codconc";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_procesar_prestamo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				if($as_tipo=="S")
				{
					$ls_tipsal="D";
					$ls_tipvac="V2";
				}
				else
				{
					$ls_tipsal="D";
					$ls_tipvac="W2";
				}
				$li_codpre=$row["codpre"];
				$ls_codconc=$row["codconc"];
				$li_cuopre=$row["cuopre"];
				$ai_acumulado=($row["monamopre"]+$li_cuopre);
				$li_saldo=($row["monpre"]-$row["amoprepre"])-$ai_acumulado;
				$ls_quirepcon=$la_conceptopersonal["quirepcon"];
				$lb_valido=$this->io_prestamo->uf_update_salida_prestamo_vac($as_codper,$li_codpre,$ls_codconc,$ls_tipsal,$ls_tipvac,$li_cuopre,$ai_acumulado,
																			 $li_saldo,$ls_quirepcon);
				$ai_total_nomi=$ai_total_nomi-$li_cuopre;
				$ai_total_dedu=$ai_total_dedu+$li_cuopre;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_procesar_prestamo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_salida($as_peractnom,$as_codper,$as_codconc,$as_tipsal)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//		   Access: private
		//	    Arguments: as_peractnom  // Período Actual de la Nómina
		//				   as_codper  // Código de personal
		//			       as_codconc  // Código de Tabla
		//	   			   as_tipsal  // Código de Tabla
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la salida está registrada
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codconc ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$as_peractnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codconc='".$as_codconc."'".
				"   AND tipsal='".$as_tipsal."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_select_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_salida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_salida($as_peractnom,$as_codper,$as_codconc,$as_tipsal,$ai_valsal,$ai_monacusal,$as_quirepcon)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_salida
		//		   Access: private
		//	    Arguments: as_peractnom // período actual de la nómina
		//				   as_codper // código de personal
		//				   as_codconc // Còdigo de concepto
		//				   as_tipsal // Tipo de Salida
		//				   ai_valsal // Valor de la Salida
		//				   ai_monacusal // Monto Acumulado de la Salida
		//	      Returns: lb_valido True si se actualizó correctamente la salida False si hubo error
		//	  Description: función que actualiza en la tabla de salida el concepto que se evaluo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$li_priquisal=0;
		$li_segquisal=0;
		switch($as_quirepcon)
		{
			case '1':
				$li_priquisal=$ai_valsal;
				break;
			case '2':
				$li_segquisal=$ai_valsal;
				break;
			case '3':
				$li_priquisal=round($ai_valsal/2,2);
				$li_segquisal=round($ai_valsal/2,2);
				if(($li_priquisal+$li_segquisal)!=$ai_valsal)
				{
					$ld_ajuste= $ai_valsal - ($li_priquisal+$li_segquisal);
					$li_segquisal = $li_segquisal + $ld_ajuste;
				}
				break;
		}
		$ls_sql="UPDATE sno_salida ".
				"   SET valsal=(valsal+".$ai_valsal."), ".
				"       monacusal=(monacusal+".$ai_monacusal."), ".
				"		priquisal=(priquisal+".$li_priquisal."), ".
				"		segquisal=(segquisal+".$li_segquisal.") ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$as_peractnom."'".
				"   AND codper='".$as_codper."'".
				"   AND codconc='".$as_codconc."'".
				"   AND tipsal='".$as_tipsal."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_update_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
 			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_salida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_salida($as_peractnom,$as_codper,$as_codconc,$as_tipsal,$ai_valsal,$ai_monacusal,$as_quirepcon)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_salida
		//		   Access: private
		//	    Arguments: as_peractnom // período actual de la nómina
		//				   as_codper // código de personal
		//				   as_codconc // Còdigo de concepto
		//				   as_tipsal // Tipo de Salida
		//				   ai_valsal // Valor de la Salida
		//				   ai_monacusal // Monto Acumulado de la Salida
		//	      Returns: lb_valido True si se insertó correctamente la salida False si hubo error
		//	  Description: función que inserta en la tabla de salida el concepto que se evaluo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_priquisal=0;
		$li_segquisal=0;
		switch($as_quirepcon)
		{
			case '1':
				$li_priquisal=$ai_valsal;
				break;
			case '2':
				$li_segquisal=$ai_valsal;
				break;
			case '3':
				$li_priquisal=round($ai_valsal/2,2);
				$li_segquisal=round($ai_valsal/2,2);
				if(($li_priquisal+$li_segquisal)!=$ai_valsal)
				{
					$ld_ajuste= $ai_valsal - ($li_priquisal+$li_segquisal);
					$li_segquisal = $li_segquisal + $ld_ajuste;
				}
				break;
		}
		$ls_sql="INSERT INTO sno_salida(codemp,codnom,codperi,codper,codconc,tipsal,valsal,monacusal,salsal, priquisal, segquisal)VALUES ".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_peractnom."','".$as_codper."','".$as_codconc."',".
				"'".$as_tipsal."',".$ai_valsal.",".$ai_monacusal.",0,".$li_priquisal.",".$li_segquisal.")";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_insert_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
 			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_insert_salida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_salida($as_codper,$as_codconc,$as_tipsal,$ai_valsal,$ai_monacusal,$as_tipvac,$as_quirepcon)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_salida
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código de concepto
		//				   as_tipsal // tipo de Salida
		//				   ai_valsal // Valor de la salida
		//				   ai_monacusal // Valor acumulado de la salida
		//				   as_tipvac // tipo de salida de vacaciones
		//	      Returns: lb_valido True si se guardo correctamente la salida False si hubo error
		//	  Description: función que guarda en la tabla de salida el concepto que se evaluo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		if ($this->uf_select_salida($ls_peractnom,$as_codper,$as_codconc,$as_tipsal)) // si existe la salida
		{
			$lb_valido=$this->uf_update_salida($ls_peractnom,$as_codper,$as_codconc,$as_tipsal,0,$ai_monacusal,$as_quirepcon);
		}
		else
		{
			$lb_valido=$this->uf_insert_salida($ls_peractnom,$as_codper,$as_codconc,$as_tipsal,0,$ai_monacusal,$as_quirepcon);
		}
		if($lb_valido)
		{
			// se inserta la salida de tipo vacación
			$lb_valido=$this->uf_insert_salida($ls_peractnom,$as_codper,$as_codconc,$as_tipvac,$ai_valsal,$ai_monacusal,$as_quirepcon);
		}
		return $lb_valido;
	}// end function uf_guardar_salida
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_resumen($as_peractnom,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_resumen
		//		   Access: private
		//	    Arguments: as_peractnom  // Período Actual de la Nómina
		//				   as_codper  // Código de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si este personal ya tiene un resumen asociado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codper ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$as_peractnom."'".
				"   AND codper='".$as_codper."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_select_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_resumen($as_peractnom,$as_codper,$ai_asires,$ai_dedres,$ai_apoempres,$ai_apopatres,$ai_priquires,$ai_segquires,$ai_monnetres)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_resumen
		//		   Access: private
		//	    Arguments: as_peractnom // periodo actual de la nómina
		//				   as_codper // código de personal
		//				   ai_asires // monto de asignación
		//				   ai_dedres // monto de deducción
		//				   ai_apoempres // monto de aporte de empleados
		//				   ai_apopatres // monto de aporte de  patrón
		//				   ai_priquires // monto de primera quincena
		//				   ai_segquires // monto de segunda quincena
		//				   ai_monnetres // monto neto
		//	      Returns: lb_valido True si se actualizó correctamente el resumen False si hubo error
		//	  Description: función que actualiza en la tabla de resumen el concepto que se evaluo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_resumen ".
				"   SET asires=(asires+".$ai_asires."), ".
				"       dedres=(dedres+".$ai_dedres."), ".
				"       apoempres=(apoempres+".$ai_apoempres."), ".
				"       apopatres=(apopatres+".$ai_apopatres."), ".
				"       priquires=(priquires+".$ai_priquires."), ".
				"       segquires=(segquires+".$ai_segquires."), ".
				"       monnetres=(monnetres+".$ai_monnetres.") ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codperi='".$as_peractnom."'".
				"   AND codper='".$as_codper."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_update_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
 			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_update_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_resumen($as_peractnom,$as_codper,$ai_asires,$ai_dedres,$ai_apoempres,$ai_apopatres,$ai_priquires,$ai_segquires,$ai_monnetres)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_resumen
		//		   Access: private
		//	    Arguments: as_peractnom // periodo actual de la nómina
		//				   as_codper // código de personal
		//				   ai_asires // monto de asignación
		//				   ai_dedres // monto de deducción
		//				   ai_apoempres // monto de aporte de empleados
		//				   ai_apopatres // monto de aporte de  patrón
		//				   ai_priquires // monto de primera quincena
		//				   ai_segquires // monto de segunda quincena
		//				   ai_monnetres // monto neto
		//	      Returns: lb_valido True si se insertó correctamente el resumen False si hubo error
		//	  Description: función que inserta en la tabla de resumen el concepto que se evaluo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_resumen(codemp,codnom,codperi,codper,asires,dedres,apoempres,apopatres,priquires,segquires,monnetres,".
				"notres)VALUE('".$this->ls_codemp."','".$this->ls_codnom."','".$as_peractnom."','".$as_codper."',".$ai_asires.",".
				"".$ai_dedres.",".$ai_apoempres.",".$ai_apopatres.",".$ai_priquires.",".$ai_segquires.",".$ai_monnetres.",'')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_mensajes->message("CLASE->Vacación MÉTODO->uf_insert_resumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
 			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_insert_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_resumen($as_codper,$ai_asires,$ai_dedres,$ai_apoempres,$ai_apopatres,$ai_priquires,$ai_segquires,$ai_monnetres)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_resumen
		//		   Access: private
		//	    Arguments: as_codper // código de personal
		//				   ai_asires // monto de asignación
		//				   ai_dedres // monto de deducción
		//				   ai_apoempres // monto de aporte de empleados
		//				   ai_apopatres // monto de aporte de  patrón
		//				   ai_priquires // monto de primera quincena
		//				   ai_segquires // monto de segunda quincena
		//				   ai_monnetres // monto neto
		//	      Returns: lb_valido True si se guardo correctamente el resumen False si hubo error
		//	  Description: función que guarda en la tabla de resumen el concepto que se evaluo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		if ($this->uf_select_resumen($ls_peractnom,$as_codper)) // si existe el resumen
		{
			$lb_valido=$this->uf_update_resumen($ls_peractnom,$as_codper,$ai_asires,$ai_dedres,$ai_apoempres,$ai_apopatres,$ai_priquires,$ai_segquires,$ai_monnetres);
		}
		else
		{
			$lb_valido=$this->uf_insert_resumen($ls_peractnom,$as_codper,$ai_asires,$ai_dedres,$ai_apoempres,$ai_apopatres,$ai_priquires,$ai_segquires,$ai_monnetres);
		}
		return $lb_valido;
	}// end function uf_guardar_resumen
	//-----------------------------------------------------------------------------------------------------------------------------------

	
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
