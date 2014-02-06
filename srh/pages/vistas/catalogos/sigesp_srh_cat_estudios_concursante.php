<?php
session_start();

if (isset($_GET["valor_cat"]))
{
	$ls_ejecucion=$_GET["valor_cat"];	
}	
	
if (isset($_GET["codper"]))
{ 
	$ls_codper=$_GET["codper"];	
}

if (isset($_GET["codcon"]))
{ 
	$ls_codcon=$_GET["codcon"];	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Formaci&oacute;n Acad&eacute;mica / Profesional</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_inscripcion_concurso.js"></script>


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
    <input name="hidstatus" type="hidden" id="hidstatus">
	
  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Formaci&oacute;n Acad&eacute;mica / Profesional</td>
    </tr>
	 
  </table>
  <br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td width="273" colspan="2">&nbsp;</td>
      </tr>
       <tr>
        <td width="227"><div align="right">C&oacute;digo Concursante </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper?>"  size=16 readonly style="text-align:center">
        </div></td>
      </tr>
	  <tr>
        <td width="227"><div align="right">C&oacute;digo Concurso </div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodcon" type="text" id="txtcodcon" value="<?php print $ls_codcon?>"  size=16 readonly style="text-align:center">
        </div></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td width="273" colspan="2">&nbsp;</td>
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
		codcon=document.form1.txtcodcon.value;
        var loadDataURL = "../../php/sigesp_srh_a_inscripcion_concurso.php?valor=createXML_estudios";
		var actionURL = "../../php/sigesp_srh_a_inscripcion_concurso.php";
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
			mygrid.setHeader("Codigo,Nivel,Carrera");
			mygrid.setInitWidths("100,150,250")
			mygrid.setColAlign("center,center,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF")

			mygrid.loadXML(loadDataURL+"&codper="+codper+"&codcon="+codcon);
			mygrid.setSkin("xp");
			mygrid.init();
            setTimeout (terminar_buscar,500);
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }	
		
		
		
		
	function aceptar(ls_codestper,  ls_tipestarea,  ls_insestper,  ls_carestper,  ls_titestper,  ls_anofin, ls_anoapr,  ls_codestperdestino,  ls_tipestareadestino,  ls_insestperdestino,  ls_carestperdestino,  ls_titestperdestino,  ls_anofindestino, ls_anoaprdestino)
	{
		
		
		  obj=eval("opener.document.form1."+ls_codestperdestino+"");
		  obj.value=ls_codestper;
	      obj1=eval("opener.document.form1."+ls_tipestareadestino+"");
	      obj1.value=ls_tipestarea;
	   	  obj2=eval("opener.document.form1."+ls_insestperdestino+"");
		  obj2.value=ls_insestper;
		  obj3=eval("opener.document.form1."+ls_carestperdestino+"");
		  obj3.value=ls_carestper;
		  obj4=eval("opener.document.form1."+ls_titestperdestino+"");
		  if ((ls_titestper==1) || (ls_titestper=='1')) 
		  {
		  	obj4.checked=true;
		  }
		  
		  obj5=eval("opener.document.form1."+ls_anofindestino+"");
		  obj5.value=ls_anofin;
		  obj6=eval("opener.document.form1."+ls_anoaprdestino+"");
		  obj6.value=ls_anoapr;
		  
	      opener.document.form1.hidguardar_est.value='modificar';
		  close();
	}
		
		
	
</script>