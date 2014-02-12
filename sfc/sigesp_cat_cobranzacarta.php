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
$ls_codtie=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cobranza</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">

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
$ls_codtie=$_SESSION["codtiend"];
print $ls_codtie."<br>";
/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion   = $_POST["operacion"];
	$ls_codbanco    = "%".$_POST["combo_banco"]."%";
	$ls_feccob_desde= $_POST["txtfeccob_desde"];
	$ls_feccob_hasta= $_POST["txtfeccob_hasta"];
	$ls_numcob      = "%".$_POST["txtnumcob"]."%";
	
}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{

	$ls_operacion   ="";
	$ls_codbanco    ="";
	$ls_feccob_desde="";
	$ls_feccob_hasta="";
	$ls_numcob      ="";
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cobranzas</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
    <tr>
         <td height="31"><div align="right">Banco</div></td>
         <td colspan="2"><label>
		 	     <?php
/************************************************************************************************************************/
/********************************************  COMBO BANCO  *****************************************************/		  
/************************************************************************************************************************/
		 $ls_sql="SELECT codban,nomban
                  FROM   scb_banco
                  ORDER  BY nomban ASC";
					
			$lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_nomban);
			
			if($lb_valest)
			 {
			   $io_datastore->data=$la_nomban;
			   $li_totalfilas=$io_datastore->getRowCount("codban");
			 }
			 else
			 {
			   $li_totalfilas=0;
			 }
			   ?>	 			 
			  <select name="combo_banco" size="1" id="combo_banco">
			   <option value="" >Seleccione...</option>
			 <?php	
			   
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigob=$io_datastore->getValue("codban",$li_i);
						 $ls_nomban=$io_datastore->getValue("nomban",$li_i);
					
						 if ($ls_codigob==$ls_codbanco)
						 {						 	
							  print "<option  value='$ls_codigob' selected>$ls_nomban</option>";
							 $ls_nombanco=$ls_nomban;
						 }
						 else 
						 {
							  print "<option  value='$ls_codigob' >$ls_nomban</option>";
						 }
					}				
	        ?>
           </select>
         </label></td>
       </tr>
        <td height="27"><div align="right">Fecha cobro </div></td>
        <td><p>
          Desde
          <input name="txtfeccob_desde" type="text" id="txtfeccob_desde"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true">
          Hasta
          <input name="txtfeccob_hasta" type="text" id="txtfeccob_hasta"  style="text-align:left" size="11" maxlength="10" onKeyPress="javascript:ue_validafecha();"  datepicker="true" readonly="true"></p>
        </td>
      </tr>
      <tr>
        <td><div align="right">No.Cobro</div></td>
        <td><input name="txtnumcob" type="text" id="txtnumcob" value="<?php $ls_numcob ?>"  size="60"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
/************************************************************************************************************************/
/******************   BUSCAR --> BUSCA LA FACTURA Y ENVIA LOS DATOS A LA PAGINA "FACTURAR" ******************************/
/************************************************************************************************************************/
if($ls_operacion=="BUSCAR")
{

$ls_feccob_desde=$io_funcion->uf_convertirdatetobd($ls_feccob_desde);	
$ls_feccob_hasta=$io_funcion->uf_convertirdatetobd($ls_feccob_hasta);	
$ls_codtie=$_SESSION["ls_codtienda"];

if ($ls_feccob_desde=="" && $ls_feccob_hasta=="")
{
			$ls_cadena="SELECT co.numcob,co.feccob,co.estcob,co.codtiend,s.nomban,s.codban,o.numinst, o.ctaban, o.fecins
						FROM   sfc_cobrocartaorden co, scb_banco s, sfc_instpagocobcartaorden o
						WHERE  co.codemp='0001' 
						AND    co.codemp=s.codemp 
						AND    co.codemp=o.codemp 
						AND    o.codemp=s.codemp 
						AND    co.codban=s.codban 
						AND    co.codban=o.codban 
						AND    o.codban=s.codban 
						AND    co.numcob=o.numcob
						AND    co.codtiend=o.codtiend
						AND    co.codtiend='".$ls_codtie."' 
						AND    co.numcob like '".$ls_numcob."' 
						AND    s.codban  like '".$ls_codbanco."'
						GROUP BY co.numcob,co.feccob,co.estcob,co.codtiend,s.nomban,s.codban,o.numinst, o.ctaban, o.fecins 
						ORDER BY co.numcob";
			}
			else
			{
			 $ls_cadena="SELECT co.numcob,co.feccob,co.estcob,co.codtiend,s.nomban,s.codban,o.numinst, o.ctaban, o.fecins
				 		 FROM   sfc_cobrocartaorden co, scb_banco s, sfc_instpagocobcartaorden o
						 WHERE  co.codemp='0001' 
						 AND    co.codemp=s.codemp 
						 AND    co.codemp=o.codemp 
						 AND    o.codemp=s.codemp 
						 AND    co.codban=s.codban 
						 AND    co.codban=o.codban 
						 AND    o.codban=s.codban 
						 AND    co.numcob=o.numcob
						 AND    co.codtiend=o.codtiend
						 AND    co.codtiend='".$ls_codtie."' 
						 AND    co.numcob like '".$ls_numcob."' 
						 AND    s.codban  like '".$ls_codbanco."'
						 AND    (co.feccob>='".$ls_feccob_desde."' 
						 AND    co.feccob<='".$ls_feccob_hasta."') 
						 AND    co.numcob ilike '".$ls_numcob."'
						 GROUP BY co.numcob,co.feccob,co.estcob,co.codtiend,s.nomban,s.codban,o.numinst, o.ctaban, o.fecins 
						 ORDER BY co.numcob  ";
			}
			//print $ls_cadena."<br>";
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de cobranzas");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Cobro</font></td>";
					print "<td><font color=#FFFFFF>Banco Asociado</font></td>";
					print "<td><font color=#FFFFFF>Cuenta</font></td>";
					print "<td><font color=#FFFFFF>Nota de Debito</font></td>";
					print "<td><font color=#FFFFFF>Fecha  del cobro</font></td>";																
					$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("numcob");
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$numcob   = $io_data->getValue("numcob",$z);
						$feccob   = $io_data->getValue("feccob",$z);
						$feccob   = $io_funcion->uf_convertirfecmostrar($feccob);
						$estcob   = $io_data->getValue("estcob",$z);
						$fecins   = $io_data->getValue("fecins",$z);
						$fecins   = substr($fecins,8,2)."/".substr($fecins,5,2)."/".substr($fecins,0,4);
						$codban   = $io_data->getValue("codban",$z);
						$nomban   = $io_data->getValue("nomban",$z);
						$codtiend = $io_data->getValue("codtiend",$z);
						$numinst  = $io_data->getValue("numinst",$z);
						$ctaban   = $io_data->getValue("ctaban",$z);
						print "<td><a href=\"javascript: aceptar('$numcob','$feccob',
						'$estcob','$fecins','$codban','$nomban','$numinst','$codtiend','$ctaban');\">".$numcob."</a></td>";
						print "<td align=center>".$nomban."</td>";
						print "<td align=center>".$ctaban."</td>";
						print "<td align=center>".$numinst."</td>";
						print "<td align=center>".$feccob."</td>";						
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("¡No se han registrado cobros ! ");
				}
		}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
/********************************************* RUTINAS JAVASCRIPT **************************************************************/
/*******************************************************************************************************************************/
/*******************************************************************************************************************************/
  function aceptar(numcob,feccob,estcob,fecins,codban,nomban,numnot,codtien,ctaban)
  {
    opener.ue_cargarcobranza(numcob,feccob,estcob,fecins,codban,nomban,numnot,codtien,ctaban);
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_cobranzacarta.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
