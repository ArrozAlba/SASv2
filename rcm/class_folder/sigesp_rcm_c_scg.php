<?php
class sigesp_rcm_c_scg
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_scg()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_scg
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
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
/*		$ld_fecha=date("Y_m_d_H_i");
		$ls_nombrearchivo="resultado/resultado_export".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");*/
	}// end function sigesp_rcm_c_scg
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public 
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
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
		//	   Creado Por: Ing. Yesenia Moreno
		//    Description: Funcion que se encarga de hacer el llamado a cada una de las sub-funciones que hacen la reconversion de
		//                 las tablas del modulo de Contabilidad. 
		// Fecha Creación: 06/08/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scgcuentas();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scgdtcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scgdtmpcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scgpcreporte();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scgpcreporteant();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scgsaldos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SCG',$aa_seguridad);
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
	function uf_convertir_scgcuentas()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scgcuentas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de la tabla scg_cuentas e inserta el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, sc_cuenta, asignado, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, ".
				"		noviembre, diciembre ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_sc_cuenta= $row["sc_cuenta"];
				$li_asignado= $row["asignado"];
				$li_enero= $row["enero"];
				$li_febrero= $row["febrero"];
				$li_marzo= $row["marzo"];
				$li_abril= $row["abril"];
				$li_mayo= $row["mayo"];
				$li_junio= $row["junio"];
				$li_julio= $row["julio"];
				$li_agosto= $row["agosto"];
				$li_septiembre= $row["septiembre"];
				$li_octubre= $row["octubre"];
				$li_noviembre= $row["noviembre"];
				$li_diciembre= $row["diciembre"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_asignado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_enero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_febrero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_marzo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_abril);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mayo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_junio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_julio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_agosto);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_septiembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_octubre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_noviembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_diciembre);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_cuentas",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgdtcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scgdtcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de la tabla scg_dt_cmp e inserta el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, procede, comprobante, fecha, codban, ctaban, sc_cuenta, procede_doc, documento, debhab, monto ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_procede= $row["procede"];
				$ls_comprobante= $row["comprobante"];
				$ls_fecha= $row["fecha"];
				$ls_codban= $row["codban"];
				$ls_ctaban= $row["ctaban"];
				$ls_sc_cuenta= $row["sc_cuenta"];
				$ls_procede_doc= $row["procede_doc"];
				$ls_documento= $row["documento"];
				$ls_debhab= $row["debhab"];
				$li_monto= $row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scgdtcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgdtmpcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scgdtmpcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de la tabla scg_dtmp_cmp e inserta el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, procede, comprobante, fecha, sc_cuenta, procede_doc, documento, debhab, monto ".
				"  FROM scg_dtmp_cmp ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgdtmpcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_procede= $row["procede"];
				$ls_comprobante= $row["comprobante"];
				$ls_fecha= $row["fecha"];
				$ls_sc_cuenta= $row["sc_cuenta"];
				$ls_procede_doc= $row["procede_doc"];
				$ls_documento= $row["documento"];
				$ls_debhab= $row["debhab"];
				$li_monto= $row["monto"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scgdtmpcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgpcreporte()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scgpcreporte
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de la tabla scg_pc_reporte e inserta el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, cod_report, sc_cuenta, asignado, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, ".
				"		noviembre, diciembre ".
				"  FROM scg_pc_reporte ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgpcreporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_cod_report= $row["cod_report"];
				$ls_sc_cuenta= $row["sc_cuenta"];
				$li_asignado= $row["asignado"];
				$li_enero= $row["enero"];
				$li_febrero= $row["febrero"];
				$li_marzo= $row["marzo"];
				$li_abril= $row["abril"];
				$li_mayo= $row["mayo"];
				$li_junio= $row["junio"];
				$li_julio= $row["julio"];
				$li_agosto= $row["agosto"];
				$li_septiembre= $row["septiembre"];
				$li_octubre= $row["octubre"];
				$li_noviembre= $row["noviembre"];
				$li_diciembre= $row["diciembre"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_asignado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_enero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_febrero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_marzo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_abril);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mayo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_junio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_julio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_agosto);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_septiembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_octubre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_noviembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_diciembre);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_report");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cod_report);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_pc_reporte",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scgpcreporte
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgpcreporteant()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scgpcreporteant
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de la tabla scg_pc_reporte_ant e inserta el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, cod_report, sc_cuenta, asignado, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, ".
				"		noviembre, diciembre ".
				"  FROM scg_pc_reporte_ant ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgpcreporteant ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_cod_report= $row["cod_report"];
				$ls_sc_cuenta= $row["sc_cuenta"];
				$li_asignado= $row["asignado"];
				$li_enero= $row["enero"];
				$li_febrero= $row["febrero"];
				$li_marzo= $row["marzo"];
				$li_abril= $row["abril"];
				$li_mayo= $row["mayo"];
				$li_junio= $row["junio"];
				$li_julio= $row["julio"];
				$li_agosto= $row["agosto"];
				$li_septiembre= $row["septiembre"];
				$li_octubre= $row["octubre"];
				$li_noviembre= $row["noviembre"];
				$li_diciembre= $row["diciembre"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_asignado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_enero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_febrero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_marzo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_abril);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mayo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_junio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_julio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_agosto);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_septiembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_octubre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_noviembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_diciembre);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_report");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cod_report);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_pc_reporte_ant",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scgpcreporteant
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgsaldos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scgsaldos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de la tabla scg_saldos e inserta el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, sc_cuenta, fecsal, debe_mes, haber_mes ".
				"  FROM scg_saldos ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scg MÉTODO->SELECT->uf_convertir_scgsaldos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_sc_cuenta= $row["sc_cuenta"];
				$ls_fecsal= $row["fecsal"];
				$li_debe_mes= $row["debe_mes"];
				$li_haber_mes= $row["haber_mes"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","debe_mesaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_debe_mes);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","haber_mesaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_haber_mes);
				
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecsal");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_fecsal);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_saldos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scgsaldos
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>