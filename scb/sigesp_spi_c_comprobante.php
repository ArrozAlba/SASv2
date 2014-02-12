<?php
class sigesp_spi_c_comprobante
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;
	var $io_int_scg;
	var $io_int_spi;
	var $io_msg;
	var $io_function;

function sigesp_spi_c_comprobante()
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");	
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");	
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");

	$this->io_function=new class_funciones();	
	$this->sig_int=new class_sigesp_int();
    $this->io_fecha=new class_fecha();
	$this->io_include=new sigesp_include();	
	$this->io_connect=$this->io_include->uf_conectar();
	$this->io_sql=new class_sql($this->io_connect);
	$this->io_msg = new class_mensajes();
	$this->io_int_spi=new class_sigesp_int_spi();	
	$this->io_int_scg=new class_sigesp_int_scg();	
	$this->is_msg_error="";
}

function uf_generar_num_cmp($as_codemp,$as_procede)
{
	 $ls_sql="SELECT comprobante FROM sigesp_cmp WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' ORDER BY comprobante DESC";		
	  $rs_funciondb=$this->io_sql->select($ls_sql);
	  if ($row=$this->io_sql->fetch_row($rs_funciondb))
	  { 
		  $codigo=$row["comprobante"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
	  }
	  else
	  {
		  $codigo="1";
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
	  }
	return $ls_codigo;
}

function uf_guardar_automatico($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,$as_prov,$as_bene,$as_tipo,$ai_tipo_comp)
{
	$lb_valido=false;
	$dat=$_SESSION["la_empresa"];
	if($this->uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,&$as_prov,&$as_bene,$as_tipo))
	{	
	   $lb_valido=$this->io_int_spi->uf_sigesp_comprobante($dat["codemp"],$as_proccomp,$as_comprobante,$ad_fecha,$ai_tipo_comp,$as_desccomp,$as_tipo,$as_prov,$as_bene,0);
	   if (!$lb_valido)
	   {
	      $this->io_msg->message("Error al procesar el comprobante Presupuestario  ".$this->io_int_spi->is_msg_error);
	   }  
	   else  {   $this->io_msg->message("El Movimiento fue registrado."); }
	   
	   $ib_valido = $lb_valido;
	   
	   if($lb_valido)
	   {
		  $ib_new = $this->io_int_spi->ib_new_comprobante;
	   }	
	   else  {  $lb_valido=true;  } 	
	}
	else { $this->io_msg->message("Error en valida datos comprobante"); }
	return $lb_valido;
}
function uf_cargar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{

	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
 	$ls_sql="SELECT DISTINCT DT.spi_cuenta as spi_cuenta,C.denominacion as denominacion,
          	 DT.procede_doc as procede_doc,P.desproc as desproc,DT.documento as documento,DT.operacion as operacion,DT.descripcion as descripcion,DT.monto as monto,DT.orden as orden, OP.denominacion as denominacion
  		     FROM spi_dt_cmp DT,spi_cuentas C, sigesp_procedencias P,spi_operaciones OP
		     WHERE DT.procede=P.procede AND DT.codemp=C.codemp AND DT.spi_cuenta=C.spi_cuenta AND OP.operacion = DT.operacion  
			 AND DT.codemp='".$as_codemp."' AND DT.procede='".$as_procede."' AND DT.comprobante='".$as_comprobante."' AND DT.fecha='".$ld_fecha."' 
			 ORDER BY DT.orden "; 
	$rs_dt_cmp=$this->io_sql->select($ls_sql);
	
	if($rs_dt_cmp===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_dt_cmp;
}

function uf_cargar_dt_contable_cmp($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{

	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	$rs_dt_scg=$this->io_int_scg->uf_scg_cargar_detalle_comprobante( $as_codemp, $as_procede,$as_comprobante, $ld_fecha,&$lds_detalle_cmp);
	if($rs_dt_scg===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_int_scg->io_sql->message));
	}
	return $rs_dt_scg;
}

function uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_procedencia,$as_desccomp,$as_cod_prov,$as_ced_bene,$as_tipo)
{
	$ls_desproc ="";
	if(!$this->io_int_spi->uf_valida_procedencia($as_procedencia,&$ls_desproc ) )
	{
	   $this->io_msg->message("Procedencia invalida.",$ls_desproc);
	   return false	;
	} 

	if(trim($as_comprobante)=="")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}

	if(trim($as_comprobante)=="000000000000000")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}
	
	
	if((trim($as_cod_prov)=="----------")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	
	if((trim($as_cod_prov)!="----------" )&&($as_tipo=="B"))
	{
		$as_cod_prov = "----------";
	}
		
	if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario1.");
		return false;
	}
	if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario.2");
		return false;	
	}
	
	if((trim($as_ced_bene)!="----------" )&&($as_tipo=="P"))
	{
		$as_ced_bene="----------";
	}
	if($as_tipo=="-")
	{
		$as_ced_bene="----------";
		$as_cod_prov="----------";
	}

  return true;
}

function uf_guardar_movimientos($arr_cmp,$ls_cuenta,$ls_procede_doc,$ls_descripcion,$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,$ls_tipocomp)
{
	$lb_valido=false;

	$ls_mensaje = $this->io_int_spi->uf_operacion_codigo_mensaje($ls_operacionpre);

	if($ls_mensaje!="")
	{
		if(!$this->uf_spi_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
		{ 
		   $this->io_msg->message("Error 1".$this->is_msg_error);
		   return false;
		}
		$this->io_int_spi->is_codemp=$arr_cmp["codemp"];
		$this->io_int_spi->is_comprobante=$arr_cmp["comprobante"];
		$this->io_int_spi->id_fecha=$arr_cmp["fecha"];
		$this->io_int_spi->is_procedencia=$arr_cmp["procedencia"];
		$this->io_int_spi->is_cod_prov=$arr_cmp["proveedor"];
		$this->io_int_spi->is_ced_bene=$arr_cmp["beneficiario"];
		$this->io_int_spi->is_tipo=$arr_cmp["tipo"];
		$lb_valido=$this->io_int_spi->uf_spi_comprobante_actualizar($ldec_monto_ant, $ldec_monto_act, $ls_tipocomp);
		if($lb_valido)
		{
	        $ls_sc_cuenta="";	
			if ($arr_cmp["tipo"]=="B")  
				{ $ls_fuente = $arr_cmp["beneficiario"]; }	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			$ls_status="";$ls_denominacion="";$ls_sc_cuenta="";
			if(!$this->io_int_spi->uf_spi_select_cuenta($arr_cmp["codemp"],$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{  
			  return false;
			}
			 $this->io_int_spi->ib_AutoConta=true;
            $lb_valido = $this->io_int_spi->uf_int_spi_insert_movimiento($arr_cmp["codemp"],$arr_cmp["procedencia"],$arr_cmp["comprobante"],$arr_cmp["fecha"],
										                                 $arr_cmp["tipo"],$ls_fuente,$arr_cmp["proveedor"],$arr_cmp["beneficiario"],
																		 $ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ls_mensaje,$ldec_monto_act,$ls_sc_cuenta,true);
			if(!$lb_valido)
			{
				$this->io_msg->message("No se registraron los detalles presupuestario".$this->io_int_spi->is_msg_error);
			}
		}
		else
		{
		  $lb_valido=false;
		}
   }
   $ldec_monto = 0;
 return $lb_valido;
}


function uf_spi_valida_datos_movimiento($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
{

	if (trim($as_cuenta)=="")
	{
		$this->is_msg_error = "Registre la Cuenta de Ingreso." ;
		return false;	
	}
	if(trim($as_descripcion)=="")
	{
		$this->is_msg_error = "Registre la Descripción del Movimiento." ;
		return false;
	}
	
	if(trim($as_documento) =="") 
	{
		$this->is_msg_error = "Registre el Nº de documento." 	;
		return false;	
	}

 return true ;
}

function uf_guardar_movimientos_contable($arr_cmp,$as_cuenta,$as_procede_doc,$as_descripcion,$as_documento,$as_operacioncon,$adec_monto)
{
	$lb_valido=false;

	if(!$this->uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto))
	{ 
		$this->io_msg->message($this->is_msg_error);
	   return false;
	}
	$lb_valido = $this->io_int_scg->uf_scg_procesar_movimiento_cmp($arr_cmp["codemp"],$arr_cmp["procedencia"],$arr_cmp["comprobante"],$arr_cmp["fecha"],
                                                          $arr_cmp["proveedor"],$arr_cmp["beneficiario"],$arr_cmp["tipo"],$arr_cmp["tipo_comp"],
                                                          $as_cuenta,$as_procede_doc,$as_documento,$as_operacioncon,$as_descripcion,$adec_monto);
	if(!$lb_valido)
	{
		$this->io_msg->message("Error al registrar movimiento contable".$this->io_int_scg->is_msg_error);
	}
	$ldec_monto = 0;
    return $lb_valido;
 }

	function uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
	{
		if (trim($as_cuenta)=="")
		{
			$this->is_msg_error = "Registre la Cuenta Gasto." ;
			return false;	
		}
		
		if(trim($as_descripcion)=="")
		{
			$this->is_msg_error = "Registre la Descripción del Movimiento." ;
			return false;
		}
		
		if(trim($as_documento) =="") 
		{
			$this->is_msg_error = "Registre el Nº de documento." 	;
			return false;	
		}
		
		if($adec_monto == 0)
		{
			$this->is_msg_error = "Registre el Monto." ;	
			return false;
		} 
	
	   return true ;
	}
}
?>