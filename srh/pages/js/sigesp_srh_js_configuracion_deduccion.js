// JavaScript Document

var url    = '../../php/sigesp_srh_a_configuracion_deduccion.php';
var params = 'operacion';
var metodo = 'get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";



function ue_cancelar()
{
    ue_nuevo();
	divResultado = document.getElementById('mostrar');
    divResultado.innerHTML= '';
    scrollTo(0,0);
    $('txtcodtipded').focus();
}


function ue_nuevo()
{
  f=document.form1;
  f.operacion.value="NUEVO";
  f.existe.value="FALSE";		
  f.action="sigesp_srh_d_configuracion_deduccion.php";
  f.submit(); 
}



function ue_guardar (){
	
lb_valido=true;
  var la_objetos=new Array ("txtcodtipded");
  
 
  var la_mensajes=new Array ("el código de tipo de deducción");
  
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  
  if ($('totalfilas').value == '1')
  {   
      alert ('Debe agragar un detalle a la deduccion de seguro');
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
	  var deducc = new Array();
	  var filas = parseInt ($F('totalfilas'));
   
	 for (f=1; f < filas ; f++)
	  {
		 titular = eval ('document.form1.cmbtitular'+f);	
		 sueldo = eval ('document.form1.txtsueldo'+f);	
		 edadmin= eval ('document.form1.txtedadmin'+f);	
		 edadmax= eval ('document.form1.txtedadmax'+f);	
		 sexo = eval ('document.form1.cmbsexper'+f);
		 nexo = eval ('document.form1.cmbnexfam'+f);
		 hcm = eval ('document.form1.cmbhcm'+f);
		 prima = eval ('document.form1.txtprima'+f);
		 aporempre= eval ('document.form1.txtaporempre'+f);
		 aporemple= eval ('document.form1.txtaporemple'+f);
		
		var reg = 
		{
		  "codtipded"    : $F('txtcodtipded'),
		  "coddettipded" : f,
		  "titular"      : titular.value,
		  "sueldo"       : sueldo.value,
		  "edadmin"      : edadmin.value,
		  "edadmax"      : edadmax.value,
		  "sexo"         : sexo.value,
		  "hcm"          : hcm.value,
		  "nexo"         : nexo.value,
		  "prima"        : prima.value,
		  "aporempre"    : aporempre.value,
		  "aporemple"    : aporemple.value
		}
		
		deducc[f-1] = reg;
	  }
	  var deducciones = 
	  {
		  "codtipded"    : $F('txtcodtipded'),
		  "deduccion"	 : deducc
	  };
	
	  var objeto = JSON.stringify(deducciones);
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
	
	
}


function ue_eliminar () {
	
  lb_valido=true;
  var la_objetos=new Array ("txtcodtipded");
  var la_mensajes=new Array ("el código de tipo de Deduucción. Seleccione un Tipo de Deducción de Seguro");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{ 
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  
	  params = "operacion=ue_eliminar&codtipded="+$F('txtcodtipded');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  }
	
}



function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	lb_valido=false;
	if(li_total==li_row)
	{	
		ls_codtipded=ue_validarvacio(f.txtcodtipded.value);
		if((ls_codtipded==""))
		{
			alert("Debe el Tipo de Deducción de Seguro");
			lb_valido=true;
		}
	    else
		{
			ls_titular=eval("f.cmbtitular"+li_row+".value");
			if (ls_titular=='')
			{
				alert("Debe indicar si es titular");
				lb_valido=true;
			}
			else
			{
				ls_sueldo=eval("f.txtsueldo"+li_row+".value");
					if (ls_sueldo=='')
					{
						alert("Debe indicar el monto del sueldo");
						lb_valido=true;
					}
				else
				{
					ls_edadmin=eval("f.txtedadmin"+li_row+".value");
					if (ls_edadmin=='')
					{
						alert("Debe indicar la edad mínima");
						lb_valido=true;
					}
					else
					{
						ls_edadmax=eval("f.txtedadmax"+li_row+".value");
						if (ls_edadmax=='')
						{
							alert("Debe indicar la edad máxima");
							lb_valido=true;
						}
						
						else
						{
							ls_sexo=eval("f.cmbsexper"+li_row+".value");
							if (ls_sexo=='')
							{
								alert("Debe indicar el sexo");
								lb_valido=true;
							}
							else
							{
								ls_nexo=eval("f.cmbnexfam"+li_row+".value");
								 if ((ls_titular!='S')  && (ls_nexo=='') && (ls_sexo!=''))
								{
									alert("Debe indicar el nexo familiar");
									lb_valido=true;
								}
							}
						}
					}
				}
			}
		}
					
			
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_srh_d_configuracion_deduccion.php";
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
		if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_srh_d_configuracion_deduccion.php";
				f.submit();
				
			}
		}
	
}


function valida_edad (ide1,ide2) 
{
	if (parseInt($(ide1).value) < parseInt($(ide2).value)) {
	alert ("La Edad Máxima debe ser menor a la edad Mínima.");	
	ide1.value="";
	 $(ide1).focus();
	}	
	
}


function chequear_titular (ide,ai) {
	  if (ide.value=='S') {
		  
		obj = eval ('document.form1.cmbnexfam'+ai)
		obj.disabled="disabled";		
		 }
	   else {
		  
		obj = eval ('document.form1.cmbnexfam'+ai)
		obj.disabled="";
		  }
	
	}


function chequear_hcm (ide,ai) {
	 
	 obj = eval ('document.form1.cmbsexper'+ai)
	 if (obj.value!="F") {
	    alert ("El HCM aplica solamente para personas de sexo Femenino.");	  
		ide.value='N';
		  }	
	 
	}


function catalogo_tipo_deduccion()
{
	window.open("../catalogos/sigesp_srh_cat_tipodeduccion.php?valor_cat=0","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}


function ue_buscar()
{
	window.open("../catalogos/sigesp_srh_cat_configuracion_deduccion.php?valor_cat=1&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	
}



function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}