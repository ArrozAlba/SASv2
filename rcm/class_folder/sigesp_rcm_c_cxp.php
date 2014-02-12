<?php
class sigesp_rcm_c_cxp
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_cxp()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_cxp
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
/*		$ld_fecha=date("Y_m_d_H_i");
		$ls_nombrearchivo="resultado/resultado_export".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");*/
	}// end function sigesp_rcm_c_cxp
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
		// Fecha Creación: 19/07/2007 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxpdcscg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxpdcspg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxpdtsolicitudes();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxprd();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxprdcargos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxprddeducciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxprdscg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxprdspg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxprddeducciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxpsolbanco();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxpsoldc();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_cxpsolicitudes();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('CXP',$aa_seguridad);
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
	function uf_convertir_cxpdcscg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpdcscg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_dc_scg e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, debhab, sc_cuenta, monto".
				"  FROM cxp_dc_scg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cxp MÉTODO->SELECT->uf_convertir_cxpdcscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numsol= $row["numsol"];
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_codope= $row["codope"];
				$ls_numdc= $row["numdc"];
				$ls_debhab= $row["debhab"];
				$ls_sccuenta= $row["sc_cuenta"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sccuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_dc_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpdcscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxpdcspg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpdcspg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scv_dt_personal e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, codestpro, spg_cuenta, monto".
				"  FROM cxp_dc_spg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cxp MÉTODO->SELECT->uf_convertir_cxpdcspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numsol= $row["numsol"];
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_codope= $row["codope"];
				$ls_numdc= $row["numdc"];
				$ls_codestpro= $row["codestpro"];
				$ls_spgcuenta= $row["spg_cuenta"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_dc_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpdcspg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxpdtsolicitudes()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpdtsolicitudes
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_dt_solicitudes e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, monto".
				"  FROM cxp_dt_solicitudes".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cxp MÉTODO->SELECT->uf_convertir_cxpdtsolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numsol= $row["numsol"];
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_dt_solicitudes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpdtsolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprd()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprd
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_rd e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numrecdoc, codtipdoc, ced_bene, cod_pro,  montotdoc, mondeddoc, moncardoc".
				"  FROM cxp_rd".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxprd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$li_montotdoc= $row["montotdoc"];
				$li_mondeddoc= $row["mondeddoc"];
				$li_moncardoc= $row["moncardoc"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotdocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_montotdoc);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondeddocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_mondeddoc);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncardocaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncardoc);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprd
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprdcargos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprdcargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_rd_cargos e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numrecdoc, codtipdoc, cod_pro, ced_bene, codcar, procede_doc, numdoccom, monobjret, monret".
				"  FROM cxp_rd_cargos".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxprdcargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_codcar= $row["codcar"];
				$ls_procededoc= $row["procede_doc"];
				$ls_numdoccom= $row["numdoccom"];
				$li_monobjret= $row["monobjret"];
				$li_monret= $row["monret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monobjret);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monret);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcar");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcar);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoccom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_cargos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprdcargos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprddeducciones()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprdcargos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_rd_deducciones e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numrecdoc, codtipdoc, cod_pro, ced_bene, codded, procede_doc, numdoccom, monobjret, monret".
				"  FROM cxp_rd_deducciones".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxprddeduccioness ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_codded= $row["codded"];
				$ls_procededoc= $row["procede_doc"];
				$ls_numdoccom= $row["numdoccom"];
				$li_monobjret= $row["monobjret"];
				$li_monret= $row["monret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monobjret);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monret);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoccom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_deducciones",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprddeducciones
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprdscg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprdscg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_rd_scg e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, debhab, sc_cuenta, monto".
				"  FROM cxp_rd_scg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxprdscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_procededoc= $row["procede_doc"];
				$ls_numdoccom= $row["numdoccom"];
				$ls_debhab= $row["debhab"];
				$ls_sccuenta= $row["sc_cuenta"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoccom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sccuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprdscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxprdspg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxprdspg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_rd_spg e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, procede_doc, numdoccom, codestpro, spg_cuenta, monto".
				"  FROM cxp_rd_spg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxprdspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_procededoc= $row["procede_doc"];
				$ls_numdoccom= $row["numdoccom"];
				$ls_codestpro= $row["codestpro"];
				$ls_spgcuenta= $row["spg_cuenta"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procededoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoccom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoccom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_rd_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxprdspg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxpsolbanco()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpsolbanco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_sol_banco e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numsol, codban, ctaban, numdoc, codope, estmov, monto".
				"  FROM cxp_sol_banco".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->uf_convertir_cxpsolbanco MÉTODO->SELECT->uf_convertir_cxprdspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numsol= $row["numsol"];
				$ls_codban= $row["codban"];
				$ls_numdoc= $row["numdoc"];
				$ls_codope= $row["codope"];
				$ls_estmov= $row["estmov"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_sol_banco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpsolbanco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxpsoldc()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpsoldc
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_sol_dc e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numsol, numrecdoc, codtipdoc, ced_bene, cod_pro, codope, numdc, monto".
				"  FROM cxp_sol_dc".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxpsoldc ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numsol= $row["numsol"];
				$ls_numrecdoc= $row["numrecdoc"];
				$ls_codtipdoc= $row["codtipdoc"];
				$ls_cedbene= $row["ced_bene"];
				$ls_codpro= $row["cod_pro"];
				$ls_codope= $row["codope"];
				$ls_numdc= $row["numdc"];
				$li_monto= $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numrecdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numrecdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codtipdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codtipdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedbene);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_sol_dc",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpsoldc
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_cxpsolicitudes()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_cxpsolicitudes
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla cxp_solicitudes e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, numsol, monsol".
				"  FROM cxp_solicitudes".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_cxpsolicitudes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_numsol= $row["numsol"];
				$li_monsol= $row["monsol"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsolaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monsol);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("cxp_solicitudes",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_cxpsolicitudes
	//-----------------------------------------------------------------------------------------------------------------------------

}
?>