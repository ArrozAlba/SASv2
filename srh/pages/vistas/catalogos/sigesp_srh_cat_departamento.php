<?php
session_start();
if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
else
	{$ls_ejecucion="";}
	
if (isset($_GET["codger"]))
	{ $ls_codger=$_GET["codger"]; }
else
	{$ls_codger="";}
	
if (isset($_GET["tipo"]))
	{ $ls_tipo=$_GET["tipo"]; }
else
	{$ls_tipo="";}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Departamento</title>

<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_departamento.js"></script>
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
        <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print ($ls_ejecucion)?>">
		<input name="hidcoduni" type="hidden" id="hidcoduni" value="<?php print ($ls_codger)?>">
		<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print ($ls_tipo)?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Departamentos </td>
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
          <input name="txtcoddep" type="text" id="txtcoddep" onKeyPress="" onKeyUp="javascript: Buscar()" >
        </div></td>
      </tr>
      
      <tr>
        <td width="110"><div align="right">Denominaci&oacute;n</div></td>
        <td width="388" height="22"><div align="left">
          <input name="txtdendep" type="text" id="txtdendep" onKeyUp="javascript: Buscar()">
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
	
		ls_codger=document.form1.hidcoduni.value;
		var loadDataURL = "../../php/sigesp_srh_a_departamento.php?valor=createXML&codger="+ls_codger;
		var actionURL = "../../php/sigesp_srh_a_departamento.php";
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
			mygrid.setHeader("Codigo,Denominacion,Unidad Administrativa");
			mygrid.setInitWidths("118,208,180")
			mygrid.setColAlign("center,center,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str","str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF");
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
	
		   coddep=document.form1.txtcoddep.value;
		   dendep=document.form1.txtdendep.value;
		   
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_departamento.php?valor=buscar"+"&txtcoddep="+coddep+"&txtdendep="+dendep);
           setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_coddep,ls_dendep,ls_coddestino,ls_dendestino, ls_coduniadm, ls_coduniadmdestino, ls_denuniadm, ls_denuniadmdestino, ls_codger,ls_denger,ls_codgerdestino,ls_dengerdestino)
		{
			
			
			tipo=document.form1.hidtipo.value;
			if (tipo=='1')
			{
			
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_coddep;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_dendep;
			}
			else
			{
			
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_coddep;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_dendep;
				obj2=eval("opener.document.form1."+ls_coduniadmdestino+"");
				obj2.value=ls_coduniadm;
				
				obj3=eval("opener.document.form1."+ls_denuniadmdestino+"");
				obj3.value=ls_denuniadm;
			
				obj4=eval("opener.document.form1."+ls_codgerdestino+"");
				obj4.value=ls_codger;
				
				obj5=eval("opener.document.form1."+ls_dengerdestino+"");
				obj5.value=ls_denger;
				
				ls_ejecucion=document.form1.hidstatus.value;
				if (ls_ejecucion=='1')
				{
				  opener.document.form1.hidstatus.value="C";
				}
				opener.document.form1.txtcoddep.readOnly=true;
			}
			close();
		}
	    
		function aceptar_personal (ls_coddep,ls_dendep)
		{
			obj=eval("opener.document.form1.txtcoddep");
			obj.value=ls_coddep;
			obj=eval("opener.document.form1.txtdendep");
			obj.value=ls_dendep;
			close();
		}
	
		function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}


	</script>
	
</html>

