<?Php

class sigesp_srh_c_estado
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_estado($path)
	{   require_once($path."shared/class_folder/class_sql.php");
		require_once($path."shared/class_folder/class_datastore.php");
		require_once($path."shared/class_folder/class_mensajes.php");
		require_once($path."shared/class_folder/sigesp_include.php");
		require_once($path."shared/class_folder/sigesp_c_seguridad.php");
		require_once($path."shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
		$this->la_empresa=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];

}
  

 function getEstados($ps_codpai,$ps_orden="",&$pa_datos="")
  {
    $lb_valido=true;
    $ls_sql = " SELECT * FROM sigesp_estados ".
	          " WHERE codpai ='$ps_codpai'".
			  " AND   codest <> '---' ".$ps_orden;
			
	
	$lb_valido=$this->io_sql->seleccionar($ls_sql, $pa_datos);
   
    return $lb_valido;

  }
  
 
}
?>