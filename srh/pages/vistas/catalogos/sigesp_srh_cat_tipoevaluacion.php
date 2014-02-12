<?php
session_start();

	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{$ls_ejecucion="";}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipos de Evaluciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_tipoevaluacion.js"></script>
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
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print ($ls_ejecucion)?>">
   
	<input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipo de Evaluaciones </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodeval" type="text" id="txtcodeval" onKeyUp="javascript: Buscar()">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdeneval" type="text" id="txtdeneval" size="60" maxlength="254" onKeyUp="javascript: Buscar()">
        </div></td>
      </tr>
      <tr>
      <td>&nbsp;</td>
        <td><div align="right"></div></td>
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
        <td align="center" bgcolor="#EBEBEB">
		<div id="gridbox" align="center" width="500" height="800" style="background-position:center"></div></td>
      </tr>
  </table>

  <br>


</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script>
	
		var loadDataURL = "../../php/sigesp_srh_a_tipoevaluacion.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_tipoevaluacion.php";
	    var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
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
			mygrid.setHeader("Codigo,Denominacion");
			mygrid.setInitWidths("120,380")
			mygrid.setColAlign("center,center")
			mygrid.setColTypes("link,ro");
			mygrid.setColSorting("str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF")

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
	
		   codeval=document.form1.txtcodeval.value;
		   deneval=document.form1.txtdeneval.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_tipoevaluacion.php?valor=buscar"+"&txtcodeval="+codeval+"&txtdeneval="+deneval);
           setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codeval,ls_deneval,ls_codesc, ls_denesc,ls_coddestino,ls_dendestino,ls_codescdestino,ls_denescdestino)
		{
			
			if (opener.document.form1.hidcontrol2.value=="2") {
			  obj=eval("opener.document.form1."+ls_coddestino+"");
			  obj.value=ls_codeval;
			  obj1=eval("opener.document.form1."+ls_dendestino+"");
			  obj1.value=ls_deneval;
			
			}
			else if (opener.document.form1.hidcontrol2.value=="3") {
			  num=opener.document.form1.totalfilas.value;
			  obj=eval("opener.document.form1."+ls_coddestino+num+"");
			  obj.value=ls_codeval;
			  obj1=eval("opener.document.form1."+ls_dendestino+num+"");
			  obj1.value=ls_deneval;
			
			}
			else {
			
			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codeval;
			obj1=eval("opener.document.form1."+ls_dendestino+"");
			obj1.value=ls_deneval;
			obj2=eval("opener.document.form1."+ls_codescdestino+"");
			obj2.value=ls_codesc;
			obj3=eval("opener.document.form1."+ls_denescdestino+"");
			obj3.value=ls_denesc;
			
			opener.document.form1.txtcodeval.readOnly=true;
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

