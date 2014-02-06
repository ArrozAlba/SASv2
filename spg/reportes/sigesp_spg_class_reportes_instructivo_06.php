<?php
class sigesp_spg_class_reportes_instructivo_06
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
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function  sigesp_spg_class_reportes_instructivo_06()
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
		$this->io_fecha = new class_fecha();
		$this->io_mensajes = new class_mensajes();
		$this->ls_codemp = $_SESSION["la_empresa"]["codemp"];
	    $this->ls_gestor = $_SESSION["ls_gestor"];
	    $this->li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$this->loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
    }
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVO 06  - EJECUCION FINANCIERA DE LOS PROYECTOS / ACCIONES CENTRALIZADAS DEL ORGANO //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    function uf_spg_reporte_ejecucion_financiera_pry_acc($adt_fecdes,$adt_fechas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_consolidado_de_ejecucion_trimestral
	 //         Access :	private
	 //     Argumentos :    $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta   
	 //                     $as_mesdes  -----> mes desde         
	 //                     $as_meshas  -----> mes hasta         
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 06 del EJECUCION FINANCIERA DE LOS PROYECTOS / ACCIONES CENTRALIZADAS DEL ORGANO
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    15/10/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ai_mes = intval(substr($adt_fechas,5,2));
	 $ls_sql=" SELECT  spg_cuentas.codestpro1, MAX(spg_ep1.denestpro1) as denominacion, ".
	         "         sum(spg_cuentas.enero) as enero, sum(spg_cuentas.febrero) as febrero , ".
       		 "	       sum(spg_cuentas.marzo) as marzo, sum(spg_cuentas.abril) as abril, sum(spg_cuentas.mayo) as mayo, sum(spg_cuentas.junio) as junio, ".
             "         sum(spg_cuentas.julio) as julio, sum(spg_cuentas.agosto) as agosto, sum(spg_cuentas.septiembre) as septiembre,  ".
             "         sum(spg_cuentas.octubre) as octubre, sum(spg_cuentas.noviembre) as noviembre, sum(spg_cuentas.diciembre) as diciembre  ".
             "     FROM spg_cuentas, spg_ep1 ".                                                                    
             "        WHERE spg_cuentas.codemp='".$this->ls_codemp."'  ".
			 " 			  AND spg_cuentas.codemp = spg_ep1.codemp  ".
			 "            AND spg_cuentas.status = 'C' ".
			 "			  AND spg_cuentas.codestpro1 = spg_ep1.codestpro1 ".
			 "			  AND spg_cuentas.estcla = spg_ep1.estcla ".
			 "			  GROUP BY spg_cuentas.codestpro1 ".                                                               
			 "			  ORDER BY spg_cuentas.codestpro1 ";
		 	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivo_06 ". 
			                            "MÉTODO->uf_spg_reporte_ejecucion_financiera_pry_acc ".
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
			   $ld_programado_mensual   = 0;
			   $ld_programado_acumulado = 0;
			   $ld_ejecutado_mens_comp  = 0;
			   $ld_ejecutado_mens_caus  = 0;
			   $ld_ejecutado_mens_paga  = 0;
			   $ld_ejecutado_mens_aume  = 0;
			   $ld_ejecutado_mens_dism  = 0;
			   $ld_ejecutado_acum_comp  = 0;
			   $ld_ejecutado_acum_caus  = 0;
			   $ld_ejecutado_acum_paga  = 0;
			   $ld_ejecutado_acum_aume  = 0;
			   $ld_ejecutado_acum_dism  = 0;
			   $ld_variacion_comp       = 0;
			   $ld_variacion_caus       = 0;
			   $ld_prevision_prox_mes   = 0;
			   $ls_codestpro1   =  $row["codestpro1"];
			   $ls_denominacion =  $row["denominacion"];
			   $ld_enero        =  $row["enero"];
			   $ld_febrero      =  $row["febrero"];
			   $ld_marzo	    =  $row["marzo"];
			   $ld_abril	    =  $row["abril"];
			   $ld_mayo	        =  $row["mayo"];
			   $ld_junio	    =  $row["junio"];
			   $ld_julio	    =  $row["julio"];
			   $ld_agosto	    =  $row["agosto"];
			   $ld_septiembre   =  $row["septiembre"];
			   $ld_octubre      =  $row["octubre"];
			   $ld_noviembre    =  $row["noviembre"];
			   $ld_diciembre    =  $row["diciembre"];
			   
			   $lb_valido=$this->uf_spg_ejecutado_mensual($ls_codestpro1,"","",$adt_fecdes,$adt_fechas,&$ld_ejecutado_mens_comp,
			                                                 &$ld_ejecutado_mens_caus,&$ld_ejecutado_mens_paga,&$ld_ejecutado_mens_aume,&$ld_ejecutado_mens_dism);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado($ls_codestpro1,"","",$adt_fechas,&$ld_ejecutado_acum_comp,
																&$ld_ejecutado_acum_caus,&$ld_ejecutado_acum_paga,&$ld_ejecutado_acum_aume,
																&$ld_ejecutado_acum_dism);
			   }//if
			   switch($ai_mes)
			   {
			    case 1  : $ld_programado_mensual   = $ld_enero;
				          $ld_programado_acumulado = $ld_enero;
						  $ld_prevision_prox_mes   = $ld_febrero;
						  break;
						  
				case 2  : $ld_programado_mensual   = $ld_febrero;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero;
						  $ld_prevision_prox_mes   = $ld_marzo;
						  break;
						  
				case 3  : $ld_programado_mensual   = $ld_marzo;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo;
						  $ld_prevision_prox_mes   = $ld_abril;
						  break;
						  
				case 4  : $ld_programado_mensual   = $ld_abril;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril;
						  $ld_prevision_prox_mes   = $ld_mayo;
						  break;
						  
				case 5 :  $ld_programado_mensual   = $ld_mayo;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo;
						  $ld_prevision_prox_mes   = $ld_junio;
						  break;
						  
				case 6 :  $ld_programado_mensual   = $ld_junio;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio;
						  $ld_prevision_prox_mes   = $ld_julio;
						  break;		 
						  
				case 7 :  $ld_programado_mensual   = $ld_julio;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio;
						  $ld_prevision_prox_mes   = $ld_agosto;
						  break;
						  
				case 8 :  $ld_programado_mensual   = $ld_agosto;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto;
						  $ld_prevision_prox_mes   = $ld_septiembre;
						  break;
						  
				case 9 :  $ld_programado_mensual   = $ld_septiembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre;
						  $ld_prevision_prox_mes   = $ld_octubre;
						  break;
						  
				case 10 : $ld_programado_mensual   = $ld_octubre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre;
						  $ld_prevision_prox_mes   = $ld_noviembre;
						  break;
						  
				case 11 : $ld_programado_mensual   = $ld_noviembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre + $ld_noviembre;
						  $ld_prevision_prox_mes   = $ld_diciembre;
						  break;
						  
				case 12 : $ld_programado_mensual   = $ld_diciembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre + $ld_noviembre + $ld_diciembre;
						  $ld_prevision_prox_mes   = 0;
						  break;		 		 		 		 		 		 		 		 		 		  
			   }
			   
			   $ls_codigo=substr($ls_codestpro1,-$this->loncodestpro1);
			   $ld_prevision_prox_mes = $ld_prevision_prox_mes + $ld_programado_mensual - $ld_ejecutado_mens_comp;
			   $this->dts_reporte->insertRow("codigo",$ls_codigo);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("programado_mensual",$ld_programado_mensual);
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
			   $this->dts_reporte->insertRow("ejecutado_mens_comp",$ld_ejecutado_mens_comp);
			   $this->dts_reporte->insertRow("ejecutado_mens_caus",$ld_ejecutado_mens_caus);
		  	   $this->dts_reporte->insertRow("ejecutado_acum_comp",$ld_ejecutado_acum_comp);					 
			   $this->dts_reporte->insertRow("ejecutado_acum_caus",$ld_ejecutado_acum_caus);					 
			   $this->dts_reporte->insertRow("prevision_prox_mes",$ld_prevision_prox_mes);	
			   $lb_valido=true;
		    }//while
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVO 06  - EJECUCION FINANCIERA DE LAS ACCIONES ESPECIFICAS DEL ORGANO //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    function uf_spg_reporte_ejecucion_financiera_acc_esp($adt_fecdes,$adt_fechas)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_ejecucion_financiera_acc_esp
	 //         Access :	private
	 //     Argumentos :    $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta          
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 06 del EJECUCION FINANCIERA DE LAS ACCIONES ESPECIFICAS DEL ORGANO
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    15/10/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ai_mes = intval(substr($adt_fechas,5,2));
	 $ls_sql=" SELECT  spg_cuentas.codestpro1, spg_cuentas.codestpro2, MAX(spg_ep1.denestpro1) as denominacion, MAX(spg_ep2.denestpro2) as denestpro2,".
	         "         sum(spg_cuentas.enero) as enero, sum(spg_cuentas.febrero) as febrero , ".
       		 "	       sum(spg_cuentas.marzo) as marzo, sum(spg_cuentas.abril) as abril, sum(spg_cuentas.mayo) as mayo, sum(spg_cuentas.junio) as junio, ".
             "         sum(spg_cuentas.julio) as julio, sum(spg_cuentas.agosto) as agosto, sum(spg_cuentas.septiembre) as septiembre,  ".
             "         sum(spg_cuentas.octubre) as octubre, sum(spg_cuentas.noviembre) as noviembre, sum(spg_cuentas.diciembre) as diciembre  ".
             "     FROM spg_cuentas, spg_ep1, spg_ep2 ".                                                                    
             "        WHERE spg_cuentas.codemp='".$this->ls_codemp."'  ".
			 " 			  AND spg_cuentas.codemp = spg_ep1.codemp  ".
			 "            AND spg_cuentas.status = 'C' ".
			 "			  AND spg_cuentas.codestpro1 = spg_ep1.codestpro1 ".
			 "			  AND spg_cuentas.estcla = spg_ep1.estcla ".
			 " 		      AND spg_cuentas.codemp = spg_ep2.codemp  ".
			 "            AND spg_cuentas.codestpro1 = spg_ep2.codestpro1 ".
			 "			  AND spg_cuentas.codestpro2 = spg_ep2.codestpro2 ".
			 "			  AND spg_cuentas.estcla     = spg_ep2.estcla ".
			 "			  GROUP BY spg_cuentas.codestpro1, spg_cuentas.codestpro2 ".                                                               
			 "			  ORDER BY spg_cuentas.codestpro1, spg_cuentas.codestpro2 ";	 	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivo_06 ". 
			                            "MÉTODO->uf_spg_reporte_ejecucion_financiera_pry_acc ".
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
			   $ld_programado_mensual   = 0;
			   $ld_programado_acumulado = 0;
			   $ld_ejecutado_mens_comp  = 0;
			   $ld_ejecutado_mens_caus  = 0;
			   $ld_ejecutado_mens_paga  = 0;
			   $ld_ejecutado_mens_aume  = 0;
			   $ld_ejecutado_mens_dism  = 0;
			   $ld_ejecutado_acum_comp  = 0;
			   $ld_ejecutado_acum_caus  = 0;
			   $ld_ejecutado_acum_paga  = 0;
			   $ld_ejecutado_acum_aume  = 0;
			   $ld_ejecutado_acum_dism  = 0;
			   $ld_variacion_comp       = 0;
			   $ld_variacion_caus       = 0;
			   $ld_prevision_prox_mes   = 0;
			   $ls_codestpro1   =  $row["codestpro1"];
			   $ls_codestpro2   =  $row["codestpro2"];
			   $ls_denominacion =  $row["denominacion"];
			   $ls_denestpro2   =  $row["denestpro2"];
			   $ld_enero        =  $row["enero"];
			   $ld_febrero      =  $row["febrero"];
			   $ld_marzo	    =  $row["marzo"];
			   $ld_abril	    =  $row["abril"];
			   $ld_mayo	        =  $row["mayo"];
			   $ld_junio	    =  $row["junio"];
			   $ld_julio	    =  $row["julio"];
			   $ld_agosto	    =  $row["agosto"];
			   $ld_septiembre   =  $row["septiembre"];
			   $ld_octubre      =  $row["octubre"];
			   $ld_noviembre    =  $row["noviembre"];
			   $ld_diciembre    =  $row["diciembre"];
			   
			   $lb_valido=$this->uf_spg_ejecutado_mensual($ls_codestpro1,$ls_codestpro2 ,"",$adt_fecdes,$adt_fechas,&$ld_ejecutado_mens_comp,
			                                                 &$ld_ejecutado_mens_caus,&$ld_ejecutado_mens_paga,&$ld_ejecutado_mens_aume,&$ld_ejecutado_mens_dism);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado($ls_codestpro1,$ls_codestpro2,"",$adt_fechas,&$ld_ejecutado_acum_comp,
																&$ld_ejecutado_acum_caus,&$ld_ejecutado_acum_paga,&$ld_ejecutado_acum_aume,
																&$ld_ejecutado_acum_dism);
			   }//if
			   switch($ai_mes)
			   {
			    case 1  : $ld_programado_mensual   = $ld_enero;
				          $ld_programado_acumulado = $ld_enero;
						  $ld_prevision_prox_mes   = $ld_febrero;
						  break;
						  
				case 2  : $ld_programado_mensual   = $ld_febrero;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero;
						  $ld_prevision_prox_mes   = $ld_marzo;
						  break;
						  
				case 3  : $ld_programado_mensual   = $ld_marzo;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo;
						  $ld_prevision_prox_mes   = $ld_abril;
						  break;
						  
				case 4  : $ld_programado_mensual   = $ld_abril;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril;
						  $ld_prevision_prox_mes   = $ld_mayo;
						  break;
						  
				case 5 :  $ld_programado_mensual   = $ld_mayo;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo;
						  $ld_prevision_prox_mes   = $ld_junio;
						  break;
						  
				case 6 :  $ld_programado_mensual   = $ld_junio;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio;
						  $ld_prevision_prox_mes   = $ld_julio;
						  break;		 
						  
				case 7 :  $ld_programado_mensual   = $ld_julio;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio;
						  $ld_prevision_prox_mes   = $ld_agosto;
						  break;
						  
				case 8 :  $ld_programado_mensual   = $ld_agosto;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto;
						  $ld_prevision_prox_mes   = $ld_septiembre;
						  break;
						  
				case 9 :  $ld_programado_mensual   = $ld_septiembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre;
						  $ld_prevision_prox_mes   = $ld_octubre;
						  break;
						  
				case 10 : $ld_programado_mensual   = $ld_octubre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre;
						  $ld_prevision_prox_mes   = $ld_noviembre;
						  break;
						  
				case 11 : $ld_programado_mensual   = $ld_noviembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre + $ld_noviembre;
						  $ld_prevision_prox_mes   = $ld_diciembre;
						  break;
						  
				case 12 : $ld_programado_mensual   = $ld_diciembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre + $ld_noviembre + $ld_diciembre;
						  $ld_prevision_prox_mes   = 0;
						  break;		 		 		 		 		 		 		 		 		 		  
			   }
			   
			   $ls_codigo=substr($ls_codestpro1,-$this->loncodestpro1);
			   $ls_codestpro2=substr($ls_codestpro2,-$this->loncodestpro2);
			   $ld_prevision_prox_mes = $ld_prevision_prox_mes + $ld_programado_mensual - $ld_ejecutado_mens_comp;
			   $this->dts_reporte->insertRow("codigo",$ls_codigo);
			   $this->dts_reporte->insertRow("codestpro2",$ls_codestpro2);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("denestpro2",$ls_denestpro2);
			   $this->dts_reporte->insertRow("programado_mensual",$ld_programado_mensual);
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
			   $this->dts_reporte->insertRow("ejecutado_mens_comp",$ld_ejecutado_mens_comp);
			   $this->dts_reporte->insertRow("ejecutado_mens_caus",$ld_ejecutado_mens_caus);
		  	   $this->dts_reporte->insertRow("ejecutado_acum_comp",$ld_ejecutado_acum_comp);					 
			   $this->dts_reporte->insertRow("ejecutado_acum_caus",$ld_ejecutado_acum_caus);					 
			   $this->dts_reporte->insertRow("prevision_prox_mes",$ld_prevision_prox_mes);	
			   $lb_valido=true;
		    }//while
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  INSTRUCTIVO 06  - INFORMACION MENSUAL DE LA EJECUCION FINANCIERA                            //
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    function uf_spg_reporte_informacion_mensual_eje_fin($adt_fecdes,$adt_fechas,$as_nivel)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_informacion_mensual_eje_fin
	 //         Access :	private
	 //     Argumentos :    $adt_fecdes  -----> fechas desde 
	 //                     $adt_fechas  -----> fechas hasta          
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del instructivo 06 - INFORMACION MENSUAL DE LA EJECUCION FINANCIERA
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    28/10/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ai_mes = intval(substr($adt_fechas,5,2));
	 if($as_nivel == 'C')
	 {
	  $ls_cadena = " AND spg_cuentas.nivel = 1 ";
	 }
	 else
	 {
	  $ls_cadena = " AND spg_cuentas.nivel <= 4";
	 }
	 $ls_sql=" SELECT  spg_cuentas.codestpro1, spg_cuentas.spg_cuenta, MAX(spg_ep1.denestpro1) as denominacion, MAX(spg_cuentas.status) as status, ".
	         "         sum(spg_cuentas.enero) as enero, sum(spg_cuentas.febrero) as febrero , ".
       		 "	       sum(spg_cuentas.marzo) as marzo, sum(spg_cuentas.abril) as abril, sum(spg_cuentas.mayo) as mayo, sum(spg_cuentas.junio) as junio, ".
             "         sum(spg_cuentas.julio) as julio, sum(spg_cuentas.agosto) as agosto, sum(spg_cuentas.septiembre) as septiembre,  ".
             "         sum(spg_cuentas.octubre) as octubre, sum(spg_cuentas.noviembre) as noviembre, sum(spg_cuentas.diciembre) as diciembre  ".
             "     FROM spg_cuentas, spg_ep1 ".                                                                    
             "        WHERE spg_cuentas.codemp='".$this->ls_codemp."'  ".
			 $ls_cadena. 
			 " 			  AND spg_cuentas.codemp = spg_ep1.codemp  ".
			 "			  AND spg_cuentas.codestpro1 = spg_ep1.codestpro1 ".
			 "			  GROUP BY spg_cuentas.codestpro1, spg_cuentas.spg_cuenta ".                                                               
			 "			  ORDER BY spg_cuentas.codestpro1, spg_cuentas.spg_cuenta ";
		 	
     $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivo_06 ". 
			                            "MÉTODO->uf_spg_reporte_informacion_mensual_eje_fin ".
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
			   $ld_programado_mensual   = 0;
			   $ld_programado_acumulado = 0;
			   $ld_ejecutado_mens_comp  = 0;
			   $ld_ejecutado_mens_caus  = 0;
			   $ld_ejecutado_mens_paga  = 0;
			   $ld_ejecutado_mens_aume  = 0;
			   $ld_ejecutado_mens_dism  = 0;
			   $ld_ejecutado_acum_comp  = 0;
			   $ld_ejecutado_acum_caus  = 0;
			   $ld_ejecutado_acum_paga  = 0;
			   $ld_ejecutado_acum_aume  = 0;
			   $ld_ejecutado_acum_dism  = 0;
			   $ld_variacion_comp       = 0;
			   $ld_variacion_caus       = 0;
			   $ld_prevision_prox_mes   = 0;
			   $ls_codestpro1   =  $row["codestpro1"];
			   $ls_denominacion =  $row["denominacion"];
			   $ls_spg_cuenta   =  $row["spg_cuenta"];
			   $ls_status       =  $row["status"];
			   $ld_enero        =  $row["enero"];
			   $ld_febrero      =  $row["febrero"];
			   $ld_marzo	    =  $row["marzo"];
			   $ld_abril	    =  $row["abril"];
			   $ld_mayo	        =  $row["mayo"];
			   $ld_junio	    =  $row["junio"];
			   $ld_julio	    =  $row["julio"];
			   $ld_agosto	    =  $row["agosto"];
			   $ld_septiembre   =  $row["septiembre"];
			   $ld_octubre      =  $row["octubre"];
			   $ld_noviembre    =  $row["noviembre"];
			   $ld_diciembre    =  $row["diciembre"];
			   
			   $lb_valido=$this->uf_spg_ejecutado_mensual($ls_codestpro1,"",$ls_spg_cuenta,$adt_fecdes,$adt_fechas,&$ld_ejecutado_mens_comp,
			                                                 &$ld_ejecutado_mens_caus,&$ld_ejecutado_mens_paga,&$ld_ejecutado_mens_aume,&$ld_ejecutado_mens_dism);
			   if($lb_valido)
		       {
				   $lb_valido=$this->uf_spg_ejecutado_acumulado($ls_codestpro1,"",$ls_spg_cuenta,$adt_fechas,&$ld_ejecutado_acum_comp,
																&$ld_ejecutado_acum_caus,&$ld_ejecutado_acum_paga,&$ld_ejecutado_acum_aume,
																&$ld_ejecutado_acum_dism);
			   }//if
			   switch($ai_mes)
			   {
			    case 1  : $ld_programado_mensual   = $ld_enero;
				          $ld_programado_acumulado = $ld_enero;
						  $ld_prevision_prox_mes   = $ld_febrero;
						  break;
						  
				case 2  : $ld_programado_mensual   = $ld_febrero;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero;
						  $ld_prevision_prox_mes   = $ld_marzo;
						  break;
						  
				case 3  : $ld_programado_mensual   = $ld_marzo;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo;
						  $ld_prevision_prox_mes   = $ld_abril;
						  break;
						  
				case 4  : $ld_programado_mensual   = $ld_abril;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril;
						  $ld_prevision_prox_mes   = $ld_mayo;
						  break;
						  
				case 5 :  $ld_programado_mensual   = $ld_mayo;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo;
						  $ld_prevision_prox_mes   = $ld_junio;
						  break;
						  
				case 6 :  $ld_programado_mensual   = $ld_junio;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio;
						  $ld_prevision_prox_mes   = $ld_julio;
						  break;		 
						  
				case 7 :  $ld_programado_mensual   = $ld_julio;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio;
						  $ld_prevision_prox_mes   = $ld_agosto;
						  break;
						  
				case 8 :  $ld_programado_mensual   = $ld_agosto;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto;
						  $ld_prevision_prox_mes   = $ld_septiembre;
						  break;
						  
				case 9 :  $ld_programado_mensual   = $ld_septiembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre;
						  $ld_prevision_prox_mes   = $ld_octubre;
						  break;
						  
				case 10 : $ld_programado_mensual   = $ld_octubre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre;
						  $ld_prevision_prox_mes   = $ld_noviembre;
						  break;
						  
				case 11 : $ld_programado_mensual   = $ld_noviembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre + $ld_noviembre;
						  $ld_prevision_prox_mes   = $ld_diciembre;
						  break;
						  
				case 12 : $ld_programado_mensual   = $ld_diciembre;
				          $ld_programado_acumulado = $ld_enero + $ld_febrero + $ld_marzo + $ld_abril + $ld_mayo + $ld_junio + $ld_julio + $ld_agosto + $ld_septiembre + $ld_octubre + $ld_noviembre + $ld_diciembre;
						  $ld_prevision_prox_mes   = 0;
						  break;		 		 		 		 		 		 		 		 		 		  
			   }
			   
			   $ls_codigo=substr($ls_codestpro1,-$this->loncodestpro1);
			   $this->dts_reporte->insertRow("codigo",$ls_codigo);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("status",$ls_status);
			   $this->dts_reporte->insertRow("programado_mensual",$ld_programado_mensual);
			   $this->dts_reporte->insertRow("programado_acumulado",$ld_programado_acumulado);
			   $this->dts_reporte->insertRow("ejecutado_mens_comp",$ld_ejecutado_mens_comp);
			   $this->dts_reporte->insertRow("ejecutado_mens_caus",$ld_ejecutado_mens_caus);
			   $this->dts_reporte->insertRow("ejecutado_mens_paga",&$ld_ejecutado_mens_paga);
		  	   $this->dts_reporte->insertRow("ejecutado_acum_comp",$ld_ejecutado_acum_comp);					 
			   $this->dts_reporte->insertRow("ejecutado_acum_caus",$ld_ejecutado_acum_caus);
			   $this->dts_reporte->insertRow("ejecutado_acum_paga",$ld_ejecutado_acum_paga);					 	
			   $lb_valido=true;
		    }//while
	    }//if	
	 $this->io_sql->free_result($rs_data);
	 }//else
     return $lb_valido;
    }//fin uf_spg_reporte_consolidado_de_ejecucion_trimestral
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_spg_ejecutado_mensual($as_codestpro1,$as_codestpro2,$as_spg_cuenta,$adt_fecdes,$adt_fechas,&$ad_comprometer,
	                                  &$ad_causado,&$ad_pagado,&$ad_aumento,&$ad_disminucion)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_ejecutado_mensual
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  -----> Código del Proyecto o Accion
	 //                     $as_codestpro2  -----> Código de la Accción Específica
	 //                     $adt_fechas     -----> Fecha hasta  
	 //                     $ad_comprometer_acumulado  -----> monto comprometer referencia   
	 //                     $ad_causado_acumulado  -----> monto causado referencia   
	 //                     $ad_pagado_acumulado  -----> monto pagado referencia   
	 //                     $ad_aumento_acumulado  -----> monto aumento referencia   
	 //                     $ad_disminucion_acumulado  -----> monto disminucion referencia   
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera los ejcutados por mes
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    16/10/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ad_comprometer = 0;  
	 $ad_causado     = 0;  
	 $ad_pagado      = 0;
	 $ad_aumento     = 0;  
	 $ad_disminucion = 0;
	 $ls_cadena_ep1  = "";
	 $ls_cadena_ep2  = "";
	 $ls_cadena_spg  = "";  
	 if (!empty($as_codestpro1))
	 {
	  $ls_cadena_ep1 = " AND DT.codestpro1 = '".$as_codestpro1."' ";
	 }
	 if (!empty($as_codestpro2))
	 {
	  $ls_cadena_ep2 = " AND DT.codestpro2 = '".$as_codestpro2."' ";
	 }
	 if (!empty($as_spg_cuenta))
	 {
	   $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   $ls_cadena_spg=" AND spg_cuenta like '".$as_spg_cuenta."' ";
	 }  
	 $ls_sql = " SELECT DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
			   "        fecha BETWEEN '".$adt_fecdes."' AND  '".$adt_fechas."' ".
                $ls_cadena_ep1." ".$ls_cadena_ep2." ".$ls_cadena_spg;
              	   
	  $rs_ejecutado=$this->io_sql->select($ls_sql);
	  if($rs_ejecutado===false)
	  { // error interno sql
			$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivo_06 ". 
			                            "MÉTODO->uf_spg_ejecutado_mensual ".
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
    function uf_spg_ejecutado_acumulado($as_codestpro1,$as_codestpro2,$as_spg_cuenta,$adt_fechas,&$ad_comprometer_acumulado,&$ad_causado_acumulado,
	                                    &$ad_pagado_acumulado,&$ad_aumento_acumulado,&$ad_disminucion_acumulado)
    {///////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_ejecutado_acumulado
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
	 //     Creado por :    Ing. Arnaldo Suárez
	 // Fecha Creación :    18/05/2008          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;	 
	 $ls_cadena_ep1  = "";
	 $ls_cadena_ep2  = "";
	 $ls_cadena_spg  = "";  
	 if (!empty($as_codestpro1))
	 {
	  $ls_cadena_ep1 = " AND DT.codestpro1 = '".$as_codestpro1."' ";
	 }
	 if (!empty($as_codestpro2))
	 {
	  $ls_cadena_ep2 = " AND DT.codestpro2 = '".$as_codestpro2."' ";
	 }
	 if (!empty($as_spg_cuenta))
	 {
	   $as_spg_cuenta = $this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   $ls_cadena_spg=" AND spg_cuenta like '".$as_spg_cuenta."' ";
	 }  
	 $ls_sql = " SELECT DT.monto, OP.aumento, OP.disminucion, ".
               "        OP.precomprometer,OP.comprometer, ".
               "        OP.causar, OP.pagar ".
               " FROM   spg_dt_cmp DT, spg_operaciones OP ".
               " WHERE  DT.codemp='".$this->ls_codemp."' AND ".
               "        DT.operacion = OP.operacion AND ".
			   "        fecha <='".$adt_fechas."' ".
                $ls_cadena_ep1." ".$ls_cadena_ep2." ".$ls_cadena_spg;
              
	  $rs_ejecutado=$this->io_sql->select($ls_sql);
	  if($rs_ejecutado===false)
	  { // error interno sql
		$this->io_mensajes->message("CLASE->sigesp_spg_class_reportes_instructivo_06 ". 
			                        "MÉTODO->uf_spg_ejecutado_acumulado ".
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
     }//fin uf_spg_ejecutado_acumulado
	//----------------------------------------------------------------------------------------------------------------------------------

	
}//fin de clase
?>