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
<title>Cat&aacute;logo de Permisos</title>
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
    <input name="operacion" type="hidden" id="operacion">
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
	<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Permisos </td>
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
	
 
</div>
</form>
<p>&nbsp;</p>

</body>

</html>

<script language="JavaScript">


        codper=document.form1.txtcodper.value;
        var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML_permisos";
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
			mygrid.setHeader("Código,Fecha Inicio,Fecha Fin,Tipo Permiso,Observacion");
			mygrid.setInitWidths("60,70,70,85,225")
			mygrid.setColAlign("center,center,center,left")
			mygrid.setColTypes("link,ro,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF")

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
		
		
		
		
	function aceptar(ls_numper,ls_feciniper,ls_fecfinper,ls_numdiaper,ls_afevacper,ls_tipper,ls_obsper,ls_remper,
				 ls_numperdestino,ls_feciniperdestino,ls_fecfinperdestino,ls_numdiaperdestino,ls_afevacperdestino,ls_tipperdestino,ls_obsperdestino,ls_remperdestino,ls_horper, ls_horperdestino)
	{
		
		
		  obj=eval("opener.document.form1."+ls_numperdestino+"");
		  obj.value=ls_numper;
	      obj1=eval("opener.document.form1."+ls_feciniperdestino+"");
	      obj1.value=ls_feciniper;
	   	  obj2=eval("opener.document.form1."+ls_fecfinperdestino+"");
		  obj2.value=ls_fecfinper;
		  obj3=eval("opener.document.form1."+ls_numdiaperdestino+"");
		  obj3.value=ls_numdiaper;
		  
		  obj4=eval("opener.document.form1."+ls_afevacperdestino+"");
		  if (ls_afevacper=='0')
		  {
		     obj4.checked=true;
		  }
		  else
		   {
		     obj4.checked=false;
		  }
		  obj5=eval("opener.document.form1."+ls_tipperdestino+"");
		  obj5.value=ls_tipper;
		  obj6=eval("opener.document.form1."+ls_obsperdestino+"");
		  obj6.value=ls_obsper;
		  
		  obj7_1=eval("opener.document.form1."+ls_remperdestino+"");
		  obj7_2=eval("opener.document.form1.chkremper2");
		   if (ls_remper=='1')
		  {
		     obj7_1.checked=true;
			 obj7_2.checked=false;
		  }
		  else
		   {
		     obj7_1.checked=false;
			 obj7_2.checked=true;
		  }
		  
		  obj8=eval("opener.document.form1."+ls_horperdestino+"");
		  obj8.value=ls_horper;
		  
		  opener.document.form1.hidguardar_per.value='modificar';

			close();
	}
		
		
	
</script>