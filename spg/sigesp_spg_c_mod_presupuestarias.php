<?php
class sigesp_spg_c_mod_presupuestarias
{
	var $is_msg_error;
	var $io_sql;
	var $io_include;
	var $io_int_scg;
	var $io_int_spg;
	var $io_msg;
	var $io_function;
	var $is_codemp;
	var $is_procedencia;
	var $is_comprobante;
	var $id_fecha;
	var $ii_tipo_comp;
	var $is_descripcion;
	var $is_tipo;
	var $is_tipmodpre;
	var $io_class_apertura;
function sigesp_spg_c_mod_presupuestarias()
{
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_fecha.php");	
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sigesp_int.php");	
	require_once("../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../shared/class_folder/class_sigesp_int_spg.php");
	require_once("../shared/class_folder/class_sigesp_int_spi.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("sigesp_spg_class_apertura.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$this->io_seguridad= new sigesp_c_seguridad();

	$this->io_function=new class_funciones();	
	$this->sig_int=new class_sigesp_int();
    $this->io_fecha=new class_fecha();
	$this->io_include=new sigesp_include();	
	$this->io_connect=$this->io_include->uf_conectar();
	$this->io_sql=new class_sql($this->io_connect);
	$this->io_msg = new class_mensajes();
	$this->io_int_spg=new class_sigesp_int_spg();	
	$this->io_int_scg=new class_sigesp_int_scg();	
	$this->is_msg_error="";
	$this->io_class_apertura = new sigesp_spg_class_apertura();
	$this->li_codemp=$_SESSION["la_empresa"]["codemp"];
}
/**********************************************************************************************************************************/
function uf_generar_num_cmp($as_codemp,$as_procede)
{
	 //$ls_sql="SELECT comprobante FROM sigesp_cmp_md WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' ORDER BY comprobante DESC";		
	 $ls_sql="SELECT max(comprobante) as comprobante ".
                    "   FROM sigesp_cmp_md ".
                    "      WHERE      codemp='".$as_codemp."' ".
					"					 AND procede='".$as_procede."' " .
					"					 AND (comprobante not like '%A%' AND comprobante not like '%a%')".
					"					 AND (comprobante not like '%B%' AND comprobante not like '%b%')".
					"					 AND (comprobante not like '%C%' AND comprobante not like '%c%')".
					"					 AND (comprobante not like '%D%' AND comprobante not like '%d%')".
					"					 AND (comprobante not like '%E%' AND comprobante not like '%e%')".
					"					 AND (comprobante not like '%F%' AND comprobante not like '%f%')".
					"					 AND (comprobante not like '%G%' AND comprobante not like '%g%')".
					"					 AND (comprobante not like '%H%' AND comprobante not like '%h%')".
					"					 AND (comprobante not like '%I%' AND comprobante not like '%i%')".
					"					 AND (comprobante not like '%J%' AND comprobante not like '%j%')".
					"					 AND (comprobante not like '%K%' AND comprobante not like '%k%')".
					"					 AND (comprobante not like '%L%' AND comprobante not like '%l%')".
					"					 AND (comprobante not like '%M%' AND comprobante not like '%m%')".
					"					 AND (comprobante not like '%N%' AND comprobante not like '%n%')".
					"					 AND (comprobante not like '%O%' AND comprobante not like '%o%')".
					"					 AND (comprobante not like '%P%' AND comprobante not like '%p%')".
					"					 AND (comprobante not like '%Q%' AND comprobante not like '%q%')".
					"					 AND (comprobante not like '%R%' AND comprobante not like '%r%')".
					"					 AND (comprobante not like '%S%' AND comprobante not like '%s%')".
					"					 AND (comprobante not like '%T%' AND comprobante not like '%t%')".
					"					 AND (comprobante not like '%U%' AND comprobante not like '%u%')".
					"					 AND (comprobante not like '%V%' AND comprobante not like '%v%')".
					"					 AND (comprobante not like '%W%' AND comprobante not like '%w%')".
					"					 AND (comprobante not like '%X%' AND comprobante not like '%x%')".
					"					 AND (comprobante not like '%Y%' AND comprobante not like '%y%')".
					"					 AND (comprobante not like '%Z%' AND comprobante not like '%z%')".
					"					  ORDER BY comprobante DESC";
	  $rs_funciondb=$this->io_sql->select($ls_sql);
	  if ($row=$this->io_sql->fetch_row($rs_funciondb))
	  { 
		  $codigo=$row["comprobante"];
		  settype($codigo,'int');                             // Asigna el tipo a la variable.
		  $codigo = $codigo + 1;                              // Le sumo uno al entero.
		  settype($codigo,'string');                          // Lo convierto a varchar nuevamente.
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
	  }
	  else
	  {
		  $codigo="1";
		  $ls_codigo=$this->io_function->uf_cerosizquierda($codigo,15);
	  }
	return $ls_codigo;
}
/**********************************************************************************************************************************/
	function uf_sigesp_insert_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,
	                                      $as_tipo,$as_cod_prov,$as_ced_ben,$as_codfuefin,$as_coduniadm)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
    //	Function:  uf_sigesp_insert_comprobante
    //	  Access:  public
	// Arguments:  as_codemp->codigo empresa; as_procede-> procedencia; as_comprobante-> comprobante;
	//             as_fecha-< fecha ai_tipo_comp-< tipo comprobante (1,2); as_descripcion->descripcion;
	//             as_tipo->tipo fuente as_ced_ben-< beneficiario;as_cod_prov-> proveedor
	//	Returns:	 lb_valido -> variable boolean
	//	Description: Método que inserta el registro comprobante (información cabezera )en la tabla SIGESP_Cmp. Usado en el mòdulo de comprobante contable
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fec=$this->io_function->uf_convertirdatetobd($as_fecha);
		if(empty($this->is_tipmodpre))
		{
		 $lb_existe=$this->uf_sigesp_existe_tipmodpre("----");
		 if(!$lb_existe)
		 {
		  $lb_valido = $this->uf_sigesp_insert_tipmodpre("----","Tipo por Defecto","---","------------");
		  if($lb_valido)
		  {
		   $this->is_tipmodpre = "----";
		  }
		 }
		 else
		 {
		  $this->is_tipmodpre = "----";
		 }
		}
		$ls_sql = " INSERT INTO sigesp_cmp_md (codemp,procede,comprobante,fecha,descripcion,tipo_comp,tipo_destino,".
		          "                            cod_pro,ced_bene,total,estapro,codfuefin,coduac,codtipmodpre)".
				  " VALUES('".$as_codemp."', '".$as_procede."', '".$as_comprobante."','".$ls_fec."', ".
				  "        '".$as_descripcion."',".$ai_tipo_comp.",'".$as_tipo."','".$as_cod_prov."', ".
				  "        '".$as_ced_ben."', ". intval(0). ",0,'".$as_codfuefin."','".$as_coduniadm."','".$this->is_tipmodpre."')";		  
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$lb_valido=false;
			$this->is_msg_error = "Error en método uf_sigesp_insert_comprobante ";
		}
		else
		{
		   $this->is_log_transacciones="Inserto comprobante Nº".$as_comprobante." de procedencia ".$as_procede." con fecha ".$as_fecha; 
		}
		return $lb_valido;
	} // end function uf_sigesp_insertcomporbante()
/**********************************************************************************************************************************/
	function uf_sigesp_update_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$as_cod_prov,$as_ced_ben,$li_estapro,$as_codfuefin,$as_coduniadm)
    {		
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//	 Function:  uf_sigesp_delete_comprobante()	
		//	   Access:  public
		//	Arguments:  instancias de la clase propia
		//	  Returns:	booleano lb_valido
		//Description:  Método que elimina el registro comprobante (información cabezera ) en la tabla SIGESP_Cmp
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql = " UPDATE sigesp_cmp_md SET descripcion='".$as_descripcion."' ,estapro=".$li_estapro.", codfuefin='".$as_codfuefin."',coduac='".$as_coduniadm."'  
		WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' AND comprobante='".$as_comprobante."' AND fecha='".$ls_fecha."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "Error en método uf_sigesp_update_comprobante ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}		
		return $lb_valido;
	} //fin de uf_scg_update_conprobante
/**********************************************************************************************************************************/
    function uf_sigesp_delete_comprobante()	
    {
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_sigesp_delete_comprobante()	
	//	   Access:  public
	//	Arguments:  instancias de la clase propia
	//	  Returns:	booleano lb_valido
	//Description:  Método que elimina el registro comprobante (información cabezera ) en la tabla SIGESP_Cmp
	/////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ld_fecha=$this->io_function->uf_convertirdatetobd(ld_fecha);
		$ls_sql="DELETE 
				 FROM sigesp_cmp_md 
				 WHERE codemp = '".$this->is_codemp."' AND  procede='".$this->is_procedencia."' 
				 AND comprobante='".$this->is_comprobante."' AND fecha='".$ld_fecha."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
		
		if($li_numrows===false)
		{
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
		}
		return $lb_valido;
	} // end function uf_sigesp_delete_comprobante
/**********************************************************************************************************************************/
	function uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_select_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_existe=false;
	   $ls_newfec=$this->io_function->uf_convertirdatetobd($as_fecha);
	   $ls_sql =   " SELECT comprobante ".
	               " FROM sigesp_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";
				  
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_existe=true;
		  }  
	  }
	  //print  $lb_existe."<br>";
	  return $lb_existe;
	} // end function uf_select_comprobante
/**********************************************************************************************************************************/
	function uf_sigesp_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,
	                               $as_tipo,$as_cod_pro,$as_ced_bene,$adec_monto,$li_estapro,$as_codfuefin,$as_coduniadm)
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:   uf_sigesp_comprobante
	// 	   Access:  public
	//  Arguments:  $as_codemp->empresa,$as_procede->procede,$as_comprobante->comprobante,$as_fecha->fecha comprobante,
    //	            $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Procesa un comprobante 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
   	    $this->ii_tipo_comp=$ai_tipo_comp;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;

		if ($as_tipo=="B")
		{
		   $this->is_ced_ben  = $as_ced_bene;
   	       $this->is_cod_prov = "----------"; 
		}
		if ($as_tipo=="P")
		{
		   $this->is_ced_ben  = "----------";
		   $this->is_cod_prov = $as_cod_pro;
		}
		if ($as_tipo=="-")
		{
		   $this->is_ced_ben  = "----------";
		   $this->is_cod_prov = "----------";
		}
        if ($this->uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha))
		{	
		   $this->ib_new_comprobante=false;
           $lb_valido=$this->uf_sigesp_update_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,$li_estapro,$as_codfuefin,$as_coduniadm);
		}
		else
		{
		   $this->ib_new_comprobante=true;		
		   $lb_valido=$this->uf_sigesp_insert_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_codfuefin,$as_coduniadm);
		}
		return $lb_valido;
	} // end function uf_procesar_comprobante_en_linea()
/**********************************************************************************************************************************/
function uf_guardar_automatico($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,$as_prov,$as_bene,$as_tipo,$ai_tipo_comp,$li_estapro,$as_codfuefin,$as_coduniadm)
{
	$lb_valido=false;
	$dat=$_SESSION["la_empresa"];
	$_SESSION["fechacomprobante"]=$ad_fecha;
	if($this->uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_proccomp,$as_desccomp,&$as_prov,&$as_bene,$as_tipo))
	{	
	   $lb_valido=$this->uf_sigesp_comprobante($dat["codemp"],$as_proccomp,$as_comprobante,$ad_fecha,$ai_tipo_comp,$as_desccomp,$as_tipo,$as_prov,$as_bene,0,$li_estapro,$as_codfuefin,$as_coduniadm);
	   if (!$lb_valido)
	   {
	      $this->io_msg->message("Error al procesar el comprobante Presupuestario".$this->is_msg_error);
	   }  
	  /* else  
	   {   
	       $this->io_msg->message("El Movimiento fue registrado.");
	   }*/
	   
	   $ib_valido = $lb_valido;
	   
	   if($lb_valido)
	   {
		  $ib_new = $this->ib_new_comprobante;
	   }	
	   else  
	   {  
	      $lb_valido=true;  
	   } 	
	}
	else
	{ 
	   $this->io_msg->message("Error en valida datos comprobante");
    }
	return $lb_valido;
}
/**********************************************************************************************************************************/
function uf_cargar_dt_comprobante($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{
	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	/*
	$ls_sql=" SELECT DISTINCT DT.codestpro1 as codest1,DT.codestpro2 as codest2,DT.codestpro3 as codest3, ".
   	        "                 DT.codestpro4 as codest4,DT.codestpro5 as codest5,DT.estcla as estcla,DT.spg_cuenta as spg_cuenta, ".
	        "                 max(C.denominacion) as dencuenta, max(DT.procede_doc) as procede_doc, max(P.desproc) as desproc, ".
	        "				  max(DT.documento) as documento, max(DT.operacion) as operacion, max(DT.descripcion) as descripcion, ".
	        "                 max(DT.monto) as monto, max(DT.orden) as orden, max(OP.denominacion) as denominacion  ".
            " FROM  spg_dtmp_cmp DT, spg_cuentas C, sigesp_procedencias P, spg_operaciones OP ".
            " WHERE DT.procede=P.procede AND DT.codemp=C.codemp AND DT.spg_cuenta=C.spg_cuenta AND ".
			"       OP.operacion = DT.operacion AND (DT.codestpro1=C.codestpro1  AND DT.codestpro2=C.codestpro2 AND ".
			"       DT.codestpro3=C.codestpro3  AND DT.codestpro4=C.codestpro4   AND DT.codestpro5=C.codestpro5 AND ".
			"       DT.estcla=C.estcla) AND DT.codemp='".$as_codemp."'  AND  DT.procede='".$as_procede."' AND ".
			"       DT.comprobante='".$as_comprobante."'  AND  DT.fecha='".$ld_fecha."' ".
            " GROUP BY DT.codestpro1, DT.codestpro2 , DT.codestpro3 , DT.codestpro4 , DT.codestpro5  , DT.estcla , DT.spg_cuenta ".
            " ORDER BY DT.codestpro1, DT.codestpro2 , DT.codestpro3 , DT.codestpro4 , DT.codestpro5  , DT.estcla , DT.spg_cuenta , dencuenta, procede_doc, ".
			"          desproc, documento, operacion, descripcion, monto, orden, denominacion ";	
 	*/

$ls_sql=" SELECT DISTINCT DT.codestpro1 as codest1,DT.codestpro2 as codest2,DT.codestpro3 as codest3, ".
   	        "                 DT.codestpro4 as codest4,DT.codestpro5 as codest5,DT.estcla as estcla,DT.spg_cuenta as spg_cuenta, ".
	        "                 C.denominacion as dencuenta, DT.procede_doc as procede_doc, P.desproc as desproc, ".
	        "				  DT.documento as documento, DT.operacion as operacion, DT.descripcion as descripcion, ".
	        "                 DT.monto as monto, DT.orden as orden, OP.denominacion as denominacion  ".
            " FROM  spg_dtmp_cmp DT, spg_cuentas C, sigesp_procedencias P, spg_operaciones OP ".
            " WHERE DT.procede=P.procede AND DT.codemp=C.codemp AND DT.spg_cuenta=C.spg_cuenta AND ".
			"       OP.operacion = DT.operacion AND (DT.codestpro1=C.codestpro1  AND DT.codestpro2=C.codestpro2 AND ".
			"       DT.codestpro3=C.codestpro3  AND DT.codestpro4=C.codestpro4   AND DT.codestpro5=C.codestpro5 AND ".
			"       DT.estcla=C.estcla) AND DT.codemp='".$as_codemp."'  AND  DT.procede='".$as_procede."' AND ".
			"       DT.comprobante='".$as_comprobante."'  AND  DT.fecha='".$ld_fecha."' ".
            " ORDER BY DT.codestpro1, DT.codestpro2 , DT.codestpro3 , DT.codestpro4 , DT.codestpro5  , DT.estcla , DT.spg_cuenta , dencuenta, procede_doc, ".
			"          desproc, documento, operacion, descripcion, monto, orden, denominacion ";
	
	$rs_dt_cmp=$this->io_sql->select($ls_sql);
	if($rs_dt_cmp===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return $rs_dt_cmp;
}
/**********************************************************************************************************************************/
function uf_cargar_dt_contable_cmp($as_codemp,$as_procede,$as_comprobante,$adt_fecha)
{
	$ld_fecha=$this->io_function->uf_convertirdatetobd($adt_fecha);
	$rs_dt_scg=$this->uf_scg_cargar_detalle_comprobante( $as_codemp, $as_procede,$as_comprobante, $ld_fecha,&$lds_detalle_cmp);
	if($rs_dt_scg===false)
	{
		$this->io_msg->message($this->io_function->uf_convertirmsg($this->io_int_scg->io_sql->message));
	}
	return $rs_dt_scg;
}
/**********************************************************************************************************************************/
 function uf_scg_cargar_detalle_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$lds_detalle_cmp)
 {	 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function: uf_scg_cargar_detalle_comprobante
	// 	   Access:  public
	//	  Returns:  estructura de datos
	//Description:  inserta la información del saldo de la cuenta correspondiente.
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql =  " SELECT DISTINCT DT.sc_cuenta as sc_cuenta,C.denominacion as denominacion,DT.procede_doc as procede_doc,P.desproc as despro,".
               		 "                 DT.documento as documento,DT.fecha as fecha,DT.debhab as debhab,DT.descripcion as descripcion,DT.monto as monto,DT.orden as orden " .
					 " FROM scg_dtmp_cmp DT,scg_cuentas C, sigesp_procedencias P ".
					 " WHERE DT.codemp='".$as_codemp."' AND DT.procede='".$as_procede."' AND DT.comprobante='".$as_comprobante."' AND ".
					 "       DT.fecha= '".$as_fecha."' AND DT.sc_cuenta=C.sc_cuenta AND DT.procede=P.procede ".
					 " ORDER BY DT.orden ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$this->is_msg_error="Error en cargar detalle comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $rs_data;
	 }  // end function uf_scg_cargar_detalle_comprobante()
/**********************************************************************************************************************************/
function uf_valida_datos_cmp($as_comprobante,$ad_fecha,$as_procedencia,$as_desccomp,$as_cod_prov,$as_ced_bene,$as_tipo)
{
	$ls_desproc ="";
	if(!$this->io_int_spg->uf_valida_procedencia($as_procedencia,&$ls_desproc ) )
	{
	   $this->io_msg->message("Procedencia invalida.",$ls_desproc);
	   return false	;
	} 

	if(trim($as_comprobante)=="")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}

	if(trim($as_comprobante)=="000000000000000")
	{
		$this->io_msg->message("Debe registrar el comprobante contable.");
		return false;
	}
	
	
	if((trim($as_cod_prov)=="----------")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
	{
		$this->io_msg->message("Debe registrar el codigo del proveedor.");
		return false;
	}
	
	if((trim($as_cod_prov)!="----------" )&&($as_tipo=="B"))
	{
		$as_cod_prov = "----------";
	}
		
	if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario1.");
		return false;
	}
	if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
	{
		$this->io_msg->message("Debe registrar la cédula del beneficiario.2");
		return false;	
	}
	
	if((trim($as_ced_bene)!="----------" )&&($as_tipo=="P"))
	{
		$as_ced_bene="----------";
	}
	if($as_tipo=="-")
	{
		$as_ced_bene="----------";
		$as_cod_prov="----------";
	}

  return true;
}
/**********************************************************************************************************************************/
function uf_guardar_movimientos($arr_cmp,$ls_est1,$ls_est2,$ls_est3,$ls_est4,$ls_est5,$ls_cuenta,$ls_procede_doc,
                                $ls_descripcion,$ls_documento,$ls_operacionpre,$ldec_monto_ant,$ldec_monto_act,
								$ls_tipocomp,$ls_estcla,$ls_codfuefin="--")
{
	$lb_valido=false;
	$estpro[0]=$ls_est1;
	$estpro[1]=$ls_est2;
	$estpro[2]=$ls_est3;
	$estpro[3]=$ls_est4;
	$estpro[4]=$ls_est5;
	$estpro[5]=$ls_estcla;
				
	$ls_mensaje = $this->io_int_spg->uf_operacion_codigo_mensaje($ls_operacionpre) ;
	
	if($ls_mensaje!="")
	{
		if(!$this->uf_spg_valida_datos_movimiento($ls_cuenta,$ls_descripcion,$ls_documento,&$ldec_monto))
		{ 
		   $this->io_msg->message($this->is_msg_error);
		   return false;
		}
		$this->io_int_spg->is_codemp=$arr_cmp["codemp"];
		$this->io_int_spg->is_comprobante=$arr_cmp["comprobante"];
		$this->io_int_spg->id_fecha=$arr_cmp["fecha"];
		$this->io_int_spg->is_procedencia=$arr_cmp["procedencia"];
		$this->io_int_spg->is_cod_prov=$arr_cmp["proveedor"];
		$this->io_int_spg->is_ced_bene=$arr_cmp["beneficiario"];
		$this->io_int_spg->is_tipo=$arr_cmp["tipo"];
		$lb_valido=$this->uf_spg_comprobante_actualizar($ldec_monto_ant, $ldec_monto_act, $ls_tipocomp);
		if($lb_valido)
		{
	        $ls_sc_cuenta="";	
			if ($arr_cmp["tipo"]=="B")  
				{ $ls_fuente = $arr_cmp["beneficiario"]; }	
			else
			{ 
				if ($arr_cmp["tipo"]=="P")
				 {  
					$ls_fuente = $arr_cmp["proveedor"]; 
				 }	
				 else 
				 {  
					$ls_fuente = "----------"; 
				 } 
			}
			if(!$this->io_int_spg->uf_spg_select_cuenta($arr_cmp["codemp"],$estpro,$ls_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
			{  
			  return false;
			}
			$ls_comprobante = $this->io_int_spg->uf_fill_comprobante( $this->is_comprobante );
		    $ls_operacion = $this->io_int_spg->uf_operacion_mensaje_codigo($ls_mensaje);
		    if(empty($ls_operacion)) { return false; }
		    if(!$this->io_int_spg->uf_valida_procedencia( $this->io_int_spg->is_procedencia , $ls_denproc)) { return false; }
		    if(!$this->io_int_spg->io_fecha->uf_valida_fecha_mes($this->io_int_spg->is_codemp,$this->io_int_spg->id_fecha))
		    {
		 	   $this->is_msg_error = "Fecha Invalida."	;
	 		   $this->io_msg->message($this->is_msg_error);			   		  		  
 			   return false;
		    }
		    if($this->uf_spg_select_movimiento($estpro, $ls_cuenta, $ls_procede_doc, $ls_documento, $ls_operacion, $lo_monto_movimiento, $lo_orden))  
		    {
		 	  $this->is_msg_error = "El movimiento presupuestario ya existe.";
	 		  $this->io_msg->message($this->is_msg_error);			   		  		  		  
 			  return false; 	 
		    }
			$lb_valido = $this->uf_spg_comprobante_actualizar(0,$ldec_monto_ant,"C");
			if($lb_valido)
			{
				$lb_existe= $this->uf_sigesp_existe_detalle_reverso($estpro,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion);
				if (!$lb_existe)
				{
					$lb_valido = $this->uf_insert_movimiento_spg($estpro,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ls_descripcion,$ldec_monto_act,$ls_codfuefin);
					if($lb_valido)
					{
						$ls_mensaje=strtoupper($ls_mensaje); // devuelve cadena en MAYUSCULAS
						$li_pos_i=strpos($ls_mensaje,"C"); 
						if (!($li_pos_i===false))
						{			      
						  if ($this->ib_AutoConta)
						  {
							  $lb_valido=$this->uf_spg_integracion_scg($ls_codemp,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_descripcion,$ldec_monto);
						  }
						} 
					
						if(!$lb_valido)
						{
							$this->io_msg->message("No se registraron los detalles presupuestario".$this->io_int_spg->is_msg_error);
						}
						else
						{
						 $this->io_msg->message("El Movimiento fue registrado.");
					    }
					}
				}
				else
				{
				 $lb_valido =  false;
				 $this->io_msg->message("El detalle ya se encuentra registrado pero con operación distinta, verifique por favor");
				}
			}
		}
		else
		{
		  	$lb_valido=false;
		}
   }
   $ldec_monto = 0;
 return $lb_valido;
}
/**********************************************************************************************************************************/
   function uf_insert_movimiento_spg($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$ad_monto_actual,$as_codfuefin="--")
   {
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   //	 Function:  uf_spg_insert_movimiento
	   //	Arguments:  estprog->estructura programatica del gasto; as_cuenta->cuenta gasto ; as_procede_doc procedenca del documento
	   //               as_documento  n° del documento; as_operacion  operacion de gasto; as_descripcion	 descripcion del movimiento  
	   //               adec_monto   monto del mivimiento 
	   //	  Returns:  lb_valido -> variable boolean
	   // Description:  Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
	   ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
	   $lb_valido = true;
	   $ls_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $li_orden  = $this->uf_spg_obtener_orden_movimiento();
	   if(empty($as_codfuefin))
	   {
	    $as_codfuefin = '--';
	   }
	   $lb_existe = $this->io_class_apertura->uf_spg_existe_dt_fuefin_estructura($this->is_codemp,$estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],$estprog[5],$as_codfuefin);
	   if(!$lb_existe)
	   {
		 $lb_valido=$this->io_class_apertura->uf_spg_insert_dt_fuefin_estructura($this->is_codemp,$estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],$estprog[5],$as_codfuefin);
	   }
	   $lb_existe = $this->io_class_apertura->uf_spg_existe_fuefin_estructura($this->is_codemp,$estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],$estprog[5],$as_cuenta,$as_codfuefin);
	   if(!$lb_existe)
	   {
	     $lb_valido=$this->io_class_apertura->uf_spg_insert_fuefin_estructura($this->is_codemp,$estprog[0],$estprog[1],$estprog[2],$estprog[3],$estprog[4],$estprog[5],$as_cuenta,$as_codfuefin,0); 
	   }
	   
	   $ls_sql = " INSERT INTO spg_dtmp_cmp (codemp,procede,comprobante,fecha,codestpro1,codestpro2,codestpro3, ".
	             "                           codestpro4,codestpro5,spg_cuenta,procede_doc,documento,operacion,  ".
				 "                           descripcion,monto,orden,estcla,codfuefin) ".
			     " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."', ".
				 "        '".$ls_fecha."','".$estprog[0]."','".$estprog[1]."','".$estprog[2]."','".$estprog[3]."', ".
				 "        '".$estprog[4]."','".$as_cuenta."','".$as_procede_doc."','".$as_documento."', ".
				 "        '".$as_operacion."','".$as_descripcion."','".$ad_monto_actual."',".$li_orden.", ".
				 "        '".$estprog[5]."','".$as_codfuefin."')";		  
	   $li_rows=$this->io_sql->execute($ls_sql);
	   if($li_rows===false)
	   {
		  $lb_valido=false;
		  $this->is_msg_error = "Error de SQL método->uf_spg_insert_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  print $this->io_sql->message;
	   }
	   return $lb_valido;
	}// end function uf_spg_insert_movimiento_gasto
/**********************************************************************************************************************************/
	function uf_spg_obtener_orden_movimiento()
	{   
	//////////////////////////////////////////////////////////////////////////////
	//	   Function:  uf_spg_obtener_orden_movimiento
	//	    Returns:  li_orden -> numero del orden
	//	Description:  Retorna el número de orden del movimiento de gasto spg
	/////////////////////////////////////////////////////////////////////////////	
		$li_orden=0;
		//$ld_fecha=$this->io_function->uf_formatovalidofecha($this->id_fecha);
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql= " SELECT count(*) as orden  FROM spg_dtmp_cmp".
				 " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."'".
				 " AND fecha='".$ld_fecha."' " ;
		$rs_data=$this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
   	 	   $this->is_msg_error="Error de SQL método->uf_spg_obtener_orden_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   print $this->io_sql->message;
		   return false;
	    }
	    else {  if($row=$this->io_sql->fetch_row($rs_data))  { $li_orden=$row["orden"]; } } 
		
	   $this->io_sql->free_result($rs_data);		
	   return $li_orden;
    } //end function uf_spg_obtener_orden_movimiento()
/**********************************************************************************************************************************/
	function uf_spg_integracion_scg($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $as_descripcion, $adec_monto_actual)
	{
		$lb_valido=true;$ls_debhab=""; $ls_status=""; $ls_denominacion=""; $ls_mensaje_error="";$ldec_monto=0;$li_orden=0;
	
		if($adec_monto_actual > 0) 	{ $ls_debhab = "D"; }
		else{  $ls_debhab = "H"; }
		if (!$this->io_int_spg->io_int_scg->uf_scg_select_cuenta( $as_codemp, $as_scgcuenta, &$ls_status, $ls_denominacion))
		{
		   $this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no existe.");
		   return false;
		} 
		if($ls_status!="C")
		{ 
		   $this->io_msg->message("La cuenta contable [". trim($as_scgcuenta) ."] no es de movimiento.");
		   return false;
		} 
		
		$this->io_int_spg->io_int_scg->is_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$this->io_int_spg->io_int_scg->is_codemp=$as_codemp;
		$this->io_int_spg->io_int_scg->is_procedencia=$this->is_procedencia;
		$this->io_int_spg->io_int_scg->is_comprobante=$this->is_comprobante;
		
		if (!$this->uf_scg_select_movimiento($as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $ldec_monto, $li_orden))
		{
		   	//$lb_valido = $this->io_int_scg->uf_scg_registro_movimiento_int($as_codemp, $as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
			$lb_valido = $this->uf_scg_procesar_insert_movimiento($as_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$as_scgcuenta, $as_procede_doc, $as_documento, $ls_debhab, $as_descripcion, 0, $adec_monto_actual);
		}																	 
	return $lb_valido;
	}//uf_spg_integracion_scg
/**********************************************************************************************************************************/
	function uf_scg_procesar_insert_movimiento($as_codemp,$as_procede, $as_comprobante, $as_fecha,
                                     	      $as_tipo_destino,$as_cod_prov, $as_ced_bene, $as_cuenta,
										      $as_procede_doc, $as_documento,$as_debhab,$as_descripcion,
										      $adec_monto_anterior, $adec_monto_actual )
    {											  
	///////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_procesar_insert_movimiento
	// 	   Access:  public
	//  Arguments:  $as_codemp->empresa,$as_procede->procede,$as_comprobante->comprobante,$as_fecha->fecha comprobante,
    //	            $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Este método registra un movimiento contable (Método Principal MAIN )
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_desproc="";	
		$li_orden=0;
		$this->is_codemp      = $as_codemp;
		$this->is_procedencia = $as_procede;
		$this->is_comprobante = $as_comprobante;
		$this->id_fecha       = $as_fecha;
		$this->is_cod_prov    = $as_cod_prov;
		$this->is_ced_ben     = $as_ced_bene;
		$this->is_tipo        = $as_tipo_destino;

		if (!($this->io_int_spg->io_int_scg->uf_valida_procedencia( $as_procede , $ls_desproc))) { return false; }	 
		
		if ($this->uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto_actual,&$li_orden)) 
		{
		   $this->is_msg_error="El movimiento contable ya existe.";
		   return false; 	
		}
		$lb_valido = $this->uf_scg_insert_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$as_descripcion,$adec_monto_actual);
		return $lb_valido;
	} //end function uf_scg_registro_movimiento()
/**********************************************************************************************************************************/
	function uf_scg_select_movimiento($as_cuenta,$as_procede_doc,$as_documento,$as_debhab,&$adec_monto,&$ai_orden)
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_select_movimiento
	// 	   Access:  public
	//  Arguments:  as_sc_cuenta-> cuenta contable;as_procede_doc->procedencia documento ; as_documento-> documento
	//              as_debhab->operacion debe-haber; adec_monto->monto Operacion;ai_orden->orden movimiento
	//	  Returns:  boolean
	//Description:  Este método verifica si existe o no el movimiento contable
	////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_existe = false;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql =   " SELECT monto,orden".
		            " FROM scg_dtmp_cmp".
		            " WHERE codemp='".$this->is_codemp."' AND procede='".$this->is_procedencia."' AND comprobante='".$this->is_comprobante."' AND ".
					"       fecha='".$ld_fecha."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND sc_cuenta='".$as_cuenta."' AND debhab='".$as_debhab."'";
		$rs_mov=$this->io_sql->select($ls_sql);
		
		if($rs_mov===false)	{  $this->is_msg_error = "Error en el método uf_scg_select_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);	}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_mov))
			{
				$lb_existe=true;
				$adec_monto = $row["monto"];
				$ai_orden   = $row["orden"];
			}
			else  {  $lb_existe=false; }
		}
	   $this->io_sql->free_result($rs_mov);		
	   return $lb_existe;
	} // end function uf_scg_select_movimiento
/**********************************************************************************************************************************/
	function uf_scg_delete_movimiento($as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion )
	{
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_delete_movimiento
	// 	   Access:  public
	//	  Returns:  boolean
	//Description:  Este método elimina el movimineto contable
	////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido= true;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($as_fecha);
		
		$ls_sql =   " DELETE FROM scg_dtmp_cmp ".
					" WHERE codemp='".$as_codemp."' AND procede='".$as_procede."' AND comprobante='".$as_comprobante ."' AND fecha= '".$ls_fecha."' AND ".
					"       sc_cuenta= '".$as_cuenta."' AND procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND debhab='".$as_operacion."'";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$this->is_msg_error = "Error en método uf_scg_delete_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
	    return $lb_valido;
	} // end function uf_scg_delete_movimiento()
/**********************************************************************************************************************************/
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_insert_movimiento
	// 	   Access:  public
	//  Arguments:  $as_cuenta->cuenta contable ,$as_procede_doc->procede documento,$as_documento->nº documento
	//              $as_operacion->operacion debe haber,$adec_monto->mnto movimiento
	//	  Returns:  Boolean
	//Description:  Este método registra un movimiento final contable enla tabla movimiento  (DEPENDE DEL PROCESAR)
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_insert_movimiento( $as_cuenta, $as_procede_doc, $as_documento, $as_debhab, $as_descripcion, $adec_monto )
	{
		$lb_valido = true;
		$li_orden = $this->uf_scg_obtener_orden_movimiento();
		$ls_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql = "INSERT INTO scg_dtmp_cmp (codemp,procede,comprobante,fecha,sc_cuenta,procede_doc,documento,debhab,descripcion,monto,orden) " . 
				  " VALUES('".$this->is_codemp."','".$this->is_procedencia."','".$this->is_comprobante."','" .$ls_fecha."','".$as_cuenta."', '".$as_procede_doc."','".$as_documento."','".$as_debhab."','".$as_descripcion."',".$adec_monto.",".$li_orden.")" ;
		$li_result=$this->io_sql->execute($ls_sql);

		if($li_result===false)
		{
		   
		   if($this->io_sql->errno==1452)
		   {
			   $this->is_msg_error = "Error en método uf_scg_insert_movimiento, Fallo alguna clave foranea";
		   }
		   else
		   {
		   		$this->is_msg_error = "Error en método uf_scg_insert_movimiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   }
		   //print $this->io_sql->message;
		   $lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_scg_insert_movimiento()
/**********************************************************************************************************************************/
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//   Function:  uf_spg_select_movimiento
    //     Access: public	
	//	Arguments:  as_est1...as_est5 -> estructura programatica  ; as_cuenta->cuenta presupuestaria
	//              as_procede_doc- > procedenca del documento ; as_documento -> n° del documento
	//	  Returns:	lb_valido -> variable boolean
	//Description:  Este método verifica si el movimiento ya existe o no en la tabla de movimeintos presupuestario de gasto,
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function uf_spg_select_movimiento($estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,&$adec_monto,&$ai_orden)
	{
  	    $lb_existe=false;
		$ls_cuenta  = "";$lb_existe=false;$ldec_monto=0;$li_orden=0;
		$ls_codemp  =  $this->is_codemp ;
		$ls_procedencia = $as_procede_doc;
		$ls_comprobante = $as_documento;
		$ls_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = " SELECT spg_cuenta,monto,orden ".
			      " FROM  spg_dtmp_cmp ".		
			      " WHERE codemp='".$ls_codemp."' AND codestpro1 ='".$estprog[0]."'  AND ".
				  "       codestpro2 ='".$estprog[1]."' AND codestpro3 ='".$estprog[2]."'   AND  ". 
			      "       codestpro4 ='".$estprog[3]."' AND codestpro5 ='".$estprog[4]."'  AND   ".
				  "       estcla='".$estprog[5]."' AND procede='".$this->is_procedencia."'   AND  ".
				  "       comprobante='".$this->is_comprobante."' AND  fecha='".$ls_fecha."' AND ".
			      "       procede_doc='".$as_procede_doc."' AND documento ='".$as_documento."' AND ".
			      "       spg_cuenta ='".$as_cuenta."'  AND  operacion='".$as_operacion."' "; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
   	 	    $this->is_msg_error="Error de SQL método->uf_spg_select_movimiento class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		    return false;
		}
		else
		{
			if ($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_cuenta=$row["spg_cuenta"];
				$ldec_monto=$row["monto"];
				$adec_monto=$ldec_monto;
				$li_orden=$row["orden"];
				$ai_orden=$li_orden;
				$lb_existe=true;
			}				
		}
		$this->io_sql->free_result($rs_data);				
		return $lb_existe;
	} // end function uf_select_movimientos
/**********************************************************************************************************************************/
	function uf_spg_comprobante_actualizar($ai_montoanterior, $ai_montoactual, $ls_tipocomp)
    {
      $lb_valido=false; 
	  $li_tipocomp=0;
	  if($ls_tipocomp=="C") { $li_tipocomp=1; }
      if($ls_tipocomp=="P") { $li_tipocomp=2; }	
	  if ($this->uf_spg_comprobante_select())
	  {
		 $lb_valido = $this->uf_spg_comprobante_update($ai_montoanterior, $ai_montoactual);
   	  }
	  else 
	  { 
	     $lb_valido = $this->uf_spg_comprobante_insert($ai_montoactual, $li_tipocomp);  
	  }
     return $lb_valido;
    } // end function uf_spg_comprobante_actualizar()
/**********************************************************************************************************************************/
	/////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_select
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método verifica si existe el comprobante SIGESP_cmp
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_comprobante_select()
	{
		$lb_existe=false;
		//$ld_fecha=$this->io_function->uf_formatovalidofecha($ld_fecha);
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql= " SELECT * ".
		         " FROM  sigesp_cmp_md ".
				 " WHERE procede='".$this->is_procedencia."' AND ".
				 "       comprobante='".$this->is_comprobante."' AND ".
				 "       fecha='".$ld_fecha."' ";
		$rs_data = $this->io_sql->select($ls_sql);
	    if($rs_data===false)
	    {
   	 	   $this->is_msg_error="Error de SQL método->uf_spg_comprobante_select class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
  	       return false;
	    }
	    else {   if($row=$this->io_sql->fetch_row($rs_data))  {  $lb_existe=true;  } } 
		$this->io_sql->free_result($rs_data);		
		return $lb_existe;
	} // end function uf_spg_comprobante_select()
/**********************************************************************************************************************************/
	 /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_update
    //   Arguments: ai_montoanterior -> monto anterior ;$ai_montoactual -> monto actual
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método actualiza si existe el comprobante SIGESP_cmp
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_comprobante_update($li_montoanterior, $li_montoactual)
	{
	   $lb_valido = true;
	   $li_total = ( - $li_montoanterior + $li_montoactual);
	   $ld_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $ls_sql = " UPDATE sigesp_cmp_md SET total = total + '".$li_total."'  ".
	             " WHERE  procede='".$this->is_procedencia."' AND ".
				 "        comprobante= '".$this->is_comprobante."' AND ".
				 "        fecha='".$ld_fecha."' ";
	   $li_exec=$this->io_sql->execute($ls_sql);
	   if($li_exec===false)
	   {
 	      $this->is_msg_error="Error de SQL método->uf_spg_comprobante_update class->xspg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  $lb_valido=false;
	   }	   
	   return $lb_valido;
	} // function uf_spg_comprobante_update()
/**********************************************************************************************************************************/
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_spg_comprobante_insert
    //   Arguments: ai_montoanterior -> monto anterior ;$ai_montoactual -> monto actual
    //      Access: public
    //     Returns: retorna valido
    // Description: Este método inserta en el compronate de gasto
    /////////////////////////////////////////////////////////////////////////////////////////////////////
	function  uf_spg_comprobante_insert($ai_monto, $ai_tipocomp)
	{
		$lb_valido=true;
		$ls_codemp = $this->is_codemp;  $ls_procede = $this->is_procedencia; $ls_comprobante = $this->is_comprobante;
		$ls_descripcion=$this->is_descripcion; 	$ls_tipo=$this->is_tipo;
		$ls_codpro=$this->is_cod_prov;
		$ls_cedbene=$this->is_ced_ben;
		//$ld_fecha=$this->io_function->uf_formatovalidofecha($this->id_fecha);		
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
	    $ls_sql = " INSERT INTO sigesp_cmp_md(codemp,procede,comprobante,fecha,descripcion,total,".
		          "                           tipo_destino,cod_pro,ced_bene,tipo_comp)  ".
				  " VALUES ('".$ls_codemp."', '".$ls_procede."', '".$ls_comprobante."', ".
				  "         '".$ld_fecha."', '".$ls_descripcion."', '".$ai_monto."',    ".
				  "         '".$ls_tipo."', '".$ls_codpro."', '".$ls_cedbene."', '".$ai_tipocomp."' )";	  
		$li_exec=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
		if($li_exec===false)
		{
 	       $this->is_msg_error="Error de SQL método->uf_spg_comprobante_insert class->class_sigesp_int_spg ::".$this->io_function->uf_convertirmsg($this->io_sql->message);
		   //print $this->io_sql->message;
		   $lb_valido=false;
		}
	return $lb_valido;
   }  // end function uf_spg_comp_insert
/**********************************************************************************************************************************/
function uf_spg_valida_datos_movimiento($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
{
	if (trim($as_cuenta)=="")
	{
		$this->is_msg_error = "Registre la Cuenta Gasto." ;
		return false;	
	}
	if(trim($as_descripcion)=="")
	{
		$this->is_msg_error = "Registre la Descripción del Movimiento." ;
		return false;
	}
	
	if(trim($as_documento) =="") 
	{
		$this->is_msg_error = "Registre el Nº de documento." 	;
		return false;	
	}
	 return true ;
}
/**********************************************************************************************************************************/
function uf_guardar_movimientos_contable($arr_cmp,$as_cuenta,$as_procede_doc,$as_descripcion,$as_documento,
                                         $as_operacioncon,$adec_monto)
{
	$lb_valido=false;

	if(!$this->uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto))
	{ 
		$this->io_msg->message($this->is_msg_error);
	   return false;
	}
	$lb_valido = $this->uf_scg_procesar_movimiento_cmp($arr_cmp["codemp"],$arr_cmp["procedencia"],$arr_cmp["comprobante"],$arr_cmp["fecha"],
                                                       $arr_cmp["proveedor"],$arr_cmp["beneficiario"],$arr_cmp["tipo"],$arr_cmp["tipo_comp"],
                                                       $as_cuenta,$as_procede_doc,$as_documento,$as_operacioncon,$as_descripcion,$adec_monto);
	if(!$lb_valido)
	{
		$this->io_msg->message("Error al registrar movimiento contable".$this->io_int_scg->is_msg_error);
	}
	$ldec_monto = 0;
    return $lb_valido;
 }
/**********************************************************************************************************************************/
	function uf_scg_valida_datos_mov_contable($as_cuenta,$as_descripcion,$as_documento,$adec_monto)
	{
		if (trim($as_cuenta)=="")
		{
			$this->is_msg_error = "Registre la Cuenta Gasto." ;
			return false;	
		}
		
		if(trim($as_descripcion)=="")
		{
			$this->is_msg_error = "Registre la Descripción del Movimiento." ;
			return false;
		}
		
		if(trim($as_documento) =="") 
		{
			$this->is_msg_error = "Registre el Nº de documento." 	;
			return false;	
		}
		
		if($adec_monto == 0)
		{
			$this->is_msg_error = "Registre el Monto." ;	
			return false;
		} 
	
	   return true ;
	}
/**********************************************************************************************************************************/
	function uf_scg_procesar_movimiento_cmp($as_codemp,$as_procedencia,$as_comprobante,$ad_fecha,
											$as_proveedor,$as_beneficiario,$as_tipo,$as_tipo_comp,$as_sc_cuenta,
											$as_procede_doc,$as_documento,$as_operacion,$as_descripcion,$adec_monto)
	{
		$this->is_codemp     = $as_codemp;
		$this->is_procedencia= $as_procedencia;
		$this->is_comprobante= $as_comprobante;
		$this->id_fecha		 = $ad_fecha;
		$this->is_cod_prov   = $as_proveedor;
		$this->is_ced_ben    = $as_beneficiario;
		$this->is_tipo       = $as_tipo;		
	
		$this->is_comprobante = $this->io_function->uf_cerosizquierda($as_comprobante,15);
		$as_documento		  =	$this->io_function->uf_cerosizquierda($as_documento,15);
		$lb_valido=true;

		if(!$this->io_int_scg->uf_scg_select_cuenta($as_codemp,$as_sc_cuenta,&$ls_status,&$ls_denominacion))
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no existe");
			return false;
		}
		
		//- valido que sea una cuenta de movimiento
		if($ls_status!="C")
		{
			$this->io_msg->message("La cuenta ".$as_sc_cuenta." no es de movimiento");
			return false;
		}
		
		//-- verifico la Procede_Doc
		if(!$this->io_int_scg->uf_valida_procedencia($as_procede_doc,&$as_descproc))
		{
			$this->io_msg->message("La procedencia ".$as_procede_doc." no esta registrada");
			return false;
		}
		
		//-- verifico la Fecha
		if(!$this->io_fecha->uf_valida_fecha_mes($as_codemp,$ad_fecha))
		{
			$this->io_msg->message($this->int_fec->is_msg_error);
			return false;
		}

		if($this->uf_scg_select_movimiento($as_sc_cuenta,$as_procede_doc,$as_documento,$as_operacion, &$adec_monto_anterior,&$ai_orden))
		{	
			$this->io_msg->message("El Movimiento ya existe ");
			return false;
		}
		//Inicio la transacion
		if($lb_valido)
		{
			$lb_valido= $this->uf_scg_insert_movimiento( $as_sc_cuenta, $as_procede_doc, $as_documento, $as_operacion, $as_descripcion, &$adec_monto );
		}
		return $lb_valido;
	}
/**********************************************************************************************************************************/
	////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	 Function:  uf_scg_obtener_orden_movimiento
	// 	   Access:  public
	//	  Returns:  integer
	//Description:  Este método genera un numero de orden secuencial de los movimiento 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_scg_obtener_orden_movimiento()
	{
		$li_orden=0;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql = " SELECT count(*) as orden " .
					" FROM scg_dtmp_cmp " .
					" WHERE codemp='".$this->is_codemp."' AND procede= '". $this->is_procedencia ."' AND comprobante= '".$this->is_comprobante."' AND fecha='".$this->id_fecha."'";
		$rs_saldos=$this->io_sql->select($ls_sql);
		if($rs_saldos==false)
		{
		  $this->is_msg_error = "Error en el método uf_scg_obtener_orden_movimiento ".$this->io_sql->message;
		  $lb_valido=false;
		}
		else
		{
		  if($row=$this->io_sql->fetch_row($rs_saldos))  {	$li_orden=$row["orden"]; }
		}		 
		return $li_orden;
	} //fin de uf_scg_obtener_orden_movimiento()
/**********************************************************************************************************************************/
	 /////////////////////////////////////////////////////////////////////////////////////////////////////
    //    Function: uf_int_spg_delete_movimiento
    //      Access: public
    //   Arguments: $as_codemp -> codigo empresa  ; $as_procedencia -> procedencia documento ; as_comprobante -> comprobante de gasto ; $as_fecha -> fecha comprobante ;
	//              $estprog -> arreglo que contiene la estructura programatica ; $as_cuenta-> cuenta gasto ;
    //	            $as_procede_doc -< procedencia documento ; $as_documento-> documento ; $as_descripcion -> descripcion ; $as_mensaje -> mensaje ; $adec_monto-> monto operacion
    //     Returns: retorna un mensaje interno para operaciones 
    // Description: Método que elimina un movimiento de gasto por medio de la integracion en lote
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    function uf_int_spg_delete_movimiento($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_tipo,$as_fuente,$as_cod_pro,$as_ced_bene,
	                                      $estprog,$as_cuenta,$as_procede_doc,$as_documento,$as_descripcion,$as_mensaje,$as_tipo_comp,
										  $adec_monto_anterior,$adec_monto_actual,$as_sc_cuenta)
	{
	   $lb_valido=false;
	   $this->is_codemp      = $as_codemp;
	   $this->is_procedencia = $as_procedencia;
	   $this->is_comprobante = $as_comprobante;
	   $this->id_fecha       = $as_fecha;
	   $this->is_tipo=$as_tipo;
	   $this->is_fuente=$as_fuente;
	   $this->is_cod_prov=$as_cod_pro;
	   $this->is_ced_ben=$as_ced_bene;
       $ls_operacion = $this->io_int_spg->uf_operacion_mensaje_codigo($as_mensaje);
	   if(empty($ls_operacion)) { return false; }
	   if(!$this->uf_spg_select_movimiento( $estprog, $as_cuenta, $as_procede_doc, $as_documento, $ls_operacion, $lo_monto_movimiento, $lo_orden))  
	   {
          $this->io_msg->message("El movimiento no existe.");			   		  
		  return false; 	
	   }
   
       $lb_valido = $this->uf_spg_delete_movimiento($estprog, $as_cuenta, $as_procede_doc, $as_documento, $ls_operacion) ;
	   if ($lb_valido)
	   {
          $lb_valido = $this->uf_spg_comprobante_actualizar($lo_monto_movimiento,0,"C");
	   }
	   return $lb_valido;
    } // end function uf_int_spg_delete_movimiento()
/**********************************************************************************************************************************/
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_spg_delete_movimiento
	//	Arguments: as_est1...as_est5  estructura programatica del gasto as_cuenta  cuenta contable; as_procede_doc  procedenca del documento
	//             as_documento       // n° del documento; as_operacion   operacion del documento de gasto; as_descripcion	 descripcion del movimiento
	//             adec_monto         // monto del mivimiento 
	//	Returns:		lb_valido -> variable boolean
	//	Description:  Este método inserta un movimiento presupuestario en las tablas de detalle comprobante spg.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_spg_delete_movimiento($estprog, $as_cuenta, $as_procede_doc, $as_documento, $as_operacion)
	{
	   $lb_valido  = true;
       $ldt_fecha = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	   $ls_sql = " DELETE FROM spg_dtmp_cmp ".
	             " WHERE  codemp='".$this->is_codemp."' AND           ".
				 "        procede='".$this->is_procedencia."' AND     ".
				 "        comprobante='".$this->is_comprobante."' AND ".
				 "        fecha='".$ldt_fecha."' AND       ".
				 "        codestpro1='".$estprog[0]."' AND ".
				 "        codestpro2='".$estprog[1]."' AND ".
				 "        codestpro3='".$estprog[2]."' AND ".
				 "        codestpro4='".$estprog[3]."' AND ".
				 "        codestpro5='".$estprog[4]."' AND ".
				 "        estcla='".$estprog[5]."' AND     ".     // CAMBIO DEL TAMAÑO DE ESTRUCTURA PROGRAMATICA
			     "        spg_cuenta='".$as_cuenta."' AND  ".
				 "        procede_doc='".$as_procede_doc."' AND ".
				 "        documento ='".$as_documento."' AND    ".
				 "        operacion ='".$as_operacion."'";
	   $li_rows=$this->io_sql->execute($ls_sql);
	   if($li_rows===false)
	   {
	      $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
	      $this->io_msg->message($this->is_msg_error);			   		  		  
		  $lb_valido=false;
	   }
	  return $lb_valido;
	}//Fin de uf_spg_delete_movimiento
/**********************************************************************************************************************************/
	function uf_delete_all_comprobante($ls_codemp,$ls_comprobante,$ld_fecha,$ls_procedencia)
	{
	   $lb_valido=true;
	   $ld_fecha=$this->io_function->uf_convertirdatetobd($ld_fecha);
	   //Eliminacion del detalle presupuestario del comprobante
	   $ls_sql="DELETE 
				FROM spg_dtmp_mensual 
				WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
	   $li_rows=$this->io_sql->execute($ls_sql);
	   if($li_rows===false)
	   {
	      $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
	      $this->io_msg->message($this->is_msg_error);			   		  		  
		  return false;
	   }
	   else
	   {
		   $ls_sql="DELETE 
					FROM spg_dtmp_cmp 
					WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
		   $li_rows=$this->io_sql->execute($ls_sql);
		   if($li_rows===false)
		   {
			  $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
			  $this->io_msg->message($this->is_msg_error);			   		  		  
			  return false;
		   }
		   else
		   {
			   //Eliminacion del detalle Contable del comprobante
			   $ls_sql="DELETE 
						 FROM scg_dtmp_cmp 
						 WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
			   $li_rows=$this->io_sql->execute($ls_sql);
			   if($li_rows===false)
			   {
				  $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
				  $this->io_msg->message($this->is_msg_error);			   		  		  
				  return false;
			   }
			   else
			   {
				   //Eliminacion del comprobante
				   $ls_sql="DELETE 
							 FROM sigesp_cmp_md 
							 WHERE codemp='".$ls_codemp."' AND comprobante='".$ls_comprobante."' AND fecha='".$ld_fecha."' AND procede='".$ls_procedencia."'";	
				   $li_rows=$this->io_sql->execute($ls_sql);
				   if($li_rows===false)
				   {
					  $this->is_msg_error = "Error de SQL.".$this->io_function->uf_convertirmsg($this->io_sql->message);
					  $this->io_msg->message($this->is_msg_error);			   		  		  
					  return false;
				   }
				}   
			}   
		}
	   return $lb_valido;
	}
/**********************************************************************************************************************************/
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_verificar_comprobante()
	//	   Access:  public
	//	Arguments:  $as_codemp-> empresa,$as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  Método que verifica si existe o no el comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_verificar_comprobante($as_codemp,$as_procedencia,$as_comprobante)
	{
	   $lb_existe=false;
	   $ls_sql =   " SELECT comprobante ".
	               " FROM   sigesp_cmp_md ".
				   " WHERE codemp='".$as_codemp."' AND procede='".$as_procedencia."' AND comprobante='".$as_comprobante."' ";
	   $lr_result = $this->io_sql->select($ls_sql);
	   if($lr_result===false)
	   {
		  $this->is_msg_error="Error en delete Comprobante".$this->io_function->uf_convertirmsg($this->io_sql->message);
		  return false;
	   }
	   else  
	   { 
	      if($row=$this->io_sql->fetch_row($lr_result)) 
		  { 
		     $lb_existe=true;
		  }  
	  }
	  return $lb_existe;
	} // end function uf_select_comprobante
/**********************************************************************************************************************************/
function uf_load_fuentes_financiamiento($as_codemp)
{
  $ls_sql  = "SELECT codfuefin, denfuefin FROM sigesp_fuentefinanciamiento WHERE codemp='".$as_codemp."' ORDER BY codfuefin ASC";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
  {
	   $this->is_msg_error="Error.CLASS->sigesp_spg_c_mod_presupuestarias.php.-Método->uf_load_fuentes_financiamiento ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_data;
}
/**********************************************************************************************************************************/
function uf_load_unidades_administradoras($as_codemp)
{
  $ls_sql   = "SELECT coduac, denuac FROM spg_ministerio_ua WHERE codemp='".$as_codemp."' ORDER BY coduac ASC";
  $rs_datos = $this->io_sql->select($ls_sql);
  if ($rs_datos===false)
  {
	   $this->is_msg_error="Error.CLASS->sigesp_spg_c_mod_presupuestarias.php.-Método->uf_load_unidades_administradoras ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_datos;
}
//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_sigespcmpmd($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_sigespcmpmd()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	
	/*$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto", $ad_monto);

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp_md",$this->li_candeccon,$this->li_tipconmon,
	                                                 $this->li_redconmon,$aa_seguridad);*/
	return $lb_valido;
}//uf_update_bsf_sigespcmp
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_spgdtmpcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codestpro,
                                  $as_spg_cuenta,$as_procede_doc,$as_documento,$as_operacion,$aa_seguridad)
{
    ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function: uf_update_bsf_spgdtcmp()                                   
	//	     Arguments:   
	//	       Returns: True si es correcto o false es otro caso                  
	//	   Description: Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por : Ing. Yozelin Barragán                                 
	// Fecha Creación : 24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codestpro1=$as_codestpro[0];
	$ls_codestpro2=$as_codestpro[1];
	$ls_codestpro3=$as_codestpro[2];
	$ls_codestpro4=$as_codestpro[3];
	$ls_codestpro5=$as_codestpro[4];
	$ls_estcla=$as_codestpro[5];  // CAMBIO TAMAÑO DE LA ESTRUCTURA PROGRAMATICA

	/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ad_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","estcla");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_estcla);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_spg_cuenta);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede_doc);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","operacion");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_operacion);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,
	                                                 $this->li_redconmon,$aa_seguridad);*/
	return $lb_valido;
}//uf_update_bsf_spgdtcmp
//---------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------
function uf_convertir_sigespcmpmd($as_codemp,$aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_convertir_sigespcmpmd
	//		   Access: private
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que selecciona los campos de moneda de la tabla sigesp_cmp_md e inserta el valor convertido
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creación: 26/07/2007 								Fecha Última Modificación : 07/08/2007
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT codemp, procede, comprobante, fecha, total ".
			" FROM   sigesp_cmp_md   ".
			" WHERE  codemp='".$as_codemp."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{ 
		$this->io_mensajes->message("CLASE->sigesp_rcm_c_cfg MÉTODO->SELECT->uf_convertir_sigespcmpmd ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		$la_seguridad="";
		while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
		{
			$ls_codemp = $row["codemp"]; 
			$ls_procede = $row["procede"];
			$ls_comprobante = $row["comprobante"]; 
			$ldt_fecha = $row["fecha"];
			$ld_total = $row["total"];

			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_total);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_procede);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_comprobante);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ldt_fecha);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp_md",$this->li_candeccon,$this->li_tipconmon,
			                                                 $this->li_redconmon,$aa_seguridad);*/
		}
	}		
	return $lb_valido;
}// end function uf_convertir_sigespcmpmd
//-----------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_scgdtcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$adt_fecha,
                                $as_cuenta,$as_procede_doc,$as_documento,$as_debhab,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_scgdtcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	/*$this->io_rcbsf->io_ds_datos->insertRow("campo","montoaux");
	$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_monto);
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_comprobante);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$adt_fecha);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","sc_cuenta");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_cuenta);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede_doc");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_procede_doc);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","documento");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_documento);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$this->io_rcbsf->io_ds_filtro->insertRow("filtro","debhab");
	$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_debhab);
	$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
	$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("scg_dtmp_cmp",$this->li_candeccon,$this->li_tipconmon,
													 $this->li_redconmon,$aa_seguridad);*/
	return $lb_valido;
}//uf_update_bsf_scgdtcmp

function uf_get_nomestructura($as_codemp)
{
  $as_nomunidad = "";
  $ls_sql  = "SELECT estmodest, nomestpro3, nomestpro5 FROM sigesp_empresa WHERE codemp='".$as_codemp."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
  {
	   $this->is_msg_error="Error.CLASS->sigesp_spg_c_mod_presupuestarias.php.-Método->uf_load_nomestructura ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  else
  {
   if($row=$this->io_sql->fetch_row($rs_data))
   {
    $li_estmodest = $row["estmodest"];
	if ($li_estmodest == 1)
	{
	 $as_nomunidad = $row["nomestpro3"];
	}
	else
	{
	  $as_nomunidad = $row["nomestpro5"];
	}
   }
  }
  return $as_nomunidad;
}
//---------------------------------------------------------------------------------------------------------------------------------

function uf_select_comprobantes_spg($as_codcmpdes,$as_codcmphas,$ad_fecha,$ad_fecaprsol,$as_concepto,$as_procede,$ai_estatus,&$ao_object,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobantes_spg
		//		   Access: public
		//	    Arguments: as_comprobante  // Número de Comprobante
		//				   as_procede  // Procede
		//				   ad_fecha  // Fecha del Comprobante
		//				   ai_estatus  // Estatus de Contabilización
		//				   ao_object  // Arreglo de objetos
		//				   ai_totrows  // total del Filas
		//	      Returns: lb_valido True si se ejecuto la contabilización correctamente
		//	  Description: Funcion que se encarga de retornar los comprobantes de modificaciones 
		//				   presupuestarias
		//    Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totrows=0;
		$lb_valido=true;
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_criterio="";
		if($ad_fecha!="")
		{
			$ls_criterio=$ls_criterio." AND sigesp_cmp.fecha ='".$this->io_function->uf_convertirdatetobd($ad_fecha)."'";
		}
		if($ad_fecaprsol!="")
		{
			$ls_criterio=$ls_criterio." AND sigesp_cmp_md.fechaconta ='".$this->io_function->uf_convertirdatetobd($ad_fecaprsol)."'";
		}
		
		if(($as_codcmpdes!="")&&($as_codcmphas!=""))
		{
			$ls_criterio=$ls_criterio." AND sigesp_cmp.comprobante between '".$as_codcmpdes."' AND '".$as_codcmphas."' ";
		}
		
		if($as_concepto!="")
		{
			$ls_criterio=$ls_criterio." AND upper(sigesp_cmp.descripcion) like '%".strtoupper($as_concepto)."%'";
		}
		
		if($as_procede!="")
		{
			$ls_criterio=$ls_criterio." AND sigesp_cmp.procede like '%SPG".$as_procede."%'";
		}
		else
		{
			$ls_criterio=$ls_criterio." AND sigesp_cmp.procede like '%SPG%'";		
		}
		$ls_sql = "SELECT sigesp_cmp.comprobante, sigesp_cmp.fecha, sigesp_cmp.procede, sigesp_cmp.descripcion, 
		                  sigesp_cmp_md.fechaconta, sigesp_cmp_md.fechaanula, sigesp_cmp.codban, sigesp_cmp.ctaban
				     FROM sigesp_cmp, sigesp_cmp_md
				    WHERE sigesp_cmp.codemp = '".$ls_codemp."'
				      AND sigesp_cmp.tipo_comp = 2
				      AND sigesp_cmp_md.estapro = ".$ai_estatus."
				      AND sigesp_cmp.esttrfcmp = 0 $ls_criterio
				 	  AND sigesp_cmp.codemp = sigesp_cmp_md.codemp
		              AND sigesp_cmp.comprobante = sigesp_cmp_md.comprobante
					  AND sigesp_cmp.fecha = sigesp_cmp_md.fechaconta
					  AND sigesp_cmp.procede = sigesp_cmp_md.procede
			        ORDER BY sigesp_cmp.fecha, sigesp_cmp.comprobante";
		$rs_data = $this->io_sql->select($ls_sql);
		
		if($rs_data===false)
		{
			$lb_valido=false;
            $this->io_msg->message("CLASE->Modificaciones_Presupuestarias MÉTODO->uf_select_comprobantes_spg ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));			
		}
		else
		{
		   $li_numrows=$this->io_sql->num_rows($rs_data);
		   if($li_numrows > 0)
		   {
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_comprobante=rtrim($row["comprobante"]);
				$ld_fecha=$this->io_function->uf_formatovalidofecha($row["fecha"]);
				$ld_fecha=$this->io_function->uf_convertirfecmostrar($ld_fecha);
				$ls_procede=rtrim($row["procede"]);
				$ls_descripcion=rtrim($row["descripcion"]);				
				$ls_fecaprsol=$this->io_function->uf_formatovalidofecha($row["fechaconta"]);
				$ls_fecaprsol=$this->io_function->uf_convertirfecmostrar($ls_fecaprsol);
				$ls_codban = $row["codban"];
				$ls_ctaban = $row["ctaban"];				
				$ao_object[$ai_totrows][1] = "<input type=checkbox name=selusu".$ai_totrows." 		  id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");' ><input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>";
				$ao_object[$ai_totrows][2] = "<input type=text     name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows." value='".$ls_comprobante."' class=sin-borde readonly style=text-align:center size=17 maxlength=15><input name=hidcodban".$ai_totrows." id=hidcodban".$ai_totrows." type=hidden  value='".$ls_codban."' readonly>";
				$ao_object[$ai_totrows][3] ="<input  type=text 	   name=txtprocede".$ai_totrows."     id=txtprocede".$ai_totrows."     value='".$ls_procede."'     class=sin-borde readonly style=text-align:center size=15 maxlength=12><input name=hidctaban".$ai_totrows." id=hidctaban".$ai_totrows." type=hidden  value='".$ls_ctaban."' readonly>";
				$ao_object[$ai_totrows][4] = "<input type=text 	   name=txtfecha".$ai_totrows."       id=txtfecha".$ai_totrows."       value='".$ld_fecha."'       class=sin-borde readonly style=text-align:center size=20 maxlength=10>";
				$ao_object[$ai_totrows][5] = "<input type=text 	   name=txtfecaprsol".$ai_totrows."   id=txtfecaprsol".$ai_totrows."   value='".$ls_fecaprsol."'   class=sin-borde readonly style=text-align:center size=20 maxlength=10>";
				$ao_object[$ai_totrows][6] = "<input type=text 	   name=txtconcepto".$ai_totrows."    id=txtconcepto".$ai_totrows."    value='".$ls_descripcion."' class=sin-borde readonly style=text-align:left   size=80 maxlength=250 title='".$ls_descripcion."'>";			
				$ao_object[$ai_totrows][7] = "<div align='center'><a href=javascript:uf_verdetalle('".str_replace(" ","___",$ls_comprobante)."','".$ls_procede."');><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></a></div>";
											 
			}
			$this->io_sql->free_result($rs_data);
		   }
		   else
		   {
		    $ai_totrows=$ai_totrows+1;
		    $ao_object[$ai_totrows][1]=  "<input type=checkbox name=selusu".$ai_totrows." 		  id=selusu".$ai_totrows." onChange='javascript: cambiar_valor(".$ai_totrows.");' ><input name=txtselusu".$ai_totrows." type=hidden id=txtselusu".$ai_totrows." readonly>";
			$ao_object[$ai_totrows][2] = "<input type=text     name=txtcomprobante".$ai_totrows." id=txtcomprobante".$ai_totrows." value=''  class=sin-borde readonly style=text-align:center size=17 maxlength=15><input name=hidcodban".$ai_totrows." id=hidcodban".$ai_totrows." type=hidden  value='' readonly>";
			$ao_object[$ai_totrows][3] = "<input type=hidden   name=txtprocede".$ai_totrows."     id=txtprocede".$ai_totrows."     value=''  class=sin-borde readonly style=text-align:center size=15 maxlength=12><input name=hidctaban".$ai_totrows." id=hidctaban".$ai_totrows." type=hidden  value='' readonly>";
			$ao_object[$ai_totrows][4] = "<input type=text 	   name=txtfecha".$ai_totrows."       id=txtfecha".$ai_totrows."       value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=10>";
			$ao_object[$ai_totrows][5] = "<input type=text     name=txtfecaprsol".$ai_totrows."   id=txtfecaprsol".$ai_totrows."   value=''  class=sin-borde readonly style=text-align:center size=20 maxlength=10>";
			$ao_object[$ai_totrows][6] = "<input type=text     name=txtconcepto".$ai_totrows."    id=txtconcepto".$ai_totrows."	   value=''  class=sin-borde readonly style=text-align:left   size=80 maxlength=250>";			
			$ao_object[$ai_totrows][7] = "<div align='center'><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></div>";
										 
		   
		   }
		}		
		return $lb_valido;
	}// end function uf_select_comprobantes_spg
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	function uf_cargar_bddestino()
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_cargar_bddestino()
	//	   Access:  public
	//	  Returns:	rs_data
	//Description:  Método que devuelve los nombres de las Base de Datos para la Consolidación
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $ls_sql =   " SELECT nombasdat ".
	               " FROM sigesp_consolidacion ".
				   " ORDER BY codestpro1 ";		   
	   $rs_data = $this->io_sql->select($ls_sql);
	   if($rs_data===false)
	   {
		  $this->is_msg_error="Error en Select de Base de Datos para Consolidacion".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   }

	  return $rs_data;
	} // end function uf_cargar_comprobante
/**********************************************************************************************************************************/
//-------------------------------------------------------------------------------------------------------------------------------
    function uf_update_estatus($as_comprobante,$as_fecha,$as_fecconta,$as_procede)
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	 Function:  uf_update_estatus
	//	   Access:  public
	//	Arguments:  $as_procedencia->procedencia,$as_comprobante->comprobante,$as_fecha
	//	  Returns:	booleano lb_existe
	//Description:  actuliza el estatus del comprobante
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido = false;		 
		 $ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		 $ls_sql="  UPDATE  sigesp_cmp                        ".
		         "     SET  esttrfcmp = 1                     ".
                 "   WHERE codemp='".$this->li_codemp."'      ".
                 "     AND procede='".$as_procede."'          ".
                 "     AND comprobante='".$as_comprobante."'  ".
	             "     AND fecha = '".$ls_fecha."'            "; 
			   $rs_data = $this->io_sql->execute($ls_sql);
			   if($rs_data===false)
			   {
				  $this->is_msg_error="Error en Clase->uf_update_estatus".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);
			   }
			   else  
			   { 			  
				  $lb_valido =true;				 
			   }		
		 return $lb_valido;
	}//fin uf_reversar_transferencia_comprobantes
//---------------------------------------------------------------------------------------------------------------------------------

	function uf_cerrar_presupuesto($ai_cerrar)
	{
		  $lb_valido=false;
		  $ls_sql    = " UPDATE sigesp_empresa set estciespg = ".$ai_cerrar.", estciespi = ".$ai_cerrar."  where codemp = '".$this->li_codemp."'";
		  $li_result = $this->io_sql->execute($ls_sql);
		  if($li_result===false)
		  {
		    $this->is_msg_error="Error en Transferencia->uf_cerrar_presupuesto".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);					   
		  }
		  else  
		  {  
			$lb_valido = true;
		  }

	 return $lb_valido;
	}//fin de uf_cerrar_presupuesto
	
	function uf_obtener_validacion_spg($as_codemp,&$ai_chkvalidacion,&$as_ctaspgrec,&$as_ctaspgced) 
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Metodo:  uf_obtener_validacion_spg
//	          Access:  public
//	       Arguments: 
//       $as_codemp :  Código de la Empresa
//	         Returns:  $lb_valido.
//	     Description:  Función que se encarga de obtener los valores de la validacion
//                     para los Traspasos Presupuestarios 
//     Elaborado Por:  Ing. Arnaldo Suárez.
// Fecha de Creación:  10/09/2008       Fecha Última Actualización:
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
  $ls_sql = " SELECT   estvalspg, ctaspgrec, ctaspgced FROM sigesp_empresa ".
            "   WHERE codemp = '".$as_codemp."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
	 {
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_SPG_C_VALIDACIONES; METODO->uf_obtener_validacion; ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	 } 
  else
	 {
	  	while(!$rs_data->EOF)
		{
		 $ai_chkvalidacion = $rs_data->fields["estvalspg"];
		 $as_ctaspgced     = $rs_data->fields["ctaspgced"];
		 $as_ctaspgrec     = $rs_data->fields["ctaspgrec"];
		 $rs_data->MoveNext();
		}   
       $lb_valido=true;
	 }
return $lb_valido;
}// fin de la funcion
//////-------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_tipos($as_codemp)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_buscar_tipos
	//	          Access:  public
	//	       Arguments: 
	//       $as_codemp :  Código de la Empresa
	//	         Returns:  $lb_valido.
	//	     Description:	                     
	//     Elaborado Por:  Ing. Jennifer Rivero
	// Fecha de Creación:  21/11/2008       Fecha Última Actualización:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_valor=0;
		$ls_sql=" select spg_tipomodificacion.* ".
		        "   from spg_tipomodificacion   ".
				"   where codtipmodpre='".$as_codemp."'";
				
		$rs_data = $this->io_sql->select($ls_sql);
  		if ($rs_data===false)
		{
	   		$lb_valido=false;
	   		$this->io_msg->message("CLASE->sigesp_c_mod_presupuestaria; METODO->uf_buscar_tipos; ERROR->".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
	    } 
        else
		{
			  if($row=$this->io_sql->fetch_row($rs_data)) 
			  { 
				 $ls_valor=1;
			  }  
		}//fin del else	
		return 	$ls_valor;		
	}//fin de la funcion uf_buscar_tipos
///--------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------
   function uf_update_tipo($as_codemp,$as_tipo)
   {
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_update_tipo
	//	          Access:  public
	//	       Arguments: 
	//       $as_codemp :  Código de la Empresa
	//	         Returns:  $lb_valido.
	//	     Description:	                     
	//     Elaborado Por:  Ing. Jennifer Rivero
	// Fecha de Creación:  21/11/2008       Fecha Última Actualización:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		  $lb_valido=true;
		  $ls_sql= " select cast (contipmodpre as int) + '1' as valor ".
		           "   from spg_tipomodificacion ".
				   "   where codemp      = '".$as_codemp."'".
				   "     and codtipmodpre='".$as_tipo."'";
				   
		  $result = $this->io_sql->select($ls_sql);
		  if($result===false)
		  {
		    $this->is_msg_error="Error en el Metodo uf_update_tipo".
				                 $this->io_function->uf_convertirmsg($this->io_sql->message);					   
		  }
		  else  
		  { 			
			  if($row=$this->io_sql->fetch_row($result)) 
			  { 
					$ls_valor=str_pad($row["valor"],12,"0",0); 
					
					$ls_sql    = " UPDATE spg_tipomodificacion ".
							   "    set contipmodpre = '".$ls_valor."'  ".
							   "   where codemp      = '".$as_codemp."'".
							   "     and codtipmodpre='".$as_tipo."'"; 
							  // print "entro";
					$li_result = $this->io_sql->execute($ls_sql);
					
					if($li_result===false)
					{
						$this->is_msg_error="Error en el Metodo uf_update_tipo".
											 $this->io_function->uf_convertirmsg($this->io_sql->message);					   
					}
					else  
					{  
						 $lb_valido=false;
					}
			  }//fin del if
		  }
	 return $lb_valido;
   }// fin uf_update_tipo
 //////----------------------------------------------------------------------------------------------------------------------------------
 
 function uf_sigesp_existe_tipmodpre($as_codtipmodpre)
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_sigesp_existe_tipmodpre
	//	          Access:  public
	//	       Arguments: 
	//       $as_codtipmodpre :  Código del Tipo de Modificacion Presupuestaria
	//	         Returns:  $lb_valido.
	//	     Description:	                     
	//     Elaborado Por:  Ing. Arnaldo Suárez
	// Fecha de Creación:  30/12/2008       Fecha Última Actualización:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql=" Select * ".
		        "   from spg_tipomodificacion   ".
				"   where codemp = '".$this->li_codemp."'".
				"   and codtipmodpre = '".$as_codtipmodpre."'";
				
		$rs_data = $this->io_sql->select($ls_sql);
  		if ($rs_data===false)
		{
	   		$lb_valido=false;
	   		$this->io_msg->message("CLASE->sigesp_c_mod_presupuestaria; METODO->uf_sigesp_existe_tipmodpre; ERROR->".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
	    } 
        else
		{
			  if($row=$this->io_sql->fetch_row($rs_data)) 
			  { 
				 $lb_existe=true;
			  }  
		}//fin del else	
		return 	$lb_existe;		
	}//fin de la funcion uf_sigesp_existe_tipmodpre
///--------------------------------------------------------------------------------------------------------------------------------------
 
 
 function uf_sigesp_insert_tipmodpre($as_codtipmodpre,$as_dentipmodpre,$as_pretipmodpre,$as_contipmodpre)
 {  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_sigesp_insert_tipmodpre
	//	          Access:  public
	//	       Arguments: $as_codtipmodpre : Código del Tipo de Modificacion Presupuestaria
	//                    $as_dentipmodpre : Denominación del Tipo de Modificacion Presupuestaria   
	//                    $as_pretipmodpre : Prefijo del Tipo de Modificacion Presupuestaria
	//                    $as_contipmodpre : Contador del Tipo de Modificacion Presupuestaria
	//	         Returns:  $lb_valido.
	//	     Description:	                     
	//     Elaborado Por:  Ing. Arnaldo Suárez
	// Fecha de Creación:  30/12/2008       Fecha Última Actualización:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		  $lb_valido=false;
		  $ls_sql    = " INSERT INTO spg_tipomodificacion(codemp, codtipmodpre, dentipmodpre, pretipmodpre, contipmodpre) ".
    				   "       VALUES ('".$this->li_codemp."','".$as_codtipmodpre."','".$as_dentipmodpre."','".$as_pretipmodpre."','".$as_contipmodpre."')";
		  $li_result = $this->io_sql->execute($ls_sql);
		  if($li_result===false)
		  {
		    $this->is_msg_error="Error en Transferencia->uf_sigesp_insert_tipmodpre".
				                       $this->io_function->uf_convertirmsg($this->io_sql->message);					   
		  }
		  else  
		  {  
			$lb_valido = true;
		  }

	 return $lb_valido;
	}//fin de uf_sigesp_insert_tipmodpre

	function uf_sigesp_existe_detalle_reverso($as_estpro,$as_cuenta,$as_procede_doc,$as_documento,$as_operacion,$as_codfuefin="--")
	{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_sigesp_existe_detalle_reverso
	//	          Access:  public
	//	       Arguments: 
	//       $as_codtipmodpre :  Código del Tipo de Modificacion Presupuestaria
	//	         Returns:  $lb_valido.
	//	     Description:	Valida que no exista un detalle presupuestario de reverso para una misma cuenta pero
	//                      para una operacion de Aumento o Dismunucion en un comprobante                                     
	//     Elaborado Por:  Ing. Arnaldo Suárez
	// Fecha de Creación:  06/01/2009       Fecha Última Actualización:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_operacion_reverso = "";
		if(trim($as_operacion) == "AU")
		{
		 $ls_operacion_reverso = "DI";
		}
		elseif(trim($as_operacion) == "DI")
		{
		  $ls_operacion_reverso = "AU";
		}
		$ld_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
		
		$ls_sql=" SELECT * ".
		        "   FROM spg_dtmp_cmp   ".
				"   WHERE codemp = '".$this->is_codemp."'".
				"   AND procede = '".$this->is_procedencia."'".
				"   AND operacion = '".$ls_operacion_reverso."'".
				"   AND comprobante = '".$this->is_comprobante."'".
				"   AND fecha = '".$ld_fecha."'".
				"   AND codestpro1 = '".$as_estpro[0]."'".
				"   AND codestpro2 = '".$as_estpro[1]."'".
				"   AND codestpro3 = '".$as_estpro[2]."'".
				"   AND codestpro4 = '".$as_estpro[3]."'".
				"   AND codestpro5 = '".$as_estpro[4]."'".
				"   AND estcla = '".$as_estpro[5]."'".
				"   AND spg_cuenta = '".$as_cuenta."'".
				"   AND codfuefin = '".$as_codfuefin."'".
				"   AND procede_doc = '".$as_procede_doc."'".
				"   AND documento = '".$as_documento."'";
		$rs_data = $this->io_sql->select($ls_sql);
  		if ($rs_data===false)
		{
	   		$lb_valido=false;
	   		$this->io_msg->message("CLASE->sigesp_c_mod_presupuestaria; METODO->uf_sigesp_existe_detalle_reverso; ERROR->".
			                       $this->io_function->uf_convertirmsg($this->io_sql->message));
	    } 
        else
		{
			  if($row=$this->io_sql->fetch_row($rs_data)) 
			  { 
				 $lb_existe=true;
			  }  
		}//fin del else	
		return 	$lb_existe;		
	}//fin de la funcion uf_sigesp_existe_detalle_reverso
///--------------------------------------------------------------------------------------------------------------------------------------
 
 
 function uf_load_fuentes_financiamiento_estructura($as_estpro,$as_codigo="%%",$as_denominacion="%%")
 {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	          Metodo:  uf_load_fuentes_financiamiento_estructura
	//	          Access:  public
	//	       Arguments:  $as_estpro :  Estructura Presupuestaria
	//	         Returns:  $lb_valido.
	//	     Description:  Carga las Fuentes de Financiamiento dada la Est. Pre y la Cuenta                            
	//     Elaborado Por:  Ing. Arnaldo Suárez
	// Fecha de Creación:  06/01/2009       Fecha Última Actualización:
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $ls_sql  = " SELECT distinct spg_dt_fuentefinanciamiento.codfuefin, sigesp_fuentefinanciamiento.denfuefin ".
             "  FROM spg_dt_fuentefinanciamiento, sigesp_fuentefinanciamiento, spg_cuenta_fuentefinanciamiento ".
             " WHERE spg_dt_fuentefinanciamiento.codemp = sigesp_fuentefinanciamiento.codemp ".
             "       AND spg_dt_fuentefinanciamiento.codfuefin = sigesp_fuentefinanciamiento.codfuefin ".
			 "       AND spg_dt_fuentefinanciamiento.codemp = '".$this->li_codemp."'".
			 "       AND spg_dt_fuentefinanciamiento.codestpro1 = '".$this->io_function->uf_cerosizquierda($as_estpro[0],25)."'".
			 "    	 AND spg_dt_fuentefinanciamiento.codestpro2 = '".$this->io_function->uf_cerosizquierda($as_estpro[1],25)."'".
			 "    	 AND spg_dt_fuentefinanciamiento.codestpro3 = '".$this->io_function->uf_cerosizquierda($as_estpro[2],25)."'".
			 "    	 AND spg_dt_fuentefinanciamiento.codestpro4 = '".$this->io_function->uf_cerosizquierda($as_estpro[3],25)."'".
			 "    	 AND spg_dt_fuentefinanciamiento.codestpro5 = '".$this->io_function->uf_cerosizquierda($as_estpro[4],25)."'".
			 "    	 AND spg_dt_fuentefinanciamiento.estcla = '".$as_estpro[5]."'".
			 "		 AND spg_dt_fuentefinanciamiento.codemp = spg_cuenta_fuentefinanciamiento .codemp ".
			 "		 AND spg_dt_fuentefinanciamiento.codestpro1 = spg_cuenta_fuentefinanciamiento .codestpro1 ".
			 "		 AND spg_dt_fuentefinanciamiento.codestpro2 = spg_cuenta_fuentefinanciamiento .codestpro2 ".
		 	 "		 AND spg_dt_fuentefinanciamiento.codestpro3 = spg_cuenta_fuentefinanciamiento .codestpro3 ".
			 "		 AND spg_dt_fuentefinanciamiento.codestpro4 = spg_cuenta_fuentefinanciamiento .codestpro4 ".
			 "		 AND spg_dt_fuentefinanciamiento.codestpro5 = spg_cuenta_fuentefinanciamiento .codestpro5 ".
			 "		 AND spg_dt_fuentefinanciamiento.estcla     = spg_cuenta_fuentefinanciamiento .estcla ".
			 "       AND sigesp_fuentefinanciamiento.codemp = spg_cuenta_fuentefinanciamiento .codemp ".
			 "		 AND sigesp_fuentefinanciamiento.codfuefin = spg_cuenta_fuentefinanciamiento .codfuefin ".
			 "		 AND spg_cuenta_fuentefinanciamiento.spg_cuenta = '".$as_estpro[6]."'".
			 "       AND spg_dt_fuentefinanciamiento.codfuefin like '".$as_codigo."'".
			 "       AND sigesp_fuentefinanciamiento.denfuefin like '".$as_denominacion."'";	 		 
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
  {
	   $this->is_msg_error="Error.CLASS->sigesp_spg_c_mod_presupuestarias.php.-Método->uf_load_fuentes_financiamiento_estructura ".$this->io_function->uf_convertirmsg($this->io_sql->message);
	   $this->io_msg->message($this->is_msg_error);
	   return false;
  }
  return $rs_data;
}
//--------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_mp_mensual($as_procede,$as_comprobante,$ad_fecha,&$ls_estapro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_select_mp_mensual
		//	          Access:  public
		//	       Arguments: 
		//       $as_codtipmodpre :  Código del Tipo de Modificacion Presupuestaria
		//	         Returns:  $lb_valido.
		//	     Description:	Valida que no exista un detalle presupuestario de reverso para una misma cuenta pero
		//                      para una operacion de Aumento o Dismunucion en un comprobante                                     
		//     Elaborado Por:  Ing. Arnaldo Suárez
		// Fecha de Creación:  06/01/2009       Fecha Última Actualización:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_estapro="";
		$ls_sql=" SELECT estapro ".
				"   FROM sigesp_cmp_md   ".
				"   WHERE codemp = '".$this->li_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->sigesp_c_mod_presupuestaria; METODO->uf_select_mp_mensual; ERROR->".
								   $this->io_function->uf_convertirmsg($this->io_sql->message));
		} 
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estapro=$row["estapro"];
			}
		}//fin del else	
		return $lb_valido;
	}//fin de la funcion uf_select_dtmp_mensual
//--------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_dtmp_mensual($as_procede,$as_comprobante,$ad_fecha,$as_codestpro1,$as_codestpro2,$as_codestpro3,
									$as_codestpro4,$as_codestpro5,$as_estcla,$as_cuentaspg,$as_operacion)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_select_dtmp_mensual
		//	          Access:  public
		//	       Arguments: 
		//       $as_codtipmodpre :  Código del Tipo de Modificacion Presupuestaria
		//	         Returns:  $lb_valido.
		//	     Description:	Valida que no exista un detalle presupuestario de reverso para una misma cuenta pero
		//                      para una operacion de Aumento o Dismunucion en un comprobante                                     
		//     Elaborado Por:  Ing. Arnaldo Suárez
		// Fecha de Creación:  06/01/2009       Fecha Última Actualización:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$rs_data="";
		$ls_sql=" SELECT * ".
				"   FROM spg_dtmp_mensual   ".
				"   WHERE codemp = '".$this->li_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND operacion = '".$as_operacion."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codestpro1 = '".$as_codestpro1."'".
				"   AND codestpro2 = '".$as_codestpro2."'".
				"   AND codestpro3 = '".$as_codestpro3."'".
				"   AND codestpro4 = '".$as_codestpro4."'".
				"   AND codestpro5 = '".$as_codestpro5."'".
				"   AND estcla = '".$as_estcla."'".
				"   AND spg_cuenta = '".$as_cuentaspg."'".
				"   AND procede_doc = '".$as_procede."'".
				"   AND documento = '".$as_comprobante."'";
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			return false;
			$this->io_msg->message("CLASE->sigesp_c_mod_presupuestaria; METODO->uf_select_dtmp_mensual; ERROR->".
								   $this->io_function->uf_convertirmsg($this->io_sql->message));
		} 
		return 	$rs_data;		
	}//fin de la funcion uf_select_dtmp_mensual
//--------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
	 function uf_insert_mp_mensual($as_procede,$as_comprobante,$ad_fecha,$as_codestpro1,$as_codestpro2,$as_codestpro3,
								   $as_codestpro4,$as_codestpro5,$as_estcla,$as_cuentaspg,$as_operacion,$ai_enero,$ai_febrero,
								   $ai_marzo,$ai_abril,$ai_mayo,$ai_junio,$ai_julio,$ai_agosto,$ai_septiembre,$ai_octubre,
								   $ai_noviembre,$ai_diciembre,$aa_seguridad)
	 {  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_insert_mp_mensual
		//	          Access:  public
		//	       Arguments: $as_codtipmodpre : Código del Tipo de Modificacion Presupuestaria
		//                    $as_dentipmodpre : Denominación del Tipo de Modificacion Presupuestaria   
		//                    $as_pretipmodpre : Prefijo del Tipo de Modificacion Presupuestaria
		//                    $as_contipmodpre : Contador del Tipo de Modificacion Presupuestaria
		//	         Returns:  $lb_valido.
		//	     Description:	                     
		//     Elaborado Por:  Ing. Arnaldo Suárez
		// Fecha de Creación:  30/12/2008       Fecha Última Actualización:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ai_enero=    str_replace(".","",$ai_enero);
		$ai_enero=    str_replace(",",".",$ai_enero);
		$ai_febrero=    str_replace(".","",$ai_febrero);
		$ai_febrero=    str_replace(",",".",$ai_febrero);
		$ai_marzo=    str_replace(".","",$ai_marzo);
		$ai_marzo=    str_replace(",",".",$ai_marzo);
		$ai_abril=    str_replace(".","",$ai_abril);
		$ai_abril=    str_replace(",",".",$ai_abril);
		$ai_mayo=    str_replace(".","",$ai_mayo);
		$ai_mayo=    str_replace(",",".",$ai_mayo);
		$ai_junio=    str_replace(".","",$ai_junio);
		$ai_junio=    str_replace(",",".",$ai_junio);
		$ai_julio=    str_replace(".","",$ai_julio);
		$ai_julio=    str_replace(",",".",$ai_julio);
		$ai_agosto=    str_replace(".","",$ai_agosto);
		$ai_agosto=    str_replace(",",".",$ai_agosto);
		$ai_septiembre=    str_replace(".","",$ai_septiembre);
		$ai_septiembre=    str_replace(",",".",$ai_septiembre);
		$ai_octubre=    str_replace(".","",$ai_octubre);
		$ai_octubre=    str_replace(",",".",$ai_octubre);
		$ai_noviembre=    str_replace(".","",$ai_noviembre);
		$ai_noviembre=    str_replace(",",".",$ai_noviembre);
		$ai_diciembre=    str_replace(".","",$ai_diciembre);
		$ai_diciembre=    str_replace(",",".",$ai_diciembre);
		$ls_sql="INSERT INTO spg_dtmp_mensual(codemp, procede, comprobante, fecha, codestpro1, codestpro2, codestpro3,".
				"                             codestpro4, codestpro5, estcla, spg_cuenta, procede_doc, documento, operacion,".
				" 							  enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre,".
				"							  noviembre, diciembre) ".
				"  VALUES ('".$this->li_codemp."','".$as_procede."','".$as_comprobante."','".$ad_fecha."','".$as_codestpro1."',".
				"          '".$as_codestpro2."','".$as_codestpro3."','".$as_codestpro4."','".$as_codestpro5."',".
				"          '".$as_estcla."','".$as_cuentaspg."','".$as_procede."','".$as_comprobante."','".$as_operacion."',".
				"          ".$ai_enero.",".$ai_febrero.",".$ai_marzo.",".$ai_abril.",".$ai_mayo.",".$ai_junio.",".$ai_julio.",".
				"          ".$ai_agosto.",".$ai_septiembre.",".$ai_octubre.",".$ai_noviembre.",".$ai_diciembre.")";
		$li_result = $this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
		  	$lb_valido=false;
			$this->is_msg_error="sigesp_c_mod_presupuestaria->uf_insert_mp_mensual".
									   $this->io_function->uf_convertirmsg($this->io_sql->message);					   
		}
		else  
		{  
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la distribucion mensual del comprobante ".$as_comprobante."  de procede ".$as_procede." Asociado a la empresa ".$this->li_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		 return $lb_valido;
	}//fin de uf_sigesp_insert_tipmodpre
//--------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_dtmp_mensual($as_procede,$as_comprobante,$ad_fecha,$as_codestpro1,$as_codestpro2,$as_codestpro3,
									$as_codestpro4,$as_codestpro5,$as_estcla,$as_cuentaspg,$as_operacion,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_select_dtmp_mensual
		//	          Access:  public
		//	       Arguments: 
		//       $as_codtipmodpre :  Código del Tipo de Modificacion Presupuestaria
		//	         Returns:  $lb_valido.
		//	     Description:	Valida que no exista un detalle presupuestario de reverso para una misma cuenta pero
		//                      para una operacion de Aumento o Dismunucion en un comprobante                                     
		//     Elaborado Por:  Ing. Arnaldo Suárez
		// Fecha de Creación:  06/01/2009       Fecha Última Actualización:
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE FROM spg_dtmp_mensual   ".
				" WHERE codemp = '".$this->li_codemp."'".
				"   AND procede = '".$as_procede."'".
				"   AND operacion = '".$as_operacion."'".
				"   AND comprobante = '".$as_comprobante."'".
				"   AND fecha = '".$ad_fecha."'".
				"   AND codestpro1 = '".$as_codestpro1."'".
				"   AND codestpro2 = '".$as_codestpro2."'".
				"   AND codestpro3 = '".$as_codestpro3."'".
				"   AND codestpro4 = '".$as_codestpro4."'".
				"   AND codestpro5 = '".$as_codestpro5."'".
				"   AND estcla = '".$as_estcla."'".
				"   AND spg_cuenta = '".$as_cuentaspg."'".
				"   AND procede_doc = '".$as_procede."'".
				"   AND documento = '".$as_comprobante."'";
		$li_result = $this->io_sql->execute($ls_sql);
		if($li_result===false)
		{
			$lb_valido=true;
			$this->is_msg_error="sigesp_c_mod_presupuestaria->uf_delete_dtmp_mensual".
									   $this->io_function->uf_convertirmsg($this->io_sql->message);					   
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó la distribucion mensual del comprobante ".$as_comprobante."  de procede ".$as_procede." Asociado a la empresa ".$this->li_codemp;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}//fin de la funcion uf_select_dtmp_mensual
//-----------------------------------------------------------------------------------------------------------------------------------
	
}//fin de la clase
?>