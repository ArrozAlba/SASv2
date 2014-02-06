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
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:hover {
	color: #006699;
}
-->
</style></head>

<body>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_include.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg		= new class_mensajes();

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion    = $_POST["operacion"];
	 $ls_codigo       = $_POST["codigo"];
	 $ls_denominacion = "%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion = "";
	 $ls_codigo    = $ls_denominacion = "";
   }
?>
<form name="form1" method="post" action="">
<div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="86" height="22" align="right">Cuenta</td>
        <td width="274" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" value="<?php print $ls_codigo; ?>" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2" style="text-align:left"><input name="nombre" type="text" id="nombre" style="text-align:left" size="70"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>"></td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<p><br>
      <?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=100>Cuenta</td>";
print "<td style=text-align:center width=400>Denominación</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT TRIM(sc_cuenta) AS sc_cuenta, denominacion, status
				  FROM scg_cuentas
		         WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'
				   AND sc_cuenta like '".$ls_codigo."%' 
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
			           $ls_dencta = utf8_decode($rs_data->fields["denominacion"]);
					   $ls_estcta = $rs_data->fields["status"];
				       if ($ls_estcta=='S')
					      {
						    echo "<tr class=celdas-blancas>";
							echo "<td style=text-align:center width=100>".$ls_scgcta."</td>";
						  }
					   else
					      {
						    echo "<tr class=celdas-azules>";
						    echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_scgcta','$ls_dencta');\">".$ls_scgcta."</a></td>";
						  }
				       echo "<td style=text-align:left title='".$ls_dencta."' width=400>".$ls_dencta."</td>";
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas !!!");
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
  function aceptar(as_scgcta,as_dencta)
  {
    opener.document.form1.txtcuentacontable.value=as_scgcta;
	opener.document.form1.txtdencuenta.value=as_dencta;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_filt_scg.php";
	  f.submit();
  }
</script>
</html>