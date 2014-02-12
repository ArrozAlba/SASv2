<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";
}


require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sfc_factura','numcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";
		}

		$lb_valido=true;
		if($lb_valido)
		{
			$lb_valido=$io_release->io_function_db->uf_select_column('sfc_devolucion','numcon');
			if($lb_valido==false)
			{
				$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
				print "<script language=JavaScript>";
				print "location.href='../index_modules.php'";
				print "</script>";
			}

		}

		$lb_valido=true;
		if($lb_valido)
		{
			$lb_valido=$io_release->io_function_db->uf_select_column('sfc_cierrecaja','cod_caja');
			if($lb_valido==false)
			{
				$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
				print "<script language=JavaScript>";
				print "location.href='../index_modules.php'";
				print "</script>";
			}

		}
	}

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();

if(array_key_exists("ls_codtienda",$_SESSION) && array_key_exists("ls_codcaj",$_SESSION))
{
	if($_SESSION["ls_codtienda"]=="" || $_SESSION["ls_codcaj"]=="")
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



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Sistema de Facturacion</title>
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



<script type="text/javascript" src="../shared/js/jquery/jquery_nuevo.js"></script>


<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">

<script type="text/javascript">

                        $(document).ready(function(){
                          
                          obtenerMontosPorEstado ();
                          obtenerVentasPorAno ();
                          
                        })
                        
                        function obtenerMontosPorEstado () {
                              
                              var fn ="montoPorEstado";
                              $.ajax({
                                  url: "controlador/sigespwindow_blank_con.php",
                                  data: "fn="+fn,
                                  dataType: "html",
                                  beforeSend: function(){
                                   $("#container").empty();
                                   $("#container").append('Cargando...');
                                  },
                                  success: function(data){
                                     $("#container").empty();
                                     $("#container").append(data);
                                     
                                  },
                                  type: "POST",
                                  timeout:20000

                            });
        
                         //return ret;
                        }

                        function obtenerVentasPorAno () {

                              var fn ="ventasPorAno";
                              $.ajax({
                                  url: "controlador/sigespwindow_blank_con.php",
                                  data: "fn="+fn,
                                  dataType: "html",
                                  beforeSend: function(){
                                   $("#containerVentasPorAno").empty();
                                   $("#containerVentasPorAno").append('Cargando...');
                                  },
                                  success: function(data){
                                     $("#containerVentasPorAno").empty();
                                     $("#containerVentasPorAno").append(data);

                                  },
                                  type: "POST",
                                  timeout:20000

                            });

                         //return ret;
                        }

		</script>
<style type="text/css">
<!--
.Estilo2 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="562" height="20" class="cd-menu"><span class="titulo-cat&aacute;logo Estilo2">Sistema de Facturacion</span></td>
    <td width="216" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
      <td colspan="2">

          

      </td>
  </tr>

</table>
    <div id="container" style="width: 700px; height: 400px;  position: relative;  left: 250px;"></div>
    <br><br><br><br><br><br>
    <div id="containerVentasPorAno" style="width: 700px; height: 400px; position: relative;  left: 250px;"></div>
<?php
	$arr=array_keys($_SESSION);
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="ls_hostname")&&
		($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="gi_posicion")&&($col!="ls_gestor")&&
		($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")&&($col!="la_ususeg")&&
		($col!="la_sistema")&&($col!="ls_port")&&($col!="ls_codtienda")&&($col!="ls_nomtienda")&&($col!="ls_precot")&&($col!="ls_prefac")&&($col!="ls_predev")&&($col!="ls_sercot")&&($col!="ls_serfac")&&($col!="ls_serdev")&&($col!="ls_sernot")&&($col!="ls_codcaj")&&($col!="ls_precob")&&($col!="ls_sercob")&&($col!="ls_item") && ($col!="ls_codest") && ($col!="ls_codmun") && ($col!="ls_codpar") && ($col!="ls_estcajero") && ($col!="ls_coduniad") && ($col!="ls_formalibre") && ($col!="ls_sercon") && ($col!="ls_spicuenta") && ($col!="ls_item"))
		{
			unset($_SESSION["$col"]);
		}
	}
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		//$lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');
		$lb_valido=$io_release->io_function_db->uf_select_column('sfc_cliente','feccre');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";
		}
	}

?>

</body>
</html>
