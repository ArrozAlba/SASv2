<?php
class sigesp_sep_c_solicitud
 {
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_id_process;
	var $ls_codemp;
	var $io_dscuentas;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sep_c_solicitud($as_path)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sep_c_solicitud
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $as_pathaux;
		$as_pathaux=$as_path;
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
	    require_once($as_path."shared/class_folder/class_fecha.php");		
		$this->io_fecha= new class_fecha();
		require_once($as_path."shared/class_folder/class_generar_id_process.php");
		$this->io_id_process=new class_generar_id_process();		
		require_once($as_path."shared/class_folder/sigesp_c_generar_consecutivo.php");
		$this->io_keygen= new sigesp_c_generar_consecutivo();
		require_once($as_path."shared/class_folder/class_datastore.php");
		$this->io_dscuentas=new class_datastore();
		$this->io_dscargos=new class_datastore();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_parsindis=$_SESSION["la_empresa"]["estparsindis"];
		$la_empresa=$_SESSION["la_empresa"];
		$this->ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$this->li_longestpro1= (25-$this->ls_loncodestpro1)+1;
		$this->ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$this->li_longestpro2= (25-$this->ls_loncodestpro2)+1;
		$this->ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$this->li_longestpro3= (25-$this->ls_loncodestpro3)+1;
		$this->ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$this->li_longestpro4= (25-$this->ls_loncodestpro4)+1;
		$this->ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
		$this->li_longestpro5= (25-$this->ls_loncodestpro5)+1;
	}// end function sigesp_sep_c_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fecha);
        unset($this->ls_codemp);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_tiposolicitud($as_seleccionado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_tiposolicitud
		//		   Access: private
		//		 Argument: $as_seleccionado // Valor del campo que va a ser seleccionado
		//	  Description: Función que busca en la tabla de tipo de solicitud los tipos de SEP
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codtipsol, dentipsol, estope, modsep, estayueco".
				"  FROM sep_tiposolicitud ".
				" ORDER BY modsep, estope  ASC "; 	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_tiposolicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			print "<select name='cmbcodtipsol' id='cmbcodtipsol' onChange='javascript: ue_cargargrid();' style=width:200px>";
			print " <option value='-'>---seleccione---</option>";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_seleccionado="";
				$ls_codtipsol=$row["codtipsol"];
				$ls_dentipsol=$row["dentipsol"];
				$ls_modsep=trim($row["modsep"]);
				$ls_estope=trim($row["estope"]);
				$ls_estayueco=$row["estayueco"];
				$ls_operacion="";
				switch($ls_estope)
				{
					case"R":// Precompromiso
						$ls_operacion="Precompromiso";
						break;
					case"O":// Compromiso
						$ls_operacion="Compromiso";
						break;
					case"S":// Sin Afectacion
						$ls_operacion="Sin Afectacion Presupuestaria";
						break;
				}
				if($as_seleccionado==$ls_codtipsol."-".$ls_modsep."-".$ls_estope."-".$ls_estayueco)
				{
					$ls_seleccionado="selected";
				} 
	
				print "<option value='".$ls_codtipsol."-".$ls_modsep."-".$ls_estope."-".$ls_estayueco."' ".$ls_seleccionado.">".$ls_dentipsol." - ".$ls_operacion."</option>";
			   
			}
			$this->io_sql->free_result($rs_data);	
			print "</select>";
		}
		return $lb_valido;
	}// end function uf_load_tiposolicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_fecha_sep($ad_fecregsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_fecha_sep
		//		   Access: private
		//		 Argument: $ad_fecregsol // fecha de registro dee solicitud de la nueva sep
		//	  Description: Función que busca la fecha de la última sep y la compara con la fecha actual
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT numsol,fecregsol ".
				"  FROM sep_solicitud  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				" ORDER BY numsol DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_fecha_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ld_fecha=$row["fecregsol"];
				$ld_fecha=$this->io_funciones->uf_formatovalidofecha($row["fecregsol"]);
				//$lb_valido=$this->io_fecha->uf_comparar_fecha($ld_fecha,$ad_fecregsol); 
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_validar_fecha_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosbienes($as_codart,$as_codprounidad,$as_estcla)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosbienes
		//		   Access: public
		//		 Argument: as_codart // Código del artículo que se están buscando los cargos
		//		 		   as_codprounidad // Código Programàtico de la unidad ejecutora
		//	  Description: Función que busca los cargos asociados a un artículo
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1= substr($as_codprounidad,0,25);
		$ls_codestpro2= substr($as_codprounidad,25,25);
		$ls_codestpro3= substr($as_codprounidad,50,25);
		$ls_codestpro4= substr($as_codprounidad,75,25);
		$ls_codestpro5= substr($as_codprounidad,100,25);

		$ls_sql="SELECT siv_cargosarticulo.codart AS codigo, sigesp_cargos.codcar, sigesp_cargos.dencar, ".
				"		TRIM(sigesp_cargos.spg_cuenta) AS spg_cuenta, sigesp_cargos.formula, sigesp_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."'                      ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."'                      ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."'                      ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."'                      ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."'                      ".
				"			AND spg_cuentas.estcla='".$as_estcla."'                                ".				
				"			AND sigesp_cargos.codemp = spg_cuentas.codemp                          ".				
				"			AND sigesp_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM sigesp_cargos, siv_cargosarticulo                                          ".
                " WHERE siv_cargosarticulo.codemp = '".$this->ls_codemp."'                         ".
				"   AND siv_cargosarticulo.codart = '".$as_codart."'                               ".
				"	AND sigesp_cargos.codemp = siv_cargosarticulo.codemp                           ".
				"   AND sigesp_cargos.porcar<>0                                                    ".
				"   AND sigesp_cargos.codcar = siv_cargosarticulo.codcar                           ";				
		$rs_data=$this->io_sql->select($ls_sql); 
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cargosbienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosbienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosservicios($as_codser,$as_codprounidad,$as_estcla)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosservicios
		//		   Access: public
		//		 Argument: as_codser // Código del artículo que se están buscando los cargos
		//		 		   as_codprounidad // Código Programàtico de la unidad ejecutora
		//	  Description: Función que busca los cargos asociados a un servicio
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1= substr($as_codprounidad,0,25);
		$ls_codestpro2= substr($as_codprounidad,25,25);
		$ls_codestpro3= substr($as_codprounidad,50,25);
		$ls_codestpro4= substr($as_codprounidad,75,25);
		$ls_codestpro5= substr($as_codprounidad,100,25);

		$ls_sql="SELECT soc_serviciocargo.codser AS codigo, sigesp_cargos.codcar, sigesp_cargos.dencar,".
				"		TRIM(sigesp_cargos.spg_cuenta) AS spg_cuenta, sigesp_cargos.formula, sigesp_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND spg_cuentas.estcla='".$as_estcla."' ".
				"			AND sigesp_cargos.codemp = spg_cuentas.codemp ".
				"			AND sigesp_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM sigesp_cargos, soc_serviciocargo ".
                " WHERE soc_serviciocargo.codemp = '".$this->ls_codemp."' ".
				"   AND soc_serviciocargo.codser = '".$as_codser."' ".
				"	AND sigesp_cargos.codemp = soc_serviciocargo.codemp ".
				"   AND sigesp_cargos.codcar = soc_serviciocargo.codcar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cargosservicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosservicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargosconceptos($as_codcon,$as_codprounidad,$as_estcla)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargosconceptos
		//		   Access: public
		//		 Argument: as_codcon // Código del concepto que se están buscando los cargos
		//		 		   as_codprounidad // Código Programàtico de la unidad ejecutora
		//	  Description: Función que busca los cargos asociados a un Concepto
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codestpro1= substr($as_codprounidad,0,25);
		$ls_codestpro2= substr($as_codprounidad,25,25);
		$ls_codestpro3= substr($as_codprounidad,50,25);
		$ls_codestpro4= substr($as_codprounidad,75,25);
		$ls_codestpro5= substr($as_codprounidad,100,25);

		$ls_sql="SELECT sep_conceptocargos.codconsep AS codigo, sigesp_cargos.codcar, sigesp_cargos.dencar,".
				"		TRIM(sigesp_cargos.spg_cuenta) AS spg_cuenta, sigesp_cargos.formula, sigesp_cargos.codestpro, ".
				"		(SELECT COUNT(spg_cuenta) FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codestpro1 = '".$ls_codestpro1."' ".
				"		    AND spg_cuentas.codestpro2 = '".$ls_codestpro2."' ".
				"		    AND spg_cuentas.codestpro3 = '".$ls_codestpro3."' ".
				"		    AND spg_cuentas.codestpro4 = '".$ls_codestpro4."' ".
				"		    AND spg_cuentas.codestpro5 = '".$ls_codestpro5."' ".
				"			AND spg_cuentas.estcla='".$as_estcla."' ".				
				"			AND sigesp_cargos.codemp = spg_cuentas.codemp ".
				"			AND sigesp_cargos.spg_cuenta = spg_cuentas.spg_cuenta) AS existecuenta ".
                "  FROM sigesp_cargos, sep_conceptocargos ".
                " WHERE sep_conceptocargos.codemp = '".$this->ls_codemp."' ".
				"   AND sep_conceptocargos.codconsep = '".$as_codcon."' ".
				"	AND sigesp_cargos.codemp = sep_conceptocargos.codemp ".
				"   AND sigesp_cargos.codcar = sep_conceptocargos.codcar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cargosconceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosconceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_solicitud($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  //  Número de Solicitud
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que verifica si la Solicitu de ejecución Presupuestaria Existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$ls_sql="SELECT numsol ".
				"  FROM sep_solicitud ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if(!$row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuentacontable($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_spgcuenta,$as_estcla,&$as_sccuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cuentacontable
		//		   Access: private
		//	    Arguments: as_codestpro1  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro2  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro3  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro4  //  Còdigo de Estructura Programàtica
		//	    		   as_codestpro5  //  Còdigo de Estructura Programàtica
		//	    		   as_spgcuenta  //  Cuentas Presupuestarias
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que obtiene la cuenta contable 
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_sccuenta=""; 
		$ls_sql="SELECT sc_cuenta ".
				"  FROM spg_cuentas ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codestpro1='".$as_codestpro1."' ".
				"   AND codestpro2='".$as_codestpro2."' ".
				"   AND codestpro3='".$as_codestpro3."' ".
				"   AND codestpro4='".$as_codestpro4."' ".
				"   AND codestpro5='".$as_codestpro5."' ".
				"   AND estcla= '".$as_estcla."'".
				"   AND spg_cuenta='".$as_spgcuenta."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_select_cuentacontable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_sccuenta=$row["sc_cuenta"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_select_cuentacontable
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_bienes($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_bienes
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los bienes asociados a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_dt_articulos.codart, sep_dt_articulos.canart, sep_dt_articulos.unidad, sep_dt_articulos.monpre, ".
				"		sep_dt_articulos.monart, sep_dt_articulos.orden, TRIM(sep_dt_articulos.spg_cuenta) AS spg_cuenta, siv_articulo.denart, ".
				"		siv_unidadmedida.unidad AS unimed, sep_dt_articulos.orden, sep_dt_articulos.codestpro1, sep_dt_articulos.codestpro2, sep_dt_articulos.codestpro2,".
				"       sep_dt_articulos.codestpro3, sep_dt_articulos.codestpro4, sep_dt_articulos.codestpro5, sep_dt_articulos.estcla".
                "  FROM sep_dt_articulos, siv_articulo, siv_unidadmedida ".
                " WHERE sep_dt_articulos.codemp = '".$this->ls_codemp."' ".
				"   AND sep_dt_articulos.numsol = '".$as_numsol."' ".
				"   AND sep_dt_articulos.codemp = siv_articulo.codemp ".
				"   AND sep_dt_articulos.codart = siv_articulo.codart ".
				"	AND siv_articulo.codunimed = siv_unidadmedida.codunimed ".
				" ORDER BY sep_dt_articulos.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_servicios($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_servicios
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los servicios asociados a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_dt_servicio.codser, sep_dt_servicio.canser, sep_dt_servicio.monpre, ".
				"		sep_dt_servicio.monser, sep_dt_servicio.orden, TRIM(sep_dt_servicio.spg_cuenta) AS spg_cuenta, soc_servicios.denser, ".
				"       sep_dt_servicio.codestpro1,sep_dt_servicio.codestpro2,sep_dt_servicio.codestpro3,sep_dt_servicio.codestpro4,".
				"       sep_dt_servicio.codestpro5,sep_dt_servicio.estcla ".
                "  FROM sep_dt_servicio, soc_servicios ".
                " WHERE sep_dt_servicio.codemp = '".$this->ls_codemp."' ".
				"   AND sep_dt_servicio.numsol = '".$as_numsol."' ".
				"   AND sep_dt_servicio.codemp = soc_servicios.codemp ".
				"   AND sep_dt_servicio.codser = soc_servicios.codser ".
				" ORDER BY sep_dt_servicio.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_conceptos($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_conceptos
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca los conceptos asociados a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sep_dt_concepto.codconsep, sep_dt_concepto.cancon, sep_dt_concepto.monpre, ".
				"		sep_dt_concepto.moncon, sep_dt_concepto.orden, TRIM(sep_dt_concepto.spg_cuenta) AS spg_cuenta,".
				"		sep_conceptos.denconsep,sep_dt_concepto.codestpro1,sep_dt_concepto.codestpro2,sep_dt_concepto.codestpro3, ".
				"       sep_dt_concepto.codestpro4,sep_dt_concepto.codestpro5,sep_dt_concepto.estcla".
                "  FROM sep_dt_concepto, sep_conceptos ".
                " WHERE sep_dt_concepto.codemp = '".$this->ls_codemp."' ".
				"   AND sep_dt_concepto.numsol = '".$as_numsol."' ".
				"   AND sep_dt_concepto.codconsep = sep_conceptos.codconsep ".
				" ORDER BY sep_dt_concepto.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cargos($as_numsol, $as_tabla, $as_campo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cargos
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//		 		   as_tabla // Tabla en la cual se va a buscar
		//		 		   as_campo // campo por el cual se va a buscar
		//	  Description: Función que busca los cargos asociados a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true; 
		$ls_sql="SELECT ".$as_tabla.".".$as_campo." AS codigo, ".$as_tabla.".codcar, ".$as_tabla.".monbasimp, ".$as_tabla.".monimp, ".
				"       ".$as_tabla.".monto, ".$as_tabla.".formula, sigesp_cargos.dencar, TRIM(sigesp_cargos.spg_cuenta) AS spg_cuenta, ".
				"       ".$as_tabla.".codestpro1,".$as_tabla.".codestpro2,".$as_tabla.".codestpro3,".$as_tabla.".codestpro4,".$as_tabla.".codestpro5,".$as_tabla.".estcla".
				"  FROM ".$as_tabla.", sigesp_cargos ".
				" WHERE ".$as_tabla.".codemp = '".$this->ls_codemp."' ".
				"   AND ".$as_tabla.".numsol = '".$as_numsol."' ".
				"   AND ".$as_tabla.".codemp = sigesp_cargos.codemp ".
				"   AND ".$as_tabla.".codcar = sigesp_cargos.codcar ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cargosbienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca las cuentas asociadas a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , TRIM(codestpro3) AS codestpro3 , ".
				"		TRIM(codestpro4) AS codestpro4 , TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
				"		monto AS total,estcla ".
				"  FROM sep_cuentagasto ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."' ".
				" UNION ".
				"SELECT TRIM(codestpro1) AS codestpro1 , TRIM(codestpro2) AS codestpro2 , TRIM(codestpro3) AS codestpro3 , ".
				"		TRIM(codestpro4) AS codestpro4 , TRIM(codestpro5) AS codestpro5 , TRIM(spg_cuenta) AS spg_cuenta , ".
				"		-monto AS total,estcla ".
				"  FROM sep_solicitudcargos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->io_dscuentas->data=$this->io_sql->obtener_datos($rs_data);
				$this->io_dscuentas->group_by(array('0'=>'codestpro1','1'=>'codestpro2','2'=>'codestpro3',
				                                    '3'=>'codestpro4','4'=>'codestpro5','5'=>'spg_cuenta'),
											  array('0'=>'total'),'total');
			}
		}
		return $this->io_dscuentas;
	}// end function uf_load_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_cuentas_cargo($as_numsol)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_cuentas_cargo
		//		   Access: public
		//		 Argument: as_numsol // Número de solicitud
		//	  Description: Función que busca las cuentas asociadas a una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT codcar,codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, TRIM(spg_cuenta) AS spg_cuenta, monto AS total,estcla ".
				"  FROM sep_solicitudcargos ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_load_cuentas_cargo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		return $rs_data;
	}// end function uf_load_cuentas_cargo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprov,$as_cedben,$as_consol,
							     $as_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
								 $as_codestpro5,$as_estcla,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$as_tabla,
								 $as_campo,$ai_totrowservicios,$ai_totrowconceptos,$ls_nombenalt,$as_tipsepbie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_solicitud
		//		   Access: private
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codfuefin  // Código de Fuente de financiamiento
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codprov  // Código de Proveedor 
		//				   as_cedben  // Código de Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_subtotal  // Subtotal de la solicitu
		//				   ai_cargos  // Monto del cargo
		//				   ai_total  // Total de la solicitud
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas del Cargo
		//				   ai_totrowservicios  // Total de Filas de Servicios
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
	    $ls_numsolaux=$as_numsol; 
	    $lb_valido= $this->io_keygen->uf_verificar_numero_generado("SEP","sep_solicitud","numsol","SEPSPC",15,"","","",&$as_numsol);
		$lb_valido=true;
		if($lb_valido)
		{
			$ls_sql="INSERT INTO sep_solicitud (codemp,numsol,codtipsol,coduniadm,fecregsol,estsol,consol,monto,tipsepbie,".
					" 							monbasinm,montotcar,cod_pro,ced_bene,tipo_destino,codfuefin,estapro,".
					"                           codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,nombenalt, ".
					"                           codusu)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$as_codtipsol."','".$as_coduniadm."',".
					" 			  '".$ad_fecregsol."','R','".$as_consol."',".$ai_total.",'M',".$ai_subtotal.",
					               ".$ai_cargos.",'".$as_codprov."','".$as_cedben."','".$as_tipodestino."','".$as_codfuefin."',0,
								   '".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."',
								   '".$as_codestpro5."','".$as_estcla."','".$ls_nombenalt."','".$aa_seguridad["logusr"]."')";
			
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				 $this->io_sql->rollback();
				 if ($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				 {
					 $this->uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprov,$as_cedben,$as_consol,
					 $as_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
					 $as_codestpro5,$as_estcla,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$as_tabla,
					 $as_campo,$ai_totrowservicios,$ai_totrowconceptos,$ls_nombenalt,$as_tipsepbie,$aa_seguridad);
				 }
				 else
				 {
					$lb_valido=false;
					$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				 }
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
              if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_bienes($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
													   $as_codestpro5,$as_estcla,$ai_totrowbienes,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_servicios($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														 $as_codestpro5,$as_estcla,$ai_totrowservicios,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_conceptos($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$as_estcla,$ai_totrowconceptos,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_cargos($as_numsol,$ai_totrowcargos,$as_tabla,$as_campo,$aa_seguridad);
				}			
				if($lb_valido)
				{	
					$lb_valido=$this->uf_insert_cuentas($as_numsol,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
				}		
				if($lb_valido)
				{ 
				  $lb_valido=$this->uf_insert_cuentas_cargos($as_numsol,$ai_totrowcuentascargo,$ai_totrowcargos,$as_codprov,$as_cedben,$as_estcla,$aa_seguridad);
				}
				if($lb_valido)
				{	
					if($ls_numsolaux!=$as_numsol)
					{
						$this->io_mensajes->message("Se Asigno el Numero de Solicitud: ".$as_numsol);
					}
					$lb_valido=true;
					$this->io_sql->commit();
					$this->io_mensajes->message("La Solicitud ha sido Registrada."); 
				}			
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Registrar la Solicitud."); 
					$this->io_sql->rollback();
				}
			}
		}
		return $lb_valido;
	}// end function uf_insert_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_bienes($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
							  $ai_totrowbienes,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_bienes
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los bienes de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowbienes)&&($lb_valido);$li_i++)
		{
			$ls_codart=$_POST["txtcodart".$li_i];
			$ls_denart=$_POST["txtdenart".$li_i];
			$li_canart=$_POST["txtcanart".$li_i];
			$ls_unidad=$_POST["cmbunidad".$li_i];
			$li_preart=$_POST["txtpreart".$li_i];
			$li_subtotart=$_POST["txtsubtotart".$li_i];
			$li_carart=$_POST["txtcarart".$li_i];
			$li_totart=$_POST["txttotart".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$ls_unidadfisica=$_POST["txtunidad".$li_i];			
			$ls_codestpro=$_POST["txtcodgas".$li_i];			
			$ls_codspg=$_POST["txtcodspg".$li_i];			
			$ls_estcla=$_POST["txtstatus".$li_i];			
			$li_canart=str_replace(".","",$li_canart);
			$li_canart=str_replace(",",".",$li_canart);
			$li_preart=str_replace(".","",$li_preart);
			$li_preart=str_replace(",",".",$li_preart);			
			$li_totart=str_replace(".","",$li_totart);
			$li_totart=str_replace(",",".",$li_totart);
			$ls_codestpro1=substr($ls_codestpro,0,25);
			$ls_codestpro2=substr($ls_codestpro,25,25);
			$ls_codestpro3=substr($ls_codestpro,50,25);
			$ls_codestpro4=substr($ls_codestpro,75,25);
			$ls_codestpro5=substr($ls_codestpro,100,25);
			$ls_sql="INSERT INTO sep_dt_articulos (codemp, numsol, codart, canart, unidad, monpre, monart, orden, codestpro1, ".
					"							   codestpro2, codestpro3, codestpro4, codestpro5,spg_cuenta,estincite,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codart."',".$li_canart.",".
					" 			  '".$ls_unidad."',".$li_preart.",".$li_totart.",".$li_i.",'".$ls_codestpro1."','".$ls_codestpro2."',".
					"			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_codspg."','NI','".$ls_estcla."')"; 
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_bienes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Articulo ".$ls_codart." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_bienes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_servicios($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
							  	 $ai_totrowservicios,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_servicios
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowservicios  // Total de Filas de Servicios
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los servicios de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowservicios)&&($lb_valido);$li_i++)
		{
			$ls_codser=$_POST["txtcodser".$li_i];
			$ls_denser=$_POST["txtdenser".$li_i];
			$li_canser=$_POST["txtcanser".$li_i];
			$li_preser=$_POST["txtpreser".$li_i];
			$li_subtotser=$_POST["txtsubtotser".$li_i];
			$li_carser=$_POST["txtcarser".$li_i];
			$li_totser=$_POST["txttotser".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$ls_codestpro=$_POST["txtcodgas".$li_i];			
			$ls_codspg=$_POST["txtcodspg".$li_i];			
			$ls_estcla=$_POST["txtstatus".$li_i];			
			$li_canser=str_replace(".","",$li_canser);
			$li_canser=str_replace(",",".",$li_canser);
			$li_preser=str_replace(".","",$li_preser);
			$li_preser=str_replace(",",".",$li_preser);			
			$li_totser=str_replace(".","",$li_totser);
			$li_totser=str_replace(",",".",$li_totser);
			$ls_codestpro1=substr($ls_codestpro,0,25);
			$ls_codestpro2=substr($ls_codestpro,25,25);
			$ls_codestpro3=substr($ls_codestpro,50,25);
			$ls_codestpro4=substr($ls_codestpro,75,25);
			$ls_codestpro5=substr($ls_codestpro,100,25);
			$ls_sql="INSERT INTO sep_dt_servicio (codemp, numsol, codser, canser, monpre, monser, orden, codestpro1, codestpro2, ".
					"							  codestpro3, codestpro4, codestpro5, spg_cuenta,estincite,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codser."',".$li_canser.",".
					" 			  ".$li_preser.",".$li_totser.",".$li_i.",'".$ls_codestpro1."','".$ls_codestpro2."',".
					"			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_codspg."','NI','".$ls_estcla."')";        
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_servicios ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Servicio ".$ls_codser." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_servicios
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_conceptos($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
							  	 $ai_totrowconceptos,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_conceptos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los conceptos de una  Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowconceptos)&&($lb_valido);$li_i++)
		{
			$ls_codcon=$_POST["txtcodcon".$li_i];
			$ls_dencon=$_POST["txtdencon".$li_i];
			$li_cancon=$_POST["txtcancon".$li_i];
			$li_precon=$_POST["txtprecon".$li_i];
			$li_subtotcon=$_POST["txtsubtotcon".$li_i];
			$li_carcon=$_POST["txtcarcon".$li_i];
			$li_totcon=$_POST["txttotcon".$li_i];
			$ls_spgcuenta=$_POST["txtspgcuenta".$li_i];			
			$li_cancon=str_replace(".","",$li_cancon);
			$li_cancon=str_replace(",",".",$li_cancon);
			$li_precon=str_replace(".","",$li_precon);
			$li_precon=str_replace(",",".",$li_precon);			
			$li_totcon=str_replace(".","",$li_totcon);
			$li_totcon=str_replace(",",".",$li_totcon);
			$ls_codestpro=$_POST["txtcodgas".$li_i];			
			$ls_codspg=$_POST["txtcodspg".$li_i];			
			$ls_estcla=$_POST["txtstatus".$li_i];			
			$ls_codestpro1=substr($ls_codestpro,0,25);
			$ls_codestpro2=substr($ls_codestpro,25,25);
			$ls_codestpro3=substr($ls_codestpro,50,25);
			$ls_codestpro4=substr($ls_codestpro,75,25);
			$ls_codestpro5=substr($ls_codestpro,100,25);
			$ls_sql="INSERT INTO sep_dt_concepto (codemp, numsol, codconsep, cancon, monpre, moncon, orden, codestpro1, codestpro2, ".
					"							  codestpro3, codestpro4, codestpro5, spg_cuenta,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcon."',".$li_cancon.",".
					" 			  ".$li_precon.",".$li_totcon.",".$li_i.",'".$ls_codestpro1."','".$ls_codestpro2."',".
					"			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_codspg."','".$ls_estcla."')";    
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Concepto ".$ls_codcon." a la SEP ".$as_numsol.
								 " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cargos($as_numsol,$ai_totrowcargos,$as_tabla,$as_campo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cargos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   ai_totrowcargos  // Total de Filas de los cargos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta los cargos de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrowcargos)&&($lb_valido);$li_i++)
		{
			$ls_codart=$_POST["txtcodservic".$li_i];
			$ls_codcar=$_POST["txtcodcar".$li_i];
			$ls_dencar=$_POST["txtdencar".$li_i];
			$li_bascar=$_POST["txtbascar".$li_i];
			$li_moncar=$_POST["txtmoncar".$li_i];
			$li_subcargo=$_POST["txtsubcargo".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$ls_cuentacargo=$_POST["cuentacargo".$li_i];			
			$ls_codestpro=$_POST["txtcodgascre".$li_i];			
			$ls_codspgcre=$_POST["txtcodspgcre".$li_i];			
			$ls_statuscre=$_POST["txtstatuscre".$li_i];			

			$ls_codestpro1=substr($ls_codestpro,0,25);
			$ls_codestpro2=substr($ls_codestpro,25,25);
			$ls_codestpro3=substr($ls_codestpro,50,25);
			$ls_codestpro4=substr($ls_codestpro,75,25);
			$ls_codestpro5=substr($ls_codestpro,100,25);
			$li_bascar=str_replace(".","",$li_bascar);
			$li_bascar=str_replace(",",".",$li_bascar);			
			$li_moncar=str_replace(".","",$li_moncar);
			$li_moncar=str_replace(",",".",$li_moncar);
			$li_subcargo=str_replace(".","",$li_subcargo);
			$li_subcargo=str_replace(",",".",$li_subcargo);
			$ls_sql="INSERT INTO ".$as_tabla." (codemp, numsol, ".$as_campo.", codcar, monbasimp, monimp, monto, formula,".
					"                           spg_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, estcla )".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codart."','".$ls_codcar."',".
					" 			  ".$li_bascar.",".$li_moncar.",".$li_subcargo.",'".$ls_formulacargo."','".$ls_codspgcre."',".
					"             '".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."','".$ls_codestpro4."',".
					"             '".$ls_codestpro5."','".$ls_statuscre."')";  
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el Cargo ".$ls_codart." a la SEP ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}
		}
		return $lb_valido;
	}// end function uf_insert_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas($as_numsol,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   ai_totrowcuentas  // Total de Filas de las cuentas Presupuestarias
		//				   ai_totrowcuentascargo  // Total de Filas de las cuentas Presupuestarias del Cargo
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<$ai_totrowcuentas)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodprogas".$li_i];
			$ls_estcla=$_POST["txtestclagas".$li_i];
			$ls_cuenta=$_POST["txtcuentagas".$li_i];
			$li_moncue=$_POST["txtmoncuegas".$li_i];
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);	
			$this->io_dscuentas->insertRow("codestpro",$ls_codpro);	
			$this->io_dscuentas->insertRow("estcla",$ls_estcla);
			$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
			$this->io_dscuentas->insertRow("moncue",$li_moncue);			
		}
		for($li_i=1;($li_i<$ai_totrowcuentascargo)&&($lb_valido);$li_i++)
		{
			$ls_codpro=$_POST["txtcodprocar".$li_i];
			$ls_cuenta=$_POST["txtcuentacar".$li_i];
			$ls_estcla=$_POST["estclacar".$li_i];
			$li_moncue=$_POST["txtmoncuecar".$li_i];
			$li_moncue=str_replace(".","",$li_moncue);
			$li_moncue=str_replace(",",".",$li_moncue);			
			$this->io_dscuentas->insertRow("codestpro",$ls_codpro);	
			$this->io_dscuentas->insertRow("estcla",$ls_estcla);
			$this->io_dscuentas->insertRow("cuenta",$ls_cuenta);			
			$this->io_dscuentas->insertRow("moncue",$li_moncue);			
		}
		$this->io_dscuentas->group_by(array('0'=>'codestpro','1'=>'cuenta'),array('0'=>'moncue'),'moncue');
		$li_total=$this->io_dscuentas->getRowCount('codestpro');	
		for($li_fila=1;$li_fila<=$li_total;$li_fila++)
		{
			$ls_codpro=$this->io_dscuentas->getValue('codestpro',$li_fila);
			$ls_estcla=$this->io_dscuentas->getValue('estcla',$li_fila);
			$ls_cuenta=$this->io_dscuentas->getValue('cuenta',$li_fila);
			$li_moncue=$this->io_dscuentas->getValue('moncue',$li_fila);
			$ls_codestpro1=substr($ls_codpro,0,25);
			$ls_codestpro2=substr($ls_codpro,25,25);
			$ls_codestpro3=substr($ls_codpro,50,25);
			$ls_codestpro4=substr($ls_codpro,75,25);
			$ls_codestpro5=substr($ls_codpro,100,25);
			$ls_sql="INSERT INTO sep_cuentagasto (codemp, numsol, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, ".
					"							  spg_cuenta, monto,estcla)".
					"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codestpro1."','".$ls_codestpro2."',".
					" 			  '".$ls_codestpro3."','".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."',".$li_moncue.",'".$ls_estcla."')";        
			$li_row=$this->io_sql->execute($ls_sql); 
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			    echo $this->io_sql->message;
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codpro." a la SEP ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
		}
		return $lb_valido;
	}// end function uf_insert_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_cuentas_cargos($as_numsol,$ai_totrowcuentascargo,$ai_totrowcargos,$as_codprov,$as_cedben,$as_estcla,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_cuentas_cargos
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   ai_totrowcuentascargo  // Total de Filas de las cuentas Presupuestarias del Cargo
		//				   ai_totrowcargos  // Total de Filas de los Cargos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta las cuentas de los cargos de una Solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		for($li_i=1;($li_i<=$ai_totrowcargos)&&($lb_valido);$li_i++)
		{
			$ls_codcar=$_POST["txtcodcar".$li_i];
			$li_bascar=$_POST["txtbascar".$li_i];
			$li_moncar=$_POST["txtmoncar".$li_i];
			$ls_formulacargo=$_POST["formulacargo".$li_i];			
			$li_bascar=str_replace(".","",$li_bascar);
			$li_bascar=str_replace(",",".",$li_bascar);			
			$li_moncar=str_replace(".","",$li_moncar);
			$li_moncar=str_replace(",",".",$li_moncar);
			$this->io_dscargos->insertRow("codcar",$ls_codcar);	
			$this->io_dscargos->insertRow("monobjret",$li_bascar);	
			$this->io_dscargos->insertRow("monret",$li_moncar);	
			$this->io_dscargos->insertRow("formula",$ls_formulacargo);	
		}
		$this->io_dscargos->group_by(array('0'=>'codcar'),array('0'=>'monobjret','1'=>'monret'),'monobjret');
		$ls_tipafeiva = $_SESSION["la_empresa"]["confiva"];		
		if ($ls_tipafeiva=='P')
		{
			for($li_i=1;($li_i<$ai_totrowcuentascargo)&&($lb_valido);$li_i++)
			{
				$ls_codcargo=$_POST["txtcodcargo".$li_i];
				$ls_codpro=$_POST["txtcodprocar".$li_i];
				$ls_cuenta=$_POST["txtcuentacar".$li_i];
				$ls_estcla=$_POST["estclacar".$li_i];
				$li_moncue=$_POST["txtmoncuecar".$li_i];
				$li_row=$this->io_dscargos->find("codcar",$ls_codcargo);		
				$li_monobjret=$this->io_dscargos->getValue("monobjret",$li_row);
				$li_monret=$this->io_dscargos->getValue("monret",$li_row);
				$ls_formula=$this->io_dscargos->getValue("formula",$li_row);	
	
				$ls_codestpro1=substr($ls_codpro,0,25);
				$ls_codestpro2=substr($ls_codpro,25,25);
				$ls_codestpro3=substr($ls_codpro,50,25);
				$ls_codestpro4=substr($ls_codpro,75,25);
				$ls_codestpro5=substr($ls_codpro,100,25);
				$li_moncue=str_replace(".","",$li_moncue);
				$li_moncue=str_replace(",",".",$li_moncue);		
				$ls_sccuenta=""; 
				$lb_valido=$this->uf_select_cuentacontable($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,
														   $ls_cuenta,$ls_estcla,&$ls_sccuenta);
				if($lb_valido)
				{
					$ls_sql="INSERT INTO sep_solicitudcargos (codemp, numsol, codcar, monobjret, monret, cod_pro, ced_bene, codestpro1,".
							"                                 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta, ".
							"								  formula, monto,estcla)".
							"	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcargo."',".$li_monobjret.",".$li_monret.",".
							"			  '".$as_codprov."','".$as_cedben."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',".
							" 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_cuenta."','".$ls_sccuenta."','".$ls_formula."',".
							"			   ".$li_moncue.",'".$ls_estcla."')";   
					$li_row=$this->io_sql->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_cuentas_cargos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					}
					else
					{
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó la Cuenta ".$ls_cuenta." de programatica ".$ls_codpro." a los cargos ".$as_numsol. " Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					}
				}
				else
				{
					$this->io_mensajes->message("ERROR-> La cuenta Presupuestaria ".$ls_cuenta." No tiene cuenta contable asociada."); 
				}
			}//fin del for
		}
		elseif($ls_tipafeiva=='C')
		{
			$li_totrowcre = $this->io_dscargos->getRowCount("codcar");
			 for ($li_i=1;$li_i<=$li_totrowcre;$li_i++)
			     {
				   $ls_codcargo	  = $_POST["txtcodcar".$li_i];
				   $li_row        = $this->io_dscargos->find("codcar",$ls_codcargo);		
				   $ld_monobjret  = $this->io_dscargos->getValue("monobjret",$li_row);
				   $ld_monret     = $this->io_dscargos->getValue("monret",$li_row);
				   $ls_formula    = $this->io_dscargos->getValue("formula",$li_row);
				   $ls_codestpro1 = '-------------------------';
				   $ls_codestpro2 = '-------------------------';
				   $ls_codestpro3 = '-------------------------';
				   $ls_codestpro4 = '-------------------------';
				   $ls_codestpro5 = '-------------------------';
				   $ls_estcla     = '-';
				   $ls_scgcta     = $_POST["cuentacargo".$li_i];
				   
				   $ls_sql="INSERT INTO sep_solicitudcargos (codemp, numsol, codcar, monobjret, monret, cod_pro, ced_bene, codestpro1,".
						   "                                 codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, sc_cuenta, ".
						   "							     formula, monto, estcla)".
						   "	  VALUES ('".$this->ls_codemp."','".$as_numsol."','".$ls_codcargo."',".$ld_monobjret.",".$ld_monret.",".
						   "			  '".$as_codprov."','".$as_cedben."','".$ls_codestpro1."','".$ls_codestpro2."','".$ls_codestpro3."',".
						   " 			  '".$ls_codestpro4."','".$ls_codestpro5."','".$ls_scgcta."','".$ls_scgcta."','".$ls_formula."',".
						   "			   ".$ld_monret.",'".$ls_estcla."')";        
				   $rs_data=$this->io_sql->execute($ls_sql);
				   if ($li_row===false)
				      {
						$lb_valido = false;
				        $this->io_mensajes->message("CLASE->sigesp_sep_c_solicitud.php;MÉTODO->uf_insert_cargos (IVA Contable) ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				      }
				   else
					  {
					    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="INSERT";
						$ls_descripcion ="Insertó la Cuenta ".$ls_scgcta." al Cargo ".$ls_codcargo." para la SEP : ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
						$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
									$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
							 		$aa_seguridad["ventanas"],$ls_descripcion);
					    /////////////////////////////////         SEGURIDAD               /////////////////////////////		
					  }
				 }	//fin del for	   
		}// fin de elseif($ls_tipafeiva=='C')
		return $lb_valido;
	}// end function uf_insert_cuentas_cargos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_solicitud($as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprov,$as_cedben,$as_consol,$as_codtipsol,
							     $ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
								 $as_codestpro5,$as_estcla,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$as_tabla,
								 $as_campo,$ai_totrowservicios,$ai_totrowconceptos,$ls_nombenalt,$as_tipsepbie,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_solicitud
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codfuefin  // Código de Fuente de financiamiento
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codprov  // Código de Proveedor 
		//				   as_cedben  // Código de Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_subtotal  // Subtotal de la solicitu
		//				   ai_cargos  // Monto del cargo
		//				   ai_total  // Total de la solicitud
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas Cargos
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   as_campo  // Campo donde se inserta el codigo del Bien, Servicio ó Concepto
		//                 nombenalt// nombre del benficiario alterno de emision de cheques.
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que actualiza la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación :20/08/08
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="UPDATE sep_solicitud ".
				"   SET coduniadm = '".$as_coduniadm."',      ".
				"		tipo_destino = '".$as_tipodestino."', ".
				"		cod_pro	= '".$as_codprov."',          ".
				"		ced_bene = '".$as_cedben."',          ".
				"		consol = '".$as_consol."',            ".
				"		codfuefin = '".$as_codfuefin."',      ".
				"		monto = ".$ai_total.",                ".
				"		monbasinm = ".$ai_subtotal.",         ".
				"		montotcar = ".$ai_cargos.",           ".
				"		tipsepbie = 'M',                      ".
				"       nombenalt='".$ls_nombenalt."'         ".
				" WHERE codemp = '".$this->ls_codemp."'       ".
				"	AND numsol = '".$as_numsol."' "; 
		$this->io_sql->begin_transaction();				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_update_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			if($lb_valido)
			{
				$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
			}	
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_bienes($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												   $as_codestpro5,$as_estcla,$ai_totrowbienes,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_conceptos($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												  	  $as_codestpro5, $as_estcla,$ai_totrowconceptos,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_servicios($as_numsol,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
												  	 $as_codestpro5,$as_estcla,$ai_totrowservicios,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cargos($as_numsol,$ai_totrowcargos,$as_tabla,$as_campo,$aa_seguridad);
			}			
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentas($as_numsol,$ai_totrowcuentas,$ai_totrowcuentascargo,$aa_seguridad);
			}		
			if($lb_valido)
			{	
				$lb_valido=$this->uf_insert_cuentas_cargos($as_numsol,$ai_totrowcuentascargo,$ai_totrowcargos,$as_codprov,$as_cedben,$as_estcla,$aa_seguridad);
			}		
			if($lb_valido)
			{
				$this->io_mensajes->message("La Solicitud fue actualizada.");
				$this->io_sql->commit();
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("Ocurrio un Error al Actualizar la Solicitud."); 
				$this->io_sql->rollback();
			}
		}
		return $lb_valido;
	}// end function uf_update_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_detalles($as_numsol,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_detalles
		//		   Access: private
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   as_tabla  // Tabla donde se deben insertar los cargos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina los detalles de una solicitud
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM sep_dta_cargos ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND numsol = '".$as_numsol."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dtc_cargos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dts_cargos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_cuentagasto ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_solicitudcargos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dt_articulos ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dt_concepto ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_dt_servicio ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"   AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_detalles ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			}
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó todos los detalles de la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////	
		}
		return $lb_valido;
	}// end function uf_update_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas($as_numsol,&$as_estsol,$as_operacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numsol // Número de solicitud
		//				   as_estsol  // Estatus de la solicitud
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		global $as_pathaux;
		require_once($as_pathaux."shared/class_folder/class_sigesp_int.php");
		require_once($as_pathaux."shared/class_folder/class_sigesp_int_scg.php");
		require_once($as_pathaux."shared/class_folder/class_sigesp_int_spg.php");
		$io_intspg=new class_sigesp_int_spg();		
		$ls_sql="SELECT codestpro1, codestpro2, codestpro3, codestpro4, codestpro5,estcla, TRIM(spg_cuenta) AS spg_cuenta, monto, ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sep_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = sep_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sep_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sep_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sep_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sep_cuentagasto.codestpro5 ".
				"           AND spg_cuentas.estcla=sep_cuentagasto.estcla".
				"			AND spg_cuentas.spg_cuenta = sep_cuentagasto.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sep_cuentagasto.codemp ".
				"			AND spg_cuentas.codestpro1 = sep_cuentagasto.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sep_cuentagasto.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sep_cuentagasto.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sep_cuentagasto.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sep_cuentagasto.codestpro5 ".
				"           AND spg_cuentas.estcla=sep_cuentagasto.estcla".
				"			AND spg_cuentas.spg_cuenta = sep_cuentagasto.spg_cuenta) AS existe ".		
				"  FROM sep_cuentagasto  ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND numsol='".$as_numsol."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$lb_existe=true;
			while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_existe))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_estcla=$row["estcla"];
				$ls_spg_cuenta=$row["spg_cuenta"];
				$li_monto=$row["monto"];
				$li_existe=$row["existe"];
				$estprog[0]=$row["codestpro1"];
				$estprog[1]=$row["codestpro2"];
				$estprog[2]=$row["codestpro3"];
				$estprog[3]=$row["codestpro4"];
				$estprog[4]=$row["codestpro5"];
				$estprog[5]=$row["estcla"];
				$lb_valido=$io_intspg->uf_spg_saldo_select($this->ls_codemp, $estprog, $ls_spg_cuenta, &$ls_status, &$adec_asignado, 
				                                           &$adec_aumento,&$adec_disminucion,&$adec_precomprometido,
													   	   &$adec_comprometido,&$adec_causado,&$adec_pagado);
				$li_disponibilidad=($adec_asignado-($adec_comprometido+$adec_precomprometido)+$adec_aumento-$adec_disminucion);
			 	if($li_existe>0)
				{
					$li_monto=number_format($li_monto,2,".","");
					$li_disponibilidad=number_format($li_disponibilidad,2,".","");
					if($li_monto>$li_disponibilidad)
					{
						$li_monto=number_format($li_monto,2,",",".");
						$li_disponibilidad=number_format($li_disponibilidad,2,",",".");
						if($as_operacion!='S')
						{
							$this->io_mensajes->message("No hay Disponibilidad en la cuenta ".$ls_spg_cuenta." Disponible=[".$li_disponibilidad."] Cuenta=[".$li_monto."]"); 
						}							
					}
				}
				else
				{
					$lb_existe = false;
					$this->io_mensajes->message("La cuenta ".$ls_spg_cuenta." No Existe en la Estructura ".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.""); 
				}
			}
			$this->io_sql->free_result($rs_data);	
			if($lb_existe)
			{
				$as_estsol="E";
			}
			else
			{
				$as_estsol="R";
			}
			$ls_sql="UPDATE sep_solicitud ".
					"   SET estsol='".$as_estsol."' ".
					" WHERE codemp = '".$this->ls_codemp."'".
					"	AND numsol = '".$as_numsol."' ";
			$this->io_sql->begin_transaction();				
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_cuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();			
			}
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_guardar($as_existe,$ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,$as_codprovben,$as_consol,
						$as_codtipsol,$ai_subtotal,$ai_cargos,$ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
						$as_codestpro5,$as_estcla,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,$ai_totrowcuentascargo,$ai_totrowservicios,
						$ai_totrowconceptos,$ls_nombenalt,$aa_seguridad,&$as_estsol,$as_tipsepbie,$as_permisosadministrador)
	{		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_guardar
		//		   Access: public (sigesp_sep_p_solicitud.php)
		//	    Arguments: ad_fecregsol  // Fecha de Solicitud
		//				   as_numsol  // Número de Solicitud 
		//				   as_coduniadm  // Codigo de Unidad Administrativa
		//				   as_codfuefin  // Código de Fuente de financiamiento
		//				   as_tipodestino  // Tipo de Destino
		//				   as_codprovben  // Código de Proveedor / Beneficiario
		//				   as_consol  // Concepto de la Solicitud
		//				   as_codtipsol  // Código Tipo de solicitud
		//				   ai_subtotal  // Subtotal de la solicitu
		//				   ai_cargos  // Monto del cargo
		//				   ai_total  // Total de la solicitud
		//				   as_codestpro1  // Código Estructura Programática 1
		//				   as_codestpro2  // Código Estructura Programática 2
		//				   as_codestpro3  // Código Estructura Programática 3
		//				   as_codestpro4  // Código Estructura Programática 4
		//				   as_codestpro5  // Código Estructura Programática 5
		//				   ai_totrowbienes  // Total de Filas de Bienes
		//				   ai_totrowcargos  // Total de Filas de Servicios
		//				   ai_totrowcuentas  // Total de Filas de Cuentas
		//				   ai_totrowcuentascargo  // Total de Filas de Cuentas de los cargos
		//				   ai_totrowservicios  // Total de Filas de Servicios
		//				   ai_totrowconceptos  // Total de Filas de Conceptos
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//				   as_estsol  // Estatus de la solicitud
		//	      Returns: lb_valido True si se ejecuto el guardar ó False si hubo error en el guardar
		//	  Description: Funcion que valida y guarda la sep
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;	
		$lb_encontrado=$this->uf_select_solicitud($as_numsol);
		$ai_subtotal=str_replace(".","",$ai_subtotal);
		$ai_subtotal=str_replace(",",".",$ai_subtotal);
		$ai_cargos=str_replace(".","",$ai_cargos);
		$ai_cargos=str_replace(",",".",$ai_cargos);
		$ai_total=str_replace(".","",$ai_total);
		$ai_total=str_replace(",",".",$ai_total);
		$ls_operacion=substr($as_codtipsol,5,1);
		$ls_codtipsol=substr($as_codtipsol,0,2);
		$ls_tipo=substr($as_codtipsol,3,1);
		$as_codestpro1=str_pad($as_codestpro1,25,'0',0);
		$as_codestpro2=str_pad($as_codestpro2,25,'0',0);
		$as_codestpro3=str_pad($as_codestpro3,25,'0',0);
		$as_codestpro4=str_pad($as_codestpro4,25,'0',0);
		$as_codestpro5=str_pad($as_codestpro5,25,'0',0);
		
		switch($ls_tipo)
		{
			case "B": // si es de Bienes
				$ls_tabla="sep_dta_cargos";
				$ls_campo="codart";
				break;
			case "S": // si es de Servicios
				$ls_tabla="sep_dts_cargos";
				$ls_campo="codser";
				break;
			case "O": // si es de Conceptos
				$ls_tabla="sep_dtc_cargos";
				$ls_campo="codconsep";
				break;
		}
		$ad_fecregsol=$this->io_funciones->uf_convertirdatetobd($ad_fecregsol);
		$_SESSION["fechacomprobante"]=$ad_fecregsol;
		$ls_codprov="----------";
		$ls_cedben="----------";
		if($as_tipodestino=="P")
		{
			$ls_codprov=$as_codprovben;
		}
		if($as_tipodestino=="B")
		{
			$ls_cedben=$as_codprovben;
		}
		switch ($as_existe)
		{
			case "FALSE":
				//if(!($lb_encontrado))
				//{
					/*if($as_permisosadministrador!=1)
					{
						$lb_valido=$this->uf_validar_fecha_sep($ad_fecregsol);
						if(!$lb_valido)
						{
							$this->io_mensajes->message("La Fecha de esta Solicitud es menor a la fecha de la Solicitud anterior.");
							return false;
						}
					}*/
					$lb_valido=$this->io_fecha->uf_valida_fecha_periodo($ad_fecregsol,$this->ls_codemp);
					if (!$lb_valido)
					{
						$this->io_mensajes->message($this->io_fecha->is_msg_error);           
						return false;
					}                    
					$lb_valido=$this->uf_insert_solicitud($ad_fecregsol,&$as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,
														  $ls_codprov,$ls_cedben,$as_consol,$ls_codtipsol,$ai_subtotal,$ai_cargos,
														  $ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$as_estcla,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,
														  $ai_totrowcuentascargo,$ls_tabla,$ls_campo,$ai_totrowservicios,
														  $ai_totrowconceptos,$ls_nombenalt,$as_tipsepbie,$aa_seguridad);
				//}
				break;

			case "TRUE":
				if($lb_encontrado)
				{
					$lb_valido=$this->uf_update_solicitud($as_numsol,$as_coduniadm,$as_codfuefin,$as_tipodestino,
														  $ls_codprov,$ls_cedben,$as_consol,$ls_codtipsol,$ai_subtotal,$ai_cargos,
														  $ai_total,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
														  $as_codestpro5,$as_estcla,$ai_totrowbienes,$ai_totrowcargos,$ai_totrowcuentas,
														  $ai_totrowcuentascargo,$ls_tabla,$ls_campo,$ai_totrowservicios,
														  $ai_totrowconceptos,$ls_nombenalt,$as_tipsepbie,$aa_seguridad);
				}
				else
				{
					$this->io_mensajes->message("La Solicitud no existe, no la puede actualizar.");
				}
				break;
		}
		if($lb_valido)
		{
			$lb_valido=$this->uf_validar_cuentas($as_numsol,&$as_estsol,$ls_operacion);
		}
		unset($_SESSION["fechacomprobante"]);
		return $lb_valido;
	}// end function uf_guardar
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_solicitud($as_numsol,$aa_seguridad,$la_permisoadministrador)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_solicitud
		//		   Access: public
		//	    Arguments: as_numsol  // Número de Solicitud 
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que elimina la solicitud de Ejecución Presupuestaria
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$lb_existe=$this->uf_validar_despacho($as_numsol);
		
		if(!$lb_existe)
		{
			$lb_valido=$this->uf_delete_detalles($as_numsol,$aa_seguridad);
		}
		else
		{
			$lb_valido=false;
		}
		if($lb_valido)
		{
			$ls_sql="DELETE FROM sep_solicitud ".
					" WHERE codemp = '".$this->ls_codemp."' ".
					"	AND numsol = '".$as_numsol."' ";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_delete_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$this->io_sql->rollback();
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="DELETE";
				$ls_descripcion ="Elimino la solicitud ".$as_numsol." Asociado a la empresa ".$this->ls_codemp;
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
				if($lb_valido)
				{	
					$this->io_mensajes->message("La Solicitud fue Eliminada.");
					$this->io_sql->commit();
				}
				else
				{
					$lb_valido=false;
					$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
					$this->io_sql->rollback();
				}
			}
		}
		else
		{
			$this->io_mensajes->message("Ocurrio un Error al Eliminar la Solicitud."); 
			$this->io_sql->rollback();
		}
		return $lb_valido;
	}// end function uf_delete_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------
    
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Yesenia Moreno   
		// Modificado por: Ing. Yozelin Barragan            
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 10/04/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=false;
		$ls_sql="SELECT * ".
	   		    "  FROM sigesp_config ".
			    " WHERE codemp='".$this->ls_codemp."' ".
			    "   AND codsis='SEP' ".
			    "   AND seccion='RELEASE' ".
			    "   AND entry='VALIDACION-PRESUPUESTARIA-FONCREI' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true; 
			}
		}
		return rtrim($lb_valido);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
				"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->SNO MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			$this->io_sql->commit();
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_validar_cambio_imputacion()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cambio_imputacion
		//		   Access: private
		//	    Arguments:
		// 	      Returns: retorna el valor del campo estmodpartsep
		//	  Description: Funcion que verifica si se permitira o no cambiar la imputaciòn presupuestaria
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 09/10/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$lb_estmodpartsep=0;
		$ls_sql="SELECT estmodpartsep  FROM sigesp_empresa  WHERE codemp='".$this->ls_codemp."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_cambio_imputacion ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_estmodpartsep=$row["estmodpartsep"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_estmodpartsep;
	}// end function uf_select_solicitud
	//------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_validar_despacho($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_despacho
		//		   Access: private
		//	    Arguments: $as_numsol  // Numero de SEP
		// 	      Returns: retorna el valor del campo estmodpartsep
		//	  Description: Funcion que verifica si existe un despacho asociado a esta sep
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2009								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT numorddes".
				"  FROM siv_despacho".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND numsol='".$as_numsol."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_validar_despacho ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numorddes=$row["numorddes"];
				$this->io_mensajes->message("La SEP esta asociada a un Movimiento de Inventario. No puede ser Eliminada.");
				$lb_existe=true;
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_select_solicitud
//------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_cuenta_unidad($as_unidad, $as_cuenta)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_buscar_cuenta_unidad
		//		   Access: private
		//	    Arguments:
		// 	      Returns: retorna el valor del campo estmodpartsep
		//	  Description: Funcion que busca si la cuenta esta asociada alguna estructura de la unidad Ejecutora
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 12/02/2009								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=true;
		$lb_valor=0;
		$ls_sql="	select  count(*) as valor ".
				"	  from spg_cuentas,spg_dt_unidadadministrativa ".
				"	 where spg_cuentas.spg_cuenta='".$as_cuenta."' ".
				"	   and spg_dt_unidadadministrativa.coduniadm='".$as_unidad."' ".
				"	   and spg_dt_unidadadministrativa.codemp=spg_cuentas.codemp ".
				"	   and spg_dt_unidadadministrativa.codestpro1=spg_cuentas.codestpro1 ".
				"	   and spg_dt_unidadadministrativa.codestpro2=spg_cuentas.codestpro2 ".
				"	   and spg_dt_unidadadministrativa.codestpro3=spg_cuentas.codestpro3 ".
				"	   and spg_dt_unidadadministrativa.codestpro4=spg_cuentas.codestpro4 ".
				"	   and spg_dt_unidadadministrativa.codestpro5=spg_cuentas.codestpro5 ".
				"	   and spg_dt_unidadadministrativa.estcla=spg_cuentas.estcla "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_buscar_cuenta_unidad ERROR->".
			                            $this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valor=$row["valor"];
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valor;
	}// end uf_buscar_cuenta_unidad
//---------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_verificar_solicitud_eliminar($as_numsol)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_verificar_solicitud_eliminar
		//		   Access: private
		//	    Arguments: as_numordcom  --->  Número de la orden de compra
		//                 $as_estcondat --->  Estatus de la orden de compra
		// 	      Returns: true si se existe la orden de compra o false en caso contrario
		//	  Description: Funcion que verifica si existe una orden de compra
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2009 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
	   switch ($_SESSION["ls_gestor"])
	   {
	   		case "INFORMIX":
				$ls_sql="SELECT LIMIT 1 numsol ".
						"  FROM sep_solicitud ".
						" WHERE codemp='".$this->ls_codemp."' ".
						" ORDER BY numsol DESC ";
			break;
			
			default: // MYSQLT POSTGRES
				$ls_sql="SELECT numsol ".
						"  FROM sep_solicitud ".
						" WHERE codemp='".$this->ls_codemp."'".
						" ORDER BY numsol DESC LIMIT 1";
			break;
	   }
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_verificar_solicitud_eliminar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numsol=$row["numsol"];
				if($ls_numsol==$as_numsol)
				{
					$lb_existe=true;
				}
			}
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_existe;
	}// end function uf_verificar_solicitud_eliminar
//-----------------------------------------------------------------------------------------------------------------------------------	

}// fin de la clase sigesp_sep_c_solicitud
?>