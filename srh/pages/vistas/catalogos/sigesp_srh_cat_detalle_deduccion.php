<?php
session_start();

if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];
	}
else
{
		$ls_tipo="";
}	
	
if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}	
	
if (isset($_GET["codded"]))
{ 
		$ls_codded=$_GET["codded"];	
}
else
{
	$ls_codded="";
}

if(array_key_exists("nexfam",$_GET))
{
	$ls_nexfam=$_GET["nexfam"];
}
else
{
		$ls_nexfam="";
}


if(array_key_exists("sexper",$_GET))
{
	$ls_sexper=$_GET["sexper"];
}
else
{
		$ls_sexper="";
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Detalle de Deducci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_personal.js"></script>


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

</head>

<body onLoad="javascript: doOnLoad();">
<form name="form1" method="post" action="">
  <p align="center">
   
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion?>" >
	<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
	<input name="hidnexfam" type="hidden" id="hidnexfam" value="<?php print $ls_nexfam?>">
	<input name="hidsexper" type="hidden" id="hidsexper" value="<?php print $ls_sexper?>">
  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Detalle de Deducci&oacute;n</td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td width="273" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="227"><div align="right">C&oacute;digo Deducci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodded" type="text" id="txtcodded" value="<?php print $ls_codded?>"  size=16 readonly style="text-align:center">
        </div></td>
      </tr>
	 </table>
	<div align="center" id="mostrar" class="oculto1"></div> 
    <table width="800" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="600" height="800" style="background-position:center"></div>
		
		</td>
		</tr>
		
	  
    </table>
	
 
</div>
</form>
<p>&nbsp;</p>

</body>

</html>

<script language="JavaScript">


        codded=document.form1.txtcodded.value;
		hidtipo=document.form1.hidtipo.value;
		hidnexfam=document.form1.hidnexfam.value;
		hidsexper=document.form1.hidsexper.value;
        var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML_det_deduccion&codded="+codded+"&tipo="+hidtipo+"&nexfam="+hidnexfam+"&sexper="+hidsexper;
		var actionURL = "../../php/sigesp_srh_a_personal.php";
	    var img="<img src=../../../public/imagenes/progress.gif> ";
		var mygrid;
		var timeoutHandler;//update will be sent automatically to server if row editting was stoped;
		var rowUpdater;//async. Calls doUpdateRow function when got data from server
		var rowEraser;//async. Calls doDeleteRow function when got confirmation about row deletion
		var authorsLoader;//sync. Loads list of available authors from server to populate dropdown (co)
		var mandFields = [0,1,1,0,0]
		
		
	//initialise (from xml) and populate (from xml) grid
		function doOnLoad()
		{
            divResultado = document.getElementById('mostrar');
			divResultado.innerHTML= img; 
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Código,Titular,Sueldo,Edad Mínima,Edad Máxima,Género,HCM,Nexo Familiar,Valor Prima,%Apor. Empresa,%Apor. Empleado");
			mygrid.setInitWidths("70,70,70,70,70,70,70,70,80,80,80")
			mygrid.setColAlign("center,center,center,center,center,center,center,center,center,center,center")
			mygrid.setColTypes("link,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF")

			mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
            setTimeout (terminar_buscar,500);
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }	
		
		
		
		
	function aceptar(ls_cod)
	{
		
		ls_tipo = document.form1.hidtipo.value;
		if (ls_tipo=='dedper')
		{
		  opener.document.form1.txtcoddettipded.value=ls_cod;
		  opener.document.form1.txtmontod.value=0;
	      calcular_monto (ls_cod);
		}
		else if (ls_tipo=='dedfam')
		{
		  opener.document.form1.txtcoddettipdedfam.value=ls_cod;
	      opener.document.form1.txtmontodfam.value=0;
		  calcular_monto_familiar (ls_cod);
		}
		setTimeout(close,2000);
	}
		
	
	
function calcular_monto (coddettipded)
 {
	function onConsultar(respuesta)
	{
		opener.document.form1.txtmontod.value  = uf_convertir(trim(respuesta.responseText));
	
	 }	
	  codper=opener.document.form1.txtcodper.value;	  
	  codtipded=opener.document.form1.txtcodtipded.value;
	  params = "operacion=calcular_monto_deduccion&codper="+codper+"&codtipded="+codtipded+"&coddettipded="+coddettipded;
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onConsultar});
		
	 
}



function calcular_monto_familiar (coddettipded)
 {
	function onConsultar(respuesta)
	{
			opener.document.form1.txtmontodfam.value  = uf_convertir(trim(respuesta.responseText));
	 }	
	
	  codper=opener.document.form1.txtcodper.value;	  
	  codtipded=opener.document.form1.txtcodtipded1.value;
	  cedfam=opener.document.form1.txtcedfam1.value;
	  coddettipded=opener.document.form1.txtcoddettipdedfam.value;
	  params = "operacion=calcular_monto_deduccion_fam&codper="+codper+"&codtipded="+codtipded+"&cedfam="+cedfam+"&coddettipded="+coddettipded;
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onConsultar});
  }
		
	
		
	
</script>