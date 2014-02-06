<?php
session_start();
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_datastore.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
$msg=new class_mensajes();
$fun=new class_funciones();
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];
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
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestaria </td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="right"><?php print $arr["nomestpro1"];?></td>
        <td><?php
		if(array_key_exists("operacion",$_POST))
		{
			$ls_operacion=$_POST["operacion"];
			$ls_codigo="%".$_POST["codigo"]."%";
			$ls_denominacion="%".$_POST["nombre"]."%";
			$ls_codscg	= "%".$_POST["txtcuentascg"]."%";
			$ls_estpro1=$_POST["codestpro1"];
			$ls_estpro2=$_POST["codestpro2"];
			$ls_estpro3=$_POST["codestpro3"];			
		}
		else
		{
			$ls_operacion="";
			$ls_estpro1="";
			$ls_estpro2="";
			$ls_estpro3="";
			$ls_codscg="";
			if((array_key_exists("codestpro1",$_GET)))
			{
				$ls_estpro1=$_GET["codestpro1"];
			}
			if(array_key_exists("hicodest2",$_GET))
			{
				$ls_estpro2=$_GET["hicodest2"];
			}
			if(array_key_exists("hicodest3",$_GET))
			{
				$ls_estpro3=$_GET["hicodest3"];
			}
		}
		?>
		  <div align="left"></div>
		  <div align="left">
            <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center "  value="<?php print $ls_estpro1;?>">
   	        </div></td>
        <td width="96" align="right"><?php print $arr["nomestpro2"];?>        </td>
        <td width="62"><div align="left">
          <input name="codestpro2" type="text" id="codestpro23" size="8" maxlength="6" style="text-align:center "  value="<?php print $ls_estpro2;?>">
        </div></td>
        <td width="87" align="right"><?php print $arr["nomestpro3"];?></td>
        <td width="96"><div align="left">
          <input name="codestpro3" type="text" id="codestpro33" size="5" maxlength="3" style="text-align:center "  value="<?php print $ls_estpro3;?>">
        </div></td>
      </tr>
      <tr>
        <td align="right" width="135">Codigo</td>
        <td width="122"><div align="left">
          <input name="codigo" type="text" id="codigo" size="22" maxlength="20">        
        </div></td>
        <td height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" size="72">
<label></label>
<br>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Cuenta Contable </div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" size="22" maxlength="20">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>".$arr["nomestpro1"]."</td>";
print "<td>".$arr["nomestpro2"]."</td>";
print "<td>".$arr["nomestpro3"]."</td>";
print "<td>Denominación</td>";
print "<td>Contable</td>";
print "<td>Disponible</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena ="SELECT *,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible FROM spg_cuentas ".
		   		"WHERE codemp = '".$as_codemp."'".
				" AND spg_cuenta like '".$ls_codigo."'".
				" AND denominacion like '".$ls_denominacion."'".
				" AND sc_cuenta like '".$ls_codscg."'".
				" AND codestpro1 like '%".$ls_estpro1."%'".
				" AND codestpro2 like '%".$ls_estpro2."%'".
				" AND codestpro3 like '%".$ls_estpro3."%'".
				" ORDER BY spg_cuenta";
	$rs_cta=$io_sql->select($ls_cadena);
	if($rs_cta==false)
	{
		$msg->message($fun->uf_convertirmsg($io_sql->message));
	}
	else
	{
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
				$codest1=$data["codestpro1"][$z];
				$codest2=$data["codestpro2"][$z];
				$codest3=$data["codestpro3"][$z];
				$scgcuenta=$data["sc_cuenta"][$z];
				$status=$data["status"][$z];
				$disponible=$data["disponible"][$z];
				if($status=="S")
				{
					print "<tr class=celdas-blancas>";
					print "<td>".$cuenta."</td>";
					print "<td  align=left>".$codest1."</td>";
					print "<td  align=left>".$codest2."</td>";
					print "<td  align=left>".$codest3."</td>";
					print "<td  align=left>".$denominacion."</td>";
					print "<td  align=center>".$scgcuenta."</td>";
					print "<td  align=right width=119>".number_format($disponible,2,",",".")."</td>";
				}
				else
				{
					print "<tr class=celdas-azules>";
					print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion','$scgcuenta','$codest1','$codest2','$codest3','$status');\">".$cuenta."</a></td>";
					print "<td  align=left>".$codest1."</td>";
					print "<td  align=left>".$codest2."</td>";
					print "<td  align=left>".$codest3."</td>";
					print "<td  align=left>".$denominacion."</td>";
					print "<td  align=center>".$scgcuenta."</td>";
					print "<td  align=right>".number_format($disponible,2,",",".")."</td>";				
				}
				print "</tr>";			
			}
			$io_sql->free_result($rs_cta);
			$io_sql->close();
		}
		else
		{
		?>
		<script language="JavaScript">
		alert("No se han creado Cuentas");
		close();
        </script>
		<?php
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

  function aceptar(cuenta,deno,scgcuenta,codest1,codest2,codest3,status)
  {
    opener.document.form1.txtcuenta.value=cuenta;
	opener.document.form1.txtdenominacion.value=deno;
  //	opener.document.form1.submit();
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_saf_cat_ctasspg.php";
	  f.submit();
  }
	function uf_cambio_estprog1()
	{
		f=document.form1;
		f.action="sigesp_saf_cat_ctasspg.php";
		f.operacion.value="est1";
		f.submit();
	}
	function uf_cambio_estprog2()
	{
		f=document.form1;
		f.action="sigesp_saf_cat_ctasspg.php";
		f.operacion.value="est2";
		f.submit();
	}
</script>
</html>
