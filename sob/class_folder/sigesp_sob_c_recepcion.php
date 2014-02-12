<?PHP
class sigesp_sob_c_recepcion
{
	 var $io_function;
	 var $la_empresa;
	 var $io_sql;
	 var $io_msg;
	 var $io_funsob;
	function sigesp_sob_c_recepcion()
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
	
	function uf_select_valuacion($as_campo,$as_orden,&$aa_data,&$ai_filas)
	{
		//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_valuacion	 //
	 //	Access:  public
	 //	Returns: arreglo con las valuaciones
	 //	Description: Funcion que retorna las que valuaciones que pueden generar recepcion de documentos
	 // Fecha: 25/05/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT v.codval,v.codcon,v.montotval,v.fecha,v.estval,o.desobr 
				FROM sob_valuacion v,sob_contrato c,sob_asignacion a,sob_obra o
				WHERE v.codemp='".$ls_codemp."' AND v.codemp=c.codemp AND v.codemp=a.codemp 
				AND v.codemp=o.codemp AND v.codcon=c.codcon AND c.codasi=a.codasi AND
				a.codobr=o.codobr AND (v.estval=1 OR v.estval=5) ORDER BY ".$as_campo." ".$as_orden."";		
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_valuacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
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
	
	function uf_select_anticipo($as_campo,$as_orden,&$aa_data,&$ai_filas)
	{
		//////////////////////////////////////////////////////////////////////////////
	 //	Metodo: uf_select_anticipo
	 //	Access:  public
	 //	Returns: arreglo con los anticipos
	 //	Description: Funcion que retorna los anticipos que pueden ser contabilizados
	 // Fecha: 25/05/2006
     // Autor: Ing. Laura Cabré
	 //////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_sql="SELECT an.codant,an.codcon,an.montotant,an.estant,an.fecant,o.desobr 
				FROM sob_anticipo an, sob_obra o,sob_contrato c, sob_asignacion a
				WHERE an.codemp='".$ls_codemp."' AND an.codemp=o.codemp AND an.codemp=c.codemp 
				AND an.codemp=a.codemp AND an.codcon=c.codcon AND c.codasi=a.codasi
				AND a.codobr=o.codobr AND (an.estant=1 OR an.estant=2) ORDER BY ".$as_campo." ".$as_orden."";		
		$rs_data=$this->io_sql->select($ls_sql);   
		if($rs_data===false)
		{
			$this->is_msg_error="Error en uf_select_anticipo".$this->io_function->uf_convertirmsg($this->io_sql->message);
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