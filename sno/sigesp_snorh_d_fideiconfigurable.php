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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_fideiconfigurable.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ld_anocurfid,$ls_codded,$ls_desded,$ls_codtipper,$ls_destipper,$li_diabonvacfid,$li_diabonfinfid,$ls_cueprefid,$ls_existe;
		global $ls_operacion,$io_fun_nomina,$ls_spgcuenta;
		
		$ld_anocurfid=1900;
		$ls_codded="";
		$ls_desded="";
		$ls_codtipper="";
		$ls_destipper="";
		$ls_cueprefid="";
		$li_diabonvacfid=0;
		$li_diabonfinfid=0;
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$ls_spgcuenta=$io_sno->uf_select_config("SNO","NOMINA","SPGCUENTA","401","C");
		unset($io_sno);
   }

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ld_anocurfid,$ls_codded,$ls_desded,$ls_codtipper,$ls_destipper,$li_diabonvacfid,$li_diabonfinfid,$ls_cueprefid;
		
		$ld_anocurfid=$_POST["txtanocurfid"];
		$ls_codded=$_POST["txtcodded"];
		$ls_desded=$_POST["txtdesded"];
		$ls_codtipper=$_POST["txtcodtipper"];
		$ls_destipper=$_POST["txtdestipper"];
		$li_diabonvacfid=$_POST["txtdiabonvacfid"];
		$li_diabonfinfid=$_POST["txtdiabonfinfid"];
		$ls_cueprefid=$_POST["txtcueprefid"];
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Configuraci&oacute;n de Fideicomiso</title>
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
	require_once("sigesp_snorh_c_fideiconfigurable.php");
	$io_fideiconfigurable=new sigesp_snorh_c_fideiconfigurable();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_fideiconfigurable->uf_guardar($ls_existe,$ld_anocurfid,$ls_codded,$ls_codtipper,$li_diabonvacfid,
														 $li_diabonfinfid,$ls_cueprefid,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_fideiconfigurable->uf_delete_fideiconfigurable($ld_anocurfid,$ls_codded,$ls_codtipper,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
	}
	$io_fideiconfigurable->uf_destructor();
	unset($io_fideiconfigurable);
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif"  title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="550" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td>
      <p>&nbsp;</p>
      <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana"> Configuraci&oacute;n de Fideicomiso</td>
        </tr>
        <tr>
          <td width="125" height="22">&nbsp;</td>
          <td width="319">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">A&ntilde;o </div></td>
          <td><div align="left">
            <input name="txtanocurfid" type="text" id="txtanocurfid" size="7" maxlength="4"  value="<?php print $ld_anocurfid;?>" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22">
              <div align="right">Dedicaci&oacute;n</div></td>
	      <td><div align="left">
	        <input name="txtcodded" type="text" id="txtcodded" value="<?php print $ls_codded;?>" size="7" maxlength="3" readonly>
	        <a href="javascript: ue_buscardedicacion();"><img id="dedicacion" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>	        
	        <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" value="<?php print $ls_desded;?>" size="40" readonly>
	        </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Tipo Personal </div></td>
		  <td><div align="left">
		    <input name="txtcodtipper" type="text" id="txtcodtipper" value="<?php print $ls_codtipper;?>" size="7" maxlength="4" readonly>
		    <a href="javascript: ue_buscartipopersonal();"><img id="tipopersonal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
		    <input name="txtdestipper" type="text" class="sin-borde" id="txtdestipper" value="<?php print $ls_destipper;?>" size="40" maxlength="100" readonly>		    
		    </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Cuenta Presupuestaria </div></td>
          <td><input name="txtcueprefid" type="text" id="txtcueprefid" value="<?php print $ls_cueprefid;?>" size="28" maxlength="25" readonly>
            <a href="javascript: ue_buscarcuentapresupuesto();"><img id="cuentapresupuesto" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="22"><div align="right">D&iacute;as Bono Vacacional </div></td>
          <td><div align="left">
            <input name="txtdiabonvacfid" type="text" id="txtdiabonvacfid" size="7" maxlength="4"  value="<?php print $li_diabonvacfid;?>" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">D&iacute;as Bono Fin de A&ntilde;o </div></td>
          <td hight="22"e><div align="left">
            <input name="txtdiabonfinfid" type="text" id="txtdiabonfinfid" value="<?php print $li_diabonfinfid;?>" size="7" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22"><input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
              <input name="spg_cuenta" type="hidden" id="spg_cuenta" value="<?php print $ls_spgcuenta;?>">
			  </td>
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
		f.action="sigesp_snorh_d_fideiconfigurable.php";
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
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		anocurfid=ue_validarvacio(f.txtanocurfid.value);
		codded=ue_validarvacio(f.txtcodded.value);
		codtipper=ue_validarvacio(f.txtcodtipper.value);
		diabonvacfid=ue_validarvacio(f.txtdiabonvacfid.value);
		diabonfinfid=ue_validarvacio(f.txtdiabonfinfid.value);
		if((anocurfid!="")&&(codded!="")&&(codtipper!="")&&(diabonvacfid!="")&&(diabonfinfid!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_fideiconfigurable.php";
			f.submit();
		}
		else
		{
			alert("Debe llenar todos los datos.");
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
			anocurfid=ue_validarvacio(f.txtanocurfid.value);
			codded=ue_validarvacio(f.txtcodded.value);
			codtipper=ue_validarvacio(f.txtcodtipper.value);
			if((anocurfid!="")&&(codded!="")&&(codtipper!=""))
			{
				if(confirm("¿Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_fideiconfigurable.php";
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

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_fideiconfigurable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
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

function ue_buscardedicacion()
{
	f=document.form1;
	if(f.existe.value=="FALSE")
	{
		window.open("sigesp_snorh_cat_dedicacion.php?tipo=fideiconfigurable","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscartipopersonal()
{
	f=document.form1;
	codded=ue_validarvacio(f.txtcodded.value);
	if(f.existe.value=="FALSE")
	{
		if(codded!="")
		{
			window.open("sigesp_snorh_cat_tipopersonal.php?tipo=fideiconfigurable&codded="+codded+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("Debe seleccionar una Dedicación.");
		}		
	}
}

function ue_buscarcuentapresupuesto()
{
	f=document.form1;
	spg_cuenta=f.spg_cuenta.value;
	window.open("sigesp_sno_cat_cuentapresupuesto.php?tipo=FIDEICOMISO&spg_cuenta="+spg_cuenta,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>