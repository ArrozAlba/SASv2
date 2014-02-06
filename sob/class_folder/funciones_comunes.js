// JavaScript Document
function ue_guardar()
{
	f=document.form1;	
	lb_valido=true;
	var la_objetos=new Array ("txtcodcon","txtcodact","txtfeciniact","txtfecfinact","txtnominsact","txtnomresact");
	var la_mensajes=new Array ("Debe seleccionar un Contrato!!!","Debe seleccionar una nueva Acta!!!","Debe indicar una fecha de inicio!!!","Debe inidicar una fecha de finalización!!!","Debe seleccionar un Ing. Inspector!!!","Debe seleccionar un Ing. Residente!!!");
	alert("antes del for!!");
	for (li_i=0;li_i<6;li_i++)
	{
		if(ue_valida_null(eval("f."+la_objetos[li_i]),la_mensajes[li_i])==false)
		{
			alert("Entro al if");
			eval("f."+la_objetos[li_i]+".focus();");
			lb_valido=false;
			break;				
		}
	}
	if(lb_valido)
	{
		f.operacion.value="ue_guardar";
		f.action="sigesp_sob_d_actainicio.php";
		f.submit();
	}	
}