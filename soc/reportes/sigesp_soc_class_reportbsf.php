<?php
class sigesp_soc_class_reportbsf
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_soc_class_reportbsf()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_soc_class_reportbsf
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yozelin Barragan /Ing. Nestor Falcon /Ing. Laura Cabre
		// Fecha Creación: 18/06/2007. 								
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/class_funciones.php");
						
		$io_include         = new sigesp_include();
		$this->io_conexion  = $io_include->uf_conectar();
		$this->io_sql       = new class_sql($this->io_conexion);	
		$this->io_mensajes  = new class_mensajes();		
		$this->io_funciones = new class_funciones();		
        $this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$this->ls_codusu    = $_SESSION["la_logusr"];
	}// end function sigesp_soc_class_reportbsf
	//-----------------------------------------------------------------------------------------------------------------------------------
	
function uf_load_cabecera_formato_solicitud_cotizacion($as_numsolcot,$as_tipsolcot,$as_fecsolcot,$as_tabla,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_cabecera_formato_solicitud_cotizacion
//		   Access:  public
//		 Argument: 
//   $as_numsolcot  //Número de la Solicitud de Cotización.
//   $as_tipsolcot  //Tipo de la Solicitud de Cotización.
//       $as_tabla  //Nombre de la tabla detalle de la Solicitud de Cotización.
//      $lb_valido  //Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	  Description:  Función que busca los datos de la cabecera de la Solicitud de Cotizacion.
//	   Creado Por:  Ing. Nestor Falcon.
// Fecha Creación:  18/06/2007								Fecha Última Modificación : 19/06/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido    = true;
  $ls_fecsolcot = $this->io_funciones->uf_convertirdatetobd($as_fecsolcot);
  
  $ls_sql = "SELECT $as_tabla.cod_pro, 
   					max(rpc_proveedor.nompro) as nompro, 
                    max(rpc_proveedor.dirpro) as dirpro, 
					max(rpc_proveedor.telpro) as telpro, 
					max(rpc_proveedor.rifpro) as rifpro, 
					max(rpc_proveedor.email) as email, 
					max(soc_sol_cotizacion.fecsol) as fecsol, 
					max(soc_sol_cotizacion.obssol) as obssol 
			   FROM soc_sol_cotizacion, $as_tabla, rpc_proveedor
			  WHERE $as_tabla.codemp='".$this->ls_codemp."'
			    AND $as_tabla.numsolcot='".$as_numsolcot."'
				AND soc_sol_cotizacion.tipsolcot='".$as_tipsolcot."'
				AND soc_sol_cotizacion.fecsol='".$ls_fecsolcot."'
			    AND soc_sol_cotizacion.codemp=$as_tabla.codemp
			    AND soc_sol_cotizacion.numsolcot=$as_tabla.numsolcot
			    AND soc_sol_cotizacion.codemp=rpc_proveedor.codemp
				AND $as_tabla.codemp=rpc_proveedor.codemp
			    AND $as_tabla.cod_pro=rpc_proveedor.cod_pro
			  GROUP BY $as_tabla.cod_pro";
  
  $rs_data = $this->io_sql->select($ls_sql);//print $ls_sql;
  if ($rs_data===false)
     { 
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_cabecera_formato_solicitud_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }			  
  return $rs_data;
}//function uf_load_cabecera_formato_solicitud_cotizacion
	
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_load_dt_solicitud_cotizacion($as_numsolcot,$as_codpro,$as_tabla,$as_table,$as_campo,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_dt_solicitud_cotizacion
//		   Access:  public
//		 Argument: 
//  $as_numsolcot  //Número de la Solicitud de Cotización.
//     $as_codpro  //Código del Proveedor asociado a esa Solicitud de Cotización.
//      $as_tabla  //Nombre de la Tabla donde se localizara el detalle de la Solicitud de Cotización,
//                   soc_dtsc_bienes para solicitus de bienes, soc_dtsc_servicios para servicios.
//      $as_table  //Nombre de la tabla de donde extraeremos la denominacion del Item, siv_articulo para los bienes y
//                   soc_servicios para los servicios. 
//      $as_campo  //Campo para el enlace del item con su tabla maestro, codart para bienes y codser para servicios.
//     $lb_valido  //Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	  Description: Función que busca los detalles una Solicitud de Cotización para un proveedor en particular.
//	   Creado Por: Ing. Nestor Falcon.
// Fecha Creación: 19/06/2007								Fecha Última Modificación : 19/06/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_straux = "";
  if ($as_tabla=='soc_dtsc_bienes')
     {
	   $ls_straux = " $as_tabla.$as_campo as codite,max($as_table.denart) as denite,max($as_tabla.canart) as canite";
	 }
  elseif($as_tabla=='soc_dtsc_servicios')
     {
	   $ls_straux = " $as_tabla.$as_campo as codite,max($as_table.denser) as denite,max($as_tabla.canser) as canite";
	 }
  $ls_sql = "SELECT $ls_straux
			   FROM soc_sol_cotizacion, $as_tabla, $as_table
			  WHERE $as_tabla.codemp='".$this->ls_codemp."'
			    AND $as_tabla.numsolcot='".$as_numsolcot."'
			    AND $as_tabla.cod_pro='".$as_codpro."'
			    AND soc_sol_cotizacion.codemp=$as_tabla.codemp
			    AND soc_sol_cotizacion.numsolcot=$as_tabla.numsolcot
			    AND $as_tabla.$as_campo=$as_table.$as_campo 
			  GROUP BY $as_tabla.$as_campo, $as_tabla.orden
	 		  ORDER BY $as_tabla.orden";
  $rs_data = $this->io_sql->select($ls_sql);//print $ls_sql;
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_dt_solicitud_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }			  
  return $rs_data;
}//function uf_load_dt_solicitud_cotizacion.
	
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_load_cabecera_formato_registro_cotizacion($as_numcot,$as_tipcot,$as_feccot,$as_codpro,$as_tabla,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_cabecera_formato_registro_cotizacion
//		   Access:  public
//		 Argument: 
//   $ls_numsolcot  //Número de la Cotización.
//   $ls_tipsolcot  //Tipo de la Cotización.
//      $lb_valido  //Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	  Description:  Función que busca los datos de la cabecera de la Cotizacion.
//	   Creado Por:  Ing. Nestor Falcon.
// Fecha Creación:  20/06/2007								Fecha Última Modificación : 20/06/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_feccot = $this->io_funciones->uf_convertirdatetobd($as_feccot);

  $ls_sql = "SELECT $as_tabla.cod_pro, 
   					max(rpc_proveedor.nompro) as nompro, 
                    max(rpc_proveedor.dirpro) as dirpro, 
					max(rpc_proveedor.telpro) as telpro,
					max(rpc_proveedor.rifpro) as rifpro, 
					max(rpc_proveedor.email) as email,
					max(soc_cotizacion.feccot) as feccot,
					max(soc_cotizacion.obscot) as obscot,
					max(soc_cotizacion.numsolcot) as numsolcot,
					max(soc_cotizacion.monsubtotaux) as monsubtot,
					max(soc_cotizacion.monimpcotaux) as monimpcot,
					max(soc_cotizacion.montotcotaux) as montotcot,
					max(soc_cotizacion.diaentcom) as diaentcom
			   FROM soc_cotizacion, $as_tabla, rpc_proveedor
			  WHERE $as_tabla.codemp='".$this->ls_codemp."'
			    AND $as_tabla.numcot='".$as_numcot."'
                AND soc_cotizacion.cod_pro='".$as_codpro."'				
				AND soc_cotizacion.tipcot='".$as_tipcot."'
				AND soc_cotizacion.feccot='".$ls_feccot."'
			    AND soc_cotizacion.codemp=$as_tabla.codemp
			    AND soc_cotizacion.numcot=$as_tabla.numcot
			    AND soc_cotizacion.codemp=rpc_proveedor.codemp
				AND $as_tabla.codemp=rpc_proveedor.codemp
			    AND $as_tabla.cod_pro=rpc_proveedor.cod_pro
			  GROUP BY $as_tabla.cod_pro";
  
  $rs_data = $this->io_sql->select($ls_sql);//print $ls_sql;
  if ($rs_data===false)
     { 
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_cabecera_formato_solicitud_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }			  
  return $rs_data;
}//function uf_load_cabecera_formato_registro_cotizacion
	
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_load_dt_registro_cotizacion($as_numcot,$as_codpro,$as_tabla,$as_table,$as_campo,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_dt_registro_cotizacion
//		   Access:  public
//		 Argument: 
//  $as_numsolcot  //Número de la Solicitud de Cotización.
//     $as_codpro  //Código del Proveedor asociado a esa Solicitud de Cotización.
//      $as_tabla  //Nombre de la Tabla donde se localizara el detalle de la Solicitud de Cotización,
//                   soc_dtsc_bienes para solicitus de bienes, soc_dtsc_servicios para servicios.
//      $as_table  //Nombre de la tabla de donde extraeremos la denominacion del Item, siv_articulo para los bienes y
//                   soc_servicios para los servicios. 
//      $as_campo  //Campo para el enlace del item con su tabla maestro, codart para bienes y codser para servicios.
//     $lb_valido  //Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	  Description: Función que busca los detalles una Solicitud de Cotización para un proveedor en particular.
//	   Creado Por: Ing. Nestor Falcon.
// Fecha Creación: 19/06/2007								Fecha Última Modificación : 19/06/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_straux = "";
  if ($as_tabla=='soc_dtcot_bienes')
     {
	   $ls_straux = " $as_tabla.$as_campo as codite,$as_table.denart as denite,$as_tabla.canart as canite, 
	                  $as_tabla.preuniartaux as preite, $as_tabla.monsubartaux as subite, $as_tabla.montotartaux as totite";
	 }
  elseif($as_tabla=='soc_dtcot_servicio')
     {
	   $ls_straux = " $as_tabla.$as_campo as codite,$as_table.denser as denite,$as_tabla.canser as canite,
	                  $as_tabla.monuniseraux as preite, $as_tabla.monsubseraux as subite, $as_tabla.montotseraux as totite";
	 }
  $ls_sql = "SELECT $ls_straux
			   FROM soc_cotizacion, $as_tabla, $as_table
			  WHERE $as_tabla.codemp='".$this->ls_codemp."'
			    AND $as_tabla.numcot='".$as_numcot."'
			    AND $as_tabla.cod_pro='".$as_codpro."'
			    AND soc_cotizacion.codemp=$as_tabla.codemp
			    AND soc_cotizacion.numcot=$as_tabla.numcot
			    AND $as_tabla.$as_campo=$as_table.$as_campo 
	 		  ORDER BY $as_tabla.orden";
			  
  $rs_data = $this->io_sql->select($ls_sql);//print $ls_sql;
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_dt_registro_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }			  
  return $rs_data;
}//function uf_load_dt_registro_cotizacion.
	
	function uf_select_orden_imprimir($as_numordcom,$as_estcondat,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_orden_imprimir
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca una orden de compra para imprimir
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		
		$ls_sql=" SELECT soc_ordencompra.numordcom, soc_ordencompra.estcondat, soc_ordencompra.cod_pro,".
				"        soc_ordencompra.codfuefin, soc_ordencompra.fecordcom,  soc_ordencompra.obscom, soc_ordencompra.obsordcom,".
				"        soc_ordencompra.monsubtotaux as monsubtot, soc_ordencompra.monimpaux as monimp, soc_ordencompra.montotaux as montot, ".
			       "	  soc_ordencompra.coduniadm, soc_ordencompra.forpagcom, soc_ordencompra.diaplacom, spg_unidadadministrativa.denuniadm,  ".
				"        soc_ordencompra.lugentnomdep, soc_ordencompra.lugentdir, soc_ordencompra.concom, soc_ordencompra.montotdiv,".
				"        soc_ordencompra.fecent,soc_ordencompra.estlugcom,soc_ordencompra.codmon,soc_ordencompra.codpai,".
				"        soc_ordencompra.codest,soc_ordencompra.codmun,soc_ordencompra.codpar,soc_ordencompra.estsegcom,".
				"        soc_ordencompra.porsegcom,soc_ordencompra.monsegcom,soc_ordencompra.monant, soc_ordencompra.tascamordcom, ".
				"        rpc_proveedor.nompro,rpc_proveedor.dirpro,rpc_proveedor.rifpro,rpc_proveedor.nitpro,rpc_proveedor.telpro,".
                           "        rpc_proveedor.nomreppro, rpc_proveedor.faxpro,".
                           "        (SELECT denfuefin".
				"          FROM sigesp_fuentefinanciamiento".
				"         WHERE sigesp_fuentefinanciamiento.codfuefin<>'--' AND ".
				"		        sigesp_fuentefinanciamiento.codemp=soc_ordencompra.codemp AND ".
				"			    sigesp_fuentefinanciamiento.codfuefin=soc_ordencompra.codfuefin) AS denfuefin ".
				" FROM  soc_ordencompra,spg_unidadadministrativa, rpc_proveedor ".
				" WHERE soc_ordencompra.codemp='".$this->ls_codemp."' AND ".
				"       soc_ordencompra.numordcom='".$as_numordcom."' AND ".
				"       soc_ordencompra.estcondat='".$as_estcondat."' ".
				"    AND soc_ordencompra.codemp=rpc_proveedor.codemp 				  ".
				"    AND soc_ordencompra.cod_pro=rpc_proveedor.cod_pro  			  ".
				"    AND soc_ordencompra.codemp=spg_unidadadministrativa.codemp 	  ".
				"    AND soc_ordencompra.coduniadm=spg_unidadadministrativa.coduniadm ";//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_orden_imprimir ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_solicitud
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_soc_sep($as_codemp,$as_numordcom,$as_estcondat)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_soc_sep
		//         Access: public 
		//	    Arguments: as_numordcom   // Orden de Compra a imprimir
		//                 $as_estcondat  // tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: Funcion que verifica si existe una SEP asociada a la orden de compra
		//	   Creado Por: Ing. Luis Anibal Lang                       
		// Fecha Creación: 18/09/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;	
		$this->ds_soc_sep = new class_datastore();
		$ls_sql="SELECT soc_enlace_sep.numordcom,soc_enlace_sep.numsol,sep_solicitud.coduniadm,".
				"       (SELECT spg_unidadadministrativa.denuniadm".
				"          FROM spg_unidadadministrativa".
				"         WHERE spg_unidadadministrativa.codemp=sep_solicitud.codemp".
				"           AND spg_unidadadministrativa.coduniadm=sep_solicitud.coduniadm) AS denuniadm".
				"  FROM soc_enlace_sep,sep_solicitud".
				" WHERE soc_enlace_sep.codemp='".$this->ls_codemp."'".
				"   AND soc_enlace_sep.numordcom='".$as_numordcom."'".
				"   AND estcondat='".$as_estcondat."'".
				"   AND sep_solicitud.codemp=soc_enlace_sep.codemp".
				"   AND sep_solicitud.numsol=soc_enlace_sep.numsol";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_numrows=$this->io_sql->num_rows($rs_data);	   	  
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_soc_sep ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->ds_soc_sep->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end 	function uf_select_soc_sep
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_detalle_orden_imprimir($as_numordcom,$as_estcondat,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_detalle_orden_imprimir
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los detalles de la  orden de compra para imprimir
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		switch ($as_estcondat)
		{
		   case 'B':
               $ls_sql=" SELECT soc_dt_bienes.numordcom, soc_dt_bienes.codart as codartser, siv_articulo.denart as denartser, ".
       				   "        soc_dt_bienes.canart as cantartser, soc_dt_bienes.unidad, (soc_dt_bienes.preuniartaux) as preartser,   ".
       				   "	    (soc_dt_bienes.monsubartaux) as montsubartser, (soc_dt_bienes.montotartaux) as monttotartser, siv_articulo.spg_cuenta, ".
					   "        soc_dt_bienes.orden, siv_articulo.codunimed, siv_unidadmedida.denunimed, ".
					   "        soc_ordencompra.fecordcom ".
					   " FROM   soc_ordencompra , soc_dt_bienes , siv_articulo, siv_unidadmedida ".
					   " WHERE  soc_dt_bienes.codemp='".$this->ls_codemp."' AND ".
           		       "        soc_dt_bienes.numordcom='".$as_numordcom."' AND ".
					   "        soc_dt_bienes.estcondat='".$as_estcondat."' AND ".
					   "		soc_dt_bienes.codemp=soc_ordencompra.codemp AND ".
					   "		soc_dt_bienes.codemp=siv_articulo.codemp AND ".
					   "		siv_articulo.codemp=soc_ordencompra.codemp AND ".
					   "		soc_dt_bienes.numordcom=soc_ordencompra.numordcom AND ".
					   "		soc_dt_bienes.estcondat=soc_ordencompra.estcondat AND ".
					   " 		soc_dt_bienes.codart=siv_articulo.codart  AND ".
       				   "	    siv_unidadmedida.codunimed=siv_articulo.codunimed ".
				       "		ORDER BY soc_dt_bienes.orden ASC ";
		  break;
		   
		  case 'S':
		       $ls_sql=" SELECT soc_dt_servicio.numordcom, soc_dt_servicio.codser as codartser, soc_servicios.denser as denartser , ".
			   		   "        soc_dt_servicio.canser as cantartser, (soc_dt_servicio.monuniseraux) as preartser, ".
					   "        (soc_dt_servicio.montotseraux) as monttotartser, ".
					   "        (soc_dt_servicio.monsubseraux) as montsubartser, soc_servicios.spg_cuenta, soc_dt_servicio.orden, ".
					   "        soc_ordencompra.fecordcom ".
					   " FROM   soc_ordencompra , soc_dt_servicio , soc_servicios ".
					   " WHERE  soc_dt_servicio.codemp='".$this->ls_codemp."' AND ".
					   "	    soc_dt_servicio.numordcom='".$as_numordcom."' AND ".
					   "	    soc_dt_servicio.estcondat='".$as_estcondat."' AND ".
					   "		soc_dt_servicio.codemp=soc_ordencompra.codemp AND ".
					   "		soc_dt_servicio.codemp=soc_servicios.codemp AND ".
					   "	    soc_servicios.codemp=soc_ordencompra.codemp AND ".
					   "	    soc_dt_servicio.numordcom=soc_ordencompra.numordcom AND ".
					   "		soc_dt_servicio.estcondat=soc_ordencompra.estcondat AND ".
					   "	    soc_dt_servicio.codser=soc_servicios.codser ".
					   " ORDER BY soc_dt_servicio.orden ASC ";
		  break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_detalle_orden_imprimir ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_detalle_orden_imprimir
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cuenta_gasto($as_numordcom,$as_estcondat,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_cuenta_gasto
		//         Access: public 
		//	    Arguments: as_numordcom    ---> Orden de Compra a imprimir
		//                 $as_estcondat  ---< tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca las cuenats de gastos de la  orden de compra para imprimir
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 21/06/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql=" SELECT numordcom,codestpro1,codestpro2,codestpro3,".
		 	    "        codestpro4,codestpro5,spg_cuenta,montoaux AS monto     ".
				" FROM   soc_cuentagasto                            ".
				" WHERE  codemp='".$this->ls_codemp."'  AND         ".
				"        numordcom='".$as_numordcom."'  AND         ".
				"        estcondat='".$as_estcondat."'              ";    
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_cuenta_gasto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $rs_data;
	}// end function uf_select_cuenta_gasto
	//-----------------------------------------------------------------------------------------------------------------------------------

   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_denominacionspg($as_cuenta,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_sep_select_denominacion_unidad_medida
		//		   Access: private 
		//	    Arguments: as_cuenta //codigo de la cuenta
		//	   			   as_denominacion // denominacion de la cuenta
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT denominacion ".
				 " FROM   spg_cuentas ".
				 " WHERE  codemp='".$this->ls_codemp."'  AND  spg_cuenta='".$as_cuenta."' ";       
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_denominacionspg ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_denominacion=$row["denominacion"];     
				$lb_valido=true;
			 }	
			$this->io_sql->free_result($rs);
		 } 
		 return $lb_valido;    
	}//fin 	uf_select_denominacionspg
   //---------------------------------------------------------------------------------------------------------------------------------	

   //---------------------------------------------------------------------------------------------------------------------------------------
		function uf_select_items($as_numanacot,$as_tipsolcot,&$la_items)
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_select_items
			//		   Access: public
			//		  return : arreglo que contiene los items que participaron en un determinado analisis 
			//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis
			//	   Creado Por: Ing. Laura Cabré
			// 			Fecha: 17/07/2007								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$la_items=array();
			$lb_valido=false;
			if($as_tipsolcot=="B")
			{				
				$ls_sql="SELECT d.codart as codigo, a.denart as denominacion, p.nompro, dt.canart as cantidad, dt.preuniartaux as precio, dt.monivaaux as moniva ,dt.montotartaux monto,
						d.obsanacot, d.numcot, d.cod_pro,d.obsanacot
						FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt
						WHERE
						d.codemp='$this->ls_codemp' AND d.numanacot='$as_numanacot' AND d.codemp=a.codemp AND a.codemp=p.codemp AND p.codemp=dt.codemp AND
						d.codart=a.codart AND d.cod_pro=p.cod_pro AND d.numcot=dt.numcot AND d.cod_pro=dt.cod_pro AND d.codart=dt.codart";				
			}
			else
			{
					$ls_sql="SELECT d.codser as codigo, a.denser as denominacion, p.nompro, dt.canser as cantidad, dt.monuniseraux as precio, dt.monivaaux as moniva,dt.montotseraux monto,
						d.obsanacot, d.numcot, d.cod_pro,d.obsanacot
						FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt
						WHERE
						d.codemp='$this->ls_codemp' AND d.numanacot='$as_numanacot' AND d.codemp=a.codemp AND a.codemp=p.codemp AND p.codemp=dt.codemp AND
						d.codser=a.codser AND d.cod_pro=p.cod_pro AND d.numcot=dt.numcot AND d.cod_pro=dt.cod_pro AND d.codser=dt.codser";				
			}
			
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$io_mensajes->message("ERROR->uf_select_items".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;	
			}
			else
			{
				$li_i=0;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$li_i++;
					$la_items[$li_i]=$row;	
					$lb_valido=true;		
				}																
			}
			return $lb_valido;
		}
	//---------------------------------------------------------------------------------------------------------------------------------------	
	
	//---------------------------------------------------------------------------------------------------------------------------------------
		function uf_cargar_cotizaciones($as_numanacot, &$aa_proveedores)
		{
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//	     Function: uf_cargar_cotizaciones
			//		   Access: public
			//     Parameters: $as_numanacot--->numero del analisis de cotizacion
			//		  return : arreglo que contiene las cotizaciones que participaron en un determinado analisis 
			//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
			//	   Creado Por: Ing. Laura Cabré
			// 			  Fecha: 18/06/2007								Fecha Última Modificación : 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$aa_proveedores=array();
			$lb_valido=false;
			$ls_sql= "SELECT a.numsolcot, cxa.numcot, c.feccot, (c.montotcotaux) AS montotcot,c.poriva,p.nompro
					  FROM soc_analisicotizacion a, soc_cotizacion c, rpc_proveedor p, soc_cotxanalisis cxa
					  WHERE a.codemp='$this->ls_codemp' AND a.numanacot='$as_numanacot'
					  AND a.codemp=c.codemp AND cxa.cod_pro=c.cod_pro AND cxa.numcot=c.numcot
					  AND a.codemp=p.codemp AND cxa.cod_pro=p.cod_pro
					  AND a.codemp=cxa.codemp and a.numanacot=cxa.numanacot";		
			
				$rs_data=$this->io_sql->select($ls_sql);
				if($rs_data===false)
				{
					$this->io_mensajes->message("ERROR->uf_cargar_cotizaciones".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
					$lb_valido=false;	
				}
				else
				{
					$li_i=0;
					while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
					{
						$li_i++;
						$aa_proveedores[$li_i]=$row;	
						$lb_valido=true;				
					}																
				}
			return $lb_valido;
		}//fin de uf_cargar_cotizaciones
	//---------------------------------------------------------------------------------------------------------------------------------------	
	
	//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_analisis_cotizaciones($as_anacotdes,$as_anacothas,$as_codprodes,$as_codprohas,
											$as_fecanades,$as_fecanahas,$as_tipanacot,&$aa_cotizaciones)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_analisis_cotizaciones
		//		   Access: public
		//	   Parametros:	
		//		        $as_anacotdes  //Numero de solicitud de Cotización a partir del cual realizaremos la búsqueda.
		//				$as_anacothas  //Numero de solicitud de Cotización hasta el cual realizaremos la búsqueda.
		//				$as_codprodes  //Código del Proveedor a partir del cual realizaremos la búsqueda.
		//				$as_codprohas  //Código del Proveedor hasta el cual realizaremos la búsqueda.
		//				$as_fecanades  //Fecha de la Solicitud de Cotización a partir del cual realizaremos la búsqueda.
		//				$as_fecanahas  //Fecha de la Solicitud de Cotización hasta el cual realizaremos la búsqueda.
		//				$as_tipsolcot  //Tipo de la Solicitud de Cotización B=Bienes, S=Servicios.
		//		  return : arreglo que contiene los analisis de cotizacion filtrados segun parametros de busqueda
		//	  Description: Metodo que  devuelve los analisis de cotizacion filtrados segun parametros de busqueda
		//	   Creado Por: Ing. Laura Cabré
		// 			Fecha: 23/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_cotizaciones=array();
		$lb_valido=false;
		$ls_straux = "";
			if (!empty($as_tipanacot))
			 {
			   $ls_straux = $ls_straux." AND a.tipsolcot='".$as_tipanacot."'";
			 } 	
		  if (!empty($as_anacotdes))
			 {
			   $ls_straux = $ls_straux. " AND a.numanacot>='".$as_anacotdes."'";
			 }
		  if (!empty($as_anacothas))
			 {
			   $ls_straux = $ls_straux. " AND a.numanacot<='".$as_anacothas."' ";
			 }		 
		  if (!empty($as_codprodes))
			 {
			   $ls_straux = $ls_straux. " AND a.codemp=c.codemp AND a.numanacot=c.numanacot AND c.cod_pro>='$as_codprodes'";
			 }	
		  if (!empty($as_codprohas))
			 {
			   $ls_straux = $ls_straux. " AND a.codemp=c.codemp AND a.numanacot=c.numanacot AND c.cod_pro<='$as_codprohas'";
			 }		  
		  if (!empty($as_fecanades))
			 {
			   $ls_fecanades = $this->io_funciones->uf_convertirdatetobd($as_fecanades);
			   $ls_straux = $ls_straux. " AND a.fecanacot>='".$ls_fecanades."'";
			 }
		  if (!empty($as_fecanahas))
			 {
			   $ls_fecanahas= $this->io_funciones->uf_convertirdatetobd($as_fecanahas); 
			   $ls_straux = $ls_straux. " AND a.fecanacot<='".$ls_fecanahas."' ";
			 }
		$ls_sql= "SELECT DISTINCT a.numanacot, a.fecanacot, a.obsana, a.tipsolcot 
				FROM soc_analisicotizacion a, soc_cotxanalisis c
				WHERE a.codemp='$this->ls_codemp'".$ls_straux;

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_analisis_cotizaciones".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$aa_cotizaciones[$li_i]["numero"]=$row["numanacot"];
				$aa_cotizaciones[$li_i]["fecha"]=$this->io_funciones->uf_convertirfecmostrar($row["fecanacot"]);					
				$aa_cotizaciones[$li_i]["observacion"]=$row["obsana"];
				if($row["tipsolcot"]=="B")
					$aa_cotizaciones[$li_i]["tipo"]="Bienes";
				else
					$aa_cotizaciones[$li_i]["tipo"]="Servicios";
				$li_i++;
				$lb_valido=true;
			}																
		}
		return $lb_valido;
	}//fin de uf_select_analisis_cotizaciones
//---------------------------------------------------------------------------------------------------------------------------------------	

function uf_load_solicitudes_cotizacion($as_solcotdes,$as_solcothas,$as_codprodes,$as_codprohas,
                                        $as_numsepdes,$as_numsephas,$as_fecsoldes,$as_fecsolhas,$as_tipsolcot,$as_estsolcot,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_solicitudes_cotizacion
//		   Access:  public
//		 Argument: 
//  $ls_solcotdes  //Numero de solicitud de Cotización a partir del cual realizaremos la búsqueda.
//  $ls_solcothas  //Numero de solicitud de Cotización hasta el cual realizaremos la búsqueda.
//  $ls_codprodes  //Código del Proveedor a partir del cual realizaremos la búsqueda.
//  $ls_codprohas  //Código del Proveedor hasta el cual realizaremos la búsqueda.
//  $ls_numsepdes  //Numero de Solicitud de Ejecucion Presupuestaria a partir del cual realizaremos la búsqueda.
//  $ls_numsephas  //Numero de Solicitud de Ejecucion Presupuestaria hasta el cual realizaremos la búsqueda.
//  $ls_fecsoldes  //Fecha de la Solicitud de Cotización a partir del cual realizaremos la búsqueda.
//  $ls_fecsolhas  //Fecha de la Solicitud de Cotización hasta el cual realizaremos la búsqueda.
//  $ls_tipsolcot  //Tipo de la Solicitud de Cotización B=Bienes, S=Servicios.
//  $ls_estsolcot  //Estatus de la Solicitud de Cotización.
//     $lb_valido  //Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	  Description: Función que obtiene las Solicitudes de Cotización según se especifiquen los parametros de búsqueda.
//	   Creado Por: Ing. Nestor Falcon.
// Fecha Creación: 21/06/2007								Fecha Última Modificación : 21/06/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido = true;
  $ls_straux = "";
  $ls_tabla = "";
  if (!empty($as_tipsolcot) && ($as_tipsolcot!='-'))
     {
	   $ls_straux = $ls_straux." AND soc_sol_cotizacion.tipsolcot='".$as_tipsolcot."'";
	 } 

  if (!empty($as_solcotdes) && !empty($as_solcothas))
     {
	   $ls_straux = $ls_straux." AND soc_sol_cotizacion.numsolcot BETWEEN '".$as_solcotdes."' AND '".$as_solcothas."' ";
	 }
  if (!empty($as_codprodes) && !empty($as_codprohas))
     {
	   if ($as_tipsolcot=='B')
	      {
	        $ls_tabla = ", soc_dtsc_bienes";
			$ls_straux = $ls_straux." AND soc_dtsc_bienes.cod_pro BETWEEN '".$as_codprodes."' AND '".$as_codprohas."'";
		  }
	   elseif($as_tipsolcot=='S')
	      {
	        $ls_tabla  = ", soc_dtsc_servicios";
			$ls_straux = $ls_straux." AND soc_dtsc_servicios.cod_pro BETWEEN '".$as_codprodes."' AND '".$as_codprohas."'";
		  }
	 }
  if (!empty($as_numsepdes) && !empty($as_numsephas))
     {
	   $ls_tabla  = $ls_tabla.", soc_solcotsep";
	   $ls_straux = $ls_straux." AND soc_solcotsep.numsol BETWEEN '".$as_numsepdes."' AND '".$as_numsephas."'";
	 }
  if (!empty($as_fecsoldes) && !empty($as_fecsolhas))
     {
	   $ls_fecsoldes = $this->io_funciones->uf_convertirdatetobd($as_fecsoldes);
	   $ls_fecsolhas = $this->io_funciones->uf_convertirdatetobd($as_fecsolhas); 
	   $ls_straux    = $ls_straux." AND soc_sol_cotizacion.fecsol BETWEEN '".$ls_fecsoldes."' AND '".$ls_fecsolhas."' ";
	 }
  if (!empty($as_estsolcot) && $as_estsolcot!='-')
     {
	   if ($as_estsolcot=='R')
	      {
			$ls_straux = $ls_straux." AND soc_sol_cotizacion.numsolcot NOT IN (SELECT numsolcot FROM soc_cotizacion WHERE codemp='".$this->ls_codemp."')";
		  }
	   elseif($as_estsolcot=='P')
	      {
			$ls_straux = $ls_straux." AND soc_sol_cotizacion.numsolcot IN (SELECT numsolcot FROM soc_cotizacion WHERE codemp='".$this->ls_codemp."')";
		  }
	 }	 

  $ls_sql = "SELECT soc_sol_cotizacion.numsolcot, 
                    max(soc_sol_cotizacion.fecsol) as fecsol, 
					max(soc_sol_cotizacion.obssol) obssol, 
					max(soc_sol_cotizacion.tipsolcot) as tipsolcot 
               FROM soc_sol_cotizacion $ls_tabla
			  WHERE soc_sol_cotizacion.codemp='".$this->ls_codemp."' $ls_straux 
			  GROUP BY soc_sol_cotizacion.numsolcot 
			  ORDER BY soc_sol_cotizacion.numsolcot ASC";//print $ls_sql;
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_solicitudes_cotizacion.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }
  return $rs_data;
}

function uf_load_registro_cotizaciones($as_numcotdes,$as_numcothas,$as_codprodes,$as_codprohas,$as_numsolcotdes,$as_numsolcothas,
	                                   $as_feccotdes,$as_feccothas,$as_tipcot,$as_estcot,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	     Function:  uf_load_registro_cotizaciones
//		   Access:  public
//		 Argument: 
//     $ls_numcotdes  //Numero de Cotización a partir del cual realizaremos la búsqueda.
//     $ls_numcothas  //Numero de Cotización hasta el cual realizaremos la búsqueda.
//     $ls_codprodes  //Código del Proveedor a partir del cual realizaremos la búsqueda.
//     $ls_codprohas  //Código del Proveedor hasta el cual realizaremos la búsqueda.
//  $ls_numsolcotdes  //Numero de Solicitud de Cotizacion a partir del cual realizaremos la búsqueda.
//  $ls_numsolcothas  //Numero de Solicitud de Cotizacion hasta el cual realizaremos la búsqueda.
//     $ls_fecsoldes  //Fecha de la Solicitud de Cotización a partir del cual realizaremos la búsqueda.
//     $ls_fecsolhas  //Fecha de la Solicitud de Cotización hasta el cual realizaremos la búsqueda.
//        $ls_tipcot  //Tipo de la Cotización B=Bienes, S=Servicios.
//        $ls_estcot  //Estatus de Cotización R=Registro, P=Procesada.
//        $lb_valido  //Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	    Description:  Función que obtiene las Solicitudes de Cotización según se especifiquen los parametros de búsqueda.
//	     Creado Por:  Ing. Nestor Falcon.
//   Fecha Creación:  15/07/2007								Fecha Última Modificación : 15/07/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_straux = "";
  $ls_tabla = "";
  if (!empty($as_tipcot) && ($as_tipcot!='-'))
     {
	   $ls_straux = $ls_straux." AND soc_cotizacion.tipcot='".$as_tipcot."'";
	 } 

  if (!empty($as_numcotdes) && !empty($as_numcothas))
     {
	   $ls_straux = $ls_straux." AND soc_cotizacion.numcot BETWEEN '".$as_numcotdes."' AND '".$as_numcothas."' ";
	 }
  if (!empty($as_codprodes) && !empty($as_codprohas))
     {
	   if ($as_tipcot=='B')
	      {
	        $ls_tabla = ", soc_dtcot_bienes";
			$ls_straux = $ls_straux." AND soc_dtcot_bienes.cod_pro BETWEEN '".$as_codprodes."' AND '".$as_codprohas."'";
		  }
	   elseif($as_tipcot=='S')
	      {
	        $ls_tabla  = ", soc_dtcot_servicio";
			$ls_straux = $ls_straux." AND soc_dtcot_servicio.cod_pro BETWEEN '".$as_codprodes."' AND '".$as_codprohas."'";
		  }
	 }
  if (!empty($as_numsolcotdes) && !empty($as_numsolcothas))
     {
	   $ls_tabla  = $ls_tabla.", soc_sol_cotizacion";
	   $ls_straux = $ls_straux." AND soc_sol_cotizacion.numsol BETWEEN '".$as_numsolcotdes."' AND '".$as_numsolcothas."'";
	 }
  if (!empty($as_feccotdes) && !empty($as_feccothas))
     {
	   $ls_feccotdes = $this->io_funciones->uf_convertirdatetobd($as_feccotdes);
	   $ls_feccothas = $this->io_funciones->uf_convertirdatetobd($as_feccothas); 
	   $ls_straux    = $ls_straux." AND soc_cotizacion.feccot BETWEEN '".$ls_feccotdes."' AND '".$ls_feccothas."' ";
	 }
  if (!empty($as_estcot) && $as_estcot!='-')
     {
	   if ($as_estcot=='R')
	      {
			$ls_straux = $ls_straux." AND soc_cotizacion.numcot NOT IN (SELECT numcot FROM soc_cotxanalisis WHERE codemp='".$this->ls_codemp."')";
		  }
	   elseif($as_estcot=='P')
	      {
			$ls_straux = $ls_straux." AND soc_cotizacion.numcot IN (SELECT numcot FROM soc_cotxanalisis WHERE codemp='".$this->ls_codemp."')";
		  }
	 }	 

  $ls_sql = "SELECT soc_cotizacion.numcot, 
                    max(soc_cotizacion.feccot) as feccot, 
					max(soc_cotizacion.obscot) as obscot, 
					max(soc_cotizacion.tipcot) as tipcot, 
					max(rpc_proveedor.nompro) as nompro 
               FROM soc_cotizacion, rpc_proveedor $ls_tabla
			  WHERE soc_cotizacion.codemp='".$this->ls_codemp."' $ls_straux 
			    AND soc_cotizacion.codemp = rpc_proveedor.codemp
		 	    AND soc_cotizacion.cod_pro = rpc_proveedor.cod_pro	  
			  GROUP BY soc_cotizacion.numcot 
			  ORDER BY soc_cotizacion.numcot ASC";//print $ls_sql;

  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_registro_cotizaciones.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }
  return $rs_data;
}

function uf_load_orden_servicio($as_numordcom,&$lb_valido)
{
///////////////////////////////////////////////////////////////////////////////////////////////////////
//	       Function:  uf_load_orden_servicio
//		     Access:  public
//		   Argument: 
//    $as_numordcom:  Numero de la Orden de Compra tipo servicio que será impresa.
//       $lb_valido:  Variable booleana que devuelve true si todo se ejecutó con éxito, false de lo contrario.
//	    Description:  Función que obtiene los datos de la Orden de Compra.
//	     Creado Por:  Ing. Nestor Falcon.
//   Fecha Creación:  22/07/2007								Fecha Última Modificación : 22/07/2007
////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_sql    = "SELECT soc_ordencompra.fecordcom, soc_ordencompra.montot, soc_ordencompra.estcom,soc_dt_servicio.codser, soc_servicios.denser, 
                       soc_dt_servicio.canser, soc_dt_servicio.monuniser,rpc_proveedor.nompro
                  FROM soc_ordencompra, soc_dt_servicio, rpc_proveedor, soc_servicios 
                 WHERE soc_ordencompra.codemp='".$this->ls_codemp."'
                   AND soc_ordencompra.numordcom='".$as_numordcom."'
				   AND soc_ordencompra.estcondat='S'
				   AND soc_ordencompra.codemp=soc_dt_servicio.codemp
				   AND soc_ordencompra.numordcom=soc_dt_servicio.numordcom
				   AND soc_ordencompra.estcondat=soc_dt_servicio.estcondat
				   AND soc_ordencompra.codemp=rpc_proveedor.codemp
				   AND soc_ordencompra.cod_pro=rpc_proveedor.cod_pro
				   AND soc_dt_servicio.codemp=soc_servicios.codemp
				   AND soc_dt_servicio.codser=soc_servicios.codser";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $this->io_mensajes->message("CLASE->sigesp_soc_class_reportbsf.php->MÉTODO->uf_load_orden_servicio.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
	 }
  return $rs_data;
}
//---------------------------------------------------------------------------------------------------------------------------------------
  
  //---------------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_listado_orden_compra($as_numordcomdes,$as_numordcomhas,$as_codprodes,$as_codprohas,
                                            $as_fecordcomdes,$as_fecordcomhas,$as_coduniadmdes,
                                            $as_coduniadmhas,$as_rdanucom,$as_rdemi,$as_rdpre,$as_rdcon,
                                            $as_rdanu,$as_rdinv,$as_artdes,$as_arthas,$as_serdes,$as_serhas,
								            $as_tipord,$as_tipo,&$lb_valido)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_listado_orden_compra
		//         Access: public 
		//	    Arguments: as_numordcom   ---> Orden de Compra a imprimir
		//                 $as_tipord  ---> tipo de la orden de compra bienes o servicios 
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca los detalles de la  orden de compra para imprimir
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 16/07/2007									Fecha Última Modificación :  
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		//$ab_valido = true;
		$lb_valido = true;  
		$ls_criterio_a = "";
		$ls_criterio_b = "";   
		$ls_criterio_c = "";  
		$ls_criterio_d = "";
		$ls_criterio_e = "";
		$ls_criterio_f = "";
		$ls_criterio_g = "";
		$ls_criterio_h = "";
		$ls_cad        = "";
		$ls_cadena     = "";
		$ls_sql        = "";
		$ls_parentesis = "";
		if(  (($as_numordcomdes!="") && ($as_numordcomhas=="")) || (($as_numordcomdes=="") && ($as_numordcomhas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Número !!!"); 	  
		}
		else
		{
			if( ($as_numordcomdes!="") && ($as_numordcomhas!="") )
			{
			   $ls_criterio_a = "   numordcom >='".$as_numordcomdes."'  AND  numordcom <='".$as_numordcomhas."'    ";
			}
			else
			{
			   $ls_criterio_a ="";
			}
		}

		if(  (($as_codprodes!="") && ($as_codprohas=="")) || (($as_codprodes=="") && ($as_codprohas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Proveedor !!!"); 
		}
		else
		{
			if( ($as_codprodes!="") && ($as_codprohas!="") )
			{
			   if($ls_criterio_a=="")
			   {
					 $CA_AND="";   //CA = Criterio A
			   } 
			   else
			   {
					 $CA_AND="  AND  ";
			   }
			   $ls_criterio_b  =  $ls_criterio_a.$CA_AND."  cod_pro   >='".$as_codprodes."'  AND  cod_pro   <='".$as_codprohas."'  ";
			}
			else
			{
			   $ls_criterio_b = $ls_criterio_a;
			}
		}

	
		if(  (($as_fecordcomdes!="") && ($as_fecordcomhas=="")) || (($as_fecordcomdes=="") && ($as_fecordcomhas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Fechas !!!"); 	
		}
		else
		{				   
		   if( ($as_fecordcomdes!="") && ($as_fecordcomhas!="") )
		   {
			   $ls_fecha  = $this->io_funciones->uf_convertirdatetobd($as_fecordcomdes);
			   $as_fecordcomdes = $ls_fecha;
		
			   $ls_fechas  = $this->io_funciones->uf_convertirdatetobd($as_fecordcomhas);
			   $as_fecordcomhas  = $ls_fechas;
			
			   if($ls_criterio_b=="")
			   {
					 $CB_AND="";  //CB = Criterio B
			   } 
			   else
			   {
					 $CB_AND="  AND  ";
			   }
			   $ls_criterio_c = $ls_criterio_b.$CB_AND."  fecordcom >='".$as_fecordcomdes."'  AND  fecordcom <='".$as_fecordcomhas."'  "; 
			 }
		   else
		   {
				$ls_criterio_c = $ls_criterio_b;
		   }			
		}

		if( ($as_rdanucom==0) && ($as_rdemi==0) && ($as_rdpre==0) && ($as_rdcon==0) && ($as_rdanu==0) && ($as_rdinv==0))
		{  
			$ls_criterio_d = $ls_criterio_c; 
		}
		else
		{
		   if($as_rdanucom!=0)
		   {
			  $ls_cadena=" (  estcom = 6 ";
		   }
		   else
		   {
			 $ls_cadena="";
		   }
	 
		   if($as_rdemi!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estcom = 1  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				  $ls_cadena=" (  estcom = 1 ";
			  }		  
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
 
		   if($as_rdpre!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estcom = 5  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estcom = 5 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
	
		   if($as_rdcon!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estcom = 2  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estcom = 2 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }

		   if($as_rdanu!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estcom = 3  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estcom = 3 ";
			  }
		   }
		   else
		   {
			 $ls_cadena=$ls_cadena;
		   }
	
		   if($as_rdinv!=0)
		   {
			  if($ls_cadena!="")
			  {
				 $ls_cad=" OR   estcom = 4  ";
				 $ls_cadena=$ls_cadena.$ls_cad;
			  }
			  else
			  {
				 $ls_cadena=" (  estcom = 4 ";
			  }
		   }
		   else
		   {
			   $ls_cadena=$ls_cadena;
		   }
	
		   $ls_parentesis="   )   ";
	
		   if(empty($ls_criterio_c))
		   {
			  $CC_AND=""; //CC = Criterio C
		   }
		   else
		   {
			  $CC_AND="   AND   ";
		   }
		   $ls_criterio_d=$ls_criterio_c.$CC_AND.$ls_cadena.$ls_parentesis;	   
	   }
			   
		if(  (($as_coduniadmdes!="") && ($as_coduniadmhas=="")) || (($as_coduniadmdes=="") && ($as_coduniadmhas!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Departamento !!!"); 
		}
		else
		{
			if(empty($ls_criterio_d))
			 {
				$CD_AND="";  //CD = Criterio D
			 } 
			else
			 {
				$CD_AND="  AND  ";
			 }
	
			if( (($as_coduniadmdes!="") && ($as_coduniadmhas!="")) && (($as_numordcomdes!="") && ($as_numordcomhas!="")) )
			{                     
			   $ls_criterio_e  =  $ls_criterio_d.$CD_AND."  numordcom in (SELECT numordcom FROM soc_enlace_sep   ".
														 "                WHERE  numordcom >='".$as_numordcomdes."' AND numordcom<='".$as_numordcomhas."' AND ".
														 "                numordcom in (SELECT S.numsol FROM sep_solicitud S               ".
														 "                              WHERE  S.coduniadm >='".$as_coduniadmdes."'  AND  S.coduniadm <='".$as_coduniadmhas."' ".
														 "                              ) ".
														 "               )                ";					
			}
			else
			{
			   if( (($as_coduniadmdes!="") && ($as_coduniadmhas!="")) && (($as_numordcomdes=="") && ($as_numordcomhas=="")) )
			   {
				  $ls_criterio_e  =  $ls_criterio_d.$CD_AND."  numordcom in (SELECT numordcom FROM soc_enlace_sep ".
															"                WHERE  numordcom in (SELECT S.numsol FROM sep_solicitud S  ".
															"                                     WHERE  S.coduniadm >='".$as_coduniadmdes."' AND S.coduniadm <='".$as_coduniadmhas."'".
															"                                    ) ".
															"               )                      ";				
			   }
			   else
			   {
					if( ($as_coduniadmdes=="") && ($as_coduniadmhas=="") )
					{
						$ls_criterio_e = $ls_criterio_d;
					}
			   }
			}
		}
		
		if( ($as_tipo=="T") || ($as_tipo=="A") )
		{
			   //************************        Busqueda por Artículo  ******************************
			   if(  (($as_artdes!="") && ($as_arthas=="")) || (($as_artdes=="") && ($as_arthas!=""))  )
				{
				   $lb_valido = false;
				   $this->io_msg->message("Debe Completar el Rango de Busqueda por Artículo !!!"); 
				}
				else
				{
					if(empty($ls_criterio_e))
					 {
						$CE_AND="";  //CD = Criterio D
					 } 
					else
					 {
						$CE_AND="  AND  ";
					 }
					 if(  ($as_artdes!="") && ($as_arthas!="")  ) 
					 {				    
						 $ls_criterio_f = $ls_criterio_e.$CE_AND."  numordcom in (SELECT numordcom                                             ".
																 "                FROM soc_dt_bienes                                           ".
																 "                WHERE codart >='".$as_artdes."' AND codart<='".$as_arthas."' ".
																 "                )                                                            ";				
					 }
					 else
					 {
						 $ls_criterio_f = $ls_criterio_e;
					 }
				}
		}	
		else
		{
		  $ls_criterio_f = $ls_criterio_e;
		}
	
		if( ($as_tipo=="T") || ($as_tipo=="S") )
		{	
			   //************************        Busqueda por Servicios  ******************************
			   if(  (($as_serdes!="") && ($as_serhas=="")) || (($as_serdes=="") && ($as_serhas!=""))  )
				{
				   $lb_valido = false;
				   $this->io_msg->message("Debe Completar el Rango de Busqueda por Servicios !!!"); 
				}
				else
				{
					if(empty($ls_criterio_f))
					 {
						$CF_AND="";  //CD = Criterio D
					 } 
					else
					 {
						$CF_AND="  AND  ";
					 }
					 if(  ($as_serdes!="") && ($as_serhas!="")  ) 
					 {
						 $ls_criterio_g = $ls_criterio_f.$CF_AND."  numordcom in (SELECT numordcom                                             ".
																 "                FROM soc_dt_servicio                                           ".
																 "                WHERE codser >='".$as_serdes."' AND codser<='".$as_serhas."' ".
																 "                )                                                            ";				
					}
					else
					{
						$ls_criterio_g = $ls_criterio_f;
					}
				}
		}
		else
		{
		   $ls_criterio_g = $ls_criterio_f;
		}
			 
		if( ($as_tipord=="A")  ||  ($as_tipord=="") )
		{
			 $ls_criterio_h = $ls_criterio_g;
		} 
		else	 	
		{			
			 if(empty($ls_criterio_g))
			 {
				 $CG_AND=""; //CC = Criterio C
			 }
			 else
			 {
				$CG_AND="   AND   ";
			 }
			 if($as_tipord=="B") 
			 {
				 $ls_criterio_h = $ls_criterio_g.$CG_AND." estcondat='B' ";
			 } 
			 else
			 {
				 if($as_tipord=="S") 
				 {
					 $ls_criterio_h = $ls_criterio_g.$CG_AND." estcondat='S' ";
				 } 
			 }		
		} 
		if($ls_criterio_h!="")
		{
		   $ls_sql=" SELECT codemp, numordcom, estcondat, cod_pro, codmon, codfuefin, codtipmod, fecordcom, estsegcom, porsegcom, ".
		   		   "		(monsegcomaux) AS monsegcom, forpagcom, estcom, diaplacom, concom, obscom, (monsubtotbieaux) as monsubtotbie, ".
				   "	    (monsubtotaux) AS monsubtot, (monbasimpaux) AS monbasimpa, (monimpaux) AS monimp, (mondesaux) AS mondes, ".
				   "		(montotaux) AS montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, lugentdir, (monantaux) AS monant, ".
				   "        estlugcom, (tascamordcomaux) AS tascamordcom, (montotdivaux) AS montotdiv, estapro, fecaprord, codusuapr, ".
				   "		(monsubtotseraux) AS monsubtotser, numpolcon, coduniadm, obsordcom ".
				   "  FROM soc_ordencompra ".
				   " WHERE codemp='".$this->ls_codemp."'  AND ".$ls_criterio_h." ".
				   " ORDER BY numordcom ASC";
		}
		else
		{
		   $ls_sql=" SELECT codemp, numordcom, estcondat, cod_pro, codmon, codfuefin, codtipmod, fecordcom, estsegcom, porsegcom, ".
		   		   "		(monsegcomaux) AS monsegcom, forpagcom, estcom, diaplacom, concom, obscom, (monsubtotbieaux) as monsubtotbie, ".
				   "	    (monsubtotaux) AS monsubtot, (monbasimpaux) AS monbasimpa, (monimpaux) AS monimp, (mondesaux) AS mondes, ".
				   "		(montotaux) AS montot, estpenalm, codpai, codest, codmun, codpar, lugentnomdep, lugentdir, (monantaux) AS monant, ".
				   "        estlugcom, (tascamordcomaux) AS tascamordcom, (montotdivaux) AS montotdiv, estapro, fecaprord, codusuapr, ".
				   "		(monsubtotseraux) AS monsubtotser, numpolcon, coduniadm, obsordcom ".
				   "  FROM soc_ordencompra ".
				   " WHERE codemp='".$this->ls_codemp."' ".
				   " ORDER BY numordcom ASC";
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_listado_orden_compra".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
        return $rs_data;
    }//fin de uf_select_listado_orden_compra
   //---------------------------------------------------------------------------------------------------------------------------------------
   
   //---------------------------------------------------------------------------------------------------------------------------------	
	function uf_select_nombre_proveedor($as_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_select_nombre_proveedor
		//		   Access: private 
		//	    Arguments: as_codpro //codigo del proveedor
		//    Description: Function que devuelve la denominacion de la cuenta presupuestaria
		//	   Creado Por: Ing. Yozelin Barragan.
		// Fecha Creación: 10/04/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		 $lb_valido=false;
		 $ls_sql=" SELECT   nompro ".
				 " FROM     rpc_proveedor ".
				 " WHERE    codemp='".$this->ls_codemp."'  AND  cod_pro ='".$as_codpro."' "; 
		 $rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_nombre_proveedor ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		 }		
		 else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$as_nompro=$row["nompro"];     
				$lb_valido=true;
			 }	
			$this->io_sql->free_result($rs);
		 } 
		 return $as_nompro;    
	}//fin 	uf_select_nombre_proveedor
   //---------------------------------------------------------------------------------------------------------------------------------	
 //---------------------------------------------------------------------------------------------------------------------------------------	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_cotizacion_analisis($as_numanacot, $ls_tipanacot)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_cotizacion_analisis
		//		   Access: public
		//		  return :	arreglo que contiene las cotizaciones que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve las cotizaciones que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 14/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_proveedores=array();
		$lb_valido=false;	
		if($ls_tipanacot == "B")
			$ls_tabla = "soc_dtac_bienes";
		elseif($ls_tipanacot == "S")	
			$ls_tabla = "soc_dtac_servicios";
		$ls_sql= "SELECT cxa.numcot, cxa.cod_pro,p.nompro,p.tipconpro
				  FROM soc_cotxanalisis cxa, rpc_proveedor p
				  WHERE cxa.codemp='$this->ls_codemp' AND cxa.numanacot='$as_numanacot' 
				  AND cxa.codemp=p.codemp AND  cxa.cod_pro = p.cod_pro
				  AND cxa.numcot IN 
				  (SELECT numcot FROM $ls_tabla WHERE codemp='$this->ls_codemp')";		
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("ERROR->uf_select_cotizacion_analisis".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$aa_proveedores[$li_i]=$row;					
				$li_i++;
			}																
		}
		return $aa_proveedores;
	}//fin de uf_select_cotizacion_analisis
	
//---------------------------------------------------------------------------------------------------------------------------------------	

	function uf_calcular_montos($ai_totrow,$aa_items,&$aa_totales,$as_tipo_proveedor)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_calcular_montos
		//		   Access: public
		//		  return :	arreglo  montos totalizados
		//	  Description: Metodo que  devuelve arreglo  montos totalizados
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 09/08/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$li_subtotal=0;
		 	$li_totaliva=0;
		 	$li_total=0;
		 	$aa_totales=array();
			for($li_j=1;$li_j<=$ai_totrow;$li_j++)
		 	{
				$li_subtotal+=(($aa_items[$li_j]["precio"]) * ($aa_items[$li_j]["cantidad"]));
				if($as_tipo_proveedor != "F") //En caso de que el roveedor sea formal no se le calculan los cargos
					$li_totaliva+=$aa_items[$li_j]["moniva"];	
			}
			$li_total=$li_totaliva+$li_subtotal;		 
			$aa_totales["subtotal"]=$li_subtotal;
			$aa_totales["totaliva"]=$li_totaliva;
			$aa_totales["total"]=$li_total;
	}
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
//---------------------------------------------------------------------------------------------------------------------------------------
	function uf_select_items_proveedor($as_numcot,$as_codpro,$as_numanacot,$as_tipsolcot,&$aa_items,&$li_i)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_items
		//		   Access: public
		//		  return :	arreglo que contiene los items que participaron en un determinado analisis 
		//	  Description: Metodo que  devuelve los items que participaron en un determinado analisis
		//	   Creado Por: Ing. Laura Cabré
		// 			  Fecha: 10/06/2007								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$aa_items=array();
		$lb_valido=false;
		if($as_tipsolcot=="B")
		{				
			$ls_sql="SELECT d.codart as codigo, a.denart as denominacion, p.nompro, dt.canart as cantidad, dt.preuniartaux as precio, dt.monivaaux as moniva,dt.montotartaux monto,
					d.obsanacot, d.numcot, d.cod_pro
					FROM soc_dtac_bienes d,siv_articulo a, rpc_proveedor p,soc_dtcot_bienes dt 
					WHERE
					d.codemp='$this->ls_codemp' AND d.numanacot='$as_numanacot' AND dt.cod_pro='$as_codpro' AND dt.numcot='$as_numcot' 
					AND d.codemp=a.codemp AND a.codemp=p.codemp AND p.codemp=dt.codemp AND
					d.codart=a.codart AND d.cod_pro=p.cod_pro AND d.numcot=dt.numcot AND d.cod_pro=dt.cod_pro AND d.codart=dt.codart";				
		}
		else
		{
				$ls_sql="SELECT d.codser as codigo, a.denser as denominacion, p.nompro, dt.canser as cantidad, dt.monuniseraux as precio, dt.monivaaux as moniva,dt.montotseraux monto,
					d.obsanacot, d.numcot, d.cod_pro
					FROM soc_dtac_servicios d,soc_servicios a, rpc_proveedor p,soc_dtcot_servicio dt
					WHERE
					d.codemp='$this->ls_codemp' AND d.numanacot='$as_numanacot' AND d.codemp=a.codemp AND dt.cod_pro='$as_codpro' AND dt.numcot='$as_numcot' 
					AND a.codemp=p.codemp AND p.codemp=dt.codemp AND
					d.codser=a.codser AND d.cod_pro=p.cod_pro AND d.numcot=dt.numcot AND d.cod_pro=dt.cod_pro AND d.codser=dt.codser";				
		}
		//print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($io_sql->message)); 
			print $io_mensajes->message("ERROR->".$this->io_funciones->uf_convertirmsg($io_sql->message)); 
			$lb_valido=false;	
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))//Se verifica si la solicitud es de bienes o de servicios
			{
				$li_i++;
				$aa_items[$li_i]=$row;					
			}																
		}
		return $aa_items;
	}

    function uf_select_denominacion($as_tabla,$as_campo,$as_where)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_denominacion
	 //	Access       public
	 //	Arguments    $as_codmon
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la moneda
	 //////////////////////////////////////////////////////////////////////////////
	
		 $ls_sql  ="SELECT $as_campo as denrow FROM $as_tabla $as_where";
	 	 $rs_data = $this->io_sql->select($ls_sql);
		 if ($rs_data===false)
		    {
			  $lb_valido=false;
			  $this->io_msg->message("ERROR: CLASS=sigesp_soc_class_reportbsf.php; Metodo=uf_select_denominacion;".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		    }		
		 else
		    {
		      if ($row=$this->io_sql->fetch_row($rs_data))
			     { 		   
			       $ls_denrow = $row["denrow"];              
			     }
		    } 
	     return $ls_denrow;
	}

	//--------------------------------------------------------------------------------------------------------------------
	function uf_load_nombre_usuario()
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_load_nombre_usuario
		//		   Access:  public
		//		 Argument: 
		//	  Description:  Función que obtiene el nombre completo del usuario que imprime el documento
		//	   Creado Por:  Ing. Luis Anibal Lang
		// Fecha Creación:  22/10/2007								Fecha Última Modificación:
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nombre="";
		$ls_sql="SELECT nomusu,apeusu".
			  	"  FROM sss_usuarios".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codusu='".$this->ls_codusu."'";
		$rs_data= $this->io_sql->select($ls_sql);//print $ls_sql;
		if ($rs_data===false)
		{ 
			$this->io_mensajes->message("CLASE->sigesp_soc_class_report.php->MÉTODO->uf_load_nombre_usuario.ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			return false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_nombre= $row["nomusu"]." ".$row["apeusu"];
			}
		}
	  return $ls_nombre;
	}// end function uf_load_nombre_usuario
//--------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------
}//FIN DE LA CLASE.
?>