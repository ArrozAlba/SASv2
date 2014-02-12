<?php
//session_id('8675309');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Proveedores</title>
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
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Proveedores</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">Codigo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="20" maxlength="10" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="nombre" type="text" id="nombre" size="70" maxlength="254" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
$in     = new sigesp_include();
$con    = $in->uf_conectar();
$io_sql = new class_sql($con);
$arr    = $_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
	$ls_destino=$_POST["destino"];
}
else
{
	$ls_operacion="";
	$ls_destino="";
}

if(array_key_exists("destino",$_GET))
{
	$ls_destino=$_GET["destino"];
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center  width=100>Código</td>";
print "<td style=text-align:center  width=400>Nombre del Proveedor</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql     = "SELECT cod_pro,nompro ".
	               "  FROM rpc_proveedor  ".
				   " WHERE cod_pro like '".$ls_codigo."' AND nompro like '".$ls_nombre."' ".
				   " ORDER BY cod_pro ASC";
 	 $rs_data    = $io_sql->select($ls_sql);
     $li_numrows = $io_sql->num_rows($rs_data);
	 if ($li_numrows>0)
	    {
		  while ($row=$io_sql->fetch_row($rs_data))
		        {
				  if($ls_destino == "")
				  {
				   print "<tr class=celdas-blancas>";
				   $ls_codpro = $row["cod_pro"];
				   $ls_nompro = $row["nompro"];
				   print "<td style=text-align:center  width=100><a href=\"javascript: aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
				   print "<td style=text-align:left  width=400>".$ls_nompro."</td>";
				   print "</tr>";			
				  }
				  else
				  {
				   print "<tr class=celdas-blancas>";
				   $ls_codpro = $row["cod_pro"];
				   $ls_nompro = $row["nompro"];
				   print "<td style=text-align:center  width=100><a href=\"javascript: aceptar_rep('$ls_codpro','$ls_destino');\">".$ls_codpro."</a></td>";
				   print "<td style=text-align:left  width=400>".$ls_nompro."</td>";
				   print "</tr>";
				  } 
				}
		} 
	 else
	    { ?>
	      <script language="javascript">
		   alert("No se han creado Proveedores !!!");
		   close();
		  </script>
	      <?php
	   }
}
print "</table>";
?>
</div>
<input name="destino" type="hidden" id="destino" value= "<?php print $ls_destino; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codpro,ls_nompro)
  {
	opener.document.form1.txtprovbene.value  = ls_codpro;
    opener.document.form1.txtdesproben.value = ls_nompro;
	close();
  }
  
  function aceptar_rep(ls_codpro,ls_destino)
  {
	obj=eval("opener.document.form1."+ls_destino+"");
	obj.value=ls_codpro;
	close();
  }
  
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_catdinamic_prov.php";
  f.submit();
  }
</script>
</html>
