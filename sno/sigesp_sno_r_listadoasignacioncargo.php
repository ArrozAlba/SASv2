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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_listadoasignacioncargo.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$li_rac=$_SESSION["la_nomina"]["racnom"];
	if($li_rac!=1)
	{
		print("<script language=JavaScript>");
		print(" alert('Este reporte esta desactivado para nóminas que no utilizan asignación de cargo.');");
		print(" location.href='sigespwindow_blank_nomina.php'");
		print("</script>");
	}	
	$li_implementarcodunirac=trim($io_sno->uf_select_config("SNO","CONFIG","CODIGO_UNICO_RAC","0","I"));
	unset($io_sno);
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
<title >Reporte Listado de Disponibildad de  Asignaci&oacute;n de Cargos</title>
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print(<?php print($li_implementarcodunirac); ?>);"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Listado de Disponibilidad de Asignaci&oacute;n de Cargo </td>
        </tr>
        
		
		
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Asignaci&oacute;n de Cargo </td>
        </tr>
        <tr>
          <td width="128" height="22"><div align="right"> Desde </div></td>
          <td width="157">
		  
            <input name="txtcodasignacdes" type="text" id="txtcodasignacdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarasignadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
			</td>
          <td width="63"><div align="right">Hasta </div></td>
          <td width="184">
            <input name="txtcodasignachas" type="text" id="txtcodasignachas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarasignahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
		<?php 
		  if($li_implementarcodunirac=="1") {?>
		<tr>
          <td height="20" colspan="4" class="titulo-celdanew">Tipo de Reporte</td>
        </tr>
		<tr>
          <td height="20">&nbsp;</td>
          <td height="20" colspan="3"><div align="left">
            <select name="cmbtiprep" id="cmbtiprep">
              <option value="0" selected>Listado de Asignaci&oacute;n de Cargos</option>
              <option value="1">Listado de C&oacute;digos &Uacute;nicos de Asignaci&oacute;n de Cargos</option>
            </select>
          </div></td>
          <td width="6" height="20">&nbsp;</td>
          
        </tr>
		<?php }	?>
        <tr>
          <td height="22" colspan="4"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de la Asignaci&oacute;n de Cargo </div></td>
          <td colspan="3"><div align="left">
              <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre de  la Asignaci&oacute;n de Cargo </div></td>
          <td colspan="3"><div align="left">
              <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><div align="right">
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
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
function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_print(codunirac)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		reporte=f.reporte.value;
		codasignades=f.txtcodasignacdes.value;
		codasignahas=f.txtcodasignachas.value;
	    
		if(codasignades<=codasignahas)
		{	
		      if(f.rdborden[0].checked)
				{
					orden="1";
				}
				if(f.rdborden[1].checked)
				{
					orden="2";
				}
				if (codunirac=='0')
				{
					pagina="reportes/sigesp_sno_rpp_listadoasignacioncargo.php?codasignades="+codasignades+"&codasignahas="+codasignahas+"&orden="+orden;							
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");	
				}
				else
				{
					tiporeporte=f.cmbtiprep.value;
					if 	(tiporeporte=='0')		
					{
						pagina="reportes/sigesp_sno_rpp_listadoasignacioncargo.php?codasignades="+codasignades+"&codasignahas="+codasignahas+"&orden="+orden;							
						window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");			
					}
					else
					{
						pagina="reportes/sigesp_sno_rpp_listado_codigo_unico_rac.php?codasignades="+codasignades+"&codasignahas="+codasignahas+"&orden="+orden;							
						window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");	
					}
				}
		}
		else
		{
			alert("El rango de la Asignaciòn de Cargo está erroneo");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarasignadesde()
{
	window.open("sigesp_sno_cat_asignacioncargo.php?tipo=listado1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarasignahasta()
{
	window.open("sigesp_sno_cat_asignacioncargo.php?tipo=listado2","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


</script> 
</html>