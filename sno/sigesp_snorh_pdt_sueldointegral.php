<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	$ls_codper=$_GET["codper"];
	$ls_anocurper=$_GET["anocurper"];
	$ls_mescurper=$_GET["mescurpe"];
	$ls_sueint=$_GET["sueint"];
	$ls_sueint=strtoupper($ls_sueint);

   //--------------------------------------------------------------
   function uf_print($ls_codper, $ls_anocurper, $ls_mescurper,$ls_sueint)
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
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
		print "<td width=60>Código Nómina</td>";
		print "<td width=60>Código Concepto</td>";
		print "<td width=300>Descripción</td>";
		print "<td width=80>Monto</td>";
		print "</tr>";
		$ls_sql="SELECT sno_hsalida.codnom, sno_hsalida.codconc,MAX(sno_hconcepto.nomcon) AS nomcon, SUM(sno_hsalida.valsal) AS valsal". 
				"	FROM sno_hsalida ".
				"	INNER JOIN sno_hconcepto ".
   				"	 ON sno_hsalida.codemp = sno_hconcepto.codemp ".
  				"	 AND sno_hsalida.codnom = sno_hconcepto.codnom ".
  				"	 AND sno_hsalida.anocur = sno_hconcepto.anocur ".
  				"	 AND sno_hsalida.codperi = sno_hconcepto.codperi ".
  				"	 AND sno_hsalida.codconc = sno_hconcepto.codconc ".
				"	INNER JOIN sno_hperiodo ".
   				"	 ON sno_hsalida.codemp = sno_hperiodo.codemp ".
  				"	 AND sno_hsalida.codnom = sno_hperiodo.codnom ".
  				"	 AND sno_hsalida.anocur = sno_hperiodo.anocur ".
  				"	 AND sno_hsalida.codperi = sno_hperiodo.codperi ".
				"	INNER JOIN sno_hnomina ".
   				"	 ON sno_hsalida.codemp = sno_hnomina.codemp ".
  				"	 AND sno_hsalida.codnom = sno_hnomina.codnom ".
  				"	 AND sno_hsalida.anocur = sno_hnomina.anocurnom ".
  				"	 AND sno_hsalida.codperi = sno_hnomina.peractnom ".
				"	WHERE sno_hsalida.codemp='".$ls_codemp."' ". 
  				"	 AND sno_hsalida.codper= '".$ls_codper."' ". 
  				"	 AND sno_hsalida.anocur = '".$ls_anocurper."' ". 
  				"	 AND substr(sno_hperiodo.fecdesper,6,2) = '".str_pad($ls_mescurper,2,"0",0)."' ". 
  				"	 AND substr(sno_hperiodo.fecdesper,1,4) = '".$ls_anocurper."' ". 
  				"	 AND sno_hnomina.espnom = '0' ".
  				"	 AND sno_hconcepto.sueintcon = 1 ". 
				"	GROUP BY sno_hsalida.codnom,sno_hsalida.codconc ".
				"	ORDER BY sno_hsalida.codnom,sno_hsalida.codconc ";
		
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			$li_sueldo=0;
			
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_codnom=$row["codnom"];
				$ls_codconc=$row["codconc"];
				$ls_nomcon= strtoupper($row["nomcon"]);
				$li_monto = number_format ($row["valsal"],2,",",".");
				$li_sueldo=$li_sueldo + $row["valsal"];			
				print "<tr class=celdas-blancas>";
				print "<td align='center'>".$ls_codnom."</td>";
				print "<td>".$ls_codconc."</td>";
				print "<td>".$ls_nomcon."</td>";
				print "<td align='right'>".$li_monto."</td>";
				print "</tr>";			
			}
			print "<tr class=celdas-blancas>";
			print "<td></td>";
			print "<td></td>";
			print "<td align='right'>".$ls_sueint."</td>";
			print "<td align='right'>".number_format($li_sueldo,2,",",".")."</td>";
			print "</tr>";	
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
<title><?php print $ls_sueint." POR PERSONAL";?></title>
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
      <td width="496" height="20" colspan="2" class="titulo-ventana"><?php print $ls_sueint?></td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="139" height="22"><div align="right">C&oacute;digo Personal</div></td>
        <td width="355"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" size="16" style="text-align:center" value="<?php print ($ls_codper);?>" readonly>        
        </div></td>
      </tr>      
  </table>
  <?php
  	 uf_print($ls_codper, $ls_anocurper, $ls_mescurper,$ls_sueint);   
  ?>
  <br>

</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

</html>
