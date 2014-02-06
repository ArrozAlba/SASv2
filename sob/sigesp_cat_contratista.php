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
<title>Cat&aacute;logo de Contratistas</title>
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
require_once("class_folder/sigesp_sob_class_asignacion.php");
require_once("class_folder/class_funciones_sob.php");
$io_fun_sob=new class_funciones_sob();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_casig=new sigesp_sob_class_asignacion();
$ls_codemp=$la_datemp["codemp"];
$ls_tipo=$io_fun_sob->uf_obtenertipo();
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codest="%".$_POST["cmbestado"]."%";
	$ls_nompro="%".$_POST["nompro"]."%";
	$ls_codigoest=$_POST["cmbestado"];
	$ls_nombrepro=$_POST["nompro"];
	$ls_codpai=$_POST["hidpais"];
	$ls_codpais="%".$_POST["cmbpais"]."%";

}
else
{
	$ls_operacion="";
	$ls_codigoest="";
	$ls_nombrepro="";
	$ls_codpai="058";
}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Contratistas </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="31"><div align="right"><span class="style6">Pais</span></div></td>
        <td><span class="style6">
          <?Php
           $lb_vali=$io_casig->uf_llenarcombo_pais($la_pais);
		   
		   if($lb_vali)
		   {
		    $io_data->data=$la_pais;
		    $totrow=$io_data->getRowCount("codpai");
		   }
		   else
		   	$totrow=0;			
			
		   ?>
          <select name="cmbpais" size="1" id="cmbpais" onChange="javascript:ue_cambiarestado()">
            <?Php
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{
					 $ls_codigo=$io_data->getValue("codpai",$li_i);
					 $ls_desest=$io_data->getValue("despai",$li_i);
					 if ($ls_codigo==$ls_codpai)
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
          <input name="hidpais" type="hidden" id="hidpais" value="<? print $ls_codpai?>">
        </span></td>
      </tr>
      <tr>
        <td width="67" height="30"><div align="right">Estado</div></td>
        <td width="431"><div align="left">
          <?Php
           $lb_vali=$io_casig->uf_llenarcombo_estado($ls_codpai,$la_estado);
		   
		   if($lb_vali)
		   {
		    $io_data->data=$la_estado;
		    $totrow=$io_data->getRowCount("codest");
		   }
		   else
		   	$totrow=0;
		   ?>
             <select name="cmbestado" id="cmbestado">
             <option value="">Todos</option>
         <?Php
			for($z=1;$z<=$totrow;$z++)
			 {
			  $ls_codestado=$io_data->getValue("codest",$z);
		      $ls_desest=$io_data->getValue("desest",$z);
		      if ($ls_codestado==$ls_codigoest)
			   {
				print "<option value='$ls_codestado' selected>$ls_desest</option>";
			   }
		       else
			   {
				print "<option value='$ls_codestado'>$ls_desest</option>";
			   }
		      }      
	        ?>
                  </select>
                  <input name="hidestado" type="hidden" id="hidestado"  value="<? print $ls_codigoest; ?>">
</div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="nompro" type="text" id="nompro" value="<? print $ls_nombrepro;?>" size="60">
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
 $ls_cadena=" SELECT cod_pro,nompro,nomreppro,tipconpro ".
			"   FROM rpc_proveedor ".
			"  WHERE codemp='".$ls_codemp."'".
			"    AND codpai like '".$ls_codpais."'".
			"    AND codest like '".$ls_codest."'".
			"    AND nompro like '".$ls_nompro."'".
			"    AND estcon=1".
			" ORDER BY $ls_campo $ls_orden";

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
					print "<td><a href=javascript:ue_ordenar('cod_pro','BUSCAR');><font color=#FFFFFF>Código</font></a></td>";
					print "<td><a href=javascript:ue_ordenar('nompro','BUSCAR');><font color=#FFFFFF>Nombre</font></a></td>";
					$la_unidades=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_unidades;
					$totrow=$io_data->getRowCount("cod_pro");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codpro=$io_data->getValue("cod_pro",$z);
		                $nompro=$io_data->getValue("nompro",$z);
						$ls_nomreppro=$io_data->getValue("nomreppro",$z);
						$ls_tipconpro=$io_data->getValue("tipconpro",$z);
						switch ($ls_tipo)
						{
							case "":
								print "<td><a href=\"javascript: aceptar('$codpro','$nompro','$ls_nomreppro','$ls_tipconpro');\">".$codpro."</a></td>";
								print "<td align=left>".$nompro."</td>";
								print "</tr>";
							break;

							case "repdocdes":
								print "<td><a href=\"javascript: aceptar_repdocdes('$codpro');\">".$codpro."</a></td>";
								print "<td align=left>".$nompro."</td>";
								print "</tr>";
							break;

							case "repdochas":
								print "<td><a href=\"javascript: aceptar_repdochas('$codpro');\">".$codpro."</a></td>";
								print "<td align=left>".$nompro."</td>";
								print "</tr>";
							break;
						}
					}
				}
				else
				{
					$io_msg->message("No se han registrado Contratistas");
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
	function aceptar_repdocdes(codigo)
	{
		opener.document.form1.txtcodcondes.value=codigo;
		close();
	}
	function aceptar_repdochas(codigo)
	{
		opener.document.form1.txtcodconhas.value=codigo;
		close();
	}
	function aceptar(codp,nomp,representante,tipconpro)
	{
		opener.ue_cargarcontratista(codp,nomp,representante,tipconpro);
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_cat_contratista.php?tipo=<?php print $ls_tipo;?>";
		f.submit();
	}
	function ue_cambiarestado()
	{
		f=document.form1;
		f.hidpais.value=f.cmbpais.value;
		f.action="sigesp_cat_contratista.php?tipo=<?php print $ls_tipo;?>";
		f.submit();
	}
	function ue_mostrar(myfield,e)
	{
		var keycode;
		if (window.event) keycode = window.event.keyCode;
		else if (e) keycode = e.which;
		else return true;
		if (keycode == 13)
		{
			ue_search();
			return false;
		}
		else
			return true
	}
</script>
</html>
