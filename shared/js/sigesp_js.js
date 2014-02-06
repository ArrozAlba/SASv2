// JavaScript Document
function mensajes_sigesp(titulo,texto){
	
			/*Ext.MessageBox.show({
							   title: titulo,
							   msg: texto,
							   buttons: Ext.MessageBox.OK,
							   width: 300,
							   icon: 'sigesp_icono'
						   });
	*/
	alert(texto);
	
	
	}
	
	
function confirmacion_sigesp(titulo,texto,funcion_x){
	
			Ext.MessageBox.show({
							   title: titulo,
							   msg: texto,
							   buttons: Ext.Msg.YESNOCANCEL,
							   width: 300,
							   fn: funcion_x,
							   icon: 'sigesp_icono'
						   });
	
	
	
	}

function sigesp_rellenar_cadena(cadena,longitud,idx,direccion)
{
	
	 if(document.getElementById(idx).value!="" && document.getElementById(idx).value>0){
		
				var mystring=new String(cadena);
				cadena_ceros="";
				lencad=mystring.length;
			
				total=longitud-lencad;
				for(i=1;i<=total;i++)
				{
					cadena_ceros=cadena_ceros+"0";
				}
				
				if(direccion=='derecha'){cadena=cadena + cadena_ceros;}
				if(direccion=='izquierda'){cadena=cadena_ceros + cadena;}	
				document.getElementById(idx).value=cadena;	
				
		}
}

function trim(cadena_s){ return cadena_s.replace(/^\s+|\s+$/g,''); }



