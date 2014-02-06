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
class sigesp_spi_reporte_instructivo_07
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
	var $sigesp_int_spi;
	var $dts_prog;
/****************************************************************************************************************************************/	
    function  sigesp_spi_reporte_instructivo_07()
    {
		$this->fun=new class_funciones() ;
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($this->con);		
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->dts_reporte=new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int_spi=new class_sigesp_int_spi();
    }
/********************************************************************************************************************************/
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//                                                 CLASE REPORTES SPI  INSTRUCTIVO NRO 07"                                  //
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_spi_reportes_ejecucion_trimestral($adt_fecdes,$adt_fechas)
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
				
				$ls_sql=" SELECT C.spi_cuenta, max(C.nivel) as nivel, max(C.denominacion) as denominacion, ".
				"        sum(C.previsto) as previsto, sum(C.enero) as enero, sum(C.febrero) as febrero, ".
				"        sum(C.marzo) as marzo, sum(C.abril) as abril, sum(C.mayo) as mayo,  ".
				"        sum(C.junio) as junio, sum(C.julio) as julio, sum(C.agosto) as agosto,  ".
				"        sum(C.septiembre) as septiembre, sum(C.octubre) as octubre,  ".
				"        sum(C.noviembre) as noviembre, sum(C.diciembre) as diciembre, MAX(status) as status ".
			    " FROM   spi_cuentas C ".
			    " WHERE  C.codemp='".$ls_codemp."' ".
			    " GROUP BY C.spi_cuenta ".
       			" ORDER BY C.spi_cuenta  ";		
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spi_reportes_ejecucion_trimestral".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
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
			   $ls_status=$row["diciembre"];
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
			 $this->SQL->free_result($rs_data);
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
	  $as_spi_cuenta=$this->sigesp_int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.monto, OP.previsto, OP.aumento, OP.disminucion, OP.devengado,OP.cobrado, OP.reservado     ".
              " FROM   spi_dt_cmp DT, spi_operaciones OP ".
              " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
              "        spi_cuenta like '".$as_spi_cuenta."' AND DT.fecha <= '".$ai_meshas."'".
			  " ORDER BY DT.fecha";
	  //print $ls_sql."<br>";
	  $rs_ejec=$this->SQL->select($ls_sql);
	  if($rs_ejec===false)
	  { // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spi_reporte_calcular_ejecutado_trimestre".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->SQL->fetch_row($rs_ejec))
		{
		  $li_previsto=$row["previsto"];
		  $li_aumento=$row["aumento"];
		  $li_disminucion=$row["disminucion"];
		  $li_devengado=$row["devengado"];
		  $li_cobrado=$row["cobrado"];
		  $li_reservado=$row["reservado"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,7);
	      
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
		$ad_aumdismes=$ld_aumento-$ld_disminucion;
	   $this->SQL->free_result($rs_ejec);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spi_reporte_calcular_ejecutado_trimestre
/****************************************************************************************************************************************/	


}//fin de clase
?>