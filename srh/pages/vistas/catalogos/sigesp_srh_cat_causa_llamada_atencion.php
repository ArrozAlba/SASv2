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
<title>Cat&aacute;logo de Causas de Amonestaci&on /  Llamada de Atenci&oacute;n</title>
<script type="text/javascript" language="JavaScript1.2" src="../shcaullam_atend/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_causa_llamada_atencion.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<style type="text/css">
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

</head>

<body onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">
   
 
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Causas de Amonestaci&oacute;n / Llamada de Atenci&oacute;n </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="110"><div align="right">C&oacute;digo</div></td>
        <td width="388" height="22"><div align="left">
          <input name="txtcodcaullam_aten" type="text" id="txtcodcaullam_aten" onKeyUp="javascript: Buscar()">
        </div></td>
      </tr>
      
      <tr>
        <td width="110"><div align="right">Denominaci&oacute;n</div></td>
        <td width="388" height="22"><div align="left">
          <input name="txtdencaullam_aten" type="text" id="txtdencaullam_aten" onKeyUp="javascript: Buscar()">
        </div></td>
     </tr>    
      
      <tr>
        <td>&nbsp;</td>
       
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

  <br>


</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>




<script>
	
		var loadDataURL = "../../php/sigesp_srh_a_causa_llamada_atencion.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_causa_llamada_atencion.php";
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
	
		   codcaullam_aten=document.form1.txtcodcaullam_aten.value;
		   dencaullam_aten=document.form1.txtdencaullam_aten.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_causa_llamada_atencion.php?valor=buscar"+"&txtcodcaullam_aten="+codcaullam_aten+"&txtdencaullam_aten="+dencaullam_aten);
          setTimeout (terminar_buscar,500);
			
		}
		
      function aceptar(ls_codcaullam_aten,ls_dencaullam_aten,ls_coddestino,ls_dendestino)
		{
			
			if (opener.document.form1.txtcontrol.value == "1") 
			{
			num=opener.document.form1.txtnum.value;
			obj=eval("opener.document.form1."+ls_coddestino+num+"");
			obj.value=ls_codcaullam_aten;
			obj1=eval("opener.document.form1."+ls_dendestino+num+"");
			obj1.value=ls_dencaullam_aten;
		    }
			else
			{
			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcaullam_aten;
			obj1=eval("opener.document.form1."+ls_dendestino+"");
			obj1.value=ls_dencaullam_aten;
			
			ls_ejecucion=document.form1.hidstatus.value;
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
			}
			
			opener.document.form1.txtcodcaullam_aten.readOnly=true;
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

