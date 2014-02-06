<?php
class sigesp_rcm_c_sep
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_sep()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_sef
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 06/08/2007
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
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 06/08/2007
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
		//    Description: Funcion que se encarga de hacer el llamado a cada una de las sub-funciones que hacen la reconversion de
		//                 las tablas del modulo de inventario. 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepconceptos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepcuentagasto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepdtarticulos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepdtconcepto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepdtservicio();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepdtacargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepdtccargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepdtscargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepsolicitud();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sepsolicitudcargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SEP',$aa_seguridad);
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
	function uf_convertir_sepconceptos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepconceptos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_cotizacion e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codconsep, monconsepe ".
				" FROM   sep_conceptos ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepconceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codconsep = $row["codconsep"]; 
				$ld_monconsepe = $row["monconsepe"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monconsepeaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monconsepe);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconsep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconsep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_conceptos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepconceptos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepcuentagasto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepcuentagasto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_cuentagasto e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codestpro1, codestpro2, codestpro3, ".
		        "        codestpro4, codestpro5, spg_cuenta, monto ".
				" FROM   sep_cuentagasto ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepcuentagasto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"];
				$ls_spgcuenta = $row["spg_cuenta"];
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_cuentagasto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepcuentagasto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepdtarticulos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepdtarticulos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dt_articulos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codart, monpre, monart ".
				" FROM   sep_dt_articulos ".
				" WHERE codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepdtarticulos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codart = $row["codart"];
				$ld_monpre = $row["monpre"];
				$ld_monart = $row["monart"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monpreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monpre);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monart);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_dt_articulos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepdtarticulos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepdtconcepto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepdtconcepto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dt_concepto e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codconsep, monpre, moncon ".
				" FROM   sep_dt_concepto ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepdtconcepto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codconsep = $row["codconsep"];
				$ld_monpre = $row["monpre"];
				$ld_moncon = $row["moncon"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monpreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monpre);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_moncon);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconsep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconsep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_dt_concepto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepdtconcepto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepdtservicio()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtservicio
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dt_servicio e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codser, monpre, monser ".
				" FROM   sep_dt_servicio ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepdtservicio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codser = $row["codser"];
				$ld_monpre = $row["monpre"];
				$ld_monser = $row["monser"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monpreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monpre);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monser);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_dt_servicio",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepdtservicio
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepdtacargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepdtacargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dta_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codart, codcar, monbasimp, monimp, monto ".
				" FROM   sep_dta_cargos ".
				" WHERE  codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepdtacargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codart = $row["codart"];
				$ls_codcar = $row["codcar"];
				$ld_monbasimp = $row["monbasimp"];
				$ld_monimp = $row["monimp"];
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monbasimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_dta_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepdtacargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepdtccargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepdtccargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dtc_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codconsep, codcar, monbasimp, monimp, monto ".
				" FROM   sep_dtc_cargos ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepdtccargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codconsep = $row["codconsep"];
				$ls_codcar = $row["codcar"];
				$ld_monbasimp = $row["monbasimp"];
				$ld_monimp = $row["monimp"];
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monbasimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codconsep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codconsep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_dtc_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepdtccargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepdtscargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepdtscargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dts_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codser, codcar, monbasimp, monimp, monto ".
				" FROM   sep_dts_cargos".
				" WHERE  codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepdtscargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codser = $row["codser"];
				$ls_codcar = $row["codcar"];
				$ld_monbasimp = $row["monbasimp"];
				$ld_monimp = $row["monimp"];
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monbasimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_dts_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepdtscargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepsolicitud()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepsolicitud
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dts_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, monto, monbasinm, montotcar ".
				" FROM   sep_solicitud ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepsolicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ld_monto = $row["monto"];
				$ld_monbasinm = $row["monbasinm"];
				$ld_montotcar = $row["montotcar"];  
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasinmaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monbasinm);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotcaraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_montotcar);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_solicitud",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepsolicitud
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sepsolicitudcargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sepsolicitudcargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sep_dts_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 06/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numsol, codcar, monobjret, monret, monto ".
				" FROM   sep_solicitudcargos ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_sep MÉTODO->SELECT->uf_convertir_sepsolicitudcargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numsol = $row["numsol"];
				$ls_codcar = $row["codcar"];
				$ld_monobjret = $row["monobjret"];
				$ld_monret = $row["monret"];
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monobjret);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monret);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sep_solicitudcargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sepsolicitudcargos
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>