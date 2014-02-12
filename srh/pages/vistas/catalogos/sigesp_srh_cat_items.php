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
<title>Cat&aacute;logo de Aspectos de Evaluaci&oacute;n con Items Definidos</title>

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
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
  <input name="hidcontrol" type="hidden" id="hidcontrol" value="1">
    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print ($ls_ejecucion); ?>">
    <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Aspectos de Evaluaci&oacute;n con Items Definidos </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="105">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>      
      <tr>
    <td><div align="right">Tipo de Evaluaci&oacute;n</div></td>
    <td width="173" height="22"><div align="left">
      <input name="txtcodeval" size="16" type="text" id="txtcodeval"   readonly >
      <a href="javascript:catalogo_tipoevaluacion();"><img src="../../../../shared/imagebank/tools15/buscar.gif" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </div></td> 
    <td colspan="2"><input name="txtdeneval" type="text" class="sin-borde" id="txtdeneval"  size="37" maxlength="40" readonly>
    </td>
  </tr>
  <tr>
    <td><div align="right">Aspecto de Evaluaci&oacute;n</div></td>
    <td width="173" height="22"><div align="left">
      <input name="txtcodasp" size="16" type="text" id="txtcodasp"   readonly >
      <a href="javascript:catalogo_aspectos();"><img src="../../../../shared/imagebank/tools15/buscar.gif" name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </div></td> 
    <td colspan="2"><input name="txtdenasp" type="text" class="sin-borde" id="txtdenasp"  size="37" maxlength="40" readonly>
    </td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
    <td width="173"><div align="right"><a href="javascript: Limpiar_busqueda(); "> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="222"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
    
  </tr>

    </table>
	
	<div align="center" id="mostrar" class="oculto1"></div>  
	
<table width="600" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      
      <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
       <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="600" height="600" style="background-position:center"></div></td>
      </tr>
    </table>


</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">



function catalogo_tipoevaluacion()
{
       f= document.form1;
	   pagina="../catalogos/sigesp_srh_cat_tipoevaluacion.php?valor_cat=0";
	   window.open(pagina,"catalogo3","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}





function catalogo_aspectos()
  {
       f= document.form1;
       pagina="../catalogos/sigesp_srh_cat_aspectos.php?valor_cat=0";
	   window.open(pagina,"catalogo22","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}

function Limpiar_busqueda () 
{
	
	document.form1.txtcodeval.value="";
	document.form1.txtdeneval.value="";
	document.form1.txtcodasp.value="";
	document.form1.txtdenasp.value="";
	
}


		var loadDataURL = "../../php/sigesp_srh_a_items.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_items.php";
	    var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
		var mygrid;
		var timeoutHandler;//update will be sent automatically to server if row editting was stoped;
		var rowUpdater;//async. Calls doUpdateRow function when got data from server
		var rowEraser;//async. Calls doDeleteRow function when got confirmation about row deletion
		var authorsLoader;//sync. Loads list of available authors from server to populate dropdown (co)
		var mandFields = [0,1,1,0,0];
		
		
	//initialise (from xml) and populate (from xml) grid
		function doOnLoad()
		{
			
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Codigo Evaluación,Evaluación,Código Aspecto,Aspecto");
			mygrid.setInitWidths("115,175,115,175")
			mygrid.setColAlign("center,center,center,center")
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF")

		//	mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
			
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }

			
	
		
		
		
function Buscar()
		{
	
		   codasp=document.form1.txtcodasp.value;
		   codeval=document.form1.txtcodeval.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_items.php?valor=buscar"+"&txtcodeval="+codeval+"&txtcodasp="+codasp);
           setTimeout (terminar_buscar,500);
		}
		
		
function aceptar (ls_codeval,ls_deneval,ls_codasp,ls_denasp,ls_codevaldestino,ls_denevaldestino, ls_codaspdestino, ls_denaspdestino)
	{
		
		  obj1=eval("opener.document.form1."+ls_codevaldestino+"");
		  obj1.value=ls_codeval;
		  obj1=eval("opener.document.form1."+ls_denevaldestino+"");
		  obj1.value=ls_deneval;
		  
		  obj1=eval("opener.document.form1."+ls_codaspdestino+"");
	      obj1.value=ls_codasp;
		  obj1=eval("opener.document.form1."+ls_denaspdestino+"");
	      obj1.value=ls_denasp;
		  
		  
		  ls_ejecucion=document.form1.hidstatus.value;
		  if(ls_ejecucion=="1")
			{	
			opener.document.form1.hidstatus.value="C";
			opener.document.form1.hidguardar.value="modificar";			
			}
		  
		opener.document.form1.operacion.value="BUSCARDETALLE";
		opener.document.form1.action="../pantallas/sigesp_srh_d_items.php";
		opener.document.form1.existe.value="TRUE";		
		opener.document.form1.submit();	
			
	   close();
	}
		

function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
	
</script>
</html>
