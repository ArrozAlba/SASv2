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


function sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,$alias_tabla,$ls_codtie) {

$add_sql = '';
if ($ls_tienda_desde=='') {

$add_sql = "AND $alias_tabla.codtiend='$ls_codtie'";

}else {
//$add_sql="";
$add_sql = "AND $alias_tabla.codtiend  BETWEEN '$ls_tienda_desde' AND '$ls_tienda_hasta'";

}

return $add_sql;
}

function uf_load_libro_ventas($as_fechadesde,$as_fechahasta,$ls_tienda_desde,$ls_tienda_hasta)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          M�todo:  uf_load_libro_ventas
//	          Access:  public
//          Arguments
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.
//       Description:  /*.
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  21/04/2006        Fecha �ltima Actualizaci�n:21/04/2006.
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   $this->ds_libroventa = new class_datastore();
$la_datemp=$_SESSION["la_empresa"];
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];

/*$ls_sql= "Select DISTINCT(sfc_factura.*), sfc_cliente.cedcli,sfc_cliente.razcli from sfc_factura,sfc_cliente where " .
		"sfc_factura.codemp='".$ls_codemp."' and  ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_desde,'sfc_factura',$ls_codtie)." and " .
		"sfc_factura.codcli=sfc_cliente.codcli and substring(cast (sfc_factura.fecemi as char(30)),0,11) BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' " .
		"ORDER BY sfc_factura.numfac,sfc_factura.fecemi ASC";*/


 $ls_sql ="
    ((Select DISTINCT
                    
                    sfc_factura.codemp
                   ,sfc_factura.numfac
                   ,sfc_factura.numcot
                   ,sfc_factura.codtiend
                   ,sfc_factura.codcli
                   ,sfc_factura.fecemi
                   ,sfc_factura.numcon
                   ,sfc_factura.estfaccon
                   ,sfc_factura.monto
                   ,sfc_factura.montopar
                   ,sfc_cliente.cedcli
                   ,sfc_cliente.razcli
                   ,(SELECT cast(sum(
                             case porimp
                               WHEN '8.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as base8
                     ,(SELECT cast(sum(
                             case porimp
                               WHEN '12.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as base12
                   ,(SELECT cast(sum(
                             case porimp
                               WHEN '0.0' THEN ((cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2))))
                               else 0.00
                             END)  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as exe
                    ,(SELECT cast(sum(
                             case porimp
                               WHEN '8.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))*((cast((porimp/100) as numeric(10,2))))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as iva8
                     ,(SELECT cast(sum(
                             case porimp
                               WHEN '12.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))*((cast((porimp/100) as numeric(10,2))))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as iva12
           from
                   sfc_factura
                  ,sfc_cliente
          where
                    sfc_factura.codemp='$ls_codemp'
                    ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sfc_factura',$ls_codtie)."
                    and estfaccon != 'A'
                    and sfc_factura.codcli=sfc_cliente.codcli
                    and substring(cast (sfc_factura.fecemi as char(30)),0,11) BETWEEN '$as_fechadesde' AND '$as_fechahasta'
    )
    union
    (
    Select DISTINCT
                    
                    sfc_factura.codemp
                   ,sfc_factura.numfac
                   ,sfc_factura.numcot
                   ,sfc_factura.codtiend
                   ,sfc_factura.codcli
                   ,sfc_factura.fecanu as fecemi
                   ,sfc_factura.numcon
                   ,sfc_factura.estfaccon
                   ,0 as monto
                   ,sfc_factura.montopar
                   ,sfc_cliente.cedcli
                   ,sfc_cliente.razcli
                   ,0.00 as base8
                   ,0.00 as base12
                   ,0.00 as exe
                   ,0.00 as iva8
                   ,0.00 as iva12
           from
                   sfc_factura
                  ,sfc_cliente
          where
                    sfc_factura.codemp='$ls_codemp'
                    ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sfc_factura',$ls_codtie)."
                    and estfaccon = 'A'
                    and sfc_factura.codcli=sfc_cliente.codcli
                    and substring(cast (sfc_factura.fecemi as char(30)),0,11) BETWEEN '$as_fechadesde' AND '$as_fechahasta'
                    and substring(cast (sfc_factura.fecanu as char(30)),0,11) BETWEEN '$as_fechadesde' AND '$as_fechahasta'
            )
        )union(
        Select DISTINCT
                sfc_factura.codemp
               ,sfc_factura.numfac
               ,sfc_factura.numcot
               ,sfc_factura.codtiend
               ,sfc_factura.codcli
               ,sfc_factura.fecanu as fecemi
               ,sfc_factura.numcon
               ,sfc_factura.estfaccon
               ,(-sfc_factura.monto) as monto
               ,sfc_factura.montopar
               ,sfc_cliente.cedcli
               ,sfc_cliente.razcli
               ,-1*(SELECT cast(sum(
                             case porimp
                               WHEN '8.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as base8
              ,-1*(SELECT cast(sum(
                             case porimp
                               WHEN '12.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as base12
               ,-1*(SELECT cast(sum(
                             case porimp
                               WHEN '0.0' THEN ((cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2))))
                               else 0.00
                             END)  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as exe
                    ,-1*(SELECT cast(sum(
                             case porimp
                               WHEN '8.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))*((cast((porimp/100) as numeric(10,2))))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as iva8
                     ,-1*(SELECT cast(sum(
                             case porimp
                               WHEN '12.0' THEN (cast(((cast(canpro as numeric(10,2))*(cast(prepro as numeric(10,2))))) as numeric(10,2)))*((cast((porimp/100) as numeric(10,2))))
                               ELSE 0.00
                             END  )  as numeric(10,2))
                        FROM
                            public.sfc_detfactura
                        WHERE
                            numfac = sfc_factura.numfac) as iva12
       from
               sfc_factura
              ,sfc_cliente
      where
                sfc_factura.codemp='$ls_codemp'
                ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'sfc_factura',$ls_codtie)."
                and estfaccon = 'A'
                and sfc_factura.codcli=sfc_cliente.codcli
                and substring(cast (sfc_factura.fecanu as char(30)),0,11) BETWEEN '$as_fechadesde' AND '$as_fechahasta'
                and substring(cast (sfc_factura.fecemi as char(30)),0,11) < '$as_fechadesde'

        )
                ORDER BY
                   fecemi,
                   numfac
                ASC
";
//print $ls_sql;

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

///***** No SE Usan  *******//

function uf_load_libro_detventas($as_fechadesde,$as_fechahasta,$as_numfac)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          M�todo:  uf_load_libro_ventas
//	          Access:  public
//          Arguments
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.
//       Description:  /*.
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  21/04/2006        Fecha �ltima Actualizaci�n:21/04/2006.
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 $this->ds_detventa = new class_datastore();
$la_datemp=$_SESSION["la_empresa"];
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=$_SESSION["ls_codtienda"];

 $ls_cadena= "Select f.numfac, dt.canpro ,dt.porimp ,dt.prepro ,dt.candev  from sfc_factura f,sfc_detfactura dt where " .
 		"f.codemp='".$ls_codemp."' and ".$this->sql_dinamica ($ls_tienda_desde,$ls_tienda_hasta,'f',$ls_codtie)." and " .
 		"dt.numfac='".$as_numfac."' and f.numfac=dt.numfac and substring(sfc_factura.fecemi,0,11) BETWEEN '".$as_fechadesde."' AND '".$as_fechahasta."' " .
 		"ORDER BY f.fecemi ASC";


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
//	          M�todo:  uf_load_libro_ventas
//	          Access:  public
//          Arguments
//    $as_fechadesde:  Fecha a partir del cual se buscaran los documentos.
//    $as_fechahasta:  Fecha hasta el cual se buscaran los documentos.
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.
//       Description:  /*.
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  21/04/2006        Fecha �ltima Actualizaci�n:21/04/2006.
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


/********  HASTA Aca  *******/


function uf_load_dt_creditos($as_codemp,$as_numrecdoc,$as_codpro,$as_cedbene)
{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	          M�todo:  uf_load_dt_creditos
//	          Access:  public
//          Arguments
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.
//       Description:  /*.
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/06/2006        Fecha �ltima Actualizaci�n:20/06/2006.
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
//	          M�todo:  uf_load_dt_deducciones
//	          Access:  public
//          Arguments
//           Returns:  $lb_valido=Variable booleana que devuelve true si la sentencia sql fue ejecutada
//                     sin problemas y el resulset obtuvo registros de la consulta.
//       Description:  /*.
//     Elaborado Por:  Ing. N�stor Falc�n.
// Fecha de Creaci�n:  20/06/2006        Fecha �ltima Actualizaci�n:20/06/2006.
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
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 01/01/2006
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
			$this->io_msg->message("CLASE->activo M�TODO->uf_saf_select_activo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
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
