<?php
class sigesp_scb_c_banco
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
function sigesp_scb_c_banco($aa_security)
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

		
	function uf_select_banco($ls_codigo)
	{
		$ls_codemp=$this->datemp["codemp"];
		$ls_cadena="SELECT * FROM scb_banco WHERE codemp='".$ls_codemp."' AND codban='".$ls_codigo."'";
		
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
	
	function uf_guardar_banco(&$ls_codigo,$ls_nombre,$ls_direccion,$ls_gerente,$ls_telefono,$ls_celular,$ls_email,$li_esttesnac,$ls_status,$ls_codsude)
	{
		$ls_codemp= $this->datemp["codemp"];
		$lb_existe=$this->uf_select_banco($ls_codigo);
		if($ls_status!='C')//Si no existe lo inserto
		{
		    $lb_valido= $this->io_keygen->uf_verificar_numero_generado("CFG","scb_banco","codban","CFGBAN",3,"","","",&$ls_codigo);
			$ls_cadena= " INSERT INTO scb_banco(codemp,codban,nomban,dirban,gerban,telban,conban,movcon,esttesnac,codsudeban) ".
			" VALUES('".$ls_codemp."','".$ls_codigo."','".$ls_nombre."','".$ls_direccion."','".$ls_gerente."','".$ls_telefono."','".$ls_email."','".$ls_celular."',".$li_esttesnac.",'".$ls_codsude."') ";

			$this->is_msg_error="Registro Incluido !!!";		

			$ls_evento="INSERT";
			$ls_descripcion="Inserto el banco con el codigo ".$ls_codigo." de nombre ".$ls_nombre ;
		}
		else
		{
			if($ls_status=="C")
			{
				$ls_cadena= " UPDATE scb_banco SET nomban='".$ls_nombre."',gerban='".$ls_gerente."',dirban='".$ls_direccion."',telban='".$ls_telefono."',conban='".$ls_email."',movcon='".$ls_celular."',esttesnac=".$li_esttesnac.",codsudeban='".$ls_codsude."' WHERE codemp='".$ls_codemp."' AND codban='".$ls_codigo."'";
				$this->is_msg_error="Registro Actualizado";
				$ls_evento="UPDATE";
				$ls_descripcion="Actualizo el banco con el codigo ".$ls_codigo." de nombre ".$ls_nombre ;
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
			if($ls_status!='C')
			{
					if($this->io_sql->errno=='23505' || $this->io_sql->errno=='1062' || $this->io_sql->errno=='-239' || $this->io_sql->errno=='-5'|| $this->io_sql->errno=='-1')
					{
						$lb_valido=$this->uf_guardar_banco(&$ls_codigo,$ls_nombre,$ls_direccion,$ls_gerente,$ls_telefono,$ls_celular,
															$ls_email,$li_esttesnac,$ls_status,$ls_codsude);
					}
					else
					{
						$lb_valido=false;
						$this->is_msg_error="Error en metodo uf_guardar_banco".$this->fun->uf_convertirmsg($this->SQL->message);
						$this->io_sql->rollback();
					}
			}
			else
			{
				$lb_valido=false;
				$this->is_msg_error="Error en metodo uf_guardar_banco".$this->fun->uf_convertirmsg($this->SQL->message);
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

function uf_delete_banco($ls_codigo,$ls_nombre,$ls_direccion,$ls_gerente,$ls_telefono,$ls_celular,$ls_email)
{
	$lb_valido = false;
	$ls_codemp = $this->datemp["codemp"];
	$ls_cadena = " DELETE FROM scb_banco WHERE codemp='".$ls_codemp."' AND codban='".$ls_codigo."'";
	$this->io_sql->begin_transaction();
	$rs_data   = $this->io_sql->execute($ls_cadena);
	if ($rs_data===false)
	   {
		 $lb_valido=false;
		 $this->is_msg_error="CLASE->SIGESP_SCB_C_BANCO->Metodo->uf_delete_banco ".$this->fun->uf_convertirmsg($this->io_sql->message);
	   }
	else
	   {
	     $lb_valido      = true;
		 $ls_evento      = "DELETE";
		 $ls_descripcion ="Elimino el banco codigo ".$ls_codigo." con nombre ".$ls_nombre ;
		 $lb_valido      = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
	   }
	return $lb_valido;
}
}
?>