<?php
class sigesp_rcm_c_sno
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_sno()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_soc
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por:Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql_origen=new class_sql($io_conexion);	
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->seguridad=   new sigesp_c_seguridad();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_rcm_c_soc
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public 
		//	  Description: Destructor de la Clase
		//	   Creado Por:Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_data($aa_seguridad)
	{	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_data
		//		   Access: public
		//     Argumentos: $aa_seguridad  //Arreglo de Seguridad
		//	   Creado Por:Ing. Yesenia Moreno
		//    Description: Funcion que se encarga de hacer el llamado a cada una de las sub-funciones que hacen la reconversion de
		//                 las tablas del modulo de Nómina. 
		// Fecha Creación: 06/08/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snocestaticket();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoclasificaciondocente();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoconceptopersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoconceptovacacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoconstante();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoconstantepersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snodtscg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snodtspg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snofideiperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snogrado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snopersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snopersonalnomina();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprenomina();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprestamos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprestamosamortizado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprestamosperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprimaconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprimagrado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoprogramacionreporte();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snoresumen();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snosalida();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snotrabajoanterior();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snovacacpersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SNO',$aa_seguridad);
		}
		if($lb_valido)
		{
			$this->io_sql_origen->commit();
		}
		else
		{
			$this->io_sql_origen->rollback();
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snocestaticket()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snocestaticket
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_cestaticket e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codcestic, moncestic".
				"  FROM sno_cestaticket".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snocestaticket ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codcestic= $row["codcestic"];
				$li_moncestic= $row["moncestic"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncesticaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncestic);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcestic");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcestic);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_cestaticket",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snocestaticket
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoclasificaciondocente()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoclasificaciondocente
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_clasificaciondocente e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codescdoc, codcladoc, suesupcladoc, suedircladoc, suedoccladoc".
				"  FROM sno_clasificaciondocente".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoclasificaciondocente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codescdoc= $row["codescdoc"];
				$ls_codcladoc= $row["codcladoc"];
				$li_suesupcladoc= $row["suesupcladoc"];
				$li_suedircladoc= $row["suedircladoc"];
				$li_suedoccladoc= $row["suedoccladoc"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","suesupcladocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_suesupcladoc);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","suedircladocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_suedircladoc);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","suedoccladocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_suedoccladoc);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codescdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codescdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcladoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcladoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_clasificaciondocente",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoclasificaciondocente
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_concepto e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codconc, acumaxcon, valmincon, valmaxcon, valminpatcon, valmaxpatcon".
				"  FROM sno_concepto".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codconc= $row["codconc"];
				$li_acumaxcon= $row["acumaxcon"];
				$li_valmincon= $row["valmincon"];
				$li_valmaxcon= $row["valmaxcon"];
				$li_valminpatcon= $row["valminpatcon"];
				$li_valmaxpatcon= $row["valmaxpatcon"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acumaxconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acumaxcon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valminconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valmincon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valmaxconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valmaxcon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valminpatconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valminpatcon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valmaxpatconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valmaxpatcon);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_concepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoconceptopersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoconceptopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_conceptopersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, codconc, valcon, acuemp, acuiniemp, acupat, acuinipat ".
				"  FROM sno_conceptopersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoconceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_codconc= $row["codconc"];
				$li_valcon= $row["valcon"];
				$li_acuemp= $row["acuemp"];
				$li_acuiniemp= $row["acuiniemp"];
				$li_acupat= $row["acupat"];
				$li_acuinipat= $row["acuinipat"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valcon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acuempaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acuemp);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acuiniempaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acuiniemp);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acupataux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acupat);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acuinipataux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acuinipat);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_conceptopersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoconceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoconceptovacacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoconceptovacacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_conceptovacacion e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codconc, acumaxsalvac, minsalvac, maxsalvac, minpatsalvac, maxpatsalvac,".
				" 		acumaxreivac, minreivac, maxreivac, minpatreivac, maxpatreivac ".
				"  FROM sno_conceptovacacion".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoconceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codconc= $row["codconc"];
				$li_acumaxsalvac= $row["acumaxsalvac"];
				$li_minsalvac= $row["minsalvac"];
				$li_maxsalvac= $row["maxsalvac"];
				$li_minpatsalvac= $row["minpatsalvac"];
				$li_maxpatsalvac= $row["maxpatsalvac"];
				$li_acumaxreivac= $row["acumaxreivac"];
				$li_minreivac= $row["minreivac"];
				$li_maxreivac= $row["maxreivac"];
				$li_minpatreivac= $row["minpatreivac"];
				$li_maxpatreivac= $row["maxpatreivac"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acumaxsalvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acumaxsalvac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","minsalvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_minsalvac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","maxsalvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_maxsalvac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","minpatsalvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_minpatsalvac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","maxpatsalvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_maxpatsalvac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","acumaxreivacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_acumaxreivac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","minreivacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_minreivac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","maxreivacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_maxreivac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","minpatreivacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_minpatreivac);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","maxpatreivacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_maxpatreivac);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_conceptovacacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoconceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoconstante()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoconstante
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_constante e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codcons, equcon, topcon, valcon".
				"  FROM sno_constante".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoconstante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codcons= $row["codcons"];
				$li_equcon= $row["equcon"];
				$li_topcon= $row["topcon"];
				$li_valcon= $row["valcon"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","equconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_equcon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","topconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_topcon);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valcon);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcons");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcons);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_constante",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoconstante
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoconstantepersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoconstantepersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_constantepersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, codcons, moncon".
				"  FROM sno_constantepersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoconstantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_codcons= $row["codcons"];
				$li_moncon= $row["moncon"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncon);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcons");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcons);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_constantepersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoconstantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snodtscg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snodtscg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_dt_scg e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codperi, codcom, tipnom, sc_cuenta, debhab, codconc, monto".
				"  FROM sno_dt_scg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snodtscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codperi= $row["codperi"];
				$ls_codcom= $row["codcom"];
				$ls_tipnom= $row["tipnom"];
				$ls_sccuenta= $row["sc_cuenta"];
				$ls_debhab= $row["debhab"];
				$ls_codconc= $row["codconc"];
				$li_monto= $row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","tipnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sccuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_dt_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snodtscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snodtspg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snodtspg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_dt_spg e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codperi, codcom, tipnom, codestpro1, codestpro2, codestpro3, codestpro4,".
				"		codestpro5, spg_cuenta, operacion, codconc, monto ".
				"  FROM sno_dt_spg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snodtspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codperi= $row["codperi"];
				$ls_codcom= $row["codcom"];
				$ls_tipnom= $row["tipnom"];
				$ls_codestpro1= $row["codestpro1"];
				$ls_codestpro2= $row["codestpro2"];
				$ls_codestpro3= $row["codestpro3"];
				$ls_codestpro4= $row["codestpro4"];
				$ls_codestpro5= $row["codestpro5"];
				$ls_spgcuenta= $row["spg_cuenta"];
				$ls_operacion= $row["operacion"];
				$ls_codconc= $row["codconc"];
				$li_monto= $row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","tipnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_dt_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snodtspg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snofideiperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snofideiperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_fideiperiodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocurper, mescurper, bonvacper, bonfinper, sueintper, apoper, bonextper ".
				"  FROM sno_fideiperiodo".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snofideiperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_anocurper= $row["anocurper"];
				$ls_mescurper= $row["mescurper"];
				$li_bonvacper= $row["bonvacper"];
				$li_bonfinper= $row["bonfinper"];
				$li_sueintper= $row["sueintper"];
				$li_apoper= $row["apoper"];
				$li_bonextper= $row["bonextper"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","bonvacperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_bonvacper);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","bonfinperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_bonfinper);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","sueintperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_sueintper);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","apoperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_apoper);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","bonextperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_bonextper);
	
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocurper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocurper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","mescurper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_mescurper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_fideiperiodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snofideiperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snogrado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snogrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_grado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codtab, codpas, codgra, monsalgra, moncomgra".
				"  FROM sno_grado".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snogrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codtab= $row["codtab"];
				$ls_codpas= $row["codpas"];
				$ls_codgra= $row["codgra"];
				$li_monsalgra= $row["monsalgra"];
				$li_moncomgra= $row["moncomgra"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsalgraaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsalgra);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncomgraaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncomgra);
	
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codpas");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpas);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codgra");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codgra);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_grado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snogrado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_periodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codperi, totper ".
				"  FROM sno_periodo ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codperi= $row["codperi"];
				$li_totper= $row["totper"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_totper);
	
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_periodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snopersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_personal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codper, monpagvivper, ingbrumen ".
				"  FROM sno_personal ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codper= $row["codper"];
				$li_monpagvivper= $row["monpagvivper"];
				$li_ingbrumen= $row["ingbrumen"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monpagvivperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monpagvivper);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","ingbrumenaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_ingbrumen);
	
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_personal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snopersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snopersonalnomina()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snopersonalnomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_personal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, sueper, sueintper, sueproper ".
				"  FROM sno_personalnomina ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snopersonalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$li_sueper= $row["sueper"];
				$li_sueintper= $row["sueintper"];
				$li_sueproper= $row["sueproper"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","sueperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_sueper);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","sueintperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_sueintper);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","sueproperaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_sueproper);
	
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_personalnomina",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snopersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprenomina()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprenomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_prenomina e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, codperi, codconc, tipprenom, valprenom, valhis ".
				"  FROM sno_prenomina ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_codperi= $row["codperi"];
				$ls_codconc= $row["codconc"];
				$ls_tipprenom= $row["tipprenom"];
				$li_valprenom= $row["valprenom"];
				$li_valhis= $row["valhis"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valprenomaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valprenom);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","valhisaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valhis);
	
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","tipprenom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipprenom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_prenomina",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprenomina
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprestamos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprestamos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_prestamos e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, monpre, monamopre ".
				"  FROM sno_prestamos ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_numpre= $row["numpre"];
				$ls_codtippre= $row["codtippre"];
				$li_monpre= $row["monpre"];
				$li_monamopre= $row["monamopre"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monpreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monpre);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monamopreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monamopre);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numpre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numpre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtippre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtippre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_prestamos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprestamos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprestamosamortizado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprestamosamortizado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_prestamosamortizado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, numamo, monamo ".
				"  FROM sno_prestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprestamosamortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_numpre= $row["numpre"];
				$ls_codtippre= $row["codtippre"];
				$ls_numamo= $row["numamo"];
				$li_monamo= $row["monamo"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monamoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monamo);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numpre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numpre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtippre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtippre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numamo");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numamo);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_prestamosamortizado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprestamosamortizado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprestamosperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprestamosperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_prestamosperiodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, numcuo, moncuo ".
				"  FROM sno_prestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprestamosperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codper= $row["codper"];
				$ls_numpre= $row["numpre"];
				$ls_codtippre= $row["codtippre"];
				$ls_numcuo= $row["numcuo"];
				$li_moncuo= $row["moncuo"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncuoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncuo);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numpre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numpre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtippre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtippre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcuo");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcuo);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_prestamosperiodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprestamosperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprimaconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprimaconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_primaconcepto e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codconc, anopri, valpri ".
				"  FROM sno_primaconcepto ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprimaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codconc= $row["codconc"];
				$ls_anopri= $row["anopri"];
				$li_valpri= $row["valpri"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valpriaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valpri);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anopri");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anopri);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_primaconcepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprimaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprimagrado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprimagrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_primagrado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codtab, codpas, codgra, codpri, monpri ".
				"  FROM sno_primagrado ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprimagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codtab= $row["codtab"];
				$ls_codpas= $row["codpas"];
				$ls_codgra= $row["codgra"];
				$ls_codpri= $row["codpri"];
				$li_monpri= $row["monpri"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monpriaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monpri);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codpas");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpas);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codgra");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codgra);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codpri");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpri);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
								
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_primagrado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprimagrado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoprogramacionreporte()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoprogramacionreporte
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_programacionreporte e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codrep, codded, codtipper, totasi, monene, monfeb, monmar, monabr, monmay, monjun, monjul, monago, ".
				"		monsep, monoct, monnov, mondic ".
				"  FROM sno_programacionreporte ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoprogramacionreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codrep= $row["codrep"];
				$ls_codded= $row["codded"];
				$ls_codtipper= $row["codtipper"];
				$li_totasi= $row["totasi"];
				$li_monene= $row["monene"];
				$li_monfeb= $row["monfeb"];
				$li_monmar= $row["monmar"];
				$li_monabr= $row["monabr"];
				$li_monmay= $row["monmay"];
				$li_monjun= $row["monjun"];
				$li_monjul= $row["monjul"];
				$li_monago= $row["monago"];
				$li_monsep= $row["monsep"];
				$li_monoct= $row["monoct"];
				$li_monnov= $row["monnov"];
				$li_mondic= $row["mondic"];
   				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totasiaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_totasi);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","moneneaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monene);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monfebaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monfeb);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monmaraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monmar);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monabraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monabr);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monmayaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monmay);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monjunaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monjun);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monjulaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monjul);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monagoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monago);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsepaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsep);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monoctaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monoct);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monnovaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monnov);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondicaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mondic);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codrep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codrep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
								
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_programacionreporte",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoprogramacionreporte
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snoresumen()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snoresumen
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_resumen e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codperi, codper, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres ".
				"  FROM sno_resumen ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snoresumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codperi= $row["codperi"];
				$ls_codper= $row["codper"];
				$li_asires= $row["asires"];
				$li_dedres= $row["dedres"];
				$li_apoempres= $row["apoempres"];
				$li_apopatres= $row["apopatres"];
				$li_priquires= $row["priquires"];
				$li_segquires= $row["segquires"];
				$li_monnetres= $row["monnetres"];
   				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","asiresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_asires);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","dedresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_dedres);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","apoempresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_apoempres);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","apopatresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_apopatres);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","priquiresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_priquires);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","segquiresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_segquires);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monnetresaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monnetres);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_resumen",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snoresumen
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snosalida()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snosalida
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_salida e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codperi, codper, codconc, tipsal, valsal, monacusal, salsal ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snosalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_codperi= $row["codperi"];
				$ls_codper= $row["codper"];
				$ls_codconc= $row["codconc"];
				$ls_tipsal= $row["tipsal"];
				$li_valsal= $row["valsal"];
				$li_monacusal= $row["monacusal"];
				$li_salsal= $row["salsal"];
   				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","valsalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_valsal);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monacusalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monacusal);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","salsalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_salsal);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","tipsal");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipsal);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_salida",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snosalida
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snotrabajoanterior()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snotrabajoanterior
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_trabajoanterior e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codper, codtraant, ultsuetraant ".
				"  FROM sno_trabajoanterior ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snotrabajoanterior ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ //
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codper= $row["codper"];
				$ls_codtraant= $row["codtraant"];
				$li_ultsuetraant= $row["ultsuetraant"];
   				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","ultsuetraantaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_ultsuetraant);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtraant");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtraant);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_trabajoanterior",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snotrabajoanterior
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snovacacpersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snovacacpersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_vacacpersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codper, codvac, sueintbonvac, sueintvac, monto_1, monto_2, monto_3, monto_4, monto_5 ".
				"  FROM sno_vacacpersonal ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sno MÉTODO->SELECT->uf_convertir_snovacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codper= $row["codper"];
				$ls_codvac= $row["codvac"];
				$li_sueintbonvac= $row["sueintbonvac"];
				$li_sueintvac= $row["sueintvac"];
				$li_monto_1= $row["monto_1"];
				$li_monto_2= $row["monto_2"];
				$li_monto_3= $row["monto_3"];
				$li_monto_4= $row["monto_4"];
				$li_monto_5= $row["monto_5"];
   				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","sueintbonvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_sueintbonvac);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","sueintvacaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_sueintvac);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monto_1aux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto_1);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monto_2aux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto_2);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monto_3aux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto_3);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monto_4aux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto_4);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monto_5aux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto_5);

				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codvac");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codvac);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_vacacpersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snovacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>