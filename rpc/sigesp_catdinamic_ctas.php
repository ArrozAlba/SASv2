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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="97" height="22" align="right">Cuenta</td>
        <td width="263" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="35" maxlength="25" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="60" maxlength="254" style="text-align:left">
<label></label>
<br>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td height="22" colspan="2" align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a></td>
      </tr>
    </table>
	<br>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_scgcta    = $_POST["codigo"];
	 $ls_denctascg = "%".$_POST["nombre"]."%";	
   }
else
   {
	 $ls_operacion="";
   }
if (array_key_exists("tipo",$_GET))
   {
	 $ls_tipo    = $_GET["tipo"];
   }
else
   {
	 $ls_tipo="";
   }

echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=120 height=22>Cuenta</td>";
echo "<td style=text-align:center width=380 height=22>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT trim(sc_cuenta) as sc_cuenta, denominacion, status
				 FROM scg_cuentas
                WHERE codemp = '".$ls_codemp."' 
				  AND sc_cuenta like '".$ls_scgcta."%'
				  AND denominacion like '".$ls_denctascg."'
				ORDER BY sc_cuenta";
				
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
			   while ($row=$io_sql->fetch_row($rs_data))
			         {
					   $ls_scgcta    = trim($row["sc_cuenta"]);
		 	           $ls_denscgcta = $row["denominacion"];
			           $ls_estscgcta = $row["status"];
					   if ($ls_estscgcta=="S")
						  {
						    echo "<tr class=celdas-blancas>";
						    echo "<td style=text-align:center  width=120>".$ls_scgcta."</td>";
						  }
					   else
					      {
							 echo "<tr class=celdas-azules>";
							 switch($ls_tipo)
							 {
								case "":
								  echo "<td style=text-align:center  width=120><a href=\"javascript: aceptar('$ls_scgcta','$ls_denscgcta','$ls_estscgcta');\">".$ls_scgcta."</a></td>";
								break;
								case "recdoc":
								  echo "<td style=text-align:center  width=120><a href=\"javascript: aceptarrecdoc('$ls_scgcta','$ls_denscgcta');\">".$ls_scgcta."</a></td>";
								break;
								case "ctaant":
								  echo "<td style=text-align:center  width=120><a href=\"javascript: aceptarctaant('$ls_scgcta','$ls_denscgcta');\">".$ls_scgcta."</a></td>";
								break;
							 }
						  }
					   echo "<td style=text-align:left width=380 title='".ltrim($ls_denscgcta)."'>".$ls_denscgcta."</td>";
					   echo "</tr>";
					 }  
	         }
          else
			 {
			   $io_msg->message("No se han Creado Cuentas Contables !!!");
			 }
        }
   }
echo "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,denominacion,status)
  {
    opener.document.form1.txtcontable.value=cuenta;
	opener.document.form1.txtcontable.readOnly=true;
	opener.document.form1.txtdencuenta.value=denominacion;
	close();
  }

  function aceptarrecdoc(cuenta,denominacion)
  {
    opener.document.form1.txtcontablerecdoc.value=cuenta;
	opener.document.form1.txtcontablerecdoc.readOnly=true;
	opener.document.form1.txtdencuentarecdoc.value=denominacion;
	close();
  }

  function aceptarctaant(cuenta,denominacion)
  {
    opener.document.form1.txtctaant.value=cuenta;
	opener.document.form1.txtdenctaant.value=denominacion;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_ctas.php?tipo=<?php print $ls_tipo; ?>";
	  f.submit();
  }
</script>
</html>