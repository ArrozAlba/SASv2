
// JavaScript Document

var url    = '../../php/sigesp_srh_a_personal.php';
var params = 'operacion';
var metodo = 'get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var acordion;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FUNCIÓN PARA INICIALIZAR

function ue_inicializar()
{
  
  function onInicializar(respuesta)
  {
	 if (trim(respuesta.responseText) != "")
	 {   ue_cancelar();
	     var respuestas = respuesta.responseText.split('&');
	     num_respuesta = -1;
		
		 //Paises
	     num_respuesta++;
		if (trim(respuestas[num_respuesta]) != "")
		{
			var pais = JSON.parse(respuestas[num_respuesta]);
			for (i=0; i<pais.despai.length; i++)
			{
			  $('cmbcodpai').options[$('cmbcodpai').options.length] = new Option(pais.despai[i],pais.codpai[i]);
			}
			
			
			for (i=0; i<pais.despai.length; i++)
			{
			  $('cmbcodpainac').options[$('cmbcodpainac').options.length] = new Option(pais.despai[i],pais.codpai[i]);
			}
		}
		

	 }
			
  }	

  params = "operacion=ue_inicializar";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
 }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FUNCIONES PARA EL MANEJO DE LOS COMBOS

function LimpiarComboPais()
{
  $('cmbcodpai').value="null";	
  $('cmbcodpai').selectedIndex = 0;
  LimpiarComboEstado();
}

function LimpiarComboEstado()
{
  removeAllOptions($('cmbcodest'));	
  $('cmbcodest').selectedIndex = 0;
  LimpiarComboMunicipio();
}

function LimpiarComboEstadoNac()
{
  removeAllOptions($('cmbcodestnac'));	
  $('cmbcodestnac').selectedIndex = 0;
}


function LimpiarComboMunicipio()
{
  removeAllOptions($('cmbcodmun'));	
  $('cmbcodmun').selectedIndex = 0;
  LimpiarComboParroquia();
}


function LimpiarComboParroquia()
{
  removeAllOptions($('cmbcodpar'));
  $('cmbcodpar').selectedIndex = 0;
}

function ue_valida_combopais () {

f= document.form1;
if (f.cmbcodpai.value =="null")
  { alert ('Debe seleccionar un Pais');   }
 
}

function ue_valida_combopaisnac () {

f= document.form1;
if (f.cmbcodpainac.value =="null")
  { alert ('Debe seleccionar un Pais de Nacimiento');   }
 
}


function ue_valida_cmbcodmun () {

f= document.form1;
if (f.cmbcodest.value =="null") 
  {alert ('Debe seleccionar un Estado');   }

}


function ue_valida_cmbcodpar () {

f= document.form1;
if (f.cmbcodmun.value =="null")
  { alert ('Debe seleccionar un Municipio');   }
 
}


function ue_CambioPais()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var estados = JSON.parse(respuesta.responseText);
	  for (i=0; i<estados.desest.length; i++)
	  {$('cmbcodest').options[$('cmbcodest').options.length] = new Option(estados.desest[i],estados.codest[i]);}
	}
  }	
  LimpiarComboEstado();
  params = "operacion=ue_inicializarestado&codpai="+$('cmbcodpai').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}

function ue_CambioPaisNac()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var estados = JSON.parse(respuesta.responseText);
	  for (i=0; i<estados.desest.length; i++)
	  {$('cmbcodestnac').options[$('cmbcodestnac').options.length] = new Option(estados.desest[i],estados.codest[i]);}
	}
  }	
  LimpiarComboEstadoNac();
  params = "operacion=ue_inicializarestado&codpai="+$('cmbcodpainac').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}

function ue_CambioEstado()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var municipios = JSON.parse(respuesta.responseText);
	  for (i=0; i<municipios.codmun.length; i++)
	  {$('cmbcodmun').options[$('cmbcodmun').options.length] = new Option(municipios.denmun[i],municipios.codmun[i]);}
	}
  }	
  LimpiarComboMunicipio();
  params = "operacion=ue_inicializarmunicipio&codpai="+$('cmbcodpai').value+"&codest="+$('cmbcodest').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}


function ue_CambioMunicipio()
{
  function onInicializar(respuesta)
  {
	if (trim(respuesta.responseText) != "")
	{
	  var parroquias = JSON.parse(respuesta.responseText);
	  for (i=0; i<parroquias.codpar.length; i++)
	  {$('cmbcodpar').options[$('cmbcodpar').options.length] = new Option(parroquias.denpar[i],parroquias.codpar[i]);}
	}
  }	
  LimpiarComboParroquia();
  params = "operacion=ue_inicializarparroquia&codpai="+$('cmbcodpai').value+"&codest="+$('cmbcodest').value+"&codmun="+$('cmbcodmun').value;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// FUNCIONES PARA HACER VALIDACIONES EN EL FORMULARIO

function ue_chequear_codper()
{
	if ((ue_valida_null($('txtcodper'))) && ($('hidguardar').value!='modificar'))
    {
		function onChequearCodPersonal(respuesta)
		{	  
			 if (trim(respuesta.responseText) != "")
			  {
				  alert(respuesta.responseText);
				  Field.clear('txtcodper');
				  Field.activate('txtcodper');
			  }
			  else
			  {
				  Field.activate('txtcedper');
			  }
		}
		params = "operacion=ue_chequear_codpersonal&codper="+$F('txtcodper');
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequearCodPersonal});	
	}
 }
 
 

function ue_chequear_cedper()
{
	if ((ue_valida_null($('txtcedper'))) && ($('hidguardar').value!='modificar'))
    {
	
		function onChequearCedPersonal(respuesta)
		{	  
			  if (trim(respuesta.responseText) != "")
			  {
				  alert(respuesta.responseText);
				  Field.clear('txtcedper');//INICIALIZAR
				  Field.activate('txtcedper');//FOCUS
			  }
			  else
			  {
				//  Field.activate('txtnomper');
			  }
		}
		params = "operacion=ue_chequear_cedpersonal&cedper="+$F('txtcedper');
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequearCedPersonal});	
	}
}


function ue_hcm()
{
	f=document.form1;
	if(f.cmbsexper.value!="F")
	{
		f.chkhcmper.checked=false;
		alert("La poliza de maternidad es solo para las Mujeres");
	}
	
}



function ue_hcmfam ()
{
  f=document.form1;
  if(f.cmbsexfam.value!="F")
	{
		f.chkhcmfam.checked=false;
		alert("La poliza de maternidad es solo para las Mujeres");
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// FUNCIONES PARA GUARDAR REGISTROS 

function ue_guardar()
{
  		lb_valido = true;

        la_objetos =new Array ("txtcodper","txtcedper","txtnomper","txtapeper",
						       "txtdirper","txtfecnacper","cmbedocivper","cmbnacper",
						       "cmbsexper","txtcodpro", "cmbnivacaper","cmbcodpai","cmbcodest",
							   "cmbcodmun","cmbcodpar","txtnumhijper", "txtcodtippersss","cmbtipvivper","txtfecingadmpub",
							   "txtanoservpreper","txtfecingper");
        la_mensajes=new Array ("el Codigo","la Cedula","el Nombre","el Apellido",
						       "la Direccion","la Fecha de Nacimiento","el Estado Civil","la Nacionalidad",
						       "el Genero","la Profesión","el Nivel Academico de la Persona","el Pais", "el Estado",
							   "el Municipio","la Parroquia","el Numero de Hijos", "el tipo de personal","el Tipo de Vivienda",
							   "la fecha de ingreso a la adminsitracion publica", "los años de servicio previo",
							   "la fecha de ingreso a la institución");
		
        lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	 if(lb_valido)
    {	
	 divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onGuardar(respuesta)
	  {
		 alert(respuesta.responseText);   
		 divResultado = document.getElementById('mostrar');
         divResultado.innerHTML= "";
	     ue_cancelar();
	   	
	
	  }
	
	   if ($('chkhcmper').checked) 
	   {  hcm='1'; }
	   else {   hcm = '0';  }
	   
	   if ($('chkcajahoper').checked) {  cajaho = '1'; }
	   else {   cajaho = '0';  }
		
	   var personal = 
	  {
		"codper"          : $F('txtcodper'),
		"cedper"          : $F('txtcedper'),
		"nomper"          : $F('txtnomper'),
		"apeper"          : $F('txtapeper'),
		"dirper"          : $F('txtdirper'),
		"fecnacper"       : $F('txtfecnacper'),
		"edocivper"    	  : $F('cmbedocivper'),
		"nacper"          : $F('cmbnacper'),
		"codpai"      	  : $F('cmbcodpai'),
		"codest"       	  : $F('cmbcodest'),
		"codmun"    	  : $F('cmbcodmun'),
		"codpar" 	      : $F('cmbcodpar'),
		"telhabper" 	  : $F('txttelhabper'),
		"coreleper"       : $F('txtcoreleper'),
		"telmovper"       : $F('txttelmovper'),
		"sexper"          : $F('cmbsexper'),
		"estaper"		  : $F('txtestaper'),
		"pesper"          : $F('txtpesper'),
		"codpro"          : $F('txtcodpro'),
		"nivacaper"       : $F('cmbnivacaper'),
		"codcom"		  : $F('txtcodcom'),
		"codran"		  : $F('txtcodran'),
		"cedbenper"		  : $F('txtcedbenper'),
		"numhijper"       : $F('txtnumhijper'),
		"contraper"		  : $F('cmbcontraper'),
		"obsper"		  : $F('txtobsper'),
		"fotper"		  : $('hidfotper').value,
		"cenmedper"		  : $F('cmbcenmedper'),
		"turper"		  : $F('cmbturper'),
		"horper"		  : $F('txthorper'),
		"hcmper"		  : hcm,
		"tipsanper"		  : $F('txttipsanper'),
		"numexpper"		  : $F('txtnumexpper'),
		"tipvivper"       : $F('cmbtipvivper'),
  		"tenvivper"		  : $F('txttenvivper'),
		"monpagvivper"	  : $F('txtmonpagvivper'),
		"cuecajahoper"	  : $F('txtcuecajahoper'),
		"cajahoper"	      : cajaho,
		"cuelphper"       : $F('txtcuelphper'),
		"cuefidper"       : $F('txtcuefidper'),
		"fecingadmpubper" : $F('txtfecingadmpub'),
		"anoservpreper"	  : $F('txtanoservpreper'),
		"anoservprecont"  : $F('txtanoservprecont'),	
		"anoservprefijo"  : $F('txtanoservprefijo'),
		"fecingper"	      : $F('txtfecingper'),
		"fecegrper"	      : $F('txtfecegrper'),
		"cauegrper"	      : $F('cmbcauegrper'),
		"obsegrper"	      : $F('txtobsegrper'),
		"codtippersss"	  : $F('txtcodtippersss'),
		"codpainac"	      : $F('cmbcodpainac'),
		"codestnac"	      : $F('cmbcodestnac'),
		"fecjubper"	      : $F('txtfecjubper'),
		"fecreingper"	  : $F('txtfecreingper'),
		"fecfevid"	      : $F('txtfecfevid'),
		"feclossfan"	  : $F('txtfecleypen'),
		"enviorec"	      : $F('cmbenviorec'),
		"codcausa"	      : $F('txtcodcausa'),
		"situacion" 	  : $F('cmbsituacion'),
		"fecsitu" 	      : $F('txtfecsitu'),
		"talcamper"       : $F('txttalcamper'),
		"talpanper"       : $F('txttalpanper'),
		"talzapper"       : $F('txttalzapper'),	
		"codunivi"	 	  : $F('txtcodunivi'),
		"porcajahoper"	  : $F('txtporcajahoper'),
		"codorg"	 	  : $F('txtcodorg'),
		"codger"	 	  : $F('txtcodger'),
		"anoperobr"	 	  : $F('txtanoperobr'),
		"carantper"	 	  : $F('txtcarantper')
	  };
	  
	  var objeto = JSON.stringify(personal);	 
	  params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
	  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});
  };
	
}



function ue_guardar_estudios()
{
  lb_valido = false; 
  if ($('txtcodper').value =='')
  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
    lb_valido= false;}
 else
 {  lb_valido= true;}
	
  if(lb_valido)
    { 
		
		la_objetos =new Array ("txtcodestrea", "cmbtipestrea", "txtinsestrea", "txtdesestrea","txttitestrea",
							   "txtescval","txtanoaprestrea","txtfeciniact",
							   "txtfecfinact", "txtfecgraestrea");
        la_mensajes=new Array ("el Codigo del Estudio","el Tipo de Estudio","el Instituto","la Descripcion",
						       "el Titulo Obtenido","la Escala de Evaluación",
						       "el ultimo año aprobado","la Fecha de inicio","la Fecha de Finalización",
							   "la Fecha de Grado");
		
        lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	
	if (lb_valido)
	{
		lb_valido = ue_comparar_fechas($F('txtfecnacper'),$F('txtfeciniact'));
		if ((!lb_valido)&&($('txtfecnacper').value!=""))
		{
		  alert ('La fecha de inicio del estudio debe ser mayor a la fecha de nacimiento de la persona.');
		  $('txtfeciniact').value="";
	    }
		else
		{
			lb_valido = ue_comparar_fechas($F('txtfeciniact'),$F('txtfecfinact'));
			if (!lb_valido)
			{
			  alert ('La fecha de finalización del estudio debe ser mayor a la fecha de inicio.');
			  $('txtfecfinact').value="";
			}
			else
			{
				lb_valido = ue_comparar_fechas($F('txtfecfinact'), $F('txtfecgraestrea'));
				if (!lb_valido)
				{
				  alert ('La fecha de grado debe ser mayor a la fecha de finalización del estudio.');
				  $('txtfecgraestrea').value="";
				}
				else
				{
					  divResultado = document.getElementById('mostrar');
					  divResultado.innerHTML= img;
					  function onGuardar(respuesta)
					  {
						 alert(respuesta.responseText); 
						 divResultado = document.getElementById('mostrar');
						 divResultado.innerHTML= "";
						 ue_limpiar_estudios();
						 ue_nuevo_codestudio();
						 
					  }		  
					  
					  var estudios =
					  {
						  "codper"   	: $F('txtcodper'),
						  "codestrea"	: $F('txtcodestrea'),
						  "tipestrea"	: $F('cmbtipestrea'),
						  "insestrea"	: $F('txtinsestrea'),
						  "desestrea"	: $F('txtdesestrea'),
						  "titestrea"	: $F('txttitestrea'),
						  "calestrea"	: $F('txtcalestrea'),
						  "escval"		: $F('txtescval'),
						  "aprestrea"	: $F('cmbaprestrea'),
						  "anoaprestrea": $F('txtanoaprestrea'),
						  "horestrea"   : $F('txthorestrea'),
						  "feciniact"	: $F('txtfeciniact'),
						  "fecfinact"	: $F('txtfecfinact'),
						  "fecgraestrea": $F('txtfecgraestrea')		  
					  }
					
					  var objeto2 = JSON.stringify(estudios);
					  $aux = "insertar";	
					  params = "operacion=ue_guardar_estudios&objeto2="+objeto2+"&insmod="+$F('hidguardar_est')
					  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
				 }
				
	   
			}
		}
	 }
	}
}


function ue_guardar_trabajos()
{
    lb_valido = false; 
	
  if ($('txtcodper').value =='')
  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
    lb_valido= false;}
 else
 { lb_valido= true;}    
	 if(lb_valido)
    {	
	  la_objetos =new Array ("txtcodtraant", "txtemptraant", "txtultcartraant", "txtultsuetraant", "txtfecingtraant", "txtfecrettraant", "cmbemppubtraant");
      la_mensajes=new Array ("el Codigo del Trabajo","el nombre de la Empresa","el último cargo ocupado","la fecha de ingreso",
		  			       "la fecha de egreso", "el tipo de empresa");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
		
	  if (lb_valido)
	  {
	    lb_valido = ue_comparar_fechas($F('txtfecnacper'),$F('txtfecingtraant'));
		if ((!lb_valido)&&($('txtfecnacper').value!=""))
		{
		  alert ('La fecha de ingreso al trabajo debe ser mayor a la fecha de nacimiento de la persona.');
		  $('txtfeciniact').value="";
	    }
		else
		{
			lb_valido = ue_comparar_fechas($F('txtfecingtraant'),$F('txtfecingper'));
			if ((!lb_valido)&&($('txtfecingper').value!=""))
			{
			  alert ('La fecha de ingreso a la institucion debe ser mayor a la fecha de ingreso al trabajo anterior.');
			  
			}
			
			else
			{
				lb_valido = ue_comparar_fechas($F('txtfecingtraant'),$F('txtfecrettraant'));
				if ((!lb_valido))
				{
				  alert ('La fecha de ingreso al trabajo anterior debe ser mayor a la fecha de egreso.');
				  $('txtfecrettraant').value="";
				}
				else
				{
					lb_valido = ue_comparar_fechas($F('txtfecrettraant'),$F('txtfecingper'));
					if ((!lb_valido))
					{
					  alert ('La fecha de ingreso a la institucion debe ser mayor a la fecha de egreso del trabajo anterior.');
					  $('txtfecrettraant').value="";
					}
					else
					{
						divResultado = document.getElementById('mostrar');
						divResultado.innerHTML= img;
					  function onGuardar2(respuesta)
					  {
						if (trim(respuesta.responseText) != "")
						{	
						  var respuestas = respuesta.responseText.split('&');
						  num_respuesta = -1;
						  num_respuesta++;
						  if (trim(respuestas[num_respuesta]) != "")
						  {	  
							var anotrabajoantfijo = JSON.parse(respuestas[num_respuesta]);
							$("txtanoservprefijo").value=anotrabajoantfijo;
						  }
						  num_respuesta++;
						  if (trim(respuestas[num_respuesta]) != "")
						  {	  
							var anoservprecont = JSON.parse(respuestas[num_respuesta]);
							$("txtanoservprecont").value=anoservprecont;
						  }
						  
						}
					  }
					  function onGuardar(respuesta)
					  {
						divResultado = document.getElementById('mostrar');
						divResultado.innerHTML= "";
						alert(respuesta.responseText); 
						ue_limpiar_trabajo();
						ue_nuevo_trabajo();
						params = "operacion=ue_buscar_servicio_previo&codper="+$F('txtcodper');
					  	new Ajax.Request(url,{method:'get',parameters:params,onComplete:onGuardar2});	
						
					  }		  
					  
					  var trabajo =
					  {
						  "codper"   		: $F('txtcodper'),
						  "codtraant"		: $F('txtcodtraant'),
						  "emptraant"		: $F('txtemptraant'),
						  "ultcartraant"	: $F('txtultcartraant'),
						  "ultsuetraant"	: $F('txtultsuetraant'),
						  "fecingtraant"	: $F('txtfecingtraant'),		  
						  "fecrettraant"	: $F('txtfecrettraant'),
						  "emppubtraant"	: $F('cmbemppubtraant'),
						  "codded"			: $F('txtcodded'),
						  "anolab"			: $F('txtanolab'),
						  "meslab"   		: $F('txtmeslab'),
						  "dialab"			: $F('txtdialab')		  
					  }
					
					  var objeto3 = JSON.stringify(trabajo);
					  $aux = "insertar";	
					  params = "operacion=ue_guardar_trabajos&objeto3="+objeto3+"&insmod="+$F('hidguardar_trab');
					  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	
						
					}
				}
			}
	    }
	 }
   }
 }



function ue_guardar_familiares()
  {
   lb_valido = false; 
   if ($('txtcodper').value =='')
    { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
    lb_valido= false;}
   else
    {  
    lb_valido= true;}      
	
  if(lb_valido)
    {
	  la_objetos =new Array ("txtcedfam", "txtnomfam", "txtapefam", "cmbsexfam", "txtfecnacperfam", "cmbnexfam");
      la_mensajes=new Array ("la cedula del familiar","el nombre del familiar","el apellido del familiar","el Genero del familiar",
		  			       "la fecha de nacimiento del familiar", "el nexo familiar");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
		
	  if (lb_valido)
	  {
		if ($("cmbnexfam").value=="")
		{  alert ('Debe seleccionar el nexo del familiar');
		  lb_valido= false;
		 }
		 else
		 {
			 lb_valido2 = ue_comparar_fechas($F('txtfecnacper'),$F('txtfecnacperfam'));
			 if (($('cmbnexfam').value== 'H') && (!lb_valido2) )
			 {
				 alert ('La Fecha de Nacimiento del Hijo debe ser menor a la Fecha de Nacimiento del Padre');
				 lb_valido=false;
			 }
			else
			{
				if (($('cmbnexfam').value== 'P') && (lb_valido2) )
				{
				 alert ('La Fecha de Nacimiento del Hijo debe ser mayor a la Fecha de Nacimiento del Padre');
				 lb_valido=false;
				}
				else
				{
					if (($('cmbnexfam').value!= 'H') && ($('chkhijesp').checked) &&(lb_valido2) )
					{
						 alert ('La opcion Hijos Especiales es solamente para Hijos');
						 lb_valido=false;
					}
					else if (($('cmbnexfam').value!= 'H') && ($('chkbonjug').checked) &&(lb_valido2) )
					{
						 alert ('La opcion Bono Juguete es solamente para Hijos');
						 lb_valido=false;
					}
					else
					{
						  divResultado = document.getElementById('mostrar');
						  divResultado.innerHTML= img;
						  function onGuardar(respuesta)
						  {
							alert(respuesta.responseText); 
							divResultado = document.getElementById('mostrar');
							divResultado.innerHTML= "";
							ue_limpiar_familia ();
						
						  }	
						  
						  if ($('chkestfam').checked)
						  { estudia = '1'; }
						  else 
						  { estudia = '0'; }
						 
						 if ($('chkhcfam').checked)
						  { hc = '1'; }
						  else 
						  { hc = '0'; }
						  
						 if ($('chkhcmfam').checked)
						  { hcm = '1'; }
						  else 
						  { hcm = '0'; }
						  
						  if ($('chkhijesp').checked)
						  { hijesp = '1'; }
						  else 
						  { hijesp = '0'; }
						  						  
						  if ($('chkbonjug').checked)
						  { bonjug = '1'; }
						  else 
						  { bonjug = '0'; }
											
						  var familiar =
						  {
							  "codper"   	: $F('txtcodper'),
							  "cedfam"		: $F('txtcedfam'),
							  "nomfam"		: $F('txtnomfam'),
							  "apefam"		: $F('txtapefam'),
							  "sexfam"		: $F('cmbsexfam'),
							  "fecnacfam"	: $F('txtfecnacperfam'),
							  "nexfam"		: $F('cmbnexfam'),
							  "estfam"		: estudia,
							  "hcfam"		: hc,
							  "hcmfam"		: hcm,
							  "hijesp"      : hijesp,
							  "bonjug"      : bonjug,
							  "cedula"      : $F('txtcedula')
						  }
						  
						  var objeto4 = JSON.stringify(familiar);
						  $aux = "insertar";	
						  params = "operacion=ue_guardar_familiares&objeto4="+objeto4+"&insmod="+$F('hidguardar_fam')
						  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
					}
				  }
			 	}
		   }
		}	
    } 
}
  
  


function ue_guardar_beneficiarios()
  {
   lb_valido = false; 
   if ($('txtcodper').value =='')
    { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
    lb_valido= false;}
   else
    {  
    lb_valido= true;}      
	
  if(lb_valido)
    {
	  $('cmbtipben').disabled="";
	  la_objetos =new Array ("txtcedben", "txtnomben", "txtapeben", "cmbnacben",  "cmbtipben" ,"cmbforpagben");
      la_mensajes=new Array ("la cedula del beneficiario","el nombre del beneficiario","el apellido del beneficiario","la nacionalidad del beneficiario", "el tipo de beneficiario","la forma de pago");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
		
	  if (lb_valido)
	  {
				
					  divResultado = document.getElementById('mostrar');
					  divResultado.innerHTML= img;
					  function onGuardar(respuesta)
					  {
						alert(respuesta.responseText); 
						divResultado = document.getElementById('mostrar');
						divResultado.innerHTML= "";
						ue_limpiar_beneficiario ();
					     ue_nuevo_beneficiario();
					  }	
					  
					 
					  var beneficiario =
					  {
						  "codper"   	: $F('txtcodper'),
						  "codben"   	: $F('txtcodben'),
						  "cedben"		: $F('txtcedben'),
						  "nomben"		: $F('txtnomben'),
						  "apeben"		: $F('txtapeben'),
						  "nacben"		: $F('cmbnacben'),
						  "dirben"		: $F('txtdirben'),
						  "telben"		: $F('txttelben'),
						  "tipben"   	: $F('cmbtipben'),
						  "porpagben"	: $F('txtporpagben'),
						  "monpagben"	: $F('txtmonpagben'),
						  "forpagben"	: $F('cmbforpagben'),
						  "nomcheben"   : $F('txtnomcheben'),
						  "codban"		: $F('txtcodban'),
						  "ctaban"		: $F('txtctaban'),
						  "cedaut"		: $F('txtcedaut'),
						  "nexben"		: $F('cmbnexben'),
						  "tipcueben"	: $F('cmbtipcueben'),
						  "numexpben"   : $F('txtnumexpben'),
						  	 	  
					  }
					  
					  var objeto4 = JSON.stringify(beneficiario);
					  $aux = "insertar";	
					  params = "operacion=ue_guardar_beneficiario&objeto4="+objeto4+"&insmod="+$F('hidguardar_ben')
					  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
				  
				  }
	}
			 
}
  

 
function ue_guardar_permiso()
  {
  	  lb_valido = false; 
	  if ($('txtcodper').value =='')
	  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
		lb_valido= false;}
	 else
	 {  
		lb_valido= true;} 
		
	if(lb_valido)
    {	
	  la_objetos =new Array ("txtnumper", "txtfeciniper", "txtfecfinper");
      la_mensajes=new Array ("el numero de permiso","la fecha de inicio del permiso","la fecha de finalización del permiso");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);

	  if (lb_valido)
	  {
		if ($("cmbtipper").value=="")
		{  
		  alert ('Debe seleccionar el tipo de permiso');
		  lb_valido= false;
		}
		else if (($("txtnumdiaper").value=="") && ($("txttothorper").value==""))
		{  
		  alert ('Debe llenar el número de días o de horas del permiso');
		  lb_valido= false;
		}
		else if ((!$('chkremper').checked) && (!$('chkremper2').checked))
		{
		 	 alert ('Debe seleccionar si es un permiso remunerado o no');
		     lb_valido= false;	
		}
		else if (($('chkremper').checked) && ($('chkremper2').checked))
		{
		 	 alert ('Debe seleccionar una sola opcion de permiso remunerado o no remunerado');
			 $('chkremper').checked=false; 
	         $('chkremper2').checked=false; 
		     lb_valido= false;	
		}
		else
		{
			 lb_valido = ue_comparar_fechas($F('txtfeciniper'),$F('txtfecfinper'));
			 
			 if (!lb_valido)
			 {
			   alert ('La fecha de inicio del permiso debe ser mayor a la fecha de finalizacion');
			   lb_valido=false;
		     }
			
			else
			{
			  divResultado = document.getElementById('mostrar');
			  divResultado.innerHTML= img;
			  function onGuardar(respuesta)
			  {
				alert(respuesta.responseText); 
				divResultado = document.getElementById('mostrar');
				divResultado.innerHTML= "";
				ue_limpiar_permiso ();
				ue_nuevo_permiso();
			
			  }		  
			  
			  if ($('chkremper').checked) 
			  {
			   remper='1';	  
			  }
			  if ($('chkremper2').checked)
			  {
			   remper='0';	  
			  }
			  if ($('chkafevacper').checked) 
			  {
			   afevac='0';	  
			  }
			  else
			  {
			   afevac='1';	  
			  }
			  
			  var permiso =
			  {
				  "codper"   		: $F('txtcodper'),
				  "numper"			: $F('txtnumper'),
				  "feciniper"		: $F('txtfeciniper'),
				  "fecfinper"		: $F('txtfecfinper'),
				  "numdiaper"		: $F('txtnumdiaper'),
				  "horper"			: $F('txttothorper'),
				  "afevacper"		: afevac,
				  "tipper"			: $F('cmbtipper'),
				  "obsper"			: $F('txtobsper1'),
				  "remper"			: remper
						  
			  }
			  
			  var objeto5 = JSON.stringify(permiso);
			  $aux = "insertar";	
			  params = "operacion=ue_guardar_permiso&objeto5="+objeto5+"&insmod="+$F('hidguardar_per')
			  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
		  }
				
	  }
	}
  }
}


function ue_guardar_premio()
  {
  	  lb_valido = false; 
	  if ($('txtcodper').value =='')
	  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
		lb_valido= false;}
	 else
	 {  
		lb_valido= true;} 
		
	if(lb_valido)
    {	
	  la_objetos =new Array ("txtnumprem", "txtfecprem", "txtdenprem");
      la_mensajes=new Array ("el numero de premiacion","la fecha de la premiacion","la denominacion de la premiacion");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);

	  if (lb_valido)
	  {
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
				alert(respuesta.responseText); 
				divResultado = document.getElementById('mostrar');
				divResultado.innerHTML= "";
				ue_limpiar_premio ();
				ue_nuevo_premio ();
			
		  }		  
		 
			  var premio =
			  {
				  "codper"   	: $F('txtcodper'),
				  "numprem"		: $F('txtnumprem'),
				  "fecprem"		: $F('txtfecprem'),
				  "denprem"		: $F('txtdenprem'),
				  "motivoprem"	: $F('txtmotivoprem')
						  
			  }
			  
			  var objeto = JSON.stringify(premio);
			  $aux = "insertar";	
			  params = "operacion=ue_guardar_premio&objeto="+objeto+"&insmod="+$F('hidguardar_prem')
			  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
		  }
				
	  }
}




function ue_guardar_deduccion()
  {
  	  lb_valido = true;
	  lb_correcto = true;
	  if ($('txtcodper').value =='')
	  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
		lb_valido= false;
	  }
	  montod = $('txtmontod').value;
	  while(montod.indexOf('.')>0)
  	  {//Elimino todos los puntos o separadores de miles
	     montod=montod.replace(".","");
	  }
	  montod=montod.replace(",",".");
	 if(lb_valido)
     {	
	  if ($('txtcodtipded').value=="")
	  {
		 alert ('Debe seleccionar una  Deduccion');
		 lb_valido=false;
	  }
	  else  if ($('txtcoddettipded').value=="")
	  {
		  alert ('Debe seleccionar un tipo de Deduccion');
		  lb_valido=false;
	  }
	  else if (($('txtmontod').value=='0,00')||(parseFloat(montod)<=0)||($('txtmontod').value==""))
	  {
			alert('No se puede Registrar la Deduccion. La persona no cumple con las condiciones para aplicar este tipo de deduccion. Revise el Monto a Deducir y las Condiciones de la deduccion.');
			lb_correcto=false;
	  }
	   
	  if ((lb_valido)&&(lb_correcto))
	  {
		 
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
			alert(respuesta.responseText); 
			divResultado = document.getElementById('mostrar');
			divResultado.innerHTML= "";
			ue_limpiar_deduccion ();
		
		  }		  
		  
		 
		  var deduccion =
		  {
			  "codper"   	: $F('txtcodper'),
			  "codtipded"	: $F('txtcodtipded'),
			  "coddettipded": $F('txtcoddettipded')
					  
		  }
	
		  var objeto6 = JSON.stringify(deduccion);	
		  params = "operacion=ue_guardar_deduccion&objeto6="+objeto6+"&insmod="+$F('hidguardar_deducc')
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
     }
	 
	}
}



function ue_guardar_deduccion_familiar()
  {
  	  lb_valido = true; 
	  lb_correcto = true;
	  if ($('txtcodper').value =='')
	  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
		lb_valido= false;
	  }
	
	 
	 la_objetos =new Array ("txtcedfam1", "txtcodtipded1","txtcoddettipdedfam");
     la_mensajes=new Array ("la cedula del familiar","el código de la deduccion", "el tipo de deduccion");
	 lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
	  montod = $('txtmontodfam').value;
	  while(montod.indexOf('.')>0)
  	  {//Elimino todos los puntos o separadores de miles
	     montod=montod.replace(".","");
	  }
	  montod=montod.replace(",",".");
	if (lb_valido)	
	{
		if ( ($('txtmontodfam').value=='0,00')||(parseFloat(montod)<=0)||($('txtmontodfam').value==""))
		  {
				alert('No se puede Registrar la Deduccion. La persona no cumple con las condiciones para aplicar este tipo de deduccion. Revise el Monto a Deducir y las Condiciones de la deduccion.');
				lb_correcto=false;
		  }
	}
	  
	  
	 if ((lb_valido)&&(lb_correcto))
    {	
	 	  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onGuardar(respuesta)
		  {
			alert(respuesta.responseText); 
			divResultado = document.getElementById('mostrar');
			divResultado.innerHTML= "";
			ue_limpiar_deduccion_familiar ();
		
		  }		  
		  
		 
		  var deduccion =
		  {
			  "codper"   	: $F('txtcodper'),
			  "cedfam"   	: $F('txtcedfam1'),
			  "codtipded"	: $F('txtcodtipded1'),
			  "coddettipded": $F('txtcoddettipdedfam')
					  
		  }
	
		  var objeto = JSON.stringify(deduccion);	
		  params = "operacion=ue_guardar_deduccion_fam&objeto="+objeto+"&insmod="+$F('hidguardar_deduccfam')
		  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	  
     
	}
}




function ue_guardar_movimiento()
  {
  	  lb_valido = false; 
	  if ($('txtcodper').value =='')
	  { alert ('El código de personal no puede estar vacío. Seleccione un personal del catalogo.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	  la_objetos =new Array ("txtnummov", "txtcodper", "txtcodcar", "txtfecreg", "txtcoduniadm","txtsuelpro", "txtfecinimov", "txtcodgrumov", "txtmotivo", "txtobs" );
      la_mensajes=new Array ("el numero de movimiento", "el codigo del personal","el codigo del cargo","la fecha registro", "la unidad administrativa propuesta" , "el sueldo basico propuesto", "la fecha de inicio del movimiento","el codigo del grupo de movimiento", "el motivo del movimiento", "la observacion del movimiento");
	  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);

	  if (lb_valido)
	  {
		 
		 function onValida_Mov(respuesta)
		  {
			 if (trim(respuesta.responseText) == "SI")
			 {
				 if ($('hidguardar_mov').value!="modificar")
				 {
				 
					 divResultado = document.getElementById('mostrar');
					  divResultado.innerHTML= img;
					  function onGuardar(respuesta)
					  {
						alert(respuesta.responseText); 
						divResultado = document.getElementById('mostrar');
						divResultado.innerHTML= "";
						ue_limpiar_movimiento ();
						ue_buscar_uniadm_actual();
						ue_buscar_cargo_actual();
						ue_buscar_sueldo_actual();				
					  }		  
					var movimiento =
					  {
						  "nummov"      :  $('txtnummov').value,	
						  "codper"      :  $('txtcodper').value,		  
						  "codcar"		:  $('txtcodcar').value,
						  "codnom"		:  $('txtcodnom').value,
						  "fecreg"		:  $('txtfecreg').value,
						  "fecinimov"	:  $('txtfecinimov').value,
						  "uniadm"		:  $('txtcoduniadm').value,
						  "grapro"		:  $('txtgrapro').value,
						  "paspro"		:  $('txtpaspro').value,
						  "suelpro"		:  $('txtsuelpro').value,
						  "compro"		:  $('txtcompro').value,
						  "suetotpro"	:  $('txtsuetotpro').value,
						  "codgrumov"	:  $('txtcodgrumov').value,
						  "motivo"		:  $('txtmotivo').value,
						  "hidcodcar"	:  $('hidcodcar').value,
						  "hidcoduniadm":  $('hidcoduniadm').value,
						  "hidcodnom"   :  $('hidcodnom').value,
						  "hidgrado"    :  $('hidgrado').value,
						  "hidpaso"     :  $('hidpaso').value,
						  "sueldoact"   :  $('txtsuelact').value,
						  "obs"			:  $('txtobs').value
					  }
					  
					  var objeto7 = JSON.stringify(movimiento);	
					  params = "operacion=ue_guardar_movimiento&objeto7="+objeto7+"&insmod="+$F('hidguardar_mov')
					  new Ajax.Request(url,{method:'post',parameters:params,onComplete:onGuardar});	 
				 }
				 else
				 {
					 alert ('No puede actualizar el movimiento. Debe eliminarlo para reversar todos los cambios');
				 }
			 }
			 else
			 {
				alert ('No se puede procesar el movimiento. Compruebe que la nómina no esté calculada o procesada.');
			 }
			
		  } // FIN FUNCION onValida_Mov
		  params = "operacion=valida_mov_nom&codper="+$F('txtcodper');
		 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onValida_Mov});	
	 }
  }

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FUNCIONES PARA ELIMINAR


function ue_eliminar_estudio()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_est').value !='modificar')
	  { alert ('Seleccione un estudio del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_estudios();
		ue_nuevo_codestudio();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_estudio&codest="+$F('txtcodestrea')+"&codper="+$F('txtcodper')
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
   }
   else   
    {
	  
	  alert("Eliminación Cancelada !!!");	  
	}
 
 }
	
}
function ue_eliminar_trabajo()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_trab').value !='modificar')
	  { alert ('Seleccione un trabajo del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	  if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	  {
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img;
		  function onEliminar2(respuesta)
		  {
			if (trim(respuesta.responseText) != "")
			{	
			  var respuestas = respuesta.responseText.split('&');
			  num_respuesta = -1;
			  num_respuesta++;
			  if (trim(respuestas[num_respuesta]) != "")
			  {	  
				var anotrabajoantfijo = JSON.parse(respuestas[num_respuesta]);
				$("txtanoservprefijo").value=anotrabajoantfijo;
			  }
			  num_respuesta++;
			  if (trim(respuestas[num_respuesta]) != "")
			  {	  
				var anoservprecont = JSON.parse(respuestas[num_respuesta]);
				$("txtanoservprecont").value=anoservprecont;
			  }
			  
			}
		  }
		  function onEliminar(respuesta)
		  {
			alert(respuesta.responseText); 
			divResultado = document.getElementById('mostrar');
			divResultado.innerHTML= "";
			ue_limpiar_trabajo();
			ue_nuevo_trabajo();	
			params = "operacion=ue_buscar_servicio_previo&codper="+$F('txtcodper');
			new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar2});
		  }		  
		  
		  params = "operacion=ue_eliminar_trabajo&codtrabant="+$F('txtcodtraant')+"&codper="+$F('txtcodper')
		  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
  	}
	else
	{
	  
	  alert("Eliminación Cancelada !!!");	  
	}
	
 }
}

function ue_eliminar_familiar()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_fam').value !='modificar')
	  { alert ('Seleccione un familiar del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	 if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	 {
		
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_familia ();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_familiar&cedfam="+$F('txtcedfam')+"&codper="+$F('txtcodper')
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
    }
	else
	{
		  alert("Eliminación Cancelada !!!");	  
	}

  }
	
}



function ue_eliminar_beneficiario()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_ben').value !='modificar')
	  { alert ('Seleccione un beneficiario del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	 if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	 {
		
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_beneficiario ();
		ue_nuevo_beneficiario();
	
	  }		  
	  $('cmbtipben').disabled="";
	  params = "operacion=ue_eliminar_beneficiario&codben="+$F('txtcodben')+"&codper="+$F('txtcodper')+"&tipben="+$F('cmbtipben');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
    }
	else
	{
		  alert("Eliminación Cancelada !!!");	  
	}

  }
	
}

 
function ue_eliminar_deduccion()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_deducc').value !='modificar')
	  { alert ('Seleccione un tipo de deducción del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	 if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	 {
	  
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_deduccion ();
	  }		  
	  params = "operacion=ue_eliminar_deduccion&codtipded="+$F('txtcodtipded')+"&codper="+$F('txtcodper')
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
  }
  else
	{
	  alert("Eliminación Cancelada !!!");	  
	}
}
	
}



function ue_eliminar_deduccion_familiar()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_deduccfam').value !='modificar')
	  { alert ('Seleccione un tipo de deducción del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	 if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	 {
	  
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_deduccion_familiar ();
	  }		  
	  params = "operacion=ue_eliminar_deduccion_fam&codtipded="+$F('txtcodtipded1')+"&codper="+$F('txtcodper')+"&cedfam="+$F('txtcedfam1');
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
  }
  else
	{
	  alert("Eliminación Cancelada !!!");	  
	}
}
	
}

function ue_eliminar_permiso()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_per').value !='modificar')
	  { alert ('Seleccione un permiso del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	
	 if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	 {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_permiso ();
		ue_nuevo_permiso();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_permiso&numper="+$F('txtnumper')+"&codper="+$F('txtcodper')
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
  }
  else
	{
	  
	  alert("Eliminación Cancelada !!!");	  
	}
  }
	
}

function ue_eliminar_premio()
  {
  	  lb_valido = false; 
	  if ($('hidguardar_prem').value !='modificar')
	  { alert ('Seleccione una premiacion del catalogo para eliminar.');
		lb_valido= false;
	  }
	 else
	 {  
		lb_valido= true;
	 } 
		
	 if(lb_valido)
    {	
	
	 if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	 {
	  divResultado = document.getElementById('mostrar');
      divResultado.innerHTML= img;
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText); 
		divResultado = document.getElementById('mostrar');
        divResultado.innerHTML= "";
		ue_limpiar_premio ();
		ue_nuevo_premio();
	
	  }		  
	  
	  params = "operacion=ue_eliminar_premio&numprem="+$F('txtnumprem')+"&codper="+$F('txtcodper')
	  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});	  
  }
  else
	{
	  
	  alert("Eliminación Cancelada !!!");	  
	}
  }
	
}


function ue_eliminar_movimiento()
 {
	lb_valido = false; 
	if ($('hidguardar_mov').value !='modificar')
	{ 
		alert ('Seleccione un movimiento del catalogo para eliminar.');
		lb_valido= false;
	 }
	 else
	 {  
		lb_valido= true;
	 } 
		
	if(lb_valido)
	{
	if (confirm("¿ Esta seguro de Reversar el Movimiento de Personal? Al reversar el Movimiento El personal será devuelto a su cargo anterior."))
	 {
		 function onValida_Mov(respuesta)
		  {
			 if (trim(respuesta.responseText) == "SI")
			 { 
			  	divResultado = document.getElementById('mostrar');
			  	divResultado.innerHTML= img;
			  	function onEliminar(respuesta)
			  	{
					alert(respuesta.responseText); 
					divResultado = document.getElementById('mostrar');
					divResultado.innerHTML= "";
					ue_limpiar_movimiento ();
					ue_buscar_uniadm_actual();
					ue_buscar_cargo_actual();
					ue_buscar_sueldo_actual();			
			   }		  
			   params = "operacion=ue_eliminar_movimiento&nummov="+$F('txtnummov')+"&codper="+$F('txtcodper')
			   new Ajax.Request(url,{method:'get',parameters:params,onComplete:onEliminar});
			 }
			 else
			 {
				alert ('No se puede procesar el movimiento. Compruebe que la nómina no esté calculada o procesada.');
			 }
			
		  } // FIN FUNCION onValida_Mov
		  params = "operacion=valida_mov_nom&codper="+$F('txtcodper');
		 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onValida_Mov});	
   }
  else
  {  
	  alert("Eliminación Cancelada !!!");	  
   }  
 }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FUNCIONES PARA LIMPIAR LOS CAMPOS DEL FORMULARIO

function ue_limpiar_estudios()
	{
	   
	   $('txtcodestrea').value="";
	   $('cmbtipestrea').value="null";
	   $('txtinsestrea').value="";
	   $('txtdesestrea').value="";
	   $('txttitestrea').value="";
	   $('txtcalestrea').value="";
	   $('txtescval').value="";
	   $('cmbaprestrea').value="null";
	   $('txtanoaprestrea').value="";
	   $('txthorestrea').value="";
	   $('txtfeciniact').value="";
	   $('txtfecfinact').value="";
	   $('txtfecgraestrea').value="";
   	   $('hidguardar_est').value="insertar";	   
	   scrollTo(0,0);
	}
		
	
function ue_limpiar_trabajo()
	{
	   
	   $('txtcodtraant').value="";
	   $('txtemptraant').value="";
	   $('txtultcartraant').value="";
	   $('txtultsuetraant').value="";
	   $('txtfecingtraant').value="";
	   $('txtfecrettraant').value="";
	   $('cmbemppubtraant').value="null";
	   $('cmbaprestrea').value="null";
	   $('txtcodded').value="";
	   $('txtdesded').value="";
	   $('txtanolab').value="";
	   $('txtmeslab').value="";
	   $('txtdialab').value="";
	   $('hidguardar_trab').value="insertar";	   
		scrollTo(0,0);
	}
		
 function ue_limpiar_familia ()
  {
	$('txtcedfam').value="";
	$('txtcedula').value="";
	$('txtnomfam').value="";
	$('txtapefam').value="";
	$('cmbsexfam').value="null";
	$('txtfecnacperfam').value="";
	$('cmbnexfam').value="null";
	$('chkhcfam').checked=false;
	$('chkhcmfam').checked=false;
	$('chkestfam').checked=false;
	$('chkhijesp').checked=false;
	$('chkbonjug').checked=false;
    $('hidguardar_fam').value="insertar";
	scrollTo(0,0);
 }
 
 function ue_limpiar_permiso ()
{
 	
	$('txtnumper').value="";
	$('txtfeciniper').value="";
	$('txtfecfinper').value="";
	$('txtnumdiaper').value="";
	$('txttothorper').value="";
	$('chkafevacper').checked=false;
	$('cmbtipper').value="null";
	$('txtobsper1').value="";
	$('chkremper').checked=false; 
	$('chkremper2').checked=false; 
    $('hidguardar_per').value="insertar";
	scrollTo(0,0);
	
}

 function ue_limpiar_deduccion ()
{
 	$('txtcodtipded').value="";
	$('txtdentipded').value="";	
    $('txtmontod').value="";		
	$('txtcoddettipded').value="";	
    $('hidguardar_deducc').value="insertar";
	scrollTo(0,0);

}

 function ue_limpiar_deduccion_familiar ()
{
 	$('txtcodtipded1').value="";
	$('txtdentipded1').value="";
	$('txtcedfam1').value="";
	$('txtnomfam1').value="";	
	$('txtmontodfam').value="";	
	$('txtcoddettipdedfam').value="";	
	$('hidguardar_deduccfam').value="insertar";
	scrollTo(0,0);

}

function ue_limpiar_movimiento ()
{
  $('txtnummov').value="";
  $('txtcodper').value="";
  $('txtnomper').value="";
  $('txtsuelact').value="";
  $('txtuniadm').value="";
  $('txtcaract').value="";
  $('txtfecinimov').value="";
  $('txtfecreg').value="";
  $('txtcodcar').value="";
  $('txtcodnom').value="";
  $('txtdescar').value="";
  $('txtcoduniadm').value="";
  $('txtdenuniadm').value="";
  $('txtgrapro').value="";
  $('txtpaspro').value="";
  $('txtsuelpro').value="";
  $('txtcompro').value="";
  $('txtsuetotpro').value="";
  $('txtcodgrumov').value="";
  $('txtdengrumov').value="";
  $('txtmotivo').value="";
  $('txtobs').value="";
  $('hidguardar_mov').value="insertar";
   scrollTo(0,0);
   ue_nuevo_movimiento();
   
}

function ue_limpiar_premio ()
{
 	$('txtnumprem').value="";
	$('txtfecprem').value="";	
    $('txtdenprem').value="";	
	$('txtmotivoprem').value="";	
    $('hidguardar_prem').value="insertar";
	scrollTo(0,0);

}


function ue_limpiar_beneficiario ()
{
  $('txtcodben').value="";
  $('txtcedben').value="";
  $('txtnomben').value="";
  $('txtapeben').value="";
  $('cmbnacben').value="null";
  $('txtdirben').value="";
  $('txttelben').value="";
  $('cmbtipben').value="null";
  $('txtporpagben').value="";
  $('txtmonpagben').value="";
  $('cmbforpagben').value="null";
  $('txtnomcheben').value="";
  $('txtcodban').value="";
  $('txtnomban').value="";
  $('txtctaban').value="";
  $('txtcedaut').value="";
  $('cmbnexben').value="-";
  $('cmbtipcueben').value="null";
  $('txtnumexpben').value="";
  $('cmbtipben').disabled="";
  $('hidguardar_ben').value="insertar";
   scrollTo(0,0);
   
}


function ue_cancelar()
{
     ue_nuevo();
    scrollTo(0,0);
   
}
 
 function ue_nuevo()
 {
	 LimpiarComboPais();
	 $('txtcodper').value="";
	 $('txtcedper').value="";
	 $('txtnomper').value="";
	 $('txtapeper').value="";
	 $('txtdirper').value="";
	 $('txtcaract').value="";
	 $('txtuniadm').value="";	 
	 $('txtfecnacper').value="";
	 $('cmbedocivper').value="null";
 	 $('cmbnacper').value="null";
	 $('txttelhabper').value="";
	 $('txtcoreleper').value="";
	 $('txttelmovper').value="";
	 $('cmbsexper').value="null";
	 $('txtestaper').value="";
	 $('txtpesper').value="";
	 $('txtcodpro').value="";
	 $('txtdespro').value="";
	 $('cmbnivacaper').value="null";
	 $('txtcodcom').value="";
	 $('txtcodran').value="";
	 $('txtcedbenper').value="";
	 $('txtnumhijper').value="0";
	 $('cmbcontraper').value="null";
	 $('txtobsper').value="";
	 $('hidfotper').value="";
	 $('cmbcenmedper').value="null";
	 $('cmbturper').value="null";
	 $('txthorper').value="";
	 $('chkhcmper').checked=false;
	 $('txttipsanper').value="";
	 $('txtnumexpper').value="";
	 $('txtcodtippersss').value="";
	 $('txtdestippersss').value="";
	 $('cmbcodpainac').value="null";
	 $('cmbcodestnac').value="null";
	 $('cmbtipvivper').value="null";
  	 $('txttenvivper').value="";
	 $('txtmonpagvivper').value="";
	 $('txtcuecajahoper').value="";
	 $('chkcajahoper').checked=false;
	 $('txtcuelphper').value="";
	 $('txtcuefidper').value="";
	 $('txtfecingadmpub').value="";
	 $('txtanoservpreper').value="0";
	 $('txtanoservprecont').value="0";
	 $('txtanoservprefijo').value="0"; 
	 $('txtfecingper').value="";
	 $('txtfecegrper').value="";
	 $('cmbcauegrper').value="";
	 $('txtobsegrper').value="";
	 $('txtfecreingper').value="";
	 $('txtfecjubper').value="";
 	 $('txtcodunivi').value="";
	 $('txtporcajahoper').value="";	 
	 $('txtdenunivi').value="";
	 $('txtcodorg').value="";
	 $('txtdesorg').value="";
	 $('txtfecfevid').value="";
	 $('txtfecleypen').value="";
 	 $('cmbenviorec').value="null";
     $('txtcodper').readOnly=false;
	 $('txtcedper').readOnly=false;
	 $('hidguardar').value="incluir";
	 $('txtdencausa').value="";
	 $('txtcodcausa').value="";
	 $('cmbsituacion').value="";
	 $('txtcodger').value="";
	 $('txtdenger').value="";
	 $('txtfecsitu').value="";
	 $('txtanoperobr').value="0";
	 $('txtcarantper').value="";
	 $('txttalcamper').value="";
	 $('txttalpanper').value="";
	 $('txttalzapper').value="";
	 $('txtdestippersss2').value="";
	 $('txtnumexpper2').value="";
	 $('txtdestippersss3').value="";
	 $('txtnumexpper3').value="";
	 $('txtdestippersss4').value="";
	 $('txtnumexpper4').value="";
	 $('txtdestippersss5').value="";
	 $('txtnumexpper5').value="";
	 $('txtdestippersss6').value="";
	 $('txtnumexpper6').value="";
	 $('txtdestippersss7').value="";
	 $('txtnumexpper7').value="";
	 $('txtdestippersss8').value="";
	 $('txtnumexpper8').value="";
	 $('txtcodper2').value="";
	 $('txtcodper3').value="";
	 $('txtcodper4').value="";
	 $('txtcodper5').value="";
	 $('txtcodper6').value="";
	 $('txtcodper7').value="";
	 $('txtcodper8').value="";
	 $('txtdia').value="0";
	 $('txtmes').value="0";
	 $('txtano').value="0";
	 
	 foto=document.getElementById('foto');
	 foto.src="";
	 foto.src="../../../fotos/silueta.jpg";
	 divResultado = document.getElementById('mostrar');
     divResultado.innerHTML= "";
	 ue_limpiar_estudios();
	 ue_limpiar_trabajo ();
	 ue_limpiar_familia ();
	 ue_limpiar_beneficiario ();
	 ue_limpiar_permiso ();
	 ue_limpiar_deduccion ();
	 ue_limpiar_deduccion_familiar();
	 ue_limpiar_premio();
}
 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FUNCIONES PARA GENERAR NUEVOS CODIGOS

  function ue_nuevo_codestudio()
  { 

	 function onNuevo(respuesta)
      {	   
	   $('txtcodestrea').value  = trim(respuesta.responseText);
	   $('txtcodestrea').focus();
      }
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_nuevo_estudio&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }

  
  
   function ue_nuevo_trabajo()
  {
  
	 function onNuevo(respuesta)
      {
	   $('txtcodtraant').value  = trim(respuesta.responseText);
	   $('txtcodtraant').focus();
      }
	   var codper = $F('txtcodper');
	
     params = "operacion=ue_nuevo_trabajo&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

function ue_nuevo_permiso()
  {
  
	 function onNuevo(respuesta)
      {
	   $('txtnumper').value  = trim(respuesta.responseText);
	   $('txtnumper').focus();
      }
	 
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_nuevo_permiso&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  
  

function ue_nuevo_premio()
  {
  
	 function onNuevo(respuesta)
      {
	   $('txtnumprem').value  = trim(respuesta.responseText);
	   $('txtnumprem').focus();
      }
	 
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_nuevo_premio&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

	
function ue_nuevo_movimiento()
  {
 
	 function onNuevo(respuesta)
      {
	   $('txtnummov').value  = trim(respuesta.responseText);
	   $('txtnummov').focus();
      }
	 
	
	
     params = "operacion=ue_nuevo_movimiento";
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  
  
  function ue_buscar_cargo_actual()
  {
 
	 function onNuevo(respuesta)
      {
	   $('txtcaract').value  = trim(respuesta.responseText);
	   $('txtfecreg').focus();
      }
	 
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_buscar_cargo_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  
  
  function ue_buscar_sueldo_actual()
  {
 
	 function onNuevo(respuesta)
      {
	   $('txttxtsuelact').value  = uf_convertir (trim(respuesta.responseText));
	   $('txtfecreg').focus();
      }
	 
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_buscar_sueldo_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

function ue_buscar_uniadm_actual()
  {
 
	 function onNuevo(respuesta)
      {
	   $('txtuniadm').value  = trim(respuesta.responseText);
	   $('txtfecreg').focus();
      }
	 
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_buscar_uniadm_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

  
 function ue_nuevo_beneficiario()
  {
 
	 function onNuevo(respuesta)
      {
	   $('txtcodben').value  = trim(respuesta.responseText);
	   $('txtcodben').focus();
      }
	 
	  var codper = $F('txtcodper');
	
     params = "operacion=ue_nuevo_beneficiario&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FUNCIONES PARA MANEJAR CATALOGOS DEL FORMULARIO


function ue_buscardedicacion()
	{
	window.open("../../../../sno/sigesp_snorh_cat_dedicacion.php?tipo=trabajoant","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
function catalogo_tipo_movimiento()
{
    
  pagina="../catalogos/sigesp_srh_cat_grupomovimiento.php?valor_cat=0";
  window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}

function catalogo_cargo()
{
   pagina="../catalogos/sigesp_srh_cat_cargo.php?valor_cat=0&tipo=3";
   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}

function catalogo_cargo_rac()
{
   pagina="../catalogos/sigesp_srh_cat_cargo_rac.php?valor_cat=0";
   window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
   
}

function catalogo_familiar()
{
   if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{
   
   codper= $('txtcodper').value;
   window.open("../catalogos/sigesp_srh_cat_familiares.php?tipo=3&codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}

function ue_buscartipopersonalsss()
{

	window.open("../../../../sno/sigesp_snorh_cat_tipopersonalsss.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");


}

function catalogo_tipo_deduccion()
{
	window.open("../catalogos/sigesp_srh_cat_configuracion_deduccion.php?valor_cat=0&tipo=2","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}



function catalogo_gerencia()
{
	window.open("../catalogos/sigesp_srh_cat_gerencia.php?valor_cat=0&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}



function catalogo_deducciones_personal()
{  
 	window.open("../catalogos/sigesp_srh_cat_configuracion_deduccion.php?valor_cat=0&tipo=3","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
			
	
}




function catalogo_detalle_deduccion_fam()
{
	if ($('txtcodtipded1').value=='') 
	{ alert ('Debe seleccionar un tipo de deduccion para el familiar');		}
	else 
	{ 
	  codded= $('txtcodtipded1').value;	
	  nexfam= $('hidnexfam').value;	
	  sexfam= $('hidsexfam').value;
	  window.open("../catalogos/sigesp_srh_cat_detalle_deduccion.php?tipo=dedfam&codded="+codded+"&nexfam="+nexfam+"&sexper="+sexfam,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
	}
}


function catalogo_detalle_deduccion()
{
	if ($('txtcodtipded').value=='') 
	{ alert ('Debe seleccionar un tipo de deduccion');		}
	else 
	{ 
	  codded= $('txtcodtipded').value;	
	  sexper= $('cmbsexper').value;	
	  window.open("../catalogos/sigesp_srh_cat_detalle_deduccion.php?tipo=dedper&codded="+codded+"&sexper="+sexper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,left=50,top=50,location=no,resizable=yes");
	}
}





function ue_BuscarUnidadVipladin()
{
	window.open("../catalogos/sigesp_srh_cat_uni_vipladin.php?valor_cat=0&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}


function catalogo_unidad_adm()
{
	window.open("../catalogos/sigesp_srh_cat_unidadadmin.php?valor_cat=0&tipo=3","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=yes");
}


function ue_buscarprofesion()
{
     
   pagina="../catalogos/sigesp_srh_cat_profesion.php?valor_cat=0";

  window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
 }
 
function ue_buscar_solicitud_empleo () 
{
      
       pagina="../catalogos/sigesp_srh_cat_solicitud_empleo.php?valor_cat=0"; 
       window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
 
}



function ue_buscarcomponente()
{
	window.open("../../../../sno/sigesp_snorh_cat_componente.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarrango()
{
	f=document.form1;
	codcom=ue_validarvacio(f.txtcodcom.value);
	if(codcom!="")
	{
		window.open("../../../../sno/sigesp_snorh_cat_rango.php?tipo=personal&codcom="+codcom+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un Componente.");
	}
}

function ue_buscarbanco()
{
	window.open("../../../../sno/sigesp_snorh_cat_banco.php?tipo=beneficiario","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_catalogo_organigrama()
{
   
   pagina="../catalogos/sigesp_srh_cat_organigrama.php?valor_cat=0&tipo=2";
	window.open(pagina,"catalogo","menubar=no, toolbar=no, scrollbars=yes,width=530, height=400,resizable=yes, location=no,				dependent=yes");
	 
}
	

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FUNCIONES PARA BUSCAR EN CÁTALOGOS


function ue_buscar()
{
	window.open("../catalogos/sigesp_srh_cat_personal.php?valor_cat=1&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
  	
}



function ue_buscar_premio () 
{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_premiaciones.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}

function ue_buscar_estudios () 
{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_estudios.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}


function ue_buscar_trabajos ()

{
	if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_trabajos.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}

}


function ue_buscar_familiares()
{
	
  if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_familiares.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}



function ue_buscar_beneficiarios()
{
	
  if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_beneficiarios.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_buscar_permisos()
{

if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_permisos.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_buscar_deducciones()
{

if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_deducciones.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_buscarcausa()
{
	  if (document.images["causa"].style.visibility!="hidden")
	  {
		window.open("../../../../sno/sigesp_snorh_cat_causa.php?tipo=personal","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	  }
}


function ue_buscar_deducciones_familiar()
{

if ($('txtcodper').value=='') 
	{ alert ('Debe seleccionar un personal del catalogo');		}
	else 
	{ codper= $('txtcodper').value;
	  window.open("../catalogos/sigesp_srh_cat_deducciones_familiar.php?codper="+codper,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_buscar_movimientos()
{

  window.open("../catalogos/sigesp_srh_cat_movimiento_personal.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=625,height=500,left=50,top=50,location=no,resizable=yes");
	
}

function ue_consultar_ubicacion_fisica ()
{
	codorg=$("txtcodorg").value;
	if (codorg=="")
	{
		alert("Debe seleccionar el Código del Organigrama");	
	}
	else
	{
		
		window.open("sigesp_srh_pdt_ubicacion_fisica.php?codorg="+codorg,"cat","menubar=no,toolbar=no,scrollbars=yes,width=680,height=400,left=50,top=50,location=no,resizable=yes");
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//OTRAS FUNCIONES


function ue_imprimir_mov()
{
	f=document.form1;
	nroreg=f.txtnummov.value;
	codper=f.txtcodper.value;
	if (codper!="")
	{
		pagina="../../../reporte/sigesp_srh_rfs_movimiento_personal.php?nroreg="+nroreg+"&codper="+codper;
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
	}
	else
	{
		alert ("El código del personal no puede estar vacío.");
	}
		  
} // fin de la funcion print

function calcular_tiempo_trabajado () 
{
    
	f=document.form1;
	valido=true;
	
	fecingtraant=f.txtfecingtraant.value;	
	fecrettraant=f.txtfecrettraant.value;
	
	if ((fecingtraant!="")&&(fecrettraant!=""))
	{
	   	valido = ue_comparar_fechas(fecingtraant,fecrettraant);
		if (valido)
		{
			dia1 = fecingtraant.substr(0,2);
			mes1 = fecingtraant.substr(3,2);
			ano1 = fecingtraant.substr(6,4);
		
			dia2 = fecrettraant.substr(0,2);
			mes2 = fecrettraant.substr(3,2);
			ano2 = fecrettraant.substr(6,4);
			if(eval(dia1)>eval(dia2))
			{
				f.txtdialab.value=eval("(30-"+dia1+")+"+dia2+"");
			}
			else
			{
				f.txtdialab.value=eval(""+dia2+"-"+dia1+"");
			}
			if(eval(mes1)>eval(mes2))
			{
				f.txtmeslab.value=eval("(12-"+mes1+")+"+mes2+"");
				ano2=ano2-1;
			}
			else
			{
				f.txtmeslab.value=eval(""+mes2+"-"+mes1+"");
			}
			f.txtanolab.value=eval(""+ano2+"-"+ano1+"");
		}
	  else
	  {
		alert ('La Fecha de Egreso debe ser mayor a la fecha de ingreso.');	  
		f.txtfecrettraant.value="";
	 }
	}

}

function calcular_anos_servio_previo()
{
   if ($F('txtcodper')!="")
  {
		function onGuardar2(respuesta)
		{  
			if (trim(respuesta.responseText) != "")
			{	
			  var respuestas = respuesta.responseText.split('&');
			  num_respuesta = -1;
			  num_respuesta++;
			  if (trim(respuestas[num_respuesta]) != "")
			  {	  
				var anotrabajoantfijo = JSON.parse(respuestas[num_respuesta]);
				$("txtanoservprefijo").value=anotrabajoantfijo;
			  }
			  num_respuesta++;
			  if (trim(respuestas[num_respuesta]) != "")
			  {	  
				var anoservprecont = JSON.parse(respuestas[num_respuesta]);
				$("txtanoservprecont").value=anoservprecont;
			  }
			  
			}
		}
		
		params = "operacion=ue_buscar_servicio_previo&codper="+$F('txtcodper');
		new Ajax.Request(url,{method:'get',parameters:params,onComplete:onGuardar2});	
  }

}

function sumar_sueldo()
{
  if ($('txtsuelpro').value=="")
  { suelpro = 0; }
  else
  {  
  	suelpro = $('txtsuelpro').value 
	uf_convertir (suelpro);
    suelpro = suelpro.replace ('.','');
    suelpro = suelpro.replace (',','.');
  }
  
  if ($('txtcompro').value=="")
  { compro = 0; }
  else
  { 
    compro = $('txtcompro').value;
	uf_convertir (compro);
    compro = compro.replace ('.','');
    compro = compro.replace (',','.');
   }

    suma = 	 parseFloat (suelpro) + parseFloat(compro);
    suma = uf_convertir (suma);
	$('txtsuetotpro').value= suma;
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

function ue_select_causa()
{
	f=document.form1;
	document.images["causa"].style.visibility="visible";
	document.form1.txtcodcausa.style.visibility="visible";
}



function ue_select_causa2()
{
	f=document.form1;
	document.images["causa"].style.visibility="hidden";
}


function ue_limpiar(tipo)
{
	f=document.form1;
	if(tipo=="0")
	{
		if(parseFloat(f.txtporpagben.value)>0)
		{
			f.txtmonpagben.value="0,00";
		}
	}
	if(tipo=="1")
	{
		if(parseFloat(f.txtmonpagben.value)>0)
		{
			f.txtporpagben.value="0,00";
		}
	}
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);


function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}



function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}







  
 //--------------------------------------------------------
//	Función que verifica que la fecha 2 sea mayor que la fecha 1
//----------------------------------------------------------
   function ue_comparar_fechas(fecha1,fecha2)
{
	vali=false;
	dia1 = fecha1.substr(0,2);
	mes1 = fecha1.substr(3,2);
	ano1 = fecha1.substr(6,4);
	dia2 = fecha2.substr(0,2);
	mes2 = fecha2.substr(3,2);
	ano2 = fecha2.substr(6,4);
	if (ano1 < ano2)
	{
		vali = true; 
	}
    else 
	{ 
    	if (ano1 == ano2)
	 	{ 
      		if (mes1 < mes2)
	  		{
	   			vali = true; 
	  		}
      		else 
	  		{ 
       			if (mes1 == mes2)
	   			{
 					if (dia1 <= dia2)
					{
		 				vali = true; 
					}
	   			}
      		} 
     	} 	
	}
	
	return vali;
	
}
   



//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
                           
  if (cal.dateClicked )
      cal.callCloseHandler();
}


function closeHandler(cal) {
  cal.hide();                        // hide the calendar

  _dynarch_popupCalendar = null;
}


function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.

    var cal = new Calendar(1, null, selected, closeHandler);
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use
 _dynarch_popupCalendar.showAtElement(el, "T");        // show the calendar

  return false;
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


function ue_cargar_foto()
{
	 if ($('txtcedper').value=="")
	 {
		alert("Debe llenar la cédula del personal"); 
	 }
	 else
	 {
	 	 pagina="sigesp_srh_cargar_foto.php?cedper="+$F('txtcedper');
		 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no,dependent=yes");
	 }
}

function ue_chequear_caja_ahorro()
{
   f=document.form1;
   if (f.chkcajahoper.checked)
   {
   		f.txtporcajahoper.readOnly=false;
   }
   else
   {
   		f.txtporcajahoper.value=0;
		f.txtporcajahoper.readOnly=true;
   }
  
	 
}

function ue_checkhijo(tipo)
{
	f=document.form1;
	if(f.cmbnexfam.value!="H")
	{
		
		alert("Esta opicón es solamente para para Hijos");
		if (tipo=='1')
		{
			f.chkhijesp.checked=false;
		}
		else if (tipo=='2')
		{
			f.chkbonjug.checked=false;
		}
	}
}