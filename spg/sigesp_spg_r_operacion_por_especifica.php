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
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SPG";
	$ls_ventanas="sigesp_spg_r_operacion_por_especifica.php";

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
<title>OPERACION POR ESPECIFICA </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script> 
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo3">Sistema de Presupuesto de Gasto</td>
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
	
if	(array_key_exists("txtprvbendes",$_POST))
	{
	  $ls_prvbendes=$_POST["txtprvbendes"];
    }
else
	{
	  $ls_prvbendes="";
	} 
	
if	(array_key_exists("txtprvbenhas",$_POST))
	{
	  $ls_prvbenhas=$_POST["txtprvbenhas"];
    }
else
	{
	  $ls_prvbenhas="";
	} 	
	
if	(array_key_exists("txtmondes",$_POST))
	{
	  $li_mondes=$_POST["txtmondes"];
    }
else
	{
	  $li_mondes="";
	}
	
if	(array_key_exists("txtmonhas",$_POST))
	{
	  $li_monhas=$_POST["txtmonhas"];
    }
else
	{
	  $li_monhas="";
	}	

if	(array_key_exists("txtconcepto",$_POST))
	{
	  $ls_concepto=$_POST["txtconcepto"];
    }
else
	{
	  $ls_concepto="";
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
	  $ls_orden="CP";
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
  <table width="580" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">OPERACION POR ESPECIFICA </td>
    </tr>
  </table>
  <table width="580" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="129"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
      <td align="center"><div align="right"><strong>Reporte en</strong></div></td>
      <td width="144" align="center"><div align="left">
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
      </div></td>
      <td width="301" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">      <div align="left">
        <table width="542" height="39" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5"><strong>Intervalo de Cuentas </strong></td>
            </tr>
          <tr>
            <td width="105" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
            <td width="146"><div align="left">
              <input name="txtcuentades" type="text" id="txtcuentades" value="<?php print $ls_cuentades; ?>" style="text-align:center" readonly="true">
              <a href="javascript:catalogo_cuentas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></div></td>
            <td width="94">
              <div align="right"></div>                <div align="right">Hasta</div></td>
            <td width="128"><input name="txtcuentahas" type="text" id="txtcuentahas" value="<?php print $ls_cuentahas; ?>" style="text-align:center" readonly="true"></td>
            <td width="67"><a href="javascript:catalogo_cuentahas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
          </tr>
        </table>
      </div></td>
    </tr>
    
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="542" height="57" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="5"><strong>Proveedor/Beneficiario </strong></td>
        </tr>
        <tr>
          <td height="22" colspan="2"><label>
                <div align="right">
                <input name="rdprvben" type="radio" value="PRV" selected>
              Proveedor                </div>
          </label></td>
          <td colspan="3"><label>
            <input name="rdprvben" type="radio" value="BEN">
            Beneficiario</label></td>
          </tr>

        <tr>
          <td width="105" height="22"><div align="right"><span class="style1 style14">Desde</span></div></td>
          <td width="156"><div align="left">
              <input name="txtprvbendes" type="text" id="txtprvbendes" value="<?php print $ls_prvbendes; ?>" style="text-align:center">
          <a href="javascript:catalogo_prvben('txtprvbendes');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Proveedores-Beenficiarios"></a></div></td>
          <td width="84"><div align="right">Hasta</div></td>
          <td width="127"><input name="txtprvbenhas" type="text" id="txtprvbenhas" value="<?php print $ls_prvbenhas; ?>" style="text-align:center"></td>
          <td width="68"><a href="javascript:catalogo_prvben('txtprvbenhas');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 1"></a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="7"><strong>Monto de la Operaci&oacute;n </strong></td>
        </tr>
        <tr>
          <td width="54" height="28"><div align="right"></div></td>
          <td width="46" height="28"><div align="right">Desde</div></td>
          <td colspan="2"><div align="left">
              <input name="txtmondes" type="text" id="txtmondes" value="<?php print $li_mondes ; ?>" style="text-align:center" onKeyPress="return(currency_Format(this,'.',',',event))">
          </div></td>
          <td width="51">&nbsp;</td>
          <td width="43"><div align="right">Hasta</div></td>
          <td width="196"><div align="left">
              <input name="txtmonhas" type="text" id="txtmonhas" value="<?php print $li_monhas ; ?>" style="text-align:center" onKeyPress="return(currency_Format(this,'.',',',event))">
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celdanew">
          <td height="13" colspan="2"><strong>Concepto de la Operaci&oacute;n </strong></td>
        </tr>
        <tr>
          <td width="99" height="28"><div align="right">Descripci&oacute;n:</div></td>
          <td width="441" height="28"><div align="right"></div>            <div align="left">
                <input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_concepto; ?>" size="75" maxlength="75" style="text-align:left">
                      </div>            <div align="left"></div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
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
        <table width="542" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="9" class="titulo-celdanew">Estado Presupuestario </td>
          </tr>
          <tr>
            <td width="52" height="22"><div align="right">
                <?php 
			  if($ls_orden=="PC")
			  {
					$ls_precomprometer = "checked";
					$ls_comprometer="";		
					$ls_causar="";
					$ls_pagar="";
			  } 		
			  if(($ls_orden=="CP")||($ls_orden==""))
			  {
					$ls_precomprometer = "";
					$ls_comprometer="checked";		
					$ls_causar="";
					$ls_pagar="";
			  }
			  if($ls_orden=="CS")
			  {
					$ls_precomprometer = "checked";
					$ls_comprometer="";		
					$ls_causar="checked";
					$ls_pagar="";
			  }
			  if($ls_orden=="PG")
			  {
					$ls_precomprometer = "";
					$ls_comprometer="";		
					$ls_causar="";
					$ls_pagar="checked";
			  }
		  ?>
                <input name="rborden" type="radio" value="PC" <?php print $ls_precomprometer ?>>
            </div></td>
            <td width="90">PreComprometido</td>
            <td width="14"><div align="right"></div>
                <div align="left"></div></td>
            <td width="40"><div align="right">
                <input name="rborden" type="radio" value="CP" <?php print $ls_comprometer ?>>
            </div></td>
            <td width="74">Comprometido</td>
            <!-- <td width="31"><div align="right"></div>-->
            <!--     <div align="left"></div></td> -->
            <td width="40"><div align="right">
                <input name="rborden" type="radio" value="CS" <?php print $ls_causar ?>>
            </div></td>
            <td width="17">Causar</td>
            <td width="23">&nbsp;</td>
            <td width="161"><input name="rborden" type="radio" value="PG" <?php print $ls_pagar ?>>
              Pagar</td>
            </tr>
        </table>
      </div>        <div align="left"></div>        <div align="left" class="style14"></div>        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    
    <tr>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
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

function ue_showouput()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	tipoformato=f.cmbbsf.value;
	if(li_imprimir==1)
	{
		tipoprvben="";
		txtcuentades = f.txtcuentades.value;
		txtcuentahas = f.txtcuentahas.value;
		txtfecdes = f.txtfecdes.value;
		txtfechas = f.txtfechas.value;
		ls_cuentades=txtcuentades;
		ls_cuentahas=txtcuentahas;
		ls_prvbendes=f.txtprvbendes.value;
		ls_prvbenhas=f.txtprvbenhas.value;
		ldec_montodes=f.txtmondes.value;
		ldec_montodes=uf_convertir_monto(ldec_montodes);
		ldec_montohas=f.txtmonhas.value;
		ldec_montohas=uf_convertir_monto(ldec_montohas);
		ls_concepto=f.txtconcepto.value;
		if (f.rdprvben[0].checked)
	    {
	     tipoprvben = "P";
	    }
	    else
	    {
	     if (f.rdprvben[1].checked)
	     {
		  tipoprvben = "B"
         }
		}
		for (i=0;i<f.rborden.length;i++)
		{ 
		   if (f.rborden[i].checked) 
			  break; 
		} 
		document.opcion = f.rborden[i].value; 
		orden=document.opcion;
		if(ls_cuentades>ls_cuentahas)
		{
		  alert("Intervalo de cuentas incorrecto...");
		  f.txtcuentades.value="";
		  f.txtcuentahas.value="";
		}
		else
		{
			if((txtfecdes=="")||(txtfechas==""))
			{
			  alert("Por Favor Seleccionar todos los parametros de busqueda");
			}
			else                                                          
			{
				if(ls_prvbendes > ls_prvbenhas)
				{
				 alert("Intervalo de Proveedores o Beneficiarios Incorrecto..");
				 f.txtprvbendes.value="";
		         f.txtprvbenhas.value="";
				}
				else
				{
				 if(parseFloat(ldec_montodes) > parseFloat(ldec_montohas))
				 {
				  alert("Intervalo de Montos Incorrecto..");
				  f.txtmondes.value="";
		          f.txtmonhas.value="";
				 }
				 else
				 {
				  pagina="reportes/sigesp_spg_rpp_operacion_por_especifica.php?txtcuentades="+txtcuentades+"&txtcuentahas="+txtcuentahas
															 +"&txtfecdes="+txtfecdes+"&rborden="+orden+"&txtfechas="+txtfechas
															 +"&tipoformato="+tipoformato+"&txtprvbendes="+ls_prvbendes+"&txtprvbenhas="+ls_prvbenhas
															 +"&tipoprvben="+tipoprvben+"&txtmondes="+ldec_montodes+"&txtmonhas="+ldec_montohas+"&txtconcepto="+ls_concepto;
				  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
				 }
				} 
			}	
		}
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}

function catalogo_cuentas()
{
	f=document.form1;
	pagina="sigesp_cat_ctasrep.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_prvben(ls_destino)
{
	f=document.form1;
	if (f.rdprvben[0].checked)
	{
	 pagina="sigesp_catdinamic_prov.php?destino="+ls_destino;
	}
	else
	{
	 if (f.rdprvben[1].checked)
	 {
	  pagina = "sigesp_catdinamic_bene.php?destino="+ls_destino
	 }
	}
	if ((f.rdprvben[0].checked)||f.rdprvben[1].checked)
	{
	 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
	 alert("Seleccione si es Proveedor o Beneficiario");
	}
}

function catalogo_cuentahas()
{
		f=document.form1;
	    pagina="sigesp_cat_ctasrephas.php";
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

function  uf_format(obj)
{
	ldec_monto=obj.value;
	obj.value=uf_convertir(ldec_monto);
}

function currency_Format(fld, milSep, decSep, e) 
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js" ></script>
</html>