<?php
session_start();

if (isset($_GET["valor_cat"]))
	{ $ls_ejecucion=$_GET["valor_cat"];	}
else {$ls_ejecion="";}

if (isset($_GET["tipo"]))
	{ $ls_tipo=$_GET["tipo"];	}
else {$ls_tipo="";}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cargos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../js/sigesp_srh_js_cargo.js"></script>
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
  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_ejecucion ?>">
  <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_tipo ?>">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cargos </td>
    </tr>
  </table>
<br>
<table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="125"><div align="right">C&oacute;digo</div></td>
    <td height="22" colspan="3"><div align="left">
      <input name="txtcodcar" type="text" id="txtcodcar" onKeyUp="javascript: ue_validarnumero(this);">
    </div></td>
  </tr>
  <tr>
    <td><div align="right">Denominaci&oacute;n</div></td>
    <td height="22" colspan="3"><div align="left">
      <input name="txtdescar" type="text" id="txtdescar" size="40">
    </div></td>
  </tr>
  <tr>
    <td><div align="right">C&oacute;digo N&oacute;mina</div></td>
    <td width="134" height="22"><div align="left">
      <input name="txtcodnom" size="16" type="text" id="txtcodnom"    >
      <a href="javascript:catalogo_nomina();"><img src="../../../../shared/imagebank/tools15/buscar.gif"  name="buscartip" width="15" height="15" border="0" id="buscartip"></a> </div></td> 
    <td colspan="2"><input name="txtdesnom" type="text" class="sin-borde" id="txtdesnom"  size="40" maxlength="40" readonly>
    </td>
  </tr>
   <tr>
        <td>&nbsp;</td>
            </tr>
			<tr>
    <td>&nbsp;</td>
   
    <td width="182"><div align="right"><a href="javascript: Limpiar_busqueda();"> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td> 
    <td width="199"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
    
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

	
	function catalogo_nomina()
	{
		pagina="../catalogos/sigesp_srh_cat_nomina.php";
		
		window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
	}
	
		//var loadDataURL = "../../php/sigesp_srh_a_cargo.php";
		var actionURL = "../../php/sigesp_srh_a_cargo.php";
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
           
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Codigo,Denominacion,Nomina");
			mygrid.setInitWidths("105,205,190")
			mygrid.setColAlign("center,center,center")
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str")//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF")

			//mygrid.loadXML(loadDataURL);
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
	
		   codcar=document.form1.txtcodcar.value;
		   descar=document.form1.txtdescar.value;
		   codnom=document.form1.txtcodnom.value;
		   mygrid.clearAll();
		   divResultado = document.getElementById('mostrar');
		   divResultado.innerHTML= img; 
		   mygrid.loadXML("../../php/sigesp_srh_a_cargo.php?valor=buscar"+"&txtcodcar="+codcar+"&txtdescar="+descar+"&txtcodnom="+codnom);
           setTimeout (terminar_buscar,500);
			
		}
		
	
function Limpiar_busqueda () 
{
	document.form1.txtcodcar.value="";
	document.form1.txtdescar.value="";
	document.form1.txtcodnom.value="";
	document.form1.txtdesnom.value="";
}		

		
		function aceptar(ls_codcar,ls_descar,ls_codnom,ls_desnom,ls_coddestino,ls_dendestino,ls_codnomdestino,ls_desnomdestino)
		{
			if (opener.document.form1.hidcontrolcar.value=='1')
			{
			    ls_tipo = document.form1.hidtipo.value;
				if (ls_tipo=='2')
				{
				  	obj=eval("opener.document.form1."+ls_coddestino+"");
					obj.value=ls_codcar;
					obj1=eval("opener.document.form1."+ls_dendestino+"");
					obj1.value=ls_descar;
					obj2=eval("opener.document.form1."+ls_codnomdestino+"");
			   	    obj2.value=ls_codnom;
					
					opener.document.form1.txtgrapro.value="";
					opener.document.form1.txtpaspro.value="";
					
					opener.document.form1.txtsuelpro.value="";
					opener.document.form1.txtcompro.value="";
					
					opener.document.form1.txtsuelpro.readOnly=false;
					opener.document.form1.txtcompro.readOnly=false;
					close();
				}
				else if (ls_tipo=='3')
				{
				  	hidcodnom=opener.document.form1.hidcodnom.value;
					if (ls_codnom!=hidcodnom)
					{
						alert ('Debe seleccionar Otro cargo que Pertenezca a la misma Nómina del Empleado. Código Nomina del Empleado: '+hidcodnom);
					}
					else
					{
						obj=eval("opener.document.form1."+ls_coddestino+"");
						obj.value=ls_codcar;
						obj1=eval("opener.document.form1."+ls_dendestino+"");
						obj1.value=ls_descar;
						obj2=eval("opener.document.form1."+ls_codnomdestino+"");
						obj2.value=ls_codnom;
						
						opener.document.form1.txtgrapro.value="";
						opener.document.form1.txtpaspro.value="";
						
						opener.document.form1.txtsuelpro.value="";
						opener.document.form1.txtcompro.value="";
						
						opener.document.form1.txtsuelpro.readOnly=false;
						opener.document.form1.txtcompro.readOnly=false;
						close();
					}
				}
				else
				{		
					obj=eval("opener.document.form1."+ls_coddestino+"");
					obj.value=ls_codcar;
					obj1=eval("opener.document.form1."+ls_dendestino+"");
					obj1.value=ls_descar;
					obj2=eval("opener.document.form1."+ls_codnomdestino+"");
				    obj2.value=ls_codnom;
					close();
				}
			}
			else {
				obj=eval("opener.document.form1."+ls_coddestino+"");
				obj.value=ls_codcar;
				obj1=eval("opener.document.form1."+ls_dendestino+"");
				obj1.value=ls_descar;
				obj2=eval("opener.document.form1."+ls_codnomdestino+"");
				obj2.value=ls_codnom;
				obj3=eval("opener.document.form1."+ls_desnomdestino+"");
				obj3.value=ls_desnom;
				
				 ls_ejecucion=document.form1.hidstatus.value;
				if (ls_ejecucion=='1')
				{
				  opener.document.form1.hidstatus.value="C";
				}
				opener.document.form1.txtcodcar.readOnly=true;
				close();
			}
			
		}
	
	
		function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}


	</script>
	
</html>

