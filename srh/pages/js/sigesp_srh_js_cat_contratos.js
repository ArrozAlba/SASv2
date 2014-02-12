var url= "../../php/sigesp_srh_a_contratos.php";
var metodo='get';
var img="<img src=../../../public/imagenes/progress.gif> ";


var loadDataURL = "../../php/sigesp_srh_a_contratos.php?valor=createXML";
var actionURL = "../../php/sigesp_srh_a_contratos.php";
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
			mygrid.setHeader("Numero,Fecha,Codigo,Nombre y Apellido,Tipo Contrato");
			mygrid.setInitWidths("80,70,80,170,160");
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
		 tipcon=document.form1.txtcodtipcon.value;
		 
		  mygrid.clearAll();
		  divResultado = document.getElementById('mostrar');
		  divResultado.innerHTML= img; 
		  mygrid.loadXML("../../php/sigesp_srh_a_contratos.php?valor=buscar"+"&txtnroreg="+nroreg+"&txtcodper="+codper+"&txtnomper="+nomper+"&txtapeper="+apeper+"&txtcodtipcon="+tipcon);
          setTimeout (terminar_buscar,600);

		}
	
	
function Limpiar_busqueda () 
{
	$('txtnroreg').value="";
	$('txtcodper').value="";
	$('txtnomper').value="";
	$('txtapeper').value="";
	$('txtcodtipcon').value="";
	$('txtdentipcon').value="";
}


function catalogo_tipocontrato()
{
     
   pagina="../catalogos/sigesp_srh_cat_tipocontrato.php?valor_cat=0";
 
  window.open(pagina,"catalogo1","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no,dependent=yes");
  
 
}





		
function aceptar ( ls_nroreg ,  ls_codper ,  ls_apeper ,  ls_nomper ,  ls_codtipcon ,  ls_dentipcon , ls_fecini , ls_fecfin, ls_obs , ls_des , ls_monto , ls_estado, ls_nrodestino ,  ls_coddestino ,   ls_apedestino ,   ls_nomdestino ,  ls_codtipcondestino ,  ls_dentipcondestino , ls_fecinidestino , ls_fecfindestino, ls_obsdestino ,  ls_desdestino, ls_montodestino, ls_estadodestino, ls_codcar,ls_codnom,ls_descar,ls_coduniadm,ls_denuniadm,ls_func,ls_hor, ls_codcardestino,ls_codnomdestino,ls_descardestino,ls_coduniadmdestino,ls_denuniadmdestino,ls_funcdestino,ls_hordestino,ls_apeperdestino,ls_nacperdestino,ls_nacper,ls_codprodestino,ls_codpro,ls_desprodestino,ls_despro)
	{
		
		ls_tipo=document.form1.hidtipo.value;
		if (ls_tipo=='1')
		{
			obj1=eval("opener.document.form1.txtnroregdes");
			obj1.value=ls_nroreg;
		}
		else if (ls_tipo=='2')
		{
			obj1=eval("opener.document.form1.txtnroreghas");
			obj1.value=ls_nroreg;
		}
		else
		{
		
			obj1=eval("opener.document.form1."+ls_nrodestino+"");
			obj1.value=ls_nroreg;
			
			obj2=eval("opener.document.form1."+ls_coddestino+"");
			obj2.value=ls_codper;
					
			
			obj4=eval("opener.document.form1."+ls_apeperdestino+"");
			obj4.value=ls_apeper;
			
			
			obj4=eval("opener.document.form1."+ls_nomdestino+"");
			obj4.value=ls_nomper;
			
			
			obj5=eval("opener.document.form1."+ls_codtipcondestino+"");
			obj5.value=ls_codtipcon;
			
			obj6=eval("opener.document.form1."+ls_dentipcondestino+"");
			obj6.value=ls_dentipcon;
			
			obj7=eval("opener.document.form1."+ls_fecinidestino+"");
			obj7.value=ls_fecini;
			
			obj8=eval("opener.document.form1."+ls_fecfindestino+"");
			obj8.value=ls_fecfin;
			
				
			obj9=eval("opener.document.form1."+ls_obsdestino+"");
			obj9.value=ls_obs;
						
			obj10=eval("opener.document.form1."+ls_desdestino+"");
			obj10.value=ls_des;
			
			obj11=eval("opener.document.form1."+ls_montodestino+"");
			obj11.value=ls_monto;
			
			obj12=eval("opener.document.form1."+ls_estadodestino+"");
			obj12.value=ls_estado;
			
			obj13=eval("opener.document.form1."+ls_codcardestino+"");
			obj13.value=ls_codcar;
			
			obj14=eval("opener.document.form1."+ls_codnomdestino+"");
			obj14.value=ls_codnom;
			
			obj15=eval("opener.document.form1."+ls_descardestino+"");
			obj15.value=ls_descar;
			
			obj16=eval("opener.document.form1."+ls_coduniadmdestino+"");
			obj16.value=ls_coduniadm;
			
			obj17=eval("opener.document.form1."+ls_denuniadmdestino+"");
			obj17.value=ls_denuniadm;
			
			obj18=eval("opener.document.form1."+ls_funcdestino+"");
			obj18.value=ls_func;
			
			obj19=eval("opener.document.form1."+ls_hordestino+"");
			obj19.value=ls_hor;
			
			obj19=eval("opener.document.form1."+ls_codprodestino+"");
			obj19.value=ls_codpro;
			
			obj20=eval("opener.document.form1."+ls_desprodestino+"");
			obj20.value=ls_despro;
			
			obj21=eval("opener.document.form1."+ls_nacperdestino+"");
			obj21.value=ls_nacper;
			
			
			
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
	}
		
			
	close ();

}
	
	
function nextPAge(val)
		{
			grid.clearAll(); //clear existing data
			grid.loadXML("some_url.php?page="+val);
		}