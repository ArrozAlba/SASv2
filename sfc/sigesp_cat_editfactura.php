<?
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";		
} 

$la_datemp=$_SESSION["la_empresa"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Edici&oacute;n de Factura</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css"> <!--  para icono de fecha -->

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<style type="text/css">
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
	color: #006699#006699;
}
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sfc_c_secuencia.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];
$io_secuencia=new sigesp_sfc_c_secuencia();
//$ls_nrofactura=$_GET["ls_nrofactura"];
$ls_nrofacturaaux=$_GET["ls_nrofactura"];
/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_numfac=$_POST["numfac"];
	$ls_nrofactura=$_POST["numfac"];
	//print $ls_nrofactura;	
	$ls_correlativo=$_POST["correlativo"];
}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{
	$ls_operacion="";	
	$ls_numfac="";	
	$ls_nrofactura=$_GET["ls_nrofactura"];
	$ls_correlativo='';
}
/************************************************************************************************************************/
/***************************   TABLA DREAMWEAVER ************************************************************************/
/************************************************************************************************************************/

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
	
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Facturas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="804" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
      <td>&nbsp;</td>
	     <td>&nbsp;</td>
	 <tr>
       <td width="56"><div align="right">Nro. Factura </div></td>
       <td width="598"><input name="numfac" type="text"   style="font-size:36px; background-color:#FF0000" id="numfac" size="30" maxlength="25" value="<? print $ls_nrofactura ?>" readonly></td>		
      </tr>
	  <tr>
	  <td>
	  <br>
	  <br>
	  
	  </td>
	  </tr>
	   <tr>
	  <td>
	  <br>
	  </td>
	  </tr>
	   <tr>
	    
       <td width="56"><div align="right">Nuevo Correlativo </div></td>
        <td width="598"><input name="correlativo" type="text" style="font-size:36px; background-color:#FF0000" id="correlativo" size="15" maxlength="25" value="<? print $ls_correlativo ?>">
         <a href="javascript:ue_validarnro('<?php print $ls_correlativo ?>');"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Validar" width="15" height="15" border="0">Verfificar Nro. Factura </a></td>
				
      </tr>
	   <tr>
	  
	  <td>&nbsp;</td>	
	  <td>&nbsp;</td>	 
	  <td width="63"><a href="javascript:ue_aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" border="0">Aceptar</a></td>
	  
	  <td width="85"><a href="javascript:ue_cancelar('<?php print $ls_nrofactura ?>');"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" border="0">Cancelar</a></td>

	  </tr>
	  
  </table>
	<br> 
<?php
	if($ls_operacion=="BUSCAR")
	{
	$ls_codusu=$_SESSION["la_logusr"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
	$ls_prefijo=$_SESSION["ls_prefac"];
	$ls_serie=$_SESSION["ls_serfac"];
	//$io_secuencia->uf_obtener_secuencia($ls_codcaj."fac",&$ls_secuencia);
	//print $ls_correlativo;
	if ($ls_correlativo<>'')
	{
	$ls_correlativoaux=$io_funcion->uf_cerosizquierda($ls_correlativo,16);
	$ls_nrofactura=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_correlativoaux);
//	print $ls_nrofactura;	
	}
	?>
	<script>
	numfac='<?php print $ls_nrofactura;?>';
	//alert (numfac);
  	document.form1.numfac.value=numfac;
	</script>
	<?php		
	$ls_cadena=" SELECT  numfac  FROM sfc_factura  WHERE numfac='".$ls_nrofactura."' ";
	// print $ls_cadena;
          	$lb_valido=false;
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				//$io_msg->message("Nro. Valido");
				$lb_valido=false;
			}
			else
			{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=false;
				$io_msg->message("Nro. factura existente, verifique Nro.");	
			}
			else
			{
				$lb_valido=true;				
				$io_msg->message("Nro. factura valido");
				
			}
				
			}
}   
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
/**********************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT *****************************************/
/**********************************************************************************************************/
   function ue_cancelar(numfac)
	{
		//opener.ue_cargarnrofactura(numfac);
		close();
	}
               
  function ue_aceptar()
  {
  
  	nro_valido='<? print $lb_valido; ?>';
	numfac='<? print $ls_nrofactura; ?>';
	correlativo='<? print $ls_correlativo; ?>';
	if(confirm("¿Está seguro de que ese es el correlativo que corresponde a la Factura es?  "+numfac))
	{
	//alert ('paso');
	valido=true;
	}
	else
	{
	valido=false;
	}
	if (nro_valido==true && valido==true)
	{
	//alert (numfac);
    opener.ue_cargarnrofactura(numfac,correlativo);	
	close();
	}
	else
	{
	//alert ('Debe colocar un Nro. de Factura Valido');
	f.numfac.value='<? print $_GET["ls_nrofactura"] ?>';
	//f.numfac.focus();
	f.action="sigesp_cat_editfactura.php";
  	f.submit();
	//f.numfac.focus();
	}
	
	
  }
 
  function ue_validarnro()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_editfactura.php";
  f.submit();
  }
 
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
