<?php
session_start();
$la_empresa=$_SESSION["la_empresa"];
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
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sql.php");

$in         = new sigesp_include();
$con        = $in->uf_conectar();
$io_msg     = new class_mensajes();
$ds         = new class_datastore();
$io_sql     = new class_sql($con);
$io_funcion = new class_funciones();

$ls_codemp    = $la_empresa["codemp"];
$li_estmodest = $la_empresa["estmodest"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion  = $_POST["operacion"];
	$ls_codestpro1 = $_POST["codigo"];
	$ls_denestpro1 = $_POST["txtdenestpro1"];
	$ls_codestpro2 = $_POST["txtcodestpro2"];
	$ls_denestpro2 = $_POST["txtdenestpro2"];
	$ls_codestpro3 = $_POST["txtcodestpro3"];
	$ls_denestpro3 = $_POST["txtdenestpro3"];
	$ls_codestpro4 = $_POST["txtcodestpro4"];
	$ls_denestpro4 = $_POST["txtdenestpro4"];
	$ls_codestpro5 = $_POST["txtcodestpro5"];
	$ls_denestpro5 = $_POST["txtdenestpro5"];
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
	$ls_codestpro4 = $_GET["txtcodestpro4"];
	$ls_denestpro4 = $_GET["txtdenestpro4"];
	$ls_codestpro5 = "";
	$ls_denestpro5 = "";
}
$ls_estcla5 = $_GET["txtclasificacion"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 5 <?php print $la_empresa["nomestpro5"] ?></title>
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
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion ?>">
        Cat&aacute;logo <?php print $la_empresa["nomestpro5"] ?>
        <input name="campoorden" type="hidden" id="campoorden" value="spg_ep5.codestpro5">
        <input name="orden" type="hidden" id="orden" value="ASC"></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right"><?php print $la_empresa["nomestpro1"]?></div></td>
        <td width="432" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" value="<?php print $ls_codestpro1 ?>" size="<?php print $ls_loncodestpro1+2 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1 ?>" size="70" maxlength="70" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro2"]?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print  $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2+2 ?>" maxlength="<?php print $ls_loncodestpro2 ?>" readonly style="text-align:center">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" value="<?php print $ls_denestpro2 ?>" size="70" class="sin-borde" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro3"]?></div></td>
        <td height="22"><label>
          <input name="txtcodestpro3" type="text" id="txtcodestpro3" value="<?php print  $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3+2 ?>" maxlength="<?php print $ls_loncodestpro3 ?>" style="text-align:center">
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" style="text-align:left" value="<?php print $ls_denestpro3 ?>" size="70" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $la_empresa["nomestpro4"]?></div></td>
        <td height="22"><label>
          <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4+2 ?>" maxlength="<?php print $ls_loncodestpro4 ?>" style="text-align:center">
          <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" value="<?php print $ls_denestpro4 ?>" size="70" readonly>
        </label></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td height="22"><input name="txtcodestpro5" type="text" id="txtcodestpro5"  size="<?php print $ls_loncodestpro5 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro5" type="text" id="txtdenestpro5"  size="72" maxlength="100" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><input name="txtpantalla" type="hidden" id="textpantalla"  size="18" maxlength="100" style="text-align:left" value="<?=$ls_pantalla?>"></td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
	  <tr>
        <td height="13" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2" style="text-align:center"><div id="detcodestpro5"></div></td>
      </tr>  
    </table>
   <p align="center">
<?php
if (array_key_exists("opener",$_GET))
   {
     $ls_opener = $_GET["opener"];
     if ($ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
	    {
		  $ls_operacion = "CODESTPRO5";		  
		}
   }
elseif ($ls_operacion=="BUSCAR")
   {
	 echo "<table width=750 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 echo "<tr class=titulo-celda>";
	 echo "<td  width=250>".$la_empresa["nomestpro1"]."</td>";
	 echo "<td  width=150>".$la_empresa["nomestpro2"]."</td>";
	 echo "<td  width=50>".$la_empresa["nomestpro3"]."</td>";
	 echo "<td  width=50>".$la_empresa["nomestpro4"]."</td>";
	 echo "<td  width=50>C&oacute;digo </td>";
	 echo "<td  width=250>Denominaci&oacute;n</td>";
	 echo "</tr>";
     $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
	 $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
	 $ls_codestpro3 = str_pad($ls_codestpro3,25,0,0);
	 $ls_codestpro4 = str_pad($ls_codestpro4,25,0,0);
	 $ls_codestpro5 = str_pad($ls_codestpro5,25,0,0);
	 if (array_key_exists('session_activa',$_SESSION))
     { 	 
		$ls_sql="  SELECT SUBSTR(codestpro1,".$li_longestpro1.",25) as codestpro1,".
				"         SUBSTR(codestpro2,".$li_longestpro2.",25) as codestpro2,".
				"         SUBSTR(codestpro3,".$li_longestpro3.",25) as codestpro3,".
				"         SUBSTR(codestpro4,".$li_longestpro4.",25) as codestpro4,".
				"         SUBSTR(codestpro5,".$li_longestpro5.",25) as codestpro5,".
				"         ep5.denestpro5,ep5.estcla,'' as codfuefin, '' as denfuefin".
				" FROM spg_ep5 as ep5".
				" WHERE ep5.codemp='".$ls_codemp."'  AND  ep5.codestpro1 like '%".$ls_codestpro1."%' AND estcla='".$ls_estcla5."' ".
				" AND ep5.codestpro2 like '%".$ls_codestpro2."%' AND  ep5.codestpro3 like '%".$ls_codestpro3."%'      ".
				" AND ep5.codestpro4 like '%".$ls_codestpro4."%' ".
				" AND ep5.denestpro5 like '%".$ls_denestpro5."%'";
	}
	else
	{
		$ls_sql="  SELECT  SUBSTR(codestpro1,".$li_longestpro1.",25) as codestpro1,".
				"         SUBSTR(codestpro2,".$li_longestpro2.",25) as codestpro2,".
				"         SUBSTR(codestpro3,".$li_longestpro3.",25) as codestpro3,".
				"         SUBSTR(codestpro4,".$li_longestpro4.",25) as codestpro4,".
				"         SUBSTR(codestpro5,".$li_longestpro5.",25) as codestpro5,".
				"         ep5.denestpro5,ep5.estcla,ff.codfuefin,ff.denfuefin".
				" FROM spg_ep5 as ep5, sigesp_fuentefinanciamiento as ff ".
				" WHERE ep5.codfuefin= ff.codfuefin".
				" AND ep5.codemp='".$ls_codemp."'  AND  ep5.codestpro1 like '%".$ls_codestpro1."%' AND estcla='".$ls_estcla5."' ".
				" AND ep5.codestpro2 like '%".$ls_codestpro2."%' AND  ep5.codestpro3 like '%".$ls_codestpro3."%'      ".
				" AND ep5.codestpro4 like '%".$ls_codestpro4."%' ".
				" AND ep5.denestpro5 like '%".$ls_denestpro5."%'";
	}
	$rs_data = $io_sql->select($ls_sql);
	$data    = $rs_data;
	if($io_sql->num_rows($rs_data)==0)
	 {
	 	 $io_msg->message("No se han definido ".$_SESSION["la_empresa"]["nomestpro3"]);
	 }
	 else
	 {	
		while($row=$io_sql->fetch_row($rs_data))
		{
				print "<tr class=celdas-blancas>";
				$ls_codestpro1 = $row["codestpro1"];
				$ls_codestpro2 = $row["codestpro2"];
				$ls_codestpro3 = $row["codestpro3"];
				$ls_codestpro4 = $row["codestpro4"];
				$ls_codestpro5 = $row["codestpro5"];
				$ls_denestpro5 = $row["denestpro5"];
				$ls_codfuefin  = $row["codfuefin"];
				$ls_denfuefin  = $row["denfuefin"];
				$ls_estcla=$row["estcla"];
				if (array_key_exists('session_activa',$_SESSION))
				{	 
					print "<td width=250 align=\"center\"><a href=\"javascript: aceptar_v2('$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
					print "<td width=150 align=\"center\"><a href=\"javascript: aceptar_v2('$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
					print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar_v2('$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro3)."</td>";
					print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar_v2('$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro4)."</a></td>";
					print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar_v2('$ls_codestpro5','$ls_denestpro5','$ls_estcla');\">".trim($ls_codestpro5)."</a></td>";
					print "<td width=250 align=\"left\">".trim($ls_denestpro5)."</td>";
					print "</tr>";
				}
				else
				{
					print "<td width=250 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
					print "<td width=150 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin','$ls_estcla');\">".trim($ls_codestpro2)."</td>";
					print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin','$ls_estcla');\">".trim($ls_codestpro3)."</td>";
					print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin','$ls_estcla');\">".trim($ls_codestpro4)."</a></td>";
					print "<td width=50 align=\"center\"><a href=\"javascript:  aceptar('$ls_codestpro5','$ls_denestpro5','$ls_codfuefin','$ls_denfuefin','$ls_estcla');\">".trim($ls_codestpro5)."</a></td>";
					print "<td width=250 align=\"left\">".trim($ls_denestpro5)."</td>";
					print "</tr>";
				}
			
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

function aceptar(ls_codestpro5,ls_denestpro5,ls_codfuefin,ls_denfuefin,ls_estcla,ls_pantalla)
{
	fop.txtcodestpro5.value    = ls_codestpro5;
	fop.txtcodestpro5.readOnly = true;
	fop.txtdenestpro5.value    = ls_denestpro5; 
	ls_opener                  = opener.document.formulario.id;
	if (ls_opener!='sigesp_cfg_d_consolidacion.php')
	   {
		 ls_maestro                 = fop.hidmaestro.value; 
		 ls_pantalla                = f.txtpantalla.value
		 switch(ls_maestro)
		 {
			case 'Y':
				fop.opeestpro5.value  = "BUSCAR";
				fop.statusprog5.value = 'C';
				fop.txtcodigo.value     = ls_codfuefin;
				fop.txtcodigo.readOnly  = true;
				fop.txtdenominacion.value    = ls_denfuefin;
				fop.txtclasificacion.value = ls_estcla;
				fop.txtdenestpro5.focus(); 
			break;
	
			case 'N':
				fop.txtestcla5.value     = ls_estcla;
			break;
		 }
       }
	close();
}
 
function aceptar_v2(ls_codestpro5,ls_denestpro5,ls_estcla,ls_pantalla)
{
	fop.txtcodestpro5.value    = ls_codestpro5;
	fop.txtcodestpro5.readOnly = true;
	fop.txtdenestpro5.value    = ls_denestpro5; 
    ls_maestro                 = fop.hidmaestro.value; 
	ls_pantalla                = f.txtpantalla.value
	switch(ls_maestro)
	{
		case 'Y':
			fop.opeestpro5.value  = "BUSCAR";
			fop.statusprog5.value = 'C';
			fop.txtclasificacion.value = ls_estcla;
			fop.txtdenestpro5.focus(); 
		break;

		case 'N':
			fop.txtestcla5.value     = ls_estcla;
		break;
	}
	close();
}

function ue_search()
{
  ls_opener = fop.id;
  if (ls_opener!='sigesp_spg_d_codestpro_codfuefin.php' && ls_opener!='sigesp_cfg_d_consolidacion.php')
	 {
	   f.operacion.value="BUSCAR";
	   f.action="sigesp_spg_cat_estpro5.php";
	   f.submit();
     }
  else
     {
	   uf_print_codestpro5();
	 }
}

function uf_print_codestpro5()
{
  ls_estcla     = fop.hidestcla.value;
  ls_codestpro1 = f.codigo.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;
  ls_codestpro4 = f.txtcodestpro4.value;
  ls_codestpro5 = f.txtcodestpro5.value;
  ls_denestpro1 = f.txtdenestpro1.value;
  ls_denestpro2 = f.txtdenestpro2.value;
  ls_denestpro3 = f.txtdenestpro3.value;
  ls_denestpro4 = f.txtdenestpro4.value;
  ls_denestpro5 = f.txtdenestpro5.value;
  orden         = f.orden.value;
  campoorden    = f.campoorden.value;
  
  divgrid = document.getElementById("detcodestpro5");
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
  ajax.send("catalogo=CODESTPRO5&campoorden="+campoorden+"&orden="+orden+"&codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&denestpro3="+ls_denestpro3+"&estcla="+ls_estcla+"&denestpro1="+ls_denestpro1+"&denestpro2="+ls_denestpro2+"&codestpro4="+ls_codestpro4+"&denestpro4="+ls_denestpro4+"&codestpro5="+ls_codestpro5+"&denestpro5="+ls_denestpro5);
}

function uf_aceptar_codestpro5(as_codestpro1,as_denestpro1,as_codestpro2,as_denestpro2,as_codestpro3,as_denestpro3,as_codestpro4,as_denestpro4,as_codestpro5,as_denestpro5,as_estcla)
{
  ls_opener     = fop.id;
  ls_codestpro1 = fop.txtcodestpro1.value;
  ls_codestpro2 = fop.txtcodestpro2.value;
  ls_codestpro3 = fop.txtcodestpro3.value;
  ls_codestpro4 = fop.txtcodestpro4.value;
  li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  if (ls_codestpro1=='' && ls_codestpro2=='' && ls_codestpro3=='' && ls_codestpro4=='')
     {
	   fop.hidestcla.value     = as_estcla;
	   fop.txtcodestpro1.value = as_codestpro1;
	   fop.txtdenestpro1.value = as_denestpro1;
	   fop.txtcodestpro2.value = as_codestpro2;
	   fop.txtdenestpro2.value = as_denestpro2;
	   fop.txtcodestpro3.value = as_codestpro3;
	   fop.txtdenestpro3.value = as_denestpro3;
	   fop.txtcodestpro4.value = as_codestpro4;
	   fop.txtdenestpro4.value = as_denestpro4;
	   fop.txtcodestpro5.value = as_codestpro5;
	   fop.txtdenestpro5.value = as_denestpro5;
	   if (ls_opener=='sigesp_cfg_d_consolidacion.php')
	      {
		    fop.operacion.value="CASTEST";
			close();
		  }
	 }
  else
     {
	   fop.txtcodestpro5.value = as_codestpro5;
	   fop.txtdenestpro5.value = as_denestpro5;
	   if (ls_opener=='sigesp_cfg_d_consolidacion.php')
		  {
		    close();
		  }
	 }
  if (ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
     {
	   uf_load_dt_fuentefinanciamiento();
	 }
}

function uf_load_dt_fuentefinanciamiento()
{
	ls_estcla     = fop.hidestcla.value;
	ls_codestpro1 = fop.txtcodestpro1.value;
	ls_codestpro2 = fop.txtcodestpro2.value;
	ls_codestpro3 = fop.txtcodestpro3.value;
    ls_codestpro4 = fop.txtcodestpro4.value;
	ls_codestpro5 = fop.txtcodestpro5.value;

	divgrid = opener.document.getElementById("detalles");
	ajax    = objetoAjax();
	ajax.open("POST","class_folder/sigesp_spg_c_codestpro_codfuefin_ajax.php",true);
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
	ajax.send("proceso=LOADDT&codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&codestpro4="+ls_codestpro4+"&codestpro5="+ls_codestpro5+"&estcla="+ls_estcla);
}
</script>
<?php
if ($ls_operacion=="CODESTPRO5")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_print_codestpro5();";
	 echo "</script>";
   }
?>
</html>