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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_disponibilidad.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$ls_reporte_especifico=$io_sno->uf_select_config("SNO","REPORTE","DISPONIBLE_FINANCIERO","sigesp_sno_rpp_disponibilidad.php","C");
	$ls_subnom=$_SESSION["la_nomina"]["subnom"];
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
	$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
	$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
	$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_titulo="";
	$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
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
<title >Reporte de Disponibilidad Financiera</title>
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
<table width="700" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="680" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Disponibilidad Financiera </td>
        </tr>
<?php if($ls_subnom=='1')
{
?>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4"><div align="center">Intervalo de Subn&oacute;mina </div></td>
          </tr>
        <tr>
          <td width="187" height="22"><div align="right">Desde</div></td>
          <td width="223"><div align="left">
            <input name="txtcodsubnomdes" type="text" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarsubnominadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>          </div>
          <td width="97"><div align="right">Hasta
          </div>
          <td width="203"><div align="left">
            <input name="txtcodsubnomhas" type="text" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarsubnominahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div>
        </tr>
<?php } 
?>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4"><div align="center"></div></td>
          </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro1;?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="" size="<?php print $li_len1+10; ?>" maxlength="<?php print $li_len1; ?>" readonly>
              <a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="" size="53" readonly>
              <input name="txtestcla1" type="hidden" id="txtestcla1" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro2;?> </div></td>
          <td colspan="3"><div align="left" >
              <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="" size="<?php print $li_len2+10; ?>" maxlength="<?php print $li_len2; ?>" readonly>
              <a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="" size="53" readonly>
              <input name="txtestcla2" type="hidden" id="txtestcla2" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro3; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="" size="<?php print $li_len3+10; ?>" maxlength="<?php print $li_len3; ?>" readonly>
              <a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="" size="53" readonly>
              <input name="txtestcla3" type="hidden" id="txtestcla3" size="2" value="">
          </div></td>
        </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="">
                <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="">
                <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="">
<?php }
	  else
	  {?>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro4; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="" size="<?php print $li_len4+10; ?>" maxlength="<?php print $li_len4;?>" readonly>
              <a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="" size="53" readonly>
              <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro5; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="" size="<?php print $li_len5+10; ?>" maxlength="<?php print $li_len5;?>" readonly>
              <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="" size="53" readonly>
              <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="">
          </div></td>
        </tr>
<?php }?>
        <tr>
          <td height="22">&nbsp;</td>
          <td width="487" colspan="5"><div align="right">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
            <input name="reporte_especifico" type="hidden" id="reporte_especifico" value="<?php print $ls_reporte_especifico;?>">
            <input name="modalidad" type="hidden" id="modalidad" value="<?php print $ls_modalidad;?>">
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
	valido=true;
	if(li_imprimir==1)
	{	
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;
		estcla=f.txtestcla1.value;
		modalidad=f.modalidad.value;
		subnom=f.subnom.value;
		subnomdes="";
		subnomhas="";
		if(subnom=='1')
		{
			subnomdes=f.txtcodsubnomdes.value;
			subnomhas=f.txtcodsubnomhas.value;
		}
		if(modalidad=="1")
		{
			if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")||((subnomdes!="")&&(subnomhas!="")))
			{
				reporte=f.reporte_especifico.value;
				denestpro1=f.txtdenestpro1.value;
				denestpro2=f.txtdenestpro2.value;	
				denestpro3=f.txtdenestpro3.value;
				reporte=reporte+"?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+
				                "&codestpro5="+codestpro5+"&estcla="+estcla+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas
								+"&denestpro1="+denestpro1+"&denestpro2="+denestpro2+"&denestpro3="+denestpro3;
			}
			else
			{
				if((codestpro1=="") && (codestpro2=="") && (codestpro3=="")&&(subnomdes=="")&&(subnomhas==""))
				{
					tipo=1;
					reporte=f.reporte_especifico.value;
					reporte=reporte+"?tipo="+tipo;
				}
				if((codestpro1!="") && (codestpro2!="") && (codestpro3=="")&&(subnomdes=="")&&(subnomhas==""))
				{
					valido=false;
					alert("Debe seleccionar la estructura completa");
				}
			}
		}
		else
		{
			if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!="")||((subnomdes!="")&&(subnomhas!="")))
			{
				reporte=f.reporte_especifico.value;
				reporte=reporte+"?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+
				                "&codestpro5="+codestpro5+"&estcla="+estcla+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas;
			}
			else
			{
				if((codestpro1=="")&&(codestpro2=="")&&(codestpro3=="")&&(codestpro4=="")&&(codestpro5=="")&&(subnomdes=="")&&(subnomhas==""))
				{
					valido=false;
					alert("Debe seleccionar la estructura completa");
				}
			}
		}
		if(valido)
		{
			pagina="reportes/"+reporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_estructura1()
{
	   window.open("sigesp_snorh_cat_estpre1.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_estructura2()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	estcla1=f.txtestcla1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_snorh_cat_estpre2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla1="+estcla1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}

function ue_estructura3()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	estcla2=f.txtestcla2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_snorh_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla2="+estcla2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura4()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	estcla3=f.txtestcla3.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!=""))
	{
    	pagina="sigesp_snorh_cat_estpre4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla3="+estcla3;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura5()
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	denestpro4=f.txtdenestpro4.value;
	estcla4=f.txtestcla4.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!="")&&(codestpro3!="")&&(denestpro3!="")&&(codestpro4!="")&&(denestpro4!=""))
	{
    	pagina="sigesp_snorh_cat_estpre5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla4="+estcla4;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
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