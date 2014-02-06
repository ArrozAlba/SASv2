<?php
class sigesp_scg_class_bal_generalbsf
{
	var $la_empresa;
	var $io_fun;
	var $io_sql;
	var $io_sql_aux;
	var $io_msg;
	var $int_scg;
	var $ds_reporte;
	var $ds_prebalance;
	var $ds_balance1;
	var $ds_cuentas;
	var $ia_niveles;
	var $io_fecha;
	var $ls_gestor;
	var $int_spi;
	var $int_spg;
	var $ls_activo;
	var $ls_pasivo;
	var $ls_resultado;
	var $ls_cta_resultado;
	var $ls_capital;
	var $ls_ingreso;
	var $ls_gastos; 
	var $ls_orden_d;
	var $ls_orden_h;
	
	function sigesp_scg_class_bal_generalbsf()
	{
		$this->io_fun = new class_funciones();
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->io_sql= new class_sql($this->con);
		$this->io_sql_aux= new class_sql($this->con);
		$this->io_msg= new class_mensajes();		
		$this->io_fecha=new class_fecha();
		$this->la_empresa=$_SESSION["la_empresa"];
		$this->ds_reporte=new class_datastore();
		$this->ds_Prebalance=new class_datastore();
		$this->ds_Balance1=new class_datastore();
		$this->ds_cuentas=new class_datastore();
		$this->ds_reporte=new class_datastore();
		$this->int_scg=new class_sigesp_int_scg();
		$this->ls_gestor = $_SESSION["ls_gestor"];
		$this->ia_niveles=array();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////************************************BALANCE GENERAL*************************************************////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_balance_general($ad_fecfin,$ai_nivel)
	{
		$lb_valido=true;
		$ds_Balance2=new class_datastore();
		$ldec_resultado=0;
		$ld_saldo_ganancia=0;
		$this->ls_activo=trim($this->la_empresa["activo"]);
		$this->ls_pasivo=trim($this->la_empresa["pasivo"]);
		$this->ls_resultado=trim($this->la_empresa["resultado"]);
		$this->ls_capital=trim($this->la_empresa["capital"]);
		$this->ls_orden_d=trim($this->la_empresa["orden_d"]);
		$this->ls_orden_h=trim($this->la_empresa["orden_h"]);
		$this->ls_ingreso=trim($this->la_empresa["ingreso"]);
		$this->ls_gastos =trim($this->la_empresa["gasto"]);
		$this->ls_cta_resultado = trim($this->la_empresa["c_resultad"]);
		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
              "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
              "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel ".
              " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mesaux),0)as T_Debe, ".
			  "                                             coalesce(sum(haber_mesaux),0) as T_Haber ".
              "                                      FROM   scg_saldos ".
              "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
              "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
              " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
              " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			  "       (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' OR ".
			  "        SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%' OR ".
			  "        SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') ".
              " ORDER BY  SC.sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
        $ld_saldo_ganancia=0;
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta=$row["sc_cuenta"];
		  $ls_denominacion=$row["denominacion"];
		  $ls_status=$row["status"];
		  $ls_rnivel=$row["rnivel"];
		  $ld_total_debe=$row["total_debe"];
		  $ld_total_haber=$row["total_haber"];
		  if($ls_status=="C")
		  {
    		$ls_nivel="4";		
		  }//if
		  else
		  {
    		$ls_nivel=$ls_rnivel;		
		  }//else
		  if($ls_nivel<=$ai_nivel)
		  {
			  $this->ds_Prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->ds_Prebalance->insertRow("denominacion",$ls_denominacion);
			  $this->ds_Prebalance->insertRow("status",$ls_status);
			  $this->ds_Prebalance->insertRow("nivel",$ls_nivel);
			  $this->ds_Prebalance->insertRow("rnivel",$ls_rnivel);
			  $this->ds_Prebalance->insertRow("total_debe",$ld_total_debe);
			  $this->ds_Prebalance->insertRow("total_haber",$ld_total_haber);
		      $lb_valido = true;
		  }//if
		}//while
	    $li=$this->ds_Prebalance->getRowCount("sc_cuenta");
		if($li==0)
		{
		  $lb_valido = false;
		  return false;
		}//if
	 } //else
	 $ld_saldo_i=0;		
		
	 if($lb_valido)
	 {
	   $lb_valido=$this->uf_scg_reporte_select_saldo_ingreso_BG($ad_fecfin,$this->ls_ingreso,$ld_saldo_i);
	 } 
     if($lb_valido)
	 {
       $ld_saldo_g=0;	 
	   $lb_valido=$this->uf_scg_reporte_select_saldo_gasto_BG($ad_fecfin,$this->ls_gastos,$ld_saldo_g);  
	 }//if
	 if($lb_valido)
	 {
	   $ld_saldo_ganancia=$ld_saldo_ganancia+($ld_saldo_i+$ld_saldo_g);
	 }//if
	 $la_sc_cuenta=array();
	 $la_denominacion=array();
	 $la_saldo=array();
	 for($i=1;$i<=$ai_nivel;$i++)
	 {
		 $la_sc_cuenta[$i]="";
		 $la_denominacion[$i]="";
		 $la_saldo[$i]=0;
	 }//for
	 $li_nro_reg=0;
     $ld_saldo_resultado=0;
	 $li_row=$this->ds_Prebalance->getRowCount("sc_cuenta");	
	 for($li_z=1;$li_z<=$li_row;$li_z++)
	 {
		$ls_sc_cuenta=$this->ds_Prebalance->getValue("sc_cuenta",$li_z);
		$ldec_debe=$this->ds_Prebalance->getValue("total_debe",$li_z);
		$ldec_haber=$this->ds_Prebalance->getValue("total_haber",$li_z);
		$li_nivel=$this->ds_Prebalance->getValue("nivel",$li_z);	
		$ls_denominacion=$this->ds_Prebalance->getValue("denominacion",$li_z);	
		$ls_tipo_cuenta=substr($ls_sc_cuenta,0,1);
	 	switch($ls_tipo_cuenta){
			case $this->ls_activo:
				$ls_orden=1;
				break;
			case $this->ls_pasivo:
				$ls_orden=2;
				break;
			case $this->ls_capital:
				$ls_orden=3;
				break;				
			case $this->ls_resultado:
				$ls_orden=4;
				break;
			case $this->ls_orden_d:
				$ls_orden=5;
				break;		
			case $this->ls_orden_h:
				$ls_orden=6;
				break;
			default:
				$ls_orden=7;		
		}
		$ldec_saldo=$ldec_debe-$ldec_haber;
		
		if( (($ls_tipo_cuenta==$this->ls_pasivo)||($ls_tipo_cuenta==$this->ls_resultado)||($ls_tipo_cuenta==$this->ls_capital))&&($li_nivel==1))
		{
			$ld_saldo_resultado=$ld_saldo_resultado+$ldec_saldo;
			//print $ld_saldo_resultado;
		}	
		
		if($li_nivel==4)	
		{
			$li_nro_reg=$li_nro_reg+1;
			$this->ds_Balance1->insertRow("orden",$ls_orden);
		    $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
		    $this->ds_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
		    $this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
			$this->ds_Balance1->insertRow("nivel",$li_nivel);
			$this->ds_Balance1->insertRow("saldo",$ldec_saldo);
		}
		else
		{
			if(empty($la_sc_cuenta[$li_nivel]))
			{
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ldec_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->ds_Balance1->insertRow("orden",$ls_orden);
			   $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
			   $this->ds_Balance1->insertRow("nivel",-$li_nivel);
			   $this->ds_Balance1->insertRow("saldo",$ldec_saldo);
			}
			else
			{
			   $this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,$ls_nivel,$la_sc_cuenta,$la_denominacion,$la_saldo); 
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ldec_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->ds_Balance1->insertRow("orden",$ls_orden);
			   $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
			   $this->ds_Balance1->insertRow("nivel",-$li_nivel);
			   $this->ds_Balance1->insertRow("saldo",$ldec_saldo);
			}
		}
		$ls_prev_nivel=$li_nivel;		
	 }					
	 $this->uf_scg_reporte_calcular_total_BG(&$li_nro_reg,$ls_prev_nivel,1,$la_sc_cuenta,$la_denominacion,$la_saldo); 			
	 $ld_saldo_resultado=($ld_saldo_resultado+$ld_saldo_ganancia);
	 /*if($ld_saldo_ganancia>0)
	 {
	 	$ld_saldo_resultado=$ld_saldo_resultado-$ld_saldo_ganancia;
	 }
	 else
	 {
	 	$ld_saldo_resultado=$ld_saldo_resultado+abs($ld_saldo_ganancia);
	 }	*/
	 $this->uf_scg_reporte_actualizar_resultado_BG($this->ls_cta_resultado,$ld_saldo_ganancia,$li_nro_reg,$ls_orden); 
	 
	 $li_total=$this->ds_Balance1->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_total;$li_i++)
	 {	
		  $ls_sc_cuenta=$this->ds_Balance1->data["sc_cuenta"][$li_i];
		  $ls_orden=$this->ds_Balance1->data["orden"][$li_i];
		  $li_nro_reg=$this->ds_Balance1->data["num_reg"][$li_i];
		  $ls_denominacion=$this->ds_Balance1->data["denominacion"][$li_i];
		  $ls_nivel=$this->ds_Balance1->data["nivel"][$li_i];
		  $ld_saldo=$this->ds_Balance1->data["saldo"][$li_i];
		  $li_pos=$this->ds_Prebalance->find("sc_cuenta",$ls_sc_cuenta);
		  if($li_pos>0)
		  { 
		    $ls_rnivel=$this->ds_Prebalance->data["rnivel"][$li_pos];
		  }
		  else
		  {
		    $ls_rnivel=0;
		  }
	      $ds_Balance2->insertRow("orden",$ls_orden);
	      $ds_Balance2->insertRow("num_reg",$li_nro_reg);
	      $ds_Balance2->insertRow("sc_cuenta",$ls_sc_cuenta);
	      $ds_Balance2->insertRow("denominacion",$ls_denominacion);
	      $ds_Balance2->insertRow("nivel",$ls_nivel);
	      $ds_Balance2->insertRow("saldo",$ld_saldo);
	      $ds_Balance2->insertRow("rnivel",$ls_rnivel);
		  $ds_Balance2->insertRow("total",$ld_saldo_resultado);
	 }//for
	 $li_tot=$ds_Balance2->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_tot;$li_i++)
	 { 
		  $ls_sc_cuenta=$ds_Balance2->data["sc_cuenta"][$li_i];
		  $ls_orden=$ds_Balance2->data["orden"][$li_i];
		  $li_nro_reg=$ds_Balance2->data["num_reg"][$li_i];
		  $ls_denominacion=$ds_Balance2->data["denominacion"][$li_i];
		  $ls_nivel=$ds_Balance2->data["nivel"][$li_i];
		  $ld_saldo=$ds_Balance2->data["saldo"][$li_i];
		  $ls_rnivel=$ds_Balance2->data["rnivel"][$li_i];
		  $ld_saldo_resultado=$ds_Balance2->data["total"][$li_i];
		  if($ls_rnivel<=$ai_nivel)
		  {
			  $this->ds_reporte->insertRow("orden",$ls_orden);
			  $this->ds_reporte->insertRow("num_reg",$li_nro_reg);
			  $this->ds_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->ds_reporte->insertRow("denominacion",$ls_denominacion);
			  $this->ds_reporte->insertRow("nivel",$ls_nivel);
			  $this->ds_reporte->insertRow("saldo",$ld_saldo);
			  $this->ds_reporte->insertRow("rnivel",$ls_rnivel);
			  $this->ds_reporte->insertRow("total",$ld_saldo_resultado);
		  }//if	  
	 }//for
     unset($this->ds_Prebalance);
     unset($this->ds_Balance1);
     unset($ds_Balance2);
	 return $lb_valido;  
	}

/****************************************************************************************************************************************/	
   function  uf_scg_reporte_select_saldo_ingreso_BG($adt_fecini,$ai_ingreso,&$ad_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_ingreso_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
     //              	    $ai_ingreso  // numero de la cuenta de ingraso 
	 //                     $ad_saldo  //  total saldo (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT COALESCE(sum(SD.debe_mesaux-SD.haber_mesaux),0) as saldo ".
             " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			 "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_ingreso."%') ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_ingreso_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $ad_saldo=$row["saldo"];
		}
		$this->io_sql->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_obtener_saldo_ingreso
/****************************************************************************************************************************************/	

 function  uf_scg_reporte_select_saldo_gasto_BG($adt_fecini,$ai_gasto,&$ad_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_gasto_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde 
     //              	    $ai_gasto  // numero de la cuenta de gasto
	 //                     $ad_saldo  //  total saldo (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 
	 $ls_sql=" SELECT COALESCE(sum(SD.debe_mesaux-SD.haber_mesaux),0) as saldo ".
             " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			 "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_gasto."%') ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto_BG ".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $ad_saldo=$row["saldo"];
		}
		$this->io_sql->free_result($rs_data);
	 } 
	 return $lb_valido;   
   }//fin uf_scg_reporte_select_saldo_gasto_BG

   function  uf_scg_reporte_calcular_total_BG(&$ai_nro_regi,$as_prev_nivel,$as_nivel,&$aa_sc_cuenta,$aa_denominacion,$aa_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_total_BG
	 //         Access :	private
	 //     Argumentos :    $as_prev_nivel  // nivel de la cuenta anterior
     //              	    $as_nivel  // nivel de  la cuenta 
	 //                     $ai_nro_regi  //  numero de registro (referencia)
	 //                     $aa_sc_cuenta  // arreglo de cuentas (referencia)
	 //                     $aa_denominacion // arreglo de denominacion         
	 //                     $aa_saldo // arreglo de saldo         
     //	       Returns :	Retorna true o false si se realizo el calculo del total para el reporte
	 //	   Description :	Metodo que genera un monto total para la cuenta del balance general 
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    08/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $i=$as_prev_nivel-1;
	 $x=$as_nivel-1;
	 if($i>$x)
	 {
		  $ls_tipo_cuenta=substr($aa_sc_cuenta[$i],0,1);
		  if($ls_tipo_cuenta==$this->ls_activo) {	$ls_orden="1"; }	
		  if($ls_tipo_cuenta==$this->ls_pasivo) {	$ls_orden="2"; }	
		  if($ls_tipo_cuenta==$this->ls_capital) { $ls_orden="3"; }	
		  if($ls_tipo_cuenta==$this->ls_resultado) { $ls_orden="4"; }	
		  if($ls_tipo_cuenta==$this->ls_orden_d) { $ls_orden="5"; }
		  if($ls_tipo_cuenta==$this->ls_orden_h){ $ls_orden="6"; }
		  else{$ls_orden="7";}
          if(!empty($aa_sc_cuenta[$i]))
		  {
	 	    $ai_nro_regi=$ai_nro_regi+1;
		    $this->ds_Balance1->insertRow("orden",$ls_orden);
		    $this->ds_Balance1->insertRow("num_reg",$ai_nro_regi);
		    $this->ds_Balance1->insertRow("sc_cuenta",$aa_sc_cuenta[$i]);
		    $this->ds_Balance1->insertRow("denominacion","Total ".$aa_denominacion[$i]);
		    $this->ds_Balance1->insertRow("nivel",$i);
		    $this->ds_Balance1->insertRow("saldo",$aa_saldo[$i]);
			$aa_sc_cuenta[$i]="";
			$i--;
		  }//if
	 }//if
    }//uf_scg_reporte_calcular_total_BG
	/****************************************************************************************************************************************/	

   function  uf_scg_reporte_actualizar_resultado_BG($ai_c_resultad,$ad_saldo_ganancia,$ai_nro_reg,$as_orden) 
   {				 
	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_actualizar_resultado_BG
	 //         Access :	private
	 //     Argumentos :    $ai_c_resultad  // cuenta de resultado
     //              	    $ad_saldo_ganancia  // saldo 
     //              	    $as_sc_cuenta  // cuenta
     //	       Returns :	Retorna true o false si se realizo el calculo para el reporte
	 //	   Description :	Metodo que genera un monto actualizado de la cuenta del resultado
	 //     Creado por :    Ing. Yozelin Barragan
	 // Fecha Creacion:    08/05/2006          Fecha ltima Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $ls_next_cuenta=$ai_c_resultad;
	 $ld_saldo=0;
	 $ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_next_cuenta);
	 while($ls_nivel>=1)
	 {
		  $li_pos=$this->ds_Balance1->find("sc_cuenta",$ls_next_cuenta);
		  if($li_pos>0)
		  {
			  $ld_saldo=$this->ds_Balance1->getValue("saldo",$li_pos);
			  /*if($ad_saldo_ganancia>0)	
			  { 
			  	$ld_saldo=$ld_saldo-$ad_saldo_ganancia;
			  }
			  else
			  {
			   $ld_saldo=$ld_saldo+abs($ad_saldo_ganancia);
			  }*/
			  $ld_saldo=$ld_saldo+$ad_saldo_ganancia;
			  $this->ds_Balance1->updateRow("saldo",$ld_saldo,$li_pos);
		  }	 
		  else
		  {
                $lb_valido=$this->uf_select_denominacion($ls_next_cuenta,$ls_denominacion);			
			    if($lb_valido)
				{
                   $li_nro_reg=$ai_nro_reg+1;
				   $this->ds_Balance1->insertRow("orden",$as_orden);
				   $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
				   $this->ds_Balance1->insertRow("sc_cuenta",$ls_next_cuenta);
				   $this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
				   $this->ds_Balance1->insertRow("nivel",$ls_nivel);
				   $this->ds_Balance1->insertRow("saldo",$ad_saldo_ganancia);				  
				}   
		  } 													
		  if($ls_nivel==1)
		  {
			 return;
		  }//if
		  $ls_next_cuenta=$this->int_scg->uf_scg_next_cuenta_nivel($ls_next_cuenta);
		  $ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_next_cuenta);
	 }//while
   }//uf_scg_reporte_actualizar_resultado_BG
   
   function uf_select_denominacion($as_sc_cuenta,&$as_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_denominacion 
	//	     Arguments:  $as_sc_cuenta  // codigo de la cuenta
	//                   $as_denominacion  // denominacion de la cuenta (referencia)
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca la denominacion de la cuenta
	//     Creado por :  Ing. Yozelin Barragan
	// Fecha Creacion :  14/08/2006                      Fecha ltima Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
    $ls_codemp = $this->la_empresa["codemp"];
	$ls_sql = "SELECT denominacion FROM scg_cuentas WHERE sc_cuenta='".$as_sc_cuenta."' AND codemp='".$ls_codemp."' ";
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
		$this->is_msg_error="Error en consulta metodo uf_select_denominacion ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
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
 }//uf_select_denominacion
   
}
?>