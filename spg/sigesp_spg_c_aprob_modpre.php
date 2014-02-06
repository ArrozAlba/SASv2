<?php 
class sigesp_spg_c_aprob_modpre
{
	var $SQL;
	var $io_function;
	var $msg;
	var $is_msg_error;	
	var $ds_sol;
	var $dat;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	function sigesp_spg_c_aprob_modpre($aa_security)
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("../shared/class_folder/sigesp_include.php");
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->io_function=new class_funciones();
		$this->msg=new class_mensajes();
		$this->dat=$_SESSION["la_empresa"];
		$this->is_empresa = $aa_security[1];
		$this->is_sistema = $aa_security[2];
		$this->is_logusr  = $aa_security[3];	
		$this->is_ventana = $aa_security[4];
		
					
	}//Fin del constructor

	function uf_cargar_comprobantes($ls_codemp,$ls_comprobante,$ls_procede,$ld_fecha,&$object,&$li_row)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:		uf_cargar_comprobantes
		// Access:			public
		//	Returns:		Boolean Retorna si encontro o no errores en la consulta
		//	Description:	Funcion que se encarga de retornar los comprobantes de modificaciones 
		//					presupuestarias para su proceso de aprobacion .
		//////////////////////////////////////////////////////////////////////////////
	
		$li_row=0;
		$ls_aux="";
		if($ld_fecha!="")
		{$ls_aux=" AND fecha ='".$this->io_function->uf_convertirdatetobd($ld_fecha)."'";	}
		if($ls_comprobante!="")
		{$ls_aux=$ls_aux." AND comprobante like '%".$ls_comprobante."%'";	}
		if($ls_procede!="")
		{$ls_aux=$ls_aux." AND procede like '%".$ls_procede."%'";	}
		
		$ls_sql="SELECT * 
				 FROM sigesp_cmp_md 
				 WHERE codemp='".$ls_codemp."' AND estapro=0 ".$ls_aux
				 ." ORDER BY fecha,comprobante";		
		$rs_comprobantes = $this->SQL->select($ls_sql);
		
		if($rs_comprobantes===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_cargar_comprobantes".$this->io_function->uf_convertirmsg($this->SQL->message);
			$data="";
		}
		else
		{
			while($row=$this->SQL->fetch_row($rs_comprobantes))
			{
				$li_row++;
				$ls_comprobante=$row["comprobante"];
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($row["fecha"]);
				$ls_procede=$row["procede"];
				$ls_descripcion=$row["descripcion"];				
				$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 >";		
				$object[$li_row][2] = "<input type=text name=txtcomprobante".$li_row."   value='".$ls_comprobante."'      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$object[$li_row][3] = "<input type=text name=txtfecha".$li_row."   value='".$ld_fecha."'    class=sin-borde readonly style=text-align:right size=15 maxlength=12 >";
				$object[$li_row][4] = "<input type=text name=txtdescripion".$li_row."  value='".$ls_descripcion."'    class=sin-borde readonly style=text-align:center size=50 maxlength=250>";			
				$object[$li_row][5] = "<input name=txtprocede".$li_row." type=text id=txtprocede".$li_row." class=sin-borde  value='".$ls_procede."'>";			
			}
			if($li_row==0)			
			{
				$object[$li_row][1] = "<input type=checkbox name=chksel".$li_row."  id=chksel".$li_row." value=1 >";		
				$object[$li_row][2] = "<input type=text name=txtcomprobante".$li_row."   value=''      class=sin-borde readonly style=text-align:center size=17 maxlength=15 >";
				$object[$li_row][3] = "<input type=text name=txtfecha".$li_row."   value=''    class=sin-borde readonly style=text-align:right size=15 maxlength=12 >";
				$object[$li_row][4] = "<input type=text name=txtdescripion".$li_row."  value=''    class=sin-borde readonly style=text-align:center size=50 maxlength=250>";			
				$object[$li_row][5] = "<input name=txtprocede".$li_row." type=text class=sin-borde id=txtprocede".$li_row." value=''>";			
			}
			$this->SQL->free_result($rs_programaciones);
		}		
	}//Fin uf_cargar_comprobantes
	
	function uf_procesar_aprobacion($ls_comprobante,$ld_fecha,$ls_procede)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:		uf_procesar_aprobacion
		//  Access:			public
		//	Returns:	    Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de realizar la aprobacion de los comprobantes
		//					de modificaiones presupuestarias.
		//////////////////////////////////////////////////////////////////////////////
		
		$li_ds_total=0;$li_x=0;		
		$lb_valido = true;
			
		$ls_codemp   = $this->dat["codemp"];
		$ls_codusu   = $_SESSION["la_logusr"];
		
		
	
		return $lb_valido;

	}//Fin de uf_procesar_aprobacion
	
	
	
	
}
?>