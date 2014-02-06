<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_mensajes.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$ls_loncodestpro1)+1;
$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$ls_loncodestpro2)+1;
$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$ls_loncodestpro3)+1;
$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_longestpro4= (25-$ls_loncodestpro4)+1;
$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
$li_longestpro5= (25-$ls_loncodestpro5)+1;
$dat=$_SESSION["la_empresa"];
$msg = new class_mensajes();
$fun = new class_funciones();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];
if (array_key_exists("operacion",$_POST))		
   {
     $ls_operacion=$_POST["operacion"];  
   }
else
   {
     $ls_operacion="BUSCAR";
   }
if (array_key_exists("ctascedrec",$_GET))		
   {
     $ls_ctascedrec=$_GET["ctascedrec"];  
   }
else
   {
     $ls_ctascedrec="";
   } 
if (array_key_exists("tipo",$_GET))		
   {
     $ls_tipo=$_GET["tipo"];  
   }
else
   {
     $ls_tipo="";
   }     
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias Cedentes y Receptoras</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestarias Cedentes y Receptoras </td>
    </tr>
  </table>
  <div align="center">
    <br>
    <?php
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Partida</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
  {
	$ls_sql_filtro = "";
	$ls_valrectif  = "";
	if($ls_tipo == "C")
    {
	$ls_valrectif = "";
    }
	elseif($ls_tipo == "R")
	{
	 $ls_valrectif = " AND substr(sig_cuenta,1,3) <> '498' ";
	}
	
	if(!empty($ls_ctascedrec))
	{
	 $ls_ctascedrec = str_replace(",","",$ls_ctascedrec);
	 $arr_ctas = str_split($ls_ctascedrec,3);
	 $ls_sql_filtro = "AND sig_cuenta not in ";
	 $li_total = count($arr_ctas);
	 for($i=0;$i<$li_total;$i++)
	 {
	  if ($i ==0)
	  {
	   $ls_sql_filtro = $ls_sql_filtro."('".$arr_ctas[$i]."000000'";
	  }
	  else
	  {
	   $ls_sql_filtro = $ls_sql_filtro.",'".$arr_ctas[$i]."000000'";
	  }
	 }
	  $ls_sql_filtro =  $ls_sql_filtro.")";
	}
	$ls_cadena =" SELECT  substr(sig_cuenta,1,3) as cuenta, denominacion  ".
			    "    FROM sigesp_plan_unico_re ".
		        "   WHERE  sig_cuenta in ('401000000','402000000','403000000','404000000',".
				"                         '405000000','406000000','407000000','408000000',".
				"                         '409000000','410000000','411000000','412000000','498000000') ".
				$ls_sql_filtro.$ls_valrectif.
				" GROUP BY sig_cuenta,denominacion   ".
				" ORDER BY sig_cuenta";						
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta === false)
	{
	 $msg->message("Error en Carga de Partidas ERROR->".$fun->uf_convertirmsg($SQL->message));
	}
	else
	{
    	while(!$rs_cta->EOF)
		{
			$cuenta=$rs_cta->fields["cuenta"];
			$denominacion=$rs_cta->fields["denominacion"];
		    print "<tr class=celdas-blancas>";
		  	print "<td><a href=\"javascript: aceptar('$cuenta');\">".$cuenta."</a></td>";
		    print "<td  align=left>".$denominacion."</td>";
			print "</tr>";
			$rs_cta->MoveNext();			
		}
		$SQL->free_result($rs_cta);
   }		
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta)
  {
    fop        = opener.document.form1;
	cedentes   = fop.hidctaced.value;
	receptoras = fop.hidctarec.value;
	ls_ctasreceptoras = fop.txtctarec.value;
	ls_ctascedentes   = fop.txtctaced.value;
	if (cedentes=='1')
	   {
	     ls_string = "";
		 if(ls_ctascedentes != "")
		 {
		  ls_string = (ls_ctascedentes +","+cuenta);
		 }
		 else
		 {
		  ls_string = (ls_ctascedentes+cuenta);
		 }
		 fop.txtctaced.value = ls_string;
		 fop.hidctaced.value = '0';
		 close();
	   }
	   
   if (receptoras == '1')
	  {
	     ls_string = "";
		 if(ls_ctasreceptoras != "")
		 {
		  ls_string = (ls_ctasreceptoras +","+cuenta);
		 }
		 else
		 {
		  ls_string = (ls_ctasreceptoras+cuenta);
		 }
		 
		 fop.txtctarec.value = ls_string;
		 fop.hidctarec.value = '0';
		 close();
	   }	

  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cfg_cat_ctascedrec.php";
	  f.submit();
  }
</script>
</html>