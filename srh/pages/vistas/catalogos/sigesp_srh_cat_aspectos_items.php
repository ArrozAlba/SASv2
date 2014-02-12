<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{$ls_ejecucion="";}
	
	if (isset($_GET["codeval"]))
	{ $ls_codeval=$_GET["codeval"];	}
	else
	{$ls_codeval="";}

	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Aspectos de Evaluaci&oacute;n</title>


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

    <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">
	<input name="hidcodeval" type="hidden" id="hidcodeval" value="<?php print $ls_codeval?>">
    <input name="hidcontrol2" type="hidden" id="hidcontrol2" value="2">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Aspectos de Evaluaci&oacute;n </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="146"><div align="right">C&oacute;digo Aspecto</div></td>
        <td width="354" height="22"><div align="left">
          <input name="txtcodasp" type="text" id="txtcodasp" size="16" onkeyup="javascript: ue_validarnumero(this);" >
        </div></td>
      </tr>
      
      <tr>
        <td width="146"><div align="right">Denominaci&oacute;n Aspecto</div></td>
        <td width="354" height="22"><div align="left">
          <input name="txtdenasp" type="text" id="txtdenasp" size="40" onKeyUp="javascript: Buscar()">
        </div></td>
     </tr>    
       <tr>
        <td>&nbsp;</td>
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


</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">



	

function Limpiar_busqueda () 
{
	document.form1.txtcodasp.value="";
	document.form1.txtdenasp.value="";

	
}

        codeval=document.form1.hidcodeval.value;
        var loadDataURL = "../../php/sigesp_srh_a_aspectos.php?valor=createXML_aspectos"+"&codeval="+codeval;
		var actionURL = "../../php/sigesp_srh_a_aspectos.php";
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
			mygrid.setHeader("Codigo,Denominaci&oacute;n,Tipo Evaluaci&oacute;n");
			mygrid.setInitWidths("120,230,160")
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
	
		   codasp=document.form1.txtcodasp.value;
		   denasp=document.form1.txtdenasp.value;
		   codeval=document.form1.hidcodeval.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_aspectos.php?valor=buscar_aspectos"+"&txtcodasp="+codasp+"&txtdenasp="+denasp+"&codeval="+codeval);
 		  setTimeout (terminar_buscar,500);
			
		}
	function aceptar(ls_codasp,ls_denasp,ls_coddestino,ls_dendestino)
	{
		
		
		
		  obj=eval("opener.document.form1."+ls_coddestino+"");
		  obj.value=ls_codasp;
	      obj1=eval("opener.document.form1."+ls_dendestino+"");
	      obj1.value=ls_denasp;

	 
		
        ls_ejecucion=document.form1.hidstatus.value;
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
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
