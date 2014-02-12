<?php
class sigesp_sno_c_pagonomina
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $ls_codnom;
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_pagonomina()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno
		//		   Access: public
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
   		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();				
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];		
	}// end function sigesp_sno
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	 function uf_buscar_personal($as_codnom,$codperi,$ai_inicio,$ai_registros,$as_codconcdes,$as_codconchas,&$as_valor,&$ai_totpag,&$as_codperdes,&$as_codperhas,$as_codente="")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_personalnomina
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 07/07/2008								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_orden="";
		$ls_pag="";			
		$ls_criterio="";
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;			
		}
		if (trim($as_codconcdes)!="")	
		{
			$ls_criterio=$ls_criterio." AND sno_salida.codconc >= '".$as_codconcdes."' ";
		}
		if (trim($as_codconchas)!="")	
		{
			$ls_criterio=$ls_criterio." AND sno_salida.codconc <= '".$as_codconchas."' ";
		}
		if (trim($as_codente)!="")	
		{
			$ls_criterio=$ls_criterio." AND sno_concepto.codente = '".$as_codente."' ";
			$ls_sql=" SELECT codper,  ".
					"       (SELECT count(codper) ".
					"		   FROM sno_personalnomina  ".
					"		  WHERE sno_personalnomina.codemp = '".$this->ls_codemp."' ".
					"		    AND sno_personalnomina.codnom='".$as_codnom."' ".
					"    		AND codper in (SELECT codper ".
					"							 FROM sno_salida ".
					"						    INNER JOIN sno_concepto ".
					"							   ON sno_salida.codemp = '".$this->ls_codemp."' ".
					"							  AND sno_salida.codnom='".$as_codnom."' ".
					"							  AND sno_salida.codperi='".$codperi."' ".
					"							  AND sno_salida.valsal<>0 ".
												  $ls_criterio.
					"							  AND sno_salida.codemp = sno_concepto.codemp ".
					"							  AND sno_salida.codnom = sno_concepto.codnom ".
					"							  AND sno_salida.codconc = sno_concepto.codconc ".
					"							WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"							  AND sno_salida.codnom='".$as_codnom."' ".
					"							  AND sno_salida.codperi='".$codperi."' ".
					"							  AND sno_salida.valsal<>0 ".
												  $ls_criterio." )) as valor  ".
					"   FROM sno_personalnomina ".
					"  WHERE codemp = '".$this->ls_codemp."' ".
					"    AND codnom='".$as_codnom."' ".
					"    AND codper in (SELECT codper ".
					"					  FROM sno_salida ".
					"					 INNER JOIN sno_concepto ".
					"					    ON sno_salida.codemp = '".$this->ls_codemp."' ".
					"					   AND sno_salida.codnom='".$as_codnom."' ".
					"					   AND sno_salida.codperi='".$codperi."' ".
					"					   AND sno_salida.valsal<>0 ".
										 $ls_criterio.
					"					   AND sno_salida.codemp = sno_concepto.codemp ".
					"					   AND sno_salida.codnom = sno_concepto.codnom ".
					"					   AND sno_salida.codconc = sno_concepto.codconc ".
					"					 WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"					   AND sno_salida.codnom='".$as_codnom."' ".
					"					   AND sno_salida.codperi='".$codperi."' ".
					"					   AND sno_salida.valsal<>0 ".
										  $ls_criterio." ) ".
					"  GROUP BY codemp, codnom, codper ".
					"  ORDER BY codper ".$ls_pag;
		}
		else
		{
			$ls_sql=" SELECT codper,  ".
					"       (SELECT count(codper) ".
					"		   FROM sno_personalnomina  ".
					"		  WHERE sno_personalnomina.codemp = '".$this->ls_codemp."' ".
					"		    AND sno_personalnomina.codnom='".$as_codnom."' ".
					"    		AND codper in (SELECT codper ".
					"							 FROM sno_salida ".
					"							WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"							  AND sno_salida.codnom='".$as_codnom."' ".
					"							  AND sno_salida.codperi='".$codperi."' ".
					"							  AND sno_salida.valsal<>0 ".
												  $ls_criterio." )) as valor  ".
					"   FROM sno_personalnomina ".
					"  WHERE codemp = '".$this->ls_codemp."' ".
					"    AND codnom='".$as_codnom."' ".
					"    AND codper in (SELECT codper ".
					"					  FROM sno_salida ".
					"					 WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"					   AND sno_salida.codnom='".$as_codnom."' ".
					"					   AND sno_salida.codperi='".$codperi."' ".
					"					   AND sno_salida.valsal<>0 ".
										  $ls_criterio." ) ".
					"  GROUP BY codemp, codnom, codper ".
					"  ORDER BY codper ".$ls_pag;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Calcular Nómina MÉTODO->uf_procesarnomina ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_numero=$rs_data->RecordCount();
			$as_codperdes=$rs_data->fields["codper"];
			$rs_data->MoveLast();
			$as_valor=$rs_data->fields["valor"];
			$as_codperhas=$rs_data->fields["codper"]; 
			$ai_totpag=ceil($as_valor/$ai_registros);
		}
		return $lb_valido;
	}// end function uf_buscar_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_personal_historico($as_codnom,$codperi,&$as_valor,$ai_inicio,$ai_registros,
	                                      &$ai_totpag, &$as_codperdes, &$as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_personal_historico
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 08/07/2008								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_orden="";
		$ls_pag="";			
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;			
		}			
		if (trim($as_codconcdes)!="")	
		{
			$ls_criterio=$ls_criterio." AND codconc >= '".$as_codconcdes."' ";
		}
		if (trim($as_codconchas)!="")	
		{
			$ls_criterio=$ls_criterio." AND codconc <= '".$as_codconchas."' ";
		}
		$ls_sql=" SELECT codper,  ".
                "       (SELECT count(codper) ".
				"		   FROM sno_thpersonalnomina  ".
				"		  WHERE sno_thpersonalnomina.codemp = '".$this->ls_codemp."' ".
				"		    AND sno_thpersonalnomina.codnom='".$as_codnom."' ".
				"    		AND codper in (SELECT codper FROM sno_thsalida WHERE codemp = '".$this->ls_codemp."' AND codnom='".$as_codnom."' AND codperi='".$codperi."' ".$ls_criterio.")) as valor  ".
                "   FROM sno_thpersonalnomina ".
                "  WHERE codemp = '".$this->ls_codemp."' ".
				"    AND codnom='".$as_codnom."' ".
				"    AND codper in (SELECT codper FROM sno_thsalida WHERE codemp = '".$this->ls_codemp."' AND codnom='".$as_codnom."' AND codperi='".$codperi."' ".$ls_criterio.")".
                "  GROUP BY codemp, codnom, codper ".
				"  ORDER BY codper ".$ls_pag;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Pago Nómina MÉTODO->uf_buscar_personal_historico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_numero=$rs_data->RecordCount();
			$as_codperdes=$rs_data->fields["codper"];
			$rs_data->MoveLast();
			$as_valor=$rs_data->fields["valor"];
			$as_codperhas=$rs_data->fields["codper"]; 
			$ai_totpag=ceil($as_valor/$ai_registros);
		}
		return $lb_valido;
	}// end function uf_buscar_personal_historico
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_buscar_beneficiario(&$as_valor,$ai_inicio,$ai_registros,&$ai_totpag, &$as_codperdes, &$as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_beneficiario
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 14/07/2008								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_pag="";			
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;			
		}			
		$ls_sql=" SELECT sno_beneficiario.codper, ".
				"  	  (SELECT  count (sno_beneficiario.codemp) FROM sno_beneficiario GROUP BY sno_beneficiario.codemp) as valor1 ".
				"   FROM sno_beneficiario ".
				"	ORDER BY sno_beneficiario.codper ".$ls_pag;	    
        
		$rs_bene=$this->io_sql->select($ls_sql);
	    $li_numero=$this->io_sql->num_rows($rs_bene);
		$li=1;	
		while($row=$this->io_sql->fetch_row($rs_bene))
		{
			$as_valor=$row["valor1"]; 
			if ($li==1)
			{
				$as_codperdes=$row["codper"];
				$li=0;
			}			
			$li_numero=$li_numero-1;
			if ($li_numero==0)	
			{
				$as_codperhas=$row["codper"]; 
			}
											
		}				
		$ai_totpag = ceil($as_valor / $ai_registros); 
				
		return $lb_valido;
	}// end uf_buscar_beneficiario
	//------------------------------------------------------------------------------------------------------------------------------------

	//------------------------------------------------------------------------------------------------------------------------------------
    function uf_buscar_sno_personal(&$as_valor,$ai_inicio,$ai_registros,&$ai_totpag, &$as_codperdes, &$as_codperhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_sno_personal
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 14/07/2008								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_pag="";			
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_pag= " LIMIT ".$ai_inicio.",".$ai_registros."";
			break;
			case "POSTGRES":
				$ls_pag= " LIMIT ".$ai_registros." OFFSET ".$ai_inicio."";
			
			break;			
		}			
		$ls_sql=" SELECT sno_personal.codper,   ".
				"       (SELECT  count (sno_personal.codper) FROM sno_personal) as valor1 ".
			    "  FROM sno_personal 		    ".
			    "  ORDER BY sno_personal.codper ".$ls_pag;	    
        
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numero=$this->io_sql->num_rows($rs_data);
		$li=1;	
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			$as_valor=$row["valor1"]; 
			if ($li==1)
			{
				$as_codperdes=$row["codper"];
				$li=0;
			}			
			$li_numero=$li_numero-1;
			if ($li_numero==0)	
			{
				$as_codperhas=$row["codper"]; 
			}
											
		}				
		$ai_totpag = ceil($as_valor / $ai_registros); 
				
		return $lb_valido;
	}// fin uf_buscar_sno_personal
	//-----------------------------------------------------------------------------------------------------------------------------------    

	//-----------------------------------------------------------------------------------------------------------------------------------    
    function uf_buscar_concepto($codnom, &$as_codconcdes, &$as_codconchas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_concepto
		//         Access: public (desde la clase sigesp_sno_rpp_pagonomina)  
		//	    Arguments: 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: 
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 14/07/2008								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_pag="";			
		$ls_gestor=$_SESSION["ls_gestor"];		
		$ls_sql=" SELECT  sno_concepto.codconc ".
		        "   FROM sno_concepto          ".
				"  WHERE sno_concepto.codnom='".$codnom."'".
				"  ORDER BY sno_concepto.codconc";	    
        
		$rs_data=$this->io_sql->select($ls_sql);
	    $li_numero=$this->io_sql->num_rows($rs_data);
		$li=1;	
		while($row=$this->io_sql->fetch_row($rs_data))
		{
			if ($li==1)
			{
				$as_codconcdes=$row["codconc"];
				$li=0;
			}			
			$li_numero=$li_numero-1;
			if ($li_numero==0)	
			{
				$as_codconchas=$row["codconc"]; 
			}
											
		}//fin del while					
		return $lb_valido;
	}// fin uf_buscar_sno_personal
	//------------------------------------------------------------------------------------------------------------------------------------
}
?>