<?
	include ("../files/header.inc.php");


	$sql="delete from clientes where cli_id=$id";

	if ($response=sql($sql))
	{
		$result['success'] = true;
	}
	else {
		$result['success'] = false;
		$result['mensaje'] = 'No se ha podido eliminar el cliente';
	}

	if (version_compare(PHP_VERSION,"5.2","<")){
	    $json = new Services_JSON();
	    $data=$json->encode($result);
	}else{
	    $data = json_encode($result);
	}
	echo $data;
?>