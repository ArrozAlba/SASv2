<?php
class sigesp_rcm_c_cfg
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_cfg()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_cfg
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
	}// end function sigesp_rcm_c_cfg
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
			$lb_valido=$this->uf_convertir_sigespcmp();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sigespcmpmd();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sigespdeducciones();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sigespempresa();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_sigespmoneda();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_scv('CFG',$aa_seguridad);
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
	function uf_convertir_sigespcmp()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmp
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sigesp_cmp e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, procede, comprobante, fecha, codban, ctaban, total ".
				" FROM   sigesp_cmp   ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cfg MÉTODO->SELECT->uf_convertir_sigespcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_procede = $row["procede"];
				$ls_comprobante = $row["comprobante"]; 
				$ldt_fecha = $row["fecha"];
				$ls_codban = $row["codban"];
				$ls_ctaban = $row["ctaban"];
				$ld_total = $row["total"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_total);
	
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_ctaban);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sigespcmp
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespcmpmd()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespcmpmd
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sigesp_cmp_md e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, procede, comprobante, fecha, total ".
				" FROM   sigesp_cmp_md   ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cfg MÉTODO->SELECT->uf_convertir_sigespcmpmd ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_procede = $row["procede"];
				$ls_comprobante = $row["comprobante"]; 
				$ldt_fecha = $row["fecha"];
				$ld_total = $row["total"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_total);
	
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
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecha);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp_md",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sigespcmpmd
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespdeducciones()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespdeducciones
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sigesp_deducciones e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, codded, monded ".
				" FROM   sigesp_deducciones   ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cfg MÉTODO->SELECT->uf_convertir_sigespdeducciones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ls_codded = $row["codded"];
				$ld_monded = $row["monded"];

				$this->io_rcbsf->io_ds_datos->insertRow("campo","mondedaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_monded);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codded");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codded);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_deducciones",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sigespdeducciones
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespempresa()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespempresa
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sigesp_empresa e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codemp, salinipro, salinieje ".
				" FROM   sigesp_empresa   ".
				" WHERE  codemp='".$this->ls_codemp."' ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cfg MÉTODO->SELECT->uf_convertir_sigespempresa ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp = $row["codemp"]; 
				$ld_salinipro = $row["salinipro"];
				$ld_salinieje = $row["salinieje"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","saliniproaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_salinipro);
	
	  		    $this->io_rcbsf->io_ds_datos->insertRow("campo","saliniejeaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_salinieje);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_empresa",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sigespempresa
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_sigespmoneda()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_sigespmoneda
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla sigesp_moneda e inserta el valor convertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT codmon, tascam ".
				" FROM   sigesp_moneda  ";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_cfg MÉTODO->SELECT->uf_convertir_sigespmoneda ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codmon = $row["codmon"]; 
				$ld_tascam = $row["tascam"];
			
				$this->io_rcbsf->io_ds_datos->insertRow("campo","tascamaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_tascam);
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codmon");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codmon);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_moneda",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_sigespmoneda
	//-----------------------------------------------------------------------------------------------------------------------------
}
?>