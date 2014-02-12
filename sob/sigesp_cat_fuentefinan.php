<?Php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 

if(!array_key_exists("campo",$_POST))
{
	$ls_campo="codfuefin";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Fuentes de Financiamiento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<style type="text/css">
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
<?Php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsfuente=new class_datastore();
$io_sql=new class_sql($conn);

$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT * ".
        " FROM sigesp_fuentefinanciamiento ".
		" ORDER BY $ls_campo $ls_orden ";
$rs_fuente=$io_sql->select($ls_sql);
$data=$rs_fuente;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Fuentes de Financiamiento </td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
  <div align="center">
    <?Php
if ($row=$io_sql->fetch_row($rs_fuente))
   {
     $data=$io_sql->obtener_datos($rs_fuente);
     $io_dsfuente->data=$data;
     $totrow=$io_dsfuente->getRowCount("codfuefin");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td><a href=javascript:ue_ordenar('codfuefin','');><font color=#FFFFFF>Código</font></a></td>";
	 print "<td ><a href=javascript:ue_ordenar('denfuefin','');><font color=#FFFFFF>Denominación</font></a></td>";
 	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
         {
			print "<tr class=celdas-blancas>";
			$ls_codfuefin   =$data["codfuefin"][$z];
			$ls_denominacion=$data["denfuefin"][$z];
			$ls_explicacion =$data["expfuefin"][$z];
			print "<td align=center><a href=\"javascript: aceptar('$ls_codfuefin','$ls_denominacion','$ls_explicacion');\">".$ls_codfuefin."</a></td>";
			print "<td  style=text-align:left>".$ls_denominacion."</td>";
			print "</tr>";			
         }
	print "</table>";
	}
else
    {	 
	  $io_msg->message("No se han creado Fuentes de Financiamiento");
	}		 
$io_sql->free_result($rs_fuente);
$io_sql->close();
?>
  </div>
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,explicacion)
  {
	codigoaux=codigo.substring(0,1);
	if (codigoaux=="-")
		alert("Escoja una Fuente de Financiamiento Válida");
	else
	{
		opener.ue_cargarfuente(codigo,denominacion);
		close();
	}
  }
</script>
</html>