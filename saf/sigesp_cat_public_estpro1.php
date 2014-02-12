<?php
session_start();
$la_empresa=$_SESSION["la_empresa"];
	require_once("class_funciones_activos.php");
	$io_activos= new class_funciones_activos();
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_titulo="";
	$lb_valido=$io_activos->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 1 <?php print $la_empresa["nomestpro1"] ?> </title>
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
  	 <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de<?php print $la_empresa["nomestpro1"] ?>  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
	  <?php  
	     $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		 if($li_estmodest==1)
		 {
	  ?>
        <td width="67"><div align="right">Codigo</div></td>
        <td width="431">
          
          <div align="left">
            <input name="codigo" type="text" id="codigo"  size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>">        
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="80">
        </div></td>
		<?php 
		 }
		 else
		 {
		 ?>
      </tr>
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><input name="codigo" type="text" id="codigo"  size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>"></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><input name="denominacion" type="text" id="denominacion" size="80"></td>
      </tr>
	  <?php 
	   }
	  ?>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
	include("../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	require_once("../shared/class_folder/class_datastore.php");
	$ds=new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_connect);

	$ls_codemp=$la_empresa["codemp"];
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo="%".$_POST["codigo"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
	
	}
	else
	{
		$ls_operacion="BUSCAR";
		$ls_codigo="%%";
		$ls_denominacion="%%";
		if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
	}
	print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código </td>";
	print "<td>Estatus </td>";
	print "<td>Denominación</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql=" SELECT codestpro1,estcla,denestpro1 ".
		        " FROM   spg_ep1 ".
				" WHERE  codemp='".$ls_codemp."' AND codestpro1 like '".$ls_codigo."' AND denestpro1 like '".$ls_denominacion."' ";
		$rs_data=$io_sql->select($ls_sql);
		$data=$rs_data;
		if($row=$io_sql->fetch_row($rs_data))
		{
			$la_data=$io_sql->obtener_datos($rs_data);
			$la_arrcols=array_keys($la_data);
			$li_totcol=count($la_arrcols);
			$ds->data=$la_data;
			$li_totrow=$ds->getRowCount("codestpro1");
			for($z=1;$z<=$li_totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codigo=substr($la_data["codestpro1"][$z],(strlen($la_data["codestpro1"][$z])-$li_len1),$li_len1);
				$ls_denominacion=$la_data["denestpro1"][$z];
				$ls_estcla=$la_data["estcla"][$z];
				$ls_estatus="";
				switch($ls_estcla)
				{
					case "A":
						$ls_estatus="Acción";
						break;
					case "P":
						$ls_estatus="Proyecto";
						break;
				}
				if($ls_tipo=="")
				{
					print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_estatus."</td>";
					print "<td>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="reporte")
				{
					print "<td><a href=\"javascript: aceptar_rep('$ls_codigo');\">".$ls_codigo."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="rephas")
				{
					print "<td><a href=\"javascript: aceptar_rephas('$ls_codigo');\">".$ls_codigo."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="reporte0415")
				{
					print "<td><a href=\"javascript: aceptar_reporte0415('$ls_codigo');\">".$ls_codigo."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td>".$ls_denominacion."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="rephas0415")
				{
					print "<td><a href=\"javascript: aceptar_rephas0415('$ls_codigo');\">".$ls_codigo."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td>".$ls_denominacion."</td>";
					print "</tr>";			
				}
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
	function aceptar(codigo,deno,estcla)
	{
		opener.document.form1.estcla.value=estcla;
		opener.document.form1.txtcodestpro1.value=codigo;
		opener.document.form1.txtdenestpro1.value=deno;
		opener.document.form1.txtcodestpro2.value="";
		opener.document.form1.txtdenestpro2.value="";
		opener.document.form1.txtcodestpro3.value="";
		opener.document.form1.txtdenestpro3.value="";
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_cat_public_estpro1.php?tipo=<?php print $ls_tipo; ?>";
		f.submit();
	}
</script>
</html>