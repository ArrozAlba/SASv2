<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_xspg.php");
require_once("../shared/class_folder/class_sigesp_int_spgctas.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");

class sigesp_spg_class_apertura extends class_sigesp_int_xspg
{

   function sigesp_spg_class_apertura()
   {
		$con=new sigesp_include();
		$this->conn=$con->uf_conectar();
		$this->SQL=new class_sql($this->conn);
		$this->msg=new class_mensajes();
		$this->int_spg=new class_sigesp_int_spg();
		$this->int_xspg=new class_sigesp_int_xspg();
		$this->fun=new class_funciones();	
		$this->sig_int=new class_sigesp_int();
		$this->int_spgctas=new class_sigesp_int_spgctas();
		$this->int_fecha=new class_fecha();
		$this->io_seguridad= new sigesp_c_seguridad();
	}
  
  function uf_llenar_combo_estpro1($as_codemp)
  {
    $ls_sql="";
	$ls_sql=" SELECT codestpro1,denestpro1 FROM spg_ep1 WHERE codemp='".$as_codemp."' ";
	$rs_SPG=$this->SQL->select($ls_sql);
    return $rs_SPG;
  }
  
  function uf_llenar_combo_estpro2($as_codemp, $as_codestpro1)
  {
     $ls_sql="";
	 $ls_sql=" SELECT codestpro2,denestpro2 FROM spg_ep2 WHERE codemp='".$as_codemp."' AND  codestpro1='".$as_codestpro1."' ";  
	 $rs_SPG=$this->SQL->select($ls_sql);
	 return $rs_SPG; 
  }//fin 
  
   function uf_llenar_combo_estpro3($as_codemp, $as_codestpro1, $as_codestpro2)
  {
     $ls_sql="";
	 $ls_sql=" SELECT codestpro3,denestpro3 FROM spg_ep3 WHERE codemp='".$as_codemp."' AND  codestpro1='".$as_codestpro1."' AND  codestpro2='".$as_codestpro2."'  ";  
	 $rs_SPG=$this->SQL->select($ls_sql);
	 return $rs_SPG; 
  }//fin
  
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
  
  $ls_sql= " SELECT spg_cuenta,denominacion,asignado,distribuir,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre".
           " FROM spg_cuentas  WHERE codemp='".$as_codemp."' AND codestpro1='".$as_ep1."' AND codestpro2='".$as_ep2."' AND ". 
    	   " codestpro3='".$as_ep3."' AND codestpro4='".$ls_ep4."' AND  codestpro5='".$ls_ep5."' AND  status='C'  ORDER BY spg_cuenta  ";  
  $rs_load=$this->SQL->select($ls_sql);
 
return $rs_load;

}//uf_spg_load_cuentas_apertura  


function uf_spg_procesar_apertura($aa_seguridad)
{

$ls_formpre="";
$ls_editmask_pre="";
$lb_valido=true;

$la_empresa =  $_SESSION["la_empresa"];
$this->is_codemp  =  $la_empresa["codemp"];
$ls_formpre =  $la_empresa["formpre"];
$ld_periodo =  $la_empresa["periodo"];

$ls_editmask_pre  = "spg_cuenta.editmask.mask=".$ls_formpre ;

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
$ldt_fecha=$ls_ano."/".$this->fun->uf_cerosizquierda($ls_mes,2)."/".$ls_dia;

if ($ld_periodo == $ldt_fecha) 
{
  $idt_fecha =$ldt_fecha;
}
else 
{
  $idt_fecha = $ld_periodo;
}
 
$this->ldt_fecha=$this->fun->uf_convertirfecmostrar($idt_fecha);
$this->id_fecha=$this->fun->uf_convertirdatetobd($this->ldt_fecha);

if (!$this->sig_int->uf_select_comprobante($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->ldt_fecha));
{
   $lb_valido = $this->sig_int->uf_insert_comprobante( $this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->ldt_fecha,$this->ii_tipo_comp,$this->is_descripcion,$this->is_tipo,$this->is_cod_prov  ,$this->is_ced_ben );
   if ($lb_valido)
   { 
	//$this->sig_int->uf_sql_transaction($lb_valido);
	//////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
	  $ls_evento="INSERT";
	  $ls_descripcion =" Guardar el Apertura trimestral con procedencia ".$this->is_procedencia." del comprobante nro ".$this->is_comprobante." ";
	  $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
									$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
									$aa_seguridad["ventanas"],$ls_descripcion);
	/////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////	
		 $this->SQL->commit();
		 $lb_valido=true;
	}
	else
	{
		 $this->SQL->rollback();
		 $lb_valido=false;
	}	
   }
}
return $lb_valido;
}//uf_spg_procesar_apertura

function uf_spg_guardar_apertura( $adec_m1,$adec_m2,$adec_m3,$adec_m4,$adec_m5,$adec_m6,$adec_m7,$adec_m8,$adec_m9,$adec_m10,$adec_m11,
                                  $adec_m12,$estprog, $as_cuenta,$adec_asignado,$ai_distribuir)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_spg_guardar_apertura
	//	Access:  public
	//	Description: Método que recorre la información almacenada en un datastore
	//              el cual contiene la información generada o registrada en cuanto 
	//              a la información de las asignación de la apertura de cuentas
	//              presupuestaria de gasto. Si la información de la apertura de 
	//              cuenta no existe, el método procederá a realizar un update en la 
	//              tabla  de spg_cuentas en cuanto a su asignación.
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ll_distribuir="";
	$li_orden=0;$ll_row=0;
	$ls_est1="";$ls_est2="";$ls_est3="";$ls_est4="";$ls_est5="";$ls_cuenta="";$ls_programatica="";
	$ldec_asignado=0;$ldec_m1=0;$ldec_m2=0;$ldec_m3=0;$ldec_m4=0;$ldec_m5=0;$ldec_m6=0;$ldec_m7=0; 
	$ldec_m8=0;$ldec_m9=0;$ldec_m10=0;$ldec_m11=0;$ldec_m12=0;$ldec_monto=0;$ldec_asignado_ant=0;
	
	$this->int_xspg=$this->is_codemp  ;
	$this->int_xspg=$this->is_procedencia;		
	$this->int_xspg=$this->is_comprobante;
	$this->int_xspg=$this->ii_tipo_comp ;
	$this->int_xspg=$this->is_ced_ben   ;
	$this->int_xspg=$this->is_cod_prov  ;
	$this->int_xspg=$this->is_tipo      ;
	$this->int_xspg=$this->is_descripcion ;
	$this->int_xspg=$this->id_fecha ;
	 
	if ($this->int_xspg->uf_spg_select_movimiento($estprog,$as_cuenta,$this->is_procedencia,$this->is_comprobante,"AAP",&$ldec_monto_ant,&$li_orden,$this->id_fecha)) 
	{
	  if ($adec_asignado <> 0) 
	  {
		$lb_valido = $this->int_xspg->uf_spg_update_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$this->is_cod_prov  ,$this->is_ced_ben ,$this->is_descripcion,$this->is_tipo, $this->ii_tipo_comp,$estprog,$estprog,$as_cuenta,$as_cuenta,$this->is_procedencia,$this->is_procedencia,$this->is_comprobante,$this->is_comprobante,$this->is_descripcion,$this->is_descripcion,'I','I',$ldec_monto_ant,$adec_asignado);
	  }					 	   											   
	  else
	  {																			 
		$lb_valido = $this->int_xspg->uf_int_spg_delete_movimiento($this->is_codemp,$this->is_procedencia,$this->is_comprobante,$this->id_fecha,$estprog,$as_cuenta,$this->is_procedencia,$this->is_comprobante,$this->is_descripcion,'I',$ldec_asignado_ant,$adec_asignado);
	  }
	}  
	else
	{	
	  $lb_valido = $this->int_spg->uf_spg_procesar_movimiento_cmp($this->is_codemp,$this->is_procedencia ,$this->is_comprobante,$this->id_fecha,$this->is_cod_prov,$this->is_ced_ben ,$this->is_tipo,$this->ii_tipo_comp,$estprog,$as_cuenta,$this->is_procedencia ,$this->is_comprobante,"I",$this->is_descripcion,$adec_asignado,false);
	}
	if ($lb_valido)
	{
		$ls_sql=" UPDATE spg_cuentas  SET asignado='".$adec_asignado."',distribuir='".$ai_distribuir."',enero='".$adec_m1."',febrero='".$adec_m2."',marzo='".$adec_m3."',abril='".$adec_m4."',mayo='".$adec_m5."',junio='".$adec_m6."',julio='".$adec_m7."',agosto='".$adec_m8."',septiembre='".$adec_m9."',octubre='".$adec_m10."',noviembre='".$adec_m11."',diciembre='".$adec_m12."'	WHERE CodEstPro1='". $estprog[0]."' AND CodEstPro2='".$estprog[1]."' AND CodEstPro3='".$estprog[2]."' AND CodEstPro4='".$estprog[3]."' AND  CodEstPro5='".$estprog[4]."' AND spg_cuenta = '".$as_cuenta."' "; 
		$li_exec=$this->SQL->execute($ls_sql);                                                                                                                                                                                          
		if($li_exec===false)
		{
			  $lb_valido=false;
			  $this->SQL->message;
	
		}
		else
		{
			  $lb_valido=true;
		} 
		if ($lb_valido)
		{
		  //////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////////////////////////////////////		
		   $ls_evento="INSERT";
		   $ls_descripcion =" Guardar el Apertura trimestral con procedencia ".$this->is_procedencia." del comprobante nro ".$this->is_comprobante." ";
		   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		  /////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////////////	
			 $this->SQL->commit();
			 $lb_valido=true;
		}
		else
		{
			 $this->SQL->rollback();
			 $lb_valido=false;
		}   
		
	 }	
	
	return $lb_valido;
}// fin 


}//fin de class_apertura
?>