
var url= "../../php/sigesp_srh_a_odi.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_odi.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_odi.php";
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
			mygrid.setHeader("Nro Registro,Codigo Personal,Nombre y Apellido,Fecha");
			mygrid.setInitWidths("100,100,200,100");
			mygrid.setColAlign("center,center, center,center");
			mygrid.setColTypes("link,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
			///mygrid.loadXML(loadDataURL);
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
		 mygrid.loadXML("../../php/sigesp_srh_a_odi.php?valor=buscar"+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtnroreg="+nroreg);
		 setTimeout (terminar_buscar,650);
	 }

}
	
	


	
function aceptar (ls_nroreg, ls_codper, ls_fecha, ls_apeper, ls_nomper,ls_codeva, ls_nomeva, ls_apeeva, ls_carper, ls_obj, ls_nroregdestino, ls_codperdestino, ls_fechadestino,  ls_nomdestino, ls_codevadestino, ls_nomevadestino, ls_carperdestino, ls_objdestino, ls_fecini1, ls_fecfin1, ls_fecini2, ls_fecfin2, ls_fecini1destino, ls_fecfin1destino,ls_fecini2destino, ls_fecfin2destino, li_total,ls_totaldestino, ls_careva, ls_carevadestino)
	{
	
		if (opener.document.form1.hidcontrol2.value=='1')
		{
			obj=eval("opener.document.form1."+ls_nroregdestino+"");
			obj.value=ls_nroreg;
		}
		else {
			obj=eval("opener.document.form1."+ls_nroregdestino+"");
			obj.value=ls_nroreg;
			
			obj1=eval("opener.document.form1."+ls_codperdestino+"");
			obj1.value=ls_codper;
			
			obj2=eval("opener.document.form1."+ls_fechadestino+"");
			obj2.value=ls_fecha;
				
				
			if (ls_apeper!='0') 	{	
				
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper+' '+ls_apeper;
			}
			else 
			{	
				
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper;
			}
			
			obj5=eval("opener.document.form1."+ls_carperdestino+"");
			obj5.value=ls_carper;
			
			obj6=eval("opener.document.form1."+ls_objdestino+"");
			obj6.value=ls_obj;
			
			obj7=eval("opener.document.form1."+ls_fecini1destino+"");
			obj7.value=ls_fecini1;
			
			obj8=eval("opener.document.form1."+ls_fecfin1destino+"");
			obj8.value=ls_fecfin1;
			
			obj9=eval("opener.document.form1."+ls_fecini2destino+"");
			obj9.value=ls_fecini2;
			
			obj10=eval("opener.document.form1."+ls_fecfin2destino+"");
			obj10.value=ls_fecfin2;
			
			obj11=eval("opener.document.form1."+ls_totaldestino+"");
			obj11.value=li_total;
			
			 if (ls_apeeva!='0') 	{	
				
			obj12=eval("opener.document.form1."+ls_nomevadestino+"");
			obj12.value=ls_nomeva+' '+ls_apeeva;
			}
			else 
			{	
				
			obj12=eval("opener.document.form1."+ls_nomevadestino+"");
			obj12.value=ls_nomeva;
			}
			
			obj13=eval("opener.document.form1."+ls_codevadestino+"");
			obj13.value=ls_codeva;
			
			obj14=eval("opener.document.form1."+ls_carevadestino+"");
			obj14.value=ls_careva;
		
	
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
			
		    opener.document.form1.txtcodper.readOnly=true;
			opener.document.form1.txtfecha.readOnly=true;
			opener.document.form1.txtnroreg.readOnly=true;
			
		    opener.document.form1.hidguardar.value = "modificar";
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_odi.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
		}
		close ();
		
}
	
	
	
function Limpiar_busqueda () 
{
	
	document.form1.txtfechades.value=document.form1.txtfecdes2.value;
	document.form1.txtfechahas.value=document.form1.txtfechas2.value;
	document.form1.txtnroreg.value="";
	
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
		
