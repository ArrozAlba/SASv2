
var url= "../../php/sigesp_srh_a_necesidad_adiestramiento.php";
var metodo='get';
var img="<img src=../../../public/imagenes/progress.gif> ";


var loadDataURL = "../../php/sigesp_srh_a_necesidad_adiestramiento.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_solicitud_empleo.php";
var mygrid;
var timeoutHandler;//update will be sent automatically to server if row editting was stoped;
var rowUpdater;//async. Calls doUpdateRow function when got data from server
var rowEraser;//async. Calls doDeleteRow function when got confirmation about row deletion
var authorsLoader;//sync. Loads list of available authors from server to populate dropdown (co)
var mandFields = [0,1,1,0,0];
		
		
	//initialise (from xml) and populate (from xml) grid
function doOnLoad()
		{
          
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Nr. Registro,Fecha");
			mygrid.setInitWidths("255,255");
			mygrid.setColAlign("center,center");
			mygrid.setColTypes("link,ro");
			mygrid.setColSorting("str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF");
			//mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
			


		}
		

function terminar_buscar ()
{ 
   divResultado = document.getElementById('mostrar');
   divResultado.innerHTML= '';   
}
		
function Buscar()
{

	 nroreg=document.form1.txtnroreg.value;
	 fechades=document.form1.txtfechades.value;
	 fechahas=document.form1.txtfechahas.value;
	 
	 valfec= ue_comparar_fechas(fechades,fechahas);
	 
 	 if (!valfec)
	 {
		alert ('Rango de Fecha Invalido.');	 
	 }
	 else
	 {
	 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_necesidad_adiestramiento.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtfechades="+fechades+"&txtfechahas="+fechahas);
		 setTimeout (terminar_buscar,650);
	 }

}
		
		
	
function Limpiar_busqueda () 
{
	document.form1.txtnroreg.value="";
	document.form1.txtfechades.value=document.form1.txtfechades2.value;
	document.form1.txtfechahas.value=document.form1.txtfechahas2.value;
}
	
	
function aceptar (ls_nroreg,ls_fecha,ls_coduni,ls_denuni,ls_codper,ls_nomper,ls_codcarper,ls_nivacaper,ls_codsup,ls_nomsup,ls_codcarsup,ls_comptec,ls_area,ls_obj,ls_estra,ls_obs,ls_nroregdestino,ls_fechadestino,ls_codunidestino,ls_denunidestino,ls_codperdestino,ls_nomperdestino,ls_codcarperdestino,ls_nivacaperdestino,ls_codsupdestino,ls_nomsupdestino,ls_codcarsupdestino,ls_comptecdestino,ls_areadestino,ls_objdestino,ls_estradestino,ls_obsdestino)
	{
		
		
		
		obj=eval("opener.document.form1."+ls_nroregdestino+"");
		obj.value=ls_nroreg;
		
		obj1=eval("opener.document.form1."+ls_fechadestino+"");
		obj1.value=ls_fecha;
		
			
		obj3=eval("opener.document.form1."+ls_codperdestino+"");
		obj3.value=ls_codper;
		
		obj4=eval("opener.document.form1."+ls_nomperdestino+"");
		obj4.value=ls_nomper;
		
		obj5=eval("opener.document.form1."+ls_codcarperdestino+"");
		obj5.value=ls_codcarper;
		
		obj6=eval("opener.document.form1."+ls_nivacaperdestino+"");
		obj6.value=ls_nivacaper;	
		
		obj7=eval("opener.document.form1."+ls_codunidestino+"");
		obj7.value=ls_coduni;
		
		obj8=eval("opener.document.form1."+ls_denunidestino+"");
		obj8.value=ls_denuni;
		
		obj9=eval("opener.document.form1."+ls_codsupdestino+"");
		obj9.value=ls_codsup;
		
		obj10=eval("opener.document.form1."+ls_nomsupdestino+"");
		obj10.value=ls_nomsup;
		
		obj11=eval("opener.document.form1."+ls_codcarsupdestino+"");
		obj11.value=ls_codcarsup;
		
		obj13=eval("opener.document.form1."+ls_comptecdestino+"");
		obj13.value=ls_comptec;
		
		obj14=eval("opener.document.form1."+ls_areadestino+"");
		obj14.value=ls_area;
		
		obj15=eval("opener.document.form1."+ls_objdestino+"");
		obj15.value=ls_obj;
		
		obj16=eval("opener.document.form1."+ls_estradestino+"");
		obj16.value=ls_estra;
		
		obj17=eval("opener.document.form1."+ls_obsdestino+"");
		obj17.value=ls_obs;
				
	  	 ls_ejecucion= document.form1.hidstatus.value;
	      
		  if(ls_ejecucion=="1")
			{
			opener.document.form1.hidguardar.value = "modificar";
			opener.document.form1.hidstatus.value="C";		
			
			}else{
			 
			opener.document.form1.hidguardar.value = "insertar";	
			opener.document.form1.hidstatus.value="";
			 
			}
			opener.document.form1.txtnroreg.readOnly=true;
			
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_necesidad_adiestramiento.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
			
	
			close ();
}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
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
		
