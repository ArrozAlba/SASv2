<?Php
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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_partida.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Partidas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
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
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>


<?php
	require_once("class_folder/sigesp_sob_c_partida.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_class_mensajes.php");
	$io_mensaje=new sigesp_sob_class_mensajes();
	$io_partida=new sigesp_sob_c_partida();
	$is_msg=new class_mensajes();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codpar=$_POST["txtcodpar"];
		$ls_nompar=$_POST["txtnompar"];
		$ld_prepar=$_POST["txtprepar"];
		$ls_coduni=$_POST["txtcoduni"];
		$ls_nomuni=$_POST["txtnomuni"];
		$ls_codcovpar=$_POST["txtcodcovpar"];
		$ls_catpar=$_POST["txtcatpar"];
		$ls_codcatpar=$_POST["txtcodcatpar"];
		$ls_status=$_POST["hidstatus"];
	}
	else
	{
		$ls_operacion="ue_nuevo";
		$ls_codpar="";
		$ls_nompar="";
		$ld_prepar="0,00";
		$ls_coduni="";
		$ls_nomuni="";
		$ls_codcovpar="";
		$ls_catpar="";
		$ls_codcatpar="";
		$ls_status="";
	}
	
	/*Cuando la operacion es UE_NUEVO*/
	if($ls_operacion=="ue_nuevo")
	{
		require_once("../shared/class_folder/class_funciones_db.php");
		require_once ("../shared/class_folder/sigesp_include.php");		
		$io_include=new sigesp_include();
		$io_connect=$io_include->uf_conectar();
		$io_funcdb=new class_funciones_db($io_connect);
		$ls_empresa=$_SESSION["la_empresa"];
	//	$ls_codpar=$io_funcdb->uf_generar_codigo(true,$ls_empresa["codemp"],"sob_partida","codpar",10);
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_codpar= $io_keygen->uf_generar_numero_nuevo("SOB","sob_partida","codpar","SOB",10,"","","");
		$ls_nompar="";
		$ld_prepar="0,00";
		$ls_coduni="";
		$ls_nomuni="";
		$ls_codcovpar="";
		$ls_catpar="";
		$ls_codcatpar="";
		$ls_status="";
		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$ls_codparaux=$ls_codpar;
		$lb_valido=$io_partida->uf_guardar_partida($ls_codpar,$ls_nompar,$ls_coduni,$ld_prepar,$ls_codcovpar,$ls_codcatpar,$ls_status,$la_seguridad);
		                        
		if ($lb_valido===true)
		{
			if($ls_codparaux!=$ls_codpar)
			{
				$is_msg->message("Se le asigno un nuevo numero ".$ls_codpar);
			}
			$ls_codpar="";
			$ls_nompar="";
			$ld_prepar="0,00";
			$ls_coduni="";
			$ls_nomuni="";
			$ls_codcovpar="";
			$ls_catpar="";
			$ls_codcatpar="";
			$is_msg->message($io_partida->is_msg_error);
		}
		else
		{
			if($lb_valido===0)
			{
				$ls_codpar="";
				$ls_nompar="";
				$ld_prepar="0,00";
				$ls_coduni="";
				$ls_nomuni="";
				$ls_catpar="";
				$ls_codcovpar="";
				$ls_codcatpar="";
			}				
			else
			{			
				$is_msg->message($io_partida->is_msg_error);				
			}
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_partida->uf_eliminar_partida($ls_codpar,$la_seguridad);			
		if ($lb_valido===true)
		{
			$ls_codpar="";
			$ls_nompar="";
			$ld_prepar="0,00";
			$ls_coduni="";
			$ls_nomuni="";
			$ls_catpar="";
			$ls_codcovpar="";
			$ls_codcatpar="";
			$io_mensaje->eliminar();
		}		
		else
		{
			if(!$lb_valido===0)
				$io_mensaje->error_eliminar();
		}
	}
	
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
    <table width="518" height="239" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="237"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Partidas </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcodpar" type="text" style="text-align:center " id="txtcodpar" value="<?php print  $ls_codpar; ?>" size="10" maxlength="10" readonly="true">
                  <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion; ?>">
				  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status; ?>">
              </td>
            </tr>
            <tr align="left">
              <td height="47" align="right"><span class="style2">Descripci&oacute;n</span></td>
              <td><textarea name="txtnompar" cols="50" rows="2" wrap="VIRTUAL" id="txtnompar" onKeyPress="return(validaCajas(this,'x',event,255))" onKeyDown="textCounter(this,255)"><?php print $ls_nompar ?></textarea></td>
            </tr>
            <tr>
              <td height="26"><div align="right">Unidad de Medida</div></td>
              <td><input name="txtcoduni" type="text" id="txtcoduni" value="<?php print  $ls_coduni; ?>" size="3" maxlength="3"  style="text-align:center "readonly="true">
              <a href="javascript:ue_catunidades();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtnomuni" readonly="true" id="txtnomuni" value="<?php print $ls_nomuni; ?>" type="text" size="50" maxlength="50" class="sin-borde"></td>
            </tr>
            <tr>
              <td height="23" align="right">Categor&iacute;a              </td>
              <td ><input name="txtcodcatpar" type="text" id="txtcodcatpar" value="<?php print $ls_codcatpar; ?>" size="4" maxlength="4" style="text-align:center">                <a href="javascript:ue_catcategoriapartida();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>                <input name="txtcatpar" type="text" id="txtcatpar" value="<?php print $ls_catpar?>" size="50" maxlength="254" class="sin-borde">              </td>
            </tr>
            <tr>
              <td height="23" align="right">Precio Unitario </td>
              <td ><input name="txtprepar" type="text" id="txtprepar" style="text-align:right " onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_prepar?>" size="22" maxlength="22">              </td>
            </tr>
            <tr>
              <td height="23"><div align="right">C&oacute;digo COVENIN </div></td>
              <td><input name="txtcodcovpar" type="text" id="txtcodcovpar" value="<?php print $ls_codcovpar;?>" size="15" maxlength="15" onKeyPress="return(validaCajas(this,'x',event,15))"></td>
            </tr>
            <tr>
              <td height="8">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtcodpar.value="";
		f.txtnompar.value="";
		f.txtprepar.value="";
		f.txtcoduni.value="";
		f.txtnomuni.value="";
		f.txtnompar.focus(true);
		f.action="sigesp_sob_d_partida.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_guardar()
{

	var resul="";
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{			   
		 with (form1)
		   {
		   if (ue_valida_null(txtcodpar,"Código")==false)
					{
					  txtcodpar.focus();
					}
				 else
					{ 
					  resul=rellenar_cad(document.form1.txtcodpar.value,3);
					  if (ue_valida_null(txtnompar,"Nombre")==false)
						 {
						   txtnompar.focus();
						 }
						 else
						 {
							if (ue_valida_null(txtcoduni,"Unidad de Medida")==false)
							{
								txtcoduni.focus();
							}
							 else
							 {
							   f=document.form1;
							   f.operacion.value="ue_guardar";
							   f.action="sigesp_sob_d_partida.php";
							   f.submit();
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
			
function ue_eliminar()
{
	var borrar="";
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if (f.txtcodpar.value=="")
		{
		 alert("No ha seleccionado ningún registro para eliminar !!!");
		}
		else
		{
			borrar=confirm("¿ Esta seguro de eliminar este registro ?");
			if (borrar==true)
			   { 
				 f=document.form1;
				 f.operacion.value="ue_eliminar";
				 f.action="sigesp_sob_d_partida.php";
				 f.submit();
			   }
			else
			   { 
				 f=document.form1;
				 f.action="sigesp_sob_d_partida.php";
				 alert("Eliminación Cancelada !!!");
				 f.txtcodpar.value="";
				 f.txtnompar.value="";
				 f.txtprepar.value="";
				 f.operacion.value="";
				 f.txtcoduni.value="";
				 f.txtnomuni.value="";
				 f.submit();
			   }
		}	   
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function EvaluateText(cadena, obj)
{ 
opc = false; 
	
	if (cadena == "%d")  
	  if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
	  opc = true; 
	if (cadena == "%f"){ 
	 if (event.keyCode > 47 && event.keyCode < 58) 
	  opc = true; 
	 if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
	  if (event.keyCode == 46) 
	   opc = true; 
	} 
	 if (cadena == "%s") // toma numero y letras
	 if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
	  opc = true; 
	 if (cadena == "%c") // toma numero y punto
	 if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
	  opc = true; 
	if(opc == false) 
	 event.returnValue = false; 
   } 

function rellenar_cad(cadena,longitud)
{
var mystring=new String(cadena);
cadena_ceros="";
lencad=mystring.length;

	total=longitud-lencad;
	for (i=1;i<=total;i++)
		{
		  cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		document.form1.txtcodpar.value=cadena;
}

function ue_buscar()
{
	f=document.form1;
	f.operacion.value="";
	var hidopener=""			
	pagina="sigesp_cat_partida.php?hidopener="+hidopener;
	popupWin(pagina,"catalogo",730,400);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no,top=0,left=0,status=yes");
}

function ue_catunidades()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_unidades.php";
	popupWin(pagina,"catalogo",650,300);
//	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=300,resizable=yes,location=no");
}

function ue_cargarunidades(coduni,codtun,nomtun,nomuni,desuni)
{
	f=document.form1;
	f.txtcoduni.value=coduni;
	f.txtnomuni.value=nomuni;
}

function ue_catcategoriapartida()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_categoriapartida.php";
	popupWin(pagina,"catalogo",600,300);
//	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=300,resizable=yes,location=no");
}

function ue_cargarcategoriapartida(codigo,descripcion)
{
	f=document.form1;
	f.txtcatpar.value=descripcion;
	f.txtcodcatpar.value=codigo;	
}

function ue_cargarpartida(codigo,nombre,descripcion,codunidad,nomunidad,prepar,codcovpar,codcatpar,descatpar)
{
	f=document.form1;
	f.txtcoduni.value=codunidad;
	f.txtnomuni.value=nomunidad;
	f.txtcodpar.value=codigo;
	f.txtnompar.value=nombre;
	f.txtprepar.value=descripcion;
	f.txtprepar.value=prepar;
	f.txtcodcovpar.value=codcovpar;
	f.txtcodcatpar.value=codcatpar;
	f.txtcatpar.value=descatpar;
	f.hidstatus.value="C";
}
</script>
</html>