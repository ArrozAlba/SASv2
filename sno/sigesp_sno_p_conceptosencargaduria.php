<?php
    session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	ini_set('max_execution_time ','0');

	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_conceptosencargaduria.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
		global $ls_operacion, $li_totrows, $ls_titletable, $li_widthtable;
		global $ls_nametable, $lo_title, $io_fun_nomina, $ls_desnom,$ls_desper,$li_calculada;
		global $li_registros, $li_pagina, $li_inicio, $li_totpag;
		
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totrows=$io_fun_nomina->uf_obtenervalor("totalfilas",1);
		$ls_titletable="Conceptos";
		$li_widthtable=700;
		$ls_nametable="grid";
		$lo_title[1]="Código";
		$lo_title[2]="Denominación";
		$lo_title[3]="Aplicar";
		$li_registros = 100;
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
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
<title >Selecci&oacute;n de Conceptos de Encargadur&iacute;a</title>
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
</head>
<body>
<?php 
	require_once("sigesp_sno_c_concepto.php");
	$io_concepto=new sigesp_sno_c_concepto();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$lb_valido=$io_concepto->uf_load_conceptoencargaduria($li_totrows,$lo_object);
			break;
			
		case "GUARDAR":
		 	$lb_valido=true;
			$io_concepto->io_sql->begin_transaction();
			for($li_i=1;$li_i<=$li_totrows&&$lb_valido;$li_i++)
			{
				$ls_codcon=$_POST["txtcodcon".$li_i.""];
				$li_aplcon=$io_fun_nomina->uf_obtenervalor("chkaplcon".$li_i."","0");
				$lb_valido=$io_concepto->uf_guardar_conceptoencargaduria($ls_codcon,$li_aplcon,$la_seguridad);
				
			}
			if($lb_valido)
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////					
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó el concepto ".$ls_codconc.", asociado a la nómina ".$ls_codnom;
				$lb_valido= $io_concepto->io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
												$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
												$la_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////	
			}		
			if($lb_valido)
			{
				$io_concepto->io_sql->commit();
				$io_concepto->io_mensajes->message("Los Conceptos fueron actualizados.");
			}
			else
			{
				$io_concepto->io_sql->rollback();
				$io_concepto->io_mensajes->message("Ocurrio un error al actualizar los conceptos.");
			}
			$lb_valido=$io_concepto->uf_load_conceptoencargaduria($li_totrows,$lo_object);
			break;
	}
	$io_concepto->uf_destructor();
	unset($io_concepto);	
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
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
<table width="762" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="712" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-celda">
          <td colspan="2" class="formato-blanco"><div align="center">
              <input name="txtnomcon" type="text" class="sin-borde2" id="txtnomcon" value="<?php print $ls_nomcon;?>" style="text-align:center" size="60" readonly>
              <input name="txtcodconc" type="hidden" id="txtcodconc" value="<?php print $ls_codconc;?>">
          </div></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Selecci&oacute;n de Conceptos de Encargadur&iacute;a</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="85">&nbsp;</td>
          <td width=><div align="right">Aplicar a Todos
              <input name="chktodos" type="checkbox" class="sin-borde" id="chktodos" value="1" onChange="ue_aplicar();">
          </div></td>
        </tr>        
        <tr>
          <td colspan="2"><div align="center">
			<?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?>
            </div></td>
          </tr>
        <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
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


function ue_guardar()
{
	f=document.form1;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_cambiar=f.cambiar.value;
		if(li_cambiar==1)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sno_p_conceptosencargaduria.php";
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

function ue_aplicar()
{
	f=document.form1;
	if(f.chktodos.checked==true)
	{
		total=f.totalfilas.value;
		for(i=1;i<=total;i++)
		{
			eval("f.chkaplcon"+i+".checked=true;");
		}
	}
	else
	{
		total=f.totalfilas.value;
		for(i=1;i<=total;i++)
		{
			eval("f.chkaplcon"+i+".checked=false;");
		}
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}
</script> 
</html>