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
	$ls_campo="a.codasi";
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
<title>Cat&aacute;logo de Asignaciones</title>
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
$io_funobr= new sigesp_sob_c_funciones_sob(); 
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
	$ls_hidestado=$_POST["hidestado"];
	$ls_origen=$_POST["hidorigen"];	
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
	$ls_codcont="%".$_POST["txtcodcont"]."%";
	$ls_descont="%".$_POST["txtdescont"]."%";		
	$ls_codasi="%".$_POST["txtcodasi"]."%";
	$ld_fecasi=$io_funcion->uf_convertirdatetobd($_POST["txtfecasi"]);
	$ls_estado=$_POST["cmbestado"]; 		
	$ls_codigoobr=$_POST["txtcodobr"];	
	$ls_descripcionobr=$_POST["txtdesobr"];	
	$ls_codigocont=$_POST["txtcodcont"];
	$ls_desecripcioncont=$_POST["txtdescont"];		
	$ls_codigoasi=$_POST["txtcodasi"];
	$ld_fechaasi=$_POST["txtfecasi"];
	
}
else
{
	$ls_operacion="";
	$ls_origen=$_GET["hidorigen"];
	$ls_hidestado=$_GET["estado"];	
	$ld_fecasi="";
	$ls_estado="";
	$ls_codigoobr="";
	$ls_descripcionobr="";
	$ls_codigocont="";
	$ls_desecripcioncont="";
	$ls_codigoasi="";
	$ld_fechaasi="";
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hidorigen" type="hidden" id="hidorigen" value="<?php print $ls_origen ?>">
  </p>
  	 <table width="800" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="800" colspan="2" class="titulo-celda">Cat&aacute;logo de Asignaciones</td>
    	</tr>
	 </table>
	 <br>
	 <table width="800" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="26"><div align="right"></div></td>
        <td>C&oacute;digo de la Asignaci&oacute;n:</td>
        <td><input name="txtcodasi" type="text" id="txtcodasi" value="<? print $ls_codigoasi;?>" size="6" maxlength="6"></td>
        <td>Fecha Asignacion:</td>
        <td><input name="txtfecasi" type="text" id="txtfecasi" value="<? print $ld_fechaasi;?>" size="11" maxlength="10" datepicker="true" onKeyPress="return validaCajas(this,'n',event)"></td>
      </tr>
      <tr>
        <td height="27"><div align="right"></div></td>
        <td height="27">C&oacute;digo de la Obra:</td>
        <td><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codigoobr;?>" size="6" maxlength="6"></td>
        <td>Descripci&oacute;n de la Obra: </td>
        <td><input name="txtdesobr" type="text" id="txtdesobr" value="<? print $ls_descripcionobr;?>" size="40" maxlength="254"></td>
      </tr>
      <tr>
        <td height="29">&nbsp;</td>
        <td height="29">Codigo Contratista: </td>
        <td><input name="txtcodcont" type="text" id="txtcodcont" value="<? print $ls_codigocont;?>" size="10" maxlength="10"></td>
        <td>Descripci&oacute;n del Contratista: </td>
        <td><input name="txtdescont" type="text" id="txtdescont" value="<? print $ls_desecripcioncont;?>" size="40" maxlength="100"></td>
      </tr>
      <tr>
        <td width="47" height="29"><div align="right"></div></td>
        <td width="118" height="29">Estado de la Asignacion: </td>
        <td width="99"><div align="left">
		<?Php
		if($ls_hidestado=="")
		{
		?>
    	<select name="cmbestado" id="cmbestado">
           			<option value="0" >Seleccione...</option>
            		<option value="1">Emitido</option>
					<option value="4">Contratada</option>
					<option value="5">Contabilizado</option>
					<option value="6">Modificado</option>
					<option value="3">Anulado</option>
		  </select>
		<?Php
		}
		else
		{
		?>
		<select name="cmbestado" id="cmbestado">
           			<option value="" >Seleccione...</option>
            		<option value="1">Emitido</option>
					<option value="5">Contabilizado</option>
					<option value="6">Modificado</option>					
		  </select>
		<?Php
		}
		if($ls_estado!="")
		{
			print "<script>";
				print "document.form1.cmbestado.value='$ls_estado';";
			print "</script>";
		}
		?>		
	    </div></td>
        <td width="138">&nbsp;</td>
        <td width="242">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<input name="hidestado" id="hidestado" type="hidden" value="<? print $ls_hidestado;?>">
	
<?

if($ls_operacion=="BUSCAR")
{
	$ls_straux='';
	if ($ls_origen=='DC')
	{
	$ls_straux="AND a.estspgscg ='1'";
	}
		$ls_sql="SELECT a.codasi,a.codobr,a.cod_pro,a.cod_pro_ins,a.puncueasi,a.fecasi,a.obsasi,a.monparasi,
	     		a.basimpasi,a.montotasi,a.estasi,o.desobr,p.nompro, a.estapr
		    	FROM sob_asignacion a,sob_obra o, rpc_proveedor p
			    WHERE a.codemp='".$ls_codemp."' AND o.codemp='".$ls_codemp."' AND p.codemp='".$ls_codemp."' AND a.codobr=o.codobr AND a.cod_pro=p.cod_pro AND o.codobr LIKE '".$ls_codobr."' AND  o.desobr LIKE '".$ls_desobr."' AND p.cod_pro LIKE '".$ls_codcont."' AND p.nompro LIKE '".$ls_descont."'
			    AND a.codasi LIKE '".$ls_codasi."' $ls_straux";
	 if($ls_hidestado=="")
	{
       if($ls_estado!=0)
	   {
		$ls_sql=$ls_sql." AND a.estasi='".$ls_estado."'";
	   }
	}
	else
	{
		$ls_sql=$ls_sql." AND a.estasi<>3 AND a.estasi<>4"; 
	}
	if ($ld_fecasi!="")
	{
		$ls_sql=$ls_sql." AND a.fecasi='".$ld_fecasi."'";
	}
	$ls_sql=$ls_sql." ORDER BY $ls_campo $ls_orden";
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select catalogo contrato".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
	}else
	{
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$io_datastore->data=$data;
			$li_totrow=$io_datastore->getRowCount("codasi");
			print "<table width=800 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td><a href=javascript:ue_ordenar('a.codasi','BUSCAR');><font color=#FFFFFF>Cod. Asig.</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('a.codobr','BUSCAR');><font color=#FFFFFF>Cod. Obra</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('o.desobr','BUSCAR');><font color=#FFFFFF>Descripción Obra</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('a.fecasi','BUSCAR');><font color=#FFFFFF>Fecha Asignacion.</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('a.cod_pro','BUSCAR');><font color=#FFFFFF>Cod. Cont.</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('p.nompro','BUSCAR');><font color=#FFFFFF>Descripción Cont.</font></a></td>";
			print "<td><a href=javascript:ue_ordenar('a.estasi','BUSCAR');><font color=#FFFFFF>Estado</font></a></td>";
			print "</tr>";
			print "<br>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{
				print "<tr class=celdas-blancas align=center>";				
                $ls_codasi=$data["codasi"][$li_z];
				$ls_estapr=$data["estapr"][$li_z];
				$ls_codobr=$data["codobr"][$li_z];
				$ls_codpro=$data["cod_pro"][$li_z];
				$ls_codproins=$data["cod_pro_ins"][$li_z];
				$ld_puncue=$data["puncueasi"][$li_z];
				$ls_obsasi=$data["obsasi"][$li_z];				
				$ls_monparasi=$data["monparasi"][$li_z];
				$ls_basimpasi=$data["basimpasi"][$li_z];
				$ls_montotasi=$data["montotasi"][$li_z];
				$ls_estasi=$data["estasi"][$li_z];
				$ls_nomestasi=$io_funobr->uf_convertir_numeroestado($ls_estasi);
				$ls_desobr=$data["desobr"][$li_z];
				$ls_nompro=$data["nompro"][$li_z];
				$ls_fecasi=$io_funcion->uf_convertirfecmostrar($data["fecasi"][$li_z]);
				switch($ls_origen)
				{
					case"DC":
						print "<td><a href=\"javascript: aceptar_dc('$ls_codasi','$ls_codobr','$ls_codpro','$ls_codproins','$ld_puncue','$ls_obsasi',
						'$ls_monparasi','$ls_basimpasi','$ls_montotasi','$ls_estasi','$ls_desobr','$ls_nompro','$ls_fecasi');\">".$ls_codasi."</a></td>";
						print "<td>".$ls_codobr."</td>";
						print "<td align=left>".$ls_desobr."</td>";
						print "<td>".$ls_fecasi."</td>";
						print "<td>".$ls_codpro."</td>";			
						print "<td align=left>".$ls_nompro."</td>";
						if (($ls_nomestasi=="EMITIDO")||($ls_nomestasi=="MODIFICADO"))
						{	
						print "<td>".$ls_nomestasi."-CONTABILIZADO"."</td>";			
						}
						else
						{
						print "<td>".$ls_nomestasi."</td>";			
						}
						print "</tr>";			
					break;
					default:
						print "<td><a href=\"javascript: aceptar('$ls_codasi','$ls_codobr','$ls_codpro','$ls_codproins','$ld_puncue','$ls_obsasi',
						'$ls_monparasi','$ls_basimpasi','$ls_montotasi','$ls_estasi','$ls_desobr','$ls_nompro','$ls_fecasi','$ls_estapr');\">".$ls_codasi."</a></td>";
						print "<td>".$ls_codobr."</td>";
						print "<td align=left>".$ls_desobr."</td>";
						print "<td>".$ls_fecasi."</td>";
						print "<td>".$ls_codpro."</td>";			
						print "<td align=left>".$ls_nompro."</td>";	
						print "<td>".$ls_nomestasi."</td>";			
						print "</tr>";			
					break;
				}
			}
			print "</table>";
		}
		else
		  {
		    $io_msg->message("No se han Creado Asignaciones");
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
function aceptar(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ld_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,ls_nompro,ls_fecasi,ls_estapr)
  {
    opener.ue_cargarasignacion(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ld_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,ls_nompro,ls_fecasi,ls_estapr);
	close();
  }
function aceptar_dc(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ld_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,ls_nompro,ls_fecasi)
  {
	opener.document.form1.hidfecasi.value=ls_fecasi;
    opener.ue_cargarasignacion(ls_codasi,ls_codobr,ls_codpro,ls_codproins,ld_puncue,ls_obsasi,ls_monparasi,ls_basimpasi,ls_montotasi,ls_estasi,ls_desobr,ls_nompro,ls_fecasi);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_asignacion.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
