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
class sigesp_spg_reporte
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
    function  sigesp_spg_reporte()
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
			   if($lb_valido)
			   {
			     $ls_denestpro4="";
			     $lb_valido=$this->io_spg_report_funciones->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
				 $ls_denestpro4=$ls_denestpro4;
			   }
			   $ls_codestpro5=$row["codestpro5"];
			   if($lb_valido)
			   {
			     $ls_denestpro5="";
			     $lb_valido=$this->io_spg_report_funciones->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
				 $ls_denestpro5=$ls_denestpro5;
			   }
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
			   $this->dts_reporte->insertRow("denestpro4",$ls_denestpro4);
			   $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
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
	          " (SELECT CMP.codemp, CMP.procede, CMP.comprobante, CMP.fecha, CMP.tipo_destino, CMP.cod_pro, CMP.ced_bene, ".
		      "         PRV.nompro, BEN.apebene, BEN.nombene ".
	          " FROM 	sigesp_cmp CMP, rpc_proveedor PRV,rpc_beneficiario BEN ".
	          " WHERE 	CMP.codemp=PRV.codemp AND PRV.codemp=BEN.codemp AND BEN.codemp='".$ls_codemp."'AND  ".
              "         CMP.cod_pro=PRV.cod_pro AND CMP.ced_bene=BEN.ced_bene) rep2 ".
	          "  on 	rep1.codemp=rep2.codemp AND rep1.procede=rep2.procede AND rep1.comprobante=rep2.comprobante AND ".
			  "         rep1.fecha=rep2.fecha ".
              " ORDER BY rep1.comprobante,rep1.procede,rep1.fecha ";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_comprobante_formato1 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			$li_numrows=$this->SQL->num_rows($rs_data);
			if($li_numrows<=0)
			{
			   $lb_valido = false;
			}
			else
			{
				while($row=$this->SQL->fetch_row($rs_data))
				{
				  $ls_comprobante=$row["comprobante"];
				  $ls_procede=$row["procede"];
				  $ldt_fecha=$row["fecha"];
				  $ls_ced_bene=$row["ced_bene"];
				  $ls_cod_pro=$row["cod_pro"];
				  $ls_nompro=$row["nompro"];
				  $ls_apebene=$row["apebene"];
				  $ls_nombene=$row["nombene"];
				  $ls_tipo_destino=$row["tipo_destino"];
				  if($ls_comprobante!="0000000APERTURA")
				  {
					  $this->dts_cab->insertRow("comprobante",$ls_comprobante);
					  $this->dts_cab->insertRow("procede",$ls_procede);
					  $this->dts_cab->insertRow("fecha",$ldt_fecha);
					  $this->dts_cab->insertRow("ced_bene",$ls_ced_bene);
					  $this->dts_cab->insertRow("cod_pro",$ls_cod_pro);
					  $this->dts_cab->insertRow("nompro",$ls_nompro);
					  $this->dts_cab->insertRow("apebene",$ls_apebene);
					  $this->dts_cab->insertRow("nombene",$ls_nombene);
					  $this->dts_cab->insertRow("tipo_destino",$ls_tipo_destino);
				  }
			      $li_rows=$this->dts_cab->getRowCount("comprobante");
				  if($li_rows<=0)
				  {
			        $lb_valido = false;
				  }
				}
			}	
			$this->SQL->free_result($rs_data);
	    }//else
		return $lb_valido;
  }//uf_spg_reporte_select_comprobante_formato1
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/
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
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/	
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
  }//uf_spg_reporte_select_denestpro3
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_denestpro4($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,&$as_denestpro4)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro4
	 //         Access :	private
	 //     Argumentos :    $as_codestpro4 // codigo
	 //                     $as_denestpro4  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 4
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    31/10/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro4 ".
             " FROM   spg_ep4 ".
             " WHERE  codemp='".$ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro4 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_denestpro4=$row["denestpro4"];
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro4
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_denestpro5($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
	                                          &$as_denestpro5)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_denestpro5
	 //         Access :	private
	 //     Argumentos :    $as_codestpro5 // codigo
	 //                     $as_denestpro5  // denominacion
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la descripcion de la estructura programatica 5
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    31/10/2006         Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT denestpro5 ".
             " FROM   spg_ep5 ".
             " WHERE  codemp='".$ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			 "        codestpro3='".$as_codestpro3."' AND codestpro4='".$as_codestpro4."' AND codestpro5='".$as_codestpro5."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_denestpro5 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_denestpro5=$row["denestpro5"];
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_denestpro5
/********************************************************************************************************************************/	
   function  uf_spg_reporte_comprobante_formato2($as_spg_cuenta_ori,$as_spg_cuenta_des,$adt_fecini,$adt_fecfin,$as_comprobante,$as_procede)
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

	  $lb_valido = false;
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
                         "           cmp.comprobante=MOV.comprobante AND  MOV.comprobante='".$as_comprobante."' AND ".
	                     "           MOV.procede='".$as_procede."' AND   ".
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
	                     "           MOV.procede='".$as_procede."' AND   ".
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
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_comprobante_formato2 ".$this->fun->uf_convertirmsg($this->SQL->message);
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
			   if($lb_valido)
			   {
			     $ls_denestpro4="";
			     $lb_valido=$this->io_spg_report_funciones->uf_spg_reporte_select_denestpro4($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_denestpro4);
				 $ls_denestpro4=$ls_denestpro4;
			   }
			   $ls_codestpro5=$row["codestpro5"];
			   if($lb_valido)
			   {
			     $ls_denestpro5="";
			     $lb_valido=$this->io_spg_report_funciones->uf_spg_reporte_select_denestpro5($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_denestpro5);
				 $ls_denestpro5=$ls_denestpro5;
			   }
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
			   $this->dts_reporte->insertRow("denestpro1",$ls_denestpro1);
			   $this->dts_reporte->insertRow("denestpro2",$ls_denestpro2);
			   $this->dts_reporte->insertRow("denestpro3",$ls_denestpro3);
			   $this->dts_reporte->insertRow("denestpro4",$ls_denestpro4);
			   $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
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
/********************************************************************************************************************************/	
   function  uf_spg_reporte_select_comprobante_formato2($as_spg_cuenta_ori,$as_spg_cuenta_des,$adt_fecini,$adt_fecfin)
   {				 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_comprobante_formato2
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
	  $this->dts_cab->resetds("comprobante");
	  
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
         $ls_cad_filtro=" AND DT.spg_cuenta between '".$as_spg_cuenta_ori."' AND '".$as_spg_cuenta_des."' ";				 
	  } 
      else
	  {
	     $ls_cad_filtro="  ";	
	  }
       
	  if ((!empty($adt_fecini)) && (!empty($adt_fecfin)))
	  {
	     $ls_cad_where3=" AND DT.fecha between '".$adt_fecini."' AND '".$adt_fecfin."' ";
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
	  
	  $ls_sql=" SELECT distinct rep1.comprobante,rep1.procede,rep1.tipo_destino, rep1.cod_pro, ".
              "                 rep1.ced_bene, rep1.nompro, rep1.apebene, rep1.nombene ".
              " FROM spg_dt_cmp DT, (SELECT CMP.codemp,CMP.procede,CMP.comprobante,CMP.fecha, CMP.tipo_destino, CMP.cod_pro, ".
              "                             CMP.ced_bene, PRV.nompro, BEN.apebene, BEN.nombene ".
              "                      FROM  sigesp_cmp CMP, rpc_proveedor PRV, rpc_beneficiario BEN ".
              "                      WHERE CMP.codemp=PRV.codemp AND PRV.codemp=BEN.codemp AND BEN.codemp='".$ls_codemp."' AND ".
              "                            CMP.cod_pro=PRV.cod_pro AND CMP.ced_bene=BEN.ced_bene)rep1 ".
              " WHERE DT.codemp=rep1.codemp  AND rep1.codemp='".$ls_codemp."' ".$ls_cad_filtro." AND ".
              "       DT.comprobante=rep1.comprobante AND DT.procede=rep1.procede  ".$ls_cad_where." order by rep1.comprobante ";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_comprobante_formato2 ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		else
		{
			$li_numrows=$this->SQL->num_rows($rs_data);
			if($li_numrows<=0)
			{
			   $lb_valido = false;
			}
			else
			{
				while($row=$this->SQL->fetch_row($rs_data))
				{
				  $ls_comprobante=$row["comprobante"];
				  $ls_procede=$row["procede"];
				  $ls_ced_bene=$row["ced_bene"];
				  $ls_cod_pro=$row["cod_pro"];
				  $ls_nompro=$row["nompro"];
				  $ls_apebene=$row["apebene"];
				  $ls_nombene=$row["nombene"];
				  $ls_tipo_destino=$row["tipo_destino"];
				  if($ls_comprobante!="0000000APERTURA")
				  {
					  $this->dts_cab->insertRow("comprobante",$ls_comprobante);
					  $this->dts_cab->insertRow("procede",$ls_procede);
					  $this->dts_cab->insertRow("ced_bene",$ls_ced_bene);
					  $this->dts_cab->insertRow("cod_pro",$ls_cod_pro);
					  $this->dts_cab->insertRow("nompro",$ls_nompro);
					  $this->dts_cab->insertRow("apebene",$ls_apebene);
					  $this->dts_cab->insertRow("nombene",$ls_nombene);
					  $this->dts_cab->insertRow("tipo_destino",$ls_tipo_destino);
				  }
			      $li_rows=$this->dts_cab->getRowCount("comprobante");
				  if($li_rows<=0)
				  {
			        $lb_valido = false;
				  }
			   }
			}	
			$this->SQL->free_result($rs_data);   
	    }//else
		return $lb_valido;
 }//uf_spg_reporte_select_comprobante_formato2
/********************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "LISTADO DE APERTURAS DE CUENTAS" // 
	////////////////////////////////////////////////////////////
    function uf_spg_reporte_apertura( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,                                      $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
	                                  $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin )
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
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_apertura ERROR->".
		  $this->fun->uf_convertirmsg($this->SQL->message));
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
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_apertura( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,                                             $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
	                                         $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin )
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
	 // Fecha Creación :    09/05/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
	  $lb_valido=true;
	  $ls_gestor = $_SESSION["ls_gestor"];
      $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
	  $this->dts_cab->resetds("spg_cuenta");
      $ls_estructura_origen=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
      $ls_estructura_destino=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;	  
	  $ls_procede="SPGAPR";
	  if(strtoupper($ls_gestor)=="MYSQL")
	  {
			 $ls_cad_DM="CONCAT(DM.codestpro1,DM.codestpro2,DM.codestpro3,DM.codestpro4,DM.codestpro5,DM.spg_cuenta)";
			 $ls_cad_GC="CONCAT(GC.codestpro1,GC.codestpro2,GC.codestpro3,GC.codestpro4,GC.codestpro5,GC.spg_cuenta)";
			 $ls_cad_DM_SC="CONCAT(DM.codestpro1,DM.codestpro2,DM.codestpro3,DM.codestpro4,DM.codestpro5)";
			 $ls_cad_EP="CONCAT(EP.codestpro1,EP.codestpro2,EP.codestpro3,EP.codestpro4,EP.codestpro5)";	
			 $ls_programatica="CONCAT(DM.codestpro1,DM.codestpro2,DM.codestpro3,DM.codestpro4,DM.codestpro5)";
	  }
	  else
	  {
			 $ls_cad_DM="DM.codestpro1||DM.codestpro2||DM.codestpro3||DM.codestpro4||DM.codestpro5||DM.spg_cuenta";
			 $ls_cad_GC="GC.codestpro1||GC.codestpro2||GC.codestpro3||GC.codestpro4||GC.codestpro5||GC.spg_cuenta";
			 $ls_cad_DM_SC="DM.codestpro1||DM.codestpro2||DM.codestpro3||DM.codestpro4||DM.codestpro5";
			 $ls_cad_EP="EP.codestpro1||EP.codestpro2||EP.codestpro3||EP.codestpro4||EP.codestpro5";
			 $ls_programatica="(DM.codestpro1||DM.codestpro2||DM.codestpro3||DM.codestpro4||DM.codestpro5)";
	  }
	  
	  $ls_sql=" SELECT distinct ".$ls_programatica." as programatica,  EP.denestpro5 ".
              " FROM   spg_dt_cmp DM, spg_cuentas GC, spg_ep5 EP ".
              " WHERE  DM.codemp='".$ls_codemp."' AND DM.codemp=GC.codemp AND GC.codemp=EP.codemp AND ".
			  "        ".$ls_cad_DM." = ".$ls_cad_GC." AND  ".$ls_cad_DM_SC." = ".$ls_cad_EP." AND ".
			  "        DM.procede = '".$ls_procede."' AND ".
		   	  "        DM.fecha between '".$adt_fecini."' AND '".$adt_fecfin."' AND ".
		 	  "        ".$ls_cad_DM_SC."  between '".$ls_estructura_origen."' AND '".$ls_estructura_destino."' ".
              " ORDER BY programatica, EP.denestpro5 ";	 
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report 
		                          MÉTODO->uf_spg_reporte_select_apertura 
								  ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
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
   }//	uf_spg_reporte_select_apertura	
/********************************************************************************************************************************/	
	/////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "ACUMULADO POR CUENTAS"   // 
	/////////////////////////////////////////////////////
    function uf_spg_reporte_acumulado_cuentas( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                           $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
											   $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin,$ai_nivel,
											   $ab_subniveles,&$ai_MenorNivel,$as_cuentades,$as_cuentahas)
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
		$lb_valido = false;
		$lb_ok = true;
        $ld_total=0;
		$asignado_total=0;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->resetds("spg_cuenta");
        $ls_str_sql_where="";
		$dts_cuentas=new class_datastore();
        $this->uf_obtener_rango_programatica( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,                                              $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
                                              $as_codestpro4_des,$as_codestpro5_des,$ls_Sql_Where,$ls_str_estructura_from,
											  $ls_str_estructura_to);
		$ls_str_sql_where="WHERE codemp='".$ls_codemp."'";
		$ls_Sql_Where = trim($ls_Sql_Where);
		
        if (!empty($ls_Sql_Where) )
        {
           $ls_str_sql_where="WHERE codemp='".$ls_codemp."' AND ".$ls_Sql_Where;
        }
        $ls_mysql = " SELECT DISTINCT spg_cuenta, nivel, denominacion, asignado, status ".
		            " FROM   spg_cuentas PCT ".$ls_str_sql_where." AND (nivel<='".$ai_nivel."') AND ".
					"        spg_cuenta BETWEEN  '".$as_cuentades."' AND '".$as_cuentahas."' ".
					" ORDER BY spg_cuenta ";
		//print $ls_mysql;
		$rs_cuentas=$this->SQL->select($ls_mysql);
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
		       // Calculo lo Ejecutado y acumulado
			   if(!$this->uf_calcular_acumulado_operaciones_por_cuenta($ls_str_sql_where,$ls_str_estructura_from,
				                                                        $ls_str_estructura_to,$ls_spg_cuenta,
																		$adt_fecini,$adt_fecfin,$ldec_monto_asignado,   
																		$ldec_monto_aumento,$ldec_monto_disminucion,
																		$ldec_monto_precompromiso,$ldec_monto_compromiso,
																		$ldec_monto_causado,$ldec_monto_pagado))
																		
			   {
			     return false; 
			   } 
			   $ll_row_found = $this->dts_reporte->find("spg_cuenta",$ls_spg_cuenta);
			   if ($ll_row_found == 0)
			   {  
					$ldec_monto_actualizado = ($ldec_monto_asignado+$ldec_monto_aumento-$ldec_monto_disminucion);
					$ldec_saldo_comprometer = ($ldec_monto_asignado+$ldec_monto_aumento-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);
					//$ldec_por_pagar = ($ldec_monto_causado+$ldec_monto_causado_a)-($ldec_monto_pagado-$ldec_monto_pagado_a);
					$ldec_por_pagar = ($ldec_monto_causado)-($ldec_monto_pagado);
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
					$this->dts_reporte->insertRow("monto_actualizado",$ldec_monto_actualizado);
					$this->dts_reporte->insertRow("saldo_comprometer",$ldec_saldo_comprometer);
					$this->dts_reporte->insertRow("por_pagar",$ldec_por_pagar);		
					$this->dts_reporte->insertRow("status",$ls_status);		
					$lb_valido = true;
			    }//IF	
		   } // WHILE 
		   if($li_nivel==1)
		   {
			  $ld_total=$ld_total+$ldec_saldo_comprometer;
		   }//if($li_nivel==1) 
	  } //else
	 return $lb_valido;
   } // fin function uf_spg_reporte_acumulado_cuentas
/********************************************************************************************************************************/	
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
		 $ls_gestor = $_SESSION["ls_gestor"];
		 if(strtoupper($ls_gestor)=="MYSQL")
		 {
		   $ls_concat="CONCAT";
		   $ls_cadena=",";
		 }
		 else
		 {
		   $ls_concat="";
		   $ls_cadena="||";
		 }
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
		 if (($ls_CodEstPro1_desde!="********************") and ($ls_CodEstPro1_hasta!="********************"))
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
		 if (($ls_CodEstPro2_desde!='******') and ($ls_CodEstPro2_hasta!='******'))
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
		 if (($ls_CodEstPro3_desde!='***') and ($ls_CodEstPro3_hasta!='***'))
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
		 if (($ls_CodEstPro4_desde!='**') and ($ls_CodEstPro4_hasta!='**'))
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
		 if (($ls_CodEstPro5_desde!='**') and ($ls_CodEstPro5_hasta!='**'))
		 {
			$ls_str_w5  = "PCT.codestpro5))";
			$ls_str_w5f = $ls_CodEstPro5_desde;
			$ls_str_w5t = $ls_CodEstPro5_hasta;
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
            // print $as_Sql_Where."<br><br>";
		 } 
         else
		 {
             $as_Sql_Where="";
             $as_str_estructura_to="";
             $as_str_estructura_from="";
		 }
    } // fin function uf_obtener_rango_programatica
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operaciones_por_cuenta($as_str_sql_where,$as_str_estructura_from,$as_str_estructura_to,$as_spg_cuenta,
														  $adt_fecini,$adt_fecfin,&$adec_monto_asignado,&$adec_monto_aumento,&$adec_monto_disminucion,
														  &$adec_monto_precompromiso,&$adec_monto_compromiso,&$adec_monto_causado,&$adec_monto_pagado)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operaciones_por_cuenta -> proviene de uf_spg_reporte_acumulado_cuentas
     //	    Returns :	$lb_valido true si se realizo la funcion con exito o false en caso contrario
	 //	Description :	Método  que ejecuta todas funciones de acumulado para asi sacar el acumulado por cuentas
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido = true; 
	   $ls_codemp = $this->dts_empresa["codemp"];
	   $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	   /* ------ CONEXION PARA CORRER EL PROCEDIMIENTOS ALMACENADOS ------- */
	   $db = new mysqli($_SESSION["ls_hostname"], $_SESSION["ls_login"],$_SESSION["ls_password"], $_SESSION["ls_database"]);
	   /* ------ LLAMADA AL PROCEDIMIENTO ALMACENADO ------- */
	   $ls_sql="CALL acumulado_cuentas( '".$ls_codemp."','".$as_str_estructura_to."','".$as_spg_cuenta."','".$adt_fecini."','".$adt_fecfin."','".$as_str_estructura_from."')";
	 //  print $ls_sql."<br>";
	   /* ------ EJECUCION DEL PROCEDIMIENTO ALMACENADO ------- */
	   $rs_data=$db->query($ls_sql);
	   if($rs_data===false)
		{   // error interno sql
			$this->io_msg->message("Error en metodo uf_calcular_acumulado_operaciones_por_cuenta".$this->fun->uf_convertirmsg($this->SQL->message));
			print $this->SQL->message;
			$lb_valido = false;
		}
		else
		{
			/* ------ SE ENVIAN LOS MONTOS POR REFERENCIAS ------- */
			while($row=mysqli_fetch_row($rs_data))
			{
			 //print_r($row);
			 $adec_monto_asignado = $row[0];
			 $adec_monto_aumento = $row[1];
			 $adec_monto_disminucion = $row[2];
			 $adec_monto_precompromiso = $row[3];
			 $adec_monto_compromiso = $row[4];
			 $adec_monto_causado = $row[5];
			 $adec_monto_pagado = $row[6];
                                                         
			}
			//$this->SQL->free_result($rs_data);
		}
       return $lb_valido;
	} // fin uf_calcular_acumulado_operaciones_por_cuenta 
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_asignacion($as_str_sql_where,$as_spg_cuenta,&$adec_monto)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_asignacion( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma lo asignado por cuenta
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		
		if($_SESSION["ls_gestor"]=='INFORMIX')
		 {
		   $ls_mysql  = "SELECT Case SUM(monto) when null then 0 
       							else SUM(monto)
       							end monto
						FROM spg_dt_cmp PCT,spg_operaciones O ";			
		 }
		else
		 {
		   $ls_mysql  = "SELECT COALESCE(SUM(monto),0) As monto ".
                        "FROM spg_dt_cmp PCT,spg_operaciones O ";				 
		 }
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
/********************************************************************************************************************************/	
	function uf_calcular_acumulado_operacion_por_rango($as_str_sql_where,$adt_fecini,$adt_fecfin,$as_spg_cuenta,$adec_monto,$as_operacion)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_por_rango( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto asignado
	 //	Description :	Método que consulta y suma dependiando de la operacion(aumento,disminucion,precompromiso,compromiso)
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = "SELECT COALESCE(SUM(monto),0) as monto ".
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
	function uf_calcular_acumulado_operacion_anterior($as_str_sql_where,$adt_fecini,$as_spg_cuenta,$adec_monto,$as_operacion)
	{//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	   Function :	uf_calcular_acumulado_operacion_anterior( -> proviene de uf_calcular_acumulado_operaciones_por_cuenta
     //	    Returns :	Retorna monto aumento
	 //	Description :	Método que consulta y suma el aumento de la cuenta 
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	    $lb_valido = true;
		$ldec_monto=0;
		$ls_codemp = $this->dts_empresa["codemp"];
		$ls_mysql  = "SELECT COALESCE(SUM(monto),0) as monto ".
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
	/////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "MAYOR ANÁLITICO DE CUENTAS" // 
	////////////////////////////////////////////////////////
	function uf_spg_reporte_select_mayor_analitico( $as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                                $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
													$as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin,$as_cuenta_from,
													$as_cuenta_to,$as_orden,&$rs_data)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_mayor_analitico
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
		$lb_valido = true;	 
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_codemp = $this->dts_empresa["codemp"];
		$this->dts_cab->resetds("comprobante");
		$ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
		$ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;	
		//print('desde--> '.$ls_estructura_desde);
		//print('hasta--> '.$ls_estructura_hasta);
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
        $ls_sql=" SELECT distinct (".$ls_str_sql_2.") as programatica ".
                " FROM spg_dt_cmp MV,spg_operaciones OPE,spg_cuentas C ".
                " WHERE MV.codemp='".$ls_codemp."' AND (MV.operacion=OPE.operacion) AND MV.codestpro1=C.codestpro1 AND ".
                "       MV.codestpro2=C.codestpro2 AND MV.codestpro3=C.codestpro3 AND ".
                "       MV.codestpro4=C.codestpro4 AND MV.codestpro5=C.codestpro5 AND ".
                "       MV.spg_cuenta=C.spg_cuenta AND (".$ls_str_sql_2." ".
                "       BETWEEN '".$ls_estructura_desde."' AND '".$ls_estructura_hasta."') ".
                " ORDER BY programatica"; 
		//print($ls_sql);	
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{   // error interno sql
			$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_mayor_analitico ".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido = false;
		}
		return $lb_valido;
	 }//uf_spg_reporte_select_mayor_analitico
/********************************************************************************************************************************/	
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
      $ls_estructura=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;

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
	   	/* ------ CONEXION PARA CORRER EL PROCEDIMIENTOS ALMACENADOS ------- */
	   $db = new  mysqli($_SESSION["ls_hostname"], $_SESSION["ls_login"],$_SESSION["ls_password"], $_SESSION["ls_database"]);
	     /* ------ LLAMADA AL PROCEDIMIENTO ALMACENADO ------- */
	   $ls_sql="CALL mayor_analitico('".$ls_codemp."','".$ls_estructura."','".$as_cuenta_from."','".$as_cuenta_to."','". $adt_fecini."','".$adt_fecfin."')";
	 //  print($ls_sql);
	  // print  "<br>".($ls_sql)."<br>";
	   /* ------ EJECUCION DEL PROCEDIMIENTO ALMACENADO ------- */
	   $rs_data=$db->query($ls_sql);
	  }
	  else
	  { ///  pendiente postgre
	     $ls_str_sql_1 = "RTRIM(BEN.apebene)||', '||BEN.nombene ";	  
	     $ls_str_sql_2 = "MV.codestpro1||MV.codestpro2||MV.codestpro3||MV.codestpro4||MV.codestpro5";
	     $ls_str_sql_3 = "MOV.codestpro1||MOV.codestpro2||MOV.codestpro3||MOV.codestpro4||MOV.codestpro5";
	  }
	  if($rs_data===false)
	  {   // error interno sql
	     $this->io_msg->message("Error en Reporte 1".$this->fun->uf_convertirmsg($this->SQL->message));
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
	  	  while($row=mysqli_fetch_row($rs_data))
		  {
			  $ls_codestpro1=$row[6];
			  $ls_codestpro2=$row[7];
			  $ls_codestpro3=$row[8];
			  $ls_codestpro4=$row[9];
			  $ls_codestpro5=$row[10];			  
		      $ls_estructura_programatica = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			  
 	 	      $ls_spg_cuenta=$row[11];
			  
			  $ls_denominacion=$row[18];
			  $ls_operacion=$row[14];
			  $ldec_monto_operacion=$row[19];
			  $ls_procede=$row[1];
			  $ls_procede_doc=$row[12];
			  $ls_comprobante=$row[2];			  
			  $ls_documento =$row[13];			   
			  $ls_descripcion =$row[15];			    	
			  $ldt_fecha=$row[3];
			  $ls_nombre_prog=$row[15];
		      if ($ls_cuenta_actual!=$ls_spg_cuenta)
			  {
				  $ld_monto_actualizado=0;
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
			  $ldt_fecha_movimiento = $ldt_fecha;
			  $ldt_fecha_movimiento=substr($ldt_fecha_movimiento,0,10);
			  if ($ldt_fecha_movimiento < $adt_fecini )
			  {
				  $ldec_monto_asignado = 0;
				  $ldec_monto_aumento  = 0;		  
				  $ldec_monto_disminucion = 0;		 
				  $ldec_monto_precompromiso = 0;		 		   
				  $ldec_monto_compromiso = 0;		 		   		  
				  $ldec_monto_causado = 0;		 		   		  		  
				  $ldec_monto_pagado = 0;	
				  /* ------ LLAMADA AL PROCEDIMIENTO ALMACENADO ------- */
	  			  $ls_sql="CALL acumulado_cuentas_operacion_anterior
				  ('".$ls_codemp."','".$ls_spg_cuenta."','".$adt_fecini."','".$ls_estructura."')";
	   			  /* ------ EJECUCION DEL PROCEDIMIENTO ALMACENADO ------- */
	  			  $rs_anterior=$db->query($ls_sql);
				  if($rs_anterior===false)
				  {   // error interno sql
					 $this->io_msg->message("Error en Reporte 2".$this->fun->uf_convertirmsg($this->SQL->message));
					 return ;
				  }
				  else
				  {
					  while($row=mysqli_fetch_row($rs_anterior))
		  			  {	
						  $ldec_monto_asignado_a = $row[0]+$ldec_monto_asignado;
						  $ldec_monto_aumento_a  = $row[1]+$ldec_monto_aumento;		  
						  $ldec_monto_disminucion_a = $row[2]+$ldec_monto_disminucion;		 
						  $ldec_monto_precompromiso_a =$row[3]+$ldec_monto_precompromiso;		 		   
						  $ldec_monto_compromiso_a = $row[4]+$ldec_monto_compromiso;		 		   		  
						  $ldec_monto_causado_a =$row[5]+$ldec_monto_causado;		 		   		  		  
						  $ldec_monto_pagado_a =$row[6]+$ldec_monto_pagado;	
		  				  $ldec_monto_por_comprometer = $ldec_monto_por_comprometer+($ldec_monto_asignado+$ldec_monto_aumento-$ldec_monto_disminucion-$ldec_monto_precompromiso-$ldec_monto_compromiso);		
					  }	
				  }  		  		  	  
				 // $this->uf_calcular_monto_operaciones($ls_operacion,$ldec_monto_operacion,$ldec_monto_asignado,$ldec_monto_aumento,$ldec_monto_disminucion,
				//					                   $ldec_monto_precompromiso,$ldec_monto_compromiso,$ldec_monto_causado,$ldec_monto_pagado);				  
				  				  		  		  				  
			  } 
			  if (($ldt_fecha_movimiento >= $adt_fecini) and ($ldt_fecha_movimiento <= $adt_fecfin) and 
			      ($ls_spg_cuenta>=$as_cuenta_from) and ($ls_spg_cuenta<=$as_cuenta_to))
			  {
				  $ldec_monto_asignado = 0;
				  $ldec_monto_aumento  = 0;		  
				  $ldec_monto_disminucion = 0;		 
				  $ldec_monto_precompromiso = 0;		 		   
				  $ldec_monto_compromiso = 0;		 		   		  
				  $ldec_monto_causado = 0;		 		   		  		  
				  $ldec_monto_pagado = 0;	
				  
				  $db1 = new mysqli($_SESSION["ls_hostname"], $_SESSION["ls_login"],$_SESSION["ls_password"], $_SESSION["ls_database"]);
				  /* ------ LLAMADA AL PROCEDIMIENTO ALMACENADO ------- */
				  $ls_sql="CALL acumulado_cuentas_operacion('".$ls_codemp."','".$ls_spg_cuenta."','".$ls_estructura."','".$ls_procede."','". $ls_comprobante."','". $ls_documento."','".$ldt_fecha."','".$ls_procede_doc."')";
				// print_r($ls_sql);
	   			  /* ------ EJECUCION DEL PROCEDIMIENTO ALMACENADO ------- */
	  			  $rs_saldo=$db1->query($ls_sql);
				  if($rs_saldo===false)
				  {   // error interno sql
					 $this->io_msg->message("Error en Reporte 3".$this->fun->uf_convertirmsg($this->SQL->message));
					 return false;
				  }
				  else
				  {	
				  	   while($row=mysqli_fetch_row($rs_saldo))
		  			   {
							  $ldec_monto_asignado = $row[0];
							  $ldec_monto_aumento  = $row[1];		  
							  $ldec_monto_disminucion = $row[2];		 
							  $ldec_monto_precompromiso =$row[3];		 		   
							  $ldec_monto_compromiso = $row[4];		 		   		  
							  $ldec_monto_causado =$row[5];		 		   		  		  
							  $ldec_monto_pagado =$row[6];	
					   }	
				  } 		   		  		  		  
				  //$this->uf_calcular_monto_operaciones($ls_operacion,$ldec_monto_operacion,$ldec_monto_asignado,$ldec_monto_aumento,$ldec_monto_disminucion,
				  //				                   $ldec_monto_precompromiso,$ldec_monto_compromiso,$ldec_monto_causado,$ldec_monto_pagado);				  
				  if ($lb_previo==true)
				  {
					 $ld_monto_actualizado=$ld_monto_actualizado+($ldec_monto_asignado_a+$ldec_monto_aumento_a-$ldec_monto_disminucion_a);//Modificado por Ing Nelson Barraez el 20-12-2006
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
					 $this->dts_reporte->insertRow("monto_actualizado",$ld_monto_actualizado);
					 $lb_previo=false;
			      }
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
					 if($ls_procede=="SPGAPR")
					 {
					   $ldec_monto_asignado_apertura=$ldec_monto_asignado;
					 }
					 else
					 {
					   $ldec_monto_asignado_apertura=$ldec_monto_asignado;
					 }
				     $ld_monto_actualizado=$ld_monto_actualizado+($ldec_monto_asignado_apertura+$ldec_monto_aumento-$ldec_monto_disminucion);//Modificado por Ing Nelson Barraez el 20-12-2006
					 $this->dts_reporte->insertRow("aumento",$ldec_monto_aumento);
					 $this->dts_reporte->insertRow("disminucion",$ldec_monto_disminucion);
					 $this->dts_reporte->insertRow("precompromiso",$ldec_monto_precompromiso);
					 $this->dts_reporte->insertRow("compromiso",$ldec_monto_compromiso);
                     $this->dts_reporte->insertRow("causado",$ldec_monto_causado);					 
					 $this->dts_reporte->insertRow("pagado",$ldec_monto_pagado);
					 $this->dts_reporte->insertRow("monto_actualizado",$ld_monto_actualizado);
			  }
	      }// fin while  
 	  }
	  //$this->SQL->free_result($rs_mov_spg);	 
	  return true;
    } // end function uf_spg_reporte_mayor_analitico
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/	
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
	 // Fecha Creación :    18/04/2006          Fecha última Modificacion : 07/12/2006          Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_struc_programatica_ori=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
	 $ls_struc_programatica_des=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $ls_gestor = $_SESSION["ls_gestor"];
	 if(strtoupper($ls_gestor)=="MYSQL")
	 {
	    $ls_concat_programatica="CONCAT(PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,PCT.codestpro4,PCT.codestpro5)";
		$ls_cadena_montos="cast(0 as UNSIGNED) as aumentos_a, cast(0 as UNSIGNED) as disminuciones_a, cast(0 as UNSIGNED) as precompromisos_a, cast(0 as UNSIGNED) as compromisos_a";
	 }
	 else
	 {
	    $ls_concat_programatica="PCT.codestpro1||PCT.codestpro2||PCT.codestpro3||PCT.codestpro4||PCT.codestpro5";
		$ls_cadena_montos="cast(0 as float) as aumentos_a, cast(0 as float) as disminuciones_a, cast(0 as float) as precompromisos_a, cast(0 as float) as compromisos_a";
	 }
	 if($as_ckbctasinmov)
	 {
	    $ls_sql=" SELECT distinct PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4,PCT.codestpro5,PCT.spg_cuenta, ".
                "                 PCT.denominacion, PCT.status,PCT.asignado, PCT.precomprometido, PCT.comprometido,PCT.causado, ".
				"                 PCT.pagado,PCT.aumento, PCT.disminucion,PAT.denestpro5 as denestpro5, ".$ls_cadena_montos." ".
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
				"                 PCT.pagado,PCT.aumento,PCT.disminucion,PAT.denestpro5 ,cast(0 as money) as aumentosA, ".
				"                 cast(0 as money) as disminucionesA,cast(0 as money) as precompromisosA, ".
				"                 cast(0 as money) as compromisosA ".
                " FROM            spg_cuentas PCT, spg_ep5 PAT ".
                " WHERE           PCT.codestpro1=PAT.codestpro1 AND PCT.codestpro2=PAT.codestpro2 AND ".
				"                 PCT.codestpro3=PAT.codestpro3 AND PCT.codestpro4=PAT.codestpro4 AND ".
				"                 PCT.codestpro5=PAT.codestpro5 AND ".
				"                 ".$ls_concat_programatica." ".
                "                 between '".$ls_struc_programatica_ori."' AND '".$ls_struc_programatica_des."' AND ".
				"                 PCT.spg_cuenta between  '".$as_cuenta_from."' AND '".$as_cuenta_to."' ".
                " ORDER BY        PCT.codestpro1,PCT.codestpro2,PCT.codestpro3,PCT.codestpro4,PCT.codestpro5,PCT.spg_cuenta ";   
	 } 
	 //print $ls_sql."<br>";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		$lb_valido=false;
		$this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_select_cuenta ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
   	 }
	return $lb_valido; 
	}//fin
/********************************************************************************************************************************/	
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
            //print "ENTRO EN EL IF $ai_ckbhasfec==0"."<br>";;
			$lb_valido=$this->uf_spg_reporte_select_cuenta($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,$rs_data,
	                                      $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,$as_codestpro4_des,
										  $as_codestpro5_des,$as_cuenta_from,$as_cuenta_to,$as_ckbctasinmov);
			//print "PASÓ POR uf_spg_reporte_select_cuenta CON $lb_valido ==> .".$lb_valido."<br>";
			if($lb_valido)
			{
		     $li_numrows=$this->SQL->num_rows($rs_data);
		     if($li_numrows<=0)
		     {
			   $lb_valido=false;
		     }	
		     else
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
					 $ld_monto_aumento_a=$row["aumentos_a"];
					 $ld_monto_disminucion_a=$row["disminuciones_a"];
					 $ld_monto_precompromisos_a=$row["precompromisos_a"];
					 $ld_monto_compromisos_a=$row["compromisos_a"];
				   
				     $ld_asignado=$ld_monto_asignado+$ld_monto_aumento_a+$ld_monto_aumento-$ld_monto_disminucion_a-$ld_monto_disminucion;
					 $ld_disponible=$ld_monto_asignado+$ld_monto_aumento-$ld_monto_disminucion-$ld_monto_compromiso-$ld_monto_precomprometido;
					 $this->dts_reporte->insertRow("programatica",$ls_programatica);
				     $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
				     $this->dts_reporte->insertRow("denominacion",$ls_denominacion);
				     $this->dts_reporte->insertRow("status",$ls_status);
				     $this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
				     $this->dts_reporte->insertRow("asignado",$ld_asignado);
				     $this->dts_reporte->insertRow("disponible",$ld_disponible);
				     $lb_valido = true;
	    	    }//while
			  } //else
			}//if
			else
			{
			  $lb_valido=false;
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
			$ls_gestor = $_SESSION["ls_gestor"];
			if(strtoupper($ls_gestor)=="MYSQL")
			{
			   $ls_concat="CONCAT(PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4,PCT.codestpro5)";
			}
			else
			{
			   $ls_concat="(PCT.codestpro1||PCT.codestpro2||PCT.codestpro3||PCT.codestpro4||PCT.codestpro5)";
			}
			$ls_sql=" SELECT ".$ls_concat." AS programatica, PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4, ".
					"        PCT.codestpro5, PCT.spg_cuenta, PCT.denominacion, PCT.status, EP.denestpro5  ".
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
					$ld_disponible=$ld_monto_asignado+$ld_monto_aumento-$ld_monto_disminucion-$ld_monto_compromiso-$ld_monto_precompromiso;
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
/********************************************************************************************************************************/	
    function uf_spg_reporte_disponibilidad_formato2($as_codestpro1_ori,$as_codestpro2_ori,$as_codestpro3_ori,$as_codestpro4_ori,
	                                                $as_codestpro5_ori,$as_codestpro1_des,$as_codestpro2_des,$as_codestpro3_des,
												    $as_codestpro4_des,$as_codestpro5_des,$adt_fecini,$adt_fecfin,
												    $as_cuenta_from,$as_cuenta_to)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_disponibilidad_formato2
	 //         Access :	private
	 //     Argumentos :    as_codestpro1_ori .. $as_codestpro5_ori  // codigo de la estructura programatica origen 
     //              	    as_codestpro1_des .. $as_codestpro5_des  // codigo de la estructura programatica destino
	 //                     $adt_fecini   //  fecha de inicio 
	 //                     $adt_fecfin  // fecha de  fin
	 //                     $as_cuenta_from   //  cuenta desde
	 //                     $as_cuenta_to    //  desde hasta 
     //	       Returns :	Retorna true en caso de exito de la consulta o false en otro caso 
	 //	   Description :	Reporte que genera la salida para el disponible presupuestario acumulada segun fecha seleccionada 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/01/2007          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe = false;	 
		$lb_valido = true;
		$ls_codemp = $this->dts_empresa["codemp"];
	    $this->dts_reporte->reset_ds();
        $ls_str_sql_where="";
		$dts_disponible=new class_datastore();
		$rs_data=0;
		$ls_estructura_desde=$as_codestpro1_ori.$as_codestpro2_ori.$as_codestpro3_ori.$as_codestpro4_ori.$as_codestpro5_ori;
		$ls_estructura_hasta=$as_codestpro1_des.$as_codestpro2_des.$as_codestpro3_des.$as_codestpro4_des.$as_codestpro5_des;
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
		$ls_gestor = $_SESSION["ls_gestor"];
		if(strtoupper($ls_gestor)=="MYSQL")
		{
		   $ls_concat="CONCAT(PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4,PCT.codestpro5)";
		}
		else
		{
		   $ls_concat="(PCT.codestpro1||PCT.codestpro2||PCT.codestpro3||PCT.codestpro4||PCT.codestpro5)";
		}
		$ls_sql=" SELECT ".$ls_concat." AS programatica, PCT.codestpro1, PCT.codestpro2, PCT.codestpro3, PCT.codestpro4, ".
				"        PCT.codestpro5, PCT.spg_cuenta, PCT.denominacion, PCT.status, EP.denestpro5  ".
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
				else
				{	
					$ld_asignado=$ld_monto_asignado+$ld_monto_aumento_a+$ld_monto_aumento-$ld_monto_disminucion_a-$ld_monto_disminucion;
					$ld_disponible=$ld_monto_asignado+$ld_monto_aumento-$ld_monto_disminucion-$ld_monto_compromiso-$ld_monto_precompromiso;
                    
					$ld_monto_ejecutado=0;
					$ld_monto_acumulado=0;
					$ldt_fecini=$this->fun->uf_convertirfecmostrar($adt_fecini);
					$ldt_fecfin=$this->fun->uf_convertirfecmostrar($adt_fecfin);
					$lb_valido=$this->uf_spg_reporte_calcular_ejecutado($ls_spg_cuenta,$ls_estructura_desde,$ls_estructura_hasta,
					                                                    $ldt_fecini,$ldt_fecfin,$ld_monto_ejecutado,$ld_monto_acumulado);					
					
					$this->dts_reporte->insertRow("programatica",$ls_programatica);
					$this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);
					$this->dts_reporte->insertRow("denominacion",$ls_denominacion);
					$this->dts_reporte->insertRow("status",$ls_status);
					$this->dts_reporte->insertRow("denestpro5",$ls_denestpro5);
					$this->dts_reporte->insertRow("asignado",$ld_asignado);
					$this->dts_reporte->insertRow("disponible",$ld_disponible);
					$this->dts_reporte->insertRow("monto_ejecutado",$ld_monto_ejecutado);
					$this->dts_reporte->insertRow("monto_acumulado",$ld_monto_acumulado);
					$lb_valido = true;
			    }	
		   }//for
	   }//else 
  return $lb_valido;
}//fin uf_spg_reporte_disponibilidad_formato2
/****************************************************************************************************************************************/	
    function uf_spg_reporte_calcular_ejecutado($as_spg_cuenta,$as_estructura_desde,$as_estructura_hasta,$ai_mesdes,
	                                           $ai_meshas,&$ad_monto_ejecutado,&$ad_monto_acumulado)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_calcular_ejecutado
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta
	 //                     $as_estructura_desde  // codigo programatico desde
	 //                     $as_estructura_hasta  //  codigo programatico hasta
	 //                     $as_mesdes  // mes  desde
     //              	    $as_meshas  // mes hasta
	 //                     $ad_monto_ejecutado // monto ejecutado (referencia)  
	 //                     $ad_monto_acumulado // monto acumulado (referencia) 
     //	       Returns :	Retorna true o false si se realizo el metodo para el reporte
	 //	   Description :	Reporte que genera salida para  la ejecucucion financiera
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/01/2007          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido = true;
	  $ldt_periodo=$_SESSION["la_empresa"]["periodo"];
	  $li_ano=substr($ldt_periodo,0,4);
	  $ls_gestor = $_SESSION["ls_gestor"];
	  $ls_codemp = $this->dts_empresa["codemp"];
	  if (strtoupper($ls_gestor)=="MYSQL")
	  {
		   $ls_cadena="CONCAT(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)";
	  }
	  else
	  {
		   $ls_cadena="codestpro1||codestpro2||codestpro3||codestpro4||codestpro5";
	  }
	  $as_spg_cuenta=$this->sigesp_int_spg->uf_spg_cuenta_sin_cero($as_spg_cuenta)."%";
	  $ls_sql=" SELECT DT.fecha, DT.monto, OP.aumento, OP.disminucion, OP.precomprometer,OP.comprometer, OP.causar, OP.pagar ".
              " FROM   spg_dt_cmp DT, spg_operaciones OP ".
              " WHERE  DT.codemp='".$ls_codemp."' AND (DT.operacion = OP.operacion) AND ".
              "        ".$ls_cadena." between  '".$as_estructura_desde."' AND '".$as_estructura_hasta."'  AND ".
              "        spg_cuenta like '".$as_spg_cuenta."' ";
	  $rs_ejec=$this->SQL->select($ls_sql);
	  if($rs_ejec===false)
	  { // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_calcular_ejecutado".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
 	  }
	  else
	  {
		while($row=$this->SQL->fetch_row($rs_ejec))
		{
		  $li_comprometer=$row["comprometer"];
		  $ld_monto=$row["monto"];
		  $ldt_fecha_db=$row["fecha"];
		  $ldt_fecha=substr($ldt_fecha_db,0,7);
		
		  if(($li_comprometer)&&($ldt_fecha>=$ai_mesdes)&&($ldt_fecha<=$ai_meshas))
		  { 
		  	$ad_monto_ejecutado=$ad_monto_ejecutado+$ld_monto;
		  }//if
		  if(($li_comprometer)&&($ldt_fecha<=$ai_meshas))
		  {  
		 	 $ad_monto_acumulado=$ad_monto_acumulado+$ld_monto;
		  }//if
		}//while  
	   $this->SQL->free_result($rs_ejec);
	  }//else	
	  return $lb_valido;	
   }//fin uf_spg_reporte_calcular_ejecutado
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
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "MODIFICACIONES PRESUPUESTARIAS " // 
	////////////////////////////////////////////////////////////
    function uf_spg_reporte_modificaciones_presupuestarias($ai_rect,$ai_trans,$ai_insub,$ai_cred,$adt_fecini,$adt_fecfin,
	                                                       $as_comprobante,$as_procede,$adt_fecha)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_modificaciones_presupuestarias
	 //         Access :	private
	 //     Argumentos :    adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true en caso de exito de la consulta o false en otro caso 
	 //	   Description :	Reporte que genera la salida para las modificaciones presupuestarias 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    15/04/2006          Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_gestor = $_SESSION["ls_gestor"];
      $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
      $ls_cad    = $this->uf_spg_reporte_chequear_modificaciones($ai_rect,$ai_insub,$ai_trans,$ai_cred);
	  $ls_cadena = str_replace('procede','MOV.procede',$ls_cad);
	  if (empty($as_comprobante))
	  {
	    $ls_cadena_2="";
	  }
	  else
	  {
	    $ls_cadena_2=" AND CMP.comprobante='".$as_comprobante."' AND CMP.procede='".$as_procede."'".
		             " AND MOV.fecha='".$adt_fecha."' ";
	  }
	  $ls_sql=" SELECT CMP.descripcion as cmp_descripcion, CMP.fecha as fecha, MOV.*, ".
	          "        CTA.denominacion         									  ".               
              "   FROM sigesp_cmp CMP,spg_dt_cmp MOV, spg_cuentas CTA				  ".
              "  WHERE CMP.codemp='".$ls_codemp."' 									  ".
			  "    AND (".$ls_cadena.")  											  ".
			  "    AND MOV.fecha between '".$adt_fecini."' 							  ".
			  "    AND '".$adt_fecfin."' 											  ".
			  "    AND CMP.tipo_comp   = 2  ".$ls_cadena_2." 						  ".
			  "    AND CMP.codemp      = MOV.codemp 								  ".
			  "    AND CMP.procede     = MOV.procede								  ".
			  "    AND CMP.comprobante = MOV.comprobante							  ".
			  "    AND CMP.fecha       = MOV.fecha 									  ".
			  "    AND MOV.codemp      = CTA.codemp									  ".
			  "    AND MOV.codestpro1  = CTA.codestpro1 							  ".
			  "    AND MOV.codestpro2  = CTA.codestpro2 							  ".
			  "    AND MOV.codestpro3  = CTA.codestpro3 							  ".
			  "    AND MOV.codestpro4  = CTA.codestpro4 							  ".
			  "    AND MOV.codestpro5  = CTA.codestpro5 							  ".
			  "    AND MOV.spg_cuenta  = CTA.spg_cuenta 							  ".
			  " ORDER BY  MOV.comprobante,MOV.spg_cuenta 							  ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;//print "ejele";
		  $this->io_msg->message("CLASE->sigesp_spg_class_report 
		                          MÉTODO->uf_spg_reporte_modificaciones_presupuestarias 
								  ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
	 } 
	 else 
	 {
			while($row=$this->SQL->fetch_row($rs_data))
			{
				  $ls_cmp_descripcion=$row["cmp_descripcion"]; 
				  $ls_procede=$row["procede"]; 
				  $ls_comprobante=$row["comprobante"]; 
				  $ldt_fecha=$row["fecha"]; 
				  $ldt_fecaprmod = $row["fecha"]; 
				  $ls_codestpro1=$row["codestpro1"]; 
				  $ls_codestpro2=$row["codestpro2"]; 
				  $ls_codestpro3=$row["codestpro3"]; 
				  $ls_codestpro4=$row["codestpro4"]; 
				  $ls_codestpro5=$row["codestpro5"]; 
				  $ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
				  $ls_spg_cuenta=$row["spg_cuenta"]; 
				  $ls_denominacion=$row["denominacion"]; 
				  $ls_procede_doc=$row["procede_doc"]; 
				  $ls_documento=$row["documento"]; 
				  $ls_operacion=$row["operacion"]; 
				  $ls_descripcion=$row["descripcion"]; 
				  $ld_monto=$row["monto"]; 
				  $ls_orden=$row["orden"]; 
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
 				 $ls_procomp=$ls_procede.$ls_comprobante;
				 $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
				 $this->dts_reporte->insertRow("cmp_descripcion",$ls_cmp_descripcion);	
				 $this->dts_reporte->insertRow("programatica",$ls_programatica);			
				 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);			
				 $this->dts_reporte->insertRow("documento",$ls_documento);			
				 $this->dts_reporte->insertRow("fecha",$ldt_fecha);			
				 $this->dts_reporte->insertRow("aumento",$ld_aumento);			
				 $this->dts_reporte->insertRow("disminucion",$ld_disminucion);			
				 $this->dts_reporte->insertRow("fecaprmod",$ldt_fecaprmod);			
				 $this->dts_reporte->insertRow("procede",$ls_procede);
				 $this->dts_reporte->insertRow("procomp",$ls_procomp);
				 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);	
			}//while
			$li_tot=$this->dts_reporte->getRowCount("spg_cuenta");
			if($li_tot==0)
			{$lb_valido=false;}
	  $this->SQL->free_result($rs_data);	 
	 }//else
  return $lb_valido;
}//fin uf_spg_reporte_modificaciones_presupuestarias
/********************************************************************************************************************************/	
	//////////////////////////////////////////////////////////////////////////
	//   CLASE REPORTES SPG  "MODIFICACIONES PRESUPUESTARIAS NO APROBADAS " // 
	/////////////////////////////////////////////////////////////////////////
    function uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas($ai_rect,$ai_trans,$ai_insub,$ai_cred,$adt_fecini,
																		$adt_fecfin,$as_comprobante,$as_procede,$adt_fecha)
    {////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas
	 //         Access :	private
	 //     Argumentos :    adt_fecini  // fecha  desde 
     //              	    adt_fecfin  // fecha hasta 
     //	       Returns :	Retorna true en caso de exito de la consulta o false en otro caso 
	 //	   Description :	Reporte que genera la salida para las modificaciones presupuestarias 
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    15/04/2006          Fecha última Modificacion :      Hora :
  	 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_gestor = $_SESSION["ls_gestor"];
      $ls_codemp = $this->dts_empresa["codemp"];
	  $this->dts_reporte->resetds("spg_cuenta");
      $ls_cad=$this->uf_spg_reporte_chequear_modificaciones($ai_rect,$ai_insub,$ai_trans,$ai_cred);
      $ls_cadena=str_replace('procede','MOV.procede',$ls_cad);
	  if (empty($as_comprobante))
	  {
	    $ls_cadena_2="";
	  }
	  else
	  {
	    $ls_cadena_2=" AND CMP.comprobante='".$as_comprobante."' AND CMP.procede='".$as_procede."'  AND MOV.fecha='".$adt_fecha."' ";
	  }
	  $ls_sql=" SELECT CMP.descripcion as cmp_descripcion, CMP.fecha as fecha, MOV.*,CTA.denominacion".
              "   FROM spg_dtmp_cmp MOV, sigesp_cmp_md CMP,spg_cuentas CTA ".
              "  WHERE CMP.codemp='".$ls_codemp."' ".
			  "    AND (".$ls_cadena.")  ".
			  "    AND MOV.fecha between '".$adt_fecini."' ".
			  "    AND '".$adt_fecfin."' ".
			  "    AND CMP.tipo_comp=2  ".
			  "    AND CMP.estapro=0  ".$ls_cadena_2." ".
			  "    AND CMP.codemp=MOV.codemp ".
			  "    AND MOV.codemp=CTA.codemp ".
			  "    AND CMP.procede=MOV.procede ".
			  "    AND CMP.comprobante=MOV.comprobante ".
			  "    AND CMP.fecha=MOV.fecha ".
			  "    AND MOV.codestpro1 = CTA.codestpro1 ".
			  "    AND MOV.codestpro2 = CTA.codestpro2 ".
			  "    AND MOV.codestpro3 = CTA.codestpro3 ".
			  "    AND MOV.codestpro4 = CTA.codestpro4 ".
			  "    AND MOV.codestpro5 = CTA.codestpro5 ".
			  "    AND MOV.spg_cuenta = CTA.spg_cuenta ".
              " ORDER BY  MOV.comprobante ";//print "Sentencia => ".$ls_sql.'<br>';
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_spg_class_report MÉTODO->uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas ERROR->".$this->fun->uf_convertirmsg($this->SQL->message));
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
				  $ls_denominacion=$row["denominacion"]; 
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
                 $ls_procomp=$ls_procede.$ls_comprobante;
				 $this->dts_reporte->insertRow("comprobante",$ls_comprobante);
				 $this->dts_reporte->insertRow("cmp_descripcion",$ls_cmp_descripcion);	
				 $this->dts_reporte->insertRow("programatica",$ls_programatica);			
				 $this->dts_reporte->insertRow("spg_cuenta",$ls_spg_cuenta);			
				 $this->dts_reporte->insertRow("documento",$ls_documento);			
				 $this->dts_reporte->insertRow("fecha",$ldt_fecha);			
				 $this->dts_reporte->insertRow("aumento",$ld_aumento);			
				 $this->dts_reporte->insertRow("disminucion",$ld_disminucion);			
				 $this->dts_reporte->insertRow("procede",$ls_procede);
				 $this->dts_reporte->insertRow("denominacion",$ls_denominacion);	
   				 $this->dts_reporte->insertRow("procomp",$ls_procomp);

			}//while
			$li_tot=$this->dts_reporte->getRowCount("spg_cuenta");
			if($li_tot==0)
			{$lb_valido=false;}
	  $this->SQL->free_result($rs_data);	 
	 }//else
  return $lb_valido;
}//fin uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas
/********************************************************************************************************************************/	
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
	 
	 if($ai_rect==1) { $ls_cadena1="procede ='SPGREC' AND procede_doc='SPGREC' OR "; }
	 else{ $ls_cadena1="";}
	 if($ai_insub==1) { $ls_cadena2="procede ='SPGINS' AND procede_doc='SPGINS' OR "; }
	 else{ $ls_cadena2="";}
	 if($ai_trans==1) { $ls_cadena3="procede ='SPGTRA' AND procede_doc='SPGTRA' OR "; }
	 else{ $ls_cadena3="";}
	 if($ai_cred==1) { $ls_cadena4="procede ='SPGCRA' AND procede_doc='SPGCRA' OR "; }
	 else{ $ls_cadena4="";}
	 
	 $ls_cadena=$ls_cadena1.$ls_cadena2.$ls_cadena3.$ls_cadena4;
	 if(!empty($ls_cadena))
	 {
	   $ls_cadena=substr($ls_cadena,0,strlen($ls_cadena)-3);
	 }
	 return $ls_cadena;
}//uf_spg_reporte_chequear_modificaciones
/********************************************************************************************************************************/	
    function uf_spg_reporte_calcular_monto_operacion(&$ad_asignado,&$ad_aumentos,&$ad_disminuciones,&$ad_precompromisos,
	                                                 &$ad_compromisos,&$ad_causado,&$ad_pagado,$as_operacion,$ad_monto)
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
/********************************************************************************************************************************/	
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
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_cuenta(&$as_spg_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta minima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(spg_cuenta) as spg_cuenta ".
             " FROM spg_cuentas ".
             " WHERE codemp = '".$ls_codemp."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_cuenta ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_spg_cuenta=$row["spg_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_cuenta
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_cuenta(&$as_spg_cuenta)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_cuenta
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(spg_cuenta) as spg_cuenta ".
             " FROM spg_cuentas ".
             " WHERE codemp = '".$ls_codemp."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_cuenta ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_spg_cuenta=$row["spg_cuenta"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_cuenta
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_programatica
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 /*$lb_valido=$this->uf_spg_reporte_select_min_codestpro1(&$as_codestpro1);
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2);
	 }
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3);
	 }*/
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 		     "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3 ".
             " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".
             " WHERE  a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND ".
             "        a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2  AND c.codestpro3 like '%' AND c.denestpro3 like '%' ".
             " ORDER BY  codestpro1  limit 1 ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_codestpro2=$row["codestpro2"];
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_cuenta
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro1) as codestpro1 ".
             " FROM   spg_ep1 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro1
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2  // codigo de estructura programatica 2 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro2 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro2
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_min_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_min_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT min(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$ls_codemp."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro3 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_min_codestpro3
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_programatica(&$as_codestpro1,&$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_programatica
	 //         Access :	private
	 //     Argumentos :    $as_spg_cuenta  // cuenta maxima (referencias)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve la cuenta minima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=$this->uf_spg_reporte_select_max_codestpro1(&$as_codestpro1);
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2);
	 }
	 if($lb_valido)
	 {
		 $lb_valido=$this->uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3);
	 }
	 /*$ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 		     "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3 ".
             " FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".
             " WHERE  a.codemp=b.codemp AND a.codemp=c.codemp AND a.codemp='".$ls_codemp."' AND a.codestpro1=b.codestpro1 AND ".
             "        a.codestpro1=c.codestpro1  AND b.codestpro2=c.codestpro2  AND c.codestpro3 like '%' AND c.denestpro3 like '%' ".
             " ORDER BY  codestpro1 desc limit 1 ";
			 print $ls_sql;
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_min_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		   $as_codestpro2=$row["codestpro2"];
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else*/
	return $lb_valido;
  }//uf_spg_reporte_select_max_programatica
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro1(&$as_codestpro1)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro1
	 //         Access :	private
	 //     Argumentos :    $as_codestpro1  // codigo de estructura programatica 1 (referencia)
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql="SELECT * FROM spg_ep1 WHERE codemp='".$ls_codemp."' ORDER BY codestpro1  desc limit 1 ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro1 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro1=$row["codestpro1"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro1
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro2($as_codestpro1,&$as_codestpro2)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro2
	 //         Access :	private
	 //     Argumentos :    $as_codestpro2  // codigo de estructura programatica 2 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1  maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro2) as codestpro2 ".
             " FROM   spg_ep2 ".
             " WHERE  codemp = '".$ls_codemp."' AND codestpro1='".$as_codestpro1."' ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro2 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro2=$row["codestpro2"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro2
/********************************************************************************************************************************/	
    function uf_spg_reporte_select_max_codestpro3($as_codestpro1,$as_codestpro2,&$as_codestpro3)
    {//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_spg_reporte_select_max_codestpro3
	 //         Access :	private
	 //     Argumentos :    $as_codestpro3  // codigo de estructura programatica 3 (referencia)
	 //                     $as_codestpro1  // codigo de estructura programatica 1 
	 //                     $as_codestpro2  // codigo de estructura programatica 2           
     //	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	devuelve el codigo de estructura programatica 1 maxima de la tabla spg_cuentas
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    19/07/2006          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->dts_empresa["codemp"];
	 $lb_valido=true;
	 $ls_sql=" SELECT max(codestpro3) as codestpro3 ".
             " FROM   spg_ep3 ".
             " WHERE  codemp = '".$ls_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."'  ";
	 $rs_data=$this->SQL->select($ls_sql);
	 if($rs_data===false)
	 {   // error interno sql
		$this->is_msg_error="Error en consulta metodo uf_spg_reporte_select_max_codestpro3 ".$this->fun->uf_convertirmsg($this->SQL->message);
		$lb_valido = false;
	 }
	 else
	 {
		if($row=$this->SQL->fetch_row($rs_data))
		{
		   $as_codestpro3=$row["codestpro3"];
		}
		else
		{
		   $lb_valido = false;
		}
		$this->SQL->free_result($rs_data);
	 }//else
	return $lb_valido;
  }//uf_spg_reporte_select_max_codestpro3
/********************************************************************************************************************************/	
}//fin de clase
?>