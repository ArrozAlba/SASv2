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
   function uf_print($as_codigo, $as_denominacion,$as_tipo)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_codpro  // Código del cesta ticket
		//				   as_despro  // Denominación del cesta ticket
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
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td>Código </td>";
		print "<td>Denominación</td>";
		print "<td>Valor</td>";
		print "<td>Metodo</td>";
		print "</tr>";
		$ls_sql= "SELECT codemp, codcestic, dencestic, moncestic, metcestic, codcli, codprod, punent, mondesdia ".
				 "  FROM sno_cestaticket ".
				 " WHERE codemp='".$ls_codemp."' ".
				 "   AND codcestic like '".$as_codigo."' AND dencestic like '".$as_denominacion."' ".
				 " ORDER BY codcestic ";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$codigo=$row["codcestic"];
				$denominacion=$row["dencestic"];
				$ld_valor=$row["moncestic"];
				$valor=number_format($ld_valor,2,",",".");
				$ls_metodo=$row["metcestic"];
				$ls_codcli=$row["codcli"];
				$ls_codprod=$row["codprod"];
				$ls_punent=$row["punent"];
				$ld_valdes=number_format($row["mondesdia"],2,",",".");
				$metodo="";
				switch($ls_metodo)
				{
					case "1":
				  		$metodo="Accord Ticket Univalor";
						break;
					case "2":
				  		$metodo="Accord Tarjeta";
						break;
					case "3":
				  		$metodo="Cesta Casa";
						break;
					case "4":
				  		$metodo="Valeven Ticket";
						break;
					case "5":
				  		$metodo="Sodexho Tarjeta";
						break;
					case "6":
				  		$metodo="Sodexho Ticket";
						break;
					case "7":
				  		$metodo="Banco Industrial Electronico";
						break;
					case "8":
				  		$metodo="Accord Ticket Multivalor";
						break;
					case "9":
				  		$metodo="Valeven Tarjeta";
						break;
						
					case "10":
				  		$metodo="IPSFA";
						break;
						
					case "11":
				  		$metodo="Todo Ticket Tarjeta";
						break;
						
					case "12":
				  		$metodo="EfecTicket";
						break;
						
					case "13":
				  		$metodo="Sodexho Ticket Plus";
						break;
				}
				switch($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$valor','$ls_metodo',";
						print "'$ls_codcli','$ls_codprod','$ls_punent','$ld_valdes');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$valor."</td>";
						print "<td align=center>".$metodo."</td>";
						print "</tr>";
						break;

					case "nomina":
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptarnomina('$codigo');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$valor."</td>";
						print "<td align=center>".$metodo."</td>";
						print "</tr>";
						break;

					case "gendisk":
						print "<tr class=celdas-blancas>";
						print "<td align=center><a href=\"javascript: aceptargendisk('$codigo','$denominacion','$metodo','$ls_codcli','$ls_codprod','$ls_punent');\">".$codigo."</a></td>";
						print "<td>".$denominacion."</td>";
						print "<td align=center>".$valor."</td>";
						print "<td align=center>".$metodo."</td>";
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
<title>Cat&aacute;logo de Cesta Tickets</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cesta Tickets </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="100" height="22"><div align="right">Codigo</div></td>
        <td width="400"><div align="left">
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
		$ls_operacion=$_POST["operacion"];
		$ls_codigo="%".$_POST["codigo"]."%";
		$ls_denominacion="%".$_POST["denominacion"]."%";
		uf_print($ls_codigo, $ls_denominacion,$ls_tipo);
	}
	else
	{
		$ls_codigo="%%";
		$ls_denominacion="%%";
		uf_print($ls_codigo, $ls_denominacion,$ls_tipo);
	}	
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codigo,deno,valor,metodo,codcli,codprod,punent,valdes)
{
	opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtdenominacion.value=deno;
	opener.document.form1.txtvalor.value=valor;
	opener.document.form1.txtcodcli.value=codcli;
	opener.document.form1.txtcodprod.value=codprod;
	opener.document.form1.txtpunent.value=punent;
	opener.document.form1.cmbmet.value=metodo;
	opener.document.form1.txtvalordesc.value=valdes;	
	opener.document.form1.btnunidad.disabled=false;
	opener.document.form1.existe.value="TRUE";
	close();
}

function aceptarnomina(codigo)
{
	opener.document.form1.txtctmetnom.value=codigo;
	opener.document.form1.txtctmetnom.readOnly=true;
	close();
}

function aceptargendisk(codigo,deno,metodo,codcli,codprod,punent)
{
	opener.document.form1.txtctmetnom.value=codigo;
	opener.document.form1.txtctmetnom.readOnly=true;
	opener.document.form1.txtdencestic.value=deno;
	opener.document.form1.txtctmetnom.readOnly=true;
	opener.document.form1.txtmetodo.value=metodo;
	opener.document.form1.txtcodcli.value=codcli;
	opener.document.form1.txtcodprod.value=codprod;
	opener.document.form1.txtpunent.value=punent;
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
	f.action="sigesp_snorh_cat_ct.php";
	f.submit();
}
</script>
</html>