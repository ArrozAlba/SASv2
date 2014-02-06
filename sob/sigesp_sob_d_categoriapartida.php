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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_categoriapartida.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definici&oacute;n de Categor&iacute;as de Partidas</title>
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
    <td width="778" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
		<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras</td>
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

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?Php
	require_once("class_folder/sigesp_sob_c_categoriapartida.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_class_mensajes.php");
	$io_mensaje=new sigesp_sob_class_mensajes();
	$io_categoriapartida=new sigesp_sob_c_categoriapartida();
	$is_msg=new class_mensajes();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codpar=$_POST["txtcodpar"];		
		$ls_nompar=$_POST["txtnompar"];				
		$ls_status=$_POST["hidstatus"];				
	}
	else
	{
		$ls_operacion="ue_nuevo";
		$ls_codpar="";
		$ls_nompar="";
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
	//	$ls_codpar=$io_funcdb->uf_generar_codigo(false,0,"sob_categoriapartida","codcatpar",4);
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_codpar= $io_keygen->uf_generar_numero_nuevo("SOB","sob_categoriapartida","codcatpar","SOB",4,"","","");
		$ls_nompar="";
		$ls_status="";
		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$ls_codparaux=$ls_codpar;
		$lb_valido=$io_categoriapartida->uf_guardar_categoriapartida($ls_codpar,$ls_nompar,$ls_status,$la_seguridad);		
		if ($lb_valido===true)
		{
			if($ls_codparaux!=$ls_codpar)
			{
				$is_msg->message("Se le asigno un nuevo numero ".$ls_codpar);
			}
			$is_msg->message($io_categoriapartida->is_msg_error);
			$ls_codpar="";
			$ls_nompar="";
		}
		else
		{
			if($lb_valido===0)
			{
				$ls_codpar="";
				$ls_nompar="";				
			}				
			else
			{			
				$is_msg->message($io_categoriapartida->is_msg_error);				
			}
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_categoriapartida->uf_eliminar_categoriapartida($ls_codpar,$la_seguridad);			
		if ($lb_valido===true)
		{
			$ls_codpar="";
			$ls_nompar="";
			$io_mensaje->eliminar();
		}		
		else
		{
			if(!$lb_valido===0)
				$io_mensaje->error_eliminar();
		}
	}
	
?>

    <table width="518" height="161" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
<?
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
      <tr>
        <td width="516" height="159"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Categor&iacute;as de Partidas </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcodpar" type="text" style="text-align:center " id="txtcodpar" value="<?php print  $ls_codpar; ?>" size="4" maxlength="4" readonly="true">
                  <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion; ?>">
				  <input type="hidden" name="hidstatus" id="hidstatus"  value="<?php print $ls_status; ?>" ></td>
            </tr>
            <tr align="left">
              <td height="49" align="right"><span class="style2">Descripci&oacute;n</span></td>
              <td><textarea name="txtnompar" cols="50" rows="2" id="txtnompar"  onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" onKeyPress="return(validaCajas(this,'x',event,254))"><?php print $ls_nompar?></textarea></td>
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
	  f.txtnompar.focus(true);
	  f.action="sigesp_sob_d_categoriapartida.php";
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
var resul="";
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
		  if (ue_valida_null(txtnompar,"Descripción")==false)
			 {
			   txtnompar.focus();
			 }
			 else
			 {					 	
				   f=document.form1;
				   f.operacion.value="ue_guardar";
				   f.action="sigesp_sob_d_categoriapartida.php";
				   f.submit();			 
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
				 f.action="sigesp_sob_d_categoriapartida.php";
				 f.submit();
			   }
			else
			   { 
				 f=document.form1;
				 f.action="sigesp_sob_d_categoriapartida.php";
				 alert("Eliminación Cancelada !!!");
				 f.txtcodpar.value="";
				 f.txtnompar.value="";			
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
	pagina="sigesp_cat_categoriapartida.php";
	popupWin(pagina,"catalogo",600,200);
	//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=200,resizable=yes,location=no,top=0,left=0");
}

function ue_cargarcategoriapartida(codigo,descripcion)
{
	f=document.form1;
	f.txtcodpar.value=codigo;
	f.txtnompar.value=descripcion;	
	f.hidstatus.value="C";
}
</script>
</html>