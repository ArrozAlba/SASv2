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


var url= "../../php/sigesp_srh_a_causa_llamada_atencion.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();




function ue_validaexiste()
{
  
 var divResultado= $('existe');
	var paran="txtcodcaullam_aten="+$F('txtcodcaullam_aten');
	if (($F('txtcodcaullam_aten')!="") && ($F('hidstatus')!='C'))
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
						  Field.clear('txtcodcaullam_aten');
						  Field.activate('txtcodcaullam_aten');
						  
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
	$('txtcodcaullam_aten').value  = trim(respuesta.responseText);
	$('txtdencaullam_aten').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodcaullam_aten.value=="")
  {
		alert('Falta Código de Llamada de Atencion');
		lb_valido=false;
   }
   else if(f.txtdencaullam_aten.value=="")
   {
	   alert('Falta Denominacion de la Causa');
	   lb_valido=false;
   }
   
   return lb_valido;
 

}

function ue_guardar_registro()
{
	 			  //donde se mostrará lo resultados
			 
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML=img;
			 
			  //valores de las cajas de texto
			 
			  dencaullam_aten=document.form1.txtdencaullam_aten.value;
			  codcaullam_aten=document.form1.txtcodcaullam_aten.value;
			  
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
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					}
				
				  //llamar a funcion para limpiar los inputs
					
				  }
			ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtdencaullam_aten="+dencaullam_aten+"&txtcodcaullam_aten="+codcaullam_aten);
  
}

function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{ue_guardar_registro();}//lb_valido
}




function ue_eliminar()
{
		if(document.form1.hidstatus.value=="C")
		{
			if (confirm("Esta seguro de eliminar este registro?"))
			{
		
			  //donde se mostrará lo resultados
  
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML= img;
			 
	
			  codcaullam_aten=document.form1.txtcodcaullam_aten.value;
					 
			
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
   ajax.send("txtcodcaullam_aten="+codcaullam_aten);
  }
		}
		else
	   {
		
		alert('Debe elegir una Causa del Catalogo');
		
		}
}


 