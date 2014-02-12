// JavaScript Document

var url    = '../php/sps_pro_antiguedad.php';
var params = 'operacion=';
var metodo = 'post';

Event.observe(window,'load',ue_cancelar,false);

function ue_cancelar()
{
  document.form1.reset();
  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecdes,txtfechas,txtfecingper,txtanoserant,txtmesserant,txtdiaserant,cmbcapint,txtfecvig,btngendeu");	
  limpiar_tabla_detalle();
  $('txtfecvig').value = ("19/06/1997");
  scrollTo(0,0);
}
/*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_nuevo()
{	
	ue_cancelar();
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/
function limpiar_tabla_detalle()
{  
  var FILAS = $("dt_antig").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_antig",FILAS[f].id)}
  }
}
//---------------------------------------------------------------------------------------------------------------------------------------------//
function ue_buscarpersonal()
{
	pagina="sps_cat_sueldos.html.php";
	catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}

function ue_cargar_registro_sueldos(arr_datos)
{
	  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom");
	  habilitar("txtfecdes,txtfechas,cmbcapint,txtfecvig,btngendeu");
	  $('txtfecdes').value  = "";
	  $('txtfechas').value  = "";
	  $('txtfecingper').value = "";
	  $('txtanoserant').value = "";
	  $('txtmesserant').value = "";
	  $('txtdiaserant').value = "";
	  $('txtfecdes').focus();
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
		  	  ld_fecant    = eval("det_antig."+campos[0]+"["+f+"];");
			  ld_salbas    = uf_convertir(eval("det_antig."+campos[1]+"["+f+"];"),2);
			  ld_incbonvac = uf_convertir(eval("det_antig."+campos[2]+"["+f+"];"),2);
			  ld_incbonnav = uf_convertir(eval("det_antig."+campos[3]+"["+f+"];"),2);
			  ld_salintdia = uf_convertir(eval("det_antig."+campos[4]+"["+f+"];"),2);
			  ld_diabas    = uf_convertir(eval("det_antig."+campos[5]+"["+f+"];"),2);
			  ld_diacom    = uf_convertir(eval("det_antig."+campos[6]+"["+f+"];"),2);
			  ld_monant    = uf_convertir(eval("det_antig."+campos[7]+"["+f+"];"),2);
			  ld_monacuant = uf_convertir(eval("det_antig."+campos[8]+"["+f+"];"),2);
			  ld_monantant = uf_convertir(eval("det_antig."+campos[9]+"["+f+"];"),2);
			  ld_salparant = uf_convertir(eval("det_antig."+campos[10]+"["+f+"];"),2);
			  ld_porint    = uf_convertir(eval("det_antig."+campos[11]+"["+f+"];"),2);
			  ld_diaint    = uf_convertir(eval("det_antig."+campos[12]+"["+f+"];"),2);
			  ld_monint    = uf_convertir(eval("det_antig."+campos[13]+"["+f+"];"),2);
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
function ue_buscar()
{
	pagina="sps_cat_antiguedad.html.php";
	catalogo = popupWin(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
function ue_cargarCatalogo(arr_datos)
{
	  var cajas = new Array('txtcodper','txtnomper','txtapeper','txtcodnom','txtdennom');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtcodper,txtnomper,txtapeper,txtcodnom,txtdennom,txtfecdes,txtfechas,cmbcapint,txtfecvig,btngendeu");
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
function ue_configuracion()
{
  function onConfiguracion(respuesta)
  {  
  	if (trim(respuesta.responseText)!="")
	{ 
		var configuracion = (JSON.parse(respuesta.responseText));
		$('hidestsue').value= configuracion.estsue[0];
	}
  }	
  params = "operacion=ue_configuracion";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onConfiguracion});
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_evaluarArticulo(literales)
{       
	li_totmeses = restarFechas("txtfecdes","txtfechas","m");
	if ((trim(literales.condicion)=="AND")&&(literales.numcon>1))
	{       
		if (literales.operador==1) //indica operador >
		{   
			if (literales.canmes>li_totmeses)
			{ 
				$('hiddiasal').value = literales.diasal;
				$('hidtiempo').value = literales.tiempo;
				if (literales.estacu == 'S') 
				{ $('hiddiaacu').value = literales.diaacu; }
				else { $('hiddiaacu').value = 0; }
			}
			if (li_totmeses>literales.canmes)
			{  
				$('hiddiasal').value = literales.diasal;
				$('hidtiempo').value = literales.tiempo;
				if (literales.estacu == 'S') 
				{ $('hiddiaacu').value = literales.diaacu; }
				else { $('hiddiaacu').value = 0; }	
			}
		}
		else if (literales.operador==3) //indica operador >=
		{ 
			if (literales.canmes>=li_totmeses)
			{
				$('hiddiasal').value = literales.diasal;
				$('hidtiempo').value = literales.tiempo;
				if (literales.estacu == 'S') 
				{ $('hiddiaacu').value = literales.diaacu; }
				else { $('hiddiaacu').value = 0; }
			}
		}
		else if (literales.operador==2) //indica operador <
		{ 
			if (literales.canmes<li_totmeses)
			{
				$('hiddiasal').value = literales.diasal;
				$('hidtiempo').value = literales.tiempo;
				if (literales.estacu == 'S') 
				{ $('hiddiaacu').value = literales.diaacu; }
				else { $('hiddiaacu').value = 0; }
			}
		}
		else if (literales.operador==4) //indica operador <=
		{
			if (literales.canmes<=li_totmeses)
			{
				$('hiddiasal').value = literales.diasal;
				$('hidtiempo').value = literales.tiempo;
				if (literales.estacu == 'S') 
				{ $('hiddiaacu').value = literales.diaacu; }
				else { $('hiddiaacu').value = 0; }
			}
		}
		else if (literales.operador==5) //indica operador ==
		{
			if (literales.canmes == li_totmeses)
			{
				$('hiddiasal').value = literales.diasal;
				$('hidtiempo').value = literales.tiempo;
				if (literales.estacu == 'S') 
				{ $('hiddiaacu').value = literales.diaacu; }
				else { $('hiddiaacu').value = 0; }	
			}
		}
	} // end hidcond
	else 
	{
		if (literales.operador==1) //indica operador >
		{   
			if (literales.canmes > li_totmeses )
			{ 
				$('hiddiasal2').value = literales.diasal;
				$('hidtiempo2').value = literales.tiempo;
				
				if (literales.estacu == 'S') 
				{ $('hiddiaacu2').value = literales.diaacu; }
				else { $('hiddiaacu2').value = 0; }
			}
			if (  li_totmeses >literales.canmes)
			{   
				$('hiddiasal2').value = literales.diasal;
				$('hidtiempo2').value = literales.tiempo;
				
				if (literales.estacu == 'S')
				{ $('hiddiaacu2').value = literales.diaacu; }		
				else { $('hiddiaacu2').value = 0; }
			}  
		}
		else if (literales.operador==3) //indica operador >=
		{    
			if (literales.canmes>=li_totmeses)
			{
				$('hiddiasal2').value = literales.diasal;
				$('hidtiempo2').value = literales.tiempo;
				if (literales.estacu == 'S')
				{ $('hiddiaacu2').value = literales.diaacu; }		
				else { $('hiddiaacu2').value = 0; }
			}
		}
		else if (literales.operador==2) //indica operador <
		{
			if (literales.canmes<li_totmeses)
			{
				$('hiddiasal2').value = literales.diasal;
				$('hidtiempo2').value = literales.tiempo;
				if (literales.estacu == 'S')
				{ $('hiddiaacu2').value = literales.diaacu; }		
				else { $('hiddiaacu2').value = 0; }
			}
		}
		else if (literales.operador==4) //indica operador <=
		{
			if (literales.canmes<=li_totmeses)
			{
				$('hiddiasal2').value = literales.diasal;
				$('hidtiempo2').value = literales.tiempo;
				if (literales.estacu == 'S')
				{ $('hiddiaacu2').value = literales.diaacu; }
				else { $('hiddiaacu2').value = 0; }
			}
		}
		else if (literales.operador==5) //indica operador ==
		{
			if (literales.canmes == li_totmeses)
			{
				$('hiddiasal2').value = literales.diasal;
				$('hidtiempo2').value = literales.tiempo;
				if (literales.estacu == 'S')
				{ $('hiddiaacu2').value = literales.diaacu; }		
				else { $('hiddiaacu2').value = 0; }
			}
		}	
	}
        
} //end of function ue_evaluarArticulo

 function ue_calcular_dias_complementarios(ld_periodo)
 {
	ld_fecingper = $F('txtfecingper');
	ls_tiempo    = ($('hidtiempo2').value).toLowerCase( );
	li_diaacu    = ($('hiddiaacu2').value);
		
   	var periodo  = ""; 
	var fechaini = new Date();
	var fechafin = new Date();
	fechaini.setFullYear(parseFloat(ld_fecingper.substr(6,4)),(parseFloat(ld_fecingper.substr(3,2))-1),parseFloat(ld_fecingper.substr(0,2)));
	fechafin.setFullYear(parseFloat(ld_periodo.substr(6,4)),(parseFloat(ld_periodo.substr(3,2))-1),parseFloat(ld_periodo.substr(0,2)));
	var tiempoRestante = fechafin.getTime() - fechaini.getTime();	
	var divisor;
	switch(ls_tiempo)
	{
		case 'd': divisor = 1000 * 60 * 60 * 24; break;
		case 'm': divisor = 1000 * 60 * 60 * 24 * 30; break;
		case 'a': divisor = 1000 * 60 * 60 * 24 * 365; break;
	}  
	periodo = (Math.floor(tiempoRestante / divisor)).toString();
	li_meses = restarFechasComplementarias(ld_fecingper,ld_periodo,"m"); 
	if (ls_tiempo=="a")
	{   
		li_mes = eval(li_meses/12);
		var ls_mes = new String(li_mes);
		if ((ls_mes.indexOf('.')=== -1)||(ls_mes=="1"))
		{
			var li_diacomp = (periodo*($('hiddiasal2').value));
			if (li_diacomp > li_diaacu){ li_diacomp = li_diaacu }
		}
		else
		{
			li_diacomp=0;
	    }
		if (ls_mes=='1')
		{ 
		  li_diacomp = $('hiddiasal2').value;
		}
	}
	else 
	{   
		var li_diacomp = (periodo*($('hiddiasal2').value));
		if (li_diacomp > li_diaacu){ li_diacomp = li_diaacu }	
	}
	return  li_diacomp;
 }
function restarFechasComplementarias(fechainicio,fechafinal,tipoPeriodo)  
{   
	if ((fechainicio != "") && (fechafinal != ""))
	{	  
	  var fechaini = new Date();
	  fechaini.setFullYear(parseFloat(fechainicio.substr(6,4)),(parseFloat(fechainicio.substr(3,2))-1),parseFloat(fechainicio.substr(0,2)));
	  var fechafin = new Date();
	  fechafin.setFullYear(parseFloat(fechafinal.substr(6,4)),(parseFloat(fechafinal.substr(3,2))-1),parseFloat(fechafinal.substr(0,2)));
	  var periodo = ""; 
	  var tiempoRestante = fechafin.getTime() - fechaini.getTime();		  		  
	  var divisor;
	  switch(tipoPeriodo)
	  {
		case 'd': divisor = 1000 * 60 * 60 * 24; break;
		case 'm': divisor = 1000 * 60 * 60 * 24 * 30; break;
		case 'a': divisor = 1000 * 60 * 60 * 24 * 365; break;
	  }  
	  periodo = (Math.floor(tiempoRestante / divisor)).toString();
	};

	return periodo;
}

function ue_consulta_articulo()
{
  params = "operacion=ue_consulta_articulo&numart=108&fecvig="+$F('txtfecvig');
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onConsultaArticulo });
  function onConsultaArticulo(respuesta)
  { 
  	if (trim(respuesta.responseText)!="")
	{  
		var articulo = (JSON.parse(respuesta.responseText));
		for (j=0; j<articulo.operador.length; j++)
		{
		  var literales = 
		  {
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
		  //ue_evaluarArticulo(literales);
		  ue_aplicar_articulo(literales);
		}
	}
  }	
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_fecha_ingreso()
{
  $('txtfecingper').value  = "";	
  function onFechaIngreso(respuesta)
  { 
    var fecha = JSON.parse(respuesta.responseText);
    li_año = fecha.fecingper[1].substr(0,4);
	li_mes = fecha.fecingper[1].substr(5,2);
	li_dia = fecha.fecingper[1].substr(8,2);
	var fecingper = (li_dia+"/"+li_mes+"/"+li_año);
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
	ls_meses = ( ls_mes - ls_calc_ano - 1);
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
/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_generar_item_fechas( ad_fecdes, ad_fechas, ai_valor )
{
	var la_fechas = new Array(); 	
	var la_dias   = new Array();
        li_diaini = ad_fecdes.substr(0,2);
	li_mesini = ad_fecdes.substr(3,2);
	li_añoini = ad_fecdes.substr(6,4);
	li_diafin = ad_fechas.substr(0,2);
	li_mesfin = ad_fechas.substr(3,2);
	li_añofin = ad_fechas.substr(6,4);
	li_cant_mes = restarFechas("txtfecdes","txtfechas","m");
	if (ai_valor == 1)
	{  //primer item menos los tres meses
		li_cant_mes = (li_cant_mes-3);
		for (i=0; i<(li_cant_mes); i++)
		{
			if (i==0){
				//li_dia = li_diaini;   
				if (li_mesini >= 10)
				{
					if (li_mesini = 10){ li_mes = 1; }
					if (li_mesini = 11){ li_mes = 2; }
					if (li_mesini = 12){ li_mes = 3; }
					li_ano = (eval(li_añoini)+1);
				}
				else
				{ 
					li_mes = (eval(li_mesini) + 3);
					li_ano = li_añoini;
				}
				switch (li_mes)
				{	case 1: li_dia_i=31 ; break;
					case 2: ld_result = (li_ano/4);
							var ls_result = new String(ld_result);
							if (ls_result.indexOf('.')>0)
							{ li_dia_i=28; }
							else { li_dia_i=29; }
							break;
					case 3: li_dia_i =31; break;
					case 4: li_dia_i =30;
					        break;
					case 5: li_dia_i =31; break;
					case 6: li_dia_i =30; break;
					case 7: li_dia_i =31; break;
					case 8: li_dia_i =31; break;
					case 9: li_dia_i =30; break;
					case 10: li_dia_i=31; break;
					case 11: li_dia_i=30; break;
					case 12: li_dia_i=31; break;
				}
				la_dias[i]=li_dia_i;  
				if (li_mes<10)
				{ la_fechas[i]= (li_dia_i+"/0"+li_mes+"/"+li_ano); }         //
				else 
				{ la_fechas[i]= (li_dia+"/"+li_mes+"/"+li_ano);}
				if (li_mes == 12)                                         //
				{
					li_mes = 1; 
					li_ano = (eval(li_añoini)+1);
				}
				else
				{ 
					li_mes = (eval(li_mes) + 1);
					li_ano = li_añoini;
				} 
			}
			else
			{	
				switch (li_mes)
				{	case 1: li_dia=31; break;
					case 2: ld_result = (li_ano/4);
							var ls_result = new String(ld_result);
							if (ls_result.indexOf('.')>0)
							{ li_dia=28; }
							else { li_dia=29; }
							break;
					case 3: li_dia=31; break;
					case 4: li_dia=30; break;
					case 5: li_dia=31; break;
					case 6: li_dia=30; break;
					case 7: li_dia=31; break;
					case 8: li_dia=31; break;
					case 9: li_dia=30; break;
					case 10: li_dia=31; break;
					case 11: li_dia=30; break;
					case 12: li_dia=31; break;
				}
				la_dias[i]=li_dia;  
				if (li_mes<10)
				{ la_fechas[i]= (li_dia+"/0"+li_mes+"/"+li_ano);}
				else 
				{ la_fechas[i]= (li_dia+"/"+li_mes+"/"+li_ano);}
				li_mes = (eval(li_mes) + 1);
				if (li_mes>12)
				{
					li_ano = (eval(li_ano) + 1);
					li_mes = 1;
				}	
			} //else (i==0)
		} //for
	} //if (ai_valor == 1) 
	else
	{
		for (i=0; i<(eval(li_cant_mes)); i++)
		{
			if (i==0)
			{
				var li_dia = li_diaini;
				var li_mes = li_mesini;
				var li_ano = li_añoini;
				var li_dia_i = 0;
				switch (li_mes)
				{	
					case '01': li_dia_i=(31-li_dia); li_dia=31;  break;
					case '02': ld_result = (li_ano/4);
							var ls_result = new String(ld_result);
							if (ls_result.indexOf('.')>0)
							{ li_dia_i=(28-li_dia); li_dia=28; }
							else { li_dia_i=(29-li_dia); li_dia=29; }
							break;
					case '03': li_dia_i =(31-li_dia); li_dia=31; break;
					case '04': li_dia_i =(30-li_dia); li_dia=30; break;
					case '05': li_dia_i =(31-li_dia); li_dia=31; break;
					case '06': li_dia_i =(30-li_dia); li_dia=30; break;
					case '07': li_dia_i =(31-li_dia); li_dia=31; break;
					case '08': li_dia_i =(31-li_dia); li_dia=31; break;
					case '09': li_dia_i =(30-li_dia); li_dia=30; break;
					case '10': li_dia_i=(31-li_dia); li_dia=31; break;
					case '11': li_dia_i=(30-li_dia); li_dia=30; break;
					case '12': li_dia_i=(31-li_dia); li_dia=31; break;
				}
				la_dias[i]=li_dia_i;  
				if (li_mes<10)
				{ la_fechas[i]= (li_dia+"/"+li_mes+"/"+li_ano);}
				else 
				{ la_fechas[i]= (li_dia+"/"+li_mes+"/"+li_ano);}
				if (li_mes == 12)
				{ li_mes = 1; li_ano = (eval(li_añoini)+1); }
				else 
				{ li_mes = (eval(li_mesini) + 1); li_ano = li_añoini; }
			}
			else
			{
				switch (li_mes)
				{	case 1: li_dia=31; break;
					case 2: ld_result = (li_ano/4);
							var ls_result = new String(ld_result);
							if (ls_result.indexOf('.')>0)
							{ li_dia=28; }
							else { li_dia=29; }
							break;
					case 3: li_dia=31; break;
					case 4: li_dia=30; break;
					case 5: li_dia=31; break;
					case 6: li_dia=30; break;
					case 7: li_dia=31; break;
					case 8: li_dia=31; break;
					case 9: li_dia=30; break;
					case 10: li_dia=31; break;
					case 11: li_dia=30; break;
					case 12: li_dia=31; break;
				}
				la_dias[i]=li_dia;  
				if (li_mes<10)
				{ la_fechas[i]= (li_dia+"/0"+li_mes+"/"+li_ano);}
				else 
				{ la_fechas[i]= (li_dia+"/"+li_mes+"/"+li_ano);}
				li_mes = (eval(li_mes) + 1);
				if (li_mes>12)
				{
					li_ano = (eval(li_ano) + 1);
					li_mes = 1;
				}	
			} //else (i==0)
		} //for
	}
	return la_fechas;
} //end function ue_generar_item_fechas
/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_generar_deuda()
{
   var la_objetos =new Array ("txtfecdes","txtfechas","txtfecvig","cmbcapint");  
   var la_mensajes=new Array ("la fecha desde","la fecha hasta","la fecha vigente del articulo"," si capitaliza interés");
   lb_valido = valida_datos_llenos(la_objetos,la_mensajes, ".");
   if(lb_valido)
   {    
   		ue_consulta_articulo();
		ue_configuracion();
		ue_fecha_ingreso();
   		ue_tiempo_servicio();
		var ls_fecdes = $F('txtfecdes');
		var ls_fechas = $F('txtfechas'); 
		var ls_fecing = $F('txtfecingper');
		deshabilitar("txtfecdes,txtfechas");
		$F('txtfecingper').refresh;
		li_valor = compararFechasIgual('txtfecdes','txtfecingper'); 
		if (li_valor==1)       //indica que fecha inicio es igual a fecha de ingreso. (antiguedad a partir del 4TO mes)
		{   
			la_fechas = ue_generar_item_fechas( ls_fecdes, ls_fechas, li_valor );
			ue_calcular_periodo_antiguedad(la_fechas);
		}
		else if (li_valor==2)  //indica que fecha inicio es mayor a fecha de ingreso.
		{
			la_fechas = ue_generar_item_fechas( ls_fecdes, ls_fechas, li_valor );
			ue_calcular_periodo_antiguedad(la_fechas);
		}
		else if (li_valor==3)  //indica que fecha inicio es menor a fecha de ingreso. 
		{
			alert("FECHA INCORRECTA: La fecha Inicial no puede ser menor a la fecha de ingreso.");	
		}
		
   } //end if (lb_valido)
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
function compararFechasIgual(cajaFechaInicio,cajaFechaFinal) 
{
	var valor=0;
	f = document.form1;
	var fechainicio;
	if (document.getElementById(cajaFechaInicio) == null)
	{  fechainicio = cajaFechaInicio; }
	else
	{	fechainicio = eval("f."+cajaFechaInicio+".value;");
		alert("Los valores presentados son generados automaticamente por el sistema.");    
	  	//if ((navigator.appName == "Netscape"))
//		{	 
//		   eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
//		}
	}
	var fechafinal;
	if (document.getElementById(cajaFechaFinal) == null)
	{  fechafinal = cajaFechaFinal;	}
	else
	{  fechafinal = eval("f."+cajaFechaFinal+".value;");}
	var fechaini = new Date();
	fechaini.setFullYear(parseFloat(fechainicio.substr(6,4)),(parseFloat(fechainicio.substr(3,2))-1),parseFloat(fechainicio.substr(0,2)));
	var fechafin = new Date();
	fechafin.setFullYear(parseFloat(fechafinal.substr(6,4)),(parseFloat(fechafinal.substr(3,2))-1),parseFloat(fechafinal.substr(0,2)));
	var booleano;
	if (fechaini.getTime() === fechafin.getTime())
	{  valor = 1; }
	else if (fechaini.getTime() > fechafin.getTime())
	{  valor = 2; }
	else if (fechaini.getTime() < fechafin.getTime())
	{  valor = 3; }
	return valor;
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
/*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_calcular_incidencias(li_ano) 
{
  params = "operacion=ue_incidencias&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom')+"&ano="+li_ano;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onIncidencias});
  
  function onIncidencias(respuesta)
  { 
  	if (trim(respuesta.responseText) != "")
	{	
		var incidencias = JSON.parse(respuesta.responseText);	  
		$('hidincvac').value = incidencias.diabonvacfid[1];
		$('hidincfin').value = incidencias.diabonfinfid[1];
	}	
	return
  }	// end onIncidencias
} // end function
/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_salariobase(periodo)
{
  params = "operacion=ue_salariobase&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom')+"&periodo="+periodo;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onSalarioBase}); 	
  function onSalarioBase(respuesta)
  { 
   	if (trim(respuesta.responseText)!="")
	{  
    	var salariobase = JSON.parse(respuesta.responseText);
		$('hidsueldo').value = salariobase.monsuebas[1];	
	}
	else
	{ alert("No existen registro de Sueldos.");} 
  }	      
}
/*--------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_salariointegral(periodo) 
{
  function onSalarioIntegral(respuesta)
  {  
  	if (trim(respuesta.responseText)!="")
	{  
      var salariointegral = JSON.parse(respuesta.responseText);
	  $('hidsueldo').value = salariointegral.monsueint[1];
	}
	else
	{ alert("No existen registro de Sueldos.");}
  }
  params = "operacion=ue_salariointegral&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom')+"&periodo="+periodo;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onSalarioIntegral});	
}
//--------------------------------------------------------------------------------------------------------------------------------------------------/
function ue_anticipos() 
{
  params = "operacion=ue_anticipos&codper="+trim($F('txtcodper'))+"&codnom="+$F('txtcodnom');
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onAnticipos});	
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
}
/*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_tasa_interes(li_anoper,li_mesper) 
{
  params = "operacion=ue_tasa_interes&anotasint="+li_anoper+"&mestasint="+li_mesper;
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onTasaInteres});	
  function onTasaInteres(respuesta)
  {   
  	if (trim(respuesta.responseText)!="")
	{  
      var tasa = JSON.parse(respuesta.responseText);
	  $('hidtasint').value = tasa.valtas[1];
	}
  }	     
}
/*------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_calcular_dias(pi_diaper,pi_mesper,pi_anoper)
{   
	li_valor1 = 30;
	li_valor2 = 31;
	li_valor3 = 28;
	li_valor4 = 29;
	switch (pi_mesper)
	{	 
		case '01': if (pi_diaper!=31)
				{ li_dia = (eval(li_valor2)-eval(pi_diaper));}
				else {li_dia=31; }
				break;
		case '02': ld_result = (pi_anoper/4);
				var ls_result = new String(ld_result);
				if (ls_result.indexOf('.')>0)
				{ li_feb=28; }
				else { li_feb=29; }
				if (li_feb===28)
				{
					if (pi_diaper!=28)
					{ li_dia = (eval(li_valor3)-eval(pi_diaper));}
					else {li_dia=28; }		
				}
				else
				{
					if (pi_diaper!=29)
					{ li_dia = (eval(li_valor4)-eval(pi_diaper));}
					else {li_dia=29; }
				}
				break;
		case '03': if (pi_diaper!=31)
				{ li_dia = (eval(li_valor2)-eval(pi_diaper));}
				else {li_dia=31; }
				break;
		case '04': if (pi_diaper!=30)
				{ li_dia = (eval(li_valor1)-eval(pi_diaper)); }
				else {li_dia=30; }
				break;
		case '05': if (pi_diaper!=31)
				{ li_dia = (eval(li_valor2)-eval(pi_diaper));}
				else {li_dia=31; }
				break;
		case '06': if (pi_diaper!=30)
				{ li_dia = (eval(li_valor1)-eval(pi_diaper));}
				else {li_dia=30; }
				break;
		case '07': if (pi_diaper!=31)
				{ li_dia = (eval(li_valor2)-eval(pi_diaper));}
				else {li_dia=31; }
				break;
		case '08': if (pi_diaper!=31)
				{ li_dia = (eval(li_valor2)-eval(pi_diaper));}
				else {li_dia=31; }
				break;
		case '09': if (pi_diaper!=30)
				{ li_dia = (eval(li_valor1)-eval(pi_diaper));}
				else {li_dia=30; }
				break;
		case '10': if (pi_diaper!=31)
				 { li_dia = (eval(li_valor2)-eval(pi_diaper));}
				 else {li_dia=31; }
				 break;
		case '11': if (pi_diaper!=30)
				 { li_dia = (eval(li_valor1)-eval(pi_diaper));}
				 else {li_dia=30; }
				 break;
		case '12': if (pi_diaper!=31)
				 { li_dia = (eval(li_valor2)-eval(pi_diaper));}
				 else {li_dia=31; }
				 break;
	}
	return li_dia;
}

/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
function ue_calcular_periodo_antiguedad(aa_fechas)
{
	li_anoaux ='1900';
	ld_psocial=0;
	ld_ps_acum=0;
	ls_ps_parcial=0
	ld_intacu=0;
	ld_saldototal=0;
	li_peri = aa_fechas.length;
	for (i=0; i<li_peri; i++)
	{    
		ld_periodo = aa_fechas[i];
		li_diaper = ld_periodo.substr(0,2);
		li_mesper = ld_periodo.substr(3,2);	
		li_anoper = ld_periodo.substr(6,4);
		
		//Incidencias  *
		if (li_anoper != li_anoaux)
		{
		  li_ano=li_anoper; 
		  li_anoaux=li_anoper;  
		  ue_calcular_incidencias(li_ano);  
		} 
		li_dias = ue_calcular_dias(li_diaper,li_mesper,li_anoper);
		//dias complementarios
		li_diacomp = ue_calcular_dias_complementarios(ld_periodo);
		//sueldo
		if ($('hidestsue').value=='B')
		{ ue_salariobase(ld_periodo); } 
		else if ($('hidestsue').value=='I')
		{ 
			ue_salariointegral(ld_periodo);  
		}
		else { 
			alert(" Debe realizar las configuraciones del sistema en Mantenimiento.");
			break;
		}
		if (confirm("Calculando el período :"+ld_periodo+" Sueldo:"+$('hidsueldo').value))
		{    
			if ($('hidsueldo').value!="")
			{   
			    ld_suedia    = ($('hidsueldo').value/30);
				ld_monincvac = (((ld_suedia*$('hidincvac').value)/12)/li_dias) ;
				ld_monincfin = (((ld_suedia*$('hidincfin').value)/12)/li_dias) ;
				ld_salintdia = eval(ld_suedia)+eval(ld_monincvac)+eval(ld_monincfin); 
				ld_monincvac = uf_convertir(ld_monincvac,2);
				ld_monincfin = uf_convertir(ld_monincfin,2);
				ld_salintdia = uf_convertir(ld_salintdia,2); 
				
				//prestacion social del periodo
				li_dias_ps = eval($(hiddiasal).value)+eval(li_diacomp);
				ld_psocial = parseFloat(uf_convertir_monto(ld_salintdia))*eval(li_dias_ps);
				ld_ps_acum = eval(ld_psocial)+eval(ld_ps_acum);
			}
			 valores1= new Array(ld_periodo,uf_convertir($('hidsueldo').value,2),ld_monincvac,ld_monincfin,ld_salintdia,$(hiddiasal).value,li_diacomp );
	
			 //Chequeo de Anticipo
			 ue_anticipos();
			 li_mesanticipo = $('hidfecant').value.substr(5,2);	
		     li_anoanticipo = $('hidfecant').value.substr(0,4);	
			 if ((li_mesanticipo===li_mesper)&&(li_anoanticipo===li_anoper))
			 { 
				 ld_anticipo=$('hidmonant').value;
				 ls_ps_parcial=(eval(ls_ps_parcial)+eval(ld_psocial)-eval(ld_anticipo));
			 }
			 else
			 { 
				 ld_anticipo=0;
				 ls_ps_parcial=eval(ls_ps_parcial)+eval(ld_psocial);
			 }
			 //intereses
			 if ($F('cmbcapint')=='S')                           
			 {
			 	li_diaint =30;
				ue_tasa_interes(li_anoper,li_mesper);
				ld_tasa   = $('hidtasint').value;
				ld_tasa_porc= $('hidtasint').value/100;
				ld_intper1 = parseFloat(eval(ls_ps_parcial) * eval(ld_tasa_porc));
				ld_intper = (ld_intper1/365*eval(li_diaint));
				ld_intacu = eval(ld_intper)+eval(ld_intacu);
			 }
			 else
			 {	ld_tasa = 0;	li_diaint = 0;	ld_intper = 0;	ld_intacu = 0; }
			 ld_saldototal   = eval(ls_ps_parcial)+eval(ld_intacu);		
			 ld_psocial  = uf_convertir(ld_psocial,2); 
			 ld_ps_acum2 = uf_convertir(ld_ps_acum,2); 
			 ls_ps_parc2 = uf_convertir(ls_ps_parcial,2); 
			 ld_tasa     = uf_convertir(ld_tasa,2);  
			 ld_intper   = uf_convertir(ld_intper,2);
			 ld_intacu2  = uf_convertir(ld_intacu,2);
			 ld_saldototales = uf_convertir(ld_saldototal,2); 
			
			 valores2 = new Array(ld_psocial,ld_ps_acum2,ld_anticipo,ls_ps_parc2,ld_tasa,li_diaint,ld_intper,ld_intacu2,ld_saldototales);
			 valores = valores1.concat(valores2);
			 ue_agregar_detalle(valores);
		  }	 
	} //end for (i=1; i<li_peri; i++)
	deshabilitar("txtfecdes,txtfechas,txtfecingper,txtanoserant,txtmesserant,txtdiaserant,cmbcapint,txtfecvig,btngendeu");	
} //end function
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
				  "salint"     : 0,
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
				  "saltotant"  : columnas[15].innerHTML,
				  "estcapint"  : $F('cmbcapint')
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
function ue_aplicar_articulo(literal)
{  
 	ls_numlitart=trim(literal.numlitart);
	ls_operador =trim(literal.operador);
	ls_canmes   =trim(literal.canmes);
	ls_tiempo   =trim(literal.tiempo);
	ls_diasal   =literal.diasal;
	ls_condicion=trim(literal.condicion);
	ls_estacu   =trim(literal.estacu);
	ls_diaacu   =trim(literal.diaacu);
	ls_numcon   =trim(literal.numcon);
	li_meses    =restarFechas("txtfecdes","txtfechas","m");
       
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
			if ((ls_numlitart == 'a')||(ls_numlitart=='A'))
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
					if (li_meses == ls_canmes)
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
			if ((ls_numlitart=='b')||(ls_numlitart=='B'))  //caso dias complementarios
			{    
				li_meses=restarFechas("txtfecingper","txtfechas","m");
				if (ls_operador==1) //indica operador >
				{   
					if (eval(li_meses) > eval(ls_canmes)) 
					{ 
						$('hiddiasal2').value = ls_diasal;
						$('hidtiempo2').value = ls_tiempo;
						
						if (ls_estacu == 'S') 
						{ $('hiddiaacu2').value = ls_diaacu; }
						else { $('hiddiaacu2').value = 0; }
					}
				}
				if (ls_operador==3) //indica operador >=
				{     
					if ((eval(li_meses)>eval(ls_canmes))||(li_meses==ls_canmes))
					{      
						$('hiddiasal2').value = ls_diasal;
						$('hidtiempo2').value = ls_tiempo;
						if (ls_estacu == 'S')
						{ $('hiddiaacu2').value = ls_diaacu; }		
						else { $('hiddiaacu2').value = 0; }
					}
				}
				if (ls_operador==2) //indica operador <
				{
					if (eval(li_meses) < eval(ls_canmes)) 
					{
						$('hiddiasal2').value = ls_diasal;
						$('hidtiempo2').value = ls_tiempo;
						if (ls_estacu == 'S')
						{ $('hiddiaacu2').value = ls_diaacu; }		
						else { $('hiddiaacu2').value = 0; }
					}
				}
				if (ls_operador==4) //indica operador <=
				{
					if (eval(ls_canmes)<=eval(li_meses)) 
					{
						$('hiddiasal2').value = ls_diasal;
						$('hidtiempo2').value = ls_tiempo;
						if (ls_estacu == 'S')
						{ $('hiddiaacu2').value = ls_diaacu; }
						else { $('hiddiaacu2').value = 0; }
					}
				}
				if (ls_operador==5) //indica operador ==
				{
					if (li_meses == ls_canmes)
					{
						$('hiddiasal2').value = ls_diasal;
						$('hidtiempo2').value = ls_tiempo;
						if (ls_estacu == 'S')
						{ $('hiddiaacu2').value = ls_diaacu; }		
						else { $('hiddiaacu2').value = 0; }
					}
				}
			}		
		}
	
  
}