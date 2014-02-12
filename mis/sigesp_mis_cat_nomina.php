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
   function uf_print($as_codigo, $as_denominacion, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codigo  // Código de la nómina
		//				   as_denominacion  // Denominación de la nómina
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
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
		print "<td width=100>Código</td>";
		print "<td width=400>Denominación</td>";
		print "</tr>";
		switch($as_tipo)
		{
			case "contabilizar":
				$ls_status=0;
				break;
			case "reversar":
				$ls_status=1;
				break;
		}
		$ls_sql= "SELECT sno_nomina.codemp, sno_nomina.codnom, sno_nomina.desnom ".
				 "  FROM sno_nomina, sno_dt_spg ".
				 " WHERE sno_nomina.codemp='".$ls_codemp."' ".
				 "	 AND sno_dt_spg.estatus = ".$ls_status." ".
				 "   AND sno_nomina.codnom like '".$as_codigo."' ".
				 "   AND sno_nomina.desnom like '".$as_denominacion."' ".
				 "   AND sno_nomina.codemp = sno_dt_spg.codemp ".
				 "   AND sno_nomina.codnom = sno_dt_spg.codnom ".
				 " GROUP BY sno_nomina.codemp, sno_nomina.codnom, sno_nomina.desnom ".
				 " ORDER BY codnom ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row["codnom"];
				$ls_denominacion=$row["desnom"];
				switch($as_tipo)
				{
					case "contabilizar":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codnom','$ls_denominacion');\">".$ls_codnom."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;					

					case "reversar":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codnom','$ls_denominacion');\">".$ls_codnom."</a></td>";
						print "<td>".$ls_denominacion."</td>";
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
		unset($io_sno);
		unset($io_nomina);
		unset($ls_codemp);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Nomina</title>
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
a:hover {
	color: #006699;
}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Nomina</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111" height="22"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
          <input name="codigo" type="text" id="codigo" onKeyPress="javascript: ue_mostrar(this,event);">        
          <input name="txtcodigo" type="hidden" id="txtcodigo">
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
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php
	require_once("class_folder/class_funciones_mis.php");
	$oi_fun_integrador=new class_funciones_mis();
	$ls_operacion =$oi_fun_integrador->uf_obteneroperacion();
	$ls_tipo=$oi_fun_integrador->uf_obtenertipo();
	$ls_codded=$oi_fun_integrador->uf_obtenervalor_get("codded","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo="%".$_POST["codigo"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo);
	}
	else
	{
		$ls_codigo="%%";
		$ls_denominacion="%%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo);
	}
	unset($oi_fun_integrador);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codnom,denominacion)
  {
     opener.document.form1.txtcodnom.value=codnom;
	 opener.document.form1.txtcodnom.readOnly=true;
     opener.document.form1.txtdesnom.value=denominacion;
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
	f.action="sigesp_mis_cat_nomina.php?tipo=<?PHP print $ls_tipo;?>";
	f.submit();
}
</script>
</html>
