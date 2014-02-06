<?php
class sigesp_scb_c_tipocta
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

function sigesp_scb_c_tipocta($aa_security)
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

		
function uf_select_tipocta($ls_codigo)
{
	
	$ls_cadena="SELECT * FROM scb_tipocuenta WHERE codtipcta='".$ls_codigo."'";
	
	$rs_data=$this->io_sql->select($ls_cadena);
	if($rs_data===false)
	{
		$lb_valido=false;
		$this->is_msg_error="Error en consulta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
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
	
function uf_guardar_tipocta(&$ls_codigo,$ls_denominacion,$ls_status)
{
	$lb_existe=$this->uf_select_tipocta($ls_codigo);

	if($ls_status!='C')//Si no existe lo inserto
	{
		$lb_valido= $this->io_keygen->uf_verificar_numero_generado("CFG","scb_tipocuenta","codtipcta","CFGBAN",3,"","","",&$ls_codigo);
		$ls_cadena= " INSERT INTO scb_tipocuenta(codtipcta,nomtipcta) VALUES('".$ls_codigo."','".$ls_denominacion."') ";
		$this->is_msg_error="Registro Incluido !!!";		
		$ls_evento="INSERT";
		$ls_descripcion="Inserto el tipo de cuenta codigo ".$ls_codigo." con denominacion ".$ls_denominacion ;
	}
	else
	{
		if($ls_status=='C')
		{
			$ls_cadena= " UPDATE scb_tipocuenta SET nomtipcta='".$ls_denominacion."' WHERE codtipcta='".$ls_codigo."'";
			$this->is_msg_error="Registro Actualizado !!!";
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo el tipo de cuenta codigo ".$ls_codigo." con denominacion ".$ls_denominacion ;
		}
		else
		{
			$this->is_msg_error="Registro ya existe introduzca un nuevo numero";
			return false;
		}
	}
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);
	if($li_numrows===false)
	{
			if($ls_status!='C')
			{
					if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
					{
						$lb_valido=$this->uf_guardar_tipocta(&$ls_codigo,$ls_denominacion,$ls_status);
					}
					else
					{
						$lb_valido=false;
						$this->is_msg_error="Error en metodo uf_guardar_tipocta".$this->fun->uf_convertirmsg($this->SQL->message);
						$this->io_sql->rollback();
					}
			}
			else
			{
				$lb_valido=false;
				$this->is_msg_error="Error en metodo uf_guardar_tipocta".$this->fun->uf_convertirmsg($this->SQL->message);
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

function uf_delete_tipocta($ls_codigo,$ls_denominacion,$ls_status)
{
  $lb_valido = false;
  $ls_sql    = "DELETE FROM scb_tipocuenta WHERE codtipcta='".$ls_codigo."'";
  $this->io_sql->begin_transaction();
  $rs_data   = $this->io_sql->execute($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->is_msg_error="CLASE->SIGESP_SCB_C_TIPOCTA->METODO->uf_delete_tipocta ".$this->fun->uf_convertirmsg($this->io_sql->message);
	 }
  else
	 {
	   $lb_valido=true;
	   $ls_evento="DELETE";
	   $ls_descripcion="Elimino el tipo de cuenta codigo ".$ls_codigo." con denominacion ".$ls_denominacion ;
	   $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	 }
  return $lb_valido;
}
}
?>