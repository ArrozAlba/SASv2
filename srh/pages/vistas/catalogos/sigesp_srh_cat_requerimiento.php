<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else {$ls_ejecucion="";}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Requerimientos de Cargos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_requerimiento.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
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

<body onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
    
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">
   
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de  Requerimientos de Cargos </td>
    </tr>
  </table>
<br>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="125"><div align="right">C&oacute;digo</div></td>
    <td height="22" colspan="3"><div align="left">
      <input name="txtcodreq" type="text" id="txtcodreq" onKeyUp="javascript: Buscar()">
    </div></td>
  </tr>
  <tr>
    <td><div align="right">Denominaci&oacute;n</div></td>
    <td height="22" colspan="3"><div align="left">
      <input name="txtdenreq" type="text" id="txtdenreq" onKeyUp="javascript: Buscar()">
    </div></td>
  </tr>
  <tr>
    <td><div align="right">Tipo de Requerimiento</div></td>
    <td width="134" height="22"><div align="left">
      <input name="txtcodtipreq" size="16" type="text" id="txtcodtipreq"    >
      <a href="javascript:catalogo_tiporequerimiento();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </div></td> 
    <td colspan="2"><input name="txtdentipreq" type="text" class="sin-borde" id="txtdentipreq"  size="40" maxlength="40" readonly>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="147"><div align="right"><a href="javascript: Limpiar_busqueda(); Buscar();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="94"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
    
  </tr>
</table>
<div align="center" id="mostrar" class="oculto1"></div>  
<table width="500" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      
      <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
       <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="800" style="background-position:center"></div></td>
      </tr>
  </table>

</form>

<p>&nbsp;</p>
<p>&nbsp;</p>

</body>

    
    

	<script>
	
	
	
	function catalogo_tiporequerimiento()
{
      pagina="../catalogos/sigesp_srh_cat_tiporequerimiento.php?valor_cat=0";
		
		window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}
	
		var loadDataURL = "../../php/sigesp_srh_a_requerimiento.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_requerimiento.php";
		var mygrid;
		var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
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
			mygrid.setHeader("Codigo,Denominacion,Tipo");
			mygrid.setInitWidths("120,190,190")
			mygrid.setColAlign("center,center,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF")

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
		
		
		
		function Buscar()
		{
	
		   codreq=document.form1.txtcodreq.value;
		   denreq=document.form1.txtdenreq.value;
		   codtipreq=document.form1.txtcodtipreq.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_requerimiento.php?valor=buscar"+"&txtcodreq="+codreq+"&txtdenreq="+denreq+"&txtcodtipreq="+codtipreq);
	       setTimeout (terminar_buscar,500);
			
		}
		
	
function Limpiar_busqueda () 
{
	document.form1.txtcodreq.value="";
	document.form1.txtdenreq.value="";
	document.form1.txtcodtipreq.value="";
	document.form1.txtdentipreq.value="";
}		

		
		function aceptar(ls_codreq,ls_denreq,ls_codtipreq,ls_dentipreq,ls_coddestino,ls_dendestino,ls_tipodestino,ls_dentipodestino)
		{
		
		    if (opener.document.form1.hidcontrol.value=="1") {
			
				num=opener.document.form1.totalfilas.value;
				
				obj=eval("opener.document.form1."+ls_coddestino+num+"");
				obj.value=ls_codreq;
				obj1=eval("opener.document.form1."+ls_dendestino+num+"");
				obj1.value=ls_denreq;
				obj2=eval("opener.document.form1."+ls_tipodestino+num+"");
				obj2.value=ls_codtipreq;
				obj3=eval("opener.document.form1."+ls_dentipodestino+num+"");
				obj3.value=ls_dentipreq;
			
			}
		    else {
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_codreq;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_denreq;
				obj2=eval("opener.document.form1."+ls_tipodestino+"");
				obj2.value=ls_codtipreq;
				obj3=eval("opener.document.form1."+ls_dentipodestino+"");
				obj3.value=ls_dentipreq;
				
				 ls_ejecucion=document.form1.hidstatus.value;
				if (ls_ejecucion=='1')
				{
				  opener.document.form1.hidstatus.value="C";
				}
			
			}
			close();
		}
	
	
		function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}


	</script>
	
</html>

