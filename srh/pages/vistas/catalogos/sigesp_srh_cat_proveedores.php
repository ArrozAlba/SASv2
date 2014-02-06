<?php
session_start();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Proveedores</title>

<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
   

 
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Proveedores</td>
    </tr>
  </table>
<br>




  <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="95"><div align="right">C&oacute;digo</div></td>
        <td width="193" height="22"><div align="left">
          <input name="txtcodprov" type="text" id="txtcodprov"  onKeyUp="javascript: ue_validarnumero(this);" >
        </div></td>
      </tr>
      
      <tr>
        <td width="95"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="42"><div align="left">
          <input name="txtdenprov" type="text" id="txtdenprov"  size="40">
        </div></td>
     </tr>    
      
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
            </tr>
			<tr>
    <td>&nbsp;</td>
   
    <td width="193"><div align="right"><a href="javascript: Limpiar_busqueda();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="212"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
    
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
	

		var loadDataURL = "../../php/sigesp_srh_a_solicitud_adiestramiento.php?valor=createXML_proveedor";
		var actionURL = "../../php/sigesp_srh_a_solicitud_adiestramiento.php";
		//var authorsURL = "ajax_folder/sigesp_srh_a_solicitud_adiestramiento.php.php?type=getAuthorsList"
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
			
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Codigo,Denominacion");
			mygrid.setInitWidths("120,380");
			mygrid.setColAlign("center,center");
			mygrid.setColTypes("link,ro");
			mygrid.setColSorting("str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF");

		//	mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
	
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }
		
		
function Limpiar_busqueda () 
{
	document.form1.txtcodprov.value="";
	document.form1.txtdenprov.value="";
	
}		
		
		function Buscar()
		{
	
		   codprov=document.form1.txtcodprov.value;
		   denprov=document.form1.txtdenprov.value;
		   
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_solicitud_adiestramiento.php?valor=buscar_proveedor"+"&txtcodprov="+codprov+"&txtdenprov="+denprov);
		   setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codprov,ls_denprov,ls_coddestino,ls_dendestino)
		{
		
			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codprov;
			obj1=eval("opener.document.form1."+ls_dendestino+"");
			obj1.value=ls_denprov;
		
			
			opener.document.form1.txtcodprov.readOnly=true;
			close();
		}
	
	
		function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}


	</script>
	
</html>

