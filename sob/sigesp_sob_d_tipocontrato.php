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
	$io_fun_sob->uf_load_seguridad("SOB","sigesp_sob_d_tipocontrato.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Definición de Tipos de Contratos</title>
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
	require_once("class_folder/sigesp_sob_c_tipocontrato.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("class_folder/sigesp_sob_class_mensajes.php");
	$io_mensaje=new sigesp_sob_class_mensajes();
	$io_tipocontrato=new sigesp_sob_c_tipocontrato();
	$io_msg=new class_mensajes();
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codtco=$_POST["txtcodtco"];
		$ls_nomtco=$_POST["txtnomtco"];
		$ls_destco=$_POST["txtdestco"];
		$ls_status=$_POST["hidstatus"];
	}
	else
	{
		$ls_operacion="ue_nuevo";
		$ls_codtco="";
		$ls_nomtco="";
		$ls_destco="";
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
	//	$ls_codtco=$io_funcdb->uf_generar_codigo(false,0,"sob_tipocontrato","codtco",2);
		require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_codtco= $io_keygen->uf_generar_numero_nuevo("SOB","sob_tipocontrato","codtco","SOB",2,"","","");
		$ls_nomtco="";
		$ls_destco="";
		$ls_status="";		
	}
	elseif($ls_operacion=="ue_guardar")
	{
		$ls_codtcoaux=$ls_codtco;
		$lb_valido=$io_tipocontrato->uf_guardar_tipocontrato($ls_codtco,$ls_nomtco,$ls_destco,$ls_status,$la_seguridad);
		
		if ($lb_valido===true)
		{
			if($ls_codtcoaux!=$ls_codtco)
			{
				$io_msg->message("Se le asigno un nuevo numero ".$ls_codtco);
			}
			$ls_codtco="";
			$ls_nomtco="";
			$ls_destco="";
			$io_msg->message($io_tipocontrato->is_msg_error);
		}else
		{
			if($lb_valido===0)
			{
				$ls_codtco="";
				$ls_nomtco="";
				$ls_destco="";
			}
			else
			{
				$io_msg->message($io_tipocontrato->is_msg_error);
			}			
		}
	}
	elseif($ls_operacion=="ue_eliminar")
	{
		$lb_valido=$io_tipocontrato->uf_eliminar_tipocontrato($ls_codtco,$la_seguridad);			
		if ($lb_valido===true)
		{
			$io_mensaje->eliminar();
			$ls_codtco="";
			$ls_nomtco="";
			$ls_destco="";
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

    <table width="518" height="172" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="170"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td colspan="2" class="titulo-ventana">Definici&oacute;n de Tipos de Contratos </td>
            </tr>
            <tr>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
            <tr>
              <td width="134" height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td width="334" ><input name="txtcodtco" type="text" style="text-align:center " id="txtcodtco" value="<?php print  $ls_codtco; ?>" size="2" maxlength="2" readonly="true">
                  <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion; ?>">
				   <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status; ?>" >
              </td>
            </tr>
            <tr align="left">
              <td height="22" align="right"><span class="style2">Nombre</span></td>
              <td><input name="txtnomtco" id="txtnomtco" value="<?php print $ls_nomtco; ?>" type="text" size="50" maxlength="50" onKeyPress="return(validaCajas(this,'x',event))"></td>
            </tr>
            <tr>
              <td height="4" align="right">Descripci&oacute;n</td>
              <td><textarea name="txtdestco" cols="53" onKeyDown="textCounter(this,254)"  onKeyUp="textCounter(this,254)" rows="2" id="txtdestco" value="<?php print $ls_destco; ?>" onKeyPress="return(validaCajas(this,'x',event,254))"><?php print $ls_destco?></textarea></td>
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
				  f.txtcodtco.value="";
				  f.txtnomtco.value="";
				  f.txtdestco.value="";
				  f.txtnomtco.focus(true);
				  f.action="sigesp_sob_d_tipocontrato.php";
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
					   if (ue_valida_null(txtcodtco,"Código")==false)
								{
								  txtcodtco.focus();
								}
							 else
								{ 
								  resul=rellenar_cad(document.form1.txtcodtco.value,2);
								  if (ue_valida_null(txtnomtco,"Nombre")==false)
									 {
									   txtnomtco.focus();
									 }
								   else
									 {
									   f=document.form1;
									   f.operacion.value="ue_guardar";
									   f.action="sigesp_sob_d_tipocontrato.php";
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
			if (f.txtcodtco.value=="")
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
						 f.action="sigesp_sob_d_tipocontrato.php";
						 f.submit();
					   }
					else
					   { 
						 f=document.form1;
						 f.action="sigesp_sob_d_tipocontrato.php";
						 alert("Eliminación Cancelada !!!");
						 f.txtcodtco.value="";
						 f.txtnomtco.value="";
						 f.txtdestco.value="";
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
	            document.form1.txtcodtco.value=cadena;
	    }
		
		function ue_buscar()
		{
            f=document.form1;
			li_leer=f.leer.value;
			if(li_leer==1)
			{
				f.operacion.value="";			
				pagina="sigesp_cat_tipocontrato.php";
				popupWin(pagina,"catalogo",520,200);
  				//window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}
</script>
</html>