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
		$ls_codestprog1=$_POST["codestpro1"];
		if(array_key_exists("denestpro1",$_POST))
		{
			$ls_denestprog1=$_POST["denestpro1"];
		}
		else
		{
			$ls_denestprog1="";
		}
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
		if(array_key_exists("estcla",$_GET))
		{
			$ls_estcla=$_GET["estcla"];
		}
		else
		{
			$ls_estcla="";
		}
	}
	else
	{
		$ls_operacion="BUSCAR";
		$ls_codestprog1=$_GET["codestpro1"];
		$ls_codigo="%%";
		$ls_denominacion="%%";
		if(array_key_exists("denestpro1",$_GET))
		{
			$ls_denestprog1=$_GET["denestpro1"];
		}
		else
		{
			$ls_denestprog1="";
		}
	
		if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
		
		if(array_key_exists("estcla",$_GET))
		{
			$ls_estcla=$_GET["estcla"];
		}
		else
		{
			$ls_estcla="";
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
    <input name="operacion" type="hidden" id="operacion"></p>
  	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="21" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo <?php print $la_empresa["nomestpro2"] ?></div></td>
       </tr>
      <tr>
	  <?php 
		 $ls_loncodestpro1 = $la_empresa["loncodestpro1"];
		 $ls_loncodestpro2 = $la_empresa["loncodestpro2"];
		 
	  ?>
        <td width="137"><div align="right"><?php print $la_empresa["nomestpro1"]?></div></td>
        <td width="461"><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestprog1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" readonly>
          <input name="denestprog1" type="hidden" id="denestprog1" value="<?php print $ls_denestprog1 ?>">
        </div></td>
      </tr>
      <tr>
        <td height="29"><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="codestprog2" type="text" id="codestprog2" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="23"><div align="right">Denominacion</div></td>
        <td width="461"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="80" maxlength="100">
        </div></td>
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
	print "<td>".$la_empresa["nomestpro1"]."</td>";
	print "<td>Código </td>";
	print "<td>Denominación</td>";
	print "<td>Tipo</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_aux="";
		if($ls_codestprog1!="")
		{
			if($li_estmodest==2)
			{
				if($ls_codestprog1!='**')
				{
				  $ls_codestprog1=$io_function->uf_cerosizquierda($ls_codestprog1,25);
				  $ls_aux=" codestpro1='".$ls_codestprog1."' AND ";
				}
			}
			else
			{
			 $ls_codestprog1=$io_function->uf_cerosizquierda($ls_codestprog1,25);
		     $ls_aux=" codestpro1='".$ls_codestprog1."' AND ";
			}			
		}
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
		 						IN ( (SELECT distinct codemp||codsis||codusu||substr(codintper,1,50)||substr(codintper,126,1)
		                       		    FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')
									UNION
									 (SELECT distinct sss_permisos_internos_grupo.codemp||'SPG'||codusu||substr(codintper,1,50)||substr(codintper,126,1)
		                       		    FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu = '".$ls_logusr."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru))	";
		}
		$ls_sql=" SELECT codestpro1,codestpro2,denestpro2,estcla ".
		        " FROM  spg_ep2 ".
				" WHERE codemp='".$ls_codemp."' AND  ".$ls_aux.
		        "       codestpro2 like '".$ls_codigo."' AND ".
				"       UPPER(denestpro2) like '".strtoupper($ls_denominacion)."' AND ".
				"       estcla='".$ls_estcla."'".$ls_sql_seguridad." ".
				" ORDER BY codestpro1,codestpro2 ";	
		$rs_data=$io_sql->select($ls_sql);
		$li_numrows=$io_sql->num_rows($rs_data);
	    if($li_numrows>0)
	    {
			while($row=$io_sql->fetch_row($rs_data))
			{
				print "<tr class=celdas-blancas>";
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_denominacion=$row["denestpro2"];
				$ls_estclaprog=$row["estcla"];
				if($ls_estclaprog=="A")
				{
				   $ls_estcla="ACCION";
				}
				elseif($ls_estclaprog=="P")
				{
				   $ls_estcla="PROYECTO";
				}
			   
			    $ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
			    $ls_incio1=25-$ls_loncodestpro1;
			    $ls_codestpro1=substr($ls_codestpro1,$ls_incio1,$ls_loncodestpro1);
				
				$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
			    $ls_incio2=25-$ls_loncodestpro2;
			    $ls_codestpro2=substr($ls_codestpro2,$ls_incio2,$ls_loncodestpro2);
				
				/*if($li_estmodest==2)
				{
					$ls_codestprog1=substr($ls_codestprog1,18,2);
					$ls_codestpro2=substr($ls_codestpro2,4,2);
				}*/
				if($ls_tipo=="")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro2)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";				
				}
				if($ls_tipo=="reporte0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_reporte0415('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_reporte0415('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro2)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro2)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas0415('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas0415('$ls_codestpro2','$ls_estcla');\">".
					trim($ls_codestpro2)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";				
				}
			}
			if(($ls_tipo!="")&&($li_estmodest==2))
			{
				print "<tr class=celdas-blancas>";
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">".trim($ls_codestpro1)."</a></td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">**</a></td>";					
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";				
				}
				if($ls_tipo=="reporte0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_reporte0415('**');\">".trim($ls_codestpro1)."</a></td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_reporte0415('**');\">**</a></td>";					
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">".trim($ls_codestpro1)."</a></td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">**</a></td>";					
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";				
				}
				if($ls_tipo=="rephas0415")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas0415('$ls_codigo_est');\">".trim($ls_codestpro1)."</a></td>";
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
  function aceptar(codestprog2,deno,estcla)
  {
    opener.document.form1.denestpro2.value=deno;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value="";
	opener.document.form1.codestpro3.value="";
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estcla.value=estcla;
	close();
  }
  function aceptar_rep(codestprog2,estcla)
  {
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro2.readOnly=true;
	opener.document.form1.codestpro3.value="";
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estclades.value=estcla;
	close();
  }
  function aceptar_reporte0415(codestprog2,estcla)
  {
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro2.readOnly=true;
	close();
  }
  function aceptar_rephas(codestprog2,estcla)
  {
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro2h.readOnly=true;
	opener.document.form1.codestpro3h.value="";
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estclahas.value=estcla;
	close();
  }
  function aceptar_rephas0415(codestprog2,estcla)
  {
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro2h.readOnly=true;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_public_estpro2.php?tipo=<?php print $ls_tipo; ?>";
	  f.submit();
  }
</script>
</html>