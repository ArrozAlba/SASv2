<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNO","sigesp_sno_p_hunidadadmin.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionhnomina();		
	unset($io_sno);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_denominacion,$ls_modalidad,$ls_nomestpro1,$ls_nomestpro2,$ls_nomestpro3,$ls_nomestpro4,$ls_nomestpro5;
		global $ls_titulo,$ls_codestpro1,$ls_denestpro1,$ls_codestpro2,$ls_denestpro2,$ls_codestpro3,$ls_denestpro3;
		global $ls_codestpro4,$ls_denestpro4,$ls_codestpro5,$ls_denestpro5,$ls_operacion,$lb_existe,$io_fun_nomina,$li_maxlen;
		global $ls_desnom,$ls_desper,$li_contabilizado,$ls_estcla1,$ls_estcla2,$ls_estcla3,$ls_estcla4,$ls_estcla5;
		
		$ls_codigo="";
		$ls_denominacion="";
		$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
		$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
		$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
		$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
		$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
		$ls_codestpro1="";
		$ls_denestpro1="";
		$ls_codestpro2="";
		$ls_denestpro2="";
		$ls_codestpro3="";
		$ls_denestpro3="";
		$ls_codestpro4="";
		$ls_denestpro4="";
		$ls_codestpro5="";
		$ls_denestpro5="";
		$ls_estcla1="";
		$ls_estcla2="";
		$ls_estcla3="";
		$ls_estcla4="";
		$ls_estcla5="";
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria";
				$ls_codestpro4="00";
				$ls_codestpro5="00";
				$li_maxlen=25;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Programática";
				$li_maxlen=5;
				break;
		}
		require_once("sigesp_sno_c_ajustarcontabilizacion.php");
		$io_ajustar=new sigesp_sno_c_ajustarcontabilizacion();
		$li_contabilizado=$io_ajustar->uf_contabilizado();
		unset($io_ajustar);
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$lb_existe=$io_fun_nomina->uf_obtenerexiste();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_denominacion,$ls_modalidad,$ls_codestpro1,$ls_denestpro1,$ls_codestpro2,$ls_denestpro2,$ls_codestpro3;
		global $ls_denestpro3,$ls_codestpro4,$ls_denestpro4,$ls_codestpro5,$ls_denestpro5,$ls_codpro;
		global $ls_estcla1,$ls_estcla2,$ls_estcla3,$ls_estcla4,$ls_estcla5,$ls_estcla;
		
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_codestpro1=$_POST["txtcodestpro1"];
		$ls_denestpro1=$_POST["txtdenestpro1"];
		$ls_codestpro2=$_POST["txtcodestpro2"];
		$ls_denestpro2=$_POST["txtdenestpro2"];
		$ls_codestpro3=$_POST["txtcodestpro3"];
		$ls_denestpro3=$_POST["txtdenestpro3"];
		$ls_codestpro4=$_POST["txtcodestpro4"];
		$ls_denestpro4=$_POST["txtdenestpro4"];
		$ls_codestpro5=$_POST["txtcodestpro5"];
		$ls_denestpro5=$_POST["txtdenestpro5"];
		$ls_estcla1=$_POST["txtestcla3"];
		$ls_estcla2=$_POST["txtestcla3"];
		$ls_estcla3=$_POST["txtestcla3"];
		$ls_estcla4=$_POST["txtestcla3"];
		$ls_estcla5=$_POST["txtestcla3"];
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_estcla=$ls_estcla3;
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_estcla=$ls_estcla5;
				break;
		}
		$ls_codpro=str_pad($ls_codestpro1,25,"0",0).str_pad($ls_codestpro2,25,"0",0).str_pad($ls_codestpro3,25,"0",0);
		$ls_codpro=$ls_codpro.str_pad($ls_codestpro4,25,"0",0).str_pad($ls_codestpro5,25,"0",0);
   }
   //--------------------------------------------------------------
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
<title>Modificar Unidad Administrativa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_uni_ad.php");
	$io_unidadadm = new sigesp_snorh_c_uni_ad();
	uf_limpiarvariables();
	$ld_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fechasnom=substr($_SESSION["la_nomina"]["fechasper"],0,4);
	if($ld_fechasnom!=$ld_ano)
	{
		print("<script language=JavaScript>");
		print(" alert('Este proceso esta desactivo para Períodos de años Diferentes al Periodo de la Empresa.');");
		print(" location.href='sigespwindow_blank_hnomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_unidadadm->uf_update_unidadadministrativa_historico($ls_codigo,$ls_denominacion,$ls_codpro,$ls_estcla,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
	$io_unidadadm->uf_destructor();
	unset($io_unidadadm);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
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
<div align="center">
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
		  <p>&nbsp;</p>
		  <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="20" colspan="2">Modificar Unidad Administrativa</td>
              </tr>
              <tr >
                <td width="158" height="22">&nbsp;</td>
                <td width="486">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right" >
                    C&oacute;digo
                </div></td>
                <td ><div align="left" >
                  <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo; ?>" size="16" maxlength="12" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,12);">
                  <a href="javascript: ue_buscarunidad();"><img id="concepto" src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Concepto"></a></div></td>
              </tr>
              <tr >
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td><div align="left">
                  <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion; ?>" onKeyUp="ue_validarcomillas(this);" size="70" maxlength="100">
                </div></td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td><div align="left"><strong><?php print $ls_titulo; ?></strong></div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                <?php print $ls_nomestpro1;?>				
                </div></td>
                <td>	
				  <div align="left">
                  <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>" size="<?php print $ls_loncodestpro1+10; ?>" maxlength="<?php print $ls_loncodestpro1+1; ?>" readonly>
                  <a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                  <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1;?>" size="53" readonly>
                  <input name="txtestcla1" type="hidden" id="txtestcla1" size="2" value="<?php print $ls_estcla1;?>">			
                  </div>
              </td>
              </tr>
            <tr>
                <td height="22">
				<div align="right">
				<?php print $ls_nomestpro2;?>
			  </div>
			  </td>
                <td>
				 <div align="left" >
                 <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print $ls_codestpro2 ; ?>" size="<?php print $ls_loncodestpro2+10; ?>" maxlength="<?php print $ls_loncodestpro2+1; ?>" readonly>
                 <a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                 <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2 ; ?>" size="53" readonly>
                 <input name="txtestcla2" type="hidden" id="txtestcla2" size="2" value="<?php print $ls_estcla2;?>">
                 </div>
				</td>
            </tr>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro3; ?>
			  </div>
			  </td>
              <td>
			    <div align="left">
                <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>" size="<?php print $ls_loncodestpro3+10; ?>" maxlength="<?php print $ls_loncodestpro3+1; ?>" readonly>
                <a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3;?>" size="53" readonly>
                <input name="txtestcla3" type="hidden" id="txtestcla3" size="2" value="<?php print $ls_estcla3;?>">
                </div></td>
            </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="<?php print $ls_denestpro4;?>">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="<?php print $ls_denestpro5;?>">
                <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="<?php print $ls_estcla4;?>">
                <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="<?php print $ls_estcla5;?>">
                
<?php }
	  else
	  {?>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro4; ?>
			  </div>
			  </td>
              <td>
			    <div align="left">
                <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>" size="<?php print $ls_loncodestpro4+10; ?>" maxlength="<?php print $ls_loncodestpro4+1;?>" readonly>
                <a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4;?>" size="53" readonly>
                <input name="txtestcla4" type="hidden" id="txtestcla4" size="2" value="<?php print $ls_estcla4;?>">
                </div></td>
            </tr>
            <tr>
              <td height="22">
                <div align="right">
				<?php print $ls_nomestpro5; ?>
			  </div>
			  </td>
              <td>
			    <div align="left">
                <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>" size="<?php print $ls_loncodestpro5+10; ?>" maxlength="<?php print $ls_loncodestpro5+1;?>" readonly>
                <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
                <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" value="<?php print $ls_denestpro5;?>" size="53" readonly>
                <input name="txtestcla5" type="hidden" id="txtestcla5" size="2" value="<?php print $ls_estcla5;?>">
                </div></td>
            </tr>
<?php } ?>
            <tr>
              <td height="18"><div align="right"></div></td>
              <td><input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php print $lb_existe;?>">
              <input name="modalidad" type="hidden" id="modalidad" value="<?php print $ls_modalidad;?>">
			  <input name="contabilizado" type="hidden" id="contabilizado" value="<?php print $li_contabilizado;?>"></td>
            </tr>
          </table>
          <p>&nbsp;</p></td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(f.contabilizado.value=="0")
	{	
		if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
		{
			codigo = ue_validarvacio(f.txtcodigo.value);
			denominacion = ue_validarvacio(f.txtdenominacion.value);
			codestpro1 = ue_validarvacio(f.txtcodestpro1.value);
			codestpro2 = ue_validarvacio(f.txtcodestpro2.value);
			codestpro3 = ue_validarvacio(f.txtcodestpro3.value);
			if(f.modalidad.value=="1")
			{
				codestpro4 = "00";
				codestpro5 = "00";
			}
			else
			{
				codestpro4 = ue_validarvacio(f.txtcodestpro4.value);
				codestpro5 = ue_validarvacio(f.txtcodestpro5.value);
			}
			if ((codigo!="")&&(denominacion!="")&&(codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
			{
				f=document.form1;
				f.operacion.value ="GUARDAR";
				f.action="sigesp_sno_p_hunidadadmin.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún cambio.");
	}
}

function ue_buscarunidad()
{
	window.open("sigesp_sno_cat_huni_ad.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_unidadadministrativa.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
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
</script>
</html>