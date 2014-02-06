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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_retencion_arc.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","RETENCION_ARC","sigesp_snorh_rpp_retencion_arc.php","C");
	unset($io_sno);		
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
<title >Reporte Relaci&oacute;n de Retenci&oacute;n AR-C</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_nominas.php");
	$io_nomina=new sigesp_snorh_c_nominas();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	$ls_titletable="Nóminas";
	$li_widthtable=600;
	$ls_nametable="grid";
	$lo_title[1]="Código ";
	$lo_title[2]="Descripción";
	$lo_title[3]=" ";
	$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
	$lo_object[0]="";
	$lb_valido=$io_nomina->uf_load_nominas_reportar(&$li_totrows,&$lo_object);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif"  title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_gendisk();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte Relaci&oacute;n de Retenci&oacute;n AR-C </td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
        <tr class="formato-blanco" style="visibility:hidden">
          <td height="20"> <div align="right">Reporte en
            
          </div></td>
          <td height="20"><div align="left">
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td width="168" height="22"><div align="right">A&ntilde;o</div></td>
          <td colspan="3"><div align="left">
            <input name="txtanocurper" type="text" id="txtanocurper" size="7" maxlength="4" readonly style="text-align:center">
            <a href="javascript: ue_buscarano();"><img id="meses" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="23" colspan="4" style="text-align:right"><div align="center">Personal</div></td>
          </tr>
        <tr>
          <td height="23" style="text-align:right"><div align="right">Desde</div></td>
          <td height="23"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="20" maxlength="10" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'); " readonly>
            <a href="javascript:uf_catalogo_personal();"><img src="../../sigesp_bsf/shared/imagebank/tools15/buscar.gif" alt="Buscar Personal..." width="15" height="15" border="0" onClick="document.form1.hidrango.value='D'"></a></div></td>
          <td height="23"><div align="right"><span style="text-align:right">Hasta</span></div></td>
          <td height="23"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" size="20" maxlength="10" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890'); " readonly>
            <a href="javascript:uf_catalogo_personal();"><img src="../../sigesp_bsf/shared/imagebank/tools15/buscar.gif" alt="Buscar Personal..." width="15" height="15" border="0" onClick="document.form1.hidrango.value='H'"></a></div></td>
        </tr>
        <tr>
          <td height="15" colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="4">
		  <div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
			  </div></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4">&nbsp;</td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" ><div align="right">Incluir Concepto de tipo Aporte </div></td>
          <td height="20"><div align="left">
            <input name="chkconceptoaporte" type="checkbox" class="sin-borde" id="chkconceptoaporte" value="1" checked>
          </div></td>
          <td height="20" style="text-align:right">Excluir si no tiene ARC</td>
          <td height="20" ><input name="chkexcluir" type="checkbox" class="sin-borde" id="chkexcluir" value="1" checked></td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22" style="text-align:right">C&oacute;digo del Personal</td>
          <td width="167"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
          <td width="126" style="text-align:right">Apellido del Personal</td>
          <td width="186"><div align="left">            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22" style="text-align:right">Nombre del Personal</td>
          <td>            <div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
          <td style="text-align:right">C&eacute;dula del Personal</td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><div align="right">
            <input name="hidrango" type="hidden" id="hidrango" size="5">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            <input name="operacion" type="hidden" id="operacion">
			<input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
          </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	imprimir=f.imprimir.value;
	if(imprimir==1)
	{	
		codperdes = f.txtcodperdes.value;
  		codperhas = f.txtcodperhas.value;
	    if((codperdes!='' && codperhas=='') || (codperdes=='' && codperhas!='') || codperdes=='' || codperhas=='')
		{
			alert("Por favor complete el Rango de Búsqueda");
		}
	    else
		{
			if (codperhas>=codperdes)
			{
				reporte=f.reporte.value;
				totalfilas=f.totalfilas.value;
				tiporeporte=f.cmbbsf.value;
				parametros="";
				total=0;
				for (li_i=1;li_i<=totalfilas;li_i++)
				{
					marcado=eval("f.chknomsel"+li_i+".checked");
					if (marcado)
					{
						total=total+1;
						nomina=eval("f.chknomsel"+li_i+".value");
						parametros=parametros+"&codnom"+total+"="+nomina;
				    }
			    }
				if (total>0)
				{
					ano=f.txtanocurper.value;
					if(ano!="")
					{
						conceptoaporte="";
						excluir="";
						if(f.chkconceptoaporte.checked)
						{
							conceptoaporte=1;
						}
						if(f.chkexcluir.checked)
						{
							excluir=1;
						}
						if(f.rdborden[0].checked)
						{
							orden="1";
						}
						if(f.rdborden[1].checked)
						{
							orden="2";
						}
						if(f.rdborden[2].checked)
						{
							orden="3";
						}
						if (f.rdborden[3].checked)
					    {
							orden="4";
					    }
						pagina="reportes/"+reporte+"?total="+total+parametros;
						pagina=pagina+"&ano="+ano+"&conceptoaporte="+conceptoaporte+"&excluir="+excluir+"&orden="+orden+"&codperdes="+codperdes+"&codperhas="+codperhas;
						window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
					 }
					 else
					 {
					   alert("Debe seleccionar un año.");
					 }
				}
				else 
				{
					alert("Debe Seleccionar una Nómina.");
				}
			}
			else
			{
			alert("Por Favor Verifique Rango de Códigos de Personal.");
			}
   	    }
	 }
	else
	{
		alert("No tiene permiso para realizar esta operación.");
	}		
}

function ue_buscarano()
{
	f=document.form1;
	codnomdes=eval("f.txtcodnom1.value");
	totalfilas=f.totalfilas.value;
	codnomhas=eval("f.txtcodnom"+totalfilas+".value");
	if((codnomdes!="")&&(codnomhas!=""))
	{
		window.open("sigesp_sno_cat_hano.php?tipo=repretarc&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
   	}
	else
   	{
		alert("Debe seleccionar una nómina desde.");
   	}
}

function uf_catalogo_personal()
{
	f= document.form1;
	anio=f.txtanocurper.value;
	if(anio=='')
	{
		alert("Debe seleccionar un año.");
	}
	else
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=retencionarc","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");	   	 
	}	 
}
</script> 
</html>