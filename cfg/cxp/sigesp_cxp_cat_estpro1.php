<?php
session_start();
$arr          = $_SESSION["la_empresa"];
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$li_estmodest = $arr["estmodest"];
if ($li_estmodest=='1')
   {
	 $li_maxlength = '20';
	 $li_size      = '25';
   }
else
   {
	 $li_maxlength = '2';
	 $li_size      = '5';
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 1 <?php print $arr["nomestpro1"] ?> </title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2" class="titulo-celda"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de<?php print $arr["nomestpro1"] ?></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="85" height="22"><div align="right">Codigo</div></td>
        <td width="413" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="<?php print $li_size ?>" maxlength="<?php print $li_maxlength ?>" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="65" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
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
	 $ls_codestpro1 = $_POST["codigo"];
	 $ls_denestpro1 = $_POST["denominacion"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = "";
	 $ls_denestpro1 = "";
   }
   
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100>Código </td>";
print "<td width=400>Denominación</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql  = "  SELECT denestpro1,substr(codestpro1,".$li_longestpro1.",25) as codestpro1,estcla FROM spg_ep1                                                         ".
	            "  WHERE codemp='".$ls_codemp."' AND codestpro1 like '%".$ls_codestpro1."%' AND ".
				"       denestpro1 like '%".$ls_denestpro1."%' ".
				"  ORDER BY estcla,codestpro1 ";
	 $rs_data = $io_sql->select($ls_sql);
	 $data    = $rs_data;
	 if ($row=$io_sql->fetch_row($rs_data))
	    {
		  $data     = $io_sql->obtener_datos($rs_data);
		  $arrcols  = array_keys($data);
		  $totcol   = count($arrcols);
		  $ds->data = $data;
		  $totrow   = $ds->getRowCount("codestpro1");
		  for ($z=1;$z<=$totrow;$z++)
		      {
			    print "<tr class=celdas-blancas>";
			    $ls_codestpro1 = $data["codestpro1"][$z];
			    $ls_denestpro1 = $data["denestpro1"][$z];
			    $ls_estcla     = $data["estcla"][$z];
				$ls_codestpro1 = $data["codestpro1"][$z];
				print "<td  style=text-align:center width=150><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_estcla');\">".$ls_codestpro1."</a></td>";
			    print "<td  style=text-align:left   width=350>".$ls_denestpro1."</td>";
		  	    print "</tr>";			
		      }
	    }
	 else
	    {
		  $io_msg->message("No se han definido ".$arr["nomestpro1"]);
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
  function aceptar(codigo,deno,estcla)
  {
    fop                        = opener.document.form1;
	fop.txtcodestpro1.value    = codigo;
    fop.txtdenestpro1.value    = deno;
	fop.operacion.value        = "BUSCAR";
	fop.txtestcla1.value             = estcla
	fop.txtcodestpro1.readOnly = true;
	ls_maestro                 = fop.hidmaestro.value;             
	if (ls_maestro=='Y')
	   {
		 if (estatus=='P')
		    {
			  fop.rbclasificacion[0].checked=true;
		    }
	 	 else
		    {
			  fop.rbclasificacion[1].checked=true;
		    }
	     fop.status.value='C';	   
	   }
	close();
  }
  
  function ue_search()
  {
  f                 = document.form1;
  f.operacion.value = "BUSCAR";
  f.action          = "sigesp_cxp_cat_estpro1.php";
  f.submit();
  }
</script>
</html>