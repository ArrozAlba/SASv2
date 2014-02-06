<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
	$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
	$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
	$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Catalogo de Estructura Presupuestaria ".$ls_nomestpro5;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Catalogo de Estructura Programática ".$ls_nomestpro5;
			break;
	}

	//-------------------------------------------------------------------------------------------------------------------------------------------
	function uf_print($as_codestprog1, $as_codestprog2, $as_codestprog3, $as_codestprog4, $as_codestprog5, $as_denominacion, $as_tipo,$as_estcla)
   	{
		//////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_print
		//	  Arguments: as_codestprog1  // Código de la estructura Programática 1
		//	  			 as_codestprog2  // Código de la estructura Programática 2
		//	  			 as_codestprog3  // Código de la estructura Programática 3 
		//	  			 as_codestprog4  // Código de la estructura Programática 4
		//	  			 as_codestprog5  // Código de la estructura Programática 5
		//				 as_denominacion // Denominación de la estructura programática
		//				 as_tipo  // Tipo de Llamada del catálogo
		//	Description: Función que obtiene e imprime los resultados de la busqueda
		//////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro1"]."</td>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro2"]."</td>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro3"]."</td>";
		print "<td>".$_SESSION["la_empresa"]["nomestpro4"]."</td>";
		print "<td>Código </td>";
		print "<td>Denominación</td>";
		print "</tr>";
		$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
		$ls_sql="SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,denestpro5,estcla ".
				"  FROM spg_ep5 ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codestpro1 ='".str_pad($as_codestprog1,25,"0",0)."' ".
				"   AND codestpro2 ='".str_pad($as_codestprog2,25,"0",0)."' ".
				"   AND codestpro3 ='".str_pad($as_codestprog3,25,"0",0)."' ".
				"   AND codestpro4 ='".str_pad($as_codestprog4,25,"0",0)."' ".
				"   AND estcla     ='".$as_estcla."'".
				"   AND codestpro5 like '".$as_codestprog5."' ".
				"   AND denestpro5 like '".$as_denominacion."' ".
				" ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5 ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codestpro1=$row["codestpro1"];
				$ls_codestpro2=$row["codestpro2"];
				$ls_codestpro3=$row["codestpro3"];
				$ls_codestpro4=$row["codestpro4"];
				$ls_codestpro5=$row["codestpro5"];
				$io_fun_nomina->uf_formato_programatica_detallado($li_len1,&$ls_codestpro1);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len2,&$ls_codestpro2);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len3,&$ls_codestpro3);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len4,&$ls_codestpro4);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len5,&$ls_codestpro5);
				$denominacion=$row["denestpro5"];
				$ls_esctla=$row["estcla"];
				switch($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$denominacion','$ls_esctla');\">".$ls_codestpro1."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro2."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro3."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro4."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro5."</a></td>";
						print "<td width=130 align=\"left\">".$denominacion."</td>";
						print "</tr>";			
						break;

					case "asignacioncargo":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptarasignacion('$ls_codestpro5','$denominacion','$ls_esctla');\">".$ls_codestpro1."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro2."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro3."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro4."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro5."</a></td>";
						print "<td width=130 align=\"left\">".$denominacion."</td>";
						print "</tr>";			
						break;
						
					case "REPDES":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptardes('$ls_codestpro5','$denominacion','$ls_esctla');\">".$ls_codestpro1."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro2."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro3."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro4."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro5."</a></td>";
						print "<td width=130 align=\"left\">".$denominacion."</td>";
						print "</tr>";			
						break;
						
					case "REPHAS":
						print "<tr class=celdas-blancas>";
						print "<td width=30 align=\"center\"><a href=\"javascript: aceptarhas('$ls_codestpro5','$denominacion','$ls_esctla');\">".$ls_codestpro1."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro2."</td>";
						print "<td width=30 align=\"center\">".$ls_codestpro3."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro4."</a></td>";
						print "<td width=30 align=\"center\">".$ls_codestpro5."</a></td>";
						print "<td width=130 align=\"left\">".$denominacion."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	if(array_key_exists("codestpro1",$_GET))
	{
		$ls_codestprog1=$_GET["codestpro1"];
		$ls_denestprog1=$_GET["denestpro1"];
	}
	if(array_key_exists("txtcodestpro1",$_POST))
	{
		$ls_codestprog1=$_POST["txtcodestpro1"];
		$ls_denestprog1=$_POST["txtdenestpro1"];
	}
	if(array_key_exists("codestpro2",$_GET))
	{
		$ls_codestprog2=$_GET["codestpro2"];
		$ls_denestprog2=$_GET["denestpro2"];
	}
	if(array_key_exists("txtcodestpro2",$_POST))
	{
		$ls_codestprog2=$_POST["txtcodestpro2"];
		$ls_denestprog2=$_POST["txtdenestpro2"];
	}
	if(array_key_exists("codestpro3",$_GET))
	{
		$ls_codestprog3=$_GET["codestpro3"];
		$ls_denestprog3=$_GET["denestpro3"];
	}
	if(array_key_exists("txtcodestpro3",$_POST))
	{
		$ls_codestprog3=$_POST["txtcodestpro3"];
		$ls_denestprog3=$_POST["txtdenestpro3"];
	}
	if(array_key_exists("codestpro4",$_GET))
	{
		$ls_codestprog4=$_GET["codestpro4"];
		$ls_denestprog4=$_GET["denestpro4"];
		$ls_estcla4    =$_GET["estcla4"];
	}
	if(array_key_exists("txtcodestpro4",$_POST))
	{
		$ls_codestprog4=$_POST["txtcodestpro4"];
		$ls_denestprog4=$_POST["txtdenestpro4"];
		$ls_estcla4    =$_POST["txtestcla4"];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_titulo;?></title>
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
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td height="20" colspan="2" class="titulo-ventana"><?php print $ls_titulo;?></td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118" height="22"><div align="right"><?php print $ls_nomestpro1; ?></div></td>
        <td width="380"><div align="left"><?php print $ls_denestprog1; ?>
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestprog1; ?>" size="22" maxlength="20" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="hidden" class="sin-borde" id="txtdenestpro1" size="50" value="<?php print $ls_denestprog1; ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro2; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog2; ?>
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print  $ls_codestprog2; ?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="txtdenestpro2" type="hidden" id="txtdenestpro2" value="<?php print $ls_denestprog2; ?>" size="50" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro3; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog3; ?>
          <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print  $ls_codestprog3; ?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="txtdenestpro3" type="hidden" id="txtdenestpro3" value="<?php print $ls_denestprog3; ?>" size="50" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro4; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog4; ?>
          <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print  $ls_codestprog4; ?>" size="22" maxlength="6" readonly style="text-align:center">
          <input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="<?php print $ls_denestprog4; ?>" size="50" class="sin-borde" readonly>
          <input name="txtestcla4" type="hidden"  id="txtestcla4" size="2" value="<?php print $ls_estcla4; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td><input name="txtcodestpro5" type="text" id="txtcodestpro5"  size="22" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion"  size="72" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo="%".$_POST["txtcodestpro5"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		$ls_estcla4=$_POST["txtestcla4"];
		uf_print($ls_codestprog1, $ls_codestprog2, $ls_codestprog3,  $ls_codestprog4, $ls_codigo, $ls_denominacion, $ls_tipo,$ls_estcla4);
	}
	else
	{
		$ls_codigo="%%";
		$ls_denominacion="%%";
		uf_print($ls_codestprog1, $ls_codestprog2, $ls_codestprog3, $ls_codestprog4,  $ls_codigo, $ls_denominacion, $ls_tipo,$ls_estcla4);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codestprog5,deno,estcla)
{
	opener.document.form1.txtdenestpro5.value=deno;
	opener.document.form1.txtcodestpro5.value=codestprog5;
	opener.document.form1.txtestcla5.value=estcla;
	close();
}

function aceptardes(codestprog5,deno,estcla)
{
	opener.document.form1.txtdenestpro5.value=deno;
	opener.document.form1.txtcodestpro5.value=codestprog5;
	opener.document.form1.txtestcla5.value=estcla;
	close();
}

function aceptarhas(codestprog5,deno,estcla)
{
	opener.document.form1.txtdenestpro10.value=deno;
	opener.document.form1.txtcodestpro10.value=codestprog5;
	opener.document.form1.txtestcla10.value=estcla;
	close();
}

function aceptarasignacion(codestprog5,deno,estcla)
{
	opener.document.form1.txtdenestpro5.value=deno;
	opener.document.form1.txtcodestpro5.value=codestprog5;
	opener.document.form1.txtestcla5.value=estcla;
	close();
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_snorh_cat_estpre5.php?tipo=<?php print $ls_tipo;?>";
	f.submit();
}
</script>
</html>