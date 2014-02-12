/***********************************************************************************
* @Javascript para el manejo de la cabecera de los Módulos
* @fecha de creación: 07/10/2008
* @autor: Ing. Yesenia Moreno de Lang
* **************************
* @fecha modificacion  
* @autor  
* @descripcion  
***********************************************************************************/
function cargarCabecera()
{
	rutaarchivo ='../../controlador/sss/sigesp_ctr_sss_seguridad.php';
   	Ext.QuickTips.init();
	var objmenu ={
		'operacion': 'cabecera',
		'codsis': sistema
	};
	objmenu=JSON.stringify(objmenu);
	parametros = 'objdata='+objmenu; 
	Ext.Ajax.request({
	url : rutaarchivo,
	params : parametros,
	method: 'POST',
	success: function (resultado, request)
	{ 
		obj   = eval('('+resultado.responseText+')');
		if(obj.raiz[0].valido==true)
		{
			divsistema = document.getElementById('sistema');
			divusuario = document.getElementById('usuario');
			divhora = document.getElementById('hora');
			divinactivo = document.getElementById('inactivo');
			divsistema.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+obj.raiz[0].nomsis;
			divusuario.innerHTML = obj.raiz[0].apeusu+', '+obj.raiz[0].nomusu+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			divhora.innerHTML = obj.raiz[0].fecha+'&nbsp;&nbsp;&nbsp;&nbsp;';
			divinactivo.innerHTML = 'Tiempo de inactividad '+obj.raiz[0].inactivo+' min';
		}
		else
		{
			Ext.MessageBox.alert('Error', obj.raiz[0].mensaje);
			setTimeout('volverEscritorio()',5000);
		}
	},
	failure: function (result,request) 
	{ 
		Ext.MessageBox.alert('Error', 'No se pudo Cargar el sistema. Favor Contacte al administrador del Sistema.');
		setTimeout('volverEscritorio()',5000);
	}
	});
}


/***********************************************************************************
* @Función para regresar al escritorio
* @parametros: 
* @retorno: 
* @fecha de creación: 14/10/2008
* @autor: Ing. Yesenia Moreno.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
function volverEscritorio()
{
	parent.location.target='_parent';
	parent.location.href='../../escritorio.html';
}