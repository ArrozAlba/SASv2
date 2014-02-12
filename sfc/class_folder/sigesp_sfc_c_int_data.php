<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cajero
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cajero.
 // Fecha:       - 04/12/2006
 // ACTUALIZADO POR: ING. ZULHEYMAR RODRÍGUEZ     Fecha:  17-02-2009
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_int_data
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 var $io_data;

function sigesp_sfc_c_int_data()
{
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("../shared/class_folder/class_datastore.php");


    $this->io_data= new class_datastore();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}

function uf_select_comprobante($as_codemp,$as_procedencia,$as_fecha,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que verifica si existe o no el comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_newfec=$this->io_funcion->uf_convertirdatetobd($as_fecha);
		$ls_sql="SELECT comprobante ".
			   "  FROM sigesp_cmp ".
			   " WHERE codemp='".$as_codemp."' ".
			   "   AND procede='".$as_procedencia."' ".
			   "   AND fecha= '".$ls_newfec."' ".
			   "   AND codban='".$as_codban."'".
			   "   AND ctaban='".$as_ctaban."'";
		//print 'select comp.'.$ls_sql;
		$li_numrows=$this->io_sql->select($ls_sql);
		if($li_numrows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_select_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else  
		{ 
			if($row=$this->io_sql->fetch_row($li_numrows)) 
			{ 
				$lb_existe=true;
			}  
		}		
		return $lb_existe;
	} // end function uf_select_comprobante
	
	
function uf_buscar_contrapartida($ls_spicuenta,&$ls_cuenta)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	Function:            uf_buscar_contrapartida
	//	Descripcion:       Metodo que selecciona la cuenta contable asociada a la presupuestaria de ingreso.
	//	FechaCreación:  16-11-2008	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_cuenta="";
	$lb_valido=false;
	 $ls_sql=  " SELECT sc_cuenta".
					"   FROM spi_cuentas".
					" WHERE spi_cuenta like '%".$ls_spicuenta."%'";
					//print '<br><br>ls_sql->'.$ls_sql.'<br><br>';
	 $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false) 
	{
			$this->io_msg->message($this->io_funcion->uf_convertirmsg($io_sql->message));
	}
	else
	{
		   if ($row=$this->io_sql->fetch_row($rs_data))
		   {
				$ls_cuenta=$row["sc_cuenta"];
				$lb_valido=true;
			//	print 'ls_cuenta->'.$ls_cuenta;
		   }
	}
	return $lb_valido;
}

function uf_buscar_cuentacontable($as_cuenta,&$ls_cuenta)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	Function:            uf_buscar_cuentacontable
	//	Descripcion:       Metodo que selecciona la cuenta contable
	//	FechaCreación:  16-11-2008	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_cuenta="";
	$ls_codtie=$_SESSION["ls_codtienda"];	
	/*$ls_sql=  " SELECT sc.sc_cuenta".
					"   FROM scg_cuentas sc,sfc_tienda_ctascontables t".				
					" WHERE sc.sc_cuenta like '".$as_cuenta."%'  AND sc.status='C' AND sc.sc_cuenta=t.sc_cuenta".
					" AND t.codtiend='".$ls_codtie."' AND sc.codemp=t.codemp AND substr(sc.sc_cuenta,12,3)='001'";*/
	$ls_sql="SELECT sc.sc_cuenta FROM scg_cuentas sc WHERE sc.sc_cuenta like '".$as_cuenta."%'  AND sc.status='C' AND substr(sc.sc_cuenta,10,2)='".substr($ls_codtie,2,2)."' AND substr(sc.sc_cuenta,12,3)='001';";
	//print 'cta_contable--->'.$ls_sql.'<br>';			
	$lb_valido=false;
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false) 
	{
			print $io_sql->message;
	}
	else
	{
		   if ($row=$this->io_sql->fetch_row($rs_data))
		   {
				$ls_cuenta=$row["sc_cuenta"];
				$lb_valido=true;
		   }
	}
	  return $lb_valido;
}


function uf_buscar_cuentacontable_retenciones($as_cuenta)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	Function:            uf_buscar_cuentacontable
	//	Descripcion:       Metodo que selecciona la cuenta contable
	//	FechaCreación:  16-11-2008	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$ls_cuenta="";
	$ls_codtie=$_SESSION["ls_codtienda"];	
	$ls_sql=  " SELECT sc.sc_cuenta".
					"   FROM scg_cuentas sc,sfc_tienda_ctascontables t".				
					" WHERE sc.sc_cuenta like '".$as_cuenta."%'  AND sc.status='C' AND sc.sc_cuenta=t.sc_cuenta".
					" AND t.codtiend='".$ls_codtie."' AND sc.codemp=t.codemp AND substr(sc.sc_cuenta,12,3)='001'";
					print '<br> buscarcuenta->'.$ls_sql.'<br>';
					
	print 'retenciones->'.$ls_sql;
	 $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false) 
	{
			print $io_sql->message;
	}
	else
	{
		   if ($row=$this->io_sql->fetch_row($rs_data))
		   {
				$ls_cuenta=$row["sc_cuenta"];
		   }
	}
	  return $ls_cuenta;
}

/*****************************************************************************************/
/***********            SECCION REGISTRO DE MOVIEMIENTOS DE BANCO      *******************/
/*****************************************************************************************/
function uf_buscar_movbco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov){
	 $lb_flag=true;
	 $ls_codemp=$this->datoemp["codemp"];

	 $ls_sql=  " SELECT *".
					"   FROM sfc_movbco_tranf".
					" WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."'".
					"      AND codope='".$ls_codope."' AND estmov='".$ls_estmov."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $lb_flag=false;
	  }


	  return $lb_flag;
}

function uf_buscar_movbco_scg($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,&$la_datos){
	 $lb_flag=false;
	 $ls_codemp=$this->datoemp["codemp"];

	 $ls_sql=  " SELECT *".
					"   FROM scb_movbco_scg".
					" WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."'".
					"      AND codope='".$ls_codope."' AND estmov='".$ls_estmov."'";

	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $la_datos=$this->io_sql->obtener_datos($rs_data);
		  $lb_flag=true;
	  }

	  return $lb_flag;
}

function uf_buscar_movbco_spi($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,&$la_datos){
	 $lb_flag=false;
	 $ls_codemp=$this->datoemp["codemp"];

	 $ls_sql=  " SELECT *".
					"   FROM scb_movbco_spi".
					" WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."'".
					"      AND codope='".$ls_codope."' AND estmov='".$ls_estmov."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $la_datos=$this->io_sql->obtener_datos($rs_data);
		  $lb_flag=true;
	  }

	  return $lb_flag;
}

function uf_guardar_movscb($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov)
	{
	    $lb_valido=true;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="INSERT INTO sfc_movbco_tranf(codemp, codban, ctaban, numdoc, codope, estmov)
				      VALUES ('".$ls_codemp."', '".$as_codban."', '".$as_ctaban."', '".$as_numdoc."', '".$as_codope."', '".$as_estmov."')";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result ===false)
		{
			//print $ls_sql;
			$this->is_msg_error="Error al guardar datos del Cierre,".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		return $lb_valido;


	}
/*****************************************************************************************/
/***********        FIN SECCION REGISTRO DE MOVIEMIENTOS DE BANCO      *******************/
/*****************************************************************************************/


/*****************************************************************************************/
/***********   SECCION REGISTRO DE MOVIEMIENTOS DE CONT. Y PRESU.      *******************/
/*****************************************************************************************/

function uf_buscar_totalfactura($ls_codcie,$ls_codusu){
	 $ls_totalfactura=0;

	 $ls_sql="SELECT SUM(monto-montoret) AS total FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' "/*AND codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalFactura=".$ls_sql."---".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalfactura=$row["total"];
	  }
	  return $ls_totalfactura;
}

function uf_buscar_totalfacturacaj($ls_codcie,$ls_cod_caja){
	 $ls_totalfactura=0;

	 $ls_sql="SELECT SUM(monto-montoret) AS total FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' "/*AND codciecaj='".$ls_codcie."' AND codcaj like '".$ls_cod_caja."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	  print "<br> TotalFacturacaja=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalfactura=$row["total"];
	  }
	  return $ls_totalfactura;
}

function uf_buscar_totaliva($ls_codcie,$ls_codusu){
	 $ls_totaliva=0;

	 $ls_sql="SELECT SUM(montoret) AS totaliva FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' "/*AND  codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalIva=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totaliva=$row["totaliva"];
	  }
	  return $ls_totaliva;
}

function uf_buscar_totalivacaj($ls_codcie,$ls_cod_caja){
	 $ls_totaliva=0;

	 $ls_sql="SELECT SUM(montoret) AS totaliva FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' "/*AND  codciecaj='".$ls_codcie."' AND codcaj like '".$ls_cod_caja."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalIvacaja=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totaliva=$row["totaliva"];
	  }
	  return $ls_totaliva;
}

function uf_buscar_totalivacontado($ls_codcie,$ls_codusu){
	 $ls_totalivacon=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacon FROM sfc_factura  WHERE conpag='1' AND estfac='P' AND estfaccon<>'A' "/*AND  codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalIvacontado=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacon=$row["totalivacon"];
	  }
	  return $ls_totalivacon;
}

function uf_buscar_totalivacontadocaj($ls_codcie,$ls_cod_caja){
	 $ls_totalivacon=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacon FROM sfc_factura  WHERE conpag='1' AND estfac='P' AND estfaccon<>'A'"/* AND  codciecaj='".$ls_codcie."' AND codcaj like '".$ls_cod_caja."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalIvacontadocaja=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacon=$row["totalivacon"];
	  }
	  return $ls_totalivacon;
}

function uf_buscar_totalivacredito($ls_codcie,$ls_codusu){
	 $ls_totalivacre=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacre FROM sfc_factura  WHERE conpag='2' AND conpag='4' AND estfac='P' AND estfaccon<>'A' "/*AND  codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalIvaCredito=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacre=$row["totalivacre"];
	  }
	  return $ls_totalivacre;
}

function uf_buscar_totalivacreditocaj($ls_codcie,$ls_cod_caja){
	 $ls_totalivacre=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacre FROM sfc_factura  WHERE conpag='2' AND conpag='4' AND estfac='P' AND estfaccon<>'A' "/*AND  codciecaj='".$ls_codcie."' AND codcaj like '".$ls_cod_caja."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 print "<br> TotalIvaCreditocaja=".$ls_sql."   ERROR--- ".$this->io_sql->message;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacre=$row["totalivacre"];
	  }
	  return $ls_totalivacre;
}


function uf_buscar_montocontado($ls_codcie,$ls_codusu){
    $ls_totcon=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' "/*AND f.codciecaj='".$ls_codcie."' AND f.codusu like '".$ls_codusu."' */."AND fp.codforpag <> '04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";

	$rs_data=$this->io_sql->select($ls_sql);
	while($row=$this->io_sql->fetch_row($rs_data))
     {
			 $ls_totcon= $ls_totcon+$row["total"];
     }
	print "<br> MontoContado=".$ls_sql."   ERROR--- ".$this->io_sql->message; 
    return $ls_totcon;


}

function uf_buscar_montocontadocaj($ls_codcie,$ls_cod_caja){
    $ls_totcon=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' "/*AND f.codciecaj='".$ls_codcie."' AND f.codcaj like '".$ls_cod_caja."'*/." AND fp.codforpag <> '04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";

	$rs_data=$this->io_sql->select($ls_sql);
	while($row=$this->io_sql->fetch_row($rs_data))
     {
			 $ls_totcon= $ls_totcon+$row["total"];		 
     }
	print "<br> MontoContadocaja=".$ls_sql."   ERROR--- ".$this->io_sql->message;  

    return $ls_totcon;


}

function uf_buscar_montocredito($ls_codcie,$ls_codusu){
	 $ls_montocredito=0;
	 $ls_montocartaor=0;
	 $ls_monparcartaor=0;

	 $ls_sql="SELECT SUM(monto) AS totalcred FROM sfc_factura  WHERE conpag='2' AND estfac='P' AND estfaccon<>'A' "/*AND codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocredito=$row["totalcred"];
	  }

	  $ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total,SUM(f.montopar) as montoparcial FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' "/*AND f.codciecaj='".$ls_codcie."' AND f.codusu like '".$ls_codusu."'*/." AND fp.codforpag='04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocartaor=$row["total"];
		  $ls_monparcartaor=$row["montoparcial"];
	  }
	  print "<br> MontoCredito=".$ls_sql."   ERROR--- ".$this->io_sql->message; 
	  return $ls_montocredito+$ls_montocartaor+$ls_monparcartaor;
}

function uf_buscar_montocreditocaj($ls_codcie,$ls_cod_caja){
	 $ls_montocredito=0;
	 $ls_montocartaor=0;
	 $ls_monparcartaor=0;

	 $ls_sql="SELECT SUM(monto) AS totalcred FROM sfc_factura  WHERE conpag='2' AND estfac='P' AND estfaccon<>'A' "/*AND codciecaj='".$ls_codcie."' AND codcaj like '".$ls_cod_caja."'"*/;
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocredito=$row["totalcred"];
	  }

	  $ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total,SUM(f.montopar) as montoparcial FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' "/*AND f.codciecaj='".$ls_codcie."' AND f.codcaj like '".$ls_cod_caja."'*/." AND fp.codforpag='04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocartaor=$row["total"];
		  $ls_monparcartaor=$row["montoparcial"];
	  }
	  print "<br> MontoCreditoCaja=".$ls_sql."   ERROR--- ".$this->io_sql->message; 
	  return $ls_montocredito+$ls_montocartaor+$ls_monparcartaor;
}

function uf_buscar_montoxcobrar($ls_codcie,$ls_codusu){
	$ls_total_cats=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' "/*AND f.codciecaj='".$ls_codcie."' AND f.codusu like '".$ls_codusu."'*/." GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	while($row=$this->io_sql->fetch_row($rs_data))
     {
			$ls_codforpag=$row["codforpag"];
			if($ls_codforpag=='04'){
			    $ls_total_cats=$row["total"];
			}
    }
	print "<br> MontoXCobrar=".$ls_sql."   ERROR--- ".$this->io_sql->message; 
    return $ls_total_cats;
}

function uf_buscar_montoxcobrarcaj($ls_codcie,$ls_cod_caja){
	$ls_total_cats=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' "/*AND f.codciecaj='".$ls_codcie."' AND f.codcaj like '".$ls_cod_caja."'*/." GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	while($row=$this->io_sql->fetch_row($rs_data))
     {
			$ls_codforpag=$row["codforpag"];
			if($ls_codforpag=='04'){
			    $ls_total_cats=$row["total"];
			}
     }
	print "<br> MontoXCobrarCaja=".$ls_sql."   ERROR--- ".$this->io_sql->message; 
    return $ls_total_cats;
}



function uf_buscar_montoNcbanco($ls_codcie,$ls_codusu){
	$ld_total=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpagocob i,sfc_cobro c  WHERE i.codforpag=fp.codforpag AND i.numcob=c.numcob "/*AND c.codciecaj='".$ls_codcie."' AND c.codusu like '".$ls_codusu."'*/." GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	while($row=$this->io_sql->fetch_row($rs_data))
     {
			$ls_codforpag=$row["codforpag"];
			if($ls_codforpag=='08'){
			    $ld_total=$row["total"];	
			}
     }
	return $ld_total;
}
/*****************************************************************************************/
/****************  FIN  SECCION REGISTRO DE MOVIEMIENTOS DE CONT. Y PRESU.    ******************/
/*****************************************************************************************/

}
?>
