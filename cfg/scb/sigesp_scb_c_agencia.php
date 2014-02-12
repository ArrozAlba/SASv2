<?php
class sigesp_scb_c_agencia
{

	 var $SQL;
	 var $fun;
	 var $siginc;
	 var $datemp;
	 var $is_msg_error;
	 var $io_seguridad;
	 var $is_empresa;
	 var $is_sistema;
	 var $is_logusr;
	 var $is_ventanas;
	 
	function sigesp_scb_c_agencia($aa_security)
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
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
		$this->SQL=new class_sql($con);
		$this->io_keygen= new sigesp_c_generar_consecutivo();
	}

		
function uf_select_agencias($ls_codigo,$ls_codban)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:  uf_select_conceptos
	// Parameters: $ls_codigo( Codigo del concepto)		
	//			   $ls_codope( Codigo de la operacion asociada al concepto) 	
	// Descripcion: -Funcion que verifica la existencia del concepto
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datemp["codemp"];		
	$ls_cadena="SELECT * FROM scb_agencias WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND codage='".$ls_codigo."'";
	$rs_agencias=$this->SQL->select($ls_cadena);

	if($rs_agencias===false)//Hubo error en la consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->SQL->message);
	}
	else//No hubo error en la consulta
	{
		if($row=$this->SQL->fetch_row($rs_agencias))//Encontro registgros
		{
			$lb_valido=true;
		}
		else//No encontro registros
		{
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado";
		}
	}
	return $lb_valido;

}

function uf_check_relacion($ls_codigo,$ls_codban)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:  uf_select_conceptos
	// Parameters: $ls_codigo( Codigo del concepto)		
	//			   $ls_codope( Codigo de la operacion asociada al concepto) 	
	// Descripcion: -Funcion que verifica la existencia del concepto
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datemp["codemp"];		
	$ls_cadena="SELECT * FROM scb_agencias WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND codage='".$ls_codigo."'";
	$rs_agencias=$this->SQL->select($ls_cadena);

	if($rs_agencias===false)//Hubo error en la consulta
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->SQL->message);
	}
	else//No hubo error en la consulta
	{
		if($row=$this->SQL->fetch_row($rs_agencias))//Encontro registgros
		{
			$lb_valido=true;
		}
		else//No encontro registros
		{
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado";
		}
	}
	return $lb_valido;

}

function uf_guardar_agencias($ls_codban,&$ls_codigo,$ls_denominacion,$ls_status)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_existe=$this->uf_select_agencias($ls_codigo,$ls_codban);//Verifico si existe
	$ls_codemp=$this->datemp["codemp"];
	if($ls_status!='C')//Si no existe lo inserto
	{
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CFG","scb_agencias","codage","CFGAGE",10,"","","",&$ls_codigo);
		$ls_cadena= " INSERT INTO scb_agencias(codemp,codban,codage,nomage) VALUES('".$ls_codemp."','".$ls_codban."','".$ls_codigo."','".$ls_denominacion."')";
		$this->is_msg_error="Registro Incluido";		
		////////////////////////////////////Parametros de seguridad////////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto la agencia ".$ls_codigo."  con denominacion ".$ls_denominacion."asociada al banco ".$ls_codban ;
		///////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else//Existe por tanto actualizo
	{
		if($ls_status=='C')
		{
			$ls_cadena= " UPDATE scb_agencias SET nomage='".$ls_denominacion."' WHERE codage='".$ls_codigo."' AND codban ='".$ls_codban."' AND codemp='".$ls_codemp."'";
			$this->is_msg_error="Registro Actualizado";
			////////////////////////////////////Parametros de seguridad////////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo la agencias ".$ls_codigo." con denominacion ".$ls_denominacion." y asociada al banco ".$ls_codban ;
			///////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
		{
			$this->is_msg_error="Registro ya existe introduzca un nuevo codigo";
			return false;
		}
	}

	$this->SQL->begin_transaction();//Inicio la transaccion
	$li_numrows=$this->SQL->execute($ls_cadena);//Ejecuto la sentencia SQL y retorno numero de filas afectadas

	if(($li_numrows===false))//Verifico si hubo error enla consulta
	{
		if($ls_status!='C')
		{
				if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
				{
					$lb_valido=$this->uf_guardar_agencias($ls_codban,&$ls_codigo,$ls_denominacion,$ls_status);
				}
				else
				{
					$lb_valido=false;
					$this->is_msg_error="Error en metodo uf_guardar_agencias".$this->fun->uf_convertirmsg($this->SQL->message);
					$this->SQL->rollback();
				}
		}
		else
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_guardar_agencias".$this->fun->uf_convertirmsg($this->SQL->message);
			$this->SQL->rollback();
		}

	}
	else
	{
		$lb_valido=true;
		$this->SQL->commit();//Hago commit de la transaccion
		//////////////////////////////////////////Inserto eventos en seguridad/////////////////////////////////////////////		
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	return $lb_valido;
}

function uf_delete_agencias($ls_codigo,$ls_codban,$ls_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_conceptos
	// Parameters:  - $ls_codigo( Codigo del concepto).		
	//			    - $ls_codope( Codigo de la operacion asociada al concepto). 	
	//			    - $ls_denominacion (Denominacion del concepto).
	// Descripcion: - Funcion que elimina el concepto.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_codemp = $this->datemp["codemp"];
	$ls_sql    = "DELETE FROM scb_agencias WHERE codemp='".$ls_codemp."' AND codage='".$ls_codigo."' AND codban='".$ls_codban."'";
	$this->SQL->begin_transaction();//Inicio la transaccion.
	$rs_data   = $this->SQL->execute($ls_sql);//Ejecuto la sentencia SQL.
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->is_msg_error="Error en metodo uf_delete_agencias ".$this->fun->uf_convertirmsg($this->SQL->message);
	   }
	else
	   {				
		 $lb_valido=true;
		 //////////////////////////////////Parametros de seguridad/////////////////////////////////////////////////
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino la agencia ".$ls_codigo." con denominacion ".$ls_denominacion." y asociada al banco ".$ls_codban ;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   }
	return $lb_valido;
}
}
?>