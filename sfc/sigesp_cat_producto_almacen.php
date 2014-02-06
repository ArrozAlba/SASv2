<?
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
session_start();
ini_set('max_execution_time',9000); //tiempo limite de ejecucion de un escript en segundos.
#
ini_set("memory_limit","1500M"); // aumentamos la memoria a 1,5GB
#
ini_set("buffering ","0"); // aumentamos la memoria a 1,5GB
/*ob_start();
ob_implicit_flush(true);*/
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
$io_alm=new class_datastore();
$io_datapro=new class_datastore();
$io_data2=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];
$ls_codtie=  $_SESSION["ls_codtienda"];
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
	$ls_codtiend=$_POST["txtcodtie"];
	$ls_destienda=$_POST["txtdestienda"];


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
	$ls_codtiend="";
	$ls_destienda="";

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

<!--	 <tr>
        <input name="txtcodtie" type="text" id="txtcodtie" value="<? print $ls_codtiend?>" size="5" maxlength="4">

        <td width="67" height="30"><div align="right">Tienda</div></td>

        <td> <input name="txtdestienda" type="text" id="txtdestienda"  value="<? print $ls_destienda?>" size="50" maxlength="50"><a href="javascript:ue_buscartienda();">
        <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></td>

      </tr>
-->
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

		
 $ls_sql="select codalm    ".
         "from sim_almacen ".
         "where substr(codalm,7,4) ilike '%".$ls_codtie."%'";
  
  $rs_alm=$io_sql->select($ls_sql);
  $data=$rs_alm;
  if($row=$io_sql->fetch_row($rs_alm))
		{
			$ls_codalm = $row["codalm"];
		}   
		else
		{
			$io_msg->message("No hay almacen asociado a la Unidad de propiedad social");
		}
 $ls_cadena="SELECT al.nomfisalm,aa.codalm,al.desalm,aa.existencia,aa.codtiend,aa.cod_pro,t.dentie,
					aa.codart as articulo,pro.nompro as proveedor,a.*,p.*,um.*,c.den_sub,ci.denominacion as denspi
			 FROM   sfc_producto p, sfc_subclasificacion c,spi_cuentas ci,sim_unidadmedida um,
					sim_articuloalmacen aa,sim_almacen al,rpc_proveedor pro,sim_articulo a , sfc_tienda t
			 WHERE  p.codemp=ci.codemp AND p.codart=aa.codart    AND p.codtiend=aa.codtiend 
			   AND  p.codemp=aa.codemp AND p.codemp=al.codemp    AND p.codemp=pro.codemp AND p.codart=a.codart
			   AND  p.codemp=a.codemp  AND p.codtiend=t.codtiend AND p.codemp=t.codemp   AND c.cod_sub=a.cod_sub
			   AND  c.codcla=a.codcla  AND ci.codemp=aa.codemp   AND ci.spi_cuenta=t.spi_cuenta 
			   AND  ci.codemp=al.codemp AND ci.codemp=pro.codemp AND ci.codemp=a.codemp  AND ci.codemp=t.codemp
			   AND  um.codunimed=a.codunimed AND aa.codalm=al.codalm  AND aa.codemp=al.codemp 
			   AND 	aa.cod_pro=pro.cod_pro   AND aa.codemp=pro.codemp AND aa.codart=a.codart 
			   AND  aa.codemp=a.codemp   AND aa.codtiend=t.codtiend   AND aa.codemp=a.codemp 
			   AND  al.codemp=pro.codemp AND al.codemp=a.codemp       AND al.codemp=t.codemp 
			   AND  pro.codemp=a.codemp  AND  pro.codemp=t.codemp     AND a.codemp=t.codemp
			   AND  aa.codalm='".$ls_codalm."'    
			   AND	p.codemp='0001'      AND a.denart ilike '".$ls_denpro."' AND a.codcla ilike '".$ls_codclas."' " .
			 " AND  a.tippro ilike '".$ls_tipprod."' AND p.codtiend ilike '".$ls_codtie."' AND  aa.codtiend ilike '".$ls_codtie."'";
			$rs_dataunipro=$io_sql->select($ls_cadena);
			if($rs_dataunipro==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_dataunipro))
				{
					print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>Código</font></td>";
					print "<td><font color=#FFFFFF>Descripcion</font></td>";
					print "<td><font color=#FFFFFF>Existencia</font></td>";
					print "<td><font color=#FFFFFF>Unidad de Medida</font></td>";
					print "<td><font color=#FFFFFF>Unidad</font></td>";
					print "<td><font color=#FFFFFF>Costo</font></td>";
					print "<td><font color=#FFFFFF>Precio</font></td>";
					print "<td><font color=#FFFFFF>Precio+iva</font></td>";
					print "<td><font color=#FFFFFF>Almacen</font></td>";
					print "<td><font color=#FFFFFF>Proveedor</font></td>";
					print "<td><font color=#FFFFFF>Tienda</font></td>";
					ob_flush();
					flush();	
					$la_producto=$io_sql->obtener_datos($rs_dataunipro);
					$io_datapro->data=$la_producto;
					$totrowpro=$io_datapro->getRowCount("codart");
					//print '$totrowpro->'.$totrowpro;
					for($z=1;$z<=$totrowpro;$z++)
					{

						$nompro=$io_datapro->getValue("proveedor",$z);
						$codpro=$io_datapro->getValue("articulo",$z);
						$nomalm=$io_datapro->getValue("nomfisalm",$z);
		                $denpro=$io_datapro->getValue("denart",$z)." ".$io_datapro->getValue("denunimed",$z);
						$tippro=$io_datapro->getValue("tippro",$z);
						$preven=$funsob->uf_convertir_numerocadena($io_datapro->getValue("preven",$z));
						$codcar=$io_datapro->getValue("codcar",$z);
						$codcla=$io_datapro->getValue("codcla",$z);
						$dencla=$io_datapro->getValue("dencla",$z);
						$codart=$io_datapro->getValue("codart",$z);
						$denart=$io_datapro->getValue("denart",$z);
						$ld_exi=$io_datapro->getValue("existencia",$z);
						$ultcosart=$funsob->uf_convertir_numerocadena($io_datapro->getValue("ultcosart",$z));
						$spi_cuenta=$io_datapro->getValue("spi_cuenta",$z);
						$denspi=$io_datapro->getValue("denspi",$z);
						$sc_cuenta=$io_datapro->getValue("sc_cuenta",$z);
						$denunidad=$io_datapro->getValue("denunimed",$z);
						$denart=$io_datapro->getValue("denart",$z)." ".$denunidad;
						$unidad=$io_datapro->getValue("unidad",$z);
						$codalmacen=$io_datapro->getValue("codalm",$z);
						$desalm=$io_datapro->getValue("desalm",$z);
						$cod_pro=$io_datapro->getValue("cod_pro",$z);
						$dentie=$io_datapro->getValue("dentie",$z);

						/************************* AGREGAR CODIGO  OTROS CAMPOS  *******************/
						$costo=$funsob->uf_convertir_numerocadena($io_datapro->getValue("cosproart",$z));
						$moncar=$funsob->uf_convertir_numerocadena($io_datapro->getValue("moncar",$z));
						$porgan=$funsob->uf_convertir_numerocadena($io_datapro->getValue("porgan",$z));
						$tipcos=$io_datapro->getValue("tipcos",$z);
						$preuni=$funsob->uf_convertir_numerocadena($io_datapro->getValue("preuni",$z));
						$porcar=$funsob->uf_convertir_numerocadena($io_datapro->getValue("porcar",$z));
						$preven1=$funsob->uf_convertir_numerocadena($io_datapro->getValue("preven",$z));
						$preven2=$funsob->uf_convertir_numerocadena($io_datapro->getValue("preven2",$z));
						$preven=$funsob->uf_convertir_numerocadena($io_datapro->getValue("preven",$z));
						$preven3=$funsob->uf_convertir_numerocadena($io_datapro->getValue("preven3",$z));
						$cosfle=$funsob->uf_convertir_numerocadena($io_datapro->getValue("cosfle",$z));
						$cosproart=$funsob->uf_convertir_numerocadena($io_datapro->getValue("cosproart",$z));
						$preiva=($io_datapro->getValue("preven",$z)*$porcar/100)+$io_datapro->getValue("preven",$z);
						/***************************************************************************/
						$ls_cadena2="SELECT i.dencar,i.porcar FROM  sigesp_cargos i WHERE i.codemp='0001' AND i.codcar='".$codcar."'";
						$rs_datauni2=$io_sql->select($ls_cadena2);
						$la_producto2=$io_sql->obtener_datos($rs_datauni2);
						$io_data2->data=$la_producto2;
						$totrow2=$io_data2->getRowCount("codcar");
						$porcar=$io_data2->getValue("porcar",1);
						$dencar=$io_data2->getValue("dencar",1);
						$porcar=$funsob->uf_convertir_numerocadena($io_data2->getValue("porcar",1));
						//print $codpro.'->'.$denart;
						if ($denart!=' ')
						{
							//print 'paso';
						print "<tr class=celdas-blancas>";
					    print "<td><a href=\"javascript: aceptar('$codpro','$denpro','$tippro','$preven','$codcar','$dencar','$codcla','$dencla','$codart','$denart','$ultcosart','$spi_cuenta','$denspi','$sc_cuenta','$denscg','$moncar','$porgan','$tipcos','$preuni','$preven1','$preven2','$preven3','$cosfle','$cosproart','$porcar','$codalmacen','$desalm','$ld_exi','$cod_pro','$nompro');\">".$codpro."</a></td>";
						print "<td align=left>".$denart."</td>";
						print "<td align=left>".$ld_exi."</td>";
						print "<td align=left>".$denunidad."</td>";
						print "<td align=left>".$unidad."</td>";
						print "<td align=left>".$costo."</td>";
						print "<td align=left>".$preven."</td>";
						print "<td align=left>".$funsob->uf_convertir_numerocadena($preiva)."</td>";
						print "<td align=left>".$nomalm."</td>";
						print "<td align=left>".$nompro."</td>";
						print "<td align=left>".$ls_codtie."-".$dentie."</td>";
						print "</tr>";
						ob_flush();
						flush();	
						}
					}
				}
				else
				{
					$io_msg->message("No se han registrado Productos");
				}
		}

}
print "</table>";
/*ob_end_flush();
ob_clean();*/
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


  function aceptar(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cosproart,porcar,codalm,desalm,exi,cod_pro,nompro)
  {
  //alert(preven);
  if (preven=='0,00')
  {
  alert ('Debe actualizar el precio del producto, debe ser >0');
  }else
  {  
    opener.ue_cargarproducto(codpro,denpro,tippro,preven,codcar,dencar,codcla,dencla,codart,denart,ultcosart,spi_cuenta,denspi,sc_cuenta,denscg,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cosproart,porcar,codalm,desalm,exi,cod_pro,nompro);
	//close();
	}
  }

function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_producto_almacen.php";

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
