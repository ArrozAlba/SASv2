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
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 15px}
.Estilo2 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" background="../shared/imagebank/header.jpg" class="contorno">
  <tr>
    <td height="30" background="imagebank/header.jpg"><a href="imagebank/header.jpg"><img src="../shared/imagebank/header.jpg" width="778" height="40" border="0"></a></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
   <tr>
    <td height="20" class="toolbar"><img src="../shared/imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_nuevor();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/deshacer.gif" alt="Deshacer" width="20" height="20"><img src="../shared/imagebank/tools20/filtrar.gif" alt="Filtrar" width="20" height="20">
	<img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>

<?php
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$int_scg=new class_sigesp_int_scg();
require_once("../shared/class_folder/sigesp_include.php");
$in = new sigesp_include();
$con= $in-> uf_conectar ();
$msg=new class_mensajes(); //Instanciando la clase mensajes 
$SQL=new class_sql($con); //Instanciando  la clase sql
require_once("sigesp_scg_class_definicion.php");
$def= new sigesp_scg_class_definicion();
require_once("../shared/class_folder/class_datastore.php");
$ds_plan=new class_datastore(); //Instanciando la clase datastore

if(array_key_exists("operacion",$_POST))
{
$ls_operacion=$_POST["operacion"];
}
else
{
$ls_operacion="";
}
if(array_key_exists("txtcuenta",$_POST))
{
  $ls_cuenta=$_POST["txtcuenta"];
}
else
{
  $ls_cuenta="";
}
if(array_key_exists("txtdenominacion",$_POST))
{
$ls_denominacion=$_POST["txtdenominacion"];
}
else
{
$ls_denominacion="";
}

?>
<p class="cd-titulo">
<?php
if($ls_operacion=="GUARDAR")
{
      $li_return = $int_scg->uf_insert_plan_unico_cuenta($ls_cuenta,$ls_denominacion);
	  if($li_return==2)
	  {
	    $ls_cuenta=" ";
        $ls_denominacion=" ";
		$msg->message("Registro Actualizado");
	 }
		
	 if($li_return==1)
	 {
	    $ls_cuenta=" ";
        $ls_denominacion=" ";
	    $msg->message("Registro Incluido"); 
     }
}

if($ls_operacion=="ELIMINAR")
{
   $msg->message("Esta seguro de Eliminar este Registro");  //terminarlo cuando tengamos la clase de menssage preguntar por la respuesta 
   $lb_valido=$def->uf_delete_planunico($ls_cuenta,$ls_denominacion);
   $ls_cuenta=" ";
   $ls_denominacion=" ";
   
   if ($lb_valido)
   {   
	  $msg->message("Registro  Eliminado");            	
   }
   else 
   {
     $msg->message("No se encontro el registro");
   }
}

if($ls_operacion=="NUEVO")
{
   $ls_cuenta=" ";
   $ls_denominacion=" ";
}
?>
</p>
<p class="cd-titulo">&nbsp;</p>
<p class="cd-titulo">&nbsp;  
</p>
<div align="center">
  <form name="form1" method="post" action="">
    <table width="522" height="170" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="520"><table width="452" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana">
            <td colspan="2"><div align="center">Plan Unico </div></td>
          </tr>
          <tr>
            <td width="93" height="20"><span class="Estilo1"></span></td>
            <td width="357"><span class="Estilo1"></span></td>
          </tr>
          <tr>
            <td><div align="right" class="fd-blanco">Codigo</div></td>
            <td><div align="left">
                <input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuenta?>">
            </div></td>
          </tr>
          <tr>
            <td><span class="Estilo1"></span></td>
            <td><span class="Estilo1"></span></td>
          </tr>
          <tr>
            <td><p align="right" class="fd-blanco">Denominaci&oacute;n</p></td>
            <td><div align="left">
              <input name="txtdenominacion" type="text" id="txtdenominacion" onKeyPress="uf_validacaracter('%d',this)" size="60" maxlength="250" value="<?php print $ls_denominacion?>">
            </div></td>
          </tr>
          <tr class="fd-blanco">
            <td height="20"><span class="Estilo1"></span></td>
            <td><span class="Estilo1">
              <input name="operacion" type="hidden" id="operacion2" value="<?php $_POST["operacion"]?>">
            </span></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </form>
</div>
</body>
<script language="javascript">

function ue_guardar()
{
  var resul="";
  with (form1)
  {
    if (valida_null(txtcuenta,"La cuenta esta vacia!!")==false)
    {
      txtcuenta.focus();
    }
    else
    {
	  if (valida_null(txtdenominacion,"La denominación esta vacia!!")==false)
	  {
	    txtdenominacion.focus();
	  }
	  else
	  {
		f=document.form1;
		f.operacion.value="GUARDAR";
		f.action="sigesp_scg_plan_unico.php";
		f.submit();
	  }
	} 
  }
}

function ue_eliminar()
{
f=document.form1;
f.operacion.value="ELIMINAR";
f.action="sigesp_scg_plan_unico.php";
f.submit();
}

function ue_nuevo()
{
f=document.form1;
f.operacion.value="NUEVO";
f.action="sigesp_scg_plan_unico.php";
f.submit();
}

function ue_buscar()
{
   f=document.form1;
   pagina="sigesp_catdinamic_ctaspu.php?";
   window.open(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,heigth=450,resizable=yes,location=no")
}

function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}

function uf_validacaracter(cadena, obj)
{ 
   opc = false; 
   if (cadena == "%d")//toma solo caracteres  
   if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
   opc = true; 
   
   if(opc == false) 

   event.returnValue = false; 
} 
	
</script>
</html>
