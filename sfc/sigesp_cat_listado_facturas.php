<?php
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
<title>Cat&aacute;logo de Facturas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<?php
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
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


/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	/*$ls_nomcla="%".$_POST["nomcla"]."%";*/
	$ls_codusu=$_POST["combo_cajero"];
	$ls_codcli=$_POST["txtcodcli"];
	$ls_fecemi=$_POST["txtfecemi"];
	$ls_orden=$_POST["combo_orden"];
	$ls_ordenarpor=$_POST["combo_ordenarpor"];

}
else
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
{
	$ls_operacion="";
	$ls_codusu="";
	$ls_codcli="";
	$ls_fecemi="";
	$ls_orden="";
	$ls_ordenarpor="";
}

?>
  <p align="center">&nbsp;</p>
  	 <table width="405" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="453" colspan="2" class="titulo-celda">Listado de facturas </td>
    	</tr>
  </table>
	 <table width="409" height="128" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      
      <tr>
        <td width="106"><div align="right">
          <input name="txtquerysql" type="hidden" id="txtquerysql" value="<? print $ls_querysql ?>">
        Ordenar por</div></td>
        <td width="150"><select name="combo_ordenarpor" size="1">
          <option value="Null">Seleccione...</option>
          <option>C&eacute;dula cliente</option>
          <option>Fecha</option>
          <option>Monto</option>
          <option>N&uacute;mero factura</option>
          <option>Status factura</option>
        </select></td>
        <td width="151" height="22"><p>
          <label>
          <div align="left">
          <br>
          <br>
          Orden
          <select name="combo_orden" size="1">
            <option value="ASC">ASC</option>
            <option value="DESC">DESC</option>
          </select>
          </label>
          <p align="left"><br>           
        </p></td>
      </tr>
      <tr>
        <td height="28" colspan="3"><table width="320" border="0" align="center" cellpadding="0" cellspacing="0" class="letras-negrita">
          <tr>
            <th height="0" colspan="3" class="titulo-celdanew" scope="col">SELECCIONE</th>
          </tr>
          <tr>
            <th width="0" height="0" scope="col"><div align="right">Cajero</div></th>
            <th width="0" height="0" scope="col"><div align="left">
              <p>
                <?Php
		    $ls_sql="SELECT DISTINCT sfc_factura.codusu, sfc_cajero.nomusu 
                       FROM sfc_cajero,sfc_factura
					   WHERE sfc_cajero.codusu=sfc_factura.codusu
                       ORDER BY sfc_cajero.nomusu ASC";					
			$lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_nomusu);
			if($lb_valido)
			 {
			   $io_datastore->data=$la_nomusu;
			   $li_totalfilas=$io_datastore->getRowCount("codusu");
			 }
			 else
			   $li_totalfilas=0;
		    ?>
                <select name="combo_cajero" size="1" id="combo_cajero">
                  <option>Seleccione..</option>
                </select>
              </p>
              </div></th>
            <th width="0" height="0" scope="col">&nbsp;</th>
          </tr>
          <tr>
            <td width="0" height="0"><div align="right">C&oacute;digo cliente</div></td>
            <td width="0" height="0"><p>
              <input name="txtcodcli" type="text" id="txtcodcli" value="<?php print $ls_codcli ?>">
            </p>
            </td>
            <td width="0" height="0">&nbsp;</td>
          </tr>
          <tr>
            <td width="0" height="0"><div align="right">Fecha</div></td>
            <td width="0" height="0"><p>
              <input name="txtfecemi" type="text" id="txtfecemi"  style="text-align:left" value="<?php print $ls_fecemi ?>" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true">
            </p>
            </td>
            <td width="0" height="0">&nbsp;</td>
          </tr>
        </table></td>
       </tr>
      <tr>
        <td height="28"><div align="right">Cajero</div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="28"><div align="right">C&oacute;digo cliente </div></td>
        <td><p>
          <label></label>        
          </p></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="center" valign="middle"><div align="right">
          <p>Fecha</p>
          </div></td>
        <td height="22" valign="top"><label></label></td>
        <td height="22"><div align="left">
          <p><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Ver</a></p>
          </div></td>
      </tr>
  </table>
     <p>&nbsp;</p>
   <p>
     <?PHP

/************************************************************************************************************************/
/******************************  BUSCAR *********************************************************************************/
/************************************************************************************************************************/
if($ls_operacion=="VER")
{
          $ls_operacion="";
  /*$_SESSION["cajero"]=$ls_codusu;
	$_SESSION["codcli"]=$ls_codcli;
	$_SESSION["fecemi"]=$ls_fecemi;
	$_SESSION["orden"]=$ls_orden;
	$_SESSION["ordenarpor"]=$ls_ordenarpor;*/
 	       $ls_sql="SELECT * FROM sfc_factura;";				
			$rs_datauni=$io_sql->select($ls_sql);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{ 
			   $la_factura=$io_sql->obtener_datos($rs_datauni);
	        }
		$_SESSION["querysql1"]=$la_factura;
?>
       
     <script language="JavaScript">
	  pagina="../fac/reportes/documentos/sfv_rep_plantilla.php";
	  popupWin(pagina,"catalogo",580,700);
     </script>
       
     <?PHP
} 
?>
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<!--************************************************************************************************************************/
/*************************** FUNCIONES DE JAVA SCRIPT **********************************************************************/
/*************************************************************************************************************************-->
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
  f.action="sigesp_cat_listado_facturas.php";
  f.submit();
  
  } 
</script>

<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>