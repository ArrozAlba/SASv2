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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_trabajoanterior.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $li_codtraant,$ls_emptraant,$ls_ultcartraant,$li_ultsuetraant,$ld_fecingtraant,$ld_fecrettraant,$ls_existe,$ls_operacion;
		global $io_fun_nomina,$ls_codded,$ls_desded,$li_anolab,$li_meslab,$li_dialab;
		
		$li_codtraant=0;
		$ls_emptraant="";
		$ls_ultcartraant="";
		$li_ultsuetraant=0;
		$ld_fecingtraant="dd/mm/aaaa";			
		$ld_fecrettraant="dd/mm/aaaa";
		$ls_codded="";
		$ls_desded="";
		$li_anolab="";			
		$li_meslab="";			
		$li_dialab="";			
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
   		global $ls_codper, $ls_nomper, $li_codtraant, $ls_emptraant, $ls_ultcartraant, $li_ultsuetraant;
		global $ld_fecingtraant,$ld_fecrettraant,$ld_fecnacper,$ld_fecingper,$ls_emppubtraant,$ls_codded,$ls_desded,$li_anolab,$li_meslab,$li_dialab;
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ld_fecnacper=$_POST["txtfecnacper"];
		$ld_fecingper=$_POST["txtfecingper"];
		$li_codtraant=$_POST["txtcodtraant"];
		$ls_emptraant=$_POST["txtemptraant"];
		$ls_ultcartraant=$_POST["txtultcartraant"];
		$li_ultsuetraant=$_POST["txtultsuetraant"];
		$ld_fecingtraant=$_POST["txtfecingtraant"];
		$ld_fecrettraant=$_POST["txtfecrettraant"];
		$ls_emppubtraant=$_POST["cmbemppubtraant"];	
		$ls_codded=$_POST["txtcodded"];	
		$ls_desded=$_POST["txtdesded"];	
		$li_anolab=$_POST["txtanolab"];		
		$li_meslab=$_POST["txtmeslab"];		
		$li_dialab=$_POST["txtdialab"];		
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
<title >Definici&oacute;n de Trabajo Anterior</title>
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
	require_once("sigesp_snorh_c_trabajoanterior.php");
	$io_trabajo=new sigesp_snorh_c_trabajoanterior();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			$ld_fecnacper=$_GET["fecnacper"];
			$ld_fecingper=$_GET["fecingper"];
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_trabajo->uf_guardar($ls_existe,$ls_codper,$li_codtraant,$ls_emptraant,$ls_ultcartraant,$li_ultsuetraant,
											   $ld_fecingtraant,$ld_fecrettraant,$ls_emppubtraant,$ls_codded,$li_anolab,$li_meslab,
											   $li_dialab,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_trabajo->uf_delete_trabajoanterior($ls_codper,$li_codtraant,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			break;
	}
	$io_trabajo->uf_destructor();
	unset($io_trabajo);
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif"  title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
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
<table width="650" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2" class="sin-borde2"><input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Trabajo Anterior </td>
        </tr>
        <tr>
          <td width="114" height="22">&nbsp;</td>
          <td width="480">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodtraant" type="text" id="txtcodtraant" value="<?php print $li_codtraant;?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre Empresa </div></td>
          <td><div align="left">
            <input name="txtemptraant" type="text" id="txtemptraant" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_emptraant;?>" size="63" maxlength="100">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">&Uacute;timo Cargo </div></td>
          <td><div align="left">
            <input name="txtultcartraant" type="text" id="txtultcartraant" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_ultcartraant;?>" size="63" maxlength="100">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">&Uacute;ltimo Sueldo </div></td>
          <td><div align="left">
            <input name="txtultsuetraant" type="text" id="txtultsuetraant" value="<?php print $li_ultsuetraant;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Ingreso </div></td>
          <td><div align="left">
            <input name="txtfecingtraant" type="text" id="txtfecingtraant" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecingtraant;?>" size="15" maxlength="10" datepicker="true">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso </div></td>
          <td><div align="left">
            <input name="txtfecrettraant" type="text" id="txtfecrettraant" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this); ue_actualizar();" value="<?php print $ld_fecrettraant;?>" size="15" maxlength="10" datepicker="true">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo de Empresa </div></td>
          <td><select name="cmbemppubtraant" id="cmbemppubtraant">
            <option value="" selected>--Seleccione--</option>
            <option value="1">P&uacute;blica</option>
            <option value="0">Privada</option>
          </select>          </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Dedicaci&oacute;n</div></td>
          <td><input name="txtcodded" type="text" id="txtcodded" value="<?php print $ls_codded;?>" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscardedicacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" value="<?php print $ls_desded;?>" size="70" maxlength="100" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">A&ntilde;os Laborados </div></td>
          <td><input name="txtanolab" type="text" id="txtanolab" value="<?php print $li_anolab;?>" size="6" maxlength="3" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Meses Laborados </div></td>
          <td><input name="txtmeslab" type="text" id="txtmeslab" value="<?php print $li_meslab;?>" size="6" maxlength="3" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">D&iacute;as Laborados </div></td>
          <td><input name="txtdialab" type="text" id="txtdialab" value="<?php print $li_dialab;?>" size="6" maxlength="3" style="text-align:right" onKeyUp="javascript: ue_validarnumero(this);" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
            <input name="txtfecingper" type="hidden" id="txtfecingper" value="<?php print $ld_fecingper;?>">
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
		fecingper=ue_validarvacio(f.txtfecingper.value);	
		f.action="sigesp_snorh_d_trabajoanterior.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"&fecingper="+fecingper+"";
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
	ue_actualizar();
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		codper = ue_validarvacio(f.txtcodper.value);
		codtraant = ue_validarvacio(f.txtcodtraant.value);
		emptraant = ue_validarvacio(f.txtemptraant.value);
		f.txtfecingtraant.value=ue_validarfecha(f.txtfecingtraant.value);	
		fecingtraant=ue_validarvacio(f.txtfecingtraant.value);	
		f.txtfecrettraant.value=ue_validarfecha(f.txtfecrettraant.value);	
		fecrettraant=ue_validarvacio(f.txtfecrettraant.value);	
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		fecingper=ue_validarvacio(f.txtfecingper.value);
		if(!ue_comparar_fechas(fecingtraant,fecrettraant))
		{
			alert("La fecha de Egreso es menor que la de Ingreso.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecnacper,fecingtraant))
		{
			alert("La fecha de Ingreso es menor que la de Nacimiento del personal.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecnacper,fecrettraant))
		{
			alert("La fecha de Egreso es menor que la de Nacimiento del personal.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecingtraant,fecingper))
		{
			alert("La fecha de Ingreso a la institución es menor que la de Ingreso al trabajo anterior.");
			valido=false;
		}
		if(!ue_comparar_fechas(fecrettraant,fecingper))
		{
			alert("La fecha de Egreso a la institución es menor que la de Ingreso al trabajo anterior.");
			valido=false;
		}
		if(valido)
		{
			if ((codper!="")&&(codtraant!="")&&(emptraant!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_trabajoanterior.php";
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
			codtraant = ue_validarvacio(f.txtcodtraant.value);
			if ((codper!="")&&(codtraant!="")&&(codtraant!="0"))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_trabajoanterior.php";
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

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_trabajoanterior.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscardedicacion()
{
	window.open("sigesp_snorh_cat_dedicacion.php?tipo=trabajoant","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_actualizar()
{
	f=document.form1;
	f.txtfecingtraant.value=ue_validarfecha(f.txtfecingtraant.value);	
	fecingtraant=ue_validarvacio(f.txtfecingtraant.value);	
	f.txtfecrettraant.value=ue_validarfecha(f.txtfecrettraant.value);	
	fecrettraant=ue_validarvacio(f.txtfecrettraant.value);	
	if(!ue_comparar_fechas(fecingtraant,fecrettraant))
	{
		alert("La fecha de Egreso es menor que la de Ingreso.");
		valido=false;
	}
	dia1 = fecingtraant.substr(0,2);
	mes1 = fecingtraant.substr(3,2);
	ano1 = fecingtraant.substr(6,4);

	dia2 = fecrettraant.substr(0,2);
	mes2 = fecrettraant.substr(3,2);
	ano2 = fecrettraant.substr(6,4);
	if(eval(dia1)>eval(dia2))
	{
		f.txtdialab.value=eval("(30-"+dia1+")+"+dia2+"");
	}
	else
	{
		f.txtdialab.value=eval(""+dia2+"-"+dia1+"");
	}
	if(eval(mes1)>eval(mes2))
	{
		f.txtmeslab.value=eval("(12-"+mes1+")+"+mes2+"");
		ano2=ano2-1;
	}
	else
	{
		f.txtmeslab.value=eval(""+mes2+"-"+mes1+"");
	}
	f.txtanolab.value=eval(""+ano2+"-"+ano1+"");

}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>