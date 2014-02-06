<?php
class sigesp_scb_c_colocacion
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
	function sigesp_scb_c_colocacion($aa_security)
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../class_folder/class_funciones_cfg.php");
		$this->fec=new class_fecha();
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->fun=new class_funciones();
		$this->is_empresa = $aa_security[1];
		$this->is_sistema = $aa_security[2];
		$this->is_logusr  = $aa_security[3];	
		$this->is_ventana = $aa_security[4];
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->dat=$_SESSION["la_empresa"];	
		$this->io_fun_cfg= new class_funciones_cfg();
	}

function uf_select_colocacion($as_codban,$as_ctaban,$as_colocacion,$as_cuentascg,$as_cuentaspi)
{
    $lb_valido=false;
	$as_num=0;
	$ls_codemp=$this->dat["codemp"];
	
	$ls_sql="SELECT * FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND codban='".$as_codban."' AND ctaban='".$as_ctaban."' AND numcol='".$as_colocacion."'";
	
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error en select".$this->fun->uf_convertirmsg($this->io_sql->message);
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$as_num=$as_num+1;
			$this->is_msg_error="Numero de Colocacion ya existe";
		}
		else
		{
			$lb_valido=false;
			$as_num=0;
			$this->is_msg_error="No encontro registro";
		}
		$this->io_sql->free_result($rs_data); 
	}
	//return $lb_valido;
	return $as_num;
}
	
function uf_guardar_colocacion($as_colocacion,$as_dencol,$as_codban,$as_cuenta_banco,$as_tasa,$ad_desde,$ad_hasta,$adec_monto,
                               $adec_interes,$as_codtipcol,$as_cuentascg,$as_cuentaspi,$ai_estrei,$as_plazo,$as_codigo,
							   $as_codpro,$as_codproben)
{
	$li_desde=0;$li_hasta=0;
	//$lb_existe=$this->uf_select_cheques($as_codban,$as_ctaban,$as_cheque,$as_chequera,&$li_status);
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_desde);
	$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_hasta);
	$as_tasa=str_replace('.','',$as_tasa);
	$as_tasa=str_replace(',','.',$as_tasa);	
	$adec_monto=str_replace('.','',$adec_monto);
	$adec_monto=str_replace(',','.',$adec_monto);	
	$adec_interes=str_replace('.','',$adec_interes);
	$adec_interes=str_replace(',','.',$adec_interes);	
	$ls_sql= " INSERT INTO scb_colocacion(codemp, codban, ctaban, numcol, dencol, codtipcol, feccol, diacol,    ".
		         "                            tascol, monto,fecvencol, monint, sc_cuenta, spi_cuenta, estreicol,    ".
				 "                            codconmov,cod_pro,ced_bene)    ".
				 " VALUES                     ('".$ls_codemp."','".$as_codban."','".$as_cuenta_banco."',            ".
				 "                             '".$as_colocacion."','".$as_dencol."','".$as_codtipcol."',           ".
				 "                             '".$ld_fecdesde."','".$as_plazo."','".$as_tasa."','".$adec_monto."', ".
				 "                             '".$ld_fechasta."','".$adec_interes."','".$as_cuentascg."',          ".
				 "                             '".$as_cuentaspi."',".$ai_estrei.", ".
				 "                             '".$as_codigo."','".$as_codpro."','".$as_codproben."') ";
	
	$this->io_sql->begin_transaction();

	$li_numrows=$this->io_sql->execute($ls_sql);

	if($li_numrows===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_colocacion".$this->fun->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
		$lb_valido=true;
		if($lb_valido)
		{
			$this->is_msg_error="Registro Incluido";		
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
	    }
		$this->io_sql->commit();
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	}

return $lb_valido;
}

function uf_update_colocacion($as_colocacion,$as_dencol,$as_codban,$as_cuenta_banco,$as_tasa,$ad_desde,$ad_hasta,$adec_monto,
                               $adec_interes,$as_codtipcol,$as_cuentascg,$as_cuentaspi,$ai_estreicol,$as_plazo,$as_codigo,
							   $as_codpro,$as_codproben)
{
	$li_desde=0;$li_hasta=0;
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$ld_fecdesde=$this->fun->uf_convertirdatetobd($ad_desde);
	$ld_fechasta=$this->fun->uf_convertirdatetobd($ad_hasta);
	$as_tasa=str_replace('.','',$as_tasa);
	$as_tasa=str_replace(',','.',$as_tasa);	
	$adec_monto=str_replace('.','',$adec_monto);
	$adec_monto=str_replace(',','.',$adec_monto);	
	$adec_interes=str_replace('.','',$adec_interes);
	$adec_interes=str_replace(',','.',$adec_interes);	

	$ls_sql= " UPDATE scb_colocacion ".
		         " SET    dencol='".$as_dencol."',feccol='".$ld_fecdesde."',diacol='".$as_plazo."', ".
				 "        tascol=".$as_tasa.",monto=".$adec_monto.",monint=".$adec_interes.",       ".
				 "        estreicol=".$ai_estreicol.",fecvencol='".$ld_fechasta."',".
				 "        codconmov='".$as_codigo."',cod_pro='".$as_codpro."',ced_bene='".$as_codproben."'".
				 " WHERE  codemp='".$ls_codemp."' AND codban='".$as_codban."' AND                   ".
				 "        ctaban='".$as_cuenta_banco."' AND numcol='".$as_colocacion."'";

	$this->io_sql->begin_transaction();

	$li_numrows=$this->io_sql->execute($ls_sql);

	if($li_numrows===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_colocacion".$this->fun->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
		$lb_valido=true;
		if($lb_valido)
		{
		 //  $this->is_msg_error="Registro Actualizado";
		   $ls_evento="UPDATE";
		   $ls_descripcion="Actualizo la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
	    }
		$this->io_sql->commit();
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	}

return $lb_valido;
}	
function uf_delete_colocacion($as_colocacion,$as_codban,$as_cuenta_banco)
{
	$lb_valido = false;
	$ls_codemp = $this->dat["codemp"];
	$ls_sql    = "DELETE FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND ctaban='".$as_cuenta_banco."' AND codban='".$as_codban."' AND numcol='".$as_colocacion."' " ;
	$this->io_sql->begin_transaction();
 	$rs_data   = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_delete_colocacion  ".$this->fun->uf_convertirmsg($this->io_sql->message);
		 print $this->io_sql->message;
	   }
	else
	   {
	     $lb_valido=true;
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco ;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
 	   }
	return $lb_valido;
}	
//----------------------------------------------------------------------------------------------------------------------------------
 function uf_scb_select_cuentas_ingresos($as_codemp,&$ai_cuantos)
 {   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scb_select_cuentas_ingresos
	 //         Access :	private
	 //     Argumentos :    $as_codemp  // codigo dela empresa
	 //                     $ai_cuantos  // cuantos registros existen
     //	       Returns :	Retorna true o false si se realizo la consulta 
	 //	   Description :	Retorna cuantos registros en spi existen (referencia)
	 //     Creado por :    Ing. Yozelin Barragán.
	 // Fecha Creación :    26/02/2007          Fecha última Modificacion :      Hora :
  	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     $lb_valido=false;
	 $ls_sql=" SELECT count(spi_cuenta) as cuantos ".
             " FROM   spi_cuentas ".
             " WHERE  codemp='".$as_codemp."'";
	 $rs_data= $this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {
	     $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_scb_select_cuentas_ingresos  ".$this->fun->uf_convertirmsg($this->io_sql->message);
	 }
	 else
	 {
        if($row=$this->io_sql->fetch_row($rs_data))
		{
		  $ai_cuantos=$row["cuantos"];
	      $lb_valido=true;
		}
	 }
	 return  $lb_valido;
 }// uf_scb_select_cuentas_ingresos
//----------------------------------------------------------------------------------------------------------------------------------
 function uf_select_banco($as_codemp)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_banco
 //	Access       public
 //	Arguments    $as_codemp
 //	Returns	     rs_data. Retorna una resulset
 //	Description  Devuelve un resulset con todos los bancos registrados para dicho 
 //              codigo de empresa.
 //////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM scb_banco WHERE codemp='".$as_codemp."'ORDER BY nomban ASC ";
   $rs_data=$this->io_sql->select($ls_sql);
   $li_numrows=$this->io_sql->num_rows($rs_data);	   
   if ($li_numrows>0)
	  {
		$lb_valido=true;
	  }
   else
	 {
	   $lb_valido=false;
	   if ($this->io_sql->message!="")
		  {                              
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		  }           
	 }	
   if ($lb_valido)
	  {
		return $rs_data;         
	  }
}


/*function sumardias($fecha,$ndias) 
	{
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: suma_fechas
			//	    Arguments: $fecha  // fecha inicial
			//				   $ndias  // número de días a sumar a la fecha inicial
			//	      Returns: Retorna la variable $nuevafecha con el nuevo valor de la fecha al sumar el número de días pasado como 
			//                 parámetro
			//	  Description: Funcion que suma un valor de días enteros a una fecha (en formato dd/mm/aaaa)
			//	   Creado Por: Maria Beatriz Unda	
			// Fecha Creación: 25/08/2008							
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		  if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
				
	
				  list($dia,$mes,$año)=split("/", $fecha);
				
	
		  if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
				
	
				  list($dia,$mes,$año)=split("-",$fecha);
			$nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
			$nuevafecha=date("d/m/Y",$nueva);
	
		  return ($nuevafecha);  
				
	
	}*/
	function sumarDias ($fecha, $ndias)
	{
		if ($ndias > 0)
		{
			$dia=substr($fecha,0,2);     
			$mes=substr($fecha,3,2);      
			$anio=substr($fecha,6,4); 
			$ultimo_dia=date("d",mktime(0, 0, 0,$mes+1,0,$anio)); 
			$dias_adelanto=$ndias;
			$siguiente=$dia+$dias_adelanto; 
			if ($ultimo_dia < $siguiente)
			{         
				$dia_final=$siguiente-$ultimo_dia;
				$mes++;         
				if($ndias=='365')
				{
					$dia_final=$dia;
				}    
				if($mes=='13')
				{            
					$anio++;
					$mes='01';        
				}    
				$fecha_final=$anio.'/'.str_pad($mes,2,"0",0).'/'.str_pad($dia_final,2,"0",0); 
			}
			else   
			{
			     $fecha_final=$anio.'/'.$mes.'/'.$siguiente;
			} 
			/*$dia=substr($fecha_final,8,2);
			$mes=substr($fecha_final,5,2);
			$anio=substr($fecha_final,0,4); 
			$dia=substr($fecha,0,2);     
			$mes=substr($fecha,3,2);      
			$anio=substr($fecha,6,4); 
			while(checkdate($mes,$dia,$anio)==false)
			{ 
			   $dia=$dia-1; 
			   break;
			} 
			$fecha_final=$anio.'/'.$mes.'/'.$dia;*/
			$fecha_final=$this->io_fun_cfg->uf_convertirfecmostrar($fecha_final);
		}
		else
		{
			$fecha_final=$this->io_fun_cfg->uf_convertirfecmostrar($fecha_final);
		}
		return $fecha_final;
}

function uf_guardar_dt_colocacion($as_colocacion,$as_codban,$as_cuenta_banco,$as_fechareint,$as_montoreint)
{
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$as_fechareint=$this->fun->uf_convertirdatetobd($as_fechareint);
	$as_montoreint=str_replace('.','',$as_montoreint);
	$as_montoreint=str_replace(',','.',$as_montoreint);	

		$ls_sql= " INSERT INTO scb_dt_colocacion(codemp, codban, ctaban, numcol, fecreint, montoreint) ".
				 " VALUES                     ('".$ls_codemp."','".$as_codban."','".$as_cuenta_banco."', ".
				 "                             '".$as_colocacion."','".$as_fechareint."','".$as_montoreint."') ";
	
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);

	if($li_numrows===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_dt_colocacion".$this->fun->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
		$lb_valido=true;
    	if($lb_valido)
		{
			$this->is_msg_error="Registro Incluido";		
			$ls_evento="INSERT";
			$ls_descripcion="Inserto el detalle de la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
	    }
		$this->io_sql->commit();
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);

	}

return $lb_valido;
	
}

function uf_update_dt_colocacion($as_colocacion,$as_codban,$as_cuenta_banco,$as_fechareint,$as_montoreint)
{
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=false;
	$as_fechareint=$this->fun->uf_convertirdatetobd($as_fechareint);
	$as_montoreint=str_replace('.','',$as_montoreint);
	$as_montoreint=str_replace(',','.',$as_montoreint);	

		$ls_sql= " UPDATE scb_dt_colocacion ".
		         " SET    montoreint='".$as_montoreint."'".
				 " WHERE  codemp='".$ls_codemp."' AND codban='".$as_codban."' AND  ".
				 "        ctaban='".$as_cuenta_banco."' AND numcol='".$as_colocacion."' AND fecreint='".$as_fechareint."'"; 
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_sql);

	if($li_numrows===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_dt_colocacion".$this->fun->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
	}
	else
	{
		$lb_valido=true;
	    if($lb_valido)
		{
		   $this->is_msg_error="Registro Actualizado";
		   $ls_evento="UPDATE";
		   $ls_descripcion="Actualizo el detalle de la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco;
	    }
		$this->io_sql->commit();
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	}

return $lb_valido;
	
}

function uf_delete_dt_colocacion($as_colocacion,$as_codban,$as_cuenta_banco,$as_fechareint,$as_montoreint)
{
	$lb_valido = false;
	$ls_codemp = $this->dat["codemp"];
	$ls_sql    = "DELETE FROM scb_dt_colocacion WHERE codemp='".$ls_codemp."' AND ctaban='".$as_cuenta_banco."'
	                 AND codban='".$as_codban."' AND numcol='".$as_colocacion."'" ;
					 
	$this->io_sql->begin_transaction();
 	$rs_data   = $this->io_sql->execute($ls_sql);
	if ($rs_data===false)
	   {
	     $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_delete_dt_colocacion  ".$this->fun->uf_convertirmsg($this->io_sql->message);
		 print $this->io_sql->message;
	   }
	else
	   {
	     $lb_valido=true;
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino el detalle de la colocacion ".$as_colocacion." perteneciente al banco ".$as_codban." y la cuenta ".$as_cuenta_banco ;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
 	   }
	return $lb_valido;
}	

}// fin de la clase
?>
