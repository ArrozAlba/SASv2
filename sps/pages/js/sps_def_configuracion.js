// JavaScript Document

var url    = '../php/sps_def_configuracion.php';
var params = 'operacion=';
var metodo = 'get';

Event.observe(window, 'load', ue_inicializar , false);
function ue_inicializar()
{
	params = "operacion=ue_inicializar";
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
	function onInicializar(respuesta)
	{
		if (trim(respuesta.responseText)!= "")
		{  
			var arr_datos = new Array();
			arr_datos = trim(respuesta.responseText);
			var arr_datos = arr_datos.split(',');
			var cajas = new Array('hidid_art','txtporant','cmbestsue','cmbincbon','txtsc_cta_ps','txtemp_fijo_ps','txtemp_fijo_vac','txtemp_fijo_agu','txtobr_fijo_ps','txtobr_fijo_vac','txtobr_fijo_agu','txtemp_cont_ps','txtemp_cont_vac','txtemp_cont_agu','txtemp_esp_ps','txtemp_esp_vac','txtemp_esp_agu');
			for (i=0; i<cajas.length; i++)
			{	
				$(cajas[i]).value = arr_datos[i];
			}
			decimal = arr_datos[1];
			decimal = decimal.replace(".",",");
			$(cajas[1]).value = decimal; 
			
			$('hidguardar').value = "modificar";
			habilitar("txtporant,cmbestsue,cmbincbon,txtsc_cta_ps,txtemp_fijo_ps,txtemp_fijo_vac,txtemp_fijo_agu,txtobr_fijo_ps,txtobr_fijo_vac,txtobr_fijo_agu,txtemp_cont_ps,txtemp_cont_vac,txtemp_cont_agu,txtemp_esp_ps,txtemp_esp_vac,txtemp_esp_agu");
			$('txtporant').focus();
		}
		else
		{
			alert("No existen datos en Configuración.");	
			var cajas = new Array('hidid_art','txtporant','cmbestsue','cmbincbon','txtsc_cta_ps','txtemp_fijo_ps','txtemp_fijo_vac','txtemp_fijo_agu','txtobr_fijo_ps','txtobr_fijo_vac','txtobr_fijo_agu','txtemp_cont_ps','txtemp_cont_vac','txtemp_cont_agu','txtemp_esp_ps','txtemp_esp_vac','txtemp_esp_agu');
			for (i=0; i<cajas.length; i++)
			{	
				$(cajas[i]).value = "";
			}
			$('hidguardar').value = "insertar";
		}
	}
}

function ue_cancelar()
{
   document.form1.reset();
   habilitar("txtporant,cmbestsue,cmbincbon");
   scrollTo(0,0);
}

function ue_guardar()
{
	lb_valido=true;
	var la_objetos  = new Array ("txtporant","cmbestsue","cmbincbon");
	var la_mensajes = new Array("Porcentaje de Anticipo","sueldo","Manejo de incidencias");
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
		if(($F('hidguardar')== "modificar")&&($F('hidpermisos').indexOf('m',0)<0 ))
		{
			alert("No tiene permiso para actualizar información.");
		}
		else
		{
			function onGuardar(respuesta)
			{
				if(trim(respuesta.responseText) !="" )
				{alert(respuesta.responseText);}
			}
			var configuracion =
			{
				"id": "1",
				"porant":$F('txtporant'),
				"estsue":$F('cmbestsue'),
				"estincbon":$F('cmbincbon'),
				"sc_cuenta_ps":$F('txtsc_cta_ps'),
				"sig_cuenta_emp_fijo_ps":$F('txtemp_fijo_ps'),
				"sig_cuenta_emp_fijo_vac":$F('txtemp_fijo_vac'),
				"sig_cuenta_emp_fijo_agu":$F('txtemp_fijo_agu'),
				"sig_cuenta_obr_fijo_ps":$F('txtobr_fijo_ps'),
				"sig_cuenta_obr_fijo_vac":$F('txtobr_fijo_vac'),
				"sig_cuenta_obr_fijo_agu":$F('txtobr_fijo_agu'),
				"sig_cuenta_emp_cont_ps":$F('txtemp_cont_ps'),
				"sig_cuenta_emp_cont_vac":$F('txtemp_cont_vac'),
				"sig_cuenta_emp_cont_agu":$F('txtemp_cont_agu'),
				"sig_cuenta_emp_esp_ps":$F('txtemp_esp_ps'),
				"sig_cuenta_emp_esp_vac":$F('txtemp_esp_vac'),
				"sig_cuenta_emp_esp_agu":$F('txtemp_esp_agu')
				
			};
			var objeto = JSON.stringify(configuracion);
			params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		}
	}	
}

function ue_buscar_sc_cuenta()
{ 
  pagina="sps_cat_sc_cuenta.html.php";
  catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");
}
function ue_buscar_spg_cuenta(status)
{
	pagina="sps_cat_spg_cuenta.html.php";
    catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");  
	$('hidctas').value = status;
}

function ue_cargar_registro_sc_cuenta(arr_datos)
{
  var cajas = new Array('txtsc_cta_ps','txtdensc_cta_ps');
  for (i=0; i<cajas.length; i++)
  {
	  $(cajas[i]).value = arr_datos[i];
  }
  deshabilitar("txtsc_cta_ps, txtdensc_cta_ps");
  //$('hidguardar').value = "modificar";
}

function ue_cargar_registro_spg_cuenta(arr_datos)
{   
  if ($('hidctas').value=='1')
  {
	  var cajas = new Array('txtemp_fijo_ps','txtdenemp_fijo_ps');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_fijo_ps, txtdenemp_fijo_ps");
  }
  if ($('hidctas').value=='2')
  {
	  var cajas = new Array('txtemp_fijo_vac','txtdenemp_fijo_vac');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_fijo_vac, txtdenemp_fijo_vac");
  }
  if ($('hidctas').value=='3')
  {
	  var cajas = new Array('txtemp_fijo_agu','txtdenemp_fijo_agu');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_fijo_agu, txtdenemp_fijo_agu");
  }
  if ($('hidctas').value=='4')
  {
	  var cajas = new Array('txtobr_fijo_ps','txtdenobr_fijo_ps');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtobr_fijo_ps, txtdenobr_fijo_ps");
  }
  if ($('hidctas').value=='5')
  {
	  var cajas = new Array('txtobr_fijo_vac','txtdenobr_fijo_vac');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtobr_fijo_vac, txtdenobr_fijo_vac");
  }
  if ($('hidctas').value=='6')
  {
	  var cajas = new Array('txtobr_fijo_agu','txtdenobr_fijo_agu');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtobr_fijo_agu, txtdenobr_fijo_agu");
  }
  if ($('hidctas').value=='7')
  {
	  var cajas = new Array('txtemp_cont_ps','txtdenemp_cont_ps');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_cont_ps, txtdenemp_cont_ps");
  }
  if ($('hidctas').value=='8')
  {
	  var cajas = new Array('txtemp_cont_vac','txtdenemp_cont_vac');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_cont_vac, txtdenemp_cont_vac");
  }
  if ($('hidctas').value=='9')
  {
	  var cajas = new Array('txtemp_cont_agu','txtdenemp_cont_agu');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_cont_agu, txtdenemp_cont_agu");
  }
  if ($('hidctas').value=='10')
  {
	  var cajas = new Array('txtemp_esp_ps','txtdenemp_esp_ps');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_esp_ps, txtdenemp_esp_ps");
  }
  if ($('hidctas').value=='11')
  {
	  var cajas = new Array('txtemp_esp_vac','txtdenemp_esp_vac');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_esp_vac, txtdenemp_esp_vac");
  }
  if ($('hidctas').value=='12')
  {
	  var cajas = new Array('txtemp_esp_agu','txtdenemp_esp_agu');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtemp_esp_agu, txtdenemp_esp_agu");
  }
  //$('hidguardar').value = "modificar";
}
