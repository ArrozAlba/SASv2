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
	$ls_ventanas="sigesp_spg_r_resumen_prov_bene.php";

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
<title>RESUMEN PROVEEDORES/BENEFICIARIO </title>
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
	  $ls_orden="N";
	}
if  (array_key_exists("rbtipo",$_POST))
	{
	  $ls_tipo = $_POST["rbtipo"];
    }
else
	{
	  $ls_tipo = "PC";
	}	
	
if  (array_key_exists("txtcodproben",$_POST))
	{
	  $ls_codproben = $_POST["txtcodproben"];
    }
else
	{
	  $ls_codproben = "";
	}	
if  (array_key_exists("txtnombre",$_POST))
	{
	  $ls_nombre = $_POST["txtnombre"];
    }
else
	{
	  $ls_nombre = "";
	}	
if  (array_key_exists("txtcodprobenhas",$_POST))
	{
	  $ls_codprobenhas = $_POST["txtcodprobenhas"];
    }
else
	{
	  $ls_codprobenhas = "";
	}	
if  (array_key_exists("txtnombrehas",$_POST))
	{
	  $ls_nombrehas = $_POST["txtnombrehas"];
    }
else
	{
	  $ls_nombrehas = "";
	}
if(array_key_exists("ckbimprdet",$_POST))
{
	if($_POST["ckbimprdet"]==1)
	{
		$ckbimprdet   = "checked" ;	
		$ls_ckbimprdet = 1;
	}
	else
	{
		$ls_ckbimprdet = 0;
		$ckbimprdet="";
	}
}
else
{
  $ls_ckbimprdet=0;
  $ckbimprdet="";
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
  <table width="600" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">RESUMEN PROVEEDORES/BENEFICIARIO </td>
    </tr>
  </table>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="129"></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr style="display:none">
      <td align="center"><div align="right"><strong>Reporte en</strong></div></td>
      <td align="center"><div align="left">
          <select name="cmbbsf" id="cmbbsf">
            <option value="0" selected>Bs.</option>
            <option value="1">Bs.F.</option>
          </select>
      </div></td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td width="104" align="center">&nbsp;</td>
      <td width="361" align="center">&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="3" align="center"><table width="580" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="6" class="titulo-celdanew">Intervalo de Proveedores /Beneficiario </td>
          </tr>
          <tr>
            <td width="117" height="21"><div align="right">
                <?php 	
			  if(($ls_tipo=="PC")||($ls_tipo==""))
			  {
					$ls_proveedor="checked";		
					$ls_beneficiario="";
			  }
			  if($ls_orden=="B")
			  {
					$ls_proveedor="";		
					$ls_beneficiario="checked";
			  }
		  ?>
                <input name="rbtipo" type="radio" value="PC" <?php print $ls_proveedor ?>>
            </div></td>
            <td width="62">Proveedor</td>
            <td width="41"><div align="right"></div>
                <div align="left"></div></td>
            <td width="99"><div align="right">
                <input name="rbtipo" type="radio" value="B" <?php print $ls_beneficiario ?>>
            </div></td>
            <td width="70">Beneficiario</td>
            <td width="189">&nbsp;</td>
          </tr>
          <tr>
            <td height="20"><div align="right">Codigo/C&eacute;dula Desde &nbsp;</div></td>
            <td height="20" colspan="5"><div align="left">
                <input name="txtcodproben" type="text" id="txtcodproben" value="<?php print $ls_codproben ?>" style="text-align:center">
              &nbsp;<a href="javascript:catalogo_proveedor();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Proveedores / Beneficiarios" width="15" height="15" border="0"></a>
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nombre ?>" size="50" maxlength="60">
              </div>
                <div align="right"></div></td>
          </tr>
          <tr>
            <td height="20"><div align="right">Codigo/C&eacute;dula Hasta &nbsp;</div></td>
            <td height="20" colspan="5"><div align="left">
                <input name="txtcodprobenhas" type="text" id="txtcodprobenhas" value="<?php print $ls_codprobenhas ?>" style="text-align:center">
              &nbsp;<a href="javascript:catalogo_proveedorhasta();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Cat&aacute;logo Proveedores / Beneficiarios" width="15" height="15" border="0"></a>
              <input name="txtnombrehas" type="text" class="sin-borde" id="txtnombrehas" value="<?php print $ls_nombre ?>" size="50" maxlength="60">
              </div>
                <div align="right"></div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="580" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
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
      <td colspan="3" align="center"><table width="580" height="42" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco"  va>
          <!--DWLayoutTable-->
          <tr class="titulo-celdanew">
            <td height="13" colspan="9" valign="top"><strong>Orden</strong></td>
          </tr>
          <tr>
            <?php 	
			  if(($ls_orden=="N")||($ls_orden==""))
			  {
					$ls_nombre="checked";		
					$ls_codigo="";
			  }
			  if($ls_orden=="C")
			  {
					$ls_nombre="";		
					$ls_codigo="checked";
			  }
		  ?>
            <td height="19" colspan="2"><div align="right">
                <input name="rborden" type="radio" value="N" <?php print $ls_nombre ?>>
            </div></td>
            <td width="49" height="19">Nombre</td>
            <td height="19" colspan="2"><div align="right">
                <input name="rborden" type="radio" value="C" <?php print $ls_codigo ?>>
            </div></td>
            <td width="72" height="19"><div align="left">Codigo</div></td>
            <td width="80"><div align="right">
                <input name="ckbimprdet" type="checkbox" id="ckbimprdet" value="0" <?php print $ckbimprdet ?>>
            </div></td>
            <td width="155" height="19" colspan="2"><div align="left">Imprimir Detalle </div></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <?php
	$arr_emp=$_SESSION["la_empresa"];
	$ls_codemp=$arr_emp["codemp"];
	?>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
          <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
  </table>
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
		txtfecdes = f.txtfecdes.value;
		txtfechas = f.txtfechas.value;
		txtcodproben=f.txtcodproben.value;
		txtnombre=f.txtnombre.value;
		txtcodprobenhas=f.txtcodprobenhas.value;
		txtnombrehas=f.txtnombrehas.value;
		for (i=0;i<f.rbtipo.length;i++)
		{ 
		   if (f.rbtipo[i].checked) 
			  break; 
		} 
		document.opcion = f.rbtipo[i].value; 
		rbtipo=document.opcion;
		for (i=0;i<f.rborden.length;i++)
		{ 
		   if (f.rborden[i].checked) 
			  break; 
		} 
		document.opcion = f.rborden[i].value; 
		rborden=document.opcion;
		if(f.ckbimprdet.checked==true)
		{
		  ckbimprdet=1;
		}
		else
		{
		 ckbimprdet=0;
		}
		if(f.ckbimprdet.checked==true)
		{
			if((txtcodproben=="")&&(txtcodprobenhas==""))
			{
			  alert("Por Favor Seleccionar todos los parametros de busqueda");
			}
			else                                                          
			{
				pagina="reportes/sigesp_spg_rpp_resumen_prov_bene_detalle.php?txtcodproben="+txtcodproben+"&txtnombre="+txtnombre
																   +"&txtfecdes="+txtfecdes+"&rbtipo="+rbtipo+"&rborden="+rborden
																   +"&txtfechas="+txtfechas+"&ckbimprdet="+ckbimprdet
																   +"&txtcodprobenhas="+txtcodprobenhas
																   +"&txtnombrehas="+txtnombrehas+"&tipoformato="+tipoformato;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}
		}
		else
		{
			if((txtcodproben=="")&&(txtcodprobenhas==""))
			{
			  alert("Por Favor Seleccionar todos los parametros de busqueda");
			}
			else                                                          
			{
				pagina="reportes/sigesp_spg_rpp_resumen_prov_bene_listado.php?txtcodproben="+txtcodproben+"&txtnombre="+txtnombre
																   +"&txtfecdes="+txtfecdes+"&rbtipo="+rbtipo+"&rborden="+rborden
																   +"&txtfechas="+txtfechas+"&ckbimprdet="+ckbimprdet
																   +"&txtcodprobenhas="+txtcodprobenhas
																   +"&txtnombrehas="+txtnombrehas+"&tipoformato="+tipoformato;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}
		}	
	}
	else
	{
       alert("No tiene permiso para realizar esta operacion");	
	}	
}

function catalogo_proveedor()
{
	document.form1.operacion.value="";			
	if (document.form1.rbtipo[0].checked)
	   {          	
		 pagina="sigesp_cxp_cat_pro.php";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   } 
	if (document.form1.rbtipo[1].checked)
	   { 		    	
		 pagina="sigesp_cxp_cat_ben.php";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   } 
} 
function catalogo_proveedorhasta()
{
	document.form1.operacion.value="";			
	if (document.form1.rbtipo[0].checked)
	   {          	
		 pagina="sigesp_cxp_cat_prohasta.php";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   } 
	if (document.form1.rbtipo[1].checked)
	   { 		    	
		 pagina="sigesp_cxp_cat_benhasta.php";
		 window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   } 
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