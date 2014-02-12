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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_r_depmensual.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte de Depreciaci&oacute;n Mensual</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40" class="cd-logo"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
  </tr>
</table>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="111"></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center" class="titulo-ventana">Reporte de Depreciaci&oacute;n Mensual</td>
    </tr>
    <tr>
      <td height="48" colspan="3" align="center"><table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr style="visibility:hidden">
          <td><strong>Reporte en</strong>
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="201"><div align="right"><strong>Mes</strong>
            <select name="cmbmes" id="cmbmes">
              <option value="Enero" selected>Enero</option>
              <option value="Febrero">Febrero</option>
              <option value="Marzo">Marzo</option>
              <option value="Abril">Abril</option>
              <option value="Mayo">Mayo</option>
              <option value="Junio">Junio</option>
              <option value="Julio">Julio</option>
              <option value="Agosto">Agosto</option>
              <option value="Septiempre">Septiempre</option>
              <option value="Octubre">Octubre</option>
              <option value="Noviembre">Noviembre</option>
              <option value="Diciembre">Diciembre</option>
              </select>
          </div></td>
          <td width="103"><div align="right"><strong>A&ntilde;o</strong>
            <select name="cmbanio" id="cmbanio">
			<?php 
				$ls_year=date("Y");
				for($i=1980;$i<2020;$i++)
				{
					if($i!=$ls_year)
					{print"<option value=".$i.">".$i."</option>";}
					else
					{print"<option value=".$i." selected>".$i."</option>";}
				}
			?>
              </select>
          </div></td>
          <td width="196">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">
          <input name="hidunidad" type="hidden" id="hidunidad">
          <input name="hidstatus" type="hidden" id="hidstatus">
          <div align="right"></div>
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
        <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="175"><div align="center"><strong>Activo</strong></div></td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">C&oacute;digo
                    <input name="radioordenact" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="333" height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n
                    <input name="radioordenact" type="radio" class="sin-borde" value="radiobutton">
              </div></td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p></p>
      </div></td>
    </tr>
  </table>
  <p align="center">&nbsp;</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_mostrar_reporte()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		ls_mes= f.cmbmes.value;
		ls_anio= f.cmbanio.value;
		tipoformato = f.cmbbsf.value;
		if(f.radioordenact[0].checked)
		{
			li_ordenact=0;
		}
		else
		{
			li_ordenact=1;
		}
		window.open("reportes/sigesp_saf_rpp_depmensual.php?ordenact="+li_ordenact+"&mes="+ls_mes+"&anio="+ls_anio+"&tipoformato="+tipoformato,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	}
	else
	{alert("No tiene permiso para realizar esta operación");}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que obtiene el valor de el radio button
//--------------------------------------------------------
function actualizaValor(oRad)
{ 
	var i 
	f=document.form1;
	for (i=0;i<f.radiostatus.length;i++)
	{ 
	   if (f.radiostatus[i].checked) 
		  break; 
	} 
	valor= i;
	f.hidradio.value=i;
} 
//--------------------------------------------------------
//	Función que da formato a la fecha colocando los separadores (/).
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}

//--------------------------------------------------------
//	Función que valida que un intervalo de tiempo sea valido
//--------------------------------------------------------
function ue_comparar_intervalo()
{ 

	f=document.form1;
	ld_desde="f.txtdesde";
	ld_hasta="f.txthasta";
	var valido = false; 
	var diad = f.txtdesde.value.substr(0, 2); 
	var mesd = f.txtdesde.value.substr(3, 2); 
	var anod = f.txtdesde.value.substr(6, 4); 
	var diah = f.txthasta.value.substr(0, 2); 
	var mesh = f.txthasta.value.substr(3, 2); 
	var anoh = f.txthasta.value.substr(6, 4); 
	
	if (anod < anoh)
	{
		 valido = true; 
	 }
	else 
	{ 
	 if (anod == anoh)
	 { 
	  if (mesd < mesh)
	  {
	   valido = true; 
	  }
	  else 
	  { 
	   if (mesd == mesh)
	   {
		if (diad <= diah)
		{
		 valido = true; 
		}
	   }
	  } 
	 } 
	} 
	if (valido==false)
	{
		alert("El rango de fecha es invalido");
		f.txtdesde.value="";
		f.txthasta.value="";
		
	} 
	return valido;
} 
</script>
</html>