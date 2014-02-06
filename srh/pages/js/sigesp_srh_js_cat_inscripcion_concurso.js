
var url= "../../php/sigesp_srh_a_inscripcion_concurso.php";
var metodo='get';
var img="<img src=../../../public/imagenes/progress.gif> ";


var loadDataURL = "../../php/sigesp_srh_a_inscripcion_concurso.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_inscripcion_concurso.php";
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
			mygrid.setHeader("Codigo Concurso, Codigo/Cedula,Nombre,Apellido");
			mygrid.setInitWidths("90,90,160,160");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
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
 	 codcon=document.form1.txtcodcon.value;
	 cedper=document.form1.txtcedper.value;
	 nomper=document.form1.txtnomper.value;
	 apeper=document.form1.txtapeper.value;
	 
	  mygrid.clearAll();
	  divResultado = document.getElementById('mostrar');
	  divResultado.innerHTML= img; 
	  mygrid.loadXML("../../php/sigesp_srh_a_inscripcion_concurso.php?valor=buscar"+"&txtcodcon="+codcon+"&txtcedper="+cedper+"&txtnomper="+nomper+"&txtapeper="+apeper);
	 setTimeout (terminar_buscar,650);


}

	
function Limpiar_busqueda () 
{
	$('txtcodcon').value="";
	$('txtcedper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	$('txtdescon').value="";
	
}
	
	
//FUNCIONES PARA INICIALIZAR LOS COMBOS DE ESTADO MUNICIPIO Y PARROQUIA AL TRAER LOS DATOS DEL CATALOGO



function LimpiarCambioPaisNac()
{
  removeAllOptions(opener.document.form1.cmbcodestnac);
  opener.document.form1.cmbcodestnac.selectedIndex = 0;

}
function ue_cambiopaisnac (ls_codpainac)
{
  LimpiarCambioPaisNac();

  if (ue_valida_null(opener.document.form1.cmbcodpainac))
  {
    function onCambioPais(respuesta)
    {
	  var estados=JSON.parse(respuesta.responseText);
	  
	  for (j=0; j<estados.codest.length; j++)
	  {
		  opener.document.form1.cmbcodestnac.options[opener.document.form1.cmbcodestnac.options.length] = new Option(estados.desest[j],estados.codest[j]);
		
		
		  }
	  
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim(opener.document.form1.hidcodestnac.value) != "")
	  {
		opener.document.form1.cmbcodestnac.value = opener.document.form1.hidcodestnac.value;
		opener.document.form1.hidcodestnac.value = "";
	  }
    }
		
    params = "operacion=ue_inicializarestado&codpai="+ls_codpainac;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioPais});
  }  
}


function ue_nuevo_codestudio2()
{ 

 function onNuevo(respuesta)
  {	   
   opener.document.form1.txtcodestper.value  = trim(respuesta.responseText);
   opener.document.form1.txtcodestper.focus();
  }
  var codper = opener.document.form1.txtcodper.value;
  var codcon = opener.document.form1.txtcodcon.value;

 params = "operacion=ue_nuevo_estudio&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}


function ue_nuevo_codcurso2()
{ 

 function onNuevo(respuesta)
  {	   
   opener.document.form1.txtcodcur.value  = trim(respuesta.responseText);
   opener.document.form1.txtdescur.focus();
  }
  var codper = opener.document.form1.txtcodper.value;
  var codcon = opener.document.form1.txtcodcon.value;

 params = "operacion=ue_nuevo_curso&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}

function ue_nuevo_trabajo2()
{ 

 function onNuevo(respuesta)
  {	   
   opener.document.form1.txtcodtraant.value  = trim(respuesta.responseText);
   opener.document.form1.txtemptraant.focus();
  }
  var codper = opener.document.form1.txtcodper.value;
  var codcon = opener.document.form1.txtcodcon.value;

 params = "operacion=ue_nuevo_trabajo&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}

function ue_nuevo_familiar2()
{ 

 function onNuevo(respuesta)
  {	   
   opener.document.form1.txtcodfam.value  = trim(respuesta.responseText);
   opener.document.form1.txtnomfam.focus();
  }
  var codper = opener.document.form1.txtcodper.value;
  var codcon = opener.document.form1.txtcodcon.value;

 params = "operacion=ue_nuevo_familiar&codper="+codper+"&codcon="+codcon;
 new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});

}


	
function aceptar (ls_codcon,ls_descon,ls_codcar,ls_cantcar,ls_tipo,ls_fechaaper,ls_fechacie,ls_codper,ls_fecreg,ls_apeper,ls_nomper,ls_sexper,ls_fecnacper,ls_telhabper,ls_codpai,ls_codest,ls_dirper,ls_telmovper,ls_edocivper,ls_nacper,ls_codcondestino, ls_descondestino, ls_codcardestino, ls_cantcardestino, ls_tipodestino, ls_fechaaperdestino, ls_fechaciedestino, ls_codperdestino, ls_fecregdestino, ls_apeperdestino, ls_nomperdestino, ls_sexperdestino, ls_fecnacperdestino, ls_telhabperdestino, ls_codpaidestino, ls_codestdestino, ls_dirperdestino ,ls_telmovperdestino, ls_edocivperdestino,ls_nacperdestino,ls_descar,ls_descardestino,ls_codpro, ls_despro, ls_nivaca, ls_corele,ls_codprodestino, ls_desprodestino, ls_nivacadestino, ls_coreledestino)
{
	
		obj1=eval("opener.document.form1."+ls_codcondestino+"");
		obj1.value=ls_codcon;
		
		obj2=eval("opener.document.form1."+ls_descondestino+"");
		obj2.value=ls_descon;
		
		obj3=eval("opener.document.form1."+ls_codcardestino+"");
		obj3.value=ls_codcar;
		
		obj4=eval("opener.document.form1."+ls_cantcardestino+"");
		obj4.value=ls_cantcar;
		
		obj5=eval("opener.document.form1."+ls_tipodestino+"");
		obj5.value=ls_tipo;
		
		obj6=eval("opener.document.form1."+ls_fechaaperdestino+"");
		obj6.value=ls_fechaaper;
		
		obj7=eval("opener.document.form1."+ls_fechaciedestino+"");
		obj7.value=ls_fechacie;
		
		
		obj9=eval("opener.document.form1."+ls_codperdestino+"");
		obj9.value=ls_codper;
		
		obj10=eval("opener.document.form1."+ls_fecregdestino+"");
		obj10.value=ls_fecreg;
		
		obj11=eval("opener.document.form1."+ls_apeperdestino+"");
		obj11.value=ls_apeper;
		
		obj12=eval("opener.document.form1."+ls_nomperdestino+"");
		obj12.value=ls_nomper;
		
		obj13=eval("opener.document.form1."+ls_sexperdestino+"");
		obj13.value=ls_sexper;
			
		obj14=eval("opener.document.form1."+ls_fecnacperdestino+"");
		obj14.value=ls_fecnacper;
		
		obj15=eval("opener.document.form1."+ls_telhabperdestino+"");
		obj15.value=ls_telhabper;
		
		obj16=eval("opener.document.form1."+ls_codpaidestino+"");
		obj16.value=ls_codpai;
		
		obj17=eval("opener.document.form1."+ls_dirperdestino+"");
		obj17.value=ls_dirper;
		
		obj18=eval("opener.document.form1."+ls_telmovperdestino+"");
		obj18.value=ls_telmovper;
		
		obj19=eval("opener.document.form1."+ls_edocivperdestino+"");
		obj19.value=ls_edocivper;
		
		obj20=eval("opener.document.form1."+ls_nacperdestino+"");
		obj20.value=ls_nacper;
		
		obj21=eval("opener.document.form1."+ls_descardestino+"");
		obj21.value=ls_descar;
		
		obj21=eval("opener.document.form1."+ls_codprodestino+"");
		obj21.value=ls_codpro;
		
		obj22=eval("opener.document.form1."+ls_desprodestino+"");
		obj22.value=ls_despro;
		
		obj23=eval("opener.document.form1."+ls_coreledestino+"");
		obj23.value=ls_corele;
		
		obj24=eval("opener.document.form1."+ls_nivacadestino+"");
		obj24.value=ls_nivaca;
		
		ls_ejecucion=document.form1.hidstatus.value
		if(ls_ejecucion=="1")
		{
		 opener.document.form1.hidguardar.value = "modificar";
		 opener.document.form1.hidstatus.value="C";	
		}else{
   		 opener.document.form1.hidguardar.value = "insertar";	
		 opener.document.form1.hidstatus.value="";
		 	
		}
		
		opener.document.form1.hidcodestnac.value=ls_codest;		
		ue_cambiopaisnac (ls_codpai);
		
		ue_nuevo_codestudio2();
		ue_nuevo_codcurso2();
		ue_nuevo_trabajo2();
		ue_nuevo_familiar2();
		
		setTimeout(close,1600);
	
}  
   
		
function catalogo_concurso()
{ 
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor=0";
   window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
}   

	
