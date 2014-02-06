<?php
session_start();
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

require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_c_seguridad.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion  = $_POST["operacion"];
	 $ls_codunieje  = $_POST["txtcodunieje"];
	 $ls_denunieje  = $_POST["txtdenunieje"];     
   }
else
   {
     $ls_operacion  = "";	
	 $ls_codunieje  = "";
	 $ls_denunieje  = "";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidades Ejecutoras</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../js/funciones_configuracion.js"></script>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"></p>
  	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda"><input name="hidmaestro" type="hidden" id="hidmaestro" value="N">
        Cat&aacute;logo de Unidades Ejecutoras</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="101" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="461" height="22"><div align="left">
          <input name="txtcodunieje" type="text" id="txtcodunieje" maxlength="10" style="text-align:center" onKeyPress="return keyRestrict(event,'1234567890');">        
        </div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22"><input name="txtdenunieje" type="text" id="txtdenunieje" style="text-align:left" size="75" maxlength="100"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
<p align="center">
<?php
echo "<table width=560 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=80>C&oacute;digo</td>";
echo "<td style=text-align:center width=400>Denominaci&oacute;n</td>";
echo "<td style=text-align:center width=80>Emite Req.</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR")
   { 
	 $ls_sql="SELECT coduniadm,denuniadm,estemireq, coduniadmsig,
	                 (SELECT denuac FROM  spg_ministerio_ua WHERE coduac=coduniadmsig)as denuniadmin
	            FROM spg_unidadadministrativa
			   WHERE codemp='".$ls_codemp."' 
			     AND coduniadm like '%".$ls_codunieje."%' 
				 AND denuniadm like '%".$ls_denunieje."%' 
				 AND coduniadm<>'----------' ORDER BY coduniadm";
   
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
		  $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		}
     else
	    {
		  $li_totrows = $io_sql->num_rows($rs_data);
		  if ($li_totrows>0)
		     {
			   while (!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
					   $ls_codunieje = $rs_data->fields["coduniadm"];
					   $ls_denunieje = $rs_data->fields["denuniadm"];
			           $li_estemireq = $rs_data->fields["estemireq"];
					   $ls_coduniadm = $rs_data->fields["coduniadmsig"];
					   $ls_denuniadm = $rs_data->fields["denuniadmin"];
			           if ($li_estemireq ==1){$ls_estreq="Si";}
					   elseif($li_estemireq ==0){$ls_estreq="No";}
					   else{$li_estemireq="";}					   
					   echo "<td style=text-align:center width=80><a href=\"javascript: aceptar('$ls_codunieje','$ls_denunieje','$li_estemireq','$ls_coduniadm','$ls_denuniadm');\">".$ls_codunieje."</a></td>";
					   echo "<td style=text-align:left   width=400 title='".$ls_denunieje."'>".$ls_denunieje."</td>";
					   echo "<td style=text-align:center width=80>".$ls_estreq."</td>";
				       echo "</tr>";
                       $rs_data->MoveNext();
					 }
			 }
		  else
		     {
			   $io_msg->message("No se han definido Unidades Ejecutoras !!!");
			 }
		}    
   }
print "</table>";
?>
</p>
</form>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
fop = opener.document.form1;
f   = document.form1;

function aceptar(ls_codunieje,ls_denunieje,estreq,ls_coduniadm,ls_denuniadm)
{
    fop.txtcodunieje.value = ls_codunieje;
    fop.txtdenunieje.value = ls_denunieje;
	if (estreq==1)
	   {
		 fop.estreq.checked=true;
	   }
	else
	   {
		 fop.estreq.checked=false;
	   }

	fop.status.value          ='C';
	fop.txtcoduniadm.value    = ls_coduniadm;
	fop.txtcodunieje.readOnly = true;
	fop.txtdenuniadm.value=ls_denuniadm;
	
	valido=true;
	parametros=""
	parametros=parametros+"&txtcodunidadm="+ls_codunieje;	
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("estructuras");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_spg_c_unidad_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
			  divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
			}
			else
			{
				if(ajax.readyState==4)
				{
					if(ajax.status==200)
					{//mostramos los datos dentro del contenedor
						divgrid.innerHTML = ajax.responseText
					}
					else
					{
						if(ajax.status==404)
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
		ajax.send("proceso=BUSCARDETALLE"+parametros);
	 }
}
  
function ue_search()
{
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_cat_unidad.php";
	f.submit();
} 
</script>
</html>