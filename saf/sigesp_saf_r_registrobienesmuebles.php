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
$io_fun_activo=new class_funciones_activos("../");
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_r_registrobienesmuebles.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title>Reporte de Registro auxiliar de bienes muebles por responsable y por ubicación</title>
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
.Estilo2 {color: #6699CC}
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Activos Fijos</td>
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
    <!-- <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a><a href="javascript:ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
	$ls_year=date("Y");
	$ld_fecdes="01/01/".$ls_year;
	$ld_fechas=date("d/m/Y");
	$la_emp=$_SESSION["la_empresa"];
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="636" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="543" colspan="2" class="titulo-ventana"> <div align="center"> Registro de Bienes Muebles por Respnsable y por Ubicaci&oacute;n </div></td>
    </tr>
  </table>
  <table width="634" height="374" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="111"></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><p>&nbsp;</p>
        <table width="575" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="139">&nbsp;</td>
          <td width="434">&nbsp;</td>
        </tr>

        <tr>
          <td><div align="right">Catalogo SIGECOF</div></td>
          <td height="22"><input name="txtcoddesde" type="text" id="txtcoddesde" size="21" maxlength="20"  style="text-align:center ">
            <a href="javascript:uf_catalogo_activosigecof('txtcoddesde','txtdendesde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
            <input name="txtdendesde" type="text" class="sin-borde" id="txtdendesde" size="40" readonly>
            <input name="txtcuenta" type="hidden" id="txtcuenta">
            <input name="txtideact2" type="hidden" id="txtideact2"></td>
        </tr>
        <tr>
          <td><div align="right">Unidad Administradora </div></td>
          <td height="22"><a href="javascript: uf_unidad();">
            <input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center " size="13" maxlength="10" >
            </a><a href="javascript:ue_buscarunidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
            <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="50" readonly></td>
        </tr>
        
        <tr>
          <td><div align="right">Responsable Primario </div></td>
          <td height="22"><input name="txtcodresced" type="text" id="txtcodresced" size="13" maxlength="10" readonly>
              <a href="javascript: ue_buscarresponsablecedente();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomresced" type="text" class="sin-borde" id="txtnomresced" size="30" maxlength="120" readonly>
              <input name="txtestced" type="hidden" class="sin-borde" id="txtestced" value="<?php print $ls_testced; ?>" size="20" maxlength="20" readonly></td>
        </tr>
        <tr>
          <td><div align="right">Responsable por Uso </div></td>
          <td height="22"><input name="txtcodresrece" type="text" id="txtcodresrece" size="13" maxlength="10" readonly>
              <a href="javascript: ue_buscarresponsablereceptora();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomresrec" type="text" class="sin-borde" id="txtnomresrec" size="30" maxlength="120" readonly>
              <input name="txtestres" type="hidden" class="sin-borde" id="txtestres" value="<?php print $ls_testrec; ?>" size="20" maxlength="20" readonly></td>
        </tr>
      </table>

        <p>&nbsp;</p>
        <table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><strong> Activos </strong></td>
          </tr>
          <tr>
            <td width="49"><div align="right">Desde</div></td>
            <td width="446" height="22"><div align="left">
                <input name="txtcodactdes" type="text" id="txtcodactdes" size="21" maxlength="20"  style="text-align:center ">
                <a href="javascript:uf_catalogo_activo('txtcodactdes','txtdenactdes');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenactdes" type="text" class="sin-borde" id="txtdenactdes" size="40" readonly>
                <input name="txtseract" type="hidden" id="txtseract">
                <input name="txtideact" type="hidden" id="txtideact">
              </div>
                <div align="left"> </div></td>
          </tr>
          <tr>
            <td height="10"><div align="right"><span class="style1 style14">Hasta</span></div></td>
            <td height="22"><div align="left">
                <input name="txtcodhasta" type="text" id="txtcodhasta" size="21" maxlength="20"  style="text-align:center">
                <a href="javascript:uf_catalogo_activo('txtcodhasta','txtdenhasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenhasta" type="text" class="sin-borde" id="txtdenhasta" size="40" readonly>
              </div>
                <div align="left"> </div></td>
          </tr>
        </table>
        <p>&nbsp;</p></td>
    </tr>
    
    <tr>
      <td height="22" colspan="3" align="center"><table width="578" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><strong>Intervalo de Fechas </strong></td>
          <td width="63">&nbsp;</td>
          <td width="168">&nbsp;</td>
          <td width="81">&nbsp;</td>
        </tr>
        <tr>
          <td width="78"><div align="right">Desde</div></td>
          <td width="82"><input name="txtdesde" type="text" id="txtdesde"  onKeyPress="ue_separadores(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
          <td><div align="right">Hasta</div></td>
          <td><div align="left">
              <input name="txthasta" type="text" id="txthasta"  onKeyPress="ue_separadores(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
          </div></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right" class="style1 style14"></div>        <div align="right" class="style1 style14"></div>        <div align="left">
          <input name="hidunidad" type="hidden" id="hidunidad">
          <input name="hidstatus" type="hidden" id="hidstatus">
        </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
        <table width="577" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          
          <tr>
            <td width="160" height="22"><div align="right"></div>
                <div align="right"></div>
              <div align="right">C&oacute;digo
                <input name="radioorden" type="radio" class="sin-borde" value="radiobutton" checked>
              </div></td>
            <td width="335">&nbsp;</td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n
              <input name="radioorden" type="radio" class="sin-borde" value="radiobutton">
            </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion2"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <div align="center">
          <p></p>
      </div></td>
    </tr>
  </table>
    <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function ue_buscarresponsablecedente()
{
	f=document.form1;
	ls_coduniadmcede=f.txtcoduniadm.value;
	/*ls_estpres=f.hidestpres.value;
	if (ls_coduniadmcede=="")
	{
	 alert ("Debe seleccionar la Unidad Cedente");
	}
	else
	{
	  if (ls_estpres==1)
	  {
	  alert("Esta acta está procesada. No puede ser modificada.");
	  } 
	 else
	  {*/
	  window.open("sigesp_snorh_cat_personal.php?tipo=asignacion2&buscar=0","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	/*  }
	 }*/
}
function ue_buscarresponsablereceptora()
{
	f=document.form1;
	ls_coduniadm=f.txtcoduniadm.value;
	ls_codresced=f.txtcodresced.value; 
/*	ls_estpres=f.hidestpres.value;
	if  ((ls_coduniadmcede=="")||(ls_codresced=="")||(ls_coduniadmrece=="")||(ls_coduniadmrece==""))
	{
	 alert ("Debe seleccionar todos los campos anteriores");
	} 
	else
	{
	  if (ls_estpres==1)
	  {
	  alert("Esta acta está procesada. No puede ser modificada.");
	  }
	 else
	 {*/
	 window.open("sigesp_snorh_cat_personal.php?tipo=asignacion3&buscaresced=buscaresced&ls_codresced="+ls_codresced+"&buscar=0","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   /*  }    
	}*/
}

function uf_catalogo_activosigecof(ls_coddestino,ls_dendestino)
{
	window.open("sigesp_saf_cat_sigecof.php?coddestino="+ls_coddestino +"&dendestino="+ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
}

function uf_catalogo_activo(ls_coddestino,ls_dendestino)
{
	window.open("sigesp_saf_cat_codactivoss.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=120,top=70,location=no,resizable=yes");
}	

function ue_buscarunidad()
{
 f=document.form1;
/* ls_estauto=f.hidestauto.value;
 if (ls_estauto==1)
 {
  alert("Esta autorización esta procesada. No puede ser modificada.");
 }
 else
 {*/
 window.open("sigesp_saf_cat_unidadejecutora.php?destino=cedente","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
 //}
}
function uf_mostrar_reporte()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
	    ls_codsigecof=f.txtcoddesde.value;
	    ls_desigecof=f.txtdendesde.value;
		ls_coduniadm=f.txtcoduniadm.value;
		ls_denuniadm=f.txtdenuniadm.value;
	    ls_codrespri=f.txtcodresced.value; 
	    ls_nomrespri=f.txtnomresced.value;
	    ls_codresuso=f.txtcodresrece.value;
	    ls_nomresuso=f.txtnomresrec.value;
		ls_codactdes=f.txtcodactdes.value;
		ls_denactdes=f.txtdenactdes.value;
		ls_codhasta=f.txtcodhasta.value;
		ls_denhasta=f.txtdenhasta.value;
		ld_desde=f.txtdesde.value;
		ld_hasta=f.txthasta.value;
		if(f.radioorden[0].checked)
		{
			li_orden=0;
		}
		else
		{
			li_orden=1;
		}

		if ((ls_codsigecof!="")&&(ls_codrespri!="")&&(ls_codresuso!="")&&(ld_desde!="")&&(ld_hasta!=""))
		{
		 window.open("reportes/sigesp_saf_rpp_registroauxiliarmuebles.php?codsigecof="+ls_codsigecof+"&desigecof="+ls_desigecof+"&orden="+li_orden+"&desde="+ld_desde+"&hasta="+ld_hasta+"&codactdes="+ls_codactdes+"&denactdes="+ls_denactdes+"&codhasta="+ls_codhasta+"&denhasta="+ls_denhasta+"&codrespri="+ls_codrespri+"&nomrespri="+ls_nomrespri+"&codresuso="+ls_codresuso+"&nomresuso="+ls_nomresuso+"&coduniadm="+ls_coduniadm+"&denuniadm="+ls_denuniadm+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
		 alert("Debe completar los datos.");
		}
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>