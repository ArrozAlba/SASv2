<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Administrativas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 11px}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
    <input name="operacion" type="hidden" id="operacion">
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" height="20" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Administrativas  </td>
    	</tr>
  </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111" height="22"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
          <input name="codigo" type="text" id="codigo" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
</div>
<?php 
	require_once("../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_conexion=$io_include->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_conexion);	
	require_once("../shared/class_folder/class_mensajes.php");
	$io_mensajes=new class_mensajes();		
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
    $ls_codemp=$_SESSION["la_empresa"]["codemp"];
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código </td>";
	print "<td>Denominación</td>";
	print "</tr>";
	$as_codigo=$_POST["codigo"];
	$as_denominacion=$_POST["denominacion"];
	if($as_codigo!="")
	{
		$as_codigo=str_pad($as_codigo,12,"0",0);
	}
	$ls_coduniad1="%".substr($as_codigo,0,4)."%";
	$ls_coduniad2="%".substr($as_codigo,4,2)."%";
	$ls_coduniad3="%".substr($as_codigo,6,2)."%";
	$ls_coduniad4="%".substr($as_codigo,8,2)."%";
	$ls_coduniad5="%".substr($as_codigo,10,2)."%";
	$ls_sql="SELECT codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm ".
			"  FROM sno_unidadadmin ".
			" WHERE codemp='".$ls_codemp."' ".
			"   AND minorguniadm ilike '".$ls_coduniad1."' ".
			"   AND ofiuniadm ilike '".$ls_coduniad2."' ".
			"   AND uniuniadm ilike '".$ls_coduniad3."' ".
			"   AND depuniadm ilike '".$ls_coduniad4."' ".
			"   AND prouniadm ilike '".$ls_coduniad5."' ".
			"   AND desuniadm ilike '%".$as_denominacion."%' ".
			" ORDER BY minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm ";
		
	$rs_data=$io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
	}
	else
	{
		while($row=$io_sql->fetch_row($rs_data))
		{
			$codigo=$row["minorguniadm"].$row["ofiuniadm"].$row["uniuniadm"].$row["depuniadm"].$row["prouniadm"];
			$ls_minorguniadm=$row["minorguniadm"];
			$ls_ofiuniadm=$row["ofiuniadm"];
			$ls_uniuniadm=$row["uniuniadm"];
			$ls_depuniadm=$row["depuniadm"];
			$ls_prouniadm=$row["prouniadm"];
			$denominacion=$row["desuniadm"];
			print "<tr class=celdas-blancas>";
			print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			print "<td>".$denominacion."</td>";
			print "</tr>";						
		}
		$io_sql->free_result($rs_data);
	}
	print "</table>";
	unset($io_include);
	unset($io_conexion);
	unset($io_sql);
	unset($io_mensajes);
	unset($io_funciones);
	unset($ls_codemp);
	unset($io_unidadadmin);
   //--------------------------------------------------------------

?>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtcoduniadm.value=codigo;
    opener.document.form1.txtdenuniadm.value=deno;
	close();
  }

 
function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_sss_cat_uni_adm.php";
	  f.submit();
  }
</script>
</html>
