<?php
class sigesp_spg_class_reportes_instructivos
{
	var $io_mensajes;
	var $io_fecha;
	var $io_sigesp_int_spg;
	var $io_sql;
	var $ls_codemp;
	var $ls_gestor;
	var $li_estmodest;	
	var $sqlca;   
    var $is_msg_error;
	var $dts_empresa; 
	var $dts_reporte;
	var $dts_cab;
	var $obj="";
	var $SQL;
	var $siginc;
	var $con;
	var $fun;	
	var $dts_prog;
	var $dts_ingresos_generales;
	var $dts_ingresos_actividadespropias;
	var $dts_ingresos_gastoscorrientes;
	var $dts_ingresos_ingresoscorrientes;
	var $dts_gastos_consumo;
	var $dts_gastos_corrientes;
	var $dts_reporte_temporal;
	var $dts_resultado;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function  sigesp_spg_class_reportes_instructivos()
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
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();
		$this->io_sigesp_int_spi = new class_sigesp_int_spi();
		$this->dts_ingresos_generales = new class_datastore();
		$this->dts_ingresos_actividadespropias = new class_datastore();
		$this->dts_ingresos_gastoscorrientes = new class_datastore();
		$this->dts_ingresos_ingresoscorrientes = new class_datastore();
		$this->dts_gastos_consumo = new class_datastore();
		$this->dts_gastos_corrientes = new class_datastore();
		$this->dts_reporte_temporal = new class_datastore();
		$this->dts_resultado = new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_mensajes = new class_mensajes();
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();
		$this->ls_codemp = $_SESSION["la_empresa"]["codemp"];
	    $this->ls_gestor = $_SESSION["ls_gestor"];
	    $this->li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$this->dts_empresa=$_SESSION["la_empresa"];
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	//////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVOS " CONSOLIDADO DE EJECUCION TRIMESTRAL "  //
	/////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reporte_consolidado_de_ejecucion_trimestral($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_consolidado_de_ejecucion_trimestral
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
	 $ls_sql=" SELECT max(codemp) as codemp, spg_cuenta, max(denominacion) as denominacion,             ".
	         "        max(status) as status, max(sc_cuenta) as sc_cuenta, sum(asignado) as asignado,    ".
             "        sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
             "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
             "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
             "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
             "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
             "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			 "        sum(diciembre) as diciembre, max(nivel) as nivel, max(referencia) as referencia,  ".
			 "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			 "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			 "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
             " FROM spg_cuentas                                                                         ".
             " WHERE codemp='".$this->ls_codemp."'  AND  nivel = '1'                                    ".
             " GROUP BY spg_cuenta                                                                      ".
             " ORDER BY spg_cuenta ";	 	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spg_reporte_consolidado_de_ejecucion_trimestral ".
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
			   $ls_denominacion=$row["denominacion"];
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
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   
			   $lb_valido=$this->uf_spg_ejecutado_trimestral($ls_spg_cuenta,$adt_fecdes,$adt_fechas,&$ld_comprometer,
			                                                 &$ld_causado,&$ld_pagado,&$ld_aumento,&$ld_disminucion);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
																&$ld_causado_acumulado,&$ld_pagado_acumulado,&$ld_aumento_acumulado,
																&$ld_disminucion_acumulado);
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
			   $ld_disponibilidad=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_comprometer;
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte->insertRow("asignado_modificado",$ld_asignado_modificado);
			   $this->dts_reporte->insertRow("programado",$ld_programado_trimestral);
			   $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
		  	   $this->dts_reporte->insertRow("causado",$ld_causado);					 
			   $this->dts_reporte->insertRow("pagado",$ld_pagado);					 
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
			   $this->dts_reporte->insertRow("compromiso_acumulado",$ld_comprometer_acumulado);
		  	   $this->dts_reporte->insertRow("causado_acumulado",$ld_causado_acumulado);					 
			   $this->dts_reporte->insertRow("pagado_acumulado",$ld_pagado_acumulado);	
			   $this->dts_reporte->insertRow("disponibilidad",$ld_disponibilidad);	
			   $lb_valido=true;
		    }//while
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_trimestral($as_spg_cuenta,$adt_fecdes,$adt_fechas,&$ad_comprometer,&$ad_causado,&$ad_pagado,
	                                     &$ad_aumento,&$ad_disminucion)
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
	 $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        spg_cuenta like '".$as_spg_cuenta."'  AND ".
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
     }//fin uf_spg_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_acumulado($as_spg_cuenta,$adt_fechas,&$ad_comprometer_acumulado,&$ad_causado_acumulado,
	                                    &$ad_pagado_acumulado,&$ad_aumento_acumulado,&$ad_disminucion_acumulado)
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
	 $ad_comprometer_acumulado = 0;
	 $ad_causado_acumulado     = 0;
	 $ad_pagado_acumulado      = 0;
	 $ad_aumento_acumulado     = 0;
	 $ad_disminucion_acumulado = 0;	 
	 $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        spg_cuenta like '".$as_spg_cuenta."'  AND ".
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
     }//fin uf_spg_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------

 function uf_spg_reportes_ejecucion_trimestral($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,
	                                              $as_codestpro4_ori,$as_codestpro5_ori,$as_codestpro1_des,
								                  $as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
											      $as_codestpro5_des,$adt_fecdes,$adt_fechas,
												  $as_codfuefindes,$as_codfuefinhas,$as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_ejecucion_trimestral
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
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
		$ls_estructura5_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
		$ls_estructura5_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;	 
		$ls_estructura4_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori;
		$ls_estructura4_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des;	 
		$ls_estructura3_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori;
		$ls_estructura3_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des;
		$ls_estructura2_desde=$as_codestpro1_ori.$as_codestpro2_ori;
		$ls_estructura2_hasta=$as_codestpro1_des.$as_codestpro2_des;	 
		$ls_estructura1_desde=$as_codestpro1_ori;
		$ls_estructura1_hasta=$as_codestpro1_des;	 
		$li_mesdes=$adt_fecdes;
		$li_meshas=$adt_fechas;
		$li_trimestre = substr($li_mesdes,5,2);
		$li_trimestre = intval($li_trimestre);
		$ls_criterio="";
		if($as_codestpro1_ori!="0000000000000000000000000")
		{
			if($as_codestpro2_ori!="0000000000000000000000000")
			{
				if($as_codestpro3_ori!="0000000000000000000000000")
				{
					if($as_codestpro4_ori!="0000000000000000000000000")
					{
						if($as_codestpro5_ori!="0000000000000000000000000")
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4,C.codestpro5)>='".$ls_estructura5_desde."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4||C.codestpro5 >= '".$ls_estructura5_desde."'";
							}
						}
						else
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4)>='".$ls_estructura4_desde."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4 >= '".$ls_estructura4_desde."'";
							}
						}
					}
					else
					{
						if (strtoupper($ls_gestor)=="MYSQLT")
						{
						   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2,C.codestpro3>='".$ls_estructura3_desde."'";
						}
						else
						{
						   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2||C.codestpro3 >= '".$ls_estructura3_desde."'";
						}
					}
				}
				else
				{
					if (strtoupper($ls_gestor)=="MYSQLT")
					{
					   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2)>='".$ls_estructura2_desde."'";
					}
					else
					{
					   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2 >= '".$ls_estructura2_desde."'";
					}
				}
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND C.codestpro1>='".str_pad($as_codestpro1_ori,25,'0',0)."'";
			}
			
		}

		if($as_codestpro1_des!="0000000000000000000000000")
		{
			if($as_codestpro2_des!="0000000000000000000000000")
			{
				if($as_codestpro3_des!="0000000000000000000000000")
				{
					if($as_codestpro4_des!="0000000000000000000000000")
					{
						if($as_codestpro5_des!="0000000000000000000000000")
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4,C.codestpro5)<='".$ls_estructura5_hasta."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4||C.codestpro5 <= '".$ls_estructura5_hasta."'";
							}
						}
						else
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4)<='".$ls_estructura4_hasta."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4 <= '".$ls_estructura4_hasta."'";
							}
						}
					}
					else
					{
						if (strtoupper($ls_gestor)=="MYSQLT")
						{
						   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2,C.codestpro3<='".$ls_estructura3_hasta."'";
						}
						else
						{
						   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2||C.codestpro3 <= '".$ls_estructura3_hasta."'";
						}
					}
				}
				else
				{
					if (strtoupper($ls_gestor)=="MYSQLT")
					{
					   $ls_criterio= $ls_criterio."AND CONCAT(C.codestpro1,C.codestpro2)<='".$ls_estructura2_hasta."'";
					}
					else
					{
					   $ls_criterio= $ls_criterio." AND C.codestpro1||C.codestpro2 <= '".$ls_estructura2_hasta."'";
					}
				}
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND C.codestpro1<='".str_pad($as_codestpro1_des,25,'0',0)."'";
			}
			
		}

		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
		   $ls_tabla="spg_ep3"; 
		   $ls_and= "   AND C.codestpro1=EP.codestpro1".
					"   AND C.codestpro2=EP.codestpro2".
					"   AND C.codestpro3=EP.codestpro3".
					"   AND C.estcla=EP.estcla";

		  // $ls_cadena_fuefin=" AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3";
		}
		elseif($li_estmodest==2)
		{
		   $ls_tabla="spg_ep5";
		   $ls_and= "   AND C.codestpro1=EP.codestpro1".
					"   AND C.codestpro2=EP.codestpro2".
					"   AND C.codestpro3=EP.codestpro3".
					"   AND C.codestpro4=EP.codestpro4".
					"   AND C.codestpro5=EP.codestpro5".
					"   AND C.estcla=EP.estcla";
		  // $ls_cadena_fuefin=" AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND ".
		//					 "	   C.codestpro4=EP.codestpro4 AND C.codestpro5=EP.codestpro5";
		}
				
				$ls_sql=" SELECT C.spg_cuenta, max(C.nivel) as nivel, max(C.denominacion) as denominacion, ".
				"        sum(C.asignado) as asignado, sum(C.enero) as enero, sum(C.febrero) as febrero, ".
				"        sum(C.marzo) as marzo, sum(C.abril) as abril, sum(C.mayo) as mayo,  ".
				"        sum(C.junio) as junio, sum(C.julio) as julio, sum(C.agosto) as agosto,  ".
				"        sum(C.septiembre) as septiembre, sum(C.octubre) as octubre,  ".
				"        sum(C.noviembre) as noviembre, sum(C.diciembre) as diciembre, MAX(C.status) as status ".
			    " FROM   spg_cuentas C, ".$ls_tabla." EP ".
			    " WHERE  C.codemp='".$ls_codemp."' ".
				/*"        ".$ls_cadena."  BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' AND ".
				"        EP.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' ".$ls_cadena_fuefin." ".*/
			    $ls_criterio.$ls_and.
				" GROUP BY C.spg_cuenta ".
       			" ORDER BY C.spg_cuenta  ";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
										"MÉTODO->uf_spg_reportes_ejecucion_trimestral ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			
			$lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
			   $lb_ok=false;
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $li_nivel=$row["nivel"];
			   $ld_asignado=$row["asignado"];
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
			   $ld_comtriact     = 0; 
			   $ld_cautriact     = 0;
			   $ld_pagtriact     = 0;
			   $ld_comacutri     = 0;
			   $ld_cauacutri     = 0;
			   $ld_pagacutri     = 0;
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
					  $ld_programado_acum =  $ld_enero + $ld_febrero + $ld_marzo +  $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_programado;
					  break;
			   }	  		  
               $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_trimestre($ls_spg_cuenta,$ls_estructura5_desde,
																		     $ls_estructura5_hasta,$li_mesdes,$li_meshas,
																		     $ld_aumdisacum,$ld_comtriact,$ld_cautriact,   
																			 $ld_pagtriact,$ld_comacutri,$ld_cauacutri,
																			 $ld_pagacutri,$as_codestpro1_ori,$as_codestpro2_ori,
																			 $as_codestpro3_ori,$as_codestpro4_ori,
																			 $as_codestpro5_ori,$as_codestpro1_des,
																			 $as_codestpro2_des,$as_codestpro3_des,
																			 $as_codestpro4_des,$as_codestpro5_des);
			   if($lb_valido)
			   {
					 $ld_dispon_fecha=$ld_asignado+$ld_aumdisacum-$ld_comacutri;
					 $ld_modificado = $ld_asignado + $ld_aumdisacum;
					 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
					 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					 $this->dts_reporte->insertRow("asignado",$ld_asignado);
					 $this->dts_reporte->insertRow("modificado",$ld_modificado);
					 $this->dts_reporte->insertRow("programado",$ld_programado);
					 $this->dts_reporte->insertRow("compromiso",$ld_comtriact);
					 $this->dts_reporte->insertRow("causado",$ld_cautriact);
					 $this->dts_reporte->insertRow("pagado",$ld_pagtriact);
					 $this->dts_reporte->insertRow("programado_acum",$ld_programado_acum);
					 $this->dts_reporte->insertRow("compromiso_acum",$ld_comacutri);					 
					 $this->dts_reporte->insertRow("causado_acum",$ld_cauacutri);					 
					 $this->dts_reporte->insertRow("pagado_acum",$ld_pagacutri);
					 $this->dts_reporte->insertRow("disponible_fecha",$ld_dispon_fecha);
					 $this->dts_reporte->insertRow("status",$ls_status);
					 $lb_valido=true;
			    }//if
				
			 }//while
			 $this->io_sql->free_result($rs_data);
		 } //else 
     return $lb_valido;
    }//fin uf_spg_reportes_ejecucion_trimestral
/********************************************************************************************************************************/	
    
function uf_spg_reporte_calcular_ejecutado_trimestre($as_spg_cuenta,$as_estructura_desde,$as_estructura_hasta,$ai_mesdes,
	                                                 $ai_meshas,&$ad_aumdisacum,&$ad_comtriact,&$ad_cautriact,&$ad_pagtriact,
													 &$ad_comacutri,&$ad_cauacutri,&$ad_pagacutri,$as_codestpro1_ori,
													 $as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
													 $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
											         $as_codestpro5_des)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado_trimestre
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
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
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $this->dts_empresa["codemp"];
      $li_mesdes=$ai_mesdes;
	  $li_meshas=$ai_meshas;
	  $ls_estructura5_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
	  $ls_estructura5_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;	 
	  $ls_estructura4_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori;
	  $ls_estructura4_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des;	 
	  $ls_estructura3_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori;
	  $ls_estructura3_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des;
	  $ls_estructura2_desde=$as_codestpro1_ori.$as_codestpro2_ori;
	  $ls_estructura2_hasta=$as_codestpro1_des.$as_codestpro2_des;
	  $ls_criterio="";	 
		if($as_codestpro1_ori!="0000000000000000000000000")
		{
			if($as_codestpro2_ori!="0000000000000000000000000")
			{
				if($as_codestpro3_ori!="0000000000000000000000000")
				{
					if($as_codestpro4_ori!="0000000000000000000000000")
					{
						if($as_codestpro5_ori!="0000000000000000000000000")
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)>=".$ls_estructura5_desde."";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 >= ".$ls_estructura5_desde."";
							}
						}
						else
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4)>=".$ls_estructura4_desde."";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2||codestpro3||codestpro4 >= ".$ls_estructura4_desde."";
							}
						}
					}
					else
					{
						if (strtoupper($ls_gestor)=="MYSQLT")
						{
						   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2,codestpro3>='".$ls_estructura3_desde."'";
						}
						else
						{
						   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2||codestpro3 >= '".$ls_estructura3_desde."'";
						}
					}
				}
				else
				{
					if (strtoupper($ls_gestor)=="MYSQLT")
					{
					   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2)>='".$ls_estructura2_desde."'";
					}
					else
					{
					   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2 >= '".$ls_estructura2_desde."'";
					}
				}
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND codestpro1>='".str_pad($as_codestpro1_ori,25,'0',0)."'";
			}
			
		}

		if($as_codestpro1_des!="0000000000000000000000000")
		{
			if($as_codestpro2_des!="0000000000000000000000000")
			{
				if($as_codestpro3_des!="0000000000000000000000000")
				{
					if($as_codestpro4_des!="0000000000000000000000000")
					{
						if($as_codestpro5_des!="0000000000000000000000000")
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)<=".$ls_estructura5_hasta."";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2||codestpro3||codestpro4||codestpro5 <= ".$ls_estructura5_hasta."";
							}
						}
						else
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2,codestpro3,codestpro4)<=".$ls_estructura4_hasta."";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2||codestpro3||codestpro4 <= ".$ls_estructura4_hasta."";
							}
						}
					}
					else
					{
						if (strtoupper($ls_gestor)=="MYSQLT")
						{
						   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2,codestpro3<='".$ls_estructura3_hasta."'";
						}
						else
						{
						   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2||codestpro3 <= '".$ls_estructura3_hasta."'";
						}
					}
				}
				else
				{
					if (strtoupper($ls_gestor)=="MYSQLT")
					{
					   $ls_criterio= $ls_criterio."AND CONCAT(codestpro1,codestpro2)<='".$ls_estructura2_hasta."'";
					}
					else
					{
					   $ls_criterio= $ls_criterio." AND codestpro1||codestpro2 <= '".$ls_estructura2_hasta."'";
					}
				}
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND codestpro1<='".str_pad($as_codestpro1_des,25,'0',0)."'";
			}
			
		}
	  
	  if (strtoupper($ls_gestor)=="MYSQLT")
	  {
		   $ls_cadena="CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla)";
	  }
	  else
	  {
		   $ls_cadena="codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla";
	  }
	  $as_spg_cuenta=$this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
              " FROM   spg_dt_cmp DT, spg_operaciones OP ".
              " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) ".
             $ls_criterio.
              "   AND  spg_cuenta like '".$as_spg_cuenta."' AND DT.fecha <= '".$ai_meshas."'".
			  " ORDER BY DT.fecha";
	  $rs_ejec=$this->io_sql->select($ls_sql);
	  if($rs_ejec===false)
	  { print $this->io_sql->message;
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_ejecutado_trimestre".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_ejec))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_precomprometer=$row["precomprometer"];
		  $li_comprometer=$row["comprometer"];
		  $li_causar=$row["causar"];
		  $li_pagar=$row["pagar"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha=substr($row["fecha"],0,10);
	      
     	  //  Comprometer, Causar, Pagar, Aumento, Disminución
		  if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_comtriact=$ad_comtriact+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_cautriact=$ad_cautriact+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagtriact=$ad_pagtriact+$ld_monto;
		  }//if
		  if(($li_comprometer)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_comacutri=$ad_comacutri+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_cauacutri=$ad_cauacutri+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagacutri=$ad_pagacutri+$ld_monto;
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
   }//fin uf_spg_reporte_calcular_ejecutado_trimestre
/****************************************************************************************************************************************/	


    function uf_spg_reporte_select_denestpro1($as_codestpro1,&$as_denestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp='".$ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro1 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro1=$row["denestpro1"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_denestpro2($as_codestpro1,$as_codestpro2,&$as_denestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2 // codigo
	 //                     $as_denestpro2  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp='".$ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro2 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro2=$row["denestpro2"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_denestpro3($as_codestpro1,$as_codestpro2,$as_codestpro3,&$as_denestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp='".$ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro3 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro3=$row["denestpro3"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
 
    function uf_spg_reporte_select_denestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_denestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql="SELECT denestpro4 ".
             "  FROM   spg_ep4 ".
             " WHERE  codemp='".$ls_codemp."'".
			 "   AND  codestpro1='".$as_codestpro1."'".
			 "   AND codestpro2='".$as_codestpro2."'".
			 "   AND codestpro3='".$as_codestpro3."' ".
			 "   AND codestpro4='".$as_codestpro4."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro4 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro4=$row["denestpro4"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
 
    function uf_spg_reporte_select_denestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,&$as_denestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    27/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql="SELECT denestpro5 ".
             "  FROM   spg_ep5 ".
             " WHERE  codemp='".$ls_codemp."'".
			 "   AND  codestpro1='".$as_codestpro1."'".
			 "   AND codestpro2='".$as_codestpro2."'".
			 "   AND codestpro3='".$as_codestpro3."' ".
			 "   AND codestpro4='".$as_codestpro4."' ".
			 "   AND codestpro5='".$as_codestpro5."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro4 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_denestpro5=$row["denestpro5"];
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_min_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_programatica
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 		     "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3 ".
             " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".
             " WHERE  a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND ".
             "        a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2  AND c.codestpro3 like '%' AND c.denestpro3 like '%' ".
             " ORDER BY  codestpro1  limit 1 ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_codestpro2=$row["codestpro2"];
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_cuenta
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_min_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro1) as codestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro1
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2  // codigo de estructura programatica 2 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro2 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro2
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$ls_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro3 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro3
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_max_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_programatica
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 		     "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3 ".
             " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".
             " WHERE  a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND ".
             "        a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2  AND c.codestpro3 like '%' AND c.denestpro3 like '%' ".
             " ORDER BY  codestpro1 desc limit 1 ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_codestpro2=$row["codestpro2"];
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_programatica
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro1) as codestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro1 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro1
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2  // codigo de estructura programatica 2 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro2 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro2
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$ls_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro3 ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro3

//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	/////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVOS " ESTADO DE RESULTADOS "     //
	////////////////////////////////////////////////////////////////////
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_reportes_estado_de_resultado($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reportes_estado_de_resultado
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Estado de Resultado Ingesos Generales
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=$this->uf_spg_reportes_ingresos_generales($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spg_reportes_ingresos_actividadespropias($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spg_reportes_otrosingresos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spg_reportes_gastos_de_consumo($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
		$lb_valido=$this->uf_spg_reportes_gastos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas);
	 }
	 if($lb_valido)
	 {
	    $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	    $ls_formpre=str_replace('-','',$ls_formpre);
	    $li_len=strlen($ls_formpre);
	    $li_len=$li_len-9;
	    $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
		
		$la_cuenta[55]=array();
		// ----> 1.  INGRESOS CORRIENTES
		$la_cuenta[1]='305000000'.$ls_ceros;
		$la_cuenta[2]='305010000'.$ls_ceros;
		$la_cuenta[3]='305010100'.$ls_ceros;
		$la_cuenta[4]='305010200'.$ls_ceros;
		$la_cuenta[5]='305010300'.$ls_ceros;
		$la_cuenta[6]='305010301'.$ls_ceros;
		$la_cuenta[7]='305010302'.$ls_ceros;
		$la_cuenta[8]='305010303'.$ls_ceros;
		$la_cuenta[9]='305010304'.$ls_ceros;
		$la_cuenta[10]='305010305'.$ls_ceros;
		$la_cuenta[11]='305010306'.$ls_ceros;
		$la_cuenta[12]='305010307'.$ls_ceros;
		$la_cuenta[13]='305010308'.$ls_ceros;
		$la_cuenta[14]='305010309'.$ls_ceros;
		$la_cuenta[15]='305010400'.$ls_ceros;
		$la_cuenta[16]='305010401'.$ls_ceros;
		$la_cuenta[17]='305010402'.$ls_ceros;
		$la_cuenta[18]='305010403'.$ls_ceros;
		$la_cuenta[19]='305010404'.$ls_ceros;
		$la_cuenta[20]='305010405'.$ls_ceros;
		$la_cuenta[21]='305010406'.$ls_ceros;
		$la_cuenta[22]='305010407'.$ls_ceros;
		$la_cuenta[23]='305010408'.$ls_ceros;
		$la_cuenta[24]='305010409'.$ls_ceros;
		$la_cuenta[25]='305010500'.$ls_ceros;
		$la_cuenta[26]='305010600'.$ls_ceros;
		// ---> b. Ingresos por Actividades Propias
		$la_cuenta[27]='301090000'.$ls_ceros;
		$la_cuenta[28]='301090100'.$ls_ceros;
		$la_cuenta[29]='301090200'.$ls_ceros;
		$la_cuenta[30]='301099900'.$ls_ceros;
		$la_cuenta[31]='408070000'.$ls_ceros; // ---> Menos Descuentos, Bonificaciones y Devoluciones
        // ---> Ventas Netas
		$la_cuenta[32]='301030000'.$ls_ceros;
		$la_cuenta[33]='303990000'.$ls_ceros;
        // ---> c. Otros Ingresos Corrientes
		$la_cuenta[34]='301040000'.$ls_ceros;
		$la_cuenta[35]='301050000'.$ls_ceros;
		$la_cuenta[36]='301100000'.$ls_ceros;
		$la_cuenta[37]='302030000'.$ls_ceros;
		$la_cuenta[38]='302040000'.$ls_ceros;
		$la_cuenta[39]='302050000'.$ls_ceros;
		// ---> 2. GASTOS CORRIENTES
		// ---> a. Gastos de Consumo
		$la_cuenta[40]='401000000'.$ls_ceros;
		$la_cuenta[41]='402000000'.$ls_ceros;
		$la_cuenta[42]='403000000'.$ls_ceros;
		// ---> Variación de Inventarios (Detallar)
		$la_cuenta[43]='408000000'.$ls_ceros;
		$la_cuenta[44]='408010000'.$ls_ceros;
		$la_cuenta[45]='408010100'.$ls_ceros;
		$la_cuenta[46]='408010200'.$ls_ceros;
		// ---> b. Otros Gastos Corrientes
		$la_cuenta[47]='407000000'.$ls_ceros;
		$la_cuenta[48]='407010000'.$ls_ceros;
		$la_cuenta[49]='407010100'.$ls_ceros;
		$la_cuenta[50]='407010300'.$ls_ceros;
		$la_cuenta[51]='407020000'.$ls_ceros;
		$la_cuenta[52]='408020000'.$ls_ceros;
		$la_cuenta[53]='408050000'.$ls_ceros;
		$la_cuenta[54]='408060000'.$ls_ceros;
		$la_cuenta[55]='408080000'.$ls_ceros;
	    
		for($i=1;$i<=55;$i++)
		{
		   switch ($i)
		   {		
			 case 1:
			   $this->dts_reporte->insertRow("cuenta","");
			   $this->dts_reporte->insertRow("denominacion",'<b>1. INGRESOS CORRIENTES</b>');
			   $this->dts_reporte->insertRow("asignado",0);
			   $this->dts_reporte->insertRow("asignado_modificado",0);
			   $this->dts_reporte->insertRow("programado",0);
		  	   $this->dts_reporte->insertRow("ejecutado",0);		
		  	   $this->dts_reporte->insertRow("variacion_absoluta",0);		
		  	   $this->dts_reporte->insertRow("variacion_porcentual",0);		
			   $this->dts_reporte->insertRow("programado_acumulado",0);
		  	   $this->dts_reporte->insertRow("ejecutado_acumulado",0);
		  	   $this->dts_reporte->insertRow("tipo","1");
			 break;
			 
			 case 27:  //b. Ingresos por Actividades Propias
			    $ld_total_asignado=0;
				$ld_total_asignado_modificado=0;
				$ld_total_programado=0;
				$ld_total_ejecutado=0;
				$ld_total_variacion_absoluta=0;
				$ld_total_variacion_porcentual=0;
				$ld_total_programado_acumulado=0;
				$ld_total_ejecutado_acumulado=0;
  				$li_total=$this->dts_ingresos_actividadespropias->getRowCount("cuenta");
				if($li_total>0)
				{
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_ingresos_actividadespropias->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_ingresos_actividadespropias->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_ingresos_actividadespropias->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_ingresos_actividadespropias->getValue("asignado_modificado",$li);
					 $ld_programado=$this->dts_ingresos_actividadespropias->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_ingresos_actividadespropias->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_ingresos_actividadespropias->getValue("variacion_absoluta",$li);
					 $ld_variacion_porcentual=$this->dts_ingresos_actividadespropias->getValue("variacion_porcentual",$li);
					 $ld_programado_acumulado=$this->dts_ingresos_actividadespropias->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_ingresos_actividadespropias->getValue("ejecutado_acumulado",$li);
					 $ls_tipo=$this->dts_ingresos_actividadespropias->getValue("tipo",$li);
					 $ls_estatus = $this->dts_ingresos_actividadespropias->getValue("estatus",$li);
					 if(($ls_cuenta == '301090000'.$ls_ceros)||($ls_cuenta == '301030000'.$ls_ceros)||($ls_cuenta == '303990000'.$ls_ceros))
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
				  $this->dts_reporte->insertRow("cuenta","");
				  $this->dts_reporte->insertRow("denominacion","<b>b. Ingresos por Actividades Propias</b>");
				  $this->dts_reporte->insertRow("asignado",$ld_total_asignado);
				  $this->dts_reporte->insertRow("asignado_modificado",$ld_total_asignado_modificado);
				  $this->dts_reporte->insertRow("programado",$ld_total_programado);
				  $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
				  $this->dts_reporte->insertRow("variacion_absoluta",$ld_total_variacion_absoluta);		
				  $this->dts_reporte->insertRow("variacion_porcentual",$ld_total_variacion_porcentual);		
				  $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
				  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);
				  $this->dts_reporte->insertRow("tipo","B");
				}  
			 break;
			 
			 case 34:  // ---> c. Otros Ingresos Corrientes
				  $ld_total_asignado=0;
				  $ld_total_asignado_modificado=0;
				  $ld_total_programado=0;
				  $ld_total_ejecutado=0;
				  $ld_total_variacion_absoluta=0;
				  $ld_total_variacion_porcentual=0;
				  $ld_total_programado_acumulado=0;
				  $ld_total_ejecutado_acumulado=0;
  				  $li_total=$this->dts_ingresos_ingresoscorrientes->getRowCount("cuenta");
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_ingresos_ingresoscorrientes->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_ingresos_ingresoscorrientes->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_ingresos_ingresoscorrientes->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_ingresos_ingresoscorrientes->getValue("asignado_modificado",$li);
					 $ld_programado=$this->dts_ingresos_ingresoscorrientes->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_ingresos_ingresoscorrientes->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_ingresos_ingresoscorrientes->getValue("variacion_absoluta",$li);
					 $ld_variacion_porcentual=$this->dts_ingresos_ingresoscorrientes->getValue("variacion_porcentual",$li);
					 $ld_programado_acumulado=$this->dts_ingresos_ingresoscorrientes->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_ingresos_ingresoscorrientes->getValue("ejecutado_acumulado",$li);
					 $ls_tipo=$this->dts_ingresos_ingresoscorrientes->getValue("tipo",$li);
					 
					 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
					 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
					 $ld_total_programado=$ld_total_programado + $ld_programado;
					 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
					 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
					 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
					 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
					 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
					 
				  }//for
				  $this->dts_reporte->insertRow("cuenta","");
				  $this->dts_reporte->insertRow("denominacion","<b>c. Otros Ingresos Corrientes</b>");
				  $this->dts_reporte->insertRow("asignado",$ld_total_asignado);
				  $this->dts_reporte->insertRow("asignado_modificado",$ld_total_asignado_modificado);
				  $this->dts_reporte->insertRow("programado",$ld_total_programado);
				  $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
				  $this->dts_reporte->insertRow("variacion_absoluta",$ld_total_variacion_absoluta);		
				  $this->dts_reporte->insertRow("variacion_porcentual",$ld_total_variacion_porcentual);		
				  $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
				  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);
				  $this->dts_reporte->insertRow("tipo","C");
			 break;
			 
			 case 40:// ---> 2. GASTOS CORRIENTES
				  $this->dts_reporte->insertRow("cuenta","");
				  $this->dts_reporte->insertRow("denominacion","<b>2. GASTOS CORRIENTES</b>");
				  $this->dts_reporte->insertRow("asignado",0);
				  $this->dts_reporte->insertRow("asignado_modificado",0);
				  $this->dts_reporte->insertRow("programado",0);
				  $this->dts_reporte->insertRow("ejecutado",0);		
				  $this->dts_reporte->insertRow("variacion_absoluta",0);		
				  $this->dts_reporte->insertRow("variacion_porcentual",0);		
				  $this->dts_reporte->insertRow("programado_acumulado",0);
				  $this->dts_reporte->insertRow("ejecutado_acumulado",0);
				  $this->dts_reporte->insertRow("tipo","2");
		          // ---> a. Gastos de Consumo
				  $ld_total_asignado=0;
				  $ld_total_asignado_modificado=0;
				  $ld_total_programado=0;
				  $ld_total_ejecutado=0;
				  $ld_total_variacion_absoluta=0;
				  $ld_total_variacion_porcentual=0;
				  $ld_total_programado_acumulado=0;
				  $ld_total_ejecutado_acumulado=0;
  				  $li_total=$this->dts_gastos_consumo->getRowCount("cuenta");
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_gastos_consumo->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_gastos_consumo->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_gastos_consumo->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_gastos_consumo->getValue("asignado_modificado",$li);
					 $ld_programado=$this->dts_gastos_consumo->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_gastos_consumo->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_gastos_consumo->getValue("variacion_absoluta",$li);
					 $ld_variacion_porcentual=$this->dts_gastos_consumo->getValue("variacion_porcentual",$li);
					 $ld_programado_acumulado=$this->dts_gastos_consumo->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_gastos_consumo->getValue("ejecutado_acumulado",$li);
					 $ls_tipo=$this->dts_gastos_consumo->getValue("tipo",$li);
					 
					 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
					 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
					 $ld_total_programado=$ld_total_programado + $ld_programado;
					 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
					 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
					 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
					 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
					 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
					 
				  }//for
				  $this->dts_reporte->insertRow("cuenta","");
				  $this->dts_reporte->insertRow("denominacion","a. Gastos de Consumo");
				  $this->dts_reporte->insertRow("asignado",$ld_total_asignado);
				  $this->dts_reporte->insertRow("asignado_modificado",$ld_total_asignado_modificado);
				  $this->dts_reporte->insertRow("programado",$ld_total_programado);
				  $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
				  $this->dts_reporte->insertRow("variacion_absoluta",$ld_total_variacion_absoluta);		
				  $this->dts_reporte->insertRow("variacion_porcentual",$ld_total_variacion_porcentual);		
				  $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
				  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);
				  $this->dts_reporte->insertRow("tipo","D");
			 break;
			 
			 case 43: // ---> Variación de Inventarios (Detallar) 
				  $ld_total_asignado=0;
				  $ld_total_asignado_modificado=0;
				  $ld_total_programado=0;
				  $ld_total_ejecutado=0;
				  $ld_total_variacion_absoluta=0;
				  $ld_total_variacion_porcentual=0;
				  $ld_total_programado_acumulado=0;
				  $ld_total_ejecutado_acumulado=0;
  				  $li_total=$this->dts_gastos_consumo->getRowCount("cuenta");
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_gastos_consumo->getValue("cuenta",$li);
					 if($ls_cuenta=='403000000'.$ls_ceros)
					 {
						 $ls_denominacion=$this->dts_gastos_consumo->getValue("denominacion",$li);
						 $ld_asignado=$this->dts_gastos_consumo->getValue("asignado",$li);
						 $ld_asignado_modificado=$this->dts_gastos_consumo->getValue("asignado_modificado",$li);
						 $ld_programado=$this->dts_gastos_consumo->getValue("programado",$li);
						 $ld_ejecutado=$this->dts_gastos_consumo->getValue("ejecutado",$li);
						 $ld_variacion_absoluta=$this->dts_gastos_consumo->getValue("variacion_absoluta",$li);
						 $ld_variacion_porcentual=$this->dts_gastos_consumo->getValue("variacion_porcentual",$li);
						 $ld_programado_acumulado=$this->dts_gastos_consumo->getValue("programado_acumulado",$li);
						 $ld_ejecutado_acumulado=$this->dts_gastos_consumo->getValue("ejecutado_acumulado",$li);
						 $ls_tipo=$this->dts_gastos_consumo->getValue("tipo",$li);
						 
						 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
						 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
						 $ld_total_programado=$ld_total_programado + $ld_programado;
						 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
						 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
						 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
						 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
						 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
					 }	//if 
				  }//for
				  $this->dts_reporte->insertRow("cuenta","");
				  $this->dts_reporte->insertRow("denominacion","<b> Variación de Inventarios (Detallar)</b>");
				  $this->dts_reporte->insertRow("asignado",$ld_total_asignado);
				  $this->dts_reporte->insertRow("asignado_modificado",$ld_total_asignado_modificado);
				  $this->dts_reporte->insertRow("programado",$ld_total_programado);
				  $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
				  $this->dts_reporte->insertRow("variacion_absoluta",$ld_total_variacion_absoluta);		
				  $this->dts_reporte->insertRow("variacion_porcentual",$ld_total_variacion_porcentual);		
				  $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
				  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);
				  $this->dts_reporte->insertRow("tipo","D");
			 break;
			 
			 case 47://  b. Otros Gastos Corrientes
				  $ld_total_asignado=0;
				  $ld_total_asignado_modificado=0;
				  $ld_total_programado=0;
				  $ld_total_ejecutado=0;
				  $ld_total_variacion_absoluta=0;
				  $ld_total_variacion_porcentual=0;
				  $ld_total_programado_acumulado=0;
				  $ld_total_ejecutado_acumulado=0;
  				  $li_total=$this->dts_gastos_corrientes->getRowCount("cuenta");
				  for($li=1;$li<=$li_total;$li++)
				  {
					 $ls_cuenta=$this->dts_gastos_corrientes->getValue("cuenta",$li);
					 $ls_denominacion=$this->dts_gastos_corrientes->getValue("denominacion",$li);
					 $ld_asignado=$this->dts_gastos_corrientes->getValue("asignado",$li);
					 $ld_asignado_modificado=$this->dts_gastos_corrientes->getValue("asignado_modificado",$li);
					 $ld_programado=$this->dts_gastos_corrientes->getValue("programado",$li);
					 $ld_ejecutado=$this->dts_gastos_corrientes->getValue("ejecutado",$li);
					 $ld_variacion_absoluta=$this->dts_gastos_corrientes->getValue("variacion_absoluta",$li);
					 $ld_variacion_porcentual=$this->dts_gastos_corrientes->getValue("variacion_porcentual",$li);
					 $ld_programado_acumulado=$this->dts_gastos_corrientes->getValue("programado_acumulado",$li);
					 $ld_ejecutado_acumulado=$this->dts_gastos_corrientes->getValue("ejecutado_acumulado",$li);
					 $ls_tipo=$this->dts_gastos_corrientes->getValue("tipo",$li);
					 
					 $ld_total_asignado=$ld_total_asignado + $ld_asignado;
					 $ld_total_asignado_modificado=$ld_total_asignado_modificado + $ld_asignado_modificado;
					 $ld_total_programado=$ld_total_programado + $ld_programado;
					 $ld_total_ejecutado=$ld_total_ejecutado + $ld_ejecutado;
					 $ld_total_variacion_absoluta=$ld_total_variacion_absoluta + $ld_variacion_absoluta;
					 $ld_total_variacion_porcentual=$ld_total_variacion_porcentual + $ld_variacion_porcentual;
					 $ld_total_programado_acumulado=$ld_total_programado_acumulado + $ld_programado_acumulado;
					 $ld_total_ejecutado_acumulado=$ld_total_ejecutado_acumulado + $ld_ejecutado_acumulado;
				  }//for
				  $this->dts_reporte->insertRow("cuenta","");
				  $this->dts_reporte->insertRow("denominacion","<b> b. Otros Gastos Corrientes</b>");
				  $this->dts_reporte->insertRow("asignado",$ld_total_asignado);
				  $this->dts_reporte->insertRow("asignado_modificado",$ld_total_asignado_modificado);
				  $this->dts_reporte->insertRow("programado",$ld_total_programado);
				  $this->dts_reporte->insertRow("ejecutado",$ld_total_ejecutado);		
				  $this->dts_reporte->insertRow("variacion_absoluta",$ld_total_variacion_absoluta);		
				  $this->dts_reporte->insertRow("variacion_porcentual",$ld_total_variacion_porcentual);		
				  $this->dts_reporte->insertRow("programado_acumulado",$ld_total_programado_acumulado);
				  $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado);
				  $this->dts_reporte->insertRow("tipo","E");
			 break;
			 }//switch	 
		           $ls_cuenta=$la_cuenta[$i];
				   $li_pos=$this->dts_reporte_temporal->find("cuenta",$ls_cuenta);
				   if($li_pos>0)
				   { 		
					 $ls_spg_cuenta=$this->dts_reporte_temporal->getValue("cuenta",$li_pos);
					 $ls_denominacion=$this->dts_reporte_temporal->getValue("denominacion",$li_pos);
					 $ld_asignado=$this->dts_reporte_temporal->getValue("asignado",$li_pos);
					 $ld_asignado_modificado=$this->dts_reporte_temporal->getValue("asignado_modificado",$li_pos);
					 $ld_programado=$this->dts_reporte_temporal->getValue("programado",$li_pos);
					 $ld_ejecutado=$this->dts_reporte_temporal->getValue("ejecutado",$li_pos);
					 $ld_variacion_absoluta=$this->dts_reporte_temporal->getValue("variacion_absoluta",$li_pos);
					 $ld_variacion_porcentual=$this->dts_reporte_temporal->getValue("variacion_porcentual",$li_pos);
					 $ld_programado_acumulado=$this->dts_reporte_temporal->getValue("programado_acumulado",$li_pos);
					 $ld_ejecutado_acumulado=$this->dts_reporte_temporal->getValue("ejecutado_acumulado",$li_pos);
					 $ls_tipo=$this->dts_reporte_temporal->getValue("tipo",$li_pos);
				  
					 $this->dts_reporte->insertRow("cuenta",$ls_spg_cuenta);
					 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					 $this->dts_reporte->insertRow("asignado",$ld_asignado);
					 $this->dts_reporte->insertRow("asignado_modificado",$ld_asignado_modificado);
					 $this->dts_reporte->insertRow("programado",$ld_programado);
					 $this->dts_reporte->insertRow("ejecutado",$ld_ejecutado);		
					 $this->dts_reporte->insertRow("variacion_absoluta",$ld_variacion_absoluta);		
					 $this->dts_reporte->insertRow("variacion_porcentual",$ld_variacion_porcentual);		
					 $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
					 $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_ejecutado_acumulado);
					 $this->dts_reporte->insertRow("tipo",$ls_tipo);
				  }//if
				  else
				  {
				     $ls_denom="";
					 $ls_cuenta_buscar=substr($ls_cuenta,0,9);
				     $lb_valido=$this->uf_spg_reportes_select_denominacion($ls_cuenta_buscar,$ls_denom);
				     if($lb_valido)
				     {
						 $this->dts_reporte->insertRow("cuenta",$ls_cuenta);
						 $this->dts_reporte->insertRow("denominacion",$ls_denom);
						 $this->dts_reporte->insertRow("asignado",0);
						 $this->dts_reporte->insertRow("asignado_modificado",0);
						 $this->dts_reporte->insertRow("programado",0);
						 $this->dts_reporte->insertRow("ejecutado",0);		
						 $this->dts_reporte->insertRow("variacion_absoluta",0);		
						 $this->dts_reporte->insertRow("variacion_porcentual",0);		
						 $this->dts_reporte->insertRow("programado_acumulado",0);
						 $this->dts_reporte->insertRow("ejecutado_acumulado",0);
						 $this->dts_reporte->insertRow("tipo","-");
				     }//if
				  }//else
				  /*if($i<27)
				  {
					 $ld_total_asignado_trasnferencias=$ld_total_asignado_trasnferencias + $ld_asignado;
					 $ld_total_asignado_modificado_trasnferencias=$ld_total_asignado_modificado_trasnferencias + $ld_asignado_modificado;
					 $ld_total_programado_trasnferencias=$ld_total_programado_trasnferencias + $ld_programado;
					 $ld_total_ejecutado_trasnferencias=$ld_total_ejecutado_trasnferencias + $ld_ejecutado;
					 $ld_total_variacion_absoluta_trasnferencias=$ld_total_variacion_absoluta_trasnferencias + $ld_variacion_absoluta;
					 $ld_total_variacion_porcentual_trasnferencias=$ld_total_variacion_porcentual_trasnferencias + $ld_variacion_porcentual;
					 $ld_total_programado_acumulado_trasnferencias=$ld_total_programado_acumulado_trasnferencias + $ld_programado_acumulado;
					 $ld_total_ejecutado_acumulado_trasnferencias=$ld_total_ejecutado_acumulado_trasnferencias + $ld_ejecutado_acumulado;
				 }*/
		}// for 
		//-------------------------------------------------------------
		//  PREPARAR LA SUMATORIAS AL REPORTE
		// INGRESOS
		/*$li_pos=$dts_reporte->find("tipo","A");
		if($li_pos>0)
		{
		  $dts_reporte->updateRow("asignado",$ld_total_asignado_trasnferencias,$li_pos);			
		  $dts_reporte->updateRow("asignado_modificado",$ld_total_asignado_modificado_trasnferencias,$li_pos);			
		  $dts_reporte->updateRow("programado",$ld_total_programado_trasnferencias,$li_pos);			
		  $dts_reporte->updateRow("ejecutado",$ld_total_ejecutado_trasnferencias,$li_pos);			
		  $dts_reporte->updateRow("variacion_absoluta",$ld_total_variacion_absoluta_trasnferencias,$li_pos);
		  $dts_reporte->updateRow("variacion_porcentual",$ld_total_variacion_porcentual_trasnferencias,$li_pos);		
		  $dts_reporte->updateRow("programado_acumulado",$ld_total_programado_acumulado_trasnferencias,$li_pos);			
		  $dts_reporte->updateRow("ejecutado_acumulado",$ld_total_ejecutado_acumulado_trasnferencias,$li_pos);			
		}//if*/
		
		/*$li_pos_a=$dts_reporte->find("tipo","A");
		if($li_pos>0)
		{
		  $ld_asignado_modificado=$this->dts_reporte->getValue("asignado_modificado",$li_pos_a);
		  $ld_programado=$this->dts_reporte->getValue("programado",$li_pos_a);
		  $ld_ejecutado=$this->dts_reporte->getValue("ejecutado",$li_pos_a);
		  $ld_variacion_absoluta=$this->dts_reporte->getValue("variacion_absoluta",$li_pos_a);
		  $ld_variacion_porcentual=$this->dts_reporte->getValue("variacion_porcentual",$li_pos_a);
		  $ld_programado_acumulado=$this->dts_reporte->getValue("programado_acumulado",$li_pos_a);
		  $ld_ejecutado_acumulado=$this->dts_reporte->getValue("ejecutado_acumulado",$li_pos_a);
		
		  $ld_asignado_ingreso=$ld_asignado_ingreso + $ld_asignado;
		  $ld_asignado_modificado_ingreso=$ld_asignado_modificado_ingreso + $ld_asignado_modificado;
		  $ld_programado_ingreso=$ld_programado_ingreso + $ld_programado;
		  $ld_ejecutado_ingreso=$ld_ejecutado_ingreso + $ld_ejecutado;
		  $ld_variacion_absoluta_ingreso=$ld_variacion_absoluta_ingreso + $ld_variacion_absoluta;
		  $ld_variacion_porcentual_ingreso=$ld_variacion_porcentual_ingreso + $ld_variacion_porcentual;
		  $ld_programado_acumulado_ingreso=$ld_programado_acumulado_ingreso + $ld_programado_acumulado;
		  $ld_ejecutado_acumulado_ingreso=$ld_ejecutado_acumulado_ingreso + $ld_ejecutado_acumulado;
		}//if*/
		/// INGRESOS POR ACTIVIDADES PROPIAS
		  $ld_asignado_ingreso=0;
		  $ld_asignado_modificado_ingreso=0;
		  $ld_programado_ingreso=0;
		  $ld_ejecutado_ingreso=0;
		  $ld_variacion_absoluta_ingreso=0;
		  $ld_variacion_porcentual_ingreso=0;
		  $ld_programado_acumulado_ingreso=0;
		  $ld_ejecutado_acumulado_ingreso=0;
		  $li_pos_b=$this->dts_reporte->find("tipo","B");
		if($li_pos_b>0)
		{
		  $ld_asignado=$this->dts_reporte->getValue("asignado",$li_pos_b);
		  $ld_asignado_modificado=$this->dts_reporte->getValue("asignado_modificado",$li_pos_b);
		  $ld_programado=$this->dts_reporte->getValue("programado",$li_pos_b);
		  $ld_ejecutado=$this->dts_reporte->getValue("ejecutado",$li_pos_b);
		  $ld_variacion_absoluta=$this->dts_reporte->getValue("variacion_absoluta",$li_pos_b);
		  $ld_variacion_porcentual=$this->dts_reporte->getValue("variacion_porcentual",$li_pos_b);
		  $ld_programado_acumulado=$this->dts_reporte->getValue("programado_acumulado",$li_pos_b);
		  $ld_ejecutado_acumulado=$this->dts_reporte->getValue("ejecutado_acumulado",$li_pos_b);
		
		  $ld_asignado_ingreso=$ld_asignado_ingreso + $ld_asignado;
		  $ld_asignado_modificado_ingreso=$ld_asignado_modificado_ingreso + $ld_asignado_modificado;
		  $ld_programado_ingreso=$ld_programado_ingreso + $ld_programado;
		  $ld_ejecutado_ingreso=$ld_ejecutado_ingreso + $ld_ejecutado;
		  $ld_variacion_absoluta_ingreso=$ld_variacion_absoluta_ingreso + $ld_variacion_absoluta;
		  $ld_variacion_porcentual_ingreso=$ld_variacion_porcentual_ingreso + $ld_variacion_porcentual;
		  $ld_programado_acumulado_ingreso=$ld_programado_acumulado_ingreso + $ld_programado_acumulado;
		  $ld_ejecutado_acumulado_ingreso=$ld_ejecutado_acumulado_ingreso + $ld_ejecutado_acumulado;
		}//if
		///OTROS INGRESOS CORRIENTES
		/*$ld_asignado_ingreso=0;
		$ld_asignado_modificado_ingreso=0;
		$ld_programado_ingreso=0;
		$ld_ejecutado_ingreso=0;
		$ld_variacion_absoluta_ingreso=0;
		$ld_variacion_porcentual_ingreso=0;
		$ld_programado_acumulado_ingreso=0;
		$ld_ejecutado_acumulado_ingreso=0;*/
		$li_pos_c=$this->dts_reporte->find("tipo","C");
		if($li_pos>0)
		{
		  $ld_asignado=$this->dts_reporte->getValue("asignado",$li_pos_c);
		  $ld_asignado_modificado=$this->dts_reporte->getValue("asignado_modificado",$li_pos_c);
		  $ld_programado=$this->dts_reporte->getValue("programado",$li_pos_c);
		  $ld_ejecutado=$this->dts_reporte->getValue("ejecutado",$li_pos_c);
		  $ld_variacion_absoluta=$this->dts_reporte->getValue("variacion_absoluta",$li_pos_c);
		  $ld_variacion_porcentual=$this->dts_reporte->getValue("variacion_porcentual",$li_pos_c);
		  $ld_programado_acumulado=$this->dts_reporte->getValue("programado_acumulado",$li_pos_c);
		  $ld_ejecutado_acumulado=$this->dts_reporte->getValue("ejecutado_acumulado",$li_pos_c);
		
		  $ld_asignado_ingreso=$ld_asignado_ingreso + $ld_asignado;
		  $ld_asignado_modificado_ingreso=$ld_asignado_modificado_ingreso + $ld_asignado_modificado;
		  $ld_programado_ingreso=$ld_programado_ingreso + $ld_programado;
		  $ld_ejecutado_ingreso=$ld_ejecutado_ingreso + $ld_ejecutado;
		  $ld_variacion_absoluta_ingreso=$ld_variacion_absoluta_ingreso + $ld_variacion_absoluta;
		  $ld_variacion_porcentual_ingreso=$ld_variacion_porcentual_ingreso + $ld_variacion_porcentual;
		  $ld_programado_acumulado_ingreso=$ld_programado_acumulado_ingreso + $ld_programado_acumulado;
		  $ld_ejecutado_acumulado_ingreso=$ld_ejecutado_acumulado_ingreso + $ld_ejecutado_acumulado;
		}//if
		/// SUMATORIA DE INGRESOS
		$li_pos_a=$this->dts_reporte->find("cuenta","305000000".$ls_ceros);
		if($li_pos_a>0)
		{
		  $ld_asignado=$this->dts_reporte->getValue("asignado",$li_pos_a);
		  $ld_asignado_modificado=$this->dts_reporte->getValue("asignado_modificado",$li_pos_a);
		  $ld_programado=$this->dts_reporte->getValue("programado",$li_pos_a);
		  $ld_ejecutado=$this->dts_reporte->getValue("ejecutado",$li_pos_a);
		  $ld_variacion_absoluta=$this->dts_reporte->getValue("variacion_absoluta",$li_pos_a);
		  $ld_variacion_porcentual=$this->dts_reporte->getValue("variacion_porcentual",$li_pos_a);
		  $ld_programado_acumulado=$this->dts_reporte->getValue("programado_acumulado",$li_pos_a);
		  $ld_ejecutado_acumulado=$this->dts_reporte->getValue("ejecutado_acumulado",$li_pos_a);
		  
		  $ld_asignado_ingreso=$ld_asignado_ingreso + $ld_asignado;
		  $ld_asignado_modificado_ingreso=$ld_asignado_modificado_ingreso + $ld_asignado_modificado;
		  $ld_programado_ingreso=$ld_programado_ingreso + $ld_programado;
		  $ld_ejecutado_ingreso=$ld_ejecutado_ingreso + $ld_ejecutado;
		  $ld_variacion_absoluta_ingreso=$ld_variacion_absoluta_ingreso + $ld_variacion_absoluta;
		  $ld_variacion_porcentual_ingreso=$ld_variacion_porcentual_ingreso + $ld_variacion_porcentual;
		  $ld_programado_acumulado_ingreso=$ld_programado_acumulado_ingreso + $ld_programado_acumulado;
		  $ld_ejecutado_acumulado_ingreso=$ld_ejecutado_acumulado_ingreso + $ld_ejecutado_acumulado;
		}//if
		$li_pos_1=$this->dts_reporte->find("tipo","1");
		if($li_pos_1>0)
		{
		  $this->dts_reporte->updateRow("asignado",$ld_asignado_ingreso,$li_pos_1);			
		  $this->dts_reporte->updateRow("asignado_modificado",$ld_asignado_modificado_ingreso,$li_pos_1);			
		  $this->dts_reporte->updateRow("programado",$ld_programado_ingreso,$li_pos_1);			
		  $this->dts_reporte->updateRow("ejecutado",$ld_ejecutado_ingreso,$li_pos_1);			
		  $this->dts_reporte->updateRow("variacion_absoluta",$ld_variacion_absoluta_ingreso,$li_pos_1);
		  $this->dts_reporte->updateRow("variacion_porcentual",$ld_variacion_porcentual_ingreso,$li_pos_1);		
		  $this->dts_reporte->updateRow("programado_acumulado",$ld_programado_acumulado_ingreso,$li_pos_1);			
		  $this->dts_reporte->updateRow("ejecutado_acumulado",$ld_ejecutado_acumulado_ingreso,$li_pos_1);			
		}//if
		///  SUMATORIA DE LOS GASTOS
		/// GASTOS  DE CONSUMO
		$ld_asignado_gasto=0;
		$ld_asignado_modificado_gasto=0;
		$ld_programado_gasto=0;
		$ld_ejecutado_gasto=0;
		$ld_variacion_absoluta_gasto=0;
		$ld_variacion_porcentual_gasto=0;
		$ld_programado_acumulado_gasto=0;
		$ld_ejecutado_acumulado_gasto=0;
		$li_pos_c=$this->dts_reporte->find("tipo","E");
		if($li_pos_c>0)
		{
		  $ld_asignado=$this->dts_reporte->getValue("asignado",$li_pos_c);
		  $ld_asignado_modificado=$this->dts_reporte->getValue("asignado_modificado",$li_pos_c);
		  $ld_programado=$this->dts_reporte->getValue("programado",$li_pos_c);
		  $ld_ejecutado=$this->dts_reporte->getValue("ejecutado",$li_pos_c);
		  $ld_variacion_absoluta=$this->dts_reporte->getValue("variacion_absoluta",$li_pos_c);
		  $ld_variacion_porcentual=$this->dts_reporte->getValue("variacion_porcentual",$li_pos_c);
		  $ld_programado_acumulado=$this->dts_reporte->getValue("programado_acumulado",$li_pos_c);
		  $ld_ejecutado_acumulado=$this->dts_reporte->getValue("ejecutado_acumulado",$li_pos_c);
		
		  $ld_asignado_gasto=$ld_asignado_gasto + $ld_asignado;
		  $ld_asignado_modificado_gasto=$ld_asignado_modificado_gasto + $ld_asignado_modificado;
		  $ld_programado_gasto=$ld_programado_gasto + $ld_programado;
		  $ld_ejecutado_gasto=$ld_ejecutado_gasto + $ld_ejecutado;
		  $ld_variacion_absoluta_gasto=$ld_variacion_absoluta_gasto + $ld_variacion_absoluta;
		  $ld_variacion_porcentual_gasto=$ld_variacion_porcentual_gasto + $ld_variacion_porcentual;
		  $ld_programado_acumulado_gasto=$ld_programado_acumulado_gasto + $ld_programado_acumulado;
		  $ld_ejecutado_acumulado_gasto=$ld_ejecutado_acumulado_gasto + $ld_ejecutado_acumulado;
		}//if
		/// GASTOS CORRIENTES
		$li_pos_d=$this->dts_reporte->find("tipo","D");
		if($li_pos_d>0)
		{
		  $ld_asignado=$this->dts_reporte->getValue("asignado",$li_pos_d);
		  $ld_asignado_modificado=$this->dts_reporte->getValue("asignado_modificado",$li_pos_d);
		  $ld_programado=$this->dts_reporte->getValue("programado",$li_pos_d);
		  $ld_ejecutado=$this->dts_reporte->getValue("ejecutado",$li_pos_d);
		  $ld_variacion_absoluta=$this->dts_reporte->getValue("variacion_absoluta",$li_pos_d);
		  $ld_variacion_porcentual=$this->dts_reporte->getValue("variacion_porcentual",$li_pos_d);
		  $ld_programado_acumulado=$this->dts_reporte->getValue("programado_acumulado",$li_pos_d);
		  $ld_ejecutado_acumulado=$this->dts_reporte->getValue("ejecutado_acumulado",$li_pos_d);
		
		  $ld_asignado_gasto=$ld_asignado_gasto + $ld_asignado;
		  $ld_asignado_modificado_gasto=$ld_asignado_modificado_gasto + $ld_asignado_modificado;
		  $ld_programado_gasto=$ld_programado_gasto + $ld_programado;
		  $ld_ejecutado_gasto=$ld_ejecutado_gasto + $ld_ejecutado;
		  $ld_variacion_absoluta_gasto=$ld_variacion_absoluta_gasto + $ld_variacion_absoluta;
		  $ld_variacion_porcentual_gasto=$ld_variacion_porcentual_gasto + $ld_variacion_porcentual;
		  $ld_programado_acumulado_gasto=$ld_programado_acumulado_gasto + $ld_programado_acumulado;
		  $ld_ejecutado_acumulado_gasto=$ld_ejecutado_acumulado_gasto + $ld_ejecutado_acumulado;
		}//if
		//UPDATE DEL MONTO TOTAL DE GASTO
		$li_pos_2=$this->dts_reporte->find("tipo","2");
		if($li_pos_2>0)
		{
		  $this->dts_reporte->updateRow("asignado",$ld_asignado_gasto,$li_pos_2);			
		  $this->dts_reporte->updateRow("asignado_modificado",$ld_asignado_modificado_gasto,$li_pos_2);			
		  $this->dts_reporte->updateRow("programado",$ld_programado_gasto,$li_pos_2);			
		  $this->dts_reporte->updateRow("ejecutado",$ld_ejecutado_gasto,$li_pos_2);			
		  $this->dts_reporte->updateRow("variacion_absoluta",$ld_variacion_absoluta_gasto,$li_pos_2);
		  $this->dts_reporte->updateRow("variacion_porcentual",$ld_variacion_porcentual_gasto,$li_pos_2);		
		  $this->dts_reporte->updateRow("programado_acumulado",$ld_programado_acumulado_gasto,$li_pos_2);			
		  $this->dts_reporte->updateRow("ejecutado_acumulado",$ld_ejecutado_acumulado_gasto,$li_pos_2);			
		}//if
		/// RESULTADO DEL EJERCICIO 3=1-2
		
			$ld_resultado_ejercicio_asignado=$ld_asignado_ingreso - $ld_asignado_gasto;
			$ld_resultado_ejercicio_asignado_modificado=$ld_asignado_modificado_ingreso - $ld_asignado_modificado_gasto;
			$ld_resultado_ejercicio_programado=$ld_programado_ingreso - $ld_programado_gasto;
			$ld_resultado_ejercicio_ejecutado=$ld_ejecutado_ingreso - $ld_ejecutado_gasto;
			$ld_resultado_ejercicio_variacion_absoluta=$ld_variacion_absoluta_ingreso - $ld_variacion_absoluta_gasto;
			$ld_resultado_ejercicio_variacion_porcentual=$ld_variacion_porcentual_ingreso - $ld_variacion_porcentual_gasto;
			$ld_resultado_ejercicio_programado_acumulado=$ld_programado_acumulado_ingreso - $ld_programado_acumulado_gasto;
			$ld_resultado_ejercicio_ejecutado_acumulado=$ld_ejecutado_acumulado_ingreso - $ld_ejecutado_acumulado_gasto;
			
			$this->dts_resultado->insertRow("resultado_ejercicio_asignado",$ld_resultado_ejercicio_asignado);
			$this->dts_resultado->insertRow("resultado_ejercicio_asignado_modificado",$ld_resultado_ejercicio_asignado_modificado);
			$this->dts_resultado->insertRow("resultado_ejercicio_programado",$ld_resultado_ejercicio_programado);
			$this->dts_resultado->insertRow("resultado_ejercicio_ejecutado",$ld_resultado_ejercicio_ejecutado);
			$this->dts_resultado->insertRow("resultado_ejercicio_variacion_absoluta",$ld_resultado_ejercicio_variacion_absoluta);
			$this->dts_resultado->insertRow("resultado_ejercicio_variacion_porcentual",$ld_resultado_ejercicio_variacion_porcentual);		
			$this->dts_resultado->insertRow("resultado_ejercicio_programado_acumulado",$ld_resultado_ejercicio_programado_acumulado);		
			$this->dts_resultado->insertRow("resultado_ejercicio_ejecutado_acumulado",$ld_resultado_ejercicio_ejecutado_acumulado);		
		//-------------------------------------------------------------
	 }//if	
	 return $lb_valido;
	}//fin uf_spg_reportes_estado_de_resultado
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_reportes_ingresos_generales($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reportes_ingresos_generales
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Estado de Resultado Ingesos Generales
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	  $ls_formpre=str_replace('-','',$ls_formpre);
	  $li_len=strlen($ls_formpre);
	  $li_len=$li_len-9;
	  $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
	  $ls_sql=" SELECT spi_cuenta, max(denominacion) as denominacion,                  ".
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
			  "        spi_cuenta = '305000000".$ls_ceros."' OR spi_cuenta = '305010000".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010100".$ls_ceros."' OR spi_cuenta = '305010200".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010300".$ls_ceros."' OR spi_cuenta = '305010301".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010302".$ls_ceros."' OR spi_cuenta = '305010303".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010304".$ls_ceros."' OR spi_cuenta = '305010305".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010306".$ls_ceros."' OR spi_cuenta = '305010307".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010308".$ls_ceros."' OR spi_cuenta = '305010309".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010400".$ls_ceros."' OR spi_cuenta = '305010401".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010402".$ls_ceros."' OR spi_cuenta = '305010403".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010404".$ls_ceros."' OR spi_cuenta = '305010405".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010406".$ls_ceros."' OR spi_cuenta = '305010407".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010408".$ls_ceros."' OR spi_cuenta = '305010409".$ls_ceros."' OR ".
			  "        spi_cuenta = '305010500".$ls_ceros."' OR spi_cuenta = '305010600".$ls_ceros."'  ".
			  " GROUP BY spi_cuenta ".
			  " ORDER BY spi_cuenta ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_reportes_ingresos_generales ".
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
			   
			   if(($ls_spi_cuenta=='305010100')||($ls_spi_cuenta=='305010200')||($ls_spi_cuenta=='305010500')||($ls_spi_cuenta=='305010600'))
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
			   $this->dts_ingresos_generales->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_generales->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_generales->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_generales->insertRow("asignado_modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_generales->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_generales->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_generales->insertRow("variacion_absoluta",0);		
		  	   $this->dts_ingresos_generales->insertRow("variacion_porcentual",0);		
			   $this->dts_ingresos_generales->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_generales->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
		  	   $this->dts_ingresos_generales->insertRow("tipo","A");
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("asignado_modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_absoluta",0);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_porcentual",0);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("tipo","A");
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_ingresos_generales
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reportes_ingresos_actividadespropias($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reportes_ingresos_actividadespropias
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Estado de Resultado Ingesos Generales
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	  $ls_formpre=str_replace('-','',$ls_formpre);
	  $li_len=strlen($ls_formpre);
	  $li_len=$li_len-9;
	  $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
	  $ls_sql=" SELECT spi_cuenta, max(denominacion) as denominacion, max(status) as estatus,             ".
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
	  	      " FROM   spi_cuentas																		 ".
			  " WHERE  codemp='".$this->ls_codemp."' AND 												 ".
			  "        spi_cuenta = '301090000".$ls_ceros."' OR spi_cuenta = '301090100".$ls_ceros."' OR ".
			  "        spi_cuenta = '301090200".$ls_ceros."' OR spi_cuenta = '301099900".$ls_ceros."' OR ".
			  "        spi_cuenta = '301030000".$ls_ceros."' OR spi_cuenta = '303990000".$ls_ceros."'    ".
			  " GROUP BY spi_cuenta 																	 ".
			  " ORDER BY spi_cuenta ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_reportes_ingresos_actividadespropias ".
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
			   $ls_spi_cuenta=trim($row["spi_cuenta"]);
			   $ls_denominacion=$row["denominacion"];
			   $ls_estatus=$row["estatus"];
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
			   
			   if($ls_spi_cuenta=='301030000'.$ls_ceros)
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
			   $this->dts_ingresos_actividadespropias->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_actividadespropias->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_actividadespropias->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_actividadespropias->insertRow("asignado_modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_actividadespropias->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_actividadespropias->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_actividadespropias->insertRow("variacion_absoluta",0);		
		  	   $this->dts_ingresos_actividadespropias->insertRow("variacion_porcentual",0);		
			   $this->dts_ingresos_actividadespropias->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_actividadespropias->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
		  	   $this->dts_ingresos_actividadespropias->insertRow("tipo","B");
			   $this->dts_ingresos_actividadespropias->insertRow("estatus",$ls_estatus);
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("asignado_modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_absoluta",0);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_porcentual",0);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("tipo","B");
			   $this->dts_reporte_temporal->insertRow("estatus",$ls_estatus);
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_ingresos_actividadespropias
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reportes_otrosingresos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reportes_otrosingresos_corrientes
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Estado de Resultado Ingesos Generales
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	  $ls_formpre=str_replace('-','',$ls_formpre);
	  $li_len=strlen($ls_formpre);
	  $li_len=$li_len-9;
	  $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
	  $ls_sql=" SELECT spi_cuenta, max(denominacion) as denominacion,                                    ".
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
	  	      " FROM   spi_cuentas 																		 ".
			  " WHERE  codemp='".$this->ls_codemp."' AND 												 ".
			  "        spi_cuenta = '301040000".$ls_ceros."' OR spi_cuenta = '301050000".$ls_ceros."' OR ".
			  "        spi_cuenta = '301100000".$ls_ceros."' OR spi_cuenta = '302030000".$ls_ceros."' OR ".
			  "        spi_cuenta = '302040000".$ls_ceros."' OR spi_cuenta = '302050000".$ls_ceros."'    ".
			  " GROUP BY spi_cuenta 																	 ".
			  " ORDER BY spi_cuenta ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_reportes_otrosingresos_corrientes ".
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
			   $ls_spi_cuenta=trim($row["spi_cuenta"]);
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
			   
			   if($ls_spi_cuenta=='302030000'.$ls_ceros)
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
			   $this->dts_ingresos_ingresoscorrientes->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_ingresos_ingresoscorrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_ingresos_ingresoscorrientes->insertRow("asignado",$ld_previsto);
			   $this->dts_ingresos_ingresoscorrientes->insertRow("asignado_modificado",$ld_previsto_modificado);
			   $this->dts_ingresos_ingresoscorrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_ingresos_ingresoscorrientes->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_ingresos_ingresoscorrientes->insertRow("variacion_absoluta",0);		
		  	   $this->dts_ingresos_ingresoscorrientes->insertRow("variacion_porcentual",0);		
			   $this->dts_ingresos_ingresoscorrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_ingresos_ingresoscorrientes->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
		  	   $this->dts_ingresos_ingresoscorrientes->insertRow("tipo","C");
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spi_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_previsto);
			   $this->dts_reporte_temporal->insertRow("asignado_modificado",$ld_previsto_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_cobrado);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_absoluta",0);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_porcentual",0);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_cobrado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("tipo","C");
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_otrosingresos_corrientes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reportes_gastos_de_consumo($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reportes_gastos_de_consumo
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Estado de Resultado Ingesos Generales
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	  $ls_formpre=str_replace('-','',$ls_formpre);
	  $li_len=strlen($ls_formpre);
	  $li_len=$li_len-9;
	  $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
	  $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignado) as asignado, 		 ".
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
	  	      " FROM   spg_cuentas 																		 ".
			  " WHERE  codemp='".$this->ls_codemp."' AND 												 ".
			  "        spg_cuenta = '401000000".$ls_ceros."' OR spg_cuenta = '402000000".$ls_ceros."' OR ".
			  "        spg_cuenta = '403000000".$ls_ceros."' OR spg_cuenta = '408000000".$ls_ceros."' OR ".
			  "        spg_cuenta = '408010000".$ls_ceros."' OR spg_cuenta = '408010100".$ls_ceros."' OR ".
			  "        spg_cuenta = '408010200".$ls_ceros."'   											 ".
			  " GROUP BY spg_cuenta".
			  " ORDER BY spg_cuenta ";//print $ls_sql;
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
			   $ls_denominacion=$row["denominacion"];
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
			   
			   if($ls_spg_cuenta=='403000000'.$ls_ceros)
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
			   $this->dts_gastos_consumo->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_gastos_consumo->insertRow("denominacion",$ls_denominacion);
			   $this->dts_gastos_consumo->insertRow("asignado",$ld_asignado);
			   $this->dts_gastos_consumo->insertRow("asignado_modificado",$ld_asignado_modificado);
			   $this->dts_gastos_consumo->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_gastos_consumo->insertRow("ejecutado",$ld_comprometer);		
		  	   $this->dts_gastos_consumo->insertRow("variacion_absoluta",0);		
		  	   $this->dts_gastos_consumo->insertRow("variacion_porcentual",0);		
			   $this->dts_gastos_consumo->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_gastos_consumo->insertRow("ejecutado_acumulado",$ld_comprometer_acumulado);
		  	   $this->dts_gastos_consumo->insertRow("tipo","D");
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_temporal->insertRow("asignado_modificado",$ld_asignado_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_comprometer);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_absoluta",0);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_porcentual",0);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_comprometer_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("tipo","D");
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_gastos_de_consumo
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_reportes_gastos_corrientes($adt_fecdes,$adt_fechas,$adts_datastore,$as_mesdes,$as_meshas)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function : uf_spg_reportes_gastos_corrientes
	 //     Argumentos : adt_fecdes ... adt_fechas  // rango de fecha del reporte
	 //                  adts_datastore  // datastore que imprime el reporte
     //	       Returns : Retorna true o false si se realizo la consulta para el reporte
	 //	   Description : Reporte que genera salida del Estado de Resultado Ingesos Generales
	 //     Creado por : Ing. Yozelin Barragán.
	 // Fecha Creación : 18/06/2008                       Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	  $ls_formpre=str_replace('-','',$ls_formpre);
	  $li_len=strlen($ls_formpre);
	  $li_len=$li_len-9;
	  $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
	  $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignado) as asignado, ".
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
			  "        spg_cuenta = '407000000".$ls_ceros."' OR spg_cuenta = '407010000".$ls_ceros."' OR ".
			  "        spg_cuenta = '407010100".$ls_ceros."' OR spg_cuenta = '407010300".$ls_ceros."' OR ".
			  "        spg_cuenta = '407020000".$ls_ceros."' OR spg_cuenta = '408020000".$ls_ceros."' OR ".
			  "        spg_cuenta = '408050000".$ls_ceros."' OR spg_cuenta = '408060000".$ls_ceros."' OR ".
			  "        spg_cuenta = '408080000".$ls_ceros."'    ".
			  " GROUP BY spg_cuenta ".
			  " ORDER BY spg_cuenta ";
			 // print $ls_sql;
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                        "MÉTODO->uf_spg_reportes_gastos_corrientes ".
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
			   $ls_denominacion=$row["denominacion"];
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
			   
			   if($ls_spg_cuenta=='407020000')
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
			   $this->dts_gastos_corrientes->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_gastos_corrientes->insertRow("denominacion",$ls_denominacion);
			   $this->dts_gastos_corrientes->insertRow("asignado",$ld_asignado);
			   $this->dts_gastos_corrientes->insertRow("asignado_modificado",$ld_asignado_modificado);
			   $this->dts_gastos_corrientes->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_gastos_corrientes->insertRow("ejecutado",$ld_comprometer);		
		  	   $this->dts_gastos_corrientes->insertRow("variacion_absoluta",0);		
		  	   $this->dts_gastos_corrientes->insertRow("variacion_porcentual",0);		
			   $this->dts_gastos_corrientes->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_gastos_corrientes->insertRow("ejecutado_acumulado",$ld_comprometer_acumulado);
		  	   $this->dts_gastos_corrientes->insertRow("tipo","E");
			   /// datastore  del reportes
			   $this->dts_reporte_temporal->insertRow("cuenta",$ls_spg_cuenta);
			   $this->dts_reporte_temporal->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte_temporal->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte_temporal->insertRow("asignado_modificado",$ld_asignado_modificado);
			   $this->dts_reporte_temporal->insertRow("programado",$ld_programado_trimestral);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado",$ld_comprometer);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_absoluta",0);		
		  	   $this->dts_reporte_temporal->insertRow("variacion_porcentual",0);		
			   $this->dts_reporte_temporal->insertRow("programado_acumulado",$ld_programado_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("ejecutado_acumulado",$ld_comprometer_acumulado);
		  	   $this->dts_reporte_temporal->insertRow("tipo","E");
			   $lb_valido=true;
		    }//while
	    }//if	
	    $this->io_sql->free_result($rs_data);
		}//else
    return $lb_valido;
    }//fin uf_spg_reportes_gastos_corrientes
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_ejecutado_trimestral($as_spi_cuenta,$adt_fecdes,$adt_fechas,&$ad_cobrado_anticipado,
	                                     &$ad_cobrado,&$ad_devengado,&$ad_aumento,&$ad_disminucion,$as_detallar)
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
	 //                     $as_detallar  -----> variable que me de
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera los eejcutados por trimestre
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    21/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ad_cobrado_anticipado=0; 
	 $ad_cobrado = 0;
	 $ad_devengado =0;
	 $ad_aumento = 0;
	 $ad_disminucion = 0;
	 $as_spi_cuenta = $this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	 $ls_cadena=" spi_cuenta like '".$as_spi_cuenta."'  AND";
	 /*if($as_detallar==true)
	 {
	   $as_spi_cuenta = $this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	   $ls_cadena=" spi_cuenta like '".$as_spi_cuenta."'  AND";
	 }
	 elseif($as_detallar==false)
	 {
	   $as_spi_cuenta = $as_spi_cuenta;
	   $ls_cadena="spi_cuenta = '".$as_spi_cuenta."'  AND";
	 }*/
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.cobrado,  OP.devengado  ".
               " FROM   spi_dt_cmp DT, spi_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        ".$ls_cadena."  ".
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
     }//fin uf_spi_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_ejecutado_acumulado($as_spi_cuenta,$adt_fechas,&$ad_cobrado_anticipado_acumulado,&$ad_cobrado_acumulado,
	                                    &$ad_devengado_acumulado,&$ad_aumento_acumulado,&$ad_disminucion_acumulado,$as_detallar)
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
	 $ad_cobrado_acumulado = 0;
	 $ad_devengado_acumulado = 0;
	 $ad_aumento_acumulado = 0;
	 $ad_disminucion_acumulado	=0;
	 $as_spi_cuenta = $this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	 $ls_cadena="spi_cuenta like '".$as_spi_cuenta."'  AND"; 
	 /*if($as_detallar==true)
	 {
	   $as_spi_cuenta = $this->io_sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	   $ls_cadena="spi_cuenta like '".$as_spi_cuenta."'  AND";
	 }
	 elseif($as_detallar==false)
	 {
	   $as_spi_cuenta = $as_spi_cuenta;
	   $ls_cadena="spi_cuenta = '".$as_spi_cuenta."'  AND";
	 }*/
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.cobrado, OP.devengado                      ".
               " FROM   spi_dt_cmp DT, spi_operaciones OP     ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND  ".
               "        DT.operacion = OP.operacion AND       ".
               "        ".$ls_cadena."   ".
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
     }//fin uf_spi_ejecutado_acumulado
	//-----------------------------------------------------------------------------------------------------------------------------------

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
	 $ad_comprometer=0;  
	 $ad_causado=0;  
	 $ad_pagado=0;
	 $ad_aumento=0;  
	 $ad_disminucion=0;
	 $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 /*if($as_detallar==true)
	 {
	   $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 }
	 elseif($as_detallar==false)
	 {
	   $as_spg_cuenta = $as_spg_cuenta;
	   $ls_cadena="spg_cuenta = '".$as_spg_cuenta."'  AND";
	 }*/
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        ".$ls_cadena."                  ".
               "        fecha BETWEEN '".$adt_fecdes."' AND  '".$adt_fechas."' "; 
	  //print  $ls_sql."<br>"; 
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
	 $ad_comprometer_acumulado = 0;
	 $ad_causado_acumulado = 0;
	 $ad_pagado_acumulado = 0;
	 $ad_aumento_acumulado = 0;
	 $ad_disminucion_acumulado = 0;
	 $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND"; 
	 /*if($as_detallar==true)
	 {
	   $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   $ls_cadena="spg_cuenta like '".$as_spg_cuenta."'  AND";
	 }
	 elseif($as_detallar==false)
	 {
	   $as_spg_cuenta = $as_spg_cuenta;
	   $ls_cadena="spg_cuenta = '".$as_spg_cuenta."'  AND";
	 }*/
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
		$ls_formpre=$_SESSION["la_empresa"]["formpre"];
	    $ls_formpre=str_replace('-','',$ls_formpre);
	    $li_len=strlen($ls_formpre);
	    $li_len=$li_len-9;
	    $ls_ceros=$this->io_funciones->uf_cerosderecha("",$li_len);
		$ls_sql = "SELECT denominacion FROM sigesp_plan_unico_re WHERE sig_cuenta='".$as_spi_cuenta.$ls_ceros."' ";
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

	
	//-----------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVOS " CONSOLIDADO DE EJECUCION TRIMESTRAL  POR PROGRAMATICA"  //
	/////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_select_programatica_consolidado($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,
	                                            	$as_codestpro4_ori,$as_codestpro5_ori,$as_codestpro1_des,
					                                $as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
											  		$as_codestpro5_des,&$rs_data)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_select_programatica_consolidado
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1_ori ....  as_codestpro5_ori -----> ESTRUCTURAS PROGRAMATICAS ORIGEN
	 //                     $as_codestpro1_des ....  as_codestpro5_des -----> ESTRUCTURAS PROGRAMATICAS DESTINO 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 07 del CONSOLIDADO DE EJECUCION TRIMESTRAL
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
	 $ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;
	 $ls_gestor = $_SESSION["ls_gestor"];
	 if (strtoupper($ls_gestor)=="MYSQLT")
	 {
	   $ls_cadena="CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)";
	 }
	 else
	 {
	   $ls_cadena="codestpro1||codestpro2||codestpro3||codestpro4||codestpro5";
	 }
	 $ls_sql=" SELECT distinct ".$ls_cadena." as programatica ".
             " FROM spg_cuentas ".
			 " WHERE codemp='".$this->ls_codemp."' AND ".$ls_cadena." ".
             "       between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ";
			// print 	$ls_sql;
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spg_select_programatica_consolidado ".
										"ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido = false;
	 }
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spg_reporte_consolidado_de_ejecucion_trimestral_x_programatica($adt_fecdes,$adt_fechas,$as_mesdes,$as_meshas,
	                                                                           $as_codestpro1,$as_codestpro2,$as_codestpro3,
																			   $as_codestpro4,$as_codestpro5)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_consolidado_de_ejecucion_trimestral
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
	 $ls_estructura=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
	 $ls_gestor = $_SESSION["ls_gestor"];
	 if (strtoupper($ls_gestor)=="MYSQLT")
	 {
	   $ls_cadena="CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)";
	 }
	 else
	 {
	   $ls_cadena="codestpro1||codestpro2||codestpro3||codestpro4||codestpro5";
	 }
	 $ls_sql=" SELECT codemp,spg_cuenta, denominacion, status, sc_cuenta, sum(asignado) as asignado,    ".
             "        sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,       ".
             "        sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento,          ".
             "        sum(disminucion) as disminucion, sum(enero) as enero , sum(febrero) as febrero ,  ".
             "        sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, sum(junio) as junio, ".
             "        sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre,        ".
             "        sum(octubre) as octubre, sum(noviembre) as noviembre,                             ".
			 "        sum(diciembre) as diciembre, nivel, referencia,                                   ".
			 "        sum(enero+febrero+marzo) as trimetrei, sum(abril+mayo+junio) as trimetreii,       ".
			 "        sum(julio+agosto+septiembre) as trimetreiii,                                      ".
			 "        sum(octubre+noviembre+diciembre) as trimetreiv                                    ".
             " FROM spg_cuentas                                                                         ".
             " WHERE codemp='".$this->ls_codemp."'  AND  nivel = '1' AND                                ".
			 "       ".$ls_cadena."='".$ls_estructura."'                                                ".
             " GROUP BY spg_cuenta                                                                      ".
             " ORDER BY spg_cuenta ";
			// print 	$ls_sql."<br>";
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivos ". 
			                            "MÉTODO->uf_spg_reporte_consolidado_de_ejecucion_trimestral ".
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
			   $ls_denominacion=$row["denominacion"];
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
			   $ld_trimetreI=$row["trimetrei"]; 
			   $ld_trimetreII=$row["trimetreii"]; 
			   $ld_trimetreIII=$row["trimetreiii"]; 
			   $ld_trimetreIV=$row["trimetreiv"]; 
			   
			   $lb_valido=$this->uf_spg_ejecutado_trimestral($ls_spg_cuenta,$adt_fecdes,$adt_fechas,&$ld_comprometer,
			                                                 &$ld_causado,&$ld_pagado,&$ld_aumento,&$ld_disminucion);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado($ls_spg_cuenta,$adt_fechas,&$ld_comprometer_acumulado,
																&$ld_causado_acumulado,&$ld_pagado_acumulado,&$ld_aumento_acumulado,
																&$ld_disminucion_acumulado);
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
			   $ld_disponibilidad=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_comprometer;
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("asignado",$ld_asignado);
			   $this->dts_reporte->insertRow("asignado_modificado",$ld_asignado_modificado);
			   $this->dts_reporte->insertRow("programado",$ld_programado_trimestral);
			   $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
		  	   $this->dts_reporte->insertRow("causado",$ld_causado);					 
			   $this->dts_reporte->insertRow("pagado",$ld_pagado);					 
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
			   $this->dts_reporte->insertRow("compromiso_acumulado",$ld_comprometer_acumulado);
		  	   $this->dts_reporte->insertRow("causado_acumulado",$ld_causado_acumulado);					 
			   $this->dts_reporte->insertRow("pagado_acumulado",$ld_pagado_acumulado);	
			   $this->dts_reporte->insertRow("disponibilidad",$ld_disponibilidad);	
			   $lb_valido=true;
		    }//while
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_trimestral_x_programatica($as_spg_cuenta,$adt_fecdes,$adt_fechas,&$ad_comprometer,&$ad_causado,&$ad_pagado,
	                                     &$ad_aumento,&$ad_disminucion)
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
	 $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        spg_cuenta like '".$as_spg_cuenta."'  AND ".
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
     }//fin uf_spg_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_acumulado_x_programatica($as_spg_cuenta,$adt_fechas,&$ad_comprometer_acumulado,&$ad_causado_acumulado,
	                                    &$ad_pagado_acumulado,&$ad_aumento_acumulado,&$ad_disminucion_acumulado)
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
	 $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 $ls_sql = " SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
               "        spg_cuenta like '".$as_spg_cuenta."'  AND ".
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
     }//fin uf_spg_ejecutado_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------

	
}//fin de clase
?>