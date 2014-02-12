<?php
class sigesp_scg_comparados
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
	function sigesp_scg_comparados()
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
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////	RESUMEN DE INVERSIONES	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_crear_reporte_inversiones($ld_fecdesde,$ld_fechasta)
	{
		$this->uf_init_array_inversiones();
		$ld_enddate= substr($ld_fechasta,3,2);
		$ld_fecha=date("d-m-Y", strtotime("$ld_fecdesde -1 month"));		
		$li_dant	= substr($ld_fecha,3,2);		
		$li_totrows=$this->ds_cuentas->getRowCount("sc_cuenta");
		for($li_x=1;$li_x<=$li_totrows;$li_x++)
		{
			$ls_tipo   = $this->ds_cuenta->getValue("tipo",$li_x);
			$ls_cuenta = $this->ds_cuenta->getValue("sc_cuenta",$li_x);
			if($ls_tipo==1)
			{
				$this->uf_procesar_cuenta();
			}
		}		
	}
	
	function uf_procesa_cuenta($ls_cuenta)
	{
		$lb_existe=false;
		$ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_cuenta);
		
		$ls_codemp = $this->la_empresa["codemp"];
		$ls_sql="SELECT * 
				 FROM   scg_cuentas
				 WHERE  codemp='".$ls_codemp."' AND sc_cuenta like '".$as_cuenta."%'";
		
		$rs_data=$this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			print "Error en procesar_cuenta 1".$this->io_sql->message;			
		}
		else
		{
			$ls_cuenta=$row["sc_cuenta"];
			$li_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_cuenta);
			if($row=$this->io_sql->fetch_row($rs_data))
			{			
				if(!$this->uf_calcular_saldo($ls_cuenta,$ld_fecdesde,$ld_fechasta, &$ldec_el_saldo,$ldec_dh);
				{return false;}
				
		

		
			cAux=sc_cuentas.sc_cuenta
			
			*-- Calculo Saldo Anterior
			If thisform.oSCG.SALDO_SELECT2(cAux, cMesDesde,cMesHasta, @nDebe, @nHaber)
				nDiferencia = nDebe-nHaber
			Else
				nDiferencia=0
			Endif
			nSigno=iif (sc_cuentas.sc_cuenta=alltrim(oEmp.gasto),1,-1) &&Saldo Acreedor
			
			If mdh=.f.
				nEl_Saldo  =nEl_Saldo * nSigno
			Endif
		
			nDiferencia=nDiferencia * nSigno
		
			nPrg_varia=thisform.PRG_MES2p(sc_cuentas.sc_cuenta,pnMesFrom,pnMesTo)
			nPrg_acum = thisform.PRG_MES2p(sc_cuentas.sc_cuenta,1,pnMesTo)  			&&--> MODIFICADO CON ANIBAL, 30/05/2005
		
			nResta1=nDiferencia-nPrg_varia
			nResta2=nEl_Saldo-nPrg_acum
			nPor1= iif (nPrg_varia=0 and nResta1=0,0,iif (nPrg_varia=0 ,100,nResta1/nPrg_varia*100))
			nPor2= iif (nPrg_acum =0 and nResta2=0,0,iif (nPrg_acum =0 ,100,nResta2/nPrg_acum*100))
		
			nRepProxMes=thisform.PRG_MESp(sc_cuentas.sc_cuenta,pnMesTo+1,pnMesTo+pnMesProx)
		
			*---	Cálculo del saldo anterior
			*----------------------------------
			nMsa=0
			nMsa = thisform.c_saldo_anterior(cAux)
			If isnull(nMsa)
				nMsa=0
			Endif
			*----------------------------------
			*programado  ==>    PRG_VARIA
			*ejecutado ====> saldo_ant
		
			nVarP = 0 		&&------> variacion periodo
		
			Do case
				Case nPrg_varia > 0 and (nPrg_varia > nEl_Saldo)
					nVarP = 0 - (nPrg_varia - nEl_Saldo)
				Case nPrg_varia = 0 and (nPrg_varia < nEl_Saldo)
					nVarP = nEl_Saldo
				Case nPrg_varia > 0 and (nPrg_varia < nEl_Saldo)
					nVarP = abs(nPrg_varia - nEl_Saldo)
				Otherwise
					nVarP = nPrg_varia - nEl_Saldo
			Endcase
		
			*periodo ===> PRG_ACUM
			*ac. ejecutado ===> s_ant+SALDO_ANT
			nVarE = 0 		&&------> variacion acumulados ej
		
			Do case
				Case nPrg_acum>0 and (nPrg_acum>(nMsa+nEl_Saldo))
					nVarE = 0-(nPrg_acum-(nMsa+nEl_Saldo))
				Case nPrg_acum=0 and (nPrg_acum<(nMsa+nEl_Saldo))
					nVarE = nEl_Saldo
				Case nPrg_acum>0 and (nPrg_acum<(nMsa+nEl_Saldo))
					nVarE = abs(nPrg_acum-(nMsa+nEl_Saldo))
				Otherwise
					nVarE = nPrg_acum-(nMsa+nEl_Saldo)
			Endcase
		
			Update curReporteCA ;
				set	Prg_varia = nPrg_varia,;
				Prg_Acum = nPrg_acum,;
				Saldo_Ant = nEl_Saldo,;
				Diferencia = nDiferencia,;
				P1 = nPor1,;
				P2 = nPor2,;
				RepProxMes = nRepProxMes,;
				Nivel = cNivel,;
				S_ant =  nMsa, ;
				vProg = nVarP,;
				vEjec = nVarE ;
				where curReporteCA.sc_cuenta=cCuenta
		
			If (sc_cuenta=alltrim(oEmp.ingreso) AND status='C')
				pnIngresos = (pnIngresos+nDiferencia)
			Endif
		
			If (sc_cuenta=alltrim(oEmp.gasto) AND status='C')
				pnGastos = (pnGastos+nDiferencia)
			Endif
		Else
			Update curReporteCA ;
				set	Prg_varia = 0,;
				Prg_Acum = 0,;
				Saldo_Ant = 0,;
				Diferencia = 0,;
				P1 = 0,;
				P2 = 0,;
				RepProxMes = 0,;
				Nivel = cNivel,;
				S_ant =  0 ;
				where curReporteCA.sc_cuenta=cCuenta
		Endif
	}
	
	function uf_init_array_inversiones()
	{
		$ls_formcont=$this->la_empresa["formcont"];
		//$li_len=strlen(str_replace($this->la_empresa["formcont"],'-',''));
		$la_cuentas[1]  = '121000000';
		$la_cuentas[2]  = '121010000';
		$la_cuentas[3]  = '121020000';
		$la_cuentas[4]  = '121030000';
		$la_cuentas[5]  = '123000000';
		$la_cuentas[6]  = '123010000';
		$la_cuentas[7]  = '123010100';
		$la_cuentas[8]  = '123010200';
		$la_cuentas[9]  = '123010300';
		$la_cuentas[10] = '123010400';
		$la_cuentas[11] = '123010500';
		$la_cuentas[12] = '123010600';
		$la_cuentas[13] = '123010700';
		$la_cuentas[14] = '123010800';
		$la_cuentas[15] = '123010900';		
		$la_cuentas[16] = '123011900';
		$la_cuentas[17] = '123020000';		
		$la_cuentas[18] = '123050000';		
		$la_cuentas[19] = '123050100';		
		$la_cuentas[20] = '123050200';		
		$la_cuentas[21] = '124000000';		
		$la_cuentas[22] = '124010000';		
		$la_cuentas[23] = '124020000';		
		$la_cuentas[24] = '124030000';		
		$la_cuentas[25] = '124040000';		
		$la_cuentas[26] = '124050000';		
		$la_cuentas[27] = '124190000';		
		$la_cuentas[28] = '401000000';		
		$la_cuentas[29] = '402000000';		
		$la_cuentas[30] = '403000000';			
		$la_cuentas[31] = '407000000';		
		$la_cuentas[32] = '408000000';		
		$la_cuentas[33] = '306010000';		
		$la_cuentas[34] = '306020000';		
		$la_cuentas[35] = '307000000';				
		$la_cuentas[36] = '308000000';	
		$la_cuentas[37] = '309000000';				
		$la_cuentas[38] = '310000000';	
		for( $li_pos=1;$li_pos<=38;$li_pos++)
		{
			$ls_cuenta=$la_cuentas[$li_pos];
			if((substr($ls_cuenta,0,1)=='4')||(substr($ls_cuenta,0,1)=='3'))
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico_re 
						 WHERE  sig_cuenta = '".$ls_cuenta."'";
			}
			else
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico 
						 WHERE  sc_cuenta = '".$ls_cuenta."'";
			}
					
		   $rs_data=$this->io_sql->select($ls_sql); 
		   if($rs_data===false)	
		   {
				$this->io_msg->message("Error al seleccionar cuenta metodo uf_init_array ".$this->io_fun->uf_convertirmsg($this->io_sql->message));
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
		
	}	
}
?>
