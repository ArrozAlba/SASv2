<?php
session_start();
require_once("class_funciones_activos.php");
$fun_activos=new class_funciones_activos();				
$li_row=$fun_activos->uf_obtenervalor_get("row","");
if($li_row=="")
{
	$li_row=$fun_activos->uf_obtenervalor("hidrow","");
}

	//-----------------------------------------------------------------------------------------------------------------------------------
   	// Función que obtiene que tipo de operación se va a ejecutar
   	// NUEVO, GUARDAR, ó ELIMINAR
   	function uf_obteneroperacion()
   	{
		if(array_key_exists("operacion",$_POST))
		{
			$operacion=$_POST["operacion"];
		}
		else
		{
			$operacion="NUEVO";
		}
   		return $operacion; 
   	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
   	// Función que le da formato a los valore numéricos que vienen de la BD
	// parametro de entrada = Valor númerico que se desa formatear
	// parametro de retorno = valor numérico formateado
   	function uf_formatonumerico($as_valor)
   	{
		$li_poscoma = strpos($as_valor, ",");
		$li_contador = 0;
		if ($li_poscoma==0)
		{
			$li_poscoma = strlen($as_valor);
			$as_valor = $as_valor.",00";
		}
		for($li_index=$li_poscoma;$li_index>=0;--$li_index)
		{
			if(($li_contador==3)&&(($li_index-1)>0)) 
			{
				$as_valor = substr($as_valor,0,$li_index).".".substr($as_valor,$li_index);
				$li_contador=1;
			}
			else
			{
				$li_contador=$li_contador + 1;
			}
		}
		return $as_valor;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	// Función que obtiene e imprime los resultados de la busqueda
	function uf_imprimirresultados($as_codper, $as_cedper, $as_nomper, $as_apeper)
   	{
		require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$msg=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		$ds=new class_datastore();
   		require_once("../shared/class_folder/class_funciones.php");
		$fun=new class_funciones();				
       	$emp=$_SESSION["la_empresa"];
        $ls_codemp=$emp["codemp"];

		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=100>Cédula</td>";
		print "<td width=440>Nombre y Apellido</td>";
		print "</tr>";
		$ls_sql="SELECT sno_personal.*, sno_profesion.despro, sigesp_pais.despai, sigesp_estados.desest, sigesp_municipio.denmun, sigesp_parroquia.denpar "; 
		$ls_sql=$ls_sql."FROM sno_personal, sno_profesion, sigesp_pais, sigesp_estados, sigesp_municipio, sigesp_parroquia ";
		$ls_sql=$ls_sql."WHERE sno_personal.codemp='".$ls_codemp."'";
		$ls_sql=$ls_sql."  AND sno_profesion.codemp = sno_personal.codemp ";
		$ls_sql=$ls_sql."  AND sno_profesion.codpro = sno_personal.codpro ";
		$ls_sql=$ls_sql."  AND sigesp_pais.codpai = sno_personal.codpai ";
		$ls_sql=$ls_sql."  AND sigesp_estados.codpai = sno_personal.codpai ";
		$ls_sql=$ls_sql."  AND sigesp_estados.codest = sno_personal.codest ";
		$ls_sql=$ls_sql."  AND sigesp_municipio.codpai = sno_personal.codpai ";
		$ls_sql=$ls_sql."  AND sigesp_municipio.codest = sno_personal.codest ";
		$ls_sql=$ls_sql."  AND sigesp_municipio.codmun = sno_personal.codmun ";
		$ls_sql=$ls_sql."  AND sigesp_parroquia.codpai = sno_personal.codpai ";
		$ls_sql=$ls_sql."  AND sigesp_parroquia.codest = sno_personal.codest ";
		$ls_sql=$ls_sql."  AND sigesp_parroquia.codmun = sno_personal.codmun ";
		$ls_sql=$ls_sql."  AND sigesp_parroquia.codpar = sno_personal.codpar ";
		$ls_sql=$ls_sql."  AND sno_personal.codper like '".$as_codper."' AND sno_personal.cedper like '".$as_cedper."'";
		$ls_sql=$ls_sql."  AND sno_personal.nomper like '".$as_nomper."' AND sno_personal.apeper like '".$as_apeper."'";
		$ls_sql=$ls_sql."  ORDER BY sno_personal.codper";

		$rs_per=$io_sql->select($ls_sql);
		if($row=$io_sql->fetch_row($rs_per))
		{
			$data=$io_sql->obtener_datos($rs_per);
			$ds->data=$data;
			$li_rows=$ds->getRowCount("codper");
			for($li_index=1;$li_index<=$li_rows;$li_index++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codper=$data["codper"][$li_index];
				$ls_cedper=$data["cedper"][$li_index];
				$ls_nomper=$data["nomper"][$li_index];
				$ls_apeper=$data["apeper"][$li_index];				
				$ls_dirper=$data["dirper"][$li_index];				
				$ld_fecnacper=$fun->uf_convertirfecmostrar($data["fecnacper"][$li_index]);				
				$ls_edocivper=$data["edocivper"][$li_index];			
				$ls_telhabper=$data["telhabper"][$li_index];				
				$ls_telmovper=$data["telmovper"][$li_index];				
				$ls_sexper=$data["sexper"][$li_index];			
				$li_estaper=$data["estaper"][$li_index];			
				$li_estaper=str_replace(".",",",$li_estaper);
				$li_estaper=uf_formatonumerico($li_estaper);
				$li_pesper=$data["pesper"][$li_index];			
				$li_pesper=str_replace(".",",",$li_pesper);
				$li_pesper=uf_formatonumerico($li_pesper);
				$ls_codpro=$data["codpro"][$li_index];	
				$ls_nivacaper=$data["nivacaper"][$li_index];
				$ls_codpai=$data["codpai"][$li_index];	
				$ls_codest=$data["codest"][$li_index];	
				$ls_codmun=$data["codmun"][$li_index];	
				$ls_codpar=$data["codpar"][$li_index];	
				$ls_catper=$data["catper"][$li_index];	
				$ls_cedbenper=$data["cedbenper"][$li_index];	
				$ls_numhijper=$data["numhijper"][$li_index];	
				$ls_obsper=$data["obsper"][$li_index];	
				$ls_contraper=$data["contraper"][$li_index];			
				$ls_tipvivper=$data["tipvivper"][$li_index];	
				$ls_tenvivper=$data["tenvivper"][$li_index];	
				$li_monpagvivper=$data["monpagvivper"][$li_index];	
				$li_monpagvivper=str_replace(".",",",$li_monpagvivper);
				$li_monpagvivper=uf_formatonumerico($li_monpagvivper);				
				$ls_cuecajahoper=$data["cuecajahoper"][$li_index];	
				$ls_cuelphper=$data["cuelphper"][$li_index];	
				$ls_cuefidper=$data["cuefidper"][$li_index];	
				$ls_cajahoper=$data["cajahoper"][$li_index];
				$ld_fecingadmpubper=$fun->uf_convertirfecmostrar($data["fecingadmpubper"][$li_index]);				
				$li_anoservpreper=$data["anoservpreper"][$li_index];	
				$ld_fecingper=$fun->uf_convertirfecmostrar($data["fecingper"][$li_index]);				
				$ld_fecegrper=$fun->uf_convertirfecmostrar($data["fecegrper"][$li_index]);				
				$ls_cauegrper=$data["cauegrper"][$li_index];			
				$ls_obsegrper=$data["obsegrper"][$li_index];			
				$ls_estper=$data["estper"][$li_index];		
				switch ($ls_estper)
				{
					case "0":
						$ls_estper="PRE INGRESO";
						break;
					
					case "1":
						$ls_estper="ACTIVO";
						break;
					
					case "2":
						$ls_estper="NA";
						break;
					
					case "3":
						$ls_estper="EGRESADO";
						break;
				}
				$ls_despro=$data["despro"][$li_index];		
				$ls_despai=$data["despai"][$li_index];		
				$ls_desest=$data["desest"][$li_index];			
				$ls_desmun=$data["denmun"][$li_index];		
				$ls_despar=$data["denpar"][$li_index];		
				
				print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_cedper','$ls_nomper','$ls_apeper');\">".$ls_codper."</a></td>";
				print "<td>".$ls_cedper."</td>";
				print "<td>".$ls_nomper."  ".$ls_apeper."</td>";
				print "</tr>";			
			}
		}
		print "</table>";
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Personal</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Personal </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="1" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="30" maxlength="10">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">C&eacute;dula</div></td>
        <td><input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10"></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60"></td>
      </tr>
      <tr>
        <td><div align="right">Apellido</div></td>
        <td><div align="left">
          <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left">
          <input name="hidrow" type="hidden" id="hidrow" value="<?php print $li_row; ?>">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	$ls_operacion=uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codper="%".$_POST["txtcodper"]."%";
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";

		uf_imprimirresultados($ls_codper, $ls_cedper, $ls_nomper, $ls_apeper);
	}
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codper,cedper,nomper,apeper)
{
	f=document.form1;
	li_row=f.hidrow.value;
	obj=eval("opener.document.form1.txtcodresuso");
	obj.value=codper;
	obj=eval("opener.document.form1.txtnomresuso");
	obj.value=nomper+" "+apeper;
	close();
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_saf_cat_responsableuso.php";
  	f.submit();
}
</script>
</html>
