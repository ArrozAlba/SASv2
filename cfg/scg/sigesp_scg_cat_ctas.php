<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="21" colspan="3" align="right"><div align="center">Cat&aacute;logo de Cuentas Contables </div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="98" height="22" align="right">Cuenta</td>
        <td width="262" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2"><input name="nombre" type="text" id="nombre" size="60"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
      </tr>
    </table>
	<p><br>
      <?php
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../class_folder/class_funciones_configuracion.php");
$fun_conf=new class_funciones_configuracion();
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$int_scg    = new class_sigesp_int_scg();
$io_msg		= new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion    = $_POST["operacion"];
	 $ls_codigo       = $_POST["codigo"]."%";
	 $ls_denominacion = "%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion="";
   }
$ls_destino=$fun_conf->uf_obtenervalor_get("destino",""); 
$ls_fila=$fun_conf->uf_obtenervalor_get("fila","");

echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>Cuenta</td>";
echo "<td style=text-align:center width=400>Denominación</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT trim(sc_cuenta) as sc_cuenta, denominacion, status 
	              FROM scg_cuentas
		         WHERE codemp = '".$ls_codemp."' 
				   AND sc_cuenta like '".$ls_codigo."' 
				   AND UPPER(denominacion) like '".strtoupper($ls_denominacion)."' 
				 ORDER BY sc_cuenta ASC";

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
			    while(!$rs_data->EOF)
				     {
					   $ls_scgcta = $rs_data->fields["sc_cuenta"];
					   $ls_dencta = $rs_data->fields["denominacion"];
					   $ls_estcta = $rs_data->fields["status"];
					   if ($ls_estcta=="S")
				          {
					        echo "<tr class=celdas-blancas>";
					      }
					   else
						  {
						    echo "<tr class=celdas-azules>";
						  }

					   switch ($ls_destino){
					     case "":
						   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_scgcta','$ls_dencta','$ls_estcta');\">".$ls_scgcta."</a></td>";
						 break;
					     case "destino":
						   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar2('$ls_scgcta','$ls_dencta','$ls_estcta','$ls_fila');\">".$ls_scgcta."</a></td>";
						 break;
					   }					   
				       echo "<td style=text-align:left title='".$ls_dencta."' width=400>".$ls_dencta."</td>";
					   echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas Contables !!!");   
			  }
		 }  		 
   }
echo "</table>";
?></p>
	</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,d,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=d;
	opener.document.form1.status.value='C';
	opener.document.form1.txtcuenta.readOnly=true;
    close();
  }
  
  function aceptar2(cuenta,d,status,fila)
  {
     f=document.form1;
     eval("opener.document.form1.txtcuentascg"+fila+".value='"+cuenta+"'");
     close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_scg_cat_ctas.php?destino=<?PHP print $ls_destino;?>&fila=<?PHP print $ls_fila;?>";
	  f.submit();
  }
</script>
</html>