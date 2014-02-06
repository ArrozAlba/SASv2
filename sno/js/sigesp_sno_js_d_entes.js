// ActionScript Remote Document
var id_cargo ;
var	cargo ;
var cargo_abvr ;
var id_vent;
var K_guardar=false;
ruta = "../";

function valida_entrada(){//valida los campos de entrada de datos.
	f=document.form1;
	K_guardar=true;
	if(f.txt_codente.value == ""){		
		alert("Debe llenar el Código del ente");
		f.txt_codente.focus();
		K_guardar = false;
	}
	else if(f.txt_ente.value == ""){		
		alert("Debe llenar la Denominación el ente");
		f.txt_ente.focus();
		K_guardar = false;
	}else{
		K_guardar = true;
	}
 	return K_guardar;
}


function envia_datos(criteriox){
	
					f=document.form1;
					
					txtente=f.txt_ente.value;
					txtporc =f.txt_porcentaje_ente.value;					
					if(criteriox=="modificar" || criteriox=="eliminar"){txtcod=f.hid_cod_ente.value;}
					else{txtcod=f.txt_codente.value;}
					
					datos = "criterio=" + criteriox + "&txtcod="+txtcod+"&txtente="+txtente+"&txtporc="+txtporc;
					enviar_ajax(datos,'sigesp_sno_d_entes_ajax.php','resultados','POST','',ruta);
	
	
	}

function resultado_guardar(res){
						
				if (res=='yes'){				
					envia_datos('guardar');				
				}else{
					alert("Operación Cancelada");
					return
				}			
						
	}
function resultado_modificar(res){
						
				if (res=='yes'){
					envia_datos('modificar');
				}else{
					alert("Operación Cancelada");
					return
				}			
						
	}
function resultado_eliminar(res){
						
				if (res=='yes'){				
					envia_datos('eliminar');				
				}else{
					alert("Operación Cancelada");
					return
				}			
						
	}


function guarda_modifica(){
	f=document.form1;
	if(document.form1.hid_cod_ente.value == ""){
		
		K_guardar=valida_entrada();
		if(K_guardar){
			resultado_guardar('yes');		
		}
		else
		{
			resultado_guardar('no');
		}
		
	}else{
		
		K_guardar=valida_entrada();
		if(K_guardar)
		{
			resultado_modificar('yes');			
		}	
		else
		{
			resultado_guardar('no');	
		}
	
	}
	
}

function salirx(){
		location.href = "sigespwindow_blank.php";
}
	
function nuevox(){
		window.location='sigesp_snorh_d_entes.php';
}

function eliminar(){
	
	if(confirm("¿Desea eliminar el Registro actual?"))
	{
		resultado_eliminar('yes');
	}
	else
	{
			resultado_eliminar('no');
	}
}

function buscar(){
	window.open('sigesp_sno_cat_ente.php','','toolbar=no,directories=no,location=no, width=500, height=350, scrollbars=yes, top=100, left=100, estatus=no')
}

function blanquear_campos(){
	
	document.form1.txt_codente.value='';
	document.form1.txt_codente.disabled = false;;
	document.form1.txt_ente.value='';
	document.form1.hid_cod_ente.value='';
	document.form1.txt_porcentaje_ente.value='';
	
	}

function funcion_respuesta(x){
	
	
				switch(x){
					
					case 'cargar_id':
							//mensajes_sigesp("MENSAJE DE RESPUESTA","<b>CÓDIGO: <b>" + document.form1.id_insertado.value);
							document.form1.hid_cod_ente.value = document.form1.id_insertado.value;
					break;
					
					case 'nuevo':
							blanquear_campos();
					break;
					
					
					}
	
	
	
	}