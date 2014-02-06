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
	$ls_campo="a.codant";
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
<title>Cat&aacute;logo de Anticipos</title>
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
	$ls_estado=$_POST["hidestado"]; 
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
	if($ls_estado=="")
		$ls_estant=$_POST["cmbestado"];
	else
		$ls_estant=$ls_estado;		
	$ls_codcon="%".$_POST["txtcodcon"]."%";
	$ls_codant="%".$_POST["txtcodant"]."%";
	$ld_fecintant=$io_funcion->uf_convertirdatetobd($_POST["txtfecintant"]);	
	
	$ls_codigoobr=$_POST["txtcodobr"];	
	$ls_descripcionobr=$_POST["txtdesobr"];	
	$ls_estadoant=$_POST["cmbestado"];
	$ls_codigocon=$_POST["txtcodcon"];
	$ls_codigoant=$_POST["txtcodant"];
	$ld_fechaintant=$_POST["txtfecintant"];	
}
else
{
	$ls_operacion="";
	$ls_estado=$_GET["estado"];
	$ls_codigoobr="";
	$ls_descripcionobr="";
	$ls_estadoant="";
	$ls_codigocon="";
	$ls_codigoant="";
	$ld_fechaintant="";
	

}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Anticipos </td>
    	</tr>
	 </table>
	 <br>
	 <table width="646" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="60" height="26"><div align="right"></div></td>
        <td width="118"><div align="right">C&oacute;digo del Anticipo</div></td>
        <td width="128"><input name="txtcodant" type="text" id="txtcodant" value="<? print $ls_codigoant;?>" size="6" maxlength="6" ></td>
        <td width="3"><div align="right"></div></td>
        <td width="126"><div align="right">Descripci&oacute;n de la Obra</div></td>
        <td width="209"><input name="txtdesobr" type="text" id="txtdesobr" value="<? print $ls_descripcionobr;?>" size="30" maxlength="254"></td>
      </tr>
      <tr>
        <td height="27"><div align="right"></div></td>
        <td height="27"><div align="right">C&oacute;digo del Contrato</div></td>
        <td><input name="txtcodcon" type="text" id="txtcodcon" value="<? print $ls_codigocon;?>" size="6" maxlength="6"></td>
        <td><div align="right"></div></td>
        <td><div align="right">Fecha del Anticipo</div></td>
        <td><input name="txtfecintant" type="text"  id="txtfecintant" value="<? print $ld_fechaintant;?>" size="11" maxlength="10" datepicker="true" onKeyPress="return validaCajas(this,'n',event)"></td>
      </tr>
      <tr>
        <td height="29">&nbsp;</td>
        <td height="29"><div align="right">C&oacute;digo de la Obra </div></td>
        <td><input name="txtcodobr" type="text" id="txtcodobr" value="<? print $ls_codigoobr;?>" size="6" maxlength="6"></td>
        <td>&nbsp;</td>
        <td><div align="right">Estado del Anticipo</div></td>
        <td><?Php
			if ($ls_estado=="5")
			{
			?>
          <select name="cmbestado" id="cmbestado">
            <option value="" >Seleccione...</option>
            <option value="5">Contabilizado</option>
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
			if($ls_estadoant!="")
			{
				print "<script>";
					print "document.form1.cmbestado.value='$ls_estadoant';";
				print "</script>";
			}
			?></td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<input type="hidden" name="hidestado" id="hidestado"value="<? print $ls_estado?>">
<?

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT a.codcon,a.codant,a.fecant,a.fecintant,a.porant,a.monto,a.fecant,
			a.conant,a.montotant,a.sc_cuenta as cuenta,a.estant,o.desobr,o.codobr,a.estspgscg,c.monto as montocontrato,a.estgenrd
			FROM sob_anticipo a,sob_obra o,sob_contrato c,sob_asignacion ag
			WHERE a.codemp='".$ls_codemp."' AND a.codemp=o.codemp AND o.codemp=c.codemp AND c.codemp=ag.codemp AND a.codcon=c.codcon AND c.codasi=ag.codasi
			AND ag.codobr=o.codobr AND a.codcon like '".$ls_codcon."' AND a.codant like '".$ls_codant."' AND o.desobr like '".$ls_desobr."' AND a.estant<>3";
	if ($ld_fecintant!="")
	{
		$ls_sql=$ls_sql." AND a.fecintant='".$ld_fecintant."'";
	}
	if ($ls_estant!="")
	{
		$ls_sql=$ls_sql." AND a.estant='".$ls_estant."'";
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
		print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td><a href=javascript:ue_ordenar('a.codant','BUSCAR');><font color=#FFFFFF>Cod. Ant.</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('a.codcon','BUSCAR');><font color=#FFFFFF>Cod. Cont.</font></a></td>";			
		print "<td><a href=javascript:ue_ordenar('o.codobr','BUSCAR');><font color=#FFFFFF>Cod. Obra</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('o.desobr','BUSCAR');><font color=#FFFFFF>Descripción Obra</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('a.fecant','BUSCAR');><font color=#FFFFFF>Fecha Ant.</font></a></td>";
		print "<td><a href=javascript:ue_ordenar('a.estant','BUSCAR');><font color=#FFFFFF>Estado</font></a></td>";
		print "<td><font color=#FFFFFF>Contabilización</font></td>";
		print "</tr>";
		print "<br>";
		while($row=$io_sql->fetch_row($rs_data))
		{
			//$data=$io_sql->obtener_datos($rs_data);
			//$io_datastore->data=$data;
			//$li_totrow=$io_datastore->getRowCount("codcon");
			//for($li_z=1;$li_z<=$li_totrow;$li_z++)
			//{
				print "<tr class=celdas-blancas align=center>";		
				$ls_codcon=$row["codcon"];
				$ls_desobr=$row["desobr"];
				$ls_estado=$io_funobr->uf_convertir_numeroestado($row["estant"]);
				$ls_codest=$row["estant"];
				$ls_codobr=$row["codobr"];
				$ld_monto=$row["monto"];
				$ls_codant=$row["codant"];
				$ls_fecintant=$io_funcion->uf_convertirfecmostrar($row["fecintant"]);
				$ld_porant=$row["porant"];
				$ls_conant=$row["conant"];
				$ld_montotant=$row["montotant"];
				$ls_cuenta=$row["cuenta"];
				$ls_estspgscg=$row["estspgscg"];
				$ld_montocontrato=$row["montocontrato"];
				$ls_fecant=$io_funcion->uf_convertirfecmostrar($row["fecant"]);
				$ls_estgenrd=$row["estgenrd"];
				print "<td><a href=\"javascript: aceptar('$ls_codcon','$ls_desobr','$ls_estado','$ls_codest','$ls_codobr','$ld_monto',
				'$ls_codant','$ls_fecintant','$ld_porant','$ls_conant','$ld_montotant','$ls_cuenta','$ls_fecant','$ld_montocontrato','$ls_estgenrd');\">".$ls_codant."</a></td>";
				print "<td>".$ls_codcon."</td>";
				print "<td>".$ls_codobr."</td>";
				print "<td align=left>".$ls_desobr."</td>";
				print "<td>".$ls_fecintant."</td>";
				print "<td>".$ls_estado."</td>";		
				if	($ls_estspgscg==0)
					print "<td></td>";		
				else
					print "<td><img src=../shared/imagebank/aprobado.gif width=15 height=15 border=0></td>";	
			//}
		}
			print "</tr>";			
			print "</table>";
/*		else
		  {
			$io_msg->message("No se han creado Anticipos que cumplan con estos parámetros de búsqueda");
			print $io_funcion->uf_convertirmsg($io_sql->message);
		  }*/
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
function aceptar(ls_codcon,ls_desobr,ls_estado,ls_codest,ls_codobr,$ld_monto,ls_codant,ls_fecintant,ld_porant,ls_conant,
				 ld_montotant,ls_cuenta,ls_fecant,ld_montocontrato,ls_estgenrd)
  {
    opener.ue_cargaranticipo(ls_codcon,ls_desobr,ls_estado,ls_codest,ls_codobr,$ld_monto,ls_codant,ls_fecintant,ld_porant,
							 ls_conant,ld_montotant,ls_cuenta,ls_fecant,ld_montocontrato,ls_estgenrd);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_anticipo.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
