<?php
class sigesp_scg_class_comparados
{
	var $la_empresa;
	var $io_fun;
	var $io_sql;
	var $io_sql_aux;
	var $io_msg;
	var $int_scg;
	var $ds_analitico;
	var $ds_reporte;
	var $ds_cab;
	var $ds_egresos;
	var $ds_prebalance;
	var $ds_balance1;
	var $ds_cuentas;
	var $ia_niveles;
	var $ds_programado;
	var $ldec_total_resultado;
	var $io_fecha;
	var $ls_gestor;
	var $li_mes_prox;
	var $la_cuentas;
	var $int_spi;
	var $int_spg;
	var $ds_currep;
	var $ds_reporte2;
	function sigesp_scg_class_comparados()
	{
		$this->io_fun = new class_funciones();
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->io_sql= new class_sql($this->con);
		$this->io_sql_aux= new class_sql($this->con);
		$this->io_msg= new class_mensajes();		
		$this->io_fecha=new class_fecha();
		$this->la_empresa=$_SESSION["la_empresa"];
		$this->ds_analitico=new class_datastore();
		$this->ds_reporte=new class_datastore();
		$this->ds_programado=new class_datastore();
		$this->ds_cab=new class_datastore();
		$this->ds_egresos=new class_datastore();
		$this->ds_prebalance=new class_datastore();
		$this->ds_balance1=new class_datastore();
		$this->ds_cuentas=new class_datastore();
		$this->ds_currep=new class_datastore();
		$this->ds_reporte=new class_datastore();
		$this->ds_reporte2=new class_datastore();
		$this->int_scg=new class_sigesp_int_scg();
		$this->ls_gestor = $_SESSION["ls_gestor"];
		$this->int_spi=new class_sigesp_int_spi();
		$this->int_spg=new class_sigesp_int_spg();
		$this->ia_niveles=array();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////************************************BALANCE GENERAL*************************************************////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_balance_general_comparado($ad_fecini,$ad_fecfin,$li_sel)
	{
		$ldec_s1=0;	$ldec_s2=0;	$ldec_s3=0;	$ldec_s4=0;	$ldec_s5=0;	$ldec_s6=0; $ldec_s7=0;
		$ls_codemp=$this->la_empresa["codemp"];		
		$li_maxnivel   = 4;		
		$ldec_totresult= 0;			
		$ls_cuenta_resultado = $this->la_empresa["c_resultad"];
		$ldec_totresultado = $this->uf_preparar_resumen($ls_codemp,$ad_fecini,$ad_fecfin);	
		$this->ldec_total_resultado=$ldec_totresultado;		
		$this->uf_init_array();			
		$li_totrows=$this->ds_cuentas->getRowCount("sc_cuenta");
		for($li_i=1;$li_i<=$li_totrows;$li_i++)
		{
			$li_tipo=$this->ds_cuentas->getValue("tipo",$li_i);
			if($li_tipo==1)
			{
				$ls_cuenta=$this->ds_cuentas->getValue("sc_cuenta",$li_i);
				$this->uf_procesar_cuenta($ls_cuenta,$ad_fecini,$ad_fecfin,$li_sel);
			}
		}		
		$ldec_resultado=$this->uf_calcular_resultado($ad_fecfin,$li_maxnivel);
		$li_row=$this->ds_cuentas->findValues(array('sc_cuenta'=>substr($ls_cuenta_resultado,0,9)),'sc_cuenta');
		$this->ds_cuentas->updateRow("resultado",$ldec_resultado,$li_row);		
		$this->ds_cuentas->updateRow("s_ant",$ldec_resultado,$li_row);		
		$li_total=$this->ds_cuentas->getRowCount("sc_cuenta");
		$ldec_monto_aux=0;
		$ldec_aux_1=0;		
		$ldec_aux_2=0;
		$ldec_aux_3=0;
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_cuenta=$this->ds_cuentas->getValue("sc_cuenta",$li_i);	
			$ldec_prog_varia=$this->ds_cuentas->getValue("prg_varia",$li_i);	
			$ldec_prog_acum =$this->ds_cuentas->getValue("prg_acum",$li_i);	
			$ldec_s_ant=$this->ds_cuentas->getValue("s_ant",$li_i);	
			$ldec_saldo_ant=$this->ds_cuentas->getValue("saldo_ant",$li_i);	
			$ldec_repproxmes=$this->ds_cuentas->getValue("repproxmes",$li_i);
			$li_nivel=$this->ds_cuentas->getValue("nivel",$li_i);
			if(substr($ls_cuenta,0,1)=='3')
			{
				//print "<br>CUENTA>>>>".$ls_cuenta." nivel >>>>".$li_nivel."monto".$ldec_s_ant."<br>";
				if(($li_nivel<>1)&&(substr($ls_cuenta,0,9)<>'325000000'))// se tuvo que cambiar por que no cuadraba
				{
					$ldec_monto_aux=$ldec_monto_aux+$ldec_s_ant;	
					$ldec_aux_1=$ldec_aux_1+$ldec_saldo_ant;
					$ldec_aux_2=$ldec_aux_2+$ldec_prog_varia;
					$ldec_aux_3=$ldec_aux_3+$ldec_prog_acum;
					//print "<br>CUENTA>>>>".$ls_cuenta." **MONTO 1".$ldec_monto_aux." **MONTO 2".$ldec_aux_1." **MONTO 3".$ldec_aux_2." **MONTO 4".$ldec_aux_3."<br>";
				}
			}					
		}
		$li_row=$this->ds_cuentas->findValues(array('sc_cuenta'=>'300000000'),'sc_cuenta');
		$this->ds_cuentas->updateRow("s_ant",$ldec_monto_aux,$li_row);		
		$this->ds_cuentas->updateRow("saldo_ant",$ldec_aux_1,$li_row);		
		$this->ds_cuentas->updateRow("prog_varia",$ldec_aux_2,$li_row);		
		$this->ds_cuentas->updateRow("prog_acum",$ldec_aux_3,$li_row);	
				//print "<br>CUENTA>>>>".$ls_cuenta." **MONTO 1".$ldec_monto_aux." **MONTO 2".$ldec_aux_1." **MONTO 3".$ldec_aux_2." **MONTO 4".$ldec_aux_3."<br>";
		for($li_i=1;$li_i<=$li_total;$li_i++)
		{
			$ls_cuenta=$this->ds_cuentas->getValue("sc_cuenta",$li_i);	
			$ldec_prog_varia=$this->ds_cuentas->getValue("prg_varia",$li_i);	
			$ldec_prog_acum =$this->ds_cuentas->getValue("prg_acum",$li_i);	
			$ldec_s_ant=$this->ds_cuentas->getValue("s_ant",$li_i);	
			$ldec_saldo_ant=$this->ds_cuentas->getValue("saldo_ant",$li_i);	
			$ldec_repproxmes=$this->ds_cuentas->getValue("repproxmes",$li_i);
			if((substr($ls_cuenta,0,9)=='200000000'	)||(substr($ls_cuenta,0,9)=='300000000'))
			{
				$ldec_s1=$ldec_s1+$ldec_prog_varia;
				$ldec_s2=$ldec_s2+$ldec_prog_acum;				
				$ldec_s3=$ldec_s3+($ldec_s_ant+$ldec_saldo_ant);
				$ldec_s4=$ldec_s4+($ldec_s_ant+$ldec_saldo_ant);
				$ldec_s5=$ldec_s5+($ldec_saldo_ant-$ldec_prog_varia);				
				$ldec_s6=$ldec_s6+($ldec_s_ant+$ldec_saldo_ant-$ldec_prog_acum);				
				$ldec_s7=$ldec_s7+$ldec_repproxmes;
			}
		}				
		$li_row=$this->ds_cuentas->findValues(array('tipo'=>'999'),'sc_cuenta');
		$this->ds_cuentas->updateRow("s_1",$ldec_s1,$li_row);
		$this->ds_cuentas->updateRow("s_2",$ldec_s2,$li_row);
		$this->ds_cuentas->updateRow("s_3",$ldec_s3,$li_row);		
		$this->ds_cuentas->updateRow("s_4",$ldec_s4,$li_row);
		$this->ds_cuentas->updateRow("s_5",$ldec_s5,$li_row);
		$this->ds_cuentas->updateRow("s_6",$ldec_s6,$li_row);
		$this->ds_cuentas->updateRow("s_7",$ldec_s7,$li_row);
		return true;
	}
	
	
	function uf_preparar_resumen($ls_codemp,$adt_fecdesde,$adt_fechasta)
	{
		$ls_cuenta_resultado="";$ldec_ganancia="";
		$li_maxnivel=3;
		$li_activo=$this->la_empresa["activo"];
		$li_pasivo=$this->la_empresa["pasivo"];
		$li_resultado=$this->la_empresa["resultado"];
		$li_capital=$this->la_empresa["capital"];		
		$li_orden_d=$this->la_empresa["orden_d"];
		$li_orden_h=$this->la_empresa["orden_h"];		
		$li_ingreso=$this->la_empresa["ingreso"];
		$li_gastos =$this->la_empresa["gasto"];
		$ds_balance2=new class_datastore();
		$ls_cuenta_resultado = $this->la_empresa["c_resultad"];		
		if($_SESSION["ls_gestor"]=='INFORMIX')
	    {
		 $ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, 
                   (SELECT  case sum(debe_mes) when null then 0 else sum(debe_mes) end  FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' AND sc_cuenta=SC.sc_cuenta GROUP BY sc_cuenta) as total_debe,
                   (SELECT case sum(haber_mes) when null then 0 else  sum(haber_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' AND sc_cuenta=SC.sc_cuenta GROUP BY sc_cuenta)  as total_haber,
                   0 as nivel FROM scg_cuentas SC 
                   WHERE (SC.sc_cuenta like '".$li_activo."%' 
				       OR SC.sc_cuenta like '".$li_pasivo."%' 
					   OR SC.sc_cuenta like '".$li_resultado."%' 
					   OR SC.sc_cuenta like '".$li_capital."%'
					   OR SC.sc_cuenta like '".$li_orden_d."%' 
					   OR SC.sc_cuenta like '".$li_orden_h."%') 
                   AND SC.nivel<=".$li_maxnivel. 
                   "ORDER BY SC.sc_cuenta";
		}
		if($_SESSION["ls_gestor"]=='POSTGRES')
	    {
	    	$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
                    "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
                    "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel ".
                    " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			        "                                             coalesce(sum(haber_mes),0) as T_Haber ".
                    "                                      FROM   scg_saldos ".
                    "                                      WHERE  codemp='".$ls_codemp."' AND fecsal between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' ".
                    "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
                    " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
                    " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			        "       (SC.sc_cuenta like '".$li_activo."%' OR SC.sc_cuenta like '".$li_pasivo."%' OR ".
			        "        SC.sc_cuenta like '".$li_resultado."%' OR  SC.sc_cuenta like '".$li_capital."%' OR ".
			        "        SC.sc_cuenta like '".$li_orden_d."%' OR SC.sc_cuenta like '".$li_orden_h."%') AND SC.nivel<=".$li_maxnivel.
                    " ORDER BY  SC.sc_cuenta "; 
		}
		else
		{				
		  $ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
              "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
              "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel ".
              " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
              "                                      FROM   scg_saldos ".
              "                                      WHERE  codemp='".$ls_codemp."' AND fecsal between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' ".
              "                                      GROUP BY sc_cuenta) curSaldo ".
              " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
              " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			  "       (SC.sc_cuenta like '".$li_activo."%' OR SC.sc_cuenta like '".$li_pasivo."%' OR ".
			  "        SC.sc_cuenta like '".$li_resultado."%' OR  SC.sc_cuenta like '".$li_capital."%' OR ".
			  "        SC.sc_cuenta like '".$li_orden_d."%' OR SC.sc_cuenta like '".$li_orden_h."%') AND SC.nivel<=".$li_maxnivel.
              " ORDER BY  SC.sc_cuenta "; ///print $ls_sql."<BR>";	
		}	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		//print $this->is_msg_error;
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
		  $this->ds_prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);
		  $this->ds_prebalance->insertRow("denominacion",$ls_denominacion);
		  $this->ds_prebalance->insertRow("status",$ls_status);
		  $this->ds_prebalance->insertRow("nivel",$ls_nivel);
		  $this->ds_prebalance->insertRow("rnivel",$ls_rnivel);
		  $this->ds_prebalance->insertRow("total_debe",$ld_total_debe);
		  $this->ds_prebalance->insertRow("total_haber",$ld_total_haber);
		  $lb_valido = true;
		}//while
	    $li=$this->ds_prebalance->getRowCount("sc_cuenta");
		if($li==0)
		{
		  $lb_valido = false;
		  return false;
		}//if
	 } //else
	 $ld_saldo_i=0;
	 if($lb_valido)
	 {
	   $lb_valido=$this->uf_scg_reporte_select_saldo_ingreso_BG($adt_fecdesde,$adt_fechasta,$li_ingreso,$ld_saldo_i);
	 } 
     if($lb_valido)
	 {
       $ld_saldo_g=0;	 
	   $lb_valido=$this->uf_scg_reporte_select_saldo_gasto_BG($adt_fecdesde,$adt_fechasta,$li_gastos,$ld_saldo_g);  
	 }//if
	 if($lb_valido)
	 {
	   $ld_saldo_ganancia=$ld_saldo_ganancia+($ld_saldo_i+$ld_saldo_g);
	 }//if	 
     $la_sc_cuenta=array();
	 $la_denominacion=array();
	 $la_saldo=array();
	 $li_nivel=3;
	 for($i=1;$i<=$li_nivel;$i++)
	 {
		 $la_sc_cuenta[$i]="";
		 $la_denominacion[$i]="";
		 $la_saldo[$i]=0;
	 }//for				
		
	 $li_nro_reg=0;
     $ld_saldo_resultado=0;
	 $li_row=$this->ds_prebalance->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_row;$li_i++)
	 {
		  $ls_sc_cuenta=$this->ds_prebalance->getValue("sc_cuenta",$li_i);
		  $ls_status=$this->ds_prebalance->getValue("status",$li_i);
		  $ls_denominacion=$this->ds_prebalance->getValue("denominacion",$li_i);
		  $ls_rnivel=$this->ds_prebalance->getValue("rnivel",$li_i);
		  $ld_total_debe=$this->ds_prebalance->getValue("total_debe",$li_i);
		  $ld_total_haber=$this->ds_prebalance->getValue("total_haber",$li_i); 
		  $ls_nivel=$this->ds_prebalance->getValue("nivel",$li_i); 
		  $ls_tipo_cuenta=substr($ls_sc_cuenta,0,1);
		  $ls_orden="7";
		  if($ls_tipo_cuenta==$li_activo  ) { $ls_orden="1"; }	
		  if($ls_tipo_cuenta==$li_pasivo  ) { $ls_orden="2"; }	
		  if($ls_tipo_cuenta==$li_capital ) { $ls_orden="3"; }	
		  if($ls_tipo_cuenta==$li_resultado){ $ls_orden="4"; } 	
		  if($ls_tipo_cuenta==$li_orden_d ) { $ls_orden="5"; }
		  if($ls_tipo_cuenta==$li_orden_h ) { $ls_orden="6"; }	
		
		  $ld_saldo=abs($ld_total_debe-$ld_total_haber);
		  if((($ls_tipo_cuenta==$li_pasivo)||($ls_tipo_cuenta==$li_resultado)||($ls_tipo_cuenta==$li_capital))&&($ls_nivel==1))
		  {
			  $ld_saldo_resultado=$ld_saldo_resultado+$ld_saldo;
		  }//if
		  if($ls_nivel==4)
		  {
		    $li_nro_reg=$li_nro_reg+1; 
		    $this->ds_balance1->insertRow("orden",$ls_orden);
		    $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
		    $this->ds_balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
		    $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
			$this->ds_balance1->insertRow("nivel",$ls_nivel);
			$this->ds_balance1->insertRow("saldo",$ld_saldo);
		  }//if
		  else
		  {
		    if (empty($la_sc_cuenta[$ls_nivel]))
			{
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ld_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->ds_balance1->insertRow("orden",$ls_orden);
			   $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
			   $this->ds_balance1->insertRow("nivel",-$ls_nivel);
			   $this->ds_balance1->insertRow("saldo",$ld_saldo);
			}//if
            else
			{
			   $this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,$ls_nivel,$la_sc_cuenta,$la_denominacion,$la_saldo,$li_activo,$li_pasivo,$li_capital,$li_resultado,$li_orden_d,$li_orden_h); 
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ld_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->ds_balance1->insertRow("orden",$ls_orden);
			   $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
			   $this->ds_balance1->insertRow("nivel",-$ls_nivel);
			   $this->ds_balance1->insertRow("saldo",$ld_saldo);
			}//else 			
          $ls_prev_nivel=$ls_nivel;		 
		}//else
	 }//for
	 $this->uf_scg_reporte_actualizar_resultado_BG($ls_cuenta_resultado,abs($ld_saldo_ganancia),$li_nro_reg,$ls_orden); 
	 if($ld_saldo_ganancia>0)	
	  { 
		$ld_saldo_resultado=$ld_saldo_resultado-$ld_saldo_ganancia;
	  }
	  else
	  {
	   $ld_saldo_resultado=$ld_saldo_resultado+abs($ld_saldo_ganancia);
	  }
	 $li_total=$this->ds_balance1->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_total;$li_i++)
	 {	
		  $ls_sc_cuenta=$this->ds_balance1->data["sc_cuenta"][$li_i];
		  $ls_orden=$this->ds_balance1->data["orden"][$li_i];
		  $li_nro_reg=$this->ds_balance1->data["num_reg"][$li_i];
		  $ls_denominacion=$this->ds_balance1->data["denominacion"][$li_i];
		  $ls_nivel=$this->ds_balance1->data["nivel"][$li_i];
		  $ld_saldo=$this->ds_balance1->data["saldo"][$li_i];
		  $li_pos=$this->ds_prebalance->find("sc_cuenta",$ls_sc_cuenta);
		  if($li_pos>0)
		  { 
		    $ls_rnivel=$this->ds_prebalance->data["rnivel"][$li_pos];
		  }
		  else
		  {
		    $ls_rnivel=0;
		  }
	      $ds_balance2->insertRow("orden",$ls_orden);
	      $ds_balance2->insertRow("num_reg",$li_nro_reg);
	      $ds_balance2->insertRow("sc_cuenta",$ls_sc_cuenta);
	      $ds_balance2->insertRow("denominacion",$ls_denominacion);
	      $ds_balance2->insertRow("nivel",$ls_nivel);
	      $ds_balance2->insertRow("saldo",abs($ld_saldo));
	      $ds_balance2->insertRow("rnivel",$ls_rnivel);
		  $ds_balance2->insertRow("total",abs($ld_saldo_resultado));
	 }//for
	 $li_tot=$ds_balance2->getRowCount("sc_cuenta");
	 
	/* for($li_i=1;$li_i<=$li_tot;$li_i++)
	 { 
		  $ls_sc_cuenta   = $ds_balance2->data["sc_cuenta"][$li_i];
		  $ls_orden       = $ds_balance2->data["orden"][$li_i];
		  $li_nro_reg     = $ds_balance2->data["num_reg"][$li_i];
		  $ls_denominacion= $ds_balance2->data["denominacion"][$li_i];
		  $ls_nivel       = $ds_balance2->data["nivel"][$li_i];
		  $ld_saldo       = $ds_balance2->data["saldo"][$li_i];
		  $ls_rnivel      = $ds_balance2->data["rnivel"][$li_i];
		  $ld_saldo_resultado=$ds_balance2->data["total"][$li_i];
		  if($ls_rnivel<=$li_maxnivel)
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
	 }//for*/
     $this->ds_prebalance->reset_ds();
     $this->ds_balance1->reset_ds();
     $ds_balance2->reset_ds();
	 return $ld_saldo_resultado;  		
	
}

 function  uf_scg_reporte_select_saldo_ingreso_BG($adt_fecdesde,$adt_fechasta,$ai_ingreso,&$ad_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_ingreso_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecdesde  =>fecha inicio busqueda
	 //						$adt_fechasta  =>fecha final  busqueda 
     //              	    $ai_ingreso  // numero de la cuenta de ingraso 
	 //                     $ad_saldo  //  total saldo (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Nelson Barraez
	 // Fecha Creaci? :    02/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT case sum(SD.debe_mes-SD.haber_mes) when null then 0 else sum(SD.debe_mes-SD.haber_mes) end as saldo ".
             " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND ".
			 "        SD.fecsal <= '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' AND ".
			 "       (SC.sc_cuenta like '".$ai_ingreso."%') AND SC.nivel=1 ".
			 " GROUP BY SD.sc_cuenta ";		
		//	 print 	 $ls_sql;
			  //le quite esto el 06-12-2006  -> between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_ingreso_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido = false;
		print $this->is_msg_error;
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

	 function  uf_scg_reporte_select_saldo_gasto_BG($adt_fecdesde,$adt_fechasta,$ai_gasto,&$ad_saldo) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_gasto_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecdesde  =>fecha inicio busqueda
	 //						$adt_fechasta  =>fecha final  busqueda 
     //              	    $ai_gasto  // numero de la cuenta de gasto
	 //                     $ad_saldo  //  total saldo (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado  
	 //     Creado por :    Ing. Nelson Barraez
	 // Fecha Creaci? :    02/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 
	 $ls_sql=" SELECT case sum(SD.debe_mes-SD.haber_mes) when null then 0 else sum(SD.debe_mes-SD.haber_mes) end as saldo 
               FROM   scg_cuentas SC, scg_saldos SD 
               WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND 
			          SD.fecsal <= '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' AND (SC.sc_cuenta like '".$ai_gasto."%') AND SC.nivel=1
			   GROUP BY SD.sc_cuenta ";
			   //le quite esto el 06-12-2006  -> between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND 
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
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
/****************************************************************************************************************************************/	

/****************************************************************************************************************************************/	
   function  uf_scg_reporte_calcular_total_BG(&$ai_nro_regi,$as_prev_nivel,$as_nivel,&$aa_sc_cuenta,$aa_denominacion,$aa_saldo,
                                              $ai_activo,$ai_pasivo,$ai_capital,$ai_resultado,$ai_orden_d,$ai_orden_h) 
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
	 //     Creado por :    Ing. Nelson Barraez
	 // Fecha Creacion :    08/05/2006          Fecha ltima Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $i=$as_prev_nivel-1;
	 $x=$as_nivel-1;
	 if($i>$x)
	 {
		  $ls_tipo_cuenta=substr($aa_sc_cuenta[$i],0,1);
		  if($ls_tipo_cuenta==$ai_activo) {	$ls_orden="1"; }	
		  if($ls_tipo_cuenta==$ai_pasivo) {	$ls_orden="2"; }	
		  if($ls_tipo_cuenta==$ai_capital) { $ls_orden="3"; }	
		  if($ls_tipo_cuenta==$ai_resultado) { $ls_orden="4"; }	
		  if($ls_tipo_cuenta==$ai_orden_d) { $ls_orden="5"; }
		  if($ls_tipo_cuenta== $ai_orden_h){ $ls_orden="6"; }
		  else{$ls_orden="7";}
          if(!empty($aa_sc_cuenta[$i]))
		  {
	 	    $ai_nro_regi=$ai_nro_regi+1;
		    $this->ds_balance1->insertRow("orden",$ls_orden);
		    $this->ds_balance1->insertRow("num_reg",$ai_nro_regi);
		    $this->ds_balance1->insertRow("sc_cuenta",$aa_sc_cuenta[$i]);
		    $this->ds_balance1->insertRow("denominacion","Total ".$aa_denominacion[$i]);
		    $this->ds_balance1->insertRow("nivel",$i);
		    $this->ds_balance1->insertRow("saldo",$aa_saldo[$i]);
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
	 //     Creado por :    Ing. NElson Barraez.
	 // Fecha Creaci? :    08/12/2006          Fecha ltima Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $ls_next_cuenta=$ai_c_resultad;
	 $ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_next_cuenta);
	 while($ls_nivel>=1)
	 {
		  $li_pos=$this->ds_balance1->find("sc_cuenta",$ls_next_cuenta);
		  if($li_pos>0)
		  {
			  $ld_saldo=$this->ds_balance1->getValue("saldo",$li_pos);	
			  if($ad_saldo_ganancia>0)	
			  { 
			  	$ld_saldo=$ld_saldo-$ad_saldo_ganancia;
			  }
			  else
			  {
			   $ld_saldo=$ld_saldo+abs($ad_saldo_ganancia);
			  }
			  $this->ds_balance1->updateRow("saldo",$ld_saldo,$li_pos);	
		  }	 
		  else
		  {
                $lb_valido=$this->uf_select_denominacion($ls_next_cuenta,$ls_denominacion);			
			    if($lb_valido)
				{
                   $li_nro_reg=$ai_nro_reg+1;
				   $this->ds_balance1->insertRow("orden",$as_orden);
				   $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
				   $this->ds_balance1->insertRow("sc_cuenta",$ls_next_cuenta);
				   $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
				   $this->ds_balance1->insertRow("nivel",$ls_nivel);
				   $this->ds_balance1->insertRow("saldo",$ad_saldo_ganancia);
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
/****************************************************************************************************************************************/	
	
/****************************************************************************************************************************************/
	function uf_init_array()
	{
		$ls_formcont=$this->la_empresa["formcont"];
		//$li_len=strlen(str_replace($this->la_empresa["formcont"],'-',''));
		$la_cuentas[1]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '100000000');
		$la_cuentas[2]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '110000000');
		$la_cuentas[3]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '111000000');
		$la_cuentas[4]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '111010100');
		$la_cuentas[5]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '111010201');
		$la_cuentas[6]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '111010202');
		$la_cuentas[7]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '111010203');
		$la_cuentas[8]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '111020000');
		$la_cuentas[9]  =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112000000');
		$la_cuentas[10] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112010000');
		$la_cuentas[11] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112020000');
		$la_cuentas[12] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112030000');
		$la_cuentas[13] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224010100');
		$la_cuentas[14] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112040000');
		$la_cuentas[15] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112050000');
		$la_cuentas[16] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112060000');
		$la_cuentas[17] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112070000');
		$la_cuentas[18] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112080000');
		$la_cuentas[19] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112090000');
		$la_cuentas[20] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112100000');
		$la_cuentas[21] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112110000');
		$la_cuentas[22] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '112190000');
		$la_cuentas[23] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '113000000');
		$la_cuentas[24] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '113010000');
		$la_cuentas[25] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '113020000');
		$la_cuentas[26] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '113030000');		
		$la_cuentas[27] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '113040000');
		$la_cuentas[28] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '113050000');
		$la_cuentas[29] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '119990000');			
		$la_cuentas[30] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '120000000');	
		$la_cuentas[31] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121000000');
		$la_cuentas[32] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121010000');
		$la_cuentas[33] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121020000');
		$la_cuentas[34] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '121030000');
		$la_cuentas[35] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '122000000');
		$la_cuentas[36] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123000000');
		$la_cuentas[37] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225010000');
		$la_cuentas[38] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123050100');
		$la_cuentas[39] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '123050200');
		$la_cuentas[40] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '124000000');
		$la_cuentas[41] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '225020000');
		$la_cuentas[42] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '129000000');		
		$la_cuentas[43] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '200000000');
		$la_cuentas[44] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '210000000');
		$la_cuentas[45] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '211000000');
		$la_cuentas[46] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '211040000');
		$la_cuentas[47] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '212000000');
		$la_cuentas[48] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '212020000');
		$la_cuentas[49] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '212040000');
		$la_cuentas[50] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '219000000');
		$la_cuentas[51] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '220000000');
		$la_cuentas[52] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '221000000');
		$la_cuentas[53] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '222000000');
		$la_cuentas[54] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '222020000');
		$la_cuentas[55] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '222040000');
		$la_cuentas[56] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224000000');
		$la_cuentas[57] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224010000');
		$la_cuentas[58] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224010200');
		$la_cuentas[59] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224010300');
		$la_cuentas[60] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224010400');
		$la_cuentas[61] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224019900');
		$la_cuentas[62] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '224020000');
		$la_cuentas[63] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '229090000');
		$la_cuentas[64] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '300000000');
		$la_cuentas[65] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '321000000');
		$la_cuentas[66] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '322000000');
		$la_cuentas[67] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '322010000');
		$la_cuentas[68] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '322020000');
		$la_cuentas[69] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '322030000');
		$la_cuentas[70] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '323000000');
		$la_cuentas[71] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '323010000');
		$la_cuentas[72] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '325000000');
		$la_cuentas[73] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '325010000');
		$la_cuentas[74] =$this->int_scg->uf_pad_scg_cuenta( $ls_formcont, '325020000');

		
		for( $li_pos=1;$li_pos<=74;$li_pos++)
		{
			$ls_cuenta=$la_cuentas[$li_pos];
			$ls_cuenta = substr($ls_cuenta,0,9);

			$ls_sql="SELECT denominacion 
					 FROM   sigesp_plan_unico 
					 WHERE  sc_cuenta = '".$ls_cuenta."'";
					
		   $rs_data=$this->io_sql->select($ls_sql); 
		   if($rs_data===false)	
		   {
				$this->io_msg->message("Error al seleccionar cuenta metodo uf_init_array ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
				print "Error en init array";
				return false;   	
		   }
		   else
		   {
		   		if($row=$this->io_sql->fetch_row($rs_data))
				{
					$ls_denominacion=$row["denominacion"];
				}
				else
				{
					$ls_denominacion="";
				}			
				$this->ds_cuentas->insertRow("sc_cuenta",$ls_cuenta);
				$this->ds_cuentas->insertRow("denominacion",$ls_denominacion);
				$this->ds_cuentas->insertRow("tipo",1);
				$this->ds_cuentas->insertRow("prg_varia",0);
				$this->ds_cuentas->insertRow("prg_acum",0);
				$this->ds_cuentas->insertRow("saldo_ant",0);
				$this->ds_cuentas->insertRow("diferencia",0);
				$this->ds_cuentas->insertRow("p1",0);
				$this->ds_cuentas->insertRow("p2",0);
				$this->ds_cuentas->insertRow("var_prog",0);
				$this->ds_cuentas->insertRow("var_ejec",0);
				$this->ds_cuentas->insertRow("repproxmes",0);				
				$this->ds_cuentas->insertRow("nivel",'');
				$this->ds_cuentas->insertRow("s_ant",0);
				$this->ds_cuentas->insertRow("s_1",0);
				$this->ds_cuentas->insertRow("s_2",0);
				$this->ds_cuentas->insertRow("s_3",0);
				$this->ds_cuentas->insertRow("s_4",0);
				$this->ds_cuentas->insertRow("s_5",0);
				$this->ds_cuentas->insertRow("s_6",0);
				$this->ds_cuentas->insertRow("s_7",0);
				$this->ds_cuentas->insertRow("resultado",0);
			}
		}
		$this->ds_cuentas->insertRow("sc_cuenta",' ');
		$this->ds_cuentas->insertRow("denominacion",'PASIVO + PATRIMONIO');
		$this->ds_cuentas->insertRow("tipo",999);
		$this->ds_cuentas->insertRow("prg_varia",0);
		$this->ds_cuentas->insertRow("prg_acum",0);
		$this->ds_cuentas->insertRow("saldo_ant",0);
		$this->ds_cuentas->insertRow("diferencia",0);
		$this->ds_cuentas->insertRow("p1",0);
		$this->ds_cuentas->insertRow("p2",0);
		$this->ds_cuentas->insertRow("var_prog",0);
		$this->ds_cuentas->insertRow("var_ejec",0);
		$this->ds_cuentas->insertRow("repproxmes",0);				
		$this->ds_cuentas->insertRow("nivel",'');
		$this->ds_cuentas->insertRow("s_ant",0);
		$this->ds_cuentas->insertRow("s_1",0);
		$this->ds_cuentas->insertRow("s_2",0);
		$this->ds_cuentas->insertRow("s_3",0);
		$this->ds_cuentas->insertRow("s_4",0);
		$this->ds_cuentas->insertRow("s_5",0);
		$this->ds_cuentas->insertRow("s_6",0);
		$this->ds_cuentas->insertRow("s_7",0);
		$this->ds_cuentas->insertRow("resultado",0);			
    }
/****************************************************************************************************************************************/	
	
	function uf_procesar_cuenta($as_cuenta,$ld_fecsalini,$ld_fecsalfin,$li_sel)
	{
		$lb_existe = false;
		$ls_codemp = $this->la_empresa["codemp"];
		$ls_sql="SELECT * 
				 FROM   scg_cuentas
				 WHERE  codemp='".$ls_codemp."' AND sc_cuenta like '".$as_cuenta."%'";
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			print "Error en procesar_cuenta 1".$this->io_sql->message;			
		}
		else
		{
			$li_nivel=$this->int_scg->uf_scg_obtener_nivel($as_cuenta);
			if($row=$this->io_sql->fetch_row($rs_data))
			{			
				
				$ls_cuenta_aux = $row["sc_cuenta"];
				$ls_sql    = "SELECT debe_mes,haber_mes
						      FROM   scg_saldos
						 	  WHERE  codemp='".$ls_codemp."' AND sc_cuenta like '".$ls_cuenta_aux."%' 
							  AND    fecsal <= '".$this->io_fun->uf_convertirdatetobd($ld_fecsalfin)."'";  //Quite esto el 06-12-2006   -> BETWEEN '".$this->io_fun->uf_convertirdatetobd($ld_fecsalini)."' AND
							  
				//print $ls_sql."<br>";		
				//print "------------------------------------------------------------------------------------------------------------";
				$li_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_cuenta_aux);
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					print "Error en procesar cuenta ".$this->io_sql->message;							
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$ldec_saldo =$this->uf_saldo_cuenta($ls_cuenta_aux,$ls_codemp,$ld_fecsalini,$ld_fecsalfin);		
						$ldec_debe=$row["debe_mes"];
						$ldec_haber=$row["haber_mes"];
						$ldec_diferencia=$ldec_debe-$ldec_haber;
						$this->uf_check_debhab($ls_codemp,$ls_cuenta_aux,&$lb_mondebhab,$ld_fecsalini,$ld_fecsalfin);												
						$ldec_prog_varia=$this->uf_programado($ls_codemp,$ls_cuenta_aux,substr($ld_fecsalini,3,2),substr($ld_fecsalfin,3,2),'0408');
						$ldec_prog_acumulado = $this->uf_acumulado_p($ls_codemp,$ls_cuenta_aux,substr($ld_fecsalini,3,2));
						$ldec_prog_acumulado = $ldec_prog_acumulado+$ldec_prog_varia;					
						//busca la programacion del año anterior
						if($this->uf_evalue_programacion(substr($ld_fecsalini,3,2),$li_newmes,$li_sel))
						{
							$ldec_prog_acumulado = $ldec_prog_acumulado + $this->uf_programado_periodo_ant($ls_codemp,$as_cuenta,$li_newmes);
						}
						$ldec_resta1= $ldec_diferencia-$ldec_prog_varia;
						$ldec_resta2= $ldec_saldo-$ldec_prog_acumulado;
						if(($ldec_prog_varia==0)&&($ldec_resta1==0))
						{
							$ldec_por1=0;						
						}
						else
						{
							if($ldec_resta1==0)
							{
								$ldec_por1=100;
							}
							else
							{
								$ldec_por1=$ldec_prog_varia / ($ldec_resta1*100);
							}
						}
						if(($ldec_prog_acumulado==0)&&($ldec_resta2==0))
						{
							$ldec_por2=0;							
						}
						else
						{
							if($ldec_resta2==0)
							{
								$ldec_por2=100;
							}
							else
							{
								$ldec_por2=$ldec_prog_acumulado/ ($ldec_resta2 *100);
							}
						}
						$ldec_repproxmes= $this->uf_programado($ls_codemp,$ls_cuenta_aux,substr($ld_fecsalfin,3,2)+1,substr($ld_fecsalfin,3,2)+$this->li_mes_prox,'0408');
						$ldec_saldo_ant = $this->uf_saldo_anterior($ls_codemp,$ls_cuenta_aux,$ld_fecsalini);
						$ldec_pvar= $ldec_prog_varia;
						$ldec_pacm= $ldec_prog_acumulado;
						$ldec_ejper=$ldec_saldo_ant+$ldec_saldo;
						$ldec_ejacm=$ldec_saldo_ant+$ldec_saldo;
						$ldec_vprog = 0;
						$ldec_vejec = 0;					
						switch(true){
							case ($ldec_pvar>0)&&($ldec_pvar>$ldec_ejper):
								$ldec_vprog = 0-($ldec_pvar-$ldec_ejper);
								break;
							case ($ldec_pvar==0)&&($ldec_pvar<$ldec_ejper):
								$ldec_vprog = $ldec_ejper;
								break;
							case ($ldec_pvar >0)&&($ldec_pvar<$ldec_ejper):
								$ldec_vprog = abs($ldec_pvar-$ldec_ejper);
								break;
							default:
								$ldec_vprog = $ldec_pvar-$ldec_ejper;
						}					
						switch(true){
							case ($ldec_pacm>0)&&($ldec_pacm>$ldec_ejacm):
								$ldec_vejec = 0-($ldec_pacm-$ldec_ejper);
								break;
							case ($ldec_pacm==0)&&($ldec_pacm<$ldec_ejacm):
								$ldec_vejec = $ldec_ejacm;
								break;
							case ($ldec_pacm>0)&&($ldec_pacm<$ldec_ejacm):
								$ldec_vejec = abs($ldec_pacm-$ldec_ejacm);
								break;
							default:
								$ldec_vejec = $ldec_pacm-$ldec_ejacm;
						}						
						$li_row=$this->ds_cuentas->findValues(array('sc_cuenta'=>$as_cuenta),'sc_cuenta');
						$this->ds_cuentas->updateRow("prg_varia",$ldec_prog_varia,$li_row);
						$this->ds_cuentas->updateRow("prg_acum",$ldec_prog_acumulado,$li_row);
						$this->ds_cuentas->updateRow("saldo_ant",$ldec_saldo,$li_row);
						$this->ds_cuentas->updateRow("diferencia",$ldec_diferencia,$li_row);
						$this->ds_cuentas->updateRow("p1",$ldec_por1,$li_row);
						$this->ds_cuentas->updateRow("p2",$ldec_por2,$li_row);
						$this->ds_cuentas->updateRow("repproxmes",$ldec_repproxmes,$li_row);
						$this->ds_cuentas->updateRow("nivel",$li_nivel,$li_row);
						$this->ds_cuentas->updateRow("s_ant",$ldec_saldo_ant,$li_row);	
						$this->ds_cuentas->updateRow("var_prog",$ldec_vprog,$li_row);	
						$this->ds_cuentas->updateRow("var_ejec",$ldec_vejec,$li_row);
						$lb_existe=true;
					}
					else
					{	
						$li_row=$this->ds_cuentas->findValues(array('sc_cuenta'=>$as_cuenta),'sc_cuenta');						
						$this->ds_cuentas->updateRow("prg_varia",0,$li_row);
						$this->ds_cuentas->updateRow("prg_acum",0,$li_row);
						$this->ds_cuentas->updateRow("saldo_ant",0,$li_row);
						$this->ds_cuentas->updateRow("diferencia",0,$li_row);
						$this->ds_cuentas->updateRow("p1",0,$li_row);
						$this->ds_cuentas->updateRow("p2",0,$li_row);
						$this->ds_cuentas->updateRow("repproxmes",0,$li_row);
						$this->ds_cuentas->updateRow("nivel",$li_nivel,$li_row);
						$this->ds_cuentas->updateRow("s_ant",0,$li_row);				
					}				
				}
			}
		}
	}
	
	
	function uf_saldo_cuenta($ls_cuenta_aux,$ls_codemp,$ld_fecsalini,$ld_fecsalfin)
	{
		$ls_sql    = "SELECT SUM(debe_mes-haber_mes) as saldo
					  FROM   scg_saldos
					  WHERE  codemp='".$ls_codemp."' AND sc_cuenta like '".$ls_cuenta_aux."%' 
					  AND    fecsal BETWEEN '".$this->io_fun->uf_convertirdatetobd($ld_fecsalini)."' AND '".$this->io_fun->uf_convertirdatetobd($ld_fecsalfin)."'";
		$ldec_saldo=0;
		$rs_data=$this->io_sql->select($ls_sql);
		//print $ls_sql;
		if($rs_data===false)
		{
			print "Error en saldo cuenta ".$this->io_sql->message;			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_saldo=$row["saldo"];
			}			
		}
		return $ldec_saldo;
	}
	
	
	function uf_check_debhab($ls_codemp,$ls_cuenta,$lb_mondebhab,$adt_fecdesde,$adt_fechasta)
	{
		$ls_sql="SELECT SUM(debe_mes) as debe, SUM(haber_mes) as haber 
				 FROM   scg_saldos 
				 WHERE  codemp='".$ls_codemp."' AND fecsal between '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND '".$this->io_fun->uf_convertirdatetobd($adt_fechasta)."' AND sc_cuenta='".$ls_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en checkdebhab".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{	
				$ldec_mondeb=$row["debe"];
				$ldec_monhab=$row["haber"];
				if($ldec_mondeb > $ldec_monhab)
				{
					$lb_mondebhab=true;
				}
				if($ldec_mondeb < $ldec_monhab)
				{
					$lb_mondebhab=false;
				}
			}		
		}
	}
	
	function uf_programado($ls_codemp,$ls_cuenta,$li_mesdesde,$li_meshasta,$ls_codreport)
	{
		$ldec_valor=0;
		$ls_sql="SELECT * 
				 FROM scg_pc_reporte 
				 WHERE sc_cuenta='".$ls_cuenta."' AND cod_report='".$ls_codreport."' AND codemp='".$ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en programado ".$this->io_sql->message;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				//print $li_mesdesde;
				//print $li_meshasta;
				for($i=intval($li_mesdesde);$i<=intval($li_meshasta);$i++)
				{
					switch($i){
						case 1:
							$ldec_valor = $ldec_valor+$row["enero"];
							break;
						case 2:
							$ldec_valor = $ldec_valor+$row["febrero"];
							break;
						case 3:
							$ldec_valor = $ldec_valor+$row["marzo"];
							break;
						case 4:
							$ldec_valor = $ldec_valor+$row["abril"];
							break;
						case 5:
							$ldec_valor = $ldec_valor+$row["mayo"];
							break;
						case 6:
							$ldec_valor = $ldec_valor+$row["junio"];
							break;
						case 7:
							$ldec_valor = $ldec_valor+$row["julio"];
							break;
						case 8:
							$ldec_valor = $ldec_valor+$row["agosto"];
							break;
						case 9:
							$ldec_valor = $ldec_valor+$row["septiembre"];
							break;
						case 10:
							$ldec_valor = $ldec_valor+$row["octubre"];
							break;
						case 11:
							$ldec_valor = $ldec_valor+$row["noviembre"];
							break;
						case 12:
							$ldec_valor = $ldec_valor+$row["diciembre"];
							break;
						default:
							$ldec_valor= 0;
							break;
					}//END CASE	
				}//END FOR
			}//END IF
		}//END IF
		return $ldec_valor;
	}
	
	function uf_acumulado_p($ls_codemp,$ls_cuenta,$li_mes)
	{
		$ldec_valor=0;
		$ls_codreport = '0408';
		//print "Mes=".$li_mes." Cuenta=".$ls_cuenta."  <br>";
		$ls_sql="SELECT * 
				 FROM scg_pc_reporte 
				 WHERE sc_cuenta='".$ls_cuenta."' AND cod_report='".$ls_codreport."' AND codemp='".$ls_codemp."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en acumulado p ".$this->io_sql->message;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				switch(intval($li_mes)){
					case 1:
						$ldec_valor = 0;
						break;
					case 2:
						$ldec_valor = $row["enero"];
						break;
					case 3:
						$ldec_valor = $row["enero"]+$row["febrero"];
						break;
					case 4:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"];
						break;
					case 5:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"];
						break;
					case 6:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"];
						break;
					case 7:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"];
						break;
					case 8:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"];
						break;
					case 9:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"];
						break;
					case 10:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"];
						break;
					case 11:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"];
						break;
					case 12:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"];
						break;
					default:
						$ldec_valor= 0;
						break;
				}//END CASE	
			}//END IF
		}//END IF
		return $ldec_valor;
	}
	
	function uf_programado_periodo_ant($ls_codemp,$ls_cuenta,$li_mes)
	{
		$ldec_valor=0;
		$ls_codreport = '0408';
		$ls_sql="SELECT * 
				 FROM scg_pc_reporte_ant 
				 WHERE sc_cuenta='".$ls_cuenta."' AND cod_report='".$ls_codreport."' AND codemp='".$ls_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en programado anterior ".$this->io_sql->message;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				switch(intval($li_mes)){
					case 1:
						$ldec_valor = $row["diciembre"];
						break;
					case 2:
						$ldec_valor = $row["noviembre"]+$row["diciembre"];
						break;
					case 3:
						$ldec_valor = $row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 4:
						$ldec_valor = $row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 5:
						$ldec_valor = $row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 6:
						$ldec_valor = $row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 7:
						$ldec_valor = $row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 8:
						$ldec_valor = $row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 9:
						$ldec_valor = $row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 10:
						$ldec_valor = $row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 11:
						$ldec_valor = $row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
					case 12:
						$ldec_valor = $row["enero"]+$row["febrero"]+$row["marzo"]+$row["abril"]+$row["mayo"]+$row["junio"]+$row["julio"]+$row["agosto"]+$row["septiembre"]+$row["octubre"]+$row["noviembre"]+$row["diciembre"];
						break;
				}//END CASE	
			}//END IF
		}//END IF
		return $ldec_valor;
	}		
	
	function uf_evalue_programacion($li_mes,&$li_newmes,$li_sel)
	{
		$lb_existe 	= false;		
		$li_newmes	= 0;
		switch(intval($li_sel)){
			case 1:
				$li_cantidad = 1;
				if(intval($li_mes,10)==1)
				{
					$li_meses = 1;
					$lb_existe= true;
				}		
				break;
			case 2:
				$li_cantidad = 2;
				if(intval($li_mes,10)<2)
				{
					$li_meses = 2-intval($li_mes,10);
					$lb_existe= true;
				}
				break;	
			case 3:
				$li_cantidad = 3;
				if(intval($li_mes,10)<3)
				{
					$li_meses = 3-intval($li_mes,10);
					$lb_existe= true;
				}
				break;
			case 6:
				$li_cantidad = 6;
				if(intval($li_mes,10)<6)
				{
					$li_meses = 6-intval($li_mes,10);
					$lb_existe= true;
				}
				break;
		}
		return $lb_existe;
	}
	
	function uf_saldo_anterior($ls_codemp,$ls_cuenta,$adt_fecdesde)
	{
		$ls_sql = "SELECT (sum(debe_mes) - sum(haber_mes)) as saldo 
				   FROM scg_saldos 
				   WHERE codemp='".$ls_codemp."' AND fecsal < '".$this->io_fun->uf_convertirdatetobd($adt_fecdesde)."' AND sc_cuenta='".$ls_cuenta."'";
		$ldec_saldo=0;
		$rs_data = $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("Error en saldo anterior");
			print $this->io_sql->message;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_saldo= $row["saldo"];
			}			
		}
		return $ldec_saldo;
	}
	
	function uf_calcular_resultado($ldt_feclimit,$li_maxnivel)
	{
		$li_activo=$this->la_empresa["activo"];
		$li_pasivo=$this->la_empresa["pasivo"];
		$li_resultado=$this->la_empresa["resultado"];
		$li_capital=$this->la_empresa["capital"];		
		$li_orden_d=$this->la_empresa["orden_d"];
		$li_orden_h=$this->la_empresa["orden_h"];		
		$li_ingreso=$this->la_empresa["ingreso"];
		$li_gastos =$this->la_empresa["gasto"];
		$ls_codemp =$this->la_empresa["codemp"];
		$ls_cuenta_resultado = $this->la_empresa["c_resultad"];
		if(($this->ls_gestor=="MYSQLT")||($this->ls_gestor=="ORACLE"))
		{
			$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,curSaldo.T_Debe,curSaldo.T_Haber, 0 as Nivel 
					  FROM scg_cuentas SC
					  LEFT OUTER JOIN ( SELECT  sc_cuenta, sum(debe_mes)as T_Debe, sum(haber_mes) as T_Haber 
					                       FROM scg_saldos 
					                       WHERE codemp='".$ls_codemp."' AND 
										   fecsal<='".$this->io_fun->uf_convertirdatetobd($ldt_feclimit)."'
					                       GROUP BY sc_cuenta) curSaldo 
					  ON SC.sc_cuenta=curSaldo.sc_cuenta 	
					  WHERE codemp='".$ls_codemp."' AND (SC.sc_cuenta like '".$li_activo."%' OR 
					        SC.sc_cuenta like '".$li_pasivo."%' OR SC.sc_cuenta like '".substr($ls_cuenta_resultado,0,1)."%' OR 
							SC.sc_cuenta like '".$li_capital."%' OR SC.sc_cuenta like '".$li_orden_d."%' OR 
							SC.sc_cuenta like '".$li_orden_h."%') 
					  ORDER BY  SC.sc_cuenta ";
		}
		else
		{
			$ls_sql =" SELECT  SC.sc_cuenta, SC.denominacion, SC.status, curSaldo.T_Debe, curSaldo.T_Haber, 
			                   cast(0 as int) as Nivel 
					   FROM  scg_cuentas SC, (SELECT sc_cuenta, sum(debe_mes)as T_Debe, sum(haber_mes) as T_Haber 
											  FROM   scg_saldos 
											  WHERE  codemp='".$ls_codemp."' AND 
											         fecsal<= '".$this->io_fun->uf_convertirdatetobd($ldt_feclimit)."'
					  GROUP BY sc_cuenta) as curSaldo 
					  WHERE codemp='".$ls_codemp."' AND ( SC.sc_cuenta = curSaldo.sc_cuenta) AND 
					        (SC.sc_cuenta like '".$li_activo."%'  OR 
					        SC.sc_cuenta like '".$li_pasivo."%' OR 
					        SC.sc_cuenta like '".substr($ls_cuenta_resultado,0,1)."%'  OR
					        SC.sc_cuenta like '".substr($li_capital,0,1)."%' OR 
							SC.sc_cuenta like '".$li_orden_d."%' OR SC.sc_cuenta like '".$li_orden_h."%')
					  ORDER BY SC.sc_cuenta ";
		}
		if($_SESSION["ls_gestor"]=='INFORMIX')	    
		{
		        $ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.status,
                         (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$this->io_fun->uf_convertirdatetobd($ldt_feclimit)."' and sc_cuenta=SC.sc_cuenta GROUP BY sc_cuenta) as T_Debe,
                         (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$this->io_fun->uf_convertirdatetobd($ldt_feclimit)."' and sc_cuenta=SC.sc_cuenta GROUP BY sc_cuenta) as T_Haber,
                         0 as Nivel 
                         FROM scg_cuentas SC
                         WHERE SC.codemp='".$ls_codemp."' 
                         AND (SC.sc_cuenta like '".$li_activo."%'  
						   OR SC.sc_cuenta like '".$li_pasivo."%'
						   OR SC.sc_cuenta like '".substr($ls_cuenta_resultado,0,1)."%' 
						   OR SC.sc_cuenta like '".$li_capital."%'
						   OR SC.sc_cuenta like '".$li_orden_d."%' 
						   OR SC.sc_cuenta like '".$li_orden_h."%') 
                              ORDER BY SC.sc_cuenta";	
		}
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message."<br>".$this->is_msg_error;
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
		  $ld_total_debe=$row["t_debe"];
		  $ld_total_haber=$row["t_haber"];
		  if($ls_status=="C")
		  {
    		$ls_nivel="4";		
		  }//if
		  else
		  {
    		$ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_sc_cuenta);		
		  }//else
		  if($ls_nivel<=$li_maxnivel)
		  {
			  $this->ds_prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->ds_prebalance->insertRow("denominacion",$ls_denominacion);
			  $this->ds_prebalance->insertRow("status",$ls_status);
			  $this->ds_prebalance->insertRow("nivel",$ls_nivel);
			  $this->ds_prebalance->insertRow("total_debe",$ld_total_debe);
			  $this->ds_prebalance->insertRow("total_haber",$ld_total_haber);
		      $lb_valido = true;
		  }//if
		}//while
	    $li=$this->ds_prebalance->getRowCount("sc_cuenta");
		if($li==0)
		{
		  $lb_valido = false;
		  return false;
		}//if
	 }		
		//resumen de ingresos y gastos
		$ldec_ganancia=0;		
		$ldec_ingreso=0;
		$ldec_gasto=0;
		$ls_sql=" SELECT sum(B.debe_mes-B.haber_mes) as saldo 
			      FROM   scg_cuentas SC, scg_saldos B
				  WHERE  SC.codemp='".$ls_codemp."' AND 
				         SC.codemp=B.codemp AND (SC.sc_cuenta = B.sc_cuenta) AND 
						 SC.status='C' AND B.fecsal <= '".$this->io_fun->uf_convertirdatetobd($ldt_feclimit)."' AND 
						 SC.sc_cuenta like '".$li_ingreso."%'";
		$rs_data= $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en metodo calcular_resultado";
			print $this->is_msg_error;
			return false;
		}
		else
		{		
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_ingreso = $row["saldo"];
			}			
		}
		$ls_sql=" SELECT sum(B.debe_mes-B.haber_mes) as saldo 
		  FROM   scg_cuentas SC, scg_saldos B
		  WHERE  SC.codemp='".$ls_codemp."' AND SC.codemp=B.codemp AND (SC.sc_cuenta = B.sc_cuenta) AND SC.status='C' AND 
				 B.fecsal <= '".$this->io_fun->uf_convertirdatetobd($ldt_feclimit)."' AND SC.sc_cuenta like '".$li_gastos."%'";
		$rs_data= $this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en metodo calcular_resultado";
			print $this->is_msg_error;
			return false;
		}
		else
		{		
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_gasto = $row["saldo"];
			}			
		}
	 $ldec_ganancia = $ldec_ingreso+$ldec_gasto;
	 $la_sc_cuenta=array();
	 $la_denominacion=array();
	 $la_saldo=array();
	 $li_nivel=3;
	 for($i=1;$i<=$li_nivel;$i++)
	 {
		 $la_sc_cuenta[$i]="";
		 $la_denominacion[$i]="";
		 $la_saldo[$i]=0;
	 }//for				
	 $ls_prev_nivel=$this->ds_prebalance->getValue("nivel",1);	
	 $li_nro_reg=0;
     $ld_saldo_resultado=0;
	 $li_row=$this->ds_prebalance->getRowCount("sc_cuenta");
	 for($li_i=1;$li_i<=$li_row;$li_i++)
	 {
		  $ls_sc_cuenta=$this->ds_prebalance->getValue("sc_cuenta",$li_i);
		  $ls_status=$this->ds_prebalance->getValue("status",$li_i);
		  $ls_denominacion=$this->ds_prebalance->getValue("denominacion",$li_i);
		  $ls_rnivel=$this->ds_prebalance->getValue("rnivel",$li_i);
		  $ld_total_debe=$this->ds_prebalance->getValue("total_debe",$li_i);
		  $ld_total_haber=$this->ds_prebalance->getValue("total_haber",$li_i); 
		  $ls_nivel=$this->ds_prebalance->getValue("nivel",$li_i); 

		  $ls_tipo_cuenta=substr($ls_sc_cuenta,0,1);
		  $ls_orden="7";
		  if($ls_tipo_cuenta==$li_activo  ) { $ls_orden="1"; }	
		  if($ls_tipo_cuenta==$li_pasivo  ) { $ls_orden="2"; }	
		  if($ls_tipo_cuenta==$li_capital ) { $ls_orden="3"; }	
		  if($ls_tipo_cuenta==$li_resultado){ $ls_orden="4"; } 	
		  if($ls_tipo_cuenta==$li_orden_d ) { $ls_orden="5"; }
		  if($ls_tipo_cuenta==$li_orden_h ) { $ls_orden="6"; }	
		
		  $ld_saldo=abs($ld_total_debe-$ld_total_haber);
		  if((($ls_tipo_cuenta==$li_pasivo)||($ls_tipo_cuenta==$li_resultado)||($ls_tipo_cuenta==$li_capital))&&($ls_nivel==1))
		  {
			  $ld_saldo_resultado=$ld_saldo_resultado+$ld_saldo;
		  }//if
		  if($ls_nivel==4)
		  {
		    $li_nro_reg=$li_nro_reg+1; 
		    $this->ds_balance1->insertRow("orden",$ls_orden);
		    $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
		    $this->ds_balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
		    $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
			$this->ds_balance1->insertRow("nivel",$ls_nivel);
			$this->ds_balance1->insertRow("saldo",$ld_saldo);
		  }//if
		  else
		  {
		    if (empty($la_sc_cuenta[$ls_nivel]))
			{
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ld_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->ds_balance1->insertRow("orden",$ls_orden);
			   $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
			   $this->ds_balance1->insertRow("nivel",-$ls_nivel);
			   $this->ds_balance1->insertRow("saldo",$ld_saldo);
			}//if
            else
			{
			   $this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,$ls_nivel,$la_sc_cuenta,$la_denominacion,$la_saldo,$li_activo,$li_pasivo,$li_capital,$li_resultado,$li_orden_d,$li_orden_h); 
			   $la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
			   $la_denominacion[$ls_nivel]=$ls_denominacion;
			   $la_saldo[$ls_nivel]=$ld_saldo;
		       $li_nro_reg=$li_nro_reg+1;
			   $this->ds_balance1->insertRow("orden",$ls_orden);
			   $this->ds_balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			   $this->ds_balance1->insertRow("denominacion",$ls_denominacion);
			   $this->ds_balance1->insertRow("nivel",-$ls_nivel);
			   $this->ds_balance1->insertRow("saldo",$ld_saldo);
			}//else 			
          $ls_prev_nivel=$ls_nivel;		 
		}//else
	 }//for	
		
		$this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,1,$la_sc_cuenta,$la_denominacion,$la_saldo,$li_activo,$li_pasivo,$li_capital,$li_resultado,$li_orden_d,$li_orden_h); 
	
		//-- Actualiza las cuentas de resultado
		$this->uf_scg_reporte_actualizar_resultado_BG($ls_cuenta_resultado,$ldec_ganancia,$li_nro_reg,$ls_orden);
		$ld_saldo_resultado=($ld_saldo_resultado+$ldec_ganancia)*-1;
		return $ldec_ganancia;	
		
	}
	
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
function uf_select_denominacion($as_sc_cuenta,&$as_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_denominacion 
	//	     Arguments:  $as_sc_cuenta  // codigo de la cuenta
	//                   $as_denominacion  // denominacion de la cuenta (referencia)
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca la denominacion de la cuenta
	//     Creado por :  Ing. Yozelin Barrag
	// Fecha Creaci :  14/08/2006                      Fecha ltima Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
    $ls_codemp = $this->la_empresa["codemp"];
	$ls_sql = "SELECT denominacion 
			   FROM scg_cuentas 
			   WHERE sc_cuenta='".$as_sc_cuenta."' AND codemp='".$ls_codemp."' ";
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
		$this->is_msg_error="Error en consulta metodo uf_select_denominacion ".$this->fun->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
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
/****************************************************************************************************************************************/		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////                ESTADO RESULTADO                  /////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 	function uf_crear_reporte($ld_fecdesde,$ld_fechasta,$li_cant_mes)
	{
//		omitir=thisform.chkOmitir.value
		
		$this->uf_init_array_resultado();
		
		$li_reg=0;
		
		//*---> primero, carga la informacion de ingresos
		for($li_i=1;$li_i<=18;$li_i++)
		{
			$this->uf_procesa_cuenta_spi($this->la_cuentas[$li_i],$ld_fecdesde,$ld_fechasta);
		}
		
		//*---> sigue con lo de contabilidad
		
		for($li_a=22;$li_a<=26;$li_a++)
		{
			$this->uf_procesa_cuenta_resultado($this->la_cuentas[$li_a],$ld_fecdesde,$ld_fechasta);
		}
		
		//*---> termina con presupuesto
		for($li_x=19;$li_x<=21;$li_x++)
		{
			$this->uf_procesa_cuenta_spg(1,$this->la_cuentas[$li_x],$ld_fecdesde,$ld_fechasta);
		}
		
		for($li_z=27;$li_z<=37;$li_z++)
		{
			$this->uf_procesa_cuenta_spg(1,$this->la_cuentas[$li_z],$ld_fecdesde,$ld_fechasta);
		}
		
		$ldec_i_programado=0;
		$ldec_i_pacum=0;
		$ldec_i_ejecutado=0;
		$ldec_i_eacum=0;
		$ldec_i_variacion=0;
		$ldec_i_vacum=0;
		$ldec_i_prevproxmes=0;
		$ldec_e_programado=0;
		$ldec_e_pacum=0;
		$ldec_e_ejecutado=0;
		$ldec_e_eacum=0;
		$ldec_e_variacion=0;
		$ldec_e_vacum=0;
		$ldec_e_prevproxmes=0;
		//*---> busca total de ingresos y egresos
		$li_rows=$this->ds_cuentas->getRowCount("sc_cuenta");
		for($li_a=1;$li_a<=$li_rows;$li_a++)
		{	
			$ls_cuenta_temp=substr($this->ds_cuentas->getValue("sc_cuenta",$li_a),0,9);
			if(($ls_cuenta_temp=='300000000')||($ls_cuenta_temp=='305010000')||($ls_cuenta_temp=='303010000')||($ls_cuenta_temp=='303020000')||($ls_cuenta_temp=='301050000')||($ls_cuenta_temp=='301100000'))
			{
				$ldec_i_programado = $ldec_i_programado+$this->ds_cuentas->getValue("programado",$li_a);
				$ldec_i_pacum      = $ldec_i_pacum+$this->ds_cuentas->getValue("programado_acum",$li_a);
				$ldec_i_ejecutado  = $ldec_i_ejecutado+$this->ds_cuentas->getValue("ejecutado",$li_a);
				$ldec_i_eacum      = $ldec_i_eacum+$this->ds_cuentas->getValue("ejecutado_acum",$li_a);
				$ldec_i_variacion  = $ldec_i_variacion+$this->ds_cuentas->getValue("variacion",$li_a);
				$ldec_i_vacum      = $ldec_i_vacum+$this->ds_cuentas->getValue("variacion_acum",$li_a);
				$ldec_i_prevproxmes= $ldec_i_prevproxmes+$this->ds_cuentas->getValue("prevproxmes",$li_a);
			}
			if(($ls_cuenta_temp=='401000000')||($ls_cuenta_temp=='402000000')||($ls_cuenta_temp=='403000000')||($ls_cuenta_temp=='113010000')||($ls_cuenta_temp=='113020000')||($ls_cuenta_temp=='113030000')||
			($ls_cuenta_temp=='113040000')||($ls_cuenta_temp=='113050000')||($ls_cuenta_temp=='408000000')||($ls_cuenta_temp=='407000000'))
			{
				$ldec_e_programado = $ldec_e_programado+$this->ds_cuentas->getValue("programado",$li_a);
				$ldec_e_pacum      = $ldec_e_pacum+$this->ds_cuentas->getValue("programado_acum",$li_a);
				$ldec_e_ejecutado  = $ldec_e_ejecutado+$this->ds_cuentas->getValue("ejecutado",$li_a);
				$ldec_e_eacum      = $ldec_e_eacum+$this->ds_cuentas->getValue("ejecutado_acum",$li_a);
				$ldec_e_variacion  = $ldec_e_variacion+$this->ds_cuentas->getValue("variacion",$li_a);
				$ldec_e_vacum      = $ldec_e_vacum+$this->ds_cuentas->getValue("variacion_acum",$li_a);
				$ldec_e_prevproxmes= $ldec_e_prevproxmes+$this->ds_cuentas->getValue("prevproxmes",$li_a);
			}
		}			
		$la_find=array('tipo'=>'999');
		$li_row	=$this->ds_cuentas->findValues($la_find,"sc_cuenta");	
		$ld_saldo1=$ldec_i_programado-$ldec_e_programado;
		$ld_saldo2=$ldec_i_pacum-$ldec_e_pacum;
		$ld_saldo3=$ldec_i_ejecutado-$ldec_e_ejecutado;
		$ld_saldo4=$ldec_i_eacum-$ldec_e_eacum;
		$ld_saldo5=$ldec_i_variacion-$ldec_e_variacion;
		$ld_saldo6=$ldec_i_vacum-$ldec_e_vacum;
		$ld_saldo7=$ldec_i_prevproxmes-$ldec_e_prevproxmes;
		$this->ds_cuentas->updateRow("programado",$ld_saldo1,$li_row);
		$this->ds_cuentas->updateRow("programado_acum",$ld_saldo2,$li_row);
		$this->ds_cuentas->updateRow("ejecutado",$ld_saldo3,$li_row);
		$this->ds_cuentas->updateRow("ejecutado_acum",$ld_saldo4,$li_row);
		$this->ds_cuentas->updateRow("variacion",$ld_saldo5,$li_row);
		$this->ds_cuentas->updateRow("variacion_acum",$ld_saldo6,$li_row);
		$this->ds_cuentas->updateRow("prevproxmes",$ld_saldo7,$li_row);	
	/*	if(($ld_saldo1==0)&&($ld_saldo3==0))
		{	$ldec_porc1=0;	}
		else
		{	if($ld_saldo3==0)
			{	$ldec_porc1=100;	}
			else
			{	$ldec_porc1=$ld_saldo1/($ld_saldo3*100);	}
		}
		if(($ld_saldo2==0)&&($ld_saldo4==0))
		{	$ldec_porc2=0;	}
		else
		{	if($ld_saldo4==0)
			{	$ldec_porc2=100;	}
			else
			{	$ldec_porc2=$ld_saldo2/($ld_saldo4*100);	}
		}
		$this->ds_cuentas->updateRow("p1",$ldec_porc1,$li_row);
		$this->ds_cuentas->updateRow("p2",$ldec_porc2,$li_row);*/
		
		
		$la_find=array('tipo'=>'11');
		$li_row	=$this->ds_cuentas->findValues($la_find,"sc_cuenta");	
		
		$this->ds_cuentas->updateRow("programado",$ldec_i_programado,$li_row);
		$this->ds_cuentas->updateRow("programado_acum",$ldec_i_pacum,$li_row);
		$this->ds_cuentas->updateRow("ejecutado",$ldec_i_ejecutado,$li_row);
		$this->ds_cuentas->updateRow("ejecutado_acum",$ldec_i_eacum,$li_row);
		$this->ds_cuentas->updateRow("variacion",$ldec_i_variacion,$li_row);
		$this->ds_cuentas->updateRow("variacion_acum",$ldec_i_vacum,$li_row);
		$this->ds_cuentas->updateRow("prevproxmes",$ldec_i_prevproxmes,$li_row);		
		if(($ldec_i_programado==0)&&($ldec_i_ejecutado==0))
		{	$ldec_porc1=0;	}
		else
		{	if($ldec_i_ejecutado==0)
			{	$ldec_porc1=100;	}
			else
			{	$ldec_porc1=$ldec_i_programado/($ldec_i_ejecutado*100);	}
		}
		if(($ldec_i_pacum==0)&&($ldec_i_eacum==0))
		{	$ldec_porc2=0;	}
		else
		{	if($ldec_i_eacum==0)
			{	$ldec_porc2=100;	}
			else
			{	$ldec_porc2=$ldec_i_pacum/($ldec_i_eacum*100);	}
		}
		$this->ds_cuentas->updateRow("p1",$ldec_porc1,$li_row);
		$this->ds_cuentas->updateRow("p2",$ldec_porc2,$li_row);
		
		$la_find=array('tipo'=>'22');
		$li_row	=$this->ds_cuentas->findValues($la_find,"sc_cuenta");	
		
		$this->ds_cuentas->updateRow("programado",$ldec_e_programado,$li_row);
		$this->ds_cuentas->updateRow("programado_acum",$ldec_e_pacum,$li_row);
		$this->ds_cuentas->updateRow("ejecutado",$ldec_e_ejecutado,$li_row);
		$this->ds_cuentas->updateRow("ejecutado_acum",$ldec_e_eacum,$li_row);
		$this->ds_cuentas->updateRow("variacion",$ldec_e_variacion,$li_row);
		$this->ds_cuentas->updateRow("variacion_acum",$ldec_e_vacum,$li_row);
		$this->ds_cuentas->updateRow("prevproxmes",$ldec_e_prevproxmes,$li_row);
		if(($ldec_e_programado==0)&&($ldec_e_ejecutado==0))
		{	$ldec_porc1=0;	}
		else
		{	if($ldec_e_ejecutado==0)
			{	$ldec_porc1=100;	}
			else
			{	$ldec_porc1=$ldec_e_programado/($ldec_e_ejecutado*100);	}
		}
		if(($ldec_e_pacum==0)&&($ldec_e_eacum==0))
		{	$ldec_porc2=0;	}
		else
		{	if($ldec_e_eacum==0)
			{	$ldec_porc2=100;	}
			else
			{	$ldec_porc2=$ldec_e_pacum/($ldec_e_eacum*100);	}
		}
		$this->ds_cuentas->updateRow("p1",$ldec_porc1,$li_row);
		$this->ds_cuentas->updateRow("p2",$ldec_porc2,$li_row);
		return true;
	}	
	 
	function uf_init_array_resultado()
	{
		//Dimension arCuentas(37)
		
		$this->la_cuentas[1]  = '305010000';
		$this->la_cuentas[2]  = '305010100';
		$this->la_cuentas[3]  = '305010300';
		$this->la_cuentas[4]  = '305010301';
		$this->la_cuentas[5]  = '305010302';
		$this->la_cuentas[6]  = '305010304';
		$this->la_cuentas[7]  = '305010308';
		$this->la_cuentas[8]  = '305010309';
		$this->la_cuentas[9]  = '305010500';
		$this->la_cuentas[10] = '305010501';
		$this->la_cuentas[11] = '305010502';
		$this->la_cuentas[12] = '305010303';
		
		$this->la_cuentas[13] = '303010000';
		$this->la_cuentas[14] = '303020000';
		$this->la_cuentas[15] = '301050000';
		$this->la_cuentas[16] = '301100000';
		$this->la_cuentas[17] = '301100401';
		$this->la_cuentas[18] = '301100500';
		
		$this->la_cuentas[19] = '401000000';
		$this->la_cuentas[20] = '402000000';
		$this->la_cuentas[21] = '403000000';
		
		$this->la_cuentas[22] = '113010000';
		$this->la_cuentas[23] = '113020000';
		$this->la_cuentas[24] = '113030000';
		$this->la_cuentas[25] = '113040000';
		$this->la_cuentas[26] = '113050000';
		
		$this->la_cuentas[27] = '408000000';
		$this->la_cuentas[28] = '408010100';
		$this->la_cuentas[29] = '408010200';
		$this->la_cuentas[30] = '408020000';
		$this->la_cuentas[31] = '408060400';
		$this->la_cuentas[32] = '408060000';
		$this->la_cuentas[33] = '408070000';
		
		$this->la_cuentas[34] = '407000000';
		$this->la_cuentas[35] = '407010100';
		$this->la_cuentas[36] = '407010300';
		$this->la_cuentas[37] = '408080000';		

		
 
		$this->ds_cuentas->insertRow("sc_cuenta",'  ');
		$this->ds_cuentas->insertRow("denominacion",'1. INGRESOS CORRIENTES');
		$this->ds_cuentas->insertRow("tipo",11);
		$this->ds_cuentas->insertRow("programado",0);
		$this->ds_cuentas->insertRow("programado_acum",0);
		$this->ds_cuentas->insertRow("saldo_ant",0);
		$this->ds_cuentas->insertRow("variacion",0);
		$this->ds_cuentas->insertRow("p1",0);
		$this->ds_cuentas->insertRow("p2",0);
		$this->ds_cuentas->insertRow("ejecutado",0);
		$this->ds_cuentas->insertRow("ejecutado_acum",0);
		$this->ds_cuentas->insertRow("prevproxmes",0);				
		$this->ds_cuentas->insertRow("nivel",'');
		$this->ds_cuentas->insertRow("s_ant",0);
		$this->ds_cuentas->insertRow("s_1",0);
		$this->ds_cuentas->insertRow("s_2",0);
		$this->ds_cuentas->insertRow("s_3",0);
		$this->ds_cuentas->insertRow("s_4",0);
		$this->ds_cuentas->insertRow("s_5",0);
		$this->ds_cuentas->insertRow("s_6",0);
		$this->ds_cuentas->insertRow("s_7",0);
		$this->ds_cuentas->insertRow("variacion_acum",0);
		
		$this->ds_cuentas->insertRow("sc_cuenta",'  ');
		$this->ds_cuentas->insertRow("denominacion",'a. Transferencias y Donaciones Corrientes');
		$this->ds_cuentas->insertRow("tipo",0);
		$this->ds_cuentas->insertRow("programado",0);
		$this->ds_cuentas->insertRow("programado_acum",0);
		$this->ds_cuentas->insertRow("saldo_ant",0);
		$this->ds_cuentas->insertRow("variacion",0);
		$this->ds_cuentas->insertRow("p1",0);
		$this->ds_cuentas->insertRow("p2",0);
		$this->ds_cuentas->insertRow("ejecutado",0);
		$this->ds_cuentas->insertRow("ejecutado_acum",0);
		$this->ds_cuentas->insertRow("prevproxmes",0);				
		$this->ds_cuentas->insertRow("nivel",'');
		$this->ds_cuentas->insertRow("s_ant",0);
		$this->ds_cuentas->insertRow("s_1",0);
		$this->ds_cuentas->insertRow("s_2",0);
		$this->ds_cuentas->insertRow("s_3",0);
		$this->ds_cuentas->insertRow("s_4",0);
		$this->ds_cuentas->insertRow("s_5",0);
		$this->ds_cuentas->insertRow("s_6",0);
		$this->ds_cuentas->insertRow("s_7",0);
		$this->ds_cuentas->insertRow("variacion_acum",0);		
		
		for($li_i=1;$li_i<=37;$li_i++)
		{
			switch($li_i){
				case 13:
					$this->ds_cuentas->insertRow("sc_cuenta",'  ');
					$this->ds_cuentas->insertRow("denominacion",'b. Ingresos por Actividades Propias');
					$this->ds_cuentas->insertRow("tipo",0);
					$this->ds_cuentas->insertRow("programado",0);
					$this->ds_cuentas->insertRow("programado_acum",0);
					$this->ds_cuentas->insertRow("saldo_ant",0);
					$this->ds_cuentas->insertRow("variacion",0);
					$this->ds_cuentas->insertRow("p1",0);
					$this->ds_cuentas->insertRow("p2",0);
					$this->ds_cuentas->insertRow("ejecutado",0);
					$this->ds_cuentas->insertRow("ejecutado_acum",0);
					$this->ds_cuentas->insertRow("prevproxmes",0);				
					$this->ds_cuentas->insertRow("nivel",'');
					$this->ds_cuentas->insertRow("s_ant",0);
					$this->ds_cuentas->insertRow("s_1",0);
					$this->ds_cuentas->insertRow("s_2",0);
					$this->ds_cuentas->insertRow("s_3",0);
					$this->ds_cuentas->insertRow("s_4",0);
					$this->ds_cuentas->insertRow("s_5",0);
					$this->ds_cuentas->insertRow("s_6",0);
					$this->ds_cuentas->insertRow("s_7",0);
					$this->ds_cuentas->insertRow("variacion_acum",0);
					break;		
				case 15:
					$this->ds_cuentas->insertRow("sc_cuenta",'  ');
					$this->ds_cuentas->insertRow("denominacion",'c. Otros Ingresos Corrientes');
					$this->ds_cuentas->insertRow("tipo",0);
					$this->ds_cuentas->insertRow("programado",0);
					$this->ds_cuentas->insertRow("programado_acum",0);
					$this->ds_cuentas->insertRow("saldo_ant",0);
					$this->ds_cuentas->insertRow("variacion",0);
					$this->ds_cuentas->insertRow("p1",0);
					$this->ds_cuentas->insertRow("p2",0);
					$this->ds_cuentas->insertRow("ejecutado",0);
					$this->ds_cuentas->insertRow("ejecutado_acum",0);
					$this->ds_cuentas->insertRow("prevproxmes",0);				
					$this->ds_cuentas->insertRow("nivel",'');
					$this->ds_cuentas->insertRow("s_ant",0);
					$this->ds_cuentas->insertRow("s_1",0);
					$this->ds_cuentas->insertRow("s_2",0);
					$this->ds_cuentas->insertRow("s_3",0);
					$this->ds_cuentas->insertRow("s_4",0);
					$this->ds_cuentas->insertRow("s_5",0);
					$this->ds_cuentas->insertRow("s_6",0);
					$this->ds_cuentas->insertRow("s_7",0);
					$this->ds_cuentas->insertRow("variacion_acum",0);
					break;		
				case 19:
					$this->ds_cuentas->insertRow("sc_cuenta",'  ');
					$this->ds_cuentas->insertRow("denominacion",'2. GASTOS CORRIENTES');
					$this->ds_cuentas->insertRow("tipo",22);
					$this->ds_cuentas->insertRow("programado",0);
					$this->ds_cuentas->insertRow("programado_acum",0);
					$this->ds_cuentas->insertRow("saldo_ant",0);
					$this->ds_cuentas->insertRow("variacion",0);
					$this->ds_cuentas->insertRow("p1",0);
					$this->ds_cuentas->insertRow("p2",0);
					$this->ds_cuentas->insertRow("ejecutado",0);
					$this->ds_cuentas->insertRow("ejecutado_acum",0);
					$this->ds_cuentas->insertRow("prevproxmes",0);				
					$this->ds_cuentas->insertRow("nivel",'');
					$this->ds_cuentas->insertRow("s_ant",0);
					$this->ds_cuentas->insertRow("s_1",0);
					$this->ds_cuentas->insertRow("s_2",0);
					$this->ds_cuentas->insertRow("s_3",0);
					$this->ds_cuentas->insertRow("s_4",0);
					$this->ds_cuentas->insertRow("s_5",0);
					$this->ds_cuentas->insertRow("s_6",0);
					$this->ds_cuentas->insertRow("s_7",0);
					$this->ds_cuentas->insertRow("variacion_acum",0);
					
					$this->ds_cuentas->insertRow("sc_cuenta",'  ');
					$this->ds_cuentas->insertRow("denominacion",'a. Gastos de Consumo');
					$this->ds_cuentas->insertRow("tipo",0);
					$this->ds_cuentas->insertRow("programado",0);
					$this->ds_cuentas->insertRow("programado_acum",0);
					$this->ds_cuentas->insertRow("saldo_ant",0);
					$this->ds_cuentas->insertRow("variacion",0);
					$this->ds_cuentas->insertRow("p1",0);
					$this->ds_cuentas->insertRow("p2",0);
					$this->ds_cuentas->insertRow("ejecutado",0);
					$this->ds_cuentas->insertRow("ejecutado_acum",0);
					$this->ds_cuentas->insertRow("prevproxmes",0);				
					$this->ds_cuentas->insertRow("nivel",'');
					$this->ds_cuentas->insertRow("s_ant",0);
					$this->ds_cuentas->insertRow("s_1",0);
					$this->ds_cuentas->insertRow("s_2",0);
					$this->ds_cuentas->insertRow("s_3",0);
					$this->ds_cuentas->insertRow("s_4",0);
					$this->ds_cuentas->insertRow("s_5",0);
					$this->ds_cuentas->insertRow("s_6",0);
					$this->ds_cuentas->insertRow("s_7",0);
					$this->ds_cuentas->insertRow("variacion_acum",0);
					break;						
				case 34:
					$this->ds_cuentas->insertRow("sc_cuenta",'  ');
					$this->ds_cuentas->insertRow("denominacion",'b. Otros Gastos Corrientes');
					$this->ds_cuentas->insertRow("tipo",0);
					$this->ds_cuentas->insertRow("programado",0);
					$this->ds_cuentas->insertRow("programado_acum",0);
					$this->ds_cuentas->insertRow("saldo_ant",0);
					$this->ds_cuentas->insertRow("variacion",0);
					$this->ds_cuentas->insertRow("p1",0);
					$this->ds_cuentas->insertRow("p2",0);
					$this->ds_cuentas->insertRow("ejecutado",0);
					$this->ds_cuentas->insertRow("ejecutado_acum",0);
					$this->ds_cuentas->insertRow("prevproxmes",0);				
					$this->ds_cuentas->insertRow("nivel",'');
					$this->ds_cuentas->insertRow("s_ant",0);
					$this->ds_cuentas->insertRow("s_1",0);
					$this->ds_cuentas->insertRow("s_2",0);
					$this->ds_cuentas->insertRow("s_3",0);
					$this->ds_cuentas->insertRow("s_4",0);
					$this->ds_cuentas->insertRow("s_5",0);
					$this->ds_cuentas->insertRow("s_6",0);
					$this->ds_cuentas->insertRow("s_7",0);
					$this->ds_cuentas->insertRow("variacion_acum",0);
					break;					
			}
		
			$ls_cuenta= $this->la_cuentas[$li_i];
		
			if(($li_i>=1)&&($li_i<= 18))
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico_re 
						 WHERE	sig_cuenta = '".$ls_cuenta."'";
				$rs_data=$this->io_sql->select($ls_sql);
						
				if($rs_data===false)
				{
					$this->is_msg_error("Error en búsqueda");
				
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data))	
					{
						$ls_denominacion=$row["denominacion"];
					}
					else
					{
						$ls_denominacion='';
					}
				}
				$li_lencta=strlen($this->la_empresa["formspi"]);
			}
		
			if(($li_i>=19)&&($li_i<= 21))
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico_re 
						 WHERE  sig_cuenta = '".$ls_cuenta."'";
				$rs_data = $this->io_sql->select($ls_sql);
		
				if($rs_data===false)
				{
					$this->io_msg_error	= "Error en búsqueda";
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$ls_denominacion= $row["denominacion"];
					}
					else
					{	
						$ls_denominacion='';
					}	
				}
				$li_lencta=strlen($this->la_empresa["formpre"]);
			}
		
			if(($li_i>=22)&&($li_i<= 26))
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico 
						 WHERE  sc_cuenta = '".$ls_cuenta."' ";
				$rs_data = $this->io_sql->select($ls_sql);		
				if($rs_data===false)
				{
					$this->is_msg_error="Error en búsqueda";
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$ls_denominacion=$row["denominacion"];
					}
					else
					{
						$ls_denominacion= '';
					}
				}
				$li_lencta=strlen($this->la_empresa["formcont"]);
			}
		
			if(($li_i>=27)&&($li_i<= 37))
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico_re 
						 WHERE  sig_cuenta = '".$ls_cuenta."'";
				$rs_data= $this->io_sql->select($ls_sql);
		
				if($rs_data===false)
				{
					$this->is_msg_error="Error en búsqueda";
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data))
					{
						$ls_denominacion=$row["denominacion"];
					}
					else	
					{
						$ls_denominacion = '';
					}
				}				
				$li_lencta=strlen($this->la_empresa["formpre"]);
			}
			$this->ds_cuentas->insertRow("sc_cuenta",$this->io_fun->uf_cerosderecha($ls_cuenta,$li_lencta));
			$this->ds_cuentas->insertRow("denominacion",$ls_denominacion);
			$this->ds_cuentas->insertRow("tipo",1);
			$this->ds_cuentas->insertRow("programado",0);
			$this->ds_cuentas->insertRow("programado_acum",0);
			$this->ds_cuentas->insertRow("saldo_ant",0);
			$this->ds_cuentas->insertRow("variacion",0);
			$this->ds_cuentas->insertRow("p1",0);
			$this->ds_cuentas->insertRow("p2",0);
			$this->ds_cuentas->insertRow("ejecutado",0);
			$this->ds_cuentas->insertRow("ejecutado_acum",0);
			$this->ds_cuentas->insertRow("prevproxmes",0);				
			$this->ds_cuentas->insertRow("nivel",'');
			$this->ds_cuentas->insertRow("s_ant",0);
			$this->ds_cuentas->insertRow("s_1",0);
			$this->ds_cuentas->insertRow("s_2",0);
			$this->ds_cuentas->insertRow("s_3",0);
			$this->ds_cuentas->insertRow("s_4",0);
			$this->ds_cuentas->insertRow("s_5",0);
			$this->ds_cuentas->insertRow("s_6",0);
			$this->ds_cuentas->insertRow("s_7",0);
			$this->ds_cuentas->insertRow("variacion_acum",0);
		}
		
		$this->ds_cuentas->insertRow("sc_cuenta",'');
		$this->ds_cuentas->insertRow("denominacion",'3. RESULTADO DEL EJERCICIO ');
		$this->ds_cuentas->insertRow("tipo",999);
		$this->ds_cuentas->insertRow("programado",0);
		$this->ds_cuentas->insertRow("programado_acum",0);
		$this->ds_cuentas->insertRow("saldo_ant",0);
		$this->ds_cuentas->insertRow("variacion",0);
		$this->ds_cuentas->insertRow("p1",0);
		$this->ds_cuentas->insertRow("p2",0);
		$this->ds_cuentas->insertRow("ejecutado",0);
		$this->ds_cuentas->insertRow("ejecutado_acum",0);
		$this->ds_cuentas->insertRow("prevproxmes",0);				
		$this->ds_cuentas->insertRow("nivel",'');
		$this->ds_cuentas->insertRow("s_ant",0);
		$this->ds_cuentas->insertRow("s_1",0);
		$this->ds_cuentas->insertRow("s_2",0);
		$this->ds_cuentas->insertRow("s_3",0);
		$this->ds_cuentas->insertRow("s_4",0);
		$this->ds_cuentas->insertRow("s_5",0);
		$this->ds_cuentas->insertRow("s_6",0);
		$this->ds_cuentas->insertRow("s_7",0);
		$this->ds_cuentas->insertRow("variacion_acum",0);			
	}

	function uf_procesa_cuenta_spi($ls_cuenta,$ld_fecini,$ld_fecfin)
	{
		$ld_inicio= $this->la_empresa["periodo"];
		$ldec_totalingresosejec  = 0;
		$ldec_totalingresosotros = 0;
		$ldec_totalegresosejec   = 0;
		$ldec_totalegresosotros  = 0;
		$ldec_programado		 = 0;
		$ldec_previsto			 = 0;
		$ls_formplan=str_replace("-","",$this->la_empresa["formspi"]);
		$li_len=strlen($ls_formplan);
		if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
		$ls_sql="SELECT spi_cuenta,denominacion 
				 FROM   spi_cuentas 
				 WHERE  substr(spi_cuenta,1,9)='".$ls_cuenta."'
				 ORDER BY spi_cuenta ";
		}
		else
		 {
		$ls_sql="SELECT spi_cuenta,denominacion 
				 FROM   spi_cuentas 
				 WHERE  substring(spi_cuenta,1,9)='".$ls_cuenta."'
				 ORDER BY spi_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error al cargar";
			///print "la cagaste";
		}
		else
		{
		 	while($row=$this->io_sql->fetch_row($rs_data))
			{					
				$ls_cta = $ls_cuenta;
				$lb_insertar = $this->uf_main_spi_programacion('0406', $ls_cta, &$lo_cuenta);
			
				$li_mesI = substr($ld_fecini,3,2);
				$li_mesF = substr($ld_fecfin,3,2);
				$this->uf_calcular_prog_acumulado($li_mesI,$li_mesF,$lo_cuenta,&$ldec_programado,&$ldec_previsto_act);
				$this->uf_calcular_prog_acumulado(1,$li_mesF,$lo_cuenta,&$ldec_programado_acum,&$ldec_previsto_acum);
				
				$this->uf_calcular_spi_acumulado($ls_cta,&$ldec_previsto,&$ldec_devengado,&$ldec_cobrado,&$ldec_cobrado_ant,&$ldec_aumento,&$ldec_disminucion,&$ldec_cobrado_acum,$ld_fecini,$ld_fecfin);
				//print $ldec_previsto;
				if(($ldec_programado==0)&&($ldec_cobrado==0))
				{
					$ldec_porc1=0;
				}
				else
				{
					//print "mes".$ldec_programado."  ".$ldec_cobrado."<br>";
					if($ldec_cobrado==0)
					{
						$ldec_porc1=100;
					}
					else
					{
						$ldec_porc1=$ldec_programado/($ldec_cobrado*100);
					}
				}
				if(($ldec_programado_acum==0)&&($ldec_cobrado_acum==0))
				{
					$ldec_porc2=0;
				}
				else
				{
					if($ldec_cobrado_acum==0)
					{
						$ldec_porc2=100;
					}
					else
					{
						$ldec_porc2=$ldec_programado_acum/($ldec_cobrado_acum*100);
					}
				}
				
				
				/*if($lb_insertar)
				{*/
					$li_rows=$this->ds_cuentas->getRowCount('sc_cuenta');
					for($li_i=1;$li_i<=$li_rows;$li_i++)
					{
						$ls_cuenta_temp=$this->ds_cuentas->getValue('sc_cuenta',$li_i);
						if(substr($ls_cuenta_temp,0,$li_len)==$ls_cta)
						{
							$this->ds_cuentas->updateRow('programado',$ldec_programado,$li_i);
							$this->ds_cuentas->updateRow('programado_acum',$ldec_programado_acum,$li_i);
							$this->ds_cuentas->updateRow('ejecutado',$ldec_cobrado,$li_i);
							$this->ds_cuentas->updateRow('ejecutado_acum',$ldec_cobrado_acum,$li_i);	
							$this->ds_cuentas->updateRow('prevproxmes',$ldec_previsto_act,$li_i);
							$this->ds_cuentas->updateRow('variacion',($ldec_programado-$ldec_cobrado),$li_i);
							$this->ds_cuentas->updateRow('variacion_acum',($ldec_programado_acum-$ldec_cobrado_acum),$li_i);
							$this->ds_cuentas->updateRow('p1',$ldec_porc1,$li_i);
							$this->ds_cuentas->updateRow('p2',$ldec_porc2,$li_i);
						}	
					}
				//}				
			}
		}
	}
	
	
	function uf_main_spi_programacion($ls_codreport,$ls_cta,$lo_data)
	{
		$lb_existe=false;
		$ls_sql="SELECT * 
				 FROM   spi_plantillacuentareporte
				 WHERE  cod_report='".$ls_codreport."' AND spi_cuenta='".$ls_cta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$this->is_msg_error="Error en main_spi_programacion ";
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lo_data=$row;
				$lb_existe=true;				
			}
		}
		return $lb_existe;
	}
	
	function uf_calcular_spi_acumulado($ls_cuenta,$ldec_previsto,$ldec_devengado,$ldec_cobrado,$ldec_cobrado_ant,$ldec_aumento,$ldec_disminucion,$ldec_cobrado_acum,$ld_fecdesde,$ld_fechasta)
	{
		
		$ls_sinceros=$this->int_spi->uf_spi_cuenta_sin_cero($ls_cuenta)."%";
		
		$ldec_previsto	=  0;
		$ldec_devengado	=  0;
		$ldec_cobrado 	=  0;
		$ldec_cobrado_ant= 0;
		$ldec_aumento  	=  0;
		$ldec_disminucion= 0;
		$ldec_cobrado_acum=0;
		
		$ls_sql="SELECT *
				 FROM  spi_dt_cmp 
				 WHERE spi_cuenta like '".$ls_sinceros."'";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_mensaje=$this->int_spi->uf_operacion_codigo_mensaje($row["operacion"]);
				$li_pos_i=strpos($as_mensaje,"I"); 
				if(!($li_pos_i===false))
				{
					$ldec_previsto=$ldec_previsto+$row["monto"];
				}		
				if(($this->io_fecha->uf_comparar_fecha($ld_fecdesde,$this->io_fun->uf_convertirfecmostrar($row["fecha"])))&&($this->io_fun->uf_convertirfecmostrar($row["fecha"])<=$ld_fechasta))
				{
					//*!*	E-Devengado
					$li_pos_e=strpos($as_mensaje,"E"); 
					if(!($li_pos_e===false))
					{
						$ldec_devengado=$ldec_devengado+$row["monto"];
					}		
					$li_pos_c=strpos($as_mensaje,"C"); 
					if(!($li_pos_c===false))
					{
						$ldec_cobrado=$ldec_cobrado+$row["monto"];
					}
					$li_pos_n=strpos($as_mensaje,"N"); 
					if(!($li_pos_n===false))
					{
						$ldec_cobrado_ant=$ldec_cobrado_ant+$row["monto"];
					}				
					$li_pos_a=strpos($as_mensaje,"A"); 
					if(!($li_pos_a===false))
					{
						$ldec_aumento=$ldec_aumento+$row["monto"];
					}		
					$li_pos_d=strpos($as_mensaje,"D"); 
					if(!($li_pos_d===false))
					{
						$ldec_disminucion=$ldec_disminucion+$row["monto"];
					}		
				}
		
				if($row["fecha"]<=$ld_fechasta)
				{	
					//*!*	C-Cobrado
					$li_pos_c=strpos($as_mensaje,"C"); 
					if(!($li_pos_c===false))
					{
						$ldec_cobrado_acum=$ldec_cobrado_acum+$row["monto"];
					}
	
				}
			}
		}
	}
	
	function uf_calcular_prog_acumulado($li_mesI,$li_mesF,$lo_cuenta,$ldec_valor,$ldec_previsto)
	{
		$ldec_valor = 0;
		
		$li_inipre = $li_mesF + 1;
		$li_finpre = $li_inipre + ($li_mesF-$li_mesI);
		if(!empty($lo_cuenta))
		{
			if((1>=$li_mesI)&&(1<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["enero"];
			}
			
			if((2>=$li_mesI)&&(2<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["febrero"];
			}
	
			if((3>=$li_mesI)&&(3<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["marzo"];
			}
			if((4>=$li_mesI)&&(4<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["abril"];
			}
			if((5>=$li_mesI)&&(5<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["mayo"];
			}		
			if((6>=$li_mesI)&&(6<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["junio"];
			}
			if((7>=$li_mesI)&&(7<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["julio"];
			}		
			if((8>=$li_mesI)&&(8<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["agosto"];
			}
			if((9>=$li_mesI)&&(9<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["septiembre"];
			}		
			if((10>=$li_mesI)&&(10<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["octubre"];
			}		
			if((11>=$li_mesI)&&(11<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["noviembre"];
			}		
			if((12>=$li_mesI)&&(12<=$li_mesF))
			{
				$ldec_valor = $ldec_valor + $lo_cuenta["diciembre"];
			}
			
			$ldec_previsto= 0;
			
			if((1>=$li_inipre)&&(1 <=$li_finpre))
			{
				$ldec_previsto=$ldec_previsto + $lo_cuenta["enero"];
			}
			if((2>=$li_inipre)&&(2<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["febrero"];
			}
	
			if((3>=$li_inipre)&&(3<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["marzo"];
			}
			if((4>=$li_inipre)&&(4<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["abril"];
			}
			if((5>=$li_inipre)&&(5<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["mayo"];
			}		
			if((6>=$li_inipre)&&(6<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["junio"];
			}
			if((7>=$li_inipre)&&(7<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["julio"];
			}		
			if((8>=$li_inipre)&&(8<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["agosto"];
			}
			if((9>=$li_inipre)&&(9<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["septiembre"];
			}		
			if((10>=$li_inipre)&&(10<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["octubre"];
			}		
			if((11>=$li_inipre)&&(11<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["noviembre"];
			}		
			if((12>=$li_inipre)&&(12<=$li_finpre))
			{
				$ldec_previsto = $ldec_previsto + $lo_cuenta["diciembre"];
			}		
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_select_saldo
	// 	   Access:  public
	//  Arguments:  as_sc_cuenta-> cuenta contable;as_fecha
	//	  Returns:  boolean
	//Description:  Este método indica si existe o no el saldo de la cuenta a una fecha específica.
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_select_saldo($as_cuenta,$as_fecdesde,$as_fechasta,$adec_debe,$adec_haber)
	{
	   $lb_existe= false;
	   $adec_debe=0;
	   $adec_haber=0;
	   $ls_fecdesde = $this->io_fun->uf_convertirdatetobd($as_fecdesde);	   
	   $ls_fechasta = $this->io_fun->uf_convertirdatetobd($as_fechasta);	   
	   $ls_sql = " SELECT SUM(debe_mes) as debe_mes,SUM(haber_mes) as haber_mes FROM scg_saldos ".
		           " WHERE codemp='".$this->la_empresa["codemp"]."' AND sc_cuenta='".$as_cuenta."' AND fecsal between '".$ls_fecdesde."' AND '".$ls_fechasta."'";
	   $rs_data=$this->io_sql->select($ls_sql);

	   if($rs_data===false)
		{
			 $this->is_msg_error = "Error en método uf_scg_select_saldo ";
			 print "error en consulta";
			 $lb_existe = false;		
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))	
			{
				$adec_debe=$row["debe_mes"];
				$adec_haber=$row["haber_mes"];
				$lb_existe=true;
			}
		}
        $this->io_sql->free_result($rs_data);				
		return $lb_existe;
	} // end function uf_scg_select_saldos()

	function uf_saldo_csm($ls_cuenta,$ld_fechacierre,$ldec_saldo)
	{
		$ldec_saldo = 0;
		$ld_fecini=$this->la_empresa["periodo"];			
		if(!$this-> uf_val_cuenta($ls_cuenta,&$ls_status))
		{
			$this->is_msg_error='La cuenta: '.$ls_cuenta.' no existe';
			return false;
		}		
		$ls_sql="SELECT sum(debe_mes) as total_debe, sum(haber_mes) as total_haber
				 FROM   scg_saldos
				 WHERE  sc_cuenta='".$ls_cuenta."' AND 
					    fecsal>= '".$ld_fecini."' AND fecsal <='".$this->io_fun->uf_convertirdatetobd($ld_fechacierre)."'";		
		$rs_data=$this->io_sql->select($ls_sql);		
		if($rs_data===false)
		{
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_saldo = $row["total_debe"]-$row["total_haber"];
			}
		}
		return true;
	}
	
	function uf_procesa_cuenta_resultado($ls_cuenta,$ld_fecdesde,$ld_fechasta)
	{
		$ld_enddate= substr($ld_fechasta,3,2);
		$ld_fecha  = date("d-m-Y", strtotime("$ld_fecdesde -1 month"));		
		$li_dant   = substr($ld_fecha,3,2);
		$ls_codemp = $this->la_empresa["codemp"];
		
		$ls_sql="SELECT *
				 FROM   scg_cuentas
				 WHERE  codemp='".$ls_codemp."' AND substring(sc_cuenta,1,9) = '".$ls_cuenta."' AND status='C'";
		$ls_formscg=str_replace("-","",$this->la_empresa["formcont"]);
		$li_len=strlen($ls_formscg);
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en metodo uf_procesar_cuenta_resultado";
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_cuenta_aux=$row["sc_cuenta"];	
				if($li_dant== 12)
				{
					$ldec_el_saldo = 0;
				}
				else
				{
					if(!$this->uf_saldo_csm($ls_cuenta_aux, $ld_fecha, &$ldec_el_saldo))
					{
						return false;
					}
				}
				$li_nivel=$row["nivel"];			
				if($this->uf_scg_select_saldo($ls_cuenta_aux,$ld_fecdesde,$ld_fechasta,&$ldec_debe,&$ldec_haber))
				{
					$ldec_diferencia=$ldec_debe-$ldec_haber;
				}
				else
				{	$ldec_diferencia=0;	}
			
				if($li_dant!=12)
				{
					$ldec_el_saldo=$ldec_el_saldo+$ldec_diferencia;
				}
			
				$li_signo = $this->io_fun->iif_string("'substr($ls_cuenta_aux,0,1)'=='".$this->la_empresa["gasto"]."'",1,-1);			
				$ldec_el_saldo   = $ldec_el_saldo*$li_signo;
				$ldec_diferencia = $ldec_diferencia*$li_signo;
				$ls_cuenta_aux   = $this->int_scg->uf_scg_next_cuenta_nivel($ls_cuenta_aux);
				$ldec_prog_varia = $this->uf_programado($ls_codemp,$ls_cuenta_aux,substr($ld_fecdesde,3,2),substr($ld_fechasta,3,2),'0406');
				$ldec_prog_acum  = $this->uf_programado($ls_codemp,$ls_cuenta_aux,01,substr($ld_fechasta,3,2),'0406');
				$ldec_saldo_ant = $this->uf_saldo_anterior($ls_codemp,$ls_cuenta_aux,$ld_fechasta)*$li_signo;
				$ldec_resta1= $ldec_diferencia-$ldec_prog_varia;
				$ldec_resta2= $ldec_el_saldo-$ldec_prog_acum;
				if(($ldec_prog_varia==0)&&($ldec_resta1==0))
				{
					$ldec_porc1=0;
				}
				else
				{
					if($ldec_resta1==0)
					{
						$ldec_porc1=100;
					}
					else
					{
						$ldec_porc1=$ldec_prog_varia/($ldec_resta1*100);
					}
				}
				if(($ldec_prog_acum==0)&&($ldec_resta2==0))
				{
					$ldec_porc2=0;
				}
				else
				{
					if($ldec_resta2==0)
					{
						$ldec_porc2=100;
					}
					else
					{
						$ldec_porc2=$ldec_prog_acum/($ldec_resta2*100);
					}
				}
				
				$ldec_repproxmes = $this->uf_programado($ls_codemp,$ls_cuenta_aux,substr($ld_fechasta,3,2)+1,substr($ld_fechasta,3,2)+$this->li_mes_prox,'0406');
				$li_rows=$this->ds_cuentas->getRowCount('sc_cuenta');
				for($li_i=1;$li_i<=$li_rows;$li_i++)
				{
					$ls_cuenta_temp=$this->ds_cuentas->getValue('sc_cuenta',$li_i);
			
					if(substr($ls_cuenta_temp,0,9)==substr($ls_cuenta_aux,0,9))
					{
						$this->ds_cuentas->updateRow('programado',$ldec_prog_varia,$li_i);
						$this->ds_cuentas->updateRow('programado_acum',$ldec_prog_acum,$li_i);
						$this->ds_cuentas->updateRow('ejecutado',$ldec_diferencia,$li_i);
						$this->ds_cuentas->updateRow('ejecutado_acum',$ldec_saldo_ant,$li_i);	
						$this->ds_cuentas->updateRow('prevproxmes',$ldec_repproxmes,$li_i);
						$this->ds_cuentas->updateRow('variacion',($ldec_prog_varia-$ldec_diferencia),$li_i);
						$this->ds_cuentas->updateRow('variacion_acum',($ldec_prog_acum-$ldec_saldo_ant),$li_i);
						$this->ds_cuentas->updateRow('p1',$ldec_porc1,$li_i);
						$this->ds_cuentas->updateRow('p2',$ldec_porc2,$li_i);
					}
				}
			}
		}
	}
	
	function uf_val_cuenta($ls_cuenta,$ls_status)
	{
		$lb_existe=false;
		$ls_sql="SELECT sc_cuenta,status,denominacion 
				 FROM   scg_cuentas 
				 WHERE  sc_cuenta = '".$ls_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_status=$row["status"];				
				$lb_existe=true;
			}
		}		
		return $lb_existe;
	}	
	
	function uf_procesa_cuenta_spg($li_nivel,$ls_cuenta_spg,$ld_fecini,$ld_fecfin)
	{
		$ld_periodo = $this->la_empresa["periodo"];
		
		$ldec_totingejec  = 0;
		$ldec_totingotros = 0;
		$ldec_totegreejec = 0;
		$ldec_totegreotros= 0;
		$ldec_programado  = 0;
		$ldec_previsto	  = 0;
		$ldec_programado  = 0;
		$ldec_previsto    = 0;
		$ldec_programado_acum=0;
		$ldec_previsto_acum=0;
		$ls_sql="SELECT DISTINCT spg_cuenta,denominacion 
			     FROM   spg_cuentas 
				 WHERE  spg_cuenta like '".$ls_cuenta_spg."%'
				 ORDER BY spg_cuenta ";
				 
		$rs_data=$this->io_sql->select($ls_sql);
		$ls_formplan=str_replace("-","",$this->la_empresa["formpre"]);
		$li_len=strlen($ls_formplan);
		if($rs_data===false)
		{
			$this->is_msg_error="Error al cargar";
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_cuenta=$row["spg_cuenta"];
				$lb_insertar = $this->uf_main_spg_programacion('0406', '000000000000000000000000000000000', $ls_cuenta, &$lo_cuenta);
				if(!empty($lo_cuenta))
				{
					$li_mesI = substr($ld_fecini,3,2);
					$li_mesF = substr($ld_fecfin,3,2);
					$this->uf_calcular_prog_acumulado( $li_mesI, $li_mesF, $lo_cuenta, &$ldec_programado, &$ldec_previsto);
					$this->uf_calcular_prog_acumulado( 1, $li_mesF, $lo_cuenta, &$ldec_programado_acum, &$ldec_previsto_acum);
				}
				$this->uf_calcular_ejecutado_periodo($ls_cuenta,"O",&$ldec_ejecutado_mes,&$ldec_ejecutado_acum,&$ldec_aumdismes,&$ldec_aumdisacum,&$ldec_comprometido,&$ldec_causado,&$ldec_pagado,&$ld_fecini,&$ld_fecfin,&$ldec_comprometidoacum);
				if(($ldec_programado==0)&&($ldec_comprometido==0))
				{
					$ldec_por1=0;
				}
				else
				{
					if($ldec_comprometido==0)
					{
						$ldec_por1=100;
					}
					else
					{
						$ldec_por1=$ldec_programado/($ldec_comprometido*100);	
					}				
				}
				if(($ldec_programado_acum==0)&&($ldec_comprometidoacum==0))
				{
					$ldec_por2=0;
				}
				else
				{
					if($ldec_comprometidoacum==0)
					{
						$ldec_por2=100;
					}
					else
					{
						$ldec_por2=$ldec_programado_acum/($ldec_comprometidoacum*100);	
					}				
				}
			
				/*if ($lb_insertar)
				{*/
					$li_rows=$this->ds_cuentas->getRowCount('sc_cuenta');
					for($li_i=1;$li_i<=$li_rows;$li_i++)
					{
						$ls_cuenta_temp=$this->ds_cuentas->getValue('sc_cuenta',$li_i);
						//print substr($ls_cuenta_temp,0,$li_len)."   ".$ls_cuenta."<br>";
						if(substr($ls_cuenta_temp,0,$li_len)==$ls_cuenta)
						{
							$this->ds_cuentas->updateRow('programado',$ldec_programado,$li_i);
							$this->ds_cuentas->updateRow('programado_acum',$ldec_programado_acum,$li_i);
							$this->ds_cuentas->updateRow('ejecutado',$ldec_comprometido,$li_i);
							$this->ds_cuentas->updateRow('ejecutado_acum',$ldec_comprometidoacum,$li_i);	
							$this->ds_cuentas->updateRow('prevproxmes',$ldec_previsto,$li_i);
							$this->ds_cuentas->updateRow('variacion',($ldec_programado-$ldec_comprometido),$li_i);
							$this->ds_cuentas->updateRow('variacion_acum',($ldec_programado_acum-$ldec_comprometidoacum),$li_i);
							$this->ds_cuentas->updateRow('p1',$ldec_por1,$li_i);
							$this->ds_cuentas->updateRow('p2',$ldec_por2,$li_i);
						}	
					}
				//}				
			}					
		}				
	}
	
	function uf_main_spg_programacion($ls_codreport,$ls_estpro,$ls_spgcuenta,$lo_cuenta)
	{
		$ls_sql="SELECT * 
				 FROM   spg_plantillareporte
				 WHERE  codrep='".$ls_codreport."' AND spg_cuenta='".$ls_spgcuenta."'";
		$lo_cuenta=array();
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			print "Error en metodo spg_cuentas_report";
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))	
			{
				$lo_cuenta=$row;
			}
		}		
	}


	function uf_calcular_ejecutado_periodo($ls_cuenta,$ls_tipo,$ldec_ejecutado_mes,$ldec_ejecutado_acum,$ldec_aumdismes,$ldec_aumdisacum,$ldec_comprometido,$ldec_causado,$ldec_pagado,$ld_fecini,$ld_fecfin,$ldec_comprometidoacum)
	{		
		$ls_cuentacSinCeros = $this->int_spg->uf_spg_cuenta_sin_cero($ls_cuenta)."%";
		$ldec_ejecutado_mes =0;
		$ldec_ejecutado_acum=0;
		$ldec_aumdismes     =0;
		$ldec_aumdisacum    =0;
		$ldec_comprometido  =0;
		$ldec_causado       =0;
		$ldec_pagado        =0;
		$ldec_comprometidoacum=0;
		$ldec_aum			=0;
		$ldec_dis			=0;
		$ldec_aumacum		=0;
		$ldec_disacum		=0;
		
		$ld_mesfrom = substr($ld_fecini,3,2);
		$ld_mesto   = substr($ld_fecfin,3,2);
		
		$ls_mesfrom = substr($this->la_empresa["periodo"],0,4).$ld_mesfrom;
		$ls_mesto   = substr($this->la_empresa["periodo"],0,4).$ld_mesto;
		
		$ld_trimfrom_ant = $this->io_fun->iif_string("'$ld_mesfrom'>'3'",$this->io_fun->uf_cerosizquierda(($ld_mesfrom-3),2)."-".substr($this->la_empresa["periodo"],0,4)."-01",substr($ls_mesfrom,0,4)."-".substr($ls_mesfrom,4,2)."-01");
		$ld_trimto_ant   = $this->io_fun->iif_string("'$ld_mesfrom'>'3'",$this->io_fecha->uf_last_day($this->io_fun->uf_cerosizquierda(($ld_mesfrom-1),2),substr($this->la_empresa["periodo"],0,4)),$this->io_fecha->uf_last_day(substr($ls_mesto,4,2),substr($ls_mesto,0,4)));
		
		$ls_sql="SELECT	a.fecha,a.monto,b.aumento,b.disminucion,b.precomprometer,b.comprometer,b.causar,b.pagar
				 FROM 	spg_dt_cmp a,spg_operaciones b
				 WHERE	a.codemp='".$this->la_empresa["codemp"]."' AND (a.operacion = b.operacion) AND a.spg_cuenta like '".$ls_cuentacSinCeros."'";  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				if($ls_tipo=='O')
				{
					if($row["comprometer"]==1)
					{
						if(($this->io_fecha-> uf_comparar_fecha($ld_fecini,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin)))
						{
							$ldec_ejecutado_mes=$ldec_ejecutado_mes+$row["monto"];
						}						
						if($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin))
						{
							$ldec_ejecutado_acum=$ldec_ejecutado_acum+$row["monto"];	
						}
					}	
				}				
				if($ls_tipo=='C')
				{
					if($row["causar"]==1)
					{
						if(($this->io_fecha-> uf_comparar_fecha($ld_fecini,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin)))
						{
							$ldec_ejecutado_mes=$ldec_ejecutado_mes+$row["monto"];
						}						
						if($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin))
						{
							$ldec_ejecutado_acum=$ldec_ejecutado_acum+$row["monto"];	
						}
					}	
				}				
								
				//*----------------- comprometido, causado y pagado
				//*nCompr,nCaus,nEjec
				if($row["comprometer"]==1)
				{
					if(($this->io_fecha-> uf_comparar_fecha($ld_trimfrom_ant,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_trimto_ant)))
					{
						$ldec_comprometido=$ldec_comprometido+$row["monto"];						
					}					
				}	
				if($row["causar"]==1)
				{
					if(($this->io_fecha-> uf_comparar_fecha($ld_fecini,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin)))
					{
						$ldec_causado=$ldec_causado+$row["monto"];
					}						
				}
				if($row["pagar"]==1)
				{
					if(($this->io_fecha-> uf_comparar_fecha($ld_fecini,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin)))
					{
						$ldec_pagado=$ldec_pagado+$row["monto"];
					}						
				}
				if($row["aumento"]==1)
				{
					if(($this->io_fecha-> uf_comparar_fecha($ld_fecini,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin)))
					{
						$ldec_aum=$row["monto"];
					}						
				}				
				if($row["disminucion"]==1)
				{
					if(($this->io_fecha-> uf_comparar_fecha($ld_fecini,$row["fecha"]))&&($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin)))
					{
						$ldec_dis=$row["monto"];
					}						
				}
				$ldec_aumdismes=$ldec_aum-$ldec_dis;
				if($row["aumento"]==1)
				{
					if($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin))
					{
						$ldec_aumacum=$row["monto"];
					}						
				}				
				if($row["disminucion"]==1)
				{
					if($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_fecfin))
					{
						$ldec_disacum=$row["monto"];
					}						
				}				
				$ldec_aumdisacum=$ldec_aumacum-$ldec_disacum;
				
				if($row["comprometer"]==1)
				{
					if($this->io_fecha-> uf_comparar_fecha($row["fecha"],$ld_trimto_ant))
					{
						$ldec_comprometidoacum=$ldec_comprometidoacum+$row["monto"];
					}						
				}
			}		
		}		
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////ORIGEN Y APLICACION DE FONDOS//////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_crear_reporte_oaf($ai_nivel,$ld_fecdesde,$ld_fechasta,$li_agno)
	{
		$ls_cuenta_ar="";
		$this->ds_reporte->insertRow("etapa",'A');		
		$this->ds_reporte->insertRow("status",' ');
		$this->ds_reporte->insertRow("sc_cuenta",'');
		$this->ds_reporte->insertRow("denominacion",'AUTOFINANCIAMIENTO');
		$this->ds_reporte->insertRow("nivel",1);
		$this->ds_reporte->insertRow("programado",0);
		$this->ds_reporte->insertRow("ejecutado",0);
		$this->ds_reporte->insertRow("variacion",0);
		$this->ds_reporte->insertRow("porc_variacion",0);
		$this->ds_reporte->insertRow("prevision",0);		
		$this->ds_reporte->insertRow("programado_acum",0);
		$this->ds_reporte->insertRow("ejecutado_acum",0);
		$this->ds_reporte->insertRow("variacion_acum",0);
		$this->ds_reporte->insertRow("porc_acum",0);
		
		$lb_valido=$this->uf_oaf_origen_de_fondos('B',$ld_fecdesde,$ld_fechasta,&$ldec_ejecutado_a);
		/*if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_inc_de_patrimonio('B',$ld_fecdesde,$ld_fechasta,&$ldec_ejecutado_f);
		//}
		/*if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'C');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'TOTAL ORIGEN DE FONDOS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",$ldec_ejecutado_a+$ldec_ejecutado_f);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
			
			$this->ds_reporte->insertRow("etapa",'D');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'APLICACIONES');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		//}
		/*if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_activos_reales('E',$ld_fecdesde,$ld_fechasta,&$ldec_ejecutado_b,&$ls_cuenta_ar);
		//}		
		/*if(!(substr($ls_cuenta_ar,0,5)=='40405'))
		{
			if($lb_valido)
			{*/
				$lb_valido = $this->uf_oaf_activos_reales_otros($ld_fecdesde,$ld_fechasta,&$ldec_ejecutadootros);
			/*}
		}
		else
		{*/
			//$ldec_ejecutadootros= 0;
		/*}
		if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'F');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'OTROS ACTIVOS REALES');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",$ldec_ejecutadootros);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			
			$this->ds_reporte->insertRow("porc_acum",0);
			$this->ds_reporte->insertRow("etapa",'G');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'ACTIVOS FINANCIEROS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		/*}
		if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_activos_financieros('H',$ld_fecdesde,$ld_fechasta,&$ldec_totaleje_c);			
		/*}
		if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_servicio_deuda_publica('K', $ld_fecdesde,$ld_fechasta,&$ldec_totaleje_d);
		/*}
		if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_dism_resultados('N',$ld_fecdesde,$ld_fechasta,&$ldec_totaleje_e);
		/*}
		if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'P');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'OTRAS APLICACIONES FINANCIERAS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
			
			$this->ds_reporte->insertRow("etapa",'Q');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'TOTAL APLICACION DE FONDOS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",$ldec_ejecutadootros+$ldec_ejecutado_b+$ldec_totaleje_c+$ldec_totaleje_d+$ldec_totaleje_e);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		/*}	
		if($lb_valido)
		{*/
			$lb_valido=$this->uf_oaf_variacion();
		/*}*/
		$li_totrows=$this->ds_reporte->getRowCount("sc_cuenta");
		for($li_a=1;$li_a<=$li_totrows;$li_a++)
		{
			$li_nivel = $this->ds_reporte->getValue("nivel",$li_a);
			$ls_status= $this->ds_reporte->getValue("status",$li_a);
			if(($li_nivel>$ai_nivel)||($ls_status=='I'))
			{
				$this->ds_reporte->deleteRow("sc_cuenta",$li_a);
			}			
		}		
		$this->ds_reporte2->data=$this->ds_reporte->data;
		$this->ds_reporte->reset_ds();
		
		if(substr($ld_fechasta,3,2)==1)
		{
			$ld_fecha=date("d-m-Y", strtotime("$ld_fecdesde -1 month"));	
			$li_year=substr($ld_fecha,0,4);
			$li_mes=1;
			$li_mes=$this->io_fun->uf_cerosizquierda($li_mes,2);			
		}
		else
		{
			$ld_fecha=date("d-m-Y", strtotime("$ld_fecdesde -1 month"));	
			$li_year=substr($ld_fecha,0,4);
			$li_mes=substr($ld_fecdesde,3,2);
			$li_mes=$this->io_fun->uf_cerosizquierda($li_mes,2);		
		}
		$ld_fecdesde="01/".$li_mes."/".$li_year;		
	/*	if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'A');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'AUTOFINANCIAMIENTO');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);	
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		/*}*/
	
		$lb_valido=$this->uf_oaf_origen_de_fondos('B',$ld_fecdesde,$ld_fechasta,&$ldec_ejecutado_a);		
		/*if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_inc_de_patrimonio('B',$ld_fecdesde,$ld_fechasta,&$ldec_ejecutado_f);
		/*}	
		if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'C');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'TOTAL ORIGEN DE FONDOS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",$ldec_ejecutado_a+$ldec_ejecutado_f);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		
			$this->ds_reporte->insertRow("etapa",'D');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'APLICACIONES');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
	//	}
	
		/*if($lb_valido)
		{*/
			$lb_valido=$this->uf_oaf_activos_reales('E',$ld_fecdesde,$ld_fechasta,&$ldec_ejecutado_b,&$ls_cuenta_ar);
		//}
		/*if(!(substr($ls_cuenta_ar,0,5)=='40405'))
		{		
			/*if($lb_valido)
			{*/
				$lb_valido=$this->uf_oaf_activos_reales_otros($ld_fecdesde,$ld_fechasta,&$ldec_ejecutadootros);
			//}
		/*}
		else
		{*/
		//	$ldec_ejecutadootros= 0;
		/*}
		if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'F');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'OTROS ACTIVOS REALES');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",$ldec_ejecutadootros);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		
			$this->ds_reporte->insertRow("etapa",'G');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'ACTIVOS FINANCIEROS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		//}

		/*if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_activos_financieros('H',$ld_fecdesde,$ld_fechasta,&$ldec_totaleje_c);			
		/*}
		if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_servicio_deuda_publica('K', $ld_fecdesde,$ld_fechasta,&$ldec_totaleje_d);
		/*}
		if($lb_valido)
		{*/
			$lb_valido = $this->uf_oaf_dism_resultados('N',$ld_fecdesde,$ld_fechasta,&$ldec_totaleje_e);
		/*}
		if($lb_valido)
		{*/
			$this->ds_reporte->insertRow("etapa",'P');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'OTRAS APLICACIONES FINANCIERAS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",0);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
			
			$this->ds_reporte->insertRow("etapa",'Q');		
			$this->ds_reporte->insertRow("status",' ');
			$this->ds_reporte->insertRow("sc_cuenta",'');
			$this->ds_reporte->insertRow("denominacion",'TOTAL APLICACION DE FONDOS');
			$this->ds_reporte->insertRow("nivel",1);
			$this->ds_reporte->insertRow("programado",0);
			$this->ds_reporte->insertRow("ejecutado",$ldec_ejecutadootros+$ldec_ejecutado_b+$ldec_totaleje_c+$ldec_totaleje_d+$ldec_totaleje_e);
			$this->ds_reporte->insertRow("variacion",0);
			$this->ds_reporte->insertRow("porc_variacion",0);
			$this->ds_reporte->insertRow("prevision",0);
			$this->ds_reporte->insertRow("programado_acum",0);
			$this->ds_reporte->insertRow("ejecutado_acum",0);
			$this->ds_reporte->insertRow("variacion_acum",0);
			$this->ds_reporte->insertRow("porc_acum",0);
		//}

		/*if($lb_valido)
		{*/
			$lb_valido=$this->uf_oaf_variacion();
		//}
		$li_totrows=$this->ds_reporte->getRowCount("sc_cuenta");
		for($li_a=1;$li_a<=$li_totrows;$li_a++)
		{
			$li_nivel = $this->ds_reporte->getValue("nivel",$li_a);
			$ls_status= $this->ds_reporte->getValue("status",$li_a);
			if(($li_nivel>$ai_nivel)||($ls_status=='I'))
			{
				$this->ds_reporte->deleteRow("sc_cuenta",$li_a);
			}			
		}
		$li_totrows=$this->ds_reporte2->getRowCount("sc_cuenta");
		for($li_b=1;$li_b<=$li_totrows;$li_b++)
		{
			$ls_cuenta=$this->ds_reporte2->getValue("sc_cuenta",$li_b);
			if(!empty($ls_cuenta))
			{
				$la_find=array('sc_cuenta'=>$ls_cuenta);
				$li_pos=$this->ds_reporte->findValues($la_find,"sc_cuenta");
				if($li_pos>0)
				{
					$ldec_prog_acum=$this->ds_reporte->getValue("programado_acum",$li_pos);
					$ldec_ejec_acum=$this->ds_reporte->getValue("ejecutado_acum",$li_pos);
					$ldec_var_acum=$this->ds_reporte->getValue("variacion",$li_pos);
					$ldec_porc_acum=$this->ds_reporte->getValue("porc_variacion",$li_pos);
					$this->ds_reporte2->updateRow("programado_acum",$ldec_prog_acum,$li_b);
					$this->ds_reporte2->updateRow("ejecutado_acum",$ldec_ejec_acum,$li_b);
					$this->ds_reporte2->updateRow("variacion_acum",$ldec_prog_acum,$li_b);
					$this->ds_reporte2->updateRow("porc_acum",$ldec_ejec_acum,$li_b);					
				}
			}
		}
		$this->ds_reporte->data=$this->ds_reporte2->data;
		return true;
	}	
	
	function uf_oaf_variacion()
	{
		$li_totrows=$this->ds_reporte->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{	
			$ldec_ejecutado=$this->ds_reporte->getValue("ejecutado",$li_z);
			$ldec_programado=$this->ds_reporte->getValue("programado",$li_z);
			$ldec_variacion=$this->uf_val_variacion($ldec_ejecutado,$ldec_programado);
			$this->ds_reporte->updateRow("variacion",$ldec_variacion,$li_z);
			if($ldec_programado==0)
			{
				$ldec_porc=0;
			}
			else
			{
				$ldec_porc=($ldec_ejecutado*100)/$ldec_programado;
			}
			$this->ds_reporte->updateRow("porc_variacion",$ldec_porc,$li_z);				
		}		
	}
	
	function uf_val_variacion($ldec_eje,$ldec_prog)
	{
		$ldec_var = 0;
		switch(true){
			case ($ldec_prog>0)&&($ldec_prog>$ldec_eje):
				$ldec_var= 0-($ldec_prog-$ldec_eje);
				break;
			case ($ldec_prog=0)&&($ldec_prog<$ldec_eje):
				$ldec_var = $ldec_eje;
				break;
			case ($ldec_prog>0)&&($ldec_prog<$ldec_eje):
				$ldec_var = abs($ldec_prog-$ldec_eje);
				break;
			default:
				$ldec_var = $ldec_prog-$ldec_eje;
		}
		return $ldec_var;
	}
	
	
	function uf_oaf_dism_resultados($ls_etapa,$ld_fecini,$ld_fecfin,$ldec_totalejec)
	{
		$ldec_totalejec = 0;
		$lb_valido= $this->uf_oaf_cur_rep('E');
		$li_totrows=$this->ds_currep->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{			
			$lo_cuenta = $this->ds_currep->getRow($li_z);
			$lb_valido = $this->uf_calcular_total_cta_ref($lo_cuenta["cta_res"],$ld_fecini,$ld_fecfin,&$ldec_saldo);
			if($ldec_saldo<0)
			{
				$ldec_saldo=0;
			}
			if($lb_valido)
			{
				$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
				$ldec_totalejec = $ldec_totalejec + $ldec_saldo;
			}
		}		
		return $lb_valido;
	}
	
	
	function uf_oaf_servicio_deuda_publica($ls_etapa,$ld_fecini,$ld_fecfin,$ldec_totalejec)
	{
		$ldec_totalejec = 0;
		$lb_valido=$this->uf_oaf_cur_rep('D');
		$li_totrows=$this->ds_currep->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{			
			$lo_cuenta = $this->ds_currep->getRow($li_z);
			$lb_valido = $this->uf_calcular_total_cta_ref($lo_cuenta["cta_res"],$ld_fecini,$ld_fecfin,&$ldec_saldo);
			if(($lb_valido)&&($ldec_saldo>=0))
			{
				$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
				$ldec_totalejec = $ldec_totalejec + $ldec_saldo;
			}
		}		
		return $lb_valido;
	}
	
	function uf_oaf_activos_financieros($ls_etapa,$ld_fecini,$ld_fecfin,$ldec_totalejec)
	{
		$ldec_totalejec = 0;
		$lb_valido = $this->uf_oaf_cur_rep('C');
		$li_totrows=$this->ds_currep->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{			
			$lo_cuenta = $this->ds_currep->getRow($li_z);
			$lb_valido = $this->uf_calcular_total_cta_ref($lo_cuenta["cta_res"],$ld_fecini,$ld_fecfin,&$ldec_saldo);
			if(($lb_valido)&&($ldec_saldo>=0))
			{
				$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
				$ldec_totalejec = $ldec_totalejec + $ldec_saldo;
			}
		}		
		return $lb_valido;
	}
		
	function uf_oaf_activos_reales_otros($ld_fecini,$ld_fecfin,&$ldec_ejecutadootros)
	{
		$ls_sql="SELECT SUM(PMV.monto) as total 
				 FROM spg_dt_cmp PMV,spg_operaciones POP 
				 WHERE (PMV.operacion=POP.operacion) AND (POP.causar=1) AND (PMV.spg_cuenta like '404%' AND 
				        PMV.spg_cuenta not like '40401%' and PMV.spg_cuenta not like '40402%' AND 
					    PMV.spg_cuenta not like '40403%' and PMV.spg_cuenta not like '40404%') AND fecha between '".$ld_fecini."' AND '".$ld_fecfin."'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))			
			{
				$ldec_ejecutadootros=$row["total"];
			}
			else
			{
				$ldec_ejecutadootros=0;
			}
			$this->io_sql->free_result($rs_data);
		}		
	}
	
	function uf_oaf_activos_reales($ls_etapa,$ld_fecini,$ld_fecfin,$ldec_ejecutado_b,$ls_cuenta_ar)
	{
		$ldec_ejecutado_b = 0;
		
		$lb_valido = $this->uf_oaf_cur_rep('B');
		$ls_cuenta='123456789';
		$li_totrows=$this->ds_currep->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{			
			$lo_cuenta = $this->ds_currep->getRow($li_z);
			$lb_valido = $this->uf_oaf_calcular_total_ctaspg($lo_cuenta["sc_cuenta"], $ld_fecini,$ld_fecfin,&$ldec_saldo);
			if(($lb_valido)&&($ldec_saldo>=0))
			{
				$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
				$ldec_ejecutado = $ldec_ejecutado + ($ldec_saldo);
			}
			$ls_cuenta_ar=$this->uf_sin_ceros($lo_cuenta["sc_cuenta"]); 
		}
		return $lb_valido;
	}
	
	function uf_oaf_inc_de_patrimonio($ls_etapa,$ld_fecini,$ld_fecfin,$ldec_totalejec)
	{
		$ldec_totalejec = 0;
		$lb_valido=$this->uf_oaf_cur_rep("F");
		$li_totrows=$this->ds_currep->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{			
			$lo_cuenta=$this->ds_currep->getRow($li_z);
			if($lo_cuenta["sc_cuenta"]=='309030100')
			{
				$lb_valido = $this->uf_calcular_total_cta_ref($lo_cuenta["cta_res"],$ld_fecini,$ld_fecfin,&$ldec_saldo);
				if(($lb_valido)&&($ldec_saldo<=0))
				{
					$ldec_saldo = abs($ldec_saldo);
					$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
					$ldec_totalejec = $ldec_totalejec + $ldec_saldo;
				}
			}
			if($lo_cuenta["sc_cuenta"]=='309030200')
			{
				$lb_valido = $this->uf_oaf_dism_res_ganancia($ld_fecini,$ld_fecfin,&$ldec_saldo);
				if($lb_valido)
				{
					$ldec_saldo = abs($ldec_saldo);
					$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
					$ldec_totalejec = $ldec_totalejec + $ldec_saldo;
				}
			}
		}
		return $lb_valido;
	}
	
	function uf_oaf_dism_res_ganancia($ld_fecini,$ld_fecfin,$ldec_ganancia)
	{
		$ls_ingreso =$this->la_empresa["ingreso"];
		$ls_gasto	=$this->la_empresa["gasto"];		
		$ld_fecini = $this->io_fun->uf_convertirdatetobd($ld_fecini);
		$ld_fecfin = $this->io_fun->uf_convertirdatetobd($ld_fecfin);		
		$ldec_ganancia = 0;
		$ls_sql=" SELECT SUM(SA.debe_mes-SA.haber_mes) as saldo 
				  FROM scg_cuentas SC,scg_saldos  SA
				  WHERE (SC.sc_cuenta = SA.sc_cuenta) AND SC.status='C' AND 
				       (SA.fecsal between '".$ld_fecini."' AND '".$ld_fecfin."') AND SC.sc_cuenta like '".$ls_ingreso."%' ";
		
		$rs_data = $this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_saldo=$row["saldo"];
			}
			else
			{
				$ldec_saldo=0;
			}
			$this->io_sql->free_result($rs_data);
		}	
		$ldec_ganancia = $ldec_saldo;
		$ldec_saldo=0;
		$ls_sql="SELECT sum(SA.debe_mes-SA.haber_mes) as saldo 
				 FROM   scg_cuentas SC,scg_saldos SA
				 WHERE (SC.sc_cuenta = SA.sc_cuenta) AND SC.status='C' AND 
				 	   (SA.fecsal between '".$ld_fecini."' AND '".$ld_fecfin."' AND 
						SC.sc_cuenta like '".$ls_gasto."%'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ldec_saldo=$row["saldo"];
			}
			else
			{
				$ldec_saldo=0;
			}
			$this->io_sql->free_result($rs_data);
		}
			
		$ldec_ganancia=$ldec_ganancia + $ldec_saldo;
		$ldec_ganancia = ($ldec_ganancia * -1);
	}
	
	
	function uf_oaf_origen_de_fondos($ls_etapa,$ld_fecini,$ld_fecfin,$ldec_totalejec)
	{
		$lb_valido=true;
		$ldec_totalejec = 0;
		$lb_valido = $this->uf_oaf_cur_rep("A");
		$li_totrows=$this->ds_currep->getRowCount("sc_cuenta");
		for($li_z=1;$li_z<=$li_totrows;$li_z++)
		{			
			$lo_cuenta=$this->ds_currep->getRow($li_z);
			$lb_valido = $this->uf_calcular_total_cta_ref($lo_cuenta["cta_res"],$ld_fecini,$ld_fecfin,&$ldec_saldo);			
			if(($lb_valido)&&($ldec_saldo <= 0))
			{
				$ldec_saldo = abs($ldec_saldo);
				$lb_valido=$this->uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo);
				$ldec_totalejec = $ldec_totalejec + $ldec_saldo;
			}
		}		
		return $lb_valido;
	}

	function uf_oaf_cur_rep($ls_grupo)
	{
		$ls_sql="SELECT sc_cuenta, denominacion, status, asignado, distribuir, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia, no_fila, tipo, cta_res 
				 FROM   scg_pc_reporte
				 WHERE  cod_report='0409' AND status='C' AND tipo='".$ls_grupo."'
				 ORDER	by no_fila, sc_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print "Error en select cur rep";
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_currep->data=$this->io_sql->obtener_datos($rs_data);
				$lb_valido=true;
			}
			else
			{
				$lb_valido=true;
			}			
		}		
		return $lb_valido;
	}

	function uf_calcular_total_cta_ref($ls_cuenta,$ld_fecini,$ld_fecfin,$ldec_saldo)
	{
		$lb_valido=true;
		if(empty($ls_cuenta))
		{
			$ldec_saldo = 0;
			return true;
		}		
		$ls_sc_cuenta = $this->int_scg->uf_scg_sin_ceros($ls_cuenta).'%';
		//--SALDO ANTERIOR		
		//---Total Debe
		$ls_sql = "SELECT SUM(monto) as total 
				   FROM scg_dt_cmp
				   WHERE debhab='D' AND sc_cuenta like '".$ls_sc_cuenta."' AND fecha < '".$this->io_fun->uf_convertirdatetobd($ld_fecini)."'" ;
		$rs_data1=$this->io_sql->select($ls_sql);
		if($rs_data1===false)
		{
			print "Error en uf_calcular_total_cta_ref  1";
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data1))
			{
				$ldec_total_debe=$row["total"];
			}	
			else
			{
				$ldec_total_debe=0;
			}
			//$this->io_sql->free_result($rs_data1);			
		}		
		//--Total Haber
		$ls_sql =" SELECT SUM(monto) as total 
				   FROM scg_dt_cmp
				   WHERE debhab='H' AND sc_cuenta like '".$ls_sc_cuenta."' AND fecha < '".$this->io_fun->uf_convertirdatetobd($ld_fecini)."'";
		$rs_data2=$this->io_sql->select($ls_sql);
		if($rs_data2===false)
		{
			print "Error en uf_calcular_total_cta_ref 2";
			return false;
		}
		else
		{	
			if($row=$this->io_sql->fetch_row($rs_data2))
			{
				$ldec_total_haber=$row["total"];				
			}
			else
			{
				$ldec_total_haber=0;	
			}
			//$this->io_sql->free_result($rs_data2);
		}		
		$ldec_saldoant = ($ldec_total_debe - $ldec_total_haber);		
		//--SALDO ACTUAL		
		//---Total Debe
		$ls_sql = "SELECT SUM(monto) as total 
				   FROM scg_dt_cmp
				   WHERE debhab='D' AND sc_cuenta like '".$ls_sc_cuenta."' AND fecha <= '".$this->io_fun->uf_convertirdatetobd($ld_fecfin)."'";

		$rs_data3=$this->io_sql->select($ls_sql);
		if($rs_data3===false)
		{
			print "Error en uf_calcular_total_cta_ref  3";
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data3))
			{
				$ldec_total_debe=$row["total"];
			}	
			else
			{
				$ldec_total_debe=0;
			}
			//$this->io_sql->free_result($rs_data3);	
		}		
		//---Total Haber
		$ls_sql = "SELECT SUM(monto) as total 
				   FROM scg_dt_cmp
				   WHERE debhab='H' AND sc_cuenta like '".$ls_sc_cuenta."' AND fecha <= '".$this->io_fun->uf_convertirdatetobd($ld_fecfin)."'";
		
		$rs_data4=$this->io_sql->select($ls_sql);
		if($rs_data4===false)
		{
			print "Error en uf_calcular_total_cta_ref  4".$this->io_sql->message;
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data4))
			{
				$ldec_total_haber=$row["total"];				
			}
			else
			{
				$ldec_total_haber=0;	
			}
			//$this->io_sql->free_result($rs_data4);
		}		
		$ldec_saldoact = ($ldec_total_debe - $ldec_total_haber);		
		$ldec_saldo = ($ldec_saldoact - $ldec_saldoant);		
		return true;
	}
	
	function uf_oaf_insert_cuenta($ls_etapa,$ld_fecini,$ld_fecfin,$lo_cuenta,$ldec_saldo)
	{
		$lb_valido = true;
		$ls_next=$lo_cuenta["sc_cuenta"];
		$li_nivel = $this->uf_obtener_nivel($ls_next);
		while(($li_nivel>=1)&&($lb_valido))
		{
			$la_find=array('etapa'=>$ls_etapa,'sc_cuenta'=>$ls_next);
			$li_row=$this->ds_reporte->findValues($la_find,'sc_cuenta');
			if($li_row>0)
			{
				$lb_valido=$this->uf_oaf_cuenta_update($ls_etapa,$ls_next,$ldec_saldo,$li_row);
			}
			else
			{
				$lb_valido=$this->uf_oaf_cuenta_insert($ls_etapa,$ld_fecini,$ld_fecfin,$ls_next,$ldec_saldo,&$lb_tiene);
			}
			if($this->uf_obtener_nivel($ls_next)== 1)
			{
				break;
			}
			$ls_next = $this->uf_next_cuenta_nivel($ls_next);
			$li_nivel= $this->uf_obtener_nivel($ls_next);
		}
		return $lb_valido;
	}
	
	function uf_oaf_cuenta_insert($ls_etapa,$ld_fecini,$ld_fecfin,$ls_cuenta,$ldec_monto,$lb_tiene)
	{
		$lb_valido = $this->uf_oaf_sc_programacion('0409',$ls_cuenta, &$lo_cuenta);
		if($lb_valido)
		{
			$lb_tiene=false;
			return true;
		}
		
		$lb_tiene=true;
		$li_mesini=substr($ld_fecini,3,2);
		$li_mesfin=substr($ld_fecfin,3,2);
		
		if(!empty($lo_cuenta))
		{
			$this->uf_calcular_prog_acumulado($li_mesini,$li_mesfin,$lo_cuenta,&$ldec_programado,&$ldec_previsto);
			$ls_status=$lo_cuenta["status"];
			$ls_denominacion=$lo_cuenta["denominacion"];
			$li_nivel=$lo_cuenta["nivel"];
		}
		else
		{
			$ls_status="";
			$ls_denominacion="";
			$li_nivel=0;
			$ldec_programado=0;
			$ldec_previsto=0;
		}
		$this->ds_reporte->insertRow("etapa",$ls_etapa);		
		$this->ds_reporte->insertRow("status",$ls_status);
		$this->ds_reporte->insertRow("sc_cuenta",$ls_cuenta);
		$this->ds_reporte->insertRow("denominacion",$ls_denominacion);
		$this->ds_reporte->insertRow("nivel",$li_nivel);
		$this->ds_reporte->insertRow("programado",$ldec_programado);
		$this->ds_reporte->insertRow("ejecutado",$ldec_monto);
		$this->ds_reporte->insertRow("variacion",0);
		$this->ds_reporte->insertRow("porc_variacion",0);
		$this->ds_reporte->insertRow("prevision",$ldec_previsto);	
		$this->ds_reporte->insertRow("programado_acum",0);
		$this->ds_reporte->insertRow("ejecutado_acum",0);
		$this->ds_reporte->insertRow("variacion_acum",0);
		$this->ds_reporte->insertRow("porc_acum",0);
	
	}
	
	function uf_oaf_cuenta_update($ls_etapa,$ls_next,$ldec_saldo,$li_row)
	{
		$ldec_monto=$this->ds_reporte->getValue("ejecutado",$li_row);
		$this->ds_reporte->updateRow("ejecutado",$ldec_monto+$ldec_saldo,$li_row);	
		return true;	
	}
	
	function uf_oaf_sc_programacion($ls_codreport,$ls_cuenta,$lo_cuenta)
	{
		$ls_sql="SELECT  * 
				 FROM scg_pc_reporte
				 WHERE cod_report='".$ls_codreport."' AND sc_cuenta='".$ls_cuenta."'";
		$ds_temp=new class_datastore();
		$rs_data=$this->io_sql->select($ls_sql);
		$lo_cuenta=array();
		if($rs_data===false)
		{
			$lb_existe=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_existe=true;
				$ds_temp->data=$this->io_sql->obtener_datos($rs_data);
				$lo_cuenta=$ds_temp->getRow(1);
			}
			else
			{	
				$lb_existe=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_existe;
	}

	function uf_init_niveles()
	{    
		 $ls_formato=""; $li_posicion=0; $li_indice=0;
		 //contable
		 $ls_formato = trim($this->la_empresa["formplan"])."-";
		 $li_posicion = 1 ;
		 $li_indice   = 1 ;
		 $li_posicion = $this->io_fun->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		 do
		 {
			$this->ia_niveles[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $this->io_fun->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		 } while ($li_posicion>=0);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_obtener_nivel
	// 	   Access:  public
	//  Arguments:  as_cuenta-> cuenta contable
	//	  Returns:  retorna un valor numerico
	//Description:  Este método retorna un valor numerico de la cuenta segun el formato
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_obtener_nivel($as_cuenta)
	{
       $this->uf_init_niveles();
	   $li_nivel=0;$li_anterior=0;$li_longitud=0;
	   $ls_cadena="";
	   $li_nivel = count($this->ia_niveles);
	   do
	   {		
			$li_anterior = $this->ia_niveles[ $li_nivel - 1 ]  + 1;
			$li_longitud = $this->ia_niveles[ $li_nivel ] - $this->ia_niveles[ $li_nivel - 1 ];
			$ls_cadena 	= substr( trim($as_cuenta),$li_anterior ,$li_longitud);
			$li=$ls_cadena;
		    if ($li>0)  {  return $li_nivel;  }
			$li_nivel = $li_nivel - 1;
	   } while( $li_nivel > 1);	
	   return $li_nivel;
	} // end function uf_obtener_nivel($as_cuenta)
	
	
	function uf_next_cuenta_nivel($as_cuenta)
	{
	    ////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_next_cuenta_nivel
		// 	   Access:  public
		//  Arguments:  $as_sc_cuenta->cuenta 
		//	  Returns:  cuenta siguiente
		//Description:  Este método obtiene el siguiente nivel de la cuenta
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_MaxNivel=0;$li_nivel=0;$li_anterior=0;$li_longitud=0; $li_long=0;
		$ls_cadena=""; $ls_fill="";
		$li_MaxNivel = count($this->ia_niveles);
		$li_nivel    = $this->uf_obtener_nivel( $as_cuenta );
		if ($li_nivel > 1)
		{
			$li_anterior = $this->ia_niveles[ $li_nivel - 1 ]; 	
			$ls_cadena   = substr($as_cuenta,0, $li_anterior+1);
			$li_longitud = strlen($ls_cadena);
			$li_long = (($this->ia_niveles[$li_MaxNivel]+1) - $li_longitud);
			$ls_newcadena = $this->io_fun->uf_cerosderecha(trim($ls_cadena),$li_long+$li_longitud);
			$ls_cadena=$ls_newcadena;
		}
		return $ls_cadena;
	}	//fin de uf_next_cuenta_nivel
	
	function uf_sin_ceros($ls_cuenta)
	{
		$li_maxnivel=count($this->ia_niveles);
		$li_nivel = $this->uf_obtener_nivel($ls_cuenta);
		$li_anterior=$this->ia_niveles[$li_nivel];
		$ls_cad = substr($ls_cuenta, 0 ,$li_anterior);
		return $ls_cad;
	}
	
	function uf_oaf_calcular_total_ctaspg($ls_cuenta,$ld_fecini,$ld_fecfin,$ldec_saldo)
	{
		if(empty($ls_cuenta))
		{
			$ldec_saldo = 0;
			return true;
		}
		
		$ls_cuenta_aux = $this->uf_sin_ceros($ls_cuenta)."%";
		
		$ls_sql= "SELECT SUM(PMV.monto) as Total 
				  FROM   spg_dt_cmp PMV,spg_operaciones POP 
				  WHERE  (PMV.operacion=POP.operacion) AND (POP.causar=1) AND 
			        	 PMV.spg_cuenta like '".$ls_cuenta_aux."' AND (PMV.fecha between '".$ld_fecini."' AND '".$ld_fecfin."')";
		
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			print "Error en calcular total spg";
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))	
			{
				$ldec_saldo=$row["Total"];
				if(empty($ldec_saldo))
				{
					$ldec_saldo=0;
				}
			}	
			else
			{
				$ldec_saldo=0;
			}
		}
	}

}
?>