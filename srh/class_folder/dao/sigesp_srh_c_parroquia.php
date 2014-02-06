<?Php
class sigesp_srh_c_parroquia
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_srh_c_parroquia($path)
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
  
  public function getProximoCodigo($ps_codest,$ps_codmun)
  {
    $ls_codpar = "01";
    $ls_sql = "SELECT MAX(codpar) AS codigo FROM sigesp_parroquia ".
	          "WHERE codest = '$ps_codest'".
	          " AND codmun = '$ps_codmun'";
   $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->parroquia MÉTODO->getProximoCodigo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			
		}
		else
		{
      $ls_codpar = $la_datos["codigo"][0]+1;
	  }
    if ($ls_codpar < 10)
     $ls_codpar = "0".$ls_codpar;
    return $ls_codpar;
  }
  
  public function getparroquias($ps_codpai,$ps_codest,$ps_codmun,$ps_orden="",&$pa_datos="")
  {
   $lb_valido=true;
    $ls_sql = "SELECT * FROM sigesp_parroquia ".
	          " WHERE codpai= '$ps_codpai' ".
			  " AND codest = '$ps_codest' ".
	          " AND codmun = '$ps_codmun' ".
			  " AND codpar <> '---' ".$ps_orden;
			 
			
	$lb_valido=$this->io_sql->seleccionar($ls_sql, $pa_datos);
   
    return $lb_valido;
			  
	
  }
 
}
?>