<?php
class sigesp_scb_c_colocacion
{
	var $SQL;
	var $is_msg_error;
	var $fun;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	var $dat;
	var $fec;
	function sigesp_scb_c_colocacion($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_fecha.php");
		$this->fec=new class_fecha();
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->fun=new class_funciones();
		$this->is_empresa = $aa_security[1];
		$this->is_sistema = $aa_security[2];
		$this->is_logusr  = $aa_security[3];	
		$this->is_ventana = $aa_security[4];
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->dat=$_SESSION["la_empresa"];	
	}

	function uf_select_colocacion($as_codban,$as_ctaban,$as_colocacion,$as_cuentascg,$as_cuentaspi)
	{
		
		$ls_codemp=$this->dat["codemp"];
		$ls_cadena="SELECT * FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND numcol='".$as_colocacion."'";
		$rs_ctabanco=$this->SQL->select($ls_cadena);
		if($rs_ctabanco==false)
		{
			$this->is_msg_error="Error en select".$this->fun->uf_convertirmsg($this->SQL->message);
			$lb_valido=false;
			$as_status=0;
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_ctabanco))
			{
				$lb_valido=true;
				$this->is_msg_error="Numero de Colocacion ya existe";
			}
			else
			{
				$lb_valido=false;
				$as_status=0;
				$this->is_msg_error="No encontro registro";
			}
		}
		return $lb_valido;
	}
	
	
	function uf_guardar_colocacion($as_colocacion ,$as_dencol,$as_codban,$as_cuenta_banco,$as_tasa,$ad_desde,$ad_hasta,$adec_monto,$adec_interes,$as_codtipcol,$as_cuentascg,$as_cuentaspi,$ai_estrei,$as_plazo )
	{
		$li_desde=0;$li_hasta=0;
		//$lb_existe=$this->uf_select_cheques($as_codban,$as_ctaban,$as_cheque,$as_chequera,&$li_status);
		$ls_codemp=$this->dat["codemp"];
		$lb_valido=false;
		$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_desde);
		$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_hasta);
		$lb_existe=$this->uf_select_colocacion($as_codban,$as_cuenta_banco,$as_colocacion,$as_cuentascg,$as_cuentaspi);
		if(!$lb_existe)
		{
			$ls_cadena= " INSERT INTO scb_colocacion(codemp, codban, ctaban, numcol, dencol, codtipcol, feccol, diacol, tascol, monto, fecvencol, monint, sc_cuenta, spi_cuenta, estreicol) 
						  VALUES('".$ls_codemp."','".$as_codban."','".$as_cuenta_banco."','".$as_colocacion."','".$as_dencol."','".$as_codtipcol."','".$ld_fecdesde."','".$as_plazo."','".$as_tasa."','".$adec_monto."','".$ld_fechasta."','".$adec_interes."','".$as_cuentascg."','".$as_cuentaspi."',".$ai_estrei.") ";
			$this->is_msg_error="Registro Incluido";		
			////////////////////////////Seguridad///////////////////////////////////////////////////////////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
		{
			$ls_cadena= " UPDATE scb_colocacion SET dencol='".$as_dencol."',feccol='".$ld_fecdesde."',diacol='".$as_plazo."',tascol=".$as_tasa.",monto=".$adec_monto.",monint=".$adec_interes.",estreicol=".$ai_estrei.",fecvencol='".$ld_fechasta."'  WHERE codemp='".$ls_codemp."' AND codban='".$as_codban."' AND ctaban='".$as_cuenta_banco."' AND numcol='".$as_colocacion."'";
			$this->is_msg_error="Registro Actualizado";
			////////////////////////////Seguridad////////////////////////////////////////////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		
		$this->SQL->begin_transaction();
	
		$li_numrows=$this->SQL->execute($ls_cadena);
		print $ls_cadena;
		if(($li_numrows==false)&&($this->SQL->message!=""))
		{

			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_guardar_Colocacion".$this->fun->uf_convertirmsg($this->SQL->message);
			$this->SQL->rollback();
			print $this->SQL->message;

		}
		else
		{
			$lb_valido=true;
			$this->SQL->commit();
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		}
	
	return $lb_valido;
}
	
	function uf_delete_colocacion($as_colocacion ,$as_dencol,$as_codban,$as_cuenta_banco,$as_tasa,$ad_desde,$ad_hasta,$adec_monto,$adec_interes,$as_codtipcol,$as_cuentascg,$as_cuentaspi,$ai_estrei,$as_plazo)
	{
		$lb_valido=false;
		$lb_existe=$this->uf_select_colocacion($as_codban,$as_cuenta_banco,$as_colocacion,$as_cuentascg,$as_cuentaspi);
		$ls_codemp=$this->dat["codemp"];
		
		if(($lb_existe))
		{
			$ls_cadena= " DELETE FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND ctaban='".$as_cuenta_banco."' AND codban='".$as_codban."' AND numcol='".$as_colocacion."' " ;
			$this->is_msg_error="Registro Eliminado";		
			$this->SQL->begin_transaction();
			$li_numrows=$this->SQL->execute($ls_cadena);
			if(($li_numrows==false)&&($this->SQL->message!=""))
			{
				$lb_valido=false;
				$this->SQL->rollback();
				$this->is_msg_error="Error en metodo uf_delete_colocacion  ".$this->fun->uf_convertirmsg($this->SQL->message);
				print $this->is_msg_error;
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					$this->SQL->commit();
					///////////////////////////Seguridad/////////////////////////////////////////////////////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion="Elimino la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco ;
					$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				else
				{
					$lb_valido=false;
					$this->is_msg_error="No se elimino registro";
					$this->SQL->rollback();
				}

			}
		}
		return $lb_valido;
	}	

}
?>
