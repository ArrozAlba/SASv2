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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_familiar.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
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
   		global $ls_cedfam,$ls_nomfam,$ls_apefam,$ld_fecnacfam,$la_sexfam,$la_nexfam,$ls_operacion,$ls_existe,$li_estfam;
		global $li_hcfam,$li_hcmfam,$li_hijesp,$li_bonjug,$io_fun_nomina, $ls_cedula;
		
		$ls_cedfam="";
		$ls_cedula="";
		$ls_nomfam="";
		$ls_apefam="";
		$ld_fecnacfam="dd/mm/aaaa";
		$la_sexfam[0]="";
		$la_sexfam[1]="";
		$la_nexfam[0]="";
		$la_nexfam[1]="";
		$la_nexfam[2]="";
		$la_nexfam[3]="";
		$li_estfam=0;
		$li_hcfam=0;
		$li_hcmfam=0;
		$li_hijesp=0;
		$li_bonjug=0;
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
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
   		global $ls_codper, $ls_nomper, $ls_cedfam,$ls_nomfam,$ls_apefam,$ls_sexfam, $ld_fecnacfam,$ls_nexfam;
		global $ld_fecnacper,$li_estfam,$li_hcfam,$li_hcmfam,$li_hijesp,$li_bonjug,$io_fun_nomina,$ls_cedula;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ld_fecnacper=$_POST["txtfecnacper"];
		$ls_cedfam=$_POST["txtcedfam"];
		$ls_nomfam=$_POST["txtnomfam"];
		$ls_apefam=$_POST["txtapefam"];
		$ls_sexfam=$_POST["cmbsexfam"];
		$ld_fecnacfam=$_POST["txtfecnacfam"];
		$ls_nexfam=$_POST["cmbnexfam"];
		$ls_cedula=$_POST["txtcedula"];
		$li_estfam=$io_fun_nomina->uf_obtenervalor("chkestfam","0");
		$li_hcfam=$io_fun_nomina->uf_obtenervalor("chkhcfam","0");
		$li_hcmfam=$io_fun_nomina->uf_obtenervalor("chkhcmfam","0");
		$li_hijesp=$io_fun_nomina->uf_obtenervalor("chkhijesp","0");
		$li_bonjug=$io_fun_nomina->uf_obtenervalor("chkbonjug","0");
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
<title >Definici&oacute;n de Familiar</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/nomina.css" rel="stylesheet" type="text/css">
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
	require_once("sigesp_snorh_c_familiar.php");
	$io_familiar=new sigesp_snorh_c_familiar();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			$ld_fecnacper=$_GET["fecnacper"];
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_familiar->uf_guardar($ls_existe,$ls_codper,$ls_cedfam,$ls_nomfam,$ls_apefam,$ls_sexfam,
												$ld_fecnacfam,$ls_nexfam,$li_estfam,$li_hcfam,$li_hcmfam,$li_hijesp,$li_bonjug,
												$ls_cedula,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("F-M",$ls_sexfam,$la_sexfam,2);
				$io_fun_nomina->uf_seleccionarcombo("C-H-P-E",$ls_nexfam,$la_nexfam,4);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_familiar->uf_delete_familiar($ls_codper,$ls_cedfam,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("F-M",$ls_sexfam,$la_sexfam,2);
				$io_fun_nomina->uf_seleccionarcombo("C-H-P-E",$ls_nexfam,$la_nexfam,4);
			}
			break;
	}
	$io_familiar->uf_destructor();
	unset($io_familiar);
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif"  title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_personal.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Familiar </td>
        </tr>
        <tr>
          <td width="166" height="22">&nbsp;</td>
          <td width="378">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcedfam" type="text" id="txtcedfam" value="<?php print $ls_cedfam;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&eacute;dula</div></td>
          <td><input name="txtcedula" type="text" id="txtcedula" value="<?php print $ls_cedula;?>" size="13" maxlength="10" onKeyUp="javascript: ue_validarnumero(this);"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre</div></td>
          <td><div align="left">
            <input name="txtnomfam" type="text" id="txtnomfam" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_nomfam;?>" size="63" maxlength="60">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido</div></td>
          <td><div align="left">
            <input name="txtapefam" type="text" id="txtapefam" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_apefam;?>" size="63" maxlength="60">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">G&eacute;nero</div></td>
          <td><div align="left">
            <select name="cmbsexfam" id="cmbsexfam">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="F" <?php print $la_sexfam[0];?>>Femenino</option>
              <option value="M" <?php print $la_sexfam[1];?>>Masculino</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha Nacimiento </div></td>
          <td><div align="left">
            <input name="txtfecnacfam" type="text" id="txtfecnacfam" value="<?php print $ld_fecnacfam;?>" size="15" maxlength="10" datepicker="true" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nexo</div></td>
          <td><div align="left">
            <select name="cmbnexfam" id="cmbnexfam">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="C" <?php print $la_nexfam[0];?>>Conyuge</option>
              <option value="H" <?php print $la_nexfam[1];?>>Hijo</option>
              <option value="P" <?php print $la_nexfam[2];?>>Progenitor</option>
              <option value="E" <?php print $la_nexfam[3];?>>Hermano</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estudia</div></td>
          <td>
            <div align="left">
              <input name="chkestfam" type="checkbox" class="sin-borde" id="chkestfam" value="1" <?php if($li_estfam==1){ print "checked"; } ?> >
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">HC</div></td>
          <td><div align="left">
            <input name="chkhcfam" type="checkbox" class="sin-borde" id="chkhcfam" value="1"  <?php if($li_hcfam==1){ print "checked"; } ?> >
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">HCM (Poliza de Maternidad) </div></td>
          <td><div align="left">
            <input name="chkhcmfam" type="checkbox" class="sin-borde" id="chkhcmfam" value="1" onchange="javascript:ue_hcm();"  <?php if($li_hcmfam==1){ print "checked"; } ?> >
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Hijo Especial</div></td>
          <td><div align="left">
            <input name="chkhijesp" type="checkbox" class="sin-borde" id="chkhijesp" value="1" onchange="javascript:ue_checkhijo('1');"  <?php if($li_hijesp==1){ print "checked"; } ?> >
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Bono Juguete</div></td>
          <td><div align="left">
            <input name="chkbonjug" type="checkbox" class="sin-borde" id="chkbonjug" value="1" onchange="javascript:ue_checkhijo('2');"  <?php if($li_bonjug==1){ print "checked"; } ?> >
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"> 
            <input name="txtfecnacper" type="hidden" id="txtfecnacper" value="<?php print $ld_fecnacper;?>"></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";	
		codper=ue_validarvacio(f.txtcodper.value);
		nomper=ue_validarvacio(f.txtnomper.value);	
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		f.action="sigesp_snorh_d_familiar.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_personal.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		codper = ue_validarvacio(f.txtcodper.value);
		cedfam = ue_validarvacio(f.txtcedfam.value);
		nomfam = ue_validarvacio(f.txtnomfam.value);
		apefam = ue_validarvacio(f.txtapefam.value);
		f.txtfecnacfam.value=ue_validarfecha(f.txtfecnacfam.value);	
		fecnacfam = ue_validarvacio(f.txtfecnacfam.value);
		fecnacper = ue_validarvacio(f.txtfecnacper.value);
		nexfam=ue_validarvacio(f.cmbnexfam.value);
		if(nexfam=="H")
		{
			if(!ue_comparar_fechas(fecnacper,fecnacfam))
			{
				alert("La fecha de Nacimiento del Hijo es menor que la de Nacimiento del Personal.");
				valido=false;
			}
		}
		if(nexfam=="P")
		{
			if(!ue_comparar_fechas(fecnacfam,fecnacper))
			{
				alert("La fecha de Nacimiento del Personal es menor que la del Progenitor.");
				valido=false;
			}
		}
		if(valido)
		{
			if ((codper!="")&&(cedfam!="")&&(nomfam!="")&&(apefam!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_familiar.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
	
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codper = ue_validarvacio(f.txtcodper.value);
			cedfam = ue_validarvacio(f.txtcedfam.value);
			if ((codper!="")&&(cedfam!=""))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_familiar.php";
					f.submit();
				}
			}
			else
			{
				alert("Debe buscar el registro a eliminar.");
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
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

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_familiar.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_hcm()
{
	f=document.form1;
	if((f.cmbsexfam.value!="F")||(f.cmbnexfam.value!="C"))
	{
		f.chkhcmfam.checked=false;
		alert("La poliza de maternidad es solo para las Conyugues");
	}
}

function ue_checkhijo(tipo)
{
	f=document.form1;
	if(f.cmbnexfam.value!="H")
	{
		
		alert("Esta opicón es solamente para para Hijos");
		if (tipo=='1')
		{
			f.chkhijesp.checked=false;
		}
		else if (tipo=='2')
		{
			f.chkbonjug.checked=false;
		}
	}
}


var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>