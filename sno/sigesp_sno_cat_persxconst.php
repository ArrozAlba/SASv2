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
   function uf_print($as_cedper, $as_nomper, $as_apeper, $as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_cedper  // Cédula del personal
		//				   as_nomper  // nombre del personal
		//				   as_apeper  // apellido del personal
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
        $ls_codnom=$_SESSION["la_nomina"]["codnom"];
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=80>Código</td>";
		print "<td width=80>Cedula</td>";
		print "<td width=340>Nombre</td>";
		print "</tr>";
		$ls_sql="SELECT sno_personalnomina.codper, sno_personalnomina.sueper, sno_personal.cedper, sno_personal.nomper, sno_personal.apeper, ".
				"       sno_personalnomina.minorguniadm, sno_personalnomina.ofiuniadm, sno_personalnomina.uniuniadm,".
				"       sno_personalnomina.depuniadm, sno_personalnomina.prouniadm ".
				"  FROM sno_personalnomina, sno_personal ".
				" WHERE sno_personalnomina.codnom = '".$ls_codnom."' ".
				"   AND sno_personal.cedper like '".$as_cedper."' ".
				"   AND sno_personal.nomper like '".$as_nomper."' ".
				"   AND sno_personal.apeper like '".$as_apeper."' ".
				"   AND sno_personalnomina.codper IN (SELECT codper ".
				"                                       FROM sno_constantepersonal ".
				"                                      WHERE codemp='".$ls_codemp."' ".
				"										 AND codnom='".$ls_codnom."') ".
				"   AND sno_personalnomina.codemp = sno_personal.codemp ".
				"   AND sno_personalnomina.codper = sno_personal.codper ".
				" ORDER BY sno_personal.codper";
				
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_cedper=$row["cedper"];
				$ls_nomper=$row["nomper"];
				$ls_apeper=$row["apeper"];
				$ls_nombre=$ls_apeper.", ".$ls_nomper;
				$ld_sueper=number_format($row["sueper"],2,",",".");
				$ls_uniad=$row["minorguniadm"]."-".$row["ofiuniadm"]."-".$row["uniuniadm"]."-".$row["depuniadm"]."-".$row["prouniadm"];
				print "<tr class=celdas-blancas>";
				print "<td><a href=\"javascript: aceptar('$ls_codper','$ls_cedper','$ls_nombre','$ld_sueper','$ls_uniad');\">".$ls_codper."</a></td>";
				print "<td>".$ls_cedper."</td>";
				print "<td>".$ls_nombre."</td>";
				print "</tr>";			
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
<title>Cat&aacute;logo de Constantes x Persona</title>
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
     	 	<td width="500" colspan="2" class="titulo-ventana">Cat&aacute;logo de Constantes x Personal</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
       <tr>
         <td width="67" height="22"><div align="right">C&eacute;dula</div></td>
         <td width="431"><div align="left">
           <input name="txtcedper" type="text" id="txtcedper" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">
         </div></td>
       </tr>
       <tr>
         <td height="22"><div align="right">Nombre</div></td>
         <td><div align="left">
           <input name="txtnomper" type="text" id="txtnomper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
         </div></td>
       </tr>
       <tr>
         <td height="22"><div align="right">Apellido</div></td>
         <td><div align="left">
             <input name="txtapeper" type="text" id="txtapeper" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
         </div></td>
       </tr>
       <tr>
         <td height="22">&nbsp;</td>
         <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
       </tr>
     </table>
	 <div align="center"><br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if($ls_operacion=="BUSCAR")
	{
		$ls_cedper="%".$_POST["txtcedper"]."%";
		$ls_nomper="%".$_POST["txtnomper"]."%";
		$ls_apeper="%".$_POST["txtapeper"]."%";
		uf_print($ls_cedper, $ls_nomper, $ls_apeper, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codper,cedper,nomper,sueper,uniad)
{
	opener.document.form1.txtcodper.value=codper;
	opener.document.form1.txtcodper.readOnly;
	opener.document.form1.txtcedper.value=cedper;
	opener.document.form1.txtnomper.value=nomper;
	opener.document.form1.txtuniad.value=uniad;
	opener.document.form1.txtsueper.value=sueper;
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.submit();
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
	f.action="sigesp_sno_cat_persxconst.php";
	f.submit();
}
</script>
</html>