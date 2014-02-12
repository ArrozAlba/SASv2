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
   function uf_print($as_codper, $as_cedben, $as_nomben, $as_apeben, $as_tipo, $as_codperdes, $as_codperhas)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codper  // código del personal
		//				   as_cedben  // cedula del beneficiario
		//				   as_nomben  // nombre del beneficiario
		//				   as_apeben  // apellido del beneficiario
		//				   as_tipo    // tipo del catalogo
		//	  Description: Función que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 07/11/2007 								Fecha Última Modificación : 
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
		$ls_criterio="";
		$ls_criterio2="";
		$ls_order="";		
		if ($as_tipo=="")
		{ 		
			print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		  	print "<tr class=titulo-celda>";
		 	print "<td width=60>Cédula</td>";
		 	print "<td width=440>Nombre y Apellido</td>";
		  	print "</tr>";
		}
		else
		{
		    print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		  	print "<tr class=titulo-celda>";
			print "<td width=60>Código del Personal</td>";
			print "<td width=60>Código del Beneficiario</td>";
		 	print "<td width=60>Cédula</td>";
		 	print "<td width=440>Nombre y Apellido</td>";
		  	print "</tr>";
		
		}
		
		if (($as_codper=="")&&($as_codperdes=="")&&($as_codperhas==""))
		{
			$ls_criterio="   AND codper like '%%' ";
			
			switch($_SESSION["ls_gestor"]) 
	   		{
				case "MSQLT":				
					$ls_orden= "ORDER BY cedben";
				break;
				case "POSTGRES":				 
				    $ls_order= "ORDER BY  cedben";
				break;
			}
		}
		elseif ($as_codper!="")
		{
			$ls_criterio="   AND codper='".$as_codper."'";
			$ls_order   = " ORDER BY codben,cedben";
		}
		
		if (($as_codperdes!="")&&($as_codperhas!=""))
		{
			$ls_criterio2="   AND codper between '".$as_codperdes."' and '".$as_codperhas."'   ";
			switch($_SESSION["ls_gestor"])
	   		{
				case "MSQLT":				
					$ls_order= "ORDER BY cedben ";
				break;
				case "POSTGRES":
				   
				    $ls_order= "ORDER BY cedben";
				break;
			}
		}
		
		$ls_sql="SELECT codemp, codper, codben, cedben, nomben, apeben, dirben, telben, tipben, nomcheben, porpagben, monpagben, ".
				"		codban, ctaban, sc_cuenta, forpagben, nacben, tipcueben, nexben, cedaut, numexpben, ".
				"       (SELECT scb_banco.nomban FROM scb_banco WHERE scb_banco.codemp = '".$ls_codemp."' ".
				"			AND scb_banco.codban = sno_beneficiario.codban) AS nomban ".
				"  FROM sno_beneficiario ".
				" WHERE codemp='".$ls_codemp."'".$ls_criterio.$ls_criterio2.			
				"   AND cedben like '".$as_cedben."' ".
				"   AND nomben like '".$as_nomben."' ".
				"   AND apeben like '".$as_apeben."' ".$ls_order; 
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
				$ls_codben=$row["codben"];
				$ls_cedben=$row["cedben"];
				$ls_nomben=$row["nomben"];
				$ls_apeben=$row["apeben"];
				$ls_dirben=$row["dirben"];
				$ls_telben=$row["telben"];
				$ls_tipben=$row["tipben"];
				$ls_nomcheben=$row["nomcheben"];
				$li_porpagben=number_format($row["porpagben"],2,",",".");
				$li_monpagben=number_format($row["monpagben"],2,",",".");
				$ls_codban=$row["codban"];
				$ls_nomban=$row["nomban"];
				$ls_ctaban=$row["ctaban"];
				$ls_forpagben=$row["forpagben"];
				$ls_nacben=$row["nacben"];
				$ls_tipcueben=$row["tipcueben"];
				$ls_nexben=$row["nexben"];
				$ls_cedaut=$row["cedaut"];
				$ls_numexpben=$row["numexpben"];				
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_codben','$ls_cedben','$ls_nomben','$ls_apeben','$ls_dirben','$ls_telben',";
						print "'$ls_tipben','$ls_nomcheben','$li_porpagben','$li_monpagben','$ls_codban','$ls_nomban','$ls_ctaban','$ls_forpagben','$ls_nacben','$ls_tipcueben','$ls_nexben','$ls_cedaut','$ls_numexpben');\">".$ls_cedben."</a></td>";
						print "<td>".$ls_nomben." ".$ls_apeben."</td>";
						print "</tr>";	
					break;
					
					case "benedes":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_codper."</td>";
						print "<td><a href=\"javascript: aceptar_benedes('$ls_codben');\">".$ls_codben."</a></td>";
						print "<td>".$ls_cedben."</td>";
						print "<td>".$ls_nomben." ".$ls_apeben."</td>";
						print "</tr>";	
					break;
					
					case "benehas":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_codper."</td>";
						print "<td><a href=\"javascript: aceptar_benehas('$ls_codben');\">".$ls_codben."</a></td>";
						print "<td>".$ls_cedben."</td>";
						print "<td>".$ls_nomben." ".$ls_apeben."</td>";
						print "</tr>";	
					break;
					
					case "benedes1":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_codper."</td>";
						print "<td>".$ls_codben."</td>";
						print "<td><a href=\"javascript: aceptar_benedes1('$ls_cedben');\">".$ls_cedben."</a></td>";
						print "<td>".$ls_nomben." ".$ls_apeben."</td>";
						print "</tr>";	
					break;
					
					case "benehas1":
						print "<tr class=celdas-blancas>";
						print "<td>".$ls_codper."</td>";
						print "<td>".$ls_codben."</td>";
						print "<td><a href=\"javascript: aceptar_benehas1('$ls_cedben');\">".$ls_cedben."</a></td>";
						print "<td>".$ls_nomben." ".$ls_apeben."</td>";
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
<title>Cat&aacute;logo de Beneficiario</title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Beneficiario </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&eacute;dula</div></td>
        <td width="431"><div align="left">
          <input name="txtcedben" type="text" id="txtcedben" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtnomben" type="text" id="txtnomben" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td><div align="left">
          <input name="txtapeben" type="text" id="txtapeben" size="30" maxlength="60" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();">
          <img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes",""); 
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");			
	if($ls_operacion=="BUSCAR")
	{
	    $ls_cedben="%".$_POST["txtcedben"]."%";
		$ls_nomben="%".$_POST["txtnomben"]."%";
		$ls_apeben="%".$_POST["txtapeben"]."%";
		$ls_codper=$_POST["txtcodper"];				
		uf_print($ls_codper,$ls_cedben,$ls_nomben, $ls_apeben, $ls_tipo, $ls_codperdes, $ls_codperhas);
	}
	else
	{
	    $ls_codper=$_GET["codper"];			
		$ls_cedben="%%";
		$ls_nomben="%%";
		$ls_apeben="%%";		
		if ($ls_codper!="")
		{
	    	uf_print($ls_codper,$ls_cedben,$ls_nomben, $ls_apeben, $ls_tipo, $ls_codperdes, $ls_codperhas);
		}
		if (($ls_codperdes!="")&&($ls_codperhas!=""))
		{
			uf_print($ls_codper,$ls_cedben,$ls_nomben, $ls_apeben, $ls_tipo, $ls_codperdes, $ls_codperhas);
		}
		
	}
	unset($io_fun_nomina);
?>
</div>
          <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codben,cedben,nomben,apeben,dirben,telben,tipben,nomcheben,porpagben,monpagben,codban,nomban,ctaban,forpagben,nacben,tipcueben, nexben,cedaut, numexpben)
{
	opener.document.form1.txtcodben.value=codben;
	opener.document.form1.txtcodben.readOnly=true;
    opener.document.form1.txtcedben.value=cedben;
	opener.document.form1.txtnomben.value=nomben;
    opener.document.form1.txtapeben.value=apeben;
	opener.document.form1.txtdirben.value=dirben;
    opener.document.form1.txttelben.value=telben;
    opener.document.form1.cmbtipben.value=tipben;
	opener.document.form1.txtnomcheben.value=nomcheben;
    opener.document.form1.txtporpagben.value=porpagben;
	opener.document.form1.txtmonpagben.value=monpagben;
    opener.document.form1.txtcodban.value=codban;
    opener.document.form1.txtnomban.value=nomban;
    opener.document.form1.txtctaban.value=ctaban;
    opener.document.form1.cmbforpagben.value=forpagben;
    opener.document.form1.cmbnacben.value=nacben;
    opener.document.form1.cmbtipcueben.value=tipcueben;
	opener.document.form1.cmbnexben.value=nexben;
	opener.document.form1.txtcedaut.value=cedaut;
	opener.document.form1.txtnumexpben.value=numexpben;	
	opener.document.form1.cmbtipben.disabled="disabled";
	opener.document.form1.existe.value="TRUE";		
	close();
}

function aceptar_benedes(codben)
{
	opener.document.form1.txtcodbenedes.value=codben;
	opener.document.form1.txtcodbenedes.readOnly=true; 		
	close();
}

function aceptar_benehas(codben)
{
	opener.document.form1.txtcodbenehas.value=codben;
	opener.document.form1.txtcodbenehas.readOnly=true; 		
	close();
}

function aceptar_benedes1(cedben)
{
	opener.document.form1.txtcodbenedes.value=cedben;
	opener.document.form1.txtcodbenedes.readOnly=true; 		
	close();
}

function aceptar_benehas1(cedben)
{
	opener.document.form1.txtcodbenehas.value=cedben;
	opener.document.form1.txtcodbenehas.readOnly=true; 		
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
  	f.action="sigesp_snorh_cat_beneficiario.php?tipo=<?php print $ls_tipo;?>&codperdes=<?php print $ls_codperdes;?>&codperhas=<?php print $ls_codperhas;?>";
  	f.submit();
}
</script>
</html>
