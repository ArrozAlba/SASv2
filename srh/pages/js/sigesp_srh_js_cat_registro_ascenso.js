
var url= "../../php/sigesp_srh_a_requisitos_minimos.php";
var metodo='get';
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";

	
		var loadDataURL = "../../php/sigesp_srh_a_registro_ascenso.php?valor=createXML";
		var actionURL = "../../php/sigesp_srh_a_registro_ascenso.php";
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
			mygrid.setHeader("Nro.,Cod. Concurso,Descripción,Cod. Personal,Nombre y Apellido");
			mygrid.setInitWidths("95,95,110,90,145")
			mygrid.setColAlign("center,center,center,center,center")
			mygrid.setColTypes("link,ro,ro,ro,ro");
			mygrid.setColSorting("str,str,str,str,str")//nuevo  ordenacion
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
		 codcon=document.form1.txtcodcon.value;
		
		 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_registro_ascenso.php?valor=buscar"+"&txtcodcon="+codcon+"&txtnroreg="+nroreg);
		setTimeout (terminar_buscar,650);

			
}
		
	
function Limpiar_busqueda () 
{
	document.form1.txtnroreg.value="";
	document.form1.txtcodcon.value="";
	document.form1.txtdescon.value="";	
}
		
		
		
function aceptar(ls_nroreg, ls_fecha,ls_codcon, ls_descon, ls_cargo,ls_reqmin, ls_codper, ls_nomper, ls_apeper, ls_caract, ls_fecing, ls_obs, ls_codsup, ls_nomsup, ls_apesup, ls_codcarsup, ls_opi, ls_nroregdestino, ls_fechadestino, ls_codcondestino, ls_descondestino, ls_cargodestino, ls_reqmindestino, ls_codperdestino, ls_nomperdestino, ls_caractdestino, ls_fecingdestino, ls_obsdestino, ls_codsupdestino, ls_nomsupdestino, ls_codcarsupdestino, ls_opidestino, ls_descar, ls_descardestino)
	{
		
		  tipo=document.form1.hidtipo.value;
		  if (tipo=='cat')
		  {
			  obj=eval("opener.document.form1."+ls_nroregdestino+"");
			  obj.value=ls_nroreg;
		  }
		  else
		  {
		  
			  if (opener.document.form1.hidcontrol.value=="2") {
				  
				obj=eval("opener.document.form1."+ls_nroregdestino+"");
				obj.value=ls_nroreg;
				obj1=eval("opener.document.form1."+ls_fechadestino+"");
				obj1.value=ls_fecha;
				obj2=eval("opener.document.form1.txtdescar");
				obj2.value=ls_descar;
				obj3=eval("opener.document.form1."+ls_codperdestino+"");
				obj3.value=ls_codper;
				 if (ls_apeper!='0') {
					obj4=eval("opener.document.form1."+ls_nomperdestino+"");
					obj4.value=ls_nomper+' '+ls_apeper;
				}
				else {
					obj4=eval("opener.document.form1."+ls_nomperdestino+"");
					obj4.value=ls_nomper;
				}
				
				obj5=eval("opener.document.form1."+ls_caractdestino+"");
				obj5.value=ls_caract;
				
				  
				 
				}	
			
			else
			{
				obj=eval("opener.document.form1."+ls_nroregdestino+"");
				obj.value=ls_nroreg;
				obj=eval("opener.document.form1."+ls_fechadestino+"");
				obj.value=ls_fecha;
				
				obj=eval("opener.document.form1."+ls_codcondestino+"");
				obj.value=ls_codcon;
				obj1=eval("opener.document.form1."+ls_descondestino+"");
				obj1.value=ls_descon;
				obj2=eval("opener.document.form1."+ls_descardestino+"");
				obj2.value=ls_descar;
				obj4=eval("opener.document.form1."+ls_reqmindestino+"");
				obj4.value=ls_reqmin;
				obj5=eval("opener.document.form1."+ls_codperdestino+"");
				obj5.value=ls_codper;
				 if (ls_apeper!='0') {
					obj6=eval("opener.document.form1."+ls_nomperdestino+"");
					obj6.value=ls_nomper+' '+ls_apeper;
				}
				else {
					obj6=eval("opener.document.form1."+ls_nomperdestino+"");
					obj6.value=ls_nomper;
				}
				
				obj7=eval("opener.document.form1."+ls_caractdestino+"");
				obj7.value=ls_caract;
				
				obj8=eval("opener.document.form1."+ls_fecingdestino+"");
				obj8.value=ls_fecing;
			
			
				obj10=eval("opener.document.form1."+ls_obsdestino+"");
				obj10.value=ls_obs;
				
				obj11=eval("opener.document.form1."+ls_codsupdestino+"");
				obj11.value=ls_codsup;
				
				obj12=eval("opener.document.form1."+ls_codsupdestino+"");
				obj12.value=ls_codsup;
				
				 if (ls_apesup!='0') {
					obj13=eval("opener.document.form1."+ls_nomsupdestino+"");
					obj13.value=ls_nomsup+' '+ls_apesup;
				}
				else {
					obj13=eval("opener.document.form1."+ls_nomsupdestino+"");
					obj13.value=ls_nomsup;
				}
				
				obj14=eval("opener.document.form1."+ls_codcarsupdestino+"");
				obj14.value=ls_codcarsup;
				
				obj15=eval("opener.document.form1."+ls_opidestino+"");
				obj15.value=ls_opi;
				
					
				ls_ejecucion=document.form1.hidstatus.value
				if(ls_ejecucion=="1")
				{
				 opener.document.form1.hidguardar.value = "modificar";
				 opener.document.form1.hidstatus.value="C";	
				}else{
				 opener.document.form1.hidguardar.value = "insertar";	
				 opener.document.form1.hidstatus.value="";
					
				}
			
				opener.document.form1.txtnroreg.readOnly=true;
			}
		  }
		  
		  close();
	}
	
	
function catalogo_concurso()
{
      f= document.form1;

     if(f.hidstatus.value=='C')
  {
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=0";
  
  }else
  {
   pagina="../catalogos/sigesp_srh_cat_concurso.php?valor_cat=1";
  } 
  window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
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
		

