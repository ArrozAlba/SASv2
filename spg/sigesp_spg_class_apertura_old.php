<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
/***************************************************************************************************************************************/
class sigesp_spg_class_apertura // extends class_sigesp_int_spg
{
   var $int_spg;
   function sigesp_spg_class_apertura()
   { 
		$this->io_function = new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		//$this->obj=new class_datastore();
		$this->io_msg=new class_mensajes();
		$this->sig_int=new class_sigesp_int();
		$this->int_spg=new class_sigesp_int_spg();
		$this->obj=new class_datastore();
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->io_fecha=new class_fecha();
	}
/***************************************************************************************************************************************/
  function uf_llenar_combo_estpro1($as_codemp)
  {
    $ls_sql="";
    
	$ls_sql=" SELECT codestpro1,denestpro1 FROM spg_ep1 WHERE codemp='".$as_codemp."' ";
	$rs_SPG=$this->io_sql->select($ls_sql);
	
  return $rs_SPG;
  }
/***************************************************************************************************************************************/
  function uf_llenar_combo_estpro2($as_codemp, $as_codestpro1)
  {
     $ls_sql="";
	            
	 $ls_sql=" SELECT codestpro2,denestpro2 FROM spg_ep2 WHERE codemp='".$as_codemp."' AND  codestpro1='".$as_codestpro1."' ";  
	 $rs_SPG=$this->io_sql->select($ls_sql);
	 
	 return $rs_SPG; 
  }//fin 
/***************************************************************************************************************************************/
   function uf_llenar_combo_estpro3($as_codemp, $as_codestpro1, $as_codestpro2)
  {
     $ls_sql="";
	            
	 $ls_sql=" SELECT codestpro3,denestpro3 FROM spg_ep3 WHERE codemp='".$as_codemp."' AND  codestpro1='".$as_codestpro1."' AND  codestpro2='".$as_codestpro2."'  ";  
	 $rs_SPG=$this->io_sql->select($ls_sql);
	 
	 return $rs_SPG; 
  }//fin
/***************************************************************************************************************************************/
  function uf_spg_load_cuentas_apertura($as_codemp,$as_ep1,$as_ep2,$as_ep3)
  {
	 //////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spg_load_cuentas_apertura
	 //	Access:  public
	 //	Description: Método que carga la información de la apertura de 
	 //              de cuentas en un data store, Este proceso es utilizado en 
	 //              apertura de cuentas presupuestaria.  
	 //////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  $ls_ep4="00";
	  $ls_ep5="00";
	  
	  $ls_sql= " SELECT spg_cuenta,status,denominacion,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre".
			   " FROM spg_cuentas  WHERE codemp='".$as_codemp."' AND codestpro1='".$as_ep1."' AND codestpro2='".$as_ep2."' AND ". 
			   " codestpro3='".$as_ep3."' AND codestpro4='".$ls_ep4."' AND  codestpro5='".$ls_ep5."' AND  status='C'  ORDER BY spg_cuenta  ";  
	  $rs_load=$this->io_sql->select($ls_sql);
	  return $rs_load;

}//uf_spg_load_cuentas_apertura  
/***************************************************************************************************************************************/
  function uf_spg_select_modalidad_apertura($as_codemp,&$as_estmodape)
  {
	 ////////////////////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spg_select_modalidad_apertura
	 //	Access:  public
	 // Arguments : $as_codemp // codigo de la empresa 
	 //             $as_estmodape  // modalidad de la apertura (referencia)
	 //	Description: Método que selecciona la modalidad de la apertura(mensual o trimestral). 
	 //              Este proceso es utilizado en  la apertura de cuentas presupuestaria.  
	 ///////////////////////////////////////////////////////////////////////////////////////////

	 $lb_valido=true;
     $ls_sql=" SELECT estmodape FROM sigesp_empresa s where codemp='".$as_codemp."' ";
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
/***************************************************************************************************************************************/
function uf_spg_procesar_apertura($aa_seguridad)
{
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
	 
	if (!$this->sig_int->uf_select_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->ldt_fecha))
	{
	   $lb_valido = $this->sig_int->uf_sigesp_insert_comprobante( $this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->ldt_fecha,$this->ii_tipo_comp,$this->is_descripcion,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben );
	   if ($lb_valido)
	   { 
		 //////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion =" Guardar la Apertura con procedencia ".$this->is_procedencia." del comprobante nro ".$this->is_comprobante." ";
		   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////	
			 $this->io_sql->commit();
			 $lb_valido=true;
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
		}
			
	}
	return $lb_valido;
}//uf_spg_procesar_apertura
/***************************************************************************************************************************************/
function uf_spg_guardar_apertura($ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
                                 $ad_m12,$estprog,$as_cuenta,$ad_asignado,$ai_distribuir,$aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_spg_guardar_apertura
	//	Access:  public
	//	Description: Método que recorre la información almacenada en un datastore el cual contiene la información generada o registrada 
	//               en cuanto a la información de las asignación de la apertura de cuentas  presupuestaria de gasto. 
	//               Si la información de la apertura de cuenta no existe, el método procederá a realizar un update en la 
	//               tabla  de spg_cuentas en cuanto a su asignación.
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    //$this->io_sql->begin_transaction();
		$lb_valido=true;
        $ldec_asignado_ant=0;	
		
		$this->int_spg->is_codemp=$this->is_codemp;
		$this->int_spg->is_procedencia=$this->is_procedencia;		
		$this->int_spg->is_comprobante=$this->is_comprobante;
		$this->int_spg->ii_tipo_comp=$this->ii_tipo_comp ;
		$this->int_spg->is_ced_ben =$this->is_ced_ben   ;
		$this->int_spg->is_cod_prov =$this->is_cod_prov  ;
		$this->int_spg->is_tipo=$this->is_tipo ;
		$this->int_spg->is_descripcion=$this->is_descripcion ;
		$this->int_spg->id_fecha=$this->id_fecha ;
		$ldt_fecha  = $this->io_function->uf_convertirdatetobd($this->id_fecha);
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
		if ($this->int_spg->uf_spg_select_movimiento($estprog,$as_cuenta,$this->is_procedencia,$this->is_comprobante,"AAP",&$ldec_monto,&$li_orden)) 
		{								
		  if ($ad_asignado <> 0) 
		  {
			$lb_valido = $this->int_spg->uf_spg_update_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_cod_prov,$this->is_ced_ben,$this->is_descripcion,$this->is_tipo, $this->ii_tipo_comp,$estprog,$estprog,$as_cuenta,$as_cuenta,$this->is_procedencia,$this->is_procedencia,$this->is_comprobante,$this->is_comprobante,$this->is_descripcion,$this->is_descripcion,'I','I',$ldec_monto,$ad_asignado);
			if($lb_valido)
			{
			 $lb_valido=$this->uf_update_distribucion($this->is_codemp,$estprog,$as_cuenta, $ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,
			                                                   $ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,$ai_distribuir,$as_cuenta,$ad_asignado,
										                       $ad_asignado,$ai_distribuir,$aa_seguridad);
			}												  
		  }					 	   											   
		  else
		  {						
			 $lb_valido = $this->int_spg->uf_int_spg_delete_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$ldt_fecha,$this->is_tipo,$ls_fuente,$this->is_cod_prov,$this->is_ced_ben,
											   $estprog,$as_cuenta,$this->is_procedencia,$this->is_comprobante,$this->is_descripcion,"I",$this->ii_tipo_comp,
											   $ldec_asignado_ant,$ad_asignado,$ls_sc_cuenta);
		  }
		}  
		else
		{	
		   //$this->int_spg->io_sql->begin_transaction();
		  if ($ad_asignado <> 0) 
		  {
			   $lb_valido = $this->int_spg->uf_int_spg_insert_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$ldt_fecha,$this->is_tipo,$ls_fuente,$this->is_cod_prov,$this->is_ced_ben,$estprog,$as_cuenta,$this->is_procedencia,$this->is_comprobante,$this->is_descripcion,"I",$ad_asignado,$ls_sc_cuenta,true);
			   if($lb_valido)
			   {
			  		$lb_valido=$this->uf_update_distribucion($this->is_codemp,$estprog,$as_cuenta, $ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,
															 $ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,$ai_distribuir,$as_cuenta,$ad_asignado,
															 $ai_distribuir,$aa_seguridad);
			   }
		   }				
		}
		/*if($lb_valido)
		{
		 //////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
		  $ls_evento="UPDATE";
		  $ls_descripcion =" Guardar la Apertura con procedencia ".$this->is_procedencia." del comprobante nro ".$this->is_comprobante." ";
		  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////	
			 $this->io_sql->commit();
			  $lb_valido=true;
			  print " COMMIT  "."<br>";
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
			 print " ROOLBACK  "."<br>";
		}	*/
	   return $lb_valido;
}// fin 

/***************************************************************************************************************************************//***************************************************************************************************************************************/
function uf_update_saldos_apertura($as_codemp,$adec_m1,$adec_m2,$adec_m3,$adec_m4,$adec_m5,$adec_m6,$adec_m7,$adec_m8,$adec_m9,$adec_m10,
                                   $adec_m11,$adec_m12,$estprog,$as_spg_cuenta,$ai_distribuir)
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
/***************************************************************************************************************************************/
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
		/*if($lb_valido)
		{
		 //////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
		  $ls_evento="UPDATE";
		  $ls_descripcion =" Guardar la Apertura con procedencia ".$this->is_procedencia." del comprobante nro ".$this->is_comprobante." ";
		  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////	
			 $this->io_sql->commit();
			  $lb_valido=true;
			  print " COMMIT  "."<br>";
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
			 print " ROOLBACK  "."<br>";
		}*/
		//if($lb_valido)
	    //{
			if($this->int_spg->uf_spg_obtener_nivel( $ls_nextcuenta ) == 1)
			{ 
			  break;
			}
			$ls_nextcuenta = $this->int_spg->uf_spg_next_cuenta_nivel($ls_nextcuenta);
			$li_nivel = $this->int_spg->uf_spg_obtener_nivel( $ls_nextcuenta );
	   //}
   }//while	
   return $lb_valido;
}//fin
/************************************************************************************************************************/

function procesar_guardar_apertura($ar_datos,$estprog,$aa_seguridad,$ai_num)   
{   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  procesar_guardar_apertura()                                   
	//	     Arguments:  $ar_datos --- codigo de la empresa    
	//                   $estprog --- estructura programatica        
	//                   $aa_seguridad--- arreglo de la seguridad
	//                   $data  --- data 
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para procesar el guardar de la apertura
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  11/04/2006        Fecha última Modificacion :         
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
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
		
	    $lb_valido=$this->uf_spg_guardar_apertura($ld_m1,$ld_m2,$ld_m3,$ld_m4,$ld_m5,$ld_m6,$ld_m7,$ld_m8,$ld_m9,$ld_m10,$ld_m11,
                                                  $ld_m12,$estprog,$ls_cuenta,$ld_asignado,$li_distribuir,$aa_seguridad);
  }//for
  return $lb_valido;
}//fin	
/********************************************************************************************************************************/
 function uf_spg_obtener_montos_cuenta($as_codemp,$as_cuenta,$aa_estprog,$aa_montos)
  {
	 //////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spg_obtener_montos_cuenta
	 //	Access:  public
	 //	Description: Método obtiene los monto programados para la cuenta enviada
	 // Desarrollado por: Ing. Nelson Barraez
	 //////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  
	  $ls_sql= " SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre".
			   " FROM spg_cuentas  WHERE codemp='".$as_codemp."' AND codestpro1='".$aa_estprog[0]."' AND codestpro2='".$aa_estprog[1]."' AND ". 
			   " codestpro3='".$aa_estprog[2]."' AND codestpro4='".$aa_estprog[3]."' AND  codestpro5='".$aa_estprog[4]."' AND spg_cuenta='".$as_cuenta."'";  

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

/************************************************************************************************************************************/

function uf_obtener_hijos($as_codemp,$as_spg_cuenta,$aa_estprog)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Function :        uf_obtener_hijos
// Descripcion:      -Metodo que retorna las cuentas hijas de la cuenta enviada.
// Desarrollado por: Ing. Nelson Barraez
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql = " SELECT spg_cuenta 
					FROM spg_cuentas 
					WHERE spg_cuenta like '".$as_spg_cuenta."%' AND codestpro1='".$aa_estprog[0]."' AND codestpro2='".$aa_estprog[1]."' 
					AND codestpro3='".$aa_estprog[2]."' AND codestpro4='".$aa_estprog[3]."' AND codestpro5='".$aa_estprog[4]."'  AND status='C'
					ORDER  by spg_cuenta " ;
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
}//fin de class_apertura
?>