<?PHP
class sigesp_sob_c_contabilizacion
{
	 var $io_function;
	 var $la_empresa;
	 var $io_sql;
	 var $io_msg;
	 var $io_funsob;
	function sigesp_sob_c_contabilizacion()
	{
		require_once("../shared/class_folder/sigesp_include.php");
		$io_siginc=new sigesp_include();
		$io_connect=$io_siginc->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_connect);	
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_function=new class_funciones();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_msg=new class_mensajes();
		$this->la_empresa=$_SESSION["la_empresa"];
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->seguridad=new sigesp_c_seguridad();
		require_once("class_folder/sigesp_sob_c_funciones_sob.php");
		$this->io_funsob= new sigesp_sob_c_funciones_sob();
	}
	
	function uf_select_contrato($as_campo,$as_orden,&$aa_data,&$ai_filas)
	{
		//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_contrato	 //
	 //	Access:  public
	 //	Returns: arreglo con los contrato
	 //	Description: Funcion que retorna los contratos que pueden ser contabilizados
	 // Fecha: 23/05/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT c.codcon,o.desobr,c.feccon,c.monto,c.estcon 
				 FROM 	sob_contrato c, sob_obra o, sob_asignacion a 
				 WHERE c.codemp='".$ls_codemp."' AND c.codemp=o.codemp AND c.codemp=a.codemp AND c.codasi=a.codasi 
				 AND a.codobr=o.codobr  AND c.estcon<>'3' ORDER BY ".$as_campo." ".$as_orden."";		
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_contrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ai_filas=$this->io_sql->num_rows($rs_data);
				
			}else
			{
				$aa_data="";
				$ai_filas=0;
			}			
		}		
		return $lb_valido;
	}
	
	function uf_select_variacion($as_campo,$as_orden,$as_tipvar,&$aa_data,&$ai_filas)
	{
		//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_variacion	 //
	 //	Access:  public
	 //	Returns: arreglo con las variaciones de contrato
	 //	Description: Funcion que retorna las variaciones de contratos que pueden ser contabilizados
	 // Fecha: 24/05/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT  v.numvar,v.codcon,v.fecvar,v.monto,o.desobr,v.estvar
				FROM sob_variacioncontrato v,sob_obra o,sob_contrato c, sob_asignacion a
				WHERE v.codemp='".$ls_codemp."' AND v.codemp=o.codemp AND v.codemp=a.codemp AND v.tipvar=".$as_tipvar." 
				AND v.codcon=c.codcon AND c.codasi=a.codasi AND a.codobr=o.codobr ORDER BY ".$as_campo." ".$as_orden."";		
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_contrato".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_data=$this->io_sql->obtener_datos($rs_data);
				$ai_filas=$this->io_sql->num_rows($rs_data);
				
			}else
			{
				$aa_data="";
				$ai_filas=0;
			}			
		}		
		return $lb_valido;
	}
}
?>