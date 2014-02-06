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


var url= "../../php/sigesp_srh_a_tipocontrato.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();






function ue_validaexiste()
{
 	var divResultado= $('existe');
	var paran="txtcodtipcon="+$F('txtcodtipcon');
	if (($F('txtcodtipcon')!="") && ($F('hidstatus')!='C'))
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
						  Field.clear('txtcodtipcon');
						  Field.activate('txtcodtipcon');
						  
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
	$('txtcodtipcon').value  = trim(respuesta.responseText);
	$('txtdentipcon').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodtipcon.value=="")
  {
		alert('Falta Codigo del Tipo de Contrato');
		lb_valido=false;
   }
   else if(f.txtdentipcon.value=="")
   {
	   alert('Falta la Denominacion del Tipo de Contrato');
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
			  
			  dentipcon=document.form1.txtdentipcon.value;
			  codtipcon=document.form1.txtcodtipcon.value;
			
			  ajax=objetoAjax();
			  //uso del medoto POST
			  
			  
			  ajax.open("POST",url+"?valor=guardar",true);
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
   ajax.send("txtdentipcon="+dentipcon+"&txtcodtipcon="+codtipcon);
   

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
		
		
			  //donde se mostrará lo resultados
  
			  divResultado = document.getElementById('mostrar');
			    divResultado.innerHTML= img;
			
			  codtipcon=document.form1.txtcodtipcon.value;
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
		
			  
			  ajax.open("POST",url+"?valor=eliminar",true);
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
   ajax.send("txtcodtipcon="+codtipcon);
   			 }
		}
		else
	   {
		
		alert('Debe elegir un Tipo de Contrato del Catalogo');
		
		}
}


