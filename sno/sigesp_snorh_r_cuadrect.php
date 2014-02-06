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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_cuadrect.php",$ls_permisos,$la_seguridad,$la_permisos);
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$ls_nomestpro1=$ls_nomestpro6=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_nomestpro2=$ls_nomestpro7=$_SESSION["la_empresa"]["nomestpro2"];		
	$ls_nomestpro3=$ls_nomestpro8=$_SESSION["la_empresa"]["nomestpro3"];		
	$ls_nomestpro4=$ls_nomestpro9=$_SESSION["la_empresa"]["nomestpro4"];		
	$ls_nomestpro5=$ls_nomestpro10=$_SESSION["la_empresa"]["nomestpro5"];		
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$li_len6=0;
	$li_len7=0;
	$li_len8=0;
	$li_len9=0;
	$li_len10=0;
	$ls_titulo="";
	$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
	$io_fun_nomina->uf_loadmodalidad(&$li_len6,&$li_len7,&$li_len8,&$li_len9,&$li_len10,&$ls_titulo);
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
<title >Reporte Cuadre de Conceptos de Cestaticket</title>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="700" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="680" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte Cuadre de Conceptos de Cestaticket</td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4"><div align="center">Rango de Estructura Presupuestria Desde </div></td>
          </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro1;?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="" size="<?php print $li_len1+10; ?>" maxlength="<?php print $li_len1; ?>" readonly>
              <a href="javascript:ue_estructura1('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="" size="53" readonly>
              <input name="txtestcla1" type="hidden" id="txtestcla1" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro2;?> </div></td>
          <td colspan="3"><div align="left" >
              <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="" size="<?php print $li_len2+10; ?>" maxlength="<?php print $li_len2; ?>" readonly>
              <a href="javascript:ue_estructura2('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="" size="53" readonly>
              <input name="txtestcla2" type="hidden" id="txtestcla2" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro3; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="" size="<?php print $li_len3+10; ?>" maxlength="<?php print $li_len3; ?>" readonly>
              <a href="javascript:ue_estructura3('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
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
              <a href="javascript:ue_estructura4('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="" size="53" readonly>
              <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro5; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="" size="<?php print $li_len5+10; ?>" maxlength="<?php print $li_len5;?>" readonly>
              <a href="javascript:ue_estructura5('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="" size="53" readonly>
              <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="">
          </div></td>
        </tr>
<?php }?>
	 <!-- CARLOS ZAMBRANO  !-->
	 <tr class="titulo-celdanew">
          <td height="22" colspan="4"><div align="center">Rango de Estructura Presupuestria Hasta </div></td>
     </tr>
	 <tr>
	 <tr>
          <td><div align="right"> <?php print $ls_nomestpro6;?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro6" type="text" id="txtcodestpro6" value="" size="<?php print $li_len6+10; ?>" maxlength="<?php print $li_len6; ?>" readonly>
              <a href="javascript:ue_estructura1('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro6" type="text" class="sin-borde" id="txtdenestpro6" value="" size="53" readonly>
              <input name="txtestcla6" type="hidden" id="txtestcla6" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro7;?> </div></td>
          <td colspan="3"><div align="left" >
              <input name="txtcodestpro7" type="text" id="txtcodestpro7" value="" size="<?php print $li_len7+10; ?>" maxlength="<?php print $li_len7; ?>" readonly>
              <a href="javascript:ue_estructura6('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro7" type="text" class="sin-borde" id="txtdenestpro7" value="" size="53" readonly>
              <input name="txtestcla7" type="hidden" id="txtestcla7" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro8; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro8" type="text" id="txtcodestpro8" value="" size="<?php print $li_len8+10; ?>" maxlength="<?php print $li_len8; ?>" readonly>
              <a href="javascript:ue_estructura7('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro8" type="text" class="sin-borde" id="txtdenestpro8" value="" size="53" readonly>
              <input name="txtestcla8" type="hidden" id="txtestcla8" size="2" value="">
          </div></td>
        </tr>
		<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro9" type="hidden" id="txtcodestpro9" value="">
 				<input name="txtdenestpro9" type="hidden" id="txtdenestpro9" value="">
 				<input name="txtcodestpro10" type="hidden" id="txtcodestpro10" value="">
 				<input name="txtdenestpro10" type="hidden" id="txtdenestpro10" value="">
                <input name="txtestcla9" type="hidden" id="txtestcla9" size="2" value="">
                <input name="txtestcla10" type="hidden" id="txtestcla10" size="2" value="">
<?php }
	  else
	  {?>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro9; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro9" type="text" id="txtcodestpro9" value="" size="<?php print $li_len9+10; ?>" maxlength="<?php print $li_len9;?>" readonly>
              <a href="javascript:ue_estructura8('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro9" type="text" class="sin-borde" id="txtdenestpro9" value="" size="53" readonly>
              <input name="txtestcla9" type="hidden" id="txtestcla9" size="2" value="">
          </div></td>
        </tr>
        <tr>
          <td><div align="right"> <?php print $ls_nomestpro10; ?> </div></td>
          <td colspan="3"><div align="left">
              <input name="txtcodestpro10" type="text" id="txtcodestpro10" value="" size="<?php print $li_len10+10; ?>" maxlength="<?php print $li_len10;?>" readonly>
              <a href="javascript:ue_estructura9('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
              <input name="txtdenestpro9" type="text" class="sin-borde" id="txtdenestpro10" value="" size="53" readonly>
              <input name="txtestcla10" type="hidden" id="txtestcla10" size="2" value="">
          </div></td>
        </tr>
<?php }?>
<!-- CARLOS ZAMBRANO  !-->
          <tr class="titulo-celdanew">
          <td height="20" colspan="4"><div align="center"></div></td>
     	  </tr>
		  <td height="22"><div align="right">Mes</div></td>
          <td colspan="3"><div align="left">
            <input name="txtanocurper" type="text" id="txtanocurper" size="7" maxlength="4" readonly>
            <input name="txtmescurper" type="text" id="txtmescurper" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmeses();"><img id="meses" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesmesper" type="text" class="sin-borde" id="txtdesmesper" value="" size="30" maxlength="20" readonly>
            <input name="txtcodperi" type="hidden" id="txtcodperi">
          </div></td>
        </tr>

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
		codestpro6=f.txtcodestpro6.value;
		codestpro7=f.txtcodestpro7.value;
		codestpro8=f.txtcodestpro8.value;
		codestpro9=f.txtcodestpro9.value;
		codestpro10=f.txtcodestpro10.value;
		estcla=f.txtestcla1.value;
		estcla2=f.txtestcla6.value;
		modalidad=f.modalidad.value;
		codperi=f.txtcodperi.value;
		mes=f.txtmescurper.value;
		ano=f.txtanocurper.value;
		reporte="sigesp_snorh_rpp_cuadrect.php";
		if ((mes=="")&&(ano=="")&&(codperi==""))
		{
			alert("Debe seleccionar un Mes.");
		}
		else
		{
			if(modalidad=="1")
			{
				if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro6!="")&&(codestpro7!="")&&(codestpro8!=""))
				{
					denestpro1=f.txtdenestpro1.value;
					denestpro2=f.txtdenestpro2.value;	
					denestpro3=f.txtdenestpro3.value;
					denestpro6=f.txtdenestpro6.value;
					denestpro7=f.txtdenestpro7.value;	
					denestpro8=f.txtdenestpro8.value;
					reporte=reporte+"?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro6="+codestpro6+"&codestpro7="+codestpro7+"&codestpro8="+codestpro8+
									"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&estcla="+estcla+"&estcla2="+estcla2+"&denestpro1="+denestpro1+"&denestpro2="+denestpro2+
									"&denestpro3="+denestpro3+"&denestpro6="+denestpro6+"&denestpro7="+denestpro7+"&denestpro8="+denestpro8+"&mes="+mes+"&ano="+ano+"&codperi="+codperi;
				}
				else
				{
					if((codestpro1=="")&&(codestpro2=="")&&(codestpro3=="")&&(codestpro6=="")&&(codestpro7=="")&&(codestpro8==""))
					{
						reporte="sigesp_snorh_rpp_cuadrect.php";
						reporte=reporte+"?mes="+mes+"&ano="+ano+"&codperi="+codperi;
					}
					else
					{
						valido=false;
						alert("Debe seleccionar la estructura completa");
					}
				}
			}
			else
			{
				if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!="")&&(codestpro6!="")&&(codestpro7!="")&&(codestpro8!="")&&(codestpro9!="")&&(codestpro10!=""))
				{
					reporte="sigesp_snorh_rpp_cuadrect.php";
					reporte=reporte+"?codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+
				                "&codestpro5="+codestpro5+"&codestpro6="+codestpro6+"&codestpro7="+codestpro7+"&codestpro8="+codestpro8+
								"&codestpro9="+codestpro9+"&codestpro10="+codestpro10+"&estcla="+estcla+"&estcla2="+estcla2+"&mes="+mes+"&ano="+ano+"&codperi="+codperi;
				}
				else
				{
					if((codestpro1=="")&&(codestpro2=="")&&(codestpro3=="")&&(codestpro4=="")&&(codestpro5=="")&&(codestpro6=="")&&(codestpro7=="")&&(codestpro8=="")&&(codestpro9=="")&&(codestpro10==""))
					{
						reporte="sigesp_snorh_rpp_cuadrect.php";
						reporte=reporte+"?mes="+mes+"&ano="+ano+"&codperi="+codperi;
					}
					else
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
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_estructura1(ls_tipo)
{
	   window.open("sigesp_snorh_cat_estpre1.php?tipo="+ls_tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_estructura2(ls_tipo)
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	estcla1=f.txtestcla1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_snorh_cat_estpre2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla1="+estcla1+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}

function ue_estructura6(ls_tipo)
{
	f=document.form1;
	codestpro6=f.txtcodestpro6.value;
	denestpro6=f.txtdenestpro6.value;
	estcla6=f.txtestcla6.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_snorh_cat_estpre2.php?codestpro1="+codestpro6+"&denestpro1="+denestpro6+"&estcla1="+estcla6+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}

function ue_estructura3(ls_tipo)
{
	f=document.form1;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	estcla2=f.txtestcla2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_snorh_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla2="+estcla2+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura7(ls_tipo)
{
	f=document.form1;
	codestpro6=f.txtcodestpro6.value;
	denestpro6=f.txtdenestpro6.value;
	codestpro7=f.txtcodestpro7.value;
	denestpro7=f.txtdenestpro7.value;
	estcla7=f.txtestcla7.value;
	if((codestpro6!="")&&(denestpro6!="")&&(codestpro7!="")&&(denestpro7!=""))
	{
    	pagina="sigesp_snorh_cat_estpre3.php?codestpro1="+codestpro6+"&denestpro1="+denestpro6+"&codestpro2="+codestpro7+"&denestpro2="+denestpro7+"&estcla2="+estcla7+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura4(ls_tipo)
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
function ue_estructura8(ls_tipo)
{
	f=document.form1;
	codestpro6=f.txtcodestpro6.value;
	denestpro6=f.txtdenestpro6.value;
	codestpro7=f.txtcodestpro7.value;
	denestpro7=f.txtdenestpro7.value;
	codestpro8=f.txtcodestpro8.value;
	denestpro8=f.txtdenestpro8.value;
	estcla8=f.txtestcla8.value;
	if((codestpro6!="")&&(denestpro6!="")&&(codestpro7!="")&&(denestpro7!="")&&(codestpro8!="")&&(denestpro8!=""))
	{
    	pagina="sigesp_snorh_cat_estpre4.php?codestpro1="+codestpro6+"&denestpro1="+denestpro6+"&codestpro2="+codestpro7+"&denestpro2="+denestpro7+"&codestpro3="+codestpro8+"&denestpro3="+denestpro8+"&estcla3="+estcla8+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura5(ls_tipo)
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
    	pagina="sigesp_snorh_cat_estpre5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla4="+estcla4+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura9(ls_tipo)
{
	f=document.form1;
	codestpro6=f.txtcodestpro6.value;
	denestpro6=f.txtdenestpro6.value;
	codestpro7=f.txtcodestpro7.value;
	denestpro7=f.txtdenestpro7.value;
	codestpro8=f.txtcodestpro8.value;
	denestpro8=f.txtdenestpro8.value;
	codestpro9=f.txtcodestpro9.value;
	denestpro9=f.txtdenestpro9.value;
	estcla9=f.txtestcla9.value;
	if((codestpro6!="")&&(denestpro6!="")&&(codestpro7!="")&&(denestpro7!="")&&(codestpro8!="")&&(denestpro8!="")&&(codestpro9!="")&&(denestpro9!=""))
	{
    	pagina="sigesp_snorh_cat_estpre5.php?codestpro1="+codestpro6+"&denestpro1="+denestpro6+"&codestpro2="+codestpro7+"&denestpro2="+denestpro7+"&codestpro3="+codestpro8+"&denestpro3="+denestpro8+"&codestpro4="+codestpro9+"&denestpro4="+denestpro9+"&estcla4="+estcla9+"&tipo="+ls_tipo;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_buscarmeses()
{
	f=document.form1;
	window.open("sigesp_sno_cat_hmes.php?tipo=repcuadrect","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
   	
}

</script> 
</html>
