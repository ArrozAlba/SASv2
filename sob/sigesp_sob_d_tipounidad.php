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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_tipounidad.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definición de Tipos de Unidades</title>
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
<?Php

	require_once("class_folder/sigesp_sob_c_tipounidad.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_class_mensajes.php");
	$io_mensajes=new sigesp_sob_class_mensajes();
	$io_tipounidad=new sigesp_sob_c_tipounidad();
	$io_msg=new class_mensajes();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codtun=$_POST["txtcodtun"];
		$ls_nomtun=$_POST["txtnomtun"];
		$ls_destun=$_POST["txtdestun"];
		$ls_status=$_POST["hidstatus"]; 
		if(array_key_exists("chktipper",$_POST))
			$ls_tipper=1;
		else
			$ls_tipper=0;
		
	}
	else
	{
		$ls_operacion="ue_nuevo";
		$ls_codtun="";
		$ls_nomtun="";
		$ls_destun="";
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
//		$ls_codtun=$io_funcdb->uf_generar_codigo(false,0,"sob_tipounidad","codtun");
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_codtun= $io_keygen->uf_generar_numero_nuevo("SOB","sob_tipounidad","codtun","SOB",3,"","","");
		$ls_nomtun="";
		$ls_destun="";
		$ls_status="";
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$ls_codtunaux=$ls_codtun;
		$lb_valido=$io_tipounidad->uf_guardar_tipounidad($ls_codtun,$ls_nomtun,$ls_destun,$ls_status,$ls_tipper,$la_seguridad);
		if ($lb_valido===true)
		{
			if($ls_codtunaux!=$ls_codtun)
			{
				$io_msg->message("Se le asigno un nuevo numero ".$ls_codtun);
			}
			$ls_codtun="";
			$ls_nomtun="";
			$ls_destun="";
			$io_msg->message($io_tipounidad->is_msg_error);
		}else
		{
			if($lb_valido==0)
			{
				$ls_codtun="";
				$ls_nomtun="";
				$ls_destun="";
			}
			else
				$io_msg->message($io_tipounidad->is_msg_error);
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_tipounidad->uf_eliminar_tipounidad($ls_codtun,$la_seguridad);			
		if ($lb_valido===true)
		{
			$ls_codtun="";
			$ls_nomtun="";
			$ls_destun="";
			$io_mensajes->eliminar();			
		}		
		else
		{
			if (!$lb_valido===0)
				$io_mensajes->error_eliminar();
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

    <table width="518" height="173" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="171"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de  Tipos de Unidades </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcodtun" type="text" id="txtcodtun2" value="<?php print  $ls_codtun?>" size="3" maxlength="3" onKeyPress="EvaluateText('%f',this)" style="text-align:center " readonly="true">                  
                <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">  
			  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status; ?>" >              </td></tr>
            <tr align="left">
              <td height="22" align="right">&nbsp;</td>
              <td><input name="chktipper" type="checkbox" class="sin-borde" id="chktipper" value="1"> 
              Periodo de Tiempo </td>
            </tr>
            <tr align="left">
              <td height="22" align="right"><span class="style2">Nombre</span></td>
              <td><input name="txtnomtun" id="txtnomtun" value="<?php print $ls_nomtun?>" type="text" size="20" maxlength="20" onKeyPress="return(validaCajas(this,'x',event))"></td>
            </tr>
            <tr>
              <td height="35" align="right">Descripci&oacute;n</td>
              <td><textarea name="txtdestun" cols="53" rows="2" id="txtdestun" onKeyPress="return(validaCajas(this,'x',event))" onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" value="<?php print $ls_destun?>"><?php print $ls_destun?></textarea></td>
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
		f.txtcodtun.value="";
		f.txtnomtun.value="";
		f.txtdestun.value="";
		f.txtnomtun.focus(true);
		f.action="sigesp_sob_d_tipounidad.php";
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
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		var resul="";
		
		with (form1)
			{
			if (ue_valida_null(txtcodtun,"Código")==false)
				{
					txtcodtun.focus();
				}
			else
			{ 
				resul=rellenar_cad(document.form1.txtcodtun.value,3);
				if (ue_valida_null(txtnomtun,"Nombre")==false)
				{
					txtnomtun.focus();
				}
				else
				{
					f=document.form1;
					f.operacion.value="ue_guardar";
					f.action="sigesp_sob_d_tipounidad.php";
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
		if (f.txtcodtun.value=="")
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
				f.action="sigesp_sob_d_tipounidad.php";
				f.submit();
			}
			else
			{ 
				f=document.form1;
				f.action="sigesp_sob_d_tipounidad.php";
				alert("Eliminación Cancelada !!!");
				f.txtcodtun.value="";
				f.txtnomtun.value="";
				f.txtdestun.value="";
				f.operacion.value="";
				f.submit();
			}
		}	
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}   
}

function aceptar(c,d)
{
	f=document.form1;
	f.txtcodigo.value=c;
	f.txtdenominacion.value=d;
	f.txtdenominacion.focus(true);
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
	document.form1.txtcodtun.value=cadena;
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";			
		pagina="sigesp_cat_tipounidades.php";
		popupWin(pagina,"catalogo",560,200);
		//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cargartipounidad(codigo,nombre,descripcion,tipper)
{
	f=document.form1;
	f.txtcodtun.value=codigo;
	f.txtnomtun.value=nombre;
	f.txtdestun.value=descripcion;
	f.hidstatus.value="C";
	if(tipper==1)
	{
		f.chktipper.checked=true;
	}
	else
	{
		f.chktipper.checked=false;
	}
}
</script>
</html>