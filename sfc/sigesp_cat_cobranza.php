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
	$ls_razcli="%".$_POST["razcli"]."%";
	$ls_cedcli="%".$_POST["cedcli"]."%";
	$ls_feccob_desde=$_POST["txtfeccob_desde"];
	$ls_feccob_hasta=$_POST["txtfeccob_hasta"];
	$ls_numcob="%".$_POST["txtnumcob"]."%";
        $ls_numfac = (trim($_POST["txtnumfac"])=="")? (trim($_POST["txtnumfac"])) : ("%".$_POST["txtnumfac"]."%");
        
        

}
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
else
{

	$ls_operacion="";
	$ls_razcli="";
	$ls_cedcli="";
	$ls_feccob_desde="";
	$ls_feccob_hasta="";
	$ls_numcob="";
        $ls_numfac="";
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
        <td width="67" height="25"><div align="right">Nombre</div></td>
        <td width="431"><div align="left">
          <input name="razcli" type="text" id="razcli"  size="60">
        </div></td>
      </tr>

	   <tr>
        <td width="67" height="25"><div align="right">C&eacute;dula/Rif</div></td>
        <td width="431"><div align="left">
          <input name="cedcli" type="text" id="cedcli"  maxlength="15" size="16">
        </div></td>
      </tr>

      <tr>
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
        <td>&nbsp;</td>
      </tr>
      <tr>
            
        <td><div align="right">No.Factura</div></td>
        <td><input name="txtnumfac" type="text" id="txtnumfac" value="<?php $ls_numfac ?>"  size="60"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?
/************************************************************************************************************************/
/******************   BUSCAR --> BUSCA LA FACTURA Y ENVIA LOS DATOS A LA PAGINA "FACTURAR" ******************************/
/************************************************************************************************************************/
if($ls_operacion=="BUSCAR")
{

$ls_feccob_desde=$io_funcion->uf_convertirdatetobd($ls_feccob_desde);
$ls_feccob_hasta=$io_funcion->uf_convertirdatetobd($ls_feccob_hasta);
if ($ls_feccob_desde=="" && $ls_feccob_hasta=="")
{
/*$ls_cadena="SELECT co.esppag,co.moncob,co.numcob,co.feccob,co.descob,co.estcob,c.codcli,c.cedcli,c.razcli " .
		"FROM sfc_cliente c,sfc_cobro co WHERE  c.codcli=co.codcli AND co.numcob LIKE '".$ls_numcob."' AND c.razcli ilike '".$ls_razcli."'  " .
		"AND c.cedcli ilike '".$ls_cedcli."' AND co.numcarta='0'; ";*/
    if ($ls_numfac =="") {
    $ls_cadena = "
             SELECT   
                 co.esppag
                ,co.moncob
                ,co.numcob
                ,co.feccob
                ,co.descob
                ,co.estcob
                ,co.cod_caja
                ,co.codciecaj
                ,c.codcli
                ,c.cedcli
                ,c.razcli 
        FROM 
            sfc_cliente c,sfc_cobro_cliente co
        WHERE 
                c.codcli=co.codcli 
               
                AND co.numcob LIKE '$ls_numcob'
                
                AND c.razcli ilike '$ls_razcli'
                AND c.cedcli ilike '$ls_cedcli' 
        ORDER by numcob;   
 ";
    }else {
        $ls_cadena = "
             SELECT   
                 co.esppag
                ,co.moncob
                ,co.numcob
                ,co.feccob
                ,co.descob
                ,co.estcob
                ,co.cod_caja
                ,co.codciecaj
                ,c.codcli
                ,c.cedcli
                ,c.razcli 
        FROM 
            sfc_cliente c,sfc_cobro_cliente co, sfc_dt_cobrocliente dt_cobcli
        WHERE 
                c.codcli=co.codcli 
                AND c.codcli=dt_cobcli.codcli 
                AND co.numcob = dt_cobcli.numcob
                AND co.numcob LIKE '$ls_numcob'
                AND dt_cobcli.numfac ilike '$ls_numfac'
                AND c.razcli ilike '$ls_razcli'
                AND c.cedcli ilike '$ls_cedcli' 
        ORDER by numcob;   
 ";
        
    }
 /*$ls_cadena="SELECT co.esppag,co.moncob,co.numcob,co.feccob,co.descob,co.estcob,co.cod_caja,co.codciecaj,c.codcli,c.cedcli,c.razcli " .
		"FROM sfc_cliente c,sfc_cobro_cliente co " .
		"WHERE c.codcli=co.codcli AND co.numcob LIKE '".$ls_numcob."' AND c.razcli ilike '".$ls_razcli."'  " .
		"AND c.cedcli ilike '".$ls_cedcli."' ".
		" ORDER by numcob; ";*/
}
else
{
 /*$ls_cadena="SELECT co.esppag,co.moncob,co.numcob,co.feccob,co.descob,co.estcob,c.codcli,c.cedcli,c.razcli " .
 		"FROM sfc_cliente c,sfc_cobro co WHERE  c.codcli=co.codcli AND (co.feccob>='".$ls_feccob_desde."' AND co.feccob<='".$ls_feccob_hasta."') " .
 		"AND co.numcob ilike '".$ls_numcob."' AND c.razcli ilike '".$ls_razcli."' AND c.cedcli ilike '".$ls_cedcli."' AND co.numcarta='0';";*/
    if ($ls_numfac =="") {
   $ls_cadena = "
              SELECT   
                 co.esppag
                ,co.moncob
                ,co.numcob
                ,co.feccob
                ,co.descob
                ,co.estcob
                ,co.cod_caja
                ,co.codciecaj
                ,c.codcli
                ,c.cedcli
                ,c.razcli 
        FROM 
            sfc_cliente c,sfc_cobro_cliente co
        WHERE 
                c.codcli=co.codcli 
                
                AND (co.feccob>='$ls_feccob_desde' AND co.feccob<='$ls_feccob_hasta')
                AND co.numcob LIKE '$ls_numcob'
                
                AND c.razcli ilike '$ls_razcli'
                AND c.cedcli ilike '$ls_cedcli' 
        ORDER by numcob;";
    }else {
        $ls_cadena = "
              SELECT   
                 co.esppag
                ,co.moncob
                ,co.numcob
                ,co.feccob
                ,co.descob
                ,co.estcob
                ,co.cod_caja
                ,co.codciecaj
                ,c.codcli
                ,c.cedcli
                ,c.razcli 
        FROM 
            sfc_cliente c,sfc_cobro_cliente co, sfc_dt_cobrocliente dt_cobcli
        WHERE 
                c.codcli=co.codcli 
                AND c.codcli=dt_cobcli.codcli 
                AND co.numcob = dt_cobcli.numcob
                AND (co.feccob>='$ls_feccob_desde' AND co.feccob<='$ls_feccob_hasta')
                AND co.numcob LIKE '$ls_numcob'
                AND dt_cobcli.numfac ilike '$ls_numfac'
                AND c.razcli ilike '$ls_razcli'
                AND c.cedcli ilike '$ls_cedcli' 
        ORDER by numcob;";
        
    }
 /* $ls_cadena="SELECT co.esppag,co.moncob,co.numcob,co.feccob,co.descob,co.estcob,co.cod_caja,co.codciecaj,c.codcli,c.cedcli,c.razcli " .
 		"FROM sfc_cliente c,sfc_cobro_cliente co WHERE  c.codcli=co.codcli AND (co.feccob>='".$ls_feccob_desde."' AND co.feccob<='".$ls_feccob_hasta."') " .
 		"AND co.numcob ilike '".$ls_numcob."' AND c.razcli ilike '".$ls_razcli."' AND c.cedcli ilike '".$ls_cedcli."' ".
		" ORDER by numcob;";*/
}
//print $ls_cadena;

			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros de cotizaciones...");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>No. Cobro</font></td>";
					print "<td><font color=#FFFFFF>Nombre cliente</font></td>";
					print "<td><font color=#FFFFFF>C&eacute;dula/Rif</font></td>";
					print "<td><font color=#FFFFFF>Fecha cobro</font></td>";
					print "<td><font color=#FFFFFF>Monto cobrado</font></td>";
					//print "<td><font color=#FFFFFF>Monto por pagar</font></td>";


					$ls_cotizacion=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$ls_cotizacion;
					$totrow=$io_data->getRowCount("codcli");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$numcob=$io_data->getValue("numcob",$z);
						$razcli=$io_data->getValue("razcli",$z);
						$feccob=$io_data->getValue("feccob",$z);
						$feccob=$io_funcion->uf_convertirfecmostrar($feccob);
						$moncob=$io_data->getValue("moncob",$z);

						$codcli=$io_data->getValue("codcli",$z);
						$cedcli=$io_data->getValue("cedcli",$z);
						//$tipcancel=$io_data->getValue("tipcancel",$z);
						//$numfac=$io_data->getValue("numfac",$z);
						$especial=$io_data->getValue("esppag",$z);
						$descob=$io_data->getValue("descob",$z);
						//$moncancel=$io_data->getValue("moncancel",$z);

						//$moncancel=number_format($moncancel,2,',','.');
						$moncob=number_format($moncob,2,',','.');
						$estcob=$io_data->getValue("estcob",$z);
						$ls_codcaja=$io_data->getValue("cod_caja",$z);
						$ls_codciecaj=$io_data->getValue("codciecaj",$z);
						print "<td><a href=\"javascript: aceptar('$numcob','$razcli','$feccob','$moncob','$codcli','$descob','$cedcli','$estcob','$especial','$ls_codcaja','$ls_codciecaj');\">".$numcob."</a></td>";
						print "<td align=center>".$razcli."</td>";
						print "<td align=center>".$cedcli."</td>";
						print "<td align=center>".$feccob."</td>";
						print "<td align=right>".$moncob."</td>";
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("No se han registrado cobros!");
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

  function aceptar(numcob,razcli,feccob,moncob,codcli,descob,cedcli,estcob,especial,caja,ciecaja)
  {
  	opener.ue_cargarcobranza(numcob,razcli,feccob,moncob,codcli,descob,cedcli,estcob,especial,caja,ciecaja);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_cobranza.php";
  f.submit();
  }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>

</html>
