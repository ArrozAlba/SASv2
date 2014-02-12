// JavaScript Document
function objetoAjax()
{
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}





var url= "../../php/sigesp_srh_a_profesion.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();



function ue_validaexiste()
{
  
 var divResultado= $('existe');
	var paran="txtcodpro="+$F('txtcodpro');
	if (($F('txtcodpro')!="") && ($F('hidstatus')!='C'))
	{
	   ajax.open(metodo,url+"?valor=existe",true);
	   ajax.onreadystatechange=function() 
	   {
		  if (ajax.readyState==4)
		  {
				if(ajax.status==200)
				{
					 divResultado.innerHTML = ajax.responseText
					 if(divResultado.innerHTML=='')
					 {
					 }
					 else
					 {
						  Field.clear('txtcodpro');
						  Field.activate('txtcodpro');
						  
						  alert(divResultado.innerHTML);
						 
					 }
				}
				else
				{
					 alert('ERROR '+ajax.status);
				}
		  }
	   }
	
      ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	  ajax.send(paran);
	}
}//fin ue_validaexiste()


function ue_cancelar()
{
  document.form1.reset();
  document.form1.hidstatus.value="";
   scrollTo(0,0);
}

function ue_nuevo()
{
  function onNuevo(respuesta)
  {
	ue_cancelar();
	$('txtcodpro').value  = trim(respuesta.responseText);
	$('txtdespro').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodpro.value=="")
  {
		alert('Falta Codigo de la Profesión');
		lb_valido=false;
   }
   else if(f.txtdespro.value=="")
   {
	   alert('Falta la Denominacion de la Profesión');
	   lb_valido=false;
   }
 
   
   return lb_valido;
 

}





function ue_guardar_registro()
{
	 //donde se mostrará lo resultados
			  divResultado = document.getElementById('mostrar');
			// divMostrar = document.getElementById('resultado');
			  divResultado.innerHTML= img;
			
			  //valores de las cajas de texto
			  
			  despro=document.form1.txtdespro.value;
			  codpro=document.form1.txtcodpro.value;
			//  valini=document.form1.txtvalini.value;
			  
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
			  //sigesp_srh_a_area.php
			  
			  ajax.open(metodo,url+"?valor=guardar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   
					   
					   if(ajax.status==200)
					   {
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					   //divResultado.innerHTML ='';
					}
					
				
				  }
			
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtdespro="+despro+"&txtcodpro="+codpro);
	
	
	}



function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{  
	  ue_guardar_registro();
	 }//lb_valido
}//ue_guardar








function ue_eliminar()
{
		
		
		if(document.form1.hidstatus.value=="C")
		{
			
			
			if (confirm("¿Esta seguro de eliminar este registro?"))
			{
		
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML= img;
			  codpro=document.form1.txtcodpro.value;
		
			  ajax=objetoAjax();
			 
			  ajax.open(metodo,url+"?valor=eliminar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   
					   
					   if(ajax.status==200)
					   {
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					  
					} 
	
					
				  }
				 
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodpro="+codpro);

  			 }
		}
		else
	   {
		
		alert('Debe elegir una Profesi&oacute;n del Catalogo');
		
		}
}




function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		
		window.open("../catalogos/sigesp_srh_cat_profesion.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


  
  

  
  
  
