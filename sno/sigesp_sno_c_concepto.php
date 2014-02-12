<?php
class sigesp_sno_c_concepto
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_prestamo;
	var $io_vacacionconcepto;
	var $io_primaconcepto;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_concepto()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_concepto
		//		   Access: public (sigesp_sno_d_concepto)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_conexiones.php");
		$this->io_conexiones=new conexiones();	
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
		require_once("sigesp_sno_c_prestamo.php");
		$this->io_prestamo=new sigesp_sno_c_prestamo();
		require_once("sigesp_sno_c_vacacionconcepto.php");
		$this->io_vacacionconcepto=new sigesp_sno_c_vacacionconcepto();
		require_once("sigesp_sno_c_primaconcepto.php");
		$this->io_primaconcepto=new sigesp_sno_c_primaconcepto();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		if(array_key_exists("la_nomina",$_SESSION))
		{
        	$this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
	        $this->ls_anocurnom=$_SESSION["la_nomina"]["anocurnom"];
	        $this->ls_codperi=$_SESSION["la_nomina"]["peractnom"];
		}
		else
		{
			$this->ls_codnom="0000";
	        $this->ls_anocurnom="0000";
	        $this->ls_codperi="000";
		}
		
	}// end function sigesp_sno_c_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_concepto)
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
		unset($this->io_prestamo);
		unset($this->io_vacacionconcepto);
		unset($this->io_primaconcepto);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_concepto($as_codconc)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_concepto
		//		   Access: private
		//	    Arguments: as_codconc // Código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si el concepto está registrado
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_select_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
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
	}// end function uf_select_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_concepto($as_codconc,$as_nomcon,$as_titcon,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
								$as_cueconcon,$as_codpro,$as_estcla,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
								$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
								$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_intingcon,$as_cueingcon,
								$ai_poringcon,$as_repacucon,$as_repconsunicon,$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$as_persalnor,$as_perenc,$as_aplresenc,$as_codente,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_concepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto							as_nomcon  // Nombre
		//				   as_titcon  // Título											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado máximo							ai_valmincon  // valor mínimo
		//				   ai_valmaxcon  // valor máximo								as_concon  // condición 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // código de programática							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la programática
		//				   as_forpatcon  // fórmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // título de retención empleados
		//				   as_titretpatcon  // título de retención patrón				ai_valminpatcon  // valor mínimo patronal
		//				   ai_valmaxpatcon  // valor máximo patronal                    ai_conprenom  // Concepto de Prenómina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		aa_seguridad  // arreglo de las variables de seguridad
		//				   as_codprov // código de proveedor							as_codben  //  código de beneficiario
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon // Aplica contabilización por proyectos
		//                 as_persalnor // indica si pertecene a salario normar
		//                 as_perenc// indica si pertenece a encargaduría               as_aplresenc//indica si es de tipo resumen encargaduria
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta el concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_concepto(codemp,codnom,codconc,nomcon,titcon,forcon,acumaxcon,valmincon,valmaxcon,concon,cueprecon,".
				"cueconcon,codpro,sigcon,glocon,aplisrcon,sueintcon,intprocon,forpatcon,cueprepatcon,cueconpatcon,titretempcon,".
				"titretpatcon,valminpatcon,valmaxpatcon,conprenom,sueintvaccon,codprov,cedben,aplarccon,conprocon,estcla,intingcon,spi_cuenta,".
				"poringcon,repacucon,repconsunicon,consunicon,quirepcon,frevarcon,asifidper,asifidpat,persalnor,conperenc,aplresenc,codente) VALUES ".
				"('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codconc."',".
				"'".$as_nomcon."','".$as_titcon."','".$as_forcon."',".$ai_acumaxcon.",".$ai_valmincon.",".$ai_valmaxcon.",'".$as_concon."',".
				"'".$as_cueprecon."','".$as_cueconcon."','".$as_codpro."','".$as_sigcon."','".$as_glocon."','".$as_aplisrcon."',".
				"'".$as_sueintcon."','".$as_intprocon."','".$as_forpatcon."','".$as_cueprepatcon."','".$as_cueconpatcon."','".$as_titretempcon."',".
				"'".$as_titretpatcon."',".$ai_valminpatcon.",".$ai_valmaxpatcon.",".$ai_conprenom.",".$ai_sueintvaccon.",'".$as_codprov."',".
				"'".$as_codben."','".$as_aplarccon."','".$as_aplconprocon."','".$as_estcla."','".$as_intingcon."','".$as_cueingcon."',".$ai_poringcon.",".
				"'".$as_repacucon."','".$as_repconsunicon."','".$as_consunicon."','".$as_quirepcon."','".$as_frevarcon."','".$as_asifidper."','".$as_asifidpat."','".$as_persalnor."','".$as_perenc."','".$as_aplresenc."','".$as_codente."')";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if(($li_row===false))
		{
 			$lb_valido=false;
			$this->io_sql->rollback();
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_insert_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el concepto ".$as_codconc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			
			if($lb_valido)
			{
				$lb_valido=$this->uf_insert_conceptopersonal($as_codconc,$as_glocon,$aa_seguridad);
			}
			
			if($lb_valido)
			{
				$this->io_mensajes->message("El Concepto fue registrado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_insert_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		return $lb_valido;
	}// end function uf_insert_concepto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptopersonal($as_codconc,$as_glocon,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que graba los conceptos a personal nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO sno_conceptopersonal(codemp,codnom,codper,codconc,aplcon,valcon,acuemp,acuiniemp,acupat,acuinipat) ".
				"SELECT codemp,codnom,codper,'".$as_codconc."',".$as_glocon.",0,0,0,0,0 ".
				"  FROM sno_personalnomina ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_insert_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el conceptopersonal concepto ".$as_codconc.", asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_concepto($as_codconc,$as_nomcon,$as_titcon,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
								$as_cueconcon,$as_codpro,$as_estcla,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
								$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
								$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_intingcon,$as_cueingcon,
								$ai_poringcon,$as_repacucon,$as_repconsunicon,$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$as_persalnor,$as_perenc,$as_aplresenc,$as_codente,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_concepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto							as_nomcon  // Nombre
		//				   as_titcon  // Título											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado máximo							ai_valmincon  // valor mínimo
		//				   ai_valmaxcon  // valor máximo								as_concon  // condición 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // código de programática							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la programática
		//				   as_forpatcon  // fórmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // título de retención empleados
		//				   as_titretpatcon  // título de retención patrón				ai_valminpatcon  // valor mínimo patronal
		//				   ai_valmaxpatcon  // valor máximo patronal                    ai_conprenom  // Concepto de Prenómina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		aa_seguridad  // arreglo de las variables de seguridad
		//				   as_codprov // código de proveedor							as_codben  //  código de beneficiario
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon  // Aplica Contabilización por proyectos
		//                 as_persalnor // indica si pertecene a salario normar
		//                 as_perenc// indica si pertenece a encargaduría               as_aplresenc//indica si es de tipo resumen encargaduria
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_concepto ".
				"   SET nomcon='".$as_nomcon."', ".
				"		titcon='".$as_titcon."', ".
				"		forcon='".$as_forcon."', ".
				"		acumaxcon=".$ai_acumaxcon.", ".
				"		valmincon=".$ai_valmincon.", ".
				"		valmaxcon=".$ai_valmaxcon.", ".
				"		concon='".$as_concon."', ".
				"		cueprecon='".$as_cueprecon."', ".
				"		cueconcon='".$as_cueconcon."', ".
				"		codpro='".$as_codpro."', ".
				"		estcla='".$as_estcla."', ".
				"		glocon='".$as_glocon."', ".
				"		aplisrcon='".$as_aplisrcon."', ".
				"		sueintcon='".$as_sueintcon."', ".
				"		intprocon='".$as_intprocon."', ".
				"		forpatcon='".$as_forpatcon."', ".
				"		cueprepatcon='".$as_cueprepatcon."', ".
				"		cueconpatcon='".$as_cueconpatcon."', ".
				"		titretempcon='".$as_titretempcon."', ".
				"		titretpatcon='".$as_titretpatcon."', ".
				"		valminpatcon=".$ai_valminpatcon.", ".
				"		valmaxpatcon=".$ai_valmaxpatcon.", ".
				"		conprenom=".$ai_conprenom.", ".
				"		codprov='".$as_codprov."', ".
				"		cedben='".$as_codben."', ".
				"		sueintvaccon=".$ai_sueintvaccon.", ".
				"		aplarccon='".$as_aplarccon."', ".
				"		conprocon='".$as_aplconprocon."', ".
				"       repacucon='".$as_repacucon."', ".
				"		repconsunicon='".$as_repconsunicon."', ".
				"		consunicon='".$as_consunicon."', ".
				"		intingcon='".$as_intingcon."', ".
				"		spi_cuenta='".$as_cueingcon."', ".
				"		poringcon=".$ai_poringcon.", ".
				"		quirepcon='".$as_quirepcon."', ".
				"		frevarcon='".$as_frevarcon."', ".
				"		asifidper='".$as_asifidper."', ".
				"		asifidpat='".$as_asifidpat."', ".
				"       persalnor='".$as_persalnor."', ".				
				"       conperenc='".$as_perenc."', ".				
				"       aplresenc='".$as_aplresenc."', ".
				"       codente='".$as_codente."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";	
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_update_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el concepto ".$as_codconc." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{	
				$this->io_mensajes->message("El Concepto fue Actualizado.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_update_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_concepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$as_codconc,$as_nomcon,$as_titcon,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,$as_concon,$as_cueprecon,
						$as_cueconcon,$as_codpro,$as_estcla,$as_sigcon,$as_glocon,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,
						$as_cueprepatcon,$as_cueconpatcon,$as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,
						$ai_conprenom,$ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_intingcon,$as_cueingcon,$ai_poringcon,
						$as_repacucon,$as_repconsunicon,$as_consunicon,$as_quirepcon,$as_frevarcon,$as_asifidper,$as_asifidpat,$as_persalnor,$as_perenc,$as_aplresenc,$as_codente,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sno_d_concepto)
		//	    Arguments: as_codconc  // código de concepto							as_nomcon  // Nombre
		//				   as_titcon  // Título											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado máximo							ai_valmincon  // valor mínimo
		//				   ai_valmaxcon  // valor máximo								as_concon  // condición 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // código de programática							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la programática
		//				   as_forpatcon  // fórmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // título de retención empleados
		//				   as_titretpatcon  // título de retención patrón				ai_valminpatcon  // valor mínimo patronal
		//				   ai_valmaxpatcon  // valor máximo patronal                    ai_conprenom  // Concepto de Prenómina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		aa_seguridad  // arreglo de las variables de seguridad
		//				   as_codprov // código de proveedor							as_codben  //  código de beneficiario
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon // Aplica contablización por proyectos
		//                 as_persalnor // indica si pertecene a salario normar
		//                 as_perenc// indica si pertenece a encargaduría               as_aplresenc//indica si es de tipo resumen encargaduria
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;				
		$ai_acumaxcon=str_replace(".","",$ai_acumaxcon);
		$ai_acumaxcon=str_replace(",",".",$ai_acumaxcon);				
		$ai_valmincon=str_replace(".","",$ai_valmincon);
		$ai_valmincon=str_replace(",",".",$ai_valmincon);				
		$ai_valmaxcon=str_replace(".","",$ai_valmaxcon);
		$ai_valmaxcon=str_replace(",",".",$ai_valmaxcon);				
		$ai_valminpatcon=str_replace(".","",$ai_valminpatcon);
		$ai_valminpatcon=str_replace(",",".",$ai_valminpatcon);				
		$ai_valmaxpatcon=str_replace(".","",$ai_valmaxpatcon);
		$ai_valmaxpatcon=str_replace(",",".",$ai_valmaxpatcon);				
		$ai_poringcon=str_replace(".","",$ai_poringcon);				
		$ai_poringcon=str_replace(",",".",$ai_poringcon);				
		$as_forcon=strtoupper($as_forcon);
		$as_concon=strtoupper($as_concon);
		switch ($as_existe)
		{
			case "FALSE":
				if($this->uf_select_concepto($as_codconc)===false)
				{
					$lb_valido=$this->uf_insert_concepto($as_codconc,$as_nomcon,$as_titcon,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,
														 $as_concon,$as_cueprecon,$as_cueconcon,$as_codpro,$as_estcla,$as_sigcon,$as_glocon,
														 $as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,$as_cueprepatcon,$as_cueconpatcon,
														 $as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,$ai_conprenom,
														 $ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_intingcon,
														 $as_cueingcon,$ai_poringcon,$as_repacucon,$as_repconsunicon,$as_consunicon,$as_quirepcon,
														 $as_frevarcon,$as_asifidper,$as_asifidpat,$as_persalnor,$as_perenc,$as_aplresenc,$as_codente,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Concepto ya existe, no lo puede incluir.");
				}
				break;

			case "TRUE":
				if(($this->uf_select_concepto($as_codconc)))
				{
					$lb_valido=$this->uf_update_concepto($as_codconc,$as_nomcon,$as_titcon,$as_forcon,$ai_acumaxcon,$ai_valmincon,$ai_valmaxcon,
														 $as_concon,$as_cueprecon,$as_cueconcon,$as_codpro,$as_estcla,$as_sigcon,$as_glocon,
														 $as_aplisrcon,$as_sueintcon,$as_intprocon,$as_forpatcon,$as_cueprepatcon,$as_cueconpatcon,
														 $as_titretempcon,$as_titretpatcon,$ai_valminpatcon,$ai_valmaxpatcon,$ai_conprenom,
														 $ai_sueintvaccon,$as_codprov,$as_codben,$as_aplarccon,$as_aplconprocon,$as_intingcon,
														 $as_cueingcon,$ai_poringcon,$as_repacucon,$as_repconsunicon,$as_consunicon,$as_quirepcon,
														 $as_frevarcon,$as_asifidper,$as_asifidpat,$as_persalnor,$as_perenc,$as_aplresenc,$as_codente,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("El Concepto no existe, no lo puede actualizar.");
				}
				break;
		}
		return $lb_valido;
	}// end function uf_guardar		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_salida($as_codconc)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_salida
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ninguna salida tenga asociada este concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_select_salida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_salida	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_prenomina($as_codconc)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_prenomina
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que valida que ninguna prenomina tenga asociada este concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación:22/05/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_prenomina ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_select_prenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe;    
	}// end function uf_select_prenomina	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_conceptopersonal($as_codconc,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_conceptopersonal
		//		   Access: private
		//	    Arguments: as_codconc  // código del concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el conceptopersonal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_conceptopersonal ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
					
	   	$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó los conceptopersonal asociados al concepto ".$as_codconc." asociada a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			if($lb_valido===false)
			{
				$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_delete_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			}
		}
		return $lb_valido;
    }// end function uf_delete_conceptopersonal		
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_delete_concepto($as_codconc,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_concepto
		//		   Access: public (sigesp_sno_d_concepto)
		//	    Arguments: as_codconc  // código de concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina el concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
        if (($this->uf_select_salida($as_codconc)===false)&&
			($this->uf_select_prenomina($as_codconc)===false)&&
			($this->io_vacacionconcepto->uf_select_vacacionconcepto("codconc",$as_codconc)===false)&&
			($this->io_primaconcepto->uf_select_primaconcepto("codconc",$as_codconc,"")===false)&&
			($this->io_prestamo->uf_select_prestamo("codconc",$as_codconc)===false))
		{
			$this->io_sql->begin_transaction();
			$lb_valido=$this->uf_delete_conceptopersonal($as_codconc,$aa_seguridad);
			if($lb_valido)
			{			
				$ls_sql="DELETE ".
						"  FROM sno_concepto ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND codnom='".$this->ls_codnom."' ".
						"   AND codconc='".$as_codconc."' ";
						
				$li_row=$this->io_sql->execute($ls_sql);
				if($li_row===false)
				{
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_delete_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$this->io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="DELETE";
					$ls_descripcion ="Eliminó el concepto  ".$as_codconc." asociada a la nómina ".$this->ls_codnom;
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					if($lb_valido)
					{	
						$this->io_mensajes->message("El Concepto fue Eliminado.");
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_delete_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
						$this->io_sql->rollback();
					}
				}
			}
		} 
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("No se puede eliminar el registro. Hay Concepto de vacaciones, Prestamos ó primas asociadas a este concepto ó ya se calculó la nómina ó la prenómina");
		}       
		return $lb_valido;
    }// end function uf_delete_concepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_concepto(&$as_existe,&$as_codconc,&$as_nomcon,&$as_titcon,&$as_forcon,&$ai_acumaxcon,&$ai_valmincon,&$ai_valmaxcon,
							  &$as_concon,&$as_cueprecon,&$as_cueconcon,&$as_codpro,&$as_sigcon,&$as_glocon,&$as_aplisrcon,&$as_sueintcon,
							  &$as_intprocon,&$as_forpatcon,&$as_cueprepatcon,&$as_cueconpatcon,&$as_titretempcon,&$as_titretpatcon,
							  &$ai_valminpatcon,&$ai_valmaxpatcon,&$as_denprecon,&$as_denconcon,&$as_codestpro1,&$as_codestpro2,
							  &$as_codestpro3,&$as_codestpro4,&$as_codestpro5,&$as_denestpro1,&$as_denestpro2,&$as_denestpro3,
							  &$as_denestpro4,&$as_denestpro5,&$as_dencueconpat,&$as_dencueprepat,&$ai_conprenom,&$ai_sueintvaccon,
							  &$as_descon,&$as_coddescon,&$as_desdescon,&$as_aplarccon,&$as_aplconprocon,&$as_estcla1,&$as_estcla2,
							  &$as_estcla3,&$as_estcla4,&$as_estcla5,&$as_intingcon,&$as_cueingcon,&$as_dencueing,&$ai_poringcon,
							  &$as_repacucon,&$as_repconsunicon,&$as_consunicon,&$as_quirepcon,&$as_frevarcon,&$as_asifidper,&$as_asifidpat,&$as_persalnor,&$as_perenc, &$as_aplresenc, &$as_codente, &$as_txtente)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_concepto
		//		   Access: public (sigesp_sno_d_concepto)
		//	    Arguments: as_codconc  // código de concepto							as_nomcon  // Nombre
		//				   as_titcon  // Título											as_forcon  // Formula
		//				   ai_acumaxcon  // acumulado máximo							ai_valmincon  // valor mínimo
		//				   ai_valmaxcon  // valor máximo								as_concon  // condición 
		//				   as_cueprecon  // cuenta de presupuesto						as_cueconcon  // cuenta contable
		//				   as_codpro  // código de programática							as_sigcon  // signo
		//				   as_glocon  // si la constante es global						as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				as_intprocon  // si esta integrada a la programática
		//				   as_forpatcon  // fórmula Patronal							as_cueprepatcon  // cuenta de presupuesto patronal
		//				   as_cueconpatcon  // cuenta contable patronal					as_titretempcon  // título de retención empleados
		//				   as_titretpatcon  // título de retención patrón				ai_valminpatcon  // valor mínimo patronal
		//				   ai_valmaxpatcon  // valor máximo patronal                    ai_conprenom  // Concepto de Prenómina
		//				   ai_sueintvaccon // Si pertenece al Sueldo Int Vacaciones		as_descon // Destino de la contabilización
		//				   as_coddescon // código del destino de la contabilización		as_desdescon  //  descripción del destino de la contabilización
		//				   as_aplarccon  // Si aplica ARC								as_aplconprocon // Aplica contabilización por proyectos
		//                  as_persalnom // indica si pertence al salario normal
		//                 as_perenc// indica si pertenece a encargaduría               as_aplresenc//indica si es de tipo resumen encargaduria
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene los conceptos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$li_longestpro1= (25-$ls_loncodestpro1);
		$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$li_longestpro2= (25-$ls_loncodestpro2);
		$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$li_longestpro3= (25-$ls_loncodestpro3);
		$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$li_longestpro4= (25-$ls_loncodestpro4);
		$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
		$li_longestpro5= (25-$ls_loncodestpro5);
		$lb_valido=true;
		if($as_intprocon=="1")
		{
			$ls_programatica="		(SELECT denominacion ".
							 "		   FROM spg_cuentas ".
							 "		  WHERE codemp='".$this->ls_codemp."'	".
							 "		    AND spg_cuentas.codestpro1=substr(sno_concepto.codpro,1,25) ".
							 "		    AND spg_cuentas.codestpro2=substr(sno_concepto.codpro,25,25) ".
							 "		    AND spg_cuentas.codestpro3=substr(sno_concepto.codpro,51,25) ".
							 "		    AND spg_cuentas.codestpro4=substr(sno_concepto.codpro,76,25) ".
							 "		    AND spg_cuentas.codestpro5=substr(sno_concepto.codpro,101,25) ".
							 "		    AND spg_cuentas.spg_cuenta=sno_concepto.cueprecon) as denprecon, ".
							 "		(SELECT denominacion ".
							 "		   FROM spg_cuentas ".
							 "		  WHERE codemp='".$this->ls_codemp."'	".
							 "		    AND spg_cuentas.codestpro1=substr(sno_concepto.codpro,1,25) ".
							 "		    AND spg_cuentas.codestpro2=substr(sno_concepto.codpro,26,25) ".
							 "		    AND spg_cuentas.codestpro3=substr(sno_concepto.codpro,51,25) ".
							 "		    AND spg_cuentas.codestpro4=substr(sno_concepto.codpro,76,25) ".
							 "		    AND spg_cuentas.codestpro5=substr(sno_concepto.codpro,101,25) ".
							 "		    AND spg_cuentas.spg_cuenta=sno_concepto.cueprepatcon) as dencueprepat ";
		}
		else
		{
			$ls_programatica=" '' as denprecon, '' as dencueprepat ";
		}
		$ls_sql="SELECT codconc, nomcon, titcon, sigcon, forcon, glocon, acumaxcon, valmincon, valmaxcon, concon, cueprecon, ".
				"		cueconcon, aplisrcon, sueintcon, intprocon, codpro, forpatcon, cueprepatcon, cueconpatcon, titretempcon, ".
				"		titretpatcon, valminpatcon, valmaxpatcon, codprov, cedben, conprenom, sueintvaccon, aplarccon, conprocon,estcla, ".
				"		intingcon, spi_cuenta, poringcon, repacucon, repconsunicon, consunicon, quirepcon, frevarcon, asifidper, asifidpat, persalnor, aplresenc, conperenc,codente, ".
				"		(SELECT descripcion_ente ".
				"		   FROM sno_entes ".
				"		  WHERE codigo_ente = codente) as nombre_ente, 	".	
				"		(SELECT nompro ".
				"		   FROM rpc_proveedor ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND rpc_proveedor.cod_pro=sno_concepto.codprov) as proveedor, ".
				"		(SELECT nombene ".
				"		   FROM rpc_beneficiario ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND rpc_beneficiario.ced_bene=sno_concepto.cedben) as beneficiario, ".
				"		(SELECT denominacion ".
				"		   FROM scg_cuentas ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND scg_cuentas.sc_cuenta=sno_concepto.cueconcon) as dencuecon, ".
				"		(SELECT denominacion ".
				"		   FROM spi_cuentas ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND spi_cuentas.spi_cuenta=sno_concepto.spi_cuenta) as dencueing, ".
				"		(SELECT denominacion ".
				"		   FROM scg_cuentas ".
				"		  WHERE codemp='".$this->ls_codemp."'	".
				"		    AND scg_cuentas.sc_cuenta=sno_concepto.cueconpatcon) as dencueconpat, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep1.codestpro1=substr(sno_concepto.codpro,1,25)".
				"           AND spg_ep1.estcla = sno_concepto.estcla) as denestpro1, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep1 ".
				"		  WHERE spg_ep1.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep1.codestpro1=substr(sno_concepto.codpro,1,25)".
				"           AND spg_ep1.estcla = sno_concepto.estcla) as estcla1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep2.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep2.codestpro2=substr(sno_concepto.codpro,26,25)".
				"           AND spg_ep2.estcla = sno_concepto.estcla ) as denestpro2, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep2 ".
				"		  WHERE spg_ep2.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep2.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep2.codestpro2=substr(sno_concepto.codpro,26,25)".
				"           AND spg_ep2.estcla = sno_concepto.estcla ) as estcla2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep3.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep3.codestpro2=substr(sno_concepto.codpro,26,25) ".
				"		    AND spg_ep3.codestpro3=substr(sno_concepto.codpro,51,25)".
				"           AND spg_ep3.estcla = sno_concepto.estcla) as denestpro3, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep3 ".
				"		  WHERE spg_ep3.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep3.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep3.codestpro2=substr(sno_concepto.codpro,26,25) ".
				"		    AND spg_ep3.codestpro3=substr(sno_concepto.codpro,51,25)".
				"           AND spg_ep3.estcla = sno_concepto.estcla) as estcla3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep4.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep4.codestpro2=substr(sno_concepto.codpro,26,25) ".
				"		    AND spg_ep4.codestpro3=substr(sno_concepto.codpro,51,25) ".
				"		    AND spg_ep4.codestpro4=substr(sno_concepto.codpro,76,25)".
				"           AND spg_ep4.estcla = sno_concepto.estcla) as denestpro4, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep4 ".
				"		  WHERE spg_ep4.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep4.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep4.codestpro2=substr(sno_concepto.codpro,26,25) ".
				"		    AND spg_ep4.codestpro3=substr(sno_concepto.codpro,51,25) ".
				"		    AND spg_ep4.codestpro4=substr(sno_concepto.codpro,76,25)".
				"           AND spg_ep4.estcla = sno_concepto.estcla) as estcla4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep5.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep5.codestpro2=substr(sno_concepto.codpro,26,25) ".
				"		    AND spg_ep5.codestpro3=substr(sno_concepto.codpro,51,25) ".
				"		    AND spg_ep5.codestpro4=substr(sno_concepto.codpro,76,25) ".
				"		    AND spg_ep5.codestpro5=substr(sno_concepto.codpro,101,25)".
				"           AND spg_ep5.estcla = sno_concepto.estcla) as denestpro5, ".
				"		(SELECT estcla ".
				"		   FROM spg_ep5 ".
				"		  WHERE spg_ep5.codemp='".$this->ls_codemp."'	".
				"		    AND spg_ep5.codestpro1=substr(sno_concepto.codpro,1,25) ".
				"		    AND spg_ep5.codestpro2=substr(sno_concepto.codpro,26,25) ".
				"		    AND spg_ep5.codestpro3=substr(sno_concepto.codpro,51,25) ".
				"		    AND spg_ep5.codestpro4=substr(sno_concepto.codpro,76,25) ".
				"		    AND spg_ep5.codestpro5=substr(sno_concepto.codpro,101,25)".
				"           AND spg_ep5.estcla = sno_concepto.estcla) as estcla5, ".
				$ls_programatica.
				"  FROM sno_concepto ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codnom='".$this->ls_codnom."'".
				"   AND codconc='".$as_codconc."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_load_concepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			switch($_SESSION["la_empresa"]["estmodest"])
			{
				case "1": // Modalidad por Proyecto
					$li_len1=20;
					$li_len2=6;
					$li_len3=3;
					$li_len4=2;
					$li_len5=2;
					break;
					
				case "2": // Modalidad por Presupuesto
					$li_len1=2;
					$li_len2=2;
					$li_len3=2;
					$li_len4=2;
					$li_len5=2;
					break;
			}
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codconc=$row["codconc"];
				$as_nomcon=$row["nomcon"];
				$as_titcon=$row["titcon"];
				$as_forcon=$row["forcon"];
				$ai_acumaxcon=$row["acumaxcon"];
				$ai_acumaxcon=$this->io_fun_nomina->uf_formatonumerico($ai_acumaxcon);
				$ai_valmincon=$row["valmincon"];
				$ai_valmincon=$this->io_fun_nomina->uf_formatonumerico($ai_valmincon);
				$ai_valmaxcon=$row["valmaxcon"];
				$ai_valmaxcon=$this->io_fun_nomina->uf_formatonumerico($ai_valmaxcon);
				$as_concon=$row["concon"];
				$as_cueprecon=$row["cueprecon"];
				$as_denprecon=$row["denprecon"];
				$as_cueconcon=$row["cueconcon"];
				$as_denconcon=$row["dencuecon"];
				$ls_codpro=$row["codpro"];
				$as_intprocon=$row["intprocon"];
				$as_frevarcon=$row["frevarcon"];
				$as_asifidper=$row["asifidper"];
				$as_asifidpat=$row["asifidpat"];
				$as_persalnor=$row["persalnor"];
				if($as_intprocon==1)
				{
					$as_estcla1=$row["estcla1"];
					$as_estcla2=$row["estcla2"];
					$as_estcla3=$row["estcla3"];
					$as_estcla4=$row["estcla4"];
					$as_estcla5=$row["estcla5"];
					$as_codestpro1=substr($ls_codpro,0,25);
					$as_codestpro2=substr($ls_codpro,25,25);
					$as_codestpro3=substr($ls_codpro,50,25);
					$as_codestpro4=substr($ls_codpro,75,25);
					$as_codestpro5=substr($ls_codpro,100,25);
					$as_codestpro1=substr($as_codestpro1,$li_longestpro1,$ls_loncodestpro1);
					$as_codestpro2=substr($as_codestpro2,$li_longestpro2,$ls_loncodestpro2);
					$as_codestpro3=substr($as_codestpro3,$li_longestpro3,$ls_loncodestpro3);
					$as_codestpro4=substr($as_codestpro4,$li_longestpro4,$ls_loncodestpro4);
					$as_codestpro5=substr($as_codestpro5,$li_longestpro5,$ls_loncodestpro5);
					$as_denestpro1=$row["denestpro1"];
					$as_denestpro2=$row["denestpro2"];
					$as_denestpro3=$row["denestpro3"];
					$as_denestpro4=$row["denestpro4"];
					$as_denestpro5=$row["denestpro5"];
				}
				$as_sigcon=$row["sigcon"];
				$as_glocon=$row["glocon"];
				$as_aplisrcon=$row["aplisrcon"];
				$as_sueintcon=$row["sueintcon"];
				$as_forpatcon=$row["forpatcon"];
				$as_cueprepatcon=$row["cueprepatcon"];
				$as_dencueprepat=$row["dencueprepat"];
				$as_cueconpatcon=$row["cueconpatcon"];
				$as_dencueconpat=$row["dencueconpat"];
				$as_titretempcon=$row["titretempcon"];
				$as_titretpatcon=$row["titretpatcon"];
				$ai_valminpatcon=$row["valminpatcon"];
				$ai_valminpatcon=$this->io_fun_nomina->uf_formatonumerico($ai_valminpatcon);
				$ai_valmaxpatcon=$row["valmaxpatcon"];
				$ai_valmaxpatcon=$this->io_fun_nomina->uf_formatonumerico($ai_valmaxpatcon);
				$ai_conprenom=$row["conprenom"];
				$ai_sueintvaccon=$row["sueintvaccon"];
				$as_aplarccon=$row["aplarccon"];
				$as_aplconprocon=$row["conprocon"];
				$as_intingcon=$row["intingcon"];
				$as_cueingcon=$row["spi_cuenta"];
				$as_dencueing=$row["dencueing"];
				$ai_poringcon=number_format($row["poringcon"],2,",",".");
				$ls_codprov=$row["codprov"];
				$ls_codben=$row["cedben"];
				$ls_proveedor=$row["proveedor"];
				$ls_beneficiario=$row["beneficiario"];
				$as_repacucon=$row["repacucon"];
				$as_repconsunicon=$row["repconsunicon"];
				$as_consunicon=$row["consunicon"];		
				$as_quirepcon=$row["quirepcon"];
				$as_perenc=$row["conperenc"];
				$as_aplresenc=$row["aplresenc"];			
				if($ls_codprov=="----------")
				{
					$as_descon="B";
					$as_coddescon=$ls_codben;
					$as_desdescon=$ls_beneficiario;
				}
				if($ls_codben=="----------")
				{
					$as_descon="P";
					$as_coddescon=$ls_codprov;
					$as_desdescon=$ls_proveedor;
				}
				$as_codente=$row["codente"];
				$as_txtente=$row["nombre_ente"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_concepto	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_conceptopersonal($as_codper,$as_codconc,&$aa_concepto)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_conceptopersonal
		//		   Access: public (sigesp_sno_c_evaluador)
		//	    Arguments: as_codper // código de personal
		//				   as_codconc // código del concepto
		//				   aa_concepto // Arreglo donde se colocan toda la información del concepto
		//	      Returns: lb_valido True si se obtuvo el concepto ó False si no se obtuvo
		//	  Description: función que dado el código de personal y concepto busca el concepto asociado al personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_concepto.glocon, sno_concepto.forcon, sno_conceptopersonal.aplcon ".
				"  FROM sno_conceptopersonal, sno_concepto ".
				" WHERE sno_conceptopersonal.codemp='".$this->ls_codemp."' ".
				"   AND sno_conceptopersonal.codnom='".$this->ls_codnom."' ".
				"   AND sno_conceptopersonal.codconc='".$as_codconc."' ".
				"   AND sno_conceptopersonal.codper='".$as_codper."' ".
				"   AND sno_conceptopersonal.codemp=sno_concepto.codemp ".
				"   AND sno_conceptopersonal.codnom=sno_concepto.codnom".
				"   AND sno_conceptopersonal.codconc=sno_concepto.codconc ";
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_obtener_conceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_total=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$aa_concepto["glocon"]=$row["glocon"];
				$aa_concepto["forcon"]=$row["forcon"];
				$aa_concepto["aplcon"]=$row["aplcon"];
				$li_total=$li_total+1;
			}
			if($li_total==0)
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_obtener_conceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_hconcepto($as_codconc,$as_codpro,$as_aplisrcon,$as_sueintcon,$as_intprocon,$as_cueprecon,$as_cueconcon,
								 $as_cueprepatcon,$as_cueconpatcon, $as_codprov,$as_estcla,$as_codben,$as_aplarccon,$as_sueintvaccon,
								 $as_aplconprocon,$as_intingcon,$as_cueingcon,$ai_poringcon,$as_asifidper,$as_asifidpat,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_hconcepto
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//				   as_codpro  // código de programática	
		//				   as_aplisrcon  // si aplica ISR
		//				   as_sueintcon  // si pertenece al sueldo integral				
		//				   as_intprocon  // si esta integrada a la programática
		//				   as_cueprecon  // cuenta presupuestaria de concepto
		//				   as_cueconcon  // cuenta contable de concepto
		//				   as_cueprepatcon  // cuenta presupuestaria de concepto para los aportes
		//				   as_cueconpatcon  // cuenta contable de concepto para los aportes
		//				   as_codprov  // código del proveedor
		//				   as_codben  // código del beneficiario
		//				   as_aplarccon  // aplica el concepto como arc
		//				   as_sueintvaccon  // aplica el concepto como sueldo integral de vacaciones
		//				   $as_aplconprocon // Contabilización por proyecto 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update del histórico ó False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto histórico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 10/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ai_poringcon=str_replace(".","",$ai_poringcon);				
		$ai_poringcon=str_replace(",",".",$ai_poringcon);				

		$ls_sql="UPDATE sno_hconcepto ".
				"   SET codpro='".$as_codpro."', ".
				"		aplisrcon='".$as_aplisrcon."', ".
				"		sueintcon='".$as_sueintcon."', ".
				"		intprocon='".$as_intprocon."', ".
				"		cueprecon='".$as_cueprecon."', ".
				"		cueconcon='".$as_cueconcon."', ".
				"		cueprepatcon='".$as_cueprepatcon."', ".
				"		cueconpatcon='".$as_cueconpatcon."', ".
				"		codprov='".$as_codprov."', ".
				"		cedben='".$as_codben."', ".
				"		aplarccon='".$as_aplarccon."', ".
				"		sueintvaccon='".$as_sueintvaccon."', ".
				"		conprocon='".$as_aplconprocon."', ".
				"       estcla='".$as_estcla."', ".
				"       intingcon='".$as_intingcon."', ".
				"       spi_cuenta='".$as_cueingcon."', ".
				"       poringcon=".$ai_poringcon.", ".
				"       asifidper='".$as_asifidper."', ".
				"       asifidpat='".$as_asifidpat."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND anocur='".$this->ls_anocurnom."' ".
				"   AND codperi='".$this->ls_codperi."' ".
				"   AND codconc='".$as_codconc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_update_hconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$ls_sql="UPDATE sno_thconcepto ".
					"   SET codpro='".$as_codpro."', ".
					"		aplisrcon='".$as_aplisrcon."', ".
					"		sueintcon='".$as_sueintcon."', ".
					"		intprocon='".$as_intprocon."', ".
					"		cueprecon='".$as_cueprecon."', ".
					"		cueconcon='".$as_cueconcon."', ".
					"		cueprepatcon='".$as_cueprepatcon."', ".
					"		cueconpatcon='".$as_cueconpatcon."', ".
					"		codprov='".$as_codprov."', ".
					"		cedben='".$as_codben."', ".
					"		aplarccon='".$as_aplarccon."', ".
					"		sueintvaccon='".$as_sueintvaccon."', ".
					"		conprocon='".$as_aplconprocon."', ".
					"       estcla='".$as_estcla."', ".
					"       intingcon='".$as_intingcon."', ".
					"       spi_cuenta='".$as_cueingcon."', ".
					"       poringcon=".$ai_poringcon.", ".
					"       asifidper='".$as_asifidper."', ".
					"       asifidpat='".$as_asifidpat."' ".
					" WHERE codemp='".$this->ls_codemp."' ".
					"   AND codnom='".$this->ls_codnom."' ".
					"   AND anocur='".$this->ls_anocurnom."' ".
					"   AND codperi='".$this->ls_codperi."' ".
					"   AND codconc='".$as_codconc."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_update_hconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el concepto histórico ".$as_codconc." asociado a la nómina ".$this->ls_codnom." Año ".$this->ls_anocurnom." ".
								 "Período ".$this->ls_codperi;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		if($lb_valido)
		{	
			$this->io_mensajes->message("El Concepto fue Actualizado.");
			$this->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$this->io_mensajes->message("El concepto no se actualizó");
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_update_hconcepto	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_islr()
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_islr
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si hay algún concepto marcado como islr
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/10/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND aplisrcon = 1 ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_select_islr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_islr	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_islr_historico()
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_islr_historico
		//		   Access: private
		//	    Arguments: 
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si hay algún concepto marcado como islr en los históricos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/02/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_hconcepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND anocur = '".$this->ls_anocurnom."' ".
				"   AND codperi = '".$this->ls_codperi."' ".
				"   AND aplisrcon = 1 ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_select_islr ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_islr_historico	
	//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_resumen_encargaduria()
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_resumen_encargaduria
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si hay algún concepto marcado como resumen de encargaduría
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 23/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_existe=false;
       	$ls_sql="SELECT codconc ".
				"  FROM sno_concepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND aplresenc = '1' ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_select_resumen_encargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
       	}
       	else
       	{
			if ($row=$this->io_sql->fetch_row($rs_data))
         	{
            	$lb_existe=true;
         	}
			$this->io_sql->free_result($rs_data);	
       	}
		return $lb_existe ;    
	}// end function uf_select_resumen_encargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_load_conceptoencargaduria(&$ai_totrows,&$ao_object)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptoencargaduria
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que selecciona en lote los conceptos que pertenecen a las encargadurias
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
       	$ls_sql="SELECT codconc, nomcon, conperenc ".
				"  FROM sno_concepto ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND aplresenc = '0' ".
				" ORDER BY codconc";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_load_conceptoencargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
       	}
       	else
       	{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codcon=$row["codconc"];
				$ls_nomcon=$row["nomcon"];			
				$li_aplcon=$row["conperenc"];
				if($li_aplcon=="1")
				{
					$ls_aplica="checked";
				}
				else
				{
					$ls_aplica="";
				}
				
				$ao_object[$ai_totrows][1]="<input name=txtcodcon".$ai_totrows." type=text id=txcodcon".$ai_totrows." value=".$ls_codcon." size=13 class=sin-borde readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtnomcon".$ai_totrows." type=text id=txtxtnomcon".$ai_totrows." value='".$ls_nomcon."' size=67 class=sin-borde readonly>";				
				$ao_object[$ai_totrows][3]="<input name=chkaplcon".$ai_totrows." type=checkbox id=chkaplcon".$ai_totrows." value='1' ".$ls_aplica.">";
				
			}
			$this->io_sql->free_result($rs_data);
       	}
		return $lb_valido;    
	}// end function uf_load_conceptoencargaduria
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar_conceptoencargaduria($as_codconc,$as_perenc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar_conceptoencargaduria
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto							
		//				   as_titcon  // Título										
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el concepto
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 24/12/2008 								Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sno_concepto ".
				"   SET  conperenc='".$as_perenc."' ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codconc='".$as_codconc."' ";
		$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Concepto MÉTODO->uf_guardar_conceptoencargaduria ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			
		}	
		return $lb_valido;
	}// end function uf_update_concepto	
		//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------

	function uf_consulta_entes($codente,$ente,$criterio){
				
				//////////////////////////////////////////////////////////////////////////////
				//	     Function: uf_consulta_entes
				//		   Access: private
				//	    Arguments: $codente  // código de ente
				//                 $ente     //Nombre del ente
				//                 $criterio  //Criterio de Busqueda 
				//	      Returns: Un arreglo con los Datos de la consulta
				//	  Description: Función que retorna los registros de una consulta de entes
				//	   Creado Por: Lic. Edgar A. Quintero
				// Fecha Creación: 25/01/2009								Fecha Última Modificación : 
				//////////////////////////////////////////////////////////////////////////////
				
				if($_SESSION["ls_gestor"] == 'POSTGRES'){$postgres_ilike = 'I';}
				
				switch($criterio){
						  
					  case "por_codigo":
							$sql_criterio = " WHERE codigo_ente='".$dato_buscar."' ORDER BY  codigo_ente";
							break;
									 
					   case "por_listado":
							$sql_criterio = " WHERE codigo_ente ".$postgres_ilike."LIKE('%".$codente."%') AND descripcion_ente ".$postgres_ilike."LIKE('%".$ente."%') ORDER BY codigo_ente";
							break;
				}
										   
				$query_rs = "SELECT * FROM sno_entes".$sql_criterio;
				return $this->io_conexiones->conexion($query_rs,'arreglo','<b>CLASE:</b> Concepto <br><b>METODO:</b> uf_consulta_entes');	
	
	
	}
//-----------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_valor_periodo($as_codconc,$as_codperi,$as_codper,$as_anocur)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_valor_periodo
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que selecciona concepto
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$as_monto=0;
       	$ls_sql="SELECT valsal ".
				"  FROM sno_hsalida ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND codper = '".$as_codper."' ".
				"   AND codconc = '".$as_codconc."' ".
				"   AND anocur = '".$as_anocur."' ".
				"   AND codperi = '".$as_codperi."' ";
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_buscar_valor_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
       	}
       	else
       	{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_monto=abs($row["valsal"]);
				
			}
			$this->io_sql->free_result($rs_data);
       	}
		return $as_monto;    
	}// end function uf_buscar_valor_periodo
	//-----------------------------------------------------------------------------------------------------------------------------------

    function uf_buscar_valor_acumulado_periodo($as_codper,$as_codconc,$as_criterio,&$as_monto)
    {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_valor_acumulado_periodo
		//		   Access: private
		//	    Arguments: as_codconc  // código de concepto
		//	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que selecciona concepto
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 27/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$as_monto=0;
       	$ls_sql="SELECT SUM(valsal) as valsal ".
				"  FROM sno_hsalida ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND codper = '".$as_codper."' ".
				"   AND codconc = '".$as_codconc."' ".$as_criterio;				
       	$rs_data=$this->io_sql->select($ls_sql);
       	if ($rs_data===false)
       	{
        	$this->io_mensajes->message("CLASE->Concepto Nómina MÉTODO->uf_buscar_valor_acumulado_periodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false; 
       	}
       	else
       	{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_monto=abs($row["valsal"]);
				
			}
			$this->io_sql->free_result($rs_data);
       	}
		return $as_monto;    
	}// end function uf_buscar_valor_acumulado_periodo
//-----------------------------------------------------------------------------------------------------------------------------------	
}
?>