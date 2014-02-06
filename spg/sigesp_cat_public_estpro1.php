<?php
session_start();
$la_empresa=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 1 <?php print $la_empresa["nomestpro1"] ?></title>
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
        <td height="20" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de<?php print $la_empresa["nomestpro1"] ?> </div></td>
       </tr>
      <tr>
	  <?php  
		 $ls_loncodestpro1 = $la_empresa["loncodestpro1"];
	     $li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	  ?>
        <td width="67" height="33"><div align="right">Codigo</div></td>
        <td width="431">
          
          <div align="left">
            <input name="codigo" type="text" id="codigo" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>">        
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="80">
        </div></td>
      </tr>
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
	print "<td>Denominación</td>";
	print "<td>Tipo</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		if($ls_tipo=="reporte0517")
		{
		  $ls_filtro=" AND estcla='A' ";
		}
		else
		{
		  $ls_filtro="";
		}
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',codestpro1,estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,CONCAT(substr(codintper,1,25),substr(codintper,126,1))) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||codestpro1||estcla 
		 						IN ((SELECT distinct codemp||codsis||codusu||substr(codintper,1,25)||substr(codintper,126,1)
		                       		  FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')
									UNION
									(SELECT distinct sss_permisos_internos_grupo.codemp||'SPG'||codusu||substr(codintper,1,25)||substr(codintper,126,1)
		                       		  FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu = '".$ls_logusr."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)	)";
		}
		$ls_sql = "SELECT codestpro1,denestpro1,estcla
		             FROM spg_ep1
				    WHERE codemp='".$ls_codemp."' 
				      AND codestpro1 <> '-------------------------' 
				      AND codestpro1 like '".$ls_codigo."'
					  AND UPPER(denestpro1) like '".strtoupper($ls_denominacion)."' $ls_sql_seguridad
				    ORDER BY codestpro1";	
		$rs_data=$io_sql->select($ls_sql);
		$li_numrows=$io_sql->num_rows($rs_data);
	    if($li_numrows>0)
	    {
			while($row=$io_sql->fetch_row($rs_data))
			{
				print "<tr class=celdas-blancas>";
				$ls_codigo=$row["codestpro1"];
				$ls_denominacion=$row["denestpro1"];
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
			    $ls_incio=25-$ls_loncodestpro1;
			    $ls_codigo=substr($ls_codigo,$ls_incio,$ls_loncodestpro1);
				
				if($ls_tipo=="")
				{
					print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_denominacion','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
					print "<td>".$ls_estcla."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="reporte")
				{
					print "<td><a href=\"javascript: aceptar_rep('$ls_codigo','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
					print "<td>".$ls_estcla."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="reporte0517")
				{
					print "<td><a href=\"javascript: aceptar_rep('$ls_codigo','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
					print "<td>".$ls_estcla."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="rephas")
				{
					print "<td><a href=\"javascript: aceptar_rephas('$ls_codigo','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
					print "<td>".$ls_estcla."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="reporte0415")
				{
					print "<td><a href=\"javascript: aceptar_reporte0415('$ls_codigo','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
					print "<td>".$ls_estcla."</td>";
					print "</tr>";			
				}
				if ($ls_tipo=="rephas0415")
				{
					print "<td><a href=\"javascript: aceptar_rephas0415('$ls_codigo','$ls_estcla');\">".$ls_codigo."</a></td>";
					print "<td>".$ls_denominacion."</td>";
					print "<td>".$ls_estcla."</td>";
					print "</tr>";			
				}
			}
			if(($ls_tipo!="")&&($la_empresa["estmodest"]==2))
			{
				print "<tr class=celdas-blancas>";
				if ($ls_tipo=="reporte")
				{	print "<td><a href=\"javascript: aceptar_rep('**');\">**</a></td>";	}
				if ($ls_tipo=="rephas")
				{	print "<td><a href=\"javascript: aceptar_rephas('**');\">**</a></td>";	}
				if ($ls_tipo=="reporte0415")
				{	print "<td><a href=\"javascript: aceptar_reporte0415('**');\">**</a></td>"; 	}
				if ($ls_tipo=="rephas0415")
				{	print "<td><a href=\"javascript: aceptar_rephas0415('**');\">**</a></td>";		}
				print "<td>Todas</td>";
				print "</tr>";			
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
    opener.document.form1.codestpro1.value=codigo;
    opener.document.form1.denestpro1.value=deno;
	opener.document.form1.codestpro2.value="";
    opener.document.form1.denestpro2.value="";
	opener.document.form1.codestpro3.value="";
    opener.document.form1.denestpro3.value="";
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
  function aceptar_rep(codigo,estcla)
  {
    opener.document.form1.codestpro1.value=codigo;
	opener.document.form1.codestpro1.readOnly=true;
	opener.document.form1.codestpro2.value="";
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
  function aceptar_reporte0415(codigo,estcla)
  {
    opener.document.form1.codestpro1.value=codigo;
	opener.document.form1.codestpro1.readOnly=true;
	opener.document.form1.codestpro2.value="";
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
  function aceptar_rephas(codigo,estcla)
  {
    opener.document.form1.codestpro1h.value=codigo;
	opener.document.form1.codestpro1h.readOnly=true;
	opener.document.form1.codestpro2h.value="";
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
  function aceptar_rephas0415(codigo,estcla)
  {
    opener.document.form1.codestpro1h.value=codigo;
	opener.document.form1.codestpro1h.readOnly=true;
	opener.document.form1.codestpro2h.value="";
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
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_public_estpro1.php?tipo=<?php print $ls_tipo; ?>";
	  f.submit();
  }
</script>
</html>