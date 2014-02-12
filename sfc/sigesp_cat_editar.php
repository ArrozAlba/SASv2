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
$ls_sercon=$_SESSION["ls_sercon"];

$ls_nroconaux=$_GET["ls_nrocontrol"];

//$ls_nrocontrolaux=$io_funcion->uf_cerosizquierda($ls_nroconaux,16);
//print $ls_nroconaux;
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
    $ls_nrocon=$_POS["nrocontrol"];
	$ls_correlativocon=$_POST["correlativocon"];
//print $ls_correlativocon;
	$lb_valido=$_POST["validofac"];
	$lb_valido2=$_POST["validocon"];


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
	$ls_nrocon=$_GET["ls_nrocontrol"];
	$ls_correlativocon='';
	$lb_valido="";
	$lb_valido2="";
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Devolucion </td>
    	</tr>
	 </table>
	 <br>
	 <table width="804" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <td>&nbsp;</td>
	     <td>&nbsp;</td>

	 <tr>

	 <td width="56"><div align="right">Nro. Control </div></td>
       <td width="598"><input name="numcontrol" type="text"   style="font-size:36px; background-color:#FF0000" id="numcontrol" size="30" maxlength="35" value="<? print $ls_nrocon ?>" readonly></td>
      </tr>
	  <tr>
	  <td>
	  <br>
	  <br>

	  </td>
	  </tr>


	   <tr>

       <td width="56"><div align="right">Nuevo Numero Control</div></td>
        <td width="598"><input name="correlativocon" type="text" style="font-size:36px; background-color:#FF0000" id="correlativocon" size="15" maxlength="25" value="<? print $ls_correlativocon ?>" onKeyPress=return(currencyFormat(this,'.',',',event))>
         <a href="javascript:ue_validarnrocontrol('<?php print $ls_correlativocon ?>');"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Validar" width="15" height="15" border="0">Verfificar Nro. Control </a></td>

      </tr>

	   <tr>
	  <td>
	  <br>
	  <br>
	  </td>

	 </tr>



	 <tr>
       <td width="56"><div align="right">Nro. Devolucion </div></td>
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

       <td width="56"><div align="right">Nuevo Numero Devolucion</div></td>
        <td width="598"><input name="correlativo" type="text" style="font-size:36px; background-color:#FF0000" id="correlativo" size="15" maxlength="25" value="<? print $ls_correlativo ?>">
         <a href="javascript:ue_validarnro('<?php print $ls_correlativo ?>');"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Validar" width="15" height="15" border="0">Verfificar Nro. Factura </a></td>

      </tr>



	   <tr>



	  <td>&nbsp;</td>
	  <td>&nbsp;</td>

	  <td width="63"><a href="javascript:ue_aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" border="0"  >Aceptar</a></td>

	  <td width="85"><a href="javascript:ue_cancelar('<?php print $ls_nrofactura ?>');"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" border="0">Cancelar</a></td>

	  </tr>





  </table>
	<br>
<?php
	if($ls_operacion=="BUSCAR")
	{

	$ls_codusu=$_SESSION["la_logusr"];
	$ls_codcaj=$_SESSION["ls_codcaj"];
//	$ls_prefijo=$_SESSION["ls_prefac"];
	$ls_serie=$_SESSION["ls_serfac"];
	$ls_nrocon=$_POST["numcontrol"];
	$ls_prefijo=$_SESSION["ls_predev"];;
//	var_dump($_SESSION);
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
	numcontrol='<?php print $ls_nrocon;?>';

	//alert (numcontrol);

  	document.form1.numcontrol.value=numcontrol;
	</script>
	<?php
	$ls_cadena=" SELECT  coddev  FROM sfc_devolucion  WHERE coddev='".$ls_nrofactura."' ";
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
				$io_msg->message("Nro. devolucion existente, verifique Nro.");
			}
			else
			{
				$lb_valido=true;
				$io_msg->message("Nro. devolucion valido");

			}

			}

	?>
	 <input name="validofac" type="hidden" id="validofac"  value="<? print $lb_valido?>">
	 <?php

}



elseif($ls_operacion=="BUSCARCONTROL")
	{


	$ls_nrocon=$_POST["numcontrol"];
//var_dump($_POST);
	//print $ls_operacion.$ls_nrocon;
	$ls_seriecon=$_SESSION["ls_sercon"];
	//$io_secuencia->uf_obtener_secuencia($ls_codcaj."fac",&$ls_secuencia);

	if ($ls_correlativocon<>'')
	{
		//print $ls_correlativocon;
	$ls_correlativoconaux=$io_funcion->uf_cerosizquierda($ls_correlativocon,16);
	$ls_nrocon=$io_secuencia->uf_crear_codigo("",$ls_seriecon,$ls_correlativoconaux);
	//print $ls_nrocon;
	}
	//print $ls_nrocon;
	?>
	<script>
	numcontrol='<?php print $ls_nrocon;?>';

	//alert (numcontrol);

  	document.form1.numcontrol.value=numcontrol;
	</script>
	<?php
	$ls_nroconntrolaux=$_SESSION["ls_sercon"]."-".$ls_nrocon;
	$ls_cadena=" SELECT numcon  FROM sfc_devolucion  WHERE numcon= '".$ls_nroconntrolaux."' ";
	//print $ls_cadena;
          	$lb_valido2=false;
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				//$io_msg->message("Nro. Valido");
				$lb_valido2=false;
			}
			else
			{
			if($row=$io_sql->fetch_row($rs_datauni))
			{
				$lb_valido2=false;
				$io_msg->message("Nro. Control existe, verifique Nro.");
			}
			else
			{
				$lb_valido2=true;
				$io_msg->message("Nro. Control valido");

			}

			}

	?>
	 <input name="validocon" type="hidden" id="validocon"  value="<? print $lb_valido2?>">
	 <?php



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
	f=document.form1;
  	nro_valido='<? print $lb_valido; ?>';
  	controlvalido='<? print $lb_valido2; ?>';
   // alert(nro_valido);
   //  alert(controlvalido);
	numfac='<? print $ls_nrofactura; ?>';
	correlativo='<? print $ls_correlativo; ?>';
	numcontrol='<? print $ls_nrocon; ?>';
	correlativocon='<? print $ls_correlativocon; ?>';

	if (nro_valido==1 && controlvalido==1)
	{
	//alert (numfac);
	//alert(nro_valido==true && controlvalido==true);

		if(confirm("�Est� seguro de que ese es el correlativo que corresponde a la Devolucion es?  "+numfac+" Y Numero de Control: "+numcontrol))
		{
			//alert ('paso');
			valido=true;
			valido2=true;
			opener.document.getElementById('txtcoddev').value=numfac;
			opener.document.getElementById('txtnumcont').value=numcontrol;
			close();
		}
		else
		{
			valido=false;
			valido2=false;

		}

	}
	else
	{
		if (nro_valido=="")
		{
			alert ('Debe colocar un Nro. de Devolucion Valido');
			f.numfac.value='<? print $_GET["ls_nrofactura"] ?>';
			f.action="sigesp_cat_editar.php";
		  	f.submit();

		}
		if (controlvalido=="")
		{
			alert ('Debe colocar un Nro. de Control Valido');
			f.numcontrol.value='<? print $_GET["ls_nrocon"] ?>';
			f.action="sigesp_cat_editar.php";
		  	f.submit();

		}

	}


  }

  function ue_validarnro()
  {
  f=document.form1;

  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_editar.php";
  f.submit();
  }

function ue_validarnrocontrol()
  {
  f=document.form1;
 // alert(f.numcontrol.value);
  f.operacion.value="BUSCARCONTROL";

  f.action="sigesp_cat_editar.php";
  f.submit();
  }

function currencyFormat(fld, milSep, decSep, e)
 {
 	milSep="";
	decSep="";
 	var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true; // Enter
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del
    key = String.fromCharCode(whichCode); // Get key value from key code
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key
    len = fld.value.length;
    for(i = 0; i < len; i++)
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
    aux = '';
    for(; i < len; i++)
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) fld.value = '';
    if (len == 1) fld.value = '0'+ decSep + '0' + aux;
    if (len == 2) fld.value = '0'+ decSep + aux;
    if (len > 2)
	{
     aux2 = '';
     for (j = 0, i = len - 3; i >= 0; i--)
	 {
      if (j == 3)
	  {
       aux2 += milSep;
       j = 0;
      }
      aux2 += aux.charAt(i);
      j++;
     }

     fld.value = '';
     len2 = aux2.length;
     for (i = len2 - 1; i >= 0; i--)
      fld.value += aux2.charAt(i);

     fld.value += decSep + aux.substr(len - 2, len);
    }


	return false;


 }






</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
