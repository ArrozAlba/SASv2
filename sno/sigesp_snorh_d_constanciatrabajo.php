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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_constanciatrabajo.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	global $ls_sueint;
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
	unset($io_sno);
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
   		global $ls_codcont,$ls_descont,$ls_concont,$li_tamletcont,$ls_operacion,$li_intlincont,$ls_existe,$io_fun_nomina;
		global $li_marinfcont,$li_marsupcont,$ls_titcont,$ls_piepagcont,$li_tamletpiecont,$ls_nomrtf;
		
		$ls_codcont="";
		$ls_descont="";
		$ls_concont="";
		$li_tamletcont=12;
		$li_tamletpiecont=10;
		$li_intlincont=0;
		$li_marinfcont="3,00";
		$li_marsupcont="4,00";
		$ls_titcont="CONSTANCIA";
		$ls_piepagcont="";
		$ls_nomrtf="";
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
   		global $ls_codcont,$ls_descont,$ls_concont,$li_tamletcont,$li_intlincont,$li_marinfcont,$li_marsupcont;
		global $ls_titcont,$ls_piepagcont,$li_tamletpiecont,$ls_nomrtf;
		
		$ls_codcont=$_POST["txtcodcont"];
		$ls_descont=$_POST["txtdescont"];
		$ls_concont=$_POST["txtconcont"];
		$li_tamletcont=$_POST["txttamletcont"];
		$li_intlincont=$_POST["cmdintlincont"];
		$li_marinfcont=$_POST["txtmarinfcont"];
		$li_marsupcont=$_POST["txtmarsupcont"];
		$ls_titcont=$_POST["txttitcont"];
		$ls_piepagcont=$_POST["txtpiepagcont"];
		$li_tamletpiecont=$_POST["txttamletpiecont"];
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
<title >Definici&oacute;n de Constancia de Trabajo</title>
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
<?php 
	require_once("sigesp_snorh_c_constanciatrabajo.php");
	$io_constancia=new sigesp_snorh_c_constanciatrabajo();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=true;
			$ls_arcrtfcont=$HTTP_POST_FILES['txtarcrtfcont']['name'];
			if(strlen($ls_arcrtfcont)>50)
			{
        		$io_constancia->io_mensajes->message("La Longitud del Nombre del Archivo es mayor a 50 caracteres."); 
				$lb_valido=false;
			} 
			if($ls_arcrtfcont!="")
			{
				$ls_tiparc=$HTTP_POST_FILES['txtarcrtfcont']['type']; 
				$ls_tamarc=$HTTP_POST_FILES['txtarcrtfcont']['size']; 
				$ls_nomtemarc=$HTTP_POST_FILES['txtarcrtfcont']['tmp_name'];
				$ls_arcrtfcont=$io_constancia->uf_upload($ls_arcrtfcont,$ls_tiparc,$ls_tamarc,$ls_nomtemarc);
			}
			if($lb_valido)
			{
				$lb_valido=$io_constancia->uf_guardar($ls_existe,$ls_codcont,$ls_descont,$ls_concont,$li_tamletcont,$li_intlincont,
													  $li_marinfcont,$li_marsupcont,$ls_titcont,$ls_piepagcont,$li_tamletpiecont,
													  $ls_arcrtfcont,$la_seguridad);
			}
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_constancia->uf_delete_constanciatrabajo($ls_codcont,$la_seguridad);
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
	   print ('<tr>');
	   print ('<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>' );
	   print ('</tr>');
	}
	
	
  ?>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
  
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Constancia de Trabajo </td>
        </tr>
        <tr>
          <td width="130" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodcont" type="text" id="txtcodcont" size="5" maxlength="3" value="<?php print $ls_codcont;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,3);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtdescont" type="text" id="txtdescont" size="60" maxlength="120" value="<?php print $ls_descont;?>" onKeyUp="ue_validarcomillas(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tama&ntilde;o de Letra </div></td>
          <td height="22">
            <div align="left">
              <input name="txttamletcont" type="text" id="txttamletcont" value="<?php print $li_tamletcont;?>" onKeyUp="javascript: ue_validarnumero(this);" size="5" maxlength="2" style="text-align:right" >            
            </div></td>
          <td height="22"><div align="right">Tama&ntilde;o Letra Pie Pagina </div></td>
          <td height="22"><div align="left">
            <input name="txttamletpiecont" type="text" id="txttamletpiecont" value="<?php print $li_tamletpiecont;?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);" style="text-align:right" >
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
            <input name="txtmarsupcont" type="text" id="txtmarsupcont" style="text-align:right" value="<?php print $li_marsupcont;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
          <td width="162"><div align="right">Margen Inferior </div></td>
          <td width="229"><div align="left">
            <label>
            <input name="txtmarinfcont" type="text" id="txtmarinfcont" style="text-align:right" value="<?php print $li_marinfcont;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo</div></td>
          <td height="22" colspan="3"><label>
            <input name="txttitcont" type="text" id="txttitcont" value="<?php print $ls_titcont;?>" size="80" maxlength="250" onKeyUp="ue_validarcomillas(this);">
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
          <td height="22"><div align="right">Campos Personal </div></td>
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
				<option value="$ls_gerencia">GERENCIA</option>
                <option value="$li_horas_lab">HORAS LABORA</option>
                <option value="$ls_mes">MES</option>
                <option value="$ls_nacionalidad">NACIONALIDAD</option>
                <option value="$ls_nombres">NOMBRES</option>
                <option value="$li_sueldo">SUELDO</option>
                <option value="$li_inte_sueldo"><?php if ($ls_sueint==""){print "SUELDO INTEGRAL";}
													  else{print (strtoupper($ls_sueint));}?></option>
                <option value="$li_mensual_inte_sueldo"><?php if ($ls_sueint==""){print "SUELDO INTEGRAL MENSUAL";}
													          else{print (strtoupper($ls_sueint)." MENSUAL");}?></option>
                <option value="$li_prom_sueldo">SUELDO PROMEDIO</option>
				<option value="$li_salario_normal">SALARIO NORMAL</option>
                <option value="$li_mensual_prom_sueldo">SUELDO PROMEDIO MENSUAL</option>
                <option value="$ls_telefono_hab">TELEFONO HABITACIÓN</option>
                <option value="$ls_telefono_mov">TELEFONO MÓVIL</option>
                <option value="$ls_tipo_personal">TIPO PERSONAL</option>
                <option value="$ls_tipo_nomina">TIPO NÓMINA</option>
                <option value="$ls_unidad_administrativa">UNIDAD ADMINISTRATIVA</option>
				<option value="$ls_fecjub">FECHA DE JUBILACIÓN</option>
              </select>
              <a href="javascript: ue_ingresarcampo();"><img src="../shared/imagebank/arrow.gif" alt="Ingresar" width="13" height="13" border="0"></a> </div></td></tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Contenido</td>
          </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtconcont" cols="100" rows="20" id="txtconcont" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_concont;?></textarea>
          </div></td>
          </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4">Pie de Pagina </td>
        </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtpiepagcont" cols="100" rows="5" id="txtpiepagcont" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_piepagcont;?></textarea>
          </div></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22" colspan="3"><input name="operacion" type="hidden" id="operacion">
		  				  <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
						  <input name="hidsrh" type="hidden" id="hidsrh" value="<?php print $ls_valor;?>"></td>
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
		valor=f.hidsrh.value;	
		if (valor=='srh')
		{
		  f.action="sigesp_snorh_d_constanciatrabajo.php?valor="+valor;	  
		}
		else
		{
		  f.action="sigesp_snorh_d_constanciatrabajo.php";		
		}
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
		codcont = ue_validarvacio(f.txtcodcont.value);
		descont = ue_validarvacio(f.txtdescont.value);
		concont = ue_validarvacio(f.txtconcont.value);
		tamlet = ue_validarvacio(f.txttamletcont.value);
		tamletpie = ue_validarvacio(f.txttamletpiecont.value);
		if ((codcont!="")&&(descont!="")&&(concont!="")&&(tamlet!="")&&(tamlet!="0")&&(tamletpie!="")&&(tamletpie!="0"))
		{
			f.operacion.value="GUARDAR";
			valor=f.hidsrh.value;	
			if (valor=='srh')
			{
			  f.action="sigesp_snorh_d_constanciatrabajo.php?valor="+valor;	  
			}
			else
			{
			  f.action="sigesp_snorh_d_constanciatrabajo.php";		
			}
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
			codcont = ue_validarvacio(f.txtcodcont.value);
			if (codcont!="")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					valor=f.hidsrh.value;	
					if (valor=='srh')
					{
					  f.action="sigesp_snorh_d_constanciatrabajo.php?valor="+valor;	  
					}
					else
					{
					  f.action="sigesp_snorh_d_constanciatrabajo.php";		
					}
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
		window.open("sigesp_snorh_cat_constanciatrabajo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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
	ls_contenido=f.txtconcont.value;
	ls_contenido=ls_contenido+ls_campo; 
	f.txtconcont.value=ls_contenido;
}
</script> 
</html>