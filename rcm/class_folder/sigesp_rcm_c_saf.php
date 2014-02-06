<?php
class sigesp_rcm_c_saf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_saf()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_saf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
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
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
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
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_safactivo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_safcontable();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_safdepreciacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_safdtmovimiento();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_safpartes();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SAF',$aa_seguridad);
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
	function uf_convertir_safactivo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_safactivo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla saf_activo e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, codact, costo, cossal, monordcom, moncobase ".
				" FROM   saf_activo ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_saf MÉTODO->SELECT->uf_convertir_safactivo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codact = $row["codact"];
				$ld_costo = $row["costo"];
				$ld_cossal = $row["cossal"];
				$ld_monordcom = $row["monordcom"];
				$ld_moncobase = $row["moncobase"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","costoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_costo);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cossalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cossal);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monordcomaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monordcom);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncobaseaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_moncobase);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("saf_activo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_safactivo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_safcontable()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_safcontable
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla saf_contable e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, cmpmov, codcau, feccmp, codact, ideact, ".
                "        sc_cuenta, documento, monto ".
				" FROM   saf_contable ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_saf MÉTODO->SELECT->uf_convertir_safcontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_cmpmov = $row["cmpmov"];
				$ls_codcau = $row["codcau"]; 
				$ldt_feccmp = $row["feccmp"];
				$ls_codact = $row["codact"];
				$ls_ideact = $row["ideact"];
				$ls_sc_cuenta = $row["sc_cuenta"];
				$ls_documento = $row["documento"];
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cmpmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cmpmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcau");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcau);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","feccmp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_feccmp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ideact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ideact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("saf_contable",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_safcontable
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_safdepreciacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_safdepreciacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla saf_depreciacion e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, codact, ideact, fecdep, mondepmen, mondepano, mondepacu ".
				" FROM   saf_depreciacion ".
				" WHERE codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_saf MÉTODO->SELECT->uf_convertir_safdepreciacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codact = $row["codact"];
				$ls_ideact = $row["ideact"];
				$ldt_fecdep = $row["fecdep"];
				$ld_mondepmen = $row["mondepmen"];
				$ld_mondepano = $row["mondepano"];
				$ld_mondepacu = $row["mondepacu"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondepmenaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mondepmen);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondepanoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mondepano);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondepacuaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mondepacu);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ideact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ideact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecdep");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecdep);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("saf_depreciacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_safdepreciacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_safdtmovimiento()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_safdtmovimiento
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla saf_dt_movimiento e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, cmpmov, codcau, feccmp, codact, ideact, monact ".
				" FROM   saf_dt_movimiento ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_saf MÉTODO->SELECT->uf_convertir_safdtmovimiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_cmpmov = $row["cmpmov"];
				$ls_codcau = $row["codcau"];
				$ldt_feccmp = $row["feccmp"];
				$ls_codact = $row["codact"];
				$ls_ideact = $row["ideact"];
				$ld_monact = $row["monact"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monactaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monact);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cmpmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cmpmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcau");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcau);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","feccmp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_feccmp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ideact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ideact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");


				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("saf_dt_movimiento",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_safdtmovimiento
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_safpartes()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_safpartes
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla saf_partes e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 03/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, codact, ideact, codpar, monto, cossal ".
				" FROM   saf_partes ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_saf MÉTODO->SELECT->uf_convertir_safpartes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codact = $row["codact"];
				$ls_ideact = $row["ideact"];
				$ls_codpar = $row["codpar"];
				$ld_monto  = $row["monto"];
				$ld_cossal = $row["cossal"];
								
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cossalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cossal);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ideact");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ideact);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codpar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("saf_partes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_safpartes
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>