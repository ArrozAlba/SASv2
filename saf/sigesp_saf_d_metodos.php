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
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #6699CC;
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/deshacer.gif" alt="Deshacer" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/filtrar.gif" alt="Filtrar" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_saf_c_metodos.php");
	require_once("../shared/class_folder/sigesp_include.php");
	$io_sss= new sigesp_saf_c_metodos();

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	include("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SSS";
	$ls_ventanas="sigespwindow_sss_grupos.php";


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
	}

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
		//$ls_empresa="";
		$ls_nombre="";
		$ls_nota="";
	}
	if ($ls_operacion=="GUARDAR")
	{
		$io_msg= new class_mensajes();
		$ls_valido= false;
		//$ls_empresa=$_POST["txtempresa"];
		$ls_nombre=$_POST["txtnombre"];
		$ls_nota=$_POST["txtnota"];
		$ls_status=$_POST["hidstatus"];
		if( ($ls_empresa=="")||($ls_nombre=="")||($ls_nota==""))
			{
				$io_msg->message("Debe compeltar todos los campos");
			}
		else
			{
				if ($ls_status=="C")
				{
					$ls_nombrevie=$_POST["txtnombrevie"];

					if($ls_nombre==$ls_nombrevie)
					{
						$io_sss->SQL->begin_transaction();
						$lb_valido=$io_sss->uf_sss_update_grupo($ls_empresa,$ls_nombre,$ls_nota);
	
						if($lb_valido)
						{
							$io_msg->message("El registro fue actualizado con exito");
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="MODIFICAR";
							$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
							////////////////////////////////         SEGURIDAD               //////////////////////////////
						
						}	
						else
						{
							$io_msg->message("El registro no pudo ser actualizado");
						}
	
					}
					else
					{
						$io_msg->message("No se puede cambiar el campo Nombre");
					}
				}
				else
				{
					$io_sss->SQL->begin_transaction();
					$lb_encontrado=$io_sss->uf_sss_select_grupos($ls_nombre);//Realiza una busqueda para ver si el evento ya existe
					if ($lb_encontrado)
					{
						$io_msg->message("Registro ya existe"); //Verificar mensajes
					}
					else
					{
						$io_sss->SQL->begin_transaction();
						$lb_valido=$io_sss->uf_sss_insert_grupo($ls_empresa,$ls_nombre,$ls_nota);//Realiza el Insert del evento0

						if ($lb_valido)
						{
								$io_msg->message("El sistema fue grabado.");

								/////////////////////////////////         SEGURIDAD               /////////////////////////////
								$ls_evento="INCLUIR";
								$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
								////////////////////////////////         SEGURIDAD               //////////////////////////////
						}
						else
						{
						$io_msg->message("No se pudo incluir el registro");
						}
					
					}
				}
				//Liberar y cerrar la conexion.
				//$int_sss->SQL->
				
			}
		$ls_nombre="";
		$ls_nota="";
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$arr=$_SESSION["la_empresa"];
		$ls_codigo=$arr["CodEmp"];
		//$ls_codigo=$_POST["txtcodigo"];
		$ls_nombre=$_POST["txtnombre"];
		$io_msg=new class_mensajes();
		
		$io_sss->SQL->begin_transaction();
		$lb_valido=$io_sss->uf_sss_delete_grupo($ls_codigo,$ls_nombre);

		if($lb_valido)
		{
			$io_msg->message("El registro fue eliminado");
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="ELIMINAR";
			$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
		}	
		else
		{
			$io_msg->message("No se pudo eliminar el registro");
		
		}
		
		//if($int_sss->ib_db_error) ********REVISAR***********
		//{
		//   $msg->message($int_sss->is_msg_error);	
		//   $int_sss->ib_db_error = false;		
		//}
		$ls_nombre="";
		$ls_nota="";
		
	}
	elseif($ls_operacion=="NUEVO")
	{
		$ls_nombre="";
		$ls_nota="";
	}
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" class="formato-blanco">
    <tr>
      <td height="223"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
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
            <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="2" class="titulo-celdanew">Definici&oacute;n de M&eacute;todos</td>
              </tr>
              <tr class="formato-blanco">
                <td width="111" height="26">&nbsp;</td>
                <td width="408"><input name="txtempresa" type="hidden" id="txtempresa" value="<?print $ls_empresa?>">
                <input name="txtnombrevie" type="hidden" id="txtnombrevie"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="29"><div align="right">C&oacute;digo</div></td>
                <td><input name="txtnombre" type="text" id="txtnombre" value="<?print $ls_nombre?>" size="5">
                <input name="hidstatus" type="hidden" id="hidstatus"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right">Denominaci&oacute;n</div></td>
                <td><input name="txtnota" type="text" id="txtnota" value="<?print $ls_nota?>" size="50">                </td>
              </tr>
              <tr class="formato-blanco">
                <td height="28"><div align="right">Formula</div></td>
                <td><input name="textfield" type="text" size="50"></td>
              </tr>
            </table>
            <p align="center">
              <input name="operacion" type="hidden" id="operacion">
            </p>
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones sobre el comprobante
function ue_cata()
{
	window.open("sigesp_catdinamic_empresas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_grupos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigespwindow_sss_grupos.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	f.operacion.value="GUARDAR";
	f.action="sigespwindow_sss_grupos.php";
	f.submit();
}
function ue_eliminar()
{
	if(confirm("¿Seguro desea eliminar el Usuario?"))
	{
		f=document.form1;
		f.operacion.value="ELIMINAR";
		f.action="sigespwindow_sss_grupos.php";
		f.submit();
	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


</script> 
</html>