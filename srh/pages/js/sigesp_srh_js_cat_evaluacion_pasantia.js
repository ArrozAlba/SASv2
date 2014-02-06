
var url= "../../php/sigesp_srh_a_evaluacion_pasantia.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_evaluacion_pasantia.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_evaluacion_pasantia.php";
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
			mygrid.setHeader("Numero,Fecha Eval.,Cedula,Nombre y Apellido");
			mygrid.setInitWidths("90,100,100,210");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
			//mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
			

		}
		
		
		
function Buscar()
{

	 nropas=document.form1.txtnropas.value;
	 cedpas=document.form1.txtcedpas.value;
	 nompas=document.form1.txtnompas.value;
	 apepas=document.form1.txtapepas.value;
	 fecevaldes=document.form1.txtfecevaldes.value;
	 fecevalhas=document.form1.txtfecevalhas.value;
 
 	 valfec= ue_comparar_fechas(fecevaldes,fecevalhas);
	 
     if (!valfec)
	 {
		alert ('Rango de Fecha Invalido.');	 
	 }
	 else
	 {
 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_evaluacion_pasantia.php?valor=buscar"+"&txtnropas="+nropas+"&txtfecevaldes="+fecevaldes+"&txtfecevalhas="+fecevalhas+"&txtcedpas="+cedpas+"&txtnompas="+nompas+"&txtapepas="+apepas);
		  setTimeout (terminar_buscar,650);
	 }

}
	
function terminar_buscar ()
{ 
   divResultado = document.getElementById('mostrar');
   divResultado.innerHTML= '';   
}
	
function Limpiar_busqueda () 
{
	$('txtnropas').value="";
	$('txtcedpas').value="";
	$('txtnompas').value="";
	$('txtapepas').value="";
	$('txtfecevaldes').value=$('txtfechahas2').value;
	$('txtfecevalhas').value=$('txtfechades2').value;
	
}

		
function aceptar (ls_nropas, ls_cedpas,ls_feceval, ls_apepas, ls_nompas, ls_estado, ls_obs, ls_res, ls_nrodestino ,  ls_ceddestino ,  ls_fecevaldestino ,  ls_apedestino ,  ls_nomdestino , ls_estadodestino, ls_obsdestino, ls_resdestino,ls_fecini, ls_fecinidestino)
	
	{
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nropas;
		
		obj1=eval("opener.document.form1."+ls_ceddestino+"");
		obj1.value=ls_cedpas;
		
		obj2=eval("opener.document.form1."+ls_fecevaldestino+"");
		obj2.value=ls_feceval;
		
		if (ls_apepas!='0') {
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nompas+" "+ls_apepas;
		}
		else 
		{
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nompas;
		}
		obj5=eval("opener.document.form1."+ls_estadodestino+"");
		obj5.value=ls_estado;
		
		obj6=eval("opener.document.form1."+ls_resdestino+"");
		obj6.value=ls_res;
		
		obj7=eval("opener.document.form1."+ls_obsdestino+"");
		obj7.value=ls_obs;
		
		obj8=eval("opener.document.form1."+ls_fecinidestino+"");
		obj8.value=ls_fecini;
		
	
			
			
			opener.document.form1.txtnropas.readOnly=true;
			 ls_ejecucion=document.form1.hidstatus.value;
			if (ls_ejecucion=='1')
			{
			  opener.document.form1.hidstatus.value="C";
			   opener.document.form1.hidguardar.value = "modificar";
			}
	       else
			{
			opener.document.form1.hidguardar.value = "insertar";	
			opener.document.form1.hidstatus.value="";
			}
			
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_evaluacion_pasantias.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
 		
			
			close();
			
		 	
}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
		
		
//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function seleodid(cal, date) {
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

    var cal = new Calendar(1, null, seleodid, closeHandler);
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
