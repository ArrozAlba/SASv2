<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
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
   function uf_print($as_codigo, $as_denominacion, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codpro  // Código de Profesión
		//				   as_despro  // Descripción de la profesión
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td>Código </td>";
		print "<td>Denominación</td>";
		print "<td>".$ls_titulo."</td>";
		print "</tr>";
		if($as_codigo!="")
		{
			$as_codigo=str_pad($as_codigo,12,"0",0);
		}
		$ls_coduniad1="%".substr($as_codigo,0,4)."%";
		$ls_coduniad2="%".substr($as_codigo,4,2)."%";
		$ls_coduniad3="%".substr($as_codigo,6,2)."%";
		$ls_coduniad4="%".substr($as_codigo,8,2)."%";
		$ls_coduniad5="%".substr($as_codigo,10,2)."%";
		$ls_sql="SELECT codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,codprouniadm, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep1.codestpro1=substr(sno_unidadadmin.codprouniadm,1,20)) as denestpro1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep2.codestpro1=substr(sno_unidadadmin.codprouniadm,1,20) ".
				"		    AND spg_ep2.codestpro2=substr(sno_unidadadmin.codprouniadm,21,6)) as denestpro2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep3.codestpro1=substr(sno_unidadadmin.codprouniadm,1,20) ".
				"		    AND spg_ep3.codestpro2=substr(sno_unidadadmin.codprouniadm,21,6) ".
				"		    AND spg_ep3.codestpro3=substr(sno_unidadadmin.codprouniadm,27,3)) as denestpro3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep4.codestpro1=substr(sno_unidadadmin.codprouniadm,1,20) ".
				"		    AND spg_ep4.codestpro2=substr(sno_unidadadmin.codprouniadm,21,6) ".
				"		    AND spg_ep4.codestpro3=substr(sno_unidadadmin.codprouniadm,27,3) ".
				"		    AND spg_ep4.codestpro4=substr(sno_unidadadmin.codprouniadm,30,2)) as denestpro4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep5.codestpro1=substr(sno_unidadadmin.codprouniadm,1,20) ".
				"		    AND spg_ep5.codestpro2=substr(sno_unidadadmin.codprouniadm,21,6) ".
				"		    AND spg_ep5.codestpro3=substr(sno_unidadadmin.codprouniadm,27,3) ".
				"		    AND spg_ep5.codestpro4=substr(sno_unidadadmin.codprouniadm,30,2) ".
				"		    AND spg_ep5.codestpro5=substr(sno_unidadadmin.codprouniadm,32,2)) as denestpro5 ".
				"  FROM sno_unidadadmin ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND minorguniadm like '".$ls_coduniad1."' ".
				"   AND ofiuniadm like '".$ls_coduniad2."' ".
				"   AND uniuniadm like '".$ls_coduniad3."' ".
				"   AND depuniadm like '".$ls_coduniad4."' ".
				"   AND prouniadm like '".$ls_coduniad5."' ".
				"   AND desuniadm like '".$as_denominacion."' ".
	   			" ORDER BY minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$codigo=$row["minorguniadm"].$row["ofiuniadm"].$row["uniuniadm"].$row["depuniadm"].$row["prouniadm"];
				$ls_minorguniadm=$row["minorguniadm"];
				$ls_ofiuniadm=$row["ofiuniadm"];
				$ls_uniuniadm=$row["uniuniadm"];
				$ls_depuniadm=$row["depuniadm"];
				$ls_prouniadm=$row["prouniadm"];
				$denominacion=$row["desuniadm"];
				$codprouniadm=$row["codprouniadm"];
				$ls_codest1=substr($codprouniadm,0,20);
				$ls_codest1=substr($ls_codest1,(strlen($ls_codest1)-$li_len1),$li_len1);
				$ls_codest2=substr($codprouniadm,20,6);
				$ls_codest2=substr($ls_codest2,(strlen($ls_codest2)-$li_len2),$li_len2);
				$ls_codest3=substr($codprouniadm,26,3);
				$ls_codest3=substr($ls_codest3,(strlen($ls_codest3)-$li_len3),$li_len3);
				$ls_codest4=substr($codprouniadm,29,2);
				$ls_codest4=substr($ls_codest4,(strlen($ls_codest4)-$li_len4),$li_len4);
				$ls_codest5=substr($codprouniadm,31,2);
				$ls_codest5=substr($ls_codest5,(strlen($ls_codest5)-$li_len5),$li_len5);
				$ls_denestpro1=$row["denestpro1"];
				$ls_denestpro2=$row["denestpro2"];
				$ls_denestpro3=$row["denestpro3"];
				$ls_denestpro4=$row["denestpro4"];
				$ls_denestpro5=$row["denestpro5"];
				switch($as_tipo)
				{
					case "": // Se hace el llamado desde sigesp_snorh_d_uni_adm.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$ls_codest1',";
						print "'$ls_codest2','$ls_codest3','$ls_codest4','$ls_codest5','$ls_denestpro1','$ls_denestpro2',";
						print "'$ls_denestpro3','$ls_denestpro4','$ls_denestpro5');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
						print "</tr>";			
						break;			

					case "reporte": // Se hace el llamado desde sigesp_snorh_d_ct_unid.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptar_reporte('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
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
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111" height="22"><div align="right">Codigo</div></td>
        <td width="451"><div align="left">
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
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<br>
    <?php
	require_once("class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	$ls_operacion =$io_fun_viaticos->uf_obteneroperacion();
	$ls_tipo=$io_fun_viaticos->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_codigo=$_POST["codigo"];
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo);
	}
	else
	{
		$ls_codigo="";
		$ls_denominacion="%%";
		uf_print($ls_codigo, $ls_denominacion, $ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function aceptar(codigo,deno,codest1,codest2,codest3,codest4,codest5,denestpro1,denestpro2,denestpro3,denestpro4,denestpro5)
	{
		opener.document.form1.txtcodigo.value=codigo;
		opener.document.form1.txtcodigo.readOnly=true;
		opener.document.form1.txtdenominacion.value=deno;
		opener.document.form1.txtcodestpro1.value=codest1;
		opener.document.form1.txtcodestpro2.value=codest2;
		opener.document.form1.txtcodestpro3.value=codest3;
		opener.document.form1.txtcodestpro4.value=codest4;
		opener.document.form1.txtcodestpro5.value=codest5;
		opener.document.form1.txtdenestpro1.value=denestpro1;
		opener.document.form1.txtdenestpro2.value=denestpro2;
		opener.document.form1.txtdenestpro3.value=denestpro3;
		opener.document.form1.txtdenestpro4.value=denestpro4;
		opener.document.form1.txtdenestpro5.value=denestpro5;
		opener.document.form1.existe.value="TRUE";
		close();
	}

  function aceptar_reporte(codigo,denominacion)
  {
    opener.document.form1.txtcoduniadm.value=codigo;
    opener.document.form1.txtdenuniadm.value=denominacion;
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
	  f.action="sigesp_snorh_cat_uni_ad.php?tipo=<?PHP print $ls_tipo;?>";
	  f.submit();
  }
</script>
</html>
