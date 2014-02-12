<?php
session_start();

	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{ $ls_ejecucion="";	}
	
	if (isset($_GET["tipo"]))
	{ $ls_tipo=$_GET["tipo"];	}
	else
	{ $ls_tipo="";	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo  de Configuraci&oacute;n de Deducciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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

    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion?>">
	 <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
    
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de  Configuraci&oacute;n de Deducciones</td>
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
          <input name="txtcodtipded" type="text" id="txtcodtipded"  onKeyUp="javascript: Buscar()">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdentipded" type="text" id="txtdentipded"  onKeyUp="javascript: Buscar()">
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
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="800" style="background-position:center"></div></td>
      </tr>
    </table>

</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script>
	
		var loadDataURL = "../../php/sigesp_srh_a_configuracion_deduccion.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_configuracion_deduccion.php";
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
			mygrid.setColTypes("link,ed");
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
	
		   codtipded=document.form1.txtcodtipded.value;
		   dentipded=document.form1.txtdentipded.value;
		   mygrid.clearAll();
		    divResultado = document.getElementById('mostrar');
			divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_configuracion_deduccion.php?valor=buscar"+"&txtcodtipded="+codtipded+"&txtdentipded="+dentipded);
           setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codtipded,ls_dentipded,ls_coddestino,ls_dendestino)
		{
			ls_tipo=document.form1.hidtipo.value;
			if (ls_tipo=='3')
			{
				obj=eval("opener.document.form1.txtcodtipded1");
				obj.value=ls_codtipded;
				obj1=eval("opener.document.form1.txtdentipded1");
				obj1.value=ls_dentipded;
			}	
			else
			{
			
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_codtipded;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_dentipded;
			
				ls_ejecucion=document.form1.hidstatus.value;
				if (ls_ejecucion=='1')
				{
				  opener.document.form1.hidstatus.value="C";
				}
				
				opener.document.form1.txtcodtipded.readOnly=true;
		
				
				if (ls_tipo!='2')
				{
				
				  opener.document.form1.operacion.value="BUSCARDETALLE";
				  opener.document.form1.action="../pantallas/sigesp_srh_d_configuracion_deduccion.php";
				  opener.document.form1.existe.value="TRUE";
				  opener.document.form1.hidguardar.value="modificar";			
				  opener.document.form1.submit();	
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

