var url= "../../php/sigesp_srh_a_items.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_guardar(li_row)
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodeval","txtcodasp");
  var la_mensajes=new Array ("el código de la evaluación", "el codigo del aspecto");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	      
	 	 if (trim(respuesta.responseText)=="El Item de Evaluacion fue Registrado")
		 {
		  
			   f=document.form1;
			   f.operacion.value="AGREGARDETALLE";
			   f.action="sigesp_srh_d_items.php";		 
			   f.submit();	
		 }
		
	    alert(respuesta.responseText);  
	   	divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= '';
	
	  }
	
	  //Arreglo 
	  var detalle = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	  
	  g= parseInt (li_row)+1;
	 
	
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var reg = 
		{
		  "codeval"          : $F('txtcodeval'),
		  "codasp"           : $F('txtcodasp'),
		  "codite"  		 : columnas[0].value,
		  "denite"        	 : columnas[1].value,
		  "valor"        	 : columnas[2].value
		}
		
		detalle[0] = reg;
	
	  var items = 
	  {
		"codeval"    : $F('txtcodeval'),		
	    "codasp"     : $F('txtcodasp'),
		"detalle"	 : detalle
		}
	
	
	  var objeto = JSON.stringify(items);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  }
}


function ue_eliminar(li_row)
{
  lb_valido=true;
 var la_objetos=new Array ("txtcodeval","txtcodasp");
  var la_mensajes=new Array ("el código de la evaluación", "el codigo del aspecto");
  var la_mensajes=new Array ("el código de la evaluación. Seleccione un registro del catálogo", "el código del aspecto. Seleccione un registro del catálogo");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  { 
  
	
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		
		if (trim(respuesta.responseText)!= "El Item de Evaluacion no puede ser eliminado porque esta asociados a una Evaluacion")
		{
		   f=document.form1;
		   f.filadelete.value=li_row;
		   f.operacion.value="ELIMINARDETALLE"
		   f.action="sigesp_srh_d_items.php";
	       f.submit();					
		  
		}
		
		alert(respuesta.responseText);
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= '';
		
	  }
	  
	  params = "operacion=ue_eliminar&codite="+$F('txtcodite'+li_row)+"&codasp="+$F('txtcodasp')+"&codeval="+$F('txtcodeval');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	
  
  }
  
  
}


function ue_cancelar ()
{
	ue_nuevo();
	divResultado = document.getElementById('mostrar');
    divResultado.innerHTML= '';
	document.form1.hidstatus.value="";
	scrollTo(0,0);
}


function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_d_items.php";
  f.submit();
}



function generar_codigo (num)
{
  
  if ($('txtcodasp').value=="")
  {
	 alert ('Debe seleccionar un Aspecto de Evaluacion');  
  }
  else
  {
      codite = eval ("document.form1.txtcodite"+num);
      if (codite.readOnly!=true)
	  {
	   codasp= $('txtcodasp').value;
  	   var codite="";
  	   var i=-1; 
  	   while(codasp.charAt(++i)==0)
        // en "i" esta el indice del primer caracter no igual a cero
        coditeaux=codasp.substring(i,codasp.length);
	   function onNuevoCodigo(respuesta)
	    {
		 codite = eval ("document.form1.txtcodite"+num);
		 codite.value=  trim(respuesta.responseText);
  		 ue_rellenarcampo(codite,15)
  		 codite.readOnly=true;

  	    }	
	  params = "operacion=ue_nuevo_codigo&codeval="+$F('txtcodeval')+"&codasp="+$F('txtcodasp')+"&coditeaux="+coditeaux;
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevoCodigo});
	 }

  }

}



function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	
		li_coditenew=eval("f.txtcodite"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codite=eval("f.txtcodite"+li_i+".value");
			if((li_codite==li_coditenew)&&(li_i!=li_row))
			{
				alert("El item de evaluacion ya fue agregado.");
				lb_valido=true;
			}
		}
		ls_codeval=ue_validarvacio(f.txtcodeval.value);
		ls_codasp=ue_validarvacio(f.txtcodasp.value);
		
		li_codite=eval("f.txtcodite"+li_row+".value");
		li_codite=ue_validarvacio(li_codite);
		ls_denite=eval("f.txtdenite"+li_row+".value");
		ls_denite=ue_validarvacio(ls_denite);
		li_valor=eval("f.txtvalor"+li_row+".value");
		li_valor=ue_validarvacio(li_valor);
		
		
		if((ls_codeval=="")|| (ls_codasp=="")||(li_codite=="")||(ls_denite=="")||(li_valor==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			ue_guardar(li_row);
			
			
		}
	
	
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_codite=eval("f.txtcodite"+li_row+".value");
		li_codite=ue_validarvacio(li_codite);
		if(li_codite=="")
		{
			alert("la fila a eliminar no debe estar vacio el codigo");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
              
				ue_eliminar (li_row);
				
			}
		}
	}
				

}



function catalogo_aspectos_items()
  {
       f= document.form1;
	    if (f.txtcodeval.value=="")
		{
		 alert ('Debe llenar el tipo de evaluación.');
		}
		else
		{
		 codeval= f.txtcodeval.value;	
         pagina="../catalogos/sigesp_srh_cat_aspectos_items.php?valor_cat=0"+"&codeval="+codeval;
	     window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		}
	
}


function catalogo_tipoevaluacion()
  {
       f= document.form1;
	   pagina="../catalogos/sigesp_srh_cat_tipoevaluacion.php?valor_cat=0";
	   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
	
}


function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_items.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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


function validarreal2(e,t)
  { 
      valor=t.value;
      longitud=valor.length;
      
      keynum=0;
      if(window.event) // IE
      {   
        keynum = e.keyCode
      }      
      else if(e.which) // Netscape/Firefox/Opera
      {
        keynum = e.which
      }

	if ((keynum==9)||(keynum==13)||(keynum==8)||(keynum==0))
	{
	
	}
	else if (!(((keynum>=48)&&(keynum<=57))||(keynum==46)))
	{        
        return false;
	}
      else if (keynum==46)
      {
        if (longitud==0)
        {
          return false; 
        }
        if (haypunto(t))
        {
          return false; 
        } 
      }

  }
  
function haypunto(t)
  {
    valor=t.value;
    longitud=valor.length;
    punto=false;

    for (i=0;i<longitud;i++)
    {
      car=valor.substring(i,i+1);
      if (car==".")
      {
        punto=true;
      }
    }

    return punto;
  }

  

  
  
  


