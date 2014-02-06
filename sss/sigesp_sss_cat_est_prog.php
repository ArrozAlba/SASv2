<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_estmodest)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Estructura Presupuestaria ";
			$li_len1=20;
			$li_len2=6;
			$li_len3=3;
			$li_len4=2;
			$li_len5=2;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Estructura Programática ";
			$li_len1=2;
			$li_len2=2;
			$li_len3=2;
			$li_len4=2;
			$li_len5=2;
			break;
	}

   //--------------------------------------------------------------
   function uf_print($as_codigo, $as_denominacion, $as_tipo, $as_estmodest)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codpro     // Código de Profesión
		//				   as_despro     // Descripción de la profesión
		//				   as_tipo       // Verifica de donde se está llamando el catálogo
		//				   as_estmodest  // estatus de modalidad de estructura
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 			
		// Modificado Por: Ing. Luis Anibal Lang					
		//   Fecha Modif.: 24/10/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_titulo, $li_len1, $li_len2, $li_len3, $li_len4, $li_len5;
		
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
		$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];
		$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];
		$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];
		if($as_estmodest==1)
		{
			$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];
			$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];
		}		
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td>".$ls_nomestpro1."</td>";
		print "<td>".$ls_nomestpro2."</td>";
		print "<td>".$ls_nomestpro3."</td>";
		if($as_estmodest==1)
		{
			print "<td>".$ls_nomestpro4."</td>";
			print "<td>".$ls_nomestpro5."</td>";
		}
		print "</tr>";
	$ls_sql="SELECT spg_ep1.codestpro1 as codestpro1,spg_ep2.codestpro2 as codestpro2,spg_ep3.codestpro3 as codestpro3,".
			"       spg_ep4.codestpro4 as codestpro4,spg_ep5.codestpro5 as codestpro5".
			"  FROM spg_ep1 ,spg_ep2 ,spg_ep3,spg_ep4,spg_ep5".
			" WHERE spg_ep1.codemp='".$ls_codemp."'".
			"   AND spg_ep1.codemp=spg_ep2.codemp".
			"   AND spg_ep1.codemp=spg_ep3.codemp".
			"   AND spg_ep1.codemp=spg_ep4.codemp".
			"   AND spg_ep1.codemp=spg_ep5.codemp".
			"   AND spg_ep1.codestpro1=spg_ep2.codestpro1".
			"   AND spg_ep1.codestpro1=spg_ep3.codestpro1".
			"   AND spg_ep1.codestpro1=spg_ep4.codestpro1".
			"   AND spg_ep1.codestpro1=spg_ep5.codestpro1".
			"   AND spg_ep2.codestpro2=spg_ep3.codestpro2". 
			"   AND spg_ep2.codestpro2=spg_ep4.codestpro2". 
			"   AND spg_ep2.codestpro2=spg_ep5.codestpro2". 
			"   AND spg_ep3.codestpro3=spg_ep4.codestpro3". 
			"   AND spg_ep3.codestpro3=spg_ep5.codestpro3". 
			"   AND spg_ep4.codestpro4=spg_ep5.codestpro4";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codest1=$row["codestpro1"];
				$ls_codest2=$row["codestpro2"];
				$ls_codest3=$row["codestpro3"];
				$ls_codest4=$row["codestpro4"];
				$ls_codest5=$row["codestpro5"];
				$ls_estpro=$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5;
				switch($as_tipo)
				{
					case "": // Se hace el llamado desde sigesp_snorh_d_uni_adm.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptar('$ls_estpro');\">".$ls_codest1."</a></td>";
						print "<td align=center><a href=\"javascript: aceptar('$ls_estpro');\">".$ls_codest2."</a></td>";
						print "<td align=center><a href=\"javascript: aceptar('$ls_estpro');\">".$ls_codest3."</a></td>";
						if($as_estmodest==1)
						{
							print "<td align=center><a href=\"javascript: aceptar('$ls_estpro');\">".$ls_codest4."</a></td>";
							print "<td align=center><a href=\"javascript: aceptar('$ls_estpro');\">".$ls_codest5."</a></td>";
						}
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
		unset($io_unidadadmin);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Administrativas</title>
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
    <input name="operacion" type="hidden" id="operacion">
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Unidades Administrativas  </td>
    	</tr>
  </table>
	 <br>
    <?php
	require_once("class_funciones_seguridad.php");
	$io_fun_seguridad=new class_funciones_seguridad();
	$ls_operacion =$io_fun_seguridad->uf_obteneroperacion();
	$ls_tipo=$io_fun_seguridad->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo=$_POST["codigo"];
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo, $ls_estmodest);
	}
	else
	{
		$ls_codigo="";
		$ls_denominacion="%%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo, $ls_estmodest);
	}	
	unset($io_fun_seguridad);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_estpro)
  {
    opener.document.form1.txtcodintper.value=ls_estpro;
	close();
  }

</script>
</html>
