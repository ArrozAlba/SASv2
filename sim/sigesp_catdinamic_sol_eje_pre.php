<?php
session_start();
	function uf_validar_fecha ($ld_desde,$ld_hasta)
	{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_validar_fecha
	//	Access:    public
	//	Arguments:
	//  ls_desde // fecha de inicio
	//  ls_hasta // fecha de cierre
	//	Description:  Esta funcion valida que los periodos no esten solapados.
	//              
	//////////////////////////////////////////////////////////////////////////////		
		$lb_fechavalida=false;
		$io_msg= new class_mensajes();
		
		if(($ld_desde=="")and($ld_hasta==""))
		{
			$lb_fechavalida=false;
		}
		else
		{
			$ld_diad= substr($ld_desde,0,2);
			$ld_mesd= substr($ld_desde,3,2);
			$ld_anod= substr($ld_desde,6,4);
			$ld_diah= substr($ld_hasta,0,2);
			$ld_mesh= substr($ld_hasta,3,2);
			$ld_anoh= substr($ld_hasta,6,4);
			
			if($ld_anod<$ld_anoh)
			{$lb_fechavalida=true;}
			elseif($ld_anod==$ld_anoh)
			{
				if($ld_mesd<$ld_mesh)
				{$lb_fechavalida=true;}
				elseif($ld_mesd==$ld_mesh)
				{
					if($ld_diad<=$ld_diah)
					{$lb_fechavalida=true;}
				}
			}
			if($lb_fechavalida==false)
			{
				$io_msg->message("El rango de fechas es invalido");
			}
		}
		return $lb_fechavalida;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Solicitud de Ejecuci&oacute;n Presupuestaria </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 36px}
-->
</style>
</head>

<body>
<table width="493" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="489" colspan="2" class="titulo-celda">Cat&aacute;logo de Solicitudes de Ejecuci&oacute;n Presupuestaria </td>
  </tr>
</table>
<br>

<form name="form1" method="post" action="">
<table width="489" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="88"><div align="left"></div></td>
    <td>      <input name="operacion" type="hidden" id="operacion2"></td>
    <td>&nbsp;</td>
    <td width="156" rowspan="4"><table width="148" height="61" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="2"><div align="center" class="titulo-conect">Fecha de Solicitud </div></td>
      </tr>
      <tr>
        <td width="45">
          <div align="right">Desde</div></td>
        <td width="101" height="22"><input name="txtdesde" type="text" id="txtfecdes6" onBlur="valFecha(document.form1.txtfecdes)" size="15" maxlength="10" datepicker="true"></td>
      </tr>
      <tr>
        <td>
          <div align="right">Hasta </div></td>
        <td height="22"><input name="txthasta" type="text" id="txtfechas6" onBlur="valFecha(document.form1.txtfechas)" size="15" maxlength="10" datepicker="true"></td>
      </tr>
    </table></td>
    <td width="71">&nbsp;</td>
  </tr>
  <tr>
    <td height="14"><div align="right">C&oacute;digo</div></td>
    <td height="22"><input name="txtnumsol" type="text" id="txtnumsol3" size="20" maxlength="15"></td>
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="19"><div align="right">Departamento</div></td>
    <td width="150"><input name="txtcoddep" type="text" id="txtcoddep" size="30"></td>
    <td width="22" height="22"><div align="right">
      <input name="txtnombre" type="hidden" id="txtnombre4" value="<?php print $ls_nombre ?>" size="30" maxlength="30">
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td colspan="2" align="center"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()">Buscar</a></div></td>
    </tr>
</table>
<p align="center">
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_funciones.php");
	$in     =new sigesp_include();
	$con    =$in->uf_conectar();
	$io_msg =new class_mensajes();
	$ds     =new class_datastore();
	$io_sql =new class_sql($con);
	$io_fun= new class_funciones();
	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];

	
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_numsol="%".$_POST["txtnumsol"]."%";
		$ls_coddep="%".$_POST["txtcoddep"]."%";
	}
	else
	{
		$ls_operacion="";
	
	}
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código de Solicitud</td>";
	print "<td>Fecha</td>";
	print "<td>Monto</td>";
	print "<td>Unidad</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		if (array_key_exists("txtdesde",$_POST))
		{
			$ls_desde=$_POST["txtdesde"];
			if (array_key_exists("txthasta",$_POST))
			{
				$ls_hasta=$_POST["txthasta"];
	
				$lb_fechavalida = uf_validar_fecha($ls_desde,$ls_hasta);//Funcion que valida que los periodos no esten solapados.
				if($lb_fechavalida)
				{
					$ls_desde=$io_fun->uf_convertirdatetobd($ls_desde);
					$ls_hasta=$io_fun->uf_convertirdatetobd($ls_hasta);
					$ls_sql=" SELECT sep_solicitud.*,".
							"       (SELECT denuniadm FROM spg_unidadadministrativa".
							"         WHERE sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm".
							"  FROM sep_solicitud ".
							" WHERE sep_solicitud.codemp='".$ls_codemp."'".
							"   AND sep_solicitud.numsol  ilike '".$ls_numsol."' ".
							"   AND sep_solicitud.fecregsol>='".$ls_desde."'".
							"   AND sep_solicitud.fecregsol<='".$ls_hasta."'". 
							"   AND sep_solicitud.coduniadm ilike '".$ls_coddep."'".
							"   AND (((sep_solicitud.estsol = 'E') AND (sep_solicitud.estapro = '1'))".
							"          OR (sep_solicitud.estsol = 'P')".
							"          OR (sep_solicitud.estsol = 'L'))".
							"   AND sep_solicitud.numsol IN (SELECT numsol FROM sep_dt_articulos".
							"                                 WHERE sep_solicitud.numsol=sep_dt_articulos.numsol)".
							" ORDER BY sep_solicitud.numsol";

					$rs_cta=$io_sql->select($ls_sql);
					$data=$rs_cta;
				
				
				}// $lb_fechavalida
				else
				{
					$ls_sql=" SELECT sep_solicitud.*,".
							"       (SELECT denuniadm FROM spg_unidadadministrativa".
							"         WHERE sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm) AS denuniadm".
							"  FROM sep_solicitud ".
							" WHERE sep_solicitud.codemp='".$ls_codemp."'".
							"   AND sep_solicitud.numsol  ilike '".$ls_numsol."' ".
							"   AND sep_solicitud.coduniadm ilike '".$ls_coddep."'".
							"   AND (((sep_solicitud.estsol = 'E') AND (sep_solicitud.estapro = '1'))".
							"          OR (sep_solicitud.estsol = 'P')".
							"          OR (sep_solicitud.estsol = 'L'))".
							"   AND sep_solicitud.numsol IN (SELECT numsol FROM sep_dt_articulos".
							"                                 WHERE sep_solicitud.numsol=sep_dt_articulos.numsol)".
							" ORDER BY sep_solicitud.numsol";
					$rs_cta=$io_sql->select($ls_sql);
					$data=$rs_cta;
				}
			}
		}
		$rs_cta=$io_sql->select($ls_sql);
		$data=$rs_cta;
		if($row=$io_sql->fetch_row($rs_cta))
		{
			$data=$io_sql->obtener_datos($rs_cta);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
	
			$totrow=$ds->getRowCount("numsol");
		
			for($z=1;$z<=$totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_numsol=    $data["numsol"][$z];
				$ls_fecha=     $data["fecregsol"][$z];
				$ls_fecsol=    $io_fun->uf_convertirfecmostrar($ls_fecha);
				$ls_monto=     $data["monto"][$z];
				$ls_coduniadm= $data["coduniadm"][$z];
				$ls_denuniadm= $data["denuniadm"][$z];
				$ls_estsol= $data["estsol"][$z];
				$ls_monto=number_format($ls_monto,2,",",".");
				print "<td><a href=\"javascript: aceptar('$ls_numsol','$ls_fecsol','$ls_monto','$ls_coduniadm','$ls_denuniadm','$ls_estsol');\">".$ls_numsol."</a></td>";
				print "<td>".$ls_fecsol."</td>";
				print "<td>".$ls_monto."</td>";
				print "<td>".$data["denuniadm"][$z]."</td>";
				print "</tr>";			
			}
		}
		else
		{   
			print ($io_sql->message);
			$io_msg->message("No hay registros");
		}
		
	}
	print "</table>";
?>      
</p>
</form>      
</body>

<script language="JavaScript">
function aceptar(ls_numsol,ls_fecsol,ls_monto,ls_coduniadm,ls_denuniadm,ls_estsol)
{    
	opener.document.form1.txtnumsol.value= ls_numsol;
	opener.document.form1.txtcoduniadm.value= ls_coduniadm;
	opener.document.form1.txtdenuniadm.value= ls_denuniadm;
	opener.document.form1.txtcodunides.value= ls_coduniadm;
	opener.document.form1.txtdenunides.value= ls_denuniadm;
	opener.document.form1.txtestsol.value= ls_estsol;
	opener.document.form1.operacion.value="BUSCARDETALLESOLICITUD";
	opener.document.form1.submit();
	close();
}
function ue_search()
{
	f=document.form1;
	f.operacion.value= "BUSCAR";
	f.action="sigesp_catdinamic_sol_eje_pre.php";
	f.submit();
}

function valSep(oTxt)
{ 
	var bOk = false; 
	var sep1 = oTxt.value.charAt(2); 
	var sep2 = oTxt.value.charAt(5); 
	bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
	bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
	return bOk; 
} 		
function finMes(oTxt)
{ 
	var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
	var nAno = parseInt(oTxt.value.substr(6), 10); 
	var nRes = 0; 
	switch (nMes)
	{ 
	 case 1: nRes = 31; break; 
	 case 2: nRes = 28; break; 
	 case 3: nRes = 31; break; 
	 case 4: nRes = 30; break; 
	 case 5: nRes = 31; break; 
	 case 6: nRes = 30; break; 
	 case 7: nRes = 31; break; 
	 case 8: nRes = 31; break; 
	 case 9: nRes = 30; break; 
	 case 10: nRes = 31; break; 
	 case 11: nRes = 30; break; 
	 case 12: nRes = 31; break; 
	} 
return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
} 		
function valDia(oTxt)
{ 
   var bOk = false; 
   var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
   bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
   return bOk; 
} 		
function valMes(oTxt)
{ 
	var bOk = false; 
	var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
	bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
	return bOk; 
} 		
function valAno(oTxt)
{ 
	var bOk = true; 
	var nAno = oTxt.value.substr(6); 
	bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
	if (bOk)
	{ 
	 for (var i = 0; i < nAno.length; i++)
	 { 
	   bOk = bOk && esDigito(nAno.charAt(i)); 
	 } 
	} 
 return bOk; 
 } 		
 function valFecha(oTxt)
 { 
	var bOk = true; 
	if (oTxt.value != "")
	{ 
	  bOk = bOk && (valAno(oTxt)); 
	  bOk = bOk && (valMes(oTxt)); 
	  bOk = bOk && (valDia(oTxt)); 
	  bOk = bOk && (valSep(oTxt)); 
	  if (!bOk)
	  { 
	   alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
	   oTxt.value = "01/01/1900"; 
	   oTxt.focus(); 
	  } 
	}
}
function esDigito(sChr)
{   
  var sCod = sChr.charCodeAt(0); 
  return ((sCod > 47) && (sCod < 58)); 
}

function ue_clear()
{
	f=document.form1;
	f.operacion.value="";
	f.cmbestatus[0].checked;
	f.txtpro.value="";
	f.txtben.value="";
	f.txtnombre.value="";
	f.txtconcepto.value="";
	f.txtnumsol.value="";
	f.prov[0].checked;
	f.action="sigesp_catdinamic_sol_eje_pre.php";
	f.submit();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
