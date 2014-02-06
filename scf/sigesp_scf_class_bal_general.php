<?php
class sigesp_scf_class_bal_general
{
	var $la_empresa;
	var $io_fun;
	var $io_sql;
	var $io_sql_aux;
	var $io_msg;
	var $int_scg;
	var $ds_reporte;
	var $ds_prebalance;
	var $ds_pasivo_t;
	var $ds_activo_h;
	var $ds_pasivo_h;
	var $ds_ingreso;
	var $ds_gasto;
	var $ds_resultado;
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
	
	function sigesp_scf_class_bal_general()
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
		$this->ds_pasivo_t=new class_datastore();
		$this->ds_activo_h=new class_datastore();
		$this->ds_pasivo_h=new class_datastore();
		$this->ds_ingreso=new class_datastore();
		$this->ds_gasto=new class_datastore();
		$this->ds_resultado=new class_datastore();
		$this->ds_Balance1=new class_datastore();
		$this->ds_cuentas=new class_datastore();
		$this->ds_reporte=new class_datastore();
		require_once("../../shared/class_folder/class_sigesp_int.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
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
		$ds_reporteB=new class_datastore();
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

		//--------------------------------------------------///////////////	
		$ls_empresa=  $this->la_empresa["codemp"];	 	
		$this->ls_activo_t=trim($this->la_empresa["activo_t"]);
		$this->ls_pasivo_t=trim($this->la_empresa["pasivo_t"]);
		$this->ls_resultado_t=trim($this->la_empresa["resultado_t"]);	
		$this->ls_activo_h=trim($this->la_empresa["activo_h"]);	
		$this->ls_pasivo_h=trim($this->la_empresa["pasivo_h"]);		
		$this->ls_ingreso_f=trim($this->la_empresa["ingreso_f"]);
		$this->ls_gastos_f=trim($this->la_empresa["gasto_f"]);
		$this->ls_resultado_t=trim($this->la_empresa["resultado_t"]);		
		//---------------------------------------------------//////////////
		
		$this->ls_cta_resultado = trim($this->la_empresa["c_resultad"]);
		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
		$ls_codemp=$this->la_empresa["codemp"];
		
////----------------activos del tesoro----------------------------------------------------------------------------------------
		
		 $ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber,
				 0 as nivel FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_activo_t."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta "; 
       
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
		  
			  $this->ds_Prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->ds_Prebalance->insertRow("denominacion",$ls_denominacion);
			  $this->ds_Prebalance->insertRow("status",$ls_status);
			  $this->ds_Prebalance->insertRow("nivel",$ls_nivel);
			  $this->ds_Prebalance->insertRow("rnivel",$ls_rnivel);
			  $this->ds_Prebalance->insertRow("total_debe",$ld_total_debe);
			  $this->ds_Prebalance->insertRow("total_haber",$ld_total_haber);
			  $ls_saldo=$ld_total_debe - $ld_total_haber;
			  $this->ds_Prebalance->insertRow("saldo",$ls_saldo);
			  
		      $lb_valido = true;	 
		}//while
		
	    $li=$this->ds_Prebalance->getRowCount("sc_cuenta");
		if($li==0)
		{
		  $lb_valido = false;
		  return false;
		}//if
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  ////----------------pasivos del tesoro----------------------------------------------------------------------------------------
			 
		 $ls_sql="SELECT SC.sc_cuenta as cuenta_p,SC.denominacion as denom_p ,SC.status as status_p,SC.nivel as rnivel_p , 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe_p,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber_p,
				 0 as nivel_p FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_pasivo_t."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta ";
       
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
       while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta_p=$row["cuenta_p"];
		  $ls_denom_p=$row["denom_p"];
		  $ls_status_p=$row["status_p"];
		  $ls_rnivel_p=$row["rnivel_p"];
		  $ld_total_debe_p=$row["total_debe_p"];
		  $ld_total_haber_p=$row["total_haber_p"];
		  
			  $this->ds_pasivo_t->insertRow("cuenta_p",$ls_sc_cuenta_p);
			  $this->ds_pasivo_t->insertRow("denom_p",$ls_denom_p);
			  $this->ds_pasivo_t->insertRow("status_p",$ls_status_p);
			  $this->ds_pasivo_t->insertRow("nivel_p",$ls_nivel);
			  $this->ds_pasivo_t->insertRow("rnivel_p",$ls_rnivel_p);
			  $this->ds_pasivo_t->insertRow("total_debe_p",$ld_total_debe_p);
			  $this->ds_pasivo_t->insertRow("total_haber_p",$ld_total_haber_p);
			  $ls_saldo_p=$ld_total_haber_p -$ld_total_debe_p;
			  $this->ds_pasivo_t->insertRow("saldo_p",$ls_saldo_p);			  
		      $lb_valido = true;	 
		}//while
		
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  
  ////----------------ACTIVO HACIENDA----------------------------------------------------------------------------------------
			 
		 $ls_sql="SELECT SC.sc_cuenta as cuenta_h,SC.denominacion as denom_h ,SC.status as status_h,SC.nivel as rnivel_h , 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe_h,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber_h,
				 0 as nivel_p FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_activo_h."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta ";
       
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
       while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta_h=$row["cuenta_h"];
		  $ls_denom_h=$row["denom_h"];
		  $ls_status_h=$row["status_h"];
		  $ls_rnivel_h=$row["rnivel_h"];
		  $ld_total_debe_h=$row["total_debe_h"];
		  $ld_total_haber_h=$row["total_haber_h"];
		  
			  $this->ds_activo_h->insertRow("cuenta_h",$ls_sc_cuenta_h);
			  $this->ds_activo_h->insertRow("denom_h",$ls_denom_h);
			  $this->ds_activo_h->insertRow("status_h",$ls_status_h);
			  $this->ds_activo_h->insertRow("nivel_h",$ls_nivel);
			  $this->ds_activo_h->insertRow("rnivel_h",$ls_rnivel_h);
			  $this->ds_activo_h->insertRow("total_debe_h",$ld_total_debe_h);
			  $this->ds_activo_h->insertRow("total_haber_h",$ld_total_haber_h);
			  $ls_saldo_h=$ld_total_debe_h-$ld_total_haber_h;
			  $this->ds_activo_h->insertRow("saldo_h",$ls_saldo_h);			  
		      $lb_valido = true;	 
		}//while
		
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  
  ////----------------PASIVO HACIENDA----------------------------------------------------------------------------------------
			 
		 $ls_sql="SELECT SC.sc_cuenta as cuenta_p_h,SC.denominacion as denom_p_h ,SC.status as status_p_h,SC.nivel as rnivel_p_h , 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe_p_h,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber_p_h,
				 0 as nivel_p FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_pasivo_h."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta ";
       
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
       while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta_p_h=$row["cuenta_p_h"];
		  $ls_denom_p_h=$row["denom_p_h"];
		  $ls_status_p_h=$row["status_p_h"];
		  $ls_rnivel_p_h=$row["rnivel_p_h"];
		  $ld_total_debe_p_h=$row["total_debe_p_h"];
		  $ld_total_haber_p_h=$row["total_haber_p_h"];
		  
			  $this->ds_pasivo_h->insertRow("cuenta_p_h",$ls_sc_cuenta_p_h);
			  $this->ds_pasivo_h->insertRow("denom_p_h",$ls_denom_p_h);
			  $this->ds_pasivo_h->insertRow("status_p_h",$ls_status_p_h);
			  $this->ds_pasivo_h->insertRow("nivel_p_h",$ls_nivel);
			  $this->ds_pasivo_h->insertRow("rnivel_p_h",$ls_rnivel_p_h);
			  $this->ds_pasivo_h->insertRow("total_debe_p_h",$ld_total_debe_p_h);
			  $this->ds_pasivo_h->insertRow("total_haber_p_h",$ld_total_haber_p_h);
			  $ls_saldo_p_h=$ld_total_haber_p_h-$ld_total_debe_p_h;
			  $this->ds_pasivo_h->insertRow("saldo_p_h",$ls_saldo_p_h);			  
		      $lb_valido = true;	 
		}//while
		
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  
  
  ////----------------ingreso----------------------------------------------------------------------------------------
			 
	 $ls_sql="SELECT SC.sc_cuenta as cuenta_i,SC.denominacion as denom_i ,SC.status as status_i,SC.nivel as rnivel_i , 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe_i,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber_i,
				 0 as nivel_p FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_ingreso_f."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta ";/////32 
       
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
       while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta_i=$row["cuenta_i"];
		  $ls_denom_i=$row["denom_i"];
		  $ls_status_i=$row["status_i"];
		  $ls_rnivel_i=$row["rnivel_i"];
		  $ld_total_debe_i=$row["total_debe_i"];
		  $ld_total_haber_i=$row["total_haber_i"];
		  
			  $this->ds_ingreso->insertRow("cuenta_i",$ls_sc_cuenta_i);
			  $this->ds_ingreso->insertRow("denom_i",$ls_denom_i);
			  $this->ds_ingreso->insertRow("status_i",$ls_status_i);
			  $this->ds_ingreso->insertRow("nivel_i",$ls_nivel);
			  $this->ds_ingreso->insertRow("rnivel_i",$ls_rnivel_i);
			  $this->ds_ingreso->insertRow("total_debe_i",$ld_total_debe_i);
			  $this->ds_ingreso->insertRow("total_haber_i",$ld_total_haber_i);
			  $ls_saldo_i=$ld_total_haber_i-$ld_total_debe_i;
			  $this->ds_ingreso->insertRow("saldo_i",$ls_saldo_i);			  
		      $lb_valido = true;	 
		}//while
		
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  
  ////----------------gastos----------------------------------------------------------------------------------------
			 
	 $ls_sql="SELECT SC.sc_cuenta as cuenta_g,SC.denominacion as denom_g ,SC.status as status_g,SC.nivel as rnivel_g , 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe_g,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber_g,
				 0 as nivel_p FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_gastos_f."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta "; ////31
       
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
       while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta_g=$row["cuenta_g"];
		  $ls_denom_g=$row["denom_g"];
		  $ls_status_g=$row["status_g"];
		  $ls_rnivel_g=$row["rnivel_g"];
		  $ld_total_debe_g=$row["total_debe_g"];
		  $ld_total_haber_g=$row["total_haber_g"];
		  
			  $this->ds_gasto->insertRow("cuenta_g",$ls_sc_cuenta_g);
			  $this->ds_gasto->insertRow("denom_g",$ls_denom_g);
			  $this->ds_gasto->insertRow("status_g",$ls_status_g);
			  $this->ds_gasto->insertRow("nivel_g",$ls_nivel);
			  $this->ds_gasto->insertRow("rnivel_g",$ls_rnivel_g);
			  $this->ds_gasto->insertRow("total_debe_g",$ld_total_debe_g);
			  $this->ds_gasto->insertRow("total_haber_g",$ld_total_haber_g);
			  $ls_saldo_g=$ld_total_debe_g-$ld_total_haber_g;
			  $this->ds_gasto->insertRow("saldo_g",$ls_saldo_g);			  
		      $lb_valido = true;	 
		}//while
		
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  
  ////----------------resultado del tesoro----------------------------------------------------------------------------------------
			 
	 $ls_sql="SELECT SC.sc_cuenta as cuenta_t,SC.denominacion as denom_t ,SC.status as status_t,SC.nivel as rnivel_t , 
				 (SELECT case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe_t,
			     (SELECT case sum(haber_mes) when null then 0 else sum(haber_mes) end  FROM scg_saldos WHERE codemp='".$ls_empresa."' AND fecsal<='".$ad_fecfin."' "." and  sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber_t,
				 0 as nivel_p FROM scg_cuentas SC 
			     WHERE  (SC.sc_cuenta like '".$this->ls_resultado_t."%' ) and SC.nivel='".$ai_nivel."'".
				"ORDER BY SC.sc_cuenta "; 
		$rs_data=$this->io_sql->select($ls_sql);

	 if($rs_data===false)
	 {// error interno sql
		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		print $this->io_sql->message;
		$lb_valido = false;
	 }
	 else
	 {
       while($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ls_sc_cuenta_t=$row["cuenta_t"];
		  $ls_denom_t=$row["denom_t"];
		  $ls_status_t=$row["status_t"];
		  $ls_rnivel_t=$row["rnivel_t"];
		  $ld_total_debe_t=$row["total_debe_t"];
		  $ld_total_haber_t=$row["total_haber_t"];
		  
			  $this->ds_resultado->insertRow("cuenta_t",$ls_sc_cuenta_t);
			  $this->ds_resultado->insertRow("denom_t",$ls_denom_t);
			  $this->ds_resultado->insertRow("status_t",$ls_status_t);
			  $this->ds_resultado->insertRow("nivel_t",$ls_nivel);
			  $this->ds_resultado->insertRow("rnivel_t",$ls_rnivel_t);
			  $this->ds_resultado->insertRow("total_debe_t",$ld_total_debe_t);
			  $this->ds_resultado->insertRow("total_haber_t",$ld_total_haber_t);
			  $ls_saldo_t=$ld_total_haber_t-$ld_total_debe_t;
			  $this->ds_resultado->insertRow("saldo_t",$ls_saldo_t);			  
		      $lb_valido = true;	 
		}//while
		
	 } //else	 
  //////-----------------------------------------------------------------------------------------------------------------------
  
	 $ld_saldo_i=0;		
		
	 if($lb_valido)
	 {
	   $lb_valido=$this->uf_scf_reporte_select_saldo_ingreso_BG($ad_fecfin,$this->ls_ingreso,$ld_saldo_i);
	 } 
     if($lb_valido)
	 {
       $ld_saldo_g=0;	 
	   $lb_valido=$this->uf_scf_reporte_select_saldo_gasto_BG($ad_fecfin,$this->ls_gastos,$ld_saldo_g);  
	 }//if
	 if($lb_valido)
	 {
	   $ld_saldo_ganancia=$ld_saldo_ganancia+($ld_saldo_i+$ld_saldo_g);
	 }//if
	 
	
    /// unset($this->ds_Prebalance);
     unset($this->ds_Balance1);
     unset($ds_Balance2);
	 return $lb_valido;  
	}

/****************************************************************************************************************************************/	
   function  uf_scf_reporte_select_saldo_ingreso_BG($adt_fecini,$ai_ingreso,&$ad_saldo) 
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
	 $ls_sql=" SELECT COALESCE(sum(SD.debe_mes-SD.haber_mes),0) as saldo ".
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

 function  uf_scf_reporte_select_saldo_gasto_BG($adt_fecini,$ai_gasto,&$ad_saldo) 
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
	 
	 $ls_sql=" SELECT COALESCE(sum(SD.debe_mes-SD.haber_mes),0) as saldo ".
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

   function  uf_scf_reporte_calcular_total_BG(&$ai_nro_regi,$as_prev_nivel,$as_nivel,&$aa_sc_cuenta,$aa_denominacion,$aa_saldo) 
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

   function  uf_scf_reporte_actualizar_resultado_BG($ai_c_resultad,$ad_saldo_ganancia,$ai_nro_reg,$as_orden) 
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