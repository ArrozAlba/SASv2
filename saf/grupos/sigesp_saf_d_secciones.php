<?
session_start();
$dat=$_SESSION["la_empresa"];
$ls_nomestpro3=$dat["NomEstPro3"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <? print $ls_nomestpro3?>  </title>
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
	include("sigesp_spg_c_estprog.php");
	include("class_folder\class_mensajes.php");
	$msg=new class_mensajes();
	$dat=$_SESSION["la_empresa"];
	$ls_nomestpro1=$dat["NomEstPro1"];
	$class_estprog3=new sigesp_spg_c_estprog();
	$ds=null;

	if( array_key_exists("operacionestprog3",$_POST))
	{
		$ls_operacion=$_POST["operacionestprog3"];
		$ls_codestprog1=$_POST["txtcodgru"];
		$ls_codestprog2=$_POST["txtcodsubgru"];
		$ls_denestprog1=$_POST["txtdengru"];
		$ls_denestprog2=$_POST["txtdensubgru"];
		$ls_codestprog3=$_POST["txtcodestprog3"];
		$ls_denestprog3=$_POST["txtdenestprog3"];
		$readonly="";
	}
	else
	{
		$ls_operacion="";
		$ls_codestprog1=$_POST["txtcodgru"];
		$ls_codestprog2=$_POST["txtcodsubgru"];
		$ls_denestprog1=$_POST["txtdengru"];
		$ls_denestprog2=$_POST["txtdensubgru"];
		$ls_codestprog3="";
		$ls_denestprog3="";
		$readonly="";
	}
	if($ls_operacion == "NUEVO")
	{
		$ls_codestprog1=$_POST["txtcodestprog1"];
		$ls_codestprog2=$_POST["txtcodestprog2"];
		$ls_denestprog1=$_POST["txtdenestprog1"];
		$ls_denestprog2=$_POST["txtdenestprog2"];
		$ls_codestprog3="";
		$ls_denestprog3="";
		$readonly="";
	}
	if($ls_operacion == "GUARDAR")
	{
		$ls_codestprog1=$_POST["txtcodestprog1"];
		$ls_denestprog1=$_POST["txtdenestprog1"];
		$ls_codestprog2=$_POST["txtcodestprog2"];
		$ls_denestprog2=$_POST["txtdenestprog2"];
		$ls_codestprog3=$_POST["txtcodestprog3"];
		$ls_denestprog3=$_POST["txtdenestprog3"];
		$readonly="readonly";
		if(($ls_codestprog1!="")&&($ls_codestprog2!="")&&($ls_codestprog3!="")&&($ls_denestprog3!=""))
		{
			$lb_valido=$class_estprog3->uf_spg_insert_estprog3($ls_codestprog1,$ls_codestprog2,$ls_codestprog3,$ls_denestprog3);
			if($lb_valido)
			{
				$msg->message($class_estprog3->is_msg_error);
			}
			else
			{
				$msg->message($class_estprog3->is_msg_error);
			}
		}
		else
		{
			$msg->message("Debe completar todos los campos");
		}
		
	}
	if($ls_operacion == "ELIMINAR")
	{
		$ls_codestprog1=$_POST["txtcodestprog1"];
		$ls_denestprog1=$_POST["txtdenestprog1"];
		$ls_codestprog2=$_POST["txtcodestprog2"];
		$ls_denestprog2=$_POST["txtdenestprog2"];
		$ls_codestprog3=$_POST["txtcodestprog3"];
		$ls_denestprog3=$_POST["txtdenestprog3"];			
		$lb_valido=$class_estprog3->uf_spg_delete_estprog3($ls_codestprog1,$ls_codestprog2,$ls_codestprog3,$ls_denestprog3);
		if($lb_valido)
		{
			$msg->message($class_estprog3->is_msg_error);
		}
		else
		{
			$msg->message($class_estprog3->is_msg_error);
		}
			
		$ls_codestprog3="";
		$ls_denestprog3="";
		$readonly="";
	}
	if($ls_operacion == "BUSCAR")
	{
		$ls_codestprog1=$_POST["txtcodestprog1"];
		$ls_denestprog1=$_POST["txtdenestprog1"];
		$ls_codestprog2=$_POST["txtcodestprog2"];
		$ls_denestprog2=$_POST["txtdenestprog2"];
		$ls_codestprog3=$_POST["txtcodestprog3"];
		$ls_denestprog3=$_POST["txtdenestprog3"];
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
                <td colspan="3"><? print $ls_nomestpro3?></td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td width="463" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="27" align="right"><? print $dat["NomEstPro1"]?></td>
                <td colspan="2" align="left">
                  <input name="txtcodgru" type="text" id="txtcodgru" size="4" maxlength="3" value="<? print  $ls_codestprog1?>" readonly="">
                  <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" value="<? print $ls_denestprog1?>" size="60" maxlength="80" readonly=""></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="27"><div align="right" >
                    <p><? print $dat["NomEstPro2"]?></p>
                </div></td>
                <td colspan="2"><div align="left" >
                    <input name="txtcodsubgru" type="text" id="txtcodsubgru" style="text-align:center " value="<? print $ls_codestprog2?>" size="4" maxlength="3" <? print $readonly?> readonly>
                    <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" style="text-align:left" value="<? print $ls_denestprog2?>" size="60" maxlength="100" readonly>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="31"><div align="right">
                  <p>Codigo</p>
                  </div></td>
                <td colspan="2"><div align="left">
                  <input name="txtcodestprog3" type="text" id="txtcodestprog3" value="<? print $ls_codestprog3?>" size="4" maxlength="3" style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,3,'cod')" <? print $readonly?> >
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">Denominaci&oacute;n</td>
                <td height="20" colspan="2" align="left"><input name="txtdenestprog3" type="text" id="txtdenestprog3" value="<? print $ls_denestprog3?>" size="82" maxlength="100"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left"><input name="botestpro2" type="button" class="boton" id="botestpro2" onClick="javascript: uf_volver();" value="<? print "Volver a ".$dat["NomEstPro2"]?>" ></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left">&nbsp;</td>
              </tr>
          </table>
            <p align="center">
            <input name="operacionestprog3" type="hidden" id="operacionestprog3">
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
f.operacionestprog3.value ="NUEVO";
f.action="sigesp_spg_d_estprog3.php";
f.submit();
}
function ue_guardar()
{
f=document.form1;
f.operacionestprog3.value ="GUARDAR";
f.action="sigesp_spg_d_estprog3.php";
f.submit();
}

function ue_eliminar()
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.operacionestprog3.value ="ELIMINAR";
f.action="sigesp_spg_d_estprog3.php";
f.submit();
//f.txtcuenta.focus(true);
}
function ue_buscar()
{
	codigo1=document.form1.txtcodestprog1.value;
	deno1=document.form1.txtdenestprog1.value;
	codigo2=document.form1.txtcodestprog2.value;
	deno2=document.form1.txtdenestprog2.value;
	window.open("sigesp_catdinamic_estprog3.php?codigo="+codigo1+"&txtdenestprog1="+deno1+"&codestprog2="+codigo2+"&txtdenestprog2="+deno2,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
function uf_volver()
{
	f=document.form1;
	f.action="sigesp_saf_d_subgrupo.php";
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
			document.form1.txtcodestprog3.value=cadena;
		}
	}
</script>
</html>
