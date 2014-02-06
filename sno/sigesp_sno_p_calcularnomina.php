<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	ini_set('max_execution_time','0');

	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_calcularnomina.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_desnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$li_nropro,$li_totregpro,$li_totasi,$li_totded,$li_totapoemp;
		global $li_totapopat,$li_totnom,$ls_operacion,$ls_existe,$io_fun_nomina,$ls_desper,$ls_perresnom,$ls_reporte;

		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		$ld_fecdesper="";
		$ld_fechasper="";
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		else
		{
			$ls_desnom=$_SESSION["la_nomina"]["desnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$li_nropro=0;
		$li_totregpro=0;
		$li_totasi=0;
		$li_totded=0;
		$li_totapoemp=0;		   
		$li_totapopat=0;
		$li_totnom=0;
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();		   
		$ls_perresnom=$_SESSION["la_nomina"]["perresnom"];
		$ls_reporte=$io_sno->uf_select_config("SNO","REPORTE","PAGO_NOMINA","sigesp_sno_rpp_pagonomina.php","C");
		unset($io_sno);
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
<title >Calcular N&oacute;mina</title>
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
	require_once("sigesp_sno_c_calcularnomina.php");
	$io_calcularnomina=new sigesp_sno_c_calcularnomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$lb_valido=$io_calcularnomina->uf_obtener_resumenpago($ls_peractnom,$li_totasi,$li_totded,$li_totapoemp,$li_totapopat,$li_totnom,$li_nropro);
			break;

		case "PROCESAR":
			if(!($io_calcularnomina->uf_existesalida()))
			{
				$lb_validonom=$io_calcularnomina->uf_procesarnomina($la_seguridad);				
			}
			else
			{
				$io_calcularnomina->io_mensajes->message("La Nómina ya se proceso. Reverse la Nómina y vuelva a calcular."); 
			}						
			$lb_valido=$io_calcularnomina->uf_obtener_resumenpago($ls_peractnom,$li_totasi,$li_totded,$li_totapoemp,$li_totapopat,$li_totnom,$li_nropro);	
			if ($lb_validonom)
			{
				$li_persobregiro=trim($io_calcularnomina->io_sno->uf_select_config("SNO","CONFIG","SOBREGIRO_CUENTAS_TRABAJADOR","0","I"));
				if (($li_persobregiro=='1')&&($io_calcularnomina->lb_sobregiro))
				{
				  ?>
				  <script language="javascript">
				  if (confirm("¿Desea ver el reporte de personas con Deducciones mayor a las Asignaciones?"))
				  {
					pagina="reportes/sigesp_sno_rpp_error_calculonomina.php";
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				  }
				  </script>
				  <?php 
				}
			}
			break;
	}
	//$io_calcularnomina->uf_destructor();
	unset($io_calcularnomina);	
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
<table width="738" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="688" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Calcular N&oacute;mina</td>
        </tr>
        <tr>
          <td width="171" height="22">&nbsp;</td>
          <td colspan="5"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="5"><div align="left">
            <input name="txtdesnom" type="text" class="sin-borde3" id="txtdesnom" value="<?php print  $ls_desnom;?>" size="80" maxlength="100" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="20"><div align="left">
            <input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
          </div></td>
          <td width="68"><div align="left">Fecha Inicio</div></td>
          <td width="60">
              <div align="left">
                <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper2" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
              </div></td>
          <td width="55"><div align="left">Fecha Fin </div></td>
          <td width="300">
              <div align="left">
                <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
              </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">N&deg;  a procesar </div></td>
          <td colspan="5"><div align="left">
            <input name="txtnropro" type="text" class="sin-borde3" id="txtnropro" value="<?php print $li_nropro;?>" size="11" maxlength="8" readonly>            
          </div></td>
        </tr>
        <tr>
          <td height="20" colspan="6" class="titulo-celdanew">Resumen de Per&iacute;odo</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Total Asignaci&oacute;n </div></td>
          <td colspan="5"><div align="left">
            <input name="txttotasi" type="text" id="txttotasi" value="<?php print $li_totasi;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Deducci&oacute;n </div></td>
          <td colspan="5"><div align="left">
            <input name="txttotded" type="text" id="txttotded" value="<?php print $li_totded;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Aporte Empleado </div></td>
          <td colspan="5"><div align="left">
            <input name="txttotapoemp" type="text" id="txttotapoemp" value="<?php print $li_totapoemp;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Aporte Patronal </div></td>
          <td colspan="5"><div align="left">
            <input name="txttotapopat" type="text" id="txttotapopat" value="<?php print $li_totapopat;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total N&oacute;mina </div></td>
          <td colspan="5"><div align="left">
            <input name="txttotnom" type="text" id="txttotnom" value="<?php print $li_totnom;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22" colspan="6">
					<div id=transferir style="visibility:hidden" align="center"><img src="../shared/imagebank/cargando.gif">Calculando Nómina... </div>		  
		  </td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5"><div align="left">
            <input name="operacion" type="hidden" id="operacion">
		  <input name="txtperresnom" type="hidden" id="txtperresnom" value="<?php print $ls_perresnom;?>">
		    <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
          </div></td>
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		ls_peresnom=f.txtperresnom.value;

		if(parseFloat(f.txtnropro.value)>0)
		{
			mostrar('transferir');
			f.operacion.value="PROCESAR";
			f.action="sigesp_sno_p_calcularnomina.php";
			f.submit();
		}
		else
		{
			alert("Debe seleccionar el Personal a Procesar");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	reporte=f.reporte.value;
	if(li_imprimir==1)
	{	
		pagina="reportes/"+reporte+"?conceptocero=1";
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=750,height=650,left=20,top=20,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}
function mostrar(nombreCapa)
{
	capa= document.getElementById(nombreCapa) ;
	capa.style.visibility="visible"; 
} 
</script> 
</html>