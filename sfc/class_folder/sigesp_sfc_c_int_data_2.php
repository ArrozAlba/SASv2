<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cajero
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cajero.
 // Fecha:       - 04/12/2006
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


function uf_buscar_contrapartida($ls_spicuenta){
	$ls_cuenta="";

	 $ls_sql= " SELECT sc_cuenta".
		      " FROM spi_cuentas".
			  " WHERE spi_cuenta='".$ls_spicuenta."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_cuenta=$row["sc_cuenta"];
	  }


	  return $ls_cuenta;
}


/*****************************************************************************************/
/***********            SECCION REGISTRO DE MOVIEMIENTOS DE BANCO      *******************/
/*****************************************************************************************/
function uf_buscar_movbco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov){
	 $lb_flag=true;
	 $ls_codemp=$this->datoemp["codemp"];

	 $ls_sql= " SELECT *".
		      " FROM sfc_movbco_tranf".
			  " WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."'".
			  " AND codope='".$ls_codope."' AND estmov='".$ls_estmov."'";
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

	 $ls_sql= " SELECT *".
		      " FROM scb_movbco_scg".
			  " WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."'".
			  " AND codope='".$ls_codope."' AND estmov='".$ls_estmov."'";

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

	 $ls_sql= " SELECT *".
		      " FROM scb_movbco_spi".
			  " WHERE codemp='".$ls_codemp."' AND codban='".$ls_codban."' AND ctaban='".$ls_ctaban."' AND numdoc='".$ls_numdoc."'".
			  " AND codope='".$ls_codope."' AND estmov='".$ls_estmov."'";
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
		$ls_sql="INSERT INTO sfc_movbco_tranf(
            codemp, codban, ctaban, numdoc, codope, estmov)
    VALUES ('".$ls_codemp."', '".$as_codban."', '".$as_ctaban."', '".$as_numdoc."', '".$as_codope."', '".$as_estmov."')";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result ===false)
		{
			print $ls_sql;
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

	 $ls_sql="SELECT SUM(monto-montoret) AS total FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' AND codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	  //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalfactura=$row["total"];
	  }

	  return $ls_totalfactura;
}

function uf_buscar_totalfacturacaj($ls_codcie,$ls_codcaj){
	 $ls_totalfactura=0;

	 $ls_sql="SELECT SUM(monto-montoret) AS total FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' AND codciecaj='".$ls_codcie."' AND codcaj like '".$ls_codcaj."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	  //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalfactura=$row["total"];
	  }

	  return $ls_totalfactura;
}

function uf_buscar_totaliva($ls_codcie,$ls_codusu){
	 $ls_totaliva=0;

	 $ls_sql="SELECT SUM(montoret) AS totaliva FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' AND  codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totaliva=$row["totaliva"];
	  }

	  return $ls_totaliva;
}

function uf_buscar_totalivacaj($ls_codcie,$ls_codcaj){
	 $ls_totaliva=0;

	 $ls_sql="SELECT SUM(montoret) AS totaliva FROM sfc_factura  WHERE estfac='P' AND estfaccon<>'A' AND  codciecaj='".$ls_codcie."' AND codcaj like '".$ls_codcaj."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totaliva=$row["totaliva"];
	  }

	  return $ls_totaliva;
}

function uf_buscar_totalivacontado($ls_codcie,$ls_codusu){
	 $ls_totalivacon=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacon FROM sfc_factura  WHERE conpag='1' AND estfac='P' AND estfaccon<>'A' AND  codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacon=$row["totalivacon"];
	  }

	  return $ls_totalivacon;
}

function uf_buscar_totalivacontadocaj($ls_codcie,$ls_codcaj){
	 $ls_totalivacon=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacon FROM sfc_factura  WHERE conpag='1' AND estfac='P' AND estfaccon<>'A' AND  codciecaj='".$ls_codcie."' AND codcaj like '".$ls_codcaj."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacon=$row["totalivacon"];
	  }

	  return $ls_totalivacon;
}

function uf_buscar_totalivacredito($ls_codcie,$ls_codusu){
	 $ls_totalivacre=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacre FROM sfc_factura  WHERE conpag='2' AND conpag='4' AND estfac='P' AND estfaccon<>'A' AND  codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacre=$row["totalivacre"];
	  }

	  return $ls_totalivacre;
}

function uf_buscar_totalivacreditocaj($ls_codcie,$ls_codcaj){
	 $ls_totalivacre=0;

	 $ls_sql="SELECT SUM(montoret) AS totalivacre FROM sfc_factura  WHERE conpag='2' AND conpag='4' AND estfac='P' AND estfaccon<>'A' AND  codciecaj='".$ls_codcie."' AND codcaj like '".$ls_codcaj."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 //print $ls_sql;
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_totalivacre=$row["totalivacre"];
	  }

	  return $ls_totalivacre;
}


function uf_buscar_montocontado($ls_codcie,$ls_codusu){
    $ls_totcon=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND f.codciecaj='".$ls_codcie."' AND f.codusu like '".$ls_codusu."' AND fp.codforpag <> '04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";

	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			 $ls_totcon= $ls_totcon+$data["total"][$li_z];
		 }
     }

    return $ls_totcon;


}

function uf_buscar_montocontadocaj($ls_codcie,$ls_codcaj){
    $ls_totcon=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND f.codciecaj='".$ls_codcie."' AND f.codcaj like '".$ls_codcaj."' AND fp.codforpag <> '04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";

	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			 $ls_totcon= $ls_totcon+$data["total"][$li_z];
		 }
     }

    return $ls_totcon;


}

function uf_buscar_montocredito($ls_codcie,$ls_codusu){
	 $ls_montocredito=0;
	 $ls_montocartaor=0;
	 $ls_monparcartaor=0;

	 $ls_sql="SELECT SUM(monto) AS totalcred FROM sfc_factura  WHERE conpag='2' AND estfac='P' AND estfaccon<>'A' AND codciecaj='".$ls_codcie."' AND codusu like '".$ls_codusu."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocredito=$row["totalcred"];
	  }

	  $ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total,SUM(f.montopar) as montoparcial FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND f.codciecaj='".$ls_codcie."' AND f.codusu like '".$ls_codusu."' AND fp.codforpag='04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocartaor=$row["total"];
		  $ls_monparcartaor=$row["montoparcial"];
	  }

	  return $ls_montocredito+$ls_montocartaor+$ls_monparcartaor;
}

function uf_buscar_montocreditocaj($ls_codcie,$ls_codcaj){
	 $ls_montocredito=0;
	 $ls_montocartaor=0;
	 $ls_monparcartaor=0;

	 $ls_sql="SELECT SUM(monto) AS totalcred FROM sfc_factura  WHERE conpag='2' AND estfac='P' AND estfaccon<>'A' AND codciecaj='".$ls_codcie."' AND codcaj like '".$ls_codcaj."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocredito=$row["totalcred"];
	  }

	  $ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total,SUM(f.montopar) as montoparcial FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND f.codciecaj='".$ls_codcie."' AND f.codcaj like '".$ls_codcaj."' AND fp.codforpag='04' AND f.estfaccon<>'A' GROUP BY fp.codforpag";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_montocartaor=$row["total"];
		  $ls_monparcartaor=$row["montoparcial"];
	  }

	  return $ls_montocredito+$ls_montocartaor+$ls_monparcartaor;
}

function uf_buscar_montoxcobrar($ls_codcie,$ls_codusu){
	$ls_total_cats=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND f.codciecaj='".$ls_codcie."' AND f.codusu like '".$ls_codusu."' GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			$ls_codforpag=$data["codforpag"][$li_z];
			if($ls_codforpag=='04'){
			    $ls_total_cats=$data["total"][$li_z];
			}

		 }
     }

    return $ls_total_cats;
}

function uf_buscar_montoxcobrarcaj($ls_codcie,$ls_codcaj){
	$ls_total_cats=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpago i,sfc_factura f  WHERE i.codforpag=fp.codforpag AND i.numfac=f.numfac AND f.estfac='P' AND f.codciecaj='".$ls_codcie."' AND f.codcaj like '".$ls_codcaj."' GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			$ls_codforpag=$data["codforpag"][$li_z];
			if($ls_codforpag=='04'){
			    $ls_total_cats=$data["total"][$li_z];
			}

		 }
     }

    return $ls_total_cats;
}



function uf_buscar_montoNcbanco($ls_codcie,$ls_codusu){
	$ld_total=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total FROM sfc_formapago fp,sfc_instpagocob i,sfc_cobro c  WHERE i.codforpag=fp.codforpag AND i.numcob=c.numcob AND c.codciecaj='".$ls_codcie."' AND c.codusu like '".$ls_codusu."' GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			$ls_codforpag=$data["codforpag"][$li_z];
			if($ls_codforpag=='08'){
			    $ld_total=$data["total"][$li_z];
			}

		 }
     }

	return $ld_total;
}
/*****************************************************************************************/
/***********  FIN  SECCION REGISTRO DE MOVIEMIENTOS DE CONT. Y PRESU.      ***************/
/*****************************************************************************************/


}
?>
