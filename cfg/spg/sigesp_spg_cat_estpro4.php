<?php
session_start();
$la_empresa=$_SESSION["la_empresa"];
$li_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
$li_longestpro1= (25-$li_loncodestpro1)+1;
$li_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
$li_longestpro2= (25-$li_loncodestpro2)+1;
$li_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
$li_longestpro3= (25-$li_loncodestpro3)+1;
$li_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
$li_longestpro4= (25-$li_loncodestpro4)+1;
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
$in           = new sigesp_include();
$con          = $in->uf_conectar();
$io_msg       = new class_mensajes();
$ds           = new class_datastore();
$io_sql       = new class_sql($con);
$io_funcion   = new class_funciones();
$ls_codemp    = $la_empresa["codemp"];
$li_estmodest = $la_empresa["estmodest"];


if ($li_estmodest=='1')
   {
	 $li_maxlength_1 = '20';
	 $li_maxlength_2 = '6';
	 $li_maxlength_3 = '3';
	 $li_size        = '25';
	 $li_ancho       = '70';
   }
else
   {
	 $li_maxlength_1 = '2';
	 $li_maxlength_2 = '2';
	 $li_maxlength_3 = '2';
	 $li_size        = '5';
	 $li_ancho       = '90';
   }

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
	 $ls_codestpro3 = $_POST["txtcodestpro3"];
	 $ls_denestpro3 = $_POST["txtdenestpro3"];
	 $ls_codestpro4 = $_POST["txtcodestpro4"];
	 $ls_denestpro4 = $_POST["txtdenestpro4"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["txtcodestpro1"];
	 $ls_denestpro1 = $_GET["txtdenestpro1"];
	 $ls_codestpro2 = $_GET["txtcodestpro2"];
	 $ls_denestpro2 = $_GET["txtdenestpro2"];
	 $ls_codestpro3 = $_GET["txtcodestpro3"];
	 $ls_denestpro3 = $_GET["txtdenestpro3"];
	 $ls_codestpro4 = "";
	 $ls_denestpro4 = "";
   }
   $ls_estcla4 = $_GET["txtclasificacion"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 4 <?php print $la_empresa["nomestpro4"] ?></title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cfg.js"></script>
</head>
<body>
<form name="formulario" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $la_empresa["nomestpro4"] ?>
        <input name="campoorden" type="hidden" id="campoorden" value="spg_ep4.codestpro4">
        <input name="orden" type="hidden" id="orden" value="ASC"></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right"><?php print $la_empresa["nomestpro1"]?></div></td>
        <td width="432" height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_loncodestpro1+2 ?>" maxlength="<?php print $li_loncodestpro1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1 ?>" size="<?php print $li_ancho ?>" maxlength="70" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print  $ls_codestpro2 ?>" size="<?php print $li_loncodestpro2+2 ?>" maxlength="<?php print $li_loncodestpro2 ?>" readonly style="text-align:center">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" value="<?php print $ls_denestpro2 ?>" size="<?php print $li_ancho ?>" class="sin-borde" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro3"]?></div></td>
        <td height="22"><label>
          <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print  $ls_codestpro3 ?>" size="<?php print $li_loncodestpro3+2 ?>" maxlength="<?php print $li_loncodestpro3 ?>" style="text-align:center">
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" style="text-align:left" value="<?php print $ls_denestpro3 ?>" size="<?php print $li_ancho ?>" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td height="22"><input name="txtcodestpro4" type="text" id="txtcodestpro4"  size="<?php print $li_loncodestpro4+2 ?>" maxlength="<?php print $li_loncodestpro4?>" style="text-align:center" si></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro4" type="text" id="txtdenestpro4"  size="72" maxlength="100" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
      <tr>
        <td height="13" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2" style="text-align:center"><div id="detcodestpro4"></div></td>
      </tr>
	</table>
     <p align="center">
<?php
if (array_key_exists("opener",$_GET))
   {
     $ls_opener = $_GET["opener"];
     if ($ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
	    {
		  $ls_operacion = "CODESTPRO4";		  
		}   
   }
elseif ($ls_operacion=="BUSCAR")
   {
	 echo "<table width=700 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 echo "<tr class=titulo-celda>";
	 echo "<td  width=250>".$la_empresa["nomestpro1"]."</td>";
	 echo "<td  width=150>".$la_empresa["nomestpro2"]."</td>";
	 echo "<td  width=50>".$la_empresa["nomestpro3"]."</td>";
	 echo "<td  width=50>C&oacute;digo </td>";
	 echo "<td  width=250>Denominaci&oacute;n</td>";
	 echo "</tr>"; 
	 
	 if (!empty($ls_codestpro1) && !empty($ls_codestpro2) && !empty($ls_codestpro3))
	    {
		  $ls_codestpro1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
  	      $ls_codestpro2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
		  $ls_codestpro3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,25);
		}
	 $ls_sql = " SELECT SUBSTR(codestpro1,".$li_longestpro1.",25) as codestpro1,
	                    SUBSTR(codestpro2,".$li_longestpro2.",25) as codestpro2,
			            SUBSTR(codestpro3,".$li_longestpro3.",25) as codestpro3,
			            SUBSTR(codestpro4,".$li_longestpro4.",25) as codestpro4,
			            denestpro4,estcla
	               FROM spg_ep4
			      WHERE codemp = '".$ls_codemp."'
				    AND codestpro1 ='".$ls_codestpro1."'
					AND estcla='".$ls_estcla4."'
					AND codestpro2 = '".$ls_codestpro2."'
					AND codestpro3 = '".$ls_codestpro3."'
					AND codestpro4 LIKE '%".$ls_codestpro4."%' 
					AND denestpro4 LIKE '%".$ls_denestpro4."%'";
			   
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
					  echo "<tr class=celdas-blancas>";
					  $ls_estcla     = $row["estcla"];
					  $ls_codestpro1 = $row["codestpro1"];
					  $ls_codestpro2 = $row["codestpro2"];
					  $ls_codestpro3 = $row["codestpro3"];
					  $ls_codestpro4 = $row["codestpro4"];
					  $ls_denestpro4 = $row["denestpro4"];
					  echo "<td width=250 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro4','$ls_denestpro4','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
					  echo "<td width=150 style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro4','$ls_denestpro4','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
					  echo "<td width=50  style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro4','$ls_denestpro4','$ls_estcla');\">".trim($ls_codestpro3)."</td>";
					  echo "<td width=50  style=text-align:center><a href=\"javascript: aceptar('$ls_codestpro4','$ls_denestpro4','$ls_estcla');\">".trim($ls_codestpro4)."</a></td>";
					  echo "<td width=250 style=text-align:left>".ltrim($ls_denestpro4)."</td>";
					  echo "</tr>";			
					}
			}
	     else
		    {
			  $io_msg->message("No se han definido ".$la_empresa["nomestpro4"]);			
			}
	   }
   }
print "</table>";
?>
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f 	= document.formulario;
fop = opener.document.formulario;

function aceptar(ls_codestpro4,ls_denestpro4,ls_estcla)
{
	ls_maestro = fop.hidmaestro.value; 
	fop.txtcodestpro4.value = ls_codestpro4;
	fop.txtdenestpro4.value = ls_denestpro4;
	
	switch(ls_maestro)
	{
		case 'Y':
		 fop.opeestpro4.value  = "BUSCAR";
	     fop.statusprog4.value = 'C';
		 fop.txtclasificacion.value = ls_estcla;
		 fop.txtcodestpro4.readOnly = true;
		 fop.txtdenestpro4.focus();
		
		break;

		case 'N':
    	fop.txtcodestpro5.value    = "";
    	fop.txtdenestpro5.value    = "";
    	fop.txtestcla5.value       = "";
    	for( i=1;i<=50;i++)
    	{
    		eval("fop.txtcuentaspg"+i+".value=''");
			eval("fop.txtdencuenta"+i+".value=''");
			eval("fop.txtcuentascg"+i+".value=''");	
    	}
		break;
	}	
	close();
}

function ue_search()
{
  ls_opener = fop.id;
  if (ls_opener!='sigesp_spg_d_codestpro_codfuefin.php')
	 {
	   f.operacion.value = "BUSCAR";
	   f.action          = "sigesp_spg_cat_estpro4.php";
	   f.submit();
     }
  else
     {
	   uf_print_codestpro4();
	 }
}

function uf_print_codestpro4()
{
  ls_estcla     = fop.hidestcla.value;
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;
  ls_codestpro4 = f.txtcodestpro4.value;
  ls_denestpro1 = f.txtdenestpro1.value;
  ls_denestpro2 = f.txtdenestpro2.value;
  ls_denestpro3 = f.txtdenestpro3.value;
  ls_denestpro4 = f.txtdenestpro4.value;
  orden         = f.orden.value;
  campoorden    = f.campoorden.value;
  
  divgrid = document.getElementById("detcodestpro4");
  ajax    = objetoAjax();
  ajax.open("POST","../class_folder/sigesp_cfg_c_catalogo_ajax.php",true);
  ajax.onreadystatechange=function() {
  if (ajax.readyState==1)
	 {
	   divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
	 }
  else if (ajax.readyState==4) {
	   divgrid.innerHTML = ajax.responseText
	 }
  }
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  ajax.send("catalogo=CODESTPRO4&campoorden="+campoorden+"&orden="+orden+"&codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&denestpro3="+ls_denestpro3+"&estcla="+ls_estcla+"&denestpro1="+ls_denestpro1+"&denestpro2="+ls_denestpro2+"&codestpro4="+ls_codestpro4+"&denestpro4="+ls_denestpro4);
}

function uf_aceptar_codestpro4(as_codestpro4,as_denestpro4)
{
  fop.txtcodestpro4.value = as_codestpro4;
  fop.txtdenestpro4.value = as_denestpro4;	 
  fop.txtcodestpro5.value = "";
  fop.txtdenestpro5.value = "";
  close();
}
</script>
<?php
if ($ls_operacion=="CODESTPRO4")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_print_codestpro4();";
	 echo "</script>";
   }
?>
</html>