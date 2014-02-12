<?php
class sigesp_spg_c_comp
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;	
	var $io_msg;
	var $io_function;
	var $io_int_scg;
	var $io_int_spg;// calse integradoras
	
	function sigesp_spg_c_comp()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_fecha.php");	
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_c_seguridad.php");
		
		////---------clases integradoras------------------------------------------
		require_once("../../shared/class_folder/class_sigesp_int.php");	
		require_once("../../shared/class_folder/class_sigesp_int_int.php");	
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
		require_once("../../shared/class_folder/class_sigesp_int_spg.php");
		///-----------------------------------------------------------------------
		$this->io_function=new class_funciones();
		$this->io_fecha=new class_fecha();
		$this->io_include=new sigesp_include();	
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);
		$this->io_msg = new class_mensajes();
	    $this->io_int_scg=new class_sigesp_int_scg();
		$this->io_int_spg=new class_sigesp_int_spg();	
		$this->is_msg_error="";
	}// fin de sigesp_spg_c_comp
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function uf_load_dt_comprobante($as_codemp,$as_comprobante,$adt_fecha,$as_procede,$as_codban,$as_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_comprobante
		//		   Access: public
		//		 Argument: 
		//	  Description: Función que busca los comprobantes
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 03/12/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_sql="SELECT DISTINCT DT.codestpro1 as codest1,".
				"                DT.codestpro2 as codest2,".
				"                DT.codestpro3 as codest3,".
				"                DT.codestpro4 as codest4,".
				"                DT.codestpro5 as codest5,".
				"                DT.estcla as estcla,".
				"                DT.spg_cuenta as spg_cuenta,".
				"                C.sc_cuenta as scg_cuenta,".
				"                C.denominacion as denominacion, ".
				"          	     DT.procede_doc as procede_doc,  ".
				"                P.desproc as desproc,           ".
				"                DT.documento as documento,      ".
				"                DT.operacion as operacion,".
				"                DT.descripcion as descripcion,".
				"                DT.monto as monto,".
				"                DT.orden as orden, ".
				"                OP.denominacion as denominacion".
				" FROM sigesp_cmp CMP,spg_dt_cmp DT,spg_cuentas C, sigesp_procedencias P,spg_operaciones OP ".
				"WHERE DT.codemp='".$as_codemp."'  ".
				"  AND DT.procede='".$as_procede."'".
				"  AND DT.comprobante='".$as_comprobante."' ".
				"  AND DT.fecha='".$ld_fecha."' ".
				"  AND DT.codban='".$as_codban."' ".
				"  AND DT.ctaban='".$as_ctaban."' ".
				"  AND CMP.codemp=DT.codemp".
				"  AND CMP.procede=DT.procede".
				"  AND CMP.comprobante=DT.comprobante".
				"  AND CMP.fecha=DT.fecha            ".
				"  AND CMP.codban=DT.codban".
				"  AND CMP.ctaban=DT.ctaban".
				"  AND DT.procede=P.procede ".
				"  AND DT.codemp=C.codemp ".
				"  AND DT.spg_cuenta=C.spg_cuenta  ".
				"  AND OP.operacion = DT.operacion ".
				"  AND (DT.codestpro1=C.codestpro1 ".
				"  AND DT.codestpro2=C.codestpro2  ".
				"  AND DT.codestpro3=C.codestpro3  ".
				"  AND DT.codestpro4=C.codestpro4  ".
				"  AND DT.codestpro5=C.codestpro5 ".
				"  AND DT.estcla=C.estcla) ".
				"  ORDER BY DT.orden "; //print 	$ls_sql;		
		$rs_dt_cmp=$this->io_sql->select($ls_sql);
		if($rs_dt_cmp===false)
		{
			$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $rs_dt_cmp;
	}// fin de uf_load_comprobante()
//------------------------------------------------------------------------------------------------------------------------------------------------
/////-----------------------------------------------------------------------------------------------------------------------------------
    function uf_load_contable($as_codemp,$as_procede,$as_comprobante,$ld_fecha,$as_codban,$as_ctaban)
	{
	//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_contable
		//		   Access: public
		//		 Argument: 
		//	  Description: Función que busca los comprobantes
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 03/12/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		 $ld_fecha=$this->io_function->uf_convertirdatetobd($ld_fecha);
		 $rs_dt_scg=$this->io_int_scg->uf_scg_cargar_detalle_comprobante( $as_codemp, $as_procede,$as_comprobante, 
		                                                                  $ld_fecha,&$lds_detalle_cmp,$as_codban,$as_ctaban);
		 if($rs_dt_scg===false)
		 {
			$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_int_scg->io_sql->message));
		 }
		 return $rs_dt_scg;
	}// fin de uf_load_contable
//////--------------------------------------------------------------------------------------------------------------------------------------
}// fin de la clase sigesp_spg_c_comp
?>
