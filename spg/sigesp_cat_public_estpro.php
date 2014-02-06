<?php
session_start();
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
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodestprog3"]."%";
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
	if(array_key_exists("estcla",$_GET))
	{
		$ls_estcla=$_GET["estcla"];
	}
	else
	{
		$ls_estcla="";
	}
}
 $ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
 
 if(array_key_exists("unidad",$_GET))
 {
    $ls_unidad=$_GET["unidad"];
 }
 else
 {
 	$ls_unidad="";
 }  

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Programatica Nivel 3 <?php print $_SESSION["la_empresa"]["nomestpro3"] ?></title>
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
        <td height="21" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo <?php print $_SESSION["la_empresa"]["nomestpro3"] ?> </div></td>
       </tr>
      <tr>
        <td width="118" height="23"><div align="right">Codigo</div></td>
        <td width="380"><input name="txtcodestprog3" type="text" id="txtcodestprog3"  size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="26"><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100">
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
print "<td>".$_SESSION["la_empresa"]["nomestpro1"]."</td>";
print "<td>".$_SESSION["la_empresa"]["nomestpro2"]."</td>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "<td>Tipo</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_logusr = $_SESSION["la_logusr"];
	$ls_gestor = $_SESSION["ls_gestor"];
	$ls_sql_seguridad = "";
	if ($ls_unidad!="")
	{
		$ls_unidad=str_pad($ls_unidad,10,"0",0);
		$ls_tabla=", spg_dt_unidadadministrativa d ";
		$ls_criterio=" AND d.codemp=c.codemp            ".
					 "  AND d.codestpro1= c.codestpro1  ".
					 "  AND d.codestpro2= c.codestpro2  ".
					 "  AND d.codestpro3= c.codestpro3  ".
					 "  AND d.estcla= c.estcla          ". 
					 "  AND d.coduniadm='".$ls_unidad."'  ";		
	}
	else
	{
		$ls_tabla=" ";
		$ls_criterio=" ";	
	
	}
	if (strtoupper($ls_gestor) == "MYSQLT")
	{
		$ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',c.codestpro1,c.codestpro2,c.codestpro3,c.estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,CONCAT(substr(codintper,1,75),substr(codintper,126,1))) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
	}
	else
	{
		$ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||c.codestpro1||c.codestpro2||c.codestpro3||c.estcla IN
								((SELECT distinct codemp||codsis||codusu||substr(codintper,1,75)||substr(codintper,126,1) 
									  FROM sss_permisos_internos 
									 WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG') 
								   UNION 
									(SELECT distinct sss_permisos_internos_grupo.codemp||'SPG'||codusu||substr(codintper,1,75)||substr(codintper,126,1) 
									  FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos 
									 WHERE sss_usuarios_en_grupos.codusu = '".$ls_logusr."' AND sss_usuarios_en_grupos.codgru=sss_permisos_internos_grupo.codgru ))";
	}
	
	
	$ls_sql=" SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
			" 		 b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3, ".
			"        c.estcla as estcla ". 
			" FROM   spg_ep1 a,spg_ep2 b,spg_ep3 c ".$ls_tabla.
			" WHERE  a.codemp=b.codemp ".
			"   AND  a.codemp=c.codemp  ".
			"   AND  a.codemp='".$ls_codemp."' ".
			"   AND  a.codestpro1=b.codestpro1 ".
			"   AND  a.codestpro1=c.codestpro1 ".
			"   AND  b.codestpro2=c.codestpro2 ".
			"   AND  c.codestpro3 like '".$ls_codigo."' ".
			"   AND  UPPER(c.denestpro3) like '".strtoupper($ls_denominacion)."' ".
			"   AND  a.estcla=b.estcla ".
			"   AND  b.estcla=c.estcla ".
			"   AND  a.codestpro1<>'-------------------------'".$ls_criterio.$ls_sql_seguridad.
			" ORDER BY 7,1,3,5 ";
	$rs_data=$io_sql->select($ls_sql);
	$li_numrows=$io_sql->num_rows($rs_data);	
	if($li_numrows>0)
	{
		while($row=$io_sql->fetch_row($rs_data))
		{
			print "<tr class=celdas-blancas>";
			$ls_codestpro1=$row["codestpro1"];
			$ls_denestpro1=$row["denestpro1"];
			$ls_codestpro2=$row["codestpro2"];
			$ls_denestpro2=$row["denestpro2"];
			$ls_codestpro3=$row["codestpro3"];
			$ls_denominacion=$row["denestpro3"];
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
			
			$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
			$ls_incio3=25-$ls_loncodestpro3;
			$ls_codestpro3=substr($ls_codestpro3,$ls_incio3,$ls_loncodestpro3);
			
			if($ls_tipo=="")
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
				print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				print "<td width=30 align=\"left\">".$ls_estcla."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="apertura")
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
				print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				print "<td width=30 align=\"left\">".$ls_estcla."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="progrep")
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denominacion','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
				print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				print "<td width=30 align=\"left\">".$ls_estcla."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="reporte")		
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
				print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				print "<td width=30 align=\"left\">".$ls_estcla."</td>";
				print "</tr>";	
			}
			if($ls_tipo=="rephas")		
			{
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
				print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
				print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				print "<td width=30 align=\"left\">".$ls_estcla."</td>";
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

  function aceptar(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3,estcla)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
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
  
  function aceptar_apertura(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3,estcla)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estcla.value=estcla;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,deno3,estcla)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=deno3;
	opener.document.form1.codestpro3.value=codestprog3;
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estcla.value=estcla;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(codestprog1,codestprog2,codestprog3,estcla)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3.value=codestprog3;
	opener.document.form1.codestpro3.readOnly=true;
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
  
  function aceptar_rephas(codestprog1,codestprog2,codestprog3,estcla)
  {
	opener.document.form1.codestpro1h.value=codestprog1;
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro3h.value=codestprog3;
	opener.document.form1.codestpro3h.readOnly=true;
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
	  f.action="sigesp_cat_public_estpro.php?tipo=<?php print $ls_tipo; ?>";
	  f.submit();
  }
</script>
</html>