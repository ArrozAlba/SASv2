<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de Plan de Cuentas de Gasto.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="800" height="42"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div>      
    <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_datastore.php");
	$msg=new class_mensajes();
	require_once("../shared/class_folder/class_sigesp_int.php");
	require_once("../shared/class_folder/class_fecha.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("sigesp_scg_c_ctas_oaf.php");
	$io_cta=new sigesp_scg_c_ctas_oaf();
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="SCG";
	$ls_ventanas="sigesp_scg_ctas_oaf.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_accesos["leer"]=     $_POST["leer"];
			$la_accesos["incluir"]=  $_POST["incluir"];
			$la_accesos["cambiar"]=  $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]=   $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if  (array_key_exists("status",$_POST))
	{
  	  $ls_estatus=$_POST["status"];
	}
else
	{
	  $ls_estatus="NUEVO";	  
	}	
	
	
	$dat=$_SESSION["la_empresa"];
	$ls_formato=$dat["formpre"];
	$ls_formatoaux = str_replace( "-", "",$ls_formato);
	$li_size_cta=strlen($ls_formatoaux);
	//Arreglo que contiene los parametros de configuracion de la empresa
	$dat=$_SESSION["la_empresa"];
	
	//Instancia de la clase de manejo de Grid dinamico
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_funciones.php");
	$io_grid=new grid_param();
	$io_fun=new class_funciones();
	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];		
	}
	else
	{
		$ls_operacion = "NUEVO";	
	}
	 	 //Titulos de la grid de Cuentas Presupuestarias.
		$title[1]="Cuenta";   $title[2]="Denominación";     $title[3]="Cuenta Res.";		$title[4]="Incremento/Disminucion";
		
  		//Nombr del grid
		$grid1="grid";	
		//Total de filas iniciales del grid
	/////////////////////// N U E V O///////////////////////////////////////////////////////////////////
	if($ls_operacion=="NUEVO")
	{
		$ls_estpro1   = "";
		$ls_estpro2   = "";
		$ls_estpro3   = "";	
		$ls_denestpro1  ="";
		$ls_denestpro2  ="";
		$ls_denestpro3  ="";
		$io_cta->uf_cargar_cuentas(&$object,&$total);
		$lastrow      = 0;
	}	
	/////////////////////// G U A R D A R///////////////////////////////////////////////////////////////////
	if($ls_operacion=="GUARDAR")
	{
	
		$total=$_POST["total"];
		$lastrow=$_POST["lastrow"];
		$li_error=0;
		$li_save=0;
		$io_cta->io_sql->begin_transaction();
		for($i=1;$i<=$total;$i++)
		{
			$ls_cuentascg   = $_POST["txtcuentascg".$i];			
			$ls_dencuenta= $_POST["txtdencuenta".$i];
			$ls_cuentares   = $_POST["txtcuentares".$i];
			if(($ls_cuentascg!="")&&($ls_dencuenta!="")&&($ls_cuentares!=""))
			{
				$lb_valido=$io_cta->uf_guardar_cuentas_reporte($ls_cuentascg,$ls_cuentares,$ls_dencuenta);
			}
		}
		if($lb_valido)
		{
			$msg->message("Registro Guardado");
			$ls_descripcion="Actualizo la tabla scg_pc_reporte de programacion de reportes para el formato 0718 de origen y aplicacion de fondos";
			$io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,'UPDATE',$ls_logusr,$ls_ventanas,$ls_descripcion);
			$io_cta->io_sql->commit();
		}
		else
		{
			$io_cta->io_sql->rollback();
			$msg->message("Error ");
		}
		$io_cta->uf_cargar_cuentas(&$object,&$total);		
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="718" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="716" height="221" valign="top">
		<form name="form1" method="post" action="" >
<?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
		
          <p>&nbsp;</p>
          <table width="680" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td width="678">Configuraci&oacute;n de Reporte Origen y Aplicacion de Fondos Forma (0719) </td>
              </tr>
              <tr class="formato-blanco">
                <td height="20" valign="bottom">  Cuenta
                  <input name="txtfind" type="text" id="txtfind">
                  <input type="button" name="Submit" value="Buscar" onClick="javascript:uf_find_valor('txtcuentascg','txtfind');"></td>
              </tr>
            <tr class="formato-blanco">
              <td height="20"><p align="center"><?php $io_grid->make_gridScroll($total,$title,$object,620,'Detalles Cuenta',$grid1,310);?>
                  <input name="total" type="hidden" id="total" value="<?php print $total?>">
                </p>
              </td>
            </tr>
          </table>
            <p align="center">&nbsp;          </p>
            <p align="center">
              <input name="operacion"  type="hidden" id="operacion" >
              <input name="lastrow"    type="hidden" id="lastrow"    value="<?php print $lastrow;?>" >
              <input name="filadelete" type="hidden" id="filadelete">
            </p>
		</form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_spg_d_planctas.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_guardar()
{
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
if ((li_cambiar==1)||(li_incluir==1))
   {
	  f.operacion.value ="GUARDAR";
	  f.action="sigesp_scg_ctas_oaf.php";
	  f.submit();
    }
 else
    {
 	  alert("No tiene permiso para realizar esta operación");
	}	
}					

function cat_plan(fila)
{
	
	f=document.form1;
	ls_cuentaspg=eval("f.txtcuentascg"+fila+".value;");
	ls_dencuenta=eval("f.txtdencuenta"+fila+".value;");
	if((ls_cuentaspg!="")&&(ls_dencuenta!=""))
	{
		window.open("sigesp_sel_scg_plancuentaspg.php?fila="+fila,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Seleccione la cuenta contable");
	}
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
function uf_blanquear()
{
	f=document.form1;
	f.operacion.value="BLANQUEAR";
	f.action="sigesp_spg_d_planctas.php";
	f.submit();
}

function uf_rellenar_cuenta(longitud,li_i)
{
		cadena_ceros="";
		f=document.form1;
		cadena=	eval("f.txtcuentaspg"+li_i+".value");
		lencad=cadena.length;
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena+cadena_ceros;
		eval("document.form1.txtcuentaspg"+li_i+".value="+cadena);
}
function uf_find_valor(obj,value)
{
	f=document.form1;
	ls_valor=eval("f."+value+".value");
	li_len=ls_valor.length;
	li_tot=f.total.value;
	for(li_a=1;li_a<=li_tot;li_a++)
	{
		ls_actual=eval("f."+obj+li_a+".value");
		ls_aux=ls_actual.substr(0,li_len);
		if(ls_aux==ls_valor)
		{
			eval("f."+obj+li_a+".focus()");
			break;			
		}
	}
	
}


	

</script>
</html>