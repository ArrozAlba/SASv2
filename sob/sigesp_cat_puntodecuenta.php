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
	$ls_campo="p.codpuncue";
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
<title>Cat&aacute;logo de Puntos de Cuenta</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
require_once("class_folder/sigesp_sob_class_obra.php");
require_once("../shared/class_folder/class_datastore.php");
require_once ("class_folder/sigesp_sob_c_funciones_sob.php");
$io_funsob= new sigesp_sob_c_funciones_sob(); 
$io_datastore=new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_obra=new sigesp_sob_class_obra();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_codpuncue="%".$_POST["txtcodpuncue"]."%";
	$ls_asupuncue="%".$_POST["txtasupuncue"]."%";
	$ld_fecpuncue=$io_funcion->uf_convertirdatetobd($_POST["txtfecpuncue"]);	
	
	$ls_codigoobr=$_POST["txtcodobr"];	
	$ls_codigopuncue=$_POST["txtcodpuncue"];	
	$ls_asuntopuncue=$_POST["txtasupuncue"];
	$ld_fechapuncue=$_POST["txtfecpuncue"];	
}
else
{
	$ls_operacion="";
	$ls_codigoobr="";
	$ls_codigopuncue="";	
	$ls_asuntopuncue="";
	$ld_fechapuncue="";
	

}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="681" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td colspan="2" class="titulo-celda">Cat&aacute;logo de Puntos de Cuenta </td>
    	</tr>
  </table>
	 <br>
	 <table width="681" border="0" cellpadding="3" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="0" height="31"><div align="right"></div></td>
        <td width="148"><div align="right">C&oacute;digo del Punto de Cuenta </div></td>
        <td width="89"><input name="txtcodpuncue" type="text" id="txtcodpuncue" value="<? print $ls_codigopuncue?>" size="3" maxlength="3" style="text-align:center " ></td>
        <td width="2"><div align="right"></div></td>
        <td width="133"><div align="right">Asunto</div></td>
        <td width="271"><textarea name="txtasupuncue" cols="50" rows="2" wrap="VIRTUAL" id="txtasupuncue" onKeyDown="textCounter(this,255)"><? print $ls_asuntopuncue?></textarea></td>
      </tr>
      <tr>
        <td height="19"><div align="right"></div></td>
        <td height="19"><div align="right">C&oacute;digo de la Obra </div></td>
        <td><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codigoobr?>" size="6" maxlength="6" style="text-align:center ">
        <a href="javascript:ue_catobra();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
        <td><div align="right"></div></td>
        <td><div align="right">Fecha del Punto de Cuenta </div></td>
        <td><input name="txtfecpuncue" type="text"  id="txtfecpuncue" value="<? print $ld_fechapuncue?>" size="11" maxlength="10" datepicker="true" readonly="true" style="text-align:left "></td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>	
<?

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT p.*,o.desobr,prov.nompro,prov.nomreppro 
			FROM sob_puntodecuenta p,sob_obra o,rpc_proveedor prov
			WHERE p.codemp='$ls_codemp' AND p.codemp=o.codemp AND p.codemp=prov.codemp AND p.codobr=o.codobr AND estpuncue<>3
			AND p.cod_pro=prov.cod_pro AND p.codobr LIKE '$ls_codobr' AND p.codpuncue LIKE '$ls_codpuncue' AND 
			p.asupuncue LIKE '$ls_asupuncue'";
	if ($ld_fecpuncue!="")
	{
		$ls_sql=$ls_sql." AND p.fecpuncue='".$ld_fecpuncue."'";
	}	
	$ls_sql=$ls_sql." ORDER BY $ls_campo $ls_orden";
	
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select catalogo anticipo".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}else
	{
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$io_datastore->data=$data;
			$li_totrow=$io_datastore->getRowCount("codpuncue");
			print "<table width=681 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td><a href=javascript:ue_ordenar('p.codpuncue','BUSCAR');><font color=#FFFFFF>Pto. de Cta.</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('p.codobr','BUSCAR');><font color=#FFFFFF>Cod. Obra</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.desobr','BUSCAR');><font color=#FFFFFF>Obra</font></a></td>";			
			print "<td><a href=javascript:ue_ordenar('p.asupuncue','BUSCAR');><font color=#FFFFFF>Asunto</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('p.fecpuncue','BUSCAR');><font color=#FFFFFF>Fecha</font></a></td>";
			print "</tr>";
			print "<br>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{				
				$ls_codobr=$data["codobr"][$li_z];
				$ls_codpuncue=$data["codpuncue"][$li_z];
				$ls_rempuncue=$data["rempuncue"][$li_z];
				$ls_despuncue=$data["despuncue"][$li_z];
				$ls_asupuncue=$data["asupuncue"][$li_z];
				$ls_fecpuncue=$io_funcion->uf_convertirfecmostrar($data["fecpuncue"][$li_z]);
				$ls_codpropuncue=$data["cod_pro"][$li_z];
				$ls_nompropuncue=$data["nompro"][$li_z];
				$ls_replegpuncue=$data["nomreppro"][$li_z];
				$ls_lapejepuncue=$io_funsob->uf_convertir_numerocadena($data["lapejepuncue"][$li_z]);
				$ls_lapejeunipuncue=$data["coduni"][$li_z];
				$ls_monnetpuncue=$io_funsob->uf_convertir_numerocadena($data["monnetpuncue"][$li_z]);
				$ls_monivapuncue=$io_funsob->uf_convertir_numerocadena($data["monivapuncue"][$li_z]);
				$ls_porivapuncue=$io_funsob->uf_convertir_numerocadena($data["porivapuncue"][$li_z]);
				$ls_monantpuncue=$io_funsob->uf_convertir_numerocadena($data["monantpuncue"][$li_z]);
				$ls_porantpuncue=$io_funsob->uf_convertir_numerocadena($data["porantpuncue"][$li_z]);
				$ls_monbrupuncue=$io_funsob->uf_convertir_numerocadena($data["monbrupuncue"][$li_z]);
				$ls_obspuncue=$data["obspuncue"][$li_z];
				$ls_desobr=$data["desobr"][$li_z];
				print "<tr class=celdas-blancas align=center>";		
				print "<td><a href=\"javascript: aceptar('$ls_codobr','$ls_codpuncue','$ls_rempuncue','$ls_despuncue','$ls_asupuncue','$ls_fecpuncue',
				'$ls_codpropuncue','$ls_nompropuncue','$ls_replegpuncue','$ls_lapejepuncue','$ls_lapejeunipuncue','$ls_monnetpuncue','$ls_monivapuncue',
				'$ls_porivapuncue','$ls_monantpuncue','$ls_porantpuncue','$ls_obspuncue','$ls_monbrupuncue');\">".$ls_codpuncue."</a></td>";
				print "<td >".$ls_codobr."</td>";
				print "<td align=left>".$ls_desobr."</td>";
				print "<td align=left>".$ls_asupuncue."</td>";
				print "<td >".$ls_fecpuncue."</td>";
				print "</tr>";			
			}
			print "</table>";
		}
		else
		  {
			$io_msg->message("No se han creado Puntos de Cuenta que cumplan con estos parámetros de búsqueda");
			print $io_funcion->uf_convertirmsg($io_sql->message);
		  }
		$io_sql->free_result($rs_data);
		$io_sql->close();
	}
}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(ls_codobr,ls_codpuncue,ls_rempuncue,ls_despuncue,ls_asupuncue,ls_fecpuncue,ls_codpropuncue,ls_nompropuncue,
				ls_replegpuncue,ls_lapejepuncue,ls_lapejeunipuncue,ls_monnetpuncue,ls_monivapuncue,ls_porivapuncue,ls_monantpuncue,
				ls_porantpuncue,ls_obspuncue,ls_monbrupuncue)
  {
    opener.ue_cargarpuntodecuenta(ls_codobr,ls_codpuncue,ls_rempuncue,ls_despuncue,ls_asupuncue,ls_fecpuncue,ls_codpropuncue,ls_nompropuncue,
				ls_replegpuncue,ls_lapejepuncue,ls_lapejeunipuncue,ls_monnetpuncue,ls_monivapuncue,ls_porivapuncue,ls_monantpuncue,
				ls_porantpuncue,ls_obspuncue,ls_monbrupuncue);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.submit();
  }
  
function ue_catobra()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_cat_obra.php?estado=''";
	window.open(pagina,'otra',"menubar=no,toolbar=no,scrollbars=yes,width=850,height=400,resizable=yes,location=no,top=40,left=40,status=yes");
}

function ue_cargarobra(ls_codigo,ls_descripcion,ls_estado,ls_codest,ls_codten,ls_codtipest,ls_codpai,ls_codmun,ls_codpar,ls_codcom,
  				         ls_codsiscon,ls_codpro,ls_codtob,ls_dirobr,ls_obsobr,ls_resobr,ld_monto,ls_feccreobr,ls_nompro,
				         ls_fechainicio,ls_fechafin,ls_nomten,ls_nomtipest,ls_nomsiscon,ls_nomtob,ls_estado,ls_codpais)
{
	f=document.form1;
	f.operacion.value="";
	f.txtcodobr.value=ls_codigo;	
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
