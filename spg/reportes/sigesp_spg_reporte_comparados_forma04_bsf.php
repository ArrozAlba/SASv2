<?php
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../../shared/class_folder/class_sigesp_int_spg.php");
require_once("../../shared/class_folder/class_sigesp_int_spi.php");	
/****************************************************************************************************************************************/	
class sigesp_spg_reporte_comparados_forma04_bsf
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $dts_cab;
	var $obj="";
	var $SQL;
	var $siginc;
	var $con;
	var $fun;	
	var $io_msg;
	var $io_fecha;
	var $sigesp_int_spg;
	var $dts_prog;
/****************************************************************************************************************************************/	
    function  sigesp_spg_reporte_comparados_forma04_bsf()
    {
		$this->fun=new class_funciones() ;
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($this->con);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->dts_cab=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->dts_prog=new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int_spg=new class_sigesp_int_spg();
    }
/****************************************************************************************************************************************/
	/////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " EJECUCION FINANCIERA MENSUAL FORMA  #0402 " //
	///////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_comparados_ejecucion_financiera_forma0402($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,
	                                                                   $as_codestpro4_ori,$as_codestpro5_ori,$as_codestpro1_des,
																	   $as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
																	   $as_codestpro5_des,$adt_fecdes,$adt_fechas,$ai_nivel,
																	   $as_codfuefindes,$as_codfuefinhas,$as_estclades,
																	   $as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_ejecucion_financiera_forma0402
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
	 //                     $adt_fecdes  //  fecha desde 
	 //                     $adt_fechas  //  fecha hasta
	 //                     $ai_nivel    //  nivel 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = false;	 
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_codemp = $this->dts_empresa["codemp"];
		$this->dts_reporte->resetds("spg_cuenta");
		$ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori.$as_estclades;
		$ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des.$as_estclahas;	 
		$li_mesdes=$adt_fecdes;
		$li_meshas=$adt_fechas; 
		$ls_tipo="O";//comprometer 
	    if (strtoupper($ls_gestor)=="MYSQL")
	    {
		   $ls_cadena="CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4,C.codestpro5,C.estcla)";
  	    }
	    else
	    {
		   $ls_cadena="C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4||C.codestpro5||C.estcla";
	    }
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
		   $ls_tabla="spg_ep3"; 
		   $ls_cadena_fuefin="AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND C.estcla=EP.estcla";
		}
		elseif($li_estmodest==2)
		{
		   $ls_tabla="spg_ep5";
		   $ls_cadena_fuefin=" AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND ".
							 "	   C.codestpro4=EP.codestpro4 AND C.codestpro5=EP.codestpro5 AND C.estcla=EP.estcla";
		}
		$ls_sql=" SELECT C.spg_cuenta, max(C.nivel) as nivel, max(C.denominacion) as denominacion, ".
				"        sum(C.asignado) as asignado, sum(C.comprometido) as comprometido, ".
				"        sum(C.causado) as causado, sum(C.pagado) as pagado, sum(C.aumento) as aumento, ".
				"        sum(C.disminucion) as disminucion, sum(C.enero) as enero, sum(C.febrero) as febrero, ".
				"        sum(C.marzo) as marzo, sum(C.abril) as abril, sum(C.mayo) as mayo,  ".
				"        sum(C.junio) as junio, sum(C.julio) as julio, sum(C.agosto) as agosto,  ".
				"        sum(C.septiembre) as septiembre, sum(C.octubre) as octubre,  ".
				"        sum(C.noviembre) as noviembre, sum(C.diciembre) as diciembre, ".
				"        C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4,C.codestpro5,C.estcla ".
			    " FROM   spg_cuentas C, spg_plantillareporte R, ".$ls_tabla." EP ".
			    " WHERE  C.codemp='".$ls_codemp."' AND R.codrep='0402' AND R.status <> 'I' AND ".
				"        ".$ls_cadena."  BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' AND ".
				"        EP.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' AND ".
				"        C.codemp=R.codemp AND  C.codestpro1=R.codestpro1  AND  ".
				"        C.codestpro2=R.codestpro2  AND  C.codestpro3=R.codestpro3  AND  ".
				"        C.codestpro4=R.codestpro4  AND  C.codestpro5=R.codestpro5  AND  ".
				"        C.spg_cuenta=R.spg_cuenta   ".$ls_cadena_fuefin." ".
			    " GROUP BY C.spg_cuenta, C.codestpro1, C.codestpro2, C.codestpro3, C.codestpro4, ".
				"	    C.codestpro5,C.estcla ".
       			" ORDER BY C.spg_cuenta  ";
	    /*$ls_sql=" SELECT c.spg_cuenta, max(c.nivel) as nivel, max(c.denominacion) as denominacion, sum(c.asignado) as asignado, ".
			    "        sum(c.comprometido) as comprometido, sum(c.causado) as causado, sum(c.pagado) as pagado, ".
			    "        sum(c.aumento) as aumento, sum(c.disminucion) as disminucion, sum(c.enero) as enero, ".
			    "        sum(c.febrero) as febrero, sum(c.marzo) as marzo, sum(c.abril) as abril, sum(c.mayo) as mayo, ".
			    "        sum(c.junio) as junio, sum(c.julio) as julio, sum(c.agosto) as agosto, sum(c.septiembre) as septiembre, ".
			    "        sum(c.octubre) as octubre, sum(c.noviembre) as noviembre, sum(c.diciembre) as diciembre, ".
			    "        c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5 ".
			    " FROM   spg_cuentas c, spg_plantillareporte r ".
			    " WHERE  c.codemp=r.codemp AND r.codemp='".$ls_codemp."' AND (c.codestpro1=r.codestpro1 AND c.codestpro2=r.codestpro2 AND ".
			    "        c.codestpro3=r.codestpro3 AND c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5) AND ".
			    "        (c.spg_cuenta = r.spg_cuenta) AND (r.status <> 'I') AND  r.codrep='0402' AND ".
			    "        ".$ls_cadena."    between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ".
			    " GROUP BY c.spg_cuenta, c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5  ".
			    " ORDER BY c.spg_cuenta ";*/
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reportes_comparados_ejecucion_financiera_forma0402".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   $lb_ok=false;
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_denominacion=$row["denominacion"];
			   $li_nivel=$row["nivel"];
			   $ld_asignado=$row["asignado"];
			   $ld_comprometido=$row["comprometido"];
			   $ld_causado=$row["causado"];
			   $ld_pagado=$row["pagado"];
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
			   if($li_nivel<=$ai_nivel)
			   {
			     $lb_ok=true;
			   }
			   if($lb_ok)
			   {				  
				   $ld_monto_programado=0;
				   $ld_monto_acumulado=0;
				  //$ls_codrep="00003";
				   $ls_codrep="0402";
				   $lb_valido=$this->uf_spg_reporte_calcular_programado_r($ls_codrep,$ls_spg_cuenta,$ls_estructura_desde,
																		  $ls_estructura_hasta,$li_mesdes,$li_meshas,
																		  $ld_monto_programado,$ld_monto_acumulado,
																		  $as_codfuefindes,$as_codfuefinhas);
				   if($lb_valido)
				   {//mes  anterior
					  $li_fecdes=substr($li_mesdes,5,2);
					  $li_fechas=substr($li_meshas,5,2);
					  $li_fecdes=intval($li_fecdes);
					  if($li_fecdes>1)
					  {
						  $ld_monto_ejecutado=0;
						  $ld_prog_t_ant=0;  
						  $ld_compr_t_ant=0;
						  $ld_disponible_fecha=0;
						  $ld_ejec_acum_t_ant=0;
						  $lb_valido=$this->uf_spg_reporte_calcular_programado_trimestre_anterior(2,$ld_asignado,$ls_spg_cuenta,
																								  $ls_estructura_desde,$ls_estructura_hasta,
																								  $li_mesdes,$li_meshas,$ld_monto_ejecutado,
																								  $ls_tipo,$ld_enero,$ld_febrero,$ld_marzo,
																								  $ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																								  $ld_agosto,$ld_septiembre,$ld_octubre,
																								  $ld_noviembre,$ld_diciembre,$ls_codrep,
																								  $as_codfuefindes,$as_codfuefinhas);				
						 $ld_ejec_t_ant=$ld_monto_ejecutado;
					  }
					  else
					  {
						  $ld_ejec_t_ant=0;
					  }
				   }//if  
				   if($lb_valido)
				   {
					  $ld_aumdis_t_ant=0;
					  $ld_prog_t_ant=0;  
					  $ld_compr_t_ant=0;
					  $ld_disponible_fecha=0;
					  $ld_ejec_acum_t_ant=0;
                      $ld_comprometer=0;
					  $ld_causado=0;
					  $ld_pagado=0;
                      $ld_comprometer_acumulado=0;
					  $ld_causado_acumulado=0;
					  $ld_pagado_acumulado=0;
					  $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_trimestre_act($ls_spg_cuenta,$ls_estructura_desde,$ls_estructura_hasta,
																						$li_mesdes,$li_meshas,$ld_monto_ejecutado,
																						$ld_ejecutado_acumulado,$ld_aumdismes,$ld_aumdisacum,
																						$ld_comprometer,$ld_causado,$ld_pagado,
																						$ld_comprometer_acumulado,
																						$ld_causado_acumulado,
																						$ld_pagado_acumulado,$ls_tipo,
																						$ld_ejec_acum_t_ant,$as_codfuefindes,
																						$as_codfuefinhas);
				   }	   
				   if($lb_valido)
				   {
						 $ld_dispon_fecha=$ld_asignado+($ld_aumdismes+$ld_aumdis_t_ant)-($ld_comprometer+$ld_ejec_acum_t_ant);
						 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
						 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
						 $this->dts_reporte->insertRow("asignado",$ld_asignado);
						 $this->dts_reporte->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_reporte->insertRow("monto_programado_acumulado",$ld_monto_acumulado);
						 $this->dts_reporte->insertRow("aumdis_mes",$ld_aumdismes);
						 $this->dts_reporte->insertRow("aumdis_acumulado",$ld_aumdisacum);
						 $this->dts_reporte->insertRow("ejecutado_mes",$ld_monto_ejecutado);
						 $this->dts_reporte->insertRow("ejecutado_acum",$ld_ejecutado_acumulado);
						 $this->dts_reporte->insertRow("compromiso",$ld_comprometer);
						 $this->dts_reporte->insertRow("causado",$ld_causado);					 
						 $this->dts_reporte->insertRow("pagado",$ld_pagado);
						 $this->dts_reporte->insertRow("compromiso_acumulado",$ld_comprometer_acumulado);
						 $this->dts_reporte->insertRow("causado_acumulado",$ld_causado_acumulado);					 
						 $this->dts_reporte->insertRow("pagado_acumulado",$ld_pagado_acumulado);					 
						 $this->dts_reporte->insertRow("ejec_t_ant",$ld_ejec_t_ant);
						 $this->dts_reporte->insertRow("compr_t_ant",$ld_compr_t_ant);
						 $this->dts_reporte->insertRow("disponible_fecha",$ld_dispon_fecha);
						 $this->dts_reporte->insertRow("nivel",$li_nivel);
						 $lb_valido=true;
				   }//if
				}//if 
			 }//while
			 $this->SQL->free_result($rs_data);
		 }//else
     return $lb_valido;
    }//fin uf_spg_reportes_comparados_ejecucion_financiera_formato3
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_programado_r($as_codrep,$as_spg_cuenta,$as_estructura_desde,$as_estructura_hasta,$ai_mesdes,
	                                              $ai_meshas,&$ad_monto_programado,&$ad_monto_acumulado,$as_codfuefindes,
												  $as_codfuefinhas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_programado_r
	 //         Access :	private
	 //     Argumentos :    $as_codrep  // codigo del reporte
	 //                     $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
	 //                     $ad_monto_acumulado // monto programado del acumulado (referencia)  
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida, calcula los programadas  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    31/05/2006          Fecha última Modificacion :26/06/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	 
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $li_mesd=substr($ai_mesdes,5,2);
      $li_mesh=substr($ai_meshas,5,2);
	  $li_mesdes=intval($li_mesd);
      $li_meshas=intval($li_mesh);
	  if($as_codrep=="0415")
	  {
		  if (strtoupper($ls_gestor)=="MYSQL")
		  {
			   $ls_cadena="CONCAT(C.codestpro1,C.codestpro2,C.estcla)";
		  }
		  else
		  {
			   $ls_cadena="C.codestpro1||C.codestpro2||C.estcla";
		  }
	  } 
	  else
	  {
		  if (strtoupper($ls_gestor)=="MYSQL")
		  {
			   $ls_cadena="CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4,C.codestpro5,C.estcla)";
		  }
		  else
		  {
			   $ls_cadena="C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4||C.codestpro5||C.estcla";
		  }
	  } 
	  $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	  if($li_estmodest==1)
	  {
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND C.estcla=EP.estcla";
	  }
	  elseif($li_estmodest==2)
	  {
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND ".
							"	  C.codestpro4=EP.codestpro4 AND C.codestpro5=EP.codestpro5 AND C.estcla=EP.estcla";
	  }
	  $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignadoaux) as asignado, ".
			  "        sum(comprometidoaux) as comprometido, sum(causadoaux) as causado, sum(pagadoaux) as pagado, ".
			  "        sum(aumentoaux) as aumento, sum(disminucionaux) as disminucion, sum(eneroaux) as enero, ".
			  "        sum(febreroaux) as febrero, sum(marzoaux) as marzo, sum(abrilaux) as abril, sum(mayoaux) as mayo, ".
			  "        sum(junioaux) as junio, sum(julioaux) as julio, sum(agostoaux) as agosto, sum(septiembreaux) as septiembre, ".
			  "        sum(octubreaux) as octubre, sum(noviembreaux) as noviembre, sum(diciembreaux) as diciembre ".
			  " FROM   spg_plantillareporte C, ".$ls_tabla." EP  ".
			  " WHERE  C.codemp='".$ls_codemp."' AND ".$ls_cadena." BETWEEN '".$as_estructura_desde."' AND '".$as_estructura_hasta."' AND ".
			  "        C.codrep='".$as_codrep."' AND  C.spg_cuenta='".$as_spg_cuenta."' AND ".
			  "        EP.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."'  ".$ls_cadena_fuefin." ".
			  " GROUP BY C.spg_cuenta ".
			  " ORDER BY C.spg_cuenta ";
			  
	  /*$ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignadoaux) as asignado, ".
			  "        sum(comprometidoaux) as comprometido, sum(causadoaux) as causado, sum(pagadoaux) as pagado, ".
			  "        sum(aumentoaux) as aumento, sum(disminucionaux) as disminucion, sum(eneroaux) as enero, ".
			  "        sum(febreroaux) as febrero, sum(marzoaux) as marzo, sum(abrilaux) as abril, sum(mayoaux) as mayo, ".
			  "        sum(junioaux) as junio, sum(julioaux) as julio, sum(agostoaux) as agosto, sum(septiembreaux) as septiembre, ".
			  "        sum(octubreaux) as octubre, sum(noviembreaux) as noviembre, sum(diciembreaux) as diciembre ".
			  " FROM   spg_plantillareporte  ".
			  " WHERE  codemp='".$ls_codemp."' AND ".$ls_cadena." between '".$as_estructura_desde."' AND '".$as_estructura_hasta."' AND ".
			  "        codrep='".$as_codrep."' AND  spg_cuenta='".$as_spg_cuenta."' ".
			  " GROUP BY spg_cuenta ".
			  " ORDER BY spg_cuenta ";*/
		//	print '<br>'.$ls_sql;
	 $rs_prog=$this->SQL->select($ls_sql);
	 if($rs_prog===false)
	 {   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_programado_r".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
	 }
	 else
	 {
	   $li_row=$this->SQL->num_rows($rs_prog);
	   $ad_monto_programado_total=0;
	   $ad_monto_acumulado_total=0;
	   while($row=$this->SQL->fetch_row($rs_prog))
	   {
		 if($li_row>0)
		 { 
		   if(!(($li_mesdes>=1)&&($li_meshas<=12)))
		   {
		     return false;
		   }
		   for($i=$li_mesdes;$i<=$li_meshas;$i++)
		   {
		     switch ($li_mesdes)
			 {
			     case 1:
			          $ad_monto_programado=$ad_monto_programado+$row["enero"];
				 break;
			     case 2:
			          $ad_monto_programado=$ad_monto_programado+$row["febrero"];
				 break;
			     case 3:
			          $ad_monto_programado=$ad_monto_programado+$row["marzo"];
				 break;
			     case 4:
			          $ad_monto_programado=$ad_monto_programado+$row["abril"];
				 break;
			     case 5:
			          $ad_monto_programado=$ad_monto_programado+$row["mayo"];
				 break;
			     case 6:
			          $ad_monto_programado=$ad_monto_programado+$row["junio"];
				 break;
			     case 7:
			          $ad_monto_programado=$ad_monto_programado+$row["julio"];
				 break;
			     case 8:
			          $ad_monto_programado=$ad_monto_programado+$row["agosto"];
				 break;
			     case 9:
			          $ad_monto_programado=$ad_monto_programado+$row["septiembre"];
				 break;
			     case 10:
			          $ad_monto_programado=$ad_monto_programado+$row["octubre"];
				 break;
			     case 11:
			          $ad_monto_programado=$ad_monto_programado+$row["noviembre"];
				 break;
			     case 12:
			          $ad_monto_programado=$ad_monto_programado+$row["diciembre"];
				 break;
			 }//switch
		   }//for
		   for($i=1;$i<=$li_meshas;$i++)
		   {
		     switch ($i)
			 {
			     case 1:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["enero"];
				 break;
			     case 2:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["febrero"];
				 break;
			     case 3:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["marzo"];
				 break;
			     case 4:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["abril"];
				 break;
			     case 5:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["mayo"];
				 break;
			     case 6:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["junio"];
				 break;
			     case 7:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["julio"];
				 break;
			     case 8:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["agosto"];
				 break;
			     case 9:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["septiembre"];
				 break;
			     case 10:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["octubre"];
				 break;
			     case 11:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["noviembre"];
				 break;
			     case 12:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["diciembre"];
				 break;
			 }//switch
		   }//for	
		  
		 //   
		}//if
	  }//while
      $this->SQL->free_result($rs_prog);
     }//else
	 return $lb_valido;
   }//fin uf_spg_reporte_calcular_programado_r
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_programado_trimestre_anterior($as_clave,$ad_asignado,$as_spg_cuenta,$as_estructura_desde,
	                                                               $as_estructura_hasta,$ai_mesdes,$ai_meshas,&$ad_monto_ejecutado,
																   $as_tipo,$ad_enero,$ad_febrero,$ad_marzo,$ad_abril,$ad_mayo,
																   $ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,$ad_octubre,
																   $ad_noviembre,$ad_diciembre,$as_codrep,$as_codfuefindes,
																   $as_codfuefinhas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_programado_trimestre_anterior
	 //         Access :	private
	 //     Argumentos :    $as_codrep  // codigo del reporte
	 //                     $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado (referencia)  
	 //                     $as_clave  // clave
	 //                     $as_tipo  //  tipo
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    01/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $dt_mesdes=substr($ai_mesdes,5,2);
     $dt_meshas=substr($ai_meshas,5,2);
	 $dt_mesdes=intval($dt_mesdes);
     $dt_meshas=intval($dt_meshas);
	 $li_mesdes=($dt_mesdes-1);
     $li_meshas=($dt_meshas-1);
	 $ld_monto_programado=0;
	 $ld_monto_acumulado=0;
	 $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_monto_acumulado,
	                                                      $as_estructura_desde,$as_estructura_hasta,$as_codrep,$as_spg_cuenta,
														  $as_codfuefindes,$as_codfuefinhas);
	 $ld_monto_ejecutado=0;
	 $ld_monto_acumulado=0;     $ld_aumdismes=0;
	 $ld_aumdisacum=0;          $ld_comprometer=0;
	 $ld_causado=0;       	    $ld_pagado=0;
	 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_trimestre($as_clave,$as_spg_cuenta,$as_estructura_desde,$as_estructura_hasta,
																   $ai_mesdes,$ai_meshas,$ld_monto_ejecutado,$ld_monto_acumulado,
																   $ld_aumdismes,$ld_aumdisacum,$ld_comprometer,$ld_causado,
																   $ld_pagado,$as_tipo);
     if($lb_valido)
	 {
	   if($as_clave==1)
	   {
	      $ad_monto_ejecutado=$ld_monto_programado;
	   }//if
	   else
	   {
	      $ad_monto_ejecutado=($ad_asignado+$ld_aumdismes+$ld_aumdisacum-$ld_comprometer);
	   }//else
	 }//if
    return  $lb_valido;
   }//fin uf_spg_reporte_calcular_programado_trimestre_anterior
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_programado($ai_mesdes,$ai_meshas,&$ad_monto_programado,&$ad_monto_acumulado,
	                                            $as_estructura_desde,$as_estructura_hasta,$as_codrep,$as_spg_cuenta,
												$as_codfuefindes,$as_codfuefinhas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_programado
	 //         Access :	private
	 //     Argumentos :    $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
	 //                     $ad_monto_acumulado // monto programado del acumulado (referencia)  
	 //                     $ad_enero .. $ad_diciembre  // monto programado desde  enero  hasta diciembre  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	metodo que calcula los montos programados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    01/06/2006          Fecha última Modificacion : 26/06/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	 
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $li_mesdes=$ai_mesdes;
      $li_meshas=$ai_mesdes;
	  if($as_codrep=="0415")
	  {
		  if (strtoupper($ls_gestor)=="MYSQL")
		  {
			   $ls_cadena="CONCAT(C.codestpro1,C.codestpro2,C.estcla)";
		  }
		  else
		  {
			   $ls_cadena="C.codestpro1||C.codestpro2||C.estcla";
		  }
	  }
	  else
	  {
		  if (strtoupper($ls_gestor)=="MYSQL")
		  {
			   $ls_cadena="CONCAT(C.codestpro1,C.codestpro2,C.codestpro3,C.codestpro4,C.codestpro5,C.estcla)";
		  }
		  else
		  {
			   $ls_cadena="C.codestpro1||C.codestpro2||C.codestpro3||C.codestpro4||C.codestpro5||C.estcla";
		  }
	  }
	  $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	  if($li_estmodest==1)
	  {
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND C.estcla=EP.estcla";
	  }
	  elseif($li_estmodest==2)
	  {
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND ".
							"	  C.codestpro4=EP.codestpro4 AND C.codestpro5=EP.codestpro5 AND C.estcla=EP.estcla";
	  }
	  $ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignadoaux) as asignado, ".
			  "        sum(comprometidoaux) as comprometido, sum(causadoaux) as causado, sum(pagadoaux) as pagado, ".
			  "        sum(aumentoaux) as aumento, sum(disminucionaux) as disminucion, sum(eneroaux) as enero, ".
			  "        sum(febreroaux) as febrero, sum(marzoaux) as marzo, sum(abrilaux) as abril, sum(mayoaux) as mayo, ".
			  "        sum(junioaux) as junio, sum(julioaux) as julio, sum(agostoaux) as agosto, sum(septiembreaux) as septiembre, ".
			  "        sum(octubreaux) as octubre, sum(noviembreaux) as noviembre, sum(diciembreaux) as diciembre ".
			  " FROM   spg_plantillareporte C, ".$ls_tabla." EP  ".
			  " WHERE  C.codemp='".$ls_codemp."' AND ".$ls_cadena." BETWEEN '".$as_estructura_desde."' AND '".$as_estructura_hasta."' AND ".
			  "        C.codrep='".$as_codrep."' AND  C.spg_cuenta='".$as_spg_cuenta."' AND ".
			  "        EP.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."'  ".$ls_cadena_fuefin." ".
			  " GROUP BY C.spg_cuenta ".
			  " ORDER BY C.spg_cuenta ";
	  /*$ls_sql=" SELECT spg_cuenta, max(denominacion) as denominacion, sum(asignadoaux) as asignado, ".
			  "        sum(comprometidoaux) as comprometido, sum(causadoaux) as causado, sum(pagadoaux) as pagado, ".
			  "        sum(aumentoaux) as aumento, sum(disminucionaux) as disminucion, sum(eneroaux) as enero, ".
			  "        sum(febreroaux) as febrero, sum(marzoaux) as marzo, sum(abrilaux) as abril, sum(mayoaux) as mayo, ".
			  "        sum(junioaux) as junio, sum(julioaux) as julio, sum(agostoaux) as agosto, sum(septiembreaux) as septiembre, ".
			  "        sum(octubreaux) as octubre, sum(noviembreaux) as noviembre, sum(diciembreaux) as diciembre ".
			  " FROM   spg_plantillareporte  ".
			  " WHERE  codemp='".$ls_codemp."' AND ".$ls_cadena." between '".$as_estructura_desde."' AND '".$as_estructura_hasta."' AND ".
			  "        codrep='".$as_codrep."' AND  spg_cuenta='".$as_spg_cuenta."' ".
			  " GROUP BY spg_cuenta ".
			  " ORDER BY spg_cuenta ";*/
	 $rs_prog=$this->SQL->select($ls_sql);
	 if($rs_prog===false)
	 {   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_programado_r".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_prog))
		{
		   if(!(($li_mesdes>=1)&&($li_meshas<=12)))
		   {	 	 
		     return false;
		   }
		   for($i=$li_mesdes;$i<=$li_meshas;$i++)
		   {
		     switch ($li_mesdes)
			 {
			     case 1:
			          $ad_monto_programado=$ad_monto_programado+$row["enero"];
				 break;
			     case 2:
			          $ad_monto_programado=$ad_monto_programado+$row["febrero"];
				 break;
			     case 3:
			          $ad_monto_programado=$ad_monto_programado+$row["marzo"];
				 break;
			     case 4:
			          $ad_monto_programado=$ad_monto_programado+$row["abril"];
				 break;
			     case 5:
			          $ad_monto_programado=$ad_monto_programado+$row["mayo"];
				 break;
			     case 6:
			          $ad_monto_programado=$ad_monto_programado+$row["junio"];
				 break;
			     case 7:
			          $ad_monto_programado=$ad_monto_programado+$row["julio"];
				 break;
			     case 8:
			          $ad_monto_programado=$ad_monto_programado+$row["agosto"];
				 break;
			     case 9:
			          $ad_monto_programado=$ad_monto_programado+$row["septiembre"];
				 break;
			     case 10:
			          $ad_monto_programado=$ad_monto_programado+$row["octubre"];
				 break;
			     case 11:
			          $ad_monto_programado=$ad_monto_programado+$row["noviembre"];
				 break;
			     case 12:
			          $ad_monto_programado=$ad_monto_programado+$row["diciembre"];
				 break;
			 }//switch
		   }//for
		   for($i=1;$i<=$li_meshas;$i++)
		   {
		     switch ($i)
			 {
			     case 1:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["enero"];
				 break;
			     case 2:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["febrero"];
				 break;
			     case 3:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["marzo"];
				 break;
			     case 4:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["abril"];
				 break;
			     case 5:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["mayo"];
				 break;
			     case 6:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["junio"];
				 break;
			     case 7:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["julio"];
				 break;
			     case 8:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["agosto"];
				 break;
			     case 9:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["septiembre"];
				 break;
			     case 10:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["octubre"];
				 break;
			     case 11:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["noviembre"];
				 break;
			     case 12:
			          $ad_monto_acumulado=$ad_monto_acumulado+$row["diciembre"];
				 break;
			 }//switch
		   }//for		   
		}//if
	    $this->SQL->free_result($rs_prog);
     }//else
	 return $lb_valido;
   }//fin uf_spg_reporte_calcular_programado
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado_trimestre($as_clave,$as_spg_cuenta,$as_estructura_desde,$as_estructura_hasta,$ai_mesdes,
	                                                     $ai_meshas,&$ad_monto_ejecutado,&$ad_monto_acumulado,&$ad_aumdismes,
														 &$ad_aumdisacum,&$ad_comprometer,&$ad_causado,&$ad_pagado,$as_tipo)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado_trimestre
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado (referencia)  
	 //                     $as_clave  // clave
	 //                     $as_tipo  //  tipo
	 //                     $ad_monto_acumulado // monto acumulado (referencia) 
	 //                     $ad_aumdismes // monto aumneto y disminuciones del mes (referencia)  
	 //                     $ad_aumdisacum // monto aumneto y disminuciones del mes acumulado (referencia)  
	 //                     $ad_comprometer // monto comprometer (referencia)  
	 //                     $ad_causado // monto causado (referencia)  
	 //                     $ad_pagado // monto pagado (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para  la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    01/06/2006          Fecha última Modificacion :      Hora :
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
	  $ls_gestor = $_SESSION["ls_gestor"];
	  if (strtoupper($ls_gestor)=="MYSQL")
	  {
		   $ls_cadena="CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)";
	  }
	  else
	  {
		   $ls_cadena="codestpro1||codestpro2||codestpro3||codestpro4||codestpro5";
	  }
	  $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.montoaux as monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
              " FROM   spg_dt_cmp DT, spg_operaciones OP ".
              " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
              "        ".$ls_cadena." between  '".$as_estructura_desde."' AND '".$as_estructura_hasta."'  AND ".
              "        spg_cuenta like '".$as_spg_cuenta."' ";
	  $rs_ejec=$this->SQL->select($ls_sql);
	  if($rs_ejec===false)
	  { // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_ejecutado_trimestre".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->SQL->fetch_row($rs_ejec))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_precomprometer=$row["precomprometer"];
		  $li_comprometer=$row["comprometer"];
		  $li_causar=$row["causar"];
		  $li_pagar=$row["pagar"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,7);
		  if($as_tipo=="O")
		  {
		    if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			{ 
			 $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_comprometer)&&($ldt_fecha<=$li_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
		  }//if($as_tipo=="O")
		  if($as_tipo=="C")
		  {
		    if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			{ 
			  $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_causar)&&($ldt_fecha<=$li_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
		  }//if($as_tipo=="C")
		  
		  //  Comprometer, Causar, Pagar, Aumento, Disminución
		  if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_comprometer=$ad_comprometer+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_causado=$ad_causado+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_causado=$ad_causado+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagado=$ad_pagado+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagado=$ad_pagado+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_aumento=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_disminucion=$ld_disminucion+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha<=$li_meshas))
		  {  
			  $ld_aumento_acum=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha<=$li_meshas))
		  {  
			  $ld_disminucion_acum=$ld_disminucion+$ld_monto;
		  }//if
		}//while
		$ad_aumdismes=$ld_aumento-$ld_disminucion;
		$ad_aumdisacum=$ld_aumento_acum-$ld_disminucion_acum;
	   $this->SQL->free_result($rs_ejec);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado_trimestre
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado_trimestre_act($as_spg_cuenta,$as_estructura_desde,$as_estructura_hasta,$ai_mesdes,
	                                                         $ai_meshas,&$ad_monto_ejecutado,&$ad_monto_acumulado,&$ad_aumdismes,
														     &$ad_aumdisacum,&$ad_comprometer,&$ad_causado,&$ad_pagado,
															 &$ad_comprometer_acumulado,&$ad_causado_acumulado,
															 &$ad_pagado_acumulado,$as_tipo,&$ld_ejec_t_ant,$as_codfuefindes,
															 $as_codfuefinhas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado_trimestre_act
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado (referencia)  
	 //                     $as_tipo  //  tipo
	 //                     $ad_ejec_acum_t_ant  // ejecutado acumulado del trimetre anterior
	 //                     $ad_monto_acumulado // monto acumulado (referencia) 
	 //                     $ad_aumdismes // monto aumneto y disminuciones del mes (referencia)  
	 //                     $ad_aumdisacum // monto aumneto y disminuciones del mes acumulado (referencia)  
	 //                     $ad_comprometer // monto comprometer (referencia)  
	 //                     $ad_causado // monto causado (referencia)  
	 //                     $ad_pagado // monto pagado (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ld_aumento=0;
	  $ld_disminucion=0;
	  $ld_aumento_acum=0;
	  $ld_disminucion_acum=0;
	  $ad_monto_ejecutado=0;
	  $ad_monto_acumulado=0;
	  $ad_comprometer=0;
	  $ad_causado=0;
	  $ad_pagado=0;
	  $ad_comprometer_acumulado=0;
	  $ad_causado_acumulado=0;
	  $ad_pagado_acumulado=0;
	  
	  $ld_ejec_t_ant=0;
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $li_mesdes=$ai_mesdes;
	  $li_meshas=$ai_meshas;
	  if (strtoupper($ls_gestor)=="MYSQL")
	  {
		   $ls_cadena="CONCAT(DT.codestpro1,DT.codestpro2,DT.codestpro3,DT.codestpro4,DT.codestpro5,DT.estcla)";
	  }
	  else
	  {
		   $ls_cadena="DT.codestpro1||DT.codestpro2||DT.codestpro3||DT.codestpro4||DT.codestpro5||DT.estcla";
	  }
	  $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	  if($li_estmodest==1)
	  {
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND DT.codestpro1=EP.codestpro1 AND DT.codestpro2=EP.codestpro2 AND DT.codestpro3=EP.codestpro3 AND DT.estcla=EP.estcla";
	  }
	  elseif($li_estmodest==2)
	  {
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND DT.codestpro1=EP.codestpro1 AND DT.codestpro2=EP.codestpro2 AND DT.codestpro3=EP.codestpro3 AND ".
							"	  DT.codestpro4=EP.codestpro4 AND DT.codestpro5=EP.codestpro5 AND DT.estcla=EP.estcla";
	  }
	  $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.montoaux as monto, OP.aumento, OP.disminucion, OP.precomprometer, ". 
			  "		   OP.comprometer, OP.causar, OP.pagar ".
			  "	FROM   spg_dt_cmp DT, spg_operaciones OP , ".$ls_tabla." EP ".
			  "	WHERE  DT.codemp='".$ls_codemp."' AND  ".
			  "		   ".$ls_cadena."  BETWEEN '".$as_estructura_desde."' AND '".$as_estructura_hasta."' AND ".
			  "		   spg_cuenta like '".$as_spg_cuenta."' AND     ".
			  "		   EP.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' AND  ".  
			  "		   DT.codemp=EP.codemp AND DT.operacion = OP.operacion   ".$ls_cadena_fuefin."  ";
	  
	 /*$ls_sql=" SELECT DT.fecha, DT.montoaux as monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
			  " FROM   spg_dt_cmp DT, spg_operaciones OP ".
			  " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
			  "        ".$ls_cadena." between  '".$as_estructura_desde."' AND '".$as_estructura_hasta."'  AND ".
			  "        spg_cuenta like '".$as_spg_cuenta."' ";*/
	
	  $rs_ejec=$this->SQL->select($ls_sql);
	  if($rs_ejec===false)
	  { // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_ejecutado_trimestre".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->SQL->fetch_row($rs_ejec))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_precomprometer=$row["precomprometer"];
		  $li_comprometer=$row["comprometer"];
		  $li_causar=$row["causar"];
		  $li_pagar=$row["pagar"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,10);
		  
		
		  if($as_tipo=="O")
		  {
		    if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			{ 
			 $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_comprometer)&&($ldt_fecha<=$li_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
			if(($li_comprometer)&&($ldt_fecha<$li_mesdes))
			{  
			  $ld_ejec_t_ant=$ld_ejec_t_ant+$ld_monto;
			}//if
		  }//if($as_tipo=="O")
		  if($as_tipo=="C")
		  { 
		    
		    if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			{ 
			  $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_causar)&&($ldt_fecha<=$li_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
		  }//if($as_tipo=="C")
		  
		  //  Comprometer, Causar, Pagar ---> ejecutado del trimestre
		  if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_comprometer=$ad_comprometer+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_causado=$ad_causado+$ld_monto;
			
			
		  }//if
		  if(($li_pagar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagado=$ad_pagado+$ld_monto;
			
		  }//if
		  
		  //  Comprometer, Causar, Pagar ---> acumulado 
		  if(($li_comprometer)&&($ldt_fecha<=$li_meshas))
		  { 
		 
			$ad_comprometer_acumulado=$ad_comprometer_acumulado+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_causado_acumulado=$ad_causado_acumulado+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagado_acumulado=$ad_pagado_acumulado+$ld_monto;
		  }//if
		 
		  //   Aumento, Disminución
		  if(($li_aumento)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_aumento=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_disminucion=$ld_disminucion+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha<=$li_meshas))
		  {  
			  $ld_aumento_acum=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha<=$li_meshas))
		  {  
			  $ld_disminucion_acum=$ld_disminucion+$ld_monto;
		  }//if
		}//while
		$ad_aumdismes=$ld_aumento-$ld_disminucion;
		$ad_aumdisacum=$ld_aumento_acum-$ld_disminucion_acum;
	   $this->SQL->free_result($rs_ejec);
	  }//else	
	 
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado_trim_actual
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_denestpro1=$row["denestpro1"];
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro2 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_denestpro2=$row["denestpro2"];
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro3 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_denestpro3=$row["denestpro3"];
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
    function uf_spg_reportes_select_ejecucion_financiera_forma0402($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,
	                                            				  $as_codestpro4_ori,$as_codestpro5_ori,$as_codestpro1_des,
					                                              $as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
											  					  $as_codestpro5_des,$as_codfuefindes,$as_codfuefinhas,
																  $as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_select_ejecucion_financiera_forma0402
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3 // codigo
	 //                     $as_denestpro3  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 1
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    07/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori.$as_estclades;
	 $ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des.$as_estclahas;	
	 $ls_gestor = $_SESSION["ls_gestor"];
	 if (strtoupper($ls_gestor)=="MYSQL")
	 {
		   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5,c.estcla)";
	 }
	 else
	 {
		   $ls_cadena="c.codestpro1||c.codestpro2||c.codestpro3||c.codestpro4||c.codestpro5||c.estcla";
	 }
	 $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	 if($li_estmodest==1)
	 {
	   $ls_tabla="spg_ep3"; 
	   $ls_cadena_fuefin="AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND C.estcla=EP.estcla";
	 }
	 elseif($li_estmodest==2)
	 {
	   $ls_tabla="spg_ep5";
	   $ls_cadena_fuefin=" AND C.codestpro1=EP.codestpro1 AND C.codestpro2=EP.codestpro2 AND C.codestpro3=EP.codestpro3 AND ".
						 "	   C.codestpro4=EP.codestpro4 AND C.codestpro5=EP.codestpro5 AND C.estcla=EP.estcla";
	 }
	 $ls_sql=" SELECT distinct ".$ls_cadena." as programatica    ".
             " FROM  spg_cuentas C, spg_plantillareporte R, ".$ls_tabla." EP ".
			 " WHERE C.codemp='".$ls_codemp."' AND EP.codfuefin BETWEEN '".$as_codfuefindes."' AND '".$as_codfuefinhas."' AND ".
			 "       ".$ls_cadena."  between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' AND ".
			 "       C.codemp=R.codemp  AND  C.codestpro1=R.codestpro1 AND C.codestpro2=R.codestpro2 AND   ".
			 "       C.codestpro3=R.codestpro3 AND C.codestpro4=R.codestpro4 AND C.codestpro5=R.codestpro5 ".
			 "       ".$ls_cadena_fuefin."  ";
	
	/* $ls_sql=" SELECT distinct ".$ls_cadena." as programatica ".
             " FROM  spg_cuentas c, spg_plantillareporte r".
			 " WHERE c.codemp=r.codemp AND r.codemp='".$ls_codemp."' AND  c.codestpro1=r.codestpro1 AND c.codestpro2=r.codestpro2 AND ".
			 "       c.codestpro3=r.codestpro3 AND c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5 AND ".
			 "       ".$ls_cadena."  between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ";*/
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reportes_select_ejecucion_financiera_forma0402".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
 	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		  $datos=$this->SQL->obtener_datos($rs_data);
		  $this->dts_cab->data=$datos;
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	}//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro1
/****************************************************************************************************************************************/
function uf_nombre_mes_desde_hasta($ai_mesdes,$ai_meshas)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function: 	  uf_load_nombre_mes
	//	Description:  Funcion que se encarga de obtener el numero de un mes a partir de su nombre.
	//	Arguments:	  - $ls_mes: Mes de la fecha a obtener el ultimo dia.	
	//				  - $ls_ano: Año de la fecha a obtener el ultimo dia.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_nombre_mesdes=$this->io_fecha->uf_load_nombre_mes($ai_mesdes);
    $ls_nombre_meshas=$this->io_fecha->uf_load_nombre_mes($ai_meshas);
	$ls_nombremes=$ls_nombre_mesdes."-".$ls_nombre_meshas;
    return $ls_nombremes;
 }
/****************************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " EJECUCIÓN FINANCIERA DE LOS PROYECTOS DEL ENTE FORMA  #0413 " //
	/////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_comparados_forma0413_proyectos($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                                        $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
															$as_codestpro4_des,$as_codestpro5_des,$ad_fecdes,$ad_fechas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_forma0413_proyectos
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1_ori.. $as_codestpro5_ori // codigo de la estructura programatica origen
	 //                     $as_codestpro1_des.. $as_codestpro5_des // codigo de la estructura programatica destino
	 //                     $ad_fecdes  //  fecha desde 
	 //                     $ad_fechas  //  fecha hasta        
     //	       Returns :	Retorna un datastore cargado con los datos  para el reporte 
	 //	   Description :	Reporte que genera salida  del comparados del instructivo 04 de la forma 0413
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    16/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=$this->uf_spg_reporte_select_proyecto($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
											         $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
											         $as_codestpro4_des,$as_codestpro5_des,"P","0413");
	if($lb_valido)
	{
	 $lb_valido = true;	 
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $this->dts_reporte->resetds("spg_cuenta");
	 $li_total=$this->dts_prog->getRowCount("programatica");
	 for($z=1;$z<=$li_total;$z++)
	 {
		$ls_programatica=$this->dts_prog->data["programatica"][$z];
		$ls_estcla=substr($ls_programatica,-1);
		$ls_codestpro1_ori=substr($ls_programatica,0,25);
		$ls_codestpro2_ori=substr($ls_programatica,25,25);
		$ls_codestpro3_ori=substr($ls_programatica,50,25);
		$ls_codestpro4_ori=substr($ls_programatica,75,25);
		$ls_codestpro5_ori=substr($ls_programatica,75,25);
		$ls_estructura_desde=$ls_codestpro1_ori.$ls_codestpro2_ori.$ls_codestpro3_ori.$ls_codestpro4_ori.$ls_codestpro5_ori.$ls_estcla;
		$ls_estructura_hasta=$ls_codestpro1_ori.$ls_codestpro2_ori.$ls_codestpro3_ori.$ls_codestpro4_ori.$ls_codestpro5_ori.$ls_estcla; 
		$li_mesdes=intval(substr($ad_fecdes,5,2));
		$li_meshas=intval(substr($ad_fechas,5,2));
		$ls_tipo="O";
	    if (strtoupper($ls_gestor)=="MYSQL")
	    {
		   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5,c.estcla)";
	    }
	    else
	    {
		   $ls_cadena="c.codestpro1||c.codestpro2||c.codestpro3||c.codestpro4||c.codestpro5||c.estcla";
	    }
		$ls_estcla="P";
		$ls_sql=" SELECT c.spg_cuenta, max(c.nivel) as nivel, max(c.denominacion) as denominacion, sum(c.asignadoaux) as asignado, ".
                "        sum(c.comprometidoaux) as comprometido, sum(c.causadoaux) as causado, sum(c.pagadoaux) as pagado, ".
                "        sum(c.aumentoaux) as aumento, sum(c.disminucionaux) as disminucion, sum(c.eneroaux) as enero, ".
                "        sum(c.febreroaux) as febrero, sum(c.marzoaux) as marzo, sum(c.abrilaux) as abril, sum(c.mayoaux) as mayo, ".
                "        sum(c.junioaux) as junio, sum(c.julioaux) as julio, sum(c.agostoaux) as agosto, sum(c.septiembreaux) as septiembre, ".
                "        sum(c.octubreaux) as octubre, sum(c.noviembreaux) as noviembre, sum(c.diciembreaux) as diciembre, ".
                "        c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5,c.estcla ".
                " FROM   spg_cuentas c, spg_plantillareporte r ".
                " WHERE  c.codemp=r.codemp AND c.codestpro1=r.codestpro1 AND ".
                "        c.codestpro2=r.codestpro2 AND ".
                "        c.codestpro3=r.codestpro3 AND c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5 AND ".
                "        c.spg_cuenta = r.spg_cuenta AND r.status <> 'I'  AND codrep='0413' AND ".
                "        ".$ls_cadena."    between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ".              
			    " GROUP BY c.spg_cuenta, c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5, c.estcla ".
                " ORDER BY c.spg_cuenta";
		/*$ls_sql=" SELECT c.spg_cuenta, max(c.nivel) as nivel, max(c.denominacion) as denominacion, sum(c.asignadoaux) as asignado, ".
                "        sum(c.comprometidoaux) as comprometido, sum(c.causadoaux) as causado, sum(c.pagadoaux) as pagado, ".
                "        sum(c.aumentoaux) as aumento, sum(c.disminucionaux) as disminucion, sum(c.eneroaux) as enero, ".
                "        sum(c.febreroaux) as febrero, sum(c.marzoaux) as marzo, sum(c.abrilaux) as abril, sum(c.mayoaux) as mayo, ".
                "        sum(c.junioaux) as junio, sum(c.julioaux) as julio, sum(c.agostoaux) as agosto, sum(c.septiembreaux) as septiembre, ".
                "        sum(c.octubreaux) as octubre, sum(c.noviembreaux) as noviembre, sum(c.diciembreaux) as diciembre, ".
                "        c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5 ".
                " FROM   spg_cuentas c, spg_plantillareporte r ".
                " WHERE  c.codemp=r.codemp AND (c.codestpro1=r.codestpro1 AND ".
                "        c.codestpro2=r.codestpro2 AND ".
                "        c.codestpro3=r.codestpro3 AND c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5) AND ".
                "        (c.spg_cuenta = r.spg_cuenta) AND (r.status <> 'I')  AND codrep='0413' AND ".
                "        ".$ls_cadena."    between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ".              
			    " GROUP BY c.spg_cuenta, c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5 ".
                " ORDER BY c.spg_cuenta";*/
        $rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reportes_comparados_forma0705_proyectos".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{	 
			 $ld_asignado_total=0;
			 $ld_causado_total=0;
			 $ld_comprometer_total=0;
			 $ld_pagado_total=0;
			 $ld_monto_programado_total=0;
			 $ld_comprometer_acumulado_total=0;	
			 $ld_monto_programado_acum_total=0;	
			 $ld_pagado_acumulado_total=0;
			 $ld_causado_acumulado_total=0;
			 $ld_aumdismes_total=0;	
			 $ld_aumdisacum_total=0;	
			 $ld_monto_ejecutado_total=0;	
			 $ld_monto_acumulado_total=0;	
			 $ld_reprog_prox_mes_total=0;	
			 $ld_comprometer_total=0;	
			 $ld_causado_total=0;	
			 $ld_pagado_total=0;	
			 $ld_monto_progracum_ant_total=0;	
			 $ld_monto_programado_ant_total=0;	
			 $ld_monto_programado_ant_total=0;	
			 while($row=$this->SQL->fetch_row($rs_data))
			 {    
				   $ls_spg_cuenta=$row["spg_cuenta"];
				   $ls_denominacion=$row["denominacion"];
				   $li_nivel=$row["nivel"];
				   $ld_asignado=$row["asignado"];
				   $ld_comprometido=$row["comprometido"];
				   $ld_causado=$row["causado"];
				   $ld_pagado=$row["pagado"];
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
				   $ls_codestpro1=$row["codestpro1"]; 
				   $ls_codestpro2=$row["codestpro2"]; 
				   $ls_codestpro3=$row["codestpro3"]; 
				   $ls_codestpro4=$row["codestpro4"]; 
				   $ls_codestpro5=$row["codestpro5"];
				   $ls_estcla=$row["estcla"];
			       $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5.$ls_estcla;
				   $ls_codfuefindes='--';
				   $ls_codfuefinhas='--';
				   if($li_nivel==1)
				   {	
                     //calculo lo programdo y lo acumulado del mes 
					 $ls_codrep="0413";
					 $ld_monto_programado=0;
					 $ld_monto_programado_acum=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_programado_r($ls_codrep,$ls_spg_cuenta,$ls_estructura_desde,
					                                                        $ls_estructura_hasta,$ad_fecdes,$ad_fechas,
	                                                                        $ld_monto_programado,$ld_monto_programado_acum,
																			$ls_codfuefindes,$ls_codfuefinhas);
					  
					 if($lb_valido)
					  {//calculo el reprogramción del proximo mes 
					    $ld_reprog_prox_mes=0; 
					    $ld_monto_progracum_ant=0;
					    $ld_monto_programado_ant=0;
					    //$ld_monto_programado=0;
					    $ld_monto_ejecutado=0;
					    $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes+1,$li_meshas+1,$ld_reprog_prox_mes,
																			$ld_monto_acumulado,$ls_estructura_desde,
																			$ls_estructura_hasta,$ls_codrep,$ls_spg_cuenta,
																			$ls_codfuefindes,$ls_codfuefinhas);
						  if($lb_valido)
						  {		
							  $ld_aumdis_t_ant=0;
							  $ld_prog_t_ant=0;  
							  $ld_compr_t_ant=0;
							  $ld_disponible_fecha=0;
							  $ld_ejec_acum_t_ant=0;
							  $ld_comprometer=0;
							  $ld_causado=0;
							  $ld_pagado=0;
							  $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_trimestre_act($ls_spg_cuenta,$ls_estructura_desde,$ls_estructura_hasta,
																								$ad_fecdes,$ad_fechas,$ld_monto_ejecutado,
																								$ld_ejecutado_acumulado,$ld_aumdismes,$ld_aumdisacum,
																								$ld_comprometer,$ld_causar,$ld_pagar,
																								$ld_comprometer_acumulado,
																								$ld_causado_acumulado,
																								$ld_pagado_acumulado,$ls_tipo,
																								$ld_ejec_acum_t_ant,$ls_codfuefindes,
																								$ls_codfuefinhas);
						  }//if lb_valido
				    }//if lb_valido
			     }//if lb_ok
		    }//while 
			$ld_asignado_total=$ld_asignado_total+$ld_asignado;
			$ld_monto_ejecutado_total=$ld_monto_ejecutado_total+$ld_monto_ejecutado;
			$ld_monto_acumulado_total=$ld_monto_acumulado_total+$ld_monto_acumulado;
			$ld_comprometer_total=$ld_comprometer_total+$ld_comprometer;
			$ld_causado_total=$ld_causado_total+$ld_causar;
			$ld_pagado_total=$ld_pagado_total+$ld_pagar;
			$ld_monto_programado_acum_total=$ld_monto_programado_acum_total+$ld_monto_programado_acum;
			$ld_comprometer_acumulado_total=$ld_comprometer_acumulado_total+$ld_comprometer_acumulado;
			$ld_causado_acumulado_total=$ld_causado_acumulado_total+$ld_causado_acumulado;
			$ld_pagado_acumulado_total=$ld_pagado_acumulado_total+$ld_pagado_acumulado;
			$ld_monto_programado_total=$ld_monto_programado_total+$ld_monto_programado;
			$ld_reprog_prox_mes_total=$ld_reprog_prox_mes_total+$ld_reprog_prox_mes;
		
			$this->dts_reporte->insertRow("spg_cuenta",$ls_estructura_desde);
			$this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			$this->dts_reporte->insertRow("asignado",$ld_asignado_total);
			$this->dts_reporte->insertRow("monto_programado",$ld_monto_programado_total);
			$this->dts_reporte->insertRow("monto_acumulado",$ld_monto_programado_acum_total);
			$this->dts_reporte->insertRow("aumdis_mes",$ld_aumdismes);
			$this->dts_reporte->insertRow("aumdis_acumulado",$ld_aumdisacum);
			$this->dts_reporte->insertRow("ejecutado_mes",$ld_monto_ejecutado_total);
			$this->dts_reporte->insertRow("ejecutado_acum",$ld_monto_acumulado_total);
			$this->dts_reporte->insertRow("reprog_prox_mes",$ld_reprog_prox_mes_total);
			$this->dts_reporte->insertRow("programatica",$ls_programatica);
			$this->dts_reporte->insertRow("compromiso",$ld_comprometer_total);
			$this->dts_reporte->insertRow("causado",$ld_causado_total);					 
			$this->dts_reporte->insertRow("pagado",$ld_pagado_total);
			$this->dts_reporte->insertRow("compromiso_acumulado",$ld_comprometer_acumulado_total);
			$this->dts_reporte->insertRow("causado_acumulado",$ld_causado_acumulado_total);					 
			$this->dts_reporte->insertRow("pagado_acumulado",$ld_pagado_acumulado_total);
			$lb_valido=true; 
		   $this->SQL->free_result($rs_data);	
		}//else
	  }//for
	 }//if	
     return $lb_valido;
    }//fin uf_spg_reportes_comparados_forma0705_proyectos
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado_meses_anteriores($as_spg_cuenta,$ai_mesdes,$ai_meshas,&$ad_monto_ejecutado,
	                                                            &$ad_monto_acumulado,&$ad_aumdismes,&$ad_aumdisacum,&$ad_comprometer,
														        &$ad_causado,&$ad_pagado,$as_tipo,$ab_pasar)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado_meses_anteriores
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado (referencia)  
	 //                     $as_tipo  //  tipo
	 //                     $ad_ejec_acum_t_ant  // ejecutado acumulado del trimetre anterior
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para el Formato 3 de la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    02/06/2006          Fecha última Modificacion :26/06/2006      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ld_aumento=0;
	  $ld_disminucion=0;
	  $ld_aumento_acum=0;
	  $ld_disminucion_acum=0;
	  $ad_monto_ejecutado=0;
	  $ad_monto_acumulado=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $li_mesdes=$ai_mesdes;
	  $li_meshas=$ai_meshas;
	  $ldt_meshas=substr($ai_meshas,5,2);
	  $ldt_meshas=intval($ldt_meshas);
	  if($li_mesdes>1)
	  {
			$li_mesantdes=$ai_mesdes;
			$li_mesanthas=$li_ano."-".$this->fun->uf_cerosizquierda(($ldt_meshas-1),2);
	  }
	  else
	  {
			$li_mesantdes=$ai_mesdes;
			$li_mesanthas=$ai_meshas;
	  }	  
	  
	  $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.montoaux as monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
			  " FROM   spg_dt_cmp DT, spg_operaciones OP ".
			  " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
			  "        spg_cuenta like '".$as_spg_cuenta."' ";
	  $rs_ejec=$this->SQL->select($ls_sql);
	  if($rs_ejec===false)
	  { // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_ejecutado_meses_anteriores".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->SQL->fetch_row($rs_ejec))
		{
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_precomprometer=$row["precomprometer"];
		  $li_comprometer=$row["comprometer"];
		  $li_causar=$row["causar"];
		  $li_pagar=$row["pagar"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,10);
		  if($as_tipo=="O")
		  {
		    if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			{ 
			 $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_comprometer)&&($ldt_fecha<=$li_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
		  }//if($as_tipo=="O")
		  if($as_tipo=="C")
		  {
		    if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			{ 
			  $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_causar)&&($ldt_fecha<=$li_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
		  }//if($as_tipo=="C")
		  
		  //  Comprometer, Causar, Pagar, Aumento, Disminución
		   if($ab_pasar)
		   {
			  if(($li_comprometer)&&($ldt_fecha>=$li_mesantdes)&&($ldt_fecha<=$li_mesanthas))
			  { 
				$ad_comprometer=$ad_comprometer+$ld_monto;
			  }//if
		  }  
		  else
		  {
			  if(($li_comprometer)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
			  { 
				$ad_comprometer=$ad_comprometer+$ld_monto;
			  }//if
		  }	  
		  if(($li_causar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_causado=$ad_causado+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ad_pagado=$ad_pagado+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_aumento=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha>=$li_mesdes)&&($ldt_fecha<=$li_meshas))
		  { 
		    $ld_disminucion=$ld_disminucion+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha<=$li_meshas))
		  {  
			  $ld_aumento_acum=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha<=$li_meshas))
		  {  
			  $ld_disminucion_acum=$ld_disminucion+$ld_monto;
		  }//if
		}//while
		$ad_aumdismes=$ld_aumento-$ld_disminucion;
		$ad_aumdisacum=$ld_aumento_acum-$ld_disminucion_acum;
	   $this->SQL->free_result($rs_ejec);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado_trim_actual
/****************************************************************************************************************************************/
    function uf_spg_reporte_select_proyecto( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
	                                         $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
											 $as_estcla,$as_codrep)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_apertura
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
	 //                     adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del listado de apertura  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    09/05/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $this->dts_prog->resetds("spg_cuenta");
	 $ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori.$as_estcla;
	 $ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des.$as_estcla;	 
	 if (strtoupper($ls_gestor)=="MYSQL")
	 {
	   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5,c.estcla)";
	 }
	 else
	 {
	   $ls_cadena="c.codestpro1||c.codestpro2||c.codestpro3||c.codestpro4||c.codestpro5||c.estcla";
	 }
     $ls_sql=" SELECT  distinct ".$ls_cadena." as programatica ".
             " FROM   spg_cuentas c, spg_plantillareporte r ".
             " WHERE  r.codrep='".$as_codrep."' AND  r.status <> 'I' AND ".
             "        ".$ls_cadena." between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' AND ".
			 "        c.codemp=r.codemp AND c.codestpro1=r.codestpro1 AND ".
             "        c.codestpro2=r.codestpro2 AND c.codestpro3=r.codestpro3 AND ".
             "        c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5 AND ".
			 "        c.estcla=r.estcla AND c.spg_cuenta = r.spg_cuenta  ";
			 
    /* $ls_sql=" SELECT  distinct ".$ls_cadena." as programatica ".
             " FROM   spg_cuentas c, spg_plantillareporte r ".
             " WHERE  c.codemp=r.codemp AND(c.codestpro1=r.codestpro1 AND ".
             "         c.codestpro2=r.codestpro2 AND c.codestpro3=r.codestpro3 AND ".
             "        c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5) AND (c.spg_cuenta = r.spg_cuenta) AND ".
             "        (r.status <> 'I') AND r.codrep='".$as_codrep."' AND  ".$ls_cadena." ".
             "         between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ";*/
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_select_proyecto ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 }
	else
	{
		if($row=$this->SQL->fetch_row($rs_data))
		{
		  $datos=$this->SQL->obtener_datos($rs_data);
		  $this->dts_prog->data=$datos;	
			
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);   
     }//else
	return $lb_valido;
   }//	uf_spg_reporte_select_proyecto	
/****************************************************************************************************************************************/
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " EJECUCIÓN FINANCIERA DE LAS ACCIONES CENTRALIZADAS DEL ENTE #0414 "  //
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_comparados_forma0414_acciones($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
											               $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
											               $as_codestpro4_des,$as_codestpro5_des,$ad_fecdes,$ad_fechas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_forma0414_acciones
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1_ori.. $as_codestpro5_ori // codigo de la estructura programatica origen
	 //                     $as_codestpro1_des.. $as_codestpro5_des // codigo de la estructura programatica destino
	 //                     $ad_fecdes  //  fecha desde 
	 //                     $ad_fechas  //  fecha hasta        
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del comparados del instructivo 04 de la forma 0414
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    28/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_spg_reporte_select_proyecto($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
											         $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
											         $as_codestpro4_des,$as_codestpro5_des,"A","0414");
	if($lb_valido)
	{
	
	
	 $lb_valido = true;	 
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $this->dts_cab->resetds("spg_cuenta");
	 $li_total=$this->dts_prog->getRowCount("programatica");
	 for($z=1;$z<=$li_total;$z++)
	 {
		$ls_programatica=$this->dts_prog->data["programatica"][$z];
		$ls_codestpro1_ori=substr($ls_programatica,0,20);
		$ls_codestpro2_ori=substr($ls_programatica,20,6);
		$ls_codestpro3_ori=substr($ls_programatica,26,3);
		$ls_codestpro4_ori=$as_codestpro4_ori;
		$ls_codestpro5_ori=$as_codestpro5_ori;
		$ls_estructura_desde=$ls_codestpro1_ori.$ls_codestpro2_ori.$ls_codestpro3_ori.$ls_codestpro4_ori.$ls_codestpro5_ori;
		$ls_estructura_hasta=$ls_codestpro1_ori.$ls_codestpro2_ori.$ls_codestpro3_ori.$ls_codestpro4_ori.$ls_codestpro5_ori; 
		$li_mesdes=intval(substr($ad_fecdes,0,2));
		$li_meshas=intval(substr($ad_fechas,2,2));
        $ls_tipo="O";
	    if (strtoupper($ls_gestor)=="MYSQL")
	    {
		   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5)";
	    }
	    else
	    {
		   $ls_cadena="c.codestpro1||c.codestpro2||c.codestpro3||c.codestpro4||c.codestpro5";
	    }
		$ls_estcla="A";
		$ls_sql=" SELECT c.spg_cuenta, max(c.nivel) as nivel, max(c.denominacion) as denominacion, sum(c.asignadoaux) as asignado, ".
                "        sum(c.comprometidoaux) as comprometido, sum(c.causadoaux) as causado, sum(c.pagadoaux) as pagado, ".
                "        sum(c.aumentoaux) as aumento, sum(c.disminucionaux) as disminucion, sum(c.eneroaux) as enero, ".
                "        sum(c.febreroaux) as febrero, sum(c.marzoaux) as marzo, sum(c.abrilaux) as abril, sum(c.mayoaux) as mayo, ".
                "        sum(c.junioaux) as junio, sum(c.julioaux) as julio, sum(c.agostoaux) as agosto, sum(c.septiembreaux) as septiembre, ".
                "        sum(c.octubreaux) as octubre, sum(c.noviembreaux) as noviembre, sum(c.diciembreaux) as diciembre, ".
                "        c.codestpro1,c.codestpro2,c.codestpro3,c.codestpro4,c.codestpro5, max(p.estcla) as estcla ".
                " FROM   spg_cuentas c, spg_plantillareporte r, spg_ep1 p ".
                " WHERE  c.codemp=r.codemp AND r.codemp=p.codemp AND p.codemp='".$ls_codemp."' AND (c.codestpro1=r.codestpro1 AND ".
                "        p.estcla='".$ls_estcla."' AND r.codestpro1=p.codestpro1 AND c.codestpro2=r.codestpro2 AND ".
                "        c.codestpro3=r.codestpro3 AND c.codestpro4=r.codestpro4 AND c.codestpro5=r.codestpro5) AND ".
                "        (c.spg_cuenta = r.spg_cuenta) AND (r.status <> 'I')  AND codrep='0414' AND ".
                "        ".$ls_cadena."    between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ".              
			    " GROUP BY c.spg_cuenta, c.codestpro1, c.codestpro2, c.codestpro3, c.codestpro4, c.codestpro5 ".
                " ORDER BY c.spg_cuenta";
	    $rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reportes_comparados_forma0705_acciones".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{	
		    $li_num=$this->SQL->num_rows($rs_data);	
			if($li_num==0)
			{
			    $lb_valido = false;
			}
			else
			{
				 $ld_asignado_total=0;
				 $ld_monto_programado_total=0;	
				 $ld_monto_programado_acum_total=0;	
				 $ld_aumdismes_total=0;	
				 $ld_aumdisacum_total=0;	
				 $ld_monto_ejecutado_total=0;	
				 $ld_monto_acumulado_total=0;	
				 $ld_reprog_prox_mes_total=0;	
				 $ld_comprometer_total=0;	
				 $ld_causado_total=0;	
				 $ld_pagado_total=0;	
				 $ld_monto_progracum_ant_total=0;	
				 $ld_monto_programado_ant_total=0;	
				 $ld_monto_programado_ant_total=0;	
				 while($row=$this->SQL->fetch_row($rs_data))
				 {  
					   $ls_spg_cuenta=$row["spg_cuenta"];
					   $ls_denominacion=$row["denominacion"];
					   $li_nivel=$row["nivel"];
					   $ld_asignado=$row["asignado"];
					   $ld_comprometido=$row["comprometido"];
					   $ld_causado=$row["causado"];
					   $ld_pagado=$row["pagado"];
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
					   $ls_codestpro1=$row["codestpro1"]; 
					   $ls_codestpro2=$row["codestpro2"]; 
					   $ls_codestpro3=$row["codestpro3"]; 
					   $ls_codestpro4=$row["codestpro4"]; 
					   $ls_codestpro5=$row["codestpro5"];
					   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					   $lb_ok=true;
					   if($lb_ok)
					   {	
						 //calculo lo programdo y lo acumulado del trimestre 
						 $ls_codrep="";
						 $ls_estructura_desde="";
						 $ls_estructura_hasta="";
						// $ld_monto_programado=0;
						// $ld_monto_programado_acum=0;
						 $ls_codrep="0414";
						 $lb_valido=$this->uf_spg_reporte_calcular_programado_r($ls_codrep,$ls_spg_cuenta,$ls_estructura_desde,
																				$ls_estructura_hasta,$ad_fecdes,$ad_fechas,
																				$ld_monto_programado,$ld_monto_programado_acum);
						  if($lb_valido)
						  {//calculo el reprogramción del proximo mes 
							   $ld_reprog_prox_mes=0; 
							   $ld_monto_progracum_ant=0;
							   $ld_monto_programado_ant=0;
							   //$ld_monto_programado=0;
							   $ld_monto_ejecutado=0;
							   $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes+1,$li_meshas+1,$ld_reprog_prox_mes,
																					$ld_monto_acumulado,$ls_estructura_desde,
																					$ls_estructura_hasta,$ls_codrep,$ls_spg_cuenta);	
							  if($lb_valido)
							  {						
								if($li_mesdes>1)
								{// calculo la programacion de los meses anteriores
								  $ld_monto_programado_ant=0;
								  $ld_monto_ejecutado=0;
								  $ld_monto_progracum_ant=0;
								  $li_mesdes=1;
								  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas-1,$ld_monto_programado_ant,
																					   $ld_monto_progracum_ant,$ld_enero,$ld_febrero,
																					   $ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																					   $ld_agosto,$ld_septiembre,$ld_octubre,
																					   $ld_noviembre,$ld_diciembre);	
									if($lb_valido)
									{   
										$ab_pasar=true;
										$lb_valido=$this->uf_spg_reporte_calcular_ejecutado_meses_anteriores($ls_spg_cuenta,$ad_fecdes,
																		$ad_fechas,$ld_monto_ejecutado,$ld_monto_acumulado,$ld_aumdismes,
																		$ld_aumdisacum,$ld_comprometer,$ld_causado,$ld_pagado,$ls_tipo,
																		$ab_pasar);
									}//if		
									if($lb_valido)
									{	
									   $ld_monto_progracum_ant=$ld_comprometer;
									}//if		
								}//if
								else
								{
								  $ld_monto_progracum_ant=0;
								  $ld_monto_programado_ant=0;
								  //$ld_monto_programado=0;
								  $ld_monto_ejecutado=0;
								}//else
							  }//if		
							  $ab_pasar=false;
							  $ld_monto_ejecutado=0;
							  $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_meses_anteriores($ls_spg_cuenta,$ad_fecdes,
															$ad_fechas,$ld_monto_ejecutado,$ld_monto_acumulado,$ld_aumdismes,
															$ld_aumdisacum,$ld_comprometer,$ld_causado,$ld_pagado,$ls_tipo,
															$ab_pasar);
							   if($lb_valido)
							   {
								 if($li_nivel==1)
								 {
									 $ld_asignado_total=$ld_asignado_total+$ld_asignado;
									 $ld_monto_programado_total=$ld_monto_programado_total+$ld_monto_programado;	
									 $ld_monto_programado_acum_total=$ld_monto_programado_acum_total+$ld_monto_programado_acum;	
									 $ld_aumdismes_total=$ld_aumdismes_total+$ld_aumdismes;	
									 $ld_aumdisacum_total=$ld_aumdisacum_total+$ld_aumdisacum;	
									 $ld_monto_ejecutado_total=$ld_monto_ejecutado_total+$ld_monto_ejecutado;	
									 $ld_monto_acumulado_total=$ld_monto_acumulado_total+$ld_monto_acumulado;	
									 $ld_reprog_prox_mes_total=$ld_reprog_prox_mes_total+$ld_reprog_prox_mes;	
									 $ld_comprometer_total=$ld_comprometer_total+$ld_comprometer;	
									 $ld_causado_total=$ld_causado_total+$ld_causado;	
									 $ld_pagado_total=$ld_pagado_total+$ld_pagado;	
									 $ld_monto_progracum_ant_total=$ld_monto_progracum_ant_total+$ld_monto_progracum_ant;	
									 $ld_monto_programado_ant_total=$ld_monto_programado_ant_total+$ld_monto_programado_ant;	
									 $ld_monto_programado_ant_total=$ld_monto_programado_ant_total+$ld_monto_programado_ant;	
							  }//if  
						   }//if
						}//if
					 }//if
				}//while
				 $this->dts_cab->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $this->dts_cab->insertRow("denominacion",$ls_denominacion);
				 $this->dts_cab->insertRow("asignado",$ld_asignado_total);
				 $this->dts_cab->insertRow("monto_programado",$ld_monto_programado_total);
				 $this->dts_cab->insertRow("monto_acumulado",$ld_monto_programado_acum_total);
				 $this->dts_cab->insertRow("aumdis_mes",$ld_aumdismes_total);
				 $this->dts_cab->insertRow("aumdis_acumulado",$ld_aumdisacum_total);
				 $this->dts_cab->insertRow("ejecutado_mes",$ld_monto_ejecutado_total);
				 $this->dts_cab->insertRow("ejecutado_acum",$ld_monto_acumulado_total);
				 $this->dts_cab->insertRow("reprog_prox_mes",$ld_reprog_prox_mes_total);
				 $this->dts_cab->insertRow("compromiso",$ld_comprometer_total);
				 $this->dts_cab->insertRow("causado",$ld_causado_total);					 
				 $this->dts_cab->insertRow("pagado",$ld_pagado_total);					 
				 $this->dts_cab->insertRow("compr_t_ant",$ld_monto_progracum_ant_total);
				 $this->dts_cab->insertRow("prog_t_ant",$ld_monto_programado_ant_total);
				 $this->dts_cab->insertRow("nivel",$li_nivel);
			     $this->dts_cab->insertRow("programatica",$ls_programatica);
				 $lb_valido=true;
			 }//else	
			$this->SQL->free_result($rs_data);
		 }//else
	   }//for
	 }//if
    return $lb_valido;
   }//fin uf_spg_reportes_comparados_forma0705_acciones
/****************************************************************************************************************************************/
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " EJECUCIÓN FINANCIERA DE LAS ACCIONES ESPECIFICAS DEL ENTE FORMA #0415 "  //
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_comparados_forma0415_proyectos($as_codestpro1_ori,$as_codestpro2_ori,
	                                                        $as_codestpro1_des,$as_codestpro2_des,
															$adt_fecdes,$adt_fechas,$as_estcla)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_forma0415_proyectos
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1_ori..$as_codestpro2_ori // rango de la estructura  programatica origen
	 //                     $as_codestpro1_des..$as_codestpro2_des // rango de la estructura  programatica destino
	 //                     $adt_fecdes      //  fecha desde
	 //                     $adt_fechas     //   fecha  hasta
	 //                     $as_estcla     //    estatus  de la clasificacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del comparados del instructivo07 de la forma 0705
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    01/07/2006          Fecha última Modificacion :      Hora :
  	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_spg_reporte0415_select_proyecto($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro1_des,
	                                                     $as_codestpro2_des,$as_estcla,"0415");
	if($lb_valido)
	{
	 $lb_valido = true;	 
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $this->dts_reporte->resetds("spg_cuenta");
	 $li_total=$this->dts_prog->getRowCount("programatica");
	 for($z=1;$z<=$li_total;$z++)
	 {
		$ls_programatica=$this->dts_prog->data["programatica"][$z];
		$ls_codestpro1=substr($ls_programatica,0,20);
		$ls_codestpro2=substr($ls_programatica,20,6);

		$ls_estructura_desde=$ls_codestpro1.$ls_codestpro2;//$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_estructura_hasta=$ls_codestpro1.$ls_codestpro2;//$ls_codestpro3.$ls_codestpro4.$ls_codestpro5; 
		$l_mesdes=substr($adt_fecdes,5,2);
		$l_meshas=substr($adt_fechas,5,2);
		$li_mesdes=intval($l_mesdes);
		$li_meshas=intval($l_meshas);
		$ls_tipo="O";
	    if (strtoupper($ls_gestor)=="MYSQL")
	    {
		   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2)";
	    }
	    else
	    {
		   $ls_cadena="c.codestpro1||c.codestpro2";
	    }
		$ls_sql=" SELECT c.spg_cuenta, c.nivel, max(c.denominacion) as denominacion, sum(c.asignadoaux) as asignado, ".
                "        sum(c.comprometidoaux) as comprometido, sum(c.causadoaux) as causado, sum(c.pagadoaux) as pagado, ".
                "        sum(c.aumentoaux) as aumento, sum(c.disminucionaux) as disminucion, sum(c.eneroaux) as enero, ".
                "        sum(c.febreroaux) as febrero, sum(c.marzoaux) as marzo, sum(c.abrilaux) as abril, sum(c.mayoaux) as mayo, ".
                "        sum(c.junioaux) as junio, sum(c.julioaux) as julio, sum(c.agostoaux) as agosto, sum(c.septiembreaux) as septiembre, ".
                "        sum(c.octubreaux) as octubre, sum(c.noviembreaux) as noviembre, sum(c.diciembreaux) as diciembre, ".
                "        c.codestpro1,c.codestpro2, p.estcla ".
                " FROM   spg_cuentas c, spg_plantillareporte r, spg_ep1 p ".
                " WHERE  c.codemp=r.codemp AND r.codemp=p.codemp AND p.codemp='0001' AND c.codestpro1=r.codestpro1 AND ".
                "        p.estcla='P' AND r.codestpro1=p.codestpro1 AND c.codestpro2=r.codestpro2 AND ".
                "        c.spg_cuenta = r.spg_cuenta  AND  r.status <> 'I'  AND  r.codrep='0415' AND ".
                "        ".$ls_cadena."    between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ".              
                " GROUP BY c.spg_cuenta ".
                " ORDER BY c.spg_cuenta";
        $rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reportes_comparados_forma0415_proyectos".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{		
		     $li_num=$this->SQL->num_rows($rs_data);	
			 if($li_num==0)
			 {
			    $lb_valido = false;
			 }
			 $ld_asignado_total=0;
			 $ld_monto_programado_total=0;	
			 $ld_monto_programado_acum_total=0;	
			 $ld_aumdismes_total=0;	
			 $ld_aumdisacum_total=0;	
			 $ld_monto_ejecutado_total=0;	
			 $ld_monto_acumulado_total=0;	
			 $ld_reprog_prox_mes_total=0;	
			 $ld_comprometer_total=0;	
			 $ld_causado_total=0;	
			 $ld_pagado_total=0;	
			 $ld_monto_progracum_ant_total=0;	
			 $ld_monto_programado_ant_total=0;	
			 while($row=$this->SQL->fetch_row($rs_data))
			 {
				   $ls_spg_cuenta=$row["spg_cuenta"];
				   $ls_denominacion=$row["denominacion"];
				   $li_nivel=$row["nivel"];
				   $ld_asignado=$row["asignado"];
				   $ld_comprometido=$row["comprometido"];
				   $ld_causado=$row["causado"];
				   $ld_pagado=$row["pagado"];
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
				   $ls_codestpro1=$row["codestpro1"]; 
				   $ls_codestpro2=$row["codestpro2"]; 
			       $ls_programatica=$ls_codestpro1.$ls_codestpro2;
				   $lb_ok=true;
				   if($lb_ok)
				   {	
						 //calculo lo programdo y lo acumulado del trimestre 
						 $ls_codrep="0415";
						 //$ls_estructura_desde="";
						// $ls_estructura_hasta="";
						 $ld_monto_programado=0;
						 $ld_monto_programado_acum=0;
						 $lb_valido=$this->uf_spg_reporte_calcular_programado_r($ls_codrep,$ls_spg_cuenta,$ls_estructura_desde,
																				$ls_estructura_hasta,$adt_fecdes,$adt_fechas,
																				$ld_monto_programado,$ld_monto_programado_acum);
						  if($lb_valido)
						  {//calculo el reprogramción del proximo mes 
							   $ld_reprog_prox_mes=0; 
							   $ld_monto_progracum_ant=0;
							   $ld_monto_programado_ant=0;
							  // $ld_monto_programado=0;
							   $ld_monto_ejecutado=0;
							   $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes+1,$li_meshas+1,$ld_reprog_prox_mes,
																					$ld_monto_acumulado,$ls_estructura_desde,
																					$ls_estructura_hasta,$ls_codrep,$ls_spg_cuenta);
							  if($lb_valido)
							  {						
								if($li_mesdes>1)
								{// calculo la programacion de los meses anteriores
								  $ld_monto_programado_ant=0;
								  $ld_monto_ejecutado=0;
								  $ld_monto_progracum_ant=0;
								 //$li_mesdes=1;
								  $lb_valido=$this->uf_spg_reporte_calcular_programado(1,$li_meshas-1,$ld_monto_programado_ant,
																					   $ld_monto_progracum_ant,$ld_enero,$ld_febrero,
																					   $ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																					   $ld_agosto,$ld_septiembre,$ld_octubre,
																					   $ld_noviembre,$ld_diciembre);	
									if($lb_valido)
									{   
										$ab_pasar=true;
										$lb_valido=$this->uf_spg_reporte_calcular_ejecutado_meses_anteriores($ls_spg_cuenta,$adt_fecdes,
																		$adt_fechas,$ld_monto_ejecutado,$ld_monto_acumulado,$ld_aumdismes,
																		$ld_aumdisacum,$ld_comprometer,$ld_causado,$ld_pagado,$ls_tipo,
																		$ab_pasar);
									}//if		
									if($lb_valido)
									{	
									   $ld_monto_progracum_ant=$ld_comprometer;
									}//if		
								}//if
								else
								{
								  $ld_monto_progracum_ant=0;
								  $ld_monto_programado_ant=0;
								  //$ld_monto_programado=0;
								  $ld_monto_ejecutado=0;
								}//else
							  }//if		
							  $ab_pasar=false;
							  $ld_monto_ejecutado=0;
							  $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_meses_anteriores($ls_spg_cuenta,$adt_fecdes,
															$adt_fechas,$ld_monto_ejecutado,$ld_monto_acumulado,$ld_aumdismes,
															$ld_aumdisacum,$ld_comprometer,$ld_causado,$ld_pagado,$ls_tipo,
															$ab_pasar);
							   if($lb_valido)
							   {
								 if($li_nivel==1)
								 {
									 $ld_asignado_total=$ld_asignado_total+$ld_asignado;
									 $ld_monto_programado_total=$ld_monto_programado_total+$ld_monto_programado;	
									 $ld_monto_programado_acum_total=$ld_monto_programado_acum_total+$ld_monto_programado_acum;	
									 $ld_aumdismes_total=$ld_aumdismes_total+$ld_aumdismes;	
									 $ld_aumdisacum_total=$ld_aumdisacum_total+$ld_aumdisacum;	
									 $ld_monto_ejecutado_total=$ld_monto_ejecutado_total+$ld_monto_ejecutado;	
									 $ld_monto_acumulado_total=$ld_monto_acumulado_total+$ld_monto_acumulado;	
									 $ld_reprog_prox_mes_total=$ld_reprog_prox_mes_total+$ld_reprog_prox_mes;	
									 $ld_comprometer_total=$ld_comprometer_total+$ld_comprometer;	
									 $ld_causado_total=$ld_causado_total+$ld_causado;	
									 $ld_pagado_total=$ld_pagado_total+$ld_pagado;	
									 $ld_monto_progracum_ant_total=$ld_monto_progracum_ant_total+$ld_monto_progracum_ant;	
									 $ld_monto_programado_ant_total=$ld_monto_programado_ant_total+$ld_monto_programado_ant;	
									 $ld_monto_programado_ant_total=$ld_monto_programado_ant_total+$ld_monto_programado_ant;	
							  }//if  
						   }//if
						 }  
					  }//if
				 }//while
				 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
				 $this->dts_reporte->insertRow("asignado",$ld_asignado_total);
				 $this->dts_reporte->insertRow("monto_programado",$ld_monto_programado_total);
				 $this->dts_reporte->insertRow("monto_acumulado",$ld_monto_programado_acum_total);
				 $this->dts_reporte->insertRow("aumdis_mes",$ld_aumdismes_total);
				 $this->dts_reporte->insertRow("aumdis_acumulado",$ld_aumdisacum_total);
				 $this->dts_reporte->insertRow("ejecutado_mes",$ld_monto_ejecutado_total);
				 $this->dts_reporte->insertRow("ejecutado_acum",$ld_monto_acumulado_total);
				 $this->dts_reporte->insertRow("reprog_prox_mes",$ld_reprog_prox_mes_total);
				 $this->dts_reporte->insertRow("compromiso",$ld_comprometer_total);
				 $this->dts_reporte->insertRow("causado",$ld_causado_total);					 
				 $this->dts_reporte->insertRow("pagado",$ld_pagado_total);					 
				 $this->dts_reporte->insertRow("compr_t_ant",$ld_monto_progracum_ant_total);
				 $this->dts_reporte->insertRow("prog_t_ant",$ld_monto_programado_ant_total);
				 $this->dts_reporte->insertRow("nivel",$li_nivel);
			     $this->dts_reporte->insertRow("programatica",$ls_programatica);
				 $lb_valido=true;
			     $this->SQL->free_result($rs_data);
		 }//else
	   }//for
	 }//if
    return $lb_valido;
  }//fin uf_spg_reportes_comparados_forma0415_proyectos
/****************************************************************************************************************************************/
    function uf_spg_reportes_comparados_forma0415_acciones($as_codestpro1_ori,$as_codestpro2_ori,
	                                                       $as_codestpro1_des,$as_codestpro2_des,
														   $adt_fecdes,$adt_fechas,$as_estcla)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reportes_comparados_forma0415_acciones
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1_ori..$as_codestpro2_ori // rango de la estructura  programatica origen
	 //                     $as_codestpro1_des..$as_codestpro2_des // rango de la estructura  programatica destino
	 //                     $adt_fecdes      //  fecha desde
	 //                     $adt_fechas     //   fecha  hasta
	 //                     $as_estcla     //    estatus  de la clasificacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del comparados del instructivo07 de la forma 0705
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    01/07/2006          Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=$this->uf_spg_reporte0415_select_proyecto($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro1_des,
	                                                     $as_codestpro2_des,$as_estcla,"0415");
	if($lb_valido)
	{
	 $lb_valido = true;	 
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $this->dts_cab->resetds("spg_cuenta");
	 $li_total=$this->dts_prog->getRowCount("programatica");
	 for($z=1;$z<=$li_total;$z++)
	 {
		$ls_programatica=$this->dts_prog->data["programatica"][$z];
		$ls_codestpro1=substr($ls_programatica,0,20);
		$ls_codestpro2=substr($ls_programatica,20,6);
		$ls_estructura_desde=$ls_codestpro1.$ls_codestpro2;
		$ls_estructura_hasta=$ls_codestpro1.$ls_codestpro2;
		$li_mesdes=substr($adt_fecdes,5,2);
		$li_meshas=substr($adt_fechas,5,2);
		$ls_tipo="O";
	    if (strtoupper($ls_gestor)=="MYSQL")
	    {
		   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2)";
	    }
	    else
	    {
		   $ls_cadena="c.codestpro1||c.codestpro2";
	    }
		$ls_sql=" SELECT c.spg_cuenta, c.nivel, max(c.denominacion) as denominacion, sum(c.asignadoaux) as asignado, ".
                "        sum(c.comprometidoaux) as comprometido, sum(c.causadoaux) as causado, sum(c.pagadoaux) as pagado, ".
                "        sum(c.aumentoaux) as aumento, sum(c.disminucionaux) as disminucion, sum(c.eneroaux) as enero, ".
                "        sum(c.febreroaux) as febrero, sum(c.marzoaux) as marzo, sum(c.abrilaux) as abril, sum(c.mayoaux) as mayo, ".
                "        sum(c.junioaux) as junio, sum(c.julioaux) as julio, sum(c.agostoaux) as agosto, sum(c.septiembreaux) as septiembre, ".
                "        sum(c.octubreaux) as octubre, sum(c.noviembreaux) as noviembre, sum(c.diciembreaux) as diciembre, ".
                "        c.codestpro1,c.codestpro2, max(p.estcla) ".
                " FROM   spg_cuentas c, spg_plantillareporte r, spg_ep1 p ".
                " WHERE  c.codemp=r.codemp AND r.codemp=p.codemp AND p.codemp='".$ls_codemp."' AND c.codestpro1=r.codestpro1 AND ".
                "        p.estcla='".$as_estcla."' AND r.codestpro1=p.codestpro1 AND c.codestpro2=r.codestpro2 AND ".
                "        c.spg_cuenta = r.spg_cuenta  AND  r.status <> 'I'  AND  r.codrep='0415' AND ".
                "        ".$ls_cadena."    between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ".              
                " GROUP BY c.spg_cuenta,c.codestpro1,c.codestpro2 ".
                " ORDER BY c.spg_cuenta ";
        $rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reportes_comparados_forma0415_acciones".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{		
		     $li_num=$this->SQL->num_rows($rs_data);	
			 if($li_num==0)
			 {
			    $lb_valido = false;
			 }
			 $ld_asignado_total=0;
			 $ld_monto_programado_total=0;	
			 $ld_monto_programado_acum_total=0;	
			 $ld_aumdismes_total=0;	
			 $ld_aumdisacum_total=0;	
			 $ld_monto_ejecutado_total=0;	
			 $ld_monto_acumulado_total=0;	
			 $ld_reprog_prox_mes_total=0;	
			 $ld_comprometer_total=0;	
			 $ld_causado_total=0;	
			 $ld_pagado_total=0;	
			 $ld_monto_progracum_ant_total=0;	
			 $ld_monto_programado_ant_total=0;	
			 while($row=$this->SQL->fetch_row($rs_data))
			 {
				   $ls_spg_cuenta=$row["spg_cuenta"];
				   $ls_denominacion=$row["denominacion"];
				   $li_nivel=$row["nivel"];
				   $ld_asignado=$row["asignado"];
				   $ld_comprometido=$row["comprometido"];
				   $ld_causado=$row["causado"];
				   $ld_pagado=$row["pagado"];
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
				   $ls_codestpro1=$row["codestpro1"]; 
				   $ls_codestpro2=$row["codestpro2"]; 
			       $ls_programatica=$ls_codestpro1.$ls_codestpro2;
				   $lb_ok=true;
				   if($lb_ok)
				   {	
						 //calculo lo programdo y lo acumulado del trimestre 
						 $ls_codrep="0415";
						 //$ls_estructura_desde="";
						 //$ls_estructura_hasta="";
						 $ld_monto_programado=0;
						 $ld_monto_programado_acum=0;
						 $lb_valido=$this->uf_spg_reporte_calcular_programado_r($ls_codrep,$ls_spg_cuenta,$ls_estructura_desde,
																				$ls_estructura_hasta,$adt_fecdes,$adt_fechas,
																				$ld_monto_programado,$ld_monto_programado_acum);
						  if($lb_valido)
						  {//calculo el reprogramción del proximo mes 
							   $ld_reprog_prox_mes=0; 
							   $ld_monto_progracum_ant=0;
							   $ld_monto_programado_ant=0;
							   //$ld_monto_programado=0;
							   $ld_monto_ejecutado=0;
							   $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes+1,$li_meshas+1,$ld_reprog_prox_mes,
																					$ld_monto_acumulado,$ls_estructura_desde,
																					$ls_estructura_hasta,$ls_codrep,$ls_spg_cuenta);
							  if($lb_valido)
							  {						
								$li_mesdes=intval($li_mesdes);
								if($li_mesdes>1)
								{// calculo la programacion de los meses anteriores
								  $ld_monto_programado_ant=0;
								  $ld_monto_ejecutado=0;
								  $ld_monto_progracum_ant=0;
								  $lb_valido=$this->uf_spg_reporte_calcular_programado(1,$li_meshas-1,$ld_monto_programado_ant,
																					   $ld_monto_progracum_ant,$ld_enero,$ld_febrero,
																					   $ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																					   $ld_agosto,$ld_septiembre,$ld_octubre,
																					   $ld_noviembre,$ld_diciembre);	
									if($lb_valido)
									{   
										$ab_pasar=true;
										$lb_valido=$this->uf_spg_reporte_calcular_ejecutado_meses_anteriores($ls_spg_cuenta,$adt_fecdes,
																		$adt_fechas,$ld_monto_ejecutado,$ld_monto_acumulado,$ld_aumdismes,
																		$ld_aumdisacum,$ld_comprometer,$ld_causado,$ld_pagado,$ls_tipo,
																		$ab_pasar);
									}//if		
									if($lb_valido)
									{	
									   $ld_monto_progracum_ant=$ld_comprometer;
									}//if		
								}//if
								else
								{
								  $ld_monto_progracum_ant=0;
								  $ld_monto_programado_ant=0;
								  //$ld_monto_programado=0;
								  $ld_monto_ejecutado=0;
								}//else
							  }//if		
							  $ab_pasar=false;
							  $ld_monto_ejecutado=0;
							  $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_meses_anteriores($ls_spg_cuenta,$adt_fecdes,
															$adt_fechas,$ld_monto_ejecutado,$ld_monto_acumulado,$ld_aumdismes,
															$ld_aumdisacum,$ld_comprometer,$ld_causado,$ld_pagado,$ls_tipo,
															$ab_pasar);
							   if($lb_valido)
							   {
								 if($li_nivel==1)
								 {
									 $ld_asignado_total=$ld_asignado_total+$ld_asignado;
									 $ld_monto_programado_total=$ld_monto_programado_total+$ld_monto_programado;	
									 $ld_monto_programado_acum_total=$ld_monto_programado_acum_total+$ld_monto_programado_acum;	
									 $ld_aumdismes_total=$ld_aumdismes_total+$ld_aumdismes;	
									 $ld_aumdisacum_total=$ld_aumdisacum_total+$ld_aumdisacum;	
									 $ld_monto_ejecutado_total=$ld_monto_ejecutado_total+$ld_monto_ejecutado;	
									 $ld_monto_acumulado_total=$ld_monto_acumulado_total+$ld_monto_acumulado;	
									 $ld_reprog_prox_mes_total=$ld_reprog_prox_mes_total+$ld_reprog_prox_mes;	
									 $ld_comprometer_total=$ld_comprometer_total+$ld_comprometer;	
									 $ld_causado_total=$ld_causado_total+$ld_causado;	
									 $ld_pagado_total=$ld_pagado_total+$ld_pagado;	
									 $ld_monto_progracum_ant_total=$ld_monto_progracum_ant_total+$ld_monto_progracum_ant;	
									 $ld_monto_programado_ant_total=$ld_monto_programado_ant_total+$ld_monto_programado_ant;	
									 $ld_monto_programado_ant_total=$ld_monto_programado_ant_total+$ld_monto_programado_ant;
									 $lb_valido=true;	
							  }//if  
						   }//if
						 }  
					  }//if
				 }//while
				 if($lb_valido)
				 {
					 $this->dts_cab->insertRow("spg_cuenta",$ls_spg_cuenta);
					 $this->dts_cab->insertRow("denominacion",$ls_denominacion);
					 $this->dts_cab->insertRow("asignado",$ld_asignado_total);
					 $this->dts_cab->insertRow("monto_programado",$ld_monto_programado_total);
					 $this->dts_cab->insertRow("monto_acumulado",$ld_monto_programado_acum_total);
					 $this->dts_cab->insertRow("aumdis_mes",$ld_aumdismes_total);
					 $this->dts_cab->insertRow("aumdis_acumulado",$ld_aumdisacum_total);
					 $this->dts_cab->insertRow("ejecutado_mes",$ld_monto_ejecutado_total);
					 $this->dts_cab->insertRow("ejecutado_acum",$ld_monto_acumulado_total);
					 $this->dts_cab->insertRow("reprog_prox_mes",$ld_reprog_prox_mes_total);
					 $this->dts_cab->insertRow("compromiso",$ld_comprometer_total);
					 $this->dts_cab->insertRow("causado",$ld_causado_total);					 
					 $this->dts_cab->insertRow("pagado",$ld_pagado_total);					 
					 $this->dts_cab->insertRow("compr_t_ant",$ld_monto_progracum_ant_total);
					 $this->dts_cab->insertRow("prog_t_ant",$ld_monto_programado_ant_total);
					 $this->dts_cab->insertRow("nivel",$li_nivel);
					 $this->dts_cab->insertRow("programatica",$ls_programatica);
					 $lb_valido=true;
				 }	 
		  $this->SQL->free_result($rs_data);
		 }//else
	   }//for
	 }//if
    return $lb_valido;
  }//fin uf_spg_reportes_comparados_forma0415_proyectos
/****************************************************************************************************************************************/
    function uf_spg_reporte0415_select_proyecto($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro1_des,$as_codestpro2_des,$as_estcla,
	                                            $as_codrep)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte0415_select_proyecto
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro2_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro2_des //rango nivel estructura presupuestaria destino
	 //                     as_estcla  // estatus de clasificacion  
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte0415
	 //	   Description :	Reporte que llena un datastore para la seleccion de la estructura programatica del reporte
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    01/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_gestor = $_SESSION["ls_gestor"];
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $this->dts_prog->resetds("spg_cuenta");
	 $ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori;
	 $ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des;	 
	 if (strtoupper($ls_gestor)=="MYSQL")
	 {
	   $ls_cadena="CONCAT(c.codestpro1,c.codestpro2)";
	 }
	 else
	 {
	   $ls_cadena="(c.codestpro1||c.codestpro2)";
	 }
     $ls_sql=" SELECT distinct ".$ls_cadena." as programatica ".
             " FROM   spg_cuentas c, spg_plantillareporte r, spg_ep1 p ".
             " WHERE  c.codemp=r.codemp AND r.codemp=p.codemp AND p.codemp='".$ls_codemp."' AND ".
             "        c.codestpro1=r.codestpro1  AND r.codestpro1=p.codestpro1 AND c.codestpro2=r.codestpro2 AND ".
             "        c.spg_cuenta = r.spg_cuenta AND r.status <> 'I' AND p.estcla='".$as_estcla."' AND  r.codrep='".$as_codrep."' AND ".
             "        ".$ls_cadena."  between '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte0415_select_proyecto ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 }
	else
	{
		if($row=$this->SQL->fetch_row($rs_data))
		{
		  $datos=$this->SQL->obtener_datos($rs_data);
		  $this->dts_prog->data=$datos;				
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);   
     }//else
	return $lb_valido;
   }//	uf_spg_reporte0415_select_proyecto	
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
	 /*$lb_valido=$this->uf_spg_reporte_select_min_codestpro1(&$as_codestpro1);
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2);
	 }
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3);
	 }*/
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 		     "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3 ".
             " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".
             " WHERE  a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND ".
             "        a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2  AND c.codestpro3 like '%' AND c.denestpro3 like '%' ".
             " ORDER BY  codestpro1  limit 1 ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_codestpro2=$row["codestpro2"];
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro2 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro3 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 /*$lb_valido=$this->uf_spg_reporte_select_max_codestpro1(&$as_codestpro1);
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2);
	 }
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3);
	 }*/
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 		     "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3 ".
             " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".
             " WHERE  a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND ".
             "        a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2  AND c.codestpro3 like '%' AND c.denestpro3 like '%' ".
             " ORDER BY  codestpro1 desc limit 1 ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_codestpro2=$row["codestpro2"];
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro2 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
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
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro3 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro3
/****************************************************************************************************************************************/
}//fin de clase
?>