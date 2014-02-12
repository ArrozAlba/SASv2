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

$li_size1 = $li_loncodestpro1+10;
$li_size2 = $li_loncodestpro2+10;
$li_size3 = $li_loncodestpro3+10;

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("class_funciones_ingreso.php");
$fun_ingresos=new class_funciones_ingreso("../");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql		= new class_sql($ls_conect);

if (array_key_exists("operacion",$_POST))
   {
	$ls_operacion  = $_POST["operacion"];
	$ls_codestpro3 = $_POST["txtcodestprog3"];
	$ls_denestpro3 = $_POST["denominacion"];
	if (array_key_exists("tipo",$_GET))
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
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro3 = "";
	 $ls_denestpro3 = "";
	 if (array_key_exists("tipo",$_GET))
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
 $ls_buscar=$fun_ingresos->uf_obtenervalor_get("buscar","");   

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de <?php print $la_empresa["nomestpro3"] ?></title>
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
        <td height="21" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de <?php print $la_empresa["nomestpro3"] ?></td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="21" style="text-align:right">C&oacute;digo</td>
        <td width="380" height="21"><input name="txtcodestprog3" type="text" id="txtcodestprog3"  size="<?php print $li_size3 ?>" maxlength="<?php print $li_loncodestpro3 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right">Denominaci&oacute;n</td>
        <td height="21" style="text-align:left"><input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100" style="text-align:left">
        </td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
<div align="center">
<p><br>
<?php
print "<table width=720 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=160 style=text-align:center>".$la_empresa["nomestpro1"]."</td>";
print "<td width=160 style=text-align:center>".$la_empresa["nomestpro2"]."</td>";
print "<td width=160 style=text-align:center>Código</td>";
print "<td width=200 style=text-align:center>Denominación</td>";
print "<td width=40  style=text-align:center>Tipo</td>";
print "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 if (!empty($ls_codestpro3))
	    {
		  $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
		}
	 
	 $ls_sql = "SELECT DISTINCT(spg_ep3.codestpro3) as codestpro3,
	                   spg_ep1.codestpro1 as codestpro1,spg_ep1.denestpro1 as denestpro1,
	                   spg_ep2.codestpro2 as codestpro2,spg_ep2.denestpro2 as denestpro2,
				  	   spg_ep3.denestpro3 as denestpro3, spg_ep3.estcla
			      FROM spg_ep1, spg_ep2, spg_ep3, spi_cuentas_estructuras
			     WHERE spg_ep1.codemp='".$la_empresa["codemp"]."'
			       AND spg_ep3.codestpro3 like '%".$ls_codestpro3."%' 
				   AND spg_ep3.denestpro3 like '%".$ls_denestpro3."%'
				   AND spg_ep1.codemp=spg_ep2.codemp 
			       AND spg_ep1.codestpro1=spg_ep2.codestpro1
			 	   AND spg_ep1.codemp=spg_ep3.codemp 
				   AND spg_ep1.codestpro1=spg_ep3.codestpro1  
				   AND spg_ep2.codemp=spg_ep3.codemp
				   AND spg_ep2.codestpro1=spg_ep3.codestpro1
			 	   AND spg_ep2.codestpro2=spg_ep3.codestpro2
				   AND spg_ep1.estcla=spg_ep2.estcla 
				   AND spg_ep2.estcla=spg_ep3.estcla
				   AND spg_ep3.estcla=spg_ep1.estcla
				   AND spg_ep3.codestpro1=spi_cuentas_estructuras.codestpro1
				   AND spg_ep3.codestpro2=spi_cuentas_estructuras.codestpro2
				   AND spg_ep3.codestpro3=spi_cuentas_estructuras.codestpro3
				   AND spg_ep1.codestpro1<>'-------------------------'
				 ORDER BY spg_ep1.codestpro1";
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
					   $ls_denestpro1 = $row["denestpro1"];
					   $ls_denestpro2 = $row["denestpro2"];
					   $ls_denestpro3 = $row["denestpro3"]; 
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
					        print "<td width=160 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro1."</a></td>";
					        print "<td width=160 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro2."</a></td>";
					        print "<td width=160 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
					 	  }
					   if ($ls_tipo=="apertura")
						  {
						    print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro2."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
						  }
		               if ($ls_tipo=="progrep")
						  {
						    print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro2."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_progrep('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
						  }
					   if ($ls_tipo=="reporte")		
					      {
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".$ls_codestpro2."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
				 	      }
					   if ($ls_tipo=="rephas")		
						  {
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".$ls_codestpro2."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
						  }
						  if ($ls_tipo=="comprobante")		
						  {
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_2('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro1."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_2('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro2."</a></td>";
							print "<td width=160 style=text-align:center><a href=\"javascript: aceptar_2('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_estcla');\">".$ls_codestpro3."</a></td>";
						  }
 				       print "<td width=200 style=text-align:left>".$ls_denestpro3."</td>";
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
</p>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_estcla)
  {
    opener.document.form1.hidtipestpro.value=ls_estcla;
	opener.document.form1.denestpro1.value=ls_denestpro1;
	opener.document.form1.codestpro1.value=ls_codestpro1;
    opener.document.form1.denestpro2.value=ls_denestpro2;
	opener.document.form1.codestpro2.value=ls_codestpro2;
    opener.document.form1.denestpro3.value=ls_denestpro3;
	opener.document.form1.codestpro3.value=ls_codestpro3;
	//opener.document.form1.estcla.value=ls_estcla;
	close();
  }
  
  function aceptar_apertura(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_estcla)
  {
    opener.document.form1.denestpro1.value=ls_denestpro1;
	opener.document.form1.codestpro1.value=ls_codestpro1;
    opener.document.form1.denestpro2.value=ls_denestpro2;
	opener.document.form1.codestpro2.value=ls_codestpro2;
    opener.document.form1.denestpro3.value=ls_denestpro3;
	opener.document.form1.codestpro3.value=ls_codestpro3; 
	opener.document.form1.estcla.value=ls_estcla;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_estcla)
  {
    opener.document.form1.denestpro1.value=ls_denestpro1;
	opener.document.form1.codestpro1.value=ls_codestpro1;
    opener.document.form1.denestpro2.value=ls_denestpro2;
	opener.document.form1.codestpro2.value=ls_codestpro2;
    opener.document.form1.denestpro3.value=ls_denestpro3;
	opener.document.form1.codestpro3.value=ls_codestpro3;
	opener.document.form1.estcla.value=ls_estcla;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_estcla)
  {
	opener.document.form1.codestpro1.value=ls_codestpro1;
	opener.document.form1.codestpro2.value=ls_codestpro2;
	opener.document.form1.codestpro3.value=ls_codestpro3;
	opener.document.form1.estclades.value=ls_estcla;
	opener.document.form1.codestpro3.readOnly=true;
	close();
  }
  
  function aceptar_rephas(ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_estcla)
  {
	opener.document.form1.codestpro1h.value=ls_codestpro1;
	opener.document.form1.codestpro2h.value=ls_codestpro2;
	opener.document.form1.codestpro3h.value=ls_codestpro3;
	opener.document.form1.estclahas.value=ls_estcla;
	opener.document.form1.codestpro3h.readOnly=true;
	close();
  }
  
  function aceptar_2(ls_codestpro1,ls_denestpro1,ls_codestpro2,ls_denestpro2,ls_codestpro3,ls_denestpro3,ls_estcla)
  {
    //opener.document.form1.hidtipestpro.value=ls_estcla;
	opener.document.form1.denestpro1.value=ls_denestpro1; 
	opener.document.form1.codestpro1.value=ls_codestpro1;
    opener.document.form1.denestpro2.value=ls_denestpro2;
	opener.document.form1.codestpro2.value=ls_codestpro2;
    opener.document.form1.denestpro3.value=ls_denestpro3;
	opener.document.form1.codestpro3.value=ls_codestpro3;
	opener.document.form1.estcla.value=ls_estcla;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_public_estpro.php?tipo=<?php print $ls_tipo;?>&estcla=<?PHP print $ls_estcla;?>";
  f.submit();
  }
</script>
</html>