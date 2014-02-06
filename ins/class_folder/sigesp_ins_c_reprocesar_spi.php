<?php
class sigesp_ins_c_reprocesar_spi
{
	var $io_sql;
	var $io_message;
	var $io_function;
	var $is_msg_error;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_ins_c_reprocesar_spi()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");
		require_once("../shared/class_folder/class_funciones.php");	
		require_once("../shared/class_folder/class_sigesp_int.php");
		require_once("../shared/class_folder/class_sigesp_int_scg.php");	
		require_once("../shared/class_folder/class_sigesp_int_spg.php");			
		require_once("../shared/class_folder/class_sigesp_int_spi.php");			
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$io_siginc=new sigesp_include();
		$con=$io_siginc->uf_conectar();
		$this->io_sql=new class_sql($con);
		$this->io_message=new class_mensajes();
		$this->io_function=new class_funciones();
		$this->io_int_spi=new class_sigesp_int_spi();
		$this->io_seguridad=new sigesp_c_seguridad();		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_reprocesar_saldos($ls_codemp,$aa_seguridad)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	 Function:  uf_reprocesar_saldos
		// 	   Access:  public
		//  Arguments:  
		//	  Returns:  Boolean
		//Description:  Este método realiza la actualización de los saldos de las cuentas presupustarias 
		//				segun los movimientos realizado en base a las mismas.
		////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		//Actualizo los saldos de las cuentas a 0 
		$this->io_sql->begin_transaction();
		$ls_sql="UPDATE spi_cuentas ".
				"   SET previsto=0, ".
				"       devengado=0, ".
				"       cobrado=0, ".
				"       cobrado_anticipado=0, ".
				"       aumento=0, ".
				"       disminucion=0 ".
				" WHERE codemp ='".$ls_codemp."'";
		$li_numrow=$this->io_sql->execute($ls_sql);
		if($li_numrow===false)
		{
            $this->io_message->message("CLASE->Reprocesar SPI MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
			$lb_valido=false;
		}		
		//Arreglo con los codigos de las operaciones. Los duplicados es por la filas que se actualizan
		$la_mensajes=array(1=>'I',2=>'A',3=>'D',4=>'E',5=>'C',6=>'EC',7=>'EC');
		//Arreglo de las filas que se van a actualizar
		$la_fila=array(1=>'previsto',2=>'aumento',3=>'disminucion',4=>'devengado',5=>'cobrado',
					   6=>'devengado',7=>'cobrado');
		$li_total_codigos=count($la_mensajes);	
		for($li_i=1;($li_i<=$li_total_codigos)&&($lb_valido);$li_i++)		
		{
			$ls_codigo=$this->io_int_spi->uf_opera_mensaje_codigo($la_mensajes[$li_i],$lb_valido);
			$ls_mensaje=$this->io_int_spi->uf_operacion_codigo_mensaje($ls_codigo);
			$ls_sql="SELECT spi_cuenta, sum(monto) AS monto, fecha ". 
					"  FROM spi_dt_cmp  ".
					" WHERE codemp = '".$ls_codemp."' ".
					"	AND	operacion = '".$ls_codigo."' ".
					" GROUP BY spi_cuenta,fecha ".
					" ORDER BY spi_cuenta,fecha ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_message->message("CLASE->Reprocesar SPI MÉTODO->uf_reprocesar_saldos ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
				$lb_valido=false;
			}
			else
			{
				while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
				{
					$_SESSION["fechacomprobante"]=$this->io_function->uf_formatovalidofecha($row["fecha"]);
					$ldec_monto=$row["monto"];
					$ls_cuenta=$row["spi_cuenta"];
					$ls_fila=$la_fila[$li_i];
					$lb_valido=$this->uf_spi_saldos_update($ls_codemp,$ls_cuenta,$ls_fila,$ldec_monto,$ls_mensaje);							
					if(!$lb_valido)
					{
						print "Error al actualizar la cuenta ".$ls_cuenta." con mensaje ".$ls_mensaje;
					}					
				}		
			}									
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion="Reproceso los Saldos de Presupuesto de Ingresos";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		if($lb_valido)
		{
			$this->io_sql->commit(); 
		}
		else
		{
			$this->io_sql->rollback();
		}
		return $lb_valido;	
	}//fin uf_reprocesar_saldos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_spi_saldos_update($as_codemp, $as_cuenta, $as_fila, $ai_valor, $as_mensaje)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function: uf_spg_saldos_update
		//	  Returns:  boolean si existe o  no 
		//Description:  actualiza el saldo de una cuenta
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
		$lb_valido=true;
		$ls_nextcuenta=$as_cuenta;
		$li_nivel=$this->io_int_spi->uf_spi_obtener_nivel($ls_nextcuenta);
		while(($li_nivel >= 1)&&($lb_valido)&&($ls_nextcuenta!=''))
		{
			if($this->io_int_spi->uf_spi_saldo_select($as_codemp,$ls_nextcuenta,&$ls_status,&$ldec_previsto,&$ldec_aumento,&$ldec_disminucion,&$ldec_devengado,&$ldec_cobrado,&$ldec_cobrado_anticipado))
			{
				//if($this->io_int_spi->uf_spi_saldo_ajusta($as_codemp,$as_mensaje,$ls_nextcuenta,$ls_status,0, $ai_valor,&$ldec_previsto,&$ldec_aumento,&$ldec_disminucion,&$ldec_devengado,&$ldec_cobrado,&$ldec_cobrado_anticipado))
				//{
					$ls_sql="UPDATE spi_cuentas ".
							"   SET ".$as_fila."=".$as_fila."+".$ai_valor."".
							" WHERE codemp='".$as_codemp."' ".
							"   AND spi_cuenta = '".$ls_nextcuenta."'";
					$li_rows=$this->io_sql->execute($ls_sql);
					if($li_rows===false)
					{
						$this->is_msg_error="CLASE->sigesp_int_spi MÉTODO->uf_spi_saldos_update ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
						$lb_valido=false;
					}
				//}
				//else
				//{
				//	$lb_valido=false;
				//}
			}
			else
			{
				$lb_valido=false;
			}
			if($this->io_int_spi->uf_spi_obtener_nivel($ls_nextcuenta)==1)
			{
				break;
			}
			$ls_nextcuenta=$this->io_int_spi->uf_spi_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel=$this->io_int_spi->uf_spi_obtener_nivel($ls_nextcuenta);  
		}
		return $lb_valido;
	} // end function uf_spg_saldos_update
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>