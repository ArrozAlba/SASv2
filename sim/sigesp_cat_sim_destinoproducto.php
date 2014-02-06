<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Destino del Producto Devuelto</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 36px}
-->
</style>
</head>

<body>
<br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_in  = new sigesp_include();
$con    = $io_in->uf_conectar();
$io_ds  = new class_datastore();
$io_sql = new class_sql($con);
$arr    = $_SESSION["la_empresa"];
?>
<form name="form1" method="post" action="">
  <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="6" class="titulo-celda"><div align="center">Cat&aacute;logo de Destino del Producto Devuelto</div></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td width="81"><div align="right">Codigo &nbsp;</div></td>
      <td width="87"><p>
        <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo; ?>" size="17" maxlength="15">
      </p>        </td>
      <td width="119"><div align="right">Denominaci&oacute;n &nbsp;</div></td>
      <td width="150">
        <div align="right">
          <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion; ?>" size="30" maxlength="60">
        </div></td>
      <td width="27"><p align="center">&nbsp; </p>          </td>
    </tr>
    <tr>
      <td colspan="6"  align="right"><a href="javascript: ue_search();">
         <input name="operacion"    type="hidden"  id="operacion"    value="<?php print $ls_operacion;?>">
      <img src="../shared/imagenes/buscarnuevo.png" alt="Buscar" width="22" height="22" border="0" onClick="ue_search()">Buscar</a></div></td>
    </tr>
    <tr>
      <td colspan="6" align="center">
        <div align="center">
      </div></td>
    </tr>
  </table>
  <p align="center">
<?php
print "<table width=450 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "</tr>";

if( ($ls_operacion=="BUSCAR") || ($ls_operacion=="") )
  {     
            $ls_codigo       = $_POST["txtcodigo"];	  
			$ls_denominacion = $_POST["txtdenominacion"];	  
			
			$ls_sql="SELECT *                                           ".
			        "FROM   sim_destinoproducto                         ".
					"WHERE  coddespro ilike '%".$ls_codigo."%'          ".
					"  AND  dendespro ilike '%".$ls_denominacion."%'    ".
					"  AND  estatus = '1'                               ".
					"ORDER BY coddespro                                 ";
					
			$rs=$io_sql->select($ls_sql);
			$data=$rs;
			if ($row=$io_sql->fetch_row($rs))
			{
					 $data=$io_sql->obtener_datos($rs);
					 $arrcols=array_keys($data);
					 $totcol=count($arrcols);
					 $io_ds->data=$data;
					 $totrow=$io_ds->getRowCount("coddespro");
					 print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					 print "<tr class=titulo-celda>";
					 print "<td>Código </td>";
					 print "<td>Denominación</td>";
					 print "</tr>";
					 for($z=1;$z<=$totrow;$z++)
						{
						  print "<tr class=celdas-blancas>";
						  $codigo      =$data["coddespro"][$z];
						  $denominacion=$data["dendespro"][$z];
						  $observacion =$data["obsdespro"][$z];
						  
						  
						  print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$observacion');\">".$codigo."</a></td>";
						  print "<td align=left>".$denominacion."</td>";
						  print "</tr>";			
					   }
					  $io_sql->free_result($rs);
					  print "</table>";
			}
}
print "</table>";
?>
</p>
</form>      
</body>
<script language="JavaScript">
function aceptar(codigo,denominacion,observacion)
  {     
	 fop = opener.document.form1;
	 fop.txtcoddespro.value = codigo;
     fop.txtdendespro.value = denominacion;
	 fop.txtobsdespro.value = observacion;
	 close();
  }
  
  function ue_search()
   {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_sim_destinoproducto.php";
	  f.submit();
   }
</script>
</html>
