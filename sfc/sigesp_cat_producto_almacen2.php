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
	$ls_cliente=$_POST["cliente"];
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
	if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];
	}
	else
	{
		$ls_tipo="";
	}	
	if(array_key_exists("cliente",$_GET))
	{
		$ls_cliente=$_GET["cliente"];
	}
	else
	{
		$ls_cliente="";
	}	
}

/************************************************************************************************************************/
/***************************   TABLAS DREAMWEAVER ***********************************************************************/
/************************************************************************************************************************/
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
	<input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalma ?>">
    <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
    <input name="cliente" type="hidden" id="cliente" value="<?php print $ls_cliente;?>">
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
        <td height="32"><div align="right">Almac&eacute;n</div></td>
        <td><span class="style6">
		<?Php
/************************************************************************************************************************/
/***************************************  COMBO ALMACEN  ****************************************************************/
/************************************************************************************************************************/
/*******************************************************************************************************************/
		    $ls_sql="SELECT DISTINCT alm.codalm ,alm.nomfisalm
					   FROM sim_almacen alm,sim_articuloalmacen art 
					  WHERE codtiend='".$ls_codtie."' AND alm.codalm ilike '%".$ls_codtie."'
					  ORDER BY nomfisalm ASC";
			$lb_valido=$io_utilidad->uf_datacombo($ls_sql,&$la_codalm);

			if($lb_valido)
			 {
			   $io_datastore->data=$la_codalm;
			   $li_totalfilas=$io_datastore->getRowCount("codalm");
			 }
			 else
			   $li_totalfilas=0;
		  ?>
          <select name="cmb_almacen" size="1"  id="cmb_almacen">
            <option value="">Seleccione...</option>
            <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
						 $ls_codigo=$io_datastore->getValue("codalm",$li_i);
						 $ls_nomfisalm=$io_datastore->getValue("nomfisalm",$li_i);
						 if ($ls_codigo==$ls_codalma)
						 {
							  print "<option value='$ls_codigo' selected >$ls_nomfisalm</option>";

						 }
						 else
						 {
						      print "<option value='$ls_codigo'  >$ls_nomfisalm</option>";

						 }
					}
	        ?>
          </select>
        </span></td>
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
/************************************************************************************************************************/
/**********************************************  BUSCAR  ****************************************************************/
/************************************************************************************************************************/
if($ls_operacion=="BUSCAR")
{
	if($ls_cliente!="")
	{
		$ls_select=" (CASE CLI.precio_estandar WHEN 'PV' THEN p.preven WHEN 'PU' THEN p.preven1 WHEN 'PD' THEN p.preven2 WHEN 'PT' THEN p.preven3 END) as precio ";
		$ls_from  =" ,sfc_cliente CLI ";
		$ls_where =" AND CLI.codemp=p.codemp AND CLI.codcli='".$ls_cliente."' ";
	}
	else
	{
		$ls_select=" p.preven as precio";
		$ls_from  =" ";
		$ls_where =" ";
	}
	
$ls_cadena="SELECT p.codart,p.cosproart,p.codcar,um.denunimed,um.unidad,aa.existencia,aa.codalm,aa.codtiend,al.nomfisalm,rpc.cod_pro,rpc.nompro,a.denart,".$ls_select.",p.moncar ".
			" FROM sfc_producto p,sim_articuloalmacen aa,sim_almacen al,rpc_proveedor rpc,sim_articulo a,sim_unidadmedida um ".$ls_from.
			" WHERE p.codart=aa.codart AND p.codemp=aa.codemp AND p.codtiend=aa.codtiend AND p.codemp=al.codemp AND p.codemp=rpc.codemp AND".
			" p.codart=a.codart AND p.codemp=a.codemp AND aa.codalm=al.codalm AND aa.codemp=al.codemp AND aa.cod_pro=rpc.cod_pro AND ".
			" aa.codemp=rpc.codemp AND aa.codart=a.codart AND aa.codemp=a.codemp AND al.codemp=rpc.codemp AND al.codemp=a.codemp AND ".
			" rpc.codemp=a.codemp AND rpc.codemp=a.codemp AND um.codunimed=a.codunimed AND p.codemp='".$ls_codemp."' AND ".
			" a.denart ilike '".$ls_denpro."' AND a.codcla ilike '".$ls_codclas."' AND a.tippro ilike '".$ls_tipprod."' AND ".
			" al.codalm ilike '".$ls_codalm."' AND al.codalm ilike '%".$ls_codtie."' AND p.codtiend ilike '".$ls_codtie."' AND  aa.codtiend ilike '".$ls_codtie."' ".$ls_where." ORDER BY codart";
			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				$i=0;
				print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><font color=#FFFFFF>C&oacute;digo</font></td>";
					print "<td><font color=#FFFFFF>Descripcion</font></td>";
					print "<td><font color=#FFFFFF>Existencia</font></td>";
					print "<td><font color=#FFFFFF>Almac&eacute;n</font></td>";
					print "<td><font color=#FFFFFF>Unidad de Medida</font></td>";
					print "<td><font color=#FFFFFF>Unidad</font></td>";
					print "<td><font color=#FFFFFF>Precio</font></td>";
					print "<td><font color=#FFFFFF>Costo</font></td>";
					print "<td><font color=#FFFFFF>Proveedor</font></td>";
					print "<td><font color=#FFFFFF>Tienda</font></td></tr>";
				while($row=$io_sql->fetch_row($rs_datauni))
				{
					$i++;					
					print "<tr class=celdas-blancas>";
					$tienda=$row["codtiend"];
					$codpro=$row["codart"];
					$denpro=$row["denart"]." ".$row["denunimed"];
					$preven=$funsob->uf_convertir_numerocadena($row["precio"]);
					$codcar=$row["codcar"];
					$nomfisalm=$row["nomfisalm"];
					$codalm=$row["codalm"];
					$ld_exi=$row["existencia"];
					$existe=$funsob->uf_convertir_numerocadena($row["existencia"]);
					$cod_pro=$row["cod_pro"];
					$nompro=$row["nompro"];
					$ls_costo=$row["cosproart"];
					//print $ls_costo;
					$denunidad=$row["denunimed"];
					$unidad=$row["unidad"];
					/************************* AGREGAR CODIGO  OTROS CAMPOS  *******************/
					$moncar=$funsob->uf_convertir_numerocadena($row["moncar"]);
					/***************************************************************************/
					$ls_cadena2="SELECT i.dencar,i.porcar FROM  sigesp_cargos i WHERE i.codemp='0001' AND i.codcar='".$codcar."'";

					//print $ls_cadena2;
					$rs_datauni2=$io_sql->select($ls_cadena2);
					$la_producto2=$io_sql->obtener_datos($rs_datauni2);
					$io_data2->data=$la_producto2;
					$totrow2=$io_data2->getRowCount("codcar");
					$porcar=$io_data2->getValue("porcar",1);
					$dencar=$io_data2->getValue("dencar",1);
					$porcar=$funsob->uf_convertir_numerocadena($io_data2->getValue("porcar",1));

				   print "<td><a href=\"javascript: aceptar('$codpro','$denpro','$preven','$moncar','$porcar','$codalm','$nomfisalm','$ld_exi','$cod_pro','$nompro','$ls_costo');\">".$codpro."</a></td>";
					print "<td align=left>".$denpro."</td>";
					print "<td align=left>".$existe."</td>";
					print "<td align=left>".$nomfisalm."</td>";
					print "<td align=left>".$denunidad."</td>";
					print "<td align=left>".$unidad."</td>";
					print "<td align=left>".$preven."</td>";
					print "<td align=left>".$ls_costo."</td>";
					print "<td align=left>".$nompro."</td>";
					print "<td align=left>".$tienda."</td>";
					print "</tr>";
					
				}
				if($i==0)
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


  function aceptar(codpro,denpro,preven,moncar,porcar,codalm,nomfisalm,exi,cod_pro,nompro,costo)
  {
    //alert (exi);
    if (exi==0) 
    {
      alert ("Este producto tiene existencia 0, no puede ser agregado a la factura");
    } else 
    {
      ls_tipo=document.form1.tipo.value;
	  if(ls_tipo=="")
	  {
	  	opener.ue_cargarproducto(codpro,denpro,preven,moncar,porcar,codalm,nomfisalm,exi,cod_pro,nompro,costo);
	  }
	  else
	  {
	  	opener.ue_cargarinsumo(codpro,denpro,preven,moncar,porcar,codalm,nomfisalm,exi,cod_pro,nompro,costo);
	  }	
    }
	//close();
  }

function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_producto_almacen2.php";

  f.submit();
  }

</script>
</html>
