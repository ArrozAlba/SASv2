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




var url= "../../php/sigesp_srh_a_seccion.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();
var params = 'operacion=';


function ue_validaexiste()
{
  

	var divResultado= $('existe');
	var paran="txtcodsec="+$F('txtcodsec');
	if(($F('txtcodsec')!=""))
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
						  Field.clear('txtcodsec');
						  Field.activate('txtcodsec');
						  
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
  document.form1.txtcodsec.readOnly=false;
   scrollTo(0,0);
}

function ue_nuevo()
{
 
	ue_cancelar();
	
}



function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodsec.value=="")
  {
		alert('Falta Código de Sección');
		lb_valido=false;
   }
   else if(f.txtdensec.value=="")
   {
	   alert('Falta la Denominacion de la Sección');
	   lb_valido=false;
   }
   else if(f.txtcoddep.value=="")
   {
	   alert('Falta el Codigo de Departamento')  ;
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
			 
			  coddep=document.form1.txtcoddep.value;
			  densec=document.form1.txtdensec.value;
			  codsec=document.form1.txtcodsec.value;
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
					
				
				  }
			
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcoddep="+coddep+"&txtdensec="+densec+"&txtcodsec="+codsec);
   	



}


function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{	
	   ue_guardar_registro();
		
    }
	
	
}


function ue_eliminar()
{
		
		
		if(document.form1.hidstatus.value=="C")
		{
			
			
			if (confirm("¿Esta seguro de eliminar este registro?"))
			{
		
		
			  //donde se mostrará lo resultados
  
			  divResultado = document.getElementById('mostrar');
    		  divResultado.innerHTML= img;
			  codsec=document.form1.txtcodsec.value;
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
					  // divResultado.innerHTML ='';
					} 
					
						
				  }
				
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodsec="+codsec);
 
  			 }
		}
		else
	   {
		
		alert('Debe elegir una Seccion del Catalogo');
		
		}
}







  