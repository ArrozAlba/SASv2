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
<title>Cat&aacute;logo de Rubros Agrícolas</title>
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
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$funsob =   new sigesp_sob_c_funciones_sob();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_denrubro="%".$_POST["denrubro"]."%";
	}
else
{
	$ls_operacion="";
	$ls_denrubro="";
	}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
	
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Rubros Agrícolas</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
     <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">
          <input name="denrubro" type="text" id="denrubro"  size="60">
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
	$ls_cadena="SELECT cla.id_clasificacion,cla.cod_clasificacion,cla.denominacion as dencla,cla.prod_estimada,r.id_rubro,r.denominacion as denrubro,
				r.id_renglon,re.denominacion as denrenglon,re.id_tipoexplotacion,ci.id_ciclo,ci.denominacion as denomina_rubro,substr(ci.denominacion,0,4) as denominacion_rubro,r.id_ciclo 
				FROM sfc_clasificacionrubro cla,sfc_rubro r,sfc_ciclo ci,
				sfc_renglon re 
				WHERE cla.id_rubro=r.id_rubro 
				AND r.id_renglon=re.id_renglon 
				AND ci.id_ciclo=r.id_ciclo 
				AND CAST(re.id_tipoexplotacion as integer)='1'
				AND cla.denominacion ilike '".$ls_denrubro."' 
				ORDER BY cla.id_clasificacion";
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
					print "<td><a href=javascript:ue_ordenar('codtipo','BUSCAR');><font color=#FFFFFF>Código</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('denrubro','BUSCAR');><font color=#FFFFFF>Denominaci&oacute;n</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('desc_tipo','BUSCAR');><font color=#FFFFFF>Tipo de Producci&oacute;n</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('prod_estimada','BUSCAR');><font color=#FFFFFF>Producci&oacute;n Estimada</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('denomina_rubro','BUSCAR');><font color=#FFFFFF>Ciclo</font></a></td>";
					$la_tipo=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_tipo;
					$totrow=$io_data->getRowCount("id_clasificacion");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codrubro    = $io_data->getValue("id_clasificacion",$z);
						$codclarubro = $io_data->getValue("cod_clasificacion",$z);
		                $nomrubro    = $io_data->getValue("dencla",$z);	
						$cod_tipo    = $io_data->getValue("id_renglon",$z);
						$descripcion = $io_data->getValue("denrenglon",$z);
						$cod_tipoprod= $io_data->getValue("id_rubro",$z);
						$tipo_prod   = $io_data->getValue("denrubro",$z);
						$deno_rubro  = $io_data->getValue("denominacion_rubro",$z);
						$deno_rubro1 = $io_data->getValue("denomina_rubro",$z);
						//print $deno_rubro;
						//$prod_est=$io_data->getValue("prod_estimada",$z);
						$prod_est=$funsob->uf_convertir_numerocadena($io_data->getValue("prod_estimada",$z));
						print "<td><a href=\"javascript: aceptar('$codrubro','$nomrubro','$cod_tipo','$cod_tipoprod','$tipo_prod','$descripcion','$prod_est','$deno_rubro','$codclarubro');\">".$codrubro."</a></td>";
						print "<td align=left>".$nomrubro."</td>";
						
						print "<td align=left>".$tipo_prod."</td>";	
						print "<td align=right>".$prod_est."</td>";	
						print "<td align=right>".$deno_rubro1."</td>";						
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han registrado Rubros");
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
  function aceptar(codrubro,nomrubro,cod_tipo,cod_tipoprod,tipo_prod,descripcion,prod_est,deno_rubro,codclarubro)
  {  	
    
	opener.ue_cargar_rubroagr(codrubro,nomrubro,cod_tipo,cod_tipoprod,tipo_prod,descripcion,prod_est,deno_rubro,codclarubro);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_rubroagri.php";
  f.submit();
  }
 
</script>
</html>
