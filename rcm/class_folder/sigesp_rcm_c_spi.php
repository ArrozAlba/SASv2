<?php
class sigesp_rcm_c_spi
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_spi()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_spi
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
	}// end function sigesp_rcm_c_spi
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
			$lb_valido=$this->uf_convertir_spicuentas();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spidtcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spidtmpcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_spiplantillacuentareporte();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SPI',$aa_seguridad);
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
	function uf_convertir_spicuentas()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spicuentas
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spi_cuentas e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, spi_cuenta, previsto, devengado, cobrado, cobrado_anticipado, aumento, disminucion,  
		                enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre".
				"  FROM spi_cuentas".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spicuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp     = $row["codemp"]; 
				$ls_spicta     = $row["spi_cuenta"];
				$ld_previsto   = $row["previsto"];
				$ld_devengado  = $row["devengado"];
				$ld_cobrado    = $row["cobrado"];
				$ld_cobant     = $row["cobrado_anticipado"];
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

				$this->io_rcbsf->io_ds_datos->insertRow("campo","previstoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_previsto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","devengadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_devengado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cobradoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cobrado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","cobrado_anticipadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cobant);

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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_cuentas",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spicuentas
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spidtcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spidtcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spi_dt_cmp e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, procede, comprobante, fecha, codban, ctaban, spi_cuenta, procede_doc, documento, operacion, monto".
				"  FROM spi_dt_cmp".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spidtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_spicta      = $row["spi_cuenta"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
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

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spidtcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spidtmpcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spidtmpcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spi_dtmp_cmp e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, procede, comprobante, fecha, spi_cuenta, procede_doc, documento, operacion, monto".
				"  FROM spi_dtmp_cmp".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spidtmpcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
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
				$ls_spicta      = $row["spi_cuenta"];
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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
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

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spidtmpcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spiplantillacuentareporte()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spiplantillacuentareporte
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla spi_plantillacuentareporte e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, cod_report, spi_cuenta, previsto, devengado, cobrado, cobrado_anticipado, aumento, 
		                disminucion, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre".
				"  FROM spi_plantillacuentareporte".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spiplantillacuentareporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp     = $row["codemp"]; 
				$ls_codrep     = $row["cod_report"]; 
				$ls_spicta     = $row["spi_cuenta"];
				$ld_previsto   = $row["previsto"];
				$ld_devengado  = $row["devengado"];
				$ld_cobrado    = $row["cobrado"];
				$ld_cobant     = $row["cobrado_anticipado"];
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

				$this->io_rcbsf->io_ds_datos->insertRow("campo","previstoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_previsto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","devengadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_devengado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cobradoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cobrado);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","cobrado_anticipadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cobant);

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
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_report");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codrep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_plantillacuentareporte",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_spiplantillacuentareporte
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>
