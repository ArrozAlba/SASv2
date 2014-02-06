
var url= "../../php/sigesp_srh_a_ganadores_concurso.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_ganadores_concurso.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_ganadores_concurso.php";
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
			mygrid.setHeader("Codigo Concurso,Descripcion,Fecha");
			mygrid.setInitWidths("100,322,100");
			mygrid.setColAlign("center,center,center");
			mygrid.setColTypes("link,ro,ro");
			mygrid.setColSorting("str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF");
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

 descon=document.form1.txtdescon.value;
 codcon=document.form1.txtcodcon.value;
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
		 mygrid.loadXML("../../php/sigesp_srh_a_ganadores_concurso.php?valor=buscar"+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtcodcon="+codcon);
		 setTimeout (terminar_buscar,650);
	 }

}
	
	


	
function aceptar ( ls_codcon, ls_fecha, ls_descon, ls_codcondestino,ls_descondestino, ls_fechadestino, ls_ejecucion)
	{
	
		obj0=eval("opener.document.form1."+ls_codcondestino+"");
		obj0.value=ls_codcon;
		
		obj1=eval("opener.document.form1."+ls_descondestino+"");
		obj1.value=ls_descon;
		
		obj2=eval("opener.document.form1."+ls_fechadestino+"");
		obj2.value=ls_fecha;
			
	
		
	
	    ls_ejecucion=document.form1.hidstatus.value
		if(ls_ejecucion=="1")
		{
		 opener.document.form1.hidguardar.value = "modificar";
		 opener.document.form1.hidstatus.value="C";	
		}else{
   		 opener.document.form1.hidguardar.value = "insertar";	
		 opener.document.form1.hidstatus.value="";
		 	
		}
			
		    opener.document.form1.txtdescon.readOnly=true;
			opener.document.form1.txtfecha.readOnly=true;
		
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_ganadores_concurso.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
			close ();
}
	
	

function catalogo_concurso()
{
    
   pagina="../catalogos/sigesp_srh_cat_concurso.php";

  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}
	
function Limpiar_busqueda () 
{
	$('txtcodcon').value="";
	$('txtdescon').value="";
	$('txtfechades').value=$('txtfecdes2').value;
	$('txtfechahas').value=$('txtfechas2').value;

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
		
