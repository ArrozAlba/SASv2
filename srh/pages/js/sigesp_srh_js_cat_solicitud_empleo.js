
var url= "../../php/sigesp_srh_a_solicitud_empleo.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_solicitud_empleo.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_soliciutd_empleo.php";
var mygrid;
var timeoutHandler;//update will be sent automatically to server if row editting was stoped;
var rowUpdater;//async. Calls doUpdateRow function when got data from server
var rowEraser;//async. Calls doDeleteRow function when got confirmation about row deletion
var authorsLoader;//sync. Loads list of available authors from server to populate dropdown (co)
var mandFields = [0,1,1,0,0];
		
		
	//initialise (from xml) and populate (from xml) grid
function doOnLoad()
		{
			
            tipo=opener.document.form1.txttipo.value;
			hidtipo=document.form1.hidtipo.value;//// agregado 28/02/2008
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Numero,Fecha,Cedula,Nombre y Apellido");
			mygrid.setInitWidths("90,100,100,210");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
		//	mygrid.loadXML(loadDataURL+"&txttipo="+tipo+"&hidtipo="+hidtipo);
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

	
	
 	 nrosol=document.form1.txtnrosol.value;
	 cedper=document.form1.txtcedper.value;
	 nomper=document.form1.txtnomper.value;
	 apeper=document.form1.txtapeper.value;
	 tipo=opener.document.form1.txttipo.value;
	 hidtipo=document.form1.hidtipo.value;//// agregado 28/02/2008
	 fecsoldes=document.form1.txtfecsoldes.value;
	 fecsolhas=document.form1.txtfecsolhas.value;
	 valfec= ue_comparar_fechas(fecsoldes,fecsolhas);
	 
	 if (!valfec)
	 {
		alert ('Rango de Fecha Invalido.');	 
	 }
	 else
	 {
	
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_solicitud_empleo.php?valor=buscar"+"&txtnrosol="+nrosol+"&txtfecsoldes="+fecsoldes+"&txtfecsolhas="+fecsolhas+"&txtcedper="+cedper+"&txtnomper="+nomper+"&txttipo="+tipo+"&hidtipo="+hidtipo+"&txtapeper="+apeper);
		 setTimeout (terminar_buscar,650);
	 }

}



 
		
	
function Limpiar_busqueda () 
{
	$('txtnrosol').value="";
	$('txtcedper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	$('txtfecsoldes').value=$('txtfecsoldes2').value;
	$('txtfecsolhas').value=$('txtfecsolhas2').value;

}
	
	
//FUNCIONES PARA INICIALIZAR LOS COMBOS DE ESTADO MUNICIPIO Y PARROQUIA AL TRAER LOS DATOS DEL CATALOGO


function LimpiarCambioPais2()
{
  removeAllOptions(opener.document.form1.cmbcodest);
  opener.document.form1.cmbcodest.selectedIndex = 0;
  LimpiarCambioEstado2();
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


function ue_cambiopais2(ls_codpai,ls_denest,ls_denmun)
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
    	ue_cambioestado2 (ls_codpai,ls_denest,ls_denmun);
	  }
      if (trim(opener.document.form1.hidcodestnac.value) != "")
	  {
        for (j=0; j<estados.codest.length; j++)
	    {
		opener.document.form1.cmbcodestnac.options[opener.document.form1.cmbcodestnac.options.length] = new Option(estados.desest[j],estados.codest[j]);
		}
		opener.document.form1.cmbcodestnac.value = opener.document.form1.hidcodestnac.value;
		//opener.document.form1.hidcodestnac.value = "";
        //alert(opener.document.form1.cmbcodestnac.value);
	  }
	}
		
    params = "operacion=ue_inicializarestado&codpai="+ls_codpai;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioPais});
  }  
}

function ue_cambioestado2 (ls_codpai,ls_denest,ls_denmun)
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
		ue_cambiomunicipio2 (ls_codpai,ls_denest,ls_denmun);
	  }
    }
		
    params = "operacion=ue_inicializarmunicipio&codpai="+ls_codpai+"&codest="+ls_denest;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioEstado});
  }  
} 

function ue_cambiomunicipio2 (ls_codpai,ls_denest,ls_denmun)
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
	  if ((opener.document.form1.hidcodpar.value) != "")
	  {
		alert ("Datos Cargados Satisfactoriamente!!");
		opener.document.form1.cmbcodpar.value = opener.document.form1.hidcodpar.value;
		opener.document.form1.hidcodpar.value = "";
		ocultar_mensaje("mensaje");
		if (opener.document.form1.hidguardar.value == "modificar")
		{ try{catalogo.close();}catch(e){}}
	  }
    }
    params = "operacion=ue_inicializarparroquia&codpai="+ls_codpai+"&codest="+ls_denest+"&codmun="+ls_denmun;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioMunicipio});  
  }  
}

function aceptar (ls_nrosol, ls_cedper, ls_fecsol, ls_apeper, ls_nomper, ls_sexsol, ls_fecna,ls_telhab, ls_email,ls_codpro, ls_carfam, ls_codpar,ls_dirper, ls_comsol, ls_codniv, ls_telmov, ls_estciv,  ls_nrodestino, ls_ceddestino, ls_fecsoldestino, ls_apedestino, ls_fecnacperdestino, ls_nomdestino, ls_sexdestino, ls_telhdestino,ls_emadestino, ls_codprodestino, ls_cardestino, ls_codpardestino, ls_dirdestino, ls_comdestino, ls_codnivdestino,   ls_telmdestino, ls_fecsoldestino, ls_estdestino, ls_dennivdestino, ls_denniv,  ls_codmundestino, ls_denmun, ls_codestdestino,ls_denest,ls_denprodestino, ls_denpro, ls_codpai, ls_nivaca, ls_pesper, ls_estaper, ls_nacper, ls_codpaidestino, ls_nivacadestino, ls_pesperdestino, ls_estaperdestino, ls_nacperdestino)
	{
		if (opener.document.form1.hidcontrol3.value=="3") 
		{ 
		   obj1=eval("opener.document.form1.txtcodper");
		   obj1.value=ls_cedper;
			
			obj2=eval("opener.document.form1.txtnomper");
			obj2.value=ls_nomper+' '+ls_apeper;
			
			close();
		}
		else if (opener.document.form1.hidcontrol.value=="1") 
		{
			obj1=eval("opener.document.form1."+ls_ceddestino+"");
		    obj1.value=ls_cedper;
			
			obj3=eval("opener.document.form1."+ls_apedestino+"");
			obj3.value=ls_apeper;
			
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper;
			
			obj5=eval("opener.document.form1."+ls_sexdestino+"");
			obj5.value=ls_sexsol;
			
			obj6=eval("opener.document.form1."+ls_fecnacperdestino+"");
		    obj6.value=ls_fecna;
			
			obj7=eval("opener.document.form1."+ls_telhdestino+"");
			obj7.value=ls_telhab;
			
			obj8=eval("opener.document.form1."+ls_emadestino+"");
			obj8.value=ls_email;
			
			obj9=eval("opener.document.form1."+ls_codprodestino+"");
			obj9.value=ls_codpro;
			
			obj16=eval("opener.document.form1."+ls_denprodestino+"");
			obj16.value=ls_denpro;
			
			obj14=eval("opener.document.form1."+ls_telmdestino+"");
			obj14.value=ls_telmov;
			
			obj15=eval("opener.document.form1."+ls_estdestino+"");
			obj15.value=ls_estciv;
			
			obj11=eval("opener.document.form1."+ls_dirdestino+"");
			obj11.value=ls_dirper;
			
			obj17=eval("opener.document.form1."+ls_codpaidestino+"");
		    obj17.value=ls_codpai;
				
			obj19=eval("opener.document.form1."+ls_pesperdestino+"");
			obj19.value=ls_pesper;
			
			obj20=eval("opener.document.form1."+ls_estaperdestino+"");
			obj20.value=ls_estaper;
			
			obj21=eval("opener.document.form1."+ls_nivacadestino+"");
			obj21.value=ls_nivaca;
			
			obj22=eval("opener.document.form1."+ls_nacperdestino+"");
			obj22.value=ls_nacper;
			
			 opener.document.form1.hidcodest.value=ls_denest;
			opener.document.form1.hidcodpar.value=ls_codpar;
			opener.document.form1.hidcodmun.value=ls_denmun;
			ue_cambiopais2 (ls_codpai, ls_denest, ls_denmun);
			setTimeout(close,1500);
				
		}
	   else if (opener.document.form1.hidcontrol.value=="2")
	   {
		   
		   num=opener.document.form1.totalfilas2.value;
			obj1=eval("opener.document.form1."+ls_ceddestino+num+"");
		    obj1.value=ls_cedper;
			
			obj2=eval("opener.document.form1.txtnomsol"+num+"");
			obj2.value=ls_nomper+' '+ls_apeper;
			
			obj3=eval("opener.document.form1."+ls_denprodestino+num+"");
			obj3.value=ls_denpro;
			
			obj4=eval("opener.document.form1."+ls_dennivdestino+num+"");
   	  	    obj4.value=ls_denniv;
			 
			close ();
			
			}
			
		else if (opener.document.form1.hidcontrol.value=="3")
		{
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nrosol;
		
		obj1=eval("opener.document.form1."+ls_ceddestino+"");
		obj1.value=ls_cedper;
		
		obj2=eval("opener.document.form1."+ls_fecsoldestino+"");
		obj2.value=ls_fecsol;
		
		obj3=eval("opener.document.form1."+ls_apedestino+"");
		obj3.value=ls_apeper;
		
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nomper;
		
		obj5=eval("opener.document.form1."+ls_sexdestino+"");
		obj5.value=ls_sexsol;
		
		obj6=eval("opener.document.form1."+ls_fecnacperdestino+"");
		obj6.value=ls_fecna;
		
		obj7=eval("opener.document.form1."+ls_telhdestino+"");
		obj7.value=ls_telhab;
		
		obj8=eval("opener.document.form1."+ls_emadestino+"");
		obj8.value=ls_email;
		
		obj9=eval("opener.document.form1."+ls_codprodestino+"");
		obj9.value=ls_codpro;
		
		obj10=eval("opener.document.form1."+ls_cardestino+"");
		obj10.value=ls_carfam;
		
		obj11=eval("opener.document.form1."+ls_dirdestino+"");
		obj11.value=ls_dirper;
		
		obj12=eval("opener.document.form1."+ls_comdestino+"");
		obj12.value=ls_comsol;
		
		obj13=eval("opener.document.form1."+ls_codnivdestino+"");
		obj13.value=ls_codniv;
		
		obj14=eval("opener.document.form1."+ls_telmdestino+"");
		obj14.value=ls_telmov;
		
		obj15=eval("opener.document.form1."+ls_estdestino+"");
		obj15.value=ls_estciv;
		
		obj16=eval("opener.document.form1."+ls_denprodestino+"");
		obj16.value=ls_denpro;
		
	
		obj17=eval("opener.document.form1."+ls_codpaidestino+"");
		obj17.value=ls_codpai;
		
		obj18=eval("opener.document.form1."+ls_dennivdestino+"");
		obj18.value=ls_denniv;
		
		obj19=eval("opener.document.form1."+ls_pesperdestino+"");
		obj19.value=ls_pesper;
		
		obj20=eval("opener.document.form1."+ls_estaperdestino+"");
		obj20.value=ls_estaper;
		
		obj21=eval("opener.document.form1."+ls_nivacadestino+"");
		obj21.value=ls_nivaca;
		
		obj22=eval("opener.document.form1."+ls_nacperdestino+"");
		obj22.value=ls_nacper;
	
	    
		
		ls_ejecucion = document.form1.hidstatus.value;
		
		if(ls_ejecucion=="1")
			{
 			  opener.document.form1.hidguardar.value = "modificar";
 			  opener.document.form1.hidstatus.value="C";		
			}else{
  		     opener.document.form1.hidguardar.value = "insertar";	
			 opener.document.form1.hidstatus.value="";
			}
		    opener.document.form1.txtnrosol.readOnly=true;
			opener.document.form1.txtcedper.readOnly=true;
			//opener.document.getElementById("divarea").style.display="block";
		/*   opener.document.form1.operacion.value="BUSCARDETALLE";
		   opener.document.form1.action="../pantallas/sigesp_srh_p_solicitud_empleo.php";
		   opener.document.form1.existe.value="TRUE";			
		   opener.document.form1.submit();	*/
		   
		    opener.document.form1.hidcodest.value=ls_denest;
			opener.document.form1.hidcodpar.value=ls_codpar;
			opener.document.form1.hidcodmun.value=ls_denmun;
			ue_cambiopais2 (ls_codpai, ls_denest, ls_denmun);
			setTimeout(close,1400);
		 		
		}
		else if (opener.document.form1.hidcontrol.value=="4") 
		{ 
		   obj1=eval("opener.document.form1.txtcodper");
		   obj1.value=ls_cedper;
			
		   obj2=eval("opener.document.form1.txtnomper");
		   obj2.value=ls_nomper;
			
		   obj2=eval("opener.document.form1.txtapeper");
		   obj2.value=ls_apeper;
		   
		   obj3=eval("opener.document.form1.txtcodpro");
		   obj3.value=ls_codpro;
	   
	   		obj4=eval("opener.document.form1.txtdespro");
	   		obj4.value=ls_denpro;
	   
		   obj5=eval("opener.document.form1.cmbnacper");
	   		obj5.value=ls_nacper;
		   
		   opener.document.form1.txtcodper.readOnly=true;
	  	   opener.document.form1.txtnomper.readOnly=true;
	   	   opener.document.form1.txtapeper.readOnly=true;
		   opener.document.form1.cmbnacper.disabled="disabled";
			
			close();
		}  
	   
		
	    
}
function aceptar2 (ls_nrosol, ls_cedper, ls_fecsol, ls_apeper, ls_nomper, ls_sexsol, ls_fecna,ls_telhab, ls_email,ls_codpro, ls_carfam, ls_codpar,ls_dirper, ls_comsol, ls_codniv, ls_telmov, ls_estciv,  ls_nrodestino, ls_ceddestino, ls_fecsoldestino, ls_apedestino, ls_fecnacperdestino, ls_nomdestino, ls_sexdestino, ls_telhdestino,ls_emadestino, ls_codprodestino, ls_cardestino, ls_codpardestino, ls_dirdestino, ls_comdestino, ls_codnivdestino,   ls_telmdestino, ls_fecsoldestino, ls_estdestino, ls_dennivdestino, ls_denniv,  ls_codmundestino, ls_denmun, ls_codestdestino,ls_denest,ls_denprodestino, ls_denpro, ls_codpai, ls_nivaca, ls_pesper, ls_estaper, ls_nacper, ls_codpaidestino, ls_nivacadestino, ls_pesperdestino, ls_estaperdestino, ls_nacperdestino, ls_codpainac,ls_codestnac)
	{
		if (opener.document.form1.hidcontrol3.value=="3") 
		{ 
		   obj1=eval("opener.document.form1.txtcodper");
		   obj1.value=ls_cedper;
			
			obj2=eval("opener.document.form1.txtnomper");
			obj2.value=ls_nomper+' '+ls_apeper;
			
			close();
		}
		else if (opener.document.form1.hidcontrol.value=="1") 
		{
			obj1=eval("opener.document.form1."+ls_ceddestino+"");
		    obj1.value=ls_cedper;
			
			obj3=eval("opener.document.form1."+ls_apedestino+"");
			obj3.value=ls_apeper;
			
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper;
			
			obj5=eval("opener.document.form1."+ls_sexdestino+"");
			obj5.value=ls_sexsol;
			
			obj6=eval("opener.document.form1."+ls_fecnacperdestino+"");
		    obj6.value=ls_fecna;
			
			obj7=eval("opener.document.form1."+ls_telhdestino+"");
			obj7.value=ls_telhab;
			
			obj8=eval("opener.document.form1."+ls_emadestino+"");
			obj8.value=ls_email;
			
			obj9=eval("opener.document.form1."+ls_codprodestino+"");
			obj9.value=ls_codpro;
			
			obj16=eval("opener.document.form1."+ls_denprodestino+"");
			obj16.value=ls_denpro;
			
			obj14=eval("opener.document.form1."+ls_telmdestino+"");
			obj14.value=ls_telmov;
			
			obj15=eval("opener.document.form1."+ls_estdestino+"");
			obj15.value=ls_estciv;
			
			obj11=eval("opener.document.form1."+ls_dirdestino+"");
			obj11.value=ls_dirper;
			
			obj17=eval("opener.document.form1."+ls_codpaidestino+"");
		    obj17.value=ls_codpai;
				
			obj19=eval("opener.document.form1."+ls_pesperdestino+"");
			obj19.value=ls_pesper;
			
			obj20=eval("opener.document.form1."+ls_estaperdestino+"");
			obj20.value=ls_estaper;
			
			obj21=eval("opener.document.form1."+ls_nivacadestino+"");
			obj21.value=ls_nivaca;
			
			obj22=eval("opener.document.form1."+ls_nacperdestino+"");
			obj22.value=ls_nacper;
			
			obj23=eval("opener.document.form1."+ls_codpainac+"");
		    obj23.value=ls_codpai;
			
			/*obj24=eval("opener.document.form1."+ls_codestnac+"");
		    obj24.value=ls_denest;*/
			
			opener.document.form1.hidcodest.value=ls_denest;
			opener.document.form1.hidcodpar.value=ls_codpar;
			opener.document.form1.hidcodmun.value=ls_denmun;
			opener.document.form1.hidcodestnac.value=ls_denest;
			//alert(opener.document.form1.hidcodestnac.value);
			ue_cambiopais2 (ls_codpai, ls_denest, ls_denmun);
			setTimeout(close,1500);
				
		}
	   else if (opener.document.form1.hidcontrol.value=="2")
	   {
		   
		   num=opener.document.form1.totalfilas2.value;
			obj1=eval("opener.document.form1."+ls_ceddestino+num+"");
		    obj1.value=ls_cedper;
			
			obj2=eval("opener.document.form1.txtnomsol"+num+"");
			obj2.value=ls_nomper+' '+ls_apeper;
			
			obj3=eval("opener.document.form1."+ls_denprodestino+num+"");
			obj3.value=ls_denpro;
			
			obj4=eval("opener.document.form1."+ls_dennivdestino+num+"");
   	  	    obj4.value=ls_denniv;
			 
			close ();
			
			}
			
		else if (opener.document.form1.hidcontrol.value=="3")
		{
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nrosol;
		
		obj1=eval("opener.document.form1."+ls_ceddestino+"");
		obj1.value=ls_cedper;
		
		obj2=eval("opener.document.form1."+ls_fecsoldestino+"");
		obj2.value=ls_fecsol;
		
		obj3=eval("opener.document.form1."+ls_apedestino+"");
		obj3.value=ls_apeper;
		
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nomper;
		
		obj5=eval("opener.document.form1."+ls_sexdestino+"");
		obj5.value=ls_sexsol;
		
		obj6=eval("opener.document.form1."+ls_fecnacperdestino+"");
		obj6.value=ls_fecna;
		
		obj7=eval("opener.document.form1."+ls_telhdestino+"");
		obj7.value=ls_telhab;
		
		obj8=eval("opener.document.form1."+ls_emadestino+"");
		obj8.value=ls_email;
		
		obj9=eval("opener.document.form1."+ls_codprodestino+"");
		obj9.value=ls_codpro;
		
		obj10=eval("opener.document.form1."+ls_cardestino+"");
		obj10.value=ls_carfam;
		
		obj11=eval("opener.document.form1."+ls_dirdestino+"");
		obj11.value=ls_dirper;
		
		obj12=eval("opener.document.form1."+ls_comdestino+"");
		obj12.value=ls_comsol;
		
		obj13=eval("opener.document.form1."+ls_codnivdestino+"");
		obj13.value=ls_codniv;
		
		obj14=eval("opener.document.form1."+ls_telmdestino+"");
		obj14.value=ls_telmov;
		
		obj15=eval("opener.document.form1."+ls_estdestino+"");
		obj15.value=ls_estciv;
		
		obj16=eval("opener.document.form1."+ls_denprodestino+"");
		obj16.value=ls_denpro;
		
		obj17=eval("opener.document.form1."+ls_codpaidestino+"");
		obj17.value=ls_codpai;
		
		obj18=eval("opener.document.form1."+ls_dennivdestino+"");
		obj18.value=ls_denniv;
		
		obj19=eval("opener.document.form1."+ls_pesperdestino+"");
		obj19.value=ls_pesper;
		
		obj20=eval("opener.document.form1."+ls_estaperdestino+"");
		obj20.value=ls_estaper;
		
		obj21=eval("opener.document.form1."+ls_nivacadestino+"");
		obj21.value=ls_nivaca;
		
		obj22=eval("opener.document.form1."+ls_nacperdestino+"");
		obj22.value=ls_nacper;
		
		obj23=eval("opener.document.form1."+ls_codpainac+"");
		obj23.value=ls_codpai;
		
		/*obj24=eval("opener.document.form1."+ls_codestnac+"");
		obj24.value=ls_denest;*/
			
	    ls_ejecucion = document.form1.hidstatus.value;
		
		if(ls_ejecucion=="1")
			{
 			  opener.document.form1.hidguardar.value = "modificar";
 			  opener.document.form1.hidstatus.value="C";		
			}else{
  		     opener.document.form1.hidguardar.value = "insertar";	
			 opener.document.form1.hidstatus.value="";
			}
		    opener.document.form1.txtnrosol.readOnly=true;
			opener.document.form1.txtcedper.readOnly=true;
			//opener.document.getElementById("divarea").style.display="block";
		/*   opener.document.form1.operacion.value="BUSCARDETALLE";
		   opener.document.form1.action="../pantallas/sigesp_srh_p_solicitud_empleo.php";
		   opener.document.form1.existe.value="TRUE";			
		   opener.document.form1.submit();	*/
		   
		    opener.document.form1.hidcodest.value=ls_denest;
			opener.document.form1.hidcodpar.value=ls_codpar;
			opener.document.form1.hidcodmun.value=ls_denmun;
			opener.document.form1.hidcodestnac.value=ls_denest;
			ue_cambiopais2 (ls_codpai, ls_denest, ls_denmun);
			setTimeout(close,1500);
		 		
		}
		else if (opener.document.form1.hidcontrol.value=="4") 
		{ 
		   obj1=eval("opener.document.form1.txtcodper");
		   obj1.value=ls_cedper;
			
		   obj2=eval("opener.document.form1.txtnomper");
		   obj2.value=ls_nomper;
			
		   obj2=eval("opener.document.form1.txtapeper");
		   obj2.value=ls_apeper;
		   
		   obj3=eval("opener.document.form1.txtcodpro");
		   obj3.value=ls_codpro;
	   
	   		obj4=eval("opener.document.form1.txtdespro");
	   		obj4.value=ls_denpro;
	   
		   obj5=eval("opener.document.form1.cmbnacper");
	   		obj5.value=ls_nacper;
		   
		   opener.document.form1.txtcodper.readOnly=true;
	  	   opener.document.form1.txtnomper.readOnly=true;
	   	   opener.document.form1.txtapeper.readOnly=true;
		   opener.document.form1.cmbnacper.disabled="disabled";
			
			close();
		}  
	   
		
	    
}

function aceptardesde (ls_nrosol,ls_nrodestino) {

obj=eval("opener.document.form1."+ls_nrodestino+"");
obj.value=ls_nrosol;
close ();

}	
function aceptarhasta(ls_nrosol,ls_nrohasta) {

obj=eval("opener.document.form1."+ls_nrohasta+"");
obj.value=ls_nrosol;  
close ();

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
		
