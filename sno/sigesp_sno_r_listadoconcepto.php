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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_listadoconcepto.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$ls_subnom=$_SESSION["la_nomina"]["subnom"];
	$ls_reporte=$io_sno->uf_select_config("SNO","REPORTE","LISTADO_CONCEPTO","sigesp_sno_rpp_listadoconcepto.php","C");
	unset($io_sno);
	///////////////// PAGINACION   /////////////////////
	$ls_codperdes="";
	$ls_codperhas="";
	$ls_codconcdes=$io_fun_nomina->uf_obtenervalor("txtcodconcdes","");
	$ls_codconchas=$io_fun_nomina->uf_obtenervalor("txtcodconchas","");
	$ls_codente=$io_fun_nomina->uf_obtenervalor("txtcodente","");
	$ls_operacion=$io_fun_nomina->uf_obtenervalor("operacion","");
	if ($ls_operacion=="")
	{
		$ls_operacion=$io_fun_nomina->uf_obtenervalor_get("operacion","");
		if ($ls_codconcdes=='')
		{
			$ls_codconcdes=$io_fun_nomina->uf_obtenervalor_get("codconcdes","");
		}
		if ($ls_codconchas=='')
		{
			$ls_codconchas=$io_fun_nomina->uf_obtenervalor_get("codconchas","");
		}
		if ($ls_codente=='')
		{
			$ls_codente=$io_fun_nomina->uf_obtenervalor_get("codente","");
		}
	}
	switch ($ls_operacion)
	{
		case "VERIFICAR_RANGO":
			$li_registros = 20000;
		break;
	
		default:
			$li_registros = 6000;
		break;
	}
	$li_pagina=$io_fun_nomina->uf_obtenervalor_get("pagina",0); 
	if (!$li_pagina)
	{ 
		$li_inicio = 0; 
		$li_pagina = 1; 
	} 
	else 
	{ 
		$li_inicio = ($li_pagina - 1) * $li_registros; 
	} 
	$li_totpag=0;
	require_once("sigesp_sno_c_pagonomina.php");
	$io_pagonomina=new sigesp_sno_c_pagonomina();
	$ls_valor=0;	
	$io_pagonomina->uf_buscar_personal($ls_codnom,$ls_peractnom,$li_inicio,$li_registros,$ls_codconcdes,$ls_codconchas,$ls_valor,$li_totpag,$ls_codperdes,$ls_codperhas,$ls_codente);	
	if ($ls_valor<$li_registros)
	{
	  $ls_codperhas="";
	  $ls_codperdes="";
	}
	unset($io_pagonomina);
    ///////////////// PAGINACION   /////////////////////
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
<title >Reporte Listado de Conceptos</title>
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
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
		<tr>
			<?php
			if ($ls_valor>$li_registros)
			{		
				print "<center>";
				if(($li_pagina - 1) > 0) 
				{
					print "<a href='sigesp_sno_r_listadoconcepto.php?pagina=".($li_pagina-1)."&codconcdes=".$ls_codconcdes."&codconchas=".$ls_codconchas."&codente=".$ls_codente."&operacion=".$ls_operacion."'>< Anterior</a> ";
				}
				for ($li_i=1; $li_i<=$li_totpag; $li_i++)
				{ 
					if ($li_pagina == $li_i) 
					{
						print "<b>".$li_pagina."</b> "; 
					}
					else
					{
						print "<a href='sigesp_sno_r_listadoconcepto.php?pagina=".($li_i)."&codconcdes=".$ls_codconcdes."&codconchas=".$ls_codconchas."&codente=".$ls_codente."&operacion=".$ls_operacion."'>$li_i</a> "; 
					}
				}
				if(($li_pagina + 1)<=$li_totpag) 
				{
					print " <a href='sigesp_sno_r_listadoconcepto.php?pagina=".($li_pagina+1)."&codconcdes=".$ls_codconcdes."&codconchas=".$ls_codconchas."&codente=".$ls_codente."&operacion=".$ls_operacion."'>Siguiente ></a>";
				}
				print "</center>";
			}		
			?>
		</tr>
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Listado de conceptos</td>
        </tr>
<?php if($ls_subnom=='1')
{
?>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de subnomina </td>
        </tr>
        <tr>
          <td height="20"><div align="right"> Desde </div></td>
          <td height="20"><input name="txtcodsubnomdes" type="text" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarsubnominadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
          <td height="20"><div align="right">Hasta </div></td>
          <td height="20"><input name="txtcodsubnomhas" type="text" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarsubnominahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
<?php } 
?>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Conceptos </td>
        </tr>
        <tr>
          <td width="143" height="22"><div align="right"> Desde </div></td>
          <td width="127"><div align="left">
            <input name="txtcodconcdes" type="text" id="txtcodconcdes" size="13" maxlength="10" value="<?php print $ls_codconcdes;?>" readonly>
            <a href="javascript: ue_buscarconceptodesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="96"><div align="right">Hasta </div></td>
          <td width="124"><div align="left">
            <input name="txtcodconchas" type="text" id="txtcodconchas" value="<?php print $ls_codconchas;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarconceptohasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew"> Ente</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Ente</div></td>
          <td><div align="left">
            <input name="txtcodente" type="text" id="txtcodente" value="<?php print $ls_codente;?>" size="13" readonly>
            <a href="javascript:ue_ente();"><img src="../shared/imagebank/tools/buscar.gif" alt="Buscar" width="15" height="15" border="0" ></a>          </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="143" height="22"><div align="right"> Desde </div></td>
          <td width="127"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="<?php print $ls_codperdes;?>" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="96"><div align="right">Hasta </div></td>
          <td width="124"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="<?php print $ls_codperhas;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Unidad Administrativa </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="19" maxlength="16" readonly>
            <a href="javascript: ue_buscaruniadm();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
			<input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="40" maxlength="30" readonly></div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Quitar conceptos en cero</div></td>
          <td><div align="left">
            <input name="chkconceptocero" type="checkbox" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&eacute;dula del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3"><div align="right">
            <input name="registros" type="hidden" id="registros" value="<?php print $li_totregistros;?>">
            <input name="operacion" type="hidden" id="operacion" value="">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
		  <input name="subnom" type="hidden" id="subnom" value="<?php print $ls_subnom;?>">
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
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		reporte=f.reporte.value;
		codconcdes=f.txtcodconcdes.value;
		codconchas=f.txtcodconchas.value;
		tiporeporte="0";
		subnom=f.subnom.value;
		codente=f.txtcodente.value;
		subnomdes="";
		subnomhas="";
		if(subnom=='1')
		{
			subnomdes=f.txtcodsubnomdes.value;
			subnomhas=f.txtcodsubnomhas.value;
		}
		if(codconcdes<=codconchas)
		{
			codperdes=f.txtcodperdes.value;
			codperhas=f.txtcodperhas.value;
			if(codperdes<=codperhas)
			{		
				coduniadm=f.txtcoduniadm.value;
				denuniadm=f.txtdenuniadm.value;
				conceptocero="";
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
				if(f.rdborden[3].checked)
				{
					orden="4";
				}
				if(f.chkconceptocero.checked)
				{
					conceptocero=1;
				}
				pagina="reportes/"+reporte+"?codconcdes="+codconcdes+"&codconchas="+codconchas+"&codperdes="+codperdes;
				pagina=pagina+"&codperhas="+codperhas+"&coduniadm="+coduniadm+"&conceptocero="+conceptocero+"&orden="+orden;
				pagina=pagina+"&denuniadm="+denuniadm+"&tiporeporte="+tiporeporte+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas+"&codente="+codente;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango del personal está erroneo");
			}
		}
		else
		{
			alert("El rango del concepto está erroneo");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_ente()
{
	   window.open("sigesp_sno_cat_ente.php?tipo=replisconc","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_buscarconceptodesde()
{
	window.open("sigesp_sno_cat_concepto.php?tipo=replisconcdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarconceptohasta()
{
	window.open("sigesp_sno_cat_concepto.php?tipo=replisconchas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=reppagnomdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=reppagnomhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscaruniadm()
{
	window.open("sigesp_snorh_cat_uni_ad.php?tipo=replisconc","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_buscarsubnominadesde()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=reportedesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsubnominahasta()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=reportehasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>