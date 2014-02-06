<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Apertura de Sigesp</title>
<meta http-equiv="imagetoolbar" content="no">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu2.js"></script></td>
  </tr>
</table>
<?php
	$arr=array_keys($_SESSION);	
	$li_count=count($arr);
	for($i=0;$i<$li_count;$i++)
	{
		$col=$arr[$i];
		if(($col!="ls_width")&&($col!="ls_height")&&($col!="ls_logo")&&($col!="ls_hostname")&&($col!="ls_login")&&($col!="ls_password")&&($col!="ls_database")&&($col!="ls_gestor")&&($col!="con")&&($col!="gestor")&&($col!="la_empresa")&&($col!="la_logusr")&&($col!="la_ususeg")&&($col!="la_sistema")&&($col!="ls_port")&&($col!="ls_data_des")&&($col!="ls_hostname_destino")&&($col!="ls_login_destino")&&($col!="ls_password_destino")&&($col!="ls_database_destino")&&($col!="ls_gestor_destino")&&($col!="ls_port_destino"))
		{
			unset($_SESSION["$col"]);
		}
	}
?>

</body>
<script language="javascript">
function ue_cerrar()
{
	window.open("sigespwindow_blank.php","Blank","_self");
}

</script> 
</html>