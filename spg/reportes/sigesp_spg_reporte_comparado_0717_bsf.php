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
class sigesp_spg_reporte_comparado_0717_bsf
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $dts_programado;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	var $io_msg;
	var $io_fecha;
	var $sigesp_int_spg;
	var $sigesp_int_spi;
	var $dts_reporte_final;
	var $dts_curft;
	var $dts_ctas_ingreso;
	var $dts_ctas_ingreso_propiedad;
	var $dts_ctas_ingreso_actividad;
	var $dts_ctas_ingreso_financieros;
	var $dts_ctas_egresos;
	var $dts_reporte_salinipromes;
/****************************************************************************************************************************************/	
    function  sigesp_spg_reporte_comparado_0717_bsf()
    {
		$this->io_function=new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_programado=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->dts_reporte_final=new class_datastore();
		$this->dts_curft=new class_datastore();
		$this->dts_ctas_ingreso=new class_datastore();
		$this->dts_ctas_ingreso_propiedad=new class_datastore();
		$this->dts_ctas_ingreso_actividad=new class_datastore();
		$this->dts_ctas_ingreso_financieros=new class_datastore();
		$this->dts_ctas_egresos=new class_datastore();
		$this->dts_reporte_salinipromes=new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int_spg=new class_sigesp_int_spg();
        $this->sigesp_int_spi=new class_sigesp_int_spi();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];

    }
/****************************************************************************************************************************************/
	////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  COMPARADOS " PRESUPUESTO DE CAJA  0717 "        //
	///////////////////////////////////////////////////////////////////////////
    function uf_spg_reportes_comparados_presupuesto_de_caja($adt_fecdes,$adt_fechas,$ls_etiqueta)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_comparados_presupuesto_de_caja
	  //        Argumentos :    adt_fecdes // fecha de desde 
	  //                        adt_fechas  // fecha hasta 
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    12/01/2007                       Fecha última Modificacion : 18/01/2006     Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ld_salinipro=0;
	  $ld_salinieje=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $li_mesdes=intval(substr($adt_fecdes,3,2));
	  $li_mes_des=$li_mesdes;
	  if($li_mesdes>=2)
	  {		
		$lb_valido=$this->uf_spg_reporte_select_saldo_empresa($ld_salinipro,$ld_salinieje);
		if($lb_valido)
		{   
		  for($li=1;$li<=12;$li++)
		  {		  
			  $this->dts_reporte_final->reset_ds();
			  $this->dts_reporte_salinipromes->reset_ds();
			  if($li==$li_mes_des)
			  {
				break;
			  }
			  if($ls_etiqueta=="Mensual")
			  {
				  $ls_cant_mes=1;
				  $li_mesanthas=$li_mesantdes+$ls_cant_mes;
				  $li_mesantdes=$this->io_function->uf_cerosizquierda($li,2);
				  $li_mesanthas=$this->io_function->uf_cerosizquierda($li_mesanthas,2);
				  $ls_diahas=$this->io_fecha->uf_last_day($li_mesanthas,$li_ano);
				  $ldt_fecantdes="01/".$li_mesantdes."/".$li_ano;
				  $ldt_fecanthas=$ls_diahas;
			  }
			  else
			  {
				 if($ls_etiqueta=="Bi-Mensual")
				 {
					  if($li==1){  $li_mesantdes=1;  } 	
					  else  {    $li_mesantdes=$li;  }
					  $ls_cant_mes=2;
					  $li_mesanthas=$li_mesantdes+1;					  
					  $li_mesanthas=$this->io_function->uf_cerosizquierda($li_mesanthas,2);
					  $ls_diahas=$this->io_fecha->uf_last_day($li_mesanthas,$li_ano);
					  $ldt_fecantdes="01/".$this->io_function->uf_cerosizquierda($li_mesantdes,2)."/".$li_ano;
					  $ldt_fecanthas=$ls_diahas;		
				 }
				 if($ls_etiqueta=="Trimestral")
				 {
					  $ls_cant_mes=3;
					  if($li==1){  $li_mesantdes=1;  } 	
					  else { $li_mesantdes=$li; }
					  $li_mesanthas=$li_mesantdes+2; 
					  $li_mesanthas=$this->io_function->uf_cerosizquierda($li_mesanthas,2);
					  $ls_diahas=$this->io_fecha->uf_last_day($li_mesanthas,$li_ano);
					  $ldt_fecantdes="01/".$this->io_function->uf_cerosizquierda($li_mesantdes,2)."/".$li_ano;
					  $ldt_fecanthas=$ls_diahas;
					  $li=$li+2;
				 }
				 if($ls_etiqueta=="Semestral")
				 {
					  $ls_cant_mes=6;
					  if($li==1){  $li_mesantdes=1;  } 	
					  else { $li_mesantdes=$li; }
					  $li_mesanthas=$li_mesantdes+5;
					  $li_mesanthas=$this->io_function->uf_cerosizquierda($li_mesanthas,2);
					  $ls_diahas=$this->io_fecha->uf_last_day($li_mesanthas,$li_ano);
					  $ldt_fecantdes="01/".$this->io_function->uf_cerosizquierda($li_mesantdes,2)."/".$li_ano;
					  $ldt_fecanthas=$ls_diahas;
				 }
			  }
 			  $lb_valido=$this->uf_spg_reportes_comparados_presupuesto_de_caja_0717($ldt_fecantdes,$ldt_fecanthas,$ld_salinipro,
																				$ld_salinieje,$this->dts_reporte_salinipromes);
			  $li_pos=$this->dts_reporte_salinipromes->find("tipo","5");
			  if($li_pos>0)
			  {
				  $ld_salinipro=$this->dts_reporte_salinipromes->getValue("monto_programado",$li_pos);
				  $ld_salinieje=$this->dts_reporte_salinipromes->getValue("monto_ejecutado",$li_pos);
				  $ld_varia_abs_final=$this->dts_reporte_salinipromes->getValue("variacion_absoluta",$li_pos);
				  $ld_porc_varia_final=$this->dts_reporte_salinipromes->getValue("porcentaje_variacion",$li_pos);
				  $ld_previsto_final=$this->dts_reporte_salinipromes->getValue("previsto",$li_pos);
			  }//if
		  }//for	
		  }	//if  
		  if($lb_valido)
		  {	  
			 $lb_valido=$this->uf_spg_reportes_comparados_presupuesto_de_caja_0717($adt_fecdes,$adt_fechas,
																				   $ld_salinipro,$ld_salinieje,
																				   $this->dts_reporte_final);
		  }
	  }
	  else
	  {
		  $lb_valido=$this->uf_spg_reporte_select_saldo_empresa($ld_salinipro,$ld_salinieje);
		  if($lb_valido)
		  {
		  	$lb_valido=$this->uf_spg_reportes_comparados_presupuesto_de_caja_0717($adt_fecdes,$adt_fechas,
			                                                                      $ld_salinipro,$ld_salinieje,
																				  $this->dts_reporte_final);
		  }
	  }
	  return  $lb_valido;
	}//fin  uf_spg_reportes_comparados_presupuesto_de_caja
/****************************************************************************************************************************************/
    function uf_spg_reportes_comparados_presupuesto_de_caja_0717($adt_fecdes,$adt_fechas,$ad_salinipro,$ad_salinieje,$adts_datastore)
    { ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_comparados_presupuesto_de_caja_0717
	  //        Argumentos :    adt_fecdes ... adt_fechas  // rango de fecha del reporte
	  //                        ad_salinipro  // saldo inicial programado
	  //                        ad_salinieje  // saldo inicial ejecutado
	  //                        adts_datastore  // datastore que imprime el reporte
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    24/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $this->dts_reporte_final->reset_ds();
	  $lb_valido = true;	 
	  if($lb_valido)
	  {
	     $ls_periodo=$_SESSION["la_empresa"]["periodo"];
		 $ls_sql=" SELECT DISTINCT spg_cuenta,denominacion ".
                 " FROM   spg_cuentas ".
                 " WHERE  (codemp='".$this->ls_codemp."') AND ".
                 "        (spg_cuenta LIKE '401000000%' OR spg_cuenta LIKE '402000000%' OR  spg_cuenta LIKE '403000000%' OR ".
	             "         spg_cuenta LIKE '408000000%' OR spg_cuenta LIKE '410000000%' OR  spg_cuenta LIKE '407000000%' OR ". 
      			 "		   spg_cuenta LIKE '407010100%' OR spg_cuenta LIKE '407010300%' OR  spg_cuenta LIKE '407030000%' OR ".
      			 "	       spg_cuenta LIKE '407030100%' OR spg_cuenta LIKE '407030300%' OR  spg_cuenta LIKE '407020000%' OR ".
      			 "		   spg_cuenta LIKE '408080000%' OR spg_cuenta LIKE '404000000%' OR  spg_cuenta LIKE '405000000%' OR ".
				 "         spg_cuenta LIKE '407010201%' OR spg_cuenta LIKE '407010202%' OR  spg_cuenta LIKE '407010401%' OR ".
				 "         spg_cuenta LIKE '407010402%' OR spg_cuenta LIKE '407010403%' OR  spg_cuenta LIKE '407010404%' OR ".
				 "         spg_cuenta LIKE '407010405%' OR spg_cuenta LIKE '407010406%' OR  spg_cuenta LIKE '407010407%' OR ".
				 "         spg_cuenta LIKE '407010408%' OR spg_cuenta LIKE '407010409%')  ".
				 " ORDER BY spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  MÉTODO->uf_spg_reportes_comparados_presupuesto_de_caja_0717  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado($ls_codrep,$ls_spg_cuenta,$ld_enero,$ld_febrero,$li_nivel,
												                            $ls_status,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																			$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_monto_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_monto_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
				     $ls_tipo="O";
					 $ld_monto_ejecutado=0;			 $ld_monto_acumulado=0;    
					 $ld_aumdismes=0;				 $ld_aumdisacum=0;
	                 $ld_comprometer=0;				 $ld_causado=0;
					 $ld_pagado=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado($ls_spg_cuenta,$ls_tipo,$ld_monto_ejecutado,
					                                                     $ld_monto_acumulado,$ld_aumdismes,$ld_aumdisacum,
	                                                                     $ld_comprometer,$ld_causado,$ld_pagado,$adt_fecdes,
																		 $adt_fechas,"C");  
					 if($lb_valido)
					 {
						 $ls_tipo_cuenta="Y";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 
						 $this->dts_reporte->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
						 $this->dts_reporte->insertRow("status",$ls_status);
						 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
						 $this->dts_reporte->insertRow("nivel",$li_nivel);
						 $this->dts_reporte->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_reporte->insertRow("monto_ejecutado",$ld_monto_ejecutado);
						 $this->dts_reporte->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_reporte->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_reporte->insertRow("previsto",$ld_monto_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if				   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
	  }//if	
	  //Busco dentro de Ingreso
	  if($lb_valido)
	  {
	    $lb_valido=$this->uf_spg_reportes_otros_egresos_corrientes($adt_fecdes,$adt_fechas);
	  }
	   if($lb_valido)
	  {
	    $lb_valido=$this->uf_spg_reportes_ingreso_flujo_de_caja(1,$adt_fecdes,$adt_fechas);
	  }
	  if($lb_valido)
	  {
	    $lb_valido=$this->uf_spg_reportes_init_array($ad_salinipro,$ad_salinieje,$adts_datastore,$adt_fecdes,$adt_fechas);
	  }
      return $lb_valido;
   }//fin uf_spg_reportes_comparados_presupuesto_de_caja_0717
/****************************************************************************************************************************************/	
    function uf_spg_reporte_select_saldo_empresa(&$ad_salinipro,&$ad_salinieje)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_saldo_empresa
	 //         Access :	private
	 //     Argumentos :    $ad_salinipro --> saldo inicial programado(referencia) 
	 //                     $ad_salinieje --> saldo inicial ejecutado(referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    24/08/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	 
	  $ls_sql=" SELECT saliniproaux, saliniejeaux  FROM sigesp_empresa WHERE codemp='".$this->ls_codemp."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  MÉTODO->uf_spg_reporte_select_saldo_empresa  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
              $ad_salinipro=$row["salinipro"];
			  $ad_salinieje=$row["salinieje"];	
	    }
		$this->io_sql->free_result($rs_data);
      }//else
	  return $lb_valido;
   }//fin uf_spg_reporte_select_saldo_empresa
/****************************************************************************************************************************************/	
    function uf_spg_reporte_llenar_datastore_programado($as_codrep,$as_spg_cuenta,&$ad_enero,&$ad_febrero,&$ai_nivel,&$as_status,
												        &$ad_marzo,&$ad_abril,&$ad_mayo,&$ad_junio,&$ad_julio,&$ad_agosto,
														&$ad_septiembre,&$ad_octubre,&$ad_noviembre,&$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_llenar_datastore
	 //         Access :	private
	 //     Argumentos :    $ad_salinipro --> saldo inicial programado(referencia) 
	 //                     $ad_salinieje --> saldo inicial ejecutado(referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    24/08/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ls_sql=" SELECT sum(asignadoaux) as asignado,sum(comprometidoaux) as comprometido, sum(causadoaux) as causado, ".
              "        sum(pagadoaux) as pagado,sum(aumentoaux) as aumento, sum(disminucionaux) as disminucion, sum(eneroaux) as enero, ".
              "        sum(febreroaux) as febrero, sum(marzoaux) as marzo, sum(abrilaux) as abril, sum(mayoaux) as mayo, sum(junioaux) as junio, ".
              "        sum(julioaux) as julio, sum(agostoaux) as agosto, sum(septiembreaux) as septiembre, ".
              "        sum(octubreaux) as octubre, sum(noviembreaux) as noviembre, sum(diciembreaux) as diciembre, max(nivel) as nivel, ".
			  "        max(status) as status, max(denominacion) as  denominacion".
     		  "	FROM   spg_plantillareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND spg_cuenta='".$as_spg_cuenta."' ".
			  " GROUP BY spg_cuenta ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                          MÉTODO->uf_spg_reporte_llenar_datastore_programado  
								  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			   $ai_nivel=$row["nivel"];
			   $as_status=$row["status"];
			   $ad_asignado=$row["asignado"];
			   $ad_comprometido=$row["comprometido"];
			   $ad_causado=$row["causado"];
			   $ad_pagado=$row["pagado"];
			   $ad_aumento=$row["aumento"];
			   $ad_disminucion=$row["disminucion"];
			   $ad_enero=$row["enero"];
			   $ad_febrero=$row["febrero"];
			   $ad_marzo=$row["marzo"];
			   $ad_abril=$row["abril"];
			   $ad_mayo=$row["mayo"];
			   $ad_junio=$row["junio"];
			   $ad_julio=$row["julio"];
			   $ad_agosto=$row["agosto"];
			   $ad_septiembre=$row["septiembre"];
			   $ad_octubre=$row["octubre"];
			   $ad_noviembre=$row["noviembre"];
			   $ad_diciembre=$row["diciembre"];
		       $lb_valido = true;
	    }
		$this->io_sql->free_result($rs_data);
      }//else
	 return $lb_valido;
   }//fin uf_spg_reporte_llenar_datastore_programado
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_programado($ai_mesdes,$ai_meshas,&$ad_monto_programado,&$ad_monto_previsto,$ad_enero,$ad_febrero,
												$ad_marzo,$ad_abril,$ad_mayo,$ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,
												$ad_octubre,$ad_noviembre,$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_programado
	 //         Access :	private
	 //     Argumentos :    $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
	 //                     $ad_monto_previsto // monto programado del acumulado (referencia)  
	 //                     $ad_enero .. $ad_diciembre  // monto programado desde  enero  hasta diciembre  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	metodo que calcula los montos programados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    24/08/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=true;
     $li_mesdes=intval($ai_mesdes);
     $li_meshas=intval($ai_meshas);
     $li_mesinipre=$li_meshas+1;
     $li_mesfinpre=$li_mesinipre+($li_meshas-$li_mesdes);
     if(!(($li_mesdes>=1)&&($li_meshas<=12)))
     {
	   $lb_valido=false;
     }
     if($lb_valido)
     {
		if ((1>=$li_mesdes)&&(1<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_enero; }   
		if ((2>=$li_mesdes)&&(2<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_febrero; }   
		if ((3>=$li_mesdes)&&(3<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_marzo;  }   
		if ((4>=$li_mesdes)&&(4<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_abril; }   
		if ((5>=$li_mesdes)&&(5<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_mayo;  }   
		if ((6>=$li_mesdes)&&(6<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_junio; }   
		if ((7>=$li_mesdes)&&(7<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_julio; }   
		if ((8>=$li_mesdes)&&(8<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_agosto; }   
		if ((9>=$li_mesdes)&&(9<=$li_meshas))   {  $ad_monto_programado=$ad_monto_programado+$ad_septiembre; }   
		if ((10>=$li_mesdes)&&(10<=$li_meshas)) {  $ad_monto_programado=$ad_monto_programado+$ad_octubre; }   
		if ((11>=$li_mesdes)&&(11<=$li_meshas))	{  $ad_monto_programado=$ad_monto_programado+$ad_noviembre;	}   
		if ((12>=$li_mesdes)&&(12<=$li_meshas))	{  $ad_monto_programado=$ad_monto_programado+$ad_diciembre;	}   
		//// Calculo el Previsto  
		if ((1>=$li_mesinipre)&&(1<=$li_mesfinpre))   {  $ad_monto_previsto=$ad_monto_previsto+$ad_enero;	}   
		if ((2>=$li_mesinipre)&&(2<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_febrero; }   
		if ((3>=$li_mesinipre)&&(3<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_marzo;	}   
		if ((4>=$li_mesinipre)&&(4<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_abril;	}   
		if ((5>=$li_mesinipre)&&(5<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_mayo;	}   
		if ((6>=$li_mesinipre)&&(6<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_junio;	}   
		if ((7>=$li_mesinipre)&&(7<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_julio;  }   
		if ((8>=$li_mesinipre)&&(8<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_agosto; }   
		if ((9>=$li_mesinipre)&&(9<=$li_mesfinpre))	  {  $ad_monto_previsto=$ad_monto_previsto+$ad_septiembre; }   
		if ((10>=$li_mesinipre)&&(10<=$li_mesfinpre)) {  $ad_monto_previsto=$ad_monto_previsto+$ad_octubre;	}   
		if ((11>=$li_mesinipre)&&(11<=$li_mesfinpre)) {  $ad_monto_previsto=$ad_monto_previsto+$ad_noviembre; }   
		if ((12>=$li_mesinipre)&&(12<=$li_mesfinpre)) {  $ad_monto_previsto=$ad_monto_previsto+$ad_diciembre; }   
	}//if
    return  $lb_valido; 
 }//fin uf_spg_reporte_calcular_programado
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado($as_spg_cuenta,$as_tipo,&$ad_monto_ejecutado,&$ad_monto_acumulado,&$ad_aumdismes,
	                                           &$ad_aumdisacum,&$ad_comprometer,&$ad_causado,&$ad_pagado,$adt_fecini,$adt_fecfin,
											   $as_tipo_metodo)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $as_tipo  //  tipo
	 //                     $ad_monto_ejecutado  //  monto ejecutado (referencia)
	 //                     $ad_monto_acumulado  //  monto  acumulado (referencia)
     //              	    $ad_aumdismes  // aumento  disminucion (referencia)
     //              	    $ad_aumdisacum  // aumento  disminucion acumulada (referencia)
	 //                     $ad_comprometer  //  monto comprometido (referencia)
	 //                     $ad_causado  // monto causado (referencia)
	 //                     $ad_pagado  // monto pagado (referencia)
	 //                     $adt_fecini  // fecha inicio
	 //                     $adt_fecfin  // fecha fin
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para el Formato 3 de la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/08/2006         Fecha última Modificacion :      Hora :
  	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ld_aumento=0;	          $ld_disminucion=0;
	  $ld_aumento_acum=0;	      $ld_disminucion_acum=0;
	  $ad_monto_ejecutado=0;	  $ad_monto_acumulado=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $_SESSION["la_empresa"]["codemp"];
	  $l_mesdes=substr($adt_fecini,3,2);
	  $l_meshas=substr($adt_fecfin,3,2);
	  $li_mesdes=intval($l_mesdes);
	  $li_meshas=intval($l_meshas);
	  $ldt_mesdes=$li_ano."-".$this->io_function->uf_cerosizquierda($li_mesdes,2);
	  $ldt_meshas=$li_ano."-".$this->io_function->uf_cerosizquierda($li_meshas,2);
	  if($li_mesdes>3)
	  {
			$ldt_mesantdes=$li_ano."-".$this->io_function->uf_cerosizquierda(($li_mesdes-3),2);
			$ldt_mesanthas=$li_ano."-".$this->io_function->uf_cerosizquierda(($li_mesdes-1),2);
	  }
	  else
	  {
			$ldt_mesantdes=$ldt_mesdes;
			$ldt_mesanthas=$ldt_meshas;
	  }	  
	  //$as_spg_cuenta=$as_spg_cuenta."%";
	  if($as_tipo_metodo=="C")
	  { 
	  	$as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  }
	  elseif($as_tipo_metodo=="S")
	  { 
	  	$as_spg_cuenta=$as_spg_cuenta;
	  }
	  $ls_sql=" SELECT DT.fecha, DT.montoaux as monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
			  " FROM   spg_dt_cmp DT, spg_operaciones OP ".
			  " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND (OP.comprometer='1' OR  OP.causar='1' OR ".
			  "        OP.pagar='1' )  AND  DT.spg_cuenta like '".$as_spg_cuenta."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                        MÉTODO->uf_spg_reporte_calcular_ejecutado  
								ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_data))
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
		    if(($li_pagar)&&($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
			{ 
			  $ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			}//if
			if(($li_pagar)&&($ldt_fecha<=$ldt_meshas))
			{  
			  $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			}//if
		  }// if($as_tipo=="O")
		  //Comprometer, Causar, Pagar, Aumento, Disminución
		  if(($li_comprometer)&&($ldt_fecha>=$ldt_mesantdes)&&($ldt_fecha<=$ldt_mesanthas))
		  { 
			$ad_comprometer=$ad_comprometer+$ld_monto;
		  }//if
		  if(($li_causar)&&($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
		  { 
		    $ad_causado=$ad_causado+$ld_monto;
		  }//if
		  if(($li_pagar)&&($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
		  { 
		    $ad_pagado=$ad_pagado+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
		  { 
		    $ld_aumento=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
		  { 
		    $ld_disminucion=$ld_disminucion+$ld_monto;
		  }//if
		  if(($li_aumento)&&($ldt_fecha<=$ldt_meshas))
		  {  
			  $ld_aumento_acum=$ld_aumento+$ld_monto;
		  }//if
		  if(($li_disminucion)&&($ldt_fecha<=$ldt_meshas))
		  {  
			  $ld_disminucion_acum=$ld_disminucion+$ld_monto;
		  }//if
		}//while
		$ad_aumdismes=$ld_aumento-$ld_disminucion;
		$ad_aumdisacum=$ld_aumento_acum-$ld_disminucion_acum;
	   $this->io_sql->free_result($rs_data);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado
/****************************************************************************************************************************************/
    function uf_spg_reportes_ingreso_flujo_de_caja($ai_nivel,$adt_fecdes,$adt_fechas)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_ingreso_flujo_de_caja
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    24/08/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = false;	 
	   $ls_periodo=$_SESSION["la_empresa"]["periodo"];
	   $ls_sql=" SELECT spi_cuenta,denominacion ".
			   " FROM   spi_cuentas ".
			   " WHERE  codemp='".$this->ls_codemp."' AND ".
			   "       (spi_cuenta LIKE '305010000%' OR spi_cuenta LIKE '305010100%' OR spi_cuenta LIKE '305010300%' OR ".
               "        spi_cuenta LIKE '305010301%' OR spi_cuenta LIKE '305010302%' OR spi_cuenta LIKE '305010304%' OR spi_cuenta LIKE '305010308%' OR ".
        	   "	    spi_cuenta LIKE '305010309%' OR spi_cuenta LIKE '305010500%' OR spi_cuenta LIKE '305010501%' OR spi_cuenta LIKE '305010502%' OR ".
               "		spi_cuenta LIKE '305010503%' OR spi_cuenta LIKE '303010000%' OR spi_cuenta LIKE '303020000%' OR spi_cuenta LIKE '301050000%' OR ".
               " 	    spi_cuenta LIKE '301100000%' OR spi_cuenta LIKE '301100401%' OR spi_cuenta LIKE '301100500%' OR spi_cuenta LIKE '306010000%' OR ".
               "		spi_cuenta LIKE '306020000%' OR spi_cuenta LIKE '305020000%' OR spi_cuenta LIKE '305020100%' OR spi_cuenta LIKE '305020300%' OR ".
        	   " 		spi_cuenta LIKE '305020301%' OR spi_cuenta LIKE '305020302%' OR spi_cuenta LIKE '305020308%' OR spi_cuenta LIKE '305020309%' OR ".
               "		spi_cuenta LIKE '305020500%' OR spi_cuenta LIKE '305020501%' OR spi_cuenta LIKE '305020502%' OR spi_cuenta LIKE '305020503%' OR ".
               "		spi_cuenta LIKE '307000000%' OR spi_cuenta LIKE '308000000%' OR spi_cuenta LIKE '309000000%' ) ".
			   " ORDER BY spi_cuenta ";
			  // print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                           MÉTODO->uf_spg_reportes_ingreso_flujo_de_caja  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado_ingreso($ls_codrep,$ls_spi_cuenta,$ld_enero,$ld_febrero,
			                                                                        $li_nivel,$ls_status,$ld_marzo,$ld_abril,$ld_mayo,
																					$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,
																					$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
					 $ld_prev=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_ingreso($ls_spi_cuenta,$ld_prev,$ld_devengado,
					                                                             $ld_cobrado,$ld_cobrado_anticipado,$ld_aumento,
	                                                                             $ld_disminucion,$adt_fecdes,$adt_fechas);  
					 
					if($lb_valido)
					{
						 $ls_tipo_cuenta="I";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_reporte->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_reporte->insertRow("spg_cuenta",$ls_spi_cuenta);
						 $this->dts_reporte->insertRow("status",$ls_status);
						 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
						 $this->dts_reporte->insertRow("nivel",$li_nivel);
						 $this->dts_reporte->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_reporte->insertRow("monto_ejecutado",$ld_cobrado);
						 $this->dts_reporte->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_reporte->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_reporte->insertRow("previsto",$ld_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if			   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
	     if($lb_valido)
	     {
		    $lb_valido=$this->uf_spg_reportes_otros_ingresos($ai_nivel,$adt_fecdes,$adt_fechas);
	     }//else
	     if($lb_valido)
	     {
		    $lb_valido=$this->uf_spg_reportes_otros_ingresos_propiedad($ai_nivel,$adt_fecdes,$adt_fechas);
	     }//else
	     if($lb_valido)
	     {
		    $lb_valido=$this->uf_spg_reportes_ingresos_actividades_propias($ai_nivel,$adt_fecdes,$adt_fechas);
	     }//else
	     if($lb_valido)
	     {
		    $lb_valido=$this->uf_spg_reportes_ingresos_financieros($ai_nivel,$adt_fecdes,$adt_fechas);
	     }//else
      return $lb_valido;
   }//fin uf_spg_reportes_comparados_presupuesto_de_caja_0717
/****************************************************************************************************************************************/	
    function uf_spg_reporte_llenar_datastore_programado_ingreso($as_codrep,$as_spi_cuenta,&$ad_enero,&$ad_febrero,&$ai_nivel,
	                                                            &$as_status,&$ad_marzo,&$ad_abril,&$ad_mayo,&$ad_junio,
																&$ad_julio,&$ad_agosto,&$ad_septiembre,&$ad_octubre,
																&$ad_noviembre,&$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_llenar_datastore
	 //     Argumentos :    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/08/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql=" SELECT sum(previstoaux) as previsto,sum(devengadoaux) as devengado, sum(cobradoaux) as cobrado, max(nivel) as nivel,  ".
              "        sum(cobrado_anticipadoaux) as cobrado_anticipado,sum(aumentoaux) as aumento, sum(disminucionaux) as disminucion,  ".
              "        sum(eneroaux) as enero, sum(febreroaux) as febrero, sum(marzoaux) as marzo, sum(abrilaux) as abril, sum(mayoaux) as mayo, ".
              "        sum(junioaux) as junio, sum(julioaux) as julio, sum(agostoaux) as agosto, sum(septiembreaux) as septiembre, ".
              "        sum(octubreaux) as octubre, sum(noviembreaux) as noviembre, sum(diciembreaux) as diciembre, max(status) as status, ".
			  "        max(denominacion) as denominacion  ".
     		  "	FROM   spi_plantillacuentareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND spi_cuenta='".$as_spi_cuenta."' ".
			  " GROUP BY spi_cuenta ";
			  //print $ls_sql."<br>";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                          MÉTODO->uf_spg_reporte_llenar_datastore_programado_ingreso  
								  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			   $ai_nivel=$row["nivel"];
			   $as_status=$row["status"];
			   $ad_previsto=$row["previsto"];
			   $ad_devengado=$row["devengado"];
			   $ad_cobrado=$row["cobrado"];
			   $ad_cobrado_anticipado=$row["cobrado_anticipado"];
			   $ad_aumento=$row["aumento"];
			   $ad_disminucion=$row["disminucion"];
			   $ad_enero=$row["enero"];
			   $ad_febrero=$row["febrero"];
			   $ad_marzo=$row["marzo"];
			   $ad_abril=$row["abril"];
			   $ad_mayo=$row["mayo"];
			   $ad_junio=$row["junio"];
			   $ad_julio=$row["julio"];
			   $ad_agosto=$row["agosto"];
			   $ad_septiembre=$row["septiembre"];
			   $ad_octubre=$row["octubre"];
			   $ad_noviembre=$row["noviembre"];
			   $ad_diciembre=$row["diciembre"];
		       $lb_valido = true;
	    }
		else
		{
			   $ai_nivel="";
			   $as_status="";
			   $ad_previsto="";
			   $ad_devengado="";
			   $ad_cobrado=0;
			   $ad_cobrado_anticipado=0;
			   $ad_aumento=0;
			   $ad_disminucion=0;
			   $ad_enero=0;
			   $ad_febrero=0;
			   $ad_marzo=0;
			   $ad_abril=0;
			   $ad_mayo=0;
			   $ad_junio=0;
			   $ad_julio=0;
			   $ad_agosto=0;
			   $ad_septiembre=0;
			   $ad_octubre=0;
			   $ad_noviembre=0;
			   $ad_diciembre=0;
		       $lb_valido = true;
		}
		$this->io_sql->free_result($rs_data);
      }//else
	 return $lb_valido;
   }//fin uf_spg_reporte_llenar_datastore_programado_ingreso
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado_ingreso($as_spi_cuenta,&$ad_previsto,&$ad_devengado,&$ad_cobrado,&$ad_cobrado_anticipado,
	                                                   &$ad_aumento,&$ad_disminucion,$adt_fecini,$adt_fecfin)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado_ingreso
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $ad_previsto  //  monto previsto (referencia)
	 //                     $ad_devengado  //  monto  devengado (referencia)
     //              	    $ad_cobrado  // monto cobrado (referencia)
     //              	    $ad_cobrado_anticipado  // cobrado anticipado (referencia)
	 //                     $ad_aumento  //  monto aumento (referencia)
	 //                     $ad_disminucion  // monto disminucion (referencia)
	 //                     $adt_fecini  // fecha inicio
	 //                     $adt_fecfin  // fecha fin
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para el Formato 3 de la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/08/2006         Fecha última Modificacion :      Hora :
  	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ad_previsto=0;	          $ad_devengado=0;
	  $ad_cobrado=0;	          $ad_cobrado_anticipado=0;
	  $ad_aumento=0;	          $ad_disminucion=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $l_mesdes=substr($adt_fecini,3,2);
	  $l_meshas=substr($adt_fecfin,3,2);
	  $li_mesdes=intval($l_mesdes);
	  $li_meshas=intval($l_meshas);
	  $ldt_mesdes=$li_ano."-".$this->io_function->uf_cerosizquierda($li_mesdes,2);
	  $ldt_meshas=$li_ano."-".$this->io_function->uf_cerosizquierda($li_meshas,2);
	  //$as_spi_cuenta=$as_spi_cuenta."%";
	  $as_spi_cuenta=$this->sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	  $ls_sql=" SELECT operacion,montoaux as monto,fecha".
			  " FROM   spi_dt_cmp ".
			  " WHERE  codemp='".$this->ls_codemp."'  AND  spi_cuenta like '".$as_spi_cuenta."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                         MÉTODO->uf_spg_reporte_calcular_ejecutado_ingreso  
								 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_operacion=$row["operacion"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,7);
		  
		  $ldt_fecha=str_replace("-","",$ldt_fecha);
		  $ldt_mesdes=str_replace("-","",$ldt_mesdes);
		  $ldt_meshas=str_replace("-","",$ldt_meshas);
		  $ls_opera=$this->sigesp_int_spi->uf_operacion_codigo_mensaje($ls_operacion);
		  $ls_mensaje=strtoupper($ls_opera); // devuelve cadena en MAYUSCULAS
		  $li_pos_i=strpos($ls_mensaje,"I"); 
		  if (!($li_pos_i===false)) 
		  { 
		    $ad_previsto=$ad_previsto+$ld_monto; 
		  }
		   
		  if(($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
		  {
			  $li_pos_e=strpos($ls_mensaje,"E"); 
			  if (!($li_pos_e===false)) 
			  { 
				 $ad_devengado=$ad_devengado+$ld_monto;
			  }
			  $li_pos_c=strpos($ls_mensaje,"C"); 
			  if (!($li_pos_c===false)) 
			  {	
				 $ad_cobrado=$ad_cobrado+$ld_monto;
			  }
			  $li_pos_n=strpos($ls_mensaje,"N"); 
			  if (!($li_pos_n===false))
			  {	
			    $ad_cobrado_anticipado = $ad_cobrado_anticipado+$ld_monto; 
			  }
			  $li_pos_a=strpos($ls_mensaje,"A"); 
			  if (!($li_pos_a===false))
			  {	
			    $ad_aumento = $ad_aumento+$ld_monto; 
			  }
			  $li_pos_d=strpos($ls_mensaje,"D"); 
			  if (!($li_pos_d===false))
			  {	
			    $ad_disminucion = $ad_disminucion+$ld_monto; 
			  }
	          $lb_valido = true;
		  }//if
		}//while
	   $this->io_sql->free_result($rs_data);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado
/****************************************************************************************************************************************/
    function uf_spg_reportes_init_array($ad_salinipro,$ad_salinieje,$adts_datastore,$adt_fecdes,$adt_fechas)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_init_array
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_formpre=$_SESSION["la_empresa"]["formpre"];
	  $lb_encontrado=false;
      $lb_valido=$this->uf_spg_reportes_llenar_datastore_cuentas($adt_fecdes,$adt_fechas);
	  if($lb_valido)
	  {
		//print $ls_formpre."<br>";
		$ls_formato=str_replace("-","",$ls_formpre);
		//print $ls_formato."<br>";
		$li_lent=strlen(trim($ls_formato));
		//print $li_lent."<br>";
		if($li_lent!=9)
		{
		  $li_cantcer=$li_lent-9;
		  $ls_cadcer="";
		  $ls_ceros=$this->io_function->uf_cerosderecha($ls_cadcer,$li_cantcer);
		}
		else
		{
		  $ls_ceros="";
		}
		//print $ls_ceros."<br>";
		$la_cuenta[49]=array();
		$la_cuenta[1]='305010000'.$ls_ceros;
		$la_cuenta[2]='305010100'.$ls_ceros;
		$la_cuenta[3]='305010300'.$ls_ceros;
		$la_cuenta[4]='305010301'.$ls_ceros;
		$la_cuenta[5]='305010302'.$ls_ceros;
		$la_cuenta[6]='305010303'.$ls_ceros;
		$la_cuenta[7]='305010304'.$ls_ceros;
		$la_cuenta[8]='305010305'.$ls_ceros;
		$la_cuenta[9]='305010306'.$ls_ceros;
		$la_cuenta[10]='305010307'.$ls_ceros;
		$la_cuenta[11]='305010308'.$ls_ceros;
		$la_cuenta[12]='305010309'.$ls_ceros;
		$la_cuenta[13]='305010500'.$ls_ceros;
		// ---> Ingresos por Actividades Propias 
		$la_cuenta[14]='303010000'.$ls_ceros;
		$la_cuenta[15]='303020000'.$ls_ceros;
        // ---> Otros Ingresos Corrientes
		$la_cuenta[16]='301050000'.$ls_ceros;
		$la_cuenta[17]='301100000'.$ls_ceros;
		$la_cuenta[18]='301100400'.$ls_ceros;
		$la_cuenta[19]='301100500'.$ls_ceros;
		// ---> Otros Ingresos de la Propiedad
		$la_cuenta[20]='306010000';
		$la_cuenta[21]='306020000';
		$la_cuenta[22]='305020100';
		$la_cuenta[23]='305020300';
		$la_cuenta[24]='305020301';
		$la_cuenta[25]='305020302';
		$la_cuenta[26]='305020308';
		$la_cuenta[27]='305020309';
		$la_cuenta[28]='305020500';
		$la_cuenta[29]='305020501';
		$la_cuenta[30]='305020502';
		$la_cuenta[31]='305020503';
		// ---> Ingresos Financieros
		$la_cuenta[32]='307000000';
		$la_cuenta[33]='308000000';
		// ---> Venta de Otras Inversiones Financieras
		$la_cuenta[34]='309000000';
		// ---> Saldo Inicial mas Ingresos
        // ---> Egresos
		$la_cuenta[35]='401000000';
		$la_cuenta[36]='402000000';
		$la_cuenta[37]='403000000';
		$la_cuenta[38]='408000000';
		// ---> Otros Egresos Corrientes
		$la_cuenta[39]='410000000';
		$la_cuenta[40]='407000000';
		$la_cuenta[41]='407010100';
		$la_cuenta[42]='407010300';
		// ---> Otras Transferencias Corrientes Diversas
		$la_cuenta[43]='407030000';
		$la_cuenta[44]='407030100';
		$la_cuenta[45]='407030300';
		$la_cuenta[46]='407020000';
		$la_cuenta[47]='408080000';
		$la_cuenta[48]='404000000';
		$la_cuenta[49]='405000000';
		// ---> Saldo Final
		
	    for($li_i=1;$li_i<=3;$li_i++)
		{
			 $ls_tipo_cuenta="";	$ls_spg_cuenta="";
			 $ls_status="";			$li_nivel="";
			 $ld_varia_abs=0;		$ld_porc_varia=0;
			 $ld_previsto=0;
			 if($li_i==1)
			 {
			   $ls_denominacion="<b>SALDO INICIAL</b>";
			   $ld_monto_programado=$ad_salinipro;
			   $ld_monto_ejecutado=$ad_salinieje;
			   $ls_tipo=1;
			 }
			 if($li_i==2)
			 {
			   $ls_denominacion="<b>INGRESOS</b>";
			   $ld_monto_programado=0;
			   $ld_monto_ejecutado=0;
			   $ls_tipo=2;
			 }
			 if($li_i==3)
			 {
			   $ls_denominacion="<b>INGRESOS CORRIENTES</b>";
			   $ld_monto_programado=0;
			   $ld_monto_ejecutado=0;
			   $ls_tipo=6;
			 }
			 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
			 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
			 $adts_datastore->insertRow("status",$ls_status);
			 $adts_datastore->insertRow("denominacion",$ls_denominacion);
			 $adts_datastore->insertRow("nivel",$li_nivel);
			 $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
			 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
			 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
			 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
			 $adts_datastore->insertRow("previsto",$ld_previsto);
			 $adts_datastore->insertRow("tipo",$ls_tipo);
		}//for	
	    for($li=1;$li<=49;$li++)
		{  //=========================> Incluir encabezado en el datastore
		    $ls_tipo_cuenta="";		 $ls_spg_cuenta="";		 $ls_status="";
			$li_nivel="";   		 $ld_varia_abs=0;        $ls_tipo="";
			$ld_porc_varia=0;		 $ld_previsto=0;         $ld_monto_programado=0;
			$ld_monto_ejecutado=0;
		   switch ($li)
		   {				
			 case 14:
  				  $li_total=$this->dts_ctas_ingreso_actividad->getRowCount("spg_cuenta");
				  $ld_monto_programado_act=0;		  $ld_monto_ejecutado_act=0;	
				  $ld_varia_abs_act=0;	  		      $ld_porc_varia_act=0;		  
				  $ld_previsto_act=0;
				  for($li_i=1;$li_i<=$li_total;$li_i++)
				  {
						$ls_sig_cuenta=$this->dts_ctas_ingreso_actividad->getValue("spg_cuenta",$li_i);	
						$ld_monto_programado=$this->dts_ctas_ingreso_actividad->getValue("monto_programado",$li_i);
						$ld_monto_ejecutado=$this->dts_ctas_ingreso_actividad->getValue("monto_ejecutado",$li_i);
						$ld_varia_abs=$this->dts_ctas_ingreso_actividad->getValue("variacion_absoluta",$li_i);
						$ld_porc_varia=$this->dts_ctas_ingreso_actividad->getValue("porcentaje_variacion",$li_i);
						$ld_previsto=$this->dts_ctas_ingreso_actividad->getValue("previsto",$li_i);
						$ld_monto_programado_act=$ld_monto_programado_act+$ld_monto_programado;
						$ld_monto_ejecutado_act=$ld_monto_ejecutado_act+$ld_monto_ejecutado;	
						$ld_varia_abs_act=$ld_varia_abs_act+$ld_varia_abs;	  
						$ld_porc_varia_act=$ld_porc_varia_act+$ld_porc_varia;		  
				  }//for
			 
   			     $adts_datastore->insertRow("denominacion","<b>Ingresos por Actividades Propias</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado_act);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado_act);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs_act);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia_act);
				 $adts_datastore->insertRow("previsto",$ld_previsto_act);
				 $adts_datastore->insertRow("tipo","14");
			 break;
			 case 16:
				  $li_total=$this->dts_ctas_ingreso->getRowCount("spg_cuenta");
				  $ld_monto_programado_aux=0;		  $ld_monto_ejecutado_aux=0;	
				  $ld_varia_abs_aux=0;	  		      $ld_porc_varia_aux=0;		  
				  $ld_previsto_aux=0;
				  for($li_i=1;$li_i<=$li_total;$li_i++)
				  {
						$ls_sig_cuenta=$this->dts_ctas_ingreso->getValue("spg_cuenta",$li_i);	
						$ld_monto_programado=$this->dts_ctas_ingreso->getValue("monto_programado",$li_i);
						$ld_monto_ejecutado=$this->dts_ctas_ingreso->getValue("monto_ejecutado",$li_i);
						$ld_varia_abs=$this->dts_ctas_ingreso->getValue("variacion_absoluta",$li_i);
						$ld_porc_varia=$this->dts_ctas_ingreso->getValue("porcentaje_variacion",$li_i);
						$ld_previsto=$this->dts_ctas_ingreso->getValue("previsto",$li_i);
						
						$ld_monto_programado_aux=$ld_monto_programado_aux+$ld_monto_programado;
						$ld_monto_ejecutado_aux=$ld_monto_ejecutado_aux+$ld_monto_ejecutado;	
						$ld_varia_abs_aux=$ld_varia_abs_aux+$ld_varia_abs;	  
						$ld_porc_varia_aux=$ld_porc_varia_aux+$ld_porc_varia;		  
				  }//for
   			     $adts_datastore->insertRow("denominacion","<b>Otros Ingresos Corrientes</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado_aux);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado_aux);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs_aux);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia_aux);
				 $adts_datastore->insertRow("previsto",$ld_previsto_aux);
				 $adts_datastore->insertRow("tipo","OIC");
				$li_pos=$adts_datastore->find("tipo","6");
				if($li_pos>0)
				{
				  $adts_datastore->updateRow("monto_programado",$ld_monto_programado_aux,$li_pos);	
				  $adts_datastore->updateRow("monto_ejecutado",$ld_monto_ejecutado_aux,$li_pos);			
				  $adts_datastore->updateRow("variacion_absoluta",$ld_varia_abs_aux,$li_pos);			
				  $adts_datastore->updateRow("porcentaje_variacion",$ld_porc_varia_aux,$li_pos);			
				  $adts_datastore->updateRow("previsto",$ld_previsto_aux,$li_pos);			
				}//if
			 break;
			 case 20:
				 $li_total=$this->dts_ctas_ingreso_propiedad->getRowCount("spg_cuenta");
				 $ld_monto_programado_aux=0;		  $ld_monto_ejecutado_aux=0;	
				 $ld_varia_abs_aux=0;	  		      $ld_porc_varia_aux=0;		  
				 $ld_previsto_aux=0;
				 for($li_i=1;$li_i<=$li_total;$li_i++)
				 {
						$ls_sig_cuenta=$this->dts_ctas_ingreso_propiedad->getValue("spg_cuenta",$li_i);	
						$ld_monto_programado=$this->dts_ctas_ingreso_propiedad->getValue("monto_programado",$li_i);
						$ld_monto_ejecutado=$this->dts_ctas_ingreso_propiedad->getValue("monto_ejecutado",$li_i);
						$ld_varia_abs=$this->dts_ctas_ingreso_propiedad->getValue("variacion_absoluta",$li_i);
						$ld_porc_varia=$this->dts_ctas_ingreso_propiedad->getValue("porcentaje_variacion",$li_i);
						$ld_previsto=$this->dts_ctas_ingreso_propiedad->getValue("previsto",$li_i);
						
						$ld_monto_programado_aux=$ld_monto_programado_aux+$ld_monto_programado;
						$ld_monto_ejecutado_aux=$ld_monto_ejecutado_aux+$ld_monto_ejecutado;	
						$ld_varia_abs_aux=$ld_varia_abs_aux+$ld_varia_abs;	  
						$ld_porc_varia_aux=$ld_porc_varia_aux+$ld_porc_varia;		  
				 }//for
   			     $adts_datastore->insertRow("denominacion","<b>Otros Ingresos de la Propiedad</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado_aux);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado_aux);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs_aux);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia_aux);
				 $adts_datastore->insertRow("previsto",$ld_previsto_aux);
				 $adts_datastore->insertRow("tipo","P");
			 break;
			 case 32:
   			     $adts_datastore->insertRow("denominacion","<b>Ingresos Financieros</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
				 $adts_datastore->insertRow("previsto",$ld_previsto);
				 $adts_datastore->insertRow("tipo",$ls_tipo);
			 break;
			 case 34:
				 $li_total=$this->dts_ctas_ingreso_financieros->getRowCount("spg_cuenta");
				 $ld_monto_programado_fin=0;		  $ld_monto_ejecutado_fin=0;	
				 $ld_varia_abs_fin=0;	  		      $ld_porc_varia_fin=0;		  
				 $ld_previsto_fin=0;
				 for($li_i=1;$li_i<=$li_total;$li_i++)
				 {
						$ls_sig_cuenta=$this->dts_ctas_ingreso_financieros->getValue("spg_cuenta",$li_i);	
						$ld_monto_programado=$this->dts_ctas_ingreso_financieros->getValue("monto_programado",$li_i);
						$ld_monto_ejecutado=$this->dts_ctas_ingreso_financieros->getValue("monto_ejecutado",$li_i);
						$ld_varia_abs=$this->dts_ctas_ingreso_financieros->getValue("variacion_absoluta",$li_i);
						$ld_porc_varia=$this->dts_ctas_ingreso_financieros->getValue("porcentaje_variacion",$li_i);
						$ld_previsto=$this->dts_ctas_ingreso_financieros->getValue("previsto",$li_i);
						
						$ld_monto_programado_fin=$ld_monto_programado_fin+$ld_monto_programado;
						$ld_monto_ejecutado_fin=$ld_monto_ejecutado_fin+$ld_monto_ejecutado;	
						$ld_varia_abs_fin=$ld_varia_abs_fin+$ld_varia_abs;	  
						$ld_porc_varia_fin=$ld_porc_varia_fin+$ld_porc_varia;		  
				 }//for
   			     $adts_datastore->insertRow("denominacion","<b>Venta de Otras Inversiones Financieras</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado_fin);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado_fin);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs_fin);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia_fin);
				 $adts_datastore->insertRow("previsto",$ld_previsto_fin);
				 $adts_datastore->insertRow("tipo","F");
			 break;
			 case 35:
   			     $adts_datastore->insertRow("denominacion","<b>SALDO INICIAL + INGRESOS</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
				 $adts_datastore->insertRow("previsto",$ld_previsto);
				 $adts_datastore->insertRow("tipo","3");
				 
   			     $adts_datastore->insertRow("denominacion","<b>EGRESOS</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
				 $adts_datastore->insertRow("previsto",$ld_previsto);
				 $adts_datastore->insertRow("tipo","E");
			 break;
			 case 39:
				 $li_total=$this->dts_ctas_egresos->getRowCount("spg_cuenta");
				 $ld_monto_programado_egr=0;		  $ld_monto_ejecutado_egr=0;	
				 $ld_varia_abs_egr=0;	  		      $ld_porc_varia_egr=0;		  
				 $ld_previsto_egr=0;
				 for($li_i=1;$li_i<=$li_total;$li_i++)
				 {
						$ls_sig_cuenta=$this->dts_ctas_egresos->getValue("spg_cuenta",$li_i);	
						$ld_monto_programado=$this->dts_ctas_egresos->getValue("monto_programado",$li_i);
						$ld_monto_ejecutado=$this->dts_ctas_egresos->getValue("monto_ejecutado",$li_i);
						$ld_varia_abs=$this->dts_ctas_egresos->getValue("variacion_absoluta",$li_i);
						$ld_porc_varia=$this->dts_ctas_egresos->getValue("porcentaje_variacion",$li_i);
						$ld_previsto=$this->dts_ctas_egresos->getValue("previsto",$li_i);
						$ld_monto_programado_egr=$ld_monto_programado_egr+$ld_monto_programado;
						$ld_monto_ejecutado_egr=$ld_monto_ejecutado_egr+$ld_monto_ejecutado;	
						$ld_varia_abs_egr=$ld_varia_abs_egr+$ld_varia_abs;	  
						$ld_porc_varia_egr=$ld_porc_varia_egr+$ld_porc_varia;		  
				 }//for
   			     $adts_datastore->insertRow("denominacion","<b>Otros Egresos Corrientes</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado_egr);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado_egr);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs_egr);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia_egr);
				 $adts_datastore->insertRow("previsto",$ld_previsto_egr);
				 $adts_datastore->insertRow("tipo","X");
			 break;
			 case 43:
   			     $adts_datastore->insertRow("denominacion","<b>Otras Transferencias Corrientes Diversas</b>");
				 $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				 $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
				 $adts_datastore->insertRow("status",$ls_status);
				 $adts_datastore->insertRow("nivel",$li_nivel);
				 $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
				 $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				 $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
				 $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
				 $adts_datastore->insertRow("previsto",$ld_previsto);
				 $adts_datastore->insertRow("tipo","D");
			 break;
		    }//switch
		   //=========================> Fin sección encabezados en datastore
		  //default:
		   $ls_cuenta=$la_cuenta[$li];
		   //$ls_cuenta=substr($ls_cuenta,0,9);
		   $li_pos=$this->dts_reporte->find("spg_cuenta",$ls_cuenta);
		   if($li_pos>0)
		   { 		
              $ls_tipo_cuenta=$this->dts_reporte->getValue("tipo_cuenta",$li_pos);	
              $ls_spg_cuenta=$this->dts_reporte->getValue("spg_cuenta",$li_pos);	
              $ls_status=$this->dts_reporte->getValue("status",$li_pos);
              $ls_denominacion=$this->dts_reporte->getValue("denominacion",$li_pos);
              $li_nivel=$this->dts_reporte->getValue("nivel",$li_pos);
              $ld_monto_programado=$this->dts_reporte->getValue("monto_programado",$li_pos);
			  $ld_monto_ejecutado=$this->dts_reporte->getValue("monto_ejecutado",$li_pos);
			  $ld_varia_abs=$this->dts_reporte->getValue("variacion_absoluta",$li_pos);
			  $ld_porc_varia=$this->dts_reporte->getValue("porcentaje_variacion",$li_pos);
			  $ld_previsto=$this->dts_reporte->getValue("previsto",$li_pos);
              
			  $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
			  $adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
			  $adts_datastore->insertRow("status",$ls_status);
			  $adts_datastore->insertRow("denominacion",$ls_denominacion);
			  $adts_datastore->insertRow("nivel",$li_nivel);
			  $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
			  $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
			  $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
			  $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
			  $adts_datastore->insertRow("previsto",$ld_previsto);
		      $adts_datastore->insertRow("tipo","");
		   } 
		   else
		   {		
		       $ls_denom="";
			   $lb_valido=$this->uf_spg_reportes_select_denominacion($ls_cuenta,$ls_denom);
			   if($lb_valido)
			   {
				  $ls_tipo_cuenta="";		  $ls_status="";	  $li_nivel="";				  $ld_monto_programado=0;
				  $ld_monto_ejecutado=0;	  $ld_varia_abs=0;	  $ld_porc_varia=0; 		  $ld_previsto=0;
				  $adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
				  $adts_datastore->insertRow("spg_cuenta",$ls_cuenta);
				  $adts_datastore->insertRow("status",$ls_status);
				  $adts_datastore->insertRow("denominacion",$ls_denom);
				  $adts_datastore->insertRow("nivel",$li_nivel);
				  $adts_datastore->insertRow("monto_programado",$ld_monto_programado);
				  $adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				  $adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs);
				  $adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia);
				  $adts_datastore->insertRow("previsto",$ld_previsto);
		          $adts_datastore->insertRow("tipo","");
			   }
		   }//else
		   if($li==42)
		   {
			  $li_total=$this->dts_curft->getRowCount("spg_cuenta");
			  $ld_monto_programado_aux=0;		  $ld_monto_ejecutado_aux=0;	
			  $ld_varia_abs_aux=0;	  			  $ld_porc_varia_aux=0;		  
			  $ld_previsto_aux=0;
			  for($li_i=1;$li_i<=$li_total;$li_i++)
			  {
                $ls_sig_cuenta=$this->dts_curft->getValue("spg_cuenta",$li_i);	
				//$ls_sig_cuenta=substr($ls_sig_cuenta,0,9);
			    $li_pos=$this->dts_reporte->find("spg_cuenta",$ls_sig_cuenta);
				if($li_pos>0)
				{
					$ld_monto_programado=$this->dts_curft->getValue("monto_programado",$li_pos);
					$ld_monto_ejecutado=$this->dts_curft->getValue("monto_ejecutado",$li_pos);
					$ld_varia_abs=$this->dts_curft->getValue("variacion_absoluta",$li_pos);
					$ld_porc_varia=$this->dts_curft->getValue("porcentaje_variacion",$li_pos);
					$ld_previsto=$this->dts_curft->getValue("previsto",$li_pos);
					
					$ld_monto_programado_aux=$ld_monto_programado_aux+$ld_monto_programado;
					$ld_monto_ejecutado_aux=$ld_monto_ejecutado_aux+$ld_monto_ejecutado;	
					$ld_varia_abs_aux=$ld_varia_abs_aux+$ld_varia_abs;	  
					$ld_porc_varia_aux=$ld_porc_varia_aux+$ld_porc_varia;		  
					$ld_previsto_aux=$ld_previsto_aux+$ld_previsto;
				}//if
			  }//for
		   }//if
		}//for
		//---> actualizo las 407 (Otras trasferencias corrientes)
		//$ls_tipo_cuenta="77";
		$li_pos=$adts_datastore->find("tipo","D");
		if($li_pos>0)
		{
		  $adts_datastore->updateRow("monto_programado",$ld_monto_programado_aux,$li_pos);			
		  $adts_datastore->updateRow("monto_ejecutado",$ld_monto_ejecutado_aux,$li_pos);			
		  $adts_datastore->updateRow("variacion_absoluta",$ld_varia_abs_aux,$li_pos);			
		  $adts_datastore->updateRow("porcentaje_variacion",$ld_porc_varia_aux,$li_pos);			
		  $adts_datastore->updateRow("previsto",$ld_previsto_aux,$li_pos);			
		}//if

		//-------------> 	preparo las sumatorias
		$ld_monto_programado_ingreso=0;     $ld_monto_programado_egreso=0;
		$ld_monto_ejecutado_ingreso=0;      $ld_monto_ejecutado_egreso=0;	
		$ld_varia_abs_ingreso=0;	        $ld_varia_abs_egreso=0; 
		$ld_porc_varia_ingreso=0;		    $ld_porc_varia_egreso=0;
		$ld_previsto_ingreso=0;             $ld_previsto_egreso=0; 
		$ld_monto_programado_ingreso_total=0;
		$ld_monto_ejecutado_ingreso_total=0;
		$ld_varia_abs_ingreso_total=0; 
		$ld_porc_varia_aux_total=0; 
		$li_total=$adts_datastore->getRowCount("spg_cuenta");
		for($li_i=1;$li_i<=$li_total;$li_i++)
	    {
			$ls_cuenta_final=$adts_datastore->getValue("spg_cuenta",$li_i);
			$ls_cuenta_final=substr($ls_cuenta_final,0,9);	
			$ld_monto_programado=$adts_datastore->getValue("monto_programado",$li_i);
			$ld_monto_ejecutado=$adts_datastore->getValue("monto_ejecutado",$li_i);
			$ld_varia_abs=$adts_datastore->getValue("variacion_absoluta",$li_i);
			$ld_porc_varia=$adts_datastore->getValue("porcentaje_variacion",$li_i);
			$ld_previsto=$adts_datastore->getValue("previsto",$li_i);
		    //INGRESO
			if(($ls_cuenta_final=='305010000')||($ls_cuenta_final=='303010000')||($ls_cuenta_final=='303020000')||
			   ($ls_cuenta_final=='301050000')||($ls_cuenta_final=='301100000')||($ls_cuenta_final=='306010000')||
			   ($ls_cuenta_final=='306020000')||($ls_cuenta_final=='305020000')||($ls_cuenta_final=='307000000')||
			   ($ls_cuenta_final=='308000000')||($ls_cuenta_final=='309000000'))
			{
				$ld_monto_programado_ingreso=$ld_monto_programado_ingreso+$ld_monto_programado;
				$ld_monto_ejecutado_ingreso=$ld_monto_ejecutado_ingreso+$ld_monto_ejecutado;	
				$ld_varia_abs_ingreso=$ld_varia_abs_ingreso+$ld_varia_abs;	  
				$ld_porc_varia_ingreso=$ld_porc_varia_ingreso+$ld_porc_varia;		  
				$ld_previsto_ingreso=$ld_previsto_ingreso+$ld_previsto;
			}//if 
		   //EGRESO
			if(($ls_cuenta_final=='401000000')||($ls_cuenta_final=='402000000')||($ls_cuenta_final=='403000000')||
			   ($ls_cuenta_final=='408000000')||($ls_cuenta_final=='410000000')||($ls_cuenta_final=='407000000')||
			   ($ls_cuenta_final=='408080000')||($ls_cuenta_final=='404000000')||($ls_cuenta_final=='405000000'))
			{
				$ld_monto_programado_egreso=$ld_monto_programado_egreso+$ld_monto_programado;
				$ld_monto_ejecutado_egreso=$ld_monto_ejecutado_egreso+$ld_monto_ejecutado;	
				$ld_varia_abs_egreso=$ld_varia_abs_egreso+$ld_varia_abs;	  
				$ld_porc_varia_egreso=$ld_porc_varia_egreso+$ld_porc_varia;		  
				$ld_previsto_egreso=$ld_previsto_egreso+$ld_previsto;
			}//if 
		}//for
		//actualiza total ingresos
		$ld_suma=0;
		$ld_suma=$adts_datastore->getValue("monto_ejecutado","3");
		$ld_suma=$ld_suma+$adts_datastore->getValue("monto_ejecutado","17");
		$ld_suma=$ld_suma+$adts_datastore->getValue("monto_ejecutado","25");
		$ld_suma=$ld_suma+$adts_datastore->getValue("monto_ejecutado","41");
		$li_pos=$adts_datastore->find("tipo","2");
		if($li_pos>0)
		{
			$ld_monto_programado=$adts_datastore->getValue("monto_programado",$li_pos);
			$ld_monto_ejecutado=$adts_datastore->getValue("monto_ejecutado",$li_pos);
			$ld_varia_abs=$adts_datastore->getValue("variacion_absoluta",$li_pos);
			$ld_porc_varia=$adts_datastore->getValue("porcentaje_variacion",$li_pos);
			$ld_previsto=$adts_datastore->getValue("previsto",$li_pos);
			
			$ld_monto_programado_ingreso_total=$ld_monto_programado_ingreso_total+$ld_monto_programado; 
			$ld_monto_ejecutado_ingreso_total=$ld_monto_ejecutado_ingreso_total+$ld_monto_ejecutado; 
			$ld_varia_abs_ingreso_total=$ld_varia_abs_ingreso_total+$ld_varia_abs; 
			$ld_porc_varia_aux_total=$ld_porc_varia_aux_total+$ld_porc_varia; 
			
			$adts_datastore->updateRow("monto_programado",$ld_monto_programado_ingreso_total,$li_pos);		
			$adts_datastore->updateRow("monto_ejecutado",$ld_suma,$li_pos);			
			$adts_datastore->updateRow("variacion_absoluta",$ld_varia_abs_ingreso_total,$li_pos);			
			$adts_datastore->updateRow("porcentaje_variacion",$ld_porc_varia_aux_total,$li_pos);			
			$adts_datastore->updateRow("previsto",$ld_previsto_ingreso,$li_pos);			
		}//if
		//actualiza saldo inicial + ingresos
		$li_pos=$adts_datastore->find("tipo","3");
		if($li_pos>0)
		{
		  $ld_monto_programado_saling=$ld_monto_programado_ingreso+$ad_salinipro;
		  $ld_monto_ejecutado_saling=$ld_suma+$ad_salinieje;
		  $ld_porc_varia_saling=$ld_porc_varia_aux+($ad_salinipro-$ad_salinieje);
		  $adts_datastore->updateRow("monto_programado",$ld_monto_programado_saling,$li_pos);			
		  $adts_datastore->updateRow("monto_ejecutado",$ld_monto_ejecutado_saling,$li_pos);			
		  $adts_datastore->updateRow("variacion_absoluta",$ld_varia_abs_ingreso,$li_pos);			
		  $adts_datastore->updateRow("porcentaje_variacion",$ld_porc_varia_saling,$li_pos);			
		  $adts_datastore->updateRow("previsto",$ld_previsto_ingreso,$li_pos);			
		}//if
		//actualiza egreso
		$li_pos=$adts_datastore->find("tipo","E");
		if($li_pos>0)
		{
		  $adts_datastore->updateRow("monto_programado",$ld_monto_programado_egreso,$li_pos);			
		  $adts_datastore->updateRow("monto_ejecutado",$ld_monto_ejecutado_egreso,$li_pos);			
		  $adts_datastore->updateRow("variacion_absoluta",$ld_varia_abs_egreso,$li_pos);			
		  $adts_datastore->updateRow("porcentaje_variacion",$ld_porc_varia_egreso,$li_pos);			
		  $adts_datastore->updateRow("previsto",$ld_previsto_egreso,$li_pos);			
		}//if
		//actualiza saldo final
	    $ls_tipo_cuenta="";		      $ls_status="";	      $li_nivel="";				  $ld_monto_programado_sf=0;
	    $ld_monto_ejecutado_sf=0;	  $ld_varia_abs_sf=0;	  $ld_porc_varia_sf=0; 		  $ld_previsto_sf=0;
		$adts_datastore->insertRow("denominacion","<b>SALDO FINAL BsF.</b>");
		$adts_datastore->insertRow("tipo_cuenta",$ls_tipo_cuenta);
		$adts_datastore->insertRow("spg_cuenta",$ls_spg_cuenta);
		$adts_datastore->insertRow("status",$ls_status);
		$adts_datastore->insertRow("nivel",$li_nivel);
		$adts_datastore->insertRow("monto_programado",$ld_monto_programado_sf);
		$adts_datastore->insertRow("monto_ejecutado",$ld_monto_ejecutado_sf);
		$adts_datastore->insertRow("variacion_absoluta",$ld_varia_abs_sf);
		$adts_datastore->insertRow("porcentaje_variacion",$ld_porc_varia_sf);
		$adts_datastore->insertRow("previsto",$ld_previsto_sf);
		$adts_datastore->insertRow("tipo","5");
		$li_pos=$adts_datastore->find("tipo","5");

		if($li_pos>0)
		{
		  $ld_monto_programado_final=($ld_monto_programado_ingreso+$ad_salinipro)-$ld_monto_programado_egreso;
		  $ld_monto_ejecutado_final=($ld_suma+$ad_salinieje)-$ld_monto_ejecutado_egreso;
		  $ld_varia_abs_final=0;
		  $ld_porc_varia_final=$ld_porc_varia_ingreso+($ad_salinipro-$ad_salinieje)-$ld_porc_varia_egreso;
		  $ld_previsto_final=0;
		  $adts_datastore->updateRow("monto_programado",$ld_monto_programado_final,$li_pos);			
		  $adts_datastore->updateRow("monto_ejecutado",$ld_monto_ejecutado_final,$li_pos);			
		  $adts_datastore->updateRow("variacion_absoluta",$ld_varia_abs_final,$li_pos);			
		  $adts_datastore->updateRow("porcentaje_variacion",$ld_porc_varia_final,$li_pos);			
		  $adts_datastore->updateRow("previsto",$ld_previsto_final,$li_pos);			
		}//if
	  }//if
	  return $lb_valido;
   }//fin uf_spg_reportes_init_array()
/****************************************************************************************************************************************/
    function uf_spg_reportes_llenar_datastore_cuentas($adt_fecdes,$adt_fechas)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_llenar_datastore_cuentas
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //estan cuentas van agrupadas al final 
	  $lb_valido=true;
	  $ls_sql=" SELECT 	curCta.* ".
              " FROM 	(SELECT * ".
	   	      "          FROM  spg_cuentas ".
	  	  	  "          WHERE substring(spg_cuenta,1,5) = '40701') curCta ".
              " WHERE (curCta.spg_cuenta NOT LIKE '4070101%' AND ".
	          "        curCta.spg_cuenta NOT LIKE '407010000%' AND ".
	          "        curCta.spg_cuenta NOT LIKE '4070103%' AND ".
              "        curCta.spg_cuenta <> '407000000' ) AND ".
		      "        substring(curCta.spg_cuenta,9,0)<>'0' ";
			//  print $ls_sql;
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                           MÉTODO->uf_spg_reportes_llenar_datastore_cuentas  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
	  else
	  {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado($ls_codrep,$ls_spg_cuenta,$ld_enero,$ld_febrero,$li_nivel,
												                            $ls_status,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																			$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_monto_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_monto_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
				     $ls_tipo="O";
					 $ld_monto_ejecutado=0;			 $ld_monto_acumulado=0;    
					 $ld_aumdismes=0;				 $ld_aumdisacum=0;
	                 $ld_comprometer=0;				 $ld_causado=0;
					 $ld_pagado=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado($ls_spg_cuenta,$ls_tipo,$ld_monto_ejecutado,
					                                                     $ld_monto_acumulado,$ld_aumdismes,$ld_aumdisacum,
	                                                                     $ld_comprometer,$ld_causado,$ld_pagado,$adt_fecdes,
																		 $adt_fechas,"S");  
					 if($lb_valido)
					 {
						 $ls_tipo_cuenta="Y";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_curft->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_curft->insertRow("spg_cuenta",$ls_spg_cuenta);
						 $this->dts_curft->insertRow("status",$ls_status);
						 $this->dts_curft->insertRow("denominacion",$ls_denominacion);
						 $this->dts_curft->insertRow("nivel",$li_nivel);
						 $this->dts_curft->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_curft->insertRow("monto_ejecutado",$ld_monto_ejecutado);
						 $this->dts_curft->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_curft->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_curft->insertRow("previsto",$ld_monto_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if				   
			   }//if
			}//while
		$this->io_sql->free_result($rs_data);   
	  }//else
    return  $lb_valido;
   }//fin uf_spg_reportes_llenar_datastore_cuentas()
/****************************************************************************************************************************************/
    function uf_spg_reportes_select_denominacion($as_spi_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion FROM sigesp_plan_unico_re WHERE sig_cuenta='".$as_spi_cuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta metodo uf_spg_reportes_select_denominacion ".$this->fun->uf_convertirmsg($this->SQL->message);
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
 }//uf_nombre_mes_desde_hasta
/****************************************************************************************************************************************/
    function uf_spg_reportes_otros_ingresos($ai_nivel,$adt_fecdes,$adt_fechas)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_otros_ingresos
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Flujo de Caja.
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    27/11/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //$this->dts_ctas_ingreso->reset_ds();
	   $lb_valido = true;	 
	   $ls_periodo=$_SESSION["la_empresa"]["periodo"];
	   $ls_sql=" SELECT curCta.* ".
               "  FROM 	(SELECT * ".
	   	       "         FROM  spi_cuentas  ".
	  	       "         WHERE substring(spi_cuenta,1,3) = '303') curCta ".
               " WHERE (curCta.spi_cuenta NOT LIKE '30301%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '30302%' AND ".
		       "        substring(curCta.spi_cuenta,9,0)<>'0')  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  MÉTODO->uf_spg_reportes_otros_ingresos  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado_ingreso($ls_codrep,$ls_spi_cuenta,$ld_enero,$ld_febrero,
			                                                                        $li_nivel,$ls_status,$ld_marzo,$ld_abril,$ld_mayo,
																					$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,
																					$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
					 $ld_prev=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_otros_ingreso($ls_spi_cuenta,$ld_prev,$ld_devengado,
					                                                             $ld_cobrado,$ld_cobrado_anticipado,$ld_aumento,
	                                                                             $ld_disminucion,$adt_fecdes,$adt_fechas);  
					 
					if($lb_valido)
					{	
						 $ls_tipo_cuenta="I";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_ctas_ingreso->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_ctas_ingreso->insertRow("spg_cuenta",$ls_spi_cuenta);
						 $this->dts_ctas_ingreso->insertRow("status",$ls_status);
						 $this->dts_ctas_ingreso->insertRow("denominacion",$ls_denominacion);
						 $this->dts_ctas_ingreso->insertRow("nivel",$li_nivel);
						 $this->dts_ctas_ingreso->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_ctas_ingreso->insertRow("monto_ejecutado",$ld_cobrado);
						 $this->dts_ctas_ingreso->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_ctas_ingreso->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_ctas_ingreso->insertRow("previsto",$ld_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if			   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
      return $lb_valido;
   }//fin uf_spg_reportes_otros_ingresos
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado_otros_ingreso($as_spi_cuenta,&$ad_previsto,&$ad_devengado,&$ad_cobrado,&$ad_cobrado_anticipado,
	                                                         &$ad_aumento,&$ad_disminucion,$adt_fecini,$adt_fecfin)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado_otros_ingreso
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $ad_previsto  //  monto previsto (referencia)
	 //                     $ad_devengado  //  monto  devengado (referencia)
     //              	    $ad_cobrado  // monto cobrado (referencia)
     //              	    $ad_cobrado_anticipado  // cobrado anticipado (referencia)
	 //                     $ad_aumento  //  monto aumento (referencia)
	 //                     $ad_disminucion  // monto disminucion (referencia)
	 //                     $adt_fecini  // fecha inicio
	 //                     $adt_fecfin  // fecha fin
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para el Formato 3 de la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    25/08/2006         Fecha última Modificacion :      Hora :
  	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ad_previsto=0;	          $ad_devengado=0;
	  $ad_cobrado=0;	          $ad_cobrado_anticipado=0;
	  $ad_aumento=0;	          $ad_disminucion=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $l_mesdes=substr($adt_fecini,3,2);
	  $l_meshas=substr($adt_fecfin,3,2);
	  $li_mesdes=intval($l_mesdes);
	  $li_meshas=intval($l_meshas);
	  $ldt_mesdes=$li_ano."-".$this->io_function->uf_cerosizquierda($li_mesdes,2);
	  $ldt_meshas=$li_ano."-".$this->io_function->uf_cerosizquierda($li_meshas,2);
	  //$as_spi_cuenta=$this->sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	  $ls_sql=" SELECT operacion,montoaux as monto,fecha ".
			  " FROM   spi_dt_cmp ".
			  " WHERE  codemp='".$this->ls_codemp."'  AND  spi_cuenta like '".$as_spi_cuenta."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  MÉTODO->uf_spg_reporte_calcular_ejecutado_otros_ingreso  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_operacion=$row["operacion"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,7);
		  $ldt_fecha=str_replace("-","",$ldt_fecha);
		  $ldt_mesdes=str_replace("-","",$ldt_mesdes);
		  $ldt_meshas=str_replace("-","",$ldt_meshas);
		  $ls_opera=$this->sigesp_int_spi->uf_operacion_codigo_mensaje($ls_operacion);
		  $ls_mensaje=strtoupper($ls_opera); // devuelve cadena en MAYUSCULAS
		  $li_pos_i=strpos($ls_mensaje,"I"); 
		  if (!($li_pos_i===false)) 
		  { 
		    $ad_previsto=$ad_previsto+$ld_monto; 
		  }
		   
		  if(($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
		  {
			  $li_pos_e=strpos($ls_mensaje,"E"); 
			  if (!($li_pos_e===false)) 
			  { 
				 $ad_devengado=$ad_devengado+$ld_monto;
			  }
			  $li_pos_c=strpos($ls_mensaje,"C"); 
			  if (!($li_pos_c===false)) 
			  {	
				 $ad_cobrado=$ad_cobrado+$ld_monto;
			  }
			  $li_pos_n=strpos($ls_mensaje,"N"); 
			  if (!($li_pos_n===false))
			  {	
			    $ad_cobrado_anticipado = $ad_cobrado_anticipado+$ld_monto; 
			  }
			  $li_pos_a=strpos($ls_mensaje,"A"); 
			  if (!($li_pos_a===false))
			  {	
			    $ad_aumento = $ad_aumento+$ld_monto; 
			  }
			  $li_pos_d=strpos($ls_mensaje,"D"); 
			  if (!($li_pos_d===false))
			  {	
			    $ad_disminucion = $ad_disminucion+$ld_monto; 
			  }
	          $lb_valido = true;
		  }//if
		}//while
	   $this->io_sql->free_result($rs_data);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado_otros_ingreso
/****************************************************************************************************************************************/
    function uf_spg_reportes_otros_ingresos_propiedad($ai_nivel,$adt_fecdes,$adt_fechas)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_otros_ingresos_propiedad
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Flujo de Caja.
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    29/11/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //$this->dts_ctas_ingreso_propiedad->reset_ds();
	   $lb_valido = true;	 
	   $ls_periodo=$_SESSION["la_empresa"]["periodo"];
	   $ls_sql=" SELECT curCta.* ".
               "  FROM 	(SELECT * ".
	   	       "         FROM  spi_cuentas  ".
	  	       "         WHERE substring(spi_cuenta,1,3) = '301') curCta ".
               " WHERE (curCta.spi_cuenta NOT LIKE '3011000%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '3011004%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '3011005%' AND ".
		       "        substring(curCta.spi_cuenta,9,0)<>'0')  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  MÉTODO->uf_spg_reportes_otros_ingresos_propiedad  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado_ingreso($ls_codrep,$ls_spi_cuenta,$ld_enero,$ld_febrero,
			                                                                        $li_nivel,$ls_status,$ld_marzo,$ld_abril,$ld_mayo,
																					$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,
																					$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
					 $ld_prev=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_otros_ingreso($ls_spi_cuenta,$ld_prev,$ld_devengado,
					                                                             $ld_cobrado,$ld_cobrado_anticipado,$ld_aumento,
	                                                                             $ld_disminucion,$adt_fecdes,$adt_fechas);  
					 
					if($lb_valido)
					{	
						 $ls_tipo_cuenta="I";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_ctas_ingreso_propiedad->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_ctas_ingreso_propiedad->insertRow("spg_cuenta",$ls_spi_cuenta);
						 $this->dts_ctas_ingreso_propiedad->insertRow("status",$ls_status);
						 $this->dts_ctas_ingreso_propiedad->insertRow("denominacion",$ls_denominacion);
						 $this->dts_ctas_ingreso_propiedad->insertRow("nivel",$li_nivel);
						 $this->dts_ctas_ingreso_propiedad->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_ctas_ingreso_propiedad->insertRow("monto_ejecutado",$ld_cobrado);
						 $this->dts_ctas_ingreso_propiedad->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_ctas_ingreso_propiedad->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_ctas_ingreso_propiedad->insertRow("previsto",$ld_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if			   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
      return $lb_valido;
   }//fin uf_spg_reportes_otros_ingresos_propiedad
/****************************************************************************************************************************************/
    function uf_spg_reportes_ingresos_actividades_propias($ai_nivel,$adt_fecdes,$adt_fechas)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_ingresos_actividades_propias
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Flujo de Caja.
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    30/11/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //$this->dts_ctas_ingreso_actividad->reset_ds();
	   $lb_valido = true;	 
	   $ls_formspi=str_replace("-","",$_SESSION["la_empresa"]["formspi"]);
	   $ls_sql=" SELECT curCta.* ".
               "  FROM 	(SELECT * ".
	   	       "         FROM  spi_cuentas  ".
	  	       "         WHERE substring(spi_cuenta,1,3) = '305') curCta ".
               " WHERE (curCta.spi_cuenta NOT LIKE '30501%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '3050101%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '3050103%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010301%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010302%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010303%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010304%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010305%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010306%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010307%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010308%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '305010309%' AND ".
	           "        curCta.spi_cuenta NOT LIKE '3050105%' AND ".
		       "        substring(curCta.spi_cuenta,9,0)<>'0')  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                           MÉTODO->uf_spg_reportes_ingresos_actividades_propias  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado_ingreso($ls_codrep,$ls_spi_cuenta,$ld_enero,$ld_febrero,
			                                                                        $li_nivel,$ls_status,$ld_marzo,$ld_abril,$ld_mayo,
																					$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,
																					$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
					 $ld_prev=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_otros_ingreso($ls_spi_cuenta,$ld_prev,$ld_devengado,
					                                                             $ld_cobrado,$ld_cobrado_anticipado,$ld_aumento,
	                                                                             $ld_disminucion,$adt_fecdes,$adt_fechas);  
					 
					if($lb_valido)
					{	
						 $ls_tipo_cuenta="I";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_ctas_ingreso_actividad->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_ctas_ingreso_actividad->insertRow("spg_cuenta",$ls_spi_cuenta);
						 $this->dts_ctas_ingreso_actividad->insertRow("status",$ls_status);
						 $this->dts_ctas_ingreso_actividad->insertRow("denominacion",$ls_denominacion);
						 $this->dts_ctas_ingreso_actividad->insertRow("nivel",$li_nivel);
						 $this->dts_ctas_ingreso_actividad->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_ctas_ingreso_actividad->insertRow("monto_ejecutado",$ld_cobrado);
						 $this->dts_ctas_ingreso_actividad->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_ctas_ingreso_actividad->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_ctas_ingreso_actividad->insertRow("previsto",$ld_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if			   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
      return $lb_valido;
   }//fin uf_spg_reportes_ingresos_actividades_propias
/****************************************************************************************************************************************/	
    function uf_spg_reportes_ingresos_financieros($ai_nivel,$adt_fecdes,$adt_fechas)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_ingresos_financieros
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Flujo de Caja.
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    30/11/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //$this->dts_ctas_ingreso_financieros->reset_ds();
	   $lb_valido = true;	 
	   $ls_formspi=str_replace("-","",$_SESSION["la_empresa"]["formspi"]);
	   $ls_sql=" SELECT curCta.* ".
               " FROM 	(SELECT * ".
 	   	       "         FROM  spi_cuentas ".
	  	       "         WHERE substring(spi_cuenta,1,3) = '307' OR ".
               "               substring(spi_cuenta,1,3) = '308') curCta ".
               " WHERE (curCta.spi_cuenta NOT LIKE '30700000%'  AND ".
               "        curCta.spi_cuenta NOT LIKE '30800000%'  AND ".
               "        substring(curCta.spi_cuenta,9,0)<>'0')  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                           MÉTODO->uf_spg_reportes_ingresos_financieros  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spi_cuenta=$row["spi_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado_ingreso($ls_codrep,$ls_spi_cuenta,$ld_enero,$ld_febrero,
			                                                                        $li_nivel,$ls_status,$ld_marzo,$ld_abril,$ld_mayo,
																					$ld_junio,$ld_julio,$ld_agosto,$ld_septiembre,
																					$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
					 $ld_prev=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado_otros_ingreso($ls_spi_cuenta,$ld_prev,$ld_devengado,
					                                                             $ld_cobrado,$ld_cobrado_anticipado,$ld_aumento,
	                                                                             $ld_disminucion,$adt_fecdes,$adt_fechas);  
					 
					if($lb_valido)
					{	
						 $ls_tipo_cuenta="I";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_ctas_ingreso_financieros->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_ctas_ingreso_financieros->insertRow("spg_cuenta",$ls_spi_cuenta);
						 $this->dts_ctas_ingreso_financieros->insertRow("status",$ls_status);
						 $this->dts_ctas_ingreso_financieros->insertRow("denominacion",$ls_denominacion);
						 $this->dts_ctas_ingreso_financieros->insertRow("nivel",$li_nivel);
						 $this->dts_ctas_ingreso_financieros->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_ctas_ingreso_financieros->insertRow("monto_ejecutado",$ld_cobrado);
						 $this->dts_ctas_ingreso_financieros->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_ctas_ingreso_financieros->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_ctas_ingreso_financieros->insertRow("previsto",$ld_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if			   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
      return $lb_valido;
   }//fin uf_spg_reportes_ingresos_financieros
/****************************************************************************************************************************************/	
    function uf_spg_reportes_otros_egresos_corrientes($adt_fecdes,$adt_fechas)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_otros_egresos_corrientes
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Flujo de Caja.
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    01/12/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //$this->dts_ctas_egresos->reset_ds();
	  $lb_valido = true;	 
	  $ls_sql= " SELECT curCta.* ".
               " FROM 	(SELECT * ".
      	   	   "         FROM  spg_cuentas ".
	  	       "         WHERE substring(spg_cuenta,1,3) = '408') curCta ".
               " WHERE (curCta.spg_cuenta NOT LIKE '408000000%' AND ".
               "        substring(curCta.spg_cuenta,9,0)<>'0') ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
		   $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0717  
		                           MÉTODO->uf_spg_reportes_otros_egresos_corrientes  
								   ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   $lb_valido = false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
               $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_denominacion=$row["denominacion"];
               $ld_enero=0;		   $ld_febrero=0;
               $ld_marzo=0;		   $ld_abril=0;
               $ld_mayo=0;		   $ld_junio=0;
			   $ld_julio=0;		   $ld_agosto=0;
			   $ld_septiembre=0;   $ld_octubre=0;
			   $ld_noviembre=0;	   $ld_diciembre=0;
               $ls_codrep="00005"; $li_nivel="";   $ls_status="";
			   $lb_valido=$this->uf_spg_reporte_llenar_datastore_programado($ls_codrep,$ls_spg_cuenta,$ld_enero,$ld_febrero,$li_nivel,
												                            $ls_status,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																			$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre);
			   if($lb_valido)
			   {
				  $li_mesdes=substr($adt_fecdes,3,2);
				  $li_meshas=substr($adt_fechas,3,2);
				  $ld_monto_programado=0;
				  $ld_monto_previsto=0;
  				  $lb_valido=$this->uf_spg_reporte_calcular_programado($li_mesdes,$li_meshas,$ld_monto_programado,$ld_monto_previsto,
				                                                       $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				  if($lb_valido)
				  {
				     $ls_tipo="O";
					 $ld_monto_ejecutado=0;			 $ld_monto_acumulado=0;    
					 $ld_aumdismes=0;				 $ld_aumdisacum=0;
	                 $ld_comprometer=0;				 $ld_causado=0;
					 $ld_pagado=0;
					 $lb_valido=$this->uf_spg_reporte_calcular_ejecutado($ls_spg_cuenta,$ls_tipo,$ld_monto_ejecutado,
					                                                     $ld_monto_acumulado,$ld_aumdismes,$ld_aumdisacum,
	                                                                     $ld_comprometer,$ld_causado,$ld_pagado,$adt_fecdes,
																		 $adt_fechas,"S");  
					 if($lb_valido)
					 {
						 $ls_tipo_cuenta="Y";
						 $ld_varia_abs=0;
						 $ld_porc_varia=0;
						 $this->dts_ctas_egresos->insertRow("tipo_cuenta",$ls_tipo_cuenta);
						 $this->dts_ctas_egresos->insertRow("spg_cuenta",$ls_spg_cuenta);
						 $this->dts_ctas_egresos->insertRow("status",$ls_status);
						 $this->dts_ctas_egresos->insertRow("denominacion",$ls_denominacion);
						 $this->dts_ctas_egresos->insertRow("nivel",$li_nivel);
						 $this->dts_ctas_egresos->insertRow("monto_programado",$ld_monto_programado);
						 $this->dts_ctas_egresos->insertRow("monto_ejecutado",$ld_monto_ejecutado);
						 $this->dts_ctas_egresos->insertRow("variacion_absoluta",$ld_varia_abs);
						 $this->dts_ctas_egresos->insertRow("porcentaje_variacion",$ld_porc_varia);
						 $this->dts_ctas_egresos->insertRow("previsto",$ld_monto_previsto);
				         $lb_valido=true;
					 }//if  
				  }//if				   
			   }//if
			}//while
		  $this->io_sql->free_result($rs_data);
	     }//else
      return $lb_valido;
   }//if uf_spg_reportes_otros_egresos_corrientes
/****************************************************************************************************************************************/	
}//fin de clase
?>