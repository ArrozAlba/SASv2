<?php
session_start();

if(array_key_exists("tipo",$_GET))
	{
		$ls_tipo=$_GET["tipo"];
	}
	
	
if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}	
	
if (isset($_GET["codper"]))
	{ $ls_codper=$_GET["codper"];	}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Estudios Realizados</title>
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
    <input name="hidstatus" type="hidden" id="hidstatus">
	<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Estudios Realizados</td>
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
        var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML_estudios";
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
			mygrid.setHeader("Codigo,Descripcion,Titulo Obtenido");
			mygrid.setInitWidths("100,250,160")
			mygrid.setColAlign("center,center,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF")

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
		
		
		
		
	function aceptar(ls_codestrea,  ls_tipestarea,  ls_insestrea,  ls_desestrea,  ls_titestrea,  ls_calestrea, ls_esceval, ls_aprestarea, ls_anoaprestrea, ls_horestrea,  ls_feciniact, ls_fecfinact, ls_fecgraestrea,
				  ls_codestreadestino,  ls_tipestareadestino,  ls_insestreadestino,  ls_desestreadestino,  ls_titestreadestino,  ls_calestreadestino, ls_escevaldestino, ls_aprestareadestino, ls_anoaprestreadestino,
				   ls_horestreadestino, ls_feciniactdestino, ls_fecfinactdestino, ls_fecgraestreadestino)
	{
		
		
		  obj=eval("opener.document.form1."+ls_codestreadestino+"");
		  obj.value=ls_codestrea;
	      obj1=eval("opener.document.form1."+ls_tipestareadestino+"");
	      obj1.value=ls_tipestarea;
	   	  obj2=eval("opener.document.form1."+ls_insestreadestino+"");
		  obj2.value=ls_insestrea;
		  obj3=eval("opener.document.form1."+ls_desestreadestino+"");
		  obj3.value=ls_desestrea;
		  obj4=eval("opener.document.form1."+ls_titestreadestino+"");
		  obj4.value=ls_titestrea;
		  obj5=eval("opener.document.form1."+ls_calestreadestino+"");
		  obj5.value=ls_calestrea;
		  obj6=eval("opener.document.form1."+ls_escevaldestino+"");
		  obj6.value=ls_esceval;
		  obj7=eval("opener.document.form1."+ls_aprestareadestino+"");
		  obj7.value=ls_aprestarea;
		  obj8=eval("opener.document.form1."+ls_anoaprestreadestino+"");
		  obj8.value=ls_anoaprestrea;
		  obj9=eval("opener.document.form1."+ls_horestreadestino+"");
		  obj9.value=ls_horestrea;
		  obj10=eval("opener.document.form1."+ls_feciniactdestino+"");
		  obj10.value=ls_feciniact;
	      obj11=eval("opener.document.form1."+ls_fecfinactdestino+"");
		  obj11.value=ls_fecfinact;
		  obj12=eval("opener.document.form1."+ls_fecgraestreadestino+"");
		  obj12.value=ls_fecgraestrea;
	
	      opener.document.form1.hidguardar_est.value='modificar';
		  close();
	}
		
		
	
</script>