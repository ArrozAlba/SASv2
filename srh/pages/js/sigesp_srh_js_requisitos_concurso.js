var url= "../../php/sigesp_srh_a_requisitos_concurso.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcon");
  var la_mensajes=new Array ("el código del concurso");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  if ($('totalfilas').value=='1')
  {
	alert ('Debe por lo menos agregar un Requisito al Concurso');  
	lb_valido=false;
  }
  
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	   alert(respuesta.responseText);   
	   if (trim(respuesta.responseText)!='Los Requisitos del Concurso ya se encuentran Registrados')
	   {	
		    ue_cancelar();
		}
		else
		{
			divResultado = document.getElementById('mostrar');
   			 divResultado.innerHTML= '';
		}
	   
	  
	  }
	
	  //Arreglo 
	  var detalle = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	  if ($('chkreqindcon').checked)
	  {
		reqindcon=1; 
		
	  }
	  else
	  {
		  reqindcon=0; 
		  
	  }
	 
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var reg = 
		{
		  "codcon"           : $F('txtcodcon'),
		  "reqindcon"  		 : reqindcon,
		  "codreqcon"  		 : columnas[0].value,
		  "desreqcon"        : columnas[1].value,
   	      "canreqcon"        : columnas[2].value
		}
		g++;
		detalle[f-1] = reg;
	  }
	  
	 
	  var reqcon = 
	  {
		"codcon"     : $F('txtcodcon'),
		"detalle"	 : detalle
	  };
	
	
	  var objeto = JSON.stringify(reqcon);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcon");
  var la_mensajes=new Array ("el código del concurso. Seleccione un Registro del Catalago");
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
	  
	  params = "operacion=ue_eliminar&codcon="+$F('txtcodcon');
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
	scrollTo(0,0);
}


function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";	
  f.action="sigesp_srh_d_requisitos_concurso.php";
  f.submit();
}



function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		li_codreqconnew=eval("f.txtcodreqcon"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codreqcon=eval("f.txtcodreqcon"+li_i+".value");
			if((li_codreqcon==li_codreqconnew)&&(li_i!=li_row))
			{
				alert("El requisito ya fue agregado.");
				lb_valido=true;
			}
		}
		ls_codcon=ue_validarvacio(f.txtcodcon.value);
		ls_descon=ue_validarvacio(f.txtdescon.value);
		
		li_codreqcon=eval("f.txtcodreqcon"+li_row+".value");
		li_codreqcon=ue_validarvacio(li_codreqcon);
		ls_desreqcon=eval("f.txtdesreqcon"+li_row+".value");
		ls_desreqcon=ue_validarvacio(ls_desreqcon);
		ls_canreqcon=eval("f.txtcanreqcon"+li_row+".value");
		ls_canreqcon=ue_validarvacio(ls_canreqcon);
	
		if((ls_codcon=="")||(ls_descon=="")||(li_codreqcon=="")||(ls_desreqcon=="")||(ls_canreqcon==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_d_requisitos_concurso.php";
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
		li_codreqcon=eval("f.txtcodreqcon"+li_row+".value");
		li_codreqcon=ue_validarvacio(li_codreqcon);
		if(li_codreqcon=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_d_requisitos_concurso.php";
				f.submit();
			}
		}
	}
}


function catalogo_concurso()
{
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=0";
  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
 
}

function ue_generar_codigo (num)
{
	codigo=eval("document.form1.txtcodreqcon"+num+"");
    codigo.value=num;
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_requisitos_concurso.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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


