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
require_once("sigesp_spg_funciones_reportes.php");	
/********************************************************************************************************************************/	
class sigesp_spg_reporte_nuevos
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
	var $sigesp_int_spg;
	var $io_spg_report_funciones;
/********************************************************************************************************************************/	
    function  sigesp_spg_reporte_nuevos()
    {
		$this->fun=new class_funciones() ;
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($this->con);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->dts_cab=new class_datastore();
		$this->dts_reporte=new class_datastore();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int_spg=new class_sigesp_int_spg();
		$this->io_spg_report_funciones=new sigesp_spg_funciones_reportes();
    }
/********************************************************************************************************************************/	
	/////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "ACUMULADO POR CUENTAS FORMATO # 2"   // 
	////////////////////////////////////////////////////////////////
    function uf_spg_reporte_acumulado_cuentas($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                          $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
											  $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin,$ai_nivel,
											  $ab_subniveles,&$ai_MenorNivel,$as_cuentades,$as_cuentahas,
											  $as_codfuefindes,$as_codfuefinhas,$as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_acumulado_cuentas
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
	 //                     adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
	 //                     ai_nivel    // nivel de la cuenta  
	 //                     ab_subniveles  // variable boolean si lo desea con subnivels 
	 //                     ai_MenorNivel  // variabvle que determina el menor nivel de la cuenta
     //	       Returns :	Retorna estructuras ordenadas para la consulta sql
	 //	   Description :	Reporte que genera el reporte del acumulado por cuentas(asignacion,aumneto,disminucion ...) 
	 //     Creado por :    Ing. Wilmer Briceño
	 // Modificado Por :    Yozelin Barragan 
	 // Fecha Creación :    01/02/2006          Fecha última Modificacion : 01/02/2006 Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$lb_existe = false;	 
		$lb_valido = false;
		$lb_ok = true;
        $ld_total=0;
		$asignado_total=0;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("spg_cuenta");
        $ls_str_sql_where="";
		$dts_cuentas=new class_datastore();
		$as_codestpro1_ori  = $this->fun->uf_cerosizquierda($as_codestpro1_ori,25);
		$as_codestpro2_ori  = $this->fun->uf_cerosizquierda($as_codestpro2_ori,25);
		$as_codestpro3_ori  = $this->fun->uf_cerosizquierda($as_codestpro3_ori,25);
		$as_codestpro4_ori  = $this->fun->uf_cerosizquierda($as_codestpro4_ori,25);
		$as_codestpro5_ori  = $this->fun->uf_cerosizquierda($as_codestpro5_ori,25);
		
		$as_codestpro1_des  = $this->fun->uf_cerosizquierda($as_codestpro1_des,25);
		$as_codestpro2_des  = $this->fun->uf_cerosizquierda($as_codestpro2_des,25);
		$as_codestpro3_des  = $this->fun->uf_cerosizquierda($as_codestpro3_des,25);
		$as_codestpro4_des  = $this->fun->uf_cerosizquierda($as_codestpro4_des,25);
		$as_codestpro5_des  = $this->fun->uf_cerosizquierda($as_codestpro5_des,25);
        $this->uf_obtener_rango_programatica($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
		                                     $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
                                             $as_codestpro4_des,$as_codestpro5_des,$ls_Sql_Where,$ls_str_estructura_from,
											 $ls_str_estructura_to,$as_estclades,$as_estclahas);
		$ls_str_sql_where="WHERE PCT.codemp='".$ls_codemp."'";
		$ls_Sql_Where = trim($ls_Sql_Where);
		
        if (!empty($ls_Sql_Where))
        {
           $ls_str_sql_where="WHERE PCT.codemp='".$ls_codemp."' AND ".$ls_Sql_Where;
        }
		if($li_estmodest==1)
		{
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND PCT.codestpro1=spg_ep3.codestpro1 AND PCT.codestpro2=spg_ep3.codestpro2 AND PCT.codestpro3=spg_ep3.codestpro3 AND PCT.estcla=spg_ep3.estcla";
		}
		elseif($li_estmodest==2)
		{
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND PCT.codestpro1=spg_ep5.codestpro1 AND PCT.codestpro2=spg_ep5.codestpro2 AND PCT.codestpro3=spg_ep5.codestpro3 AND PCT.codestpro4=spg_ep5.codestpro4 AND PCT.codestpro5=spg_ep5.codestpro5 AND PCT.estcla=spg_ep5.estcla";
		}
        $ls_mysql=" SELECT DISTINCT PCT.spg_cuenta, PCT.nivel, PCT.denominacion, ".
                  "                 PCT.asignado, PCT.status                     ". 
                  " FROM spg_cuentas PCT, ".$ls_tabla."  ".$ls_str_sql_where." AND ".
                  "      PCT.spg_cuenta BETWEEN '".trim($as_cuentades)."' AND '".trim($as_cuentahas)."' AND  ".
                  "      ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' AND ".
				  "      (PCT.nivel<='".$ai_nivel."') ".$ls_cadena_fuefin."   ".
                  " ORDER BY PCT.spg_cuenta ";
		$rs_cuentas=$this->SQL->select($ls_mysql);
		//print $ls_mysql."<br><br><br><br>";
		if($rs_cuentas===false)
		{   //error interno sql
		   $this->io_msg->message("Error en Reporte1".$this->fun->uf_convertirmsg($this->SQL->message));
           return false;
		}
		else
        {
		   while($row=$this->SQL->fetch_row($rs_cuentas))
		   {
			   $ls_spg_cuenta = $row["spg_cuenta"];
			   $ls_denominacion = $row["denominacion"];
			   $li_nivel = $row["nivel"];
			   $ls_status = $row["status"];
			   $ls_asignado = $row["asignado"];
			   if ($ai_nivel=$li_nivel)
			   {
		          $lb_si_va = true;
			   }
			   if ( $ab_subniveles and ($ai_nivel<$li_nivel) )
			   {  
			      $lb_si_va = true;
			   }
			   if ($lb_si_va==true)
			   {
		      	  if ($li_nivel < $ai_MenorNivel) { $ai_MenorNivel = $li_nivel; }
				  // Calculo lo Ejecutado y acumulado
					if (!$this->uf_calcular_acumulado_operaciones_por_cuenta($ls_str_sql_where,$ls_str_estructura_from,				                                                                              $ls_str_estructura_to,$ls_spg_cuenta,
																			 $adt_fecini,$adt_fecfin,$ldec_monto_asignado,                                                                              $ldec_monto_aumento,$ldec_monto_disminucion,
																			 $ldec_monto_precompromiso,$ldec_monto_compromiso,                                                                              $ldec_monto_causado,$ldec_monto_pagado,
																			 $ldec_monto_aumento_a,$ldec_monto_disminucion_a,                                                                              $ldec_monto_precompromiso_a,
																			 $ldec_monto_compromiso_a,$ldec_monto_causado_a,
																			 $ldec_monto_pagado_a,$as_codfuefindes,$as_codfuefinhas))
					{
					   return false; 
					} 
					$ldec_monto_actualizado = ($ldec_monto_asignado+$ldec_monto_aumento_a+$ldec_monto_aumento-$ldec_monto_disminucion_a-$ldec_monto_disminucion);
					$ldec_saldo_comprometer = ($ldec_monto_asignado+$ldec_monto_aumento_a+$ldec_monto_aumento-$ldec_monto_disminucion_a-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);
					$ldec_por_pagar = $ldec_monto_causado-$ldec_monto_pagado;
					
					$this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
					$this->dts_reporte->insertRow("denominacion",$ls_denominacion);							
					$this->dts_reporte->insertRow("nivel",$li_nivel);							
					$this->dts_reporte->insertRow("asignado",$ldec_monto_asignado);	
					$this->dts_reporte->insertRow("aumento",$ldec_monto_aumento);							
					$this->dts_reporte->insertRow("disminucion",$ldec_monto_disminucion);
					$this->dts_reporte->insertRow("precompromiso",$ldec_monto_precompromiso);
					$this->dts_reporte->insertRow("compromiso",$ldec_monto_compromiso);							
					$this->dts_reporte->insertRow("causado",$ldec_monto_causado);							
					$this->dts_reporte->insertRow("pagado",$ldec_monto_pagado);
					$this->dts_reporte->insertRow("aumento_a",$ldec_monto_aumento_a);							
					$this->dts_reporte->insertRow("disminucion_a",$ldec_monto_disminucion_a);
					$this->dts_reporte->insertRow("precompromiso_a",$ldec_monto_precompromiso_a);
					$this->dts_reporte->insertRow("compromiso_a",$ldec_monto_compromiso_a);							
					$this->dts_reporte->insertRow("causado_a",$ldec_monto_causado_a);																												
					$this->dts_reporte->insertRow("pagado_a",$ldec_monto_pagado_a);
					$this->dts_reporte->insertRow("monto_actualizado",$ldec_monto_actualizado);
					$this->dts_reporte->insertRow("saldo_comprometer",$ldec_saldo_comprometer);
					$this->dts_reporte->insertRow("por_pagar",$ldec_por_pagar);		
					$this->dts_reporte->insertRow("status",$ls_status);		
					$lb_valido = true;
		   } // end if 
		   if($li_nivel==1)
		   {
			  $ld_total=$ld_total+$ldec_saldo_comprometer;
		   }//if($li_nivel==1) 
		 } // end WHILE
	 } //else
	 return $lb_valido;
   } // fin function uf_spg_reporte_acumulado_cuentas
/********************************************************************************************************************************/	
    function uf_obtener_rango_programatica($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
                                           $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
                                           $as_codestpro4_des,$as_codestpro5_des,&$as_Sql_Where,&$as_str_estructura_from,
                                           &$as_str_estructura_to,$as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_obtener_rango_programatica -> proviene de uf_spg_reporte_acumulado_cuentas
	 //       Access :	private
	 //   Argumentos :  as_codestpro1_ori ... as_estprepro5_ori,as_codestpro1_des ... as_estprepro5_des
	 //                 as_estclades   // estatus desde de clasificaicones de la estructura presupuestaria IPSFA
	 //                 as_estclahas   // estatus hasta de clasificaicones de la estructura presupuestaria IPSFA
     //	    Returns :	Retorna estructuras ordenadas para la consulta sql
	 //	Description :	Método que determina y ordena el minimo por niveles de la estructuras presupuestarias
     //                 para luego concatenar en una variables de origen y una de destino 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $ls_gestor = $_SESSION["ls_gestor"];
		 if(strtoupper($ls_gestor)=="MYSQLT")
		 {
		   $ls_concat="CONCAT";
		   $ls_cadena=",";
		 }
		 else
		 {
		   $ls_concat="";
		   $ls_cadena="||";
		 }
		 $ls_CodEstPro1_desde = $as_codestpro1_ori;
		 $ls_CodEstPro1_hasta = $as_codestpro1_des;
		 $ls_CodEstPro2_desde = $as_codestpro2_ori;
		 $ls_CodEstPro2_hasta = $as_codestpro2_des;
		 $ls_CodEstPro3_desde = $as_codestpro3_ori;
		 $ls_CodEstPro3_hasta = $as_codestpro3_des;
		 $ls_CodEstPro4_desde = $as_codestpro4_ori;
		 $ls_CodEstPro4_hasta = $as_codestpro4_des;
		 $ls_CodEstPro5_desde = $as_codestpro5_ori;
		 $ls_CodEstPro5_hasta = $as_codestpro5_des;
		 
         // Nivel 1
		 if ((($ls_CodEstPro1_desde!="********************") and ($ls_CodEstPro1_hasta!="********************")) and
		     (($ls_CodEstPro1_desde!="0000000000000000000000000") and ($ls_CodEstPro1_hasta!="0000000000000000000000000")))
		 { 
			$ls_str_w1  = " ".$ls_concat."(PCT.codestpro1".$ls_cadena." ";
			$ls_str_w1f = $ls_CodEstPro1_desde;
			$ls_str_w1t = $ls_CodEstPro1_hasta;
		 }
		 else
		 {
			$ls_str_w1  = "";
			$ls_str_w1f = "";
			$ls_str_w1t = "";
		 }
         // Nivel 2
		 if ((($ls_CodEstPro2_desde!="********************") and ($ls_CodEstPro2_hasta!="********************")) and
		     (($ls_CodEstPro2_desde!="0000000000000000000000000") and ($ls_CodEstPro2_hasta!="0000000000000000000000000")))
		 {
			$ls_str_w2  = "PCT.codestpro2".$ls_cadena." ";
			$ls_str_w2f = $ls_CodEstPro2_desde;
			$ls_str_w2t = $ls_CodEstPro2_hasta;
		 }
		 else
		 {
			$ls_str_w2  = "";
			$ls_str_w2f = "";
			$ls_str_w2t = "";
		 }
         // Nivel 3
		 if ((($ls_CodEstPro3_desde!="********************") and ($ls_CodEstPro3_hasta!="********************")) and
		     (($ls_CodEstPro3_desde!="0000000000000000000000000") and ($ls_CodEstPro3_hasta!="0000000000000000000000000")))
		 {
			$ls_str_w3  = "PCT.codestpro3".$ls_cadena." ";
			$ls_str_w3f = $ls_CodEstPro3_desde;
			$ls_str_w3t = $ls_CodEstPro3_hasta;
		 }
		 else
		 {
			$ls_str_w3  = "";
			$ls_str_w3f = "";
			$ls_str_w3t = "";
		 }
         // Nivel 4
		 if ((($ls_CodEstPro4_desde!="********************") and ($ls_CodEstPro4_hasta!="********************")) and
		     (($ls_CodEstPro4_desde!="0000000000000000000000000") and ($ls_CodEstPro4_hasta!="0000000000000000000000000")))
		 {
			$ls_str_w4  = "PCT.codestpro4".$ls_cadena." ";
			$ls_str_w4f = $ls_CodEstPro4_desde;
			$ls_str_w4t = $ls_CodEstPro4_hasta;
		 }
		 else
		 {
			$ls_str_w4  = "";
			$ls_str_w4f = "";
			$ls_str_w4t = "";
		 }
         // Nivel 5
		 if ((($ls_CodEstPro5_desde!="********************") and ($ls_CodEstPro5_hasta!="********************")) and
		     (($ls_CodEstPro5_desde!="0000000000000000000000000") and ($ls_CodEstPro5_hasta!="0000000000000000000000000")))
		 {
			$ls_str_w5  = "PCT.codestpro5".$ls_cadena." ";
			$ls_str_w5f = $ls_CodEstPro5_desde;
			$ls_str_w5t = $ls_CodEstPro5_hasta;
		 }
		 else
		 {
			$ls_str_w5  = "";
			$ls_str_w5f = "";
			$ls_str_w5t = "";
		 }
		 //estatus de clasificacion
		 if (($as_estclades!='') and ($as_estclahas!=''))
		 {
			$ls_str_estcla  = "PCT.estcla))";
			$ls_str_estclaf = $as_estclades;
			$ls_str_estclat = $as_estclahas;
		 }
		 else
		 {
			$ls_str_estcla  = "";
			$ls_str_estclaf = "";
			$ls_str_estclat = "";
		 }
		 
         if (!(empty($ls_str_w1) and empty($ls_str_w2) and empty($ls_str_w3) and empty($ls_str_w4) and empty($ls_str_w5) and empty($ls_str_estcla)))
         {
			 $ls_str_estructura = $ls_str_w1.$ls_str_w2.$ls_str_w3.$ls_str_w4.$ls_str_w5.$ls_str_estcla;
             $li_lent= strlen($ls_str_estructura)-1;
             $ls_str_estructura = substr( $ls_str_estructura ,0,$li_lent);
             $as_str_estructura_from = $ls_str_w1f.$ls_str_w2f.$ls_str_w3f.$ls_str_w4f.$ls_str_w5f.$ls_str_estclaf;
             $as_str_estructura_to = $ls_str_w1t.$ls_str_w2t.$ls_str_w3t.$ls_str_w4t.$ls_str_w5t.$ls_str_estclat;
             $as_Sql_Where=$ls_str_estructura." between '".$as_str_estructura_from."' AND '".$as_str_estructura_to."' ";
         } 
         else
		 {
             $as_Sql_Where="";
             $as_str_estructura_to="";
             $as_str_estructura_from="";
		 }
    } // fin function uf_obtener_rango_programatica
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operaciones_por_cuenta($as_str_sql_where,$as_str_estructura_from,$as_str_estructura_to,
	                                                      $as_spg_cuenta,$adt_fecini,$adt_fecfin,&$adec_monto_asignado,
														  &$adec_monto_aumento,&$adec_monto_disminucion,&$adec_monto_precompromiso,
														  &$adec_monto_compromiso,&$adec_monto_causado,&$adec_monto_pagado,
                                                          &$adec_monto_aumento_a,&$adec_monto_disminucion_a,
														  &$adec_monto_precompromiso_a,&$adec_monto_compromiso_a,
														  &$adec_monto_causado_a,&$adec_monto_pagado_a,
														  $as_codfuefindes,$as_codfuefinhas)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operaciones_por_cuenta -> proviene de uf_spg_reporte_acumulado_cuentas
     //	    Returns :	$lb_valido true si se realizo la funcion con exito o false en caso contrario
	 //	Description :	Método  que ejecuta todas funciones de acumulado para asi sacar el acumulado por cuentas
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true; 
	   $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
   	   // Global	   
       $lb_valido=$this->uf_calcular_acumulado_operacion_asignacion($as_str_sql_where,$as_spg_cuenta,&$adec_monto_asignado,$as_codfuefindes,$as_codfuefinhas);
	 
	   // acumulado Anteriores
       if ($lb_valido)
  	   { 
	      $ls_operacion="aumento";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_aumento_a,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="disminucion";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_disminucion_a,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="precomprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_precompromiso_a,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="comprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_compromiso_a,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="causar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_causado_a,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="pagar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_pagado_a,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
	   // En el Rango
       if ($lb_valido)
  	   { 
	      $ls_operacion="aumento";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_aumento,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="disminucion";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_disminucion,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
		  
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="precomprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_precompromiso,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="comprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_compromiso,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="causar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_causado,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="pagar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_pagado,$ls_operacion,$as_codfuefindes,$as_codfuefinhas);
	   }
	   return $lb_valido;
	} // fin uf_calcular_acumulado_operaciones_por_cuenta 
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_asignacion($as_str_sql_where,$as_spg_cuenta,&$adec_monto,$as_codfuefindes,
	                                                    $as_codfuefinhas)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_asignacion( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma lo asignado por cuenta
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND PCT.codestpro1=spg_ep3.codestpro1 AND PCT.codestpro2=spg_ep3.codestpro2 AND PCT.codestpro3=spg_ep3.codestpro3";
		}
		elseif($li_estmodest==2)
		{
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND PCT.codestpro1=spg_ep5.codestpro1 AND PCT.codestpro2=spg_ep5.codestpro2 AND PCT.codestpro3=spg_ep5.codestpro3 AND PCT.codestpro4=spg_ep5.codestpro4 AND PCT.codestpro5=spg_ep5.codestpro5";
		}
		$lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
		  $ls_mysql  = "SELECT Case SUM(monto) WHEN NULL  THEN  0 
      						   ELSE SUM(monto)
      					 	   END monto		   ".
                       "FROM spg_dt_cmp PCT,spg_operaciones O, ".$ls_tabla." ";				 
		}
		else
		 {
		   $ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto 			   ".
                        "FROM spg_dt_cmp PCT,spg_operaciones O, ".$ls_tabla." ";
		 }
        if(!empty($as_str_sql_where))              
        { 
		  $ls_concat_sql = $as_str_sql_where." AND PCT.operacion=O.operacion AND O.asignar=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
		                    "       ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' ".
							"      ".$ls_cadena_fuefin." ";
        }
		else
		{
           $ls_concat_sql = " WHERE PCT.operacion=O.operacion AND  O.asignar=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
		                    "       spg_ep5.codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' ".
							"       ".$ls_cadena_fuefin."  ";
		}		
		$ls_mysql = $ls_mysql.$ls_concat_sql;
		$rs_data=$this->SQL->select($ls_mysql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo uf_calcular_acumulado_operacion_asignacion".$this->fun->uf_convertirmsg($this->SQL->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->SQL->free_result($rs_data);
		}
		$adec_monto = $ldec_monto;
		return $lb_valido;
	} // fin function uf_calcular_acumulado_operacion_asignacion
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,
	                                                   $adec_monto,$as_operacion,$as_codfuefindes,$as_codfuefinhas)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_por_rango( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma dependiando de la operacion(aumento,disminucion,precompromiso,compromiso)
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND PCT.codestpro1=spg_ep3.codestpro1 AND PCT.codestpro2=spg_ep3.codestpro2 AND PCT.codestpro3=spg_ep3.codestpro3";
		}
		elseif($li_estmodest==2)
		{
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND PCT.codestpro1=spg_ep5.codestpro1 AND PCT.codestpro2=spg_ep5.codestpro2 AND PCT.codestpro3=spg_ep5.codestpro3 AND PCT.codestpro4=spg_ep5.codestpro4 AND PCT.codestpro5=spg_ep5.codestpro5";
		}
		$lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
		  $ls_mysql  = "SELECT Case SUM(monto) WHEN NULL  THEN  0 
      						   ELSE SUM(monto)
      					 	   END monto		   ".
                       "FROM spg_dt_cmp PCT,spg_operaciones O, ".$ls_tabla." ";				 
		}
		else
		 {
		   $ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto 			   ".
                        "FROM spg_dt_cmp PCT,spg_operaciones O, ".$ls_tabla." ";
		 }
        if(!empty($as_str_sql_where))              
        { 
           $ls_concat_sql = $as_str_sql_where." AND PCT.operacion=O.operacion AND O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
                                    	      " fecha >='".$adt_fecini."' AND fecha <='".$adt_fecfin."'  AND ".
		                    "       ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' ".
							"       ".$ls_cadena_fuefin." ";
        }
		else
		{
           $ls_concat_sql = " WHERE codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND ".
                            "       O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
					        "       fecha >='".$adt_fecini."' AND fecha <='".$adt_fecfin."' AND ".
		                    "       ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' ".
							"       ".$ls_cadena_fuefin." ";
		}
        $ls_mysql = $ls_mysql.$ls_concat_sql;
		$rs_data=$this->SQL->select($ls_mysql);
		if($rs_data===false)
		{   // error interno sql
            $this->io_msg->message("Error en uf_calcular_acumulado_operacion_por_rango ".$this->fun->uf_convertirmsg($this->SQL->message));
			$lb_valido = false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->SQL->free_result($rs_data);
		}
		$adec_monto = $ldec_monto;
		return $lb_valido;
	} // fin function uf_calcular_acumulado_operacion_rango
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,$adec_monto,$as_operacion,
	                                                  $as_codfuefindes,$as_codfuefinhas)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_anterior( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto aumento
	 //	Description :	Método que consulta y suma el aumento de la cuenta 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND PCT.codestpro1=spg_ep3.codestpro1 AND PCT.codestpro2=spg_ep3.codestpro2 AND PCT.codestpro3=spg_ep3.codestpro3";
		}
		elseif($li_estmodest==2)
		{
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND PCT.codestpro1=spg_ep5.codestpro1 AND PCT.codestpro2=spg_ep5.codestpro2 AND PCT.codestpro3=spg_ep5.codestpro3 AND PCT.codestpro4=spg_ep5.codestpro4 AND PCT.codestpro5=spg_ep5.codestpro5";
		}
	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
		  $ls_mysql  = "SELECT Case SUM(monto) WHEN NULL  THEN  0 
      						   ELSE SUM(monto)
      					 	   END monto		   ".
                       "FROM spg_dt_cmp PCT,spg_operaciones O, ".$ls_tabla." ";				 
		}
		else
		 {
		   $ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto 			   ".
                        "FROM spg_dt_cmp PCT,spg_operaciones O, ".$ls_tabla." ";
		 }
        if(!empty($as_str_sql_where))              
        { 
           $ls_concat_sql = $as_str_sql_where." AND PCT.operacion=O.operacion AND O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
                                    	      " fecha <'".$adt_fecini."' AND fecha <='".$adt_fecini."' AND ".
		                    "       ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' ".
							"       ".$ls_cadena_fuefin." ";
        }
		else
		{
           $ls_concat_sql = " WHERE codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND ".
                            "       O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
					        "       fecha <'".$adt_fecini."'  AND ".
		                    "       ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' ".
							"       ".$ls_cadena_fuefin." ";
		}
		$ls_mysql = $ls_mysql.$ls_concat_sql;
		$rs_data=$this->SQL->select($ls_mysql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_calcular_acumulado_operacion_anterior ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
			   $ldec_monto = $row["monto"];
			}
			$this->SQL->free_result($rs_data);
		}
		$adec_monto = $ldec_monto;		
		return $lb_valido;
	} // fin function uf_calcular_acumulado_operacion_anterior
/********************************************************************************************************************************/	
	///////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "ACUMULADO POR CUENTAS  FORMATO 2"     // 
	/////////////////////////////////////////////////////////////////
	function uf_spg_reporte_select_programatica_acumulado_formato2($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                                 $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
											         $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin,$ai_nivel,
											         $ab_subniveles,&$ai_MenorNivel,$as_cuentades,$as_cuentahas,
											         $as_codfuefindes,$as_codfuefinhas,&$rs_data,$as_estclades,$as_estclahas)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_acumulado_cuentas_formato2
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori ... $as_codestpro5_ori //rango nivel estructura presupuestaria origen
	 //                     as_codestpro1_des ... $as_codestpro5_des //rango nivel estructura presupuestaria destino
	 //                     adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
	 //                     as_cuenta_from ... as_cuenta_to // de la cuenta de gasto ...hasta la cuenta de gasto
	 //                     ab_subniveles  // variable boolean si lo desea con subnivels 
	 //                     ai_MenorNivel  // variabvle que determina el mor nivel de la cuenta
     //	       Returns :	Retorna estructuras ordenadas para la consulta sql
	 //	   Description :	Reporte que genera el reporte del acumulado por cuentas(asignacion,aumneto,disminucion ...) 
	 //     Creado por :    Ing.Yozelin Barragan
	 // Fecha Creación :    16/05/2006          Fecha última Modificacion :         Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_seguridad="";
	    $this->io_spg_report_funciones->uf_filtro_seguridad_programatica('PCT',$ls_seguridad);
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
        $ls_str_sql_where="";
		$ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori.$as_estclades;
		$ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des.$as_estclahas;	  
		$ls_gestor = $_SESSION["ls_gestor"];
		if (strtoupper($ls_gestor)=="MYSQLT")
		{
			 $ls_str_sql = "CONCAT(PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,PCT.codestpro4,PCT.codestpro5,PCT.estcla)";
		}
		else
		{
			 $ls_str_sql = "PCT.codestpro1||PCT.codestpro2||PCT.codestpro3||PCT.codestpro4||PCT.codestpro5||PCT.estcla";
		}
		
        $this->uf_obtener_rango_programatica($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,                                              $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
                                             $as_codestpro4_des,$as_codestpro5_des,$ls_Sql_Where,$ls_str_estructura_from,
											 $ls_str_estructura_to,$as_estclades,$as_estclahas);
		$ls_str_sql_where="WHERE codemp='".$ls_codemp."'";
		$ls_Sql_Where = trim($ls_Sql_Where);
        if (!empty($ls_Sql_Where) )
        {
           $ls_str_sql_where="WHERE PCT.codemp='".$ls_codemp."' AND ".$ls_Sql_Where;
        }
		if($li_estmodest==1)
		{
		  $ls_tabla="spg_ep3"; 
		  $ls_cadena_fuefin="AND PCT.codestpro1=spg_ep3.codestpro1 AND PCT.codestpro2=spg_ep3.codestpro2 AND PCT.codestpro3=spg_ep3.codestpro3 AND PCT.estcla=spg_ep3.estcla";
		}
		elseif($li_estmodest==2)
		{
		  $ls_tabla="spg_ep5";
		  $ls_cadena_fuefin=" AND PCT.codestpro1=spg_ep5.codestpro1 AND PCT.codestpro2=spg_ep5.codestpro2 AND PCT.codestpro3=spg_ep5.codestpro3 AND PCT.codestpro4=spg_ep5.codestpro4 AND PCT.codestpro5=spg_ep5.codestpro5 AND PCT.estcla=spg_ep5.estcla";
		}
        $ls_sql=" SELECT  DISTINCT ".$ls_str_sql."  AS programatica        ". 
                " FROM spg_cuentas PCT, ".$ls_tabla."  ".$ls_str_sql_where." AND ".
                "      PCT.spg_cuenta BETWEEN '".trim($as_cuentades)."' AND '".trim($as_cuentahas)."' AND  ".
                "      ".$ls_tabla.".codfuefin BETWEEN '".trim($as_codfuefindes)."' AND '".trim($as_codfuefinhas)."' AND ".
				"      (PCT.nivel<='".$ai_nivel."')  ".$ls_cadena_fuefin."     ".$ls_seguridad.
                " ORDER BY programatica ";
		$rs_data=$this->SQL->select($ls_sql);
		//print $ls_sql."<br>";
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_mayor_analitico ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		return $lb_valido;
	 }//uf_spg_reporte_select_mayor_analitico
/********************************************************************************************************************************/	
}//fin de clase
?>