
var url= "../../php/sigesp_srh_a_evaluacion_ascenso.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";


var loadDataURL = "../../php/sigesp_srh_a_evaluacion_ascenso.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_evaluacion_ascenso.php";
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
			mygrid.setHeader("Nro. Registro, Codigo,Nombre y Apellido,Fecha");
			mygrid.setInitWidths("100,100,245,100");
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
		 mygrid.loadXML("../../php/sigesp_srh_a_evaluacion_ascenso.php?valor=buscar"+"&txtfechades="+fechades+"&txtfechahas="+fechahas+"&txtnroreg="+nroreg);
		 setTimeout (terminar_buscar,650);
	 }

}
	
	


	
function aceptar ( ls_codper, ls_fecha, ls_apeper, ls_nomper, ls_caract, ls_carasc, li_res,ls_obs ,ls_codperdestino, ls_fechadestino,  ls_nomdestino, 	 ls_caractdestino, ls_carascdestino, ls_resdestino, ls_obsdestino,ls_nroreg,ls_fecreg,ls_nroregdestino,ls_fecregdestino, ls_codeval, ls_deneval, ls_denevaldestino, ls_codevaldestino, ls_ejecucion)
	{
	
		
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
		
		obj5=eval("opener.document.form1."+ls_caractdestino+"");
		obj5.value=ls_caract;
		
		obj6=eval("opener.document.form1.txtdescar");
		obj6.value=ls_carasc;
		
		obj7=eval("opener.document.form1."+ls_resdestino+"");
		obj7.value=li_res;
		
		obj7=eval("opener.document.form1."+ls_obsdestino+"");
		obj7.value=ls_obs;
		
		obj8=eval("opener.document.form1."+ls_nroregdestino+"");
		obj8.value=ls_nroreg;
		
		obj9=eval("opener.document.form1."+ls_fecregdestino+"");
		obj9.value=ls_fecreg;
		
		obj10=eval("opener.document.form1."+ls_codevaldestino+"");
		obj10.value=ls_codeval;
		
		obj11=eval("opener.document.form1."+ls_denevaldestino+"");
		obj11.value=ls_deneval;
		
	    ls_ejecucion=document.form1.hidstatus.value
		if(ls_ejecucion=="1")
		{
		 opener.document.form1.hidguardar.value = "modificar";
		 opener.document.form1.hidstatus.value="C";	
		}else{
   		 opener.document.form1.hidguardar.value = "insertar";	
		 opener.document.form1.hidstatus.value="";
		 	
		}
			
		    opener.document.form1.txtcodper.readOnly=true;
			opener.document.form1.txtfecha.readOnly=true;
		
			opener.document.form1.operacion.value="BUSCARDETALLE";
			opener.document.form1.action="../pantallas/sigesp_srh_p_evaluacion_ascenso.php";
			opener.document.form1.existe.value="TRUE";			
			opener.document.form1.submit();	
			close ();
}
	
	
	
function Limpiar_busqueda () 
{
	$('txtnroreg').value="";
	$('txtfechades').value=$('txtfecdes2').value;
	$('txtfechahas').value=$('txtfechas2').value;
	
}

function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}
		
function cat_registro_ascenso()
{
    	 
   pagina="../catalogos/sigesp_srh_cat_registro_ascenso.php?valor_cat=0"+"&tipo=cat";

  window.open(pagina,"catalogo2","menubar=no,toolbar=no,scrollbars=yes,width=540,height=400,resizable=yes,location=no,dependent=yes");
  
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
		
