<?php
session_start();
$dat           = $_SESSION["la_empresa"];
$ls_nomestpro1 = $dat["nomestpro1"];
$ls_nomestpro2 = $dat["nomestpro2"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
   $ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
   $ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
   $ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
   if( $_SESSION["ir3"]=='1')
   {$ls_clasificacion = $_POST["txtclasificacion"];
    $_SESSION["ir3"]='0';}
   else
   {
	   $_SESSION["go"]='1';
	   //$ls_clasificacion = $_POST["rbclasificacion"];
	   if  (array_key_exists("ls_clasificacion",$_GET))
		{
		  $ls_clasificacion = $_GET["ls_clasificacion"];
		}
		else
		{
		  $ls_clasificacion="";
		}
	}
   
  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <?php print $ls_nomestpro2 ?>  </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
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
a:hover {
	color: #006699;
}
-->
</style></head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>      </td>
  </tr>
</table>
<?php
	require_once("class_folder/sigesp_spg_c_estprog2.php");
	require_once("class_folder/sigesp_spg_c_estprog1.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_conect  = new sigesp_include();
    $conn       = $io_conect->uf_conectar();
	$io_funcion = new class_funciones(); 
	$io_msg     = new class_mensajes();
	$io_sql     = new class_sql($conn);
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad = new sigesp_c_seguridad();
	
	$ls_empresa               = $dat["codemp"];
	$ls_codemp                = $ls_empresa; 
	$ls_logusr                = $_SESSION["la_logusr"];
	$ls_sistema               = "CFG";
	$ls_ventanas              = "sigesp_spg_d_estprog2.php";
	
	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
    $li_estmodest             = $dat["estmodest"];
	if (array_key_exists("txtclasificacion",$_POST))
	{
		$ls_estcla=$_POST["txtclasificacion"];
	}
	

	if (array_key_exists("permisos2",$_POST)||($ls_logusr=="PSEGIS"))
	   {	
		 if ($ls_logusr=="PSEGIS")
		    {
			  $ls_permisos="";
		    }
		 else
		    {
			  $ls_permisos            = $_POST["permisos2"];
			  $la_accesos["leer"]     = $_POST["leer"];			
			  $la_accesos["incluir"]  = $_POST["incluir"];			
			  $la_accesos["cambiar"]  = $_POST["cambiar"];
			  $la_accesos["eliminar"] = $_POST["eliminar"];
			  $la_accesos["imprimir"] = $_POST["imprimir"];
			  $la_accesos["anular"]   = $_POST["anular"];
			  $la_accesos["ejecutar"] = $_POST["ejecutar"];
		    }
	   }
	else
	   {
		 $la_accesos["leer"]     = "";	 	
		 $la_accesos["incluir"]  = "";
		 $la_accesos["cambiar"]  = "";
		 $la_accesos["eliminar"] = "";
		 $la_accesos["imprimir"] = "";
		 $la_accesos["anular"]   = "";
		 $la_accesos["ejecutar"] = "";
		 $ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	   }
	//Inclusión de la clase de seguridad.
	$io_estpro1 = new sigesp_spg_c_estprog1($conn);
	$io_estpro2 = new sigesp_spg_c_estprog2($conn);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if  (array_key_exists("statusprog2",$_POST))
	{
  	  $ls_status=$_POST["statusprog2"];
	}
	else
	{
	  $ls_status="NUEVO";	  
	}	

if (array_key_exists("operacionestprog2",$_POST))
   {
	 $ls_operacion  = $_POST["operacionestprog2"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
	 $readonly      = "";
	 $disabled      = "";
   }
else
   {
     $ls_operacion  = "";
  	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
 	 if (array_key_exists("txtcodestpro2",$_POST))
		{
		  $ls_codestpro2 = $_POST["txtcodestpro2"];
		  $ls_denestpro2 = $_POST["txtdenestpro2"];
		}
	 else
		{
		  $ls_codestpro2 = "";
		  $ls_denestpro2 = "";
		}
	 $disabled = "disabled";
	 $readonly = "";
   }

if ($ls_operacion == "NUEVO")
   {
     $ls_codestpro1 = $_POST["txtcodestpro1"];
  	 $ls_codestpro2 = "";
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	  $ls_clasificacion = $_POST["txtclasificacion"];
	 $ls_denestpro2 = "";
	 $readonly      = "";
 	 $disabled      = "disabled";
	 $ls_status     = "NUEVO";
   }

if ($ls_operacion == "GUARDAR")
   { $ls_clasificacion=$_POST["txtclasificacion"];
	 $ls_codestp1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	 $ls_codestp2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
	 $lb_encontrado = $io_estpro1->uf_spg_select_estprog1($ls_codemp,$ls_codestp1,$ls_estcla);
	 if ($lb_encontrado)
	    {
	      $lb_existe     = $io_estpro2->uf_spg_select_estprog2($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_estcla); 
	      if (!$lb_existe)
	         {
			   
			   
			   $lb_valido = $io_estpro2->uf_spg_insert_estprog2($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_denestpro2,$li_estmodest,$ls_estcla,$la_seguridad);		
		       if ($lb_valido)
		          {
			        $io_sql->commit();
			        $io_msg->message("Registro Incluido !!!");
			      /*  if ($li_estmodest=='2')
			           {
					     $ls_codestpro1 = substr($ls_codestpro1,18,2);
				       }*/
			        $ls_codestpro2 = "";
			        $ls_denestpro2 = "";
			      }
		       else
		          { 
			        $io_sql->rollback();
			        $io_msg->message($io_estpro2->is_msg_error);
			      }
		     }
	      else
	         {
		       $lb_valido = $io_estpro2->uf_spg_update_estprog2($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_denestpro2,$ls_estcla,$la_seguridad);		
		       if ($lb_valido)
		          {
			        $io_sql->commit();
			        $io_msg->message("Registro Actualizado !!!");
			       /* if ($li_estmodest=='2')
			           {
			             $ls_codestpro1 = substr($ls_codestpro1,18,2);
				       }*/
			        $ls_codestpro2 = "";
			        $ls_denestpro2 = "";
			      }
		       else
		          {
			        $io_sql->rollback();
			        $io_msg->message($io_estpro2->is_msg_error);
			      }
		     }
        }
	 else
	    {
		  $io_msg->message("Debe registrar la Estructura de Nivel 1 previamente !!!");
		}
	 $ls_status = "NUEVO";
     $readonly  = ""; 
   }	 
	
if ($ls_operacion == "ELIMINAR")
   {    $ls_clasificacion=$_POST["txtclasificacion"];
   	    $ls_codestp1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	    $ls_codestp2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
	 $lb_valido     = $io_estpro2->uf_spg_delete_estpro2($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_denestpro2,$ls_clasificacion,$la_seguridad);
	 if ($lb_valido)
	    {
		  $io_sql->commit();
		  $io_msg->message("Registro Eliminado !!!");
		}
	 else
	    {
		  $io_sql->rollback();
		  $io_msg->message($io_estpro2->is_msg_error);
		}
     /*if ($li_estmodest=='2')
	    {
	      $ls_codestpro1 = substr($ls_codestpro1,18,2);
	    }*/
	 $ls_codestpro2 = "";
	 $ls_denestpro2 = "";
	 $ls_clasificacion="";
	 $readonly      = "";
   }
	
if ($ls_operacion == "BUSCAR")
   {
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
	 $ls_clasificacion=$_POST["txtclasificacion"];
	 $readonly      = "readonly";
   }
	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="705" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="673" height="221" valign="top">
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
   {
	 print("<input type=hidden name=permisos2 id=permisos2 value='$ls_permisos'>");
	 print("<input type=hidden name=leer      id=leer      value='$la_accesos[leer]'>");
	 print("<input type=hidden name=incluir   id=incluir   value='$la_accesos[incluir]'>");
	 print("<input type=hidden name=cambiar   id=cambiar   value='$la_accesos[cambiar]'>");
	 print("<input type=hidden name=eliminar  id=eliminar  value='$la_accesos[eliminar]'>");
	 print("<input type=hidden name=imprimir  id=imprimir  value='$la_accesos[imprimir]'>");
	 print("<input type=hidden name=anular    id=anular    value='$la_accesos[anular]'>");
	 print("<input type=hidden name=ejecutar  id=ejecutar  value='$la_accesos[ejecutar]'>");
   }
else
   {
	 print("<script language=JavaScript>");
	 print(" location.href='sigespwindow_blank.php'");
	 print("</script>");
   }
?>
          <p>&nbsp;</p>
          <table width="649" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3"><input name="hidmaestro" type="hidden" id="hidmaestro" value="Y">
                <?php print $ls_nomestpro2?></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td width="546" height="22" colspan="2"><input name="statusprog2" type="hidden" id="statusprog2" value="<?php print $ls_status ?>"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" align="right"><?php print $dat["nomestpro1"]?></td>
                <td height="22" colspan="2" align="left">
                  <input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print $ls_loncodestpro1+10 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" value="<?php print  $ls_codestpro1?>" readonly="" style="text-align:center">
                <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1?>" size="80" maxlength="80" readonly=""></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="22"><div align="right" >
                    <p>C&oacute;digo</p>
                </div></td>
                <td height="22" colspan="2"><div align="left" >
                    <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center " value="<?php print $ls_codestpro2?>" size="<?php print $ls_loncodestpro2+10 ?>" maxlength="<?php print $ls_loncodestpro2 ?>" onBlur="javascript:rellenar_cad(this.value,<?php print $ls_loncodestpro2 ?>,'cod')" <?php print $readonly?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');">
                    
                   <input name="txtclasificacion" type="hidden" id="txtclasificacion" value="<?php print $ls_clasificacion?>" >
                  
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td height="22" colspan="2"><div align="left">
                  <input name="txtdenestpro2" type="text" id="txtdenestpro2" style="text-align:left" value="<?php print $ls_denestpro2?>" size="93" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@/'+'´áéíóú');">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="3">&nbsp;                 
                  <table width="543" height="21" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td width="271"><div align="center"><a href="javascript: uf_estprog1();"><?php print "Ir a ".$dat["nomestpro1"]?></a></div></td>
                    <td width="270"><div align="center"><a href="javascript: uf_estprog3();"><?php print "Ir a ".$dat["nomestpro3"]?></a></div></td>
                  </tr>
                </table>                            </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td height="22" colspan="2" align="left">&nbsp;</td>
              </tr>
          </table>
            <p align="center">
            <input name="operacionestprog2" type="hidden" id="operacionestprog2">
</p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
f=document.formulario;
function ue_nuevo()
{
   f.operacionestprog2.value ="NUEVO";
   f.action="sigesp_spg_d_estprog2.php";
   f.submit();
}

function ue_guardar()
{
	li_incluir    = f.incluir.value;
    li_cambiar    = f.cambiar.value;
    lb_status     = f.statusprog2.value;
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_denestpro2 = f.txtdenestpro2.value;
    if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
       {
	     if ((ls_codestpro1!="") && (ls_codestpro2!="") && (ls_denestpro2!=""))
	        {
 	          f.operacionestprog2.value = "GUARDAR";   
	          f.action                  = "sigesp_spg_d_estprog2.php";
 	          f.submit();
	        }	
	     else
		    {
			  alert("Debe completar todos los campos !!!");
		    }
       }
    else
       {
         alert("No tiene permiso para realizar esta operación !!!");
       }  
}

function ue_eliminar()
{
li_eliminar = f.eliminar.value;
if (li_eliminar==1)
   {	
     borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	 if (borrar==true)
	    { 
          f.operacionestprog2.value ="ELIMINAR";
          f.action="sigesp_spg_d_estprog2.php";
          f.submit();
  	    }
     else
	    {
		  alert("Eliminación Cancelada !!!");
		}
   }
  else
   {
     alert("No tiene permiso para realizar esta operación !!!");
   }
}

function ue_buscar()
{
	ls_codestpro1   = f.txtcodestpro1.value;
	ls_denestpro1   = f.txtdenestpro1.value;
	ls_estcla       = f.txtclasificacion.value;
	window.open("sigesp_spg_cat_estpro2.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtclasificacion="+ls_estcla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f.action="sigespwindow_blank.php";
	f.submit();
}
function uf_estprog1()
{
	f.action="sigesp_spg_d_estprog1.php";
	f.submit();
}
function uf_estprog3()
{
	ls_codigo        = f.txtcodestpro1.value;
	ls_denominacion  = f.txtdenestpro1.value;
	ls_codigo2       = f.txtcodestpro2.value;
	ls_denominacion2 = f.txtdenestpro2.value;
	if((ls_codigo!="")&&(ls_denominacion!="")&&(ls_codigo2!="")&&(ls_denominacion2!=""))
	{
		f.action="sigesp_spg_d_estprog3.php";
		f.submit();
	}
	else
	{
		alert("Debe seleccionar algun valor para continuar");
	}

}
//Funcion de relleno con ceros a un textfield
function rellenar_cad(cadena,longitud,campo)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
	total        = longitud-lencad;
	if (cadena!="")
	   {
		 for (i=1;i<=total;i++)
			 {
			   cadena_ceros=cadena_ceros+"0";
			 }
		 cadena=cadena_ceros+cadena;
		 if (campo=="cod")
			{
				document.formulario.txtcodestpro2.value=cadena;
			}
	   } 
}
</script>
</html>