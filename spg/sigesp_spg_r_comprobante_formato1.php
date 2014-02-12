<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	require_once("class_funciones_gasto.php");
	$io_fun_spg=new class_funciones_gasto();
	$ls_reporte=$io_fun_spg->uf_select_config("SPG","REPORTE","COMPROBANTE_FORMATO1","sigesp_spg_rpp_comprobante_formato1.php","C");
	//var_dump($io_fun_spg);
	
	//echo $ls_reporte;
	//die();
	
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_r_comprobante_formato1.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title>Listado de Comprobante  Formato1</title>
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
.Estilo2 {font-size: 14px}
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Contabilidad Presupuestaria de Gasto</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr> 
         <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_showouput();"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" border="0" title="Imprimir"></a></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a>
													 <img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></td>
  </tr>
</table>
  <?php
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
if (array_key_exists("txtcompdes",$_POST))
   {
     $ls_compdes=$_POST["txtcompdes"];	   
   }
else
   {
     $ls_compdes="";
   }
if (array_key_exists("txtcomphas",$_POST))
   {
    $ls_comphas=$_POST["txtcomphas"];	   
   }
else
   {
     $ls_comphas="";
   }
if (array_key_exists("txtprocdes",$_POST))
   {
     $ls_procdes=$_POST["txtprocdes"];	   
   }
else
   {
     $ls_procdes="";
   }
   
if (array_key_exists("txtprochas",$_POST))
   {
     $ls_prochas=$_POST["txtprochas"];	   
   }
else
   {
     $ls_prochas="";
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
   
if  (array_key_exists("rborden",$_POST))
	{
	  $ls_orden=$_POST["rborden"];
    }
else
	{
	  $ls_orden="";
	}	
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php 
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if (($ls_permisos)||($ls_logusr=="PSEGIS"))
	{
		print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
		print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
		print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
		print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
		print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
		print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
		print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
		print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
		
	}
	else
	{
		
		print("<script language=JavaScript>");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}
	//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
  <table width="530" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">Listado de Comprobante</td>
    </tr>
  </table>
  <table width="530" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="521"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
      <td colspan="3" align="center"><div align="left"><strong> Reporte en</strong>
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left">
           <table width="480" height="36" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
			<tr class="titulo-celdanew">
			<td height="13" colspan="6" valign="top">Intervalo de Comprobante</td>
            </tr>
		    <tr class="formato-blanco">
		      <td width="66" height="18"><div align="right">Desde</div></td>
		      <td width="125" height="18"><input name="txtcompdes" type="text"  style="text-align:center" id="txtcompdes2" value="<?php print $ls_compdes ?>" size="22" maxlength="20"></td>
		      <td width="40"><div align="left"><a href="javascript:catalogo_compdes();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
		      <td width="86"><div align="right">Hasta</div></td>
		      <td width="125"><input name="txtcomphas" type="text" style="text-align:center" id="txtcomphas" value="<?php print $ls_comphas ?>" size="22" maxlength="20"></td>
		      <td width="36"><a href="javascript:catalogo_comphas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
	         </tr>
        </table>
      </div>        </td>
    </tr>
    <tr>
      <td height="17" colspan="3" align="center"><div align="left"><span class="Estilo2"><span class="Estilo2"></span></span></div></td>
    </tr>
    <tr>
      <td colspan="3" align="center">      <div align="left">
        <table width="480" height="33" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celdanew">
            <td height="13" colspan="6" valign="top">Intervalo de Procede </td>
          </tr>
          <tr class="formato-blanco">
            <td width="60" height="18"><div align="right">Desde</div></td>
            <td width="133" height="18"><input name="txtprocdes" type="text"  style="text-align:center" id="txtprocdes" value="<?php print $ls_procdes ?>" size="22" maxlength="20"></td>
            <td width="40"><a href="javascript:catalogo_procdes();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
            <td width="85"><div align="right">Hasta</div></td>
            <td width="122"><input name="txtprochas" type="text" style="text-align:center" id="txtprochas" value="<?php print $ls_prochas ?>" size="22" maxlength="6"></td>
            <td width="38"><a href="javascript:catalogo_prochas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 2"></a></td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="left"><strong><span class="style14">
        <input name="hidrango" type="hidden" id="hidrango">
        <input name="hidcodesp" type="hidden"  id="hidcodesp" value="<?php print $ls_codigoesp ?>">
</span></strong></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="left"></div>        <div align="left"></div>        <div align="left" class="style14"></div>        <div align="left">
        <table width="480" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="6"><strong>Intervalos de Fechas </strong></td>
            </tr>
          <tr>
            <td width="82" height="28"><div align="right">Desde</div></td>
            <td colspan="2"><div align="left">
              <input name="txtfecdes" type="text" style="text-align:center" id="txtfecdes" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fecdes ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
            <td width="43">&nbsp;</td>
            <td width="42"><div align="right">Hasta</div></td>
            <td width="200"><div align="left">
              <input name="txtfechas" type="text" id="txtfechas" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ldt_fechas ; ?>" size="15" maxlength="15" datepicker="true">
            </div></td>
          </tr>
          <tr class="titulo-celdanew">
            <td height="13" colspan="6" class="titulo-celdanew">Ordenado por </td>
            </tr>
          <tr>
            <td height="22"><div align="right"></div></td>
		  <?php 	 
			  if(($ls_orden=="N")||($ls_orden==""))
			  {
					$ls_nat="checked";		
					$ls_cta="";
			  }
			  elseif($ls_orden=="C")
			  {
					$ls_nat="";		
					$ls_cta="checked";
			  }
		  ?>           
		   <td width="21"><div align="right"></div>              
              
                <div align="left">
                  <input name="rborden" type="radio" value="N" <?php print $ls_nat ?>>
                </div></td>
            <td width="90"><div align="left">Natural</div></td>
            <td>&nbsp;</td>
            <td>
              <div align="right">
                <input name="rborden" type="radio" value="C" <?php print $ls_cta ?>>
</div></td><td><div align="left">Cuenta</div></td>
          </tr>
        </table>
        </div></td>
    </tr>
    <tr><?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total2" value="<?php print $totrow;?>">
  </p>
  <input type="hidden" id="nomreporte" value=<?php echo $ls_reporte; ?>>
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
	nombreporte=document.getElementById('nomreporte').value;
	//alert(nombreporte);
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{
		txtcompdes  = f.txtcompdes.value;
		txtcomphas  = f.txtcomphas.value;
		txtprocdes  = f.txtprocdes.value;
		txtprochas = f.txtprochas.value;
		txtfecdes = f.txtfecdes.value;
		txtfechas = f.txtfechas.value;
		tipoformato=f.cmbbsf.value;
		for (i=0;i<f.rborden.length;i++)
		{ 
		   if (f.rborden[i].checked) 
			  break; 
		} 
		document.opcion = f.rborden[i].value; 
		orden=document.opcion;
		pagina="reportes/"+nombreporte+"?txtcompdes="+txtcompdes
				+"&txtcomphas="+txtcomphas+"&txtprocdes="+txtprocdes+"&txtprochas="+txtprochas
				+"&txtfecdes="+txtfecdes+"&rborden="+orden+"&txtfechas="+txtfechas+"&tipoformato="+tipoformato;
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}

function catalogo_compdes()
{
	   pagina="sigesp_cat_comprobantesspg.php?tipocat=repcompdes";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_comphas()
{
		pagina="sigesp_cat_comprobantesspg.php?tipocat=repcomphas";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_procdes()
{
	   pagina="sigesp_cat_comprobantesspg.php?tipocat=rep_proc_des";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}
function catalogo_prochas()
{
		pagina="sigesp_cat_comprobantesspg.php?tipocat=rep_proc_has";
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
