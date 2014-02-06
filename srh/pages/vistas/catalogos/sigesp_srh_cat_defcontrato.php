<?php
session_start();
if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
else {$ls_ejecucion="";}

if (isset($_GET["tipo"]))
	{ $ls_tipo=$_GET["tipo"];	}
else {$ls_tipo="";}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Configuraci&oacute;n de Contratos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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
   </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Configuraci&oacute;n de Contratos </td>
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
          <input name="txtcodcont" type="text" id="txtcodcont" onKeyUp="javascript: Buscar();">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Descripci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdescont" size="40" type="text" id="txtdescont" onKeyUp="javascript: Buscar();">
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

  <br>


</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script>
	
		var loadDataURL = "../../php/sigesp_srh_a_defcontrato.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_defcontrato.php";
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
			mygrid.setHeader("Codigo,Denominación,Nombre del Archivo RTF");
			mygrid.setInitWidths("75,250,175")
			mygrid.setColAlign("center,center,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF")

			mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init()
            setTimeout (terminar_buscar,500);
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }	
		
		
		
		function Buscar()
		{
	
		   codcont=document.form1.txtcodcont.value;
		   descont=document.form1.txtdescont.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_area.php?valor=buscar"+"&txtcodcont="+codcont+"&txtdescont="+descont);
           setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codcont, ls_descont, ls_tamletcont,ls_tamletpiecont, ls_intlincont, ls_marsupcont, ls_marinfcont, ls_titcont, ls_arcrtfcont, ls_concont, ls_piepagcont, ls_codcontdestino, ls_descontdestino, ls_tamletcontdestino, ls_tamletpiecontdestino, ls_intlincontdestino, ls_marsupcontdestino, ls_marinfcontdestino, ls_titcontdestino, ls_arcrtfcontdestino, ls_concontdestino, ls_piepagcontdestino)
		
		{
		
			ls_tipo=document.form1.hidtipo.value;
		 	if (ls_tipo=='1')
		 	{
			   obj=eval("opener.document.form1."+ls_codcontdestino+"");
				obj.value=ls_codcont;
			
				obj1=eval("opener.document.form1."+ls_descontdestino+"");
				obj1.value=ls_descont;
				
				obj2=eval("opener.document.form1."+ls_arcrtfcontdestino+"");
				obj2.value=ls_arcrtfcont;
			}
			else
			{
			
				obj=eval("opener.document.form1."+ls_codcontdestino+"");
				obj.value=ls_codcont;
				
				obj1=eval("opener.document.form1."+ls_descontdestino+"");
				obj1.value=ls_descont;
				
				obj2=eval("opener.document.form1."+ls_tamletcontdestino+"");
				obj2.value=ls_tamletcont;
				
				obj3=eval("opener.document.form1."+ls_tamletpiecontdestino+"");
				obj3.value=ls_tamletpiecont;
				
				obj4=eval("opener.document.form1."+ls_intlincontdestino+"");
				obj4.value=ls_intlincont;
				
				obj5=eval("opener.document.form1."+ls_marsupcontdestino+"");
				obj5.value=ls_marsupcont;
				
				obj6=eval("opener.document.form1."+ls_marinfcontdestino+"");
				obj6.value=ls_marinfcont;
				
				obj7=eval("opener.document.form1."+ls_titcontdestino+"");
				obj7.value=ls_titcont;
				
				obj8=eval("opener.document.form1."+ls_arcrtfcontdestino+"");
				obj8.value=ls_arcrtfcont;
				
				obj9=eval("opener.document.form1."+ls_concontdestino+"");
				obj9.value=ls_concont;
				
				obj10=eval("opener.document.form1."+ls_piepagcontdestino+"");
				obj10.value=ls_piepagcont;
				
				ls_ejecucion=document.form1.hidstatus.value;
				if (ls_ejecucion=='1')
				{
				  opener.document.form1.hidguardar.value="modificar";
				}
				else
				{
				 opener.document.form1.hidguardar.value="incluir";
				}
	
				opener.document.form1.txtcodcont.readOnly=true;
				
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

