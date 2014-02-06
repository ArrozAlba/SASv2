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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_movimientos.php",$ls_permisos,$la_seguridad,$la_permisos);
require_once("sigesp_saf_c_activo.php");
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
$io_saf_tipcat= new sigesp_saf_c_activo();
$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_denominacion,$ls_explicacion,$ls_tipoI,$ls_tipoD,$ls_tipoR,$ls_tipoM,$ls_contablesi,$ls_presupuestariasi;
		
		$ls_codigo="";
		$ls_denominacion="";
		$ls_explicacion="";
		$ls_tipoI="";
		$ls_tipoD="";
		$ls_tipoR="";
		$ls_tipoM="";
		$ls_contablesi="";
		$ls_presupuestariasi="";
   }

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
<title >Definici&oacute;n de Causas de Movimientos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
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
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
   <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>	
    <!-- <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="580">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones();
	require_once("sigesp_saf_c_movimientos.php");
	$io_saf=  new sigesp_saf_c_movimientos();
	require_once("../shared/class_folder/sigesp_include.php");
	$in=      new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun=  new class_funciones_db($con);
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
	}
	if ($ls_operacion=="GUARDAR")
	{
		$ls_valido= false;
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_explicacion=$_POST["txtexplicacion"];
		$ls_status=$_POST["hidstatus"];
		if(array_key_exists("hidradio",$_POST))
		{
			$ls_radio=$_POST["hidradio"];
			switch ($ls_radio) 
			{
				case 0:
					$ls_radiotipo="I";
					break;
				case 1:
					$ls_radiotipo="D";
					break;
				case 2:
					$ls_radiotipo="R";
					break;
				case 3:
					$ls_radiotipo="M";
					break;
			}
		}
		if(array_key_exists("chkcontable",$_POST))
		{$li_contable=$HTTP_POST_VARS["chkcontable"];}else{$li_contable=0;}
		if(array_key_exists("chkpresupuestaria",$_POST))
		{$li_presupuestaria=$HTTP_POST_VARS["chkpresupuestaria"];}else{$li_presupuestaria=0;}
		if( ($ls_codigo=="")||($ls_denominacion==""))
		{
			$io_msg->message("Debe compeltar los campos código y denominación");
		}
		else
		{
			if ($ls_status=="C")
			{
				$lb_valido=$io_saf->uf_saf_update_movimientos($ls_codigo,$ls_denominacion,$ls_radiotipo,$li_contable,
																$li_presupuestaria,$ls_explicacion,$la_seguridad);

				if($lb_valido)
				{
					$io_msg->message("El registro fue actualizado con exito");
					uf_limpiarvariables();
				}	
				else
				{
					$io_msg->message("El registro no pudo ser actualizado");
					uf_limpiarvariables();
				}
			}
			else
			{
				$lb_encontrado=$io_saf->uf_saf_select_movimientos($ls_codigo);
				if ($lb_encontrado)
				{
					$io_msg->message("Registro ya existe"); //Verificar mensajes
					uf_limpiarvariables();
				}
				else
				{
					$lb_valido=$io_saf->uf_saf_insert_movimientos($ls_codigo,$ls_denominacion,$ls_radiotipo,$li_contable,
																	$li_presupuestaria,$ls_explicacion,$la_seguridad);

					if ($lb_valido)
					{
						$io_msg->message("El registro fue grabado.");
						uf_limpiarvariables();
					}
					else
					{
						$io_msg->message("No se pudo incluir el registro");
						uf_limpiarvariables();
					}
				
				}
			}
		}
		$ls_nombre="";
		$ls_nota="";
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$ls_codigo=$_POST["txtcodigo"];
		$lb_valido=$io_saf->uf_saf_delete_movimientos($ls_codigo,$la_seguridad);
		if($lb_valido)
		{
			$io_msg->message("El registro fue eliminado");
			uf_limpiarvariables();
		}	
		else
		{
			$io_msg->message("No se pudo eliminar el registro");
		}
	}
	elseif($ls_operacion=="NUEVO")
	{
		uf_limpiarvariables();
		$ls_emp="";
		$ls_codemp="";
		$ls_tabla="saf_causas";
		$ls_columna="codcau";
		$ls_codigo=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
	}
		
?>

<p>&nbsp;</p>
<div align="center">
  <table width="596" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="588" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="588" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="3" class="titulo-ventana">Definici&oacute;n de Causas de Movimiento </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408" colspan="2"><input name="txtempresa" type="hidden" id="txtempresa2" value="<?php print $ls_empresa?>">
                        </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="22"><div align="right">C&oacute;digo</div></td>
                    <td colspan="2"><input name="txtcodigo" type="text" id="txtnombre" value="<?php print $ls_codigo?>" size="8" maxlength="3" style="text-align:center" readonly>
                        <input name="hidstatus" type="hidden" id="hidstatus"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="41"><div align="right">Denominaci&oacute;n</div></td>
                    <td colspan="2" rowspan="2"><textarea name="txtdenominacion" cols="70" rows="3" id="txtnota2" onKeyUp="javascript: ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);"><?php print $ls_denominacion?></textarea>
                    </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="13">&nbsp;</td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="39"><div align="right">Explicaci&oacute;n</div></td>
                    <td colspan="2" rowspan="2"><textarea name="txtexplicacion" cols="70" rows="3" id="txtexplicacion" onKeyUp="javascript: ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);"><?php print $ls_explicacion?></textarea></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="13">&nbsp;</td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="44" colspan="3">
                      <div align="center"></div>                      <table width="441" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                          <tr>
                            <td height="22" colspan="2"><div align="center">Tipo
                                <input name="radiotipo" type="radio" class="sin-borde" value="radiobutton" onClick="actualizaValor(this)">
                                Incorporaci&oacute;n
                                <input name="radiotipo" type="radio" class="sin-borde" value="radiobutton"  onClick="actualizaValor(this)">
                                Desincorporaci&oacute;n
                                <input name="radiotipo" type="radio" class="sin-borde" value="radiobutton" onClick="actualizaValor(this)">
                                Reasignaci&oacute;n
                                <input name="radiotipo" type="radio" class="sin-borde" value="radiobutton" onClick="actualizaValor(this)">
Modificacion
<input name="hidradio" type="hidden" id="hidradio">
                            </div></td>
                          </tr>
                          <tr>
                            <td width="38" height="22"><div align="center">
      </div></td>
                            <td width="401"><input name="chkcontable" type="checkbox" class="sin-borde" id="chkcontable" value="1" >
Estatus de afectaci&oacute;n contable
<input name="chkpresupuestaria" type="checkbox" class="sin-borde" id="chkpresupuestaria" value="1">
Estatus de afectaci&oacute;n presupuestaria</td>
                          </tr>
                                            </table></td>
                    </tr>
                  <tr class="formato-blanco">
                    <td height="28">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones sobre la causa de movimiento

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_saf_cat_causas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_d_movimientos.php";
		f.txtcodigo.readonly=false;
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
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status=="")&&(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_d_movimientos.php";
		f.submit();
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
		if(confirm("¿Seguro desea eliminar el Registro?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_saf_d_movimientos.php";
			f.submit();
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que obtiene el valor de el radio button
//--------------------------------------------------------
   function actualizaValor(oRad)
   { 
    var i 
	f=document.form1;
    for (i=0;i<f.radiotipo.length;i++)
	{ 
       if (f.radiotipo[i].checked) 
          break; 
    } 
    valor= i;
	f.hidradio.value=i;
   } 

</script> 
</html>