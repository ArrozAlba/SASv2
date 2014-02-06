var url= "../../php/sigesp_srh_a_escalageneral.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodesc","txtdenesc", "txtvalini", "txtvalfin");
  
 
  var la_mensajes=new Array ("el código de la escala", "la denominación de la escala","el valor inicial de la escala", "el valor final de la escala");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   ue_cancelar();
	  }
	
	  //Arreglo 
	  var detalle = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var reg = 
		{
		  "codesc"           : $F('txtcodesc'),
		  "coddetesc"  		 : columnas[0].value,
		  "dendetesc"        : columnas[1].value,
   	      "valini"           : columnas[2].value,
	      "valfin"           : columnas[3].value
		  
		}
		g++;
		detalle[f-1] = reg;
	  }
	  var escala = 
	  {
		"codesc"     : $F('txtcodesc'),
		"denesc"     : $F('txtdenesc'),
		"valini"     : $F('txtvalini'),
		"valfin"     : $F('txtvalfin'),
		"detalle"	 : detalle
		};
	
	
	  var objeto = JSON.stringify(escala);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodesc");
  var la_mensajes=new Array ("el código de la escala. Seleccione una Escala de Evaluación del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  { 
   if ($('hidguardar').value=='modificar')
  {
	if (confirm("¿Esta seguro de Eliminar este Registro ?"))
	{ 
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&codesc="+$F('txtcodesc');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
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
  f.action="sigesp_srh_d_escalageneral.php";
  f.submit();
}

function ue_nuevo_codigo()
{
  function onNuevo(respuesta)
  {
	if ($('txtcodesc').value=="") {
	
	$('txtcodesc').value  = trim(respuesta.responseText);
	$('txtdenesc').focus();
	}
  }	

  params = "operacion=ue_nuevo_codigo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function valida_escalaini (ide1,ide2) 
{
	if (parseFloat($(ide1).value) < parseFloat($(ide2).value)) {
	alert ("El valor inicial del Campo debe ser mayor o igual al valor inicial de la Escala.");	
	ide1.value="";
	 $(ide1).focus();
	}	
	
}

function valida_escalafin (ide1,ide2) 
{
	if ( parseFloat($(ide1).value) > parseFloat($(ide2).value)) {
	alert ("El valor final del Campo debe ser menor o igual al valor final de la Escala.");	
	ide1.value="";
	 $(ide1).focus();
     
	}
}


function valida_escala (ide1,ide2) 
{
	if ( parseFloat($(ide1).value) > parseFloat($(ide2).value)) {
	alert ("El valor final debe ser mayor al valor inicial de la Escala.");	

	ide2.value="";	
	}
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_coddetescnew=eval("f.txtcoddetesc"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_coddetesc=eval("f.txtcoddetesc"+li_i+".value");
			if((li_coddetesc==li_coddetescnew)&&(li_i!=li_row))
			{
				alert("El detalle de la Escala de Evaluacion ya fue agregado.");
				lb_valido=true;
			}
		}
		ls_codesc=ue_validarvacio(f.txtcodesc.value);
		ls_denesc=ue_validarvacio(f.txtdenesc.value);
		li_valini=ue_validarvacio(f.txtvalini.value);
		li_valfin=ue_validarvacio(f.txtvalfin.value);
		li_coddetesc=eval("f.txtcoddetesc"+li_row+".value");
		li_coddetesc=ue_validarvacio(li_coddetesc);
		ls_dendetesc=eval("f.txtdendetesc"+li_row+".value");
		ls_dendetesc=ue_validarvacio(ls_dendetesc);
		li_valinidetesc=eval("f.txtvalinidetesc"+li_row+".value");
		li_valinidetesc=ue_validarvacio(li_valinidetesc);
		li_valfindetesc=eval("f.txtvalfindetesc"+li_row+".value");
		li_valfindetesc=ue_validarvacio(li_valfindetesc);
		if((ls_codesc=="")||(ls_denesc=="")||(li_coddetesc=="")||(ls_dendetesc=="")||(li_valinidetesc=="")||(li_valfindetesc==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_d_escalageneral.php";
			f.submit();
		}
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		li_coddetesc=eval("f.txtcoddetesc"+li_row+".value");
		li_coddetesc=ue_validarvacio(li_coddetesc);
		if(li_coddetesc=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_d_escalageneral.php";
				f.submit();
			}
		}
	}
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

  

  
  
  
