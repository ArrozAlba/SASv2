<?php
session_start();
$arr          = $_SESSION["la_empresa"];
$li_estmodest = $arr["estmodest"];
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Consolidación de Presupuesto</title>
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
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Bases de Datos para Consolidaci&oacute;n de Presupuesto </td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="131" height="22"><div align="right">Proyecto y/o Acci&oacute;n Centralizada </div></td>
        <td width="367" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength ?>" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Base de Datos </div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="65" maxlength="100">
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
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_funciones_gasto.php");
$io_fun_gasto=new class_funciones_gasto();
$in     	= new sigesp_include();
$con    	= $in->uf_conectar();
$io_msg 	= new class_mensajes();
$ds     	= new class_datastore();
$io_sql 	= new class_sql($con);
$io_funcion = new class_funciones();

$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_proyacc = $_POST["codigo"];
	 $ls_nombd = $_POST["denominacion"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_proyacc = "";
	 $ls_nombd = "";
   }
$ls_fila=$io_fun_gasto->uf_obtenervalor("fila",""); 
  
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100>Código </td>";
print "<td width=600>Denominación</td>";
if ($li_estmodest=='1')
{ 
print "<td width=200>Tipo</td>";
}
print "</tr>";


if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql  = "SELECT * FROM sigesp_consolidacion".
	            " WHERE codemp='".$ls_codemp."' AND nombasdat like '%".$ls_nombd."%' AND ".
				"       codestpro1 like '%".$ls_proyacc."%' ";
	 $rs_data = $io_sql->select($ls_sql);
	 $data    = $rs_data;
	 if ($row=$io_sql->fetch_row($rs_data))
	    {
		  $data     = $io_sql->obtener_datos($rs_data);
		  $arrcols  = array_keys($data);
		  $totcol   = count($arrcols);
		  $ds->data = $data;
		  $totrow   = $ds->getRowCount("nombasdat");
		  for ($z=1;$z<=$totrow;$z++)
		      {
			    print "<tr class=celdas-blancas>";
				$ls_codemp=$data["codemp"][$z];
				//$ls_denestpro= $data["denestpro"][$z];
			    $ls_nombasd = $data["nombasdat"][$z];
			    $ls_proyacc = $data["codestpro1"][$z];
			    $ls_estcla     = $data["estcla"][$z];
		
				  if($ls_estcla=='P')
				  {
				  	$ls_denestcla='PROYECTO';
					
				  }
				  else{
				  	$ls_denestcla='ACCION';
				  }
			
				print "<td  style=text-align:center width=150><a href=\"javascript: aceptar('$ls_nombasd');\">".$ls_nombasd."</a></td>";
				print "<td  style=text-align:left   width=550>".$ls_proyacc."</td>";
				print "<td  style=text-align:left   width=550>".$ls_denestcla."</td>";
				print "</tr>";
				  	
		      }
	    }
	
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_nombd)
  {
    fop                      = opener.document.form1;
	fop.txtbddestino.value    = ls_nombd;
    close();
  }
  
  function ue_search()
  {
  f                 = document.form1;
  f.operacion.value = "BUSCAR";
  f.action          = "sigesp_sss_cat_consolidacion.php";
  f.submit();
  }
</script>
</html>