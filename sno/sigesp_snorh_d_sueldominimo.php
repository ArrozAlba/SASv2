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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_sueldominimo.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

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
   		global $ls_codsuemin, $li_anosuemin, $ls_gacsuemin, $ls_decsuemin, $ld_fecvigsuemin, $li_monsuemin, $ls_obssuemin;
		global $ls_operacion, $lb_existe, $io_fun_nomina;
		
		$ls_codsuemin="";
		$li_anosuemin="";
		$ls_gacsuemin="";
		$ls_decsuemin="";
		$ld_fecvigsuemin="";
		$li_monsuemin="";
		$ls_obssuemin="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$lb_existe=$io_fun_nomina->uf_obtenerexiste();
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
   		global $ls_codsuemin, $li_anosuemin, $ls_gacsuemin, $ls_decsuemin, $ld_fecvigsuemin, $li_monsuemin, $ls_obssuemin;
		
		$ls_codsuemin=$_POST["txtcodsuemin"];
		$li_anosuemin=$_POST["txtanosuemin"];
		$ls_gacsuemin=$_POST["txtgacsuemin"];
		$ls_decsuemin=$_POST["txtdecsuemin"];
		$ld_fecvigsuemin=$_POST["txtfecvigsuemin"];
		$li_monsuemin=$_POST["txtmonsuemin"];
		$ls_obssuemin=$_POST["txtobssuemin"];
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
<title>Definici&oacute;n de Sueldo M&iacute;nimo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_sueldominimo.php");
	$io_sueldo = new sigesp_snorh_c_sueldominimo();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_sueldo->uf_guardar($lb_existe,$ls_codsuemin, $li_anosuemin, $ls_gacsuemin, $ls_decsuemin, $ld_fecvigsuemin, 
			                                  $li_monsuemin, $ls_obssuemin, $la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$lb_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_sueldo->uf_delete($ls_codsuemin,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$lb_existe="FALSE";
			}
			break;
	}
	$io_sueldo->uf_destructor();
	unset($io_sueldo);
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<div align="center">
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
		  <p>&nbsp;</p>
		  <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
            <tr class="titulo-ventana">
              <td height="20" colspan="2"><div align="center">Definici&oacute;n de Sueldo M&iacute;nimo </div></td>
            </tr>
            <tr >
              <td height="22">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="122" height="22"><div align="right" >
                  <p>Codigo</p>
              </div></td>
              <td width="456"><div align="left" >
                  <input name="txtcodsuemin" type="text" id="txtcodsuemin" value="<?php print $ls_codsuemin; ?>" size="6" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);">
              </div></td>
            </tr>
            <tr >
              <td height="22"><div align="right">A&ntilde;o</div></td>
              <td><div align="left">
                  <input name="txtanosuemin" type="text" id="txtanosuemin" value="<?php print $li_anosuemin; ?>" size="6" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);">
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Nro de Gaceta </div></td>
              <td><div align="left">
                <input name="txtgacsuemin" type="text" id="txtgacsuemin" value="<?php print $ls_gacsuemin; ?>" size="12" maxlength="10" onKeyUp="ue_validarcomillas(this);">
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Nro de Decreto </div></td>
              <td><div align="left">
                <input name="txtdecsuemin" type="text" id="txtdecsuemin" value="<?php print $ls_decsuemin; ?>" size="12" maxlength="10" onKeyUp="ue_validarcomillas(this);">
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Fecha Vigencia </div></td>
              <td> <div align="left">
                <input name="txtfecvigsuemin" type="text" id="txtfecvigsuemin" value="<?php print $ld_fecvigsuemin;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
              </div></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Valor</div></td>
              <td>
                <div align="left">
                  <input name="txtmonsuemin" type="text" id="txtmonsuemin"  style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php  print $li_monsuemin; ?>" size="22" maxlength="23">
                    </div></td></tr>
            <tr>
              <td height="22"><div align="right">Observaci&oacute;n</div></td>
              <td><textarea name="txtobssuemin" cols="55" rows="4" id="txtobssuemin" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_obssuemin; ?></textarea></td>
            </tr>
            <tr>
              <td height="22"><div align="right"></div></td>
              <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $lb_existe;?>">            </tr>
          </table>
		  <p>&nbsp;</p></td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE"
		f.action="sigesp_snorh_d_sueldominimo.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
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
		codsuemin = ue_validarvacio(f.txtcodsuemin.value);
		anosuemin = ue_validarvacio(f.txtanosuemin.value);
		if ((codsuemin!="")&&(anosuemin!=""))
		{
			f.operacion.value ="GUARDAR";
			f.action="sigesp_snorh_d_sueldominimo.php";
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
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.operacion.value ="ELIMINAR";
				f.action="sigesp_snorh_d_sueldominimo.php";
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
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_sueldominimo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_cestaticket.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

</script>
</html>