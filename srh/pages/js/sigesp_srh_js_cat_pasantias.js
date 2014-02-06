
var url= "../../php/sigesp_srh_a_pasantias.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_pasantias.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_pasantias.php";
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
			mygrid.setHeader("Numero,Fecha Inic.,Cedula,Nombre y Apellido");
			mygrid.setInitWidths("90,100,100,210");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
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

	 nropas=document.form1.txtnropas.value;
	 cedpas=document.form1.txtcedpas.value;
	 nompas=document.form1.txtnompas.value;
	 apepas=document.form1.txtapepas.value;
	 fecinides=document.form1.txtfecinides.value;
	 fecinihas=document.form1.txtfecinihas.value;
	 valfec= ue_comparar_fechas(fecinides,fecinihas);
	 
 	 if (!valfec)
	 {
		alert ('Rango de Fecha Invalido.');		 
	 }
	 else
	 {
	 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_pasantias.php?valor=buscar"+"&txtnropas="+nropas+"&txtfecinides="+fecinides+"&txtfecinihas="+fecinihas+"&txtcedpas="+cedpas+"&txtnompas="+nompas+"&txtapepas="+apepas);
		  setTimeout (terminar_buscar,650);
	 }

}
	
	
//FUNCIONES PARA INICIALIZAR LOS COMBOS DE MUNICIPIO Y PARROQUIA AL TRAER LOS DATOS DEL CATALOGO

function LimpiarCambioEstado2()
{
  removeAllOptions(opener.document.form1.combomun);	
  opener.document.form1.combomun.selectedIndex = 0;
  LimpiarCambioMunicipio2();
}


function LimpiarCambioMunicipio2()
{
  removeAllOptions(opener.document.form1.combopar);
  opener.document.form1.combopar.selectedIndex = 0;
}



function ue_cambioestado2 (ls_denest, ls_denmun)
{
  LimpiarCambioEstado2();
  if (ue_valida_null(opener.document.form1.comboest))
  {
    function onCambioEstado(respuesta)
    {
	  var municipios = JSON.parse(respuesta.responseText);
	  for (j=0; j<municipios.codmun.length; j++)
	  {opener.document.form1.combomun.options[opener.document.form1.combomun.options.length] = new Option(municipios.denmun[j],municipios.codmun[j]);}
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim(opener.document.form1.hidcodmun.value) != "")
	  {
		opener.document.form1.combomun.value = opener.document.form1.hidcodmun.value;
		opener.document.form1.hidcodmun.value = "";
		ue_cambiomunicipio2 (ls_denest, ls_denmun);
	  }
    }
		
    params = "operacion=ue_cambioestado&codpai="+'058'+"&codest="+ls_denest;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioEstado});
  }  
}


function ue_cambiomunicipio2 (ls_denest, ls_denmun)
{
  LimpiarCambioMunicipio2();
  if (ue_valida_null(opener.document.form1.combomun))
  {
    function onCambioMunicipio(respuesta)
    {
	  var parroquias = JSON.parse(respuesta.responseText);
	  for (i=0; i<parroquias.codpar.length; i++)
	  {opener.document.form1.combopar.options[opener.document.form1.combopar.options.length] = new Option(parroquias.denpar[i],parroquias.codpar[i]);}	  
	  //El siguiente if es usado cuando viene del catalogo	  
	  if (trim(opener.document.form1.hidcodpar.value) != "")
	  {
		opener.document.form1.combopar.value = opener.document.form1.hidcodpar.value;
		opener.document.form1.hidcodpar.value = "";
		ocultar_mensaje("mensaje");
		if (opener.document.form1.hidguardar.value == "modificar")
		{ try{catalogo.close();}catch(e){}}
	  }
    }
    params = "operacion=ue_cambiomunicipio&codpai="+'058'+"&codest="+ls_denest+"&codmun="+ls_denmun;
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onCambioMunicipio});  
  }  
}




		
function aceptar (ls_nropas, ls_cedpas, ls_fecini, ls_apepas, ls_nompas, ls_sexpas, ls_fecna, ls_telhab, ls_email, ls_codpar, ls_dirpas ,    ls_telmov ,  ls_edociv, ls_tutor, ls_univ, ls_carre ,   ls_nrodestino ,  ls_ceddestino ,  ls_fecinidestino ,  ls_apedestino ,  ls_fecnacdestino ,  ls_nomdestino ,  ls_sexdestino ,  ls_telhdestino , ls_emadestino ,  ls_codpardestino ,  ls_dirdestino ,   ls_telmdestino ,  ls_fecinidestino ,  ls_estdestino ,  ls_codmundestino , ls_denmun, ls_codestdestino, ls_denest, ls_tutordestino, ls_carredestino, ls_univdestino, ls_fecfin, ls_fecfindestino, ls_nomtutor,ls_nomtutordestino,ls_apetutor,ls_ejecucion)
	
	{
		if (opener.document.form1.hidcontrol.value=="1") {
			obj=eval("opener.document.form1."+ls_nrodestino+"");
			obj.value=ls_nropas;
			obj1=eval("opener.document.form1."+ls_ceddestino+"");
			obj1.value=ls_cedpas;
			if (ls_apepas!='0') {
			obj2=eval("opener.document.form1."+ls_nomdestino+"");
			obj2.value=ls_nompas+" "+ls_apepas;
			}
			else 
			{
			obj2=eval("opener.document.form1."+ls_nomdestino+"");
			obj2.value=ls_nompas;
			}
			obj3=eval("opener.document.form1."+ls_fecinidestino+"");
			obj3.value=ls_fecini;
		
			
		close();
			
		}
		
		else 
		{
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nropas;
		
		obj1=eval("opener.document.form1."+ls_ceddestino+"");
		obj1.value=ls_cedpas;
		
		obj2=eval("opener.document.form1."+ls_fecinidestino+"");
		obj2.value=ls_fecini;
		
		
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nompas;
		
	
		obj4=eval("opener.document.form1."+ls_apedestino+"");
		obj4.value=ls_apepas;
	
		
		
		obj5=eval("opener.document.form1."+ls_sexdestino+"");
		obj5.value=ls_sexpas;
		
		obj6=eval("opener.document.form1."+ls_fecnacdestino+"");
		obj6.value=ls_fecna;
		
		obj7=eval("opener.document.form1."+ls_telhdestino+"");
		obj7.value=ls_telhab;
		
		obj8=eval("opener.document.form1."+ls_emadestino+"");
		obj8.value=ls_email;
	
		obj9=eval("opener.document.form1."+ls_dirdestino+"");
		obj9.value=ls_dirpas;
		
		obj10=eval("opener.document.form1."+ls_telmdestino+"");
		obj10.value=ls_telmov;
		
		obj11=eval("opener.document.form1."+ls_estdestino+"");
		obj11.value=ls_edociv;
		
		obj12=eval("opener.document.form1."+ls_codestdestino+"");
		obj12.value=ls_denest;
		
		obj13=eval("opener.document.form1."+ls_tutordestino+"");
		obj13.value=ls_tutor;
	
        obj14=eval("opener.document.form1."+ls_univdestino+"");
		obj14.value=ls_univ;
		
		obj15=eval("opener.document.form1."+ls_carredestino+"");
		obj15.value=ls_carre;
		
		obj16=eval("opener.document.form1."+ls_fecfindestino+"");
		obj16.value=ls_fecfin;
		
		if (ls_apetutor!='0')
		{
		obj17=eval("opener.document.form1."+ls_nomtutordestino+"");
		obj17.value=(ls_nomtutor+" "+ls_apetutor);
		}
		else
		{
		obj17=eval("opener.document.form1."+ls_nomtutordestino+"");
		obj17.value=ls_nomtutor;
		}
		
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
	
		opener.document.form1.txtnropas.readOnly=true;
	    opener.document.form1.hidcodpar.value=ls_codpar;
	    opener.document.form1.hidcodmun.value=ls_denmun;
   	    ue_cambioestado2 (ls_denest, ls_denmun);
	    setTimeout(close,800);
			
	
		}
	      
			
			
			
			
		 	
}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
		
		
	
function Limpiar_busqueda () 
{
	$('txtnropas').value="";
	$('txtfecinides').value=$('txtfechades2').value;
	$('txtfecinihas').value=$('txtfechahas2').value;
	$('txtnompas').value="";
	$('txtapepas').value="";
	$('txtcedpas').value="";
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
		

	