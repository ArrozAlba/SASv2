<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Grupos</title>
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
<link href="css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/cabecera.css" rel="stylesheet" type="text/css">
<link href="css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {
	color: #6699CC;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" title="Nuevo" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" title="Buscar"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20" title="Ejecutar"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/deshacer.gif" alt="Deshacer" width="20" height="20" title="Deshacer"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/filtrar.gif" alt="Filtrar" width="20" height="20" title="Filtrar"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	require_once("sigesp_saf_c_condicion.php");
	require_once("../shared/class_folder/sigesp_include.php");
	
	$io_saf= new sigesp_saf_c_condicion();
	$in=     new sigesp_include();
	$con= 	 $in->uf_conectar();
	$io_fun= new class_funciones_db($con);

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
/*	include("class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["CodEmp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SAF";
	$ls_ventanas="sigesp_saf_d_rotulacion.php";


	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			print("Bienvenido usuario SIGESP");
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}*/

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$li_count=count($arr);

	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_denominacion="";
		$ls_descripcion="";
	}
	if ($ls_operacion=="GUARDAR")
	{
		$io_msg= new class_mensajes();
		$ls_valido= false;
		//$ls_empresa=$_POST["txtempresa"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$ls_descripcion=$_POST["txtdescripcion"];
		$ls_status=$_POST["hidstatus"];
		if( ($ls_codigo=="")||($ls_denominacion==""))
			{
				$io_msg->message("Debe compeltar los campos código y denominación");
			}
		else
			{
				if ($ls_status=="C")
				{
					$io_saf->SQL->begin_transaction();
					$lb_valido=$io_saf->uf_saf_update_condicion($ls_codigo,$ls_denominacion,$ls_descripcion);
	
					if($lb_valido)
					{
						$io_msg->message("El registro fue actualizado con exito");
						$ls_limpiar=uf_limpiar();
						$ls_codigo="";
						$ls_denominacion="";
						$ls_descripcion="";
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						//$ls_evento="MODIFICAR";
						//$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
						////////////////////////////////         SEGURIDAD               //////////////////////////////
						
					}	
					else
					{
						$io_msg->message("El registro no pudo ser actualizado");
					}
				}
				else
				{
/*					$io_sss->SQL->begin_transaction();
					$lb_encontrado=$io_sss->uf_sss_select_grupos($ls_nombre);//Realiza una busqueda para ver si el evento ya existe
					if ($lb_encontrado)
					{
						$io_msg->message("Registro ya existe"); //Verificar mensajes
					}
					else
					{*/
						$io_saf->SQL->begin_transaction();
						$lb_valido=$io_saf->uf_saf_insert_condicion($ls_codigo,$ls_denominacion,$ls_descripcion);//Realiza el Insert del evento0

						if ($lb_valido)
						{
							$io_msg->message("El sistema fue grabado.");
							$ls_limpiar=uf_limpiar();
							$ls_codigo="";
							$ls_denominacion="";
							$ls_descripcion="";
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							//$ls_evento="INCLUIR";
							//$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
							////////////////////////////////         SEGURIDAD               //////////////////////////////
						}
						else
						{
						$io_msg->message("No se pudo incluir el registro");
						}
					
					//}
				}
				//Liberar y cerrar la conexion.
				//$int_sss->SQL->
				
			}
		$ls_nombre="";
		$ls_nota="";
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$ls_codigo=$_POST["txtcodigo"];
		$io_msg=new class_mensajes();
		
		$io_saf->SQL->begin_transaction();
		$lb_valido=$io_saf->uf_saf_delete_condicion($ls_codigo);

		if($lb_valido)
		{
			$io_msg->message("El registro fue eliminado");
			$ls_codigo="";
			$ls_denominacion="";
			$ls_descripcion="";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			//$ls_evento="ELIMINAR";
			//$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
			$ls_limpiar=uf_limpiar();
		}	
		else
		{
			$io_msg->message("No se pudo eliminar el registro");
		
		}
		
	}
	elseif($ls_operacion=="NUEVO")
	{
		$ls_denominacion="";
		$ls_descripcion="";
		$ls_emp="";
		$ls_codemp="";
		$ls_tabla="saf_conservacionbien";
		$ls_columna="CodConBie";
	
		$ls_codigo=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);

	}
	
function uf_limpiar()
{
	$ls_denominacion="";
	$ls_empleo="";
	$ls_codigo="";
	return $ls_denominacion;
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
/*if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}*/
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="588" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="2" class="titulo-celdanew">Condici&oacute;n del Activo </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408"><input name="txtempresa" type="hidden" id="txtempresa2" value="<?print $ls_empresa?>">
                        <input name="txtnombrevie" type="hidden" id="txtnombrevie2"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="29"><div align="right">C&oacute;digo</div></td>
                    <td><input name="txtcodigo" type="text" id="txtcodigo" value="<? print $ls_codigo?>" size="2" readonly="true">
                        <input name="hidstatus" type="hidden" id="hidstatus"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="32"><div align="right">Denominaci&oacute;n</div></td>
                    <td><input name="txtdenominacion" type="text" id="txtdenominacion" value="<? print $ls_denominacion?>" size="50" onKeyPress="javascript: ue_validarcomillas();">
                    </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="18"><div align="right">Descripci&oacute;n</div></td>
                    <td rowspan="2"><textarea name="txtdescripcion" cols="50" rows="3" id="txtdescripcion" onKeyPress="javascript: ue_validarcomillas();"><? print $ls_descripcion?></textarea></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="19"><div align="right"></div></td>
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
//Funciones de operaciones sobre el comprobante
function ue_buscar()
{
	window.open("sigesp_catdinamic_condicion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_saf_d_condicion.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	f.operacion.value="GUARDAR";
	f.action="sigesp_saf_d_condicion.php";
	f.submit();
}
function ue_eliminar()
{
	if(confirm("¿Seguro desea eliminar el Usuario?"))
	{
		f=document.form1;
		f.operacion.value="ELIMINAR";
		f.action="sigesp_saf_d_condicion.php";
		f.submit();
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas()
{
	if (event.keyCode==39)
	{
		event.returnValue = false;
	}
}</script> 
</html>