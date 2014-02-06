<?php
session_start();
$arr=$_SESSION["la_empresa"];
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];

$li_longcodestpro1 = (25-$li_loncodestpro1)+1;
$li_longcodestpro2 = (25-$li_loncodestpro2)+1;

require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones.php");

$in           = new sigesp_include();
$con          = $in->uf_conectar();
$io_msg       = new class_mensajes();
$io_sql       = new class_sql($con);
$ls_codemp    = $arr["codemp"];
$li_estmodest = $arr["estmodest"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codestpro1 = $_POST["txtcodestpro1"];
	 $ls_denestpro1 = $_POST["txtdenestpro1"];
	 $ls_codestpro2 = $_POST["txtcodestpro2"];
	 $ls_denestpro2 = $_POST["txtdenestpro2"];
   }
else
   {
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["txtcodestpro1"];
	 $ls_denestpro1 = $_GET["txtdenestpro1"];
	 $ls_estcla     = $_GET["txtclasificacion"]; 
	 $ls_codestpro2 = "";
	 $ls_denestpro2 = "";
   }   
if (array_key_exists("txtclasificacion",$_POST))
   {
     $ls_estcla = $_POST["txtclasificacion"]; 
   }   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 2 <?php print $arr["nomestpro2"] ?></title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <br>
	 <table width="550" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo <?php print $arr["nomestpro2"] ?>
        <input name="campoorden" type="hidden" id="campoorden" value="spg_ep2.codestpro2">
        <input name="orden" type="hidden" id="orden" value="ASC">
        <input name="txtclasificacion" type="hidden" id="txtclasificacion" value="<?php echo $ls_estcla; ?>"></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="99" height="22"><div align="right"><?php print $arr["nomestpro1"]?></div></td>
        <td width="449" height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_loncodestpro1+2 ?>" maxlength="<?php print $li_loncodestpro1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="55" value="<?php print $ls_denestpro1 ?>" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo</td>
        <td height="22" style="text-align:left"><input name="txtcodestpro2" type="text" id="txtcodestpro2" size="<?php print $li_loncodestpro2+2 ?>" maxlength="<?php print $li_loncodestpro2 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="txtdenestpro2" type="text" id="txtdenestpro2" size="72" maxlength="100" style="text-align:left"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
	  <tr>
        <td height="13" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>    </table>
  <div align="center">
    <p><br>
      <?php
if (array_key_exists("opener",$_GET))
   {
     $ls_opener = $_GET["opener"];
     if ($ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
	    {
		  $ls_operacion = "CODESTPRO2";
		}
   }
elseif ($ls_operacion=="BUSCAR")
   {
	 echo "<table width=550 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 echo "<tr class=titulo-celda>";
	 echo "<td>".$arr["nomestpro1"]."</td>";
	 echo "<td>Código</td>";
	 echo "<td>Denominación</td>";
	 echo "</tr>";

	 if (!empty($ls_codestpro1))
	    {
	      $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
		}
	 $ls_sql = "SELECT substr(codestpro1,".$li_longcodestpro1.",25) as codestpro1,
	                   substr(codestpro2,".$li_longcodestpro2.",25) as codestpro2,
					   denestpro2,spg_ep2.estcla as estcla
	              FROM spg_ep2
			     WHERE codemp='".$ls_codemp."'
			       AND codestpro1 ='".$ls_codestpro1."'
				   AND estcla='".$ls_estcla."'
				   AND codestpro2 like '%".$ls_codestpro2."%' 
				   AND denestpro2 like '%".$ls_denestpro2."%' ".
				   "AND codestpro1||codestpro2||estcla IN (SELECT SUBSTR(codintper,1,50)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' 
				  UNION  SELECT SUBSTR(codintper,1,50)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)";

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
					  $ls_codestpro1 = $row["codestpro1"];
					  $ls_codestpro2 = $row["codestpro2"];
					  $ls_denestpro2 = $row["denestpro2"];
					  $ls_estcla	 = $row["estcla"];
					  echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2','$ls_estcla');\">".trim($ls_codestpro1)."</td>";
					  echo "<td width=30 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro2','$ls_denestpro2','$ls_estcla');\">".trim($ls_codestpro2)."</a></td>";
					  echo "<td width=130 align=\"left\">".ltrim($ls_denestpro2)."</td>";
					  echo "</tr>";
					}
			}
	     else
		    {
			  $io_msg->message("No se han definido ".$arr["nomestpro2"]);			
			}
	   }
     print "</table>";
   }

?>
</p>
    <div id="detcodestpro2"></div>
    <p>&nbsp;    </p>
  </div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f 	= document.formulario;
fop = opener.document.formulario;

function aceptar(ls_codestpro2,ls_denestpro2,ls_estcla)
{
	ls_maestro              = fop.hidmaestro.value; 
	fop.txtcodestpro2.value = ls_codestpro2;
    fop.txtdenestpro2.value = ls_denestpro2;

	switch(ls_maestro)
	{
		case 'Y':
		 fop.operacionestprog2.value = "BUSCAR";
	     fop.statusprog2.value       = 'C';
		 fop.txtclasificacion.value    =ls_estcla;
		break;

		case 'P':		
    	  fop.txtcodestpro3.value    = "";
    	  fop.txtdenestpro3.value    = "";
    	  if ("<?php $_SESSION["la_empresa"]["estmodest"] ?>"=="2")
		     {
			   fop.txtcodestpro4.value = "";
			   fop.txtdenestpro4.value = "";
			   fop.txtcodestpro5.value = "";
			   fop.txtdenestpro5.value = "";			 
			 }
    	  for (i=1;i<=50;i++)
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
  if (ls_opener!='sigesp_spg_d_codestpro_codfuefin.php' && ls_opener!='sigesp_cfg_d_consolidacion.php')
	 {
	   f.operacion.value="BUSCAR";
	   f.action="sigesp_spg_cat_estpro2.php";
	   f.submit();
     }
  else
     {
	   uf_print_codestpro2();
	 }
}

function uf_print_codestpro2()
{
  ls_estcla     = fop.hidestcla.value;
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_denestpro2 = f.txtdenestpro2.value;
  orden      = f.orden.value;
  campoorden = f.campoorden.value;
  divgrid    = document.getElementById("detcodestpro2");
  ajax       = objetoAjax();
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
  ajax.send("catalogo=CODESTPRO2&campoorden="+campoorden+"&orden="+orden+"&codestpro2="+ls_codestpro2+"&denestpro2="+ls_denestpro2+"&codestpro1="+ls_codestpro1+"&estcla="+ls_estcla);
}

function uf_aceptar_codestpro2(as_codestpro2,as_denestpro2)
{
  fop.txtcodestpro2.value = as_codestpro2;
  fop.txtdenestpro2.value = as_denestpro2;
  fop.txtcodestpro3.value = "";
  fop.txtdenestpro3.value = "";
  li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  if (li_estmodest==2)
     {
	   fop.txtcodestpro4.value = "";
	   fop.txtcodestpro5.value = "";
	   fop.txtdenestpro4.value = "";
	   fop.txtdenestpro5.value = "";	 
	 }
  close();
}
</script>
<?php
if ($ls_operacion=="CODESTPRO2")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_print_codestpro2();";
	 echo "</script>";
   }
?>
</html>