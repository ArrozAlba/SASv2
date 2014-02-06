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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_clasificacionobreros.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_grado, $li_suemin, $li_suemax, $la_tipcla, $ls_obscla, $io_fun_nomina, $ls_existe, $ls_operacion;
		global $ls_anovig, $ls_nrogac;
		
		$ls_grado="";
		$li_suemin="0,00";
		$li_suemax="0,00"; 
		$ls_obscla="";
		$la_tipcla[0]="";
		$la_tipcla[1]="";		
		$la_tipcla[2]="";		
		$ls_anovig="";
		$ls_nrogac="";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();				
		$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
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
   		global $ls_grado, $li_suemin, $li_suemax, $ls_tipcla, $ls_obscla,$ls_anovig, $ls_nrogac;
		
		$ls_grado=$_POST["txtgrado"];
		$li_suemin=$_POST["txtsuemin"];
		$li_suemax=$_POST["txtsuemax"];
		$ls_tipcla=$_POST["cmbtipcla"];
		$ls_obscla=$_POST["txtobscla"];
		$ls_anovig=$_POST["txtanovig"];
		$ls_nrogac=$_POST["txtnrogac"];
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
<title >Definici&oacute;n de Clasificaci&oacute;n de Obreros</title>
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<?php 
	require_once("sigesp_snorh_c_clasificacionobreros.php");
	$io_clasificacionobrero=new sigesp_snorh_c_clasificacionobreros();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_clasificacionobrero->uf_guardar($ls_existe,$ls_grado,$li_suemin,$li_suemax,$ls_tipcla,$ls_obscla,$ls_anovig,$ls_nrogac,
														   $la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("01-02-03",$ls_tipcla,$la_tipcla,3);
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_clasificacionobrero->uf_delete_clasificacionobrero($ls_grado,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("01-02-03",$ls_tipcla,$la_tipcla,3);
			}
			break;
	}
	$io_clasificacionobrero->uf_destructor();
	unset($io_clasificacionobrero);
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
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
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="450" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Clasificaci&oacute;n de Obreros </td>
        </tr>
        <tr>
          <td width="79" height="22">&nbsp;</td>
          <td width="317">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Grado</div></td>
          <td><div align="left">
            <input name="txtgrado" type="text" id="txtgrado" size="7" maxlength="4" value="<?php print $ls_grado;?>" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">A&ntilde;o en vigencia </div></td>
          <td>
            <input name="txtanovig" type="text" id="txtanovig" size="7" maxlength="4" value="<?php print $ls_anovig;?>" onKeyUp="javascript: ue_validarnumero(this);">
         </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nro de Gaceta </div></td>
          <td>
            <input name="txtnrogac" type="text" id="txtnrogac" size="12" maxlength="10" value="<?php print $ls_nrogac;?>" onKeyUp="ue_validarcomillas(this);">
         </td>
        </tr>
        <tr>
          <td height="22"><div align="right">Min&iacute;mo</div></td>
          <td><div align="left">
            <input name="txtsuemin" type="text" id="txtsuemin"  style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php  print $li_suemin; ?>" size="22" maxlength="23">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">M&aacute;ximo</div></td>
          <td><div align="left">
            <input name="txtsuemax" type="text" id="txtsuemax"  style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php  print $li_suemax; ?>" size="22" maxlength="23">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo</div></td>
          <td><div align="left">
            <select name="cmbtipcla" id="cmbtipcla">
              <option value="" selected>--Seleccione Una--</option>
              <option value="01" <?php print $la_tipcla[0];?>>No Calificado</option>
              <option value="02" <?php print $la_tipcla[1];?>>Calificado</option>
              <option value="03" <?php print $la_tipcla[2];?>>Supervisor</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td><div align="left">
            <textarea name="txtobscla" cols="55" rows="4" id="txtobscla" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_obscla; ?></textarea>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td>&nbsp;</td>
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
		f.action="sigesp_snorh_d_clasificacionobreros.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	valido=true;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		grado = ue_validarvacio(f.txtgrado.value);
		suemin = ue_validarvacio(f.txtsuemin.value);
		suemax = ue_validarvacio(f.txtsuemax.value);
		while(suemin.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			suemin=suemin.replace(".","");
		}
		suemin=suemin.replace(",",".");
		while(suemax.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			suemax=suemax.replace(".","");
		}
		suemax=suemax.replace(",",".");
		if(parseFloat(suemin)>parseFloat(suemax))
		{
			alert("El Sueldo Mínimo no puede ser Mayor que el Máximo.");
			valido=false;
		}
		if(valido)
		{
			if ((grado!="")&&(suemin!="")&&(suemax!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_clasificacionobreros.php";
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
			grado = ue_validarvacio(f.txtgrado.value);
			if (grado!="")
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_clasificacionobreros.php";
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
		window.open("sigesp_snorh_cat_clasificacionobrero.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_clasificacionobrero.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}
</script> 
</html>