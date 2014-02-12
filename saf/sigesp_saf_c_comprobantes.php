<?php
class sigesp_saf_c_comprobantes
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_saf_c_comprobantes()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_datastore.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}//fin de la function sigesp_saf_c_metodos()
	
	function uf_saf_load_activomovimiento($as_codemp,$as_cmpmov)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_load_activomovimiento
		//         Access: public (sigesp_siv_d_activos)
		//      Argumento: $as_codemp //codigo de empresa 
		//				   $as_cmpmov //numero de comprbante de movimiento
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtiene los datos del activo que se refieren al banco y la cuenta con que se pago en la 
		//				   tabla saf_activo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 06/06/2006 								Fecha Última Modificación : 06/06/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = " SELECT saf_dt_movimiento.codact,saf_dt_movimiento.ideact,saf_dta.coduniadm,".
				  "        (SELECT denuniadm FROM spg_unidadadministrativa".
				  " 	     WHERE spg_unidadadministrativa.coduniadm=saf_dta.coduniadm)as denuniadm".
				  "  FROM saf_activo,saf_dt_movimiento,saf_dta  ".
				  " WHERE saf_dt_movimiento.codemp='".$as_codemp."' ".
				  "   AND saf_dt_movimiento.cmpmov='".$as_cmpmov."' ".
				  "   AND saf_dt_movimiento.codact=saf_dta.codact".
				  "   AND saf_dt_movimiento.ideact=saf_dta.ideact".
				  " GROUP BY saf_dta.coduniadm,saf_dt_movimiento.codact,saf_dt_movimiento.ideact";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->comprobantes MÉTODO->uf_saf_load_activomovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_numrows= $this->io_sql->num_rows($rs_data);
			if ($li_numrows>0)
			{
				$lb_valido= true;
			}
		}
		return $rs_data;
	}//fin de la function uf_saf_load_activomovimiento


}//fin de la class sigesp_saf_c_activosanexos
?>
