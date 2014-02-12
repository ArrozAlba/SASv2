<?
	include ("../files/header.inc.php");


	$sql="update clientes set

			cli_razon_social='$cli_razon_social',
			cli_cif_nif='$cli_cif_nif',
			cli_direccion='$cli_direccion',
			cli_localidad='$cli_localidad',
			cli_cp=$cli_cp,
			cli_provincia='$cli_provincia',
			cli_pais='$cli_pais',
			cli_telefono='$cli_telefono',
			cli_email='$cli_email',
			cli_web='$cli_web',
			cli_cuenta_banco='$cli_cuenta_banco'
			where cli_id=$cli_id";

	if ($response=sql($sql))
	{
		$result['success'] = true;
	}
	else {
		$result['success'] = false;
		$result['mensaje'] = 'No se ha podido modificar el cliente';
	}

	if (version_compare(PHP_VERSION,"5.2","<")){
	    $json = new Services_JSON();
	    $data=$json->encode($result);
	}else{
	    $data = json_encode($result);
	}
	echo $data;
?>