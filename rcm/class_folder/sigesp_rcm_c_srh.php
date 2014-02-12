<?php
class sigesp_rcm_c_srh
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rcm_c_srh()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rcm_c_srh
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
	}// end function sigesp_rcm_c_srh
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
			$lb_valido=$this->uf_convertir_srhcontratos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_srhmovimientopersonal();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->uf_convertir_srhsolicitudadiestramientos();
		}
		if($lb_valido)
		{	
			$lb_valido=$this->io_rcbsf->uf_insert_check_srh('SRH',$aa_seguridad);
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
	function uf_convertir_srhcontratos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_srhcontratos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla scv_dt_personal e inserta el valor reconvertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 19/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, nrocon, moncon".
				"  FROM srh_contratos".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_srh MÉTODO->SELECT->uf_convertir_srhcontratos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_nrocon= $row["nrocon"];
				$li_moncon= $row["moncon"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monconaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_moncon);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","nrocon");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nrocon);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("srh_contratos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_srhcontratos
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_srhmovimientopersonal()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_srhmovimientopersonal
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla srh_movimientopersonal e inserta el valor reconvertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, nrocon, monmov".
				"  FROM srh_movimientopersonal".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_srhmovimientopersonal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_nrocon= $row["nrocon"];
				$li_monmov= $row["monmov"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","monmovaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_monmov);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","nrocon");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_nrocon);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("srh_movimientopersonal",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_srhmovimientopersonal
	//-----------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------
	function uf_convertir_srhsolicitudadiestramientos()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_convertir_srhsolicitudadiestramientos
		//		   Access: private
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que selecciona los campos de moneda de la tabla srh_solicitudadiestramientos e inserta el valor reconvertido
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 20/07/2007 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp, soladi, cosadi".
				"  FROM srh_solicitudadiestramientos".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql_origen->select($ls_sql);
		if($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_rcm_c_scv MÉTODO->SELECT->uf_convertir_srhsolicitudadiestramientos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$la_seguridad="";
			while(($row=$this->io_sql_origen->fetch_row($rs_data))&&($lb_valido))
			{
				$ls_codemp= $row["codemp"]; 
				$ls_soladi= $row["soladi"];
				$li_cosadi= $row["cosadi"];
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","cosadiaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$li_cosadi);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","soladi");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_soladi);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("srh_solicitudadiestramientos",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$la_seguridad);
			}
		}		
		return $lb_valido;
	}// end function uf_convertir_srhsolicitudadiestramientos
	//-----------------------------------------------------------------------------------------------------------------------------

}
?>