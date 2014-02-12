<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
/*require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");*/
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
class sigesp_spg_class_apertura 
{
   var $int_spg;
   function sigesp_spg_class_apertura()
   { 
		$this->io_function = new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->io_msg=new class_mensajes();
		$this->sig_int=new class_sigesp_int();
		$this->int_spg=new class_sigesp_int_spg();
		$this->obj=new class_datastore();
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->io_fecha=new class_fecha();
		/*$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();*/
		/*$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];*/
	}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
  function uf_spg_load_cuentas_apertura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_load_cuentas_apertura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica        
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que carga la información de la apertura de 
	//                   de cuentas en un data store, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  01/02/2006        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql= " SELECT spg_cuenta,status,denominacion,asignado,distribuir,enero, ".
	           "        febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre, ".
			   "        noviembre,diciembre ".
			   " FROM   spg_cuentas  ".
			   " WHERE  codemp='".$as_codemp."'  AND ".
			   "        codestpro1='".$as_ep1."' AND ".
			   "        codestpro2='".$as_ep2."' AND ". 
			   "        codestpro3='".$as_ep3."' AND ".
			   "        codestpro4='".$as_ep4."' AND ".
			   "        codestpro5='".$as_ep5."' AND ".
			   "        estcla='".$as_estcla."'  AND ".
			   "        status='C'                   ".
			   " ORDER BY spg_cuenta  "; 
	  $rs_load=$this->io_sql->select($ls_sql);
	  return $rs_load;
}//uf_spg_load_cuentas_apertura  
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
  function uf_spg_select_modalidad_apertura($as_codemp,&$as_estmodape)
  {	 //////////////////////////////////////////////////////////////////////////////////////////////
	 //	       Function:  uf_spg_select_modalidad_apertura
	 //      Arguments :  $as_codemp // codigo de la empresa 
	 //                   $as_estmodape  // modalidad de la apertura (referencia)
	 //	    Description:  Método que selecciona la modalidad de la apertura(mensual o trimestral). 
	 //                   Este proceso es utilizado en  la apertura de cuentas presupuestaria.  
	 //     Creado por :  Ing. Yozelin Barragán                                 
	 // Fecha Creación :  01/02/2006        Fecha última Modificacion :         
	 //////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
     $ls_sql=" SELECT estmodape FROM sigesp_empresa  WHERE codemp='".$as_codemp."' ";
	 $li_select=$this->io_sql->select($ls_sql);                                                                                                                                                                                          
	 if($li_select===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->class_apertura MÉTODO->uf_select_scg_plantillacuentareporte ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	 }
	 else
	 {
		   if($row=$this->io_sql->fetch_row($li_select))
		   {
		     $as_estmodape=$row["estmodape"];
			 $lb_valido=true;
		   } 
	 } 
	 return  $lb_valido;
}//fin  uf_spg_select_modalidad_apertura
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_spg_procesar_apertura($aa_seguridad)
{   ////////////////////////////////////////////////////////////////////////////////////////////////
	 //	       Function:  uf_spg_select_modalidad_apertura
	 //      Arguments :  $as_codemp // codigo de la empresa 
	 //                   $as_estmodape  // modalidad de la apertura (referencia)
	 //	    Description:  Método que selecciona la modalidad de la apertura(mensual o trimestral). 
	 //                   Este proceso es utilizado en  la apertura de cuentas presupuestaria.  
	 //     Creado por :  Ing. Yozelin Barragán                                 
	 // Fecha Creación :  01/02/2006        Fecha última Modificacion :         
	 //////////////////////////////////////////////////////////////////////////////////////////////
	$ls_formpre="";
	$ls_editmask_pre="";
	$lb_valido=true;
	
	$la_empresa =  $_SESSION["la_empresa"];
	$this->is_codemp  =  $la_empresa["codemp"];
	$ls_formpre =  $la_empresa["formpre"];
	$ld_periodo =  $la_empresa["periodo"];
	
	$this->is_procedencia = "SPGAPR";
	$this->is_comprobante = "0000000APERTURA";
	$this->ii_tipo_comp   = 2;
	$this->is_ced_ben     = "----------";
	$this->is_cod_prov    = "----------";
	$this->is_tipo        = "-";
	$this->is_descripcion = "APERTURA DE CUENTAS";
	$this->as_codban  = "---";
	$this->as_ctaban  = "-------------------------";
	
	$arr_dia=getdate();
	$ls_dia=$arr_dia["mday"];
	$ls_mes=$arr_dia["mon"];
	$ls_ano=$arr_dia["year"];
	$ldt_fecha=$ls_ano."/".$this->io_function->uf_cerosizquierda($ls_mes,2)."/".$ls_dia;

	if ($ld_periodo == $ldt_fecha) 
	{
	  $idt_fecha = $ldt_fecha;
	}
	else 
	{
	  $idt_fecha = $ld_periodo;
	}
	$this->io_sql->begin_transaction();
	$this->ldt_fecha=$this->io_function->uf_convertirfecmostrar($idt_fecha);
	$this->id_fecha=$this->io_function->uf_convertirdatetobd($this->ldt_fecha);
	if (!$this->sig_int->uf_select_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->ldt_fecha,$this->as_codban,$this->as_ctaban))
	{
	   $lb_valido = $this->sig_int->uf_sigesp_insert_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
	                                                             $this->ldt_fecha,$this->ii_tipo_comp,$this->is_descripcion,
																 $this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,
			                                                     $this->as_codban,$this->as_ctaban);
	   if ($lb_valido)
	   { 
		 //////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////		
		    $ls_evento="INSERT";
		    $ls_descripcion =" Guardar la Apertura con procedencia ".$this->is_procedencia." del comprobante nro ".$this->is_comprobante." ";
		    $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////	
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","totalaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto", 0);
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->is_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","procede");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->is_procedencia);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","comprobante");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->is_comprobante);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","fecha");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->ldt_fecha);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->as_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$this->as_ctaban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
	
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("sigesp_cmp",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,$aa_seguridad);
			if($lb_valido)	 
			{
				 $this->io_sql->commit();
				 $lb_valido=true;
			}
			else
			{
				 $this->io_sql->rollback();
				 $lb_valido=false;
			}*/
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
		}
			
	}
	return $lb_valido;
}//uf_spg_procesar_apertura
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_spg_guardar_apertura($ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
                                 $ad_m12,$estprog,$as_cuenta,$ad_asignado,$ai_distribuir,$aa_seguridad)
{   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_guardar_apertura()                                   
	//	     Arguments:  $as_cuenta --- codigo de la cuenta  
	//                   $estprog --- estructura programatica        
	//                   $adec_m1.. $adec_m12 --- monto desde el  mes de enero hasta diciembre
	//                   $ai_distribuir --- modo de distribución
	//                   $ad_asignado --- asignado de la cuenta 
	//                   $aa_seguridad --- arreglo de seguridad 
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que recorre la información almacenada en un datastore el cual contiene la información generada o    //               registrada 
	//                   en cuanto a la información de las asignación de la apertura de cuentas  presupuestaria de gasto. 
	//                   Si la información de la apertura de cuenta no existe, el método procederá a realizar un update en la 
	//                   tabla  de spg_cuentas en cuanto a su asignación.
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  02/02/2006        Fecha última Modificacion :         
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       //$this->io_sql->begin_transaction();
		$lb_valido=true;
        $ldec_asignado_ant=0;	
		$this->int_spg->is_codemp=$this->is_codemp;
		$this->int_spg->is_procedencia=$this->is_procedencia;		
		$this->int_spg->is_comprobante=$this->is_comprobante;
		$this->int_spg->ii_tipo_comp=$this->ii_tipo_comp;
		$this->int_spg->is_ced_ben =$this->is_ced_ben;
		$this->int_spg->is_cod_prov =$this->is_cod_prov;
		$this->int_spg->is_tipo=$this->is_tipo;
		$this->int_spg->is_descripcion=$this->is_descripcion ;
		$this->int_spg->id_fecha=$this->id_fecha;
		$ldt_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
		$_SESSION["fechacomprobante"]=$ldt_fecha;
		$this->int_spg->as_codban=$this->as_codban; //  POR LAS INTEGRADORAS NUEVAS 
		$this->int_spg->as_ctaban=$this->as_ctaban; //  POR LAS INTEGRADORAS NUEVAS
		$ls_denominacion="";	
		$ls_status="";	
		$ls_sc_cuenta="";	
		if ($this->is_tipo=="B")  
		{ $ls_fuente = $this->is_ced_ben; }	
		 else
		 { 
			if ($this->is_tipo=="P")
			 {  
				$ls_fuente = $this->is_cod_prov; 
			 }	
			 else 
			 {  
				$ls_fuente = "----------"; 
			 } 
		 }
		if(!$this->int_spg->uf_spg_select_cuenta($this->is_codemp,$estprog,$as_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
		{  
		  return false;
		}
		if ($this->int_spg->uf_spg_select_movimiento($estprog,$as_cuenta,$this->is_procedencia,$this->is_comprobante,"AAP",
		                                             &$ldec_monto,&$li_orden)) 
		{
		  if ($ad_asignado <> 0) 
		  {
			$ls_logusr=$aa_seguridad["logusr"];
			$lb_valido=$this->uf_verificar_administrador($this->is_codemp,$ls_logusr);
			if($lb_valido)
			{
				$ld_comprometido=0;
				$ld_aumento=0;
				$ld_disminucion=0;
				$lb_valido=$this->uf_select_compromiso_cuenta($this->is_codemp,$as_cuenta,$estprog,$ld_comprometido,$ld_aumento,$ld_disminucion);
				$ad_asignado=trim($ad_asignado);
				$ld_comprometido=number_format($ld_comprometido,2,".","");
				$ad_montoactualizado=(number_format($ad_asignado,2,".","")+number_format($ld_aumento,2,".",""))-number_format($ld_disminucion,2,".","");
				$ad_montoactualizado=number_format($ad_montoactualizado,2,".","");
				if($lb_valido)
				{
			          if($ad_montoactualizado>=$ld_comprometido)
					  {	
						 $lb_valido = $this->int_spg->uf_spg_update_movimiento($this->is_codemp,$this->is_procedencia,
																			   $this->is_comprobante,$this->id_fecha,
																			   $this->is_cod_prov,$this->is_ced_ben,
																			   $this->is_descripcion,$this->is_tipo, 
																			   $this->ii_tipo_comp,$estprog,$estprog,
																			   $as_cuenta,$as_cuenta,$this->is_procedencia,
																			   $this->is_procedencia,$this->is_comprobante,
																			   $this->is_comprobante,$this->is_descripcion,
																			   $this->is_descripcion,'I','I',$ldec_monto,
																			   $ad_asignado);
						 if($lb_valido)
						 {
							/*$lb_valido=$this->uf_update_bsf_spgdtcmp($ldec_monto,$this->is_codemp,$this->is_procedencia,
							                                         $this->is_comprobante,$this->id_fecha,$this->as_codban,
							                                         $this->as_ctaban,$estprog,$as_cuenta,$this->is_procedencia,
																	 $this->is_comprobante,'AAP',$aa_seguridad);*/
							if($lb_valido)
						    {
							   $lb_valido=$this->uf_update_distribucion($this->is_codemp,$estprog,$as_cuenta,$ad_m1,$ad_m2,$ad_m3,
							                                            $ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
																		$ad_m12,$ai_distribuir,$as_cuenta,$ad_asignado,
																		$ai_distribuir,$aa_seguridad);
							   if($lb_valido)
							   {
							      $ad_precomprometido=0;
								  $ad_comprometido=0;
								  $ad_causado=0;
								  $ad_pagado=0;
								  $ad_aumento=0;
                                  $ad_disminucion=0;
								  /*$lb_valido=$this->uf_update_bsf_spgcuentas($ad_asignado,$ad_precomprometido,$ad_comprometido,
								                                             $ad_causado,$ad_pagado,$ad_aumento,$ad_disminucion,
                                                                             $ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,
																			 $ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,
																			 $this->is_codemp,$estprog,$as_cuenta,$aa_seguridad);*/
							   }  
							}
						 }
					   }
					   else
					   {
						  $this->io_msg->message(" La Cuenta ".$as_cuenta."  tiene comprometido ".$ld_comprometido." y el monto actualizado es ".$ad_montoactualizado." esta asignando menos de lo comprometido por favor revise su monto asignado... ");
						  $lb_valido=false;						  
					   }	
				 }		
			 }
			 else
			 {
			   $this->io_msg->message(" El usuario ".$ls_logusr." no tiene permiso para modificar el asignado de la apertura  comuniquese con el  Administrador del Sistema....");
			   $lb_valido=false;						  
			 }												  
		  }					 	   											   
		  else
		  {						
				$ld_comprometido=0;
				$ld_aumento=0;
				$ld_disminucion=0;
				$lb_valido=$this->uf_select_compromiso_cuenta($this->is_codemp,$as_cuenta,$estprog,$ld_comprometido,$ld_aumento,$ld_disminucion);
				$ad_asignado=trim($ad_asignado);
				$ld_comprometido=number_format($ld_comprometido,2,".",",");
				$ad_montoactualizado=(number_format($ad_asignado,2,".",",")+number_format($ld_aumento,2,".",","))-number_format($ld_disminucion,2,".",",");
				$ad_montoactualizado=number_format($ad_montoactualizado,2,".",",");
				if($lb_valido)
				{
			          if($ad_montoactualizado>=$ld_comprometido)
					  {	
						 $lb_valido = $this->int_spg->uf_int_spg_delete_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
																				   $ldt_fecha,$this->is_tipo,$ls_fuente,$this->is_cod_prov,
																				   $this->is_ced_ben,$estprog,$as_cuenta,$this->is_procedencia,
																				   $this->is_comprobante,$this->is_descripcion,"I",
																				   $this->ii_tipo_comp,$ldec_asignado_ant,$ad_asignado,
																				   $ls_sc_cuenta,$this->as_codban,$this->as_ctaban);
						 if($lb_valido)
						 {
							/*$lb_valido=$this->uf_update_bsf_spgdtcmp($ad_asignado,$this->is_codemp,$this->is_procedencia,
																	 $this->is_comprobante,$this->id_fecha,$this->as_codban,
																	 $this->as_ctaban,$estprog,$as_cuenta,$this->is_procedencia,
																	 $this->is_comprobante,'AAP',$aa_seguridad);*/
							if($lb_valido)
							{
								$lb_valido=$this->uf_update_distribucion($this->is_codemp,$estprog,$as_cuenta,$ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,
																		 $ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,$ai_distribuir,
																		 $as_cuenta,$ad_asignado,$ai_distribuir,$aa_seguridad);
							   if($lb_valido)
							   {
								  $ad_precomprometido=0;
								  $ad_comprometido=0;
								  $ad_causado=0;
								  $ad_pagado=0;
								  $ad_aumento=0;
								  $ad_disminucion=0;
								 /* $lb_valido=$this->uf_update_bsf_spgcuentas($ad_asignado,$ad_precomprometido,$ad_comprometido,
																			 $ad_causado,$ad_pagado,$ad_aumento,$ad_disminucion,
																			 $ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,
																			 $ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,$this->is_codemp,
																			 $estprog,$as_cuenta,$aa_seguridad);*/
							   }  
							}
						 }
						}
					   else
					   {
						  $this->io_msg->message(" La Cuenta ".$as_cuenta."  tiene comprometido ".$ld_comprometido." y el monto actualizado es ".$ad_montoactualizado." esta asignando menos de lo comprometido por favor revise su monto asignado... ");
						  $lb_valido=false;						  
					   }	
					}
		  }
		}  
		else
		{	
		  if ($ad_asignado <> 0) 
		  {
			   $lb_valido = $this->int_spg->uf_int_spg_insert_movimiento($this->is_codemp,$this->is_procedencia,
			                                                             $this->is_comprobante,$ldt_fecha,
																		 $this->is_tipo,$ls_fuente,$this->is_cod_prov,
																		 $this->is_ced_ben,$estprog,$as_cuenta,
																		 $this->is_procedencia,$this->is_comprobante,
																		 $this->is_descripcion,"I",$ad_asignado,
																		 $ls_sc_cuenta,true,$this->as_codban,$this->as_ctaban);
			   if($lb_valido)
			   {
				  /*$lb_valido=$this->uf_update_bsf_spgdtcmp($ad_asignado,$this->is_codemp,$this->is_procedencia,
					 									   $this->is_comprobante,$this->id_fecha,$this->as_codban,
														   $this->as_ctaban,$estprog,$as_cuenta,$this->is_procedencia,
														   $this->is_comprobante,'I',$aa_seguridad);
				   */
				   if($lb_valido)
				   {
						$lb_valido=$this->uf_update_distribucion($this->is_codemp,$estprog,$as_cuenta,$ad_m1,$ad_m2,$ad_m3,
																 $ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
																 $ad_m12,$ai_distribuir,$as_cuenta,$ad_asignado,$ai_distribuir,
																 $aa_seguridad);
					    if($lb_valido)
					    {
						  $ad_precomprometido=0;
						  $ad_comprometido=0;
						  $ad_causado=0;
						  $ad_pagado=0;
						  $ad_aumento=0;
						  $ad_disminucion=0;
						  /*$lb_valido=$this->uf_update_bsf_spgcuentas($ad_asignado,$ad_precomprometido,$ad_comprometido,
																	 $ad_causado,$ad_pagado,$ad_aumento,$ad_disminucion,
																	 $ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,
																	 $ad_m8,$ad_m9,$ad_m10,$ad_m11, $ad_m12,$this->is_codemp,
																	 $estprog,$as_cuenta,$aa_seguridad);*/
					   }  
				  }
			  }  
		   }
		   else
		   {
			   if($lb_valido)
			   {
				 /* $lb_valido=$this->uf_update_bsf_spgdtcmp($ad_asignado,$this->is_codemp,$this->is_procedencia,
					 									   $this->is_comprobante,$this->id_fecha,$this->as_codban,
														   $this->as_ctaban,$estprog,$as_cuenta,$this->is_procedencia,
														   $this->is_comprobante,'I',$aa_seguridad);*/
				   if($lb_valido)
				   {
						$lb_valido=$this->uf_update_distribucion($this->is_codemp,$estprog,$as_cuenta,$ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,
																 $ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,$ai_distribuir,
																 $as_cuenta,$ad_asignado,$ai_distribuir,$aa_seguridad);
					    if($lb_valido)
					    {
						  $ad_precomprometido=0;
						  $ad_comprometido=0;
						  $ad_causado=0;
						  $ad_pagado=0;
						  $ad_aumento=0;
						  $ad_disminucion=0;
						 /* $lb_valido=$this->uf_update_bsf_spgcuentas($ad_asignado,$ad_precomprometido,$ad_comprometido,
																	 $ad_causado,$ad_pagado,$ad_aumento,$ad_disminucion,
																	 $ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,
																	 $ad_m8,$ad_m9,$ad_m10,$ad_m11, $ad_m12,$this->is_codemp,
																	 $estprog,$as_cuenta,$aa_seguridad);*/
					   }  
				  }
			  }  
		   }				
		}
		if($lb_valido)
		{
		  $ls_programatica=$estprog[0].$estprog[1].$estprog[2].$estprog[3].$estprog[4];
		 //////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////		
		  $ls_evento="UPDATE";
		  $ls_descripcion =" Actualizacion de la Apertura con procedencia ".$this->is_procedencia.", con un asignado de ".
		  $ad_asignado.", la cuenta ".$as_cuenta." con programatica ".$ls_programatica." ";
		  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////	
			 $this->io_sql->commit();
			  $lb_valido=true;
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
		}	
	   return $lb_valido;
}// fin 
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_saldos_apertura($as_codemp,$adec_m1,$adec_m2,$adec_m3,$adec_m4,$adec_m5,$adec_m6,$adec_m7,
                                   $adec_m8,$adec_m9,$adec_m10,$adec_m11,$adec_m12,$estprog,$as_spg_cuenta,$ai_distribuir)
{   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_saldos_apertura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $estprog --- estructura programatica        
	//                   $adec_m1.. $adec_m12 --- monto desde el  mes de enreo hasta diciembre
	//                   $ai_distribuir --- modo de distribución
	//                   $as_spg_cuenta --- codigo de la cuenta 
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para actualizar los saldos de la distribucion de la apertura
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  05/04/2006        Fecha última Modificacion :         
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     
		$ls_sql=" UPDATE spg_cuentas  ".
				" SET    distribuir='".$ai_distribuir."', ".
				"        enero=".$adec_m1.", ".
				"        febrero=".$adec_m2.", ".
				"        marzo=".$adec_m3.", ".
				"        abril=".$adec_m4.", ".
				"        mayo=".$adec_m5.", ".
				"        junio=".$adec_m6.", ".
				"        julio=".$adec_m7.", ".
				"        agosto=".$adec_m8.", ".
				"        septiembre=".$adec_m9.", ".
				"        octubre=".$adec_m10.", ".
				"        noviembre=".$adec_m11.", ".
				"        diciembre=".$adec_m12." ".
				" WHERE  codemp='".$as_codemp."' AND ".
				"        codestpro1='".$estprog[0]."' AND ".
				"        codestpro2='".$estprog[1]."' AND ".
				"        codestpro3='".$estprog[2]."' AND ".
				"        codestpro4='".$estprog[3]."' AND ".
				"        codestpro5='".$estprog[4]."' AND ".
				"        estcla='".$estprog[5]."' AND ".
				"        spg_cuenta = '".$as_spg_cuenta."' "; 
		$li_row=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
		if($li_row===false)
		{
		  $lb_valido=false;
		  $this->io_msg->message("Error en actualización de saldos, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
		}
		else
		{
		  $lb_valido=true;
		} 
	    return 	$lb_valido;
}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_distribucion($as_codemp,$estprog,$as_cuenta,$ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,
								$ai_distribuir,$as_cuenta,$ad_asignado,$as_distribuir,$aa_seguridad)
{ //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //	      Function:  uf_update_distribucion_apertura()                                   
 //	     Arguments:  $as_codemp --- codigo de la empresa    
 //                   $estprog --- estructura programatica                   
 //	       Returns:  True si es correcto o false es otro caso                  
 //	   Description:  Funcion que se usa  para actualizar los saldos de la distribuciob y de las cuentas madres 
 //     Creado por :  Ing. Nelson Barraez                                 
 // Fecha Creación :  11/04/2006        Fecha última Modificacion :         
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
	//$this->io_sql->begin_transaction();
	$lb_valido=true;
	$li_nivel = $this->int_spg->uf_spg_obtener_nivel($as_cuenta);
	$ls_nextcuenta=$as_cuenta;
	//Distribuyo los montos para la cuenta actual.
	$lb_valido=$this->uf_update_saldos_apertura($as_codemp,$ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,
								                $ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,
												$estprog,$ls_nextcuenta,$ai_distribuir);
													
	//Obtengo la cuenta anterior.
	$ls_nextcuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
	while(($lb_valido)&&($li_nivel>=1))
	{  
	  $ld_enero=0;		$ld_febrero=0; $ld_marzo=0;   $ld_abril=0;        $ld_mayo=0;
 	  $ld_junio=0; 		$ld_julio=0;   $ld_agosto=0;  $ld_septiembre=0;	  $ld_octubre=0;
	  $ld_noviembre=0;	$ld_diciembre=0;		
      //Obtengo la cuenta sin ceros para buscar todos los hijos.
	  $ls_cta_sin_ceros=$this->int_spg->uf_spg_cuenta_sin_cero($ls_nextcuenta);
	  //Obtengo los hijos de la cuenta.
	  $aa_hijos=$this->uf_obtener_hijos($as_codemp,$ls_cta_sin_ceros,$estprog);
  	  $li_total_hijos=count($aa_hijos);

		for($li_i=1;$li_i<=$li_total_hijos;$li_i++)
		{
			$ls_cuenta_spg=$aa_hijos[$li_i];
			//Obtengo los montos de los hijos de la cuenta actual y los acumulo.		
			$this->uf_spg_obtener_montos_cuenta($as_codemp,$ls_cuenta_spg,$estprog,&$aa_montos);
			$ld_enero=$ld_enero+$aa_montos["enero"];
			$ld_febrero=$ld_febrero+$aa_montos["febrero"];
			$ld_marzo=$ld_marzo+$aa_montos["marzo"];
			$ld_abril=$ld_abril+$aa_montos["abril"];
			$ld_mayo=$ld_mayo+$aa_montos["mayo"];
			$ld_junio=$ld_junio+$aa_montos["junio"];
			$ld_julio=$ld_julio+$aa_montos["julio"];
			$ld_agosto=$ld_agosto+$aa_montos["agosto"];
			$ld_septiembre=$ld_septiembre+$aa_montos["septiembre"];			
			$ld_octubre=$ld_octubre+$aa_montos["octubre"];
			$ld_noviembre=$ld_noviembre+$aa_montos["noviembre"];
			$ld_diciembre=$ld_diciembre+$aa_montos["diciembre"];			
		}
		//Actualizo los saldos para la cuenta.
		$lb_valido=$this->uf_update_saldos_apertura($as_codemp,$ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
									                $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre,
													$estprog,$ls_nextcuenta,$ai_distribuir);
		if($this->int_spg->uf_spg_obtener_nivel( $ls_nextcuenta ) == 1)
		{ 
		  break;
		}
		$ls_nextcuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
		$li_nivel = $this->int_spg->uf_spg_obtener_nivel( $ls_nextcuenta );
   }//while	
   return $lb_valido;
}//fin
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function procesar_guardar_apertura($ar_datos,$estprog,$aa_seguridad,$ai_num)   
{   ////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  procesar_guardar_apertura()                                   
	//	     Arguments:  $ar_datos --- codigo de la empresa    
	//                   $estprog --- estructura programatica        
	//                   $aa_seguridad--- arreglo de la seguridad
	//                   $data  --- data 
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para procesar el guardar de la apertura
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  11/04/2006        Fecha última Modificacion : 03/08/2007         
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$this->int_spg->is_codemp=$this->is_codemp;
	$this->int_spg->is_procedencia=$this->is_procedencia;		
	$this->int_spg->is_comprobante=$this->is_comprobante;
	$this->int_spg->ii_tipo_comp=$this->ii_tipo_comp;
	$this->int_spg->is_ced_ben =$this->is_ced_ben;
	$this->int_spg->is_cod_prov =$this->is_cod_prov;
	$this->int_spg->is_tipo=$this->is_tipo;
	$this->int_spg->is_descripcion=$this->is_descripcion;
	$this->int_spg->id_fecha=$this->id_fecha ;
	$ldt_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
	$this->int_spg->as_codban=$this->as_codban; 
	$this->int_spg->as_ctaban=$this->as_ctaban; 
	for($i=1;$i<=$ai_num;$i++)
	{	
		$ls_cuenta=$ar_datos["spg_cuenta"][$i];
		$ls_denominacion=$ar_datos["denominacion"][$i];
		$ld_asignado=$ar_datos["asignado"][$i];
		$li_distribuir=$ar_datos["distribuir"][$i];
		$ld_m1=$ar_datos["enero"][$i];
		$ld_m2=$ar_datos["febrero"][$i];
		$ld_m3=$ar_datos["marzo"][$i];
		$ld_m4=$ar_datos["abril"][$i];
		$ld_m5=$ar_datos["mayo"][$i];
		$ld_m6=$ar_datos["junio"][$i];
		$ld_m7=$ar_datos["julio"][$i];
		$ld_m8=$ar_datos["agosto"][$i];
		$ld_m9=$ar_datos["septiembre"][$i];
		$ld_m10=$ar_datos["octubre"][$i];
		$ld_m11=$ar_datos["noviembre"][$i];
		$ld_m12=$ar_datos["diciembre"][$i];	
	    if($lb_valido)
		{
			 $ls_codestpro1=$estprog[0];
		     $ls_codestpro2=$estprog[1];
			 $ls_codestpro3=$estprog[2];
			 $ls_codestpro4=$estprog[3]; 
			 $ls_codestpro5=$estprog[4]; 
			 $ls_estcla    =$estprog[5];
			 
			 $lb_existe = $this->uf_spg_existe_dt_fuefin_estructura($this->is_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,"--");
			 if(!$lb_existe)
			 {
			  $lb_valido=$this->uf_spg_insert_dt_fuefin_estructura($this->is_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,"--");
			 }
			 
			 $lb_existe = $this->uf_spg_existe_fuefin_estructura($this->is_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,"--");
	         if(!$lb_existe)
	         {
	          $lb_valido= $this->uf_spg_insert_fuefin_estructura($this->is_codemp,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,$ls_codestpro5,$ls_estcla,$ls_cuenta,"--",0); 
	         }
			 
			if($lb_valido)
			{
			 $lb_valido=$this->uf_spg_guardar_apertura($ld_m1,$ld_m2,$ld_m3,$ld_m4,$ld_m5,$ld_m6,$ld_m7,$ld_m8,$ld_m9,$ld_m10,
			                                          $ld_m11,$ld_m12,$estprog,$ls_cuenta,$ld_asignado,$li_distribuir,
													  $aa_seguridad);
			}										  
			if($lb_valido==false)
			{
			  break;
			}								  								  
		}
  }//for
  return $lb_valido;
}//fin	
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
 function uf_spg_obtener_montos_cuenta($as_codemp,$as_cuenta,$aa_estprog,$aa_montos)
  {
	///////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_spg_obtener_montos_cuenta
	//    Descripcion : Método obtiene los monto programados para la cuenta enviada
	//     Creado Por : Ing. Nelson Barraez
	// Modificada Por : Ing. Yozelin Barragan.
	////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  $ls_sql= " SELECT enero,febrero,marzo,abril,mayo,junio,julio,   ".
	           "        agosto,septiembre,octubre,noviembre,diciembre ".
			   " FROM   spg_cuentas  ".
			   " WHERE  codemp='".$as_codemp."'  AND codestpro1='".$aa_estprog[0]."' AND         ".
			   "        codestpro2='".$aa_estprog[1]."' AND  codestpro3='".$aa_estprog[2]."' AND ".
			   "        codestpro4='".$aa_estprog[3]."' AND  codestpro5='".$aa_estprog[4]."' AND ".
			   "        estcla='".$aa_estprog[5]."'     AND  spg_cuenta='".$as_cuenta."' ";  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {
	  	$this->io_msg->message("Error en obtener montos de la cuenta, ".$this->io_function->uf_convertirmsg($this->io_sql->message));
		return false;
	  }
	  else
	  {
		  if($row=$this->io_sql->fetch_row($rs_data))
		  {
		  		$aa_montos["enero"]=$row["enero"];
				$aa_montos["febrero"]=$row["febrero"];
				$aa_montos["marzo"]=$row["marzo"];
				$aa_montos["abril"]=$row["abril"];
				$aa_montos["mayo"]=$row["mayo"];
				$aa_montos["junio"]=$row["junio"];
				$aa_montos["julio"]=$row["julio"];
				$aa_montos["agosto"]=$row["agosto"];
				$aa_montos["septiembre"]=$row["septiembre"];
				$aa_montos["octubre"]=$row["octubre"];
				$aa_montos["noviembre"]=$row["noviembre"];
				$aa_montos["diciembre"]=$row["diciembre"];
		  }
		  else
		  {
			 	$aa_montos["enero"]     = 0;
				$aa_montos["febrero"]   = 0;
				$aa_montos["marzo"]     = 0;
				$aa_montos["abril"]     = 0;
				$aa_montos["mayo"]      = 0;
				$aa_montos["junio"]     = 0;
				$aa_montos["julio"]     = 0;
				$aa_montos["agosto"]    = 0;
				$aa_montos["septiembre"]= 0;
				$aa_montos["octubre"]   = 0;
				$aa_montos["noviembre"] = 0;
				$aa_montos["diciembre"] = 0;
		  }
	  }
  }//uf_spg_obtener_montos_cuenta
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_obtener_hijos($as_codemp,$as_spg_cuenta,$aa_estprog)
{
	///////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_obtener_hijos
	//     Descripcion: Metodo que retorna las cuentas hijas de la cuenta enviada.
	//     Creado Por : Ing. Nelson Barraez
	// Modificada Por : Ing. Yozelin Barragan.
	////////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql = " SELECT spg_cuenta ".
			  "	FROM   spg_cuentas ".
			  "	WHERE  spg_cuenta like '".$as_spg_cuenta."%' AND ".
			  "        codestpro1='".$aa_estprog[0]."' AND codestpro2='".$aa_estprog[1]."' AND ".
			  "		   codestpro3='".$aa_estprog[2]."' AND codestpro4='".$aa_estprog[3]."' AND ".
			  "        codestpro5='".$aa_estprog[4]."' AND estcla='".$aa_estprog[5]."'     AND ".
			  "        status='C' ".
			  "	ORDER  by spg_cuenta " ;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	  $data=array();
	  $this->is_msg_error="Error al obtener cuentas hijas, ".$this->io_sql->message;		  
	}
	else
	{
		$i=1;
		$data=array();
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_sc_cuenta  =  $row["spg_cuenta"];
			$data[$i]=$ls_sc_cuenta;
			$i=$i+1;
		}// cierre del while rs_oaf.next (update)
	}
	return $data;
}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_select_cuenta_presupuestaria($as_codemp,$as_spg_cuenta,$aa_estprog,$as_procede,$as_comprobante,$adt_fecha,                                         $as_procede_doc,$as_documento,$as_operacion)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_cuenta_presupuestaria()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para saber si existe  la  apertura
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  15/09/06        Fecha última Modificacion :        
	//////////////////////////////////////////////////////////////////////////////////////////s
		$ls_sql = " SELECT * ".
                  " FROM   spg_dt_cmp ".
                  " WHERE  codemp='".$as_codemp."' AND procede='".$as_procede."' AND ".
				  "        comprobante='".$as_comprobante."'  AND ".
                  "        codestpro1='".$aa_estprog[0]."' AND codestpro2='".$aa_estprog[1]."' AND ".
				  "        codestpro3='".$aa_estprog[2]."' AND codestpro4='".$aa_estprog[3]."' AND ".
				  "        codestpro5='".$aa_estprog[4]."' AND spg_cuenta='".$as_spg_cuenta."'  " ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		    $this->is_msg_error="Error uf_select_cuenta_presupuestaria, ".$this->io_sql->message;		  
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			   $lb_valido=true;
			}
			else
			{
			   $lb_valido=false;
			}
            $this->io_sql->free_result($rs_data);		
		}
	return $lb_valido;
}
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_select_compromiso_cuenta($as_codemp,$as_spg_cuenta,$aa_estprog,&$ad_comprometido,&$ad_aumento,&$ad_disminucion)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_compromiso_cuenta()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para saber si existe  movimiento de la cuenta
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  15/09/06        Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql = "SELECT comprometido, aumento, disminucion  ".
              "  FROM spg_cuentas   ".
              " WHERE codemp='".$as_codemp."' ".
			  "   AND codestpro1='".$aa_estprog[0]."'  ".
			  "   AND codestpro2='".$aa_estprog[1]."' ".
			  "   AND codestpro3='".$aa_estprog[2]."' ".
              "   AND codestpro4='".$aa_estprog[3]."' ".
			  "   AND codestpro5='".$aa_estprog[4]."' ".
			  "   AND estcla='".$aa_estprog[5]."' ".
			  "   AND spg_cuenta='".$as_spg_cuenta."' " ;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error uf_select_compromiso_cuenta, ".$this->io_sql->message;		  
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
		   $ad_comprometido=$row["comprometido"];
		   $ad_aumento=$row["aumento"];
		   $ad_disminucion=$row["disminucion"];
		   $lb_valido=true;
		}
		else
		{
		   $lb_valido=false;
		}
		$this->io_sql->free_result($rs_data);		
	}
	return $lb_valido;
}//uf_select_compromiso_cuenta
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_verificar_administrador($as_codemp,&$as_codusu)
{
    ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_verificar_administrador()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para saber si existe  movimiento de la cuenta
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  15/09/06        Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
    $i=1;
	$campo="";
	$as_ventana= $this->obtenerCodigoMenu('SPG','sigesp_spg_p_apertura.php',&$campo);
	$ls_sql = " SELECT codusu ".
              " FROM   sss_derechos_usuarios  ".
              " WHERE  codemp='".$as_codemp."' AND  codsis='SPG' AND  ".
			  "        $campo='".$as_ventana."' AND ".
              "        incluir='1' AND cambiar='1' AND codusu='".$as_codusu."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->is_msg_error="Error uf_select_cuenta_presupuestaria, ".$this->io_sql->message;		  
		$lb_valido=false;
	}
	else
	{
		while($row=$this->io_sql->fetch_row($rs_data))
		{
		   $as_codusu[$i]=$row["codusu"];
		   $lb_valido=true;
		   $i=$i+1;
		}
		$this->io_sql->free_result($rs_data);		
	}
    return $lb_valido;
}
//---------------------------------------------------------------------------------------------------------------------------------


			function obtenerCodigoMenu($codsis,$nomfisico,&$campo)
			{
				global $conexionbd;
				if (array_key_exists('session_activa',$_SESSION))
				{				
					$consulta = "SELECT codmenu ".
								"  FROM sss_sistemas_ventanas ".
								" WHERE codsis = '$codsis' ".
								"	AND nomfisico ='$nomfisico' ";
					$result = $this->io_sql->select($consulta); 
					if($result === false)
					{
						$this->valido  = false;
					}
					else
					{
						if(!$result->EOF)
						{   
							$codmenu=$result->fields["codmenu"];
						}
						$result->Close();
					}
					$campo= "codmenu";
				}
				else
				{
					$codmenu = $nomfisico;
					$campo= "nomven";
				}
				return $codmenu;
			}		


//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_spgdtcmp($ad_monto,$as_codemp,$as_procede,$as_comprobante,$ad_fecha,$as_codban,$as_ctaban,$as_codestpro,
                                $as_spg_cuenta,$as_procede_doc,$as_documento,$as_operacion,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_spgdtcmp()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="SELECT codemp, procede, comprobante, fecha, codban, ctaban, codestpro1, ".
	        "       codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, ".
			"		procede_doc, documento, operacion, monto,estcla ".
			"  FROM spg_dt_cmp ".
			" WHERE codemp='".$as_codemp."' ".
			"   AND codestpro1 ='".$as_codestpro[0]."' ".
			"   AND codestpro2 ='".$as_codestpro[1]."' ". 
			"   AND codestpro3 ='".$as_codestpro[2]."' ".
			"   AND codestpro4 ='".$as_codestpro[3]."' ".
			"   AND codestpro5 ='".$as_codestpro[4]."' ".
			"   AND estcla ='".$as_codestpro[5]."' ".
			"   AND procede='".$as_procede."' ".
			"   AND comprobante='".$as_comprobante."' ".
			"   AND fecha='".$ad_fecha."' ".
			"   AND codban='".$as_codban."' ".
			"   AND ctaban='".$as_ctaban."' ".
			"   AND procede_doc='".$as_procede_doc."' ".
			"   AND documento ='".$as_documento."' ".
			"   AND operacion='".$as_operacion."' "; 
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{ 
		$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgdtcmp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_codestpro1=$row["codestpro1"];
			$ls_codestpro2=$row["codestpro2"];
			$ls_codestpro3=$row["codestpro3"];
			$ls_codestpro4=$row["codestpro4"];
			$ls_codestpro5=$row["codestpro5"];
			$ls_estcla=$row["estcla"];
			$as_procede=$row["procede"];
			$as_comprobante=$row["comprobante"];
			$as_fecha=$row["fecha"];
			$as_codban=$row["codban"];
			$as_ctaban=$row["ctaban"];
			$as_procede_doc=$row["procede_doc"];
			$as_documento=$row["documento"];
			$as_spg_cuenta=$row["spg_cuenta"];
			$as_operacion=$row["operacion"];
			
			/*$ls_codestpro1=$as_codestpro[0];
			$ls_codestpro2=$as_codestpro[1];
			$ls_codestpro3=$as_codestpro[2];
			$ls_codestpro4=$as_codestpro[3];
			$ls_codestpro5=$as_codestpro[4];
			$ls_estcla=$as_codestpro[5];*/
		
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
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codban);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","ctaban");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_ctaban);
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
		
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_dt_cmp",$this->li_candeccon,$this->li_tipconmon,
															 $this->li_redconmon,$aa_seguridad);*/
          }
    }									 
	return $lb_valido;
}//uf_update_bsf_spgdtcmp
//---------------------------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------------
function uf_update_bsf_spgcuentas($ad_asignado,$ad_precomprometido,$ad_comprometido,$ad_causado,$ad_pagado,$ad_aumento,
                                  $ad_disminucion,$ad_enero,$ad_febrero,$ad_marzo,$ad_abril,$ad_mayo,$ad_junio,$ad_julio,
                                  $ad_agosto,$ad_septiembre,$ad_octubre,$ad_noviembre,$ad_diciembre,$as_codemp,
                                  $as_codestpro,$as_spg_cuenta,$aa_seguridad)
{
     ////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_update_bsf_spgcuentas()                                   
	//	     Arguments:    
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa para actualizar los monto a bolivar fuerte
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  24/09/2007                 Fecha última Modificacion :        
	/////////////////////////////////////////////////////////////////////////////////////////
	$ls_codestpro1=$as_codestpro[0];
    $ls_codestpro2=$as_codestpro[1];
    $ls_codestpro3=$as_codestpro[2];
    $ls_codestpro4=$as_codestpro[3];
    $ls_codestpro5=$as_codestpro[4];
    $ls_estcla=$as_codestpro[5];
		
	$lb_valido=true;
	$ls_sql="SELECT codemp, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, ".
            "       spg_cuenta, asignado, precomprometido,comprometido, causado, pagado, ".
            "       aumento, disminucion, enero, febrero, marzo, abril, mayo, junio, julio, ".
            "       agosto, septiembre, octubre, noviembre, diciembre, estcla ".
			"  FROM spg_cuentas ".
			" WHERE codemp='".$as_codemp."' ".
			"   AND codestpro1 ='".$as_codestpro[0]."' ".
			"   AND codestpro2 ='".$as_codestpro[1]."' ". 
			"   AND codestpro3 ='".$as_codestpro[2]."' ".
			"   AND codestpro4 ='".$as_codestpro[3]."' ".
			"   AND codestpro5 ='".$as_codestpro[4]."' ".
			"   AND estcla ='".$as_codestpro[5]."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{ 
		$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgcuentas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
		{
			$as_codemp     = $row["codemp"]; 
			$ls_codestpro1 = $row["codestpro1"];
			$ls_codestpro2 = $row["codestpro2"];
			$ls_codestpro3 = $row["codestpro3"];
			$ls_codestpro4 = $row["codestpro4"];
			$ls_codestpro5 = $row["codestpro5"]; 
			$ls_estcla = $row["estcla"]; 
			$as_spg_cuenta = $row["spg_cuenta"];
			$ad_asignado   = $row["asignado"];
			$ad_precomprometido = $row["precomprometido"];
			$ad_comprometido = $row["comprometido"];
			$ad_causado = $row["causado"];
			$ad_pagado = $row["pagado"];
			$ad_aumento = $row["aumento"];
			$ad_disminucion = $row["disminucion"];
			$ad_enero = $row["enero"];
			$ad_febrero = $row["febrero"];
			$ad_marzo = $row["marzo"];
			$ad_abril = $row["abril"];
			$ad_mayo = $row["mayo"];
			$ad_junio = $row["junio"];
			$ad_julio = $row["julio"];
			$ad_agosto = $row["agosto"];
			$ad_septiembre = $row["septiembre"];
			$ad_octubre = $row["octubre"];
			$ad_noviembre = $row["noviembre"];
			$ad_diciembre = $row["diciembre"];
	
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_asignado);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","precomprometidoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_precomprometido);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","comprometidoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_comprometido);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","causadoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_causado);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","pagadoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_pagado);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","aumentoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_aumento);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","disminucionaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_disminucion);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_enero);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_febrero);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_marzo);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_abril);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_mayo);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_junio);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_julio);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_agosto);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_septiembre);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_octubre);
		
			$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_noviembre);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ad_diciembre);
		
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
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
			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_cuentas",$this->li_candeccon,$this->li_tipconmon,
															  $this->li_redconmon,$aa_seguridad);*/
	   }
	} 
	return $lb_valido;
}//uf_update_bsf_spgcuentas
//---------------------------------------------------------------------------------------------------------------------------------

function uf_spg_load_fuefin_estructura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla,$as_cuenta)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_load_fuefin_estrutura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica        
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que carga la información de las Fuentes de Financiamiento 
	//                   Asociadas a la Estrutura Presupuestaria, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Arnaldo Suárez                     
	// Fecha Creación :  22/12/2008        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql= " SELECT spg_dt_fuentefinanciamiento.codfuefin, sigesp_fuentefinanciamiento.denfuefin, ".
			   "      (SELECT COALESCE(spg_cuenta_fuentefinanciamiento.monto,0) as monto  ".
			   "              FROM spg_cuenta_fuentefinanciamiento ".
			   "           WHERE spg_cuenta_fuentefinanciamiento.codemp = '".$as_codemp."' ".
			   "		     AND   spg_cuenta_fuentefinanciamiento.codestpro1 = '".$as_ep1."' ".	 
			   "		     AND   spg_cuenta_fuentefinanciamiento.codestpro2 = '".$as_ep2."' ".
			   "		     AND   spg_cuenta_fuentefinanciamiento.codestpro3 = '".$as_ep3."' ".	
			   "			 AND   spg_cuenta_fuentefinanciamiento.codestpro4 = '".$as_ep4."' ".	
			   "		     AND   spg_cuenta_fuentefinanciamiento.codestpro5 = '".$as_ep5."' ".
			   "		     AND   spg_cuenta_fuentefinanciamiento.estcla = '".$as_estcla."'".
			   "		     AND   spg_cuenta_fuentefinanciamiento.spg_cuenta = '".$as_cuenta."'".
			   "             AND   spg_cuenta_fuentefinanciamiento.codfuefin = spg_dt_fuentefinanciamiento.codfuefin) as monto ".
			   "		   	FROM spg_dt_fuentefinanciamiento, sigesp_fuentefinanciamiento ".
	  		   " WHERE spg_dt_fuentefinanciamiento.codemp    = sigesp_fuentefinanciamiento.codemp ".
			   "		AND   spg_dt_fuentefinanciamiento.codfuefin = sigesp_fuentefinanciamiento.codfuefin ".
			   "		AND   spg_dt_fuentefinanciamiento.codemp = '".$as_codemp."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro1 = '".$as_ep1."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro2 = '".$as_ep2."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro3 = '".$as_ep3."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro4 = '".$as_ep4."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro5 = '".$as_ep5."' ".
			   "		AND   spg_dt_fuentefinanciamiento.estcla = '".$as_estcla."'".
			   "        AND   sigesp_fuentefinanciamiento.codfuefin <> '--' ";	     
	  $rs_fuefin=$this->io_sql->select($ls_sql);
	  return $rs_fuefin;
}//uf_spg_load_fuefin_estrutura  
//---------------------------------------------------------------------------------------------------------------------------------


function uf_spg_insert_fuefin_estructura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla,$as_cuenta,$as_codfuefin,$ad_monto)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_insert_fuefin_estructura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica
	//                   $as_codfuefin --- codigo de la Fuente de Financiamiento
	//                   $ad_monto     --- monto asignado a la Cuenta por Fuente de Financiamiento
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que inserta la información de las Fuentes de Financiamiento 
	//                   Asociadas a la Estrutura Presupuestaria, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Arnaldo Suárez                     
	// Fecha Creación :  22/12/2008        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql= " INSERT INTO spg_cuenta_fuentefinanciamiento(codemp, codfuefin, codestpro1, codestpro2, codestpro3, codestpro4, ".
               "                                             codestpro5, estcla, spg_cuenta, monto)                             ".
               " VALUES ('".$as_codemp."', '".$as_codfuefin."', '".$as_ep1."', '".$as_ep2."', '".$as_ep3."', '".$as_ep4."',     ".
               "         '".$as_ep5."', '".$as_estcla."', '".$as_cuenta."',".$ad_monto."); ";	   	     
	  $li_row=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
	  if($li_row===false)
	  {
	   $lb_valido=false;
	   $this->io_msg->message("Error al Insertar Detalle Cuenta de Gasto con Fuente de Financiamiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
	  }
	  else
	  {
	   $lb_valido=true;
	  } 
	    return 	$lb_valido;
}//uf_spg_insert_fuefin_estructura

function uf_spg_update_fuefin_estructura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla,$as_cuenta,$as_codfuefin,$ad_monto)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_update_fuefin_estructura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica
	//                   $as_codfuefin --- codigo de la Fuente de Financiamiento
	//                   $ad_monto     --- monto asignado a la Cuenta por Fuente de Financiamiento
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que actualiza la información de las Fuentes de Financiamiento 
	//                   Asociadas a la Estrutura Presupuestaria, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Arnaldo Suárez                     
	// Fecha Creación :  22/12/2008        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql= " UPDATE spg_cuenta_fuentefinanciamiento SET  monto=".$ad_monto." ".
               " WHERE  spg_cuenta_fuentefinanciamiento.codemp = '".$as_codemp."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro1 = '".$as_ep1."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro2 = '".$as_ep2."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro3 = '".$as_ep3."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro4 = '".$as_ep4."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro5 = '".$as_ep5."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.estcla = '".$as_estcla."'".
			    "		AND   spg_cuenta_fuentefinanciamiento.codfuefin = '".$as_codfuefin."'".
			   "		AND   spg_cuenta_fuentefinanciamiento.spg_cuenta = '".$as_cuenta."'";    
	  $li_row=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
	  if($li_row===false)
	  {
	   $lb_valido=false;
	   $this->io_msg->message("Error al Actualizar Detalle Cuenta de Gasto con Fuente de Financiamiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
	  }
	  else
	  {
	   $lb_valido=true;
	  } 
	    return 	$lb_valido;
}//uf_spg_insert_fuefin_estructura  
//---------------------------------------------------------------------------------------------------------------------------------


function uf_spg_existe_fuefin_estructura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla,$as_cuenta,$as_codfuefin)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_update_fuefin_estructura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica
	//                   $as_codfuefin --- codigo de la Fuente de Financiamiento
	//                   $ad_monto     --- monto asignado a la Cuenta por Fuente de Financiamiento
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que actualiza la información de las Fuentes de Financiamiento 
	//                   Asociadas a la Estrutura Presupuestaria, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Arnaldo Suárez                     
	// Fecha Creación :  22/12/2008        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=false;
	  $ls_sql= " SELECT *  FROM spg_cuenta_fuentefinanciamiento ".
               " WHERE  spg_cuenta_fuentefinanciamiento.codemp = '".$as_codemp."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro1 = '".$as_ep1."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro2 = '".$as_ep2."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro3 = '".$as_ep3."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro4 = '".$as_ep4."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.codestpro5 = '".$as_ep5."' ".
			   "		AND   spg_cuenta_fuentefinanciamiento.estcla = '".$as_estcla."'".
			    "		AND   spg_cuenta_fuentefinanciamiento.codfuefin = '".$as_codfuefin."'".
			   "		AND   spg_cuenta_fuentefinanciamiento.spg_cuenta = '".$as_cuenta."'";	       
	  $li_row=$this->io_sql->select($ls_sql);                                                                                                                                                                                          
	  if($li_row===false)
	  {
	   $lb_valido=false;
	   $this->io_msg->message("Error al Actualizar Detalle Cuenta de Gasto con Fuente de Financiamiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
	  }
	  else
	  {
	   if($row=$this->io_sql->fetch_row($li_row))
	   {
	    $lb_valido = true;
	   }
	  } 
	    return 	$lb_valido;
}//uf_spg_existe_fuefin_estructura  
//---------------------------------------------------------------------------------------------------------------------------------

function uf_spg_existe_dt_fuefin_estructura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla,$as_codfuefin)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_update_dt_fuefin_estructura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica
	//                   $as_codfuefin --- codigo de la Fuente de Financiamiento
	//                   $ad_monto     --- monto asignado a la Cuenta por Fuente de Financiamiento
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que actualiza la información de las Fuentes de Financiamiento 
	//                   Asociadas a la Estrutura Presupuestaria, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Arnaldo Suárez                     
	// Fecha Creación :  22/12/2008        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=false;
	  $ls_sql= " SELECT *  FROM spg_dt_fuentefinanciamiento ".
               " WHERE  spg_dt_fuentefinanciamiento.codemp = '".$as_codemp."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro1 = '".$as_ep1."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro2 = '".$as_ep2."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro3 = '".$as_ep3."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro4 = '".$as_ep4."' ".
			   "		AND   spg_dt_fuentefinanciamiento.codestpro5 = '".$as_ep5."' ".
			   "		AND   spg_dt_fuentefinanciamiento.estcla = '".$as_estcla."'".
			    "		AND   spg_dt_fuentefinanciamiento.codfuefin = '".$as_codfuefin."'";    
	  $li_row=$this->io_sql->select($ls_sql);                                                                                                                                                                                          
	  if($li_row===false)
	  {
	   $lb_valido=false;
	   $this->io_msg->message("Error al Actualizar Detalle Cuenta de Gasto con Fuente de Financiamiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
	  }
	  else
	  {
	   if($row=$this->io_sql->fetch_row($li_row))
	   {
	    $lb_valido = true;
	   }
	  } 
	    return 	$lb_valido;
}//uf_spg_existe_dt_fuefin_estructura  
//---------------------------------------------------------------------------------------------------------------------------------

function uf_spg_insert_dt_fuefin_estructura($as_codemp,$as_ep1,$as_ep2,$as_ep3,$as_ep4,$as_ep5,$as_estcla,$as_codfuefin)
  { ////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_dt_insert_fuefin_estructura()                                   
	//	     Arguments:  $as_codemp --- codigo de la empresa    
	//                   $as_ep1.. $as_ep1 ---estructura programatica
	//                   $as_codfuefin --- codigo de la Fuente de Financiamiento
	//                   $ad_monto     --- monto asignado a la Cuenta por Fuente de Financiamiento
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Método que inserta la información de las Fuentes de Financiamiento 
	//                   Asociadas a la Estrutura Presupuestaria, Este proceso es utilizado en 
	//                   apertura de cuentas presupuestaria. 
	//     Creado por :  Ing. Arnaldo Suárez                     
	// Fecha Creación :  22/12/2008        Fecha última Modificacion :         
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $lb_valido=true;
	  $ls_sql= " INSERT INTO spg_dt_fuentefinanciamiento(codemp, codfuefin, codestpro1, codestpro2, codestpro3, codestpro4, ".
               "                                             codestpro5, estcla)                             ".
               " VALUES ('".$as_codemp."', '".$as_codfuefin."', '".$as_ep1."', '".$as_ep2."', '".$as_ep3."', '".$as_ep4."',     ".
               "         '".$as_ep5."', '".$as_estcla."'); ";	   	     
	  $li_row=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
	  if($li_row===false)
	  {
	   $lb_valido=false;
	   $this->io_msg->message("Error al Insertar Detalle de Fuente de Financiamiento, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
	  }
	  else
	  {
	   $lb_valido=true;
	  } 
	    return 	$lb_valido;
}//uf_spg_insert_dt_fuefin_estructura

}//fin de class_apertura
?>