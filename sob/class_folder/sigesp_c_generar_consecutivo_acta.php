<?php
class sigesp_c_generar_consecutivo_acta
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_logusr;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_c_generar_consecutivo_acta()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_c_generar_consecutivo
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/class_datastore.php");
		$this->io_dscuentas=new class_datastore();
		$this->io_dscargos=new class_datastore();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_logusr=$_SESSION["la_logusr"];
	}// end function sigesp_c_generar_consecutivo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_prefijo($as_codsis,$as_procede)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_prefijo
		//		   Access: private
		//		 Argument: $as_codsis   // Codigo de Sistema
		//				   $as_procede  // Procedencia del Documento
		//	  Description: Función que Obtiene el prefijo del numero de documento (en caso de poseerlo)
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_prefijo="";
		$ls_sql="SELECT codsis, procede, id, prefijo, codusu".
				"  FROM sigesp_ctrl_numero ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codsis='".$as_codsis."'".
				"   AND procede='".$as_procede."'".
				"   AND estact=1";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Generar_Consecutivo MÉTODO->uf_load_prefijo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codusu=rtrim($row["codusu"]);
				if($ls_codusu!="--")
				{
					if($ls_codusu==$this->ls_logusr)
					{
						$ls_prefijo=$row["prefijo"];
					}
				}
				else
				{
						$ls_prefijo=$row["prefijo"];
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_prefijo;
	}// end function uf_load_prefijo
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_numero_inicial($as_campo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_numero_inicial
		//		   Access: private
		//		 Argument: $as_campo   // Nombre del Campo que Contiene el Valor Inicial del Documento
		//	  Description: Función que el Valor Inicial del Documento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_nroini="";
		if($as_campo=="")
		{
			return $ls_nroini;
		}
		$ls_sql="SELECT ".$as_campo."".
				"  FROM sigesp_empresa ".
				" WHERE codemp='".$this->ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Generar_Consecutivo MÉTODO->uf_load_numero_inicial ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nroini=$row[$as_campo];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_nroini;
	}// end function uf_load_numero_inicial
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_data_existente($as_tabla,$as_campo,$as_filtro,$as_valor,$as_filtro1,$as_valor1,$as_prefijo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_data_existente
		//		   Access: private
		//		 Argument: $as_tabla   // Nombre de la Tabla de registro del documento
		//				   $as_campo   // Nombre del Campo que Contiene el id del documento
		//				   $as_filtro   // Nombre del Campo que Contiene el filtro
		//				   $as_valor   // Valor del Filtro
		//				   $as_prefijo // Prefijo del numero de comprobante
		//	  Description: Función que obtiene el ultimo valor de la tabla indicada
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_release.php");
		$io_release= new sigesp_release();
		$lb_valido=true;
		$ls_nroact="";
		$ls_criterio="";

		$lb_valido=$io_release->io_function_db->uf_select_column($as_tabla,'codemp');	
		if($lb_valido==false)
		{
			if(!empty($as_filtro))
			{
				$ls_criterio=$ls_criterio. " WHERE ".$as_filtro."='".$as_valor."'";
			}
			if($ls_criterio=="")
			{
				$ls_criterio=" WHERE ".$as_campo." LIKE '".$as_prefijo."%'";
			}
			else
			{
				$ls_criterio=$ls_criterio. " AND ".$as_campo." LIKE '".$as_prefijo."%'";
			}
			if(!empty($as_filtro1))
			{
				$ls_criterio=$ls_criterio. " AND ".$as_filtro1."='".$as_valor1."'";
			}
			$ls_sql="SELECT ".$as_campo."".
					"  FROM ".$as_tabla." ".
					" ".$ls_criterio." ".
					" ORDER BY ".$as_campo." DESC LIMIT 1";
		}
		else
		{
			if(!empty($as_filtro))
			{
				$ls_criterio=$ls_criterio. " AND ".$as_filtro."='".$as_valor."'";
			}
	
			$ls_criterio=$ls_criterio. " AND ".$as_campo." LIKE '".$as_prefijo."%'";
			$ls_sql="SELECT ".$as_campo."".
					"  FROM ".$as_tabla." ".
					" WHERE codemp='".$this->ls_codemp."'".
					" ".$ls_criterio." ".
					" ORDER BY ".$as_campo." DESC LIMIT 1";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Generar_Consecutivo MÉTODO->uf_load_data_existente ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nroact=$row[$as_campo];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $ls_nroact;
	}// end function uf_load_data_existente
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_numero_generado($as_codsis,$as_tabla,$as_campo,$as_procede,$ai_loncam,$as_camini,$as_filtro,
										  $as_valor,$as_filtro1,$as_valor1,&$as_numero)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_numero_generado
		//		   Access: private
		//		 Argument: $as_codsis  // Codigo de Sistema
		//				   $as_tabla   // Nombre de la Tabla de registro del documento
		//				   $as_campo   // Nombre del Campo que Contiene el id del documento
		//				   $ai_loncam  // Longitud del Campo
		//				   $as_camini  // Nombre del campo que tiene el valor inicial del documento
		//				   $as_filtro   // Nombre del Campo que Contiene el filtro
		//				   $as_valor   // Valor del Filtro
		//				   $as_numero  // Valor a verificar
		//				   $ai_estgen  // Indica si se esta Generando el Numero por que el Actual ya existe o no.
		//	  Description: Función que verifica si un numero generado esta disponible
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_release.php");
		$io_release= new sigesp_release();
		$lb_valido=false;
		$ls_nroact="";
		$ls_nroant=$as_numero;
		$ls_criterio="";
		if(!empty($as_filtro))
		{
			$ls_criterio= "AND ".$as_filtro."='".$as_valor."'";
		}
		if(!empty($as_filtro1))
		{
			$ls_criterio=$ls_criterio. " AND ".$as_filtro1."='".$as_valor1."'";
		}
		$lb_valido=$io_release->io_function_db->uf_select_column($as_tabla,'codemp');	
		if($lb_valido==false)
		{
			$ls_sql="SELECT ".$as_campo."".
					"  FROM ".$as_tabla."".
					" WHERE ".$as_campo."='".$as_numero."'".
					" ".$ls_criterio." ";
		}
		else
		{
			$ls_sql="SELECT ".$as_campo."".
					"  FROM ".$as_tabla."".
					" WHERE codemp='".$this->ls_codemp."'".
					"   AND ".$as_campo."='".$as_numero."'".
					" ".$ls_criterio." ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Generar_Consecutivo MÉTODO->uf_verificar_numero_generado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$as_numero=$this->uf_generar_numero_nuevo($as_codsis,$as_tabla,$as_campo,$as_procede,$ai_loncam,$as_camini,
														  $as_filtro,$as_valor,$as_filtro1,$as_valor1);
			}
			else
			{
				if($ls_nroant!=$as_numero)
				{
					$this->io_mensajes->message("Se le Asignó un nuevo número de documento el cual es :".$as_numero);
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_verificar_numero_generado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_generar_numero_nuevo($as_codsis,$as_tabla,$as_campo,$as_procede,$ai_loncam,$as_camini,$as_filtro,$as_valor,$as_filtro1,$as_valor1)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_generar_numero_nuevo
		//		   Access: private
		//		 Argument: $as_codsis  // Codigo de Sistema
		//				   $as_tabla   // Nombre de la Tabla de registro del documento
		//				   $as_campo   // Nombre del Campo que Contiene el id del documento
		//				   $ai_loncam  // Longitud del Campo
		//				   $as_camini  // Nombre del campo que tiene el valor inicial del documento
		//				   $as_filtro   // Nombre del Campo que Contiene el filtro
		//				   $as_valor   // Valor del Filtro
		//				   $ai_estgen  // Indica si se esta Generando el Numero por que el Actual ya existe o no.
		//	  Description: Función que verifica si un numero generado esta disponible
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_nvonro="";
		if($ai_loncam>10)
		{
			$ls_prefijo=$this->uf_load_prefijo($as_codsis,$as_procede);
		}
		else
		{
			$ls_prefijo="";
		}
		$ls_nroact=$this->uf_load_data_existente($as_tabla,$as_campo,$as_filtro,$as_valor,$as_filtro1,$as_valor1,$ls_prefijo);
		$li_lonpre=strlen($ls_prefijo);
		if($ls_nroact!="")
		{
			if($ls_prefijo!="")
			{
				$li_nrolen=$ai_loncam-$li_lonpre;
				$ls_numpre=substr($ls_nroact,0,$li_lonpre);
				$ls_nro=substr($ls_nroact,$li_lonpre,$li_nrolen);
			}
			else
			{
				$ls_nro=$ls_nroact;
				$li_nrolen=$ai_loncam;
			}
		}
		else
		{
			$ls_nro=$this->uf_load_numero_inicial($as_camini);
			if($ls_nro=="")
			{
				$ls_nro=0;
			}
		}
		settype($ls_nro,'int');
		$li_nvonro=$ls_nro + 1;
		if($ls_prefijo!="")
		{
			$ls_nvonro= $this->io_funciones->uf_cerosizquierda($li_nvonro,$ai_loncam-$li_lonpre);
			$ls_nvonro= $ls_prefijo.$ls_nvonro;
		}
		else
		{
			$ls_nvonro= $this->io_funciones->uf_cerosizquierda($li_nvonro,$ai_loncam);
		}
		$lb_valido=$this->uf_verificar_numero_generado($as_codsis,$as_tabla,$as_campo,$as_procede,$ai_loncam,$as_camini,$as_filtro,
										 			   $as_valor,$as_filtro1,$as_valor1,&$ls_nvonro);
		return $ls_nvonro;
	}
//-----------------------------------------------------------------------------------------------------------------------------------
}
?>