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
		$ls_estcla=$_POST["estcla"];
		if(array_key_exists("denestpro1",$_POST))
		{
			$ls_denestprog1=$_POST["denestpro1"];
		}
		else
		{
			$ls_denestprog1="";
		}
		
		$ls_codestprog2=$_POST["codestpro2"];
		if(array_key_exists("denestpro2",$_POST))
		{
			$ls_denestpro2=$_POST["denestpro2"];
		}
		else
		{
			$ls_denestpro2="";
		}
		
		$ls_codestprog3=$_POST["codestpro3"];
		if(array_key_exists("denestpro3",$_POST))
		{
			$ls_denestpro3=$_POST["denestpro3"];
		}
		else
		{
			$ls_denestpro3="";
		}

		$ls_codestprog4=$_POST["codestpro4"];
		if(array_key_exists("denestpro4",$_POST))
		{
			$ls_denestpro4=$_POST["denestpro4"];
		}
		else
		{
			$ls_denestpro4="";
		}
		
		$ls_codigo="%".$_POST["codestprog4"]."%";
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
		
		$ls_codestprog1=$_GET["codestpro1"];
		$ls_estcla=$_POST["estcla"];
		if(array_key_exists("denestpro1",$_GET))
		{
			$ls_denestprog1=$_GET["denestpro1"];
		}
		else
		{
			$ls_denestprog1="";
		}
		$ls_codestprog2=$_GET["codestpro2"];
			if(array_key_exists("denestpro2",$_GET))
		{
			$ls_denestprog2=$_GET["denestpro2"];
		}
		else
		{
			$ls_denestprog2="";
		}

		$ls_codestprog3=$_GET["codestpro3"];
			if(array_key_exists("denestpro3",$_GET))
		{
			$ls_denestprog3=$_GET["denestpro3"];
		}
		else
		{
			$ls_denestprog3="";
		}
		
		$ls_codestprog4=$_GET["codestpro4"];
			if(array_key_exists("denestpro4",$_GET))
		{
			$ls_denestprog4=$_GET["denestpro4"];
		}
		else
		{
			$ls_denestprog4="";
		}
		
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
<title>Catalogo de Programatica Nivel 5 <?php print $la_empresa["nomestpro5"] ?></title>
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
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $la_empresa["nomestpro5"] ?>  </td>
    	</tr>
	 </table>
	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118"><div align="right"><?php print $la_empresa["nomestpro1"]?></div></td>
        <td width="380"><div align="left">
            <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestprog1 ?>" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>" readonly style="text-align:center">
            <input name="denestprog1" type="hidden" id="denestprog1" value="<?php print $ls_denestprog1 ?>">
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" value="<?php print  $ls_codestprog2?>" size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>" readonly style="text-align:center">
          <input name="denestprog2" type="hidden" id="denestprog2" value="<?php print $ls_denestprog2?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $la_empresa["nomestpro3"]?></div></td>
        <td><div align="left">
          <input name="codestpro3" type="text" id="codestpro3" value="<?php print  $ls_codestprog3 ?>"  size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>" readonly style="text-align:center">
          <input name="denestprog3" type="hidden" id="denestprog3" value="<?php print $ls_denestprog3 ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $la_empresa["nomestpro4"]?></div></td>
        <td><div align="left">
          <input name="codestpro4" type="text" id="codestpro4" value="<?php print  $ls_codestprog4 ?>"  size="<?php print ($li_len4+10); ?>" maxlength="<?php print $li_len4; ?>" readonly style="text-align:center">
          <input name="denestprog4" type="hidden" id="denestprog4" value="<?php print $ls_denestprog4 ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="codestprog5" type="text" id="codestprog5"   size="<?php print ($li_len5+10); ?>" maxlength="<?php print $li_len5; ?>" style="text-align:center">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominacion</div></td>
        <td><div align="left">
            <input name="denominacion" type="text" id="denominacion"  size="80" maxlength="100">
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
	print "<td>".$la_empresa["nomestpro2"]."</td>";
	print "<td>".$la_empresa["nomestpro3"]."</td>";
	print "<td>".$la_empresa["nomestpro4"]."</td>";
	print "<td>Código </td>";
	print "<td>Estatus </td>";
	print "<td>Denominación</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
	    if(($ls_codestprog1!="")&&($ls_codestprog2!="")&&($ls_codestprog3!="")&&($ls_codestprog4!=""))
		{
			$ls_codestprog1=$io_function->uf_cerosizquierda($ls_codestprog1,25);
			$ls_codestprog2=$io_function->uf_cerosizquierda($ls_codestprog2,25);
			$ls_codestprog3=$io_function->uf_cerosizquierda($ls_codestprog3,25);
			$ls_codestprog4=$io_function->uf_cerosizquierda($ls_codestprog4,25);
		}
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla, denestpro5 ".
                "  FROM   spg_ep5 ".
                " WHERE  codemp='".$ls_codemp."'".
				"   AND codestpro1 like '%".$ls_codestprog1."%'".
				"   AND codestpro2 like '%".$ls_codestprog2."%'".
				"   AND codestpro3 like '%".$ls_codestprog3."%'".
                "   AND codestpro4 like '%".$ls_codestprog4."%'".
                "   AND estcla like '%".$ls_estcla."%'".
				"   AND codestpro5 like '".$ls_codigo."' ".               
			    "   AND denestpro5 like '".$ls_denominacion."' ";
		$rs_data=$io_sql->select($ls_sql);
		$data=$rs_data;
		if($row=$io_sql->fetch_row($rs_data))
		{
			$data=$io_sql->obtener_datos($rs_data);
			$li_arrcols=array_keys($data);
			$li_totcol=count($li_arrcols);
			$ds->data=$data;
			$li_totrow=$ds->getRowCount("codestpro5");
			for($z=1;$z<=$li_totrow;$z++)
			{
				print "<tr class=celdas-blancas>";
				$codestprog1=substr($la_data["codestpro1"][$z],(strlen($la_data["codestpro1"][$z])-$li_len1),$li_len1);
				$codestprog2=substr($la_data["codestpro2"][$z],(strlen($la_data["codestpro2"][$z])-$li_len2),$li_len2);
				$codestprog3=substr($la_data["codestpro3"][$z],(strlen($la_data["codestpro3"][$z])-$li_len3),$li_len3);
				$codestprog4=substr($la_data["codestpro4"][$z],(strlen($la_data["codestpro4"][$z])-$li_len4),$li_len4);
				$codigo=substr($la_data["codestpro5"][$z],(strlen($la_data["codestpro5"][$z])-$li_len5),$li_len5);
				$denominacion=$data["denestpro5"][$z];					
				$ls_estcla=$data["estcla"][$z];
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
				if($ls_tipo=="")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".
					trim($codestprog1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".
					trim($codestprog2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".
					trim($codestprog3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".
					trim($codestprog4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$codigo','$denominacion');\">".
					trim($codigo)."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="apertura")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codigo','$denominacion');\">".
					trim($codestprog1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codigo','$denominacion');\">".
					trim($codestprog2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codigo','$denominacion');\">".
					trim($codestprog3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codigo','$denominacion');\">".
					trim($codestprog4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$codigo','$denominacion');\">".
					trim($codigo)."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="progrep")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codigo','$denominacion');\">".
					trim($codestprog1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codigo','$denominacion');\">".
					trim($codestprog2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codigo','$denominacion');\">".
					trim($codestprog3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codigo','$denominacion');\">".
					trim($codestprog4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$codigo','$denominacion');\">".
					trim($codigo)."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codigo');\">".
					trim($codestprog1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codigo');\">".
					trim($codestprog2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codigo');\">".
					trim($codestprog3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codigo');\">".
					trim($codestprog4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$codigo');\">".
					trim($codigo)."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codigo');\">".
					trim($codestprog1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codigo');\">".
					trim($codestprog2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codigo');\">".
					trim($codestprog3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codigo');\">".
					trim($codestprog4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$codigo');\">".
					trim($codigo)."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="completo")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo','$ls_estcla');\">".
					trim($codestprog1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo','$ls_estcla');\">".
					trim($codestprog2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo','$ls_estcla');\">".
					trim($codestprog3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo','$ls_estcla');\">".
					trim($codestprog4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_completo('$codestprog1','$codestprog2','$codestprog3','$codestprog4','$codigo','$ls_estcla');\">".
					trim($codigo)."</a></td>";
					print "<td width=30>".$ls_estatus."</td>";
					print "<td width=130 align=\"left\">".trim($denominacion)."</td>";
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

  function aceptar(codestprog,deno)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
	close();
  }
  
  function aceptar_apertura(codestprog,deno)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog,deno)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_apertura(codestprog,deno)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(codestprog)
  {
	opener.document.form1.codestpro5.value=codestprog;
	opener.document.form1.codestpro5.readOnly=true;
	close();
  }
  
  function aceptar_rephas(codestprog)
  {
	opener.document.form1.codestpro5h.value=codestprog;
	opener.document.form1.codestpro5h.readOnly=true;
	close();
  }
  
  function aceptar_completo(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5,estcla)
  {
	opener.document.form1.estcla.value=estcla;
	opener.document.form1.txtcodestpro1.value=codestprog1;
	opener.document.form1.txtcodestpro2.value=codestprog2;
	opener.document.form1.txtcodestpro3.value=codestprog3;
	opener.document.form1.txtcodestpro4.value=codestprog4;
	opener.document.form1.txtcodestpro5.value=codestprog5;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_public_estpro5.php?tipo=<?php print $ls_tipo; ?>";
  f.submit();
  }
</script>
</html>