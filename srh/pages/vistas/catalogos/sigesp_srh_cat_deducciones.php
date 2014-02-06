<?php
session_start();

if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}	
	
if (isset($_GET["codper"]))
	{ $ls_codper=$_GET["codper"];	}	
	
	
if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];		
	}
   else { $ls_tipo="";}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Deduccion de Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_personal.js"></script>


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

<body onLoad="javascript: doOnLoad();">
<form name="form1" method="post" action="">
  <p align="center">


	<input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion?>" >
	<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Deducciones de Personal</td>
    </tr>
	 
  </table>
  <br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td width="273" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td width="227"><div align="right">C&oacute;digo Personal</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper?>"  size=16 readonly style="text-align:center">
        </div></td>
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
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="600" height="800" style="background-position:center"></div>
		
		</td>
	  </tr>
		
	  
    </table>
	
 
</form>
<p>&nbsp;</p>

</body>

</html>

<script language="JavaScript">


        codper=document.form1.txtcodper.value;
        var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML_deducciones";
		var actionURL = "../../php/sigesp_srh_a_personal.php";
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
			mygrid.setHeader("Codigo,Descripcion");
			mygrid.setInitWidths("120,380")
			mygrid.setColAlign("center,center")
			mygrid.setColTypes("link,ro");
			mygrid.setColSorting("str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF")

			mygrid.loadXML(loadDataURL+"&codper="+codper);
			mygrid.setSkin("xp");
			mygrid.init();
            setTimeout (terminar_buscar,500);
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }	
		
		
		
		
	function aceptar(ls_codtipded,  ls_dentipded, ls_codtipdeddestino, ls_dentipdeddestino,ls_coddet, ls_coddetdestino)
	{
			
		 ls_tipo = document.form1.hidtipo.value;
		if (ls_tipo=='2')
		{
		  obj=eval("opener.document.form1.txtcodtipded1");
		  obj.value=ls_codtipded;
		  obj1=eval("opener.document.form1.txtdentipded1");
		  obj1.value=ls_dentipded;
		  close();
		}
		else
		{	
		
		  obj=eval("opener.document.form1."+ls_codtipdeddestino+"");
		  obj.value=ls_codtipded;
	      obj1=eval("opener.document.form1."+ls_dentipdeddestino+"");
	      obj1.value=ls_dentipded;
		  obj2=eval("opener.document.form1."+ls_coddetdestino+"");
	      obj2.value=ls_coddet;		
	      calcular_monto (ls_coddet);	   	
	      opener.document.form1.hidguardar_deducc.value='modificar';
		  setTimeout(close,2000);
	  }
		  
	}


function calcular_monto (coddettipded)
 {
	function onConsultar(respuesta)
	{
		opener.document.form1.txtmontod.value  = uf_convertir(trim(respuesta.responseText));
	
	 }	
	  codper=opener.document.form1.txtcodper.value;	  
	  codtipded=opener.document.form1.txtcodtipded.value;
	  params = "operacion=calcular_monto_deduccion&codper="+codper+"&codtipded="+codtipded+"&coddettipded="+coddettipded;
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onConsultar});
		
	 
}




		
		
	
</script>