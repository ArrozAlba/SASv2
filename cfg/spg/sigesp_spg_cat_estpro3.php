<?php
session_start();
$arr=$_SESSION["la_empresa"];
$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];

$li_longcodestpro1 = (25-$li_loncodestpro1)+1;
$li_longcodestpro2 = (25-$li_loncodestpro2)+1;
$li_longcodestpro3 = (25-$li_loncodestpro3)+1;


require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");

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
	 $ls_codestpro3 = $_POST["txtcodestpro3"];
	 $ls_denestpro3 = $_POST["txtdenestpro3"];
	 $ls_estcla     = $_POST["txtestcla2"];	 
   }
else
   { 
	 $ls_operacion  = "BUSCAR";
	 $ls_codestpro1 = $_GET["txtcodestpro1"];
	 $ls_denestpro1 = $_GET["txtdenestpro1"];
	 $ls_codestpro2 = $_GET["txtcodestpro2"];
	 $ls_denestpro2 = $_GET["txtdenestpro2"];
	 $ls_estcla     = $_GET["txtclasificacion"];
	 $ls_codestpro3 = "";
	 $ls_denestpro3 = ""; 
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Programática Nivel 3 <?php print $arr["nomestpro3"] ?></title>
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
	 <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo <?php print $arr["nomestpro3"] ?>
        <input name="campoorden" type="hidden" id="campoorden" value="spg_ep3.codestpro3">
        <input name="orden" type="hidden" id="orden" value="ASC"></td>
       </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td width="118" height="22"><div align="right"><?php print $arr["nomestpro1"]?></div></td>
        <td width="432" height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" value="<?php print $ls_codestpro1 ?>" size="<?php print $li_loncodestpro1+2 ?>" maxlength="<?php print $li_loncodestpro1 ?>" readonly style="text-align:center">        
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="50" value="<?php print $ls_denestpro1 ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $arr["nomestpro2"]?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print  $ls_codestpro2?>" size="<?php print $li_loncodestpro2+2 ?>" maxlength="<?php print $li_loncodestpro2 ?>" readonly style="text-align:center">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" value="<?php print $ls_denestpro2?>" size="50" class="sin-borde" readonly>
          <input name="txtestcla2" type="hidden" id="txtestcla2" value="<?php print $ls_estcla?>" size="<?php print $ls_ancho ?>" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo</td>
        <td height="22"><input name="txtcodestpro3" type="text" id="txtcodestpro3"  size="<?php print $li_loncodestpro3+2 ?>" maxlength="<?php print $li_loncodestpro3 ?>" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td height="22"><div align="left">
          <input name="txtdenestpro3" type="text" id="txtdenestpro3"  size="72" maxlength="100">
        </div></td>
      </tr>
      <tr>
        <td height="22"><input name="txtpantalla" type="hidden" id="txtpantalla"  size="18" maxlength="100" style="text-align:left" value="<?=$ls_pantalla?>"></td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
      <tr>
        <td height="13" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" colspan="2" style="text-align:center">&nbsp;</td>
      </tr>
	</table>
	 <p align="center">
<?php
if (array_key_exists("opener",$_GET))
   {
     $ls_opener = $_GET["opener"];
     if ($ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
	    {
		  $ls_operacion = "CODESTPRO3";		  
		}
   }
elseif ($ls_operacion=="BUSCAR")
   {
	 echo "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 echo "<tr class=titulo-celda>";
	 echo "<td  width=150>".$arr["nomestpro1"]."</td>";
	 echo "<td  width=100>".$arr["nomestpro2"]."</td>";
	 echo "<td  width=50>C&oacute;digo </td>";
	 echo "<td  width=300>Denominaci&oacute;n</td>";
	 echo "</tr>";

	 if (!empty($ls_codestpro1) && !empty($ls_codestpro2))
	    {
	      $ls_codestpro1 = str_pad($ls_codestpro1,25,0,0);
  	      $ls_codestpro2 = str_pad($ls_codestpro2,25,0,0);
		}
	if (array_key_exists('session_activa',$_SESSION))
    {	 
		 $ls_sql="SELECT substr(codestpro1,".$li_longcodestpro1.",25) as codestpro1,
						 substr(codestpro2,".$li_longcodestpro2.",25) as codestpro2 ,
						 substr(codestpro3,".$li_longcodestpro3.",25) as codestpro3,
						 denestpro3,estcla,estreradi, '' as codfuefin, '' as denfuefin
					FROM spg_ep3
				   WHERE spg_ep3.codemp='".$ls_codemp."'
					 AND estcla='".$ls_estcla."'
					 AND codestpro1 = '".$ls_codestpro1."'
					 AND codestpro2 = '".$ls_codestpro2."'
					 AND codestpro3 like '%".$ls_codestpro3."%' ".
				"    AND denestpro3 like '%".$ls_denestpro3."%' AND codestpro1||codestpro2||codestpro3||estcla IN (SELECT SUBSTR(codintper,1,75)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' 
				   UNION  SELECT SUBSTR(codintper,1,75)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
				   ORDER BY codestpro3";
	}
	else
	{	
		 $ls_sql="SELECT substr(codestpro1,".$li_longcodestpro1.",25) as codestpro1,
						 substr(codestpro2,".$li_longcodestpro2.",25) as codestpro2 ,
						 substr(codestpro3,".$li_longcodestpro3.",25) as codestpro3,
						 denestpro3,estcla,estreradi,sigesp_fuentefinanciamiento.codfuefin,
						 sigesp_fuentefinanciamiento.denfuefin
					FROM spg_ep3,sigesp_fuentefinanciamiento
				   WHERE spg_ep3.codemp='".$ls_codemp."'
					 AND estcla like '%".$ls_estcla."%'
					 AND codestpro1 like '%".$ls_codestpro1."%'
					 AND codestpro2 like '%".$ls_codestpro2."%'
					 AND codestpro3 like '%".$ls_codestpro3."%'
					 AND denestpro3 like '%".$ls_denestpro3."%'
					 AND spg_ep3.codfuefin=sigesp_fuentefinanciamiento.codfuefin
					 AND codestpro1 <>'-------------------------'
					 AND codestpro2 <>'-------------------------'
					 AND codestpro3 <>'-------------------------' ".
				"    AND codestpro1||codestpro2||codestpro3||estcla IN (SELECT SUBSTR(codintper,1,75)||SUBSTR(codintper,126,1) FROM sss_permisos_internos WHERE codusu='".$_SESSION["la_logusr"]."' 
				   UNION  SELECT SUBSTR(codintper,1,75)||SUBSTR(codintper,126,1) FROM sss_permisos_internos_grupo,sss_usuarios_en_grupos WHERE codusu='".$_SESSION["la_logusr"]."' AND sss_permisos_internos_grupo.codgru=sss_usuarios_en_grupos.codgru)
				   ORDER BY codestpro1,codestpro2,codestpro3 ASC";
	}

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
					  $ls_codestpro3 = $row["codestpro3"];
					  $ls_denestpro3 = $row["denestpro3"];
					  $ls_codfuefin  = $row["codfuefin"];
					  $ls_denfuefin  = $row["denfuefin"];
					  $ls_estcla     = $row["estcla"];
					  $ls_estreradi  = $row["estreradi"]; 
					  if (array_key_exists('session_activa',$_SESSION))
					     {	 
						   echo "<td width=150 align=\"center\"><a href=\"javascript: aceptar_v2('$ls_codestpro3','$ls_denestpro3','$ls_estcla','$ls_estreradi');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=100 align=\"center\"><a href=\"javascript: aceptar_v2('$ls_codestpro3','$ls_denestpro3','$ls_estcla','$ls_estreradi');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=50  align=\"center\"><a href=\"javascript: aceptar_v2('$ls_codestpro3','$ls_denestpro3','$ls_estcla','$ls_estreradi');\">".trim($ls_codestpro3)."</a></td>";
						   echo "<td width=300 align=\"left\">".ltrim($ls_denestpro3)."</td>";
						   echo "</tr>";
					     }
					  else
					     {
						   echo "<td width=150 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3','$ls_codfuefin','$ls_denfuefin','$ls_estcla','$ls_estreradi');\">".trim($ls_codestpro1)."</td>";
						   echo "<td width=100 align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3','$ls_codfuefin','$ls_denfuefin','$ls_estcla','$ls_estreradi');\">".trim($ls_codestpro2)."</td>";
						   echo "<td width=50  align=\"center\"><a href=\"javascript: aceptar('$ls_codestpro3','$ls_denestpro3','$ls_codfuefin','$ls_denfuefin','$ls_estcla','$ls_estreradi');\">".trim($ls_codestpro3)."</a></td>";
						   echo "<td width=300 align=\"left\" title='".ltrim($ls_denestpro3)."'>".ltrim($ls_denestpro3)."</td>";
						   echo "</tr>";			
					     }
					}
			}
	     else
		    {
			  $io_msg->message("No se han definido ".$arr["nomestpro3"]);			
			}
	   }
     print "</table>";
   }
?>
</p>
	 <div id="detcodestpro3"></div>
	 <p align="center">&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
f 	= document.formulario;
fop = opener.document.formulario;

function aceptar(ls_codestpro3,ls_denestpro3,ls_codfuefin,ls_denfuefin,ls_estcla,ls_estreradi)
{
	fop.txtcodestpro3.value    = ls_codestpro3;
	fop.txtcodestpro3.readOnly = true;
	fop.txtdenestpro3.value    = ls_denestpro3;
	ls_opener                  = opener.document.formulario.id;
	if (ls_opener!='sigesp_cfg_d_consolidacion.php')
	   {
		ls_maestro                 = fop.hidmaestro.value; 
		ls_pantalla                = document.formulario.txtpantalla.value;
		if (ls_estreradi=="1")
		   {
			 fop.chkrecuadi.checked=true; 
		   }
		switch(ls_maestro)
		{
			case 'Y':
			fop.operacionestprog3.value = "BUSCAR";
			fop.statusprog3.value       = 'C';  
			fop.txtcodigo.value         = ls_codfuefin;
			fop.txtcodigo.readOnly      = true;
			fop.txtdenominacion.value   = ls_denfuefin;
			fop.txtclasificacion.value  = ls_estcla; 
			break;
	
			case 'N':
			fop.txtcodestpro4.value    = "";
			fop.txtdenestpro4.value    = "";
			break;		
		}
	   }
	close();
}

function aceptar_v2(ls_codestpro3,ls_denestpro3,ls_estcla,ls_estreradi)
{
	fop.txtcodestpro3.value    = ls_codestpro3;
	fop.txtcodestpro3.readOnly = true;
	fop.txtdenestpro3.value    = ls_denestpro3;
	ls_maestro                 = fop.hidmaestro.value; 
	ls_pantalla                = f.txtpantalla.value;
	if (ls_estreradi=="1")
	   {
	     fop.chkrecuadi.checked=true; 
	   }
	switch(ls_maestro)
	{
		case 'Y':
		fop.operacionestprog3.value = "BUSCAR";
		fop.statusprog3.value       = 'C';  
		fop.txtclasificacion.value  = ls_estcla; 
		break;

		case 'N':
    	fop.txtcodestpro4.value    = "";
    	fop.txtdenestpro4.value    = "";
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
	   f.action="sigesp_spg_cat_estpro3.php";
	   f.submit();
     }
  else
     {
	   uf_print_codestpro3();
	 }
}

function uf_print_codestpro3()
{
  ls_estcla     = fop.hidestcla.value;
  ls_codestpro1 = f.txtcodestpro1.value;
  ls_codestpro2 = f.txtcodestpro2.value;
  ls_codestpro3 = f.txtcodestpro3.value;
  ls_denestpro1 = f.txtdenestpro1.value;
  ls_denestpro2 = f.txtdenestpro2.value;
  ls_denestpro3 = f.txtdenestpro3.value;
  orden         = f.orden.value;
  campoorden    = f.campoorden.value;
  
  divgrid = document.getElementById("detcodestpro3");
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
  ajax.send("catalogo=CODESTPRO3&campoorden="+campoorden+"&orden="+orden+"&codestpro1="+ls_codestpro1+"&codestpro2="+ls_codestpro2+"&codestpro3="+ls_codestpro3+"&denestpro3="+ls_denestpro3+"&estcla="+ls_estcla+"&denestpro1="+ls_denestpro1+"&denestpro2="+ls_denestpro2);
}

function uf_aceptar_codestpro3(as_codestpro1,as_denestpro1,as_codestpro2,as_denestpro2,as_codestpro3,as_denestpro3,as_estcla)
{
  ls_opener     = fop.id;
  ls_codestpro1 = fop.txtcodestpro1.value;
  ls_codestpro2 = fop.txtcodestpro2.value;
  li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
  if (ls_codestpro1=='' && ls_codestpro2=='' && li_estmodest==1)
     {
	   fop.hidestcla.value     = as_estcla;
	   fop.txtcodestpro1.value = as_codestpro1;
	   fop.txtdenestpro1.value = as_denestpro1;
	   fop.txtcodestpro2.value = as_codestpro2;
	   fop.txtdenestpro2.value = as_denestpro2;
	   fop.txtcodestpro3.value = as_codestpro3;
	   fop.txtdenestpro3.value = as_denestpro3;
	   if (ls_opener=='sigesp_cfg_d_consolidacion.php')
	      {
		    fop.operacion.value="CASTEST";
			close();
		  }
	 }
  else
     {
	   fop.txtcodestpro3.value = as_codestpro3;
	   fop.txtdenestpro3.value = as_denestpro3;
	   if (li_estmodest==2)
	      {
		    fop.txtcodestpro4.value = "";
		    fop.txtdenestpro4.value = "";
		    fop.txtcodestpro5.value = "";
		    fop.txtdenestpro5.value = "";
			close();
		  }
	   if (ls_opener=='sigesp_cfg_d_consolidacion.php')
		  {
		    close();
		  }
	 }
  if (li_estmodest==1 && ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
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
	li_estmodest  = "<?php print $_SESSION["la_empresa"]["estmodest"]; ?>";
	if (li_estmodest==2)
	   {
		 ls_codestpro2 = fop.txtcodestpro2.value;
		 ls_codestpro3 = fop.txtcodestpro3.value;
	   }
	else
	   {
	     ls_codestpro4 = ls_codestpro5 = "";
	   }
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
if ($ls_operacion=="CODESTPRO3")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_print_codestpro3();";
	 echo "</script>";
   }
?>
</html>