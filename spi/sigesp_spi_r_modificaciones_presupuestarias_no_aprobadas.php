<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
//////////////////////////////////////////////         SEGURIDAD               ///////////////////////////////////////////////////////////////////////
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_ingreso.php");
	$io_fun_ingreso=new class_funciones_ingreso();
	$io_fun_ingreso->uf_load_seguridad("SPI","sigesp_spi_r_modificaciones_presupuestarias_no_aprobadas.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Reporte de Modificaciones Presupuestarias</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
-->
</style>
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
      <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Sistema de Presupuesto de Ingreso</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr> 
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></a><a href="../spi/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a>
													 <img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></td>
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
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}
if(array_key_exists("ckbaum",$_POST))
{
	if($_POST["ckbaum"]==1)
	{
		$ckbaum   = "checked" ;	
		$ls_ckbaum = 1;
	}
	else
	{
		$ls_ckbaum = 0;
		$ckbaum="";
	}
}
else
{
  $ls_ckbaum=0;
  $ckbaum="";
}	

if(array_key_exists("ckbdis",$_POST))
{
	if($_POST["ckbdis"]==1)
	{
		$ckbdis   = "checked" ;	
		$ls_ckbdis = 1;
	}
	else
	{
		$ls_ckbdis = 0;
		$ckbdis="";
	}
}
else
{
  $ls_ckbdis=0;
  $ckbdis="";
}	

if (array_key_exists("txtcomprobante",$_POST))
   {
     $ls_comprobante=$_POST["txtcomprobante"];	   
   }
else
   {
     $ls_comprobante="";
   }
if (array_key_exists("txtprocede",$_POST))
   {
     $ls_procede=$_POST["txtprocede"];	   
   }
else
   {
     $ls_procede="";
   }
   
if (array_key_exists("txtfecha",$_POST))
   {
     $ldt_fecha=$_POST["txtfecha"];	   
   }
else
   {
     $ldt_fecha="";
   }
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
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
   
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		$io_fun_ingreso->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
		unset($io_fun_ingreso);
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
  <table width="540" height="21" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="586" colspan="2" class="titulo-ventana">Modificaciones Presupuestarias No Aprobadas </td>
    </tr>
  </table>
  <table width="540" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="544"></td>
    </tr>
    <tr style="display:none">
      <td colspan="3" align="left">Reporte en
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
           <table width="480" height="59" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
			<tr class="titulo-celdanew">
			<td height="13" colspan="7" valign="top"><strong>Modificaciones Presupuestarias </strong></td>
            </tr>
			<tr>
			  <td height="19"><div align="right">
			    <input name="ckbaum" type="checkbox" id="ckbaum" value="1" checked <?php print $ckbaum ?>>
			  </div></td>
			  <td width="123" height="19"><div align="left">Aumentos</div></td>
			  <td height="19" colspan="2"></td>
			  <td width="61" height="19"><div align="right">
			    <input name="ckbdis" type="checkbox" id="ckbdis" value="1" checked <?php print $ckbdis ?>>
			  </div></td>
			  <td width="169" height="19" colspan="2"><div align="left">Disminuciones</div></td>
		     </tr>
        </table>
      </div>        </td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"><strong><span class="style14">
        <input name="hidcodesp" type="hidden"  id="hidcodesp2" value="<?php print $ls_codigoesp ?>">        
        <input name="hidrango" type="hidden" id="hidrango">
      </span></strong></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left"></div>        <div align="left"></div>        <div align="left" class="style14"></div>        <div align="left">
        <table width="480" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="6"><strong>Intervalos de Fechas </strong></td>
            </tr>
          <tr>
            <td width="97" height="31"><div align="right">Desde</div></td>
            <td width="121" colspan="2"><div align="left">
              <input name="txtfecdes" type="text" id="txtfecdes"  style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fecdes ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
            <td width="20">&nbsp;</td>
            <td width="81"><div align="right">Hasta</div></td>
            <td width="165"><div align="left">
              <input name="txtfechas" type="text" id="txtfechas"  style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fechas ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
          </tr>
        </table>
        </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="480" height="58" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
        <!--DWLayoutTable-->
        <tr class="titulo-celdanew">
          <td height="13" colspan="4" valign="top">Comprobante</td>
        </tr>
        <tr class="formato-blanco">
          <td width="147" height="43"><div align="center">
              <input name="txtcomprobante" type="text" id="txtcompdes2" value="<?php print $ls_comprobante ?>" size="22" maxlength="20" style="text-align:center">
          </div></td>
          <td width="144" height="43"><div align="center">
              <input name="txtprocede" type="text" id="txtcompdes" value="<?php print $ls_procede ?>" size="22" maxlength="20" style="text-align:center">
          </div></td>
          <td width="148"><div align="center">
              <input name="txtfecha" type="text" id="txtfecha" value="<?php print $ldt_fecha ?>" size="22" maxlength="20" style="text-align:center">
          </div></td>
          <td width="39"><div align="left"><a href="javascript:catalogo_comprobante();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr><?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
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

function ue_showouput()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		if(f.ckbaum.checked==true)
		{
		  ckbaum=1;
		}
		else
		{
		 ckbaum=0;
		}
		if(f.ckbdis.checked==true)
		{
		  ckbdis=1;
		}
		else
		{
		 ckbdis=0;
		}
		txtfecdes = f.txtfecdes.value;
		txtfechas = f.txtfechas.value;
		txtfechas = f.txtfechas.value;
		txtcomprobante  = f.txtcomprobante.value;
		txtprocede  = f.txtprocede.value;
		txtfecha = f.txtfecha.value;
		ls_tiporeporte= f.cmbbsf.value;
		if( (txtfecdes=="")||(txtfechas=="")|| ( (ckbaum==0)&&(ckbdis==0) ) ) 
		{
		   alert("Por Favor Seleccionar todos los parametros de busqueda");
		}
		else                                                          
		{
			pagina="reportes/sigesp_spi_rpp_modificaciones_presupuestaria_no_aprobadas.php?ckbaum="+ckbaum+"&txtcomprobante="+txtcomprobante
					+"&txtfecha="+txtfecha+"&txtprocede="+txtprocede+"&ckbdis="+ckbdis+"&txtfecdes="+txtfecdes+"&txtfechas="+txtfechas+"&tiporeporte="+ls_tiporeporte;
			window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}
function catalogo_comprobante()
{
	   tiporep="noapro"; 
	   pagina="sigesp_cat_comprobantes_modificaciones.php?tipocat=rep_ejecucion&tiporep="+tiporep;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
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