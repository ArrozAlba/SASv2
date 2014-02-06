// JavaScript Document

function Consultar()
{
     if  ( ($('cmbnivorg').value=="") ) 		
	 {
		 alert("Debe seleccionar un nivel de organigrama.");	
		 
	 }
	 else 
	 {
		  f=document.form1;
		  f.operacion.value="CONSULTAR";
		  f.action="../pantallas/sigesp_srh_p_consulta_organigrama.php";		 		
		  f.submit();	
	 }
}
	



function Limpiar_Datos()
{
  $('cmbnivorg').value="";
  $('txtcodorg').value="";
  $('txtdesorg').value="";
}



function ue_buscarunidad()
{

	nivorg=$('cmbnivorg').value;
	if (nivorg!="")
	{
		
		pagina="../catalogos/sigesp_srh_cat_organigrama.php?valor_cat=0&tipo=5&nivel="+nivorg;
		window.open(pagina,"catalogo","menubar=no, toolbar=no, scrollbars=yes,width=530, height=400,resizable=yes, location=no,				dependent=yes");
	}
	else
	{
		alert ('Debe Seleccionar un Nivel');	
	}
}




function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}





