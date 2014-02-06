<?php

session_start();
require_once("class_funciones_inventario.php");
$io_fun_inv=new class_funciones_inventario();
//$io_fun_inv->uf_load_seguridad("SIM","sigesp_sim_d_articulo.php",$as_permisos,$aa_seguridad,$aa_permisos);

require_once("sigesp_sim_c_articulo.php");
$io_siv= new sigesp_sim_c_articulo();

$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];

// proceso a ejecutar
$ls_proceso=$io_fun_inv->uf_obtenervalor("proceso","");
// Cod del articulo
$ls_codart=trim($io_fun_inv->uf_obtenervalor("codart",""));
// cod Tienda
$ls_codtienda=$io_fun_inv->uf_obtenervalor("codtienda","");

switch($ls_proceso)
{
	case "QUITAR-TIENDA":
		uf_quitar_tienda($ls_codart,$ls_codtienda,$ls_codemp);
	break;
}

function uf_select_dt_movimiento($as_codart,$as_codtienda,$as_codemp)
{
	global $io_siv;

	$lb_valido=false;
	$ls_cadena=" SELECT nummov FROM sim_dt_movimiento WHERE codemp='".$as_codemp."' " .
			" AND codart='".$as_codart."' AND codtiend='".$as_codtienda."' ";

	//print $ls_cadena;

	$rs_data = $io_siv->io_sql->select($ls_cadena);
	if($rs_data===false)
	{
		$io_siv->io_msg->message("CLASE->articulo_ajax METODO->uf_select_dt_movimiento ERROR->".$io_siv->io_funcion->uf_convertirmsg($io_siv->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		if($row=$io_siv->io_sql->fetch_row($rs_data))
		{
			$lb_valido=true;
			$io_siv->io_sql->free_result($rs_data);
		}
		else
		{
			$lb_valido=false;
		}
	}

	return $lb_valido;
}

function uf_delete_productotienda($as_codart,$as_codtienda,$as_codemp)
{
	global $io_siv;

	$lb_valido=false;
	$ls_cadena=" DELETE FROM sfc_producto WHERE codemp='".$as_codemp."' " .
			" AND codart='".$as_codart."' AND codtiend='".$as_codtienda."' ";

	$io_siv->io_sql->begin_transaction();
	$li_row = $io_siv->io_sql->execute($ls_cadena);
	if($li_row===false)
	{
		$io_siv->io_msg->message("CLASE->articulo_ajax MÉTODO->uf_sim_delete_articulo ERROR->".$io_siv->io_funcion->uf_convertirmsg($io_siv->io_sql->message));
		$lb_valido=false;
		$io_siv->io_sql->rollback();
	}
	else
	{
		/////////////////////////////////         SEGURIDAD               /////////////////////////////
		/*$ls_evento="DELETE";
		$ls_descripcion ="Elimino el Articulo ".$as_codart." Asociado a la Empresa ".$as_codemp;
		$lb_variable= $io_siv->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);*/
		//////////////////////////////////         SEGURIDAD               /////////////////////////////
		/*if($lb_variable)
		{
			$lb_valido=true;
			$io_siv->io_sql->commit();
		}
		else
		{
			$lb_valido=false;
			$io_siv->io_sql->rollback();
		}*/

		$lb_valido=true;
		$io_siv->io_sql->commit();
	}

	return $lb_valido;
}

function uf_quitar_tienda($as_codart,$as_codtienda,$as_codemp)
{
	$encontrado = "NO";

	$lb_valido=uf_select_dt_movimiento($as_codart,$as_codtienda,$as_codemp);
	if($lb_valido){
		$encontrado = "SI";
	}else{
		//$encontrado = "NO";
		if( uf_delete_productotienda($as_codart,$as_codtienda,$as_codemp) ){
			$encontrado = "NO";
		}else{
			$encontrado = "ERROR";
		}
	}

	print $encontrado;
}

?>
