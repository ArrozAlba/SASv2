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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_documentos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_coddoc,$ls_desdoc,$ls_condoc,$li_tamletdoc,$ls_operacion,$li_intlindoc,$ls_existe,$io_fun_sob;
		global $li_marinfdoc,$li_marsupdoc,$ls_titdoc,$ls_piepagdoc,$li_tamletpiedoc,$ls_nomrtf;
		
		$ls_coddoc="";
		$ls_desdoc="";
		$ls_condoc="";
		$li_tamletdoc=12;
		$li_tamletpiedoc=10;
		$li_intlindoc=0;
		$li_marinfdoc="3,00";
		$li_marsupdoc="4,00";
		$ls_titdoc="";
		$ls_piepagdoc="";
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
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_coddoc,$ls_desdoc,$ls_condoc,$li_tamletdoc,$li_intlindoc,$li_marinfdoc,$li_marsupdoc;
		global $ls_titdoc,$ls_piepagdoc,$li_tamletpiedoc,$ls_nomrtf,$ls_tipdoc;
		
		$ls_coddoc=$_POST["txtcoddoc"];
		$ls_desdoc=$_POST["txtdesdoc"];
		$ls_condoc=$_POST["txtcondoc"];
		$li_tamletdoc=$_POST["txttamletdoc"];
		$li_intlindoc=$_POST["cmdintlindoc"];
		$li_marinfdoc=$_POST["txtmarinfdoc"];
		$li_marsupdoc=$_POST["txtmarsupdoc"];
		$ls_titdoc=$_POST["txttitdoc"];
		$ls_piepagdoc=$_POST["txtpiepagdoc"];
		$li_tamletpiedoc=$_POST["txttamletpiedoc"];
		$ls_nomrtf=$_POST["txtnomrtf"];
		$ls_tipdoc=$_POST["cmbtipdoc"];
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
<title >Definici&oacute;n de Documentos</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_sob_c_documento.php");
	$io_constancia=new sigesp_sob_c_documento();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
			$io_keygen= new sigesp_c_generar_consecutivo();
			$ls_coddoc= $io_keygen->uf_generar_numero_nuevo("SOB","sob_documento","coddoc","SOB",3,"","","");
		break;
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=true;
			$ls_arcrtfdoc=$HTTP_POST_FILES['txtarcrtfdoc']['name'];
			if(strlen($ls_arcrtfdoc)>50)
			{
        		$io_constancia->io_mensajes->message("La Longitud del Nombre del Archivo es mayor a 50 caracteres."); 
				$lb_valido=false;
			} 
			if($ls_arcrtfdoc!="")
			{
				$ls_tiparc=$HTTP_POST_FILES['txtarcrtfdoc']['type']; 
				$ls_tamarc=$HTTP_POST_FILES['txtarcrtfdoc']['size']; 
				$ls_nomtemarc=$HTTP_POST_FILES['txtarcrtfdoc']['tmp_name'];
				$ls_arcrtfdoc=$io_constancia->uf_upload($ls_arcrtfdoc,$ls_tiparc,$ls_tamarc,$ls_nomtemarc);
			}
			if($lb_valido)
			{
				$lb_valido=$io_constancia->uf_guardar($ls_existe,$ls_coddoc,$ls_desdoc,$ls_condoc,$li_tamletdoc,$li_intlindoc,
													  $li_marinfdoc,$li_marsupdoc,$ls_titdoc,$ls_piepagdoc,$li_tamletpiedoc,
													  $ls_arcrtfdoc,$ls_tipdoc,$la_seguridad);
			}
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_constancia->uf_delete_documento($ls_coddoc,$la_seguridad);
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
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
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
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="389"><div align="center"></div></td>
    <td class="toolbar" width="13"><div align="center"></div></td>
    <td class="toolbar" width="13"><div align="center"></div></td>
    <td class="toolbar" width="13"><div align="center"></div></td>
    <td class="toolbar" width="230">&nbsp;</td>
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
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Documentos </td>
        </tr>
        <tr>
          <td width="130" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcoddoc" type="text" id="txtcoddoc" size="5" maxlength="3" value="<?PHP print $ls_coddoc;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,3);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtdesdoc" type="text" id="txtdesdoc" size="60" maxlength="120" value="<?php print $ls_desdoc;?>" onKeyUp="ue_validarcomillas(this);">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tama&ntilde;o de Letra </div></td>
          <td height="22">
            <div align="left">
              <input name="txttamletdoc" type="text" id="txttamletdoc" value="<?php print $li_tamletdoc;?>" onKeyUp="javascript: ue_validarnumero(this);" size="5" maxlength="2" style="text-align:right" >            
            </div></td>
          <td height="22"><div align="right">Tama&ntilde;o Letra Pie Pagina </div></td>
          <td height="22"><div align="left">
            <input name="txttamletpiedoc" type="text" id="txttamletpiedoc" value="<?php print $li_tamletpiedoc;?>" size="5" maxlength="2" onKeyUp="javascript: ue_validarnumero(this);" style="text-align:right" >
         </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Interlineado</div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmdintlindoc" id="cmdintlindoc">
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
            <input name="txtmarsupdoc" type="text" id="txtmarsupdoc" style="text-align:right" value="<?php print $li_marsupdoc;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
          <td width="162"><div align="right">Margen Inferior </div></td>
          <td width="229"><div align="left">
            <label>
            <input name="txtmarinfdoc" type="text" id="txtmarinfdoc" style="text-align:right" value="<?php print $li_marinfdoc;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </label> 
            cm
</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo</div></td>
          <td height="22" colspan="3"><label>
            <input name="txttitdoc" type="text" id="txttitdoc" value="<?php print $ls_titdoc;?>" size="80" maxlength="250" onKeyUp="ue_validarcomillas(this);">
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
          <td height="22" colspan="3"><input name="txtarcrtfdoc" type="file" id="txtarcrtfdoc" size="50" maxlength="200"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Documento </div></td>
          <td height="22" colspan="3"><select name="cmbtipdoc" id="cmbtipdoc" onChange="javascript: ue_cargarcampos();">
            <option value="--" selected="selected">--Seleccione--</option>
            <option value="contrato">CONTRATO</option>
            <option value="carta_asignacion">CARTA ASIGNACION</option>
            <option value="actas">ACTAS</option>
          </select>
          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Campos Documento </div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmbcamper" id="cmbcamper">
                <option value="" selected>--Seleccione--</option>
              </select>
             <a href="javascript: ue_ingresarcampo();"><img src="../shared/imagebank/arrow.gif" alt="Ingresar" width="13" height="13" border="0"></a> </div></td></tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Contenido</td>
          </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtcondoc" cols="100" rows="20" id="txtcondoc" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_condoc;?></textarea>
          </div></td>
          </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4">Pie de Pagina </td>
        </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
            <textarea name="txtpiepagdoc" cols="100" rows="5" id="txtpiepagdoc" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_piepagdoc;?></textarea>
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
		f.action="sigesp_sob_d_documentos.php";
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
		coddoc = ue_validarvacio(f.txtcoddoc.value);
		desdoc = ue_validarvacio(f.txtdesdoc.value);
		condoc = ue_validarvacio(f.txtcondoc.value);
		tamlet = ue_validarvacio(f.txttamletdoc.value);
		tamletpie = ue_validarvacio(f.txttamletpiedoc.value);
		if ((coddoc!="")&&(desdoc!="")&&(condoc!="")&&(tamlet!="")&&(tamlet!="0")&&(tamletpie!="")&&(tamletpie!="0"))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sob_d_documentos.php";
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
			coddoc = ue_validarvacio(f.txtcoddoc.value);
			if (coddoc!="")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_sob_d_documentos.php";
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
		window.open("sigesp_sob_cat_documento.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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
	ls_contenido=f.txtcondoc.value;
	ls_contenido=ls_contenido+ls_campo; 
	f.txtcondoc.value=ls_contenido;
}

function ue_cargarcampos()
{
	f=document.form1;
	f.cmbcamper.length=0;
	f.cmbcamper.options[0]= new Option('--Seleccione--','');
	if(f.cmbtipdoc.value=="contrato")
	{
		f.cmbcamper.options[1]= new Option('OBRA','$ls_obra');
		f.cmbcamper.options[2]= new Option('CONTRATO','$ls_contrato');
		f.cmbcamper.options[3]= new Option('CONTRATISTA','$ls_nompro');
		f.cmbcamper.options[4]= new Option('FECHA DE CONTRATO','$ls_fechacontrato');
		f.cmbcamper.options[5]= new Option('FECHA DE INICIO DEL CONTRATO','$ld_fecinicontrato');
		f.cmbcamper.options[6]= new Option('MONTO','$li_monto');
		f.cmbcamper.options[7]= new Option('MONTO LIMITE','$li_monmaxcon');
		f.cmbcamper.options[8]= new Option('OBSERVACION','$ls_observacion');
		f.cmbcamper.options[9]= new Option('DIRECCION CONTRATISTA','$ls_dirpro');
		f.cmbcamper.options[10]= new Option('TELEFONO CONTRATISTA','$ls_telpro');
		f.cmbcamper.options[11]= new Option('RIF CONTRATISTA','$ls_rifpro');
		f.cmbcamper.options[12]= new Option('CAPITAL CONTRATISTA','$ls_capital');
	}
	if(f.cmbtipdoc.value=="actas")
	{
		f.cmbcamper.options[1]= new Option('OBRA','$ls_obra');
		f.cmbcamper.options[2]= new Option('CONTRATO','$ls_contrato');
		f.cmbcamper.options[3]= new Option('CONTRATISTA','$ls_nompro');
		f.cmbcamper.options[4]= new Option('FECHA DE CONTRATO','$ls_fechacontrato');
		f.cmbcamper.options[5]= new Option('ACTA','$ls_acta');
		f.cmbcamper.options[6]= new Option('MONTO','$li_motact');
		f.cmbcamper.options[7]= new Option('FECHA DEL ACTA','$ld_fecact');
		f.cmbcamper.options[8]= new Option('FECHA DE INICIO DEL ACTA','$ld_feciniact');
		f.cmbcamper.options[9]= new Option('OBSERVACION','$ls_obsact');
		f.cmbcamper.options[10]= new Option('DIRECCION CONTRATISTA','$ls_dirpro');
		f.cmbcamper.options[11]= new Option('TELEFONO CONTRATISTA','$ls_telpro');
		f.cmbcamper.options[12]= new Option('RIF CONTRATISTA','$ls_rifpro');
	}
}

</script> 
</html>