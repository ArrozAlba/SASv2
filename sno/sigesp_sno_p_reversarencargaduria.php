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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_reversarencargaduria.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	//--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing.  María Beatriz Unda
		// Fecha Creación: 26/12/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codenc,$ls_codper,$ls_nomper;
		global $ls_operacion,$ls_existe,$io_fun_nomina,$ls_desnom,$ls_codnom,$li_rac,$li_subnomina;
		global $li_tipnom,$li_calculada;
		global $li_implementarcodunirac,$ls_codunirac,$ls_tipcuebanper,$ls_desper;
		global $li_loncueban, $li_valloncueban, $ls_grado;
		global $ld_fecinienc, $ld_fecfinenc,$ls_estsuspernom;
		global $ls_codperenc,$ls_nomperenc, $ls_desnomenc, $ls_codnomenc;
		global $li_racenc,$li_subnominaenc,$li_tipnomenc,$ls_codnomenc,$ls_obsenc,$ls_codenc,$ls_estenc,$ls_calenc;
				
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_estsuspernom="";
		$ls_codnomenc="";
		$ls_desnomenc="";
		$ls_codenc="";
		$ls_codper="";
		$ls_nomper="";			
		$ls_codperenc="";
		$ls_nomperenc="";				
		$li_racenc="";
		$li_subnominaenc="";			
		$li_tipnomenc="";			
		$ld_fecinienc="dd/mm/aaaa";
		$ld_fecfinenc="dd/mm/aaaa";				
		$ls_obsenc="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
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
		//	   Creado Por: Ing.  María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codenc,$ls_codper,$ls_nomper;
		global $ls_operacion,$ls_existe,$io_fun_nomina,$ls_desnom,$ls_codnom,$li_rac,$li_subnomina;
		global $li_tipnom,$li_calculada;
		global $li_implementarcodunirac;
		global  $ld_fecinienc, $ld_fecfinenc,$ls_obsenc,$ls_estsuspernom;
		global $ls_codperenc,$ls_nomperenc, $ls_desnomenc, $ls_codnomenc;		
		
		$ls_codenc=$_POST["txtcodenc"];
		$ls_estenc=$_POST["txtestenc"];
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];	
		$ls_codperenc=$_POST["txtcodperenc"];
		$ls_nomperenc=$_POST["txtnomperenc"];
		$ls_codnomenc=$_POST["txtcodnomenc"];
		$ls_desnomenc=$_POST["txtdesnomenc"];
		$ld_fecinienc=$_POST["txtfecinienc"];
		$ld_fecfinenc=$_POST["txtfecfinenc"];			
		$ls_obsenc=$_POST["txtobsenc"];
		$ls_estsuspernom=$_POST["txtestsuspernom"];
		
		
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
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.keyCode == 17 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Reverso de Encargadur&iacute;a</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
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
		
	require_once("sigesp_sno_c_reversarencargaduria.php");
	$io_encargaduria=new sigesp_sno_c_reversarencargaduria();	
	uf_limpiarvariables();		
	switch ($ls_operacion) 
	{
				
		case "PROCESAR":
			uf_load_variables();
			$lb_valido=$io_encargaduria->uf_reversar($ls_codenc,$ld_fecinienc, $ld_fecfinenc, $ls_obsenc, $ls_codper, $ls_codnomenc, $ls_codperenc,$ls_estsuspernom, $la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				
			}
			
			break;	

	}
unset ($io_encargaduria);

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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar 'alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    
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
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Reverso de Encargadur&iacute;a</td>
      </tr>
	  <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de la Encargadur&iacute;a</td>
      </tr> 
	  <tr>
          <td width="128" height="22"><div align="right">C&oacute;digo</div></td>
          <td width="566">
                <div align="left">
                  <input name="txtcodenc" type="text" id="txtcodenc" size="15" maxlength="10" value="<?php print $ls_codenc;?>"  readonly> <a href="javascript: ue_buscarencargaduria();"><img id="encargaduria" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="txtestenc" type="text" class="sin-borde2" id="txtestenc" value="<?php print trim($ls_estenc);?>" size="20" maxlength="20" readonly>                   
            </div></td>
          
        </tr>
	  <tr>
          <td height="22"><div align="right">Fecha Inicio</div></td>
          <td colspan="2"><input name="txtfecinienc" type="text" id="txtfecinienc" value="<?php print $ld_fecinienc;?>" size="15" maxlength="10" readonly></td>
        </tr>		
	   <tr>
          <td height="22"><div align="right">Fecha Finalizaci&oacute;n</div></td>
          <td colspan="2"><input name="txtfecfinenc" type="text" id="txtfecfinenc" value="<?php print $ld_fecfinenc;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          (modifque la fecha de Finalizaci&oacute;n de la Encargadur&iacute;a en caso de ser necesario) </td>
        </tr>
		<tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td colspan="2">
            <div align="left">
              <textarea name="txtobsenc" cols="80" rows="3" id="txtobsenc" onKeyUp="javascript: ue_validarcomillas(this);"> <?php print $ls_obsenc;?></textarea>
            </div></td>
        </tr>
      <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal </td>
      </tr>
      <tr>
        <td width="128" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper;?>" size="13" maxlength="10" readonly>
         </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="90" maxlength="120" readonly></td>
      </tr>
      
	  <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal Encargado</td>
      </tr>  
	  <tr>
          <td height="22"><div align="right">N&oacute;mina</div></td>
          <td colspan="4"><input name="txtcodnomenc" type="text" id="txtcodnomenc" size="13" maxlength="10" value="<?php print $ls_codnomenc;?>"  readonly>
            <input name="txtdesnomenc" type="text" class="sin-borde" id="txtdesnomenc" value="<?php print trim($ls_desnomenc);?>" size="80"  readonly></td>
        </tr>
		<tr>
        <td width="128" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><input name="txtcodperenc" type="text" id="txtcodperenc" value="<?php print $ls_codperenc;?>" size="13" maxlength="10" readonly>
            </td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><input name="txtnomperenc" type="text" class="sin-borde" id="txtnomperenc" value="<?php print $ls_nomperenc;?>" size="90" maxlength="120" readonly></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3">
		  <input name="txtestsuspernom" type="hidden" id="txtestsuspernom" value="<?php print $ls_estsuspernom;?>">
		   <input name="operacion" type="hidden" id="operacion">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
          <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">
		  <input name="racenc" type="hidden" id="racenc" value="<?php print $li_racenc;?>">
          <input name="subnomina" type="hidden" id="subnomina" value="<?php print $li_subnomina;?>">
		  <input name="subnominaenc" type="hidden" id="subnominaenc" value="<?php print $li_subnominaenc;?>">
          <input name="camuniadm" type="hidden" id="camuniadm" value="<?php print $li_camuniadm;?>">
          <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
          <input name="codunirac" type="hidden" id="codunirac" value="<?php print $li_implementarcodunirac;?>">		  
		  <input type="hidden" name="loncueban" id="loncueban" value="<?php print $li_loncueban;?>">
          <input type="hidden" name="valloncueban" id="valloncueban" value="<?php print $li_valloncueban;?>">
		  <input type="hidden" name="tiponom" id="tiponom" value="<?php print $li_tipnom;?>">
		  <input name="camdedtipper" type="hidden" id="camdedtipper" value="<?php print $li_camdedtipper;?>">		  </td>
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
	valido=true;
	f=document.form1;
	li_calculada=f.calculada.value;
	estenc=f.txtestenc.value;
	if (estenc!='FINALIZADA')
	{
		if(li_calculada=="0")
		{		
			li_incluir=f.incluir.value;
			li_cambiar=f.cambiar.value;
			lb_existe=f.existe.value;
			if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
			{
				codper = ue_validarvacio(f.txtcodper.value);
				codperenc = ue_validarvacio(f.txtcodperenc.value);
				codenc = ue_validarvacio(f.txtcodenc.value);
				fecinienc = ue_validarvacio(f.txtfecinienc.value);	
				fecfinenc = f.txtfecfinenc.value;
				if ((codper!="")&&(codperenc!="")&&(fecinienc!='dd/mm/aaaa')&&(fecinienc!=''))
				{
					if ((fecfinenc!='dd/mm/aaaa')&&(fecfinenc!='01/01/1900')&&(fecfinenc!=''))
					{
						if(!ue_comparar_fechas(fecinienc,fecfinenc))
						{
							alert("La fecha de Finalizacion es menor que la Fecha de Inicio de la Encargaduría.");
							valido=false;
						}
						else
						{
							f.operacion.value="PROCESAR";
							f.action="sigesp_sno_p_reversarencargaduria.php";
							f.submit();
						}
					}
					else
					 {
					 	alert ("Debe ingresar la Fecha de Finalización de la Encargaduría.");
					 }
				}
				else
				{
					alert ("Debe llenar todos los campos");
					
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
	else
	{
		alert("La Encargaduria se encuentra en estatus Finalizada. No se puede Reversar.");
	}
}


function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscarencargaduria()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_registroencargaduria.php?tipo=REVERSO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}



var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>