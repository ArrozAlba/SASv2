var url= "../../php/sigesp_srh_a_requerimiento_cargo.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_guardar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcar");
  
 
  var la_mensajes=new Array ("el código del cargo");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  if ($('totalfilas').value == '1')
  {   
      alert ('Debe agragar un requerimiento al cargo');
	  lb_valido=false;
  }
  
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
	  var requerimiento = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	 g=2;
	 for (f=1; f<(filas.length - 2); f++)
	  {
		
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var req = 
		{
		  "codcar"           : $F('txtcodcar'),
		  "codnom"           : $F('txtcodnom'),
		  "codtipreq"  		 : columnas[0].value,
		  "codreq"        	 : columnas[2].value 	     
		}
		g++;
		requerimiento[f-1] = req;
	  }
	  var requerimiento_cargo = 
	  {
		"codcar"     : $F('txtcodcar'),
		"codnom"     : $F('txtcodnom'),
		"requerimiento"	 : requerimiento
		};
	
	
	  var objeto = JSON.stringify(requerimiento_cargo);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
}


function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodcar");
  var la_mensajes=new Array ("el código del Cargo. Seleccione un Cargo del Catalago");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	divResultado = document.getElementById('mostrar');
    divResultado.innerHTML= img;
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&codcar="+$F('txtcodcar');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
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
  f.action="sigesp_srh_d_requerimiento_cargo.php";
  f.submit();
}




function catalogo_requerimiento()
{
      f= document.form1;
      pagina="../catalogos/sigesp_srh_cat_requerimiento.php?valor_cat=0";
      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}


function catalogo_cargo()
{
      f= document.form1;
	  pagina="../catalogos/sigesp_srh_cat_cargo.php?valor_cat=0";
      window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}

function catalogo_cargo_rac()
{
   pagina="../catalogos/sigesp_srh_cat_cargo_rac.php?valor_cat=0&tipo=3";
   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_requerimiento_cargo.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}



function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		ls_codreqnew=eval("f.txtcodreq"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codreq=eval("f.txtcodreq"+li_i+".value");
			if((ls_codreq==ls_codreqnew)&&(li_i!=li_row))
			{
				alert("El requerimiento de cargo ya fue agregado.");
				lb_valido=true;
			}
		}
		ls_codcar=ue_validarvacio(f.txtcodcar.value);
	
		ls_codreq=eval("f.txtcodreq"+li_row+".value");
		ls_codreq=ue_validarvacio(ls_codreq);
	
		if((ls_codcar=="")||(ls_codreq==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_d_requerimiento_cargo.php";
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
		ls_codreq=eval("f.txtcodreq"+li_row+".value");
		ls_codreq=ue_validarvacio(ls_codreq);
		if(ls_codreq=="")
		{
			alert("la fila a eliminar no debe estar vacio el lapso");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_d_requerimiento_cargo.php";
				f.submit();
			}
		}
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}