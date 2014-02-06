<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Fuentes de Financiamiento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cfg.js"></script>
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
<?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");


$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsfuente=new class_datastore();
$io_sql=new class_sql($conn);
if (array_key_exists("opener",$_GET))
   {
     $ls_opener = $_GET["opener"];
     if ($ls_opener=='sigesp_spg_d_codestpro_codfuefin.php')
	    {
		  $ls_operacion = "CODFUEFIN";
		}
   }
else
   {
     $ls_operacion = "";
	 $arr=$_SESSION["la_empresa"];
     $ls_sql = "SELECT * FROM sigesp_fuentefinanciamiento WHERE codfuefin <> '--' ORDER BY codfuefin ASC";
     $rs_fuente=$io_sql->select($ls_sql);
     $data=$rs_fuente;
	 if (array_key_exists("txtpantalla",$_GET))
	    {
     	  $ls_pantalla = $_GET["txtpantalla"];
        }
     else
	    {
		  $ls_pantalla = "";
		}
   }
?>
<form name="formulario" method="post" action="">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="500" height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Fuentes de Financiamiento 
    <input name="campoorden" type="hidden" id="campoorden" value="codfuefin">
    <input name="orden" type="hidden" id="orden" value="ASC"></td>
  </tr>
  <tr>
    <td height="22" colspan="2" style="text-align:center"><div id="detfuente"></div></td>
  </tr></table>
 <input name="txtpantalla" id="txtpantalla" type="hidden" value="<?=$ls_pantalla?>">
  <div align="center">
<?php
if (!array_key_exists("opener",$_GET))
   {
	if ($row=$io_sql->fetch_row($rs_fuente))
	   {
		 $data=$io_sql->obtener_datos($rs_fuente);
		 $io_dsfuente->data=$data;
		 $totrow=$io_dsfuente->getRowCount("codfuefin");
		 print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		 print "<tr class=titulo-celda>";
		 print "<td>Código</td>";
		 print "<td  style=text-align:left>Denominación</td>";
		 print "</tr>";
		 for ($z=1;$z<=$totrow;$z++)
			 {
				print "<tr class=celdas-blancas>";
				$ls_codfuefin   =$data["codfuefin"][$z];
				$ls_denominacion=$data["denfuefin"][$z];
				$ls_explicacion =$data["expfuefin"][$z];
				print "<td><a href=\"javascript: aceptar('$ls_codfuefin','$ls_denominacion','$ls_explicacion');\">".$ls_codfuefin."</a></td>";
				print "<td  style=text-align:left>".$ls_denominacion."</td>";
				print "</tr>";			
			 }
		print "</table>";
		}
	else
		{
		  ?>
		  <script language="javascript" >
		  alert("No se han creado Fuentes de Financiamiento !!!");
		  close();
		  </script>
		 <?php  
		}		 
	$io_sql->free_result($rs_fuente);
	$io_sql->close();
   }
?>
  </div>
</form>
</body>
<script language="JavaScript">
f   = document.formulario;
fop = opener.document.formulario;
function aceptar(codigo,denominacion,explicacion)
{
  ls_pantalla = document.formulario.txtpantalla.value;
  if (ls_pantalla=="d_estprog5" )
	 {
	   fop.txtcodigo.value=codigo;
	   fop.txtcodigo.readOnly=true;
       fop.txtdenominacion.value=denominacion;
	 }
  else
	 {
	   fop.txtcodigo.value=codigo;
	   fop.txtcodigo.readOnly=true;
       fop.txtdenominacion.value=denominacion;
	   fop.txtexplicacion.value=explicacion;
	   fop.status.value='C';
	   fop.txtdenominacion.focus(true);	
	}	
  close();
}

function uf_print_fuente_financiamiento()
{
  orden      = f.orden.value;
  campoorden = f.campoorden.value;
  divgrid    = document.getElementById("detfuente");
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
  ls_codfuefin = ls_denfuefin = "";
  ajax.send("catalogo=CODFUEFIN&campoorden="+campoorden+"&orden="+orden+"&codfuefin="+ls_codfuefin+"&denfuefin="+ls_denfuefin);
}

function uf_aceptar_fuente_financiamiento(as_codfuefin,as_denfuefin)
{
  lb_valido  = true;
  li_totrows = ue_calcular_total_fila_opener("txtcodfuefin");
  for (li_i=1;(li_i<=li_totrows)&&(lb_valido);li_i++)
	  {
	    codfuefingrid = eval("fop.txtcodfuefin"+li_i+".value");
		if (codfuefingrid==as_codfuefin)
		   {
		     alert("La Fuente de Financiamiento ya fué Incorporada !!!");
			 lb_valido = false;
		   }
	  }
   parametros="";
   for (li_i=1;(li_i<=li_totrows)&&(lb_valido);li_i++)
	   { 
	     ls_codfuefin = eval("fop.txtcodfuefin"+li_i+".value");
		 if (ls_codfuefin!='')
		    {
			  ls_denfuefin = eval("fop.txtdenfuefin"+li_i+".value");
		      lb_exifuefin = eval("fop.hidexiste"+li_i+".value");
		      parametros   = parametros+"&txtcodfuefin"+li_i+"="+ls_codfuefin+"&txtdenfuefin"+li_i+"="+ls_denfuefin+"&hidexiste"+li_i+"="+lb_exifuefin;
			}
	   }
   if (li_totrows==1 && eval("fop.txtcodfuefin1.value")=='')
      {
        totalrows  = li_totrows;
		parametros = parametros+"&txtcodfuefin1="+as_codfuefin+"&txtdenfuefin1="+as_denfuefin+"&totrows="+totalrows+"&hidexiste1=false";
	  }
   else
      {
		totalrows  = eval(li_totrows+"+1");
		parametros = parametros+"&txtcodfuefin"+totalrows+"="+as_codfuefin+"&txtdenfuefin"+totalrows+"="+as_denfuefin+"&totrows="+totalrows+"&hidexiste"+totalrows+"=false";
	  }
   if ((parametros!="")&&(lb_valido))
	  {
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("detalles");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_spg_c_codestpro_codfuefin_ajax.php",true);
		ajax.onreadystatechange=function(){
		if (ajax.readyState==1)
			{
				divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
			}
		else
			{
			  if (ajax.readyState==4)
				 {
				   if (ajax.status==200)
					  {//mostramos los datos dentro del contenedor
					    divgrid.innerHTML = ajax.responseText
					  }
				   else
					  {
						if (ajax.status==404)
						   {
							 divgrid.innerHTML = "La página no existe";
					 	   }
						else
						   {//mostramos el posible error     
							 divgrid.innerHTML = "Error:".ajax.status;
						   }
					  }
					
				 }
			}
		}	
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso=LIMPIAR"+parametros);
		fop.totrows.value=totalrows;
      }
}
</script>
<?php
if ($ls_operacion=="CODFUEFIN")
   {
	 echo "<script language=JavaScript>";
	 echo "   uf_print_fuente_financiamiento();";
	 echo "</script>";
   }
?>
</html>