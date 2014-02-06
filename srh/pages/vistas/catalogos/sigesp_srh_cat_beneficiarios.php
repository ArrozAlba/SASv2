<?php
session_start();
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	
	if (isset($_GET["codper"]))
	{ $ls_codper=$_GET["codper"];	}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Beneficiarios</title>
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

<body onload="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
    <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Beneficiarios </td>
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
        <td align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="600" style="background-position:center"></div></td>
      </tr>
	  
    </table>
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

<script language="JavaScript">

		codper=document.form1.txtcodper.value;
        var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML_beneficiarios";
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
			mygrid.setHeader("Código,Cédula,Nombre,Tipo Beneficiario");
			mygrid.setInitWidths("70,90,200,140");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
			mygrid.loadXML(loadDataURL+"&codper="+codper);
			mygrid.setSkin("xp");
			mygrid.init();
			setTimeout (terminar_buscar,650);

		}
		
	
function terminar_buscar ()
{ 
   divResultado = document.getElementById('mostrar');
   divResultado.innerHTML= '';   
}


		
function aceptar (ls_codben,ls_cedben,ls_nomben,ls_apeben,ls_nacben,ls_dirben,ls_telben, ls_tipben,ls_porpagben,ls_monpagben,ls_forpagben,ls_nomcheben,ls_codban,ls_nomban,ls_ctaban,ls_tipcueben,ls_codbendestino ,ls_cedbendestino ,ls_nombendestino,ls_apebendestino,ls_nacbendestino,ls_dirbendestino,ls_telbendestino , ls_tipbendestino , ls_porpagbendestino , ls_monpagbendestino ,ls_forpagbendestino, ls_nomchebendestino, ls_codbandestino, ls_nombandestino, ls_ctabandestino, ls_tipcuebendestino, ls_nexben, ls_nexbendestino, ls_cedaut, ls_cedautdestino, ls_numexpben, ls_numexpbendestino)
	{
		
		obj=eval("opener.document.form1."+ls_codbendestino+"");
		obj.value=ls_codben;
		
		obj2=eval("opener.document.form1."+ls_cedbendestino+"");
		obj2.value=ls_cedben;
		
		obj3=eval("opener.document.form1."+ls_nombendestino+"");
		obj3.value=ls_nomben;
		
			
		obj4=eval("opener.document.form1."+ls_apebendestino+"");
		obj4.value=ls_apeben;	

		obj5=eval("opener.document.form1."+ls_nacbendestino+"");
		obj5.value=ls_nacben;

		
		obj7=eval("opener.document.form1."+ls_dirbendestino+"");
		obj7.value=ls_dirben;
		
				
		obj8=eval("opener.document.form1."+ls_telbendestino+"");
		obj8.value=ls_telben;
		
		obj9=eval("opener.document.form1."+ls_tipbendestino+"");
		obj9.value=ls_tipben;
		
		obj10=eval("opener.document.form1."+ls_porpagbendestino+"");
		obj10.value=ls_porpagben;
	
		obj11=eval("opener.document.form1."+ls_monpagbendestino+"");
		obj11.value=ls_monpagben;
		
		obj12=eval("opener.document.form1."+ls_forpagbendestino+"");
		obj12.value=ls_forpagben;
	
		obj13=eval("opener.document.form1."+ls_nomchebendestino+"");
		obj13.value=ls_nomcheben;
	
		obj14=eval("opener.document.form1."+ls_codbandestino+"");
		obj14.value=ls_codban;
		
		obj15=eval("opener.document.form1."+ls_nombandestino+"");
		obj15.value=ls_nomban;
		
		obj16=eval("opener.document.form1."+ls_ctabandestino+"");
		obj16.value=ls_ctaban;
		
		obj17=eval("opener.document.form1."+ls_tipcuebendestino+"");
		obj17.value=ls_tipcueben;
		
		obj18=eval("opener.document.form1."+ls_nexbendestino+"");
		obj18.value=ls_nexben;
		
		obj19=eval("opener.document.form1."+ls_cedautdestino+"");
		obj19.value=ls_cedaut;
		
		obj20=eval("opener.document.form1."+ls_numexpbendestino+"");
		obj20.value=ls_numexpben;
					 
		 opener.document.form1.cmbtipben.disabled="disabled";
		 opener.document.form1.hidguardar_ben.value='modificar';
		
			
	    close ();

}

	


</script>