// JavaScript Document
var url    = '../php/sps_pro_liquidacion.php';
var params = 'operacion=';
var metodo = 'get';
var catalogo;

Event.observe(window, 'load', ue_inicializar , false);

function ue_inicializar()
{
  params = "operacion=ue_inicializar";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
  function onInicializar(respuesta)
  {  
	if (trim(respuesta.responseText) != "")
	{	
		var respuestas = respuesta.responseText.split('&');
	    num_respuesta = -1;
		// Causa de Retiro
		num_respuesta++;
		if (trim(respuestas[num_respuesta]) != "")
		{	  
			var causaretiro = JSON.parse(respuestas[num_respuesta]);
			for (i=0; i<causaretiro.codcauret.length; i++)
			     $('cmbcauret').options[$('cmbcauret').options.length] = new Option(causaretiro.dencauret[i],causaretiro.codcauret[i]);
		}                                                                    
		// Articulos
		num_respuesta++;
		if (trim(respuestas[num_respuesta]) != "")
		{	 
			var articulo = JSON.parse(respuestas[num_respuesta]);
			for (i=0; i<articulo.conart.length; i++)
			     $('cmbarticulo').options[$('cmbarticulo').options.length] = new Option(articulo.conart[i], articulo.id_art[i]);
		}
	 }			 
  }	// end onInicializar
  ue_cancelar();
  ue_nuevo();
} /* end function*/
/*---------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_cancelar()
{
   document.form1.reset();
   limpiar_datos_detalle();
   limpiar_tabla_detalle();
   deshabilitar("txtfecliq,txtnumliq,txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtcargo,txtfecingper,txtsalintper,txtsalintdia,txtano,txtmes,txtdia,cmbcauret,txtfecdes,txtfechas,txtdescripcion,txtdiasal,txtmonto,cmbarticulo,txtsc_cta_ps,btnantiguedad,btnvacacion,btnbonvac,btncalcular,btnincluir,btndeudaanterior,txtdedicacion,txttipopersonal,txtdiaabofid");
   scrollTo(0,0);
}
/*****************************************************************
  Funcion que elimina una fila de una tabla
******************************************************************/
function eliminarFila(id_tabla, id_fila)
{
    var TABLA = $(id_tabla);
    if(TABLA.rows.length > 1)
    {
	  var FILAS = TABLA.rows;
	  var eliminado = false;
	  var fila = FILAS.length - 1;
	  while ((eliminado == false) && (fila > 0))
	  {
	    if (FILAS[fila].id == id_fila)
	    {
	  	  TABLA.deleteRow(fila);
		  eliminado = true;
	    }
	    else
	    {fila--;}
	  }
    }
	ue_sumarTotal();
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_nuevo()
{	
  function onNuevo(respuesta)
  { 
    ue_cancelar();
	$('hidguardar').value = "insertar";
	$('hiddiaabo').value = 0;
	$('txtnumliq').value  = trim(respuesta.responseText);
	$('txtfecliq').value  = hoy();
	$('txtfecegrper').value = hoy();
	$('txtasignacion').value= "0,00";
	$('txtdeduccion').value = "0,00";
	$('txttotal').value     = "0,00";
	$('txtdiaabofid').value = "0,00";
	$('txtestliq').value    = "Registrada";
	deshabilitar("txtnumliq,txtfecliq,txtdedicacion,txttipopersonal,btnantiguedad,btnvacacion,btnbonvac,btncalcular,btnincluir,btndeudaanterior");
    habilitar("txtsalintper,txtfecdes,txtfechas,cmbcauret,txtdescripcion,txtdiasal,txtmonto,cmbarticulo,txtdiaabofid");
	$('txtfecdes').focus();
  }	
  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onNuevo});
}
/*----------------------------------------------------------------------------------------------------------------------------------------------*/          
function ue_tiempo_servicio()
{
	if ( (ue_valida_null($('txtfecingper')))&& (ue_valida_null($('txtfecegrper'))) )
	{
		var ls_fecdes = $F('txtfecingper');
		var ls_fechas = $F('txtfecegrper');
		
		ls_ano = restarFechasLiq("txtfecingper","txtfecegrper","a");
		ls_mes = restarFechasLiq("txtfecingper","txtfecegrper","m");
		//años
		f.txtano.value = ls_ano;
	
		//meses	
		ls_calc_ano = (ls_ano*12);
		ls_meses = ( ls_mes - ls_calc_ano );
		f.txtmes.value = ls_meses;
	
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
			f.txtdia.value = ls_dias;
		}
		else
		{
			f.txtdia.value = ls_dia;	
		}
	}// ue_valida_null
	else
	{ alert("Debe llenar los campos de Fecha de Ingreso y Egreso del Trabajador.  ");}
}
/*------------------------------------------------------------------------------------------------------------------------------------------------------*/
function restarFechasLiq(cajaFechaInicio,cajaFechaFinal,tipoPeriodo)  
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
/*----------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_detalleliquidacion()
{
  if ( (ue_valida_null($('txtcodper')))&&(ue_valida_null($('txtcodnom')))&&(ue_valida_null($('txtnumliq'))) )
  {
	params = "operacion=ue_detalleliquidacion&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom')+"&numliq="+$F('txtnumliq');  
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequear});		
  }
  function onChequear(respuesta)
  {
	  deshabilitar("txtfecliq,txtnumliq,txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtcargo,txtfecingper,txtsalintper,txtsalintdia,txtano,txtmes,txtdia,cmbcauret");
	  habilitar("txtsalintper,txtfecdes,txtfechas,cmbcauret,txtdescripcion,txtdiasal,txtmonto,cmbarticulo");
	  $('txtfecdes').focus();		  
	  $('hidguardar').value = "modificar";
	  if (trim(respuesta.responseText) != "")
	  { 	  	  
		  var det_liq = JSON.parse(respuesta.responseText);
		  campos = "desespliq,diapag,subtotal";
		  campos = campos.split(',');
		  var cajas = new Array("txtdescripcion","txtdiasal","txtmonto");
		  limpiar_tabla_detalle(true);
		  for (f=0; f<det_liq.desespliq.length; f++)
		  {  
			for (c=0; c<campos.length; c++)
		    {
			  if (cajas[c]=="txtdiasal")
			  {     
			    aux = eval(cajas[c]+".value = uf_convertir( det_liq."+campos[c]+"["+f+"], 2 );");
				if (aux=="0,00") 
				{ eval(cajas[c]+".value = '---' "); }
				else
			  	{ eval(cajas[c]+".value = uf_convertir( det_liq."+campos[c]+"["+f+"], 2 );"); }
			  }     
			  if (cajas[c]=="txtmonto")
			  {     
			  	eval(cajas[c]+".value = uf_convertir( det_liq."+campos[c]+"["+f+"], 2 );");
			  }     
			  if (cajas[c]=="txtdescripcion")
			  {
			  	eval(cajas[c]+".value = det_liq."+campos[c]+"["+f+"];");
			  }
			}
			ue_agregar_detallexconsulta();
		  }
	   }
	   try
	   {catalogo.close();}
	   catch(e)
	   {}
	} //end of onChequear(respuesta)
}
/*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_buscar()
{
  pagina="sps_cat_liquidacion.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
/*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_cargar_registro_liquidacion(arr_datos)
{ 
	ue_cancelar();
	$('hidguardar').value = "modificar";
	var cajas = new Array('txtnumliq','txtnomper','txtapeper','txtcodper','txtcodnom','txtdennom','cmbcauret','txtfecliq','txtfecingper','txtfecegrper','txtsalintper','txtcargo','txtano','txtmes','txtdia','txtasignacion','txtdeduccion','txttotal','txtestliq','txtdedicacion','txttipopersonal','txtdiaabofid');
	for (i=0; i<cajas.length; i++)
	{
	  if (cajas[i].substring(0,6)==="txtfec" )
	  {   
	  	  li_ano = arr_datos[i].substr(0,4);
		  li_mes = arr_datos[i].substr(5,2);	
		  li_dia = arr_datos[i].substr(8,2);
		  $(cajas[i]).value = (li_dia+"/"+li_mes+"/"+li_ano);  
	  }
	  else
	  {
	  	$(cajas[i]).value = trim(arr_datos[i]);
	  }
	   if (arr_datos[18] == 'R')
	   { $(cajas[18]).value = 'Registrada'; }
	   if (arr_datos[18] == 'A')
	   { $(cajas[18]).value = 'Aprobada'; }
	   if (arr_datos[18] == 'P')
	   { $(cajas[18]).value = 'Pagada'; }
	}
	if ($('txtsalintper').value!=""){  $('txtsalintdia').value=($('txtsalintper').value/30); }
	$('txtsalintper').value = uf_convertir($('txtsalintper').value,2);
	$('txtsalintdia').value = uf_convertir($('txtsalintdia').value,2);
	$('txtasignacion').value= uf_convertir($('txtasignacion').value,2);
	$('txtdeduccion').value = uf_convertir($('txtdeduccion').value,2);
	$('txttotal').value     = uf_convertir($('txttotal').value,2);
	ue_detalleliquidacion();
	if (($('txtestliq').value=="Aprobada")||($('txtestliq').value=="Pagada"))
	{ deshabilitar("txtfecliq,txtnumliq,txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtcargo,txtdedicacion,txttipopersonal,txtfecingper,txtfecegr,txtsalintper,txtsalintdia,txtano,txtmes,txtdia,cmbcauret,txtfecdes,txtfechas,txtdescripcion,txtdiasal,txtmonto,cmbarticulo,btncalcular,btnantiguedad,btnvacacion,btnbonvac,btnincluir,btndeudaanterior");	}
	else { habilitar("btncalcular,btnantiguedad,btnvacacion,btnbonvac,btnincluir,btndeudaanterior"); }
	if ((navigator.appName == "Netscape"))
	{	 
	   eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
	}
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_buscarpersonal()
{
  pagina="sps_cat_personalliq.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
/*----------------------------------------------------------------------------------------------------------------------------------------------*/          
function ue_cargar_registro(arr_datos)
{ 
  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom','txtfecingper','txtsalintper','txtcargo','txtdedicacion','txttipopersonal');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecingper,txtsalintper,txtsalintdia,txtcargo,txtdedicacion,txttipopersonal,txtano,txtmes,txtdia");
  habilitar("txtfecdes,txtfechas,txtdescripcion,txtdiasal,txtmonto,cmbarticulo,btnantiguedad,btnvacacion,btnbonvac,btncalcular,btnincluir,btndeudaanterior");
  li_ano = $F('txtfecingper').substr(0,4);
  li_mes = $F('txtfecingper').substr(5,2);
  li_dia = $F('txtfecingper').substr(8,2);
  $('txtfecingper').value = li_dia+"/"+li_mes+"/"+li_ano;
  ld_salintper = $F('txtsalintper');
  ld_salintdia = (ld_salintper/30);
  $('txtsalintper').value = uf_convertir(ld_salintper,2);
  $('txtsalintdia').value = uf_convertir(ld_salintdia,2);
  $('txtfecdes').value = $('txtfecingper').value;
  $('txtfechas').value = $('txtfecegrper').value;
  ue_tiempo_servicio();
  try
  {catalogo.close();}
  catch(e)
  {}

}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_buscar_sc_cuenta()
{ 
  pagina="sps_cat_sc_cuenta.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
function ue_cargar_registro_sc_cuenta(arr_datos)
{
  var cajas = new Array('txtsc_cta_ps');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtsc_cta_ps");
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function limpiar_datos_detalle()
{
  $('txtdescripcion').value = "";
  $('txtdiasal').value = "";
  $('txtmonto').value = "";
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function limpiar_tabla_detalle()
{  
  var FILAS = $("dt_liquidacion").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{
		eliminarFila("dt_liquidacion",FILAS[f].id);
		ue_sumarTotal();  
	}
  }
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_validar_deduccion()
{
	lb_valido=true;
	ld_monto = $F('txtmonto');
	if (ld_monto.indexOf('-')==-1) //es asignación
	{
		lb_valido=false;
	}
	return lb_valido
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_agregar_detalle()  
{
  lb_valido=true;
  var la_objetos =new Array ("txtdescripcion","txtdiasal","txtmonto");  
  var la_mensajes=new Array ("la descripción ","los días de salario","el monto calculado.");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  if(lb_valido)
  {
      var nuevaFila = clonarFila("dt_liquidacion","fila0");
      lb_ok = ue_validar_deduccion();	    
      copiarDatosDetalle(nuevaFila,lb_ok); 	
      limpiar_datos_detalle();
      try{$('txtdescripcion').focus();}
	  catch(e){}
  }
  ue_sumarTotal();  
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_agregar_detallexconsulta()  
{
  lb_valido=true;
  var la_objetos =new Array ("txtdescripcion","txtdiasal","txtmonto");  
  var la_mensajes=new Array ("la descripción ","los días de salario","el monto calculado.");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  if(lb_valido)
  {
      var nuevaFila = clonarFila("dt_liquidacion","fila0");
      copiarDatosDetalle(nuevaFila);
      limpiar_datos_detalle();
      try
	  {catalogo.close();}
	  catch(e)
	  {}
  }
}
//-------------------------------------------------------------------------------------------------------------------------------------------//

function copiarDatosDetalle(Fila, deducion)
{
  if ($F("txtestliq")==="Registrada")
  {
	  var boton =  '<div class="menuBar">';
		  boton += '<a class="menuButton" href="javascript:eliminarFila(\'dt_liquidacion\','+Fila.id+');">';
		  boton += '<img src="../../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" width="15" height="15" border="0" align="absmiddle">';
		  boton += '</a>';
		  boton += '</div>';  	  
  }
  else
  {
	  var boton =  '<div class="menuBar">';
		  boton += '<a class="menuButton">';
		  boton += '<img src="../../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" width="15" height="15" border="0" align="absmiddle">';
		  boton += '</a>';
		  boton += '</div>';  
  }
  if (deducion)
  {
  	  var hidden = '<input name="hid'+Fila.id+'" type="hidden" value="'+$F("txtsc_cta_ps")+'">';
  }
  else
  { 
	  var hidden = '<input name="hid'+Fila.id+'" type="hidden" value="">';
  }
  var ultima_columna = boton+hidden; 
  valores = new Array(Fila.id,$F("txtdescripcion"),$F("txtdiasal"),$F("txtmonto"),ultima_columna );
  alineaciones = new Array("center","left","center","right","center");
  
  for (cnt=0; cnt < valores.length; cnt++)
  {
	  agregarColumna(Fila,valores[cnt],alineaciones[cnt]);
  }
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/          

//--------------------------------------------------------------------------------------------------------------------------------------------//
function ue_vacaciones()  
{
	params = "operacion=ue_vacaciones&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom'); 
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onVacaciones});
	
	function onVacaciones(respuesta)
	{ 	
		if (trim(respuesta.responseText)!="")
		{	
			var vacaciones = JSON.parse(respuesta.responseText);
			for (i=0; i<vacaciones.fecvenvac.length; i++)  
			{  
				ld_fecvenvac = vacaciones.fecvenvac[i];
				li_diavac    = vacaciones.diavac[i];  
				ld_sueintvac = vacaciones.sueintvac[i];
				li_diaadivac = vacaciones.diaadivac[i];
		
				li_año = ld_fecvenvac.substr(0,4);
				li_mes = ld_fecvenvac.substr(5,2);
				li_dia = ld_fecvenvac.substr(8,2);
				var fecvac = (li_dia+"/"+li_mes+"/"+li_año);
				var li_diatotal= (eval(li_diavac)+eval(li_diaadivac));
				
				if (ld_sueintvac!=0) {
					var ld_monto = parseFloat(ld_sueintvac/30*eval(li_diatotal)); }
				else	
				{  var ld_monto = parseFloat(uf_convertir_monto($F('txtsalintper'))/30*eval(li_diatotal)); }
				$('txtdescripcion').value = "Vacaciones vencidas al "+fecvac;	
				$('txtdiasal').value = li_diatotal;
				$('txtmonto').value  = uf_convertir(ld_monto,2);
				ue_agregar_detalle();
			}
	 	}	
		else
		{ 	
			alert(trim(respuesta.responseText));
		}
	} //onVacaciones
}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function ue_bonovacacional()  
{
	params = "operacion=ue_bonovacacional&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom'); 
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onBonoVacacional});
	
	function onBonoVacacional(respuesta)
	{ 
		if (trim(respuesta.responseText)!="")
		{	
			var bonovac = JSON.parse(respuesta.responseText);
			for (i=0; i<bonovac.sueintbonvac.length; i++)  
			{  
			 	ld_fecvenvac = bonovac.fecvenvac[i];
				ld_sueintbonvac = bonovac.sueintbonvac[i];
				li_diabonvac = bonovac.diabonvac[i];
		
				li_año = ld_fecvenvac.substr(0,4);
				li_mes = ld_fecvenvac.substr(5,2);
				li_dia = ld_fecvenvac.substr(8,2);
				var fecbonvac = (li_dia+"/"+li_mes+"/"+li_año);
				if (ld_sueintbonvac!=0) {
					var ld_monto = parseFloat(ld_sueintbonvac/30*eval(li_diabonvac)); }
				else	
				{  var ld_monto = parseFloat(ld_sueintbonvac/30*eval(li_diabonvac)); }
				$('txtdescripcion').value = "Bono Vacacional al "+fecbonvac;	
				$('txtdiasal').value = li_diabonvac;
				$('txtmonto').value  = uf_convertir(ld_monto,2);
				ue_agregar_detalle();
			}
	 	}	
		else {alert(trim(respuesta.responseText)); }
	} //onBonoVacacional
}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function ue_extraer_antiguedad()  
{
	if ( (ue_valida_null($('txtfecdes')))&& (ue_valida_null($('txtfechas'))) )
	{
		$('hidfecdes').value=$F('txtfecdes');
		$('hidfechas').value=$F('txtfechas');
		params = "operacion=ue_antiguedad&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom')+"&fecdes="+$F('txtfecdes')+"&fechas="+$F('txtfechas');  
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onAntiguedad});
	}
	else
	{
		alert("Debe indicar las fechas de búsqueda de calculos de antigüedad.");	
	}
	function onAntiguedad(respuesta)
	{   
		if (trim(respuesta.responseText)!="")
		{	
			var antiguedad = JSON.parse(respuesta.responseText);	  
			for (i=0; i<antiguedad.diasal.length; i++)  
			{  
				ls_diasal = antiguedad.diasal[i];  
				ld_monant = antiguedad.monant[i];
				ld_monantant = antiguedad.monantant[i];
				ld_monint = antiguedad.monint[i];
			}	
			for (j=0; j<=1; j++)  
			{
				if (j==0)	
				{
					$('txtdescripcion').value = "Antiguedad Art.108 LOT. ";	
					$('txtdiasal').value = ls_diasal;
					$('txtmonto').value  = uf_convertir(eval(ld_monant-ld_monantant),2);
					ue_agregar_detalle();
				}
				else if (j==1)
				{
					$('txtdescripcion').value = "Intereses ";	
					$('txtdiasal').value = "-------";
					$('txtmonto').value  = uf_convertir(ld_monint,2);
					ue_agregar_detalle();
				}
			}
	 	}	
	}
}

function ue_calcular_sueldo_diario()
{
	var ld_bonofin = 0;
	var ld_bonovac = 0;
	var ld_cajaaho = 0;
	var ld_saldiaint= 0;
	var fecha = $F('txtfecegrper');
	var li_ano = fecha.substr(6,4);
	var ld_salint = uf_convertir_monto($F('txtsalintper'));
	var ld_saldia = eval(ld_salint/30);
	params = "operacion=ue_incidencias&ano="+li_ano+"&tipoper="+trim($F('txttipopersonal'))+"&dedicacion="+trim($F('txtdedicacion'));
    new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onIncidencias});
    function onIncidencias(respuesta)
	{  
	  	if (trim(respuesta.responseText) != "")
		{	
			var incidencias = JSON.parse(respuesta.responseText);	  
			li_incvac = incidencias.diabonvacfid[1];
			li_incfin = incidencias.diabonfinfid[1];
			if ( chkbonofin.checked )
			{ 
				ld_bonofin = eval(ld_salint*(li_incfin/30)/360);
			}
			if ( chkbonovac.checked )
			{
				ld_bonovac = eval(ld_saldia*li_incvac/360);
			}
			if ( chkcajaaho.checked )
			{
				ld_cajaaho = eval(ld_salint/10/30);
			}
			ld_saldiaint = (ld_saldia+eval(ld_bonofin)+eval(ld_bonovac)+eval(ld_cajaaho))
			$('txtsalintdia').value = uf_convertir(ld_saldiaint,2);
		}	
	
	 }	// end onIncidencias
	
}

function ue_aplicar_articulo(literal)
{
	ls_numart   =trim(literal.numart); 
	ls_conart   =trim(literal.conart);
	ls_numlitart=trim(literal.numlitart);
	ls_operador =trim(literal.operador);
	ls_canmes   =trim(literal.canmes);
	ls_tiempo   =trim(literal.tiempo);
	ls_diasal   =literal.diasal;
	ls_condicion=trim(literal.condicion);
	ls_estacu   =trim(literal.estacu);
	ls_diaacu   =trim(literal.diaacu);
	ls_numcon   =trim(literal.numcon);
	li_meses    =restarFechasLiq("txtfecdes","txtfechas","m");
    lb_valido   =false; 
		if ((ls_condicion=='AND')&&(ls_numcon==2))
		{   
			if (ls_operador==1)  // >
			{   
				if (eval(li_meses)>eval(ls_canmes))
				{
					$('hidliteral').value = ls_numlitart;
					$('hidtemp').value = ls_diasal;
					$(hidand).value=1;
				}
			}
			if (ls_operador==2)  // <
			{   
				if (eval(li_meses)<eval(ls_canmes))
				{
					$('hidliteral').value = ls_numlitart;
					$('hidtemp').value = ls_diasal;
					$(hidand).value=1;
				}
			}
			if (ls_operador==3)  // >=
			{   		
				if (trim(li_meses)>=trim(ls_canmes))
				{       
					$('hidliteral').value = ls_numlitart;
					$('hidtemp').value = ls_diasal;
					$(hidand).value=1;
				}
			}
			if (ls_operador==4)  // <= 
			{   
				if (eval(li_meses)<=eval(ls_canmes))
				{
					$('hidliteral').value = ls_numlitart;
					$('hidtemp').value = ls_diasal;
					$(hidand).value=1;
				}
			}
			if (ls_operador==0)  // =
			{   
				if (li_meses == ls_canmes)
				{
					$('hidliteral').value = ls_numlitart;
					$('hidtemp').value = ls_diasal;
					$(hidand).value=1;
				}
			}
		}
		if ((ls_condicion=='NONE')&&($(hidand).value==1))
		{   
			ls_condicion='';
			if (ls_operador==1)  // >
			{     
				if (eval(li_meses) > eval(ls_canmes))
				{
					if ($('hidliteral').value==ls_numlitart)
					{ 
					  $('hiddiasal').value=ls_diasal;
					  $('hidtiempo').value=ls_tiempo;
					}
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu;
					  $('hidtiempo').value=ls_tiempo; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==2)  // <
			{   
				if (eval(li_meses) < eval(ls_canmes))
				{
					if ($('hidliteral').value==ls_numlitart)
					{ 
					  $('hiddiasal').value=ls_diasal;
					  $('hidtiempo').value=ls_tiempo;
					}
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu;
					  $('hidtiempo').value=ls_tiempo; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==3)  // >=
			{                                                   
				if (eval(li_meses) >= eval(ls_canmes))
				{      
					if ($('hidliteral').value==ls_numlitart)
					{ 
					  $('hiddiasal').value=ls_diasal;
					  $('hidtiempo').value=ls_tiempo;
					}
					if (ls_estacu == 'S') 
					{   $('hiddiaacu').value = ls_diaacu;
					    $('hidtiempo').value=ls_tiempo;   }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==4)  // <= 
			{   
				if (eval(li_meses) <= eval(ls_canmes))
				{   
					if ($('hidliteral').value==ls_numlitart)
					{ 
					  $('hiddiasal').value=ls_diasal;
					  $('hidtiempo').value=ls_tiempo;
					}
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu;
					  $('hidtiempo').value=ls_tiempo; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==0)  // =
			{   
				if (eval(li_meses) == eval(ls_canmes))
				{
					if ($('hidliteral').value==ls_numlitart)
					{ 
					  $('hiddiasal').value=ls_diasal;
					  $('hidtiempo').value=ls_tiempo;
					}
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu;
					  $('hidtiempo').value=ls_tiempo; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}	
			
		}
		else if ((ls_condicion=='NONE')&&($(hidand).value==0))
		{  
			if (ls_operador==1)  // >
			{   
				if (eval(li_meses) > eval(ls_canmes))
				{   
					$('hidliteral').value = ls_numlitart;
					$('hiddiasal').value  = ls_diasal;
					$('hidtiempo').value  = ls_tiempo;
					$('hidtemp').value = 0;
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==2)  // <
			{   
				if (eval(li_meses) < eval(ls_canmes))
				{
					$('hidliteral').value = ls_numlitart;
					$('hiddiasal').value  = ls_diasal;
					$('hidtiempo').value  = ls_tiempo;
					$('hidtemp').value = 0;
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==3)  // >=
			{   
				if ((eval(li_meses)>eval(ls_canmes))||(li_meses==ls_canmes))
				{  
					$('hidliteral').value = ls_numlitart;
					$('hiddiasal').value  = ls_diasal;
					$('hiddiasal').value  = ls_diasal;
					$('hidtiempo').value  = ls_tiempo;
					$('hidtemp').value = 0;
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==4)  // <= 
			{   
				if (eval(ls_canmes)<=eval(li_meses))
				{
					$('hidliteral').value = ls_numlitart;
					$('hiddiasal').value  = ls_diasal;
					$('hidtiempo').value  = ls_tiempo;
					$('hidtemp').value = 0;
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu; }
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
			if (ls_operador==0)  // =
			{   
				if (li_meses==ls_canmes)
				{   
					$('hidliteral').value = ls_numlitart;
					$('hiddiasal').value  = ls_diasal;
					$('hidtiempo').value  = ls_tiempo;
					$('hidtemp').value = 0;
					if (ls_estacu == 'S') 
					{ $('hiddiaacu').value = ls_diaacu;}
					else { $('hiddiaacu').value = 0; }
					$(hidok).value=1;
				}
			}
		}
	
    li_ok = trim($(hidok).value);
	if (li_ok==1)
	{   
		li_resta = eval(15.00);
        li_dias = ue_calcular_dias_acum( li_meses,ls_canmes,ls_estacu );
        $('txtdescripcion').value = ls_conart;
		ld_diaabo = uf_convertir_monto($F('txtdiaabofid'));
		li_fecing =restarFechasLiq("txtfecdes","txtfecingper","m");
			
		if ( (ls_numart=='108')&&(ld_diaabo!=0)&&($('hiddiaabo').value==0) )
		{   
			if (li_dias<ld_diaabo) 
			{	alert("Los días abonados no deben ser mayor a los días correspondientes a Antiguedad Art. 108."); }
			else
			{   
				if (li_fecing==0){ li_dias = li_dias-li_resta; }
				li_dias = (li_dias-ld_diaabo); 
		 	    $('txtdiasal').value = li_dias; 
			    ld_monto = (uf_convertir_monto($F('txtsalintdia'))*eval(li_dias));
				$('txtmonto').value = uf_convertir(eval(ld_monto),2);
				$(hidok).value=0;
				$(hiddiaabo).value=1;
				ue_agregar_detalle();
			}
		}
		if ( (ls_numart=='108')&&((ls_numlitart=='B')||(ls_numlitart=='b')) )
		{   
			$('txtdiasal').value = li_dias; 
			ld_monto = (uf_convertir_monto($F('txtsalintdia'))*eval(li_dias));
			$('txtmonto').value = uf_convertir(eval(ld_monto),2);
			$(hidok).value=0;
			ue_agregar_detalle();
		}
		else if ( (ls_numart=='108')&&(ld_diaabo==0)&&($('hiddiaabo').value==0) )
		{   
			if (li_fecing==0){ li_dias = li_dias-li_resta; }
			$('txtdiasal').value = li_dias; 
			ld_monto = (uf_convertir_monto($F('txtsalintdia'))*eval(li_dias));
			$('txtmonto').value = uf_convertir(eval(ld_monto),2);
			$(hidok).value=0;
			ue_agregar_detalle();
		}
		else
		{
			$('txtdiasal').value = li_dias; 
			ld_monto = (uf_convertir_monto($F('txtsalintdia'))*eval(li_dias));
			$('txtmonto').value = uf_convertir(eval(ld_monto),2);
			$(hidok).value=0;
			$(hidand).value=0;
			ue_agregar_detalle();
		}	
	}
}
//--------------------------------------------------------------------------------------------------------------------------------------------//
function ue_calcular_dias_acum( ai_meses,ai_canmes,as_estacu )
{     
    if ($('hidtiempo').value=='A') //si es cada año
	{     
		  numero = Math.round(ai_meses/ai_canmes);
		  var li_aux = parseInt(numero);
		  num = eval(ai_meses/12);
		  num = num.toString();
		  var li_int = parseInt(num);
		  var li_pos = num.indexOf('.');
		  var li_dec = num.substr(li_pos+1,2);
		  if (li_dec>50) 
		  {
			li_dia_acum= ($('hiddiasal').value*eval(li_aux+1));
		  }
		  else
		  {
		  	li_dia_acum= ($('hiddiasal').value*li_aux);
		  }
		  if ($('hidtemp').value!=0)
		  {  
		     li_dias=eval(li_dia_acum)+eval($('hidtemp').value);
          }
		  else
		  {li_dias=li_dia_acum;};
		  if (as_estacu=='S')
			  if (li_dia_acum>$('hiddiaacu').value)
			  {  li_dias=$('hiddiaacu').value+eval($('hidtemp').value); }
	}
	if ($('hidtiempo').value=='M')  //si es cada mes
	{ 
	  numero = eval(ai_meses/12);
	  numero = numero.toString();
	  var li_int = parseInt(numero);
	  var li_pos = numero.indexOf('.');
	  var li_dec = numero.substr(li_pos+1,2);
	  if (li_dec>50) 
	  {
		li_meses = (eval(li_int+1)*12);
		li_dias= (eval($('hiddiasal').value)*eval(li_meses));
	  }
	  else
	  {
		li_dias= (eval($('hiddiasal').value)*eval(ai_meses));	
	  }
	  if (as_estacu=='S')
	  	 if (li_dias>$('hiddiaacu').value){ li_dias=$('hiddiaacu').value; }
	}
	if ($('hidtiempo').value=='S') //si es cada semana
	{ 
	  li_aux = (ai_meses*4);
	  li_dias= (eval($('hiddiasal').value)*eval(li_aux));
	  if (as_estacu=='S')
	      if (li_dias>$('hiddiaacu').value){ li_dias=$('hiddiaacu').value; }
	}
	if ($('hidtiempo').value=='V'){ li_dias=$('hiddiasal').value; }	
												
	return 	li_dias;											
}

function ue_calcular()
{
	if ((ue_valida_null($('cmbarticulo')))&&(ue_valida_null($('txtfecdes')))&&(ue_valida_null($('txtfechas'))))
    {
		params = "operacion=ue_detallearticulo&id_art="+$F('cmbarticulo'); 
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onDetalleArticulo});
	}
	function onDetalleArticulo(respuesta)
	{   
		if (trim(respuesta.responseText)!="")
		{   
			var articulo = JSON.parse(respuesta.responseText);
			for (j=0; j<articulo.numlitart.length; j++) 
			{       
				  var literal  = 
				  {
				  	   "numart":articulo.numart[j],
					   "conart":articulo.conart[j],
					"numlitart":articulo.numlitart[j],
					 "operador":articulo.operador[j],
					   "canmes":articulo.canmes[j],
					   "tiempo":articulo.tiempo[j],
					   "diasal":articulo.diasal[j],
					"condicion":articulo.condicion[j],
					   "estacu":articulo.estacu[j],
					   "diaacu":articulo.diaacu[j],
					   "numcon":articulo.numcon[j]
				  };
				  ue_aplicar_articulo(literal);
			}
		}
	}
} //end function ue_calcular

function ue_sumarTotal()
{
	var total=0;
	var liq  =0;
	var asignacion=0;
	var deduccion=0;
	var li_count_a=0;
	var li_count_d=0;
	var filas = $('dt_liquidacion').getElementsByTagName("tr");
	for (f=1; f<filas.length; f++)
	{   
		var columnas = filas[f].getElementsByTagName("td");
		totalLiq = columnas[3].innerHTML;
		if (totalLiq.indexOf('-')==-1) //si es asignacion
		{
			liq   = uf_convertir_monto(totalLiq);
			total = eval(liq)+eval(total);
			asignacion=eval(liq)+eval(asignacion);
			asig = asignacion;
			$('txtasignacion').value= uf_convertir(asig,2);		
			li_count_a++;
		}
		else    //si es deduccion
		{   
			liq   = uf_convertir_monto(totalLiq);
			total = eval(liq)+eval(total);
			deduccion=eval(liq)+eval(deduccion);
			deduc = deduccion;
			$('txtdeduccion').value= uf_convertir(deduccion,2);	
			li_count_d++;
		}
		
	};
	if ((filas.length==li_count_a)&&(li_count_d===0)){$('txtdeduccion').value= "0.00";}
	totales=total;
	$('txttotal').value= uf_convertir(totales,2);
	if (total===0){
		$('txtdeduccion').value= "0.00";
		$('txtasignacion').value= "0.00" 
	} 
}
//-------------------------------------------------------------------------------------------------------------------------------------------//
function ue_guardar()
{
	lb_valido=true;
	var la_objetos =new Array("txtfecliq","txtnumliq","txtcodper","txtcodnom");
	var la_mensajes=new Array("la fecha de liquidación", "el numero de liquidación","el código de personal", "el código de  nómina" );
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
		if (($F("txtestliq")=="Aprobada")||($F("txtestliq")=="Pagada"))
		{
			alert("No puede Actualizar estos Datos!!!"); 
		}
		else
		{
			if(($F('hidguardar')== "modificar")&&($F('hidpermisos').indexOf('m',0)<0))
			{
				alert("No tiene permiso para actualizar información.");
			}
			else
			{
				function onGuardar(respuesta)
				{
					if(trim(respuesta.responseText) !="" )
					{alert(respuesta.responseText);}
					ue_cancelar();
				}
				//Arreglo de detalles sueldos
				var liq_detalle = new Array();
				var filas = $('dt_liquidacion').getElementsByTagName("tr");
				for (f=1; f<filas.length; f++)
				{
					hidvar = "hid"+f;
					sc_cuenta=eval("document.form1."+hidvar+".value");
					var IdFila   = filas[f].getAttribute("id");
					var columnas = filas[f].getElementsByTagName("td");
					var liquidacion = 
					{
					  "codper"     : $F('txtcodper'),
					  "codnom"     : $F('txtcodnom'),
					  "numliq"     : $F('txtnumliq'),
					  "codcauret"  : $F('cmbcauret'),
					  "fecliq"     : $F('txtfecliq'),
					  "fecing"     : $F('txtfecingper'),
					  "fecegr"     : $F('txtfecegrper'),
					  "salint"     : $F('txtsalintper'),
					  "descargo"   : $F('txtcargo'),
					  "dedicacion" : $F('txtdedicacion'),
					  "tipopersonal":$F('txttipopersonal'),
					  "anoser"     : $F('txtano'),
					  "messer"     : $F('txtmes'),
					  "diaser"     : $F('txtdia'),
					  "totasiliq"  : $F('txtasignacion'),
					  "totdedliq"  : $F('txtdeduccion'),
					  "totpagliq"  : $F('txttotal'),
					  "estliq"     : "R",
					  "fecdes"     : $('hidfecdes').value,
					  "fechas"     : $('hidfechas').value,
					  "numespliq"  : columnas[0].innerHTML,
					  "desespliq"  : columnas[1].innerHTML,
					  "salpro"     : $F('txtsalintdia'),
					  "diapag"     : columnas[2].innerHTML,
					  "subtotal"   : columnas[3].innerHTML,
					  "sc_cuenta_ded": sc_cuenta,
					  "diaabofid"  : $F('txtdiaabofid')
					}				
					liq_detalle[f-1] = liquidacion;
				}
				var regliquidacion =
				{
					"codper":$F('txtcodper'),
					"codnom":$F('txtcodnom'),
					"dt_liquidacion":liq_detalle
				};
				
				var objeto = JSON.stringify(regliquidacion);
				params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
				new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
			}
		} //end else
	}	
 }
function ue_eliminar()
{
  lb_valido = true;
  var la_objetos=new Array("txtcodper","txtcodnom","txtnumliq");
  var la_mensajes=new Array("el código personal", "el código nómina","el numero de liquidación." );
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar la Liquidación Nº "+$F('txtnumliq')+" ?"))
	{
	  function onEliminar(respuesta)
	  {
		alert(respuesta.responseText);
		ue_cancelar();
	  }
	   params = "operacion=ue_eliminar&codper="+trim($F('txtcodper'))+"&codnom="+trim($F('txtcodnom'))+"&numliq="+trim($F('txtnumliq'));
	   new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});	
	}
	else
	{
	   alert("Eliminación Cancelada !!!");	  
	   ue_cancelar();
	}
  };
}
function ue_deuda_anterior()  
{
	if (ue_valida_null($('txtfecdes')))
	{   
		params = "operacion=ue_deudaanterior&codper="+$F('txtcodper')+"&codnom="+$F('txtcodnom')+"&fecdes="+$F('txtfecdes');  
		new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onDeudaAnterior});
	}
	else
	{
		alert("Debe indicar las fechas de búsqueda.");	
	}
	function onDeudaAnterior(respuesta)
	{ 
		if (trim(respuesta.responseText)!="")
		{	
			var deudaanterior = JSON.parse(respuesta.responseText);	  
			for (i=0; i<deudaanterior.feccordeuant.length; i++)  
			{  
				ls_feccordeuant = deudaanterior.feccordeuant[i];  
				ld_deuantant = deudaanterior.deuantant[i];
				ld_deuantint = deudaanterior.deuantint[i];
				ld_antpag    = deudaanterior.antpag[i];
			}	
			li_ano = ls_feccordeuant.substr(0,4);
  			li_mes = ls_feccordeuant.substr(5,2);
  			li_dia = ls_feccordeuant.substr(8,2);
            		ldt_feccordeuant = li_dia+"/"+li_mes+"/"+li_ano;
			if (ld_deuantant!="")	
			{
				$('txtdescripcion').value = "Deuda Anterior de Antiguedad hasta "+ldt_feccordeuant;	
				$('txtdiasal').value = "-------";
				$('txtmonto').value  = uf_convertir(eval(ld_deuantant-ld_antpag),2);
				ue_agregar_detalle();
			}
			if (ld_deuantint!="")
			{
				$('txtdescripcion').value = "Intereses Deuda Anterior hasta "+ldt_feccordeuant;
				$('txtdiasal').value = "-------";
				$('txtmonto').value  = uf_convertir(ld_deuantint,2);
				ue_agregar_detalle();
			}
			
	 	}
		else
		{ alert("No existen datos de Deuda Anterior. ");}
	} // end onDeudaAnterior
}
