<?php 
class sigesp_apr_c_salcontables
{
	//-----------------------------------------------------------------------------------------------------------------------------------
    function sigesp_apr_c_salcontables()
    {
		$ld_fecha=date("_d-m-Y");
		$ls_nombrearchivo="resultado/".$_SESSION["ls_data_des"]."_saldos_contables_result_".$ld_fecha.".txt";
		$this->lo_archivo=@fopen("$ls_nombrearchivo","a+");
		$this->ls_database_source=$_SESSION["ls_database"];
		$this->ls_dabatase_target=$_SESSION["ls_data_des"];
		require_once("../shared/class_folder/class_sql.php");  
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("class_folder/class_sigesp_int.php");
		require_once("class_folder/class_sigesp_int_int.php");
		require_once("class_folder/class_sigesp_int_spg.php");
		require_once("class_folder/class_sigesp_int_scg.php");
		require_once("class_folder/class_sigesp_int_spi.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		$this->io_rcbsf	 = new sigesp_c_reconvertir_monedabsf(); 
		require_once("class_folder/class_fecha.php");
        $this->io_sigesp_int=new class_sigesp_int_int();
		$this->io_function=new class_funciones() ;

		$io_conect	= new sigesp_include();
		$io_conexion_origen = $io_conect->uf_conectar();
		$io_conexion_destino = $io_conect->uf_conectar($this->ls_dabatase_target);
		$this->io_sql_origen = new class_sql($io_conexion_origen);
		$this->io_sql_destino = new class_sql($io_conexion_destino);

		$this->io_function=new class_funciones();
		$this->ds_scg=new class_datastore();
		$this->ds_saldos_contables=new class_datastore();
		$this->io_msg=new class_mensajes();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_periodo=$_SESSION["la_empresa"]["periodo"];
		$this->io_seguridad=new sigesp_c_seguridad();
    }
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_procesar_apertura_ejercicio($as_procede,$as_comprobante,$as_ced_ben,$as_cod_prov,$as_tipo,$as_tipo_cmp,
	                                        $as_descripcion,$aa_seguridad)
	{    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_procesar_apertura_ejercicio
		// 	   Access:  public
		//  Arguments:  $as_procede----> procede,
		//              $as_comprobante----> comprobante,
		//              $as_ced_ben-----> cedula del beneficiario,
		//	            $as_cod_prov----> codigo del proveedor ,
		//              $as_tipo-----> tipo ,
		//              $as_tipo_cmp----->  tipo del comprobante ,
		//              $as_descripcion->descripcion del movimiento,
		//	  Returns:  $lb_valido ---> Boolean
		//Description:  Procesa un comprobante  con la apertura del ejercicio.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*--------- Procesamos  una consulta donde traemos los saldos de la base de datos origen y luego utilizamos un 
		      datastore para almecenar los datos------------ */
		$this->ds_scg->reset_ds();
		$this->ds_saldos_contables->reset_ds();
		$ld_fecdesde=$this->io_function->uf_convertirdatetobd($this->ls_periodo);
		$ld_fecdesde=substr($ld_fecdesde,0,10);
		$li_ano=substr($this->ls_periodo,0,4);
		$ld_fechasta="31/12/".$li_ano;
		$ld_fechasta=$this->io_function->uf_convertirdatetobd($ld_fechasta);
		$ls_sql=" SELECT curbb.*,scta.status ".
		        " FROM (  SELECT DISTINCT B.sc_cuenta as sc_cuenta,B.denominacion as denominacion,B.saldo_Ant as saldo_ant, ".
                "                  B.debe as debe,B.haber as haber,B.saldo_act as saldo_act,C.T_DEBE_MES as t_debe_mes, ".
                "                  C.T_HABER_MES as t_haber_mes,COALESCE(C.T_DEBE_MES,0) as BalDebe, ".
                "                  COALESCE(C.T_HABER_MES,0) as BalHABER ".
                " FROM ( SELECT A.sc_cuenta,A.denominacion,saldo_ant,COALESCE(curSACT.T_DEBE_MES,0) as Debe, ".
                "               COALESCE(curSACT.T_HABER_MES,0) as Haber, ".
                "               (COALESCE(Saldo_Ant,0)+COALESCE(curSACT.T_DEBE_MES,0) - COALESCE(curSACT.T_HABER_MES,0)) as Saldo_Act ".
                "        FROM (SELECT CCT.sc_cuenta,CCT.denominacion,CCT.nivel,COALESCE(curSANT.SANT,0) as Saldo_Ant ".
                "              FROM scg_cuentas CCT ".
                " LEFT OUTER JOIN ( SELECT CSD.sc_cuenta,SUM(debe_mes-haber_mes) AS SANT ".
                "                   FROM scg_saldos CSD ".
                "                   WHERE CSD.codemp='".$this->ls_codemp."' AND  CSD.fecsal < '".$ld_fecdesde."' ".
                "                   GROUP BY CSD.sc_cuenta ) curSANT  ".
                " ON  CCT.sc_cuenta=curSANT.sc_cuenta ) A ".
                " LEFT OUTER JOIN ( SELECT CSD.sc_cuenta, COALESCE(SUM(debe_mes),0) As T_DEBE_MES, ".
				"                          COALESCE(SUM(haber_mes),0) As T_HABER_MES ".
                "                   FROM scg_saldos CSD ".
                "                   WHERE CSD.codemp='".$this->ls_codemp."'  AND ".
                "                         CSD.fecsal between '".$ld_fecdesde."' AND '".$ld_fechasta."' ".
                "                   GROUP BY CSD.sc_cuenta ) curSACT ON A.sc_cuenta=curSACT.sc_cuenta ".
                " WHERE (A.nivel<=7)) B, ".
                " ( SELECT COALESCE(sum(DEBE_MES),0) as T_DEBE_MES, COALESCE(sum(HABER_MES),0) as T_HABER_MES ".
                "   FROM  scg_cuentas CCT, scg_saldos CSD ".
                "   WHERE CCT.codemp='".$this->ls_codemp."' AND (CCT.sc_cuenta=CSD.sc_cuenta) AND ".
                "         CSD.fecsal between '".$ld_fecdesde."' AND '".$ld_fechasta."' AND (CCT.nivel=1) ) C ".
                "   ORDER BY B.sc_cuenta ) as curbb, scg_cuentas scta ".
                " WHERE curbb.sc_cuenta=scta.sc_cuenta  AND  scta.status='C' ";
		$resultSCG=$this->io_sql_origen->select($ls_sql);
		if($resultSCG===false)
		{   // error interno sql
 			$ls_cadena="Error al Seleccionar los saldos de la origen.\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
            $lb_valido = false;	 
		}
		else
		{
			while($row=$this->io_sql_origen->fetch_row($resultSCG))
			{
				$this->ds_saldos_contables->insertRow("sc_cuenta",$row["sc_cuenta"]);
				$this->ds_saldos_contables->insertRow("descripcion",$row["denominacion"]);
				$this->ds_saldos_contables->insertRow("saldo_ant",$row["saldo_ant"]);
				$this->ds_saldos_contables->insertRow("debe",$row["debe"]);
				$this->ds_saldos_contables->insertRow("haber",$row["haber"]);
				$this->ds_saldos_contables->insertRow("saldo_act",$row["saldo_act"]);
			}
			$lb_valido=true;
	        $this->io_sql_origen->free_result($resultSCG);  
		}
		/*----- Comienzo con la base de datos destino para pasar la informacion --------*/
		if($lb_valido)
		{
			$this->io_sigesp_int->uf_int_init_transaction_begin();
			$ls_periodo="";
			$lb_valido=$this->uf_select_empresa($ls_periodo); // selecciono el periodo de la empresa 
			$ls_agno=substr($ls_periodo,0,4);
			$ls_periodo="01/01/".$ls_agno;
			$ld_fecdesde=$this->io_function->uf_convertirdatetobd($ls_periodo);
			$lb_autoconta=true;
			if ($as_tipo=="B")
			{
				$ls_fuente  = $as_ced_ben;
			}
			if ($as_tipo=="P")
			{
				$ls_fuente  = $as_cod_prov;
			}
			if ($as_tipo=="-")
			{
				$ls_fuente  = "----------";
			}
			$ls_codban="---";
			$ls_ctaban="-------------------------";
			if($lb_valido)
			{
				//  INICIO EL PROCESO DE INSERT SALDOS CONTABLES INICIALES
				$lb_valido = $this->io_sigesp_int->uf_int_init($this->ls_codemp,$as_procede,$as_comprobante,$ld_fecdesde,$as_descripcion,$as_tipo,
															   $ls_fuente,$lb_autoconta,$ls_codban,$ls_ctaban,$as_tipo_cmp);
			}
			if($lb_valido)
			{
				// RECORRO EL DATASTORE $this->ds_saldos_contables DE LA BASE DE DATOS ORIGINAL
				$li_total=$this->ds_saldos_contables->getRowCount("sc_cuenta");
				for($li_i=0;($li_i<$li_total) && ($lb_valido);$li_i++)
				{  
					$ls_sc_cuenta=$this->ds_saldos_contables->getValue("sc_cuenta",$li_i);	
					$ls_denominacion=$this->ds_saldos_contables->getValue("denominacion",$li_i);	
					$ld_saldo_ant=$this->ds_saldos_contables->getValue("saldo_ant",$li_i);
					$ld_debe=$this->ds_saldos_contables->getValue("debe",$li_i);	
					$ld_haber=$this->ds_saldos_contables->getValue("haber",$li_i);
					$ld_saldo_act=$this->ds_saldos_contables->getValue("saldo_act",$li_i);
					$ld_saldo_ant=$this->io_rcbsf->uf_convertir_monedabsf($ld_saldo_ant,2,1,1000,1);
					$ld_debe=$this->io_rcbsf->uf_convertir_monedabsf($ld_debe,2,1,1000,1);
					$ld_haber=$this->io_rcbsf->uf_convertir_monedabsf($ld_haber,2,1,1000,1);
					$ld_saldo_act=$this->io_rcbsf->uf_convertir_monedabsf($ld_saldo_act,2,1,1000,1);
					if($ld_saldo_act!=0)
					{
						if($ld_saldo_act>0)
						{
							$ls_operacion="D";
						}
						if($ld_saldo_act<0)
						{
							$ls_operacion="H";
						}
						$ld_monto=abs($ld_saldo_act);
						$lb_valido=$this->io_sigesp_int->uf_scg_insert_datastore($this->ls_codemp,$ls_sc_cuenta,$ls_operacion,$ld_monto,
																				 $as_comprobante,$as_procede,$as_descripcion);
					}
				}
			}
			if($lb_valido)
			{
				$lb_valido = $this->io_sigesp_int->uf_init_end_transaccion_integracion("");
			}
	  	}
		$this->io_sigesp_int->uf_sql_transaction($lb_valido);
		return $lb_valido;
	}//fin uf_procesar_apertura_ejercicio
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_empresa(&$ls_periodo)
	{	//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function:  uf_select_comprobante()
		//	   Access:  public
		//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
		//	  Returns:	booleano lb_existe
		//Description:  Método que verifica si existe o no el comprobante
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql=" SELECT periodo ".
			    " FROM  sigesp_empresa ".
				" WHERE codemp='".$this->ls_codemp."' ";
		$lr_result=$this->io_sql_destino->select($ls_sql);
		if($lr_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_apr_c_salcontables 
			                     MÉTODO->uf_select_comprobante 
								 ERROR->".$this->io_function->uf_convertirmsg($this->io_sql_destino->message);
			return false;
		}
		else  
		{ 
			if($row=$this->io_sql_destino->fetch_row($lr_result)) 
			{ 
				$ls_periodo=$row["periodo"];
				$lb_existe=true;
			}  
		}
		return $lb_existe;
	} // end function uf_select_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>