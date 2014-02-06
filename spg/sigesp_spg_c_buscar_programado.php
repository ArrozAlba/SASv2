<?php
class sigesp_spg_c_buscar_programado
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;	
	var $io_msg;
	var $io_function;
	var $is_codemp;
	var $is_procedencia;
	var $is_comprobante;
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
	var $io_sigesp_int_spg;
	function sigesp_spg_c_buscar_programado()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_fecha.php");	
		require_once("../shared/class_folder/class_funciones.php");		
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sigesp_int_spg.php");		
	
		$this->io_function=new class_funciones();			
		$this->io_fecha=new class_fecha();
		$this->io_include=new sigesp_include();	
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();		
		$this->is_msg_error="";	
		$this->io_sigesp_int_spg = new class_sigesp_int_spg();	
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
		$this->li_codemp=$_SESSION["la_empresa"]["codemp"];
	}	

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_buscar_monto($as_operacion,$fecIni,$fecFin, $codest1,$codest2,$codest3,$codest4,$codest5,$estcla,$as_cuenta)
	{
		   
		    $ls_monto=0;
		    $as_cuenta=$this->io_sigesp_int_spg->uf_spg_cuenta_sin_cero($as_cuenta);
		    $ls_sql= "  SELECT COALESCE(SUM(monto),0) As monto ".
					"	 FROM spg_dt_cmp PCT, spg_operaciones Op".
					"	 where Op.$as_operacion='1' ".
					"	   and PCT.fecha between '".$fecIni."' and '".$fecFin."' ".
					"	   AND PCT.codestpro1='".$codest1."' ".
					"	   and PCT.codestpro2='".$codest2."' ".
					"	   and PCT.codestpro3='".$codest3."' ".
					"	   and PCT.codestpro4='".$codest4."' ".
					"	   and PCT.codestpro5='".$codest5."' ".
					"	   and PCT.estcla='".$estcla."'      ".
					"	   and PCT.spg_cuenta like '$as_cuenta%' ".
					"      and PCT.operacion=Op.operacion";  
			$rs_monto = $this->io_sql->select($ls_sql); 
			if ($rs_monto===false)
			{				
	     		$msg->message($fun->uf_convertirmsg($this->io_sql->message)); 	   
			}
			else
			{
				$li_numrows = $this->io_sql->num_rows($rs_monto); 
				if ($row=$this->io_sql->fetch_row($rs_monto))
				{ 
					$ls_monto=$row["monto"];
				}
				$this->io_sql->free_result($rs_monto);	
			}	
		return $ls_monto;
	}// fin de la funciòn uf_buscar_monto
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
}// fin de la calse sigesp_spg_c_eliminar_comprobantes
?>