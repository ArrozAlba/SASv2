<?php
class sigesp_rcm_c_soc
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_soc()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_soc
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
		//	   Creado Por: Ing. Luis Anibal Lang
		//    Description: Funcion que se encarga de hacer el llamado a cada una de las sub-funciones que hacen la reconversion de
		//                 las tablas del modulo de inventario. 
		// Fecha Creación: 26/07/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_soccotizacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_soccuentagasto();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtbienes();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtcomac();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtservicio();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtacargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtcotbienes();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtcotservicio();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socdtscargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sococdeducciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socordencompra();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socservicios();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_socsolicitudcargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SOC',$aa_seguridad);
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
	function uf_convertir_soccotizacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_soccotizacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_cotizacion e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numcot, cod_pro, monsubtot, monimpcot, mondes, montotcot".
				"  FROM soc_cotizacion".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_soc MÉTODO->SELECT->uf_convertir_soccotizacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numcot= $row["numcot"];
				$ls_codpro= $row["cod_pro"];
				$li_monsubtot= $row["monsubtot"];
				$li_monimpcot= $row["monimpcot"];
				$li_mondes= $row["mondes"];
				$li_montotcot= $row["montotcot"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubtotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubtot);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpcotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monimpcot);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondesaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mondes);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotcotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotcot);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcot");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcot);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_cotizacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_soccotizacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_soccuentagasto()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_soccuentagasto
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_cuentagasto e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codestpro1, codestpro2, codestpro3,".
				"       codestpro4, codestpro5, spg_cuenta, monto".
				"  FROM soc_cuentagasto".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_soc MÉTODO->SELECT->uf_convertir_soccuentagasto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$ls_codestpro1= $row["codestpro1"];
				$ls_codestpro2= $row["codestpro2"];
				$ls_codestpro3= $row["codestpro3"];
				$ls_codestpro4= $row["codestpro4"];
				$ls_codestpro5= $row["codestpro5"];
				$ls_spgcuenta= $row["spg_cuenta"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
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
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_cuentagasto",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_soccuentagasto
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtbienes()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtbienes
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dt_bienes e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codart, preuniart, monsubart, montotart".
				"  FROM soc_dt_bienes".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_soc MÉTODO->SELECT->uf_convertir_socdtbienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$ls_codart= $row["codart"];
				$li_preuniart= $row["preuniart"];
				$li_monsubart= $row["monsubart"];
				$li_montotart= $row["montotart"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preuniartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preuniart);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubart);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotart);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dt_bienes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtbienes
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtcomac()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtcomac
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dt_com_ac e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numanacot, cod_pro, codartser, preproa, preprob, preproc, preprod".
				"  FROM soc_dt_com_ac".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_socdtcomac ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numanacot= $row["numanacot"];
				$ls_codpro= $row["cod_pro"];
				$ls_codartser= $row["codartser"];
				$li_preproa= $row["preproa"];
				$li_preprob= $row["preprob"];
				$li_preproc= $row["preproc"];
				$li_preprod= $row["preprod"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preproaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preproa);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preprobaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preprob);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preprocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preproc);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preprodaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preprod);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numanacot");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numanacot);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codartser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codartser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dt_com_ac",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtcomac
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtservicio()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtservicio
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dt_servicio e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codser, monuniser, monsubser, montotser".
				"  FROM soc_dt_servicio".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_soc MÉTODO->SELECT->uf_convertir_socdtservicio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$ls_codser= $row["codser"];
				$li_monuniser= $row["monuniser"];
				$li_monsubser= $row["monsubser"];
				$li_montotser= $row["montotser"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monuniseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monuniser);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubser);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotser);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dt_servicio",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtservicio
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtacargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtacargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dta_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codart, codcar, monbasimp, monimp, monto".
				"  FROM soc_dta_cargos".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_soc MÉTODO->SELECT->uf_convertir_socdtacargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$ls_codart= $row["codart"];
				$ls_codcar= $row["codcar"];
				$li_monbasimp= $row["monbasimp"];
				$li_monimp= $row["monimp"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monbasimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monimp);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dta_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtacargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtcotbienes()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtcotbienes
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dtcot_bienes e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numcot, cod_pro, codart, preuniart, moniva, monsubart, montotart".
				"  FROM soc_dtcot_bienes".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_socdtcotbienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numcot= $row["numcot"];
				$ls_codpro= $row["cod_pro"];
				$ls_codart= $row["codart"];
				$li_preuniart= $row["preuniart"];
				$li_moniva= $row["moniva"];
				$li_monsubart= $row["monsubart"];
				$li_montotart= $row["montotart"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preuniartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preuniart);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monivaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moniva);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubart);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotart);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcot");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcot);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dtcot_bienes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtcotbienes
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtcotservicio()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_soc_dtcot_servicio
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dtcot_servicio e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numcot, cod_pro, codser, monuniser, moniva, monsubser, montotser".
				"  FROM soc_dtcot_servicio".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_socdtcotservicio ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numcot= $row["numcot"];
				$ls_codpro= $row["cod_pro"];
				$ls_codser= $row["codser"];
				$li_monuniser= $row["monuniser"];
				$li_moniva= $row["moniva"];
				$li_monsubser= $row["monsubser"];
				$li_montotser= $row["montotser"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monuniseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monuniser);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monivaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moniva);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubser);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotser);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcot");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcot);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dtcot_servicio",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtcotservicio
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socdtscargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socdtscargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_dts_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codser, codcar, monbasimp, monimp, monto".
				"  FROM soc_dts_cargos".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->uf_convertir_cxpsolbanco MÉTODO->SELECT->uf_convertir_socdtscargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$ls_codser= $row["codser"];
				$ls_codcar= $row["codcar"];
				$li_monbasimp= $row["monbasimp"];
				$li_monimp= $row["monimp"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monbasimp);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monimp);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_dts_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socdtscargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sococdeducciones()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sococdeducciones
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_oc_deducciones e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codded, monto".
				"  FROM soc_oc_deducciones".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_soc MÉTODO->SELECT->uf_convertir_sococdeducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$ls_codded= $row["codded"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_oc_deducciones",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sococdeducciones
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socordencompra()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socordencompra
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_ordencompra e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, monsegcom, monsubtotbie, monsubtotser, monsubtot, monbasimp,".
				"       monimp, mondes, montot, montotdiv, monant, tascamordcom".
				"  FROM soc_ordencompra".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_socordencompra ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"];
				$li_monsegcom= $row["monsegcom"];
				$li_monsubtotbie= $row["monsubtotbie"];
				$li_monsubtotser= $row["monsubtotser"];
				$li_monsubtot= $row["monsubtot"];
				$li_monbasimp= $row["monbasimp"];
				$li_monimp= $row["monimp"];
				$li_mondes= $row["mondes"];
				$li_montotdiv= $row["montotdiv"];
				$li_montot= $row["montot"];
				$li_monant= $row["monant"];
				$li_tascamordcom= $row["tascamordcom"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsegcomaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsegcom);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubtotbieaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubtotbie);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubtotseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubtotser);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubtotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsubtot);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monbasimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monbasimp);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monimp);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondesaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mondes);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotdivaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotdiv);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montot);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monantaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monant);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","tascamordcomaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_tascamordcom);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_ordencompra",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socordencompra
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socservicios()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socservicios
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_servicios e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codser, preser".
				"  FROM soc_servicios".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_socservicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_codser= $row["codser"];
				$li_preser= $row["preser"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preseraux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_preser);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codser");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codser);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_servicios",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socservicios
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_socsolicitudcargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_socsolicitudcargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla soc_servicios e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numordcom, estcondat, codcar, monobjret, monret,monto".
				"  FROM soc_solicitudcargos".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_socsolicitudcargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numordcom= $row["numordcom"];
				$ls_estcondat= $row["estcondat"]; 
				$ls_codcar= $row["codcar"];
				$li_monobjret= $row["monobjret"];
				$li_monret= $row["monret"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monobjret);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monret);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcondat");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcondat);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("soc_solicitudcargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_socsolicitudcargos
	//-----------------------------------------------------------------------------------------------------------------------------

}
?>