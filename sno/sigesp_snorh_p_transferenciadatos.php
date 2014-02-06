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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_p_transferenciadatos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnomdes,$ls_desnomdes,$ls_codnomhas,$ls_desnomhas,$ls_operacion,$li_tabulador,$li_cargos,$li_rac;
		global $li_sueldo,$li_unidadadmin,$li_banco,$li_cuentabancaria,$li_tipocuenta,$li_cuentacontable,$io_fun_nomina;
		
		$ls_codnomdes="";
		$ls_desnomdes="";
		$ls_codnomhas="";
		$ls_desnomhas="";
		$li_tabulador="0";
		$li_cargos="0";
		$li_rac="0";
		$li_sueldo="0";
		$li_unidadadmin="0";
		$li_banco="0";
		$li_cuentabancaria="0";
		$li_tipocuenta="0";
		$li_cuentacontable="0";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
		// Fecha Creación: 30/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnomdes,$ls_desnomdes,$ls_codnomhas,$ls_desnomhas,$li_tabulador,$li_cargos,$li_rac,$li_sueldo,$li_unidadadmin;
		global $li_banco,$li_cuentabancaria,$li_tipocuenta,$li_cuentacontable,$io_fun_nomina;
		
		$ls_codnomdes=$_POST["txtcodnomdes"];
		$ls_desnomdes=$_POST["txtdesnomdes"];
		$ls_codnomhas=$_POST["txtcodnomhas"];
		$ls_desnomhas=$_POST["txtdesnomhas"];
		$li_tabulador=$io_fun_nomina->uf_obtenervalor("chktabulador","0");
		$li_cargos=$io_fun_nomina->uf_obtenervalor("chkcargos","0");
		$li_rac=$io_fun_nomina->uf_obtenervalor("chkrac","0");
		$li_sueldo=$io_fun_nomina->uf_obtenervalor("chksueldo","0");
		$li_unidadadmin=$io_fun_nomina->uf_obtenervalor("chkunidadadmin","0");
		$li_banco=$io_fun_nomina->uf_obtenervalor("chkbanco","0");
		$li_cuentabancaria=$io_fun_nomina->uf_obtenervalor("chkcuentabancaria","0");
		$li_tipocuenta=$io_fun_nomina->uf_obtenervalor("chktipocuenta","0");
		$li_cuentacontable=$io_fun_nomina->uf_obtenervalor("chkcuentacontable","0");
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
<title >Transferencia de Datos entre RAC</title>
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
	require_once("sigesp_sno_c_personalnomina.php");
	$io_personalnomina=new sigesp_sno_c_personalnomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			uf_load_variables();
			$lb_valido=$io_personalnomina->uf_procesar_transferenciadatos($ls_codnomdes,$ls_codnomhas,$li_tabulador,$li_cargos,$li_rac,
																		  $li_sueldo,$li_unidadadmin,$li_banco,$li_cuentabancaria,
																		  $li_tipocuenta,$li_cuentacontable,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
			}
			break;
	}
	$io_personalnomina->uf_destructor();
	unset($io_personalnomina);
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
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif"  title="Ejecutar" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="620" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="570" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Transferencia de Datos entre RAC </td>
        </tr>
        <tr>
          <td width="113" height="22"><div align="right"></div></td>
          <td colspan="3"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina Desde </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="13" maxlength="10" value="<?php print $ls_codnomdes;?>" readonly>
            <a href="javascript: ue_buscarnominadesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesnomdes" type="text" class="sin-borde" id="txtdesnomdes" value="<?php print $ls_desnomdes;?>" size="60" maxlength="120" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina Hasta </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodnomhas" type="text" id="txtcodnomhas" size="13" maxlength="10" value="<?php print $ls_codnomhas;?>" readonly>
              <a href="javascript: ue_buscarnominahasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtdesnomhas" type="text" class="sin-borde" id="txtdesnomhas" value="<?php print $ls_desnomhas;?>" size="60" maxlength="120" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="3"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew"><div align="right"></div>            
            <div align="center">Informaci&oacute;n a Transferir </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Tabulador</div></td>
          <td width="117"><div align="left">
            <input name="chktabulador" type="checkbox" class="sin-borde" id="chktabulador" value="1" <?php if($li_tabulador=="1"){ print "checked";} ?>>
          </div></td>
          <td width="112"><div align="right">Banco</div></td>
          <td width="188"><div align="left">
            <input name="chkbanco" type="checkbox" class="sin-borde" id="chkbanco" value="1" <?php if($li_banco=="1"){ print "checked";} ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cargos</div></td>
          <td>
            <div align="left">
              <input name="chkcargos" type="checkbox" class="sin-borde" id="chkcargos" value="1" <?php if($li_cargos=="1"){ print "checked";} ?>>
            </div></td>
          <td><div align="right">Cuenta Bancaria </div></td>
          <td><div align="left">
            <input name="chkcuentabancaria" type="checkbox" class="sin-borde" id="chkcuentabancaria" value="1" <?php if($li_cuentabancaria=="1"){ print "checked";} ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">RAC</div></td>
          <td><div align="left">
            <input name="chkrac" type="checkbox" class="sin-borde" id="chkrac" value="1" <?php if($li_rac=="1"){ print "checked";} ?>>
          </div></td>
          <td><div align="right">Tipo de Cuenta </div></td>
          <td><div align="left">
            <input name="chktipocuenta" type="checkbox" class="sin-borde" id="chktipocuenta" value="1" <?php if($li_tipocuenta=="1"){ print "checked";} ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Sueldo</div></td>
          <td><div align="left">
            <input name="chksueldo" type="checkbox" class="sin-borde" id="chksueldo" value="1" <?php if($li_sueldo=="1"){ print "checked";} ?>>
          </div></td>
          <td><div align="right">Cuenta Contable </div></td>
          <td><div align="left">
            <input name="chkcuentacontable" type="checkbox" class="sin-borde" id="chkcuentacontable" value="1" <?php if($li_cuentacontable=="1"){ print "checked";} ?>>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Unidad Administrativa </div></td>
          <td>
            <div align="left">
              <input name="chkunidadadmin" type="checkbox" class="sin-borde" id="chkunidadadmin" value="1" <?php if($li_unidadadmin=="1"){ print "checked";} ?>>
            </div></td>
          <td><div align="right"></div></td>
          <td><div align="left"></div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td colspan="3"><div align="left">
            <input name="operacion" type="hidden" id="operacion">
          </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{	
		valido=true;
		codnomdes=ue_validarvacio(f.txtcodnomdes.value);
		codnomhas=ue_validarvacio(f.txtcodnomhas.value);
		tabulador=f.chktabulador.checked;
		cargos=f.chkcargos.checked;
		rac=f.chkrac.checked;
		sueldo=f.chksueldo.checked;
		unidadadmin=f.chkunidadadmin.checked;
		banco=f.chkbanco.checked;
		cuentabancaria=f.chkcuentabancaria.checked;
		tipocuenta=f.chktipocuenta.checked;
		cuentacontable=f.chkcuentacontable.checked;
		if((codnomdes=="")&&(codnomhas==""))
		{
			valido=false;
			alert("Debe seleccionar la nómina desde y la nómina hasta.");
		}
		if(!(tabulador||cargos||rac||sueldo||unidadadmin||banco||cuentabancaria||tipocuenta||cuentacontable))
		{
			valido=false;
			alert("Debe seleccionar algún campo a transferir.");
		}
		if(valido)
		{
			f.operacion.value="PROCESAR";
			f.action="sigesp_snorh_p_transferenciadatos.php";
			f.submit();
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
function ue_buscarnominadesde()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=transferirdesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=transferirhasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una nómina desde.");
	}
}
</script> 
</html>