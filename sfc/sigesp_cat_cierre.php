<?
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
<title>Cat&aacute;logo de Cierres</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
	$ls_fecdes=$_POST["txtfecdes"];
	$ls_fechas=$_POST["txtfechas"];
	$ls_codtiend=$_POST["txtcodtie"];
	$ls_destienda=$_POST["txtdestienda"];
}
else
{
	$ls_operacion="";
	$ls_fecdes=date('d/m/Y');
	$ls_fechas=date('d/m/Y');
	$ls_codtiend="";
	$ls_destienda="";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cierres</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?
      	if ($ls_codtie!='0001')
      {

      ?>
      <tr>
        <input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtiend?>" size="5" maxlength="4">

        <td width="89" height="30"><div align="right">Unidad Operativa de Suministro</div></td>

        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>
		<?
      }
		?>
      <tr>
        <td width="89"><div align="right">Fecha(Desde)</div></td>
        <td width="257"><div align="left">
          <input name="txtfecdes" type="text" id="txtfecdes"  style="text-align:left"  size="11" maxlength="10"  datepicker="true"  readonly="true" value="<? print $ls_fecdes ?>">
        </div></td>
        <td width="70"><div align="right">Fecha(Hasta)</div></td>
        <td width="82">
        <input name="txtfechas" type="text" id="txtfechas"  style="text-align:left" size="11" maxlength="10"  datepicker="true"  readonly="true" value="<? print $ls_fechas ?>">        </td>
      </tr>
      <tr>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
       </tr>
    </table>
<br>
<?php
if($ls_operacion=="BUSCAR")
{

 $ls_fecdes=$io_funcion->uf_convertirdatetobd($ls_fecdes);
 $ls_fechas=$io_funcion->uf_convertirdatetobd($ls_fechas);

 if ($ls_codtie=='0001')
  {
		$ls_cadena=" SELECT cie.codciecaj,cie.codusu,cie.feccie,caj.nomusu,caj.dencaja,caj.cod_caja".
            " FROM   sfc_cierrecaja cie, sfc_cajero caj".
			" WHERE  cie.codemp='".$ls_codemp."'" .
			" AND    cie.codtiend='".$ls_codtie."' " .
			" AND    cie.codtiend=caj.codtiend" .
			" AND    cie.codemp=caj.codemp" .
			" AND    cie.codusu=caj.codusu".
			" AND    (DATE(cie.feccie) BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."')";
  }
  else
  {
  	$ls_cadena=" SELECT cie.codciecaj,cie.codusu,cie.feccie,caj.nomusu,caj.dencaja,caj.cod_caja".
            " FROM   sfc_cierrecaja cie, sfc_cajero caj".
			" WHERE  cie.codemp='".$ls_codemp."'" .
			" AND    cie.codtiend='".$ls_codtiend."' " .
			" AND    cie.codtiend=caj.codtiend" .
			" AND    cie.codemp=caj.codemp" .
			" AND    cie.codusu=caj.codusu".
			" AND    (DATE(cie.feccie) BETWEEN '".$ls_fecdes."' AND '".$ls_fechas."') ";
  }
//print $ls_cadena;
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
				//print $ls_cadena;
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>Usuario</font></td>";
					print "<td><font color=#FFFFFF>Cierre</font></td>";
					print "<td><font color=#FFFFFF>Fecha</font></td>";
					$la_caja=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_caja;
					$totrow=$io_data->getRowCount("codciecaj");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codcie=$io_data->getValue("codciecaj",$z);
		                $codusu=$io_data->getValue("codusu",$z);
						$feccie=$io_data->getValue("feccie",$z);
						$nomusu=$io_data->getValue("nomusu",$z);
						$codcaja=$io_data->getValue("cod_caja",$z);
						$dencaja=$io_data->getValue("dencaja",$z);
					    $feccie=$io_funcion->uf_convertirfecmostrar($feccie)  ;// =substr($ls_feccie,6,4)."-".substr($ls_feccie,3,2)."-".substr($ls_feccie,0,2);
						print "<td><a href=\"javascript:aceptar('$codcie','$codusu','$feccie','$nomusu','$codcaja','$dencaja');\">".$codusu."</a></td>";
						print "<td align=left>".$codcie."</td>";
						print "<td align=left>".$feccie."</td>";
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("No se han realizado Cierres");
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

  function aceptar(codcie,codusu,feccie,nomusu,codcaja,dencaja)
  {
    opener.ue_cargarcierre(codcie,codusu,feccie,nomusu,codcaja,dencaja);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_cierre.php";
  f.submit();
  }
/***********************************************************************************************************************************/
  function ue_buscartienda()
		{
            f=document.form1;

			f.operacion.value="";
			pagina="sigesp_cat_tienda.php";
			popupWin(pagina,"catalogo_tiendas",600,250);




		}

/***********************************************************************************************************************************/

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentaiva,deniva)
		{
			f=document.form1;

			f.txtcodtie.value=codtie;
            f.txtdestienda.value=nomtie;


		}



</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
