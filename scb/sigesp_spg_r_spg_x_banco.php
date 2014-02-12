<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="file://///Servidor/WorkSheet/php/nelson/shared/js/disabled_keys.js"></script>
<title>OPERACION POR BANCO</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="file://///Servidor/WorkSheet/php/nelson/yoze/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
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
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 14px}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr> 
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput('<?php print $ls_codemp ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0"></a></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a>
													 <img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
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
}
else
{
	$ls_operacion="";	
}
   
if	(array_key_exists("txtcuentades",$_POST))
	{
	  $ls_cuentades=$_POST["txtcuentades"];
    }
else
	{
	  $ls_cuentades="";
	}   
if	(array_key_exists("txtcuentahas",$_POST))
	{
	  $ls_cuentahas=$_POST["txtcuentahas"];
    }
else
	{
	  $ls_cuentahas="";
	} 
if (array_key_exists("txtfecdes",$_POST)) 
   {
     $ldt_fecdes=$_POST["txtfecdes"];
   }
else
   {
     $ldt_fecdes="01/01/".$li_ano;
   }
if (array_key_exists("txtfechas",$_POST)) 
   {
     $ldt_fechas=$_POST["txtfechas"];
   }
else
   {
     $ldt_fechas=date("d/m/Y");
   }
if	(array_key_exists("txtcuenta",$_POST))
	{
	  $ls_cuenta=$_POST["txtcuenta"];
    }
else
	{
	  $ls_cuenta="";
	}   
if	(array_key_exists("txtcodban",$_POST))
	{
	  $ls_codban=$_POST["txtcodban"];
    }
else
	{
	  $ls_codban="";
	}
if(array_key_exists("ckbfec",$_POST))
{
	if($_POST["ckbfec"]==1)
	{
		$ckbfec   = "checked" ;	
		$ls_ckbfec = 1;
	}
	else
	{
		$ls_ckbfec = 0;
		$ckbfec="";
	}
}
else
{
  $ls_ckbfec=0;
  $ckbfec="";
}	
if(array_key_exists("ckbdoc",$_POST))
{
	if($_POST["ckbdoc"]==1)
	{
		$ckbdoc   = "checked" ;	
		$ls_ckbdoc = 1;
	}
	else
	{
		$ls_ckbdoc = 0;
		$ckbdoc="";
	}
}
else
{
  $ls_ckbdoc=0;
  $ckbdoc="";
}
if(array_key_exists("ckbproc",$_POST))
{
	if($_POST["ckbproc"]==1)
	{
		$ckbproc   = "checked" ;	
		$ls_ckbproc = 1;
	}
	else
	{
		$ls_ckbproc = 0;
		$ckbproc="";
	}
}
else
{
  $ls_ckbproc=0;
  $ckbproc="";
}	
if(array_key_exists("ckbbene",$_POST))
{
	if($_POST["ckbbene"]==1)
	{
		$ckbbene   = "checked" ;	
		$ls_ckbbene = 1;
	}
	else
	{
		$ls_ckbbene = 0;
		$ckbbene="";
	}
}
else
{
  $ls_ckbbene=0;
  $ckbbene="";
}	
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="580" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">OPERACION POR BANCO </td>
    </tr>
  </table>
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="109"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">      <div align="left">
        <table width="540" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5"><strong>Intervalo de Cuentas </strong></td>
            </tr>
          <tr>
            <td width="105" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="146"><div align="left">
              <input name="txtcuentades" type="text" id="txtcuentades" value="<?php print $ls_cuentades; ?>" style="text-align:center">
              <a href="javascript:catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
            <td width="94">
              <div align="right"></div>                <div align="right">Hasta</div></td>
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
      <td colspan="3" align="center"><table width="540" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
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
      <td colspan="3" align="center"><div align="left">
        <table width="540" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5">Bancos</td>
          </tr>
          <tr>
            <td width="72" height="22"><div align="right"><span class="style1 style14">Banco</span></div></td>
            <td width="165"><div align="left">
                <input name="txtcodban" type="text" id="txtcodban" value="<?php print $ls_codban; ?>" style="text-align:center">
            <a href="javascript:catalogo_banco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
            <td width="85"><div align="right"></div>
              <div align="right">Cuenta</div></td>
            <td width="157"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_cuenta; ?>" size="30" maxlength="25"></td>
            <td width="61"><a href="javascript:catalogo_cuenta_banco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          </tr>
        </table>
      </div>        
      <div align="left"></div>        <div align="left" class="style14"></div>        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="13" colspan="3" align="center"><table width="540" height="59" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
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
      <td height="22" colspan="3" align="center"><div align="right"><a href="javascript: ue_showouput('<?php print $ls_codemp ?>');">     <span class="Estilo1">
        <input name="denban"   type="hidden"   id="denban"   value="<?php print $ls_denban;?>">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total2" value="<?php print $totrow;?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

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

function ue_showouput(codemp)
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
			pagina="reportes/sigesp_spg_rpp_operacion_por_banco.php?txtcuentades="+txtcuentades+"&txtcuentahas="+txtcuentahas
					+"&txtfecdes="+txtfecdes+"&txtfechas="+txtfechas+"&txtcodban="+txtcodban+"&txtcuenta="+txtcuenta
					+"&ckbfec="+ckbfec+"&ckbproc="+ckbproc+"&ckbdoc="+ckbdoc+"&ckbbene="+ckbbene;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}	
	}	
}

function catalogo_cuentas()
{
	f=document.form1;
	pagina="sigesp_cat_ctasrep.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_cuentahas()
{
		f=document.form1;
	    pagina="sigesp_cat_ctasrephas.php";
	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
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
	    denban = f.denban.value;
	    pagina="sigesp_cat_ctabanco.php?codigo="+txtcodban+"&denban="+denban;
	    window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
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