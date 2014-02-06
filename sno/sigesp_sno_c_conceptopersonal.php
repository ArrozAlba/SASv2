<?php
class sigesp_sno_c_conceptopersonal
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_sno;
	var $io_fun_nomina;
	var $ls_codemp;
	var $ls_codnom;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_conceptopersonal()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_conceptopersonal
		//		   Access: public (sigesp_sno_d_conceptopersonal)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		ini_set('memory_limit','2048M');
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
		require_once("sigesp_sno.php");
		$this->io_sno= new sigesp_sno();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];		
	}// end function sigesp_sno_c_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_conceptopersonal)
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
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptopersonal($as_codconc,$ai_inicio,$ai_registros,&$ai_totrows,&$ao_object,&$ai_totpag)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptopersonal
		//		   Access: public (sigesp_sno_d_conceptopersonal)
		//	    Arguments: as_codconc  // código de concepto
		//				   ai_totrows  // total de filas del detalle
		//				   ao_object  // objetos del detalle
		//	      Returns: $lb_valido True si se ejecuto el buscar ó False si hubo error en el buscar
		//	  Description: Funcion que obtiene todo el personalconcepto asociado a un concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_orden="";
		$ls_pag="";
		$ls_pag1="";
		$ls_ordconc=$this->io_sno->uf_select_config("SNO","CONFIG","ORDEN CONCEPTO","CODIGO","C");
		switch($ls_ordconc)
		{
			case "CODIGO":
				$ls_orden=" ORDER BY sno_personal.codper ASC ";
				break;

			case "NOMBRE":
				$ls_orden=" ORDER BY sno_personal.nomper ASC ";
				break;

			case "APELLIDO":
				$ls_orden=" ORDER BY sno_personal.apeper ASC ";
				break;

			case "UNIDAD":
				$ls_orden=" ORDER BY sno_personalnomina.minorguniadm,sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm,sno_personalnomina.prouniadm ASC ";
				break;
		}
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;
			case "INFORMIX":
				$ls_pag1= " SKIP  ".$ai_inicio." FIRST ".$ai_registros;
			
			break;
		}
		$ls_sql="SELECT ".$ls_pag1." sno_conceptopersonal.codper, sno_conceptopersonal.codconc, sno_conceptopersonal.aplcon, sno_conceptopersonal.valcon, ".
				"       sno_conceptopersonal.acuemp, sno_conceptopersonal.acuiniemp, sno_conceptopersonal.acupat, ".
				" 		sno_conceptopersonal.acuinipat, sno_personal.nomper, sno_personal.apeper, ".
				"		(SELECT COUNT(codconc) ".
				"		   FROM sno_conceptopersonal ".
				"		   WHERE  codemp='".$this->ls_codemp."' AND codnom='".$this->ls_codnom."' AND codconc='".$as_codconc."') AS total ".
				"  FROM sno_conceptopersonal,sno_personalnomina, sno_personal ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codconc='".$as_codconc."'".
				"	AND sno_conceptopersonal.codemp=sno_personal.codemp ".
				"   AND sno_conceptopersonal.codper=sno_personal.codper ".
			    "   AND sno_conceptopersonal.codnom=sno_personalnomina.codnom ".
			    "   AND sno_conceptopersonal.codemp=sno_personalnomina.codemp ".
			    "   AND sno_conceptopersonal.codper=sno_personalnomina.codper ".
				$ls_orden.
				$ls_pag;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_load_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codper=$row["codper"];
				$li_total=$row["total"];
				$ls_nomper=$row["apeper"].", ".$row["nomper"];
				$li_aplcon=$row["aplcon"];
				if($li_aplcon=="1")
				{
					$ls_aplica="checked";
				}
				else
				{
					$ls_aplica="";
				}
				$li_acuemp=$row["acuemp"];
				$li_acuiniemp=$row["acuiniemp"];
				$li_acupat=$row["acupat"];
				$li_acuinipat=$row["acuinipat"];	
				$li_acuemp=$this->io_fun_nomina->uf_formatonumerico($li_acuemp);
				$li_acuiniemp=$this->io_fun_nomina->uf_formatonumerico($li_acuiniemp);
				$li_acupat=$this->io_fun_nomina->uf_formatonumerico($li_acupat);
				$li_acuinipat=$this->io_fun_nomina->uf_formatonumerico($li_acuinipat);
				$ao_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=hidden id=txcodper".$ai_totrows." value=".$ls_codper."><input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." value='".$ls_nomper."' class=sin-borde size=50  readonly>";
				$ao_object[$ai_totrows][2]="<input name=chkaplcon".$ai_totrows." type=checkbox id=chkaplcon".$ai_totrows." value='1' ".$ls_aplica.">";
				$ao_object[$ai_totrows][3]="<input name=txtacuemp".$ai_totrows." type=text id=txtacuemp".$ai_totrows." class=sin-borde size=15 maxlength=20 value=".$li_acuemp." onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtacuiniemp".$ai_totrows." type=text id=txtacuiniemp".$ai_totrows." class=sin-borde size=15 maxlength=20 value=".$li_acuiniemp." onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
				$ao_object[$ai_totrows][5]="<input name=txtacupat".$ai_totrows." type=text id=txtacupat".$ai_totrows." class=sin-borde size=15 maxlength=20 value=".$li_acupat." onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtacuinipat".$ai_totrows." type=text id=txtacuinipat".$ai_totrows." class=sin-borde size=15 maxlength=20 value=".$li_acuinipat." onKeyPress=return(ue_formatonumero(this,'.',',',event)) style=text-align:right>";
			}
			$this->io_sql->free_result($rs_data);
			$ai_totpag = ceil($li_total / $ai_registros); 
		}
		return $lb_valido;
	}// end function uf_load_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_conceptopersonal($as_codconc,$as_codper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//				   as_codper  // código de personal
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el conceptopersonal está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codconc FROM sno_conceptopersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ".
				"   AND codper='".$as_codper."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_select_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
	}// end function uf_select_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_conceptopersonal($as_codconc,$as_codper,$ai_aplcon,$ai_acuemp,$ai_acuiniemp,$ai_acupat,$ai_acuinipat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//				   as_codper  // código de personal
		//				   ai_aplcon  // aplica concepto
		//				   ai_acuemp  // acumulado empleado
		//				   ai_acuiniemp  // acumulado inicial empleado
		//				   ai_acupat  // acumulado patrón
		//				   ai_acuinipat  // acumulado inical patrón
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//    Description: Funcion que actualiza en la tabla de vacación período
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_conceptopersonal ".
				"   SET aplcon = ".$ai_aplcon.", ".
				"  		acuemp = ".$ai_acuemp.", ".
				"		acuiniemp = ".$ai_acuiniemp.", ".
				"		acupat = ".$ai_acupat.", ".
				"		acuinipat = ".$ai_acuinipat." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_update_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 
		
		return $lb_valido;
	}// end function uf_update_conceptopersonal	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_codconc,$as_codper,$ai_aplcon,$ai_acuemp,$ai_acuiniemp,$ai_acupat,$ai_acuinipat,$aa_seguridad)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_conceptopersonal)
		//	    Arguments: as_codconc  // código de concepto
		//				   as_codper  // código de personal
		//				   ai_aplcon  // aplica concepto
		//				   ai_acuemp  // acumulado empleado
		//				   ai_acuiniemp  // acumulado inicial empleado
		//				   ai_acupat  // acumulado patrón
		//				   ai_acuinipat  // acumulado inical patrón
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que actualiza el conceptopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_acuemp=str_replace(".","",$ai_acuemp);
		$ai_acuemp=str_replace(",",".",$ai_acuemp);
		$ai_acuiniemp=str_replace(".","",$ai_acuiniemp);
		$ai_acuiniemp=str_replace(",",".",$ai_acuiniemp);
		$ai_acupat=str_replace(".","",$ai_acupat);
		$ai_acupat=str_replace(",",".",$ai_acupat);
		$ai_acuinipat=str_replace(".","",$ai_acuinipat);
		$ai_acuinipat=str_replace(",",".",$ai_acuinipat);

		if(($this->uf_select_conceptopersonal($as_codconc,$as_codper)))
		{
			$lb_valido=$this->uf_update_conceptopersonal($as_codconc,$as_codper,$ai_aplcon,$ai_acuemp,$ai_acuiniemp,$ai_acupat,$ai_acuinipat,$aa_seguridad);
		}
		return $lb_valido;
	}// end function uf_guardar	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_aplicaconcepto($as_codcar,$as_codasicar,$as_codconc,&$ai_totperfil,&$ai_totrows,&$aa_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_aplicaconcepto
		//		   Access: public (sigesp_sno_p_aplicaconcepto)
		//	    Arguments: as_codcar  // Código de Cargo
		//				   as_codasicar  // Código de asignación de cargo
		//				   as_codconc  // Código del concepto
		//				   ai_totperfil  // Total de personas Filtradas
		//				   ai_totrows  // Total de Filas
		//				   aa_object  //  Arreglo de objectos que se van a imprimir
		//	      Returns: $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene el sueldo de un personal dado un ó sueldo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper, sno_conceptopersonal.aplcon ".
				"  FROM sno_personal, sno_personalnomina, sno_conceptopersonal ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codconc='".$as_codconc."' ".
				"   AND sno_personalnomina.codemp = sno_conceptopersonal.codemp ".
				"   AND sno_personalnomina.codnom = sno_conceptopersonal.codnom ".
				"   AND sno_personalnomina.codper = sno_conceptopersonal.codper ".
				"   AND sno_personal.codemp = sno_personalnomina.codemp ".
				"   AND sno_personal.codper = sno_personalnomina.codper ";
		if(empty($as_codasicar))
		{
			$ls_sql=$ls_sql."   AND sno_personalnomina.codcar='".$as_codcar."' ";
		}
		else
		{
			$ls_sql=$ls_sql."   AND sno_personalnomina.codasicar='".$as_codasicar."' ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto Personal MÉTODO->uf_load_aplicaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codper=$row["codper"];
				$ls_nomper=$row["apeper"].", ".$row["nomper"];
				$li_aplcon=$row["aplcon"];
				if($li_aplcon=="1")
				{
					$ls_aplcon="checked";
				}
				else
				{
					$ls_aplcon="";
				}
				$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=hidden id=txtcodper".$ai_totrows." value=".$ls_codper.">".$ls_codper."";
				$aa_object[$ai_totrows][2]=" ".$ls_nomper." ";
				$aa_object[$ai_totrows][3]="<input name=chkaplcon".$ai_totrows." type=checkbox value='1' ".$ls_aplcon.">";
			}
			$this->io_sql->free_result($rs_data);		
			$ai_totperfil=$ai_totrows;
		}
		return $lb_valido;
	}// end function uf_load_aplicaconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_aplicaconcepto($as_codconc,$as_codper,$ai_aplcon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_aplicaconcepto
		//		   Access: public (sigesp_sno_p_aplicaconcepto)
		//	    Arguments: as_codconc  // Código del Concepto
		//	    		   as_codper  // Código del Personal
		//				   ai_aplcon  // Aplica el Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que le aplica ó le desaplia los conceptos al personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	
		$ls_sql="UPDATE sno_conceptopersonal ".
				"   SET aplcon = ".$ai_aplcon." ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND codconc = '".$as_codconc."' ".
				"   AND codper = '".$as_codper."' ";

		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto Personal MÉTODO->uf_update_aplicaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if($ai_aplcon==1)
			{
				$ls_descripcion = "Aplico";
			}
			else
			{
				$ls_descripcion = "Quito";
			}
			$ls_descripcion =$ls_descripcion." el concepto ".$as_codconc." del personal ".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}// end function uf_update_aplicaconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos_x_personal($as_codper,&$ai_totrows,&$aa_object,&$ls_aplcontodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos_x_personal
		//		   Access: public (sigesp_sno_d_persxconce.php)
		//	    Arguments: as_codper  // Código de personal
		//				   ai_totrows  // Total de Filas
		//				   aa_object  //  Arreglo de objectos que se van a imprimir
		//	      Returns: $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene el sueldo de un personal dado un ó sueldo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_concepto.codconc,sno_concepto.nomcon, sno_conceptopersonal.aplcon ".
				"  FROM sno_concepto, sno_conceptopersonal ".
				" WHERE sno_concepto.codemp='".$this->ls_codemp."' ".
				"   AND sno_concepto.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codper='".$as_codper."' ".
				"   AND sno_concepto.codemp = sno_conceptopersonal.codemp ".
				"   AND sno_concepto.codnom = sno_conceptopersonal.codnom ".
				"   AND sno_concepto.codconc = sno_conceptopersonal.codconc ".
				"   AND sno_concepto.aplresenc = '0' ".
				" ORDER BY sno_concepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto Personal MÉTODO->uf_load_conceptos_x_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			$as_contar=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codconc=$row["codconc"];  
				$ls_nomcon=$row["nomcon"];
				$li_aplcon=$row["aplcon"];
				if($li_aplcon=="1")
				{
					$ls_aplcon="checked";
					$as_contar=$as_contar+1;
				}
				else
				{
					$ls_aplcon="";
				}
				$aa_object[$ai_totrows][1]="<input type=text name=txtcod".$ai_totrows." value='".$ls_codconc."' size=20 class=sin-borde readonly>";
				$aa_object[$ai_totrows][2]="<input type=text name=txtnom".$ai_totrows." value='".$ls_nomcon."' size=40 class=sin-borde readonly >";
			  	$aa_object[$ai_totrows][3]="<input name=chk".$ai_totrows." type=checkbox id=chk".$ai_totrows." value=1 class=sin-borde ".$ls_aplcon." >";
			}
			$this->io_sql->free_result($rs_data);
			//--------------------------------------------------------------------------------------------------------
			if ($as_contar==$ai_totrows)
			{ 
				$ls_aplcontodo="checked";
			}			
			//--------------------------------------------------------------------------------------------------------		
		}
		return $lb_valido;
	}// end function uf_load_conceptos_x_personal	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_conceptos_x_personal($as_codconc,$as_codper,$ai_aplcon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: conceptos_x_personal
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//				   as_codper  // código de personal
		//				   ai_aplcon  // aplica concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//    Description: Funcion que actualiza en la tabla de concepto por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 04/07/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_conceptopersonal ".
				"   SET aplcon = ".$ai_aplcon." ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"	AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ".
				"   AND codper='".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->conceptos_x_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		} 
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////					
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el concepto".$as_codconc." del personal ".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}		
		return $lb_valido;
	}// end function conceptos_x_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>