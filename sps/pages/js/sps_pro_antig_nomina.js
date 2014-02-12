// JavaScript Document

var url    = '../php/sps_pro_antig_nomina.php';
var params = 'operacion=';
var metodo = 'post';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecdes,txtfechas,txtfecingper,txtanoserant,txtmesserant,txtdiaserant,txtfecvig,btnantignom,btngenint");	
  limpiar_tabla_detalle();
  $('txtfecdes').value = "dd/mm/aaaa";
  $('txtfechas').value = "dd/mm/aaaa";
  $('txtfecvig').value = ("19/06/1997");
  scrollTo(0,0);
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_nuevo()
{	
	ue_cancelar();
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function limpiar_tabla_detalle()
{  
  var FILAS = $("dt_antig").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_antig",FILAS[f].id)}
  }
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function ue_buscarpersonal()
{
	pagina="sps_cat_personal.html.php";
	catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=540,height=250,resizable=yes,location=no,top=0,left=0");
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_cargar_registro(arr_datos)
{     
	  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  habilitar("txtfecdes,txtfechas,txtfecvig,btnantignom,btngenint");
	  //deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
	  $('txtfecingper').value = "";
	  $('txtanoserant').value = "";
	  $('txtmesserant').value = "";
	  $('txtdiaserant').value = "";
	  $('txtfecdes').focus();
}
//---------------------------------------------------------------------------------------------------------------------------------------------//
function ue_buscar()
{
	pagina="sps_cat_antiguedad.html.php";
	catalogo = popupWin(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
//---------------------------------------------------------------------------------------------------------------------------------------------//
function ue_cargarCatalogo(arr_datos)
{
	  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecdes,txtfechas,txtfecvig,btnantignom,btngenint");
	  $('txtfecdes').value  = "";
	  $('txtfechas').value  = "";
	  $('txtfecingper').value = "";
	  $('txtanoserant').value = "";
	  $('txtmesserant').value = "";
	  $('txtdiaserant').value = "";
	  ue_detalleantiguedad();
	  if ((navigator.appName == "Netscape"))
	  {	 
		   eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
	  }
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function restarFechas(cajaFechaInicio,cajaFechaFinal,tipoPeriodo)  
{
	f = document.form1;
	var fechainicio = eval("f."+cajaFechaInicio+".value;");
	var fechafinal  = eval("f."+cajaFechaFinal+".value;");
	if ((fechainicio != "") && (fechafinal != ""))
	{	  
	  var fechainicio = eval("f."+cajaFechaInicio+".value;");
	  var fechafinal  = eval("f."+cajaFechaFinal+".value;");
	  var fechaini = new Date();
	  fechaini.setFullYear(parseFloat(fechainicio.substr(6,4)),(parseFloat(fechainicio.substr(3,2))-1),parseFloat(fechainicio.substr(0,2)));
	  var fechafin = new Date();
	  fechafin.setFullYear(parseFloat(fechafinal.substr(6,4)),(parseFloat(fechafinal.substr(3,2))-1),parseFloat(fechafinal.substr(0,2)));
	  var periodo = ""; 
	  if (compararFechas(cajaFechaInicio,cajaFechaFinal,""))
	  {
		  var tiempoRestante = fechafin.getTime() - fechaini.getTime();		  		  
		  var divisor;
		  switch(tipoPeriodo)
		  {
			case 'd': divisor = 1000 * 60 * 60 * 24; break;
			case 'm': divisor = 1000 * 60 * 60 * 24 * 30; break;
			case 'a': divisor = 1000 * 60 * 60 * 24 * 365; break;
		  }  
		  periodo = (Math.floor(tiempoRestante / divisor)).toString();
	  }
	};
	return periodo;
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_fecha_ingreso()
{
  $('txtfecingper').value  = "";	
  function onFechaIngreso(respuesta)
  { 
    var fecha = JSON.parse(respuesta.responseText);
    li_ano = fecha.fecingper[1].substr(0,4);
	li_mes = fecha.fecingper[1].substr(5,2);
	li_dia = fecha.fecingper[1].substr(8,2);
	var fecingper = (li_dia+"/"+li_mes+"/"+li_ano);
	$('txtfecingper').value  = fecingper;
	deshabilitar("txtfecingper");
	$F('txtfecingper').refresh;
  }	
  params = "operacion=ue_fecha_ingreso&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom');
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onFechaIngreso});
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_tiempo_servicio()
{
	var ls_fecdes = $F('txtfecdes');
	var ls_fechas = $F('txtfechas');
	ls_ano = restarFechas("txtfecdes","txtfechas","a");
	ls_mes = restarFechas("txtfecdes","txtfechas","m");

	//años
	f.txtanoserant.value = ls_ano;
	//meses	
	ls_calc_ano = (ls_ano*12);
	ls_meses = ( ls_mes - ls_calc_ano - 1 );
	f.txtmesserant.value = ls_meses;
	//dias
	ls_diaini = (ls_fecdes.substr(0,2));
	ls_diafin = (ls_fechas.substr(0,2));
	ls_dia    = (ls_diafin - ls_diaini);
	if (ls_dia<0)
	{ 
		ls_mes=ls_fecdes.substr(3,2);
		switch (ls_mes){  
			case '01': ls_dias = 31 + ls_dia;break;
			case '02': ls_dias = 28 + ls_dia;break;
			case '03': ls_dias = 31 + ls_dia;break;
			case '04': ls_dias = 30 + ls_dia;break;
			case '05': ls_dias = 31 + ls_dia;break;
			case '06': ls_dias = 30 + ls_dia;break;
			case '07': ls_dias = 31 + ls_dia;break;
			case '08': ls_dias = 31 + ls_dia;break;
			case '09': ls_dias = 30 + ls_dia;break;
			case '10': ls_dias = 31 + ls_dia;break;
			case '11': ls_dias = 30 + ls_dia;break;
			case '12': ls_dias = 31 + ls_dia;break;
		}
		f.txtdiaserant.value = ls_dias;
	}
	else
	{
		f.txtdiaserant.value = ls_dia;	
	}
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_consultar_anticipos() 
{ 
  if ( (ue_valida_null($('txtcodper')))&&(ue_valida_null($('txtcodnom'))) )
  {
	  if (($('txtfecdes').value!='dd/mm/aaaa')&&($('txtfechas').value!='dd/mm/aaaa'))
	  {
		  function onAnticipos(respuesta)
		  {   
		  	if (trim(respuesta.responseText)!="")
			{  
		      var anticipo = JSON.parse(respuesta.responseText);
			  $('hidfecant').value = anticipo.fecantper[1];
			  $('hidmonant').value = anticipo.monant[1];
			}
			else
			{
			  $('hidfecant').value = '01/01/1900';
			  $('hidmonant').value = 0;
			}
					
		  }
		  var params = 
	      {
	      	operacion : "ue_anticipos",
		  	codper    : $F('txtcodper'),
		  	codnom    : $F('txtcodnom'),
		  	fecdes    : $F('txtfecdes'),
		  	fechas    : $F('txtfechas'),
	      };
		  new Ajax.Request(url,{method:metodo,parameters:$H(params).toQueryString(),onComplete:onAnticipos});
		  ue_getDataAntigNomina();	
	  }
	  else
	  { alert("Debe indicar un rango de fecha.")}	  
  }  
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_getDataAntigNomina()
{
	if ( (ue_valida_null($('txtcodper')))&&(ue_valida_null($('txtcodnom')))&&(ue_valida_null($('txtfecdes')))&&(ue_valida_null($('txtfechas'))) )
	{
		ue_fecha_ingreso();
		ue_tiempo_servicio();
		var params = 
	    {
	      operacion : "ue_antig_nomina",
		  codper    : $F('txtcodper'),
		  codnom    : $F('txtcodnom'),
		  fecdes    : $F('txtfecdes'),
		  fechas    : $F('txtfechas'),
	    };
		new Ajax.Request(url,{method:metodo,parameters:$H(params).toQueryString(),onComplete:onAntiguedadNomina});		
	}
	else
	{ alert("Debe indicar el Rango de Fecha."); }
	function onAntiguedadNomina(respuesta)
  	{     
  		if (trim(respuesta.responseText) != "")
	    {  
	      //Mostramos los Datos del Detalle
		  var antig_nomina = JSON.parse(respuesta.responseText);
		  campos = "anocurper,mescurper,bonvacper,bonfinper,sueintper,apoper,diafid,diaadi";
		  campos = campos.split(',');
		  //Limpiamos los Datos del Detalle
		  limpiar_tabla_detalle();
		  ld_monacuant=0.00;
		  ld_salparant=0.00;
		  
		  for (f=0; f<antig_nomina.anocurper.length; f++)
		  {  
		  	  li_ano = eval("antig_nomina.anocurper["+f+"];");
		   	  li_mes = eval("antig_nomina.mescurper["+f+"];");
		   	  if ((li_mes==1)||(li_mes==3)||(li_mes==5)||(li_mes==7)||(li_mes==8)||(li_mes==10)||(li_mes==12)) { li_dia=31;}
		   	  else if ((li_mes==4)||(li_mes==6)||(li_mes==9)||(li_mes==11)) { li_dia=30; }
		   	  else { ld_result = (li_ano/4);
					 var ls_result = new String(ld_result);
					 if (ls_result.indexOf('.')>0)
					 { li_dia=28; }
					 else { li_dia=29; } }
			  if (li_mes<10) li_mes="0"+li_mes;
		  	  ls_fecant= li_dia+"/"+li_mes+"/"+li_ano;
		  	  //ANTICIPOS
		  	  li_anoant = $('hidfecant').value.substr(0,4);
			  li_mesant = $('hidfecant').value.substr(5,2);
			  if (li_anoant==1900){ld_monantant = "0,00";}
			  else if ((li_anoant==li_ano)&&(li_mesant==li_mes))
			  {  
				ld_monantant = $('hidmonant').value;
			  } 	 
		  	  else {ld_monantant = "0,00";}
							     
			  ld_salbas    = "0,00";
			  ld_incbonvac = uf_convertir(eval("antig_nomina.bonvacper["+f+"];"),2);
			  ld_incbonnav = uf_convertir(eval("antig_nomina.bonfinper["+f+"];"),2);
			  ld_salintdia = uf_convertir(eval("antig_nomina.sueintper["+f+"];"),2);
			  ld_diabas    = uf_convertir(eval("antig_nomina.diafid["+f+"];"),2);
			  ld_diacom    = uf_convertir(eval("antig_nomina.diaadi["+f+"];"),2);
			  ld_monant    = eval("antig_nomina.apoper["+f+"];");
		  	  ld_monacuant = (parseFloat(eval(ld_monant))+parseFloat(ld_monacuant));
			  ld_salparant = (parseFloat(eval(ld_salparant))+(parseFloat(ld_monant)-parseFloat(eval(ld_monantant))));
			  ld_porint    = "0,00";
			  ld_diaint    = "0,00";
			  ld_monint    = "0,00";
			  ld_monacuint = "0,00";
			  ld_saltotant = "0,00";
			  			  
			  valores = new Array(ls_fecant,ld_salbas,ld_incbonvac,ld_incbonnav,ld_salintdia,ld_diabas,ld_diacom,uf_convertir(ld_monant,2),uf_convertir(ld_monacuant,2),uf_convertir(ld_monantant,2),uf_convertir(ld_salparant,2),ld_porint,ld_diaint,ld_monint,ld_monacuint,ld_saltotant);	
		  
			  ue_agregar_detalle(valores);
		  }
		}	  
  	}	
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_agregar_detalle(valores)  
{
      var nuevaFila = clonarFila("dt_antig","fila0");
      copiarDatosDetalle(nuevaFila,valores);
      try{$('txtfecdes').focus();}
	  catch(e){}
 
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function copiarDatosDetalle(Fila,valores)
{
  alineaciones = new Array("center","right","right","right","right","center","center","right","right","right","right","right","center","center","center","right","right","right");
  for (cnt=0; cnt < valores.length; cnt++)
  {
	  agregarColumna(Fila,valores[cnt],alineaciones[cnt]);
  }
}
//---------------------------------------------------------------------------------------------------------------------------------------------//
function ue_calcular_interes()
{	
	function onGenerarInteres(respuesta)
	{   
		if(trim(respuesta.responseText) !="" )  
		{		
		  //Mostramos los Datos del Detalle
		  var det_antig = JSON.parse(respuesta.responseText);
		  //Limpiamos los Datos del Detalle
		  limpiar_tabla_detalle();

		  for ( f=0; f < det_antig.periodo.length; f++ )
		  {  
		   	  ld_fecant    = eval("det_antig.periodo["+f+"];");
		      ld_salbas    = uf_convertir(eval("det_antig.salbas["+f+"];"),2);
			  ld_incbonvac = uf_convertir(eval("det_antig.incbonvac["+f+"];"),2);
			  ld_incbonnav = uf_convertir(eval("det_antig.incbonnav["+f+"];"),2);
			  ld_salintdia = uf_convertir(eval("det_antig.salint["+f+"];"),2);
			  ld_diabas    = uf_convertir(eval("det_antig.diabas["+f+"];"),2);
			  ld_diacom    = uf_convertir(eval("det_antig.diacom["+f+"];"),2);
			  ld_monant    = uf_convertir(eval("det_antig.monant["+f+"];"),2);
			  ld_monacuant = uf_convertir(eval("det_antig.monacuant["+f+"];"),2);
			  ld_monantant = uf_convertir(eval("det_antig.monantant["+f+"];"),2);
			  ld_salparant = uf_convertir(eval("det_antig.salparant["+f+"];"),2);
			  ld_porint    = uf_convertir(eval("det_antig.taspor["+f+"];"),2);
			  ld_diaint    = eval("det_antig.diaint["+f+"];");
			  ld_monint    = uf_convertir(eval("det_antig.intper["+f+"];"),2);
			  ld_monacuint = uf_convertir(eval("det_antig.intacu["+f+"];"),2);
			  ld_saltotant = uf_convertir(eval("det_antig.saltotant["+f+"];"),2);
			  li_ano = ld_fecant.substr(0,4);
			  li_mes = ld_fecant.substr(5,2);
			  li_dia = ld_fecant.substr(8,2);
			  ls_fecant= li_dia+"/"+li_mes+"/"+li_ano;
			  valores = new Array(ls_fecant,ld_salbas,ld_incbonvac,ld_incbonnav,ld_salintdia,ld_diabas,ld_diacom,ld_monant,ld_monacuant,ld_monantant,ld_salparant,ld_porint,ld_diaint,ld_monint,ld_monacuint,ld_saltotant);	
 			  ue_agregar_detalle(valores);
		  }
		}
	}
	var antig_detalle = new Array();
	var filas = $('dt_antig').getElementsByTagName("tr");
	if (filas.length>1)
	{
		for (f=1; f<filas.length; f++)
		{  
			var columnas = filas[f].getElementsByTagName("td");
			var antig_nomina =
				{
					  "codper"     : $F('txtcodper'),
					  "codnom"     : $F('txtcodnom'),
					  "fecincsue"  : columnas[0].innerHTML,
					  "salbas"     : columnas[1].innerHTML,
					  "incbonvac"  : columnas[2].innerHTML,
					  "incbonnav"  : columnas[3].innerHTML,
					  "salint"     : columnas[4].innerHTML,
					  "diabas"     : columnas[5].innerHTML,
					  "diacom"     : columnas[6].innerHTML,
					  "monant"     : columnas[7].innerHTML,
					  "monacuant"  : columnas[8].innerHTML,
					  "monantant"  : columnas[9].innerHTML,
					  "salparant"  : columnas[10].innerHTML 
				}				
				antig_detalle[f-1] = antig_nomina;
		}
		var reg_prestaciones =
		{
			"codper":$F('txtcodper'),
			"codnom":$F('txtcodnom'),
			"dt_antig":antig_detalle
		};
		var objeto = JSON.stringify(reg_prestaciones);
		params = "operacion=ue_calcular_interes&objeto="+objeto;
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGenerarInteres});
	}
	else
	{ alert("Debe obtener los datos de Antiguedad antes de generar los Intereses.");}	
}
//-------------------------------------------------------------------------------------------------------------------------------------------//

function ue_guardar()
{
	lb_valido=true;
	var la_objetos =new Array("txtcodper","txtcodnom");
	var la_mensajes=new Array("el código personal", "el código nómina" );
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
		if(($F('hidguardar')== "modificar")&&($F('hidpermisos').indexOf('m',0)<0 ))
		{
			alert("No tiene permiso para actualizar información.");
		}
		else
		{
			function onGuardar(respuesta)
			{   
				ue_cancelar();
				if(trim(respuesta.responseText) !="" )
				{alert(respuesta.responseText);}
			}
			//Arreglo de detalles sueldos
			var antig_detalle = new Array();
			var filas = $('dt_antig').getElementsByTagName("tr");
			for (f=1; f<filas.length; f++)
			{
				var IdFila   = filas[f].getAttribute("id");
				var columnas = filas[f].getElementsByTagName("td");
				var antiguedad = 
				{
				  "codper"     : $F('txtcodper'),
				  "codnom"     : $F('txtcodnom'),
				  "fecincsue"  : columnas[0].innerHTML,
				  "anoserant"  : $F('txtanoserant'),
				  "messerant"  : $F('txtmesserant'),
				  "diaserant"  : $F('txtdiaserant'),
				  "salbas"     : columnas[1].innerHTML,
				  "incbonvac"  : columnas[2].innerHTML,
				  "incbonnav"  : columnas[3].innerHTML,
				  "salint"     : columnas[4].innerHTML,
				  "salintdia"  : columnas[4].innerHTML,
				  "diabas"     : columnas[5].innerHTML,
				  "diacom"     : columnas[6].innerHTML,
				  "diaacu"     : (eval(columnas[5].innerHTML)+eval(columnas[6].innerHTML)),
				  "monant"     : columnas[7].innerHTML,
				  "monacuant"  : columnas[8].innerHTML,
				  "monantant"  : columnas[9].innerHTML,
				  "salparant"  : columnas[10].innerHTML,
				  "porint"     : columnas[11].innerHTML,
				  "diaint"     : columnas[12].innerHTML,
				  "monint"     : columnas[13].innerHTML,
				  "monacuint"  : columnas[14].innerHTML,
				  "saltotant"  : columnas[15].innerHTML
				   
				}				
				antig_detalle[f-1] = antiguedad;
			}
			var regantiguedad =
			{
				"codper":$F('txtcodper'),
				"codnom":$F('txtcodnom'),
				"dt_antig":antig_detalle
			};
			var objeto = JSON.stringify(regantiguedad);
			params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		}
	}	
 }
 
function ue_eliminar()
{
  lb_valido = true;
  var la_objetos=new Array("txtcodper","txtcodnom");
  var la_mensajes=new Array("el código personal", "el código nómina" );
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar los datos de Antiguedad. ?"))
	{
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText);
		ue_cancelar();
	  }
	   params = "operacion=ue_eliminar&codper="+trim($F('txtcodper'))+"&codnom="+trim($F('txtcodnom'));
	   new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});	
	}
	else
	{
	   alert("Eliminación Cancelada !!!");	  
	   ue_cancelar();
	}
  };
}
//---------------------------------------------------------------------------------------------------------------------------------------------//
function ue_detalleantiguedad()
{
  if ( (ue_valida_null($('txtcodper')))&&(ue_valida_null($('txtcodnom'))) )
  {
	params = "operacion=ue_detalleantiguedad&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom');  
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onDetalleAntiguedad});		
  }
  
  function onDetalleAntiguedad(respuesta)
  {
  	  $('hidguardar').value = "modificar";
	  if (trim(respuesta.responseText) != "")
	  {   
		  //Mostramos los Datos del Detalle
		  var det_antig = JSON.parse(respuesta.responseText);
		  campos = "fecant,salbas,incbonvac,incbonnav,salintdia,diabas,diacom,monant,monacuant,monantant,salparant,porint,diaint,monint,monacuint,saltotant";
		  campos = campos.split(',');
		   var cajas = new Array("txtfecdes");
		  //Limpiamos los Datos del Detalle
		  limpiar_tabla_detalle();
		  for ( f=0; f < det_antig.fecant.length; f++ )
		  {  
		    for (c=0; c<campos.length; c++)
		    {  
		  	  ld_fecant = eval("det_antig."+campos[0]+"["+f+"];");
			  ld_salbas = uf_convertir(eval("det_antig."+campos[1]+"["+f+"];"),2);
			  ld_incbonvac = uf_convertir(eval("det_antig."+campos[2]+"["+f+"];"),2);
			  ld_incbonnav = uf_convertir(eval("det_antig."+campos[3]+"["+f+"];"),2);
			  ld_salintdia = uf_convertir(eval("det_antig."+campos[4]+"["+f+"];"),2);
			  ld_diabas = uf_convertir(eval("det_antig."+campos[5]+"["+f+"];"),2);
			  ld_diacom = uf_convertir(eval("det_antig."+campos[6]+"["+f+"];"),2);
			  ld_monant = uf_convertir(eval("det_antig."+campos[7]+"["+f+"];"),2);
			  ld_monacuant = uf_convertir(eval("det_antig."+campos[8]+"["+f+"];"),2);
			  ld_monantant = uf_convertir(eval("det_antig."+campos[9]+"["+f+"];"),2);
			  ld_salparant = uf_convertir(eval("det_antig."+campos[10]+"["+f+"];"),2);
			  ld_porint = uf_convertir(eval("det_antig."+campos[11]+"["+f+"];"),2);
			  ld_diaint = uf_convertir(eval("det_antig."+campos[12]+"["+f+"];"),2);
			  ld_monint = uf_convertir(eval("det_antig."+campos[13]+"["+f+"];"),2);
			  ld_monacuint = uf_convertir(eval("det_antig."+campos[14]+"["+f+"];"),2);
			  ld_saltotant = uf_convertir(eval("det_antig."+campos[15]+"["+f+"];"),2);
			  
			  li_ano = ld_fecant.substr(0,4);
			  li_mes = ld_fecant.substr(5,2);
			  li_dia = ld_fecant.substr(8,2);
			  ls_fecant= li_dia+"/"+li_mes+"/"+li_ano;
			  valores = new Array(ls_fecant,ld_salbas,ld_incbonvac,ld_incbonnav,ld_salintdia,ld_diabas,ld_diacom,ld_monant,ld_monacuant,ld_monantant,ld_salparant,ld_porint,ld_diaint,ld_monint,ld_monacuint,ld_saltotant);	
		    }
			ue_agregar_detalle(valores);
		  }
	   }
	   try
	   {catalogo.close();}
	   catch(e)
	   {}
	} //end of onDetalleAntiguedad(respuesta)
}
//---------------------------------------------------------------------------------------------------------------------------------------------//