<?php 

class class_funciones_spi
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
//-----------------------------------------------------------------------------------------------------------------------------------
	function class_funciones_spi()
	{
		//////////////////////////////////////////////////////////////////////////////
		//Function: class_funciones_nomina
		// Access: public
		// Description: Constructor de la Clase
		//	   
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_spidtcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spidtcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT spi_cuenta, procede_doc, documento, operacion, monto".
				"  FROM spi_dt_cmp".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'"; ///print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_spidtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_spi_cuenta=$row["spi_cuenta"];
				$ls_procede_doc=$row["procede_doc"];
				$ls_documento=$row["documento"]; 
				$ls_operacion=$row["operacion"];
				$ld_monto=$row["monto"];
				/*// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spi_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			}
		}		
		///unset($this->io_rcbsf);
		return $lb_valido;
	}// end function uf_convertir_spidtcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT total ".
				"  FROM sigesp_cmp ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'"; ////print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->uf_convertir_sigespcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
			
				/*$li_total=$row["total"];
				// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_total);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			}
		}
		///unset($this->io_rcbsf);
		return $lb_valido;
	}// end function uf_convertir_sigespcmp
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_scgdtcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT sc_cuenta, procede_doc, documento, debhab, monto ".
				"  FROM scg_dt_cmp ".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->uf_convertir_scgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_sc_cuenta= $row["sc_cuenta"];
				$ls_procede_doc= $row["procede_doc"];
				$ls_documento= $row["documento"];
				$ls_debhab= $row["debhab"];
				$li_monto= $row["monto"];
				/*// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
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
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			}
		}
		///unset($this->io_rcbsf);
		return $lb_valido;
	}// end function uf_convertir_scgdtcmp
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_convertir_spgdtcmp($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spgdtcmp
		//		   Access: private
		//	    Arguments: as_procede  // procede del comprobante
		//				   as_comprobante  //  número del comprobante
		//				   ad_fecha  // fecha del comprobante
		//				   as_codban  //  código de banco del comprobante
		//				   as_ctaban  //  cuenta del banco del comprobante
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 14/08/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, procede_doc, documento, operacion, monto ".
				"  FROM spg_dt_cmp".
				" WHERE codemp = '".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_spgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"]; 
				$ls_spg_cuenta=$row["spg_cuenta"];
				$ls_procede_doc=$row["procede_doc"];
				$ls_documento=$row["documento"]; 
				$ls_operacion=$row["operacion"];
				$ld_monto=$row["monto"];
				/*// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monto);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spg_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede_doc);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_documento);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_operacion);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			}
		}		
		//unset($this->io_rcbsf);
		return $lb_valido;
	}// end function uf_convertir_spgdtcmp
//-----------------------------------------------------------------------------------------------------------------------------

	function uf_convertir_spicuenta($as_spicuenta,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_spicuenta
		//		   Access: private
		//	    Arguments: as_spicuenta  // cuenta de la estructura presupuestaria de ingreso
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualizamos los montos en el valor reconvertido
		//	   Creado Por: Ing. Carlos Zambrano
		// Fecha Creación: 04/10/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$ls_sql="SELECT previsto, devengado, cobrado, cobrado_anticipado, aumento, disminucion, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre".
				"  FROM spi_cuentas".
				" WHERE codemp = '".$this->ls_codemp."'".
				" AND spi_cuenta = '".$as_spicuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->class_funciones MÉTODO->SELECT->uf_convertir_spicuenta ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
			{
				$ld_previsto=$row["previsto"];
				$ld_devengado=$row["devengado"];
				$ld_cobrado=$row["cobrado"];
				$ld_cobrado_anticipado=$row["cobrado_anticipado"];
				$ld_aumento=$row["aumento"]; 
				$ld_disminucion=$row["disminucion"];
				$ld_enero=$row["enero"];
				$ld_febrero=$row["febrero"]; 
				$ld_marzo=$row["marzo"];
				$ld_abril=$row["abril"];
				$ld_mayo=$row["mayo"];
				$ld_junio=$row["junio"]; 
				$ld_julio=$row["julio"];
				$ld_agosto=$row["agosto"];
				$ld_septiembre=$row["septiembre"];
				$ld_octubre=$row["octubre"]; 
				$ld_noviembre=$row["noviembre"];
				$ld_diciembre=$row["diciembre"];
				/*// Campos a Convertir
				$this->io_rcbsf->io_ds_datos->insertRow("campo","previstoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("previsto",$ld_previsto);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","devengadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("devengado",$ld_devengado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cobradoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("cobrado",$ld_cobrado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cobrado_anticipadoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("cobrado_anticipado",$ld_cobrado_anticipado);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","aumentoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("aumento",$ld_aumento);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","disminucionaux");
				$this->io_rcbsf->io_ds_datos->insertRow("disminucion",$ld_disminucion);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("enero",$ld_enero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("febrero",$ld_febrero);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("marzo",$ld_marzo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("abril",$ld_abril);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("mayo",$ld_mayo);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("junio",$ld_junio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("julio",$ld_julio);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("agosto",$ld_agosto);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("septiembre",$ld_septiembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("octubre",$ld_octubre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("noviembre",$ld_noviembre);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("diciembre",$ld_diciembre);
				// Filtros de los Campos
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_cuentas",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);*/
			}
		}		
		///unset($this->io_rcbsf);
		return $lb_valido;
	}// end function uf_convertir_spicuentas
//-----------------------------------------------------------------------------------------------------------------------------



}
?>