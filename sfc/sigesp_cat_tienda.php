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
<title>Cat&aacute;logo de Tienda</title>
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
	$ls_dentie="%".$_POST["dentie"]."%";
	$ls_codest=$_POST["cmbestado"];
	$ls_codesta="%".$_POST["cmbestado"]."%";

}
else
{
	$ls_operacion="";
	$ls_dentie="";
	$ls_codest="";

}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidad Operativa de Suministro</td>
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
			///print $ls_sql;
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
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="dentie" type="text" id="dentie"  size="60">
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
	 $ls_cadena=" SELECT t.*, s.denominacion,u.denuniadm,spg.denominacion as denpre,sfc_tipo_unidadoperativasuministro.dentipundopesum  FROM sfc_tienda t," .
			" spi_cuentas s,spg_unidadadministrativa u,spg_cuentas spg,sfc_tipo_unidadoperativasuministro  WHERE" .
			" t.spi_cuenta=s.spi_cuenta AND t.codest ilike '".$ls_codesta."'" .
			"  AND t.dentie ilike '".$ls_dentie."' AND t.coduniadm=u.coduniadm AND t.spg_cuenta=spg.spg_cuenta ".
			" AND  t.codestpro1=spg.codestpro1 AND t.codestpro2=spg.codestpro2 AND t.codestpro3=spg.codestpro3 AND t.codestpro4=spg.codestpro4" .
			"   AND t.codestpro5=spg.codestpro5 AND t.codtipundopesum=sfc_tipo_unidadoperativasuministro.codtipundopesum ".
			" ORDER BY t.codtiend ASC ";
		
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td><a href=javascript:ue_ordenar('codtie','BUSCAR');><font color=#FFFFFF>C&oacute;digo</font></a></td>";
				print "<td><a href=javascript:ue_ordenar('dentie','BUSCAR');><font color=#FFFFFF>Raz&oacute;n Social</font></a></td>";
				print "</tr>";	
				$i=0;
				while($row=$io_sql->fetch_row($rs_datauni))
				{
						$i++;			
						print "<tr class=celdas-blancas>";
						$codtie=$row["codtiend"];
		                $nomtie=$row["dentie"];
						$dirtie=$row["dirtie"];
						$teltie=$row["teltie"];
						$riftie=$row["riftie"];
						$codpai=$row["codpai"];
						$codest=$row["codest"];
						$codmun=$row["codmun"];
						$codpar=$row["codpar"];
						$unidad=$row["coduniadm"];
						$denunidad=$row["denuniadm"];
						$item=$row["item"];
						$spi_cuenta=$row["spi_cuenta"];
						$denominacion=$row["denominacion"];
						$codestpro1=$row["codestpro1"];
						$codestpro2=$row["codestpro2"];
						$codestpro3=$row["codestpro3"];
						$codestpro4=$row["codestpro4"];
						$codestpro5=$row["codestpro5"];
						$cuentapre=$row["spg_cuenta"];
						$denpre=$row["denpre"];
						$codtipundopesum=$row["codtipundopesum"];
						$dentipundopesum=$row["dentipundopesum"];
						$facporvol=$row["facporvol"];
						$estatus=$row["estatus"];
				        print "<td><a href=\"javascript: aceptar('$codtie','$nomtie','$dirtie','$teltie','$riftie','$codpai','$codest','$codmun','$codpar','$item','$spi_cuenta','$denominacion','$unidad','$denunidad','$cuentapre','$denpre','$codestpro1','$codestpro2','$codestpro3','$codestpro4','$codestpro5','$facporvol','$codtipundopesum','$dentipundopesum','$estatus');\">".$codtie."</a></td>";
						print "<td align=left>".$nomtie."</td>";
						print "</tr>";					
				}
				if($i==0)
				{
					$io_msg->message("No se han registrado Tiendas");
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
  function aceptar(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,facporvol,codtipundopesum,dentipundopesum,estatus)
  {
    opener.ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar,items,spi_cuenta,denominacion,codunidad,denunidad,cuentapre,denpre,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,facporvol,codtipundopesum,dentipundopesum,estatus);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_tienda.php";
  f.submit();
  }

</script>
</html>
