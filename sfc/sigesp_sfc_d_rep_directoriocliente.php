<?Php
/******************************************/
/* FECHA: 13/08/2007                      */ 
/* AUTOR: ING. ZULHEYMAR RODRÍGUEZ        */         
/******************************************/

session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_conexion.php'";
	 print "</script>";		
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Directorio de Clientes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.style6 {color: #000000}
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
  <tr> 
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="537" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="241" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu">
	<script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?Php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codcli="%/".$_POST["txtcodcli"]."%";
	$ls_razcli="%/".$_POST["txtrazcli"]."%";
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];
}
else
{
	$ls_operacion="";
   	$ls_codcli="";
	$ls_razcli="";
	$ls_orden="";
	$ls_ordenarpor="Null";
}
?>
<form name="form1" method="post" action="">
    <table width="518" height="260" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="258"><div align="center">
            <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
              <tr>
                <td colspan="3" class="titulo-ventana">Directorio de Clientes (Filtrar) </td>
              </tr>
              <tr>
                <td colspan="3" class="sin-borde">&nbsp;</td>
              </tr>
              <tr>
                <td width="143" ><div align="right">
                  <input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion?>">                  
                  Ordenar por                  
                </div></td>
                <td width="153" ><p align="left">				
                  <select name="combo_ordenarpor" size="1" >				  
				  <?php
				  if ($ls_ordenarpor=="Null")
				   {
				   ?>
				    <option value="Null" selected>Seleccione...</option>
				    <option value="cli.codcli">C&eacute;dula &oacute; rif</option>
				    <option value="cli.razcli">Nombre</option>
				   
				  <?php
				   }
				  elseif ($ls_ordenarpor=="cli.codcli") 
				   {
				    ?>
					<option value="Null">Seleccione...</option>
				    <option value="cli.codcli" selected>C&eacute;dula &oacute; rif</option>
				    <option value="cli.razcli">Nombre</option>
				   
				  <?php
				   }
				   elseif ($ls_ordenarpor=="cli.razcli")
				   {
				    ?>
					<option value="Null" >Seleccione...</option>
				    <option value="cli.codcli">C&eacute;dula &oacute; rif</option>
				    <option value="cli.razcli" selected>Nombre</option>
				   
				  <?php
				    }
				   else
				   {
				    ?>
					<option value="Null" >Seleccione...</option>
				    <option value="cli.codcli">C&eacute;dula &oacute; rif</option>
				    <option value="cli.razcli">Nombre</option>
				    <option value="tie.dentie" selected>tienda</option>
				  <?php
				   }
				  ?>
                  </select>				  
                Orden</p>
				</td>
                <td width="104" >
				<select name="combo_orden" size="1">
				<?php
				  if ($ls_orden=="ASC")
				   {
				   ?>
                  <option value="ASC" selected>ASC</option>
                  <option value="DESC">DESC</option>
				  <?php
				   }
				  else
				   {
				   ?>
                  <option value="ASC" >ASC</option>
                  <option value="DESC" selected>DESC</option>
				  <?php
				  }
				  ?>
                </select></td>
              </tr>
              <tr align="left">
                
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td height="22" align="right">C&eacute;dula &oacute; rif  </td>
                <td colspan="2" ><input name="txtcodcli" type="text" id="txtcodcli">
                <a href="javascript: ue_ver();"></a></td>
              </tr>
              <tr>
                <td height="22" align="right">Nombre</td>
                <td colspan="2" ><input name="txtrazcli" type="text" id="txtrazcli"><a href="javascript: ue_ver();"></a></td>
              </tr>
              
              <tr>
                <td height="8">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</form>
<?php
if($ls_operacion=="VER")
{
        $ls_operacion="";
		if ($ls_ordenarpor!="Null")
		{
  	    $ls_sql="SELECT DISTINCT cli.*,tie.dentie FROM sfc_tienda tie,sfc_cliente cli WHERE cli.codtie=tie.codtie AND cli.razcli ilike '".$ls_razcli."' AND cli.cedcli ilike '".$ls_codcli."' ORDER BY ".$ls_ordenarpor." ".$ls_orden.";";
		}else{
		$ls_sql="SELECT DISTINCT cli.*,tie.dentie FROM sfc_tienda tie,sfc_cliente cli WHERE cli.codtie=tie.codtie  AND cli.razcli ilike '".$ls_razcli."' AND cli.cedcli ilike '".$ls_codcli."';";
		
		}
?>       
     <script language="JavaScript">  
   	 	var ls_sql="<?php print $ls_sql; ?>"; 
	   	pagina="reportes/sigesp_sfc_rep_directoriocliente.php?sql="+ls_sql;
	  	popupWin(pagina,"catalogo",580,700);
     </script> 
       
<?PHP
} 
?>
</body>
<script language="JavaScript"> 
  function aceptar(codcla,nomcla)
  {
    opener.ue_cargarclasificacion(codcla,nomcla);
	close();
  } 
  function ue_ver()
  {
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_rep_directoriocliente.php";
  f.submit();  
  } 
  function actualizar_combo()
  {
  f=document.form1;
  f.combo_ordenarpor.value="VER";
  f.action="sigesp_sfc_d_rep_directoriocliente.php";
  f.submit();  
  }  
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>