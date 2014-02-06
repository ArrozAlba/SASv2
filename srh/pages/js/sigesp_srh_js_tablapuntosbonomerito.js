var url= "../../php/sigesp_srh_a_tablapuntosbonomerito.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodesc","txtdenesc", "txtcodtipper", "txtvalunitri");
  
 
  var la_mensajes=new Array ("el código", "la denominación","el tipo de personal", "el valor de la unidad tributaria");
  
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
		  "codpun"           : $F('txtcodesc'),
		  "prompun"  		 : columnas[0].value,
		  "unitri"           : columnas[1].value,
   	      "monbs"            : columnas[2].value
		  
		}
		g++;
		detalle[f-1] = reg;
	  }
	  var escala = 
	  {
		"codpun"     : $F('txtcodesc'),
		"denpun"     : $F('txtdenesc'),
		"codtipper"  : $F('txtcodtipper'),
		"valunitri"  : $F('txtvalunitri'),
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
  var la_mensajes=new Array ("el código. Seleccione un registro del Catalago");
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
  f.action="sigesp_srh_d_tablapuntosbonomerito.php";
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




function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_coddetescnew=eval("f.txtprompun"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_coddetesc=eval("f.txtprompun"+li_i+".value");
			if((li_coddetesc==li_coddetescnew)&&(li_i!=li_row))
			{
				alert("El promedio de puntos ya fue agregado.");
				lb_valido=true;
			}
		}
		ls_codesc=ue_validarvacio(f.txtcodesc.value);
		ls_denesc=ue_validarvacio(f.txtdenesc.value);
		li_prompun=eval("f.txtprompun"+li_row+".value");
		li_prompun=ue_validarvacio(li_prompun);
		li_unitri=eval("f.txtunitri"+li_row+".value");
		li_unitri=ue_validarvacio(li_unitri);
		li_monbs=eval("f.txtmonbs"+li_row+".value");
		li_monbs=ue_validarvacio(li_monbs);
		
		if((ls_codesc=="")||(ls_denesc=="")||(li_prompun=="")||(li_unitri=="")||(li_monbs==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_d_tablapuntosbonomerito.php";
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
		li_coddetesc=eval("f.txtprompun"+li_row+".value");
		li_coddetesc=ue_validarvacio(li_coddetesc);
		if(li_coddetesc=="")
		{
			alert("la fila a eliminar no debe estar el codigo vacío");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_d_tablapuntosbonomerito.php";
				f.submit();
			}
		}
	}
}



function catalogo_tipo_personal()
{	
     f=document.form1;
	 pagina="../catalogos/sigesp_srh_cat_tipopersonal.php?valor_cat=0";
	 window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
		
}

function multiplicar (pos)
{
  

  montobs=eval("document.form1.txtmonbs"+pos);
  unitri =eval("document.form1.txtunitri"+pos);
  valunitri=document.form1.txtvaluntri.value;
 
  if ((valunitri=="") || (unitri.value==""))
  {
  		alert ('Debe colocar el valor de la unidad tributaria presupuestada y el número de unidades tributarias para calcular el monto en bolívares');
  }
  else
  {
  	 valunitri = valunitri.replace ('.','');
     valunitri = valunitri.replace (',','.');
	 
	 cantunitri =unitri.value
	 
	 cantunitri = cantunitri.replace ('.','');
     cantunitri = cantunitri.replace (',','.');
	 
	 multi = parseFloat (cantunitri) * parseFloat (valunitri);
	 multi = uf_convertir (multi);
	 montobs.value= multi;
 }
  

}



function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_tablapuntosbonomerito.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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




