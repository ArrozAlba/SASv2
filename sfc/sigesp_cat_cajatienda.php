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
<title>Cat&aacute;logo de Caja</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
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
<?

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
	$ls_desccaja="%".$_POST["desccaja"]."%";
	$ls_codtiend=$_POST["txtcodtie"];
	$ls_destienda=$_POST["txtdestienda"];
	//print $ls_codtiend;

}
else
{
	$ls_operacion="";
	$ls_desccaja="";
	$ls_codtiend="";

}

if($ls_codtiend == ''){
	if(array_key_exists("codtienda",$_REQUEST))
	{
		$tienda=$_REQUEST["codtienda"];
		if($tienda != ''){
			$ls_codtiend=$_REQUEST["codtienda"];
		}
	}else{
		$tienda='';
	}
}else{
	$tienda=$ls_codtiend;
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
    <td width="334" ><input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtiend?>" size="5" maxlength="4"></td>


</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Caja </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

	 <?php
	 	if($tienda==''){
	 ?>
       <tr>
        <td width="101" height="30"><div align="right">Unidad Operativa de Suministro</div></td>

        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>

      <?php } ?>


      <tr>
        <td width="101"><div align="right">Nombre</div></td>
        <td width="397"><div align="left">
          <input name="desccaja" type="text" id="desccaja"  size="60" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_buscarcaja();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?


if($ls_operacion=="BUSCAR")
{
 //print $ls_codtiend;

 if ($ls_codtiend!="")
  {	$ls_cadena=" SELECT * ".
			" FROM sfc_caja ".
			" WHERE descripcion_caja ilike '".$ls_desccaja."' and codtiend='".$ls_codtiend."' ";
  }
   else
   {
   	$ls_cadena=" SELECT * ".
			" FROM sfc_caja ".
			" WHERE descripcion_caja ilike '".$ls_desccaja."' or codtiend='".$ls_codtiend."' ";

   }

   //print $ls_cadena;
	$rs_datauni=$io_sql->select($ls_cadena);
	if($rs_datauni==false&&($io_sql->message!=""))
	{
		$io_msg->message("No hay registros");
	}
	else
	{
		if($row=$io_sql->fetch_row($rs_datauni))
		{
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td><font color=#FFFFFF>C&oacute;digo</font></td>";
			print "<td><font color=#FFFFFF>Nombre</font></td>";
			$la_caja=$io_sql->obtener_datos($rs_datauni);
			$io_data->data=$la_caja;
			$totrow=$io_data->getRowCount("cod_caja");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$codtienda=$io_data->getValue("codtiend",$z);
				$codcaja=$io_data->getValue("cod_caja",$z);
                $desccaja=$io_data->getValue("descripcion_caja",$z);
				$precot=$io_data->getValue("precot",$z);
				$prefac=$io_data->getValue("prefac",$z);
				$predev=$io_data->getValue("predev",$z);
				$preped=$io_data->getValue("preped",$z);
				$sercot=$io_data->getValue("sercot",$z);
				$serfac=$io_data->getValue("serfac",$z);
				$serdev=$io_data->getValue("serdev",$z);
				$serped=$io_data->getValue("serped",$z);
				$sernot=$io_data->getValue("sernot",$z);
				$sercon=$io_data->getValue("sercon",$z);
				$formalibre=$io_data->getValue("formalibre",$z);
				$precob=$io_data->getValue("precob",$z);
				$sercob=$io_data->getValue("sercob",$z);


				print "<td><a href=\"javascript: aceptar('$codtienda','$ls_destienda','$codcaja','$desccaja','$precot','$prefac','$predev','$preped','$sercot','$serfac','$serdev','$serped','$sernot','$sercon','$formalibre','$precob','$sercob');\">".$codcaja."</a></td>";
				print "<td align=left>".$desccaja."</td>";
				print "</tr>";
			}
		}
		else
		{
			$io_msg->message("No se han registrado Cajas");
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

  function aceptar(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
  {
  	opener.ue_cargarcaja(codtienda,ls_destienda,codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob);
	close();

  }

  function ue_buscarcaja()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_cajatienda.php";
	  f.submit();
  }


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
</html>
