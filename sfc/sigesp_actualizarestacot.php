<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 

$la_datemp=$_SESSION["la_empresa"];

?>

<html>
<body>
<form name="form1" method="post" action="">
<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_sfc_c_cotizacion.php");
$io_cotizacion=new  sigesp_sfc_c_cotizacion();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$ls_codemp=$la_datemp["codemp"];
$arre=$_SESSION["la_empresa"];//
$ls_empresa=$arre["codemp"];
$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sfc_d_factura.php";
$la_seguridad["empresa"]=$ls_empresa;
$la_seguridad["logusr"]=$ls_logusr;
$la_seguridad["sistema"]=$ls_sistema;
$la_seguridad["ventanas"]=$ls_ventanas;
/*----------------------------------  ACTUALIZAR COTIZACION A "FACTURADA"---------------------------------------------*/
 $ls_estcot=$_GET['estcot1'];
 $ls_numcot=$_GET['numcot1'];
  /*print "ESTCOT".$ls_estcot;
  print "NUMCOT".$ls_numcot;*/
	   if ($ls_estcot=='P')	
	   {
	    $ls_estcot='E';
   	    $lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot,$la_seguridad);
	   } 
	  
	   print "<script language=JavaScript>close();</script>";
	   
/*---/*-----------------------------------------------------------------------------------------------------------------*/		  

?>
</form>
</body>
</html>
