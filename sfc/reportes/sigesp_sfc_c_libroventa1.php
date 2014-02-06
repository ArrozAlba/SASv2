<?php

class sigesp_sfc_c_libroventa
{

var $ds_libroventa;
var $ds_detventa;
var $ds_dtdeducciones;
var $ds_creditos;
		
	function sigesp_sfc_c_libroventa($conn)
	{
	  require_once("../../shared/class_folder/class_funciones.php");
	  require_once("../../shared/class_folder/class_mensajes.php");
	  require_once("../../shared/class_folder/class_sql.php");  
	  $this->io_sql= new class_sql($conn);
	  $this->io_msg= new class_mensajes();
  	  $this->io_funcion = new class_funciones();
	  
	}


function uf_load_libro_ventas($as_fechadesde,$as_fechahasta)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_libro_ventas
//	          Access:  public
//          Arguments  
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.  
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  /*.                   
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  21/04/2006        Fecha Última Actualización:21/04/2006.	 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   $this->ds_libroventa = new class_datastore();
$la_datemp=$_SESSION["la_empresa"];
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];

$ls_sql= "Select DISTINCT(sfc_factura.*), sfc_cliente.cedcli,sfc_cliente.razcli from sfc_factura,sfc_cliente where sfc_factura.codemp='".$ls_codemp."' and sfc_factura.codtiend='".$ls_codtie."' and sfc_factura.codcli=sfc_cliente.codcli and sfc_factura.fecemi BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' ORDER BY sfc_factura.fecemi ASC";

//$ls_sql= "Select sfc_factura.*, sfc_cliente.cedcli,sfc_cliente.razcli,SUM(dt.candev) as devol from sfc_factura,sfc_cliente,sfc_detfactura dt where and sfc_factura.fecemi BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' and sfc_factura.codcli=sfc_cliente.codcli and dt.c sfc_factura.codemp='".$ls_codemp."' and sfc_factura.codtiend='".$ls_codtie."'andev<>0  GROUP BY sfc_factura.numfac,dt.candev ORDER BY sfc_factura.fecemi ASC";

		 // print $ls_sql;
   $rs_data = $this->io_sql->select($ls_sql);
   if ($rs_data===false)
      {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_SFC_C_LIBROVENTA; METODO->uf_load_libro_ventas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	    $li_numrows=$this->io_sql->num_rows($rs_data);
		if ($li_numrows>0)
		   {
		     $datos=$this->io_sql->obtener_datos($rs_data);
		     $this->ds_libroventa->data=$datos;
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



function uf_load_libro_detventas($as_fechadesde,$as_fechahasta,$as_numfac)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_libro_ventas
//	          Access:  public
//          Arguments  
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.  
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  /*.                   
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  21/04/2006        Fecha Última Actualización:21/04/2006.	 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 $this->ds_detventa = new class_datastore();
$la_datemp=$_SESSION["la_empresa"];
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];

 $ls_cadena= "Select f.numfac, dt.canpro ,dt.porimp ,dt.prepro ,dt.candev  from sfc_factura f,sfc_detfactura dt where f.codemp='".$ls_codemp."' and f.codtiend='".$ls_codtie."' and dt.numfac='".$as_numfac."' and f.numfac=dt.numfac and f.fecemi BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' ORDER BY f.fecemi ASC";
			   

//Select f.*, dt.canpro,dt.porimp,dt.prepro,dt.candev from sfc_factura f,sfc_detfactura dt where f.codemp='0001' and f.codtiend='0014' and dt.numfac='FAC-E00000000000000001894' and f.numfac=dt.numfac and f.fecemi BETWEEN '2008-01-01' AND '2008-01-31'  ORDER BY f.fecemi ASC

  //print $ls_cadena;
  $rs_data = $this->io_sql->select($ls_cadena);
   if ($rs_data===false)
      {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_SFC_C_LIBROVENTA; METODO->uf_load_libro_detventas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	    $li_numrows=$this->io_sql->num_rows($rs_data);
		if ($li_numrows>0)
		   {
		     $datos=$this->io_sql->obtener_datos($rs_data);
		     $this->ds_detventa->data=$datos;
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


function uf_load_notas_ventas($numfac,$codcli)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_libro_ventas
//	          Access:  public
//          Arguments  
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.  
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  /*.                   
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  21/04/2006        Fecha Última Actualización:21/04/2006.	 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   $this->ds_libroventa = new class_datastore();
$la_datemp=$_SESSION["la_empresa"];
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];

 $ls_sql="SELECT sfc_nota.codcli,sfc_nota.numnot,sfc_nota.tipnot,sfc_nota.nro_factura FROM sfc_nota WHERE sfc_nota.nro_factura ='".$numfac."' and sfc_nota.codcli='".$codcli."' ";
			
//   print $ls_sql;
   $rs_data = $this->io_sql->select($ls_sql);
   if ($rs_data===false)
      {
	    $lb_valido=false;
 	    $this->io_msg->message("CLASE->SIGESP_SFC_C_LIBROVENTA; METODO->uf_load_notas_ventas; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	  }
   else
      {
	    $li_numrows=$this->io_sql->num_rows($rs_data);
		if ($li_numrows>0)
		   {
		     $datos=$this->io_sql->obtener_datos($rs_data);
		     $this->ds_libroventa->data=$datos;
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





function uf_load_dt_creditos($as_codemp,$as_numrecdoc,$as_codpro,$as_cedbene)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_dt_creditos
//	          Access:  public
//          Arguments  
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  /*.                   
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/06/2006        Fecha Última Actualización:20/06/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_sql= "SELECT monto as basimp,montoret as impiva".
			 "  FROM sfc_factura ".
			 " WHERE codemp='".$as_codemp."'".
			 "   AND numfac='".$as_numrecdoc."'".
			 "   AND codtiend='".$as_codpro."'".
			 "   AND cedcli='".$as_cedbene."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->SIGESP_SFC_C_LIBROVENTA; METODO->uf_load_dt_creditos; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		$li_numrows = $this->io_sql->num_rows($rs_data);
		if ($li_numrows>0)
		{
			$lb_valido=true;
			$datos=$this->io_sql->obtener_datos($rs_data);
			$this->ds_creditos = new class_datastore();
			$this->ds_creditos->data=$datos;
			$this->io_sql->free_result($rs_data);
		}
	}		
	return $lb_valido;
}

function uf_load_dt_deducciones($as_codemp,$as_numfac,$as_numcontrol,$as_codpro,$as_cedbene)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          Método:  uf_load_dt_deducciones
//	          Access:  public
//          Arguments  
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.           
//       Description:  /*.                   
//     Elaborado Por:  Ing. Néstor Falcón.
// Fecha de Creación:  20/06/2006        Fecha Última Actualización:20/06/2006.	 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  $this->ds_dtdeducciones = new class_datastore();
  $ls_sql=" SELECT DISTINCT max(a.numrecdoc) as numrecdoc,max(a.monobjret) as monobjret,SUM(a.monret) as monret,max(a.porded) as porded,max(b.codret) as codret,max(b.numcom) as numcom,max(b.iva_ret) as iva_ret".
          "   FROM cxp_rd_deducciones a,scb_dt_cmp_ret b                                                                         ".
          "  WHERE a.codemp=b.codemp AND a.numrecdoc=b.numfac AND a.codemp='".$as_codemp."' AND a.numrecdoc='".$as_numfac."' AND ".
		  "        a.cod_pro='".$as_codpro."' AND a.ced_bene='".$as_cedbene."'                                              ".
		  " GROUP BY a.numrecdoc";
		 // print "<br><br><br>".$ls_sql."<br><br>";
  $rs_data=$this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido=false;
 	   $this->io_msg->message("CLASE->SIGESP_SFC_C_LIBROVENTA; METODO->uf_load_dt_deducciones; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   print ($this->io_sql->message); 
     }
   else
     {
	   $li_numrows=$this->io_sql->num_rows($rs_data);
	   if ($li_numrows>0)
		  {
		    $datos=$this->io_sql->obtener_datos($rs_data);
		    $this->ds_dtdeducciones->data=$datos;
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
	function uf_load_totaldocumento($as_codemp,$as_numrecdoc,$as_codpro,/*$as_cedbene,*/&$ai_monconiva)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_saf_select_activo
		//         Access: public (sigesp_sim_d_activos)
		//      Argumento: $as_codemp    //codigo de empresa 
		//				   $as_numrecdoc //codigo de activo
		//				   $as_codpro    //codigo de activo
		//				   $as_cedbene   //codigo de activo
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que obtien
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 01/01/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		
		$ls_sql = "SELECT sum(monto) AS montot ".
				  " FROM sfc_factura".
				  "WHERE codemp='".$as_codemp."'".
				  "  AND numfac='".$as_numrecdoc."'".
				  "  AND codtiend='".$as_codpro."'";
				 // "  AND codcli='".$as_cedbene."'";
				  
				 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->activo MÉTODO->uf_saf_select_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_monconiva= $row["montot"];
				$lb_valido=true;
			}
		}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	}//fin de la function uf_saf_select_movimientos()

}
?>