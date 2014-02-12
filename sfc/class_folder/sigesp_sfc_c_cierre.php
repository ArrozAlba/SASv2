<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cajero
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cajero.
 // Fecha:       - 04/12/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_cierre
{
 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 var $io_data;

function sigesp_sfc_c_cierre()
{
	require_once ("../shared/class_folder/sigesp_include.php");
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
	$this->io_msg = new class_mensajes();
	require_once("../shared/class_folder/class_datastore.php");
	$this->io_datastore=new class_datastore();
}

function uf_select_cierre($ls_feccie)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_cadena="SELECT codemp,codciecaj,codtiend,codusu,feccie,montotfac,cod_caja
		            FROM   sfc_cierrecaja
		            WHERE  codemp  ='".$ls_codemp."'
					AND    feccie  ='".$ls_feccie."'
					AND    codtiend='".$ls_codtie."' ;";
		//print $ls_cadena."<br><br>";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cierre ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_select_cierre_caja($ls_feccie,$ls_caja,&$fechacierre)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_cierre_caja
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_cadena="SELECT codemp,codciecaj,codtiend,codusu,feccie,montotfac,cod_caja
		            FROM   sfc_cierrecaja
		            WHERE  codemp  ='".$ls_codemp."'
					AND    DATE(feccie)='".$ls_feccie."'
					AND    codtiend='".$ls_codtie."' AND cod_caja='".$ls_caja."' ;";
		//print $ls_cadena."<br>";
		//exit;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cierre ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;

				$la_cierre=$this->io_sql->obtener_datos($rs_datauni);
				$this->io_datastore->data=$la_cierre;
				$fechacierre=$this->io_datastore->getValue("feccie",1);
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}// uf_select_cierre_caja

function uf_actualizar_estfac($as_codusu,$as_estfac,$as_codcie,$as_feccie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_factura ".
	        "SET    estfac='".$as_estfac."',codciecaj='".$as_codcie."' ".
	        "WHERE  codemp='".$ls_codemp."'
			 AND    estfac='N'
			 AND    codtiend='".$ls_codtie."'
			 AND    codusu ilike '".$as_codusu."'
			 AND    fecemi='".$as_feccie."'";
	//print $ls_cadena."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
		//	print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estfac".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Facturas con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_actualizar_estfaccaj($as_codcaj,$as_estfac,$as_codcie,$as_feccie,$aa_seguridad)
{

	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_factura ".
	        "SET    estfac   = '".$as_estfac."',codciecaj='".$as_codcie."' ".
	        "WHERE  codemp   = '".$ls_codemp."'
			 AND    estfac   = 'N'
			 AND    cod_caja ilike '".$as_codcaj."'
			 AND    codtiend = '".$ls_codtie."'
			 AND    DATE(fecemi)= '".$as_feccie."'";
	//print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
		//	print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estfac".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Facturas con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_actualizar_estcob($as_codusu,$as_estcob,$as_codcie,$as_feccie,$aa_seguridad)
{
    //falta empresa!!!
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_cobrocartaorden
	         SET    estcob='".$as_estcob."',codciecaj='".$as_codcie."'
	         WHERE  codemp='".$ls_codemp."'        AND  (estcob='E' or estcob='C')
			 AND    codusu ilike '".$as_codusu."'  AND  feccob='".$as_feccie."'
			 AND    codtiend='".$ls_codtie."'";
	//print $ls_sql;
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			//print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Cobranzas con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_actualizar_estcobcaj($as_codcaj,$as_estcob,$as_codcie,$as_feccie,$aa_seguridad)
{
    //falta empresa!!!
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_cobrocartaorden ".
	        "SET    estcob = '".$as_estcob."',codciecaj='".$as_codcie."' ".
	        "WHERE  codemp = '".$ls_codemp."'
			 AND    (estcob= 'E' or estcob='C')
			 AND    cod_caja ilike '".$as_codcaj."'
			 AND    codtiend='".$ls_codtie."'
			 AND    feccob = '".$as_feccie."'";
	//print $ls_sql;
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			//print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Cobranzas con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_actualizar_estdev($as_codusu,$as_estdev,$as_codcie,$as_feccie,$aa_seguridad)
{
    //falta empresa!!!
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_devolucion ".
	        "SET    estdev='".$as_estdev."',codciecaj='".$as_codcie."' ".
	        "WHERE  codemp='".$ls_codemp."'
			 AND    estdev='E'
			 AND    codtiend='".$ls_codtie."'
			 AND    codusu ilike '".$as_codusu."'
			 AND    fecdev='".$as_feccie."'";
	//print $ls_sql;
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			//print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Devocluciones con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_actualizar_estdevcaj($as_codcaj,$as_estdev,$as_codcie,$as_feccie,$aa_seguridad)
{
    //falta empresa!!!
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_devolucion ".
	        "SET    estdev='".$as_estdev."',codciecaj='".$as_codcie."' ".
	        "WHERE  codemp='".$ls_codemp."'
			 AND    estdev='E'
			 AND    codtiend='".$ls_codtie."'
			 AND    cod_caja ilike '".$as_codcaj."'
			 AND    fecdev='".$as_feccie."'";
	//print $ls_sql;
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			//print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Devocluciones con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_actualizar_estnot($as_estdev,$as_codcie,$as_feccie,$aa_seguridad)
{
    //falta empresa!!!
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$as_feccie=$this->io_funcion->uf_convertirdatetobd($as_feccie);
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_nota
	         SET    estcie='".$as_estdev."',codciecaj='".$as_codcie."'
	         WHERE  codemp='".$ls_codemp."'
			 AND    estcie='N'
			 AND    codtiend='".$ls_codtie."'
			 AND    fecnot='".$as_feccie."'";
	//print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			//print $ls_cadena;
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Notas con el Nro. de Cierre ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}


function uf_monto_cierre($as_codcie,$as_moncie)
{
    //falta empresa!!!
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
    $ls_sql="UPDATE sfc_cierrecaja ".
	        "SET    montotfac = ".$as_moncie." ".
	        "WHERE  codemp    = '".$ls_codemp."'
			 AND    codciecaj = '".$as_codcie."'
			 AND    codtiend  = '".$ls_codtie."' ";
	//print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_guardar_cierre($as_codcie,$as_codusu,$as_feccie,$la_codcaj,$as_total_general,$aa_seguridad)
{
    $lb_valido=true;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$m=time() - 1800;
	$hora=date("h:i:s",$m);
	$as_fecemi=$this->io_funcion->uf_convertirdatetobd_hora($as_feccie,$hora);

	if(empty($as_total_general) )
	{
	   $as_total_general=0;
	}
	$ls_sql="INSERT INTO sfc_cierrecaja(codemp,codciecaj,codusu,feccie,codtiend,cod_caja,montotfac)
	         VALUES ('".$ls_codemp."','".$as_codcie."','".$as_codusu."','".$as_fecemi."','".$ls_codtie."','".$la_codcaj."',".$as_total_general.")";

	//print $ls_sql;

	$li_result=$this->io_sql->execute($ls_sql);
	if($li_result ===false)
	{
		$this->is_msg_error="Error al guardar datos del Cierre,".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		print $this->is_msg_error;
		$lb_valido=false;
	}else{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		$ls_evento="INSERT";
		$ls_descripcion ="Inserto el cierre de caja con el Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
		$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
	}
	return $lb_valido;

}

function uf_buscar_montoxcobrar($ls_codcie,$ls_codusu)
{
	$ls_total_cats =0;
	$ls_total_facts=0;
	$ls_codtie     =$_SESSION["ls_codtienda"];
	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total
	         FROM    sfc_formapago fp,sfc_instpago i,sfc_factura f
			 WHERE   i.codforpag = fp.codforpag
			 AND     i.numfac    = f.numfac
			 AND     f.estfac    = 'P'
			 AND     f.codtiend  = i.codtiend
			 AND     f.codciecaj = '".$ls_codcie."'
			 AND     f.codusu like '".$ls_codusu."'
			 AND     f.codtiend='".$ls_codtie."'
			 GROUP BY fp.codforpag";
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

	 $ls_sql="SELECT SUM(monto) AS total
	          FROM   sfc_factura
			  WHERE  conpag='2'
			  AND    estfac='P'
			  AND    codciecaj='".$ls_codcie."'
			  AND    codtiend='".$ls_codtie."'
			  AND    codusu like '".$ls_codusu."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_total_facts=$row["total"];
	  }

	  return $ls_total_cats+$ls_total_facts;
}

function uf_buscar_montoxcobrarcaj($ls_codcie,$ls_codcaj)
{
	$ls_total_cats=0;
	$ls_total_facts=0;
    $ls_codtie=$_SESSION["ls_codtienda"];
	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total
	         FROM   sfc_formapago fp,sfc_instpago i,sfc_factura f
			 WHERE  i.codforpag = fp.codforpag
			 AND    i.numfac    = f.numfac
			 AND    f.codtiend  = i.codtiend
			 AND    f.estfac    = 'P'
			 AND    f.codciecaj = '".$ls_codcie."'
			 AND    f.cod_caja like '".$ls_codcaj."'
			 AND    f.codtiend='".$ls_codtie."'
			 GROUP BY fp.codforpag";
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
	 $ls_sql="SELECT SUM(monto) AS total
	          FROM   sfc_factura
	          WHERE  conpag    = '2'
			  AND    estfac    = 'P'
			  AND    codtiend='".$ls_codtie."'
			  AND    codciecaj = '".$ls_codcie."'
	          AND    cod_caja like '".$ls_codcaj."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_total_facts=$row["total"];
	  }

	  return $ls_total_cats+$ls_total_facts;
}

function uf_buscar_cuenta($ls_codusu)
{
	 $ls_cuenta="";
	 $ls_codtie=$_SESSION["ls_codtienda"];
	 $ls_sql= " SELECT t.spi_cuenta".
		      " FROM   sfc_cajero c,sfc_tienda t".
			  " WHERE  c.codtie=t.codtie
			    AND    c.codusu='".$ls_codusu."'
				AND    f.codtiend='".$ls_codtie."'  ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $ls_cuenta=$row["spi_cuenta"];
	  }
	  return $ls_cuenta;
}

function uf_buscar_movbco($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov){
	 $lb_flag=true;
	 $ls_codemp=$this->datoemp["codemp"];
	 $ls_sql= " SELECT * ".
		      " FROM   sfc_movbco_tranf".
			  " WHERE  codemp='".$ls_codemp."'  AND  codban='".$ls_codban."'
			    AND    ctaban='".$ls_ctaban."'  AND  numdoc='".$ls_numdoc."'".
			  " AND    codope='".$ls_codope."'  AND  estmov='".$ls_estmov."'";
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
		      " FROM   scb_movbco_scg".
			  " WHERE  codemp='".$ls_codemp."'  AND  codban='".$ls_codban."'
			    AND    ctaban='".$ls_ctaban."'  AND  numdoc='".$ls_numdoc."'".
			  " AND    codope='".$ls_codope."'  AND  estmov='".$ls_estmov."'";

	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $la_datos=$this->io_sql->obtener_datos($rs_data);
		  $lb_flag=true;
	  }

	  return $lb_flag;
}

function uf_buscar_movbco_spi($ls_codban,$ls_ctaban,$ls_numdoc,$ls_codope,$ls_estmov,&$la_datos)
{
	 $lb_flag=false;
	 $ls_codemp=$this->datoemp["codemp"];
	 $ls_sql= " SELECT *".
		      " FROM   scb_movbco_spi".
			  " WHERE  codemp='".$ls_codemp."'  AND  codban='".$ls_codban."'
			    AND    ctaban='".$ls_ctaban."'  AND  numdoc='".$ls_numdoc."'".
			  " AND    codope='".$ls_codope."'  AND  estmov='".$ls_estmov."'";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($row=$this->io_sql->fetch_row($rs_data))
	  {
	      $la_datos=$this->io_sql->obtener_datos($rs_data);
		  $lb_flag=true;
	  }
	  return $lb_flag;
}

function uf_buscar_montoNcbanco($ls_codcie,$ls_codusu){
	$ld_total=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total
	         FROM   sfc_formapago fp,sfc_instpagocob i,sfc_cobro c
			 WHERE  i.codforpag = fp.codforpag
			 AND    i.numcob    = c.numcob
			 AND    c.codciecaj = '".$ls_codcie."'
			 AND    c.codusu like '".$ls_codusu."'
			 GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			$ls_codforpag=$data["codforpag"][$li_z];
			if($ls_codforpag=='08')
			{
			    $ld_total=$data["total"][$li_z];
			}

		 }
     }
	return $ld_total;
}

function uf_buscar_montoNcbancocaj($ls_codcie,$ls_codcaj)
{
	$ld_total=0;

	$ls_sql="SELECT fp.codforpag ,SUM(i.monto) as total
			 FROM   sfc_formapago fp,sfc_instpagocob i,sfc_cobrocartaorden c
			 WHERE  i.codforpag = fp.codforpag
			 AND    i.numcob    = c.numcob
			 AND    c.codciecaj = '".$ls_codcie."'
			 AND    c.cod_caja like '".$ls_codcaj."'
			 GROUP BY fp.codforpag";
	$rs_data=$this->io_sql->select($ls_sql);
	if($row=$this->io_sql->fetch_row($rs_data))
     {
		$data=$this->io_sql->obtener_datos($rs_data);
		$this->io_data->data=$data;
		$li_totrow=$this->io_data->getRowCount("codforpag");
		for($li_z=1;$li_z<=$li_totrow;$li_z++)
		 {
			$ls_codforpag=$data["codforpag"][$li_z];
			if($ls_codforpag=='08')
			{
			    $ld_total=$data["total"][$li_z];
			}
		 }
     }
	return $ld_total;
}

function uf_guardar_movscb($as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_estmov,$aa_seguridad)
	{
	    $lb_valido=true;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="INSERT INTO sfc_movbco_tranf(codemp, codban, ctaban, numdoc, codope, estmov)
                 VALUES
				 ('".$ls_codemp."', '".$as_codban."', '".$as_ctaban."', '".$as_numdoc."', '".$as_codope."', '".$as_estmov."')";
		$li_result=$this->io_sql->execute($ls_sql);
		if($li_result ===false)
		{
			$this->is_msg_error="Error al guardar datos del Cierre,".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
			$lb_valido=false;
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="INSERT";
			$ls_descripcion ="Inserto el Movimiento de Banco ".$as_numdoc." del Banco ".$as_codban." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}

function uf_reversar_estfac($as_codcie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_sql="UPDATE sfc_factura ".
	        "SET    estfac='N', codciecaj=''
	         WHERE  codemp='".$ls_codemp."'
			 AND    codciecaj='".$as_codcie."'
			 AND    codtiend='".$ls_codtie."' ";
    //print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estfac".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Faturas Asociadas al Cierre Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_reversar_estcob($as_codcie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_sql="UPDATE sfc_cobrocartaorden
	        SET estcob='C', codciecaj=''
			WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codciecaj='".$as_codcie."'";
    //print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Cobranzas Asociadas al Cierre Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_reversar_estnot($as_codcie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_sql="UPDATE sfc_nota
	         SET    estcie='N', codciecaj=''
	         WHERE  codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codciecaj='".$as_codcie."'";
    //print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_actualizar_estcob".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Notas Asociadas al Cierre Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_reversar_estcli($as_codcie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_sql="UPDATE sfc_cobro_cliente
	         SET    estcie='N', codciecaj=''
	         WHERE  codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codciecaj='".$as_codcie."'";
    //print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_reversar_estcli".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Notas Asociadas al Cierre Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_reversar_estdev($as_codcie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	$ls_sql="UPDATE sfc_devolucion
	         SET    estcie='E', codciecaj=''
	         WHERE  codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codciecaj='".$as_codcie."'";
    //print $ls_sql."<br><br>";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msg="Error en metodo uf_reversar_estdev".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{

			if($li_numrows>0)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo las Notas Asociadas al Cierre Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$lb_valido=true;
			}
		}
  return $lb_valido;
}

function uf_delete_cierre($as_codcie,$aa_seguridad)
{
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$ls_sql="DELETE FROM sfc_cierrecaja ".
	        "WHERE  codemp='".$ls_codemp."'
			 AND    codciecaj='".$as_codcie."'
			 AND    codtiend='".$ls_codtie."' ";
	$li_numrows=$this->io_sql->execute($ls_sql);
    if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msg="Error en metodo uf_delete_cierre".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{

		if($li_numrows>0)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Elimino el Cierre Nro. ".$as_codcie." Asociado a la Empresa ".$ls_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$lb_valido=true;
		}
	}
	return $lb_valido;
}

function uf_ver_secuenciaexiste($ls_codusu,&$ls_lastval)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codusu = strtolower($ls_codusu);
		$ls_codusu = str_replace(" ","",$ls_codusu);
		$ls_cadena="SELECT last_value FROM ".$ls_codusu;
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
				$ls_lastval=$row["last_value"];
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_update_secuencia($ls_codigo,$valor)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_unidad
		// Parameters:  - $ls_codtob( Codigo del Tipo de Obra).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	    $lb_valido=true;
		$valor=trim($valor);
		$valor=str_replace("0","",$valor);
		$valor=strval($valor);
		$valor=$valor-1;
	    $ls_cadena="ALTER
		            SEQUENCE     ".$ls_codigo."
					RESTART WITH ".$valor;
		//print $ls_cadena."<br><br>";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_cliente ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
}

function uf_reversar_cierre($as_codcie,$aa_seguridad)
{
	  $lb_valido=true;

	  $lb_valido=$this->uf_reversar_estfac($as_codcie,$aa_seguridad);
	  /*print "1::".$lb_valido."<br>";
	  if($lb_valido)
	  {*/
	  $lb_valido=$this->uf_reversar_estcob($as_codcie,$aa_seguridad);
	  /*}
	  print "2::".$lb_valido."<br>";
	  if($lb_valido)
	  {*/
	  $lb_valido=$this->uf_reversar_estnot($as_codcie,$aa_seguridad);
	  /*}
	  print "3::".$lb_valido."<br>";
	  */
	  $lb_valido=$this->uf_reversar_estcli($as_codcie,$aa_seguridad);

	  $lb_valido=$this->uf_reversar_estdev($as_codcie,$aa_seguridad);

	  $lb_valido=$this->uf_delete_cierre($as_codcie,$aa_seguridad);

	  //$lb_valido=$this->uf_ver_secuenciaexiste('numerocierre',&$valor);

	  //$lb_valido=$this->uf_update_secuencia('numerocierre',$valor);

	  if($lb_valido)
	  {
		  $this->io_sql->commit();
	  }
	  else
	  {
		  $this->io_sql->rollback();
	  }
	  return $lb_valido;
}

/**************************************************************************************/
function uf_validar_cajero($ls_codusu)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
	    $ls_codtie=$_SESSION["ls_codtienda"];
		$ls_cadena="SELECT * FROM sfc_cajero
		            WHERE codemp='".$ls_codemp."' AND codusu='".$ls_codusu."' AND codtiend ilike '".$ls_codtie."';";
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_validar_cajero ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				//$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}


}
?>
