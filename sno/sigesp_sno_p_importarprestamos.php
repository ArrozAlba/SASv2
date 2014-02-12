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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_importarprestamos.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="txt/general";
	@mkdir($ls_ruta,0755);
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
   		global $ls_desnom, $ls_peractnom, $ld_fecdesper, $ld_fechasper, $ls_concsue1, $ls_concsue2, $ls_concsue3, $ls_concsue4;
		global $ls_conccaj1, $ls_conccaj2, $ls_conccaj3, $ls_conccaj4, $ls_concpreper, $ls_concpreesp, $ls_concmontepio;
		global $ls_concfianza, $ls_concprehip, $ls_operacion, $ls_accion,  $io_fun_nomina, $ls_desper, $li_calculada,		
		$lb_mostrargrid;
		global $ls_codarch,$ls_denarch,$li_totrow;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		$ld_fecdesper="";
		$ld_fechasper="";
		$ls_codarch="";
		$ls_denarch="";
		$li_totrow=0;
		$lb_mostrargrid=false;
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
		$ls_concsue1="";
		$ls_concsue2="";
		$ls_concsue3="";
		$ls_concsue4="";
		$ls_conccaj1="";
		$ls_conccaj2="";
		$ls_conccaj3="";
		$ls_conccaj4="";
		$ls_concpreper="";
		$ls_concpreesp="";
		$ls_concmontepio="";
		$ls_concfianza="";
		$ls_concprehip="";
		$ls_accion="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		unset($io_sno);
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
<title >Importar Pr&eacute;stamos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
	require_once("sigesp_sno_c_importarprestamos.php");
	$io_importar=new sigesp_sno_c_importarprestamos();
	require_once("../shared/class_folder/grid_param.php");
	$io_grid=new grid_param();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$ls_titletable="Campos";
			$li_widthtable=500;
			$ls_nametable="grid";
			$lo_title[1]="Código";
			$lo_campos[1][1]="";
			$ls_arctxt=$HTTP_POST_FILES["txtarctxt"]["tmp_name"]; 
			$ls_tiparctxt=$HTTP_POST_FILES["txtarctxt"]["type"]; 
			$ls_codarch=$_POST["txtcodarch"];
			$ls_denarch=$_POST["txtdenarch"];
			if($ls_tiparctxt=="text/plain")
			{
				$lb_valido=$io_importar->uf_importardatos($ls_arctxt,$ls_codarch,&$lo_title,&$lo_campos,&$li_totrow,$la_seguridad);
				$lb_mostrargrid=true;
			}
			else
			{
				$io_importar->io_mensajes->message("Tipo de archivo inválido. Solo se permiten archivos TXT.");
			}
			break;	
				

		case "GUARDAR":			
				$ls_codarch=$_POST["txtcodarch"];
				$ls_denarch=$_POST["txtdenarch"];				
				$li_totrow=$_POST["totrow"];
				$lb_valido=$io_importar->uf_procesarimportardatos($ls_codarch,$li_totrow,$la_seguridad);			
			break;	
		
	}
	$io_importar->uf_destructor();
	unset($io_importar);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>	
	<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" title="Descargar" alt="Salir" width="20" height="20" border="0"></a></div></td>	
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="301"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="68"><div align="center"></div></td>
    <td class="toolbar" width="3">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" enctype="multipart/form-data" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="750" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Importar Pr&eacute;stamos</td>
        </tr>
        <tr>
          <td width="118" height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="35"><input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>          </td>
          <td width="115"><div align="right">Fecha Inicio</div></td>
          <td width="137"><div align="left">
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
          </div></td>
          <td width="107"><div align="right">Fecha Fin </div></td>
          <td width="224"><div align="left">
              <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
          </div></td>
        </tr>
        
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="6" class="titulo-celdanew">Archivo a Importar</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Archivo TXT </div></td>
          <td colspan="5"><div align="left">
            <input name="txtarctxt" type="file" id="txtarctxt" size="50" maxlength="200">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Archivo </div></td>
          <td colspan="5"><div align="left">
            <input name="txtcodarch" type="text" size="6" maxlength="4" value="<? print $ls_codarch; ?>" readonly>
            <a href="javascript: ue_buscararchivo();"><img id="archivo" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenarch" type="text" class="sin-borde" id="txtdenarch" size="60" maxlength="120" value="<? print $ls_denarch; ?>" readonly>
			
          </div></td>
        </tr>
		<?php if($lb_mostrargrid)
		  		{
		   ?>
         <tr>
          <td height="22" colspan="6" align="center">
		  <?php 	
		  	$io_grid->makegrid($li_totrow,$lo_title,$lo_campos,$li_widthtable,$ls_titletable,$ls_nametable);
			unset($io_grid);
		   ?>		   </td>
          </tr>
		  <?php
		  		}
		   ?>
		 <tr>
          <td height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
          </table>		  </td>
          </tr>
        <tr>
          <td height="22"><input name="operacion" type="hidden" id="operacion">
          <input name="totrow" type="hidden" id="totrow" value="<?php print $li_totrow;?>">
          <input name="accion" type="hidden" id="accion">
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


function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		f.accion.value="";
		arctxt=ue_validarvacio(f.txtarctxt.value);
		codarch=ue_validarvacio(f.txtcodarch.value);
		if((arctxt!="")&&(codarch!=""))
		{
			f.operacion.value="PROCESAR";
			f.action="sigesp_sno_p_importarprestamos.php";
			f.submit();			
		}
		else
		{
			alert("Debe seleccionar el archivo a Importar.");
		}
			
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_guardar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{
		if (li_ejecutar==1)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sno_p_importarprestamos.php";
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

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}


function ue_buscararchivo()
{
	window.open("sigesp_snorh_cat_archivotxtprestamos?tipo=IMPORTAR","Archivos","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


function ue_descargar(ruta)
{
	window.open("sigesp_sno_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}


</script> 
</html>