<?php
session_start();

   //--------------------------------------------------------------
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
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   // Función que obtiene e imprime los resultados de la busqueda
   function uf_imprimirresultados($as_codpai, $as_codest, $as_codmun, $as_desmun)
   {
		require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_msg=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$SQL=new class_sql($con);
		$ds=new class_datastore();
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=440>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT codmun,denmun FROM sigesp_municipio".
				" WHERE (codpai='".$as_codpai."')".
				" AND(codest='".$as_codest."')".
				" AND(codmun like '".$as_codmun."'".
				" AND denmun like '".$as_desmun."')";
				
		$rs_mun=$SQL->select($ls_sql);
		if($row=$SQL->fetch_row($rs_mun))
		{
			$data=$SQL->obtener_datos($rs_mun);
			$ds->data=$data;
			$li_rows=$ds->getRowCount("codmun");
			for($li_index=1;$li_index<=$li_rows;$li_index++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codmun=$data["codmun"][$li_index];
				$ls_desmun=$data["denmun"][$li_index];
				
				print "<td><a href=\"javascript: aceptar('$ls_codmun','$ls_desmun');\">".$ls_codmun."</a></td>";
				print "<td>".$ls_desmun."</td>";
				print "</tr>";			
			}
		}
		else
		{
			$io_msg->message("No hay registros para este estado");
		}
		print "</table>";
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Municipio</title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Municipio </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodmun" type="text" id="txtcodmun" size="30" maxlength="3">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Descripci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdesmun" type="text" id="txtdesmun" size="30" maxlength="50">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"></div></td>
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
		$ls_codmun="%".$_POST["txtcodmun"]."%";
		$ls_desmun="%".$_POST["txtdesmun"]."%";
		$ls_codpai=$_POST["txtcodpai"];
		$ls_codest=$_POST["txtcodest"];
		
		uf_imprimirresultados($ls_codpai,$ls_codest,$ls_codmun, $ls_desmun);
	}
	else
	{
		$ls_codpai=$_GET["codpai"];
		$ls_codest=$_GET["codest"];
	}
?>
</div>
          <input name="txtcodpai" type="hidden" id="txtcodpai" value="<?php print $ls_codpai;?>">
          <input name="txtcodest" type="hidden" id="txtcodest" value="<?php print $ls_codest;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,descripcion)
{
	opener.document.form1.txtcodmun.value=codigo;
	opener.document.form1.txtcodmun.readOnly=true;
    opener.document.form1.txtdesmun.value=descripcion;
	close();
}
function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_saf_cat_municipio.php";
  	f.submit();
}
</script>
</html>
