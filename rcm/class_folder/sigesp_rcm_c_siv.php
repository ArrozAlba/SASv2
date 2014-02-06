<?php
class sigesp_rcm_c_siv
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_siv()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_siv
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 07/08/2007
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
	}// end function sigesp_rcm_c_siv
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
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 07/08/2007
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
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql_origen->begin_transaction();
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sivarticulo();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sivdtdespacho();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sivdtmovimiento();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sivdtrecepcion();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sivdtscg();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sivdttransferencia();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('SIV',$aa_seguridad);
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
	function uf_convertir_sivarticulo()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sivarticulo
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla siv_articulo e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, codart, prearta, preartb, ".
		        "        preartc, preartd, ultcosart, cosproart ".
				" FROM   siv_articulo   ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_siv MÉTODO->SELECT->uf_convertir_sivarticulo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codart = $row["codart"];
				$ld_prearta = $row["prearta"]; 
				$ld_preartb = $row["preartb"];
				$ld_preartc = $row["preartc"];
				$ld_preartd = $row["preartd"];
				$ld_ultcosart = $row["ultcosart"];
				$ld_cosproart = $row["cosproart"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","preartaaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_prearta);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preartbaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_preartb);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preartcaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_preartc);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","preartdaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_preartd);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","ultcosartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_ultcosart);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cosproartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cosproart);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_articulo",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sivarticulo
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sivdtdespacho()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sivdtdespacho
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla siv_dt_despacho e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numorddes, numreg, codart, ".
		        "        codalm, preuniart, monsubart, montotart ".
				" FROM   siv_dt_despacho ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_siv MÉTODO->SELECT->uf_convertir_sivdtdespacho ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numorddes = $row["numorddes"];
				$ls_numreg = $row["numreg"];
				$ls_codart = $row["codart"];
				$ls_codalm = $row["codalm"];
				$ld_preuniart = $row["preuniart"];
				$ld_monsubart = $row["monsubart"];
				$ld_montotart = $row["montotart"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preuniartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_preuniart);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monsubart);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_montotart);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numorddes");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numorddes);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numreg");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numreg);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codalm");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codalm);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_despacho",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sivdtdespacho
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sivdtmovimiento()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sivdtmovimiento
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla siv_dt_movimiento e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, nummov, fecmov, codart, codalm, ".
		        "        opeinv, codprodoc, numdoc, cosart ".
				" FROM   siv_dt_movimiento ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_siv MÉTODO->SELECT->uf_convertir_sivdtmovimiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_nummov = $row["nummov"];
				$ldt_fecmov = $row["fecmov"];
				$ls_codart = $row["codart"];
				$ls_codalm = $row["codalm"];
				$ls_opeinv = $row["opeinv"];
				$ls_codprodoc = $row["codprodoc"];
				$ls_numdoc = $row["numdoc"];
				$ld_cosart = $row["cosart"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cosartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cosart);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","nummov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nummov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecmov");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecmov);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codalm");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codalm);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","opeinv");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_opeinv);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codprodoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codprodoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numdoc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numdoc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_movimiento",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sivdtmovimiento
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sivdtrecepcion()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sivdtrecepcion
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla siv_dt_recepcion e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numordcom, numconrec, codart, ".
		        "        preuniart, monsubart, montotart ".
				" FROM   siv_dt_recepcion ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_siv MÉTODO->SELECT->uf_convertir_sivdtrecepcion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numordcom = $row["numordcom"];
				$ls_numconrec = $row["numconrec"];
				$ls_codart = $row["codart"];
				$ld_preuniart = $row["preuniart"];
				$ld_monsubart = $row["monsubart"];
				$ld_montotart = $row["montotart"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","preuniartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_preuniart);
	
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monsubartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monsubart);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montotartaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_montotart);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numordcom");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numordcom);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numconrec");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numconrec);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_recepcion",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sivdtrecepcion
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sivdtscg()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sivdtscg
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla siv_dt_scg e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codart, codcmp, feccmp, ".
		        "        sc_cuenta, debhab, codemp, monto ".
				" FROM   siv_dt_scg  ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_siv MÉTODO->SELECT->uf_convertir_sivdtscg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codart = $row["codart"];
				$ls_codcmp = $row["codcmp"];
				$ldt_feccmp = $row["feccmp"];
				$ls_sc_cuenta = $row["sc_cuenta"];
				$ls_debhab = $row["debhab"];
				$ls_codemp = $row["codemp"]; 
				$ld_monto = $row["monto"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codcmp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codcmp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","feccmp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_feccmp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_sc_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_debhab);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_scg",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sivdtscg
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sivdttransferencia()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sivdttransferencia
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla siv_dt_transferencia e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, numtra, fecemi, codart, cosuni, costot ".
				" FROM   siv_dt_transferencia  ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_siv MÉTODO->SELECT->uf_convertir_sivdttransferencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_numtra = $row["numtra"];
				$ldt_fecemi = $row["fecemi"];
				$ls_codart = $row["codart"];
				$ld_cosuni = $row["cosuni"];
				$ld_costot = $row["costot"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cosuniaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_cosuni);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","costotaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_costot);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","numtra");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_numtra);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecemi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecemi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codart");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codart);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("siv_dt_transferencia",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sivdttransferencia
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>