<?Php

class sigesp_srh_c_pais
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_pais($path)
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
  

 function getProximoCodigo()
  {
    $ls_codest = "01";
    $ls_sql = "SELECT MAX(codest) AS codigo FROM sigesp_estados";
	
   $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->estado MÉTODO->getProximoCodigo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			
		}
		else
		{
      $ls_codest = $la_datos["codigo"][0]+1;
	  }
    if ($ls_codest < 10)
     $ls_codest = "0".$ls_codest;
    return $ls_codest;
  }
  
 function getPais($ps_orden="",&$pa_datos="")
  {
    $lb_valido=true;
    $ls_sql = " SELECT * FROM sigesp_pais ".
	          " WHERE codpai <> '---' ".$ps_orden;
	
	$lb_valido=$this->io_sql->seleccionar($ls_sql, $pa_datos);
   
    return $lb_valido;

  }
  
 
}
?>