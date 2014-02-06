<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";
}
/*require_once("../sigesp_config.php");
$posicion=1;
$_SESSION["ls_database"] = $empresa["database"][$posicion];
$_SESSION["ls_hostname"] = $empresa["hostname"][$posicion];
$_SESSION["ls_login"]    = $empresa["login"][$posicion];
$_SESSION["ls_password"] = $empresa["password"][$posicion];
$_SESSION["ls_gestor"]   = $empresa["gestor"][$posicion];	
$_SESSION["ls_port"]     = $empresa["port"][$posicion];	
$_SESSION["ls_width"]    = $empresa["width"][$posicion];
$_SESSION["ls_height"]   = $empresa["height"][$posicion];	
$_SESSION["ls_logo"]     = $empresa["logo"][$posicion];	*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<title >Sistema de Inventario</title>
<meta http-equiv="imagetoolbar" content="no">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo3 {
	font-size: 14px;
	color: #6699CC;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1 Estilo3">Sistema de Inventario</td>
      <td bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  
  <tr>
    <td height="20" colspan="2" bgcolor="#E7E7E7" class="cd-menu">
      <div align="left">
            <script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>
      </div></td></tr>
</table>
<?php
	$arr=array_keys($_SESSION);
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_port")&&($col!="ls_hostname")&&($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="gi_posicion")&&($col!="ls_gestor")
		   &&($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")&&($col!="la_ususeg")&&($col!="la_sistema")
		   &&($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="ls_codtienda")&&($col!="ls_nomtienda")&&
		   ($col!="ls_precot")&&($col!="ls_prefac")&&($col!="ls_predev")&&($col!="ls_sercot")&&($col!="ls_serfac")&&
		   ($col!="ls_serdev")&&($col!="ls_sernot")&&($col!="ls_codcaj")&&($col!="ls_precob")&&($col!="ls_sercob")&&
		   ($col!="ls_item") && ($col!="ls_codest") && ($col!="ls_codmun") && ($col!="ls_codpar") && ($col!="ls_estcajero") && 
		   ($col!="ls_coduniad") && ($col!="ls_formalibre") && ($col!="ls_sercon") && ($col!="ls_spicuenta") && ($col!="ls_item") &&
		   ($col!="ls_formalibreordent") && ($col!="ls_serordent") && ($col!="ls_preordent"))
		{
			unset($_SESSION["$col"]);
		}
	}

	// validaci�n de los release necesarios poara que funcione el sistema de n�mina
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sim_despacho','tipo');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";
		}
	}

	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_datastore.php");
	//$io_datastore= new class_datastore();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_sql=new class_sql($io_connect);
	$io_data_inv=new class_datastore();
if(array_key_exists("ls_codtienda",$_SESSION))
{
	if($_SESSION["ls_codtienda"]=="" )
	{
		print "<script language=JavaScript>";
		print "	alert('Debe seleccionar la tienda y la caja a utilizar');";
		print "	location.href='../index_modules.php';";
		print "</script>";
	}
}
else
{
	print "<script language=JavaScript>";
	print "	alert('Debe seleccionar la tienda y la caja a utilizar');";
	print "	location.href='../index_modules.php';";
    print "</script>";
} 

//	  $ls_cadena="SELECT p.codemp,p.cod_caja,p.descripcion_caja,p.precot,p.prefac,p.predev,p.sercot,p.serfac," .
//	  		"p.serdev,p.sernot,p.sercon,p.formalibre,p.precob,p.sercob,p.preped,p.serped ,t.* " .
//	  			"FROM sfc_caja p,sfc_tienda t " .
//	  			"WHERE p.codtiend=t.codtiend AND p.codemp=t.codemp";
//
//	/*$ls_cadena="SELECT p.codemp,p.cod_caja,p.descripcion_caja,p.precot,p.prefac,p.predev,p.sercot,p.serfac,p.serdev,p.sernot," .
//			" p.sercon,p.formalibre,p.precob,p.sercob,p.preped,p.serped ,t.* " .
//			" FROM sfc_caja p,sfc_tienda t " .
//			" WHERE p.codtiend=t.codtiend";*/
//
//	$rs_datauniinv=$io_sql->select($ls_cadena);
//
//	  if($rs_datauniinv==false&&($io_sql->message!=""))
//	   {
//		print "error conectandose!!";
//	   }
//	   else
//	   {
//
//
//	  $la_tipoinv=$io_sql->obtener_datos($rs_datauniinv);
// 	  $io_data_inv->data=$la_tipoinv;
//  	  $totrow=$io_data_inv->getRowCount("codtiend");
//
//	if($totrow>=1)
//	 {
//	  //print $totrow."<br>";
//	   if ($totrow>1)
//	   {
//		for($z=0;$z<=$totrow;$z++)
//		{
//			if ($io_data_inv->getValue("codtiend",$z)=='0001')
//			{
//				//print 'paso'.$z."<br>";
//
//			   	$_SESSION["ls_coduniad"]=$row["coduniadm"];
//				$_SESSION["ls_codtienda"]=$io_data_inv->getValue("codtiend",$z);
//	       		$_SESSION["ls_nomtienda"]=$io_data_inv->getValue("dentie",$z);
//
//	       		$_SESSION["ls_coduniad"]=$io_data_inv->getValue("coduniadm",$z);
//			    $_SESSION["ls_spicuenta"]=$io_data_inv->getValue("spi_cuenta",$z);
//
//			    //print $row["ls_sercon"];
//			 }
//		}
//
//	   }else{
//
//	      	//$_SESSION["ls_codtienda"]=$io_data->getValue("codtiend",$totrow);
//	      	$_SESSION["ls_codtienda"]=$io_data_inv->getValue("codtiend",$totrow);
//	      	$_SESSION["ls_nomtienda"]=$io_data_inv->getValue("dentie",$totrow);
//	    	//$_SESSION["ls_coduniad"]=$io_data->getValue("coduniadm",$totrow);//$row["coduniadm"];
//	    	$_SESSION["ls_coduniad"]=$io_data_inv->getValue("coduniadm",$totrow);
//			$_SESSION["ls_spicuenta"]=$io_data_inv->getValue("spi_cuenta",$totrow);
//
//	 		//print  $_SESSION["ls_codtienda"]."--".$_SESSION["ls_nomtienda"];
//
//	   }
//
//	 }
//	 else
//     {
//		//print 'paso'.$z."<br>";
//	   $_SESSION["ls_codtienda"]="";
//	   $_SESSION["ls_nomtienda"]="";
//	   $_SESSION["ls_coduniad"]="";
//	   $_SESSION["ls_spicuenta"]="";
//	  }
//
//
//	 }

?>

</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	f.operacion.value="GUARDAR";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
}
function ue_eliminar()
{
	f=document.form1;
	f.operacion.value="ELIMINAR";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
}
function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}


</script>
</html>
