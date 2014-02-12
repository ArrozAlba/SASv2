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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_d_primaconcepto.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc,$ls_nomcon,$ls_sigcon,$li_anopri,$li_valpri,$ls_desnom,$ls_operacion,$ls_existe;
		global $io_fun_nomina,$ls_desper,$li_calculada;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codconc="";
		$ls_nomcon="";
		$ls_sigcon="";
		$li_anopri=0;
		$li_valpri=0;
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
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
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codconc, $ls_nomcon, $ls_sigcon, $li_anopri, $li_valpri;
		
		$ls_codconc=$_POST["txtcodconc"];
		$ls_nomcon=$_POST["txtnomcon"];
		$ls_sigcon=$_POST["txtsigcon"];
		$li_anopri=$_POST["txtanopri"];
		$li_valpri=$_POST["txtvalpri"];
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
<title >Definici&oacute;n de Vacaci&oacute;n Concepto</title>
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
</head>
<body>
<?php 
	require_once("sigesp_sno_c_primaconcepto.php");
	$io_primaconcepto=new sigesp_sno_c_primaconcepto();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_primaconcepto->uf_guardar($ls_existe,$ls_codconc,$li_anopri,$li_valpri,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_primaconcepto->uf_delete_primaconcepto($ls_codconc,$li_anopri,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
	$io_primaconcepto->uf_destructor();
	unset($io_primaconcepto);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title='Nuevo' alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title='Eliminar' alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definición de Prima Concepto </td>
        </tr>
        <tr>
          <td width="104">&nbsp;</td>
          <td width="470" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Concepto</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodconc" type="text" id="txtcodconc" size="13" maxlength="10" value="<?php print $ls_codconc;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,10);">
            <a href="javascript: ue_buscarconcepto();"><img id="concepto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
            <input name="txtnomcon" type="text" class="sin-borde" id="txtnomcon" value="<?php print $ls_nomcon;?>" size="33" maxlength="30">
            <input name="txtsigcon" type="text" class="sin-borde2" id="txtsigcon" value="<?php print $ls_sigcon;?>" size="18" maxlength="15"> 
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cantidad</div></td>
          <td colspan="3"><div align="left">
            <input name="txtanopri" type="text" id="txtanopri" value="<?php print $li_anopri;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Valor</div></td>
          <td colspan="3"><div align="left">
            <input name="txtvalpri" type="text" id="txtvalpri" value="<?php print $li_valpri;?>" size="23" maxlength="20" onKeyPress="return(ue_formatonumero(this,'.',',',event))" style="text-align:right">
          </div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
			<input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>"></td>
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
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{	
			f.operacion.value="NUEVO";
			f.existe.value="FALSE";		
			f.action="sigesp_sno_d_primaconcepto.php";
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_incluir=f.incluir.value;
		li_cambiar=f.cambiar.value;
		lb_existe=f.existe.value;
		if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
		{
			codconc = ue_validarvacio(f.txtcodconc.value);
			anopri = ue_validarvacio(f.txtanopri.value);
			valpri = ue_validarvacio(f.txtvalpri.value);
			if ((codconc!="")&&(anopri!="")&&(anopri!="0")&&(valpri!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_sno_d_primaconcepto.php";
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
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		{	
			if(f.existe.value=="TRUE")
			{
				codconc = ue_validarvacio(f.txtcodconc.value);
				anopri = ue_validarvacio(f.txtanopri.value);
				if ((codconc!="")&&(anopri!=""))
				{
					if(confirm("¿Desea eliminar el Registro actual?"))
					{
						f.operacion.value="ELIMINAR";
						f.action="sigesp_sno_d_primaconcepto.php";
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
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_primaconcepto.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarconcepto()
{
	f=document.form1;
	existe = ue_validarvacio(f.existe.value);
	if(existe=="FALSE")
	{
		window.open("sigesp_sno_cat_concepto.php?tipo=PRIMA","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}
</script> 
</html>