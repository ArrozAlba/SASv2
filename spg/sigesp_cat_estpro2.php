<?php
	session_start();
	$la_empresa=$_SESSION["la_empresa"];
	include("../shared/class_folder/sigesp_include.php");
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_function=new class_funciones();
	require_once("../shared/class_folder/class_datastore.php");
	$ds=new class_datastore();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($io_connect);

	$ls_codemp=$la_empresa["codemp"];
	$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_codigo="%".$_POST["codestprog2"]."%";
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 2 <?php print $la_empresa["nomestpro2"] ?> </title>
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $la_empresa["nomestpro2"] ?>  </td>
    	</tr>
  </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
	  <?php 
		 if($li_estmodest==1)
		 {
	  ?>
        <td><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="codestprog2" type="text" id="codestprog2" size="22" maxlength="6">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td width="80"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="80" maxlength="100">
        </div></td>
		<?php 
		}
		else
		{
		?>
      </tr>     
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><input name="codestprog2" type="text" id="codestprog2" size="5" maxlength="2" ></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><input name="denominacion" type="text" id="denominacion" size="80" maxlength="100"></td>
       <?php 
	   }
	   ?>     
	  </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php
	print "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	print "<tr class=titulo-celda>";
	print "<td>Código </td>";
	print "<td>Denominación</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{		
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
			$ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',codestpro1,codestpro2,estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,CONCAT(substr(codintper,1,50),substr(codintper,126,1))) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		else
		{
			$ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||codestpro1||codestpro2||estcla 
									IN ((SELECT distinct codemp||codsis||codusu||substr(codintper,1,50)||substr(codintper,126,1)
		                       			   FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')
							   	 UNION (SELECT distinct sss_permisos_internos_grupo.codemp||'SPG'||codusu||substr(codintper,1,50)||substr(codintper,126,1)
		                       			   FROM sss_permisos_internos_grupo,sss_ususarios_en_grupos WHERE codusu = '".$ls_logusr."' AND sss_permisos_internos_grupo.codgru=sss_ususarios_en_grupos.codgru))";
		}
		$ls_sql=" SELECT codestpro1, codestpro2,denestpro2 ".
		        " FROM spg_ep2 ".
				" WHERE codemp='".$ls_codemp."' AND  ".
		        "       codestpro2 like '".$ls_codigo."' AND denestpro2 like '".$ls_denominacion."' ".$ls_sql_seguridad. 
				"  ORDER BY codespro1, codestpro2 ";
		$rs_data=$io_sql->select($ls_sql);
		$data=$rs_data;
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$li_arrcols=array_keys($data);
			$li_totcol=count($li_arrcols);
			$ds->data=$data;
			$li_totrow=$ds->getRowCount("codestpro2");
		
			for($z=1;$z<=$li_totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$ls_codigo_est=$data["codestpro2"][$z];
				$ls_denominacion=$data["denestpro2"][$z];
				if($li_estmodest==2)
				{
					$ls_codigo_est=substr($ls_codigo_est,4,2);
				}
				if($ls_tipo=="")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codigo_est','$ls_denominacion');\">".
					trim($ls_codigo_est)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codigo_est');\">".
					trim($ls_codigo_est)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "</tr>";				
				}
				if($ls_tipo=="reporte0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_reporte0415('$ls_codigo_est');\">".
					trim($ls_codigo_est)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codigo_est');\">".
					trim($ls_codigo_est)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas0415('$ls_codigo_est');\">".
					trim($ls_codigo_est)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "</tr>";				
				}
			}
			if($ls_tipo!="")
			{
				print "<tr class=celdas-blancas>";
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">**</a></td>";
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";				
				}
				if($ls_tipo=="reporte0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_reporte0415('**');\">**</a></td>";
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">**</a></td>";
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas0415('$ls_codigo_est');\">**</a></td>";
					print "<td width=130 align=\"left\">Todas</td>";
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
  function aceptar(codestprog2,deno)
  {
    opener.document.form1.denestpro2.value=deno;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value="";
	opener.document.form1.codestpro3.value="";
	close();
  }
  function aceptar_rep(codestprog2)
  {
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro2.readOnly=true;
	opener.document.form1.codestpro3.value="";
	close();
  }
  function aceptar_reporte0415(codestprog2)
  {
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro2.readOnly=true;
	close();
  }
  function aceptar_rephas(codestprog2)
  {
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro2h.readOnly=true;
	opener.document.form1.codestpro3h.value="";
	close();
  }
  function aceptar_rephas0415(codestprog2)
  {
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro2h.readOnly=true;
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_estpro2.php?tipo=<?php print $ls_tipo; ?>";
  f.submit();
  }
</script>
</html>