<?php
class sigesp_spg_c_mod_programado
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;	
	var $io_msg;
	var $io_function;
	var $is_codemp;	
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
	
	function sigesp_spg_c_mod_programado()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");	
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");
		  
		$this->io_function=new class_funciones();	
		$this->sig_int=new class_sigesp_int();
		$this->io_fecha=new class_fecha();
		$this->io_include=new sigesp_include();	
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();	
		$this->is_msg_error="";		
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->io_intspg= new class_sigesp_int_spg();
	}
	
	function uf_buscar_disponibilidad_mensual($as_mes1,$as_mes2,$coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, 
	                                          $as_cuenta, $as_monto,$as_fecha, $la_security)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf _buscar_disponibilidad_mensual
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
        $lb_valido=true;
		$ls_ano=$_SESSION["la_empresa"]["periodo"];
		$ls_ano=substr($ls_ano,0,4);
		$fechaIni=$ls_ano."-".str_pad($as_mes1,2,"0",0)."-01";
		$diames=$this->io_fecha->uf_last_day($as_mes1,$ls_ano);
		$fechaFinal=$diames;		
		$ls_fecfin=""; 
		$li_pos=strpos($fechaFinal,"/");
		$li_pos2=strpos($fechaFinal,"-");
		if(($li_pos==2)||($li_pos2==2))
		{
			 $ls_fecfin=(substr($fechaFinal,5,4)."-".str_pad(substr($fechaFinal,3,1),2,"0",0)."-".substr($fechaFinal,0,2)); 
		}
		$ls_aumento=$this->calcular_aumento_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5, 
		                                        $as_estcla,$as_cuenta);		
		$ls_disminucion=$this->calcular_disminucion_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                                                $as_estcla, $as_cuenta);		
		$ls_compometido1=$this->calcular_comp_cau_pag_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                                                  $as_estcla, $as_cuenta);		
		$ls_compometido2=$this->calcular_comp_gas_pag_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                                                  $as_estcla, $as_cuenta);		
		$ls_compometido3=$this->calcular_comp_simple_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5, 
		                                                 $as_estcla, $as_cuenta);		
		$ls_comprometido=0;
		$ls_comprometido=$ls_compometido1+$ls_compometido2+$ls_compometido3;
		$ls_programado=$this->uf_buscra_programado($as_mes1, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta);
		$ls_precompromiso=$this->calcular_pre_compromiso($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5, 
		                                                  $as_estcla, $as_cuenta);	
		$ls_disponible= (($ls_programado+$ls_aumento)-($ls_disminucion+$ls_comprometido+$ls_precompromiso));
			
		$as_monto    =str_replace(".","",$as_monto);
		$as_monto    =str_replace(",",".",$as_monto);		
		
	    if ($ls_disponible >= $as_monto)
		{
			$lb_valido=$this->uf_update_mes1($as_mes1, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta, 
			                                 $as_monto,$as_fecha, $la_security);
			if ($lb_valido)
			{
				$lb_valido=$this->uf_update_mes2($as_mes2, $coest1, $coest2, $coest3, $coest4, $coest5,$as_estcla,  $as_cuenta,
				                      $as_monto,$as_fecha, $la_security);
				if ($lb_valido)
				{
				  $aa_estpro[0]=$as_estcla;
				  $aa_estpro[1]=$coest1;
				  $aa_estpro[2]=$coest2;
				  $aa_estpro[3]=$coest3;
				  $aa_estpro[4]=$coest4;
				  $aa_estpro[5]=$coest5;
				  $this->uf_guardar_regmodificacion($aa_estpro,$as_cuenta,$as_monto,$as_mes2,$as_mes1,$la_security);
				}					  
			}
		}
		else
		{
			$this->io_msg->message("El Mes No Posee Disponibilidad suficiente");
			$lb_valido=false;
		}
		return $lb_valido;
	}// fin de la funcion uf _buscar_disponibilidad_mensual
/////------------------------------------------------------------------------------------------------------------------------------------------	
	function calcular_aumento_mes($as_fecha1, $as_fecha2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  calcular_aumento_mes
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $lb_valido = true;
	    $ls_sql="   SELECT COALESCE(SUM(monto),0) As monto    ".
				"	  FROM spg_dt_cmp PCT,spg_operaciones O   ".
				"	 where PCT.operacion=O.operacion          ".
				"	   and PCT.fecha between '".$as_fecha1."' and '".$as_fecha2."' ".
				"	   AND PCT.codestpro1='".$coest1."' ".
				"	   and PCT.codestpro2='".$coest2."' ".
				"	   and PCT.codestpro3='".$coest3."' ".
				"	   and PCT.codestpro4='".$coest4."'  ".
				"	   and PCT.codestpro5='".$coest5."'  ".
				"	   and PCT.spg_cuenta='".$as_cuenta."' ".
				"      and PCT.estcla='".$as_estcla."'".
				"	   and PCT.operacion='AU'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo calcular_aumento_mes".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_monto;
	}// fin de calcular_aumento_mes()
///-------------------------------------------------------------------------------------------------------------------------------------
	function calcular_disminucion_mes($as_fecha1, $as_fecha2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  calcular_disminucion_mes
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $lb_valido = true;
	    $ls_sql="   SELECT COALESCE(SUM(monto),0) As monto    ".
				"	  FROM spg_dt_cmp PCT,spg_operaciones O   ".
				"	 where PCT.operacion=O.operacion          ".
				"	   and PCT.fecha between '".$as_fecha1."' and '".$as_fecha2."' ".
				"	   AND PCT.codestpro1='".$coest1."' ".
				"	   and PCT.codestpro2='".$coest2."' ".
				"	   and PCT.codestpro3='".$coest3."' ".
				"	   and PCT.codestpro4='".$coest4."'  ".
				"	   and PCT.codestpro5='".$coest5."'  ".
				"	   and PCT.spg_cuenta='".$as_cuenta."' ".
				"      and PCT.estcla='".$as_estcla."'".
				"	   and PCT.operacion='DI'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo calcular_disminucion_mes".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_monto;
	}// fin de calcular_disminucion_mes
///--------------------------------------------------------------------------------------------------------------------------------------
///----------------------------------------------------------------------------------------------------------------------------------------
	function calcular_comp_cau_pag_mes($as_fecha1, $as_fecha2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  calcular_comp_cau_pag_mes
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $lb_valido = true;
	    $ls_sql="   SELECT COALESCE(SUM(monto),0) As monto    ".
				"	  FROM spg_dt_cmp PCT,spg_operaciones O   ".
				"	 where PCT.operacion=O.operacion          ".
				"	   and PCT.fecha between '".$as_fecha1."' and '".$as_fecha2."' ".
				"	   AND PCT.codestpro1='".$coest1."' ".
				"	   and PCT.codestpro2='".$coest2."' ".
				"	   and PCT.codestpro3='".$coest3."' ".
				"	   and PCT.codestpro4='".$coest4."'  ".
				"	   and PCT.codestpro5='".$coest5."'  ".
				"	   and PCT.spg_cuenta='".$as_cuenta."' ".
				"      and PCT.estcla='".$as_estcla."'".
				"	   and PCT.operacion='CCP'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo calcular_comp_cau_pag_mes".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_monto;
	}// fin de calcular_comp_cau_pag_mes
////-------------------------------------------------------------------------------------------------------------------------------------
 	function calcular_comp_gas_pag_mes($as_fecha1, $as_fecha2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  calcular_comp_cau_pag_mes
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $lb_valido = true;
	    $ls_sql="   SELECT COALESCE(SUM(monto),0) As monto    ".
				"	  FROM spg_dt_cmp PCT,spg_operaciones O   ".
				"	 where PCT.operacion=O.operacion          ".
				"	   and PCT.fecha between '".$as_fecha1."' and '".$as_fecha2."' ".
				"	   AND PCT.codestpro1='".$coest1."' ".
				"	   and PCT.codestpro2='".$coest2."' ".
				"	   and PCT.codestpro3='".$coest3."' ".
				"	   and PCT.codestpro4='".$coest4."'  ".
				"	   and PCT.codestpro5='".$coest5."'  ".
				"	   and PCT.spg_cuenta='".$as_cuenta."' ".
				"      and PCT.estcla='".$as_estcla."'".
				"	   and PCT.operacion='CG'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo calcular_comp_gas_pag_mes".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_monto;
	}// fin de calcular_comp_gas_pag_mes
///------------------------------------------------------------------------------------------------------------------------------------------
////-------------------------------------------------------------------------------------------------------------------------------------
   function calcular_comp_simple_mes($as_fecha1, $as_fecha2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  calcular_comp_simple_mes
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $lb_valido = true;
	    $ls_sql="   SELECT COALESCE(SUM(monto),0) As monto    ".
				"	  FROM spg_dt_cmp PCT,spg_operaciones O   ".
				"	 where PCT.operacion=O.operacion          ".
				"	   and PCT.fecha between '".$as_fecha1."' and '".$as_fecha2."' ".
				"	   AND PCT.codestpro1='".$coest1."' ".
				"	   and PCT.codestpro2='".$coest2."' ".
				"	   and PCT.codestpro3='".$coest3."' ".
				"	   and PCT.codestpro4='".$coest4."'  ".
				"	   and PCT.codestpro5='".$coest5."'  ".
				"	   and PCT.spg_cuenta='".$as_cuenta."' ".
				"      and PCT.estcla='".$as_estcla."'".
				"	   and PCT.operacion='CS'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo calcular_comp_simple_mes".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_monto;
	}// fin de calcular_comp_simple_mes
///--------------------------------------------------------------------------------------------------------------------------------------
    function calcular_pre_compromiso($as_fecha1, $as_fecha2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  calcular_pre_compromiso
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	   $lb_valido = true;
	    $ls_sql="   SELECT COALESCE(SUM(monto),0) As monto    ".
				"	  FROM spg_dt_cmp PCT,spg_operaciones O   ".
				"	 where PCT.operacion=O.operacion          ".
				"	   and PCT.fecha between '".$as_fecha1."' and '".$as_fecha2."' ".
				"	   AND PCT.codestpro1='".$coest1."' ".
				"	   and PCT.codestpro2='".$coest2."' ".
				"	   and PCT.codestpro3='".$coest3."' ".
				"	   and PCT.codestpro4='".$coest4."'  ".
				"	   and PCT.codestpro5='".$coest5."'  ".
				"	   and PCT.spg_cuenta='".$as_cuenta."' ".
				"      and PCT.estcla='".$as_estcla."'".
				"	   and PCT.operacion='PC'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo calcular_pre_compromiso".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_monto = $row["monto"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_monto;
	}// fin de calcular_pre_compromiso
////------------------------------------------------------------------------------------------------------------------------------------
   function uf_buscra_programado($as_mes, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
   {
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_buscra_programado
    //	  Access:  public
	// Arguments:  
	//	Returns:	 
	//	Description: 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = true;
		switch ($as_mes) 
		{
			case "1":
				$ls_mes="enero";
			break;
			case "2":
				$ls_mes="febrero";
			break;
			case "3":
				$ls_mes="marzo";
			break;
			case "4":
				$ls_mes="abril";
			break;
			case "5":
				$ls_mes="mayo";
			break;
			case "6":
				$ls_mes="junio";
			break;
			case "7":
				$ls_mes="julio";
			break;
			case "8":
				$ls_mes="agosto";
			break;
			case "9":
				$ls_mes="septiembre";
			break;
			case "10":
				$ls_mes="octubre";
			break;
			case "11":
				$ls_mes="noviembre";
			break;
			case "12":
				$ls_mes="diciembre";
			break;
		}		  
	    $ls_sql="   select ".$ls_mes." as montoprog from spg_cuentas   ".
				"		where codestpro1='".$coest1."'           ".
				"		  and codestpro2='".$coest2."'           ".
				"		  and codestpro3='".$coest3."'           ".
				"		  and codestpro4='".$coest4."'           ".
				"		  and codestpro5='".$coest5."'           ".
				"		  and spg_cuenta='".$as_cuenta."'        ".
				"         and estcla='".$as_estcla."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo uf_buscra_programado".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_montoprog = $row["montoprog"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_montoprog;
   }// fin de la funcion uf_buescra_programado
/////-------------------------------------------------------------------------------------------------------------------------------------
///-------------------------------------------------------------------------------------------------------------------------------------
   function uf_update_mes1($as_mes, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta, $as_monto,$as_fecha, $la_security)
   {   		
   	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_buscra_programado
    //	  Access:  public
	// Arguments:  
	//	Returns:	 
	//	Description: 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = true;
		 
		switch ($as_mes) 
		{
			case "1":
				$ls_mes="enero";
			break;
			case "2":
				$ls_mes="febrero";
			break;
			case "3":
				$ls_mes="marzo";
			break;
			case "4":
				$ls_mes="abril";
			break;
			case "5":
				$ls_mes="mayo";
			break;
			case "6":
				$ls_mes="junio";
			break;
			case "7":
				$ls_mes="julio";
			break;
			case "8":
				$ls_mes="agosto";
			break;
			case "9":
				$ls_mes="septiembre";
			break;
			case "10":
				$ls_mes="octubre";
			break;
			case "11":
				$ls_mes="noviembre";
			break;
			case "12":
				$ls_mes="diciembre";
			break;
		}
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		while(($li_nivel>=1)and($lb_valido)and($ls_nextcuenta!=""))
		{  
			$ls_sql="   update spg_cuentas ".
					"   set ".$ls_mes."= ".$ls_mes." - ".$as_monto.
					"   where codestpro1='".$coest1."' ".
					"   and  codestpro2='".$coest2."' ".
					"   and  codestpro3='".$coest3."' ".
					"   and  codestpro4='".$coest4."'  ".
					"   and  codestpro5='".$coest5."'  ".
					"   and  spg_cuenta='".$ls_nextcuenta."'".
					"   and  estcla='".$as_estcla."'";
					
			$rs_data=$this->io_sql->execute($ls_sql);
			if($rs_data===false)
			{   // error interno sql
				$this->io_msg->message("Error en uf_update_mes1".
									   $this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido = false;
			}
			else
			{
				$lb_valido = true;
				////////////////////////////////////////seguridad///////////////////////////////////////////////////////////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion =" La Estructura $coest1-$coest2-$coest3-$coest4-$coest5  con cuenta $as_cuenta se le disminuyo $as_monto Bs $ls_mes a la fehca del $as_fecha";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($la_security[1],
											$la_security[2],$ls_evento,$la_security[3],
											$la_security[4],$ls_descripcion);
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$this->io_sql->free_result($rs_data);
			}
			if($this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->io_intspg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		}
		return $lb_valido;
   }// fin de la funcion  
///-------------------------------------------------------------------------------------------------------------------------------------

   function uf_update_mes2($as_mes, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta, $as_monto,$as_fecha, $la_security)
   {   		
   	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_update_mes2
    //	  Access:  public
	// Arguments:  
	//	Returns:	 
	//	Description: 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = true;
		 
		switch ($as_mes) 
		{
			case "1":
				$ls_mes="enero";
			break;
			case "2":
				$ls_mes="febrero";
			break;
			case "3":
				$ls_mes="marzo";
			break;
			case "4":
				$ls_mes="abril";
			break;
			case "5":
				$ls_mes="mayo";
			break;
			case "6":
				$ls_mes="junio";
			break;
			case "7":
				$ls_mes="julio";
			break;
			case "8":
				$ls_mes="agosto";
			break;
			case "9":
				$ls_mes="septiembre";
			break;
			case "10":
				$ls_mes="octubre";
			break;
			case "11":
				$ls_mes="noviembre";
			break;
			case "12":
				$ls_mes="diciembre";
			break;
		}
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		while(($li_nivel>=1)and($lb_valido)and($ls_nextcuenta!=""))
		{  
			$ls_sql="   update spg_cuentas ".
					"   set ".$ls_mes."= ".$ls_mes." + ".$as_monto.
					"   where codestpro1='".$coest1."' ".
					"   and  codestpro2='".$coest2."' ".
					"   and  codestpro3='".$coest3."' ".
					"   and  codestpro4='".$coest4."'  ".
					"   and  codestpro5='".$coest5."'  ".
					"   and  spg_cuenta='".$ls_nextcuenta."'".
					"   and  estcla='".$as_estcla."'"; 
			$rs_data=$this->io_sql->execute($ls_sql);
			if($rs_data===false)
			{   // error interno sql
				$this->io_msg->message("Error en uf_update_mes1".
									   $this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido = false;
			}
			else
			{
				$lb_valido = true;
				////////////////////////////////////////seguridad///////////////////////////////////////////////////////////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion =" La Estructura $coest1-$coest2-$coest3-$coest4-$coest5  con cuenta $as_cuenta se le Aumento $as_monto Bs $ls_mes a la fecha del $as_fecha";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($la_security[1],
											$la_security[2],$ls_evento,$la_security[3],
											$la_security[4],$ls_descripcion);
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$this->io_sql->free_result($rs_data);
			}	
			if($this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->io_intspg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		}
		return $lb_valido;
   }// fin de la funcion  
///-------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_disponibilidad_trimestral($as_mes1,$as_mes2,$coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta, $as_monto,$as_fecha, $la_security)
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_buscar_disponibilidad_trimestral
    //	  Access:  public
	// Arguments:  
	//	Returns:	 la disponibilidad dados los meses desde y hasta;
	//	Description: la disponibilidad dados los meses desde y hasta;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
        $lb_valido=true;
		$ls_ano=$_SESSION["la_empresa"]["periodo"];
		$ls_ano=substr($ls_ano,0,4);
		switch ($as_mes1)// corresponde al trimestre a disminuir 
		{
			case "1":// trimestre desde enero hasta  Marzo
				$fechaIni=$ls_ano."-01-01";
				$diames=$this->io_fecha->uf_last_day(1,$ls_ano);
				$fechaFinal=$diames;	
			break;
			
			case "2":// trimestre desde Abril hasta  Junio
				$fechaIni=$ls_ano."-04-01";
				$diames=$this->io_fecha->uf_last_day(6,$ls_ano);
				$fechaFinal=$diames;	
			break;
			
			case "3":// trimestre desde Julio hasta  Septiembre
				$fechaIni=$ls_ano."-07-01";
				$diames=$this->io_fecha->uf_last_day(9,$ls_ano);
				$fechaFinal=$diames;	
			break;
			
			case "4":// trimestre desde Octubre hasta  Diciembre
				$fechaIni=$ls_ano."-10-01";
				$diames=$this->io_fecha->uf_last_day(12,$ls_ano);
				$fechaFinal=$diames;	
			break;
		}
				
		$fechaFinal=$diames;		
		$ls_fecfin=""; 
		$li_pos=strpos($fechaFinal,"/");
		$li_pos2=strpos($fechaFinal,"-");
		if(($li_pos==2)||($li_pos2==2))
		{
			 $ls_fecfin=(substr($fechaFinal,5,4)."-".str_pad(substr($fechaFinal,3,1),2,"0",0)."-".substr($fechaFinal,0,2)); 
		}
		$ls_aumento=$this->calcular_aumento_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5, 
		                                        $as_estcla, $as_cuenta);		
		$ls_disminucion=$this->calcular_disminucion_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                                        $as_estcla, $as_cuenta);		
		$ls_compometido1=$this->calcular_comp_cau_pag_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                                                  $as_estcla, $as_cuenta);		
		$ls_compometido2=$this->calcular_comp_gas_pag_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5, 
		                                                  $as_estcla, $as_cuenta);		
		$ls_compometido3=$this->calcular_comp_simple_mes($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                                                 $as_estcla, $as_cuenta);		
		$ls_comprometido=0;
		$ls_comprometido=$ls_compometido1+$ls_compometido2+$ls_compometido3;
		$ls_precompromiso=$this->calcular_pre_compromiso($fechaIni,$ls_fecfin, $coest1, $coest2, $coest3, $coest4, $coest5,
		                         $as_estcla, $as_cuenta);
		$ls_programado=$this->uf_buscar_programado_trimestral($as_mes1, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, 
		                                                      $as_cuenta);
		
		$ls_disponible= (($ls_programado+$ls_aumento)-($ls_disminucion+$ls_comprometido+$ls_precompromiso));	
		$as_monto    =str_replace(".","",$as_monto);
		$as_monto    =str_replace(",",".",$as_monto);			
		
	    if ($ls_disponible >= $as_monto)
		{
			$lb_valido=$this->uf_update_trimestral1($as_mes1, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, 
			                                        $as_cuenta, $as_monto,$as_fecha,$la_security);
			if ($lb_valido)
			{
				$lb_valido=$this->uf_update_trimestral2($as_mes2, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, 
				                             $as_cuenta, $as_monto, $as_fecha, $la_security);
											 
				if ($lb_valido)
				{
				  $aa_estpro[0]=$as_estcla;
				  $aa_estpro[1]=$coest1;
				  $aa_estpro[2]=$coest2;
				  $aa_estpro[3]=$coest3;
				  $aa_estpro[4]=$coest4;
				  $aa_estpro[5]=$coest5;
				  
				  switch($as_mes1)
				  {
				   case "1": $as_mes1="3";
				   break;
				   case "2": $as_mes1="6";
				   break;
				   case "3": $as_mes1="9";
				   break;
				   case "4": $as_mes1="12";
				   break;
				  }
				  
				  switch($as_mes2)
				  {
				   case "1": $as_mes2="3";
				   break;
				   case "2": $as_mes2="6";
				   break;
				   case "3": $as_mes2="9";
				   break;
				   case "4": $as_mes2="12";
				   break;
				  }
				  $this->uf_guardar_regmodificacion($aa_estpro,$as_cuenta,$as_monto,$as_mes2,$as_mes1,$la_security);
				}
			}
		}
		else
		{
			$this->io_msg->message("El trimestre No Posee Disponibilidad suficiente");
			$lb_valido=false;
		}
		return $lb_valido;
	}// fin de la funcion uf _buscar_disponibilidad_mensual
//----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_programado_trimestral($as_mes, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta)
   {
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_buscar_programado_trimestral
    //	  Access:  public
	// Arguments:  
	//	Returns:	 
	//	Description: 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = true;
		 
		switch ($as_mes) 
		{
			case "1":
				$ls_mes="marzo";
			break;
			case "2":
				$ls_mes="junio";
			break;
			case "3":
				$ls_mes="Septiembre";
			break;
			case "4":
				$ls_mes="Diciembre";
			break;			
		}
		 
		  
	    $ls_sql="   select ".$ls_mes." as montoprog from spg_cuentas   ".
				"		where codestpro1='".$coest1."'           ".
				"		  and codestpro2='".$coest2."'           ".
				"		  and codestpro3='".$coest3."'           ".
				"		  and codestpro4='".$coest4."'           ".
				"		  and codestpro5='".$coest5."'           ".
				"		  and spg_cuenta='".trim($as_cuenta)."'  ".
				"         and estcla='".$as_estcla."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo uf_buscar_programado_trimestral".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $ld_montoprog = $row["montoprog"];
			}
			$this->io_sql->free_result($rs_data);
		}
	    return $ld_montoprog;
   }// fin de la funcion uf_buescra_programado
//----------------------------------------------------------------------------------------------------------------------------------------
////------------------------------------------------------------------------------------------------------------------------------------
    function uf_update_trimestral1($as_mes, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta, 
	                               $as_monto,$as_fecha,  $la_security)
   {   		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_update_trimestral1
    //	  Access:  public
	// Arguments:  
	//	Returns:	 
	//	Description: 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = true;
		 
		switch ($as_mes) 
		{
			case "1":
				$ls_mes="marzo";
				$ls_trimestre="Enero - Marzo";
			break;
			case "2":
				$ls_mes="junio";
				$ls_trimestre="Abril - Junio";
			break;
			case "3":
				$ls_mes="Septiembre";
				$ls_trimestre="Julio - Septiembre";
			break;
			case "4":
				$ls_mes="Diciembre";
				$ls_trimestre="Octubre - Diciembre";
			break;		
		}
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		while(($li_nivel>=1)and($lb_valido)and($ls_nextcuenta!=""))
		{  
			$ls_sql="   update spg_cuentas ".
					"   set ".$ls_mes."= ".$ls_mes." - ".$as_monto.
					"   where codestpro1='".$coest1."' ".
					"   and  codestpro2='".$coest2."' ".
					"   and  codestpro3='".$coest3."' ".
					"   and  codestpro4='".$coest4."'  ".
					"   and  codestpro5='".$coest5."'  ".
					"   and  spg_cuenta='".trim($ls_nextcuenta)."'".
					"   and  estcla='".$as_estcla."'";  
					
			$rs_data=$this->io_sql->execute($ls_sql);
			if($rs_data===false)
			{   // error interno sql
				$this->io_msg->message("Error en uf_update_trimestral1".
									   $this->io_function->uf_convertirmsg($this->io_sql->message));
				$lb_valido = false;
			}
			else
			{
				$lb_valido = true;
				////////////////////////////////////////seguridad///////////////////////////////////////////////////////////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion =" La Estructura $coest1-$coest2-$coest3-$coest4-$coest5  con cuenta $as_cuenta se le disminuyo $as_monto Bs $ls_trimestre a la fehca del $as_fecha";
				$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($la_security[1],
											$la_security[2],$ls_evento,$la_security[3],
											$la_security[4],$ls_descripcion);
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				$this->io_sql->free_result($rs_data);
			}
			if($this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->io_intspg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		}
		return $lb_valido;
   }// fin de la funcion  
////-------------------------------------------------------------------------------------------------------------------------------------

///---------------------------------------------------------------------------------------------------------------------------------------
   function uf_update_trimestral2($as_mes, $coest1, $coest2, $coest3, $coest4, $coest5, $as_estcla, $as_cuenta, $as_monto, 
                                  $as_fecha,$la_security)
   {   		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_update_trimestral2
    //	  Access:  public
	// Arguments:  
	//	Returns:	 
	//	Description: 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
		$lb_valido = true;
		 
		switch ($as_mes) 
		{
			case "1":
				$ls_mes="marzo";
				$ls_trimestre="Enero - Marzo";
			break;
			case "2":
				$ls_mes="junio";
				$ls_trimestre="Abril - Junio";
			break;
			case "3":
				$ls_mes="Septiembre";
				$ls_trimestre="Julio - Septiembre";
			break;
			case "4":
				$ls_mes="Diciembre";
				$ls_trimestre="Octubre - Diciembre";
			break;		
		}
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		while(($li_nivel>=1)and($lb_valido)and($ls_nextcuenta!=""))
		{  
				$ls_sql="   update spg_cuentas ".
						"   set ".$ls_mes."= ".$ls_mes." + ".$as_monto.
						"   where codestpro1='".$coest1."' ".
						"   and  codestpro2='".$coest2."' ".
						"   and  codestpro3='".$coest3."' ".
						"   and  codestpro4='".$coest4."'  ".
						"   and  codestpro5='".$coest5."'  ".
						"   and  spg_cuenta='".trim($ls_nextcuenta)."'".
						"   and  estcla='".$as_estcla."'";  
				$rs_data=$this->io_sql->execute($ls_sql);
				if($rs_data===false)
				{   // error interno sql
					$this->io_msg->message("Error en uf_update_trimestral2".
										   $this->io_function->uf_convertirmsg($this->io_sql->message));
					$lb_valido = false;
				}
				else
				{
					$lb_valido = true;
					////////////////////////////////////////seguridad///////////////////////////////////////////////////////////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion =" La Estructura $coest1-$coest2-$coest3-$coest4-$coest5  con cuenta $as_cuenta se le aumento $as_monto Bs $ls_trimestre a la fehca del $as_fecha";
					$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($la_security[1],
												$la_security[2],$ls_evento,$la_security[3],
												$la_security[4],$ls_descripcion);
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					$this->io_sql->free_result($rs_data);
				}	
			if($this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->io_intspg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->io_intspg->uf_spg_obtener_nivel($ls_nextcuenta);
		}
		return $lb_valido;
   }// fin de la funcion ///---------------------------------------------------------------------------------------------------------------------------------------
   
   function uf_guardar_regmodificacion($aa_estpro,$as_spg_cuenta,$ad_monto,$as_mesaum,$as_mesdis,$aa_security)
   {
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	//	Function:    uf_guardar_regmodificacion
    //	Access:      public
	//  Arguments:   $aa_estpro     -> Estructura Presupuestaria
	//               $as_spg_cuenta -> Cuenta Presupuestaria de Gasto Afectada
	//				 $ad_monto      -> Monto de la Operacion
	//               $as_mesaum     -> Mes a Acreditar
	//               $as_mesdis     -> Mes a Debitar
	//				 $aa_security   -> Seguridad
	//	Returns:	 $lb_valido     -> True o False
	//	Description: Funcion que registra las Modificaciones hechas al Programado de los Meses en Presupuesto de Gasto,
	//               también se comtempla la parte de seguridad registrando de manera automática el
	//               responsable de la operacion
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	$lb_valido = false;
	
	$ls_ip = $this->io_seguridad->getip();
	
	$ls_host=@gethostbyaddr($ls_ip);
	
	$ls_equipo = "Ip: ".$ls_ip." - Equipo: ".$ls_host;
	
	$ld_fecha = date("Y-m-d H:i:s");
	
	$as_mesaum = $this->io_function->uf_cerosizquierda($as_mesaum,2);
	
	$as_mesdis = $this->io_function->uf_cerosizquierda($as_mesdis,2);
	
	
	$ls_sql = "INSERT INTO spg_regmodprogramado(codemp, estcla, codestpro1, codestpro2, codestpro3, codestpro4,  ".
              "                                 codestpro5, spg_cuenta, fecha, codusu, equipo, mesaumento, ".
			  "                                 mesdisminucion, monto,  montoantmesaum,montoantmesdis) ".
              "        VALUES ('".$aa_security[1]."','".$aa_estpro[0]."', '".$aa_estpro[1]."', '".$aa_estpro[2]."', '".$aa_estpro[3]."', '".$aa_estpro[4]."',". 
              "                '".$aa_estpro[5]."','".$as_spg_cuenta."','".$ld_fecha."','".$aa_security[3]."','".$ls_equipo."','".$as_mesaum."', '".$as_mesdis."',".$ad_monto.",0,0)";	  

	$rs_data=$this->io_sql->execute($ls_sql);
    if($rs_data===false)
	{// error interno sql
	 $this->io_msg->message("Error en uf_guardar_regmodificacion ".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Insertó la modificacion del programado para la cuenta ".$as_spg_cuenta." Estructura ".$aa_estpro[0].$aa_estpro[1].$aa_estpro[2].$aa_estpro[3].$aa_estpro[4].
						 " Asociado a la empresa ".$aa_security[1];
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_security[1],
										$aa_security[2],$ls_evento,$aa_security[3],
										$aa_security[4],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	 $lb_valido = true;
	}
    return $lb_valido;
   }
   
   function uf_obtener_regmodificacion($as_codemp,$aa_estpro,$as_spg_cuenta="",$ad_fechades,$ad_fechahas,&$rs_data)
   {
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	//	Function:    uf_obtener_regmodificacion
    //	Access:      public
	//  Arguments:   $aa_estpro     -> Estructura Presupuestaria
	//               $as_spg_cuenta -> Cuenta Presupuestaria de Gasto Afectada
	//               $ad_fecha      -> Fecha de Proceso
	//	Returns:	 $lb_valido     -> True o False
	//	Description: Funcion que obtiene las Modificaciones hechas al Programado de los Meses en Presupuesto de Gasto
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$lb_valido = false;
	
	$ls_cad="";
	
	if($aa_estpro[0] != "")
	{
	 if (($aa_estpro[1]!="")&&($aa_estpro[1]!="0000000000000000000000000"))
	 {
	  $ls_cad = " AND spg_regmodprogramado.codestpro1 = '".$aa_estpro[1]."'";
	 }
	 
	 if (($aa_estpro[2]!="")&&($aa_estpro[2]!="0000000000000000000000000"))
	 {
	  $ls_cad = $ls_cad." AND spg_regmodprogramado.codestpro2 = '".$aa_estpro[2]."'";
	 }
	 
	 if (($aa_estpro[3]!="")&&($aa_estpro[3]!="0000000000000000000000000"))
	 {
	  $ls_cad = $ls_cad." AND spg_regmodprogramado.codestpro3 = '".$aa_estpro[3]."'";
	 }
	 
	 if (($aa_estpro[4]!="")&&($aa_estpro[4]!="0000000000000000000000000"))
	 {
	  $ls_cad = $ls_cad." AND spg_regmodprogramado.codestpro4 = '".$aa_estpro[4]."'";
	 }
	 
	 if (($aa_estpro[5]!="")&&($aa_estpro[5]!="0000000000000000000000000"))
	 {
	  $ls_cad = $ls_cad." AND spg_regmodprogramado.codestpro5 = '".$aa_estpro[5]."'";
	 }
	  
	 $ls_cad = $ls_cad. " AND spg_regmodprogramado.estcla='".$aa_estpro[0]."'";
	}
	
	if($as_spg_cuenta != "")
	{
	 $ls_cad=$ls_cad." AND spg_regmodprogramado.spg_cuenta ='".$as_spg_cuenta."'";
	}
	
	if(($ad_fechades != "")&&($ad_fechahas != ""))
	{
	 $ls_cad=$ls_cad." AND substr(spg_regmodprogramado.fecha,1,9) >= '".$ad_fechades."' and substr(spg_regmodprogramado.fecha,1,9) <= '".$ad_fechahas."'";
	}
	
	$ls_sql = "SELECT spg_regmodprogramado.*,spg_ep1.denestpro1,spg_ep2.denestpro2,spg_ep3.denestpro3, ".
	          "       spg_ep4.denestpro4,spg_ep5.denestpro5 FROM spg_regmodprogramado,spg_ep1,spg_ep2,spg_ep3,spg_ep4,spg_ep5  ".
	          " WHERE spg_regmodprogramado.codemp = '".$as_codemp."' ".$ls_cad.
			  " AND spg_ep1.codemp=spg_regmodprogramado.codemp ".
			  " AND spg_ep1.codestpro1=spg_regmodprogramado.codestpro1 ".
			  "	AND spg_ep1.estcla=spg_regmodprogramado.estcla ".
			  "	AND spg_ep2.codemp=spg_regmodprogramado.codemp ".
			  "	AND spg_ep2.codestpro1=spg_regmodprogramado.codestpro1 ".
			  "	AND spg_ep2.codestpro2=spg_regmodprogramado.codestpro2 ".
			  "	AND spg_ep2.estcla=spg_regmodprogramado.estcla ".
			  "	AND spg_ep3.codemp=spg_regmodprogramado.codemp ".
			  "	AND spg_ep3.codestpro1=spg_regmodprogramado.codestpro1 ".
			  "	AND spg_ep3.codestpro2=spg_regmodprogramado.codestpro2 ".
			  "	AND spg_ep3.codestpro3=spg_regmodprogramado.codestpro3 ".
			  "	AND spg_ep3.estcla=spg_regmodprogramado.estcla ".
			  "	AND spg_ep4.codemp=spg_regmodprogramado.codemp ".
			  "	AND spg_ep4.codestpro1=spg_regmodprogramado.codestpro1 ".
			  "	AND spg_ep4.codestpro2=spg_regmodprogramado.codestpro2 ".
			  "	AND spg_ep4.codestpro3=spg_regmodprogramado.codestpro3 ".
			  "	AND spg_ep4.codestpro4=spg_regmodprogramado.codestpro4 ".
			  "	AND spg_ep4.estcla=spg_regmodprogramado.estcla ".
			  "	AND spg_ep5.codemp=spg_regmodprogramado.codemp ".
			  "	AND spg_ep5.codestpro1=spg_regmodprogramado.codestpro1 ".
			  "	AND spg_ep5.codestpro2=spg_regmodprogramado.codestpro2 ".
			  "	AND spg_ep5.codestpro3=spg_regmodprogramado.codestpro3 ".
			  "	AND spg_ep5.codestpro4=spg_regmodprogramado.codestpro4 ".
			  "	AND spg_ep5.codestpro5=spg_regmodprogramado.codestpro5 ".
			  "	AND spg_ep5.estcla=spg_regmodprogramado.estcla";	  
	$rs_data=$this->io_sql->execute($ls_sql);
    if($rs_data===false)
	{// error interno sql
	 $this->io_msg->message("Error en uf_obtener_regmodificacion ".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	 $lb_valido = true;
	}
    return $lb_valido;
   }
}// fin de la clase sigesp_spg_c_mod_programado
?>