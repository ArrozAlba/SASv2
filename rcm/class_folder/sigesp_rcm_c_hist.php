<?php
class sigesp_rcm_c_hist
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_hist()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_soc
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por:Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
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
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
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
		//                 las tablas del modulo de Nómina en los históricos. 
		// Fecha Creación: 07/08/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohconceptopersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothconceptopersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohconceptovacacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothconceptovacacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohconstante();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothconstante();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohconstantepersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothconstantepersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohgrado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothgrado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohpersonalnomina();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothpersonalnomina();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohprenomina();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothprenomina();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohprestamos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothprestamos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohprestamosamortizado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothprestamosamortizado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohprestamosperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothprestamosperiodo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohprimaconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothprimaconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohprimagrado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothprimagrado();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohresumen();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothresumen();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohsalida();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothsalida();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snohvacacpersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_snothvacacpersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('HIST',$aa_seguridad);
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
	function uf_convertir_snohconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hconcepto e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, acumaxcon, valmincon, valmaxcon, valminpatcon, valmaxpatcon".
				"  FROM sno_hconcepto".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hconcepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thconcepto e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, acumaxcon, valmincon, valmaxcon, valminpatcon, valmaxpatcon".
				"  FROM sno_thconcepto".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thconcepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohconceptopersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohconceptopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hconceptopersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codconc, valcon, acuemp, acuiniemp, acupat, acuinipat ".
				"  FROM sno_hconceptopersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohconceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hconceptopersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohconceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothconceptopersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothconceptopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thconceptopersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codconc, valcon, acuemp, acuiniemp, acupat, acuinipat ".
				"  FROM sno_thconceptopersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothconceptopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{//, valconaux, acuempaux, acuiniempaux, acupataux, acuinipataux
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thconceptopersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothconceptopersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohconceptovacacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohconceptovacacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hconceptovacacion e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, acumaxsalvac, minsalvac, maxsalvac, minpatsalvac, maxpatsalvac,".
				" 		acumaxreivac, minreivac, maxreivac, minpatreivac, maxpatreivac ".
				"  FROM sno_hconceptovacacion".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohconceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hconceptovacacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohconceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothconceptovacacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothconceptovacacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thconceptovacacion e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, acumaxsalvac, minsalvac, maxsalvac, minpatsalvac, maxpatsalvac,".
				" 		acumaxreivac, minreivac, maxreivac, minpatreivac, maxpatreivac ".
				"  FROM sno_thconceptovacacion".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothconceptovacacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thconceptovacacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothconceptovacacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohconstante()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohconstante
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hconstante e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codcons, equcon, topcon, valcon".
				"  FROM sno_hconstante".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohconstante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcons");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcons);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hconstante",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohconstante
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothconstante()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothconstante
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thconstante e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codcons, equcon, topcon, valcon".
				"  FROM sno_thconstante ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothconstante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
								
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcons");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcons);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thconstante",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothconstante
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohconstantepersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohconstantepersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hconstantepersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codcons, moncon".
				"  FROM sno_hconstantepersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohconstantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcons");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcons);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hconstantepersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohconstantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothconstantepersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothconstantepersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thconstantepersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codcons, moncon ".
				"  FROM sno_thconstantepersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothconstantepersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcons");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcons);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thconstantepersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothconstantepersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohgrado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohgrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hgrado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, monsalgra, moncomgra".
				"  FROM sno_hgrado".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohgrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hgrado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohgrado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothgrado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothgrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thgrado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, monsalgra, moncomgra".
				"  FROM sno_thgrado".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothgrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thgrado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothgrado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hperiodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, totper ".
				"  FROM sno_hperiodo ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hperiodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thperiodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, totper ".
				"  FROM sno_thperiodo ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thperiodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohpersonalnomina()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohpersonalnomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hpersonalnomina e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, sueper, sueintper, sueproper ".
				"  FROM sno_hpersonalnomina ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohpersonalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hpersonalnomina",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohpersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothpersonalnomina()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothpersonalnomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thpersonalnomina e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, sueper, sueintper, sueproper ".
				"  FROM sno_thpersonalnomina ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothpersonalnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{  
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thpersonalnomina",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothpersonalnomina
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohprenomina()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohprenomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hprenomina e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis ".
				"  FROM sno_hprenomina ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hprenomina",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohprenomina
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothprenomina()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothprenomina
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thprenomina e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, codconc, tipprenom, valprenom, valhis ".
				"  FROM sno_thprenomina ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothprenomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thprenomina",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothprenomina
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohprestamos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohprestamos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hprestamos e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, monpre, monamopre ".
				"  FROM sno_hprestamos ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohprestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numpre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numpre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtippre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtippre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hprestamos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohprestamos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothprestamos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothprestamos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thprestamos e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, monpre, monamopre ".
				"  FROM sno_thprestamos ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothprestamos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numpre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numpre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtippre");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtippre);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thprestamos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothprestamos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohprestamosamortizado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohprestamosamortizado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hprestamosamortizado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, monamo ".
				"  FROM sno_hprestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohprestamosamortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hprestamosamortizado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohprestamosamortizado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothprestamosamortizado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothprestamosamortizado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thprestamosamortizado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, numpre, codtippre, anocur, codperi, numamo, monamo ".
				"  FROM sno_thprestamosamortizado ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothprestamosamortizado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thprestamosamortizado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothprestamosamortizado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohprestamosperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohprestamosperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hprestamosperiodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, moncuo ".
				"  FROM sno_hprestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohprestamosperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hprestamosperiodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohprestamosperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothprestamosperiodo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothprestamosperiodo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thprestamosperiodo e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, codper, anocur, codperi, numpre, codtippre, numcuo, moncuo ".
				"  FROM sno_thprestamosperiodo ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothprestamosperiodo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thprestamosperiodo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothprestamosperiodo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohprimaconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohprimaconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hprimaconcepto e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, anopri, valpri ".
				"  FROM sno_hprimaconcepto ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohprimaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
								
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anopri");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anopri);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hprimaconcepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohprimaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothprimaconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothprimaconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thprimaconcepto e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codconc, anopri, valpri ".
				"  FROM sno_thprimaconcepto ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothprimaconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{ 
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
								
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anopri");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anopri);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thprimaconcepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothprimaconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohprimagrado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohprimagrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hprimagrado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, codpri, monpri ".
				"  FROM sno_hprimagrado ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohprimagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
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
								
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hprimagrado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohprimagrado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothprimagrado()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothprimagrado
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thprimagrado e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codtab, codpas, codgra, codpri, monpri ".
				"  FROM sno_thprimagrado ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothprimagrado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
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
								
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thprimagrado",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothprimagrado
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohresumen()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohresumen
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hresumen e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres ".
				"  FROM sno_hresumen ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohresumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hresumen",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohresumen
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothresumen()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothresumen
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thresumen e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, asires, dedres, apoempres, apopatres, priquires, segquires, monnetres ".
				"  FROM sno_thresumen ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothresumen ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thresumen",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothresumen
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohsalida()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohsalida
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hsalida e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codconc, tipsal, valsal, monacusal, salsal ".
				"  FROM sno_hsalida ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohsalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
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

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hsalida",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohsalida
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothsalida()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothsalida
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thsalida e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codconc, tipsal, valsal, monacusal, salsal ".
				"  FROM sno_thsalida ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothsalida ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
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

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thsalida",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothsalida
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snohvacacpersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snohvacacpersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_hvacacpersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codvac, sueintbonvac, sueintvac, monto_1, monto_2, monto_3, monto_4, monto_5 ".
				"  FROM sno_hvacacpersonal ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snohvacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"];
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codvac");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codvac);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_hvacacpersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snohvacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_snothvacacpersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_snothvacacpersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sno_thvacacpersonal e inserta el valor convertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codnom, anocur, codperi, codper, codvac, sueintbonvac, sueintvac, monto_1, monto_2, monto_3, monto_4, monto_5 ".
				"  FROM sno_thvacacpersonal ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_hist MÉTODO->SELECT->uf_convertir_snothvacacpersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"];
				$ls_codnom= $row["codnom"];
				$ls_anocur= $row["anocur"];
				$ls_codperi= $row["codperi"];				
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codnom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codnom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","anocur");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_anocur);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codperi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codperi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codper");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codper);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codvac");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codvac);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","I");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sno_thvacacpersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_snothvacacpersonal
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>