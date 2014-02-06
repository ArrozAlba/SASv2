<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Asignaci&oacute;n de Permisos al Sistema</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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

</head>

<frameset rows="175,*" cols="*" frameborder="NO" border="0" framespacing="0">
  <frame src="top_sss_p_derechogrupo.php" name="topFrame" scrolling="NO">
  <frameset rows="*" cols="220,*" framespacing="0" frameborder="no" border="0">
    <frame src="menu_sss_p_derechogrupo.php" name="leftFrame" scrolling="auto" noresize>
    <frame src="principal_sss_p_derechogrupo.php" name="mainFrame"> 
  </frameset>
</frameset>
<noframes><body>
</body></noframes>
</html>
