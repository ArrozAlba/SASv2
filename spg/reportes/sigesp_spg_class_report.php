<?php
/////////////////////////////////////////////////////////////////////////////
//	Class:  sigesp_spg_class_report
//	Description:  Esta clase tiene todos los metodos para la generación de los
//                reportes de gasto del sistema.
//////////////////////////////////////////////////////////////////////////////
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../../shared/class_folder/class_sigesp_int_spg.php");
/****************************************************************************************************************************************/	
class sigesp_spg_class_report
{
    //conexion	
	var $sqlca;   
	//Instancia de la clase funciones.
    var $is_msg_error;
	var $dts_empresa; // datastore empresa
	var $dts_reporte;
	var $obj="";
	var $SQL;
	var $siginc;
	var $con;
	var $fun;	
	var $io_msg;
	var $sigesp_int_spg;
/****************************************************************************************************************************************/	
    function  sigesp_spg_class_report()
    {
		$this->fun=new class_funciones() ;
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($this->con);		
		$this->obj=new class_datastore();
		$this->dts_empresa=$_SESSION["la_empresa"];
		$this->dts_reporte=new class_datastore();
		$this->io_msg=new class_mensajes();
		$this->sigesp_int_spg=new class_sigesp_int_spg();
    }
/****************************************************************************************************************************************/	
	/////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "ACUMULADO POR CUENTAS"   // 
	/////////////////////////////////////////////////////
    function uf_spg_reporte_acumulado_cuentas( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
	                                           $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
	                                           $adt_fecini,$adt_fecfin,$ai_nivel,$ab_subniveles,&$ai_MenorNivel)
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
	 // Fecha Creación :    01/02/2006          Fecha última Modificacion : 01/02/2006 Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe = false;	 
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
        $ls_str_sql_where="";
		$dts_cuentas=new class_datastore();
        $this->uf_obtener_rango_programatica( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
                                              $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
											  $ls_Sql_Where,$ls_str_estructura_from,$ls_str_estructura_to);

        $ls_str_sql_where="WHERE codemp='".$ls_codemp."'";
		$ls_Sql_Where = trim($ls_Sql_Where);
		
        if ( !empty($ls_Sql_Where) )
        {
           $ls_str_sql_where="WHERE codemp='".$ls_codemp."' AND ".$ls_Sql_Where;
        }

        $ls_mysql = " SELECT DISTINCT spg_cuenta,nivel,denominacion,asignado FROM spg_cuentas PCT ".$ls_str_sql_where." AND (nivel<='".$ai_nivel."') ORDER BY spg_cuenta ";
		$rs_cuentas=$this->SQL->select($ls_mysql);
		if($rs_cuentas===false)
		{   //error interno sql
		   $this->io_msg->message("Error en Reporte1".$this->fun->uf_convertirmsg($this->SQL->message));
           return false;
		}
		else
        {
   		   if($row=$this->SQL->fetch_row($rs_cuentas))
		   {
              $dts_cuentas->data=$this->SQL->obtener_datos($rs_cuentas);
              $lb_existe=true;
           }
		   $this->SQL->free_result($rs_cuentas);   
           if($lb_existe==false)
           {
              return false; // no hay registro
		   }
           $li_total_row=$dts_cuentas->getRowCount("spg_cuenta");			
		   for ($li_i=1;$li_i<=$li_total_row;$li_i++)
  	       {   
			   $lb_si_va = false;
			   $ls_spg_cuenta = $dts_cuentas->getValue("spg_cuenta",$li_i);
			   $ls_denominacion = $dts_cuentas->getValue("denominacion",$li_i);
			   $li_nivel = $dts_cuentas->getValue("nivel",$li_i);
			   
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
					if (!$this->uf_calcular_acumulado_operaciones_por_cuenta( $ls_str_sql_where,$ls_str_estructura_from,$ls_str_estructura_to,$ls_spg_cuenta,
																			  $adt_fecini,$adt_fecfin,$ldec_monto_asignado,$ldec_monto_aumento,$ldec_monto_disminucion,
																			  $ldec_monto_precompromiso,$ldec_monto_compromiso,$ldec_monto_causado,$ldec_monto_pagado,
																			  $ldec_monto_aumento_a,$ldec_monto_disminucion_a,$ldec_monto_precompromiso_a,$ldec_monto_compromiso_a,
																			  $ldec_monto_causado_a,$ldec_monto_pagado_a))
					{
					   return false; 
					} 
	 	 	   		    $ll_row_found = $this->dts_reporte->find("spg_cuenta",$ls_spg_cuenta);
						if ($ll_row_found == 0)
						{  
						    $ldec_monto_actualizado = ($ldec_monto_asignado+$ldec_monto_aumento_a+$ldec_monto_aumento-$ldec_monto_disminucion_a-$ldec_monto_disminucion);
							$ldec_saldo_comprometer = ($ldec_monto_asignado+$ldec_monto_aumento_a+$ldec_monto_aumento-$ldec_monto_disminucion_a-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);
							$ldec_por_pagar = ($ldec_monto_causado+$ldec_monto_causado_a)-($ldec_monto_pagado-$ldec_monto_pagado_a);
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
						} 
						else
						{
						    $ldec_monto_actualizado = ($ldec_monto_asignado+$ldec_monto_aumento_a+$ldec_monto_aumento-$ldec_monto_disminucion_a-$ldec_monto_disminucion);
							$ldec_saldo_comprometer = ($ldec_monto_asignado+$ldec_monto_aumento_a+$ldec_monto_aumento-$ldec_monto_disminucion_a-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);
							$ldec_por_pagar = ($ldec_monto_causado+$ldec_monto_causado_a)-($ldec_monto_pagado-$ldec_monto_pagado_a);					
							$ldec_monto = $this->dts_reporte->getValue("asignado",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_asignado;
						    $this->dts_reporte->updateRow("asignado",$ldec_monto,$ll_row_found);														
							$ldec_monto = $this->dts_reporte->getValue("aumento",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_aumento;
  							$this->dts_reporte->updateRow("aumento",$ldec_monto,$ll_row_found);						
							$ldec_monto = $this->dts_reporte->getValue("disminucion",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_disminucion;						
							$this->dts_reporte->updateRow("disminucion",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("precompromiso",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_precompromiso;																						
							$this->dts_reporte->updateRow("precompromiso",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("compromiso",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_compromiso;																								
							$this->dts_reporte->updateRow("compromiso",$ldec_monto,$ll_row_found);							
                            $ldec_monto = $this->dts_reporte->getValue("causado",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_causado;									
							$this->dts_reporte->updateRow("causado",$ldec_monto,$ll_row_found);	
                            $ldec_monto = $this->dts_reporte->getValue("pagado",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_pagado;																						
							$this->dts_reporte->updateRow("pagado",$ldec_monto,$ll_row_found);							
                            $ldec_monto = $this->dts_reporte->getValue("aumento_a",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_aumento_a;									
							$this->dts_reporte->updateRow("aumento_a",$ldec_monto,$ll_row_found);							
                            $ldec_monto = $this->dts_reporte->getValue("disminucion_a",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_disminucion_a;																
							$this->dts_reporte->updateRow("disminucion_a",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("precompromiso_a",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_precompromiso_a;																					
							$this->dts_reporte->updateRow("precompromiso_a",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("compromiso_a",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_compromiso_a;																											
							$this->dts_reporte->updateRow("compromiso_a",$ldec_monto,$ll_row_found);						
                            $ldec_monto = $this->dts_reporte->getValue("causado_a",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_causado_a;
							$this->dts_reporte->updateRow("causado_a",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("pagado_a",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_pagado_a;						
							$this->dts_reporte->updateRow("pagado_a",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("monto_actualizado",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_monto_actualizado;													
							$this->dts_reporte->updateRow("monto_actualizado",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("saldo_comprometer",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_saldo_comprometer;																				
							$this->dts_reporte->updateRow("saldo_comprometer",$ldec_monto,$ll_row_found);
                            $ldec_monto = $this->dts_reporte->getValue("por_pagar",$ll_row_found );
							$ldec_monto = $ldec_monto + $ldec_por_pagar;
                            $this->dts_reporte->updateRow("por_pagar",$ldec_monto,$ll_row_found);											
						}// else
			   } // end if 
            } // end for
         } //else
		 return $lb_valido;
    } // fin function uf_spg_reporte_acumulado_cuentas
/****************************************************************************************************************************************/	
    function uf_obtener_rango_programatica( $as_codestpro1_ori,$as_codestpro2_ori,
                                            $as_codestpro3_ori,$as_codestpro4_ori,
                                            $as_codestpro5_ori,$as_codestpro1_des,
                                            $as_codestpro2_des,$as_codestpro3_des,
                                            $as_codestpro4_des,$as_codestpro5_des,
                                            &$as_Sql_Where,&$as_str_estructura_from,
                                            &$as_str_estructura_to )
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_obtener_rango_programatica -> proviene de uf_spg_reporte_acumulado_cuentas
	 //       Access :	private
	 //   Argumentos :  as_codestpro1_ori ... as_estprepro5_ori,as_codestpro1_des ... as_estprepro5_des
     //	    Returns :	Retorna estructuras ordenadas para la consulta sql
	 //	Description :	Método que determina y ordena el minimo por niveles de la estructuras presupuestarias
     //                 para luego concatenar en una variables de origen y una de destino 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $ls_CodEstPro1_desde = min($as_codestpro1_ori,$as_codestpro1_des);
		 $ls_CodEstPro1_hasta = $as_codestpro1_des;
		 $ls_CodEstPro2_desde = min($as_codestpro2_ori,$as_codestpro2_des);
		 $ls_CodEstPro2_hasta = $as_codestpro2_des;
		 $ls_CodEstPro3_desde = min($as_codestpro3_ori,$as_codestpro3_des);
		 $ls_CodEstPro3_hasta = $as_codestpro3_des;
		 $ls_CodEstPro4_desde = min($as_codestpro4_ori,$as_codestpro4_des);
		 $ls_CodEstPro4_hasta = $as_codestpro4_des;
		 $ls_CodEstPro5_desde = min($as_codestpro5_ori,$as_codestpro5_des);
		 $ls_CodEstPro5_hasta = $as_codestpro5_des;
         // Nivel 1
		 if (($ls_CodEstPro1_desde!="********************") and ($ls_CodEstPro1_hasta!="********************")
		     or ($ls_CodEstPro1_desde!="0000000000000000000000000") and ($ls_CodEstPro1_hasta!="0000000000000000000000000"))
		 { 
			$ls_str_w1  = "CONCAT(PCT.codestpro1, ";
			$ls_str_w1f = $ls_CodEstPro1_desde;
			$ls_str_w1t = $ls_CodEstPro1_desde;
		 }
		 else
		 {
			$ls_str_w1  = "";
			$ls_str_w1f = "";
			$ls_str_w1t = "";
		 }
         // Nivel 2
		 if (($ls_CodEstPro2_desde!='******') and ($ls_CodEstPro2_hasta!='******')
		     or ($ls_CodEstPro2_desde!="0000000000000000000000000") and ($ls_CodEstPro2_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w2  = "PCT.codestpro2, ";
			$ls_str_w2f = $ls_CodEstPro2_desde;
			$ls_str_w2t = $ls_CodEstPro2_desde;
		 }
		 else
		 {
			$ls_str_w2  = "";
			$ls_str_w2f = "";
			$ls_str_w2t = "";
		 }
         // Nivel 3
		 if (($ls_CodEstPro3_desde!='***') and ($ls_CodEstPro3_hasta!='***')
		     or ($ls_CodEstPro3_desde!="0000000000000000000000000") and ($ls_CodEstPro3_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w3  = "PCT.codestpro3, ";
			$ls_str_w3f = $ls_CodEstPro3_desde;
			$ls_str_w3t = $ls_CodEstPro3_desde;
		 }
		 else
		 {
			$ls_str_w3  = "";
			$ls_str_w3f = "";
			$ls_str_w3t = "";
		 }
         // Nivel 4
		 if (($ls_CodEstPro4_desde!='**') and ($ls_CodEstPro4_hasta!='**')
		     or ($ls_CodEstPro4_desde!="0000000000000000000000000") and ($ls_CodEstPro4_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w4  = "PCT.codestpro4, ";
			$ls_str_w4f = $ls_CodEstPro4_desde;
			$ls_str_w4t = $ls_CodEstPro4_desde;
		 }
		 else
		 {
			$ls_str_w4  = "";
			$ls_str_w4f = "";
			$ls_str_w4t = "";
		 }
         // Nivel 5
		 if (($ls_CodEstPro5_desde!='**') and ($ls_CodEstPro5_hasta!='**')
		     or ($ls_CodEstPro5_desde!="0000000000000000000000000") and ($ls_CodEstPro5_hasta!="0000000000000000000000000"))
		 {
			$ls_str_w5  = "PCT.codestpro5))";
			$ls_str_w5f = $ls_CodEstPro5_desde;
			$ls_str_w5t = $ls_CodEstPro5_desde;
		 }
		 else
		 {
			$ls_str_w5  = "";
			$ls_str_w5f = "";
			$ls_str_w5t = "";
		 }
         if (!(empty($ls_str_w1) and empty($ls_str_w2) and empty($ls_str_w3) and empty($ls_str_w4) and empty($ls_str_w5)))
         {
             $ls_str_estructura = $ls_str_w1.$ls_str_w2.$ls_str_w3.$ls_str_w4.$ls_str_w5;
             $li_lent= strlen($ls_str_estructura)-1;
             $ls_str_estructura = substr( $ls_str_estructura ,0,$li_lent);
             $as_str_estructura_to = $ls_str_w1t.$ls_str_w2t.$ls_str_w3t.$ls_str_w4t.$ls_str_w5t;
             $as_str_estructura_from = $ls_str_w1f.$ls_str_w2f.$ls_str_w3f.$ls_str_w4f.$ls_str_w5f;
             $as_Sql_Where=$ls_str_estructura." between '".$as_str_estructura_from."' AND '".$as_str_estructura_to."' ";
         } 
         else
		 {
             $as_Sql_Where="";
             $as_str_estructura_to="";
             $as_str_estructura_from="";
		 }
    } // fin function uf_obtener_rango_programatica
/****************************************************************************************************************************************/	
	function uf_calcular_acumulado_operaciones_por_cuenta($as_str_sql_where,$as_str_estructura_from,$as_str_estructura_to,$as_spg_cuenta,
														  $adt_fecini,$adt_fecfin,&$adec_monto_asignado,&$adec_monto_aumento,&$adec_monto_disminucion,
														  &$adec_monto_precompromiso,&$adec_monto_compromiso,&$adec_monto_causado,&$adec_monto_pagado,
                                                          &$adec_monto_aumento_a,&$adec_monto_disminucion_a,
														  &$adec_monto_precompromiso_a,&$adec_monto_compromiso_a,&$adec_monto_causado_a,&$adec_monto_pagado_a)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operaciones_por_cuenta -> proviene de uf_spg_reporte_acumulado_cuentas
     //	    Returns :	$lb_valido true si se realizo la funcion con exito o false en caso contrario
	 //	Description :	Método  que ejecuta todas funciones de acumulado para asi sacar el acumulado por cuentas
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true; 
	   $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
   	   // Global	   
       $lb_valido=$this->uf_calcular_acumulado_operacion_asignacion($as_str_sql_where,$as_spg_cuenta,&$adec_monto_asignado);
	   // acumulado Anteriores
       if ($lb_valido)
  	   { 
	      $ls_operacion="aumento";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_aumento_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="disminucion";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_disminucion_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="precomprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_precompromiso_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="comprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_compromiso_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="causar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_causado_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="pagar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,&$adec_monto_pagado_a,$ls_operacion);
	   }
	   // En el Rango
       if ($lb_valido)
  	   { 
	      $ls_operacion="aumento";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_aumento,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="disminucion";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_disminucion,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="precomprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_precompromiso,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="comprometer";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_compromiso,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="causar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_causado,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="pagar";
          $lb_valido=$this->uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_pagado,$ls_operacion);
	   }
	   return $lb_valido;
	} // fin uf_calcular_acumulado_operaciones_por_cuenta 
/****************************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_asignacion($as_str_sql_where,$as_spg_cuenta,&$adec_monto)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_asignacion( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma lo asignado por cuenta
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto ".
                     "FROM spg_dt_cmp PCT,spg_operaciones O ";				 
  	    
        if(!empty($as_str_sql_where))              
        { 
		  $ls_concat_sql = $as_str_sql_where." AND PCT.operacion=O.operacion AND O.asignar=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."'";
        }
		else
		{
           $ls_concat_sql = " WHERE PCT.operacion=O.operacion AND  O.asignar=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' ";
		   
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
/****************************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,$adec_monto,$as_operacion)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_por_rango( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma dependiando de la operacion(aumento,disminucion,precompromiso,compromiso)
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto ".
                     "FROM spg_dt_cmp PCT,spg_operaciones O  ";
        if(!empty($as_str_sql_where))              
        { 
           $ls_concat_sql = $as_str_sql_where." AND PCT.operacion=O.operacion AND O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
                                    	      " fecha >='".$adt_fecini."' AND fecha <='".$adt_fecfin."'";
        }
		else
		{
           $ls_concat_sql = " WHERE codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND ".
                            "       O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
					        "       fecha >='".$adt_fecini."' AND fecha <='".$adt_fecfin."'";

		}
        //$ls_mysql = $ls_mysql.$ls_mysql;
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
/****************************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,$adec_monto,$as_operacion)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_anterior( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto aumento
	 //	Description :	Método que consulta y suma el aumento de la cuenta 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto ".
                     "FROM spg_dt_cmp PCT,spg_operaciones O  " ;
        if(!empty($as_str_sql_where))              
        { 
           $ls_concat_sql = $as_str_sql_where." AND PCT.operacion=O.operacion AND O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
                                    	      " fecha <'".$adt_fecini."' AND fecha <='".$adt_fecini."'";
        }
		else
		{
           $ls_concat_sql = " WHERE codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND ".
                            "       O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
					        "       fecha <'".$adt_fecini."'";

		}
        //$ls_mysql = $ls_mysql.$ls_mysql;
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
/****************************************************************************************************************************************/	
	////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "MAYOR ANÁLITICO DE CUENTAS" // 
	////////////////////////////////////////////////////////
	function uf_select_denestpro()
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_select_denestpro
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1 ... $as_codestpro5 //rango nivel estructura presupuestaria 
	 //                     $as_codemp  // variabvle que determina el mor nivel de la cuenta
     //	       Returns :	Retorna denominación de la estructura presupuestaria 
	 //	   Description :	Selecciona la denominación de la estructura presupuestarias 
	 //     Creado por :    Ing. Yozelin Barragan
	 // Fecha Creación :    12/04/2006          Fecha última Modificacion : 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
      $lb_valido=true;
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $ls_sql=" SELECT denestpro5 ".
              " FROM   spg_ep5  ".
              " WHERE  codemp='".$ls_codemp."' AND codestpro1='00000000000000000001' AND codestpro2='000001' AND ".
              "        codestpro3='000' AND codestpro4='00' AND codestpro5='00' ";
	 $li_select=$this->SQL->select($ls_sql);                                                                                                                                                                                          
	 if($li_select===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->class_apertura MÉTODO->uf_select_denestpro ERROR->".$this->io_function->uf_convertirmsg($this->SQL->message));
	 }
	 return  $lb_valido;
	}
/****************************************************************************************************************************************/	
    function uf_spg_reporte_mayor_analitico( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
	                                         $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
	                                         $adt_fecini,$adt_fecfin,$as_cuenta_from,$as_cuenta_to,$as_orden)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_mayor_analitico
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
	 //     Creado por :    Ing. Wilmer Briceño 
	 // Fecha Creación :    01/02/2006          Fecha última Modificacion : 01/02/2006 Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $lb_existe = false;	 
	  $ls_gestor = $_SESSION["ls_gestor"];
      $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
      $ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
      $ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;	  
      if($as_orden=='F')
	  {
         $ls_ordenar="TA.fecha";	  
	  }
	  elseif($as_orden=='D')
	  {
         $ls_ordenar="TA.Documento";	  
	  }
	  
	  if (strtoupper($ls_gestor)=="MYSQL")
	  {
	     $ls_str_sql_1 = "CONCAT(RTRIM(BEN.apebene),', ',BEN.nombene) ";
	     $ls_str_sql_2 = "CONCAT(MV.codestpro1,MV.codestpro2,MV.codestpro3,MV.codestpro4,MV.codestpro5)";
		 $ls_str_sql_3 = "CONCAT(MOV.codestpro1,MOV.codestpro2,MOV.codestpro3,MOV.codestpro4,MOV.codestpro5)";
	  }
	  else
	  {
	     $ls_str_sql_1 = "RTRIM(BEN.apebene)||', '||BEN.nombene ";	  
	     $ls_str_sql_2 = "MV.codestpro1||MV.codestpro2||MV.codestpro3||MV.codestpro4||MV.codestpro5";
	     $ls_str_sql_3 = "MOV.codestpro1||MOV.codestpro2||MOV.codestpro3||MOV.codestpro4||MOV.codestpro5";
	  }
 
	  
	  $ls_mysql = " SELECT * ".
                  " FROM (  SELECT  MV.* , C.denominacion,MV.monto as monto_mov ".
                  "         FROM spg_dt_cmp MV,spg_operaciones OPE,spg_cuentas C ".
                  "         WHERE MV.codemp='".$ls_codemp."' AND (MV.operacion=OPE.operacion) AND ".
                  "               MV.codestpro1=C.codestpro1 AND MV.codestpro2=C.codestpro2 AND ".
				  "               MV.codestpro3=C.codestpro3 AND MV.codestpro4=C.codestpro4 AND ".
				  "               MV.codestpro5=C.codestpro5 AND MV.spg_cuenta=C.spg_cuenta AND".				  
                  " 	    (".$ls_str_sql_2." BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."') ".				  
                  " ORDER BY MV.codestpro1,MV.codestpro2,MV.codestpro3,MV.codestpro4,MV.codestpro5,MV.spg_cuenta, ".
                  "          MV.fecha,OPE.asignar DESC, OPE.aumento DESC, OPE.disminucion DESC, OPE.comprometer DESC, ".
                  "          OPE.causar DESC, OPE.pagar DESC, MV.Documento ) TA,".
                  " (  SELECT DISTINCT SPG.procede,SPG.comprobante,SPG.fecha,SPG.descripcion, ".
                  "           SPG.total,SPG.tipo_destino,SPG.cod_pro,PROV.nompro,SPG.ced_bene, ".
                  "           ".$ls_str_sql_1."  as ben_nombre ".
                  "    FROM sigesp_cmp SPG,spg_dt_cmp MOV,rpc_proveedor PROV,rpc_beneficiario BEN ".
                  "    WHERE  PROV.cod_pro=SPG.cod_pro AND BEN.ced_bene=SPG.ced_bene AND SPG.codemp='".$ls_codemp."' AND ". 
                  "           (SPG.procede=MOV.procede AND SPG.comprobante=MOV.comprobante AND SPG.fecha=MOV.fecha ) AND ".
				  " 	    (".$ls_str_sql_3." BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."'))  TB ".
                  " WHERE TA.procede=TB.procede AND TA.comprobante=TB.comprobante AND TA.fecha=TB.fecha ".
				  " ORDER BY TA.spg_cuenta, ".$ls_ordenar." ";
		//print $ls_mysql;		  
      $rs_mov_spg=$this->SQL->select($ls_mysql);
	
	  if($rs_mov_spg===false)
	  {   // error interno sql
	     $this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
         return ;
   	  }
	  else
	  {
		  $ldec_monto_asignado = 0;
		  $ldec_monto_aumento  = 0;		  
		  $ldec_monto_disminucion = 0;		 
		  $ldec_monto_precompromiso = 0;		 		   
		  $ldec_monto_compromiso = 0;		 		   		  
		  $ldec_monto_causado = 0;		 		   		  		  
		  $ldec_monto_pagado = 0;		 		   		  		  		  
		  $ldec_monto_asignado_a = 0;
		  $ldec_monto_aumento_a  = 0;		  
		  $ldec_monto_disminucion_a = 0;		 
		  $ldec_monto_precompromiso_a = 0;		 		   
		  $ldec_monto_compromiso_a = 0;		 		   		  
		  $ldec_monto_causado_a = 0;		 		   		  		  
		  $ldec_monto_pagado_a = 0;		 		   		  		  		  
		  $ldec_monto_por_comprometer = 0;		 		   		  		  		  		  
		  $ls_cuenta_actual = "";		 		   		  		  		  		  
		  $ls_descripcion = "";
		  $lb_previo = false;
	  	  while($row=$this->SQL->fetch_row($rs_mov_spg))
		  {
		      $ls_codestpro1=$row["codestpro1"];
			  $ls_codestpro2=$row["codestpro2"];
			  $ls_codestpro3=$row["codestpro3"];
			  $ls_codestpro4=$row["codestpro4"];
			  $ls_codestpro5=$row["codestpro5"];			  
		      $ls_estructura_programatica = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
 	 	      $ls_spg_cuenta=$row["spg_cuenta"];
			  $ls_denominacion=$row["denominacion"];
			  $ls_operacion=$row["operacion"];
			  $ldec_monto_operacion=$row["monto"];
			  $ls_procede=$row["procede"];
			  $ls_procede_doc=$row["procede_doc"];
			  $ls_comprobante=$row["comprobante"];			  
			  $ls_documento =$row["documento"];			   
			  $ls_descripcion =$row["descripcion"];			   
			  $ls_tipo_destino=$row["tipo_destino"];			 
			  $ls_BEN_Nombre=$row["ben_nombre"];			   
			  $ls_NomPro=$row["nompro"];			
			  $ldt_fecha=$row["fecha"];
			  $ls_cod_pro=$row["cod_pro"];
			  $ls_nombre_prog=$row["descripcion"];
		      if ($ls_cuenta_actual!=$ls_spg_cuenta)
			  {
				  $ldec_monto_asignado_a = 0;
				  $ldec_monto_aumento_a  = 0;		  
				  $ldec_monto_disminucion_a = 0;		 
				  $ldec_monto_precompromiso_a = 0;		 		   
				  $ldec_monto_compromiso_a = 0;		 		   		  
				  $ldec_monto_causado_a = 0;		 		   		  		  
				  $ldec_monto_pagado_a = 0;		 		   		  		  		  
				  $ldec_monto_por_comprometer = 0;		
				  $lb_previo = true; 		   		  		  		  		  
				  $ls_cuenta_actual = $ls_spg_cuenta;		 		   		  		  		  		  			  
			  } 	
			  $ldt_fecha_movimiento = $this->fun->uf_convertirdatetobd($ldt_fecha);
			 
			  if ($ldt_fecha_movimiento < $adt_fecini )
			  {
				  $ldec_monto_asignado = 0;
				  $ldec_monto_aumento  = 0;		  
				  $ldec_monto_disminucion = 0;		 
				  $ldec_monto_precompromiso = 0;		 		   
				  $ldec_monto_compromiso = 0;		 		   		  
				  $ldec_monto_causado = 0;		 		   		  		  
				  $ldec_monto_pagado = 0;		 		   		  		  		  	  
				  $this->uf_calcular_monto_operaciones($ls_operacion,$ldec_monto_operacion,$ldec_monto_asignado,$ldec_monto_aumento,$ldec_monto_disminucion,
									                   $ldec_monto_precompromiso,$ldec_monto_compromiso,$ldec_monto_causado,$ldec_monto_pagado);				  
				  $ldec_monto_por_comprometer = $ldec_monto_por_comprometer+($ldec_monto_asignado+$ldec_monto_aumento-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);		
				  $ldec_monto_asignado_a = $ldec_monto_asignado_a+$ldec_monto_asignado;
				  $ldec_monto_aumento_a  = $ldec_monto_aumento_a+$ldec_monto_aumento;		  
				  $ldec_monto_disminucion_a = $ldec_monto_disminucion_a+$ldec_monto_disminucion;		 
				  $ldec_monto_precompromiso_a = $ldec_monto_precompromiso_a+$ldec_monto_precompromiso;		 		   
				  $ldec_monto_compromiso_a = $ldec_monto_compromiso_a+$ldec_monto_compromiso;		 		   		  
				  $ldec_monto_causado_a = $ldec_monto_causado_a+$ldec_monto_causado;		 		   		  		  
   			      $ldec_monto_pagado_a = $ldec_monto_pagado_a+$ldec_monto_pagado;						  		  		  				  
			  } 
			  if (($ldt_fecha_movimiento >= $adt_fecini ) and ($ldt_fecha_movimiento <= $adt_fecfin) and 
			      ($ls_spg_cuenta>=$as_cuenta_from) and ($ls_spg_cuenta<=$as_cuenta_to))
			  {
				  $ldec_monto_asignado = 0;
				  $ldec_monto_aumento  = 0;		  
				  $ldec_monto_disminucion = 0;		 
				  $ldec_monto_precompromiso = 0;		 		   
				  $ldec_monto_compromiso = 0;		 		   		  
				  $ldec_monto_causado = 0;		 		   		  		  
				  $ldec_monto_pagado = 0;		 		   		  		  		  
				  $this->uf_calcular_monto_operaciones($ls_operacion,$ldec_monto_operacion,$ldec_monto_asignado,$ldec_monto_aumento,$ldec_monto_disminucion,
									                   $ldec_monto_precompromiso,$ldec_monto_compromiso,$ldec_monto_causado,$ldec_monto_pagado);				  
				  $ldec_monto_por_comprometer = $ldec_monto_por_comprometer+($ldec_monto_asignado+$ldec_monto_aumento-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);					  
			  
				  if ($lb_previo==true)
				  {
					 $this->dts_reporte->insertRow("programatica",$ls_estructura_programatica);
					 $this->dts_reporte->insertRow("nombre_prog",$ls_nombre_prog);
					 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
					 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					 $this->dts_reporte->insertRow("fecha","");
					 $this->dts_reporte->insertRow("procede","");
					 $this->dts_reporte->insertRow("procede_doc","");
					 $this->dts_reporte->insertRow("comprobante","");
					 $this->dts_reporte->insertRow("documento","");
					 $this->dts_reporte->insertRow("descripcion",'SALDOS ANTERIORES');
					 $this->dts_reporte->insertRow("asignado",$ldec_monto_asignado_a);
					 $this->dts_reporte->insertRow("aumento",$ldec_monto_aumento_a);
					 $this->dts_reporte->insertRow("disminucion",$ldec_monto_disminucion_a);
					 $this->dts_reporte->insertRow("precompromiso",$ldec_monto_precompromiso_a);
					 $this->dts_reporte->insertRow("compromiso",$ldec_monto_compromiso_a);
                     $this->dts_reporte->insertRow("causado",$ldec_monto_causado_a);					 
					 $this->dts_reporte->insertRow("pagado",$ldec_monto_pagado_a);					 
					 $this->dts_reporte->insertRow("por_comprometer",$ldec_monto_por_comprometer);
					 $this->dts_reporte->insertRow("tipo_destino","");
					 $this->dts_reporte->insertRow("cod_pro","");
					 $this->dts_reporte->insertRow("nompro","");
					 $this->dts_reporte->insertRow("ben_nombre","");
					 $lb_previo=false;
				  }
				  else
				  {
					 $this->dts_reporte->insertRow("programatica",$ls_estructura_programatica);
					 $this->dts_reporte->insertRow("nombre_prog",$ls_nombre_prog);
					 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
					 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					 $this->dts_reporte->insertRow("fecha",$ldt_fecha_movimiento);
					 $this->dts_reporte->insertRow("procede",$ls_procede);
					 $this->dts_reporte->insertRow("procede_doc",$ls_procede_doc);
					 $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
					 $this->dts_reporte->insertRow("documento",$ls_documento);
					 $this->dts_reporte->insertRow("descripcion",$ls_descripcion);
					 $this->dts_reporte->insertRow("asignado",$ldec_monto_asignado);
					 $this->dts_reporte->insertRow("aumento",$ldec_monto_aumento);
					 $this->dts_reporte->insertRow("disminucion",$ldec_monto_disminucion);
					 $this->dts_reporte->insertRow("precompromiso",$ldec_monto_precompromiso);
					 $this->dts_reporte->insertRow("compromiso",$ldec_monto_compromiso);
                     $this->dts_reporte->insertRow("causado",$ldec_monto_causado);					 
					 $this->dts_reporte->insertRow("pagado",$ldec_monto_pagado);					 
					 $this->dts_reporte->insertRow("por_comprometer",$ldec_monto_por_comprometer);
					 $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
					 $this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
					 $this->dts_reporte->insertRow("nompro",$ls_NomPro);
					 $this->dts_reporte->insertRow("ben_nombre",$ls_BEN_Nombre);			  
				  }
			  }
	      }// fin while  
 	  }
	  $this->SQL->free_result($rs_mov_spg);	 
	  return true;
    } // end function uf_spg_reporte_mayor_analitico
/****************************************************************************************************************************************/	
	function uf_calcular_monto_operaciones($ls_operacion,$ldec_monto_operacion,&$adec_monto_asignado,&$adec_monto_aumento,&$adec_monto_disminucion,
									       &$adec_monto_precompromiso,&$adec_monto_compromiso,&$adec_monto_causado,&$adec_monto_pagado)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operaciones->uf_spg_reporte_mayor_analitico
     //	    Returns :	Retorna campos calculados 
	 //	Description :	Método que mediante la operacion de gasto suma o resta los monto de las operaciones
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $ls_mensaje = $this->sigesp_int_spg->uf_operacion_codigo_mensaje($ls_operacion);
	 
 	 $li_pos_a=strpos($ls_mensaje,"I"); // I-Asignacion
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_asignado = $adec_monto_asignado + $ldec_monto_operacion;
	 }
 	 $li_pos_a=strpos($ls_mensaje,"A"); // A-Aumento
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_aumento = $adec_monto_aumento + $ldec_monto_operacion;
	 }
 	 $li_pos_a=strpos($ls_mensaje,"D"); // D-Disminucion
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_disminucion = $adec_monto_disminucion + $ldec_monto_operacion;
	 }
 	 $li_pos_a=strpos($ls_mensaje,"R"); // R-Pre-Comprometer
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_precompromiso = $adec_monto_precompromiso + $ldec_monto_operacion;
	 }
 	 $li_pos_a=strpos($ls_mensaje,"O"); // O-Comprometer
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_compromiso = $adec_monto_compromiso + $ldec_monto_operacion;
	 }
 	 $li_pos_a=strpos($ls_mensaje,"C"); // C-Causar
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_causado = $adec_monto_causado + $ldec_monto_operacion;
	 }
 	 $li_pos_a=strpos($ls_mensaje,"P"); // P-Pagar
	 if (!($li_pos_a===false))
	 { 
	   $adec_monto_pagado = $adec_monto_pagado + $ldec_monto_operacion;
	 }
      return ;
    } // end uf_calcular_monto_operaciones
/****************************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "LISTADO DE APERTURAS DE CUENTAS" // 
	////////////////////////////////////////////////////////////
    function uf_spg_reporte_apertura( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
	                                  $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
	                                  $adt_fecini,$adt_fecfin )
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
	 // Fecha Creación :    16/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	  $lb_valido=true;
	  $ls_gestor = $_SESSION["ls_gestor"];
      $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
      $ls_estructura_origen=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
      $ls_estructura_dstino=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;	  
	  $ls_procede="SPGAPR";
	  
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
			 $ls_cad_DM="CONCAT(DM.codestpro1,DM.codestpro2,DM.codestpro3,DM.codestpro4,DM.codestpro5,DM.spg_cuenta)";
			 $ls_cad_GC="CONCAT(GC.codestpro1,GC.codestpro2,GC.codestpro3,GC.codestpro4,GC.codestpro5,GC.spg_cuenta)";
			 $ls_cad_DM_SC="CONCAT(DM.codestpro1,DM.codestpro2,DM.codestpro3,DM.codestpro4,DM.codestpro5)";
			 $ls_cad_EP="CONCAT(EP.codestpro1,EP.codestpro2,EP.codestpro3,EP.codestpro4,EP.codestpro5)";	  
	  }
	  /*elseif($ls_gestor=="sybase")
	  {
			 $ls_cad_DM="DM.codestpro1+DM.codestpro2+DM.codestpro3+DM.codestpro4+DM.codestpro5+DM.spg_cuenta";
			 $ls_cad_GC="GC.codestpro1+GC.codestpro2+GC.codestpro3+GC.codestpro4+GC.codestpro5+GC.spg_cuenta";
			 $ls_cad_DM_SC="DM.codestpro1+DM.codestpro2+DM.codestpro3+DM.codestpro4+DM.codestpro5";
			 $ls_cad_EP="EP.codestpro1+EP.codestpro2+EP.codestpro3+EP.codestpro4+EP.codestpro5";	  
	  }*/
	  else
	  {
			 $ls_cad_DM="DM.codestpro1||DM.codestpro2||DM.codestpro3||DM.codestpro4||DM.codestpro5||DM.spg_cuenta";
			 $ls_cad_GC="GC.codestpro1||GC.codestpro2||GC.codestpro3||GC.codestpro4||GC.codestpro5||GC.spg_cuenta";
			 $ls_cad_DM_SC="DM.codestpro1||DM.codestpro2||DM.codestpro3||DM.codestpro4||DM.codestpro5";
			 $ls_cad_EP="EP.codestpro1||EP.codestpro2||EP.codestpro3||EP.codestpro4||EP.codestpro5";	  
	  }
	  
	  $ls_sql=" SELECT DM.*, GC.denominacion,EP.denestpro5 ".
              " FROM   spg_dt_cmp DM, spg_cuentas GC, spg_ep5 EP ".
              " WHERE  DM.codemp='".$ls_codemp."' AND DM.codemp=GC.codemp AND GC.codemp=EP.codemp AND ".
			  "        ".$ls_cad_DM." = ".$ls_cad_GC." AND  ".$ls_cad_DM_SC." = ".$ls_cad_EP." AND ".
			  "        DM.procede = '".$ls_procede."' AND ".
		   	  "        DM.fecha between '".$adt_fecini."' AND '".$adt_fecfin."' AND ".
		 	  "        ".$ls_cad_DM_SC."  between '".$ls_estructura_origen."' AND '".$ls_estructura_dstino."' ".
              " ORDER BY DM.codestpro1, DM.codestpro2, DM.codestpro3, DM.codestpro4, DM.codestpro5, DM.fecha, DM.spg_cuenta ";	 
	$rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_apertura ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 }
     else
	 {
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   $ls_codemp=$row["codemp"]; 
			   $ls_procede=$row["procede"]; 
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"]; 
			   $ls_codestpro2=$row["codestpro2"]; 
			   $ls_codestpro3=$row["codestpro3"]; 
			   $ls_codestpro4=$row["codestpro4"]; 
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_estructura_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5; 
			   $ls_spg_cuenta=$row["spg_cuenta"]; 
			   $ls_procede_doc=$row["procede_doc"]; 
			   $ls_documento=$row["documento"]; 
			   $ls_operacion=$row["operacion"]; 
			   $ls_descripcion=$row["descripcion"];
			   $ld_monto=$row["monto"]; 
			   $ls_orden=$row["orden"]; 
			   $ls_denominacion=$row["denominacion"];
			   $ls_denestpro5=$row["denestpro5"];
			
			   $this->dts_reporte->insertRow("programatica",$ls_estructura_programatica);
               $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);			
               $this->dts_reporte->insertRow("denominacion",$ls_denominacion);			
               $this->dts_reporte->insertRow("descripcion",$ls_descripcion);			
	           $this->dts_reporte->insertRow("documento",$ls_documento);			
			   $this->dts_reporte->insertRow("monto",$ld_monto);			
			   $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);			
			}
	 }
	  $this->SQL->free_result($rs_data);	 
	  return $lb_valido;
}//fin uf_spg_reporte_apertura
/****************************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "MODIFICACIONES PRESUPUESTARIAS " // 
	////////////////////////////////////////////////////////////
    function uf_spg_reporte_modificaciones_presupuestarias($ai_rect,$ai_insub,$ai_trans,$ai_cred,$adt_fecini,$adt_fecfin)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_modificaciones_presupuestarias
	 //         Access :	private
	 //     Argumentos :    adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true en caso de exito de la consulta o false en otro caso 
	 //	   Description :	Reporte que genera la salida para las modificaciones presupuestarias 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    15/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_gestor = $_SESSION["ls_gestor"];
      $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
      $ls_cad=$this->uf_spg_reporte_chequear_modificaciones($ai_rect,$ai_insub,$ai_trans,$ai_cred);
      $ls_cadena=str_replace('procede','MOV.procede',$ls_cad);
      
	  $ls_sql=" SELECT CMP.descripcion as cmp_descripcion, MOV.*, (monto-monto) as aumento, (monto-monto) as disminucion, ".
	          "        CTA.denominacion,cast('' as char(50)) as doc_autor,cast('' as char(150)) as autorizante, ".
			  "        cast('' as char(10)) as fecha_aut, cast('' as char(254)) as observacion ".
              " FROM   spg_dt_cmp MOV, sigesp_cmp CMP,spg_cuentas CTA ".
              " WHERE  CMP.codemp='".$ls_codemp."' AND CMP.codemp=MOV.codemp AND  MOV.codemp=.CTA.codemp AND CMP.procede=MOV.procede AND ".
              "        CMP.comprobante=MOV.comprobante AND CMP.fecha=MOV.fecha AND MOV.codestpro1 = CTA.codestpro1 AND ".
			  "        MOV.codestpro2 = CTA.codestpro2 AND MOV.codestpro3 = CTA.codestpro3 AND MOV.codestpro4 = CTA.codestpro4 AND ".
			  "        MOV.codestpro5 = CTA.codestpro5 AND MOV.spg_cuenta = CTA.spg_cuenta AND (".$ls_cadena.")  AND  ".
	          "        MOV.fecha between '".$adt_fecini."' AND '".$adt_fecfin."' AND CMP.tipo_comp=2 ".
              " ORDER BY  MOV.comprobante, MOV.procede, MOV.fecha, MOV.orden ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_modificaciones_presupuestarias ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 } 
	 else
	 {
			while($row=$this->SQL->fetch_row($rs_data))
			{
				  $ls_cmp_descripcion=$row["cmp_descripcion"]; 
				  $ls_procede=$row["procede"]; 
				  $ls_comprobante=$row["comprobante"]; 
				  $ldt_fecha=$row["fecha"]; 
				  $ls_codestpro1=$row["codestpro1"]; 
				  $ls_codestpro2=$row["codestpro2"]; 
				  $ls_codestpro3=$row["codestpro3"]; 
				  $ls_codestpro4=$row["codestpro4"]; 
				  $ls_codestpro5=$row["codestpro5"]; 
				  $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				  $ls_spg_cuenta=$row["spg_cuenta"]; 
				  $ls_procede_doc=$row["procede_doc"]; 
				  $ls_documento=$row["documento"]; 
				  $ls_operacion=$row["operacion"]; 
				  $ls_descripcion=$row["descripcion"]; 
				  $ld_monto=$row["monto"]; 
				  $ls_orden=$row["orden"]; 
				  $ld_aumento=$row["aumento"]; 
				  $ld_disminucion=$row["disminucion"];
				  $ls_denominacion=$row["denominacion"]; 
				  $ls_doc_autor=$row["doc_autor"]; 
				  $ls_autorizante=$row["autorizante"]; 
				  $ldt_fecha_aut=$row["fecha_aut"]; 
				  $ls_observacion=$row["observacion"];
			  
			      $ld_asignado = 0;
	              $ld_aumentos = 0;
	              $ld_disminuciones = 0;
	              $ld_precompromisos = 0;
	              $ld_compromisos = 0;
	              $ld_causado = 0;
	              $ld_pagado = 0;
			      
                  $this->uf_spg_reporte_calcular_monto_operacion($ld_asignado,$ld_aumentos,$ld_disminuciones,
				                                                 $ld_precompromisos,$ld_compromisos,$ld_causado,
																 $ld_pagado,$ls_operacion,$ld_monto);			
				 $ld_aumento=$ld_aumentos;
				 $ld_disminucion=$ld_disminuciones;
				 if($ls_procede=='SPGTRA')
				 {
				   $lb_valido=$this->uf_spg_reporte_chequear_autorizacion_traspaso($ls_procede,$ls_comprobante,$ldt_fecha,
																				   $ls_doc_autor,$ls_autorizante, 
																				   $ldt_fecha_aut,$ls_observacion,$ls_codemp);
					  if($lb_valido)
					  {
						  $ls_doc_autor=$ls_doc_autor; 
						  $ls_autorizante=$ls_autorizante; 
						  $ldt_fecha_aut=$ldt_fecha_aut; 
						  $ls_observacion=$ls_observacion;
					  }
				 }
				 
			    $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
			    $this->dts_reporte->insertRow("descripcion",$ls_descripcion);	
			    $this->dts_reporte->insertRow("programatica",$ls_programatica);			
			    $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);			
			    $this->dts_reporte->insertRow("documento",$ls_documento);			
			    $this->dts_reporte->insertRow("aumento",$ld_aumento);			
			    $this->dts_reporte->insertRow("disminucion",$ld_disminucion);			
			    $this->dts_reporte->insertRow("doc_autor",$ls_doc_autor);			
			    $this->dts_reporte->insertRow("autorizante",$ls_autorizante);			
			    $this->dts_reporte->insertRow("fecha_aut",$ldt_fecha_aut);			
			    $this->dts_reporte->insertRow("observacion",$ls_observacion);			
			}//while
	 }//else
	  $this->SQL->free_result($rs_data);	 
  return $lb_valido;
}//fin uf_spg_reporte_modificaciones_presupuestarias
/****************************************************************************************************************************************/	
    function uf_spg_reporte_chequear_modificaciones( $ai_rect,$ai_insub,$ai_trans,$ai_cred )
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_chequear_modificaciones
	 //         Access :	private
	 //     Argumentos :    $ai_rect   // chequear rectificaciones
     //              	    $ai_insub // chequear insubsistencias
	 //                     $ai_trans //  chequear transpaso
	 //                     $ai_cred  // chequear credito
     //	       Returns :	Retorna una cadena con las opciones de las modificaciones presupuestarias seelccionadas 
	 //	   Description :	Verifica segun los parametros y construye una cadena para construir el reporte 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    15/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	 if($ai_rect==1) { $ls_cadena1="procede ='SPGREC' OR "; }
	 else{ $ls_cadena1="";}
	 if($ai_insub==1) { $ls_cadena2="procede ='SPGINS' OR "; }
	 else{ $ls_cadena2="";}
	 if($ai_trans==1) { $ls_cadena3="procede ='SPGTRA' OR "; }
	 else{ $ls_cadena3="";}
	 if($ai_cred==1) { $ls_cadena4="procede ='SPGCRA' OR "; }
	 else{ $ls_cadena4="";}
	 
	 $ls_cadena=$ls_cadena1.$ls_cadena2.$ls_cadena3.$ls_cadena4;
	 if(!empty($ls_cadena))
	 {
	   $ls_cadena=substr($ls_cadena,0,strlen($ls_cadena)-3);
	 }
	 return $ls_cadena;
	 
}//uf_spg_reporte_chequear_modificaciones
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_monto_operacion(&$ad_asignado,&$ad_aumentos,&$ad_disminuciones,&$ad_precompromisos,&$ad_compromisos,
	                                                 &$ad_causado,&$ad_pagado,$as_operacion,$ad_monto)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_monto_operacion
	 //         Access :	private
	 //     Argumentos :    $ad_asignado // monto asignado (referencia)
	 //                     $ad_aumentos // monto aumento (referencia)
	 //                     $ad_disminuciones // monto disminuciones (referencia)
	 //                     $ad_precompromisos // monto precompromiso (referencia)
	 //                     $ad_compromisos  // monto compromiso (referencia)
     //	                    $ad_causado //  monto causado (referencia)
	 //                     $ad_pagado  // monto pagado  (referencia)
	 //                     $as_operacion // operacion  
	 //                     $ad_monto  //  monto ed la operacion
     //	       Returns :	Retorna  por referencia los valores de cada monto 
	 //	   Description :	Verifica segun la operacion y calcula el monto 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    16/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
     $ls_operacion = $this->sigesp_int_spg->uf_operacion_codigo_mensaje($as_operacion);
     $li_pos_i=strpos($ls_operacion,"I"); //I-Asignacion
     if (!($li_pos_i===false))
     {
	   $ad_asignado = $ad_asignado + $ad_monto;
     }
     $li_pos_a=strpos($ls_operacion,"A"); //A-Aumentos
     if (!($li_pos_a===false))
     {
	   $ad_aumentos = $ad_aumentos + $ad_monto;
     }
     $li_pos_d=strpos($ls_operacion,"D"); //D-Disminución
     if (!($li_pos_d===false))
     {
	   $ad_disminuciones = $ad_disminuciones + $ad_monto;
     }
	 $li_pos_r=strpos($ls_operacion,"R"); //R-PreComprometer
	 if (!($li_pos_r===false))
     {
	   $ad_precompromisos = $ad_precompromisos + $ad_monto;
     }
	 $li_pos_o=strpos($ls_operacion,"O"); //	O-Comprometer
	 if (!($li_pos_o===false))
	 {
	   $ad_compromisos = $ad_compromisos + $ad_monto;
     }
	 $li_pos_c=strpos($ls_operacion,"C"); //	C-Causar
	 if (!($li_pos_c===false))
	 {
	   $ad_causado = $ad_causado + $ad_monto;
     }
	 $li_pos_p=strpos($ls_operacion,"P");  // P-Pagar
	 if (!($li_pos_p===false))
	 {
	   $ad_pagado = $ad_pagado + $ad_monto;
     }
	return true; 
}// fin uf_spg_reporte_calcular_monto_operacion
/****************************************************************************************************************************************/	
    function uf_spg_reporte_chequear_autorizacion_traspaso($as_procede, $as_comprobante, $adt_fecha, &$as_doc_autor, &$as_autorizante, 
	                                                       &$adt_fecha_aut, &$as_observacion,$as_codemp)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_chequear_autorizacion_traspaso
	 //         Access :	private
	 //     Argumentos :    $as_procede // procede
	 //                     $as_comprobante // numero de comprobante
	 //                     $adt_fecha // fecha 
	 //                     $as_doc_autor // documento del autorizado (referencia)
	 //                     $as_autorizante  // autorizante (referencia)
     //	                    $adt_fecha_aut //  fecha autorizacion (referencia)
	 //                     $as_observacion  // observacion (referencia)
	 //                     $as_codemp  //  codigo de la empresa
     //	       Returns :	Retorna  por referencia los valores para la autorizacion de traspaso
	 //	   Description :	Verifica si existe la autorizacion de traspaso
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    16/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=true;
	 $ls_sql=" SELECT * ".
             " FROM   spg_aut_comp ".
             " WHERE  codemp='".$as_codemp."'  AND procede='".$as_procede."' AND ".
			 "        comprobante='".$as_comprobante."' AND fecha='".$adt_fecha."' ";
	 $rs_sel=$this->SQL->select($ls_sql);
	 if($rs_sel===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_chequear_autorizacion_traspaso ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 } 
	 else
	 {
			if($row=$this->SQL->fetch_row($rs_sel))
			{
			  $as_doc_autor=$row["docauttra"];
			  $as_autorizante=$row["auttra"]; 
			  $adt_fecha_aut=$row["fecauttra"];
			  $as_observacion=$row["obstra"];
			}
			else
			{
			  $as_doc_autor="";
			  $as_autorizante=""; 
			  $adt_fecha_aut="";
			  $as_observacion="";
			}
	}		
	  $this->SQL->free_result($rs_sel);	 
  return $lb_valido;
}//fin uf_spg_reporte_chequear_autorizacion_traspaso
/****************************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  " DISPONIBILIDAD PRESUPUESTARIA " // 
	////////////////////////////////////////////////////////////
    function uf_spg_reporte_select_cuenta($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,&$rs_data,
	                                      $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
										  $as_codestpro4_des,$as_codestpro5_des,$as_cuenta_from,$as_cuenta_to,$as_ckbctasinmov)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_cuenta
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori .. $as_codestpro5_ori  // codigo de la estructura programatica origen 
     //              	    as_codestpro1_des .. $as_codestpro5_des  // codigo de la estructura programatica destino
	 //                     $as_cuenta_from  // cuenta desde 
	 //                     $as_cuenta_to  // cuenta hasta 
	 //                     $as_ckbctasinmov  //chequear cuentas sin o con movimiento 
     //	       Returns :	Retorna true en caso de exito de la consulta o false en otro caso 
	 //	   Description :	Seleciona las cuentas con o sin movimiento segun el la condicion enviada por parametro
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    18/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_struc_programatica_ori=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
	 $ls_struc_programatica_des=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $ls_gestor = $_SESSION["ls_gestor"];
	 if(strtoupper($ls_gestor)=="MYSQL")
	 {
	    $ls_concat_programatica="CONCAT(PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,PCT.codestpro4,PCT.codestpro5)";
	 }
	 else
	 {
	    $ls_concat_programatica="PCT.codestpro1||PCT.codestpro2||PCT.codestpro3||PCT.codestpro4||PCT.codestpro5";
	 }
	 if($as_ckbctasinmov)
	 {
	    $ls_sql=" SELECT distinct PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4,PCT.codestpro5,PCT.spg_cuenta, ".
                "                 PCT.denominacion, PCT.status,PCT.asignado, PCT.precomprometido, PCT.comprometido,PCT.causado, ".
				"                 PCT.pagado,PCT.aumento, PCT.disminucion,PAT.denestpro5 as denestpro5,cast(0 as UNSIGNED) as aumentosA,".
				"                 cast(0 as UNSIGNED) as disminucionesA,cast(0 as UNSIGNED) as precompromisosA, ".
				"                 cast(0 as UNSIGNED) as compromisosA ".
                " FROM            spg_cuentas PCT, spg_ep5 PAT, spg_dt_cmp PMV ".
                " WHERE           PCT.codestpro1=PAT.codestpro1 AND PCT.codestpro2=PAT.codestpro2 AND PCT.codestpro3=PAT.codestpro3 AND ".
                "                 PCT.codestpro4=PAT.codestpro4 AND PCT.codestpro5=PAT.codestpro5 AND PCT.codestpro1=PMV.codestpro1 AND ". 
                "                 PCT.codestpro2=PMV.codestpro2 AND PCT.codestpro3=PMV.codestpro3 AND PCT.codestpro4=PMV.codestpro4 AND ".
                "                 PCT.codestpro5=PMV.codestpro5 AND PCT.spg_cuenta=PMV.spg_cuenta AND ".
	            "                 ".$ls_concat_programatica." ".
                "                 between '".$ls_struc_programatica_ori."' AND '".$ls_struc_programatica_des."' AND ".
	            "                 PCT.spg_cuenta between '".$as_cuenta_from."' AND '".$as_cuenta_to."' ".
                " ORDER BY        PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,PCT.codestpro4,PCT.codestpro5,PCT.spg_cuenta ";
	 }
	 else
	 {
	    $ls_sql=" SELECT distinct PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4,PCT.codestpro5, PCT.spg_cuenta, ".
		        "                 PCT.denominacion, PCT.status,PCT.asignado, PCT.precomprometido, PCT.comprometido, PCT.causado, ".
				"                 PCT.pagado,PCT.aumento,PCT.disminucion,PAT.denestpro5 ,cast(0 as UNSIGNED) as aumentosA, ".
				"                 cast(0 as UNSIGNED) as disminucionesA,cast(0 as UNSIGNED) as precompromisosA, ".
				"                 cast(0 as UNSIGNED) as compromisosA ".
                " FROM            spg_cuentas PCT, spg_ep5 PAT ".
                " WHERE           PCT.codestpro1=PAT.codestpro1 AND PCT.codestpro2=PAT.codestpro2 AND ".
				"                 PCT.codestpro3=PAT.codestpro3 AND PCT.codestpro4=PAT.codestpro4 AND ".
				"                 PCT.codestpro5=PAT.codestpro5 AND ".
				"                 ".$ls_concat_programatica." ".
                "                 between '".$ls_struc_programatica_ori."' AND '".$ls_struc_programatica_des."' AND ".
				"                 PCT.spg_cuenta between  '".$as_cuenta_from."' AND '".$as_cuenta_to."' ".
                " ORDER BY        PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,PCT.codestpro4,PCT.codestpro5,PCT.spg_cuenta ";   
	 } 
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		$lb_valido=false;
		$this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_select_cuenta ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
   	 }
	return $lb_valido; 
	}//fin
/****************************************************************************************************************************************/	
    function uf_spg_reporte_disponibilidad_cuenta($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                              $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
												  $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin,
												  $as_cuenta_from,$as_cuenta_to,$as_ckbctasinmov,$ai_ckbhasfec)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_disponibilidad_presupuestaria
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori .. $as_codestpro5_ori  // codigo de la estructura programatica origen 
     //              	    as_codestpro1_des .. $as_codestpro5_des  // codigo de la estructura programatica destino
     //	       Returns :	Retorna true en caso de exito de la consulta o false en otro caso 
	 //	   Description :	Reporte que genera la salida para el disponible presupuestario
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    17/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe = false;	 
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("spg_cuenta");
        $ls_str_sql_where="";
		$dts_disponible=new class_datastore();
		$rs_data=0;
		if($ai_ckbhasfec==0)
		{
            $lb_valido=$this->uf_spg_reporte_select_cuenta($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$rs_data,
	                                      $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
										  $as_codestpro5_des,$as_cuenta_from,$as_cuenta_to,$as_ckbctasinmov);
			if($lb_valido)
			{
			   while($row=$this->SQL->fetch_row($rs_data))
			   {
					 $ls_codestpro1=$row["codestpro1"];
					 $ls_codestpro2=$row["codestpro2"];
					 $ls_codestpro3=$row["codestpro3"];
					 $ls_codestpro4=$row["codestpro4"];
					 $ls_codestpro5=$row["codestpro5"];
					 $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					 $ls_spg_cuenta=$row["spg_cuenta"];
					 $ls_denominacion=$row["denominacion"];
					 $ls_status=$row["status"];
					 $ld_monto_asignado=$row["asignado"];
					 $ld_monto_precomprometido=$row["precomprometido"];
					 $ld_monto_compromiso=$row["comprometido"];
					 $ld_monto_causado=$row["causado"];
					 $ld_monto_pagado=$row["pagado"];
					 $ld_monto_aumento=$row["aumento"];
					 $ld_monto_disminucion=$row["disminucion"];
					 $ls_denestpro5=$row["denestpro5"];
					 $ld_monto_aumento_a=$row["aumentosA"];
					 $ld_monto_disminucion_a=$row["disminucionesA"];
					 $ld_monto_precompromisos_a=$row["precompromisosA"];
					 $ld_monto_compromisos_a=$row["compromisosA"];
				   
				     $ld_asignado=$ld_monto_asignado+$ld_monto_aumento_a+$ld_monto_aumento-$ld_monto_disminucion_a-$ld_monto_disminucion;
					 $ld_disponible=$ld_monto_asignado+$ld_monto_aumento-$ld_monto_disminucion-$ld_monto_compromiso;
					 
					 
				     $this->dts_reporte->insertRow("programatica",$ls_programatica);
				     $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
				     $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
				     $this->dts_reporte->insertRow("status",$ls_status);
				     $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
				     $this->dts_reporte->insertRow("asignado",$ld_asignado);
				     $this->dts_reporte->insertRow("disponible",$ld_disponible);
				     $lb_valido = true;
	    	   }
			}
			else
			{
			  return false;
			}							  		
		}
		else
		{
			$this->uf_obtener_rango_programatica($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$as_codestpro5_ori,
												 $as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,$as_codestpro5_des,
												 $ls_Sql_Where,$ls_str_estructura_from,$ls_str_estructura_to);
	
			$ls_Sql_Where = trim($ls_Sql_Where);
			if ( !empty($ls_Sql_Where) )  
			{
			   $ls_str_sql_where=$ls_Sql_Where." AND ";
			}
			else
			{
			   $ls_str_sql_where="";
			}
			$ls_sql=" SELECT PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4,PCT.codestpro5, PCT.spg_cuenta, ".
					"        PCT.denominacion, PCT.status,EP.denestpro5 ".
					" FROM   spg_cuentas PCT, spg_ep5 EP ".
					" WHERE  PCT.codemp='".$ls_codemp."' AND PCT.codemp=EP.codemp AND  PCT.codestpro1=EP.codestpro1 AND ".
					"        PCT.codestpro2=EP.codestpro2 AND PCT.codestpro3=EP.codestpro3 AND PCT.codestpro4=EP.codestpro4 AND ".
					"        PCT.codestpro5=EP.codestpro5 AND ".$ls_str_sql_where." ".
					"        PCT.spg_cuenta between '".$as_cuenta_from."' AND  '".$as_cuenta_to."' ".
					" ORDER BY PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4, PCT.codestpro5, PCT.spg_cuenta ";
		   $rs_data=$this->SQL->select($ls_sql);
		   if($rs_data===false)
		   {
			  $lb_valido=false;
			  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_disponibilidad_presupuestaria ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
		   }
		   else
		   {
			   if($row=$this->SQL->fetch_row($rs_data))
			   {
				  $dts_disponible->data=$this->SQL->obtener_datos($rs_data);
				  $lb_existe=true;
			   }
			   $this->SQL->free_result($rs_data);   
			   if($lb_existe==false)
			   {
				  return false; // no hay registro
			   }
			   $li_total_row=$dts_disponible->getRowCount("spg_cuenta");			
			   for ($li_i=1;$li_i<=$li_total_row;$li_i++)
			   {   
				   $ls_codestpro1 = $dts_disponible->getValue("codestpro1",$li_i);
				   $ls_codestpro2 = $dts_disponible->getValue("codestpro2",$li_i);
				   $ls_codestpro3 = $dts_disponible->getValue("codestpro3",$li_i);
				   $ls_codestpro4 = $dts_disponible->getValue("codestpro4",$li_i);
				   $ls_codestpro5 = $dts_disponible->getValue("codestpro5",$li_i);
				   $ls_programatica = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				   $ls_spg_cuenta = $dts_disponible->getValue("spg_cuenta",$li_i);
				   $ls_denominacion = $dts_disponible->getValue("denominacion",$li_i);
				   $ls_status = $dts_disponible->getValue("status",$li_i);
				   $ls_denestpro5 = $dts_disponible->getValue("denestpro5",$li_i);
				   
				   $ld_monto_asignado=0;
				   $ld_monto_aumento=0;
				   $ld_monto_disminucion=0;
				   $ld_monto_precompromiso=0;
				   $ld_monto_compromiso=0;
				   $ld_monto_causado=0;
				   $ld_monto_pagado=0;
				   $ld_monto_aumento_a=0;
				   $ld_monto_disminucion_a=0;
				   $ld_monto_precompromiso_a=0;
				   $ld_monto_compromiso_a=0;
				   
				   if(!$this->uf_calcular_disponible_operacion_por_cuenta($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
																		  $ls_codestpro5,$ls_spg_cuenta,$adt_fecini,$adt_fecfin,
																		  &$ld_monto_asignado,&$ld_monto_aumento,&$ld_monto_disminucion,
																		  &$ld_monto_precompromiso,&$ld_monto_compromiso,&$ld_monto_causado,
																		  &$ld_monto_pagado,&$ld_monto_aumento_a,&$ld_monto_disminucion_a,
																		  &$ld_monto_precompromiso_a,&$ld_monto_compromiso_a))
					{
					  return false;
					}	
					
					$ld_asignado=$ld_monto_asignado+$ld_monto_aumento_a+$ld_monto_aumento-$ld_monto_disminucion_a-$ld_monto_disminucion;
					$ld_disponible=$ld_monto_asignado+$ld_monto_aumento-$ld_monto_disminucion-$ld_monto_compromiso;
					if($as_ckbctasinmov) 
					{
						  if(($ld_monto_asignado<>0)||($ld_monto_aumento<>0)||($ld_monto_disminucion<>0)||($ld_monto_precompromiso<>0)||
							 ($ld_monto_compromiso<>0)||($ld_monto_causado<>0)||($ld_monto_pagado<>0)||($ld_monto_aumento_a<>0)||
							 ($ld_monto_disminucion_a<>0)||($ld_monto_precompromiso_a<>0)||($ld_monto_compromiso_a<>0))
						  {
							  $lb_ok=true;
						  }
						  else
						  {
							 $lb_ok=false;
						  }      
					}
					else
					{
					  $lb_ok=true;
					}
					if($lb_ok)
					{
					   $this->dts_reporte->insertRow("programatica",$ls_programatica);
					   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
					   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					   $this->dts_reporte->insertRow("status",$ls_status);
					   $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
					   $this->dts_reporte->insertRow("asignado",$ld_asignado);
					   $this->dts_reporte->insertRow("disponible",$ld_disponible);
					   $lb_valido = true;
					}//if
			   }//for
		   }//else 
	   }//else
  return $lb_valido;
}//fin uf_spg_reporte_disponibilidad
/****************************************************************************************************************************************/	
	function uf_calcular_disponible_operacion_por_cuenta($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                                     $as_spg_cuenta,$adt_fecini,$adt_fecfin,&$adec_monto_asignado,
														 &$adec_monto_aumento,&$adec_monto_disminucion,&$adec_monto_precompromiso,
														 &$adec_monto_compromiso,&$adec_monto_causado,&$adec_monto_pagado,
														 &$adec_monto_aumento_a,&$adec_monto_disminucion_a,&$adec_monto_precompromiso_a,
														 &$adec_monto_compromiso_a)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_disponible_operacion_por_cuenta -> proviene de uf_spg_reporte_disponibilidad_presupuestaria
     //	    Returns :	$lb_valido true si se realizo la funcion con exito o false en caso contrario
	 //	Description :	Método  que ejecuta todas funciones de acumulado para asi sacar el acumulado por cuentas
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true; 
	   $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
   	   // Global	   
       $lb_valido=$this->uf_calcular_disponible_asignacion($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                                       $as_spg_cuenta,&$adec_monto_asignado);
	   // acumulado Anteriores
       if ($lb_valido)
  	   { 
	      $ls_operacion="aumento";
          $lb_valido=$this->uf_calcular_disponible_anterior($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                    $adt_fecini,$as_spg_cuenta,&$adec_monto_aumento_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="disminucion";
          $lb_valido=$this->uf_calcular_disponible_anterior($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                    $adt_fecini,$as_spg_cuenta,&$adec_monto_disminucion_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="precomprometer";
          $lb_valido=$this->uf_calcular_disponible_anterior($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                    $adt_fecini,$as_spg_cuenta,&$adec_monto_precompromiso_a,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="comprometer";
          $lb_valido=$this->uf_calcular_disponible_anterior($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                    $adt_fecini,$as_spg_cuenta,&$adec_monto_compromiso_a,$ls_operacion);
	   }
	   // En el Rango
       if ($lb_valido)
  	   { 
	      $ls_operacion="aumento";
          $lb_valido=$this->uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                     $adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_aumento,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="disminucion";
          $lb_valido=$this->uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                     $adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_disminucion,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="precomprometer";
          $lb_valido=$this->uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                     $adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_precompromiso,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="comprometer";
          $lb_valido=$this->uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                     $adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_compromiso,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="causar";
          $lb_valido=$this->uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                     $adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_causado,$ls_operacion);
	   }
       if ($lb_valido)
  	   { 
	      $ls_operacion="pagar";
          $lb_valido=$this->uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
		                                                     $adt_fecini,$adt_fecfin,$as_spg_cuenta,&$adec_monto_pagado,$ls_operacion);
	   }
	   return $lb_valido;
	} // fin uf_calcular_acumulado_operaciones_por_cuenta 
/****************************************************************************************************************************************/	
	function uf_calcular_disponible_asignacion($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                           $as_spg_cuenta,&$adec_monto)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_disponible_asignacion( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma lo asignado por cuenta
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_sql    = " SELECT COALESCE(SUM(monto),0) As monto ".
                     " FROM   spg_dt_cmp PCT,spg_operaciones O ".
					 " WHERE  PCT.codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND  O.asignar=1 AND ".
					 "        PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND PCT.codestpro1='".$as_codestpro1."' AND ".
					 "        PCT.codestpro2='".$as_codestpro2."' AND PCT.codestpro3='".$as_codestpro3."' AND ".
					 "        PCT.codestpro4='".$as_codestpro4."' AND PCT.codestpro5='".$as_codestpro5."' ";				 
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo uf_calcular_disponible_asignacion".$this->fun->uf_convertirmsg($this->SQL->message));
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
	} // fin function uf_calcular_disponible_asignacion
/****************************************************************************************************************************************/	
	function uf_calcular_disponible_por_rango($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                          $adt_fecini,$adt_fecfin,$as_spg_cuenta,$adec_monto,$as_operacion)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_disponible_por_rango( -> proviene de uf_calcular_disponible_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma dependiando de la operacion(aumento,disminucion,precompromiso,compromiso)
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = " SELECT COALESCE(SUM(monto),0) As monto ".
                     " FROM  spg_dt_cmp PCT,spg_operaciones O  ".
                     " WHERE codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND ".
                     "       O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
					 "       fecha >='".$adt_fecini."' AND fecha <='".$adt_fecfin."' AND ".
					 "       PCT.codestpro1='".$as_codestpro1."' AND PCT.codestpro2='".$as_codestpro2."' AND ".
					 "       PCT.codestpro3='".$as_codestpro3."' AND PCT.codestpro4='".$as_codestpro4."' AND ".
					 "       PCT.codestpro5='".$as_codestpro5."' ";	
		$rs_data=$this->SQL->select($ls_mysql);
		if($rs_data===false)
		{   // error interno sql
            $this->io_msg->message("Error en uf_calcular_disponible_por_rango ".$this->fun->uf_convertirmsg($this->SQL->message));
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
	} // fin function uf_calcular_disponible_por_rango
/****************************************************************************************************************************************/	
	function uf_calcular_disponible_anterior($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                         $adt_fecini,$as_spg_cuenta,$adec_monto,$as_operacion)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_disponible_anterior( -> proviene de uf_calcular_disponible_operaciones_por_cuenta
     //	    Returns :	Retorna monto aumento
	 //	Description :	Método que consulta y suma el aumento de la cuenta 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = " SELECT COALESCE(SUM(monto),0) As monto ".
                     " FROM   spg_dt_cmp PCT,spg_operaciones O ".
					 " WHERE  PCT.codemp='".$ls_codemp."' AND PCT.operacion=O.operacion AND ".
                     "        O.".$as_operacion."=1 AND PCT.spg_cuenta LIKE '".$as_spg_cuenta."' AND ".
					 "        PCT.fecha <'".$adt_fecini."' AND PCT.codestpro1='".$as_codestpro1."' AND ".
					 "        PCT.codestpro2='".$as_codestpro2."' AND PCT.codestpro3='".$as_codestpro3."' AND ".
					 "        PCT.codestpro4='".$as_codestpro4."' AND PCT.codestpro5='".$as_codestpro5."' " ;
		$rs_data=$this->SQL->select($ls_mysql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_calcular_disponible_anterior ".$this->fun->uf_convertirmsg($this->SQL->message);
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
	} // fin function uf_calcular_disponible_anterior
/****************************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  " COMPROBANTES FORMATO 1 Y FORMATO 2" // 
	////////////////////////////////////////////////////////////////
    function uf_spg_reporte_comprobante_formato1($as_procede_ori,$as_procede_des,$as_comprobante_ori,$as_comprobante_des,
	                                             $adt_fecini,$adt_fecfin)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
	 //                     $as_procede_des  // procede destino
	 //                     $as_comprobante_ori  // comprobante origen 
	 //                     $as_comprobante_des  //  comprobante destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido = false;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("spg_cuenta");
        
		if((!empty($as_procede_ori))&&(!empty($as_procede_des)))
		{
			   $ls_cad_where1=" MV.procede between '".$as_procede_ori."' AND  '".$as_procede_des."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if((!empty($as_comprobante_ori))&&(!empty($as_comprobante_des)))
		{
			   $ls_cad_where2=" MV.comprobante between '".$as_comprobante_ori."' AND  '".$as_comprobante_des."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if((!empty($adt_fecini))&&(!empty($adt_fecfin)))
		{
			   $ls_cad_where3=" MV.fecha between '".$adt_fecini."' AND  '".$adt_fecfin."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))
		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	  $ls_sql=" SELECT * ".
              " FROM ".
	          "       (SELECT MV.*, CC.denominacion as dencuenta, OP.denominacion as denoperacion,PR.denestpro5 ".
              "        FROM   spg_dt_cmp MV, spg_cuentas CC, spg_operaciones OP, spg_ep5 PR ".
              "        WHERE  MV.codemp=CC.codemp AND CC.codemp=PR.codemp AND PR.codemp='".$ls_codemp."' AND ".
              "               MV.spg_cuenta = CC.spg_cuenta AND MV.codestpro1=CC.codestpro1 AND ".
              "               MV.codestpro2=CC.codestpro2 AND MV.codestpro3=CC.codestpro3 AND ".
              "               MV.codestpro4=CC.codestpro4 AND MV.codestpro5=CC.codestpro5 AND ".
              "               MV.operacion=OP.operacion AND MV.codestpro1=PR.codestpro1 AND ".
              "               MV.codestpro2=PR.codestpro2 AND MV.codestpro3=PR.codestpro3 AND ".
              "               MV.codestpro4=PR.codestpro4 AND MV.codestpro5=PR.codestpro5  ".
              "               ".$ls_cad_where." ".
	          "        ) rep1 ".
	          " left join ".
	          " (SELECT CMP.codemp,CMP.procede,CMP.comprobante,CMP.fecha, CMP.tipo_destino, CMP.cod_pro, CMP.ced_bene, ".
		      "         PRV.nompro, BEN.apebene, BEN.nombene ".
	          " FROM 	sigesp_cmp CMP, rpc_proveedor PRV,rpc_beneficiario BEN ".
	          " WHERE 	CMP.codemp=PRV.codemp AND PRV.codemp=BEN.codemp AND BEN.codemp='".$ls_codemp."'AND CMP.cod_pro=PRV.cod_pro AND ".	
              "         CMP.ced_bene=BEN.ced_bene) rep2 ".
	          "  on 	rep1.codemp=rep2.codemp AND rep1.procede=rep2.procede AND rep1.comprobante=rep2.comprobante AND ".
			  "         rep1.fecha=rep2.fecha ".
              " ORDER BY rep1.procede,rep1.comprobante,rep1.fecha ";
		//print $ls_sql;
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
               $ls_procede=$row["procede"]; 
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"];
			   $ls_denestpro1="";
			   $lb_valido=$this->uf_spg_reporte_select_denestpro1($ls_codestpro1,$ls_denestpro1);
			   if($lb_valido)
			   {
			     $ls_denestpro1=$ls_denestpro1;
			   }
			   $ls_codestpro2=$row["codestpro2"];
			   if($lb_valido)
			   {
			     $ls_denestpro2="";
			     $lb_valido=$this->uf_spg_reporte_select_denestpro2($ls_codestpro1,$ls_codestpro2,$ls_denestpro2);
				 $ls_denestpro2=$ls_denestpro2;
			   }
			   $ls_codestpro3=$row["codestpro3"];
			   if($lb_valido)
			   {
			     $ls_denestpro3="";
			     $lb_valido=$this->uf_spg_reporte_select_denestpro3($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_denestpro3);
				 $ls_denestpro3=$ls_denestpro3;
			   }
			   $ls_codestpro4=$row["codestpro4"];
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"];
			   $ls_operacion=$row["operacion"];
			   $ls_descripcion=$row["descripcion"];
			   $ld_monto=$row["monto"];
			   $ls_orden=$row["orden"];
			   $ls_dencuenta=$row["dencuenta"];
			   $ls_denoperacion=$row["denoperacion"];
			   $ls_denestpro5=$row["denestpro5"];
			   $ls_tipo_destino=$row["tipo_destino"];
			   $ls_cod_pro=$row["cod_pro"];
			   $ls_ced_bene=$row["ced_bene"];
			   $ls_nompro=$row["nompro"];
			   $ls_apebene=$row["apebene"];
			   $ls_nombene=$row["nombene"];
			   
			   $this->dts_reporte->insertRow("procede",$ls_procede);
			   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte->insertRow("denestpro1",$ls_denestpro1);
			   $this->dts_reporte->insertRow("denestpro2",$ls_denestpro2);
			   $this->dts_reporte->insertRow("denestpro3",$ls_denestpro3);
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("procede_doc",$ls_procede_doc);
			   $this->dts_reporte->insertRow("documento",$ls_documento);
			   $this->dts_reporte->insertRow("operacion",$ls_operacion);
			   $this->dts_reporte->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte->insertRow("monto",$ld_monto);
			   $this->dts_reporte->insertRow("orden",$ls_orden);
			   $this->dts_reporte->insertRow("dencuenta",$ls_dencuenta);
			   $this->dts_reporte->insertRow("denoperacion",$ls_denoperacion);
			   $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
			   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
			   $this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte->insertRow("apebene",$ls_apebene);
			   $this->dts_reporte->insertRow("nombene",$ls_nombene);
			   $lb_valido = true;
			}//while
			$this->SQL->free_result($rs_data);
		}//else
  return $lb_valido;
  }// fin uf_spg_reporte_comprobante_formato1
/****************************************************************************************************************************************/	
    function uf_spg_reporte_select_comprobante_formato1($as_procede_ori,$as_procede_des,$as_comprobante_ori,$as_comprobante_des,
	                                                    $adt_fecini,$adt_fecfin)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
	 //                     $as_procede_des  // procede destino
	 //                     $as_comprobante_ori  // comprobante origen 
	 //                     $as_comprobante_des  //  comprobante destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("spg_cuenta"); 
        //$this->dts_cab->resetds("comprobante");  en caso de ser usado otra vez 
         
		if((!empty($as_procede_ori))&&(!empty($as_procede_des)))
		{
			   $ls_cad_where1=" MV.procede between '".$as_procede_ori."' AND  '".$as_procede_des."' ";
		}
		else
		{
		 	   $ls_cad_where1="";
		}
		if((!empty($as_comprobante_ori))&&(!empty($as_comprobante_des)))
		{
			   $ls_cad_where2=" MV.comprobante between '".$as_comprobante_ori."' AND  '".$as_comprobante_des."' ";
		}
		else
		{
		 	   $ls_cad_where2="";
		}
		if((!empty($adt_fecini))&&(!empty($adt_fecfin)))
		{
			   $ls_cad_where3=" MV.fecha between '".$adt_fecini."' AND  '".$adt_fecfin."' ";
		}
		else
		{
		 	   $ls_cad_where3="";
		}
		
		$ls_cadena_concat=$ls_cad_where1.$ls_cad_where2.$ls_cad_where3;
		if (!empty($ls_cadena_concat))
		{
			$ls_cad_where=" AND ";
			
			if(!empty($ls_cad_where1))
			{
				$ls_cad_concat=$ls_cad_where2.$ls_cad_where3;
				$ls_cond_iif=$this->iif(!empty($ls_cad_concat)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where1.$ls_cond_iif;
			}
			if(!empty($ls_cad_where2))
			{
				$ls_cond_iif=$this->iif(!empty($ls_cad_where3)," AND ", "");		    
				$ls_cad_where=$ls_cad_where.$ls_cad_where2.$ls_cond_iif;
			}
			if(!empty($ls_cad_where3))
			{
				$ls_cad_where=$ls_cad_where.$ls_cad_where3;
			}
	   }
	   else
	   {
	        $ls_cad_where=" ";
	   }	
	  $ls_sql=" SELECT distinct rep1.comprobante,rep1.procede,rep1.fecha,rep2.ced_bene,rep2.cod_pro,nompro,".
              "        rep2.apebene,rep2.nombene,rep2.tipo_destino ".
              " FROM ".
	          "       (SELECT MV.*, CC.denominacion as dencuenta, OP.denominacion as denoperacion,PR.denestpro5 ".
              "        FROM   spg_dt_cmp MV, spg_cuentas CC, spg_operaciones OP, spg_ep5 PR ".
              "        WHERE  MV.codemp=CC.codemp AND CC.codemp=PR.codemp AND PR.codemp='".$ls_codemp."' AND ".
              "               MV.spg_cuenta = CC.spg_cuenta AND MV.codestpro1=CC.codestpro1 AND ".
              "               MV.codestpro2=CC.codestpro2 AND MV.codestpro3=CC.codestpro3 AND ".
              "               MV.codestpro4=CC.codestpro4 AND MV.codestpro5=CC.codestpro5 AND ".
              "               MV.operacion=OP.operacion AND MV.codestpro1=PR.codestpro1 AND ".
              "               MV.codestpro2=PR.codestpro2 AND MV.codestpro3=PR.codestpro3 AND ".
              "               MV.codestpro4=PR.codestpro4 AND MV.codestpro5=PR.codestpro5  ".
              "               ".$ls_cad_where." ".
	          "        ) rep1 ".
	          " left join ".
	          " (SELECT CMP.codemp,CMP.procede,CMP.comprobante,CMP.fecha, CMP.tipo_destino, CMP.cod_pro, CMP.ced_bene, ".
		      "         PRV.nompro, BEN.apebene, BEN.nombene ".
	          " FROM 	sigesp_cmp CMP, rpc_proveedor PRV,rpc_beneficiario BEN ".
	          " WHERE 	CMP.codemp=PRV.codemp AND PRV.codemp=BEN.codemp AND BEN.codemp='".$ls_codemp."'AND CMP.cod_pro=PRV.cod_pro AND ".	
              "         CMP.ced_bene=BEN.ced_bene) rep2 ".
	          "  on 	rep1.codemp=rep2.codemp AND rep1.procede=rep2.procede AND rep1.comprobante=rep2.comprobante AND ".
			  "         rep1.fecha=rep2.fecha ".
              " ORDER BY rep1.procede,rep1.comprobante,rep1.fecha ";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
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
  }//uf_spg_reporte_select_comprobante_formato1
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
	function iif($ad_condicional,$ad_true,$ad_false)
	{
		if(eval("return $ad_condicional;"))
		{
			$ad_return=$ad_true;
		}
		else
		{
			$ad_return=($ad_false);
		}
		return $ad_return;
	}
/****************************************************************************************************************************************/	
   function  uf_spg_reporte_comprobante_formato2($as_spg_cuenta_ori,$as_spg_cuenta_des,$adt_fecini,$adt_fecfin) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_comprobante_formato2
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta_ori  // cuenta origen
	 //                     $as_spg_cuenta_des  // cuenta destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 2  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      
	  $lb_valido = true;
	  $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
	  
	  if (empty($as_spg_cuenta_ori) && (!empty($as_spg_cuenta_des)))
	  {
	      $this->io_msg("Debe especificar cuenta DESDE....");
	      return false;
	  }	  
	  if (!empty($as_spg_cuenta_ori) && empty($as_spg_cuenta_des))
	  {
	      $this->io_msg("Debe especificar cuenta HASTA....");
	      return false;
	  }	  
      if (!empty($as_spg_cuenta_ori) && (!empty($as_spg_cuenta_des)))
	  {
         $ls_cad_filtro1=" , (SELECT cmp.comprobante,cmp.fecha,cmp.procede ".
                         "    FROM   sigesp_cmp cmp, spg_dt_cmp MOV ".
                         "    WHERE  cmp.codemp=MOV.codemp AND cmp.codemp='".$ls_codemp."' AND cmp.procede=MOV.procede AND ".
                         "           cmp.comprobante=MOV.comprobante AND ".
	                     "           cmp.fecha=MOV.fecha AND  MOV.spg_cuenta Between '".$as_spg_cuenta_ori."' AND ".
						 "           '".$as_spg_cuenta_des."' ".
                         "   GROUP BY cmp.comprobante,cmp.fecha,cmp.procede) as curFiltrado ";	  
		 
		 $ls_cad_filtro2=" AND MV.comprobante=curFiltrado.comprobante ".
                         " AND MV.fecha=curFiltrado.fecha ".
                         " AND MV.procede=curFiltrado.procede ";				 
	  } 
      else
	  {
	     $ls_cad_filtro1=" , (SELECT cmp.comprobante,cmp.fecha,cmp.procede ".
                         "    FROM   sigesp_cmp cmp, spg_dt_cmp MOV ".
                         "    WHERE  cmp.codemp=MOV.codemp AND cmp.codemp='".$ls_codemp."' AND cmp.procede=MOV.procede AND ".
                         "           cmp.comprobante=MOV.comprobante AND ".
  	                     "           cmp.fecha=MOV.fecha ".
                         "    GROUP BY  cmp.comprobante,cmp.fecha,cmp.procede) as curFiltrado ";
        
		 $ls_cad_filtro2=" AND MV.comprobante=curFiltrado.comprobante ".
                         " AND MV.fecha=curFiltrado.fecha ".
                         " AND MV.procede=curFiltrado.procede ";	
	  }
       
	  if ((!empty($adt_fecini)) && (!empty($adt_fecfin)))
	  {
	     $ls_cad_where3=" AND MV.fecha between '".$adt_fecini."' and '".$adt_fecfin."' ";
	  }
      else
	  {
	     $ls_cad_where3="";
	  } 
      $ls_cad_where=$ls_cad_where3;
	 
	  if (empty($adt_fecini) && empty($adt_fecfin))
	  {
	    $ls_cad_where= $ls_cad_where;
	  }	
	  if ( (!empty($adt_fecini) && empty($adt_fecfin))||(empty($adt_fecini) && !empty($adt_fecfin)))
	  {
	      $ls_cad_where= $ls_cad_where."";
      }
	  $ls_sql=" SELECT * ".
              " FROM (SELECT  MV.*,  CC.denominacion,  OP.denominacion as denoperacion, PR.denestpro5 ".
              "       FROM 	  spg_dt_cmp MV, spg_cuentas CC, spg_operaciones OP, spg_ep5 PR ".$ls_cad_filtro1." ".
              "       WHERE   (MV.codemp=CC.codemp AND CC.codemp='".$ls_codemp."' AND  MV.spg_cuenta = CC.spg_cuenta AND  ".
			  "                MV.codestpro1=CC.codestpro1 AND MV.codestpro2=CC.codestpro2 AND ".
              "                MV.codestpro3=CC.codestpro3 AND MV.codestpro4=CC.codestpro4 AND MV.codestpro5=CC.codestpro5) AND ".
	          "               (MV.operacion = OP.operacion) AND ".
              "               (MV.codestpro1=PR.codestpro1 AND MV.codestpro2=PR.codestpro2 AND MV.codestpro3=PR.codestpro3 AND ".
			  "                MV.codestpro4=PR.codestpro4 AND MV.codestpro5=PR.codestpro5) ".$ls_cad_where3." ".
              "                ".$ls_cad_filtro2." ".
              "       ORDER BY MV.procede,MV.comprobante,MV.fecha, MV.orden) rep1 ".
              " LEFT JOIN (SELECT CMP.codemp,CMP.procede,CMP.comprobante,CMP.fecha, CMP.tipo_destino, CMP.cod_pro, ".
              "                   CMP.ced_bene, PRV.nompro, BEN.apebene, BEN.nombene ".
	          "            FROM   sigesp_cmp CMP, rpc_proveedor PRV,rpc_beneficiario BEN ".
	          "            WHERE  CMP.codemp=PRV.codemp AND PRV.codemp=BEN.codemp AND BEN.codemp='".$ls_codemp."' AND ".
			  "                   CMP.cod_pro=PRV.cod_pro AND CMP.ced_bene=BEN.ced_bene)rep2 ".
              " on 	rep1.codemp=rep2.codemp AND rep1.procede=rep2.procede AND rep1.comprobante=rep2.comprobante AND ".
              "     rep1.fecha=rep2.fecha ".
              " ORDER BY rep1.procede,rep1.comprobante,rep1.fecha ";
		//print $ls_sql;
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_data))
			{
			   
			   $ls_procede=$row["procede"]; 
			   $ls_comprobante=$row["comprobante"];
			   $ldt_fecha=$row["fecha"];
			   $ls_codestpro1=$row["codestpro1"];
			   $ls_codestpro2=$row["codestpro2"];
			   $ls_codestpro3=$row["codestpro3"];
			   $ls_codestpro4=$row["codestpro4"];
			   $ls_codestpro5=$row["codestpro5"];
			   $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			   $ls_spg_cuenta=$row["spg_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"];
			   $ls_operacion=$row["operacion"];
			   $ls_descripcion=$row["descripcion"];
			   $ld_monto=$row["monto"];
			   $ls_orden=$row["orden"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_denoperacion=$row["denoperacion"];
			   $ls_denestpro5=$row["denestpro5"];
			   $ls_tipo_destino=$row["tipo_destino"];
			   $ls_cod_pro=$row["cod_pro"];
			   $ls_ced_bene=$row["ced_bene"];
			   $ls_nompro=$row["nompro"];
			   $ls_apebene=$row["apebene"];
			   $ls_nombene=$row["nombene"];
			   
			   $this->dts_reporte->insertRow("procede",$ls_procede);
			   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte->insertRow("programatica",$ls_programatica);
			   $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("procede_doc",$ls_procede_doc);
			   $this->dts_reporte->insertRow("documento",$ls_documento);
			   $this->dts_reporte->insertRow("operacion",$ls_operacion);
			   $this->dts_reporte->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte->insertRow("monto",$ld_monto);
			   $this->dts_reporte->insertRow("orden",$ls_orden);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("denoperacion",$ls_denoperacion);
			   $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
			   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
			   $this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte->insertRow("apebene",$ls_apebene);
			   $this->dts_reporte->insertRow("nombene",$ls_nombene);
			   $lb_valido = true;
			}//while
			$this->SQL->free_result($rs_data);
		}//else
  return $lb_valido;
 }//uf_spg_reporte_comprobante_formato2
/****************************************************************************************************************************************/	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_modificaciones_programado($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
												 $as_codestproh1,$as_codestproh2,$as_codestproh3,$as_codestproh4,$as_codestproh5,
												 $as_cuentades,$as_cuentahas,$as_codusu,&$ab_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_modificaciones_programado
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 as_numsoldes     // Numero de Solicitud Desde
		//                 as_numsolhas     // Numero de Solicitud Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes de pago en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp = $this->dts_empresa["codemp"];
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_estructura5_desde=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4.$as_codestpro5;
		$ls_estructura5_hasta=$as_codestproh1.$as_codestproh2.$as_codestproh3.$as_codestproh4.$as_codestproh5;	 
		$ls_estructura4_desde=$as_codestpro1.$as_codestpro2.$as_codestpro3.$as_codestpro4;
		$ls_estructura4_hasta=$as_codestproh1.$as_codestproh2.$as_codestproh3.$as_codestproh4;	 
		$ls_estructura3_desde=$as_codestpro1.$as_codestpro2.$as_codestpro3;
		$ls_estructura3_hasta=$as_codestproh1.$as_codestproh2.$as_codestproh3;
		$ls_estructura2_desde=$as_codestpro1.$as_codestpro2;
		$ls_estructura2_hasta=$as_codestproh1.$as_codestproh2;	 
		if($as_codestpro1!="0000000000000000000000000")
		{
			if($as_codestpro2!="0000000000000000000000000")
			{
				if($as_codestpro3!="0000000000000000000000000")
				{
					if($as_codestpro4!="0000000000000000000000000")
					{
						if($as_codestpro5!="0000000000000000000000000")
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2,spg_regmodprogramado.codestpro3,spg_regmodprogramado.codestpro4,spg_regmodprogramado.codestpro5)>='".$ls_estructura5_desde."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2||spg_regmodprogramado.codestpro3||spg_regmodprogramado.codestpro4||spg_regmodprogramado.codestpro5 >= '".$ls_estructura5_desde."'";
							}
						}
						else
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2,spg_regmodprogramado.codestpro3,spg_regmodprogramado.codestpro4)>='".$ls_estructura4_desde."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2||spg_regmodprogramado.codestpro3||spg_regmodprogramado.codestpro4 >= '".$ls_estructura4_desde."'";
							}
						}
					}
					else
					{
						if (strtoupper($ls_gestor)=="MYSQLT")
						{
						   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2,spg_regmodprogramado.codestpro3)>='".$ls_estructura3_desde."'";
						}
						else
						{
						   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2||spg_regmodprogramado.codestpro3 >= '".$ls_estructura3_desde."'";
						}
					}
				}
				else
				{
					if (strtoupper($ls_gestor)=="MYSQLT")
					{
					   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2)>='".$ls_estructura2_desde."'";
					}
					else
					{
					   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2 >= '".$ls_estructura2_desde."'";
					}
				}
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND spg_regmodprogramado.codestpro1>='".str_pad($as_codestpro1,25,'0',0)."'";
			}
			
		}

		if($as_codestproh1!="0000000000000000000000000")
		{
			if($as_codestproh2!="0000000000000000000000000")
			{
				if($as_codestproh3!="0000000000000000000000000")
				{
					if($as_codestproh4!="0000000000000000000000000")
					{
						if($as_codestproh5!="0000000000000000000000000")
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2,spg_regmodprogramado.codestpro3,spg_regmodprogramado.codestpro4,spg_regmodprogramado.codestpro5)<='".$ls_estructura5_hasta."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2||spg_regmodprogramado.codestpro3||spg_regmodprogramado.codestpro4||spg_regmodprogramado.codestpro5 <= '".$ls_estructura5_hasta."'";
							}
						}
						else
						{
						    if (strtoupper($ls_gestor)=="MYSQLT")
							{
							   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2,spg_regmodprogramado.codestpro3,spg_regmodprogramado.codestpro4)<='".$ls_estructura4_hasta."'";
							}
							else
							{
							   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2||spg_regmodprogramado.codestpro3||spg_regmodprogramado.codestpro4 <= '".$ls_estructura4_hasta."'";
							}
						}
					}
					else
					{
						if (strtoupper($ls_gestor)=="MYSQLT")
						{
						   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2,spg_regmodprogramado.codestpro3)<='".$ls_estructura3_hasta."'";
						}
						else
						{
						   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2||spg_regmodprogramado.codestpro3 <= '".$ls_estructura3_hasta."'";
						}
					}
				}
				else
				{
					if (strtoupper($ls_gestor)=="MYSQLT")
					{
					   $ls_criterio= $ls_criterio." AND CONCAT(spg_regmodprogramado.codestpro1,spg_regmodprogramado.codestpro2)<='".$ls_estructura2_hasta."'";
					}
					else
					{
					   $ls_criterio= $ls_criterio." AND spg_regmodprogramado.codestpro1||spg_regmodprogramado.codestpro2 <= '".$ls_estructura2_hasta."'";
					}
				}
			}
			else
			{
				$ls_criterio= $ls_criterio."   AND spg_regmodprogramado.codestpro1<='".str_pad($as_codestproh1,25,'0',0)."'";
			}
			
		}
		if(!empty($as_cuentades))
		{
			$ls_criterio=$ls_criterio. "  AND spg_regmodprogramado.spg_cuenta>='".$as_cuentades."'";
		}
		if(!empty($as_cuentahas))
		{
			$ls_criterio=$ls_criterio. "  AND spg_regmodprogramado.spg_cuenta<='".$as_cuentahas."'";
		}
		if(!empty($as_codusu))
		{
			$ls_criterio=$ls_criterio. "  AND spg_regmodprogramado.codusu='".$as_codusu."'";
		}
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, estcla, spg_cuenta, codusu,".
				"       mesaumento, mesdisminucion, monto,fecha ".
				"  FROM spg_regmodprogramado ".	
				" WHERE spg_regmodprogramado.codemp='".$ls_codemp."' ".
				"   ".$ls_criterio." ".
				" ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, estcla, spg_cuenta";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{print $this->SQL->message;
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_modificaciones_programado ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_solicitudes
	//-----------------------------------------------------------------------------------------------------------------------------------

}//end clase 
?>
