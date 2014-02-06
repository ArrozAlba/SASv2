<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$dat=$_SESSION["la_empresa"];
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_cod=$_POST["codigo"];
		$ls_denominacion=$_POST["nombre"];
		$ls_codscg="";
		$ls_estpro1="";
		$ls_estpro2="";
		$ls_estpro3="";
	}
	else
	{
		$ls_operacion="";
		$ls_cod="";
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
<title>Cat&aacute;logo de Cuentas Presupuestaria </title>
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
  <table width="501" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="551" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Cuentas Presupuestaria </div></td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right" width="107">Codigo</td>
        <td width="5">
          <div align="left"></div></td>
        <td width="312"><input name="textfield" type="text" value="408" size="2" style="width:17px "><input name="codigo" type="text" id="codigo2" maxlength="20"></td>
        <td width="74">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">
<label></label>
<br>
          </div></td>
        <td colspan="2"><input name="nombre" type="text" id="nombre2" size="60"></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td><div align="left">
        </div></td>
        <td><input name="txtcuentascg" type="text" id="txtcuentascg2" maxlength="20"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php 

print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["NomEstPro1"]."</td>";
print "<td>".$arr["NomEstPro2"]."</td>";
print "<td>".$arr["NomEstPro3"]."</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "</tr>";
$ls_408    = "408"; 
$ls_codigo = $ls_408.$ls_cod;
if($ls_operacion=="BUSCAR")
{

	$ls_cadena ="SELECT * FROM spg_cuentas ".
		   "WHERE CodEmp = '".$as_codemp."' AND spg_cuenta like '%".$ls_codigo."%' AND denominacion like '%".$ls_denominacion."%' AND sc_cuenta like '%".$ls_codscg."%' AND CodEstPro1 like '%".$ls_estpro1."%' AND CodEstPro2 like '%".$ls_estpro2."%' AND CodEstPro3 like '%".$ls_estpro3."%' ORDER BY spg_cuenta";
//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_cta=$io_sql->select($ls_cadena);

	$data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("spg_cuenta");
		for($z=1;$z<=$totrow;$z++)
		{
			$cuenta=$data["spg_cuenta"][$z];
			$denominacion=$data["denominacion"][$z];
			$codest1=$data["CodEstPro1"][$z];
			$denestpro1=uf_obtener_denominacion("spg_ep1","DenEstPro1"," CodEstPro1='".$codest1."'");
			$codest2=$data["CodEstPro2"][$z];
			$denestpro2=uf_obtener_denominacion("spg_ep2","DenEstPro2"," CodEstPro1='".$codest1."' AND CodEstPro2 ='".$codest2."'");
			$codest3=$data["CodEstPro3"][$z];
			$denestpro3=uf_obtener_denominacion("spg_ep3","DenEstPro3"," CodEstPro1='".$codest1."' AND CodEstPro2 ='".$codest2."' AND CodEstPro3 ='".$codest3."' ");
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
		$io_sql->free_result($rs_cta);
		$io_sql->close();
	}
	else
	{
		print "No se han creado Cuentas de gasto";
	}
}
print "</table>";
function uf_obtener_denominacion($ls_tabla,$ls_campo,$ls_where)
{
global $io_sql;
global $as_codemp;
$ls_denominacion="";
	$ls_cadena ="SELECT * FROM ".$ls_tabla." WHERE CodEmp = '".$as_codemp."' AND ".$ls_where;
//$ls_sql="SELECT SC_cuenta,denominacion FROM SIGESP_Plan_unico ";
	$rs_est=$io_sql->select($ls_cadena);

	$data=$rs_est;
	if($rs_est==false)
	{
		print $this->io_sql->message;
	}
	else
	{
		if($row=$io_sql->fetch_row($rs_est))
		{
			$ls_denominacion=$row[$ls_campo];
			$io_sql->free_result($rs_est);
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
    opener.document.form1.txtctaspg.value=cuenta;
	opener.document.form1.txtdenctaspg.value=deno;
//	opener.document.form1.operacion.value="BUSCAR";
//	opener.document.form1.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_catdinamic_ctasSPG.php";
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
