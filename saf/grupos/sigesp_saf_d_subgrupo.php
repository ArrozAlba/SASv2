<?
session_start();
$dat=$_SESSION["la_empresa"];
$ls_nomestpro2=$dat["NomEstPro2"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <? print $ls_nomestpro2?>  </title>
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
<link href="css/general.css" rel="stylesheet" type="text/css">
<link href="css/ventanas.css" rel="stylesheet" type="text/css">
<link href="css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="imagebank/header.jpg" width="778" height="40"></td>
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
	//require_once("sigesp_spg_c_estprog.php");
	include("class_folder\class_mensajes.php");
	include("sigesp_saf_c_grupo.php");

	require_once("class_folder\class_funciones.php");
	require_once("class_folder\class_funciones_db.php");
	require_once("sigesp_include.php");

	$io_saf=  new sigesp_saf_c_grupo();
	$in=      new sigesp_include();
	$con= $in->uf_conectar();
	$io_msg=  new class_mensajes();
	$io_fun=  new class_funciones_db($con);
	$io_func= new class_funciones();

	$dat=$_SESSION["la_empresa"];
	$ls_nomestpro1=$dat["NomEstPro1"];
	//$class_estprog2=new sigesp_spg_c_estprog();
	$ds=null;

	if( array_key_exists("operacion2",$_POST))
	{
		$ls_operacion=$_POST["operacion2"];
		$ls_codgru=$_POST["txtcodgru"];
		$ls_codsubgru=$_POST["txtcodsubgru"];
		$ls_dengru=$_POST["txtdengru"];
		$ls_densubgru=$_POST["txtdensubgru"];
		$readonly="";
	}
	else
	{
		$ls_operacion="";
		$ls_codgru=$_POST["txtcodigo"];
		$ls_codsubgru="";
		$ls_dengru=$_POST["txtdenominacion"];
		$ls_densubgru="";
		$readonly="";
	}
	if($ls_operacion == "NUEVO")
	{
		$ls_codgru=$_POST["txtcodgru"];
		$ls_codsubgru="";
		$ls_dengru=$_POST["txtdengru"];
		$ls_densubgru="";
		$readonly="";

		$ls_emp="";
		$ls_codemp="";
		$ls_tabla="saf_subgrupo";
		$ls_columna="CodSubGru";
		$ls_longitud= 3;
	
		$ls_codsubgru=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
		$ls_codsubgru=$io_func->uf_cerosizquierda($ls_codsubgru,$ls_longitud);

	}
	if($ls_operacion == "GUARDAR")
	{
		$ls_codgru=$_POST["txtcodgru"];
		$ls_dengru=$_POST["txtdengru"];
		$ls_codsubgru=$_POST["txtcodsubgru"];
		$ls_densubgru=$_POST["txtdensubgru"];
		$readonly="readonly";
		if(($ls_codgru!="")&&($ls_codsubgru!="")&&($ls_densubgru!=""))
		{
				$io_saf->SQL->begin_transaction();
				$lb_encontrado=$io_saf->uf_saf_select_subgrupo($ls_codgru,$ls_codsubgru);//Realiza una busqueda para ver si el evento ya existe
	
				if ($lb_encontrado)
				{
				$io_saf->SQL->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_subgrupo($ls_codgru,$ls_codsubgru,$ls_densubgru);
	
				if($lb_valido)
				{
					$io_msg->message("El registro fue actualizado con exito");
					$ls_codigo="";
					$ls_denominacion="";
					$ls_status="";
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
					$io_saf->SQL->begin_transaction();
					$lb_valido=$io_saf->uf_saf_insert_subgrupo($ls_codgru,$ls_codsubgru,$ls_densubgru);//Realiza el Insert del evento0
	
					if ($lb_valido)
					{
						$io_msg->message("El sistema fue grabado.");
						$ls_codigo="";
						$ls_denominacion="";
						$ls_status="";
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						//$ls_evento="INCLUIR";
						//$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
						////////////////////////////////         SEGURIDAD               //////////////////////////////
					}
					else
					{
					$io_msg->message("No se pudo incluir el registro");
					}
				
				}
		}
		else
		{
			$io_msg->message("Debe completar todos los campos");
		}
		
	}
	if($ls_operacion == "ELIMINAR")
	{
		$ls_codgru=$_POST["txtcodgru"];
		$ls_dengru=$_POST["txtdengru"];
		$ls_codsubgru=$_POST["txtcodsubgru"];
		$ls_densubgru=$_POST["txtdensubgru"];
			
		$lb_valido=$io_saf->uf_saf_delete_subgrupo($ls_codgru,$ls_codsubgru);
		if($lb_valido)
		{
			$io_msg->message("El registro fue eliminado");
			$ls_codsubgru="";
			$ls_densubgru="";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			//$ls_evento="ELIMINAR";
			//$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
		}
		else
		{
			$io_msg->message("No se pudo eliminar el registro");
		}
			
		$ls_codsubgru="";
		$ls_densubgru="";
		$readonly="";
	}
	if($ls_operacion == "BUSCAR")
	{
		$ls_codgru=$_POST["txtcodgru"];
		$ls_dengru=$_POST["txtdengru"];
		$ls_codsubgru=$_POST["txtcodsubgru"];
		$ls_densubgru=$_POST["txtdensubgru"];
		$readonly="readonly";
	}
	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top"><form name="form1" method="post" action="">
          <p>&nbsp;</p>
          <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td colspan="3">Definici&oacute;n de Sub Grupos </td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td width="463" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="27" align="right"><? print $dat["NomEstPro1"]?></td>
                <td colspan="2" align="left">
                  <input name="txtcodgru" type="text" id="txtcodgru"  style="text-align:center" size="4" maxlength="3" value="<? print  $ls_codgru?>" readonly="">
                  <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" value="<? print $ls_dengru?>" size="60" maxlength="80" readonly=""></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="27"><div align="right" >
                    <p>Codigo</p>
                </div></td>
                <td colspan="2"><div align="left" >
                    <input name="txtcodsubgru" type="text" id="txtcodsubgru" style="text-align:center " value="<? print $ls_codsubgru?>" size="4" maxlength="6" <? print $readonly?>>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="31"><div align="right">Denominaci&oacute;n</div></td>
                <td colspan="2"><div align="left">
                  <input name="txtdensubgru" type="text" id="txtdensubgru" style="text-align:left" value="<? print $ls_densubgru?>" size="82" maxlength="100">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left"><input name="buttonvolver" type="button" class="boton" id="buttonvolver" onClick="javascript: uf_volvergrupos();" value="Volver a Grupos" >
                  <input name="botestpro3" type="button" class="boton" id="botestpro3" onClick="javascript: uf_ir();" value="Ir a Secciones"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left">&nbsp;</td>
              </tr>
          </table>
            <p align="center">
            <input name="operacion2" type="hidden" id="operacion2">
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
f.operacion2.value ="NUEVO";
f.action="sigesp_saf_d_subgrupo.php";
f.submit();
}
function ue_guardar()
{
f=document.form1;
f.operacion2.value ="GUARDAR";
f.action="sigesp_saf_d_subgrupo.php";
f.submit();
}

function ue_eliminar()
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.operacion2.value ="ELIMINAR";
f.action="sigesp_saf_d_subgrupo.php";
f.submit();
//f.txtcuenta.focus(true);
}
function ue_buscar()
{
	codigo=document.form1.txtcodgru.value;
	deno=document.form1.txtdengru.value;
	window.open("sigesp_catdinamic_subgrupo.php?codigo="+codigo+"&deno="+deno,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
function uf_volvergrupos()
{
	f=document.form1;
	f.action="sigesp_saf_d_grupo.php";
	f.submit();
}
function uf_ir()
{
	f=document.form1;
	f.action="sigesp_saf_d_secciones.php";
	f.submit();
}
//Funcion de relleno con ceros a un textfield
	function rellenar_cad(cadena,longitud,campo)
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;
	
		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		
		if(campo=="cod")
		{
			document.form1.txtcodsubgru.value=cadena;
		}
	}

</script>
</html>
