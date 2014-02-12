
var url= "../../php/sigesp_srh_a_evaluacion_adiestramiento.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_evaluacion_adiestramiento.php?valor=createXML";
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
			mygrid.setHeader("Numero,Fecha Solicitud,Descripción,Fecha Evaluacion");
			mygrid.setInitWidths("90,90,230,90");
			mygrid.setColAlign("center,center,center,center");
			mygrid.setColTypes("link,ed,ed,ed");
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
			  mygrid.loadXML("../../php/sigesp_srh_a_evaluacion_adiestramiento.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtfechades="+fechades+"&txtfechahas="+fechahas);
			  setTimeout (terminar_buscar,650);
		 }

		}
		
		
	
function Limpiar_busqueda () 
{
	document.form1.txtnroreg.value="";
	document.form1.txtfechades.value=document.form1.txtfechades2.value;
	document.form1.txtfechahas.value=document.form1.txtfechahas2.value;	
	
}
	
	
function aceptar (ls_nroreg, ls_fecsol, ls_des, ls_codsol, ls_nomsol, ls_apesol,  ls_denuniad, ls_fecha, ls_codprov, ls_denprov, ls_fecini, ls_fecfin, ls_durhras ,  ls_costo ,  ls_obs, ls_obseval, ls_fecha , ls_nrodestino ,  ls_fecsoldestino ,  ls_desdestino ,  ls_codsoldestino ,  ls_nomdestino ,   ls_denuniaddestino , ls_fechadestino , ls_codprovdestino, ls_denprovdestino ,  ls_fecinidestino ,  ls_fecfindestino ,  ls_durhrasdestino ,  ls_costodestino ,  ls_obsdestino, ls_obsevaldestino, ls_fechadestino)
	{
		
		
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nroreg;
		
		obj0=eval("opener.document.form1."+ls_desdestino+"");
		obj0.value=ls_des;
		
		obj1=eval("opener.document.form1."+ls_fecsoldestino+"");
		obj1.value=ls_fecsol;
		
		obj2=eval("opener.document.form1."+ls_codsoldestino+"");
		obj2.value=ls_codsol;
			
		
		 if (ls_apesol!='0') 	{	
			
		obj3=eval("opener.document.form1."+ls_nomdestino+"");
		obj3.value=ls_nomsol+' '+ls_apesol;
		}
		else 
		{	
			
		obj3=eval("opener.document.form1."+ls_nomdestino+"");
		obj3.value=ls_nomsol;
		}
		
		
		obj5=eval("opener.document.form1."+ls_denuniaddestino+"");
		obj5.value=ls_denuniad;
		
		obj6=eval("opener.document.form1."+ls_fechadestino+"");
		obj6.value=ls_fecha;
		
		obj7=eval("opener.document.form1."+ls_denprovdestino+"");
		obj7.value=ls_denprov;
		
		obj8=eval("opener.document.form1."+ls_fecinidestino+"");
		obj8.value=ls_fecini;
		
		obj9=eval("opener.document.form1."+ls_fecfindestino+"");
		obj9.value=ls_fecfin;
		
		obj10=eval("opener.document.form1."+ls_durhrasdestino+"");
		obj10.value=ls_durhras;
		
		obj11=eval("opener.document.form1."+ls_costodestino+"");
		obj11.value=ls_costo;
		
		obj12=eval("opener.document.form1."+ls_obsdestino+"");
		obj12.value=ls_obs;
		
		obj13=eval("opener.document.form1."+ls_obsevaldestino+"");
		obj13.value=ls_obseval;
		
		obj14=eval("opener.document.form1."+ls_fechadestino+"");
		obj14.value=ls_fecha;
		
		obj15=eval("opener.document.form1."+ls_codprovdestino+"");
		obj15.value=ls_codprov;	
	
	      ls_ejecucion= document.form1.hidstatus.value;
		  
		  if(ls_ejecucion=="1")
			{
			 opener.document.form1.hidguardar.value = "modificar";
			  opener.document.form1.hidstatus.value="C";		
			}else
			{
		   opener.document.form1.hidguardar.value = "insertar";	
			opener.document.form1.hidstatus.value="";
			
			}
			opener.document.form1.txtnroreg.readOnly=true;
			opener.document.form1.hidguardar.value = "modificar";
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_evaluacion_adiestramiento.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();				
			close ();
			
			
			
		 	
}

function catalogo_solicitud_adiestramiento()
{
	
	window.open("../catalogos/sigesp_srh_cat_solicitud_adiestramiento.php?valor_cat=0"+"&tipo=cat","catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=680,height=500,left=50,top=50,location=no,resizable=yes");
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
		
