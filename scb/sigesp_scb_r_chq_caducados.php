<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_chq_caducados.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}

$ld_feccad=date("d/m/Y");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cheques Caducados</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
 
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="535" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td ></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="4" align="center">Cheques Caducados </td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    
    <tr>
      <td height="13" colspan="4" style="text-align:left">&nbsp;</td>
    </tr>
   
     <tr>
          <td width="93" align="center"><table width="483" border="0" cellpadding="0"  align="center"cellspacing="0" class="formato-blanco">
        <tr>
          <td width="101" style="text-align:right">Banco</td>
          <td width="401"><div align="left">
            <input name="txtcodban" type="text" id="txtcodban" size="8" style="text-align:center" readonly>
              <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Bancos" width="15" height="15" border="0"></a>              
              <input name="txtdenban" type="text" class="sin-borde" id="txtdenban" size="40" style="text-align:left" readonly>
              <input name="txttipocuenta" type="hidden" id="txttipocuenta">
              <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
            <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg"></div></td>
        </tr>
        <tr>
          <td style="text-align:right">Cuenta Bancaria</td>
          <td><div align="left">
            <input name="txtcuenta" type="text" id="txtcuenta" size="30" style="text-align:center" readonly>
            <a href="javascript:cat_ctabanco();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas Bancarias" width="15" height="15" border="0"></a>
            <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="30" style="text-align:left" readonly>
            <input name="txtdisponible" type="hidden" id="txtdisponible">
            <span class="Estilo1">
            <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
            </span></div></td>
        </tr>
		 <tr>
      <td height="13" colspan="4" style="text-align:left">&nbsp;</td>
    </tr>
      </table></td>
        </tr>
     <tr>
      <td height="17" colspan="4" style="text-align:left">&nbsp;</td>
    </tr>
  
    <tr>
      <td height="52" colspan="4" align="center"><table width="483" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="4" align="center"><strong>Intervalo de Emisi&oacute;n de Cheques </strong></td>
        </tr>
		 <tr>
            <td width="75" height="26"><div align="right">Desde</div></td>
                  <td width="132"><select name="cmbmesdes" id="cmbmesdes">
                      <option value="01" selected="selected">Enero</option>
                      <option value="02">Febrero</option>
                      <option value="03">Marzo</option>
                      <option value="04">Abril</option>
                      <option value="05">Mayo</option>
                      <option value="06">Junio</option>
                      <option value="07">Julio</option>
                      <option value="08">Agosto</option>
                      <option value="09">Septiembre</option>
                      <option value="10">Octubre</option>
                      <option value="11">Noviembre</option>
                      <option value="12">Diciembre</option>
            </select></td>
                 
                  <td width="47"><div align="right">Hasta</div></td>
                  <td width="219"><select name="cmbmeshas" id="cmbmeshas">
                      <option value="01" selected="selected">Enero</option>
                      <option value="02">Febrero</option>
                      <option value="03">Marzo</option>
                      <option value="04">Abril</option>
                      <option value="05">Mayo</option>
                      <option value="06">Junio</option>
                      <option value="07">Julio</option>
                      <option value="08">Agosto</option>
                      <option value="09">Septiembre</option>
                      <option value="10">Octubre</option>
                      <option value="11">Noviembre</option>
                      <option value="12">Diciembre</option>
            </select></td>
          </tr>
				<tr class="titulo-celda">
          <td colspan="4" align="center"><strong>Fecha Tope de Caducidad</strong></td>
        </tr>
        <tr>
         <td width="75" height="26"><div align="right">Hasta</div></td>
          <td width="132" align="left"><input name="txtfeccad" type="text" id="txtfecdcad" value="<?php print $ld_feccad;?>" style="text-align:center" datepicker="true" onKeyPress="javascript:currencyDate(this)">              </td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">&nbsp;</td>
    </tr>
  </table>
 
</table>
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_imprimir()
{
  f=document.form1;
  ls_mesdesde    = f.cmbmesdes.value;
  ls_meshasta    = f.cmbmeshas.value;
  ls_codban      = f.txtcodban.value;
  ls_nomban      = f.txtdenban.value;
  ls_ctaban      = f.txtcuenta.value;
  ls_dencta      = f.txtdenominacion.value;
  ld_feccad		 = f.txtfeccad.value;
  
  li_imprimir = f.imprimir.value;		
  if (li_imprimir=='1')
     {
       
	   if ((ls_codban=="")||(ls_ctaban==""))
	   {
	   	alert("Debe seleccionar el Banco y la Cuenta Bancaria!!!");
	   }
	   else
	   {
		   if (ls_mesdesde <= ls_meshasta)
			  {
				
				pagina="reportes/sigesp_scb_rpp_chq_caducados.php?mesdes="+ls_mesdesde+""+"&meshas="+ls_meshasta+"&codban="+ls_codban+"&denban="+ls_nomban+"&ctaban="+ls_ctaban+"&dencta="+ls_dencta+"&feccad="+ld_feccad;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			  }
		   else
			  {	
				alert("Intervalo de Emisión de Cheques Erróneo!!!");	
			  }
		}
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}


 function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
			//alert(ls_long);


  //  return false; 
   }

	function cat_bancos()
	{
		f=document.form1;
		window.open("sigesp_cat_bancos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	
	function cat_ctabanco()
	{
		f=document.form1;
		ls_codban=f.txtcodban.value;
		ls_denban=f.txtdenban.value;
		if((ls_codban!="")&&(ls_denban!=""))
		{
			window.open("sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_denban,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Seleccione el Banco");
		}
	 }


	
	
	
		

  
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
