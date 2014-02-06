<?php
session_start();
$ls_codestpro1=$_GET["codestpro1"];
$ls_codestpro2=$_GET["codestpro2"];
$ls_codestpro3=$_GET["codestpro3"];
$ls_codestpro4=$_GET["codestpro4"];
$ls_codestpro5=$_GET["codestpro5"];
$ls_estcla=$_GET["estcla"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Fuentes de Financiamiento</title>
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
	 <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1;?>">
	  <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2;?>">
	  <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3;?>">
	  <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4;?>">
	  <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5;?>">
	  <input name="hidestcla"     type="hidden" id="hidestcla"     value="<?php print $ls_estcla ?>">
   
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Fuentes de Financiamiento</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="141"><div align="right">C&oacute;digo Fuente Financiamiento</div></td>
        <td width="357" height="22"><div align="left">
          <input name="txtcodfuefin" type="text" id="txtcodfuefin">
        </div></td>
      </tr>
      
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
$ds=new class_datastore();
$io_sql=new class_sql($con);


$la_empresa		  = $_SESSION["la_empresa"];
$li_estmodest     = $la_empresa["estmodest"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];
$li_size1         = $li_loncodestpro1+10;


if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codfuefin="%".$_POST["txtcodfuefin"]."%";
	
}
else
{
	$ls_operacion="";

}
print "<table width=720 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width=100 height=21 style=text-align:center>Fuente de Financiamiento</td>";
print "<td width=160 height=21 align:center style='cursor:pointer' onClick=ue_orden('spg_dt_unidadadministrativa.codestpro1')>".$la_empresa["nomestpro1"]."</td>";
print "<td width=160 height=21 align:center style='cursor:pointer' onClick=ue_orden('spg_dt_unidadadministrativa.codestpro2')>".$la_empresa["nomestpro2"]."</td>";
print "<td width=160 height=21 align:center style='cursor:pointer' onClick=ue_orden('spg_dt_unidadadministrativa.codestpro3')>".$la_empresa["nomestpro3"]."</td>";
if ($li_estmodest==2)
   {
     print "<td width=160 height=21 style=text-align:center onClick=ue_orden('spg_dt_unidadadministrativa.codestpro4')>".$la_empresa["nomestpro4"]."</td>"; 
     print "<td width=160 height=21 style=text-align:center onClick=ue_orden('spg_dt_unidadadministrativa.codestpro5')>".$la_empresa["nomestpro5"]."</td>";
   }
print "<td width=40 height=21 style=text-align:center>Tipo</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	
	$ls_codestpro1=$_POST["txtcodestpro1"];
	$ls_codestpro2=$_POST["txtcodestpro2"];
	$ls_codestpro3=$_POST["txtcodestpro3"];
	$ls_codestpro4=$_POST["txtcodestpro4"];
	$ls_codestpro5=$_POST["txtcodestpro5"];
	$ls_estcla=$_POST["hidestcla"];
	$ls_sql=" SELECT * ".
			" FROM  spg_dt_fuentefinanciamiento ".
			" WHERE codfuefin LIKE '".$ls_codfuefin."'".
			" AND codestpro1 = '".$ls_codestpro1."'  ".
			" AND codestpro2 = '".$ls_codestpro2."'  ".
			" AND codestpro3 = '".$ls_codestpro3."'  ".
			" AND codestpro4 = '".$ls_codestpro4."'  ".
			" AND codestpro5 = '".$ls_codestpro5."'  ".
			" AND estcla = '".$ls_estcla."'  ".		
			" ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,codfuefin  ";
			
     $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
	      $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	    }
	 else
	    {
	      $li_numrows = $io_sql->num_rows($rs_data);
	      if ($li_numrows>0)
		     {
			   while ($row=$io_sql->fetch_row($rs_data))
			         {
					   print "<tr class=celdas-blancas>";
					   $ls_codfuefin  = $row["codfuefin"];					   
					   $ls_codestpro1 = $row["codestpro1"];
					   $ls_codestpro2 = $row["codestpro2"];
					   $ls_codestpro3 = $row["codestpro3"];
					   $ls_codestpro4 = $row["codestpro4"];
					   $ls_codestpro5 = $row["codestpro5"];					   
					   $ls_estcla     = $row["estcla"];		  
					  
					   if ($ls_estcla=='P')
					      {
						    $ls_denestcla='Proyecto';
						  }
					   elseif($ls_estcla=='A')
					      {
						    $ls_denestcla='Acción';
						  }
					   if ($li_estmodest==2)
					      {
					        $ls_denestcla = $_SESSION["la_empresa"]["nomestpro1"];
						  }
					  print "<td width=40 style=text-align:center><a href=\"javascript:aceptar('$ls_codfuefin');\">".$ls_codfuefin."</a></td>";
					  print "<td width=160 style=text-align:center><a href=\"javascript:aceptar('$ls_codfuefin');\">".trim(substr($ls_codestpro1,-$li_loncodestpro1))."</a></td>";
					   print "<td width=160 style=text-align:center><a href=\"javascript:aceptar('$ls_codfuefin');\">".trim(substr($ls_codestpro2,-$li_loncodestpro2))."</a></td>";
					   print "<td width=160 style=text-align:center><a href=\"javascript:aceptar('$ls_codfuefin');\">".trim(substr($ls_codestpro3,-$li_loncodestpro3))."</a></td>";
					   if ($li_estmodest==2)
					   {
						print "<td width=160 style=text-align:center ><a href=\"javascript:aceptar('$ls_codfuefin');\">".trim(substr($ls_codestpro4,-$li_loncodestpro4))."</a></td>";					        			
						print "<td width=160 style=text-align:center ><a href=\"javascript:aceptar('$ls_codfuefin');\">".trim(substr($ls_codestpro5,-$li_loncodestpro5))."</a></td>";
					   }
					   print "<td width=40 style=text-align:center>".$ls_denestcla."</td>";
					   
			           print "</tr>";
					 }
			 } 
	      else
		     {
			   $io_msg->message("No se encontraron registros !!!");
			 }
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
  function aceptar(ls_codfuefin)
  {
	opener.document.form1.txtcodfuefin.value=ls_codfuefin;
	close();
  }

 

  function ue_search()
  {
	f=document.form1;
	ls_codestpro1=f.txtcodestpro1.value;
	ls_codestpro2=f.txtcodestpro2.value;
	ls_codestpro3=f.txtcodestpro3.value;
	ls_codestpro4=f.txtcodestpro4.value;
	ls_codestpro5=f.txtcodestpro5.value;
	ls_estcla=f.hidestcla.value;
	f.operacion.value="BUSCAR";
	f.action="sigesp_scv_cat_fuentefinanciamiento.php?codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla+"";
	f.submit();
  }
</script>
</html>
