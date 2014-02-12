<?php
class sigesp_srh_class_report
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_srh_class_report($as_path="../../")
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_srh_class_report
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Fecha Creación: 11/03/2007 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once($as_path."shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once($as_path."shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS2=new class_datastore();
		$this->ds_detalle=new class_datastore();
		$this->ds_detalle2=new class_datastore();
		$this->ds_detalle3=new class_datastore();
		$this->ds_detalle4=new class_datastore();
		$this->ds_detalle5=new class_datastore();
		$this->ds_detalle6=new class_datastore();
		$this->det_per_psi=new class_datastore();
		$this->det_item_psi=new class_datastore();
		$this->det_item_ent=new class_datastore();
		$this->det_ascenso=new class_datastore();
		$this->det_item_asc=new class_datastore();
		$this->detalle_pasantia=new class_datastore();
		$this->det_list_adi=new class_datastore();
		$this->det_pers_adi=new class_datastore();
		require_once($as_path."shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once($as_path."shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once($as_path."shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once($as_path."shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad=new sigesp_c_seguridad();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_sep_class_report
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_evaluacion_desemp($ad_fechades,$ad_fechahas,$as_codperdes,$as_codperhas,$as_orden)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_recepcion
		//         Access: public  
		//	    Arguments: as_tipproben     // Tipo de Proveedor/Beneficiario
		//                 as_codprobendes  // Codigo de Proveedor/Beneficiario Desde
		//                 as_codprobenhas  // Codigo de Proveedor/Beneficiario Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 as_codtipdoc     // Codigo de Tipo de Documento
		//                 as_registrada    // Estatus de la Recepcion Registrada
		//                 ai_anulada       // Estatus de la Recepcion Anulada
		//                 ai_procesada     // Estatus de la Recepcion Procesada
		//                 ai_orden         // Orden de los Datos en el Reporte Numero/Fecha
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las recepciones de documentos en los intervalos indicados
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		
		$ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);	   
	    $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);  
	  	if (($ad_fechades!="")&&($ad_fechahas!=""))
	    {
		  $ls_criterio= " AND  srh_evaluacion_desempeno.fecha BETWEEN '".$ad_fechades."' AND '".$ad_fechahas."'";
		}
	  	if (($as_codperdes!="")&&($as_codperhas!=""))
	    {
		  $ls_criterio=$ls_criterio." AND  sno_personal.codper BETWEEN '".$as_codperdes."' and  '".$as_codperhas."' ";
		}
        
		
		switch($as_orden)
		{
		case "1": // Ordena por codigo del personal
		
		$ls_orden="srh_persona_evaluacion_desempeno.codper ";
		break;
		
		case "2": // Ordena por nombre
		
		$ls_orden="sno_personal.nomper";
		break;
		}
	
     $ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);
	 $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);
     $ls_sql=" SELECT srh_evaluacion_desempeno.*, sno_personal.codper, sno_personal.nomper, sno_personal.apeper, ".
	          " (SELECT desuniadm FROM sno_unidadadmin,sno_personalnomina,srh_persona_evaluacion_desempeno,sno_nomina
				WHERE sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm 
				AND sno_personalnomina.codper=srh_persona_evaluacion_desempeno.codper
				AND sno_nomina.codnom = sno_personalnomina.codnom 
				AND sno_nomina.espnom='0'
				AND srh_persona_evaluacion_desempeno.tipo='P') as desuniadm ".
              " FROM  srh_evaluacion_desempeno,srh_persona_evaluacion_desempeno, sno_personal".
              " WHERE srh_persona_evaluacion_desempeno.codemp='".$this->ls_codemp."' AND sno_personal.codper=srh_persona_evaluacion_desempeno.codper AND ".
			  " srh_evaluacion_desempeno.nroeval=srh_persona_evaluacion_desempeno.nroeval AND ".
			  " srh_persona_evaluacion_desempeno.tipo='P' ".$ls_criterio.
              " ORDER BY ".$ls_orden." ";	   
			  	  
		  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_evaluacio_desemp
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_revisiones($as_codperdes,$as_codperhas,$ad_fecregdes,$ad_fecreghas,$as_orden)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_revisiones
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo de Personal Desde
		//                 as_codperhas  // Codigo de Personal Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 ai_orden         // Orden de los Datos en el Reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las revisiones odi del personal
		//	   Creado Por: Ing.Gusmary Balza
		// Fecha Creación: 23/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		
		if(!empty($as_codperdes))
		{
			
				$ls_criterio= $ls_criterio."   AND srh_persona_odi.codper>='".$as_codperdes."'";
			
		}
		if(!empty($as_codperhas))
		{
		
				$ls_criterio= $ls_criterio."   AND srh_persona_odi.codper<='".$as_codperhas."'";
			
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND srh_odi.fecha>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND srh_odi.fecha<='".$ad_fecreghas."'";
		}

	
		switch($as_orden)
		{
			case "1": // Ordena por numero de registro
				$ls_orden="srh_persona_odi.nroreg ";
				break;

			case "2": // Ordena por fecha
				$ls_orden="srh_odi.fecha ";
				break;
           case "3": // Ordena por nombre
				$ls_orden="sno_personal.nomper ";
				break;
		}
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
				
		 $ls_sql=" SELECT srh_persona_odi.nroreg,sno_personal.codper,sno_personal.nomper,sno_personal.apeper,".$ls_cadena." as nombre,srh_odi.total,srh_odi.fecha".
              " FROM   sno_personal, srh_persona_odi, srh_odi ".
              " WHERE  srh_persona_odi.codemp='".$this->ls_codemp."' AND sno_personal.codper=srh_persona_odi.codper ".
			  " AND srh_persona_odi.nroreg=srh_odi.nroreg  ".
			  "        ".$ls_criterio." ".
			 " group by sno_personal.codper, srh_persona_odi.nroreg,sno_personal.nomper,sno_personal.apeper,srh_odi.total,
			    srh_odi.fecha ".
              " ORDER BY ".$ls_orden." ";	
				
				
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_revisiones ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_revisiones	
//----------------------------------------------------------------------------------------------------------------------------------------
	function uf_lista_evaluacion_eficiencia($as_fechades,$as_fechahas,$as_codperdes,$as_codperhas,$as_orden)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_lista_evaluacion_eficiencia
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo de Personal Desde
		//                 as_codperhas  // Codigo de Personal Hasta
		//                 as_fechades     // Fecha de Registro Desde
		//                 as_fechahas     // Fecha de Registro Hasta
		//                 as_orden         // Orden de los Datos en el Reporte
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones  del personal
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 26/02/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	  $ls_cadena="";
	  $ls_orden="";	
	  $ls_valido=true;	  
	  $as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);	   
	  $as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas);  
	  if (($as_fechades!="")&&($as_fechahas!=""))
	    {
		  $ls_cadena= " and a.fecha between '".$as_fechades."' and '".$as_fechahas."'";
		}
	  if (($as_codperdes!="")&&($as_codperhas!=""))
	    {
		  $ls_cadena=$ls_cadena." and b.codper between '".$as_codperdes."' and  '".$as_codperhas."' ";
		}
		
	  if ($as_orden==1)
	    {
		  $ls_orden="order by a.nroeval";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden="order by b.codper";
		}
	  if ($as_orden==3)
	    {
		  $ls_orden="order by nombre";
		}
	   if ($as_orden==4)
	    {
		  $ls_orden="order by nombre";
		}
	
	 if ($_SESSION["ls_gestor"]=="MYSQLT")
	 {
	  $ls_sql="SELECT  distinct(a.nroeval) as nroeval,a.codemp, b.codper, b.tipo,
        			   d.cedper, 
        			   case d.apeper when null then d.nomper else concat(d.apeper,' ',d.nomper) end nombre,
        			   a.fecha,
            		   (select sum(puntos) from srh_dt_evaluacion_eficiencia  where nroeval=a.nroeval group by nroeval) as suma
			  FROM srh_evaluacion_eficiencia a
			  join srh_persona_evaluacion_eficiencia b on (a.codemp=b.codemp) and (a.nroeval=b.nroeval)
			  join sno_personal d on (b.codper=d.codper)
			  where a.codemp='".$this->ls_codemp."'  ".$ls_cadena.$ls_orden; 
	 }
	 else 
	 {
	 	$ls_sql="SELECT distinct(a.nroeval) as nroeval,a.codemp, b.codper, b.tipo, d.cedper,
 (d.apeper||' '||d.nomper) as nombre, a.fecha,  (select sum(puntos) from srh_dt_evaluacion_eficiencia  where nroeval=a.nroeval group by nroeval) as suma
			  FROM srh_evaluacion_eficiencia a
			  join srh_persona_evaluacion_eficiencia b on (a.codemp=b.codemp) and (a.nroeval=b.nroeval)
			  join sno_personal d on (b.codper=d.codper)
			  where a.codemp='".$this->ls_codemp."'  ".$ls_cadena.$ls_orden; 	 
			  
	
	 }
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_lista_evaluacion_eficiencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $lb_valido;	
	}
//-------------------------------------------------------------------------------------------------------------------------------	
function uf_select_bonos_x_merito($ad_fechades,$ad_fechahas,$as_codperdes,$as_codperhas,$as_coduniadm1,$as_coduniadm2)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_bonos_x_merito
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//            
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_cadena="";
		$ls_gestor = $_SESSION["ls_gestor"];
		$ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);	   
	    $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);  
		
		 if (($ad_fechades!="")&&($ad_fechahas!=""))
	    {
		  $ls_cadena=$ls_cadena. " AND srh_bono_merito.fecha BETWEEN '".$ad_fechades."' AND '".$ad_fechahas."'";
		}
	    if (($as_codperdes!="")&&($as_codperhas!=""))
	    {
		  $ls_cadena=$ls_cadena." AND srh_bono_merito.codper BETWEEN '".$as_codperdes."' AND '".$as_codperhas."' ";
		}
		
		if ((!empty($as_coduniadm1)) && (!empty($as_coduniadm2)))
		{
			$minorguniadm1 = substr($as_coduniadm1,0,4);
			$ofiuniadm1 = substr($as_coduniadm1,5,2);
			$uniuniadm1 = substr($as_coduniadm1,8,2);
			$depuniadm1 = substr($as_coduniadm1,11,2);
			$prouniadm1 = substr($as_coduniadm1,14,2);	
			
			$minorguniadm2 = substr($as_coduniadm2,0,4);
			$ofiuniadm2 = substr($as_coduniadm2,5,2);
			$uniuniadm2 = substr($as_coduniadm2,8,2);
			$depuniadm2 = substr($as_coduniadm2,11,2);
			$prouniadm2 = substr($as_coduniadm2,14,2);
				
			$ls_cadena=$ls_cadena." AND sno_personalnomina.minorguniadm BETWEEN '".$minorguniadm1."' AND '".$minorguniadm2."'";	
			$ls_cadena=$ls_cadena."	AND sno_personalnomina.ofiuniadm  BETWEEN   '".$ofiuniadm1."' AND '".$ofiuniadm2."' ";
			$ls_cadena=$ls_cadena."	AND sno_personalnomina.uniuniadm  BETWEEN   '".$uniuniadm1."' AND '".$uniuniadm2."' ";
			$ls_cadena=$ls_cadena."	AND sno_personalnomina.depuniadm  BETWEEN   '".$depuniadm1."' AND '".$depuniadm2."' ";
			$ls_cadena=$ls_cadena."	AND sno_personalnomina.prouniadm  BETWEEN   '".$prouniadm1."' AND '".$prouniadm2."' ";
		}
       
	
     $ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);
	 $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);
	 
	 
     $ls_sql=" SELECT srh_bono_merito.*, sno_personalnomina.codper ".
              " FROM   srh_bono_merito, sno_personalnomina, sno_nomina".
              " WHERE srh_bono_merito.codemp='".$this->ls_codemp."'  ".
			  " AND srh_bono_merito.codper = sno_personalnomina.codper ".
			  " AND sno_nomina.codnom = sno_personalnomina.codnom ".
			  " AND sno_nomina.espnom='0' ".$ls_cadena.
			  " ORDER BY srh_bono_merito.codper, srh_bono_merito.fecha";	 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_bonos_x_merito ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito


 function uf_select_persona_bonos_x_merito($as_codper)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_persona_bonos_x_merito
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben   
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
        $ls_sql=" SELECT sno_personal.codper, sno_personal.nomper,sno_personal.apeper,".
				"(SELECT denasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as cargo1,
				(SELECT descar FROM sno_cargo 
				WHERE sno_personalnomina.codemp = sno_cargo.codemp 
				AND sno_personalnomina.codnom = sno_cargo.codnom 
				AND sno_personalnomina.codcar = sno_cargo.codcar) as cargo2,
				(SELECT desuniadm FROM sno_unidadadmin
				WHERE sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ) as desuniadm ".
              " FROM  sno_personalnomina, sno_personal, sno_nomina ".
              " WHERE sno_personal.codper=sno_personalnomina.codper AND ".
			  " sno_personal.codper= '".$as_codper."' AND ".
			  " sno_nomina.codemp=sno_personal.codemp     ".
			  " AND sno_nomina.codnom = sno_personalnomina.codnom ".
			  " AND sno_nomina.espnom='0' ";	   

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_persona_bonos_x_merito ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_persona_bonos_x_merito
	//-----------------------------------------------------------------------------------------------------------------------------------

 function uf_select_persona_pago_bonos_x_merito($as_codper)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_persona_pago_bonos_x_merito
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben   
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
        $ls_sql=" SELECT sno_personal.codper, sno_personal.nomper,sno_personal.apeper, sno_nomina.codnom,".
				"(SELECT dentippersss  FROM sno_tipopersonalsss
				WHERE sno_personal.codemp = sno_tipopersonalsss.codemp 
				AND sno_personal.codtippersss = sno_tipopersonalsss.codtippersss) as tipper ".
              " FROM  sno_personalnomina, sno_personal, sno_nomina ".
              " WHERE sno_personal.codper=sno_personalnomina.codper AND ".
			  " sno_personal.codper= '".$as_codper."' AND ".
			  " sno_nomina.codemp=sno_personal.codemp     ".
			  " AND sno_nomina.codnom = sno_personalnomina.codnom ".
			  " AND sno_nomina.espnom='0' ";	   

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_persona_pago_bonos_x_merito ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_persona_pago_bonos_x_merito

	//-----------------------------------------------------------------------------------------------------------------------------------

 function uf_select_concurso($ad_fechades,$ad_fechahas,$as_estatus,$as_orden,&$rs_data)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_concurso
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 as_estatus    //  Estatus del concurso
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//            
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los concursos en un periodo.
		//	   Creado Por: María Beatriz Unda.
		// Fecha Creación: 27/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
       
		
		switch($as_orden)
		{
		case "1": // Ordena por codigo del concurso
		
		$ls_orden="srh_concurso.codcon ";
		break;
		
		case "2": // Ordena por cargo
		
		$ls_orden="srh_concurso.codcar";
		break;
		case "3": // Ordena por pfecha de inicio del concurso
		
		$ls_orden="srh_concurso.fechaaper";
		break;
		case "4": // Ordena por pfecha de cierre del concurso
		
		$ls_orden="srh_concurso.fechacie";
		break;
		}
	
     $ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);
	 $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);
	 
	 if (($as_estatus=='Abierto') || ($as_estatus=='Cerrado')){
	 
	 
		 $ls_sql="SELECT * FROM srh_concurso  ".
		        "  LEFT JOIN sno_cargo ON (srh_concurso.codcar = sno_cargo.codcar AND srh_concurso.codnom = sno_cargo.codnom )
				   LEFT JOIN sno_asignacioncargo ON (srh_concurso.codcar = sno_asignacioncargo.codasicar AND srh_concurso.codnom = sno_asignacioncargo.codnom) ". 
				  " WHERE srh_concurso.codemp='".$this->ls_codemp."' AND ".
				  " srh_concurso.fechaaper between '".$ad_fechades."' AND '".$ad_fechahas."' ".				 
				   " AND srh_concurso.estatus = '".$as_estatus."' ".
				  " ORDER BY ".$ls_orden." ";	   

		
		 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
		}
		 
		 	
			
	}
	elseif ($as_estatus=='') {
		 $ls_sql="SELECT * FROM srh_concurso  ".
		        "  LEFT JOIN sno_cargo ON (srh_concurso.codcar = sno_cargo.codcar AND srh_concurso.codnom = sno_cargo.codnom )
				   LEFT JOIN sno_asignacioncargo ON (srh_concurso.codcar = sno_asignacioncargo.codasicar AND srh_concurso.codnom = sno_asignacioncargo.codnom) ". 
				  " WHERE srh_concurso.codemp='".$this->ls_codemp."'  AND".
				  " srh_concurso.fechaaper between '".$ad_fechades."' AND '".$ad_fechahas."'  ".
				  " ORDER BY ".$ls_orden." ";
				  
				    
	
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
			}
					
			
	
	  }
	  return $lb_valido;
	}// end function uf_select_concurso
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_revisiones_metas_x_personal($ad_fechades,$ad_fechahas,$as_codperdes,$as_codperhas)
	 {
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_revisiones_metas_x_personal
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta  
		//            
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los datos del personal, la fecha de inicio y fin de las metas planificadas por cada personal.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 26/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
		if(!empty($as_codperdes))
		{
		 $ls_criterio= $ls_criterio."   AND srh_persona_revision_metas.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
		  $ls_criterio= $ls_criterio."   AND srh_persona_revision_metas.codper<='".$as_codperhas."'";
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND srh_registro_metas.fecini>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND srh_registro_metas.fecfin <='".$ad_fecreghas."'";
		}
       	 $ls_sql=" SELECT srh_persona_revision_metas.*,srh_registro_metas.*,sno_personal.codper,sno_personal.nomper,sno_personal.apeper".
				  " FROM  srh_persona_revision_metas,srh_registro_metas, sno_personal".
				  " WHERE srh_persona_revision_metas.codemp='".$this->ls_codemp."' AND sno_personal.codper=srh_persona_revision_metas.codper AND ".
				  " srh_persona_revision_metas.codper=srh_registro_metas.codper AND ".
				  " srh_persona_revision_metas.tipo='P' AND".
				  " srh_persona_revision_metas.nroreg =srh_registro_metas.nroreg ".
				  "   ".$ls_criterio." ";	  
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_revisiones_metas_x_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_revisiones_metas_x_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_print_detalle_metas_x_personal($as_nroreg,$ad_fechades,$ad_fechahas,$as_codperdes,$as_codperhas)
	 {
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_metas_x_personal
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//            
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las metas de cada personal.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 26/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		if(!empty($as_codperdes))
		{
		 $ls_criterio= $ls_criterio."   AND srh_persona_revision_metas.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
		  $ls_criterio= $ls_criterio."   AND srh_persona_revision_metas.codper<='".$as_codperhas."'";
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND srh_registro_metas.fecini>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND srh_registro_metas.fecfin <='".$ad_fecreghas."'";
		}
		$ls_sql=" SELECT srh_dt_revision_metas.codmeta,srh_dt_revision_metas.codemp,srh_dt_revision_metas.valor,srh_dt_revision_metas.feceje,
		          srh_dt_revision_metas.obsmet,srh_persona_revision_metas.codper,srh_dt_registro_metas.meta".
				  " FROM  srh_dt_revision_metas,srh_registro_metas,srh_persona_revision_metas,srh_dt_registro_metas".
				  " WHERE srh_persona_revision_metas.codemp='".$this->ls_codemp."' AND srh_registro_metas.nroreg=srh_persona_revision_metas.nroreg AND ".
	              " srh_registro_metas.nroreg=".$as_nroreg." AND".			 
				  " srh_persona_revision_metas.nroreg=".$as_nroreg." AND".
				  " srh_dt_registro_metas.nroreg=".$as_nroreg." AND".
				  " srh_persona_revision_metas.tipo='P' AND".
				  " srh_dt_revision_metas.codmeta=srh_dt_registro_metas.codmeta AND".
				  " srh_dt_revision_metas.nroreg=srh_dt_registro_metas.nroreg ".
				  "   ".$ls_criterio." ";
		//print $ls_sql;  
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_revisiones_metas_x_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_print_detalle_metas_x_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_metas($as_nroreg)
	{
		$lb_valido=true;
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
				
		 $ls_sql=" SELECT srh_dt_registro_metas.nroreg,srh_dt_registro_metas.codmeta,srh_dt_registro_metas.meta,srh_dt_registro_metas.estado_meta ".
              " FROM   srh_dt_registro_metas, srh_registro_metas ".
              " WHERE  srh_dt_registro_metas.codemp='".$this->ls_codemp."' ".
			  " AND srh_registro_metas.nroreg= ".$as_nroreg."  ".
			  " AND  srh_registro_metas.nroreg=srh_dt_registro_metas.nroreg   ".
			  " ORDER BY codmeta ";		
				
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_metas ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_metas
//-------------------------------------------------------------------------------------------------------------------------------------//
function uf_select_personal($as_codperdes,$as_codperhas,$ad_fecregdes,$ad_fecreghas,$as_orden)
{
       
	   $lb_valido=true;
	   $ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND srh_registro_metas.codper>='".$as_codperdes."'";
		
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND srh_registro_metas.codper<='".$as_codperhas."'";
			
		}
		if(!empty($ad_fecregdes))
		{
			$ad_fecregdes=$this->io_funciones->uf_convertirdatetobd($ad_fecregdes);
			$ls_criterio=$ls_criterio. "  AND srh_registro_metas.fecreg>='".$ad_fecregdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			$ad_fecreghas=$this->io_funciones->uf_convertirdatetobd($ad_fecreghas);
			$ls_criterio=$ls_criterio. "  AND srh_registro_metas.fecreg<='".$ad_fecreghas."'";
		}

		switch($as_orden)
		{
			case "1": // Ordena por numero de registro
				$ls_orden="srh_registro_metas.nroreg ";
				break;

			case "2": // Ordena por fecha
				$ls_orden="srh_registro_metas.fecini ";
				break;
           case "3": // Ordena por nombre
				$ls_orden="nombre ";
				break;
		}
	switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}

      $ls_sql=" SELECT srh_registro_metas.nroreg,srh_registro_metas.codper,(SELECT ".$ls_cadena." FROM sno_personal WHERE  srh_registro_metas.codper=sno_personal.codper) as nombre,fecini,fecfin ".
              " FROM srh_registro_metas ".
              "WHERE  srh_registro_metas.codemp='".$this->ls_codemp."' ".
			  " ".$ls_criterio." ".
			  " ORDER BY ".$ls_orden." "; 
			  
			  
			 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 

}  //fin de select_personal
//---------------------------------------------------------------------------------------------------------------------------------//

function uf_select_aspirante($as_curdes,$as_curhas,$as_orden)
{
       
	   $lb_valido=true;
	   $ls_criterio="";
		
		if(!empty($ad_fecregdes))
		{
			
			$ls_criterio=$ls_criterio. "  AND srh_requisitos_minimos.codcon>='".$as_curdes."'";
		}
		if(!empty($ad_fecreghas))
		{
			
			$ls_criterio=$ls_criterio. "  AND srh_requisitos_minimos.codcon<='".$as_curhas."'";
		}

		switch($as_orden)
		{
			case "1": // Ordena por numero de evaluación
				$ls_orden="srh_requisitos_minimos.tipo_eval ";
				break;

			case "2": // Ordena por fecha
				$ls_orden="srh_requisitos_minimos.fecha ";
				break;
           case "3": // Ordena por nombre
				$ls_orden="nombre ";
				break;
			case "4": // Ordena por nombre
				$ls_orden="srh_requisitos_minimos.codcon ";
				break;	
		}
	switch ($_SESSION["ls_gestor"])
	{
		case "MYSQLT":
			$ls_cadena="CONCAT(srh_concursante.nomper,' ',srh_concursante.apeper)";
		break;
		case "POSTGRES":
			$ls_cadena="srh_concursante.nomper||' '||srh_concursante.apeper";
		break;
	
	}
		
	

		  
			$ls_sql="  SELECT srh_requisitos_minimos.tipo_eval,srh_requisitos_minimos.codper, ".
			        " (SELECT ".$ls_cadena." FROM srh_concursante ".
					" WHERE  srh_requisitos_minimos.codper=srh_concursante.codper) as nombre1, ".
              		"	srh_concurso.codcon, fecha,punreqmin ".
			        " FROM srh_requisitos_minimos, srh_concurso ".
                     " WHERE  srh_requisitos_minimos.codemp='".$this->ls_codemp."' ".
					 " and srh_concurso.codcon=srh_requisitos_minimos.codcon ".
					 " ".$ls_criterio." ".                    
				     " ORDER BY ".$as_orden." ";
					 			  
			 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_aspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 

}  //fin de select_aspirante

function uf_select_requisitos($as_tipo_eval,$as_codcon,$as_codper,$ad_fecha)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_revisiones
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo de Personal Desde
		//                 as_codperhas  // Codigo de Personal Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 ai_orden         // Orden de los Datos en el Reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las revisiones odi del personal
		//	   Creado Por: Ing.Gusmary Balza
		// Fecha Creación: 23/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		 $ls_sql=" SELECT srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.valormax, srh_dt_requisitos_minimos.puntos ".
              "   FROM  srh_items_evaluacion, srh_dt_requisitos_minimos, srh_concurso  ".
              " WHERE  srh_items_evaluacion.codemp='".$this->ls_codemp."' ".
			  " AND srh_items_evaluacion.codeval='".$as_tipo_eval."'  ".
			  " AND srh_concurso.codcon= '".$as_codcon."'  ".
			  " AND srh_dt_requisitos_minimos.codper= '".$as_codper."' ".
			  " AND srh_dt_requisitos_minimos.codite=srh_items_evaluacion.codite  ".
			  " AND Srh_dt_requisitos_minimos.fecha= '".$ad_fecha."' ".
			  " ORDER BY codite ";		
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_requisitos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_requisitos

//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_select_resultadosxaspirante($as_tipo_eval,$as_codper,$as_codcon,$as_fecha)
{
   $lb_valido=true; 
   $ls_cadena="";
   
   switch ($_SESSION["ls_gestor"])
	{
		case "MYSQLT":
			$ls_cadena="CONCAT(srh_concursante.nomper,' ',srh_concursante.apeper)";
			break;

		case "POSTGRES":
			$ls_cadena="srh_concursante.nomper||' '||srh_concursante.apeper";
			break;			
	}
	 $as_fecha=$this->io_funciones->uf_convertirdatetobd($as_fecha);
	 
	$ls_sql="  SELECT srh_requisitos_minimos.tipo_eval,srh_requisitos_minimos.codper, ".
			"  (".$ls_cadena.") as nombre, srh_concurso.codcon, fecha,punreqmin       ".
			"    FROM srh_requisitos_minimos										  ".
			"	INNER JOIN srh_concurso ON (srh_requisitos_minimos.codcon = srh_concurso.codcon) ".
			"	LEFT JOIN srh_concursante ON (srh_concursante.codper = srh_requisitos_minimos.codper) ".  
			"	INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_requisitos_minimos.tipo_eval) ".
			" WHERE srh_requisitos_minimos.codemp='".$this->ls_codemp."' ".
			"   and trim(srh_requisitos_minimos.codper)= '".trim($as_codper)."'  ".
			"   AND srh_requisitos_minimos.fecha= '".$as_fecha."' ".
			"   AND srh_concurso.codcon= '".$as_codcon."'  ".				  
			" ORDER BY srh_requisitos_minimos.tipo_eval"; 
			
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_resultadosxaspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
		}
		else
		{
			$lb_valido=false;
		}
		$this->io_sql->free_result($rs_data);
	}	
	

return $lb_valido; 
}

	//----------------------------------------------------------------------------------------------------------------------------------------
	function uf_personal_eval_psicologico($as_concurdes,$as_concurhas,$as_orden)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_personal_eval_psicologico
		//         Access: public  
		//	    Arguments: as_concurerdes  // Codigo del concurso Desde
		//                 as_concurerhas  // Codigo del concursoHasta
		//                 as_fechades     // Fecha de Registro Desde
		//                 as_fechahas     // Fecha de Registro Hasta
		//                 as_orden         // Orden de los Datos en el Reporte
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones  del personal
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 28/02/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	  $ls_cadena="";
	  $ls_orden="";	
	  $ls_valido=true;	  
	   
	  if (($as_concurdes!="")&&($as_concurhas!=""))
	    {
		  $ls_cadena= " and a.codcon between '".$as_concurdes."' and '".$as_concurhas."'";		 
		}
	  
		
	  if ($as_orden==1)
	    {
		  $ls_orden=" order by a.codcon";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden=" order by a.codper";
		}
	  if ($as_orden==3)
	    {
		  $ls_orden=" order by nomper";
		}
	   
	

	 if ($_SESSION["ls_gestor"]=="MYSQLT")
	 {
	  $ls_sql="select a.codemp, a.codper, a.codcon, a.fecha, a.tipo_eval, a.punevapsi, ".
	          "(select concat(srh_concursante.nomper,' ',srh_concursante.apeper) ".
			  " from srh_concursante WHERE a.codper=srh_concursante.codper) as nombre1 
				from srh_evaluacion_psicologica a
				join srh_concurso b on (a.codcon=b.codcon) 
				where a.codemp='".$this->ls_codemp."' ".$ls_cadena.$ls_orden;					
			 
	 }
	 else 
	 {
	 	 $ls_sql="select a.codemp, a.codper, a.codcon, a.fecha, a.tipo_eval, a.punevapsi, ".
		         "(select srh_concursante.nomper||' '||srh_concursante.apeper from srh_concursante ".
				 " WHERE a.codper=srh_concursante.codper) as nombre1				 
				from srh_evaluacion_psicologica a
				join srh_concurso b on (a.codcon=b.codcon) 
				where a.codemp='".$this->ls_codemp."' ".$ls_cadena.$ls_orden;
				
				 	
	 }
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_personal_eval_psicologico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_per_psi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}
//-------------------------------------------------------------------------------------------------------------------------------	
//----------------------------------------------------------------------------------------------------------------------------------------
	function uf_items_eval_psicologico($as_codper,$as_fecha,$as_tipo_eval)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_items_eval_psicologico
		//         Access: public  
		//	    Arguments: as_codper  // Codigo del personal
		//                 $as_fecha  // fecha de registros del iten a evaluar
		//                 $as_tipo_eval     // tipo de evaluación				                 
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones  del personal
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 28/02/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;	 	
	 	 
	 	$ls_sql="select a.codemp, a.codper, a.codite, a.fecha, a.puntos,
       				d.denite
				from srh_dt_evaluacion_psicologica a
				join srh_evaluacion_psicologica b on (a.codper=b.codper)
				join srh_items_evaluacion d on (a.codite=d.codite)
				where a.codemp='".$this->ls_codemp."'
				and a.codper='".$as_codper."'
				and d.codeval='".$as_tipo_eval."'
				and a.fecha='".$as_fecha."'";	 	
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_personal_eval_psicologico ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_item_psi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}
//-------------------------------------------------------------------------------------------------------------------------------	



//-----------------------------------------------------------------------------------------------------------------------------------//


function uf_select_aspirante_entrevista($as_curdes,$as_curhas,$as_orden)
{
       
	   $lb_valido=true;
	   $ls_criterio="";
		
		
		
		switch($as_orden)
		{
			case "1": // Ordena por numero de evaluación
				$ls_orden="srh_entrevista_tecnica.tipo_eval ";
				break;

			case "2": // Ordena por fecha
				$ls_orden="srh_entrevista_tecnica.fecha ";
				break;
           case "3": // Ordena por nombre
				$ls_orden="nombre ";
				break;
			case "4": // Ordena por nombre
				$ls_orden="srh_entrevista_tecnica.codcon ";
				break;	
		}
	switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(srh_concursante.nomper,' ',srh_concursante.apeper)";
				
				break;
			case "POSTGRES":
				$ls_cadena="srh_concursante.nomper||' '||srh_concursante.apeper";
				
				break;
			
		}

		  
			$ls_sql="  SELECT srh_entrevista_tecnica.tipo_eval,srh_entrevista_tecnica.codper, ".
			        " (SELECT ".$ls_cadena." FROM srh_concursante WHERE  srh_entrevista_tecnica.codper=srh_concursante.codper) ".
					" as nombre1,descon, fecha,punenttec ".
			        " FROM srh_entrevista_tecnica, srh_concurso ".
                     " WHERE  srh_entrevista_tecnica.codemp='".$this->ls_codemp."' ".
					 " AND srh_concurso.codcon=srh_entrevista_tecnica.codcon ".
					 " AND srh_entrevista_tecnica.codcon >='".$as_curdes."'  ".
					 " AND srh_entrevista_tecnica.codcon<='".$as_curhas."' ".                    
				     " ORDER BY ".$ls_orden." ";
		
					 			  
			 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_aspirante_entrevista ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 

}  //fin de select_aspirante



function uf_select_entrevista_tecnica($as_tipo_eval,$as_descon,$as_codper,$ad_fecha)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_revisiones
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo de Personal Desde
		//                 as_codperhas  // Codigo de Personal Hasta
		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 ai_orden         // Orden de los Datos en el Reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las revisiones odi del personal
		//	   Creado Por: Ing.Gusmary Balza
		// Fecha Creación: 23/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql=" SELECT srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.valormax, srh_dt_entrevista_tecnica.puntos ".
              "   FROM  srh_items_evaluacion, srh_dt_entrevista_tecnica, srh_concurso  ".
              " WHERE  srh_items_evaluacion.codemp='".$this->ls_codemp."' ".
			  " AND srh_items_evaluacion.codeval='".$as_tipo_eval."'  ".
			  " AND srh_concurso.descon= '".$as_descon."'  ".
			  " AND srh_dt_entrevista_tecnica.codper= '".$as_codper."' ".
			  " AND srh_dt_entrevista_tecnica.codite=srh_items_evaluacion.codite  ".
			  " AND Srh_dt_entrevista_tecnica.fecha= '".$ad_fecha."' ".
			  " ORDER BY codite ";		
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_entrevista_tecnica ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_requisitos
//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_select_solicitudes_empleo($ad_fechades,$ad_fechahas,$as_nrosoldes,$as_nrosolhas,$as_orden,$as_sexo)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_solicitudes_empleo
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_nrosoldes  // numero de la solicitud Desde
		//                 as_nrosolhas    // numero de la solicitud Hasta
		//                 as_orden    //  Orden de selección    
		//                 as_sexo  //  selección del sexo  
		//
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las solicitudes de empleo.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 28/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		
		if ($ad_fechades=="")
		{
		  $ad_fechades='1900-01-01';
		}
		
		if ($ad_fechahas=="")
		{
		  $ad_fechahas='2108-01-01';
		}
		
		if ($as_nrosoldes=="")
		{
		  $as_nrosoldes='0000000001';
		}
		if ($as_nrosolhas=="")
		{
		  $as_nrosolhas='9999999999';
		}
				
		switch($as_orden)
		{
		case "1": // Ordena por número de solicitud
		
		$ls_orden="srh_solicitud_empleo.nrosol ";
		break;
		
		case "2": // Ordena por fecha de solicitud
		
		$ls_orden="srh_solicitud_empleo.fecsol";
		break;
		case "3": // Ordena por apellido del solicitante
		
		$ls_orden="srh_solicitud_empleo.apesol";
		break;
		case "4": // Ordena por nombre del solicitante
		
		$ls_orden="srh_solicitud_empleo. nomsol ";
		break;

		}

       $ls_cadena="";
	    switch($as_sexo)
		{
		case "": 
		$ls_cadena=$ls_cadena."AND";
		break;

	  $ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);
	  $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);
	  $ls_sql=" SELECT srh_solicitud_empleo.nrosol,srh_solicitud_empleo.codemp,srh_solicitud_empleo.cedsol,srh_solicitud_empleo.fecsol,srh_solicitud_empleo.apesol,
	          srh_solicitud_empleo.nomsol,srh_solicitud_empleo.sexsol,srh_solicitud_empleo.email,srh_solicitud_empleo.telmov,
			  srh_solicitud_empleo.dirsol,srh_solicitud_empleo.comsol, sno_profesion.despro  ".
              " FROM srh_solicitud_empleo, sno_profesion".
              " WHERE srh_solicitud_empleo.codemp='".$this->ls_codemp."' AND ".
			  " srh_solicitud_empleo.nrosol between '".$as_nrosoldes."' AND '".$as_nrosolhas."' ".
			 " ".$ls_cadena." ".
			  " srh_solicitud_empleo.fecsol between '".$ad_fechades."' AND '".$ad_fechahas."' ".
              " AND sno_profesion.codpro = srh_solicitud_empleo.codpro ".
			  " ORDER BY ".$ls_orden." ";	
			  
			 
		
		case "F":
		
		$ls_cadena= $ls_cadena." And srh_solicitud_empleo.sexsol= '".$as_sexo."' AND";
		break;
		case "M": 
		$ls_cadena= $ls_cadena." And srh_solicitud_empleo.sexsol= '".$as_sexo."' AND";
		break;
		}

     $ad_fechades=$this->io_funciones->uf_convertirdatetobd($ad_fechades);
	 $ad_fechahas=$this->io_funciones->uf_convertirdatetobd($ad_fechahas);
     $ls_sql=" SELECT srh_solicitud_empleo.nrosol,srh_solicitud_empleo.codemp,srh_solicitud_empleo.cedsol,srh_solicitud_empleo.fecsol,srh_solicitud_empleo.apesol,
	          srh_solicitud_empleo.nomsol,srh_solicitud_empleo.sexsol,srh_solicitud_empleo.email,srh_solicitud_empleo.telmov,
			  srh_solicitud_empleo.dirsol,srh_solicitud_empleo.comsol, sno_profesion.despro   ".
              " FROM srh_solicitud_empleo, sno_profesion".
              " WHERE srh_solicitud_empleo.codemp='".$this->ls_codemp."' AND ".
			  " srh_solicitud_empleo.nrosol between '".$as_nrosoldes."' AND '".$as_nrosolhas."' ".
			 " ".$ls_cadena." ".
			  " srh_solicitud_empleo.fecsol between '".$ad_fechades."' AND '".$ad_fechahas."' ".
			  " AND sno_profesion.codpro = srh_solicitud_empleo.codpro ".
              " ORDER BY ".$ls_orden." ";	
			  
	  
   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_solicitudes_empleo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_solicitudes_empleo
	//-----------------------------------------------------------------------------------------------------------------------------------	
  
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
function uf_select_personal_bonos($as_codper,$ld_fecha)
	{
		
		$lb_valido=true;
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
	 $ld_fecha=$this->io_funciones->uf_convertirdatetobd($ld_fecha);
	  $ls_sql=" SELECT srh_bono_merito.codper,(SELECT ".$ls_cadena." FROM sno_personal WHERE  srh_bono_merito.codper=sno_personal.codper) as nombre,fecha,total ".
              "   FROM srh_bono_merito  ".
              " WHERE  srh_bono_merito.codemp='".$this->ls_codemp."' ".
			  " AND srh_bono_merito.codper= '".$as_codper."' ".
		      " AND srh_bono_merito.fecha= '".$ld_fecha."' ".
			  " ORDER BY codper ";	
			
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_personal_bonos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{

			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_personal_bonos
	
//-------------------------------------------------------------------------------------------------------------------------	
	
function uf_select_bonos($as_codper,$ld_fecha)
	{
		
		$lb_valido=true;
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
	 $ld_fecha=$this->io_funciones->uf_convertirdatetobd($ld_fecha);
	  $ls_sql=" SELECT srh_dt_bono_merito.codper,srh_dt_bono_merito.codpunt,nompunt,valini,valfin, puntos,observacion ".
              "   FROM srh_dt_bono_merito, srh_bono_merito,srh_puntuacion_bono_merito  ".
              " WHERE  srh_dt_bono_merito.codemp='".$this->ls_codemp."' ".
			  " AND srh_dt_bono_merito.codper= '".$as_codper."' ".
		      " AND srh_bono_merito.fecha= '".$ld_fecha."' ".
			  " AND srh_dt_bono_merito.codpunt=srh_puntuacion_bono_merito.codpunt ".
			  " ORDER BY codper ";	
			
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_bonos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_bonos
	
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_select_aspirante_total($as_codper,$as_codcon,$as_fecha)
{
      $lb_valido=true;
	   
       switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(srh_concursante.nomper,' ',srh_concursante.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="srh_concursante.nomper||' '||srh_concursante.apeper";
				break;			
		}
			 $as_fecha=$this->io_funciones->uf_convertirdatetobd($as_fecha);
				$ls_sql="  SELECT srh_resultados_evaluacion_aspirante.codper, srh_resultados_evaluacion_aspirante.fecreg, ".
                    "  (".$ls_cadena.") as nombre, srh_concurso.codcon      ".
                    "    FROM srh_resultados_evaluacion_aspirante									  ".
					"	INNER JOIN srh_concurso ON (srh_resultados_evaluacion_aspirante.codcon = srh_concurso.codcon) ".
					"	LEFT JOIN srh_concursante ON ".
					"  (trim(srh_concursante.codper) = trim(srh_resultados_evaluacion_aspirante.codper)) ".  
					" WHERE  srh_resultados_evaluacion_aspirante.codemp='".$this->ls_codemp."' ".
			 		" AND srh_resultados_evaluacion_aspirante.codper= '".trim($as_codper)."' ".
			   		" AND srh_resultados_evaluacion_aspirante.codcon= '".trim($as_codcon)."' ".
		      		" AND srh_resultados_evaluacion_aspirante.fecreg= '".$as_fecha."' ".
			  		" ORDER BY codper "; 
				 
				 $rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_resultadosxaspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			else
			{
				if($row=$this->io_sql->fetch_row($rs_data))
				{
					$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
				}
				else
				{
					$lb_valido=false;
				}
				$this->io_sql->free_result($rs_data);
			}	
			
		
	  
		return $lb_valido;

	}
	// end function uf_select__aspirante_total
//-----------------------------------------------------------------------------------------------------------------------------------
	

	function uf_select_requisitosxaspirante($as_codper,$as_codcon)
	{
		
		$lb_valido=true;
		
	    $ls_sql=" SELECT srh_requisitos_minimos.codper, srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.valormax, srh_dt_requisitos_minimos.puntos ".
              "   FROM srh_dt_requisitos_minimos, srh_items_evaluacion, srh_requisitos_minimos  ".
              " WHERE  srh_dt_requisitos_minimos.codemp='".$this->ls_codemp."' ".
			  " AND trim(srh_requisitos_minimos.codper)= '".$as_codper."' ".
		      " AND srh_requisitos_minimos.codcon= '".$as_codcon."' ".
		   	  " AND trim(srh_requisitos_minimos.codper)=trim(srh_dt_requisitos_minimos.codper) ".
              " AND srh_items_evaluacion.codite=srh_dt_requisitos_minimos.codite ".
			  " ORDER BY codite ";	
					
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_requisitosxaspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_requisitosxaspirante
//---------------------------------------------------------------------------------------------------------------------------
function uf_select_evalpsicologicaxaspirante($as_codper,$as_codcon)
	{
		
		$lb_valido=true;
		
		
	  $ls_sql=" SELECT srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.valormax, srh_dt_evaluacion_psicologica.puntos  ".
              "  FROM srh_dt_evaluacion_psicologica,srh_items_evaluacion,srh_evaluacion_psicologica  ".
              " WHERE  srh_dt_evaluacion_psicologica.codemp='".$this->ls_codemp."' ".
			  " AND trim(srh_evaluacion_psicologica.codper)= '".$as_codper."' ".
		      " AND srh_evaluacion_psicologica.codcon= '".$as_codcon."' ".
		 	  " AND trim(srh_evaluacion_psicologica.codper)=trim(srh_dt_evaluacion_psicologica.codper) ".
              " AND srh_items_evaluacion.codite=srh_dt_evaluacion_psicologica.codite ".
			  " ORDER BY codite ";	
			 	
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_evalpsicologicaxaspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_item_psi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_evalpsicologicaxaspirante
//-------------------------------------------------------------------------------------------------------------------------

function uf_select_entrevistasxaspirante($as_codper,$as_codcon)
	{
		
		$lb_valido=true;
		
	  $ls_sql=" SELECT srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.valormax, srh_dt_entrevista_tecnica.puntos ".
              "  FROM  srh_dt_entrevista_tecnica,srh_items_evaluacion,srh_entrevista_tecnica  ".
              " WHERE srh_dt_entrevista_tecnica.codemp='".$this->ls_codemp."' ".
			  " AND trim(srh_entrevista_tecnica.codper)= '".$as_codper."' ".
		      " AND srh_entrevista_tecnica.codcon= '".$as_codcon."' ".
		 	  " AND trim(srh_entrevista_tecnica.codper)=trim(srh_dt_entrevista_tecnica.codper) ".
              " AND srh_items_evaluacion.codite=srh_dt_entrevista_tecnica.codite ".
			  " ORDER BY codite ";	
			
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_entrevistasxaspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_item_ent->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end function uf_select_entrevistasxaspirante
//----------------------------------------------------------------------------------------------------------------------------------//

function uf_select_personal_listado($as_codperdes,$as_codperhas,$as_orden)
{
       
	   $lb_valido=true;
	   $ls_criterio="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personal.codper>='".$as_codperdes."'";
		
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personal.codper<='".$as_codperhas."'";
			
		}
		
		switch($as_orden)
		{
			case "1": // Ordena por codigo del personal
				$ls_orden="sno_personal.codper ";
				break;

			case "2": // Ordena por cedula del personal
				$ls_orden="sno_personal.cedper ";
				break;
           case "3": // Ordena por nombre
				$ls_orden="sno_personal.nomper ";
				break;
		   case "4": // Ordena por apellido
				$ls_orden="sno_personal.apeper ";
				break;	
		}

      $ls_sql=" SELECT sno_personal.codper,cedper,nomper,apeper, sno_cargo.descar,  sno_asignacioncargo.denasicar ".
	  		  " FROM sno_personal ".
			  " JOIN sno_personalnomina ON (sno_personalnomina.codper=sno_personal.codper)  " .
			  " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar AND   ".
			  "                               sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
			  " LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar AND ".
			  " sno_personalnomina.codnom=sno_cargo.codnom) ".	   
			  " JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
              " WHERE  sno_personal.codemp='".$this->ls_codemp."' ".
			  " ".$ls_criterio." ".
			  " ORDER BY ".$ls_orden." "; 

			  
			 $rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_personal_listado ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido; 

}  //fin de select_personal_listado
//-----------------------------------------------------------------------------------------------------------------------------------	
  function uf_buscar_evaluados($as_codigoeva,$as_codigoper,$as_codigocon,$as_fecha,&$rs_data)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_evaluados
		//         Access: public  
		//	    Arguments: as_codigoper    // Código de la evaluacion
		//                 as_fecha   // Fecha de la evaluación
		//                 as_codigoeva // Código de la evaluación
		//                 as_codigocon  // Código del concurso
		//                 as_fecha		//  Fecha del concurso
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas evaluadas psicológicamente.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 29/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
	    $as_fecha=$this->io_funciones->uf_convertirdatetobd($as_fecha);
		  
		$ls_sql=" SELECT srh_dt_evaluacion_psicologica.*,srh_concurso.codcon,srh_evaluacion_psicologica.punevapsi,
		      srh_items_evaluacion.codite,srh_items_evaluacion.valormax,srh_items_evaluacion.denite,srh_items_evaluacion.codeval ".
				" FROM srh_dt_evaluacion_psicologica,srh_concurso,srh_evaluacion_psicologica,srh_items_evaluacion".
				 " WHERE  srh_dt_evaluacion_psicologica.codemp='".$this->ls_codemp."' ".
				 " AND srh_dt_evaluacion_psicologica.codper ='".$as_codigoper."' ".
				 " AND srh_evaluacion_psicologica.codcon= '".$as_codigocon."'". 
				 " AND srh_dt_evaluacion_psicologica.fecha ='".$as_fecha."' ".
				 " AND srh_evaluacion_psicologica.tipo_eval ='".$as_codigoeva."' ".
				 " AND srh_evaluacion_psicologica.codper=srh_dt_evaluacion_psicologica.codper".
				 " AND srh_items_evaluacion.codite=srh_dt_evaluacion_psicologica.codite".
                 " AND srh_items_evaluacion.codeval= '".$as_codigoeva."'".
				 " AND srh_concurso.codcon=srh_evaluacion_psicologica.codcon ". 
				 " ORDER BY srh_evaluacion_psicologica.codper ";                 
        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_evaluados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
				
		return $lb_valido;  
	}// end uf_buscar_evaluados
	//-----------------------------------------------------------------------------------------------------------------------------------	
function uf_select_aspirante_registro_entrevista($as_tipo_eval,$as_codcon,$as_codper)
{
       
	 $lb_valido=true;
	 $ls_tipoper="";
      
	 switch ($_SESSION["ls_gestor"])
	 {
		case "MYSQLT":
			$ls_cadena="CONCAT(srh_concursante.nomper,' ',srh_concursante.apeper)";
			break;
		case "POSTGRES":
			$ls_cadena="srh_concursante.nomper||' '||srh_concursante.apeper";
			break;			
	 }

	$ls_sql= " SELECT srh_entrevista_tecnica.tipo_eval, srh_entrevista_tecnica.codper, ".
			 " srh_entrevista_tecnica.fecha, srh_entrevista_tecnica.punenttec, srh_concurso.descon, ".
			 " (SELECT ".$ls_cadena." FROM srh_concursante WHERE  srh_entrevista_tecnica.codper =  	 
				srh_concursante.codper) as nombre ".
			 " FROM  srh_entrevista_tecnica								  ".
			 " INNER JOIN srh_concurso ON (srh_entrevista_tecnica.codcon = srh_concurso.codcon) ".
			 " LEFT JOIN srh_concursante ON (srh_concursante.codper = srh_entrevista_tecnica.codper) ".  
			 " INNER JOIN srh_tipoevaluacion ON  (srh_tipoevaluacion.codeval = srh_entrevista_tecnica.tipo_eval) ".
			 " WHERE  srh_entrevista_tecnica.codemp='".$this->ls_codemp."' ".
			 " AND   srh_entrevista_tecnica.codper='".$as_codper."' ".
			 " AND   srh_entrevista_tecnica.codcon='".$as_codcon."' ".
			 " AND  srh_entrevista_tecnica.tipo_eval='".$as_tipo_eval."' ".
			 " AND srh_concurso.codcon=srh_entrevista_tecnica.codcon ".					          
			 " ORDER BY srh_entrevista_tecnica.codper ";
			 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_resultadosxaspirante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}	
		
	
		return $lb_valido; 

}  //fin de select_registro_aspirante

function uf_select_registro_entrevista_tecnica($as_tipo_eval,$as_codcon,$as_codper,$ad_fecha)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_revisiones
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo de Personal Desde
		//                 as_codperhas  // Codigo de Personal Hasta

		//                 ad_fecregdes     // Fecha de Registro Desde
		//                 ad_fecreghas     // Fecha de Registro Hasta
		//                 ai_orden         // Orden de los Datos en el Reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de la entrevits técnica realizada a un personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 29/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ad_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
		
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		 $ls_sql=" SELECT srh_items_evaluacion.codite,srh_items_evaluacion.denite,srh_items_evaluacion.valormax,srh_dt_entrevista_tecnica.puntos ".
              "   FROM  srh_items_evaluacion, srh_dt_entrevista_tecnica, srh_concurso  ".
              " WHERE  srh_items_evaluacion.codemp='".$this->ls_codemp."' ".
			  " AND srh_items_evaluacion.codeval='".$as_tipo_eval."'  ".
			  " AND srh_concurso.codcon= '".$as_codcon."'  ".
			  " AND srh_dt_entrevista_tecnica.codper= '".$as_codper."' ".
			  " AND srh_dt_entrevista_tecnica.codite=srh_items_evaluacion.codite  ".
			  " AND Srh_dt_entrevista_tecnica.fecha= '".$ad_fecha."' ".
			  " ORDER BY codite ";		
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_registro_entrevista_tecnica ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}
	// end uf_select_registro_entrevista_tecnica	

//----------------------------------------------------------------------------------------------------------------------------------------
	function uf_listado_ascenso($as_codperdes,$as_codperhas,$as_fechades,$as_fechahas, $as_orden)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_items_eval_psicologico
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo del personal desde
		//                 $as_codperhas //  Codigo del personal hasta
		//                 $as_fechades  // fecha desde
		//				   $as_fechahas	 // fecha hasta	                 
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los ascenso  del personal
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 03/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;	
	    $ls_orden="";
	    $ls_cadena="";	
	 	$as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);	   
	    $as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas); 
	     
	  if (($as_fechades!="")&&($as_fechahas!=""))
	    {
		  $ls_cadena= " and a.fecreg between '".$as_fechades."' and '".$as_fechahas."'";
		}
	  if (($as_codperdes!="")&&($as_codperhas!=""))
	    {
		  $ls_cadena=$ls_cadena." and d.codper between '".$as_codperdes."' and  '".$as_codperhas."' ";
		}
		
	  if ($as_orden==1)
	    {
		  $ls_orden=" order by a.nroreg";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden=" order by a.fecreg";
		}
	  if ($as_orden==3)
	    {
		  $ls_orden=" order by d.codper";
		}
	   if ($as_orden==4)
	    {
		  $ls_orden=" order by e.nomper";
		}
	   if ($as_orden==5)
	    {
		  $ls_orden=" order by e.apeper";
		}
	 	
		$ls_sql="select distinct(a.nroreg), a.codemp, a.fecreg, a.codcon, a.opinion, a.observacion, b.reseval,b.fecha,  ".
				" d.codper,e.cedper, e.nomper, e.apeper, i.codcar, h.descar, j.denasicar, ".
				" (select sno_cargo.descar from sno_cargo where sno_cargo.codcar=i.codcar and sno_cargo.codnom=i.codnom) ".
				" as caract1, ".
				" (select sno_asignacioncargo.denasicar from sno_asignacioncargo where sno_asignacioncargo.codasicar=i.codcar ". 
				" and sno_asignacioncargo.codnom=i.codnom) as caract2 ".
				" from srh_registro_ascenso a join srh_evaluacion_ascenso b on (a.nroreg=b.nroreg) ".
				" join srh_persona_registro_ascenso d on (a.nroreg=d.nroreg)".
				" join sno_personal e on (d.codper=e.codper) join srh_concurso f on (a.codcon=f.codcon) ".
				" left join sno_cargo h on (f.codcar=h.codcar and f.codnom=h.codnom) ".
				" left join sno_asignacioncargo j on (f.codcar=j.codasicar and f.codnom=j.codnom) ".
				" left join sno_personalnomina i on (d.codper=i.codper) ".
				" JOIN sno_nomina ON (sno_nomina.codnom = i.codnom AND sno_nomina.espnom='0') ".
				" where a.codemp='".$this->ls_codemp."' ".
  				" and d.tipo='P' ".$ls_cadena.$ls_orden; 
	
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_ascenso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_ascenso->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}

//-------------------------------------------------------------------------------------------------------------------------------
	function uf_items_eval_ascenso($as_codasc)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_items_eval_ascenso
		//         Access: public  
		//	    Arguments: $as_codasc  // Codigo del ascenso                 			                 
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones  del ascenso a un personal
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 04/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;   	
	 	 
	 	$ls_sql="select a.codemp, a.nroreg, a.codite, a.fecha, a.puntos,
       					b.denite
				 from srh_dt_evaluacion_ascenso a
				 left join srh_items_evaluacion b on (a.codite=b.codite)
				 where a.codemp='".$this->ls_codemp."'
				 and a.nroreg='".$as_codasc."'";	  	
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_items_eval_ascenso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_item_asc->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}	
//-------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------	
 function uf_select_listado_accidentes($as_fechades,$as_fechahas,$as_codperdes,$as_codperhas,$as_orden)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_listado_accidentes
		//         Access: public  
		//	    Arguments: as_fechades   // Fecha de selección desde
		//                 as_fechahas  // Fecha de selección hasta
		//                 as_codperdes // Código del personal desde
		//                 as_codperhas  // Código del personal hasta
		//                 as_orden	//  orden de selección
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas evaluadas psicológicamente.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 04/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
	  	if(!empty($as_codperdes))
		{
		 $ls_criterio= $ls_criterio."   AND srh_accidentes.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
		  $ls_criterio= $ls_criterio."   AND srh_accidentes.codper<='".$as_codperhas."'";
		}
		if(!empty($as_fechades))
		{
			$as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);
			$ls_criterio=$ls_criterio. "  AND srh_accidentes.fecacc>='".$as_fechades."'";
		}
		if(!empty($as_fechahas))
		{
			$as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas);
			$ls_criterio=$ls_criterio. "  AND srh_accidentes.fecacc<='".$as_fechahas."'";
		}


		switch($as_orden)
		{
		case "1": // Ordena por número de registro
		
		$ls_orden="srh_accidentes.nroreg";
		break;
		
		case "2": // Ordena por código del personal
		
		$ls_orden="srh_accidentes.codper";
		break;
		case "3": // Ordena por apellido del solicitante
		
		$ls_orden="sno_personal.apeper";
		break;
		case "4": // Ordena por nombre del solicitante
		
		$ls_orden="sno_personal.nomper";
		break;

		}
		
		$ls_sql=" SELECT srh_accidentes.*,sno_personal.nomper,sno_personal.apeper".
				" FROM srh_accidentes,sno_personal".
				 " WHERE  srh_accidentes.codemp='".$this->ls_codemp."' ".
				 " AND  srh_accidentes.codper=sno_personal.codper".
				 " ".$ls_criterio." ". 
				 " ORDER BY ".$ls_orden." ";                 

        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_listado_accidentes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;  
	}// end uf_select_listado_accidentes
	
//-----------------------------------------------------------------------------------------------------------------------------------	
  function uf_select_listado_enfermedades($as_fechades,$as_fechahas,$as_codperdes,$as_codperhas,$as_orden)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_listado_enfermedades
		//         Access: public  
		//	    Arguments: as_fechades   // Fecha de selección desde
		//                 as_fechahas  // Fecha de selección hasta
		//                 as_codperdes // Código del personal desde
		//                 as_codperhas  // Código del personal hasta
		//                 as_orden	//  orden de selección
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas con enfermedades.
		//	   Creado Por: Ing. Gloriely Fréitez.
		// Fecha Creación: 04/02/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
	  	if(!empty($as_codperdes))
		{
		 $ls_criterio= $ls_criterio."   AND srh_enfermedades.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
		  $ls_criterio= $ls_criterio."   AND srh_enfermedades.codper<='".$as_codperhas."'";
		}
		if(!empty($as_fechades))
		{
			$as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);
			$ls_criterio=$ls_criterio. "  AND srh_enfermedades.fecini>='".$as_fechades."'";
		}
		if(!empty($as_fechahas))
		{
			$as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas);
			$ls_criterio=$ls_criterio. "  AND srh_enfermedades.fecini<='".$as_fechahas."'";
		}

		switch($as_orden)
		{
		case "1": // Ordena por número de registro
		
		$ls_orden="srh_enfermedades.nroreg";
		break;
		
		case "2": // Ordena por código del personal
		
		$ls_orden="srh_enfermedades.codper";
		break;
		case "3": // Ordena por apellido del solicitante
		
		$ls_orden="sno_personal.apeper";
		break;
		case "4": // Ordena por nombre del solicitante
		
		$ls_orden="sno_personal.nomper";
		break;

		}
		
		$ls_sql=" SELECT srh_enfermedades.*,sno_personal.nomper,sno_personal.apeper".
				" FROM srh_enfermedades,sno_personal".
				 " WHERE srh_enfermedades.codemp='".$this->ls_codemp."' ".
				 " AND srh_enfermedades.codper=sno_personal.codper".
				 " ".$ls_criterio." ". 
				 " ORDER BY ".$ls_orden." ";                 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_listado_enfermedades ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;  
	}// end uf_select_listado_enfermedades
   //-------------------------------------------------------------------------------------------------------------------------------		
   function uf_listado_pasantes($as_fechades1,$as_fechahas1,$as_fechades2,$as_fechahas2,$as_estatus,$as_orden)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_pasantes
		//         Access: public  
		//	    Arguments: $as_fechades1  // fecha desde
		//				   $as_fechahas1	 // fecha hasta	 
		//				   $as_fechades2 // fecha desde
		//				   $as_fechahas2	 // fecha hasta	  
		//				   $as_estatus // estatus de la pasantia
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las pasantias
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 04/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;	
	    $ls_orden="";
	    $ls_cadena="";	
		$ls_cadena2="";
		$ls_gestor = $_SESSION["ls_gestor"];
	 	$as_fechades1=$this->io_funciones->uf_convertirdatetobd($as_fechades1);	   
	    $as_fechahas1=$this->io_funciones->uf_convertirdatetobd($as_fechahas1); 
		$as_fechades2=$this->io_funciones->uf_convertirdatetobd($as_fechades2);	   
	    $as_fechahas2=$this->io_funciones->uf_convertirdatetobd($as_fechahas2); 
	     
	  if (($as_fechades1!="")&&($as_fechahas1!=""))
	    {
		  $ls_cadena= " and (a.fecini between '".$as_fechades1."' and '".$as_fechahas1."')";
		}
		
	 if (($as_fechades2!="")&&($as_fechahas2!=""))
	    {
		  $ls_cadena= " or (a.fecini between '".$as_fechades2."' and '".$as_fechahas2."')";
		}
	 
		
	  if ($as_orden==1)
	    {
		  $ls_orden=" order by a.nropas";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden=" order by a.cedpas";
		}
	  if ($as_orden==3)
	    {
		  $ls_orden=" order by a.nompas";
		}
	   if ($as_orden==4)
	    {
		  $ls_orden=" order by a.apepas";
		}
	  if ($as_estatus!="")
	   {
	    $ls_cadena2=" and a.estado='".$as_estatus."'";
	   }
	 	
		if ($ls_gestor=="MYSQLT")
		{
		  $ls_sql="select a.codemp, a.nropas, a.cedpas, a.fecini, a.fecfin, a.apepas,a.nompas,
       					a.carrera,a.inst_univ, a.tutor, a.estado,
       					(select concat(sno_personal.nomper,' ',sno_personal.apeper) 
              			 		from sno_personal 
       					 		where sno_personal.codper=a.tutor) as tutor
								from srh_pasantias a
				  left join sno_personal b on (a.tutor=b.codper)
				  where a.codemp='".$this->ls_codemp."'".$ls_cadena.$ls_cadena2.$ls_orden;
		}
		else
		{
		   $ls_sql="select a.codemp, a.nropas, a.cedpas, a.fecini, a.fecfin, a.apepas,a.nompas,
       					a.carrera,a.inst_univ, a.tutor, a.estado,
       					(select sno_personal.nomper||' '||sno_personal.apeper 
              			 		from sno_personal 
       					 		where sno_personal.codper=a.tutor) as tutor
								from srh_pasantias a
				left join sno_personal b on (a.tutor=b.codper)
				where a.codemp='".$this->ls_codemp."'".$ls_cadena.$ls_cadena2.$ls_orden;
				
				
		}
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_pasantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->detalle_pasantia->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}
	//----------------------------------------------------------------------------------------------------------------------------
	function uf_listado_adiestramiento($as_fechades,$as_fechahas,$as_codperdes,$as_codperhas,$as_orden)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_adiestramiento
		//         Access: public  
		//	    Arguments: $as_fechades  // fecha desde
		//				   $as_fechahas	 // fecha hasta	 
		//				   $as_codperdes // codigo del personal desde
		//				   $as_codperhas	 // codigo del personal hasta			
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes de las pasantias
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 05/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;	
	    $ls_orden="";
	    $ls_cadena="";	
		$ls_cadena2="";
		$ls_gestor = $_SESSION["ls_gestor"];
	 	$as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);	   
	    $as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas); 		
	     
	  if (($as_fechades!="")&&($as_fechahas!=""))
	    {
		  $ls_cadena= " and (a.fecha between '".$as_fechades."' and '".$as_fechahas."')";
		}
		
	 if (($as_codperdes!="")&&($as_codperhas!=""))
	    {
		  $ls_codigo= " and (a.codper between '".$as_codperdes."' and '".$as_codperhas."')";
		}
	else
		{
	      $ls_codigo="";
		}
		
	  if ($as_orden==1)
	    {
		  $ls_orden=" order by a.nroreg";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden=" order by a.fecha";
		} 
	 
	 	   $ls_sql="SELECT a.codemp, a.nroreg, a.fecha, a.codper,  a.codprov, a.descripcion, 
       					   a.observacion, a.fecini, a.fecfin, a.durhras, a.costo, a.estrategia, a.objetivo, a.area, 
       					   b.nompro, d.nomper,d.apeper
    					   FROM srh_solicitud_adiestramiento a
						   left join rpc_proveedor b on (a.codprov=b.cod_pro)
						   left join sno_personal d on (a.codper=d.codper)
						   where a.codemp='".$this->ls_codemp."'".$ls_cadena.$ls_codigo.$ls_orden;

	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_adiestramiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_list_adi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}
	//----------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------
	function uf_listado_personas_adiestramiento($as_adiestramiento)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_personas_adiestramiento
		//         Access: public  
		//	    Arguments: $as_adiestramiento  // codigo del adiestramiento				                  
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que asistiran al adiestramiento
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;
	    	 
	    $ls_sql="SELECT a.codemp, a.nroreg, a.codper, a.carper,a.dep,
       					b.cedper, b.nomper,b.apeper
				 FROM srh_dt_solicitud_adiestramiento a
				 left join sno_personal b on (a.codper=b.codper)
				 where a.codemp='".$this->ls_codemp."'
				 and a.nroreg='".$as_adiestramiento."'
				 order by a.codper";
		
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_personas_adiestramiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_pers_adi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}
	//----------------------------------------------------------------------------------------------------------------------------

 function uf_listado_llamadas_atencion($as_fechainides,$as_fechafinhas,$as_codperdes,$as_codperhas,$as_orden)
   {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_llamadas_atencion
		//         Access: public  
		//	    Arguments: $as_fechainides  // fecha desde
		//				   $as_fechafinhas	 // fecha hasta	 
		//				   $as_codperdes // código del personal desde
		//                 $as_codperhas // código del personal hasta
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas que tienen llamadas de atención.
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 05/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
		if(!empty($as_fechainides))
		{
			$as_fechainides=$this->io_funciones->uf_convertirdatetobd($as_fechainides);
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.fecllam>='".$as_fechainides."'";
		}
		if(!empty($as_fechafinhas))
		{
			$as_fechafinhas=$this->io_funciones->uf_convertirdatetobd($as_fechafinhas);
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.fecllam<='".$as_fechafinhas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.codtrab>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.codtrab<='".$as_codperhas."'";
		}

		switch($as_orden)
		{
		case "1": // Ordena por número de llamadas
		
		$ls_orden="srh_llamada_atencion.nrollam";
		break;
		
		case "2": // Ordena por código del personal
		
		$ls_orden="srh_llamada_atencion.codtrab";
		break;
		case "3": // Ordena por apellido del personal
		
		$ls_orden="sno_personal.apeper";
		break;
		case "4": // Ordena por nombre del personal
		
		$ls_orden="sno_personal.nomper";
		break;

		}
		
		$ls_sql=" SELECT  srh_llamada_atencion.*,sno_personal.nomper,sno_personal.apeper".
				" FROM srh_llamada_atencion,sno_personal".
				 " WHERE srh_llamada_atencion.codemp='".$this->ls_codemp."' ".
				 " AND srh_llamada_atencion.codtrab=sno_personal.codper".
				 " ".$ls_criterio." ". 
				 " ORDER BY ".$ls_orden." ";                 
				           
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_listado_llamadas_atencion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;  
	}// end uf_listado_llamadas_atencion
	//----------------------------------------------------------------------------------------------------------------------------
 function uf_listado_amonestacion($as_fechainides,$as_fechafinhas,$as_codperdes,$as_codperhas,$as_coduniadm1,$as_coduniadm2,$as_causa,$as_orden, &$rs_data)
   {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_amonestacion
		//         Access: public  
		//	    Arguments: $as_fechainides  // fecha desde
		//				   $as_fechafinhas	 // fecha hasta	 
		//				   $as_codperdes // código del personal desde
		//                 $as_codperhas // código del personal hasta
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas que tienen amonestaciones.
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
		if(!empty($as_fechainides))
		{
			$as_fechainides=$this->io_funciones->uf_convertirdatetobd($as_fechainides);
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.fecllam>='".$as_fechainides."'";
		}
		if(!empty($as_fechafinhas))
		{
			$as_fechafinhas=$this->io_funciones->uf_convertirdatetobd($as_fechafinhas);
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.fecllam<='".$as_fechafinhas."'";
		}
		if(!empty($as_codperdes))
		{
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.codtrab>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio=$ls_criterio. "  AND srh_llamada_atencion.codtrab<='".$as_codperhas."'";
		}
		if ((!empty($as_coduniadm1)) && (!empty($as_coduniadm2)))
		{
			$minorguniadm1 = substr($as_coduniadm1,0,4);
			$ofiuniadm1 = substr($as_coduniadm1,5,2);
			$uniuniadm1 = substr($as_coduniadm1,8,2);
			$depuniadm1 = substr($as_coduniadm1,11,2);
			$prouniadm1 = substr($as_coduniadm1,14,2);	
			
			$minorguniadm2 = substr($as_coduniadm2,0,4);
			$ofiuniadm2 = substr($as_coduniadm2,5,2);
			$uniuniadm2 = substr($as_coduniadm2,8,2);
			$depuniadm2 = substr($as_coduniadm2,11,2);
			$prouniadm2 = substr($as_coduniadm2,14,2);
				
			$ls_criterio=$ls_criterio." AND sno_personalnomina.minorguniadm BETWEEN '".$minorguniadm1."' AND '".$minorguniadm2."'";	
			$ls_criterio=$ls_criterio."	AND sno_personalnomina.ofiuniadm  BETWEEN   '".$ofiuniadm1."' AND '".$ofiuniadm2."' ";
			$ls_criterio=$ls_criterio."	AND sno_personalnomina.uniuniadm  BETWEEN   '".$uniuniadm1."' AND '".$uniuniadm2."' ";
			$ls_criterio=$ls_criterio."	AND sno_personalnomina.depuniadm  BETWEEN   '".$depuniadm1."' AND '".$depuniadm2."' ";
			$ls_criterio=$ls_criterio."	AND sno_personalnomina.prouniadm  BETWEEN   '".$prouniadm1."' AND '".$prouniadm2."' ";
		}
	
		switch($as_causa)
		{
			case "1": 
				$ls_criterio=$ls_criterio." AND srh_llamada_atencion.causa = '1' ";
			break;
			case "2": 
				$ls_criterio=$ls_criterio." AND srh_llamada_atencion.causa = '2'";
			break;
			
		}
		
		switch($as_orden)
		{
			case "1": // Ordena por código de personal
				$ls_orden="srh_llamada_atencion.codtrab";
			break;
			case "2": // Ordena por apellido del personal
				$ls_orden="sno_personal.apeper";
			break;
			case "3": // Ordena por nombre del personal
				$ls_orden="sno_personal.nomper";
			break;
			case "4": // Ordena por nombre del personal
				$ls_orden="sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm, sno_personalnomina.depuniadm, sno_personalnomina.prouniadm";
			break;
		}
		
		$ls_sql=" SELECT DISTINCT (sno_personalnomina.codper), srh_llamada_atencion.*,sno_personal.nomper, ".
				" sno_personal.apeper, sno_unidadadmin.desuniadm, sno_asignacioncargo.denasicar, sno_cargo.descar".
				" FROM sno_nomina, srh_llamada_atencion,sno_unidadadmin,sno_personal, sno_personalnomina ".
				" JOIN sno_asignacioncargo on (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar ".
				" AND sno_personalnomina.codnom=sno_asignacioncargo.codnom   ".
				" AND sno_personalnomina.codemp=sno_asignacioncargo.codemp)  ".
				" JOIN sno_cargo on (sno_personalnomina.codcar=sno_cargo.codcar AND ".
				" sno_personalnomina.codnom=sno_cargo.codnom  AND sno_personalnomina.codemp=sno_cargo.codemp)   ".
				 " WHERE srh_llamada_atencion.codemp='".$this->ls_codemp."' ".
				 " AND sno_personal.codemp='".$this->ls_codemp."' ".
				 " AND srh_llamada_atencion.codtrab=sno_personal.codper".
				 " AND sno_personalnomina.codemp='".$this->ls_codemp."' ".
				 " AND srh_llamada_atencion.codtrab=sno_personalnomina.codper ".
				 " AND sno_unidadadmin.codemp='".$this->ls_codemp."' ".
				 " AND sno_nomina.codemp=sno_personal.codemp     ".
				 " AND sno_nomina.codnom = sno_personalnomina.codnom ".
				 " AND sno_nomina.espnom='0'".
				 " AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
			 	 " ".$ls_criterio." ". 
				 " ORDER BY ".$ls_orden." ";                 
        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO-> uf_listado_amonestacion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
			
		return $lb_valido;  
	}// end  uf_listado_amonestacion
	
//



//----------------------------------------------------------------------------------------------------------------------------
 function uf_select_causa_llamada_atencion ($as_numllam, &$rs_data)
   {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function:  uf_select_causa_llamada_atencion
		//         Access: public  
		//	    Arguments: $as_numllam // numero de la llamada de atenciom               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las causas de la llamada de atencion
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
	    $ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
			
		$ls_sql=" SELECT dencaullam_aten ".
				" FROM srh_causa_llamada_atencion, srh_dt_llamada_atencion ".				
				 " WHERE srh_dt_llamada_atencion.codemp='".$this->ls_codemp."' ".
				 " AND srh_dt_llamada_atencion.nrollam='".$as_numllam."' ".
				 " AND srh_dt_llamada_atencion.codcaullam_aten=srh_causa_llamada_atencion.codcaullam_aten";                 
        
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->  uf_select_causa_llamada_atencion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
			
		return $lb_valido;  
	}// end  uf_listado_amonestacion





//
	//----------------------------------------------------------------------------------------------------------------------------
 function uf_buscar_datos_pasantes($as_fechainides,$as_fechafinhas,$as_estatus,$as_orden)
   {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_buscar_datos_pasantes
		//         Access: public  
		//	    Arguments: $as_fechainides  // fecha desde
		//				   $as_fechafinhas	 // fecha hasta	 
		//				   $as_estatus // estatus de la pasantia
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los datos del pasante y de la pasantia.
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	   $lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
		if(!empty($as_fechainides))
		{
			$as_fechainides=$this->io_funciones->uf_convertirdatetobd($as_fechainides);
			$ls_criterio=$ls_criterio. "  AND srh_pasantias.fecini>='".$as_fechainides."'";
		}
		if(!empty($as_fechafinhas))
		{
			$as_fechafinhas=$this->io_funciones->uf_convertirdatetobd($as_fechafinhas);
			$ls_criterio=$ls_criterio. "  AND srh_pasantias.fecfin<='".$as_fechafinhas."'";
		}

		switch($as_orden)
		{
		case "1": // Ordena por número de las pasantías
		
		$ls_orden="srh_pasantias.nropas";
		break;
		
		case "2": // Ordena por cédula del personal
		
		$ls_orden="srh_pasantias.cedpas";
		break;
		case "3": // Ordena por apellido del personal
		
		$ls_orden="srh_pasantias.apepas";
		break;
		case "4": // Ordena por nombre del personal
		
		$ls_orden="srh_pasantias.nompas";
		break;

		}

		switch($as_estatus)
		{
		case "": 
		$ls_cadena="";
		break;
			
		case "Activa":
		
		$ls_cadena= $ls_cadena."  AND srh_pasantias.estado='".$as_estatus."' ";
		break;
		case "Concluida": 

		$ls_cadena= $ls_cadena." AND srh_pasantias.estado='".$as_estatus."' ";
		break;
		}

		$ls_sql=" SELECT srh_pasantias.codemp,srh_pasantias.nropas,srh_pasantias.cedpas,srh_pasantias.fecini,
		        srh_pasantias.fecfin,srh_pasantias.nompas,srh_pasantias.apepas,srh_pasantias.estado".
				" FROM srh_pasantias".
				 " WHERE srh_pasantias.codemp='".$this->ls_codemp."' ".
				 " ".$ls_cadena." ". 
				 " ".$ls_criterio." ". 
				 " ORDER BY ".$ls_orden." ";                 
      
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_buscar_datos_pasantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;  
	}// end uf_buscar_datos_pasantes
	
 function uf_print_detalle_evaluacion_pasantes($as_fechainides,$as_fechafinhas,$as_estatus,$as_orden,$as_cedpas)
   {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_evaluacion_pasantes
		//         Access: public  
		//	    Arguments: $as_fechainides  // fecha desde
		//				   $as_fechafinhas	 // fecha hasta	
		//                 $as_estatus  // estatus de la pasantia 
		//				   $as_cedpas // cédula del personal desde
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los datos de la evaluación del pasante.
		//	   Creado Por: Ing.Gloriely Fréitez
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
		if(!empty($as_fechainides))
		{
			$as_fechainides=$this->io_funciones->uf_convertirdatetobd($as_fechainides);
			$ls_criterio=$ls_criterio. "  AND srh_evaluacion_pasantia.feceval>='".$as_fechainides."'";
		}
		if(!empty($as_fechafinhas))
		{
			$as_fechafinhas=$this->io_funciones->uf_convertirdatetobd($as_fechafinhas);
			$ls_criterio=$ls_criterio. "  AND srh_evaluacion_pasantia.feceval<='".$as_fechafinhas."'";
		}
		switch($as_orden)
		{
		case "1": // Ordena por número de las pasantías
		
		$ls_orden="srh_evaluacion_pasantia.nropas";
		break;
		
		case "2": // Ordena por cédula del personal
		
		$ls_orden="srh_pasantias.cedpas";
		break;
		case "3": // Ordena por apellido del personal
		
		$ls_orden="srh_pasantias.apepas";
		break;
		case "4": // Ordena por nombre del personal
		
		$ls_orden="srh_pasantias.nompas";
		break;

		}
		
		$ls_sql=" SELECT srh_evaluacion_pasantia.*,srh_pasantias.cedpas".
				" FROM srh_evaluacion_pasantia,srh_pasantias".
				 " WHERE srh_evaluacion_pasantia.codemp='".$this->ls_codemp."' ".
				 " AND srh_evaluacion_pasantia.nropas=srh_pasantias.nropas".
				 " AND srh_pasantias.cedpas='".$as_cedpas."' ". 
				 " ".$ls_cadena." ".
				 " ORDER BY ".$ls_orden." ";                 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_print_detalle_evaluacion_pasantes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;  
	}// end uf_print_detalle_evaluacion_pasantes
	//----------------------------------------------------------------------------------------------------------------------------
function uf_select_registro_concurso($as_codcon,&$rs_data)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_registro_concurso
		//         Access: public  
		//	    Arguments: as_codcon      // Código de concurso 
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     	         
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los concursos en un periodo.
		//	   Creado Por: María Beatriz Unda.
		// Fecha Creación: 05/03/2008									Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
       	
	 
		 $ls_sql= " SELECT srh_concurso.*, sno_asignacioncargo.codasicar, sno_asignacioncargo.denasicar, ".
		 	      " sno_cargo.codcar, sno_cargo.descar  ".
				  " FROM   srh_concurso ".
		          " LEFT JOIN sno_asignacioncargo ON  (srh_concurso.codcar=sno_asignacioncargo.codasicar AND      ".
			      "                               srh_concurso.codnom=sno_asignacioncargo.codnom)  ".
			      " LEFT JOIN sno_cargo  ON  (srh_concurso.codcar=sno_cargo.codcar AND ".
				  "  srh_concurso.codnom=sno_cargo.codnom) ".	 
				  " WHERE srh_concurso.codemp='".$this->ls_codemp."' ".
				  " AND srh_concurso.codcon = '".$as_codcon."'  ".
				  " ORDER BY codcon ";	
			 
		
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_registro_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			
			return $lb_valido;
	
	}// end function uf_select_registro_concurso
	
	
   function uf_select_participantes_concurso($as_codcon, $as_orden, &$rs_data)
	{	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_participantes_concurso
		//         Access: public  
		//	    Arguments: as_codcon      // Código de concurso 
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     	         
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de los concursos en un periodo.
		//	   Creado Por: María Beatriz Unda.
		// Fecha Creación: 05/03/2008									Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];


	switch($as_orden)
		{
		case "1": // Ordena por Código de concurso 
		
		$ls_orden="srh_concurso.codcon";
		break;
		
		case "2": // Ordena por código de Personal
		
		$ls_orden="srh_dt_ganadores_concurso.codper";
		break;
		
		case "3": // Ordena por Puntaje
		$ls_orden="srh_dt_ganadores_concurso.total DESC";
		break;
	

		}		
		
	 switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(srh_concursante.nomper,' ',srh_concursante.apeper)";
				
				break;
			case "POSTGRES":
				$ls_cadena="srh_concursante.nomper||' '||srh_concursante.apeper";
				
				break;
			
		}
	 
	 
		 $ls_sql=" SELECT srh_ganadores_concurso.*, srh_dt_ganadores_concurso.*,  srh_concurso.codcon,  (SELECT ".$ls_cadena." FROM srh_concursante WHERE  srh_dt_ganadores_concurso.codper=srh_concursante.codper) as nombre1  ".
				  " FROM  srh_concurso, srh_ganadores_concurso, srh_dt_ganadores_concurso ".
				  " WHERE srh_concurso.codemp='".$this->ls_codemp."' AND ".
				  " srh_concurso.codcon = '".$as_codcon."'  ".
				  " AND srh_dt_ganadores_concurso.codcon = srh_concurso.codcon  ".
				  " AND srh_ganadores_concurso.codcon = srh_concurso.codcon  ".		  
				  " ORDER BY ".$ls_orden."  ";	
		
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_participantes_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
			}
			
				
			return $lb_valido;
	
	}// end function uf_select_participantes_concurso
	
//--------------------------------------------------------------------------------------------------------------------------------
function uf_listado_evaluacion_adiestramiento($as_fechades,$as_fechahas,$as_orden)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_adiestramiento
		//         Access: public  
		//	    Arguments: $as_fechades  // fecha desde
		//				   $as_fechahas	 // fecha hasta	 
		//				   $as_codperdes // codigo del personal desde
		//				   $as_codperhas	 // codigo del personal hasta			
		//				   $as_orden // variable para ordenar               
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las solicitudes de las pasantias
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 05/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;	
	    $ls_orden="";
	    $ls_cadena="";	
		$ls_cadena2="";
		$ls_gestor = $_SESSION["ls_gestor"];
	 	$as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);	   
	    $as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas); 		
	     
	  if (($as_fechades!="")&&($as_fechahas!=""))
	    {
		  $ls_cadena= " and (c.feceval between '".$as_fechades."' and '".$as_fechahas."')";
		}
		
	  if ($as_orden==1)
	    {
		  $ls_orden=" order by c.nroreg";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden=" order by c.feceval";
		} 
	 
	 	   $ls_sql="SELECT a.codemp, a.nroreg, a.fecha, a.codper, a.coduniadm, a.codprov, a.descripcion, 
       					   a.observacion, a.fecini, a.fecfin, a.durhras, a.costo, a.estrategia, a.objetivo, a.area, 
       					   b.nompro, d.nomper,d.apeper, c.feceval, c.obseval
    					   FROM srh_solicitud_adiestramiento a 
						   left join rpc_proveedor b on (a.codprov=b.cod_pro)
						   left join sno_personal d on (a.codper=d.codper), srh_evaluacion_adiestramiento c
						   where a.codemp='".$this->ls_codemp."' and a.nroreg=c.nroreg".$ls_cadena.$ls_orden;
	
	    
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_evaluacion_adiestramiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_list_adi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}



function uf_listado_personas_evaluacion_adiestramiento($as_adiestramiento)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_personas_adiestramiento
		//         Access: public  
		//	    Arguments: $as_adiestramiento  // codigo del adiestramiento				                  
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que asistiran al adiestramiento
		//	   Creado Por: Ing.Rivero Jennifer
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	    $ls_valido=true;
	    	 
	    $ls_sql="SELECT a.codemp, a.nroreg, a.codper, a.carper,a.dep, 
       					b.cedper, b.nomper,b.apeper, c.asistencia
				 FROM srh_dt_solicitud_adiestramiento a
				 left join sno_personal b on (a.codper=b.codper), srh_dt_evaluacion_adiestramiento c
				 where a.codemp='".$this->ls_codemp."' and a.codper=c.codper
				 and a.nroreg='".$as_adiestramiento."'
				 order by a.codper";

	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_personas_evaluacion_adiestramiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->det_pers_adi->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}
	
//--------------------------------------------------------------------------------------------------------------------------------
function uf_select_hmovimiento($as_codper, $as_nummov, &$as_uniadmant, &$as_cargoant)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_hmovimiento
		//         Access: public 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas evaluadas psicológicamente.
		//	   Creado Por: María Beatriz Unda.
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$lb_valido=true;
		
		$ls_sql=" SELECT MAX(fecreg), sno_unidadadmin.desuniadm,  ".
				 " (SELECT sno_cargo.descar FROM  sno_cargo WHERE sno_cargo.codcar = srh_hmovimiento_personal.codcar AND 
				    sno_cargo.codnom = srh_hmovimiento_personal.codnom) as cargo1, ".
				 " (SELECT sno_asignacioncargo.denasicar FROM  sno_asignacioncargo WHERE 
				    sno_asignacioncargo.codasicar = srh_hmovimiento_personal.codcar AND 
					sno_asignacioncargo.codnom = srh_hmovimiento_personal.codnom) as cargo2 ".
		  		 " FROM srh_hmovimiento_personal ".
				 " LEFT JOIN sno_unidadadmin ON (srh_hmovimiento_personal.minorguniadm = sno_unidadadmin.minorguniadm ".
				 " AND  srh_hmovimiento_personal.ofiuniadm = sno_unidadadmin.ofiuniadm AND ".
				 " srh_hmovimiento_personal.uniuniadm = sno_unidadadmin.uniuniadm AND ".
				 " srh_hmovimiento_personal.depuniadm = sno_unidadadmin.depuniadm AND ".
				 " srh_hmovimiento_personal.prouniadm = sno_unidadadmin.prouniadm )".
				 " WHERE  srh_hmovimiento_personal.codemp='".$this->ls_codemp."' ".
				 " AND  srh_hmovimiento_personal.codper='$as_codper'".
				 " AND  srh_hmovimiento_personal.nummov='$as_nummov'".
				 " GROUP BY fecreg, desuniadm, codcar, codnom ";                 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_hmovimiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while ($row=$this->io_sql->fetch_row($rs_data)) 
			{
			     
					$as_uniadmant = $row["desuniadm"];
					$as_cargoant1 = $row["cargo1"];
					$as_cargoant2 = $row["cargo2"];
					
					if ($as_cargoant1=="")
					{
						$as_cargoant = $as_cargoant2;
					}
					else
					{
						$as_cargoant = $as_cargoant1;
					}
					
					
			
			}
		
		}
		
		
		return $lb_valido;
	}// end uf_select_listado_movimiento_personal
	
//--------------------------------------------------------------------------------------------------------------------------------
function uf_select_listado_movimiento_personal($as_fechades,$as_fechahas,$as_codperdes,$as_codperhas,$as_orden,&$rs_data)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_listado_movimiento_personal
		//         Access: public  
		//	    Arguments: as_fechades   // Fecha de selección desde
		//                 as_fechahas  // Fecha de selección hasta
		//                 as_codperdes // Código del personal desde
		//                 as_codperhas  // Código del personal hasta
		//                 as_orden	//  orden de selección
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas evaluadas psicológicamente.
		//	   Creado Por: María Beatriz Unda.
		// Fecha Creación: 06/03/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];

	  	if(!empty($as_codperdes))
		{
		 $ls_criterio= $ls_criterio."   AND srh_movimiento_personal.codper>='".$as_codperdes."'";
		}
		if(!empty($as_codperhas))
		{
		  $ls_criterio= $ls_criterio."   AND srh_movimiento_personal.codper<='".$as_codperhas."'";
		}
		if(!empty($as_fechades))
		{
			$as_fechades=$this->io_funciones->uf_convertirdatetobd($as_fechades);
			$ls_criterio=$ls_criterio. "  AND srh_movimiento_personal.fecreg>='".$as_fechades."'";
		}
		if(!empty($as_fechahas))
		{
			$as_fechahas=$this->io_funciones->uf_convertirdatetobd($as_fechahas);
			$ls_criterio=$ls_criterio. "  AND srh_movimiento_personal.fecreg<='".$as_fechahas."'";
		}


		switch($as_orden)
		{
			
		case "2": // Ordena por código del personal		
		$ls_orden="srh_movimiento_personal.codper";
		break;
		case "3": // Ordena por apellido 
		
		$ls_orden="sno_personal.apeper";
		break;
		case "4": // Ordena por nombre
		
		$ls_orden="sno_personal.nomper";
		break;

		}
	
		
		$ls_sql=" SELECT srh_movimiento_personal.*, srh_grupomovimientos.*,sno_personal.nomper,sno_personal.apeper, ".
		         " sno_personal.cedper,sno_unidadadmin.desuniadm,  ".
				 " (SELECT sno_cargo.descar FROM  sno_cargo WHERE sno_cargo.codcar = srh_movimiento_personal.codcar AND 
				    sno_cargo.codnom = srh_movimiento_personal.codnom) as cargo1, ".
				 " (SELECT sno_asignacioncargo.denasicar FROM  sno_asignacioncargo WHERE 
				    sno_asignacioncargo.codasicar = srh_movimiento_personal.codcar AND 
					sno_asignacioncargo.codnom = srh_movimiento_personal.codnom) as cargo2 ".
		  		 " FROM sno_personal,srh_grupomovimientos,srh_movimiento_personal ".
				 " LEFT JOIN sno_unidadadmin ON (srh_movimiento_personal.minorguniadm = sno_unidadadmin.minorguniadm ".
				 " AND  srh_movimiento_personal.ofiuniadm = sno_unidadadmin.ofiuniadm AND ".
				 " srh_movimiento_personal.uniuniadm = sno_unidadadmin.uniuniadm AND ".
				 " srh_movimiento_personal.depuniadm = sno_unidadadmin.depuniadm AND ".
				 " srh_movimiento_personal.prouniadm = sno_unidadadmin.prouniadm )".
				 " WHERE  srh_movimiento_personal.codemp='".$this->ls_codemp."' ".
				 " AND  srh_movimiento_personal.codper=sno_personal.codper".
				 " AND  srh_movimiento_personal.grumov=srh_grupomovimientos.codgrumov".
				 " ".$ls_criterio." ". 
				 " ORDER BY ".$ls_orden." ";                 

													
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_listado_movimiento_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		
		return $lb_valido;
	}// end uf_select_listado_movimiento_personal
	
//--------------------------------------------------------------------------------------------------------------------------------
function uf_listado_movimiento($as_nroreg, $as_codper)
	{
	    $ls_valido=true;
	    	 
	    $ls_sql="SELECT srh_movimiento_personal.*, sno_personal.apeper,sno_personal.nomper,sno_personal.cedper,
		         sno_asignacioncargo.denasicar, sno_cargo.descar
				 FROM  sno_personal, srh_movimiento_personal 
			     LEFT JOIN sno_asignacioncargo ON (srh_movimiento_personal.codcar=sno_asignacioncargo.codasicar 
				 AND srh_movimiento_personal.codnom=sno_asignacioncargo.codnom)
				 LEFT JOIN sno_cargo ON (srh_movimiento_personal.codcar=sno_cargo.codcar 
				 AND srh_movimiento_personal.codnom=sno_asignacioncargo.codnom) 
				 WHERE srh_movimiento_personal.codemp='".$this->ls_codemp."' 
				 AND srh_movimiento_personal.nummov='".$as_nroreg."'
				 AND srh_movimiento_personal.codper='".$as_codper."' 
				 AND sno_personal.codper=srh_movimiento_personal.codper
				 ORDER BY srh_movimiento_personal.nummov";
			
	 
	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->uf_listado_movimiento ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$ls_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$ls_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
	 
	 return $ls_valido;	
	}


//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_select_lote_revision_odi($ad_fechades,$ad_fechahas,$as_codperdes,$as_codperhas)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_lote_revision_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
        
        switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		
	    $ls_sql="select srh_odi.nroreg,srh_persona_odi.codper,fecinirev1,fecfinrev1,fecinirev2 ,fecfinrev2,objetivo ,
				(select ".$ls_cadena." from sno_personal WHERE sno_personal.codper=srh_persona_odi.codper AND srh_persona_odi.tipo='P') AS nomper,
				(select cedper from sno_personal WHERE sno_personal.codper=srh_persona_odi.codper AND srh_persona_odi.tipo='P') AS cedper
				from srh_persona_odi,srh_odi 
				where srh_persona_odi.codper  between '".$as_codperdes." 'AND  '".$as_codperhas."'
				AND  srh_persona_odi.nroreg=srh_odi.nroreg 
				AND  srh_persona_odi.tipo='P' ";	
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	 function uf_select_lote_dt_revision_odi($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_lote_revision_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
       	
	    $ls_sql="SELECT srh_dt_revisiones_odi.nroreg,srh_dt_revisiones_odi.fecrev,srh_dt_revisiones_odi.odi, 
				 srh_dt_revisiones_odi.observacion,srh_dt_odi.valor
				 FROM srh_dt_revisiones_odi,srh_dt_odi 
				 WHERE srh_dt_revisiones_odi.nroreg='".$as_nroreg."'
				 AND srh_dt_revisiones_odi.nroreg=srh_dt_odi.nroreg
				 AND srh_dt_revisiones_odi.cododi=srh_dt_odi.cododi";	   
 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito
	//-----------------------------------------------------------------------------------------------------------------------------------

	
     function uf_select_odi_persona($as_nroreg,$as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_lote_revision_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
 	
	    $ls_sql="select (select sno_personal.nomper||' '||sno_personal.apeper from sno_personal 
					WHERE sno_personal.codper=srh_persona_odi.codper AND srh_persona_odi.tipo='".$as_tipo."') as evaluador from srh_persona_odi where nroreg='".$as_nroreg."' AND srh_persona_odi.tipo='".$as_tipo."' ";	   
			  	  	    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
     function uf_select_odi_personas($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_lote_revision_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
	    $ls_sql="select * from srh_persona_evaluacion_desempeno where nroeval='".$as_nroreg."'  ORDER BY tipo";	   

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
 function uf_select_registro_odi($as_nroeval,$as_codper,$as_tipo)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_registro_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
     
        
        switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		
	    $ls_sql="SELECT sno_personalnomina.codper,sno_personalnomina.codnom ,
				(SELECT denasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as denasicar,
				(SELECT codasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as codasicar,
				(SELECT desuniadm FROM sno_unidadadmin
				WHERE sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ) as desuniadm,
				(SELECT ".$ls_cadena." FROM sno_personal 
				WHERE sno_personalnomina.codper=sno_personal.codper AND sno_personalnomina.codper='".$as_codper."')
				as nombre, 
				(SELECT cedper FROM sno_personal 
				WHERE sno_personalnomina.codper=sno_personal.codper AND sno_personalnomina.codper='".$as_codper."' )
				as cedper
				FROM sno_personalnomina, srh_persona_evaluacion_desempeno, sno_nomina
				WHERE sno_personalnomina.codper='".$as_codper."' 
				AND tipo='".$as_tipo."'
				AND sno_personalnomina.codnom=sno_nomina.codnom
				AND sno_nomina.espnom='0'
				AND srh_persona_evaluacion_desempeno.nroeval='".$as_nroeval."'
				AND  staper ='1'"; 
	   
	       	    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------

 function uf_select_dt_evaluacion_odi($as_nroeval)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_registro_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
	    $ls_sql="SELECT nroeval, odi, peso_rango, rango ".
		         "  FROM srh_evaluacion_odi, srh_dt_odi ".
 				 "  WHERE srh_evaluacion_odi.codemp='".$this->ls_codemp."' ".
				 "  AND srh_evaluacion_odi.nroeval='".$as_nroeval."' ".
				 "  AND srh_evaluacion_odi.codemp=srh_dt_odi.codemp ".
				 "  AND srh_evaluacion_odi.cododi=srh_dt_odi.cododi ";  
	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle3->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_bonos_x_merito	
//-----------------------------------------------------------------------------------------------------------------------------------
  function uf_select_competencias_odi($as_nroeval)
  {     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_registro_odi
		//         Access: public  
		//	    Arguments: $as_nroeval numero de la evaluación 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		//  Creado por: Ing. Rivero Jennifer
		// Fecha Creación: 10/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		
	    $ls_sql="SELECT *
				FROM srh_items_evaluacion, srh_competencias_evaluacion_desempeno 
				WHERE srh_competencias_evaluacion_desempeno.codemp='".$this->ls_codemp."'
				  AND srh_competencias_evaluacion_desempeno.nroeval = '".$as_nroeval."' 
				  AND srh_competencias_evaluacion_desempeno.codite = srh_items_evaluacion.codite 
		        ORDER BY srh_competencias_evaluacion_desempeno.codite";////print $ls_sql."<br>"; 
				    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_evaluacion_desemp ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle4->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
  }
	
//-----------------------------------------------------------------------------------------------------------------------------------	
 function uf_select_dt_evaluacion_desempeño($as_nroeval)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_evaluacion_desempeño
		//         Access: public  
		//	    Arguments:$as_nroeval Código de la evaluación    
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones
		// Creado por: Ing. Rivero Jennifer
		// Fecha Creación: 11/03/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		
	    $ls_sql="SELECT codemp, nroeval, fecha, fecinie, fecfine, revision, totalodi, 
	                    totalcompe, actuacion, obs_sup, obs_jefe, tipo_eval 
	             FROM srh_evaluacion_desempeno where nroeval='".$as_nroeval."' ";   
	    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_evaluacion_desempeño ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle5->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dt_evaluacion_desempeño	
//-----------------------------------------------------------------------------------------------------------------------------------

function uf_select_lote_odi($as_nroreg, $ad_fecini, $ad_fecfin, &$rs_data)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_lote_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
        
        
        switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		
		$ad_fecini=$this->io_funciones->uf_convertirdatetobd($ad_fecini);	   
	    $ad_fecfin=$this->io_funciones->uf_convertirdatetobd($ad_fecfin); 
		
	    $ls_sql="SELECT srh_odi.nroreg,srh_persona_odi.codper,fecinirev1,fecfinrev1,fecinirev2 ,fecfinrev2,objetivo,
				(SELECT ".$ls_cadena." FROM sno_personal WHERE sno_personal.codper=srh_persona_odi.codper AND srh_persona_odi.tipo='P') AS nomper,
				(SELECT desuniadm FROM sno_unidadadmin
				WHERE sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ) as desuniadm,
				(SELECT cedper FROM sno_personal WHERE sno_personal.codper=srh_persona_odi.codper AND srh_persona_odi.tipo='P') AS cedper, sno_asignacioncargo.denasicar, sno_cargo.descar
				  FROM srh_odi,srh_persona_odi ".
			    " JOIN sno_personalnomina  ON  (srh_persona_odi.codper=sno_personalnomina.codper)   ".
			    " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and       ".
			    "                               sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
			    " LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom) ".
				" JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
				" WHERE srh_odi.nroreg = '".$as_nroreg."' 
				  AND  srh_persona_odi.nroreg=srh_odi.nroreg 
				  AND  srh_persona_odi.tipo='P' ";
				
	   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_lote_odi ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		return $lb_valido;
	}// end function 
	//--------------------------------------------------------------------------------------------------------------------------------	
	
	 function uf_select_lote_dt_odi($as_nroreg,$ad_fecini, $ad_fecfin)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_lote_dt_odi
		//         Access: public  
		//	    Arguments: ad_fechades    // Fecha desde 
		//                 ad_fechahas  // Fecha hasta 
		//                 as_codperdes  // Código del personal Desde
		//                 as_codperhas    // Código del personal Hasta
		//                 as_orden    //  Orden de selección (código o nombre) as_tipproben     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene bonos por meritos
		// Fecha Creación: 03/06/2007									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
       
	   $ad_fecini=$this->io_funciones->uf_convertirdatetobd($ad_fecini);	   
	    $ad_fecfin=$this->io_funciones->uf_convertirdatetobd($ad_fecfin); 
	   	
	    $ls_sql="SELECT srh_dt_revisiones_odi.nroreg,srh_dt_revisiones_odi.fecrev,srh_dt_revisiones_odi.odi, 
				 srh_dt_revisiones_odi.observacion,srh_dt_odi.valor
				 FROM srh_dt_revisiones_odi,srh_dt_odi 
				 WHERE srh_dt_revisiones_odi.nroreg='".$as_nroreg."'
				 AND srh_dt_revisiones_odi.nroreg=srh_dt_odi.nroreg
				 AND srh_dt_revisiones_odi.cododi=srh_dt_odi.cododi
				 ORDER BY srh_dt_revisiones_odi.cododi ";	   
				 
			
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_lote_dt_odi ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
			
				
				
				$this->ds_detalle->data=$this->io_sql->obtener_datos($rs_data);		
			
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}

	//--------------------------------------------------------------------------------------------------------------------------------

function uf_select_personas_evaluacion_eficiencia($as_nroeval)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_personas_evaluacion_eficiencia
		//         Access: public  
		//	    Arguments: as_nroeval    // número de evaluación
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las personas asociadas a una evaluación de eficiencia
		// Fecha Creación: 15/04/08									Fecha Última Modificación :  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
	    $ls_sql="select * from srh_persona_evaluacion_eficiencia where nroeval='".$as_nroeval."'  ORDER BY tipo";	   

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_personas_evaluacion_eficiencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function  uf_select_personas_evaluacion_eficiencia
	//-----------------------------------------------------------------------------------------------------------------------------------

 function uf_select_registro_persona_eval_eficiencia($as_nroeval,$as_codper,$as_tipo)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_registro_persona_eval_eficiencia
		//         Access: public  
		//	    Arguments: as_nroeval    //  Número de Evaluación de Eficiencia
		//                 as_codper   // Código del Personal
		//                 as_tipo    // Tipo de Persona (trabajador o evaluador)   
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene evaluacion de eficiencia
		// Fecha Creación: 15/04/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
      
        
        switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		
	    $ls_sql="SELECT sno_personalnomina.codper,sno_personalnomina.codnom ,
				(SELECT denasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as denasicar,
				(SELECT codasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as codasicar,
				(SELECT descar FROM sno_cargo 
				WHERE sno_personalnomina.codemp = sno_cargo.codemp 
				AND sno_personalnomina.codnom = sno_cargo.codnom 
				AND sno_personalnomina.codcar = sno_cargo.codcar) as descargo,
				(SELECT codcar FROM sno_cargo 
				WHERE sno_personalnomina.codemp = sno_cargo.codemp 
				AND sno_personalnomina.codnom = sno_cargo.codnom 
				AND sno_personalnomina.codcar = sno_cargo.codcar) as codcargo,
				(SELECT desuniadm FROM sno_unidadadmin
				WHERE sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ) as desuniadm,
				(SELECT ".$ls_cadena." FROM sno_personal 
				WHERE sno_personalnomina.codper=sno_personal.codper AND sno_personalnomina.codper='".$as_codper."')
				as nombre, 
				(SELECT cedper FROM sno_personal 
				WHERE sno_personalnomina.codper=sno_personal.codper AND sno_personalnomina.codper='".$as_codper."' )
				as cedper
				FROM sno_personalnomina, sno_nomina, srh_persona_evaluacion_eficiencia
				WHERE sno_personalnomina.codper='".$as_codper."' 
				AND sno_nomina.codnom = sno_personalnomina.codnom 
				AND sno_nomina.espnom='0'
				AND tipo='".$as_tipo."'
				AND srh_persona_evaluacion_eficiencia.nroeval='".$as_nroeval."'
				AND  staper ='1'"; 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_registro_persona_eval_eficiencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_registro_persona_eval_eficiencia
	//-----------------------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------------------------------------------------------------

 function uf_select_factor_evaluacion_eficiencia($as_nroeval,&$rs_data)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_factor_evaluacion_eficiencia
		//         Access: public  
		//	    Arguments: as_nroeval    //  Número de Evaluación de Eficiencia     
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la el detalle de la evaluación de eficiencia
		// Fecha Creación: 15/04/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
	    $ls_sql="SELECT srh_evaluacion_eficiencia.tipo_eval, srh_evaluacion_eficiencia.nroeval, srh_items_evaluacion.codeval, 
		                srh_items_evaluacion.codite, srh_items_evaluacion.denite, srh_items_evaluacion.codasp, 
						srh_items_evaluacion.codemp, srh_aspectos_evaluacion.codeval, srh_aspectos_evaluacion.codasp, 
						srh_aspectos_evaluacion.denasp, srh_dt_evaluacion_eficiencia.puntos ,srh_dt_evaluacion_eficiencia.nroeval 
		           FROM srh_evaluacion_eficiencia, srh_dt_evaluacion_eficiencia, srh_items_evaluacion, srh_aspectos_evaluacion 
				   WHERE srh_evaluacion_eficiencia.nroeval='".$as_nroeval."'
				   AND   srh_evaluacion_eficiencia.nroeval= srh_dt_evaluacion_eficiencia.nroeval
				   AND   srh_dt_evaluacion_eficiencia.codite = srh_items_evaluacion.codite
				   AND   srh_items_evaluacion.codasp = srh_aspectos_evaluacion.codasp
				   AND  srh_aspectos_evaluacion.codeval = srh_evaluacion_eficiencia.tipo_eval
				   AND  srh_items_evaluacion.codeval = srh_evaluacion_eficiencia.tipo_eval
				   ORDER BY srh_items_evaluacion.codasp "; //print $ls_sql;
				   
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_factor_evaluacion_eficiencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}

		return $lb_valido;
	}// end function uf_select_factor_evaluacion_eficiencia	
//--------------------------------------------------------------------------------------------------------------------------------


function uf_select_dt_evaluacion_eficiencia($as_nroeval, &$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dt_evaluacion_eficiencia
		//         Access: public  
		//	    Arguments:$as_nroeval Código de la evaluación    
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones de eficiencia
		// Creado por: María Beatriz Unda
		// Fecha Creación: 15/04/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
		
	    $ls_sql="SELECT * 
	             FROM srh_evaluacion_eficiencia where nroeval='".$as_nroeval."' ";   
	    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dt_evaluacion_eficiencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		return $lb_valido;
	}// end function uf_select_dt_evaluacion_eficiencia	
	
//--------------------------------------------------------------------------------------------------------------------------------


function uf_select_evaluacion_desempeno($ad_fecini, $ad_fecfin, &$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_evaluacion_desempeno
		//         Access: public  
		//	    Arguments: $ad_fecini // fecha de inicio del periodo de evaluacion
		//                 $ad_fecini // fecha de final del periodo de evaluacion
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones de desempeño realizadas en un periodo de 
		//                 evaluación
		// Creado por: María Beatriz Unda
		// Fecha Creación: 14/05/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
	
	    $ad_fecini=$this->io_funciones->uf_convertirdatetobd($ad_fecini);	   
	    $ad_fecfin=$this->io_funciones->uf_convertirdatetobd($ad_fecfin); 
		
	    $ls_sql="SELECT * 
	             FROM srh_evaluacion_desempeno WHERE ".
				 " fecha BETWEEN  '".$ad_fecini."' AND '".$ad_fecfin."'  "; 
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_evaluacion_desempeno ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		
		return $lb_valido;
	}// end function uf_select_dt_evaluacion_eficiencia	
//--------------------------------------------------------------------------------------------------------------------------------


function uf_select_renglones(&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_renglones
		//         Access: public  
		//	    Arguments: $as_tipoeval // tipo de evaluacion
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones de desempeño realizadas en un periodo de 
		//                 evaluación
		// Creado por: María Beatriz Unda
		// Fecha Creación: 14/05/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
	
	   
	    $ls_sql="SELECT dendetesc, valinidetesc,valfindetesc
	             FROM srh_dt_escalageneral, srh_tipoevaluacion, srh_evaluacion_desempeno  ".
				 " WHERE srh_tipoevaluacion.codeval = srh_evaluacion_desempeno.tipo_eval ".
				 " AND srh_tipoevaluacion.codesc = srh_dt_escalageneral.codesc ".
				 " GROUP BY dendetesc, valinidetesc,valfindetesc";   
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_renglonesERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		return $lb_valido;
	}// end function uf_select_dt_evaluacion_eficiencia	

//--------------------------------------------------------------------------------------------------------------------------------


function uf_select_evaldes_uniadm($ad_fecini, $ad_fecfin,$as_coduniadm1, $as_coduniadm2,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_evaldes_uniadm
		//         Access: public  
		//	    Arguments: $ad_fecini // fecha de inicio del periodo de evaluacion
		//                 $ad_fecini // fecha de final del periodo de evaluacion
		//				   $as_coduniadm1 // código de la unidad adminiestrativa desde
		//                 $as_coduniadm2 // código de la unidad adminiestrativa hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las evaluaciones de desempeño realizadas en un periodo de 
		//                 evaluación y en unidades administrativas
		// Creado por: María Beatriz Unda
		// Fecha Creación: 14/05/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
	
	    $ad_fecini=$this->io_funciones->uf_convertirdatetobd($ad_fecini);	   
	    $ad_fecfin=$this->io_funciones->uf_convertirdatetobd($ad_fecfin); 
			
		$minorguniadm1 = substr($as_coduniadm1,0,4);
		$ofiuniadm1 = substr($as_coduniadm1,5,2);
		$uniuniadm1 = substr($as_coduniadm1,8,2);
		$depuniadm1 = substr($as_coduniadm1,11,2);
		$prouniadm1 = substr($as_coduniadm1,14,2);
		
		$uniadm1=$minorguniadm1.$ofiuniadm1.$uniuniadm1.$depuniadm1.$prouniadm1; 
		
		$minorguniadm2 = substr($as_coduniadm2,0,4);
		$ofiuniadm2 = substr($as_coduniadm2,5,2);
		$uniuniadm2 = substr($as_coduniadm2,8,2);
		$depuniadm2 = substr($as_coduniadm2,11,2);
		$prouniadm2 = substr($as_coduniadm2,14,2);
		
		$uniadm2=$minorguniadm2.$ofiuniadm2.$uniuniadm2.$depuniadm2.$prouniadm2;
		
		 switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" AND CONCAT(sno_personalnomina.minorguniadm,sno_personalnomina.ofiuniadm,sno_personalnomina.uniuniadm,sno_personalnomina.depuniadm,sno_personalnomina.prouniadm) BETWEEN '".$uniadm1."' AND '".$uniadm2."' ";
				break;
			case "POSTGRES":
				$ls_cadena=" AND sno_personalnomina.minorguniadm||sno_personalnomina.ofiuniadm||sno_personalnomina.uniuniadm||sno_personalnomina.depuniadm||sno_personalnomina.prouniadm   BETWEEN '".$uniadm1."' AND '".$uniadm2."' ";
				break;
			
		}	
						
	    $ls_sql="SELECT srh_evaluacion_desempeno.totalodi, srh_evaluacion_desempeno.totalcompe, 
		         sno_unidadadmin.desuniadm, srh_evaluacion_desempeno.nroeval		
		        FROM sno_personalnomina, srh_evaluacion_desempeno, srh_persona_evaluacion_desempeno, sno_unidadadmin, sno_nomina
				WHERE  sno_personalnomina.codper = srh_persona_evaluacion_desempeno.codper
                AND srh_persona_evaluacion_desempeno.tipo = 'P'
				AND srh_persona_evaluacion_desempeno.nroeval = srh_evaluacion_desempeno.nroeval".				
				$ls_cadena.
				"AND srh_evaluacion_desempeno.fecha BETWEEN  '".$ad_fecini."' AND '".$ad_fecfin."'  ".
				"AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm
				 AND sno_personalnomina.ofiuniadm  = sno_unidadadmin.ofiuniadm
				 AND sno_personalnomina.uniuniadm  = sno_unidadadmin.uniuniadm
				 AND sno_personalnomina.depuniadm  = sno_unidadadmin.depuniadm 
				 AND sno_personalnomina.prouniadm  = sno_unidadadmin.prouniadm 
				 AND sno_nomina.codnom = sno_personalnomina.codnom 
				 AND sno_nomina.espnom='0'
				 ORDER BY sno_unidadadmin.desuniadm";   									
									
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_evaldes_uniadmERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		
		return $lb_valido;
	}// end function uf_select_evaldes_uniadm
	
//------------------------------------------------------------------------------------------------------------------------------

function uf_select_tipo_persona_concurso ($as_codper, &$as_tipoper)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_tipo_persona_concurso
		//         Access: public  
		//	    Arguments: $as_codper//  código del personal
		//                 $as_tipoper // tipo del personal (Interno o Externo)
		//    Description: función que busca el tipo de una persona en la tabla srh_personal concurso
		// Creado por: María Beatriz Unda
		// Fecha Creación: 02/06/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		 $ls_sql="SELECT tipo
	             FROM srh_persona_concurso  ".
				 " WHERE srh_persona_concurso.codper = '".$as_codper."' ";   
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_tipo_persona_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
       else
	   {
	     	$row=$this->io_sql->fetch_row($rs_data);
			$as_tipoper=trim ($row["tipo"]);
	   
	   }

}// end uf_select_tipo_persona_concurso	


//----------------------------------------------------------------------------------------------------------------------------------------
	function uf_personal_deduccion($as_codperdes,$as_codperhas,$as_orden,&$rs_data)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_personal_eval_psicologico
		//         Access: public  
		//	    Arguments: as_codperdes  // Codigo del concurso Desde
		//                 as_codperhas  // Codigo del concurso Hasta
		//                 as_orden         // Orden de los Datos en el Reporte
		//	      Returns: ls_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las deduccion del personal
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 12/06/2008									Fecha Última Modificación :  
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	{
	  $ls_cadena="";
	  $ls_orden="";	
	  $ls_valido=true;	  
	   
	  if (($as_codperdes!="")&&($as_codperhas!=""))
	    {
		  $ls_cadena= " AND sno_personaldeduccion.codper between '".$as_codperdes."' and '".$as_codperhas."'";		 
		}
	  
		
	  if ($as_orden==1)
	    {
		  $ls_orden=" order by sno_personaldeduccion.codper";
		}	  
	  if ($as_orden==2)
	    {
		  $ls_orden=" order by nomper";
		}
	  if ($as_orden==3)
	    {
		  $ls_orden=" order by apeper";
		}
	   
	
  
	
	  $ls_sql=" SELECT Distinct (sno_personaldeduccion.codper), sno_personal.nomper, sno_personal.apeper, 
	            sno_asignacioncargo.denasicar, sno_cargo.descar, sno_unidadadmin.desuniadm   ".
	          " FROM sno_unidadadmin, sno_personaldeduccion, sno_personal  ".
			  " JOIN sno_personalnomina  ON  (sno_personal.codper=sno_personalnomina.codper)   ".
			  " LEFT JOIN sno_asignacioncargo ON  (sno_personalnomina.codasicar=sno_asignacioncargo.codasicar and       ".
			  "                               sno_personalnomina.codnom=sno_asignacioncargo.codnom)  ".
			  " LEFT JOIN sno_cargo  ON  (sno_personalnomina.codcar=sno_cargo.codcar and sno_personalnomina.codnom=sno_cargo.codnom) ".
			  " JOIN sno_nomina ON (sno_nomina.codnom = sno_personalnomina.codnom AND sno_nomina.espnom='0') ".
		      " WHERE sno_personaldeduccion.codper= sno_personal.codper ".
			  " AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm  ".
			  " AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm  ".
			  " AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
			  " AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
			  " AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
			  " AND sno_personal.codemp='".$this->ls_codemp."' ".			
			  "	AND sno_personaldeduccion.codemp='".$this->ls_codemp."' ".$ls_cadena.$ls_orden;					
	

	 $rs_data=$this->io_sql->select($ls_sql);
	 
	 if($rs_data===false)
		{
				
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_personal_deduccion ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
	 
	 return $ls_valido;	
	}
//-------------------------------------------------------------------------------------------------------------------------------	


function uf_select_deduccion_personal($as_codper, &$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_deduccion_personal
		//         Access: public  
		//	    Arguments: $as_codper // código del personal	
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las deducciones por personal
		// Creado por: María Beatriz Unda
		// Fecha Creación: 13/06/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
			
						
	    $ls_sql=" SELECT sno_personaldeduccion.codtipded, sno_personaldeduccion.coddettipded ,srh_tipodeduccion.dentipded, ".
		        "  srh_dt_tipodeduccion.valprim, srh_dt_tipodeduccion.aporemple,srh_dt_tipodeduccion.edadmin,  ".
				"   srh_dt_tipodeduccion.aporempre,".
				" srh_dt_tipodeduccion.edadmax, srh_dt_tipodeduccion.suelbene,sno_personalnomina.sueper,sno_personal.fecnacper ".
		        " FROM sno_personaldeduccion, srh_tipodeduccion,srh_dt_tipodeduccion,sno_personalnomina,sno_personal,sno_nomina ".
				" WHERE  sno_personaldeduccion.codemp='".$this->ls_codemp."' ".
				" AND sno_personaldeduccion.codper='".$as_codper."'   ".				
				" AND  srh_tipodeduccion.codemp=sno_personaldeduccion.codemp   ".
				" AND  srh_tipodeduccion.codtipded =sno_personaldeduccion.codtipded   ".			
				" AND srh_dt_tipodeduccion.codemp=sno_personaldeduccion.codemp  ".	
				" AND srh_dt_tipodeduccion.codtipded=sno_personaldeduccion.codtipded  ".	
				" AND srh_dt_tipodeduccion.coddettipded=sno_personaldeduccion.coddettipded  ".				
				" AND sno_personal.codemp=sno_personaldeduccion.codemp  ".
				" AND sno_personal.codper=sno_personaldeduccion.codper ".
				" AND sno_personalnomina.codemp=sno_personal.codemp  ".
				" AND sno_personalnomina.codper=sno_personal.codper ".
				" AND   sno_personalnomina.codnom=sno_nomina.codnom   ". 
				" AND   sno_nomina.espnom='0' ";    
											
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_deduccion_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		
		return $lb_valido;
	}// end function uf_select_evaldes_uniadm
	
//------------------------------------------------------------------------------------------------------------------------------




function uf_select_deduccion_personal_familiar($as_codper, &$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_deduccion_personal_familiar
		//         Access: public  
		//	    Arguments: $as_codper // código del personal	
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las deducciones por personal
		// Creado por: María Beatriz Unda
		// Fecha Creación: 13/06/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
	    $ls_sql=" SELECT sno_familiardeduccion.codtipded,  srh_dt_tipodeduccion.valprim, srh_dt_tipodeduccion.aporemple, ".
		 		" srh_dt_tipodeduccion.edadmin,  srh_dt_tipodeduccion.edadmax, srh_dt_tipodeduccion.suelbene, ".
				" srh_dt_tipodeduccion.aporempre, sno_personalnomina.sueper, srh_tipodeduccion.dentipded, ".
		        " sno_familiar.fecnacfam, sno_familiar.nexfam ".
			    "  FROM sno_familiardeduccion,srh_dt_tipodeduccion, sno_personalnomina,srh_tipodeduccion,sno_nomina, ".
				"       sno_familiar  ".
				"        WHERE sno_familiardeduccion.codemp= '".$this->ls_codemp."'  ".
				"        AND   sno_familiardeduccion.codper= '".$as_codper."' ".			
				"        AND sno_familiar.codemp=sno_familiardeduccion.codemp  ".
				"        AND   sno_familiar.codper=sno_familiardeduccion.codper ".	
				"        AND   sno_familiar.cedfam=sno_familiardeduccion.cedfam ".							
				"        AND   srh_tipodeduccion.codemp=sno_familiardeduccion.codemp  ".
				"        AND   srh_tipodeduccion.codtipded=sno_familiardeduccion.codtipded  ".				
				"        AND   srh_dt_tipodeduccion.codemp=sno_familiardeduccion.codemp  ".
				"        AND   srh_dt_tipodeduccion.codtipded=sno_familiardeduccion.codtipded  ".				
				"        AND   srh_dt_tipodeduccion.coddettipded=sno_familiardeduccion.coddettipded  ".			
				"        AND sno_personalnomina.codemp= '".$this->ls_codemp."'  ".
				"		 AND sno_personalnomina.codper='$as_codper'   ".
				"		 AND sno_personalnomina.codnom=sno_nomina.codnom   ". 
				"        AND sno_nomina.espnom='0' ". 
				"   	 ORDER BY sno_familiardeduccion.coddettipded ";  
						
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
				
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_deduccion_personal_familiar ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		
		
		
		return $lb_valido;
	}// end function uf_select_deduccion_personal_familiar
	
//------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
  function uf_select_configuracion_contrato ($as_codcont)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_configuracion_contrato
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_codcont // Código de la Constancia
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del contrato
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/06/2008 								Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		
		$ls_sql="SELECT codcont, descont, concont, tamletcont, intlincont, marinfcont, marsupcont, titcont, piepagcont, ".
				"		tamletpiecont, arcrtfcont ".
				"  FROM srh_defcontrato ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codcont = '".$as_codcont."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_configuracion_contrato  ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_configuracion_contrato
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_contratos_personal($as_nroregdes,$as_nroreghas,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_contratos_personal
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_nroregdes // Código de contrato donde se empieza a filtrar
		//	  			   as_nroreghas // Código de contrato donde se termina de filtrar
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del personal
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 30/06/2008 								Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_criterioperiodo="";
		if(!empty($as_codperdes))
		{
			$ls_criterio= " AND srh_contratos.nroreg>='".$as_nroregdes."'";
		}
		if(!empty($as_codperhas))
		{
			$ls_criterio= $ls_criterio." AND srh_contratos.nroreg<='".$as_nroreghas."'";
		}
		
		$ls_sql="SELECT  srh_contratos.*, sno_profesion.despro, srh_tipocontratos.dentipcon,  ".
				"		sno_unidadadmin.desuniadm, ".
				"       (SELECT denasicar FROM sno_asignacioncargo ".
				"   	     WHERE srh_contratos.codemp='".$this->ls_codemp."' ".					  
				"            AND srh_contratos.codemp = sno_asignacioncargo.codemp ".
				"		     AND srh_contratos.codnom = sno_asignacioncargo.codnom ".
				"            AND srh_contratos.codcar = sno_asignacioncargo.codasicar) as descar1, ".
				"       (SELECT descar FROM sno_cargo ".
				"   	     WHERE srh_contratos.codemp='".$this->ls_codemp."' ".
				"		     AND srh_contratos.codemp = sno_cargo.codemp ".
				"		     AND srh_contratos.codnom = sno_cargo.codnom ".
				"            AND srh_contratos.codcar = sno_cargo.codcar) as descar2 ".				
				"  FROM sno_profesion, sno_unidadadmin, ".
				"       srh_contratos,  srh_tipocontratos ".
				" WHERE srh_contratos.codemp = '".$this->ls_codemp."' ".
				$ls_criterio.
				"   AND sno_profesion.codemp = srh_contratos.codemp ".
				"   AND sno_profesion.codpro = srh_contratos.codpro ".						
				"   AND srh_contratos.codemp = sno_unidadadmin.codemp ".
				"   AND srh_contratos.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND srh_contratos.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND srh_contratos.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND srh_contratos.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND srh_contratos.prouniadm = sno_unidadadmin.prouniadm ".				
				"   AND srh_contratos.codemp = srh_tipocontratos.codemp ".
				"   AND srh_contratos.codtipcon = srh_tipocontratos.codtipcon ".				
				" ORDER BY srh_contratos.codper ";
				
						
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_contratos_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
	
		return $lb_valido;
	}// end function uf_constanciatrabajo_personal
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_select_monto_bono_merito ($as_escala,$as_promedio,&$as_monto)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_monto_bono_merito
		//         Access: public  
		//	    Arguments: $as_codper//  código del personal
		//                 $as_tipoper // tipo del personal (Interno o Externo)
		//    Description: función que busca el tipo de una persona en la tabla srh_personal concurso
		// Creado por: María Beatriz Unda
		// Fecha Creación: 02/06/2008									Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		 $ls_sql="SELECT  monbs
	             FROM srh_dt_puntosunitri ".
				 " WHERE prompun = ".$as_promedio." AND codpun = '".$as_escala."'   ";   
	 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_tipo_persona_concurso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
       else
	   {
	     	$row=$this->io_sql->fetch_row($rs_data);
			$as_monto=trim ($row["monbs"]);
	   
	   }

}// end uf_select_monto_bono_merito

	//-----------------------------------------------------------------------------------------------------------------------------------	
	
  function uf_select_deteccion_necesidad_adiestramiento($as_nroreg)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dateccion_necesidad_adiestramiento
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_nroreg // Número del registro de detección de necesidades de adiestramiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del registro de detección de necesidades de adiestramiento
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/08/2008 								Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		
		$ls_sql="SELECT * ".
				"  FROM srh_necesidad_adiestramiento ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND nroreg = '".$as_nroreg."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dateccion_necesidad_adiestramiento  ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_dateccion_necesidad_adiestramiento
	//-----------------------------------------------------------------------------------------------------------------------------------
 function uf_select_registro_persona_deteccion_adiestramiento($as_codper)
	{
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_registro_persona_eval_eficiencia
		//         Access: public  
		//	    Arguments: as_nroeval    //  Número de Registro de la Detección de Necesidades de Adiestramiento
		//                 as_codper   // Código del Personal	
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tiene evaluacion de eficiencia
		// Fecha Creación: 15/04/2008									Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_gestor = $_SESSION["ls_gestor"];
      
        
        switch ($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena="CONCAT(sno_personal.nomper,' ',sno_personal.apeper)";
				break;
			case "POSTGRES":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
			case "INFORMIX":
				$ls_cadena="sno_personal.nomper||' '||sno_personal.apeper";
				break;
		}
		
	    $ls_sql="SELECT sno_personalnomina.codper,sno_personal.nivacaper,
				(SELECT denasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as denasicar,
				(SELECT codasicar FROM sno_asignacioncargo 
				WHERE sno_personalnomina.codemp = sno_asignacioncargo.codemp 
				AND sno_personalnomina.codnom = sno_asignacioncargo.codnom 
				AND sno_personalnomina.codasicar = sno_asignacioncargo.codasicar) as codasicar,
				(SELECT descar FROM sno_cargo 
				WHERE sno_personalnomina.codemp = sno_cargo.codemp 
				AND sno_personalnomina.codnom = sno_cargo.codnom 
				AND sno_personalnomina.codcar = sno_cargo.codcar) as descargo,
				(SELECT codcar FROM sno_cargo 
				WHERE sno_personalnomina.codemp = sno_cargo.codemp 
				AND sno_personalnomina.codnom = sno_cargo.codnom 
				AND sno_personalnomina.codcar = sno_cargo.codcar) as codcargo,
				(SELECT desuniadm FROM sno_unidadadmin
				WHERE sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm 
				AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm 
				AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm 
				AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm 
				AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ) as desuniadm,
				(SELECT ".$ls_cadena." FROM sno_personal 
				WHERE sno_personalnomina.codper=sno_personal.codper AND sno_personalnomina.codper='".$as_codper."')
				as nombre, 
				(SELECT cedper FROM sno_personal 
				WHERE sno_personalnomina.codper=sno_personal.codper AND sno_personalnomina.codper='".$as_codper."' )
				as cedper
				FROM sno_personalnomina, sno_nomina, sno_personal, srh_persona_evaluacion_eficiencia
				WHERE sno_personal.codper='".$as_codper."'
				AND  sno_personal.codper=sno_personalnomina.codper
				AND sno_nomina.codnom = sno_personalnomina.codnom 
				AND sno_nomina.espnom='0'				
				AND  staper ='1'"; 

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_registro_persona_eval_eficiencia ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_detalle2->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_select_registro_persona_eval_eficiencia
	//-----------------------------------------------------------------------------------------------------------------------------------	
	
  function uf_select_causas_adiestramiento ($as_nroreg,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_dateccion_necesidad_adiestramiento
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_nroreg // Número del registro de detección de necesidades de adiestramiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del causas de detección de necesidades de adiestramiento
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/08/2008 								Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		
		$ls_sql="SELECT dencauadi ".
				"  FROM srh_dt_causas_adiestramiento, srh_causas_adiestramiento ".
				" WHERE srh_dt_causas_adiestramiento.codemp = '".$this->ls_codemp."' ".
				"   AND nroreg = '".$as_nroreg."' ".
				" AND srh_dt_causas_adiestramiento.codcauadi = srh_causas_adiestramiento.codcauadi";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_dateccion_necesidad_adiestramiento  ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
				
		return $lb_valido;
	}// end function uf_select_dateccion_necesidad_adiestramiento
	//-----------------------------------------------------------------------------------------------------------------------------------
	
  function uf_select_competencias_adiestramiento  ($as_nroreg,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_competencias_adiestramiento 
		//         Access: public (desde la clase sigesp_snorh_rpp_constanciatrabajo)  
		//	    Arguments: as_nroreg // Número del registro de detección de necesidades de adiestramiento
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las competencias de detección de necesidades de adiestramiento
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 04/08/2008 								Fecha Última Modificación :  		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$lb_valido=true;
		
		$ls_sql="SELECT srh_dt_competencias_adiestramiento.codcompadi, dencompadi, prioridad ".
				"  FROM srh_dt_competencias_adiestramiento, srh_competencias_adiestramiento".
				" WHERE srh_dt_competencias_adiestramiento.codemp = '".$this->ls_codemp."' ".
				"   AND nroreg = '".$as_nroreg."' ".
				" AND srh_dt_competencias_adiestramiento.codcompadi = srh_competencias_adiestramiento.codcompadi";
			
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_competencias_adiestramiento   ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
				
		return $lb_valido;
	}// end function uf_select_competencias_adiestramiento 
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	
	
} // fin de la clase sigesp_srh_class_report

?>