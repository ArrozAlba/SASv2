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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_chq_custodia_entregados.php",$ls_permisos,$la_seguridad,$la_permisos);
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cheques en Custodia o Entregados</title>
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
  <?php
require_once("../shared/class_folder/grid_param.php");
$io_grid=new grid_param();
require_once("../shared/class_folder/sigesp_include.php");
$sig_inc=new sigesp_include();
$con=$sig_inc->uf_conectar();
$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_tipo=$_POST["rb_provbene"];
	$ld_fecdesde=$_POST["txtfecdesde"];
	$ld_fechasta=$_POST["txtfechasta"];
	$ls_report=$_POST["rb"];
	
}
else
{
	$ls_operacion="";	
	$ls_tipo="-";
	$ls_report='G';
	$ld_fecdesde="";
	$ld_fechasta="";
}

if($ls_tipo=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipo=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipo=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}
	if($ls_report=='G')	
	{
		$lb_chkG="checked";
		$lb_chkE="";
	}
	else
	{
		$lb_chkG="";
		$lb_chkE="checked";
	}

?>
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
      <td height="22" colspan="4" align="center">Cheques en Custodia o Entregados </td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr style="visibility:hidden">
      <td height="13" colspan="4" style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reporte en
	      <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>      </td>
    </tr>
    <tr>
      <td height="13" colspan="4" style="text-align:left">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><table width="483" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="224"><div align="right">
                <input name="rb" type="radio" class="sin-borde" onClick="javascript:uf_verificar();" value="C" checked <?php print $lb_chkG?>>
            En custodia          </div></td>
          <td width="257"><div align="left">
                <input name="rb" type="radio" class="sin-borde" value="E" onClick="javascript:uf_verificar();" <?php print $lb_chkE?>>
            Entregados</div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="68" colspan="4" align="center"><table width="483" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="93" style="text-align:right">Banco</td>
          <td width="388"><div align="left">
            <input name="txtcodban" type="text" id="txtcodban" size="8" style="text-align:center" readonly>
              <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Bancos" width="15" height="15" border="0"></a>              
              <input name="txtdenban" type="text" class="sin-borde" id="txtdenban" size="40" style="text-align:left" readonly>
              <input name="txttipocuenta" type="hidden" id="txttipocuenta">
              <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
              <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg"></td>
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
      </table></td>
    </tr>
    <tr>
      <td height="68" colspan="4" align="center"><table width="483" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="4" align="center"><strong>Proveedor / Beneficiario </strong></td>
        </tr>
        <tr>
          <td height="22" colspan="4" align="right"><table width="249" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="353"><label>
                  <input type="radio" name="rb_provbene" id="rb_provbene" value="P" class="sin-borde" style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(document.form1.tipo);" <?php print $rb_p;?>>
            Proveedor</label>
                    <label>
                    <input type="radio" name="rb_provbene" id="rb_provbene" value="B" class="sin-borde"   style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(document.form1.tipo);" <?php print $rb_b;?>>
            Beneficiario</label>
                    <label>
                    <input name="rb_provbene" type="radio"  class="sin-borde" id="rb_provbene" style="width:10 ; height:10" onClick="javascript:uf_verificar_provbene(document.form1.tipo);" value="-" checked <?php print $rb_n;?>>
            Ninguno</label>
                    <input name="tipo" type="hidden" id="tipo"></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td width="57" height="22" align="right">Desde</td>
          <td width="177" align="left"><input name="txtprovbendesde" type="text" id="txtprovbendesde" style="text-align:center">
              <a href="javascript:cat_desde()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></td>
          <td width="50" align="right">Hasta</td>
          <td width="189" align="left"><input name="txtprovbenhasta" type="text" id="txtprovbenhasta" style="text-align:center">
            <a href="javascript:cat_hasta()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="52" colspan="4" align="center"><table width="483" border="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="4" align="center"><strong>Intervalo de Fechas </strong></td>
        </tr>
        <tr>
          <td width="56" height="22" align="right">Desde</td>
          <td width="177" align="left"><input name="txtfecdesde" type="text" id="txtfecdesde" value="<?php print $ld_fecdesde;?>" style="text-align:center" datepicker="true" onKeyPress="javascript:currencyDate(this)">              </td>
          <td width="51" align="right">Hasta</td>
          <td width="189" align="left"><input name="txtfechasta" type="text" id="txtfechasta" value="<?php print $ld_fechasta;?>" style="text-align:center" datepicker="true" onKeyPress="javascript:currencyDate(this)">              </td>
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
  ld_fecdesde    = f.txtfecdesde.value;
  ld_fechasta    = f.txtfechasta.value;
  ls_codban      = f.txtcodban.value;
  ls_nomban      = f.txtdenban.value;
  ls_ctaban      = f.txtcuenta.value;
  ls_dencta      = f.txtdenominacion.value;
  ls_probendesde = f.txtprovbendesde.value;
  ls_probenhasta = f.txtprovbenhasta.value;
  ls_tiporeporte = f.cmbbsf.value;
  if(f.rb_provbene[0].checked)
  {
  	ls_tipproben='P';
  }
  if(f.rb_provbene[1].checked)
  {
  	ls_tipproben='B';
  }
  if(f.rb_provbene[2].checked)
  {
  	ls_tipproben='-';
  }
  if(f.rb[0].checked==true)
  {
  	ls_tiprep='C';
  }
  else
  {
  	ls_tiprep='E';
  }
  li_imprimir = f.imprimir.value;		
  if (li_imprimir=='1')
     {
       if (ld_fecdesde!="" && ld_fechasta!="")
          {
 	        pagina="reportes/sigesp_scb_rpp_chq_custodia_entregados.php?fecdes="+ld_fecdesde+"&fechas="+ld_fechasta+"&tiprep="+ls_tiprep+"&codban="+ls_codban+"&denban="+ls_nomban+"&ctaban="+ls_ctaban+"&dencta="+ls_dencta
			       +"&probendesde="+ls_probendesde+"&probenhasta="+ls_probenhasta+"&tipo_destino="+ls_tipproben+"&tiporeporte="+ls_tiporeporte;
 	               window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
          }
       else
		  {	
			alert("Seleccione el rango de fechas !!!");	
		  }
	 }
  else
     {
	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}


function rellenar_cad(cadena,longitud,objeto)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	if (cadena!="")
	   {
		for (i=1;i<=total;i++)
			{
			  cadena_ceros=cadena_ceros+"0";
			}
		cadena=cadena_ceros+cadena;
		if (objeto=="txtcodprov1")
		   {
			 document.form1.txtcodprov1.value=cadena;
		   }
		 else
		   {
			 document.form1.txtcodprov2.value=cadena;
		   }  
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

//Catalogo de cuentas contables
	function cat_desde()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked)
		{
			window.open("sigesp_cat_prov_general.php?obj=txtprovbendesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked)
		{
			window.open("sigesp_cat_bene_general.php?obj=txtprovbendesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}
	function cat_hasta()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked)
		{
			window.open("sigesp_cat_prov_general.php?obj=txtprovbenhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
		else if(f.rb_provbene[1].checked)
		{
			window.open("sigesp_cat_bene_general.php?obj=txtprovbenhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes,dependent=yes");
		}
	}
	
	function uf_verificar_provbene(obj)
	{
		f=document.form1;
		if((f.rb_provbene[0].checked)&&(obj.value!='P'))
		{
			f.tipo.value='P';
			f.txtprovbendesde.value="";
			f.txtprovbenhasta.value="";	
		}
		if((f.rb_provbene[1].checked)&&(obj.value!='B'))
		{
			f.tipo.value='B';			
			f.txtprovbendesde.value="";
			f.txtprovbenhasta.value="";	
		}
		if((f.rb_provbene[2].checked)&&(obj.value!='N'))
		{
			f.tipo.value='N';			
			f.txtprovbendesde.value="";
			f.txtprovbenhasta.value="";	
		}
	}
	
	function uf_verificar()
	{
		f=document.form1;
		if(f.rb[0].checked)
		{
			
			
		}
		else
		{
			
		}
		
		
	}
   function uf_format(obj)
   {
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
   }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
