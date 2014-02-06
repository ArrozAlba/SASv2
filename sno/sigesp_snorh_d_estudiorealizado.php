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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_estudiorealizado.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $li_codestrea,$ls_insestrea,$ls_titestrea,$li_calestrea,$ld_fecgraestrea,$la_tipestrea,$ls_operacion,$ls_existe;
		global $ls_escval,$ls_desestrea,$ld_feciniact,$ld_fecfinact,$la_aprestrea,$io_fun_nomina,$ls_aprestrea,$li_anoaprestrea;
		global $li_horestrea;
		
		$ld_fecgraestrea=date("d/m/Y");
		$ld_feciniact=date("d/m/Y");
		$ld_fecfinact=date("d/m/Y");
		$li_codestrea=0;
		$ls_insestrea="";
		$ls_titestrea="";
		$li_calestrea=0;
		$ld_fecgraestrea="dd/mm/aaaa";
		$la_tipestrea[0]="";
		$la_tipestrea[1]="";
		$la_tipestrea[2]="";
		$la_tipestrea[3]="";		
		$la_tipestrea[4]="";
		$la_tipestrea[5]="";
		$la_tipestrea[6]="";
		$la_tipestrea[7]="";
		$la_tipestrea[8]="";		
		$la_tipestrea[9]="";
		$la_tipestrea[10]="";
		$la_aprestrea[0]="";
		$la_aprestrea[1]="";		
		$ls_escval="";
		$ls_desestrea="";
		$ls_aprestrea="0";		
		$li_anoaprestrea="0";
		$li_horestrea="0";
		$ld_feciniact="dd/mm/aaaa";
		$ld_fecfinact="dd/mm/aaaa";
		//$ld_fecgraestrea="01-01-1900";
		//$ld_feciniact="01-01-1900";
		//$ld_fecfinact="01-01-1900";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
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
   		global $ls_codper, $ls_nomper, $li_codestrea,$ls_tipestrea,$ls_insestrea,$ls_titestrea,$ld_fecnacper;
		global $li_calestrea,$ld_fecgraestrea,$ls_escval,$ls_desestrea,$ld_feciniact,$ld_fecfinact,$ls_aprestrea,$li_anoaprestrea;
		global $li_horestrea;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ld_fecnacper=$_POST["txtfecnacper"];
		$li_codestrea=$_POST["txtcodestrea"];
		$ls_desestrea=$_POST["txtdesestrea"];
		$ls_tipestrea=$_POST["cmbtipestrea"];
		$ls_insestrea=$_POST["txtinsestrea"];
		$ls_titestrea=$_POST["txttitestrea"];
		$li_calestrea=$_POST["txtcalestrea"];
		$ld_fecgraestrea=$_POST["txtfecgraestrea"];
		$ls_escval=$_POST["txtescval"];		
		$ld_feciniact=$_POST["txtfeciniact"];
		$ld_fecfinact=$_POST["txtfecfinact"];
		$ls_aprestrea=$_POST["cmbaprestrea"];
		$li_anoaprestrea=$_POST["txtanoaprestrea"];
		$li_horestrea=$_POST["txthorestrea"];
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
<title >Definici&oacute;n de Estudio Realizado</title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/nomina.css" rel="stylesheet" type="text/css">
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
	require_once("sigesp_snorh_c_estudiorealizado.php");
	$io_estudio=new sigesp_snorh_c_estudiorealizado();	
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			$ld_fecnacper=$_GET["fecnacper"];
			break;

		case "GUARDAR":
			uf_load_variables();
			if ($ld_fecincact=="dd/mm/aaaa")
			{
			   $ld_fecgraestrea="1900-01-01";
			   $ld_feciniact="1900-01-01";
			   $ld_fecfinact="1900-01-01";
			}
			$lb_valido=$io_estudio->uf_guardar($ls_existe,$ls_codper,$li_codestrea,$ls_tipestrea,$ls_insestrea,$ls_titestrea,
											   $li_calestrea,$ld_fecgraestrea,$ls_escval,$ld_feciniact,$ld_fecfinact,$ls_desestrea,
											   $ls_aprestrea,$li_anoaprestrea,$li_horestrea,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3-4-5-6-7-8-9-10",$ls_tipestrea,$la_tipestrea,11);
				$io_fun_nomina->uf_seleccionarcombo("0-1",$ls_aprestrea,$la_aprestrea,2);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_estudio->uf_delete_estudiorealizado($ls_codper,$li_codestrea,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3-4-5-6-7-8-9-10",$ls_tipestrea,$la_tipestrea,11);
			}
			break;
	}
	$io_estudio->uf_destructor();
	unset($io_estudio);
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_personal.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136" >
      <p>&nbsp;</p>
      <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="4"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Definici&oacute;n de Estudio Realizado </td>
        </tr>
        <tr>
          <td width="109" height="22">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtcodestrea" type="text" id="txtcodestrea" value="<?php print $li_codestrea;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Tipo </div></td>
          <td colspan="3">
            <div align="left">
              <select name="cmbtipestrea" id="cmbtipestrea">
                <option value="" selected>--Seleccione Uno--</option>
                <option value="0" <?php print $la_tipestrea[0];?>>Primaria</option>
                <option value="1" <?php print $la_tipestrea[1];?>>Ciclo Básico</option>
                <option value="2" <?php print $la_tipestrea[2];?>>Ciclo Diversificado</option>
                <option value="3" <?php print $la_tipestrea[3];?>>Pregrado</option>
                <option value="4" <?php print $la_tipestrea[4];?>>Especializaci&oacute;n</option>
                <option value="5" <?php print $la_tipestrea[5];?>>Maestr&iacute;a</option>
                <option value="6" <?php print $la_tipestrea[6];?>>Post Grado</option>
                <option value="7" <?php print $la_tipestrea[7];?>>Doctorado</option>
                <option value="8" <?php print $la_tipestrea[8];?>>Taller</option>
                <option value="9" <?php print $la_tipestrea[9];?>>Curso</option>
                <option value="10" <?php print $la_tipestrea[10];?>>Seminarios</option>
              </select>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Instituto</div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtinsestrea" type="text" id="txtinsestrea" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_insestrea;?>" size="100" maxlength="254">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Descripci&oacute;n</div></td>
          <td colspan="3"><input name="txtdesestrea" type="text" id="txtdesestrea" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_desestrea;?>" size="100" maxlength="254"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">T&iacute;tulo Obtenido </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txttitestrea" type="text" id="txttitestrea" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_titestrea;?>" size="100" maxlength="254">
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Calificaci&oacute;n</div></td>
          <td width="141">
            <div align="left">
              <input name="txtcalestrea" type="text" id="txtcalestrea" value="<?php print $li_calestrea;?>" size="8" maxlength="5" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
            </div></td>
		    <td width="83"><div align="right">Escala</div></td>
              <td width="307" >
                <div align="left">
                  <input name="txtescval" type="text" id="txtescval" value="<?php print $ls_escval;?>" size="25" maxlength="20" onKeyUp="javascript: ue_validarcomillas(this);">
            </div>                    </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Aprobado</div></td>
          <td colspan="3">
            <select name="cmbaprestrea" id="cmbaprestrea">
              <option value="0" <?php print $la_aprestrea[0];?>>No</option>
              <option value="1" <?php print $la_aprestrea[1];?>>Si</option>
            </select>          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">&Uacute;ltimo A&ntilde;o Aprobado </div></td>
          <td><input name="txtanoaprestrea" type="text" id="txtanoaprestrea" value="<?php print $li_anoaprestrea;?>" size="6" maxlength="1" onKeyUp="javascript: ue_validarnumero(this);"></td>
          <td><div align="right">Horas</div></td>
          <td><label>
            <input name="txthorestrea" type="text" id="txthorestrea" value="<?php print $li_horestrea;?>" size="6" maxlength="3"  onKeyUp="javascript: ue_validarnumero(this);">
          </label></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Inicio </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfeciniact" type="text" id="txtfeciniact" value="<?php print $ld_feciniact;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">Fecha de Finalizaci&oacute;n </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfecfinact" type="text" id="txtfecfinact" value="<?php print $ld_fecfinact;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
            </div></td></tr>
        <tr>
          <td height="22"><div align="right">Fecha Grado </div></td>
          <td colspan="3">
            <div align="left">
              <input name="txtfecgraestrea" type="text" id="txtfecgraestrea" value="<?php print $ld_fecgraestrea;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
              </div></td></tr>
        <tr>
          <td><div align="right"></div></td>
          <td colspan="3"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
            <input name="txtfecnacper" type="hidden" id="txtfecnacper" value="<?php print $ld_fecnacper;?>"></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
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
		f.existe.value="FALSE";	
		codper=ue_validarvacio(f.txtcodper.value);
		nomper=ue_validarvacio(f.txtnomper.value);	
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		f.action="sigesp_snorh_d_estudiorealizado.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_personal.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		codper = ue_validarvacio(f.txtcodper.value);
		titestrea = ue_validarvacio(f.txttitestrea.value);
		codestrea = ue_validarvacio(f.txtcodestrea.value);
		f.txtfecgraestrea.value=ue_validarfecha(f.txtfecgraestrea.value);	
		fecgraestrea=ue_validarvacio(f.txtfecgraestrea.value);
		fecnacper = ue_validarvacio(f.txtfecnacper.value);
		feciniact = ue_validarvacio(f.txtfeciniact.value);
		fecfinact = ue_validarvacio(f.txtfecfinact.value);
		//if (feciniact!="1900-01-01")
		if(!(feciniact=="1900-01-01"))
		{
		   if(!ue_comparar_fechas(fecnacper,feciniact))
		   {
			  alert("La fecha de Inicio del Estudio es menor que la de Nacimiento del Personal.");
			  valido=false;
		   }
		}
		if(!(fecgraestrea=="1900-01-01"))
		{
		   if(!ue_comparar_fechas(fecnacper,fecgraestrea))
		   {
			  alert("La fecha de Grado es menor que la de Nacimiento del Personal.");
			  valido=false;
		   }
		}
		if(!((feciniact=="1900-01-01")||(fecfinact=="1900-01-01")))   
		{
		   if(!ue_comparar_fechas(feciniact,fecfinact))
		   {
			  alert("La fecha de Finalización de la actividad es menor a la de Inicio.");
			  valido=false;
		   }
		}   
		if(!((fecfinact=="1900-01-01")||(fecgraestrea=="1900-01-01"))) 
		{
		   if(!ue_comparar_fechas(fecfinact,fecgraestrea))
		   {
			 alert("La fecha de grado de la actividad es menor a la de finalización.");
			 valido=false;
		   }
		}  
		if(valido)
		{
			 f.txtfeciniact.value = ue_validarfecha(f.txtfeciniact.value);
			 f.txtfecfinact.value = ue_validarfecha(f.txtfecfinact.value);
			 if ((codper!="")&&(titestrea!="")&&(codestrea!="")&&(feciniact!="")&&(fecfinact!="")&&(fecgraestrea!=""))
			 {
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_estudiorealizado.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
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
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codper = ue_validarvacio(f.txtcodper.value);
			codestrea = ue_validarvacio(f.txtcodestrea.value);
			if ((codper!="")&&(codestrea!="")&&(codestrea!="0"))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_estudiorealizado.php";
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

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_estudiorealizado.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
</script> 
</html>