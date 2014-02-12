var url= "../../php/sigesp_srh_a_accidentes.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_accidentes.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_accidentes.php";
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
			mygrid.setInitWidths("100,90,100,210");
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
		
function Buscar()
		{
		
		 nroreg=document.form1.txtnroreg.value;
		 codper=document.form1.txtcodper.value;
		 nomper=document.form1.txtnomper.value;
		 apeper=document.form1.txtapeper.value;
		
		 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_accidentes.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtcodper="+codper+"&txtnomper="+nomper+"&txtapeper="+apeper);
		  setTimeout (terminar_buscar,650);

		}
	
	
	
function Limpiar_busqueda () 
{
	document.form1.txtnroreg.value="";
	document.form1.txtcodper.value="";
	document.form1.txtnomper.value="";
	document.form1.txtapeper.value="";
		 
}


		
function aceptar ( ls_nroreg ,  ls_codper ,  ls_fecelab ,  ls_apeper ,  ls_nomper ,  ls_codacc ,  ls_denacc , ls_fecacc ,  ls_des , ls_testigos, ls_rep ,   ls_nrodestino ,  ls_coddestino ,  ls_fecelabdestino , ls_nomdestino ,  ls_codaccdestino ,  ls_denaccdestino , ls_fecaccdestino ,  ls_desdestino ,ls_testigosdestino,  ls_repdestino, ls_ejecucion)
	{
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nroreg;
		
		obj1=eval("opener.document.form1."+ls_coddestino+"");
		obj1.value=ls_codper;
		
		obj2=eval("opener.document.form1."+ls_fecelabdestino+"");
		obj2.value=ls_fecelab;
		
		if (ls_apeper!='0') {
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper+' '+ls_apeper;
		}
		else {
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper;	
		}
		obj5=eval("opener.document.form1."+ls_codaccdestino+"");
		obj5.value=ls_codacc;
		
		obj6=eval("opener.document.form1."+ls_denaccdestino+"");
		obj6.value=ls_denacc;
		
		obj7=eval("opener.document.form1."+ls_fecaccdestino+"");
		obj7.value=ls_fecacc;
		
		obj8=eval("opener.document.form1."+ls_desdestino+"");
		obj8.value=ls_des;
					
		obj9=eval("opener.document.form1."+ls_repdestino+"");
		obj9.value=ls_rep;
		
		obj10=eval("opener.document.form1."+ls_testigosdestino+"");
		obj10.value=ls_testigos;
		
		
	
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