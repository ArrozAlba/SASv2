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
		global $io_fun_nomina;
		
		$io_fun_nomina->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
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
		print "<td>Tipo</td>";
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
		$ls_sql="SELECT codemp,minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm,desuniadm,codprouniadm,estcla, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep1.codestpro1=substr(sno_unidadadmin.codprouniadm,1,25)".
				"           AND spg_ep1.estcla=sno_unidadadmin.estcla) as denestpro1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep2.codestpro1=substr(sno_unidadadmin.codprouniadm,1,25) ".
				"		    AND spg_ep2.codestpro2=substr(sno_unidadadmin.codprouniadm,26,25) ".
				"           AND spg_ep2.estcla=sno_unidadadmin.estcla) as denestpro2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep3.codestpro1=substr(sno_unidadadmin.codprouniadm,1,25) ".
				"		    AND spg_ep3.codestpro2=substr(sno_unidadadmin.codprouniadm,26,25) ".
				"		    AND spg_ep3.codestpro3=substr(sno_unidadadmin.codprouniadm,51,25)".
				"           AND spg_ep3.estcla=sno_unidadadmin.estcla) as denestpro3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep4.codestpro1=substr(sno_unidadadmin.codprouniadm,1,25) ".
				"		    AND spg_ep4.codestpro2=substr(sno_unidadadmin.codprouniadm,26,25) ".
				"		    AND spg_ep4.codestpro3=substr(sno_unidadadmin.codprouniadm,51,25) ".
				"		    AND spg_ep4.codestpro4=substr(sno_unidadadmin.codprouniadm,76,25)".
				"           AND spg_ep4.estcla=sno_unidadadmin.estcla) as denestpro4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE codemp=sno_unidadadmin.codemp".
				"		    AND spg_ep5.codestpro1=substr(sno_unidadadmin.codprouniadm,1,25) ".
				"		    AND spg_ep5.codestpro2=substr(sno_unidadadmin.codprouniadm,26,25) ".
				"		    AND spg_ep5.codestpro3=substr(sno_unidadadmin.codprouniadm,51,25) ".
				"		    AND spg_ep5.codestpro4=substr(sno_unidadadmin.codprouniadm,76,25) ".
				"		    AND spg_ep5.codestpro5=substr(sno_unidadadmin.codprouniadm,101,25)".
				"           AND spg_ep5.estcla=sno_unidadadmin.estcla) as denestpro5 ".
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
				$ls_estcla=$row["estcla"];
				$codprouniadm=$row["codprouniadm"];
				$ls_codest1=substr($codprouniadm,0,25);
				$ls_codest2=substr($codprouniadm,25,25);
				$ls_codest3=substr($codprouniadm,50,25);
				$ls_codest4=substr($codprouniadm,75,25);
				$ls_codest5=substr($codprouniadm,100,25);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len1,&$ls_codest1);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len2,&$ls_codest2);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len3,&$ls_codest3);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len4,&$ls_codest4);
				$io_fun_nomina->uf_formato_programatica_detallado($li_len5,&$ls_codest5);
				$io_fun_nomina->uf_formatoprogramatica($row["codprouniadm"],&$ls_codprouniadm);
				$ls_denestpro1=$row["denestpro1"];
				$ls_denestpro2=$row["denestpro2"];
				$ls_denestpro3=$row["denestpro3"];
				$ls_denestpro4=$row["denestpro4"];
				$ls_denestpro5=$row["denestpro5"];
				switch($ls_estcla)
				{
					case "P":
					$ls_estclatipo="PROYECTO";
				    break;
					
					case "A":
					$ls_estclatipo="ACCION";
				    break;
				}
				switch($as_tipo)
				{
					case "": // Se hace el llamado desde sigesp_snorh_d_uni_adm.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$ls_codest1',";
						print "'$ls_codest2','$ls_codest3','$ls_codest4','$ls_codest5','$ls_denestpro1','$ls_denestpro2',";
						print "'$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "cestaticket": // Se hace el llamado desde sigesp_snorh_d_ct_unid.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarcestaticket('$codigo','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";
						break;			

					case "asignacion": // Se hace el llamado desde sigesp_sno_d_personalnomina.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarasignacion('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "replisconc": // Se hace el llamado desde sigesp_sno_r_listadoconcepto.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreplisconc('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "asignacioncargo": // Se hace el llamado desde sigesp_sno_d_asignacioncargo.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarasignacioncargo('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm','$denominacion','$ls_codest1','$ls_codest2','$ls_codest3',";
						print "'$ls_codest4','$ls_codest5','$ls_denestpro1','$ls_denestpro2','$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "represconcuni": // Se hace el llamado desde sigesp_sno_r_resumenconceptounidad.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarrepresconcuni('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "reprecpag": // Se hace el llamado desde sigesp_sno_r_recibopago.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreprecpag('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "replisfir": // Se hace el llamado desde sigesp_sno_r_recibopago.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreplisfir('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm','$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "credenciales": // Se hace el llamado desde sigesp_snorh_r_credencialespersonal.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarcredenciales('$denominacion');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "reppagnomdes": // Se hace el llamado desde sigesp_sno_r_pagonominaunidadadmin.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreppagnomdes('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "reppagnomhas": // Se hace el llamado desde sigesp_sno_r_pagonominaunidadadmin.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreppagnomhas('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "replisperdes": // Se hace el llamado desde sigesp_snorh_r_unidadadministrativa.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreplisperdes('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			

					case "replisperhas": // Se hace el llamado desde sigesp_snorh_r_unidadadministrativa.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarreplisperhas('$ls_minorguniadm','$ls_ofiuniadm',";
						print "'$ls_uniuniadm','$ls_depuniadm','$ls_prouniadm');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$ls_codprouniadm."</td>";
						print "<td>".$ls_estclatipo."</td>";
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
  function aceptar(codigo,deno,codest1,codest2,codest3,codest4,codest5,denestpro1,denestpro2,denestpro3,denestpro4,denestpro5,estcla)
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
	opener.document.form1.txtestcla1.value=estcla;
	opener.document.form1.txtestcla2.value=estcla;
	opener.document.form1.txtestcla3.value=estcla;
	opener.document.form1.txtestcla4.value=estcla;
	opener.document.form1.txtestcla5.value=estcla;
    opener.document.form1.existe.value="TRUE";
	close();
  }

  function aceptarcestaticket(codigo,deno)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=deno;
	close();
  }

  function aceptarasignacion(ministerio,oficina,unidad,departamento,programa,desuniadm)
  {
    opener.document.form1.txtcoduniadm.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadm.readOnly=true;
    opener.document.form1.txtdesuniadm.value=desuniadm;
    opener.document.form1.txtdesuniadm.readOnly=true;
	close();
  }
  
  function aceptarreplisconc(ministerio,oficina,unidad,departamento,programa,desuniadm)
  {
    opener.document.form1.txtcoduniadm.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadm.readOnly=true;
    opener.document.form1.txtdenuniadm.value=desuniadm;
    opener.document.form1.txtdenuniadm.readOnly=true;
	close();
  }

  function aceptarasignacioncargo(ministerio,oficina,unidad,departamento,programa,desuniadm,codest1,codest2,codest3,codest4,
  								  codest5,denestpro1,denestpro2,denestpro3,denestpro4,denestpro5,estcla)
  {
    opener.document.form1.txtcoduniadm.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadm.readOnly=true;
    opener.document.form1.txtdesuniadm.value=desuniadm;
    opener.document.form1.txtdesuniadm.readOnly=true;
    opener.document.form1.txtcodestpro1.value=codest1;
    opener.document.form1.txtcodestpro1.readOnly=true;
    opener.document.form1.txtcodestpro2.value=codest2;
    opener.document.form1.txtcodestpro2.readOnly=true;
    opener.document.form1.txtcodestpro3.value=codest3;
    opener.document.form1.txtcodestpro3.readOnly=true;
	opener.document.form1.txtcodestpro4.value=codest4;
    opener.document.form1.txtcodestpro4.readOnly=true;
	opener.document.form1.txtcodestpro5.value=codest5;
    opener.document.form1.txtcodestpro5.readOnly=true;
    opener.document.form1.txtdenestpro1.value=denestpro1;
    opener.document.form1.txtdenestpro1.readOnly=true;
    opener.document.form1.txtdenestpro2.value=denestpro2;
    opener.document.form1.txtdenestpro2.readOnly=true;
    opener.document.form1.txtdenestpro3.value=denestpro3;
    opener.document.form1.txtdenestpro3.readOnly=true;
	opener.document.form1.txtdenestpro4.value=denestpro4;
    opener.document.form1.txtdenestpro4.readOnly=true;
	opener.document.form1.txtdenestpro5.value=denestpro5;
    opener.document.form1.txtdenestpro5.readOnly=true;
    opener.document.form1.txtestcla1.value=estcla;
    opener.document.form1.txtestcla2.value=estcla;
    opener.document.form1.txtestcla3.value=estcla;
    opener.document.form1.txtestcla4.value=estcla;
    opener.document.form1.txtestcla5.value=estcla;
	close();
  }

  function aceptarrepresconcuni(ministerio,oficina,unidad,departamento,programa,desuniadm)
  {
    opener.document.form1.txtcoduniadm.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadm.readOnly=true;
    opener.document.form1.txtdenuniadm.value=desuniadm;
    opener.document.form1.txtdenuniadm.readOnly=true;
	close();
  }

  function aceptarreprecpag(ministerio,oficina,unidad,departamento,programa,desuniadm)
  {
    opener.document.form1.txtcoduniadm.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadm.readOnly=true;
    opener.document.form1.txtdenuniadm.value=desuniadm;
    opener.document.form1.txtdenuniadm.readOnly=true;
	close();
  }

  function aceptarreplisfir(ministerio,oficina,unidad,departamento,programa,desuniadm)
  {
    opener.document.form1.txtcoduniadm.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadm.readOnly=true;
    opener.document.form1.txtdenuniadm.value=desuniadm;
    opener.document.form1.txtdenuniadm.readOnly=true;
	close();
  }

  function aceptarcredenciales(desuniadm)
  {
    opener.document.form1.txtdesuniadm.value=desuniadm;
    opener.document.form1.txtdesuniadm.readOnly=true;
	close();
  }

  function aceptarreppagnomdes(ministerio,oficina,unidad,departamento,programa)
  {
    opener.document.form1.txtcoduniadmdes.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadmdes.readOnly=true;
	close();
  }

  function aceptarreppagnomhas(ministerio,oficina,unidad,departamento,programa)
  {
	if(opener.document.form1.txtcoduniadmdes.value<=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa)
	{
		opener.document.form1.txtcoduniadmhas.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
		opener.document.form1.txtcoduniadmhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de Unidad Administrativa inválido");
	}
  }

  function aceptarreplisperdes(ministerio,oficina,unidad,departamento,programa)
  {
    opener.document.form1.txtcoduniadmdes.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
    opener.document.form1.txtcoduniadmdes.readOnly=true;
	close();
  }

  function aceptarreplisperhas(ministerio,oficina,unidad,departamento,programa)
  {
	if(opener.document.form1.txtcoduniadmdes.value<=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa)
	{
		opener.document.form1.txtcoduniadmhas.value=ministerio+"-"+oficina+"-"+unidad+"-"+departamento+"-"+programa;
		opener.document.form1.txtcoduniadmhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango de Unidad Administrativa inválido");
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
	  f.action="sigesp_snorh_cat_uni_ad.php?tipo=<?php print $ls_tipo;?>";
	  f.submit();
  }
</script>
</html>
