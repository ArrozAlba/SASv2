
var url= "../../php/sigesp_srh_a_revisiones_odi.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_revisiones_odi.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_revisiones_odi.php";
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
			mygrid.setHeader("Nro. Revisión,Codigo,Nombre y Apellido,Fecha");
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


	nroreg=document.form1.txtnroreg.value;
	fechades=document.form1.txtfechades.value;
	fechahas=document.form1.txtfechahas.value;
	valfecha=ue_comparar_fechas(fechades,fechahas);
	if (!valfecha)
	 {
		alert ('Rango de Fecha Invalido.');	 
	 }
	 else
	 {
	
		mygrid.clearAll();
		divResultado = document.getElementById('mostrar');
		divResultado.innerHTML= img; 
		mygrid.loadXML("../../php/sigesp_srh_a_revisiones_odi.php?valor=buscar"+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtnroreg="+nroreg);
		setTimeout (terminar_buscar,650);
	 }

}

	


	
function aceptar ( ls_nroreg, ls_fecha, ls_apeper, ls_nomper, ls_codper,ls_apeeva, ls_nomeva, ls_codeva, ls_careva,ls_fecini,ls_fecfin, ls_rev, ls_nrodestino,ls_codperdestino, ls_fechadestino,  ls_nomdestino, ls_fecinidestino,  ls_fecfindestino, ls_codevadestino,ls_nomevadestino,ls_carevadestino,ls_revdestino, ls_carper,ls_carperdestino)
	{
	
		obj0=eval("opener.document.form1."+ls_nrodestino+"");
		obj0.value=ls_nroreg;
		
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
		
		obj5=eval("opener.document.form1."+ls_carevadestino+"");
		obj5.value=ls_careva;
		
		
		 if (ls_apeeva!='0') 	{	
			
		obj6=eval("opener.document.form1."+ls_nomevadestino+"");
		obj6.value=ls_nomeva+' '+ls_apeeva;
		}
		else 
		{	
			
		obj6=eval("opener.document.form1."+ls_nomevadestino+"");
		obj6.value=ls_nomeva;
		}
		
		obj7=eval("opener.document.form1."+ls_fecinidestino+"");
		obj7.value=ls_fecini;
		
		obj8=eval("opener.document.form1."+ls_fecfindestino+"");
		obj8.value=ls_fecfin;
		
		obj9=eval("opener.document.form1."+ls_carevadestino+"");
		obj9.value=ls_careva;
		
		obj10=eval("opener.document.form1."+ls_codevadestino+"");
		obj10.value=ls_codeva;
		
		obj10=eval("opener.document.form1."+ls_revdestino+"");
		obj10.value=ls_rev;
		
	
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
		
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_revisiones_odi.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
			close ();
}
	
	
	
function Limpiar_busqueda () 
{
	document.form1.txtnroreg.value="";
	document.form1.txtfechades.value=document.form1.txtfecdes2.value;
	document.form1.txtfechahas.value=document.form1.txtfechas2.value;
	
}

function catalogo_registro_odi ()
{
    
   pagina="../catalogos/sigesp_srh_cat_odi.php?valor_cat=0";

  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
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
		
