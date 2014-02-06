<?php
session_start();
if(array_key_exists("tipo",$_GET))
{
	$ls_tipo=$_GET["tipo"];
}
if (isset($_GET["valor_cat"]))
{
	$ls_ejecucion=$_GET["valor_cat"];	
}	
else
{ $ls_ejecucion="";}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Movimientos de Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../../../shared/js/number_format.js"></script>

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
       <input name="hidtipo" type="hidden" id="hidtipo" value="<?php print $ls_ejecucion?>">
</p>
  <table width="604" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="600" colspan="2" class="titulo-celda">Cat&aacute;logo de Movimiento de Personal </td>
    </tr>
  </table>
<br>
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="126">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      
	  <tr>
        <td><div align="right">Nro. Movimiento</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnummov" type="text" id="txtnummov" size=16  onKeyUp="javascript: ue_validarnumero(this);" >
        </div></td>
      </tr>
	  
	  
      <tr>
        <td><div align="right">C&oacute;digo Personal</div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtcodper" type="text" id="txtcodper" size=16  onKeyUp="javascript:  ue_validarnumero(this);" >
        </div></td>
      </tr>

  </tr>
   <tr>
        <td><div align="right">Nombre </div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtnomper" type="text" id="txtnomper" size=40 >
        </div></td>
      </tr>
 
  </tr>
   <tr class="formato-blanco"> 
  <td><div align="right">Apellido </div></td>
        <td height="22" colspan="2"><div align="left">          <input name="txtapeper" type="text" id="txtapeper" size=40  >
        </div></td>
         <td width="107">&nbsp;</td>
  </tr>
  
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
       <tr>
    <td>&nbsp;</td>
    <td width="155"><div align="right"><a href="javascript: Limpiar_busqueda(); "> <img src="../../../public/imagenes/nuevo.gif" alt="Limpiar" width="15" height="15" border="0">Limpiar</a></div></td>
    <td width="212"><div align="right"><a href="javascript: Buscar()"><img src="../../../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Haga click aqu&iacute; para Buscar</a></div></td>
  </tr>
  </table>
<div align="center" id="mostrar" class="oculto1"></div>  
 
    <table width="600" border="0" cellpadding="0" cellspacing="0" class="fondo-tabla" align="center">
      
      <tr>
        <td width="600" bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
       <tr>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      
      <tr>
        <td height="19" align="center" bgcolor="#EBEBEB"><div id="gridbox" align="center" width="500" height="600" style="background-position:center"></div></td>
      </tr>
	  
  </table>
	
 
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>

</html>

<script language="JavaScript">


var url    = '../../php/sigesp_srh_a_personal.php';
var params = 'operacion';
var metodo = 'get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";

		
        var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML_movimientos";
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
			
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Numero,Codigo,Nombre y Apellido, Fecha Inicio Mov., Tipo Movimiento");
			mygrid.setInitWidths("98,77,195,95,145");
			mygrid.setColAlign("center,center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");		
			mygrid.setSkin("xp");
			mygrid.init();
			

}


function Buscar()
{
	codper=document.form1.txtcodper.value;
	nomper=document.form1.txtnomper.value;
	apeper=document.form1.txtapeper.value;
	nummov=document.form1.txtnummov.value;
	mygrid.clearAll();
	divResultado = document.getElementById('mostrar');
	divResultado.innerHTML= img; 
	mygrid.loadXML("../../php/sigesp_srh_a_personal.php?valor=buscar_movimiento"+"&txtcodper="+codper+"&txtnomper="+nomper+"&txtapeper="+apeper+"&txtnummov="+nummov);
	setTimeout (terminar_buscar,650);
}

	
function Limpiar_busqueda () 
{
	document.form1.txtnummov.value="";
	document.form1.txtcodper.value="";
	document.form1.txtnomper.value="";
	document.form1.txtapeper.value="";
		 
}
		
	
function terminar_buscar ()
{ 
   divResultado = document.getElementById('mostrar');
   divResultado.innerHTML= '';   
}




		
function aceptar (ls_nummov,ls_codcar,ls_coduniadm,ls_denuniadm ,ls_grapro,ls_paspro,ls_suelpro,ls_compro,ls_suetotpro,ls_codgrumov,ls_dengrumov,ls_motivo,ls_obs,ls_fecreg,ls_fecinimov, ls_nummovdestino,ls_codcardestino,ls_coduniadmdestino, ls_denuniadmdestino,ls_graprodestino,ls_pasprodestino,ls_suelprodestino,ls_comprodestino,ls_suetotprodestino,ls_codgrumovdestino,ls_dengrumovdestino,
				ls_motivodestino,ls_obsdestino,ls_fecregdestino,ls_fecinimovdestino,ls_descar,ls_descardestino,ls_codnom,ls_codnomdestino,ls_codper,ls_nomper,ls_codperdestino,ls_nomperdestino)
	{
		
		
		ue_buscar_cargo_actual2(ls_codper);
		ue_buscar_sueldo_actual2(ls_codper);
		ue_buscar_uniadm_actual2(ls_codper);
		
		obj=eval("opener.document.form1."+ls_nummovdestino+"");
		obj.value=ls_nummov;
		
		obj2=eval("opener.document.form1."+ls_codcardestino+"");
		obj2.value=ls_codcar;
		
		obj3=eval("opener.document.form1."+ls_descardestino+"");
		obj3.value=ls_descar;
		
			
		obj4=eval("opener.document.form1."+ls_graprodestino+"");
		obj4.value=ls_grapro;	

		obj5=eval("opener.document.form1."+ls_pasprodestino+"");
		obj5.value=ls_paspro;

		
		obj7=eval("opener.document.form1."+ls_suelprodestino+"");
		obj7.value= uf_convertir (ls_suelpro);
		
				
		obj8=eval("opener.document.form1."+ls_comprodestino+"");
		obj8.value=uf_convertir (ls_compro);
		
		obj9=eval("opener.document.form1."+ls_suetotprodestino+"");
		obj9.value=uf_convertir (ls_suetotpro);
		
		obj10=eval("opener.document.form1."+ls_codgrumovdestino+"");
		obj10.value=ls_codgrumov;
	
		obj11=eval("opener.document.form1."+ls_dengrumovdestino+"");
		obj11.value=ls_dengrumov;
		
		obj12=eval("opener.document.form1."+ls_motivodestino+"");
		obj12.value=ls_motivo;
	
		obj13=eval("opener.document.form1."+ls_obsdestino+"");
		obj13.value=ls_obs;
	
		obj14=eval("opener.document.form1."+ls_fecregdestino+"");
		obj14.value=ls_fecreg;
		
		obj15=eval("opener.document.form1."+ls_fecinimovdestino+"");
		obj15.value=ls_fecinimov;
		
		obj16=eval("opener.document.form1."+ls_coduniadmdestino+"");
		obj16.value=ls_coduniadm;
		
		obj17=eval("opener.document.form1."+ls_denuniadmdestino+"");
		obj17.value=ls_denuniadm;
		
		obj18=eval("opener.document.form1."+ls_codnomdestino+"");
		obj18.value=ls_codnom;
		
		obj19=eval("opener.document.form1."+ls_codperdestino+"");
		obj19.value=ls_codper;
		
		obj20=eval("opener.document.form1."+ls_nomperdestino+"");
		obj20.value=ls_nomper;
		
		
		ls_ejecucion=document.form1.hidtipo.value;
		
		if (ls_ejecucion=='1')
		{
		   opener.document.form1.hidguardar_mov.value='modificar';
		}
			
	   setTimeout (close,810);


}

 function ue_buscar_cargo_actual2(codper)
  {
 
	
	 function onNuevo(respuesta)
      {
	    opener.document.form1.txtcaract.value  = trim(respuesta.responseText);
	 
      }
	
     params = "operacion=ue_buscar_cargo_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  
  
  function ue_buscar_sueldo_actual2(codper)
  {
 
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtsuelact.value    = uf_convertir (trim(respuesta.responseText));
	  
      }
	 
     params = "operacion=ue_buscar_sueldo_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

function ue_buscar_uniadm_actual2(codper)
  {
 
	 function onNuevo(respuesta)
      {
	    opener.document.form1.txtuniadm.value  = trim(respuesta.responseText);
	  
      }
	  
     params = "operacion=ue_buscar_uniadm_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  


</script>