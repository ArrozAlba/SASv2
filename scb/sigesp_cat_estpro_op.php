<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");

$ls_codemp        = $_SESSION["la_empresa"]["codemp"];
$ls_estmodest     = $_SESSION["la_empresa"]["estmodest"];
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_size1         = $li_loncodestpro1+10;

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["denominacion"];
	 $ls_coduniadm  = $_POST["hidcoduniadm"];
	 $ls_estuniadm  = $_POST["hidestuniadm"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = "";
	 $ls_denestpro1 = "";
	 $ls_coduniadm  = $_GET["hidcoduniadm"];
	 $ls_estuniadm  = $_GET["hidestuniadm"];
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de <?php echo $_SESSION["la_empresa"]["nomestpro1"] ?></title>
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
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion ?>">
        Cat&aacute;logo <?php print $_SESSION["la_empresa"]["nomestpro1"] ?> <input name="hidcoduniadm" type="hidden" id="hidcoduniadm" value="<?php echo $ls_coduniadm ?>">
        <input name="hidestuniadm" type="hidden" id="hidestuniadm" value="<?php echo $ls_estuniadm ?>"></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="92" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="456" height="22"><input name="txtcodestpro1" type="text" id="txtcodestpro1"  size="<?php echo $li_size1 ?>" maxlength="<?php echo $li_loncodestpro1 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"> <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
<div align="center">
<p><br>
<?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td height=22 width=70 style=text-align:center>Código</td>";
print "<td height=22 width=270 style=text-align:center>Denominación</td>";
print "<td height=22 width=60 style=text-align:center>Tipo</td>";
print "<td height=22 width=200 style=text-align:center>Unidad Administrativa</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sqlaux = "";
	 if ($ls_estuniadm=='C')
	    {
	      $ls_sqlaux = "AND spg_ministerio_ua.coduac='".$ls_coduniadm."'";
		}
 	 $ls_sql="SELECT spg_ep1.codestpro1 as codestpro1,
	                 spg_ep1.denestpro1 as denestpro1,
					 spg_ep1.estcla,
					 spg_ministerio_ua.coduac as coduniadm, 
					 spg_ministerio_ua.denuac as denuniadm
			    FROM spg_ep1, spg_ministerio_ua
			   WHERE spg_ep1.codemp='".$ls_codemp."' 
				 AND spg_ep1.codestpro1 like '%".$ls_codestpro1."%'
				 AND spg_ep1.denestpro1 like '%".$ls_denestpro1."%' $ls_sqlaux
				 AND spg_ep1.codemp=spg_ministerio_ua.codemp
				 AND spg_ep1.codestpro1<>'-------------------------' ".
			"    AND codestpro1||estcla IN (SELECT SUBSTR(codintper,1,25)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' UNION SELECT SUBSTR(codintper,1,25)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru) 
			   ORDER BY spg_ep1.codestpro1 ASC";   
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
	      $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	    }
	 else
	    {
	      $li_numrows = $io_sql->num_rows($rs_data);
	      if ($li_numrows>0)
		     {
			   while ($row=$io_sql->fetch_row($rs_data))
			         {
					   echo "<tr class=celdas-blancas>";
					   $ls_codestpro1 = trim(substr($row["codestpro1"],-$li_loncodestpro1));
					   $ls_denestpro1 = $row["denestpro1"]; 
					   $ls_estcla     = $row["estcla"]; 
					   if ($ls_estcla=='P')
					      {
						    $ls_denestcla='Proyecto';
						  }
					   elseif($ls_estcla=='A')
					      {
						    $ls_denestcla='Acción';
						  }
					   $ls_coduniadm = $row["coduniadm"];
					   $ls_denuniadm = $row["denuniadm"];
					   echo "<td width=70 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_estcla');\">".$ls_codestpro1."</a></td>";
					   echo "<td width=270 style=text-align:left title='".$ls_denestpro1."'>".$ls_denestpro1."</td>";
					   echo "<td width=60 style=text-align:center>".$ls_denestcla."</td>";
					   echo "<td width=200 style=text-align:center title='".$ls_coduniadm.'-'.$ls_denuniadm."'>".$ls_coduniadm.'-'.$ls_denuniadm."</td>";
			           echo "</tr>";
					 }
			 } 
	      else
		     {
			   $io_msg->message("No se han definido registros !!!");
			 }
	    }
   }
echo "</table>";
?></p>
</div>
</div></form>
</body>
<script language="JavaScript">
  function aceptar(ls_codestpro1,ls_denestpro1,ls_estcla)
  {
    opener.document.form1.codestpro1.value = ls_codestpro1;
	opener.document.form1.denestpro1.value = ls_denestpro1;
	opener.document.form1.hidestcla.value  = ls_estcla;
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_estpro_op.php";
	  f.submit();
  }
</script>
</html>