<?php
class class_validacion
{
	function class_validacion()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: class_validacion
		//		   Access: public
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
	}

   //--------------------------------------------------------------
   function uf_valida_texto($as_valor,$ai_inicio,$ai_longitud,$as_valordefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_texto
		//		   Access: public
		//	    Arguments: as_valor  // Variable que deseamos validar
		//				   ai_inicio  // inicio donde se va a cortar el campo
		//				   ai_longitud  // longitud del campo
		//				   as_valordefecto  // Valor por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida un texto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ls_nuevovalor=$as_valor;
		$ls_nuevovalor=ltrim(rtrim($ls_nuevovalor));
		
		if(($ls_nuevovalor=="")||($ls_nuevovalor==NULL))
		{
			$ls_nuevovalor=$as_valordefecto;
		}
		else
		{
			$ls_nuevovalor=str_replace("'","",$ls_nuevovalor);
			$ls_nuevovalor=str_replace('"',"",$ls_nuevovalor);
			$ls_nuevovalor=str_replace('\\',"",$ls_nuevovalor);
			$ls_nuevovalor=substr($ls_nuevovalor,$ai_inicio,$ai_longitud);
		}
   		return $ls_nuevovalor; 
   }// end function uf_valida_texto
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_valida_fecha($ad_fecha,$ad_fechadefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_fecha
		//		   Access: public
		//	    Arguments: ad_fecha  // Variable que deseamos validar
		//				   ad_fechdefecto  // Fecha por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida una fecha
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$ld_nuevafecha=$ad_fecha;
		$ld_nuevafecha=trim($ld_nuevafecha);
		
		if(($ld_nuevafecha=="")||($ld_nuevafecha==NULL))
		{
			$ld_nuevafecha=$ad_fechadefecto;
		}
		else
		{
			$li_pos=strpos($ld_nuevafecha,"/");
			$li_pos2=strpos($ld_nuevafecha,"-");
			if(($li_pos==2)||($li_pos2==2))
			{
				$ld_nuevafecha=(substr($ld_nuevafecha,6,4)."-".substr($ld_nuevafecha,3,2)."-".substr($ld_nuevafecha,0,2)); 
			}
			elseif(($li_pos==4)||($li_pos2==4))
			{
				$ld_nuevafecha=$ld_nuevafecha;
			}			
		}
   		return $ld_nuevafecha; 
   }// end function uf_valida_fecha
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_valida_monto($ai_monto,$ai_montodefecto)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_monto
		//		   Access: public
		//	    Arguments: ai_monto  // Variable que deseamos validar
		//				   ai_montodefecto  // Fecha por defecto de la variable si viene en blanco
		//	      Returns: valor contenido de la variable
		//	  Description: Función que valida un monto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/06/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$li_nuevomonto=$ai_monto;
		$li_nuevomonto=trim($li_nuevomonto);
		
		if(($li_nuevomonto=="")||($li_nuevomonto==NULL))
		{
			$li_nuevomonto=$ai_montodefecto;
		}
   		return $li_nuevomonto; 
   }// end function uf_valida_monto
   //--------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_suma_fechas($ad_fecha,$ai_ndias)
    {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_suma_fechas
		//		   Access: public
		//	    Arguments: ad_fecha // Fecha a la que se desa sumar
		//                 ai_ndias // Cantidad de dias a sumar          
		//	      Returns: nuevafecha-> variable date
		//	  Description: suma una cantidad de dias pasado por parametros  a una fecha pasada por parametros 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($ai_ndias>0)
		{
			$dia=substr($ad_fecha,8,2);      
			$mes=substr($ad_fecha,5,2);      
			$anio=substr($ad_fecha,0,4);      
			$ultimo_dia=date("d",mktime(0, 0, 0,$mes+1,0,$anio));
			$dias_adelanto=$ai_ndias;
			$siguiente=$dia+$dias_adelanto;
			if($ultimo_dia<$siguiente)
			{        
				$dia_final=$siguiente-$ultimo_dia;
				$mes++;         
				if($mes=='13')
				{            
					$anio++;
					$mes='01';        
				}      
				$fecha_final=$anio.'/'.str_pad($mes,2,"0",0).'/'.str_pad($dia_final,2,"0",0); 
			}
			else   
			{         
				$fecha_final=$anio.'/'.str_pad($mes,2,"0",0).'/'.str_pad($siguiente,2,"0",0); 
			} 
		}
		else
		{
			$fecha_final=$ad_fecha;
		}
		return $fecha_final;
    }// end function uf_suma_fechas	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_insert_sistema_apertura($as_codsis)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_apertura
		//		   Access: public
		//	  Description: Funcion que inserta si un sistema termino su conversión
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/09/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		$io_conect	= new sigesp_include();
		$io_conexion_destino = $io_conect->uf_conectar($_SESSION["ls_data_des"]);
		$io_sql = new class_sql($io_conexion_destino);
		$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type) VALUES ".
				"('0001','".$as_codsis."','APERTURA','".date("Y-m-d H:i")."','SISTEMA DE APERTURA','C')";
		$li_row=$io_sql->execute($ls_sql);
		if($li_row===false)
		{
			return false;
		}
		unset($io_sql);
        return $lb_valido;	
	} // end function uf_insert_apertura
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_sistema_apertura($as_codsis,&$as_resultado)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_sistema_apertura
		//		   Access: public
		//	  Description: Funcion que busca si un sistema se convirtio y si fue exitoso ó no
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 30/09/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_resultado="";
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_sql.php");
		$io_conect	= new sigesp_include();
		$io_conexion_destino = $io_conect->uf_conectar($_SESSION["ls_data_des"]);
		$io_sql = new class_sql($io_conexion_destino);
		$ls_sql="SELECT * ".
				"  FROM sigesp_config ".
				" WHERE codemp='0001'".
				"   AND codsis='".$as_codsis."' ".
				"   AND seccion='APERTURA' ";
		$io_recordset=$io_sql->select($ls_sql);
		if($io_recordset===false)
		{
			print $io_sql->message."<br>";
			return false;
		}
		else
		{
			if($row=$io_sql->fetch_row($io_recordset))
			{
				$as_resultado="disabled";
			}
		}	  
        return $lb_valido;	
	} // end function uf_select_sistema_apertura
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>