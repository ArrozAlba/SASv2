var url= "../../php/sigesp_srh_a_enfermedades.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_enfermedades.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_enfermedades.php";
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
			mygrid.setHeader("Numero,Fecha,Codigo,Nombre y Apellido");
			mygrid.setInitWidths("100,100,100,205");
			mygrid.setColAlign("center,center,center,center,center");
			mygrid.setColTypes("link,ro,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF,#FFFFFF");
			//mygrid.loadXML(loadDataURL);
			mygrid.setSkin("xp");
			mygrid.init();
			

		}
		
function terminar_buscar ()
{ 
   divResultado = document.getElementById('mostrar');
   divResultado.innerHTML= '';   
}	
		

function Limpiar_busqueda ()
{
      document.form1.txtnroreg.value="";
	  document.form1.txtcodper.value="";
	  document.form1.txtnomper.value="";
	  document.form1.txtapeper.value="";	
}
		
function Buscar()
		{
		
		 nroreg=document.form1.txtnroreg.value;
		 codper=document.form1.txtcodper.value;
		 nomper=document.form1.txtnomper.value;
		 apeper=document.form1.txtapeper.value;
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_enfermedades.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtcodper="+codper+"&txtnomper="+nomper+"&txtapeper="+apeper);
		  setTimeout (terminar_buscar,650);

		}
	
	



		
function aceptar ( ls_nroreg ,  ls_codper ,  ls_fecelab ,  ls_apeper ,  ls_nomper ,  ls_codenf ,  ls_denenf , ls_fecini ,  ls_obs , ls_rep ,   ls_nrodestino ,  ls_coddestino ,  ls_fecelabdestino ,  ls_nomdestino ,  ls_codenfdestino ,  ls_desdestino , ls_fecinidestino ,  ls_obsdestino ,  ls_repdestino, ls_ejecucion)
	{
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nroreg;
		
		obj1=eval("opener.document.form1."+ls_coddestino+"");
		obj1.value=ls_codper;
		
		obj2=eval("opener.document.form1."+ls_fecelabdestino+"");
		obj2.value=ls_fecelab;
		
			
	    if (ls_apeper!='0') 	{	
			
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nomper+' '+ls_apeper;
		}
		else 
		{	
			
		obj4=eval("opener.document.form1."+ls_nomdestino+"");
		obj4.value=ls_nomper;
		}
		
				
		obj5=eval("opener.document.form1."+ls_codenfdestino+"");
		obj5.value=ls_codenf;
		
		obj6=eval("opener.document.form1."+ls_desdestino+"");
		obj6.value=ls_denenf;
		
		obj7=eval("opener.document.form1."+ls_fecinidestino+"");
		obj7.value=ls_fecini;
		
		obj8=eval("opener.document.form1."+ls_obsdestino+"");
		obj8.value=ls_obs;
					
		obj9=eval("opener.document.form1."+ls_repdestino+"");
		obj9.value=ls_rep;
		
		
	
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
			
		    opener.document.form1.txtnroreg.readOnly=true;
		
			
			close ();

}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}