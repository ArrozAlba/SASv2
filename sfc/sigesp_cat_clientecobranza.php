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
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="cod_pro";
	$ls_orden="ASC";


}
else
{

	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cliente</title>
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

$ls_codtienda=$_SESSION["ls_codtienda"];
//$ls_codtienda='0002';
//echo $ls_codtienda.' ls_codtienda<br>';

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_razcli="%".$_POST["razcli"]."%";
	$ls_codest=$_POST["cmbestado"];
	$ls_codesta="%".$_POST["cmbestado"]."%";
	$ls_codtie=$_POST["txtcodtie"];
	$ls_codtien="%".$_POST["txtcodtie"]."%";
	$ls_destienda=$_POST["txtdestienda"];

}
else
{
	$ls_operacion="";
	$ls_razcli="";
	$ls_codest="";
	$ls_codesta="";
	$ls_codtie="";
	$ls_codtien="";
	$ls_destienda="";
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Clientes </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td width="67" height="30"><div align="right">Estado</div></td>
        <td width="431"><div align="left"><span class="style6">
          <?Php

		    $ls_sql="SELECT codest ,desest
                       FROM sigesp_estados
                      WHERE codpai='058' ORDER BY codest ASC";

			$lb_valest=$io_utilidad->uf_datacombo($ls_sql,&$la_estado);


			if($lb_valest)
			 {
			   $io_datastore->data=$la_estado;
			   $li_totalfilas=$io_datastore->getRowCount("codest");
			 }
			 else
			   $li_totalfilas=0;

		  ?>
          <select name="cmbestado" size="1" id="cmbestado">
            <option value="">Seleccione...</option>
            <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codest",$li_i);
					 $ls_desest=$io_datastore->getValue("desest",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_desest</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_desest</option>";
					 }
					}
	        ?>
          </select>
        </span></div></td>
      </tr>
      <tr>
        <td height="30"><div align="right">Tienda</div></td>
        <td>
        	<input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="30" maxlength="50">
        	<a href="javascript:ue_buscartienda();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a>
        	<input name="txtcodtie" type="hidden" id="txtcodtie" value="<? print $ls_codtie?>">
          <?Php

		    /*$ls_sql="SELECT codtiend ,dentie
                       FROM sfc_tienda
                      ORDER BY codtiend ASC";

			$lb_valtie=$io_utilidad->uf_datacombo($ls_sql,&$la_tienda);


			if($lb_valtie)
			 {
			   $io_datastore->data=$la_tienda;
			   $li_totalfilas=$io_datastore->getRowCount("codtiend");
			 }
			 else
			   $li_totalfilas=0;*/

		  ?>
          <!-- select name="cmbtienda" size="1" id="cmbtienda" >
            <option value="">Seleccione...</option-->
            <?Php
				/*
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codtiend",$li_i);
					 $ls_dentie=$io_datastore->getValue("dentie",$li_i);
					 if ($ls_codigo==$ls_codtie)
					 {
						  print "<option value='$ls_codigo' selected>$ls_dentie</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_dentie</option>";
					 }
					}
				*/
	        ?>
          <!-- /select -->
          </td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="razcli" type="text" id="razcli"  size="60">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?


if($ls_operacion=="BUSCAR")
{
	/*if($ls_codtienda != '0001'){
		$ls_cadena=" SELECT c.codcli,c.razcli,c.cedcli FROM sfc_cliente c,sfc_nota n WHERE c.codcli=n.codcli AND n.tipnot='CXC' AND n.estnota='P' " .
 				"AND n.numnot=n.nro_documento AND c.codest ilike '".$ls_codesta."'  AND  c.razcli ilike '".$ls_razcli."' " .
 				"AND n.codtiend='".$ls_codtiend."' GROUP BY c.codcli,c.razcli,c.cedcli";
	}else{
		$ls_cadena=" SELECT c.codcli,c.razcli,c.cedcli FROM sfc_cliente c,sfc_nota n WHERE c.codcli=n.codcli AND n.tipnot='CXC' AND n.estnota='P' " .
 				"AND n.numnot=n.nro_documento AND c.codest ilike '".$ls_codesta."'  AND  c.razcli ilike '".$ls_razcli."' " .
 				"AND n.codtiend='".$ls_codtienda."' GROUP BY c.codcli,c.razcli,c.cedcli";
	}*/

	/*$ls_cadena=" SELECT c.codcli,c.razcli,c.cedcli FROM sfc_cliente c,sfc_nota n WHERE c.codcli=n.codcli AND n.tipnot='CXC' AND n.estnota='P' " .
 				"AND n.numnot=n.nro_documento AND c.codest ilike '".$ls_codesta."'  AND  c.razcli ilike '".$ls_razcli."' " .
 				"AND n.codtiend ilike '".$ls_codtien."' GROUP BY c.codcli,c.razcli,c.cedcli";*/
        $ls_cadena=" SELECT c.codcli,c.razcli,c.cedcli FROM sfc_cliente c,sfc_nota n WHERE c.codcli=n.codcli AND n.tipnot='CXC' AND n.estnota='P' " .
 				"AND n.numnot=n.nro_documento AND c.codest ilike '".$ls_codesta."'  AND  c.razcli ilike '".$ls_razcli."' " .
 				"AND n.codtiend = '$ls_codtienda' GROUP BY c.codcli,c.razcli,c.cedcli";
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
			print "<td><a href=javascript:ue_ordenar('codtie','BUSCAR');><font color=#FFFFFF>C�digo</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('dentie','BUSCAR');><font color=#FFFFFF>Razon Social</font></a></td>";
			$la_tienda=$io_sql->obtener_datos($rs_datauni);
			$io_data->data=$la_tienda;
			$totrow=$io_data->getRowCount("codcli");

			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$codcli=$io_data->getValue("codcli",$z);
				$cedcli=$io_data->getValue("cedcli",$z);
				$nomcli=$io_data->getValue("razcli",$z);
				$dircli=$io_data->getValue("dircli",$z);
				$telcli=$io_data->getValue("telcli",$z);
				$celcli=$io_data->getValue("celcli",$z);
				$codpai=$io_data->getValue("codpai",$z);
				$codest=$io_data->getValue("codest",$z);
				$codmun=$io_data->getValue("codmun",$z);
				$codpar=$io_data->getValue("codpar",$z);
				$productor=$io_data->getValue("productor",$z);
		        print "<td><a href=\"javascript: aceptar('$codcli','$nomcli','$dircli','$telcli','$celcli','$codpai','$codest','$codmun','$codpar','$productor','$cedcli');\">".$cedcli."</a></td>";
				print "<td align=left>".$nomcli."</td>";
				print "</tr>";
			}
		}
		else
		{
			$io_msg->message("No se han registrado Clientes con Facturas por Cobrar");
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

//////////////////////////////////////////////////////////////////////////////
function aceptar(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,producto,cedcli)
{
	opener.ue_cargarcliente(codcli,nomcli,dircli,telcli,celcli,codpai,codest,codmun,codpar,producto,cedcli);
	close();
}

/////////////////////////////////////////////////////////////////////////////
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_cat_clientecobranza.php";
	f.submit();
}

/////////////////////////////////////////////////////////////////////////////
function ue_buscartienda()
{
    f=document.form1;
	f.operacion.value="";
	pagina="sigesp_cat_tienda.php" ;
	popupWin(pagina,"catalogo_tiendas",600,250);
}

/////////////////////////////////////////////////////////////////////////////
function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5)
{
	f=document.form1;
	f.txtcodtie.value=codtie;
	f.txtdestienda.value=nomtie;
}

</script>
</html>
