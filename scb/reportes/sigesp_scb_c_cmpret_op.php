<?php
class sigesp_scb_c_cmpret_op
{
var $ls_sql;
var $ds_detpresupuesto;	
var $ds_contable;	
var $ds_solicitud;
var $ds_sol_dt;	
var $ds_spg_dt;
var $ds_scg_dt;
var $ds_dc_dt;
var $ds_recepciones;
var $ds_deducciones;
var $ds_otroscreditos;
var $ds_documentos;
var $ds_car_dt;
var $ds_ded_dt;
var $ds_not;
var $ds_provben;     
var $ds_detfac;
var $ds_provbenc;
var $ds_detsolsaldos;
var $ds_detdeusaldos;
var $ds_sol_t;
var $ds_retenciones;
    function sigesp_scb_c_cmpret_op($conn)
	{
  	  require_once("../../shared/class_folder/class_funciones.php");
  	  require_once("../../shared/class_folder/class_mensajes.php");
	  $this->io_funcion        = new class_funciones();
	  $this->io_sql            = new class_sql($conn);
	  $this->ds_detpresupuesto = new class_datastore();
	  $this->ds_detcontable    = new class_datastore();
	  $this->ds_recepciones    = new class_datastore();
	  $this->ds_deducciones    = new class_datastore();
	  $this->ds_otroscreditos  = new class_datastore();
	  $this->ds_documentos     = new class_datastore();
	  $this->ds_provben        = new class_datastore();
	  $this->io_msg            = new class_mensajes();		
      $this->ds_spg_dt =         new class_datastore();
      $this->ds_scg_dt =         new class_datastore();
	  $this->ds_retenciones    = new class_datastore();
	}

function uf_load_recepciones($as_codemp,$as_categoria,$as_codigo1,$as_codigo2,
                             $as_tipodoc,$as_recibidas,$as_anuladas,
							 $as_procesadas,$as_fechadesde,$as_fechahasta,$as_ordbydoc,$as_ordbyfec,$as_ordbycod,&$lb_valido) 
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_recepciones
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//     $as_categoria:  Categoria de la persona (P=Proveedor;B= Beneficiario;A= Ambos) .
//       $as_codigo1:  Código del proveedor a partir del cual se realizara la búsqueda.
//       $as_codigo2:  Código del proveedor hasta el cual se realizara la búsqueda.
//       $as_tipodoc:  Código del Tipo de Documento.
//     $as_recibidas:  Caracter que permitirá saber si se requiere mostrar todas aquellas 
//                     Recepciones de Documentos cuyo estatus sea (R)Recibida.
//      $as_anuladas:  Caracter que permitirá saber si se requiere mostrar todas aquellas 
//                     Recepciones de Documentos cuyo estatus sea (A)Anuladas.
//    $as_procesadas:  Caracter que permitirá saber si se requiere mostrar todas aquellas 
//                     Recepciones de Documentos cuyo estatus sea (P)procesadas.
//    $as_fechadesde:  Fecha a partir del cual se realizara la busqueda de las recepciones.
//    $as_fechahasta:  Fecha hasta el cual se buscaran las recepciones de documentos.
//      $as_ordbydoc:  Caracter que indicará si debemos ordenar por el Número de la Recepción de Documento.
//      $as_ordbyfec:  Caracter que indicará si debemos ordenar por la Fecha de Emisión de la Recepción de Documento.
//      $as_ordbycod:  Caracter que indicará si debemos ordenar por el Código del Proveedor/Beneficiario de la Recepción de Documento.
//        $lb_valido:  Variable booleana que me devuelve true si de la consulta sql resultan
//                     registros (Obtiene datos), de lo contrario, si no se consiguen registros, $lb_valido retorna false.       
//	         Returns:		
//       Description:  Función que se encarga de realizar la búsqueda de Recepciones de Documentos.
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  02/02/2006        Fecha Última Actualización:04/05/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    $this->ds_recepciones = new class_datastore();
	$lb_valido=true;
	if (($as_categoria=="P")||($as_categoria=="B")) 
       {
		 $ls_criterio=" AND tipproben='".$as_categoria."'";
	   }
    else
       {
	     $ls_criterio="";
	   }
  if ((empty($as_codigo1))||(empty($as_codigo2)))
     {
	   $as_codigo1  ="0000000000";
	   $as_codigo2  ="9999999999";
	 }
  if ($as_categoria=="P")
     {
	   $ls_criterio =$ls_criterio." AND cod_pro BETWEEN '".$as_codigo1."' AND '".$as_codigo2."'";
     }
  elseif($as_categoria=="B")
     {
	   $ls_criterio =$ls_criterio." AND ced_bene BETWEEN '".$as_codigo1."' AND '".$as_codigo2."'";
     }
  if (!empty($as_fechadesde))
     {
      $as_fechadesde=$this->io_funcion->uf_convertirdatetobd($as_fechadesde);
	 }
  if (!empty($as_fechahasta))
     {
	   $as_fechahasta=$this->io_funcion->uf_convertirdatetobd($as_fechahasta);
	 }	 
  if ((!empty($as_fechadesde))&&(!empty($as_fechahasta)))
     {
       $ls_criterio=$ls_criterio." AND fecregdoc BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."'";
	 }
  if (!empty($as_tipodoc))
     {
       $ls_criterio=$ls_criterio." AND codtipdoc ='".$as_tipodoc."'";
	 }
  $lb_anterior=false;
  if (!empty($as_recibidas))
     {
	   $ls_criterio=$ls_criterio." AND estprodoc ='".$as_recibidas."'";
	   $lb_anterior=true;
	 }
  if (!empty($as_anuladas))
     {
	   if ($lb_anterior)
	      {
		    $ls_criterio=$ls_criterio." OR estprodoc ='".$as_anuladas."'";
		  }
	   else
	      {
		    $ls_criterio=$ls_criterio." AND estprodoc ='".$as_anuladas."'";  
		  }
     }
  if (!empty($as_procesadas))
     {
	   if ($lb_anterior)
	      {
	        $ls_criterio=$ls_criterio." OR estprodoc ='".$as_procesadas."'";
		  }
	   else
	      {
		    $ls_criterio=$ls_criterio." AND estprodoc ='".$as_anuladas."'";  
		  }
     } 
  if (!empty($as_ordbydoc) || !empty($as_ordbyfec) || !empty($as_ordbycod) || !empty($as_ordbynom))
     {
	   $ls_criterio=$ls_criterio." ORDER BY ";
       if (!empty($as_ordbydoc))
	      {
		    $ls_criterio=$ls_criterio."numrecdoc,";
		  }
	   if (!empty($as_ordbyfec))
          {
		    $ls_criterio=$ls_criterio."fecemidoc,";
		  }
       if (!empty($as_ordbycod))
	      {
		    if ($as_categoria=='P')
			   {
			     $ls_criterio=$ls_criterio."cod_pro,";
			   }
			else
			   {
			     $ls_criterio=$ls_criterio."ced_bene,";
			   }
		  }
       if (!empty($as_ordbynom))
	      {
		    $ls_criterio=$ls_criterio."nomproben,";
		  }
	   $ls_criterio=substr($ls_criterio,0,strlen($ls_criterio)-1);
	 }
  $ls_sql = " SELECT * FROM cxp_rd WHERE codemp = '".$as_codemp."' $ls_criterio";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_load_recepciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
   else
      {
	    $li_numrows=$this->io_sql->num_rows($rs_data);
		if ($li_numrows>0)
		   {
		     $datos=$this->io_sql->obtener_datos($rs_data);
		     $this->ds_recepciones->data=$datos;
			 $lb_valido=true;
			 $this->io_sql->free_result($rs_data);
		   }
		else
		   {
		     $lb_valido=false; 
		   } 
	 }		
return $lb_valido;
}

   function uf_select_nombre_proveedor($as_codemp,$as_codpro)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion          :  uf_select_nombre_proveedor
     //	Access           :  public
     //	Arguments        :  $as_codemp,$as_codpro
     //	Returns	         :  $rs (Resulset)
     //	Description      :  Devuelve un resulset para seleccionar el nombre del proveedor
     //                     correspondiente al codigo recibido.
     // Creado Por       :  Ing. Selena Lucena.
     // Fecha de Creacion:  06/02/2006.        Fecha Ultima Modificacion:06/02/2006
     //////////////////////////////////////////////////////////////////////////////

	   $ls_sql=" SELECT nompro FROM rpc_proveedor WHERE codemp='".$as_codemp."' AND cod_pro ='".$as_codpro."' "; 
	   $rs = $this->io_sql->select($ls_sql);
       if ($rs===false)
		  {
	        $lb_valido=false;
 	        $this->io_msg->message("CLASE->SIGESP_CXP_RC_LIBROCOMPRA; METODO->uf_load_libro_compras; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
  	      }
       else
          {
             return $rs;
          }
	}

function uf_select_nombre_beneficiario($as_codemp,$as_ced_bene)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion          :  uf_select_nombre_proveedor
 //	Access           :  public
 //	Arguments        :  $as_codemp,$as_codpro
 //	Returns	         :  $rs (Resulset)
 //	Description      :  Devuelve un resulset para seleccionar el nombre del proveedor
 //                     correspondiente al codigo recibido.
 // Creado Por       :  Ing. Selena Lucena.
 // Fecha de Creacion:  06/02/2006.        Fecha Ultima Modificacion:06/02/2006	
 //////////////////////////////////////////////////////////////////////////////

	 $ls_sql=" SELECT   nombene ".
			 " FROM     rpc_beneficiario ".
			 " WHERE    codemp='".$as_codemp."'  AND  ced_bene ='".$as_ced_bene."' "; 

	$rs=$this->io_sql->select($ls_sql);
	if (($rs==false)&&($this->io_sql->message!=""))
	 {
		$lb_valido=false;
		$this->io_msg->message('Error en Consulta SQL !!!'); 
		$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
	 else
	 {
		return $rs;
	 }
}

function uf_select_detallespresupuestarios($as_codemp,$as_numrecdoc)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_select_detallespresupuestarios
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//    $ as_numrecdoc:  Número de la Recepción de Documento a la cual se le buscaran
//                     los detalles de Tipo Presupuestarios.         
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.             
//       Description:  Función que se encarga de realizar la búsqueda de los detalles 
//                     de Tipo Presupuestarios en la tabla cxp_rd_spg 
//                     asociados a la Recepción de Documento cuyo Número de Recepción 
//                     (numrecdoc) viene como parametro al método y al código de la 
//                     empresa.      
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  03/02/2006        Fecha Última Actualización:03/02/2006.	 
//////////////////////////////////////////////////////////////////////////////
  
  $lb_valido=true;
  $ls_sql= " SELECT * FROM cxp_rd_spg WHERE codemp='".$as_codemp."' AND numrecdoc = '".$as_numrecdoc."'";
  $rs_detpresupuesto=$this->io_sql->select($ls_sql);		    
  if ($rs_detpresupuesto===false)
     {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_detallespresupuestarios; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_detpresupuesto))
		  {
			$datos=$this->io_sql->obtener_datos($rs_detpresupuesto);
			$this->ds_detpresupuesto->data=$datos;
		  }
	   else
		  {
			$lb_valido=false;
		  }
			$this->io_sql->free_result($rs_detpresupuesto);
	  }		
return $lb_valido;
}

function uf_select_detallescontables($as_codemp,$as_numrecdoc)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_select_detallescontables
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//    $ as_numrecdoc:  Número de la Recepción de Documento a la cual se le buscaran
//                     los detalles de Tipo Presupuestarios.         
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de realizar la búsqueda de los detalles 
//                     de Tipo Contable en la tabla cxp_rd_scg 
//                     asociados a la Recepción de Documento cuyo Número de Recepción 
//                     (numrecdoc) viene como parametro al método y al código de la 
//                     empresa.      
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  03/02/2006        Fecha Última Actualización:08/05/2006.	 
//////////////////////////////////////////////////////////////////////////////
  $lb_valido=true;
  $ls_sql= " SELECT * FROM cxp_rd_scg WHERE  codemp='".$as_codemp."' AND numrecdoc = '".$as_numrecdoc."'";
  $rs_detcontable=$this->io_sql->select($ls_sql);		    
  if ($rs_detcontable===false)
     {
 	   $lb_valido=false;
	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_detallescontables; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_detcontable))
		  {
			$datos=$this->io_sql->obtener_datos($rs_detcontable);
			$this->ds_detcontable->data=$datos;
		    $this->io_sql->free_result($rs_detcontable);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_load_deducciones($as_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_select_deducciones
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de extraer todos los registros de la Tabla sigesp_deducciones.      
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  08/05/2006        Fecha Última Actualización:08/05/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $this->ds_deducciones = new class_datastore();
  $ls_sql= " SELECT * FROM sigesp_deducciones WHERE codemp='".$as_codemp."' ORDER BY codded ASC";
  $rs_data=$this->io_sql->select($ls_sql);		    
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_load_deducciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_data))
		  {
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_deducciones->data=$datos;
			$lb_valido=true;
		    $this->io_sql->free_result($rs_data);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_selec_rel_prov_fact($as_codemp,$as_tipo,$as_codigo1,$as_codigo2,&$lb_valido)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Metodo:       uf_selec_rel_prov_fact
		//	Access:       public
		//	Arguments:    $ls_mes,$ls_agno,$ls_probendesde,$ls_probenhasta,$ls_tipo
		//	Returns:		 
		//	Description:  M?odo que se encarga de llamar a otros metodos para generar 
		//                el comprobante, tambien selecciona el rango de proveedores y 
		//                beneficiarios desde la cual se va a crear el comprobante  
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido  = true;						
		$ds_provben=new class_datastore();
		
		if($as_tipo=='P')
		{
			$ls_sql = "SELECT cod_pro as codproben 
					   FROM rpc_proveedor
					   WHERE codemp='".$as_codemp."' AND cod_pro between '".$as_codigo1."' AND '".$as_codigo2."'
					   ORDER BY cod_pro ";
		}
		else
		{
			$ls_sql = "SELECT ced_bene as codproben  
					   FROM rpc_beneficiario
					   WHERE codemp='".$as_codemp."' AND ced_bene between '".$as_codigo1."' AND '".$as_codigo2."'
					   ORDER BY ced_bene ";
		}			
			
		$rs_result=$this->io_sql->select($ls_sql);
		$ds_provben->resetds("codproben");
		if($rs_result===false)
		{
			$this->is_msg_error="Error al cargar proveedores o beneficiarios,".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->is_msg_error;
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$lb_valido=true;
				$datos=$this->io_sql->obtener_datos($rs_result);
				$this->ds_provben->data=$datos;				
			}
			else
			{
     			$lb_valido=false;	
			}
		}			
		return $lb_valido;	
	}	

function uf_selec_rel_prov_factrs($as_codemp,$as_tipo,$as_codigo1,$as_codigo2,&$lb_valido)
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Metodo:       uf_selec_rel_prov_fact
		//	Access:       public
		//	Arguments:    $ls_mes,$ls_agno,$ls_probendesde,$ls_probenhasta,$ls_tipo
		//	Returns:		 
		//	Description:  M?odo que se encarga de llamar a otros metodos para generar 
		//                el comprobante, tambien selecciona el rango de proveedores y 
		//                beneficiarios desde la cual se va a crear el comprobante  
		//////////////////////////////////////////////////////////////////////////////
		$lb_valido  = true;						
		$ds_provbenc=new class_datastore();
		
		if($as_tipo=='P')
		{
			$ls_sql = "SELECT COUNT(*) as row
					   FROM rpc_proveedor
					   WHERE codemp='".$as_codemp."' AND cod_pro between '".$as_codigo1."' AND '".$as_codigo2."'
					   GROUP BY cod_pro ";
		}
		else
		{
			$ls_sql = "SELECT COUNT(*) as row
					   FROM rpc_beneficiario
					   WHERE codemp='".$as_codemp."' AND ced_bene between '".$as_codigo1."' AND '".$as_codigo2."'
					   GROUP BY ced_bene ";
		}								
		$rs_result=$this->io_sql->select($ls_sql);
		$ds_provbenc->resetds("codproben");
		if($rs_result===false)
		{
		       $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_selec_rel_prov_factrs; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		       print ($this->io_sql->message);
			$lb_valido=false;	
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_result))
			{
				$lb_valido=true;
				$datos=$this->io_sql->obtener_datos($rs_result);
				$this->ds_provbenc->data=$datos;
				$li_row=$row["row"];       
				$this->io_sql->free_result($rs_result);  		
			}
			else
			{
     			$lb_valido=false;	
			}
		}			
    return $li_row;	
	}	

function uf_selec_rel_facturas($as_codemp,$as_categoria,$as_codproben,
	                           $as_fechadesde,$as_fechahasta,$as_orderbydoc,
	                           $as_orderbyfec,$as_orderbycod,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_selec_rel_facturas
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de extraer todos los registros de la Tabla sigesp_cargos.      
//     Elaborado Por:  Ing. Selena Lucena.
// Fecha de Creación:  27/05/2006        Fecha Última Actualización:27/05/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  $this->ds_detfac = new class_datastore();
  if($as_categoria=="P")
  {
      $ls_sql= " SELECT rd.numrecdoc, rd.cod_pro as codbenpro, rd.fecregdoc, rd.dencondoc,rd.montotdoc, dt.numsol
				 FROM   cxp_rd rd , cxp_dt_solicitudes dt
				 WHERE  rd.codemp   = '".$as_codemp."'    AND
					    rd.cod_pro  = '".$as_codproben."' AND
					    rd.codemp   = dt.codemp           AND
					    rd.numrecdoc= dt.numrecdoc        AND
					    rd.ced_bene = dt.ced_bene         AND
					    rd.codtipdoc= dt.codtipdoc        AND
					    rd.cod_pro  = dt.cod_pro";
  }
  else
  {
	  if($as_categoria=="B")
	  {
         $ls_sql= " SELECT rd.numrecdoc, rd.ced_bene as codbenpro, rd.fecregdoc, rd.dencondoc,rd.montotdoc, dt.numsol
					FROM   cxp_rd rd , cxp_dt_solicitudes dt
					WHERE  rd.codemp   = '".$as_codemp."'    AND
						   rd.ced_bene = '".$as_codproben."' AND
						   rd.codemp   = dt.codemp           AND
						   rd.numrecdoc= dt.numrecdoc        AND
						   rd.ced_bene = dt.ced_bene         AND
						   rd.codtipdoc= dt.codtipdoc        AND
						   rd.cod_pro  = dt.cod_pro";
	  }
  }
  
  $rs_data=$this->io_sql->select($ls_sql);		    
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_selec_rel_facturas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_data))
		  {
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_detfac->data=$datos;
			$lb_valido=true;
     	    $this->io_sql->free_result($rs_data);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_selec_sol_saldos($as_codemp,$as_categoria,$as_codproben,
	                         $as_fechadesde,$as_fechahasta,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_selec_sol_saldos
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de extraer todos los registros de la Tabla sigesp_cargos.      
//     Elaborado Por:  Ing. Selena Lucena.
// Fecha de Creación:  29/05/2006        Fecha Última Actualización:29/05/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  $this->ds_detsolsaldos = new class_datastore();
  $ls_fecdesde = $this->io_funcion->uf_convertirdatetobd($as_fechadesde);	    
  $ls_fechasta = $this->io_funcion->uf_convertirdatetobd($as_fechahasta);
  
  if($as_categoria=="P")
  {
		   if (($ls_fecdesde!="") && ($ls_fechasta!=""))
		   {	   
				  $ls_sql= " SELECT cod_pro as codbenpro,fecemisol,consol,monsol,numsol
							 FROM   cxp_solicitudes
							 WHERE  (estprosol='E' or estprosol='C' or estprosol='S')           AND 
									fecemisol BETWEEN '".$ls_fecdesde."' AND '".$ls_fechasta."' AND 
									cod_pro='".$as_codproben."' AND ced_bene='----------'       AND 
									codemp='".$as_codemp."'";
		   }
		   else
		   {
				  $ls_sql= " SELECT cod_pro as codbenpro,fecemisol,consol,monsol,numsol
							 FROM   cxp_solicitudes
							 WHERE  (estprosol='E' or estprosol='C' or estprosol='S') AND 
									cod_pro='".$as_codproben."' AND ced_bene='----------'AND 
									codemp='".$as_codemp."' ";
		   }
  }
  else
  {
	  if($as_categoria=="B")
	  {
			  if (($ls_fecdesde!="") && ($ls_fechasta!=""))
			  {	   	
					 $ls_sql= " SELECT cod_pro as codbenpro,fecemisol,consol,monsol,numsol
								FROM   cxp_solicitudes
								WHERE  (estprosol='E' or estprosol='C' or estprosol='S') AND 					
								       fecemisol BETWEEN '".$ls_fecdesde."' AND '".$ls_fechasta."' AND 		
									   cod_pro='----------' AND ced_bene='".$as_codproben."' AND 
									   codemp='".$as_codemp."'";
			  }
			  else
			  {
					 $ls_sql= " SELECT cod_pro as codbenpro,fecemisol,consol,monsol,numsol
								FROM   cxp_solicitudes
								WHERE  (estprosol='E' or estprosol='C' or estprosol='S') AND 							
										cod_pro='----------' AND ced_bene='".$as_codproben."' AND 
									    codemp='".$as_codemp."'";
			  }
	  }
  }
  
  $rs_data=$this->io_sql->select($ls_sql);		    
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_selec_rel_facturas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_data))
		  {
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_detsolsaldos->data=$datos;
			$lb_valido=true;
     	    $this->io_sql->free_result($rs_data);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_selec_deu_saldos($as_codemp,$as_numsol,&$lb_valido)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_selec_rel_facturas
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de extraer todos los registros de la Tabla sigesp_cargos.      
//     Elaborado Por:  Ing. Selena Lucena.
// Fecha de Creación:  29/05/2006        Fecha Última Actualización:29/05/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////  
  $this->ds_detdeusaldos = new class_datastore();
  
  $ls_sql= "SELECT SUM(monto) as monto
			FROM   cxp_sol_banco
			WHERE  codemp='".$as_codemp."' AND numsol='".$as_numsol."'
			GROUP BY numsol";
 
  $rs_data=$this->io_sql->select($ls_sql);		    
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_selec_rel_facturas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_data))
		  {
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_detdeusaldos->data=$datos;
			$lb_valido=true;
     	    $this->io_sql->free_result($rs_data);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_load_otros_creditos($as_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_otros_creditos
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de extraer todos los registros de la Tabla sigesp_cargos.      
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  08/05/2006        Fecha Última Actualización:08/05/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $this->ds_otroscreditos = new class_datastore();
  $ls_sql= " SELECT * FROM sigesp_cargos WHERE codemp='".$as_codemp."' ORDER BY codcar ASC";
  $rs_data=$this->io_sql->select($ls_sql);		    
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_load_otros_creditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_data))
		  {
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_otroscreditos->data=$datos;
			$lb_valido=true;
     	    $this->io_sql->free_result($rs_data);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_load_documentos($as_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_documentos
//	          Access:  public
//         Arguments:  
//        $as_codemp:  Código de la empresa.
//           Returns:		
//        $lb_valido:  Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  Función que se encarga de extraer todos los registros de la Tabla sigesp_cargos.      
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  08/05/2006        Fecha Última Actualización:08/05/2006.	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $this->ds_documentos = new class_datastore();
  $ls_sql= " SELECT * FROM cxp_documento ORDER BY codtipdoc ASC";
  $rs_data=$this->io_sql->select($ls_sql);		    
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_load_documentos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
	 {
	   if ($row=$this->io_sql->fetch_row($rs_data))
		  {
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_documentos->data=$datos;
			$lb_valido=true;
     	    $this->io_sql->free_result($rs_data);
		  }
	   else
		  {
			$lb_valido=false;
		  }
	  }		
return $lb_valido;
}

function uf_load_recepcion($as_codemp,$as_numrecdoc,$as_codtipdoc,$as_categoria,$as_codigo,&$lb_valido)
{
  if ($as_categoria=='P')
     {
	   $ls_codpro = $as_codigo;
	   $ls_cedbene='----------';
	 }
  else
     {
	   $ls_cedbene = $as_codigo;
	   $ls_codpro='----------';
	 }
  $ls_sql=" SELECT * FROM cxp_rd WHERE codemp='".$as_codemp."' AND numrecdoc='".$as_numrecdoc."' AND codtipdoc='".$as_codtipdoc."' AND ".
          " ced_bene='".$ls_cedbene."' AND cod_pro='".$ls_codpro."'";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
       $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_load_recepcion; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));	 
	 }  
  else
     {
	   $li_numrows=$this->io_sql->num_rows($rs_data); 
	   if ($li_numrows>0)
	      {
		    $lb_valido=true;
		  }
	   else
	      {
		    $lb_valido=false;
		  }
	 }
return $rs_data;
} 

function uf_select_sol($as_codemp,$as_numdes,$as_numhas,
                       $as_codigo1,$as_codigo2,$as_fecdes,$as_fechas,
                       $as_rdpag,$as_rdemi,$as_rdpro,$as_rdcon,
                       $as_rdanu,$as_tipo) 
  {
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo          :  uf_select_sol
	//	Access          :  public
	// 	Arguments       :  $as_codemp,$as_numdes,$as_numhas,
    //                     $as_codigo1,$as_codigo2,$as_fecdes,$as_fechas,
    //                     $as_rdpag,$as_rdemi,$as_rdpro,$as_rdcon,
    //                     $as_rdanu,$as_tipo
	//	Returns         :  return $rs. Devuelve un resulset   
	//	Description     :  Función que se encarga de realizar la busqueda de una
    //                     Solicitud, por los parametros: Número, Fecha,
    //                     Proveedor, y los estatus, entre un rango de numeros.
    // Creado Por       :  Ing. Selena Lucena.
    // Fecha de Creacion:  06/02/2006.        Fecha Ultima Modificacion:06/02/2006
	//////////////////////////////////////////////////////////////////////////////    
    $this->ds = new class_datastore();
	
    $lb_valido = true;  

    $ls_criterio_a="";
    $ls_criterio_b="";
    $ls_criterio_c="";
    $ls_criterio_d="";
    $ls_criterio_e="";
	$ls_cad       ="";
	$ls_cadena    ="";
    
    if(  (($as_numdes!="") && ($as_numhas=="")) || (($as_numdes=="") && ($as_numhas!=""))  )
    {
       $lb_valido = false;
       $this->io_msg->message("Debe Completar el Rango de Busqueda por Número !!!"); 
    }
    else
    {
        if( ($as_numdes!="") && ($as_numhas!="") )
        {
           $ls_criterio_a = "   numsol >='".$as_numdes."'  AND  numsol <='".$as_numhas."'    ";
        }
        else
        {
           $ls_criterio_a ="";
        }
    }   

    if($as_tipo=="P")
    {
		if(  (($as_codigo1!="") && ($as_codigo2=="")) || (($as_codigo1=="") && ($as_codigo2!=""))  )
		{
		   $lb_valido = false;
		   $this->io_msg->message("Debe Completar el Rango de Busqueda por Proveedor !!!"); 
		}
		else
		{
		    if($ls_criterio_a=="")
		    {
				 $CA_AND="";
		    } 
		    else
		    {
				 $CA_AND="  AND  ";
		    }

			if( ($as_codigo1!="") && ($as_codigo2!="") )
			{
			   $ls_criterio_b  =  $ls_criterio_a.$CA_AND."  cod_pro   >='".$as_codigo1."'  AND  cod_pro   <='".$as_codigo2."'  ";
			}
			else
			{
			   $ls_criterio_b = $ls_criterio_a.$CA_AND."    tipproben ='P'   ";
			}
		}
    }
    else
    {
		if($as_tipo=="B")
		{
			if(  (($as_codigo1!="") && ($as_codigo2=="")) || (($as_codigo1=="") && ($as_codigo2!=""))  )
			{
			   $lb_valido = false;
			   $this->io_msg->message("Debe Completar el Rango de Busqueda por Proveedor !!!"); 
			}
			else
			{
			    if($ls_criterio_a=="")
			    {
					 $CA_AND="";
			    } 
			    else
			    {
				    $CA_AND="  AND  ";
			    }

				if( ($as_codigo1!="") && ($as_codigo2!="") )
				{
				   $ls_criterio_b = $ls_criterio_a.$CA_AND."  ced_bene   >='".$as_codigo1."'  AND  ced_bene   <='".$as_codigo2."'  ";
				}
				else
				{
				   $ls_criterio_b = $ls_criterio_a.$CA_AND."    tipproben ='B'   ";
				}
			}
		}
		else
		{
		   $ls_criterio_b = $ls_criterio_a;
		}
    }

    if(  (($as_fecdes!="") && ($as_fechas=="")) || (($as_fecdes=="") && ($as_fechas!=""))  )
    {
       $lb_valido = false;
       $this->io_msg->message("Debe Completar el Rango de Busqueda por Fechas !!!"); 
    }
    else
    {
        if( ($as_fecdes!="") && ($as_fechas!="") )
        {
           $ls_fechas  = $this->io_funcion->uf_convertirdatetobd($as_fechas);
           $as_fechas  = $ls_fechas;

           $ls_fecha  = $this->io_funcion->uf_convertirdatetobd($as_fecdes);
           $as_fecdes = $ls_fecha;   

           if($ls_criterio_b=="")
           {
                 $CB_AND="";
           } 
           else
           {
                 $CB_AND="  AND  ";
           }
           $ls_criterio_c = $ls_criterio_b.$CB_AND."  fecemisol >='".$as_fecdes."'  AND  fecemisol <='".$as_fechas."'  "; 
        }
        else
        {
           $ls_criterio_c = $ls_criterio_b;
        }
    }

    if( ($as_rdpag==0) && ($as_rdemi==0) && ($as_rdpro==0) && ($as_rdcon==0) && ($as_rdanu==0) )
    {  
        $ls_criterio_d = $ls_criterio_c; 
    }
    else
    {
       if($as_rdpag!=0)
       {
          $ls_cadena=" (  estprosol = 'P' ";
       }
       else
       {
         $ls_cadena="";
       }
 
       if($as_rdemi!=0)
       {
          if($ls_cadena!="")
          {
             $ls_cad=" OR   estprosol = 'E'  ";
			 $ls_cadena=$ls_cadena.$ls_cad;
          }
		  else
          {
             $ls_cadena=" (  estprosol = 'E'  ";
          }
       }
       else
       {
         $ls_cadena=$ls_cadena;
       }
 
       if($as_rdpro!=0)
       {
          if($ls_cadena!="")
          {
             $ls_cad=" OR   estprosol = 'S'  ";
             $ls_cadena=$ls_cadena.$ls_cad;
          }
		  else
          {
             $ls_cadena=" (  estprosol = 'S'  ";
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
             $ls_cad=" OR   estprosol = 'C'  ";
			 $ls_cadena=$ls_cadena.$ls_cad;
          }
		  else
          {
             $ls_cadena=" (  estprosol = 'C'  ";
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
             $ls_cad=" OR   estprosol = 'A'  ";
             $ls_cadena=$ls_cadena.$ls_cad;
          }
		  else
          {
             $ls_cadena=" (  estprosol = 'A'  ";
          }          
       }
       else
       {
         $ls_cadena=$ls_cadena;
       }      

       $ls_parentesis="   )   ";

       if($ls_criterio_c=="")
       {
          $CC_AND="";
       }
       else
       {
          $CC_AND="   AND   ";
       }

       $ls_criterio_d=$ls_criterio_c.$CC_AND.$ls_cadena.$ls_parentesis;
    }

    if($lb_valido) 
    {
        $ls_sql="";

        if($ls_criterio_d!="")
        {
		   $ls_sql=" SELECT * FROM cxp_solicitudes ".
				   " WHERE codemp='".$as_codemp."' AND ".$ls_criterio_d." ".
				   " ORDER BY numsol ASC";
        }
        else
        {
		   $ls_sql=" SELECT  * ".
		           " FROM    cxp_solicitudes ".
				   " WHERE   codemp='".$as_codemp."' ".
				   " ORDER BY numsol ASC";
        }    	  
      $this->ds->resetds("numsol");	  
	  $rs=$this->io_sql->select($ls_sql); 
	  if (($rs==false)&&($this->io_sql->message!=""))
	  {
	     $lb_valido=false;
		 if ($this->io_sql->message!="")
		  {                              
             $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
		  } 
	  }
	  else
	  {         
 	        if ($row=$this->io_sql->fetch_row($rs))
			{ 
				$lb_valido=true;
				$datos=$this->io_sql->obtener_datos($rs);
				$this->ds->data=$datos;
				$this->io_sql->free_result($rs);  		
            }
			else
			{
 			    $lb_valido=false;
			}
	  }
      return $lb_valido;
	  }
   }

function uf_select_nota_debito_credito($as_codemp,$as_numdes,$as_numhas,$as_numdessol,
                                       $as_numhassol,$as_fecdes,$as_fechas,$as_rdemi,
									   $as_rdcon,$as_rdanu,$as_tipo) 
  {
	//////////////////////////////////////////////////////////////////////////////
	//	Metodo          :  uf_select_sol
	//	Access          :  public
	// 	Arguments       :  $as_codemp,$as_numdes,$as_numhas,
    //                     $as_codigo1,$as_codigo2,$as_fecdes,$as_fechas,
    //                     $as_rdpag,$as_rdemi,$as_rdpro,$as_rdcon,
    //                     $as_rdanu,$as_tipo
	//	Returns         :  return $rs. Devuelve un resulset   
	//	Description     :  Función que se encarga de realizar la busqueda de una
    //                     Solicitud, por los parametros: Número, Fecha,
    //                     Proveedor, y los estatus, entre un rango de numeros.
    // Creado Por       :  Ing. Selena Lucena.
    // Fecha de Creacion:  06/02/2006.        Fecha Ultima Modificacion:06/02/2006
	//////////////////////////////////////////////////////////////////////////////    
    $this->ds_not = new class_datastore();
	
    $lb_valido = true;  

    $ls_criterio_a="";
    $ls_criterio_b="";
    $ls_criterio_c="";
    $ls_criterio_d="";
    $ls_criterio_e="";
	$ls_cad       ="";
	$ls_cadena    ="";
    
    if(  (($as_numdes!="") && ($as_numhas=="")) || (($as_numdes=="") && ($as_numhas!=""))  )
    {
       $lb_valido = false;
       $this->io_msg->message("Debe Completar el Rango de Busqueda por Número !!!"); 
    }
    else {
			if( ($as_numdes!="") && ($as_numhas!="") )
			{
			   $ls_criterio_a = "   numdc >='".$as_numdes."'  AND  numdc <='".$as_numhas."'    ";
			}
			else
			{
			   $ls_criterio_a ="";
			}
		 }   
   
	if(  (($as_numdessol!="") && ($as_numhassol=="")) || (($as_numdessol=="") && ($as_numhassol!=""))  )
	{
	   $lb_valido = false;
	   $this->io_msg->message("Debe Completar el Rango de Solicitudes!!!"); 
	}
	else
	{
		if($ls_criterio_a==""){   $CA_AND="";   } 
		else { $CA_AND="  AND  ";  }

        if( ($as_numdessol!="") && ($as_numhassol!="") )
        {
           $ls_criterio_b = $ls_criterio_a.$CA_AND."   numsol >='".$as_numdessol."'  AND  numsol <='".$as_numhassol."'    ";
        }
		else
		{
		   $ls_criterio_b = $ls_criterio_a;
		}
	}
    
	
    if(  (($as_fecdes!="") && ($as_fechas=="")) || (($as_fecdes=="") && ($as_fechas!=""))  )
    {
       $lb_valido = false;
       $this->io_msg->message("Debe Completar el Rango de Busqueda por Fechas !!!"); 
    }
    else
    {
        if( ($as_fecdes!="") && ($as_fechas!="") )
        {
           $ls_fechas  = $this->io_funcion->uf_convertirdatetobd($as_fechas);
           $as_fechas  = $ls_fechas;

           $ls_fecha  = $this->io_funcion->uf_convertirdatetobd($as_fecdes);
           $as_fecdes = $ls_fecha;   

           if($ls_criterio_b=="")
           {
                 $CB_AND="";
           } 
           else
           {
                 $CB_AND="  AND  ";
           }
           $ls_criterio_c = $ls_criterio_b.$CB_AND."  fecope >='".$as_fecdes."'  AND  fecope <='".$as_fechas."'  "; 
        }
        else
        {
           $ls_criterio_c = $ls_criterio_b;
        }
    }

    if(  ($as_rdemi==0) && ($as_rdcon==0) && ($as_rdanu==0) )
    {  
        $ls_criterio_d = $ls_criterio_c; 
    }
    else
    {
       if($as_rdemi!=0)
       {          
          $ls_cadena=" ( estnotadc = 'E'  ";                  
       }
       else
       {       
		  $ls_cadena="";	  
       }
      
       if($as_rdcon!=0)
       {
          if($ls_cadena!="")
          {
                 $ls_cad=" OR   estnotadc = 'C'  ";
 	             $ls_cadena=$ls_cadena.$ls_cad;
          }
		  else
		  {
		         $ls_cad="( estnotadc = 'C'  ";
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
                 $ls_cad=" OR   estnotadc = 'A'  ";
	             $ls_cadena=$ls_cadena.$ls_cad;
          }
  		  else
		  {
		         $ls_cad="( estnotadc = 'A'  ";
		  }
       }
       else
       {
         $ls_cadena=$ls_cadena;
       }      

       $ls_parentesis="   )   ";

       if($ls_criterio_c=="")
       {
          $CC_AND="";
       }
       else
       {
          $CC_AND="   AND   ";
       }
       $ls_criterio_d=$ls_criterio_c.$CC_AND.$ls_cadena.$ls_parentesis;
    }

    if( ($as_tipo=="A")  ||  ($as_tipo=="") )
    {
	     $ls_criterio_e = $ls_criterio_d;
    } 
    else	 	
    {			
	     if(empty($ls_criterio_d))
	     {
		     $CD_AND=""; //CC = Criterio C
	     }
	     else
	     {
		    $CD_AND="   AND   ";
	     }
		 if($as_tipo=="D") 
		 {
			 $ls_criterio_e = $ls_criterio_d.$CD_AND." codope='D' ";
		 } 
		 else
		 {
			 if($as_tipo=="C") 
			 {
				 $ls_criterio_e = $ls_criterio_d.$CD_AND." codope='C' ";
			 } 
         }		
	} 

    if($lb_valido) 
    {
        $ls_sql="";

        if($ls_criterio_e!="")
        {
		   $ls_sql=" SELECT * FROM cxp_sol_dc ".
				   " WHERE codemp='".$as_codemp."' AND ".$ls_criterio_e." ".
				   " ORDER BY numdc ASC";
        }
        else
        {
		   $ls_sql=" SELECT  * ".
		           " FROM    cxp_sol_dc ".
				   " WHERE   codemp='".$as_codemp."' ".
				   " ORDER BY numdc ASC";
        }    
		
      $this->ds_not->resetds("numdc");	  
	  $rs=$this->io_sql->select($ls_sql); 
	  if (($rs==false)&&($this->io_sql->message!=""))
	  {
	     $lb_valido=false;
		 if ($this->io_sql->message!="")
		  {                              
             $this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));		 
		  } 
	  }
	  else
	  {    
 	        if ($row=$this->io_sql->fetch_row($rs))
			{     
				$lb_valido=true;
				$datos=$this->io_sql->obtener_datos($rs);
				$this->ds_not->data=$datos;
				$this->io_sql->free_result($rs);  		
			}
			else
			{
 			     $lb_valido=false;
			}
	  }
      return $lb_valido;

	  }
   }

	function uf_select_solicitud_imprimir($as_codemp,$as_numsol)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion       uf_select_solicitud_imprimir
     //	Access        public
     //	Arguments     $as_codemp,$as_numsol
     //	Returns 	  $rs. Retorna un Resulset
     //	Description   Funcion que busca una solicitud para el imprimirla
     //////////////////////////////////////////////////////////////////////////////
        
		$this->ds_solicitud = new class_datastore();
/*		$ls_sql="SELECT cxp_solicitudes.*,cxp_dt_solicitudes.numrecdoc,cxp_rd.codtipdoc,cxp_documento.dentipdoc".
				"  FROM cxp_solicitudes,cxp_dt_solicitudes,cxp_rd,cxp_documento".
				" WHERE cxp_solicitudes.codemp='".$as_codemp."'".
				"   AND cxp_solicitudes.numsol='".$as_numsol."'".
				"   AND cxp_solicitudes.codemp=cxp_dt_solicitudes.codemp".
				"   AND cxp_solicitudes.numsol=cxp_dt_solicitudes.numsol".
				"   AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
				"   AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
				"   AND cxp_rd.codtipdoc=cxp_documento.codtipdoc".
				" GROUP BY cxp_dt_solicitudes.numsol";
*/		$ls_sql=" SELECT cxp_solicitudes.* ".
		        " FROM   cxp_solicitudes ".
				" WHERE  codemp='".$as_codemp."' AND numsol='".$as_numsol."'";
					  
		$rs=$this->io_sql->select($ls_sql);
        if ($rs===false)
	 	   {
			 $this->io_msg->message("Error en Sentencias");
			 $lb_valido=false;
		   }
		else
		   {
             if ($row=$this->io_sql->fetch_row($rs))
		        {
			      $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_solicitud->data=$datos;
				  $lb_valido=true;
				  $this->io_sql->free_result($rs);
                 }
             else
                {   
                  $lb_valido=false;			     
                }
		   }
return $lb_valido;		   	
}

function uf_select_dc_imprimir($as_codemp,$as_numdc)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion       uf_select_dc_imprimir
     //	Access        public
     //	Arguments     $as_codemp,$as_numsol
     //	Returns 	  $rs. Retorna un Resulset
     //	Description   Funcion que busca una nota para el imprimirla
     //////////////////////////////////////////////////////////////////////////////
        
		$this->ds_solicitud = new class_datastore();
		
		if($this->uf_tipo_proveedor($as_codemp,$as_numdc) )
		{
			$ls_sql=" SELECT D.numdc,D.estnotadc,D.fecope,D.desope,D.numsol,D.monto,   ".
					"        S.cod_pro,R.nompro,R.nitpro,R.dirpro,D.codope             ".
					" FROM   cxp_sol_dc D, cxp_solicitudes S, rpc_proveedor R          ". 
					" WHERE  D.codemp='".$as_codemp."'  AND  D.numdc='".$as_numdc."' AND ".
					"        D.codemp=S.codemp          AND  D.numsol=S.numsol       AND ".
					"        R.codemp=S.codemp          AND  D.codemp=R.codemp       AND ".
					"        S.cod_pro=R.cod_pro                                         ";
        }		
		else
		{
			$ls_sql=" SELECT D.numdc,D.estnotadc,D.fecope,D.desope,D.numsol,D.monto,     ".
					"        S.ced_bene, R.nombene as nompro,R.rifben as nitpro,         ".
					"        R.dirbene as dirpro, R.apebene as apellido, D.codope        ".
					" FROM   cxp_sol_dc D, cxp_solicitudes S, rpc_beneficiario R         ". 
					" WHERE  D.codemp='".$as_codemp."'  AND  D.numdc='".$as_numdc."' AND ".
					"        D.codemp=S.codemp          AND  D.numsol=S.numsol       AND ".
					"        R.codemp=S.codemp          AND  D.codemp=R.codemp       AND ".
					"        S.ced_bene=R.ced_bene                                       ";
        }		
        
		$rs=$this->io_sql->select($ls_sql);
        if ($rs===false)
	 	   {
			 $this->io_msg->message("Error en Sentencias");
			 $lb_valido=false;
		   }
		else
		   {
             if ($row=$this->io_sql->fetch_row($rs))
		        {
			      $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_solicitud->data=$datos;
				  $lb_valido=true;
				  $this->io_sql->free_result($rs);
                }
             else
                {   
                  $lb_valido=false;			     
                }
		   }
return $lb_valido;		   	
}

function uf_select_rec_doc_solicitud($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_sol_dt = new class_datastore();
	 
	 $ls_sql=" SELECT   C.numsol,C.numrecdoc,D.dentipdoc,R.montotdoc,R.mondeddoc,R.moncardoc,R.fecemidoc ".
		     "  FROM    cxp_dt_solicitudes C, cxp_solicitudes S, cxp_rd R, cxp_documento D ".
			 "  WHERE   C.codemp='".$as_codemp."' AND C.numsol='".$as_numero."' AND C.codemp=S.codemp AND 
			            C.codemp=R.codemp AND  R.codemp=S.codemp  AND C.numsol=S.numsol AND".
			 "          C.numrecdoc=R.numrecdoc AND D.codtipdoc=R.codtipdoc  AND  C.cod_pro=R.cod_pro AND 				         
				        C.ced_bene=R.ced_bene ".
			 " ORDER BY C.numrecdoc ASC";     				
   //     print $ls_sql;
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_sol_dt->data=$datos;
		  		  $this->io_sql->free_result($rs);  	
	          }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}

    function uf_select_cuenta_spg($as_codemp,$ls_numrec)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_servicios
     //	Access       public
     //	Arguments    $as_codemp,$as_codsol
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar los articulos asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
	 
	 $ls_sql=" SELECT numrecdoc as numdoc, spg_cuenta as cuenta, monto as montospg ".
	         " FROM   cxp_rd_spg ".
			 " WHERE  codemp='".$as_codemp."'    AND ".
			 "        numrecdoc='".$ls_numrec."'     ";  
	
	 $this->ds_spg_dt->resetds("numdoc");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {				
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_spg_dt->data=$datos;			  
           		  $this->io_sql->free_result($rs);  	
		     }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}

function uf_select_cuenta_scg($as_codemp,$ls_numrec)
{
 //////////////////////////////////////////////////////////////////////////////
 //	Funcion      uf_select_servicios
 //	Access       public
 //	Arguments    $as_codemp,$as_codsol
 //	Returns	     $rs (Resulset)
 //	Description  Devuelve un resulset para cargar los articulos asociados a una
 //              Solicitud, se utiliza en el catalogo de principal del solicitud
 //              de Ejecucion Presupuestaria  
 //////////////////////////////////////////////////////////////////////////////
 $ls_sql=" SELECT numrecdoc as numrec, sc_cuenta as cuentasc, monto as montosc, debhab as columna ".
		 " FROM   cxp_rd_scg                       ".
		 " WHERE  codemp='".$as_codemp."'    AND ".
		 "        numrecdoc='".$ls_numrec."'     ";  
	  
 $this->ds_scg_dt->resetds("numrec");			
 $rs=$this->io_sql->select($ls_sql);
 if ($rs===false)
	 {		 
		 $this->io_msg->message("Error en Sentencia");
		 $lb_valido=false;
	 }
	 else
	 {
	   if ($row=$this->io_sql->fetch_row($rs))
		  {				
			$lb_valido=true;				
			$datos=$this->io_sql->obtener_datos($rs);
			$this->ds_scg_dt->data=$datos;			  
   		    $this->io_sql->free_result($rs);  	
	      }
	   else
		  {   
			$lb_valido=false;			     
		  }	
	 }
return $lb_valido;		
}
    function uf_select_denfuefin($as_codfuefin)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_denfuefin
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	
		 $ls_sql=" SELECT denfuefin                      ".
				 " FROM   sigesp_fuentefinanciamiento    ".
				 " WHERE  codfuefin='".$as_codfuefin."'  ";	  
				 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_denfuefin="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_denfuefin=$row["denfuefin"];              
			 }	
		 } 
	return $ls_denfuefin;
	}
	function uf_select_nombre_pro($as_codemp,$as_codpro)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_nombre_pro
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	
		$ls_sql=" SELECT  nompro        ".
				" FROM    rpc_proveedor ".
				" WHERE   (codemp='".$as_codemp."'AND cod_pro='".$as_codpro."')";             
				 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_nombre="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_nombre=$row["nompro"];              
			 }	
		 } 
	return $ls_nombre;
	}
	function uf_select_nombre_bene($as_codemp,$as_cedben)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_denfuefin
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	     $ls_nombre ="";     
         $ls_apebene="";  
                
		 $ls_sql=" SELECT  nombene,apebene          ".
				 " FROM    rpc_beneficiario ".
				 " WHERE  (codemp='".$as_codemp."'AND ced_bene='".$as_cedben."')";    
				 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_nombre="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				 $ls_nombre =$row["nombene"];     
                 $ls_apebene=$row["apebene"];  
                 if(!empty($ls_apebene))            
                 {
                    $ls_nombre =$ls_nombre.", ".$ls_apebene;                             
                }
			 }	
		 } 
	return $ls_nombre;
	}
    function uf_select_denominacionspg($as_codemp,$as_cuentaspg)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_denfuefin
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	     $ls_denominacionspg="";
		 
		 $ls_sql=" SELECT denominacion ".
				 " FROM   spg_cuentas ".
				 " WHERE (codemp='".$as_codemp."'AND spg_cuenta='".$as_cuentaspg."')";       
				 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_denominacionspg="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_denominacionspg=$row["denominacion"];              
			 }	
		 } 
	return $ls_denominacionspg;
	}
    function uf_select_denominacionscg($as_codemp,$as_cuentascg)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_denfuefin
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_denominacionscg="";
		 
		$ls_sql=" SELECT denominacion ".
				" FROM   scg_cuentas  ".
		        " WHERE (codemp='".$as_codemp."'AND sc_cuenta='".$as_cuentascg."')";     
				 		 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_denominacionscg="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_denominacionscg=$row["denominacion"];              				
			 }	
		 } 
	return $ls_denominacionscg;
	}
    function uf_select_dencar($as_codemp,$as_codcar)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_dencar
	 //	Access       public
	 //	Arguments    $as_codemp,$as_codcar
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion del cargo
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_dencar="";
		 
		$ls_sql=" SELECT dencar ".
				" FROM   sigesp_cargos  ".
		        " WHERE (codemp='".$as_codemp."'AND codcar='".$as_codcar."')";     
				 		 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_dencar="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_dencar=$row["dencar"];              				
			 }	
		 } 
	return $ls_dencar;
	}
	function uf_select_dended($as_codemp,$as_codded)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_dended
	 //	Access       public
	 //	Arguments    $as_codemp,$as_codcar
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la deducción
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_dended="";
		 
		$ls_sql=" SELECT dended ".
				" FROM   sigesp_deducciones  ".
		        " WHERE (codemp='".$as_codemp."'AND codded='".$as_codded."')";     
				 		 
		$rs=$this->io_sql->select($ls_sql);
		if ($rs===false)
		 {
			$lb_valido=false;
			$ls_dended="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_dended=$row["dended"];              				
			 }	
		 } 
	return $ls_dended;
	}
    function uf_tipo_proveedor($as_codemp,$as_numdc)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion       uf_tipo_proveedor
     //	Access        public
     //	Arguments     $as_codemp,$as_numsol
     //	Returns 	  $rs. Retorna un Resulset
     //	Description   Funcion que busca una solicitud para el imprimirla
     //////////////////////////////////////////////////////////////////////////////
        		
	 $lb_valido=false;			     
				
	 $ls_sql=" SELECT  * ".
			 " FROM    cxp_sol_dc ".
			 " WHERE   codemp='".$as_codemp."' AND numdc='".$as_numdc."' AND ".
			 "         cod_pro<>'----------' ";
					
		$rs=$this->io_sql->select($ls_sql);
        if ($rs===false)
	 	   {			 
			 $this->io_msg->message("Error en Sentencias");
			 $lb_valido=false;
		     $this->io_sql->free_result($rs);
		   }
		else
		   {
             if ($row=$this->io_sql->fetch_row($rs))
		        {			      
				  $lb_valido=true;				
                }
             else
                {   
                  $lb_valido=false;			     
                }
		   }
return $lb_valido;		   	
}

    function uf_select_dirpro($as_codemp,$as_codpro)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_nombre_pro
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_dirpro="";	
		$ls_sql=" SELECT  dirpro        ".
				" FROM    rpc_proveedor ".
				" WHERE   (codemp='".$as_codemp."'AND cod_pro='".$as_codpro."')";             
				 
		$rs=$this->io_sql->select($ls_sql);
		if (($rs==false)&&($this->io_sql->message!=""))
		 {
			$lb_valido=false;
			$ls_dirpro="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_dirpro=$row["dirpro"];              
			 }	
		 } 
	return $ls_dirpro;
	}
	function uf_select_dirben($as_codemp,$as_codpro)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_nombre_pro
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_dirpro="";
		$ls_sql=" SELECT  dirbene          ".
				" FROM    rpc_beneficiario ".
				" WHERE   (codemp='".$as_codemp."'AND ced_bene='".$as_codpro."')";             
				 
		$rs=$this->io_sql->select($ls_sql);
		if (($rs==false)&&($this->io_sql->message!=""))
		 {
			$lb_valido=false;
			$ls_dirpro="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_dirpro=$row["dirbene"];              
			 }	
		 } 
	return $ls_dirpro;
	}
	
	function uf_select_spg($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
	 
	 $ls_sql=" SELECT P.spg_cuenta,P.codestpro,SUM(P.monto) as montospg    ".
                 " FROM   cxp_dt_solicitudes C, cxp_rd_spg P                            ".
                 " WHERE  C.codemp='".$as_codemp."'  AND  C.numsol ='".$as_numero."' AND".
                 "        C.codemp=P.codemp          AND  C.numrecdoc=P.numrecdoc    AND 
		          C.cod_pro=P.cod_pro        AND  C.ced_bene=P.ced_bene      AND
                          C.codtipdoc=P.codtipdoc  ".
             "GROUP by P.spg_cuenta,P.codestpro,C.numsol,C.numrecdoc";    
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;		
				  $this->io_sql->datastore->reset_ds();
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_spg_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}

	function uf_select_scg($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
	 
	 $ls_sql=" SELECT C.numsol,C.numrecdoc,P.sc_cuenta,                               ".
	         "        SUM(P.monto) as montoscg, debhab as columna                     ".
             " FROM   cxp_dt_solicitudes C, cxp_rd_scg P                              ".
             " WHERE  C.codemp='".$as_codemp."'  AND C.numsol ='".$as_numero."' AND   ".
             "        C.codemp=P.codemp          AND  C.numrecdoc=P.numrecdoc    AND 
				      C.cod_pro=P.cod_pro        AND 
				      C.ced_bene=P.ced_bene      AND C.codtipdoc=P.codtipdoc       ".
             " GROUP by P.sc_cuenta,C.numsol,C.numrecdoc,columna".
			 " ORDER by columna,P.sc_cuenta";    
	 $this->ds_scg_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $this->io_sql->datastore->reset_ds();
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_scg_dt->data=$datos;
			      $this->io_sql->free_result($rs);  	
		     }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	
	function uf_select_ret_iva_cab($as_codemp,$as_numcom)
	{
     //////////////////////////////////////////////////////////////////////////////    
     //	Funcion       uf_select_ret_iva_cab
     //	Access        public
     //	Arguments     $as_codemp,$as_numcom
     //	Returns 	  $lb_valido;
     //	Description   Funcion que busca una Ret. de Iva para el imprimirla    
     //////////////////////////////////////////////////////////////////////////////        
	 $this->ds_ret = new class_datastore();
	 $ls_sql="SELECT a.* 
	 		  FROM scb_cmp_ret_op a, scb_dt_cmp_ret_op b
			  WHERE a.codemp='".$as_codemp."' AND b.numdoc ='".$as_numcom."' AND a.codret ='0000000001' AND a.codemp=b.codemp AND a.numcom=b.numcom AND a.codret=b.codret";
	 $rs_data = $this->io_sql->select($ls_sql);
	 if ($rs_data===false)
	   {
               $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_ret_iva_cab; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	        print ($this->io_sql->message);
          }
	 else
	   {
		 if ($row=$this->io_sql->fetch_row($rs_data))
			{
			  $lb_valido=true;				
			  $datos=$this->io_sql->obtener_datos($rs_data);
			  $this->ds_ret->data=$datos;
			  $this->io_sql->free_result($rs_data);
			}
		 else
			{   
			  $lb_valido=false;			      
			}		   
	   }
    return $lb_valido;		   	
    }

	function uf_select_ret_iva_det($as_codemp,$as_numcom)
	{
     //////////////////////////////////////////////////////////////////////////////    
     //	Funcion       uf_select_ret_iva_det
     //	Access        public
     //	Arguments     $as_codemp,$as_numcom
     //	Returns 	  $lb_valido;
     //	Description   Funcion que busca los detalles de una Ret. de Iva para el imprimirla    
     //////////////////////////////////////////////////////////////////////////////        
	 $this->ds_det_ret = new class_datastore();
	 $ls_sql="SELECT codemp, codret, numcom,numope, fecfac, numfac, numcon, numnd,
					 numnc, tiptrans, totcmp_sin_iva , totcmp_con_iva,
					 basimp, porimp, totimp,
					 iva_ret, desope, numsop, codban, ctaban, numdoc, codope
			  FROM scb_dt_cmp_ret_op
			  WHERE  codemp='".$as_codemp."' AND numdoc='".$as_numcom."' AND codret='0000000001'
			  GROUP BY codemp,numfac,porimp";		
        $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
	   {
		 $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_ret_iva_det; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 $lb_valido=false;
	        print ($this->io_sql->message);
          }
	 else
	   {
		 if ($row=$this->io_sql->fetch_row($rs))
			{
			  $lb_valido=true;				
			  $datos=$this->io_sql->obtener_datos($rs);
			  $this->ds_det_ret->data=$datos;
			  $this->io_sql->free_result($rs);
			}
		 else
			{   
			  $lb_valido=false;			      
			}		   
	   }
    return $lb_valido;		   	
    }

	function uf_select_monfac($as_codemp,$as_numfac)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_monfac
	 //	Access       public
	 //	Arguments    $as_codemp,$as_numfac
	 //	Returns      $ls_numcontrol
	 //	Description  Metodo que devuelve el numero de control de una recepcion de 
	 //              Documento
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_numcontrol="";
		$monfac=0;
		$ls_sql  =" SELECT montotdoc FROM cxp_rd WHERE (codemp='".$as_codemp."' AND numrecdoc='".$as_numfac."')";             
		$rs_data = $this->io_sql->select($ls_sql);
		if ($rs_data===false)
		   {
			 $lb_valido=false;
			 $ls_dirpro="";
                      $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_monfac; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));

		   }		
		else
		   {
		     if ($row=$this->io_sql->fetch_row($rs_data))
			    { 		   
				  $monfac=$row["montotdoc"];              
			    }	
		   }  
	return $monfac;
	}
	function uf_select_sol_cargos($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_car_dt = new class_datastore();
	 
	 $ls_sql=" SELECT C.numsol as numsol,C.numrecdoc as numrecdoc,
	                  P.codcar as codcar,SUM(P.monobjret) as monobjretcar,
					  SUM(P.monret) as objretcar
               FROM   cxp_dt_solicitudes C, cxp_rd_cargos P
               WHERE  C.codemp='".$as_codemp."'  AND  C.numsol ='".$as_numero."' AND
                      C.codemp=P.codemp          AND  C.numrecdoc=P.numrecdoc    AND
					  C.cod_pro=P.cod_pro        AND  C.ced_bene=P.ced_bene      
               GROUP by P.codcar,C.numsol,C.numrecdoc";  

	 $this->ds_car_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("CLASE->SIGESP_CXP_CLASS_REPORT; METODO->uf_select_sol_cargos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_car_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	function uf_select_sol_deducciones($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_sol_deducciones
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_ded_dt = new class_datastore();
	 
	 $ls_sql=" SELECT C.numsol as numsol,C.numrecdoc as numrecdoc,
	                  P.codded as codded,SUM(P.monobjret) as monobjretded, 
					  SUM(P.monret) as objretded
               FROM   cxp_dt_solicitudes C, cxp_rd_deducciones P
               WHERE  C.codemp='".$as_codemp."'  AND  C.numsol ='".$as_numero."' AND
                      C.codemp=P.codemp          AND  C.numrecdoc=P.numrecdoc    AND
					  C.cod_pro=P.cod_pro        AND  C.ced_bene=P.ced_bene      
               GROUP by P.codded,C.numsol,C.numrecdoc";    
			  				
	 $this->ds_ded_dt->resetds("numsol");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_ded_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	function uf_select_spg_dc($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
	 
	 $ls_sql=" SELECT C.numsol,C.numdc,P.spg_cuenta,P.codestpro,SUM(P.monto) as montospg, P.codope ".
             " FROM   cxp_sol_dc C, cxp_dc_spg P                            ".
             " WHERE  C.codemp='".$as_codemp."'  AND  C.numdc ='".$as_numero."' AND".
             "        C.codemp=P.codemp          AND  C.numdc=P.numdc       ".
             "GROUP by P.spg_cuenta";    
						
	 $this->ds_spg_dt->resetds("numdc");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_spg_dt->data=$datos;			  				 
		    	  $this->io_sql->free_result($rs);  	
			 }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	function uf_select_scg_dc($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
	 
	 $ls_sql=" SELECT C.numsol,C.numdc,P.sc_cuenta,                               ".
	         "        SUM(P.monto) as montoscg, debhab as columna                     ".
             " FROM   cxp_sol_dc C, cxp_dc_scg P                              ".
             " WHERE  C.codemp='".$as_codemp."'  AND  C.numdc ='".$as_numero."' AND   ".
             "        C.codemp=P.codemp          AND  C.numdc=P.numdc         ".
             " GROUP by P.sc_cuenta".
			 " ORDER by columna";    
			  
	 $this->ds_scg_dt->resetds("numdc");			
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_scg_dt->data=$datos;
			      $this->io_sql->free_result($rs);  	
		     }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	function uf_select_rifpro($as_codemp,$as_codpro)
	{
	 //////////////////////////////////////////////////////////////////////////////
	 //	Funcion      uf_select_rifpro
	 //	Access       public
	 //	Arguments    $as_codtipsol
	 //	Returns      $rs (Resulset)	
	 //	Description  Variable string con la denominacion de la fuente de 
	 //              Financiamiento
	 //////////////////////////////////////////////////////////////////////////////
	    $ls_rifpro="";
		$ls_sql=" SELECT  rifpro        ".
				" FROM    rpc_proveedor ".
				" WHERE   (codemp='".$as_codemp."'AND cod_pro='".$as_codpro."')";             
				 
		$rs=$this->io_sql->select($ls_sql);
		if (($rs==false)&&($this->io_sql->message!=""))
		 {
			$lb_valido=false;
			$ls_rifpro="";
			$this->io_msg->message('Error en Consulta SQL !!!'); 
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($this->io_sql->message));
		 }		
		else
		 {
			 if($row=$this->io_sql->fetch_row($rs))
			 { 		   
				$ls_rifpro=$row["rifpro"];              
			 }	
		 } 
	return $ls_rifpro;
	}
	function uf_select_rec_doc_total($as_codemp,$as_numero)
	{
     //////////////////////////////////////////////////////////////////////////////
     //	Funcion      uf_select_rec_doc_solicitud
     //	Access       public
     //	Arguments    $as_codemp,$as_numero
     //	Returns	     $rs (Resulset)
     //	Description  Devuelve un resulset para cargar las receptciones asociados a una
     //              Solicitud, se utiliza en el catalogo de principal del solicitud
     //              de Ejecucion Presupuestaria  
     //////////////////////////////////////////////////////////////////////////////
     $this->ds_sol_t = new class_datastore();
	 
	 $ls_sql=" SELECT SUM(cxp_rd.montotdoc) as montotdoc,SUM(cxp_rd.mondeddoc) as mondeddoc, SUM(cxp_rd.moncardoc) as moncardoc".
			 "   FROM cxp_dt_solicitudes , cxp_solicitudes , cxp_rd , cxp_documento ".
			 "  WHERE cxp_dt_solicitudes.codemp='".$as_codemp."'".
			 "    AND cxp_dt_solicitudes.numsol='".$as_numero."'".
			 "    AND cxp_dt_solicitudes.codemp=cxp_solicitudes.codemp ".
			 "    AND cxp_dt_solicitudes.codemp=cxp_rd.codemp".
			 "    AND cxp_rd.codemp=cxp_solicitudes.codemp".
			 "    AND cxp_dt_solicitudes.numsol=cxp_solicitudes.numsol".
			 "    AND cxp_dt_solicitudes.numrecdoc=cxp_rd.numrecdoc".
			 "    AND cxp_documento.codtipdoc=cxp_rd.codtipdoc".
			 "    AND cxp_dt_solicitudes.cod_pro=cxp_rd.cod_pro".
			 "    AND cxp_dt_solicitudes.ced_bene=cxp_rd.ced_bene";
/*	 $ls_sql=" SELECT C.numsol,C.numrecdoc,D.dentipdoc,SUM(R.montotdoc) as montotdoc,
				      SUM(R.mondeddoc) as mondeddoc, SUM(R.moncardoc) as moncardoc,R.fecemidoc
			   FROM   cxp_dt_solicitudes C, cxp_solicitudes S, cxp_rd R, cxp_documento D
			   WHERE  C.codemp='".$as_codemp."' AND C.numsol='".$as_numero."' AND C.codemp=S.codemp AND
				      C.codemp=R.codemp AND R.codemp=S.codemp AND C.numsol=S.numsol AND
				      C.numrecdoc=R.numrecdoc AND D.codtipdoc=R.codtipdoc AND C.cod_pro=R.cod_pro AND
				      C.ced_bene=R.ced_bene
			   GROUP BY C.codemp,C.numsol,C.numrecdoc,D.dentipdoc,R.fecemidoc";     				
*/  //   print $ls_sql."<br>";
	 $rs=$this->io_sql->select($ls_sql);
	 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_sol_t->data=$datos;
		  		  $this->io_sql->free_result($rs);  	
	          }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
    return $lb_valido;		
	}
	
	function uf_generar_islr($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codpro,$ls_cedbene,$ls_tipodestino,$ls_numdocres,$ls_nrocontrol,$ld_fecdocres)
	{
		if($ls_tipodestino=='P')
		{
			$ls_campos= ",d.rifpro,d.nitpro,d.nompro,d.dirpro,d.telpro ";
			$ls_from  = " ,rpc_proveedor d ";
			$ls_where = " AND c.cod_pro='".$ls_codpro."' AND c.cod_pro=d.cod_pro ";
		}
		else
		{
			$ls_campos= ",d.rifben,d.ced_bene,d.nombene,b.apebene,d.dirbene,d.telbene ";
			$ls_from  = " ,rpc_beneficiario d ";
			$ls_where = " AND c.ced_bene='".$ls_cedbene."' AND c.ced_bene=d.ced_bene ";
		}
		
		
		$ls_sql="SELECT a.monobjret,b.porded,a.monto,b.codded,b.islr,c.cod_pro,c.ced_bene,c.tipo_destino".$ls_campos."
				 FROM scb_movbco_scg a,sigesp_deducciones b,scb_movbco c".$ls_from."
				 WHERE c.numdoc='".$ls_numdoc."' AND c.codban='".$ls_codban."' AND c.ctaban='".$ls_ctaban."' AND c.codope='OP' ".$ls_where." AND b.islr=1 AND a.codded=b.codded 
				 AND a.numdoc=c.numdoc AND a.codban=c.codban AND a.ctaban=c.ctaban AND a.codope=c.codope ";

		$rs=$this->io_sql->select($ls_sql);
		 if ($rs===false)
		 {		 
			 $this->io_msg->message("Error en Sentencia");
			 $lb_valido=false;
		 }
         else
         {
		     if ($row=$this->io_sql->fetch_row($rs))
		     {
				  $lb_valido=true;				
				  $datos=$this->io_sql->obtener_datos($rs);
				  $this->ds_retenciones->data=$datos;
		  		  $this->io_sql->free_result($rs);  	
	          }
             else
             {   
                 $lb_valido=false;			     
             }	
         }
   	 	return $lb_valido;		
	}
	
	
}
?>
