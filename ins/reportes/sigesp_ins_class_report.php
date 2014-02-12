<?php

class sigesp_ins_class_report
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $siginc;
	var $con;

	function sigesp_ins_class_report()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////      Funciones del reporte de solicitudes de pago sin detalle asociado       ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_solicitudpago()	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago
		//	           Access: public
		//  		Arguments: 
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que se obtiene las solicitudes de pago que no tienen detalle asociado
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 27/06/2007							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol,cxp_solicitudes.fecemisol,cxp_solicitudes.monsol, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_solicitudes.numsol NOT IN (SELECT cxp_dt_solicitudes.numsol".
				"									     FROM cxp_dt_solicitudes".
				"										WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."')";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_solicitudpago ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago

	function uf_select_comprobantes($as_procede)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago
		//	           Access: public
		//  		Arguments: as_procede  // Procedencia del documento
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que obtiene los comprobantes dado el procede indicado
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 25/04/2008							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codemp,procede,comprobante,fecha,codban,ctaban".
				"  FROM scg_dt_cmp".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND procede like '%".$as_procede."%'".
				" GROUP BY codemp,procede,comprobante,fecha,codban,ctaban".
				" ORDER BY comprobante,procede";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_comprobantes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago

	function uf_select_detalles($as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago
		//	           Access: public
		//  		Arguments: as_procede  // Procedencia del documento
		//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//	      Description: Función que obtiene los comprobantes dado el procede indicado
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 25/04/2008							Fecha de Ultima Modificación:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ds_detalle=new class_datastore();
		$lb_valido=false;
		$ls_sql="SELECT codemp,procede,comprobante,fecha,codban,ctaban,debhab,SUM(monto) AS monto".
				"  FROM scg_dt_cmp".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codban = '".$as_codban."'".
				"   AND ctaban = '".$as_ctaban."'".
				" GROUP BY codemp,procede,comprobante,fecha,codban,ctaban,debhab".
				" ORDER BY comprobante,procede,debhab";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_select_comprobantes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds_detalle->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago

} //fin  class sigesp_ins_class_report
?>
