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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Conceptos de Facturaci&oacute;n</title>
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

<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sob_c_funciones_sob.php");
$funsob =   new sigesp_sob_c_funciones_sob();
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
	$ls_denpro="%".$_POST["denpro"]."%";
	$ls_codcla=$_POST["cmbclasificacion"];
	$ls_codclas="%".$_POST["cmbclasificacion"]."%";
	$ls_tippro=$_POST["cmbtippro"];
	$ls_tipprod="%".$_POST["cmbtippro"]."%";
	
	
	
}
else
{
	$ls_operacion="";
	$ls_denpro="";
	$ls_codcla="";
	$ls_codclas="";
	$ls_tippro="";
	$ls_tipprod="";
	
	
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
	
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Conceptos de Facturacion </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
      <tr>
        <td width="102" height="30"><div align="right">Clasificacion</div></td>
        <td width="396"><div align="left"><span class="style6">
          <?Php
	       
		    $ls_sql="SELECT codcla ,dencla 
                       FROM sfc_clasificacion
                       ORDER BY codcla ASC";
					
			$lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_clasif);
			
				    
			if($lb_valido)
			 {
			   $io_datastore->data=$la_clasif;
			   $li_totalfilas=$io_datastore->getRowCount("codcla");
			 }
			 else
			   $li_totalfilas=0;
					 
		  ?>
          <select name="cmbclasificacion" size="1" id="cmbclasificacion">
            <option value="">Seleccione...</option>
            <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codcla",$li_i);
					 $ls_dencla=$io_datastore->getValue("dencla",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_dencla</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo' >$ls_dencla</option>";
					 }
					} 
	        ?>
          </select>
        </span></div></td>
      </tr>
      <tr>
        <td height="31"><div align="right">Tipo</div></td>
        <td><label>
          <select name="cmbtippro" size="1" id="cmbtippro">
            <option value="B" selected>Bien</option>
            <option value="S">Servicio</option>
        </select>
        </label></td>
      </tr>
      <tr>
        <td height="32"><div align="right">Descripcion</div></td>
        <td><div align="left">
          <input name="denpro" type="text" id="denpro"  size="60">
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
 $ls_cadena=" SELECT p.*,c.dencla,a.denart,a.ultcosart,i.dencar,ci.denominacion as denspi,cc.denominacion as denscg".
			" FROM sfc_producto p,sfc_clasificacion c, sim_articulo a, sigesp_cargos i, spi_cuentas ci, scg_cuentas cc ".
			" WHERE p.codemp='".$ls_codemp."' and p.codemp=a.codemp and p.codemp=i.codemp and p.codemp=ci.codemp and p.codemp=cc.codemp and p.codcla=c.codcla and p.codart=a.codart and p.codcar=i.codcar and p.spi_cuenta=ci.spi_cuenta and p.sc_cuenta=cc.sc_cuenta and denpro ilike '".$ls_denpro."'  AND p.codcla like '".$ls_codclas."' AND tippro like '".$ls_tipprod."' ";
			
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
					print "<td><font color=#FFFFFF>Código</font></td>";
					print "<td><font color=#FFFFFF>Descripcion</font></td>";
					print "<td><font color=#FFFFFF>Clasificacion</font></td>";
					$la_producto=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_producto;
					$totrow=$io_data->getRowCount("codpro");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codpro=$io_data->getValue("codpro",$z);
		                $denpro=$io_data->getValue("denpro",$z);
						$tippro=$io_data->getValue("tippro",$z);
						$preven=$funsob->uf_convertir_numerocadena($io_data->getValue("preven",$z));
						$codcar=$io_data->getValue("codcar",$z);
						$dencar=$io_data->getValue("dencar",$z);
						$codcla=$io_data->getValue("codcla",$z);
						$dencla=$io_data->getValue("dencla",$z);
						$codart=$io_data->getValue("codart",$z);
						$denart=$io_data->getValue("denart",$z);
						$ultcosart=$funsob->uf_convertir_numerocadena($io_data->getValue("ultcosart",$z));
						$spi_cuenta=$io_data->getValue("spi_cuenta",$z);
						$denspi=$io_data->getValue("denspi",$z);
						$sc_cuenta=$io_data->getValue("sc_cuenta",$z);
						$denscg=$io_data->getValue("denscg",$z);
					   print "<td><a href=\"javascript: aceptar('$codpro','$denpro','$tippro','$preven','$codcar','$dencar','$codcla','$dencla','$codart','$denart','$ultcosart','$spi_cuenta','$denspi','$sc_cuenta','$denscg');\">".$codpro."</a></td>";
						print "<td align=left>".$denpro."</td>";
						print "<td align=left>".$dencla."</td>";
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han registrado Productos");
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
  function aceptar(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg)
  {
    opener.ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_producto.php";
  f.submit();
  }
 
</script>
</html>
