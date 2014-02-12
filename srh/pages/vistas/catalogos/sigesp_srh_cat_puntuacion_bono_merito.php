<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	else
	{ $ls_ejecucion="";	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Puntuacir&oacute;n Bono M&eacute;rito </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_puntuacion_bono_merito.js"></script>
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

    <input name="hidstatus" type="hidden" id="hidstatus" value= "<?php print $ls_ejecucion; ?>">
 
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de  Puntuaci&oacute;n Bono M&eacute;rito </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="86"><div align="right">C&oacute;digo</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodpunt" type="text" id="txtcodpunt" onKeyUp="javascript: Buscar()" >
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnombpunt" type="text" id="txtnombpunt" size="60" maxlength="254" onKeyUp="javascript: Buscar()" >
        </div></td>
      </tr>
        <tr>
    <td height="28"><div align="right">Tipo Personal</div></td>
    <td height="28" colspan="2"><input name="txtcodtipper" type="text" id="txtcodtipper"    size="5" maxlength="3" readonly> <a href="javascript:catalogo_tipo_personal();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a>
      <input name="txtdentipper" type="text" class="sin-borde" id="txtdentipper"  size="45" maxlength="80" readonly></td>      
		</tr>
	 <tr>
	  <td>&nbsp;</td>
	  <td width="53">&nbsp;</td>
       <td width="299"><div align="right"><a href="javascript: Limpiar_busqueda(); Buscar();">  <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
       <td width="62"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
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

</body>
<script>


function catalogo_tipo_personal()
{	
f=document.form1;		
	  pagina="../catalogos/sigesp_srh_cat_tipopersonal.php?valor_cat=0";
	  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}
	
		var loadDataURL = "../../php/sigesp_srh_a_puntuacion_bono_merito.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_puntuacion_bono_merito.php";
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
			mygrid.setHeader("Codigo,Denominacion,Tipo Personal, Escala");
			mygrid.setInitWidths("115,190,105,90")
			mygrid.setColAlign("center,center,center,center")
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF")

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
	
		   codpunt=document.form1.txtcodpunt.value;
		   nombpunt=document.form1.txtnombpunt.value;
		   codtipper=document.form1.txtcodtipper.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_puntuacion_bono_merito.php?valor=buscar"+"&txtcodpunt="+codpunt+"&txtnombpunt="+nombpunt+"&txtcodtipper="+codtipper);
		   setTimeout (terminar_buscar,500);
			
		}
		
		function aceptar(ls_codpunt,ls_nombpunt,ls_coddestino,ls_nombdestino,ls_despunt,ls_desdestino,li_valini, li_valfin, li_valinidestino,li_valfindestino,ls_codtipper, ls_dentipper,ls_codtipperdestino, ls_dentipperdestino,ls_ejecucion)
	{
		
		
		if (opener.document.form1.hidcontrol.value=="1")
		{
		  num=opener.document.form1.totalfilas.value;
		  obj=eval("opener.document.form1."+ls_coddestino+num+"");
		  obj.value=ls_codpunt;
		  obj1=eval("opener.document.form1."+ls_nombdestino+num+"");
		  obj1.value=ls_nombpunt;
		  obj1=eval("opener.document.form1."+li_valinidestino+num+"");
		  obj1.value=(li_valini +' / '+ li_valfin);
		
		}
		else {
		 obj=eval("opener.document.form1."+ls_coddestino+"");
		 obj.value=ls_codpunt;
		 obj1=eval("opener.document.form1."+ls_nombdestino+"");
		 obj1.value=ls_nombpunt;
		 obj1=eval("opener.document.form1."+ls_desdestino+"");
		 obj1.value=ls_despunt;
		 obj1=eval("opener.document.form1."+li_valinidestino+"");
		 obj1.value=li_valini;
		 obj1=eval("opener.document.form1."+li_valfindestino+"");
		 obj1.value=li_valfin;
		 obj1=eval("opener.document.form1."+ls_codtipperdestino+"");
	     obj1.value=ls_codtipper;
		 obj1=eval("opener.document.form1."+ls_dentipperdestino+"");
		 obj1.value=ls_dentipper;
		 
		 opener.document.form1.txtcodpunt.readOnly=true;
		}
			ls_ejecucion=document.form1.hidstatus.value;
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
			}
			
			close();
		}
	
function Limpiar_busqueda () {
 document.form1.txtcodpunt.value ="";
 document.form1.txtnombpunt.value ="";
 document.form1.txtcodtipper.value ="";
 document.form1.txtdentipper.value ="";
 }

	</script>
	
</html>


