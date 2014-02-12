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
   function uf_print($as_codcom, $as_descom,$as_tipo)
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codcom  // Código del Componente
		//				   as_desde  // Descripción del Componente
		//				   as_tipo  // Verifica de donde se está llamando el catálogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/07/2007 								Fecha Última Modificación : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		print "<td width=60>Código</td>";
		print "<td width=440>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT codcom, descom ".
				"  FROM sno_componente ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codcom like '".$as_codcom."' AND descom like '".$as_descom."'".
				" ORDER BY codcom ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codcom=$row["codcom"];
				$ls_descom=$row["descom"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptar('$ls_codcom','$ls_descom');\">".$ls_codcom."</a></td>";
						print "<td>".$ls_descom."</td>";
						print "</tr>";			
						break;
						
					case "personal":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarpersonal('$ls_codcom','$ls_descom');\">".$ls_codcom."</a></td>";
						print "<td>".$ls_descom."</td>";
						print "</tr>";			
						break;
					case "componentedes":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarlistadodes('$ls_codcom');\">".$ls_codcom."</a></td>";
						print "<td>".$ls_descom."</td>";
						print "</tr>";			
						break;
					case "componentehas":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: ue_aceptarlistadohas('$ls_codcom');\">".$ls_codcom."</a></td>";
						print "<td>".$ls_descom."</td>";
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
<title>Cat&aacute;logo de Componente</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Componente </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodcom" type="text" id="txtcodcom" size="30" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdescom" type="text" id="txtdescom" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
		$ls_codcom="%".$_POST["txtcodcom"]."%";
		$ls_descom="%".$_POST["txtdescom"]."%";
		uf_print($ls_codcom, $ls_descom, $ls_tipo);
	}
	else
	{
		$ls_codcom="%%";
		$ls_descom="%%";
		uf_print($ls_codcom, $ls_descom, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_aceptar(codcom,descom)
{
	opener.document.form1.txtcodcom.value=codcom;
	opener.document.form1.txtcodcom.readOnly=true;
    opener.document.form1.txtdescom.value=descom;
	opener.document.form1.existe.value="TRUE";
	opener.document.form1.btnrango.disabled=false;	
	close();
}

function ue_aceptarpersonal(codcom,descom)
{
	opener.document.form1.txtcodcom.value=codcom;
	opener.document.form1.txtcodcom.readOnly=true;
    opener.document.form1.txtdescom.value=descom;
	opener.document.form1.txtdescom.readOnly=true;
	close();
}

function ue_aceptarlistadodes(codcom)
{
  	opener.document.form1.txtcodcomdes.value=codcom;
	opener.document.form1.txtcodcomdes.readOnly=true;   
	close();
}

function ue_aceptarlistadohas(codcom)
{
    codcomdes=opener.document.form1.txtcodcomdes.value;
	if (codcomdes<=codcom)
	{
		opener.document.form1.txtcodcomhas.value=codcom;
		opener.document.form1.txtcodcomhas.readOnly=true;   
		close();
	}
	else
	{
		alert("El rango seleccionado es invalido");
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

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_snorh_cat_componente.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
