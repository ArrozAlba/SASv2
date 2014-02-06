<?
session_start();
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Valuaciones</title>
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
	$ls_hidestado=$_POST["hidestado"];
	$ls_estado=$_POST["cmbestado"]; 	
	$ls_operacion=$_POST["operacion"];
	$ls_codobr="%".$_POST["txtcodobr"]."%";	
	$ls_codcon="%".$_POST["txtcodcon"]."%";	
	$ls_codcont="%".$_POST["txtcodcont"]."%";
	$ls_codasi="%".$_POST["txtcodasi"]."%";
	$ld_fecval=$io_funcion->uf_convertirdatetobd($_POST["txtfecval"]);
	
}
else
{
	$ls_operacion="";
	$ls_hidestado=$_GET["estado"];	
}
?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Valuaciones</td>
    	</tr>
	 </table>
	 <br>
	 <table width="646" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="23"><div align="right"></div></td>
        <td>C&oacute;digo de la Asignaci&oacute;n:</td>
        <td><input name="txtcodasi" type="text" id="txtcodasi" size="6" maxlength="6"></td>
        <td>Fecha Valuacion:</td>
        <td><input name="txtfecval" type="text" id="txtfecval" size="11" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td height="21"><div align="right"></div></td>
        <td height="21">C&oacute;digo de la Obra:</td>
        <td><input name="txtcodobr" type="text" id="txtcodobr" size="6" maxlength="6"></td>
        <td>Codigo Contrato: </td>
        <td><input name="txtcodcon" type="text" id="txtcodcon" size="6" maxlength="6"></td>
      </tr>
      <tr>
        <td height="23">&nbsp;</td>
        <td height="23">Codigo Contratista: </td>
        <td><input name="txtcodcont" type="text" id="txtcodcont" size="10" maxlength="10"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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
	$ls_sql="SELECT v.*,a.codasi"; 
   
	if($ls_codobr!="%%")   
     {
      $ls_sql=$ls_sql." FROM sob_obra o,sob_asignacion a,sob_contrato c,sob_valuacion v
	                   WHERE o.codobr like '".$ls_codobr."' AND o.codobr=a.codobr AND a.codasi=c.codasi AND c.codcon=v.codcon";
	 }
	 else
	 {
	  if($ls_codasi!="%%")   
       {
         if($ls_codcont!="%%")
		 {
		   $ls_sql=$ls_sql." FROM rpc_proveedor p,sob_asignacion a,sob_contrato c,sob_valuacion v
                            WHERE p.cod_pro like '".$ls_codcont."' AND p.cod_pro=a.cod_pro AND a.codasi like '".$ls_codasi."' AND c.codasi=a.codasi AND c.codcon=v.codcon";
		 }
		 else
		 {
		   $ls_sql=$ls_sql." FROM sob_asignacion a,sob_contrato c,sob_valuacion v
	                        WHERE a.codasi like '".$ls_codasi."' AND a.codasi=c.codasi AND c.codcon=v.codcon";
		 }				 
	   }
	   else
	   {
	    if($ls_codcon!="%%")   
        {
	     if($ls_codcont!="%%")
		 {
		   $ls_sql=$ls_sql." FROM rpc_proveedor p,sob_asignacion a,sob_contrato c,sob_valuacion v
                            WHERE (p.cod_pro like '".$ls_codcont."' AND p.cod_pro=a.cod_pro AND c.codasi=a.codasi) AND c.codcon like '".$ls_codcon."' AND c.codcon=v.codcon";
		 }
		 else
		 {
		   $ls_sql=$ls_sql." FROM sob_contrato c,sob_valuacion v,sob_asignacion a
	                        WHERE a.codcon like '".$ls_codcon."' AND c.codcon=v.codcon AND c.codasi=a.codasi";
		 }				
       }
	   else
	   {
	    if($ls_codcont!="%%")
	    {
		  $ls_sql=$ls_sql." FROM rpc_proveedor p,sob_asignacion a,sob_contrato c,sob_valuacion v
                          WHERE p.cod_pro like '".$ls_codcont."' AND p.cod_pro=a.cod_pro AND c.codasi=a.codasi  AND c.codcon=v.codcon";

		}
		else
		{
		  $ls_sql=$ls_sql." FROM sob_valuacion v,sob_asignacion a,sob_contrato c
	                        WHERE v.codemp='".$ls_codemp."'  AND c.codasi=a.codasi  AND c.codcon=v.codcon";
		}
	   }
	   }
	 }
	
	
	if($ls_hidestado=="")
	{
       if($ls_estado!=0)
	   {
		$ls_sql=$ls_sql." AND v.estval='".$ls_estado."'";
	   }
	}
	else
	{
		$ls_sql=$ls_sql." AND v.estval<>3"; 
	}
	if ($ld_fecval!="")
	{
		$ls_sql=$ls_sql." AND v.fecha='".$ld_fecval."'";
	}
	$ls_sql=$ls_sql." ORDER BY v.codcon";
	
	
	$rs_data=$io_sql->select($ls_sql);
	$data=$rs_data;
	if($rs_data===false)
	{
		$is_msg_error="Error en select catalogo valuacion".$io_funcion->uf_convertirmsg($io_sql->message);
		print $is_msg_error;
		print $ls_sql;
	}else
	{
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$io_datastore->data=$data;
			$li_totrow=$io_datastore->getRowCount("codval");
			print "<table width=650 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
			print "<tr class=titulo-celda>";
			print "<td>Cod. Contrato</td>";
			print "<td>Cod. Valuacion</td>";
			print "<td>Fecha Valuacion.</td>";
		   	print "<td>Estado</td>";
			print "</tr>";
			for($li_z=1;$li_z<=$li_totrow;$li_z++)
			{
				print "<tr class=celdas-blancas align=center>";				
                $ls_codval=$data["codval"][$li_z];
				$ls_codcon=$data["codcon"][$li_z];
				$ls_codasi=$data["codasi"][$li_z];
				$ls_fecha=$io_funcion->uf_convertirfecmostrar($data["fecha"][$li_z]);
				$ls_fecinival=$io_funcion->uf_convertirfecmostrar($data["fecinival"][$li_z]);
				$ls_fecfinval=$io_funcion->uf_convertirfecmostrar($data["fecfinval"][$li_z]);
				$ls_obsval=$data["obsval"][$li_z];
				$ls_amoval=$data["amoval"][$li_z];					
				$ls_obsamoval=$data["obsamoval"][$li_z];
				$ls_amoantval=$data["amoantval"][$li_z];
				$ls_amototval=$data["amototval"][$li_z];
				$ls_amoresval=$data["amoresval"][$li_z];
				$ls_basimpval=$data["basimpval"][$li_z];
				$ls_montotval=$data["montotval"][$li_z];
				$ls_subtotpar=$data["subtotpar"][$li_z];
				$ls_totreten=$data["totreten"][$li_z];
				$ls_subtot=$data["subtot"][$li_z];
				$ls_estapr=$data["estapr"][$li_z];
				$ls_nomestval=$io_funobr->uf_convertir_numeroestado($data["estval"][$li_z]);
				
				print "<td><a href=\"javascript: aceptar('$ls_codval','$ls_codasi','$ls_codcon','$ls_fecha','$ls_fecinival','$ls_fecfinval','$ls_obsval','$ls_amoval','$ls_obsamoval','$ls_amoantval','$ls_amototval','$ls_amoresval','$ls_basimpval','$ls_montotval','$ls_subtotpar','$ls_totreten','$ls_subtot','$ls_nomestval','$ls_estapr');\">".$ls_codcon."</a></td>";
				print "<td>".$ls_codval."</td>";
				print "<td>".$ls_fecha."</td>";
				print "<td>".$ls_nomestval."</td>";			
				print "</tr>";			
			}
			print "</table>";
		}
		else
		  {
		    $io_msg->message("No se han Creado Valuaciones");
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
function aceptar(ls_codval,ls_codasi,ls_codcon,ls_fecha,ls_fecinival,ls_fecfinval,ls_obsval,ls_amoval,ls_obsamoval,ls_amoantval,ls_amototval,ls_amoresval,ls_basimpval,ls_montotval,ls_subtotpar,$ls_totreten,ls_subtot,ls_nomestval,ls_estapr)
  {
    opener.ue_cargarvaluacion(ls_codval,ls_codasi,ls_codcon,ls_fecha,ls_fecinival,ls_fecfinval,ls_obsval,ls_amoval,ls_obsamoval,ls_amoantval,ls_amototval,ls_amoresval,ls_basimpval,ls_montotval,ls_subtotpar,$ls_totreten,ls_subtot,ls_nomestval,ls_estapr);
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_valuacion.php";
  f.submit();
  }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
</html>
