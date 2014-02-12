<?php
class sigesp_scg_class_report
{
	var $SQL;
	var $dat_emp;
	var $fun;
	var $io_msg;
	var $SQL_aux;
	var $ds_analitico;
	var $dts_reporte;
/****************************************************************************************************************************************/	
	function sigesp_scg_class_report($conn)
	{
	  require_once("../../shared/class_folder/class_sql.php");	  
	  require_once("../../shared/class_folder/class_funciones.php");
	  $this->fun = new class_funciones();
	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->SQL= new class_sql($conn);
	  $this->SQL_aux= new class_sql($conn);
	  $this->io_msg= new class_mensajes();		
	  $this->dat_emp=$_SESSION["la_empresa"];
	  $this->ds_analitico=new class_datastore();
	  $this->dts_reporte=new class_datastore();
	}
/****************************************************************************************************************************************/	
	function uf_cargar_mayor_analitico($ld_fecdesde,$ld_fechasta,$ls_cuenta_desde,$ls_cuenta_hasta,$ls_orden) 
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo: uf_cargar_bancos
	//	Access:  public
	//	Arguments:
	//	Returns:		
	//  $object_bancos=  Arreglo de los bancos para enviarlo a la clase grid_param
	//	Description:  Función que se encarga de seleccionar los   bancos y retornarlos en un arreglo de object
	//////////////////////////////////////////////////////////////////////////////
	  
	  $ls_codemp=$this->dat_emp["codemp"];
	  $li_row=0;	  
	  $ld_fecdesde=	$this->fun->uf_convertirdatetobd($ld_fecdesde);
  	  $ld_fechasta=	$this->fun->uf_convertirdatetobd($ld_fechasta);
		if($_SESSION["ls_gestor"]=='MYSQLT')
		{
		   $ls_sql="SELECT A.sc_cuenta as sc_cuenta,A.procede as procede,A.comprobante as comprobante,A.procede_doc as procede_doc,A.documento as documento,A.fecha as fecha,A.debhab as debhab, A.descripcion as descripcion,A.monto as monto,A.orden as orden, 
				   A.denominacion as denominacion, A.cod_pro as cod_pro,A.ced_bene as ced_bene,A.des_comp as des_comp,COALESCE(curSA.saldo_ant,0) as saldo_ant,A.nom_benef as nom_benef 	          
				   FROM ( SELECT mv.sc_cuenta,mv.procede,mv.comprobante,mv.procede_doc,mv.documento,mv.fecha,mv.debhab,mv.descripcion,mv.monto,mv.orden, 
						  scu.denominacion,sco.cod_pro,sco.ced_bene,sco.descripcion as des_comp, 
						 IF(tipo_destino='B' ,CONCAT(rtrim(ben.apebene) , ', ' , rtrim(ben.nombene) ) , IF(tipo_destino='P',pro.nompro ,' ' )) as nom_benef 			   
							 FROM scg_dt_cmp mv, sigesp_cmp sco,rpc_beneficiario ben,rpc_proveedor pro,scg_cuentas scu 
						   WHERE ( scu.sc_cuenta=mv.sc_cuenta AND sco.procede=mv.procede AND sco.comprobante=mv.comprobante AND sco.fecha=mv.fecha ) AND  
						 ( sco.cod_pro=pro.cod_pro AND sco.ced_bene=ben.ced_bene ) AND ( mv.fecha between '".$ld_fecdesde."' and '".$ld_fechasta."' ) AND 
						 ( mv.sc_cuenta between '".$ls_cuenta_desde."' and '".$ls_cuenta_hasta."' ) ) A  LEFT OUTER JOIN 				  
					  ( SELECT SA.sc_cuenta,COALESCE(SUM(SA.debe_mes-SA.haber_mes),0) As saldo_ant  
						 FROM scg_saldos SA  
						   WHERE (SA.fecsal<'".$ld_fecdesde."') AND SA.sc_cuenta>='".$ls_cuenta_desde."' AND SA.sc_cuenta<='".$ls_cuenta_hasta."'
						   GROUP BY SA.sc_cuenta ) curSA 
				ON A.sc_cuenta=curSA.sc_cuenta 
				ORDER BY ".$ls_orden;
		}
		if($_SESSION["ls_gestor"]=='POSTGRES')
		{
		   $ls_sql="SELECT A.sc_cuenta as sc_cuenta,A.procede as procede,A.comprobante as comprobante,A.procede_doc as procede_doc,A.documento as documento,A.fecha as fecha,A.debhab as debhab, A.descripcion as descripcion,A.monto as monto,A.orden as orden, 
				   A.denominacion as denominacion, A.cod_pro as cod_pro,A.ced_bene as ced_bene,A.des_comp as des_comp,COALESCE(curSA.saldo_ant,0) as saldo_ant,A.nom_benef as nom_benef 	          
				   FROM ( SELECT mv.sc_cuenta,mv.procede,mv.comprobante,mv.procede_doc,mv.documento,mv.fecha,mv.debhab,mv.descripcion,mv.monto,mv.orden, 
						  scu.denominacion,sco.cod_pro,sco.ced_bene,sco.descripcion as des_comp, 
						 IF(tipo_destino='B' ,(rtrim(ben.apebene)||', '||rtrim(ben.nombene) ) , IF(tipo_destino='P',pro.nompro ,' ' )) as nom_benef 			   
							 FROM scg_dt_cmp mv, sigesp_cmp sco,rpc_beneficiario ben,rpc_proveedor pro,scg_cuentas scu 
						   WHERE ( scu.sc_cuenta=mv.sc_cuenta AND sco.procede=mv.procede AND sco.comprobante=mv.comprobante AND sco.fecha=mv.fecha ) AND  
						 ( sco.cod_pro=pro.cod_pro AND sco.ced_bene=ben.ced_bene ) AND ( mv.fecha between '".$ld_fecdesde."' and '".$ld_fechasta."' ) AND 
						 ( mv.sc_cuenta between '".$ls_cuenta_desde."' and '".$ls_cuenta_hasta."' ) ) A  LEFT OUTER JOIN 				  
					  ( SELECT SA.sc_cuenta,COALESCE(SUM(SA.debe_mes-SA.haber_mes),0) As saldo_ant  
						 FROM scg_saldos SA  
						   WHERE (SA.fecsal<'".$ld_fecdesde."') AND SA.sc_cuenta>='".$ls_cuenta_desde."' AND SA.sc_cuenta<='".$ls_cuenta_hasta."'
						   GROUP BY SA.sc_cuenta ) curSA 
				ON A.sc_cuenta=curSA.sc_cuenta 
				ORDER BY ".$ls_orden;
		}
		//////////////////////////////////////////////////agregado el 31/01/2008////////////////////////////////////////////////////
		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
		   $ld_fecdesde=	$this->fun->uf_formatovalidofecha($ld_fecdesde);
  	       $ld_fechasta=	$this->fun->uf_formatovalidofecha($ld_fechasta);
	       $ld_fecdesde=	$this->fun->uf_convertirdatetobd($ld_fecdesde);
  	       $ld_fechasta=	$this->fun->uf_convertirdatetobd($ld_fechasta);
		   
		   $ls_sql="SELECT A.sc_cuenta as sc_cuenta,A.procede as procede,A.comprobante as comprobante,A.procede_doc as procede_doc,A.documento as documento,A.fecha as fecha,A.debhab as debhab, A.descripcion as descripcion,A.monto as monto,A.orden as orden, 
				   A.denominacion as denominacion, A.cod_pro as cod_pro,A.ced_bene as ced_bene,A.des_comp as des_comp,COALESCE(curSA.saldo_ant,0) as saldo_ant,A.nom_benef as nom_benef 	          
				   FROM ( SELECT mv.sc_cuenta,mv.procede,mv.comprobante,mv.procede_doc,mv.documento,mv.fecha,mv.debhab,mv.descripcion,mv.monto,mv.orden, 
						  scu.denominacion,sco.cod_pro,sco.ced_bene,sco.descripcion as des_comp, 
						 IF(tipo_destino='B' ,(rtrim(ben.apebene)||', '||rtrim(ben.nombene) ) , IF(tipo_destino='P',pro.nompro ,' ' )) as nom_benef 			   
							 FROM scg_dt_cmp mv, sigesp_cmp sco,rpc_beneficiario ben,rpc_proveedor pro,scg_cuentas scu 
						   WHERE ( scu.sc_cuenta=mv.sc_cuenta AND sco.procede=mv.procede AND sco.comprobante=mv.comprobante AND sco.fecha=mv.fecha ) AND  
						 ( sco.cod_pro=pro.cod_pro AND sco.ced_bene=ben.ced_bene ) AND ( mv.fecha between '".$ld_fecdesde."' and '".$ld_fechasta."' ) AND 
						 ( mv.sc_cuenta between '".$ls_cuenta_desde."' and '".$ls_cuenta_hasta."' ) ) A  LEFT OUTER JOIN 				  
					  ( SELECT SA.sc_cuenta,COALESCE(SUM(SA.debe_mes-SA.haber_mes),0) As saldo_ant  
						 FROM scg_saldos SA  
						   WHERE (SA.fecsal<'".$ld_fecdesde."') AND SA.sc_cuenta>='".$ls_cuenta_desde."' AND SA.sc_cuenta<='".$ls_cuenta_hasta."'
						   GROUP BY SA.sc_cuenta ) curSA 
				ON A.sc_cuenta=curSA.sc_cuenta 
				ORDER BY ".$ls_orden;				
		}	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	    $rs_analitico=$this->SQL->select($ls_sql);
	   
	   if (($rs_analitico===false))
	   {
			$lb_valido=false;
			$this->is_msg_error="Error en select bancos,".$this->fun->uf_convertirmsg($this->SQL->message);
			print $this->SQL->message;
	   }
	   else
	   {
			if($row=$this->SQL->fetch_row($rs_analitico))
			{
				$this->ds_analitico->data=$this->SQL->obtener_datos($rs_analitico);
				
			}			
			$this->SQL->free_result($rs_analitico);
	   }
	   
	   //return $rs_proveedor;         
		 
	}//fin de uf_cargar_mayor analitico
/****************************************************************************************************************************************/	
	/////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  "BALANCE DE COMPROBACION FORMATO 1"   // 
	/////////////////////////////////////////////////////////////////

	function uf_scg_reporte_balance_comprobante($as_cuenta_desde,$as_cuenta_hasta,$ad_fecdesde,$ad_fechasta,$ai_nivel)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_balance_comprobante
	 //         Access :	public
	 //     Argumentos :    adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
	 //                     ai_nivel    // nivel de la cuenta  
     //	       Returns :	Retorna un Boleano y genera un datastore con datos preparado
	 //	   Description :	Reporte que genera el balance de comprobanciona una detewrminada fecha y cuenta
	 //     Creado por :    Ing. Wilmer Briceño 
	 // Fecha Creación :    04/02/2006          Fecha última Modificacion : 04/02/2006 Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_existe = false;	 
		$ls_codemp = $this->dat_emp["codemp"];
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_fecdesde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_fechasta);
		$ls_mysql= " SELECT DISTINCT B.sc_cuenta as sc_cuenta,B.denominacion as denominacion,B.saldo_Ant as saldo_ant,B.debe as debe,B.haber as haber,B.saldo_act as saldo_act,C.T_DEBE_MES as t_debe_mes,C.T_HABER_MES as t_haber_mes, ".
		           "       COALESCE(C.T_DEBE_MES,0) as BalDebe,COALESCE(C.T_HABER_MES,0) as BalHABER  ".
		           " FROM (  SELECT A.sc_cuenta,A.denominacion,saldo_ant,COALESCE(curSACT.T_DEBE_MES,0) as Debe,COALESCE(curSACT.T_HABER_MES,0) as Haber,  ".
		           "      	        (COALESCE(Saldo_Ant,0)+COALESCE(curSACT.T_DEBE_MES,0) - COALESCE(curSACT.T_HABER_MES,0)) as Saldo_Act  ".
		           "       	 FROM (SELECT CCT.sc_cuenta,CCT.denominacion,CCT.nivel,COALESCE(curSANT.SANT,0) as Saldo_Ant  ".
				   "  	           FROM scg_cuentas CCT   ".
				   "  	           LEFT OUTER JOIN ( SELECT CSD.sc_cuenta,SUM(debe_mes-haber_mes) SANT  ".
                   "                                 FROM scg_saldos CSD   ".
				   "					             WHERE CSD.codemp='".$ls_codemp."' AND (CSD.sc_cuenta between '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."') AND CSD.fecsal < '".$ld_fecdesde."' ".
				   "  					             GROUP BY CSD.sc_cuenta ) curSANT    ".
				   "	           ON  CCT.sc_cuenta=curSANT.sc_cuenta ) A LEFT OUTER JOIN  ".
				   "	        ( SELECT CSD.sc_cuenta, COALESCE(SUM(debe_mes),0) As T_DEBE_MES, COALESCE(SUM(haber_mes),0) As T_HABER_MES   ".
				   "		      FROM scg_saldos CSD WHERE CSD.codemp='".$ls_codemp."' AND (CSD.sc_cuenta between '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."') AND CSD.fecsal between '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
				   "		      GROUP BY CSD.sc_cuenta )  curSACT   ".
				   "         ON A.sc_cuenta=curSACT.sc_cuenta  ". 		  
				   "       WHERE  (A.sc_cuenta between '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."') AND (A.nivel<=".$ai_nivel.")) B, ". 
			       "      (  SELECT COALESCE(sum(DEBE_MES),0) as T_DEBE_MES, COALESCE(sum(HABER_MES),0) as T_HABER_MES  ".  
			       "         FROM scg_cuentas CCT, scg_saldos CSD  ".  
			       "         WHERE CCT.codemp='".$ls_codemp."' AND (CCT.sc_cuenta=CSD.sc_cuenta) AND (CSD.sc_cuenta between '".$as_cuenta_desde."' AND '".$as_cuenta_hasta."') AND  ".
			       "               CSD.fecsal between '".$ld_fecdesde."' AND '".$ld_fechasta."' AND (CCT.nivel=1) )  C ".
                   " ORDER BY B.sc_cuenta ";
         $rs_balance=$this->SQL->select($ls_mysql);

		if($rs_balance===false)
		{   // error interno sql
		   $this->io_msg->message("Error en Reporte".$this->fun->uf_convertirmsg($this->SQL->message));
           return false;
		}
		else
		{
   		   if($row=$this->SQL->fetch_row($rs_balance))
		   {
              $this->dts_reporte->data=$this->SQL->obtener_datos($rs_balance);
           }
	       $this->SQL->free_result($rs_balance);   
		}

        return true;
	}//fin balance comprobación
/****************************************************************************************************************************************/	
    //////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SCG  " COMPROBANTES FORMATO 1 Y FORMATO 2" // 
	////////////////////////////////////////////////////////////////
    function uf_scg_reporte_comprobante_formato1($as_procede_ori,$as_procede_des,$as_comprobante_ori,$as_comprobante_des,
	                                             $adt_fecini,$adt_fecfin,$ai_orden)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_comprobante_formato1
	 //         Access :	private
	 //     Argumentos :    $as_procede_ori  // procede origen
	 //                     $as_procede_des  // procede destino
	 //                     $as_comprobante_ori  // comprobante origen 
	 //                     $as_comprobante_des  //  comprobante destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
	 //                     $ai_orden  //  orden la consulta  
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 1  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    23/04/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("scg_cuenta");
        
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
	   if($ai_orden==1)  
	   {
    	  $ls_orden="MV.procede,MV.comprobante,MV.fecha,MV.orden";
	   }
	   if($ai_orden==2)  
	   {
    	  $ls_orden="MV.fecha,MV.procede,MV.comprobante,MV.orden";
	   }
	   if($ai_orden==3)  
	   {
    	  $ls_orden="MV.comprobante,MV.fecha,MV.procede,MV.orden";
	   }
	   
	   $ls_sql=" SELECT  MV.*, CC.denominacion, CMP.tipo_destino, CMP.cod_pro, CMP.ced_bene, PRV.nompro, ".
               "         BEN.apebene, BEN.nombene, CAST(CMP.descripcion as char(250)) as CMP_descripcion ".
               " FROM    scg_dt_cmp MV, scg_cuentas CC, sigesp_cmp CMP, rpc_proveedor PRV, rpc_beneficiario BEN ".
               " WHERE   (MV.sc_cuenta = CC.sc_cuenta) AND (MV.procede=CMP.procede AND MV.comprobante=CMP.comprobante AND ".
			   "         MV.fecha=CMP.fecha) AND (CMP.cod_pro=PRV.cod_pro) AND (CMP.ced_bene=BEN.ced_bene) ".$ls_cad_where." ".
               " ORDER BY ".$ls_orden." ";
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
			   $ls_sc_cuenta=$row["sc_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"];
			   $ls_debhab=$row["debhab"];
			   $ls_descripcion=$row["descripcion"];
			   $ld_monto=$row["monto"];
			   $ls_orden=$row["orden"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_tipo_destino=$row["tipo_destino"];
			   $ls_cod_pro=$row["cod_pro"];
			   $ls_ced_bene=$row["ced_bene"];
			   $ls_nompro=$row["nompro"];
			   $ls_apebene=$row["apebene"];
			   $ls_nombene=$row["nombene"];
			   $ls_CMP_descripcion=$row["CMP_descripcion"];
			   
			   $this->dts_reporte->insertRow("procede",$ls_procede);
			   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte->insertRow("sc_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("procede_doc",$ls_procede_doc);
			   $this->dts_reporte->insertRow("documento",$ls_documento);
			   $this->dts_reporte->insertRow("debhab",$ls_debhab);
			   $this->dts_reporte->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte->insertRow("monto",$ld_monto);
			   $this->dts_reporte->insertRow("orden",$ls_orden);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
			   $this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte->insertRow("apebene",$ls_apebene);
			   $this->dts_reporte->insertRow("nombene",$ls_nombene);
			   $this->dts_reporte->insertRow("CMP_descripcion",$ls_CMP_descripcion);
			   $lb_valido = true;
			}//while
			$this->SQL->free_result($rs_data);
		}//else
  return $lb_valido;
  }// fin uf_spg_reporte_comprobante_formato1
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
   function  uf_scg_reporte_comprobante_formato2($as_spg_cuenta_ori,$as_spg_cuenta_des,$adt_fecini,$adt_fecfin) 
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_comprobante_formato2
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta_ori  // cuenta origen
	 //                     $as_spg_cuenta_des  // cuenta destino
	 //                     $adt_fecini  // fecha  desde 
     //              	    $adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Comprobante Formato 2  
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    23/04/2006          Fecha última Modificacion :24/04/2006      Hora :
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
         $ls_cad_filtro1=" , (SELECT cmp1.comprobante,cmp1.fecha,cmp1.procede ".
                         "    FROM   sigesp_cmp cmp1, scg_dt_cmp mv1 ".
                         "    WHERE  cmp1.procede=mv1.procede AND cmp1.comprobante=mv1.comprobante AND ".
	                     "           cmp1.fecha=mv1.fecha AND mv1.sc_cuenta between '".$as_spg_cuenta_ori."' AND '".$as_spg_cuenta_des."' ".
                         "    GROUP BY cmp1.comprobante,cmp1.fecha,cmp1.procede) as curFiltro ";	  
		 
		 $ls_cad_filtro2=" AND MV.comprobante=curFiltro.comprobante ".
                         " AND MV.fecha=curFiltro.fecha ".
                         " AND MV.procede=curFiltro.procede ";				 
	  } 
      else
	  {
	     $ls_cad_filtro1=" , (SELECT cmp1.comprobante,cmp1.fecha,cmp1.procede ".
                         "    FROM   sigesp_cmp cmp1, scg_dt_cmp mv1 ".
                         "    WHERE cmp1.procede=mv1.procede and cmp1.comprobante=mv1.comprobante and cmp1.fecha=mv1.fecha ".
                         "    GROUP BY cmp1.comprobante,cmp1.fecha,cmp1.procede) as curFiltro ";
        
		 $ls_cad_filtro2=" AND MV.comprobante=curFiltro.comprobante ".
                         " AND MV.fecha=curFiltro.fecha ".
                         " AND MV.procede=curFiltro.procede ";	
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
	  if($ai_orden==1)  
	  {
    	  $ls_orden="MV.procede,MV.comprobante,MV.fecha,MV.orden";
	  }
	  if($ai_orden==2)  
	  {
    	  $ls_orden="MV.fecha,MV.procede,MV.comprobante,MV.orden";
	  }
	  if($ai_orden==3)  
	  {
    	  $ls_orden="MV.comprobante,MV.fecha,MV.procede,MV.orden";
	  }
	  
	  $ls_sql=" SELECT MV.*, CC.denominacion, CMP.tipo_destino, CMP.cod_pro, CMP.ced_bene, ".
              "        PRV.nompro, BEN.apebene, BEN.nombene,CMP.descripcion as CMP_descripcion ".
              " FROM   scg_dt_cmp MV, scg_cuentas CC, sigesp_cmp CMP, rpc_proveedor PRV, rpc_beneficiario BEN  ".$ls_cad_filtro1." ".
              " WHERE (MV.sc_cuenta = CC.sc_cuenta) AND (MV.procede=CMP.procede AND MV.comprobante=CMP.comprobante AND ".
			  "        MV.fecha=CMP.fecha) AND (CMP.cod_pro=PRV.cod_pro) AND (CMP.ced_bene=BEN.ced_bene) ".
	          "        ".$ls_cad_where3." ".$ls_cad_filtro2." ".
			  " ORDER BY  ".$ls_orden." ";
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
			   $ls_sc_cuenta=$row["sc_cuenta"];
			   $ls_procede_doc=$row["procede_doc"];
			   $ls_documento=$row["documento"];
			   $ls_debhab=$row["debhab"];
			   $ls_descripcion=$row["descripcion"];
			   $ld_monto=$row["monto"];
			   $ls_orden=$row["orden"];
			   $ls_denominacion=$row["denominacion"];
			   $ls_tipo_destino=$row["tipo_destino"];
			   $ls_cod_pro=$row["cod_pro"];
			   $ls_ced_bene=$row["ced_bene"];
			   $ls_nompro=$row["nompro"];
			   $ls_apebene=$row["apebene"];
			   $ls_nombene=$row["nombene"];
			   $ls_CMP_descripcion=$row["CMP_descripcion"];
			   
			   $this->dts_reporte->insertRow("procede",$ls_procede);
			   $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
			   $this->dts_reporte->insertRow("fecha",$ldt_fecha);
			   $this->dts_reporte->insertRow("sc_cuenta",$ls_spg_cuenta);
			   $this->dts_reporte->insertRow("procede_doc",$ls_procede_doc);
			   $this->dts_reporte->insertRow("documento",$ls_documento);
			   $this->dts_reporte->insertRow("debhab",$ls_debhab);
			   $this->dts_reporte->insertRow("descripcion",$ls_descripcion);
			   $this->dts_reporte->insertRow("monto",$ld_monto);
			   $this->dts_reporte->insertRow("orden",$ls_orden);
			   $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
			   $this->dts_reporte->insertRow("tipo_destino",$ls_tipo_destino);
			   $this->dts_reporte->insertRow("cod_pro",$ls_cod_pro);
			   $this->dts_reporte->insertRow("ced_bene",$ls_ced_bene);
			   $this->dts_reporte->insertRow("nompro",$ls_nompro);
			   $this->dts_reporte->insertRow("apebene",$ls_apebene);
			   $this->dts_reporte->insertRow("nombene",$ls_nombene);
			   $this->dts_reporte->insertRow("CMP_descripcion",$ls_CMP_descripcion);
			   $lb_valido = true;
			}//while
			$this->SQL->free_result($rs_data);
		}//else
  return $lb_valido;
 }//uf_spg_reporte_comprobante_formato2
/****************************************************************************************************************************************/	
}
?> 