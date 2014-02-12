<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   } 
   require_once ("class_funciones_banco.php");
   $io_scb= new class_funciones_banco();
$ls_orden=$io_scb->uf_obtenervalor("orden","ASC");
$ls_campoorden=$io_scb->uf_obtenervalor("campoorden","codtipfon");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Tipos de Fondos</title>
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<form id="formulario" name="formulario" method="post" action="">
  <p>&nbsp;</p>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
    <tr class="titulo-celda">
      <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion; ?>" />
      Cat&aacute;logo de Tipos de Fondos
      <input name="campoorden" type="hidden" id="campoorden" value = "<?php print $ls_campoorden; ?>" />
      <input name="orden"      type="hidden" id="orden"      value = "<?php print  $ls_orden; ?>"/></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td width="84" height="22" style="text-align:right">C&oacute;digo</td>
      <td width="414" height="22" style="text-align:left"><input name="txtcodtipfon" type="text" id="txtcodtipfon" maxlength="4" size="8" style="text-align:center" onkeypress="return keyRestrict(event,'1234567890');" /></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Denominaci&oacute;n</td>
      <td height="22" style="text-align:left"><input name="txtdentipfon" type="text" id="txtdentipfon" size="70" /></td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" /> Buscar</a></div></td>
    </tr>
  </table>
  <p align="center">
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);

//$ls_campoorden = $_POST["campoorden"];


if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codtipfon  = $_POST["txtcodtipfon"];
	 $ls_dentipfon  = $_POST["txtdentipfon"];
   }
else
   {
	 $ls_operacion = $ls_codtipfon = $ls_dentipfon = "";   
   }
   
if ($ls_operacion=='BUSCAR')
   {
	 echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 echo "<tr class=titulo-celda>";
	 echo "<td width=100 style='cursor:pointer' title='Ordenar por C&oacute;digo'       align='center' onClick=ue_orden('codtipfon')>C&oacute;digo</td>";
	 echo "<td width=400 style='cursor:pointer' title='Ordenar por Denominaci&oacute;n' align='center' onClick=ue_orden('dentipfon')>Denominaci&oacute;n</td>";
	 echo "</tr>";

     $ls_sql = "SELECT codtipfon, dentipfon 
	              FROM scb_tipofondo
		         WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'
			       AND codtipfon <>'----'
				   AND codtipfon like '%".$ls_codtipfon."%'
				   AND UPPER(dentipfon) like '%".strtoupper($ls_dentipfon)."%'
			     ORDER BY $ls_campoorden $ls_orden";
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
					  echo "<tr class=celdas-blancas>";
			          $ls_codtipfon = $rs_data->fields["codtipfon"];
			          $ls_dentipfon = $rs_data->fields["dentipfon"];
			          echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_codtipfon','$ls_dentipfon');\">".$ls_codtipfon."</a></td>";
				      echo "<td style=text-align:left title='".$ls_dentipfon."' width=400>".$ls_dentipfon."</td>";
				      echo "</tr>";
                      $rs_data->MoveNext();
					}
			 }
		  else
		     {
			   $io_msg->message("No se han definido Tipos de Fondos !!!");
			 }
		}	 
   }
?>
<div id="orden"></div>
</p>

</form>
</body>
<script language="javascript">
f   = document.formulario;
fop = opener.document.form1;
function ue_search()
{
  f.operacion.value = "BUSCAR";
  f.action			= "sigesp_scb_cat_tipofondo.php";
  f.submit();
}

function aceptar(as_codtipfon,as_dentipfon)
{
  fop.txtcodtipfon.value = as_codtipfon;
  fop.txtdentipfon.value = as_dentipfon;
  fop.hidcodtipfon.value = as_codtipfon;
  fop.hiddentipfon.value = as_dentipfon;
  ue_verificar_numero_orden();
 // close();
}

function ue_verificar_numero_orden()
{
  f = document.formulario;
  ls_proceso = "VERIFICAR_NUMORD";
  parametros = "";
  ls_numordpagmin = opener.document.form1.txtnumordpagmin.value;
  ls_codtipfon    = opener.document.form1.txtcodtipfon.value;
  parametros	  = "&codtipfon="+ls_codtipfon+"&numordpagmin="+ls_numordpagmin;
  if (parametros!="")
	 {
			// Div donde se van a cargar los resultados
			divgrid = document.getElementById('orden');
			// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax.open("POST","class_folder/sigesp_scb_c_catalogo_ajax.php",true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					texto=ajax.responseText;
					if (texto.indexOf("ERROR->")!=-1)
					   {
						 posicion = texto.indexOf("ERROR->");
						 lontitud = texto.length-posicion;						 
						 ls_texto = texto.substr(posicion,lontitud);
						 ls_texto = ls_texto.substr(7,73);
						 alert(ls_texto);
						 fop.txtcodtipfon.value = "";
						 fop.txtdentipfon.value = "";
						 fop.hidcodtipfon.value = "";
						 fop.hiddentipfon.value = "";
						 fop.txtnumordpagmin.value = "";
				       }				
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax.send("catalogo="+ls_proceso+parametros);
	 }
}
</script>
</html>