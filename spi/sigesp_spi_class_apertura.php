<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
require_once("class_folder/class_funciones_spi.php");

//-----------------------------------------------------------------------------------------------------------------------------------
class sigesp_spi_class_apertura 
{
   var $int_spi;
   function sigesp_spi_class_apertura()
   { 
		/////////////////////////////////////////////////////////
		//	Function:     sigesp_spi_class_apertura
		//	Description:  Constructor de la Clase
		/////////////////////////////////////////////////////////
		$this->io_function = new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->io_msg=new class_mensajes();
		$this->sig_int=new class_sigesp_int();
		$this->int_spi=new class_sigesp_int_spi();
		$this->obj=new class_datastore();
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->io_fecha=new class_fecha();

		
		// Agregado para la conversión
		$this->io_class_spi = new class_funciones_spi();
		$this->io_rcbsf		= new sigesp_c_reconvertir_monedabsf();
		$this->li_candeccon = $_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon = $_SESSION["la_empresa"]["redconmon"];
        //Agregado para la conversión

        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_estpreing=$_SESSION["la_empresa"]["estpreing"];/// manejo de Estructura
	}
//---------------------------------------------------------------------------------------------------------------------------------
  function uf_spi_load_cuentas_apertura(&$rs_data)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_load_cuentas_apertura 
	//	     Arguments:  $rs_data // resulset con la data (referencia)
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que carga la información de la apertura de  de cuentas en un resulset, 
	//                   Este proceso es utilizado en apertura de cuentas presupuestaria de ingreso.  
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  11/07/2006          Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
    $ls_sql= " SELECT   spi_cuenta,denominacion,status,previsto,distribuir,enero,febrero,marzo, ".
	         "          abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre".
			 " FROM     spi_cuentas  ".
			 " WHERE    codemp='".$this->ls_codemp."'  AND status='C'  ".
			 " ORDER BY spi_cuenta  "; //print $ls_sql;
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_spi_load_cuentas_apertura ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return $lb_valido;

}//uf_spg_load_cuentas_apertura  
//-----------------------------------------------------------------------------------------------------------------------------------
  function uf_spi_select_modalidad_apertura(&$as_estmodape)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_select_modalidad_apertura 
	//	     Arguments:  $as_estmodape // estatus de la modalidad de la apertura
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que selecciona la modalidad de la apertura(mensual o trimestral). 
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  11/07/2006          Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	 $lb_valido=true;
     $ls_sql=" SELECT estmodape FROM sigesp_empresa  where codemp='".$this->ls_codemp."' ";
	 $li_select=$this->io_sql->select($ls_sql);                                                                                                                                                                                          
	 if($li_select===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->class_apertura_ingreso MÉTODO->uf_spi_select_modalidad_apertura ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
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
}//fin  uf_spi_select_modalidad_apertura
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_spi_procesar_apertura($aa_seguridad)
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_procesar_apertura 
	//	     Arguments:  $aa_seguridad // arreglo de  seguridad 
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que selecciona la modalidad de la apertura(mensual o trimestral). 
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  11/07/2006          Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$this->is_codemp  =  $this->ls_codemp;
	$ls_formspi =  $_SESSION["la_empresa"]["formspi"];
	$ld_periodo =  $_SESSION["la_empresa"]["periodo"];
	$this->is_procedencia = "SPIAPR";
	$this->is_comprobante = "0000000APERTURA";
	$this->ii_tipo_comp   = 2;
	$this->is_ced_ben     = "----------";
	$this->is_cod_prov    = "----------";
	$this->is_tipo        = "-";
	$this->is_descripcion = "APERTURA DE CUENTAS";
	$this->as_codban  	  = "---";
	$this->as_ctaban      = "-------------------------";
	
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
	   $lb_valido = $this->sig_int->uf_sigesp_insert_comprobante( $this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->ldt_fecha,$this->ii_tipo_comp,$this->is_descripcion,$this->is_tipo,$this->is_cod_prov,$this->is_ced_ben,$this->as_codban,$this->as_ctaban);
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
}//uf_spi_procesar_apertura
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_spi_guardar_apertura($ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,
                                 $as_cuenta,$ad_previsto,$ai_distribuir,$estprog,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function:   uf_spi_guardar_apertura
	//	  Description:   Método que recorre la información almacenada en un datastore el cual contiene la información generada o    //                     registrada 
	//                   en cuanto a la información de las asignación de la apertura de cuentas  presupuestaria de gasto. 
	//                   Si la información de la apertura de cuenta no existe, el método procederá a realizar un update en la 
	//                   tabla  de spg_cuentas en cuanto a su asignación.
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  13/07/2006          Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
        $ldec_previsto_ant=0;	
		$this->int_spi->is_codemp		=$this->is_codemp;
		$this->int_spi->is_procedencia  =$this->is_procedencia;		
		$this->int_spi->is_comprobante	=$this->is_comprobante;
		$this->int_spi->ii_tipo_comp	=$this->ii_tipo_comp ;
		$this->int_spi->is_ced_ben 		=$this->is_ced_ben   ;
		$this->int_spi->is_cod_prov 	=$this->is_cod_prov  ;
		$this->int_spi->is_tipo			=$this->is_tipo ;
		$this->int_spi->is_descripcion	=$this->is_descripcion ;
		$this->int_spi->id_fecha		=$this->id_fecha ;
		$ldt_fecha  					=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$_SESSION["fechacomprobante"]=$ldt_fecha;
		$this->int_spi->as_codban=$this->as_codban; //  POR LAS INTEGRADORAS NUEVAS 
		$this->int_spi->as_ctaban=$this->as_ctaban; //  POR LAS INTEGRADORAS NUEVAS

		$ls_estpreing=$this->ls_estpreing;
		
		$ls_denominacion="";	
		$ls_status="";	
		$ls_sc_cuenta="";
		$acum_ad_m1=0;
		$acum_previsto=0;
		
		if($ls_estpreing=='1')
		{
			$ls_codestpro1=$estprog[0]; 
	        $ls_codestpro2=$estprog[1];
		    $ls_codestpro3=$estprog[2];
		    $ls_codestpro4=$estprog[3];
		    $ls_codestpro5=$estprog[4];
		    $ls_estcla=$estprog[5];	
		}
		else
		{
		    $ls_codestpro1="-------------------------";
		    $ls_codestpro2="-------------------------";
			$ls_codestpro3="-------------------------";
			$ls_codestpro4="-------------------------";
			$ls_codestpro5="-------------------------";
			$ls_estcla="-";
			$la_codestpro[0]=$ls_codestpro1;
			$la_codestpro[1]=$ls_codestpro2;
			$la_codestpro[2]=$ls_codestpro3;
			$la_codestpro[3]=$ls_codestpro4;
			$la_codestpro[4]=$ls_codestpro5;
			$la_codestpro[5]=$ls_estcla;
			$lb_valido=$this->uf_check_estructurapresupuestaria($this->is_codemp,$la_codestpro,5);
			if($lb_valido)
			{
			 $lb_existe = $this->uf_check_spi_cuenta_estructurapresupuestaria($this->is_codemp,$as_cuenta,$la_codestpro);
			 if (!$lb_existe)
			 {
			   $lb_valido = $this->uf_spi_insert_sipcuentas_estructuras($this->is_codemp,$as_cuenta,$la_codestpro,$ad_previsto,$ad_m1,$ad_m2,
					                                                         $ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
																			 $ad_m12,$aa_seguridad);
		     }															 
			}
		}
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
		if($ls_estpreing=='1')
		{
		  if(!$this->int_spi->uf_spi_select_cuenta_estructuras($this->is_codemp,$as_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta,
															   $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
															   $ls_codestpro5,$ls_estcla))
		  {
			return false;												   
		  }
															   
		}
		else
		{
		   if(!$this->int_spi->uf_spi_select_cuenta($this->is_codemp,$as_cuenta,&$ls_status,&$ls_denominacion,&$ls_sc_cuenta))
		   {
		      return false;		
		   }
		}
               
		if ($this->int_spi->uf_spi_select_movimiento($as_cuenta,$this->is_procedencia,$this->is_comprobante,"PRE",&$ldec_monto,&$li_orden,
		                                             $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
													 $ls_codestpro5,$ls_estcla))
		{								
		  if ($ad_previsto <> 0) 
		  {  
		    $lb_valido = $this->uf_spi_buscar_estructuras($this->is_codemp,$estprog,$as_cuenta,&$lb_vali);
			//Obtengo los montos de los hijos de la cuenta actual y los acumulo.	
			if ($ls_estpreing=='1')// si maneja estructura con las cuentas de ingreso
			{

				$lb_valido = $this->int_spi->uf_spi_update_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
			                                                      $this->id_fecha,$this->is_cod_prov,$this->is_ced_ben,
																  $this->is_descripcion,$this->is_tipo, $this->ii_tipo_comp,
																  $as_cuenta,$as_cuenta,$this->is_procedencia,$this->is_procedencia,
																  $this->is_comprobante,$this->is_comprobante,$this->is_descripcion,
																  $this->is_descripcion,'I','I',$ldec_monto,$ad_previsto,
																  $this->as_codban,$this->as_ctaban,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																  $ls_codestpro4,$ls_codestpro5,$ls_estcla); 
			}
			else
			{  
				$acum_ad_m1=$ad_m1; 
				$acum_ad_m2=$ad_m2; 
				$acum_ad_m3=$ad_m3; 
				$acum_ad_m4=$ad_m4;
				$acum_ad_m5=$ad_m5;
				$acum_ad_m6=$ad_m6;
				$acum_ad_m7=$ad_m7;
				$acum_ad_m8=$ad_m8;
				$acum_ad_m9=$ad_m9;			
				$acum_ad_m10=$ad_m10;
				$acum_ad_m11=$ad_m11;
				$acum_ad_m12=$ad_m12;
				$acum_ad_previsto = $ad_previsto; 
				$ls_codestpro1="-------------------------";
				$ls_codestpro2="-------------------------";
				$ls_codestpro3="-------------------------";
				$ls_codestpro4="-------------------------";
			 	$ls_codestpro5="-------------------------";
				$ls_estcla="-";
				$lb_valido = $this->int_spi->uf_spi_update_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
			                                                      $this->id_fecha,$this->is_cod_prov,$this->is_ced_ben,
																  $this->is_descripcion,$this->is_tipo, $this->ii_tipo_comp,
																  $as_cuenta,$as_cuenta,$this->is_procedencia,$this->is_procedencia,
																  $this->is_comprobante,$this->is_comprobante,$this->is_descripcion,
																  $this->is_descripcion,'I','I',$ldec_monto,$acum_ad_previsto,
																  $this->as_codban,$this->as_ctaban,$ls_codestpro1,
																  $ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
			 						                              $ls_codestpro5,$ls_estcla);
			}

			if($lb_valido)
			{
			    /* $lb_valido=$this->uf_update_distribucion($this->is_codemp,$as_cuenta,$acum_ad_m1,$acum_ad_m2,$acum_ad_m3,$acum_ad_m4,
														  $acum_ad_m5,$acum_ad_m6,$acum_ad_m7,$acum_ad_m8,$acum_ad_m9,$acum_ad_m10,
														  $acum_ad_m11,$acum_ad_m12,$ai_distribuir,
														  $as_cuenta,$acum_ad_previsto,$aa_seguridad);*/
				if(($lb_vali==1)&&($ls_estpreing == 1))
				{ 
					$lb_valido = $this->uf_spi_update_sipcuentas_estructuras($this->is_codemp,$as_cuenta,$estprog,$ad_previsto,$ad_m1,$ad_m2,
					                                                         $ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
																			 $ad_m12,$aa_seguridad);
				}
				elseif(($lb_vali==0)&&($ls_estpreing == 1))
				{ 
					$lb_valido = $this->uf_spi_insert_sipcuentas_estructuras($this->is_codemp,$as_cuenta,$estprog,$ad_previsto,$ad_m1,$ad_m2,
					                                                         $ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
																			 $ad_m12,$aa_seguridad);
				}
				if($lb_valido)
				{	
				
				   if ($ls_estpreing=='1')// si maneja estructura con las cuentas de ingreso
			       {
				  	$this->uf_spi_buscar_meses_cuentas($this->is_codemp,$as_cuenta,$aa_montos_acum);
					$acum_ad_m1=$aa_montos_acum["enero"]; 
					$acum_ad_m2=$aa_montos_acum["febrero"]; 
					$acum_ad_m3=$aa_montos_acum["marzo"]; 
					$acum_ad_m4=$aa_montos_acum["abril"];
					$acum_ad_m5=$aa_montos_acum["mayo"];
					$acum_ad_m6=$aa_montos_acum["junio"];
					$acum_ad_m7=$aa_montos_acum["julio"];
					$acum_ad_m8=$aa_montos_acum["agosto"];
					$acum_ad_m9=$aa_montos_acum["septiembre"];			
					$acum_ad_m10=$aa_montos_acum["octubre"];
					$acum_ad_m11=$aa_montos_acum["noviembre"];
					$acum_ad_m12=$aa_montos_acum["diciembre"];
					$acum_ad_previsto=$aa_montos_acum["previsto"];
				   }
				   else
				   {
				    $acum_ad_m1=$ad_m1; 
					$acum_ad_m2=$ad_m2; 
					$acum_ad_m3=$ad_m3; 
					$acum_ad_m4=$ad_m4;
					$acum_ad_m5=$ad_m5;
					$acum_ad_m6=$ad_m6;
					$acum_ad_m7=$ad_m7;
					$acum_ad_m8=$ad_m8;
					$acum_ad_m9=$ad_m9;			
					$acum_ad_m10=$ad_m10;
					$acum_ad_m11=$ad_m11;
					$acum_ad_m12=$ad_m12;
					$acum_ad_previsto = $ad_previsto;
				   }  
				    $lb_valido=$this->uf_update_distribucion($this->is_codemp,$as_cuenta,$acum_ad_m1,$acum_ad_m2,$acum_ad_m3,$acum_ad_m4,
															 $acum_ad_m5,$acum_ad_m6,$acum_ad_m7,$acum_ad_m8,$acum_ad_m9,$acum_ad_m10,
															 $acum_ad_m11,$acum_ad_m12,$ai_distribuir,$acum_ad_previsto,$aa_seguridad);
				}
			}
		  }					 	   											   
		  else
		  {				
			 $lb_valido = $this->int_spi->uf_int_spi_delete_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,
			                                                           $ldt_fecha,$this->is_tipo,$ls_fuente,$this->is_cod_prov,
																	   $this->is_ced_ben,$as_cuenta,$this->is_procedencia,
																	   $this->is_comprobante,$this->is_descripcion,"I",
											                           $this->ii_tipo_comp,$ldec_previsto_ant,$ad_previsto,
																	   $ls_sc_cuenta,$this->as_codban,$this->as_ctaban,
																	   $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																  	   $ls_codestpro4,$ls_codestpro5,$ls_estcla);
		  }
		  
		}  
		else // No existe el Movimiento
		{	
		  if ($ad_previsto <> 0) 
		  { 
			   $lb_valido = $this->int_spi->uf_int_spi_insert_movimiento($this->is_codemp,$this->is_procedencia,
			                                                             $this->is_comprobante,
			                                                             $ldt_fecha,$this->is_tipo,$ls_fuente,
																		 $this->is_cod_prov,
																		 $this->is_ced_ben,$as_cuenta,
																		 $this->is_procedencia,
																		 $this->is_comprobante,$this->is_descripcion,
																		 "I",$ad_previsto,
																		 $ls_sc_cuenta,true,$this->as_codban,$this->as_ctaban,
																		 $ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																		 $ls_codestpro4,$ls_codestpro5,$ls_estcla);
			   if($lb_valido)
			   {  
			        $lb_valido = $this->uf_spi_update_sipcuentas_estructuras($this->is_codemp,$as_cuenta,$estprog,$ad_previsto,$ad_m1,$ad_m2,
					                                                         $ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
																			 $ad_m12,$aa_seguridad);
																			 
				   if ($ls_estpreing=='1')// si maneja estructura con las cuentas de ingreso
			       {
				  	$this->uf_spi_buscar_meses_cuentas($this->is_codemp,$as_cuenta,$aa_montos_acum);
					$acum_ad_m1=$aa_montos_acum["enero"]; 
					$acum_ad_m2=$aa_montos_acum["febrero"]; 
					$acum_ad_m3=$aa_montos_acum["marzo"]; 
					$acum_ad_m4=$aa_montos_acum["abril"];
					$acum_ad_m5=$aa_montos_acum["mayo"];
					$acum_ad_m6=$aa_montos_acum["junio"];
					$acum_ad_m7=$aa_montos_acum["julio"];
					$acum_ad_m8=$aa_montos_acum["agosto"];
					$acum_ad_m9=$aa_montos_acum["septiembre"];			
					$acum_ad_m10=$aa_montos_acum["octubre"];
					$acum_ad_m11=$aa_montos_acum["noviembre"];
					$acum_ad_m12=$aa_montos_acum["diciembre"];
					$acum_ad_previsto=$aa_montos_acum["previsto"];
				   }
				   else
				   {
				    $acum_ad_m1=$ad_m1; 
					$acum_ad_m2=$ad_m2; 
					$acum_ad_m3=$ad_m3; 
					$acum_ad_m4=$ad_m4;
					$acum_ad_m5=$ad_m5;
					$acum_ad_m6=$ad_m6;
					$acum_ad_m7=$ad_m7;
					$acum_ad_m8=$ad_m8;
					$acum_ad_m9=$ad_m9;			
					$acum_ad_m10=$ad_m10;
					$acum_ad_m11=$ad_m11;
					$acum_ad_m12=$ad_m12;
					$acum_ad_previsto = $ad_previsto;
				   } 
			  
					if ($lb_valido)
					{
					 $lb_valido=$this->uf_update_distribucion($this->is_codemp,$as_cuenta, $acum_ad_m1,$acum_ad_m2,$acum_ad_m3,$acum_ad_m4,
															 $acum_ad_m5,$acum_ad_m6,$acum_ad_m7,$acum_ad_m8,$acum_ad_m9,$acum_ad_m10,
															 $acum_ad_m11,$acum_ad_m12,$ai_distribuir,$acum_ad_previsto,$aa_seguridad);	
					}										 
					
			   }		   	  
			 /*  if ($lb_valido)
			   {
			    $lb_valido =$this->io_class_spi->uf_convertir_spidtcmp($this->is_procedencia,$this->is_comprobante,$ldt_fecha,$this->as_codban,$this->as_ctaban,$aa_seguridad);
			   }
			   if ($lb_valido)
			   {
			   $lb_valido =$this->io_class_spi->uf_convertir_sigespcmp($this->is_procedencia,$this->is_comprobante,$ldt_fecha,$this->as_codban,$this->as_ctaban,$aa_seguridad);
			   }*/
		   
		   }				
		} 
	   return $lb_valido;
}// fin 

//---------------------------------------------------------------------------------------------------------------------------------------
function uf_update_saldos_apertura($as_codemp,$adec_m1,$adec_m2,$adec_m3,$adec_m4,$adec_m5,$adec_m6,$adec_m7,$adec_m8,$adec_m9,$adec_m10,
                                   $adec_m11,$adec_m12,$as_spi_cuenta,$ai_distribuir,$ad_previsto)
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
	// Fecha Creación :  13/07/2006        Fecha última Modificacion :         
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     
		$ls_sql=" UPDATE spi_cuentas  ".
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
				"        diciembre=".$adec_m12.", ".
				"        previsto=".$ad_previsto." ".
				" WHERE  codemp='".$as_codemp."' AND ".
				"        spi_cuenta = '".$as_spi_cuenta."' ";
		//print $ls_sql."<br><br><br>";		
		$li_row=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
		if($li_row===false)
		{
			$lb_valido=false;
		    $this->io_msg->message("Error en actualización de saldos, ".$this->io_function->uf_convertirmsg($this->io_sql->message));		 
		}
		else
		{ 
			$lb_valido=true;
		    /* $this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m1);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m2);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m3);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m4);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m5);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m6);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m7);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m8);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m9);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m10);

				$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m11);
				
				$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
				$this->io_rcbsf->io_ds_datos->insertRow("monto",$adec_m12);
	
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_codemp);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
				
				$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spi_cuenta");
				$this->io_rcbsf->io_ds_filtro->insertRow("valor",$as_spi_cuenta);
				$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");*/
				
				
				
				//$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spi_cuentas",$this->li_candeccon,$this->li_tipconmon,$this->li_redconmon,"");
		} 
	    return 	$lb_valido;
}
//-------------------------------------------------------------------------------------------------------------------------------------------
function uf_update_distribucion($as_codemp,$as_cuenta,$ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,
								$ai_distribuir,$ad_previsto,$aa_seguridad)
{ //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	      Function:  uf_update_distribucion_apertura()                                   
//	     Arguments:  $as_codemp --- codigo de la empresa    
//                   $as_cuenta --- cuenta                 
//	       Returns:  True si es correcto o false es otro caso                  
//	   Description:  Funcion que se usa  para actualizar los saldos de la distribucion y de las cuentas madres 
//     Creado por :  Ing. Nelson Barraez                                 
// Fecha Creación :  11/04/2006        Ultima Modificacion :  Ing. Yozelin Barraagán  ---->  Fecha: 13/07/2006       
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
	//$this->io_sql->begin_transaction();
	$lb_valido=true;
	$li_nivel = $this->int_spi->uf_spi_obtener_nivel($as_cuenta); 
	$ls_nextcuenta=$as_cuenta;
	//Distribuyo los montos para la cuenta actual.
	$lb_valido=$this->uf_update_saldos_apertura($as_codemp,$ad_m1,$ad_m2,$ad_m3,$ad_m4,$ad_m5,$ad_m6,
								                 $ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,
												 $ls_nextcuenta,$ai_distribuir,$ad_previsto);
	//Obtengo la cuenta anterior.
	$ls_nextcuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_nextcuenta);
	while(($lb_valido)&&($li_nivel>=1))
	{  
	    $ld_enero=0;		$ld_febrero=0; $ld_marzo=0;   $ld_abril=0;        $ld_mayo=0;
 	    $ld_junio=0; 		$ld_julio=0;   $ld_agosto=0;  $ld_septiembre=0;	  $ld_octubre=0;
	    $ld_noviembre=0;	$ld_diciembre=0;	$ld_acumprevisto = 0;	
        //Obtengo la cuenta sin ceros para buscar todos los hijos.
	    $ls_cta_sin_ceros=$this->int_spi->uf_spi_cuenta_sin_cero($ls_nextcuenta);
	    //Obtengo los hijos de la cuenta.
	    $aa_hijos=$this->uf_obtener_hijos($as_codemp,$ls_cta_sin_ceros);
  	    $li_total_hijos=count($aa_hijos);
		for($li_i=1;$li_i<=$li_total_hijos;$li_i++)
		{ 
			$ls_cuenta_spi=$aa_hijos[$li_i];
			//Obtengo los montos de los hijos de la cuenta actual y los acumulo.		
			$this->uf_spi_obtener_montos_cuenta($as_codemp,$ls_cuenta_spi,&$aa_montos);
			$ld_acumprevisto = $ld_acumprevisto + $aa_montos["previsto"];
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
		$ad_previsto = $ld_acumprevisto;
		$lb_valido=$this->uf_update_saldos_apertura($as_codemp,$ld_enero,$ld_febrero,$ld_marzo,$ld_abril,$ld_mayo,$ld_junio,
									                $ld_julio,$ld_agosto,$ld_septiembre,$ld_octubre,$ld_noviembre,$ld_diciembre,
													$ls_nextcuenta,$ai_distribuir,$ad_previsto);
		if($this->int_spi->uf_spi_obtener_nivel( $ls_nextcuenta ) == 1)
		{ 
		  break;
		}
		$ls_nextcuenta = $this->int_spi->uf_spi_next_cuenta_nivel($ls_nextcuenta);
		$li_nivel = $this->int_spi->uf_spi_obtener_nivel( $ls_nextcuenta );
   }//while	
   return $lb_valido;
}//fin
//-----------------------------------------------------------------------------------------------------------------------------------
function procesar_guardar_apertura($ar_datos,$estprog,$aa_seguridad,$ai_num)   
{   //////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  procesar_guardar_apertura()                                   
	//	     Arguments:  $ar_datos --- codigo de la empresa    
	//                   $estprog --- estructura programatica        
	//                   $aa_seguridad--- arreglo de la seguridad
	//                   $data  --- data 
	//	       Returns:  True si es correcto o false es otro caso                  
	//	   Description:  Funcion que se usa  para procesar el guardar de la apertura
	//     Creado por :  Ing. Yozelin Barragán                                 
	// Fecha Creación :  11/04/2006        Fecha última Modificacion :         
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	for($i=1;$i<=$ai_num;$i++)
	{	
		$ls_cuenta=$ar_datos["spi_cuenta"][$i];
		$ls_denominacion=$ar_datos["denominacion"][$i];
		$ld_previsto=$ar_datos["previsto"][$i];
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
	
		
	    $lb_valido=$this->uf_spi_guardar_apertura($ld_m1,$ld_m2,$ld_m3,$ld_m4,$ld_m5,$ld_m6,$ld_m7,$ld_m8,$ld_m9,$ld_m10,$ld_m11,
                                                  $ld_m12,$ls_cuenta,$ld_previsto,$li_distribuir,$estprog,$aa_seguridad);
  }//for
  return $lb_valido;
}//fin	
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_spi_obtener_montos_cuenta($as_codemp,$as_cuenta,$aa_montos)
  {
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spi_obtener_montos_cuenta
	 //	Access:  public
	 //	Description: Método obtiene los monto programados para la cuenta enviada
	 // Desarrollado por: Ing. Nelson Barraez   Modificado: Ing.Yozelin Barragán  ----> Fecha:13/07/2006
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  
/*	  if ($this->ls_estpreing !='1')// si maneja estructura con las cuentas de ingreso
	  {*/
	   $ls_sql= " SELECT previsto,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre".
			   " FROM   spi_cuentas  WHERE codemp='".$as_codemp."' AND spi_cuenta='".$as_cuenta."' ";  
	 /* }
	  else
	  {
	   $ls_cta_sin_ceros=$this->int_spi->uf_spi_cuenta_sin_cero($as_cuenta);
	   $ls_sql= " SELECT enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre".
			    " FROM   spi_cuentas  WHERE codemp='".$as_codemp."' AND spi_cuenta like '".$ls_cta_sin_ceros."'% "; 
	   
	  }	*/
	  //print $ls_sql."<br><br>";	   
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
		  		$aa_montos["previsto"]=$row["previsto"];
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
			 	$aa_montos["previsto"]  = 0;
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
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_obtener_hijos($as_codemp,$as_spi_cuenta)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Function :        uf_obtener_hijos
	// Descripcion:      Metodo que retorna las cuentas hijas de la cuenta enviada.
	// Desarrollado por: Ing. Nelson Barraez   Modificado: Ing. Yozelin Baragán --> Fecha:13/07/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_sql = " SELECT spi_cuenta ".
			  "	FROM   spi_cuentas ".
			  " WHERE  spi_cuenta like '".$as_spi_cuenta."%'  AND status='C' ".
			  "	ORDER  BY spi_cuenta " ;
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
			$ls_sc_cuenta  =  $row["spi_cuenta"];
			$data[$i]=$ls_sc_cuenta;
			$i=$i+1;
		}// cierre del while
	}
   return $data;
 }//uf_obtener_hijos
//-----------------------------------------------------------------------------------------------------------------------------------
  function uf_spi_insert_sipcuentas_estructuras($as_codemp,$as_cuenta,$estprog,$ad_previsto,$ad_m1,$ad_m2,$ad_m3,$ad_m4,
                                                $ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,$ad_m12,$aa_seguridad)
  {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_insert_sipcuentas_estructuras 
	//	     Arguments:  $estprog // estructura programática
	//                   $ad_previsto // monto previsto
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que inserta en la tabla spi_cuentas_estructura la cuenta de ingreso con su respectiva estructura. 
	//     Creado por :  
	// Fecha Creación :            Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	 $lb_valido=true;
	 $as_codestpro1=$estprog[0];
	 $as_codestpro2=$estprog[1];
	 $as_codestpro3=$estprog[2];
	 $as_codestpro4=$estprog[3];
	 $as_codestpro5=$estprog[4];
	 $as_estcla=$estprog[5];	 
	 
     $ls_sql="INSERT INTO spi_cuentas_estructuras (codemp, spi_cuenta, codestpro1, codestpro2, codestpro3, codestpro4, ".
             "									   codestpro5, estcla, previsto, enero, febrero, marzo, abril, mayo, ".
             "									   junio, julio, agosto, septiembre, octubre, noviembre, diciembre) ".
		     "   VALUES('".$as_codemp."','".$as_cuenta."','".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."', ".
			 "          '".$as_codestpro4."','".$as_codestpro5."','".$as_estcla."',".$ad_previsto.",".$ad_m1.",".$ad_m2.",".$ad_m3.",".$ad_m4.",".
             "           ".$ad_m5.",".$ad_m6.",".$ad_m7.",".$ad_m8.",".$ad_m9.",".$ad_m10.",".$ad_m11.",".$ad_m12.")" ;
	 
	 ///print $ls_sql."<br><br><br>";
	 $li_select=$this->io_sql->execute($ls_sql);                                                                                                                                                                                           
	 if($li_select===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->class_apertura_ingreso MÉTODO->uf_spi_insert_sipcuentas_estructuras ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	 }
	 else
	 {
	      $as_programatica=$estprog[0].$estprog[1].$estprog[2].$estprog[3].$estprog[4];
		 //////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion =" Insertó la cuenta de Ingreso ".$as_cuenta." asociada a la estructura programatica ".$as_programatica." y el estatus ".$as_estcla;
		   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////	
		    if($lb_valido)
			{
				 $this->io_sql->commit();
				 $lb_valido=true;
			}
			else
			{
				 $this->io_sql->rollback();
				 $lb_valido=false;
			}
	 } 
	 return  $lb_valido;
   }//fin  uf_spi_insert_sipcuentas_estructuras
//-----------------------------------------------------------------------------------------------------------------------------------
  function uf_spi_update_sipcuentas_estructuras($as_codemp,$as_cuenta,$estprog,$ad_previsto,$ad_m1,$ad_m2,
												$ad_m3,$ad_m4,$ad_m5,$ad_m6,$ad_m7,$ad_m8,$ad_m9,$ad_m10,$ad_m11,
												$ad_m12,$aa_seguridad)
  {
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_update_sipcuentas_estructuras 
	//	     Arguments:  $estprog // estructura programática
	//                   $ad_previsto // monto previsto
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que inserta en la tabla spi_cuentas_estructura la cuenta de ingreso con su respectiva estructura. 
	//     Creado por :  
	// Fecha Creación :            Fecha última Modificacion : 17/03/2009
	// Modificado por : Ing. Arnaldo Suárez
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	 $lb_valido=true;
	 $as_codestpro1=$estprog[0];
	 $as_codestpro2=$estprog[1];
	 $as_codestpro3=$estprog[2];
	 $as_codestpro4=$estprog[3];
	 $as_codestpro5=$estprog[4];
	 $as_estcla=$estprog[5];
	 if (($as_codestpro1 == '0000000000000000000000000') && ($as_codestpro2 == '0000000000000000000000000') &&
	     ($as_codestpro3 == '0000000000000000000000000') && ($as_codestpro4 == '0000000000000000000000000') &&
		 ($as_codestpro5 == '0000000000000000000000000') && (empty($as_estcla)))
	{
	 $as_codestpro1='-------------------------';
	 $as_codestpro2='-------------------------';
	 $as_codestpro3='-------------------------';
	 $as_codestpro4='-------------------------';
	 $as_codestpro5='-------------------------';
	 $as_estcla    ='-';
	}
	 
     $ls_sql=" UPDATE  spi_cuentas_estructuras ".
			 " SET    previsto =".$ad_previsto.", ".
			 "        enero=".$ad_m1.", ".
			 "        febrero=".$ad_m2.", ".
			 "        marzo=".$ad_m3.", ".
			 "        abril=".$ad_m4.", ".
			 "        mayo=".$ad_m5.", ".
			 "        junio=".$ad_m6.", ".
			 "        julio=".$ad_m7.", ".
			 "        agosto=".$ad_m8.", ".
			 "        septiembre=".$ad_m9.", ".
			 "        octubre=".$ad_m10.", ".
			 "        noviembre=".$ad_m11.", ".
			 "        diciembre=".$ad_m12." ".
			 " WHERE codemp='".$as_codemp."'".
			 " and spi_cuenta='".$as_cuenta."'".
			 " and codestpro1='".$as_codestpro1."'".
			 " and codestpro2='".$as_codestpro2."'".
			 " and codestpro3='".$as_codestpro3."'".
			 " and codestpro4='".$as_codestpro4."'".
			 " and codestpro5='".$as_codestpro5."'".
			 " and estcla='".$as_estcla."'" ; 
	 $li_select=$this->io_sql->execute($ls_sql);                                                                                                                                                                                          
	 if($li_select===false)
	 {
		  $lb_valido=false;
		  $this->io_msg->message("CLASE->class_apertura_ingreso MÉTODO->uf_spi_update_sipcuentas_estructuras ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	 }
	 else
	 {
	      $as_programatica=$estprog[0].$estprog[1].$estprog[2].$estprog[3].$estprog[4];
		 //////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
		   $ls_evento="UPDATE";
		   $ls_descripcion =" Actualizó el monto previsto la cuenta de Ingreso ".$as_cuenta." asociada a la estructura programatica ".$as_programatica." y el estatus ".$as_estcla;
		   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		 /////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////	
		    if($lb_valido)
			{
				 $this->io_sql->commit();
				 $lb_valido=true;
			}
			else
			{
				 $this->io_sql->rollback();
				 $lb_valido=false;
			}
	 } 
	 return  $lb_valido;
   }//fin  uf_spi_update_sipcuentas_estructuras
//---------------------------------------------------------------------------------------------------------------------------------
  function uf_spi_buscar_estructuras($as_codemp,$estprog,$as_cuenta,&$lb_vali)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_buscar_estructuras 
	//	     Arguments:  $rs_data // resulset con la data (referencia)
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que carga la información de la apertura de  de cuentas en un resulset, 
	//                   Este proceso es utilizado en apertura de cuentas presupuestaria de ingreso.  
	//     Creado por :  
	// Fecha Creación :           Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$lb_vali=0;
	 $as_codestpro1=$estprog[0];
	 $as_codestpro2=$estprog[1];
	 $as_codestpro3=$estprog[2];
	 $as_codestpro4=$estprog[3];
	 $as_codestpro5=$estprog[4];
	 $as_estcla=$estprog[5];
	 if (($as_codestpro1 == '0000000000000000000000000') && ($as_codestpro2 == '0000000000000000000000000') &&
	     ($as_codestpro3 == '0000000000000000000000000') && ($as_codestpro4 == '0000000000000000000000000') &&
		 ($as_codestpro5 == '0000000000000000000000000') && (empty($as_estcla)))
	{
	 $as_codestpro1='-------------------------';
	 $as_codestpro2='-------------------------';
	 $as_codestpro3='-------------------------';
	 $as_codestpro4='-------------------------';
	 $as_codestpro5='-------------------------';
	 $as_estcla    ='-';
	}
	$ls_sql= " SELECT spi_cuenta,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla".
			 " FROM     spi_cuentas_estructuras  ".
			 " WHERE    codemp='".$as_codemp."' and spi_cuenta='".$as_cuenta."'".
			 " and  codestpro1='".$as_codestpro1."'".
			 " and  codestpro2='".$as_codestpro2."'".
			 " and  codestpro3='".$as_codestpro3."'".
			 " and  codestpro4='".$as_codestpro4."'".
			 " and  codestpro5='".$as_codestpro5."'".
			 " and  estcla='".$as_estcla."'"; //print $ls_sql;
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_spi_buscar_estructuras ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	   $lb_vali=0;
	}
	else
	{
	    if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
            $lb_vali=1;
		}
	}
	return $lb_valido;

}//uf_spi_buscar_estructuras  
//---------------------------------------------------------------------------------------------------------------------------------
 function uf_spi_buscar_cuentas($as_codemp,$as_cuenta,&$aa_montos_aux)
  {
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spi_buscar_cuentas
	 //	Access:  public
	 //	Description: Método obtiene los monto programados para la cuenta enviada
	 // Desarrollado 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	
	  $ls_sql= " SELECT spi_cuentas.enero,spi_cuentas.febrero,spi_cuentas.marzo,spi_cuentas.abril,
	             spi_cuentas.mayo,spi_cuentas.junio,spi_cuentas.julio,spi_cuentas.agosto,spi_cuentas.septiembre,spi_cuentas.octubre,
				 spi_cuentas.noviembre,spi_cuentas.diciembre ".
			   " FROM  spi_cuentas ".
			   " WHERE spi_cuentas.codemp='".$as_codemp."' AND spi_cuentas.spi_cuenta='".$as_cuenta."' ";
  
	  //print $ls_sql."<br>";
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
		  	    $aa_montos_aux["enero"]=$row["enero"];
				$aa_montos_aux["febrero"]=$row["febrero"];
				$aa_montos_aux["marzo"]=$row["marzo"];
				$aa_montos_aux["abril"]=$row["abril"];
				$aa_montos_aux["mayo"]=$row["mayo"];
				$aa_montos_aux["junio"]=$row["junio"];
				$aa_montos_aux["julio"]=$row["julio"];
				$aa_montos_aux["agosto"]=$row["agosto"];
				$aa_montos_aux["septiembre"]=$row["septiembre"];
				$aa_montos_aux["octubre"]=$row["octubre"];
				$aa_montos_aux["noviembre"]=$row["noviembre"];
				$aa_montos_aux["diciembre"]=$row["diciembre"];
		  }
		  else
		  {
			 	$aa_montos_aux["enero"]     = 0;
				$aa_montos_aux["febrero"]   = 0;
				$aa_montos_aux["marzo"]     = 0;
				$aa_montos_aux["abril"]     = 0;
				$aa_montos_aux["mayo"]      = 0;
				$aa_montos_aux["junio"]     = 0;
				$aa_montos_aux["julio"]     = 0;
				$aa_montos_aux["agosto"]    = 0;
				$aa_montos_aux["septiembre"]= 0;
				$aa_montos_aux["octubre"]   = 0;
				$aa_montos_aux["noviembre"] = 0;
				$aa_montos_aux["diciembre"] = 0;
		  }
	  }
}//uf_spi_buscar_cuenta
//---------------------------------------------------------------------------------------------------------------------------------

 function uf_spi_buscar_previsto($as_codemp,$as_cuenta,&$as_previsto)
  {
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spi_buscar_previsto
	 //	Access:  public
	 //	Description: Método obtiene los monto programados para la cuenta enviada
	 // Desarrollado 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  $acum_previsto=0;
	  $ls_sql= " SELECT * ".
			   " FROM  spi_cuentas ".
			   " WHERE spi_cuentas.codemp='".$as_codemp."' AND spi_cuentas.spi_cuenta='".$as_cuenta."' ";
  
	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {
	  	$this->io_msg->message("Error en obtener montos de la cuenta, ".$this->io_function->uf_convertirmsg($this->io_sql->message));
		return false;
	  }
	  else
	  {
		  while($row=$this->io_sql->fetch_row($rs_data))
		  {
		  	    $as_previsto=$row["previsto"];
				//$acum_previsto=$acum_previsto+$as_previsto; 
		  }
	  }
}//uf_spi_buscar_previsto
//----------------------------------------------------------------------------------------------------------------------------
 function uf_spi_buscar_previsto_estructuras($as_codemp,$as_cuenta,&$as_previsto)
  {
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spi_buscar_previsto_estructuras
	 //	Access:  public
	 //	Description: Método obtiene los monto programados para la cuenta enviada
	 // Desarrollado 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  $acum_previsto=0;
	  $ls_sql= " SELECT sum(previsto) as previsto ".
			   " FROM  spi_cuentas_estructuras ".
			   " WHERE spi_cuentas_estructuras.codemp='".$as_codemp."' AND spi_cuentas_estructuras.spi_cuenta='".$as_cuenta."' ";

	  $rs_data=$this->io_sql->select($ls_sql);
	  if($rs_data===false)
	  {
	  	$this->io_msg->message("Error en obtener montos de la cuenta, ".$this->io_function->uf_convertirmsg($this->io_sql->message));
		return false;
	  }
	  else
	  {
		  while($row=$this->io_sql->fetch_row($rs_data))
		  {
		  	    $as_previsto=$row["previsto"];
				//$acum_previsto=$acum_previsto+$as_previsto; 
		  }
	  }
}//uf_spi_buscar_previsto_estructuras
//----------------------------------------------------------------------------------------------------------------------------
 function uf_spi_buscar_meses_cuentas($as_codemp,$as_cuenta,&$aa_montos_acum)
  {
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	Function:  uf_spi_buscar_meses_cuentas
	 //	Access:  public
	 //	Description:
	 // Desarrollado 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $ls_sql="";  
	  $lb_valido=true;
	  $acum_previsto=0;
	  $ls_sql= " SELECT sum(previsto) as previsto, sum(enero) as enero, sum(febrero) as febrero, sum(marzo) as marzo , sum(abril) as abril, ".
	           "  sum(mayo) as mayo, sum(junio) as junio, sum(julio) as julio, sum(agosto) as agosto, sum(septiembre) as septiembre, ".
			   "  sum(octubre) as octubre, sum(noviembre) as noviembre, sum(diciembre) as diciembre ".
			   " FROM  spi_cuentas_estructuras ".
			   " WHERE codemp='".$as_codemp."' AND spi_cuenta='".$as_cuenta."' ";
	 // print $ls_sql."<br><br>";		   
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
		  	    $aa_montos_acum["previsto"]=$row["previsto"];
				$aa_montos_acum["enero"]=$row["enero"];
				$aa_montos_acum["febrero"]=$row["febrero"];
				$aa_montos_acum["marzo"]=$row["marzo"];
				$aa_montos_acum["abril"]=$row["abril"];
				$aa_montos_acum["mayo"]=$row["mayo"];
				$aa_montos_acum["junio"]=$row["junio"];
				$aa_montos_acum["julio"]=$row["julio"];
				$aa_montos_acum["agosto"]=$row["agosto"];
				$aa_montos_acum["septiembre"]=$row["septiembre"];
				$aa_montos_acum["octubre"]=$row["octubre"];
				$aa_montos_acum["noviembre"]=$row["noviembre"];
				$aa_montos_acum["diciembre"]=$row["diciembre"];
		  }
		  else
		  {
			 	$aa_montos_acum["previsto"]  = 0;
				$aa_montos_acum["enero"]     = 0;
				$aa_montos_acum["febrero"]   = 0;
				$aa_montos_acum["marzo"]     = 0;
				$aa_montos_acum["abril"]     = 0;
				$aa_montos_acum["mayo"]      = 0;
				$aa_montos_acum["junio"]     = 0;
				$aa_montos_acum["julio"]     = 0;
				$aa_montos_acum["agosto"]    = 0;
				$aa_montos_acum["septiembre"]= 0;
				$aa_montos_acum["octubre"]   = 0;
				$aa_montos_acum["noviembre"] = 0;
				$aa_montos_acum["diciembre"] = 0;
		  }
	  }
}//uf_spi_buscar_previsto_estructuras

//-----------------------------------------------------------------------------------------------------------------------------------------
  function uf_spi_load_cuentas_apertura_con_estructuras($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
                                                        $ls_codestpro5,$ls_estcla,&$rs_data)
  {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_load_cuentas_apertura_con_estructuras
	//	     Arguments:  $rs_data // resulset con la data (referencia)
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que carga la información de la apertura de  de cuentas en un resulset, 
	//                   Este proceso es utilizado en apertura de cuentas presupuestaria de ingreso.  
	//     Creado por :  Ing. Jennifer Rivero
	// Fecha Creación :  17/11/2008          Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
    $ls_sql= " SELECT   spi_cuentas.spi_cuenta,spi_cuentas.denominacion,spi_cuentas.status,".
	         "          spi_cuentas_estructuras.previsto,spi_cuentas.distribuir,spi_cuentas.enero,spi_cuentas.febrero,".
			 "          spi_cuentas.marzo, spi_cuentas.abril,spi_cuentas.mayo,".
			 "          spi_cuentas.junio,spi_cuentas.julio,spi_cuentas.agosto,spi_cuentas.septiembre, ".
			 "          spi_cuentas.octubre,spi_cuentas.noviembre,spi_cuentas.diciembre".
			 " FROM     spi_cuentas, spi_cuentas_estructuras, spg_ep5  ".
			 " WHERE    spi_cuentas.codemp='".$this->ls_codemp."'  AND spi_cuentas.status='C'  ".
			 "   AND spi_cuentas_estructuras.codestpro1 = '".$ls_codestpro1."' ".
			 "	 AND spi_cuentas_estructuras.codestpro2 = '".$ls_codestpro2."' ".
			 "	 AND spi_cuentas_estructuras.codestpro3 = '".$ls_codestpro3."' ".
			 "	 AND spi_cuentas_estructuras.codestpro4 = '".$ls_codestpro4."' ".
			 "	 AND spi_cuentas_estructuras.codestpro5 = '".$ls_codestpro5."' ".
			 "	 AND spi_cuentas_estructuras.estcla = '".$ls_estcla."' ".
			 "	 AND spi_cuentas.codemp=spi_cuentas_estructuras.codemp ".
			 "   AND TRIM(spi_cuentas.codemp)=TRIM(spi_cuentas_estructuras.codemp) ".
			 "	 AND spi_cuentas_estructuras.codemp = spg_ep5.codemp ".
			 "   AND spi_cuentas_estructuras.codestpro1 = spg_ep5.codestpro1 ".
			 "   AND spi_cuentas_estructuras.codestpro2 = spg_ep5.codestpro2 ".
			 "   AND spi_cuentas_estructuras.codestpro3 = spg_ep5.codestpro3 ".
			 "   AND spi_cuentas_estructuras.codestpro4 = spg_ep5.codestpro4 ".
			 "   AND spi_cuentas_estructuras.codestpro5 = spg_ep5.codestpro5 ".
			 "   AND spi_cuentas_estructuras.spi_cuenta = spi_cuentas.spi_cuenta ".
			 "   AND spi_cuentas_estructuras.estcla = spg_ep5.estcla";
			 " ORDER BY spi_cuenta  "; //print $ls_sql;
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	   $lb_valido=false;
	   $this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_spi_load_cuentas_apertura_con_estructuras ERROR->".
	                            $this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	return $lb_valido;

}//uf_spi_load_cuentas_apertura_con_estructuras 
//--------------------------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_check_estructurapresupuestaria1($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria1
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta presupuestaria existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/09/2007  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tabla="";
		$ls_criterio="";
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_estcla="'".$aa_codestpro[1]."'";
		$ls_sql="SELECT codemp ".
				"  FROM spg_ep1 ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$aa_codestpro[0]."'".
				"   AND estcla = ".$aa_estcla;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_estructurapresupuestaria1 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=$this->uf_insertar_estructurapresupuestaria1($as_codemp,$aa_codestpro);
			}
		}
		return $lb_valido;
	}// end function uf_check_estructurapresupuestaria_destino
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insertar_estructurapresupuestaria1($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_estructurapresupuestaria1
		//		   Access: public
		//		 Argumens: aa_codestpro // Arreglo de estructuras
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que inserta una estructura de nivel 1
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/06/2008  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_estcla="'".$aa_codestpro[1]."'";
		$ls_sql="INSERT INTO spg_ep1(codemp,codestpro1,denestpro1,estcla)".
				" VALUES('".$as_codemp."','".$aa_codestpro[0]."','POR DEFECTO',$aa_estcla)";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_insertar_estructurapresupuestaria1 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_check_estructurapresupuestaria2($as_codemp,$aa_codestpro,$as_anocur)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria2
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta presupuestaria existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/09/2007  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tabla="";
		$ls_criterio="";
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_estcla="'".$aa_codestpro[2]."'";
		$ls_sql="SELECT codemp ".
				"  FROM spg_ep2 ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$aa_codestpro[0]."'".
				"   AND codestpro2='".$aa_codestpro[1]."'".
				"   AND estcla =".$aa_estcla;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_estructurapresupuestaria2 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=$this->uf_insertar_estructurapresupuestaria2($as_codemp,$aa_codestpro);
			}
		}
		return $lb_valido;
	}// end function uf_check_estructurapresupuestaria_destino
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insertar_estructurapresupuestaria2($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_estructurapresupuestaria2
		//		   Access: public
		//		 Argumens: aa_codestpro // Arreglo de estructuras
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que inserta una estructura de nivel 1
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/06/2008  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_estcla="'".$aa_codestpro[2]."'";
		$ls_sql="INSERT INTO spg_ep2(codemp,estcla,codestpro1,codestpro2,denestpro2) ".
				" VALUES('".$as_codemp."',$aa_estcla,'".$aa_codestpro[0]."','".$aa_codestpro[1]."','POR DEFECTO')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_insertar_estructurapresupuestaria2 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_check_estructurapresupuestaria3($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta presupuestaria existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/09/2007  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tabla="";
		$ls_criterio="";
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_estcla="'".$aa_codestpro[3]."'";
		$ls_sql="SELECT codemp ".
				"  FROM spg_ep3 ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$aa_codestpro[0]."'".
				"   AND codestpro2='".$aa_codestpro[1]."'".
				"   AND codestpro3='".$aa_codestpro[2]."'".
				"   AND estcla    =".$aa_estcla;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_estructurapresupuestaria3 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=$this->uf_insertar_estructurapresupuestaria3($as_codemp,$aa_codestpro);
			}
		}
		return $lb_valido;
	}// end function uf_check_estructurapresupuestaria2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insertar_estructurapresupuestaria3($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_estructurapresupuestaria3
		//		   Access: public
		//		 Argumens: aa_codestpro // Arreglo de estructuras
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que inserta una estructura de nivel 1
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/06/2008  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_estcla="'".$aa_codestpro[3]."'";
		$ls_sql="INSERT INTO spg_ep3(codemp,estcla,codestpro1,codestpro2,codestpro3,denestpro3) ".
				" VALUES('".$as_codemp."',$aa_estcla,'".$aa_codestpro[0]."','".$aa_codestpro[1]."','".$aa_codestpro[2]."','POR DEFECTO')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_insertar_estructurapresupuestaria3 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_check_estructurapresupuestaria4($as_codemp,$aa_codestpro,$as_anocur)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria4
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta presupuestaria existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/09/2007  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tabla="";
		$ls_criterio="";
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_codestpro[3]=str_pad($aa_codestpro[3],$_SESSION["la_empresa"]["loncodestpro4"],"0",0);
		$aa_estcla="'".$aa_codestpro[4]."'";
		$ls_sql="SELECT codemp ".
				"  FROM spg_ep4 ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$aa_codestpro[0]."'".
				"   AND codestpro2='".$aa_codestpro[1]."'".
				"   AND codestpro3='".$aa_codestpro[2]."'".
				"   AND codestpro4='".$aa_codestpro[3]."'".
				"   AND estcla = ".$aa_estcla;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_estructurapresupuestaria3 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=$this->uf_insertar_estructurapresupuestaria4($as_codemp,$aa_codestpro);
			}
		}
		return $lb_valido;
	}// end function uf_check_estructurapresupuestaria_destino
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insertar_estructurapresupuestaria4($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_estructurapresupuestaria4
		//		   Access: public
		//		 Argumens: aa_codestpro // Arreglo de estructuras
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que inserta una estructura de nivel 1
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/06/2008  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_codestpro[3]=str_pad($aa_codestpro[3],$_SESSION["la_empresa"]["loncodestpro4"],"0",0);
		$aa_estcla="'".$aa_codestpro[4]."'";
		$ls_sql="INSERT INTO spg_ep4(codemp,estcla,codestpro1,codestpro2,codestpro3,codestpro4,denestpro4) ".
				" VALUES('".$as_codemp."',$aa_estcla,'".$aa_codestpro[0]."','".$aa_codestpro[1]."','".$aa_codestpro[2]."','".$aa_codestpro[3]."','POR DEFECTO')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_insertar_estructurapresupuestaria4 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_check_estructurapresupuestaria5($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria_destino
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta presupuestaria existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/09/2007  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tabla="";
		$ls_criterio="";
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_codestpro[3]=str_pad($aa_codestpro[3],$_SESSION["la_empresa"]["loncodestpro4"],"0",0);
		$aa_codestpro[4]=str_pad($aa_codestpro[4],$_SESSION["la_empresa"]["loncodestpro5"],"0",0);
		$aa_estcla="'".$aa_codestpro[5]."'";
		$ls_sql="SELECT codemp ".
				"  FROM spg_ep5 ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$aa_codestpro[0]."'".
				"   AND codestpro2='".$aa_codestpro[1]."'".
				"   AND codestpro3='".$aa_codestpro[2]."'".
				"   AND codestpro4='".$aa_codestpro[3]."'".
				"   AND codestpro5='".$aa_codestpro[4]."'".
				"   AND estcla =".$aa_estcla;	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_estructurapresupuestaria5 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=$this->uf_insertar_estructurapresupuestaria5($as_codemp,$aa_codestpro);
			}
		}
		return $lb_valido;
	}// end function uf_check_estructurapresupuestaria
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	function uf_check_spi_cuenta_estructurapresupuestaria($as_codemp,$as_spi_cuenta,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//                 as_spi_cuenta // Cuenta de Ingreso a chequear
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta de ingreso existe
		//	   Creado Por: Ing. Arnaldo Suárez
		// Fecha Creación: 17/03/2009  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_tabla="";
		$ls_criterio="";
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_codestpro[3]=str_pad($aa_codestpro[3],$_SESSION["la_empresa"]["loncodestpro4"],"0",0);
		$aa_codestpro[4]=str_pad($aa_codestpro[4],$_SESSION["la_empresa"]["loncodestpro5"],"0",0);
		$aa_estcla="'".$aa_codestpro[5]."'";
		$ls_sql="SELECT codemp ".
				"  FROM spi_cuentas_estructuras ".
				" WHERE codemp='".$as_codemp."'".
				"   AND codestpro1='".$aa_codestpro[0]."'".
				"   AND codestpro2='".$aa_codestpro[1]."'".
				"   AND codestpro3='".$aa_codestpro[2]."'".
				"   AND codestpro4='".$aa_codestpro[3]."'".
				"   AND codestpro5='".$aa_codestpro[4]."'".
				"   AND estcla =".$aa_estcla.
				"   AND spi_cuenta = '".$as_spi_cuenta."'";		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{ 
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_spi_cuenta_estructurapresupuestaria ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}// end function uf_check_spi_cuenta_estructurapresupuestaria
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insertar_estructurapresupuestaria5($as_codemp,$aa_codestpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insertar_estructurapresupuestaria1_destino
		//		   Access: public
		//		 Argumens: aa_codestpro // Arreglo de estructuras
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que inserta una estructura de nivel 1
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 04/06/2008  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$aa_codestpro[0]=str_pad($aa_codestpro[0],$_SESSION["la_empresa"]["loncodestpro1"],"0",0);
		$aa_codestpro[1]=str_pad($aa_codestpro[1],$_SESSION["la_empresa"]["loncodestpro2"],"0",0);
		$aa_codestpro[2]=str_pad($aa_codestpro[2],$_SESSION["la_empresa"]["loncodestpro3"],"0",0);
		$aa_codestpro[3]=str_pad($aa_codestpro[3],$_SESSION["la_empresa"]["loncodestpro4"],"0",0);
		$aa_codestpro[4]=str_pad($aa_codestpro[4],$_SESSION["la_empresa"]["loncodestpro5"],"0",0);
		$aa_estcla="'".$aa_codestpro[5]."'";
		$ls_sql="INSERT INTO spg_ep5(codemp,estcla,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,denestpro5) ".
				" VALUES('".$as_codemp."',$aa_estcla,'".$aa_codestpro[0]."','".$aa_codestpro[1]."','".$aa_codestpro[2]."','".$aa_codestpro[3]."','".$aa_codestpro[4]."','POR DEFECTO')";
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_insertar_estructurapresupuestaria5 ERROR->".
									$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------


function uf_check_estructurapresupuestaria($as_codemp,$aa_codestpro,$ai_nivel)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_check_estructurapresupuestaria
		//		   Access: public
		//		 Argumens: as_codemp // Código de Empresa
		//				   aa_codestpro // Estructura Presupuestaria
		//				   ai_nivel // Nivel que se quiere validar
		//	      Returns: lb_valido si la cuenta existe
		//	  Description: Función que verifica si la cuenta presupuestaria existe
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/09/2007  								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_tabla="";
		$ls_criterio="";
		for($li_i=0;$li_i<=5;$li_i++)
		{
			if($li_i===$ai_nivel)
			{
				$ls_tabla="spg_ep".$ai_nivel;
			}
			if($li_i<$ai_nivel)
			{
				$li_j=$li_i+1;
				$ls_criterio=$ls_criterio." AND codestpro".$li_j."='".$aa_codestpro[$li_i]."'";
			}
		}
		$ls_criterio=$ls_criterio." AND estcla='".$aa_codestpro[$ai_nivel]."'";
		$ls_sql="SELECT codemp ".
				"  FROM ".$ls_tabla." ".
				" WHERE codemp='".$as_codemp."'".
				" ".$ls_criterio;			
		 $rs_data=$this->io_sql->select($ls_sql);
		 if($rs_data===false)
		 {
		   $lb_valido=false;
	       $this->io_msg->message("CLASE->Apertura de Ingreso MÉTODO->uf_check_estructurapresupuestaria ERROR->".
	                            $this->io_function->uf_convertirmsg($this->io_sql->message));
		 }
		 else
		 {
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			else
			{
				switch($ai_nivel) 
				{
					case 1:
						$lb_valido=$this->uf_insertar_estructurapresupuestaria1($as_codemp,$aa_codestpro);
					break;
					case 2:
						$lb_valido=$this->uf_check_estructurapresupuestaria1($as_codemp,$aa_codestpro,$as_anocur);
						if($lb_valido)
						{
							$lb_valido=$this->uf_insertar_estructurapresupuestaria2($as_codemp,$aa_codestpro);
						}
					break;
					case 3:
						$lb_valido=$this->uf_check_estructurapresupuestaria1($as_codemp,$aa_codestpro,$as_anocur);
						if($lb_valido)
						{
							$lb_valido=$this->uf_check_estructurapresupuestaria2($as_codemp,$aa_codestpro,$as_anocur);
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_insertar_estructurapresupuestaria3($as_codemp,$aa_codestpro);
						}
					break;
					case 4:
						$lb_valido=$this->uf_check_estructurapresupuestaria1($as_codemp,$aa_codestpro,$as_anocur);
						if($lb_valido)
						{
							$lb_valido=$this->uf_check_estructurapresupuestaria2($as_codemp,$aa_codestpro,$as_anocur);
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_check_estructurapresupuestaria3($as_codemp,$aa_codestpro,$as_anocur);
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_insertar_estructurapresupuestaria4($as_codemp,$aa_codestpro);
						}
					break;
					case 5:
						$lb_valido=$this->uf_check_estructurapresupuestaria1($as_codemp,$aa_codestpro,$as_anocur);
						if($lb_valido)
						{
							$lb_valido=$this->uf_check_estructurapresupuestaria2($as_codemp,$aa_codestpro,$as_anocur);
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_check_estructurapresupuestaria3($as_codemp,$aa_codestpro,$as_anocur);
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_check_estructurapresupuestaria4($as_codemp,$aa_codestpro,$as_anocur);
						}
						if($lb_valido)
						{
							$lb_valido=$this->uf_insertar_estructurapresupuestaria5($as_codemp,$aa_codestpro);
						}
					break;
					default:
						$lb_valido=false;
					break;
				}
				
			}
		}
		return $lb_valido;
	}// end function uf_check_estructurapresupuestaria



}//fin de class_apertura
?>