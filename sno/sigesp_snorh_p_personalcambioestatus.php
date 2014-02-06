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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_personalcambioestatus.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $li_implementarcodunirac;
	$li_implementarcodunirac=trim($io_sno->uf_select_config("SNO","CONFIG","CODIGO_UNICO_RAC","0","I"));
	unset($io_sno);
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estactper,$ld_fecegrper,$la_cauegrper,$ls_obsegrper,$ls_operacion,
		       $ls_codcausa,$ls_dencausa,$io_fun_nomina;
		
		$ls_codper="";
		$ls_nomper="";
		$ls_estactper="";
		$ld_fecegrper="dd/mm/aaaa";
		$la_cauegrper[0]="";
		$la_cauegrper[1]="";
		$la_cauegrper[2]="";
		$la_cauegrper[3]="";
		$la_cauegrper[4]="";
		$la_cauegrper[5]="";
		$la_cauegrper[6]="";
		$la_cauegrper[7]="";
		$la_cauegrper[8]="";
		$ls_obsegrper="";
		$ls_codcausa="";
		$ls_dencausa="";
		$ls_fecperi="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
   		global $ls_codper, $ls_nomper, $ls_estactper, $ls_estper, $ld_fecegrper, $ls_cauegrper;
		global $ls_obsegrper;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_estactper=$_POST["txtestactper"];
		$ls_estper=$_POST["cmbestper"];
		$ld_fecegrper="1900-01-01";
		$ls_fecperi="1900-01-01";
		$ls_cauegrper="";
		$ls_obsegrper="";
		if($ls_estper==3)
		{
			$ld_fecegrper=$_POST["txtfecegrper"];
			$ls_cauegrper=$_POST["cmbcauegrper"];
			$ls_obsegrper=$_POST["txtobsegrper"];
		}
		$ls_codcausa=$_POST["txtcodcausa"];
		$ls_dencausa=$_POST["txtdencausa"];
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
<title >Cambio de Estatus de Personal</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_personal.php");
	$io_personal=new sigesp_snorh_c_personal();
	require_once("../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	require_once("../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			uf_load_variables();
			$lb_valido=true;
			if (isset($_POST["txtcodcausa"]))
			{ 
				$ls_codcausa=$_POST["txtcodcausa"];
			}
			else
			{  
				$ls_codcausa="";
			}	
					
			if ((($ld_fecegrper!='1900-01-01')||($ld_fecegrper!='1900/01/01'))&&($ls_estper=="3"))
			{
				$ls_fecperi=$io_personal->uf_buscar_fecha_periodo($ls_codper);
				$ls_fecperi=substr($ls_fecperi,0,10);  
				$valido1=$io_fecha->uf_comparar_fecha($ls_fecperi,$ld_fecegrper); 
				if ((!$valido1)&&($ls_fecperi!=""))
				{
					$lb_valido=false;
					$ls_fecperi=$io_funciones->uf_convertirfecmostrar($ls_fecperi);
					$io_personal->io_mensajes->message("La Fecha de Egreso no puede ser menor al $ls_fecperi que es el utlimo Calculo de Nómina para el Personal con Código $ls_codper.");
				}				
			}
			
			if ($ls_estper=="3")
			{
				$codper="";
				$codper=$io_personal->uf_buscar_personal_nomina($ls_codper);
				if($codper!="")
				{
					$io_personal->io_mensajes->message("El Personal posee calculo generados en el periodo activo de la Nómina");
					$lb_valido=false;
				}
			}
			
			if ($lb_valido)
			{		
				$lb_valido=$io_personal->uf_update_personalestatus($ls_codper,$ls_estper,$ld_fecegrper,$ls_cauegrper,$ls_obsegrper,
																   $ls_codcausa,$li_implementarcodunirac,$la_seguridad);
			}
			
			if($lb_valido)
			{
				uf_limpiarvariables();
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("N-D-P-R-T-J-F",$ls_cauegrper,$la_cauegrper,7);			
			}
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
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
   <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	   print('<tr>');
	   print('<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>' );
	   print ('</tr>');
	}
	
  ?>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Ejecutar" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
     <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	    print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	   
	}
	else
	{
	 print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: close();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	
	}
	
  ?>
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
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Cambio de Estatus  de Personal </td>
        </tr>
        <tr>
          <td width="141" height="22"><div align="right"></div></td>
          <td width="453">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Personal</div></td>
          <td><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>" readonly>
            <a href="javascript: ue_buscarpersonal();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" maxlength="120" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Actual </div></td>
          <td><div align="left">
            <input name="txtestactper" type="text" id="txtestactper" value="<?php print $ls_estactper;?>" size="20" maxlength="20" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Nuevo </div></td>
          <td><div align="left">
            <select name="cmbestper" id="cmbestper">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="0">Pre-Ingreso</option>
              <option value="1">Activo</option>
              <option value="2">N/A</option>
              <option value="3">Egresado</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso </div></td>
          <td><div align="left">
            <input name="txtfecegrper" type="text" id="txtfecegrper" value="<?php print $ld_fecegrper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
            <input name="txtfecingper" type="hidden" id="txtfecingper">
            <input name="txtfecnacper" type="hidden" id="txtfecnacper">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Causa de Egreso </div></td>
          <td><div align="left">
            <select name="cmbcauegrper" id="cmbcauegrper">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="N" <?php print $la_cauegrper[0];?> >Ninguno</option>
              <option value="D" <?php print $la_cauegrper[1];?> >Despido</option>
			  <option value="1" <?php print $la_cauegrper[2];?> >Despido 102</option>
			  <option value="2" <?php print $la_cauegrper[3];?> >Despido 125</option>
              <option value="P" <?php print $la_cauegrper[4];?> >Pensionado</option>
              <option value="R" <?php print $la_cauegrper[5];?> >Renuncia</option>
              <option value="T" <?php print $la_cauegrper[6];?> >Traslado</option>
              <option value="J" <?php print $la_cauegrper[7];?> >Jubilado</option>
              <option value="F" <?php print $la_cauegrper[8];?> >Fallecido</option>
            </select>
          </div></td>
        </tr>
        <tr>
           <td height="22"><div align="right">Causales</div></td>
           <td height="20" colspan="2"><div align="left">
            <input name="txtcodcausa" type="text" id="txtcodcausa" value="<?php print $ls_codcausa;?>" size="5" maxlength="15" onKeyUp="" readonly>
            <a href="javascript: ue_buscarcausa();"><img id="causa" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>&nbsp;
            <input name="txtdencausa" type="text" class="sin-borde" id="txtdencausa" value="<?php print $ls_dencausa;?>" size="60" maxlength="50" readonly>
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td><div align="left">
            <textarea name="txtobsegrper" cols="80" rows="3" id="txtobsegrper" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_obsegrper;?></textarea>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
		      <input name="hidsrh" type="hidden" id="hidsrh" value="<?php print $ls_valor;?>">
		 </td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		valido=true;
		valor=f.cmbestper.selectedIndex;
		estactper=f.txtestactper.value;
		estper=ue_validarvacio(f.cmbestper.options[valor].text);
		valestper=ue_validarvacio(f.cmbestper.options[valor].value);
		codper = ue_validarvacio(f.txtcodper.value);
		f.txtfecegrper.value=ue_validarfecha(f.txtfecegrper.value);
		fecegrper = ue_validarvacio(f.txtfecegrper.value);
		cauegrper = ue_validarvacio(f.cmbcauegrper.value);
		obsegrper = ue_validarvacio(f.txtobsegrper.value);
		f.txtfecnacper.value=ue_validarfecha(f.txtfecnacper.value);	
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		f.txtfecingper.value=ue_validarfecha(f.txtfecingper.value);
		fecingper=ue_validarvacio(f.txtfecingper.value);		
		if(!((fecegrper=="01/01/1900")||(fecegrper=="1900-01-01"))&&(estper=="3"))
		{
			if(!ue_comparar_fechas(fecnacper,fecegrper))
			{
				alert("La fecha de Egreso de la institución es menor que la de Nacimiento.");
				valido=false;
			}
			if(!ue_comparar_fechas(fecingper,fecegrper))
			{
				alert("La fecha de Egreso de la institución es menor que la de Ingreso a la institución.");
				valido=false;
			}		
		}
		if(valido)
		{
			if((estactper==estper)||(valestper==""))
			{
				alert("No Cambió el estatus del personal");
			}
			else
			{
				if ((codper!="")&&(valestper!=""))
				{
					if((valestper=="3")&&(fecegrper!="")&&(cauegrper!="")&&(obsegrper!=""))
					{
						f.operacion.value="PROCESAR";
						valor=f.hidsrh.value;	
						if (valor=='srh')
						{
						  f.action="sigesp_snorh_p_personalcambioestatus.php?valor="+valor;	  
						}
						else
						{
						  f.action="sigesp_snorh_p_personalcambioestatus.php";
						}
						 f.submit();
					}
					else
					{
						if(valestper=="3")
						{
							alert("Debe ingresar la fecha, causa y observación del egreso.");
						}
						else
						{
							f.operacion.value="PROCESAR";
							valor=f.hidsrh.value;	
							if (valor=='srh')
							{
							  f.action="sigesp_snorh_p_personalcambioestatus.php?valor="+valor;	  
							}
							else
							{
							  f.action="sigesp_snorh_p_personalcambioestatus.php";
							}
							f.submit();
						}
					}
				}
				else
				{
					alert("Debe seleccionar el personal.");
				}
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscarpersonal()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=egreso","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcausa()
{
	  if (document.images["causa"].style.visibility!="hidden")
	  {
		window.open("sigesp_snorh_cat_causa.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	  }
}

function ue_select_causa()
{
	f=document.form1;
	document.images["causa"].style.visibility="visible";
	document.form1.txtcodcausa.style.visibility="visible";
}

function ue_select_causa2()
{
	f=document.form1;
	document.images["causa"].style.visibility="hidden";
}

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>