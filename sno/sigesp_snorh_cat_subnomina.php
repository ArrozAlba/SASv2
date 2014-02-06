<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}

   //--------------------------------------------------------------
   function uf_print($as_subcodigo, $as_denominacion, $as_codigo, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_subcodigo  // Código de la subnómina
		//				   as_denominacion  // Denominación de la subnómina
		//				   as_codigo  // Código de la nómina
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		print "<td>Código Subnomina </td>";
		print "<td>Denominación</td>";
		print "</tr>";
		$ls_sql= "SELECT codsubnom,dessubnom ".
				 "  FROM sno_subnomina ".
				 " WHERE codemp='".$ls_codemp."' ".
				 "	 AND codsubnom like '".$as_subcodigo."' ".
				 "   AND dessubnom like '".$as_denominacion."' ".
				 "   AND codnom='".$as_codigo."' ".
				 "   AND codsubnom<>'0000000000'".
				 " ORDER BY codsubnom ";
		if($as_tipo=="movimiento")
		{
			$ls_sql= "SELECT codsubnom, dessubnom ".
					 "  FROM sno_subnomina ".
					 " WHERE codemp='".$ls_codemp."' ".
					 "	 AND codsubnom like '".$as_subcodigo."' ".
					 "   AND dessubnom like '".$as_denominacion."' ".
					 "   AND codsubnom<>'0000000000'".
					 " GROUP BY codsubnom, dessubnom ".
					 " ORDER BY codsubnom, dessubnom ";
		}
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$codigo=$row["codsubnom"];
				$denominacion=$row["dessubnom"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td width=150><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "</tr>";
						break;			

					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td width=350><a href=\"javascript: aceptarasignacion('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "</tr>";						
						break;			

					case "movimiento":
						print "<tr class=celdas-blancas>";
						print "<td width=150><a href=\"javascript: aceptarmovimiento('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "</tr>";						
						break;			

					case "reportedesde":
						print "<tr class=celdas-blancas>";
						print "<td width=150><a href=\"javascript: aceptarreportedesde('$codigo');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "</tr>";						
						break;			

					case "reportehasta":
						print "<tr class=celdas-blancas>";
						print "<td width=150><a href=\"javascript: aceptarrreportehasta('$codigo');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
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
   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Subn&oacute;minas</title>
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
<style type="text/css">
<!--
.Estilo1 {font-size: 11px}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo ?>">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Subn&oacute;minas</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="100" height="22"><div align="right">Codigo</div></td>
        <td width="400"><div align="left">
          <input name="codigo" type="text" id="codigo" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" onKeyPress="javascript: ue_mostrar(this,event);">
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
	$ls_codigo=$io_fun_nomina->uf_obtenervalor_get("codnom",'');
	if($ls_codigo=='')
	{
		$ls_codigo=$_SESSION["la_nomina"]["codnom"];
	}
	if($ls_operacion=="BUSCAR")
	{
		$ls_subcodigo="%".$_POST["codigo"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_subcodigo, $ls_denominacion, $ls_codigo, $ls_tipo);
	}
	else
	{
		$ls_subcodigo="%%";
		$ls_denominacion="%%";
		uf_print($ls_subcodigo, $ls_denominacion, $ls_codigo, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,deno)
{
	opener.document.form1.txtcodsubnom.value=codigo;
	opener.document.form1.txtcodsubnom.readOnly=true;
	opener.document.form1.txtdessubnom.value=deno;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarasignacion(codsubnom,dessubnom)
{
	opener.document.form1.txtcodsubnom.value=codsubnom;
	opener.document.form1.txtcodsubnom.readOnly=true;
	opener.document.form1.txtdessubnom.value=dessubnom;
	close();
}

function aceptarmovimiento(codsubnom,dessubnom)
{
	opener.document.form1.txtcodsubnom.value=codsubnom;
	opener.document.form1.txtcodsubnom.readOnly=true;
	opener.document.form1.txtdessubnom.value=dessubnom;
	close();
}

function aceptarreportedesde(codsubnom)
{
	opener.document.form1.txtcodsubnomdes.value=codsubnom;
	opener.document.form1.txtcodsubnomdes.readOnly=true;
	opener.document.form1.txtcodsubnomdes.value=codsubnom;
	opener.document.form1.txtcodsubnomhas.value='';
	close();
}

function aceptarrreportehasta(codsubnom)
{
	if(opener.document.form1.txtcodsubnomdes.value<=codsubnom)
	{
		opener.document.form1.txtcodsubnomhas.value=codsubnom;
		opener.document.form1.txtcodsubnomhas.readOnly=true;
		opener.document.form1.txtcodsubnomhas.value=codsubnom;
		close();
	}
	else
	{
		alert("Rango de la subnómina inválido");
	}
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
	f.action="sigesp_snorh_cat_subnomina.php?tipo=<?php print $ls_tipo;?>&codnom=<?php print $ls_codigo;?>";
	f.submit();
}
</script>
</html>