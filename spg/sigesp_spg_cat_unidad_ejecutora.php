<?php
	session_start();
	$la_empresa=$_SESSION["la_empresa"];
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
		$ls_operacion="";
		if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
	}
	if(array_key_exists("codestpro1",$_GET))
	{
		$ls_estpro1  = $_GET["codestpro1"];
	}
	else
	{
	   $ls_estpro1  = "";
	}
	if(array_key_exists("codestpro2",$_GET))
	{
		$ls_estpro2  = $_GET["codestpro2"];
	}
	else
	{
	   $ls_estpro2  = "";
	}
	if(array_key_exists("codestpro3",$_GET))
	{
		$ls_estpro3  = $_GET["codestpro3"];
	}
	else
	{
	   $ls_estpro3  = "";
	}
	$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	if($li_estmodest==2)
	{
		if(array_key_exists("codestpro4",$_GET))
		{
			$ls_estpro4  = $_GET["codestpro4"];
		}
		else
		{
		    $ls_estpro4  = "";
		}
		if(array_key_exists("codestpro5",$_GET))
		{
			$ls_estpro5  = $_GET["codestpro5"];
		}
		else
		{
		    $ls_estpro5  = "";
		}
	}
	else
	{
		$ls_estpro4  = "00";
		$ls_estpro4  = "00";
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Ejecutoras </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
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
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="560" colspan="2" class="titulo-celda">Cat&aacute;logo de Unidades Ejecutoras  </td>
    	</tr>
  </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
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
			$ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',spg_dt_unidadadministrativa.codestpro1,spg_dt_unidadadministrativa.codestpro2,spg_dt_unidadadministrativa.codestpro3,spg_dt_unidadadministrativa.codestpro4,spg_dt_unidadadministrativa.codestpro5,spg_dt_unidadadministrativa.estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		else
		{
			$ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||spg_dt_unidadadministrativa.codestpro1||spg_dt_unidadadministrativa.codestpro2||spg_dt_unidadadministrativa.codestpro3||spg_dt_unidadadministrativa.codestpro4||spg_dt_unidadadministrativa.codestpro5||spg_dt_unidadadministrativa.estcla IN (SELECT distinct codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		
		
		$ls_sql=" SELECT distinct spg_unidadadministrativa.coduniadm, spg_unidadadministrativa.denuniadm ".
				" FROM   spg_unidadadministrativa, spg_dt_unidadadministrativa ".
				" WHERE  spg_unidadadministrativa.codemp='".$ls_codemp."' AND spg_unidadadministrativa.coduniadm like '".$ls_codigo."' AND spg_unidadadministrativa.denuniadm like '".$ls_denominacion."' "; 
		if($ls_estpro1!="")
		{
			 $ls_sql=$ls_sql." AND spg_dt_unidadadministrativa.codestpro1='".$ls_estpro1."' ";
			 if($ls_estpro2!="")
			 {
				$ls_sql=$ls_sql." AND spg_dt_unidadadministrativa.codestpro2='".$ls_estpro2."' ";
			 }	
			 if($ls_estpro3!="")
			 {
				$ls_sql=$ls_sql." AND spg_dt_unidadadministrativa.codestpro3='".$ls_estpro3."' ";
			 }
			 if($li_estmodest==2)
			 {
				 if($ls_estpro4!="")
				 {
					$ls_sql=$ls_sql." AND spg_dt_unidadadministrativa.codestpro4='".$ls_estpro4."' ";
				 }
				 if($ls_estpro5!="")
				 {
					$ls_sql=$ls_sql." AND spg_dt_unidadadministrativa.codestpro5='".$ls_estpro5."' ";
				 }
			 }
			 $ls_sql = $ls_sql." ".$ls_sql_seguridad;
		}
		else
		{
		 $ls_sql = $ls_sql." ".$ls_sql_seguridad;
		}
		$rs_unidad=$io_sql->select($ls_sql);
		$data=$rs_unidad;
		if($row=$io_sql->fetch_row($rs_unidad))
		{
			$data=$io_sql->obtener_datos($rs_unidad);
			$arrcols=array_keys($data);
			$totcol=count($arrcols);
			$ds->data=$data;
			$totrow=$ds->getRowCount("coduniadm");
			$io_sql->free_result($rs_unidad);
			$io_sql->close();
			for($z=1;$z<=$totrow;$z++)
			{
				if ($ls_tipo=="coduniad_desde")
				{
					print "<tr class=celdas-blancas>";
					$codigo=$data["coduniadm"][$z];
					$denominacion=$data["denuniadm"][$z];
					print "<td align=center><a href=\"javascript:aceptar_desde('$codigo','$denominacion');\">".$codigo."</a></td>";
					print "<td>".$denominacion."</td>";
					print "</tr>";
				}	
				if ($ls_tipo=="coduniad_hasta")
				{
					print "<tr class=celdas-blancas>";
					$codigo=$data["coduniadm"][$z];
					$denominacion=$data["denuniadm"][$z];
					print "<td align=center><a href=\"javascript:aceptar_hasta('$codigo','$denominacion');\">".$codigo."</a></td>";
					print "<td>".$denominacion."</td>";
					print "</tr>";
				}			
			}
		}
		else
		{
			$io_msg->message("No se han definido unidades administrativas");		
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
  function aceptar_desde(codigo,deno)
  {
    opener.document.form1.txtcoduniadmdes.value=codigo;
	opener.document.form1.txtcoduniadmdes.readOnly=true;
	close();
  }
  function aceptar_hasta(codigo,deno)
  {
    opener.document.form1.txtcoduniadmhas.value=codigo;
	opener.document.form1.txtcoduniadmhas.readOnly=true;
	close();
  }
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_cat_unidad_ejecutora.php?tipo=<?php print $ls_tipo; ?>";
	f.submit();
}
  
function catalogo_estpro1()
{
	   pagina="sigesp_cat_public_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estpro.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
}
</script>
</html>
