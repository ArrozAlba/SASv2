// ActionScript Remote Document
ruta = "../";
function mensajes_sigesp(titulo,texto){
	
		/*	Ext.MessageBox.show({
							   title: titulo,
							   msg: texto,
							   buttons: Ext.MessageBox.OK,
							   width: 300,
							   icon: 'sigesp_icono'
						   });
	*/
	alert(texto);
	
	}

function buscar(){
	f=document.form1;
	txtcod=f.txt_codigo.value;
	txtente=f.txt_ente.value;
	criterio = 'por_listado';
	
	datos = "catalogo=entes&txtcod="+txtcod+"&txtente="+txtente+"&criterio="+criterio;
	enviar_ajax(datos,'sigesp_sno_rajax_ente.php','resultados','POST','',ruta);

}

function aceptar(codente,ente,porcentaje)
{
	if(document.form1.tipo.value=="codentdes")
	{
		opener.document.form1.txtcodentdes.value=codente;
	
	}
	else if(document.form1.tipo.value=="codenthas")
	{
		opener.document.form1.txtcodenthas.value=codente;
		
	}
	else if(document.form1.tipo.value=="replisconc")
	{
		opener.document.form1.txtcodente.value=codente;
		opener.document.form1.operacion.value="VERIFICAR_RANGO";
		opener.document.form1.action="sigesp_sno_r_listadoconcepto.php";
		opener.document.form1.submit();
		close();
	}
	else if(document.form1.tipo.value=="repnetded")
	{
		opener.document.form1.txtcodente.value=codente;
		close();
	}
	else
	{
		opener.document.form1.txt_codente.value=codente;
		opener.document.form1.txt_ente.value=ente;
		if(opener.document.getElementById('hid_cod_ente')!=null)
		{opener.document.form1.hid_cod_ente.value=codente;}
		if(opener.document.getElementById('txt_porcentaje_ente')!=null)
		{opener.document.form1.txt_porcentaje_ente.value=porcentaje;}
	}	
	
	close();
}