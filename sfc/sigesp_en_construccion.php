<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);


  $ls_cadena="SELECT codtie,nomtie
              FROM sfc_cajero
			  WHERE codusu='".$_SESSION["la_logusr"]."'";

  $rs_datauni=$io_sql->select($ls_cadena);
  if($rs_datauni==false&&($io_sql->message!=""))
   {
	
   }
   else
   {
	if($row=$io_sql->fetch_row($rs_datauni))
	 {
       $_SESSION["ls_codtienda"]=$row["codtie"];
	   $_SESSION["ls_nomtienda"]=$row["nomtie"];
     }
	 else
     {
	   $_SESSION["ls_codtienda"]="";
	   $_SESSION["ls_nomtienda"]="";
     }
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14px;
	font-style: italic;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
</table>
<p>
  <?php
	$arr=array_keys($_SESSION);	
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="ls_hostname")&&($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="gi_posicion")&&($col!="ls_gestor")&&($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")&&($col!="la_ususeg")&&($col!="la_sistema")&&($col!="ls_port")&&($col!="ls_codtienda")&&($col!="ls_nomtienda"))
		{
			unset($_SESSION["$col"]);
		}
	}
	/*require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sss_registro_eventos','codintper');	
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release SIGESP BD");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
	}
	*/
?>
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="333" height="33" border="0" align="center">
  <tr>
    <th width="119" height="0" scope="col"><img src="Imagenes/undercon.gif" width="40" height="38"></th>
    <th width="204" height="0" scope="col"><span class="Estilo1">P&aacute;gina en construcci&oacute;n </span></th>
  </tr>
</table>
</body>
</html>