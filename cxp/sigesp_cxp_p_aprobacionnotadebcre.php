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
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_p_aprobacionnotadebcre.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funcin que limpia todas las variables necesarias en la pgina
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creacin: 17/03/2007								Fecha ltima Modificacin : 
		//////////////////////////////////////////////////////////////////////////////
   		global $io_fun_cxp,$ls_operacion,$ls_codtipsol,$ls_coduniadm,$ls_denuniadm,$ld_fecregdes,
			   $ld_fecreghas,$ld_fecaprsep,$li_totrow,$ls_numsol,$ls_numrecdoc,$ls_numncnd,$ls_tipoope;
		
		$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
		$ls_codtipsol="";
		$ls_tipoope  =0;
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_numrecdoc="";
		$ls_numsol="";
		$ls_numncnd="";
		$ld_fecregdes=date("01/m/Y");
		$ld_fecreghas=date("d/m/Y");
		$ld_fecaprsep=date("d/m/Y");
		$li_totrow=0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funcin que carga todas las variables necesarias en la pgina
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creacin: 17/03/2007								Fecha ltima Modificacin : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_totrow,$ls_tipope,$ld_fecapro;
		
		$li_totrow = $_POST["totrow"];
		$ls_tipope = $_POST["rdtipooperacion"];
		$ld_fecapro  =$_POST["txtfecapro"];
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
<title >Aprobaci&oacute;n de Notas de D&eacute;bito / Cr&eacute;dito</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("class_folder/sigesp_cxp_c_aprobacionnotas.php");
	$io_cxp=new sigesp_cxp_c_aprobacionnotas("../");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_mensajes=new class_mensajes();		
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
					$ls_numsol=$io_fun_cxp->uf_obtenervalor("txtnumsol".$li_i,"");
					$ls_numrecdoc=$io_fun_cxp->uf_obtenervalor("txtnumrecdoc".$li_i,"");
					$ls_numncnd=$io_fun_cxp->uf_obtenervalor("txtnumncnd".$li_i,"");
					$ld_fecregsol=$io_fun_cxp->uf_obtenervalor("txtfecregsol".$li_i,"");
					$ls_codope=$io_fun_cxp->uf_obtenervalor("txtcodope".$li_i,"");
					$ls_codtipdoc=$io_fun_cxp->uf_obtenervalor("txtcodtipdoc".$li_i,"");
					switch ($ls_tipope)
					{
						case 0:
							$lb_valido=$io_fecha->uf_comparar_fecha($ld_fecregsol,$ld_fecapro);
							if($lb_valido)
							{
								$lb_existe=$io_cxp->uf_validar_estatus_nota($ls_numsol,$ls_numrecdoc,$ls_numncnd,$ls_codope,$ls_codtipdoc,"1");
								if(!$lb_existe)
								{
									$lb_valido=$io_cxp->uf_validar_cuentas($ls_numsol,$ls_numrecdoc,$ls_numncnd,$ls_codope,$ls_codtipdoc);
									if($lb_valido)
									{
										$lb_valido=$io_cxp->uf_update_estatus_nota($ls_numsol,$ls_numrecdoc,$ls_numncnd,$ls_codope,$ls_codtipdoc,1,'E',$ld_fecapro,$la_seguridad);
									}
								}
								else
								{
									$io_mensajes->message("La Nota ".$ls_numncnd." ya esta aprobada");
								}
							}
							else
							{
								$io_mensajes->message("La Fecha de Registro de la Nota ".$ls_numncnd." debe ser menor a la fecha de Aprobacion");
							}							
							break;
		
						case 1:
							$lb_existe=$io_cxp->uf_validar_nota($ls_numsol,$ls_numrecdoc,$ls_numncnd,$ls_codope,$ls_codtipdoc);
							if($lb_existe)
							{
								$lb_valido=$io_cxp->uf_update_estatus_nota($ls_numsol,$ls_numrecdoc,$ls_numncnd,$ls_codope,$ls_codtipdoc,0,'R',$ld_fecapro,$la_seguridad);
							}
							else
							{
								$io_mensajes->message("La Nota ".$ls_numncnd." debe estar en Registrada");
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
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="807" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136">
      <p>&nbsp;</p>
        <table width="741" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="5" class="titulo-ventana">Aprobaci&oacute;n de Notas de D&eacute;bito / Cr&eacute;dito</td>
          </tr>
          <tr> 
            <td width="22%" height="22"><div align="right"></div></td>
            <td colspan="3"><div align="right">Fecha</div></td>
            <td width="18%"><input name="txtfecapro" type="text" id="txtfecapro" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecaprsep; ?>" size="15"  datepicker="true"></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Nro. Nota </div></td>
            <td height="22" colspan="4"><div align="left">
              <input name="txtnumncnd" type="text" id="txtnumncnd" onKeyUp="ue_validarnumero(this);" value="<?php print $ls_numncnd;?>" size="18" maxlength="15"> 
            </div></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Fecha de Registro </div></td>
            <td width="18%" height="22"><div align="left">Desde 
                <input name="txtfecregdes" type="text" id="txtfecregdes"  style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecregdes; ?>" size="13" maxlength="10"  datepicker="true">
            </div></td>
            <td width="21%">Hasta
              <input name="txtfecreghas" type="text" id="txtfecreghas" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecreghas; ?>" size="13"  datepicker="true">            </td>
            <td width="21%"><div align="right">Operacion</div></td>
            <td height="22"><select name="tiponota" id="tiponota">
              <option value="T">Todas</option>
              <option value="NC">Notas de Credito</option>
              <option value="ND">Notas de Debito</option>
            </select></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Orden de Pago </div></td>
            <td height="22" colspan="4"><label>
              <input name="txtnumsol" type="text" id="txtnumsol" value="<?php print $ls_numsol;?>" size="18" maxlength="15">
              <a href="javascript: ue_catalogo('sigesp_sep_cat_unidad_ejecutora.php?tipo=APROBACION');"></a></label></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Recepcion de Documento </div></td>
            <td height="22" colspan="4"><label>
              <input name="txtnumrecdoc" type="text" id="txtnumrecdoc" value="<?php print $ls_numrecdoc;?>" size="18" maxlength="15">
              <a href="javascript: ue_catalogo('sigesp_sep_cat_unidad_ejecutora.php?tipo=APROBACION');"></a></label></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Destino</div></td>
            <td height="22" colspan="4"><div align="left">
              <select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
                <option value="-" selected>-- Seleccione Uno --</option>
                <option value="P">PROVEEDOR</option>
                <option value="B">BENEFICIARIO</option>
              </select> 
              <input name="txtcodigo" type="text" id="txtcodigo" size="15" maxlength="10" readonly> 
              <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" size="50" maxlength="30" readonly>            
            </div></td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22" colspan="4"><div align="left">
              <input name="rdtipooperacion" type="radio" class="sin-borde" value="0" checked>
              Aprobaci&oacute;n
              <input name="rdtipooperacion" type="radio" class="sin-borde" value="1">
            Reversar Aprobaci&oacute;n </div></td>
          </tr>
        </table>
        <table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="748">
            <input name="operacion" type="hidden" id="operacion"></td>
          </tr>
          <tr>
            <td><div id="bienesservicios"></div></td>
          </tr>
        </table>        </td>
  </tr>
</table>
</form>   
<?php
	$io_cxp->uf_destructor();
	unset($io_cxp);
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
		valido=ue_validarcampo(f.txtfecregdes.value,"Los campos de fecha no deben estar vacios",f.txtfecregdes);
		if(valido)
		{
			valido=ue_validarcampo(f.txtfecreghas.value,"Los campos de fecha no deben estar vacios",f.txtfecreghas.value);
			if(valido)
			{
				valido=ue_validarcampo(f.txtfecapro.value,"Los campos de fecha no deben estar vacios",f.txtfecapro.value);
				if(valido)
				{
					fecreghas=f.txtfecreghas.value;
					fecaprsep=f.txtfecapro.value;
					valido=ue_comparar_fechas(fecreghas,fecaprsep);
					if(valido)
					{
						// Cargamos las variables para pasarlas al AJAX
						numsol=f.txtnumsol.value;
						numncnd=f.txtnumncnd.value;
						numrecdoc=f.txtnumrecdoc.value;
						fecregdes=f.txtfecregdes.value;
						tipproben=f.cmbtipdes.value;
						proben=f.txtcodigo.value;
						operacion=f.tiponota.value;
						if(document.formulario.rdtipooperacion[0].checked==true)
						{
							tipooperacion=0;
						}
						else
						{
							tipooperacion=1;
						}
						// Div donde se van a cargar los resultados
						divgrid = document.getElementById('bienesservicios');
						// Instancia del Objeto AJAX
						ajax=objetoAjax();
						// Pagina donde estn los mtodos para buscar y pintar los resultados
						ajax.open("POST","class_folder/sigesp_cxp_c_aprobacionnotas_ajax.php",true);
						ajax.onreadystatechange=function() {
							if (ajax.readyState==4) {
								divgrid.innerHTML = ajax.responseText
							}
						}
						ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
						// Enviar todos los campos a la pagina para que haga el procesamiento
						ajax.send("tiponota="+operacion+"&numncnd="+numncnd+"&numrecdoc="+numrecdoc+"&numsol="+numsol+"&fecregdes="+fecregdes+"&fecreghas="+fecreghas+"&proben="+proben+"&tipproben="+tipproben+"&tipooperacion="+tipooperacion+"&proceso=BUSCAR");
   					
					}
					else
					{
						alert("La Fecha de Aprobacion no debe estar dentro del intervalo de fechas de Busquedas");
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
		total=ue_calcular_total_fila_local("txtnumsol");
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
			f.action="sigesp_cxp_p_aprobacionnotadebcre.php";
			f.submit();		
		}
		else
		{
			alert("Debe marcar la(s) solicitud(es) a aprobar");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
	
}
function ue_cambiardestino()
{
	f=document.formulario;
	f.txtcodigo.value="";
	f.txtnombre.value="";
	tipdes=ue_validarvacio(f.cmbtipdes.value);
	if(tipdes!="-")
	{
		if(tipdes=="P")
		{
			window.open("sigesp_cxp_cat_proveedor.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			window.open("sigesp_cxp_cat_beneficiario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}	
	}
}

function ue_chequear_all()
{
	  f=document.formulario;
	  total=f.totrow.value;
  
	  if(f.chkall.checked)
	  {
		  for(i=1;i<=total;i++)	
		  {			
			eval("f.chkaprobacion"+i+".checked=true");
		  }		  
	 }
	 else
	 {
		 for(i=1;i<=total;i++)	
		  {
			eval("f.chkaprobacion"+i+".checked=false");
		  }		  
	 }
}
</script> 
</html>