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
            <option value="P">Producto</option>
            <option value="B">Bien</option>
            <option value="S">Servicio</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td height="32"><div align="right">Descripcion</div></td>
        <td><div align="left">
          <input name="denpro" type="text" id="denpro"  size="60" maxlength="225">
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
 $ls_cadena=" SELECT p.codcar,p.moncar,p.porgan,p.preuni,p.preven,p.preven1,p.preven2,p.preven3,p.cosfle," .
 		" p.ultcosart,p.cosproart,u.id_uso,u.denuso,u.descripcion,c.dencla,s.den_sub,a.*,um.*".
" FROM sfc_producto p,sfc_clasificacion c, sim_articulo a, sfc_subclasificacion s,sfc_uso u,sim_unidadmedida um ".
" WHERE p.codemp='".$ls_codemp."' and p.codemp=a.codemp and a.codcla=c.codcla and a.cod_sub=s.cod_sub" .
" and p.codart=a.codart and a.denart ilike '".$ls_denpro."' and a.codcla like '".$ls_codclas."'" .
" and a.tippro like '".$ls_tipprod."' AND u.id_uso=a.id_uso AND u.codemp=p.codemp and um.codunimed=a.codunimed ";
	//print $ls_cadena;
			$rs_datauni=$io_sql->select($ls_cadena);

			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				$li=0;
				print "<table width=800 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
				print "<tr class=titulo-celda>";
				print "<td><font color=#FFFFFF>C�digo</font></td>";
				print "<td><font color=#FFFFFF>Descripcion</font></td>";
				print "<td><font color=#FFFFFF>Clasificacion</font></td>";
				print "<td><font color=#FFFFFF>Existencia</font></td>";
				print "<td><font color=#FFFFFF>Subclasificacion</font></td>";
				print "<td><font color=#FFFFFF>Unidad de Medida</font></td>";
				print "<td><font color=#FFFFFF>Unidad</font></td></tr>";
				while($row=$io_sql->fetch_row($rs_datauni))
				{
					$li++;
					
					/*$la_producto=$io_sql->obtener_datos($rs_datauni);

					$io_data->data=$la_producto;

					$totrow=$io_data->getRowCount("denart");

					for($z=1;$z<=$totrow;$z++)
					{*/
						print "<tr class=celdas-blancas>";
						$tippro=$row["tippro"];
						$preven=$funsob->uf_convertir_numerocadena($row["preven"]);
						$codcar=$row["codcar"];
						$dencar=$row["dencar"];
						$codcla=$row["codcla"];
						$dencla=$row["dencla"];
						$codcla1=$row["cod_sub"];
						$dencla1=$row["den_sub"];
						$codart=$row["codart"];
						$denart=$row["denart"];
						$ultcosart=$funsob->uf_convertir_numerocadena($row["ultcosart"]);
						$exist=$row["existencia"];
						$coduso=$row["id_uso"];
						$denuso=$row["denuso"];
						$descripcion=$row["descripcion"];
						if ($descripcion!='S/D')
						{
							$denuso=$descripcion." ".$denuso;
						}

						$porgan=$funsob->uf_convertir_numerocadena($row["porgan"]);
						$moncar=$funsob->uf_convertir_numerocadena($row["moncar"]);
						$tipcos=$row["tipcos"];
					   	$preuni=$funsob->uf_convertir_numerocadena($row["preuni"]);

						$preven1=$funsob->uf_convertir_numerocadena($row["preven1"]);
						$preven2=$funsob->uf_convertir_numerocadena($row["preven2"]);
						$preven3=$funsob->uf_convertir_numerocadena($row["preven3"]);
						$cosfle=$funsob->uf_convertir_numerocadena($row["cosfle"]);
						$cosproart=$funsob->uf_convertir_numerocadena($row["cosproart"]);
						$preven=$funsob->uf_convertir_numerocadena($row["preuni"] + $row["moncar"]);
						$denunidad=$row["denunimed"];
						$unidad=$row["unidad"];
						$ls_cadena2="SELECT i.dencar,i.porcar FROM  sigesp_cargos i WHERE i.codemp='0001' AND i.codcar='".$codcar."'";
						$rs_datauni2=$io_sql->select($ls_cadena2);
						$la_producto2=$io_sql->obtener_datos($rs_datauni2);
						$io_data2->data=$la_producto2;
						$totrow2=$io_data2->getRowCount("codcar");
						$porcar=$io_data2->getValue("porcar",1);
						$dencar=$io_data2->getValue("dencar",1);
						$porcar=$funsob->uf_convertir_numerocadena($io_data2->getValue("porcar",1));


					   print "<td><a href=\"javascript: aceptar('$codart','$denart','$tippro','$preven','$codcar','$dencar','$codcla','$dencla','$codcla','$dencla','$ultcosart','$moncar','$porgan','$tipcos','$preuni','$preven1','$preven2','$preven3','$cosfle','$cosproart','$porcar','$codcla1','$dencla1','$preven','$coduso','$denuso');\">".$codart."</a></td>";
					   $rs_datauni2=$io_sql->select($ls_cadena);
						print "<td align=left>".$denart."</td>";
						print "<td align=left>".$dencla."</td>";
						print "<td align=left>".$exist."</td>";
						print "<td align=left>".$dencla1."</td>";
						print "<td align=left>".$denunidad."</td>";
						print "<td align=left>".$unidad."</td>";
						print "</tr>";
					//}
			}
			if($li==0)
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
  function aceptar(codart,denart,tippro,preven,codcar,dencar,codcla,dencla,codcla,dencla,ultcosart,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cosproart,porcar,codcla1,dencla1,preven,coduso,denuso)
  {
opener.ue_cargarproducto(codart,denart,tippro,preven,codcar,dencar,codcla,dencla,codcla,dencla,ultcosart,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cosproart,porcar,codcla1,dencla1,preven,coduso,denuso);
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
