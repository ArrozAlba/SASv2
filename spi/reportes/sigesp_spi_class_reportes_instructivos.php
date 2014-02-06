<?php
class sigesp_spi_class_reportes_instructivos
{
	var $io_msg;
	var $io_fecha;
	var $io_sigesp_int_spg;
	var $io_sql;
	var $ls_codemp;
	var $ls_gestor;
	var $li_estmodest;
	var $sqlca;   
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $dts_cab;
	var $obj="";
	var $siginc;
	var $con;
	var $fun;	
	var $sigesp_int_spi;
	var $dts_prog;
	 // Presupuesto de Caja
	var $dts_reporte_temporal;
	var $dts_ingresos_corrientes;
	var $dts_ingresos_capital;
	var $dts_ingresos_financieros;
	var $dts_egresos_consumo;
	var $dts_egresos_corrientes;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function  sigesp_spi_class_reportes_instructivos()
    {
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_datastore.php");
		require_once("../../shared/class_folder/class_sigesp_int.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../../shared/class_folder/class_sigesp_int_spg.php");		
		require_once("../../shared/class_folder/class_sigesp_int_spi.php");	
		
		$this->io_funciones = new class_funciones() ;
		$this->io_include = new sigesp_include();
		$this->io_conexion = $this->io_include->uf_conectar();
		$this->io_sql = new class_sql($this->io_conexion);		
		$this->dts_reporte = new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_mensajes = new class_mensajes();
		$this->io_sigesp_int_spi = new class_sigesp_int_spi();
		$this->ls_codemp = $_SESSION["la_empresa"]["codemp"];
	    $this->ls_gestor = $_SESSION["ls_gestor"];
	    $this->li_estmodest = $_SESSION["la_empresa"]["estmodest"];
		
		//Presupuesto de Caja
		$this->dts_reporte_temporal        = new class_datastore();
		$this->dts_ingresos_corrientes     = new class_datastore();
		$this->dts_ingresos_capital        = new class_datastore();
		$this->dts_ingresos_financieros    = new class_datastore();
		$this->dts_incrementos_pasivos     = new class_datastore();
		$this->dts_incrementos_patrimonio  = new class_datastore();
		$this->dts_egresos_consumo         = new class_datastore();
		$this->dts_egresos_corrientes      = new class_datastore();
	
		
		$this->io_fecha = new class_fecha();
		$this->io_mensajes = new class_mensajes();
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();
		$this->ls_codemp = $_SESSION["la_empresa"]["codemp"];
	    $this->ls_gestor = $_SESSION["ls_gestor"];
	    $this->li_estmodest=$_SESSION["la_empresa"]["estmodest"];
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVOS " CONSOLIDADO DE EJECUCION TRIMESTRAL "  //
	/////////////////////////////////////////////////////////////////////////////////
    function uf_spi_reporte_consolidado_de_ejecucion_trimestral($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_consolidado_de_ejecucion_trimestral
	 //         Access :	private
	 //     Argumentos :    $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta   
	 //                     $as_mesdes  -----> mes desde         
	 //                     $as_meshas  -----> mes hasta         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 07 del CONSOLIDADO DE EJECUCION TRIMESTRAL
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_sql=" SELECT max(codemp) as codemp, spi_cuenta, max(denominacion) as denominacion,             ".
	         "        max(status) as status, max(sc_cuenta) as sc_cuenta, sum(previsto) as previsto,    ".
             "        sum(cobrado_anticipado) as cobrado_anticipado, sum(cobrado) as cobrado,           ".
             "        sum(devengado) as devengado, sum(aumento) as aumento,                             ".
             "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
             "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
             "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
             "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			 "        sum(diciembre) as diciembre, max(nivel) as nivel, max(referencia) as referencia,  ".
			 "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			 "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			 "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
             " FROM spi_cuentas                                                                         ".
             " WHERE codemp='".$this->ls_codemp."'  AND  nivel = '1'                                    ".
             " GROUP BY spi_cuenta                                                                      ".
             " ORDER BY spi_cuenta ";	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spi_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spi_reporte_consolidado_de_ejecucion_trimestral ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido = false;
	 }
	 else
	 {		
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $li_nivel=$row["nivel"];
			   $ld_previsto=$row["previsto"];
			   //$ld_cobrado_anticipado_total=$row["cobrado_anticipado"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   
			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   $ld_ingresosxrecibir=$ld_previsto+$ld_aumento-$ld_disminucion-$ld_devengado;
			   $this->dts_reporte->insertRow("spi_cuenta",$ls_spi_cuenta);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("previsto",$ld_previsto);
			   $this->dts_reporte->insertRow("previsto_modificado",$ld_previsto_modificado);
			   $this->dts_reporte->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte->insertRow("cobrado",$ld_cobrado);					 
			   $this->dts_reporte->insertRow("devengado",$ld_devengado);					 
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
			   $this->dts_reporte->insertRow("cobrado_anticipado_acumulado",$ld_cobrado_anticipado_acumulado);
		  	   $this->dts_reporte->insertRow("cobrado_acumulado",$ld_cobrado_acumulado);					 
			   $this->dts_reporte->insertRow("devengado_acumulado",$ld_devengado_acumulado);	
			   $this->dts_reporte->insertRow("ingresosxrecibir",$ld_ingresosxrecibir);	
			   $lb_valido=true;
		    }//while
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_spi_reporte_total_ingresos($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_total_ingresos
	 //         Access :	private
	 //     Argumentos :    $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta   
	 //                     $as_mesdes  -----> mes desde         
	 //                     $as_meshas  -----> mes hasta         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 07 del CONSOLIDADO DE EJECUCION TRIMESTRAL
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_sql=" SELECT codemp,spi_cuenta, sum(previsto) as previsto,    ".
             "        sum(cobrado_anticipado) as cobrado_anticipado, sum(cobrado) as cobrado,           ".
             "        sum(devengado) as devengado, sum(aumento) as aumento,                             ".
             "        sum(disminucion) as disminucion,                              ".
			 "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			 "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			 "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
             " FROM spi_cuentas                                                                         ".
             " WHERE codemp='".$this->ls_codemp."'  AND spi_cuenta like '3%' and nivel = 1              ".
             " GROUP BY codemp,spi_cuenta                                                               ".
             " ORDER BY codemp,spi_cuenta ";		 	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spi_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spi_reporte_consolidado_de_ejecucion_trimestral ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido = false;
	 }
	 else
	 {		
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     $ld_ejecutadoacum = 0;
			 $ld_ejecutado = 0;
			 $ld_programado = 0;
			 $ld_programadoacum = 0;
			 $ld_asignado_modificado = 0;
			 $ld_asignado = 0;
			 $ld_totejeacum = 0;
			 $ld_toteje = 0;
			 while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ld_asignado = $ld_asignado + $ld_previsto;
			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 $ld_ejecutado,&$ld_devengado,&$ld_aumento,&$ld_disminucion);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																$ld_ejecutadoacum,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado);
			   }//if
			   
			   $ld_totejeacum = $ld_totejeacum + $ld_ejecutadoacum;
			   $ld_toteje =  $ld_toteje + $ld_ejecutado;
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado= $ld_programado+ $ld_trimetreI;
				   $ld_programadoacum= $ld_programadoacum +$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado= $ld_programado + $ld_trimetreII;
				   $ld_programadoacum= $ld_programadoacum +=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado= $ld_programado + $ld_trimetreIII;
				   $ld_programadoacum= $ld_programadoacum + $ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado=$ld_programado + $ld_trimetreIV;
				   $ld_programadoacum= $ld_programadoacum + $ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_asignado_modificado=$ld_asignado_modificado+$ld_previsto+$ld_aumento-$ld_disminucion;
			   $lb_valido=true;
		    }//while
			if ($ld_programado>0)
			{
			 $ld_porcentual = ($ld_toteje/$ld_programado)*100;
			}
			else
			{
			 $ld_porcentual = 0;
			}
			$this->dts_reporte->insertRow("cuenta","");
			$this->dts_reporte->insertRow("denominacion",'<b>INGRESOS</b>');
			$this->dts_reporte->insertRow("asignado",$ld_asignado);
			$this->dts_reporte->insertRow("modificado",$ld_asignado_modificado);
			$this->dts_reporte->insertRow("programado",$ld_programado);
			$this->dts_reporte->insertRow("ejecutado", $ld_toteje);		
			$this->dts_reporte->insertRow("absoluto",abs($ld_ejecutado - $ld_programado));		
			$this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
			$this->dts_reporte->insertRow("programado_acumulado",$ld_programadoacum);
			$this->dts_reporte->insertRow("ejecutado_acumulado",$ld_totejeacum);  
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_spg_reporte_total_egresos($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_total_egresos
	 //         Access :	private
	 //     Argumentos :    $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta   
	 //                     $as_mesdes  -----> mes desde         
	 //                     $as_meshas  -----> mes hasta         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 07
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_sql=" SELECT codemp,spg_cuenta, sum(asignado) as asignado,                                     ".
             "        sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
             "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
             "        sum(disminucion) as disminucion,                                                  ".
			 "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			 "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			 "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
             " FROM spg_cuentas                                                                         ".
             " WHERE codemp='".$this->ls_codemp."'  AND spg_cuenta like '4%' and status = 'C'           ".
             " GROUP BY codemp,spg_cuenta                                                               ".
             " ORDER BY codemp,spg_cuenta";		 			 	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spi_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spg_reporte_total_ingresos ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido = false;
	 }
	 else
	 {		
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     $ld_ejecutadoacum = 0;
			 $ld_ejecutado = 0;
			 $ld_programado = 0;
			 $ld_asignadoacum = 0;
			 $ld_programadoacum = 0;
			 $ld_asignado_modificado = 0;
			 $ld_asignado = 0;
			 $ld_totejeacum = 0;
			 $ld_toteje = 0;
			 while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ld_asignado=$row["asignado"];
			   $ld_precomprometido=$row["precomprometido"];
			   $ld_comprometido=$row["comprometido"];
			   $ld_causado=$row["causado"];
			   $ld_pagado=$row["pagado"];
			   $ld_aumento=$row["aumento"];
			   $ld_disminucion=$row["disminucion"];
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ld_asignadoacum = $ld_asignadoacum + $ld_asignado;

			   if($as_mesdes=='Enero')
		       {
				   $ld_programado= $ld_programado+ $ld_trimetreI;
				   $ld_programadoacum= $ld_programadoacum +$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado= $ld_programado + $ld_trimetreII;
				   $ld_programadoacum= $ld_programadoacum +=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado= $ld_programado + $ld_trimetreIII;
				   $ld_programadoacum= $ld_programadoacum + $ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado=$ld_programado + $ld_trimetreIV;
				   $ld_programadoacum= $ld_programadoacum + $ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_asignado_modificado=$ld_asignado_modificado+$ld_asignado+$ld_aumento-$ld_disminucion;
			   $lb_valido=true;
		    }//while
			$lb_valido=$this->uf_spg_ejecutado_trimestral_estado_resultado($ls_spg_cuenta,$adt_fecdes,$adt_fechas,
			                                                                  &$ld_comprometer,$ld_ejecutado,&$ld_pagado,
																			  &$ld_aumento,&$ld_disminucion,false);
			if($lb_valido)
		    {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado_estado_resultado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
				                                                                 $ld_ejecutadoacum,&$ld_pagado_acumulado,
																				 &$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																                 false);
			}//if
			$ld_totejeacum = $ld_totejeacum + $ld_ejecutadoacum;
			$ld_toteje =  $ld_toteje + $ld_ejecutado;
			if ($ld_programado>0)
			{
			 $ld_porcentual = ($ld_toteje/$ld_programado)*100;
			}
			else
			{
			 $ld_porcentual = 0;
			}
			$this->dts_reporte->insertRow("cuenta","400000000");
			$this->dts_reporte->insertRow("denominacion",'<b>EGRESOS</b>');
			$this->dts_reporte->insertRow("asignado",$ld_asignadoacum);
			$this->dts_reporte->insertRow("modificado",$ld_asignado_modificado);
			$this->dts_reporte->insertRow("programado",$ld_programado);
			$this->dts_reporte->insertRow("ejecutado", $ld_toteje);		
			$this->dts_reporte->insertRow("absoluto",abs($ld_programado-$ld_ejecutado));		
			$this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
			$this->dts_reporte->insertRow("programado_acumulado",$ld_programadoacum);
			$this->dts_reporte->insertRow("ejecutado_acumulado",$ld_totejeacum);  
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_ejecutado_trimestral($as_spi_cuenta,$adt_fecdes,$adt_fechas,&$ad_cobrado_anticipado,
	                                     &$ad_cobrado,&$ad_devengado,&$ad_aumento,&$ad_disminucion)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_ejecutado_trimestral
	 //         Access :	private
	 //     Argumentos :    $as_spi_cuenta  -----> cuenta 
	 //                     $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta  
	 //                     $ad_cobrado_anticipado  -----> monto cobrado_anticipado referencia   
	 //                     $ad_cobrado  -----> monto cobrado referencia   
	 //                     $ad_devengado  -----> monto devengado referencia   
	 //                     $ad_aumento_acumulado  -----> monto aumento referencia   
	 //                     $ad_disminucion_acumulado  -----> monto disminucion referencia   
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera los eejcutados por trimestre
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    21/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ad_cobrado_anticipado = 0;
	 $ad_cobrado = 0;
	 $ad_devengado = 0;
	 $ad_aumento = 0;
	 $ad_disminucion = 0;
	 $as_spi_cuenta = $this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.cobrado,  OP.devengado  ".
               " FROM   spi_dt_cmp DT, spi_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        spi_cuenta like '".$as_spi_cuenta."'  AND ".
               "        fecha BETWEEN '".$adt_fecdes."' AND  '".$adt_fechas."' ";
	  $rs_ejecutado=$this->io_sql->select($ls_sql);
	  if($rs_ejecutado===false)
	  { // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spi_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spi_ejecutado_trimestral ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_ejecutado))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_cobrado=$row["cobrado"];
		  $li_devengado=$row["devengado"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  
		  if($li_cobrado)
		  { 
		    $ad_cobrado=$ad_cobrado+$ld_monto;
		  }//if
		  if($li_devengado)
		  { 
		    $ad_devengado=$ad_devengado+$ld_monto;
		  }//if
		  if($li_aumento)
		  {
		    $ad_aumento=$ad_aumento+$ld_monto;
		  }//if
		  if($li_disminucion)
		  { 
		    $ad_disminucion=$ad_disminucion+$ld_monto;
		  }//if
	    }// while
	    $this->io_sql->free_result($rs_ejecutado);
	   }//else	
	  return $lb_valido;	
     }//fin uf_spg_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_ejecutado_acumulado($as_spi_cuenta,$adt_fechas,&$ad_cobrado_anticipado_acumulado,&$ad_cobrado_acumulado,
	                                    &$ad_devengado_acumulado,&$ad_aumento_acumulado,&$ad_disminucion_acumulado)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_ejecutado_trimestral
	 //         Access :	private
	 //     Argumentos :    $as_spi_cuenta  -----> cuenta 
	 //                     $adt_fechas  -----> fechas hasta    
	 //                     $ad_cobrado_anticipado_acumulado  -----> monto acumulado cobrado_anticipado referencia   
	 //                     $ad_cobrado_acumulado  -----> monto acumulado cobrado referencia   
	 //                     $ad_devengado_acumulado  -----> monto acumulado devengado referencia   
	 //                     $ad_aumento_acumulado  -----> monto acumulado aumento referencia   
	 //                     $ad_disminucion_acumulado  -----> monto acumulado disminucion referencia   
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera los eejcutados por trimestre
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    21/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;
	 $ad_cobrado_anticipado_acumulado = 0;
	 $ad_cobrado_acumulado = 0;
	 $ad_devengado_acumulado = 0;
	 $ad_aumento_acumulado = 0;
	 $ad_disminucion_acumulado = 0;
	 $as_spi_cuenta = $this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.cobrado, OP.devengado                      ".
               " FROM   spi_dt_cmp DT, spi_operaciones OP     ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND  ".
               "        DT.operacion = OP.operacion AND       ".
               "        spi_cuenta like '".$as_spi_cuenta."'  AND ".
               "        fecha <='".$adt_fechas."' ";		   
	  $rs_ejecutado=$this->io_sql->select($ls_sql);
	  if($rs_ejecutado===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spi_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spi_ejecutado_trimestral ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_ejecutado))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_cobrado=$row["cobrado"];
		  $li_devengado=$row["devengado"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  
		  if($li_cobrado)
		  { 
		    $ad_cobrado_acumulado=$ad_cobrado_acumulado+$ld_monto;
		  }//if
		  if($li_devengado)
		  { 
		    $ad_devengado_acumulado=$ad_devengado_acumulado+$ld_monto;
		  }//if
		  if($li_aumento)
		  { 
		    $ad_aumento_acumulado=$ad_aumento_acumulado+$ld_monto;
		  }//if
		  if($li_disminucion)
		  { 
		    $ad_disminucion_acumulado=$ad_disminucion_acumulado+$ld_monto;
		  }//if
	    }// while
	    $this->io_sql->free_result($rs_ejecutado);
	   }//else	
	  return $lb_valido;	
     }//fin uf_spi_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	 function uf_spi_reportes_ejecucion_trimestral($adt_fecdes,$adt_fechas,$as_codestpro1,$as_codestpro2,$as_codestpro3,
	 											   $as_codestpro4,$as_codestpro5,$as_codestpro1h,$as_codestpro2h,$as_codestpro3h,
												   $as_codestpro4h,$as_codestpro5h,$as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_ejecucion_trimestral
	 //         Access :	private
	 //     Argumentos : 
	 //                     $adt_fecdes  //  fecha desde 
	 //                     $adt_fechas  //  fecha hasta
	 //                     $ai_nivel    //  nivel 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  de la Ejecucion Trimestral
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    18/05/2008     
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;	 
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_codemp = $this->dts_empresa["codemp"]; 
		$li_mesdes=$adt_fecdes;
		$li_meshas=$adt_fechas;
		$li_trimestre = substr($li_mesdes,5,2);
		$li_trimestre = intval($li_trimestre);
		$li_estpreing = $_SESSION["la_empresa"]["estpreing"];
		$ls_sqlaux = $ls_straux = "";
		if ($li_estpreing==1)
		   {
		      if (!empty($as_codestpro1) && !empty($as_codestpro2) && !empty($as_codestpro3) && !empty($as_codestpro1h)&&
			      !empty($as_codestpro2h) && !empty($as_codestpro3h))
			    {
				  $ls_straux      = ",spi_cuentas_estructuras";
				  $ls_codestpro1  = str_pad($as_codestpro1,25,0,0);
				  $ls_codestpro2  = str_pad($as_codestpro2,25,0,0);
				  $ls_codestpro3  = str_pad($as_codestpro3,25,0,0);
				  $ls_codestpro1h = str_pad($as_codestpro1h,25,0,0);
				  $ls_codestpro2h = str_pad($as_codestpro2h,25,0,0);
				  $ls_codestpro3h = str_pad($as_codestpro3h,25,0,0);
				}
			 if (!empty($as_codestpro4)&&!empty($as_codestpro5)&&!empty($as_codestpro4h)&&!empty($as_codestpro5h))
			    {
				  $ls_codestpro4  = str_pad($as_codestpro4,25,0,0);
				  $ls_codestpro5  = str_pad($as_codestpro5,25,0,0);
				  $ls_codestpro4h = str_pad($as_codestpro4h,25,0,0);
				  $ls_codestpro5h = str_pad($as_codestpro5h,25,0,0);					 
				}
			 else
				{
				  $ls_codestpro4=$ls_codestpro5=$ls_codestpro4h=$ls_codestpro5h = str_pad("",25,0,0);
				}
		     if (!empty($as_codestpro1) && !empty($as_codestpro1h))
			    {
				  $ls_sqlaux = " AND spi_cuentas_estructuras.estcla = '".$as_estclades."'
								 AND spi_cuentas_estructuras.codestpro1 BETWEEN '".$ls_codestpro1."' AND '".$ls_codestpro1h."'
								 AND spi_cuentas_estructuras.codestpro2 BETWEEN '".$ls_codestpro2."' AND '".$ls_codestpro2h."'
								 AND spi_cuentas_estructuras.codestpro3 BETWEEN '".$ls_codestpro3."' AND '".$ls_codestpro3h."'
								 AND spi_cuentas_estructuras.codestpro4 BETWEEN '".$ls_codestpro4."' AND '".$ls_codestpro4h."'
								 AND spi_cuentas_estructuras.codestpro5 BETWEEN '".$ls_codestpro5."' AND '".$ls_codestpro5h."'";
				}
		   }
		
		$ls_sql = "SELECT spi_cuentas.spi_cuenta, max(spi_cuentas.nivel) as nivel, max(spi_cuentas.denominacion) as denominacion,
				          sum(spi_cuentas.previsto) as previsto, sum(spi_cuentas.enero) as enero, sum(spi_cuentas.febrero) as febrero,
				          sum(spi_cuentas.marzo) as marzo, sum(spi_cuentas.abril) as abril, sum(spi_cuentas.mayo) as mayo,
				          sum(spi_cuentas.junio) as junio, sum(spi_cuentas.julio) as julio, sum(spi_cuentas.agosto) as agosto,
				          sum(spi_cuentas.septiembre) as septiembre, sum(spi_cuentas.octubre) as octubre,
				          sum(spi_cuentas.noviembre) as noviembre, sum(spi_cuentas.diciembre) as diciembre, MAX(status) as status
			         FROM spi_cuentas $ls_straux
			        WHERE spi_cuentas.codemp='".$this->ls_codemp."' $ls_sqlaux
			        GROUP BY spi_cuentas.spi_cuenta
       			    ORDER BY spi_cuentas.spi_cuenta ASC";
		$rs_data=$this->io_sql->select($ls_sql);//echo $ls_sql.'<br>';
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spi_reportes_ejecucion_trimestral".$this->fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $lb_ok=false; 
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $li_nivel=$row["nivel"];
			   $ld_previsto=$row["previsto"];
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
			   $ls_status=$row["status"];
			   $ld_prog_tri_i    = 0;
			   $ld_prog_tri_ii   = 0;
			   $ld_prog_tri_iii  = 0;
			   $ld_prog_tri_iv   = 0;
			   $ld_aumdisacum    = 0;
			   $ld_pretriact     = 0; 
			   $ld_devtriact     = 0;
			   $ld_cobtriact     = 0;
			   $ld_restriact     = 0;
			   $ld_preacum       = 0; 
			   $ld_devacum       = 0;
			   $ld_cobacum       = 0;
			   $ld_resacum       = 0;

			   switch ($li_trimestre)
			   {
		        case 1:
		              $ld_programado      =  $ld_enero + $ld_febrero + $ld_marzo;
					  $ld_programado_acum =  $ld_programado;
					  break;
					  
			    case 4:
			          $ld_programado      =  $ld_abril + $ld_mayo + $ld_junio;
					  $ld_programado_acum =  $ld_enero + $ld_febrero + $ld_marzo + $ld_programado;
					  break;
					  
			    case 7:
			          $ld_programado      =  $ld_julio + $ld_agosto + $ld_septiembre;
					  $ld_programado_acum =  $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio +
					                         $ld_programado;
					  break;	  	  
					  
			    case 10:
			          $ld_programado      =  $ld_octubre + $ld_noviembre + $ld_diciembre;
					  $ld_programado_acum =  $ld_enero + $ld_febrero + $ld_marzo +  $ld_abril + $ld_mayo + $ld_junio + $ld_julio +                                             $ld_agosto + $ld_septiembre + $ld_programado;
					  break;
			   }	  		  
               $lb_valido=$this->uf_spi_reporte_calcular_ejecutado_trimestre($ls_spi_cuenta,$li_mesdes,$li_meshas,$ld_aumdisacum,
			                                                                 $ld_pretriact,$ld_devtriact,$ld_cobtriact,
			                                                                 $ld_restriact,$ld_preacum,$ld_devacum,$ld_cobacum
																			,$ld_resacum);
			   if($lb_valido)
			   {
					 $ld_ingresos_recibir=$ld_previsto+$ld_aumdisacum-$ld_cobacum;
					 $ld_modificado = $ld_previsto + $ld_aumdisacum;
					 $this->dts_reporte->insertRow("spi_cuenta",$ls_spi_cuenta);
					 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					 $this->dts_reporte->insertRow("previsto",$ld_previsto);
					 $this->dts_reporte->insertRow("modificado",$ld_modificado);
					 $this->dts_reporte->insertRow("programado",$ld_programado);
					 $this->dts_reporte->insertRow("devengado",$ld_devtriact);
					 $this->dts_reporte->insertRow("liquidado",$ld_cobtriact);
					 $this->dts_reporte->insertRow("recaudado",0);
					 $this->dts_reporte->insertRow("programado_acum",$ld_programado_acum);
					 $this->dts_reporte->insertRow("devengado_acum",$ld_devacum);					 
					 $this->dts_reporte->insertRow("liquidado_acum",$ld_cobacum);					 
					 $this->dts_reporte->insertRow("recaudado_acum",0);
					 $this->dts_reporte->insertRow("ingresos_recibir",$ld_ingresos_recibir);
					 $this->dts_reporte->insertRow("status",$ls_status);
					 $lb_valido=true;
			    }//if
				
			 }//while
			 $this->io_sql->free_result($rs_data);
		 } //else 
     return $lb_valido;
    }//fin uf_spg_reportes_ejecucion_trimestral
/********************************************************************************************************************************/	
    
function uf_spi_reporte_calcular_ejecutado_trimestre($as_spi_cuenta,$ai_mesdes,$ai_meshas,&$ad_aumdisacum,&$ad_pretriact,&
                                                     $ad_devtriact,&$ad_cobtriact,&$ad_restriact,&$ad_preacum,&
                                                     $ad_devacum,&$ad_cobacum,&$ad_resacum)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_calcular_ejecutado_trimestre
	 //         Access :	private
	 //     Argumentos :    $as_spi_cuenta  // cuenta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado (referencia)  
	 //                     $ad_monto_acumulado // monto acumulado (referencia) 
	 //                     $ad_aumdismes // monto aumneto y disminuciones del mes (referencia)  
	 //                     $ad_aumdisacum // monto aumneto y disminuciones del mes acumulado (referencia)  
	 //                     $ad_comprometer // monto comprometer (referencia)  
	 //                     $ad_causado // monto causado (referencia)  
	 //                     $ad_pagado // monto pagado (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para  la ejecucucion financiera
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ld_aumento=0;
	  $ld_disminucion=0;
	  $ld_aumento_acum=0;
	  $ld_disminucion_acum=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_codemp = $this->dts_empresa["codemp"];
      $li_mesdes=$ai_mesdes;
	  $li_meshas=$ai_meshas;
	  $as_spi_cuenta=$this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.monto, OP.previsto, OP.aumento, OP.disminucion, OP.devengado,OP.cobrado, OP.reservado     ".
              " FROM   spi_dt_cmp DT, spi_operaciones OP ".
              " WHERE  DT.codemp='".$this->ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
              "        spi_cuenta like '".$as_spi_cuenta."' AND DT.fecha <= '".$ai_meshas."'".
			  " ORDER BY DT.fecha";	  
	  $rs_ejec=$this->io_sql->select($ls_sql);
	  if($rs_ejec===false)
	  { // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spi_reporte_calcular_ejecutado_trimestre".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_ejec))
		{
		  $li_previsto=$row["previsto"];
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_devengado=$row["devengado"];
		  $li_cobrado=$row["cobrado"];
		  $li_reservado=$row["reservado"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,10);
	      
     	  //  Comprometer, Causar, Pagar, Aumento, Disminución
		  if(($li_previsto)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pretriact=$ad_pretriact+$ld_monto;
		  }//if
		  if(($li_devengado)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_devtriact=$ad_devtriact+$ld_monto;
		  }//if
		  if(($li_cobrado)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_cobtriact=$ad_cobtriact+$ld_monto;
		  }//if
		   if(($li_reservado)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_restriact=$ad_restriact+$ld_monto;
		  }//if
		  if(($li_previsto)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_preacum=$ad_preacum+$ld_monto;
		  }//if
		  if(($li_devengado)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_devacum=$ad_devacum+$ld_monto;
		  }//if
		  if(($li_cobrado)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_cobacum=$ad_cobacum+$ld_monto;
		  }//if
		   if(($li_reservado)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_resacum=$ad_resacum+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_aumento=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_disminucion=$ld_disminucion+$ld_monto;
		  }//if
		}//while
		$ad_aumdisacum=$ld_aumento-$ld_disminucion;
	   $this->io_sql->free_result($rs_ejec);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spi_reporte_calcular_ejecutado_trimestre
/****************************************************************************************************************************************/	

//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_trimestral_estado_resultado($as_spg_cuenta,$adt_fecdes,$adt_fechas,&$ad_comprometer,&$ad_causado,
	                                                      &$ad_pagado,&$ad_aumento,&$ad_disminucion,$as_detallar)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_ejecutado_trimestral
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  -----> cuenta 
	 //                     $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta  
	 //                     $ad_comprometer_acumulado  -----> monto comprometer referencia   
	 //                     $ad_causado_acumulado  -----> monto causado referencia   
	 //                     $ad_pagado_acumulado  -----> monto pagado referencia   
	 //                     $ad_aumento_acumulado  -----> monto aumento referencia   
	 //                     $ad_disminucion_acumulado  -----> monto disminucion referencia   
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera los eejcutados por trimestre
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ad_comprometer=0;  $ad_causado=0;  $ad_pagado=0;
	 $ad_aumento=0;  $ad_disminucion=0;
	 if($as_detallar==true)
	 {
	   $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 }
	 elseif($as_detallar==false)
	 {
	   $as_spg_cuenta = $as_spg_cuenta;
	   $ls_cadena="spg_cuenta = '".$as_spg_cuenta."'  AND";
	 }
     $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        ".$ls_cadena."                  ".
               "        fecha BETWEEN '".$adt_fecdes."' AND  '".$adt_fechas."' ";	      	   	   
	  $rs_ejecutado=$this->io_sql->select($ls_sql); 
	  if($rs_ejecutado===false)
	  { // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spg_ejecutado_trimestral ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_ejecutado))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_precomprometer=$row["precomprometer"];
		  $li_comprometer=$row["comprometer"];
		  $li_causar=$row["causar"];
		  $li_pagar=$row["pagar"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  
		  if($li_comprometer)
		  { 
		    $ad_comprometer=$ad_comprometer+$ld_monto;
		  }//if
		  if($li_causar)
		  { 
		    $ad_causado=$ad_causado+$ld_monto;
		  }//if
		  if($li_pagar)
		  { 
		    $ad_pagado=$ad_pagado+$ld_monto;
		  }//if
		  if($li_aumento)
		  {
		    $ad_aumento=$ad_aumento+$ld_monto;
		  }//if
		  if($li_disminucion)
		  { 
		    $ad_disminucion=$ad_disminucion+$ld_monto;
		  }//if
	    }// while
	    $this->io_sql->free_result($rs_ejecutado);
	   }//else	
	  return $lb_valido;	
     }//fin uf_spg_ejecutado_trimestral_estado_resultado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_acumulado_estado_resultado($as_spg_cuenta,$adt_fechas,&$ad_comprometer_acumulado,
	                                                     &$ad_causado_acumulado,&$ad_pagado_acumulado,&$ad_aumento_acumulado,
														 &$ad_disminucion_acumulado,$as_detallar)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_ejecutado_trimestral
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  -----> cuenta 
	 //                     $adt_fechas  -----> fechas hasta    
	 //                     $ad_comprometer_acumulado  -----> monto acumulado comprometer referencia   
	 //                     $ad_causado_acumulado  -----> monto acumulado causado referencia   
	 //                     $ad_pagado_acumulado  -----> monto acumulado pagado referencia   
	 //                     $ad_aumento_acumulado  -----> monto acumulado aumento referencia   
	 //                     $ad_disminucion_acumulado  -----> monto acumulado disminucion referencia   
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera los eejcutados por trimestre
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 if($as_detallar==true)
	 {
	   $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 }
	 elseif($as_detallar==false)
	 {
	   $as_spg_cuenta = $as_spg_cuenta;
	   $ls_cadena="spg_cuenta = '".$as_spg_cuenta."'  AND";
	 }
	  $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        ".$ls_cadena."  ".
               "        fecha <='".$adt_fechas."' ";	   
	  $rs_ejecutado=$this->io_sql->select($ls_sql);
	  if($rs_ejecutado===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_ejecutado_trimestral ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_ejecutado))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_precomprometer=$row["precomprometer"];
		  $li_comprometer=$row["comprometer"];
		  $li_causar=$row["causar"];
		  $li_pagar=$row["pagar"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  
		  if($li_comprometer)
		  { 
		    $ad_comprometer_acumulado=$ad_comprometer_acumulado+$ld_monto;
		  }//if
		  if($li_causar)
		  { 
		    $ad_causado_acumulado=$ad_causado_acumulado+$ld_monto;
		  }//if
		  if($li_pagar)
		  { 
		    $ad_pagado_acumulado=$ad_pagado_acumulado+$ld_monto;
		  }//if
		  if($li_aumento)
		  { 
		    $ad_aumento_acumulado=$ad_aumento_acumulado+$ld_monto;
		  }//if
		  if($li_disminucion)
		  { 
		    $ad_disminucion_acumulado=$ad_disminucion_acumulado+$ld_monto;
		  }//if
	    }// while
	    $this->io_sql->free_result($rs_ejecutado);
	   }//else	
	  return $lb_valido;	
     }//fin uf_spg_ejecutado_acumulado_estado_resultado
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reportes_select_denominacion($as_spi_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/06/2008                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion FROM sigesp_plan_unico_re WHERE sig_cuenta='".$as_spi_cuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
										"MÉTODO->uf_spg_reportes_select_denominacion ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 	
		}
		else
		{
		   if($row=$this->io_sql->fetch_row($rs_data))
		   {
			  $as_denominacion=$row["denominacion"];
		   }
		   $this->io_sql->free_result($rs_data);
		}
		return  $lb_valido;
     }//fin uf_spg_reportes_select_denominacion()
	//-----------------------------------------------------------------------------------------------------------------------------------
/********************************************************************************************************************************/
/*                                                        PRESUPUESTO DE CAJA                                                   */
/********************************************************************************************************************************/


function uf_spg_reportes_presupuesto_de_caja($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :  uf_spg_reportes_presupuesto_de_caja
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Presupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suárez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ld_asignado_i = 0;
	 $ld_asignado_modificado_i = 0;
	 $ld_programado_i = 0;
	 $ld_ejecutado_i = 0;
	 $ld_variacion_absoluta_i = 0;
	 $ld_variacion_porcentual_i = 0;
	 $ld_programado_acumulado_i = 0;
	 $ld_ejecutado_acumulado_i = 0;
	 
	  $lb_valido = $this->uf_spg_reporte_select_saldo_empresa();
	 if($lb_valido)
	 {
	  $lb_valido=$this->uf_spi_reporte_total_ingresos($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
	   $lb_valido=$this->uf_spi_reportes_ingresos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spi_reportes_ingresos_capital($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spi_reportes_ingresos_financieros($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spi_reportes_gastos_de_consumo($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spi_reportes_gastos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }	 
	 if($lb_valido)
	 {
		$la_cuenta[67]=array();
		
		// INGRESOS CORRIENTES
		$la_cuenta[1]='305010000';
		$la_cuenta[2]='305010100';
		$la_cuenta[3]='305010300';
		$la_cuenta[4]='305010301';
		$la_cuenta[5]='305010302';
		$la_cuenta[6]='305010304';
		$la_cuenta[7]='305010305';
		$la_cuenta[8]='305010306';
		$la_cuenta[9]='305010307';
		$la_cuenta[10]='305010308';
		$la_cuenta[11]='305010309';
		$la_cuenta[12]='305010500';
		$la_cuenta[13]='305010501';
		$la_cuenta[14]='305010502';
		$la_cuenta[15]='305010503';
		$la_cuenta[16]='301090000';
		$la_cuenta[17]='301090100';
		$la_cuenta[18]='301090200';
		$la_cuenta[19]='301099900';
		$la_cuenta[20]='408070000';
		$la_cuenta[21]='301030000';
		$la_cuenta[22]='301040000';
		$la_cuenta[23]='301050000';
		$la_cuenta[24]='301100000';
		$la_cuenta[25]='301100401';
		$la_cuenta[26]='301100400';
		$la_cuenta[27]='301100500';
		$la_cuenta[28]='301100800';
		$la_cuenta[29]='301110000';
		// INGRESOS DE CAPITAL
		$la_cuenta[30]='305020000';
		$la_cuenta[31]='305020100';
		$la_cuenta[32]='305020300';
		$la_cuenta[33]='305020301';
		$la_cuenta[34]='305020302';
		$la_cuenta[35]='305020308';
		$la_cuenta[36]='305020309';
		$la_cuenta[37]='305020500';
		$la_cuenta[38]='305020501';
		$la_cuenta[39]='305020502';
		$la_cuenta[40]='305020503';
		$la_cuenta[41]='306010000';
		$la_cuenta[42]='306020000';
		// INGRESOS FINANCIEROS
		$la_cuenta[43]='307000000';
		$la_cuenta[44]='308000000';
		$la_cuenta[45]='309000000';
		$la_cuenta[46]='311000000';
		// INCREMENTOS DE PASIVO
		$la_cuenta[47]='312000000';
		// INCREMENTO DE PATRIMONIO
		$la_cuenta[48]='313000001';
		// EGRESOS
		$la_cuenta[49]='400000000';
		// EGRESOS DE CONSUMO
		$la_cuenta[50]='401000000';
		$la_cuenta[51]='402000000';
		$la_cuenta[52]='403000000';
		$la_cuenta[53]='408000000';
		$la_cuenta[54]='408020000';
		$la_cuenta[55]='408060000';
		// OTROS EGRESOS CORRIENTES
		$la_cuenta[56]='407000000';
		$la_cuenta[57]='407010100';
		$la_cuenta[58]='407010300';
		$la_cuenta[59]='407020000';
		$la_cuenta[60]='407030000';
		$la_cuenta[61]='407030100';
		$la_cuenta[62]='407030300';
		$la_cuenta[63]='408080000';
		// ACTIVOS REALES
		$la_cuenta[64]='404000000';
		// ACTIVOS FINANCIEROS
		$la_cuenta[65]='405000000';
		// DISMINUCION DE PASIVOS
		$la_cuenta[66]='411000000';
		// DISMINUCION DE PATRIMONIO
		$la_cuenta[67]='412000000';
		
		$ld_asignado_vn = 0;
		$ld_asignado_modificado_vn = 0;
		$ld_programado_vn = 0;
		$ld_ejecutado_vn = 0;
		$ld_variacion_absoluta_vn = 0;
		$ld_variacion_porcentual_vn = 0;
		$ld_programado_acumulado_vn = 0;
		$ld_ejecutado_acumulado_vn = 0;
		
		$ld_total_asignado_ic=0;
		$ld_total_asignado_modificado_ic=0;
		$ld_total_programado_ic=0;
		$ld_total_ejecutado_ic=0;
		$ld_total_variacion_absoluta_ic=0;
		$ld_total_variacion_porcentual_ic=0;
		$ld_total_programado_acumulado_ic=0;
		$ld_total_ejecutado_acumulado_ic=0;
		
		$ld_total_asignado_if=0;
		$ld_total_asignado_modificado_if=0;
		$ld_total_programado_if=0;
		$ld_total_ejecutado_if=0;
		$ld_total_variacion_absoluta_if=0;
		$ld_total_variacion_porcentual_if=0;
		$ld_total_programado_acumulado_if=0;
		$ld_total_ejecutado_acumulado_if=0;
		
	    
		for($i=1;$i<=67;$i++)
		{
		   switch ($i)
		   {		
			 case 1:  //INGRESOS CORRIENTES
			    $ld_total_asignado_ic=0;
				$ld_total_asignado_modificado_ic=0;
				$ld_total_programado_ic=0;
				$ld_total_ejecutado_ic=0;
				$ld_total_variacion_absoluta_ic=0;
				$ld_total_variacion_porcentual_ic=0;
				$ld_total_programado_acumulado_ic=0;
				$ld_total_ejecutado_acumulado_ic=0;
  				$li_total=$this->dts_ingresos_corrientes->getRowCount("cuenta");
				if($li_total>0)
				{
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_ingresos_corrientes->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_ingresos_corrientes->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_ingresos_corrientes->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_ingresos_corrientes->getValue("modificado",$li);
					 $ld_programado=$this->dts_ingresos_corrientes->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_ingresos_corrientes->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_ingresos_corrientes->getValue("absoluto",$li);
					 $ld_variacion_porcentual=$this->dts_ingresos_corrientes->getValue("porcentual",$li);
					 $ld_programado_acumulado=$this->dts_ingresos_corrientes->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_ingresos_corrientes->getValue("ejecutado_acumulado",$li);
					 $ls_status=$this->dts_ingresos_corrientes->getValue("status",$li);
					 
					 if($ls_status == "C")
					 {
						 $ld_total_asignado_ic=$ld_total_asignado_ic + $ld_asignado;
						 $ld_total_asignado_modificado_ic=$ld_total_asignado_modificado_ic + $ld_asignado_modificado;
						 $ld_total_programado_ic=$ld_total_programado_ic + $ld_programado;
						 $ld_total_ejecutado_ic=$ld_total_ejecutado_ic + $ld_ejecutado;
						 $ld_total_variacion_absoluta_ic=$ld_total_variacion_absoluta_ic + $ld_variacion_absoluta;
						 $ld_total_variacion_porcentual_ic=$ld_total_variacion_porcentual_ic + $ld_variacion_porcentual;
						 $ld_total_programado_acumulado_ic=$ld_total_programado_acumulado_ic + $ld_programado_acumulado;
						 $ld_total_ejecutado_acumulado_ic=$ld_total_ejecutado_acumulado_ic + $ld_ejecutado_acumulado;
					 }
					 
				  }//for
				  
				  if ($ld_total_programado_ic> 0)
				{
				 $ld_porcentual = ($ld_total_ejecutado_ic/$ld_total_programado_ic)*100;
				}
				else
				{
				 $ld_porcentual = 0;
				}
					
					$this->dts_reporte->insertRow("cuenta","");
			   		$this->dts_reporte->insertRow("denominacion",'<b>INGRESOS CORRIENTES</b>');
					$this->dts_reporte->insertRow("asignado",$ld_total_asignado_ic);
					$this->dts_reporte->insertRow("modificado",$ld_total_asignado_modificado_ic);
					$this->dts_reporte->insertRow("programado",$ld_total_programado_ic);
					$this->dts_reporte->insertRow("ejecutado", $ld_total_ejecutado_ic);		
					$this->dts_reporte->insertRow("absoluto",abs($ld_total_ejecutado_ic - $ld_total_programado_ic));		
					$this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
					$this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado_ic);
					$this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado_ic);  
				}  
			 break;
			 
			 case 30:  //INGRESOS CAPITAL
			    $ld_total_asignado=0;
				$ld_total_asignado_modificado=0;
				$ld_total_programado=0;
				$ld_total_ejecutado=0;
				$ld_total_variacion_absoluta=0;
				$ld_total_variacion_porcentual=0;
				$ld_total_programado_acumulado=0;
				$ld_total_ejecutado_acumulado=0;
  				$li_total=$this->dts_ingresos_capital->getRowCount("cuenta");
				if($li_total>0)
				{
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_ingresos_capital->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_ingresos_capital->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_ingresos_capital->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_ingresos_capital->getValue("modificado",$li);
					 $ld_programado=$this->dts_ingresos_capital->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_ingresos_capital->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_ingresos_capital->getValue("absoluto",$li);
					 $ld_variacion_porcentual=$this->dts_ingresos_capital->getValue("porcentual",$li);
					 $ld_programado_acumulado=$this->dts_ingresos_capital->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_ingresos_capital->getValue("ejecutado_acumulado",$li);
					 $ls_status=$this->dts_ingresos_capital->getValue("status",$li);
					 if ($ls_status == "C")
					 {
						 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
						 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
						 $ld_total_programado=$ld_total_programado + $ld_programado;
						 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
						 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
						 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
						 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
						 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
					 }
					 
				  }//for
				  
				  	
				}
			    if ($ld_total_programado> 0)
				{
				 $ld_porcentual = ($ld_total_ejecutado/$ld_total_programado)*100;
				}
				else
				{
				 $ld_porcentual = 0;
				}
					
					$this->dts_reporte->insertRow("cuenta","");
			   		$this->dts_reporte->insertRow("denominacion",'<b>INGRESOS DE CAPITAL</b>');
					$this->dts_reporte->insertRow("asignado",$ld_total_asignado);
					$this->dts_reporte->insertRow("modificado",$ld_total_asignado_modificado);
					$this->dts_reporte->insertRow("programado",$ld_total_programado);
					$this->dts_reporte->insertRow("ejecutado", $ld_total_ejecutado);		
					$this->dts_reporte->insertRow("absoluto",abs($ld_total_ejecutado - $ld_total_programado));		
					$this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
					$this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
					$this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);  
			 break;
			 
			 case 43:  //INGRESOS FINANCIEROS
  				$li_total=$this->dts_ingresos_financieros->getRowCount("cuenta");
				if($li_total>0)
				{
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_ingresos_financieros->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_ingresos_financieros->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_ingresos_financieros->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_ingresos_financieros->getValue("modificado",$li);
					 $ld_programado=$this->dts_ingresos_financieros->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_ingresos_financieros->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_ingresos_financieros->getValue("absoluto",$li);
					 $ld_variacion_porcentual=$this->dts_ingresos_financieros->getValue("porcentual",$li);
					 $ld_programado_acumulado=$this->dts_ingresos_financieros->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_ingresos_financieros->getValue("ejecutado_acumulado",$li);
					 $ls_status=$this->dts_ingresos_financieros->getValue("status",$li);
		
					 if($ls_status == "C")
					 {
					 $ld_total_asignado_if=$ld_total_asignado_if + $ld_asignado;
					 $ld_total_asignado_modificado_if=$ld_total_asignado_modificado_if + $ld_asignado_modificado;
					 $ld_total_programado_if=$ld_total_programado_if + $ld_programado;
					 $ld_total_ejecutado_if=$ld_total_ejecutado_if + $ld_ejecutado;
					 $ld_total_variacion_absoluta_if=$ld_total_variacion_absoluta_if + $ld_variacion_absoluta;
					 $ld_total_variacion_porcentual_if=$ld_total_variacion_porcentual_if + $ld_variacion_porcentual;
					 $ld_total_programado_acumulado_if=$ld_total_programado_acumulado_if + $ld_programado_acumulado;
					 $ld_total_ejecutado_acumulado_if=$ld_total_ejecutado_acumulado_if + $ld_ejecutado_acumulado;
					 }
					 
				  }//for
				}
				if ($ld_total_programado_if> 0)
				{
				 $ld_porcentual = ($ld_total_ejecutado_if/$ld_total_programado_if)*100;
				}
				else
				{
				 $ld_porcentual = 0;
				}
				$this->dts_reporte->insertRow("cuenta","");
			   	$this->dts_reporte->insertRow("denominacion",'<b>INGRESOS FINANCIEROS</b>');
			   	$this->dts_reporte->insertRow("asignado",$ld_total_asignado_if);
			  	$this->dts_reporte->insertRow("modificado",$ld_total_asignado_modificado_if);
			    $this->dts_reporte->insertRow("programado",$ld_total_programado_if);
			    $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado_if);		
			    $this->dts_reporte->insertRow("absoluto",abs($ld_total_ejecutado_if - $ld_total_programado_if));		
			    $this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
			    $this->dts_reporte->insertRow("programado_acumulado", $ld_total_programado_acumulado_if);
			    $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado_if);  
			 break;
			 
			 case 49:
			   $ld_asignado_si=$this->dts_reporte->getValue("asignado",1);
			   $ld_asignado_modificado_si=$this->dts_reporte->getValue("modificado",1);
			   $ld_programado_si=$this->dts_reporte->getValue("programado",1);
			   $ld_ejecutado_si=$this->dts_reporte->getValue("ejecutado",1);
			   $ld_variacion_absoluta_si=$this->dts_reporte->getValue("absoluto",1);
			   $ld_programado_acumulado_si=$this->dts_reporte->getValue("programado_acumulado",1);
			   $ld_ejecutado_acumulado_si=$this->dts_reporte->getValue("ejecutado_acumulado",1);
			  
			   $ld_asignado_i=$this->dts_reporte->getValue("asignado",2);
			   $ld_asignado_modificado_i=$this->dts_reporte->getValue("modificado",2);
			   $ld_programado_i=$this->dts_reporte->getValue("programado",2);
			   $ld_ejecutado_i=$this->dts_reporte->getValue("ejecutado",2);
			   $ld_variacion_absoluta_i=$this->dts_reporte->getValue("absoluto",2);
			   $ld_programado_acumulado_i=$this->dts_reporte->getValue("programado_acumulado",2);
			   $ld_ejecutado_acumulado_i=$this->dts_reporte->getValue("ejecutado_acumulado",2);
			   
			   if (($ld_programado_si+$ld_programado_i)>0)
			   {
			    $ld_porcentual = (($ld_ejecutado_si+$ld_ejecutado_i)/($ld_programado_si+$ld_programado_i))*100;
			   }
			   else
			   {
			    $ld_porcentual = 0;
			   }
			   
			   $this->dts_reporte->insertRow("cuenta","");
			   $this->dts_reporte->insertRow("denominacion",'<b>SALDO INICIAL + INGRESOS </b>');
			   $this->dts_reporte->insertRow("asignado",$ld_asignado_si+$ld_asignado_i);
			   $this->dts_reporte->insertRow("modificado",$ld_asignado_modificado_si+$ld_asignado_modificado_i);
			   $this->dts_reporte->insertRow("programado",$ld_programado_si+$ld_programado_i);
		  	   $this->dts_reporte->insertRow("ejecutado",$ld_ejecutado_si+$ld_ejecutado_i);		
		  	   $this->dts_reporte->insertRow("absoluto",abs(($ld_ejecutado_si+$ld_ejecutado_i)-($ld_programado_si+$ld_programado_i)));		
		  	   $this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado_si+$ld_programado_acumulado_i);
		  	   $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_ejecutado_acumulado_si+$ld_ejecutado_acumulado_i);
	           $lb_valido=$this->uf_spg_reporte_total_egresos($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas);
			   break;
			 
			 case 50:  //EGRESOS DE CONSUMO
			    $ld_total_asignado=0;
				$ld_total_asignado_modificado=0;
				$ld_total_programado=0;
				$ld_total_ejecutado=0;
				$ld_total_variacion_absoluta=0;
				$ld_total_variacion_porcentual=0;
				$ld_total_programado_acumulado=0;
				$ld_total_ejecutado_acumulado=0;
  				$li_total=$this->dts_egresos_consumo->getRowCount("cuenta");
				if($li_total>0)
				{
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_egresos_consumo->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_egresos_consumo->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_egresos_consumo->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_egresos_consumo->getValue("modificado",$li);
					 $ld_programado=$this->dts_egresos_consumo->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_egresos_consumo->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_egresos_consumo->getValue("absoluto",$li);
					 $ld_variacion_porcentual=$this->dts_egresos_consumo->getValue("porcentual",$li);
					 $ld_programado_acumulado=$this->dts_egresos_consumo->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_egresos_consumo->getValue("ejecutado_acumulado",$li);
					 $li_nivel=$this->dts_egresos_consumo->getValue("nivel",$li);
					 
					 if($li_nivel == 1)
					 {
						 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
						 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
						 $ld_total_programado=$ld_total_programado + $ld_programado;
						 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
						 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
						 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
						 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
						 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
					 }	 
				  }//for
				if ($ld_total_programado> 0)
				{
				 $ld_porcentual = ($ld_total_ejecutado/$ld_total_programado)*100;
				}
				else
				{
				 $ld_porcentual = 0;
				}
				$this->dts_reporte->insertRow("cuenta","");
			   	$this->dts_reporte->insertRow("denominacion",'<b>EGRESOS DE CONSUMO</b>');
			   	$this->dts_reporte->insertRow("asignado",$ld_total_asignado);
			  	$this->dts_reporte->insertRow("modificado",$ld_total_asignado_modificado);
			    $this->dts_reporte->insertRow("programado",$ld_total_programado);
			    $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
			    $this->dts_reporte->insertRow("absoluto",abs($ld_total_ejecutado-$ld_total_programado));		
			    $this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
			    $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
			    $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_programado_acumulado);
				}  
			 break;
			 
			 case 56:  //EGRESOS CORRIENTES
			    $ld_total_asignado=0;
				$ld_total_asignado_modificado=0;
				$ld_total_programado=0;
				$ld_total_ejecutado=0;
				$ld_total_variacion_absoluta=0;
				$ld_total_variacion_porcentual=0;
				$ld_total_programado_acumulado=0;
				$ld_total_ejecutado_acumulado=0;
  				$li_total=$this->dts_egresos_corrientes->getRowCount("cuenta");
				if($li_total>0)
				{
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_egresos_corrientes->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_egresos_corrientes->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_egresos_corrientes->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_egresos_corrientes->getValue("modificado",$li);
					 $ld_programado=$this->dts_egresos_corrientes->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_egresos_corrientes->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_egresos_corrientes->getValue("absoluto",$li);
					 $ld_variacion_porcentual=$this->dts_egresos_corrientes->getValue("porcentual",$li);
					 $ld_programado_acumulado=$this->dts_egresos_corrientes->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_egresos_corrientes->getValue("ejecutado_acumulado",$li);
					 $li_nivel=$this->dts_egresos_corrientes->getValue("nivel",$li);
					 
					 if($li_nivel == 1)
					 {
						 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
						 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
						 $ld_total_programado=$ld_total_programado + $ld_programado;
						 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
						 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
						 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
						 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
						 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
					 }
					 
				  }//for
				}
				if ($ld_total_programado> 0)
				{
				 $ld_porcentual = ($ld_total_ejecutado/$ld_total_programado)*100;
				}
				else
				{
				 $ld_porcentual = 0;
				}
				$this->dts_reporte->insertRow("cuenta","");
			   	$this->dts_reporte->insertRow("denominacion",'<b>OTROS EGRESOS CORRIENTES</b>');
			   	$this->dts_reporte->insertRow("asignado",$ld_total_asignado);
			  	$this->dts_reporte->insertRow("modificado",$ld_total_asignado_modificado);
			    $this->dts_reporte->insertRow("programado",$ld_total_programado);
			    $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
			    $this->dts_reporte->insertRow("absoluto",abs($ld_total_ejecutado- $ld_total_programado));		
			    $this->dts_reporte->insertRow("porcentual",$ld_porcentual);		
			    $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
			    $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);  
			 break;
			 
		  }//switch	 
		           $ls_cuenta=$la_cuenta[$i];
				   $li_pos=$this->dts_reporte_temporal->find("cuenta",$ls_cuenta);
				   $ld_asignado_e = 0;
				   $ld_asignado_modificado_e = 0;
				   $ld_programado_e= 0;
				   $ld_ejecutado_e= 0;
				   $ld_variacion_absoluta_e= 0;
				   $ld_variacion_porcentual_e= 0;
				   $ld_programado_acumulado_e= 0;
				   $ld_ejecutado_acumulado_e= 0; 
				   
				   if(($li_pos>0)&&($i!=49))
				   { 		
					 $ls_spg_cuenta=$this->dts_reporte_temporal->getValue("cuenta",$li_pos);
					 $ls_denominacion=$this->dts_reporte_temporal->getValue("denominacion",$li_pos);
					 $ld_asignado=$this->dts_reporte_temporal->getValue("asignado",$li_pos);
					 $ld_asignado_modificado=$this->dts_reporte_temporal->getValue("modificado",$li_pos);
					 $ld_programado=$this->dts_reporte_temporal->getValue("programado",$li_pos);
					 $ld_ejecutado=$this->dts_reporte_temporal->getValue("ejecutado",$li_pos);
					 $ld_variacion_absoluta=$this->dts_reporte_temporal->getValue("absoluto",$li_pos);
					 $ld_variacion_porcentual=$this->dts_reporte_temporal->getValue("porcentual",$li_pos);
					 $ld_programado_acumulado=$this->dts_reporte_temporal->getValue("programado_acumulado",$li_pos);
					 $ld_ejecutado_acumulado=$this->dts_reporte_temporal->getValue("ejecutado_acumulado",$li_pos);
					  if (($i == 17)||($i == 18)||($i == 19))
					 {
					  $ld_asignado_vn = $ld_asignado_vn + $ld_asignado;
					  $ld_asignado_modificado_vn = $ld_asignado_modificado_vn + $ld_asignado_modificado;
					  $ld_programado_vn = $ld_programado_vn + $ld_programado;
					  $ld_ejecutado_vn = $ld_ejecutado_vn + $ld_ejecutado;
					  $ld_variacion_absoluta_vn = $ld_variacion_absoluta_vn + $ld_variacion_absoluta;
					  $ld_variacion_porcentual_vn = $ld_variacion_porcentual_vn + $ld_variacion_porcentual;
					  $ld_programado_acumulado_vn = $ld_programado_acumulado_vn + $ld_programado_acumulado;
					  $ld_ejecutado_acumulado_vn = $ld_ejecutado_acumulado_vn + $ld_programado_acumulado;
					 }
					 if($i == 20)
					 {
					  $ld_asignado_e=$this->dts_reporte_temporal->getValue("asignado",$li_pos);
					  $ld_asignado_modificado_e=$this->dts_reporte_temporal->getValue("modificado",$li_pos);
					  $ld_programado_e=$this->dts_reporte_temporal->getValue("programado",$li_pos);
					  $ld_ejecutado_e=$this->dts_reporte_temporal->getValue("ejecutado",$li_pos);
					  $ld_variacion_absoluta_e=$this->dts_reporte_temporal->getValue("absoluto",$li_pos);
					  $ld_variacion_porcentual_e=$this->dts_reporte_temporal->getValue("porcentual",$li_pos);
					  $ld_programado_acumulado_e=$this->dts_reporte_temporal->getValue("programado_acumulado",$li_pos);
					  $ld_ejecutado_acumulado_e=$this->dts_reporte_temporal->getValue("ejecutado_acumulado",$li_pos);
					 }
					  $ld_asignado_vn = $ld_asignado_vn - $ld_asignado_e;
					  $ld_asignado_modificado_vn = $ld_asignado_modificado_vn - $ld_asignado_modificado_e;
					  $ld_programado_vn = $ld_programado_vn - $ld_programado_e;
					  $ld_ejecutado_vn = $ld_ejecutado_vn - $ld_ejecutado_e;
					  $ld_variacion_absoluta_vn = $ld_variacion_absoluta_vn - $ld_variacion_absoluta_e;
					  $ld_variacion_porcentual_vn = $ld_variacion_porcentual_vn - $ld_variacion_porcentual_e;
					  $ld_programado_acumulado_vn = $ld_programado_acumulado_vn - $ld_programado_acumulado_e;
					  $ld_ejecutado_acumulado_vn = $ld_ejecutado_acumulado_vn - $ld_programado_acumulado_e;
					  if($i == 21)
					  {
					 	  $this->dts_reporte->insertRow("cuenta","");
						  $this->dts_reporte->insertRow("denominacion","Ventas Netas");
						  $this->dts_reporte->insertRow("asignado",$ld_asignado_vn);
						  $this->dts_reporte->insertRow("modificado",ld_asignado_modificado_vn);
						  $this->dts_reporte->insertRow("programado",$ld_programado_vn);
						  $this->dts_reporte->insertRow("ejecutado",$ld_ejecutado_vn);		
						  $this->dts_reporte->insertRow("absoluto",0);		
						  $this->dts_reporte->insertRow("porcentual",0);		
						  $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado_vn);
						  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_ejecutado_acumulado_vn);  
					  
					 }
					 $this->dts_reporte->insertRow("cuenta",$ls_spg_cuenta);
					 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					 $this->dts_reporte->insertRow("asignado",$ld_asignado);
					 $this->dts_reporte->insertRow("modificado",$ld_asignado_modificado);
					 $this->dts_reporte->insertRow("programado",$ld_programado);
					 $this->dts_reporte->insertRow("ejecutado",$ld_ejecutado);		
					 $this->dts_reporte->insertRow("absoluto",$ld_variacion_absoluta);		
					 $this->dts_reporte->insertRow("porcentual",$ld_variacion_porcentual);		
					 $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
					 $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_ejecutado_acumulado);
				  }//if
				  else
				  {
				     $ls_denom="";
				     $lb_valido=$this->uf_spg_reportes_select_denominacion($ls_cuenta,$ls_denom);
				     if($lb_valido)
				     {
					     if($i == 20)
					     {
					      $ls_denom = " Menos: ".$ls_denom;
						  $this->dts_reporte->insertRow("cuenta",$ls_cuenta);
						  $this->dts_reporte->insertRow("denominacion",$ls_denom);
						  $this->dts_reporte->insertRow("asignado",0);
						  $this->dts_reporte->insertRow("modificado",0);
						  $this->dts_reporte->insertRow("programado",0);
						  $this->dts_reporte->insertRow("ejecutado",0);		
						  $this->dts_reporte->insertRow("absoluto",0);		
						  $this->dts_reporte->insertRow("porcentual",0);		
						  $this->dts_reporte->insertRow("programado_acumulado",0);
						  $this->dts_reporte->insertRow("ejecutado_acumulado",0);
					     }
						 else
						 {
						  if($i!=49)
						  { 
						   $this->dts_reporte->insertRow("cuenta",$ls_cuenta);
						   $this->dts_reporte->insertRow("denominacion",$ls_denom);
						   $this->dts_reporte->insertRow("asignado",0);
						   $this->dts_reporte->insertRow("modificado",0);
						   $this->dts_reporte->insertRow("programado",0);
						   $this->dts_reporte->insertRow("ejecutado",0);		
						   $this->dts_reporte->insertRow("absoluto",0);		
						   $this->dts_reporte->insertRow("porcentual",0);		
						   $this->dts_reporte->insertRow("programado_acumulado",0);
						   $this->dts_reporte->insertRow("ejecutado_acumulado",0);
						  } 
						 }
				     }//if
				  }//else
				  if($i == 21)
				  {
					 	  $this->dts_reporte->insertRow("cuenta","");
						  $this->dts_reporte->insertRow("denominacion","Ventas Netas");
						  $this->dts_reporte->insertRow("asignado",$ld_asignado_vn);
						  $this->dts_reporte->insertRow("modificado",$ld_asignado_modificado_vn);
						  $this->dts_reporte->insertRow("programado",$ld_programado_vn);
						  $this->dts_reporte->insertRow("ejecutado",$ld_ejecutado_vn);		
						  $this->dts_reporte->insertRow("absoluto",0);		
						  $this->dts_reporte->insertRow("porcentual",0);		
						  $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado_vn);
						  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_ejecutado_acumulado_vn);  
			     }
				  
		}		
	 }	  
				 
  return $lb_valido;
}//fin uf_spg_reportes_presupuesto_de_caja
	

	function uf_spi_reportes_ingresos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_ingresos_corrientes
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del PResupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suarez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion,max(status) as status,        ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         			".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    			".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  			".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, 			".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        			".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             			".
			  "        sum(diciembre) as diciembre,                                                      			".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       			".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                     		 	".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    			".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        (spi_cuenta like '305010000%' OR spi_cuenta like '305010100%' OR spi_cuenta like '305010300%' ".
			  "        OR spi_cuenta like '305010301%' OR".
			  "        spi_cuenta like '305010302%' OR spi_cuenta like '305010303%' OR spi_cuenta like '305010304%' OR ".
			  "        spi_cuenta like '305010305%' OR spi_cuenta like '305010306%' OR spi_cuenta like '305010307%' OR ".
			  "        spi_cuenta like '305010308%' OR spi_cuenta like '305010309%' OR spi_cuenta like '305010500%' OR ".
			  "        spi_cuenta like '305010501%' OR spi_cuenta like '305010502%' OR spi_cuenta like '305010503%' OR ".
			  "        spi_cuenta like '301090000%' OR spi_cuenta like '301090100%' OR spi_cuenta like '301090200%' OR ".
			  "        spi_cuenta like '301099900%') AND ". 
			  " 	   status = 'C' ";  
	  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_detallar=true; 

			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion,
															 $ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																$ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_cobrado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_corrientes->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_corrientes->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_corrientes->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_corrientes->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_corrientes->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_ingresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $this->dts_ingresos_corrientes->insertRow("status",$ls_status);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
	  
	   $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, max(status) as status 
	   , sum(asignado) as asignado, ".
              "        sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
              "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
	  	      " FROM   spg_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spg_cuenta like '408070000%' ".
			  " GROUP BY spg_cuenta".
			  " ORDER BY spg_cuenta ";	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_spg_cuenta = substr($ls_spg_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_asignado=$row["asignado"];
			   $ld_comprometido_total=$row["comprometido"];
			   $ld_causado_total=$row["causado"];
			   $ld_pagado_total=$row["pagado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_detallar=false; 
			   $lb_valido=$this->uf_spg_ejecutado_trimestral_estado_resultado($ls_spg_cuenta,$adt_fecdes,$adt_fechas,
			                                                                  &$ld_comprometer,&$ld_causado,&$ld_pagado,
																			  &$ld_aumento,&$ld_disminucion,$ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado_estado_resultado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
				                                                                 &$ld_causado_acumulado,&$ld_pagado_acumulado,
																				 &$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																                 $ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_asignado_modificado=$ld_asignado+$ld_aumento-$ld_disminucion;
			    if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_causado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_corrientes->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_ingresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_corrientes->insertRow("asignado",$ld_asignado);
			   $this->dts_ingresos_corrientes->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_ingresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_ingresos_corrientes->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_corrientes->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_ingresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
	  
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion,                  ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         ".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			  "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spi_cuenta like '301030000%' OR spi_cuenta like '301040000%' OR spi_cuenta like '301050000%' ".
			  "        OR spi_cuenta like '301100000%' OR".
			  "        spi_cuenta like '301100401%' OR spi_cuenta like '301100400%' OR spi_cuenta like '301100500%' OR ".
			  "        spi_cuenta like '301100800%' OR spi_cuenta like '301110000%' ";  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_corrientes ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
               $ls_detallar = true;
			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion,
															 $ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																$ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_cobrado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_corrientes->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_corrientes->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_corrientes->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_corrientes->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_corrientes->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_ingresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_corrientes->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $this->dts_ingresos_corrientes->insertRow("status",$ls_status);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual", $ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
    return $lb_valido;
    }//fin uf_spg_reportes_gastos_corrientes
	

	function uf_spi_reportes_ingresos_capital($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_ingresos_capital
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del PResupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suarez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion, max(status) as status,                  ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         ".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimestrei, sum(abril+mayo+junio) as trimestreii,       ".
			  "        sum(julio+agosto+septiembre) as trimestreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimestreiv                                    ".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spi_cuenta like '305020000%' OR spi_cuenta like '305020100%' OR spi_cuenta like '305020300%' OR  ".
			  "        spi_cuenta like '305020301%' OR spi_cuenta like '305020302%' OR spi_cuenta like '305020308%' OR ".
			  "        spi_cuenta like '305020309%' OR spi_cuenta like '305020500%' OR spi_cuenta like '305020501%' OR ".
			  "        spi_cuenta like '305020502%' OR spi_cuenta like '305020503%' OR spi_cuenta like '306010000%' OR ".
			  "        spi_cuenta like '306020000%' ";  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_capital ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimestrei"]; 
			   $ld_trimetreII=$row["trimestreii"]; 
			   $ld_trimetreIII=$row["trimestreiii"]; 
			   $ld_trimetreIV=$row["trimestreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_detallar=false; 
			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion,
															 $ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																$ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_cobrado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_capital->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_capital->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_capital->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_capital->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_capital->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_capital->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_capital->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_capital->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_ingresos_capital->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_capital->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $this->dts_ingresos_capital->insertRow("status",$ls_status);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
    return $lb_valido;
    }//fin uf_spg_reportes_gastos_capital

function uf_spi_reportes_ingresos_financieros($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_ingresos_financieros
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del PResupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suarez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql=" SELECT max(spi_cuenta) as spi_cuenta,max(denominacion) as denominacion,max(status) as status,                  ".
              "        sum(previsto) as previsto, sum(cobrado_anticipado) as cobrado_anticipado,         ".
              "        sum(cobrado) as cobrado, sum(devengado) as devengado, sum(aumento) as aumento,    ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimestrei, sum(abril+mayo+junio) as trimestreii,       ".
			  "        sum(julio+agosto+septiembre) as trimestreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimestreiv                                    ".
	  	      " FROM   spi_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spi_cuenta like '307000000%' OR spi_cuenta like '308000000%' OR spi_cuenta like '309000000%' ".
			  "        OR spi_cuenta like '311000000%' OR".
			  "        spi_cuenta like '312000000%' OR spi_cuenta like '313000001%' ";  	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_reporte_pres_caja_07 ". 
			                        "MÉTODO->uf_spi_reportes_ingresos_capital ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $ld_previsto=$row["previsto"];
			   $ld_cobrado_total=$row["cobrado"];
			   $ld_devengado_total=$row["devengado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimestrei"]; 
			   $ld_trimetreII=$row["trimestreii"]; 
			   $ld_trimetreIII=$row["trimestreiii"]; 
			   $ld_trimetreIV=$row["trimestreiv"]; 
			   $ls_spi_cuenta = substr($ls_spi_cuenta,0,9);
			   if(($ls_spi_cuenta=='311000000')||($ls_spi_cuenta=='312000000')||($ls_spi_cuenta=='313000001'))
			   {
			     $ls_detallar=true; 
			   }
			   else
			   {
			     $ls_detallar=false; 
			   }
			   $lb_valido=$this->uf_spi_ejecutado_trimestral($ls_spi_cuenta,$adt_fecdes,$adt_fechas,&$ld_cobrado_anticipado,
			                                                 &$ld_cobrado,&$ld_devengado,&$ld_aumento,&$ld_disminucion,
															 $ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spi_ejecutado_acumulado($ls_spi_cuenta,$adt_fechas,&$ld_cobrado_anticipado_acumulado,
																&$ld_cobrado_acumulado,&$ld_devengado_acumulado,
																&$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																$ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_previsto_modificado=$ld_previsto+$ld_aumento-$ld_disminucion;
			   if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_cobrado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_ingresos_financieros->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_financieros->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_financieros->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_financieros->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_financieros->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_financieros->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_financieros->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_ingresos_financieros->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_ingresos_financieros->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_financieros->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $this->dts_ingresos_financieros->insertRow("status",$ls_status);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_cobrado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
	  }//else
    return $lb_valido;
    }//fin uf_spi_reportes_gastos_financieros


	function uf_spi_reportes_gastos_de_consumo($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_gastos_de_consumo
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Presupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suárez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	  $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignado) as asignado, max(status) as status, ".
              "        max(nivel) as nivel, sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
              "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimestrei, sum(abril+mayo+junio) as trimestreii,       ".
			  "        sum(julio+agosto+septiembre) as trimestreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimestreiv                                    ".
	  	      " FROM   spg_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spg_cuenta like '401000000%' OR spg_cuenta like '402000000%' OR spg_cuenta like '403000000%' OR ".
			  "        spg_cuenta like '408000000%' OR spg_cuenta like '408020000%' OR spg_cuenta like '408060000%' ".
			  " GROUP BY spg_cuenta".
			  " ORDER BY spg_cuenta ";	  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_reportes_gastos_de_consumo ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_spg_cuenta = substr($ls_spg_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $li_nivel=$row["nivel"];
			   $ld_asignado=$row["asignado"];
			   $ld_comprometido_total=$row["comprometido"];
			   $ld_causado_total=$row["causado"];
			   $ld_pagado_total=$row["pagado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimestrei"]; 
			   $ld_trimetreII=$row["trimestreii"]; 
			   $ld_trimetreIII=$row["trimestreiii"]; 
			   $ld_trimetreIV=$row["trimestreiv"]; 
			   $ls_detallar=false; 
			   $lb_valido=$this->uf_spg_ejecutado_trimestral_estado_resultado($ls_spg_cuenta,$adt_fecdes,$adt_fechas,
			                                                                  &$ld_comprometer,&$ld_causado,&$ld_pagado,
																			  &$ld_aumento,&$ld_disminucion,$ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado_estado_resultado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
				                                                                 &$ld_causado_acumulado,&$ld_pagado_acumulado,
																				 &$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																                 $ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_asignado_modificado=$ld_asignado+$ld_aumento-$ld_disminucion;
			    if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_causado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_egresos_consumo->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_egresos_consumo->insertRow("denominacion",$ls_denominacion);
			   $this->dts_egresos_consumo->insertRow("asignado",$ld_asignado);
			   $this->dts_egresos_consumo->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_egresos_consumo->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_egresos_consumo->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_egresos_consumo->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_egresos_consumo->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_egresos_consumo->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_egresos_consumo->insertRow("ejecutado_acumulado",$ld_comprometer_acumulado);
			   $this->dts_egresos_consumo->insertRow("status",$ls_status);
			   $this->dts_egresos_consumo->insertRow("nivel",$li_nivel);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_gastos_de_consumo
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_spi_reportes_gastos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spi_reportes_gastos_corrientes
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Presupuesto de Caja
	 //     Creado por : Ing. Arnaldo Suárez
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
	  $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignado) as asignado, max(status) as status, ".
              "        max(nivel) as nivel, sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
              "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
              "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
              "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
              "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			  "        sum(diciembre) as diciembre,                                                      ".
			  "        sum(enero+febrero+marzo) as trimestrei, sum(abril+mayo+junio) as trimestreii,       ".
			  "        sum(julio+agosto+septiembre) as trimestreiii,                                      ".
			  "        sum(octubre+noviembre+diciembre) as trimestreiv                                    ".
	  	      " FROM   spg_cuentas ".
			  " WHERE  codemp='".$this->ls_codemp."' AND ".
			  "        spg_cuenta like '407000000%' OR spg_cuenta like '407010100%' OR spg_cuenta like '407010300%' OR ".
			  "        spg_cuenta like '407020000%' OR spg_cuenta like '407030100%' OR spg_cuenta like '407030300%' OR ".
			  "        spg_cuenta like '408080000%' OR spg_cuenta like '404000000%' OR spg_cuenta like '405000000%' OR ".
			  "        spg_cuenta like '411000000%' OR spg_cuenta like '412000000%' ".
			  " GROUP BY spg_cuenta".
			  " ORDER BY spg_cuenta ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_reportes_gastos_de_consumo ".
									"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		$lb_valido = false;
 	  }
	  else
	  {
		$li_numrows=$this->io_sql->num_rows($rs_data);	
		if($li_numrows>=0)
		{
		     while($row=$this->io_sql->fetch_row($rs_data))
			 {
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_spg_cuenta = substr($ls_spg_cuenta,0,9);
			   $ls_denominacion=$row["denominacion"];
			   $ls_status=$row["status"];
			   $li_nivel=$row["nivel"];
			   $ld_asignado=$row["asignado"];
			   $ld_comprometido_total=$row["comprometido"];
			   $ld_causado_total=$row["causado"];
			   $ld_pagado_total=$row["pagado"];
			   $ld_aumento_total=$row["aumento"];
			   $ld_disminucion_total=$row["disminucion"];
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
			   $ld_trimetreI=$row["trimestrei"]; 
			   $ld_trimetreII=$row["trimestreii"]; 
			   $ld_trimetreIII=$row["trimestreiii"]; 
			   $ld_trimetreIV=$row["trimestreiv"]; 
			   if(($ls_spg_cuenta=='407010300')||($ls_spg_cuenta=='405000000')||($ls_spg_cuenta=='411000000')||($ls_spg_cuenta=='412000000'))
			   {
			     $ls_detallar=true; 
			   }
			   else
			   {
			     $ls_detallar=false; 
			   }
			   $lb_valido=$this->uf_spg_ejecutado_trimestral_estado_resultado($ls_spg_cuenta,$adt_fecdes,$adt_fechas,
			                                                                  &$ld_comprometer,&$ld_causado,&$ld_pagado,
																			  &$ld_aumento,&$ld_disminucion,$ls_detallar);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado_estado_resultado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
				                                                                 &$ld_causado_acumulado,&$ld_pagado_acumulado,
																				 &$ld_aumento_acumulado,&$ld_disminucion_acumulado,
																                 $ls_detallar);
			   }//if
			   if($as_mesdes=='Enero')
		       {
				   $ld_programado_trimestral=$ld_trimetreI;
				   $ld_programado_acumulado=$ld_trimetreI;
			   }//if
			   if($as_mesdes=='Abril')
		       {
				   $ld_programado_trimestral=$ld_trimetreII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII;
			   }//if
			   if($as_mesdes=='Junio')
		       {
				   $ld_programado_trimestral=$ld_trimetreIII;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII;
			   }//if
			   if($as_mesdes=='Octubre')
		       {
				   $ld_programado_trimestral=$ld_trimetreIV;
				   $ld_programado_acumulado=$ld_trimetreI+$ld_trimetreII+$ld_trimetreIII+$ld_trimetreIV;
			   }//if
			   $ld_asignado_modificado=$ld_asignado+$ld_aumento-$ld_disminucion;
			    if ($ld_programado_trimestral > 0)
			   {
			    $ld_porcentual =($ld_causado/$ld_programado_trimestral)*100;
			   }
			   else
			   {
			    $ld_porcentual =0;
			   }
			   $this->dts_egresos_corrientes->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_egresos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_egresos_corrientes->insertRow("asignado",$ld_asignado);
			   $this->dts_egresos_corrientes->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_egresos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_egresos_corrientes->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_egresos_corrientes->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_egresos_corrientes->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_egresos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_egresos_corrientes->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   $this->dts_egresos_corrientes->insertRow("status",$ls_status);
			   $this->dts_egresos_corrientes->insertRow("nivel",$li_nivel);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_temporal->insertRow("modificado",$ld_asignado_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_causado);		
		  	   $this->dts_reporte_temporal->insertRow("absoluto",abs($ld_causado-$ld_programado_trimestral));		
		  	   $this->dts_reporte_temporal->insertRow("porcentual",$ld_porcentual);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_causado_acumulado);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_gastos_de_consumo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
		function uf_spg_reporte_select_saldo_empresa()
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_saldo_empresa
	 //         Access :	private
	 //     Argumentos :    $ad_salinipro --> saldo inicial programado(referencia) 
	 //                     $ad_salinieje --> saldo inicial ejecutado(referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Arnaldo Suárez.
	 // Fecha Creación :    18/08/2008               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	 
	  $ls_sql=" SELECT salinipro, salinieje  FROM sigesp_empresa WHERE codemp='".$this->ls_codemp."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spi_class_reportes_instructivos  MÉTODO->uf_spg_reporte_select_saldo_empresa  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			  $ad_salinipro=$row["salinipro"];
			  $ad_salinieje=$row["salinieje"];	
			  
			  $this->dts_reporte->insertRow("cuenta","");
			  $this->dts_reporte->insertRow("denominacion",'<b>SALDO INICIAL</b>');
			  $this->dts_reporte->insertRow("asignado",$ad_salinipro);
			  $this->dts_reporte->insertRow("modificado",0);
			  $this->dts_reporte->insertRow("programado",0);
			  $this->dts_reporte->insertRow("ejecutado",0);		
			  $this->dts_reporte->insertRow("absoluto",0);		
			  $this->dts_reporte->insertRow("porcentual",0);		
			  $this->dts_reporte->insertRow("programado_acumulado",0);
			  $this->dts_reporte->insertRow("ejecutado_acumulado",$ad_salinieje); 
	    }
		$this->io_sql->free_result($rs_data);
      }//else
	  return $lb_valido;
   }//fin uf_spg_reporte_select_saldo_empresa
/****************************************************************************************************************************************/	
}//fin de clase
?>