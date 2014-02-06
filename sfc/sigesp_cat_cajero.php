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
<title>Cat&aacute;logo de Cajero</title>
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
	$ls_nomusu="%".$_POST["nomusu"]."%";
	if ($_SESSION["ls_codtienda"]=='0001')
	{
	$ls_codtie=$_POST["cmbtienda"];
	$ls_codtien="%".$_POST["cmbtienda"]."%";
/*	print $ls_codtie;
	print $ls_codtien;*/
	}
}
else
{
	$ls_operacion="";
	$ls_nomusu="";
	if ($_SESSION["ls_codtienda"]=='0001')
	{
	$ls_codtie="";
	$ls_codtien="";
	}
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">

</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cajero </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">

      <tr>
        <td width="103"><div align="right">Usuario</div></td>
		&nbsp;
        <td width="395"><div align="left">
          <input name="nomusu" type="text" id="nomusu"  size="60" maxlength="225">
        </div></td>
      </tr>
      <?php
      if ( $_SESSION["ls_codtienda"]=='0001')
      {
      	?>
 		   <tr>
        <td height="30"><div align="right">Unidad Operativa de Suministro</div></td>
        <td><span class="style6">
          <?Php

		    $ls_sql="SELECT codtiend ,dentie
                       FROM sfc_tienda
                      ORDER BY codtiend ASC";

			$lb_valtie=$io_utilidad->uf_datacombo($ls_sql,&$la_tienda);


			if($lb_valtie)
			 {
			   $io_datastore->data=$la_tienda;
			   $li_totalfilas=$io_datastore->getRowCount("codtiend");
			 }
			 else
			   $li_totalfilas=0;

		  ?>
          <select name="cmbtienda" size="1" id="cmbtienda">
            <option value="">Seleccione...</option>
            <?Php
					for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
					{
					 $ls_codigo=$io_datastore->getValue("codtiend",$li_i);
					 $ls_dentie=$io_datastore->getValue("dentie",$li_i);
					 if ($ls_codigo==$ls_codest)
					 {
						  print "<option value='$ls_codigo' selected>$ls_dentie</option>";
					 }
					 else
					 {
						  print "<option value='$ls_codigo'>$ls_dentie</option>";
					 }
					}
	        ?>
          </select>
        </span></td>
      </tr>
      <?php
      }
      ?>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?


if($ls_operacion=="BUSCAR")
{
if ($ls_codtien!='')
{
 $ls_cadena=" SELECT * ".
			" FROM sfc_cajero ".
			" WHERE nomusu ilike '".$ls_nomusu."' AND codtiend ilike '".$ls_codtien."'";
//print $ls_cadena;
}else{
	$ls_cadena=" SELECT * ".
			" FROM sfc_cajero ".
			" WHERE nomusu ilike '".$ls_nomusu."'";
//print $ls_cadena;
}
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
					print "<td><font color=#FFFFFF>Nombre</font></td>";
					if ($ls_codtien!='')
					{
					print "<td><font color=#FFFFFF>Tienda</font></td>";
					}
					$la_cajero=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_cajero;
					$totrow=$io_data->getRowCount("codusu");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codusu=$io_data->getValue("codusu",$z);
		                $nomusu=$io_data->getValue("nomusu",$z);
						$codtie=$io_data->getValue("codtiend",$z);
						$nomtie=$io_data->getValue("nomtie",$z);
						$codcaja=$io_data->getValue("cod_caja",$z);
						$dencaja=$io_data->getValue("dencaja",$z);
						print "<td><a href=\"javascript: aceptar('$codusu','$nomusu','$codtie','$nomtie','$codcaja','$dencaja');\">".$codusu."</a></td>";
						print "<td align=left>".$nomusu."</td>";
						if ($ls_codtien!='')
						{
						print "<td align=left>".$nomtie."</td>";
						}
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("No se han registrado Cajeros");
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

  function aceptar(codusu,nomusu,codtie,nomtie,codcaja,dencaja)
  {
    opener.ue_cargarcajero(codusu,nomusu,codtie,nomtie,codcaja,dencaja);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_cajero.php";
  f.submit();
  }
</script>
</html>
