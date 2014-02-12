<?php
session_start();
if (isset($_GET["valor_cat"]))
{ 
	$ls_ejecucion=$_GET["valor_cat"];
}
else
{
	$ls_ejecucion="";
}

	
if (isset($_GET["tipo"]))
{
	$ls_tipo=$_GET["tipo"]; 
}
else
{
	$ls_tipo="";
}

if (isset($_GET["nivel"]))
{
	$ls_nivel=$_GET["nivel"]; 
}
else
{
	$ls_nivel="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Estructura del Organigrama</title>

<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_organigrama.js"></script>
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
        <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print ($ls_ejecucion)?>">
		<input name="hidnivel" type="hidden" id="hidnivel" value="<?php print ($ls_nivel)?>">
		<input name="hidtipo" type="hidden" id="hidtipo" value="<?php print ($ls_tipo)?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Estructura del Organigrama</td>
    </tr>
  </table>
<br>




  <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="100"><div align="right">C&oacute;digo</div></td>
        <td width="178" height="22"><div align="left">
          <input name="txtcodorg" type="text" id="txtcodorg" maxlength="10" style="text-align:center " onKeyUp="javascript: ue_validarnumero(this);">
        </div></td>
      </tr>
      
      <tr>
        <td width="100"><div align="right">Denominaci&oacute;n</div></td>
        <td width="178" height="22"><div align="left">
          <input name="txtdesorg" type="text" id="txtdesorg" onKeyUp="ue_validarcomillas(this);">
        </div></td>
     </tr>   
	 <tr>
          <td height="22"><div align="right">Nivel</div></td>
          <td height="22" colspan="3">
            <div align="left">
              <select name="cmbnivorg" id="cmbnivorg">
                <option value="" selected>-- Seleccione --</option>
				<option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
              </select>
            </div></td>
        </tr> 
      
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
      </tr>
     <tr>
    <td>&nbsp;</td>
   
    <td width="178"><div align="right"><a href="javascript: Limpiar_busqueda();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="222"><div align="CENTER"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
    
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
	
		ls_nivorg=document.form1.hidnivel.value;
		ls_tipo=document.form1.hidtipo.value;
		if (ls_nivorg!="")
		{
			var loadDataURL = "../../php/sigesp_srh_a_organigrama.php?valor=createXML&nivel="+ls_nivorg+"&tipo="+ls_tipo;
		}
		var actionURL = "../../php/sigesp_srh_a_organigrama.php";
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
            
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Codigo,Denominacion,Nivel");
			mygrid.setInitWidths("90,360,53")
			mygrid.setColAlign("center,left,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str","str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF");
			mygrid.setSkin("xp");
			mygrid.init();
			ls_nivorg=document.form1.hidnivel.value;
			if (ls_nivorg!="")
			{
				mygrid.loadXML(loadDataURL);
				divResultado = document.getElementById('mostrar');
				divResultado.innerHTML= img; 
				setTimeout (terminar_buscar,500);
			}
			
            
			
		}
		
		function terminar_buscar ()
		{ 
  		    divResultado = document.getElementById('mostrar');
   			divResultado.innerHTML= ''; 
        }
		
		function Buscar()
		{
	
		   codorg=document.form1.txtcodorg.value;
		   desorg=document.form1.txtdesorg.value;
		   nivorg=document.form1.cmbnivorg.value;
		   ls_tipo=document.form1.hidtipo.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_organigrama.php?valor=buscar"+"&txtcodorg="+codorg+"&txtdesorg="+desorg+"&cmbnivorg="+nivorg+"&tipo="+ls_tipo);
           setTimeout (terminar_buscar,500);
			
		}
		
		function Limpiar_busqueda () 
		{
			document.form1.txtcodorg.value="";
			document.form1.txtdesorg.value="";
			document.form1.cmbnivorg.value="";
		}	
		
		function aceptar(ls_codorg,ls_desorg,ls_coddestino,ls_dendestino, ls_nivorg, ls_nivorgdestino, ls_padorg, ls_padorgdestino,ls_nivpad,ls_nivpaddestino)
		{
			
			if (document.form1.hidtipo.value=='1')
			{
				obj=eval("opener.document.form1.txtpadorg");
				obj.value=ls_codorg;
				obj1=eval("opener.document.form1.txtnivpad");
				obj1.value=ls_nivorg;				
			}
			else if (document.form1.hidtipo.value=='2')
			{
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_codorg;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_desorg
			}
			else if (document.form1.hidtipo.value=='3')
			{
				obj=eval("opener.document.form1.txtcodorgdes");
				obj.value=ls_codorg;
				
			}
			else if (document.form1.hidtipo.value=='4')
			{
				obj=eval("opener.document.form1.txtcodorghas");
				obj.value=ls_codorg;
				
			}
			else if (document.form1.hidtipo.value=='5')
			{
				obj=eval("opener.document.form1.txtcodorg");
				obj.value=ls_codorg;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_desorg;
				
			}
			else
			{
			
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_codorg;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_desorg;
				obj2=eval("opener.document.form1."+ls_nivorgdestino+"");
				obj2.value=ls_nivorg;
				obj3=eval("opener.document.form1."+ls_padorgdestino+"");
				obj3.value=ls_padorg;
				obj4=eval("opener.document.form1."+ls_nivpaddestino+"");
				obj4.value=ls_nivpad;
					
				ls_ejecucion=document.form1.hidstatus.value;
				if (ls_ejecucion=='1')
				{
				  opener.document.form1.hidstatus.value="C";
				}
				opener.document.form1.txtcodorg.readOnly=true;
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
