
var url= "../../php/sigesp_srh_a_llamada_atencion.php";
var metodo='get';
var img="<img src=../../../public/imagenes/progress.gif> ";


var loadDataURL = "../../php/sigesp_srh_a_llamada_atencion.php?valor=createXML";
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
			
			mygrid = new dhtmlXGridObject('gridbox');
		 	mygrid.setImagePath("../../../public/imagenes/"); 
			//set columns properties
			mygrid.setHeader("Numero,Fecha,Codigo,Nombre y Apellido,Causa");
			mygrid.setInitWidths("80,80,80,195,165");
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

function  Limpiar_busqueda ()

 {
    document.form1.txtnrollam.value="";
	document.form1.txtcodper.value="";
	document.form1.txtnomper.value="";
	document.form1.txtapeper.value="";
}
		
function Buscar()
		{
		
		 nrollam=document.form1.txtnrollam.value;
		 codtrab=document.form1.txtcodper.value;
		 nomtrab=document.form1.txtnomper.value;
		 apetrab=document.form1.txtapeper.value;
		 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_llamada_atencion.php?valor=buscar"+"&txtnrollam="+nrollam+"&txtcodper="+codtrab+"&txtnomper="+nomtrab+"&txtapeper="+apetrab);
		  setTimeout (terminar_buscar,650);

		}
	
	



		
function aceptar (ls_nrollam, ls_codtrab, ls_fecllam, ls_apetrab, ls_nomtrab, ls_des,ls_cargo, ls_coduniad,  ls_nrodestino, ls_coddestino, ls_fecllamdestino,  ls_nomdestino, ls_desdestino,ls_cardestino, ls_coduniaddestino, ls_causa, ls_causadestino, ls_tipo,ls_tipodestino)
	{
		
		obj=eval("opener.document.form1."+ls_nrodestino+"");
		obj.value=ls_nrollam;
		
		obj1=eval("opener.document.form1."+ls_coddestino+"");
		obj1.value=ls_codtrab;
		
		obj2=eval("opener.document.form1."+ls_fecllamdestino+"");
		obj2.value=ls_fecllam;
			
			
	    if (ls_apetrab!='0') 	{	
			
		obj3=eval("opener.document.form1."+ls_nomdestino+"");
		obj3.value=ls_nomtrab+' '+ls_apetrab;
		}
		else 
		{	
			
		obj3=eval("opener.document.form1."+ls_nomdestino+"");
		obj3.value=ls_nomtrab;
		}
		
		obj4=eval("opener.document.form1."+ls_desdestino+"");
		obj4.value=ls_des;
		
		obj5=eval("opener.document.form1."+ls_cardestino+"");
		obj5.value=ls_cargo;
		
		obj6=eval("opener.document.form1."+ls_coduniaddestino+"");
		obj6.value=ls_coduniad;
		
		obj7=eval("opener.document.form1."+ls_causadestino+"");
		obj7.value=ls_causa;
		
		obj8=eval("opener.document.form1."+ls_tipodestino+"");
		obj8.value=ls_tipo;
		
		
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
			
		    opener.document.form1.txtnrollam.readOnly=true;
		
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_llamada_atencion.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();
			opener.document.form1.txtnrollam.readOnly=true;
			close ();			
	
		
		 	
}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}