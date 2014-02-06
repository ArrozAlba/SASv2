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




var url= "../../php/sigesp_srh_a_departamento.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();



function ue_validaexiste()
{
  

	var divResultado= $('existe');
	var paran="txtcoddep="+$F('txtcoddep');
	if (($F('txtcoddep')!=""))
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
						  Field.clear('txtcoddep');
						  Field.activate('txtcoddep');
						  
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
  document.form1.txtcoddep.readOnly=false;
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
  
if(f.txtcoddep.value=="")
  {
		alert('Falta Codigo del Departamento');
		lb_valido=false;
   }
   else if(f.txtdendep.value=="")
   {
	   alert('Falta la Denominacion del Departamento');
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
			  
			  dendep=document.form1.txtdendep.value;
			  coddep=document.form1.txtcoddep.value;
			  coduniadm=document.form1.txtcoduniadm.value;
			  codger=document.form1.txtcodger.value;
	  
			
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizará la operacion
		
			  
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
		
					}
					
				
				  }
			
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtdendep="+dendep+"&txtcoddep="+coddep+"&txtcoduniadm="+coduniadm+"&txtcodger="+codger);
   

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
			  
			  coddep=document.form1.txtcoddep.value;

		
			  ajax=objetoAjax();
			  
			  
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
   ajax.send("txtcoddep="+coddep);
 
  			 }
		}
		else
	   {
		
		alert('Debe elegir un Departamento del Catalogo');
		
		}
}






function ue_limpiarbuscar()
{

  document.form1.txtcoddep.value="";
  document.form1.txtdendep.value="";
  document.form1.txtcoduniadm.value="";
  document.form1.txtdenuniadm.value="";
 
  
}




function catalogo_unidad_adm()
{
	window.open("../catalogos/sigesp_srh_cat_unidadadmin.php?valor_cat=0&tipo=3","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}


function catalogo_gerencia()
{
	window.open("../catalogos/sigesp_srh_cat_gerencia.php?valor_cat=0&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		
		window.open("../catalogos/sigesp_srh_cat_departamento.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
