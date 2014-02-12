
var url= "../../php/sigesp_srh_a_evaluacion_eficiencia.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_evaluacion_eficiencia.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_evaluacion_eficiencia.php";
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
	hidtipo=document.form1.hidtipo.value;
	mygrid = new dhtmlXGridObject('gridbox');
	mygrid.setImagePath("../../../public/imagenes/"); 
	//set columns properties
	mygrid.setHeader("Nro. Evaluación,Codigo,Nombre y Apellido,Fecha");
	mygrid.setInitWidths("100,100,200,100");
	mygrid.setColAlign("center,center,center,center");
	mygrid.setColTypes("link,ro,ro,ro");
	mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
	mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
	//mygrid.loadXML(loadDataURL+"&txttipo="+tipo+"&hidtipo="+hidtipo);
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
	
	nroeval=document.form1.txtnroeval.value;
	tipo=opener.document.form1.txttipo.value;
	hidtipo=document.form1.hidtipo.value;
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
		mygrid.loadXML("../../php/sigesp_srh_a_evaluacion_eficiencia.php?valor=buscar"+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtnroeval="+nroeval+"&txttipo="+tipo+"&hidtipo="+hidtipo);
		setTimeout (terminar_buscar,650);
	 }
}

	


	
function aceptar ( ls_nroeval, ls_fecha, ls_apeper, ls_nomper, ls_codper, ls_codcarper, ls_apeeva, ls_nomeva, 
				  ls_codeva, ls_codcareva, ls_fecini,ls_fecfin,    ls_obs, ls_comsup ,li_total, ls_ranact,
				  ls_nroevaldestino, ls_fechadestino,  ls_nomperdestino, ls_codperdestino, ls_codcarperdestino,
				  ls_nomevadestino, ls_codevadestino, ls_codcarevadestino,
				  ls_fecinidestino, ls_fecfindestino,  ls_obsdestino, ls_comsupdestino,
				  ls_totaldestino, ls_ranactdestino, ls_codeval, ls_deneval, ls_codevaldestino, 
				  ls_denevaldestino, ls_accion, ls_acciondestino)
	{
	
		obj0=eval("opener.document.form1."+ls_nroevaldestino+"");
		obj0.value=ls_nroeval;
		
		obj1=eval("opener.document.form1."+ls_codperdestino+"");
		obj1.value=ls_codper;
		
		obj2=eval("opener.document.form1."+ls_fechadestino+"");
		obj2.value=ls_fecha;
			
			
	    if (ls_apeper!='0') 	{	
			
		obj3=eval("opener.document.form1."+ls_nomperdestino+"");
		obj3.value=ls_nomper+' '+ls_apeper;
		}
		else 
		{	
			
		obj3=eval("opener.document.form1."+ls_nomperdestino+"");
		obj3.value=ls_nomper;
		}
		
		obj4=eval("opener.document.form1."+ls_codcarperdestino+"");
		obj4.value=ls_codcarper;
		

		obj9=eval("opener.document.form1."+ls_codcarevadestino+"");
		obj9.value=ls_codcareva;

		obj11=eval("opener.document.form1."+ls_codevadestino+"");
		obj11.value=ls_codeva;
		
		
		 if (ls_apeeva!='0') 	{	
			
		obj12=eval("opener.document.form1."+ls_nomevadestino+"");
		obj12.value=ls_nomeva+' '+ls_apeeva;
		}
		else 
		{	
			
		obj12=eval("opener.document.form1."+ls_nomevadestino+"");
		obj12.value=ls_nomeva;
		}
		
		
		obj13=eval("opener.document.form1."+ls_fecinidestino+"");
		obj13.value=ls_fecini;
		
		obj14=eval("opener.document.form1."+ls_fecfindestino+"");
		obj14.value=ls_fecfin;
		
		
		obj15=eval("opener.document.form1."+ls_obsdestino+"");
		obj15.value=ls_obs;
		
		obj16=eval("opener.document.form1."+ls_comsupdestino+"");
		obj16.value=ls_comsup;
		
		obj17=eval("opener.document.form1."+ls_totaldestino+"");
		obj17.value=li_total;
		
		obj18=eval("opener.document.form1."+ls_ranactdestino+"");
		obj18.value=ls_ranact;
		
		obj19=eval("opener.document.form1."+ls_codevaldestino+"");
		obj19.value=ls_codeval;
		
		obj20=eval("opener.document.form1."+ls_denevaldestino+"");
		obj20.value=ls_deneval;
		
		obj21=eval("opener.document.form1."+ls_acciondestino+"");
		obj21.value=ls_accion;
		
	     
		  ls_ejecucion= document.form1.hidstatus.value;
	      if(ls_ejecucion=="1")
			{
			 opener.document.form1.hidguardar.value = "modificar";
			 opener.document.form1.hidstatus.value="C";		
			}else{
			 opener.document.form1.hidguardar.value = "insertar";	
			 opener.document.form1.hidstatus.value="";
			 
			}
			
		    opener.document.form1.txtnroeval.readOnly=true;
		
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_evaluacion_eficiencia.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
			close ();
}
	
	
	
function Limpiar_busqueda () 
{
	document.form1.txtnroeval.value="";
	document.form1.txtfechades.value=document.form1.txtfechades2.value;
	document.form1.txtfechahas.value=document.form1.txtfechahas2.value;

}

function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}

function aceptar_listado (ls_nroeval,ls_nroevaldestino)
	{
	
		obj0=eval("opener.document.form1."+ls_nroevaldestino+"");
		obj0.value=ls_nroeval;
	
			close ();
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
		

	