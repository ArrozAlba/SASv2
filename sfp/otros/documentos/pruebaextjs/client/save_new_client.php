<?
	include ("../files/header.inc.php");


	$sql="insert into clientes values(
			NULL,
			'$cli_razon_social',
			'$cli_cif_nif',
			'$cli_direccion',
			'$cli_localidad',
			$cli_cp,
			'$cli_provincia',
			'$cli_pais',
			'$cli_telefono',
			'$cli_email',
			'$cli_web',
			'$cli_cuenta_banco'
			)";

	if ($response=sql($sql))
	{
		$result['success'] = true;
	}
	else {
		$result['success'] = false;
		$result['mensaje'] = 'No se ha podido guardar el cliente';
	}

	if (version_compare(PHP_VERSION,"5.2","<")){
	    $json = new Services_JSON();
	    $data=$json->encode($result);
	}else{
	    $data = json_encode($result);
	}
	echo $data;
?>