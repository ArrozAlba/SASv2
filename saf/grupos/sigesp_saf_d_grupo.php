<?
session_start();
	$dat=$_SESSION["la_empresa"];
	$ls_nomestpro1=$dat["NomEstPro1"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <? print $ls_nomestpro1?> </title>
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?
	include("sigesp_saf_c_grupo.php");
	include("class_folder\class_mensajes.php");
	require_once("class_folder\class_funciones.php");
	require_once("class_folder\class_funciones_db.php");
	require_once("sigesp_include.php");

	$io_saf=  new sigesp_saf_c_grupo();
	$in=      new sigesp_include();
	$con= $in->uf_conectar();
	$io_msg=  new class_mensajes();
	$io_fun=  new class_funciones_db($con);
	$io_func= new class_funciones();

	$ds=null;
	$ls_status="";

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$disabled="";
		$readonly="";
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_denominacion="";
		$ls_clasificacion="P";
		$disabled="disabled";
		$readonly="";
	}
	if($ls_operacion == "NUEVO")
	{
		$ls_codigo="";
		$ls_denominacion="";
		$ls_clasificacion="P";
		$disabled="disabled";
		$readonly="";

		$ls_emp="";
		$ls_codemp="";
		$ls_tabla="saf_grupo";
		$ls_columna="CodGru";
		$ls_longitud= 3;
	
		$ls_codigo=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
		$ls_codigo=$io_func->uf_cerosizquierda($ls_codigo,$ls_longitud);

	}
		if(array_key_exists("hidstatus",$_POST))
		{
			$ls_status=$_POST["hidstatus"];
		}
		else
		{
			$ls_status="";
		}
	if($ls_operacion == "GUARDAR")
	{
		$ls_codigo=trim($_POST["txtcodigo"]);
		$ls_denominacion=$_POST["txtdenominacion"];
		$disabled="";
		$readonly="readonly";
		if(($ls_codigo=="")||($ls_denominacion==""))
		{
			$io_msg->message("Debe completar los campos");
		}
		else
		{
				$io_saf->SQL->begin_transaction();
				$lb_encontrado=$io_saf->uf_saf_select_grupo($ls_codigo);//Realiza una busqueda para ver si el evento ya existe
	
				if ($lb_encontrado)
				{
				$io_saf->SQL->begin_transaction();
				$lb_valido=$io_saf->uf_saf_update_grupo($ls_codigo,$ls_denominacion);
	
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
					$lb_valido=$io_saf->uf_saf_insert_grupo($ls_codigo,$ls_denominacion);//Realiza el Insert del evento0
	
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
	}
	if($ls_operacion == "ELIMINAR")
	{
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$disabled="disabled";
		$readonly="readonly";
		
		$lb_valido=$io_saf->uf_saf_delete_grupo($ls_codigo);
		if($lb_valido)
		{
			$io_msg->message("El registro fue eliminado");
			$ls_codigo="";
			$ls_denominacion="";
			$ls_status="";
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			//$ls_evento="ELIMINAR";
			//$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas);
			////////////////////////////////         SEGURIDAD               //////////////////////////////
		}
		else
		{
			$io_msg->message("No se pudo eliminar el registro");
		}
	
		$ls_codigo="";
		$ls_denominacion="";
		$ls_status="";
		$disabled="disabled";
		$readonly="";
	}
	if($ls_operacion == "BUSCAR")
	{
		$ls_codigo=$_POST["txtcodigo"];
		$ls_denominacion=$_POST["txtdenominacion"];
		$disabled="";
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
                <td colspan="3">Definici&oacute;n de Grupo</td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td width="463" colspan="2"><input name="hidstatus" type="hidden" id="hidstatus"></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="23"><div align="right" >
                    <p>Codigo</p>
                </div></td>
                <td colspan="2"><div align="left" >
                    <input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<? print $ls_codigo?>" size="4" maxlength="3"<? print $readonly ?> >
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="31"><div align="right">Denominaci&oacute;n</div></td>
                <td colspan="2" rowspan="2"><div align="left">
                  <textarea name="txtdenominacion" cols="70" rows="2" id="txtdenominacion" style="text-align:left"><? print $ls_denominacion?></textarea>
                </div></td>
              </tr>
			  
              <tr class="formato-blanco">
                <td height="13">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="25"><div align="right"></div></td>
                <td height="25" colspan="2" align="left"><p>
				
                  <label>                  </label>
                  <br>
                </p></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left">
				
                  <input name="buttonir" type="button" class="boton" id="buttonir" onClick="javascript: uf_ir();" value="Ir a Sub Grupos" <? print $disabled?>>
                </td>
              </tr>
            <tr class="formato-blanco">
                <td height="20"><div align="right" ></div></td>
                <td colspan="2"><div align="left" >
                </div></td>
            </tr>
          </table>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
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
f.operacion.value ="NUEVO";
f.action="sigesp_saf_d_grupo.php";
f.submit();
}
function ue_guardar()
{
f=document.form1;
f.operacion.value ="GUARDAR";
f.action="sigesp_saf_d_grupo.php";
f.submit();
f.txtcodigo.focus(true);
}

function ue_eliminar()
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.operacion.value ="ELIMINAR";
f.action="sigesp_saf_d_grupo.php";
f.submit();
//f.txtcuenta.focus(true);
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_grupo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

function uf_ir()
{
	f=document.form1;
	f.action="sigesp_saf_d_subgrupo.php";
	f.submit();
}

//Funciones de validacion de fecha.
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
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
	}

</script>
</html>
