var url= "../../php/sigesp_srh_a_documentos.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_documentos.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_documentos.php";
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
			mygrid.setHeader("Numero,Denominación,Tipo Documento");
			mygrid.setInitWidths("100,200,200");
			mygrid.setColAlign("center,,center,center");
			mygrid.setColTypes("link,ro,ro,");
			mygrid.setColSorting("str,str,str");//nuevo  ordenacion
			mygrid.setColumnColor("#FFFFFF,#FFFFFF,#FFFFFF");
		//	mygrid.loadXML(loadDataURL);
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

	 nrodoc=document.form1.txtnrodoc.value;
	 dendoc=document.form1.txtdendoc.value;
	 tipdoc=document.form1.txtcodtipdoc.value;
	 
	 mygrid.clearAll();
	 divResultado = document.getElementById('mostrar');
	 divResultado.innerHTML= img; 
	 mygrid.loadXML("../../php/sigesp_srh_a_documentos.php?valor=buscar"+"&txtnrodoc="+nrodoc+"&txtdendoc="+dendoc+"&txtcodtipdoc="+tipdoc);
	 setTimeout (terminar_buscar,600);
	
}

	
function Limpiar_busqueda () 
{
	$('txtnrodoc').value="";
	$('txtdendoc').value="";
	$('txtcodtipdoc').value="";
	$('txtdentipdoc').value="";
}


function catalogo_tipocontrato()
{
     
   pagina="../catalogos/sigesp_srh_cat_tipodocumentos.php?valor_cat=1";
 
  window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}



		
function aceptar ( ls_nrodoc, ls_dendoc,   ls_codtipdoc, ls_dentipdoc,ls_accdoc,ls_dirdoc, ls_archdoc,ls_nrodestino, ls_dendocdestino,  ls_accdocdestino,  ls_codtipdocdestino, 	ls_dentipdocdestino,  ls_accdocdestino, ls_dirdocdestino, ls_archdocdestino, ls_ejecucion)
	{
		
		obj1=eval("opener.document.form1."+ls_nrodestino+"");
		obj1.value=ls_nrodoc;
		
		obj2=eval("opener.document.form1."+ls_dendocdestino+"");
		obj2.value=ls_dendoc;
		
			
		obj3=eval("opener.document.form1."+ls_codtipdocdestino+"");
		obj3.value=ls_codtipdoc;
		
		obj4=eval("opener.document.form1."+ls_dentipdocdestino+"");
		obj4.value=ls_dentipdoc;
		
		obj5=eval("opener.document.form1."+ls_accdocdestino+"");
		obj5.value=ls_accdoc;
		
		obj6=eval("opener.document.form1."+ls_dirdocdestino+"");
		obj6.value=ls_dirdoc;
		
			
		obj7=eval("opener.document.form1."+ls_archdocdestino+"");
		obj7.value=ls_archdoc;
					
	
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
		    opener.document.form1.txtnrodoc.readOnly=true;
		
			
			close ();

}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
		

function catalogo_tipodocumento()
{
   
   pagina="../catalogos/sigesp_srh_cat_tipodocumento.php?valor_cat=0";
  
  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}