
var url= "../../php/sigesp_srh_a_personal.php";
var metodo='get';
var img="<img src=../../../public/imagenes/progress.gif> ";

//var loadDataURL = "../../php/sigesp_srh_a_personal.php?valor=createXML"+"&hidtipo="+hidtipo;

var mygrid;
var timeoutHandler;//update will be sent automatically to server if row editting was stoped;
var rowUpdater;//async. Calls doUpdateRow function when got data from server
var rowEraser;//async. Calls doDeleteRow function when got confirmation about row deletion
var authorsLoader;//sync. Loads list of available authors from server to populate dropdown (co)
var mandFields = [0,1,1,0,0];
		
		
function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		Buscar();
		return false;
	}
	else
		return true
}
		
	//initialise (from xml) and populate (from xml) grid
function doOnLoad()
		{
            hidtipo=document.form1.hidtipo.value;
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Código,Cedula,Apellido, Nombre");
			mygrid.setInitWidths("90,90,150,170");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
			//mygrid.loadXML("../../php/sigesp_srh_a_personal.php?valor=createXML"+"&hidtipo="+hidtipo);
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

 codper=document.form1.txtcodper.value;
 cedper=document.form1.txtcedper.value;
 nomper=document.form1.txtnomper.value;
 apeper=document.form1.txtapeper.value;
 hidtipo=document.form1.hidtipo.value;
 
  
  mygrid.clearAll();
  divResultado = document.getElementById('mostrar');
  divResultado.innerHTML= img; 
  mygrid.loadXML("../../php/sigesp_srh_a_personal.php?valor=buscar"+"&txtcodper="+codper+"&txtcedper="+cedper+"&txtnomper="+nomper+"&txtapeper="+apeper+"&hidtipo="+hidtipo);
 setTimeout (terminar_buscar,3000);
}
		
		
	
function Limpiar_busqueda () 
{
	$('txtcodper').value="";
	$('txtcedper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	
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
   



	
//FUNCIONES PARA INICIALIZAR LOS CODIGOS

function ue_nuevo_codestudio2()
  { 
  //-----------agregado el 17/03/2008--------------------------------------------------
	 function onNuevo(respuesta)
      {	   
	   opener.document.form1.txtcodestrea.value  = trim(respuesta.responseText);
	   opener.document.form1.txtcodestrea.focus();
      }
	  var codper =  opener.document.form1.txtcodper.value;
	 //--------------------------------------------------------------------------
     params = "operacion=ue_nuevo_estudio&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	//-----------------------------------------------------------------------------------
  }

  
  //-------------------------------------------------------------------------------------------  
   function ue_nuevo_trabajo2()
  {
  //-----------agregado el 17/03/2008--------------------------------------------------
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtcodtraant.value  = trim(respuesta.responseText);
	   opener.document.form1.txtcodtraant.focus();
      }
	   var codper =  opener.document.form1.txtcodper.value;
	 //--------------------------------------------------------------------------
     params = "operacion=ue_nuevo_trabajo&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	//-----------------------------------------------------------------------------------
  }  
//-----------------------------------------------------------------------------------------------------------------------
	
function ue_nuevo_permiso2()
  {
  //-----------agregado el 17/03/2008--------------------------------------------------
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtnumper.value  = trim(respuesta.responseText);
	   opener.document.form1.txtnumper.focus();
      }
	 
	  var codper =  opener.document.form1.txtcodper.value;
	 //--------------------------------------------------------------------------
     params = "operacion=ue_nuevo_permiso&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	//-----------------------------------------------------------------------------------
  }  
//------------------------------------------------------------------------------------------------------------------
	

	
function ue_nuevo_movimiento2()
  {
  //-----------agregado el 17/03/2008--------------------------------------------------
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtnummov.value  = trim(respuesta.responseText);
	   opener.document.form1.txtnummov.focus();
      }
	 
	  var codper =  opener.document.form1.txtcodper.value;
	 //--------------------------------------------------------------------------
     params = "operacion=ue_nuevo_movimiento&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	//-----------------------------------------------------------------------------------
  }  
  
  
  function ue_nuevo_premio2()
  {
  //-----------agregado el 17/03/2008--------------------------------------------------
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtnumprem.value  = trim(respuesta.responseText);
	   opener.document.form1.txtnumprem.focus();
      }
	 
	  var codper =  opener.document.form1.txtcodper.value;
	 //--------------------------------------------------------------------------
     params = "operacion=ue_nuevo_premio&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	//-----------------------------------------------------------------------------------
  }  
  
  
  
function ue_nuevo_beneficiario2()
  {
  //-----------agregado el 17/03/2008--------------------------------------------------
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtcodben.value  = trim(respuesta.responseText);
	   opener.document.form1.txtcodben.focus();
      }
	 
	  var codper =  opener.document.form1.txtcodper.value;
	 //--------------------------------------------------------------------------
     params = "operacion=ue_nuevo_beneficiario&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	//-----------------------------------------------------------------------------------
  }  
  
   function ue_buscar_cargo_actual2()
  {
 
	 function onNuevo(respuesta)
      {
	   opener.document.form1.txtcaract.value  = trim(respuesta.responseText);
	   opener.document.form1.txtcaract.focus();
      }
	 
	  var codper = opener.document.form1.txtcodper.value;
	
     params = "operacion=ue_buscar_cargo_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

function ue_buscar_uniadm_actual2()
  {
 
	 function onNuevo(respuesta)
      {
	  opener.document.form1.txtuniadm.value  = trim(respuesta.responseText);
	  opener.document.form1.txtuniadm.focus();
      }
	 
	  var codper = opener.document.form1.txtcodper.value;
	
     params = "operacion=ue_buscar_uniadm_actual&codper="+codper;
     new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
	
  }  

	
	
	
//FUNCIONES PARA INICIALIZAR LOS COMBOS DE ESTADO MUNICIPIO Y PARROQUIA AL TRAER LOS DATOS DEL CATALOGO


function LimpiarCambioPais2()
{
  removeAllOptions(opener.document.form1.cmbcodest);
  opener.document.form1.cmbcodest.selectedIndex = 0;
  LimpiarCambioEstado2();
}

function LimpiarCambioPaisNac()
{
  removeAllOptions(opener.document.form1.cmbcodestnac);
  opener.document.form1.cmbcodestnac.selectedIndex = 0;

}

function LimpiarCambioEstado2()
{
  removeAllOptions(opener.document.form1.cmbcodmun);	
  opener.document.form1.cmbcodmun.selectedIndex = 0;
  LimpiarCambioMunicipio2();
}


function LimpiarCambioMunicipio2()
{
  removeAllOptions(opener.document.form1.cmbcodpar);
  opener.document.form1.cmbcodpar.selectedIndex = 0;
}


function ue_cambiopais2(ls_codpai, ls_codest, ls_codmun)
{
  LimpiarCambioPais2();
  if (ue_valida_null(opener.document.form1.cmbcodpai))
  {
    function onCambioPais(respuesta)
    {
	  var estados=JSON.parse(respuesta.responseText);
	  
	  for (j=0; j<estados.codest.length; j++)
	  {
		  opener.document.form1.cmbcodest.options[opener.document.form1.cmbcodest.options.length] = new Option(estados.desest[j],estados.codest[j]);
		
		
		  }
	  
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim(opener.document.form1.hidcodest.value) != "")
	  {
		opener.document.form1.cmbcodest.value = opener.document.form1.hidcodest.value;
		opener.document.form1.hidcodest.value = "";
		ue_cambioestado2 (ls_codpai, ls_codest, ls_codmun);
	  }
    }
		
    params = "operacion=ue_inicializarestado&codpai="+ls_codpai;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioPais});
  }  
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


function ue_cambioestado2 (ls_codpai, ls_codest, ls_codmun)
{
  LimpiarCambioEstado2();
  if (ue_valida_null(opener.document.form1.cmbcodest))
  {
    function onCambioEstado(respuesta)
    {
	  var municipios = JSON.parse(respuesta.responseText);
	  for (j=0; j<municipios.codmun.length; j++)
	  {opener.document.form1.cmbcodmun.options[opener.document.form1.cmbcodmun.options.length] = new Option(municipios.denmun[j],municipios.codmun[j]);}
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim(opener.document.form1.hidcodmun.value) != "")
	  {
		opener.document.form1.cmbcodmun.value = opener.document.form1.hidcodmun.value;
		opener.document.form1.hidcodmun.value = "";
		ue_cambiomunicipio2 (ls_codpai, ls_codest, ls_codmun);
	  }
    }
		
    params = "operacion=ue_inicializarmunicipio&codpai="+ls_codpai+"&codest="+ls_codest;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioEstado});
  }  
} 


function ue_cambiomunicipio2 (ls_codpai, ls_codest, ls_codmun)
{
  LimpiarCambioMunicipio2();
  if (ue_valida_null(opener.document.form1.cmbcodmun))
  {
    function onCambioMunicipio(respuesta)
    {
	  var parroquias = JSON.parse(respuesta.responseText);
	  for (i=0; i<parroquias.codpar.length; i++)
	  {opener.document.form1.cmbcodpar.options[opener.document.form1.cmbcodpar.options.length] = new Option(parroquias.denpar[i],parroquias.codpar[i]);}	  
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim(opener.document.form1.hidcodpar.value) != "")
	  {
		opener.document.form1.cmbcodpar.value = opener.document.form1.hidcodpar.value;
		opener.document.form1.hidcodpar.value = "";
		ocultar_mensaje("mensaje");
		if (opener.document.form1.hidguardar.value == "modificar")
		{ try{catalogo.close();}catch(e){}}
	  }
    }
    params = "operacion=ue_inicializarparroquia&codpai="+ls_codpai+"&codest="+ls_codest+"&codmun="+ls_codmun;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioMunicipio});  
  }  
}









function aceptar_persona1 (ls_codper, ls_cedper, ls_nomper, ls_apeper,	ls_dirper,	ls_fecnacper, 	ls_edocivper, 	ls_nacper, ls_codpai,	ls_codest,	ls_codmun, ls_codpar, ls_telhabper, 	ls_coreleper,ls_telmovper, ls_sexper, ls_estaper, ls_pesper, ls_codpro,ls_despro, ls_nivacaper,	ls_codcom,	ls_codran,	ls_cedbenper,	ls_numhijper,	ls_contraper,	ls_obsper, ls_fotper, 				ls_cenmedper, ls_turper, 	ls_horper,	ls_hcmper,	ls_tipsanper,	ls_numexpper, ls_tipvivper, ls_tenvivper, ls_monpagvivper, ls_cuecajahoper, ls_cajahoper, ls_cuelphper, ls_cuefidper,	ls_fecingadmpubper, ls_anoservpreper, ls_fecingper, ls_fecegrper, ls_cauegrper, ls_obsegrper, ls_codperdestino, ls_cedperdestino, ls_nomperdestino, ls_apeperdestino,	ls_dirperdestino,	ls_fecnacperdestino, ls_edocivperdestino, ls_nacperdestino, ls_codpaidestino,	ls_codestdestino, ls_codmundestino, ls_codpardestino, ls_telhabperdestino, 	ls_coreleperdestino,	ls_telmovperdestino, ls_sexperdestino, ls_estaperdestino,	ls_pesperdestino,	ls_codprodestino, ls_desprodestino, ls_nivacaperdestino, ls_codcomdestino, ls_codrandestino,	ls_cedbenperdestino,	ls_numhijperdestino,	ls_contraperdestino,	ls_obsperdestino, ls_fotperdestino,	ls_cenmedperdestino, ls_turperdestino, 	ls_horperdestino,	ls_hcmperdestino,	ls_tipsanperdestino,	ls_numexpperdestino, ls_tipvivperdestino, ls_tenvivperdestino, ls_monpagvivperdestino,ls_cuecajahoperdestino, ls_cajahoperdestino,ls_cuelphperdestino, ls_cuefidperdestino,	ls_fecingadmpubperdestino, ls_anoservpreperdestino, ls_fecingperdestino, ls_fecegrperdestino, ls_cauegrperdestino, ls_obsegrperdestino, ls_codpainac, ls_codestnac,ls_codtippersss, ls_dentippersss, ls_codpainacdestino, ls_codestnacdestino,ls_codtippersssdestino,ls_dentippersssdestino, ls_codunivi,ls_codunividestino, ls_denunivi,ls_denunividestino, ls_fecjubper,ls_fecjubperdestino, ls_fecreingper,ls_fecreingperdestino, ls_fecfevid, ls_fecfeviddestino, ls_enviorec, ls_enviorecdestino, ls_descom, ls_descomdestino , ls_desran,ls_desrandestino, ls_feclossfan,ls_feclossfandestino,ls_codcausa, ls_codcausadestino, ls_dencausa, ls_dencausadestino,ls_situacion,ls_fecsitu,  ls_talcamper, ls_talpanper, ls_talzapper, ls_situaciondestino, ls_fecsitudestino,ls_talcamperdestino, ls_talpanperdestino , ls_talzapperdestino,ls_anoservprecont,ls_anoservprecontdestino,ls_anoservprefijo, ls_anoservprefijodestino, ls_codorg, ls_codorgdestino,ls_desorg, ls_desorgdestino,ls_porcajahoper,ls_porcajahoperdestino, ls_codger,ls_denger,ls_coddestino,ls_dendestino, ls_anoperobr, ls_anoperobrdestino, ls_carantper, ls_carantperdestino, dias, meses, anos)
	{
		opener.document.form1.txtdia.value=dias;
		opener.document.form1.txtmes.value=meses;
		opener.document.form1.txtano.value=anos;
		
		
		obj0=eval("opener.document.form1.txtdestippersss2");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper2");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtdestippersss3");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper3");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtdestippersss4");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper4");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtdestippersss5");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper5");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtdestippersss6");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper6");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtdestippersss7");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper7");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtdestippersss8");
		obj0.value=ls_dentippersss;
		obj0=eval("opener.document.form1.txtnumexpper8");
		obj0.value=ls_numexpper;
		obj0=eval("opener.document.form1.txtcodper2");
		obj0.value=ls_codper;
		obj0=eval("opener.document.form1.txtcodper3");
		obj0.value=ls_codper;
		obj0=eval("opener.document.form1.txtcodper4");
		obj0.value=ls_codper;
		obj0=eval("opener.document.form1.txtcodper5");
		obj0.value=ls_codper;
		obj0=eval("opener.document.form1.txtcodper6");
		obj0.value=ls_codper;
		obj0=eval("opener.document.form1.txtcodper7");
		obj0.value=ls_codper;
		obj0=eval("opener.document.form1.txtcodper8");
		obj0.value=ls_codper;
		
		foto=opener.document.getElementById('foto');
		foto.src="";
		if ((ls_fotper=="")||(ls_fotper=="blanco.jpg"))
		{
			foto.src="../../../fotos/silueta.jpg";
		}
		else
		{
			foto.src="../../../../sno/fotospersonal/"+ls_fotper;	
		}
		
		obj0=eval("opener.document.form1."+ls_codperdestino+"");
		obj0.value=ls_codper;
		
		obj1=eval("opener.document.form1."+ls_cedperdestino+"");
		obj1.value=ls_cedper;
		
		obj2=eval("opener.document.form1."+ls_apeperdestino+"");
		obj2.value=ls_apeper;
		
		obj3=eval("opener.document.form1."+ls_nomperdestino+"");
		obj3.value=ls_nomper;
		
		obj4=eval("opener.document.form1."+ls_dirperdestino+"");
		obj4.value=ls_dirper;
		
		obj5=eval("opener.document.form1."+ls_fecnacperdestino+"");
		obj5.value=ls_fecnacper;
		
		obj6=eval("opener.document.form1."+ls_edocivperdestino+"");
		obj6.value=ls_edocivper;
		
		obj7=eval("opener.document.form1."+ls_nacperdestino+"");
		obj7.value=ls_nacper;
		
		obj8=eval("opener.document.form1."+ls_codpaidestino+"");
		obj8.value=ls_codpai;
		
		obj9=eval("opener.document.form1."+ls_telhabperdestino+"");
		obj9.value=ls_telhabper;
		
		obj10=eval("opener.document.form1."+ls_coreleperdestino+"");
		obj10.value=ls_coreleper;
		
		obj11=eval("opener.document.form1."+ls_telmovperdestino+"");
		obj11.value=ls_telmovper;
		
		obj12=eval("opener.document.form1."+ls_sexperdestino+"");
		obj12.value=ls_sexper;
		
		obj13=eval("opener.document.form1."+ls_estaperdestino+"");
		obj13.value=ls_estaper;
		
		obj14=eval("opener.document.form1."+ls_pesperdestino+"");
		obj14.value=ls_pesper;
		
		obj15=eval("opener.document.form1."+ls_codprodestino+"");
		obj15.value=ls_codpro;
		
		obj16=eval("opener.document.form1."+ls_desprodestino+"");
		obj16.value=ls_despro;
		
	
		obj17=eval("opener.document.form1."+ls_nivacaperdestino+"");
		obj17.value=ls_nivacaper;
		
		obj18=eval("opener.document.form1."+ls_codcomdestino+"");
		obj18.value=ls_codcom;
		
		obj19=eval("opener.document.form1."+ls_codrandestino+"");
		obj19.value=ls_codran;
		
		obj21=eval("opener.document.form1."+ls_cedbenperdestino+"");
		obj21.value=ls_cedbenper;
		
		obj22=eval("opener.document.form1."+ls_numhijperdestino+"");
		obj22.value=ls_numhijper;
		 		
		obj23=eval("opener.document.form1."+ls_contraperdestino+"");
		obj23.value=ls_contraper;
		
		obj24=eval("opener.document.form1."+ls_obsperdestino+"");
		obj24.value=ls_obsper;
		
		obj25=eval("opener.document.form1."+ls_desrandestino+"");
		obj25.value=ls_desran;
		
		obj251=eval("opener.document.form1."+ls_descomdestino+"");
		obj251.value=ls_descom;

		
		obj26=eval("opener.document.form1."+ls_cenmedperdestino+"");
		obj26.value=ls_cenmedper;
		
		obj27=eval("opener.document.form1."+ls_turperdestino+"");
		obj27.value=ls_turper;
		
		obj28=eval("opener.document.form1."+ls_horperdestino+"");
		obj28.value=ls_horper;
			  
		if (ls_hcmper == '1') {
			opener.document.form1.chkhcmper.checked=true; 
		}
		
		obj30=eval("opener.document.form1."+ls_tipsanperdestino+"");
		obj30.value=ls_tipsanper;
		
		obj31=eval("opener.document.form1."+ls_numexpperdestino+"");
		obj31.value=ls_numexpper;
		
		obj32=eval("opener.document.form1."+ls_tipvivperdestino+"");
		obj32.value=ls_tipvivper;
		
		obj33=eval("opener.document.form1."+ls_tenvivperdestino+"");
		obj33.value=ls_tenvivper;
		
		obj34=eval("opener.document.form1."+ls_monpagvivperdestino+"");
		obj34.value=ls_monpagvivper;
				
		obj35=eval("opener.document.form1."+ls_cuecajahoperdestino+"");
		obj35.value=ls_cuecajahoper;
		
		if(ls_cajahoper=="1")
		{
			opener.document.form1.chkcajahoper.checked=true;		 
			opener.document.form1.txtporcajahoper.readOnly=false;
		}
		else
		{
			opener.document.form1.chkcajahoper.checked=false;
			opener.document.form1.txtporcajahoper.readOnly=true;
		}
		
		
		obj37=eval("opener.document.form1."+ls_cuelphperdestino+"");
		obj37.value=ls_cuelphper;
		
		obj38=eval("opener.document.form1."+ls_cuefidperdestino+"");
		obj38.value=ls_cuefidper;
		
		obj39=eval("opener.document.form1."+ls_fecingadmpubperdestino+"");
		obj39.value=ls_fecingadmpubper;
		
		obj40=eval("opener.document.form1."+ls_anoservpreperdestino+"");
		obj40.value=ls_anoservpreper;
		
		obj40=eval("opener.document.form1."+ls_anoservprecontdestino+"");
		obj40.value=ls_anoservprecont;	
				
		obj40=eval("opener.document.form1."+ls_anoservprefijodestino+"");
		obj40.value=ls_anoservprefijo;
		
		obj41=eval("opener.document.form1."+ls_fecingperdestino+"");
		obj41.value=ls_fecingper;
		
		obj42=eval("opener.document.form1."+ls_fecegrperdestino+"");
		obj42.value=ls_fecegrper;
		
		obj43=eval("opener.document.form1."+ls_cauegrperdestino+"");
		
		if (ls_cauegrper=="N")
		{
        	obj43.value="Ninguno";
		}
		else if (ls_cauegrper=="D")
		{
        	obj43.value="Despido";
		}
		else if (ls_cauegrper=="1")
		{
        	obj43.value="Despido 102";
		}
		else if (ls_cauegrper=="2")
		{
        	obj43.value="Despido 125";
		}
		else if (ls_cauegrper=="E")
		{
        	obj43.value="Detitución";
		}
        else if (ls_cauegrper=="P")
		{
        	obj43.value="Pensionado";
		}
		else if (ls_cauegrper=="R")
		{
        	obj43.value="Renuncia";
		}
		else if (ls_cauegrper=="T")
		{
        	obj43.value="Traslado";
		}
		else if (ls_cauegrper=="J")
		{
        	obj43.value="Julado";
		}
		else if (ls_cauegrper=="C")
		{
        	obj43.value="Terminación de Contrato";
		}
		else if (ls_cauegrper=="F")
		{
        	obj43.value="Fallecido";
		}
		else 
		{
        	obj43.value="N/A";
		}              
		
		obj44=eval("opener.document.form1."+ls_obsegrperdestino+"");
		obj44.value=ls_obsegrper;
		
		obj45=eval("opener.document.form1."+ls_codpainacdestino+"");
		obj45.value=ls_codpainac;
		
		obj46=eval("opener.document.form1."+ls_codtippersssdestino+"");
		obj46.value=ls_codtippersss;
		
	    obj47=eval("opener.document.form1."+ls_dentippersssdestino+"");
		obj47.value=ls_dentippersss;
		
	    obj48=eval("opener.document.form1."+ls_codunividestino+"");
		obj48.value=ls_codunivi;
		
		obj49=eval("opener.document.form1."+ls_denunividestino+"");
		obj49.value=ls_denunivi;
		
		obj50=eval("opener.document.form1."+ls_fecjubperdestino+"");
		obj50.value=ls_fecjubper;
		
		obj51=eval("opener.document.form1."+ls_fecreingperdestino+"");
		obj51.value=ls_fecreingper;
		
		obj52=eval("opener.document.form1."+ls_fecfeviddestino+"");
		obj52.value=ls_fecfevid;
		
		obj52=eval("opener.document.form1."+ls_enviorecdestino+"");
		obj52.value=ls_enviorec;
		
		obj53=eval("opener.document.form1."+ls_feclossfandestino+"");
		obj53.value=ls_feclossfan;
		
		obj54=eval("opener.document.form1."+ls_codcausadestino+"");
		obj54.value=ls_codcausa;
		
		obj55=eval("opener.document.form1."+ls_dencausadestino+"");
		obj55.value=ls_dencausa;
		
		obj56=eval("opener.document.form1."+ls_fecsitudestino+"");
		obj56.value=ls_fecsitu;
		
		obj57=eval("opener.document.form1."+ls_talcamperdestino+"");
		obj57.value=ls_talcamper;
		
		obj58=eval("opener.document.form1."+ls_talpanperdestino+"");
		obj58.value=ls_talpanper;
		
		obj59=eval("opener.document.form1."+ls_talzapperdestino+"");
		obj59.value=ls_talzapper;
		
		obj60=eval("opener.document.form1."+ls_situaciondestino+"");
		obj60.value=ls_situacion;
		
		obj61=eval("opener.document.form1."+ls_codorgdestino+"");
		obj61.value=ls_codorg;
		
		obj61=eval("opener.document.form1."+ls_desorgdestino+"");
		obj61.value=ls_desorg;
		
		obj62=eval("opener.document.form1."+ls_porcajahoperdestino+"");
		obj62.value=ls_porcajahoper;
        
		obj63=eval("opener.document.form1."+ls_coddestino+"");
		obj63.value=ls_codger;
		
		obj64=eval("opener.document.form1."+ls_dendestino+"");
		obj64.value=ls_denger;
		
		obj65=eval("opener.document.form1."+ls_anoperobrdestino+"");
		obj65.value=ls_anoperobr;
		
		obj66=eval("opener.document.form1."+ls_carantperdestino+"");
		obj66.value=ls_carantper;
				
	      ls_ejecucion = document.form1.hidstatus.value;
	      if(ls_ejecucion=="1")
			{
			 opener.document.form1.hidguardar.value = "modificar";
			opener.document.form1.hidstatus.value="C";
			}else{
			opener.document.form1.hidguardar.value = "insertar";	
			opener.document.form1.hidstatus.value="";
					
			}
			
			ue_nuevo_codestudio2();
			ue_nuevo_trabajo2();
			ue_nuevo_beneficiario2();
			ue_nuevo_permiso2();
			ue_nuevo_premio2();
			ue_buscar_uniadm_actual2();
			ue_buscar_cargo_actual2();
			
			opener.document.form1.txtcodper.readOnly=true;
			opener.document.form1.txtcedper.readOnly=true;
			opener.document.form1.hidcodest.value=ls_codest;
			opener.document.form1.hidcodpar.value=ls_codpar;
			opener.document.form1.hidcodmun.value=ls_codmun;
			opener.document.form1.hidcodestnac.value=ls_codestnac;
		
			ue_cambiopais2 (ls_codpai, ls_codest, ls_codmun);	
			ue_cambiopaisnac (ls_codpainac);
			
			
		/*	ClosingVar =true
			window.onbeforeunload = ExitCheck;
			function ExitCheck()
			{  
			///control de cerrar la ventana///
			 if(ClosingVar == true) 
			  { ExitCheck = false
				return "Si pulsa aceptar continuar,abandonará la página pudiendo no cargar todos lo datos del personal.";
			  }
			  else
			  {
			    close();
			  }
			}*/
						
			
	   	   setTimeout(close,7500);
}
	


function aceptar_personal3 (ls_codper,  ls_codperdestino,   ls_ejecucion)
	{
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	
    close();
	   
}
	

		
function aceptar_persona_grid (ls_codper, ls_nomper, ls_apeper,ls_cargo, ls_codperdestino, ls_nomperdestino, ls_cargodestino,ls_uniadm,ls_uniadmdestino)
	{
		num=opener.document.form1.totalfilas.value;
	   obj1=eval("opener.document.form1."+ls_codperdestino+num+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+num+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+num+"");
	   obj2.value=ls_nomper;}	   
	    obj3=eval("opener.document.form1."+ls_cargodestino+num+"");
	    obj3.value=ls_cargo;
		obj4=eval("opener.document.form1."+ls_uniadmdestino+num+"");
	    obj4.value=ls_uniadm;
	  close();
	   
}


function aceptar_persona_cargo (ls_codper, ls_nomper, ls_apeper,ls_cargo, ls_codperdestino, ls_nomperdestino, ls_cargodestino, ls_ejecucion)
	{
		
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}	   
	    obj3=eval("opener.document.form1."+ls_cargodestino+"");
	    obj3.value=ls_cargo;
	  close();
	   
}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
		
function aceptar_personal_ascenso(ls_codper, ls_nomper, ls_apeper, ls_fecha,ls_cargo, ls_codperdestino, ls_nomperdestino, ls_fechadestino,ls_cargodestino, ls_ejecucion)
	{
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}	
	  
	  obj3=eval("opener.document.form1."+ls_fechadestino+"");
	  obj3.value=ls_fecha;	
	  
	  obj4=eval("opener.document.form1."+ls_cargodestino+"");
	  obj4.value=ls_cargo;	  
    close();	   
}

function aceptar_tutor (ls_codper, ls_nomper, ls_apeper, ls_codperdestino, ls_nomperdestino)
	{
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}	
	  
    close();	   
}

function aceptar_persona_nivaca (ls_codper,ls_nomper, ls_apeper,ls_cargo,ls_codperdestino,ls_nomperdestino,ls_cargodestino,ls_nivaca,ls_nivacadestino)
	{
		
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}	   
	   
	   obj3=eval("opener.document.form1."+ls_cargodestino+"");
	   obj3.value=ls_cargo;
	   
	   obj4=eval("opener.document.form1."+ls_nivacadestino+"");
	   obj4.value=ls_nivaca;
	  close();
	   
}

function aceptar_persona_movimiento (ls_codper,ls_nomper, ls_apeper,ls_cargo,ls_uniadm,ls_sueact, ls_codperdestino,ls_nomperdestino, ls_caractdestino, ls_uniadmdestino, ls_sueactdestino, ls_codcar, ls_codcardestino, ls_coduniadm, ls_coduniadmdestino,ls_codnom,ls_codnomdestino, ls_paso,ls_pasodestino,ls_grado,ls_gradodestino )
{
	obj1=eval("opener.document.form1."+ls_codperdestino+"");
	obj1.value=ls_codper;
			
	 if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}	   
	   
	   obj3=eval("opener.document.form1."+ls_caractdestino+"");
	   obj3.value=ls_cargo;
	   
	   obj4=eval("opener.document.form1."+ls_uniadmdestino+"");
	   obj4.value=ls_uniadm;
	   
	   obj5=eval("opener.document.form1."+ls_sueactdestino+"");
	   obj5.value=uf_convertir (ls_sueact);
	   
	   obj6=eval("opener.document.form1."+ls_codcardestino+"");
	   obj6.value=ls_codcar;
	   
	   obj7=eval("opener.document.form1."+ls_coduniadmdestino+"");
	   obj7.value=ls_coduniadm;
	   
	   obj8=eval("opener.document.form1."+ls_codnomdestino+"");
	   obj8.value=ls_codnom;
	   
	   obj9=eval("opener.document.form1."+ls_pasodestino+"");
	   obj9.value=ls_paso;
	   
	   obj10=eval("opener.document.form1."+ls_gradodestino+"");
	   obj10.value=ls_grado;
	   
	  close();

}
	
function aceptar_persona_contrato (ls_codper, ls_nomper, ls_apeper, ls_codperdestino, ls_nomperdestino,ls_apeperdestino,ls_codpro,ls_codprodestino,ls_despro,ls_desprodestino,ls_nacper,ls_nacperdestino)
	{
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper; 
	   
	   obj2=eval("opener.document.form1."+ls_apeperdestino+"");
	   obj2.value=ls_apeper;
	   
	   obj3=eval("opener.document.form1."+ls_codprodestino+"");
	   obj3.value=ls_codpro;
	   
	   obj4=eval("opener.document.form1."+ls_desprodestino+"");
	   obj4.value=ls_despro;
	   
	   obj5=eval("opener.document.form1."+ls_nacperdestino+"");
	   obj5.value=ls_nacper;
	   
	   opener.document.form1.txtcodper.readOnly=true;
	   opener.document.form1.txtnomper.readOnly=true;
	   opener.document.form1.txtapeper.readOnly=true;
	   opener.document.form1.cmbnacper.disabled="disabled";
    close();	   
}

function aceptar_bono_merito(ls_codper,	ls_nomper, ls_apeper,ls_codperdestino,  ls_nomperdestino, ls_dentippersss,ls_tipperdestino)
	{
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}
	   
	  obj3=eval("opener.document.form1."+ls_tipperdestino+"");
	  obj3.value=ls_dentippersss;
	  
    close();	   
}



function aceptar_personal_concurso (ls_cedper, ls_nomper, ls_apeper,	ls_dirper,	ls_fecnacper, 	ls_edocivper, 	ls_nacper, ls_codpai,	ls_codest,	 ls_telhabper, 	ls_coreleper,ls_telmovper, ls_sexper,  ls_codpro,ls_despro, ls_nivacaper,	 ls_cedperdestino, ls_nomperdestino, ls_apeperdestino,	ls_dirperdestino,	ls_fecnacperdestino, ls_edocivperdestino, ls_nacperdestino, ls_codpaidestino,	ls_codestdestino, ls_telhabperdestino, 	ls_coreleperdestino,	ls_telmovperdestino, ls_sexperdestino,ls_codprodestino, ls_desprodestino, ls_nivacaperdestino)
	{
		
		obj1=eval("opener.document.form1."+ls_cedperdestino+"");
		obj1.value=ls_cedper;
		obj1.readOnly=true;
		
		obj2=eval("opener.document.form1."+ls_apeperdestino+"");
		obj2.value=ls_apeper;
		obj2.readOnly=true;
		
		obj3=eval("opener.document.form1."+ls_nomperdestino+"");
		obj3.value=ls_nomper;
		obj3.readOnly=true;
		
		obj4=eval("opener.document.form1."+ls_dirperdestino+"");
		obj4.value=ls_dirper;
				
		obj5=eval("opener.document.form1."+ls_fecnacperdestino+"");
		obj5.value=ls_fecnacper;
		obj5.readOnly=true;
		
		obj6=eval("opener.document.form1."+ls_edocivperdestino+"");
		obj6.value=ls_edocivper;
		
		obj7=eval("opener.document.form1."+ls_nacperdestino+"");
		obj7.value=ls_nacper;
		
		obj8=eval("opener.document.form1."+ls_codpaidestino+"");
		obj8.value=ls_codpai;
		
		obj9=eval("opener.document.form1."+ls_telhabperdestino+"");
		obj9.value=ls_telhabper;
		
		
		obj10=eval("opener.document.form1."+ls_coreleperdestino+"");
		obj10.value=ls_coreleper;
				
		obj11=eval("opener.document.form1."+ls_telmovperdestino+"");
		obj11.value=ls_telmovper;
		
		
		obj12=eval("opener.document.form1."+ls_sexperdestino+"");
		obj12.value=ls_sexper;
		
		obj13=eval("opener.document.form1."+ls_codestdestino+"");
		obj13.value=ls_codest;
		
		obj14=eval("opener.document.form1."+ls_nivacaperdestino+"");
		obj14.value=ls_nivacaper;
		
		obj15=eval("opener.document.form1."+ls_codprodestino+"");
		obj15.value=ls_codpro;
		
		obj16=eval("opener.document.form1."+ls_desprodestino+"");
		obj16.value=ls_despro;

		ue_cambiopaisnac (ls_codpai);
		opener.document.form1.txttipper.value='I';	
	    setTimeout(close,1000);
}
	


		
function aceptar_personal2 (ls_codper, ls_nomper, ls_apeper,ls_cargo, ls_codperdestino, ls_nomperdestino,ls_cargodestino,ls_ejecucion)
	{
	   obj1=eval("opener.document.form1."+ls_codperdestino+"");
	   obj1.value=ls_codper;
			
	  if (ls_apeper!='0'){
  	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
 	   obj2.value=ls_nomper+" "+ls_apeper; }
	 else {
	   obj2=eval("opener.document.form1."+ls_nomperdestino+"");
	   obj2.value=ls_nomper;}	
	  
	   obj3=eval("opener.document.form1."+ls_cargodestino+"");
	   obj3.value=ls_cargo;
	  
    close();	   
}








