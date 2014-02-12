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
   function uf_print($as_grado, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_grado  // Código de escala docente
		//				   as_desescdoc  // Descripción de la escala docente
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
		print "<td width=200>Grado</td>";
		print "<td width=150>Mínimo</td>";
		print "<td width=150>Máximo</td>";
		print "</tr>";
		$ls_sql="SELECT grado, suemin, suemax, tipcla, obscla, anovig, nrogac ".
				"  FROM sno_clasificacionobrero ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND grado like '".$as_grado."'".
				" ORDER BY grado ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_grado=$row["grado"];
				$li_suemin=number_format($row["suemin"],2,",",".");
				$li_suemax=number_format($row["suemax"],2,",",".");
				$ls_tipcla=$row["tipcla"];
				$ls_obscla=$row["obscla"];
				$ls_anovig=$row["anovig"];
				$ls_nrogac=$row["nrogac"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_grado','$li_suemin','$li_suemax','$ls_tipcla','$ls_obscla','$ls_anovig','$ls_nrogac');\">".$ls_grado."</a></td>";
						print "<td>".$li_suemin."</td>";
						print "<td>".$li_suemax."</td>";
						print "</tr>";			
						break;
	
					case "asignacion":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacion('$ls_grado','$li_suemin');\">".$ls_grado."</a></td>";
						print "<td>".$li_suemin."</td>";
						print "<td>".$li_suemax."</td>";
						print "</tr>";			
						break;
					case "asignacioncargo":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarasignacioncargo('$ls_grado','$li_suemin');\">".$ls_grado."</a></td>";
						print "<td>".$li_suemin."</td>";
						print "<td>".$li_suemax."</td>";
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
<title>Cat&aacute;logo de Clasificaci&oacute;n Obrero</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Clasificaci&oacute;n Obrero </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">Grado</div></td>
        <td width="431"><div align="left">
          <input name="txtgrado" type="text" id="txtgrado" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
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
		$ls_grado="%".$_POST["txtgrado"]."%";
		uf_print($ls_grado, $ls_tipo);
	}
	else
	{
		$ls_grado="%%";
		uf_print($ls_grado, $ls_tipo);
	}

	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(grado,suemin,suemax,tipcla,obscla,anovig,nrogac)
{
	opener.document.form1.txtgrado.value=grado;
	opener.document.form1.txtgrado.readOnly=true;
    opener.document.form1.txtsuemin.value=suemin;
    opener.document.form1.txtsuemax.value=suemax;
    opener.document.form1.txtobscla.value=obscla;
    opener.document.form1.cmbtipcla.value=tipcla;
    opener.document.form1.txtanovig.value=anovig;
    opener.document.form1.txtnrogac.value=nrogac;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarasignacion(grado,suemin)
{
	opener.document.form1.txtgrado.value=grado;
	opener.document.form1.txtgrado.readOnly=true;
	if((parseFloat(opener.document.form1.txtsueper.value)==0)||(opener.document.form1.txtsueper.value==""))
	{
		opener.document.form1.txtsueper.value=suemin;
	}
	close();
}

function aceptarasignacioncargo(grado,suemin)
{
	opener.document.form1.txtcodgraobrero.value=grado;
	opener.document.form1.txtcodgraobrero.readOnly=true;
	opener.document.form1.txtmonsalgra.value=suemin;	
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

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_clasificacionobrero.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
