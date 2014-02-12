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
<title>Cat&aacute;logo de Secciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_seccion.js"></script>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
   <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print ($ls_ejecucion)?>">
  </p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Secciones </td>
    </tr>
  </table>
<br>
    <table width="497" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td width="123"><div align="right">C&oacute;digo</div></td>
        <td height="22" colspan="4"><div align="left">
          <input name="txtcodsec" type="text" id="txtcodsec" onKeyPress=""  onKeyUp="javascript: Buscar()" >
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="4"><div align="left">          <input name="txtdensec" type="text" id="txtdensec" onKeyUp="javascript: Buscar()">
        </div></td>
      </tr>
       <tr>
        <td><div align="right">C&oacute;digo de Departamento</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtcoddep" size="16" type="text" id="txtcoddep" onChange="javascript: Buscar()" readonly>
        <a href="javascript:catalogo_departamento();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo Tipos de Documentos" name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
        </div></td>
        <td colspan="3"><input name="txtdendep" type="text" class="sin-borde" id="txtdendep"  size="40" maxlength="40" readonly>  </td>
      </tr>
	  
	  <tr>
	  <td>&nbsp;</td>
	  <td width="4">&nbsp;</td>
       <td width="130"><div align="right"></div></td>
       <td width="160"><div align="right"><a href="javascript: Limpiar_busqueda(); Buscar();"><img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
       
       <td width="80"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
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
  <br>
  
  </div>
<div id="mostrar" align="center" >
</div>
  

<script language="JavaScript">


    	
		var loadDataURL = "../../php/sigesp_srh_a_seccion.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_seccion.php";
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
			mygrid.setHeader("Codigo,Denominacion,Departamento");
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
	
		   codsec=document.form1.txtcodsec.value;
		   densec=document.form1.txtdensec.value;
		   coddep=document.form1.txtcoddep.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_seccion.php?valor=buscar"+"&txtcodsec="+codsec+"&txtdensec="+densec+"&txtcoddep="+coddep);
           setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codsec,ls_densec,ls_coddep,ls_dendep,ls_coddestino,ls_dendestino,ls_coddestino1,ls_dendestino1)
		{
			
			ls_ejecucion=document.form1.hidstatus.value;
			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codsec;
			obj1=eval("opener.document.form1."+ls_dendestino+"");
			obj1.value=ls_densec;
			obj2=eval("opener.document.form1."+ls_coddestino1+"");
			obj2.value=ls_coddep;
			obj3=eval("opener.document.form1."+ls_dendestino1+"");
			obj3.value=ls_dendep;
		
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
			}
			
			opener.document.form1.txtcodsec.readOnly=true;
			close();
		}
	
	
		function catalogo_departamento()
		{
		   f= document.form1;
	  
		   ls_depto = f.txtcoddep.value;			
		  
			pagina="sigesp_srh_cat_departamento.php";
			window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=no,location=no,dependent=yes");
			
	
		}
function Limpiar_busqueda () 
{
	document.form1.txtcodsec.value="";
	document.form1.txtdensec.value="";
	document.form1.txtcoddep.value="";
	document.form1.txtdendep.value="";
}
	


	</script>
</html>

