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


var url= "../../php/sigesp_srh_a_puntuacion_bono_merito.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();

function ue_validaexiste()
{
   
	var divResultado= $('existe');
	var paran="txtcodpunt="+$F('txtcodpunt');
	if (($F('txtcodpunt')!="")&& ($F('hidstatus')!='C'))
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
						  Field.clear('txtcodpunt');
						  Field.activate('txtcodpunt');
						  
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
	$('txtcodpunt').value  = trim(respuesta.responseText);
	$('txtdenpunt').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}





function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodpunt.value=="")
  {
		alert('Falta Código de Puntuacion');
		lb_valido=false;
   }
   else if(f.txtnombpunt.value=="")
   {
	   alert('Falta la Denominacion de Puntuacion');
	   lb_valido=false;
   }
   else if(f.txtdespunt.value=="")
   {
	   alert('Falta Descripcion de Puntuacion')  ;
	   lb_valido=false;
   }
   else if(f.txtvalini.value=="")
   {
	   alert('Falta el valor inicial de la Puntuacion')  ;
	   lb_valido=false;
   }
   else if(f.txtvalfin.value=="")
   {
	   alert('Falta el valor final de la Puntuacion')  ;
	   lb_valido=false;
   }
   else if(f.txtcodtipper.value=="")
   {
	   alert('Falta el Tipo de Personal')  ;
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
			  nombpunt=document.form1.txtnombpunt.value;
              despunt=document.form1.txtdespunt.value;
              valini=document.form1.txtvalini.value;
			  valfin=document.form1.txtvalfin.value;
			  codtipper=document.form1.txtcodtipper.value;
			  codpunt=document.form1.txtcodpunt.value;
		
			  
			 
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
					
				
				  }
			
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodtipper="+codtipper+"&txtvalini="+valini+"&txtvalfin="+valfin+"&txtdespunt="+despunt+"&txtnombpunt="+nombpunt+"&txtcodpunt="+codpunt);
   	


}


function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{
		ue_guardar_registro();
		}//lb_valido
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
			 
			  codpunt=document.form1.txtcodpunt.value;
			  
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
					  
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
   ajax.send("txtcodpunt="+codpunt);
    }
		}
		else
	   {
		
		alert('Debe elegir una Puntuacion del Catalogo');
		
		}
}


function ue_validarnumero2(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="-")||(texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9")||(texto=="-1")||(texto=="-2")||(texto=="-3")||(texto=="-4")||(texto=="-5")||(texto=="-6")||(texto=="-7")||(texto=="-8")||(texto=="-9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}



function valida_escala (ide1,ide2) 
{
	if ( parseFloat($(ide1).value) > parseFloat($(ide2).value)) {
	alert ("El valor final debe ser mayor al valor inicial de la Puntuacion.");	
	
	//$(ide2).focus();
	ide2.value="";	
	}
}



function catalogo_tipo_personal()
{	
     f=document.form1;
	 pagina="../catalogos/sigesp_srh_cat_tipopersonal.php?valor_cat=0";
	 window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}