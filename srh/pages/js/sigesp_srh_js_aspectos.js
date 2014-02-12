var url= "../../php/sigesp_srh_a_aspectos.php";
var metodo='get';
var params = 'operacion';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


function ue_guardar(li_row)
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodeval");
  
 
  var la_mensajes=new Array ("el código de la evaluación");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
	 	if (trim(respuesta.responseText)=="El Aspecto de Evaluacion fue Registrado")
		 {
		  
			   f=document.form1;
			   f.operacion.value="AGREGARDETALLE";
			   f.action="sigesp_srh_d_aspectos.php";
			   f.submit();	
		 }
		alert(respuesta.responseText);   
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= '';
	   
	  }
	
	  
	  var detalle = new Array();
	  var filas = $('grid').getElementsByTagName("tr");
	  g= parseInt (li_row)+1;
	
		var IdFila   = filas[g].getAttribute("id");
		var columnas = filas[g].getElementsByTagName("input");
		var reg = 
		{
		  "codeval"          : $F('txtcodeval'),
		  "codasp"  		 : columnas[0].value,
		  "denasp"        	 : columnas[1].value
		}
		
		detalle[0] = reg;
	  
	  var aspecto = 
	  {
		"codeval"     : $F('txtcodeval'),		
		"detalle"	 : detalle
		}
	
	
	  var objeto = JSON.stringify(aspecto);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  }
}


function ue_eliminar(li_row)
{
  lb_valido=true;
  var la_objetos=new Array ("txtcodeval");
  var la_mensajes=new Array ("el código de la evaluación. Seleccione un registro del catálogo");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  { 
  	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		
		
		if (trim(respuesta.responseText)!= "El Aspecto de Evaluacion no pueden ser eliminados porque esta asociados a Items de Evaluacion")
		{
		   f=document.form1;
		   f.filadelete.value=li_row;
		   f.operacion.value="ELIMINARDETALLE"
		   f.action="sigesp_srh_d_aspectos.php";
		   f.submit();
		  
		}
		
		alert(respuesta.responseText);
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= '';
		
	  }
	  
	  params = "operacion=ue_eliminar&codeval="+$F('txtcodeval')+"&codasp="+$F('txtcodasp'+li_row);
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
  f.action="sigesp_srh_d_aspectos.php";
  f.submit();
}


function generar_codigo (num)
{
  
  if ($('txtcodeval').value=="")
  {
	 alert ('Debe seleccionar un Tipo de Evaluacion');  
  }
  else
  {
       codasp = eval ("document.form1.txtcodasp"+num);
       if (codasp.readOnly!=true)
	   {
      
	   codeval= $('txtcodeval').value;
	   var codasp="";
	   var i=-1; 
	   while(codeval.charAt(++i)==0)
		// en "i" esta el indice del primer caracter no igual a cero
	   codaspaux=codeval.substring(i,codeval.length);
	   codasp = eval ("document.form1.txtcodasp"+num);
	   function onNuevoCodigo(respuesta)
	   {
		 codasp.value= trim(respuesta.responseText);
		 ue_rellenarcampo(codasp,15)
		 codasp.readOnly=true;
  	  }	
	  params = "operacion=ue_nuevo_codigo&codeval="+$F('txtcodeval')+"&codaspaux="+codaspaux;
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevoCodigo});
  
	   }

  }

}


function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	
		li_codaspnew=eval("f.txtcodasp"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			li_codasp=eval("f.txtcodasp"+li_i+".value");
			if((li_codasp==li_codaspnew)&&(li_i!=li_row))
			{
				alert("El Aspecto de Evaluacion ya fue Agregado.");
				lb_valido=true;
			}
		}
		ls_codeval=ue_validarvacio(f.txtcodeval.value);
		
		li_codasp=eval("f.txtcodasp"+li_row+".value");
		li_codasp=ue_validarvacio(li_codasp);
		ls_denasp=eval("f.txtdenasp"+li_row+".value");
		ls_denasp=ue_validarvacio(ls_denasp);
		
		if((ls_codeval=="")||(li_codasp=="")||(ls_denasp==""))
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
		li_codasp=eval("f.txtcodasp"+li_row+".value");
		li_codasp=ue_validarvacio(li_codasp);
		if(li_codasp=="")
		{
			alert("La fila a eliminar no debe estar vacio el codigo");
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
		
		window.open("../catalogos/sigesp_srh_cat_aspectos.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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


