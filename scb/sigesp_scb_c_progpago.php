<?php 
class sigesp_scb_c_progpago
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
	
	function sigesp_scb_c_progpago($aa_security)
	{
		require_once("class_funciones_banco.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		
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
		$this->io_seguridad= new sigesp_c_seguridad();
	}//Fin del constructor

	function uf_cargar_solicitudes($ls_codemp,$ls_tipproben,$ai_tipvia)
	{
	  $li_estciespg = $this->io_funscb->uf_obtenervalor("hidestciespg",0);
	  $li_estciespi = $this->io_funscb->uf_obtenervalor("hidestciespi",0);
	  $li_estciescg = $this->io_funscb->uf_obtenervalor("hidestciescg",0);
	  		
	  $ls_cadaux = "";
	  if ($ls_tipproben=='P')
		 {
		   $ls_tabla   = 'rpc_proveedor ';
		   $ls_columna = 'nompro as nomproben';
		   $ls_campo   = 'cod_pro ';
		   $ls_aux     = 'AND rpc_proveedor.codemp=cxp_dt_solicitudes.codemp AND rpc_proveedor.cod_pro=cxp_dt_solicitudes.cod_pro';
		 }
	  elseif($ls_tipproben=='B')
		 {
		   $ls_tabla   = 'rpc_beneficiario';
		   $ls_columna = "nombene, rpc_beneficiario.apebene";
		   $ls_campo   = 'ced_bene ';
		   $ls_aux     = 'AND rpc_beneficiario.codemp=cxp_dt_solicitudes.codemp AND rpc_beneficiario.ced_bene=cxp_dt_solicitudes.ced_bene';
		   if ($ai_tipvia=='1')
			  {
			    $ls_cadaux = " AND cxp_rd.procede='SCVSOV' ";
			  } 
		   else
			  {
			    $ls_cadaux = " AND cxp_rd.procede<>'SCVSOV' "; 
			  } 
		 }																		   
	    $ls_sql = "SELECT DISTINCT cxp_solicitudes.numsol as numsol,
								 cxp_solicitudes.$ls_campo as codproben,
								 cxp_solicitudes.fecemisol as fecemisol,
								 cxp_solicitudes.tipproben as tipproben,
								 cxp_solicitudes.fecpagsol as fecpagsol,
								 cxp_solicitudes.consol as consol,
								 cxp_solicitudes.estprosol as estprosol,
								 cxp_solicitudes.monsol as monsol,
								 cxp_solicitudes.obssol as obssol,
								 $ls_tabla.$ls_columna,
								 cxp_rd.procede as procede,
								 cxp_solicitudes.numordpagmin,
								 cxp_solicitudes.codtipfon,
								 (SELECT CAST(estcon as char)||CAST(estpre as char) FROM cxp_documento WHERE cxp_documento.codtipdoc=cxp_rd.codtipdoc) as estcodtipdoc
					        FROM cxp_solicitudes, $ls_tabla, cxp_rd, cxp_dt_solicitudes
						   WHERE cxp_solicitudes.codemp='".$ls_codemp."'
						     AND cxp_solicitudes.tipproben='".$ls_tipproben."'
							 AND cxp_solicitudes.estprosol='C' $ls_cadaux
							 AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
							 AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol
							 AND cxp_rd.codemp=cxp_dt_solicitudes.codemp
							 AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc
							 AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc
							 AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro
							 AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene $ls_aux
							 AND cxp_solicitudes.numsol NOT IN (SELECT numsol
																  FROM scb_prog_pago
																 WHERE codemp='".$ls_codemp."'
																   AND numsol=cxp_solicitudes.numsol)
					        ORDER BY cxp_solicitudes.numsol ";	

	  $rs_data = $this->SQL->select($ls_sql);
	  if ($rs_data===false)
		 {
		   $lb_valido=false;
		   $this->is_msg_error="Error en metodo uf_cargar_solicitudes".$this->fun->uf_convertirmsg($this->SQL->message);
		 }
	  return $rs_data;
	}//Fin uf_cargar_solicitudes
	
	function uf_procesar_programacion($ls_numsol,$ld_fecpropag,$ls_estmov,$ls_codban,$ls_ctaban,$ls_provbene,$ls_tipo,$ai_tipvia)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:	uf_procesar_programacion
		// Access:		public
		//	Returns:	Boolean Retorna si proceso correctamente
		//	Description:	Funcion que se encarga de guardar el movimiento bien sea 
		//						insertando o actualizando
		//////////////////////////////////////////////////////////////////////////////
		
		$li_ds_total=0;$li_x=0;
		$ls_codemp   = $this->dat["codemp"];
	    $ls_codusu   = $_SESSION["la_logusr"];			 
		$this->is_msg_error="";
		$ls_sql="INSERT INTO scb_prog_pago(codemp,codban,ctaban,codusu,numsol,fecpropag,estmov,esttipvia)
				      VALUES('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_codusu."','".$ls_numsol."','".$this->fun->uf_convertirdatetobd($ld_fecpropag)."','P',".$ai_tipvia.")";
			
		$li_result = $this->SQL->execute($ls_sql);
		if (($li_result===false))
		   {
		     $this->is_msg_error = "Error al programar solicitud ".$ls_numsol.", ".$this->fun->uf_convertirmsg($this->SQL->message);
			 $lb_valido = false;
		   }
		else
		   {
		     $lb_valido=true;
		   	 ////////////////////////Seguridad///////////////////////////////////////////////////////////
			 $ls_evento="INSERT";
		 	 $ls_descripcion="Programo la solicitud  ".$ls_numsol." perteneciente al  Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Inserta en la table scb_prog_pago)";
			 $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
			 ////////////////////////////////////////////////////////////////////////////////////////////
		   }	
		if ($lb_valido)
		   {
		     $ls_sql = "UPDATE cxp_solicitudes SET estprosol = 'S' WHERE codemp='".$ls_codemp."' AND numsol='".$ls_numsol."'";
			 $li_result_upd=$this->SQL->execute($ls_sql);		
			 if (($li_result_upd===false))
				{
				  $this->is_msg_error   = "Error actualizando solicitud ".$ls_numsol.",".$this->fun->uf_convertirmsg($this->SQL->message);
				  $lb_valido = false;
				}
			 else
				{
				  $lb_valido=true;
				  /////////////////////////Seguridad//////////////////////////////////////////////////////////////////////////////
				  $ls_evento="UPDATE";
				  $ls_descripcion="Programo la solicitud  ".$ls_numsol." perteneciente al  Proveedor/Beneficiario ".$ls_provbene." para el banco ".$ls_codban." y la cuenta ".$ls_ctaban." (Actualiza estatus en tabla cxp_solicitudes)";
				  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				
				$ls_sql="INSERT INTO cxp_historico_solicitud(codemp, numsol, fecha, estprodoc)
						 VALUES('".$ls_codemp."','".$ls_numsol."','".$this->fun->uf_convertirdatetobd($ld_fecpropag)."','S')";
				
				$li_result_upd=$this->SQL->execute($ls_sql);		
				if(($li_result_upd===false))
				{
					$this->is_msg_error   = "Error actualizando solicitud ".$ls_numsol.",".$this->fun->uf_convertirmsg($this->SQL->message);
					$lb_valido      = false;					
				}
				else
				{
				  $lb_valido=true;
				  /////////////////////////Seguridad//////////////////////////////////////////////////////////////////////////////
				  $ls_evento="INSERT";
				  $ls_descripcion="Inserto la programacion en el historico para la solicitud ".$ls_numsol;
				  $lb_valido = $this->io_seguridad->uf_sss_insert_eventos_ventana($this->is_empresa,$this->is_sistema,$ls_evento,$this->is_logusr,$this->is_ventana,$ls_descripcion);
				  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
			}	
		return $lb_valido;
	}//Fin de uf_procesar_programacion
	
	function uf_load_notas_asociadas($as_codemp,$as_numsol,&$ai_montonotas)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_load_notas_asociadas
		//	          Access:  public
		//	        Arguments  as_codemp //  Código de la Empresa.
		//                     as_numsol //  Número de Identificación de la Solicitud de Pago.
		//                     ai_montonotas //  monto de las Notas de Débito y Crédito.
		//	         Returns:  lb_valido.
		//	     Description:  Función que se encarga de buscar las notas de debito y crédito asociadas a la solicitud de pago. 
		//     Elaborado Por:  Ing. Yesenia Moreno
		// Fecha de Creación:  26/09/2007       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=true;
		$ai_montonotas=0;
		$ls_sql= "SELECT SUM(CASE cxp_sol_dc.codope WHEN 'NC' THEN (-1*cxp_sol_dc.monto) ".
			   "                                 			ELSE (cxp_sol_dc.monto) END) as total ".
			   "  FROM cxp_dt_solicitudes, cxp_sol_dc ".
			   " WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
			   "   AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
			   "   AND cxp_sol_dc.estnotadc= 'C' ".
			   "   AND cxp_dt_solicitudes.codemp = cxp_sol_dc.codemp ".
			   "   AND cxp_dt_solicitudes.numsol = cxp_sol_dc.numsol ".
			   "   AND cxp_dt_solicitudes.numrecdoc = cxp_sol_dc.numrecdoc ".
			   "   AND cxp_dt_solicitudes.codtipdoc = cxp_sol_dc.codtipdoc ".
			   "   AND cxp_dt_solicitudes.ced_bene = cxp_sol_dc.ced_bene ".
			   "   AND cxp_dt_solicitudes.cod_pro = cxp_sol_dc.cod_pro";
		$rs_data=$this->SQL->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_load_notas_asociadas".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
				$ai_montonotas=$row["total"];
			}
		}
		return $lb_valido;
	}	
///----------------------------------------------------------------------------------------------------------------------------------------
///---------------------------------------------------------------------------------------------------------------------------------------
     function uf_buscar_detalles_pre($as_codemp, $as_numsol, $ls_tipproben)
	 {
	 //////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_buscar_detalles_pre
		//	          Access:  public
		//	        Arguments  
		//	         Returns:  numeros de detalles presupuestarios.
		//	     Description:  Función que se encarga de buscar los detalles presupuestarios asociados a la recepción de documento. 
		//     Elaborado Por:  Ing. Jennifer Rivero
		// Fecha de Creación:  22/10/2008       Fecha Última Actualización:
		////////////////////////////////////////////////////////////////////////////// 
	    $li_detspg=0;
		if ($ls_tipproben=='P')
		 {
		   $ls_tabla   = 'rpc_proveedor ';		  
		   $ls_aux     = 'AND rpc_proveedor.codemp=cxp_dt_solicitudes.codemp 
		                  AND rpc_proveedor.cod_pro=cxp_dt_solicitudes.cod_pro';
		 }
	  elseif($ls_tipproben=='B')
		 {
		   $ls_tabla   = 'rpc_beneficiario';		  
		   $ls_aux     = 'AND rpc_beneficiario.codemp=cxp_dt_solicitudes.codemp 
		                  AND rpc_beneficiario.ced_bene=cxp_dt_solicitudes.ced_bene';
         }
		$ls_sql= "  SELECT sum((SELECT DISTINCT count(spg_cuenta) 
					  FROM cxp_rd_spg 
					  WHERE cxp_rd_spg.codemp=cxp_rd.codemp 
						AND cxp_rd_spg.numrecdoc=cxp_rd.numrecdoc 
						AND cxp_rd_spg.codtipdoc=cxp_rd.codtipdoc 
						AND cxp_rd_spg.ced_bene=cxp_rd.ced_bene 
						AND cxp_rd_spg.cod_pro=cxp_rd.cod_pro)) as detspg 
				  from  cxp_solicitudes, $ls_tabla ,cxp_rd, cxp_dt_solicitudes 
				  WHERE cxp_solicitudes.codemp='".$as_codemp."' 
				  AND cxp_solicitudes.tipproben='P' 
				  AND cxp_solicitudes.estprosol='C' 
				  AND cxp_solicitudes.numsol='".$as_numsol."'
				  AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp 
				  AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol 
				  AND cxp_rd.codemp=cxp_dt_solicitudes.codemp 
				  AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc 
				  AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc 
				  AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro 
				  AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene $ls_aux				 
				  AND cxp_solicitudes.numsol NOT IN (SELECT numsol 
				                                       FROM scb_prog_pago 
				                                      WHERE codemp='".$as_codemp."' 
													    AND numsol=cxp_solicitudes.numsol)";				 
						
		$rs_data=$this->SQL->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en metodo uf_buscar_detalles_pre".$this->fun->uf_convertirmsg($this->SQL->message);
		}
		else
		{
			if($row=$this->SQL->fetch_row($rs_data))
			{
				 $li_detspg=$row["detspg"];
			}
		}
		return  $li_detspg;		
	 }// fin de uf_buscar_detalles_pre
///---------------------------------------------------------------------------------------------------------------------------------------

function uf_load_datos_orden_pago($as_numordpagmin,$as_codtipfon,&$ls_ctaban,&$ls_nomban,&$ls_denctaban)
{
  $ls_sql = "SELECT scb_movbco.codban,scb_movbco.ctaban, scb_banco.nomban, scb_ctabanco.dencta
			   FROM scb_movbco, scb_banco, scb_ctabanco
			  WHERE scb_movbco.codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
			    AND trim(scb_movbco.numordpagmin) = '".trim($as_numordpagmin)."' 
			    AND scb_movbco.codtipfon = '".$as_codtipfon."' 
			    AND (scb_movbco.codope = 'NC' OR scb_movbco.codope = 'DP')
			    AND scb_movbco.codemp=scb_banco.codemp
			    AND scb_movbco.codban=scb_banco.codban
			    AND scb_movbco.codemp=scb_ctabanco.codemp
			    AND scb_movbco.codban=scb_ctabanco.codban
			    AND scb_movbco.ctaban=scb_ctabanco.ctaban;";
  $rs_data = $this->SQL->select($ls_sql);//echo $ls_sql.'<br>';
  if ($rs_data===false)
     {
	   $lb_valido = false;
	 }
  else
     {
	   if ($row=$this->SQL->fetch_row($rs_data))
		  {
		    $ls_codban    = $row["codban"];
			$ls_ctaban    = $row["ctaban"];
			$ls_nomban    = $row["nomban"];
			$ls_denctaban = $row["dencta"];
		  }
	 }
  return $ls_codban;
}

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

}// fin de la clase 
?>