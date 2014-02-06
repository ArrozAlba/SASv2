<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }
$la_empresa		  = $_SESSION["la_empresa"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];

$li_size1 = $li_loncodestpro1+10;
$li_size2 = $li_loncodestpro2+10;
$li_size3 = $li_loncodestpro3+10;
$li_size4 = $li_loncodestpro4+10;
$li_size5 = $li_loncodestpro5+10;

require_once("../shared/class_folder/sigesp_include.php");	
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");	

$io_include  = new sigesp_include();
$ls_connect  = $io_include->uf_conectar();
$io_msg      = new class_mensajes();
$io_sql      = new class_sql($ls_connect);

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion  = $_POST["operacion"];
   	 $ls_codestpro1 = $_POST["codestpro1"];
	 $ls_codestpro2 = $_POST["codestpro2"];
	 $ls_codestpro3 = $_POST["codestpro3"];
	 $ls_codestpro4 = $_POST["codestpro4"];
	 $ls_codestpro5 = $_POST["codestprog4"];
	 $ls_denestpro1 = $_POST["denestprog1"];
	 $ls_denestpro2 = $_POST["denestprog2"];
	 $ls_denestpro3 = $_POST["denestprog3"];
	 $ls_denestpro4 = $_POST["denestprog4"];
	 $ls_denestpro5 = $_POST["denominacion"];
     $ls_estcla     = $_POST["hidestcla"];
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
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["codestpro1"];
	 $ls_codestpro2 = $_GET["codestpro2"];
	 $ls_codestpro3 = $_GET["codestpro3"];
	 $ls_codestpro4 = $_GET["codestpro4"];
	 $ls_denestpro1 = $_GET["denestpro1"];
	 $ls_denestpro2 = $_GET["denestpro2"];
	 $ls_denestpro3 = $_GET["denestpro3"];
	 $ls_denestpro4 = $_GET["denestpro4"];
	 $ls_codestpro5 = "";
	 $ls_denestpro5 = "";
	 if(array_key_exists("tipo",$_GET))
		{
			$ls_tipo=$_GET["tipo"];
		}
		else
		{
			$ls_tipo="";
		}
	 $ls_estcla     = $_GET["hidestcla"];
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de <?php print $la_empresa["nomestpro5"] ?></title>
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
        <td height="21" colspan="2"><input name="hidestcla" type="hidden" id="hidestcla" value="<?php print $ls_estcla ?>">
          <input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $la_empresa["nomestpro5"] ?> </td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="21" style="text-align:right"><?php print $la_empresa["nomestpro1"]?></td>
        <td width="380" height="21" style="text-align:left"><input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_size1 ?>" maxlength="<?php print $li_loncodestpro1 ?>" readonly style="text-align:center">
            <input name="denestprog1" type="hidden" id="denestprog1" value="<?php print $ls_denestpro1 ?>">
        </td>
      </tr>
      <tr>
        <td height="21"><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td height="21"><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" value="<?php print  $ls_codestpro2 ?>" size="<?php print $li_size2 ?>" maxlength="<?php print $li_loncodestpro2 ?>" readonly style="text-align:center">
          <input name="denestprog2" type="hidden" id="denestprog2" value="<?php print $ls_denestpro2 ?>">
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right"><?php print $la_empresa["nomestpro3"]?></div></td>
        <td height="21"><div align="left">
          <input name="codestpro3" type="text" id="codestpro3" value="<?php print  $ls_codestpro3 ?>" size="<?php print $li_size3 ?>" maxlength="<?php print $li_loncodestpro3 ?>" readonly style="text-align:center">
          <input name="denestprog3" type="hidden" id="denestprog3" value="<?php print $ls_denestpro3 ?>">
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right"><?php print $la_empresa["nomestpro4"]?></div></td>
        <td height="21"><div align="left">
          <input name="codestpro4" type="text" id="codestpro4" value="<?php print  $ls_codestpro4 ?>" size="<?php print $li_size4 ?>" maxlength="<?php print $li_loncodestpro4 ?>" readonly style="text-align:center">
          <input name="denestprog4" type="hidden" id="denestprog4" value="<?php print $ls_denestpro4 ?>">
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right">Codigo</div></td>
        <td height="21"><div align="left">
          <input name="codestprog4" type="text" id="codestprog4"  size="<?php print $li_size5 ?>" maxlength="<?php print $li_loncodestpro5 ?>" style="text-align:center">
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right">Denominacion</div></td>
        <td height="21"><div align="left">
            <input name="denominacion" type="text" id="denominacion"  size="80" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
<?php
print "<table width=820 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=130 style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
print "<td width=130 style=text-align:center>".$la_empresa["nomestpro2"]."</td>";
print "<td width=130 style=text-align:center>".$la_empresa["nomestpro3"]."</td>";
print "<td width=130 style=text-align:center>".$la_empresa["nomestpro4"]."</td>";
print "<td width=130 style=text-align:center>Código</td>";
print "<td width=130 style=text-align:center>Denominación</td>";
print "<td width=40  style=text-align:center>Tipo</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
	 $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
	 $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
	 $ls_codestpro4 = str_pad($ls_codestpro4,25,0,0);
	 if (!empty($ls_codestpro5))
		{
		  $ls_codestpro5 = str_pad($ls_codestpro5,25,0,0);
		}
	 $ls_sql= "  SELECT distinct (spg_ep5.codestpro5), spg_ep5.codestpro1, spg_ep5.codestpro2, spg_ep5.codestpro3, ".
			  "    	    spg_ep5.codestpro4,spg_ep5.denestpro5, spg_ep5.estcla  ".
			  "		  FROM spg_ep5, spi_cuentas_estructuras                    ".
			  "		 WHERE spg_ep5.codemp='".$la_empresa["codemp"]."'          ". 
			  "		   AND spg_ep5.codestpro1 ='".$ls_codestpro1."'            ".
			  "		   AND spg_ep5.codestpro2 ='".$ls_codestpro2."'            ".
			  "		   AND spg_ep5.codestpro3 ='".$ls_codestpro3."'            ".
			  "		   AND spg_ep5.codestpro4 ='".$ls_codestpro4."'            ".
			  "		   AND spg_ep5.codestpro5 like '%".$ls_codestpro5."%'      ".
			  "		   AND spg_ep5.denestpro5 like '%".$ls_denestpro5."%'      ".
			  "		   AND spg_ep5.estcla = '".$ls_estcla."'                   ".
			  "		   AND spi_cuentas_estructuras.codemp=spg_ep5.codemp       ".
			  "		   AND spi_cuentas_estructuras.codestpro1 =spg_ep5.codestpro1 ". 
			  "		   AND spi_cuentas_estructuras.codestpro2 =spg_ep5.codestpro2 ".
			  "		   AND spi_cuentas_estructuras.codestpro3 =spg_ep5.codestpro3 ".
			  "		   AND spi_cuentas_estructuras.codestpro4 =spg_ep5.codestpro4 ".
			  "		   AND spi_cuentas_estructuras.codestpro5 =spg_ep5.codestpro5 ". 
			  "		   AND spi_cuentas_estructuras.estcla  =spg_ep5.estcla ".
			  "		 ORDER BY spg_ep5.codestpro1,spg_ep5.codestpro2,spg_ep5.codestpro3,spg_ep5.codestpro4";
					 
  	
	 $rs_data=$io_sql->select($ls_sql);
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
					   print "<tr class=celdas-blancas>";
					   $ls_codestpro1 = trim(substr($row["codestpro1"],-$li_loncodestpro1));
					   $ls_codestpro2 = trim(substr($row["codestpro2"],-$li_loncodestpro2));
					   $ls_codestpro3 = trim(substr($row["codestpro3"],-$li_loncodestpro3));
					   $ls_codestpro4 = trim(substr($row["codestpro4"],-$li_loncodestpro4));
					   $ls_codestpro5 = trim(substr($row["codestpro5"],-$li_loncodestpro5));
					   $ls_denestpro5 = $row["denestpro5"]; 
					   $ls_estcla     = $row["estcla"]; 
					   if ($ls_estcla=='P')
					      {
						    $ls_denestcla='Proyecto';
						  }
						  elseif($ls_estcla=='A')
					      {
						    $ls_denestcla='Acción';
						  }
				       if ($ls_tipo=="")
				          {
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro5."</a></td>";
				          }
				       if ($ls_tipo=="apertura")
						  {
						    print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro5."</a></td>";
						  }
					   if ($ls_tipo=="progrep")
						  {
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro5."</a></td>";
						  }
					   if ($ls_tipo=="reporte")		
						  { 
						    print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro5."</a></td>";
					 	  }
					   if ($ls_tipo=="rephas")		
						  {
						    print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro1."</a></td>";
						    print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro2."</a></td>";
						    print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro3."</a></td>";
						    print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=130 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro5."</a></td>";
						  }
					   print "<td width=130 style=text-align:left>".$ls_denestpro5."</td>";
					   print "<td width=40  style=text-align:center>".$ls_denestcla."</td>";
					   print "</tr>";	
					 }
			 } 
	      else
		     {
			   $io_msg->message("No se han definido registros !!!");
			 }
	    }
}
print "</table>";
?>
</div>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(ls_codestpro5,ls_denestpro5)
  {
    opener.document.form1.denestpro5.value=ls_denestpro5;
	opener.document.form1.codestpro5.value=ls_codestpro5;
	close();
  }
  
  function aceptar_apertura(ls_codestpro5,ls_denestpro5)
  {
    opener.document.form1.denestpro5.value=ls_denestpro5;
	opener.document.form1.codestpro5.value=ls_codestpro5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(ls_codestpro5,ls_denestpro5)
  {
    opener.document.form1.denestpro5.value=ls_denestpro5;
	opener.document.form1.codestpro5.value=ls_codestpro5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_apertura(ls_codestpro5,ls_denestpro5)
  {
    opener.document.form1.denestpro5.value=ls_denestpro5;
	opener.document.form1.codestpro5.value=ls_codestpro5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(ls_codestpro5)
  {
	opener.document.form1.codestpro5.value=ls_codestpro5;
	opener.document.form1.codestpro5.readOnly=true;
	close();
  }
  
  function aceptar_rephas(ls_codestpro5)
  {
	opener.document.form1.codestpro5h.value=ls_codestpro5;
	opener.document.form1.codestpro5h.readOnly=true;
	close();
  }
  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_spi_cat_public_estpro5.php?tipo=<?php print $ls_tipo; ?>";
  f.submit();
  }
</script>
</html>