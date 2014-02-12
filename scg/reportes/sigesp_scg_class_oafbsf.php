<?php
class sigesp_scg_class_oafbsf
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
	function sigesp_scg_class_oafbsf()
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
	
	function uf_crear_reporte_oaf($li_nivel,$ldt_fecdes,$ldt_fechas,$ls_agno)
	{
		
		$this->uf_init_array_oaf();
		return true;
		
		
	}
	
	function uf_init_array_oaf()
	{
		$la_cuentas=array();
		
		$la_cuentas[1]  = '325020000';
		$la_cuentas[2]  = '305000000';
		$la_cuentas[3]  = '305020300';
		$la_cuentas[4]  = '305020400';		
		$la_cuentas[5]  = '306000000';		
		$la_cuentas[6]  = '306010000';		
		$la_cuentas[7]  = '306020000';
		$la_cuentas[8]  = '306030000';
		$la_cuentas[9]  = '309000000';						
		$la_cuentas[10] = '310000000';		
		$la_cuentas[11] = '311000000';		
		$la_cuentas[12] = '311010000';		
		$la_cuentas[13] = '311020000';
		$la_cuentas[14] = '311040000';
		$la_cuentas[15] = '311030000';
		$la_cuentas[16] = '311050000';								
		$la_cuentas[17] = '311990000';		
		$la_cuentas[18] = '312000000';		
		$la_cuentas[19] = '312010000';		
		$la_cuentas[20] = '312020000';		
		$la_cuentas[21] = '312030000';		
		$la_cuentas[22] = '312040000';		
		$la_cuentas[23] = '312050000';		
		$la_cuentas[24] = '312060000';		
		$la_cuentas[25] = '312080000';		
		$la_cuentas[26] = '312990000';		
		$la_cuentas[27] = '313000000';
		
		$la_cuentas[28] = '404000000';		
		$la_cuentas[29] = '404010000';		
		$la_cuentas[30] = '404020000';		
		$la_cuentas[31] = '404030000';		
		$la_cuentas[32] = '404040000';		
		$la_cuentas[33] = '404000000';
		
		$la_cuentas[34]  = '404050000';
		$la_cuentas[35]  = '404050100';
		$la_cuentas[36]  = '404050200';
		$la_cuentas[37]  = '404050300';
		$la_cuentas[38]  = '404050400';
		$la_cuentas[39]  = '404059900';
		$la_cuentas[40]  = '404060000';
		$la_cuentas[41]  = '404060100';
		$la_cuentas[42]  = '404069900';
		$la_cuentas[43]  = '404070000';
		$la_cuentas[44]  = '404070100';
		$la_cuentas[45]  = '404070200';
		$la_cuentas[46]  = '404070300';
		$la_cuentas[47]  = '404070400';
		$la_cuentas[48]  = '404070500';
		$la_cuentas[49]  = '404070600';
		$la_cuentas[50]  = '404079900';
		$la_cuentas[51]  = '404080000';
		$la_cuentas[52]  = '404080100';
		$la_cuentas[53]  = '404089900';
		$la_cuentas[54]  = '404090000';
		$la_cuentas[55]  = '404090100';
		$la_cuentas[56]  = '404090200';
		$la_cuentas[57]  = '404090300';
		$la_cuentas[58]  = '404099900';
		$la_cuentas[59]  = '404100000';
		$la_cuentas[60]  = '404100100';
		$la_cuentas[61]  = '404110000';
		$la_cuentas[62]  = '404110100';
		$la_cuentas[63]  = '404110200';
		$la_cuentas[64]  = '404110300';
		$la_cuentas[65]  = '404110400';
		$la_cuentas[66]  = '404110500';
		$la_cuentas[67]  = '404110501';
		$la_cuentas[68]  = '404110502';
		$la_cuentas[69]  = '404110503';
		$la_cuentas[70]  = '404110504';
		$la_cuentas[71]  = '404110505';
		$la_cuentas[72]  = '404110506';
		$la_cuentas[73]  = '404110507';
		$la_cuentas[74]  = '404110599';
		$la_cuentas[75]  = '404120000';
		$la_cuentas[76]  = '404120100';
		$la_cuentas[77]  = '404120200';
		$la_cuentas[78]  = '404120300';
		$la_cuentas[79]  = '404120400';
		$la_cuentas[80]  = '404120500';
		$la_cuentas[81]  = '404129900';
		$la_cuentas[82]  = '404130000';
		$la_cuentas[83]  = '404130100';
		$la_cuentas[84]  = '404130200';
		$la_cuentas[85]  = '404140000';
		$la_cuentas[86]  = '404140100';
		$la_cuentas[87]  = '404140200';
		$la_cuentas[88]  = '404150000';
		$la_cuentas[89]  = '404150100';
		$la_cuentas[90]  = '404150200';
		$la_cuentas[91]  = '404150300';
		$la_cuentas[92]  = '404150400';
		$la_cuentas[93]  = '404150500';
		$la_cuentas[94]  = '404150600';
		$la_cuentas[95]  = '404160000';
		$la_cuentas[96]  = '404160100';
		$la_cuentas[97]  = '404160200';
		$la_cuentas[98]  = '404160300';
		$la_cuentas[99]  = '404160400';
		$la_cuentas[100]  = '404990000';
		$la_cuentas[101]  = '404990100';
		
		$la_cuentas[102]  = '405000000';
		$la_cuentas[103]  = '405050000';
		$la_cuentas[104]  = '405060000';
		$la_cuentas[105]  = '405080000';
		$la_cuentas[106]  = '405070000';
		$la_cuentas[107]  = '405090000';
		$la_cuentas[108]  = '405100000';
		$la_cuentas[109]  = '405200000';
		$la_cuentas[110]  = '405210000';
		$la_cuentas[111]  = '411000000';
		$la_cuentas[112]  = '411010000';
		$la_cuentas[113]  = '411020000';
		$la_cuentas[114]  = '411030000';
		$la_cuentas[115]  = '411040000';		
		$la_cuentas[116]  = '411050000';		
		$la_cuentas[117]  = '411060000';		
		$la_cuentas[118]  = '411980000';		
		$la_cuentas[119]  = '411990000';		
		$la_cuentas[120]  = '412000000';
		
		$this->ds_cuentas->insertRow("sc_cuenta",'  ');
		$this->ds_cuentas->insertRow("denominacion",'<b>ORIGEN DE FONDOS</b>');
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
		
		$this->ds_cuentas->insertRow("sc_cuenta",'  ');
		$this->ds_cuentas->insertRow("denominacion",'AUTOFINANCIAMIENTO');
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
		
		for($li_i=1;$li_i<=120;$li_i++)
		{
			$ls_cuenta=$la_cuentas[$li_i];
			if($li_i==1)
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico 
						 WHERE  sc_cuenta = '".$ls_cuenta."'";
			}
			else
			{
				$ls_sql="SELECT denominacion 
						 FROM   sigesp_plan_unico_re 
						 WHERE  sig_cuenta = '".$ls_cuenta."'";
			}
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
			if(($ls_cuenta=='305000000')||($ls_cuenta=='306000000')||($ls_cuenta=='311000000')||($ls_cuenta=='313000000')||($ls_cuenta=='404000000')||($ls_cuenta=='405000000')||($ls_cuenta=='411000000')||($ls_cuenta=='412000000'))
			{
				$ls_tipo=1;
			}
			else
			{
				$ls_tipo=2;
			}
			if($li_i==28)
			{
				$this->ds_cuentas->insertRow("sc_cuenta",'  ');
				$this->ds_cuentas->insertRow("denominacion",'TOTAL DE ORIGEN DE FONDOS');
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
				
				$this->ds_cuentas->insertRow("sc_cuenta",'  ');
				$this->ds_cuentas->insertRow("denominacion",'<b>APLICACIONES</b>');
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
			}
			$this->ds_cuentas->insertRow("sc_cuenta",$ls_cuenta);
			$this->ds_cuentas->insertRow("denominacion",$ls_denominacion);
			$this->ds_cuentas->insertRow("tipo",$ls_tipo);
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
		$this->ds_cuentas->insertRow("sc_cuenta",'  ');
		$this->ds_cuentas->insertRow("denominacion",'TOTAL DE ORIGEN DE FONDOS');
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
}
?>