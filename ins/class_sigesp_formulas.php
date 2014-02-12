<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Editor de F&oacute;rmulas Usando</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="javascript" src="../shared/js/number_format.js"></script>
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
<?php

require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/evaluate_formula.php");
$io_msg     = new class_mensajes();
$io_formula = new evaluate_formula();
$lb_formulavalida=false; 	
if(array_key_exists("operacion",$_POST))
	{
	  $ls_operacion=$_POST["operacion"];
	  $ls_formula="";
	}
	else
	{
	  $ls_formula   = $_GET["txtformula"];
	  $ls_operacion = "";
	}

if ($ls_operacion=="VERIFICAR")
   {
	 $ls_formula = $_POST["txtformula"];
	 $ldec_monto = $_POST["txtmonto"];
	 $ldec_monto = str_replace('.','',$ldec_monto);
	 $ldec_monto = str_replace(',','.',$ldec_monto);
     $lb_valido  = false;
	 $ld_monto   = $io_formula->uf_evaluar($ls_formula,$ldec_monto,$lb_valido);
	 if ($lb_valido)
	    {
	      $io_msg->message("Fórmula Válida. Total Monto de Prueba =".number_format($ld_monto,2,',','.'));
		  $lb_formulavalida=true; 	
		}
	 else
	    {
	      $io_msg->message("Fórmula Invalida !!!"); 	
		  $ld_monto = 0;
		  $lb_formulavalida=false; 	
		}
	 $ld_monto   = number_format($ld_monto,2,',','.');	 
   }
?>
<form name="form1" method="post" action="">
  <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">
  <br>
	 <table width="539" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="4">Editor de F&oacute;rmulas</td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><input name="hiddeducible" type="hidden" id="hiddeducible">
        <input name="formulavalida" type="hidden" id="formulavalida" value="<?php print $lb_formulavalida; ?>"></td>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="103" height="22"><div align="right">Variable Est&aacute;tica</div></td>
        <td width="129" height="22"><div align="left">
          <input name="txtvar" type="text" value="$LD_MONTO" readonly style="text-align:center">
</div></td>
        <td width="220" height="22"><a href="javascript: uf_agregar(document.form1.txtvar.value)"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a></td>
        <td width="85" height="22"><div align="left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Operadores</div></td>
        <td height="22"><div align="left">
          <select name="select3">
            <option value="">Operadores</option>
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
            <option value="(">(</option>
            <option value=")">)</option>
            <option value="IIF">IIF</option>
          </select>
</div></td>

        <td height="22"><a href="javascript: uf_agregar(document.form1.select3.value)"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Valor</div></td>
        <td height="22"><input name="txtvalor" type="text" id="txtvalor" style="text-align:right"></td>
        <td height="22"><a href="javascript: uf_agregar(document.form1.txtvalor.value)"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" valign="top"><div align="right">F&oacute;rmula</div></td>
        <td height="22" colspan="3"><input name="txtformula" type="txtformula" value="<?php print $ls_formula;?>" size="70" maxlength="70" onChange="javascript:this.value=this.value.toUpperCase();">
          <a href="javascript: uf_blanqueo()"><img src="../shared/imagebank/tools15/actualizar.gif" alt="Blanquear" width="15" height="15" border="0"></a></td>
      </tr>
      <tr>
        <td height="22"> <div align="right">Valor de Prueba</div></td>
        <td height="22" colspan="3"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(ue_formatonumero(this,'.',',',event));"></td>
      </tr>
      <tr>
        <td height="22" colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="4"><div align="center">
          <table width="306" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="25"><div align="right"></div></td>
              <td width="135"><a href="javascript: uf_verificar();"><img src="../shared/imagebank/tools20/aprobado-off.gif" alt="Verificar" width="20" height="20" border="0">Probar Fórmula</a></td>
              <td width="119"><a href="javascript: uf_volver();"><img src="../shared/imagebank/tools20/deshacer.gif" alt="Volver" width="20" height="20" border="0">Retornar Fórmula</a></td>
              <td width="25">&nbsp;</td>
            </tr>
          </table>
        </div></td>
       </tr>
      <tr>
        <td height="22" colspan="4">&nbsp;</td>
      </tr>
  </table>
</form>
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
  f               = document.form1;
  ls_formula      = f.txtformula.value;
  ld_monto_prueba = f.txtmonto.value;
  if ((ld_monto_prueba!="")&&(ls_formula!=""))
     {
	   f.operacion.value="VERIFICAR";
       f.action="class_sigesp_formulas.php";
       f.submit(); 
	 }
  else
     {
	   alert("Debe establecer formula y monto para realizar la prueba !!!"); 
	 }
  }
  
function uf_volver()
{
	f=document.form1;
	formula=f.txtformula.value;
	formulavalida=f.formulavalida.value
	if(formulavalida==true)
	{
		opener.document.form1.txtformula.value=formula;
		close();
	}
	else
	{
		alert("Debe probar la formula y ser una formula valida");
	}
}
  function uf_blanqueo()
  {
    f=document.form1;
	f.txtformula.value="";
  }
  
//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}  
</script>
</html>
