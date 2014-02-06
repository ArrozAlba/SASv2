<?php
class sigesp_scb_c_tipocolocacion
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
	function sigesp_scb_c_tipocolocacion($aa_security)
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
		$this->io_sql=new class_sql($con);
		$this->io_keygen= new sigesp_c_generar_consecutivo();
	}

		
function uf_select_tipocolocacion($ls_codigo)
{
//////////////////////////////////////////////////////////////////////////////////////////
// Function:  uf_select_tipòcolocacion
// Parameters:  - $ls_codigo( Codigo del tipo de colocacion)		
// Descripcion: -Funcion que verifica la existencia del tipo
//////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql  = "SELECT * FROM scb_tipocolocacion WHERE codtipcol='".$ls_codigo."'";
	$rs_data = $this->io_sql->select($ls_sql);//Ejecuto la sentencia io_sql.
	if ($rs_data===false)//Verifico si hubo error.
	   {
		 $lb_valido=false;
		 $this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
		  if ($row=$this->io_sql->fetch_row($rs_data))//Verifico si encontro el registro
		     {
			   $lb_valido=true;
		       $this->io_sql->free_result($rs_data); 
			 }
		  else
		     {
			   $lb_valido=false;
			   $this->is_msg_error="Registro no encontrado";
	 	     }  
		
	   }
	return $lb_valido;
}
	
function uf_guardar_tipocolocacion(&$ls_codigo,$ls_denominacion,$ls_status)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_guardar_tipòcolocacion
	// Parameters:  - $ls_codigo( Codigo del tipo de colocacion)		
	//				- $ls_denominacion (Denominacion del tipo de colocacion)
	// Descripcion: - Funcion que guarda el tipo de colocacion
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_existe=$this->uf_select_tipocolocacion($ls_codigo);//Verifico si existe
	if($ls_status!='C')//Si no existe lo inserto
	{
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CFG","scb_tipocolocacion","codtipcol","CFGTCO",3,"","","",&$ls_codigo);
		$ls_cadena= " INSERT INTO scb_tipocolocacion(codtipcol,nomtipcol) VALUES('".$ls_codigo."','".$ls_denominacion."') ";
		$this->is_msg_error="Registro Incluido !!!";		
		///////////////////////////////////////Parametros de seguridad///////////////////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el tipo de colocacion codigo ".$ls_codigo." con denominacion ".$ls_denominacion ;
		/////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		if($ls_status=='C')
		{
			$ls_cadena= " UPDATE scb_tipocolocacion SET nomtipcol='".$ls_denominacion."' WHERE codtipcol='".$ls_codigo."'";
			$this->is_msg_error="Registro Actualizado !!!";
			///////////////////////////////////////Parametros de seguridad///////////////////////////////////////////
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el tipo de colocacion codigo ".$ls_codigo." con denominacion ".$ls_denominacion ;
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
		{
			$this->is_msg_error="Registro ya existe introduzca un nuevo codigo";
			return false;
		}
	}

	$this->io_sql->begin_transaction();//Inicio la transaccion.

	$li_numrows=$this->io_sql->execute($ls_cadena);//Ejecuto la sentencia

	if($li_numrows===false)//Verifico si hubo error en la sentencia
	{
			if($ls_status!='C')
			{
					if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
					{
						$lb_valido=$this->uf_guardar_tipocolocacion(&$ls_codigo,$ls_denominacion,$ls_status);
					}
					else
					{
						$lb_valido=false;
						$this->is_msg_error="Error en metodo uf_guardar_tipocolocacion".$this->fun->uf_convertirmsg($this->SQL->message);
						$this->io_sql->rollback();
					}
			}
			else
			{
				$lb_valido=false;
				$this->is_msg_error="Error en metodo uf_guardar_tipocolocacion".$this->fun->uf_convertirmsg($this->SQL->message);
				$this->io_sql->rollback();
			}
	}
	else
	{
		$lb_valido=true;
		$this->io_sql->commit();
		$lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	}
	return $lb_valido;
}

function uf_check_relaciones($ls_codigo)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_check_relaciones
	// Parameters:  - $ls_codigo( Codigo del tipo de colocacion)		
	// Descripcion: - Funcion que chequea las relaciones
	//////////////////////////////////////////////////////////////////////////////////////////

	$ls_codemp=$this->datemp["codemp"];
	$ls_cadena="SELECT * FROM scb_colocacion WHERE codemp='".$ls_codemp."' AND codtipcol='".$ls_codigo."'";
	
	$rs_data=$this->io_sql->select($ls_cadena);//Ejecuto la sentencia io_sql.
	if($rs_data===false)//Verifico si hubo error en la consulta.
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))//Verifico si encontro relaciones
		{
			$lb_valido=true;
			$this->is_msg_error="No se puede eliminar, posee registros relacionados";
		}
		else
		{
			$lb_valido=false;
			$this->is_msg_error="Registro no encontrado";
		}
		$this->io_sql->free_result($rs_data); 
	}
	return $lb_valido;	
}

function uf_delete_tipocolocacion($ls_codigo,$ls_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_tipòcolocacion
	// Parameters:  - $ls_codigo( Codigo del tipo de colocacion)		
	//				- $ls_denominacion (Denominacion del tipo de colocacion)
	// Descripcion: - Funcion que elimina el tipo de colocacion
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = false;
	$ls_sql    = "DELETE FROM scb_tipocolocacion WHERE codtipcol='".$ls_codigo."'";
	$this->io_sql->begin_transaction();//Inicio la transaccion.
	$rs_data = $this->io_sql->execute($ls_sql);//Ejecuto la sentencia io_sql.
	if ($rs_data===false)//Verifico que no ocurrio error en consulta.
	   {
	     $lb_valido=false;
	 	 $this->is_msg_error="Error en metodo uf_delete_tipocolocacion ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	 else
	   {
	     $lb_valido=true;
		 $ls_evento="DELETE";
		 $ls_descripcion="Elimino el tipo de colocacion codigo ".$ls_codigo." con denominacion ".$ls_denominacion ;
		 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	   }
	return $lb_valido;
}
}
?>