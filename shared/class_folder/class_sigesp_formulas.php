
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Editor de Fórmulas</title>
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
<link href="css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/general.css" rel="stylesheet" type="text/css">
<link href="css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	background-color: #f3f3f3;
}
-->
</style></head>

<body>
<?
include("class_folder\class_mensajes.php");
//require_once("class_mensajes.php");
$io_msg=new class_mensajes();
if(array_key_exists("operacion",$_POST))
	{
	$ls_operacion=$_POST["operacion"];
	$ls_formula="";
	}
	else
	{
	$ls_formula="";
	$ls_operacion="";
	}


if($ls_operacion=="VERIFICAR")
{
	$ls_formula=$_POST["txtformula"];
	$ldec_monto=intval($_POST["txtmonto"]);
	if($ldec_monto>0)
	{
		$ls_form=str_replace('$ld_monto',intval($ldec_monto),$ls_formula);
		if($result=eval("return $ls_form;"))
		{
			$io_msg->message("Fórmula Válida, Resultado=".$result);
		}
		else
		{
			$io_msg->message("Fórmula Invalida");
			$ls_formula="";
		}
	}
	else
	{
		$io_msg->message("Introduzca el monto para verificar la fórmula");

	}
}
?>
<input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion;?>">
<form name="form1" method="post" action="">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Editor de Fórmulas</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="101"><div align="right">Variable Est&aacute;tica</div></td>
        <td width="122"><div align="left">
          <input name="txtvar" type="text" value="$ld_monto" readonly style="text-align:center">
</div></td>
        <td width="160"><a href="javascript: uf_agregar(document.form1.txtvar.value)"><img src="imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a></td>
        <td width="115"><div align="left">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Operadores</div></td>
        <td><div align="left">
          <select name="select3">
            <option value="">Operadores</option>
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
            <option value="(">(</option>
            <option value=")">)</option>
          </select>
</div></td>

        <td><a href="javascript: uf_agregar(document.form1.select3.value)"><img src="imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right">Valor</div></td>
        <td><input name="txtvalor" type="text" id="txtvalor" style="text-align:right"></td>
        <td><a href="javascript: uf_agregar(document.form1.txtvalor.value)"><img src="imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div align="right">F&oacute;rmula</div></td>
        <td colspan="3"><input name="txtformula" type="text" value="<? print $ls_formula;?>" size="70" maxlength="70" readonly>
          <a href="javascript: uf_blanqueo()"><img src="imagebank/tools15/actualizar(1).gif" alt="Blanquear" width="15" height="15" border="0"></a></td>
      </tr>
      <tr>
        <td> <div align="right">Valor de Prueba</div></td>
        <td colspan="3">
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right">
        <a href="javascript: uf_verificar();"><img src="imagebank/tools15/aprobado-off.gif" alt="Verificar" width="15" height="15" border="0"></a><a href="javascript: uf_volver();"><img src="imagebank/tools15/deshacer.gif" alt="Volver" width="15" height="15" border="0"></a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="3"><div align="left">
        </div></td>
      </tr>
    </table>
	<br>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=deno;
	opener.document.form1.operacion.value="BUSCAR";
	opener.document.form1.submit();
	close();
  }
function uf_agregar(parametro)
{
	f=document.form1;
	valor=f.txt
	formula=f.txtformula.value+parametro;
	f.txtformula.value=formula;
}

  function uf_verificar()
  {
  f=document.form1;
  f.operacion.value="VERIFICAR";
  f.action="class_sigesp_formulas.php";
  f.submit();
  }
  function uf_volver()
  {
  f=document.form1;
  formula=f.txtformula.value;
  opener.document.form1.f.txtformula.value=formula;
  close();
  }
  function uf_blanqueo()
  {
    f=document.form1;
	f.txtformula.value="";
  }
</script>
</html>
