<?
	include ("../files/header.inc.php");

	$response=sql("select * from clientes");


	if (!is_array($response))
	{
		$rows=0;
	}
	else {
		$rows=count($response);

	}
	$result=$response;

	//Generamos el archivo json
	if (version_compare(PHP_VERSION,"5.2","<")){

		$json = new Services_JSON();
		$data=$json->encode($result);
	}else{
		$data = json_encode($result);
	}
	echo '({"total":"' . $rows . '","data":' . $data . '})';
?>