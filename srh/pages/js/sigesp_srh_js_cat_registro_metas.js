
var url= "../../php/sigesp_srh_a_registro_metas.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_registro_metas.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_registro_metas.php";
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
			mygrid.setHeader("Numero,Fecha ,Cedula,Nombre y Apellido");
			mygrid.setInitWidths("95,95,100,210");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
		//	mygrid.loadXML(loadDataURL);
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
	 codper=document.form1.txtcodper.value;
	 nomper=document.form1.txtnomper.value;
	 apeper=document.form1.txtapeper.value;
	 fechades=document.form1.txtfechades.value;
	 fechahas=document.form1.txtfechahas.value;
	 
	  valfecha=ue_comparar_fechas(fechades,fechahas);
	  if (!valfecha)
	 {
		alert ('Rango de Fecha Invalido.');	 
	 }
	 else
	 {
	 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_registro_metas.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtcodper="+codper+"&txtnomper="+nomper+"&txtapeper="+apeper);
		  setTimeout (terminar_buscar,650);
	 }

}
	

	
function Limpiar_busqueda () 
{
	$('txtnroreg').value="";
	$('txtcodper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	$('txtfechades').value=$('txtfecdes2').value;
	$('txtfechahas').value=$('txtfechas2').value;
	
}

		
function aceptar (ls_nroreg, ls_codper, ls_fecreg, ls_apeper, ls_nomper, ls_fecini, ls_obs, ls_fecfin, ls_nrodestino ,  ls_ceddestino ,  ls_fecregdestino ,  ls_apedestino ,  ls_nomdestino , ls_fecinidestino, ls_obsdestino, ls_fecfindestino, ls_codcarper, ls_codcarperdestino)
	
	{
		
		tipo=document.form1.hidtipo.value;
		
		if (tipo=='cat')
		{
			obj=eval("opener.document.form1.txtnroreg");
			obj.value=ls_nroreg;
			
		}
		else
		{
		
			obj=eval("opener.document.form1."+ls_nrodestino+"");
			obj.value=ls_nroreg;
			
			obj1=eval("opener.document.form1."+ls_ceddestino+"");
			obj1.value=ls_codper;
			
			
			obj2=eval("opener.document.form1."+ls_fecregdestino+"");
			obj2.value=ls_fecreg;
			
			if (ls_apeper!='0') {
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper+" "+ls_apeper;
			}
			else 
			{
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper;
			}
			
			obj1=eval("opener.document.form1."+ls_codcarperdestino+"");
			obj1.value=ls_codcarper;
			
			obj5=eval("opener.document.form1."+ls_fecinidestino+"");
			obj5.value=ls_fecini;
			
			obj6=eval("opener.document.form1."+ls_fecfindestino+"");
			obj6.value=ls_fecfin;
			
			obj7=eval("opener.document.form1."+ls_obsdestino+"");
			obj7.value=ls_obs;
			
			ls_ejecucion=document.form1.hidstatus.value;
		
			  if(ls_ejecucion=="1")
				{
				opener.document.form1.hidguardar.value = "modificar";
				opener.document.form1.hidstatus.value="C";	
				}else{
				opener.document.form1.hidguardar.value = "insertar";	
				opener.document.form1.hidstatus.value="";
					
				}
				
				
			if (opener.document.form1.hidcontrol.value=='1')	 {
				
				 
				opener.document.form1.txtnroreg.readOnly=true;
				 opener.document.form1.hidguardar.value = "modificar";
				opener.document.form1.operacion.value="BUSCARDETALLE";
				opener.document.form1.action="../pantallas/sigesp_srh_p_registro_metas.php";
				opener.document.form1.existe.value="TRUE";			
				opener.document.form1.submit();	
				
			}
		}
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
		
