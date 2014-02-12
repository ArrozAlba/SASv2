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
		$ls_codestprog1=$_POST["codestpro1"];
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
		
		$ls_codestprog1=$_GET["codestpro1"];
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
            <input name="codestpro1" type="text" id="codestpro1" value="<?php print $ls_codestprog1 ?>" size="<?php print $ls_loncodestpro1; ?>" maxlength="<?php print $ls_loncodestpro1; ?>" readonly style="text-align:center">
            <input name="denestpro1" type="hidden" id="denestpro1" value="<?php print $ls_denestprog1 ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td><div align="left">
          <input name="codestpro2" type="text" id="codestpro2" value="<?php print  $ls_codestprog2?>" size="<?php print $ls_loncodestpro2; ?>" maxlength="<?php print $ls_loncodestpro2; ?>" readonly style="text-align:center">
          <input name="denestpro2" type="hidden" id="denestpro2" value="<?php print $ls_denestprog2?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $la_empresa["nomestpro3"]?></div></td>
        <td><div align="left">
          <input name="codestpro3" type="text" id="codestpro3" value="<?php print  $ls_codestprog3 ?>" size="<?php print $ls_loncodestpro3; ?>" maxlength="<?php print $ls_loncodestpro3; ?>" readonly style="text-align:center">
          <input name="denestpro3" type="hidden" id="denestpro3" value="<?php print $ls_denestprog3 ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><?php print $la_empresa["nomestpro4"]?></div></td>
        <td><div align="left">
          <input name="codestpro4" type="text" id="codestpro4" value="<?php print  $ls_codestprog4 ?>" size="<?php print $ls_loncodestpro4; ?>" maxlength="<?php print $ls_loncodestpro4; ?>" readonly style="text-align:center">
          <input name="denestpro4" type="hidden" id="denestpro4" value="<?php print $ls_denestprog4 ?>">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="codestpro5" type="text" id="codestpro5"  size="<?php print $ls_loncodestpro5; ?>" maxlength="<?php print $ls_loncodestpro5; ?>" style="text-align:center">
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
	print "<td>Denominación</td>";
    print "<td>Tipo</td>";
	print "</tr>";
	if($ls_operacion=="BUSCAR")
	{
	    $ls_codestpro1=$io_function->uf_cerosizquierda($ls_codestprog1,25);
	    $ls_codestpro2=$io_function->uf_cerosizquierda($ls_codestprog2,25);
	    $ls_codestpro3=$io_function->uf_cerosizquierda($ls_codestprog3,25);
	    $ls_codestpro4=$io_function->uf_cerosizquierda($ls_codestprog4,25);
		
		if ($ls_unidad!="")
		{
			$ls_unidad=str_pad($ls_unidad,10,"0",0);
			$ls_tabla=", spg_dt_unidadadministrativa d ";
			$ls_criterio=" AND d.codemp=codemp            ".
						 "  AND d.codestpro1= codestpro1  ".
						 "  AND d.codestpro2= codestpro2  ".
						 "  AND d.codestpro3= codestpro3  ".
						 "  AND d.codestpro4= codestpro4  ".
						 "  AND d.codestpro5= codestpro5  ".
						 "  AND d.estcla= estcla          ". 
						 "  AND d.coduniadm='".$ls_unidad."'      ";		
		}
		else
		{
			$ls_tabla=" ";
			$ls_criterio=" ";	
		
		}
	
		$ls_logusr = $_SESSION["la_logusr"];
		$ls_gestor = $_SESSION["ls_gestor"];
		$ls_sql_seguridad = "";
		if (strtoupper($ls_gestor) == "MYSQLT")
		{
		 $ls_sql_seguridad = " AND CONCAT('".$ls_codemp."','SPG','".$ls_logusr."',codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla) IN (SELECT distinct CONCAT(codemp,codsis,codusu,codintper) 
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		else
		{
		 $ls_sql_seguridad = " AND '".$ls_codemp."'||'SPG'||'".$ls_logusr."'||codestpro1||codestpro2||codestpro3||codestpro4||codestpro5||estcla IN (SELECT distinct codemp||codsis||codusu||codintper
		                       FROM sss_permisos_internos WHERE codusu = '".$ls_logusr."' AND codsis = 'SPG')";
		}
		
		$ls_sql=" SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,denestpro5,estcla ".
                " FROM   spg_ep5 ".$ls_tabla.
                " WHERE  codemp='".$ls_codemp."' AND codestpro1 ='".$ls_codestpro1."' AND ".
				"        codestpro2 ='".$ls_codestpro2."' AND  codestpro3 ='".$ls_codestpro3."'  AND  ".
                "        codestpro4 ='".$ls_codestpro4."' AND  codestpro5 like '".$ls_codigo."'  AND  ".               
			    "        UPPER(denestpro5) like '".strtoupper($ls_denominacion)."' AND  estcla='".$ls_estcla."' ".$ls_criterio.$ls_sql_seguridad." ".
				" ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5"; 
		$rs_data=$io_sql->select($ls_sql);
	    $li_numrows=$io_sql->num_rows($rs_data);
	    if($li_numrows>0)
	    {
	  	     while($row=$io_sql->fetch_row($rs_data))
		     {
				print "<tr class=celdas-blancas>";
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$ls_denominacion=$row["denestpro5"];
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
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="apertura")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_apertura('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="progrep")
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_progrep('$ls_codestpro5','$ls_denominacion','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('$ls_codestpro5','$ls_estcla');\">".
					trim($ls_codestpro5)."</a></td>";
					print "<td width=130 align=\"left\">".trim($ls_denominacion)."</td>";
				    print "<td width=30 align=\"left\">".$ls_estcla."</td>";
					print "</tr>";	
				}
			}
			if(($ls_tipo!="")&&($li_estmodest==2))
			{				
				print "<tr class=celdas-blancas>";
				if($ls_tipo=="reporte")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rep('**');\">**</a></td>";
					print "<td width=130 align=\"left\">Todas</td>";
					print "</tr>";	
				}
				if($ls_tipo=="rephas")		
				{
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">".
					trim($ls_codestpro1)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">".
					trim($ls_codestpro2)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">".
					trim($ls_codestpro3)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">".
					trim($ls_codestpro4)."</td>";
					print "<td width=30 align=\"center\"><a href=\"javascript: aceptar_rephas('**');\">**</a></td>";
					print "<td width=130 align=\"left\">Todas</td>";
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
  function aceptar(codestprog,deno,estcla)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
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
  
  function aceptar_apertura(codestprog,deno,estcla)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
 	opener.document.form1.operacion.value="CARGAR";
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estcla.value=estcla;
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_progrep(codestprog,deno,estcla)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
 	opener.document.form1.operacion.value="CARGAR";
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estcla.value=estcla;
	opener.document.form1.submit();
	close();
  }
  
  function aceptar_apertura(codestprog,deno,estcla)
  {
    opener.document.form1.denestpro5.value=deno;
	opener.document.form1.codestpro5.value=codestprog;
 	opener.document.form1.operacion.value="CARGAR";
	if(estcla=="PROYECTO")
	{
	  estcla="P";
	}
	else if(estcla=="ACCION")
	{
	  estcla="A";
	}
	opener.document.form1.estcla.value=estcla;
    opener.document.form1.submit();
	close();
  }
  
  function aceptar_rep(codestprog,estcla)
  {
	opener.document.form1.codestpro5.value=codestprog;
	opener.document.form1.codestpro5.readOnly=true;
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
  
  function aceptar_rephas(codestprog,estcla)
  {
	opener.document.form1.codestpro5h.value=codestprog;
	opener.document.form1.codestpro5h.readOnly=true;
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
	  f.action="sigesp_cat_public_estpro5.php?tipo=<?php print $ls_tipo; ?>";
	  f.submit();
  }
</script>
</html>