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
	 $ls_operacion=$_POST["operacion"];
	 $ls_codigo="%".$_POST["codigo"]."%";
	 $ls_nombre="%".$_POST["nombre"]."%";
	 $ls_obj=$_POST["obj"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_codigo    = "%%";
	 $ls_nombre	   = "%%";
	 $ls_obj	   = $_GET["obj"];
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Beneficiarios</title>
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
        <td height="22" colspan="2">Cat&aacute;logo de Beneficiarios</td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&eacute;dula</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" maxlength="10" style="text-align:center">        
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
        <td height="22"><div align="right"><a href="javascript: ue_search();">
          <input name="obj" type="hidden" id="obj" value="<?php print $ls_obj;?>">
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
    <div align="center"><br>
        <?php
$ls_codemp=$arr["codemp"];
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 style=text-align:center>C&eacute;dula</td>";
print "<td width=400 style=text-align:center>Nombre del Beneficiario</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT ced_bene,nombene,apebene                                                                    ".
	           "  FROM rpc_beneficiario                                                                            ".
			   " WHERE codemp='".$ls_codemp."' AND ced_bene like '".$ls_codigo."' AND nombene like '".$ls_nombre."'".
			   "   AND ced_bene<>'----------'                                                                      ";
	 $rs_data    = $io_sql->select($ls_sql);
	 $li_numrows = $io_sql->num_rows($rs_data);
	 if ($li_numrows>0)
	    {
	      while ($row=$io_sql->fetch_row($rs_data))
		        {
				  print "<tr class=celdas-blancas>";
				  $ls_cedben  = $row["ced_bene"];
				  $ls_nomben  = $row["nombene"];
				  $ls_apeben  = $row["apebene"];
				  $ls_nombene = $ls_nomben." ".$ls_apeben;
				  print "<td width=100 style=text-align:center><a href=\"javascript: aceptar('$ls_cedben','$ls_nomben');\">".$ls_cedben."</a></td>";
				  print "<td width=400 style=text-align:left>".$ls_nombene."</td>";
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
function aceptar(ls_cedben,ls_nomben)
{
  f = document.form1;
  ls_opener = opener.document.form1.id;
  if (ls_opener=="sigesp_scb_cat_solicitudpago.php")
     {
	   opener.document.form1.txtcodigo.value = ls_cedben;
	   opener.document.form1.txtnombre.value = ls_nomben;	 
	 }
  else
     {
	   ls_obj = f.obj.value;
	   eval("opener.document.form1."+ls_obj+".value='"+ls_cedben+"'");
	 }
  close();
}

function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_cat_bene_general.php";
	f.submit();
}
</script>
</html>