<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Calificaci&oacute;n de Proveedores</title>
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Calificaci&oacute;n de Proveedores</td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="">
  <div align="center">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();

     $ls_sql ="SELECT codclas,denclas 
                 FROM rpc_clasificacion 
				WHERE codclas<>'--'
		        ORDER BY codclas ASC ";
		   
  	 $rs_data = $io_sql->select($ls_sql);
     if ($rs_data===false)
	    {
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		}
     else
	    {
		  $li_numrows = $io_sql->num_rows($rs_data);
		  if ($li_numrows>0)
		     {
			   echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			   echo "<tr class=titulo-celda>";
			   echo "<td>Código</td>";
			   echo "<td>Denominación</td>";
			   while($row=$io_sql->fetch_row($rs_data))
			        {
					  echo "<tr class=celdas-blancas>";
					  $ls_codcal = $row["codclas"];
			          $ls_dencal = $row["denclas"];
			          echo "<td><a href=\"javascript: aceptar('$ls_codcal','$ls_dencal');\">".$ls_codcal."</a></td>";
			          echo "<td>".$ls_dencal."</td>";
					  echo "</tr>";
					}
               echo "</table>";
               $io_sql->free_result($rs_data);
			 }
          else
		     {
			   $io_msg->message("No se han creado Calificaciones !!!");
			 }
		}
?>
</div>
</form>
</body>
<script language="JavaScript">
function aceptar(codigo,denominacion)
{
	fop = opener.document.form1;
	fop.txtcodigo.value=codigo;
	fop.txtcodigo.readOnly=true;
	fop.hidestatus.value="GRABADO";
	fop.txtdenominacion.value=denominacion;
	close();
}
</script>
</html>