<?
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "opener.document.form1.submit();";
	print "close();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$fun=new class_funciones();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
	}
else
{
	$ls_operacion="";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas IVA</title>
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
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas IVA </td>
    </tr>
  </table>
 
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right" width="135">Codigo</td>
        <td width="122"><div align="left">
          <input name="codigo" type="text" id="codigo" size="15" maxlength="10">        
        </div></td>
        <td width="341">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72" maxlength="100">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?

print "<table width=450 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta IVA</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
							
	$ls_cadena =" SELECT ue.sc_cuenta,ue.denominacion 
				  FROM scg_cuentas ue 
		   		  WHERE ue.codemp = '".$as_codemp."' AND ue.sc_cuenta like '".$ls_codigo."' AND ue.denominacion like '".$ls_denominacion."' and substr(sc_cuenta,1,7)='2190901' ORDER BY ue.sc_cuenta";
//print $ls_cadena;
	//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta==false)
	{
		$msg->message($fun->uf_convertirmsg($SQL->message));
	}
	else
	{
		$data=$rs_cta;
		if($row=$SQL->fetch_row($rs_cta))
		{
			$data=$SQL->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("sc_cuenta");
			for($z=1;$z<=$totrow;$z++)
			{
			$codigo=$data["sc_cuenta"][$z];
			$denominacion=$data["denominacion"][$z];
			print "<tr class=celdas-azules>";
			print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			print "<td  align=left>".$denominacion."</td>";							
			print "</tr>";			
			}
			$SQL->free_result($rs_cta);
			$SQL->close();
		}
		else
		{
		?>
		<script language="JavaScript">
		alert("No se han creado unidades.....");
		//close();
        </script>
		<?
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
  function aceptar(cuenta,deno)
  {
    opener.ue_cargarcuentaiva(cuenta,deno);
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_cuentaiva.php";
	  f.submit();
  }	
</script>
</html>