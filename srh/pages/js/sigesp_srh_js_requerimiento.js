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

var url= "../../php/sigesp_srh_a_requerimiento.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();

function ue_validaexiste()
{
  
	var divResultado= $('existe');
	var paran="txtcodreq="+$F('txtcodreq');
	if (($F('txtcodreq')!="")&& ($F('hidstatus')!='C'))
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
						  Field.clear('txtcodreq');
						  Field.activate('txtcodreq');
						  
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
	$('txtcodreq').value  = trim(respuesta.responseText);
	$('txtdenreq').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}


function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodreq.value=="")
  {
		alert('Falta Código de Requerimiento');
		lb_valido=false;
   }
   else if(f.txtdenreq.value=="")
   {
	   alert('Falta Nombre Requerimiento');
	   lb_valido=false;
   }
   else if(f.txtcodtipreq.value=="")
   {
	   alert('Falta Código de Tipo de Requerimiento')  ;
	   lb_valido=false;
   }
   
   return lb_valido;
 

}





function ue_guardar_registro()
{
		  //donde se mostrará lo resultados
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML= img;
			
			  //valores de las cajas de texto
			  codtipreq=document.form1.txtcodtipreq.value;
			  denreq=document.form1.txtdenreq.value;
			  codreq=document.form1.txtcodreq.value;
			
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
			 
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
					   	alert( divResultado.innerHTML);
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
   ajax.send("txtcodtipreq="+codtipreq+"&txtdenreq="+denreq+"&txtcodreq="+codreq);


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
			
			if (confirm("Esta seguro de eliminar este registro?"))
			{
			  //donde se mostrará lo resultados
  
			  divResultado = document.getElementById('mostrar');
			   divResultado.innerHTML=img;
			
			  codreq=document.form1.txtcodreq.value;
			
			  
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
		
			  
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
					   	alert('El Registro fue Eliminado');
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					
					} 
					
					else
					{
						
						if(ajax.status==200)
					   {
					   	alert('El Requerimiento No puede ser Eliminado Pertenece a un Cargo'+divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
						
					}
					
				  
				  //llamar a funcion para limpiar los inputs
					
				  }
				 // document.body.onLoad=+"writetostatus('Listo')";
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodreq="+codreq);

   		}	
   }
		else
	   {
		
		alert('Debe elegir un  Requerimiento del Catalogo');
		
		}
}





