<?php
class sigesp_scb_c_cmp_retencion
{
	function sigesp_scb_c_cmp_retencion($as_path)
	{
	  require_once($as_path."shared/class_folder/class_sql.php");
	  require_once($as_path."shared/class_folder/class_fecha.php");
	  require_once($as_path."shared/class_folder/class_mensajes.php");
      require_once($as_path."shared/class_folder/sigesp_include.php");
	  require_once($as_path."shared/class_folder/class_funciones.php");
	  require_once($as_path."shared/class_folder/class_datastore.php");
      require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
	  	  
	  $io_include 		  = new sigesp_include();
	  $ls_connect         = $io_include->uf_conectar();
	  $this->io_sql       = new class_sql($ls_connect);	
	  $this->io_function  = new class_funciones();
	  $this->io_msg       = new class_mensajes();
	  $this->io_fecha     = new class_fecha();
	  $this->ls_codusu    = $_SESSION["la_logusr"];
	  $this->io_seguridad = new sigesp_c_seguridad();
    }

function uf_procesar_comprobante_retencion($as_mes,$as_agno,$as_probendesde,$as_probenhasta,$as_tipproben,&$aa_numcmp,$aa_seguridad)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_procesar_comprobante_retencion
  //		   Access: private
  //	    Arguments: $as_mes  = Mes en curso.
  //                   $as_agno = Año curso.
  //                   $as_probendesde = Proveedor/Beneficiario a partir del cual se procesaran los comprobantes.
  //                   $as_probenhasta
  //                   $as_tipproben = Determinar si la operacion se realiza sobre un proveedor o beneficiario.
  //                   &$aa_numcmp = Número del comprobante generado.
  //                   $aa_seguridad = Arreglo de seguridad cargado con la informacion de la ventana, operacion,etc.
  //	      Returns: $li_numcmp = Número del comprobante.
  //	  Description: Función que agrupa una serie de métodos para la creacion de comprobante de retenciones iva.
  //	   Creado Por: Ing. Nestor Falcón.
  // Fecha Creación: 24/06/2008 								Fecha Última Modificación : 24/06/2008
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $aa_numcmp = array();
  $li_numcmp = $li_totrowpro = 0;
  $ls_fecact = date('Y-m-d');
  $ls_perfis = $as_agno.$as_mes;
  $lb_valido = false;
  $rs_data   = $this->uf_load_proveedores_beneficiarios($as_mes,$as_agno,$as_probendesde,$as_probenhasta,$as_tipproben);
  $li_numrow = $this->io_sql->num_rows($rs_data);
  if ($li_numrow>0)
     {
	   $this->io_sql->begin_transaction();
	   while($row=$this->io_sql->fetch_row($rs_data))
	        {
			  $ls_codpro = $row["cod_pro"];
			  $this->uf_get_documento($as_mes,$as_agno,$ls_codpro,$as_tipproben,&$la_recdoc);
			  $li_totrows = count($la_recdoc["numrecdoc"]);
			  if ($li_totrows>0)
			     {
				   $this->uf_get_nrocomprobante($ls_perfis,&$ls_nrocomp);
				   $ls_nompro = $row["nompro"];
				   $ls_rifpro = $row["rifpro"];
				   $ls_dirpro = $row["dirpro"];
				   $lb_valido = $this->uf_crear_comprobante($ls_nrocomp,$ls_fecact,$ls_perfis,$ls_codpro,$ls_nompro,$ls_dirpro,$ls_rifpro,$aa_seguridad);
				   if ($lb_valido)
				      {
					    $li_numcmp++;
					    $aa_numcmp [$li_numcmp] = $ls_nrocomp;
						for ($li_i=1;$li_i<=$li_totrows;$li_i++)
						    {
							  $ls_numrecdoc = $la_recdoc["numrecdoc"][$li_i];
							  $ls_codtipdoc = $la_recdoc["codtipdoc"][$li_i];
							  $ls_fecha     = $la_recdoc["fecemidoc"][$li_i];
							  $ls_basimpiva = $la_recdoc["basimpiva"][$li_i];
							  $ls_totconiva = $la_recdoc["totconiva"][$li_i];
							  $ls_monobjret = $la_recdoc["monobjret"][$li_i];
							  $ls_porcar    = $la_recdoc["porcar"][$li_i];
							  $ls_totiva    = $la_recdoc["totiva"][$li_i];
							  $ls_ivaret    = $la_recdoc["ivaret"][$li_i];
							  $ls_numsop    = $la_recdoc["numsop"][$li_i];
							  $ls_codded    = $la_recdoc["codded"][$li_i];
							  $ls_numref    = $la_recdoc["numref"][$li_i];
							  $ls_numope    = str_pad($li_i,10,0,0);
							  $ls_totsiniva = $ls_totconiva-$ls_basimpiva;
							  $lb_valido    = $this->uf_guardar_detallecmp($ls_nrocomp,$ls_numope,$ls_fecha,$ls_numrecdoc,$ls_numref,'','',"01-reg",$ls_totsiniva,$ls_totconiva,$ls_monobjret,$ls_porcar,$ls_totiva,$ls_ivaret,"",$ls_numsop,"","",$ls_numrecdoc,"01");
							  if ($lb_valido)
							     {
								   $lb_valido=$this->uf_actualizar_estcmp($ls_numrecdoc,$ls_codpro,$as_tipproben,$ls_codded,$ls_codtipdoc);
								 }
						    }
					  }
			     }	 
			  else
				 {
				   $this->io_msg->message("No existen documentos válidos para realizar el proceso !!!");
				 }
	        }
	 }
  if (($lb_valido)&&($li_numcmp>0))
	 {
	   $this->io_sql->commit();
	 }
  else
	 {
	   $this->io_sql->rollback();
	   $li_numcmp=0;
	 }
  return $li_numcmp;
}

function uf_load_proveedores_beneficiarios($as_mes,$as_agno,$as_probendesde,$as_probenhasta,$as_tipproben)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_proveedores_beneficiarios
	//		   Access: private
	//	    Arguments: 
	//	      Returns: 
	//	  Description: Función que carga un listado de los Proveedores/Beneficiarios pendientes por generar comprobantes 
	//                 de retención de Impuestos al Valor Agregado para un periodo.
	//	   Creado Por: Ing. Nestor Falcón.
	// Fecha Creación: 24/06/2008 								Fecha Última Modificación : 24/06/2008
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ld_fecdes = $this->io_function->uf_convertirdatetobd("01/".$as_mes."/".$as_agno);
	 $ld_hasta  = $this->io_fecha->uf_last_day($as_mes,$as_agno);
	 $ld_fechas = $this->io_function->uf_convertirdatetobd($ld_hasta);
     $ls_selaux = $ls_tabla = $ls_sqlaux = "";
	 
     if ($as_tipproben=='P')
	    {
		  $ls_group  = "cod_pro";
		  $ls_tabla  = ", rpc_proveedor";
		  if (!empty($as_probendesde) && !empty($as_probenhasta))
		     {
			   $ls_sqlaux = " AND (cxp_rd.cod_pro BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."')";
			 }
		  $ls_sqlaux = $ls_sqlaux." AND cxp_rd.codemp=rpc_proveedor.codemp 
								    AND cxp_rd.cod_pro=rpc_proveedor.cod_pro 
								    AND cxp_rd_deducciones.codemp=rpc_proveedor.codemp
								    AND cxp_rd_deducciones.cod_pro=rpc_proveedor.cod_pro					 
								    AND cxp_rd.codemp=cxp_dt_solicitudes.codemp 
								    AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro";
		  $ls_selaux = "cxp_rd.cod_pro as cod_pro, max(rpc_proveedor.nompro) as nompro, max(rpc_proveedor.dirpro) as dirpro, max(rpc_proveedor.rifpro) as rifpro";
		}
     elseif($as_tipproben=='B')
	    {
		  $ls_group  = "ced_bene";
		  $ls_tabla  = ", rpc_beneficiario";
		  if (!empty($as_probendesde) && !empty($as_probenhasta))
		     {
			   $ls_sqlaux = " AND (cxp_rd.ced_bene BETWEEN '".$as_probendesde."' AND '".$as_probenhasta."')";
			 }
		  $ls_sqlaux = $ls_sqlaux." AND cxp_rd.codemp=rpc_beneficiario.codemp 
								    AND cxp_rd.ced_bene=rpc_beneficiario.ced_bene 
								    AND cxp_rd_deducciones.codemp=rpc_beneficiario.codemp
								    AND cxp_rd_deducciones.ced_bene=rpc_beneficiario.ced_bene					 
								    AND cxp_rd.codemp=cxp_dt_solicitudes.codemp 
								    AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene";
		  $ls_selaux = " cxp_rd.ced_bene as cod_pro, MAX(rpc_beneficiario.dirbene) as dirpro, MAX(rpc_beneficiario.rifben) as rifpro";
		  switch ($_SESSION["ls_gestor"])
				 {
					case "MYSQLT":
						$ls_selaux = $ls_selaux." ,CONCAT(rpc_beneficiario.nombene,rpc_beneficiario.apebene) AS nompro ";
						break;
					case "POSTGRES":
						$ls_selaux = $ls_selaux." ,(MAX(rpc_beneficiario.nombene)||MAX(rpc_beneficiario.apebene)) AS nompro ";
						break;
					case "INFORMIX":
						$ls_selaux = $ls_selaux." ,(MAX(rpc_beneficiario.nombene)||MAX(rpc_beneficiario.apebene)) AS nompro ";
						break;
				 }
		}
     $ls_sql = "SELECT $ls_selaux 
	              FROM cxp_solicitudes, cxp_dt_solicitudes, cxp_rd, cxp_rd_deducciones, 
				       sigesp_deducciones, cxp_sol_banco, scb_movbco $ls_tabla
				 WHERE cxp_rd.codemp = '".$_SESSION["la_empresa"]["codemp"]."'
				   AND scb_movbco.estmov = 'C'
				   AND ((scb_movbco.codope = 'CH' AND scb_movbco.estbpd='P') OR (scb_movbco.codope = 'ND' AND scb_movbco.numcarord<>''))
				   AND (cxp_solicitudes.fecaprosol BETWEEN '".$ld_fecdes."' AND '".$ld_fechas."')
				   AND sigesp_deducciones.iva=1
				   AND sigesp_deducciones.islr=0
				   AND sigesp_deducciones.estretmun=0
				   AND sigesp_deducciones.otras=0 $ls_sqlaux
				   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp
				   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol				   
				   AND cxp_dt_solicitudes.codemp=cxp_rd_deducciones.codemp
				   AND cxp_dt_solicitudes.numrecdoc=cxp_rd_deducciones.numrecdoc
				   AND cxp_dt_solicitudes.codtipdoc=cxp_rd_deducciones.codtipdoc
				   AND cxp_dt_solicitudes.cod_pro=cxp_rd_deducciones.cod_pro
				   AND cxp_dt_solicitudes.ced_bene=cxp_rd_deducciones.ced_bene				   
				   AND cxp_rd.codemp=cxp_rd_deducciones.codemp
				   AND cxp_rd.numrecdoc=cxp_rd_deducciones.numrecdoc
				   AND cxp_rd.codtipdoc=cxp_rd_deducciones.codtipdoc
				   AND cxp_rd.cod_pro=cxp_rd_deducciones.cod_pro
				   AND cxp_rd.ced_bene=cxp_rd_deducciones.ced_bene
				   AND cxp_rd_deducciones.codemp=sigesp_deducciones.codemp
				   AND cxp_rd_deducciones.codded=sigesp_deducciones.codded
				   AND cxp_sol_banco.codemp=cxp_solicitudes.codemp
				   AND cxp_sol_banco.numsol=cxp_solicitudes.numsol
				   AND cxp_sol_banco.codemp=cxp_dt_solicitudes.codemp
				   AND cxp_sol_banco.numsol=cxp_dt_solicitudes.numsol				   
				   AND cxp_sol_banco.codemp=scb_movbco.codemp
				   AND cxp_sol_banco.codban=scb_movbco.codban
				   AND cxp_sol_banco.ctaban=scb_movbco.ctaban				   
				   AND cxp_sol_banco.estmov=scb_movbco.estmov
				   AND cxp_sol_banco.codope=scb_movbco.codope
				 GROUP BY cxp_rd.$ls_group";
	 $rs_data = $this->io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
	      $lb_valido=false;
		  $this->io_msg->message("CLASE->sigesp_scb_c_cmp_retencion.php;MÉTODO->uf_load_proveedores_beneficiarios;ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	      echo $this->io_sql->message; 
		}		 
	 return $rs_data;
}

function uf_get_documento($as_mes,$as_agno,$as_codprobene,$as_tipproben,&$as_recdoc)
{
   //////////////////////////////////////////////////////////////////////////////
   //	Function: uf_get_documento
   //	 Access: public
   //	 Argument: $as_mes // Mes  | $as_agno // Año
   //              $as_codpro // Codigo del proveedor o beneficiaro
   //              $as_tipproben // Indica si se trabaja con proveedores o beneficiarios | $as_tiporet // Indica el tipo de retencion
   //  Description:   
   //	Creado Por: Ing. Gerardo Cordero
   //  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
   //////////////////////////////////////////////////////////////////////////////
	$ld_fecdesde= $this->io_function->uf_convertirdatetobd("01/".$as_mes."/".$as_agno);
	$ld_hasta   = $this->io_fecha->uf_last_day($as_mes,$as_agno);
	$ld_fechasta= $this->io_function->uf_convertirdatetobd($ld_hasta);
	$lb_valido  = true;
	switch ($_SESSION["ls_gestor"])
	{
		case "MYSQLT":
			$ls_id="CONCAT(RD.numrecdoc,RDC.porcar)";
			break;
		case "POSTGRES":
			$ls_id="RD.numrecdoc||RDC.porcar";
			break;
		case "INFORMIX":
			$ls_id="RD.numrecdoc||RDC.porcar";
			break;
	}
    if ($as_tipproben=='P')
	   {
		$ls_sql = " SELECT ".$ls_id." as id, RD.numrecdoc, RD.codtipdoc, RD.fecemidoc, MAX(RDC.monobjret+RDC.monret) AS basimpiva,
				           MAX(RD.montotdoc+RD.mondeddoc) AS totconiva, MAX(RDC.monobjret) AS monobjret, MAX(RDC.porcar) AS porcar,
						   MAX(RDC.monret) AS totiva, MAX(RDD.monret) AS ivaret, DS.numsol as numsop, RDD.codded,
						   MAX(RDD.sc_cuenta) as cuenta, RD.numref
				      FROM sigesp_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,cxp_rd_cargos RDC ,cxp_dt_solicitudes DS,
					       cxp_solicitudes SO, cxp_sol_banco, scb_movbco
				     WHERE SD.codemp='".$_SESSION["la_empresa"]["codemp"]."'
				       AND SD.iva=1
					   AND SD.islr=0
					   AND SD.estretmun=0
					   AND SD.otras=0
					   AND RDD.estcmp='0'
					   AND RDD.cod_pro='".$as_codprobene."' 
					   AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') 
					   AND scb_movbco.estmov='C'					   
				       AND ((scb_movbco.codope = 'CH' AND scb_movbco.estbpd='P') OR (scb_movbco.codope = 'ND' AND scb_movbco.numcarord<>''))					   
					   AND SD.codemp=RDD.codemp
					   AND SD.codemp=RD.codemp 
					   AND SD.codemp=RDC.codemp
					   AND SD.codemp=DS.codemp
					   AND SD.codemp=SO.codemp					   
					   AND SD.codded=RDD.codded					   
				       AND RDD.numrecdoc=RD.numrecdoc
					   AND RDC.numrecdoc=RD.numrecdoc
					   AND RDD.codtipdoc=RD.codtipdoc				       
					   AND RDD.cod_pro=RD.cod_pro
					   AND RDC.cod_pro=RD.cod_pro
				  	   AND RD.numrecdoc=DS.numrecdoc
					   AND RD.cod_pro=DS.cod_pro
					   AND DS.numsol=SO.numsol					   
					   AND cxp_sol_banco.codemp=SO.codemp
					   AND cxp_sol_banco.numsol=SO.numsol				   
					   AND cxp_sol_banco.codemp=DS.codemp
					   AND cxp_sol_banco.numsol=DS.numsol					   
					   AND cxp_sol_banco.codemp=scb_movbco.codemp
					   AND cxp_sol_banco.codban=scb_movbco.codban
					   AND cxp_sol_banco.ctaban=scb_movbco.ctaban				   
					   AND cxp_sol_banco.estmov=scb_movbco.estmov
					   AND cxp_sol_banco.codope=scb_movbco.codope					   
				     GROUP BY id,RD.numrecdoc,RD.codtipdoc,RD.fecemidoc,numsop,RDD.codded,RD.numref,porcar";
	   }
	   else
	   {
		$ls_sql = " SELECT ".$ls_id." as id, RD.numrecdoc, RD.codtipdoc, RD.fecemidoc, MAX(RDC.monobjret+RDC.monret) AS basimpiva,
				           MAX(RD.montotdoc+RD.mondeddoc) AS totconiva, MAX(RDC.monobjret) AS monobjret,
						   MAX(RDC.porcar) AS porcar, MAX(RDC.monret) AS totiva, MAX(RDD.monret) AS ivaret,
						   DS.numsol as numsop, RDD.codded, MAX(RDD.sc_cuenta) as cuenta, RD.numref
				      FROM sigesp_deducciones SD,cxp_rd_deducciones RDD,cxp_rd RD,cxp_rd_cargos RDC,
					       cxp_dt_solicitudes DS,cxp_solicitudes SO, cxp_sol_banco, scb_movbco
				     WHERE SD.codemp='".$this->la_empresa["codemp"]."' 
					   AND SD.iva=1
					   AND SD.islr=0
					   AND SD.estretmun=0
					   AND SD.otras=0				   
					   AND RDD.estcmp='0'
				       AND RDD.ced_bene='".$as_codprobene."'
				       AND (SO.fecaprosol BETWEEN '".$ld_fecdesde."' AND '".$ld_fechasta."') 
					   AND scb_movbco.estmov='C'					   
				       AND ((scb_movbco.codope = 'CH' AND scb_movbco.estbpd='P') OR (scb_movbco.codope = 'ND' AND scb_movbco.numcarord<>''))					   
				       AND RDD.ced_bene=RD.ced_bene
					   AND RDC.ced_bene=RD.ced_bene
				       AND RDD.numrecdoc=RD.numrecdoc
				       AND RDC.numrecdoc=RD.numrecdoc
					   AND RDD.codtipdoc=RD.codtipdoc
				       AND SD.codded=RDD.codded
					   AND SD.codemp=RDD.codemp
					   AND SD.codemp=RD.codemp
					   AND SD.codemp=RDC.codemp
					   AND SD.codemp=DS.codemp
					   AND SD.codemp=SO.codemp
					   AND RD.numrecdoc=DS.numrecdoc
					   AND RD.cod_pro=DS.cod_pro
					   AND DS.numsol=SO.numsol
					   AND cxp_sol_banco.codemp=SO.codemp
					   AND cxp_sol_banco.numsol=SO.numsol				   
					   AND cxp_sol_banco.codemp=DS.codemp
					   AND cxp_sol_banco.numsol=DS.numsol					   
					   AND cxp_sol_banco.codemp=scb_movbco.codemp
					   AND cxp_sol_banco.codban=scb_movbco.codban
					   AND cxp_sol_banco.ctaban=scb_movbco.ctaban				   
					   AND cxp_sol_banco.estmov=scb_movbco.estmov
					   AND cxp_sol_banco.codope=scb_movbco.codope
				     GROUP by id,RD.numrecdoc,RD.codtipdoc,RD.fecemidoc,numsop,RDD.codded,RD.numref,porcar";
	   }
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	   {
		 $lb_valido=false;	
		 $this->io_msg->message("CLASE->sigesp_scb_c_cmp_retencion;MÉTODO->uf_get_documento ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		 echo $this->io_sql->message;
	   }
	else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
		    {
			  $as_recdoc = $this->io_sql->obtener_datos($rs_data);
	 	    }
		 else
		    {
		      $lb_valido=false;
		    } 
		 $this->io_sql->free_result($rs_data);
	   }
	return $lb_valido;	
}

function uf_get_nrocomprobante($as_periodofiscal,&$as_nrocomp)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_get_nrocomprobante
	//		   Access: public
	//		 Argument: $as_periodofiscal // Perido fiscal YYYYMM 
	//                 $as_nrocomp // Numero del Comprobante generado
	//	  Description: Función que genera el numero del comprobante
	//	   Creado Por: Ing. Gerardo Cordero
	// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
	//////////////////////////////////////////////////////////////////////////////
	$this->ds_numcmp= new class_datastore();
	$ls_sql=" SELECT numcom ".
			"   FROM scb_cmp_ret".
			"  WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'".
			"    AND codret='0000000001'".
			"  ORDER by numcom desc ";
	$rs_result=$this->io_sql->select($ls_sql);		
	if($rs_result===false)
	{
		$this->io_msg->message("CLASE->sigesp_scb_c_cmp_retencion;MÉTODO->uf_get_nrocomprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		return false;			
	}
	else
	{
		$li_i=0;
		while($row=$this->io_sql->fetch_row($rs_result))
		{
			$li_i=$li_i+1;
			$codigo =$row["numcom"];				   
			$codigo =substr($codigo,6,9);			      			   		   
			$this->ds_numcmp->insertRow("codigo",$codigo);
		}
		if($li_i>0)
		{
			$this->ds_numcmp->sortData("codigo");
			$ls_codigo=$this->ds_numcmp->getValue("codigo",$li_i);
			settype($ls_codigo,'int');
			$li_newcodigo =$ls_codigo + 1;                             
			settype($li_newcodigo,'string');  
			$ls_nrocomp=$this->io_function->uf_cerosizquierda($li_newcodigo,8);
			$as_nrocomp=$as_periodofiscal.$ls_nrocomp;
			$this->io_sql->free_result($rs_result);
			return true;
		}
		else
		{
		   $codigo=$this->uf_load_numeroinicial();
		   $as_nrocomp=$this->io_function->uf_cerosizquierda($codigo,8);
		   $this->io_sql->free_result($rs_result);
		   $as_nrocomp=$as_periodofiscal.$as_nrocomp;
		   return true;
		}							
	}			
}	

function uf_guardar_detallecmp($as_numcom,$as_numope,$as_fecfac,$as_numfac,$as_numcon,$as_numnd,$as_numnc,$as_tiptrans,$as_tot_cmp_sin_iva,$as_tot_cmp_con_iva,$as_basimp,$as_porimp,$as_totimp,$as_ivaret,$as_desope,$as_numsop,$as_codban,$as_ctaban,$as_numdoc,$as_codope)
{
	//////////////////////////////////////////////////////////////////////////////
	//	      Function: uf_guardar_detallecmp
	//	        Access: public
	//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
	//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
	//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
	//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
	//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
	//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
	//     Description: Función que guarda la cabecera de un comprobante de retencion  
	//	    Creado Por: Ing. Gerardo Cordero
	//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql = " INSERT INTO scb_dt_cmp_ret (codemp,codret,numcom,numope,fecfac,numfac,numcon,numnd,numnc,tiptrans,".
			  "                             totcmp_sin_iva,totcmp_con_iva,basimp,porimp,totimp,iva_ret,desope,". 
			  "                              numsop,codban,ctaban,numdoc,codope) ".
			  " VALUES  ('".$_SESSION["la_empresa"]["codemp"]."','0000000001','".$as_numcom."','".$as_numope."',".
			  "          '".$as_fecfac."','".$as_numfac."','".$as_numcon."','".$as_numnd."','".$as_numnc."',".
			  "          '".$as_tiptrans."','".$as_tot_cmp_sin_iva."','".$as_tot_cmp_con_iva."','".$as_basimp."',".
			  "          '".$as_porimp."','".$as_totimp."','".$as_ivaret."','".$as_desope."','".$as_numsop."',".
			  "          '".$as_codban."','".$as_ctaban."','".$as_numdoc."','".$as_codope."')";
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result ===false)
	{	
		$this->io_msg->message("CLASE->sigesp_scb_c_cmp_retencion;MÉTODO->uf_guardar_detallecmp ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	return $lb_valido;
}

function uf_actualizar_estcmp($as_numrecdoc,$as_codprobene,$as_tipproben,$as_codded,$as_codtipdoc)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_actualizar_estcmp
	//		   Access: public
	//		 Argument: $as_numrecdoc // Número de Recepcion de Documento
	//                 $as_codprobene // Codigo del proveedor o beneficiario 
	//                 $as_codded // Codigo de Retencion 
	//                 $as_tipproben // Indica si el codprobene es un proveedor o un beneficiario 
	//	  Description: Función que actualiza el campo estcmp al valor 1 en la tabla cxp_rd_deducciones lo
	//                 que indica que ese item ya fue procesado en un comprobante
	//	   Creado Por: Ing. Gerardo Cordero
	// Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	if($as_tipproben=="P"){
	   $ls_filtro = "cod_pro='".$as_codprobene."' AND ced_bene='----------'";
	 }
	 elseif($as_tipproben="B"){
	   $ls_filtro = "ced_bene='".$as_codprobene."' AND cod_pro='----------'";
	 }
	$ls_sql="UPDATE cxp_rd_deducciones
			    SET estcmp='1'
			  WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'
			    AND numrecdoc='".$as_numrecdoc."'
			    AND $ls_filtro
			    AND codded='".$as_codded."'
				AND codtipdoc = '".$as_codtipdoc."'";
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{	
		$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_actualizar_estcmp ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	return $lb_valido;
}	  

function uf_load_numeroinicial()
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_numeroinicial
	//		   Access: public
	//		 Argument: 
	//	  Description: Función que busca la configuracion del numero inicial
	//	   Creado Por: Ing. Luis Anibal Lang
	// Fecha Creación: 26/02/2008								Fecha Última Modificación : 
	//////////////////////////////////////////////////////////////////////////////
	$ls_concomiva=1;
	$ls_sql="SELECT concomiva ".
			"  FROM sigesp_empresa ".
			" WHERE codemp='".$_SESSION["la_empresa"]["codemp"]."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_load_numeroinicial ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		return false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_concomiva=$row["concomiva"];
		}
		$this->io_sql->free_result($rs_data);	
	}
	return $ls_concomiva;
}

function uf_crear_comprobante($as_numcom,$as_fecrep,$as_perfiscal,$as_codsujret,$as_nomsujret,$as_dirsujret,$as_rif,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////
	//	      Function: uf_crear_comprobante
	//	        Access: public
	//	      Argument: $as_codret // Codigo de la retencion,$as_numcom // Numero del comprobante
	//                  $as_fecrep // Fecha del comprobante,$as_perfiscal // perido fiscal
	//                  $as_codsujret // Codigo del proveedor o beneficiario,$as_nomsujret // Nombre del proveedor o beneficiario
	//                  $as_dirsujret // Direccion del proveedor o beneficiario ,$as_rif // RIF del proveedor o beneficiario
	//                  $as_nit // NIT del proveedor ,$as_estcmpret // Estatus del comprobante,
	//                  $as_codusu // codigo del usuario ,$as_numlic // Numero de licencia del proveedor,$as_origen 
	//     Description: Función que guarda la cabezera de un comprobante de retencion  
	//	    Creado Por: Ing. Gerardo Cordero
	//  Fecha Creación: 13/09/2007								Fecha Última Modificación : 13/09/2007
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" INSERT INTO scb_cmp_ret (codemp,codret,numcom,fecrep,perfiscal,codsujret,nomsujret,dirsujret,rif,".
			"                          nit,estcmpret,codusu,numlic,origen)".
			  " VALUES ('".$_SESSION["la_empresa"]["codemp"]."','0000000001','".$as_numcom."','".$as_fecrep."',".
			  "         '".$as_perfiscal."','".$as_codsujret."','". $as_nomsujret."','".$as_dirsujret."','".$as_rif."',".
			  "         '','1','".$this->ls_codusu."','','A')";
	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result===false)
	{	
		$this->io_msg->message("CLASE->Generar Comprobate MÉTODO->uf_crear_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Insertó el Comprobante ".$as_numcom.
						 " Asociado a la empresa ".$_SESSION["la_empresa"]["codemp"];
		$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////	
	}
	return $lb_valido;
}
}
?>