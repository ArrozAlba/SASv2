<?php
//session_start();
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
class sigesp_spg_c_catunidad
{
	var $SQL;
	var $siginc;
	var $datemp;
	var $is_msg_error;
	
	function sigesp_spg_c_catunidad()
	{
		$this->siginc=new sigesp_include();
		$con=$this->siginc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->datemp=$_SESSION["la_empresa"];
	}

	function uf_cmb_estprog1()
	{
		$ls_codemp=$this->datemp["codemp"];
		$ls_cadena="SELECT * FROM spg_ep1 WHERE codemp='".$ls_codemp."'";
		
		$rs_est1=$this->SQL->select($ls_cadena);
		return $rs_est1;
	}
	
	function uf_cmb_estprog2($ls_est1)
	{
		$ls_codemp=$this->datemp["codemp"];
		$ls_cadena="SELECT * FROM spg_ep2 WHERE codemp='".$ls_codemp."' AND codestpro1='".$ls_est1."'";
		
		$rs_est2=$this->SQL->select($ls_cadena);
		return $rs_est2;
	}
	
	function uf_cmb_estprog3($ls_est1,$ls_est2)
	{
		$ls_codemp=$this->datemp["codemp"];
		$ls_cadena="SELECT * FROM spg_ep3 WHERE codemp='".$ls_codemp."' AND codestpro1='".$ls_est1."' AND codestpro2='".$ls_est2."'";
		
		$rs_est3=$this->SQL->select($ls_cadena);
		return $rs_est3;
	}
}
?>
