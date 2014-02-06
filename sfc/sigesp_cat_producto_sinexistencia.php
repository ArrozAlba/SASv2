<?
/////////////////////////////////////////////////////////////////////////////////////////////
 // Catalogo:       - sigesp_cat_producto_sinexistencia.php
 // Autor:       - Ing. Zulheymar Rodríguez
 // Fecha:       - 28/08/2007
 //////////////////////////////////////////////////////////////////////////////////////////
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
$ls_codtienda=$_SESSION["ls_codtienda"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Productos</title>
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
	$ls_opcion=$_POST["opcion"];
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
	$ls_opcion="pordebajomin";
}
if($ls_operacion=="ue_actualizar_option")
	{
		  if ( $ls_opcion=="pordebajomin"){
		  $ls_opcion="pordebajomin";
		  $ls_ordenarpor="Null";
		  }else{
		  $ls_opcion="todos";
		  $ls_ordenarpor="Null";
		  }
	}
/************************************************************************************************************************/
/***************************   TABLAS  ***********************************************************************/
/************************************************************************************************************************/
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
	<input name="hidcodalm" type="hidden" id="hidcodalm" value="<?php print $ls_codalma ?>">
  </p>
  	 <table width="700" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="696" colspan="2" class="titulo-celda">Cat&aacute;logo de Conceptos de Facturaci&oacute;n	</td>
    	</tr>
	 </table>
	 <br>
	 <table width="700" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">

      <tr>
        <td width="102" height="30"><div align="right">Clasificación</div></td>
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
<?php
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
		</select>
        </label></td>
      </tr>
      <tr>
        <td height="32"><div align="right">Almac&eacute;n</div></td>
        <td><span class="style6">
		<?php
/************************************************************************************************************************/
/***************************************  COMBO ALMACEN  ****************************************************************/
/************************************************************************************************************************/
		    $ls_sql="SELECT DISTINCT alm.codalm ,alm.nomfisalm
					   FROM sim_almacen alm,sim_articuloalmacen art 
					  WHERE codtiend='".$ls_codtienda."' AND alm.codalm ilike '%".$ls_codtienda."'
					  ORDER BY nomfisalm ASC";
			//print $ls_sql;
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
						<td height="8">&nbsp;</td>
						<td colspan="3"><p>
							<label>
							<?php
							 if ($ls_opcion=='pordebajomin')
							   {
							   ?>
			                   <input name="opcion" type="radio" value="pordebajomin"  checked="checked" onClick="actualizar_option()">
			                   Por Debajo ó Igual al Minimo                </label>
								 <label>
			                    <input name="opcion" type="radio" value="todos"  onClick="actualizar_option()" >
			                   Todos los Productos
							   </label>
								<?php
								}
								else
								{
								?>
								 <input name="opcion" type="radio" value="pordebajomin"  onClick="actualizar_option()">
			                    Por Debajo ó Igual al Minimo                  </label>
			                  <label>
			                    <input name="opcion" type="radio" value="todos"  checked="checked" onClick="actualizar_option()">
			                    Todos los Productos </label>
								<?php
								}
								?>
			                  <br>
			                </p>
						</td>
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
	if ($ls_opcion=='pordebajomin')
  	{
	 $ls_cadena=" SELECT alm.nomfisalm, alm.codalm,a.codart,a.denart,a.codcla,cla.dencla,aa.existencia,p.minart,aa.cod_pro,pr.nompro FROM sfc_producto p," .
	 		"sim_articulo a, sim_articuloalmacen aa, sim_almacen alm,sfc_clasificacion cla,rpc_proveedor pr WHERE p.codemp='".$ls_codemp."' and p.codemp=a.codemp " .
	 		"and p.codart=aa.codart and p.codart=a.codart and cla.codcla=a.codcla AND a.denart ilike '".$ls_denpro."'  AND a.codcla like '".$ls_codclas."' " .
	 		" and cla.codcla ilike '".$ls_codclas."' AND a.tippro ilike '".$ls_tipprod."' AND a.codart=aa.codart AND aa.codalm=alm.codalm AND " .
	 		" alm.codalm ilike '".$ls_codalm."' and alm.codalm ilike '".$ls_codalm."'  AND aa.existencia <= p.minart and aa.codalm ilike '".$ls_codalm."' " .
	 		" and p.codtiend=aa.codtiend and p.codtiend='".$ls_codtienda."' and aa.codtiend='".$ls_codtienda."' and aa.cod_pro=pr.cod_pro ORDER BY a.denart ASC ";
	 //print $ls_cadena;
	 }else{
	 $ls_cadena=" SELECT alm.nomfisalm, alm.codalm,a.codart,a.denart,a.codcla,cla.dencla,aa.existencia,p.minart,aa.cod_pro,pr.nompro FROM sfc_producto p," .
	 		"sim_articulo a, sim_articuloalmacen aa, sim_almacen alm,sfc_clasificacion cla,rpc_proveedor pr WHERE p.codemp='".$ls_codemp."' " .
	 		"and p.codemp=a.codemp and p.codart=aa.codart and p.codart=a.codart and cla.codcla=a.codcla AND a.denart ilike '".$ls_denpro."'  " .
	 		"AND a.codcla like '".$ls_codclas."' and cla.codcla ilike '".$ls_codclas."' AND a.tippro ilike '".$ls_tipprod."' AND a.codart=aa.codart " .
	 		" AND aa.codalm=alm.codalm and alm.codalm ilike '".$ls_codalm."' and aa.codalm ilike '".$ls_codalm."' and p.codtiend=aa.codtiend" .
	 		" and p.codtiend='".$ls_codtienda."' and aa.codtiend='".$ls_codtienda."' and aa.cod_pro=pr.cod_pro ORDER BY a.denart ASC ";
	//print $ls_cadena;
	 }
		//print $ls_cadena;

			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				print "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td><font color=#FFFFFF>Código</font></td>";
				print "<td><font color=#FFFFFF>Descripcion</font></td>";
				print "<td><font color=#FFFFFF>Clasificación</font></td>";
				print "<td><font color=#FFFFFF>Proveedor</font></td>";
				print "<td><font color=#FFFFFF>Existencia</font></td>";
				print "<td><font color=#FFFFFF>Minimo</font></td>";
				print "<td><font color=#FFFFFF>Almacén</font></td>";
				$i=0;
				while($row=$io_sql->fetch_row($rs_datauni))
				{
					$i++;
					print "<tr class=celdas-blancas>";
					$codpro=$row["codart"];
					$denpro=$row["denart"];
					$codcla=$row["codcla"];
					$dencla=$row["dencla"];
					$minimo=$row["minart"];
					$nomfisalm=$row["nomfisalm"];
					$codalm=$row["codalm"];
					$ld_exi=$row["existencia"];
					$codproveedor=$row["cod_pro"];
					$denproveedor=$row["nompro"];
					//print $codpro.$denpro.$codalm;

					$existe=$funsob->uf_convertir_numerocadena($row["existencia"]);
					print "<td><a href=\"javascript: aceptar('$codpro','$denpro','$codalm','$codproveedor','$denproveedor');\">".$codpro."</a></td>";
					print "<td align=left>".$denpro."</td>";
					print "<td align=left>".$dencla."</td>";
					print "<td align=left>".$codproveedor."-".$denproveedor."</td>";
					print "<td align=left>".$existe."</td>";
					print "<td align=left>".$minimo."</td>";
					print "<td align=left>".$nomfisalm."</td>";
					print "</tr>";
					
				}
				if($i==0)
				{
					$io_msg->message("No hay Registros de Productos con esa Descripción");
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
function aceptar(codpro,denpro,codalm,codproveedor,denproveedor)
{
    opener.ue_cargarproducto(codpro,denpro,codalm,codproveedor,denproveedor);
	//close();
}
function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_producto_sinexistencia.php";
  f.submit();
}
 function actualizar_option()
{
	f=document.form1;
	f.operacion.value="ue_actualizar_option";
	f.action="sigesp_cat_producto_sinexistencia.php";
	f.submit();
}
</script>
</html>
