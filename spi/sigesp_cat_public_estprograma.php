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
require_once("../shared/class_folder/class_sql.php");
	
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql		= new class_sql($ls_conect);

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro5 = $_POST["txtcodestpro5"];
	 $ls_denestpro5 = $_POST["denominacion"];
	 if (array_key_exists("tipo",$_GET))
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
	 $ls_codestpro5 = "";
  	 $ls_denestpro5 = "";
   	 if (array_key_exists("tipo",$_GET))
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
  	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $la_empresa["nomestpro5"] ?> </td>
       </tr>
      <tr>
        <td width="88" height="13">&nbsp;</td>
        <td width="460" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo</td>
        <td height="22"><input name="txtcodestpro5" type="text" id="txtcodestpro5"  size="<?php print $li_size5 ?>" maxlength="<?php print $li_loncodestpro5 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"> <input name="denominacion" type="text" id="denominacion"  size="80" maxlength="100">
        </td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
<?php
print "<table width=760 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=120 style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
print "<td width=120 style=text-align:center>".$la_empresa["nomestpro2"]."</td>";
print "<td width=120 style=text-align:center>".$la_empresa["nomestpro3"]."</td>";
print "<td width=120 style=text-align:center>".$la_empresa["nomestpro4"]."</td>";
print "<td width=120 style=text-align:center>Código</td>";
print "<td width=120 style=text-align:center>Denominación</td>";
print "<td width=40  style=text-align:center>Tipo</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codestpro5))
	    {
		  $ls_codestpro5 = str_pad($ls_codestpro5,25,0,0);
		}
	 
	 $ls_sql = " SELECT a.codestpro1 as codestpro1,a.denestpro1 as denestpro1,b.codestpro2 as codestpro2, ".
 			   "        b.denestpro2 as denestpro2,c.codestpro3 as codestpro3,c.denestpro3 as denestpro3, ".
               "        d.codestpro4 as codestpro4,d.denestpro4 as denestpro4,e.codestpro5 as codestpro5, ".
               "        e.denestpro5 as denestpro5, e.estcla ".
               "   FROM spg_ep1 a,spg_ep2 b,spg_ep3 c,spg_ep4 d,spg_ep5 e ".
               "  WHERE e.codemp='".$la_empresa["codemp"]."'
  					AND e.codestpro5 like '%".$ls_codestpro5."%' 
					AND e.denestpro5 like '%".$ls_denestpro5."%'
			        AND a.codemp=b.codemp 
				    AND b.codemp=c.codemp 
				    AND c.codemp=d.codemp 
				    AND d.codemp=e.codemp 
				    AND a.codestpro1=b.codestpro1 
				    AND a.codestpro1=c.codestpro1  
				    AND a.codestpro1=d.codestpro1  
					AND a.codestpro1=e.codestpro1  
					AND b.codestpro2=c.codestpro2  
					AND b.codestpro2=d.codestpro2  
					AND b.codestpro2=d.codestpro2  
					AND b.codestpro2=e.codestpro2  
					AND c.codestpro3=d.codestpro3  
					AND c.codestpro3=e.codestpro3  
					AND d.codestpro4=e.codestpro4
					AND a.estcla=b.estcla
					AND c.estcla=a.estcla
					AND d.estcla=a.estcla
					AND e.estcla=a.estcla";
     
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
					   print "<tr class=celdas-blancas>";
					   $ls_codestpro1 = trim(substr($row["codestpro1"],-$li_loncodestpro1));
					   $ls_codestpro2 = trim(substr($row["codestpro2"],-$li_loncodestpro2));
					   $ls_codestpro3 = trim(substr($row["codestpro3"],-$li_loncodestpro3));
					   $ls_codestpro4 = trim(substr($row["codestpro4"],-$li_loncodestpro4));
					   $ls_codestpro5 = trim(substr($row["codestpro5"],-$li_loncodestpro5));
					   $ls_denestpro1 = $row["denestpro1"]; 
					   $ls_denestpro2 = $row["denestpro2"]; 
					   $ls_denestpro3 = $row["denestpro3"]; 
					   $ls_denestpro4 = $row["denestpro4"]; 
					   $ls_denestpro5 = $row["denestpro5"]; 
					   $ls_estcla     = $row["estcla"]; 
					   if ($ls_estcla=='P')
					      {
						    $ls_denestcla='Actividad';
						  }
				       if ($ls_tipo=="")
				          {
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro2','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro2','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro2','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro2','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro2','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro5."</a></td>";
				          }
				       if ($ls_tipo=="apertura")
						  {
						    print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro5."</a></td>";
						  }
					   if ($ls_tipo=="progrep")
						  {
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denestpro5');\">".$ls_codestpro5."</a></td>";
						  }
					   if ($ls_tipo=="reporte")		
						  { 
						    print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro1."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro2."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro3."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro5');\">".$ls_codestpro5."</a></td>";
					 	  }
					   if ($ls_tipo=="rephas")		
						  {
						    print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro1."</a></td>";
						    print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro2."</a></td>";
						    print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro3."</a></td>";
						    print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro4."</a></td>";
							print "<td width=120 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro5');\">".$ls_codestpro5."</a></td>";
						  }
					   print "<td width=120 style=text-align:left>".$ls_denestpro5."</td>";
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

  function aceptar(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                   codestprog5,denestprog5)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3.value=codestprog3;
	opener.document.form1.codestpro4.value=codestprog4;
	opener.document.form1.codestpro5.value=codestprog5;
    opener.document.form1.denestpro1.value=denestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
    opener.document.form1.denestpro3.value=denestprog3;
    opener.document.form1.denestpro4.value=denestprog4;
    opener.document.form1.denestpro5.value=denestprog5;
	close();
  }
  
  function aceptar_apertura(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                            codestprog5,denestprog5)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=denestprog3;
	opener.document.form1.codestpro3.value=codestprog3;
    opener.document.form1.denestpro4.value=denestprog4;
	opener.document.form1.codestpro4.value=codestprog4;
    opener.document.form1.denestpro5.value=denestprog5;
	opener.document.form1.codestpro5.value=codestprog5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                           codestprog5,denestprog5)
  {
    opener.document.form1.denestpro1.value=denestprog1;
	opener.document.form1.codestpro1.value=codestprog1;
    opener.document.form1.denestpro2.value=denestprog2;
	opener.document.form1.codestpro2.value=codestprog2;
    opener.document.form1.denestpro3.value=denestprog3;
	opener.document.form1.codestpro3.value=codestprog3;
    opener.document.form1.denestpro4.value=denestprog4;
	opener.document.form1.codestpro4.value=codestprog4;
    opener.document.form1.denestpro5.value=denestprog5;
	opener.document.form1.codestpro5.value=codestprog5;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3.value=codestprog3;
	opener.document.form1.codestpro4.value=codestprog4;
	opener.document.form1.codestpro5.value=codestprog5;
	opener.document.form1.codestpro5.readOnly=true;
	close();
  }
  
  function aceptar_rephas(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5)
  {
	opener.document.form1.codestpro1h.value=codestprog1;
	opener.document.form1.codestpro2h.value=codestprog2;
	opener.document.form1.codestpro3h.value=codestprog3;
	opener.document.form1.codestpro4h.value=codestprog4;
	opener.document.form1.codestpro5h.value=codestprog5;
	opener.document.form1.codestpro5h.readOnly=true;
	close();
  }

  
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_public_estprograma.php?tipo=<?php print $ls_tipo; ?>";
  f.submit();
  }
</script>
</html>
