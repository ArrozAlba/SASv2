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
<title>Cat&aacute;logo de Contratos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
	$ls_tipoacta=$_POST["hidtipoacta"]; 
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_desobr="%".$_POST["txtdesobr"]."%";	
	$ls_codcon="%".$_POST["txtcodcon"]."%";
	$ls_codasi="%".$_POST["txtcodasi"]."%";
	$ld_feccrecon=$io_funcion->uf_convertirdatetobd($_POST["txtfeccrecon"]);
	$ld_fecinicon=$io_funcion->uf_convertirdatetobd($_POST["txtfecinicon"]);
}
else
{
	$ls_operacion="";
	$ls_tipoacta=$_GET["tipoacta"];
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Contratos</td>
    	</tr>
	 </table>
	 <br>
	 <table width="646" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="60" height="26"><div align="right"></div></td>
        <td width="118"><div align="right">C&oacute;digo del Contrato</div></td>
        <td width="128"><input name="txtcodcon" id="txtcodcon" type="text" size="15" maxlength="12" ></td>
        <td width="3"><div align="right"></div></td>
        <td width="161"><div align="right">Descripci&oacute;n de la Obra</div></td>
        <td width="174"><input name="txtdesobr" type="text" id="txtdesobr" size="30" maxlength="254"></td>
      </tr>
      <tr>
        <td height="27"><div align="right"></div></td>
        <td height="27"><div align="right">C&oacute;digo de la Asignaci&oacute;n</div></td>
        <td><input name="txtcodasi" type="text" id="txtcodasi" size="15" maxlength="12"></td>
        <td><div align="right"></div></td>
        <td><div align="right">Fecha de Creaci&oacute;n del Contrato</div></td>
        <td><input name="txtfeccrecon"  id="txtfeccrecon" type="text" size="11" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td height="29">&nbsp;</td>
        <td height="29"><div align="right">C&oacute;digo de la Obra</div></td>
        <td><input name="txtcodobr" type="text" id="txtcodobr" size="15" maxlength="12"></td>
        <td>&nbsp;</td>
        <td><div align="right">Fecha de Inicio del Contrato</div></td>
        <td><input name="txtfecinicon" type="text" id="txtinicon" size="11" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<input type="hidden" name="hidtipoacta" id="hidtipoacta"value="<? print $ls_tipoacta?>">
	<br>
<?

if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT c.codcon, c.precon,a.codobr, a.cod_pro, a.cod_pro_ins,c.codasi,o.desobr,c.feccon,c.fecinicon,c.estcon,c.estspgscg
			FROM sob_contrato c,sob_asignacion a,sob_obra o, sob_tipocontrato t
			WHERE c.codemp='".$ls_codemp."' AND a.codemp=c.codemp AND o.codemp=c.codemp AND c.codasi=a.codasi AND a.codobr=o.codobr AND c.codtco=t.codtco
			AND o.codobr LIKE '".$ls_codobr."'	AND  o.desobr LIKE '".$ls_desobr."' AND c.codcon LIKE '".$ls_codcon."'
			AND a.codasi LIKE '".$ls_codasi."' AND c.estspgscg=1";
	if ($ld_feccrecon!="")
	{
		$ls_sql=$ls_sql." AND c.feccon='".$ld_feccrecon."'";
	}
	
	if ($ld_fecinicon)
	{
		$ls_sql=$ls_sql." AND c.fecinicon='".$ld_fecinicon."'";
	}
/*	if($ls_tipoacta=="1")//Inicio
	{
		$ls_sql=$ls_sql." AND c.ultactcon=0 ";//si no tiene ningun acta asociada y el contrato esta contabilizado
	}*/
	elseif($ls_tipoacta=="2")//Finalización	
	{
		$ls_sql=$ls_sql." AND (c.ultactcon=1 OR c.ultactcon=6 OR c.ultactcon=7) AND (c.estcon=7 OR c.estcon=9 OR c.estcon=10 OR c.estcon=11)"; //Si tiene acta de inicio, de reanudación o de prorroga y el contrato: Paralizado
																																				//o en prorroga, o onocoado, en prorrogaparalizado
	}
	elseif($ls_tipoacta=="3")//Recepcion Provisional	
	{
		$ls_sql=$ls_sql." AND c.ultactcon=2 AND c.estcon=8";//Si tiene acta de finalizacion y el contrato esta finalizado
	}
	elseif($ls_tipoacta=="4")//Recepcion Definitiva
	{
		$ls_sql=$ls_sql." AND (c.ultactcon=2 OR c.ultactcon=3 ) AND c.estcon=8";//Si tiene acta de finalizacion o recepcion provisional y el contrato esta finalizado
	}
	elseif($ls_tipoacta=="5")//Paralizacion	
	{
		$ls_sql=$ls_sql." AND (c.ultactcon=1 OR c.ultactcon=6 OR c.ultactcon=7) AND (c.estcon=9 OR c.estcon=10) ";//Si tiene acta de Inicio,Reanudacion o prorroga y el contrato esta iniciado o en prorroga
	}
	elseif($ls_tipoacta=="6")//Reanudacion
	{
		$ls_sql=$ls_sql." AND c.ultactcon=5 AND (c.estcon=7 OR c.estcon=11)";//Si tiene acta de paralizacion y el contrato esta paralizado o en prorroga paralizado
	}
	elseif($ls_tipoacta=="7")//Prórroga
	{
		$ls_sql=$ls_sql." AND (c.ultactcon=1 OR c.ultactcon=6) AND c.estcon=10";//Si tiene acta de Inicio,reanudacion
	}
	
	$ls_sql=$ls_sql." ORDER BY c.codcon";	
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
			$li_totrow=$io_datastore->getRowCount("codcon");
			print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td>Cod. Cont.</td>";
			print "<td>Cod. Asig</td>";
			print "<td>Cod. Obra</td>";
			print "<td>Descripción Obra</td>";
			print "<td>Fecha Creación Cont.</td>";
			print "<td>Fecha Inicio Cont.</td>";
			print "<td>Estado</td>";
			print "<td>Contabilizado</td>";
			print "</tr>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{
				print "<tr class=celdas-blancas align=center>";				
				$ls_codigo=$data["codcon"][$li_z];
				$ls_desobr=$data["desobr"][$li_z];
				$ls_estado=$io_funobr->uf_convertir_numeroestado($data["estcon"][$li_z]);
				$ls_codest=$data["estcon"][$li_z];
				$ls_codobr=$data["codobr"][$li_z];				
				$ls_codasi=$data["codasi"][$li_z];
				$ls_codpro=$data["cod_pro"][$li_z];
				$ls_codproins=$data["cod_pro_ins"][$li_z];
				$ls_precon=$data["precon"][$li_z];
				$ls_estspgscg=$data["estspgscg"][$li_z];
				$ls_feccrecon=$io_funcion->uf_convertirfecmostrar($data["feccon"][$li_z]);
				$ls_fecinicon=$io_funcion->uf_convertirfecmostrar($data["fecinicon"][$li_z]);				
				print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_desobr','$ls_estado','$ls_codest','$ls_codasi','$ls_feccrecon','$ls_fecinicon','$ls_codobr','$ls_codpro','$ls_codproins','$ls_precon');\">".$ls_precon.$ls_codigo."</a></td>";
				print "<td>".$ls_codasi."</td>";
				print "<td>".$ls_codobr."</td>";
				print "<td align=left>".$ls_desobr."</td>";
				print "<td>".$ls_feccrecon."</td>";
				print "<td>".$ls_fecinicon."</td>";			
				print "<td>".$ls_estado."</td>";	
				if($ls_estspgscg==0)
					print "<td></td>";			
				else
					print "<td><img src=../shared/imagebank/aprobado.gif width=15 height=15 border=0></td>";	
				print "</tr>";			
			}
			print "</table>";
		}
		else
		  {
			$io_msg->message("No se han creado Contratos que cumplan con estos parámetros de búsqueda");
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
function aceptar(ls_codigo,ls_desobr,ls_estado,ls_codest,ls_codasi,ls_feccrecon,ls_fecinicon,ls_codobr,ls_codpro,ls_codproins,ls_precon)
  {
    opener.ue_cargarcontrato(ls_codigo,ls_desobr,ls_estado,ls_codest,ls_codasi,ls_feccrecon,ls_fecinicon,ls_codobr,ls_codpro,ls_codproins,ls_precon);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_contratoactas.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
