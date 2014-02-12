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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_calcularprenomina.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_desnom,$ls_peractnom,$ld_fecdesper,$ld_fechasper,$li_nropro,$li_totregpro,$li_totasiprenom,$li_totdedprenom;
		global $li_totapoempprenom,$li_totapopatprenom,$li_totprenom,$li_totasinomant,$li_totdednomant,$li_totapoempnomant;		   
		global $li_totapopatnomant,$li_totnomant,$ls_codperdes,$ls_nomperdes,$ls_codperhas,$ls_nomperhas,$ls_existe,$ls_operacion;
		global $io_fun_nomina,$ls_desper,$ls_perresnom,$ls_reporte;

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
		$ls_codperdes="";
		$ls_nomperdes="";
		$ls_codperhas="";
		$ls_nomperhas="";
		$li_nropro=0;
		$li_totregpro=0;
		$li_totasiprenom=0;
		$li_totdedprenom=0;
		$li_totapoempprenom=0;		   
		$li_totapopatprenom=0;
		$li_totprenom=0;		   
		$li_totasinomant=0;
		$li_totdednomant=0;
		$li_totapoempnomant=0;		   
		$li_totapopatnomant=0;
		$li_totnomant=0;		   
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_perresnom=$_SESSION["la_nomina"]["perresnom"];
		$ls_reporte=$io_sno->uf_select_config("SNO","REPORTE","PRENOMINA","sigesp_sno_rpp_prenomina.php","C");
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
<title >Calcular Pren&oacute;mina</title>
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
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_sno_c_calcularprenomina.php");
	$io_calcularprenomina=new sigesp_sno_c_calcularprenomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$lb_valido=$io_calcularprenomina->uf_load_resumenprenomina($ls_peractnom,$ls_codperdes,$ls_codperhas,$li_totasiprenom,
															  $li_totdedprenom,$li_totapoempprenom,$li_totapopatprenom,$li_totprenom,
															  $li_nropro,$li_totasinomant,$li_totdednomant,$li_totapoempnomant,
															  $li_totapopatnomant,$li_totnomant);
			break;

		case "ELIMINAR":
			if(!($io_calcularprenomina->uf_select_salida()))
			{
				$lb_valido=$io_calcularprenomina->uf_delete_prenomina($la_seguridad);
				if($lb_valido)
				{
					$io_calcularprenomina->io_mensajes->message("La prenómina fue eliminada."); 
				}
				else
				{
					$io_calcularprenomina->io_mensajes->message("Ocurrio un error al eliminar la prenómina."); 
				}
			}
			else
			{
				$io_calcularprenomina->io_mensajes->message("La Nómina ya se proceso. Reverse la Nómina y elimine la prenómina."); 
			}
			$lb_valido=$io_calcularprenomina->uf_load_resumenprenomina($ls_peractnom,$ls_codperdes,$ls_codperhas,$li_totasiprenom,
															  $li_totdedprenom,$li_totapoempprenom,$li_totapopatprenom,$li_totprenom,
															  $li_nropro,$li_totasinomant,$li_totdednomant,$li_totapoempnomant,
															  $li_totapopatnomant,$li_totnomant);
			break;

		case "PROCESAR":
			$ls_codperdes=$io_fun_nomina->uf_obtenervalor("txtcodperdes","");
			$ls_nomperdes=$io_fun_nomina->uf_obtenervalor("txtnomperdes","");
			$ls_codperhas=$io_fun_nomina->uf_obtenervalor("txtcodperhas","");
			$ls_nomperhas=$io_fun_nomina->uf_obtenervalor("txtnomperhas","");
			if(!($io_calcularprenomina->uf_select_salida()))
			{
				$lb_valido=$io_calcularprenomina->uf_procesarprenomina($ls_codperdes,$ls_codperhas,$la_seguridad);
			}
			else
			{
				$io_calcularprenomina->io_mensajes->message("La Nómina ya se proceso. Reverse la Nómina y vuelva a calcular."); 
			}
			$lb_valido=$io_calcularprenomina->uf_load_resumenprenomina($ls_peractnom,$ls_codperdes,$ls_codperhas,$li_totasiprenom,
															  $li_totdedprenom,$li_totapoempprenom,$li_totapopatprenom,$li_totprenom,
															  $li_nropro,$li_totasinomant,$li_totdednomant,$li_totapoempnomant,
															  $li_totapopatnomant,$li_totnomant);
			break;

		case "BUSCAR":
			$ls_codperdes=$io_fun_nomina->uf_obtenervalor("txtcodperdes","");
			$ls_nomperdes=$io_fun_nomina->uf_obtenervalor("txtnomperdes","");
			$ls_codperhas=$io_fun_nomina->uf_obtenervalor("txtcodperhas","");
			$ls_nomperhas=$io_fun_nomina->uf_obtenervalor("txtnomperhas","");
			$lb_valido=$io_calcularprenomina->uf_load_resumenprenomina($ls_peractnom,$ls_codperdes,$ls_codperhas,$li_totasiprenom,
															  $li_totdedprenom,$li_totapoempprenom,$li_totapopatprenom,$li_totprenom,
															  $li_nropro,$li_totasinomant,$li_totdednomant,$li_totapoempnomant,
															  $li_totapopatnomant,$li_totnomant);
			break;
	}
	$io_calcularprenomina->uf_destructor();
	unset($io_calcularprenomina);	
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title='Eliminar' alt="Eliminar" width="20" height="18" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
          <td height="20" colspan="7" class="titulo-ventana">Calcular Pren&oacute;mina</td>
        </tr>
        <tr>
          <td width="133" height="22">&nbsp;</td>
          <td colspan="6"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="6">
            <div align="left">
              <input name="txtdesnom" type="text" class="sin-borde3" id="txtdesnom" value="<?php print  $ls_desnom;?>" size="80" maxlength="100" readonly>
              </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="30">
            <div align="left">
              <input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
              </div></td>
          <td width="73"><div align="left">Fecha Inicio</div></td>
          <td width="70">
            
              <div align="left">
                <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper2" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
            </div></td><td width="54"><div align="left">Fecha Fin </div></td>
                <td colspan="2">
                    
                  <div align="left">
                    <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
                  </div></td></tr>
        <tr>
          <td height="22"><div align="right">Personal Desde </div></td>
          <td colspan="6">
            
              <div align="left">
                <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="<?php print $ls_codperdes;?>" readonly>
                <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtnomperdes" type="text" id="txtnomperdes" value="<?php print $ls_nomperdes;?>" size="70" maxlength="100">
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">Personal Hasta </div></td>
          <td colspan="6">
            
              <div align="left">
                <input name="txtcodperhas" type="text" id="txtcodperhas" value="<?php print $ls_codperhas;?>" size="13" maxlength="10" readonly>
                <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
                <input name="txtnomperhas" type="text" id="txtnomperhas" value="<?php print $ls_nomperhas;?>" size="70" maxlength="100">
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">N&deg;  a procesar </div></td>
          <td colspan="6">
            <div align="left">
              <input name="txtnropro" type="text" class="sin-borde3" id="txtnropro" value="<?php print $li_nropro;?>" size="11" maxlength="8" readonly>            
              </div></td>
        </tr>
        <tr>
          <td height="20" colspan="7" class="titulo-celdanew">Resumen de Pren&oacute;mina </td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5"><div align="center" class="sin-borde2">Pren&oacute;mina</div> </td>
          <td width="280" class="sin-borde2"><div align="center">N&oacute;mina Anterior</div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Asignaci&oacute;n </div></td>
          <td colspan="5"><div align="center">
            <input name="txttotasiprenom" type="text" id="txttotasiprenom" value="<?php print $li_totasiprenom;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
          <td><div align="center">
            <input name="txttotasinomant" type="text" id="txttotasinomant" value="<?php print $li_totasinomant;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Deducci&oacute;n </div></td>
          <td colspan="5"><div align="center">
            <input name="txttotdedprenom" type="text" id="txttotdedprenom" value="<?php print $li_totdedprenom;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
          <td><div align="center">
            <input name="txttotdednomant" type="text" id="txttotdednomant" value="<?php print $li_totdednomant;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Aporte Empleado </div></td>
          <td colspan="5"><div align="center">
            <input name="txttotapoempprenom" type="text" id="txttotapoempprenom" value="<?php print $li_totapoempprenom;?>" size="33" maxlength="30" style="text-align:right" readonly>
</div></td>
          <td><div align="center">
            <input name="txttotapoempnomant" type="text" id="txttotapoempprenom" value="<?php print $li_totapoempnomant;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Aporte Patronal </div></td>
          <td colspan="5"><div align="center">
            <input name="txttotapopatprenom" type="text" id="txttotapopatprenom" value="<?php print $li_totapopatprenom;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
          <td><div align="center">
            <input name="txttotapopatnomant" type="text" id="txttotapopatnomant" value="<?php print $li_totapopatnomant;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Total Pren&oacute;mina </div></td>
          <td colspan="5"><div align="center">
            <input name="txttotprenom" type="text" id="txttotprenom" value="<?php print $li_totprenom;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
          <td><div align="center">
            <input name="txttotnomant" type="text" id="txttotnomant" value="<?php print $li_totnomant;?>" size="33" maxlength="30" style="text-align:right" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="6">
		  		<input name="operacion" type="hidden" id="operacion">
		  <input name="txtperresnom" type="hidden" id="txtperresnom" value="<?php print $ls_perresnom;?>">				</td>
		  <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
        </tr>
      </table>    
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>.</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_sno_p_calcularprenomina.php";
	f.submit();
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		ls_peresnom=f.txtperresnom.value;
		if(ls_peresnom=="000")
		{	
			if(confirm("¿Desea eliminar la prenómina actual?"))
			{
				f.operacion.value="ELIMINAR";
				f.action="sigesp_sno_p_calcularprenomina.php";
				f.submit();
			}
		}
		else
		{
			alert("Para este período no se puede eliminar Prenómina");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		ls_peresnom=f.txtperresnom.value;
		//if(ls_peresnom=="000")
		//{
			if(f.txtnropro.value>0)
			{
				f.operacion.value="PROCESAR";
				f.action="sigesp_sno_p_calcularprenomina.php";
				f.submit();
			}
			else
			{
				alert("No hay Personal a Procesar");
			}
		/*}
		else
		{
			alert("Para este período no se puede calcular la Prenómina");
		}*/
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
function ue_buscarpersonaldesde()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=prenominades","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	if(f.txtcodperdes!="")
	{
		window.open("sigesp_sno_cat_personalnomina.php?tipo=prenominahas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un personal desde.");
	}
}
function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	reporte=f.reporte.value;
	if (li_imprimir==1)
   	{
		pagina="reportes/"+reporte+"";
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=750,height=650,left=20,top=20,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}
</script> 
</html>