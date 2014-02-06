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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_formatoreportes.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación :28/03/08 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codfor,$ls_desfor,$ls_confor,$li_tamletfor,$ls_operacion,$li_intlinfor,$ls_existe,$io_fun_sob;
		global $li_marinffor,$li_marsupfor,$ls_titfor,$ls_piepagfor,$li_tamletpiefor,$ls_nomrtf;
		
		$ls_codfor="";
		$ls_desfor="";
		$ls_confor="";
		$li_tamletfor=12;
		$li_tamletpiefor=10;
		$li_intlinfor=0;
		$li_marinffor="3,00";
		$li_marsupfor="4,00";
		$ls_titfor="FORMATO DE REPORTE";
		$ls_piepagfor="";
		$ls_nomrtf="";
		$ls_operacion=$io_fun_sob->uf_obteneroperacion();
		$ls_existe=$io_fun_sob->uf_obtenerexiste();
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
   		global $ls_codfor,$ls_desfor,$ls_confor,$li_tamletfor,$li_intlinfor,$li_marinffor,$li_marsupfor;
		global $ls_titfor,$ls_piepagfor,$li_tamletpiefor,$ls_nomrtf;
		
		$ls_codfor=$_POST["txtcodfor"];
		$ls_desfor=$_POST["txtdesfor"];
		$ls_confor=$_POST["txtconfor"];
		$li_tamletfor=$_POST["txttamletfor"];
		$li_intlinfor=$_POST["cmdintlinfor"];
		$li_marinffor=$_POST["txtmarinffor"];
		$li_marsupfor=$_POST["txtmarsupfor"];
		$ls_titfor=$_POST["txttitfor"];
		$ls_piepagfor=$_POST["txtpiepagfor"];
		$li_tamletpiefor=$_POST["txttamletpiefor"];
		$ls_nomrtf=$_POST["txtnomrtf"];
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
<title >Definici&oacute;n de Formatos para Reportes</title>
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
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
</head>
<body>
<?php 
	require_once("sigesp_sob_c_formatoreportes.php");
	$io_constancia=new sigesp_sob_c_formatoreportes();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=true;
			$ls_arcrtffor=$HTTP_POST_FILES['txtarcrtffor']['name'];
			if(strlen($ls_arcrtffor)>50)
			{
        		$io_constancia->io_mensajes->message("La Longitud del Nombre del Archivo es mayor a 50 caracteres."); 
				$lb_valido=false;
			} 
			if($ls_arcrtffor!="")
			{
				$ls_tiparc=$HTTP_POST_FILES['txtarcrtffor']['type']; 
				$ls_tamarc=$HTTP_POST_FILES['txtarcrtffor']['size']; 
				$ls_nomtemarc=$HTTP_POST_FILES['txtarcrtffor']['tmp_name'];
				$ls_arcrtffor=$io_constancia->uf_upload($ls_arcrtffor,$ls_tiparc,$ls_tamarc,$ls_nomtemarc);
			}
			if($lb_valido)
			{
				$lb_valido=$io_constancia->uf_guardar($ls_existe,$ls_codfor,$ls_desfor,$ls_confor,$li_tamletfor,$li_intlinfor,
													  $li_marinffor,$li_marsupfor,$ls_titfor,$ls_piepagfor,$li_tamletpiefor,
													  $ls_arcrtffor,$la_seguridad);
			}
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_constancia->uf_delete_formatoreporte($ls_codfor,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
	$io_constancia->uf_destructor();
	unset($io_constancia);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" enctype="multipart/form-data" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Formato para Reporte </td>
        </tr>
        <tr>
          <td width="130" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodfor" type="text" id="txtcodfor" size="5" maxlength="3" value="<?PHP print $ls_codfor;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,3);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtdesfor" type="text" id="txtdesfor" size="60" maxlength="120" value="<?php print $ls_desfor;?>" onKeyUp="ue_validarcomillas(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tipo de Documento</div></td>
          <td height="22"><select name="cmbtipo" id="cmbtipo">
            <option value="" selected>--Seleccione--</option>
			<option value="$ls_asignacion">Carta Asignación</option>
            <option value="$ls_contrato">Contrato</option>
            <option value="$ls_acta">Acta</option>
                                        </select></td>
          <td height="22">&nbsp;</td>
          <td height="22">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tama&ntilde;o de Letra </div></td>
          <td height="22">
            <div align="left">
              <input name="txttamletfor" type="text" id="txttamletfor" value="<?php print $li_tamletfor;?>" onKeyUp="javascript: ue_validarnumero(this);" size="5" maxlength="2" style="text-align:right" >            
            </div></td>
          <td height="22"><div align="right">Tama&ntilde;o Letra Pie Pagina </div></td>
          <td height="22"><div align="left">
            <input name="txttamletpiefor" type="text" id="txttamletpiefor" value="<?php print $li_tamletpiefor;?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);" style="text-align:right" >
         </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Interlineado</div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmdintlincont" id="cmdintlincont">
                <option value="1" selected>1</option>
                <option value="2">1.5</option>
                <option value="3">2</option>
              </select>
            </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Margen Superior </div></td>
          <td width="119" height="22"><div align="left">
            <label>
            <input name="txtmarsupfor" type="text" id="txtmarsupfor" style="text-align:right" value="<?php print $li_marsupfor;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
          <td width="162"><div align="right">Margen Inferior </div></td>
          <td width="229"><div align="left">
            <label>
            <input name="txtmarinffor" type="text" id="txtmarinffor" style="text-align:right" value="<?php print $li_marinffor;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo</div></td>
          <td height="22" colspan="3"><label>
            <input name="txttitfor" type="text" id="txttitfor" value="<?php print $ls_titfor;?>" size="80" maxlength="250" onKeyUp="ue_validarcomillas(this);">
            </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Plantilla rtf </div></td>
          <td height="22" colspan="3"><label>
            <input name="txtnomrtf" type="text" id="txtnomrtf" size="50" maxlength="60" value="<?php print $ls_nomrtf;?>" readonly>
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Actualizar Plantilla rtf </div></td>
          <td height="22" colspan="3"><input name="txtarcrtfcont" type="file" id="txtarcrtfcont" size="50" maxlength="200"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Campos en el Reporte</div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmbcamper" id="cmbcamper">
                <option value="" selected>--Seleccione--</option>
                <option value="$ls_ano">AÑO</option>
                <option value="$ls_apellidos">APELLIDOS</option>
                <option value="$ls_cargo">CARGO</option>
                <option value="$ls_cedula">CEDULA</option>
                <option value="$ls_dedicacion">DEDICACIÓN</option>
                <option value="$ls_dia">DIA</option>
                <option value="$ls_direccion">DIRECCION</option>
                <option value="$ls_edo_civil">EDO_CIVIL</option>
                <option value="$ls_ente">ENTE</option>
                <option value="$ld_fecha_ingreso">FECHA INGRESO INSTITUCIÓN</option>
                <option value="$ld_fecha_egreso">FECHA EGRESO INSTITUCIÓN</option>
                <option value="$ld_fecha_nacimiento">FECHA NACIMIENTO</option>
                <option value="$li_horas_lab">HORAS LABORA</option>
                <option value="$ls_mes">MES</option>
                <option value="$ls_nacionalidad">NACIONALIDAD</option>
                <option value="$ls_nombres">NOMBRES</option>
                <option value="$li_sueldo">SUELDO</option>
                <option value="$li_inte_sueldo">SUELDO INTEGRAL</option>
                <option value="$li_mensual_inte_sueldo">SUELDO INTEGRAL MENSUAL</option>
                <option value="$li_prom_sueldo">SUELDO PROMEDIO</option>
                <option value="$li_mensual_prom_sueldo">SUELDO PROMEDIO MENSUAL</option>
                <option value="$ls_telefono_hab">TELEFONO HABITACIÓN</option>
                <option value="$ls_telefono_mov">TELEFONO MÓVIL</option>
                <option value="$ls_tipo_personal">TIPO PERSONAL</option>
                <option value="$ls_tipo_nomina">TIPO NÓMINA</option>
                <option value="$ls_unidad_administrativa">UNIDAD ADMINISTRATIVA</option>
              </select>
              <a href="javascript: ue_ingresarcampo();"><img src="../shared/imagebank/arrow.gif" alt="Ingresar" width="13" height="13" border="0"></a> </div></td></tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Contenido</td>
          </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtconfor" cols="100" rows="20" id="txtconfor" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_confor;?></textarea>
          </div></td>
          </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4">Pie de Pagina </td>
        </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtpiepagfor" cols="100" rows="5" id="txtpiepagfor" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_piepagfor;?></textarea>
          </div></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22" colspan="3"><input name="operacion" type="hidden" id="operacion">
		  				  <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
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
		f.action="sigesp_sob_d_formatoreportes.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codfor = ue_validarvacio(f.txtcodfor.value);
		desfor = ue_validarvacio(f.txtdesfor.value);
		confor = ue_validarvacio(f.txtconfor.value);
		tamlet = ue_validarvacio(f.txttamletfor.value);
		tamletpie = ue_validarvacio(f.txttamletpiefor.value);
		if ((codfor!="")&&(desfor!="")&&(confor!="")&&(tamlet!="")&&(tamlet!="0")&&(tamletpie!="")&&(tamletpie!="0"))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sob_d_formatoreportes.php";
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

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codfor = ue_validarvacio(f.txtcodfor.value);
			if (codfor!="")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_sob_d_formatoreportes.php";
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
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_constanciatrabajo.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_cat_formatoreportes.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_ingresarcampo()
{
	f=document.form1;
	ls_campo=f.cmbcamper.value;
	ls_contenido=f.txtconfor.value;
	ls_contenido=ls_contenido+ls_campo; 
	f.txtconfor.value=ls_contenido;
}
</script> 
</html>