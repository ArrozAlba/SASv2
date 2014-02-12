<?php
class sigesp_spg_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_spg_class_report($as_path="../../../")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sep_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->ds_detalle=new class_datastore();
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sep_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_casamientos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_casamientos
		//         Access: public (sigesp_sep_p_solicitud)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Funcion que busca los casamientos contables/presupuestarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/11/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;				
		$ls_sql="SELECT scg_casa_presu.sig_cuenta , scg_casa_presu.sc_cuenta, sigesp_plan_unico_re.denominacion as spgdenominacion,".
				"       scg_cuentas.denominacion as scgdenominacion".
				"  FROM scg_casa_presu,sigesp_plan_unico_re,scg_cuentas".
				" WHERE scg_casa_presu.codemp='".$this->ls_codemp."'".
				"   AND scg_casa_presu.codemp=scg_cuentas.codemp".
				"   AND scg_casa_presu.sc_cuenta=scg_cuentas.sc_cuenta".
				"   AND trim(scg_casa_presu.sig_cuenta)=trim(sigesp_plan_unico_re.sig_cuenta)".
				" ORDER BY scg_casa_presu.sig_cuenta"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{		
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_casamientos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{   
				$lb_valido=false;			     
			}	
		}
		return $lb_valido;		
	}// end function uf_select_casamientos
	//-----------------------------------------------------------------------------------------------------------------------------------

}
?>