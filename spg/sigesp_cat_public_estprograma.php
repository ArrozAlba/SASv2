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
		$ls_codigo="%".$_POST["codestpro5"]."%";
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
	$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
	$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
	$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
	$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
	$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
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
        <td width="118"><div align="right">Codigo</div></td>
        <td width="380"><input name="codestpro5" type="text" id="codestpro5"  size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" style="text-align:center"></td>
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
	print "<td>Denominación</td>";
    print "<td>Estatus</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
		$ls_sql=" SELECT a.codestpro1 as codestpro1, a.denestpro1 as denestpro1, b.codestpro2 as codestpro2, ".
 			    "        b.denestpro2 as denestpro2, c.codestpro3 as codestpro3, c.denestpro3 as denestpro3, ".
                "        d.codestpro4 as codestpro4, d.denestpro4 as denestpro4, e.codestpro5 as codestpro5, ".
                "        e.denestpro5 as denestpro5, e.estcla ".
                " FROM   spg_ep1 a, spg_ep2 b, spg_ep3 c, spg_ep4 d, spg_ep5 e ".
                " WHERE  a.codemp=b.codemp AND b.codemp=c.codemp AND c.codemp=d.codemp AND d.codemp=e.codemp AND ".
                "        e.codemp='".$ls_codemp."'  AND  a.codestpro1=b.codestpro1  AND a.codestpro1=c.codestpro1   AND ".
                "        a.codestpro1=d.codestpro1  AND  a.codestpro1=e.codestpro1  AND  b.codestpro2=c.codestpro2  AND ".
                "        b.codestpro2=d.codestpro2  AND  b.codestpro2=d.codestpro2  AND  b.codestpro2=e.codestpro2  AND ".
                "        c.codestpro3=d.codestpro3  AND  c.codestpro3=e.codestpro3  AND  d.codestpro4=e.codestpro4  AND ".
			    "        e.codestpro5 like '".$ls_codigo."' AND e.denestpro5 like '".$ls_denominacion."' AND ".
				"        a.estcla=b.estcla AND  b.estcla=c.estcla AND  c.estcla=d.estcla   ";
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
				$ls_denestpro3=$row["denestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_denestpro4=$row["denestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_denominacion=$row["denestpro5"];
			    $ls_estcla=$row["estcla"];

				$ls_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
				$ls_incio1=25-$ls_loncodestpro1;
				$ls_codestpro1=substr($ls_codestpro1,$ls_incio1,$ls_loncodestpro1);
				
				$ls_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
				$ls_incio2=25-$ls_loncodestpro2;
				$ls_codestpro2=substr($ls_codestpro2,$ls_incio2,$ls_loncodestpro2);
				
				$ls_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
				$ls_incio3=25-$ls_loncodestpro3;
				$ls_codestpro3=substr($ls_codestpro3,$ls_incio3,$ls_loncodestpro3);
				
				$ls_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
				$ls_incio4=25-$ls_loncodestpro4;
				$ls_codestpro4=substr($ls_codestpro4,$ls_incio4,$ls_loncodestpro4);
				
				$ls_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
				$ls_incio5=25-$ls_loncodestpro5;
				$ls_codestpro5=substr($ls_codestpro5,$ls_incio5,$ls_loncodestpro5);
				
				/*$codestprog1=substr($codestprog1,18,2);
				$codestprog2=substr($codestprog2,4,2);
				$codestprog3=substr($codestprog3,1,2);
				$codestprog4=substr($codestprog4,0,2);
				$codigo=substr($codigo,0,2);*/
				
				if($ls_tipo=="")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro3)."</a></td>";
		
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro4)."</a></td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="apertura")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";

					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";

					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_apertura('$ls_codestpro1','$ls_denestpro1','$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4','$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="progrep")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro3)."</a></td>";
		
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro4)."</a></td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript:aceptar_progrep('$ls_codestpro1','$ls_denestpro1', 
					'$ls_codestpro2','$ls_denestpro2','$ls_codestpro3','$ls_denestpro3','$ls_codestpro4','$ls_denestpro4',
					'$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rep('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
					print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					
					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";

					print "<td width=30 align=\"center\"><a href=\"javascript: 
					aceptar_rephas('$ls_codestpro1','$ls_codestpro2','$ls_codestpro3','$ls_codestpro4','$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
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

  function aceptar(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                   codestprog5,denestprog5,estcla)
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
	opener.document.form1.estcla.value=estcla;
	close();
  }
  
  function aceptar_apertura(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                            codestprog5,denestprog5,estcla)
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
	opener.document.form1.estcla.value=estcla;
 	opener.document.form1.operacion.value="CARGAR";
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog1,denestprog1,codestprog2,denestprog2,codestprog3,denestprog3,codestprog4,denestprog4,
                           codestprog5,denestprog5,estcla)
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
  
  function aceptar_rep(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5,estcla)
  {
	opener.document.form1.codestpro1.value=codestprog1;
	opener.document.form1.codestpro2.value=codestprog2;
	opener.document.form1.codestpro3.value=codestprog3;
	opener.document.form1.codestpro4.value=codestprog4;
	opener.document.form1.codestpro5.value=codestprog5;
	opener.document.form1.codestpro5.readOnly=true;
	close();
  }
  
  function aceptar_rephas(codestprog1,codestprog2,codestprog3,codestprog4,codestprog5,estcla)
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