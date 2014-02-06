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
   function uf_imprimirresultados($as_codpai, $as_codest, $as_desest,$as_destino)
   {
		require_once("../shared/class_folder/sigesp_include.php");
		$in=new sigesp_include();
		$con=$in->uf_conectar();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_msg=new class_mensajes();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($con);
		$ds=new class_datastore();
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=60>Código</td>";
		print "<td width=440>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT codest,desest FROM sigesp_estados".
				" WHERE codpai='".$as_codpai."'".
				"   AND codest like '".$as_codest."'".
				"   AND desest like '".$as_desest."'".
				"   AND codest <> '---'".
				"   AND codest <> '000'";
				
		$rs_est=$io_sql->select($ls_sql);
		if($row=$io_sql->fetch_row($rs_est))
		{
			$data=$io_sql->obtener_datos($rs_est);
			$ds->data=$data;
			$li_rows=$ds->getRowCount("codest");
			switch ($as_destino)
			{
				case "":
					for($li_index=1;$li_index<=$li_rows;$li_index++)
					{
						print "<tr class=celdas-blancas>";
						$ls_codest=$data["codest"][$li_index];
						$ls_desest=$data["desest"][$li_index];
						
						print "<td><a href=\"javascript: aceptar('$ls_codest','$ls_desest');\">".$ls_codest."</a></td>";
						print "<td>".$ls_desest."</td>";
						print "</tr>";			
					}
				break;
				case "REGIONES":
					for($li_index=1;$li_index<=$li_rows;$li_index++)
					{
						print "<tr class=celdas-blancas>";
						$ls_codest=$data["codest"][$li_index];
						$ls_desest=$data["desest"][$li_index];
						
						print "<td><a href=\"javascript: aceptar_regiones('$ls_codest','$ls_desest');\">".$ls_codest."</a></td>";
						print "<td>".$ls_desest."</td>";
						print "</tr>";			
					}
				break;
			}
		}
		else
		{
			$io_msg->message("No hay registros para este pais");
		}
		print "</table>";
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Estado</title>
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
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Estado </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodest" type="text" id="txtcodest" size="30" maxlength="3">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Descripci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdesest" type="text" id="txtdesest" size="30" maxlength="50">
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
		$ls_codest="%".$_POST["txtcodest"]."%";
		$ls_desest="%".$_POST["txtdesest"]."%";
		$ls_codpai=$_POST["txtcodpai"];
		$ls_destino=$_POST["txtdestino"];
		
		uf_imprimirresultados($ls_codpai,$ls_codest, $ls_desest,$ls_destino);
	}
	else
	{
		$ls_codpai=$_GET["codpai"];
		if(array_key_exists("destino",$_GET))
		{$ls_destino=$_GET["destino"];}
		else
		{$ls_destino="";}
		
	}
?>
</div>
          <input name="txtcodpai" type="hidden" id="txtcodpai" value="<?php print $ls_codpai; ?>">
          <input name="txtdestino" type="hidden" id="txtdestino" value="<?php print $ls_destino; ?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,descripcion)
{
	opener.document.form1.txtcodest.value=codigo;
	opener.document.form1.txtcodest.readOnly=true;
    opener.document.form1.txtdesest.value=descripcion;
	close();
}
function aceptar_regiones(codigo,denominacion)
{
	fop        = opener.document.form1;
	li_lastrow = (fop.hidlastrow.value); 
	lb_existe  = false;
	for (i=1;i<=li_lastrow;i++)
	{
		ls_codest = eval("fop.txtcodest"+i+".value");
		if (ls_codest==codigo)
		{
			lb_existe = true;
			alert("Este Estado ya fue Registrado !!!"); 
			break;
		}
	}
	if (!lb_existe)
	{
		li_lastrow = parseInt(li_lastrow)+1;
		eval("fop.txtcodest"+li_lastrow+".value='"+codigo+"'");
		eval("fop.txtdenest"+li_lastrow+".value='"+denominacion+"'");
		fop.hidlastrow.value = li_lastrow; 
		fop.hidtotrows.value = parseInt(li_lastrow)+1; 
		fop.operacion.value  = 'PINTAR'
		fop.submit();
		close();
	}
}

function ue_search()
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_scv_cat_estados.php";
  	f.submit();
}
</script>
</html>
