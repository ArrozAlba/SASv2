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
class sigesp_scg_reporte_comparado_0714
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_function;	
	var $io_msg;
	var $io_fecha;
	var $sigesp_int;
	var $sigesp_int_scg;
	var $sigesp_int_spg;
	var $dts_reporte_final;
	var $dts_scg_cuentas;
	var $dts_reporte_prestamo;
	var $dts_reporte_venta;
	var $dts_spg_cuentas;
	var $dts_spi_cuentas;
	var $int_spi;
/**********************************************************************************************************************************/	
    function  sigesp_scg_reporte_comparado_0714()
    {
		$this->io_function=new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->dts_reporte_final=new class_datastore();
		$this->dts_scg_cuentas=new class_datastore();
		$this->dts_spg_cuentas=new class_datastore();
		$this->dts_spi_cuentas=new class_datastore();
		$this->dts_reporte_prestamo=new class_datastore();
		$this->dts_reporte_venta=new class_datastore();
		$this->io_fecha = new class_fecha();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int=new class_sigesp_int();
		$this->sigesp_int_scg=new class_sigesp_int_scg();
		$this->sigesp_int_spg=new class_sigesp_int_spg();
		$this->int_spi=new class_sigesp_int_spi();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
    }
/**********************************************************************************************************************************/
	////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  COMPARADOS " INVERSIONES    "                   //
	///////////////////////////////////////////////////////////////////////////
    function uf_scg_reportes_comparados_inversiones_0714($adt_fecdes,$adt_fechas,$ai_mesdes,$ai_meshas,$ai_cant_mes)
    { //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_comparados_inversiones_0714
	  //        Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	  //                        as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    01/09/2006                       Fecha última Modificacion :      Hora :
  	  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_valido=$this->uf_scg_reportes_init_array();
	  if($lb_valido)
	  {
		  $li_total=$this->dts_reporte_final->getRowCount("sc_cuenta");
		  for($li_i=1;$li_i<=$li_total;$li_i++)
		  {
			$ls_sc_cuenta=$this->dts_reporte_final->getValue("sc_cuenta",$li_i);
			
			$ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_i);	
			if($ls_tipo==1)
			{
			  
			    $lb_valido=$this->uf_scg_reportes_procesar_cuentas($ls_sc_cuenta,$adt_fecdes,$adt_fechas,$ai_mesdes,$ai_meshas,
				                                                   $ai_cant_mes);
			}//if
			if($ls_tipo==2)
			{
				$lb_valido=$this->uf_spg_reportes_procesar_cuentas($ls_sc_cuenta,$adt_fecdes,$adt_fechas,$ai_mesdes,$ai_meshas,
				                                                   $ai_cant_mes);
			}//if
			if($ls_tipo==3)
			{
  			    $lb_valido=$this->uf_spi_reportes_procesar_cuentas($ls_sc_cuenta,$adt_fecdes,$adt_fechas,$ai_mesdes,$ai_meshas,
				                                                   $ai_cant_mes);
			}//if
		  }//for
		  if($lb_valido)
		  {
	         $lb_valido=$this->uf_scg_reportes_organizar_datastore();  
		  }	
	  }//if
    return $lb_valido;
   }//fin uf_scg_reportes_comparados_inversiones_0714
/**********************************************************************************************************************************/
    function uf_scg_reportes_organizar_datastore()
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_organizar_datastore
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del inversiones
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    05/09/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $li_total=$this->dts_reporte_final->getRowCount("sc_cuenta");
	  for($li_i=1;$li_i<=$li_total;$li_i++)
	  {
		$ls_sc_cuenta=$this->dts_reporte_final->getValue("sc_cuenta",$li_i);
		if(($ls_sc_cuenta=='306010000')||($ls_sc_cuenta=='306020000')||($ls_sc_cuenta=='307000000')||($ls_sc_cuenta=='308000000'))
		{
			 $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_i);
			 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_i);   
			 $ls_status=$this->dts_reporte_final->getValue("status",$li_i);  
			 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_i);
			 $ld_monto_programado=$this->dts_reporte_final->getValue("monto_programado",$li_i);
			 $ld_monto_programado_acumulado=$this->dts_reporte_final->getValue("programado_acumulado",$li_i);
			 $ld_monto_ejecutado=$this->dts_reporte_final->getValue("monto_ejecutado",$li_i);
			 $ld_monto_ejecutado_acumulado=$this->dts_reporte_final->getValue("ejecutado_acumulado",$li_i);
			 $ld_variacion_absoluta=$this->dts_reporte_final->getValue("variacion_absoluta",$li_i);
			 $ld_porcentaje_variacion=$this->dts_reporte_final->getValue("porcentaje_variacion",$li_i);
			 $ld_variacion_absoluta_acumulada=$this->dts_reporte_final->getValue("variacion_absoluta_acumulada",$li_i);
			 $ld_porcentaje_variacion_acumulada=$this->dts_reporte_final->getValue("porcentaje_variacion_acumulado",$li_i);
			 $ld_reprog_proxima=$this->dts_reporte_final->getValue("reprogr_prox_periodo",$li_i);
		
			 $this->dts_reporte_venta->insertRow("sc_cuenta",$ls_sc_cuenta);
			 $this->dts_reporte_venta->insertRow("denominacion",$ls_denominacion);
			 $this->dts_reporte_venta->insertRow("tipo",$ls_tipo);
			 $this->dts_reporte_venta->insertRow("status",$ls_status);
			 $this->dts_reporte_venta->insertRow("nivel",$li_nivel);
			 $this->dts_reporte_venta->insertRow("monto_programado",$ld_monto_programado);
			 $this->dts_reporte_venta->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
			 $this->dts_reporte_venta->insertRow("monto_ejecutado",$ld_monto_ejecutado);
			 $this->dts_reporte_venta->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
			 $this->dts_reporte_venta->insertRow("variacion_absoluta",$ld_variacion_absoluta);
			 $this->dts_reporte_venta->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
			 $this->dts_reporte_venta->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
			 $this->dts_reporte_venta->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
			 $this->dts_reporte_venta->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
			 $lb_valido=true;
		}//if
		elseif(($ls_sc_cuenta=='309000000')||($ls_sc_cuenta=='310000000'))
		{
			 $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_i);
			 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_i);   
			 $ls_status=$this->dts_reporte_final->getValue("status",$li_i);  
			 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_i);
			 $ld_monto_programado=$this->dts_reporte_final->getValue("monto_programado",$li_i);
			 $ld_monto_programado_acumulado=$this->dts_reporte_final->getValue("programado_acumulado",$li_i);
			 $ld_monto_ejecutado=$this->dts_reporte_final->getValue("monto_ejecutado",$li_i);
			 $ld_monto_ejecutado_acumulado=$this->dts_reporte_final->getValue("ejecutado_acumulado",$li_i);
			 $ld_variacion_absoluta=$this->dts_reporte_final->getValue("variacion_absoluta",$li_i);
			 $ld_porcentaje_variacion=$this->dts_reporte_final->getValue("porcentaje_variacion",$li_i);
			 $ld_variacion_absoluta_acumulada=$this->dts_reporte_final->getValue("variacion_absoluta_acumulada",$li_i);
			 $ld_porcentaje_variacion_acumulada=$this->dts_reporte_final->getValue("porcentaje_variacion_acumulado",$li_i);
			 $ld_reprog_proxima=$this->dts_reporte_final->getValue("reprogr_prox_periodo",$li_i);
		
			 $this->dts_reporte_prestamo->insertRow("sc_cuenta",$ls_sc_cuenta);
			 $this->dts_reporte_prestamo->insertRow("denominacion",$ls_denominacion);
			 $this->dts_reporte_prestamo->insertRow("tipo",$ls_tipo);
			 $this->dts_reporte_prestamo->insertRow("status",$ls_status);
			 $this->dts_reporte_prestamo->insertRow("nivel",$li_nivel);
			 $this->dts_reporte_prestamo->insertRow("monto_programado",$ld_monto_programado);
			 $this->dts_reporte_prestamo->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
			 $this->dts_reporte_prestamo->insertRow("monto_ejecutado",$ld_monto_ejecutado);
			 $this->dts_reporte_prestamo->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
			 $this->dts_reporte_prestamo->insertRow("variacion_absoluta",$ld_variacion_absoluta);
			 $this->dts_reporte_prestamo->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
			 $this->dts_reporte_prestamo->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
			 $this->dts_reporte_prestamo->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
			 $this->dts_reporte_prestamo->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
			 $lb_valido=true;
	    }//elseif
		else
		{
			 $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_i);
			 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_i);   
			 $ls_status=$this->dts_reporte_final->getValue("status",$li_i);  
			 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_i);
			 $ld_monto_programado=$this->dts_reporte_final->getValue("monto_programado",$li_i);
			 $ld_monto_programado_acumulado=$this->dts_reporte_final->getValue("programado_acumulado",$li_i);
			 $ld_monto_ejecutado=$this->dts_reporte_final->getValue("monto_ejecutado",$li_i);
			 $ld_monto_ejecutado_acumulado=$this->dts_reporte_final->getValue("ejecutado_acumulado",$li_i);
			 $ld_variacion_absoluta=$this->dts_reporte_final->getValue("variacion_absoluta",$li_i);
			 $ld_porcentaje_variacion=$this->dts_reporte_final->getValue("porcentaje_variacion",$li_i);
			 $ld_variacion_absoluta_acumulada=$this->dts_reporte_final->getValue("variacion_absoluta_acumulada",$li_i);
			 $ld_porcentaje_variacion_acumulada=$this->dts_reporte_final->getValue("porcentaje_variacion_acumulado",$li_i);
			 $ld_reprog_proxima=$this->dts_reporte_final->getValue("reprogr_prox_periodo",$li_i);
		
			 $this->dts_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
			 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			 $this->dts_reporte->insertRow("tipo",$ls_tipo);
			 $this->dts_reporte->insertRow("status",$ls_status);
			 $this->dts_reporte->insertRow("nivel",$li_nivel);
			 $this->dts_reporte->insertRow("monto_programado",$ld_monto_programado);
			 $this->dts_reporte->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
			 $this->dts_reporte->insertRow("monto_ejecutado",$ld_monto_ejecutado);
			 $this->dts_reporte->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
			 $this->dts_reporte->insertRow("variacion_absoluta",$ld_variacion_absoluta);
			 $this->dts_reporte->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
			 $this->dts_reporte->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
			 $this->dts_reporte->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
			 $this->dts_reporte->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
			 $lb_valido=true;
		}//else
	  }//for	
      return $lb_valido;	
	}// fin uf_scg_reportes_organizar_datastore
/*******************************************************************************************************************************/
    function uf_scg_reportes_init_array()
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_init_array
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del inversiones
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    01/09/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  if($lb_valido)
	  {
	    $la_cuenta=array();
		$la_cuenta[1]='121000000';
		$la_cuenta[2]='121010000';
		$la_cuenta[3]='121020000';
		$la_cuenta[4]='121030000';
		$la_cuenta[5]='123000000';
		$la_cuenta[6]='123010000';
		$la_cuenta[7]='123010100';
		$la_cuenta[8]='123010200';
		$la_cuenta[9]='123010300';
		$la_cuenta[10]='123010400';
		$la_cuenta[11]='123010500';
		$la_cuenta[12]='123010600';
		$la_cuenta[13]='123010700';
		$la_cuenta[14]='123010800';
		$la_cuenta[15]='123010900';
		$la_cuenta[16]='123011900';
		$la_cuenta[17]='123020000';
		$la_cuenta[18]='123050000';
		$la_cuenta[19]='123050100';
		$la_cuenta[20]='123050200';
		$la_cuenta[21]='124000000';
		$la_cuenta[22]='124010000';
		$la_cuenta[23]='124020000';
		$la_cuenta[24]='124030000';
		$la_cuenta[25]='124040000';
		$la_cuenta[26]='124050000';
		$la_cuenta[27]='124190000';
		$la_cuenta[28]='401000000';
		$la_cuenta[29]='402000000';
		$la_cuenta[30]='403000000';
		$la_cuenta[31]='407000000';
		$la_cuenta[32]='408000000';
		$la_cuenta[33]='306010000';
		$la_cuenta[34]='306020000';
		$la_cuenta[35]='307000000';
		$la_cuenta[36]='308000000';
		$la_cuenta[37]='309000000';
		$la_cuenta[38]='310000000';
	    
	    for($li=1;$li<=38;$li++)
		{  //=========================> Incluir encabezado en el datastore
		   
		   $ls_sc_cuenta="";		 				  
   		   $ld_monto_programado=0;		   			$ld_monto_programado_acumulado=0;
		   $ld_monto_ejecutado=0; 		 		    $ld_monto_ejecutado_acumulado=0;	
		   $ld_variacion_absoluta=0;	   			$ld_porcentaje_variacion=0;
		   $ld_variacion_absoluta_acumulada=0;	    $ld_porcentaje_variacion_acumulada=0;
		   $ld_reprog_proxima=0;					$ls_tipo="";   $ls_status="";  $li_nivel="";   			 
		   if($li==28)
		   {
   			     $this->dts_reporte_final->insertRow("denominacion","Conservaciones, Ampliaciones y Mejoras");
				 $this->dts_reporte_final->insertRow("tipo","0");
				 $this->dts_reporte_final->insertRow("sc_cuenta",$ls_sc_cuenta);
				 $this->dts_reporte_final->insertRow("status",$ls_status);
				 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
				 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
				 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
				 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
				 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
				 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
				 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
				 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
				 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);

   			     $this->dts_reporte_final->insertRow("denominacion","Gastos Capitalizables");
				 $this->dts_reporte_final->insertRow("tipo","0");
				 $this->dts_reporte_final->insertRow("sc_cuenta",$ls_sc_cuenta);
				 $this->dts_reporte_final->insertRow("status",$ls_status);
				 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
				 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
				 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
				 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
				 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
				 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
				 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
				 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
				 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
				 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
		   }
		   //=========================> Fin sección encabezados en datastore
		   $ls_cuenta_arreglo=$la_cuenta[$li];
		   $ls_denom="";
		   $lb_valido=$this->uf_scg_reportes_select_denominacion($ls_cuenta_arreglo,$ls_denom);
		   if($lb_valido)
		   {
				 $ld_monto_programado=0;		   			$ld_monto_programado_acumulado=0;
				 $ld_monto_ejecutado=0; 		 		    $ld_monto_ejecutado_acumulado=0;	
				 $ld_variacion_absoluta=0;	   			    $ld_porcentaje_variacion=0;
				 $ld_variacion_absoluta_acumulada=0;	    $ld_porcentaje_variacion_acumulada=0;
				 $ld_reprog_proxima=0;					    $ls_tipo="";   $ls_status="";  $li_nivel="";   			 
				 if(($li>=28)&&($li<=32))
				 {
				     $ls_nombre="";
					 $lb_valido=$this->uf_spg_reportes_select_denominacion($ls_cuenta_arreglo,$ls_nombre);
					 $this->dts_reporte_final->insertRow("sc_cuenta",$ls_cuenta_arreglo);
					 $this->dts_reporte_final->insertRow("denominacion",$ls_nombre);
					 $this->dts_reporte_final->insertRow("tipo","2");
					 $this->dts_reporte_final->insertRow("status",$ls_status);
					 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
					 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
					 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
					 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
					 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
					 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
					 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
					 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
					
					 $this->dts_reporte_final->insertRow("sc_cuenta","");
					 $this->dts_reporte_final->insertRow("denominacion","Imputación en Activo Fijo");
					 $this->dts_reporte_final->insertRow("tipo","333");
					 $this->dts_reporte_final->insertRow("status",$ls_status);
					 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
					 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
					 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
					 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
					 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
					 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
					 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
					 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);

					 $this->dts_reporte_final->insertRow("sc_cuenta","");
					 $this->dts_reporte_final->insertRow("denominacion","Imputación en Activo Intangible");
					 $this->dts_reporte_final->insertRow("tipo","334");
					 $this->dts_reporte_final->insertRow("status",$ls_status);
					 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
					 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
					 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
					 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
					 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
					 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
					 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
					 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
				 }
				 elseif(($li>=33)&&($li<=38))
				 {
				     $ls_nombre="";
					 $lb_valido=$this->uf_spi_reportes_select_denominacion($ls_cuenta_arreglo,$ls_nombre);
					 $this->dts_reporte_final->insertRow("sc_cuenta",$ls_cuenta_arreglo);
					 $this->dts_reporte_final->insertRow("denominacion",$ls_nombre);
					 $this->dts_reporte_final->insertRow("tipo","3");
					 $this->dts_reporte_final->insertRow("status",$ls_status);
					 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
					 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
					 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
					 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
					 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
					 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
					 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
					 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
				 }//if
				 else
				 {
					 $this->dts_reporte_final->insertRow("sc_cuenta",$ls_cuenta_arreglo);
					 $this->dts_reporte_final->insertRow("denominacion",$ls_denom);
					 $this->dts_reporte_final->insertRow("tipo","1");
					 $this->dts_reporte_final->insertRow("status",$ls_status);
					 $this->dts_reporte_final->insertRow("nivel",$li_nivel);
					 $this->dts_reporte_final->insertRow("monto_programado",$ld_monto_programado);
					 $this->dts_reporte_final->insertRow("programado_acumulado",$ld_monto_programado_acumulado);
					 $this->dts_reporte_final->insertRow("monto_ejecutado",$ld_monto_ejecutado);
					 $this->dts_reporte_final->insertRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado);
					 $this->dts_reporte_final->insertRow("variacion_absoluta",$ld_variacion_absoluta);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion",$ld_porcentaje_variacion);
					 $this->dts_reporte_final->insertRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada);
					 $this->dts_reporte_final->insertRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada);
					 $this->dts_reporte_final->insertRow("reprogr_prox_periodo",$ld_reprog_proxima);
				 }//else
		     }//if

		 }//for
	   }//if
	  return $lb_valido;
   }//fin uf_scg_reportes_init_array()
/********************************************************************************************************************************/
    function uf_scg_reportes_llenar_datastore_cuentas()
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_llenar_datastore_cuentas
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    03/09/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql=" SELECT * ".
              " FROM   scg_cuentas ".
              " WHERE  codemp='0001' AND (sc_cuenta like '121000000%' OR sc_cuenta like '121010000%' OR  ".
			  "        sc_cuenta like '121020000%' OR sc_cuenta like '121030000%' OR sc_cuenta like '123000000%' OR ".
              "        sc_cuenta like '123010000%' OR sc_cuenta like '123010100%' OR sc_cuenta like '123010200%' OR ".
              "        sc_cuenta like '123010300%' OR sc_cuenta like '123010400%' OR sc_cuenta like '123010500%' OR ".
              "        sc_cuenta like '123010600%' OR sc_cuenta like '123010700%' OR sc_cuenta like '123010800%' OR ".
			  "        sc_cuenta like '123010900%' OR sc_cuenta like '123011900%' OR sc_cuenta like '123020000%' OR ".
              "        sc_cuenta like '123050000%' OR sc_cuenta like '123050100%' OR sc_cuenta like '123050200%' OR ".
              "        sc_cuenta like '124000000%' OR sc_cuenta like '124010000%' OR sc_cuenta like '124020000%' OR ".
              "        sc_cuenta like '124030000%' OR sc_cuenta like '124040000%' OR sc_cuenta like '124050000%' OR ".
			  "        sc_cuenta like '124190000%' )  ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		    $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_spg_reportes_llenar_datastore_cuentas  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $datos=$this->io_sql->obtener_datos($rs_data);
		  $this->dts_scg_cuentas->data=$datos;	
		  $lb_valido=true;			
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);   
	  }//else
    return  $lb_valido;
   }//fin uf_spg_reportes_llenar_datastore_cuentas()
/********************************************************************************************************************************/
    function uf_scg_reportes_select_denominacion($as_sc_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion   FROM   sigesp_plan_unico   WHERE  sc_cuenta='".$as_sc_cuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
		    $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reportes_select_denominacion  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
   }//fin uf_scg_reportes_select_denominacion()
/********************************************************************************************************************************/
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
/********************************************************************************************************************************/	
	function uf_scg_reportes_procesar_cuentas($as_sc_cuenta,$adt_fesdes,$adt_feshas,$ai_mesdes,$ai_meshas,$ai_cant_mes)
	{ ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_procesar_cuentas
	  //        Argumentos :    $as_sc_cuenta --> codigo de la cuentas
	  //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    03/09/2006                       Fecha última Modificacion :      Hora :
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////
      $ls_gasto=$_SESSION["la_empresa"]["gasto"];	
	  $lb_valido=$this->uf_scg_reportes_llenar_datastore_cuentas();
      if($lb_valido)
	  {
	    $li_total=$this->dts_scg_cuentas->getRowCount("sc_cuenta");
		/*for($li_i=1;$li_i<=$li_total;$li_i++)
	    {*/
			$li_pos=$this->dts_scg_cuentas->find("sc_cuenta",$as_sc_cuenta);
			if($li_pos>0)
			{
			  $ld_enero=0;		   $ld_febrero=0;
			  $ld_marzo=0;		   $ld_abril=0;
			  $ld_mayo=0;		   $ld_junio=0;
			  $ld_julio=0;		   $ld_agosto=0;
			  $ld_septiembre=0;    $ld_octubre=0;
			  $ld_noviembre=0;	   $ld_diciembre=0;
			  $ls_codrep="0714";   $li_nivel="";     $ls_status="";
			  $lb_valido=$this->uf_scg_reporte_cargar_programado($ls_codrep,$as_sc_cuenta,$ld_enero,$ld_febrero,$li_nivel,
																 $ls_status,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																 $ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre);
			  if($lb_valido)
			  {
				  // monto programado y programado ejecutado
				  $ld_monto_programado=0;
				  $ld_monto_programado_acumulado=0;
				  $lb_valido=$this->uf_scg_reporte_calcular_programado($ai_mesdes,$ai_meshas,$ld_monto_programado,$ld_monto_programado_acumulado,
																	   $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				 if($lb_valido)
				 {
				    //monto ejecutado
					$ld_monto_ejecutado=0;
					$lb_mayor_dh=true;
					$ldt_fesdes=$this->io_fecha->uf_convert_date_to_db($adt_fesdes);
					$ldt_feshas=$this->io_fecha->uf_convert_date_to_db($adt_feshas);
					$lb_valido=$this->uf_scg_reporte_calcular_ejecutado($as_sc_cuenta,$ldt_fesdes,$ldt_feshas,$ld_monto_ejecutado,
					                                                    $lb_mayor_dh);
					if($lb_valido)													
					{
                      //monto ejecutado acumulado
					  $ld_monto_ejecutado_acumulado=0;
					  $lb_valido=$this->uf_scg_reporte_calcular_ejecutado_acumulado($as_sc_cuenta,$ldt_fesdes,$ldt_feshas,
					                                                                $ld_monto_ejecutado_acumulado);
					  if($lb_valido)
					  {
						  $ls_cuenta=substr($as_sc_cuenta,0,1);
						  if($ls_cuenta==$ls_gasto)
						  {
							 $ls_signo=1;
						  }//if
						  else
						  {
							 $ls_signo=-1;
						  }//else
						  //variacion absoluta  del periodo entre el  monto ejecutado y monto programado
						  if($ld_monto_programado>$ld_monto_ejecutado)
						  {
						   $ld_variacion_absoluta=0-($ld_monto_programado-$ld_monto_ejecutado); 
						  }
						  else
						  {
							   if($ld_monto_programado==0)
							   { 
								  $ld_variacion_absoluta=$ld_monto_ejecutado; 
							   } 
							   else 
							   { 
								  $ld_variacion_absoluta=$ld_monto_programado-$ld_monto_ejecutado;  
							   }
						  }
						 //variacion porcentual  del periodo entre el  monto ejecutado y monto programado
						 if($ld_monto_programado>0)
						 { 
						    $ld_porcentaje_variacion=(($ld_monto_programado-$ld_monto_ejecutado)/$ld_monto_programado)*100;  
						 }
						 else
						 {
						   $ld_porcentaje_variacion=0;  
						 }
						 if($ld_monto_programado_acumulado==0)
						 {
						   $ld_varia_acum=$ld_monto_ejecutado_acumulado;
						 }
						 else
						 {
						   $ld_varia_acum=$ld_monto_programado_acumulado-$ld_monto_ejecutado_acumulado;
						 }
						 //variacion absoluta  del monto acumulado
						 if($ld_monto_programado_acumulado>$ld_monto_ejecutado_acumulado)
						 {
						   $ld_variacion_absoluta_acumulada=0-($ld_varia_acum);
						 }
						 else
						 {
						   $ld_variacion_absoluta_acumulada=$ld_varia_acum;
						 }
						 //variacion porcentual del monto acumulado
						 if($ld_monto_programado_acumulado>0)
						 { 
						    $ld_porcentaje_variacion_acumulada=(($ld_monto_programado_acumulado-$ld_monto_ejecutado_acumulado)/$ld_monto_programado_acumulado)*100; 
						 }
						 else
						 { 
						    $ld_porcentaje_variacion_acumulada=0; 
						 }
						 // monto de la inversion proximo mes
						 $ld_reprog_proxima=0;
						 $ldt_fechadesde=$ai_meshas+1;
						 $ldt_fechahasta=$ai_meshas+$ai_cant_mes;
					     $lb_valido=$this->uf_scg_reporte_calcular_programado_prox_mes($ldt_fechadesde,$ldt_fechahasta,$ld_reprog_proxima,
						                                                               $ls_codrep,$as_sc_cuenta);
					     if($lb_valido)
						 {
							$li_pos=$this->dts_reporte_final->find("sc_cuenta",$as_sc_cuenta);
							if($li_pos>0)
							{
								 $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_pos);
								 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_pos);   
								 $ls_status=$this->dts_reporte_final->getValue("status",$li_pos);  
								 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_pos);
								 $this->dts_reporte_final->updateRow("sc_cuenta",$as_sc_cuenta,$li_pos);
								 $this->dts_reporte_final->updateRow("denominacion",$ls_denominacion,$li_pos);
								 $this->dts_reporte_final->updateRow("tipo",$ls_tipo,$li_pos);
								 $this->dts_reporte_final->updateRow("status",$ls_status,$li_pos);
								 $this->dts_reporte_final->updateRow("nivel",$li_nivel,$li_pos);
								 $this->dts_reporte_final->updateRow("monto_programado",$ld_monto_programado,$li_pos);
								 $this->dts_reporte_final->updateRow("programado_acumulado",$ld_monto_programado_acumulado,$li_pos);
								 $this->dts_reporte_final->updateRow("monto_ejecutado",$ld_monto_ejecutado,$li_pos);
								 $this->dts_reporte_final->updateRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado,$li_pos);
								 $this->dts_reporte_final->updateRow("variacion_absoluta",$ld_variacion_absoluta,$li_pos);
								 $this->dts_reporte_final->updateRow("porcentaje_variacion",$ld_porcentaje_variacion,$li_pos);
								 $this->dts_reporte_final->updateRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada,$li_pos);
								 $this->dts_reporte_final->updateRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada,$li_pos);
								 $this->dts_reporte_final->updateRow("reprogr_prox_periodo",$ld_reprog_proxima,$li_pos);
						         $lb_valido=true;
						   }//if
						}//if 
					 }//if
				   }//if
				 }//if      
			  }//if
            }//if
			else
			{
				$li_pos=$this->dts_reporte_final->find("sc_cuenta",$as_sc_cuenta);
				if($li_pos>0)
				{
                     $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_pos);
					 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_pos);   
					 $ls_status=$this->dts_reporte_final->getValue("status",$li_pos);  
					 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_pos);
 					 $ld_monto_programado=0;		   			$ld_monto_programado_acumulado=0;
					 $ld_monto_ejecutado=0; 		 		    $ld_monto_ejecutado_acumulado=0;	
					 $ld_variacion_absoluta=0;	   			    $ld_porcentaje_variacion=0;
					 $ld_variacion_absoluta_acumulada=0;	    $ld_porcentaje_variacion_acumulada=0;
					 $ld_reprog_proxima=0;					      			 
				
					 $this->dts_reporte_final->updateRow("sc_cuenta",$as_sc_cuenta,$li_pos);
					 $this->dts_reporte_final->updateRow("denominacion",$ls_denominacion,$li_pos);
					 $this->dts_reporte_final->updateRow("tipo",$ls_tipo,$li_pos);
					 $this->dts_reporte_final->updateRow("status",$ls_status,$li_pos);
					 $this->dts_reporte_final->updateRow("nivel",$li_nivel,$li_pos);
					 $this->dts_reporte_final->updateRow("monto_programado",$ld_monto_programado,$li_pos);
					 $this->dts_reporte_final->updateRow("programado_acumulado",$ld_monto_programado_acumulado,$li_pos);
					 $this->dts_reporte_final->updateRow("monto_ejecutado",$ld_monto_ejecutado,$li_pos);
					 $this->dts_reporte_final->updateRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado,$li_pos);
					 $this->dts_reporte_final->updateRow("variacion_absoluta",$ld_variacion_absoluta,$li_pos);
					 $this->dts_reporte_final->updateRow("porcentaje_variacion",$ld_porcentaje_variacion,$li_pos);
					 $this->dts_reporte_final->updateRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada,$li_pos);
					 $this->dts_reporte_final->updateRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada,$li_pos);
					 $this->dts_reporte_final->updateRow("reprogr_prox_periodo",$ld_reprog_proxima,$li_pos);
					 $lb_valido=true;
			  }//if
			}//else
		//}//for
      }//if 
	  return $lb_valido;
	}//fin uf_scg_reportes_procesar_cuentas
/********************************************************************************************************************************/	
    function uf_scg_reporte_cargar_programado($as_codrep,$as_sc_cuenta,&$ad_enero,&$ad_febrero,&$ai_nivel,&$as_status,
											  &$ad_marzo,&$ad_abril,&$ad_mayo,&$ad_junio,&$ad_julio,&$ad_agosto,
											  &$ad_septiembre,&$ad_octubre,&$ad_noviembre,&$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_cargar_programado
	 //         Access :	private
	 //     Argumentos :    $as_codrep  -->  codigo del reporte
	 //                     $as_sc_cuenta -->  codigo de la  cuenta 
	 //                     $ad_enero .. $ad_diciembre --> monto programado para cada  mes    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    24/08/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	    $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   scg_pc_reporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND substr(sc_cuenta,1,9)='".$as_sc_cuenta."' ".
              " GROUP BY sc_cuenta, nivel, status, denominacion ";
		}
		else
		{
		 $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   scg_pc_reporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND substr(sc_cuenta,1,9)='".$as_sc_cuenta."' ".
              " GROUP BY sc_cuenta,nivel,status, denominacion ";	
			  ///print $ls_sql."<br>esta consulta";
		}	
			 		 			  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reporte_cargar_programado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			   $ai_nivel=$row["nivel"];
			   $as_status=$row["status"];
			   $ad_asignado=$row["asignado"];
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
			   $ad_asignado=0;
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
   }//fin uf_scg_reporte_select_saldo_empresa
/********************************************************************************************************************************/	
    function uf_scg_reporte_calcular_programado($ai_mesdes,$ai_meshas,&$ad_monto_programado,&$ad_monto_acumulado,$ad_enero,$ad_febrero,
												$ad_marzo,$ad_abril,$ad_mayo,$ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,
												$ad_octubre,$ad_noviembre,$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_programado
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
	 // Fecha Creación :    01/06/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=true;
     $li_mesdes=intval($ai_mesdes);
     $li_meshas=intval($ai_meshas);
     if(!(($li_mesdes>=1)&&($li_meshas<=12)))
     {
	   $lb_valido=false;
     }
     if($lb_valido)
     {
	   for($i=$li_mesdes;$i<=$li_meshas;$i++)
	   {
		 switch ($li_mesdes)
		 {
			 case 1:
				  $ad_monto_programado=$ad_monto_programado+$ad_enero;
			 break;
			 case 2:
				  $ad_monto_programado=$ad_monto_programado+$ad_febrero;
			 break;
			 case 3:
				  $ad_monto_programado=$ad_monto_programado+$ad_marzo;
			 break;
			 case 4:
				  $ad_monto_programado=$ad_monto_programado+$ad_abril;
			 break;
			 case 5:
				  $ad_monto_programado=$ad_monto_programado+$ad_mayo;
			 break;
			 case 6:
				  $ad_monto_programado=$ad_monto_programado+$ad_junio;
			 break;
			 case 7:
				  $ad_monto_programado=$ad_monto_programado+$ad_julio;
			 break;
			 case 8:
				  $ad_monto_programado=$ad_monto_programado+$ad_agosto;
			 break;
			 case 9:
				  $ad_monto_programado=$ad_monto_programado+$ad_septiembre;
			 break;
			 case 10:
				  $ad_monto_programado=$ad_monto_programado+$ad_octubre;
			 break;
			 case 11:
				  $ad_monto_programado=$ad_monto_programado+$ad_noviembre;
			 break;
			 case 12:
				  $ad_monto_programado=$ad_monto_programado+$ad_diciembre;
			 break;
		 }//switch
	   }//for
	   for($i=1;$i<=$li_meshas;$i++)
	   {
		 switch ($i)
		 {
			 case 1:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_enero;
			 break;
			 case 2:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_febrero;
			 break;
			 case 3:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_marzo;
			 break;
			 case 4:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_abril;
			 break;
			 case 5:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_mayo;
			 break;
			 case 6:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_junio;
			 break;
			 case 7:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_julio;
			 break;
			 case 8:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_agosto;
			 break;
			 case 9:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_septiembre;
			 break;
			 case 10:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_octubre;
			 break;
			 case 11:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_noviembre;
			 break;
			 case 12:
				  $ad_monto_acumulado=$ad_monto_acumulado+$ad_diciembre;
			 break;
		 }//switch
	   }//for		
	   }//if
	   return  $lb_valido; 
   }//fin uf_scg_reporte_calcular_programado
/**************************************************************************************************************************************/	
    function uf_scg_reporte_calcular_ejecutado($as_sc_cuenta,$adt_fesdes,$adt_feshas,&$ad_monto_ejecutado,&$ab_mayor_dh)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_ejecutado
	 //         Access :	private
	 //     Argumentos :    $as_mesdes  // mes  desde 
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado del mes (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Metodo que calcula los montos ejecutados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    04/09/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $ad_monto_ejecutado=0;
	  $lb_valido=true;
	  //$ls_cuenta_sin_cero=$this->sigesp_int_scg->uf_scg_sin_ceros($as_sc_cuenta);
	  $ls_cuenta=$this->sigesp_int_scg->uf_pad_scg_cuenta( $_SESSION["la_empresa"]["formcont"], $as_sc_cuenta );
	  if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	     $ls_sql=" SELECT Case sum(debe_mes) when null then 0 else sum(debe_mes) end totdebe, 
	                   Case sum(haber_mes) when null then 0 else sum(haber_mes) end tothaber ".
                 " FROM   scg_saldos ".
                 " WHERE  codemp='".$this->ls_codemp."' AND  sc_cuenta like '".$ls_cuenta."' AND ".
	             "        fecsal >= '".$adt_fesdes."'    AND  fecsal <='".$adt_feshas."' ";
	   }
	   if($_SESSION["ls_gestor"]=='POSTGRES')
	   {
	     $ls_sql=" SELECT (Case sum(debe_mes) when null then 0 else sum(debe_mes) end) as totdebe, ".
       			 "		  (Case sum(haber_mes) when null then 0 else sum(haber_mes) end) as tothaber  ".
                 " FROM   scg_saldos ".
                 " WHERE  codemp='".$this->ls_codemp."' AND  sc_cuenta like '".$ls_cuenta."' AND ".
	             "        fecsal >= '".$adt_fesdes."'    AND  fecsal <='".$adt_feshas."' "; //print $ls_sql."<br>";
	   }
	    if($_SESSION["ls_gestor"]=='MYSQLT')
	   {
	     $ls_sql=" SELECT (Case sum(debe_mes) when null then 0 else sum(debe_mes) end) as totdebe, ".
       			 "		  (Case sum(haber_mes) when null then 0 else sum(haber_mes) end) as tothaber  ".
                 " FROM   scg_saldos ".
                 " WHERE  codemp='".$this->ls_codemp."' AND  sc_cuenta like '".$ls_cuenta."' AND ".
	             "        fecsal >= '".$adt_fesdes."'    AND  fecsal <='".$adt_feshas."' "; //print $ls_sql."<br>";
	   }	  	   
	  $rs_data=$this->io_sql->select($ls_sql);
	  
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reporte_calcular_ejecutado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
          $ld_totdebe=$row["totdebe"];
		  $ld_tothaber=$row["tothaber"];
		  $ad_monto_ejecutado=$ld_totdebe-$ld_tothaber;
		  if($ld_totdebe>$ld_tothaber)
		  {
		     $ab_mayor_dh=true; 
		  }//if
		  else
		  {
		     $ab_mayor_dh=false; 
		  }//else
		}//if
		$this->io_sql->free_result($rs_data);
	  }//else
	  return $lb_valido;
 }//fin uf_scg_reporte_calcular_ejecutado
/**********************************************************************************************************************************/	
    function uf_scg_reporte_calcular_ejecutado_acumulado($as_sc_cuenta,$adt_fesdes,$adt_feshas,&$ad_monto_ejecutado_acumulado)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_ejecutado_acumulado
	 //         Access :	private
	 //     Argumentos :    $as_mesdes  // mes  desde 
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado del mes (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Metodo que calcula los montos ejecutados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    04/09/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $ad_monto_ejecutado_acumulado=0;
	  $lb_valido=true;
	  //$ls_cuenta_sin_cero=$this->sigesp_int_scg->uf_scg_sin_ceros($as_sc_cuenta);
  	  $ls_cuenta=$this->sigesp_int_scg->uf_pad_scg_cuenta( $_SESSION["la_empresa"]["formcont"], $as_sc_cuenta );
	  if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	      $ls_sql=" SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end debe_mes, 
	                   case sum(haber_mes) when null then 0 else sum(haber_mes) end haber_mes ".
                  " FROM   scg_saldos ".
                  " WHERE  codemp='".$this->ls_codemp."'  AND  sc_cuenta like '".$ls_cuenta."' AND  ".
			      "        fecsal <='".$adt_feshas."' ";
	   }
	   if($_SESSION["ls_gestor"]=='POSTGRES')
	   {
	      $ls_sql=" SELECT (case sum(debe_mes) when null then 0 else sum(debe_mes) end) AS debe_mes, 
	                       (case sum(haber_mes) when null then 0 else sum(haber_mes) end) AS haber_mes ".
                  " FROM   scg_saldos ".
                  " WHERE  codemp='".$this->ls_codemp."'  AND  sc_cuenta like '".$ls_cuenta."' AND  ".
			      "        fecsal <='".$adt_feshas."' ";
	   }
	    if($_SESSION["ls_gestor"]=='MYSQLT')
	   {
	      $ls_sql=" SELECT (case sum(debe_mes) when null then 0 else sum(debe_mes) end) AS debe_mes, 
	                       (case sum(haber_mes) when null then 0 else sum(haber_mes) end) AS haber_mes ".
                  " FROM   scg_saldos ".
                  " WHERE  codemp='".$this->ls_codemp."'  AND  sc_cuenta like '".$ls_cuenta."' AND  ".
			      "        fecsal <='".$adt_feshas."' ";
	   }	  			  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reporte_calcular_ejecutado_acumulado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
          $ld_debe_mes=$row["debe_mes"];
		  $ld_haber_mes=$row["haber_mes"];
		  $ad_monto_ejecutado_acumulado=$ld_debe_mes-$ld_haber_mes;
		}//if
		$this->io_sql->free_result($rs_data);
	  }//else
	  return $lb_valido;
 }//fin uf_scg_reporte_calcular_ejecutado
/**********************************************************************************************************************************/	
    function uf_scg_reporte_calcular_programado_prox_mes($li_mesdes,$li_meshas,&$ad_monto_programado,$as_codrep,$as_sc_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_programado_prox_mes
	 //         Access :	private
	 //     Argumentos :    $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	metodo que calcula los montos programados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    04/09/2006          Fecha última Modificacion :              Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	
	  $ad_monto_programado=0; 
	  if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	     $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero, ".
              "        sum(febrero) as febrero, sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, ".
              "        sum(junio) as junio, sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre, ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre, sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   scg_pc_reporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."'  AND  substr(sc_cuenta,1,9)='".$as_sc_cuenta."'  ".
              " GROUP BY sc_cuenta, nivel, status, denominacion ";
	   }
	   else
	   {
	   	     $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero, ".
              "        sum(febrero) as febrero, sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo, ".
              "        sum(junio) as junio, sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre, ".
              "        sum(octubre) as octubre, sum(noviembre) as noviembre, sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   scg_pc_reporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."'  AND  substr(sc_cuenta,1,9)='".$as_sc_cuenta."'  ".
              " GROUP BY sc_cuenta,nivel, status,denominacion ";
	   }
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	     $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reporte_calcular_programado_prox_mes  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 $lb_valido = false;
	 }
	 else
	 {
	  	if($row=$this->io_sql->fetch_row($rs_data))
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
		}//if
	    $this->io_sql->free_result($rs_data);
     }//else
	 return $lb_valido;
   }//fin uf_spg_reporte_calcular_programado
/**********************************************************************************************************************************/	
    function uf_spg_reportes_select_denominacion($as_spg_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion FROM spg_cuentas WHERE spg_cuenta='".$as_spg_cuenta."' ";
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
/**********************************************************************************************************************************/	
    function uf_spi_reportes_select_denominacion($as_spi_cuenta,&$as_denominacion)
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spi_reportes_select_denominacion
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    28/08/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT denominacion FROM spi_cuentas WHERE spi_cuenta='".$as_spi_cuenta."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta metodo uf_spi_reportes_select_denominacion ".$this->fun->uf_convertirmsg($this->SQL->message);
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
   }//fin uf_spi_reportes_select_denominacion()
/****************************************************************************************************************************************/
	function uf_spg_reportes_procesar_cuentas($as_sc_cuenta,$adt_fesdes,$adt_feshas,$ai_mesdes,$ai_meshas,$ai_cant_mes)
	{ ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_scg_reportes_procesar_cuentas
	  //        Argumentos :    $as_sc_cuenta --> codigo de la cuentas
	  //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    03/09/2006                       Fecha última Modificacion :      Hora :
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////
      $ls_gasto=$_SESSION["la_empresa"]["gasto"];	
	  $lb_valido=$this->uf_spg_reportes_llenar_datastore_cuentas();
      if($lb_valido)
	  {
			$li_pos=$this->dts_spg_cuentas->find("spg_cuenta",$as_sc_cuenta);
			if($li_pos>0)
			{
			  $ld_enero=0;		   $ld_febrero=0;
			  $ld_marzo=0;		   $ld_abril=0;
			  $ld_mayo=0;		   $ld_junio=0;
			  $ld_julio=0;		   $ld_agosto=0;
			  $ld_septiembre=0;    $ld_octubre=0;
			  $ld_noviembre=0;	   $ld_diciembre=0;
			  $ls_codrep="0714";   $li_nivel="";     $ls_status="";
			  $lb_valido=$this->uf_spg_reporte_cargar_programado($ls_codrep,$as_sc_cuenta,$ld_enero,$ld_febrero,$li_nivel,
																 $ls_status,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																 $ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre);
			  if($lb_valido)
			  {
				  // monto programado y programado ejecutado
				  $ld_monto_programado=0;
				  $ld_monto_programado_acumulado=0;
				  $lb_valido=$this->uf_scg_reporte_calcular_programado($ai_mesdes,$ai_meshas,$ld_monto_programado,$ld_monto_programado_acumulado,
																	   $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);

				 if($lb_valido)
				 {
				    //monto ejecutado
					$ld_monto_ejecutado=0;
					$ld_monto_ejecutado_acumulado=0;
					$ldt_fesdes=$this->io_fecha->uf_convert_date_to_db($adt_fesdes);
					$ldt_feshas=$this->io_fecha->uf_convert_date_to_db($adt_feshas);
					$lb_valido=$this->uf_spg_reporte_calcular_ejecutado($as_sc_cuenta,&$ld_monto_ejecutado,&$ld_monto_ejecutado_acumulado,
					                                                    $ldt_fesdes,$ldt_feshas);
					/*if($lb_valido)													
					{
                      //monto ejecutado acumulado
					  $ld_monto_ejecutado_acumulado=0;
					  $lb_valido=$this->uf_scg_reporte_calcular_ejecutado_acumulado($as_sc_cuenta,$ldt_fesdes,$ldt_feshas,
					                                                                $ld_monto_ejecutado_acumulado);*/
					  if($lb_valido)
					  {
						  $ls_cuenta=substr($as_sc_cuenta,0,1);
						  if($ls_cuenta==$ls_gasto)
						  {
							 $ls_signo=1;
						  }//if
						  else
						  {
							 $ls_signo=-1;
						  }//else
						  //variacion absoluta  del periodo entre el  monto ejecutado y monto programado
						  if($ld_monto_programado>$ld_monto_ejecutado)
						  {
						   $ld_variacion_absoluta=0-($ld_monto_programado-$ld_monto_ejecutado); 
						  }
						  else
						  {
							   if($ld_monto_programado==0)
							   { 
								  $ld_variacion_absoluta=$ld_monto_ejecutado; 
							   } 
							   else 
							   { 
								  $ld_variacion_absoluta=$ld_monto_programado-$ld_monto_ejecutado;  
							   }
						  }
						 //variacion porcentual  del periodo entre el  monto ejecutado y monto programado
						 if($ld_monto_programado>0)
						 { 
						    $ld_porcentaje_variacion=(($ld_monto_programado-$ld_monto_ejecutado)/$ld_monto_programado)*100;  
						 }
						 else
						 {
						   $ld_porcentaje_variacion=0;  
						 }
						 if($ld_monto_programado_acumulado==0)
						 {
						   $ld_varia_acum=$ld_monto_ejecutado_acumulado;
						 }
						 else
						 {
						   $ld_varia_acum=$ld_monto_programado_acumulado-$ld_monto_ejecutado_acumulado;
						 }
						 //variacion absoluta  del monto acumulado
						 if($ld_monto_programado_acumulado>$ld_monto_ejecutado_acumulado)
						 {
						   $ld_variacion_absoluta_acumulada=0-($ld_varia_acum);
						 }
						 else
						 {
						   $ld_variacion_absoluta_acumulada=$ld_varia_acum;
						 }
						 //variacion porcentual del monto acumulado
						 if($ld_monto_programado_acumulado>0)
						 { 
						    $ld_porcentaje_variacion_acumulada=(($ld_monto_programado_acumulado-$ld_monto_ejecutado_acumulado)/$ld_monto_programado_acumulado)*100; 
						 }
						 else
						 { 
						    $ld_porcentaje_variacion_acumulada=0; 
						 }
						 // monto de la inversion proximo mes
						 $ld_reprog_proxima=0;
						 $ldt_fechadesde=$ai_meshas+1;
						 $ldt_fechahasta=$ai_meshas+$ai_cant_mes;
					     $lb_valido=$this->uf_spg_reporte_calcular_programado_prox_mes($ldt_fechadesde,$ldt_fechahasta,$ld_reprog_proxima,
						                                                               $ls_codrep,$as_sc_cuenta);
					     if($lb_valido)
						 {
							$li_pos=$this->dts_reporte_final->find("sc_cuenta",$as_sc_cuenta);
							//print "posicion en final=".$li_pos;
							if($li_pos>0)
							{
								 $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_pos);
								 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_pos);   
								 $ls_status=$this->dts_reporte_final->getValue("status",$li_pos);  
								 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_pos);
							
								 $this->dts_reporte_final->updateRow("sc_cuenta",$as_sc_cuenta,$li_pos);
								 $this->dts_reporte_final->updateRow("denominacion",$ls_denominacion,$li_pos);
								 $this->dts_reporte_final->updateRow("tipo",$ls_tipo,$li_pos);
								 $this->dts_reporte_final->updateRow("status",$ls_status,$li_pos);
								 $this->dts_reporte_final->updateRow("nivel",$li_nivel,$li_pos);
								 $this->dts_reporte_final->updateRow("monto_programado",$ld_monto_programado,$li_pos);
								 $this->dts_reporte_final->updateRow("programado_acumulado",$ld_monto_programado_acumulado,$li_pos);
								 $this->dts_reporte_final->updateRow("monto_ejecutado",$ld_monto_ejecutado,$li_pos);
								 $this->dts_reporte_final->updateRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado,$li_pos);
								 $this->dts_reporte_final->updateRow("variacion_absoluta",$ld_variacion_absoluta,$li_pos);
								 $this->dts_reporte_final->updateRow("porcentaje_variacion",$ld_porcentaje_variacion,$li_pos);
								 $this->dts_reporte_final->updateRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada,$li_pos);
								 $this->dts_reporte_final->updateRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada,$li_pos);
								 $this->dts_reporte_final->updateRow("reprogr_prox_periodo",$ld_reprog_proxima,$li_pos);
								// print_r($this->dts_reporte_final->data);
						         $lb_valido=true;
						   }//if
						}//if 
					 }//if
				   //}//if
				 }//if      
			  }//if
            }//if
			else
			{
				$li_pos=$this->dts_reporte_final->find("sc_cuenta",$as_sc_cuenta);
				if($li_pos>0)
				{
                     $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_pos);
					 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_pos);   
					 $ls_status=$this->dts_reporte_final->getValue("status",$li_pos);  
					 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_pos);
 					 $ld_monto_programado=0;		   			$ld_monto_programado_acumulado=0;
					 $ld_monto_ejecutado=0; 		 		    $ld_monto_ejecutado_acumulado=0;	
					 $ld_variacion_absoluta=0;	   			    $ld_porcentaje_variacion=0;
					 $ld_variacion_absoluta_acumulada=0;	    $ld_porcentaje_variacion_acumulada=0;
					 $ld_reprog_proxima=0;					      			 
				
					 $this->dts_reporte_final->updateRow("sc_cuenta",$as_sc_cuenta,$li_pos);
					 $this->dts_reporte_final->updateRow("denominacion",$ls_denominacion,$li_pos);
					 $this->dts_reporte_final->updateRow("tipo",$ls_tipo,$li_pos);
					 $this->dts_reporte_final->updateRow("status",$ls_status,$li_pos);
					 $this->dts_reporte_final->updateRow("nivel",$li_nivel,$li_pos);
					 $this->dts_reporte_final->updateRow("monto_programado",$ld_monto_programado,$li_pos);
					 $this->dts_reporte_final->updateRow("programado_acumulado",$ld_monto_programado_acumulado,$li_pos);
					 $this->dts_reporte_final->updateRow("monto_ejecutado",$ld_monto_ejecutado,$li_pos);
					 $this->dts_reporte_final->updateRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado,$li_pos);
					 $this->dts_reporte_final->updateRow("variacion_absoluta",$ld_variacion_absoluta,$li_pos);
					 $this->dts_reporte_final->updateRow("porcentaje_variacion",$ld_porcentaje_variacion,$li_pos);
					 $this->dts_reporte_final->updateRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada,$li_pos);
					 $this->dts_reporte_final->updateRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada,$li_pos);
					 $this->dts_reporte_final->updateRow("reprogr_prox_periodo",$ld_reprog_proxima,$li_pos);
					 $lb_valido=true;
			  }//if
			}//else
      }//if 
	  return $lb_valido;
	}//fin uf_scg_reportes_procesar_cuentas
/**********************************************************************************************************************************/
    function uf_spg_reportes_llenar_datastore_cuentas()
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spg_reportes_llenar_datastore_cuentas
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    03/09/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql=" SELECT * ".
              " FROM   spg_cuentas ".
              " WHERE  codemp='0001' AND (spg_cuenta like '401000000%' OR spg_cuenta like '402000000%' OR  ".
			  "        spg_cuenta like '403000000%' OR spg_cuenta like '407000000%' OR spg_cuenta like '408000000%')  ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		    $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_spg_reportes_llenar_datastore_cuentas  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $datos=$this->io_sql->obtener_datos($rs_data);
		  $this->dts_spg_cuentas->data=$datos;	
		  $lb_valido=true;			
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);   
	  }//else
    return  $lb_valido;
   }//fin uf_spg_reportes_llenar_datastore_cuentas()
/****************************************************************************************************************************************/	
    function uf_spg_reporte_cargar_programado($as_codrep,$as_sc_cuenta,&$ad_enero,&$ad_febrero,&$ai_nivel,&$as_status,
											  &$ad_marzo,&$ad_abril,&$ad_mayo,&$ad_junio,&$ad_julio,&$ad_agosto,
											  &$ad_septiembre,&$ad_octubre,&$ad_noviembre,&$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_cargar_programado
	 //         Access :	private
	 //     Argumentos :    $as_codrep  -->  codigo del reporte
	 //                     $as_sc_cuenta -->  codigo de la  cuenta 
	 //                     $ad_enero .. $ad_diciembre --> monto programado para cada  mes    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    24/08/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	    $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spg_plantillareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND spg_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spg_cuenta, nivel, status, denominacion  ";
	   }
	   else
	   {
	    $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spg_plantillareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND spg_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spg_cuenta";
	   }
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		 // $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reporte_cargar_programado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			   $ai_nivel=$row["nivel"];
			   $as_status=$row["status"];
			   $ad_asignado=$row["asignado"];
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
			   $ad_asignado=0;
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
   }//fin uf_scg_reporte_select_saldo_empresa
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado($as_spg_cuenta,&$ad_monto_ejecutado,&$ad_monto_acumulado,$adt_fecini,$adt_fecfin)
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
	  $ad_monto_ejecutado=0;	  $ad_monto_acumulado=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $_SESSION["la_empresa"]["codemp"];
	  $l_mesdes=substr($adt_fecini,5,2);
	  $l_meshas=substr($adt_fecfin,5,2);
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
	  $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	 /*$ls_sql=" SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
              " FROM   spg_dt_cmp DT, spg_operaciones OP ".
              " WHERE  DT.codemp='".$this->ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
              "        spg_cuenta in (SELECT distinct spg_cuenta ".
			  "                       FROM  spg_cuentas ".
			  "                       WHERE spg_cuenta like '".$as_spg_cuenta."' AND ".
			  "    	                        status <>'S' AND  ".
			  "	                            sc_cuenta like '1230501%' AND   ".
			  " 	                        sc_cuenta like '1230502%' ) ";*/
	  
	  $ls_sql=" SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
			  " FROM   spg_dt_cmp DT, spg_operaciones OP ".
			  " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
			  "        spg_cuenta like '".$as_spg_cuenta."' ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_spg_reporte_calcular_ejecutado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
			 if(($li_comprometer)&&($ldt_fecha>=$ldt_mesdes)&&($ldt_fecha<=$ldt_meshas))
			 { 
				$ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
			 }//if
			 if(($li_comprometer)&&($ldt_fecha<=$ldt_meshas))
			 {  
				$ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
			 }//if
		}//while
	   $this->io_sql->free_result($rs_data);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado
/**********************************************************************************************************************************/	
    function uf_spg_reporte_calcular_programado_prox_mes($li_mesdes,$li_meshas,&$ad_monto_programado,$as_codrep,$as_sc_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_programado_prox_mes
	 //         Access :	private
	 //     Argumentos :    $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	metodo que calcula los montos programados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    04/09/2006          Fecha última Modificacion :              Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	
	  $ad_monto_programado=0; 
	   if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	    $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spg_plantillareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND spg_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spg_cuenta, nivel, status, denominacion ";
	   }
	   else
	   {
	   $ls_sql=" SELECT sum(asignado) as asignado, sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spg_plantillareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND spg_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spg_cuenta ";
	   }
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	     $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_spg_reporte_calcular_programado_prox_mes  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 $lb_valido = false;
	 }
	 else
	 {
	  	if($row=$this->io_sql->fetch_row($rs_data))
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
		}//if
	    $this->io_sql->free_result($rs_data);
     }//else
	 return $lb_valido;
   }//fin uf_spg_reporte_calcular_programado_prox_mes
/**********************************************************************************************************************************/	
	function uf_spi_reportes_procesar_cuentas($as_sc_cuenta,$adt_fesdes,$adt_feshas,$ai_mesdes,$ai_meshas,$ai_cant_mes)
	{ ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spi_reportes_procesar_cuentas
	  //        Argumentos :    $as_sc_cuenta --> codigo de la cuentas
	  //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    03/09/2006                       Fecha última Modificacion :      Hora :
	  ////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=$this->uf_spi_reportes_llenar_datastore_cuentas();
      if($lb_valido)
	  {
	    $li_total=$this->dts_spi_cuentas->getRowCount("spi_cuenta");
		$li_pos=$this->dts_spi_cuentas->find("spi_cuenta",$as_sc_cuenta);
			if($li_pos>0)
			{
			  $ld_enero=0;		   $ld_febrero=0;
			  $ld_marzo=0;		   $ld_abril=0;
			  $ld_mayo=0;		   $ld_junio=0;
			  $ld_julio=0;		   $ld_agosto=0;
			  $ld_septiembre=0;    $ld_octubre=0;
			  $ld_noviembre=0;	   $ld_diciembre=0;
			  $ls_codrep="0714";   $li_nivel="";     $ls_status="";
			  $lb_valido=$this->uf_spi_reporte_cargar_programado($ls_codrep,$as_sc_cuenta,$ld_enero,$ld_febrero,$li_nivel,
																 $ls_status,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,$ld_julio,
																 $ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre);
			  if($lb_valido)
			  {
				  // monto programado y programado ejecutado
				  $ld_monto_programado=0;
				  $ld_monto_programado_acumulado=0;
				  $lb_valido=$this->uf_scg_reporte_calcular_programado($ai_mesdes,$ai_meshas,$ld_monto_programado,$ld_monto_programado_acumulado,
																	   $ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
																	   $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,
																	   $ld_diciembre);
				 if($lb_valido)
				 {
				    //monto ejecutado
					$ld_monto_ejecutado=0;
					$ld_monto_ejecutado_acumulado=0;
					$ld_previsto=0;
					$ld_devengado=0;
					$ld_cobrado_anticipado=0;
					$ld_aumento=0;
					$ld_disminucion=0;
					$ldt_fesdes=$this->io_fecha->uf_convert_date_to_db($adt_fesdes);
					$ldt_feshas=$this->io_fecha->uf_convert_date_to_db($adt_feshas);
					$lb_valido=$this->uf_spi_reporte_calcular_ejecutado($as_sc_cuenta,$ld_previsto,$ld_devengado,$ld_monto_ejecutado,
					                                                    $ld_monto_ejecutado_acumulado,$ld_cobrado_anticipado,
																		$ld_aumento,$ld_disminucion,$ldt_fesdes,$ldt_feshas);

					/*if($lb_valido)													
					{
                      //monto ejecutado acumulado/*/
					 /* $lb_valido=$this->uf_scg_reporte_calcular_ejecutado_acumulado($as_sc_cuenta,$ldt_fesdes,$ldt_feshas,
					                                                                $ld_monto_ejecutado_acumulado);*/
					  if($lb_valido)
					  {
						  //variacion absoluta  del periodo entre el  monto ejecutado y monto programado
						  if($ld_monto_programado>$ld_monto_ejecutado)
						  {
						   $ld_variacion_absoluta=0-($ld_monto_programado-$ld_monto_ejecutado); 
						  }
						  else
						  {
							   if($ld_monto_programado==0)
							   { 
								  $ld_variacion_absoluta=$ld_monto_ejecutado; 
							   } 
							   else 
							   { 
								  $ld_variacion_absoluta=$ld_monto_programado-$ld_monto_ejecutado;  
							   }
						  }
						 //variacion porcentual  del periodo entre el  monto ejecutado y monto programado
						 if(($ld_monto_programado>0)&&($ld_monto_ejecutado>0))
						 { 
						    $ld_porcentaje_variacion=($ld_monto_programado-$ld_monto_ejecutado)/($ld_monto_ejecutado*100);  
						 	//print " cta=".$as_sc_cuenta."  ".$ld_porcentaje_variacion." prog=".$ld_monto_programado."  ejecutado=".$ld_monto_ejecutado."<br>";
						 }
						 else
						 {
						   $ld_porcentaje_variacion=0;  
						 }
						 if($ld_monto_programado_acumulado==0)
						 {
						   $ld_varia_acum=$ld_monto_ejecutado_acumulado;
						 }
						 else
						 {
						   $ld_varia_acum=$ld_monto_programado_acumulado-$ld_monto_ejecutado_acumulado;
						 }
						 //variacion absoluta  del monto acumulado
						 if($ld_monto_programado_acumulado>$ld_monto_ejecutado_acumulado)
						 {
						   $ld_variacion_absoluta_acumulada=0-($ld_varia_acum);
						 }
						 else
						 {
						   $ld_variacion_absoluta_acumulada=$ld_varia_acum;
						 }
						 //variacion porcentual del monto acumulado
						 if(($ld_monto_programado_acumulado>0)&&($ld_monto_ejecutado_acumulado>0))
						 { 
						    $ld_porcentaje_variacion_acumulada=($ld_monto_programado_acumulado-$ld_monto_ejecutado_acumulado)/($ld_monto_ejecutado_acumulado*100); 
						 }
						 else
						 { 
						    $ld_porcentaje_variacion_acumulada=0; 
						 }
						 // monto de la inversion proximo mes
						 $ld_reprog_proxima=0;
						 $ldt_fechadesde=$ai_meshas+1;
						 $ldt_fechahasta=$ai_meshas+$ai_cant_mes;
					     $lb_valido=$this->uf_spi_reporte_calcular_programado_prox_mes($ldt_fechadesde,$ldt_fechahasta,$ld_reprog_proxima,
						                                                               $ls_codrep,$as_sc_cuenta);

					     if($lb_valido)
						 {
							$li_pos=$this->dts_reporte_final->find("sc_cuenta",$as_sc_cuenta);
							if($li_pos>0)
							{
								 $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_pos);
								 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_pos);   
								 $ls_status=$this->dts_reporte_final->getValue("status",$li_pos);  
								 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_pos);
							
								 $this->dts_reporte_final->updateRow("sc_cuenta",$as_sc_cuenta,$li_pos);
								 $this->dts_reporte_final->updateRow("denominacion",$ls_denominacion,$li_pos);
								 $this->dts_reporte_final->updateRow("tipo",$ls_tipo,$li_pos);
								 $this->dts_reporte_final->updateRow("status",$ls_status,$li_pos);
								 $this->dts_reporte_final->updateRow("nivel",$li_nivel,$li_pos);
								 $this->dts_reporte_final->updateRow("monto_programado",$ld_monto_programado,$li_pos);
								 $this->dts_reporte_final->updateRow("programado_acumulado",$ld_monto_programado_acumulado,$li_pos);
								 $this->dts_reporte_final->updateRow("monto_ejecutado",$ld_monto_ejecutado,$li_pos);
								 $this->dts_reporte_final->updateRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado,$li_pos);
								 $this->dts_reporte_final->updateRow("variacion_absoluta",$ld_variacion_absoluta,$li_pos);
								 $this->dts_reporte_final->updateRow("porcentaje_variacion",$ld_porcentaje_variacion,$li_pos);
								 $this->dts_reporte_final->updateRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada,$li_pos);
								 $this->dts_reporte_final->updateRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada,$li_pos);
								 $this->dts_reporte_final->updateRow("reprogr_prox_periodo",$ld_reprog_proxima,$li_pos);
						         $lb_valido=true;
						   }//if
						}//if 
					 }//if
				   //}//if
				 }//if      
			  }//if
            }//if
			else
			{
				$li_pos=$this->dts_reporte_final->find("sc_cuenta",$as_sc_cuenta);
				if($li_pos>0)
				{
                     $ls_denominacion=$this->dts_reporte_final->getValue("denominacion",$li_pos);
					 $ls_tipo=$this->dts_reporte_final->getValue("tipo",$li_pos);   
					 $ls_status=$this->dts_reporte_final->getValue("status",$li_pos);  
					 $li_nivel=$this->dts_reporte_final->getValue("nivel",$li_pos);
 					 $ld_monto_programado=0;		   			$ld_monto_programado_acumulado=0;
					 $ld_monto_ejecutado=0; 		 		    $ld_monto_ejecutado_acumulado=0;	
					 $ld_variacion_absoluta=0;	   			    $ld_porcentaje_variacion=0;
					 $ld_variacion_absoluta_acumulada=0;	    $ld_porcentaje_variacion_acumulada=0;
					 $ld_reprog_proxima=0;					      			 
				
					 $this->dts_reporte_final->updateRow("sc_cuenta",$as_sc_cuenta,$li_pos);
					 $this->dts_reporte_final->updateRow("denominacion",$ls_denominacion,$li_pos);
					 $this->dts_reporte_final->updateRow("tipo",$ls_tipo,$li_pos);
					 $this->dts_reporte_final->updateRow("status",$ls_status,$li_pos);
					 $this->dts_reporte_final->updateRow("nivel",$li_nivel,$li_pos);
					 $this->dts_reporte_final->updateRow("monto_programado",$ld_monto_programado,$li_pos);
					 $this->dts_reporte_final->updateRow("programado_acumulado",$ld_monto_programado_acumulado,$li_pos);
					 $this->dts_reporte_final->updateRow("monto_ejecutado",$ld_monto_ejecutado,$li_pos);
					 $this->dts_reporte_final->updateRow("ejecutado_acumulado",$ld_monto_ejecutado_acumulado,$li_pos);
					 $this->dts_reporte_final->updateRow("variacion_absoluta",$ld_variacion_absoluta,$li_pos);
					 $this->dts_reporte_final->updateRow("porcentaje_variacion",$ld_porcentaje_variacion,$li_pos);
					 $this->dts_reporte_final->updateRow("variacion_absoluta_acumulada",$ld_variacion_absoluta_acumulada,$li_pos);
					 $this->dts_reporte_final->updateRow("porcentaje_variacion_acumulado",$ld_porcentaje_variacion_acumulada,$li_pos);
					 $this->dts_reporte_final->updateRow("reprogr_prox_periodo",$ld_reprog_proxima,$li_pos);
					 $lb_valido=true;
			  }//if
			}//else
      }//if 
	  return $lb_valido;
	}//fin uf_spi_reportes_procesar_cuentas
/**********************************************************************************************************************************/
    function uf_spi_reportes_llenar_datastore_cuentas()
    { /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	      Function :	uf_spi_reportes_llenar_datastore_cuentas
	  //        Argumentos :    
      //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	  //	   Description :	Reporte que genera salida  del Ejecucion Financiera Formato 3
	  //        Creado por :    Ing. Yozelin Barragán.
	  //    Fecha Creación :    03/09/2006                       Fecha última Modificacion :      Hora :
  	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql=" SELECT * ".
              " FROM   spi_cuentas ".
              " WHERE  codemp='0001' AND (spi_cuenta like '306010000%' OR spi_cuenta like '306020000%' OR  ".
			  "        spi_cuenta like '307000000%' OR spi_cuenta like '308000000%' OR spi_cuenta like '309000000%' OR".
			  "        spi_cuenta like '310000000%' )  ";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		    $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_spg_reportes_llenar_datastore_cuentas  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			$lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $datos=$this->io_sql->obtener_datos($rs_data);
		  $this->dts_spi_cuentas->data=$datos;
		  $lb_valido=true;			
		}
		else
		{
		   $lb_valido = false;
		}
		$this->io_sql->free_result($rs_data);   
	  }//else
    return  $lb_valido;
   }//fin uf_spi_reportes_llenar_datastore_cuentas()
/****************************************************************************************************************************************/	
    function uf_spi_reporte_calcular_ejecutado($as_spi_cuenta,&$ad_previsto,&$ad_devengado,&$ad_cobrado,&$ad_cobrado_acumulado,
	                                           &$ad_cobrado_anticipado,&$ad_aumento,&$ad_disminucion,$adt_fecini,$adt_fecfin)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_calcular_ejecutado
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
	  $lb_valido=true;	
	  $ad_previsto=0;	          $ad_devengado=0;
	  $ad_cobrado=0;	          $ad_cobrado_anticipado=0;
	  $ad_aumento=0;	          $ad_disminucion=0;
	  $ad_cobrado_acumulado=0;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $l_mesdes=substr($adt_fecini,5,2);
	  $l_meshas=substr($adt_fecfin,5,2);
	  $li_mesdes=intval($l_mesdes);
	  $li_meshas=intval($l_meshas);
	  $ldt_mesdes=$li_ano."-".$this->io_function->uf_cerosizquierda($li_mesdes,2);
	  $ldt_meshas=$li_ano."-".$this->io_function->uf_cerosizquierda($li_meshas,2);
	  $as_spi_cuenta=$this->int_spi->uf_spi_cuenta_sin_cero($as_spi_cuenta);
	  $as_spi_cuenta=$as_spi_cuenta."%";
	  $ls_sql=" SELECT * ".
			  " FROM   spi_dt_cmp ".
			  " WHERE  codemp='".$this->ls_codemp."'  AND  spi_cuenta like '".$as_spi_cuenta."'";
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  { // error interno sql
		$this->io_msg->message("CLASE->sigesp_scg_reporte_comparado_0714  MÉTODO->uf_spi_reporte_calcular_ejecutado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
		  $ls_opera=$this->int_spi->uf_operacion_codigo_mensaje($ls_operacion);
		  $ls_mensaje=strtoupper($ls_opera); // devuelve cadena en MAYUSCULAS
		  $li_pos_i=strpos($ls_mensaje,"I"); 
		  if (!($li_pos_i===false)) 
		  { 
		    $ad_previsto=$ad_previsto+$ld_monto; 
		  }
		 
		  if($ldt_fecha<=$ldt_meshas)
		  {
			  $li_pos_c=strpos($ls_mensaje,"C"); 
			  if (!($li_pos_c===false)) 
			  {	
				 $ad_cobrado_acumulado=$ad_cobrado_acumulado+$ld_monto;
			  }
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
		}//if		
	   $this->io_sql->free_result($rs_data);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spi_reporte_calcular_ejecutado
/****************************************************************************************************************************************/
    function uf_spi_reporte_calcular_programado_prox_mes($li_mesdes,$li_meshas,&$ad_monto_programado,$as_codrep,$as_sc_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spi_reporte_calcular_programado_prox_mes
	 //         Access :	private
	 //     Argumentos :    $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_programado // monto programado del mes (referencia)  
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	metodo que calcula los montos programados y los acumulados
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    04/09/2006          Fecha última Modificacion :              Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;	
	  $ad_monto_programado=0; 
	   if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	     $ls_sql=" SELECT sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spi_plantillacuentareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND spi_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spi_cuenta, nivel, status, denominacion ";
	  }
	  else
	  {
	    $ls_sql=" SELECT sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spi_plantillacuentareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND spi_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spi_cuenta,nivel, status, denominacion ";
	  }
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
	     $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_spi_reporte_calcular_programado_prox_mes  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 $lb_valido = false;
	 }
	 else
	 {
	  	if($row=$this->io_sql->fetch_row($rs_data))
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
		}//if
	    $this->io_sql->free_result($rs_data);
     }//else
	 return $lb_valido;
   }//fin uf_spi_reporte_calcular_programado_prox_mes
/**********************************************************************************************************************************/	
    function uf_spi_reporte_cargar_programado($as_codrep,$as_sc_cuenta,&$ad_enero,&$ad_febrero,&$ai_nivel,&$as_status,
											  &$ad_marzo,&$ad_abril,&$ad_mayo,&$ad_junio,&$ad_julio,&$ad_agosto,
											  &$ad_septiembre,&$ad_octubre,&$ad_noviembre,&$ad_diciembre)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_cargar_programado
	 //         Access :	private
	 //     Argumentos :    $as_codrep  -->  codigo del reporte
	 //                     $as_sc_cuenta -->  codigo de la  cuenta 
	 //                     $ad_enero .. $ad_diciembre --> monto programado para cada  mes    
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte por referencia los saldos iniciales programados y ejecutados.   
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    24/08/2006               Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = false;
	  if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	    $ls_sql=" SELECT sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spi_plantillacuentareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND spi_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spi_cuenta, nivel, status, denominacion ";
		}
		else
		{
		 $ls_sql=" SELECT sum(enero) as enero,sum(febrero) as febrero, sum(marzo) as marzo, ".
              "        sum(abril) as abril, sum(mayo) as mayo,sum(junio) as junio, sum(julio) as julio, ".
       		  "		   sum(agosto) as agosto, sum(septiembre) as septiembre,sum(octubre) as octubre, ".
              "        sum(noviembre) as noviembre,sum(diciembre) as diciembre, nivel, status, denominacion ".
              " FROM   spi_plantillacuentareporte ".
              " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND spi_cuenta='".$as_sc_cuenta."' ".
              " GROUP BY spi_cuenta,nivel, status, denominacion ";		
		}
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {   // error interno sql
		  $this->io_msg->message("CLASE->sigesp_spg_reporte_comparado_0714  MÉTODO->uf_scg_reporte_cargar_programado  ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		  $lb_valido = false;
	  }
	  else
	  {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			   $ai_nivel=$row["nivel"];
			   $as_status=$row["status"];
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
			   $ad_asignado=0;
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
   }//fin uf_scg_reporte_select_saldo_empresa
/****************************************************************************************************************************************/	
}//fin de clase
?>