<?php
class sigesp_rcm_c_scb
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_scb()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_scb
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
	}// end function sigesp_rcm_c_scb
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
			$lb_valido=$this->uf_convertir_scbcolocacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scbconciliacion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scbdtcmpret();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_dt_cmp_ret_op();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_dt_movbco();
		}				
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_errorconcbco();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movbco();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movbco_scg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movbco_spg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movbco_spgop();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movbco_spi();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movcaja();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movcol();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movcol_scg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movcol_spg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_scb_movcol_spi();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SCB',$aa_seguridad);
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
	function uf_convertir_scbcolocacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbcolocacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_colocacion e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 23/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numcol, monto, monint".
				"  FROM scb_colocacion".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scbcolocacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codban = $row["codban"];
				$ls_ctaban = $row["ctaban"];
				$ls_numcol = $row["numcol"];
				$ld_monto  = $row["monto"];
				$ld_monint = $row["monint"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monintaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monint);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_colocacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbcolocacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbconciliacion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbconciliacion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_conciliacion e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, mesano, salseglib, salsegbco, conciliacion".
				"  FROM scb_conciliacion".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scbconciliacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp 		 = $row["codemp"]; 
				$ls_codban 		 = $row["codban"];
				$ls_ctaban 		 = $row["ctaban"];
				$ls_mesano 		 = $row["mesano"];
				$ld_salseglib    = $row["salseglib"];
				$ld_salsegbco    = $row["salsegbco"];
				$ld_conciliacion = $row["conciliacion"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","salseglibaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_salseglib);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","salsegbcoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_salsegbco);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","conciliacionaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_conciliacion);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","mesano");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_mesano);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_conciliacion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbcolocacion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scbdtcmpret()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scbdtcmpret
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_dt_cmp_ret e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codret, numcom, numope, totcmp_sin_iva, totcmp_con_iva, basimp, porimp, totimp, iva_ret".
				"  FROM scb_dt_cmp_ret".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scbdtcmpret ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp 	  = $row["codemp"]; 
				$ls_codret 	  = $row["codret"];
				$ls_numcom 	  = $row["numcom"];
				$ls_numope 	  = $row["numope"];
				$ld_totsiniva = $row["totcmp_sin_iva"];
				$ld_totconiva = $row["totcmp_con_iva"];
				$ld_basimp    = $row["basimp"];
				$ld_porimp    = $row["porimp"];
				$ld_totimp    = $row["totimp"];
				$ld_ivaret    = $row["iva_ret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totcmp_sin_ivaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_totsiniva);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","totcmp_con_ivaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_totconiva);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","basimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_basimp);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_totimp);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","iva_retaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_ivaret);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codret");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codret);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_dt_cmp_ret",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scbdtcmpret
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_dt_cmp_ret_op()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_dt_cmp_ret_op
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_dt_cmp_ret_op e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codret, numcom, numope, totcmp_sin_iva, totcmp_con_iva, basimp, porimp, totimp, iva_ret".
				"  FROM scb_dt_cmp_ret_op".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_dt_cmp_ret_op ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp 	  = $row["codemp"]; 
				$ls_codret 	  = $row["codret"];
				$ls_numcom 	  = $row["numcom"];
				$ls_numope 	  = $row["numope"];
				$ld_totsiniva = $row["totcmp_sin_iva"];
				$ld_totconiva = $row["totcmp_con_iva"];
				$ld_basimp    = $row["basimp"];
				$ld_porimp    = $row["porimp"];
				$ld_totimp    = $row["totimp"];
				$ld_ivaret    = $row["iva_ret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totcmp_sin_ivaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_totsiniva);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","totcmp_con_ivaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_totconiva);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","basimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_basimp);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_totimp);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","iva_retaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_ivaret);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codret");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codret);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_dt_cmp_ret_op",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_dt_cmp_ret_op
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_dt_movbco()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_dt_movbco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_dt_movbco e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, numsolpag, monsolpag".
				"  FROM scb_dt_movbco".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_dt_movbco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp 	  = $row["codemp"]; 
				$ls_codban 	  = $row["codban"];
				$ls_ctaban 	  = $row["ctaban"];
				$ls_numdoc 	  = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estmov    = $row["estmov"];
				$ls_codpro    = $row["cod_pro"];
				$ls_cedben    = $row["ced_bene"];
				$ls_numsolpag = $row["numsolpag"];
				$ld_monsolpag = $row["monsolpag"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsolpagaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monsolpag);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","cod_pro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ced_bene");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_cedben);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numsolpag");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numsolpag);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_dt_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_dt_movbco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_errorconcbco()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_errorconcbco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_errorconcbco e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, fecmesano, monmov, monret".
				"  FROM scb_errorconcbco".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_errorconcbco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_fecmesano = $row["fecmesano"];
				$ld_monmov 	  = $row["monmov"];
				$ld_monret    = $row["monret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monmovaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monmov);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monret);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecmesano");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_fecmesano);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_errorconcbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_errorconcbco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movbco()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movbco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movbco e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, estmov, monto, monobjret, monret".
				"  FROM scb_movbco".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movbco ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estmov    = $row["estmov"];
				$ld_monto 	  = $row["monto"];
				$ld_monobjret = $row["monobjret"];
				$ld_monret    = $row["monret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monobjret);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monret);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movbco
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movbco_scg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movbco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movbco_scg e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, estmov, scg_cuenta, debhab, codded, documento, monto, monobjret".
				"  FROM scb_movbco_scg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movbco_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estmov    = $row["estmov"];
				$ls_scgcta    = $row["scg_cuenta"];
				$ls_debhab    = $row["debhab"];
				$ls_codded    = $row["codded"];
				$ls_documento = $row["documento"];
			    $ld_monto 	  = $row["monto"];
				$ld_monobjret = $row["monobjret"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","monobjretaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monobjret);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","scg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_scgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movbco_scg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movbco_spg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movbco
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movbco_spg e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, estmov, codestpro, spg_cuenta, documento, monto".
				"  FROM scb_movbco_spg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movbco_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estmov    = $row["estmov"];
				$ls_codestpro = $row["codestpro"];
				$ls_spgcta    = $row["spg_cuenta"];
				$ls_documento = $row["documento"];
			    $ld_monto 	  = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movbco_spg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movbco_spgop()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movbco_spgop
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movbco_spgop e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, estmov, codestpro, spg_cuenta, documento, monto, baseimp".
				"  FROM scb_movbco_spgop".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movbco_spgop ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estmov    = $row["estmov"];
				$ls_codestpro = $row["codestpro"];
				$ls_spgcta    = $row["spg_cuenta"];
				$ls_documento = $row["documento"];
			    $ld_monto 	  = $row["monto"];
				$ld_baseimp   = $row["baseimp"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","baseimpaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_baseimp);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spgop",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movbco_spgop
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movbco_spi()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movbco_spi
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movbco_spi e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numdoc, codope, estmov, spi_cuenta, documento, monto".
				"  FROM scb_movbco_spi".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movbco_spi ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estmov    = $row["estmov"];
				$ls_spicta    = $row["spi_cuenta"];
				$ls_documento = $row["documento"];
			    $ld_monto 	  = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
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

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movbco_spi",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movbco_spi
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movcaja()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movcaja
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movcaja e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codcaj, numdoc, tipopecaj, codopecaj, mone, monc, montot".
				"  FROM scb_movcaja".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movcaja ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codcaj    = $row["codcaj"];
				$ls_numdoc    = $row["numdoc"];
				$ls_tipopecaj = $row["tipopecaj"];
				$ls_codopecaj = $row["codopecaj"];
				$ld_mone      = $row["mone"];
				$ld_monc      = $row["monc"];
				$ld_montot    = $row["montot"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","moneaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mone);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","moncaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monc);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_montot);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcaj");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcaj);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

                $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","tipopecaj");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_tipopecaj);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codopecaj");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codopecaj);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcaja",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movcaja
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movcol()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movcol
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movcol e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numcol, numdoc, codope, estcol, monmovcol".
				"  FROM scb_movcol".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movcol ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numcol    = $row["numcol"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estcol    = $row["estcol"];
		        $ld_monmovcol = $row["monmovcol"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monmovcolaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monmovcol);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movcol
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movcol_scg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movcol_scg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movcol_scg e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numcol, numdoc, codope, estcol, sc_cuenta, debhab, codded, monto".
				"  FROM scb_movcol_scg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movcol_scg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codban = $row["codban"];
				$ls_ctaban = $row["ctaban"];
				$ls_numcol = $row["numcol"];
				$ls_numdoc = $row["numdoc"];
				$ls_codope = $row["codope"];
				$ls_estcol = $row["estcol"];
				$ls_scgcta = $row["sc_cuenta"];
				$ls_debhab = $row["debhab"];
				$ls_codded = $row["codded"];
			    $ld_monto  = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_scgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movcol_scg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movcol_spg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movcol_spg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movcol_spg e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numcol, numdoc, codope, estcol, codestpro, spg_cuenta, monto".
				"  FROM scb_movcol_spg".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movcol_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numcol    = $row["numcol"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estcol    = $row["estcol"];
				$ls_codestpro = $row["codestpro"];
				$ls_spgcta    = $row["spg_cuenta"];
			    $ld_monto     = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol_spg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movcol_spg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scb_movcol_spi()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_scb_movcol_spi
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scb_movcol_spi e inserta el valor convertido
		//	   Creado Por: Ing. Néstor Falcón
		// Fecha Creación: 08/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, codban, ctaban, numcol, numdoc, codope, estcol, spi_cuenta, monto".
				"  FROM scb_movcol_spi".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scb MÉTODO->SELECT->uf_convertir_scb_movcol_spg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp    = $row["codemp"]; 
				$ls_codban    = $row["codban"];
				$ls_ctaban    = $row["ctaban"];
				$ls_numcol    = $row["numcol"];
				$ls_numdoc    = $row["numdoc"];
				$ls_codope    = $row["codope"];
				$ls_estcol    = $row["estcol"];
				$ls_spicta    = $row["spi_cuenta"];
			    $ld_monto     = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
   			    $this->io_rcbsf->io_ds_filtro->insertRow("filtro","numcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codope");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codope);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcol");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcol);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spicta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scb_movcol_spi",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_scb_movcol_spi
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>