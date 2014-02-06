<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}

	
?>


<?php
	$filas=$_SESSION["data_aux"];
	$totrow=count($filas['numsol']);
	for ($z=1;$z<=$totrow;$z++)
	{
		$marcado  	= $filas["numsol"][$z];	
		$seleccion	= $filas["marcado"][$z];	
		print "Valor :".$marcado." seleccion= ".$seleccion." <br>";
	}
?>





















