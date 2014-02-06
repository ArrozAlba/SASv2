<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_ins.php");
	$io_fun_ins=new class_funciones_ins("../");
	$io_fun_ins->uf_load_seguridad("INS","sigesp_ins_p_cambioiva.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cambios de Alicuota del IVA </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Instala</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div>      
    <div align="center"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("class_folder/sigesp_ins_c_cambioiva.php");
	$io_ins=new sigesp_ins_c_cambioiva();
	$ls_operacion=$io_fun_ins->uf_obteneroperacion();
	$li_poraliant=$io_fun_ins->uf_obtenervalor("txtporaliant","");
	$li_pornueali=$io_fun_ins->uf_obtenervalor("txtpornueali","");
	$ls_formula=$io_fun_ins->uf_obtenervalor("txtformula","0,00");
	$ls_status="N";
	switch($ls_operacion)
	{
		case "GUARDAR":
			$lb_valido=$io_ins->uf_guardar($li_poraliant,$li_pornueali,$ls_formula,$la_seguridad);
		break;
	}
	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top"><form name="form1" method="post" action="">
		<?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		$io_fun_ins->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
		unset($io_fun_ins);
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
        ?>
          <p>&nbsp;</p>
          <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3">Cambios de Alicuota del IVA </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td width="463" height="22" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="22"><div align="right" >Alicuota Anterior </div></td>
                <td height="22" colspan="2"><div align="left" >
                    <input name="txtporaliant" type="text" id="txtporaliant" size="3" maxlength="4" style="text-align:center" readonly>
                    %
                    <a href="javascript: ue_catalogo_alicuota();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                    <input name="txtforaliant" type="text" class="sin-borde" id="txtforaliant" readonly>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Nueva Alicuota </div></td>
                <td height="22" colspan="2"><div align="left">
                  <input name="txtpornueali" type="text" id="txtpornueali" size="6" maxlength="5"  onKeyPress="return(currencyFormat(this,'.',',',event))" style="text-align:right" onBlur="javascript: ue_validarporcentaje();" >
                %</div></td>
              </tr>
			  
              <tr class="formato-blanco">
                <td height="22"><div align="right">F&oacute;rmula</div></td>
                <td height="22" colspan="2" align="left"><div align="left">
                  <input name="txtformula" type="text" id="txtformula" size="60" readonly>
                  <input name="btnformula" type="button" class="boton" id="btnformula" onClick="uf_editor()" value="F&oacute;rmula" style="cursor: pointer">
                </div></td>
              </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
            </tr>
          </table>
            <p>&nbsp;</p>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
            <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">
          </p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.status.value;
	if (((lb_status=="C")&&(li_cambiar==1))||((lb_status=="N")&&(li_incluir==1)))
	   {
		ls_aliant=f.txtporaliant.value;
		ls_pornueali=f.txtpornueali.value;
		ls_formula=f.txtformula.value;
		if(confirm("¿Estan procesadas todas las Sep y O.C. hasta la fecha?"))
		{
			if(confirm("¿Esta seguro de ejecutar este proceso?. Despues de ejecutado no existe Reverso"))
			{

				if ((ls_aliant!="")&&(ls_pornueali!="")&&(ls_formula!=""))
				{
					f.operacion.value ="GUARDAR";
					f.action="sigesp_ins_d_cambioiva.php";
					f.submit();
				}
				else
				{
					alert("No ha completado los datos");
				}
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_catalogo_alicuota()
{
	window.open("sigesp_ins_cat_alicuotas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function uf_editor()
{
	f=document.form1; 	
	formula=f.txtformula.value;
	window.open("class_sigesp_formulas.php?txtformula="+formula,"catalogo","menubar=no,toolbar=no,scrollbars=no,width=560,height=270,resizable=yes,location=no");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function ue_validarporcentaje()
{
	f=document.form1;
	ls_porcentaje=eval("f.txtpornueali.value");
	if(parseFloat(ls_porcentaje)>100)
	{
		alert("El porcentaje no debe exeder el 100%.");
		f.txtpornueali.value="";
	}

}
    function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8)  return true; // Enter 
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
