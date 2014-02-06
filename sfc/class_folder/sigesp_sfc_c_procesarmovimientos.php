<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");


class sigesp_sfc_c_procesarmovimientos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sfc_c_procesarmovimientos()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_funcion=new class_funciones();
		$this->io_mov=    new sigesp_sim_c_movimientoinventario();
		$this->io_msg=    new class_mensajes();
		$this->ls_gestor=   $_SESSION["ls_gestor"];
	}


	function uf_sfc_movimientos($as_codemp,$as_codalm,$ls_codtie)
	{

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sim_movimientos
		//         Access: public (sigesp_sfc_d_actualizar_facturas)
		//      Argumento: $as_codemp //codigo de empresa
		//                 $as_numsol    // numero de la solicitud de ejecuci�n presupuestaria
		//                 $ai_totrows   // total de filas encontradas
		//                 $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que redefine existencias y movimientos de articulos
		//	   Creado Por: Ing. Rosmary Linarez
		// Fecha Creaci�n: 08/02/2006 								Fecha �ltima Modificaci�n :08/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
 		$lb_valido0=true;
		$lb_valido2=true;
		$lb_valido3=true;
		$lb_valido4=true;
		$lb_valido5=true;
		$lb_valido6=true;
		$lb_valido7=true;
		$lb_valido8=true;
		$lb_valido9=true;
		$lb_valido10=true;

		//print $as_codalm;
		$ls_sql_dtmov="DELETE from sim_dt_movimiento where opeinv ilike 'Sal' and codtiend='".$ls_codtie."';";
		//print $ls_sql_dtmov."<br>";
			$rs_datadtmov=$this->io_sql->execute($ls_sql_dtmov);
			//$li_row=$this->io_sql->execute($ls_sql);
			if($rs_datadtmov==false)
			{
				$lb_valido2=false;

			}

		$ls_sql_dtmov2="DELETE from sim_dt_movimiento where numdoc ilike 'FAC-%' and codtiend='".$ls_codtie."';";
			//print $ls_sql_dtmov2."<br>";
			$rs_datadtmov2=$this->io_sql->execute($ls_sql_dtmov2);

			if($rs_datadtmov2==false)
			{
				$lb_valido3=false;
			}


		$ls_sql_dtmov3="DELETE from sim_dt_movimiento where numdoc ilike 'Dev-%' and codtiend='".$ls_codtie."';";
			//print $ls_sql_dtmov3."<br>";
			$rs_datadtmov3=$this->io_sql->execute($ls_sql_dtmov3);

			if($rs_datadtmov3==false)
			{
				$lb_valido4=false;
			}


		$ls_sql_mov="DELETE from sim_movimiento where nomsol ilike 'Despacho' or nomsol ilike 'Despacho%' or nomsol ilike 'FAC-%' or nomsol ilike 'Dev-%' and codtiend='".$ls_codtie."';";
		//print $ls_sql_mov."<br>";
			$rs_datamov=$this->io_sql->execute($ls_sql_mov);

			if($rs_datamov==false)
			{
				$lb_valido5=false;
			}




		$ls_sql_dtrec="DELETE from sim_dt_recepcion where numordcom ilike 'FAC-%' or numordcom ilike 'Dev-%' and codtiend='".$ls_codtie."';";
			//print $ls_sql_dtrec."<br>";
			$rs_datadtrec=$this->io_sql->execute($ls_sql_dtrec);

			if($rs_datadtrec==false)
			{
				$lb_valido6=false;
			}


			if($lb_valido6)
			{
				$ls_sql_rec="DELETE from sim_recepcion where numordcom ilike 'FAC-%' or numordcom ilike 'Dev-%' and codtiend='".$ls_codtie."';";
					//print $ls_sql_rec."<br>";
					$rs_datarec=$this->io_sql->execute($ls_sql_rec);

					if($rs_datarec==false)
					{
						$lb_valido7=false;
					}
			}

		$ls_sql_art="Update sim_articuloalmacen set existencia=0 where codalm='".$as_codalm."' and codtiend='".$ls_codtie."';";
			$rs_dataart=$this->io_sql->execute($ls_sql_art);

			if($rs_dataart==false)
			{
				$lb_valido8=false;
			}


/*********************** Reprocesar Entradas                 *****************************/
		$ls_sql_dt_entradas="SELECT dt.codart FROM sim_articulo a,sim_unidadmedida um,sim_recepcion r,sim_dt_recepcion dt WHERE a.codunimed=um.codunimed AND a.codart=dt.codart AND a.codemp=r.codemp AND dt.numordcom=r.numordcom AND dt.numconrec=r.numconrec AND dt.codemp=r.codemp AND r.codalm like '".$as_codalm."' AND r.estrevrec='1' AND dt.codtiend='".$ls_codtie."'
		AND dt.codtiend=r.codtiend AND dt.codart IN (SELECT codart FROM sim_articuloalmacen where codalm='".$as_codalm."' and codtiend ='".$ls_codtie."') GROUP BY dt.codart,r.codalm,dt.cod_pro,dt.codtiend";
		//print "<br>ENTRADAS".$ls_sql_dt_entradas."<br>";

$rs_dataentradas=$this->io_sql->select($ls_sql_dt_entradas);
if($rs_dataentradas==false)
	{
		$lb_valido=false;

	}
	else
	{
		$row=$this->io_sql->num_rows($rs_dataentradas);
		$la_entradas=$this->io_sql->obtener_datos($rs_dataentradas);

		if($la_entradas)
		{

			for($i=0;$i<$row;$i++)
			{
				$ls_codart=$la_entradas["codart"][$i+1];

				$ls_sql_art3="update sim_articuloalmacen set existencia= (SELECT SUM(dt.canart)  FROM sim_articulo a,sim_unidadmedida um,sim_recepcion r,sim_dt_recepcion dt WHERE a.codunimed=um.codunimed AND a.codart=dt.codart AND a.codemp=r.codemp AND dt.numordcom=r.numordcom AND dt.numconrec=r.numconrec AND dt.codemp=r.codemp AND r.codalm like '".$as_codalm."' AND r.estrevrec='1' AND dt.codtiend='".$ls_codtie."'
AND dt.codtiend=r.codtiend AND dt.codart='".$ls_codart."' AND dt.codart IN (SELECT codart FROM sim_articuloalmacen where codalm='".$as_codalm."' and codtiend ='".$ls_codtie."') GROUP BY dt.codart,r.codalm,dt.cod_pro,dt.codtiend) where codtiend='".$ls_codtie."' AND codart='".$ls_codart."' and codalm='".$as_codalm."';";
				//print "<br>".$ls_sql_art3."<br>";
				$rs_datasalidades=$this->io_sql->execute($ls_sql_art3);

			}
		}
		else
		{
			$lb_valido=false;
			//$this->io_msgc="Registro no encontrado";
		}

	}

/*********************** FIN  Reprocesar Entradas      *****************************/




		$ls_sql_dtdes="DELETE from sim_dt_despacho where codalm='".$as_codalm."' and codtiend='".$ls_codtie."' and numorddes in (SELECT numorddes from sim_despacho where codunides='' and codtiend='".$ls_codtie."' and tipo!='SAL');";
		//print $ls_sql_dtdes."<br>";
		$rs_datadtdes=$this->io_sql->execute($ls_sql_dtdes);

		if($rs_datadtdes==false)
		{
			$lb_valido0=false;
		}



			$ls_sql_des="DELETE from sim_despacho where codtiend='".$ls_codtie."' AND tipo!='SAL';";
			//print $ls_sql_des."<br>";
			$rs_datades=$this->io_sql->execute($ls_sql_des);

			if($rs_datades==false)
			{
				$lb_valido=false;
			}



		$ls_sql_dtfac="UPDATE sfc_detfactura set codalm='".$as_codalm."' where codalm='' and codtiend='".$ls_codtie."';";

			$rs_datadetfac=$this->io_sql->execute($ls_sql_dtfac);
			//$li_row=$this->io_sql->execute($ls_sql);
			if($rs_datadetfac==false)
			{
				$lb_valido9=false;

			}
			else{$lb_valido9=true;}


/*********************** Reprocesar Transferencias     *****************************/
$ls_sql_dt_transferencia="Select t.codart from sim_dt_transferencia t,sim_articuloalmacen where t.codart=sim_articuloalmacen.codart and " .
		" t.codtiend='".$ls_codtie."' and t.codtiend=sim_articuloalmacen.codtiend and t.codart in " .
		" (Select codart from sim_dt_transferencia where codtiend='".$ls_codtie."') and codalm='".$as_codalm."' and t.codtiend='".$ls_codtie."'";

//print "<br>TRNSF".$ls_sql_dt_transferencia."<br>";
$rs_datatransferencia=$this->io_sql->select($ls_sql_dt_transferencia);

if($rs_datatransferencia==false)
	{
		$lb_valido=false;

	}
	else
	{
		$row=$this->io_sql->num_rows($rs_datatransferencia);
		$la_transferencia=$this->io_sql->obtener_datos($rs_datatransferencia);

		if($la_transferencia)
		{

			for($i=0;$i<$row;$i++)
			{
				$ls_codart=$la_transferencia["codart"][$i+1];


	$ls_sql_trans="Update sim_articuloalmacen set existencia =existencia-(" .
			" Select SUM(t.cantidad) from sim_dt_transferencia t where t.codart=sim_articuloalmacen.codart and t.codtiend='".$ls_codtie."' " .
			" and t.codtiend=sim_articuloalmacen.codtiend and t.codart='".$ls_codart."' group by codart) where codart in (Select codart from sim_dt_transferencia where sim_dt_transferencia.codart='".$ls_codart."' ) " .
			" and codalm='".$as_codalm."' and codtiend='".$ls_codtie."'";
	$rs_datatrans=$this->io_sql->execute($ls_sql_trans);

				if($rs_datatrans==false)
				{
					$lb_valido10=false;

				}
				else
				{
					$lb_valido10=true;
				}

			}
		}
		else
		{
			$lb_valido=false;
			//$this->io_msgc="Registro no encontrado";
		}

	}

/*********************** FIN Reprocesar Transferencias     *****************************/


/*********************** Reprocesar Despachos     *****************************/
$ls_art_despacho="Select codart from sim_dt_despacho where numorddes in
  (SELECT numorddes from sim_despacho where tipo='SAL' and codtiend='".$ls_codtie."') and codtiend='".$ls_codtie."' and codalm='".$as_codalm."' group by codart";
  //print "<br>DESP ".$ls_art_despacho."<br>";

$rs_datasalida=$this->io_sql->select($ls_art_despacho);

if($rs_datasalida==false)
	{
		$lb_valido=false;

	}
	else
	{
		$row=$this->io_sql->num_rows($rs_datasalida);
		$la_articulo=$this->io_sql->obtener_datos($rs_datasalida);

		if($la_articulo)
		{

			for($i=0;$i<$row;$i++)
			{
				$ls_codart=$la_articulo["codart"][$i+1];
				$ls_sql_salida="UPDATE sim_articuloalmacen set existencia=existencia - (SELECT SUM(canart) from sim_dt_despacho t where t.codart='".$ls_codart."' and numorddes in (SELECT numorddes from sim_despacho where tipo='SAL' and codtiend='".$ls_codtie."') group by t.codart) where codart= '".$ls_codart."'
and codtiend='".$ls_codtie."' and codalm='".$as_codalm."'";
				//print "<br>DESP ".$ls_sql_salida;
				$rs_datasalidades=$this->io_sql->execute($ls_sql_salida);

			}
		}
		else
		{
			$lb_valido=false;

		}

	}
/*********************** FIN Reprocesar Despachos     *****************************/

/************* La tabla sim_articulo ya no tiene existencia *************************
 * $ls_sql_actulice_exiart="Update sim_articulo set exiart =(Select t.existencia
 * from sim_articuloalmacen t where
 * t.codart=sim_articulo.codart and t.codalm='".$as_codalm."') where codart in
 * (Select codart from sim_articuloalmacen where codalm='".$as_codalm."') ;";
 * $rs_data_actualice_exiart=$this->io_sql->execute($ls_sql_actulice_exiart);
 * **********************************************************************************/


	//exit;

	return $lb_valido;
	} // end  function uf_sim_obtener_dt_solicitud

	function uf_ultimo_costo($as_codemp,$as_codtie,$ls_codproveedor){

		$sql_ultcosto = "SELECT DISTINCT(codart), preuniart,cod_pro FROM sim_dt_recepcion where codemp='".$as_codemp."' and ".
				"(substr(numordcom,1,4)!='FAC-' and substr(numordcom,1,4)!='DEV-') and codtiend='".$as_codtie."' and cod_pro='".$ls_codproveedor."' order by codart ASC";

		$arr_costo=$this->io_sql->select($sql_ultcosto);
		if(!$arr_costo){
			return false;
		}

		while($filacosto=$this->io_sql->fetch_row($arr_costo)){

			$sql_act_costo="UPDATE sfc_producto SET ultcosart=".$filacosto["preuniart"]." WHERE" .
					" codart = '".$filacosto["codart"]."' and codemp='".$as_codemp."' AND codtiend='".$as_codtie."' and cod_pro='".$ls_codproveedor."' ;";

			$arr_act=$this->io_sql->execute($sql_act_costo);

			if(!$arr_act){
				$this->io_sql->rollback();
				$this->io_msg="Error al Actualizar el Articulo ".$filacosto["codart"];
				return false;
			}
		}

		return true;

	}// FIN function uf_ultimo_costo

	function uf_costo_prom($as_codemp,$as_codalm,$as_codtie,$ls_codproveedor){

		require_once("sigesp_sim_c_recepcion.php");
		$io_recep = new sigesp_sim_c_recepcion();
		$sql_art="SELECT codart,cod_pro from sim_articuloalmacen where codalm ='".$as_codalm."' and codemp='".$as_codemp."' and codtiend='".$as_codtie."' and cod_pro='".$ls_codproveedor."' ;";

		$arr_art=$this->io_sql->select($sql_art);
		if($arr_art){
			while($fila_art=$this->io_sql->fetch_row($arr_art))
			{
				$act_cost=$io_recep->uf_sim_actualizar_costo_promedio($as_codemp,$fila_art["codart"],$fila_art["cod_pro"],$as_codtie);   //($as_codemp,$fila_art["codart"]);

				if(!$act_cost){
					$this->io_sql->rollback();
					$this->io_msg="Error al Actualizar el Articulo ".$fila_art["codart"];
					return false;
				}
			}
			return true;
		}else{
			return false;
		}
	}// FIN function uf_costo_prom


}//end  class sigesp_sfc_c_procesarmovimientos
?>
