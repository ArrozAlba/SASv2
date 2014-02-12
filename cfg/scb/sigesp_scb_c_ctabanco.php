<?php
class sigesp_scb_c_ctabanco
{
	var $io_sql;
	var $is_msg_error;
	var $fun;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	var $dat;
	var $fec;
	function sigesp_scb_c_ctabanco($aa_security)
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_fecha.php");
		$this->fec          = new class_fecha();
		$sig_inc            = new sigesp_include();
		$con                = $sig_inc->uf_conectar();
		$this->io_sql       = new class_sql($con);
		$this->fun          = new class_funciones();
		$this->is_empresa   = $aa_security[1];
		$this->is_sistema   = $aa_security[2];
		$this->is_logusr    = $aa_security[3];	
		$this->is_ventana   = $aa_security[4];
		$this->io_seguridad = new sigesp_c_seguridad();
		$this->dat          = $_SESSION["la_empresa"];	
	}

function uf_select_ctabanco($as_codban,$as_ctaban)
{
	
	$ls_codemp = $this->dat["codemp"];
	$ls_cadena = "SELECT * FROM scb_ctabanco WHERE codemp='".$ls_codemp."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."' ";
	$rs_data   = $this->io_sql->select($ls_cadena);
	if ($rs_data===false)
	   {
		 $this->is_msg_error="Error en select".$this->fun->uf_convertirmsg($this->io_sql->message);
		 $lb_valido=false;
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $lb_valido=true;
		      $this->io_sql->free_result($rs_data);
			}
	 	 else
		    {
		 	  $lb_valido=false;
			  $this->is_msg_error="No encontro registro";
	  	    }
	   }
	return $lb_valido;
}

function uf_validacion_fechas($ad_fec_aper,$ad_fec_cierre,$as_codemp)
{
	if($ad_fec_cierre!="")
	{
		if(date($ad_fec_aper)<date($ad_fec_cierre))
		{
			if((!$this->fec->uf_valida_fecha_periodo($ad_fec_aper,$as_codemp))&&(!$this->fec->uf_valida_fecha_periodo($ad_fec_cierre,$as_codemp)))
			{
				$this->is_msg_error="Fechas no estan en el periodo, o algun mes no esta abierto";
				return false;
			}
			else
			{
				return true;
			}			
		}
		else
		{
			$this->is_msg_error="Fecha de apertura debe ser menor que la de cierre ";
			return false;				
		}
		
	}	
	else
	{
		if(!$this->fec->uf_valida_fecha_periodo($ad_fec_aper,$as_codemp))
		{
			$this->is_msg_error="Fecha no estan en el periodo, o mes no esta abierto";
			return false;
		}
		else
		{
			return true;
		}
	}
		
	
}
	
function uf_guardar_ctabanco($as_codigo,$as_denominacion,$as_tipcta,$as_codban,$as_cuenta_scg,$ad_fec_aper,$ad_fec_cierre,$ai_status,$ls_status,$ls_ctaext)
{
	$lb_existe=$this->uf_select_ctabanco($as_codban,$as_codigo);
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
		$ld_fec_aper=$this->fun->uf_convertirdatetobd($ad_fec_aper);
		$ld_fec_cierre=$this->fun->uf_convertirdatetobd($ad_fec_cierre);
		if(!$lb_existe)
		{

			$ls_cadena= " INSERT INTO scb_ctabanco(codemp,codban,ctaban,ctabanext,codtipcta,dencta,sc_cuenta,fecapr,feccie,estact) VALUES('".$ls_codemp."','".$as_codban."','".$as_codigo."', '".$ls_ctaext."' ,'".$as_tipcta."','".$as_denominacion."','".$as_cuenta_scg."','".$ld_fec_aper."','".$ld_fec_cierre."',".$ai_status.") ";
			$this->is_msg_error="Registro Incluido";		
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la cuenta ".$as_codigo." con denominacion ".$as_denominacion." para el banco ".$as_codban;
		}
		else
		{
			if($ls_status=='C')
			{
			$ls_cadena= " UPDATE scb_ctabanco SET codtipcta='".$as_tipcta."',dencta='".$as_denominacion."',sc_cuenta='".$as_cuenta_scg."',fecapr='".$ld_fec_aper."',feccie='".$ld_fec_cierre."',estact=".$ai_status.",ctabanext='".$ls_ctaext."' WHERE codemp='".$ls_codemp."' ".
						" AND codban='".$as_codban."' AND ctaban='".$as_codigo."'";
			$this->is_msg_error="Registro Actualizado";
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo la cuenta ".$as_codigo." con denominacion ".$as_denominacion." para el banco ".$as_codban;
			}
			else
			{
				$this->is_msg_error="Registro ya existe introduzca un nuevo codigo";
				return false;
			}
			
		}

		$this->io_sql->begin_transaction();

		$li_numrows=$this->io_sql->execute($ls_cadena);

		if($li_numrows===false)
		{

			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_guardar_ctabanco".$this->fun->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_sql->message;

		}
		else
		{				
			$lb_valido=true;
			$this->io_sql->commit();
			$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		}
	return $lb_valido;	
}

function uf_delete_ctabanco($as_codigo,$as_denominacion,$as_codban)
{
	$lb_valido = false;
	$ls_codemp = $this->dat["codemp"];
	$ls_sql    = "DELETE FROM scb_ctabanco WHERE codemp='".$ls_codemp."' AND ctaban='".$as_codigo."' AND codban='".$as_codban."'";
	$this->io_sql->begin_transaction();
	$rs_data = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_delete_ctabanco ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
	     $lb_valido      = true;
		 $ls_evento      = "DELETE";
		 $ls_descripcion = "Elimino la cuenta codigo ".$as_codigo." con denominacion ".$as_denominacion." del banco ".$as_codban  ;
		 $lb_valido      = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	   }
    return $lb_valido;
}
}
?>
