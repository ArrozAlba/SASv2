<?php
session_start();
	if(array_key_exists("tipo",$_GET))
	{
	  $ls_tipo=$_GET["tipo"];	  
	}
	else
	{
		  $ls_tipo="";	  
	}
	if (isset($_GET["valor_cat"]))
	{ 
	   $ls_ejecucion=$_GET["valor_cat"];	
	}
	else {$ls_ejecucion="";}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Concursos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="javascript" src="../../../../shared/js/js_intra/datepickercontrol.js"></script>

<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_concurso.js"></script>



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
    <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print trim($ls_tipo)?>">  
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Concursos </td>
    </tr>
  </table>
<br>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="123"><div align="right">C&oacute;digo</div></td>
    <td height="22" colspan="2"><div align="left">
      <input name="txtcodcon" type="text" id="txtcodcon" size="16" onKeyUp="javascript:ue_validarnumero(this);" >
    </div></td>
  </tr>
  <tr>
    <td><div align="right">Descripci&oacute;n</div></td>
    <td height="22" colspan="2"><div align="left">
      <input name="txtdescon" type="text" id="txtdescon" size=40 onKeyUp="javascript: Buscar()" >
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Fecha Apertura</div></td>
    <td width="171" height="28" valign="middle"><input name="txtfechaaper" type="text" id="txtfechaaper"  size="16"   style="text-align:center"  readonly >
        <input name="reset" type="reset" onClick="return showCalendar('txtfechaaper', '%d/%m/%Y');" value=" ... " >
    </td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Fecha Cierre</div></td>
    <td height="28" valign="middle"><input name="txtfechacie" type="text" id="txtfechacie"  
  size="16"  style="text-align:center" readonly >
        <input name="reset" type="reset" onClick="return showCalendar('txtfechacie', '%d/%m/%Y');" value=" ... " >
     </td>
  </tr>
  <tr class="formato-blanco">
    <td height="28"><div align="right">Estado</div></td>
    <td height="28" valign="middle"><label>
      <select name="comboestatus" id="comboestatus" onChange="javascript: Buscar()" >
        <option value="--Seleccione--">--Seleccione--</option>
        <option value="Abierto">Abierto</option>
        <option value="Cerrado">Cerrado</option>
      </select>
    </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
   
    <td width="171"><div align="right"><a href="javascript: Limpiar_busqueda(); "> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
    <td width="206"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
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
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">


    function catalogo_cargo()
{
       f = document.form1;
  
	   ls_cargo = f.txtcodcar.value;			
	  
			pagina="sigesp_srh_cat_cargos.php";
			window.open(pagina,"catalogoi","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=no,location=no,dependent=yes");
		
	
}


	
		var loadDataURL = "../../php/sigesp_srh_a_concurso.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_concurso.php";
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
		    
     		tipo=opener.document.form1.txttipo.value;//// agregado 28/02/2008
			hidtipo=document.form1.hidtipo.value;//// agregado 28/02/2008						
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Codigo,Denominacion,Fecha Apertura,Fecha Cierre,Estatus");
			mygrid.setInitWidths("95,140,98,98,90")
			mygrid.setColAlign("center,center,center,center,center")
			mygrid.setColTypes("link,ro,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
		//	mygrid.loadXML(loadDataURL+"&txttipo="+tipo+"&hidtipo="+hidtipo);//// modificado 28/02/2008
			mygrid.setSkin("xp");
			mygrid.init();
			
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }	
		
  function Buscar()
		{
		
		 codcon=document.form1.txtcodcon.value;
		 descon=document.form1.txtdescon.value;
		 
		 tipo=opener.document.form1.txttipo.value;//// agregado el 28/02/2008
    	 hidtipo=document.form1.hidtipo.value;//// agregado el 28/02/2008
		 
		 if (document.form1.txtfechaaper.value=="") {
		   fechaaper=document.form1.txtfechaaper.value;
		 }
		 else
		 {  
		   fechaaper=document.form1.txtfechaaper.value.substr(6,4)+"-"+document.form1.txtfechaaper.value.substr(3,2)+"-"+document.form1.txtfechaaper.value.substr(0,2);
		 }
		 
		 if (document.form1.txtfechacie.value=="") {
		   fechacie=document.form1.txtfechacie.value;
		 }
		 else
		 {  
		   fechacie=document.form1.txtfechacie.value.substr(6,4)+"-"+document.form1.txtfechacie.value.substr(3,2)+"-"+document.form1.txtfechacie.value.substr(0,2);
		
		 }
		 
		
		 if (document.form1.comboestatus.value=="--Seleccione--") 
		 {
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  estatus="";
		  mygrid.loadXML("../../php/sigesp_srh_a_concurso.php?valor=buscar"+"&txtcodcon="+codcon+"&txtdescon="+descon+"&txtfechaaper="+fechaaper+"&txtfechacie="+fechacie+"&comboestatus="+estatus+"&txttipo="+tipo+"&hidtipo="+hidtipo);//// modificado el 28/02/2008ç
		  setTimeout (terminar_buscar,500);
		 }      
      else {
		  estatus=document.form1.comboestatus.value;
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 		  
		  mygrid.loadXML("../../php/sigesp_srh_a_concurso.php?valor=buscar"+"&txtcodcon="+codcon+"&txtdescon="+descon+"&txtfechaaper="+fechaaper+"&txtfechacie="+fechacie+"&comboestatus="+estatus+"&txttipo="+tipo+"&hidtipo="+hidtipo); //// modificado el 28/02/2008
		  setTimeout (terminar_buscar,500);
	  }

			
}
		
	
function Limpiar_busqueda () 
{
	document.form1.txtcodcon.value="";
	document.form1.txtdescon.value="";
	document.form1.txtfechaaper.value="";
	document.form1.txtfechacie.value="";
	document.form1.comboestatus.value="null";
}
		
		
		
		function aceptar(ls_codcon, ls_descon, ls_coddestino, ls_desdestino,ls_fechaaperdestino, ls_fechaaper , ls_fechaciedestino, ls_fechacie,ls_codcardestino, ls_codcar, ls_descar, ls_descardestino, li_cantcardestino, li_cantcar, ls_estatusdestino, ls_estatus,ls_tipodestino, ls_tipo, ls_codnom, ls_codnomdestino)
	{
		
		if (opener.document.form1.hidcontrol2.value=='2') {
		
		     obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			obj1=eval("opener.document.form1."+ls_desdestino+"");
			obj1.value=ls_descon;
			close();
		
		}
		else if (opener.document.form1.hidcontrol2.value=='3') {
		
		     obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			obj1=eval("opener.document.form1."+ls_desdestino+"");
			obj1.value=ls_descon;
			obj5=eval("opener.document.form1."+ls_descardestino+"");
			obj5.value=ls_descar;
			close();
		
		}
		else if (opener.document.form1.hidcontrol.value=='2') 
		{
  			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			obj1=eval("opener.document.form1."+ls_desdestino+"");
			obj1.value=ls_descon;
			obj2=eval("opener.document.form1."+ls_fechaaperdestino+"");
			obj2.value=ls_fechaaper;
			obj3=eval("opener.document.form1."+ls_fechaciedestino+"");
			obj3.value=ls_fechacie;
			obj4=eval("opener.document.form1."+ls_fechaciedestino+"");
			obj4.value=ls_fechacie;
			obj5=eval("opener.document.form1."+ls_codcardestino+"");
			obj5.value=ls_codcar;
			obj5=eval("opener.document.form1."+ls_descardestino+"");
			obj5.value=ls_descar;
			obj6=eval("opener.document.form1."+li_cantcardestino+"");
			obj6.value=li_cantcar;
			obj8=eval("opener.document.form1.txtcodtipconcur");
			obj8.value=ls_tipo;
			close();
			
		}
		else if (opener.document.form1.hidcontrol.value=='0') 
		{
  			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			obj1=eval("opener.document.form1."+ls_desdestino+"");
			obj1.value=ls_descon;
			obj2=eval("opener.document.form1."+ls_fechaaperdestino+"");
			obj2.value=ls_fechaaper;
			obj3=eval("opener.document.form1."+ls_fechaciedestino+"");
			obj3.value=ls_fechacie;
			obj4=eval("opener.document.form1."+ls_fechaciedestino+"");
			obj4.value=ls_fechacie;
			obj5=eval("opener.document.form1."+ls_codcardestino+"");
			obj5.value=ls_codcar;
			obj5=eval("opener.document.form1."+ls_descardestino+"");
			obj5.value=ls_descar;
			obj6=eval("opener.document.form1."+li_cantcardestino+"");
			obj6.value=li_cantcar;
			obj8=eval("opener.document.form1.txtcodtipconcur");
			obj8.value=ls_tipo;
						
			if ((trim(ls_tipo)=='Mixto')||(trim(ls_tipo)=='Interno'))
			{
			   div=opener.document.getElementById('personal');
			   div.style.display="";
			   div.style.display="compact";
			   
			}
			else
			{
				div=opener.document.getElementById('personal');
			    div.style.display="";
			    div.style.display="none";
			}
			
			close();
			
		}
		
		else if (opener.document.form1.hidcontrol.value=='5') {
  			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			obj1=eval("opener.document.form1."+ls_desdestino+"");
			obj1.value=ls_descon;
			obj2=eval("opener.document.form1."+ls_fechaaperdestino+"");
			obj2.value=ls_fechaaper;
			close();
			
		}
		else   {
  			obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			obj1=eval("opener.document.form1."+ls_desdestino+"");
			obj1.value=ls_descon;
			obj2=eval("opener.document.form1."+ls_fechaaperdestino+"");
			obj2.value=ls_fechaaper;
			obj3=eval("opener.document.form1."+ls_fechaciedestino+"");
			obj3.value=ls_fechacie;
			obj4=eval("opener.document.form1."+ls_fechaciedestino+"");
			obj4.value=ls_fechacie;
			obj5=eval("opener.document.form1."+ls_codcardestino+"");
			obj5.value=ls_codcar;
			obj5=eval("opener.document.form1."+ls_descardestino+"");
			obj5.value=ls_descar;
			obj6=eval("opener.document.form1."+li_cantcardestino+"");
			obj6.value=li_cantcar;
			obj7=eval("opener.document.form1."+ls_estatusdestino+"");
			obj7.value=ls_estatus;
			obj8=eval("opener.document.form1."+ls_tipodestino+"");
			obj8.value=ls_tipo;
			obj9=eval("opener.document.form1."+ls_codnomdestino+"");
			obj9.value=ls_codnom;

			ls_ejecucion=document.form1.hidstatus.value;
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
			}
			
			opener.document.form1.txtcodcon.readOnly=true;
			close();
		}
			
   }
   
   
   function aceptar_origen(ls_codcon,ls_coddestino)
	{	
            obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			close();		
    }
    
    function aceptar_hasta(ls_codcon,ls_coddestino)
	{	
            obj=eval("opener.document.form1."+ls_coddestino+"");
			obj.value=ls_codcon;
			close();		
    }
	
	
		function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}

	</script>
	
</html>