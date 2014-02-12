<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
	$li_longestpro1= (25-$ls_loncodestpro1);
	$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
	$li_longestpro2= (25-$ls_loncodestpro2);
	$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
	$li_longestpro3= (25-$ls_loncodestpro3);
	$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
	$li_longestpro4= (25-$ls_loncodestpro4);
	$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
	$li_longestpro5= (25-$ls_loncodestpro5);
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
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
   function uf_print($as_codproy, $as_nomproy, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codproy  // Código dl proyecto
		//				   as_nomproy  // Descripción nombre del proyecto
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_titulo, $li_len1, $li_len2, $li_len3, $li_len4, $li_len5;
		global $ls_loncodestpro1,$li_longestpro1,$ls_loncodestpro2,$li_longestpro2,$ls_loncodestpro3,$li_longestpro3;
		global $ls_loncodestpro4,$li_longestpro4,$ls_loncodestpro5,$li_longestpro5,$ls_modalidad;
		
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
		print "<td>Tipo </td>";
		print "</tr>";
		$ls_sql="SELECT codemp, codproy, nomproy, estproproy,estcla, ".
				"		(SELECT denestpro1 ".
				"		   FROM spg_ep1 ".
				"		  WHERE codemp=sno_proyecto.codemp".
				"		    AND spg_ep1.codestpro1=substr(sno_proyecto.estproproy,1,25) AND spg_ep1.estcla=sno_proyecto.estcla".
				"           AND spg_ep1.estcla=sno_proyecto.estcla) as denestpro1, ".
				"		(SELECT denestpro2 ".
				"		   FROM spg_ep2 ".
				"		  WHERE codemp=sno_proyecto.codemp".
				"		    AND spg_ep2.codestpro1=substr(sno_proyecto.estproproy,1,25) ".
				"		    AND spg_ep2.codestpro2=substr(sno_proyecto.estproproy,26,25)".
				"           AND spg_ep2.estcla=sno_proyecto.estcla) as denestpro2, ".
				"		(SELECT denestpro3 ".
				"		   FROM spg_ep3 ".
				"		  WHERE codemp=sno_proyecto.codemp".
				"		    AND spg_ep3.codestpro1=substr(sno_proyecto.estproproy,1,25) ".
				"		    AND spg_ep3.codestpro2=substr(sno_proyecto.estproproy,26,25) ".
				"		    AND spg_ep3.codestpro3=substr(sno_proyecto.estproproy,51,25)".
				"           AND spg_ep3.estcla=sno_proyecto.estcla) as denestpro3, ".
				"		(SELECT denestpro4 ".
				"		   FROM spg_ep4 ".
				"		  WHERE codemp=sno_proyecto.codemp".
				"		    AND spg_ep4.codestpro1=substr(sno_proyecto.estproproy,0,25) ".
				"		    AND spg_ep4.codestpro2=substr(sno_proyecto.estproproy,26,25) ".
				"		    AND spg_ep4.codestpro3=substr(sno_proyecto.estproproy,51,25) ".
				"		    AND spg_ep4.codestpro4=substr(sno_proyecto.estproproy,76,25)".
				"           AND spg_ep4.estcla=sno_proyecto.estcla) as denestpro4, ".
				"		(SELECT denestpro5 ".
				"		   FROM spg_ep5 ".
				"		  WHERE codemp=sno_proyecto.codemp".
				"		    AND spg_ep5.codestpro1=substr(sno_proyecto.estproproy,0,25) ".
				"		    AND spg_ep5.codestpro2=substr(sno_proyecto.estproproy,26,25) ".
				"		    AND spg_ep5.codestpro3=substr(sno_proyecto.estproproy,51,25) ".
				"		    AND spg_ep5.codestpro4=substr(sno_proyecto.estproproy,76,25) ".
				"		    AND spg_ep5.codestpro5=substr(sno_proyecto.estproproy,101,25)".
				"           AND spg_ep5.estcla=sno_proyecto.estcla) as denestpro5 ".
				"  FROM sno_proyecto ".
				" WHERE codemp='".$ls_codemp."' ".
				"   AND codproy like '".$as_codproy."' ".
				"   AND nomproy like '".$as_nomproy."' ".
	   			" ORDER BY codproy ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codproy=$row["codproy"];
				$ls_nomproy=$row["nomproy"];
				$estproproy=$row["estproproy"];
				$ls_estcla=$row["estcla"];
				$ls_codest1=substr($estproproy,0,25);
				$ls_codest2=substr($estproproy,25,25);
				$ls_codest3=substr($estproproy,50,25);
				$ls_codest4=substr($estproproy,75,25);
				$ls_codest5=substr($estproproy,100,25);
				$ls_codest1=substr($ls_codest1,$li_longestpro1,$ls_loncodestpro1);
				$ls_codest2=substr($ls_codest2,$li_longestpro2,$ls_loncodestpro2);
				$ls_codest3=substr($ls_codest3,$li_longestpro3,$ls_loncodestpro3);
				$ls_codest4=substr($ls_codest4,$li_longestpro4,$ls_loncodestpro4);
				$ls_codest5=substr($ls_codest5,$li_longestpro5,$ls_loncodestpro5);
				$ls_denestpro1=$row["denestpro1"];
				$ls_denestpro2=$row["denestpro2"];
				$ls_denestpro3=$row["denestpro3"];
				$ls_denestpro4=$row["denestpro4"];
				$ls_denestpro5=$row["denestpro5"];
				switch($ls_modalidad)
				{
					case "1": // Modalidad por Proyecto
					$ls_codest4="";
					$ls_codest5="";
					break;
				}
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
					case "": // Se hace el llamado desde sigesp_snorh_d_proyecto.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: ue_aceptar('$ls_codproy','$ls_nomproy','$ls_codest1',";
						print "'$ls_codest2','$ls_codest3','$ls_codest4','$ls_codest5','$ls_denestpro1','$ls_denestpro2',";
						print "'$ls_denestpro3','$ls_denestpro4','$ls_denestpro5','$ls_estcla');\">".$ls_codproy."</a></td>";
						print "<td>".$ls_nomproy."</td>";
						print "<td align=center>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;		
							
					case "personaproyecto": // Se hace el llamado desde sigesp_sno_d_personaproyecto.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: ue_aceptarpersonaproyecto('$ls_codproy','$ls_nomproy');\">".$ls_codproy."</a></td>";
						print "<td>".$ls_nomproy."</td>";
						print "<td align=center>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			
							
					case "replisproydes": // Se hace el llamado desde sigesp_sno_r_listadoproyecto.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: ue_aceptarreplisproydes('$ls_codproy');\">".$ls_codproy."</a></td>";
						print "<td>".$ls_nomproy."</td>";
						print "<td align=center>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
						print "<td>".$ls_estclatipo."</td>";
						print "</tr>";			
						break;			
							
					case "replisproyhas": // Se hace el llamado desde sigesp_sno_r_listadoproyecto.php
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: ue_aceptarreplisproyhas('$ls_codproy');\">".$ls_codproy."</a></td>";
						print "<td>".$ls_nomproy."</td>";
						print "<td align=center>".$ls_codest1.$ls_codest2.$ls_codest3.$ls_codest4.$ls_codest5."</td>";
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
<title>Cat&aacute;logo de Proyectos</title>
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
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Proyectos </td>
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
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_aceptar(codproy,nomproy,codest1,codest2,codest3,codest4,codest5,denestpro1,denestpro2,denestpro3,denestpro4,denestpro5,estcla)
{
    opener.document.form1.txtcodproy.value=codproy;
	opener.document.form1.txtcodproy.readOnly=true;
    opener.document.form1.txtnomproy.value=nomproy;
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

function ue_aceptarpersonaproyecto(codproy,nomproy)
{
    pos=opener.document.form1.totalfilas.value;
	valido=true;
	for(li_i=1;(li_i<pos)&&(valido);li_i++)
	{
		codigo= eval("opener.document.form1.txtcodproy"+li_i+".value;");
		if(codigo==codproy)
		{
			alert("El Proyecto ya lo tiene asignado el personal");
			valido=false;
		}
	}
	if(valido)
	{
		eval("opener.document.form1.txtcodproy"+pos+".value='"+codproy+"';");
		eval("opener.document.form1.txtnomproy"+pos+".value='"+nomproy+"';");
		opener.document.form1.operacion.value="CARGARPROYECTO";
		opener.document.form1.action="sigesp_sno_d_personaproyecto.php";
		opener.document.form1.submit();
		close();
	}
}

function ue_aceptarreplisproydes(codproy)
{
    opener.document.form1.txtcodproydes.value=codproy;
	opener.document.form1.txtcodproydes.readOnly=true;
	opener.document.form1.txtcodproyhas.value="";
	close();
}

function ue_aceptarreplisproyhas(codproy)
{
	if(opener.document.form1.txtcodproydes.value<=codproy)
	{
		opener.document.form1.txtcodproyhas.value=codproy;
		opener.document.form1.txtcodproyhas.readOnly=true;
		close();
	}
	else
	{
		alert("Rango del Proyecto inválido");
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
	  f.action="sigesp_snorh_cat_proyecto.php?tipo=<?php print $ls_tipo;?>";
	  f.submit();
  }
</script>
</html>
