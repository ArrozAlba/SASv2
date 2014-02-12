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

	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_mensajes.php");

	$io_include = new sigesp_include();
	$ls_connect = $io_include->uf_conectar();
	$io_msg     = new class_mensajes();	
	$io_sql     = new class_sql($ls_connect);

	$ls_codemp    = $la_empresa["codemp"];
	$li_estmodest = $_SESSION["la_empresa"]["estmodest"];
	
	if (array_key_exists("operacion",$_POST))
	   {
		 if (array_key_exists("tipo",$_GET))
		    {
			  $ls_tipo = $_GET["tipo"];
		    }
		 else
		    {
			  $ls_tipo = "";
		    }
		 $ls_estcla		= $_POST["estcla"];
		 $ls_operacion  = $_POST["operacion"];
		 $ls_codestpro1 = $_POST["codestpro1"];
		 $ls_codestpro2 = $_POST["codestpro2"];
		 $ls_codestpro3 = $_POST["txtcodestprog3"];
		 $ls_denestpro1 = $_POST["denestprog1"];
		 $ls_denestpro2 = $_POST["denestprog2"];		 
		 $ls_denestpro3 = $_POST["denominacion"];
	   }
	else
	   {
		 $ls_operacion="BUSCAR";
		 if (array_key_exists("codestpro1",$_GET))
		    {
			  $ls_estcla	 = $_GET["estcla"];
			  $ls_codestpro1 = $_GET["codestpro1"];
			  $ls_codestpro2 = $_GET["codestpro2"];
			  $ls_codestpro3 = "";
			  if (array_key_exists("denestpro1",$_GET))
				 {
				   $ls_denestpro1 = $_GET["denestpro1"];
				 }
			  else
				 {
				   $ls_denestpro1 = "";
				 }
			  if (array_key_exists("denestpro2",$_GET))
				 {
				   $ls_denestpro2 = $_GET["denestpro2"];
				 }
			  else
				 {
				   $ls_denestpro2 = "";
				 }
		      $ls_denestpro3 = "";
			}
		 else
		    {
			  $ls_estcla     = "";
			  $ls_codestpro1 = "";
			  $ls_codestpro2 = "";
			  $ls_codestpro3 = "";
			  $ls_denestpro1 = "";
			  $ls_denestpro2 = "";
			  $ls_codestpro3 = "";
			  $ls_denestpro3 = "";
		    }
		 if (array_key_exists("tipo",$_GET))
		    {
			  $ls_tipo = $_GET["tipo"];
		    }
		 else
		    {
			  $ls_tipo = "";
			}
	   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 3 - <?php print $la_empresa["nomestpro3"] ?></title>
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
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">Catálogo de Programática Nivel 3 - <?php print $la_empresa["nomestpro3"] ?></td>
       </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right"><?php print $la_empresa["nomestpro1"]?></div></td>
        <td width="380" height="22"><div align="left">
          <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>" readonly style="text-align:center">        
          <input name="denestprog1" type="hidden" id="denestprog1" value="<?php print $ls_denestprog1 ?>">
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td height="22"><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" value="<?php print  $ls_codestpro2?>" size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>" readonly style="text-align:center">
          <input name="denestprog2" type="hidden" id="denestprog2" value="<?php print $ls_denestprog2?>">
</div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td height="22"><div align="left">
          <input name="txtcodestprog3" type="text" id="txtcodestprog3" size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>" style="text-align:center">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="80" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	 <div align="center"><br>
         <?php
	echo "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	echo "<tr class=titulo-celda>";
	echo "<td>".$la_empresa["nomestpro1"]."</td>";
	echo "<td>".$la_empresa["nomestpro2"]."</td>";
	echo "<td>Código </td>";
	echo "<td>Estatus </td>";
	echo "<td>Denominación</td>";
	echo "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		if (!empty($ls_codestpro1) && !empty($ls_codestpro2))
		   {
		     $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
		     $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
		   }
		$ls_sql=" SELECT codestpro1,codestpro2,codestpro3,estcla,denestpro3 ".
		        "  FROM spg_ep3 ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codestpro1 LIKE '%".$ls_codestpro1."%'".
				"   AND codestpro2 LIKE '%".$ls_codestpro2."%'  ".
				"   AND estcla LIKE '%".$ls_estcla."%'  ".
		        "   AND codestpro3 like '%".$ls_codestpro3."%'".
				"   AND denestpro3 like '%".$ls_denestpro3."%'";
		
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
					  $ls_codestpro1 = substr($row["codestpro1"],(strlen($row["codestpro1"])-$li_len1),$li_len1);
					  $ls_codestpro2 = substr($row["codestpro2"],(strlen($row["codestpro2"])-$li_len2),$li_len2);
					  $ls_codestpro3 = substr($row["codestpro3"],(strlen($row["codestpro3"])-$li_len3),$li_len3);
					  $ls_denestpro3 = $row["denestpro3"];
					  $ls_estcla	 = $row["estcla"];
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
			          if ($ls_tipo=="")
						 {
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3');\">".trim($ls_codestpro3)."</a></td>";
						 }
					  if ($ls_tipo=="apertura")
					     {
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro3','$ls_denestpro3','$li_estmodest');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro3','$ls_denestpro3','$li_estmodest');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro3','$ls_denestpro3','$li_estmodest');\">".trim($ls_codestpro3)."</a></td>";
						 }
					  if ($ls_tipo=="progrep")
						 {
						   echo "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro3','$ls_denestpro3','$li_estmodest');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro3','$ls_denestpro3','$li_estmodest');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro3','$ls_denestpro3','$li_estmodest');\">".trim($ls_codestpro3)."</a></td>";
						 }
					  if ($ls_tipo=="reporte")		
					     {
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro3');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro3');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro3');\">".trim($ls_codestpro3)."</a></td>";
				         }
				      if ($ls_tipo=="rephas")		
						 {
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro3');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro3');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro3');\">".trim($ls_codestpro3)."</a></td>";
						 }
					  if ($ls_tipo=="completo")		
						 {
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_estcla');\">".trim($ls_codestpro3)."</a></td>";
						 }
					  echo "<td width=30>".$ls_estatus."</td>";
					  echo "<td width=130 align=\"left\" title='$ls_denestpro3'>".ltrim($ls_denestpro3)."</td>";
					  echo "</tr>";
			        }
			}
	     else
		    {
			  $io_msg->message("No se han definido Cuentas Presupuestarias !!!");			
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

  function aceptar(codestprog,deno)
  {
    opener.document.form1.txtdenestpro3.value=deno;
	opener.document.form1.txtcodestpro3.value=codestprog;
	close();
  }
  
  function aceptar_apertura(codestprog,deno,li_estmodest)
  {
    opener.document.form1.denestpro3.value=deno;
	opener.document.form1.codestpro3.value=codestprog;
	if(li_estmodest==1)
	{
		opener.document.form1.operacion.value="CARGAR";
		opener.document.form1.submit();
		close();
	}
	else
	{
		close();
	}
  }
  
  function aceptar_progrep(codestprog,deno,li_estmodest)
  {
    opener.document.form1.denestpro3.value=deno;
	opener.document.form1.codestpro3.value=codestprog;
	if(li_estmodest==1)
	{
		opener.document.form1.operacion.value="CARGAR";
		opener.document.form1.submit();
		close();
	}
	else
	{
		close();
	}
  }
  
  function aceptar_rep(codestprog)
  {
	opener.document.form1.codestpro3.value=codestprog;
	opener.document.form1.codestpro3.readOnly=true;
	close();
  }
  function aceptar_completo(codestprog1,codestprog2,codestprog3,estcla)
  {
	opener.document.form1.estcla.value=estcla;
	opener.document.form1.txtcodestpro1.value=codestprog1;
	opener.document.form1.txtcodestpro2.value=codestprog2;
	opener.document.form1.txtcodestpro3.value=codestprog3;
	close();
  }
  
  function aceptar_rephas(codestprog)
  {
	opener.document.form1.codestpro3h.value=codestprog;
	opener.document.form1.codestpro3h.readOnly=true;
	close();
  }
  
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_public_estpro3.php?tipo=<?php print $ls_tipo; ?>";
	  f.submit();
  }
</script>
</html>