<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Calificaci&oacute;n del Proveedor</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_rpc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: uf_close();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("class_folder/sigesp_rpc_c_proxcla.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");

$io_proxcla = new sigesp_rpc_c_proxcla();
$io_conect  = new sigesp_include();
$con        = $io_conect->uf_conectar ();
$la_emp     = $_SESSION["la_empresa"];
$io_msg     = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql     = new class_sql($con); //Instanciando  la clase sql
$io_funcion = new class_funciones(); //Instanciando la clase datastore
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "RPC";
	$ls_ventanas = "sigesp_rpc_w_proxcla.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos             =$_POST["permisos"];
			$la_accesos["leer"]     =$_POST["leer"];			
			$la_accesos["incluir"]  =$_POST["incluir"];			
			$la_accesos["cambiar"]  =$_POST["cambiar"];
			$la_accesos["eliminar"] =$_POST["eliminar"];
			$la_accesos["imprimir"] =$_POST["imprimir"];
			$la_accesos["anular"]   =$_POST["anular"];
			$la_accesos["ejecutar"] =$_POST["ejecutar"];
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
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion    = $_POST["operacion"];
	 $ls_codpro       = $_POST["txtprov"];
	 $ls_nompro       = $_POST["txtnombre"];
     $ls_codigo       = $_POST["txtcodigo"];
 	 $ls_estatus   	  = $_POST["cmbestatus"];
	 $ls_denominacion = $_POST["txtdenominacion"];
	 $ls_nivel		  = $_POST["cmbnivestatus"];
     $ls_codniv		  = $_POST["txtcodniv"];
     $ls_desniv		  = $_POST["txtdesniv"];
     $ls_monmincon	  = $_POST["txtmontomin"];
     $ls_monmaxcon	  = $_POST["txtmontomax"];
	 $ld_monfincon	  = $_POST["txtmonfincon"];
	 $lr_datos["codigo"] 	  = $ls_codigo;
	 $lr_datos["estatus"]	  = $ls_estatus;
     $lr_datos["nivel"]		  = $ls_nivel;
	 $lr_datos["codigoniv"]	  = $ls_codniv;
	 $lr_datos["descripcion"] = $ls_desniv;
	 $lr_datos["montomin"]    = $ls_monmincon;
     $lr_datos["montomax"]    = $ls_monmaxcon;
	 $lr_datos["monfincon"]   = $ld_monfincon;
   }
else
   {
	 $ls_operacion    = "";
 	 $ls_codpro       = $_GET["txtprov"]; 
	 $ls_nompro       = $_GET["txtnombre"];
	 $ls_codigo		  = "";
     $ls_denominacion = "";
     $ls_estatus      = "";
     $ls_nivel		  = ""; 
     $ls_codniv		  = ""; 
     $ls_desniv		  = ""; 
     $ls_monmincon	  = "0,00"; 
     $ls_monmaxcon	  = "0,00"; 
	 $ld_monfincon	  = "0,00"; 
   }    
$ls_estact = "";
$ls_estina = "";
if ($ls_estatus==0)
   {
     $ls_estact = "selected";
   }
elseif($ls_estatus==1)
   {
     $ls_estina = "selected";
   }
$ls_nivnin = "";
$ls_nivbue = "";
$ls_nivreg = "";
$ls_nivmal = "";
if ($ls_nivel==0)
   {
     $ls_nivnin = "selected";
   }
elseif($ls_nivel==1)
   {
     $ls_nivbue = "selected";
   }
elseif($ls_nivel==2)
   {
     $ls_nivreg = "selected";
   }
elseif($ls_nivel==3)
   {
     $ls_nivmal = "selected";
   }
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
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

$ls_codemp = $la_emp["codemp"];
if ($ls_operacion=="GUARDAR")
   { 
     $lb_existe = $io_proxcla->uf_existe_proveedor($ls_codemp,$ls_codpro);
     if ($lb_existe)
        {
		  $li_return = $io_proxcla->ue_guardar($ls_codpro,$ls_codemp,$lr_datos,$la_seguridad);
	      $ls_codigo="";		
		  $ls_denominacion="";		
		  $ls_estatus="";		
		  $ls_nivel="";		
        }
     else
        {            
          $io_msg->message('El Proveedor No Existe!!!');          
        }
   }
if ($ls_operacion=="ELIMINAR")
   {
     $li_return = $io_proxcla->ue_eliminar($ls_codemp,$ls_codigo,$ls_codpro,$la_seguridad);
     $ls_codigo="";
	 $ls_denominacion="";
	 $ls_estatus="";
	 $ls_nivel="";
     $ls_codniv=""; 
     $ls_desniv=""; 
     $ls_monmincon=""; 
     $ls_monmaxcon="";
   }
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?> 
	<table width="578" height="22" border="0" align="center" cellpadding="0" cellspacing="0" class="titulo-celda">
	<tr class="titulo-celda"> 
        <td style="text-align:left"> &nbsp;Proveedor: <?php print $ls_codpro ?> -  <?php print $ls_nompro ?></td>
      </tr>
  </table>
<br>
  <table width="578" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celdanew">
      <td height="22" colspan="3" align="center">Calificaci&oacute;n del Proveedor</strong></font></td>
    </tr>
    <tr>
      <td height="22" align="right">&nbsp;</td>
      <td height="22"><input name="txtprov" type="hidden" id="txtprov" value="<?php print $ls_codpro ?>">
      <input name="txtnombre" type="hidden" id="txtnombre" value="<?php print $ls_nompro ?>">
      <input name="operacion" type="hidden" id="operacion">      </td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td width="220" height="22" align="right">Código</td>
      <td width="322" height="22"><input name="txtcodigo" type="text"  id="txtcodigo" value="<?php print $ls_codigo ?>" readonly style="text-align:center">
        <a href="javascript:catalogo_clasificacion();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" ></a></td>
      <td width="34" height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Denominaci&oacute;n</td>
      <td height="22"><input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion ?>" size="40" maxlength="254" readonly></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Estatus</td>
      <td height="22"><select name="cmbestatus" id="select">
        <option value="0" <?php echo $ls_estact ?>>Activa</option>
        <option value="1" <?php echo $ls_estina ?>>No Activa</option>
      </select></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" align="right">Nivel del Estatus</td>
      <td height="22"><select name="cmbnivestatus" id="select">
        <option value="0" <?php echo $ls_nivnin ?>>Ninguno</option>
        <option value="1" <?php echo $ls_nivbue ?>>Excelente</option>
        <option value="2" <?php echo $ls_nivreg ?>>Bueno</option>
        <option value="3" <?php echo $ls_nivmal ?>>Regular</option>
      </select></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">C&oacute;digo Nivel de Clasificaci&oacute;n</td>
      <td height="22"><input name="txtcodniv" type="text"  id="txtcodniv" value="<?php print $ls_codniv ?>" size="6" maxlength="3" readonly style="text-align:center">
      <a href="javascript:catalogo_niveles();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" ></a></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Descripci&oacute;n del Nivel</td>
      <td height="22"><input name="txtdesniv" type="text"  id="txtdesniv" value="<?php print $ls_desniv ?>" size="15" maxlength="20" readonly></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Monto M&iacute;nimo de Contrataci&oacute;n</td>     
      <td height="22"><input name="txtmontomin" type="text"  id="txtmontomin" value="<?php print $ls_monmincon ?>" size="30" maxlength="20" readonly style="text-align:right"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Monto M&aacute;ximo de Contrataci&oacute;n</td>
      <td height="22"><input name="txtmontomax" type="text"  id="txtmontomax" value="<?php print $ls_monmaxcon ?>" size="30" maxlength="20" readonly style="text-align:right"></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nivel Financiero Estimado de Contrataci&oacute;n </td>
      <td height="22"><label>
        <input name="txtmonfincon" type="text" id="txtmonfincon" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))" value="<?php echo $ld_monfincon ?>" size="30" maxlength="20">
      </label></td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function ue_nuevo()
{
  f=document.form1;
  f.txtcodigo.value="";
  f.txtdenominacion.value="";
  f.cmbestatus[0].selected=true;
  f.cmbnivestatus[0].selected=true;
  f.txtcodniv.value="";
  f.txtdesniv.value="";
  f.txtmontomin.value="0,00";
  f.txtmontomax.value="0,00";
  f.txtmonfincon.value="0,00";
}

function ue_guardar()
{
  var resul="";
  with (document.form1)
       {
         if (valida_null(txtcodigo,"El Código esta vacio!!")==false)
		    {
			 txtcodigo.focus();
		    }
		 else
		    {
			  f=document.form1;
			  f.operacion.value="GUARDAR";
			  f.action="sigesp_rpc_w_proxcla.php";
			  f.submit();
		    }
       }
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

function ue_eliminar()
{
 var borrar="";
 f=document.form1;

 if (f.txtcodigo.value=="")
    {
	  alert("No ha seleccionado ningún registro para eliminar !!!");
      f.txtcodigo.focus=true;
    }
 else
	{
	  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
	  if (borrar==true)
		 { 
		   f=document.form1;
		   f.operacion.value="ELIMINAR";
		   f.action="sigesp_rpc_w_proxcla.php";
		   f.submit();
		 }
	  else
		 { 
		   f=document.form1;
		   alert("Eliminación Cancelada !!!");
		 }
	}		 
}

function uf_close()
{ 
 close();
}  

function catalogo_clasificacion()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_rpc_cat_clasifica.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function catalogo_niveles()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_rpc_cat_niveles.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no,dependent=yes");
}

function ue_buscar()
{
	f=document.form1;
	f.operacion.value="";
	ls_prov=f.txtprov.value;			
	pagina="sigesp_cat_clasxpro.php?txtprov="+ls_prov;
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no");
}		
</script>
</html>