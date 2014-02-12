<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Especialidades</title>
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
a:hover {
	color: #006699;
}
-->
</style></head>

<body>
<p>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);

if (array_key_exists("operacion",$_POST))
   {
	 $ls_codesp    = $_POST["txtcodesp"];
	 $ls_denesp    = $_POST["txtdenesp"];
     $ls_operacion = $_POST["operacion"];
   }
else
   {
	 $ls_codesp    = "";
	 $ls_denesp    = "";
     $ls_operacion = "";
   }
?>
<form name="form1" method="post" action="">
  <table width="465" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="4" class="titulo-celda"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>">
      Cat&aacute;logo de Especialidades</td>
    </tr>
    <tr>
      <td width="84" height="13">&nbsp;</td>
      <td width="173" height="13">&nbsp;</td>
      <td width="108" height="13">&nbsp;</td>
      <td width="98" height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">C&oacute;digo</td>
      <td height="22"><label>
        <input name="txtcodesp" type="text" id="txtcodesp" value="<?php echo $ls_codesp ?>" size="6" maxlength="3" style="text-align:center">
      </label></td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Denominaci&oacute;n</td>
      <td height="22" colspan="3"><label>
        <input name="txtdenesp" type="text" id="txtdenesp" value="<?php echo $ls_denesp ?>" size="60" maxlength="254" style="text-align:left">
      </label></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" style="text-align:right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar Especialidad..." width="20" height="20" border="0">Buscar</a></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
  </table>
  <div align="center">
    <p>
      <?php
if ($ls_operacion=='BUSCAR')
   {
     echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 echo "<tr class=titulo-celda>";
     echo "<td width=60 style=text-align:center>C&oacute;digo</td>";
     echo "<td width=440 style=text-align:center>Denominaci&oacute;n</td>";
	 echo "</tr>";
   
	 $ls_sql  = " SELECT codesp,denesp 
	                FROM rpc_especialidad 
				   WHERE codesp like '%".$ls_codesp."%'
				     AND denesp like '%".$ls_denesp."%'
				     AND codesp<>'---' 
				   ORDER BY codesp ASC";
     $rs_data = $io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		 }
      else
	     {
		   $li_totrows = $io_sql->num_rows($rs_data);
		   if ($li_totrows>0)
		      {
			    while($row=$io_sql->fetch_row($rs_data))
				     {
					   echo "<tr class=celdas-blancas>";
					   $ls_codesp = $row["codesp"];
					   $ls_denesp = $row["denesp"];
					   echo "<td width=60 style=text-align:center><a href=\"javascript: aceptar('$ls_codesp','$ls_denesp');\">".$ls_codesp."</a></td>";
					   echo "<td width=440 style=text-align:left>".$ls_denesp."</td>";
					   echo "</tr>";
					 }
			    $io_sql->free_result($rs_data);
			  }
		   else
		      {
			    $io_msg->message("No se han definido Especialidades !!!");
			  }  
		 }
     print "</table>"; 
   }
?>  
      </p>
  </div>
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.hidestatus.value="GRABADO";
	close();
  }

function ue_search()
{
  f = document.form1;
  f.action    = "sigesp_rpc_cat_especialidad.php";
  f.operacion.value = "BUSCAR";
  f.submit();
}
</script>
</html>