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
<title>Cat&aacute;logo  de Tipo de Accidente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/sigesp_srh_js_tipoaccidente.js"></script>
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

<body  onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
   <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo  de Tipo de Accidente</td>
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
          <input name="txtcodacc" type="text" id="txtcodacc"  onKeyUp="javascript: Buscar()" >
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenacc" type="text" id="txtdenacc" size="60" maxlength="254" onKeyUp="javascript: Buscar()">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right">
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


		var loadDataURL = "../../php/sigesp_srh_a_tipoaccidente.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_yipoaccidente.php";
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
	
		   codacc=document.form1.txtcodacc.value;
		   denacc=document.form1.txtdenacc.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_tipoaccidente.php?valor=buscar"+"&txtcodacc="+codacc+"&txtdenacc="+denacc);
           setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codacc,ls_denacc,ls_coddestino,ls_dendestino)
		{
			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codacc;
			obj1=eval("opener.document.form1."+ls_dendestino+"");
			obj1.value=ls_denacc;
		
			ls_ejecucion=document.form1.hidstatus.value;
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
			}
			opener.document.form1.txtcodacc.readOnly=true;
			close();
		}
	
	
		function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}


	</script>
	
</html>

