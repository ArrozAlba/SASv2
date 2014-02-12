<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_xspg.php");
require_once("../shared/class_folder/class_sigesp_int_spgctas.php");
require_once("../shared/class_folder/class_fecha.php");
class sigesp_spg_class_progrep_trim
{

   function sigesp_spg_class_progrep_trim()
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
   
function  uf_prog_report_delete($as_codemp,$as_codrep)
{    
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_prog_report_delete
	//	Access:  public
	// Argumente:   as_codrep   // codigo del reporte
	//	Description: Método que borrar la información contenida a la tabla 
	//               plantila reporte del reporte especificado
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido =true;
	$ib_db_error = false;
	$is_msg_error = "";
	$ls_sql="";
	
	$ls_sql=" DELETE FROM spg_plantillareporte WHERE codemp='".$as_codemp."' AND codrep='".$as_codrep."' ";
	//print $ls_sql;
	$li_rows_afecta=$this->SQL->execute($ls_sql);
	if ($li_rows_afecta===false)
	{
	   $lb_valido=false;
	   print $this->is_msg_error = "Error en método uf_prog_report_delete ".$this->SQL->message;
	   //$this->msg->message("Error en método uf_prog_report_delete ");
	}
	else 
	{
	  $lb_valido=true;
	}
	
 return $lb_valido;
}//fin 

  
function uf_prog_report_load_original($as_codemp, $as_codrep, $as_codestpro1,$as_codestpro2,$as_codestpro3)
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_prog_report_load_original
	//	Access:  public
	//	Description: Método que carga la información nuevamente en la
	// tabla spg_plantillacuentareporte. Esta información es la copia exacta
	// de las cuentas definidas en la tabla spg_cuentas en la tabla mencionada anteriormente
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido =true;
	$ls_sql="";
	$ls_codestpro4="00";
	$ls_codestpro5="00";
	//print "parameter".$as_codemp.$as_codrep.$as_codestpro1.$as_codestpro2.$as_codestpro3;
	if (!($this->uf_prog_report_delete($as_codemp,$as_codrep)))
	{
	  return false;
	}  
		
	if ($as_codrep=='00005')
	{  // Flujo de Caja
		$ls_sql = "  INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				  "  spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado, pagado, aumento, disminucion, distribuir, enero,".
				  "  febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia)". 
				  "  SELECT DISTINCT '00005' As codrep,codemp,'00000000000000000000','000000','000','00','00',spg_cuenta, sc_cuenta, denominacion, status,". 
				  "  0 as asignado, 0 as precomprometido, 0 as comprometido, 0 as causado, 0 as pagado, 0 as aumento,". 
				  "  0 as disminucion, 1 as distribuir,0 as enero, 0 as febrero, 0 as marzo, 0 as abril, 0 as mayo, 0 as junio, 0 as julio,".
				  "  0 as agosto, 0 as septiembre, 0 as octubre, 0 as noviembre, 0 as diciembre, nivel, referencia ".
				  "  FROM spg_cuentas WHERE codemp='".$as_codemp."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'";
	}
	else
	{
		$ls_sql = "  INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				  "  spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado, pagado, aumento, disminucion, distribuir, enero,".
				  "  febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia)". 
				  "  SELECT '".$as_codrep."' As codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,spg_cuenta, sc_cuenta, denominacion, status,". 
				  "  0 as asignado, 0 as precomprometido, 0 as comprometido, 0 as causado, 0 as pagado, 0 as aumento,". 
				  "  0 as disminucion, 1 as distribuir,0 as enero, 0 as febrero, 0 as marzo, 0 as abril, 0 as mayo, 0 as junio, 0 as julio,".
				  "  0 as agosto, 0 as septiembre, 0 as octubre, 0 as noviembre, 0 as diciembre, nivel, referencia ".
				  "  FROM spg_cuentas WHERE codemp='".$as_codemp."'";
				  
	}
	$rs_load=$this->SQL->execute($ls_sql);
	if ($rs_load==false)
	{
	   $lb_existe=false;
	   $this->SQL->message;
	}
	else //($row=$this->SQL->fetch_row($rs_load))
	{
	   $lb_existe=true;
	}
	
	// se procede a realizar un filtro a la data generada //
	switch ($as_codrep)
	{
		case "00004": // Inversiones
			$ls_sql = " DELETE FROM spg_plantillareporte ".
					  " WHERE codemp='".$as_codemp."' AND codrep='".$as_codrep."' AND ".
					  " NOT (spg_cuenta = '401050100' OR  ".
					  " spg_cuenta = '401050300' OR spg_cuenta = '403010000' OR  ".
					  " spg_cuenta = '403020700' OR spg_cuenta = '403020200' OR  ".
					  " spg_cuenta = '403030100' OR spg_cuenta = '403030300' OR  ".
					  " spg_cuenta = '403030400' OR spg_cuenta = '403100400' OR  ".
					  " spg_cuenta = '403030500' OR spg_cuenta = '403040000' OR  ".
					  " spg_cuenta = '403030600' OR spg_cuenta = '403050100' OR  ".
					  " spg_cuenta = '403050200' OR spg_cuenta = '403050300' OR  ".
					  " spg_cuenta = '403060100' OR spg_cuenta = '407010102' OR  ".
					  " spg_cuenta = '407010101' OR spg_cuenta = '401071600' OR  ".
					  " spg_cuenta = '407010103' OR spg_cuenta = '407010106' OR  ".
					  " spg_cuenta = '407010110' OR spg_cuenta = '407010120' OR  ".
					  " spg_cuenta = '407010198' OR spg_cuenta = '407030000' OR  ".
					  " spg_cuenta = '407020200' OR spg_cuenta = '407020100' OR  ".
					  " spg_cuenta = '401080000' OR spg_cuenta = '407010301' )  ";
		 break;		
		   
		 case "00005": // Flujo de caja
			 $ls_sql = " DELETE FROM spg_plantillareporte ".
					   " WHERE codemp='".$as_codemp."' AND codrep='".$as_codrep."' AND ".
					   " NOT (spg_cuenta = '400000000' OR  ".
					   " spg_cuenta = '401000000' OR spg_cuenta = '402000000' OR ".
					   " spg_cuenta = '403000000' OR spg_cuenta = '404000000' OR ".
					   " spg_cuenta = '405000000' OR spg_cuenta = '406000000' OR ".
					   " spg_cuenta = '407000000' OR ".
					   " spg_cuenta = '408000000' OR spg_cuenta = '408030000' OR spg_cuenta = '408030200' ) ";
         break;
    }
	$li_rows_afecta=$this->SQL->execute($ls_sql);
	if($li_rows_afecta===false)
	{
		$lb_valido=false;
		$this->is_msg_error = " ".$this->SQL->message;
		$this->msg->message(" uf_prog_report_load_original ");
	}
	else
	{
		$lb_valido=true;
	}
	
	if ($lb_valido)
	{
	    $lb_valido = $this->sig_int->uf_sql_transaction(true);	
	}	
		
 return $lb_valido;
}//fin 


function uf_prog_report_load_data($as_codemp,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
//////////////////////////////////////////////////////////////////////////////
//	Function:  uf_prog_report_load_data
//	Access:  public
//	Description: Método que carga la información de la 
//              programacion de reportes por programatica 
//              y lo registra en un datastore.
//////////////////////////////////////////////////////////////////////////////
	
	$li_fila=0;$li_distribuir="";
	$lb_valido=true;
	$ls_cuentas="";$ls_denominacion="";$ls_sql="";$ls_referencia="";$ls_status="";
	$ldec_asignado="";$ldec_m1=0;$ldec_m2=0;$ldec_m3=0;$ldec_m4=0;$ldec_m5=0;$ldec_m6=0;$ldec_m7=0;
	$ldec_m8=0;$ldec_m9=0;$ldec_m10=0;$ldec_m11=0;$ldec_m12=0;$li_nivel=0;	
	
	$ls_codestpro4="00";
	$ls_codestpro5="00";
	
	if ($as_codrep=='00005')// Flujo de Caja
	{
		$ls_sql = " SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				  " spg_cuenta,denominacion,status,asignado,distribuir,marzo,".
				  " junio,septiembre,diciembre,nivel,referencia ".
				  " FROM spg_plantillareporte ".
				  " WHERE codemp='".$as_codemp."' AND codrep='".$as_codrep."' ORDER BY spg_cuenta ";
	}
	else
	{
		$ls_sql = " SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				  " spg_cuenta,denominacion,status,asignado,distribuir,marzo,".
				  " junio,septiembre,diciembre,nivel,referencia ".
				  " FROM spg_plantillareporte".
				  " WHERE codemp='".$as_codemp."' AND codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' ORDER BY spg_cuenta ";
	}
    $rs_progrep=$this->SQL->select($ls_sql);
 
return $rs_progrep;

}///fin

function uf_obt_nivel_cta( $as_cuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3 )
{
////////////////////////////////////////////////////////////////////////////////////////////
// Function:    uf_obt_nivel_cta
// Acesso:      Public
// Argumentos:  as_sc_cuenta: String
// Descripción  Busca en la tabla scg_pc_report el nivel de la cuenta que pasa por parametro 		  	  
/////////////////////////////////////////////////////////////////////////////////////////////
  	 	
  	 	$ls_sql="";
		$ls_codestpro4="00";
		$ls_codestpro5="00";
		
  	 	$ls_sql = "SELECT nivel FROM spg_plantillareporte WHERE spg_cuenta = '".$as_cuenta."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' ";	
  	 	$rs_pr = $this->SQL->select($ls_sql);
		if ($rs_pr==false)
		{
		    $li_nivel = 0; //no existen registros
			$this->is_msg_error= " Error en la función uf_select_scg_plantillacuentareporte. ".$this->SQL->message;
		 } 
		else
		{
		   if ($row=$this->SQL->fetch_row($rs_pr))
	 	   {
			  $li_nivel = $row["nivel"];
		   }
		}
		
	return $li_nivel;
  	 	
   }//uf_obt_nivel_cta 

function uf_cuenta_sin_ceros( $as_cuenta )
{ ///////////////////////////////////////////////////////////////////////  
 //  Function:   uf_cuenta_sin_ceros
 //  Acceso:     public
 //  Argumentos: as_cuenta
 //  Descripción: Elimina los ceros a la derecha de la cuenta contable   
 /////////////////////////////////////////////////////////////////////////
	 	
	 	$li_lenCta=0; $li_cero=1;
	 	$ls_cta_ceros=""; $ls_cad="";
	 	$lb_encontrado=true;
	 	global $msg;
	 	$li_lenCta = strlen(trim($as_cuenta));
		$ls_cad = substr(trim($as_cuenta), strlen(trim($as_cuenta))-1, 1 );
		$li_cero = $ls_cad;
	 	
	 	if ($li_cero == 0)
	 	{
			$ls_cta_ceros = substr(trim($as_cuenta), 0 , 11);
	  	}
	 	
	 	do  
		{
			$ls_cad = substr(trim($ls_cta_ceros), strlen($ls_cta_ceros)-1, 1);
	 		$li_cero = intval($ls_cad);
			$li_cant=strlen($ls_cta_ceros)-1;
	 		if ($li_cero == 0 )
	 		{
				$ls_cta_ceros = substr(trim($ls_cta_ceros), 0 , $li_cant);
	 			$lb_encontrado=true;
	 	 	}
	 	 	else
	 	 	{
	 	 		$lb_encontrado = false;
	 	 	}
	 		
	 	}while ( $lb_encontrado == true ); 
	 	
	  	return $ls_cta_ceros;
	 	
 }//uf_cuenta_sin_ceros
 
 
 function uf_disable_cta_inferior( $as_cta_ceros, $as_spg_cuenta,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3 )
 {
	 	$lb_valido=true;
	 	$li_row = 0; $li_contador=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0;
	  	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
		$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
		$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
		$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
		global $msg;
	 	
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	    $la_empresa = $_SESSION["la_empresa"];	
		$ls_codemp = $la_empresa["codemp"]; 
	 		
	 	$ls_sql = " SELECT * FROM spg_plantillareporte WHERE spg_cuenta like '".$as_cta_ceros."%' and spg_cuenta <> '".$as_spg_cuenta."' and codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'  order by spg_cuenta " ;
		$rs_pr=$this->SQL->select($ls_sql);
		$li_row=$this->SQL->num_rows($rs_pr);
		if ($row=$this->SQL->fetch_row($rs_pr))
		{
			while ($row=$this->SQL->fetch_row($rs_pr))
			{	
				$ldc_asignado = $row["asignado"];
				$ls_spg_cuenta = $row["spg_cuenta"];
										
				if (!($ldc_asignado == 0))
				{
					$li_rtn = 1 ;
					$msg->message("La cuenta ".$ls_sc_cuenta." tiene asignación. ");
					//$is_msg_error = "La cuenta ".$ls_sc_cuenta." tiene asignación. ";	
					break;
				}
				else
				{
					$li_contador = $li_contador + 1;
				} 	
			} //cierre del while
			
			if ($li_contador + 1 == $li_row )
			{   
				$ls_sql = " SELECT * FROM spg_plantillareporte WHERE spg_cuenta like '".$as_cta_ceros."%' and spg_cuenta <> '".$as_spg_cuenta."' and codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' order by spg_cuenta " ;
                $rs_pr=$this->SQL->select($ls_sql);
				$i=1;
				while($row=$this->SQL->fetch_row($rs_pr))
				{
					$ls_spg_cuenta  =  $row["spg_cuenta"];
					$data[$i]=$ls_spg_cuenta;
					$i=$i+1;
				}// cierre del while rs_oaf.next (update)
			}// cierre del if (li_contador == li_row)
  }//cierre del if
  
return $data;
} // fin de uf_disable_cta_inferior
	
function uf_spg_guardar_progrep($as_codemp,$as_status,$ad_asignado,$as_distribuir,$ad_enero,$ad_febrero,$ad_marzo,$ad_abril,$ad_mayo,$ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,$ad_octubre,$ad_noviembre,$ad_diciembre,$as_spg_cuenta,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
   $ls_sql="";
   $ls_codestpro4="00";
   $ls_codestpro5="00";
   
   if($as_codrep=="00005")
   {
	   $ls_sql = " UPDATE spg_plantillareporte  SET status='".$as_status."', asignado='".$ad_asignado."', distribuir=".$as_distribuir.",".
				 " enero='".$ad_enero."', febrero='".$ad_febrero."', marzo='".$ad_marzo."', abril='".$ad_abril."', mayo='".$ad_mayo."',".
				 " junio='".$ad_junio."', julio='".$ad_julio."', agosto='".$ad_agosto."', septiembre='".$ad_septiembre."', ".
				 " octubre='".$ad_octubre."', noviembre='".$ad_noviembre."', diciembre='".$ad_diciembre."' WHERE codrep='".$as_codrep."' ".
				 " AND codemp='".$as_codemp."' AND spg_cuenta='".$as_spg_cuenta."' ";
	   
   }
   else
   {
		 $ls_sql = " UPDATE spg_plantillareporte  SET status='".$as_status."', asignado='".$ad_asignado."', distribuir=".$as_distribuir.",".
				   " enero='".$ad_enero."', febrero='".$ad_febrero."', marzo='".$ad_marzo."', abril='".$ad_abril."', mayo='".$ad_mayo."',".
				   " junio='".$ad_junio."', julio='".$ad_julio."', agosto='".$ad_agosto."', septiembre='".$ad_septiembre."', ".
				   " octubre='".$ad_octubre."', noviembre='".$ad_noviembre."', diciembre='".$ad_diciembre."' WHERE codrep='".$as_codrep."' ".
				   " AND codemp='".$as_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' ".
				   " AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND spg_cuenta='".$as_spg_cuenta."' ";
   } 
   $li_rows=$this->SQL->execute($ls_sql);
   if(($li_rows==false)&&($li_rows!=""))
   {
	  $lb_valido=false;
	  print $this->is_msg_error = "Error en método uf_spg_guardar_progrep.".$this->SQL->message;
	  
   }
   else
   {
      if($li_rows>=0)
      {
         $lb_valido=true;
	  }
   }
   
return $lb_valido;

}//uf_spg_guardar_progrep()

function uf_select_denominacion($as_spg_cuenta,$as_codemp,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
    $ls_sql="";
    $ls_codestpro4="00";     
	$ls_codestpro5="00";     

	$ls_sql = " SELECT denominacion ".
			  " FROM spg_plantillareporte".
			  " WHERE  spg_cuenta='".$as_spg_cuenta."' AND codemp='".$as_codemp."' AND codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'";
    $rs_progrep=$this->SQL->select($ls_sql);
	if($rs_progrep==false)
	{
	   print $this->is_msg_error = "Error en método uf_select_denominacion.".$this->SQL->message; 
	}
	else
	{
	   if($row=$this->SQL->fetch_row($rs_progrep))
	   {
	      $ls_denominacion=$row["denominacion"];
	   }
	}
 return  $ls_denominacion;
}//uf_select_denominacion


function uf_spg_delete_cuenta($as_spg_cuenta,$as_codemp,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
   $ls_sql="";
   $ls_codestpro4="00";
   $ls_codestpro5="00";
   
   if($as_codrep=="00005")
   {
      $ls_sql= " DELETE  FROM  spg_plantillareporte WHERE codrep='".$as_codrep."' AND codemp='".$as_codemp."' ".
	           " AND spg_cuenta='".$as_spg_cuenta."' ";
   }
   else
   {
      $ls_sql= " DELETE  FROM  spg_plantillareporte WHERE codrep='".$as_codrep."' AND codemp='".$as_codemp."' ".
	           " AND spg_cuenta='".$as_spg_cuenta."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' ".
			   " AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'  "; 
   }
  
   $li_rows=$this->SQL->execute($ls_sql);
   if(($li_rows==false)&&($li_rows!=""))
   {
	  $lb_valido=false;
	  print $this->is_msg_error = "Error en método uf_spg_delete_cuenta.".$this->SQL->message;
	  
   }
   else
   {
      if($li_rows>=0)
      {
         $lb_valido=true;
	  }
   }
 
   if ($lb_valido)
   {
      $this->SQL->begin_transaction();
      //$lb_valido=$this->sig_int->uf_sql_transaction(true);
	  //////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
	  $ls_evento="DELETE";
	  $ls_descripcion =" Eliminar la programacion de reporte ".$as_codrep."  ";
	  $lb_valido= $io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
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
return $lb_valido;
}//fin uf_spg_delete_cuenta

function buscar_referencia($as_spg_cuenta,$as_codemp,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
    $ls_sql="";
    $ls_codestpro4="00";     
	$ls_codestpro5="00";     
    $ls_referencia="";
	$ls_sql = " SELECT referencia ".
			  " FROM spg_plantillareporte".
			  " WHERE  spg_cuenta='".$as_spg_cuenta."' AND codemp='".$as_codemp."' AND codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'";
    $rs_progrep=$this->SQL->select($ls_sql);
	if($rs_progrep===false)
	{
	   print $this->is_msg_error = "Error en método uf_select_denominacion.".$this->SQL->message; 
	}
	else
	{
	   if($row=$this->SQL->fetch_row($rs_progrep))
	   {
	      $ls_referencia=$row["referencia"];
	   }
	}
 return  $ls_referencia;
}//fin 

/*function uf_distribucion_auto(as_cuenta,ad_monto,ai_modo)
{
  $li_mes=0 ;
  $ll_row=,ll_total_row
  $lb_valido=true;
string  ls_spgcuenta,ls_meses[],ls_status,ls_nextCuenta
decimal ldec_monto_mes,ldec_monto_parte,ldec_monto_part_tmp

$ls_meses[1]  = 'enero'
$ls_meses[2]  = 'febrero'
$ls_meses[3]  = 'marzo'
$ls_meses[4]  = 'abril'
$ls_meses[5]  = 'mayo'
$ls_meses[6]  = 'junio'
$ls_meses[7]  = 'julio'
$ls_meses[8]  = 'agosto'
$ls_meses[9]  = 'septiembre'
$ls_meses[10] = 'octubre'
$ls_meses[11] = 'noviembre'
$ls_meses[12] = 'diciembre'

ldec_monto_parte = round(adec_monto/12,2)
//ll_total_row = dw_1.Rowcount()

for ll_row=1 to ll_total_row
	 ls_status = dw_1.GetItemString( ll_row , "status" )
	 ls_spgcuenta = dw_1.GetItemString( ll_row , "spg_cuenta" )
    if ls_spgcuenta = as_cuenta then 
		 if ls_status='C' then
			 for li_mes=1 to 11		
				  ldec_monto_mes = dw_1.GetItemDecimal( ll_row , ls_meses[ li_mes ] )
				  istr_mes.mes[ li_mes ] = ldec_monto_mes
				  istr_mes.parte[ li_mes ] = ldec_monto_parte
				  dw_1.SetItem( ll_row , ls_meses[ li_mes ],ldec_monto_parte)
			 next
			 ldec_monto_mes = dw_1.GetItemDecimal( ll_row , "Diciembre" )
			 istr_mes.mes[ 12 ] = adec_monto - ( ldec_monto_parte * 11 ) 
			 dw_1.SetItem( ll_row , "Diciembre" , adec_monto - ( ldec_monto_parte * 11 ) )
			 istr_mes.parte[ 12 ] = adec_monto - ( ldec_monto_parte * 11 ) 
	
			 dw_1.SetItem( ll_row , "modificado" ,1 ) 
			 dw_1.SetItem( ll_row , "distribuir" ,ai_modo ) 
	    else				 
			 for li_mes=1 to 12		
				  ldec_monto_parte = dw_1.GetItemDecimal( ll_row , ls_meses[ li_mes ] )
				  dw_1.SetItem( ll_row , ls_meses[ li_mes ], ldec_monto_parte - istr_mes.mes[ li_mes ] + istr_mes.parte[ li_mes ] )
			 next			
			 dw_1.SetItem( ll_row , "modificado" ,1 ) 
			 dw_1.SetItem( ll_row , "distribuir" ,ai_modo ) 			 
		  end if 				 
		  ls_nextCuenta = dw_1.GetItemString( ll_row , "referencia" )
        if TRIM(ls_nextCuenta) = '' then 
			  return True
		  else	  
			  lb_valido = uf_distribucion_auto( ls_nextCuenta,adec_monto,ai_modo)
		  end if 	
	 end if 	
next

return lb_valido
  

}*/

}// fin sigesp_spg_class_progrep 
?>