<?php
class sigesp_rcm_c_spg
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_spg()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_spg
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 
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
	}// end function sigesp_rcm_c_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public 
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 
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
		//	   Creado Por: Ing. Néstor Falcón.
		//    Description: Funcion que se encarga de hacer el llamado a cada una de las sub-funciones que hacen la reconversion de
		//                 las tablas del modulo de contabilidad presupuestaria de gasto. 
		// Fecha Creación: 07/08/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spgcuentas();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spgdtcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spgdtmpcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spgplantillareporte();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SPG',$aa_seguridad);
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
	function uf_convertir_spgcuentas()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spgcuentas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spg_cuentas e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, asignado, precomprometido,
		                comprometido, causado, pagado, aumento, disminucion, 
		                enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre".
				"  FROM spg_cuentas".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp     = $row["codemp"]; 
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"]; 
				$ls_spgcta     = $row["spg_cuenta"];
				$ld_asignado   = $row["asignado"];
				$ld_precomprometido = $row["precomprometido"];
				$ld_comprometido = $row["comprometido"];
				$ld_causado = $row["causado"];
				$ld_pagado = $row["pagado"];
				$ld_aumento = $row["aumento"];
				$ld_disminucion = $row["disminucion"];
				$ld_enero = $row["enero"];
				$ld_febrero = $row["febrero"];
				$ld_marzo = $row["marzo"];
				$ld_abril = $row["abril"];
				$ld_mayo = $row["mayo"];
				$ld_junio = $row["junio"];
				$ld_julio = $row["julio"];
				$ld_agosto = $row["agosto"];
				$ld_septiembre = $row["septiembre"];
				$ld_octubre = $row["octubre"];
				$ld_noviembre = $row["noviembre"];
				$ld_diciembre = $row["diciembre"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_asignado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","precomprometidoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_precomprometido);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","comprometidoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_comprometido);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","causadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_causado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","pagadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_pagado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","aumentoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_aumento);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","disminucionaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_disminucion);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_enero);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_febrero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_marzo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_abril);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mayo);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_junio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_julio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_agosto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_septiembre);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_octubre);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_noviembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_diciembre);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_cuentas",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spgcuentas
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spgdtcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spgdtcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spg_dt_cmp e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, procede, comprobante, fecha, codban, ctaban, codestpro1, codestpro2, codestpro3, codestpro4, 
		                codestpro5, spg_cuenta, procede_doc, documento, operacion, monto".
				"  FROM spg_dt_cmp".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp      = $row["codemp"]; 
				$ls_procede     = $row["procede"]; 
				$ls_comprobante = $row["comprobante"]; 
				$ld_fecha 		= $row["fecha"];
				$ls_codban 		= $row["codban"];  
				$ls_ctaban 		= $row["ctaban"];  
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"]; 
				$ls_spgcta     = $row["spg_cuenta"];
				$ls_procededoc = $row["procede_doc"];
				$ls_documento = $row["documento"]; 
				$ls_operacion  = $row["operacion"];
				$ld_monto   = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
	
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ld_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spgdtcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spgdtmpcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spgdtmpcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spg_dtmp_cmp e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, procede, comprobante, fecha, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, 
		                spg_cuenta, procede_doc, documento, operacion, monto".
				"  FROM spg_dtmp_cmp".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp      = $row["codemp"]; 
				$ls_procede     = $row["procede"]; 
				$ls_comprobante = $row["comprobante"]; 
				$ld_fecha 		= $row["fecha"];
				$ls_codestpro1  = $row["codestpro1"];
				$ls_codestpro2  = $row["codestpro2"];
				$ls_codestpro3  = $row["codestpro3"];
				$ls_codestpro4  = $row["codestpro4"];
				$ls_codestpro5  = $row["codestpro5"]; 
				$ls_spgcta      = $row["spg_cuenta"];
				$ls_procededoc  = $row["procede_doc"];
				$ls_documento   = $row["documento"]; 
				$ls_operacion   = $row["operacion"];
				$ld_monto       = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
	
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ld_fecha);
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spgdtmpcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spgplantillareporte()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spgdtmpcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spg_plantillareporte e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codrep, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, asignado, precomprometido, 
		                comprometido, causado, pagado, aumento, disminucion, enero, febrero, marzo, abril, mayo, junio, 
						julio, agosto, septiembre, octubre, noviembre, diciembre".
				"  FROM spg_plantillareporte".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgplantillareporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp      = $row["codemp"]; 
				$ls_codrep      = $row["codrep"]; 
				$ls_codestpro1  = $row["codestpro1"];
				$ls_codestpro2  = $row["codestpro2"];
				$ls_codestpro3  = $row["codestpro3"];
				$ls_codestpro4  = $row["codestpro4"];
				$ls_codestpro5  = $row["codestpro5"]; 
				$ls_spgcta      = $row["spg_cuenta"];
				$ld_asignado   = $row["asignado"];
				$ld_precomprometido = $row["precomprometido"];
				$ld_comprometido = $row["comprometido"];
				$ld_causado = $row["causado"];
				$ld_pagado = $row["pagado"];
				$ld_aumento = $row["aumento"];
				$ld_disminucion = $row["disminucion"];
				$ld_enero = $row["enero"];
				$ld_febrero = $row["febrero"];
				$ld_marzo = $row["marzo"];
				$ld_abril = $row["abril"];
				$ld_mayo = $row["mayo"];
				$ld_junio = $row["junio"];
				$ld_julio = $row["julio"];
				$ld_agosto = $row["agosto"];
				$ld_septiembre = $row["septiembre"];
				$ld_octubre = $row["octubre"];
				$ld_noviembre = $row["noviembre"];
				$ld_diciembre = $row["diciembre"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_asignado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","precomprometidoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_precomprometido);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","comprometidoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_comprometido);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","causadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_causado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","pagadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_pagado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","aumentoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_aumento);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","disminucionaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_disminucion);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_enero);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_febrero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_marzo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_abril);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mayo);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_junio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_julio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_agosto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_septiembre);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_octubre);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_noviembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_diciembre);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codrep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codrep);
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_plantillareporte",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spgplantillareporte
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>