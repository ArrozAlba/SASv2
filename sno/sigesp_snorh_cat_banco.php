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
   function uf_print($as_codban, $as_nomban, $as_codnomdes, $as_codnomhas, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codban  // Código de banco
		//				   as_nomban  // nombre del banco
		//				   as_codnomdes  // Código de Nómina Desde
		//				   as_codnomhas  // Código de Nómina Hasta
		//				   as_tipo  // Tipo de llamada del catálogo
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
		if(array_key_exists("la_nomina",$_SESSION))
		{
			$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$ls_codnom="0000";
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=80>Banco</td>";
		print "<td width=420>Descripción</td>";
		print "</tr>";
		$ls_sql="SELECT codban,nomban ".
				"  FROM scb_banco ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND codban like '".$as_codban."'".
				"   AND nomban like '".$as_nomban."'";
		if($ls_codnom!="0000")
		{
			if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
			{
				if(($as_tipo=="replisperche")||($as_tipo=="replisban")||($as_tipo=="archtxt"))
				{
					$ls_sql=$ls_sql." AND codban IN (SELECT codban ".
									"				   FROM sno_personalnomina ".
									"				  WHERE codemp='".$ls_codemp."'".
									"				    AND codnom='".$ls_codnom."')";
				}
			}
			else
			{
				if(($as_tipo=="replisperche")||($as_tipo=="replisban"))
				{
					$ls_sql=$ls_sql." AND codban IN (SELECT codban ".
									"				   FROM sno_thpersonalnomina ".
									"				  WHERE codemp='".$ls_codemp."'".
									"				    AND codnom='".$ls_codnom."')";
				}
			}
		}
		if(($as_tipo=="repconlisban")||($as_tipo=="depbandes")||($as_tipo=="depbanhas"))
		{
			$ls_sql="SELECT codban, MAX(nomban) AS nomban ".
					"  FROM scb_banco ".
					" WHERE codemp='".$ls_codemp."'".
					"   AND codban like '".$as_codban."'".
					"   AND nomban like '".$as_nomban."'".
					"   AND codban IN (SELECT codban ".
				    "   				 FROM sno_personalnomina ".
					"				   WHERE codemp='".$ls_codemp."'".
					"				     AND codnom>='".$as_codnomdes."' ".
					"					 AND codnom<='".$as_codnomhas."' )" .
					" GROUP BY codban ";
		}
		$ls_sql=$ls_sql." ORDER BY codban ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codban=$row["codban"];
				$ls_nomban=$row["nomban"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;

					case "replisperche": // el llamado se hace desde  sigesp_sno_r_listadopersonalcheque.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisperche('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;

					case "replisban": // el llamado se hace desde  sigesp_sno_r_listadobanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisban('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;

					case "repconlisban": // el llamado se hace desde  sigesp_snorh_r_listadobanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarrepconlisban('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;

					case "beneficiario": // el llamado se hace desde  sigesp_snorh_d_beneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarbeneficiario('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;

					case "replisben": // el llamado se hace desde  sigesp_snorh_d_beneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisben('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
						
					case "depbandes": // el llamado se hace desde  sigesp_snorh_r_listadobanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptardepbandes('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
						
					case "depbanhas": // el llamado se hace desde  sigesp_snorh_r_listadobanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptardepbanhas('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
						
					case "replisbanpen": // el llamado se hace desde  sigesp_sno_r_listadobanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbanpen('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
						
					case "replisbanpenh": // el llamado se hace desde  sigesp_sno_r_listadobanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbanpenh('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
					
					case "replisbenpen": // el llamado se hace desde  sigesp_snorh_d_beneficiario.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreplisbenpen('$ls_codban','$ls_nomban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
						
					case "reppagbandes": // el llamado se hace desde  sigesp_sno_r_pagosbanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppagbandes('$ls_codban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
						
					case "reppagbanhas": // el llamado se hace desde  sigesp_sno_r_pagosbanco.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarreppagbanhas('$ls_codban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
						print "</tr>";			
						break;
					case "archtxt": // el llamado se hace desde  sigesp_sno_r_metodo_fonz.php
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptararchtxt('$ls_codban');\">".$ls_codban."</a></td>";
						print "<td>".$ls_nomban."</td>";
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
<title>Cat&aacute;logo de Banco</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de  Banco </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="111" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="380"><div align="left">
          <input name="txtcodban" type="text" id="txtcodban" size="30" maxlength="4" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"> Nombre </div></td>
        <td><div align="left">
          <input name="txtnomban" type="text" id="txtnomban" size="30" maxlength="20" onKeyPress="javascript: ue_mostrar(this,event);">
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
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	if($ls_operacion=="BUSCAR")
	{
		$ls_codban="%".$_POST["txtcodban"]."%";
		$ls_nomban="%".$_POST["txtnomban"]."%";
		uf_print($ls_codban, $ls_nomban, $ls_codnomdes, $ls_codnomhas, $ls_tipo);
	}
	else
	{
		$ls_codban="%%";
		$ls_nomban="%%";
		uf_print($ls_codban, $ls_nomban, $ls_codnomdes, $ls_codnomhas, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	opener.document.form1.txtcodage.value="";
	opener.document.form1.txtcodage.readOnly=true;
	opener.document.form1.txtnomage.value="";
	opener.document.form1.txtnomage.readOnly=true;

	close();
}

function aceptarreplisperche(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	close();
}

function aceptarreplisban(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	opener.document.form1.txtcodcue.value="";
	opener.document.form1.txtcodcue.readOnly=true;
	close();
}

function aceptarreplisbanpen(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	opener.document.form1.txtcodcue.value="";
	opener.document.form1.txtcodcue.readOnly=true;
	opener.document.form1.operacion.value="BANCO";
	pag=opener.document.form1.txtpag.value;
	opener.document.form1.action="sigesp_sno_r_listadobanco.php?&pagina="+pag;
	opener.document.form1.submit();	
	close();
}

function aceptarreplisbanpenh(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	opener.document.form1.txtcodcue.value="";
	opener.document.form1.txtcodcue.readOnly=true;
	opener.document.form1.operacion.value="BANCO";
	pag=opener.document.form1.txtpag.value;
	opener.document.form1.action="sigesp_sno_r_hlistadobanco.php?&pagina="+pag;
	opener.document.form1.submit();	
	close();
}

function aceptarrepconlisban(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	opener.document.form1.txtcodcue.value="";
	opener.document.form1.txtcodcue.readOnly=true;
	close();
}

function aceptarbeneficiario(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	close();
}

function aceptarreplisben(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	close();
}

function aceptardepbandes(codban,nomban)
{
    opener.document.form1.txtcodbandes.value=codban;
	opener.document.form1.txtcodbandes.readOnly=true;
	close();
}

function aceptardepbanhas(codban,nomban)
{
	opener.document.form1.txtcodbanhas.value=codban;
	opener.document.form1.txtcodbanhas.readOnly=true;
	close();
}

function aceptarreppagbandes(codban,nomban)
{
    opener.document.form1.txtcodbandes.value=codban;
	opener.document.form1.txtcodbandes.readOnly=true;
	close();
}

function aceptarreppagbanhas(codban,nomban)
{
	opener.document.form1.txtcodbanhas.value=codban;
	opener.document.form1.txtcodbanhas.readOnly=true;
	close();
}

function aceptarreplisbenpen(codban,nomban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
	opener.document.form1.txtnomban.value=nomban;
	opener.document.form1.txtnomban.readOnly=true;
	opener.document.form1.operacion.value="BANCO";
	pag=opener.document.form1.txtpag.value;
	opener.document.form1.action="sigesp_sno_r_listadobeneficiario.php?&pagina="+pag;
	opener.document.form1.submit();	
	close();
}

function aceptararchtxt(codban)
{
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtcodban.readOnly=true;
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
  	f.action="sigesp_snorh_cat_banco.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
