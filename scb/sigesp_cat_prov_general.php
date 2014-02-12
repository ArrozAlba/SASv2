<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
$in     = new sigesp_include();
$con    = $in->uf_conectar();
$io_sql = new class_sql($con);
$arr    = $_SESSION["la_empresa"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_codigo    = "%".$_POST["codigo"]."%";
	 $ls_nombre    = "%".$_POST["nombre"]."%";
	 $ls_obj       = $_POST["obj"]; 
	 $ls_rifpro    = $_POST["txtrifpro"];  
   }
else
   {
	 $ls_operacion = "";
	 $ls_obj       = $_GET["obj"];
	 $ls_rifpro    = "";
   }
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
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Proveedores</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="nombre" type="text" id="nombre" size="70" maxlength="254">
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">RIF</td>
        <td height="22"><label>
          <input name="txtrifpro" type="text" id="txtrifpro" maxlength="15" style="text-align:center">
        </label></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">
          <div align="right">
            <input name="obj" type="hidden" id="obj" value="<?php print $ls_obj;?>">
            <a href="javascript: ue_search();"> <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a> </div></td></tr>
    </table>
	 <div align="center"><br>
         <?php

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>Código</td>";
print "<td width=300 style=text-align:center>Nombre del Proveedor</td>";
print "<td width=100 style=text-align:center>RIF</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = " SELECT cod_pro,nompro,rifpro
	               FROM rpc_proveedor
			      WHERE codemp='".$arr["codemp"]."'
			        AND cod_pro like '".$ls_codigo."'
					AND nompro like '".$ls_nombre."'
			        AND rifpro like '%".$ls_rifpro."%'
			        AND cod_pro<>'----------'
			        AND estprov=0
			      ORDER BY cod_pro ASC";
 	 $rs_data    = $io_sql->select($ls_sql);
     $li_numrows = $io_sql->num_rows($rs_data);
     if ($li_numrows>0)
	    {
          while ($row=$io_sql->fetch_row($rs_data))
	            {
				  print "<tr class=celdas-blancas>";
			      $ls_codpro = $row["cod_pro"];
			      $ls_nompro = $row["nompro"];
				  $ls_rifpro = $row["rifpro"];
		 	      print "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_codpro','$ls_nompro');\">".$ls_codpro."</a></td>";
			      print "<td width=300 style=text-align:left>".$ls_nompro."</td>";
				  print "<td width=100 style=text-align:left>".$ls_rifpro."</td>";
			      print "</tr>";			
				}		
		}
     else
	    { ?>
	   <script language="javascript">
		    alert("No se encontraron Proveedores !!!");
			close();
		  </script> 
	   <?php
		}
   }
print "</table>";
?>
  </div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_codpro,ls_nompro)
{
  f = document.form1;
  ls_opener = opener.document.form1.id;
  if (ls_opener=="sigesp_scb_cat_solicitudpago.php")
     {
	   opener.document.form1.txtcodigo.value = ls_codpro;
	   opener.document.form1.txtnombre.value = ls_nompro;	 
	 }
  else
     {
	   ls_obj = f.obj.value;
	   eval("opener.document.form1."+ls_obj+".value='"+ls_codpro+"'");
	 }
  close();
}
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_cat_prov_general.php";
	f.submit();
}
</script>
</html>