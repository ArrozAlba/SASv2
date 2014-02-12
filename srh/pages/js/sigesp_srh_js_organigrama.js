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




var url= "../../php/sigesp_srh_a_organigrama.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();
var params = 'operacion=';


function ue_validaexiste()
{
  

	var divResultado= $('existe');
	var paran="txtcodorg="+$F('txtcodorg');
	if(($F('txtcodorg')!=""))
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
						  Field.clear('txtcodorg');
						  Field.activate('txtcodorg');						  
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
  document.form1.txtcodorg.readOnly=false;
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
  
if(f.txtcodorg.value=="")
  {
		alert('Falta Código de la Estructura Organigrama');
		lb_valido=false;
   }
   else if(f.txtdesorg.value=="")
   {
	   alert('Falta la Descripcion de la Estructura Organigrama');
	   lb_valido=false;
   }
   else if(f.cmbnivorg.value=="")
   {
	   alert('Falta el Nivel de la Estructura del Organigrama')  ;
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
	 
	  codorg=document.form1.txtcodorg.value;
	  desorg=document.form1.txtdesorg.value;
	  nivorg=document.form1.cmbnivorg.value;
	  padorg=document.form1.txtpadorg.value;
	  
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
   ajax.send("cmbnivorg="+nivorg+"&txtpadorg="+padorg+"&txtcodorg="+codorg+"&txtdesorg="+desorg);
}


function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{	
		  nivpad=document.form1.txtnivpad.value;
		  nivorg=document.form1.cmbnivorg.value;
		  
		  if( (parseInt(nivpad) > parseInt(nivorg)) && (nivorg!=0) )
		  {
				alert ("El nivel del padre debe ser el nivel inmediatamente superior");
		  }
		  else
		  {
			 if (((parseInt(nivpad)+1) != parseInt(nivorg)) && (nivorg!=0) )
			 {
				alert ("El nivel del padre debe ser el nivel inmediatamente superior");
		  	 }
			 else
			 {
				 ue_guardar_registro();
			 }
		  }
		
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
		  codorg=document.form1.txtcodorg.value;
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
   ajax.send("txtcodorg="+codorg);
 
  			 }
		}
		else
	   {
		
		alert('Debe elegir una Seccion del Catalogo');
		
		}
}



function catalogo_organigrama()
{
   f= document.form1;
   nivorg=f.cmbnivorg.value;
   if (nivorg=='')
   {
		alert ("Debe seleccionar un Nivel");   
   }
   else
   {
	   if (nivorg=='0')
	   {
			alert ("El nivel cero (0) no tiene padre");
	   }
	   else
	   {
		   pagina="../catalogos/sigesp_srh_cat_organigrama.php?valor_cat=0&tipo=1&nivel="+nivorg;
			window.open(pagina,"catalogo","menubar=no, toolbar=no, scrollbars=yes,width=530, height=400,resizable=yes, location=no,				dependent=yes");
	   }
   }
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_organigrama.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
		
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




  