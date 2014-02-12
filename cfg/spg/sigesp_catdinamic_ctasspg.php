<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("sigesp_spg_c_cmbestpro.php");
$dat=$_SESSION["la_empresa"];
$nvo_estprog=new sigesp_spg_c_cmbestpro();
$int_scg=new class_sigesp_int_scg();
$io_msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo=$_POST["codigo"];
		$ls_denominacion=$_POST["nombre"];
		$ls_codscg=$_POST["txtcuentascg"];
		$ls_estpro1=$_POST["codestpro1"];
		$ls_estpro2=$_POST["codestpro2"];
		$ls_estpro3=$_POST["codestpro3"];
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_denominacion="";
		$ls_codscg="";
		$ls_estpro1="";
		$ls_estpro2="";
		$ls_estpro3="";
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="15" align="right">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
    <td><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td colspan="2">
      <div align="left">
        <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center" readonly>
        <a href="javascript:catalogo_estpro1();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>        <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>     
      </div>
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td><div align="right"><?php print $dat["nomestpro2"] ; ?></div>      </td>
    <td colspan="2"><div align="left">
      <input name="codestpro2" type="text" id="codestpro2" size="22" maxlength="6" style="text-align:center" readonly>
        <a href="javascript:catalogo_estpro2();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
        <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly>
    </div></td>
  </tr>
  <tr>
    <td><div align="right"><?php print $dat["nomestpro3"] ; ?></div></td>
    <td colspan="2">      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="22" maxlength="3" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
    </div></td>
  </tr>
      <tr>
        <td align="right" width="146">Codigo</td>
        <td width="127"><div align="left">
          <input name="codigo" type="text" id="codigo" maxlength="20">        
        </div></td>
        <td width="325">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="60">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td colspan="2"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php 

print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{

	$ls_cadena ="SELECT * FROM spg_cuentas ".
		         "WHERE codemp = '".$as_codemp."' AND spg_cuenta like '".$ls_codigo."%' AND denominacion like '%".$ls_denominacion."%' AND sc_cuenta like '".$ls_codscg."%' AND codestpro1 like '%".$ls_estpro1."%' AND codestpro2 like '%".$ls_estpro2."%' AND codestpro3 like '%".$ls_estpro3."%' ORDER BY spg_cuenta";
//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_cta=$SQL->select($ls_cadena);

	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("spg_cuenta");
		for($z=1;$z<=$totrow;$z++)
		{
			$cuenta=$data["spg_cuenta"][$z];
			$denominacion=$data["denominacion"][$z];
			$codest1=$data["codestpro1"][$z];
			$denestpro1=uf_obtener_denominacion("spg_ep1","denestpro1"," codestpro1='".$codest1."'");
			$codest2=$data["codestpro2"][$z];
			$denestpro2=uf_obtener_denominacion("spg_ep2","denestpro2"," codestpro1='".$codest1."' AND codestpro2 ='".$codest2."'");
			$codest3=$data["codestpro3"][$z];
			$denestpro3=uf_obtener_denominacion("spg_ep3","denestpro3"," codestpro1='".$codest1."' AND codestpro2 ='".$codest2."' AND codestpro3 ='".$codest3."' ");
			$scgcuenta=$data["sc_cuenta"][$z];
			$status=$data["status"][$z];
			if($status=="S")
			{
				print "<tr class=celdas-blancas>";
				print "<td>".$cuenta."</td>";
				print "<td  align=left>".$codest1."</td>";
				print "<td  align=left>".$codest2."</td>";
				print "<td  align=left>".$codest3."</td>";
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$codest1','$denestpro1','$codest2','$denestpro2','$codest3','$denestpro3','$status');\">".$cuenta."</a></td>";
				print "<td  align=left>".$codest1."</td>";
				print "<td  align=left>".$codest2."</td>";
				print "<td  align=left>".$codest3."</td>";
				print "<td  align=left>".$denominacion."</td>";
				print "<td  align=center>".$scgcuenta."</td>";
			}
			print "</tr>";			
		}
		$SQL->free_result($rs_cta);
		$SQL->close();
	}
	else
	{
		$io_msg->message("No se han creado cuentas para la programatica seleccionada");
		print "<script language=JavaScript>";
		print " close();";
		print "<script>";
	}
}
print "</table>";
function uf_obtener_denominacion($ls_tabla,$ls_campo,$ls_where)
{
global $SQL;
global $as_codemp;
$ls_denominacion="";
	$ls_cadena ="SELECT * FROM ".$ls_tabla." WHERE codemp = '".$as_codemp."' AND ".$ls_where;
//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_est=$SQL->select($ls_cadena);

	$data=$rs_est;
	if($rs_est==false)
	{
		print $this->SQL->message;
	}
	else
	{
		if($row=$SQL->fetch_row($rs_est))
		{
			$ls_denominacion=$row[$ls_campo];
			$SQL->free_result($rs_est);
		}	
	}
	
	return $ls_denominacion;
}

?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,deno,scgcuenta,codest1,denest1,codest2,denest2,codest3,denest3,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
    opener.document.form1.txtcuentaplan.value=scgcuenta;
    opener.document.form1.codestpro1.value=codest1;
	opener.document.form1.codestpro2.value=codest2;
	opener.document.form1.codestpro3.value=codest3;
    opener.document.form1.denestpro1.value=denest1;
	opener.document.form1.denestpro2.value=denest2;
	opener.document.form1.denestpro3.value=denest3;
//	opener.document.form1.operacion.value="BUSCAR";
//	opener.document.form1.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_ctasspg.php";
	  f.submit();
  }
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}
</script>
</html>
