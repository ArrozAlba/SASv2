<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr = $_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_r_spg_x_banco.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Movimientos Presupuestarios por Banco</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  <tr> 
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0" alt="Imprimir" title="Imprimir"></a></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a>
													 <img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
  <?php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();


$la_emp=$_SESSION["la_empresa"];
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
    $ls_cuentades=$_POST["txtcuentades"];
    $ls_cuentahas=$_POST["txtcuentahas"];
	$ldt_fecdes=$_POST["txtfecdes"];
	$ldt_fechas=$_POST["txtfechas"];
	$ls_cuenta=$_POST["txtcuenta"];
	$ls_codban=$_POST["txtcodban"];
	$ls_cuenta=$_POST["txtdenban"];
	$ls_dencta=$_POST["txtdenominacion"];
	if(array_key_exists("ckbfec",$_POST))
	{
		if($_POST["ckbfec"]==1)
		{	$ckbfec   = "checked" ;	$ls_ckbfec = 1;	}
		else
		{	$ls_ckbfec = 0;	   	   $ckbfec="";	}
	}
	else
	{
	  $ls_ckbfec=0;
	  $ckbfec="";
	}	
	if(array_key_exists("ckbdoc",$_POST))
	{
		if($_POST["ckbdoc"]==1)
		{	$ckbdoc   = "checked" ;	$ls_ckbdoc = 1;	}
		else
		{	$ls_ckbdoc = 0;	$ckbdoc="";	}
	}
	else
	{
	  $ls_ckbdoc=0;
	  $ckbdoc="";
	}
	if(array_key_exists("ckbproc",$_POST))
	{
		if($_POST["ckbproc"]==1)
		{	$ckbproc   = "checked" ;	$ls_ckbproc = 1;}
		else
		{	$ls_ckbproc = 0;	$ckbproc="";	}
	}
	else
	{
	  $ls_ckbproc=0;
	  $ckbproc="";
	}	
	if(array_key_exists("ckbbene",$_POST))
	{
		if($_POST["ckbbene"]==1)
		{	$ckbbene   = "checked" ;	$ls_ckbbene = 1;}
		else
		{	$ls_ckbbene = 0;	$ckbbene="";	}
	}
	else
	{
	  $ls_ckbbene=0;
	  $ckbbene="";
	}	
}
else
{
	$ls_operacion="";	
    $ls_cuentades="";
	$ls_cuentahas="";
    $ldt_fecdes="01/01/".$li_ano;
    $ldt_fechas=date("d/m/Y");
	$ls_cuenta="";
	$ls_codban="";
    $ls_denban="";
	$ls_dencta="";
	$ls_ckbfec=0;
	$ckbfec="";
    $ls_ckbdoc=0;
	$ckbdoc="";
	$ls_ckbproc=0;
    $ckbproc="";
	$ls_ckbbene=0;
    $ckbbene="";
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
<table width="613" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="609"></td>
    </tr>
    <tr class="titulo-ventana">
      <td height="22" colspan="3" align="center">Movimientos Presupuestarios por Banco</td>
    </tr>
    <tr style="visibility:hidden">
      <td colspan="3" style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reporte en
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>      </td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="556" height="57" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="2">Bancos</td>
        </tr>
        <tr>
          <td width="72" height="22"><div align="right"><span class="style1 style14">Banco</span></div></td>
          <td><input name="txtcodban" type="text" id="txtcodban" style="text-align:center" value="<?php print $ls_codban; ?>" size="5" maxlength="3">
              <a href="javascript:catalogo_banco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
              <input name="txtdenban"   type="text" class="sin-borde"   id="txtdenban"   value="<?php print $ls_denban;?>" size="45" maxlength="60">          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta</div></td>
          <td><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta; ?>" size="27" maxlength="25">
              <a href="javascript:catalogo_cuenta_banco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a>
              <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencta;?>" size="48" maxlength="254" readonly>
              <input name="txttipocuenta" type="hidden" id="txttipocuenta">
              <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
              <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
              <input name="txtdisponible" type="hidden" id="txtdisponible" style="text-align:right" size="24" readonly></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center">      <div align="left">
        <table width="556" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5"><strong>Intervalo de Cuentas </strong></td>
            </tr>
          <tr>
            <td width="105" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="146"><div align="left">
              <input name="txtcuentades" type="text" id="txtcuentades" value="<?php print $ls_cuentades; ?>" style="text-align:center">
              <a href="javascript:catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
            <td width="94" style="text-align:right">Hasta</td>
            <td width="128"><input name="txtcuentahas" type="text" id="txtcuentahas" value="<?php print $ls_cuentahas; ?>" style="text-align:center"></td>
            <td width="67"><a href="javascript:catalogo_cuentahas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          </tr>
        </table>
      </div></td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="556" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="7"><strong>Intervalos de Fechas </strong></td>
        </tr>
        <tr>
          <td width="54" height="28"><div align="right"></div></td>
          <td width="53" height="28"><div align="right">Desde</div></td>
          <td colspan="2"><div align="left">
              <input name="txtfecdes" type="text" id="txtfecdes" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fecdes ; ?>" size="15" maxlength="15" datepicker="true" style="text-align:center">
          </div></td>
          <td width="51">&nbsp;</td>
          <td width="47"><div align="right">Hasta</div></td>
          <td width="192"><div align="left">
              <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fechas ; ?>" size="15" maxlength="15" datepicker="true" style="text-align:center">
          </div></td>
        </tr>
        

      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center"><table width="556" height="59" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
        <!--DWLayoutTable-->
        <tr class="titulo-celdanew">
          <td height="13" colspan="8" valign="top"><strong>Orden</strong></td>
        </tr>
        <tr>
          <td height="19" colspan="2"><div align="right">
              <input name="ckbfec" type="checkbox" id="ckbfec" value="0" <?php print $ckbfec ?>>
          </div></td>
          <td width="140" height="19"><div align="left">Fechas</div></td>
          <td height="19" colspan="2"></td>
          <td width="90" height="19"><div align="right">
              <input name="ckbdoc" type="checkbox" id="ckbdoc" value="0" <?php print $ckbdoc ?>>
          </div></td>
          <td width="214" height="19" colspan="2"><div align="left">Documento</div></td>
        </tr>
        <tr class="formato-blanco">
          <td height="22" colspan="2"><div align="right">
              <input name="ckbproc" type="checkbox" id="ckbproc" value="0" <?php print $ckbproc ?>>
          </div></td>
          <td height="22"><div align="left">Procede</div></td>
          <td height="22" colspan="2"></td>
          <td height="22"><div align="right">
              <input name="ckbbene" type="checkbox" id="ckbbene" value="0" <?php print $ckbbene ?>>
          </div></td>
          <td height="22" colspan="2"><div align="left">Beneficiario </div></td>
        </tr>
      </table></td>
    </tr>
    <tr><?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td height="22" colspan="3" align="center"><div align="right">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
     </div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">&nbsp;</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_imprimir()
{
	f=document.form1;
	txtcuentades = f.txtcuentades.value;
	txtcuentahas = f.txtcuentahas.value;
	txtfecdes = f.txtfecdes.value;
	txtfechas = f.txtfechas.value;
	ls_cuentades=txtcuentades;
	ls_cuentahas=txtcuentahas;
	txtcodban = f.txtcodban.value;
	txtcuenta = f.txtcuenta.value;
	txtdenban = f.txtdenban.value;
	txtdencta = f.txtdenominacion.value;
	ls_tiporeporte = f.cmbbsf.value;
	if(f.ckbfec.checked==true)
	{
	  ckbfec=1;
	}
	else
	{
	 ckbfec=0;
	}
	if(f.ckbproc.checked==true)
	{
	  ckbproc=1;
	}
	else
	{
	 ckbproc=0;
	}
	if(f.ckbdoc.checked==true)
	{
	  ckbdoc=1;
	}
	else
	{
	 ckbdoc=0;
	}
	if(f.ckbbene.checked==true)
	{
	  ckbbene=1;
	}
	else
	{
	 ckbbene=0;
	}
	if(ls_cuentades>ls_cuentahas)
	{
	  alert("Intervalo de cuentas incorrecto...");
	  f.txtcuentades.value="";
	  f.txtcuentahas.value="";
	}
	else
	{
		if((txtfecdes=="")||(txtfechas=="")||(txtcodban=="")||(txtcuenta==""))
		{
		  alert("Por Favor Seleccionar todos los parametros de busqueda");
		}
		else                                                          
		{
			li_imprimir = f.imprimir.value;
			if (li_imprimir=='1')
			   {
				 pagina="reportes/sigesp_scb_rpp_spg_x_banco.php?txtcuentades="+txtcuentades+"&txtcuentahas="+txtcuentahas
						 +"&txtfecdes="+txtfecdes+"&txtfechas="+txtfechas+"&txtcodban="+txtcodban+"&txtcuenta="+txtcuenta+"&txtdenban="+txtdenban+"&txtdencta="+txtdencta
						 +"&ckbfec="+ckbfec+"&ckbproc="+ckbproc+"&ckbdoc="+ckbdoc+"&ckbbene="+ckbbene+"&tiporeporte="+ls_tiporeporte;
				 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			   }
			else
			   {
	             alert("No tiene permiso para realizar esta operación !!!");
			   }
		}	
	}	
}

function catalogo_cuentas()
{
	f=document.form1;
	pagina="sigesp_cat_ctasrep.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,resizable=yes,location=no");
}

function catalogo_cuentahas()
{
		f=document.form1;
	    pagina="sigesp_cat_ctasrephas.php";
	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,resizable=yes,location=no");
}

function catalogo_banco()
{
		f=document.form1;
	    pagina="sigesp_cat_bancos.php";
	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_cuenta_banco()
{
		f=document.form1;
	    txtcodban = f.txtcodban.value;
	    denban = f.txtdenban.value;
	    pagina="sigesp_cat_ctabanco.php?codigo="+txtcodban+"&hidnomban="+denban;
	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,resizable=yes,location=no");
}
//--------------------------------------------------------
//	Función que le da formato a la fecha
//--------------------------------------------------------
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_formatofecha(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++)
		{
			val2 += val[r]	
		}
		if(nums)
		{
			for(z=0;z<val2.length;z++)
			{
				if(isNaN(val2.charAt(z)))
				{
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++)
		{
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++)
		{
			if(q ==0)
			{
				val = val3[q]
			}
			else
			{
				if(val3[q] != "")
				{
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>