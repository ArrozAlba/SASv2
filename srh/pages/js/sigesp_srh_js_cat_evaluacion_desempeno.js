
var url= "../../php/sigesp_srh_a_evaluacion_desempeno.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_evaluacion_desempeno.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_evaluacion_desempeno.php";
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
			mygrid.setHeader("Nro. Evaluación,Codigo,Nombre y Apellido,Fecha");
			mygrid.setInitWidths("100,100,200,100");
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


	 nroeval=document.form1.txtnroeval.value;
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
		 mygrid.loadXML("../../php/sigesp_srh_a_evaluacion_desempeno.php?valor=buscar"+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtnroeval="+nroeval);
		 setTimeout (terminar_buscar,650);
	 }

}
	
	


	
function aceptar ( ls_nroeval, ls_fecha, ls_apeper, ls_nomper, ls_codper, ls_codcarper, ls_dencarper, ls_apeeva, ls_nomeva, ls_codeva, ls_codcareva, ls_dencareva, ls_apesup, ls_nomsup, ls_codsup, ls_codcarsup, ls_dencarsup ,  li_resodi, li_rescom, li_total, ls_ranact, ls_obs, ls_opi , ls_nroevaldestino, ls_fechadestino,  ls_nomperdestino, ls_codperdestino, ls_codcarperdestino, ls_dencarperdestino, ls_nomevadestino, ls_codevadestino, ls_codcarevadestino, ls_dencarevadestino, ls_nomsupdestino, ls_codsupdestino, ls_codcarsupdestino, ls_dencarsupdestino ,  ls_resodidestino, ls_rescomdestino, ls_totaldestino, ls_ranactdestino, ls_obsdestino, ls_opidestino,ls_codeval,ls_deneval,ls_codevaldestino,ls_denevaldestino,ls_fecini,ls_fecfin,ls_fecinidestino,ls_fecfindestino)
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
		
		
		obj13=eval("opener.document.form1."+ls_codsupdestino+"");
		obj13.value=ls_codsup;
		
		
		obj14=eval("opener.document.form1."+ls_codcarsupdestino+"");
		obj14.value=ls_codcarsup;
		
				
		
		 if (ls_apesup!='0') 	{	
			
		obj16=eval("opener.document.form1."+ls_nomsupdestino+"");
		obj16.value=ls_nomsup+' '+ls_apesup;
		}
		else 
		{	
			
		obj16=eval("opener.document.form1."+ls_nomsupdestino+"");
		obj16.value=ls_nomsup;
		}
		
		
		
		obj20=eval("opener.document.form1."+ls_resodidestino+"");
		obj20.value=li_resodi;
		
		obj21=eval("opener.document.form1."+ls_rescomdestino+"");
		obj21.value=li_rescom;
		
		obj22=eval("opener.document.form1."+ls_totaldestino+"");
		obj22.value=li_total;
		
		obj23=eval("opener.document.form1."+ls_ranactdestino+"");
		obj23.value=ls_ranact;
		
		obj24=eval("opener.document.form1."+ls_obsdestino+"");
		obj24.value=ls_obs;
		
		obj25=eval("opener.document.form1."+ls_opidestino+"");
		obj25.value=ls_opi;
		
		obj26=eval("opener.document.form1."+ls_codevaldestino+"");
		obj26.value=ls_codeval;
		
		obj27=eval("opener.document.form1."+ls_denevaldestino+"");
		obj27.value=ls_deneval;

		obj28=eval("opener.document.form1."+ls_fecinidestino+"");
		obj28.value=ls_fecini;
		
		obj29=eval("opener.document.form1."+ls_fecfindestino+"");
		obj29.value=ls_fecfin;
	
	      ls_ejecucion=document.form1.hidstatus.value;
		  
		  if(ls_ejecucion=="1")
			{
			 opener.document.form1.hidguardar.value = "modificar";
			 opener.document.form1.hidstatus.value="C";			
			}
			else
			{
			  opener.document.form1.hidguardar.value = "insertar";	
			  opener.document.form1.hidstatus.value="";
			}
			
		    opener.document.form1.txtnroeval.readOnly=true;
		
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_evaluacion_desempeno.php";
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
		
