<?php 
class sigesp_scb_c_desprogpago
{
	var $SQL;
	var $fun;
	var $msg;
	var $is_msg_error;	
	var $dat;
	var $io_seguridad;
	var $is_empresa;
	var $is_sistema;
	var $is_logusr;
	var $is_ventanas;
	
	function sigesp_scb_c_desprogpago($aa_security)
	{
		require_once("class_funciones_banco.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
        require_once("../shared/class_folder/class_funciones.php");
		
		$this->io_seguridad= new sigesp_c_seguridad();
		$sig_inc=new sigesp_include();
		$con=$sig_inc->uf_conectar();
		$this->SQL=new class_sql($con);
		$this->fun=new class_funciones();
		$this->msg=new class_mensajes();
		$this->io_funscb = new class_funciones_banco();
		$this->dat=$_SESSION["la_empresa"];
		$this->is_empresa = $aa_security["empresa"];
		$this->is_sistema = $aa_security["sistema"];
		$this->is_logusr  = $aa_security["logusr"];	
		$this->is_ventana = $aa_security["ventanas"];
	}//Fin del constructor

	function uf_cargar_programaciones($ls_codemp,$as_tipproben)
	{
	  //////////////////////////////////////////////////////////////////////////////
	  //	Function:		uf_cargar_programaciones
	  // Access:			public
	  //	Returns:			Boolean Retorna si encontro o no errores en la consulta
	  //	Description:	Funcion que se encarga de llenar el datastore con los datos de
	  //						las programaciones para el proceso de cancelaciones
	  //                desprogramacion de pagos
	  //////////////////////////////////////////////////////////////////////////////
  
      if ($as_tipproben=='P')
	     {
		   $ls_tabla  = "rpc_proveedor";
		   $ls_campo  = "cod_pro";
		   $ls_sqlaux = ',rpc_proveedor.nompro as nomproben';
		   $ls_straux = "AND cxp_solicitudes.codemp = rpc_proveedor.codemp AND cxp_solicitudes.cod_pro = rpc_proveedor.cod_pro";
		 }
	  elseif($as_tipproben=='B')
		 {
		   $ls_tabla  = 'rpc_beneficiario';
		   $ls_sqlaux = ',rpc_beneficiario.nombene, rpc_beneficiario.apebene';
		   $ls_straux = "AND cxp_solicitudes.codemp = rpc_beneficiario.codemp AND cxp_solicitudes.ced_bene = rpc_beneficiario.ced_bene";
		 }
	  
	  $ls_sql = "SELECT cxp_solicitudes.numsol as numsol,
		                cxp_solicitudes.cod_pro as codproben,
						cxp_solicitudes.consol as consol,
		                cxp_solicitudes.estprosol as estprosol,
						cxp_solicitudes.monsol as monsol,
						cxp_solicitudes.obssol as obssol,
						scb_prog_pago.fecpropag as fecpropag,
						scb_prog_pago.codban as codban,
						scb_prog_pago.ctaban as ctaban,
						scb_banco.nomban as nomban,
						scb_ctabanco.dencta as dencta $ls_sqlaux,
					   (SELECT count(cxp_rd_spg.spg_cuenta) 
						  FROM cxp_rd_spg, cxp_rd, cxp_dt_solicitudes
						 WHERE cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
						   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
						   AND cxp_dt_solicitudes.codemp=cxp_rd_spg.codemp
						   AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc
						   AND cxp_dt_solicitudes.codtipdoc=cxp_rd_spg.codtipdoc
						   AND cxp_dt_solicitudes.cod_pro=cxp_rd_spg.cod_pro
						   AND cxp_dt_solicitudes.ced_bene=cxp_rd_spg.ced_bene
						   AND cxp_rd.codemp=cxp_rd_spg.codemp
						   AND cxp_rd.numrecdoc=cxp_rd_spg.numrecdoc
						   AND cxp_rd.codtipdoc=cxp_rd_spg.codtipdoc
						   AND cxp_rd.cod_pro=cxp_rd_spg.cod_pro
						   AND cxp_rd.ced_bene=cxp_rd_spg.ced_bene) as detspg,(SELECT CAST(estcon as char)||CAST(estpre as char) FROM cxp_documento WHERE cxp_documento.codtipdoc=cxp_dt_solicitudes.codtipdoc) as estcodtipdoc
				   FROM cxp_solicitudes,cxp_dt_solicitudes, $ls_tabla, scb_prog_pago, scb_banco, scb_ctabanco
				  WHERE cxp_solicitudes.codemp = '".$ls_codemp."'
					AND cxp_solicitudes.tipproben = '".$as_tipproben."'
					AND cxp_solicitudes.estprosol = 'S'
					AND scb_prog_pago.estmov = 'P'
					AND cxp_solicitudes.numsol = scb_prog_pago.numsol $ls_straux					
					AND scb_prog_pago.codban = scb_banco.codban
					AND scb_prog_pago.codban = scb_ctabanco.codban
					AND scb_prog_pago.ctaban = scb_ctabanco.ctaban					
					AND cxp_solicitudes.codemp = scb_prog_pago.codemp
					AND cxp_solicitudes.codemp = scb_banco.codemp
					AND cxp_solicitudes.codemp = scb_ctabanco.codemp
					AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
					AND cxp_solicitudes.numsol NOT IN (SELECT numsol FROM cxp_sol_banco WHERE estmov<>'A' AND estmov<>'O')
					AND scb_prog_pago.ctaban IN (SELECT codintper ".
							"					 FROM sss_permisos_internos ".
							"				    WHERE codusu='".$_SESSION["la_logusr"]."' ".
							"				    UNION ".
							"				   SELECT codintper ".
							"				     FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos ".
							"					WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
				  ORDER BY cxp_solicitudes.numsol ASC";
	  $rs_data = $this->SQL->select($ls_sql);
	  if ($rs_data===false)
	     {
		   return false;
		   $this->is_msg_error="Error;CLASS=sigesp_scb_c_desprogpago.php;FUNCION=uf_cargar_programaciones();".$this->fun->uf_convertirmsg($this->SQL->message);
		 }
	  return $rs_data;
	}
	
	function uf_procesar_desprogramacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_procesar_desprogramacion
		// Access:			public
		//	Returns:			Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de realizar la desprogramacion de 
		//						los pagos a proveedores o beneficiarios
		//////////////////////////////////////////////////////////////////////////////
		
		$li_ds_total=0;$li_x=0;
		$lb_valido = true;
		$ls_codemp   = $this->dat["codemp"];
		$ls_codusu   = $_SESSION["la_logusr"];
		$this->is_msg_error="";
		$ls_sql="DELETE 
				   FROM  scb_prog_pago
			  	  WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND fecpropag='".$this->fun->uf_convertirdatetobd($ld_fecpropag)."'";
			
		$li_result=$this->SQL->execute($ls_sql);
		if ($li_result===false)
		   {
				$lb_valido=false;
				$this->is_msg_error="Error al desprogramar solicitud ".$ls_numsol.", ".$this->fun->uf_convertirdatetobd($this->SQL->message);
				print $this->is_msg_error;
		   }
		else
			{
			  $lb_valido=true;
			  ////////////////////////Seguridad///////////////////////////////////////////////////////////
			  $ls_evento="DELETE";
			  $ls_descripcion="Se Desprogramo la soliciutd ".$ls_numsol." perteneciente al Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Elimina en tabla scb_prog_pago)";
			  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			  ////////////////////////////////////////////////////////////////////////////////////////////
			}
			
		 if ($lb_valido)
			{
				
				$ls_sql="UPDATE cxp_solicitudes
						SET 	estprosol = 'C'
						WHERE   codemp	  = '".$ls_codemp."' AND numsol ='".$ls_numsol."'";
				$li_result=$this->SQL->execute($ls_sql);
				if(($li_result===false))
				{
					$lb_valido=false;
					$this->is_message_error="Error al actualizar estatus de solicitud ".$ls_numsol.", ".$this->fun->uf_convertirdatetobd($this->SQL->message);
					print $this->is_msg_error;
				}
				else
				{
				  $lb_valido=true;
				  ////////////////////////Seguridad///////////////////////////////////////////////////////////
				  $ls_evento="UPDATE";
				  $ls_descripcion="Se Desprogramo la soliciutd ".$ls_numsol." perteneciente al Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Actualiza estatus en tabla cxp_solicitudes)";
				  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				  ////////////////////////////////////////////////////////////////////////////////////////////
				}
				
				$ls_sql="DELETE FROM cxp_historico_solicitud
						 WHERE   codemp	  = '".$ls_codemp."' AND numsol ='".$ls_numsol."' AND fecha='".$this->fun->uf_convertirdatetobd($ld_fecpropag)."' AND  estprodoc='S'";
				$li_result=$this->SQL->execute($ls_sql);
				if(($li_result===false))
				{
					$lb_valido=false;
					$this->is_message_error="Error al actualizar estatus de solicitud ".$ls_numsol.", ".$this->fun->uf_convertirdatetobd($this->SQL->message);
					print $this->is_msg_error;
				}
				else
				{
				  $lb_valido=true;
				  ////////////////////////Seguridad///////////////////////////////////////////////////////////
				  $ls_evento="DELETE";
				  $ls_descripcion="Se elimino la programacion del historico para la soliciutd ".$ls_numsol;
				  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				  ////////////////////////////////////////////////////////////////////////////////////////////						
				}
			}
		return $lb_valido;
	}//Fin de uf_procesar_programacion
	
	
	function uf_validar_asignacion_estructura($as_numsol,$as_codproben,$as_tipproben)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//     Function:  uf_validar_asignacion_estructura
		//   Parametros:  $as_numsol    - Numero de Solicitud
		//				  $as_codproben - Codigo del proveedor/Benficiario
		// 				  $as_tipproben - Tipo de receptor del pago
		//  Observacion:  Funcion que valida que el usuario tenga asignada las estructuras presupuestarias 
		//				  del detalle presupuestario de la solicitud.
		//Desarrollador:  Ing. Nelson Barraez
		//	   Creacion:  22/07/2010   
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($as_tipproben=='P')
		{
			$ls_aux=" AND cxp_solicitudes.cod_pro='".$as_codproben."' ";
		}
		else
		{
			$ls_aux=" AND cxp_solicitudes.ced_bene='".$as_codproben."' ";
		}
		
		$ls_sql = "SELECT codestpro
				   FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd_spg
				  WHERE cxp_solicitudes.codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
					AND cxp_solicitudes.numsol = '".trim($as_numsol)."' ".$ls_aux. 
				  " AND cxp_solicitudes.codemp = cxp_dt_solicitudes.codemp 
					AND cxp_solicitudes.numsol = cxp_dt_solicitudes.numsol
					AND cxp_dt_solicitudes.numrecdoc=cxp_rd_spg.numrecdoc
					AND cxp_dt_solicitudes.codtipdoc=cxp_rd_spg.codtipdoc
					AND cxp_dt_solicitudes.cod_pro=cxp_rd_spg.cod_pro
					AND cxp_dt_solicitudes.ced_bene=cxp_rd_spg.ced_bene 
					AND codestpro||estcla IN (SELECT codintper
												FROM sss_permisos_internos
											   WHERE codusu='".$_SESSION["la_logusr"]."' AND codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
											  UNION
											  SELECT codintper
												FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos
											   WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
												 AND sss_permisos_internos_grupo.codemp = sss_usuarios_en_grupos.codemp AND sss_permisos_internos_grupo.codgru = sss_usuarios_en_grupos.codgru)";
	  $rs_data = $this->SQL->select($ls_sql);
	  if ($rs_data===false)
	  {
		   $this->msg->message("Error en uf_validar_asignacion_estructura ");	   
	  }
	  else
	  {
		   if ($row=$this->SQL->fetch_row($rs_data))
		   {
				$lb_valido=true;
		   }
	  }
	  return $lb_valido;
	}
}
?>