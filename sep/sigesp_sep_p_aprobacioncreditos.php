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
	require_once("class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	$io_fun_sep->uf_load_seguridad("SEP","sigesp_sep_p_aprobacioncreditos.php",$ls_permisos,$la_seguridad,$la_permisos);
	$lb_cierrescg = $io_fun_sep->uf_chkciespg();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		// Fecha Creación: 22/07/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_operacion,$lo_title,$li_totrows,$oi_fun_integrador,$li_widthtable,$ls_titletable,$ls_nametable;
		global $ls_rutaarchivo, $io_fun_sep;
		
        $lo_title[1]="";
		$lo_title[2]="Beneficiario";
		$lo_title[3]="Fecha Credito";
		$lo_title[4]="Concepto";
		$li_widthtable=700;
		$ls_titletable="Créditos por aprobar";
		$ls_nametable="grid";
		$li_totrows=$io_fun_sep->uf_obtenervalor("totalfilas",0);
		$ls_rutaarchivo="scc/aprobacion/pendientes";
		$ls_operacion=$io_fun_sep->uf_obteneroperacion();
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
<title >Aprobaci&oacute;n de Cr&eacute;ditos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/sep.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("class_folder/sigesp_sep_c_aprobacioncreditos.php");  	
	$in_class = new sigesp_sep_c_aprobacioncreditos("../");  
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if(array_key_exists("chkaprobacion".$li_i,$_POST))
				{
					$ls_archivo=$_POST["txtarchivo".$li_i];
					$ls_ced_bene=$_POST["txtbeneficiario".$li_i];
					$ls_consol=$_POST["txtconcepto".$li_i];
					$li_monto=$_POST["txtmonto".$li_i];
					$li_monto = str_replace(".",'',$li_monto);
					$li_monto = str_replace(",",'.',$li_monto);
					$ls_codtipsol=$_POST["txtcodtipsol".$li_i];
					$ls_coduniadm=$_POST["txtcoduniadm".$li_i];
					$ls_estcla=$_POST["txtestcla".$li_i];
					$ls_codestpro1=$_POST["txtcodestpro1".$li_i];
					$ls_codestpro2=$_POST["txtcodestpro2".$li_i];
					$ls_codestpro3=$_POST["txtcodestpro3".$li_i];
					$ls_codestpro4=$_POST["txtcodestpro4".$li_i];
					$ls_codestpro5=$_POST["txtcodestpro5".$li_i];
					$ls_tipo_destino=$_POST["txttipodestino".$li_i];	
					$lb_valido=$in_class->uf_procesar_beneficiarios("../".$ls_rutaarchivo,$ls_archivo,$la_seguridad);
					if($lb_valido)
					{
						$lb_valido=$in_class->uf_procesar_credito("../".$ls_rutaarchivo,$ls_archivo,$ls_ced_bene,$ls_consol,$li_monto,$ls_codtipsol,
																  $ls_coduniadm,$ls_estcla,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
																  $ls_codestpro4,$ls_codestpro5,$ls_tipo_destino,$la_seguridad);
					}
					if($lb_valido)
					{
						$in_class->io_mensajes->message("El Crédito  ".$ls_ced_bene." - ".$ls_consol." fue Aprobado.");
					}
					else
					{
						$in_class->io_mensajes->message("El Crédito  ".$ls_ced_bene." - ".$ls_consol." no fue aprobado. ");
					}
				}
			}
			$li_totrows=1;
			break;
	}
	$in_class->uf_destructor();
	unset($in_class);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Solicitud 
            de Ejecuci&oacute;n Presupuestaria</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
			<tr>
			<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
			<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Grabar" width="20" height="20" border="0" title="Procesar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
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
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sep->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sep);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136">
      <p>&nbsp;</p>
        <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana"> 
            <td width="100%" class="titulo-ventana">Aprobaci&oacute;n de Cr&eacute;ditos </td>
          </tr>
        </table>
        <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><div align="center"></div></td>
          </tr>
          <tr>
            <td><div id="creditos" align="center">
			</div></td>
          </tr>
          <tr>
            <td width="748"><input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrow; ?>">
            <input name="operacion" type="hidden" id="operacion">
            <input name="rutaarchivo" type="hidden" id="rutaarchivo" value="<?php print "../../".$ls_rutaarchivo; ?>">
			</td>
          </tr>
          <tr>
            <td></td>
          </tr>
        </table>        </td>
  </tr>
</table>
</form>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		// Cargamos las variables para pasarlas al AJAX
		rutaarchivo=f.rutaarchivo.value;
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('creditos');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_sep_c_aprobacioncreditos_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("rutaarchivo="+rutaarchivo+"&proceso=BUSCAR");
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.formulario;
	li_procesar=f.ejecutar.value;
	if (li_procesar==1)
   	{
		total=ue_calcular_total_fila_local("txtbeneficiario");
		f.totalfilas.value=total;
		valido=false;
		for(i=1;i<=total;i++)
		{
			if(eval("f.chkaprobacion"+i+".checked")==true)
			{
				valido=true;
			}
		}
		if(valido==true)
		{
			f.operacion.value="PROCESAR";
			f.action="sigesp_sep_p_aprobacioncreditos.php";
			f.submit();		
		}
		else
		{
			alert("Debe marcar lo(s) créditos(es) a aprobar");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
   	}
	
}
</script> 
</html>