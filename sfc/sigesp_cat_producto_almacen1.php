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
<title>Cat&aacute;logo de Conceptos de Facturacion</title>
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
/************************************************************************************************************************/
/********************************   LIBRERIAS   *************************************************************************/
/************************************************************************************************************************/
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
$io_data2=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];
/**************************************************************************************************************************/
/**************************    SUBMIT   ***********************************************************************************/
/**************************************************************************************************************************/
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_denpro="%".$_POST["denpro"]."%";
	$ls_codclas="%".$_POST["cmbclasificacion"]."%";
	$ls_tipprod="%".$_POST["cmbtippro"]."%";
	$ls_codalm="%".$_POST["cmb_almacen"]."%";
	
	
}
else
/************************************************************************************************************************/
/***************************   NO SUBMIT ********************************************************************************/
/************************************************************************************************************************/
{
	$ls_operacion="";
	$ls_denpro="";
	$ls_codclas="";
	$ls_tipprod="";
	$ls_codalm="";
	
}

/************************************************************************************************************************/
/***************************   TABLAS DREAMWEAVER ***********************************************************************/
/************************************************************************************************************************/
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
	<input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalma ?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Conceptos/Almac&eacute;n </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      
      <tr>
        <td width="102" height="30"><div align="right">Clasificacion</div></td>
        <td width="396"><div align="left"><span class="style6">
		
		<?Php
/************************************************************************************************************************/
/********************************************  COMBO CLASIFICACION  *****************************************************/		  
/************************************************************************************************************************/
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
						 if ($ls_codigo==$ls_codcla)
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
<?PHP 
/************************************************************************************************************************/
/********************************  COMBO TIPO: SERVICIO Y BIEN  *********************************************************/	
/************************************************************************************************************************/
?>
        <select name="cmbtippro" size="1" id="cmbtippro"  >
         
          
          		<?php 
		  		if 	($ls_tippro=="B")
		         { 
				 ?>
				  <option value="null" >Selecione...</option>
				 <option value="B" selected="selected">Bien</option>
			     <option value="S">Servicio</option>
				 <?php
				 $ls_tipprod="B"; 
				 }
				 elseif 	($ls_tippro=="S")
				  {
				  ?>
				 <option value="null">Selecione...</option>
				 <option value="B"  >Bien</option>
			     <option value="S"  selected="selected">Servicio</option>
				  <?php
				  $ls_tipprod="S";
				  }
          		  else
				  {
				  ?>
				 <option value="null" selected="selected">Selecione...</option>
				 <option value="B"  >Bien</option>
			     <option value="S" >Servicio</option>
				  <?php
				  $ls_tipprod="%%";
				  }?>
		<!--  <option value="B" selected="selected">Bien</option>
		  		  
          <option value="S" >Servicio</option> -->
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
  
<?php
if($ls_operacion=="BUSCAR")
{
$ls_cadena="SELECT al.nomfisalm,aa.codalm,p.*,um.*,c.den_sub,a.denart,a.ultcosart,ci.denominacion as denspi,aa.existencia FROM sfc_producto p,sfc_subclasificacion c, sim_articulo a,spi_cuentas ci,sim_unidadmedida um,sim_articuloalmacen aa,sim_almacen al WHERE p.codemp='".$ls_codemp."' and p.codemp=a.codemp and p.codemp=ci.codemp and p.cod_sub=c.cod_sub and p.codart=a.codart and  p.spi_cuenta=ci.spi_cuenta and p.denpro ilike '".$ls_denpro."'  AND p.cod_sub ilike '".$ls_codclas."' AND p.tippro ilike '".$ls_tipprod."' and a.codunimed=um.codunimed AND aa.codart=a.codart AND p.codpro=aa.codart And al.codalm=aa.codalm AND a.codart=aa.codart";
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
					print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>Código</font></td>";
					print "<td><font color=#FFFFFF>Descripcion</font></td>";
					print "<td><font color=#FFFFFF>Existencia</font></td>";
					print "<td><font color=#FFFFFF>Unidad de Medida</font></td>";
					print "<td><font color=#FFFFFF>Unidad</font></td>";
					print "<td><font color=#FFFFFF>Precio</font></td>";
					print "<td><font color=#FFFFFF>Precio+iva</font></td>";
					print "<td><font color=#FFFFFF>Almacen</font></td>";
					$la_producto=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_producto;
					$totrow=$io_data->getRowCount("codpro");
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codpro=$io_data->getValue("codpro",$z);
						$nomalm=$io_data->getValue("nomfisalm",$z);
		                $denpro=$io_data->getValue("denpro",$z);
						$tippro=$io_data->getValue("tippro",$z);
						$preven=$funsob->uf_convertir_numerocadena($io_data->getValue("preven",$z));
						$codcar=$io_data->getValue("codcar",$z);						
						$codcla=$io_data->getValue("codcla",$z);
						$dencla=$io_data->getValue("dencla",$z);
						$codart=$io_data->getValue("codart",$z);
						$denart=$io_data->getValue("denart",$z);						
						$ld_exi=$io_data->getValue("existencia",$z);
						$existe=$funsob->uf_convertir_numerocadena($io_data->getValue("exiart",$z));
						$ultcosart=$funsob->uf_convertir_numerocadena($io_data->getValue("ultcosart",$z));
						$spi_cuenta=$io_data->getValue("spi_cuenta",$z);
						$denspi=$io_data->getValue("denspi",$z);
						$sc_cuenta=$io_data->getValue("sc_cuenta",$z);
						$denscg=$io_data->getValue("denscg",$z);
						$denunidad=$io_data->getValue("denunimed",$z);
						$unidad=$io_data->getValue("unidad",$z);
						$codalmacen=$io_data->getValue("codalm",$z);
						/************************* AGREGAR CODIGO  OTROS CAMPOS  *******************/
						$moncar=$funsob->uf_convertir_numerocadena($io_data->getValue("moncar",$z));
						$porgan=$funsob->uf_convertir_numerocadena($io_data->getValue("porgan",$z));
						$tipcos=$io_data->getValue("tipcos",$z);					   
						$preuni=$funsob->uf_convertir_numerocadena($io_data->getValue("preuni",$z));
						$porcar=$funsob->uf_convertir_numerocadena($io_data->getValue("porcar",$z));
						$preven1=$funsob->uf_convertir_numerocadena($io_data->getValue("preven",$z));
						$preven2=$funsob->uf_convertir_numerocadena($io_data->getValue("preven2",$z));
						$preven=$funsob->uf_convertir_numerocadena($io_data->getValue("preven",$z));
						$preven3=$funsob->uf_convertir_numerocadena($io_data->getValue("preven3",$z));
						$cosfle=$funsob->uf_convertir_numerocadena($io_data->getValue("cosfle",$z));
						$cosproart=$funsob->uf_convertir_numerocadena($io_data->getValue("cosproart",$z));
						/***************************************************************************/
						$ls_cadena2="SELECT i.dencar,i.porcar FROM  sigesp_cargos i WHERE i.codemp='0001' AND i.codcar='".$codcar."'";
						$rs_datauni2=$io_sql->select($ls_cadena2);
						$la_producto2=$io_sql->obtener_datos($rs_datauni2);
						$io_data2->data=$la_producto2;
						$totrow2=$io_data2->getRowCount("codcar");
						$porcar=$io_data2->getValue("porcar",1);	
						$dencar=$io_data2->getValue("dencar",1);					
						$porcar=$funsob->uf_convertir_numerocadena($io_data2->getValue("porcar",1));	
						$preiva=($io_data->getValue("preven",$z)*$porcar/100)+$io_data->getValue("preven",$z);
											
					   print "<td><a href=\"javascript: aceptar('$codpro','$denpro','$tippro','$preven','$codcar','$dencar','$codcla','$dencla','$codart','$denart','$ultcosart','$spi_cuenta','$denspi','$sc_cuenta','$denscg','$moncar','$porgan','$tipcos','$preuni','$preven1','$preven2','$preven3','$cosfle','$cosproart','$porcar','$codalmacen','$ld_exi');\">".$codpro."</a></td>";
						print "<td align=left>".$denpro."</td>";
						print "<td align=left>".$ld_exi."</td>";
						print "<td align=left>".$denunidad."</td>";
						print "<td align=left>".$unidad."</td>";
						print "<td align=left>".$preven."</td>";
						print "<td align=left>".$funsob->uf_convertir_numerocadena($preiva)."</td>";
						print "<td align=left>".$nomalm."</td>";
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
/************************************************************************************************************************/	
/******************************************  FUNCIONES JAVASCRIPT  ******************************************************/
/************************************************************************************************************************/	
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function actualizar_combo_almacen()
{
     f=document.form1;
	  f.operacion.value="";
	 f.submit();
}
  function actualizar_servicio()
  {
     f=document.form1;
	 
	  f.operacion.value="";
	 /*f.hidcodforpag.value=f.comboforma.value;*/
	 /*f.hiddenforago.value=f.comboforma.value;*/
	 f.submit();
  }
  
  
  function aceptar(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cosproart,porcar,codalm,exi)
  {
    opener.ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cosproart,porcar,codalm,exi);
	close();
  }
  
function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_producto_almacen.php";
  
  f.submit();
  }
 
</script>
</html>
