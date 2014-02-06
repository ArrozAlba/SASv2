<?php
class sigesp_sno_c_cierre_periodo
{
	var $io_sql;
	var $io_mensajes;
	var $io_seguridad;
	var $io_funciones;
	var $io_cierre_periodo2;
	var $io_cierre_periodo3;
	var $io_cierre_periodo4;
	var $io_vacacion;
	var $io_sno;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_cierre_periodo()
	{	
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_cierre_periodo
		//		   Access: public (sigesp_sno_p_manejoperiodo)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 15/02/2006 								
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
   		require_once("sigesp_sno_c_cierre_periodo2.php");
		$this->io_cierre_periodo2=new sigesp_sno_c_cierre_periodo2();
		require_once("sigesp_sno_c_cierre_periodo3.php");
		$this->io_cierre_periodo3=new sigesp_sno_c_cierre_periodo3();
		require_once("sigesp_sno_c_cierre_periodo4.php");
		$this->io_cierre_periodo4=new sigesp_sno_c_cierre_periodo4();
		require_once("sigesp_sno_c_vacacion.php");
		$this->io_vacacion=new sigesp_sno_c_vacacion();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];		
	}// end function sigesp_sno_c_cierre_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_p_manejoperiodo)
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
		unset($this->io_cierre_periodo2);
		unset($this->io_cierre_periodo3);
		unset($this->io_cierre_periodo4);
		unset($this->io_vacacion);
		unset($this->io_sno);
        unset($this->ls_codemp);
        unset($this->ls_codnom);       
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_cierre_periodo($as_codperi,$adt_fecdesper,$adt_fechasper,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_cierre_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo a cerrar
		//                 adt_fecdesper  //  fecha desde donde comienza el periodo
		//                 adt_fechasper  //  fecha hasta donde termina el periodo
		//                 aa_seguridad  //  arreglo de las variables de seguridad
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar el cierre del periodo 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 13/02/2006          
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
	   	$lb_valido=false;
	  	$li_total=$this->uf_verificar_periodo($ls_peractnom);
		$ls_statusg=0;
		$ls_statusi=0;
		$ls_valido=$this->uf_validarcierre_gastos_ingreso($ls_statusg,$ls_statusi);
		if (($ls_statusg==0)&&($ls_statusi==0))
		{  
			//if($li_total>0)
			//{
				$this->io_sql->begin_transaction();
				if($ls_conpronom=="1") // contabilización por proyectos
				{
					$lb_valido=$this->io_cierre_periodo4->uf_procesar_contabilizacion_proyectos();
									
				}
				else
				{
					$lb_valido=$this->io_cierre_periodo4->uf_procesar_contabilizacion();
					
				}
				if($lb_valido)
				{
					$ls_recdocpagperche=$_SESSION["la_nomina"]["recdocpagperche"];
					$ls_tipdocpagperche=$_SESSION["la_nomina"]["tipdocpagperche"];
					if(($ls_recdocpagperche=='1')&&($ls_tipdocpagperche!=""))
					{
						$lb_valido=$this->io_cierre_periodo4->uf_procesar_rec_doc_pago_personal_cheque();	
					}
				}
				if($lb_valido)
				{
					$lb_valido=$this->uf_cerrar_periodo($as_codperi,$adt_fecdesper,$adt_fechasper);
				
				}
				if($lb_valido)
				{
					$ld_fecdes="";
					$ld_fechas="";
					$lb_valido=$this->uf_load_proximoperiodo($ls_peractnom,$ld_fecdes,$ld_fechas);
				
				}
				if($lb_valido)
				{
					$li_metodo_vac=$this->io_sno->uf_select_config("SNO","CONFIG","METODO_VACACIONES","0","C");
					
				}
				if(($lb_valido)&&($li_metodo_vac!="0"))
				{
					$lb_valido=$this->io_cierre_periodo2->uf_actualizar_personal_vacaciones($adt_fechasper,$as_codperi,$li_metodo_vac);  
				
					if($lb_valido)
					{
						$lb_valido=$this->io_cierre_periodo2->uf_reingreso_personal_vac($adt_fecdesper,$adt_fechasper,$as_codperi,$li_metodo_vac);  
					
					}
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_vacacion->uf_procesar_porvencer($ld_fecdes,$ld_fechas,$aa_seguridad);
				}
				if($lb_valido)
				{
					$ls_periodo=str_pad((intval($ls_peractnom)+1),3,"0",0);
					if((trim($ld_fecdes)!="")&&(trim($ld_fechas)!=""))
					{
						$lb_valido=$this->uf_suspender_contratatados($ls_periodo,$ld_fecdes,$ld_fechas);
						if($lb_valido)
						{
							$lb_valido=$this->uf_load_contratatados_por_suspender($ls_periodo,$ld_fecdes,$ld_fechas);
						}					
					}
				}
				if($lb_valido)
				{
					$lb_valido=$this->io_sno->uf_crear_sessionnomina();
				}
				if($lb_valido)
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="PROCESS";
					$ls_descripcion ="Cerro el período ".$as_codperi." asociado a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////				
				}
	
				if($lb_valido)
				{
					$this->io_sql->commit(); 
					$this->io_mensajes->message("El Cierre de Período fue procesado.");
					$this->uf_eliminar_carpeta($ls_peractnom);
				}
				else
				{
					$this->io_sql->rollback();
					$this->io_mensajes->message("Ocurrio un error al cerrar el período.");
				}
				if($_SESSION["la_nomina"]["peractnom"]=="000")
				{
					print "<script language=JavaScript>";
					print "location.href='sigespwindow_blank.php'";
					print "</script>";		
				}
			/*}
			else
			{
				$this->io_mensajes->message("La nomina debe estar previamente calculada. Por favor Verifique.");
			} */
		 }
		 else
		 {
			$this->io_mensajes->message("No se Puede Cerrar el Periodo ya que se realizo el cierre Presupustario de Gasto y el de Ingreso.");
			$lb_valido=false;
		 } 
		 return  $lb_valido;    
	}// end function uf_procesar_cierre_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cerrar_periodo($as_codperi,$adt_fecdesper,$adt_fechasper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cerrar_periodo 
		//	    Arguments: as_codperi // codigo del periodo
		//                 adt_fechasper  // fecha del periodo hasta
		//                 adt_fechasper  //  fecha del periodo desde
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que procesa el cierre del periodo 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_perresnom=$_SESSION["la_nomina"]["perresnom"];
		$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	    $lb_valido=$this->io_cierre_periodo2->uf_acumular_conceptos($as_codperi);
		if($lb_valido)
		{
			$lb_valido=$this->uf_procesar_historico($as_codperi);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_actualizar_prestamo_cierre();
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_limpiar_constantes();  
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_limpiar_concepto();  
		}
		if($lb_valido)
		{
			//$lb_valido=$this->io_cierre_periodo3->uf_limpiar_proyectopersonal();  
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_actualizar_periodo($as_codperi,$ls_codperi_next,$ls_codperi_prev);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_limpiar_periodo($as_codperi,$ls_codperi_next);  
		}
		if(($lb_valido)&&($ls_codperi_prev))
		{		
			$lb_valido=$this->uf_restaurar_periodo($ls_codperi_next,$as_codperi);
		}
		return  $lb_valido;
	}// end function uf_cerrar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_historico($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_historico 
		//	    Arguments: as_codperi // codigo del periodo
		// 	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que procesas las tablas historicas 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=$this->uf_registrar_nomina_en_historico($as_codperi,$lb_insert);
		if(($lb_valido)&&(!$lb_insert))
		{
		   $lb_valido=$this->uf_eliminar_periodo_historico($as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->uf_insert_periodo_historico($as_codperi);
		}
		return  $lb_valido;
	}// end function uf_procesar_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_registrar_nomina_en_historico($as_codperi,&$lb_insert)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_registrar_nomina_en_historico 
		//	    Arguments: as_codperi // codigo del periodo
		//	    		   lb_insert // variable que me indica si ctualizó la nómina ó la inserto
		//	      Returns: lb_valido true si es correcto el registro o false en caso contrario
		//	  Description: Función que registra la nomina en la tablas historica sno_hnomina 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////
        $lb_insert=false;
		$lb_valido=true;
		$ls_sql="SELECT desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom,tipnom,subnom,racnom,adenom,espnom,".
				"		ctnom,ctmetnom,diabonvacnom,diareivacnom,diainivacnom,diatopvacnom,diaincvacnom,consulnom,descomnom,".
				"		codpronom,codbennom,conaponom,cueconnom,notdebnom,numvounom,recdocnom,tipdocnom,recdocapo,tipdocapo,".
				"		perresnom, conpernom, conpronom, titrepnom, codorgcestic, confidnom, recdocfid, tipdocfid, codbenfid, ".
				"		cueconfid, divcon, informa, recdocpagperche, tipdocpagperche,estctaalt, racobrnom ".
                "  FROM sno_nomina ".
                " WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		  $lb_valido=false;
		  $this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_registrar_nomina_en_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_desnom=$row["desnom"];
				$ls_tippernom=$row["tippernom"];
				$ls_despernom=$row["despernom"];
				$ldt_anocurnom=$row["anocurnom"];
				$ldt_fecininom=$this->io_funciones->uf_formatovalidofecha($row["fecininom"]);
				$ls_peractnom=$row["peractnom"];
				$li_numpernom=$row["numpernom"];
				$li_tipnom=$row["tipnom"]; 
				$ls_subnom=$row["subnom"]; 
				$ls_racnom=$row["racnom"]; 
				$ls_racobrnom=$row["racobrnom"]; 
				$ls_adenom=$row["adenom"]; 
				$ls_espnom=$row["espnom"]; 
				$ls_ctnom=$row["ctnom"]; 
				$ls_ctmetnom=$row["ctmetnom"]; 
				$li_diabonvacnom=$row["diabonvacnom"]; 
				$li_diareivacnom=$row["diareivacnom"]; 
				$li_diainivacnom=$row["diainivacnom"]; 
				$li_diatopvacnom=$row["diatopvacnom"];  
				$li_diaincvacnom=$row["diaincvacnom"];  
				$ls_consulnom=$row["consulnom"];  
				$ls_descomnom=$row["descomnom"];  
				$ls_codpronom=$row["codpronom"];
				$ls_codbennom=$row["codbennom"];
				$ls_conaponom=$row["conaponom"];
				$ls_cueconnom=$row["cueconnom"];
				$ls_notdebnom=$row["notdebnom"];
				$ls_numvounom=$row["numvounom"];
				$ls_perresnom=$row["perresnom"];
				$ls_recdocnom=$row["recdocnom"];
				$ls_recdocapo=$row["recdocapo"];
				$ls_tipdocnom=$row["tipdocnom"];
				$ls_tipdocapo=$row["tipdocapo"];
				$ls_conpernom=$row["conpernom"];
				$ls_conpronom=$row["conpronom"];
				$ls_titrepnom=$row["titrepnom"];
				$ls_codorgcestic=$row["codorgcestic"];
				$ls_confidnom=$row["confidnom"];
				$ls_recdocfid=$row["recdocfid"];
				$ls_tipdocfid=$row["tipdocfid"];
				$ls_codbenfid=$row["codbenfid"];
				$ls_cueconfid=$row["cueconfid"];
				$ls_divcon=$row["divcon"];
				$ls_informa=$row["informa"];
				$ls_recdocpagperche=$row["recdocpagperche"];
				$ls_tipdocpagperche=$row["tipdocpagperche"];
				$ls_estctaalt=$row["estctaalt"];
			}
			$lb_existe=$this->io_cierre_periodo2->uf_select_hnomina($as_codperi);
			if(!$lb_existe)
			{
				$ls_sql=" INSERT INTO sno_hnomina(codemp,codnom,desnom,tippernom,despernom,anocurnom,fecininom,peractnom,numpernom, ".
					   "            tipnom,subnom,racnom,adenom,espnom,ctnom,ctmetnom,diabonvacnom,diareivacnom,diainivacnom, ".
					   "            diatopvacnom,diaincvacnom,consulnom,descomnom,codpronom,codbennom,conaponom,cueconnom, ".
					   "			notdebnom,numvounom,perresnom,recdocnom,recdocapo,tipdocnom,tipdocapo,conpernom,conpronom, ".
					   "			titrepnom, codorgcestic,confidnom,recdocfid,tipdocfid,codbenfid,cueconfid, divcon, informa, ".
					   "            recdocpagperche,tipdocpagperche,estctaalt,racobrnom) ".
					   "     VALUES ('".$this->ls_codemp."','".$this->ls_codnom."','".$ls_desnom."','".$ls_tippernom."', ".
					   "             '".$ls_despernom."','".$ldt_anocurnom."','".$ldt_fecininom."','".$ls_peractnom."', ".
					   "             '".$li_numpernom."','".$li_tipnom."','".$ls_subnom."','".$ls_racnom."','".$ls_adenom."', ".
					   "             '".$ls_espnom."','".$ls_ctnom."','".$ls_ctmetnom."','".$li_diabonvacnom."','".$li_diareivacnom."', ".
					   "             '".$li_diainivacnom."','".$li_diatopvacnom."','".$li_diaincvacnom."','".$ls_consulnom."', ".
					   "             '".$ls_descomnom."','".$ls_codpronom."','".$ls_codbennom."','".$ls_conaponom."', ".
					   "             '".$ls_cueconnom."','".$ls_notdebnom."','".$ls_numvounom."','".$ls_perresnom."','".$ls_recdocnom."', ".
					   "			 '".$ls_recdocapo."','".$ls_tipdocnom."','".$ls_tipdocapo."','".$ls_conpernom."','".$ls_conpronom."', ".
					   "			 '".$ls_titrepnom."','".$ls_codorgcestic."','".$ls_confidnom."','".$ls_recdocfid."','".$ls_tipdocfid."',".
					   "			 '".$ls_codbenfid."','".$ls_cueconfid."','".$ls_divcon."','".$ls_informa."', ".
					   "             '".$ls_recdocpagperche."','".$ls_tipdocpagperche."', '".$ls_estctaalt."','".$ls_racobrnom."') ";
				$lb_insert=true;    
			}
			else
			{
				$ls_sql= "UPDATE sno_hnomina  ".
					   "   SET desnom='".$ls_desnom."', ".
					   "       tippernom='".$ls_tippernom."', ".
					   "       despernom='".$ls_despernom."', ". 
					   "       fecininom='".$ldt_fecininom."', ".
					   "       peractnom='".$ls_peractnom."', ".
					   "       numpernom='".$li_numpernom."', ".
					   "       tipnom='".$li_tipnom."', ".
					   "       racnom='".$ls_racnom."', ".
					   "       racobrnom='".$ls_racobrnom."', ".
					   "       adenom='".$ls_adenom."', ". 
					   "       espnom='".$ls_espnom."', ".
					   "       ctnom='".$ls_ctnom."', ".
					   "       ctmetnom='".$ls_ctmetnom."', ".
					   "       diabonvacnom='".$li_diabonvacnom."', ".
					   "       diareivacnom='".$li_diareivacnom."', ".
					   "       subnom='".$ls_subnom."', ".
					   "       diainivacnom='".$li_diainivacnom."', ".
					   "       diatopvacnom='".$li_diatopvacnom."', ".
					   "       diaincvacnom='".$li_diaincvacnom."', ".
					   "       consulnom='".$ls_consulnom."', ".
					   "       descomnom='".$ls_descomnom."', ".
					   "       codpronom='".$ls_codpronom."', ".
					   "       codbennom='".$ls_codbennom."', ".
					   "       conaponom='".$ls_conaponom."', ".
					   "       cueconnom='".$ls_cueconnom."', ".
					   "       notdebnom='".$ls_notdebnom."', ".
					   "       numvounom='".$ls_numvounom."', ".
					   "       recdocnom='".$ls_recdocnom."', ".
					   "       recdocapo='".$ls_recdocapo."', ".
					   "       tipdocnom='".$ls_tipdocnom."', ".
					   "       tipdocapo='".$ls_tipdocapo."', ".
					   "       perresnom='".$ls_perresnom."', ".
					   "       conpernom='".$ls_conpernom."', ".
					   "       conpronom='".$ls_conpronom."', ".
					   "	   titrepnom='".$ls_titrepnom."', ".
					   "       codorgcestic='".$ls_codorgcestic."', ".
					   "       confidnom='".$ls_confidnom."', ".
					   "       recdocfid='".$ls_recdocfid."', ".
					   "       tipdocfid='".$ls_tipdocfid."', ".
					   "       codbenfid='".$ls_codbenfid."', ".
					   "       cueconfid='".$ls_cueconfid."', ".
					   "       divcon='".$ls_divcon."', ".
					   "       informa='".$ls_informa."', ".
					   "       recdocpagperche='".$ls_recdocpagperche."', ".
					   "       tipdocpagperche='".$ls_tipdocpagperche."', ".
					   "       estctaalt='".$ls_estctaalt."'  ".					   
					   " WHERE codemp='".$this->ls_codemp."' ".
					   "   AND codnom='".$this->ls_codnom."' ".
					   "   AND anocurnom='".$ldt_anocurnom."' ".
					   "   AND peractnom='".$as_codperi."' ";
			} 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_registrar_nomina_en_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
	}// end function uf_registrar_nomina_en_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_periodo_historico($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_periodo_historico 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si es correcto los delete o false en caso contrario
		//	  Description: Función que elimina el periodos de todas las tablas historicas para proceder al cierre del mismo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $ldt_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=$this->io_cierre_periodo2->uf_delete_hsalida($ldt_anocurnom,$as_codperi);
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprenomina($ldt_anocurnom,$as_codperi);
		} 
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hresumen($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprestamoperiodo($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprestamoamortizado($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprestamos($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_htipoprestamo($ldt_anocurnom,$as_codperi);
		}
        if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconstantepersonal($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconstante($ldt_anocurnom,$as_codperi);
		} 
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconceptovacacion($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconceptopersonal($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprimaconcepto($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hconcepto($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hvacacpersonal($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hproyectopersonal($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hpersonalpension($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprimadocentepersonal($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hprimasdocentes($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hpersonalnomina($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido)
		{
           $lb_valido=$this->io_cierre_periodo2->uf_delete_hcodigounicorac($ldt_anocurnom,$as_codperi);		
		}
		if($lb_valido)
		{
           $lb_valido=$this->io_cierre_periodo2->uf_delete_hasignacioncargo($ldt_anocurnom,$as_codperi);		
		}		
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hcargo($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hunidadadmin($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hclasificacionobrero($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hproyecto($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo2->uf_delete_sno_hprimagrado($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hgrado($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_htabla($ldt_anocurnom,$as_codperi);
		} 
        if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hperiodo($ldt_anocurnom,$as_codperi);
		}  
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hsubnomina($ldt_anocurnom,$as_codperi);
		} 
		if($lb_valido) 
		{
		   $lb_valido=$this->io_cierre_periodo2->uf_delete_hencargaduria($ldt_anocurnom,$as_codperi);
		} 
		return $lb_valido;
	}// end function uf_eliminar_periodo_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_periodo_historico($as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_periodo_historico 
		//	    Arguments: as_codperi // codigo del periodo
		//	      Returns: lb_valido true si el insert se hizo correctamente o false en caso contrario
		//	  Description: Función que procesa las tablas historicas 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 09/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$ldt_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=$this->io_cierre_periodo2->uf_insert_hsubnomina($ldt_anocurnom,$as_codperi); 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hperiodo($ldt_anocurnom,$as_codperi); 
		}  
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_htabla($ldt_anocurnom,$as_codperi); 
		} 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hgrado($ldt_anocurnom,$as_codperi); 
		}   
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprimagrado($ldt_anocurnom,$as_codperi); 
		}   
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hunidadadmin($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hclasificacionobrero($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hproyecto($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hcargo($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hasignacioncargo($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hcodigounicorac($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hpersonalnomina($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprimasdocentes($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprimadocentepersonal($ldt_anocurnom,$as_codperi); 
		}
		
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hpersonalpension($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hproyectopersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hvacacpersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconcepto($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprimaconcepto($ldt_anocurnom,$as_codperi);
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconceptopersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconceptovacacion($ldt_anocurnom,$as_codperi); 
		} 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconstante($ldt_anocurnom,$as_codperi); 
		}    
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hconstantepersonal($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_htipoprestamo($ldt_anocurnom,$as_codperi); 
		}    
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprestamos($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprestamoperiodo($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprestamoamortizado($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hresumen($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hprenomina($ldt_anocurnom,$as_codperi); 
		}
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hsalida($ldt_anocurnom,$as_codperi); 
		} 
		if($lb_valido)
		{
		  $lb_valido=$this->io_cierre_periodo2->uf_insert_hencargaduria($ldt_anocurnom,$as_codperi); 
		} 
    	return $lb_valido;
	}// end function uf_insert_periodo_historico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_restaurar_periodo($as_codperi_actual,$as_codperi)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_restaurar_periodo 
		//	    Arguments: as_codperi // codigo del periodo
		//                 as_codperi_actual // codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que procesa el cierre del periodo 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 13/02/2006   
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		////////////////////////////////////////////////////////////////////////////////////////////
        $ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
		$lb_valido=true;
		$li_total=$this->io_cierre_periodo3->uf_existe_hpersonalnomina($as_codperi_actual,$ls_anocurnom);
		if($li_total>0)
		{
		   $lb_valido=$this->io_cierre_periodo3->uf_reversar_procesar_historico($as_codperi_actual,$as_codperi);
		}
		if($lb_valido)
		{
		   $ls_perires="000";
		   $lb_valido=$this->uf_reestablecer_periodo($ls_perires);
		}
        return $lb_valido;		
	}// end function uf_restaurar_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reestablecer_periodo($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_reestablecer_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de reestablecer un periodo 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 11/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	  	$lb_valido=true;
		$ls_sql="UPDATE sno_nomina ".
                "   SET perresnom='000' ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ";  
		//print $ls_sql."";
		$li_row=$this->io_sql->select($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_reestablecer_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}// end function uf_reestablecer_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_proximoperiodo($as_peractnom,&$ad_fecdes,&$ad_fechas)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_proximoperiodo
		//		   Access: private
		//	    Arguments: as_peractnom // período actual de la nómina
		//                 ad_fecdes // Fecha desde del próximo período    
		//                 ad_fechas // Fecha hasta del próximo período
		//	      Returns: lb_valido True si se ejecuto correctamente la función y false si hubo error
		//	  Description: función que devuelve la fecha desde y hata del próximo período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 20/03/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_periodo=str_pad((intval($as_peractnom)+1),3,"0",0);
		$ls_sql="SELECT fecdesper, fechasper ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$ls_periodo."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_load_proximoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ad_fecdes=$this->io_funciones->uf_formatovalidofecha($row["fecdesper"]);
				$ad_fechas=$this->io_funciones->uf_formatovalidofecha($row["fechasper"]);
			}
			$this->io_sql->free_result($rs_data);
		}
      	return ($lb_valido);  
    }// end function uf_load_proximoperiodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_suspender_contratatados($as_codperi,$adt_fecdesper,$adt_fechasper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suspender_contratatados 
		//	    Arguments: as_codperi_actual  //  codigo del periodo a cerrar
		//                 adt_fechasper  //  fecha desde donde comienza el periodo
		//                 adt_fechasper  //  fecha hasta donde termina el periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar el cierre del periodo 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 13/02/2006 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_mensaje="";
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.fecingper, sno_personalnomina.fecculcontr, ".
				"		sno_personal.nomper, sno_personal.apeper ".
                "  FROM sno_personalnomina, sno_periodo, sno_personal ".
                " WHERE sno_periodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_periodo.codnom='".$this->ls_codnom."' ".
				"   AND sno_periodo.codperi='".$as_codperi."' ".
				"   AND sno_personalnomina.fecculcontr>'1900-01-01' ".
				"   AND sno_personalnomina.fecculcontr<='".$adt_fecdesper."' ".
				"   AND sno_personalnomina.staper<>'4' ".
				"   AND sno_personalnomina.codemp=sno_periodo.codemp ".
				"   AND sno_personalnomina.codnom=sno_periodo.codnom ".
				"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				"   AND sno_personalnomina.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_suspender_contratatados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$ls_staper=4;
				$ls_sql="UPDATE sno_personalnomina ".
					    "   SET staper='".$ls_staper."', ".
					    "	   fecsusper='".$adt_fecdesper."' ".
					    " WHERE codemp='".$this->ls_codemp."' ".
					    "   AND codnom='".$this->ls_codnom."' ".
					    "   AND codper='".$ls_codper."' ";
				$ls_mensaje = $ls_mensaje.' Código: '.$ls_codper.'  -  '.$ls_apeper.', '.$ls_nomper.'\n';
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$ls_mensaje="";
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_suspender_contratatados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				}
			}
		}
		if($ls_mensaje!="")
		{
			$ls_mensaje=' PERSONAL SUSPENDIDO POR FINALIZACIÓN DE CONTRATO   \n\n  '.$ls_mensaje;
			$this->io_mensajes->message($ls_mensaje);
		}
		return $lb_valido;
    }// end function uf_suspender_contratatados	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_periodo_anterior(&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_periodo_anterior 
		//	    Arguments: ai_totrows // total de filas que tiene el arreglo
		//                 ao_object // arreglo de objetos 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que busca la información del período anterior
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
        $ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
        $ls_codperi_ant=$this->io_funciones->uf_rellenar_izq((intval($ls_peractnom)-1),0,3);	
	    $ls_sql="SELECT sno_periodo.codperi, sno_periodo.fecdesper, sno_periodo.fechasper, sno_periodo.cerper,".
				"		sno_periodo.conper, sno_periodo.apoconper, sno_periodo.ingconper, sno_periodo.fidconper, ".
				"		sno_periodo.totper ".
                "  FROM sno_periodo, sno_nomina ".
                " WHERE sno_nomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_nomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_periodo.codperi='".$ls_codperi_ant."' ".
				"   AND sno_periodo.cerper=1 ".
				"   AND sno_nomina.perresnom='000' ".
				"   AND sno_nomina.codemp=sno_periodo.codemp ".
				"   AND sno_nomina.codnom=sno_periodo.codnom ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		    $this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_select_periodo_anterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codperi=$row["codperi"];  
				$ls_fecdesper=$this->io_funciones->uf_convertirfecmostrar($row["fecdesper"]);
				$ls_fechasper=$this->io_funciones->uf_convertirfecmostrar($row["fechasper"]);
				$li_cerper=$row["cerper"];
				$ls_cerrado="";
				if($li_cerper==1)
				{
					$ls_cerrado="checked";
				}			
			    $li_conper=$row["conper"];
				$ls_contabilizado="";
				if($li_conper==1)
				{
					$ls_contabilizado="checked";
				}			
				$li_apoconper=$row["apoconper"];
				$ls_aporte="";
				if($li_apoconper==1)
				{
					$ls_aporte="checked";
				}			
				$li_ingconper=$row["ingconper"];
				$ls_ingreso="";
				if($li_ingconper==1)
				{
					$ls_ingreso="checked";
				}			
				$li_fidconper=$row["fidconper"];
				$ls_fideicomiso="";
				if($li_fidconper==1)
				{
					$ls_fideicomiso="checked";
				}			
				$li_totper=number_format($row["totper"],2,",",".");
				$ao_object[$ai_totrows][1]="<input type=text name=txtcodperi".$ai_totrows." value=".$ls_codperi." class=sin-borde  size=7  style=text-align:center readonly>";
				$ao_object[$ai_totrows][2]="<input type=text name=txtfecdesper".$ai_totrows." value=".$ls_fecdesper." size=12 class=sin-borde   style=text-align:center readonly >";
				$ao_object[$ai_totrows][3]="<input type=text name=txtfechasper".$ai_totrows." class=sin-borde value=".$ls_fechasper." size=12  style=text-align:center readonly>";
				$ao_object[$ai_totrows][4]="<input name=chkcerrada".$ai_totrows." type=checkbox id=chkcerrada".$ai_totrows." class=sin-borde ".$ls_cerrado." disabled>";
				$ao_object[$ai_totrows][5]="<input name=chkcontabilizada".$ai_totrows." type=checkbox id=chkcontabilizada".$ai_totrows." class=sin-borde ".$ls_contabilizado." disabled><input name='contabilizado".$ai_totrows."' type='hidden' id='contabilizado".$ai_totrows."' value='".$li_conper."'>";
				$ao_object[$ai_totrows][6]="<input name=chkaporte".$ai_totrows." type=checkbox id=chkaporte".$ai_totrows." class=sin-borde ".$ls_aporte." disabled><input name='aporte".$ai_totrows."' type='hidden' id='aporte".$ai_totrows."' value='".$li_apoconper."'>";
				$ao_object[$ai_totrows][7]="<input name=chkingreso".$ai_totrows." type=checkbox id=chkingreso".$ai_totrows." class=sin-borde ".$ls_ingreso." disabled><input name='ingreso".$ai_totrows."' type='hidden' id='ingreso".$ai_totrows."' value='".$li_ingconper."'>";
				$ao_object[$ai_totrows][8]="<input name=chkfideicomiso".$ai_totrows." type=checkbox id=chkfideicomiso".$ai_totrows." class=sin-borde ".$ls_fideicomiso." disabled><input name='fideicomiso".$ai_totrows."' type='hidden' id='fideicomiso".$ai_totrows."' value='".$li_fidconper."'>";
				$ao_object[$ai_totrows][9]="<input type=text name=txttotper".$ai_totrows." value=".$li_totper." class=sin-borde size=17 style=text-align:right readonly>";
		  	}
	  }
		return 	$lb_valido;
    }// end function uf_select_periodo_anterior	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_procesar_abrir_periodo($as_codperi_abrir,$as_codperi_actual,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_procesar_abrir_periodo 
		//	    Arguments: as_codperi_abrir // codigo del periodo abrir 
		//                 as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar el período abrir 
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 17/02/2006     
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
        $ls_codperi_abrir=$as_codperi_abrir;       
		$ls_codperi_actual=$as_codperi_actual;
	    $this->io_sql->begin_transaction();
		$lb_valido=$this->uf_guardar_periodo($as_codperi_actual);
		if($lb_valido)
		{
		    $lb_valido=$this->uf_abrir_periodo($as_codperi_abrir,$as_codperi_actual);
		} 
		if(($lb_valido)&&($as_codperi_actual<>'000'))
		{
		   // $lb_valido=$this->io_cierre_periodo3->uf_limpiar_periodo($as_codperi_actual,$as_codperi_abrir);
		}
		if($lb_valido)
		{
			$lb_valido=$this->io_sno->uf_crear_sessionnomina();
		}
		if($lb_valido)
		{		
			$lb_valido=$this->io_cierre_periodo4->uf_delete_contabilizacion($ls_codperi_abrir);
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Abrio el período ".$ls_codperi_abrir." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////				
		}
		if($lb_valido)
		{
		   $this->io_sql->commit(); 
		   $this->io_mensajes->message("El abrir período se proceso.");
	    }
	    else
	    {
		   $this->io_sql->rollback();
		   $this->io_mensajes->message("Ocurrio un error al abrir período.");
	    }
		return $lb_valido;
    }// end function uf_procesar_abrir_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_periodo($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de guardar un periodo que no ha sido calculado
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 11/02/2006
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
         $lb_valido=$this->uf_procesar_historico($as_codperi_actual); 
		 if($lb_valido)
		 {
		    $lb_valido=$this->uf_reestablecer_periodo($as_codperi_actual);
		 }
		 return  $lb_valido; 
    }// end function uf_guardar_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_abrir_periodo($as_codperi_abrir,$as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_abrir_periodo 
		//	    Arguments: as_codperi_abrir // codigo del periodo abrir 
		//                 as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se enacrga de abrir un periodo especifico
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 10/02/2006        
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($lb_valido)
		{
			$lb_valido=$this->io_cierre_periodo3->uf_reversar_procesar_historico($as_codperi_abrir,$as_codperi_actual);
		}
		if($lb_valido)
		{
		   $lb_valido=$this->io_cierre_periodo4->uf_reversar_actualizar_periodo($as_codperi_abrir);
		}
		return  $lb_valido;
    }// end function uf_abrir_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_periodo($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_periodo 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: li_total devuelve el total de registros encontrados
		//	  Description: Función que se encarga de verificar si puedo hacer el cierre del periodo
	    //     Creado por: Ing. Yozelin Barragán
	    // Fecha Creación: 13/02/2006          Fecha última Modificacion : 
		// Modificado Por: Ing. Yesenia Moreno						Fecha Última Modificación : 29/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT COUNT(valsal) AS total ".
                "  FROM sno_salida ".
                " WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi_actual."' ";
	    $rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
	    {
			$li_total=0;
		    $this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_verificar_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
	    }
	    else
	    {
		     if($row=$this->io_sql->fetch_row($rs_data))
			 {
				$li_total=$row["total"];
			 } 
		}
	   return $li_total;
    }// end function uf_verificar_periodo	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contabilizado_ant($as_codperi_actual)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contabilizado_ant 
		//	    Arguments: as_codperi_actual  //  codigo del periodo actual
		//	      Returns: lb_contabilizado devuelve si esta contabilizado el perído anterior
		//	  Description: Función que se encarga de verificar si el período anterior está contabilizado
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 30/05/2006          Fecha última Modificacion : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codperi=str_pad(intval($as_codperi_actual-1),3,"0",0);
		$lb_contabilizado=0;
		if($ls_codperi<>"000")
		{
			$ls_sql="SELECT conper, apoconper, ingconper, fidconper, ".
					"		(SELECT COUNT(sno_hsalida.codconc) ".
					"  		   FROM sno_hsalida ".
					" 		  WHERE sno_hsalida.codemp=sno_periodo.codemp ".
					"   		AND sno_hsalida.codnom=sno_periodo.codnom ".
					"   		AND sno_hsalida.codperi=sno_periodo.codperi ".
					"   		AND (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='P2') ".
					"   		AND sno_hsalida.valsal<>0) AS existeaporte, ".
					"		(SELECT COUNT(codperi) ".
					"  		   FROM sno_hperiodo ".
					" 		  WHERE codemp='".$this->ls_codemp."' ".
					"  			AND codnom='".$this->ls_codnom."' ".
					"   		AND codperi='".$ls_codperi."') AS existehistorico ".
					"  FROM sno_periodo ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND codperi='".$ls_codperi."' ";
			$rs_data=$this->io_sql->select($ls_sql);
			if ($rs_data===false)
			{
				$lb_contabilizado=0;
				$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_contabilizado_ant ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_conper=$row["conper"];
					$li_apoconper=$row["apoconper"];
					$li_ingconper=$row["ingconper"];
					$li_fidconper=$row["fidconper"];
					$li_existeaporte=intval($row["existeaporte"]);
					$li_existehistorico=intval($row["existehistorico"]);
					if((($li_conper=="1")&&(($li_apoconper=="1")||($li_existeaporte=="0")))||($li_existehistorico==0))
					{
						$lb_contabilizado=1;
					}
				}
				$this->io_sql->free_result($rs_data);	
			}
		}
		else
		{
			$lb_contabilizado=1;
		}
	   	return $lb_contabilizado;
    }// end function uf_contabilizado_ant	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_eliminar_carpeta($as_peractnom)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_carpeta 
		//	    Arguments: as_peractnom  //  codigo del periodo actual
		//	      Returns: lb_contabilizado devuelve si esta contabilizado el perído anterior
		//	  Description: Función que se encarga de eliminar la carpeta generada por los listados al banco.
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 25/08/2006          Fecha última Modificacion : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nomina=$_SESSION["la_nomina"]["codnom"];
		//----------------------------- Para los Disco al Banco
		$ls_ruta="txt/disco_banco/".$ls_nomina."-".$as_peractnom;
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
				@unlink($ls_ruta."/".$file);
			 }
		}
		@closedir($handle);
		$ls_ruta="txt/disco_banco";
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
			 	if($file==$ls_nomina."-".$as_peractnom)
				{
					rmdir($ls_ruta."/".$file);
				}
			 }
		}
		@closedir($handle);
		//----------------------------- Para los Aportes
		$ls_ruta="txt/aportes/".$ls_nomina."-".$as_peractnom;
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
				@unlink($ls_ruta."/".$file);
			 }
		}
		@closedir($handle);
		$ls_ruta="txt/aportes";
		$lista = array();
		$handle = @opendir($ls_ruta);
		while ($file = @readdir($handle))
		{
			 if(($file != '.') && ($file != '..'))
			 {
			 	if($file==$ls_nomina."-".$as_peractnom)
				{
					rmdir($ls_ruta."/".$file);
				}
			 }
		}
		@closedir($handle);
		
	   	return $lb_valido;
    }// end function uf_eliminar_carpeta	
	//---------------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_load_contratatados_por_suspender($as_codperi,$adt_fecdesper,$adt_fechasper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_contratatados_por_suspender 
		//	    Arguments: as_codperi_actual  //  codigo del periodo a cerrar
		//                 adt_fechasper  //  fecha desde donde comienza el periodo
		//                 adt_fechasper  //  fecha hasta donde termina el periodo
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar el cierre del periodo 
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 20/08/2007 
		// Modificado Por: 											Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_mensaje="";
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.fecingper, sno_personalnomina.fecculcontr, ".
				"		sno_personal.nomper, sno_personal.apeper ".
                "  FROM sno_personalnomina, sno_periodo, sno_personal ".
                " WHERE sno_periodo.codemp='".$this->ls_codemp."' ".
				"   AND sno_periodo.codnom='".$this->ls_codnom."' ".
				"   AND sno_periodo.codperi='".$as_codperi."' ".
				"   AND sno_personalnomina.fecculcontr>='".$adt_fecdesper."' ".
				"   AND sno_personalnomina.fecculcontr<='".$adt_fechasper."' ".
				"   AND sno_personalnomina.staper<>'4' ".
				"   AND sno_personalnomina.codemp=sno_periodo.codemp ".
				"   AND sno_personalnomina.codnom=sno_periodo.codnom ".
				"   AND sno_personalnomina.codemp=sno_personal.codemp ".
				"   AND sno_personalnomina.codper=sno_personal.codper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->uf_load_contratatados_por_suspender ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codper=$row["codper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$ls_mensaje = $ls_mensaje.' Código: '.$ls_codper.'  -  '.$ls_apeper.', '.$ls_nomper.'\n';
			}
		}
		if($ls_mensaje!="")
		{
			$ls_mensaje=' PERSONAL EL CUAL SE VENCE EL CONTRATO EN EL PERIODO '.$as_codperi.'  \n\n  '.$ls_mensaje;
			$this->io_mensajes->message($ls_mensaje);
		}
		return $lb_valido;
    }// end function uf_load_contratatados_por_suspender	
	//---------------------------------------------------------------------------------------------------------------------------------------
	
//-------------------------------------------------------------------------------------------------------------------------------------
    function uf_validarcierre_gastos_ingreso(&$as_statusg,&$as_statusi)
	{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validarcierre_gastos_ingreso
		//		   Access: private
		//     Argumentos: 
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que buscas los estatus de cierre del presuepuesto de gastos i de ingreso
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 28/08/2008 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT estciespg, estciespi FROM sigesp_empresa where codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->Cierre Periodo MÉTODO->SELECT->uf_validarcierre_gastos_ingreso ERROR->"
			                            .$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$as_statusg= $row["estciespg"];
				$as_statusi= $row["estciespi"];				
			}
		}
		return 	$lb_valido;
	}//fin de uf_validarcierre_gastos_ingreso
//------------------------------------------------------------------------------------------------------------------------------------
		 		 //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_chequear_encargaduria()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_chequear_encargaduria
		//		   Access: private
		//	      Returns: lb_valido 
		//	  Description: Funcion que chequea si hay encargadurias vencidas y aún activas
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 02/01/2009							Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	
		$ld_fecdesper=$_SESSION["la_nomina"]["fecdesper"];
		$ld_fechasper=$_SESSION["la_nomina"]["fechasper"];
		
		$ls_sql="SELECT codenc ".
				"  FROM sno_encargaduria ".
				" WHERE sno_encargaduria.codemp='".$this->ls_codemp."' ".
				"   AND sno_encargaduria.codnom='".$this->ls_codnom."' ".
				"   AND sno_encargaduria.estenc='1' ".
				"   AND fecfinenc BETWEEN '".$ld_fecdesper."' AND '".$ld_fechasper."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_chequear_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{	   
				$ls_codenc=$row["codenc"];
				$this->io_mensajes->message("La Encargaduria ".$ls_codenc." se encuentra vencida y no está en estado finalizada. Reverse la Encargaduría.");
				$lb_valido=false;
				
			} // fin del primer while
		}	   
	 	
		return $lb_valido;
	}// uf_chequear_encargaduria
//-----------------------------------------------------------------------------------------------------------------------------------	


}
?>