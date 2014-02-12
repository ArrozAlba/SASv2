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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_permiso.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_numper,$ld_feciniper,$ld_fecfinper,$li_numdiaper,$li_afevacper,$li_tipper, $ls_obsper,$la_tipper,$ls_existe,$ls_operacion,$ls_remper;
		global $io_fun_nomina;
		$li_numper=0;
		$ld_feciniper="dd/mm/aaaa";
		$ld_fecfinper="dd/mm/aaaa";
		$li_numdiaper=0;
		$li_afevacper="";
		$ls_remper="";
		$li_tipper="";
		$ls_obsper="";
		$la_tipper[0]="";
		$la_tipper[1]="";
		$la_tipper[2]="";
		$la_tipper[3]="";
		$la_tipper[4]="";
		$li_numhoras=0;
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
   		global $ls_codper, $ls_nomper, $li_numper, $ld_feciniper, $ld_fecfinper, $li_numdiaper, $li_afevacper;
		global $li_tipper, $ls_obsper, $io_fun_nomina, $ld_fecnacper, $ld_fecingper,$ls_remper, $li_numhoras;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ld_fecnacper=$_POST["txtfecnacper"];
		$ld_fecingper=$_POST["txtfecingper"];
		$li_numper=$_POST["txtnumper"];
		$ld_feciniper=$_POST["txtfeciniper"];
		$ld_fecfinper=$_POST["txtfecfinper"];
		$li_numdiaper=$_POST["txtnumdiaper"];
		$li_afevacper=$io_fun_nomina->uf_obtenervalor("chkafevacper","0");
		$ls_remper=$io_fun_nomina->uf_obtenervalor("chkremper","0");
		$li_tipper=$_POST["cmbtipper"];
		$ls_obsper=$_POST["txtobsper"];
		$li_numhoras=$_POST["txthoras"];
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
<title >Definici&oacute;n de Permiso Personal</title>
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
	require_once("sigesp_snorh_c_permiso.php");
	$io_permiso=new sigesp_snorh_c_permiso();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			$ld_fecnacper=$_GET["fecnacper"];
			$ld_fecingper=$_GET["fecingper"];
			$li_numhoras=0;
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_permiso->uf_guardar($ls_existe,$ls_codper,$li_numper,$ld_feciniper,$ld_fecfinper,$li_numdiaper,$li_afevacper,$li_tipper,$ls_obsper,$ls_remper,$li_numhoras,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$li_numhoras=0;
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5",$li_tipper,$la_tipper,5);
				if($li_afevacper==1)
				{
					$li_afevacper="checked";
				}
				else
				{
					$li_afevacper="";
				}
				if($ls_remper==1)
				{
					$ls_remper="checked";
				}
				else
				{
					$ls_remper="";
				}
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_permiso->uf_delete_permiso($ls_codper,$li_numper,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5",$li_tipper,$la_tipper,5);
				if($li_afevacper==1)
				{
					$li_afevacper="checked";
				}
				else
				{
					$li_afevacper="";
				}
				if($ls_remper==1)
				{
					$ls_remper="checked";
				}
				else
				{
					$ls_remper="";
				}
			}
			break;
	}
	$io_permiso->uf_destructor();
	unset($io_permiso);
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif"  title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
<table width="550" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="548">	<p>&nbsp;</p>
      <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="2"><div align="center">
            <input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Permiso</td>
      </tr>
      <tr>
        <td width="121" height="22"><div align="right"></div></td>
        <td width="300"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero</div></td>
        <td><div align="left">
            <input name="txtnumper" type="text" id="txtnumper" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $li_numper;?>" size="5" maxlength="2" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha Inicio </div></td>
        <td><div align="left">
            <input name="txtfeciniper" type="text" id="txtfeciniper" value="<?php print $ld_feciniper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha Fin</div></td>
        <td><div align="left">
            <input name="txtfecfinper" type="text" id="txtfecfinper" value="<?php print $ld_fecfinper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero de D&iacute;as </div></td>
        <td><div align="left">
            <input name="txtnumdiaper" type="text" id="txtnumdiaper" value="<?php print $li_numdiaper;?>" size="5" maxlength="3" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nro. de Horas</div></td>
        <td><div align="left">
            <input name="txthoras" type="text" id="txthoras" value="<?php print $li_numhoras;?>" size="8" maxlength="5" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">No afecta vacaciones</div></td>
        <td><div align="left">
            <input name="chkafevacper" type="checkbox" class="sin-borde" id="chkafevacper" value="1" <?php print $li_afevacper;?>>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Permiso Remunerado </div></td>
        <td><label>
          <input name="chkremper" type="checkbox" class="sin-borde" id="chkremper" value="1" <?php print $ls_remper;?>>
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo</div></td>
        <td><div align="left">
            <select name="cmbtipper" id="cmbtipper">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="1" <?php print $la_tipper[0];?>>Estudio</option>
              <option value="2" <?php print $la_tipper[1];?>>M&eacute;dico</option>
              <option value="3" <?php print $la_tipper[2];?>>Tr&aacute;mites</option>
              <option value="4" <?php print $la_tipper[3];?>>Otro</option>
              <option value="5" <?php print $la_tipper[4];?>>Reposo</option>
            </select>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Observaci&oacute;n</div></td>
        <td><div align="left">
            <textarea name="txtobsper" cols="55" rows="4" id="textarea"  onKeyUp="javascript: ue_validarcomillas(this);"></textarea>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"></div></td>
        <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe2" value="<?php print $ls_existe;?>">
            <input name="txtfecingper" type="hidden" id="txtfecingper" value="<?php print $ld_fecingper;?>">
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
		fecingper=ue_validarvacio(f.txtfecingper.value);	
		f.action="sigesp_snorh_d_permiso.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"&fecingper="+fecingper+"";
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
		numper = ue_validarvacio(f.txtnumper.value);
		f.txtfeciniper.value=ue_validarfecha(f.txtfeciniper.value);	
		feciniper = ue_validarvacio(f.txtfeciniper.value);
		f.txtfecfinper.value=ue_validarfecha(f.txtfecfinper.value);	
		fecfinper = ue_validarvacio(f.txtfecfinper.value);
		numdiaper = ue_validarvacio(f.txtnumdiaper.value);
		afevacper = ue_validarvacio(f.chkafevacper.value);
		tipper = ue_validarvacio(f.cmbtipper.value);
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		fecingper=ue_validarvacio(f.txtfecingper.value);
		if(!ue_comparar_fechas(feciniper,fecfinper))
		{
			alert("La fecha de Fin es menor que la de Inicio.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecnacper,feciniper))
		{
			alert("La fecha de Inicio del permiso es menor que la de Nacimiento del Personal.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecnacper,fecfinper))
		{
			alert("La fecha Fin del permiso es menor que la de Nacimiento del Personal.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecingper,feciniper))
		{
			alert("La fecha de Inicio del permiso es menor que la de Ingreso a la Institución.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecingper,fecfinper))
		{
			alert("La fecha Fin del permiso es menor que la de Ingreso a la Institución.");
			valido=false;
		}
		if(valido)
		{
			if ((codper!="")&&(numper!="")&&(feciniper!="")&&(fecfinper!="")&&(numdiaper!="")&&(afevacper!="")&&(tipper!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_permiso.php";
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
			numper = ue_validarvacio(f.txtnumper.value);
			if ((codper!="")&&(numper!="")&&(numper!="0"))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_permiso.php";
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

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
//	window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_permiso.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>