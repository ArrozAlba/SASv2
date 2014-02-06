<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
//-----------------------------------------------------------------------------------------------------------------------------------
class sigesp_spi_class_progrep
{
   function sigesp_spi_class_progrep()
   {
		$this->io_function = new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->io_msg=new class_mensajes();
		$this->int_spi=new class_sigesp_int_spi();
		$this->obj=new class_datastore();
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->io_fecha=new class_fecha();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
   }
//-----------------------------------------------------------------------------------------------------------------------------------
 function  uf_prog_report_delete($as_codrep)
 {    
	////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_prog_report_delete 
	//	     Arguments:  $as_codrep // codigo del reporte
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que borrar la información contenida a la tabla 
	//                   plantila reporte del reporte especificado.
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  25/07/2006          Fecha última Modificacion : 
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$ls_sql=" DELETE FROM spi_plantillacuentareporte WHERE codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' ";
	$li_rows_afecta=$this->io_sql->execute($ls_sql);
	if ($li_rows_afecta===false)
	{
	   $lb_valido=false;
	   $this->msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_delete ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else 
	{
	  $lb_valido=true;
	}
    return $lb_valido;
}//fin 
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_spi_cargar_data_original($as_codrep,$aa_seguridad)
 {
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_cargar_data_original 
	//	     Arguments:  $as_codrep // codigo del reporte
	//                   $aa_seguridad // arreglo de seguridad             
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que carga la información nuevamente en la 
	//                   tabla spi_plantillacuentareporte. Esta información es la copia exacta
	//                   de las cuentas definidas en la tabla spi_cuentas en la tabla mencionada anteriormente
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  25/07/2006          Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido =true;
	$lb_ok=false;
	if ($this->uf_prog_report_delete($as_codrep))
	{
		$ls_sql = "  INSERT INTO spi_plantillacuentareporte (cod_report,codemp,spi_cuenta,denominacion,status,sc_cuenta,previsto, ".
				  "                                          devengado,cobrado,cobrado_anticipado,aumento,disminucion,distribuir, ".
				  "                                          enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre, ".
				  "                                          octubre,noviembre,diciembre,nivel,referencia,modrep ) ". 
				  "  SELECT '".$as_codrep."' As cod_report,codemp,spi_cuenta,denominacion,status,sc_cuenta, 0 as previsto, ". 
				  "         0 as devengado, 0 as cobrado, 0 as cobrado_anticipado, 0 as aumento, 0 as disminucion, 1 as distribuir, ". 
				  "         0 as enero, 0 as febrero, 0 as marzo, 0 as abril, 0 as mayo, 0 as junio, 0 as julio, 0 as agosto, ".
				  "         0 as septiembre, 0 as octubre, 0 as noviembre, 0 as diciembre, nivel, referencia,'0' as modrep ".
				  "  FROM spi_cuentas ".
				  "  WHERE codemp='".$this->ls_codemp."' ";
		$rs_load=$this->io_sql->execute($ls_sql);
		if ($rs_load===false)
		{
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->class_progrep MÉTODO->uf_spi_cargar_data_original(INSERT) ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else 
		{
		   $lb_valido=true;
		}
		if ($lb_valido)
		{
			//////////////////////////////////         SEGURIDAD               ////////////////////////////////////////////////////////////////		
			   $ls_evento="INSERT";
			   $ls_descripcion =" Inserto el reporte ".$as_codrep."  ";
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
	if(($as_codrep=="00005")||($as_codrep=="0714")||($as_codrep=='0406'))
	{
	   $lb_ok=true;
	}
	if($lb_ok)
	{	
		if($as_codrep=="00005")
		{//fujo de caja 
			 $ls_sql = " DELETE FROM spi_plantillacuentareporte ".
					   " WHERE codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND ".
					   " NOT (spi_cuenta = '305010000' OR  ".
					   " spi_cuenta = '305010100' OR spi_cuenta = '305010300'  OR spi_cuenta = '305010301' OR ".
					   " spi_cuenta = '305010302' OR spi_cuenta = '305010304'  OR spi_cuenta = '305010308' OR ".
					   " spi_cuenta = '305010309' OR spi_cuenta = '305010302'  OR spi_cuenta = '305010500' OR ".
					   " spi_cuenta = '305010501' OR spi_cuenta = '305010502'  OR spi_cuenta = '305010503' OR ".
					   " spi_cuenta = '303010000' OR spi_cuenta = '303020000'  OR spi_cuenta = '301050000' OR ".
					   " spi_cuenta = '301100000' OR spi_cuenta = '301100401'  OR spi_cuenta = '301100500' OR ".
					   " spi_cuenta = '306010000' OR spi_cuenta = '306020000'  OR spi_cuenta = '305020100' OR ".
					   " spi_cuenta = '305020300' OR spi_cuenta = '305020301'  OR spi_cuenta = '305020302' OR ".
					   " spi_cuenta = '305020308' OR spi_cuenta = '305020309'  OR spi_cuenta = '305020500' OR ".
					   " spi_cuenta = '305020501' OR spi_cuenta = '305020502'  OR spi_cuenta = '305020503' OR ".
					   " spi_cuenta = '307000000' OR spi_cuenta = '308000000'  OR spi_cuenta = '309000000'  ) ";
		}
		if($as_codrep=="0714")
		{//fujo de caja 
			 $ls_sql = " DELETE FROM spi_plantillacuentareporte ".
					   " WHERE codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND ".
					   " NOT (spi_cuenta = '306010000' OR  ".
					   " spi_cuenta = '306020000' OR spi_cuenta = '307000000'  OR spi_cuenta = '308000000' OR ".
					   " spi_cuenta = '309000000' OR spi_cuenta = '310000000' ) ";
		}
		if($as_codrep=="0406")
		{//Estado resultado
			 $ls_sql = " DELETE FROM spi_plantillacuentareporte ".
					   " WHERE codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND ".
					   " NOT (spi_cuenta = '305010000' OR  ".
					   " spi_cuenta = '305010100' OR spi_cuenta = '305010300' OR spi_cuenta = '305010301' OR ".
					   " spi_cuenta = '305010302' OR spi_cuenta = '305010304' OR spi_cuenta = '305010308' OR 
					   	 spi_cuenta = '305010309' OR spi_cuenta = '305010500' OR spi_cuenta = '305010501' OR 
						 spi_cuenta = '305010502' OR spi_cuenta = '305010303' OR spi_cuenta = '303010000' OR 
						 spi_cuenta = '303030000' OR spi_cuenta = '301050000' OR spi_cuenta = '301100000' OR 
						 spi_cuenta = '301100401' OR spi_cuenta = '301100500' ) ";
		}
		$li_rows_afecta=$this->io_sql->execute($ls_sql);
		if($li_rows_afecta===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_spi_cargar_data_original(DELETE) ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		    //print $this->io_sql->message;
		}
		else
		{
			$lb_valido=true;
		}
		if ($lb_valido)
		{
			  //////////////////////////////////         SEGURIDAD        ////////////////////////////////////////////////////////////////		
			   $ls_evento="DELETE";
			   $ls_descripcion =" Eliminar el reporte ".$as_codrep."  ";
			   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											    $aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											    $aa_seguridad["ventanas"],$ls_descripcion);
		     /////////////////////////////////         SEGURIDAD         //////////////////////////////////////////////////////////////////	
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
  }//fin  uf_spi_cargar_data_original
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_spi_cargar_data($as_codrep,$as_modrep,&$rs_progrep)
 {
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_cargar_data 
	//	     Arguments:  $as_codrep // codigo del reporte
	//                   $as_modrep // modalidad del reporte
	//                   $rs_progrep  // result de la data (referencia)             
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que carga la información de la  programacion de reportes 
	//                   y lo almacena en un resulset que luego se pasa por referencia.
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  25/07/2006          Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_modrep=0;
	$ls_sql = " SELECT spi_cuenta,denominacion,status,previsto,distribuir,enero,febrero,marzo,abril, ".
			  "        mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,modrep ".
			  " FROM   spi_plantillacuentareporte ".
			  " WHERE  codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' AND (modrep='".$as_modrep."' OR modrep='".$ls_modrep."') ".
			  " ORDER BY spi_cuenta ";
	$rs_progrep=$this->io_sql->select($ls_sql);
	if($rs_progrep===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_spi_cargar_data ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
    return $lb_valido; 
  }///fin uf_spi_cargar_data
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_obtener_nivel_cta($as_cuenta,$ai_nivel)
 {
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_obtener_nivel_cta 
	//	     Arguments:  $as_cuenta // codigo de la cuenta
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Busca en la tabla scg_pc_report el nivel de la cuenta que pasa por parametro 
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  26/07/2006          Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql = " SELECT nivel ".
			  " FROM   spi_plantillacuentareporte ".
			  " WHERE  spi_cuenta = '".$as_cuenta."' AND codemp='".$this->ls_codemp."' ";	
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
	    $lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_obtener_nivel_cta ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	 } 
	else
	{
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
		   $ai_nivel = $row["nivel"];
	   }
	   else
	   {
		  $ai_nivel = 0; //no existen registros
	      $this->io_sql->free_result($rs_data);
	   }
	}
	return $lb_valido;
  }//uf_obtener_nivel_cta 
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_cuenta_sin_ceros($as_cuenta)
 { 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_cuenta_sin_ceros 
	//	     Arguments:  $as_cuenta // codigo de la cuenta
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Elimina los ceros a la derecha de la cuenta contable   
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  26/07/2006          Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$li_lenCta=0; $li_cero=1;
	$ls_cta_ceros=""; $ls_cad="";
	$lb_encontrado=true;
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
			$ls_cta_ceros = substr(trim($ls_cta_ceros),0,$li_cant);
			$lb_encontrado=true;
		}
		else
		{
			$lb_encontrado = false;
		}
	}while ( $lb_encontrado == true ); 
	return $ls_cta_ceros;
  }//uf_cuenta_sin_ceros
//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_disable_cta_inferior($as_cta_ceros,$as_spi_cuenta,$as_codrep)
 {
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_disable_cta_inferior 
	//	     Arguments:  $as_cta_ceros // cuenta sin ceros
	//                   $as_spg_cuenta  // codigo de la cuenta
	//                   $as_codrep       // codigo del reporte
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca las cuentas inferiores  de la cuenta  pasada por parametros
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  26/07/2006          Fecha última Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
		
	$lb_valido=true;
	$li_row = 0; $li_contador=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0;
	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
	$ldc_enero=0; $ldc_febrero=0; $ldc_marzo=0; $ldc_abril=0; $ldc_mayo=0; $ldc_junio=0; $ldc_asignado=0;
	$ldc_julio=0; $ldc_agosto=0; $ldc_septiembre=0; $ldc_octubre=0; $ldc_noviembre=0; $ldc_diciembre=0;
	$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
	$data[]="";
	$ls_sql = " SELECT *  ".
	          " FROM   spi_plantillacuentareporte ".
			  " WHERE  spi_cuenta like '".$as_cta_ceros."%' AND spi_cuenta <> '".$as_spi_cuenta."' AND ".
			  "        cod_report='".$as_codrep."' AND codemp='".$this->ls_codemp."' ".
			  " ORDER BY spi_cuenta " ;
	$rs_data=$this->io_sql->select($ls_sql);
	$li_row=$this->io_sql->num_rows($rs_data);
	if ($row=$this->io_sql->fetch_row($rs_data))
	{
		while ($row=$this->io_sql->fetch_row($rs_data))
		{	
			$ld_previsto = $row["previsto"];
			$ls_spi_cuenta = $row["spi_cuenta"];
									
			if (!($ldc_asignado == 0))
			{
				$li_rtn = 1 ;
				$this->io_msg->message("La cuenta ".$ls_sc_cuenta." tiene asignación. ");
				break;
			}
			else
			{
				$li_contador = $li_contador + 1;
			} 	
		} //cierre del while
		if ($li_contador + 1 == $li_row )
		{   
			$ls_sql = " SELECT * ". 
			          " FROM   spi_plantillacuentareporte ".
					  " WHERE  spi_cuenta like '".$as_cta_ceros."%' AND spi_cuenta <> '".$as_spi_cuenta."' AND ".
					  "        cod_report='".$as_codrep."' AND codemp='".$this->ls_codemp."' ".
					  " ORDER BY spi_cuenta " ;
			$rs_data=$this->io_sql->select($ls_sql);
			$i=1;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_spi_cuenta  =  $row["spi_cuenta"];
				$data[$i]=$ls_spi_cuenta;
				$i=$i+1;
			}// cierre del while rs_oaf.next (update)
		}// cierre del if (li_contador == li_row)
      }//cierre del if
      return $data;
   } // fin de uf_disable_cta_inferior
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_spi_guardar_programacion_reportes($as_status,$ad_previsto,$as_distribuir,$as_modrep,$ad_enero,$ad_febrero,$ad_marzo,
                                              $ad_abril,$ad_mayo,$ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,$ad_octubre,
											  $ad_noviembre,$ad_diciembre,$as_spi_cuenta,$as_codrep)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_guardar_programacion_reportes 
	//	     Arguments:  $as_cta_ceros // cuenta sin ceros
	//                   $as_spg_cuenta  // codigo de la cuenta
	//                   $as_codrep       // codigo del reporte
	//	       Returns:	 retorna un true o false si se hizo correcto o no el update
	//	   Description:  Actualiza la tabla spi_plantillacuentareporte con los datos pasados por parametros 
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  31/07/2006          Fecha última Modificacion : 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
    $ls_sql = " UPDATE spi_plantillacuentareporte  ".
			  " SET    status='".$as_status."', previsto='".$ad_previsto."', distribuir=".$as_distribuir.", modrep='".$as_modrep."', ".
			  "        enero='".$ad_enero."', febrero='".$ad_febrero."', marzo='".$ad_marzo."', abril='".$ad_abril."', mayo='".$ad_mayo."',".
			  "        junio='".$ad_junio."', julio='".$ad_julio."', agosto='".$ad_agosto."', septiembre='".$ad_septiembre."', ".
			  "        octubre='".$ad_octubre."', noviembre='".$ad_noviembre."', diciembre='".$ad_diciembre."' ".
			  " WHERE  cod_report='".$as_codrep."'  AND codemp='".$this->ls_codemp."' AND spi_cuenta='".$as_spi_cuenta."' ";
   $li_rows=$this->io_sql->execute($ls_sql);
   if($li_rows===false)
   {
	  $lb_valido=false;
	  $this->io_msg->message("CLASE->class_progrep MÉTODO->uf_spi_guardar_programacion_reportes ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	  
   }
   else
   {
      if($li_rows>=0)
      {
         $lb_valido=true;
	  }
   }
   return $lb_valido;
}//uf_spi_guardar_programacion_reportes()
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_denominacion($as_spi_cuenta,$as_codrep,&$as_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_denominacion 
	//	     Arguments:  $as_spg_cuenta  // codigo de la cuenta
	//                   $as_codrep       // codigo del reporte
	//                   $as_denominacion  // denominacion de la cuenta (referencia)
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca la denominacion de la cuenta
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  26/07/2006          Fecha última Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql = " SELECT  denominacion ".
			  " FROM    spi_plantillacuentareporte ".
			  " WHERE   spi_cuenta='".$as_spi_cuenta."' AND codemp='".$this->ls_codemp."' AND ".
			  "         cod_report='".$as_codrep."' ";
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_select_denominacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	   if($row=$this->io_sql->fetch_row($rs_data))
	   {
	      $as_denominacion=$row["denominacion"];
	   }
			$this->io_sql->free_result($rs_data);
	}
    return  $lb_valido;
  }//uf_select_denominacion
//---------------------------------------------------------------------------------------------------------------------------------------------
function uf_spi_buscar_referencia($as_spi_cuenta,$as_codrep,&$as_referencia)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spi_buscar_referencia 
	//	     Arguments:  $as_spi_cuenta  // codigo de la cuenta
	//                   $as_codrep       // codigo del reporte
	//                   $as_denominacion  // denominacion de la cuenta (referencia)
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca la denominacion de la cuenta
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :  26/07/2006          Fecha última Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql = " SELECT  referencia ".
			  " FROM    spi_plantillacuentareporte".
			  " WHERE   spi_cuenta='".$as_spi_cuenta."' AND codemp='".$this->ls_codemp."' AND cod_report='".$as_codrep."' ";
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_spi_buscar_referencia ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	   if($row=$this->io_sql->fetch_row($rs_data))
	   {
	      $as_referencia=$row["referencia"];
	   }
			$this->io_sql->free_result($rs_data);
	}
    return  $lb_valido;
  }//fin 
//-----------------------------------------------------------------------------------------------------------------------------------
}// fin sigesp_spg_class_progrep 
?>