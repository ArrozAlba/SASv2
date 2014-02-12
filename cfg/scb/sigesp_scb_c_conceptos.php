<?php
class sigesp_scb_c_conceptos
{

 var $io_sql;
 var $fun;
 var $siginc;
 var $datemp;
 var $is_msg_error;
 var $io_seguridad;
 var $is_empresa;
 var $is_sistema;
 var $is_logusr;
 var $is_ventanas;
 
function sigesp_scb_c_conceptos($aa_security)
{
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$this->fun=new class_funciones();
	$this->siginc=new sigesp_include();
	$con=$this->siginc->uf_conectar();
	$this->datemp=$_SESSION["la_empresa"];
	$this->is_empresa = $aa_security[1];
	$this->is_sistema = $aa_security[2];
	$this->is_logusr  = $aa_security[3];	
	$this->is_ventana = $aa_security[4];
	$this->io_seguridad= new sigesp_c_seguridad();	
	$this->io_sql=new class_sql($con);
	$this->io_keygen= new sigesp_c_generar_consecutivo();
	$this->io_mensajes=new class_mensajes();
}
		
function uf_select_conceptos($ls_codigo,$ls_codope)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:  uf_select_conceptos
	// Parameters: $ls_codigo( Codigo del concepto)		
	//			   $ls_codope( Codigo de la operacion asociada al concepto) 	
	// Descripcion: -Funcion que verifica la existencia del concepto
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_cadena="SELECT * FROM scb_concepto WHERE codconmov='".$ls_codigo."'";
	
	$rs_data=$this->io_sql->select($ls_cadena);

	if($rs_data===false)//Hubo error en la consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else//No hubo error en la consulta
	{
		if($row=$this->io_sql->fetch_row($rs_data))//Encontro registgros
		{
			$lb_valido=true;
		}
		else//No encontro registros
		{
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado";
		}
		$this->io_sql->free_result($rs_data); 
	}
	return $lb_valido;


}


	
function uf_guardar_conceptos(&$ls_codigo,$ls_codope,$ls_denominacion,$ls_status)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	if($ls_status!='C')//Si no existe lo inserto
	{
		$lb_valido=$this->uf_insert_conceptos(&$ls_codigo,$ls_codope,$ls_denominacion);
	}
	else//Existe por tanto actualizo
	{
		$lb_valido=$this->uf_update_conceptos(&$ls_codigo,$ls_codope,$ls_denominacion);
	}
	if($lb_valido)
	{
		$this->is_msg_error="Se registro el concepto correctamente";
	}
	else
	{
		$this->is_msg_error="Ocurrio un error al registrar el concepto";
	}
	return $lb_valido;
}


function uf_insert_conceptos(&$ls_codigo,$ls_codope,$ls_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_insert_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CFG","scb_concepto","codconmov","CFGCTO",3,"","","",&$ls_codigo);
	if($lb_valido)
	{
		$ls_sql= "INSERT INTO scb_concepto(codconmov,denconmov,codope)".
				 " VALUES('".$ls_codigo."','".$ls_denominacion."','".$ls_codope."') ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			 $this->io_sql->rollback();
			 if ($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
			 {
				 $this->uf_insert_conceptos(&$ls_codigo,$ls_codope,$ls_denominacion);
			 }
			 else
			 {print $this->io_sql->message;
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			 }
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el concepto ".$ls_codigo." Asociado a la empresa ".$this->is_empresa;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,
											$this->is_sistema,$ls_evento,$this->is_logusr,
											$this->is_ventana,$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
	}
	return $lb_valido;
}

function uf_update_conceptos(&$ls_codigo,$ls_codope,$ls_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_update_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido= true;
	$ls_sql="UPDATE scb_concepto".
			"   SET denconmov='".$ls_denominacion."'".
			" WHERE codconmov='".$ls_codigo."'";
	$li_row=$this->io_sql->execute($ls_sql);
	if($li_row===false)
	{
		$lb_valido=false;
		$this->io_mensajes->message("CLASE->Solicitud MÉTODO->uf_insert_solicitud ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion ="Actualizó el concepto ".$ls_codigo." Asociado a la empresa ".$this->is_empresa;
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,
										$this->is_sistema,$ls_evento,$this->is_logusr,
										$this->is_ventana,$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
	return $lb_valido;
}

function uf_delete_conceptos($ls_codigo,$ls_codope,$ls_denominacion,$ls_status)
{
//////////////////////////////////////////////////////////////////////////////////////////
// Function:    - uf_delete_conceptos
// Parameters:  - $ls_codigo( Codigo del concepto).		
//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
//			    - $ls_denominacion (Denominacion del concepto).
// Descripcion: - Funcion que elimina el concepto.
//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$lb_valido=$this->uf_delete_detalles($ls_codigo);
	if($lb_valido)
	{
		$ls_sql    = "DELETE FROM scb_concepto WHERE codconmov='".$ls_codigo."'";
		$this->io_sql->begin_transaction();//Inicio la transaccion.
		$rs_data   = $this->io_sql->execute($ls_sql);//Ejecuto la sentencia io_sql.
		if ($rs_data===false)//Verifico si hubo error en la consulta.
		   {
			 $lb_valido=false;
			 $this->is_msg_error="Error en metodo uf_delete_conceptos ".$this->fun->uf_convertirmsg($this->io_sql->message);
		   }
		else
		   {
			 $lb_valido=true;
			 ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
			 $ls_evento="DELETE";
			 $ls_descripcion="Elimino el concepto de movimiento ".$ls_codigo." con denominacion ".$ls_denominacion." y la operacion ".$ls_codope ;
			 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			 ////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		}
	return $lb_valido;
}

function uf_delete_detalles($ls_codigo)
{
//////////////////////////////////////////////////////////////////////////////////////////
// Function:    - uf_delete_conceptos
// Parameters:  - $ls_codigo( Codigo del concepto).		
//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
//			    - $ls_denominacion (Denominacion del concepto).
// Descripcion: - Funcion que elimina el concepto.
//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql="DELETE FROM scb_casamientoconcepto".
			" WHERE codconmov='".$ls_codigo."'";
	$rs_data= $this->io_sql->execute($ls_sql);//Ejecuto la sentencia io_sql.
	if ($rs_data===false)//Verifico si hubo error en la consulta.
	{
	     $lb_valido=false;
	  	 $this->is_msg_error="Error en metodo uf_delete_detalle ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
	     $lb_valido=true;
		 ///////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino el detallo de concepto de movimiento ".$ls_codigo;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	return $lb_valido;
}
function uf_insert_detalle($as_codigo,$as_codban,$as_ctaban)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql="INSERT INTO scb_casamientoconcepto(codconmov,codban ,ctaban )".
			"  VALUES('".$as_codigo."','".$as_codban."','".$as_ctaban."') ";
		$this->is_msg_error="Registro Incluido";		
	$li_numrows=$this->io_sql->execute($ls_sql);//Ejecuto la sentencia io_sql y retorno numero de filas afectadas
	if($li_numrows===false)//Verifico si hubo error enla consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en metodo uf_guardar_conceptos".$this->fun->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
	}
	else
	{
		$lb_valido=true;
		//////////////////////////////////////////Inserto eventos en seguridad/////////////////////////////////////////////		
		////////////////////////////////////Parametros de seguridad////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el detalle del concepto de movimiento ".$as_codigo." con banco ".$as_codban." y la cuenta ".$as_ctaban;
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	return $lb_valido;
}
function uf_select_buscardetalle($ls_codigo,&$ai_totrow,&$aa_object)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:  uf_select_conceptos
	// Parameters: $ls_codigo( Codigo del concepto)		
	//			   $ls_codope( Codigo de la operacion asociada al concepto) 	
	// Descripcion: -Funcion que verifica la existencia del concepto
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="SELECT scb_casamientoconcepto.codconmov,scb_casamientoconcepto.codban,scb_casamientoconcepto.ctaban,scb_banco.nomban,".
			"       scb_ctabanco.dencta".
			"  FROM scb_casamientoconcepto,scb_banco,scb_ctabanco".
			" WHERE codconmov='".$ls_codigo."'".
			"   AND scb_casamientoconcepto.codban=scb_banco.codban".
			"   AND scb_casamientoconcepto.codban=scb_ctabanco.codban".
			"   AND scb_casamientoconcepto.ctaban=scb_ctabanco.ctaban";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)//Hubo error en la consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else//No hubo error en la consulta
	{
		$li_i=0;
		while($row=$this->io_sql->fetch_row($rs_data))//Encontro registgros
		{
			$li_i=$li_i+1;
			$ls_codban=$row["codban"];
			$ls_denban=$row["nomban"];
			$ls_ctaban=$row["ctaban"];
			$ls_dencta=$row["dencta"];
			$aa_object[$li_i][1]="<input name=txtdenban".$li_i." type=text   id=txtdenban".$li_i." class=sin-borde size=30 value='".$ls_denban."' readonly>".
								 "<input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." class=sin-borde size=17 value='".$ls_codban."' readonly>";
			$aa_object[$li_i][2]="<input name=txtctaban".$li_i." type=text   id=txtctaban".$li_i." class=sin-borde size=30 value='".$ls_ctaban."' readonly>";
			$aa_object[$li_i][3]="<input name=txtdencta".$li_i." type=text   id=txtdencta".$li_i." class=sin-borde size=45 value='".$ls_dencta."' readonly>";
			$aa_object[$li_i][4]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		$li_i=$li_i+1;
		$aa_object[$li_i][1]="<input name=txtdenban".$li_i." type=text   id=txtdenban".$li_i." class=sin-borde size=30 readonly>".
								   "<input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." class=sin-borde size=17 readonly>";
		$aa_object[$li_i][2]="<input name=txtctaban".$li_i." type=text   id=txtctaban".$li_i."  class=sin-borde size=30 maxlength=15 readonly>";
		$aa_object[$li_i][3]="<input name=txtdencta".$li_i." type=text   id=txtdencta".$li_i." class=sin-borde size=45 readonly>";
		$aa_object[$li_i][4]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		
		$ai_totrow=$li_i;
		$this->io_sql->free_result($rs_data); 
	}
	return $lb_valido;
	}
function uf_select_configuracion()
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:  uf_select_conceptos
	// Parameters: $ls_codigo( Codigo del concepto)		
	//			   $ls_codope( Codigo de la operacion asociada al concepto) 	
	// Descripcion: -Funcion que verifica la existencia del concepto
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_sql="SELECT casconmov".
			"  FROM sigesp_empresa".
			" WHERE codemp='".$ls_codemp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)//Hubo error en la consulta
	{
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else//No hubo error en la consulta
	{
		if($row=$this->io_sql->fetch_row($rs_data))//Encontro registgros
		{
			$ls_casconmov=$row["casconmov"];
			if($ls_casconmov==1)
			{
				$lb_valido=true;
			}
		}
		$this->io_sql->free_result($rs_data); 
	}
	return $lb_valido;

}

}
?>