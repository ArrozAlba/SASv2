<?php
session_start();
	
	if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
	
	if(array_key_exists("codcon",$_GET))
	{
		$ls_codcon=$_GET["codcon"];
		
		
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Participantes por Concurso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="javascript" src="../../../../shared/js/js_intra/datepickercontrol.js"></script>

<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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

<body onLoad="doOnLoad()">
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
	<input name="hidcodcon" type="hidden" id="hidcodcon" value="<?php print $ls_codcon?>">
	
    <input name="txtnombrevie" type="hidden" id="txtnombrevie">
    <input name="hidcoddestino" type="hidden" id="hidcoddestino" value="<?php print $ls_coddestino ?>">
    <input name="hiddendestino" type="hidden" id="hiddendestino" value="<?php print $ls_desdestino ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Participantes por Concurso </td>
    </tr>
  </table>
<br>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
     <tr>
        <td width="150"><div align="right">C&oacute;digo Personal</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="txtcodper" type="text" id="txtcodper"  size=16>
        </div></td>
      </tr>
  </tr>
   <tr>
        <td><div align="right">Nombre Personal</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnomper" type="text" id="txtnomper" size=40 >
        </div></td>
      </tr>
 
  </tr>
   <tr class="formato-blanco"> 
  <td><div align="right">Apellido Personal</div></td>
        <td height="22" colspan="2"><div align="left">       <input name="txtapeper" type="text" id="txtapeper" size=40 >
        </div></td>
         <td width="7">&nbsp;</td>
  </tr>
    <tr>
        <td>&nbsp;</td>
            </tr>
			<tr>
    <td>&nbsp;</td>
   
    <td width="182"><div align="right"><a href="javascript: Limpiar_busqueda();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="199"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
    
  
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




	    
		var loadDataURL = "../../php/sigesp_srh_a_inscripcion_concurso.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_inscripcion_concurso.php";
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
			hidcodcon=document.form1.hidcodcon.value;
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Código,Apellido, Nombre, Tipo Personal");
			mygrid.setInitWidths("90,150,170,90");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
			mygrid.setSkin("xp");
			mygrid.init();
			setTimeout (terminar_buscar,500);
			
		}
		
		
		
function terminar_buscar ()
{ 
	divResultado = document.getElementById('mostrar');
	divResultado.innerHTML= ''; 
}

function Limpiar_busqueda () 
{
	$('txtcodper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	
}
		
		
 function Buscar()
		{
		
		 codper=document.form1.txtcodper.value;
		 nomper=document.form1.txtnomper.value;
		 apeper=document.form1.txtapeper.value;
		 hidcodcon=document.form1.hidcodcon.value;
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_inscripcion_concurso.php?valor=buscar_Persona_Concurso"+"&txtcodper="+codper+"&txtnomper="+nomper+"&txtapeper="+apeper+"&hidcodcon="+hidcodcon);
         setTimeout (terminar_buscar,500);
		}
		
		
	
function Limpiar_busqueda () 
{
	$('txtcodper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	
}
			
		
function aceptar(ls_codper, ls_nomper, ls_codperdestino, ls_nomperdestino, ls_ejecucion)
	{
			obj=eval("opener.document.form1."+ls_codperdestino+"");
			obj.value=ls_codper;
			obj1=eval("opener.document.form1."+ls_nomperdestino+"");
			obj1.value=ls_nomper;

			close();
	}
			
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}

	</script>
	
</html>