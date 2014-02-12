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
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_p_aprobacion_contrato.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fun_sob,$ls_operacion,$ld_feccondes,$ld_fecconhas,$ld_fecapr,$li_totrow;
		
		$ls_operacion=$io_fun_sob->uf_obteneroperacion();
		$ld_feccondes=date("01/m/Y");
		$ld_fecconhas=date("d/m/Y");
		$ld_fecapr=date("d/m/Y");
		$li_totrow=0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrow,$ls_tipope,$ld_fecapr;
		
		$li_totrow = $_POST["totrow"];
		$ls_tipope = $_POST["rdtipooperacion"];
		$ld_fecapr  =$_POST["txtfecapr"];
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
<title >Aprobaci&oacute;n de Contrato</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
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
	require_once("class_folder/sigesp_sob_c_aprobacion_contrato.php");
	$io_sob=new sigesp_sob_c_aprobacion_contrato("../");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_mensajes=new class_mensajes();	
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
	require_once("../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();		
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			uf_load_variables();
			$lb_valido=false;
			for($li_i=0;$li_i<=$li_totrow;$li_i++)
			{
				if (array_key_exists("chkaprobacion".$li_i,$_POST))
				{
					$ls_codcon=$io_fun_sob->uf_obtenervalor("txtcodcon".$li_i,"");
					$ld_feccon=$io_fun_sob->uf_obtenervalor("txtfeccon".$li_i,"");
					$ld_feccon=$io_funciones->uf_formatovalidofecha($ld_feccon);
					switch ($ls_tipope)
					{
						case 0: // Aprobaciòn de la contrato
							$lb_valido=$io_fecha->uf_comparar_fecha($ld_feccon,$ld_fecapr);
							if($lb_valido)
							{
								$lb_existe=$io_sob->uf_validar_estatus_contrato($ls_codcon,"1");
								if(!$lb_existe)
								{
									$lb_valido=$io_sob->uf_validar_cuentas($ls_codcon);
									if($lb_valido)
									{
										$lb_valido=$io_sob->uf_update_estatus_contrato($ls_codcon,1,$ld_fecapr,$la_seguridad);
									}
								}
								else
								{
									$io_mensajes->message("El Contrato ".$ls_codcon." ya esta aprobado");
								}
							}
							else
							{
								$io_mensajes->message("La Fecha de Registro del Contrato ".$ls_codcon." debe ser menor a la fecha de Aprobación");
							}							
							break;
		
						case 1:
							$lb_existe=$io_sob->uf_validar_estatus_contrato($ls_codcon,"0");
							if(!$lb_existe)
							{
								$lb_valido=$io_sob->uf_update_estatus_contrato($ls_codcon,0,$ld_fecapr,$la_seguridad);
							}
							else
							{
								$io_mensajes->message("El contrato ".$ls_codcon." debe estar aprobado y Emitido ò Modificado");
							}
							break;
					}
				}
			}
			if($lb_valido)
			{
				$io_mensajes->message("El proceso se realizo con Exito");
			}
			else
			{
				$io_mensajes->message("No se pudo realizar el proceso");
			}
			uf_limpiarvariables();
			break;
	}
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
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
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136">
      <p>&nbsp;</p>
        <table width="741" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="4" class="titulo-ventana">Aprobaci&oacute;n de Contrato </td>
          </tr>
          <tr> 
            <td width="22%" height="22"><div align="right"></div></td>
            <td colspan="2"><div align="right">Fecha</div></td>
            <td width="18%"><input name="txtfecapr" type="text" id="txtfecapr" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecapr; ?>" size="15"  datepicker="true"></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Contrato</div></td>
            <td height="22" colspan="3"><div align="left">
              <input name="txtcodcon" type="text" id="txtcodcon" size="18" onKeyUp="ue_validarnumero(this);"> 
            </div></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Fecha de Registro </div></td>
            <td width="18%" height="22"><div align="left">Desde 
                <input name="txtfeccondes" type="text" id="txtfeccondes"  style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_feccondes; ?>" size="13" maxlength="10"  datepicker="true">
            </div></td>
            <td width="42%">Hasta
              <input name="txtfecconhas" type="text" id="txtfecconhas" style="text-align:center" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecconhas; ?>" size="13"  datepicker="true">            </td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22" colspan="3"><div align="left">
              <input name="rdtipooperacion" type="radio" class="sin-borde" value="0" checked>
              Aprobaci&oacute;n
              <input name="rdtipooperacion" type="radio" class="sin-borde" value="1">
            Reversar Aprobaci&oacute;n </div></td>
          </tr>
        </table>
        <table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="748"><input name="totrow" type="hidden" id="totrow" value="<?php print $li_totrow; ?>">
            <input name="operacion" type="hidden" id="operacion"></td>
          </tr>
          <tr>
            <td><div id="asignacion"></div></td>
          </tr>
        </table>        </td>
  </tr>
</table>
</form>   
<?php
	$io_sob->uf_destructor();
	unset($io_sob);
?>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_catalogo(ls_catalogo)
{
	// abre el catalogo que se paso por parametros
	window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

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
		valido=ue_validarcampo(f.txtfeccondes.value,"Los campos de fecha no deben estar vacios",f.txtfeccondes);
		if(valido)
		{
			valido=ue_validarcampo(f.txtfecconhas.value,"Los campos de fecha no deben estar vacios",f.txtfecconhas.value);
			if(valido)
			{
				valido=ue_validarcampo(f.txtfecapr.value,"Los campos de fecha no deben estar vacios",f.txtfecapr.value);
				if(valido)
				{
					fecconhas=f.txtfecconhas.value;
					fecapr=f.txtfecapr.value;
					valido=ue_comparar_fechas(fecconhas,fecapr);
					if(valido)
					{
						// Cargamos las variables para pasarlas al AJAX
						codcon=f.txtcodcon.value;
						feccondes=f.txtfeccondes.value;
						fecconhas=f.txtfecconhas.value;
						if(document.formulario.rdtipooperacion[0].checked==true)
						{
							tipooperacion=0;
						}
						else
						{
							tipooperacion=1;
						}
						// Div donde se van a cargar los resultados
						divgrid = document.getElementById('asignacion');
						// Instancia del Objeto AJAX
						ajax=objetoAjax();
						// Pagina donde están los métodos para buscar y pintar los resultados
						ajax.open("POST","class_folder/sigesp_sob_c_aprobacion_contrato_ajax.php",true);
						ajax.onreadystatechange=function() {
							if (ajax.readyState==4) {
								divgrid.innerHTML = ajax.responseText
							}
						}
						ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
						// Enviar todos los campos a la pagina para que haga el procesamiento
						ajax.send("codcon="+codcon+"&feccondes="+feccondes+"&fecconhas="+fecconhas+"&tipooperacion="+tipooperacion+"&proceso=BUSCAR");
   					
					}
					else
					{
						alert("La Fecha de Aprobación no debe estar dentro del intervalo de fechas de Busquedas");
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

function ue_procesar()
{
	f=document.formulario;
	li_procesar=f.ejecutar.value;
	if (li_procesar==1)
   	{
		total=ue_calcular_total_fila_local("txtcodcon");
		f.totrow.value=total;
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
			f.action="sigesp_sob_p_aprobacion_contrato.php";
			f.submit();		
		}
		else
		{
			alert("Debe marcar lo(s) contrato(s) a aprobar");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
	
}
</script> 
</html>